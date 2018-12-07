<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('webservice_model');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class webservice_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}

	/*--------------------------  BASIC SETUP & LOGIN --------------------------*/
	public function validate($loginID,$password){
		$query = $GLOBALS['$dbFramework']->query("
																										SELECT 
													a.user_id AS user_id,
													a.user_name AS user_name,
													c.user_id AS rep_id,
													c.location_tracking AS location_tracking,
													c.call_recording AS call_recording,
													c.holiday_calender AS holidaycalendar,
													a.team_id AS team_id,
													a.department AS department,
													b.module_id AS modules,
													b.plugin_id AS plugins,
													a.reporting_to AS reporting_to,
													a.photo AS user_photo,
													a.user_gender AS user_gender,
													a.last_name AS user_last_name,
													a.tokenid AS user_firebase_token,
													a.address1 AS user_address,
													a.dob AS user_dob,
													a.designation AS user_designation,
													a.user_timezone AS user_timezone,
													a.user_primary_email AS user_primary_email,
													a.user_primary_mobile AS user_primary_mobile,
													a.user_state AS user_state,
													a.login_state AS login_state,
													t.teamname AS team_name,
													ud.user_name AS reporting_to_name,
													d.Department_name AS department_name,
													ur.role_name AS user_designation_name,
													upd.device_brand AS device_brand,
													upd.device_ID AS device_id,
													upd.device_model AS device_model,
													upd.device_os_version AS device_os_version,
													upd.device_type AS device_type,
													upd.IMEI AS imei,
													upd.SIM_network_type AS sim_network_type
													FROM
													user_details a 
													LEFT JOIN user_app_details AS upd
													ON a.user_id = upd.user_id,
													user_module_plugin_mapping b,
													representative_details c,
													teams AS t,
													department AS d,
													user_details AS ud,
													user_roles AS ur,
													user_module_plugin_mapping AS ul
													WHERE
													a.user_id = b.user_id
													AND a.user_id = c.user_id
													AND a.login_pwd = BINARY('$password')
													AND a.user_state = 1
													AND a.app_login_state = 0
													AND a.login_id = '$loginID'
													AND a.team_id = t.teamid
													AND ul.module_id->'$.sales' != '0'
                                                    AND ul.user_id = a.user_id
													AND a.department = d.Department_id
													AND a.reporting_to = ud.user_id
													AND a.designation = ur.role_id
													GROUP BY a.user_id
												");

		return $query;
	}

	public function insertDeviceInfo($user_id,$deviceArray,$devID) 
	{
		# code...
		$query1=$GLOBALS['$dbFramework']->query("
													SELECT 
													* 
													FROM 
													user_app_details as upd 
													WHERE 
													upd.user_id = '$user_id' 
												");

		if ($query1->num_rows()>0) 
		{
			$query =$GLOBALS['$dbFramework']->query("
													SELECT 
													* 
													FROM user_app_details as upd 
													WHERE 
													upd.user_id = '$user_id' 
													AND
													upd.device_ID = '$devID'
												");
			if ($query->num_rows() > 0) 
			{
				return true;
			}
			else
			{
				return false;
			}

		}
		else
		{
			$insert = $GLOBALS['$dbFramework']->insert('user_app_details',$deviceArray);
			return $insert;
		}

	}

	public function updateRep($devId,$repId){
		$query1=$GLOBALS['$dbFramework']->query("UPDATE representative_details SET rep_devid='$devId' WHERE user_id ='$repId'");
		return $query1;
	}

	public function updateLoginState($repId){
		$query1=$GLOBALS['$dbFramework']->query("UPDATE user_details SET login_state=1 WHERE user_id ='$repId'");
		return $query1;
	}

	public function user_details($userID) {
		$query = $GLOBALS['$dbFramework']->query("SELECT * FROM user_details where user_id='$userID'"); 
		return $query->result();
	}

	public function get_user_mapping($user_id){
		$query = $GLOBALS['$dbFramework']->query("
		(SELECT a.id, a.user_mapping_id, a.user_id, a.map_type, a.map_id, coalesce(a.map_key,'') as map_key, coalesce(a.map_value,'') as map_value, coalesce(b.hvalue2,'') as map_name 
		FROM user_mappings a LEFT JOIN hierarchy b on  a.map_id = b.hkey2
		WHERE a.user_id='$user_id' and a.map_type IN ('sell_type','business_location','product','office_location','clientele_industry'))
		UNION
		(SELECT h.id, h.hierarchy_id as user_mapping_id, '$user_id' as user_id, h.hvalue1 as map_type, h.hkey2 as map_id, '' as map_key, '' as map_value, h.hvalue2 as map_name 
		FROM hierarchy h WHERE h.hvalue1='Lead Source' and h.hvalue2='Others')");
		return $query;
	}

	public function get_currency(){
		$query = $GLOBALS['$dbFramework']->query("SELECT * FROM currency");
		return $query;
	}

	public function phone_fetch_lookup(){
		$query = $GLOBALS['$dbFramework']->query("SELECT * FROM lookup");
		return $query->result();
	}

	public function all_user_details() {
		$query = $GLOBALS['$dbFramework']->query("SELECT * FROM user_details"); 
		return $query->result();
	}

	public function reset_login_state($username) {
		$query1=$GLOBALS['$dbFramework']->query("UPDATE user_details SET app_login_state=0 WHERE login_id = BINARY '$username'");
		return $query1;		
	}
	/*---------------------------------- LEADS ---------------------------------*/
	public function get_newleads($userid){
            $query=$GLOBALS['$dbFramework']->query("
                SELECT distinct a.lead_name,a.lead_created_by,a.lead_industry,a.lead_business_loc,a.lead_id,a.lead_location_coord,a.contact_number,
                a.lead_closed_reason,a.lead_approach_date,
                a.lead_rep_status,a.lead_website,a.lead_rep_owner,a.lead_remarks,a.lead_created_time,a.lead_manager_owner,
                a.lead_zip,a.lead_address,a.lead_logo,a.lead_country,a.lead_source,a.lead_city, a.lead_state, a.lead_status,
                JSON_UNQUOTE(a.lead_number->'$.phone[0]') as leadphone, 
                JSON_UNQUOTE(a.lead_email->'$.email[0]') as leademail,a.customer_id
                FROM lead_info a,contact_details b   
                WHERE a.lead_id = b.lead_cust_id and a.lead_rep_status=1 and 
                a.lead_id in (SELECT lead_cust_id FROM lead_cust_user_map where to_user_id='$userid' and 
                action in ('assigned','reassigned') and type='lead'  and state=1 group by lead_cust_id)
               and a.lead_id not in  (SELECT lead_cust_id FROM lead_cust_user_map where from_user_id='$userid' and 
                action='rejected'  and type='lead' and module='sales' and state=1 group by lead_cust_id) group by a.lead_id");
            return $query->result();
	}

	public function get_acceptleads($userid){
		$query=$GLOBALS['$dbFramework']->query("
			SELECT distinct a.lead_name,a.lead_created_by,a.lead_industry,a.lead_business_loc,a.lead_id,a.lead_location_coord,a.contact_number,
				a.lead_closed_reason,a.lead_approach_date,
				a.lead_rep_status,a.lead_website,a.lead_rep_owner,a.lead_remarks,a.lead_created_time,a.lead_manager_owner,
				a.lead_zip,a.lead_address,a.lead_logo,a.lead_country,a.lead_source,a.lead_city, a.lead_state, a.lead_status,
				JSON_UNQUOTE(a.lead_number->'$.phone[0]') as leadphone, 
				JSON_UNQUOTE(a.lead_email->'$.email[0]') as leademail,a.customer_id
			FROM lead_info a,contact_details b   
			WHERE a.lead_id = b.lead_cust_id and lead_status=0 and lead_rep_status=2
			and a.lead_rep_owner='$userid'");
		return $query->result();
	}

	public function get_progressleads($userid){
		$query=$GLOBALS['$dbFramework']->query("
			SELECT distinct a.lead_id,a.lead_name,a.lead_status,a.lead_created_by,a.lead_industry,a.lead_business_loc,
				a.lead_location_coord,a.contact_number,a.lead_closed_reason,a.lead_approach_date,
				a.lead_rep_status,a.lead_website,a.lead_rep_owner,a.lead_remarks,a.lead_created_time,a.lead_manager_owner,
				a.lead_zip,a.lead_address,a.lead_logo,a.lead_country,a.lead_source,a.lead_city, a.lead_state,  a.lead_status,
				JSON_UNQUOTE(a.lead_number->'$.phone[0]') as leadphone, JSON_UNQUOTE(a.lead_email->'$.email[0]') as leademail,a.customer_id
			FROM lead_info a,contact_details b   
			WHERE a.lead_id = b.lead_cust_id and a.lead_status=1 and lead_rep_status=2 and a.lead_rep_owner='$userid'");
		return $query->result();
	}

	public function get_closedleads($userid){
		$query=$GLOBALS['$dbFramework']->query("
			SELECT distinct a.lead_name,a.lead_status,a.lead_created_by,a.lead_industry,a.lead_business_loc,a.lead_id,
				a.lead_location_coord,a.contact_number,a.lead_closed_reason,a.lead_approach_date,
				a.lead_rep_status,a.lead_website,a.lead_rep_owner,a.lead_remarks,a.lead_created_time,a.lead_manager_owner,
				a.lead_zip,a.lead_address,a.lead_logo,a.lead_country,a.lead_source,a.lead_city, a.lead_state,  a.lead_status,
				JSON_UNQUOTE(a.lead_number->'$.phone[0]') as leadphone, JSON_UNQUOTE(a.lead_email->'$.email[0]') as leademail,a.customer_id
			FROM lead_info a,contact_details b   
			WHERE a.lead_id = b.lead_cust_id  and a.lead_rep_owner='$userid' and (a.lead_status='2' or a.lead_status='3' or a.lead_status='4')");
		return $query->result();
	}

	public function check_lead_name($leadname){
		$query=$GLOBALS['$dbFramework']->query("SELECT count(*) as total from lead_info where LCASE(lead_name)='".$leadname."'");
		return $query->result();
	}
	
	public function fetch_leadsource() {
		$query = $GLOBALS['$dbFramework']->query("
			SELECT h.id as id, h.hierarchy_id as lookup_id, 
			hc.Hierarchy_Class_Name as lookup_name, h.hkey2 as lookup_key, h.hvalue2 as lookup_value
			FROM hierarchy h, hierarchy_class hc 
			WHERE hc.Hierarchy_Class_Name = 'lead_source' AND 
			hc.Hierarchy_Class_ID = h.hierarchy_class_id AND h.hvalue2 <> 'Lead Source'
			GROUP BY h.hkey2
			ORDER BY h.hvalue2");
		return $query->result();
	}

	public function insert_lead($data) {
		$insert = $GLOBALS['$dbFramework']->insert('lead_info',$data); 
		return $insert;
	}

	public function insert_lead_cust_trans($data){
		$insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data); 
		return $insert;
	}

	public function update_lead_cust_trans($leadid){
		$query=$GLOBALS['$dbFramework']->query("
			UPDATE lead_cust_user_map 
			SET state=0 
			WHERE lead_cust_id='$leadid' and action in ('assigned','reassigned') and state=1 and module='sales'");
		return TRUE;
	}

	public function update_lead_reminder($user_id,$leadid) {
		$query=$GLOBALS['$dbFramework']->query("SELECT * FROM lead_reminder WHERE module_id='sales' and lead_id='$leadid'");
		$count_row = $query->num_rows();
		$data1= array(
		    'rep_id'=>$user_id,
		);
		if($count_row >0){
		  $update = $GLOBALS['$dbFramework']->update('lead_reminder' ,$data1, array('lead_id' => $leadid,'module_id' => 'sales'));
		}
		return true;
	}


	public function update_lead_info($leadid,$data) {
		$update = $GLOBALS['$dbFramework']->update('lead_info' ,$data, array('lead_id' => ($leadid)));
		return $update;
	}


	public function update_lead_info_condition($data, $conditionArray) {
		$update = $GLOBALS['$dbFramework']->update('lead_info' ,$data, $conditionArray);
		return $update;
	}

	public function lead_rep_status($leadid) {
		$query=$GLOBALS['$dbFramework']->query("SELECT lead_rep_status from lead_info where lead_id='$leadid'");
		return $query->result();
	}

	public function notifications($data){
		$insert = $GLOBALS['$dbFramework']->insert('notifications',$data); 
		return $insert;
	}

	public function lead_last_reject($leadid,$remarks,$data2,$userid){
            $query=$GLOBALS['$dbFramework']->query("select * from lead_cust_user_map where state=1 and lead_cust_id='$leadid' and action in ('assigned','reassigned') and module='sales'");
            $count_reject = $query->num_rows();
            $result=$query->result();
            $data1= array(
                'mapping_id' =>uniqid(rand(),TRUE) ,
                'lead_cust_id' =>$leadid,
                'type'=>'lead',
                'state' =>1,
                'action'=>"rejected",
                'module'=>"sales",
                'from_user_id'=>$userid,
                'to_user_id'=>$result[0]->from_user_id,
                'remarks'=>$remarks,
                'timestamp'=>date('Y-m-d H:i:s'),
               );
           $insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data1);
           $insert2 = $GLOBALS['$dbFramework']->insert('notifications',$data2); 
            if($insert==true && $insert2==true ){
                $query2=$GLOBALS['$dbFramework']->query("select * from lead_cust_user_map where lead_cust_id='$leadid' and action='rejected' and state=1 and module='sales'");
                $count_reject1 = $query2->num_rows();
                if($count_reject1==$count_reject){
                    $query3=$GLOBALS['$dbFramework']->query("update lead_info set lead_rep_status=3 where lead_id='$leadid'");
                    $query4=$GLOBALS['$dbFramework']->query("update lead_cust_user_map set state=0 where lead_cust_id='$leadid' and state=1 and module='sales'"); 
                }
            return true; 
            }
		
	}

	public function duplicate_lead($leadname){
		$query=$GLOBALS['$dbFramework']->query("
												SELECT * from lead_info where UCASE(lead_name)='".($leadname)."'
											 ");
		$count_row = $query->num_rows();
		if ($count_row > 0){
			return 0;
		} else {
			return 1;
		}
	}

	public function get_lead_product_map($user_id) {
		$query=$GLOBALS['$dbFramework']->query("
			SELECT lpm.id as id, lpm.lead_id as lead_id, 
			lpm.product_id as product_id, coalesce(lpm.remarks,'') as remarks, 
			lpm.timestamp as timestamp
			FROM lead_product_map lpm, lead_info li 
			WHERE li.lead_rep_owner='$user_id' and li.lead_id=lpm.lead_id
			GROUP BY lpm.id");
		$result=$query->result();		
		return $result;
	}

	public function insert_lead_product($data) {
		$insert = $GLOBALS['$dbFramework']->insert('lead_product_map',$data); 
		return $insert;
	}

	public function delete_lead_product($leadid) {
		$GLOBALS['$dbFramework']->delete('lead_product_map' , array('lead_id' => $leadid));
		return true;
	}
	
	public function get_lead_cust_user_map($lead_id, $action='in progress') {
		$query=$GLOBALS['$dbFramework']->query("
			SELECT *
			FROM lead_cust_user_map lcum
			WHERE lcum.lead_cust_id='$lead_id' and lcum.action='$action'");
		$result=$query->result();
		if (count($result) > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function add_lead_cust_user_map($data){
		$insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data);
		return $insert;
	}

	public function cancel_pending_activities($leadid, $rep_id){
		$query=$GLOBALS['$dbFramework']->query("
			UPDATE lead_reminder 
			set status='cancel' 
			where status in('pending','scheduled') and remi_date >=CURDATE() and lead_id='$leadid'"
		);
		return $query;
	}
	
	/*-------------------------------- CUSTOMERS -------------------------------*/
	public function get_newCustomers($userID) {
		$query = $GLOBALS['$dbFramework']->query("
		SELECT ci.id as id,
		ci.customer_id as customer_id, ci.customer_name as customer_name,
		ci.customer_logo as customer_logo, 
		JSON_UNQUOTE(ci.customer_number->'$.phone[0]') as customer_number,
		JSON_UNQUOTE(ci.customer_number->'$.phone[1]') as customer_number2,
		JSON_UNQUOTE(ci.customer_email->'$.email[0]') as customer_email,
		JSON_UNQUOTE(ci.customer_email->'$.email[1]') as customer_email2,
		ci.customer_website as customer_website,
		ci.customer_location_coord as customer_location_coord, ci.customer_address as customer_address,
		ci.customer_city as customer_city, ci.customer_state as customer_state,
		ci.customer_country as customer_country, ci.customer_zip as customer_zip,
		ci.customer_remarks as customer_remarks, ci.customer_source as customer_source,
		ci.customer_edit_status as customer_edit_status, ci.customer_created_by as customer_created_by,
		ci.customer_manager_owner as customer_manager_owner, ci.customer_rep_owner as customer_rep_owner,
		ci.customer_manager_status as customer_manager_status, ci.customer_rep_status as customer_rep_status,
		ci.customer_closed_reason as customer_closed_reason, ci.customer_approach_date as customer_approach_date,
		ci.customer_created_time as customer_created_time, ci.lead_id as lead_id,
		ci.customer_industry as customer_industry, ci.customer_business_loc as customer_business_loc,
		ci.customer_updated_by as customer_updated_by, ci.customer_updated_time as customer_updated_time,
		ci.contact_number as contact_number, ci.customer_status as customer_status,
		ci.attribute as attribute
		FROM customer_info as ci, lead_cust_user_map as lcum
		WHERE (lcum.lead_cust_id in 
				(SELECT lead_cust_id 
				FROM lead_cust_user_map 
				WHERE to_user_id='$userID' and (action='assigned' or action='reassigned') and type='customer'))
			AND (lcum.lead_cust_id not in 
				(SELECT lead_cust_id 
				FROM lead_cust_user_map 
				WHERE from_user_id='$userID' AND (action='accepted' or action='rejected') AND type='customer' AND state=1))
			AND ci.customer_rep_status = 1
			AND ci.customer_id=lcum.lead_cust_id
		GROUP BY ci.customer_id
		ORDER BY ci.customer_name"); 
		return $query->result();
	}

	public function get_acceptedCustomers($userID) {
		$query = $GLOBALS['$dbFramework']->query("
			SELECT ci.id as id,
			ci.customer_id as customer_id, ci.customer_name as customer_name,
			ci.customer_logo as customer_logo, 
			JSON_UNQUOTE(ci.customer_number->'$.phone[0]') as customer_number,
			JSON_UNQUOTE(ci.customer_number->'$.phone[1]') as customer_number2,
			JSON_UNQUOTE(ci.customer_email->'$.email[0]') as customer_email,
			JSON_UNQUOTE(ci.customer_email->'$.email[1]') as customer_email2,
			ci.customer_website as customer_website,
			ci.customer_location_coord as customer_location_coord, ci.customer_address as customer_address,
			ci.customer_city as customer_city, ci.customer_state as customer_state,
			ci.customer_country as customer_country, ci.customer_zip as customer_zip,
			ci.customer_remarks as customer_remarks, ci.customer_source as customer_source,
			ci.customer_edit_status as customer_edit_status, ci.customer_created_by as customer_created_by,
			ci.customer_manager_owner as customer_manager_owner, ci.customer_rep_owner as customer_rep_owner,
			ci.customer_manager_status as customer_manager_status, ci.customer_rep_status as customer_rep_status,
			ci.customer_closed_reason as customer_closed_reason, ci.customer_approach_date as customer_approach_date,
			ci.customer_created_time as customer_created_time, ci.lead_id as lead_id,
			ci.customer_industry as customer_industry, ci.customer_business_loc as customer_business_loc,
			ci.customer_updated_by as customer_updated_by, ci.customer_updated_time as customer_updated_time,
			ci.contact_number as contact_number, ci.customer_status as customer_status,
			ci.attribute as attribute
			FROM customer_info as ci
			WHERE ci.customer_rep_owner='$userID' AND ci.customer_rep_status=2
			GROUP BY ci.customer_id
			ORDER BY ci.customer_name");
		return $query->result();		
	}


	public function get_myCustomers($userID) {
		$query = $GLOBALS['$dbFramework']->query("
			SELECT ci.id as id,
			ci.customer_id as customer_id, ci.customer_name as customer_name,
			ci.customer_logo as customer_logo, 
			JSON_UNQUOTE(ci.customer_number->'$.phone[0]') as customer_number,
			JSON_UNQUOTE(ci.customer_number->'$.phone[1]') as customer_number2,
			JSON_UNQUOTE(ci.customer_email->'$.email[0]') as customer_email,
			JSON_UNQUOTE(ci.customer_email->'$.email[1]') as customer_email2,
			ci.customer_website as customer_website,
			ci.customer_location_coord as customer_location_coord, ci.customer_address as customer_address,
			ci.customer_city as customer_city, ci.customer_state as customer_state,
			ci.customer_country as customer_country, ci.customer_zip as customer_zip,
			ci.customer_remarks as customer_remarks, ci.customer_source as customer_source,
			ci.customer_edit_status as customer_edit_status, ci.customer_created_by as customer_created_by,
			ci.customer_manager_owner as customer_manager_owner, ci.customer_rep_owner as customer_rep_owner,
			ci.customer_manager_status as customer_manager_status, ci.customer_rep_status as customer_rep_status,
			ci.customer_closed_reason as customer_closed_reason, ci.customer_approach_date as customer_approach_date,
			ci.customer_created_time as customer_created_time, ci.lead_id as lead_id,
			ci.customer_industry as customer_industry, ci.customer_business_loc as customer_business_loc,
			ci.customer_updated_by as customer_updated_by, ci.customer_updated_time as customer_updated_time,
			ci.contact_number as contact_number, ci.customer_status as customer_status,
			ci.attribute as attribute
			FROM customer_info as ci, lead_info as li
			WHERE (ci.lead_id=li.lead_id) 
			AND (li.lead_rep_owner='$userID' or ci.customer_rep_owner ='$userID')
			AND (ci.customer_rep_status=2 or ci.customer_rep_status is null)
			GROUP BY ci.customer_id
			ORDER BY ci.customer_name");
		return $query->result();		
	}

	public function get_product_purchase_info($userID) {
		$query=$GLOBALS['$dbFramework']->query("
			SELECT ppi.*
			FROM product_purchase_info ppi, customer_info ci 
			WHERE ci.customer_rep_owner='$userID' and ci.customer_id=ppi.customer_id
			GROUP BY ppi.purchase_id");
		$result=$query->result();		
		return $result;
	}


	public function update_customer_reminder($user_id,$customer_id) {
		$query=$GLOBALS['$dbFramework']->query("SELECT * FROM lead_reminder WHERE module_id='sales' and lead_id='$customer_id'");
		$count_row = $query->num_rows();
		$data1= array(
		    'rep_id'=>$user_id,
		);
		if($count_row >0){
		  $update = $GLOBALS['$dbFramework']->update('lead_reminder' ,$data1, array('lead_id' => $customer_id,'module_id' => 'sales'));
		}
		return true;
	}


	public function update_customer_info($customer_id,$data) {
		$update = $GLOBALS['$dbFramework']->update('customer_info' ,$data, array('customer_id' => ($customer_id)));
		return $update;
	}

	public function customer_rep_status($customer_id) {
		$query=$GLOBALS['$dbFramework']->query("SELECT customer_rep_status from customer_info where customer_id='$customer_id'");
		return $query->result();
	}

	public function customer_last_reject($customer_id,$remarks,$data2,$userid){
        $query=$GLOBALS['$dbFramework']->query("
        	SELECT * 
        	FROM lead_cust_user_map 
        	WHERE state=1 and lead_cust_id='$customer_id' and action in ('assigned','reassigned') and module='sales'");
        $count_reject = $query->num_rows();
        $result=$query->result();
        $data1= array(
            'mapping_id' =>uniqid(rand(),TRUE) ,
            'lead_cust_id' =>$customer_id,
            'type'=>'customer',
            'state' =>1,
            'action'=>"rejected",
            'module'=>"sales",
            'from_user_id'=>$userid,
            'to_user_id'=>$result[0]->from_user_id,
            'remarks'=>$remarks,
            'timestamp'=>date('Y-m-d H:i:s'),
           );
       $insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data1);
       $insert2 = $GLOBALS['$dbFramework']->insert('notifications',$data2); 
        if($insert==true && $insert2==true){
            $query2=$GLOBALS['$dbFramework']->query("
            	SELECT * 
            	FROM lead_cust_user_map 
            	WHERE lead_cust_id='$customer_id' and action='rejected' and state=1 and module='sales'");
            $count_reject1 = $query2->num_rows();
            if($count_reject1==$count_reject){
                $query3=$GLOBALS['$dbFramework']->query("UPDATE customer_info SET customer_rep_status=3 WHERE customer_id='$customer_id'");
                $query4=$GLOBALS['$dbFramework']->query("UPDATE lead_cust_user_map SET state=0 WHERE lead_cust_id='$customer_id' and state=1 and module='sales'"); 
            }
        return true; 
        }		
	}

	/*------------------------------ OPPORTUNITIES -----------------------------*/
	//change this	
	public function opportunity($user) {
		$query = $GLOBALS['$dbFramework']->query("
			SELECT a.id as id, a.opportunity_id as opportunity_id, a.opportunity_name as opportunity_name,
				a.opportunity_contact as opportunity_contact, a.cycle_id as cycle_id, a.sell_type as sell_type,
				b.stage_name as opportunity_stage, a.opportunity_product as opportunity_product,
				a.opportunity_industry as opportunity_industry, a.opportunity_location as opportunity_location,
				a.opportunity_currency as opportunity_currency, a.lead_cust_id as lead_cust_id,
				coalesce(a.opportunity_value,'') as opportunity_value,
				coalesce(a.opportunity_numbers,'') as opportunity_numbers,
				coalesce(a.opportunity_date,'') as opportunity_date,
				coalesce(a.opportunity_rate,'') as opportunity_rate,
				coalesce(a.opportunity_score,'') as opportunity_score,
				coalesce(a.opportunity_customer_code,'') as opportunity_customer_code,
				coalesce(a.opportunity_priority,'') as opportunity_priority,
				coalesce(a.closed_status,'') as closed_status,
				a.closed_reason as closed_reason, a.opportunity_approach_date as approach_date,
				a.created_by as created_by, a.created_timestamp as created_timestamp,
				a.owner_id as owner_id, a.owner_status as owner_status,
				a.manager_owner_id as manager_owner_id, a.owner_manager_status as owner_manager_status,
				a.stage_owner_id as stage_owner_id, a.stage_owner_status as stage_owner_status,
				a.stage_manager_owner_id as stage_manager_owner_id, a.stage_manager_owner_status as stage_manager_owner_status
			FROM oppo_user_map h, opportunity_details a, sales_stage b
			WHERE a.opportunity_id = h.opportunity_id AND b.stage_id = a.opportunity_stage
				AND ((a.owner_id = '$user' AND a.owner_status = 2)
				OR (a.stage_owner_id = '$user' AND a.stage_owner_status = 2)
				OR (h.action IN ('stage progressed' , 'rejected') AND h.to_user_id = '$user'))
			GROUP BY a.opportunity_id"); 
		return $query->result();
	}

	public function get_newopportunity($user){
		$query = $GLOBALS['$dbFramework']->query("
			SELECT a.id as id, a.opportunity_id as opportunity_id, a.opportunity_name as opportunity_name,
				a.opportunity_contact as opportunity_contact, a.cycle_id as cycle_id, a.sell_type as sell_type,
				b.stage_name as opportunity_stage, a.opportunity_product as opportunity_product,
				a.opportunity_industry as opportunity_industry, a.opportunity_location as opportunity_location,
				a.opportunity_currency as opportunity_currency, a.lead_cust_id as lead_cust_id,
				coalesce(a.opportunity_value,'') as opportunity_value,
				coalesce(a.opportunity_numbers,'') as opportunity_numbers,
				coalesce(a.opportunity_date,'') as opportunity_date,
				coalesce(a.opportunity_rate,'') as opportunity_rate,
				coalesce(a.opportunity_score,'') as opportunity_score,
				coalesce(a.opportunity_customer_code,'') as opportunity_customer_code,
				coalesce(a.opportunity_priority,'') as opportunity_priority,
				coalesce(a.closed_status,'') as closed_status,
				a.closed_reason as closed_reason, a.opportunity_approach_date as approach_date,
				a.created_by as created_by, a.created_timestamp as created_timestamp,
				a.owner_id as owner_id, a.owner_status as owner_status,
				a.manager_owner_id as manager_owner_id, a.owner_manager_status as owner_manager_status,
				a.stage_owner_id as stage_owner_id, a.stage_owner_status as stage_owner_status,
				a.stage_manager_owner_id as stage_manager_owner_id, a.stage_manager_owner_status as stage_manager_owner_status
			FROM oppo_user_map h, opportunity_details a ,sales_stage b
			WHERE a.opportunity_id = h.opportunity_id AND b.stage_id = a.opportunity_stage
				AND h.to_user_id = '$user'
				AND h.state = 1
				AND h.module = 'sales'
				AND h.action IN ('stage assigned' , 'ownership assigned',
				'ownership reassigned',
				'stage reassigned')
				AND (a.owner_status = 1 OR a.stage_owner_status = 1)
				AND a.closed_status != 100
			GROUP BY a.opportunity_id");
		return $query;
	}
	
	public function get_inprogressopportunity($user){
		$query = $GLOBALS['$dbFramework']->query("
			SELECT a.id as id, a.opportunity_id as opportunity_id, a.opportunity_name as opportunity_name,
				a.opportunity_contact as opportunity_contact, a.cycle_id as cycle_id, a.sell_type as sell_type,
				b.stage_name as opportunity_stage, a.opportunity_product as opportunity_product,
				a.opportunity_industry as opportunity_industry, a.opportunity_location as opportunity_location,
				a.opportunity_currency as opportunity_currency, a.lead_cust_id as lead_cust_id,
				coalesce(a.opportunity_value,'') as opportunity_value,
				coalesce(a.opportunity_numbers,'') as opportunity_numbers,
				coalesce(a.opportunity_date,'') as opportunity_date,
				coalesce(a.opportunity_rate,'') as opportunity_rate,
				coalesce(a.opportunity_score,'') as opportunity_score,
				coalesce(a.opportunity_customer_code,'') as opportunity_customer_code,
				coalesce(a.opportunity_priority,'') as opportunity_priority,
				coalesce(a.closed_status,'') as closed_status,
				a.closed_reason as closed_reason, a.opportunity_approach_date as approach_date,
				a.created_by as created_by, a.created_timestamp as created_timestamp,
				a.owner_id as owner_id, a.owner_status as owner_status,
				a.manager_owner_id as manager_owner_id, a.owner_manager_status as owner_manager_status,
				a.stage_owner_id as stage_owner_id, a.stage_owner_status as stage_owner_status,
				a.stage_manager_owner_id as stage_manager_owner_id, a.stage_manager_owner_status as stage_manager_owner_status
			FROM oppo_user_map h, opportunity_details a, sales_stage b
			WHERE a.opportunity_id = h.opportunity_id AND b.stage_id = a.opportunity_stage
				AND (a.closed_reason IS NULL)
				AND ((a.owner_id = '$user'
				AND a.owner_status = 2)
				OR (a.stage_owner_id = '$user'
				AND a.stage_owner_status = 2)
				OR (h.action IN ('stage progressed' , 'rejected')
				AND h.to_user_id = '$user'))
			GROUP BY a.opportunity_id;");
		return $query;
	}
	
	public function get_closedopportunity($user){
		$query=$GLOBALS['$dbFramework']->query("
				SELECT a.id as id, a.opportunity_id as opportunity_id, a.opportunity_name as opportunity_name,
				a.opportunity_contact as opportunity_contact, a.cycle_id as cycle_id, a.sell_type as sell_type,
				b.stage_name as opportunity_stage, a.opportunity_product as opportunity_product,
				a.opportunity_industry as opportunity_industry, a.opportunity_location as opportunity_location,
				a.opportunity_currency as opportunity_currency, a.lead_cust_id as lead_cust_id,
				coalesce(a.opportunity_value,'') as opportunity_value,
				coalesce(a.opportunity_numbers,'') as opportunity_numbers,
				coalesce(a.opportunity_date,'') as opportunity_date,
				coalesce(a.opportunity_rate,'') as opportunity_rate,
				coalesce(a.opportunity_score,'') as opportunity_score,
				coalesce(a.opportunity_customer_code,'') as opportunity_customer_code,
				coalesce(a.opportunity_priority,'') as opportunity_priority,
				coalesce(a.closed_status,'') as closed_status,
				a.closed_reason as closed_reason, a.opportunity_approach_date as approach_date,
				a.created_by as created_by, a.created_timestamp as created_timestamp,
				a.owner_id as owner_id, a.owner_status as owner_status,
				a.manager_owner_id as manager_owner_id, a.owner_manager_status as owner_manager_status,
				a.stage_owner_id as stage_owner_id, a.stage_owner_status as stage_owner_status,
				a.stage_manager_owner_id as stage_manager_owner_id, a.stage_manager_owner_status as stage_manager_owner_status
				FROM opportunity_details a, sales_stage b
				WHERE a.closed_status = 100 AND (a.owner_id = '$user' OR a.stage_owner_id = '$user') AND b.stage_id = a.opportunity_stage
			GROUP BY a.opportunity_id");
		return $query;
	}

	/*-------------------------------- CONTACTS --------------------------------*/

	public function get_contact($userid){
		$query=$GLOBALS['$dbFramework']->query("
			SELECT cd.id, cd.contact_name, cd.contact_id, cd.lead_cust_id, coalesce(cd.contact_photo,'') as contact_photo, cd.contact_type, 
			cd.contact_desg, cd.contact_dob, cd.contact_address, cd.remarks,
			JSON_UNQUOTE(cd.contact_email->'$.email[0]') as contact_email1,
			JSON_UNQUOTE(cd.contact_email->'$.email[1]') as contact_email2,
			JSON_UNQUOTE(cd.contact_number->'$.phone[0]') as contact_number1,
			JSON_UNQUOTE(cd.contact_number->'$.phone[1]') as contact_number2,
			cd.contact_created_time, cd.contact_created_by, cd.contact_for
			FROM contact_details cd 
			left join lead_info li on cd.lead_cust_id=li.lead_id
			left join customer_info ci on (cd.lead_cust_id=ci.lead_id or cd.lead_cust_id = ci.customer_id)
			left join opportunity_details od on cd.lead_cust_id=od.lead_cust_id
			WHERE li.lead_rep_owner='$userid' or ci.customer_rep_owner='$userid' or
			od.owner_id='$userid' or od.stage_owner_id='$userid'
			group by cd.contact_id");
		return $query->result();
	}

	public function insert_contact($data){
		$var = $GLOBALS['$dbFramework']->insert('contact_details', $data);
		return $var;
	}
	
	public function edit_contact($data,$contact_id)	{
		$var = $GLOBALS['$dbFramework']->update('contact_details', $data, array('contact_id' => $contact_id));
		return $var;
	}

	public function get_contact_for($lead_id)	{
		$query = $GLOBALS['$dbFramework']->query("
			SELECT * from contact_details where lead_cust_id = '$lead_id'");
		return $query->result();
	}
	/*------------------------------- REMINDERS --------------------------------*/
	public function phone_fetch_reminder($rep_id){
		$query = $GLOBALS['$dbFramework']->query("SELECT lead_reminder_id, lead_id, rep_id, leadempid,
			remi_date, rem_time, conntype, status, meeting_start, meeting_end, addremtime, managerid, timestamp,remarks, event_name, opportunity_id, module_id, duration, type, created_by, cancel_remarks
			from lead_reminder 
		WHERE rep_id = '$rep_id'");
		return $query->result_array();
	}

    public function insert_reminder($data) {
    	$insert = $GLOBALS['$dbFramework']->insert('lead_reminder',$data); 
    	return $insert;
    }

	/*----------------------------- ACTIVITY LOG -------------------------------*/	
		
	/*----------------------- WASTE FUNCTIONS ----------------------*/	

	public function call_proc($user_id) {
		$query = $GLOBALS['$dbFramework']->query("call getChildrenForParent('$user_id')"); 
		return $query->result();
	}


	public function get_user_plugin($plugins){
		$query = $GLOBALS['$dbFramework']->query("SELECT plugin_id,plugin_name FROM plugin_master WHERE plugin_id IN ($plugins);");
		return $query;
	}

	public function insert_taskcomplete($data1){
		$query=$GLOBALS['$dbFramework']->insert('rep_log',$data1);
		return $query;
	}

	
	public function insert_repinfo($user_id,$event_lead) {
		//Query Should be change based on the tables.
		$query = $GLOBALS['$dbFramework']->query("
			SELECT from_user_id 
			FROM lead_cust_user_map 
			WHERE lead_cust_id='$event_lead' AND action='in progress'");
		if(!$query)   {
			$data1=array(
				'lead_cust_id'=>$event_lead,
				'from_user_id'=>$user_id,
				'to_user_id'=>$user_id,
				'action' => 'in progress',
				'timestamp' => date('Y-m-d H:i:s'),
				'state'=>5,
				'type'=>'lead'
			);

			$GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data1);
			$getCustomer=$GLOBALS['$dbFramework']->query("SELECT customer_id 
											FROM customer_info
											WHERE customer_id='$event_lead'");
			if($getCustomer->num_rows() > 0){
				$data3=array(
				'customer_rep_status'=>5,
				'customer_manager_status'=>5
				);
				$update1 = $GLOBALS['$dbFramework']->update('customer_info', $data3,array('customer_id'=> $event_lead)); 
				return $update1;
			} else {
			// $this->db->where('lead_id', $event_lead);
				$data2=array(
				'lead_rep_status'=>5,
				'lead_manager_status'=>5
				);
				$update = $GLOBALS['$dbFramework']->update('lead_info', $data2,array('lead_id'=> $event_lead));   
				return $update;
			}
		}
	}

public function get_fulluser_data($userid){
   try{
            $query=$GLOBALS['$dbFramework']->query("SELECT staff_tbl.*,manager_tbl.user_name as Manager,dpt.Department_name , teams.teamname,desg.role_name,salespersona.lookup_value
                                            from user_details as staff_tbl
                                            LEFT  JOIN user_details as manager_tbl2
                                            on manager_tbl2.reporting_to=manager_tbl2.user_id
                                            LEFT join user_details as manager_tbl
                                            on staff_tbl.reporting_to=manager_tbl.user_id
                                            LEFT join department as dpt
                                            on staff_tbl.department=dpt.Department_id
                                            LEFT join teams as teams
                                            on staff_tbl.team_id=teams.teamid
                                            LEFT join user_roles as desg
                                            on staff_tbl.designation=desg.role_id
                                            LEFT join lookup as salespersona
                                            on staff_tbl.user_product=salespersona.lookup_id
                                            where staff_tbl.user_name !='Admin' and staff_tbl.user_id='$userid' order by staff_tbl.id ");
            $a2= $query->result();

        /* ---------------------------- modules ---------------------------------------------- */
            $get_modules=$GLOBALS['$dbFramework']->query("select module_id from user_module_plugin_mapping where user_id='$userid'");
            $a1=$get_modules->result();
        /* ---------------------------- plugins ---------------------------------------------- */
            $get_plugin=$GLOBALS['$dbFramework']->query("select plugin_id from user_module_plugin_mapping where user_id='$userid'");
            $b=$get_plugin->result();

        /* ---------------------------- officelocation ---------------------------------------------- */

            $query=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('office_location','user_mappings','".$userid."','bussinessLoc1,bussinessLoc');");
            $c=$query->result();

        /* ---------------------------- business loction ---------------------------------------------- */
            $query=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('business_location','user_mappings','".$userid."','bussinessLoc1,bussinessLoc');");
            $d=$query->result();

        /* ---------------------------- industries ---------------------------------------------- */
            $query=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('clientele_industry','user_mappings','".$userid."','clientInds1,clientInds');");

            $e=$query->result();

        /* ---------------------------- Product Currency ---------------------------------------------- */
            $a=array();
            $get_products=$GLOBALS['$dbFramework']->query("SELECT  distinct a.map_type,a.map_id,
                                                        (select distinct hvalue2 from hierarchy where hkey2=a.map_id) as productname,
                                                        (select distinct hvalue1 from hierarchy where hkey2=a.map_id) as productname1
                                                        FROM user_mappings a where user_id='".$userid."' and map_type='product';");
            $arr=$get_products->result_array();
            $row=0;
                        if($get_products->num_rows()>0){
                          for($i=0;$i<count($arr);$i++){
                              $product_id=$arr[$i]['map_id'];
                             $a[$row]['product_id']=$arr[$i]['map_id'];
                             $a[$row]['product_name']=$arr[$i]['productname']."(".$arr[$i]['productname1'].")";

                              $query1=$GLOBALS['$dbFramework']->query("select a.map_value,(select currency_name from currency where a.map_value=currency_id)as currencyname from user_mappings a where map_id='$product_id'  and user_id='$userid' order by id ");
                              $arr1=$query1->result_array();
                                  for($j=0;$j<count($arr1);$j++){
                                              $a[$row]['curdata'][$j]['currency_id']=$arr1[$j]['map_value'];
                                              $a[$row]['curdata'][$j]['currency_name']=$arr1[$j]['currencyname'];

                                  }
                               $row++;
                          }
                        }
            $f=$a;
            /* ---------------------------- working Details ---------------------------------------------- */
            $g="";
            $query=$GLOBALS['$dbFramework']->query("SELECT expression FROM user_attributes where user_id='$userid' ");
            $query->result();
            if($query->num_rows()>0){
                foreach ($query->result() as $row)
                {
                     $workingArr = $row->expression;
                }
                $workingArr = json_decode($workingArr);
                if(count($workingArr)!=0){
                    for($i=0;$i<count($workingArr);$i++){
                         $work[] = $this->parse_cron($workingArr[$i]);
                    }

                    $g=$work;
                }else{
                   $g=$workingArr;
                }

            }

            /* ---------------------------- Productivity Details ---------------------------------------------- */
            $query=$GLOBALS['$dbFramework']->query("SELECT a.*,c.calendername,
                                                              (select currency_name from currency where a.resource_currency=currency_id)as resource_curcyName,
                                                              (select currency_name from currency where a.outgoingcall_currency=currency_id)as outgoingcall_curcyName,
                                                              (select currency_name from currency where a.outgoingsms_currency=currency_id)as outgoingsms_curcyName
                                                              FROM representative_details a,calender c
                                                              where a.user_id='$userid'
                                                              and a.holiday_calender=c.calenderid");
            $h=$query->result();
            /* ---------------------------- sell type ---------------------------------------------- */
            $query=$GLOBALS['$dbFramework']->query("select a.lookup_id ,a.lookup_value as map_id from lookup a, user_mappings b
                                                    where a.lookup_id=b.map_id and b.user_id='$userid' and b.map_type='sell_type';");

            $i=$query->result();
            /* ---------------------------- Device Information ---------------------------------------------- */  
            $query=$GLOBALS['$dbFramework']->query("SELECT * from user_app_details 
            	WHERE user_id='$userid'");

            $j=$query->result();    

            /* ---------------------------- Target Information ---------------------------------------------- */    
            $query=$GLOBALS['$dbFramework']->query("SELECT rpt.target_data,rpt.target_id,um.map_id as product_id,
                hi.hvalue2 as product_name,rpt.target_data->'$.target_currency[0]' as currency_id,cu.currency_name
                from rep_target_details as rpt,user_mappings as um,hierarchy as hi,currency as cu
                where rpt.target_data->'$.manager_id'='$userid'
                and rpt.target_id=um.map_value
                and um.map_id=hi.hkey2
                and target_data->'$.target_currency[0]'=cu.currency_id
                order by rpt.target_id");

            $k=$query->result();  			                                        

            $result=array(
               'user'=>$a2,
               'modules'=>$a1,
               'plugin'=>$b,
               'officeloc'=>$c,
               'businessloc'=>$d,
               'industry'=>$e,
               'procur'=>$f,
               'workdetails'=>$g,
               'prodetails'=>$h,
               'selltype'=>$i,
               'device_info'=>$j,
               'target_details'=>$k
            );
            return $result;
     }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}

	public function insert_created($data) {
		$insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data); 
		return $insert;
	}

	public function insert_seen($data) {
		$insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data); 
		return $insert;
	}
	
	public function insert_accepted($data) {
		$insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data); 
		return $insert;
	}

	public function accept_datails($data) {
		$insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data); 
		return $insert;
	}

	public function reject_datails($data) {
		$insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data); 
		return $insert;
	}

	public function insert_log($data) {
		$insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data); 
		return $insert;
	}

	public function insert_transaction($data){
		$insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data); 
		return $insert;
	}
	/*public function validateAccess($loginID,$password) {
		$res = array();
		$query=$GLOBALS['$dbFramework']->query("
			SELECT a.user_id,a.user_name as name,a.phone_num,a.emailId,a.login_pwd,a.photo,a.employee_id,a.reporting_to,a.department,a.team_id,
			IF(b.module_id->'$.cxo'!='0',JSON_UNQUOTE(b.module_id->'$.cxo'),'-') as cxo,
			IF(b.module_id->'$.sales'!='0',JSON_UNQUOTE(b.module_id->'$.sales'),'-') as sales,
			IF(b.module_id->'$.Manager'!='0',JSON_UNQUOTE(b.module_id->'$.Manager'),'-') as manager,
			b.module_id as modules,b.plugin_id as plugins, c.location_tracking, c.call_recording, c.holiday_calender as holidaycalendar, c.user_id as rep_id
			FROM user_details a,user_module_plugin_mapping b, representative_details c
			WHERE a.user_id = b.user_id AND a.user_id = c.user_id
			AND a.login_id = BINARY '$loginID' GROUP BY a.user_id");
		if($query->num_rows()>0){
			$result = $query->result();
			$stored_password = $result[0]->login_pwd;
			if($stored_password==$password){
				$qry1 = $GLOBALS['$dbFramework']->query("SELECT user_id,user_name FROM user_details WHERE user_state=1 AND login_id='$loginID'");
				if($qry1->num_rows()>0){
					$qry2 = $GLOBALS['$dbFramework']->query("SELECT user_id,user_name FROM user_details WHERE login_state=0 AND login_id='$loginID'");
					if($qry2->num_rows()>0){
						$res['success'] = 1;
						$res['result'] = $result;
						return $res;
					}else{
						$res['success'] = 3;
						return $res;
					}
				}else{
					$res['success'] = 2;
					return $res;
				}
			}else{
				$res['success'] = 0;
				return $res;
			}
		}else{
			$res['success'] = 0;
			return $res;
		}
	}*/ 

	public function fetchMobileNotification($userid) {

		$query=$GLOBALS['$dbFramework']->query("SELECT id,notificationShortText,notificationText,
												(select user_name from user_details where user_id=from_user) as username,
                                       			DATE_FORMAT(notificationTimestamp,\"%d-%m-%Y %H:%i:%s\") AS notifydate  
                                       			FROM notifications 
                                       			WHERE
                                       			to_user='$userid' 
  
                                       			ORDER BY id desc");
		return $query->result();
	}

    public function insertNotificationData($notificationDataArray) {
        try{
                $insert = $GLOBALS['$dbFramework']->insert('notifications',$notificationDataArray);
                return $insert;
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }	

	public function parse_cron($crontab) {
            $cron = explode(" ",$crontab);
            $seconds = str_pad($cron[0], 2, '0', STR_PAD_LEFT);
            $minutes = explode(",",$cron[1]);
            $hours = explode(",",$cron[2]);

            $sminutes = str_pad($minutes[0], 2, '0', STR_PAD_LEFT);
            $eminutes = str_pad($minutes[1], 2, '0', STR_PAD_LEFT);

            $shours = str_pad($hours[0], 2, '0', STR_PAD_LEFT);
            $ehours = str_pad($hours[1], 2, '0', STR_PAD_LEFT);

            $work = array();
            $work['start_time'] = $shours.":".$sminutes;
            $work['end_time'] = $ehours.":".$eminutes;
            switch($cron[5]){
                    case '1': $work['day_of_week'] = "SUN";
                                    break;
                    case '2': $work['day_of_week'] = "MON";
                                    break;
                    case '3': $work['day_of_week'] = "TUE";
                                    break;
                    case '4': $work['day_of_week'] = "WED";
                                    break;
                    case '5': $work['day_of_week'] = "THU";
                                    break;
                    case '6': $work['day_of_week'] = "FRI";
                                    break;
                    case '7': $work['day_of_week'] = "SAT";
                                    break;
            }
            return $work;
}

	public function checkInstance($instanceId,$userId)
	{
		// checking Instance.
		$instanceQuery=$GLOBALS['$dbFramework']->query("
											SELECT 
											client_active_state 
											FROM
											client_info
											WHERE
											client_id ='$instanceId'
											");

		// AppData.
		$appDataQuery= $GLOBALS['$dbFramework']->query("
												SELECT 
	    											app_login_state AS mobile_login_state,
		    										login_state AS user_login_state,
		    										user_state AS user_state
												FROM
    												user_details
												WHERE
    											user_id = '$userId'
											");

		$checkIMIEQuery =$GLOBALS['$dbFramework']->query("SELECT * FROM user_app_details as upd WHERE upd.user_id = '$userId'");

		// Result of instance Query.
			$instanceData = $instanceQuery->result();
		// Result of appData Query.
			$appData      = $appDataQuery->result();	
		// Result of checkIMIE Query.
			$checkIMIE    = $checkIMIEQuery->result();

		// Checking Empty
		if (!empty($instanceData)) 
		{
			$client_active_state = $instanceData[0]->client_active_state;
		}
		else
		{
			$client_active_state = '';
		}

		if (!empty($appData)) 
		{
			$mobile_login_state = $appData[0]->mobile_login_state;
			$user_login_state 	= $appData[0]->user_login_state;
			$user_state         = $appData[0]->user_state;
		}
		else
		{
			$mobile_login_state = '';
			$user_login_state   = '';
			$user_state         = '';
		}
		if (!empty($checkIMIE)) 
		{
			$IMEI              = $checkIMIE[0]->IMEI;
		}
		else
		{
			$IMEI              = '';
		}

			$loginStateArray = array(
								'clientActiveState' =>$client_active_state,
								'appLoginState'     =>$mobile_login_state,
								'userLoginState'    =>$user_login_state,
								'userState'         =>$user_state,
								'IMEI'              =>$IMEI
								);

			return $loginStateArray;

	}

	public function fetch_completetask($user_id)
	{
		// Fetching Lead, Cust & Opp reminders.
		$completedRemiders =$GLOBALS['$dbFramework']->query("
			SELECT coalesce((CASE 
                 WHEN a.type ='support' THEN
                 (SELECT request_name 
                 FROM support_opportunity_details as li
                 WHERE a.lead_id=li.request_id)
                 WHEN a.type = 'opportunity' THEN 
                (SELECT opportunity_name
                FROM opportunity_details AS li
                WHERE a.lead_id = li.opportunity_id)
                WHEN a.type = 'lead' THEN 
                (SELECT lead_name  
                FROM lead_info AS li
                WHERE a.lead_id = li.lead_id)
                ELSE 
                (SELECT customer_name
                FROM customer_info AS ci
                WHERE a.lead_id = ci.customer_id)
                END)
                ,'-') AS leadname,
                (CASE WHEN a.type ='support' THEN (SELECT request_id FROM support_opportunity_details as li 
                WHERE a.lead_id=li.opp_cust_id)
                WHEN a.type = 'opportunity' THEN
                (SELECT opportunity_id
                FROM opportunity_details AS li
                WHERE a.lead_id = li.opportunity_id)
                WHEN a.type = 'lead' THEN 
                (SELECT lead_id
                FROM lead_info AS li
                WHERE a.lead_id = li.lead_id)
                ELSE 
                (SELECT customer_id
                FROM customer_info AS ci 
                WHERE a.lead_id = ci.customer_id)
                END) AS leadid,
                a.addremtime,a.duration,a.rem_time,a.lead_reminder_id,a.status,a.event_name,
                a.meeting_start,a.remi_date, a.meeting_start,a.remarks,a.leadempid,a.lead_id,
                a.remi_date,a.conntype,a.rep_id AS user_id,a.reminder_members,a.status as reminderstatus,a.timestamp as closed_date,coalesce(ud.user_name,'-') as created_by ,
                b.lookup_id,b.lookup_value,
                d.contact_id AS employeeid,d.contact_number AS employeephone1,d.contact_name AS employeename,
                e.user_name AS person_name,e.user_state,
                f.Department_id,f.Department_name,a.type as type,a.meeting_end as meeting_end,'' as phone
                FROM
                lead_reminder a
                LEFT JOIN contact_details as d 
                ON
                a.leadempid = d.contact_id
                LEFT JOIN user_details as ud
                ON 
                ud.user_id = a.created_by,
                lookup b,
                lead_info c,
                user_details e,
                department f
                WHERE
                (a.rep_id IN ('$user_id'))
                AND date(a.timestamp) = curdate()
                AND a.status IN ('' , 'reschedule','cancel')
                AND a.conntype = b.lookup_id
                AND a.rep_id = e.user_id
                AND e.department = f.Department_id
                GROUP BY a.lead_reminder_id
                ORDER BY a.timestamp DESC;
		");

		return $completedRemiders->result();

	}

	public function fetch_mytaskCompleted1($user_id)
	{
		// Fetching Lead, Cust & Opp reminders.
		$completedRemidersInternal =$GLOBALS['$dbFramework']->query("
			SELECT coalesce((CASE 
                 WHEN a.type ='support' THEN
                 (SELECT request_name 
                 FROM support_opportunity_details as li
                 WHERE a.lead_id=li.request_id)
                 WHEN a.type = 'opportunity' THEN 
                (SELECT opportunity_name
                FROM opportunity_details AS li
                WHERE a.lead_id = li.opportunity_id)
                WHEN a.type = 'lead' THEN 
                (SELECT lead_name  
                FROM lead_info AS li
                WHERE a.lead_id = li.lead_id)
                ELSE 
                (SELECT customer_name
                FROM customer_info AS ci
                WHERE a.lead_id = ci.customer_id)
                END)
                ,'-') AS leadname,
                (CASE WHEN a.type ='support' THEN (SELECT request_id FROM support_opportunity_details as li 
                WHERE a.lead_id=li.opp_cust_id)
                WHEN a.type = 'opportunity' THEN
                (SELECT opportunity_id
                FROM opportunity_details AS li
                WHERE a.lead_id = li.opportunity_id)
                WHEN a.type = 'lead' THEN 
                (SELECT lead_id
                FROM lead_info AS li
                WHERE a.lead_id = li.lead_id)
                ELSE 
                (SELECT customer_id
                FROM customer_info AS ci 
                WHERE a.lead_id = ci.customer_id)
                END) AS leadid,
                a.addremtime,a.duration,a.rem_time,a.lead_reminder_id,a.status,a.event_name,
                a.meeting_start,a.remi_date, a.meeting_start,a.remarks,a.leadempid,a.lead_id,
                a.remi_date,a.conntype,a.rep_id AS user_id,a.reminder_members,a.status as reminderstatus,a.timestamp as closed_date,coalesce(ud.user_name,'-') as created_by ,
                b.lookup_id,b.lookup_value,
                d.contact_id AS employeeid,d.contact_number AS employeephone1,d.contact_name AS employeename,
                e.user_name AS person_name,e.user_state,
                f.Department_id,f.Department_name,a.type as type,a.meeting_end as meeting_end,'' as phone
                FROM
                lead_reminder a
                LEFT JOIN contact_details as d 
                ON
                a.leadempid = d.contact_id
                LEFT JOIN user_details as ud
                ON 
                ud.user_id = a.created_by,
                lookup b,
                lead_info c,
                user_details e,
                department f
                WHERE
                (a.rep_id IN ('$user_id'))
                AND date(a.timestamp) = curdate()
                AND a.status IN ('' , 'reschedule','cancel')
                AND a.conntype = b.lookup_id
                AND a.rep_id = e.user_id
                AND e.department = f.Department_id
                GROUP BY a.lead_reminder_id
                ORDER BY a.timestamp DESC;
		");
		return $completedRemidersInternal->result();
	}

	public function fetch_mytaskCompletedReplog($user_id)
	{
		$query= $GLOBALS['$dbFramework']->query("
										SELECT coalesce((CASE WHEN a.type = 'opportunity' THEN 
                (SELECT opportunity_name
                FROM opportunity_details AS li
                WHERE a.leadid = li.opportunity_id)
                WHEN a.type = 'lead' THEN 
                (SELECT lead_name  
                FROM lead_info AS li
                WHERE a.leadid = li.lead_id)
                ELSE 
                (SELECT customer_name
                FROM customer_info AS ci
                WHERE a.leadid = ci.customer_id)
                END)
                ,'-') AS leadname,
                (CASE WHEN a.type = 'opportunity' THEN
                (SELECT opportunity_id
                FROM opportunity_details AS li
                WHERE a.leadid = li.opportunity_id)
                WHEN a.type = 'lead' THEN 
                (SELECT lead_id
                FROM lead_info AS li
                WHERE a.leadid = li.lead_id)
                ELSE 
                (SELECT customer_id
                FROM customer_info AS ci 
                WHERE a.leadid = ci.customer_id)
                END) AS leadid,
                TIMEDIFF(a.endtime, a.starttime) as duration,'-' as rem_time,a.log_name as event_name,a.reminderid,a.call_type as reminderstatus,
                a.starttime as meeting_start ,a.time as remi_date, a.note as remarks,a.leademployeeid as leadempid,a.leadid,a.logtype as conntype,a.rep_id AS user_id,a.type as type,a.time as closed_date,coalesce(ud.user_name,'-') as created_by,a.endtime as meeting_end,
                b.lookup_id,b.lookup_value,
                d.contact_id AS employeeid,d.contact_number AS employeephone1,d.contact_name AS employeename,
                e.user_name AS person_name,e.user_state,
                f.Department_id,f.Department_name,a.phone as phone
                FROM
                rep_log a
                left join contact_details as d 
                on a.leademployeeid = d.contact_id
                left join user_details as ud
                on a.rep_id = ud.user_id ,
                lookup b,
                lead_info c,
                user_details e,
                department f
                WHERE
                (a.rep_id IN ('$user_id')) 
                AND a.call_type IN ('complete')
                AND a.logtype = b.lookup_id
                AND a.rep_id = e.user_id
                AND e.department = f.Department_id
                group by a.id
                ORDER BY a.time DESC;
			");
		return $query->result();
	}

	public function fetch_mytaskCompletedReplogInternal($user_id)
	{
		$query= $GLOBALS['$dbFramework']->query("
			SELECT a.type,a.log_name as event_name,a.reminderid,a.call_type as reminderstatus, a.starttime as meeting_start,a.endtime as meeting_end,
		a.time as remi_date,a.note as remarks,a.leademployeeid as leadempid,a.leadid,a.logtype as conntype,
		a.rep_id AS user_id,a.time as closed_date,coalesce(ud.user_name,'-') as created_by,
		b.lookup_id,b.lookup_value,
		d.user_id AS employeeid,d.phone_num AS employeephone1,d.user_name AS employeename,
		e.user_name AS person_name,e.user_state,c.user_name as leadname,
		f.Department_id,f.Department_name,c.user_name as leadid,TIMEDIFF(a.endtime, a.starttime) as duration,a.phone as phone
		from rep_log as a 
		left join user_details as ud
		on a.rep_id = ud.user_id,
		lookup b,
		user_details d,
		user_details e,
		department f,
		user_details c
		where a.leademployeeid = d.user_id
		AND a.call_type IN ('complete')
		AND a.logtype = b.lookup_id
		
        AND date(a.time) = curdate()
		AND a.type = 'internal'
		AND a.rep_id IN ('$user_id')
		AND a.rep_id = e.user_id
		AND a.leadid = c.user_id
		AND e.department = f.Department_id
		group by a.id
		ORDER BY a.time DESC;
			");
		return $query->result();
	}

	public function fetch_completetask_between_date($user_id,$from_date,$to_date)
	{
		// Fetching Lead, Cust & Opp reminders.
		$completedRemiders =$GLOBALS['$dbFramework']->query("
			SELECT coalesce((CASE 
                 WHEN a.type ='support' THEN
                 (SELECT request_name 
                 FROM support_opportunity_details as li
                 WHERE a.lead_id=li.request_id)
                 WHEN a.type = 'opportunity' THEN 
                (SELECT opportunity_name
                FROM opportunity_details AS li
                WHERE a.lead_id = li.opportunity_id)
                WHEN a.type = 'lead' THEN 
                (SELECT lead_name  
                FROM lead_info AS li
                WHERE a.lead_id = li.lead_id)
                ELSE 
                (SELECT customer_name
                FROM customer_info AS ci
                WHERE a.lead_id = ci.customer_id)
                END)
                ,'-') AS leadname,
                (CASE WHEN a.type ='support' THEN (SELECT request_id FROM support_opportunity_details as li 
                WHERE a.lead_id=li.opp_cust_id)
                WHEN a.type = 'opportunity' THEN
                (SELECT opportunity_id
                FROM opportunity_details AS li
                WHERE a.lead_id = li.opportunity_id)
                WHEN a.type = 'lead' THEN 
                (SELECT lead_id
                FROM lead_info AS li
                WHERE a.lead_id = li.lead_id)
                ELSE 
                (SELECT customer_id
                FROM customer_info AS ci 
                WHERE a.lead_id = ci.customer_id)
                END) AS leadid,
                a.addremtime,a.duration,a.rem_time,a.lead_reminder_id,a.status,a.event_name,
                a.meeting_start,a.remi_date, a.meeting_start,a.remarks,a.leadempid,a.lead_id,
                a.remi_date,a.conntype,a.rep_id AS user_id,a.reminder_members,a.status as reminderstatus,a.timestamp as closed_date,coalesce(ud.user_name,'-') as created_by ,
                b.lookup_id,b.lookup_value,
                d.contact_id AS employeeid,d.contact_number AS employeephone1,d.contact_name AS employeename,
                e.user_name AS person_name,e.user_state,
                f.Department_id,f.Department_name,a.type as type,a.meeting_end as meeting_end,'' as phone
                FROM
                lead_reminder a
                LEFT JOIN contact_details as d 
                ON
                a.leadempid = d.contact_id
                LEFT JOIN user_details as ud
                ON 
                ud.user_id = a.created_by,
                lookup b,
                lead_info c,
                user_details e,
                department f
                WHERE
                (a.rep_id IN ('$user_id'))
                AND date(a.timestamp) BETWEEN date('$from_date') AND date('$to_date')
                AND a.status IN ('' , 'reschedule','cancel')
                AND a.conntype = b.lookup_id
                AND a.rep_id = e.user_id
                AND e.department = f.Department_id
                GROUP BY a.lead_reminder_id
                ORDER BY a.timestamp DESC;
		");

		return $completedRemiders->result();

	}

	public function fetch_mytaskCompleted1_between_date($user_id,$from_date,$to_date)
	{
		// Fetching Internal.
		$completedRemidersInternal =$GLOBALS['$dbFramework']->query("
				SELECT a.event_name AS title,
                a.addremtime,a.duration,a.rem_time,a.lead_reminder_id,a.status,
                a.event_name, a.meeting_start,a.remi_date, a.meeting_start,a.remarks,
                a.leadempid,a.lead_id as leadid,a.remi_date,a.conntype,a.rep_id AS user_id,
                a.reminder_members,b.lookup_id,b.lookup_value, d.user_id AS employeeid,
                d.phone_num AS employeephone1,d.user_name AS employeename,
                e.user_name AS person_name,e.user_state,f.Department_id,f.Department_name,
                a.type as leadname, a.meeting_start as start,a.conntype as activity_id,
                b.lookup_value as activity_name,coalesce(ud.user_name,'-') as created_by,
                a.status,a.timestamp as closed_date,'' as phone
                from 
                lead_reminder a 
                LEFT JOIN user_details ud 
                ON 
                ud.user_id = a.created_by ,
                lookup b, lead_info c, user_details d,
                user_details e,  department f 
                where                
                a.leadempid = d.user_id
                AND a.status IN ('','reschedule','cancel')
                AND date(a.timestamp) BETWEEN date('$from_date') AND date('$to_date')
                AND a.conntype = b.lookup_id
                AND a.rep_id = e.user_id
                and a.type='internal'
                and a.rep_id='user_id'
                AND e.department = f.Department_id
                GROUP BY a.lead_reminder_id
                ORDER BY a.timestamp DESC	
                ");
		return $completedRemidersInternal->result();
	}

	public function fetch_mytaskCompletedReplog_between_date($user_id,$from_date,$to_date)
	{
		$query= $GLOBALS['$dbFramework']->query("
										SELECT coalesce((CASE WHEN a.type = 'opportunity' THEN 
                (SELECT opportunity_name
                FROM opportunity_details AS li
                WHERE a.leadid = li.opportunity_id)
                WHEN a.type = 'lead' THEN 
                (SELECT lead_name  
                FROM lead_info AS li
                WHERE a.leadid = li.lead_id)
                ELSE 
                (SELECT customer_name
                FROM customer_info AS ci
                WHERE a.leadid = ci.customer_id)
                END)
                ,'-') AS leadname,
                (CASE WHEN a.type = 'opportunity' THEN
                (SELECT opportunity_id
                FROM opportunity_details AS li
                WHERE a.leadid = li.opportunity_id)
                WHEN a.type = 'lead' THEN 
                (SELECT lead_id
                FROM lead_info AS li
                WHERE a.leadid = li.lead_id)
                ELSE 
                (SELECT customer_id
                FROM customer_info AS ci 
                WHERE a.leadid = ci.customer_id)
                END) AS leadid,
                TIMEDIFF(a.endtime, a.starttime) as duration,'-' as rem_time,a.log_name as event_name,a.reminderid,a.call_type as reminderstatus,
                a.starttime as meeting_start ,a.time as remi_date, a.note as remarks,a.leademployeeid as leadempid,a.leadid,a.logtype as conntype,a.rep_id AS user_id,a.type as type,a.time as closed_date,coalesce(ud.user_name,'-') as created_by,a.endtime as meeting_end,
                b.lookup_id,b.lookup_value,
                d.contact_id AS employeeid,d.contact_number AS employeephone1,d.contact_name AS employeename,
                e.user_name AS person_name,e.user_state,
                f.Department_id,f.Department_name,a.phone as phone
                FROM
                rep_log a
                left join contact_details as d 
                on a.leademployeeid = d.contact_id
                left join user_details as ud
                on a.rep_id = ud.user_id ,
                lookup b,
                lead_info c,
                user_details e,
                department f
                WHERE
                (a.rep_id IN ('$user_id')) 
                AND a.call_type IN ('complete')
                AND date(a.time) BETWEEN date('$from_date') AND date('$to_date') -- changed
                AND a.logtype = b.lookup_id
                
                AND a.rep_id = e.user_id
                AND e.department = f.Department_id
                group by a.id
                ORDER BY a.time DESC;
			");
		return $query->result();
	}

	public function fetch_mytaskCompletedReplogInternal_between_date($user_id,$from_date,$to_date)
	{
		$query= $GLOBALS['$dbFramework']->query("
			SELECT a.type,a.log_name as event_name,a.reminderid,a.call_type as reminderstatus, a.starttime as meeting_start,a.endtime as meeting_end,
		a.time as remi_date,a.note as remarks,a.leademployeeid as leadempid,a.leadid,a.logtype as conntype,
		a.rep_id AS user_id,a.time as closed_date,coalesce(ud.user_name,'-') as created_by,
		b.lookup_id,b.lookup_value,
		d.user_id AS employeeid,d.phone_num AS employeephone1,d.user_name AS employeename,
		e.user_name AS person_name,e.user_state,c.user_name as leadname,
		f.Department_id,f.Department_name,c.user_name as leadid,TIMEDIFF(a.endtime, a.starttime) as duration,a.phone as phone
		from rep_log as a 
		left join user_details as ud
		on a.rep_id = ud.user_id,
		lookup b,
		user_details d,
		user_details e,
		department f,
		user_details c
		where a.leademployeeid = d.user_id
		AND a.call_type IN ('complete')
		AND a.logtype = b.lookup_id
		AND a.reminderid IS NULL
        AND date(a.time) BETWEEN date('$from_date') AND date('$to_date')
		AND a.type = 'internal'
		AND a.rep_id IN ('$user_id')
		AND a.rep_id = e.user_id
		AND a.leadid = c.user_id
		AND e.department = f.Department_id
		group by a.id
		ORDER BY a.time DESC;
			");
		return $query->result();
	}
	
	 // adding attendance details in the table 06-08-2018

    public function insertPunchDetails($resultset)
    {
        $userId=$resultset['userId'];
        $inOutIdentifier=$resultset['inoutidentifier'];
        $inOutdate=$resultset['inoutdate'];
        $inOutdatetime=$resultset['inoutdatetime'];
        if($inOutIdentifier=='punchIn')
        {
          $insertarray=array(
                    'User_Id'=>$userId,
                    'Date'=>$inOutdate,
                    'PunchIn_Time'=>$inOutdatetime
          );
          //print_r($insertarray);
          $insert = $GLOBALS['$dbFramework']->insert('user_attendance',$insertarray);
          return $insert;
        }else{
           $updatearray=array(
                    'PunchOut_Time'=>$inOutdatetime
          );
          $update_check=$GLOBALS['$dbFramework']->query("SELECT * FROM user_attendance where User_Id='$userId' and Date='$inOutdate'");
           if($update_check->num_rows())
           {
              $update = $GLOBALS['$dbFramework']->update('user_attendance' ,$updatearray, array('User_Id' => $userId,'Date'=>$inOutdate));
           }
           else{
                 $updatearray['User_Id']=$userId;
                 $updatearray['Date']=$inOutdate;
                 $update_response = $GLOBALS['$dbFramework']->insert('user_attendance',$updatearray);
           }
           $update_response=$GLOBALS['$dbFramework']->query("SELECT * FROM user_attendance where User_Id='$userId' and Date='$inOutdate'");
		   return $update_response->result();
        }
    }
    // Checking Lead Number & Contact Number
    public function checkContacts($contactNumber)
	{

		$query = $GLOBALS['$dbFramework']->query("
			SELECT * from lead_info as li where json_contains(li.lead_number,'[\"".$contactNumber."\"]','$.phone')
			");
		$query1  = $GLOBALS['$dbFramework']->query("
			SELECT * from contact_details as li where json_contains(li.contact_number,'[\"".$contactNumber."\"]','$.phone')	;
			");

		$response = array();

		if ($query->num_rows() > 0) 
		{
			if ($query1->num_rows() > 0) 
			{
				$exitsQuery = $GLOBALS['$dbFramework']->query("
																SELECT li.contact_name as contactName
																FROM contact_details as li
																WHERE
																json_contains(li.contact_number,'[\"".$contactNumber."\"]','$.phone')
															 ");
				$Name = $exitsQuery->result();
				$response['exist'] = $Name[0]->contactName.' (Contact).';
				$response['result'] = 1;
			}
			else
			{
				$exitsQuery = $GLOBALS['$dbFramework']->query("
																SELECT li.lead_name as LeadName
																FROM lead_info as li
																WHERE
																json_contains(li.lead_number,'[\"".$contactNumber."\"]','$.phone')
															 ");
				$Name = $exitsQuery->result();
				$response['exist'] = $Name[0]->LeadName.' (Lead).';
				$response['result'] = 1;
			}	
		}
		else if ($query1->num_rows() > 0) 
		{
				$exitsQuery = $GLOBALS['$dbFramework']->query("
																SELECT li.contact_name as contactName
																FROM contact_details as li
																WHERE
																json_contains(li.contact_number,'[\"".$contactNumber."\"]','$.phone')
															 ");
				$Name = $exitsQuery->result();
				$response['exist'] = $Name[0]->contactName.' (Contact).';
				$response['result'] = 1;
		}
		else
		{
			$response['exist'] = "Number is not exits in Lead (or) Contact";
			$response['result'] = 0;
		}

		return $response;
	}

	public function getContactsforMobile()
	{
		$query = $GLOBALS['$dbFramework']->query("
			SELECT contact_id, contact_name, contact_number, contact_email from contact_details
			");
		return $query->result();
	}

	// Added For Lead Injection From Website,

	public function fetchSuperiorManager() {
       try{
            $query=$GLOBALS['$dbFramework']->query("
													SELECT 
													ud1.user_id as superior_id,ud1.user_name as superior_name
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

 }