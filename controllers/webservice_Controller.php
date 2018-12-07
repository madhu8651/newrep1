  <?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('webservice_Controller');

class webservice_Controller extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('webservice_model','service');
		$this->load->model('sales_mytaskModel','mytask');
		$this->load->model('common_opportunitiesModel','opp_common');
		$this->load->model('sales_opportunitiesModel','opp_sales');
		$this->load->model('manager_opportunitiesModel','opp_mgr');
		$this->load->model('admin_userModel1','userinfo');
		$this->load->model('leadinfo_model','lead');
		$this->load->model('sales_com_personalmailModel','personalmail');
        $this->load->model('emailExtractModel','emailModel');
        $this->load->helper('url');

	}

	/*------------------------------ BASIC SETUP & LOGIN ------------------------------*/
	public function login(){
		$loginId = trim($_POST['login_username']," ");
		$pwd = trim($_POST['login_password']," ");
		$devID = trim($_POST['login_deviceId']," ");
		$result = array();
		$success = false;
		$modules = '';
		$plugins = '';
		$status = $this->service->validate($loginId,$pwd);
		if($status->num_rows()>0){
			foreach($status->result_array() as $data){
				$userdata = array(
					'uid' => $data['user_id'],
					'uname' => $data['user_name'],
					'rep_id' => $data['rep_id'],
					'location_tracking' => $data['location_tracking'],
					'call_recording' => $data['call_recording'],
					'holidaycalendar' => $data['holidaycalendar'],
					'team_id' => $data['team_id'],
					'department' => $data['department'],
					'rep_mgr' => $data['reporting_to'],
					'user_photo' => $data['user_photo'],
					'user_gender' => $data['user_gender'],
					'user_last_name' => $data['user_last_name'],
					'user_firebase_token' => $data['user_firebase_token'],
					'user_address' => $data['user_address'],
					'user_dob' => $data['user_dob'],
					'user_designation' => $data['user_designation'],
					'user_timezone' => $data['user_timezone'],
					'user_primary_email' => $data['user_primary_email'],
					'user_primary_mobile' => $data['user_primary_mobile'],
					'user_state'=> $data['user_state'],
					'login_state'=>$data['login_state'],
					'user_designation_name'=>$data['user_designation_name'],
					'department_name'=>$data['department_name'],
					'reporting_to_name'=>$data['reporting_to_name'],
					'team_name'=>$data['team_name'],
					'device_brand'=>$data['device_brand'],
					'device_id'=>$data['device_id'],
					'device_model'=>$data['device_model'],
					'device_os_version'=>$data['device_os_version'],
					'device_type'=>$data['device_type'],
					'imei'=>$data['imei'],
					'sim_network_type'=>$data['sim_network_type']

				);

					$modules = $data['modules'];
					$plugins = $data['plugins'];
					

			}
			$plugins = json_decode($plugins);
			if($plugins->Navigator==''){
				$userdata['Navigator'] = 0;
			}else{
				$userdata['Navigator'] = 1;
			}
			if($plugins->Communicator==''){
				$userdata['Communicator'] = 0;
			}else{
				$userdata['Communicator'] = 1;
			}
			if($plugins->Attendence==''){
				$userdata['Attendence'] = 0;
			}else{
				$userdata['Attendence'] = 1;
			}
			if($plugins->Expense==''){
				$userdata['Expense'] = 0;
			}else{
				$userdata['Expense'] = 1;
			}
			if($plugins->Library==''){
				$userdata['Library'] = 0;
			}else{
				$userdata['Library'] = 1;
			}
			if($plugins->Inventory==''){
				$userdata['Inventory'] = 0;
			}else{
				$userdata['Inventory'] = 1;
			}

			$modules = json_decode($modules);
			if($modules -> cxo == '0'){
				$userdata['cxo'] = 0;
			}else{
				$userdata['cxo'] = 1;
			}
			if($modules -> sales == '0'){
				$userdata['sales'] = 0;
			}else{
				$userdata['sales'] = 1;
			}
			if($modules -> Manager == '0'){
				$userdata['Manager'] = 0;
			}else{
				$userdata['Manager'] = 1;
			}
			if($modules -> custo_assign == '0'){
				$userdata['custo_assign'] = 0;
			}else{
				$userdata['custo_assign'] = 1;
			}

			// Device Info..
			$Device_id = $_POST['Device_id'];
			$Device_type = $_POST['Device_type'];
			$Imei       = $_POST['Imei'];
			$Device_model = $_POST['Device_model'];
			$Device_Os_version = $_POST['Device_Os_version'];
			$Device_brand = $_POST['Device_brand'];
			$SIM_network_type = $_POST['SIM_network_type'];

			$deviceArray = array(
							'Device_id'=>$Device_id,
							'Device_type'=>$Device_type,
							'Imei'=>$Imei,
							'Device_model'=>$Device_model,
							'Device_Os_version'=>$Device_Os_version,
							'Device_brand'=>$Device_brand,
							'SIM_network_type'=>$SIM_network_type,
							'user_id'=>$userdata['uid']
							);
			$addDeviceInfo  =  $this->service->insertDeviceInfo($userdata['uid'],$deviceArray,$devID);

				if ($addDeviceInfo == TRUE) 
				{
					$update = $this->service->updateRep($userdata['uid'],$devID);
					$update1 = $this->service->updateLoginState($userdata['uid']);

					if($update1 > 0)
					{
						$result['success'] = true;
						$result['data'] = $userdata;
						$result['message'] = 'Login Successfull.';
						echo json_encode($result);
					} 
					else
					{
						$result['success'] = false;
						$result['message'] = 'Login Unsuccessfull.';
						echo json_encode($result);
					}
				}
				else
				{
					$result['success'] = false;
					$result['message'] = 'Your account has been signed in to another device.';
					echo json_encode($result);
				}
		}
		else
		{
			$result['success'] = false;
			$result['message'] = 'Login Unsuccessfull.';
			echo json_encode($result);
		}

	}

	public function get_users(){
		$userID = trim($_POST['lead_userid']," ");
		$result_set= array();
		$user= $this->service->user_details($userID);
		if(count($user)>0){
			$result_set['success'] = true;
			$result_set['data'] = $user;
			echo json_encode( $result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function getuser_mapping(){
		$userID = trim($_POST['user_id']," ");
		$result= array();

		$status = $this->service->get_user_mapping($userID);
		if($status->num_rows()>0){
			foreach($status->result_array() as $data){
				$userdata = array(
				'user_mapping_id' => $data['user_mapping_id'],
				'user_id' => $data['user_id'],
				'map_type' => $data['map_type'],
				'map_id' => $data['map_id'],
				'map_key' => $data['map_key'],
				'map_value' => $data['map_value'],
				'map_name' => $data['map_name']
				);
			}
			$result['success'] = true;
			$result['data'] = $status;
			echo json_encode($result);
		} else{
			$result['success'] = false;
			echo json_encode($result);
		}
	}

	public function get_currency(){
		$status = $this->service->get_currency();
		if($status->num_rows()>0){
			$result['success'] = true;
			$result['data'] = $status->result();
		} else {
			$result['success'] = false;
		}
		echo json_encode($result);
	}

	public function phone_get_lookup(){
		$result_set= array();
		$reminder = $this->service->phone_fetch_lookup();
		if(count($reminder)>0){
			$result_set['success'] = true;
			$result_set['data'] = $reminder;
			echo json_encode( $result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function get_all_users(){
		$result_set= array();
		$user= $this->service->all_user_details();
		if(count($user)>0){
			$result_set['success'] = true;
			$result_set['data'] = $user;
			echo json_encode( $result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function reset_login($username){
		$update1 = $this->service->reset_login_state($username);
		if($update1>0){
			$result['success'] = true;
			echo json_encode($result);
		} else{
			$result['success'] = false;
			echo json_encode($result);
		}
	}

	/*---------------------------------- LEADS ---------------------------------*/
	public function lead_list(){
		$userID = trim($_POST['lead_userid']," ");
		if($userID!=""){
			$result = array();
			$result_set= array();
			$new = $this->service->get_newleads($userID);
			$accept = $this->service->get_acceptleads($userID);
			$progress = $this->service->get_progressleads($userID);
			$closed = $this->service->get_closedleads($userID);
			array_push($result,$new);
			array_push($result,$accept);
			array_push($result,$progress);
			array_push($result,$closed);
			if(count($result)>0){
				$result_set['success'] = true;
				$result_set['data'] = $result;
				echo json_encode( $result_set);
			} else {
				$result_set['success'] = false;
				echo json_encode($result_set);
			}
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function get_leadsource() {
		$result= array();
		$lead_source = $this->service->fetch_leadsource();
		if(count($lead_source)>0){
			$result['success'] = true;
			$result['data'] = $lead_source;
			echo json_encode($result);
		} else {
			$result['success'] = false;
			echo json_encode($result);
		}
	}

	public function add_lead(){

		$result = array();
		$userid = trim($_POST['lead_userid']," ");
		$leadid 		= isset($_POST['lead_id']) 		  ? $_POST['lead_id'] 		: null;
		$leadname 		= isset($_POST['lead_name'])	  ? $_POST['lead_name'] 	: null;
		$leadwebsite 	= isset($_POST['lead_website'])	  ? $_POST['lead_website']  : null;
		$coordinate		= isset($_POST['lead_cordinate']) ? $_POST['lead_cordinate']: null;
		$ofcaddress 	= isset($_POST['lead_ofc_address'])  ? $_POST['lead_ofc_address']  : null;
		$city 			= isset($_POST['lead_city']) 	  ? $_POST['lead_city'] 	: null;
		$state 		 	= isset($_POST['lead_state']) 	  ? $_POST['lead_state'] 	: null;
		$leadcountry 	= isset($_POST['lead_country'])   ? $_POST['lead_country']  : null;
		$zipcode  		= isset($_POST['lead_zipcode'])   ? $_POST['lead_zipcode']  : null;
		$splcomments 	= isset($_POST['lead_spl_comments']) ? $_POST['lead_spl_comments'] : null;
		$leadsource 	= isset($_POST['leadsource']) 	  ? $_POST['leadsource']    : null;
		$industry 		= isset($_POST['industry']) 	  ? $_POST['industry']      : null;
		$bussiness		= isset($_POST['bussiness']) 	  ? $_POST['bussiness']     : null;
		$lead_status 	= isset($_POST['lead_status'])    ? $_POST['lead_status'] 	: null;
		$lead_mgr_owner = isset($_POST['lead_mgr_owner']) ? $_POST['lead_mgr_owner']: null;
		$base64String 	= isset($_POST['lead_logo'])	  ? $_POST['lead_logo'] 	: null;

		$GLOBALS['$log']->debug('Data:add_lead');
		$GLOBALS['$log']->debug($_POST);
		$dt = date('Y-m-d H:i:s');
		$data1 = array(
			'lead_id' 		=> $leadid,
			'lead_name' 	=> $leadname,
			'lead_website' 	=> $leadwebsite,
			'lead_location_coord' => $coordinate,
			'lead_address'	=> $ofcaddress,
			'lead_city' 	=> $city,
			'lead_state'	=> $state,
			'lead_country'	=> $leadcountry,
			'lead_zip'		=> $zipcode,
			'lead_remarks'	=> $splcomments,
			'lead_source'	=> $leadsource,
			'lead_industry'	=> $industry,
			'lead_business_loc'	=> $bussiness,
			'lead_rep_owner'	=> $userid,
			'lead_rep_status'	=> "2",
			'lead_manager_owner' => $lead_mgr_owner,
			'lead_manager_status'=> "2",
			'lead_created_by'	=> $userid,
			'lead_created_time'	=> $dt,
			'lead_updated_by' 	=> $userid,
			'lead_status'	 	=> $lead_status,
		 );

		if ($base64String != null) {
			$lead_logo  = base64_decode($base64String);

			$image_name = $leadid;
			$filename = $image_name . '.' . 'jpg';
			$filePath = FCPATH."uploads/".$filename;
			file_put_contents($filePath, $lead_logo);

			$data1['lead_logo'] = $filename;
		}

		if (isset($_POST['lead_phones'])) {
			$data1['contact_number'] = $_POST['lead_phones'];
			$lead_phone = array();
			$lead_phone['phone'][0] = $_POST['lead_phones'];
			$data1['lead_number'] = json_encode($lead_phone);
		}else{
				$leadContact['phone'][0] = "";
                $data1['lead_number'] = json_encode($leadContact);
		}
		if (isset($_POST['lead_mail'])) {
			$lead_email = array();
			$lead_email['email'][0] = $_POST['lead_mail'];
			$data1['lead_email'] = json_encode($lead_email);
		}else{
				$leadEmail['email'][0] = "";
                $data1['lead_email'] = json_encode($leadEmail);
		}

		$data3 = array(
			'lead_cust_id' =>$leadid,
			'type'=>'lead',
			'state' =>'0',
			'action'=>"created",
			'module'=>"sales",
			'from_user_id'=>$userid,
			'to_user_id'=>$userid,
			'timestamp'=>$dt,
		);
		$data4 = array(
			'lead_cust_id' =>$leadid,
			'type'=>'lead',
			'state' =>'0',
			'action'=>"seen",
			'module'=>"sales",
		    'from_user_id'=>$userid,
		    'to_user_id'=>$userid,
			'timestamp'=>$dt,
		);
		$data5 = array(
			'lead_cust_id' =>$leadid,
			'type'=>'lead',
			'state' =>'1',
			'action'=>"accepted",
			'module'=>"sales",
			'from_user_id'=>$userid,
			'to_user_id'=>$userid,
			'timestamp'=>$dt,
		);

		$insert = $this->service->insert_lead($data1);
		$GLOBALS['$log']->debug('Inserted lead');
		$insert2 = $this->service->insert_lead_cust_trans($data3);
		$insert3 = $this->service->insert_lead_cust_trans($data4);
		$insert4 = $this->service->insert_lead_cust_trans($data5);
		$GLOBALS['$log']->debug('Inserted transaction rows');
		if($insert==TRUE && $insert3==TRUE && $insert4==TRUE){
			$result['success'] = true;
			echo json_encode($result);
		} else {
			$result['success'] = false;
			echo json_encode($result);
		}
	}

	public function edit_lead(){
		$GLOBALS['$log']->debug('Data:edit_lead');
		$GLOBALS['$log']->debug($_POST);
		$dt = date('Y-m-d H:i:s');
		$result= array();

		$userid = "";
		if (isset($_POST['lead_userid'])) {
			$userid = trim($_POST['lead_userid']," ");
		} else {
			$GLOBALS['$log']->debug('No userID');
			$result['success'] = false;
			echo json_encode($result);
		}

		$leadid = "";
		if (isset($_POST['lead_id'])) {
			$leadid = $_POST['lead_id'];
		} else {
			$GLOBALS['$log']->debug('No Lead ID on data');
			$result['success'] = false;
			echo json_encode($result);
		}

		$data1 = array();
		if (isset($_POST['lead_name'])) {
			$data1['lead_name'] = $_POST['lead_name'];
		}
		if (isset($_POST['lead_website'])) {
			$data1['lead_website'] = $_POST['lead_website'];
		}
		if (isset($_POST['lead_cordinate'])) {
			$data1['lead_location_coord'] = $_POST['lead_cordinate'];
		}
		if (isset($_POST['lead_ofc_address'])) {
			$data1['lead_address'] = $_POST['lead_ofc_address'];
		}
		if (isset($_POST['lead_city'])) {
			$data1['lead_city'] = $_POST['lead_city'];
		}
		if (isset($_POST['lead_state'])) {
			$data1['lead_state'] = $_POST['lead_state'];
		}
		if (isset($_POST['lead_country'])) {
			$data1['lead_country'] = $_POST['lead_country'];
		}
		if (isset($_POST['lead_zipcode'])) {
			$data1['lead_zip'] = $_POST['lead_zipcode'];
		}
		if (isset($_POST['lead_spl_comments'])) {
			$data1['lead_remarks'] = $_POST['lead_spl_comments'];
		}
		if (isset($_POST['leadsource'])) {
			$data1['lead_source'] = $_POST['leadsource'];
		}
		if (isset($_POST['industry'])) {
			$data1['lead_industry'] = $_POST['industry'];
		}
		if (isset($_POST['bussiness'])) {
			$data1['lead_business_loc'] = $_POST['bussiness'];
		}
		if (isset($_POST['lead_status'])) {
			$data1['lead_status'] = $_POST['lead_status'];
		}
		if (isset($_POST['lead_rep_status'])) {
			$data1['lead_rep_status'] = $_POST['lead_rep_status'];
		}
		if (isset($_POST['lead_phones'])) {
			$data1['contact_number'] = $_POST['lead_phones'];

			$lead_phone = array();
			$lead_phone['phone'][0] = $_POST['lead_phones'];
			$data1['lead_number'] = json_encode($lead_phone);
		}
		if (isset($_POST['lead_mail'])) {
			$lead_email = array();
			$lead_email['email'][0] = $_POST['lead_mail'];
			$data1['lead_email'] = json_encode($lead_email);
		}
		if (isset($_POST['lead_updated_time'])) {
			$dt = $_POST['lead_updated_time'];
		}


		if (isset($_POST['lead_logo'])) {
			$base64String = $_POST['lead_logo'];
			if ($base64String != null) {
				$lead_logo  = base64_decode($base64String);

				$image_name = $leadid;
				$filename = $image_name . '.' . 'jpg';
				$filePath = FCPATH."uploads/".$filename;
				file_put_contents($filePath, $lead_logo);

				$data1['lead_logo'] = $filename;
			}
		}
		$data1['lead_rep_owner']	= $userid;
		$data1['lead_updated_by'] 	= $userid;
		$data1['lead_updated_time']	= $dt;

		$data3 = array(
			'lead_cust_id' =>$leadid,
			'type'=>'lead',
			'action'=>"edited",
			'module'=>"sales",
			'from_user_id'=>$userid,
			'to_user_id'=>$userid,
			'timestamp'=>$dt,
		);
		$update = $this->service->update_lead_info($leadid,$data1);
		$update2 = $this->service->insert_lead_cust_trans($data3);
		$update3 = $this->service->delete_lead_product($leadid);

		if($update==TRUE && $update2==TRUE && $update3==TRUE){
			$result['success'] = true;
			echo json_encode($result);
		}else{
			$result['success'] = false;
			echo json_encode($result);
		}
	}

	public function accept_lead() {
		$userid = trim($_POST['lead_userid']," ");
		$leadid = $_POST['lead_id'];
		$dt = date('Y-m-d H:i:s');
		$result_set=array();
		$lead_rep_status= $this->service->lead_rep_status($leadid);
		$lead_status= $lead_rep_status[0]->lead_rep_status;
		if($lead_status==1){
			$data1= array(
				'lead_status'=>0,
				'lead_rep_owner'=>$userid,
				'lead_rep_status'=>2
			);
			$data2= array(
				'mapping_id' =>uniqid(rand()) ,
				'lead_cust_id' =>$leadid,
				'type'=>'lead',
				'state' =>1,
				'action'=>"accepted",
				'module'=>"sales",
				'from_user_id'=>$userid,
				'to_user_id'=>$userid,
				'timestamp'=>$dt,
			);
			$update = $this->service->update_lead_info($leadid,$data1);
			$update2 = $this->service->update_lead_cust_trans($leadid);
			$update1 = $this->service->insert_lead_cust_trans($data2);
			$update3 = $this->service->update_lead_reminder($userid,$leadid);
			if($update1==true && $update==true && $update2==true && $update3==true){
				 $result_set['success'] = true;
				 echo json_encode( $result_set);
			} else{
				$result_set['success'] = false;
				echo json_encode($result_set);
			}
		}else{
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function reject_lead(){
		$userid = trim($_POST['lead_userid']," ");
		$dt = date('ymdHis');
		$leadid = $_POST['lead_id'];
		$remarks = $_POST['lead_remarks'];
		$result_set=array();
		$lead_rep_status= $this->service->lead_rep_status($leadid);
		$lead_status= $lead_rep_status[0]->lead_rep_status;
		$notify_id= uniqid($dt);
		$data2= array(
			'notificationID' =>$notify_id,
			'notificationShortText'=>'Lead Rejected',
			'notificationText' =>'Lead Rejected',
			'from_user'=>"$userid",
			'to_user'=>$userid,
			'action_details'=>'lead',
			'notificationTimestamp'=>$dt,
			'read_state'=>0,
			'remarks'=>$remarks,
		);
		if($lead_status==1){
			$check_assign= $this->service->lead_last_reject($leadid,$remarks,$data2,$userid);
			if($check_assign==true){
				$result_set['success'] = true;
				echo json_encode($result_set);
			} else {
				$result_set['success'] = false;
				echo json_encode($result_set);
			}
		}else{
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function check_lead(){
		$result = array();
		$leadname = $_POST['leadName'];
		$insertchk = $this->service->duplicate_lead($leadname);
	   if($insertchk==1){
		   $result['success'] = true;
	   } else{
		   $result['success'] = false;
	   }
	   echo json_encode($result);
	}

	public function lead_product_map(){
		$userID = trim($_POST['user_id']," ");
		$result_set= array();
		$lpm= $this->service->get_lead_product_map($userID);
		if(count($lpm)>0){
			$result_set['success'] = true;
			$result_set['data'] = $lpm;
			echo json_encode($result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function add_lead_product_map(){
		$result= array();
		$prod_id = $_POST['product_id'];
		$lead_id = $_POST['lead_id'];
		$timestamp = $_POST['timestamp'];
		$remarks = $_POST['remarks'];
		$data6 = array(
				'lead_id' =>$lead_id,
				'product_id'=>$prod_id,
				'timestamp' =>$timestamp,
				'remarks' => $remarks
			);
		$insert5 = $this->service->insert_lead_product($data6);
		if($insert5 == TRUE)
		{
			$result['success'] = true;
				echo json_encode($result);
		}else{
				$result['success'] = false;
				echo json_encode($result);
		}
	}

	public function add_lead_cust_user(){
		$result= array();
		$data['lead_cust_id'] = $_POST['lead_cust_id'];
		$data['from_user_id'] = $_POST['from_user_id'];
		$data['to_user_id'] = $_POST['to_user_id'];
		$data['module'] = $_POST['module'];
		$data['action'] = $_POST['action'];
		$data['timestamp'] = $_POST['timestamp'];
		$data['state'] = $_POST['state'];
		$data['type'] = $_POST['type'];
		$data['remarks'] = $_POST['remarks'];
		$data['mapping_id'] = $_POST['mapping_id'];

		$inProgressRow = $this->service->get_lead_cust_user_map($data['lead_cust_id'], $data['action']);
		if ($inProgressRow == false) {
			$insert = $this->service->insert_lead_cust_trans($data);
			if($insert == TRUE) {
				$result['success'] = true;
				echo json_encode($result);
			} else {
				$result['success'] = false;
				echo json_encode($result);
			}
		} else {
			$result['success'] = false;
			echo json_encode($result);
		}
	}

	public function close_lead(){

		$close_opportunity_insert_array = array();
        $close_opportunity_update_array = array();
        $opportunity_tasks_array = array();

        $leadid	= $_POST['lead_id'];
        $userid = $_POST['user_id'];
        
        $loss_type		 = $_POST['loss_type'];
		$remarks 		 = $_POST['remarks'];
		$name 			 = $_POST['lead_name'];
		$cancel_pending	 = $_POST['cancel_pending']; 
		$future_activity = isset($_POST['connect_type']) ? $_POST['connect_type'] : "";
		$duration 		 = isset($_POST['duration']) ? $_POST['duration'] : "00:30:00";
		$alert_before	 = isset($_POST['alert_before']) ? $_POST['alert_before'] : "30";
		$task_title 	 = isset($_POST['task_title']) ? $_POST['task_title'] : "Reconnect with ".$name;
		$contact_id 	 = isset($_POST['contact_id']) ? $_POST['contact_id'] : "";

        $approach_date = isset($_POST['connect_date'])? $_POST['connect_date']: date("Y-m-d H:i:s");

        $dt = date('ymdHis');
		$lead_reminder_id = '';
		$lead_reminder_id .= $dt;
		$lead_reminder_id = uniqid($lead_reminder_id);
		$mapping_id = uniqid(rand(),TRUE);
		$newDate = date("Y-m-d H:i:s");
		$event_start_date = date('Y-m-d', strtotime($approach_date));
		$event_start_time = date('H:i:s', strtotime($approach_date));
		$seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
		$activity_duration = (int)$seconds->getTimestamp();
		$start = new DateTime($approach_date);
		$event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
		$event_end = $event_end->format('Y-m-d H:i:s');

        // Fetch all opportunity data;
        $opportunity_data = $this->lead->check_opportunity_owner($leadid,$userid);

        for ($i=0; $i <count($opportunity_data['value']) ; $i++)
        { 
            $log_trans_data = array(
                'mapping_id' => uniqid(date('ymdHis')),
                'opportunity_id'=> $opportunity_data['value'][$i]->opportunity_id,
                'lead_cust_id' => $opportunity_data['value'][$i]->lead_cust_id,
                'from_user_id'=> $userid,
                'to_user_id'=> $userid,
                'cycle_id' => $opportunity_data['value'][$i]->cycle_id,
                'stage_id' => $opportunity_data['value'][$i]->opportunity_stage,
                'module' => 'sales',
                'timestamp'=> date('Y-m-d H:i:s'),
                'sell_type' => $opportunity_data['value'][$i]->sell_type,
                'remarks' => '',
            );


            $update_opp_data = array(
                                'opportunity_id' => $opportunity_data['value'][$i]->opportunity_id,
                                'closed_status' => '100',
                                'opportunity_approach_date'=>$_POST['connect_date'],
                                'closed_reason'=>$_POST['loss_type']
                                );

                if ($loss_type == 'temporary_loss') 
                {
                    $dt = date('ymdHis');
                    $lead_reminder_id = '';
                    $lead_reminder_id .= $dt;
                    $lead_reminder_id = uniqid($lead_reminder_id); 

                    $opportunity_tasks = array(
						'lead_reminder_id' => $lead_reminder_id.rand(),
						'lead_id'   => $opportunity_data['value'][$i]->opportunity_id,
						'rep_id'    => $userid,
						'leadempid' => $contact_id,
						'remi_date' => $event_start_date,
						'rem_time'  => $event_start_time,
						'conntype'  => $future_activity,
						'status'    => "scheduled",
						'meeting_start' => $approach_date,
						'meeting_end'   => $event_end,
						'addremtime'    => $alert_before,
						'timestamp'     => $newDate,
						'remarks'       => $remarks,
						'event_name'    => $task_title,
						'duration'      => $duration,
						'type' 			=> "opportunity",
						'created_by'	=> $userid,
						'module_id'		=> 'sales'
                    ); 

                    array_push($opportunity_tasks_array, $opportunity_tasks); 
                }
           

            // Closing all opportunity tasks ,.

            if ($_POST['cancel_pending'] == 'true' )
            {
                $this->service->cancel_pending_activities($opportunity_data['value'][$i]->opportunity_id,$userid);
            }

            array_push($close_opportunity_update_array, $update_opp_data);
            array_push($close_opportunity_insert_array, $log_trans_data);

        }


		if (isset($_POST['contact_id'])) {
			$contact_id = $_POST['contact_id'];
		} else {
			// fetch a contact for given lead ID and put it here
			$contacts = $this->service->get_contact_for($leadid);
			if (count($contacts) == 0) {
				$result['success'] = false;
				echo json_encode($result);
				return;
			}
			$contact_id = $contacts[0]->contact_id;
		}

		$data6 = array(
			'lead_reminder_id' => $lead_reminder_id,
			'lead_id'   => $leadid,
			'rep_id'    => $userid,
			'leadempid' => $contact_id,
			'remi_date' => $event_start_date,
			'rem_time'  => $event_start_time,
			'conntype'  => $future_activity,
			'status'    => "scheduled",
			'meeting_start' => $approach_date,
			'meeting_end'   => $event_end,
			'addremtime'    => $alert_before,
			'timestamp'     => $newDate,
			'remarks'       => $remarks,
			'event_name'    => $task_title,
			'duration'      => $duration,
			'type' 			=> "lead",
			'created_by'	=> $userid,
			'module_id'		=> 'sales'
		);

		$data1 = array(
			'lead_status' => 4,
			'lead_closed_reason' => $loss_type,
		);
		$data3 = array(
			'lead_status' => 3,
			'lead_closed_reason' => $loss_type,
			'lead_approach_date' => $approach_date,
		);
		$data4 = array(
			'mapping_id' 	=> $mapping_id,
			'lead_cust_id' 	=> $leadid,
			'type'			=> 'lead',
			'action'		=> "closed",
			'module'		=> "sales",
			'from_user_id'	=> $userid,
			'to_user_id'	=> $userid,
			'state'			=> 1,
			'timestamp'		=> $dt,
			'remarks'		=> $remarks
		);
		$notify_id= uniqid($dt);
		$data5= array(
			'notificationID' 	=> $notify_id,
			'notificationShortText'=> 'Lead Closed',
			'notificationText' 	=> $name.' lead closed by force close',
			'from_user'			=> $userid,
			'to_user'			=> $userid,
			'action_details'	=> 'lead',
			'notificationTimestamp'=> $dt,
			'read_state'		=> 0,
			'remarks'			=> $remarks,
		);

		if($loss_type=='permanent_loss'){
			$update1 = $this->service->update_lead_info($leadid,$data1);
			$insert = $this->service->insert_lead_cust_trans($data4);
			// opportunity data's.
               if (!empty($close_opportunity_insert_array) && !empty($close_opportunity_update_array)) 
               {
                   $log_data = $this->lead->insert_opp_log($close_opportunity_insert_array);
                   if ($log_data == 1) 
                   {
                       $update_data = $this->lead->update_opp_data($close_opportunity_update_array);
                   }
               }

			if($cancel_pending == 'true'){
				$update4 = $this->service->cancel_pending_activities($leadid, $userid);
			}
		}

		if($loss_type=='temporary_loss')
		{

			$update1 = $this->service->update_lead_info($leadid,$data3);
			$insert = $this->service->insert_lead_cust_trans($data4);
			// opportunity data's.

               if (!empty($close_opportunity_insert_array) && !empty($close_opportunity_update_array)) 
               {
                   $log_data = $this->lead->insert_opp_log($close_opportunity_insert_array);
                   if ($log_data == 1) 
                   {
                       $update_data = $this->lead->update_opp_data($close_opportunity_update_array);
                   }

                   if($cancel_pending== 'true'){
						$update4 = $this->service->cancel_pending_activities($leadid, $userid);
					}

                   $opp_tasks = $this->lead->insert_reminder($opportunity_tasks_array);

               }

			$insert6 = $this->service->insert_reminder($data6);

		}

		$notify = $this->service->notifications($data5);
		if($notify == TRUE){
			$result['success'] = true;
			echo json_encode($result);
		} else {
			$result['success'] = false;
			echo json_encode($result);
		}
	}
	/*-------------------------------- CUSTOMERS -------------------------------*/
	public function customer_info(){

		$userID = trim($_POST['lead_userid']," ");
		$result = array();
		$result_set= array();
		if($userID!=""){
			$new = $this->service->get_newCustomers($userID);
			$accept = $this->service->get_acceptedCustomers($userID);
			$myCust = $this->service->get_myCustomers($userID);
			$result = array_merge($new, $accept, $myCust);
			if(count($result)>0){
				$result_set['success'] = true;
				$result_set['data'] = $result;
				echo json_encode($result_set);
			} else {
				$result_set['success'] = false;
				echo json_encode($result_set);
			}
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function product_purchase_info(){
		$userID = trim($_POST['user_id']," ");
		$result_set= array();
		$ppi= $this->service->get_product_purchase_info($userID);
		if(count($ppi)>0){
			$result_set['success'] = true;
			$result_set['data'] = $ppi;
			echo json_encode($result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function accept_customer() {
		$user_id = trim($_POST['user_id']," ");
		$customer_id = $_POST['customer_id'];
		$dt = date('Y-m-d H:i:s');
		$result_set=array();
		$customer_rep_status= $this->service->customer_rep_status($customer_id);
		$rep_status= $customer_rep_status[0]->customer_rep_status;
		if($rep_status==1){
			$data1= array(
				'customer_status'=>0,
				'customer_rep_owner'=>$user_id,
				'customer_rep_status'=>2
			);
			$data2= array(
				'mapping_id'=>uniqid(rand()),
				'lead_cust_id'=>$customer_id,
				'type'=>'customer',
				'state'=>1,
				'action'=>"accepted",
				'module'=>'sales',
				'from_user_id'=>$user_id,
				'to_user_id'=>$user_id,
				'timestamp'=>$dt,
			);
			$update = $this->service->update_customer_info($customer_id,$data1);
			$update2 = $this->service->update_lead_cust_trans($customer_id);
			$update1 = $this->service->insert_lead_cust_trans($data2);
			$update3 = $this->service->update_customer_reminder($user_id,$customer_id);
			if($update1==true && $update==true && $update2==true && $update3==true){
				 $result_set['success'] = true;
				 echo json_encode($result_set);
			} else {
				$result_set['success'] = false;
				echo json_encode($result_set);
			}
		}else{
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function reject_customer(){
		$result_set=array();

		$user_id = trim($_POST['user_id']," ");
		$customer_id = $_POST['customer_id'];
		$remarks = $_POST['customer_remarks'];
		$customer_rep_status= $this->service->customer_rep_status($customer_id);
		$rep_status= $customer_rep_status[0]->customer_rep_status;
		$dt = date('ymdHis');
		$notify_id= uniqid($dt);
		$data2= array(
			'notificationID' =>$notify_id,
			'notificationShortText'=>'Customer Rejected',
			'notificationText' =>'Customer Rejected',
			'from_user'=>$user_id,
			'to_user'=>$user_id,
			'action_details'=>'customer',
			'notificationTimestamp'=>$dt,
			'read_state'=>0,
			'remarks'=>$remarks,
		);
		if($rep_status==1){
			$check_assign= $this->service->customer_last_reject($customer_id,$remarks,$data2,$user_id);
			if($check_assign==true){
				$result_set['success'] = true;
				echo json_encode($result_set);
			} else {
				$result_set['success'] = false;
				echo json_encode($result_set);
			}
		}else{
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	/*------------------------------ OPPORTUNITIES -----------------------------*/
	public function opportunity_list(){
		$userID = trim($_POST['lead_userid']," ");
		$result_set= array();
		$opp= $this->service->opportunity($userID);
		if(count($opp)>0){
			$result_set['success'] = true;
			$result_set['data'] = $opp;
			echo json_encode( $result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function getNewOpportunity(){
		$userID = trim($_POST['user_id']," ");
		$result= array();
		// $status = $this->service->get_newopportunity($userID);
		$status = $this->opp_sales->fetch_new_opportunities($userID);
		if($status > 0){
			$result['success'] = true;
			$result['data'] = $status;
		} else {
			$result['success'] = false;
		}
		echo json_encode($result);
	}

	public function getInprogressOpportunity(){
		$userID = trim($_POST['user_id']," ");
		$result= array();
		$status = $this->service->get_inprogressopportunity($userID);
		if($status->num_rows()>0){
			$result['success'] = true;
			$result['data'] = $status->result_array();
		} else {
			$result['success'] = false;
		}
		echo json_encode($result);
	}

	public function getClosedOpportunity(){
		$userID = trim($_POST['user_id']," ");
		$result= array();
		$status = $this->service->get_closedopportunity($userID);
		if($status->num_rows()>0){
			$result['success'] = true;
			$result['data'] = $status->result_array();
		} else {
			$result['success'] = false;
		}
		echo json_encode($result);
	}

	public function stage_view($opp_id = '',$user_id = '') {
		$data['opportunity_id'] = $opp_id;
		$data['user_id'] = $user_id;

		$updateStatus = $this->opp_common->canUpdate($user_id, $opp_id);
		if ($updateStatus == 1) {
			$urlParts = explode('/', base_url());
			$clientid = $urlParts[3];
			$this->session->set_userdata('uid', $user_id);
			$this->session->set_userdata('clientid', $clientid);
			$_SESSION['active_module_name'] = 'sales';
			$this->load->view('mobile_opp_stageview', $data);
		} else {
			echo "<h1 style='font-size:72pt;text-align:center;padding-top: 20%;'>Something went Wrong!</h1>";
		}
	}

	public function oppo_details()  {
		try {
			$json = file_get_contents("php://input");
			$data = json_decode($json);
			$opp_id = $data->opportunity_id;
			$user_id = $data->user_id;
			$data = $this->opp_common->fetch_OpportunityDetails($opp_id, $user_id);
            $data['activityList'] = $this->opp_sales->get_activityList();
			$data['user_id'] = $user_id;
			$opp = $data['opportunity_id'];
			$data['canUpdate'] = 1;

			if ($user_id != $data['stage_owner_id']) {
			  $data['canProgress'] = 0;
			} else {
			  $data['canProgress'] = 1;
			}

			if ($user_id != $data['owner_id']) {
			  $data['canClose'] = 0;
			} else {
			  $data['canClose'] = 1;
			}

            // only manager_owner or owner can edit products.
            if (($user_id == $data['owner_id']) || ($user_id == $data['manager_owner_id']))
            {
                 $data['canEditProducts'] = 1;
            }else{
                 $data['canEditProducts'] = 0;
            }

			echo json_encode($data);
		} catch (LConnectApplicationException $e)  {
			echo $this->exceptionThrower($e);
		}
	}

	public function add_opportunity1() {
		$result = array();

		$opportunity_id = '';
		$dt = date('YmdHis');
		$opportunity_id .= $dt;
		$opportunity_id = uniqid($opportunity_id);
		$data['opportunity_id'] = $opportunity_id;

		$data['opportunity_name'] = $_POST['opportunity_name'];
		$data['target'] = $_POST['target'];
		$data['lead_cust_id'] = $_POST['lead_cust_id'];
		$data['opportunity_contact'] = $_POST['opportunity_contact'];
		$data['product_id'] = $_POST['product_id'];
		$data['currency_id'] = $_POST['currency_id'];
		$data['industry_id'] = $_POST['industry_id'];
		$data['location_id'] = $_POST['location_id'];
		$data['opp_remarks'] = $_POST['opp_remarks'];
		$data['manager_id'] = $_POST['manager_id'];
		$data['owner_id'] = $_POST['user_id'];
		$owner_id = $data['owner_id'];

		// get sell type from given data
		$sell_type = $this->opp_common->fetch_sellType($data['product_id'], $data['lead_cust_id'], $data['target'], $data['owner_id']);
		if ($sell_type['sell_type'] == '') {
			$returnArray= array(
				'message' 	=> 'Error fetching sell type..',
				'status'	=> false,
				'qualifier' => false);
			echo json_encode($returnArray);
			return ;
		}
		$data['sell_type'] = $sell_type['sell_type'];

		//$can_create_opp = $this->opp_common->validate_oppo_params($data);
		$can_create_opp = $this->opp_common->isValidOppName($data);
		if ($can_create_opp == 0) {
			$returnArray= array(
				'message' 	=> 'An Opportunity with same name already exists.',
				'status'	=> false,
				'qualifier' => false);
			echo json_encode($returnArray);
			return ;
		}

		$data1 = $this->opp_common->fetch_SalesCycle_firstStage($data);
		if (count($data1) == 0) {
			$returnArray= array(
				'message' 	=> 'No sales cycle found for the selected combination. Please contact admin.',
				'status' 	=> false,
				'qualifier' => false);
			echo json_encode($returnArray);
			return ;
		}
		$data['cycle_id'] = $data1[0]->cycle_id;
		$data['stage_id'] = $data1[0]->stage_id;
		if ($data['stage_id'] == null) {
			$returnArray= array(
				'message' 	=> 'Opportunity could not be created as Sales cycle does not have a proper stage. Contact admin.',
				'status' 	=> false,
				'qualifier' => false);
			echo json_encode($returnArray);
			return ;
		}
		$salesStageAttributes = $this->opp_common->fetch_stageAttributes($data['cycle_id'], $data['stage_id']);
		$allocation_list_exists = false;
		$qualifier_exists = false;
		$allocation_list = '';
		foreach ($salesStageAttributes as $attribute) {
			if ($attribute->attribute_name == 'allocation_matrix') {
				$allocation_list_exists = true;
				$allocation_list = $attribute->attribute_value;
			}
			if ($attribute->attribute_name == 'qualifier') {
				$qualifier_exists = true;
			}
		}
		//if allocation list attribute is enabled for stage -
		if ($allocation_list_exists) {
			$allocation_list_users = explode(':', $allocation_list);
			//if my name is not in list - return an error saying you can't proceed
			if (in_array($owner_id, $allocation_list_users) == false) {
				$returnArray= array(
					'message' 	=> 'You cannot create this opportunity as we could not find your name in allocation list',
					'status' 	=> false,
					'qualifier' => false
				);
				echo json_encode($returnArray);
				return ;
			}
		}
		if ($qualifier_exists) {
			$returnArray = array(
				'message' 	=> 'Answer this qualifier to proceed further.',
				'status' 	=> false,
				'qualifier' => true,
				'opp_data' 	=> $data
			);
			echo json_encode($returnArray);
			return ;
		}
		echo $this->opp_sales->addOpportunity($data, $owner_id);
	}

	public function load_qualifier()	{
		$result = array();
		$data['opportunity_id'] 	= $_POST['opportunity_id'];
		$data['opportunity_name'] 	= $_POST['opportunity_name'];
		$data['target'] 			= $_POST['target'];
		$data['lead_cust_id'] 		= $_POST['lead_cust_id'];
		$data['opportunity_contact']= $_POST['opportunity_contact'];
		$data['product_id'] 		= $_POST['product_id'];
		$data['currency_id'] 		= $_POST['currency_id'];
		$data['industry_id'] 		= $_POST['industry_id'];
		$data['location_id'] 		= $_POST['location_id'];
		$data['opp_remarks'] 		= $_POST['opp_remarks'];
		$data['manager_id'] 		= $_POST['manager_id'];
		$data['owner_id'] 			= $_POST['owner_id'];
		$data['cycle_id'] 			= $_POST['cycle_id'];
		$data['stage_id'] 			= $_POST['stage_id'];
		$data['sell_type'] 			= $_POST['sell_type'];

			$urlParts = explode('/', base_url());
			$clientid = $urlParts[3];
			$this->session->set_userdata('uid', $data['owner_id']);
			$this->session->set_userdata('clientid', $clientid);
			$_SESSION['active_module_name'] = 'sales';

		$this->load->view('admin_questionanswerView', $data);
	}

	public function add_opp_final()	{
	    $json = file_get_contents("php://input");
        $data = json_decode($json,true);
		$owner_id = $data['owner_id'];
		// check if qualifier passed for given opportunity...
		$has_passed_qualifier = $this->opp_common->check_qualifier_passed($data['opportunity_id']);
		if ($has_passed_qualifier == true) {
			echo $this->opp_sales->addOpportunity($data, $owner_id);
			return ;
		} else {
			$returnArray= array(
				'message' 	=> 'Could not create opportunity as you have not passed the qualifier',
				'status' 	=> false,
				'qualifier' => false);
			echo json_encode($returnArray);
		}
	}

	/*-------------------------------- CONTACTS --------------------------------*/
	public function contact_details(){
	  	$userID = trim($_POST['lead_userid']," ");
		$result_set= array();
		$result = array();
		$contact = $this->service->get_contact($userID);
		if(count($contact)>0){
			array_push($result,$contact);
			$result_set['success'] = true;
			$result_set['data'] = $result;
			echo json_encode($result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function add_contact() {
		$result=array();
		$userid = trim($_POST['lead_userid']," ");
		$created_time 	= date('Y-m-d H:i:s');
		$contact_id 	= isset($_POST['contact_id']) 		? $_POST['contact_id'] 		: null;
		$lead_cust_id 	= isset($_POST['lead_cust_id']) 	? $_POST['lead_cust_id'] 	: null;
		$contact_name 	= isset($_POST['contact_name']) 	? $_POST['contact_name'] 	: null;
		$contact_desg 	= isset($_POST['contact_desg']) 	? $_POST['contact_desg'] 	: null;
		$contact_dob 	= isset($_POST['contact_dob']) 		? $_POST['contact_dob'] 	: null;
		$contact_address= isset($_POST['contact_address']) 	? $_POST['contact_address'] : null;
		$contact_type 	= isset($_POST['contact_type']) ? $_POST['contact_type'] 	: null;
		$remarks 		= isset($_POST['remarks']) 		? $_POST['remarks'] 		: null;
		$contact_for 	= isset($_POST['contact_for']) 	? $_POST['contact_for'] 	: null;
		$base64String 	= isset($_POST['contact_image'])	? $_POST['contact_image'] 	: null;

		$contact_number = array();
		
		$contact_number['phone'][0] = isset($_POST['contact_number']) ? $_POST['contact_number'] : "";
		$contact_number['phone'][1] = isset($_POST['contact_number2']) ? $_POST['contact_number2'] : "";

		/*if(isset($_POST['contact_number']) && isset($_POST['contact_number2'])){

			$contact_number['phone'][0] = $_POST['contact_number'];
			$contact_number['phone'][1] = $_POST['contact_number2'];

		}else if(isset($_POST['contact_number'])){
			$contact_number['phone'][0] = $_POST['contact_number'];

		}else if(isset($_POST['contact_number2'])){

			$contact_number['phone'][1] = $_POST['contact_number2'];
		}else{
			$contact = ["",""];
			$contact_number['phone'] = json_encode($contact);
		}
*/
		$contact_email = array();
		$contact_email['email'][0] = isset($_POST['contact_email']) ? $_POST['contact_email'] : "";
		$contact_email['email'][1] = isset($_POST['contact_email2']) ? $_POST['contact_email2'] : "";

		$data = array(
			'contact_id' 	=> $contact_id,
			'lead_cust_id' 	=> $lead_cust_id,
			'contact_name' 	=> $contact_name,
			'contact_desg'	=> $contact_desg,
			'contact_email' => json_encode($contact_email),
			'contact_number'=> json_encode($contact_number),
			'contact_dob' 	=> $contact_dob,
			'contact_address' => $contact_address,
			'contact_type' 	=> $contact_type,
			'contact_created_by' => $userid,
			'contact_created_time' => $created_time,
			'remarks' 		=> $remarks,
			'contact_for' 	=> $contact_for
		);

		if ($base64String != null) {
			$contact_image  = base64_decode($base64String);
			$image_name = $contact_id;
			$filename = $image_name . '.' . 'jpg';
			$filePath = FCPATH."uploads/".$filename;
			file_put_contents($filePath, $contact_image);

			$data['contact_photo'] = $filename;
		}

		$insert = $this->service->insert_contact($data);
		if($insert==TRUE){
			$result['success'] = true;
			echo json_encode($result);
		}else{
			$result['success'] = false;
			echo json_encode($result);
		}
	}


	public function edit_contact(){
		$userid = trim($_POST['lead_userid']," ");
		$result=array();
		$created_time 	= date('Y-m-d H:i:s');
		$contact_id 	= isset($_POST['contact_id']) 		? $_POST['contact_id'] 		: null;
		$lead_cust_id 	= isset($_POST['lead_cust_id']) 	? $_POST['lead_cust_id'] 	: null;
		$contact_name 	= isset($_POST['contact_name']) 	? $_POST['contact_name'] 	: null;
		$contact_desg 	= isset($_POST['contact_desg']) 	? $_POST['contact_desg'] 	: null;
		$contact_dob 	= isset($_POST['contact_dob']) 		? $_POST['contact_dob'] 	: null;
		$contact_address= isset($_POST['contact_address'])	? $_POST['contact_address'] : null;
		$contact_type 	= isset($_POST['contact_type']) 	? $_POST['contact_type'] 	: null;
		$remarks 		= isset($_POST['remarks']) 			? $_POST['remarks'] 		: null;
		$contact_for 	= isset($_POST['contact_for']) 		? $_POST['contact_for'] 	: null;
		$base64String 	= isset($_POST['contact_image'])	? $_POST['contact_image'] 	: null;
		$contact_image  = base64_decode($base64String);

		$contact_number = array();
		$contact_number['phone'][0] = isset($_POST['contact_number']) ? $_POST['contact_number'] : null;
		$contact_number['phone'][1] = isset($_POST['contact_number2']) ? $_POST['contact_number2'] : null;

		$contact_email  = array();
		$contact_email['email'][0] = isset($_POST['contact_email']) ? $_POST['contact_email'] : null;
		$contact_email['email'][1] = isset($_POST['contact_email2']) ? $_POST['contact_email2'] : null;

		$data = array(
			'lead_cust_id' 	=> $lead_cust_id,
			'contact_name' 	=> $contact_name,
			'contact_desg' 	=> $contact_desg,
			'contact_email' => json_encode($contact_email),
			'contact_number'=> json_encode($contact_number),
			'contact_dob' 	=> $contact_dob,
			'contact_address' => $contact_address,
			'contact_type' 	=> $contact_type,
			'contact_updated_by' => $userid,
			'contact_updated_time' => $created_time,
			'remarks' 		=> $remarks,
			'contact_for' 	=> $contact_for
		);

		if ($base64String != null) {
			$contact_image  = base64_decode($base64String);
			$image_name = $contact_id;
			$filename = $image_name . '.' . 'jpg';
			$filePath = FCPATH."uploads/".$filename;
			file_put_contents($filePath, $contact_image);

			$data['contact_photo'] = $filename;
		}

		$update = $this->service->edit_contact($data,$contact_id);
		if($update==TRUE){
			$result['success'] = true;
			echo json_encode($result);
		} else {
			$result['success'] = false;
			echo json_encode($result);
		}
	}

	

	/*------------------------------- REMINDERS --------------------------------*/
	public function phone_get_reminders(){
		$repId= trim($_POST['lead_userid']," ");
		$result_set= array();
		$reminder = $this->service->phone_fetch_reminder($repId);
		if(count($reminder)>0){
			$result_set['success'] = true;
			$result_set['data'] = $reminder;
			echo json_encode( $result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function insert_reminders() {
		
		$result = array();
		$GLOBALS['$log']->debug("Reciving Data");
		$GLOBALS['$log']->debug($_POST);

		$event_name = $_POST['event_name'];
		$type 		= $_POST['type'];
		$lead_id 	= $_POST['lead_id'];
		$leadempid 	= $_POST['leadempid'];
		$rep_id 	= $_POST['rep_id'];
		$created_by = $_POST['rep_id'];

		$addremtime = $_POST['addremtime'];
		$remi_date	= $_POST['remi_date'];
		$rem_time 	= $_POST['rem_time'];
		$meeting_start = $_POST['meeting_start'];
		$duration 	= $_POST['duration'];
		$timestamp 	= $_POST['timestamp'];

		$conntype 	= $_POST['conntype'];
		$lead_reminder_id = $_POST['lead_reminder_id'];
		$status 	= $_POST['status'];
		$remarks 	= $_POST['remarks'];

		//get seconds from cmp_duration
		$seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
		$activity_duration = (int)$seconds->getTimestamp();

		$start = new DateTime($meeting_start);

		$GLOBALS['$log']->debug("add seconds to date object 1(start) and make it date object 2(end)");
		$event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
		$meeting_end = $event_end->format('Y-m-d H:i:s');

		$GLOBALS['$log']->debug("formating data in date form");
		$GLOBALS['$log']->debug($meeting_end);

		$GLOBALS['$log']->debug("Geting managerid");
		$ReportingTo=$this->mytask->fetch_reporting($rep_id);
		$reporting_to=$ReportingTo[0]->report;

		$data1 = array(
			'lead_reminder_id'   => $lead_reminder_id,
			'lead_id'            => $lead_id,
			'rep_id'             => $rep_id,
			'managerid'          => $reporting_to,
			'leadempid'          => $leadempid,
			'remi_date'          => $remi_date,
			'rem_time'           => $rem_time,
			'conntype'           => $conntype,
			'status'             => $status,
			'meeting_start'      => $meeting_start,
			'meeting_end'        => $meeting_end,
			'addremtime'         => $addremtime,
			'timestamp'          => $timestamp,
			'remarks'            => $remarks,
			'event_name'         => $event_name,
			'duration'           => $duration,
			'type'               => $type,
			'created_by'         => $created_by,
			'module_id'          =>'sales'
		);

		$GLOBALS['$log']->debug('Final Array before saving');
		$GLOBALS['$log']->debug($data1);
		$checkId = $this->mytask->checkForRemiderId($lead_reminder_id);
		if($checkId==true){
			$update=$this->mytask->update_reminder($data1,$lead_reminder_id);
			$GLOBALS['$log']->debug('updated');
			if($update==TRUE){
				$result['success'] = true;
				echo json_encode($result);
			}  else {
				$result['success'] = false;
				echo json_encode($result);
			}
		} else{
			$insert = $this->mytask->Phone_insert_reminders($data1);
			$GLOBALS['$log']->debug('Inserted');
			if($insert==TRUE){
				$result['success'] = true;
				echo json_encode($result);
			} else {
				$result['success'] = false;
				echo json_encode($result);
			}
		}
	}

	public function phoneCancelEvent() {
		$result = array();
		$user_id = trim($_POST['rep_id']," ");
		$lead_reminder_id=$_POST['lead_reminder_id'];
		$camp_note1=$_POST['remarks'];
		$data1 = array(
		    'cancel_remarks'=>$camp_note1,
		    'status'=>'cancel',
		    'created_by'=>$user_id
		);
		$cancelStatus=$this->mytask->cancelTask($lead_reminder_id,$data1);
		if ($cancelStatus == true) {
			$result['success'] = true;
			echo json_encode($result);
		} else {
			$result['success'] = false;
			echo json_encode($result);
		}
	}


		public function phoneUpdateReminder($value='') 
		{
			
		$result_set = array();
		$user_id = $_POST['rep_id'];
		$lead_reminder_id = $_POST['lead_reminder_id'];
		$camp_note = $_POST['note'];
		$rating = $_POST['rating'];
		$leademployeeid = $_POST['leademployeeid'];
		$logtype = $_POST['logtype'];
		$cmp_lead = $_POST['leadid'];
		$cmp_phone = $_POST['phone'];
		$cmp_end_time = $_POST['duration'];
		$personId  = $user_id;
		$event_title = $_POST['title'];
		$type = $_POST['type'];
		$timestamp = $_POST['timestamp'];
		$status = "complete";
		$status1 = "scheduled";
		$status2 = "reschedule";

		$completed_start_date = $_POST['newstartdate'];
		$completed_start_time = $_POST['newstarttime'];

		$start_date_time = $_POST['startdate']." ".$_POST['starttime'];
		$start_date_time = date('Y-m-d H:i:s',strtotime($start_date_time));
		$new_start_date_time = $completed_start_date."".$completed_start_time;
		$new_start_date_time = date('Y-m-d H:i:s',strtotime($new_start_date_time));

		$s = new DateTime("1970-01-01 $cmp_end_time", new DateTimeZone('UTC'));
		$seconds = (int)$s->getTimestamp();

		$s1 = new DateTime("1970-01-01 $cmp_end_time", new DateTimeZone('UTC'));
		$seconds1 = (int)$s1->getTimestamp();
					//add seconds to date object 1 and make it date object 2
		$start = new DateTime($start_date_time);
		$start1 = new DateTime($new_start_date_time);
		$end_date_time = $start->add(new DateInterval('PT'.$seconds.'S')); // adds 674165 secs
		$new_end_date_time = $start1->add(new DateInterval('PT'.$seconds1.'S'));
		$end_date_time = $end_date_time->format('Y-m-d H:i:s');
		$new_end_date_time = $new_end_date_time->format('Y-m-d H:i:s');

		$data = array('remarks' => $camp_note);
		$data1 = array(
			'remarks' => $camp_note,
			'status'=> $status,
			'duration'=>$cmp_end_time
		);

		$newData1=array(
			'remarks' => $camp_note,
			'status'=> $status,
			'duration'=>$cmp_end_time
		);

		$data3 = array(
			'remarks' => $camp_note,
			'status'=>$status1,
			'duration'=>$cmp_end_time
		);

		$newData3 = array(
			'remarks' => $camp_note,
			'status'=>$status1,
			'duration'=>$cmp_end_time
		);

		$dt = date('ymdHis');
		$lead_reminder_id_new = '';
		$lead_reminder_id_new .= $dt;
		$lead_reminder_id_new = uniqid($lead_reminder_id_new);


		$data2 = array(
			'log_name'=>$event_title,
			'note' => $camp_note,
			'log_method'=>'auto',
			'call_type'=>$status,
			'reminderid'=>$lead_reminder_id,
			'leademployeeid' => $leademployeeid,
			'logtype' => $logtype,
			'leadid' => $cmp_lead,
			'phone'=>$cmp_phone,
			'starttime'=>$start_date_time,
			'endtime'=>$end_date_time,
			'time'=>$timestamp,
			'rep_id'=>$user_id,
			'rating'=>$rating,
			'type'=>$type
		);

		$newData2 = array(
			'log_name'=>$event_title,
			'note' => $camp_note,
			'log_method'=>'auto',
			'call_type'=>$status,
			'reminderid'=>$lead_reminder_id_new,
			'leademployeeid' => $leademployeeid,
			'logtype' => $logtype,
			'leadid' => $cmp_lead,
			'phone'=>$cmp_phone,
			'starttime'=>$new_start_date_time,
			'endtime'=>$new_end_date_time,
			'time'=>$timestamp,
			'rep_id'=>$user_id,
			'rating'=>$rating,
			'type'=>$type
		);


		$newSchedule=array(
			'lead_reminder_id' => $lead_reminder_id_new,
			'lead_id'   => $cmp_lead,
			'rep_id'    => $user_id,
			'leadempid' => $leademployeeid,
			'remi_date' => $completed_start_date,
			'rem_time'  => $completed_start_time,
			'conntype'  => $logtype,
			'status'    =>  'complete',
			'meeting_start'    => $new_start_date_time,
			'meeting_end'      => $new_end_date_time,
			// 'addremtime'       => $reminder_time,
			'timestamp'        => $timestamp,
			'remarks'          => $camp_note,
			'event_name'       => $event_title,
			'duration'         => $cmp_end_time,
			// 'managerid' =>$reporting_to,
			'type'=>$type,
			'created_by'=>$user_id
		);

		$updateArray=array(
			'lead_reminder_id'=>$lead_reminder_id,
			'status'=>'reschedule'
		);

		if($new_start_date_time!=$start_date_time){
			$updateOldEventReschedule = $this->mytask->updateOldEventReschedule($updateArray,$lead_reminder_id);
			$insertRescheduledData = $this->mytask->insert_reminder($newSchedule);
			if($insertRescheduledData==1){
				$insert = $this->mytask->insert_repcomplete($newData2);
				// $update1=$this->mytask->insert_repinfo($user_id,$cmp_lead);
				echo json_encode($insertRescheduledData);
			}
			//   if the manager has got the sales module, he will perform the same from there
		} else {
			if($rating==0)  {
				$update = $this->mytask->update_reminder($data3,$lead_reminder_id);
				echo json_encode($update);
			} else {
				$update = $this->mytask->update_remindercomplete($data1,$lead_reminder_id,$user_id);
				if($update==1){
					$insert = $this->mytask->insert_repcomplete($data2);
					// $update1=$this->mytask->insert_repinfo($user_id,$cmp_lead);
					echo json_encode($update);
				}
				//   if the manager has got the sales module, he will perform the same from there
			}
		}
		}

	/*----------------------------- ACTIVITY LOG -------------------------------*/
	public function get_rep_log() {
		$result_set= array();
		$repId= trim($_POST['lead_userid']," ");
		$repLogData=$this->mytask->get_rep_log($repId);
		if(count($repLogData)>0){
			$result_set['success'] = true;
			$result_set['data'] = $repLogData;
			echo json_encode( $result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function insert_rep_log(){
		$result = array();
		$GLOBALS['$log']->debug('Data:RepLog');
		$GLOBALS['$log']->debug($_POST);
		$rep_id		= trim($_POST['rep_id']," ");
		$note 		= isset($_POST['note']) 	  ? $_POST['note']    	: null;
		$time  		= isset($_POST['timestamp'])  ? $_POST['timestamp'] : null;
		$rating 	= isset($_POST['rating']) 	  ? $_POST['rating']    : null;
		$log_name 	= isset($_POST['log_name'])   ? $_POST['log_name']  : null;
		$log_method = isset($_POST['log_method']) ? $_POST['log_method']: 'auto';
		$reminder_id= isset($_POST['reminder_id']) ? $_POST['reminder_id']: null;
        $recording= isset($_POST['recording']) ? $_POST['recording']: null;

		$leadid 	= $_POST['leadid'];
		$type 		= $_POST['type'];
		$phone 		= isset($_POST['phone'])? $_POST['phone'] : '';
		$logtype 	= $_POST['logtype'];
		$event_start = $_POST['starttime'];
		$duration 	= $_POST['duration'];


		$event_start_date = date('Y-m-d', strtotime($event_start));
		$event_start_time = date('H:i', strtotime($event_start));
		//get seconds from cmp_duration
		$seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
		$activity_duration = (int)$seconds->getTimestamp();
		//add seconds to date object 1(start) and make it date object 2(end)
		$start = new DateTime($event_start);
		$event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
		$event_end = $event_end->format('Y-m-d H:i:s');
		$data1 = array(
			'rep_id'    => $rep_id,
			'leadid'    => $leadid,
			'phone'     => $phone,
			'logtype'   => $logtype,
			'rating'	=> $rating,
			'call_type' => 'complete',
			'note'      => $note,
			'time'      => $time,
			'starttime' => $event_start,
			'endtime'   => $event_end,
			'type' 		=> $type,
			'module_id'	=> 'sales',
			'log_name'	=> $log_name,
			'log_method'=> $log_method,
			'reminderid'=> $reminder_id,
			'duration'  => $duration
		);



		if(isset($_POST['leademployeeid'])){
			$leademployeeid=$_POST['leademployeeid'];
			$data1['leademployeeid'] = $leademployeeid;
		}

		$recordingStr = '';
		$arr=explode(" ",$recording);
		foreach ($arr as $key => $value) {
			$recordingStr .= $value."";
		}
        // saving recording
        if($recording!=null) {
        	//Checking Base64
        		if($logtype =='CALL594ce66d07b45' || $logtype == 'CALL594ce66d07b46' || $logtype =='ME594ce66d07b9fd4'){
        				$GLOBALS['$log']->debug('Recording Data');
        				$recordingdata  = base64_decode($recording);
        				$GLOBALS['$log']->debug('After decoding');
        				$GLOBALS['$log']->debug($recordingdata);
        				$GLOBALS['$log']->debug('File Name');
						$filename = uniqid(). '.' . 'mp3';
						$GLOBALS['$log']->debug($filename);
					    $filePath = FCPATH."uploads/".$filename;
					    $GLOBALS['$log']->debug('File Full Path');
						///$filePath = $filename;
						file_put_contents($filePath, $recordingdata);
						$GLOBALS['$log']->debug($filePath);
						$data1['path'] = $filename;
        		}
        		else{
        			$GLOBALS['$log']->debug('SMS data');
        			$GLOBALS['$log']->debug($recording);
        			$data1['path'] = $recording;
        		}
        }
		if ($type == 'lead') {
			$update = $this->service->update_lead_info_condition(array('lead_status'=>1), array('lead_id'=>$leadid, 'lead_status'=>0));
			$inProgressRow = $this->service->get_lead_cust_user_map($leadid, 'in progress');
			if ($inProgressRow == false) {
				$data4 = array(
					'mapping_id' 	=> uniqid(),
					'lead_cust_id' 	=> $leadid,
					'type'			=> 'lead',
					'action'		=> "in progress",
					'module'		=> "sales",
					'from_user_id'	=> $rep_id,
					'to_user_id'	=> $rep_id,
					'state'			=> 1,
					'timestamp'		=> date('Y-m-d H:i:s'),
					'remarks'		=> $note
				);
				$insert = $this->service->insert_lead_cust_trans($data4);
			}
		}
		$GLOBALS['$log']->debug('Final Array');
		$GLOBALS['$log']->debug($data1);
		$insert = $this->mytask->insert_phone_replog($data1);
		if($insert==TRUE){
			$GLOBALS['$log']->debug("Inserted");
			$result['success'] = true;
			echo json_encode($result);
		}else{
			$GLOBALS['$log']->debug("NOT Inserted");
			$result['success'] = false;
			echo json_encode($result);
		}
	}

	/*----------------------- MY TASK FUNCTIONS BY SURESH ----------------------*/

	public function Phone_get_mytask() {
		// open task list
		$result_set = array();
		$user_id=$_POST['user_id'];
		$data = $this->mytask->fetch_mytask($user_id, '');
		$data1= $this->mytask->fetch_mytask1($user_id, '');
		$taskArray= array_merge($data, $data1);
		if(count($taskArray)>0){
			$result_set['success'] = true;
			$result_set['data'] = $taskArray;
			echo json_encode( $result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function Completed_tasklist(){
		$result_set = array();
		$user_id=$_POST['user_id'];

		if(isset($_POST['from_date']) && isset($_POST['to_date'])){
			$from_date = $_POST['from_date'];
			$to_date = $_POST['to_date'];
			$data = $this->service->fetch_completetask_between_date($user_id,$from_date,$to_date);
			$data1= $this->service->fetch_mytaskCompleted1_between_date($user_id,$from_date,$to_date);
			$dataArray1=array_merge($data,$data1);
			$data3 = $this->service->fetch_mytaskCompletedReplog_between_date($user_id,$from_date,$to_date);
			$data4=array_merge($dataArray1,$data3);
			$data5=$this->service->fetch_mytaskCompletedReplogInternal_between_date($user_id,$from_date,$to_date);
			$dataArray = array_merge($data4,$data5);
		}
		else{

			$data = $this->service->fetch_completetask($user_id);
			$data1= $this->service->fetch_mytaskCompleted1($user_id);
			$dataArray1=array_merge($data,$data1);
			$data3 = $this->service->fetch_mytaskCompletedReplog($user_id);
			$data4=array_merge($dataArray1,$data3);
			$data5=$this->service->fetch_mytaskCompletedReplogInternal($user_id);
			$dataArray = array_merge($data4,$data5);
		}

		if(count($dataArray)>0){
			$result_set['success'] = true;
			$result_set['data'] = $dataArray;
			echo json_encode( $result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function Assigned_for() {
		$result_set = array();
		$lead_id=$_POST['lead_id'];
		$type = $_POST['type'];
		$assignedForName = $this->mytask->fetchAssignedForName($lead_id,$type);
		if(count($assignedForName)>0){
			$result_set['success'] = true;
			$result_set['data'] = $assignedForName;
			echo json_encode( $result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function phoneGetLeads() {
		$result_set = array();
		$user_id=$_POST['user_id'];
		$leadData = $this->mytask->getLeadsForPhone($user_id);
		if(count($leadData)>0){
			$result_set['success'] = true;
			$result_set['data'] = $leadData;
			echo json_encode( $result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function phoneGetOpportunities(){
		$result_set = array();
		$user_id=$_POST['user_id'];
		$opportunitiesData = $this->mytask->getOpportunitiesForPhone($user_id);

		if(count($opportunitiesData)>0){
			$result_set['success'] = true;
			$result_set['data'] = $opportunitiesData;
			echo json_encode( $result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function phoneGetCustomer() {
		$result_set = array();
		$user_id=$_POST['user_id'];
		$customerData1 = $this->mytask->getCustomerForPhone($user_id);
		// $customerData2 = $this->mytask->getCustomerFromOpp($user_id);
		// $customerData = array_merge($customerData1,$customerData2);
		if(count($customerData1)>0){
			$result_set['success'] = true;
			$result_set['data'] = $customerData1;
			echo json_encode( $result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function getContacts() {
		$result_set = array();
		$leadid=$_POST['lead_id'];
		$type = $_POST['type'];
		$contactsData = $this->mytask->getContactsForPhone($leadid,$type);

		if(count($contactsData)>0){
			$result_set['success'] = true;
			$result_set['data'] = $contactsData;
			echo json_encode( $result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function User_details(){
		$result_set = array();
		$repId=$_POST['user_id'];
		$userName = $this->mytask->fetch_userName($repId);
		if(count($userName)>0){
			$result_set['success'] = true;
			$result_set['data'] = $userName;
			echo json_encode( $result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}



	public function testScript()	{
		$data = $this->service->call_proc('1708030427505982a646bcdbc');
		echo json_encode($data);
	}

	public function get_fulluser_data() {
		$result_set = array();
		$userid=$_POST['user_id'];
		$get_fulluser_data = $this->service->get_fulluser_data($userid);
		if(count($get_fulluser_data)>0){
			$result_set['success'] = true;
			$result_set['data'] = $get_fulluser_data;
			echo json_encode($result_set);
		} else {
			$result_set['success'] = false;
			echo json_encode($result_set);
		}
	}

	public function phoneGetNotifications() {
		// Fetching Notifications For Mobile.
		$result_set = array();
		$userid = $_POST['user_id'];
		$getNotification = $this->service->fetchMobileNotification($userid);
			if(count($getNotification) > 0){
				$result_set['success'] = true;
				$result_set['data']    = $getNotification;
				echo json_encode($result_set);
			}
			else{
				$result_set['success'] = false;
				echo json_encode($result_set);
			}
	}

	public function insertPhoneNotification() {
		# Inserting Notifications from the phone.
		$result_set = array();
		$dt = date('ymdHis');
		$fromId = $_POST['user_id'];
		$toId = $_POST['to_user_id'];
		$notificationShortText = $_POST['notificationShortText'];
		$notificationText = $_POST['notificationText'];
		$actionDetails = $_POST['action_details'];
		$readState = $_POST['read_state'];
		$remarks = $_POST['remarks'];
		$showStatus = $_POST['show_status'];
		$taskId = $_POST['leadCustId'];
		$action = $_POST['action'];

		// Genertating Unique notification id.
        $notify_id= uniqid($dt);
        // Notification Array --!!!!!!
			$notificationDataArray= array(
			'notificationID' =>$notify_id,
			'notificationShortText'=>$notificationShortText,
			'notificationText' =>$notificationText,
			'from_user'=>$fromId,
			'to_user'=>$toId,
			'action_details'=>$actionDetails,
			'notificationTimestamp'=>$dt,
			'read_state'=>$readState,
			'remarks'=>$remarks,
			'show_status' =>$showStatus,
			'task_id' =>$taskId,
			'action'=>$action
			);

			$notificationsInsert = $this->service->insertNotificationData($notificationDataArray);

			if(count($notificationsInsert) > 0){
				$result_set['success'] = true;
				$result_set['data']    = $notificationsInsert;
				echo json_encode($result_set);
			}
			else{
				$result_set['success'] = false;
				echo json_encode($result_set);
			}

	}

	// Instance API

	public function getAppStatus()
	{
		$result_set = array();
		$instanceId = $_POST['instanceId'];
		$userId = $_POST['user_id'];
		$instanceResult = $this->service->checkInstance($instanceId,$userId);
			if(count($instanceResult) > 0){
				$result_set['success'] = true;
				$result_set['data']    = $instanceResult;
				echo json_encode($result_set);
			}
			else{
				$result_set['success'] = false;
				echo json_encode($result_set);
			}
	}

    // Attendance module API
    public function get_attendance()
	{
		$result_response = array();
		$instanceId = $_POST['userId'];
		$inoutidentifier = $_POST['inOutIdentifier'];
		$inoutdatetime = $_POST['inOutDatetime'];
		$inoutdate = $_POST['inOutDate'];
        $result_set=array(
                    'userId'=>$instanceId,
                    'inoutidentifier'=>$inoutidentifier,
                    'inoutdate'=>$inoutdate,
                    'inoutdatetime'=>$inoutdatetime
        );
        $result=$this->service->insertPunchDetails($result_set);

        if($result){
				$result_response['success'] = true;
                $result_response['data']=$result;
				echo json_encode($result_response);
			}
		else{
				$result_set['success'] = false;
				echo json_encode($result_response);
		}


	}

	// Check Lead Number and Contact Number.
	public function check_contact()
	{
		$result = array();
		$contactNumber = $_POST['contactNumber'];
		$checkDuplicate = $this->service->checkContacts($contactNumber);
		if($checkDuplicate['result'] == 1){
		   $result['success'] = false;
		   $result['data'] = $checkDuplicate['exist'];
	   } else{
		   $result['success'] = true;
		   $result['data'] = $checkDuplicate['exist'];
	   }
	   echo json_encode($result);

	}

	public function check_state_lead()
    {
		$GLOBALS['$logger']->info('contacts for lead customer function called');
		$json = file_get_contents("php://input");
		$data = json_decode($json);

		$result = array();

		$userid = $_POST['user_id'];	

		$loss_type          = isset($_POST['loss_type']) ? $_POST['loss_type']: 'Reopened';
		$remarks            = $_POST['remarks'];
		$reopen             = $_POST['reopen'];

		$cancel_pending     = $_POST['cancel_pending'];
		$leadid             = $_POST['lead_id'];

        // Fetching Lead Name & Contact Person Data.

        $lead_data      = $this->lead->get_lead_data($leadid);
        $name           = $lead_data['lead_data'][0]->lead_name;
        $contact_id     = isset($_POST['contact_id']) ? $_POST['contact_id']: '';

        $mapping_id     = uniqid(rand(),TRUE);

        // Log Data.

            $log_data = array(
                'mapping_id'    => $mapping_id,
                'lead_cust_id'  => $leadid,
                'type'          => 'lead',
                'action'        => "closed",
                'module'        => "sales",
                'from_user_id'  => $userid,
                'to_user_id'    => $userid,
                'state'         => 1,
                'timestamp'     => date('ymdHis'),
                'remarks'       => $remarks
            );

            // Notification Data.

                $notify_id= uniqid(date('ymdHis'));

                if($loss_type == 'Reopened') 
                {
                    $notificationShortText = $name.' lead reopened by '.$userid;
                }
                else
                {
                    $notificationShortText = $name.' lead closed by force close';
                }

                $notifications = array(
                            'notificationID' =>$notify_id,
                            'notificationShortText'=> 'Lead '.$loss_type,
                            'notificationText' =>$notificationShortText,
                            'from_user'=>$userid,
                            'to_user'=>$userid,
                            'action_details'=>'lead',
                            'notificationTimestamp'=>date('ymdHis'),
                            'read_state'=>0,
                            'remarks'=>$remarks,
                        );

                    // Check opportunity tasks, 

                    $opportunity_tasks = $this->lead->check_opportunity_tasks($leadid);

                // If Loss Type is temporary . 

                if ($loss_type == 'temporary_loss') 
                {

                    $approach_date   = isset($_POST['connect_date']) ? $_POST['connect_date']: '';
                    // if contact_type for activity is not mentioned , add call.
                    $future_activity = isset($_POST['connect_type']) ? $_POST['connect_type']: '';


                    $duration        = isset($_POST['duration']) ? $_POST['duration']: '';
                    $alert_before    = isset($_POST['alert_before']) ? $_POST['alert_before']: '';
                    $task_title      = isset($_POST['task_title']) ? $_POST['task_title'] : "Reconnect with ".$name;

                    $newDate = date("Y-m-d H:i:s");
                    $event_start_date = date('Y-m-d', strtotime($approach_date));
                    $event_start_time = date('H:i:s', strtotime($approach_date));
                    $seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
                    $activity_duration = (int)$seconds->getTimestamp();
                    $start = new DateTime($approach_date);
                    $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
                    $event_end = $event_end->format('Y-m-d H:i:s');
                    $dt = date('ymdHis');
                    $lead_reminder_id = '';
                    $lead_reminder_id .= $dt;
                    $lead_reminder_id = uniqid($lead_reminder_id);


                    $scheduled_task = array(
                        'lead_reminder_id' => $lead_reminder_id,
                        'lead_id'   => $leadid,
                        'leadempid' => $contact_id,
                        'remi_date' => $event_start_date,
                        'rem_time'  => $event_start_time,
                        'conntype'  => $future_activity,
                        'status'    => "scheduled",
                        'meeting_start' => $event_start_date,
                        'meeting_end'   => $event_end,
                        'addremtime'    => $alert_before,
                        'timestamp'     => $newDate,
                        'remarks'       => $remarks,
                        'event_name'    => $task_title,
                        'duration'      => $duration,
                        'type'          => "lead",
                        'created_by'    => $userid,
                        'module_id'     => 'sales',
                        'rep_id'    => $userid
                    ); 

                    $temporary_loss = array(
                        'lead_status' => 3,
                        'lead_closed_reason' =>$loss_type,
                        'lead_approach_date' =>$approach_date,
                    );
                    	

                    // Update data and insert.
                    // if cancel pending is true, cancel future activity

                    $update_temporary_data = $this->lead->temporary_close($leadid,$temporary_loss);

                    $insert_log_data = $this->lead->insert_transaction($log_data);

                    // Notification data inserting.

                    $insert_notifications = $this->lead->notifications($notifications);

                    //Check opportunity task exits. 




                    if($cancel_pending == 'true')
                    {
                        $cancel_activites = $this->lead->lead_activities($leadid); 
                        // If exists,

                        if (!empty($opportunity_tasks)) 
                        {
                            foreach ($opportunity_tasks as $key => $value) 
                            {
                               $cancel_activites = $this->lead->lead_activities($value->opportunity_id); 
                            }

                        }   
                    }

                    // Schedule an event for approach date.

                    $insert_task = $this->lead->insert_mytask($scheduled_task);

					if($insert_log_data)
					{
						$result_response['success'] = true;
						$result_response['message']='lead changed to temporary loss.';
						echo json_encode($result_response);
					}
					else
					{

						$result_set['success'] = false;
						$result_response['message']='lead not changed to temporary loss.';
						echo json_encode($result_response);
					}

                }

                // If loss type is permanent

                if ($loss_type == 'permanent_loss') 
                {
                    // Update lead data and insert log.

                    $permanent_loss_data = array(
                    'lead_status' => 4,
                    'lead_closed_reason' => $loss_type,
                    );

                    $update_permanent_data = $this->lead->permanent_close($leadid,$permanent_loss_data);

                    $insert_log_data = $this->lead->insert_transaction($log_data);

                    // Notification data inserting.

                    $insert_notifications = $this->lead->notifications($notifications);

                    if($cancel_pending == 'true')
                    {
                        $cancel_activites = $this->lead->lead_activities($leadid);   

                        // If exists,

                        if (!empty($opportunity_tasks)) 
                        {
                            foreach ($opportunity_tasks as $key => $value) 
                            {
                               $cancel_activites = $this->lead->lead_activities($value->opportunity_id); 
                            }

                        }  
                    }

					if($insert_log_data){
						$result_response['success'] = true;
						$result_response['message']='lead changed to permanent loss.';
						echo json_encode($result_response);
					}
					else{
						
						$result_set['success'] = false;
						$result_response['message']='lead not changed to permanent loss.';
						echo json_encode($result_response);
					}
                }

                // reopen lead . 
                if ($reopen == "true") 
                {
                	
                   $reopen_data =array(
                                        'lead_id'           =>$leadid,
                                        'lead_closed_reason'=>NULL,
                                        'lead_status'       =>1
                                        );

                    $log_data   = array(
                                            'lead_cust_id'=>$leadid,
                                            'state'=>0
                                            ); 

                    $log_trans_data=array(
                                    'lead_cust_id'=>$leadid,
                                    'state'=>1,
                                    'action'=>'reopened',
                                    'from_user_id'=>$userid,
                                    'to_user_id'=>$userid,                                
                                    'type'=>'lead',
                                    'mapping_id'=>$mapping_id,
                                    'module'=>'sales',
                                    'timestamp'=>date('Y-m-d H:i:s')
                                    );

                    $update_reopen_data = $this->lead->re_open_data($leadid,$reopen_data);

                    $update_log_data = $this->lead->update_reopen_log($leadid,$log_data);

                    $insert_log_data = $this->lead->insert_transaction($log_trans_data);

                    // Notification data inserting.

                    $insert_notifications = $this->lead->notifications($notifications);


					if($insert_log_data){
						$result_response['success'] = true;
						$result_response['message']='lead reopened';
						echo json_encode($result_response);
					}
					else{
						
						$result_set['success'] = false;
						$result_response['message']='lead not reopened.';
						echo json_encode($result_response);
					}
                }
    }


    public function check_state_opportunity()	{
		$result = array();
        $login_user1                    = $_POST['user_id'];
		$data['opportunity_id'] 	    = $_POST['opportunity_id'];
		$data['lead_cust_id'] 	        = $_POST['lead_cust_id'];
		$data['lossType'] 			    = $_POST['lossType'];
		$data['remarks'] 		        = $_POST['remarks'];
		$data['lead_cust_name']         = isset($_POST['lead_cust_name']) ? $_POST['lead_cust_name'] : null;
        $data['opportunity_name'] 		= isset($_POST['opportunity_name']) ? $_POST['opportunity_name'] : null;
		$data['date'] 		            = isset($_POST['date']) ? $_POST['date'] : null;
		$data['title'] 	    	        = isset($_POST['title']) ? $_POST['title'] : null;
		$data['futureActivity'] 		= isset($_POST['futureActivity']) ? $_POST['futureActivity'] : null;
		$data['activityDuration'] 		= isset($_POST['activityDuration']) ? $_POST['activityDuration'] : null;
		$data['alertBefore'] 			= isset($_POST['alertBefore']) ? $_POST['alertBefore'] : null;
		$data['contactType'] 			= isset($_POST['contactType']) ? $_POST['contactType'] : null;

        $extradata=array();
        $updateoppdetails=array();
        $login_user=$login_user1;
        $opp_data= $this->opp_sales->stage_owner($data['opportunity_id']); // gets all data from opportunity details
        $opportunity_stage=$opp_data[0]->opportunity_stage;
        $cycle_id=$opp_data[0]->cycle_id;
        $opp_usermap_data = array(
              'mapping_id' => uniqid(rand()),
              'opportunity_id'=> $data['opportunity_id'],
              'lead_cust_id' => $data['lead_cust_id'],
              'from_user_id'=> $login_user,
              'to_user_id'=> $login_user,
              'cycle_id' => $cycle_id,
              'stage_id' => $opportunity_stage,
              'module' => 'sales',
              'action'=>	$data['lossType'] ,
              'timestamp'=> date('Y-m-d H:i:s'),
              'sell_type' => 'new_sell',
              'state' => 1,
              'remarks' => $data['remarks']
        );

      $stage_owner_id=$opp_data[0]->stage_owner_id;
      $owner_id=$opp_data[0]->owner_id;
      $manager_owner_id=$opp_data[0]->manager_owner_id;
      $stage_manager_owner_id=$opp_data[0]->stage_manager_owner_id;
      $extradata['manager_owner_id']=$manager_owner_id;
      $extradata['stage_manager_owner_id']=$stage_manager_owner_id;

      $log_attr_data = array(
              'mapping_id' => $opp_usermap_data['mapping_id'],
              'opportunity_id'=> $opp_usermap_data['opportunity_id'],
              'stage_id'=> $opp_data[0]->opportunity_stage,
              'user_id'=> $login_user,
              'opp_close_date'=> $opp_data[0]->opportunity_date,
              'opp_numbers'=> $stage_manager_owner_id,
              'oppo_rate' => $opp_data[0]->opportunity_rate,
              'oppo_score' => $opp_data[0]->opportunity_score,
              'oppo_customer_code' => $opp_data[0]->opportunity_customer_code,
              'oppo_priority' => $opp_data[0]->opportunity_priority,
              'timestamp'=> date('Y-m-d H:i:s'),
              'remarks'=>"changed state to ".$opp_usermap_data['action']." from ".$opp_data[0]->closed_reason
      );
      $lead_closed_status = $this->opp_common->lead_closed_status($opp_usermap_data['lead_cust_id']);

      $extradata['leadclosedstatus']=$lead_closed_status;
      $extradata['lead_cust_name']=$data['lead_cust_name'];
      $extradata['remarks']=$data['remarks'];
      $extradata['opportunity_name']=$data['opportunity_name'];

      if($opp_usermap_data['action']=='reopen')
      {
        	$updateoppdetails['closed_reason'] = NULL;
            $updateoppdetails['closed_status'] = '0';
            $updateoppdetails['opportunity_approach_date'] = NULL;

      }else{
              $updateoppdetails['closed_reason'] = $opp_usermap_data['action'];
              $updateoppdetails['closed_status'] = '100';
              $updateoppdetails['opportunity_approach_date'] = $data['date'];
          if ($opp_usermap_data['action']=="temporary_loss") {
                    $extradata['title']=$data['title'];
                    $extradata['futureActivity']=$data['futureActivity'];
                    $extradata['activityDuration']=$data['activityDuration'];
                    $extradata['alertBefore']=$data['alertBefore'] ;
                    $extradata['contactType']=$data['contactType'];
                    $extradata['approachdate']=$data['date'];
                    $opp_usermap_data['action'] = 'temporary loss';
          } else if ($opp_usermap_data['action']=="permanent_loss") {
                    $opp_usermap_data['action'] = 'permanent loss';
          }
      }

      $getusername=$this->opp_mgr->getusernamae($stage_owner_id);
      $extradata['stage_owner_id'] = $stage_owner_id;
      $extradata['stage_owner_name'] = $getusername;
      $extradata['manager_owner_id'] = $manager_owner_id;

      $insert = $this->opp_sales->changestate($opp_usermap_data,$updateoppdetails,$extradata);
    


      if(!$insert)
      {
          $result['success'] = false;
		  $result['data'] = 'Opportunity could not be reopened,Please try again';
		  echo json_encode($result);
      }else if('$insert'=='2'){
          $result['success'] = false;
		  $result['data'] = 'Cannot change state of opportunity,since the Lead is in Permanent Loss State';
          echo json_encode($result);
      }else{
          $result['success'] = true;
		  $result['data'] = 'Opportunity State Changed successfully';
          echo json_encode($result);
      }



    }


	public function getAllMailApi() {
		//This api is used to fetch all the associated lead, customer and opportunity based on the mail-id entered by the user.
			$json = file_get_contents("php://input");
			$data = json_decode($json);
			$get_matchdata = $this->personalmail->fetchAllMail();
			$match_email_details = array();
			$matched_arr = array();
			//var_dump($get_matchdata);exit();
			foreach ($get_matchdata as $key => $value) {
					$get_matchdata1 = $this->personalmail->get_matchdata('name',$value->Mail_ID,'conflict');
					$match_email_details['matchdata'] = $get_matchdata1;  
					$match_email_details['Mail_ID'] = $value->Mail_ID;  
					$match_email_details['Name'] = $value->Name;
					array_push($matched_arr, $match_email_details);                    
			}

			if($matched_arr>0){
				$result['success'] = true;
				$result['data'] = $matched_arr;
				echo json_encode($result);
			} else{
				$result['success'] = false;
				echo json_encode($result);
			}
			
	} 

	public function getAllContacts()
	{
		$contactsArray = $this->service->getContactsforMobile();
		if($contactsArray>0){
				$result['success'] = true;
				$result['data'] = $contactsArray;
				echo json_encode($result);
			} else{
				$result['success'] = false;
				echo json_encode($result);
			}
	}
    public function MatchwithUnassocConflictEmails()
	{		
		$resultData = array();
		$lead_cust_id=$_POST['lead_cust_id'];
		$hidmsgid=$_POST['messageid'];
		$hidemail=$_POST['email'];
		$type=$_POST['type'];
		$pagetype=$_POST['pagetype'];
		$associated_id =$_POST['associated_id']; 
		$get_matchdata = $this->personalmail->remove_unassoc($lead_cust_id,$hidmsgid,$hidemail,$type,$pagetype,$associated_id);
		if($get_matchdata == true){
			$resultData['success'] = 'true';
			$resultData['data'] = $get_matchdata;
		}else{
			$resultData['success'] = 'false';
			$resultData['data'] = $get_matchdata;
		}
		echo json_encode($resultData);  
		
	}

	// Added For Lead Injection From Website

	public function add_lead_website(){
		
		$result = array();
		// Creating Lead Id,
		$dt = date('Y-m-d H:i:s');
		$dt1 = date('ymdHis');	
		$json = file_get_contents("php://input");
        $data = json_decode($json);	
		
		$leadname 		= $data->lead_name;
		$lead =strtoupper(substr($leadname,0,2));
		$leadid= $lead.uniqid($dt1);
		$leadwebsite 	= $data->lead_website;
		$coordinate		= null;
		$ofcaddress 	= null;
		$city 			= null;
		$state 		 	= null;
		$leadcountry 	= null;
		$zipcode  		= null;
		$splcomments 	= null;		
		$industry 		= null;
		$bussiness		= null;
		$lead_status 	= null;

		$GLOBALS['$log']->debug('Data:add_lead');
		
		// Fetching Superior Manager, 

		$superiorManager = $this->service->fetchSuperiorManager();

		$lead_phone = array();
		$lead_email = array();

		$lead_phone['phone'][0] = $data->lead_phones;		
		$data1['lead_number'] = json_encode($lead_phone);

		$lead_email['email'][0] = $data->lead_mail;
		$data1['lead_email'] = json_encode($lead_email);
		$mapping_id = uniqid(rand(),TRUE); 

		$data1 = array(
			'lead_id' 		=> $leadid,
			'lead_name' 	=> $leadname,
			'lead_website' 	=> $leadwebsite,
			'lead_location_coord' => $coordinate,
			'lead_address'	=> $ofcaddress,
			'lead_city' 	=> $city,
			'lead_email'    => json_encode($lead_email),
			'lead_number'   => json_encode($lead_phone),
			'lead_state'	=> $state,
			'lead_country'	=> $leadcountry,
			'lead_zip'		=> $zipcode,
			'lead_remarks'	=> $splcomments,
			'lead_source'	=> 'OT170505081815590c3547b487u',
			'lead_industry'	=> $industry,
			'lead_business_loc'	=> $bussiness,
			'lead_rep_owner'	=> NULL,
			'lead_rep_status'	=> '0',
			'lead_manager_owner' => $superiorManager[0]->superior_id,
			'lead_manager_status'=> "2",
			'lead_created_by'	=> $superiorManager[0]->superior_id,
			'lead_created_time'	=> $dt,
			'lead_updated_by' 	=> $superiorManager[0]->superior_id,
			'lead_status'	 	=> '0'			
		 );

			

		$data3 = array(
			'lead_cust_id' =>$leadid,
			'type'=>'lead',
			'state' =>'1',
			'action'=>"created",
			'module'=>"manager",
			'from_user_id'=>$superiorManager[0]->superior_id,
			'to_user_id'=>$superiorManager[0]->superior_id,
			'timestamp'=>$dt,
			'mapping_id'=>$mapping_id
		);

		$contact_email['email'][0] = $data->lead_mail;	

		$contactArray = array(
					'contact_id' => uniqid($dt),
					'lead_cust_id' =>$leadid,
					'contact_name'=> $leadname,
					'contact_desg' => NULL,
					'contact_email' =>json_encode($contact_email),
					'contact_number'=> json_encode($lead_phone),        
					'contact_type'=>NULL,
					'contact_created_time'=>$dt,
					'contact_created_by'=>$superiorManager[0]->superior_id,
					'contact_for'=>'lead',
					'contact_address'=>NULL
		);


		$notificationData= array(
			'notificationID' =>uniqid($dt1),
			'notificationShortText'=>'Lead Created From Website',
			'notificationText' => $leadname  ." lead has created from the Website",
			'from_user'=>$superiorManager[0]->superior_id,
			'to_user'=>$superiorManager[0]->superior_id,
			'action_details'=>'lead',
			'notificationTimestamp'=>$dt,
			'read_state'=>0,
			'task_id'=>$leadid,
		);
		

		$GLOBALS['$log']->debug('Lead Data');
		$GLOBALS['$log']->debug($data1);

		$insert = $this->service->insert_lead($data1);
		$GLOBALS['$log']->debug('Inserted lead');

		$insert2 = $this->service->insert_lead_cust_trans($data3);
		$GLOBALS['$log']->debug('Inserted to Lead Cust UserMap');
		// Inserting Notification
		$insertContact = $this->service->insert_contact($contactArray);
		$GLOBALS['$log']->debug('Inserted to contact to contact_details');
		$GLOBALS['$log']->debug('Notification Data');
		$GLOBALS['$log']->debug($notificationData);
		$insertNotification = $this->service->notifications($notificationData);
		$GLOBALS['$log']->debug('Notification Inserted');
		$GLOBALS['$log']->debug('Inserted transaction rows');

		if($insert==TRUE && $insert2 ==TRUE && $insertContact== TRUE &&$insertNotification ==TRUE)
		{
			$result['success'] = true;
			$result['message'] = 'Lead is successfully injected to application';
			echo json_encode($result);
		}
		else 
		{
			$result['success'] = false;
			echo json_encode($result);
		}
	}
    public function giveresponse($success, $message){
        $result=array();
        $result['success'] = $success;
		$result['message'] = $message;
        echo json_encode($result);
    }

    public function mobile_accept_opportunity(){
        $result = array();

        $given_data = array();
        $given_data['userid'] = $_POST['user_id']; ;
        $given_data['opp_id'] = $_POST['opportuniy_id'];
        $given_data['lead_cust_id'] = $_POST['lead_cust_id'];
        $given_data['sell_type'] = $_POST['sell_type'];
        $given_data['opportunity_stage'] = $_POST['opportunity_stage'];
        $given_data['cycle_id']= $_POST['cycle_id'];
        $given_data['owner_name']= $_POST['owner_name'];
        $given_data['mapping_id'] = uniqid(rand());


        //check if user is both owner and stage owner
        $checkuserstatus=$this->opp_sales->check_if_user_is_oppown_n_stgown($given_data['userid'],$given_data['opp_id']);
        if($checkuserstatus == 1){
             $returndata=$this->accept_opp_ownership($given_data);
             if($returndata == 1){
                $returndata1=$this->accept_opp_stage($given_data);
                if($returndata1 == 1){
                    $this->giveresponse(true, 'Opportunity Accepted');
                }else{
                    $this->giveresponse(false, 'Acceptance Failed');
                }
             }


        }else{
            $opp_status= $checkuserstatus[0]->action;

            if($opp_status=='ownership assigned'){
           	    $returndata=$this->accept_opp_ownership($given_data);
                if($returndata == 1){
                    $this->giveresponse(true, 'Ownership Accepted');
                }else{
                    $this->giveresponse(false, 'Acceptance Failed');
                }

            } else if($opp_status=='stage assigned'){

                if($given_data['owner_name'] == 'pending'){
                    $this->giveresponse(false, 'Opportunity Owner Pending');
                }else{
                    $returndata=$this->accept_opp_stage($given_data);
                    if($returndata == 1){
                        $this->giveresponse(true, 'Stage Accepted');
                    }else{
                        $this->giveresponse(false, 'Acceptance Failed');
                    }
                }
            }
        }

    }
   //-----Helper function for accept_opportunity()-----//
    private function accept_opp_ownership($given_data) {
      $result = array();
        $opp_ownership= $this->opp_sales->opp_owner($given_data['opp_id']);
        $opp_status= $opp_ownership[0]->owner_status;
        $repotmgr=$this->opp_sales->getrepmgr($given_data['userid']);
        //if($opp_status=='1'){
            $data1= array(
               'owner_id'=>$given_data['userid'],
               'oppowner'=>$given_data['userid'],
               'owner_status'=>2
            );
            $data2= array(
                'mapping_id' => uniqid(rand()),
                'opportunity_id' => $given_data['opp_id'],
                'lead_cust_id' => $given_data['lead_cust_id'],
                'from_user_id' => $given_data['userid'],
                'to_user_id' => $given_data['userid'],
                'cycle_id' => $given_data['cycle_id'],
                'stage_id' =>$given_data['opportunity_stage'],
                'module' => 'sales',
                'sell_type' => $given_data['sell_type'],
                'timestamp' => date('Y-m-d H:i:s'),
                'action' => 'ownership accepted',
                'state' => '1'
            );

             $insertArray1=array();

            //---------------- notification code----------------------------------
            $get_fromuser= $this->opp_sales->get_from_userid1($given_data['opp_id'],$given_data['userid']);
            //to user---- is the one who is to be notified.
            // from user---- is the one who performs action
            $dt = date('ymdHis');
            $notify_id= uniqid($dt);
            $remarks="accept of opp from executive module ";
            $getusername=$this->opp_mgr->getusernamae($get_fromuser);
            $getusername1=$this->opp_mgr->getusernamae($given_data['userid']);
    		$data21= array(
    			'notificationID' =>$notify_id,
    			'notificationShortText'=>'Opportunity Ownership Accepted',
    			'notificationText' =>'Opportunity Ownership Accepted by '.$getusername1.' given by '.$getusername,
    			'from_user'=>$given_data['userid'],
    			'to_user'=>$get_fromuser,
    			'action_details'=>'Opportunity',
    			'notificationTimestamp'=>$dt,
    			'read_state'=>0,
    			'remarks'=>$remarks,
    			'show_status'=>0
    		);
            //array_push($insertArray1, $data2);
            if($get_fromuser!=""){
                 $get_fromuser= $this->opp_sales->rej_opp_notification($data21);
            }

            //---------------------------------------------------------end -----------------

            $update = $this->opp_sales->accept_opp($given_data['opp_id'],$data1);
            $update2 = $this->opp_sales->update_transaction($given_data['opp_id']);
            $update1 = $this->opp_sales->insert_transaction($data2);
            if($update==true && $update1==true && $update2==true){
                return 1;
            }
       // }
    }
//------Helper function for accept_opportunity() for accepting stage ownership----//
    private function accept_opp_stage($given_data)	{
      $result = array();
    	$opp_stage= $this->opp_sales->stage_owner($given_data['opp_id']);
        $opp_status= $opp_stage[0]->stage_owner_status;
        $opp_value=$opp_stage[0]->opportunity_value;
        $stage_manager_owner_id=$opp_stage[0]->stage_manager_owner_id;

        //if($opp_status=='1' || $opp_status=='0'){
            $repotmgr=$this->opp_sales->getrepmgr($given_data['userid']);
            if($opp_value=='onlyexe'){
                $data1= array(
                    'stage_owner_id'=>$given_data['userid'],
                    'stage_owner_status'=>2,
                    'stage_manager_owner_id'=>$repotmgr
                );
            }else{
                $data1= array(
                    'stage_owner_id'=>$given_data['userid'],
                    'stage_owner_status'=>2
                );

            }
            if($opp_value=='onlyexe'){
                $data2= array(
                  'mapping_id'=> uniqid(rand()),
                  'opportunity_id'=>$given_data['opp_id'],
                  'lead_cust_id'=> $given_data['lead_cust_id'],
                  'from_user_id'=> $repotmgr,
                  'to_user_id'=> $given_data['userid'],
                  'cycle_id'=> $given_data['cycle_id'],
                  'stage_id'=>$given_data['opportunity_stage'],
                  'module'=> 'sales',
                  'sell_type'=> $given_data['sell_type'],
                  'timestamp'=> date('Y-m-d H:i:s'),
                  'action' => 'stage accepted',
                  'state' => '1'
              );
            }else{
                $data2= array(
                  'mapping_id'=> uniqid(rand()),
                  'opportunity_id'=>$given_data['opp_id'],
                  'lead_cust_id'=> $given_data['lead_cust_id'],
                  'from_user_id'=> $given_data['userid'],
                  'to_user_id'=> $given_data['userid'],
                  'cycle_id'=> $given_data['cycle_id'],
                  'stage_id'=>$given_data['opportunity_stage'],
                  'module'=> 'sales',
                  'sell_type'=> $given_data['sell_type'],
                  'timestamp'=> date('Y-m-d H:i:s'),
                  'action' => 'stage accepted',
                  'state' => '1'
              );

            }
            $update = $this->opp_sales->accept_opp($given_data['opp_id'],$data1);
            $update2 = $this->opp_sales->update_stage_transaction($given_data['opp_id']);
            $update1 = $this->opp_sales->insert_transaction($data2);

            //---------------- notification code----------------------------------
            $get_fromuser= $this->opp_sales->get_from_userid1($given_data['opp_id'],$given_data['userid']);
            //to user---- is the one who is to be notified.
            // from user---- is the one who performs action
            $dt = date('ymdHis');
            $notify_id= uniqid($dt);
            $remarks="accept of opp from executive module ";
            $getusername=$this->opp_mgr->getusernamae($get_fromuser);
            $getusername1=$this->opp_mgr->getusernamae($given_data['userid']);
    		$data21= array(
    			'notificationID' =>$notify_id,
    			'notificationShortText'=>'Opportunity Stage Accepted',
    			'notificationText' =>'Opportunity Stage Accepted by '.$getusername1.' given by '.$getusername,
    			'from_user'=>$given_data['userid'],
    			'to_user'=>$get_fromuser,
    			'action_details'=>'Opportunity',
    			'notificationTimestamp'=>$dt,
    			'read_state'=>0,
    			'remarks'=>$remarks,
    			'show_status'=>0
    		);
            //array_push($insertArray1, $data2);
            if($get_fromuser!=""){
                 $get_fromuser= $this->opp_sales->rej_opp_notification($data21);
            }

            //---------------------------------------------------------end -----------------

            if($update==true && $update1==true && $update2==true){
                return 1;
            }
        //}
    }


}


