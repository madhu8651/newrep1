<?php

defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('excelUploadController');

	/**
	* Description Excel Upload in Admin
	* @author suresh.n
	*/
	class excelUploadController extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->helper('url');
			$this->load->library('session');
			$this->load->model('excelUploadModel','excel');
			$this->load->library('lconnecttcommunication');
			$this->load->model('manager_lead_model','manager');
		}

		private function exceptionThrower($e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
			$errorArray = array(
			'errorCode' => $e->getErrorCode(), 
			'errorMsg' => $e->getErrorMessage()
			); 
			$GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
			$GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
	 		return json_encode($errorArray); 
 		}

 		// Fetching all the users to assign, Because of no constrains!!!
 		public function getUsersToAssign($value='') {
 			if($this->session->userdata('uid')){
 				try{

 					$GLOBALS['$logger']->info('Fetching all the users to assign');
                    $json = file_get_contents("php://input");
                    $data = json_decode($json,TRUE);
                    $industry = $data['industry'];
                    $businessLocation = $data['business_loc'];
 					$usersList = $this->excel->fetchAllUsers($industry,$businessLocation);
 					$GLOBALS['$logger']->info($usersList);
 					echo json_encode($usersList);
 				}
 				catch (LConnectApplicationException $e){
                      echo $this->exceptionThrower($e);
				}
 			}
 			else {
                redirect('indexController');
            }
 		}

 		public function getLeadsToAddCount($value=''){
 			if($this->session->userdata('uid')){
 				try{

 					$GLOBALS['$logger']->info('Fetching all the users to assign');
 					$leadsList = $this->excel->fetchNumberofLeads();
 					$GLOBALS['$logger']->info($leadsList);
 					echo json_encode($leadsList);
 				}
 				catch (LConnectApplicationException $e){
                      echo $this->exceptionThrower($e);
				}
 			}
 			else {
                redirect('indexController');
            }
 		}

 		// Adding Lead Excel data from admin.
 		public function addExcelData(){
 			if($this->session->userdata('uid')){
 				try{
 					$GLOBALS['$logger']->info('adding lead data from excel in admin');
 				}
 				catch (LConnectApplicationException $e){
                      echo $this->exceptionThrower($e);
				}
 			}
 			else {
                redirect('indexController');
            }
 		}

 		public function lead_source($value='') {
 			if($this->session->userdata('uid')){
 				try{
 					$GLOBALS['$logger']->info('fetching lead source from excel in admin');
 					$product_data = $this->excel->fetchLeadSource();					
					$json = array();
					$hkey11="";
						for($i=0;$i<count($product_data);$i++){
										$json[$i]['id'] = $product_data[$i]->hkey2;
										$json[$i]['name'] = $product_data[$i]->hvalue2;
										$a=$product_data[$i]->hkey1;
										if($a=='0'){
											$json[$i]['parent'] = "";
										}else{
										$json[$i]['parent'] = $product_data[$i]->hkey1;
										}
										$json[$i]['checked'] = false;
										$json[$i]['nameAttr'] = 'Addlead';
						}					
					$GLOBALS['$logger']->debug('response received successfully');						
					$GLOBALS['$logger']->debug($json);
					echo json_encode($json); 		
 				}
 				catch (LConnectApplicationException $e){
                      echo $this->exceptionThrower($e);
				}
 			}
 			else {
                redirect('indexController');
            }# code...
 		}

 		public function getIndustry($value='') {
 			if($this->session->userdata('uid')){
 				try{
 					$GLOBALS['$logger']->info('fetching lead source from excel in admin');
 					$leadsList = $this->excel->fetchLeadsIndustry();
 					$GLOBALS['$logger']->info($leadsList);
 					echo json_encode($leadsList);

 				}
 				catch (LConnectApplicationException $e){
                      echo $this->exceptionThrower($e);
				}
 			}
 			else {
                redirect('indexController');
            }
 		}

	public function getLocation(){ 
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch getLocation from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$user= $this->session->userdata('uid');
				$location = $this->excel->location();
				echo json_encode($location);			
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($location);	
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

    public function get_data($value='') {
        if($this->session->userdata('uid')){
            try{    // Function for excel upload from the admin based on the count.
                    $GLOBALS['$logger']->debug('get_data from excel_upload');
                    $GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));
                    $userid=$this->session->userdata('uid');
                    $json = file_get_contents("php://input");
                    $data = json_decode($json,TRUE);
                    $date =  gmdate(date('Y-m-d H:i:s'));
                    $leadDetails = $data['lead'];
                    //user data in packets from ui.
                    $userPackets = $data['users'];
                    $leadCountry = $this->manager->country();
                    $leadState = $this->manager->get_state();
                    $userMapDataArray=array();
                    $leadsDataArray=array();
                    $contactDataArray=array();
                    $selectedLeadIds=array();
                    $selectedLeadNames = array();
                    // Fetching all lead details for validating data.
                    $allLeadDetails = $this->manager->get_leads();
                        for ($i=0; $i <count($leadDetails) ; $i++) { 
                            //Add null to the values;
                            // lead Array for leadinfo
                            $leads = array();
                            $leads['lead_id'] =null;
                            $leads['lead_name']=null;
                            $leads['lead_website']=null;
                            $leads['lead_source']=null;
                            $leads['lead_address']=null;
                            $leads['lead_country']=null;
                            $leads['lead_state']=null;
                            $leads['lead_city']=null;
                            $leads['lead_zip']=null;
                            $leads['lead_city']=null;
                            $leads['lead_remarks']=null;
                            $leadContact['phone'] = [""];
                            $leads['lead_number'] = json_encode($leadContact);
                            $leads['lead_city']=null;
                            $leads['lead_source']=$data['source'];
                            $leads['lead_created_by']=$userid;
                            $leads['lead_created_time']=$date;
                            $leads['lead_industry']=$data['industry'];
                            $leads['lead_business_loc']=$data['bussiness'];
                            $leads['source_flag']=0;
                            $leads['lead_status']=0;
                            // contact array for contact_details.
                            $contact = array();
                            $contact['contact_id']=null;
                            $contact['contact_name']=null;
                            $contact['contact_desg']=null;
                            $contact['contact_number']='';
                            $contact['contact_email']=null;
                            $contact['lead_cust_id']=null;
                            $contact['contact_created_by']=$userid;
                            $contact['contact_created_time']=$date;
                            $contact['contact_for']='lead';

                            // Check LeadName,LeadEmail,ContactName is set.

                            if ((isset($leadDetails[$i]['Lead Name*^']) 
                                    && isset($leadDetails[$i]['Lead Contact Name*^'])
                                    && isset($leadDetails[$i]['Lead Phone Number 1*']))) {
                                    $checkStatus = true;
                                    foreach ($allLeadDetails as $leadsValue) {
                                        // Check Lead Name to each leads.
                                        if($leadsValue->leadname == strtolower($leadDetails[$i]['Lead Name*^'])){
                                            $checkStatus = false;
                                            break;
                                        }
                                    }    
                                        if($checkStatus == true) {
                                            //Generating LeadId
                                            $dt = date('ymdHis');
                                            $leadid = $dt;
                                            $rand = rand();
                                            $leadId = $leadid.uniqid().$rand;
                                            //Generating Contact Id
                                            $contactId =uniqid($dt);
                                            //in Json,
                                            $leadContactNumber = array();
                                            $contactNumber = array();
                                            $leadEmail= array();
                                            //Assign Values to check Lead Column
                                            foreach ($leadDetails[$i] as $key => $leadValue) {
                                                switch ($key) {
                                                    //Lead Colum data
                                                    case 'Lead Name*^':
                                                            $leads['lead_name'] = $leadValue;
                                                            break;
                                                    case 'Lead Company Website': 
                                                            $leads['lead_website'] = $leadValue;
                                                            break;
                                                    case 'Address': 
                                                            $leads['lead_address'] = $leadValue;
                                                            break;                          
                                                    case 'City': 
                                                            $leads['lead_city'] = $leadValue;
                                                            break;
                                                    case 'Zipcode':
                                                            $leads['lead_zip'] = $leadValue;
                                                            break;
                                                    case 'Special Comments': 
                                                            $leads['lead_remarks'] = $leadValue;
                                                            break; 
                                                    case 'Lead E-mail ID 1': 
                                                            $leads['lead_email'] = $leadValue;
                                                            $leadEmail['email'][0]=$leadValue;
                                                            break;
                                                    case 'Lead E-mail ID 2': 
                                                            $leads['lead_email'] = $leadValue;
                                                            $leadEmail['email'][1]=$leadValue;
                                                            break;
                                                    case 'Country':
                                                            foreach ($leadCountry as $country){
                                                                $countryValue= $country->country_name;
                                                                    if($countryValue==strtolower($leadValue)){
                                                                        $leads['lead_country']=$country->lookup_id;
                                                                    }
                                                            }
                                                            break; 
                                                    case 'State':
                                                            foreach ($leadState as $state){
                                                                $stateValue= $state->state_name;
                                                                    if($stateValue==strtolower($leadValue)){
                                                                        $leads['lead_state']=$state->state_id;
                                                                    }
                                                            }
                                                            break; 
                                                    case 'Lead_id': 
                                                            $leads['lead_id']=$leadId;
                                                            break;
                                                    //Contact Data        
                                                    case 'Contact_id': 
                                                            $contact['contact_id']=$contactId;
                                                            break;
                                                    case 'Lead Contact Name*^': 
                                                            $contact['contact_name']=$leadValue;
                                                            break; 
                                                    case 'Lead Contact Designation': 
                                                            $contact['contact_desg']=$leadValue;
                                                            break; 
                                                    case 'Lead Phone Number 1*': 
                                                           $contact['contact_number'] = $leadValue;
                                                           $contactNumber['phone'][0]=$leadValue;
                                                            break;        
                                                    case 'Lead Phone Number 2': 
                                                           $contact['contact_number'] = $leadValue;
                                                           $contactNumber['phone'][1]=$leadValue;
                                                            break;                                                              
                                                }
                                            }
                                            //$contact['contact_number'] ='';
                                            //$contact['contact_email'] ='';
                                           // $leads['lead_number']=json_encode($leadContactNumber);
                                            $contact['contact_number'] = json_encode($contactNumber);
                                            $leads['lead_email']=json_encode($leadEmail);
                                            // Adding LeadCustId to Contact Details
                                            $contact['lead_cust_id']=$leads['lead_id'];
                                            //Inserting into UserMap
                                            $userMapData = array(
                                                            'lead_cust_id'=>$leads['lead_id'],
                                                            'from_user_id'=>$userid,
                                                            'to_user_id'=>$userid,
                                                            'module'=>'admin',
                                                            'action'=>'created',
                                                            'timestamp'=>date("Y-m-d H:i:s"),
                                                            'state'=>'1',
                                                            'type'=>'lead',
                                                            'mapping_id'=>uniqid(rand(),TRUE)
                                                            );
                                        //Array Pusing For Batch Inserting.
                                        //array push userMapData to userMapDataArray
                                        array_push($userMapDataArray, $userMapData);
                                        //array push leads to leadsDataArray
                                        array_push($leadsDataArray,$leads);
                                        //array_push contact to contactDataArray
                                        array_push($contactDataArray,$contact);
                                        // array_push lead id's to selectedLeadIdsArray
                                        array_push($selectedLeadIds,$leads['lead_id']);
                                        // array_push lead name's to selectedLeadNames
                                        array_push($selectedLeadNames,$leads['lead_name']);
                                        }

                                    }
                            }


                            //Inserting Data into Lead Table,Contact Table & LeadCustUsermap Table.
                           $success=$this->excel->insertLeadData($leadsDataArray,$contactDataArray,$userMapDataArray); 
							
                            if($success ==true){
                                $insertArray =array();
                                $status_arr=array();
                                $userList = array();

                                // Assigning Users code need to right here,..! Need User data from UI.
                                foreach ($selectedLeadIds as $leadId) {
                                    for($i=0;$i<count($userPackets);$i++){
                                        $data=array(
                                        'mapping_id'=>uniqid(rand(),TRUE),
                                        'lead_cust_id' => $leadId,
                                        'to_user_id' => $userPackets[$i]['to_user_id'],
                                        'from_user_id'=>$userid,
                                        'module'=>strtolower($userPackets[$i]['module']),
                                        'type'=>'lead',
                                        'state'=>1,
                                        'action'=>'assigned',
                                        'timestamp'=>date('Y-m-d H:i:s')
                                        );
                                        array_push($insertArray, $data);
                                        array_push($userList, $userPackets[$i]['to_user_id']);
                                        if(strtolower($userPackets[$i]['module'])=='manager'){
                                        $data1=array('lead_manager_status'=>1,
                                        'lead_id'=>$leadId);
                                        }else{
                                        $data1=array('lead_rep_status'=>1,
                                        'lead_id'=>$leadId);
                                        }
                                        array_push($status_arr,$data1);
                                    }
                                }

                                //Adding Assignment data to each users.
                                $insert=$this->manager->rep_assign($insertArray);  

                                $GLOBALS['$logger']->debug('response received successfully');
                                $GLOBALS['$logger']->debug($insert);    
                                $update=$this->manager->lead_status_assigned($status_arr);                  
                                $GLOBALS['$logger']->debug('response received successfully');
                                $GLOBALS['$logger']->debug($update);

                                $notificationDataArray = array();
                                $leadIDs='';
                                $leadNamesString = '';

                                // Inserting Notification

                                // Imploding ',' to LeadIds.
                                foreach ($selectedLeadIds as $leadIds) {
                                    $leadIDs .=$leadIds."','";
                                }
                                // Imploding ',' to Lead Names.
                                for ($i=0; $i <count($selectedLeadIds) ; $i++) {
                                    $leadNamesString .=$selectedLeadNames[$i].",";
                                }
                                for ($i=0; $i <count($selectedLeadIds) ; $i++) {
                                    for($j=0;$j<count($userPackets);$j++){
                                        $dt = date('ymdHis');
                                        $notify_id= uniqid($dt);
                                        $GLOBALS['$logger']->info('Fetching user name of assigning user from the session');
                                        $getUserName = $this->session->userdata('uname');
                                        $GLOBALS['$logger']->info('Usernames in array');
                                        $GLOBALS['$logger']->info($getUserName);
                                        // Notification Array

                                        $notificationData= array(
                                            'notificationID' =>$notify_id,
                                            'notificationShortText'=>'Lead Assigned',
                                            'notificationText' =>'Lead '.$selectedLeadNames[$i].' has been assigned to you from '.$getUserName.'.',
                                            'from_user'=>$userid,
                                            'to_user'=>$userPackets[$j]['to_user_id'],
                                            'action_details'=>'lead',
                                            'notificationTimestamp'=>$dt,
                                            'read_state'=>0,
                                            'remarks'=>'assigned',
                                            'show_status' =>0,
                                            'task_id' =>$selectedLeadIds[$i],
                                            'action'=>'assigned'
                                        ); 

                                        array_push($notificationDataArray, $notificationData);

                                    } 
                                }
                                // Inserting Notification
                                $GLOBALS['$logger']->info('Inserting notification data');
                                $notificationsInsert = $this->manager->insertNotificationData($notificationDataArray);
                                $GLOBALS['$logger']->info('Inserted');

                                // Email to assigned users.
                                // Sending userlist,message body and subject to the library function.
                                $users = $userList;
                                $msgbody = 'Lead '.$leadNamesString.' has been assigned';
                                $subject =  'Lead Assigned';
                                $GLOBALS['$logger']->info('Sending Email each assigned user');
                                $email = $this->lconnecttcommunication->send_email($users,$subject,$msgbody);  
                                $GLOBALS['$logger']->info('Email Function called');
                                $GLOBALS['$logger']->info('Returns' . $email );
								
								echo count($selectedLeadIds);

                            }  
                            else{
                                echo count($selectedLeadIds);
                            }                       

            }
            catch (LConnectApplicationException $e){
                echo $this->exceptionThrower($e);
            }


        }
        else{
                redirect('indexController');
        }
    }

		
	}



?>