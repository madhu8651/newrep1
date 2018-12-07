<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_lead_model');

$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class manager_lead_model extends CI_Model{    
	public function __construct(){
		parent::__construct();
    } 

    public function lead_accept_mgr($data,$user,$lid){
    	try{
    		$query=$GLOBALS['$dbFramework']->insert('lead_cust_user_map', $data);
    		return $query;	        
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function checkForPrevMgr($leadid, $user) {
      //return null if lead_manager_owner is NULL or if given user's reporting manager
      //else returns lead_name of those unqualified
    	try{
	    	/*$query = $GLOBALS['$dbFramework']->query('
			select (
			CASE li.lead_manager_owner 
			WHEN ud.reporting_to THEN NULL 
			WHEN NULL THEN NULL 
			ELSE li.lead_name END) AS lead_manager_owner
			from lead_info li, user_details ud
			where li.lead_id="'.$leadid.'" AND ud.user_id="'.$user.'"');*/
			$query = $GLOBALS['$dbFramework']->query("call manager_checkForPrevMgr('$leadid','$user')");	
			$mgrOwner = $query->result();
			$mgrOwner = $mgrOwner[0]->lead_manager_owner;
			return $mgrOwner;
		}catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function lead_manager_owner_update($data,$lid) {
		try{
			$update = $GLOBALS['$dbFramework']->update('lead_info', $data,array('lead_id'=>$lid));       
			return $update;
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
    }

    public function getChildrenForParent($user_id) {
    	try{
	        $query = $GLOBALS['$dbFramework']->query("SELECT user_id, reporting_to FROM user_details");
	        $full_structure = $query->result();
	        $allParentNodes = [];
        		if (version_compare(phpversion(), '7.0.0', '<')) {
		          // php version isn't high enough to support array_column
		            foreach($full_structure as $row)  {
		                $allParentNodes[$row->user_id] = $row->reporting_to;
		            }
				}else {
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
				$GLOBALS['$logger']->debug("Child Nodes -- ".$ids);
		        return $ids;
		} catch (LConnectApplicationException $e) {
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

    public function fetch_leads($manager_id)  {
      /* Unassigned leads */  
		try{           
			$children = $manager_id."','";
			$children .= $this->getChildrenForParent($manager_id);
			$GLOBALS['$log']->debug('running new query for fetching unassigned leads');
			$query =$GLOBALS['$dbFramework']->query("
			SELECT a.lead_id as leadid, a.lead_name as leadname,
			coalesce(a.lead_address,'') as leadtaddress, coalesce(a.lead_city,'') as city, coalesce(a.lead_zip,'') as zipcode,
			coalesce(a.lead_website,'') as leadwebsite, coalesce(a.lead_manager_status,'') as leadstate,
			coalesce(a.lead_remarks,'') as repremarks,  coalesce(a.lead_location_coord,',') as coordinate,
			coalesce(JSON_UNQUOTE(a.lead_number->'$.phone[0]'), '') as leadphone,
			coalesce(JSON_UNQUOTE(a.lead_email->'$.email[0]'),'') as leademail,
			coalesce(b.contact_name,'-') as employeename, coalesce(b.contact_id,'-') as employeeid,
			coalesce(b.contact_desg,'-') as employeedesg, 
			coalesce(JSON_UNQUOTE(b.contact_number->'$.phone[0]'),'') as employeephone1,
			coalesce(JSON_UNQUOTE(b.contact_number->'$.phone[1]'),'') as employeephone2,
			coalesce(JSON_UNQUOTE(b.contact_email->'$.email[0]'),'') as employeeemail, 
			coalesce(JSON_UNQUOTE(b.contact_email->	'$.email[1]'),'') as employeeemail2,
			coalesce(ls.hvalue2,'') as leadsource,coalesce(e.lookup_value,'') as statename, coalesce(g.lookup_value,'-') as countryname,coalesce(g.lookup_id,'') as leadcountry, 
			coalesce(e.lookup_id,'') as state, coalesce(h.lookup_id,'') as contacttypeid,h.lookup_value as contactype,	coalesce(hk.hvalue2,'') as industry_name, hk1.hvalue2 as business_location_name, a.lead_industry as lead_industry, a.lead_business_loc as lead_business_loc,	udm.user_name as lead_owner, coalesce(GROUP_CONCAT(distinct h22.hvalue2),'') as product_names, a.lead_logo as leadlogo,coalesce(b.contact_address,'') as contPrsnAdd,a.lead_manager_status as rejected_manager
			FROM lead_info a 
			LEFT JOIN  contact_details b
			ON   (a.lead_id = b.lead_cust_id) 
			LEFT JOIN hierarchy ls   
			ON  a.lead_source=ls.hkey2
			LEFT JOIN lookup e
			on  a.lead_state=e.lookup_id   
			LEFT JOIN  lookup g
			on a.lead_country=g.lookup_id
			LEFT JOIN  lookup h 
			on b.contact_type=h.lookup_id  
			LEFT JOIN hierarchy hk
			on a.lead_industry=hk.hkey2			
			LEFT JOIN hierarchy hk1
			on a.lead_business_loc=hk1.hkey2	
			LEFT JOIN user_details udm
			on a.lead_manager_owner=udm.user_id	
			LEFT JOIN lead_product_map lpm
			on a.lead_id=lpm.lead_id
			LEFT join hierarchy h22
			on lpm.product_id=h22.hkey2
			LEFT join lead_cust_user_map lcm
			on a.lead_id=lcm.lead_cust_id
			WHERE (a.customer_id is NULL) 
			AND (b.contact_for = 'lead')
			AND (a.lead_manager_owner IN ('$children'))
			AND (a.lead_rep_owner is NULL)
			/*and a.lead_rep_status!='1' */
			/*and a.lead_manager_owner='$manager_id'*/
			AND a.lead_id NOT IN (
			SELECT DISTINCT lead_cust_id
			FROM lead_cust_user_map
			WHERE (to_user_id IN ('$children')) AND (module='sales'))
			AND b.contact_for = 'lead'        
			GROUP BY a.lead_id
			ORDER BY a.lead_name");
			return $query->result(); 
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}			     
    }
   
    

    public function fetch_assignleads($manager_id)  {
    	try{
    		
			$children = $manager_id."','";
			$children .= $this->getChildrenForParent($manager_id);
			$query = $GLOBALS['$dbFramework']->query("
			SELECT (CASE WHEN li.lead_rep_owner IS NOT NULL THEN (SELECT group_concat( DISTINCT a.hkey2) 
			from hierarchy a,user_mappings c, user_details d
			where a.hkey2=c.map_id 
			and c.map_type='clientele_industry' and d.team_id=c.transaction_id and c.user_id=li.lead_rep_owner) END) AS rep_industry,
			(CASE WHEN li.lead_rep_owner IS NOT NULL THEN (SELECT group_concat( DISTINCT a.hkey2) 
			from hierarchy a,user_mappings c, user_details d
			where a.hkey2=c.map_id 
			and c.map_type='business_location' and d.team_id=c.transaction_id and c.user_id=li.lead_rep_owner) END) AS rep_location,
			(CASE WHEN li.lead_rep_owner IS NOT NULL THEN (SELECT group_concat( DISTINCT a.hkey2) 
			from hierarchy a,user_mappings c, user_details d
			where a.hkey2=c.map_id 
			and c.map_type='product' and d.team_id=c.transaction_id and c.user_id=li.lead_rep_owner) END) AS rep_product,
			(case (select count(lead_cust_id) from lead_cust_user_map where lead_cust_id=li.lead_id and module='sales' and action='accepted' and state=1) when 0
			then 'pending' 
			else 'accepted'
			end )as leadstate,			
			(case (select count(lead_cust_id) from lead_cust_user_map where lead_cust_id=li.lead_id and module in('manager', 'sales') and action='reopened' and state=1) when 0
			then 'pending' 
			else 'reopen'
			end )as reopenstatus,
			 ud.user_state, li.lead_id AS leadid, li.lead_name AS leadname,
			coalesce((SELECT user_name FROM user_details WHERE user_id=li.lead_rep_owner), '') AS user_name,
			GROUP_CONCAT(DISTINCT ud.user_name) AS assigned_to,
			lcum.to_user_id, lcum.from_user_id, li.lead_zip AS zipcode, li.lead_website as leadwebsite,
			coalesce(li.lead_city, '') as city, JSON_UNQUOTE(li.lead_number->'$.phone[0]') as leadphone,
			JSON_UNQUOTE(coalesce(li.lead_email->'$.email[0]','')) as leademail,coalesce(li.lead_manager_owner,'') as lead_manager_owner,coalesce(li.lead_rep_owner,'') as lead_rep_owner,
			cd.contact_name as employeename,
			cd.contact_id as employeeid, coalesce(cd.contact_desg,'') as employeedesg, 
			JSON_UNQUOTE(coalesce(cd.contact_number->'$.phone[0]','')) as employeephone1,
			JSON_UNQUOTE(coalesce(cd.contact_number->'$.phone[1]','')) as employeephone2,
			JSON_UNQUOTE(coalesce(cd.contact_email->'$.email[0]','')) as employeeemail,
			JSON_UNQUOTE(coalesce(cd.contact_email->'$.email[1]','')) as employeeemail2,
			coalesce(ls.hvalue2,'') AS leadsource, e.lookup_value as statename,
			g.lookup_value as countryname, g.lookup_id as leadcountry, 
			e.lookup_id as state, h.lookup_value as contactype,h.lookup_id as contacttypeid, 
			coalesce(GROUP_CONCAT(distinct h22.hvalue2),'') as product_names,coalesce(cd.contact_address,'') as contPrsnAdd,coalesce(ud22.user_name,'') as mgr_owner,coalesce(li.lead_location_coord,',') as coordinate, coalesce(li.lead_remarks,'') as repremarks,
			li.lead_manager_status as rejected_manager,	li.lead_rep_status as rejected_sales,
			coalesce(hk.hvalue2,'') as industry_name, hk1.hvalue2 as business_location_name,li.lead_industry,coalesce(li.lead_address,'') as leadtaddress,
			li.lead_business_loc,li.lead_logo as leadlogo
			FROM (lead_info li
			LEFT JOIN contact_details cd
			ON cd.lead_cust_id=li.lead_id  
			LEFT JOIN lookup h ON cd.contact_type=h.lookup_id			       
			LEFT JOIN lead_cust_user_map lcum
			ON lcum.lead_cust_id=li.lead_id
			LEFT JOIN user_details ud ON lcum.to_user_id=ud.user_id   
			LEFT JOIN hierarchy ls ON li.lead_source=ls.hkey2
			LEFT JOIN  lookup e  ON li.lead_state=e.lookup_id 
			LEFT JOIN lookup g ON li.lead_country=g.lookup_id			
			LEFT JOIN lead_product_map lpm
			on li.lead_id=lpm.lead_id
			LEFT join hierarchy h22
			on lpm.product_id=h22.hkey2
			LEFT JOIN user_details ud22
			ON li.lead_manager_owner=ud22.user_id) 
			LEFT JOIN hierarchy hk
			on li.lead_industry=hk.hkey2			
			LEFT JOIN hierarchy hk1
			on li.lead_business_loc=hk1.hkey2     
			WHERE (li.customer_id is NULL) AND
			li.lead_manager_owner IN ('$children')
			AND li.lead_id IN (
			SELECT DISTINCT lead_cust_id
			FROM lead_cust_user_map)
			AND  lcum.module='sales'			
			and li.lead_status < '2'  			
			GROUP BY li.lead_id
			ORDER BY li.lead_name");
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}			
    }

    public function fetch_assignleads_others($manager_id)  {
    	try{

			$children = $manager_id."','";
			$children .= $this->getChildrenForParent($manager_id);
			$query = $GLOBALS['$dbFramework']->query("
			SELECT (case (select count(lead_cust_id) from lead_cust_user_map where lead_cust_id=li.lead_id and module='sales' and action='accepted' and state=1) when 0
			then 'pending' 
			else 'accepted'
			end )as leadstate, ud.user_state, li.lead_id AS leadid, li.lead_name AS leadname,
			coalesce((SELECT user_name FROM user_details WHERE user_id=li.lead_rep_owner), '') AS user_name,
			GROUP_CONCAT(DISTINCT ud.user_name) AS assigned_to,
			lcum.to_user_id, lcum.from_user_id, li.lead_zip AS zipcode, li.lead_website as leadwebsite,
			coalesce(li.lead_city, '') as city, JSON_UNQUOTE(li.lead_number->'$.phone[0]') as leadphone,
			JSON_UNQUOTE(coalesce(li.lead_email->'$.email[0]','')) as leademail,coalesce(li.lead_manager_owner,'') as lead_manager_owner,coalesce(li.lead_rep_owner,'') as lead_rep_owner,
			cd.contact_name as employeename,
			cd.contact_id as employeeid, coalesce(cd.contact_desg,'') as employeedesg, 
			JSON_UNQUOTE(coalesce(cd.contact_number->'$.phone[0]','')) as employeephone1,
			JSON_UNQUOTE(coalesce(cd.contact_number->'$.phone[1]','')) as employeephone2,
			JSON_UNQUOTE(coalesce(cd.contact_email->'$.email[0]','')) as employeeemail,
			JSON_UNQUOTE(coalesce(cd.contact_email->'$.email[1]','')) as employeeemail2,
			coalesce(ls.hvalue2,'') AS leadsource, e.lookup_value as statename,
			g.lookup_value as countryname, g.lookup_id as leadcountry, 
			e.lookup_id as state, h.lookup_value as contactype,h.lookup_id as contacttypeid, 
			coalesce(GROUP_CONCAT(distinct h22.hvalue2),'') as product_names,coalesce(cd.contact_address,'') as contPrsnAdd,coalesce(ud22.user_name,'') as mgr_owner,coalesce(li.lead_location_coord,',') as coordinate, coalesce(li.lead_remarks,'') as repremarks,
			li.lead_manager_status as rejected_manager,	li.lead_rep_status as rejected_sales,
			coalesce(hk.hvalue2,'') as industry_name, hk1.hvalue2 as business_location_name,li.lead_industry,coalesce(li.lead_address,'') as leadtaddress, li.lead_business_loc,li.lead_logo as leadlogo
			FROM (lead_info li
			LEFT JOIN contact_details cd
			ON cd.lead_cust_id=li.lead_id  
			LEFT JOIN lookup h ON cd.contact_type=h.lookup_id			       
			LEFT JOIN lead_cust_user_map lcum
			ON lcum.lead_cust_id=li.lead_id
			LEFT JOIN user_details ud ON lcum.to_user_id=ud.user_id   
			LEFT JOIN hierarchy ls ON li.lead_source=ls.hkey2
			LEFT JOIN  lookup e  ON li.lead_state=e.lookup_id 
			LEFT JOIN lookup g ON li.lead_country=g.lookup_id			
			LEFT JOIN lead_product_map lpm
			on li.lead_id=lpm.lead_id
			LEFT join hierarchy h22
			on lpm.product_id=h22.hkey2
			LEFT JOIN user_details ud22
			ON li.lead_manager_owner=ud22.user_id) 
			LEFT JOIN hierarchy hk
			on li.lead_industry=hk.hkey2			
			LEFT JOIN hierarchy hk1
			on li.lead_business_loc=hk1.hkey2     
			WHERE (li.customer_id is NULL) AND
			li.lead_rep_owner IN ('$children') and
			li.lead_manager_owner not in('$children')
			AND li.lead_id IN (
			SELECT DISTINCT lead_cust_id
			FROM lead_cust_user_map)
			AND  lcum.module='sales'			
			and li.lead_status<2  
			/*AND li.lead_id IN (
			SELECT DISTINCT lead_cust_id
			FROM lead_cust_user_map
			WHERE (to_user_id IN ('$children')) AND (module='sales'))
			-- Changes because of Reassigned changes .
			-- (to_user_id IN ('$children')) AND (module='sales'))
			AND  lcum.module='sales'    */   
			GROUP BY li.lead_id
			ORDER BY li.lead_name");
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}			
    }

    public function fetch_receivedlead($user)  {       
   		$query=$GLOBALS['$dbFramework']->query("call manager_ReceivedLeads('$user')");
   		return $query->result();
	   		//print_r($query);

    	/*try{     
			$query = $GLOBALS['$dbFramework']->query("
			SELECT a.lead_id as leadid, a.lead_name as leadname,
			a.lead_address as leadtaddress, a.lead_city as city, a.lead_zip as zipcode,
			a.lead_website as leadwebsite, a.lead_manager_status as leadstate,
			a.lead_remarks as repremarks, coalesce(a.lead_location_coord,',') as coordinate,
			JSON_UNQUOTE(a.lead_number->'$.leadphone[0]') as leadphone,
			JSON_UNQUOTE(a.lead_email->'$.leademail[0]') as leademail,
			c.contact_name as employeename, c.contact_id as employeeid,
			c.contact_desg as employeedesg, 
			JSON_UNQUOTE(c.contact_number->'$.phone[0]') as employeephone1,
			JSON_UNQUOTE(c.contact_number->'$.phone[1]') as employeephone2,
			JSON_UNQUOTE(c.contact_email->'$.email[0]') as employeeemail, 
			JSON_UNQUOTE(c.contact_email->'$.email[1]') as employeeemail2,
			coalesce(ls.hvalue2,'-') as leadsource, coalesce(GROUP_CONCAT(distinct h22.hvalue2),'')
			as product_names,coalesce(c.contact_address,'') as contPrsnAdd,hk.hvalue2 as industry_name, hk1.hvalue2 as business_location_name,a.lead_industry
			FROM lead_info a
			LEFT JOIN lead_cust_user_map b ON a.lead_id=b.lead_cust_id
			LEFT JOIN contact_details c ON a.lead_id=c.lead_cust_id
			LEFT JOIN hierarchy ls ON a.lead_source=ls.hkey2
			LEFT JOIN lead_product_map lpm
			on a.lead_id=lpm.lead_id
			LEFT join hierarchy h22
			on lpm.product_id=h22.hkey2
			LEFT JOIN hierarchy hk
			on a.lead_industry=hk.hkey2			
			LEFT JOIN hierarchy hk1
			on a.lead_business_loc=hk1.hkey2 
			WHERE  (b.lead_cust_id in 
			(SELECT lead_cust_id 
			FROM lead_cust_user_map 
			WHERE (to_user_id='$user'  and (action='assigned' or  action='reassigned' or action='reopened')  and type='lead' and state=1 and module='manager')))
			and (b.lead_cust_id not in 
			(SELECT lead_cust_id 
			FROM lead_cust_user_map 
			where (to_user_id='$user'  and (action='rejected')  and type='lead' and state=1 and module='manager')))		
			AND (a.customer_id is NULL) 
			AND b.lead_cust_id=a.lead_id 
			AND a.lead_manager_status=1			                 
			GROUP BY a.lead_id
			ORDER BY a.lead_name");
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	*/
    }

    public function fetchOtherWonLead($manager_id)
    {
    	try{
			$children = $manager_id."','";
			$children .= $this->getChildrenForParent($manager_id);
			$GLOBALS['$log']->debug('running new query for fetching unassigned leads');
			$query =$GLOBALS['$dbFramework']->query("
			SELECT a.lead_id as leadid, a.lead_name as leadname,
			coalesce(a.lead_address,'') as leadtaddress, coalesce(a.lead_city,'') as city, coalesce(a.lead_zip,'') as zipcode,
			coalesce(a.lead_website,'') as leadwebsite, coalesce(a.lead_manager_status,'') as leadstate,
			coalesce(a.lead_remarks,'') as repremarks,  coalesce(a.lead_location_coord,',') as coordinate,
			coalesce(JSON_UNQUOTE(a.lead_number->'$.phone[0]'), '')as leadphone,
			coalesce(JSON_UNQUOTE(a.lead_email->'$.email[0]'),'') as leademail,
			coalesce(b.contact_name,'') as employeename, coalesce(b.contact_id,'') as employeeid,
			coalesce(b.contact_desg,'') as employeedesg, 
			coalesce(JSON_UNQUOTE(b.contact_number->'$.phone[0]'),'') as employeephone1,
			coalesce(JSON_UNQUOTE(b.contact_number->'$.phone[1]'),'') as employeephone2,
			coalesce(JSON_UNQUOTE(b.contact_email->'$.email[0]'),'') as employeeemail, 
			coalesce(JSON_UNQUOTE(b.contact_email->'$.email[1]'),'') as employeeemail2,
			coalesce(ls.hvalue2,'') as leadsource,coalesce(e.lookup_value,'') as statename, coalesce(g.lookup_value,'') as countryname,coalesce(g.lookup_id,'') as leadcountry, 
			coalesce(e.lookup_id,'') as state, coalesce(h.lookup_id,'') as contacttypeid,
			a.lead_closed_reason as reason, a.lead_rep_owner as lead_rep_owner,
			coalesce((SELECT user_name FROM user_details WHERE user_id=a.lead_rep_owner), '') AS user_name, coalesce(GROUP_CONCAT(distinct h22.hvalue2),'') as product_names, a.lead_logo as leadlogo,coalesce(b.contact_address,'') as contPrsnAdd,hk.hvalue2 as industry_name, hk1.hvalue2 as business_location_name,a.lead_industry
			FROM lead_info a 
			LEFT JOIN  contact_details b
			ON   (a.lead_id = b.lead_cust_id) 
			LEFT JOIN hierarchy ls   
			ON  a.lead_source=ls.hkey2
			LEFT JOIN lookup e
			on  a.lead_state=e.lookup_id   
			LEFT JOIN  lookup g
			on a.lead_country=g.lookup_id
			LEFT JOIN  lookup h 
			on b.contact_type=h.lookup_id 
			LEFT JOIN lead_product_map lpm
			on a.lead_id=lpm.lead_id
			LEFT join hierarchy h22
			on lpm.product_id=h22.hkey2
			LEFT JOIN hierarchy hk
			on a.lead_industry=hk.hkey2			
			LEFT JOIN hierarchy hk1
			on a.lead_business_loc=hk1.hkey2 
			WHERE /*(a.customer_id is NULL) */
			 (b.contact_for = 'lead')
			AND (a.lead_rep_owner IN ('$children') and
			a.lead_manager_owner not in('$children'))                               
			AND b.contact_for = 'lead' 
			AND a.lead_status=2
			and a.lead_closed_reason='closed_won'        
			GROUP BY a.lead_id
			ORDER BY a.lead_name");
			return $query->result();   
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}    	# code...
    }

    public function fetch_leads_won($manager_id){
    	try{
			$children = $manager_id."','";
			$children .= $this->getChildrenForParent($manager_id);
			$GLOBALS['$log']->debug('running new query for fetching unassigned leads');
			$query =$GLOBALS['$dbFramework']->query("
			SELECT a.lead_id as leadid, a.lead_name as leadname,
			coalesce(a.lead_address,'') as leadtaddress, coalesce(a.lead_city,'') as city, coalesce(a.lead_zip,'') as zipcode,
			coalesce(a.lead_website,'') as leadwebsite, coalesce(a.lead_manager_status,'') as leadstate,
			coalesce(a.lead_remarks,'') as repremarks,  coalesce(a.lead_location_coord,',') as coordinate,
			coalesce(JSON_UNQUOTE(a.lead_number->'$.phone[0]'), '')as leadphone,
			coalesce(JSON_UNQUOTE(a.lead_email->'$.email[0]'),'') as leademail,
			coalesce(b.contact_name,'') as employeename, coalesce(b.contact_id,'') as employeeid,
			coalesce(b.contact_desg,'') as employeedesg, 
			coalesce(JSON_UNQUOTE(b.contact_number->'$.phone[0]'),'') as employeephone1,
			coalesce(JSON_UNQUOTE(b.contact_number->'$.phone[1]'),'') as employeephone2,
			coalesce(JSON_UNQUOTE(b.contact_email->'$.email[0]'),'') as employeeemail, 
			coalesce(JSON_UNQUOTE(b.contact_email->'$.email[1]'),'') as employeeemail2,
			coalesce(ls.hvalue2,'') as leadsource,coalesce(e.lookup_value,'') as statename, coalesce(g.lookup_value,'') as countryname,coalesce(g.lookup_id,'') as leadcountry, 
			coalesce(e.lookup_id,'') as state, coalesce(h.lookup_id,'') as contacttypeid,
			a.lead_closed_reason as reason, a.lead_rep_owner as lead_rep_owner,
			coalesce((SELECT user_name FROM user_details WHERE user_id=a.lead_rep_owner), '') AS user_name, coalesce(GROUP_CONCAT(distinct h22.hvalue2),'') as product_names, a.lead_logo as leadlogo,coalesce(b.contact_address,'') as contPrsnAdd,hk.hvalue2 as industry_name, hk1.hvalue2 as business_location_name,a.lead_industry
			FROM lead_info a 
			LEFT JOIN  contact_details b
			ON   (a.lead_id = b.lead_cust_id) 
			LEFT JOIN hierarchy ls   
			ON  a.lead_source=ls.hkey2
			LEFT JOIN lookup e
			on  a.lead_state=e.lookup_id   
			LEFT JOIN  lookup g
			on a.lead_country=g.lookup_id
			LEFT JOIN  lookup h 
			on b.contact_type=h.lookup_id 
			LEFT JOIN lead_product_map lpm
			on a.lead_id=lpm.lead_id
			LEFT join hierarchy h22
			on lpm.product_id=h22.hkey2
			LEFT JOIN hierarchy hk
			on a.lead_industry=hk.hkey2			
			LEFT JOIN hierarchy hk1
			on a.lead_business_loc=hk1.hkey2 
			WHERE /*(a.customer_id is NULL) */
			 (b.contact_for = 'lead')
			AND (a.lead_manager_owner IN ('$children'))                               
			AND b.contact_for = 'lead' 
			AND a.lead_status=2
			and a.lead_closed_reason='closed_won'        
			GROUP BY a.lead_id
			ORDER BY a.lead_name");
			return $query->result();   
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	   
    }

    public function fetchOtherLostLead($manager_id)
    {
     	try{

			$children = $manager_id."','";
			$children .= $this->getChildrenForParent($manager_id);
			$GLOBALS['$log']->debug('running new query for fetching unassigned leads');
			$query =$GLOBALS['$dbFramework']->query("
			SELECT a.lead_id as leadid, a.lead_name as leadname,
			coalesce(a.lead_address,'') as leadtaddress, coalesce(a.lead_city,'') as city, coalesce(a.lead_zip,'') as zipcode,
			coalesce(a.lead_website,'-') as leadwebsite, coalesce(a.lead_manager_status,'') as leadstate,
			coalesce(a.lead_remarks,'-') as repremarks,  coalesce(a.lead_location_coord,',') as coordinate,
			coalesce(JSON_UNQUOTE(a.lead_number->'$.phone[0]'), '')as phone,
			coalesce(JSON_UNQUOTE(a.lead_email->'$.email[0]'),'') as leademail,
			coalesce(b.contact_name,'') as employeename, coalesce(b.contact_id,'-') as employeeid,
			coalesce(b.contact_desg,'') as employeedesg, 
			coalesce(JSON_UNQUOTE(b.contact_number->'$.phone[0]'),'') as employeephone1,
			coalesce(JSON_UNQUOTE(b.contact_number->'$.phone[1]'),'') as employeephone2,
			coalesce(JSON_UNQUOTE(b.contact_email->'$.email[0]'),'') as employeeemail, 
			coalesce(JSON_UNQUOTE(b.contact_email->'$.email[1]'),'') as employeeemail2,
			coalesce(ls.hvalue2,'') as leadsource,coalesce(e.lookup_value,'') as statename, coalesce(g.lookup_value,'') as countryname,coalesce(g.lookup_id,'') as leadcountry, 
			coalesce(e.lookup_id,'') as state, coalesce(h.lookup_id,'') as contacttypeid, 
			a.lead_closed_reason as reason, a.lead_rep_owner as lead_rep_owner,
			coalesce((SELECT user_name FROM user_details WHERE user_id=a.lead_rep_owner), '') AS user_name, coalesce(GROUP_CONCAT(distinct h22.hvalue2),'') as product_names, a.lead_logo as leadlogo,coalesce(b.contact_address,'') as contPrsnAdd,hk.hvalue2 as industry_name, hk1.hvalue2 as business_location_name,a.lead_industry,a.lead_business_loc, coalesce(a.lead_manager_owner, '') as mowner, coalesce(a.lead_rep_owner, '') as rowner
			FROM lead_info a 
			LEFT JOIN  contact_details b
			ON  a.lead_id = b.lead_cust_id 
			LEFT JOIN hierarchy ls   
			ON  a.lead_source=ls.hkey2
			LEFT JOIN lookup e
			on  a.lead_state=e.lookup_id   
			LEFT JOIN  lookup g
			on a.lead_country=g.lookup_id
			LEFT JOIN  lookup h 
			on b.contact_type=h.lookup_id
			LEFT JOIN lead_product_map lpm
			on a.lead_id=lpm.lead_id
			LEFT join hierarchy h22
			on lpm.product_id=h22.hkey2
			LEFT JOIN hierarchy hk
			on a.lead_industry=hk.hkey2			
			LEFT JOIN hierarchy hk1
			on a.lead_business_loc=hk1.hkey2 
			WHERE (a.customer_id is NULL) 
			AND (b.contact_for = 'lead') AND
			(a.lead_rep_owner IN ('$children') and
			a.lead_manager_owner not in('$children'))                              
			AND b.contact_for = 'lead' 
			AND (a.lead_status=3 or a.lead_status=4)			      
			GROUP BY a.lead_id
			ORDER BY a.lead_name");
			return $query->result();   
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}   	# code...
    }

    public function fetch_leads_lost($manager_id){
    	try{
			$children = $manager_id."','";
			$children .= $this->getChildrenForParent($manager_id);

			$GLOBALS['$log']->debug('running new query for fetching unassigned leads');
			$query =$GLOBALS['$dbFramework']->query("
			SELECT a.lead_id as leadid, a.lead_name as leadname,
			coalesce(a.lead_address,'') as leadtaddress, coalesce(a.lead_city,'') as city, coalesce(a.lead_zip,'') as zipcode,
			coalesce(a.lead_website,'-') as leadwebsite, coalesce(a.lead_manager_status,'') as leadstate,
			coalesce(a.lead_remarks,'-') as repremarks,  coalesce(a.lead_location_coord,',') as coordinate,
			coalesce(JSON_UNQUOTE(a.lead_number->'$.phone[0]'), '')as phone,
			coalesce(JSON_UNQUOTE(a.lead_email->'$.email[0]'),'') as leademail,
			coalesce(b.contact_name,'') as employeename, coalesce(b.contact_id,'-') as employeeid,
			coalesce(b.contact_desg,'') as employeedesg, 
			coalesce(JSON_UNQUOTE(b.contact_number->'$.phone[0]'),'') as employeephone1,
			coalesce(JSON_UNQUOTE(b.contact_number->'$.phone[1]'),'') as employeephone2,
			coalesce(JSON_UNQUOTE(b.contact_email->'$.email[0]'),'') as employeeemail, 
			coalesce(JSON_UNQUOTE(b.contact_email->'$.email[1]'),'') as employeeemail2,
			coalesce(ls.hvalue2,'') as leadsource,coalesce(e.lookup_value,'') as statename, coalesce(g.lookup_value,'') as countryname,coalesce(g.lookup_id,'') as leadcountry, 
			coalesce(e.lookup_id,'') as state, coalesce(h.lookup_id,'') as contacttypeid, 
			a.lead_closed_reason as reason, a.lead_rep_owner as lead_rep_owner,
			coalesce((SELECT user_name FROM user_details WHERE user_id=a.lead_rep_owner), '') AS user_name, coalesce(GROUP_CONCAT(distinct h22.hvalue2),'') as product_names, a.lead_logo as leadlogo,coalesce(b.contact_address,'') as contPrsnAdd,hk.hvalue2 as industry_name, hk1.hvalue2 as business_location_name,a.lead_industry,a.lead_business_loc, coalesce(a.lead_manager_owner, '') as mowner, coalesce(a.lead_rep_owner, '') as rowner
			FROM lead_info a 
			LEFT JOIN  contact_details b
			ON  a.lead_id = b.lead_cust_id 
			LEFT JOIN hierarchy ls   
			ON  a.lead_source=ls.hkey2
			LEFT JOIN lookup e
			on  a.lead_state=e.lookup_id   
			LEFT JOIN  lookup g
			on a.lead_country=g.lookup_id
			LEFT JOIN  lookup h 
			on b.contact_type=h.lookup_id
			LEFT JOIN lead_product_map lpm
			on a.lead_id=lpm.lead_id
			LEFT join hierarchy h22
			on lpm.product_id=h22.hkey2
			LEFT JOIN hierarchy hk
			on a.lead_industry=hk.hkey2			
			LEFT JOIN hierarchy hk1
			on a.lead_business_loc=hk1.hkey2 
			WHERE (a.customer_id is NULL) 
			AND (b.contact_for = 'lead')
			AND (a.lead_manager_owner IN ('$children'))                               
			AND b.contact_for = 'lead' 
			AND (a.lead_status=3 or a.lead_status=4)			      
			GROUP BY a.lead_id
			ORDER BY a.lead_name");
			return $query->result();   
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}			   
    }
    public function lead_history($id){
    	try{	

			$query = $GLOBALS['$dbFramework']->query("call manager_lead_history('$id')");
			return $query->result();

		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}			
    }

    public function lead_historydetails($id){
    	try{
			$query = $GLOBALS['$dbFramework']->query("call manager_lead_history_details('$id')");
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}			
    }

    public function insert_product($data){
    	try{
    		$GLOBALS['$dbFramework']->insert('lead_product_map', $data);  
    	}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}     
    }

    public function delete_prod($leadid) {
    	try{
			$GLOBALS['$dbFramework']->delete('lead_product_map' , array('lead_id' => $leadid));
			return true;
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
    }

    public function products($lid){
    	try{
			$query=$GLOBALS['$dbFramework']->query("select * from lead_product_map where lead_id='$lid'");
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
    }

    public function get_selproduct(){
    	try{
			$userid=$this->session->userdata('uid');
			$query=$GLOBALS['$dbFramework']->query("SELECT a.map_id as product_id, a.map_type,b.hvalue2 as product_name
			from
			user_mappings a, hierarchy b
			where 
			a.map_type='product' and a.user_id='$userid' and a.map_id=b.hkey2
			group by a.map_id");

			return $query->result();
		}catch (LConnectApplicationException $e) { 
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}			
    }

    public function fetch_logs($id){
        try{  
			$query = $GLOBALS['$dbFramework']->query("call manager_lead_fetch_logs('$id')");     
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}           
    }

	public function industry($user){
  		try{
			/*$query=$this->db->query("SELECT a.hvalue2 as industry_name,a.hkey2 as industry_id 
			from hierarchy a,user_mappings c, user_details d
			where a.hkey2=c.map_id 
			and c.map_type='clientele_industry' and d.team_id=c.transaction_id and c.user_id='$user'
			group by a.hkey2");*/
			$query = $this->db->query("SELECT b.hvalue2 as industry_name, a.map_id as industry_id from user_mappings a,hierarchy b 
            where a.map_id=b.hkey2 and a.map_type='clientele_industry' and a.user_id='$user'");
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}			
	}

	public function location($user){
		try{
			$query=$this->db->query("SELECT a.hvalue2 as business_location_name,a.hkey2 as business_location_id 
            from hierarchy a,user_mappings c
            where a.hkey2=c.map_id 
            and c.map_type='business_location'
            and c.user_id='$user'
            group by a.hkey2");
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}

        public function logs_unassigned($id){ 
    	try{                 
			$query = $GLOBALS['$dbFramework']->query("SELECT li.lead_name as leadname, coalesce(rl.rating,'-') as rating,rl.endtime as end, rl.note as note,rl.starttime as start,

				ud.user_name as rep_name,lkp.lookup_value as type, rl.call_type as status, rl.path, rl.logtype as conntype

			from lead_info li, rep_log rl,user_details ud, lookup lkp

			where rl.leadid='$id'

			and rl.leadid=li.lead_id

            and ud.user_id=rl.rep_id   

            and lkp.lookup_id=rl.logtype        

			and rl.call_type='complete'");
			
			return $query->result();                
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
    }

    public function logs_scheduleactivity($id){ 
    	try{                  
			$query = $GLOBALS['$dbFramework']->query("call manager_lead_logs_schedule_activity('$id')");
			return $query->result();    
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}
    
	
	public function opportunity_list($id) {   
     try{               
   $query= $GLOBALS['$dbFramework']->query("call manager_lead_opportunity_list('$id')");
   return $query->result();     
  }catch (LConnectApplicationException $e) {
   $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
   throw $e;
  }        
    }
	
    public function view_data(){  
    	try{              
			$query= $GLOBALS['$dbFramework']->query("SELECT a.rep_id,b.user_name as repname,b.designation,c.user_name as manager,e.lookup_value as region,
			f.lookup_value as location,d.teamname,a.rep_actvstate
			FROM rep_info a,user_details b,user_details c,teams d,lookup e,lookup f 
			WHERE a.rep_id=b.user_id
			AND a.rep_mgr=c.user_id                               
			AND b.region=e.lookup_id
			AND b.location=f.lookup_id
			AND b.team_id=d.teamid
			ORDER BY b.user_name");
			return $query->result();
    	} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}   
    }
    public function rep_list($manager_id){
    	try{
			$query= $GLOBALS['$dbFramework']->query("SELECT user_name as repname, user_id as rep_id from user_details
			where reporting_to='$manager_id'");       
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
    }

    public function get_listof_rep($manager_id)   {
    	try{
			$query= $GLOBALS['$dbFramework']->query("SELECT user_name as repname, user_id as rep_id from user_details
			where reporting_to='$manager_id'");       
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}
    public function get_listofmgr($user)   {
    	try{
			$children .= $id."','";
			$query= $GLOBALS['$dbFramework']->query("
			SELECT a.user_name, a.user_id, b.sales_module, b.manager_module 
			from user_details a, user_licence b
			where (a.reporting_to in ('$children')) and a.user_id=b.user_id");                    
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
   }
  

public function getrep_products($lid,$user)    {
    	try{
    		$old_lead_count=count($lid);
			$lid=implode("','", $lid);			
			$children = $user."','";
			$children .= $this->getChildrenForParent($user);
			$emptyproduct=$GLOBALS['$dbFramework']->query("SELECT count(distinct lpm.lead_id) as id FROM lead_product_map as lpm where lpm.lead_id IN ('$lid')");
			$leadcount=$emptyproduct->result();			
			$new_lead_count=$leadcount[0]->id;	

			if($old_lead_count==$new_lead_count){	
			#if user has sales module and its not assigned or reassigned or accepted by him
			#then return 1 so that he can assign
			#else return 0			
			$query3 = $GLOBALS['$dbFramework']->query("SELECT lead_industry from lead_info where lead_id in('$lid') and lead_industry!=''");		
			$query4 = $GLOBALS['$dbFramework']->query("SELECT lead_business_loc from lead_info where lead_id in('$lid') and lead_business_loc!=''");
			if($query3->num_rows()>0 && $query4->num_rows()>0){
				$query= $GLOBALS['$dbFramework']->query("
				SELECT um.user_id,ud.user_name,
				(CASE WHEN 
				(ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
				FROM lead_cust_user_map lp
				WHERE lp.lead_cust_id IN ('$lid')
				AND lp.action IN ('assigned', 'reassigned','accepted','reopened','created')
				AND lp.state = 1
				AND lp.module = 'sales'
				AND lp.to_user_id = um.user_id) = 0
				THEN 1
				ELSE 0
				END) AS sales_module,
				(CASE WHEN
				(ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
				FROM lead_cust_user_map lp
				WHERE lp.lead_cust_id IN ('$lid')
				AND lp.action IN ('assigned', 'reassigned', 'accepted','reopened','created')
				AND lp.state = 1
				AND lp.module = 'manager'
				AND lp.to_user_id = um.user_id) = 0
				THEN 1
				ELSE 0
				END) AS manager_module
				FROM
				user_mappings um,
				lead_product_map lp,
				user_licence ul,
				user_details ud,
				user_mappings um3
				WHERE
				um.map_type = 'product'
				AND um3.map_type = 'sell_type'
				AND um3.map_id = 'new_sell'
				AND lp.lead_id IN ('$lid')
				AND um.map_id = lp.product_id
				AND ul.user_id = um.user_id
				AND ud.user_id = um.user_id
				and ud.user_id=um3.user_id
				GROUP BY um.user_id
				HAVING COUNT(distinct lp.product_id) = (SELECT COUNT(distinct product_id)
				FROM lead_product_map
				WHERE lead_id IN ('$lid'))
				AND um.user_id IN (SELECT ud1.user_id
				FROM
				user_mappings um1,
				user_details ud1,        
				user_mappings um2,       	
				lead_info li    
				WHERE			
				li.lead_id IN ('$lid')		
				AND um1.map_type = 'clientele_industry'
				AND um1.map_id = li.lead_industry
				AND um2.map_type = 'business_location'
				AND um2.map_id = li.lead_business_loc
				AND (ud1.reporting_to in('$children')
				OR ud1.user_id = '$user')
				AND ud1.user_state = 1
				) order by lp.product_id");
				return $query->result();	

			}else if($query3->num_rows()>0){
				$query= $GLOBALS['$dbFramework']->query("
				SELECT um.user_id,ud.user_name,
				(CASE WHEN 
				(ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
				FROM lead_cust_user_map lp
				WHERE lp.lead_cust_id IN ('$lid')
				AND lp.action IN ('assigned', 'reassigned','accepted','reopened','created')
				AND lp.state = 1
				AND lp.module = 'sales'
				AND lp.to_user_id = um.user_id) = 0
				THEN 1
				ELSE 0
				END) AS sales_module,
				(CASE WHEN
				(ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
				FROM lead_cust_user_map lp
				WHERE lp.lead_cust_id IN ('$lid')
				AND lp.action IN ('assigned', 'reassigned', 'accepted','reopened','created')
				AND lp.state = 1
				AND lp.module = 'manager'
				AND lp.to_user_id = um.user_id) = 0
				THEN 1
				ELSE 0
				END) AS manager_module
				FROM
				user_mappings um,
				lead_product_map lp,
				user_licence ul,
				user_details ud,
				user_mappings um3
				WHERE
				um.map_type = 'product'
				AND um3.map_type = 'sell_type'
				AND um3.map_id = 'new_sell'
				AND lp.lead_id IN ('$lid')
				AND um.map_id = lp.product_id
				AND ul.user_id = um.user_id
				AND ud.user_id = um.user_id
				and ud.user_id=um3.user_id
				GROUP BY um.user_id
				HAVING COUNT(distinct lp.product_id) = (SELECT COUNT(distinct product_id)
				FROM lead_product_map
				WHERE lead_id IN ('$lid'))
				AND um.user_id IN (SELECT ud1.user_id
				FROM
				user_mappings um1,
				user_details ud1,        
				/*user_mappings um2, */      	
				lead_info li    
				WHERE			
				li.lead_id IN ('$lid')		
				AND um1.map_type = 'clientele_industry'
				AND um1.map_id = li.lead_industry
				/*AND um2.map_type = 'business_location'
				AND um2.map_id = li.lead_business_loc*/
				AND (ud1.reporting_to in('$children')
				OR ud1.user_id = '$user')
				AND ud1.user_state = 1
				)order by lp.product_id");
				return $query->result();

			}else if($query4->num_rows()>0){
				$query= $GLOBALS['$dbFramework']->query("
				SELECT um.user_id,ud.user_name,
				(CASE WHEN 
				(ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
				FROM lead_cust_user_map lp
				WHERE lp.lead_cust_id IN ('$lid')
				AND lp.action IN ('assigned', 'reassigned','accepted','reopened','created')
				AND lp.state = 1
				AND lp.module = 'sales'
				AND lp.to_user_id = um.user_id) = 0
				THEN 1
				ELSE 0
				END) AS sales_module,
				(CASE WHEN
				(ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
				FROM lead_cust_user_map lp
				WHERE lp.lead_cust_id IN ('$lid')
				AND lp.action IN ('assigned', 'reassigned', 'accepted','reopened','created')
				AND lp.state = 1
				AND lp.module = 'manager'
				AND lp.to_user_id = um.user_id) = 0
				THEN 1
				ELSE 0
				END) AS manager_module
				FROM
				user_mappings um,
				lead_product_map lp,
				user_licence ul,
				user_details ud,
				user_mappings um3
				WHERE
				um.map_type = 'product'
				AND um3.map_type = 'sell_type'
				AND um3.map_id = 'new_sell'
				AND lp.lead_id IN ('$lid')
				AND um.map_id = lp.product_id
				AND ul.user_id = um.user_id
				AND ud.user_id = um.user_id
				and ud.user_id=um3.user_id
				GROUP BY um.user_id
				HAVING COUNT(distinct lp.product_id) = (SELECT COUNT(distinct product_id)
				FROM lead_product_map
				WHERE lead_id IN ('$lid'))
				AND um.user_id IN (SELECT ud1.user_id
				FROM
				/*user_mappings um1,*/
				user_details ud1,        
				user_mappings um2,       	
				lead_info li    
				WHERE			
				li.lead_id IN ('$lid')		
				/*AND um1.map_type = 'clientele_industry'
				AND um1.map_id = li.lead_industry*/
				AND um2.map_type = 'business_location'
				AND um2.map_id = li.lead_business_loc
				AND (ud1.reporting_to in('$children')
				OR ud1.user_id = '$user')
				AND ud1.user_state = 1
				)order by lp.product_id");
				return $query->result();

			}else {
				$query= $GLOBALS['$dbFramework']->query("
				SELECT um.user_id,ud.user_name,
				(CASE WHEN 
				(ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
				FROM lead_cust_user_map lp
				WHERE lp.lead_cust_id IN ('$lid')
				AND lp.action IN ('assigned', 'reassigned','accepted','reopened','created')
				AND lp.state = 1
				AND lp.module = 'sales'
				AND lp.to_user_id = um.user_id) = 0
				THEN 1
				ELSE 0
				END) AS sales_module,
				(CASE WHEN
				(ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
				FROM lead_cust_user_map lp
				WHERE lp.lead_cust_id IN ('$lid')
				AND lp.action IN ('assigned', 'reassigned', 'accepted','reopened','created')
				AND lp.state = 1
				AND lp.module = 'manager'
				AND lp.to_user_id = um.user_id) = 0
				THEN 1
				ELSE 0
				END) AS manager_module
				FROM
				user_mappings um,
				lead_product_map lp,
				user_licence ul,
				user_details ud,
				user_mappings um3
				WHERE
				um.map_type = 'product'
				AND um3.map_type = 'sell_type'
				AND um3.map_id = 'new_sell'
				AND lp.lead_id IN ('$lid')
				AND um.map_id = lp.product_id
				AND ul.user_id = um.user_id
				AND ud.user_id = um.user_id
				and ud.user_id=um3.user_id
				GROUP BY um.user_id
				HAVING COUNT(distinct lp.product_id) = (SELECT COUNT(distinct product_id)
				FROM lead_product_map
				WHERE lead_id IN ('$lid'))
				AND um.user_id IN (SELECT ud1.user_id
				FROM
				/*user_mappings um1,*/
				user_details ud1,        
				/*user_mappings um2,*/       	
				lead_info li    
				WHERE
				(ud1.reporting_to in('$children')
				OR ud1.user_id = '$user')
				AND ud1.user_state = 1
				)order by lp.product_id");
				return $query->result();

			}	
      	}else if($new_lead_count==0){
      			$query3 = $GLOBALS['$dbFramework']->query("SELECT lead_industry from lead_info where lead_id in('$lid') and lead_industry!=''");
      			$query4 = $GLOBALS['$dbFramework']->query("SELECT lead_business_loc from lead_info where lead_id in('$lid') and lead_business_loc!=''");
      			if($query3->num_rows()>0 && $query4->num_rows()>0){      			
      				$query= $GLOBALS['$dbFramework']->query("
			        SELECT a.user_name, a.user_id,
			       (CASE WHEN 
					(ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
					FROM lead_cust_user_map lp
					WHERE lp.lead_cust_id IN ('$lid')
						AND lp.action IN ('assigned', 'reassigned','accepted','reopened','created')
						AND lp.state = 1
						AND lp.module = 'sales'
						AND lp.to_user_id = um.user_id) = 0
					THEN 1
					ELSE 0
					END) AS sales_module,
					(CASE WHEN
					(ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
					FROM lead_cust_user_map lp
					WHERE lp.lead_cust_id IN ('$lid')
						AND lp.action IN ('assigned', 'reassigned', 'accepted','reopened','created')
						AND lp.state = 1
						AND lp.module = 'manager'
						AND lp.to_user_id = um.user_id) = 0
					THEN 1
					ELSE 0
					END) AS manager_module
					from user_details a, user_licence ul,user_mappings um,
					lead_info li,user_mappings um1,user_mappings um2
					where 
					li.lead_id in('$lid')
					and (a.reporting_to in('$children') or a.user_id='$user')
					and um.map_type = 'sell_type'	
			        and um.map_id = 'new_sell'
			        AND um1.map_type = 'clientele_industry'
					AND um1.map_id = li.lead_industry
					AND um2.map_type = 'business_location'
					AND um2.map_id = li.lead_business_loc
			        and um.user_id=a.user_id
			        and a.user_id=ul.user_id 
			        AND a.user_state = 1			      
			        group by a.user_id");                    
			        return $query->result(); 

      			}else if($query3->num_rows()>0){
      				$query= $GLOBALS['$dbFramework']->query("
			        SELECT a.user_name, a.user_id,
			       (CASE WHEN 
					(ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
					FROM lead_cust_user_map lp
					WHERE lp.lead_cust_id IN ('$lid')
						AND lp.action IN ('assigned', 'reassigned','accepted','reopened','created')
						AND lp.state = 1
						AND lp.module = 'sales'
						AND lp.to_user_id = um.user_id) = 0
					THEN 1
					ELSE 0
					END) AS sales_module,
					(CASE WHEN
					(ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
					FROM lead_cust_user_map lp
					WHERE lp.lead_cust_id IN ('$lid')
						AND lp.action IN ('assigned', 'reassigned', 'accepted','reopened','created')
						AND lp.state = 1
						AND lp.module = 'manager'
						AND lp.to_user_id = um.user_id) = 0
					THEN 1
					ELSE 0
					END) AS manager_module
					from user_details a, user_licence ul,user_mappings um,
					lead_info li,user_mappings um1
					where 
					li.lead_id in('$lid')
					and (a.reporting_to in('$children') or a.user_id='$user')
					and um.map_type = 'sell_type'	
			        and um.map_id = 'new_sell'
			        AND um1.map_type = 'clientele_industry'
					AND um1.map_id = li.lead_industry				
			        and um.user_id=a.user_id
			        and a.user_id=ul.user_id 
			        AND a.user_state = 1			      
			        group by a.user_id");                    
			        return $query->result(); 

      			}else if($query4->num_rows()>0){
      				$query= $GLOBALS['$dbFramework']->query("
			        SELECT a.user_name, a.user_id,
			       (CASE WHEN 
					(ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
					FROM lead_cust_user_map lp
					WHERE lp.lead_cust_id IN ('$lid')
						AND lp.action IN ('assigned', 'reassigned','accepted','reopened','created')
						AND lp.state = 1
						AND lp.module = 'sales'
						AND lp.to_user_id = um.user_id) = 0
					THEN 1
					ELSE 0
					END) AS sales_module,
					(CASE WHEN
					(ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
					FROM lead_cust_user_map lp
					WHERE lp.lead_cust_id IN ('$lid')
						AND lp.action IN ('assigned', 'reassigned', 'accepted','reopened','created')
						AND lp.state = 1
						AND lp.module = 'manager'
						AND lp.to_user_id = um.user_id) = 0
					THEN 1
					ELSE 0
					END) AS manager_module
					from user_details a, user_licence ul,user_mappings um,
					lead_info li,user_mappings um2
					where 
					li.lead_id in('$lid')
					and (a.reporting_to in('$children') or a.user_id='$user')
					and um.map_type = 'sell_type'	
			        and um.map_id = 'new_sell'			       
					AND um2.map_type = 'business_location'
					AND um2.map_id = li.lead_business_loc
			        and um.user_id=a.user_id
			        and a.user_id=ul.user_id 
			        AND a.user_state = 1			      
			        group by a.user_id");                    
			        return $query->result();     

      			}else {
      				$query= $GLOBALS['$dbFramework']->query("
			        SELECT a.user_name, a.user_id,
			       (CASE WHEN 
					(ul.sales_module <> '0') AND (SELECT COUNT(lp.to_user_id)
					FROM lead_cust_user_map lp
					WHERE lp.lead_cust_id IN ('$lid')
						AND lp.action IN ('assigned', 'reassigned','accepted','reopened','created')
						AND lp.state = 1
						AND lp.module = 'sales'
						AND lp.to_user_id = um.user_id) = 0
					THEN 1
					ELSE 0
					END) AS sales_module,
					(CASE WHEN
					(ul.manager_module <> '0') AND (SELECT COUNT(lp.to_user_id)
					FROM lead_cust_user_map lp
					WHERE lp.lead_cust_id IN ('$lid')
						AND lp.action IN ('assigned', 'reassigned', 'accepted','reopened','created')
						AND lp.state = 1
						AND lp.module = 'manager'
						AND lp.to_user_id = um.user_id) = 0
					THEN 1
					ELSE 0
					END) AS manager_module
					from user_details a, 
					user_licence ul,
					user_mappings um
					where 
					(a.reporting_to in('$children') or a.user_id='$user')		
					and um.map_type = 'sell_type'	
			        and um.map_id = 'new_sell'
			        and um.user_id=a.user_id
			        and a.user_id=ul.user_id 
			        and a.user_state=1			      
			        group by a.user_id");                    
			        return $query->result();

      			}
			}else{
					return array();
			}	
    	}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		} 
    }

 
 
    public function getReportingPersons($user){    
    	try{  
			$children = $user."','";
			$children .= $this->getChildrenForParent($user);
			$query= $GLOBALS['$dbFramework']->query("
			SELECT user_id 
			FROM user_details 
			WHERE user_id IN ('$children') 
			GROUP BY user_id");             
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}			
    }
   
    public function get_rep_id($leadid){  
    	try{              
			$qry = $GLOBALS['$dbFramework']->query("SELECT lead_rep_owner from lead_info where lead_id='$leadid'");      
			return $qry->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
    }   
    public function rep_data($rep_id){     
	    try{
	       	$query= $GLOBALS['$dbFramework']->query("SELECT * FROM rep_info WHERE rep_id='$rep_id'");   
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
    }
    public function leadstate_update($leadid,$state){ 
    	try{      
			$this->db->where('lead_id', $leadid);
			$query=$this->db->update('lead_info', $state);             
			return true;
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}			
    }
   public function reassign_state_zero($lead_state,$module){         
		try{
			$GLOBALS['$logger']->debug(json_encode($lead_state));
			$lid=implode("','", $lead_state);
			$GLOBALS['$logger']->debug($lid);
			$query= $GLOBALS['$dbFramework']->query("UPDATE lead_cust_user_map SET state=0 
													where lead_cust_id in('$lid')
													and module='$module'
													and type='lead'"); 
			return $query; 
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}  
	
   public function update_reminder_table($manager_activity,$rep_activity){
  /*$query= $GLOBALS['$dbFramework']->update_batch('lead_reminder', $rep_activity, 'lead_id',);	
*/   		//$stringRepresentation= json_encode($rep_activity);
  		try{ 
  			$stringRepresentation=implode("','", $rep_activity);
  			$stringRepresentation1 = implode("','", $manager_activity);
  			
	  			if(!empty($stringRepresentation)){  				
					$query=$GLOBALS['$dbFramework']->query("UPDATE lead_reminder  set rep_id=null
					where lead_id in ('$stringRepresentation') and module_id='sales' and status in('pending',
					'scheduled') and type='lead'");  		
	  			}	

				if(!empty($stringRepresentation1)){
					$query=$GLOBALS['$dbFramework']->query("UPDATE lead_reminder  set rep_id=null
		   			where lead_id in ('$stringRepresentation1') and module_id='manager' and status in('pending','scheduled') and type='lead'");
				}
			}catch (LConnectApplicationException $e) {
				$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
				throw $e;
		}	
    }

	public function rep_assign($data){         
		try{  			
			$query= $GLOBALS['$dbFramework']->insert_batch('lead_cust_user_map', $data);            
			return $query;
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}

	public function rep_owner_update($insertrepowner){  
		try{   
			$query= $GLOBALS['$dbFramework']->update_batch('lead_info',$insertrepowner, 'lead_id');
			return $query;  
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
	}

	public function lead_manager_status_assigned($status_arr){
		try{
				$query= $GLOBALS['$dbFramework']->update_batch('lead_info',$status_arr, 'lead_id');
				return true;       
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}		
	}


	public function leadretrieve($leadid){
		try{
			$query= $GLOBALS['$dbFramework']->query("SELECT  leadname from lead_info where leadid='$leadid'");  
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}
   
    public function product_view($lid){
    	try{
			$query=$GLOBALS['$dbFramework']->query("select a.product_id,b.hvalue2 from lead_product_map a, hierarchy b 
				where a. product_id=b.hkey2 and lead_id='$lid'
				group by a.product_id");
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
   }

    public function product($user){     //fetch products based on team not yet finished 
    	try{              
			$query= $GLOBALS['$dbFramework']->query("SELECT JSON_UNQUOTE(a.hvalue2) as product_name, c.map_id as product_id
			from
			hierarchy a, user_mappings c
			where
			c.user_id='$user'
			and a.hkey2=c.map_id
			and c.map_type='product'
			group by a.hkey2");  
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
   }
 
  public function leadsource1(){
  		try{                  
			$query =  $GLOBALS['$dbFramework']->query("SELECT b.id,b.hierarchy_id,b.hkey2,b.hvalue2,b.hkey1,b.hvalue1
			from
			hierarchy_class a,hierarchy b
			where
			a.Hierarchy_Class_Name='lead_source'
			and b.hkey2!='0'
			and b.Hierarchy_Class_ID=a.hierarchy_class_id
			order by b.hierarchy_id;");                 
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
  }

	public function productdata_edit($leadid){
		try{
			$json=array();
			$b=array();
			$c=array();
			$query=$GLOBALS['$dbFramework']->query("select b.id,b.hierarchy_id,b.hkey2,b.hvalue2,b.hkey1,b.hvalue1
			from hierarchy_class a,hierarchy b where a.Hierarchy_Class_Name='lead_source'
			and b.hkey2!='0' and b.Hierarchy_Class_ID=a.hierarchy_class_id  order by b.hierarchy_id;");
				if($query->num_rows()>0){
				$rowdata=$query->result_array();
					for($row=0;$row<count($rowdata);$row++){
					$lead_srcid=$rowdata[$row]['hkey2'];
						$query=$GLOBALS['$dbFramework']->query("SELECT lead_source from lead_info where lead_source='$lead_srcid' and lead_id='$leadid'");
						if($query->num_rows()>0){

							$json[$row]['id'] = $rowdata[$row]['hkey2'];
							$json[$row]['name'] = $rowdata[$row]['hvalue2'];
							$a1=$rowdata[$row]['hkey1'];
								if($a1=='0'){
								$json[$row]['parent'] = "";
								}else{
								$json[$row]['parent'] = $rowdata[$row]['hkey1'];
								}
							$json[$row]['checked'] = true;
							$json[$row]['nameAttr'] = 'Editsource';
						}else{
								$json[$row]['id'] = $rowdata[$row]['hkey2'];
								$json[$row]['name'] = $rowdata[$row]['hvalue2'];
								$a1=$rowdata[$row]['hkey1'];
										if($a1=='0'){
											$json[$row]['parent'] = "";
										}else{
											$json[$row]['parent'] = $rowdata[$row]['hkey1'];
										}
							$json[$row]['checked'] = false;
							$json[$row]['nameAttr'] = 'Editsource';
						}
					}
				}
			return $json;
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
	}


	public function view_country(){   
		try{               
			$query= $GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='country'");
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
	}

	public function insert_lead1($data) {    
		try{    
			$query= $GLOBALS['$dbFramework']->insert('lead_info',$data);         
			return $query;
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	        
	}

	public function state($cid){  
		try{                
			$query= $GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='$cid'");     
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}			
	}

	public function insert_details1($data) {
		try{
			$GLOBALS['$dbFramework']->insert('contact_details',$data); 
			return TRUE;
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
	}

	public function insert_rep($data) {
		try{
			$lead=  $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data); 
			return $lead;
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
	}

	public function contact(){   
		try{
			$GLOBALS['$log']->debug("fetching Bio persona");
			$query= $GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='Buyer Persona'");
			return $query->result();
		}
		catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
	}
   
	 public function update_leadPhoto($leadid,$data) {
        try{
	  $update = $GLOBALS['$dbFramework']->update('lead_info' ,$data, array('lead_id' => ($leadid)));
      return $update;
      } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    
	public function update_info($leadid,$data) {
		try{
			$update=$GLOBALS['$dbFramework']->update('lead_info', $data,array('lead_id'=> $leadid));    
			return $update;
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
	}

	public function update_details($employeeid,$data) {
		try{
			$update=$GLOBALS['$dbFramework']->update('contact_details', $data,array('contact_id'=> $employeeid));
			return $update;  
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	    
	}

	public function update_rep($leadid,$data){
		try{
			$update=$GLOBALS['$dbFramework']->update('lead_cust_user_map', $data,array('contact_id'=> $lead_cust_id));
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}
  
	public function country(){
		try{
			$query=$GLOBALS['$dbFramework']->query("SELECT LOWER(lookup_value) as country_name,lookup_id FROM lookup WHERE lookup_name='country'");
			return $query->result();
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
	}
	public function get_state(){
		try{
			$query=$GLOBALS['$dbFramework']->query("SELECT b.lookup_id as state_id,
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
	public function get_leads(){
		try{
			$query=$GLOBALS['$dbFramework']->query("select LOWER(a.lead_name) as leadname,
			JSON_UNQUOTE(a.lead_number->'$.leadphone[0]') as leadphone from lead_info a,
			contact_details b where a.lead_id=b.lead_cust_id");
			return $query->result();
		}
		catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
	}
	public function insert_details($leads,$contacts,$transaction){
		try{
			$query1=$GLOBALS['$dbFramework']->insert_batch("lead_info",$leads);
			$query2=$GLOBALS['$dbFramework']->insert_batch('contact_details',$contacts);
			$query3=$GLOBALS['$dbFramework']->insert_batch('lead_cust_user_map',$transaction);
			return $query1 && $query2 && $query3;
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}	
	}
    public function fetchAssignedManager($leadCustId,$user) {
        try{

            $query = $GLOBALS['$dbFramework']->query("SELECT lcum.from_user_id as managerid,
                                                      ud.user_name as managername,ud.reporting_to as Admin
                                                      from lead_cust_user_map as lcum,user_details as ud 
                                                      where lcum.to_user_id = '$user'
                                                      AND lcum.action IN ('assigned','reassigned') 
                                                      AND lcum.module = 'manager'
                                                      AND lcum.lead_cust_id = '$leadCustId'
                                                      AND lcum.from_user_id = ud.user_id
                                                      AND lcum.type = 'lead'");
            return $query->result();
        }
        catch(LConnectApplicationException $e){
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


public function updateLeadMgrOwner($leadid, $user) {
		//returns true or false indicating whether lead manager owner was successfully updated or not
	try{
	/*	$query = $this->db->query('
		UPDATE lead_info 
		SET lead_manager_owner="'.$user.'", lead_manager_status=1
		WHERE lead_id="'.$leadid.'" AND lead_manager_status=0');*/
	/*	$updateStatus = $this->db->affected_rows();      */
			/*if ($updateStatus < 1) {
			return false;
			}*/
			$mapping_id = uniqid(rand(),TRUE);
			$data=array(
			'lead_cust_id' => $leadid,
			'from_user_id' => $user,
			'to_user_id' => $user,
			'action'=>'accepted',
			'module'=>'manager',
			'state'=>'1',
			'type' =>'lead',
			'mapping_id'=>$mapping_id,
			'timestamp'=>date('Y-m-d H:i:s')
			);
			$data1=array(        
			'state'=>'0');
			$data2=array('lead_manager_status'=>'2',
						 'lead_manager_owner'=>$user);
			//insert a row into transaction table as you have updated owner
			$update1=$GLOBALS['$dbFramework']->update('lead_info', $data2,array('lead_id'=>$leadid));
			$update=$GLOBALS['$dbFramework']->update('lead_cust_user_map', $data1,array('lead_cust_id'=>$leadid,'module'=>'manager'));
				if($update){
				$insertQuery = $GLOBALS['$dbFramework']->insert('lead_cust_user_map', $data); 
				}        
				if ($insertQuery == false) {
				return false;        
				}
				$update3=$GLOBALS['$dbFramework']->query("UPDATE lead_reminder  set rep_id='$user'
				where lead_id='$leadid' and module_id='manager' and status in('pending',
				'scheduled') and type='lead'");
				return $leadid;
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}		
	}	
	public function lead_status_assigned($status_arr){
		try{
				$query= $GLOBALS['$dbFramework']->update_batch('lead_info',$status_arr, 'lead_id');
				return true;       
		}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}		
	}

    public function check_leadname($lead_name,$lead_id){
    	try{
    			if($lead_id==''){
    				$query= $GLOBALS['$dbFramework']->query("SELECT lead_name from lead_info where LCASE(lead_name)='".strtolower($lead_name)."'");
					if($query->num_rows()>0){
						return 1;
					}else{
						return 0;
					}	
    			}else{
    				$query= $GLOBALS['$dbFramework']->query("SELECT lead_name from lead_info where LCASE(lead_name)='".strtolower($lead_name)."' and lead_id!='$lead_id'");
					if($query->num_rows()>0){
						return 1;
					}else{
						return 0;
					}	

    			}
								       
			}catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}		

    }
    public function count_assign_lead($lid){
    	try{
			$query= $GLOBALS['$dbFramework']->query("SELECT count(lead_cust_id) from lead_cust_user_map 
			where lead_cust_id='$lid' and (action='assigned' or action='reassigned') and module='manager' and state=1");
			return $query->result();
		}
		catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}			
    }

    public function count_rejected_lead($lid){
     	try{     	
			$query= $GLOBALS['$dbFramework']->query("SELECT count(lead_cust_id) from lead_cust_user_map 
			where lead_cust_id='$lid' and action='rejected' and module='manager' and state=1");
			return $query->result();
		}	
    	catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
    }

	public function update_leadtable($lid,$mstatus){
		try{			
			$GLOBALS['$logger']->debug('updating lead_manager_status for rejected lead');
			$query=$GLOBALS['$dbFramework']->query("UPDATE lead_info li 
			INNER JOIN lead_cust_user_map lcm ON lcm.lead_cust_id = li.lead_id			
			SET li.lead_manager_status = 3, /*li.lead_rep_status = 0,*/lcm.state = 0	    
			WHERE li.lead_id = '$lid'
			and lcm.lead_cust_id='$lid'
			and lcm.module='manager'
			and (lcm.action='assigned' or lcm.action='reassigned' or action='rejected') ");
			return $query;

		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}		
	}
	public function dual_check($lid){
		try{
			$query=$GLOBALS['$dbFramework']->query("SELECT  (
			SELECT COUNT(lead_cust_id)
			FROM   lead_cust_user_map
			where lead_cust_id='$lid'
			and action='assigned'
			) AS count1,
			(
			SELECT COUNT(lead_cust_id)
			FROM   lead_cust_user_map
			where lead_cust_id='$lid'
			and action='rejected'
			) AS count2
			FROM    dual");
			return $query->result();
		}	
		catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}		
	}
	 
public function fetchCustomLead($leadId){
       try{

          $query=$GLOBALS['$dbFramework']->query("
         SELECT aa.attribute_key,aa.attribute_name,aa.attribute_type,coalesce(li.attribute,'') as attribute,li.lead_id as id,aa.module as module,aa.attribute_validation_string
          from `admin_attributes` as aa,lead_info as li
          where li.lead_id='$leadId'
          and module IN ('Lead')");
          return $query->result();   
      } 
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
     }
    }

 public function fetchAddCustomLead(){
       try{

          $query=$GLOBALS['$dbFramework']->query("
         SELECT aa.attribute_key,aa.attribute_name,aa.attribute_type,aa.module as module,aa.attribute_validation_string
          from `admin_attributes` as aa
          where  aa.module IN ('Lead')");
          return $query->result();   
      } 
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
     }
    }

	public function user_plugin($userid){
		try{
			$query= $GLOBALS['$dbFramework']->query("select b.plugin_id from user_details a,user_module_plugin_mapping b where a.user_id=b.user_id
												and a.user_id='$userid'");
												return $query->result();
		}
		catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}		
	}
		
	
	public function insert_lead_cust_table($edit_array){
		try{
			$query=$GLOBALS['$dbFramework']->insert('lead_cust_user_map', $edit_array);
		}
		catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}		
	}

	public function lead_reopen($lead1,$lead2,$lead3,$lead4,$lead5){
		try{					
			$query= $GLOBALS['$dbFramework']->update_batch('lead_info',$lead1, 'lead_id');			
			$query1= $GLOBALS['$dbFramework']->update_batch('lead_cust_user_map',$lead2, 'lead_cust_id');	
			$query2= $GLOBALS['$dbFramework']->insert_batch('lead_cust_user_map',$lead3);
			$query3= $GLOBALS['$dbFramework']->insert_batch('lead_cust_user_map',$lead4);
			$query4= $GLOBALS['$dbFramework']->update_batch('lead_info',$lead5, 'lead_id');
			return $query2;	
								
		}
		catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}		
	}
	
   public function fetchSuperiorManager() {
       try{
            $query=$GLOBALS['$dbFramework']->query("
													SELECT 
													ud.user_id as superior_id,ud.user_name as superior_name
													FROM
													user_details AS ud,
													user_details AS ud1
													WHERE
													ud.reporting_to IS NULL
													AND ud.user_id = ud1.reporting_to
            										");
            return $query->result();
        }catch (LConnectApplicationException $e){
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


    public function insertNotificationAssigned($assignedNotificationArray) {
        try{
                $insert = $GLOBALS['$dbFramework']->insert_batch('notifications',$assignedNotificationArray);
                return $insert;
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }  

    public function updateLeadInfoData($updateLeadArray) {
        try{
            
            $insert = $GLOBALS['$dbFramework']->update_batch('lead_info',$updateLeadArray,'lead_id');
                return $insert;
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    } 
 
    public function assignedUserData($assignedDataArray) {
        try{
            
            $insert = $GLOBALS['$dbFramework']->insert_batch('lead_cust_user_map',$assignedDataArray);
                return $insert;
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }          

    public function getLeadName($custid) {
		try{

		$query=$GLOBALS['$dbFramework']->query("SELECT lead_name FROM lead_info where lead_id in('$custid')");
		return $query->result();  
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


    public function fetchAllContacts($leadCustId) {
      try{

        $query=$GLOBALS['$dbFramework']->query("
                SELECT cd.contact_id AS contact_id, cd.contact_name AS contact_name, cd.contact_for AS contact_for,
					coalesce(lo.lookup_value,'-') AS contact_type_name, lo.lookup_id AS contact_type_id,
					li.lead_id AS lead_cust_id, li.lead_name AS lead_cust_name,
					JSON_UNQUOTE(coalesce(cd.contact_number->'$.phone[0]','')) as employeephone1,
					JSON_UNQUOTE(coalesce(cd.contact_number->'$.phone[1]','')) as employeephone2,
					JSON_UNQUOTE(coalesce(cd.contact_email->'$.email[0]','')) as employeeemail,
					JSON_UNQUOTE(coalesce(cd.contact_email->'$.email[1]','')) as employeeemail2,
					coalesce(cd.contact_desg,'-') as contact_desg,coalesce(cd.contact_photo,'') as contact_photo, cd.contact_created_time,
					coalesce(cd.contact_dob,'-') AS contact_dob, cd.contact_address AS contact_address, cd.remarks AS remarks
				FROM lead_info AS li, contact_details AS cd LEFT JOIN `lookup` AS lo ON cd.contact_type = lo.lookup_id AND lo.lookup_name = 'Buyer Persona'
				WHERE li.lead_id='$leadCustId' AND (li.lead_id=cd.lead_cust_id)
				GROUP BY cd.contact_id
				ORDER BY cd.contact_name");
        return $query->result();
      }
      catch(LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;

      }
    }	

	public function getContact(){
	$query = $GLOBALS['$dbFramework']->query("select co.contact_number ,li.lead_name, li.lead_number from contact_details co 
												join lead_info li on li.lead_id=co.lead_cust_id");
	return $query->result();
	}

	public function getManagerOwner($manager_id){
			$children = $manager_id."','";
			$children .= $this->getChildrenForParent($manager_id);		
			$query = $GLOBALS['$dbFramework']->query("SELECT lead_name, lead_manager_owner from lead_info
														WHERE lead_manager_owner in('$children')");
			var_dump($query->result());
	}

	public function validateOpportunityandStageOwner($lead_id){
		$query = $GLOBALS['$dbFramework']->query("SELECT li.lead_id from lead_info li, opportunity_details od
													WHERE li.lead_id = od.lead_cust_id
													and li.lead_manager_owner = od.manager_owner_id
													and li.lead_manager_owner = od.stage_manager_owner_id");

		if(count($query->result())<=0){
			return 1;
		}else{
			return 0;
		}

	}

	 public function opportunity_check($leadid,$user_id){
        try{//ex
            $query=$GLOBALS['$dbFramework']->query("select * from opportunity_details where lead_cust_id='$leadid' and closed_status!=100");   

            $query1 = $GLOBALS['$dbFramework']->query("SELECT 
                                                    	*
                                                    FROM
                                                    opportunity_details
                                                    WHERE
                                                    closed_status != 100
                                                    AND lead_cust_id = '$leadid'
                                                    AND (stage_manager_owner_id = '$user_id'
                                                    OR manager_owner_id = '$user_id')");    
          
            if(count($query->result())>0 && count($query1->result())<=0){
                return 1;  // user dosenot have permision to close the lead, because he is not the opportunity manager owner or opportunity manager owner of that opportunity
            }else if(count($query->result())>0){
                return 2; // logged in user is the opportunity owner of that lead or stage manager owner or lead manager owner
            }else{
                return 0; // This lead is does not have any opportunity, so user can close the lead withour prompt.
            }

        
        } catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
        }
    }

    public function opportunityCount($leadid){
    	$query=$GLOBALS['$dbFramework']->query("select opportunity_id from opportunity_details where lead_cust_id='$leadid' and closed_status!=100"); 
    	return $query->result();
    }

    public function permanentCloseOpportunity($leadid,$data) {
        try{
      $update = $GLOBALS['$dbFramework']->update('opportunity_details' ,$data, array('lead_id' => ($leadid)));
      return $update;
      } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }


    public function check_opportunity_owner($leadid,$user_id)
    {
        $query = $GLOBALS['$dbFramework']->query("
                                                    SELECT 
                                                    *
                                                    FROM
                                                    opportunity_details
                                                    WHERE
                                                    closed_status != 100
                                                    AND lead_cust_id = '$leadid'
                                                    AND (stage_manager_owner_id = '$user_id'
                                                    OR manager_owner_id = '$user_id')
                                                ");
        $result ['value'] = $query->result();

        if ($query->num_rows() > 0) 
        {
            $result['result'] =  true;
        }
        else
        {
            $result['result'] =  false;
        }

        return $result;
 
    }

    public function fetchContactsForLead($leadid){
        //Query should changed according to tables and Include Customer also.
        try {
                $query =$GLOBALS['$dbFramework']->query("
                SELECT cd.contact_id , cd.contact_name 
                FROM contact_details cd
                WHERE cd.lead_cust_id = '$leadid'");
                return $query->result(); 
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
     }   

     public function managerPermissionForAcceptReject($user_id){
     	 $query =$GLOBALS['$dbFramework']->query("SELECT user_id from user_details
     	 											where user_id='$user_id'
     	 											and reporting_to in (select user_id from user_details where user_name='Admin')");
     	if(count($query->result())>0){
     	 	return 1;
     	}else{
     		return 0;
     	}
    } 	

    public function updateLeadinfoReopen($leadTableUpdate,$leadid)
    {
    	$update = $GLOBALS['$dbFramework']->update('lead_info', $data,array('lead_id'=>$leadid));       
		return $update;
    }
}

?>