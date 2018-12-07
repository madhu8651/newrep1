<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_contacts');

class manager_contacts extends Master_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('manager_contactModel','contacts');
	}

	private function exceptionThrower($e)    {
		$GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
		$errorArray = array(
			'errorCode' => $e->getErrorCode(), 
			'errorMsg' => $e->getErrorMessage()
		);
		$GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
		$GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
		return json_encode($errorArray);
	}

	public function index(){
		if($this->session->userdata('uid')){
			try {
				$this->load->view('manager_contacts_view');
			}
			catch(LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
	
	public function initView()  {
		if ($this->session->userdata('uid')) {
			try {
				/*fetches all leads, customers, contact types associated with the rep*/
				$user_id = $this->session->userdata('uid');
				$package = array();
				// fetching leads -
				$package['leads'] = $this->contacts->fetch_Leads($user_id);
				// fetching customers -
				$package['customers'] = $this->contacts->fetch_Customers($user_id);
				// fetching contacts -
				$package['contacts'] = $this->contacts->fetch_Contacts($user_id);
				// fetching contact types -
				$package['contact_type'] = $this->contacts->fetch_contactTypes();   

				$package['opportunity'] = $this->contacts->fetch_Opportunity($user_id);   

				echo json_encode($package);   
			}
			catch(LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}

	//Add a contact for Lead
	public function add_leadContact()   {
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);

				//var_dump($data);exit();
				$user_id = $this->session->userdata('uid');

				$dt = date('ymdHis');
				$contact_id = '';
				$contact_id .= $dt;
				$contact_id = uniqid($contact_id);
				$lead_cust_id = $data->lead_cust_id;
				$contact_name = $data->contact_name;
				$contact_desg = $data->contact_desg;
				$contact_type = $data->contact_type;
				$contact_number = json_encode($data->contact_number);
				$contact_email = json_encode($data->contact_email);
				$contact_dob = $data->contact_dob;
				if (($contact_dob == '0000-00-00') || ($contact_dob == '')) {
					$contact_dob = null;
				}				
				$contact_address = $data->contact_address;
				$created_time = date('Y-m-d H:i:s');
				$remarks = $data->remarks;
				$contact_for = $data->contact_for;
				if($data->contact_for == 'opportunity'){
					//updating opportunity_details table opportunity_contact column if adding more than one contact.
					$this->getOppoContact($contact_id, $lead_cust_id);
				}

				$data = array(
				'contact_id' => $contact_id,
				'lead_cust_id' => $lead_cust_id,
				'contact_name' => $contact_name,
				'contact_desg' => $contact_desg,
				'contact_type' => $contact_type,
				'contact_number' => $contact_number,
				'contact_email' => $contact_email,
				'contact_dob' => $contact_dob,
				'contact_address' => $contact_address,
				'contact_created_by' => $user_id,
				'contact_created_time' => $created_time,
				'remarks' => $remarks,
				'contact_for' => $contact_for
				);
				$insert = $this->contacts->insert_contact($data);
				$response = array();
				$response['success'] = $insert;
				$response['contact_id'] = $contact_id;
				echo json_encode($response);
			}
			catch(LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} 
		else {
			redirect('indexController'); 
		}
	}
	
	//Edit a contact for Lead
	public function edit_leadContact()   {
		 if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$user_id = $this->session->userdata('uid');
				$contact_id = $data->contact_id;
				$lead_cust_id = $data->lead_cust_id;
				$contact_name = $data->contact_name;
				$contact_desg = $data->contact_desg;
				$contact_type = $data->contact_type;
				$contact_number = json_encode($data->contact_number);
				$contact_email = json_encode($data->contact_email);
				$contact_dob = $data->contact_dob;
				if (($contact_dob == '0000-00-00') || ($contact_dob == '')) {
					$contact_dob = null;
				}
				$contact_address = $data->contact_address;
				$remarks = $data->remarks;
				$contact_for = $data->contact_for;

				$data = array(
				'contact_id' => $contact_id,
				'lead_cust_id' => $lead_cust_id,
				'contact_name' => $contact_name,
				'contact_desg' => $contact_desg,
				'contact_type' => $contact_type,				
				'contact_number' => $contact_number,
				'contact_email' => $contact_email,
				'contact_dob' => $contact_dob,
				'contact_address' => $contact_address,
				'remarks' => $remarks,
				'contact_for' => $contact_for
				);
				$insert = $this->contacts->edit_contact($data,$contact_id);
				echo json_encode($data);
			}
			catch(LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		}
		else {            
			redirect('indexController'); 
		}
	}

	public function file_upload($empID)    {
		if($this->session->userdata('uid')) {
			try {
				$config['upload_path']   = './uploads';
				$config['allowed_types'] = 'gif|GIF|jpg|jpeg|JPG|JPEG|png|PNG|bmp|BMP';
				$config['max_size'] = "512000"; // Can be set to particular file size , here it is 2 MB(2048 Kb)
				$config['overwrite']  = TRUE;
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('userfile'))  {
					$error = array('error' => $this->upload->display_errors());
					echo json_encode($error);
					return ;
				} 
				else {
					$data = array('upload_data' => $this->upload->data());

					$old_path=$data['upload_data']['full_path'];
                    //old_path = /uploads/contacts/shruti.jpg
					$old_fname = $data['upload_data']['file_name'];
                    //old_fname = shruti.jpg
					$new_fname = $empID.$data['upload_data']['file_ext'];
                    //new_fname = empID.jpg
					$new_path = str_replace($old_fname, $new_fname, $old_path);
                    //replaces shruti.jpg in old_path with empID.jpg and store it in new_path
					if (rename($old_path, $new_path)) {
						$employeephoto = $new_fname;
						$data = array(
						'contact_photo' => $employeephoto,
						);
						$insert = $this->contacts->edit_contact($data, $empID);
						if ($insert) {
							echo json_encode($data);
						}
					}
				}
			}
            catch(LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} 
		else {
			redirect('indexController'); 
		}
	}

	public function getOppoContact($contact_id, $opp_id){

		$var = $this->contacts->getContacts();
		//var_dump($var);		
		foreach ($var as $key => $value) {
			if($value->opportunity_id == $opp_id){
						//var_dump($value->opportunity_contact.':'.$opp_contact);
						$res = $this->contacts->update_oppoContact($value->opportunity_contact.':'.$contact_id, $value->opportunity_id);
						return $res;
			}
		}
	}

}