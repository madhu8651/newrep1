<?php
include_once (ROOT_PATH . '/core/LConnectApplicationException.php');
include_once (ROOT_PATH . '/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH . '/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH . '/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_supportrequestmodel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class manager_supportrequestmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function receviedSupport($user)
    {
       try
       {
            $module = $this->session->userdata('active_module_name');

            
                $recived = $GLOBALS['$dbFramework']->query("
                            SELECT 
                                (SELECT 
                                        CASE sod.request_for
                                                WHEN
                                                    'opportunity'
                                                THEN
                                                    (SELECT 
                                                            opportunity_name
                                                        FROM
                                                            opportunity_details AS od
                                                        WHERE
                                                            od.opportunity_id = sod.opp_cust_id)
                                                ELSE (SELECT 
                                                        customer_name
                                                    FROM
                                                        customer_info AS ci
                                                    WHERE
                                                        ci.customer_id = sod.opp_cust_id)
                                            END
                                    ) AS supportAssociatedName,
                                sod.closed_reason AS closed_reason,sod.closed_status AS closed_status,sod.opp_cust_id AS opp_cust_id,
                                sod.owner_id,sod.manager_owner_id,
                                sod.process_type,sod.request_contact,
                                cd.contact_name,ud.user_name AS requestOwnerName,
                                mud.user_name AS requestManagerName,
                                sod.request_product,hPro.hvalue2 AS productName,
                                sod.request_industry,hInd.hvalue2 AS industryName,
                                sod.request_location,hLoc.hvalue2 AS locationName,
                                sod.cricticality,sod.owner_status,
                                sod.owner_manager_status,sod.process_type as process_type,
                                sod.request_numbers,sod.request_tat,sod.request_user_id as ticketId,coalesce(sod.request_tat,'') as TAT,pt.lookup_value as processTypeName,
                                sod.request_name as SupportRequestName,
                                IF(sod.manager_owner_id IS NOT NULL,
                                    (SELECT 
                                            user_name
                                        FROM
                                            user_details
                                        WHERE
                                            user_id = sod.manager_owner_id),
                                    'pending') AS manager_owner_name,
                                (SELECT 
                                        COUNT(*)
                                    FROM
                                        support_user_map
                                    WHERE
                                        request_id = sod.request_id
                                            AND from_user_id = '$user'
                                            AND state = 1
                                            AND action = 'ownership rejected') AS ownerReject,
                            IF(sod.stage_owner_id IS NOT NULL,
                                    (SELECT 
                                            user_name
                                        FROM
                                            user_details
                                        WHERE
                                            user_id = sod.stage_owner_id),
                                    'pending') AS stage_manager_owner_name,
                                (SELECT 
                                        COUNT(*)
                                    FROM
                                        support_user_map
                                    WHERE
                                        request_id = sod.request_id
                                            AND from_user_id = '$user'
                                            AND state = 1
                                            AND action = 'stage rejected') AS ownerReject
                                            
                            FROM
                                support_opportunity_details AS sod
                                    LEFT JOIN
                                hierarchy hInd ON sod.request_industry = hInd.hkey2
                                    LEFT JOIN
                                hierarchy hLoc ON sod.request_location = hLoc.hkey2
                                    LEFT JOIN
                                hierarchy hPro ON sod.request_product = hPro.hkey2,
                                contact_details AS cd,
                                hierarchy AS phi,
                                support_sales_cycle AS ssc,
                                support_sales_stage AS sss,
                                user_details AS ud,
                                user_details AS mud,
                                support_user_map AS sm,
                                lookup as pt
                            WHERE
                                sod.request_stage = sss.stage_id
                                    AND sod.cycle_id = ssc.CYCLE_ID
                                    AND sod.owner_id = ud.user_id
                                    AND sod.request_contact = cd.contact_id
                                    AND sod.request_product = phi.hkey2
                                    AND sod.closed_status != '100'
                                    AND sod.closed_reason IS NULL
                                    AND sod.request_id = sm.request_id
                                    AND sm.action IN ('stage_assigned')
                                    AND sm.to_user_id = '$user'
                                    AND sod.process_type = pt.lookup_id
                            GROUP BY sod.request_id
                                                    "); 
            
            return $recived->result();

       }
       catch (LConnectApplicationException $e) 
       {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
       } 

    }


    public function associatedProductContacts($matchedId,$type) {
        try {

            if ($type == 'customer') 
            {
                $customerProduct = $GLOBALS['$dbFramework']->query("
                                                        SELECT 
                                                        a.product_id AS productId, b.hvalue2 AS productName
                                                        FROM
                                                        product_purchase_info a,
                                                        hierarchy b
                                                        WHERE
                                                        a.product_id = b.hkey2
                                                        AND customer_id = '$matchedId'
                                                        GROUP BY b.hkey2
                                                    ");


                $customerContact = $GLOBALS['$dbFramework']->query("
                    SELECT cd.contact_id AS contact_id, cd.contact_name AS contact_name,cd.contact_email
                    FROM customer_info AS ci, contact_details AS cd
                    WHERE (ci.customer_id='$matchedId') AND 
                    ((ci.customer_id=cd.lead_cust_id OR ci.lead_id = cd.lead_cust_id))
                    GROUP BY cd.contact_id
                    ORDER BY cd.contact_name
                ");

                $busNumRows = $this->getBusinessLocation($matchedId);
                $indNumRows = $this->getIndustry($matchedId);

                $cusBus = ($busNumRows['count'] > 0)?TRUE:FALSE;
                $cusInd = ($indNumRows['count'] > 0)?TRUE:FALSE;

                return array('product'=>$customerProduct->result(),
                            'contact'=>$customerContact->result(),
                            'businessLocation'=>$cusBus,
                            'industry'=>$cusInd
                            );
            }
            else
            {
                $opportunityProduct = $GLOBALS['$dbFramework']->query("
                                                            SELECT a.opportunity_product as prod_id,b.hvalue2 as prod_name from opportunity_details a ,hierarchy b where
                                                            a.opportunity_product=b.hkey2 and opportunity_id='$matchedId'
                                                    ");

                $opportunityContact =$GLOBALS['$dbFramework']->query("
                                                            SELECT od.opportunity_id, od.opportunity_name, cd.contact_name,
                                                            cd.contact_number->'$.phone[0]' AS employeephone1, cd.contact_id,cd.contact_email 
                                                            FROM opportunity_details AS od 
                                                            left join contact_details cd on find_in_set(contact_id, replace(od.opportunity_contact,':',','))
                                                            WHERE (od.opportunity_id='$matchedId')
                                                    ");

                return array(
                            'product'=>$opportunityProduct->result(),
                            'contact'=>$opportunityContact->result(),
                            'businessLocation'=>true,
                            'industry'=>true
                        );
            }
            
        } 
        catch (LConnectApplicationException $e) 
        {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    } 

    public function getIndustry($id)
    {
       try
       {
            $response = array();
            $isIndustry = $GLOBALS['$dbFramework']->query("
                                            SELECT 
                                            customer_industry
                                            FROM
                                            customer_info
                                            WHERE
                                            customer_industry IS NOT NULL
                                            AND customer_id = '$id'
                                        ");

            $count  = $isIndustry->num_rows();
            $result = $isIndustry->result();

            $response['count'] = $count;
            $response['value'] = ($count > 0) ? $result[0]->customer_industry:'';

            return $response;
            
       }
       catch (LConnectApplicationException $e) 
        {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    } 

    public function getBusinessLocation($id)
    {
       try
       {
            $response = array();
            $isBusinessLocation = $GLOBALS['$dbFramework']->query("
                                            SELECT 
                                            customer_business_loc
                                            FROM
                                            customer_info
                                            WHERE
                                            customer_business_loc IS NOT NULL
                                            AND customer_id = '$id'
                                        ");

            $count  = $isBusinessLocation->num_rows();
            $result = $isBusinessLocation->result();

            $response['count'] = $count;
            $response['value'] = ($count > 0) ? $result[0]->customer_business_loc:'';

            return $response;
            
       }
       catch (LConnectApplicationException $e) 
        {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }   

    public function associatedToData($user,$type){
        try {

            $customer = $GLOBALS['$dbFramework']->query("
                                                        (SELECT 
                                                        ci.customer_id AS leadid,
                                                        ci.customer_name AS leadname,
                                                        'customer' AS type
                                                        FROM
                                                        customer_info ci
                                                        WHERE
                                                        ci.customer_manager_owner IN ('$user')
                                                        GROUP BY ci.customer_id
                                                        ORDER BY ci.customer_name) UNION (SELECT 
                                                        ci.customer_id AS leadid,
                                                        ci.customer_name AS leadname,
                                                        'customer' AS type
                                                        FROM
                                                        oppo_user_map AS opum,
                                                        lead_info AS li,
                                                        customer_info AS ci
                                                        WHERE
                                                        opum.lead_cust_id = li.lead_id
                                                        AND li.lead_id = ci.lead_id
                                                        AND opum.to_user_id IN ('$user')
                                                        GROUP BY li.lead_id)
                                                    ");
            $opportunity = $GLOBALS['$dbFramework']->query("
                                                    SELECT 
                                                    od.opportunity_id, od.opportunity_name
                                                    FROM
                                                    opportunity_details AS od,
                                                    user_details AS ud
                                                    WHERE
                                                    (od.owner_id = ud.user_id
                                                    OR od.stage_owner_id = ud.user_id
                                                    OR od.manager_owner_id = ud.user_id
                                                    OR od.stage_manager_owner_id = ud.user_id)
                                                    AND (ud.user_id IN ('$user')
                                                    OR ud.reporting_to IN ('$user'))
                                                    GROUP BY od.opportunity_name
                                                    ");

            $processType = $GLOBALS['$dbFramework']->query("
                                                            SELECT a.lookup_id,a.lookup_value,b.map_type from lookup a, user_mappings b where a.lookup_name='support_process' and a.lookup_id=b.map_id and b.user_id='$user'
                                                        ");

            $contactType = $GLOBALS['$dbFramework']->query("
                                                            SELECT lookup_id AS contact_type_id, lookup_value AS contact_type_name 
                                                            FROM lookup 
                                                            WHERE lookup_name='Buyer Persona'
                                                        ");

            if ($type == 'customer') 
            {
                return array('customer'=>$customer->result(),'processType'=>$processType->result(),'contactType'=>$contactType->result());
            }
            else if ($type == 'opportunity') 
            {
               return array('opportunity' => $opportunity->result(),'processType'=>$processType->result(),'contactType'=>$contactType->result());
            }


        } 
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function listOfAssignee($user,$assignedAs,$requestId)
    {
       
        try
        {
            $children = $user."','";
            $children .= $this->getChildrenForParent($user);

            $stageOwners = $GLOBALS['$dbFramework']->query("
                            SELECT 
                                um.user_id,
                                ud.user_name,
                                (CASE
                                    WHEN
                                        (ul.sales_module <> '0')
                                            AND (SELECT 
                                                COUNT(oum.to_user_id)
                                            FROM
                                                support_user_map oum
                                            WHERE
                                                oum.request_id IN ('$requestId')
                                                    AND oum.action IN ('ownership accepted' , 'ownership assigned',
                                                    'ownership reassigned',
                                                    'ownership rejected')
                                                    AND oum.state = 1
                                                    AND oum.module = 'sales'
                                                    AND oum.to_user_id = um.user_id) = 0
                                    THEN
                                        1
                                    ELSE 0
                                END) AS sales_module,
                                (CASE
                                    WHEN
                                        (ul.manager_module <> '0')
                                            AND (SELECT 
                                                COUNT(oum.to_user_id)
                                            FROM
                                                support_user_map oum
                                            WHERE
                                                oum.request_id IN ('$requestId')
                                                    AND oum.action IN ('ownership assigned' , 'ownership reassigned',
                                                    'ownership accepted',
                                                    'ownership rejected')
                                                    AND oum.state = 1
                                                    AND oum.module = 'manager'
                                                    AND oum.to_user_id = um.user_id) = 0
                                    THEN
                                        1
                                    ELSE 0
                                END) AS manager_module
                            FROM
                                user_mappings um,
                                support_opportunity_details sod,
                                user_licence ul,
                                user_details ud
                            WHERE
                                um.map_type = 'product'
                                    AND sod.request_id IN ('$requestId')
                                    AND um.map_id = sod.request_product
                                    AND ul.user_id = um.user_id
                                    AND ud.user_id = um.user_id
                            GROUP BY um.user_id
                            HAVING COUNT(DISTINCT sod.request_product) = (SELECT 
                                    COUNT(DISTINCT sod.request_product)
                                FROM
                                    support_opportunity_details sod
                                WHERE
                                    sod.request_id IN ('$requestId'))
                                AND (um.user_id IN (SELECT 
                                    ud1.user_id
                                FROM
                                    support_opportunity_details sod,
                                    user_details ud1,
                                    user_mappings um1,
                                    user_mappings um2,
                                    user_mappings um3
                                WHERE
                                    (ud1.user_id IN ('$children')
                                        AND sod.request_id IN ('$requestId')
                                        AND ud1.user_state = 1
                                        AND ud1.user_id = um1.user_id
                                        AND ud1.user_id = um2.user_id
                                        AND ud1.user_id = um3.user_id
                                        AND um1.map_type = 'clientele_industry'
                                        AND um1.map_id = sod.request_industry
                                        AND um2.map_type = 'business_location'
                                        AND um2.map_id = sod.request_location)
                                ORDER BY sod.request_product))
                                ");

            $supportOwners =$GLOBALS['$dbFramework']->query("
                                                            SELECT ud.user_id, (CASE ud.user_id WHEN '$user' THEN   group_concat(ud.user_name,'( Myself )') ELSE ud.user_name END) as user_name
                                                            FROM user_details as ud
                                                            WHERE 
                                                            ud.reporting_to IN ('$children')
                                                            AND ud.user_state = 1
                                                            GROUP BY user_id
                                                            ");
            if ($assignedAs == 'supportOwners') 
            {
                return  $supportOwners->result();
                       
            }
            else if ($assignedAs == 'stageOwners') 
            {
                return $stageOwners->result();
            }
            else
            {
                return array(
                            'stageOwners'=>$stageOwners->result(),
                            'supportOwners'=>$supportOwners->result()
                        );
            }

        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }


    private function getChildrenForParent($user_id) {
        try {
                $query = $GLOBALS['$dbFramework']->query("
                SELECT user_id, reporting_to FROM user_details");
                $full_structure = $query->result();
                $allParentNodes = [];
                if (version_compare(phpversion(), '7.0.0', '<')) {
                // php version isn't high enough to support array_column
                foreach($full_structure as $row)  {
                $allParentNodes[$row->user_id] = $row->reporting_to;
                }
                } else {
                $allParentNodes = array_column(
                $full_structure, 
                'reporting_to',
                'user_id');
                }
                $childNodes = array();
                $this->fetchChildNodes($user_id, $childNodes, $allParentNodes);
                if (count($childNodes) == 0) {
                return '';
                }
                $ids = implode("','", $childNodes);
                return $ids;  
        }
         catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
      
    }
    private function fetchChildNodes($givenID, & $childNodes, $allParentNodes)  {
    	try{
	        foreach ($allParentNodes as $user_id => $reporting_to) {
	            if ($reporting_to == $givenID)  {
	                array_push($childNodes, $user_id);
	                $this->fetchChildNodes($user_id, $childNodes, $allParentNodes);                
	            }
	        }
	    } catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}   
    }

    public function unassignedRequest($user)
    {
        try 
        {
            $children = $user."','";
            $children .= $this->getChildrenForParent($user);
            $query = $GLOBALS['$dbFramework']->query("
                                        SELECT 
                                            a.request_id AS request_id,
                                            a.request_name AS request_name,
                                            a.cycle_id AS cycle_id,
                                            a.process_type AS process_type,
                                            a.request_industry AS request_industry,
                                            hInd.hvalue2 AS industry_name,
                                            a.request_location AS request_location,
                                            hLoc.hvalue2 AS location_name,
                                            a.request_user_id as ticked_id,
                                            a.request_tat as TAT,
                                            a.created_timestamp AS created_date,
                                            (SELECT 
                                                    hvalue2
                                                FROM
                                                    hierarchy
                                                WHERE
                                                    request_product = hkey2) AS product_name,
                                            b.stage_id,
                                            a.opp_cust_id,
                                            (CASE a.request_for
                                                WHEN
                                                    'customer'
                                                THEN
                                                    (SELECT DISTINCT
                                                            customer_name
                                                        FROM
                                                            customer_info
                                                        WHERE
                                                            customer_id = a.opp_cust_id)
                                                ELSE (SELECT DISTINCT
                                                        customer_name
                                                    FROM
                                                        customer_info
                                                    WHERE
                                                        customer_id = a.opp_cust_id)
                                            END) AS lead_cust_name,
                                            owner_status,
                                            stage_owner_status,
                                            (SELECT 
                                                    user_name
                                                FROM
                                                    user_details
                                                WHERE
                                                    user_id = owner_id) AS suport_rep,
                                            owner_id,
                                            owner_status AS support_repstatus,
                                            (SELECT 
                                                    user_name
                                                FROM
                                                    user_details
                                                WHERE
                                                    user_id = manager_owner_id) AS support_manager,
                                            manager_owner_id,
                                            owner_manager_status AS support_manager_status,
                                            (SELECT 
                                                    user_name
                                                FROM
                                                    user_details
                                                WHERE
                                                    user_id = stage_owner_id) AS stage_rep,
                                            stage_owner_id,
                                            stage_owner_status AS stage_repstatus,
                                            (SELECT 
                                                    user_name
                                                FROM
                                                    user_details
                                                WHERE
                                                    user_id = stage_manager_owner_id) AS stage_man,
                                            stage_manager_owner_id,
                                            stage_manager_owner_status AS stage_manstatus,
                                            to_user_id,'' as stage_name,
                                            a.request_product as product
                                        FROM
                                            support_user_map b,
                                            support_opportunity_details a
                                                LEFT JOIN
                                            hierarchy hInd ON a.request_industry = hInd.hkey2
                                                LEFT JOIN
                                            hierarchy hLoc ON a.request_location = hLoc.hkey2
                                        WHERE
                                            closed_reason IS NULL
                                                AND a.request_id = b.request_id
                                                AND b.to_user_id IN ('$children')
                                                AND module <> 'sales'
                                                AND action <> 'ownership assigned'
                                                AND ((owner_id IS NULL AND owner_status = 0)
                                                OR (stage_owner_id IS NULL
                                                AND stage_owner_status = 0))
                                                AND ACTION = 'ownership accepted'
                                        GROUP BY a.request_id
                                                    ");
            return $query->result();
        } 
        catch (LConnectApplicationException $e) 
        {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function getContactList($customerid) {
        try {
            $query = $GLOBALS['$dbFramework']->query("select contact_id,contact_name from contact_details where lead_cust_id='$customerid'");
            return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function getProcessType($userid) {
        try {
            $query = $GLOBALS['$dbFramework']->query("
                SELECT a.lookup_id,a.lookup_value,b.map_type from lookup a, user_mappings b where a.lookup_name='support_process' and a.lookup_id=b.map_id and b.user_id='$userid'
                ");
            return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function getContactListopp($oop_id) {
        try {
            $query = $GLOBALS['$dbFramework']->query("select REPLACE(opportunity_contact,':',',') as contact_id from opportunity_details where opportunity_id='$oop_id'");
            $result = $query->result();
            $contactids = $result[0]->contact_id;
            $query1 = $GLOBALS['$dbFramework']->query("select contact_id,contact_name from contact_details where contact_id in ('$contactids')");
            return $result_array = $query1->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function chk_parameters_opp($opportunity_ids, $process_type, $product_id) {
        try {
            $userid = $this->session->userdata('uid');
            $query = $GLOBALS['$dbFramework']->query("select opportunity_industry,opportunity_location from
                    opportunity_details where opportunity_id='$opportunity_ids'");
            $result = $query->result();
            $industry = $result[0]->opportunity_industry;
            $location = $result[0]->opportunity_location;

            $query1 = $GLOBALS['$dbFramework']->query("SELECT scp.cycle_id,(select ss.stage_id
                from support_stage_cycle_mapping AS scm, support_sales_stage AS ss
                where scm.cycle_id=scp.cycle_id AND ss.stage_id=scm.stage_id AND ss.stage_sequence=6) AS stage_id
                FROM `support_sales_cycle_parameters` AS scp
                WHERE scp.parameter_product_node ='$product_id' AND scp.parameter_industry_node ='$industry' AND 
                scp.parameter_location_node='$location'  AND scp.parameter_for='$process_type' AND scp.cycle_togglebit = 1
                GROUP BY scp.parameter_id, scp.cycle_id");
            $result_array = $query1->result();
            $count_row = $query1->num_rows();
            if ($count_row > 0) {
                $stage_id = $result_array[0]->stage_id;
                $cycle_id = $result_array[0]->cycle_id;
                $query2 = $GLOBALS['$dbFramework']->query("select attribute_name,REPLACE(attribute_value,':',',') as matrix 
                from support_sales_stage_attributes where attribute_name='allocation_matrix' and  stage_id='$stage_id'");
                $stage_result = $query2->result();
                $stage_count = $query2->num_rows();
                if ($stage_count > 0){
                    $contact_ids = $stage_result[0]->matrix;
                    $realArray = explode(',', $contact_ids);
                    $all_childnodes=$this->getChildrenForParent($userid);
                    $contact_name=array_intersect($realArray,$all_childnodes);
                    $contacts= implode("','", $contact_name);
                    $query2 = $GLOBALS['$dbFramework']->query("select  a.user_name, a.user_id,b.sales_module,b.manager_module,c.Department_name,d.lookup_value
                        from user_details a left join  user_licence b on  a.user_id=b.user_id left join department c on a.department=c.Department_id 
                        left join lookup d on a.user_product=d.lookup_id and d.lookup_name='Sales Persona' where a.user_id in('$contacts')");
                    $matrix_list = $query2->result();
                    return $allocation = array(
                        'contacts_list' => $matrix_list,
                        'stage' => $stage_id,
                        'cycle' => $cycle_id,
                        'opp_id' => $opportunity_ids,
                        'industry' => $industry,
                        'location' => $location,
                    );
                }else {
                    return $allocation = 1;
                }
            } else {
                return $stage = 0;
            }
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function chk_parameters_cust($customer_id, $process_type, $product_id) {
        try {
            $userid = $this->session->userdata('uid');
            $query = $GLOBALS['$dbFramework']->query("SELECT customer_industry,customer_business_loc FROM customer_info where customer_id='$customer_id'");
            $result = $query->result();
            $industry = $result[0]->customer_industry;
            $location = $result[0]->customer_business_loc;
            if(($industry!="" ||$industry!=null )&& ($location!="" || $location!="")){
          $query1 = $GLOBALS['$dbFramework']->query("SELECT scp.cycle_id,(select ss.stage_id
                from support_stage_cycle_mapping AS scm, support_sales_stage AS ss
                where scm.cycle_id=scp.cycle_id AND ss.stage_id=scm.stage_id AND ss.stage_sequence=6) AS stage_id
                FROM `support_sales_cycle_parameters` AS scp
                WHERE scp.parameter_product_node ='$product_id' AND scp.parameter_industry_node ='$industry' AND 
                scp.parameter_location_node='$location'  AND scp.parameter_for='$process_type' AND scp.cycle_togglebit = 1
                GROUP BY scp.parameter_id, scp.cycle_id");
            $result_array = $query1->result();
            $count_row = $query1->num_rows();
            if ($count_row > 0) {
                $stage_id = $result_array[0]->stage_id;
                $cycle_id = $result_array[0]->cycle_id;
                $query2 = $GLOBALS['$dbFramework']->query("select attribute_name,REPLACE(attribute_value,':',',') as matrix 
                from support_sales_stage_attributes where attribute_name='allocation_matrix' and  stage_id='$stage_id'");
                $stage_result = $query2->result();
                $stage_count = $query2->num_rows();
                if ($stage_count > 0) {
                    $contact_ids = $stage_result[0]->matrix;
                    $realArray = explode(',', $contact_ids);
                    $contacts = "'" . implode("','", $realArray) . "'";
                    $query2 = $GLOBALS['$dbFramework']->query("select  user_name, user_id from user_details where user_id 
                    in( select user_id from user_details where reporting_to='$userid' ) and
                    user_id  in (select user_id from user_details where user_id in ($contacts))");
                    $matrix_list = $query2->result();
                    return $allocation = array(
                        'contacts_list' => $matrix_list,
                        'stage' => $stage_id,
                        'cycle' => $cycle_id,
                        'opp_id' => $customer_id
                    );
                } else {
                    return $allocation = 1;
                }
            } else {
                return $stage = 0;
            }
            }else{
                echo 2;
            }
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }



    public function fetch_emails($user_id) {
        try {
            $query = $GLOBALS['$dbFramework']->query("SELECT a.user_id,
                a.user_name, a.emailId->'$.work[0]', b.department_name, c.role_name as designation from user_details a, department b, user_roles c where a.designation=c.role_id and a.department=b.Department_id and a.user_id != '$user_id' ");
            return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function isValidSupName($name) {
        try {
                $name = strtolower($name);
                $query = $GLOBALS['$dbFramework']->query("
                    SELECT * FROM support_opportunity_details WHERE request_name='$name'");
                if ($query->num_rows() == 0) 
                {
                    return 1; 
                }
                return 0;
                } 
        catch (LConnectApplicationException $e) 
        {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function chk_qualifier($stageid,$data2,$userid){
        try {
             $dt = date('YmdHis');
            $query = $GLOBALS['$dbFramework']->query("select attribute_name,attribute_value 
                   from support_sales_stage_attributes where stage_id='$stageid' and attribute_name='qualifier'");
                $count = $query->num_rows();
                if($count > 0)
                {
                    foreach ($query->result() as $row)
                    {
                        $qualifier_id= $row->attribute_value;
                    }
                }
                else
                {
                    $action=array('created','ownership_accpted');
                    $data1_3=array();
                  for($j=0;$j <count($action);$j++)
                  {
                       $data3=
                       array('mapping_id'=>uniqid(rand(),TRUE),
                        'request_id'=> $data2['request_id'],
                        'opp_cust_id'=>$data2['opp_cust_id'],
                        'from_user_id'=>$userid,
                        'to_user_id'=> $userid,
                        'cycle_id'=> $data2['cycle'],
                        'stage_id'=>$data2['stage'],
                        'module'=> 'manager',
                        'process_type'=>$data2['process'],
                        'timestamp'=> $dt,
                        'action'=> $action[$j],
                        'state'=> 1
                    );
                         array_push($data1_3,$data3) ; 
                  }
                  $data1_4=array();
                 $owner= count($data2['owner']);
                for($i=0;$i<$owner;$i++){
                    $data4=array(
                     'mapping_id'=>uniqid(rand(),TRUE),
                     'request_id'=> $data2['request_id'],
                     'opp_cust_id'=> $data2['opp_cust_id'],
                     'from_user_id'=>$userid,
                     'to_user_id'=> $owner[$i],
                     'cycle_id'=> $data2['cycle'],
                     'stage_id'=>$data2['stage'],
                     'process_type'=>$data2['process'],
                     'module'=> 'sales',
                     'timestamp'=> $dt,
                     'action'=> 'ownership_assigned',
                     'state'=> 1
                    );
                     array_push($data1_4,$data4) ;
                }
                    $req_user_details=$this->create_request($data2,$data1_3,$data1_4);
                    return $req_user_details;
                }
	    $query12 = $GLOBALS['$dbFramework']->query("SELECT * FROM lead_qualifier WHERE lead_qualifier_id='$qualifier_id' 
	        	and lead_qualifier_type='support' ORDER BY id;");
	    $arr=$query12->result_array();
                $a=array();
	        for($i=0;$i<count($arr);$i++){
	            $lead_qualifier_id=$arr[$i]['lead_qualifier_id'];
	            $a[$i]['lead_qualifier_name']=$arr[$i]['lead_qualifier_name'];
	            $a[$i]['lead_qualifier_id']=$arr[$i]['lead_qualifier_id'];
                    
	            $query1 = $GLOBALS['$dbFramework']->query("SELECT * FROM qualifier_questions WHERE lead_qualifier_id='$lead_qualifier_id' 
	            	AND que_delete_bit=1 ORDER BY row_order");
	            $arr1=$query1->result_array();
                    
	            for($j=0;$j<count($arr1);$j++){
	                $question_id=$arr1[$j]['question_id'];
	                $a[$i]['question_data'][$j]['question_type']=$arr1[$j]['question_type'];
	                $a[$i]['question_data'][$j]['question_id']=$arr1[$j]['question_id'];
	                $a[$i]['question_data'][$j]['question_text']=$arr1[$j]['question_text'];
	                $a[$i]['question_data'][$j]['answer']=$arr1[$j]['answer'];
	                $a[$i]['question_data'][$j]['mandatory_bit']=$arr1[$j]['mandatory_bit'];

	                $query11= $GLOBALS['$dbFramework']->query("SELECT * FROM qualifier_answers WHERE question_id='$question_id' ORDER BY id");
	                $arr11=$query11->result_array(); 
	                for($j1=0;$j1<count($arr11);$j1++){
	                    $a[$i]['question_data'][$j]['answer_data'][$j1]['answer_id']=$arr11[$j1]['answer_id'];
	                    $a[$i]['question_data'][$j]['answer_data'][$j1]['answer_text']=$arr11[$j1]['answer_text'];
	                }
	            }
	        }
	        return $a;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function insert_data($type1_2,$data1,$data2,$data1_3,$data1_4){
        $response=1; 
        $status_data=$data1;
        $action='passed qualifier';
        foreach($type1_2 as $value){
            $questype=$value->questype;
            $quesid=$value->quesid;
            $ansid=$value->ansid;
            if($questype==1){
                $query=$this->db->query("select * from qualifier_questions where lower(question_id)=lower('$quesid')");
                if($query->num_rows()>0) {
                    foreach ($query->result() as $row) {
                        $right_ans= $row->answer;
                    }
                }
                $query1=$this->db->query("select * from qualifier_answers where lower(answer_id)=lower('$ansid')");
                if($query1->num_rows()>0) {
                    foreach ($query1->result() as $row) {
                        $given_ans= $row->answer_text;
                    }
                }
                if($right_ans<>$given_ans) {
                    $response=0; 
                    $status='fail';
                    $action='failed qualifier';
                } else {
                    $response=1;
                    $status='success';
                }
        }
        }
        if($response==1){
             $status_data = array_merge(array('remarks' => 'success'), $status_data);
        }else{
             $status_data = array_merge(array('remarks' => 'fail'), $status_data);
        }
        $user =$this->session->userdata('uid');
        $data4=array(
            'mapping_id'=>uniqid(rand(),TRUE),
            'request_id'=>$data2['request_id'],
            'from_user_id'=>$user,
            'opp_cust_id'=>$status_data['opportunity_id'],
            'to_user_id'=>$user,
            'cycle_id'=>$data2['cycle_id'],  
            'stage_id'=>$status_data['stageid'],  
            'module'=>'manager',
            'action'=>$action,
            'process_type'=>$data2['process_type'],
            'state'=>0,
            
        );
        $insert1 = $GLOBALS['$dbFramework']->insert('qualifier_tran_details',$status_data); 
        $insert2 = $GLOBALS['$dbFramework']->insert('support_user_map',$data4);
        if($response==1){
             $req_user_details=$this->create_request($data2,$data1_3,$data1_4);
             return $req_user_details;
        }else{
        return $response;
       }
}
public function create_request($type,$supportData,$data1_4){
    try{
            $supportTicket=$this->getToken();
            if($type =='customer')
            {
                $supportTicket='c'.$supportTicket;
                $supportData = array_merge(array('request_user_id' => $supportTicket), $supportData);
            }
            else
            {
                $supportTicket='O'.$supportTicket;
                $supportData = array_merge(array('request_user_id' => $supportTicket), $supportData);
            }

            $response = $GLOBALS['$dbFramework']->insert('support_opportunity_details',$supportData);


            if ($response == TRUE) 
            {
                $ticket['ticket']=$supportTicket;
                $ticket['status']=$response;

                return $ticket;
            }
    }
    catch (LConnectApplicationException $e) {
      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
      throw $e;
    }

}
public function getToken(){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet); 
    for ($i=0; $i < 7; $i++) 
    {
        $token .= $codeAlphabet[rand(0, $max-1)];
    }
        return $token;
    }
    
 public function fetch_tickets($user){
    try{
        $query = $GLOBALS['$dbFramework']->query("select a.request_id,DATE_FORMAT(a.request_tat,'%d/%m/%Y') as tat,
            a.cricticality,a.request_user_id,a.request_for, a.request_name,b.hvalue2 as prod,c.hvalue2 as ind,
            REPLACE(request_contact,':',',') as contact ,
            d.lookup_value ,(select CASE a.request_for WHEN 'opportunity' THEN 
            (select opportunity_name from opportunity_details where opportunity_id=a.opp_cust_id)
            ELSE (select customer_name from customer_info where customer_id=a.opp_cust_id)
            END) AS oppo_cust_name from support_opportunity_details a left join  
            hierarchy b on a.request_product=b.hkey2 
            left join hierarchy c on a.request_industry=c.hkey2 
            left join lookup d on a.process_type=d.lookup_id where 
            request_id in (select request_id from support_user_map
            where to_user_id='$user' and action='ownership_assigned' and module='manager' and state=1)
            and request_id not in (select request_id from support_user_map
            where from_user_id='$user' and action='rejected' and module='manager' and state=1)
            and owner_manager_status=1 and closed_status<>100");
        
            $result_array=$query->result();
            for ($i=0;$i<count($result_array);$i++){
            $contact_names= $result_array[$i]->contact;
            $realArray = explode(',', $contact_names);
            $contacts = "'" . implode("','", $realArray) . "'";
            $query1 = $GLOBALS['$dbFramework']->query("select contact_name, JSON_UNQUOTE(contact_number->'$.mobile[0]') as contact_number from contact_details where contact_id in ($contacts)");
            $res = $query1->result();
            for($j=0;$j<count($res);$j++){
                $result_array[$i]->contact_details[$j]['contact_name'] = $res[$j]->contact_name;
                $result_array[$i]->contact_details[$j]['contact_number'] = $res[$j]->contact_number;
             }
        }
        return $result_array;               
    } catch (LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
 }   
 public function inprogress_tickets($user){
    try{
         $children=$this->getChildrenForParent($user);
         $ids = implode("','", $children);
            $query = $GLOBALS['$dbFramework']->query("SELECT a.request_id,a.request_contact,a.opp_cust_id,DATE_FORMAT(a.request_tat,'%d/%m/%Y') as tat,
            a.cricticality,a.request_user_id,a.request_for, a.owner_id,a.cycle_id,a.process_type,a.request_stage,a.request_name,b.hvalue2 as prod,c.hvalue2 as ind,
            REPLACE(request_contact,':',',') as contact,
            d.lookup_value ,(select CASE a.request_for WHEN 'opportunity' THEN 
            (select opportunity_name from opportunity_details where opportunity_id=a.opp_cust_id)
            ELSE (select customer_name from customer_info where customer_id=a.opp_cust_id)
            END) AS oppo_cust_name,e.user_name from support_opportunity_details a left join  
            hierarchy b on a.request_product=b.hkey2 
            left join hierarchy c on a.request_industry=c.hkey2 
            left join lookup d on a.process_type=d.lookup_id left join user_details e on a.manager_owner_id=e.user_id
            where a.manager_owner_id in('$ids','$user') and a.owner_id is null 
            and owner_status=0 
            and closed_status<>100 ");
            $result_array=$query->result();
            for ($i=0;$i<count($result_array);$i++){
            $contact_names= $result_array[$i]->contact;
            $realArray = explode(',', $contact_names);
            $contacts = "'" . implode("','", $realArray) . "'";
            $query1 = $GLOBALS['$dbFramework']->query("select contact_name, JSON_UNQUOTE(contact_number->'$.mobile[0]') as contact_number from contact_details where contact_id in ($contacts)");
            $res = $query1->result();
            for($j=0;$j<count($res);$j++){
                $result_array[$i]->contact_details[$j]['contact_name'] = $res[$j]->contact_name;
                $result_array[$i]->contact_details[$j]['contact_number'] = $res[$j]->contact_number;
             }
        }
        return $result_array;               
    } catch (LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
 }   
 public function get_closed_tickets($user){
    try{
        $children=$this->getChildrenForParent($user);
        $ids = implode("','", $children);
        $query = $GLOBALS['$dbFramework']->query("select a.request_id,DATE_FORMAT(a.request_tat,'%d/%m/%Y') as tat,
            a.cricticality,a.request_user_id, a.request_for,a.request_name,b.hvalue2 as prod,c.hvalue2 as ind,
            REPLACE(request_contact,':',',') as contact ,
            d.lookup_value ,(select CASE a.request_for WHEN 'opportunity' THEN 
            (select opportunity_name from opportunity_details where opportunity_id=a.opp_cust_id)
            ELSE (select customer_name from customer_info where customer_id=a.opp_cust_id)
            END) AS oppo_cust_name from support_opportunity_details a left join  
            hierarchy b on a.request_product=b.hkey2 
            left join hierarchy c on a.request_industry=c.hkey2 
            left join lookup d on a.process_type=d.lookup_id
            where (a.manager_owner_id in('$ids','$user') or a.owner_id in('$ids','$user')) and closed_status=100");
            $result_array=$query->result();
            for ($i=0;$i<count($result_array);$i++){
            $contact_names= $result_array[$i]->contact;
            $realArray = explode(',', $contact_names);
            $contacts = "'" . implode("','", $realArray) . "'";
            $query1 = $GLOBALS['$dbFramework']->query("select contact_name, JSON_UNQUOTE(contact_number->'$.mobile[0]') as contact_number from contact_details where contact_id in ($contacts)");
            $res = $query1->result();
            for($j=0;$j<count($res);$j++){
                $result_array[$i]->contact_details[$j]['contact_name'] = $res[$j]->contact_name;
                $result_array[$i]->contact_details[$j]['contact_number'] = $res[$j]->contact_number;
             }
        }
        return $result_array;               
    } catch (LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
 }   
  public function get_userlist($userid) {
    try{
          $query=$GLOBALS['$dbFramework']->query("SELECT user_id,user_name FROM user_details where user_id = '$userid'");
          return $query->result();  
    }
    catch (LConnectApplicationException $e) {
      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
      throw $e;
    } 
    }
    public function manager_owner($request_id) {
        try{
            $query=$GLOBALS['$dbFramework']->query("select * from support_opportunity_details where request_id='$request_id'");
            $req_owner=$query->result();
            $req_status= $req_owner[0]->owner_manager_status;
                if($req_status==1){
                   return $reuest_details=array(
                    'cycle_id' =>$req_owner[0]->cycle_id,
                    'request_stage'=>$req_owner[0]->request_stage,
                    'opp_cust_id'=>$req_owner[0]->opp_cust_id,
                    'process_type'=>$req_owner[0]->process_type
                  );
                }else{
                    return 0;
                }
                return $query->result();
       } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }
     public function accept_request($request_id,$data) {
        try{
            $update = $GLOBALS['$dbFramework']->update('support_opportunity_details' ,$data, array('request_id' => ($request_id)));
            return $update;
       } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }
    public function update_transaction($request_id){
      try{
        $query=$GLOBALS['$dbFramework']->query("update support_user_map set state=0 where request_id='$request_id' and module='manager' and  action in ('ownership_assigned','ownership_reassigned')");
        return TRUE;
      }catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
    }
    public function insert_transaction($data) {
       try{
         $insert = $GLOBALS['$dbFramework']->insert('support_user_map',$data); 
     return $insert;
     } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
     }
   }
    public function last_reject($request_id,$remarks){
      try{
        $userid=$this->session->userdata('uid');
        $query=$GLOBALS['$dbFramework']->query("select * from support_user_map where state=1 and request_id='$request_id' and action in ('ownership_assigned','ownership_reassigned') and module='manager'");
        $count_reject = $query->num_rows();
        $result=$query->result();
         $dt = date('YmdHis');
        $data1= array(
            'mapping_id' =>uniqid(rand(),TRUE),
            'request_id' =>$request_id,
            'cycle_id' =>$result[0]->cycle_id,
            'stage_id'=>$result[0]->stage_id,
            'process_type'=>$result[0]->process_type,
            'opp_cust_id'=>$result[0]->opp_cust_id,
            'state' =>1,
            'action'=>'rejected',
            'module'=>"manager",
            'from_user_id'=>$userid,
            'to_user_id'=>$result[0]->from_user_id,
            'timestamp'=>$dt,
            'remarks'=>$remarks
            );
           $insert = $GLOBALS['$dbFramework']->insert('support_user_map',$data1);
            if($insert==true){
                $query2=$GLOBALS['$dbFramework']->query("SELECT * from support_user_map where request_id='$request_id' and action='rejected' and state=1 and module='manager'");
                $count_reject1 = $query2->num_rows();
                if($count_reject1==$count_reject){
                    $query3=$GLOBALS['$dbFramework']->query("UPDATE support_opportunity_details set owner_manager_status=3 where request_id='$request_id'");
                    $query4=$GLOBALS['$dbFramework']->query("UPDATE support_user_map set state=0 where request_id='$request_id' and state=1 and module='manager'"); 
                }
            return true; 
            }
           
      }catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
    }
  public function opportunity_contacts($oop_id){
     try{
        $query=$GLOBALS['$dbFramework']->query(" select REPLACE(opportunity_contact,':',',') as contacts_id  from opportunity_details where opportunity_id='$oop_id'");
        $aray_result=$query->result();
        $contact_ids=$aray_result[0]->contacts_id;
        $query1=$GLOBALS['$dbFramework']->query(" select contact_name,contact_id from contact_details where  contact_id in('$contact_ids')");
        return $contact_result=$query1->result();
    }catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    } 
  }
   public function customer_contacts($oop_id){
     try{
        $query=$GLOBALS['$dbFramework']->query(" select REPLACE(opportunity_contact,':',',') as contacts_id  from opportunity_details where opportunity_id='$oop_id'");
        $aray_result=$query->result();
        $contact_ids=$aray_result[0]->contacts_id;
        $query1=$GLOBALS['$dbFramework']->query(" select contact_name,contact_id from contact_details where  contact_id in('$contact_ids')");
        return $contact_result=$query1->result();
    }catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    } 
  }
   public function update_details($data,$request_id) {
        try{
            $update = $GLOBALS['$dbFramework']->update('support_opportunity_details' ,$data, array('request_id' => ($request_id)));
            return $update;
       } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }
    public function fetch_reassign_contacts($userid,$req_id){
        try{
              $ids=$this->getChildrenForParent($userid);
              $children = implode("','", $ids);
              $realArray = explode(',', $req_id);
              $request_id = implode("','", $realArray);
              $query = $GLOBALS['$dbFramework']->query("SELECT um.user_id,ud.user_name,
                (CASE WHEN 
                (ul.sales_module <> '0') AND (SELECT COUNT(oum.to_user_id)
                 FROM support_user_map oum
                 WHERE oum.request_id IN ('$req_id')
                  AND oum.action IN ('ownership_assigned', 'ownership_reassigned','ownership_accepted','rejected')
                  AND oum.state = 1
                  AND oum.module = 'sales'
                  AND oum.to_user_id = um.user_id) = 0
                THEN 1
                ELSE 0
               END) AS sales_module,
               (CASE WHEN
                (ul.manager_module <> '0') AND (SELECT COUNT(oum.to_user_id)
                 FROM support_user_map oum
                 WHERE oum.request_id IN ('$request_id')
                  AND oum.action IN ('ownership_assigned', 'ownership_reassigned','ownership_accepted','rejected')
                  AND oum.state = 1
                  AND oum.module = 'manager'
                  AND oum.to_user_id = um.user_id) = 0
                THEN 1
                ELSE 0
               END) AS manager_module
               FROM
               user_mappings um,
               support_opportunity_details sod,
               user_licence ul,
               user_details ud
               WHERE
               um.map_type = 'product'
               AND sod.request_id IN ('$request_id')
               AND um.map_id = sod.request_product
               AND ul.user_id = um.user_id
               AND ud.user_id = um.user_id
               GROUP BY um.user_id
               HAVING COUNT(distinct sod.request_product) = (SELECT COUNT(request_product)
               FROM support_opportunity_details
               WHERE request_id IN ('$request_id')
               AND um.user_id IN (SELECT ud1.user_id
               FROM support_opportunity_details sod,
                user_details ud1,
                user_mappings um1,
                user_mappings um2,
                user_mappings um3
                WHERE (ud1.user_id IN ('$children')
                AND sod.request_id IN ('$request_id')
                AND ud1.user_state = 1
                AND ud1.user_id = um1.user_id
                AND ud1.user_id = um2.user_id
                AND ud1.user_id = um3.user_id
                AND um3.map_type = 'sell_type' AND um3.map_id = sod.process_type
                AND um1.map_type = 'clientele_industry' AND um1.map_id = sod.request_industry
                AND um2.map_type = 'business_location' AND um2.map_id = sod.request_location)
                order by sod.request_product))");
            return  $result=$query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function reassign_request($request_id,$mgr_id){
        try{
            $query = $GLOBALS['$dbFramework']->query('update support_opportunity_details set manager_owner_id is null and owner_manager_status=1 where ');
          
       } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }
    public function close_request_details($remarks,$request_id,$user_id){
        try{
            $query = $GLOBALS['$dbFramework']->query('update support_opportunity_details set manager_owner_id is null and owner_manager_status=1 where ');
          
       } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }
      public function assigned_tickets($user){
    try{
         $children=$this->getChildrenForParent($user);
         $ids = implode("','", $children);
        $query = $GLOBALS['$dbFramework']->query("SELECT a.request_id,a.request_contact,a.opp_cust_id,DATE_FORMAT(a.request_tat,'%d/%m/%Y') as tat,
            a.cricticality,a.request_user_id,a.request_for, a.cycle_id,a.process_type,a.request_stage,a.request_name,b.hvalue2 as prod,c.hvalue2 as ind,
            REPLACE(request_contact,':',',') as contact,coalesce(f.user_name,'-') as rep_owner,
            d.lookup_value ,(select CASE a.request_for WHEN 'opportunity' THEN 
            (select opportunity_name from opportunity_details where opportunity_id=a.opp_cust_id)
            ELSE (select customer_name from customer_info where customer_id=a.opp_cust_id)
            END) AS oppo_cust_name,e.user_name from support_opportunity_details a left join  
            hierarchy b on a.request_product=b.hkey2 
            left join hierarchy c on a.request_industry=c.hkey2 
            left join lookup d on a.process_type=d.lookup_id left join user_details e on a.manager_owner_id=e.user_id
            left join user_details f on a.owner_id=f.user_id
            where (a.manager_owner_id in('$ids','$user') or  a.owner_id in('$ids','$user')) and owner_status <>0 and closed_status<>100");
            $result_array=$query->result();
            for ($i=0;$i<count($result_array);$i++){
            $contact_names= $result_array[$i]->contact;
            $realArray = explode(',', $contact_names);
            $contacts = "'" . implode("','", $realArray) . "'";
            $query1 = $GLOBALS['$dbFramework']->query("select contact_name, JSON_UNQUOTE(contact_number->'$.mobile[0]') as contact_number from contact_details where contact_id in ($contacts)");
            $res = $query1->result();
            for($j=0;$j<count($res);$j++){
                $result_array[$i]->contact_details[$j]['contact_name'] = $res[$j]->contact_name;
                $result_array[$i]->contact_details[$j]['contact_number'] = $res[$j]->contact_number;
            }
        }
        return $result_array;               
    } catch (LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
 }   
 public function ticket_assignment($userid,$req_id){
        try{
              $ids=$this->getChildrenForParent($userid);
              $children = implode("','", $ids);
              $realArray = explode(',', $req_id);
              $request_id = implode("','", $realArray);
              $query = $GLOBALS['$dbFramework']->query("SELECT um.user_id,ud.user_name,
                (CASE WHEN 
                (ul.sales_module <> '0') AND (SELECT COUNT(oum.to_user_id)
                 FROM support_user_map oum
                 WHERE oum.request_id IN ('$request_id')
                  AND oum.action IN ('ownership_assigned', 'ownership_reassigned','ownership_accepted','rejected')
                  AND oum.state = 1
                  AND oum.module = 'sales'
                  AND oum.to_user_id = um.user_id) = 0
                THEN 1
                ELSE 0
               END) AS sales_module,
               (CASE WHEN
                (ul.manager_module <> '0') AND (SELECT COUNT(oum.to_user_id)
                 FROM support_user_map oum
                 WHERE oum.request_id IN ('$request_id')
                  AND oum.action IN ('ownership_assigned', 'ownership_reassigned','ownership_accepted','rejected')
                  AND oum.state = 1
                  AND oum.module = 'manager'
                  AND oum.to_user_id = um.user_id) = 0
                THEN 1
                ELSE 0
               END) AS manager_module
               FROM
               user_mappings um,
               support_opportunity_details sod,
               user_licence ul,
               user_details ud
               WHERE
               um.map_type = 'product'
               AND sod.request_id IN ('$request_id')
               AND um.map_id = sod.request_product
               AND ul.user_id = um.user_id
               AND ud.user_id = um.user_id
               GROUP BY um.user_id
               HAVING COUNT(distinct sod.request_product) = (SELECT COUNT(request_product)
               FROM support_opportunity_details
               WHERE request_id IN ('$request_id')
               AND um.user_id IN (SELECT ud1.user_id
               FROM support_opportunity_details sod,
                user_details ud1,
                user_mappings um1,
                user_mappings um2,
                user_mappings um3
                WHERE (ud1.user_id IN ('$children')
                AND sod.request_id IN ('$request_id')
                AND ud1.user_state = 1
                AND ud1.user_id = um1.user_id
                AND ud1.user_id = um2.user_id
                AND ud1.user_id = um3.user_id
                AND um3.map_type = 'sell_type' AND um3.map_id = sod.process_type
                AND um1.map_type = 'clientele_industry' AND um1.map_id = sod.request_industry
                AND um2.map_type = 'business_location' AND um2.map_id = sod.request_location)
                order by sod.request_product))");
            return  $result=$query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function assign_support_request($userid,$req_id,$ownerlist){
        try{
            $query = $GLOBALS['$dbFramework']->query("select * from support_opportunity_details where request_id='$req_id'");
            $reault=$query->result();
            $cycle_id=$reault[0]->cycle_id;
            $opp_cust_id=$reault[0]->opp_cust_id;
            $stage_id=$reault[0]->request_stage;
            $process=$reault[0]->process_type;
            $dt = date('YmdHis');
            for($i=0;$i<count($ownerlist);$i++){
                $ar_owner=explode('-',$ownerlist[$i]);
                if( $ar_owner[1]=='sales'){
                    $module='sales';
                }else{
                    $module='manager';
                }
            $data4=array(
                'mapping_id'=>uniqid(rand(),TRUE),
                'request_id'=> $req_id,
                'opp_cust_id'=> $opp_cust_id,
                'from_user_id'=>$userid,
                'to_user_id'=> $ar_owner[0],
                'cycle_id'=> $cycle_id,
                'stage_id'=>$stage_id,
                'process_type'=>$process,
                'module'=> $module,
                'timestamp'=> $dt,
                'action'=> 'ownership_assigned',
                'state'=> 1
               );
            }
                $rep_status=0;
                $mgr_status=0;
               for($j=0;$j< count($ownerlist);$j++){
                  $owner=explode('-',$ownerlist[$j]);
                    if($owner[1]=='sales'){
                      $rep_status ++;
                    }else if($owner[1]=='mgr'){
                      $mgr_status ++;
                    }
                }
                $owner_state=0;
                $mgr_state=2;
                if($rep_status>0){
                   $owner_state=1;
                }
                if($mgr_status>0){
                    $mgr_state=1;
                }
                $data2=array(
                    'owner_manager_status'=>$mgr_state,
                    'owner_status'=> $owner_state,
                );
            $insert = $GLOBALS['$dbFramework']->insert('support_user_map',$data4); 
            $update = $GLOBALS['$dbFramework']->update('support_opportunity_details' ,$data2, array('request_id' => ($req_id)));
            if($insert=true && $update== true){
                return 1;
            }
       } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }
     public function get_audit_details($request_id){
        try{
            $query = $GLOBALS['$dbFramework']->query("SELECT a.attribute_name,a.attribute_value, a.stage_id,a.time_stamp,a.remarks,b.request_name,c.stage_name 
                from support_custom_log a,support_opportunity_details b ,support_sales_stage c where a.stage_id=c.stage_id and b.request_id=a.request_id and
                a.request_id='$request_id'");
             return  $result=$query->result();
          
       } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }
     public function request_reopen($note,$userid,$reqst_id){
            try{	
                $query = $GLOBALS['$dbFramework']->query("select * from support_opportunity_details where request_id='$reqst_id'");
                $reault=$query->result();
                $cycle_id=$reault[0]->cycle_id;
                $opp_cust_id=$reault[0]->opp_cust_id;
                $stage_id=$reault[0]->request_stage;
                $process=$reault[0]->process_type;
                $data1=array(
                        'closed_status'=>0,
                        'owner_status'=>2,
                        'remarks'=>$note
                    );
                $data3=array(
                    'mapping_id'=>uniqid(),
                    'request_id'=> $reqst_id,
                    'opp_cust_id'=> $opp_cust_id,
                    'from_user_id'=>$userid,
                    'to_user_id'=> $userid,
                    'cycle_id'=> $cycle_id,
                    'stage_id'=>$stage_id,
                    'process_type'=>$process,
                    'module'=> 'manager',
                    'timestamp'=> date('Y-m-d H:i:s'),
                    'action'=> 'reopened',
                    'state'=> 1
                );
                $update = $GLOBALS['$dbFramework']->update('support_opportunity_details' ,$data1, array('request_id' => ($reqst_id)));
                $insert = $GLOBALS['$dbFramework']->insert('support_user_map',$data3);
                if($update== true && $insert== true){
                    return 1;
                }
            }
            catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }		
	}
        public function reassignRequestStage($req_id,$remarks,$ownerlist,$userid) {
        try{
            $query = $GLOBALS['$dbFramework']->query("select * from support_opportunity_details where request_id='$req_id'");
            $result = $query->result();
            $cycle_id=$result[0]->cycle_id;
            $opp_cust_id=$result[0]->opp_cust_id;
            $stage_id=$result[0]->request_stage;
            $process=$result[0]->process_type; 
            $dt = date('YmdHis');

                for($i = 0; $i< count($ownerlist); $i++){

                        if($ownerlist[$i]->module=='sales'){
                             $module='sales';
                        }else{
                            $module='manager';
                        }

                        $data4=array(
                        'mapping_id'=>uniqid(rand(),TRUE),
                        'request_id'=> $req_id,
                        'opp_cust_id'=> $opp_cust_id,
                        'from_user_id'=>$userid,
                        'to_user_id'=> $ownerlist[$i]->to_user_id,
                        'cycle_id'=> $cycle_id,
                        'stage_id'=>$stage_id,
                        'process_type'=>$process,
                        'module'=> $module,
                        'timestamp'=> $dt,
                        'action'=> 'ownership_reassigned',
                        'state'=> 1
                        );

                        $rep_status=0;
                        $mgr_status=0;  

                        for($j=0;$j< count($ownerlist);$j++){
                            if($ownerlist[$j]->module == 'sales'){
                                $rep_status ++;
                            }
                            elseif ($ownerlist[$j]->module == 'manager') {
                                $mgr_status ++;
                            }
                        }


                        $owner_state=0;
                        $mgr_state=2;
                        if($rep_status>0){
                            $owner_state=1;
                            $query= $GLOBALS['$dbFramework']->query("UPDATE support_user_map SET state=0 
                                                    where request_id in('$req_id')
                                                    and module='sales'
                                                    "); 
                        }
                        if($mgr_status>0){
                            $mgr_state=1;
                            $query= $GLOBALS['$dbFramework']->query("UPDATE support_user_map SET state=0 
                                                    where request_id in('$req_id')
                                                    and module='sales'
                                                    ");
                        }

                        $data2=array(
                        'owner_manager_status'=>$mgr_state,
                        'owner_status'=> $owner_state,
                        );
                }
                        $insert = $GLOBALS['$dbFramework']->insert('support_user_map',$data4); 
                        $update = $GLOBALS['$dbFramework']->update('support_opportunity_details' ,$data2, array('request_id' => ($req_id)));
                        if($insert=true && $update== true){
                            return 1;
                        }           

        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function map_support($data){
        // inserts in to the opportunity transaction table - oppo user map
        try {

            $query= $GLOBALS['$dbFramework']->insert_batch('support_user_map', $data);
            return $query;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function reassign_reset($request_id,$updateData) {
        try {
            $var = $GLOBALS['$dbFramework']->update('support_opportunity_details', $updateData, array('request_id' => $request_id));
            return $var;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function getAssocaitedChildrens($user,$industry,$location,$product)
    {

        try
        {
            $children = $user."','";
            $children .= $this->getChildrenForParent($user);

            $childrens = $GLOBALS['$dbFramework']->query("
                                                        SELECT 
                                                        ud.user_id, ud.user_name
                                                        FROM
                                                        user_details ud,
                                                        user_mappings um1,
                                                        user_mappings um2,
                                                        user_mappings um3
                                                        WHERE
                                                        (ud.user_id IN ('$children'))
                                                        AND ud.user_state = 1
                                                        AND ud.user_id = um1.user_id
                                                        AND ud.user_id = um2.user_id
                                                        AND ud.user_id = um3.user_id
                                                        AND um1.map_type = 'clientele_industry'
                                                        AND um1.map_id = '$industry'
                                                        AND um2.map_type = 'business_location'
                                                        AND um2.map_id = '$location'
                                                        AND um3.map_id = '$product'
                                                        AND um3.map_type = 'product'
                                                        GROUP BY ud.user_id
                                                        ");
            return array(
                            'stageOwners'=>$childrens->result(),
                            'supportOwners'=>$childrens->result()
                        );

        }
        catch (LConnectApplicationException $e) 
        {
           $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e; 
        }
    }
        
   
}