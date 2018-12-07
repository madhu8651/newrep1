<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'utils.php';
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
defined('BASEPATH') OR exit('No direct script access allowed');

class sales_customerController extends Master_Controller{

		public function __construct(){
				parent::__construct();
				$this->load->helper('url');
				$this->load->library('session');
				$this->load->model('sales_customerModel','customer');
		}

    private function exceptionThrower($e) {
           $GLOBALS['$logger']->info('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
            $errorArray = array(
            'errorCode' => $e->getErrorCode(), 
            'errorMsg' => $e->getErrorMessage()
            );  
            $GLOBALS['$logger']->info('Exception JSON to view - '.json_encode($errorArray));
            $GLOBALS['$logger']->info("/-------------------------------------------------------------------------/");

            return json_encode($errorArray);  
  }

		public function assignedCustomerView(){
				if($this->session->userdata('uid')){
          try {
                  $GLOBALS['$logger']->info('!!!Loading Recived Customer View!!!');
                  $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                  $this->load->view('sales_assignedCustomerView');
             }
		      catch(LConnectApplicationException $e) {
                  echo $this->exceptionThrower($e);   
            } 
				}
        else{
						redirect('indexController');
				}    
		}

    public function userPrivilages() {
      if($this->session->userdata('uid')){
        try {
              $GLOBALS['$logger']->info('!!!Fetching User Privilages Data For The Customer!!!');
              $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
              $user_id=$this->session->userdata('uid');
              $data = $this->customer->fetch_userPrivilages($user_id);
              $GLOBALS['$logger']->info('Response :'. json_encode($data));
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

		public function getAssignedCustomerDetails(){
				if($this->session->userdata('uid')){
            try{    
                    $GLOBALS['$logger']->info('!!!Fetching Assigned Customer Data !!!');
                    $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                    $user= $this->session->userdata('uid'); 
                    $data = $this->customer->fetch_userPrivilages($user); 
                    $newCustomer = $this->customer->assignedCustomerInfo($user);
                    echo json_encode($newCustomer);
                  
               }
            catch(LConnectApplicationException $e) {
                   echo $this->exceptionThrower($e);   
                  }   
              } 
            else {
						redirect('indexController');
				  }    
		}

		public function acceptedCustomerView()
		{
				 if($this->session->userdata('uid')){
              try {
                      $GLOBALS['$logger']->info('!!!Loading Accepted Customer View!!!');
                      $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
      				        $this->load->view('sales_acceptedCustomerView');
                  }
              catch(LConnectApplicationException $e) {
                        $GLOBALS['$logger']->info('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                        $errorArray = array(
                        'errorCode' => $e->getErrorCode(), 
                        'errorMsg' => $e->getErrorMessage()
                        );  
                        $GLOBALS['$logger']->info('Exception JSON to view - '.json_encode($errorArray));
                        $GLOBALS['$logger']->info("/-------------------------------------------------------------------------/");

                        echo json_encode($errorArray);    
                } 
      			}
        else{
						redirect('indexController');
				}   
		}

		public function getAcceptedCustomerDetails()
		{
			 if($this->session->userdata('uid')){
            try {
                    $user= $this->session->userdata('uid');  
                    $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));  
                    $data = $this->customer->fetch_userPrivilages($user); 
                    $GLOBALS['$logger']->info('Fetching Accepted Customer Data');
                    $acceptedCustomer = $this->customer->acceptedCustomerInfo($user);
                    echo json_encode($acceptedCustomer);
                    $GLOBALS['$logger']->info('Response :');
                    $GLOBALS['$logger']->info($acceptedCustomer);
                      
                    
            }
            catch(LConnectApplicationException $e) {
                    echo $this->exceptionThrower($e);     
            } 
					
				}
        else {
						redirect('indexController');
				} 
		}

		public function myCustomerView(){
				if($this->session->userdata('uid')){
            try {
                  
                  $GLOBALS['$logger']->info('Loading My Customer View');
                  $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid')); 
                  $this->load->view('sales_myCustomerView');

            } 
            catch(LConnectApplicationException $e) {
                   echo $this->exceptionThrower($e);   
            }       
				}
        else
        {
						redirect('indexController');
				} 
		}


    public function myCustomerDetails() {
      if($this->session->userdata('uid')){
            try { 

                    $GLOBALS['$logger']->info('Fetching My Customer Data'); 
                    $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid')); 
                    $user=$this->session->userdata('uid');
                    $myCustomer= $this->customer->myCustomersData($user);
                    echo json_encode($myCustomer);
                    $GLOBALS['$logger']->info('Response:'); 
                    $GLOBALS['$logger']->info($myCustomer); 

            } 
            catch(LConnectApplicationException $e) {
                    echo $this->exceptionThrower($e);   
            }       
        }
        else
        {
            redirect('indexController');
        } 
    }
	

    public function customerAccept(){          

        if($this->session->userdata('uid')){
              try {     
                        $GLOBALS['$logger']->info('Accepted Customer Data from the user'); 
                        $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                        $user=$this->session->userdata('uid');
                        $json = file_get_contents("php://input");
                        $data = json_decode($json); 
                        $timestamp= date('Y-m-d H:i:s'); 
                        $lid=explode(":", $data->lid); 
                        $GLOBALS['$logger']->info('CustomerID`s in array'); 
                        $GLOBALS['$logger']->info($lid); 
                        $qualified = array();
                        $dt = date('ymdHis');
                        $custName = '';
                        $notify_id= uniqid($dt);
                        $notificationDataArray = array();
                        $notificationDataArray1 = array();
                        $GLOBALS['$logger']->info('Updating customer status and Inserting into lead cust user map'); 
                        $mapping_id = uniqid(rand(),TRUE); 
                        for($i=0; $i<count($lid); $i++) {
                            $customer_owner= $this->customer->rep_owner($lid[$i]); 
                            $customer_status= $customer_owner[0]->customer_rep_status;
                            if($customer_status==1){
                                  $data1= array(
                                  'customer_status'=>'0',
                                  'customer_rep_owner'=>$user,
                                  'customer_rep_status'=>'2'
                                  );
                                  $data2= array(
                                  'mapping_id' =>$mapping_id ,
                                  'lead_cust_id' =>$lid[$i],
                                  'type'=>'customer',
                                  'state' =>1,
                                  'action'=>"accepted",
                                  'module'=>"sales",
                                  'from_user_id'=>$user,
                                  'to_user_id'=>$user,
                                  'timestamp'=>$dt,
                                  );
                                  $update = $this->customer->accept_customer($lid[$i],$data1);
                                  // For assignment purpose 
                                  $update2 = $this->customer->update_transaction($lid[$i]);
                                  $update1 = $this->customer->insert_transaction($data2);
                                  array_push($qualified,$update1);
                            }

                                $customerName =  $this->customer->getCustomerName($lid[$i]); 
                                $getUserName = $this->session->userdata('uname');
                                $getAssignedManager = $this->customer->fetchAssignedManager($lid[$i],$user);  
                                $notificationData1= array(
                                'notificationID' =>$notify_id,
                                'notificationShortText'=>'Customer Accepted',
                                'notificationText' =>'Customer '.$customerName[0]->customer_name.' has Accepted by '.$getUserName.'.',
                                'from_user'=>$user,
                                'to_user'=>$getAssignedManager[0]->managerid,
                                'action_details'=>'customer',
                                'notificationTimestamp'=>$dt,
                                'read_state'=>0,
                                'remarks'=>'Accepted',
                                'task_id'=>$lid[$i]
                                );                                
                                
                                array_push($notificationDataArray1,$notificationData1); 

                        }
                        // Inserting Notification.
                        $notificationsInsert1 = $this->customer->insertNotificationData($notificationDataArray1);                        

                        $GLOBALS['$logger']->info('Customer Data is inserted and updated'); 
                        echo json_encode($qualified);
           }
           catch(LConnectApplicationException $e) {
                  echo $this->exceptionThrower($e);    
            } 
        }
        else{
            redirect('indexController');
        }
    }

    public function customerReject()  {
          if($this->session->userdata('uid')){
                try {   
                        $GLOBALS['$logger']->info('Rejected Customer Data from the user'); 
                        $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                        $user=$this->session->userdata('uid');
                        $json = file_get_contents("php://input");
                        $data = json_decode($json);
                        $leadid = $data->lid;
                        $remarks = $data->note;
                        $rejected_ids=explode(',',$leadid);
                        $count =count($rejected_ids);
                        $rejectedleads=array();
                        $rejectcount=count($rejectedleads); 
                        $dt = date('ymdHis');
                        $notify_id= uniqid($dt);
                        $notificationDataArray = array();
                        $mapping_id = uniqid(rand(),TRUE);   
                        $dt = date('ymdHis');
                        $notificationDataArray = array();
                        $getAssignedManager = '';
                        for($i=0;$i<$count;$i++){
                   
                            $customerowner= $this->customer->rep_owner($rejected_ids[$i]); 
                            $customer_status= $customerowner[0]->customer_rep_status;
                         
                            if($customer_status==1) {
                                  $check_assign= $this->customer->last_reject($rejected_ids[$i],$remarks); 

                            }
                            else {
                                    array_push($rejectedleads,$rejected_ids[$i]); 
                                    $GLOBALS['$logger']->info('Rejected Customers'); 
                                    $GLOBALS['$logger']->info($rejectedleads); 
                            }
                            $getAssignedManager = $this->customer->fetchAssignedManager($rejected_ids[$i],$user);     
                            $notifyUpdateData = array('show_status'=>'1');
                            $this->customer->notificationShowStatus($notifyUpdateData,$rejected_ids[$i],$user);
                        } 
                        //Notification 
                        for($i = 0 ; $i<$count;$i++) { 
                            $getUserName = $this->customer->fetchUserName($user);
                            $customerName =  $this->customer->getCustomerName($rejected_ids[$i]);
                            $notify_id= uniqid($dt);
                            $notificationData= array(
                            'notificationID' =>$notify_id,
                            'notificationShortText'=>'Customer Rejected',
                            'notificationText' =>'Customer '.$customerName[0]->customer_name.'  has rejected by '.$getUserName[0]->user_name.'.',
                            'from_user'=>$user,
                            'to_user'=>$getAssignedManager[$i]->managerid,
                            'action_details'=>'customer',
                            'notificationTimestamp'=>$dt,
                            'read_state'=>0,
                            'remarks'=>'assigned',
                            'show_status' =>0,
                            'task_id' =>$rejected_ids[$i],
                            'action'=>'rejected'
                            ); 
                            array_push($notificationDataArray, $notificationData);
                        }
                        // Inserting Notification.
                        $notificationsInsert = $this->customer->insertNotificationData($notificationDataArray);
                        $GLOBALS['$logger']->info('Rejected Customer Data is updated and inserted'); 
                        echo 1;
                }
                catch(LConnectApplicationException $e) {
                   echo $this->exceptionThrower($e);  
            }             
        }
        else {
            redirect('indexController');
        }
    }  

		public function get_from_user($to,$customer) {
        try {
              $from = $this->customer->getFromUser($to,$customer);
              return  @$from[0]->from_user_id;      
        }
        catch(LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);    
        } 

        }
		public function getCountry() {
			 if($this->session->userdata('uid')){
            try {
                  $GLOBALS['$logger']->info('Fetching Customer Country'); 
                  $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                  $country=$this->customer->view_country();
                  echo json_encode($country);
                  $GLOBALS['$logger']->info('Response'); 
                  $GLOBALS['$logger']->info($country); 
            }
            catch(LConnectApplicationException $e) {
                  echo $this->exceptionThrower($e);   
            } 
				}
        else{
						redirect('indexController');
				} 
		}

		 public function getState(){ 
			if($this->session->userdata('uid')){
            try {
                    $GLOBALS['$logger']->info('Fetching Customer State'); 
                    $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                    $countryId = $this->input->post('id');
                    $state = $this->customer->state($countryId);
                    echo json_encode($state); 
                    $GLOBALS['$logger']->info('Response'); 
                    $GLOBALS['$logger']->info($state); 
            }
            catch(LConnectApplicationException $e) {
                    echo $this->exceptionThrower($e);    
            } 
				}
        else {
						redirect('indexController');
				} 
	 
	 }

	   public function getManagerlist() { 
       if($this->session->userdata('uid')){
            try {
                    $GLOBALS['$logger']->info('Fetching user list'); 
                    $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                    $user=$this->session->userdata('uid');
                    $ListOfManagers=$this->customer->getListOfManager($user);    
                    echo json_encode($ListOfManagers); 
                    $GLOBALS['$logger']->info('Response:'); 
                    $GLOBALS['$logger']->info($ListOfManagers);
            }
            catch(LConnectApplicationException $e) {
                   echo $this->exceptionThrower($e);    
            } 
        }
        else{
            redirect('indexController');
        }

    }

	 public function getContactType(){ 

		 if($this->session->userdata('uid')){
          try {
                  $GLOBALS['$logger']->info('Fetching Contact Type Of Customer'); 
                  $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                  $contact = $this->customer->contact();
                  echo json_encode($contact); 
                  $GLOBALS['$logger']->info('Response:'); 
                  $GLOBALS['$logger']->info($contact);  
          }
          catch(LConnectApplicationException $e) {
                   echo $this->exceptionThrower($e);   
            } 	 
				}
        else{
						redirect('indexController');
				} 
	
	 }

	 public function getIndustry() { 
		 if($this->session->userdata('uid')){
          try {   
                  $GLOBALS['$logger']->info('Fetching industry Of Customer'); 
                  $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                  $user=$this->session->userdata('uid');
                  $industry = $this->customer->industry($user);
                  echo json_encode($industry);
                  $GLOBALS['$logger']->info('Response:'); 
                  $GLOBALS['$logger']->info($industry); 
          }
          catch(LConnectApplicationException $e) {
                  echo $this->exceptionThrower($e);    
            } 

				}
        else{
						redirect('indexController');
				} 
	
	 }

	 public function getLocation(){ 

		 if($this->session->userdata('uid')) {
        try {   
                $GLOBALS['$logger']->info('Fetching Location Of Customer'); 
                $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                $user=$this->session->userdata('uid');
                $location = $this->customer->location($user);
                echo json_encode($location); 
                $GLOBALS['$logger']->info('Response:'); 
                $GLOBALS['$logger']->info($location);       
        }
        catch(LConnectApplicationException $e) {
               echo $this->exceptionThrower($e);  
      } 

		}
    else{
						redirect('indexController');
				} 
	
	 }

  public function getScheduleTask() {
        if($this->session->userdata('uid')){
            try {   
                    $GLOBALS['$logger']->info('Fetching ScheduleTask Of Customer'); 
                    $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                    $user=$this->session->userdata('uid');
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $customer_id=$data->customerId;
                    $customerScheduleData=$this->customer->fetchCustomerScheduleData($customer_id);
                    echo json_encode($customerScheduleData);
                    $GLOBALS['$logger']->info('Response:'); 
                    $GLOBALS['$logger']->info($customerScheduleData); 

            }  
             catch(LConnectApplicationException $e) {
                    echo $this->exceptionThrower($e);  
            }     
        }
        else{
            redirect('indexController');
        }
     }

	 public function getCustomerLogDetails() {
	 
			if($this->session->userdata('uid')){
          try {   
                  $GLOBALS['$logger']->info('Fetching CustomerLogDetails Of Customer'); 
                  $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $customerID=$data->customerId;
                  $CustomerLog=$this->customer->customerLogData($customerID);
                  echo json_encode($CustomerLog);
                  $GLOBALS['$logger']->info('Response:'); 
                  $GLOBALS['$logger']->info($CustomerLog);

          }
          catch(LConnectApplicationException $e) {
                  echo $this->exceptionThrower($e);  
            } 

				}
        else{
						redirect('indexController');
				} 
	 }

		public function getCustomerOppDetails() {
	 
		 if($this->session->userdata('uid')){
          try {   
                  $GLOBALS['$logger']->info('Fetching Opportunity Of Customer'); 
                  $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $customerID=$data->customerId;
                  $CustomerOpp=$this->customer->customerOppData($customerID);
                  echo json_encode($CustomerOpp);
                  $GLOBALS['$logger']->info('Response:'); 
                  $GLOBALS['$logger']->info($CustomerOpp);
          }
          catch(LConnectApplicationException $e) {
                   echo $this->exceptionThrower($e); 
            } 
				}
        else {
						redirect('indexController');
				} 
	 }

	   public function getCurrencyOfOwner(){
       if($this->session->userdata('uid')){
            try {   
                    $GLOBALS['$logger']->info('Fetching Customer Of Owner'); 
                    $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                    $json=file_get_contents("php://input");
                    $data=json_decode($json);
                    $userId=$data->ownerId;
                    $ListOfCurrency=$this->customer->getListOfCurrency($userId);    
                    echo json_encode($ListOfCurrency); 
                    $GLOBALS['$logger']->info('Response:'); 
                    $GLOBALS['$logger']->info($ListOfCurrency);
            }
            catch(LConnectApplicationException $e) {
                    echo $this->exceptionThrower($e);   
            }  
        }
        else{
            redirect('indexController');
        }    
    }

     public function getProductData() {   
      if($this->session->userdata('uid')){
          try {   
                  $GLOBALS['$logger']->info('Fetching Product Data Of The Customer'); 
                  $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $user_id=$data->ownerId;
                  $productData = $this->customer->customerProductData($user_id);
                  echo json_encode($productData);
                  $GLOBALS['$logger']->info('Response Product Data'); 
                  $GLOBALS['$logger']->info($productData); 

          }
          catch(LConnectApplicationException $e) {
                  echo $this->exceptionThrower($e);   
            } 
        }
        else {
            redirect('indexController');
        } 
	 }

      public function postProduct() {
     if($this->session->userdata('uid')){
          try{
                $GLOBALS['$logger']->info('Fetching Same Currency For Products'); 
                $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                $json=file_get_contents("php://input");
                $data=json_decode($json);
                $productArray=$data->productArray;
                $ownerId=$data->ownerId;
                $products=array();
                foreach ($productArray as $productValues) {
                    array_push($products,$productValues );
                }
                $GLOBALS['$logger']->info('Fetching Same Currency For Products'.$products); 
                $distnictCurrency=$this->customer->getCurrency($products,$ownerId);
                echo json_encode($distnictCurrency);
                $GLOBALS['$logger']->info('Response');
                $GLOBALS['$logger']->info($distnictCurrency); 
             }
          catch(LConnectApplicationException $e) {
                echo $this->exceptionThrower($e);   
          }

     }
     else {
            redirect('indexController');
     }
   }
   
    public function postUpdateInfo(){

     if($this->session->userdata('uid')){
          try {   

                  $GLOBALS['$logger']->info('Updating Customer Data'); 
                  $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                  $user_id = $this->session->userdata('uid');
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $GLOBALS['$logger']->info('Customer Data from the user'); 
                  $GLOBALS['$logger']->info($data); 
                  $customerId=$data->customerid;
                  $customerName=$data->customer_name;
                  $customerWebsite=$data->customer_website;
                  $customerEmail = json_encode($data->customer_email);
                  $customerPhone = json_encode($data->customer_phone);
                  $customerCountry=$data->customer_country;
                  $customerState=$data->customer_state;
                  $customerCity=$data->customer_city;
                  $customerZipCode=$data->customer_zipcode;
                  $customerIndustry=$data->customer_industry;
                  $customerBusinessLocation=$data->customer_business_location;
                  $customerOfficeAdress=$data->customer_ofcaddress;
                  $customerRemarks=$data->customer_splcomments;
                  $customerLocationCoord=$data->coordinate;
                  $mapping_id = uniqid(rand(),TRUE); 
                   $newDate = date("Y-m-d H:i:s");
                  $GLOBALS['$logger']->info('!!!Custom Field Data');
                  $leadCustom=$data->leadCustom;
                  $customerCustom=$data->customerCustom;
                  $custom_lead_id=$data->custom_lead_id;
                    if($custom_lead_id!=''){

                      $leadid=$data->custom_lead_id;
                      $GLOBALS['$logger']->info('!!!Updating Custom Data to Lead');
                      $leadCustomData=array('lead_id'=>$custom_lead_id,'attribute'=>json_encode($leadCustom));
                      array_push($customDataArray,$leadCustomData);
                      $update=$this->customer->updateCustomLead($customDataArray);
                      $GLOBALS['$logger']->info('!!!Updated Custom Data to Lead');
                    }

                  $customerDataArray = array();
                  $contactDataArray = array();

                  $checkName = $this->customer->checkEditCustomer($customerName,$customerId);

                  if($checkName == 1) {
                        $customerData=array(
                        'customer_name'=>$customerName,
                        'customer_website'=>$customerWebsite,
                        'customer_number'=>$customerPhone,
                        'customer_country'=>$customerCountry,
                        'customer_state'=>$customerState,
                        'customer_city'=>$customerCity,
                        'customer_zip'=>$customerZipCode,
                        'customer_email'=>$customerEmail,
                        'customer_location_coord'=>$customerLocationCoord,
                        'customer_remarks'=>$customerRemarks,
                        'customer_address'=>$customerOfficeAdress,
                        'customer_industry'=>$customerIndustry,
                        'customer_business_loc'=>$customerBusinessLocation,
                        'customer_id'=>$customerId,
                        'attribute'=>json_encode($customerCustom)
                        );

                        array_push($customerDataArray,$customerData);
                        $GLOBALS['$logger']->info('Customer Data Array to customer table'); 
                        $GLOBALS['$logger']->info($customerDataArray); 

                      $contactID=$data->contact_id;
                      $contactNumbers= json_encode($data->contactNumber);       
                      $contactEmails = json_encode($data->contactEmail);  
                      $contactName=$data->contactname;
                      $contactDesignation=$data->designation;
                      $contactType=$data->contacttype;
                      $contactAddress=$data->address;

                          $customerContactData=array(
                          'contact_name'=>$contactName,
                          'contact_desg'=>$contactDesignation,
                          'contact_email'=>$contactEmails,
                          'contact_number'=>$contactNumbers,
                          'contact_address'=>$contactAddress,
                          'contact_type'=>$contactType,
                          'contact_id'=>$contactID
                          );
                          $transactiondata = array(
                            'mapping_id'=>$mapping_id,
                            'lead_cust_id' =>$customerId,
                            'type'=>'customer',
                            'action'=>"edited",
                            'module'=>'manager',
                            'state' =>1,
                            'from_user_id'=>$user_id,
                            'to_user_id'=>$user_id,
                            'timestamp'=>$newDate,
                        );

                         

                        array_push($contactDataArray, $customerContactData);
                       $GLOBALS['$logger']->info('Contact Data Array to contact table'); 
                        $GLOBALS['$logger']->info($contactDataArray); 

                          $updateCustomerData=$this->customer->updateCustomerInfo($customerDataArray);
                          $GLOBALS['$logger']->info('Customer Data Array is updated'); 
                          $inserttransaction=$this->customer->customer_accept_mgr($transactiondata);
                          $GLOBALS['$logger']->info('Transactions Data Array is inserted'); 
                          $updateContactData=$this->customer->updateContactInfo($contactDataArray);
                          $GLOBALS['$logger']->info('Contact Data Array is updated'); 
                          if($updateCustomerData==1){
                            $user_id=$this->session->userdata('uid');
                            $customerData=$this->customer->assignedCustomerInfo($user_id);
                            echo json_encode($customerData);
                            $GLOBALS['$logger']->info('Response to the view');
                            $GLOBALS['$logger']->info($customerData);
                          }
                          else{
                            $updateCustomerData="false";
                            echo json_encode($updateCustomerData);
                          }
                }
                else {
                    $checkName = "exists";
                    echo json_encode($checkName);
                  }  

               
          }
           catch(LConnectApplicationException $e) {
                  echo $this->exceptionThrower($e);     
          } 

        }
        else{
            redirect('indexController');
        }
         
		}
 public function addProductPurchase() {
       if($this->session->userdata('uid')){
            try {
                    $json=file_get_contents("php://input");
                    $data=json_decode($json);
                    $customerID=$data->customer_id;
                    $purchaseDoc=$data->purchase_doc;
                    $purchaseCurrency=$data->product_Currency;
                    $productInfo=$data->product;
                    $ref_number=$data->ref_number;
                    $pro_owner=$data->pro_owner;
                    $purchaseArray= array();
                    $dt = rand();
                    $purchaseID = '';
                    $purchaseID.=$dt;
                    $purchaseID = uniqid($purchaseID); 

                    foreach ($productInfo as $product) {
                      array_push($purchaseArray, array(
                        'purchase_id'=>$purchaseID,
                        'customer_id'=>$customerID,
                        'product_id'=>$product->product_id,
                        'purchase_start_date'=>$product->pro_strDate,
                        'purchase_end_date'=>$product->pro_endDate,
                        'timestamp'=>date('Y-m-d H:i:s'),
                        'Quantity'=>$product->pro_quantity,
                        'amount'=>$product->pro_cost,
                        'currency'=>$purchaseCurrency,
                        'reference_number'=>$ref_number,
                        'product_owner'=>$pro_owner
                        ));
                    }

                    //batch insert
                $insertProductPurchase=$this->customer->addProductPurchaseInfo($purchaseArray);
                if($insertProductPurchase==true){
                    $purchaseData=$this->customer->customerProductPurchase($customerID);
                    echo json_encode($purchaseData);
                }
            }
             catch(LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);  
            }
        }
        else{
            redirect('indexController');
        }
      
     }
  

  public function update_customer(){
    # code...
        $email=array();
        $phone=array();
        $result = array();
        $customer_id = $_POST['customer_id'];
        $customer_name = $_POST['customer_name'];
        $customer_website = $_POST['customer_website'];
        $customerPhone['phone'] = $_POST['customer_number'];
        $customer_country = $_POST['customer_country'];
        $customer_state = $_POST['customer_state'];
        $customer_city = $_POST['customer_city'];
        $customer_zip = $_POST['customer_zip'];
        $customerEmail['email']  = $_POST['customer_email'];
        $customer_location_coord=$_POST['customer_location_coord'];
        $customer_remarks = $_POST['customer_remarks'];
        $customer_address= $_POST['customer_address'];
        $customer_industry = $_POST['customer_industry'];
        $customer_business_loc=$_POST['customer_business_loc'];

        $customerDataArray = array();
        $contactDataArray = array();

        $checkName = $this->customer->Phone_checkEditCustomer($customer_name,$customer_id);

          if($checkName == 1) {

                $customerData=array(
                'customer_name'=>$customer_name,
                'customer_website'=>$customer_website,
                'customer_number'=>json_encode($customerPhone),
                'customer_country'=>$customer_country,
                'customer_state'=>$customer_state,
                'customer_city'=>$customer_city,
                'customer_zip'=>$customer_zip,
                'customer_email'=>json_encode($customerEmail),
                'customer_location_coord'=>$customer_location_coord,
                'customer_remarks'=>$customer_remarks,
                'customer_address'=>$customer_address,
                'customer_industry'=>$customer_industry,
                'customer_business_loc'=>$customer_business_loc,
                'customer_id'=>$customer_id
                );

                array_push($customerDataArray,$customerData);


                
                $contact_id=$_POST['contact_id'];
                $mobiles=array();
                $mobiles['mobile']=$_POST['contact_number'];       
                $emails=array();
                $emails['email'] = $_POST['contact_email']; 
                $contact_name=$_POST['contact_name']; 
                $contact_desg=$_POST['contact_desg'];
                $contact_type=$_POST['contact_type'];
                $contact_address=$_POST['contact_address'];

                

                $customerContactData=array(
                'contact_name'=>$contact_name,
                'contact_desg'=>$contact_desg,
                'contact_email'=>json_encode($emails),
                'contact_number'=>json_encode($mobiles),
                'contact_address'=>$contact_address,
                'contact_type'=>$contact_type,
                'contact_id'=>$contact_id
                );

                array_push($contactDataArray,$customerContactData);


                $updateCustomerData=$this->customer->phone_updateCustomerInfo($customerDataArray);
                $updateContactData=$this->customer->phone_updateContactInfo($contactDataArray);

                if($updateCustomerData==TRUE && $updateContactData==TRUE) {
                    $success = true;
                    $result['success'] = true;
                    echo json_encode($result);
                    }else{
                    $success = false;
                    $result['success'] = false;
                    echo json_encode($result);
                }

        }
        else{
           $success = false;
           $result['success'] = 0;
           echo json_encode($result);
        }

    }

    public function get_product_purchase_info() {

        $result_set = array();
        $customer_id = $_POST['customer_id'];
        $purchaseData = $this->customer->Phone_get_product_purchase($customer_id);
        if(count($purchaseData)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $purchaseData;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }
    }

    public function getCustomerHistory()  {
          if($this->session->userdata('uid')){
            try {   
                    $GLOBALS['$logger']->info('Customer History Data'); 
                    $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));  
                    $user=$this->session->userdata('uid');
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $customer_id=$data->customerid;
                    $customerCreated=$this->customer->customerHistory($customer_id);
                    echo json_encode($customerCreated); 
                    $GLOBALS['$logger']->info('Response : Customer History'); 
                    $GLOBALS['$logger']->info($customerCreated); 
            }  
             catch(LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);  
            }     
        }
        else{
            redirect('indexController');
        }
    } 

    public function updateProductPurchase() {
        if($this->session->userdata('uid')){
          try {     
                    $GLOBALS['$logger']->info('Updating Customer Data'); 
                    $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                    $user=$this->session->userdata('uid');
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $purchaseID =  $data->purchase_id;
                    $customerID=$data->customer_id;
                    $purchaseDoc=$data->purchase_doc;
                    $purchaseCurrency=$data->product_Currency;
                    $productInfo=$data->product;

                    $purchaseArray= array();
                    foreach ($productInfo as $product) {
                      array_push($purchaseArray, array(
                        'purchase_id'=>$purchaseID,
                        'customer_id'=>$customerID,
                        'product_id'=>$product->product_id,
                        'purchase_start_date'=>$product->pro_strDate,
                        'purchase_end_date'=>$product->pro_endDate,
                        'timestamp'=>date('Y-m-d H:i:s'),
                        'Quantity'=>$product->pro_quantity,
                        'amount'=>$product->pro_cost,
                        'currency'=>$purchaseCurrency,
                        ));
                    }


                    //batch update
                $updateProductPurchase=$this->customer->updateProductPurchaseInfo($purchaseArray);
                 if($updateProductPurchase==true){
                    $purchaseData=$this->customer->customerProductPurchase($customerID);
                    echo json_encode($purchaseData);
                  }
                 else{
                  $purchaseData="false";
                    echo json_encode($purchaseData);
                 } 

            }  
             catch(LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);  
            }     
        }
        else{
            redirect('indexController');
        }
      } 


    public function getProductPurchaseInfo() {   
      if($this->session->userdata('uid')){
          try {
                $GLOBALS['$logger']->info('Fetching Purchase Data'); 
                $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $customerID=$data->customer_id;
                $purchaseData=$this->customer->customerProductPurchase($customerID);
                echo json_encode($purchaseData); 
                $GLOBALS['$logger']->info('Response Data');
                $GLOBALS['$logger']->info($purchaseData);   
          }
           catch(LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);  
            }
            
        }
        else{
            redirect('indexController');
        }   
   }

  public function get_plugin_data(){
      $user=$this->session->userdata('uid');
      $opuput = $this->customer->user_plugin($user);
      echo json_encode($opuput);

  }

  public function customFieldCustomer() {
    if($this->session->userdata('uid')){
    try{
        $GLOBALS['$logger']->info('Custom Field function called');
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $customerId=$data->customerid;
        $getCustomLead='';
        $getCustomCustomer='';
        $finalArrayCustomer=array();
        $finalArrayLead=array();
        $checkLeadId=$this->customer->checkLeadID($customerId);
         $GLOBALS['$logger']->info('Check For The Lead ID in Customer Table');
        if($checkLeadId==true){
          $getCustomLead=$this->customer->fetchCustomLead($customerId);
          $GLOBALS['$logger']->info("Lead Custom Field");
          $GLOBALS['$logger']->info($getCustomLead);

          for($i=0;$i<count($getCustomLead);$i++){
            if($getCustomLead[$i]->attribute!='' OR $getCustomLead[$i]->attribute!=NULL){
               $arr = json_decode($getCustomLead[$i]->attribute);
               for($j=0;$j<count($getCustomLead);$j++){
                  if($getCustomLead[$i]->attribute_key == $arr[$i]->attribute_key){
                    $someArray=array(
                      'attribute_key'=>$getCustomLead[$i]->attribute_key,
                      'attribute_value'=>$arr[$i]->attribute_value,
                      'attribute_name'=>$getCustomLead[$i]->attribute_name,
                      'attribute_validation_string'=>$getCustomLead[$i]->attribute_validation_string,
                      'attribute_type'=>$getCustomLead[$i]->attribute_type,
                      'module'=>$getCustomLead[$i]->module,
                      'id'=>$getCustomLead[$i]->id
                    );
                  }
                  else{
                    $someArray=array(
                      'attribute_key'=>$getCustomLead[$i]->attribute_key,
                      'attribute_value'=>'',
                      'attribute_name'=>$getCustomLead[$i]->attribute_name,
                      'attribute_validation_string'=>$getCustomLead[$i]->attribute_validation_string,
                      'attribute_type'=>$getCustomLead[$i]->attribute_type,
                      'module'=>$getCustomLead[$i]->module,
                      'id'=>$getCustomLead[$i]->id
                    );
                  }
                }

            array_push($finalArrayLead,$someArray); 
            }
            else{
              for($j=0;$j<count($getCustomLead);$j++){
                    $someArray=array(
                      'attribute_key'=>$getCustomLead[$i]->attribute_key,
                      'attribute_value'=>'',
                      'attribute_name'=>$getCustomLead[$i]->attribute_name,
                      'attribute_validation_string'=>$getCustomLead[$i]->attribute_validation_string,
                      'attribute_type'=>$getCustomLead[$i]->attribute_type,
                      'module'=>$getCustomLead[$i]->module,
                      'id'=>$getCustomLead[$i]->id
                    ); 
                }
             array_push($finalArrayLead,$someArray);    
            }             
          }
          $getCustomCustomer=$this->customer->fetchCustomCustomer($customerId);
          $GLOBALS['$logger']->info("Customer Custom Field");
          $GLOBALS['$logger']->info($getCustomCustomer);
          for($i=0;$i<count($getCustomCustomer);$i++){
            if($getCustomCustomer[$i]->attribute!='' OR $getCustomCustomer[$i]->attribute!=NULL){
               $arr = json_decode($getCustomCustomer[$i]->attribute);

               for($j=0;$j<count($getCustomCustomer);$j++){
                  if($getCustomCustomer[$i]->attribute_key == $arr[$i]->attribute_key){
                    $someArray=array(
                      'attribute_key'=>$getCustomCustomer[$i]->attribute_key,
                      'attribute_value'=>$arr[$i]->attribute_value,
                      'attribute_name'=>$getCustomCustomer[$i]->attribute_name,
                      'attribute_validation_string'=>$getCustomCustomer[$i]->attribute_validation_string,
                      'attribute_type'=>$getCustomCustomer[$i]->attribute_type,
                      'module'=>$getCustomCustomer[$i]->module,
                      'id'=>$getCustomCustomer[$i]->id
                    );
                  }
                  else{
                    $someArray=array(
                      'attribute_key'=>$getCustomCustomer[$i]->attribute_key,
                      'attribute_value'=>'',
                      'attribute_name'=>$getCustomCustomer[$i]->attribute_name,
                      'attribute_validation_string'=>$getCustomCustomer[$i]->attribute_validation_string,
                      'attribute_type'=>$getCustomCustomer[$i]->attribute_type,
                      'module'=>$getCustomCustomer[$i]->module,
                      'id'=>$getCustomCustomer[$i]->id
                    );
                  }
                }
            } 
            else{
               $someArray=array(
                      'attribute_key'=>$getCustomCustomer[$i]->attribute_key,
                      'attribute_value'=>'',
                      'attribute_name'=>$getCustomCustomer[$i]->attribute_name,
                      'attribute_validation_string'=>$getCustomCustomer[$i]->attribute_validation_string,
                      'attribute_type'=>$getCustomCustomer[$i]->attribute_type,
                      'module'=>$getCustomCustomer[$i]->module,
                      'id'=>$getCustomCustomer[$i]->id
                    );
            } 

            array_push($finalArrayCustomer,$someArray);            
          }
        }
        else {
          $getCustomCustomer=$this->customer->fetchCustomCustomer($customerId);
          $GLOBALS['$logger']->info("Customer Custom Field");
          $GLOBALS['$logger']->info($getCustomCustomer);
          for($i=0;$i<count($getCustomCustomer);$i++){
            if($getCustomCustomer[$i]->attribute!='' OR $getCustomCustomer[$i]->attribute!=NULL){
               $arr = json_decode($getCustomCustomer[$i]->attribute);

               for($j=0;$j<count($getCustomCustomer);$j++){
                  if($getCustomCustomer[$i]->attribute_key == $arr[$i]->attribute_key){
                    $someArray=array(
                      'attribute_key'=>$getCustomCustomer[$i]->attribute_key,
                      'attribute_value'=>$arr[$i]->attribute_value,
                      'attribute_name'=>$getCustomCustomer[$i]->attribute_name,
                      'attribute_validation_string'=>$getCustomCustomer[$i]->attribute_validation_string,
                      'attribute_type'=>$getCustomCustomer[$i]->attribute_type,
                      'module'=>$getCustomCustomer[$i]->module,
                      'id'=>$getCustomCustomer[$i]->id
                    );
                  }
                  else{
                    $someArray=array(
                      'attribute_key'=>$getCustomCustomer[$i]->attribute_key,
                      'attribute_value'=>'',
                      'attribute_name'=>$getCustomCustomer[$i]->attribute_name,
                      'attribute_validation_string'=>$getCustomCustomer[$i]->attribute_validation_string,
                      'attribute_type'=>$getCustomCustomer[$i]->attribute_type,
                      'module'=>$getCustomCustomer[$i]->module,
                      'id'=>$getCustomCustomer[$i]->id
                    );
                  }
                }
            }
            else{
               $someArray=array(
                      'attribute_key'=>$getCustomCustomer[$i]->attribute_key,
                      'attribute_value'=>'',
                      'attribute_name'=>$getCustomCustomer[$i]->attribute_name,
                      'attribute_validation_string'=>$getCustomCustomer[$i]->attribute_validation_string,
                      'attribute_type'=>$getCustomCustomer[$i]->attribute_type,
                      'module'=>$getCustomCustomer[$i]->module,
                      'id'=>$getCustomCustomer[$i]->id
                    );
            } 

            array_push($finalArrayCustomer,$someArray);             
          }
         } 

        $finalCustom=array('leadCustom'=>$finalArrayLead,'customerCustom'=>$finalArrayCustomer);
        echo json_encode($finalCustom); 
        $GLOBALS['$logger']->info('Custom Field Final Data'); 
        $GLOBALS['$logger']->info($finalCustom);   
        }
        catch (LConnectApplicationException $e){
            echo $this->exceptionThrower($e); 
        }
    }
    else{
        redirect('indexController');
    }
}

public function oppDetailsCustomer() {
    if($this->session->userdata('uid')){
        try{
            $json = file_get_contents("php://input");
            $data = json_decode($json);
            $GLOBALS['$logger']->info('Session UserID:'.$this->session->userdata('uid'));
            $GLOBALS['$logger']->info($data);
            $customerId=$data->customer_id;
            $logData=array();
            $oppid=array();
            $GLOBALS['$logger']->info("Checking LeadId");
            $checkLeadId=$this->customer->checkLeadID($customerId);
            $GLOBALS['$logger']->info($checkLeadId);
            if($checkLeadId!='' or $checkLeadId !=NULL){
                $GLOBALS['$logger']->info("If LeadId");
                $GLOBALS['$logger']->info("fetching LeadId");
                $getLeadId=$this->customer->fetchLeadId($customerId);
                json_encode($getLeadId);
                $leadId=$getLeadId[0]->lead_id;
                $GLOBALS['$logger']->info("fetching opportunity_id for".$leadId);
                $getOppIds=$this->customer->fetchOppId($leadId);
                    foreach ($getOppIds as $key => $value) {
                        $oppid[]=$value->opportunity_id;
                    }
                $oppids = implode("','", $oppid);
                $GLOBALS['$logger']->info("opportunity_ids for".$oppids);
                $GLOBALS['$logger']->info("fetching data for opportunity_ids");
                $oppLogData=$this->customer->oppLogData($oppids);
                $GLOBALS['$logger']->info($oppLogData);
                $GLOBALS['$logger']->info("fetching data for leadid");
                $leadLogData=$this->customer->leadLogData($leadId);
                $GLOBALS['$logger']->info($leadLogData);
                $logData=array_merge($leadLogData,$oppLogData); 
                $GLOBALS['$logger']->info("final Data");
                $GLOBALS['$logger']->info($logData);
        }  
            echo json_encode($logData);
        }
        catch (LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
        $errorArray = array(
        'errorCode' => $e->getErrorCode(), 
        'errorMsg' => $e->getErrorMessage()
        );
        $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
        $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");

        echo json_encode($errorArray);
        }
    }
    else{
    redirect('indexController');
    }
}

      public function file_upload($path){
       if($this->session->userdata('uid')){
        try{
                $GLOBALS['$logger']->info('File Upload Data'); 
                $GLOBALS['$logger']->info('User Session : '. $this->session->userdata('uid'));
                $user_id = $this->session->userdata('uid');          
                $config['upload_path']   = './uploads';
                $config['allowed_types'] = 'gif|jpg|png|bmp';
                $config['max_size'] = "5024000"; 
                $config['overwrite']  = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('userfile')){
                        $error = array('error' => $this->upload->display_errors());
                         echo 1;
                }
                else {
                $data = array(
                 'upload_data' => $this->upload->data()
                );
                $size=$data["upload_data"]["file_size"];
                $this->lconnecttcommunication->FileSizeConvert($size,$user_id,'customer');
                $old_path=$data['upload_data']['full_path'];
                $old_fname = $data['upload_data']['file_name'];
                $new_fname = $path.$data['upload_data']['file_ext'];
                $new_path = str_replace($old_fname, $new_fname, $old_path);
                if (rename($old_path, $new_path)){
                    $customerphoto = $new_fname;
                    $data = array(
                        'customer_logo' => $customerphoto,
                    );
                    $GLOBALS['$logger']->info('Updating Customer Data'); 
                    $update = $this->customer->updateCustomerPhoto($path,$data);
                    if ($update == TRUE) {
                        echo 1;
                        $GLOBALS['$logger']->info('Updated'); 
                    }
                  }
                }
         }catch (LConnectApplicationException $e){
                echo $this->exceptionThrower($e); 
            }
     }else{
            redirect('indexController');
        }
}

public function contactsForLeadCust() {
    if($this->session->userdata('uid')){
        try{
            $GLOBALS['$logger']->info('contacts for lead customer function called');
            $json = file_get_contents("php://input");
            $data = json_decode($json);
            $leadCustId = $data->customer_id;
            $GLOBALS['$logger']->info("Lead Cust ID : $leadCustId");
            $contactDataArray = $this->customer->fetchAllContacts($leadCustId);
            $GLOBALS['$logger']->info('Fetching Contact Records');
            $GLOBALS['$logger']->info('Fetched');
            $GLOBALS['$logger']->info('Response in Array');
            $GLOBALS['$logger']->info($contactDataArray);
            echo json_encode($contactDataArray);            
        }
        catch(LConnectApplicationException $e){
            echo $this->exceptionThrower($e);
        }
    }
    else{
    redirect('indexController');
    }
}


    
}

?>