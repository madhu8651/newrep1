<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_customerModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class manager_customerModel extends CI_Model{
    public function __construct()
  {
     parent::__construct();
  }

  /*
   * Query records which matches specific WHERE clause
   */ 
   
  public function get_state(){
    try{
      $query=$GLOBALS['$dbFramework']->query("select b.lookup_id as state_id,
      LOWER(b.lookup_value)as state_name
      from lookup a,lookup b
      where a.lookup_id=b.lookup_name
      and a.lookup_name='country'");
      return $query->result();
    }catch (LConnectApplicationException $e) {
      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
      throw $e;
    }
  }
  public function getChildrenForParent($user_id) {
      try  {
              $query = $GLOBALS['$dbFramework']->query("
              SELECT user_id, reporting_to FROM user_details");
              $full_structure = $query->result();
              $allParentNodes = [];
              if (version_compare(phpversion(), '7.0.0', '<')) {
              // php version isn't high enough to support array_column
              foreach($full_structure as $row)  {
                $allParentNodes[$row->user_id] = $row->reporting_to;
                }
              } 
              else {
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
    /*
    * Query records which matches specific WHERE clause
    */
    private function fetchChildNodes($givenID, & $childNodes, $allParentNodes)  {
        foreach ($allParentNodes as $user_id => $reporting_to) {
            if ($reporting_to == $givenID)  {
                array_push($childNodes, $user_id);
                $this->fetchChildNodes($user_id, $childNodes, $allParentNodes);                
            }
        }
    }
   /*
   * Query records which matches specific WHERE clause for Sell type of users
   */  

 public function fetch_userPrivilages($user_id) {
    try {
            $sell_types = array();
            $query = $GLOBALS['$dbFramework']->query("SELECT map_id from user_mappings where user_id='$user_id' and map_type='sell_type'");
            foreach ($query->result_array() as $row)  {
                array_push($sell_types, $row['map_id']);
            }
            $finalArray = array();
            $finalArray['leads'] = 0;
            $finalArray['customers'] = 0;
            foreach ($sell_types as $sell_type) {
            if ($sell_type=='new_sell') {
            $finalArray['leads']=1;
            } else if (($sell_type=='cross_sell')||($sell_type='up_sell')){
            $finalArray['customers']=1;
            }
            }
            $finalArray['sell_types'] = $sell_types;
            return $finalArray;
    }
    catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    
  } 

/*
     * Query all records from given table
     * TODO Order By
     * TOGET Unassigned Customers
    ---------------------------Unassigned Customer Records---------------------------------- */


  public function fetchCustomerDetails($user_id) {  
      try {
              $children = $user_id."','";
              $children .= $this->getChildrenForParent($user_id);

              $GLOBALS['$log']->debug('running new query for fetching unassigned customers');
              $query =$GLOBALS['$dbFramework']->query("
              SELECT (CASE WHEN a.customer_rep_owner IS NOT NULL THEN 
                            (SELECT group_concat( DISTINCT a.hkey2,':', a.hvalue2,'')   
                                from hierarchy a,user_mappings c, user_details d
                               where a.hkey2=c.map_id 
                              and c.map_type='clientele_industry' and d.team_id=c.transaction_id and c.user_id=a.customer_rep_owner) END)  AS rep_industry,
                     (CASE WHEN a.customer_rep_owner IS NOT NULL THEN 
                              (SELECT group_concat( DISTINCT a.hkey2,':', a.hvalue2,'')   
                                from hierarchy a,user_mappings c, user_details d
                               where a.hkey2=c.map_id 
                              and c.map_type='business_location' and d.team_id=c.transaction_id and c.user_id=a.customer_rep_owner) END)  AS rep_location, 
                     (CASE WHEN a.customer_rep_owner IS NOT NULL THEN 
                              (SELECT group_concat( DISTINCT a.hkey2,':', a.hvalue2,'')   
                                from hierarchy a,user_mappings c, user_details d
                               where a.hkey2=c.map_id 
                              and c.map_type='product' and d.team_id=c.transaction_id and c.user_id=a.customer_rep_owner) END)  AS rep_product,
                              a.customer_status as customer_status,coalesce(udm.user_name,'') as customer_manager_owner,coalesce( group_concat(distinct cp.hvalue2),'') as customer_products,a.customer_id as customer_id, a.customer_name as customer_name,
              a.customer_address as customer_address,a.customer_zip,
              a.customer_website, a.customer_manager_status,a.customer_country,a.customer_city as city,
              a.customer_remarks as customer_remarks,a.customer_location_coord as coordinate,a.customer_logo as customer_logo,
              a.customer_business_loc,a.customer_state,a.customer_industry,a.customer_manager_status as rejected_manager,
              a.customer_rep_status as rejected_sales,
              coalesce(JSON_UNQUOTE(a.customer_number->'$.phone[0]'),'') as customer_number,
              coalesce(JSON_UNQUOTE(a.customer_email->'$.email[0]'),'')  as customer_email,
              c.contact_photo,
              c.contact_type,c.contact_id,coalesce(c.contact_name,'') as contact_name,coalesce(c.contact_desg,' - ') as contact_desg ,
              JSON_UNQUOTE(c.contact_number->'$.phone[0]') as mobile_number1,
              JSON_UNQUOTE(c.contact_number->'$.phone[1]') as mobile_number2,
              JSON_UNQUOTE(c.contact_email->'$.email[0]') as contact_email1, 
              JSON_UNQUOTE(c.contact_email->'$.email[1]') as contact_email2,
              c.contact_address,
              coalesce(bl.hvalue2,'') as customer_city,
              st.lookup_value as state_name,
              coalesce (ind.hvalue2,'') as industry_name,
              bp.lookup_value as contacttype,
              cn.lookup_value as country_name, (select CASE WHEN COUNT(customer_id)>0 THEN 'Active' ELSE 'Inactive' END
              FROM product_purchase_info where (customer_id=a.customer_id) AND (now() < coalesce(purchase_end_date, addtime(now(),'1 1:1:1')))) as status  
              FROM  customer_info as a 
              LEFT JOIN lookup as st 
              ON 
              a.customer_state=st.lookup_id
              LEFT JOIN product_purchase_info as ppi
              ON
              a.customer_id = ppi.customer_id
              LEFT JOIN user_details as udm
              ON a.customer_manager_owner = udm.user_id 
              LEFT JOIN hierarchy as cp
              ON 
              ppi.product_id = cp.hkey2
              LEFT JOIN lookup as cn 
              ON
              a.customer_country=cn.lookup_id
              LEFT JOIN hierarchy as bl 
              ON 
              a.customer_business_loc=bl.hkey2
              LEFT JOIN hierarchy as ind
              ON 
              a.customer_industry=ind.hkey2
              LEFT JOIN contact_details as c
              ON (a.customer_id=c.lead_cust_id OR a.lead_id = c.lead_cust_id)
              LEFT JOIN lookup as bp 
              ON
              c.contact_type = bp.lookup_id
              WHERE a.customer_manager_owner IN ('$children') 
              and a.customer_rep_owner IS NULL
              and a.customer_id NOT IN (
              SELECT DISTINCT lead_cust_id
              FROM lead_cust_user_map
              WHERE (to_user_id IN ('$children')) AND (module = 'sales') 
              AND (action in ('assigned','reassigned')) 
              )
              GROUP BY a.customer_id
              ORDER BY a.customer_name   
              ");
              return $query->result();
      } 
     catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }

  }

  /*
     * Query all records from given table
     * TODO Order By
     * TOGET Received Customers
   -----------------------------Recived Customer Records------------------------------------ */

  public function fetchRecivedCustomerDetails($user_id) { 
      try {
              $query=$GLOBALS['$dbFramework']->query("SELECT coalesce( group_concat(distinct cp.hvalue2),'-') as customer_products,a.customer_id as customer_id, a.customer_name as customer_name,
              a.customer_address as customer_address,a.customer_zip,
              a.customer_website, a.customer_manager_status,a.customer_country,a.customer_city as city,
              a.customer_remarks as customer_remarks,a.customer_location_coord as coordinate,
              a.customer_business_loc,a.customer_state,a.customer_industry,
              coalesce(JSON_UNQUOTE(a.customer_number->'$.phone[0]'),'') as customer_number,
              coalesce(JSON_UNQUOTE(a.customer_email->'$.email[0]'),'')  as customer_email,
              c.contact_photo,
              c.contact_type,c.contact_id,c.contact_name,c.contact_desg,
              JSON_UNQUOTE(c.contact_number->'$.phone[0]') as mobile_number1,
              JSON_UNQUOTE(c.contact_number->'$.phone[1]') as mobile_number2,
              JSON_UNQUOTE(c.contact_email->'$.email[0]') as contact_email1, 
              JSON_UNQUOTE(c.contact_email->'$.email[1]') as contact_email2,
              c.contact_address,
              bl.hvalue2 as customer_city,
              st.lookup_value as state_name,
              cn.lookup_value as country_name,
              (select CASE WHEN COUNT(customer_id)>0 THEN 'Active' ELSE 'Inactive' END
              FROM product_purchase_info where (customer_id=a.customer_id) AND (now() < coalesce(purchase_end_date, addtime(now(),'1 1:1:1')))) as status
              FROM customer_info a
              LEFT JOIN lead_cust_user_map b 
              ON a.customer_id=b.lead_cust_id
              LEFT JOIN lookup as st 
              ON 
              a.customer_state=st.lookup_id
              LEFT JOIN product_purchase_info as ppi
              ON 
              a.customer_id = ppi.customer_id
              LEFT JOIN hierarchy as cp
              ON 
              ppi.product_id = cp.hkey2
              LEFT JOIN lookup as cn 
              ON
              a.customer_country=cn.lookup_id
              LEFT JOIN hierarchy as bl 
              ON 
              a.customer_business_loc=bl.hkey2
              LEFT JOIN contact_details as c
              ON (a.customer_id=c.lead_cust_id or a.lead_id = c.lead_cust_id)
              WHERE 
              (b.lead_cust_id in 
                (SELECT lead_cust_id 
                FROM lead_cust_user_map 
                WHERE (to_user_id='$user_id'  and (action='assigned' or  action='reassigned')  and type='customer' and state=1 and module='manager')))
                and (b.lead_cust_id not in 
                (SELECT lead_cust_id 
                FROM lead_cust_user_map 
                where
                (to_user_id='$user_id'  and (action='rejected')  and type='customer' and state=1 and module='manager')))
                AND a.customer_manager_status=1 
                AND b.lead_cust_id=a.customer_id 
                GROUP BY a.customer_id
                ORDER BY a.customer_name");

              return $query->result();
      }
      catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
      }

  /*
    * Query all records from given table
    * TOGET Assigned Customers
    * TODO Order By
    -------------------------- Assigned Customer Records---------------------------*/

    public function fetchAssignedCustomerDetails($user_id) { 
      try {
              $children = $user_id."','";
              $children .= $this->getChildrenForParent($user_id);
             
              $GLOBALS['$log']->debug('running new query for fetching assigned customers');
              $query=$GLOBALS['$dbFramework']->query("
              SELECT (CASE WHEN ci.customer_rep_owner IS NOT NULL THEN 
                            (SELECT group_concat( DISTINCT a.hkey2)   
                                from hierarchy a,user_mappings c, user_details d
                               where a.hkey2=c.map_id 
                              and c.map_type='clientele_industry' and d.team_id=c.transaction_id and c.user_id=ci.customer_rep_owner) END)  AS rep_industry,
                     (CASE WHEN ci.customer_rep_owner IS NOT NULL THEN 
                              (SELECT group_concat( DISTINCT a.hkey2)   
                                from hierarchy a,user_mappings c, user_details d
                               where a.hkey2=c.map_id 
                              and c.map_type='business_location' and d.team_id=c.transaction_id and c.user_id=ci.customer_rep_owner) END)  AS rep_location, 
                     (CASE WHEN ci.customer_rep_owner IS NOT NULL THEN 
                              (SELECT group_concat( DISTINCT a.hkey2)   
                                from hierarchy a,user_mappings c, user_details d
                               where a.hkey2=c.map_id 
                              and c.map_type='product' and d.team_id=c.transaction_id and c.user_id=ci.customer_rep_owner) END)  AS rep_product,
                              coalesce(udm.user_name,'') as customer_manager_owner,coalesce( group_concat(distinct cp.hvalue2),'-') as customer_products,ci.customer_id,ci.customer_name,
              coalesce((SELECT user_name FROM user_details WHERE user_id=ci.customer_rep_owner), '-') AS user_name,
              GROUP_CONCAT(DISTINCT ud.user_name) AS assigned_to,
              lcum.to_user_id, lcum.from_user_id,ci.customer_zip,ci.customer_website,ci.customer_city as city,
              ci.customer_city as city,ci.customer_location_coord as coordinate, 
              coalesce(JSON_UNQUOTE(ci.customer_number->'$.phone[0]'),'') as customer_number,ci.customer_address as customer_address,ci.customer_remarks as customer_remarks,
              ci.customer_country,ci.customer_state,
              coalesce(JSON_UNQUOTE(ci.customer_email->'$.email[0]'),'') as customer_email,ci.customer_business_loc,
              ci.customer_city as city,
              ci.customer_rep_owner as customer_rep_owner,ci.customer_logo,ci.customer_manager_status as rejected_manager,ci.customer_rep_status as rejected_sales,
              cd.contact_name,cd.contact_type,
              cd.contact_id, cd.contact_desg,cd.contact_photo, 
              JSON_UNQUOTE(cd.contact_number->'$.phone[0]') as mobile_number1,
              JSON_UNQUOTE(cd.contact_number->'$.phone[1]') as mobile_number2,
              JSON_UNQUOTE(cd.contact_email->'$.email[0]') as contact_email1,
              JSON_UNQUOTE(cd.contact_email->'$.email[1]') as contact_email2,
              cd.contact_address,
              coalesce (bl.hvalue2,'-') as customer_city,
              coalesce (st.lookup_value,'-') as state_name,
              coalesce (cn.lookup_value,'-') as country_name,
              coalesce (ind.hvalue2,'-') as industry_name,ci.customer_industry,
              lk.lookup_value as contacttype,
              (select CASE WHEN COUNT(customer_id)>0 THEN 'Active' ELSE 'Inactive' END
              FROM product_purchase_info where (customer_id=ci.customer_id) AND (now() < coalesce(purchase_end_date, addtime(now(),'1 1:1:1')))) as status
              FROM customer_info ci
              LEFT JOIN lookup as st 
              ON ci.customer_state=st.lookup_id
              LEFT JOIN lookup as cn
              ON ci.customer_country=cn.lookup_id
              LEFT JOIN contact_details as cd 
              ON (ci.customer_id=cd.lead_cust_id or ci.lead_id = cd.lead_cust_id)
              LEFT JOIN hierarchy as bl
              ON ci.customer_business_loc=bl.hkey2
              LEFT JOIN hierarchy as ind
              ON ci.customer_industry=ind.hkey2
              LEFT JOIN product_purchase_info as ppi
              ON ci.customer_id = ppi.customer_id
              LEFT JOIN user_details as udm
              ON ci.customer_manager_owner = udm.user_id
              LEFT JOIN hierarchy as cp
              ON ppi.product_id = cp.hkey2
              LEFT JOIN lookup as lk
              ON cd.contact_type=lk.lookup_id,
              lead_cust_user_map lcum, 
              user_details ud
              WHERE
              (ci.customer_manager_owner IN ('$children') 
              OR  ci.customer_rep_owner IN ('$children'))    
              AND ci.customer_id IN (
              SELECT DISTINCT lead_cust_id
              FROM lead_cust_user_map
              WHERE  (module='sales'))
              AND (lcum.lead_cust_id=ci.customer_id) 
              AND (lcum.to_user_id=ud.user_id) 
              AND (lcum.module='sales')
              AND (lcum.action = 'assigned' or lcum.action='reassigned')
              GROUP BY ci.customer_id
              ORDER BY ci.customer_name
              ");

              return $query->result();
      }
      catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
      }

     
  }

   /*
    * Query records which matches specific WHERE clause 
    * TOGET mycustomers details closed by my team
    */ 

  public function myCustomerDetails($user_id) {
       try {
              $children = $user_id."','";
              $children .= $this->getChildrenForParent($user_id);
              $query=$GLOBALS['$dbFramework']->query("
              SELECT (CASE WHEN ci.customer_rep_owner IS NOT NULL THEN 
                            (SELECT group_concat( DISTINCT a.hkey2,':', a.hvalue2,'')   
                                from hierarchy a,user_mappings c, user_details d
                               where a.hkey2=c.map_id 
                              and c.map_type='clientele_industry' and d.team_id=c.transaction_id and c.user_id=ci.customer_rep_owner) END)  AS rep_industry,
                     (CASE WHEN ci.customer_rep_owner IS NOT NULL THEN 
                              (SELECT group_concat( DISTINCT a.hkey2,':', a.hvalue2,'')   
                                from hierarchy a,user_mappings c, user_details d
                               where a.hkey2=c.map_id 
                              and c.map_type='business_location' and d.team_id=c.transaction_id and c.user_id=ci.customer_rep_owner) END)  AS rep_location, 
                     (CASE WHEN ci.customer_rep_owner IS NOT NULL THEN 
                              (SELECT group_concat( DISTINCT a.hkey2,':', a.hvalue2,'')   
                                from hierarchy a,user_mappings c, user_details d
                               where a.hkey2=c.map_id 
                              and c.map_type='product' and d.team_id=c.transaction_id and c.user_id=ci.customer_rep_owner) END)  AS rep_product,
                              coalesce(udm.user_name,'') as customer_manager_owner,coalesce( group_concat(distinct cp.hvalue2),'-') as customer_products,ci.customer_id,ci.customer_name,
              coalesce((SELECT user_name FROM user_details WHERE user_id=ci.customer_rep_owner), '-') AS user_name,
              GROUP_CONCAT(DISTINCT ud.user_name) AS assigned_to,
              lcum.to_user_id, lcum.from_user_id,ci.customer_zip,ci.customer_website,ci.customer_city as city,
              ci.customer_city as city,ci.customer_location_coord as coordinate, 
              coalesce(JSON_UNQUOTE(ci.customer_number->'$.phone[0]'),'') as customer_number,ci.customer_address as customer_address,ci.customer_remarks as customer_remarks,
              ci.customer_country,ci.customer_state,
              coalesce(JSON_UNQUOTE(ci.customer_email->'$.email[0]'),'') as customer_email,ci.customer_business_loc,
              ci.customer_city as city,
              ci.customer_rep_owner as customer_rep_owner,ci.customer_logo,ci.customer_manager_status as rejected_manager,ci.customer_rep_status as rejected_sales,
              cd.contact_name,cd.contact_type,
              cd.contact_id, cd.contact_desg,cd.contact_photo,cd.contact_address,
              JSON_UNQUOTE(cd.contact_number->'$.phone[0]') as mobile_number1,
              JSON_UNQUOTE(cd.contact_number->'$.phone[1]') as mobile_number2,
              JSON_UNQUOTE(cd.contact_email->'$.email[0]') as contact_email1,
              JSON_UNQUOTE(cd.contact_email->'$.email[1]') as contact_email2,
              coalesce (bl.hvalue2,'-') as customer_city,
              coalesce (st.lookup_value,'-') as state_name,
              coalesce (cn.lookup_value,'-') as country_name,
              coalesce (ind.hvalue2,'-') as industry_name,ci.customer_industry,
              lk.lookup_value as contacttype,
              (select CASE WHEN COUNT(customer_id)>0 THEN 'Active' ELSE 'Inactive' END
              FROM product_purchase_info where (customer_id=ci.customer_id) AND (now() < coalesce(purchase_end_date, addtime(now(),'1 1:1:1')))) as status
              FROM customer_info ci
              LEFT JOIN lookup as st 
              ON ci.customer_state=st.lookup_id
              LEFT JOIN lookup as cn
              ON ci.customer_country=cn.lookup_id
              LEFT JOIN contact_details as cd 
              ON (ci.customer_id=cd.lead_cust_id or ci.lead_id = cd.lead_cust_id)
              LEFT JOIN hierarchy as bl
              ON ci.customer_business_loc=bl.hkey2
              LEFT JOIN hierarchy as ind
              ON ci.customer_industry=ind.hkey2
              LEFT JOIN product_purchase_info as ppi
              ON ci.customer_id = ppi.customer_id
              LEFT JOIN user_details as udm
              ON ci.customer_manager_owner = udm.user_id
              LEFT JOIN hierarchy as cp
              ON ppi.product_id = cp.hkey2
              LEFT JOIN lookup as lk
              ON cd.contact_type=lk.lookup_id 
              LEFT JOIN lead_cust_user_map as lcum
              ON lcum.lead_cust_id = ci.customer_id, 
              user_details ud
              WHERE
              ci.customer_manager_owner IN ('$children') 
              and ci.customer_id in (SELECT lead_cust_id from lead_cust_user_map where type='customer')
              GROUP BY ci.customer_id
              ORDER BY ci.customer_name
             
              ");

              return $query->result();
      }
      catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
  }

    /*
    * Query records which matches specific WHERE clause 
    * TOGET Reporting Persons
    */ 
  public function getReportingPersons($user) { 
      try {
              $children = $user."','";
              $children .= $this->getChildrenForParent($user);
              $query= $GLOBALS['$dbFramework']->query("
              SELECT user_id 
              FROM user_details 
              WHERE user_id IN ('$children') 
              GROUP BY user_id");             
              return $query->result();
      } 
      catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
      }    
    
    }
  /*
  *Query records which matches specific WHERE clause
  *TOGET Country
  */
   public function view_country() {
      try {
              $query=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='country'");
              return $query->result();
      }
      catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
      }
        
    }
  /*
  *Query records which matches specific WHERE clause
  */  
    public function state($countrId) {
      try {
              $query=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='$countrId'");
              return $query->result();
      }
      catch (LConnectApplicationException $e) {
      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
      throw $e;
      }
        
   }
  /*
  *Query records which matches specific WHERE clause
  */ 
   public function contact() {
      try {
                $query=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='Buyer Persona'");
                return $query->result();
      }
      catch (LConnectApplicationException $e) {
      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
      throw $e;
      }

      
   }
  /*
  *Query records which matches specific WHERE clause 
  *TOGET industries for user
  */ 
  public function industry($user) {
      try {
              $query=$GLOBALS['$dbFramework']->query("SELECT a.hvalue2 as industry_name,a.hkey2 as industry_id 
              from hierarchy a,user_mappings c
              where a.hkey2=c.map_id 
              and c.map_type='clientele_industry'  and c.user_id='$user'
              group by a.hkey2");
              return $query->result();
      }
      catch (LConnectApplicationException $e) {
      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
      throw $e;
      }
        
  }
  /*
  *Query records which matches specific WHERE clause 
  *TOGET locations for user
  */ 
  public function location($user) {
    try {
            $query=$GLOBALS['$dbFramework']->query("SELECT a.hvalue2 as business_location_name,a.hkey2 as business_location_id 
            from hierarchy a,user_mappings c
            where a.hkey2=c.map_id 
            and c.map_type='business_location'
            and c.user_id='$user'
            group by a.hkey2");
            return $query->result();
    }
    catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
   }
  
  }
/*-------------------------Product Purchase Information----------------------------------------*/
/*
  *Query records which matches specific WHERE clause 
  *TOGET List of Currency associated for user
  */

  public function getListOfCurrency($userId) {
    try {
            $query=$GLOBALS['$dbFramework']->query("SELECT a.map_value as currency_id,b.currency_name 
                                              from user_mappings a,currency b 
                                              where a.map_key='currency' and a.map_value=b.currency_id and a.user_id='$userId'
                                              group by currency_id");
        return $query->result();
    }
    catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    } 
  }

  public function getCurrency($products,$ownerId) {
    try {
          $products=implode("','", $products);
            $query=$GLOBALS['$dbFramework']->query("SELECT cu.currency_name,cu.currency_id 
                                                    from user_mappings as um,currency as cu
                                                    where um.user_id='$ownerId' 
                                                    and um.map_type='product'
                                                    and um.map_id IN ('$products') 
                                                    and um.map_value=cu.currency_id
                                                    group by cu.currency_id;");
            return $query->result();
    }
    catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    } 
  }

  /*
  *Query records which matches specific WHERE clause 
  *TOGET List of Product associated for user of specific currency
  */

/*  public function customerProductData($currency_id,$user_id) { 
    try {
            $query=$GLOBALS['$dbFramework']->query("SELECT a.hvalue2 as product_name,a.hkey2 as product_id 
                              from hierarchy a, user_mappings c
                             where a.hkey2=c.map_id 
                             and c.user_id='$user_id' 
                             and c.map_value='$currency_id'
                             and c.map_type='product'
                              group by a.hkey2");
            return $query->result();
    }
    catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    } 

 }*/

  public function customerProductData($user_id) { 
    try {
            $query=$GLOBALS['$dbFramework']->query("SELECT a.hvalue2 as product_name,a.hkey2 as product_id 
                              from hierarchy a, user_mappings c
                             where a.hkey2=c.map_id 
                             and c.user_id='$user_id' 
                             and c.map_type='product'
                              group by a.hkey2");
            return $query->result();
    }
    catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    } 

 }

 /*
 * Function for insert
 * TODO Order By
 */

  public function addProductPurchaseInfo($purchaseArray) {
      try {
            $query=$this->db->insert_batch('product_purchase_info',$purchaseArray);         
            return $query;
      }
      catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
     }
       
  }

  /*
  *Query records which matches specific WHERE clause
  */

  public function getUsers($user) { 
    try {
            $children = $user."','";
            //   $children .= $this->getChildrenForParent($user);
            $query= $GLOBALS['$dbFramework']->query("
            SELECT a.user_name, a.user_id, b.sales_module, b.manager_module 
            from user_details a, user_licence b
            where 
            --  (a.user_id in ('$children')) 
            (a.reporting_to in ('$children') or a.user_id in ('$children')) 
            and
            a.user_id=b.user_id
            and a.user_state=1
            order by a.user_id");
            return $query->result();
    }   
    catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
    }
 
  }

  /*
  *Query records which matches specific WHERE clause
  */

  public function customerProductPurchase($customerID) {
      try {
            $query= $GLOBALS['$dbFramework']->query("SELECT 
                                                    ppi.purchase_id AS purchase_id,ppi.id,
                                                   coalesce(ppi.purchase_end_date,' - ') AS purchase_end_date,
                                                    ppi.purchase_start_date AS purchase_start_date,
                                                    ppi.opportunity_id AS opportunity_id,
                                                    ppi.product_id AS product_id,
                                                    hi.hvalue2  as product_name,coalesce(ppi.Quantity,'') as quantity,coalesce(ppi.amount,'') as amount,ppi.currency,coalesce(ppi.opportunity_id,'-'),cu.currency_name as currency_name,ppi.id,
                                                    coalesce(ppi.reference_number,' -')  as reference_number,
                                                    ppi.product_owner as product_owner_id ,ud.user_name as product_owner,ppi.renewal_date as renewal_date,coalesce(ppi.rate,'') as rate,coalesce(ppi.score,'') as score,coalesce(ppi.customer_code,'')  as customer_code,coalesce(ppi.priority,'')  as priority
                                                    FROM
                                                        product_purchase_info AS ppi 
                                                        LEFT JOIN currency AS cu 
                                                        ON ppi.currency =cu.currency_id,
                                                        hierarchy AS hi,
                                                        user_details AS ud
                                                    WHERE
                                                        ppi.customer_id = '$customerID'
                                                    AND ppi.product_id = hi.hkey2
                                                    AND ppi.product_owner =ud.user_id
                                                    group by ppi.purchase_id,ppi.product_id
                                                    order by ppi.id"); 
          $arr=$query->result_array();
          $row = array();
          if($query->num_rows() > 0) {
               for($i=0;$i<count($arr);$i++){
                $purchase_id = $arr[$i]['purchase_id'];
                $row[$purchase_id] = array();
                $product_array = array();
                for($j=0;$j<count($arr);$j++){
                    if($purchase_id==$arr[$j]['purchase_id']){
                        $some_array= array(
                        'product_id'=>$arr[$j]['product_id'],
                        'product_name'=>$arr[$j]['product_name'],
                        'purchase_end_date'=>$arr[$j]['purchase_end_date'],
                        'purchase_start_date'=>$arr[$j]['purchase_start_date'],
                        'quantity'=>$arr[$j]['quantity'],
                        'amount'=>$arr[$j]['amount'],
                        'id'=>$arr[$j]['id'],
                        'renewal_date'=>$arr[$j]['renewal_date'],
                        'rate'=>$arr[$j]['rate'],
                        'score'=>$arr[$j]['score'],
                        'customer_code'=>$arr[$j]['customer_code'],
                        'priority'=>$arr[$j]['priority']


                        );
                      array_push($product_array, $some_array); 
                    }
                } 
                $some_array = array(
                  'opp_id' => $arr[$i]['opportunity_id'],
                  'currency_name' => $arr[$i]['currency_name'],
                  'currency_id' => $arr[$i]['currency'],
                   'reference_number' => $arr[$i]['reference_number'],
                    'product_owner' => $arr[$i]['product_owner'],
                    'product_owner_id'=>$arr[$i]['product_owner_id'],
                  'purchase_id' => $purchase_id,
                  'prod_data' => $product_array,
          
                );
              $row[$purchase_id] = $some_array;
            } 
          }
          $finalArray = array();
          foreach ($row as $key=>$value) {
            $finalArray[] =$row[$key];
          }
          return $finalArray;
      }

      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
 }  
/*----------------------------------------------------------------------*/
/*
  *Query records which matches specific WHERE clause 
  *TOGET customer logs 
  */
   public function customerLogData($customerId) {
        try {
                $query=$GLOBALS['$dbFramework']->query("
                                SELECT 
                                lu.lookup_value AS activity,
                                ud.user_name AS rep_name,
                                ci.customer_name AS customer_name,
                                DATE_FORMAT(rl.starttime, '%D %b %Y %h:%i %p') AS 'starttime',
                                rl.note AS note,
                                DATE_FORMAT(rl.starttime, '%Y/%m/%d %H:%i:%s') AS start,
                                rl.rating AS rating,
                                rl.call_type AS action,
                                DATE_FORMAT(rl.endtime, '%Y/%m/%d %H:%i:%s') AS end,
                                'completed' AS status, rl.path as path,rl.logtype as conntype
                                FROM
                                customer_info ci,
                                rep_log rl,
                                user_details ud,
                                lookup lu
                                WHERE
                                rl.leadid = '$customerId'
                                AND rl.leadid = ci.customer_id
                                AND rl.call_type = 'complete'
                                AND rl.type = 'customer'
                                AND rl.logtype = lu.lookup_id
                                AND ud.user_id = rl.rep_id");
                return $query->result();
        }
        catch (LConnectApplicationException $e) {
              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
              throw $e;
        }
  
   }
/*
  *Query records which matches specific WHERE clause 
  *TOGET customers opportunity
  */
    public function customerOppData($customerId) {
      try {
              $query=$GLOBALS['$dbFramework']->query("SELECT opd.opportunity_id,opd.opportunity_name,
                                                      ss.stage_name as stage_name,hi.hvalue2 as opportunity_product,coalesce(DATE_FORMAT(opd.opportunity_date, '%D %b %Y %h:%i %p'),'-') AS opportunity_date ,ud.user_name as stage_owner
                                                    FROM opportunity_details as opd,sales_stage as ss,hierarchy as hi,user_details as ud
                                                    WHERE lead_cust_id='$customerId'
                                                    AND opd.opportunity_stage=ss.stage_id
                                                    AND opd.opportunity_product=hi.hkey2
                                                    and opd.stage_owner_id=ud.user_id");
                                                    return $query->result();
      } 
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
   }

 /*
  *Query records which matches specific WHERE clause 
  *TOGET customers Source
  ------------------------------Not using------------------------------------*/

   public function customerLeadSourceData() {
     // Include sesion ID to get the product assigned for the particular team.
    $query =$GLOBALS['$dbFramework']->query("SELECT b.id,b.hierarchy_id,b.hkey2,b.hvalue2,b.hkey1,b.hvalue1
                              from hierarchy_class a,hierarchy b
                              where a.Hierarchy_Class_Name='lead_source'
                               and b.hkey2!='0' and b.Hierarchy_Class_ID=a.hierarchy_class_id  order by b.hierarchy_id");
        return $query->result();
   }

  /*
  *Function for update the customer records which matches specific WHERE clause   
  --------------------------Update customer records---------------------------------------*/

   public function updateCustomerInfo($customerDataArray) {   
      try {

             $query= $GLOBALS['$dbFramework']->update_batch('customer_info',$customerDataArray, 'customer_id');
             return true; 
      }
      catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }
       
   }

  public function updateCustomLead($customDataArray) {   
      try {

            $query= $GLOBALS['$dbFramework']->update_batch('lead_info',$customDataArray, 'lead_id');
             return true; 
      }
      catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
      }
       
   }


   /*
  *Query to update the contact records which matches specific WHERE clause 
  ----------------------------update contact records------------------------------------------*/
   public function updateContactInfo($contactDataArray) {  
      try {
            $query= $GLOBALS['$dbFramework']->update_batch('contact_details',$contactDataArray, 'contact_id');
            return true;   
      } 
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      } 
     
   }

 /*
  *Query  which matches specific WHERE clause 
  ----------------------------------Not using------------------------------------------------*/

    public function checkRepOwner($acceptedValue)  {
      $query=$GLOBALS['$dbFramework']->query("SELECT customer_rep_owner
                                FROM customer_info as customer_owner
                                WHERE customer_id='$acceptedValue' 
                             --   AND customer_rep_owner IS NULL
                                 AND customer_manager_owner IS NULL
                                "); 
      if($query->num_rows() > 0){
      return true;
      }   
      else{
      return false;
      }                                                                        
                                                                      
    }

 /*
  *Query for update which matches specific WHERE clause 
  ----------------------------------Not using------------------------------------------------*/

    public function updateCustomerOwner($user,$customerManagerStatus,$acceptedValue) { 
      $query=$GLOBALS['$dbFramework']->query("UPDATE customer_info 
                                      SET customer_manager_owner='$user',customer_manager_status='$customerManagerStatus'
                                      WHERE customer_id='$acceptedValue'");
      return $query;
       
    }

  /*
     * Function for insert
     * TODO Order By
  ----------------------------------Accepting Customers----------------------------------------*/


  public function insertCustomerUser($insertArray) {
      try {
            $query=$GLOBALS['$dbFramework']->insert_batch('lead_cust_user_map',$insertArray);         
             return $query;
      }
      catch (LConnectApplicationException $e) {
      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
      throw $e;
      }
       
  }

  /*
     * Function for update
     */
  public function updateLeadMgrOwner($lid, $user) {
      //returns true or false indicating whether lead manager owner was successfully updated or not
      try {
              $mapping_id = uniqid(rand(),TRUE);  
              $data=array(
              'lead_cust_id' => $lid,
              'from_user_id' => $user,
              'to_user_id' => $user,
              'action'=>'accepted',
              'module'=>'manager',
              'timestamp'=>date('Y-m-d H:i:s'),
              'state'=>'1',
              'type'=>'customer',
              'mapping_id'=>$mapping_id
              );
              //insert a row into transaction table as you have updated owner
              $data1=array('state'=>'0');
             $data2=array('customer_manager_status'=>'2',
             'customer_manager_owner'=>$user);
              //insert a row into transaction table as you have updated owner
              $update=$GLOBALS['$dbFramework']->update('customer_info', $data2,array('customer_id'=>$lid));
              $update=$GLOBALS['$dbFramework']->update('lead_cust_user_map', $data1,array('lead_cust_id'=>$lid,'module'=>'manager'));
              if($update){
                  $insertQuery = $GLOBALS['$dbFramework']->insert('lead_cust_user_map', $data); 
              }        
              if ($insertQuery == false) {
                 return false;        
              }
                $update3=$GLOBALS['$dbFramework']->query("UPDATE lead_reminder  set rep_id='$user'
                where lead_id='$lid' and module_id='manager' and status in('pending',
                'scheduled') and type='customer'");
               return $lid; 
       }
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
        }

    } 

  /*
     * Function for insert
     *---------------------------Not using-----------------------------------------------------*/

  public function customer_accept_mgr($data) {
        $query=$GLOBALS['$dbFramework']->insert('lead_cust_user_map', $data);  
        return $query;
 }

 /*
  *Query  which matches specific WHERE clause 
  *Returning Manager owner
  -----------------------------------NotUsing------------------------------------------------ */

  public function checkForPrevMgr($customerid, $user) {
  //return null if lead_manager_owner is NULL or if given user's reporting manager
  //else returns lead_name of those unqualified
    $query = $GLOBALS['$dbFramework']->query('
    SELECT (
    CASE ci.customer_manager_owner 
    WHEN ud.reporting_to THEN NULL 
    WHEN NULL THEN NULL 
    ELSE ci.customer_name END) AS customer_manager_owner
    from customer_info ci, user_details ud
    where ci.customer_id="'.$customerid.'" AND ud.user_id="'.$user.'"');
    $mgrOwner = $query->result();
    $mgrOwner = $mgrOwner[0]->customer_manager_owner;
    return $mgrOwner;
  }

/*
     * Function for batch insert
     * TODO Order By
     -----------------------Not using---------------------------------------------------------*/
 public function rep_owner_update($insertrepowner){     
  $query= $GLOBALS['$dbFramework']->update_batch('customer_info',$insertrepowner, 'customer_id');
  return $query;  
}

/*
  *Query  which matches specific WHERE clause 
  */

  public function getModule($userId) {
    try {
            $query= $GLOBALS['$dbFramework']->query("
            SELECT a.user_name, a.user_id, b.sales_module, b.manager_module 
            from user_details a, user_licence b
            where 
            a.user_id='$userId'
            AND
            a.user_id=b.user_id
            ");
            return $query->result();
    }
    catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
    }
 
}
/*
  *Query  which matches specific WHERE clause 
  *TOGET List of Users
 -------------------------------Assignment---------------------------------------------------- */

  public function getListOfManager($customer1,$user){  
      try {
              //Get Only active user list   
               $old_customer_count=count($customer1);  
               $customer1=implode("','", $customer1);    
               $children = $user."','";
               $children .= $this->getChildrenForParent($user);
               $emptyproduct =  $GLOBALS['$dbFramework']->query("SELECT count(distinct ppi.customer_id) as id from product_purchase_info as ppi where ppi.customer_id IN ('$customer1')");
              $customercount=$emptyproduct->result();  
              $new_customer_count=$customercount[0]->id;  

              if($old_customer_count==$new_customer_count){               
                $query3 = $GLOBALS['$dbFramework']->query("SELECT customer_industry from customer_info where lead_id in('$customer1') and customer_industry!=''");    

                $query4 = $GLOBALS['$dbFramework']->query("SELECT customer_business_loc from customer_info where lead_id in('$customer1') and customer_business_loc!=''");

                if($query3->num_rows()>0 && $query4->num_rows()>0){
                  $query = $GLOBALS['$dbFramework']->query("
                                        SELECT um.user_id,ud.user_name,
                                    (CASE WHEN 
                                    (ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'sales'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS sales_module,
                                    (CASE WHEN
                                    (ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'manager'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS manager_module
                              FROM
                              user_mappings um,
                              product_purchase_info lp,
                              user_licence ul,
                              user_details ud,
                              user_mappings um3
                              WHERE
                              um.map_type = 'product'
                              AND lp.customer_id IN ('$customer1')
                              AND um3.map_type = 'sell_type'
                              AND um3.map_id IN ('cross_sell','up_sell')
                              AND ud.user_id = um3.user_id
                              AND um.map_id = lp.product_id
                              AND ul.user_id = um.user_id
                              AND ud.user_id = um.user_id
                              GROUP BY um.user_id
                              HAVING COUNT(distinct lp.product_id) = (SELECT COUNT(distinct product_id)
                              FROM product_purchase_info
                              WHERE customer_id IN ('$customer1'))
                              AND um.user_id IN (SELECT ud1.user_id
                              FROM
                              user_mappings um1,
                              user_details ud1,
                              user_mappings um2,
                              customer_info li
                              WHERE
                              ud1.user_id = um1.user_id
                              AND ud1.user_id = um2.user_id                            
                              AND li.customer_id IN ('$customer1')                             
                              AND um1.map_type = 'clientele_industry'
                              AND um1.map_id = li.customer_industry
                              AND um2.map_type = 'business_location'
                              AND um2.map_id = li.customer_business_loc
                              AND (ud1.reporting_to in ('$children')
                              OR ud1.user_id = '$user')
                              AND ud1.user_state = 1)
                              order by lp.product_id");

                    return $query->result();
               }else if($query3->num_rows()>0){
                $query = $GLOBALS['$dbFramework']->query("
                                        SELECT um.user_id,ud.user_name,
                                    (CASE WHEN 
                                    (ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'sales'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS sales_module,
                                    (CASE WHEN
                                    (ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'manager'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS manager_module
                              FROM
                              user_mappings um,
                              product_purchase_info lp,
                              user_licence ul,
                              user_details ud,
                              user_mappings um3
                              WHERE
                              um.map_type = 'product'
                              AND lp.customer_id IN ('$customer1')
                              AND ud.user_id = um3.user_id
                              AND um.map_id = lp.product_id
                              AND ul.user_id = um.user_id
                              AND ud.user_id = um.user_id
                              GROUP BY um.user_id
                              HAVING COUNT(distinct lp.product_id) = (SELECT COUNT(distinct product_id)
                              FROM product_purchase_info
                              WHERE customer_id IN ('$customer1'))
                              AND um.user_id IN (SELECT ud1.user_id
                              FROM
                              user_mappings um1,
                              user_details ud1,
                              /*user_mappings um2,*/
                              customer_info li
                              WHERE
                              ud1.user_id = um1.user_id
                              AND ud1.user_id = um2.user_id                            
                              AND li.customer_id IN ('$customer1')                             
                              AND um1.map_type = 'clientele_industry'
                              AND um1.map_id = li.customer_industry                            
                              AND (ud1.reporting_to in ('$children')
                              OR ud1.user_id = '$user')
                              AND ud1.user_state = 1)
                              order by lp.product_id");

                    return $query->result();

               }else if($query4->num_rows()>0) {
                $query = $GLOBALS['$dbFramework']->query("
                                        SELECT um.user_id,ud.user_name,
                                    (CASE WHEN 
                                    (ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'sales'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS sales_module,
                                    (CASE WHEN
                                    (ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'manager'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS manager_module
                              FROM
                              user_mappings um,
                              product_purchase_info lp,
                              user_licence ul,
                              user_details ud,
                              user_mappings um3
                              WHERE
                              um.map_type = 'product'
                              AND lp.customer_id IN ('$customer1')
                              AND um3.map_type = 'sell_type'
                              AND um3.map_id IN ('cross_sell','up_sell')
                              AND ud.user_id = um3.user_id
                              AND um.map_id = lp.product_id
                              AND ul.user_id = um.user_id
                              AND ud.user_id = um.user_id
                              GROUP BY um.user_id
                              HAVING COUNT(distinct lp.product_id) = (SELECT COUNT(distnct product_id)
                              FROM product_purchase_info
                              WHERE customer_id IN ('$customer1'))
                              AND um.user_id IN (SELECT ud1.user_id
                              FROM                            
                              user_details ud1,
                              user_mappings um2,
                              customer_info li
                              WHERE
                              ud1.user_id = um1.user_id
                              AND ud1.user_id = um2.user_id                            
                              AND li.customer_id IN ('$customer1')  
                              AND um2.map_type = 'business_location'
                              AND um2.map_id = li.customer_business_loc
                              AND um3.map_type = 'sell_type'
                              AND um3.map_id IN ('cross_sell','up_sell')
                              AND (ud1.reporting_to in ('$children')
                              OR ud1.user_id = '$user')
                              AND ud1.user_state = 1)
                              order by lp.product_id");

                    return $query->result();
               }else {
                $query = $GLOBALS['$dbFramework']->query("
                                    SELECT um.user_id,ud.user_name,
                                    (CASE WHEN 
                                    (ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'sales'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS sales_module,
                                    (CASE WHEN
                                    (ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'manager'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS manager_module
                              FROM
                              user_mappings um,
                              product_purchase_info lp,
                              user_licence ul,
                              user_details ud,
                              user_mappings um3
                              WHERE
                              um.map_type = 'product'
                              AND lp.customer_id IN ('$customer1')
                              AND um3.map_type = 'sell_type'
                              AND um3.map_id IN ('cross_sell','up_sell')
                              AND ud.user_id = um3.user_id
                              AND um.map_id = lp.product_id
                              AND ul.user_id = um.user_id
                              AND ud.user_id = um.user_id
                              GROUP BY um.user_id
                              HAVING COUNT(distinct lp.product_id) = (SELECT COUNT(distinct product_id)
                              FROM product_purchase_info
                              WHERE customer_id IN ('$customer1'))
                              AND um.user_id IN (SELECT ud1.user_id
                              FROM                             
                              user_details ud1
                              WHERE                             
                             (ud1.reporting_to in('$children')
                              OR ud1.user_id = '$user')
                              AND ud1.user_state = 1)
                              order by lp.product_id");
                    return $query->result();
               }
             }
               else if($new_customer_count==0) {
                      $query3 = $GLOBALS['$dbFramework']->query("SELECT customer_industry from customer_info where lead_id in('$customer1') and customer_industry!=''");    

                      $query4 = $GLOBALS['$dbFramework']->query("SELECT customer_business_loc from customer_info where lead_id in('$customer1') and customer_business_loc!=''");

                            if($query3->num_rows()>0 && $query->num_rows()>0){
                               $query = $GLOBALS['$dbFramework']->query("
                                    SELECT um.user_id,ud.user_name,
                                    (CASE WHEN 
                                    (ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'sales'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS sales_module,
                                    (CASE WHEN
                                    (ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'manager'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS manager_module
                              FROM                             
                              user_mappings um,
                              product_purchase_info lp,
                              user_licence ul,
                              user_details ud,
                              user_mappings u1,
                              user_mappings u2
                              where ud.user_id in ('$children')                   
                              AND um.map_type = 'sell_type'
                              AND um.map_id IN ('cross_sell','up_sell')
                              and um.user_id=ud.user_id
                              AND um1.map_type = 'clientele_industry'
                              AND um1.map_id = li.customer_industry
                              AND um2.map_type = 'business_location'
                              AND um2.map_id = li.customer_business_loc
                              and ud.user_id=ul.user_id                    
                              and ud.user_state='1'
                              group by ud.user_id");                    
                              return $query->result();


                            }else if($query3->num_rows()>0) {
                              $query = $GLOBALS['$dbFramework']->query("
                                    SELECT um.user_id,ud.user_name,
                                    (CASE WHEN 
                                    (ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'sales'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS sales_module,
                                    (CASE WHEN
                                    (ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'manager'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS manager_module
                              FROM                             
                              user_mappings um,
                              product_purchase_info lp,
                              user_licence ul,
                              user_details ud,
                              user_mappings u1
                              where ud.user_id in ('$children')                   
                              AND um.map_type = 'sell_type'
                              AND um.map_id IN ('cross_sell','up_sell')
                              and um.user_id=ud.user_id
                              AND um1.map_type = 'clientele_industry'
                              AND um1.map_id = li.customer_industry                            
                              and ud.user_id=ul.user_id                    
                              and ud.user_state='1'
                              group by ud.user_id");                    
                              return $query->result();

                            }else if($query4->num_rows()>0){
                              $query = $GLOBALS['$dbFramework']->query("
                                    SELECT um.user_id,ud.user_name,
                                    (CASE WHEN 
                                    (ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'sales'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS sales_module,
                                    (CASE WHEN
                                    (ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'manager'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS manager_module
                              FROM                             
                              user_mappings um,
                              product_purchase_info lp,
                              user_licence ul,
                              user_details ud,
                              user_mappings u2
                              where ud.user_id in ('$children')                   
                              AND um.map_type = 'sell_type'
                              AND um.map_id IN ('cross_sell','up_sell')
                              and um.user_id=ud.user_id                              
                              AND um2.map_type = 'business_location'
                              AND um2.map_id = li.customer_business_loc
                              and ud.user_id=ul.user_id                    
                              and ud.user_state='1'
                              group by ud.user_id");                    
                              return $query->result();
                            }else {
                                  $query = $GLOBALS['$dbFramework']->query("
                                    SELECT um.user_id,ud.user_name,
                                    (CASE WHEN 
                                    (ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'sales'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS sales_module,
                                    (CASE WHEN
                                    (ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
                                    FROM lead_cust_user_map lp
                                    WHERE lp.lead_cust_id IN ('$customer1')
                                    AND lp.action IN ('assigned', 'reassigned', 'accepted','created')
                                    AND lp.state = 1
                                    AND lp.module = 'manager'
                                    AND lp.to_user_id = um.user_id) = 0
                                    THEN 1
                                    ELSE 0
                                    END) AS manager_module
                              FROM                             
                              user_mappings um,
                              product_purchase_info lp,
                              user_licence ul,
                              user_details ud
                              where ud.user_id in ('$children')                   
                              AND um.map_type = 'sell_type'
                              AND um.map_id IN ('cross_sell','up_sell')
                              and um.user_id=ud.user_id
                              and ud.user_id=ul.user_id                    
                              and ud.user_state='1'
                              group by ud.user_id");                    
                              return $query->result();
                      }        
              }else{
                      return 0;
              }

           
        }
        catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
        }
  
  }

  /*
    * Function for insert
     ----------------------------------------Not using-----------------------------------------*/
  public function customer_assign($data) { 

  $query=$this->db->insert('lead_cust_user_map',$data);            
  return $query;
  }

/*
    * Function for Batch insert 
    */
  public function rep_assign($data) {
    try {
           $query= $GLOBALS['$dbFramework']->insert_batch('lead_cust_user_map', $data);      
            return $query; 
    } 
    catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
    }          
   
  }

  /*
    * Function for Batch update 
    */
    public function getTokenIds($userString) {
        try{
                $query = $GLOBALS['$dbFramework']->query("SELECT JSON_UNQUOTE(firebase_token->'$.mobile[0]') as firebase_token FROM representative_details WHERE user_id IN ('$userString')");
                $ids= $query->result();
                $idString = array();
                foreach ($ids as $id) {
                    array_push($idString, $id->firebase_token);
                }
                return $idString;
                
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }
    public function getTokenIdsWeb($userString) {
        try{
                $query = $GLOBALS['$dbFramework']->query("SELECT JSON_UNQUOTE(firebase_token->'$.web[0]') as firebase_token FROM representative_details WHERE user_id IN ('$userString')");
                $ids= $query->result();
                $idStringWeb = array();
                foreach ($ids as $id) {
                    array_push($idStringWeb, $id->firebase_token);
                }
                return $idStringWeb;
                
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }
  

  public function customer_status_assigned($status_arr){
     try {
              $query= $GLOBALS['$dbFramework']->update_batch('customer_info',$status_arr, 'customer_id');
              return $query; 
     } 
     catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
    }
      
  }

  public function updateProductPurchaseInfo($purchaseArray,$purchaseID,$product) {
    try {
            $query= $GLOBALS['$dbFramework']->update_batch('product_purchase_info',$purchaseArray,'id');
            return $query; 
     } 
     catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
    }
  }
/*-------------------------------------------------------------------------------------------*/
  
  /*
    * Function for Batch insert 
    ---------------------------Not using -----------------------------------------------------*/
  public function customer_manager_owner_update($data,$lid) {
    $update = $GLOBALS['$dbFramework']->update('customer_info', $data,array('customer_id'=>$lid));       
    return $update;
  }

  /*
  *Query  which matches specific WHERE clause 
  *TOGET List of COUNTRY
  ------------------------------xsl------------------------------------------------------------*/

  public function country() {
    try {
            $query=$GLOBALS['$dbFramework']->query("SELECT LOWER(lookup_value) as country_name,lookup_id FROM lookup WHERE lookup_name='country'");
            return $query->result();
    }
    catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
     
  }
 /*
  *Query  which matches specific WHERE clause 
 */

 public function get_customers() {
    try {
            $query=$GLOBALS['$dbFramework']->query("SELECT LOWER(a.customer_name) as customername, JSON_UNQUOTE(a.customer_number->'$.phone[0]') as customerphone from customer_info a,contact_details b where a.customer_id=b.lead_cust_id");
            return $query->result();       
    } 
    catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    } 
        
  }

  /*
    * Function for Batch insert */

  public function insert_details($customers,$contacts,$transaction){
        try {
                $query1=$GLOBALS['$dbFramework']->insert_batch("customer_info",$customers);
                $query2=$GLOBALS['$dbFramework']->insert_batch('contact_details',$contacts);
                $query3=$GLOBALS['$dbFramework']->insert_batch('lead_cust_user_map',$transaction);
                return $query1 && $query2 && $query3; 
        }
        catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
        }
        
   }

/*--------------------------------Not using---------------------------------------------------*/

    public function fetchCustomerCreated($customer_id) {
        $query=$GLOBALS['$dbFramework']->query("SELECT lcum.lead_cust_id as customer_id,lcum.from_user_id as from_user_id,lcum.to_user_id as to_user_id,lcum.action as action,lcum.module,lcum.timestamp as createdtime,ud.user_name as from_user_name,ud1.user_name as to_user_name
        from lead_cust_user_map as lcum,user_details ud,user_details ud1 
        where lcum.lead_cust_id='$customer_id'
        and lcum.type='customer'
        and lcum.from_user_id=ud.user_id
        and lcum.to_user_id=ud1.user_id
        and lcum.action='created'
        ");
        return $query->result();
    }
//////////////////////////////////////////////////////////////////////////////////////////////////////
   public function fetchCustomerScheduleData($customer_id){ 
      try{                  
            $query = $GLOBALS['$dbFramework']->query("
            SELECT a.status, DATE_FORMAT(a.meeting_start, '%D %b %Y %h:%i %p') as start_date, DATE_FORMAT(a.meeting_end, '%D %b %Y %h:%i %p') as end_date, a.conntype, a.remarks,
            b.customer_name as leadname, c.user_name as activity_owner, d.lookup_value as activity,a.status as status,a.duration as duration
            from
            lead_reminder a , customer_info b, user_details c, lookup d
            where 
            a.lead_id='$customer_id'
            and a.status in ('scheduled','pending')
            and a.lead_id=b.customer_id
            and a.rep_id=c.user_id
            and a.conntype=d.lookup_id");
      return $query->result();    
    }
    catch (LConnectApplicationException $e) {
      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
      throw $e;
    }
  }

  public function checkEditCustomer($customerName,$customerId){
        try{
                $query=$GLOBALS['$dbFramework']->query("SELECT * FROM customer_info where LCASE(customer_name)='".strtolower($customerName)."' and  customer_id!='$customerId'");
                if ($query->num_rows() > 0) {
                    return 0;
                }
                else {
                    return 1;
                } 
        } catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
        }
    }

    public function rep_owner($customerid) {
        try{
            $query=$GLOBALS['$dbFramework']->query("select customer_rep_status from customer_info where customer_id='$customerid'");
            return $query->result();
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }

    public function assigned_count($customerid,$user_id) {
      try{
       
            $query=$GLOBALS['$dbFramework']->query("select from_user_id, DATE(timestamp) as assig_dt from lead_cust_user_map where to_user_id='$user_id' and lead_cust_id='$customerid' and action in('assigned','reassigned') order by timestamp desc limit 1");
            $ss= $query->result();
            $fromid= $ss[0]->from_user_id;
            $assign_date= $ss[0]->assig_dt;
            $query1=$GLOBALS['$dbFramework']->query("select count(*) as total from lead_cust_user_map where from_user_id='$fromid' and lead_cust_id='$customerid' and action in('assigned','reassigned') and to_user_id!='$user_id' and DATE(timestamp)='$assign_date'");
            $ids= $query1->result();
            $assign_count= $ids[0]->total;

            $query2=$GLOBALS['$dbFramework']->query("select count(*) as total from lead_cust_user_map where to_user_id='$fromid' and lead_cust_id='$customerid' and action='rejected' and DATE(timestamp)>='$assign_date'");
            $reject= $query2->result();
            $reject_count= $reject[0]->total;
            return array(
            'assign' =>$assign_count,            
            'reject'=>$reject_count,
            'from_id'=>$fromid,
            );
        
    }
    catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
    }

    public function accept_lead($customerid,$data){
        try{
            $update = $GLOBALS['$dbFramework']->update('customer_info' ,$data, array('customer_id' => ($customerid)));
            return $update;
        } catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }

    public function insertRejectData($data) {
        try{
            $insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data); 
            return $insert;
        } 
        catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }

  public function customerHistory($customer_id){
    try{
        $query = $GLOBALS['$dbFramework']->query("SELECT lcum.timestamp as timestamp, ci.customer_name as lead_cust_name, lcum.remarks as remarks, lcum.action as action, lcum.lead_cust_id as lead_id,lcum.from_user_id as from_user_id,lcum.mapping_id as mapping_id, lcum.to_user_id as to_user_id,lcum.module,ud.user_name as from_user_name,ud1.user_name as to_user_name 
        from lead_cust_user_map as lcum,user_details ud,user_details ud1, customer_info as ci
        where lcum.lead_cust_id='$customer_id'
        and ci.customer_id=lcum.lead_cust_id      
        and lcum.type='customer'           
        and (lcum.action='created' or lcum.action='assigned' or lcum.action='accepted' or lcum.action='rejected' or lcum.action='reassigned' or lcum.action='seen')
        and lcum.from_user_id=ud.user_id
        and lcum.to_user_id=ud1.user_id
        order by lcum.timestamp");
        return $query->result();
    }
    catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }     
  }   


  public function logScheduleActivity($customerId){ 
    try{                  
        $query = $GLOBALS['$dbFramework']->query("SELECT a.status, a.meeting_start, a.meeting_end, a.conntype, a.remarks, 
        b.customer_name as leadname, c.user_name, d.lookup_value as activity 
        from
        lead_reminder a , customer_info b, user_details c, lookup d
        where 
        a.lead_id='$customerId'
        and b.customer_id=a.lead_id
        and a.rep_id=c.user_id
        and a.conntype=d.lookup_id");
        return $query->result();    
    }
    catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
  } 

  public function reassign_state_zero($customer_state,$module){         
    try{ 
           
        $GLOBALS['$logger']->debug(json_encode($customer_state));
        $cust_id=implode("','", $customer_state);
        $GLOBALS['$logger']->debug($cust_id);
        $query= $GLOBALS['$dbFramework']->query("UPDATE lead_cust_user_map SET state=0 
        where lead_cust_id in('$cust_id')
        and module='$module'
        and type='customer'");  

        return $query;   
    }
    catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
  } 

    public function lead_accept_mgr($data,$lid){
        try{
            $query=$GLOBALS['$dbFramework']->insert('lead_cust_user_map', $data);  
            $mstatus=array('state'=>'0'); 
            return $query;  
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function count_assign_customer($lid){
        $query= $GLOBALS['$dbFramework']->query("SELECT count(lead_cust_id) from lead_cust_user_map 
        where lead_cust_id='$lid' and (action='assigned' or action='reassigned') and module='manager' and state =1");
        return $query->result();
    }

    public function count_rejected_customer($lid){
        $query= $GLOBALS['$dbFramework']->query("SELECT count(lead_cust_id) from lead_cust_user_map 
        where lead_cust_id='$lid' and action='rejected' and module='manager' and state =1");
        return $query->result();
    }

    public function update_customertable($lid,$mstatus){
   
    try{
          $query=$GLOBALS['$dbFramework']->query("UPDATE customer_info as  ci 
          INNER JOIN lead_cust_user_map lcm ON lcm.lead_cust_id = ci.customer_id      
          SET ci.customer_manager_status = 3, /*li.lead_rep_status = 0,*/lcm.state = 0          
          WHERE ci.customer_id = '$lid'
          and lcm.lead_cust_id='$lid'
          and (lcm.action='assigned' or lcm.action='reassigned' or action='rejected') ");
          return $query;  
    }
    catch (LConnectApplicationException $e) {
      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
      throw $e;
    }   
  }

    public function updateCustomerPhoto($customerid,$data) {
        try{
            $update = $GLOBALS['$dbFramework']->update('customer_info' ,$data, array('customer_id' => ($customerid)));
            return $update;
        } 
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function fetchUserName($user){
      try{
            $query = $GLOBALS['$dbFramework']->query("SELECT user_name 
                                                FROM user_details 
                                                WHERE user_id='$user'");
            return $query->result();
        } 
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function insertNotificationData($notificationDataArray) {
        try{
                $insert = $GLOBALS['$dbFramework']->insert_batch('notifications',$notificationDataArray);
                return $insert;
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }        

    public function getCustomerName($custid) {
        try{

        $query=$GLOBALS['$dbFramework']->query("SELECT customer_name FROM customer_info 
          where customer_id in('$custid')");
        return $query->result();  
        }
        catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
        } 
    }
    public function customer_history($custid) {
      try{
          $query=$GLOBALS['$dbFramework']->query("SELECT distinct lcum.timestamp as timestamp, li.customer_name as lead_cust_name, lcum.remarks as remarks, lcum.action as action, lcum.lead_cust_id as lead_id,lcum.from_user_id as from_user_id,lcum.mapping_id as mapping_id, lcum.to_user_id as to_user_id,lcum.module,ud.user_name as from_user_name,ud1.user_name as to_user_name 
            from lead_cust_user_map as lcum,user_details ud,user_details ud1, customer_info li
            where lcum.lead_cust_id='$custid'
            and li.customer_id=lcum.lead_cust_id      
            and lcum.type='customer'           
            and (lcum.action='created' or lcum.action='assigned' or lcum.action='accepted' or lcum.action='rejected' 			or lcum.action='reassigned' or lcum.action='seen' or lcum.action='edited')
            and lcum.from_user_id=ud.user_id 
            and lcum.to_user_id=ud1.user_id 
            order by lcum.timestamp");
          return $query->result();  
    }
    catch (LConnectApplicationException $e) {
      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
      throw $e;
    } 
    }
     public function customer_historydetails($custid) {
      try{
          $query=$GLOBALS['$dbFramework']->query("
                                              SELECT 
                                              a.action AS action,
                                              b.user_name AS from_user_name,
                                              a.module AS module,
                                              (CASE
                                              WHEN (action IN ('assigned' , 'reassigned')) THEN b1.user_name
                                              ELSE '-'
                                              END) AS to_user_name,
                                              a.timestamp AS timestamp,
                                              a.remarks AS remarks,
                                              a.mapping_id AS mapping_id
                                              FROM
                                              lead_cust_user_map AS a,
                                              user_details b,
                                              user_details b1
                                              WHERE
                                              a.lead_cust_id = '$custid'
                                              AND a.type = 'customer'
                                              AND (a.from_user_id = b.user_id)
                                              AND (a.to_user_id = b1.user_id)
                                              group by a.id
                                              ORDER BY a.timestamp
                                            ");
          return $query->result();  
    }
    catch (LConnectApplicationException $e) {
      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
      throw $e;
    } 
    }

    public function update_reminder_table($manager_activity,$rep_activity){
        try{ 
            $stringRepresentation=implode("','", $rep_activity);
            $stringRepresentation1 = implode("','", $manager_activity);

            if(!empty($stringRepresentation)){          
            $query=$GLOBALS['$dbFramework']->query("UPDATE lead_reminder  set rep_id=null
            where lead_id in ('$stringRepresentation') and module_id='sales' and status in('pending',
            'scheduled') and type='customer'");     
            } 
            if(!empty($stringRepresentation1)){
            $query=$GLOBALS['$dbFramework']->query("UPDATE lead_reminder  set rep_id=null
            where lead_id in ('$stringRepresentation1') and module_id='manager' and status in('pending','scheduled') and type='customer'");
            }
        }catch (LConnectApplicationException $e) {
            $GLOBALSs['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        } 
    }

    public function checkLeadID($customerId) {
      try{
        $query=$GLOBALS['$dbFramework']->query("SELECT count(*) FROM customer_info as ci WHERE ci.customer_id='$customerId' and ci.lead_id IS NOT NULL");
        if($query->num_rows() > 0){
            return 1;
        }
        else{
          return 0;
        }
       
      }
      catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
     }
    }

    public function fetchAllContacts($leadCustId) {
      try{

        $query=$GLOBALS['$dbFramework']->query("
                SELECT cd.contact_id AS contact_id, cd.contact_name AS contact_name, cd.contact_for AS contact_for,
                    coalesce(lo.lookup_value,'-') AS contact_type_name, lo.lookup_id AS contact_type_id,
                    ci.customer_id AS lead_cust_id, ci.customer_name AS lead_cust_name,
                    JSON_UNQUOTE(coalesce(cd.contact_number->'$.phone[0]','')) as employeephone1,
                    JSON_UNQUOTE(coalesce(cd.contact_number->'$.phone[1]','')) as employeephone2,
                    JSON_UNQUOTE(coalesce(cd.contact_email->'$.email[0]','')) as employeeemail,
                    JSON_UNQUOTE(coalesce(cd.contact_email->'$.email[1]','')) as employeeemail2,
                    coalesce(cd.contact_desg,'-') as contact_desg,coalesce(cd.contact_photo,''), cd.contact_created_time,
                    coalesce(cd.contact_dob,'-') AS contact_dob, cd.contact_address AS contact_address, cd.remarks AS remarks
                FROM customer_info AS ci, contact_details AS cd LEFT JOIN `lookup` AS lo ON cd.contact_type = lo.lookup_id AND lo.lookup_name = 'Buyer Persona'
                WHERE (ci.customer_id = '$leadCustId') 
                AND (ci.customer_id=cd.lead_cust_id or ci.lead_id = cd.lead_cust_id)
                GROUP BY cd.contact_id
                ORDER BY cd.contact_name");
        return $query->result();
      }
      catch(LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;

      }
    }

    public function fetchCustomLead($customerId){
     try{

        $query=$GLOBALS['$dbFramework']->query("
          SELECT aa.attribute_key,aa.attribute_name,aa.attribute_type,coalesce (li.attribute,'') as attribute,li.lead_id as id,aa.module as module,aa.attribute_validation_string
          from `admin_attributes` as aa,customer_info as ci,lead_info as li
          where ci.customer_id='customerId'
          and ci.lead_id=li.lead_id
          and module IN ('Lead')");
          $arr=$query->result(); 

      }
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
    }
    }

    public function fetchCustomCustomer($customerId){
       try{

          $query=$GLOBALS['$dbFramework']->query("
         SELECT aa.attribute_key,aa.attribute_name,aa.attribute_type,coalesce(ci.attribute,'') as attribute,ci.customer_id as id,aa.module as module,aa.attribute_validation_string
          from `admin_attributes` as aa,customer_info as ci
          where ci.customer_id='$customerId'
          and module IN ('Customer')");
          return $query->result();   
      } 
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
     }
    }
    public function customerDocumentsById($customerId) {
      try{

          $query=$GLOBALS['$dbFramework']->query("
          SELECT sgea.message_id AS mapping_id,sgea.mail_attachment_path AS path,sge.from_name AS doc_user_id,sge.mail_date as timestamp,'FileName' as stage_name 
          from support_group_emails as sge,support_group_email_attachments as sgea
          where sge.lead_cust_opp_id='$customerId' 
          and sgea.message_id=sge.message_id");
          return $query->result();   
      } 
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
     }
    }
    public function fetchLeadId($customerId) {
      try{

          $query=$GLOBALS['$dbFramework']->query("
          SELECT ci.lead_id as lead_id 
          from customer_info as ci
          where ci.customer_id='$customerId' 
          ");
          return $query->result();   
      } 
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
     }
    }
    public function fetchOppId($leadId) {
         try {
                $query=$GLOBALS['$dbFramework']->query("SELECT od.opportunity_id as opportunity_id 
                  FROM opportunity_details as od WHERE od.lead_cust_id='$leadId'");
                  return $query->result();
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    
    public function oppLogData($oppids) {
        try {

              $query=$GLOBALS['$dbFramework']->query("
               SELECT lu.lookup_value as activity, ud.user_name as rep_name, ci.opportunity_name as customer_name, DATE_FORMAT(rl.starttime, '%D %b %Y %h:%i %p') AS 'starttime', rl.note as note, rl.rating as rating, rl.call_type as action, DATE_FORMAT(rl.endtime, '%Y/%m/%d %H:%i:%s') AS end, DATE_FORMAT(rl.starttime, '%Y/%m/%d %H:%i:%s') AS start,'completed' as status,rl.path as path, rl.logtype as conntype
                from opportunity_details ci, rep_log rl, user_details ud, lookup lu
                where rl.leadid IN ('$oppids')
                and rl.leadid=ci.opportunity_id
                and rl.call_type ='complete'
                and rl.type='opportunity'
                and rl.logtype=lu.lookup_id
                and ud.user_id=rl.rep_id");
              return $query->result();
        }
        catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
        }

    } 

    public function leadLogData($leadId) {
        try {
                $query=$GLOBALS['$dbFramework']->query("
                SELECT lu.lookup_value as activity, ud.user_name as rep_name, ci.lead_name as customer_name, DATE_FORMAT(rl.starttime, '%D %b %Y %h:%i %p') AS 'starttime', rl.note as note, rl.rating as rating, rl.call_type as action, DATE_FORMAT(rl.endtime, '%Y/%m/%d %H:%i:%s') AS end,'completed' as status,DATE_FORMAT(rl.starttime, '%Y/%m/%d %H:%i:%s') AS start,rl.path as path,rl.logtype as conntype
                from lead_info ci, rep_log rl, user_details ud, lookup lu
                where rl.leadid='$leadId'
                and rl.leadid=ci.lead_id
                and rl.call_type ='complete'
                and rl.type='lead'
                and rl.logtype=lu.lookup_id
                and ud.user_id=rl.rep_id");
                return $query->result();
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }
    public function notificationShowStatus($notifyUpdateData,$cmp_lead,$user_id) {
        try{

            $update = $GLOBALS['$dbFramework']->update('notifications',$notifyUpdateData,array('task_id'=>$cmp_lead,'action' =>'assigned'));
            return $update;
        }
        catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    } 
    public function notificationShowStatusAccept($notifyUpdateData,$cmp_lead) {
        try{

            $update = $GLOBALS['$dbFramework']->update('notifications',$notifyUpdateData,array('task_id'=>$cmp_lead));
            return $update;
        }
        catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function fetchAssignedManager($leadCustId,$user) {
        try{

            $query = $GLOBALS['$dbFramework']->query("SELECT lcum.from_user_id as managerid,
                                                      ud.user_name as managername 
                                                      from lead_cust_user_map as lcum,user_details as ud 
                                                      where lcum.to_user_id = '$user'
                                                      AND lcum.action IN ('assigned','reassigned') 
                                                      AND lcum.module = 'manager'
                                                      AND lcum.lead_cust_id = '$leadCustId'
                                                      AND lcum.from_user_id = ud.user_id
                                                      AND lcum.type = 'customer'");
            return $query->result();
        }
        catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }       
}

?>