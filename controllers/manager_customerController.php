<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'utils.php';
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');

class manager_customerController extends Master_Controller{
    public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('manager_customerModel','customer');
        $this->load->library('lconnecttcommunication');
        $this->load->library('pushNotification');
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

	public function index(){  
        if($this->session->userdata('uid')){
			try {
				$GLOBALS['$logger']->info('Unassigned Customer View');
				$GLOBALS['$logger']->info($this->session->userdata('uid'));
				$this->load->view('manager_customerView');
			}
			catch(LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);     
			}
        }
        else{
            redirect('indexController');
        }
	}
  
  public function get_data(){
   if($this->session->userdata('uid')){
      try {   
              $GLOBALS['$logger']->info($this->session->userdata('uid'));
              $GLOBALS['$logger']->info('getting data from the view - Excel');
              $user_id=$this->session->userdata('uid');
              $json = file_get_contents("php://input");
              $data = json_decode($json,TRUE);
              $GLOBALS['$logger']->info('getting excel json data from the view');
              $GLOBALS['$logger']->info($data);
              $cust_data=$data['customer'];
              $country = $this->customer->country();
              $state = $this->customer->get_state();
              $customer_details = $this->customer->get_customers();
              $date =  gmdate(date('Y-m-d H:i:s'));
              $selected_Customer=array();
              $customer_info=array();
              $contact_info=array();
              $transaction=array();
                  for ($i=0;$i<count($cust_data);$i++){
                       $dt = date('ymdHis');
                       $leadid = $dt;
                       $cust_id = $leadid.uniqid();
                        $contact_id=uniqid($leadid);
                        $customers = array();
                        $customers['customer_id']=null;
                        $customers['customer_name']=null;
                        $customers['customer_website']=null;
                        $customers['customer_address']=null;
                        $customers['customer_country']=null;
                        $customers['customer_state']=null;
                        $customers['customer_city']=null;
                        $customers['customer_zip']=null;
                        $customers['customer_remarks']=null;
                        $contact = array();
                        $contact['contact_name']=null;
                        $contact['contact_desg']=null;
                        $contact['contact_number']=null;
                        $contact['contact_email']=null;
                        $contactemail['email'] = array();
                        if(isset($cust_data[$i]['Customer Name*^']) && isset($cust_data[$i]['Customer Contact Name*^'])&& isset($cust_data[$i]['Customer Phone Number 1*'])){
                            $status = true;   
                            foreach ($customer_details as $key=> $val){
                                $Customer_name= $val->customername;
                                if($Customer_name== strtolower($cust_data[$i]['Customer Name*^'])){
                                $status = false;
                                break;
                           }
                        }
                        if($status==true){
                            $cont_num = array();
                            $cont_email= array();
                        foreach ($cust_data[$i] as $k=>$v){
                        switch ($k){
                              case 'Customer_id': $customers['customer_id'] = $cust_id;
                                  $contact['lead_cust_id'] = $cust_id;
                                                       break;
                              case 'Contact_id':$contact['contact_id'] = $contact_id;
                                                      break;
                              case 'Customer Name*^': $customers['customer_name'] = $v;
                                                       break;
                              case 'Customer Company Website': $customers['customer_website'] = $v;
                                                       break;
                              case 'Customer Contact Name*^': $contact['contact_name'] = $v;
                                                       break;
                              case 'Customer Contact Designation': $contact['contact_desg'] = $v;
                                                        break;
                              case 'Customer Phone Number 1*': $cont_num['phone'][0] = $v;
                                                          break;
                              case 'Customer Phone Number 2':$cont_num['phone'][1] = $v;
                                                           break;
                              case 'Customer E-mail ID 1': $cont_email['email'][0] = $v;
                                                        break;
                              case 'Customer E-mail ID 2': $cont_email['email'][1] = $v;
                                                        break;
                              case 'Address': $customers['customer_address'] = $v;
                                                        break;
                              case 'Country':foreach ($country as $key=> $val){
                                       $country_val= $val->country_name;
                                       if($country_val== strtolower($v)){
                                         $customers['customer_country']=$val->lookup_id;
                                       }
                                 }
                                 break;
                              case 'State':foreach ($state as $key=> $val){
                                       $state_val= $val->state_name;
                                       if($state_val== strtolower($v)){
                                         $customers['customer_state']=$val->state_id;
                                       }
                                 }
                                                        break;
                              case 'City': $customers['customer_city'] = $v;
                                                        break;
                              case 'Zipcode': $customers['customer_zip'] = $v;
                                                        break;
                              case 'Special Comments': $customers['customer_remarks'] = $v;
                                                        break;
                              }

                        } 
                            $contact['contact_number'] = json_encode($cont_num);
                            $contact['contact_email'] = json_encode($cont_email);
                            $contact['contact_created_time'] =$date;
                            $contact['contact_created_by'] =$user_id;
                            $contact['contact_for'] ='customer';
                            $customers['customer_created_by'] =$user_id;
                            $customers['customer_manager_owner'] =$user_id;
                            $customers['customer_created_time'] =$date;
                            $customers['customer_manager_status'] =2;
                            $customers['customer_status'] =0;
                            $mapping_id = uniqid(rand(),TRUE);
                            $data5 = array('lead_cust_id' =>$cust_id,'type'=>'customer','state' =>'1','action'=>"created",'module'=>"manager",
                            'from_user_id'=>$user_id, 'to_user_id'=>$user_id,'timestamp'=>$dt,'mapping_id'=>$mapping_id,);
                            $GLOBALS['$logger']->info('customer_data');
                            $GLOBALS['$logger']->info($data5);
                            array_push( $transaction,$data5);
                            array_push( $customer_info,$customers);
                            array_push( $contact_info,$contact);
                            array_push( $selected_Customer,$customers['customer_name']);
                        }
                        }
                        }
                        if(count($customer_info)>0){
                          $GLOBALS['$logger']->info('!!!Batch inserting to customer_info,contact_details,lead_cust_user_map');
                             $insert = $this->customer->insert_details($customer_info,$contact_info,$transaction);
                        if($insert==TRUE){
                          $GLOBALS['$logger']->info('!!!Inserted');
                            echo count($selected_Customer);
                        }
                        }
                        else {
                          echo count($selected_Customer);
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

    

  public function getCustomerDetails()
	{
     if($this->session->userdata('uid')){
          try {

                $GLOBALS['$logger']->info('!!!Fetching Unassigned Customer Data');
                $GLOBALS['$logger']->info('UserID : '.$this->session->userdata('uid'));
                $user_id=$this->session->userdata('uid');
                $customerData=$this->customer->fetchCustomerDetails($user_id);
                echo json_encode($customerData); 
                $GLOBALS['$logger']->info('!!!!!Response from the Query: Unassigned Data');
                $GLOBALS['$logger']->info($customerData);
          }
           catch(LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);  
            }
          
        }else{
            redirect('indexController');
        }
	
	}

  public function userPrivilages() {
    if($this->session->userdata('uid')){
          try {   
				$GLOBALS['$logger']->info('!!!!!Response from the Query: Unassigned Data');
				$user_id=$this->session->userdata('uid');
				$data = $this->customer->fetch_userPrivilages($user_id);
				echo json_encode($data); 
				$GLOBALS['$logger']->info('!!!!!userPrivilages:'.json_encode($data));
          }
           catch(LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);  
            }
          
        }else{
            redirect('indexController');
        }
  }

  public function getRecivedCustomerDetails() {
     if($this->session->userdata('uid')){
            try {
                $GLOBALS['$logger']->info('!!!!!Fetching Received CustomerData');
                $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
                $user_id=$this->session->userdata('uid');
                $recivedCustomerData=$this->customer->fetchRecivedCustomerDetails($user_id);
                echo json_encode($recivedCustomerData);
                $GLOBALS['$logger']->info('Response From the Query');
                $GLOBALS['$logger']->info($recivedCustomerData);

            }
           catch(LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);  
            }
        
          
        }else{
            redirect('indexController');
        }
  }

   public function getCustomerAssignedDetails()
  {
     if($this->session->userdata('uid')){
        try { 
                $GLOBALS['$logger']->info('!!!!!Fetching Assigned CustomerData');
                $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
                $user_id=$this->session->userdata('uid');
                $assignedCustomerData=$this->customer->fetchAssignedCustomerDetails($user_id);
                echo json_encode($assignedCustomerData); 
                $GLOBALS['$logger']->info('Response From the Query');
                $GLOBALS['$logger']->info($assignedCustomerData);
        }
         catch(LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);  
            }   
        }else{
            redirect('indexController');
        }
  }

	public function assignedCustomerView(){
     if($this->session->userdata('uid')){
          try {
				$GLOBALS['$logger']->info('!!!!!Loading Assigned Customer View');
				$GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
				$this->load->view('manager_assignedCustomerView');
            }
           catch(LConnectApplicationException $e) {
            	echo $this->exceptionThrower($e);  
            }  
        }
        else {
            redirect('indexController');
        }
	}
	public function recivedCustomerView(){
     if($this->session->userdata('uid')){
          try {
                $GLOBALS['$logger']->info('!!!!!Loading Received Customer View');
                $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
                $this->load->view('manager_recivedCustomerView');
            }
           catch(LConnectApplicationException $e) {
               echo $this->exceptionThrower($e);   
            }
        }
        else {
            redirect('indexController');
        }
	}

  public function myCustomersView() {
    if($this->session->userdata('uid')){
        try {
				$GLOBALS['$logger']->info('!!!!!Loading My Customer View');
				$GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
				$this->load->view('manager_myCustomersView');
            }
        catch(LConnectApplicationException $e) {
           		echo $this->exceptionThrower($e);  
        }

    }
  }

  public function getMyCustomerDetails() {
    if($this->session->userdata('uid')){
        try {
				$GLOBALS['$logger']->info('!!!!!Fetching Assigned CustomerData');
				$GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
				$user_id=$this->session->userdata('uid');
				$myCustomers=$this->customer->myCustomerDetails($user_id);
				echo json_encode($myCustomers);
				$GLOBALS['$logger']->info('!!!!!Response From the Query My Customer Data');
				$GLOBALS['$logger']->info($myCustomers);
        }
        catch(LConnectApplicationException $e) {
            	echo $this->exceptionThrower($e);  
        }

    }
  }

	 public function getCountry(){
     if($this->session->userdata('uid')){
		try {   
				$GLOBALS['$logger']->info('!!!!!Fetching Country');
				$GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
				$country=$this->customer->view_country();
				echo json_encode($country);
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
                    $GLOBALS['$logger']->info('!!!!!Fetching State');
                    $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
                    $countryId = $this->input->post('id');
                    $state = $this->customer->state($countryId);
                    echo json_encode($state);
            }
            catch(LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);  
            }
            
        }else{
            redirect('indexController');
        }
   }

	public function getContactType(){ 
		if($this->session->userdata('uid')){
		try {
				$GLOBALS['$logger']->info('!!!!!Fetching State');
				$GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
				$contact = $this->customer->contact();
				echo json_encode($contact);
		}
		catch(LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);  
		}

		}else{
		redirect('indexController');
		} 
	 }

	 public function getCustomerLogDetails()
	 {
       if($this->session->userdata('uid')){
            try {
                    $GLOBALS['$logger']->info('!!!!!Fetching Customer Log Details');
                    $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $customerID=$data->customerId;
                    $CustomerLog=$this->customer->customerLogData($customerID);
                    echo json_encode($CustomerLog);
                    $GLOBALS['$logger']->info('!!!!!Response From the Query');
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
				$GLOBALS['$logger']->info('!!!!!Fetching Customer Opportunity Details');
				$GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$customerID=$data->customerId;
				$CustomerOpp=$this->customer->customerOppData($customerID);
				echo json_encode($CustomerOpp);
				$GLOBALS['$logger']->info('!!!!!Response From the Query');
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

	public function getProductData() {   
      if($this->session->userdata('uid')){
          try {   
                  $GLOBALS['$logger']->info('!!!!!Fetching Product Data');
                  $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $user_id=$data->ownerId;
                  $productData = $this->customer->customerProductData($user_id);
                  echo json_encode($productData);
                  $GLOBALS['$logger']->info('!!!!!Response From the Query');
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

  
   /********************************** Unused Function***********************************************/
    public function postProduct() {
     if($this->session->userdata('uid')){
          try{  
                $GLOBALS['$logger']->info('!!!!!Product Data');
                $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
                $json=file_get_contents("php://input");
                $data=json_decode($json);
                $productArray=$data->productArray;
                $ownerId=$data->ownerId;
                $products=array();
                foreach ($productArray as $productValues) {
                    array_push($products,$productValues );
                }
                $distnictCurrency=$this->customer->getCurrency($products,$ownerId);
                echo json_encode($distnictCurrency);
             }
          catch(LConnectApplicationException $e) {
                echo $this->exceptionThrower($e);    
          }

     }
     else {
            redirect('indexController');
     }
   }

	public function getLeadSource() {
     	if($this->session->userdata('uid')){
			try {
				$product_data = $this->customer->customerLeadSourceData();
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
				echo json_encode($json);   
			}
            catch(LConnectApplicationException $e) {
            	echo $this->exceptionThrower($e);  
            }
        }
        else{
            redirect('indexController');
        }
	 	  
	 }

  /**************************************************************************************************/ 

   public function getProductPurchaseInfo()
   {   
      if($this->session->userdata('uid')){
          try {
                $GLOBALS['$logger']->info('!!!!!Product Purchase Data');
                $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));  
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $customerID=$data->customer_id;
                $purchaseData=$this->customer->customerProductPurchase($customerID);
                echo json_encode($purchaseData); 
                $GLOBALS['$logger']->info('!!!!!Response From the Query');
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

  public function getIndustry(){ 
	if($this->session->userdata('uid')){
		try {
			$GLOBALS['$logger']->info('!!!!!Fetching Industry Data');
			$GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));  
			$user=$this->session->userdata('uid');
			$industry = $this->customer->industry($user);
			echo json_encode($industry); 
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
		if($this->session->userdata('uid')){
		try {
			$GLOBALS['$logger']->info('!!!!!Fetching Location Data');
			$GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));  
			$user=$this->session->userdata('uid');
			$location = $this->customer->location($user);
			echo json_encode($location);
			$GLOBALS['$logger']->info($location);
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

	public function postUpdateInfo(){

     	if($this->session->userdata('uid')){  
          	try {
					$GLOBALS['$logger']->info('!!!!!Customer Data For the Update');
					$GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid')); 
					$json = file_get_contents("php://input");
					$data = json_decode($json);
					$GLOBALS['$logger']->info('!!!Passing as JSON to Controller - Customer update!!!');
					$GLOBALS['$logger']->info($data);
					$user_id = $this->session->userdata('uid');
					$customerId=$data->customerid;
					$customerName=$data->customer_name;
					$customerWebsite=$data->customer_website;
					$customerEmail= json_encode($data->customer_email);
					$customerPhone=json_encode($data->customer_phone);
					$customerCountry=$data->customer_country;
					$customerState=$data->customer_state;
					$customerCity=$data->customer_city;
					$customerZipCode=$data->customer_zipcode;
					$customerIndustry=$data->customer_industry;
					$customerBusinessLocation=$data->customer_business_location;
					$customerOfficeAdress=$data->customer_ofcaddress;
					$customerRemarks=$data->customer_splcomments;
					$customerLocationCoord=$data->coordinate;
					$contactID=$data->contact_id;
					$contactNumbers =json_encode($data->contactNumber);       
					$contactEmails = json_encode($data->contactEmail);  
					$contactName=$data->contactname;
					$contactDesignation=$data->designation;
					$contactType=$data->contacttype;
					$contactAddress=$data->address;
					$customerDataArray = array();
					$contactDataArray = array();
					$customDataArray=array();
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

                	$GLOBALS['$logger']->info('Passing Customer Name and Customer ID to Model for Name Validation!!!');
                	//  $checkName = $this->customer->checkEditCustomer($customerName,$customerId);
                	$checkName=1;
                  $GLOBALS['$logger']->info('Function Returns');
                  	if($checkName == 1) {
		                    $customerData=array(
		                    'customer_id'=>$customerId, 
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
		                    'customer_updated_by'=>$user_id,
		                    'customer_updated_time'=>$newDate,
		                    'attribute'=>json_encode($customerCustom)
	                    );
                    array_push($customerDataArray,$customerData);

                    $customerContactData=array(
	                    'contact_id'=>$contactID,  
	                    'contact_name'=>$contactName,
	                    'contact_desg'=>$contactDesignation,
	                    'contact_email'=>$contactEmails,
	                    'contact_number'=>$contactNumbers,
	                    'contact_address'=>$contactAddress,
	                    'contact_type'=>$contactType
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

                    //batch update
                    $GLOBALS['$logger']->info('!!!Batch Updating - Customer, Transaction, Contact Information');
                    $updateCustomerData=$this->customer->updateCustomerInfo($customerDataArray);
                    $GLOBALS['$logger']->info('!!!Success');
                    $inserttransaction=$this->customer->customer_accept_mgr($transactiondata);
                     $GLOBALS['$logger']->info('!!!Success');
                    $updateContactData=$this->customer->updateContactInfo($contactDataArray);
                     $GLOBALS['$logger']->info('!!!Success');
                    if($updateCustomerData==true){
						$GLOBALS['$logger']->info('!!!Updated');
						$customerData=$this->customer->fetchCustomerDetails($user_id);
						echo json_encode($customerData);
                    }
                    else{
						$GLOBALS['$logger']->info('!!!Not Updated');
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

    public function addProductPurchase() {
       if($this->session->userdata('uid')){
            try { 
                    $GLOBALS['$logger']->info('!!!!!Adding Product Purchase Data');
                    $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid')); 
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
                        'product_owner'=>$pro_owner,
                        'renewal_date'=>$product->pro_renewaldate,
                        'rate' =>$product->rate,
                        'score' =>$product->score,
                        'customer_code'=>$product->customer_code,
                        'priority'=>$product->priority
                        ));
                    }
                $GLOBALS['$logger']->info('!!!Purchase Data');
                $GLOBALS['$logger']->info($purchaseArray);   
                $GLOBALS['$logger']->info('!!!!!Batch inserting');    
                    //batch insert
                $insertProductPurchase=$this->customer->addProductPurchaseInfo($purchaseArray);
                if($insertProductPurchase==true){
                    $GLOBALS['$logger']->info('!!!!Inserted Successfully');  
                    $purchaseData=$this->customer->customerProductPurchase($customerID);
                    $GLOBALS['$logger']->info('!!!!!Response To View'); 
                    $GLOBALS['$logger']->info($purchaseData);                     
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

    public function getManagerlist()
    { 
       if($this->session->userdata('uid')) {
            try { 
                    $GLOBALS['$logger']->info('!!!!User List For Assignment is called');  
                    $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid')); 
                    $user=$this->session->userdata('uid');
                    $json=file_get_contents("php://input");
                    $data=json_decode($json);
                    $customers=$data->customers;
                    $GLOBALS['$logger']->info('customerids:'); 
                    $GLOBALS['$logger']->info($customers);
                    $custid=array();
                    foreach ($customers as $customer){
                    array_push($custid, $customer);         
                    } 
                    $GLOBALS['$logger']->info('!!!!Sending customer id for fetching List');  
                    $ListOfManagers=$this->customer->getListOfManager($user,$custid);
                    $GLOBALS['$logger']->info('!!!!Fetching User List');  
                    $GLOBALS['$logger']->info('!!!!Response Manager List'); 
                    $GLOBALS['$logger']->info($ListOfManagers);                        
                    echo json_encode($ListOfManagers); 

            }
             catch(LConnectApplicationException $e) {
                echo $this->exceptionThrower($e);  
            }
          
        }else{
            redirect('indexController');
        }

    }

       public function getManagers() { 
       if($this->session->userdata('uid')){
            try {
                    $GLOBALS['$logger']->info('!!!!User List function called');  
                    $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid')); 
                    $user=$this->session->userdata('uid');
                    $json=file_get_contents("php://input");
                    $data=json_decode($json);
                    $ListOfManagers=$this->customer->getUsers($user);  
                    $GLOBALS['$logger']->info('!!!!Response Manager List');
                    $GLOBALS['$logger']->info($ListOfManagers);                      
                    echo json_encode($ListOfManagers);    
            }
           catch(LConnectApplicationException $e) {
                
                echo $this->exceptionThrower($e);    
            }   
        }
        else{
            redirect('indexController');
        }

    }

    public function getUserModule(){
      if($this->session->userdata('uid')){
		try {
				$GLOBALS['$logger']->info('!!!!UserModule function called');  
				$GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid')); 
				$userId=$this->session->userdata('uid');
				$json=file_get_contents("php://input");
				$data=json_decode($json);
				$UserModule=$this->customer->getModule($userId);  
				$GLOBALS['$logger']->info('!!!!Response :');
				$GLOBALS['$logger']->info($UserModule);   
				echo json_encode($UserModule); 
		}
		catch(LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);  
		}    
        }
        else{
            redirect('indexController');
        } 
    }

    public function getCurrencyOfOwner(){
       if($this->session->userdata('uid')){
            try {   
                    $GLOBALS['$logger']->info('!!!!Currency For user function called');  
                    $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid')); 
                    $json=file_get_contents("php://input");
                    $data=json_decode($json);
                    $userId=$data->ownerId;
                    $ListOfCurrency=$this->customer->getListOfCurrency($userId);  
                    $GLOBALS['$logger']->info('!!!!List of currency for user : Response');  
                    $GLOBALS['$logger']->info($ListOfCurrency);                       
                    echo json_encode($ListOfCurrency); 
            }
             catch(LConnectApplicationException $e) {

                    echo $this->exceptionThrower($e);  
            }
            
        }else{
            redirect('indexController');
        }    
    }

    public function post_id(){ 
    if($this->session->userdata('uid')){
      	try {
	            $GLOBALS['$logger']->info('!!!!Assigned user function called');  
	            $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid')); 
	            $user = $this->session->userdata('uid');
	            $json = file_get_contents("php://input");
	            $data=json_decode($json);
	            $customers = $data->customers;
	            $GLOBALS['$logger']->info('Customer IDs in array:');
	            $GLOBALS['$logger']->info('Customer IDs in array:');
	            $userPackets = $data->users;
	            $insertArray = array();
	            $mapping_id = uniqid(rand(),TRUE);
	            $status_arr= array();
	            $notificationDataArray = array();
	            $userList = array();
	            $userString = '';
	            $reg_id = '';
	            $custName ='';
                //declaring string var.
                $customerids='';
	            $pushNotificationDataArray = array();
	            $messageArray =array();
	            $pushNotificationDataWebArray = array();
	            $messageWebArray =array();
	            $GLOBALS['$logger']->info('imploding "/," to user_ids');  
	            foreach ($userPackets as $packet) {
	              $userString .= ($packet->to_user_id)."','";
	            }
                // imploding customer array using ',' to get all the customer name.
                foreach ($customers as $customer) {
                    $customerids .=$customer."','";
                }

                $GLOBALS['$logger']->info('User String '.$userString); 
                // Token Id's of users respective devices.
                $GLOBALS['$logger']->info('Fetching TokenIds for users');
                $reg_id = $this->customer->getTokenIds($userString);
                $GLOBALS['$logger']->info('TokenIds of Users in Array');
                $GLOBALS['$logger']->info($reg_id);
                $web_reg_id = $this->customer->getTokenIdsWeb($userString);
                $GLOBALS['$logger']->info('TokenIds of Users in Array');
                $GLOBALS['$logger']->info($web_reg_id);
                // customer name in array
                $GLOBALS['$logger']->info('Fetching customer name for each customer ids');
                $customerNameValues =  $this->customer->getCustomerName($customerids);
                $GLOBALS['$logger']->info($customerNameValues);
                for ($i=0; $i <count($customerNameValues) ; $i++) { 
                    $custName .= $customerNameValues[$i]->customer_name.",";
                    foreach ($userPackets as $packet) {
                        $dt = date('ymdHis');
                        $notify_id= uniqid($dt);
                        $GLOBALS['$logger']->info('Fetching user name of assigning user from the session');
                        $getUserName = $this->session->userdata('uname');
                        $GLOBALS['$logger']->info('Usernames in array');
                        $GLOBALS['$logger']->info($getUserName);

                        // Notification Array

                        $notificationData= array(
                            'notificationID' =>$notify_id,
                            'notificationShortText'=>'Customer Assigned',
                            'notificationText' =>'Customer '.$customerNameValues[$i]->customer_name.' has been assigned to you from '.$getUserName.'.',
                            'from_user'=>"$user",
                            'to_user'=>$packet->to_user_id,
                            'action_details'=>'customer',
                            'notificationTimestamp'=>$dt,
                            'read_state'=>0,
                            'remarks'=>'assigned',
                            'show_status' =>0,
                            'task_id' =>$customer,
                            'action'=>'assigned'
                        );

                        $GLOBALS['$logger']->info('Notification Data for assigned to user');
                        $GLOBALS['$logger']->info($notificationData);

                        //Mobile Push Notification
                        $GLOBALS['$logger']->info('Push Notification Data for users device');
                        $pushNotificationData = array(
                        'title'=>'New Customer Assigned',
                        'body'=>$customerNameValues[$i]->customer_name.' has been assigned to you by '.$getUserName.'.'
                        );

                        $GLOBALS['$logger']->info($pushNotificationData);
                        $GLOBALS['$logger']->info('Push Notification message data for users device');
                        $message = array('customer details'=>$customerNameValues[$i]->customer_name);
                        $GLOBALS['$logger']->info($message);

                        //Web Push Notification
                        $GLOBALS['$logger']->info('Push Notification Data for users device');
                        $pushNotificationDataWeb = array(
                        'title'=>'New Customer Assigned',
                        'body'=>$customerNameValues[$i]->customer_name.' has been assigned to you by '.$getUserName.'.'
                        );
                        $GLOBALS['$logger']->info($pushNotificationDataWeb);
                        $messageWeb = array(
                            'title' => 'Testing Notification',
                            'body'  => 'Testing Notification free from localhost',
                            'icon'  =>'',
                            'image' =>''
                        );

                        //push userids to userList array.
                        array_push($userList,$packet->to_user_id);
                        //push notificationData to notificationDataArray array.
                        array_push($notificationDataArray,$notificationData);
                        //push pushNotificationData to pushNotificationDataArray array.
                        array_push($pushNotificationDataArray,$pushNotificationData);
                        //push message to messageArray array.
                        //array_push($messageArray,$message);
                        //push pushNotificationDataWeb to pushNotificationDataWebArray array
                        //array_push($pushNotificationDataWebArray,$pushNotificationDataWeb);
                        //push message to messageArray array.
                        //array_push($messageWebArray,$messageWeb);
                    }

                    $GLOBALS['$logger']->info('Sending Push Notification Data to the library function');
                    $sendPushNotification = $this->pushnotification->sendPushNotification($reg_id,$message,$pushNotificationData);
                    // $sendPushNotification = $this->pushnotification->sendPushNotificationWeb($reg_id,$message,$pushNotificationDataWeb);
                    $GLOBALS['$logger']->info('Push Notification Data sent to the users devices');
                }


	            $GLOBALS['$logger']->info('Adding assignment data to each customer');
            	foreach ($customers as $customer) { 
                	foreach ($userPackets as $packet) {
	                    $data=array(
	                    'lead_cust_id' => $customer,
	                    'to_user_id' => $packet->to_user_id,
	                    'from_user_id'=>$user,
	                    'module'=>$packet->module,
	                    'type'=>'customer',
	                    'state'=>1,
	                    'action'=>'assigned',
	                    'timestamp'=>date('Y-m-d H:i:s'),
	                    'mapping_id'=>$mapping_id
	                    );

                    	array_push($insertArray, $data);
	                    if($packet->module=='manager'){
	                        $data1=array('customer_manager_status'=>1,
	                        'customer_id'=>$customer);
	                    }
                    	else{
	                        $data1=array('customer_rep_status'=>1,
	                        'customer_id'=>$customer);
                    	}
                    	
						array_push($status_arr,$data1);
						
                    }
	                
                }

	                $GLOBALS['$logger']->info('Inserting assignment data to each customer');
	                $insert=$this->customer->rep_assign($insertArray);
	                $GLOBALS['$logger']->info('Inserted');
	                $GLOBALS['$logger']->info('Updating status data to each customer');
	                $update=$this->customer->customer_status_assigned($status_arr);
	                $GLOBALS['$logger']->info('Updated');
	                // Inserting Data to Notification Table.
	                $GLOBALS['$logger']->info('Inserting notification data');
	                $notificationsInsert = $this->customer->insertNotificationData($notificationDataArray);
	                $GLOBALS['$logger']->info('Inserted');
	                // Email to assigned users.
	                // Sending userlist,message body and subject to the library function.
	                $users = $userList;
	                $msgbody = 'Customer '.$custName.' has been assigned';
	                $subject =  'Customer Assigned';
	                $GLOBALS['$logger']->info('Sending Email each assigned user');
	                $email = $this->lconnecttcommunication->send_email($users,$subject,$msgbody);  
	                $GLOBALS['$logger']->info('Email Function called');
	                $GLOBALS['$logger']->info('Returns' . $email );
	                echo json_encode($update); 
      	}
       	catch(LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);  
        }  
        }
        else {  
          redirect('indexController');
        }
    } 

 public function postReassign(){ 
    if($this->session->userdata('uid')){ 
        try {
                $GLOBALS['$logger']->info('!!!!Reassigment Customer for the user function called');  
                $GLOBALS['$logger']->info('Session UserID:'.$this->session->userdata('uid'));
                $user = $this->session->userdata('uid');
                $json = file_get_contents("php://input");
                $data=json_decode($json);
                $customers = $data->customers;             
                $userPackets = $data->users;
                $insertArray = array();
                $customer_state=array();
                $insertrepowner=array();
                $rep_activity=array();
                $mapping_id = uniqid(rand(),TRUE);
                $manager_activity=array();
                $manager='';
                $sales='';
                $custName = '';
                $userList = array();
                $userString = '';
                $reg_id = '';
                $custName ='';
                //declaring string var.
                $customerids='';
                $pushNotificationDataArray = array();
                $messageArray =array();
                $GLOBALS['$logger']->info('imploding "/," to user_ids');  
                foreach ($userPackets as $packet) {
                $userString .= ($packet->to_user_id)."','";
                }
                // imploding customer array using ',' to get all the customer name.
                foreach ($customers as $customer) {
                    $customerids .=$customer."','";
                }

                $GLOBALS['$logger']->info('Fetching customer name for each customer ids');
                $customerNameValues =  $this->customer->getCustomerName($customerids);
                $GLOBALS['$logger']->info($customerNameValues);

                $notificationDataArray = array();
                $GLOBALS['$logger']->info('User String '.$userString); 
                $GLOBALS['$logger']->info('Fetching TokenIds for users');
                $reg_id = $this->customer->getTokenIds($userString);
                $GLOBALS['$logger']->info('TokenIds of Users in Array');
                $GLOBALS['$logger']->info($reg_id);
                for ($i=0; $i <count($customerNameValues) ; $i++) { 
                    $custName .= $customerNameValues[$i]->customer_name.",";
                    foreach ($userPackets as $packet) {
                        $dt = date('ymdHis');
                        $notify_id= uniqid($dt);
                        $GLOBALS['$logger']->info('Fetching user name of assigning user from the session');
                        $getUserName = $this->session->userdata('uname');
                        $GLOBALS['$logger']->info('Usernames in array');
                        $GLOBALS['$logger']->info($getUserName);

                        // Notification Array

                        $notificationData= array(
                            'notificationID' =>$notify_id,
                            'notificationShortText'=>'Customer Re-Assigned',
                            'notificationText' =>'Customer '.$customerNameValues[$i]->customer_name.' has been re-assigned to you from '.$getUserName.'.',
                            'from_user'=>$user,
                            'to_user'=>$packet->to_user_id,
                            'action_details'=>'customer',
                            'notificationTimestamp'=>$dt,
                            'read_state'=>0,
                            'remarks'=>'assigned',
                            'show_status' =>0,
                            'task_id' =>$customer,
                            'action'=>'assigned'
                        );

                        $GLOBALS['$logger']->info('Notification Data for assigned to user');
                        $GLOBALS['$logger']->info($notificationData);

                        $GLOBALS['$logger']->info('Push Notification Data for users device');
                        $pushNotificationData = array(
                        'title'=>'New Customer Assigned',
                        'body'=>$customerNameValues[$i]->customer_name.' has been re-assigned to you by '.$getUserName.'.'
                        );
                        $GLOBALS['$logger']->info($pushNotificationData);
                        $GLOBALS['$logger']->info('Push Notification message data for users device');
                        $message = array('customer details'=>$customerNameValues[$i]->customer_name);
                        $GLOBALS['$logger']->info($message);

                        array_push($userList,$packet->to_user_id);
                        //push notificationData to notificationDataArray array.
                        array_push($notificationDataArray,$notificationData);
                        //push pushNotificationData to pushNotificationDataArray array.
                        array_push($pushNotificationDataArray,$pushNotificationData);
                        //push message to messageArray array.
                        array_push($messageArray,$message);

                    }

                    $GLOBALS['$logger']->info('Sending Push Notification Data to the library function');
                   // $sendPushNotification = $this->pushnotification->sendPushNotification($reg_id,$message,$pushNotificationData);
                    $GLOBALS['$logger']->info('Push Notification Data sent to the users devices');
                }

                $GLOBALS['$logger']->info('Adding re-assignment data to each customer');
                foreach ($customers as $customer) {
                      foreach ($userPackets as $packet) {
                            $data=array(
                            'lead_cust_id' => $customer,
                            'to_user_id' => $packet->to_user_id,
                            'from_user_id'=>$user,
                            'module'=>$packet->module,
                            'type'=>'customer',
                            'state'=>1,
                            'action'=>'reassigned',
                            'timestamp'=>date('Y-m-d H:i:s'),
                            'mapping_id'=>$mapping_id
                            );

                            array_push($insertArray, $data);
                            if($packet->module=='manager'){
                                $data1=array('customer_manager_status'=>1,
                                'customer_id'=>$customer,
                                'customer_manager_owner'=>$user);
                                $manager='manager';
                                /* $data2=array('state'=>0,
                                'lead_cust_id'=>$customer);*/
                                array_push($customer_state, $customer);
                                array_push($manager_activity, $customer);
                            }
                            else{
                                $data1=array('customer_rep_status'=>1,
                                'customer_id'=>$customer,
                                'customer_rep_owner'=>null);
                                $sales='sales';   
                                /* $data2=array('state'=>0,
                                'lead_cust_id'=>$customer);*/
                                array_push($customer_state, $customer);
                                array_push($rep_activity, $customer);
                            }

                            array_push($insertrepowner,$data1);
                            //array_push($customer_state,$data2);
                        }

                    }

                    if($manager!='') {
                        $reassign_zero=$this->customer->reassign_state_zero($customer_state,$manager);
                    }
                    else {
                        $reassign_zero=$this->customer->reassign_state_zero($customer_state,$sales);
                    } 

                    $GLOBALS['$logger']->info('Updating Null data to reassigned ID`s in table');
                    $update_lead_reminder=$this->customer->update_reminder_table($manager_activity,$rep_activity);
                    $GLOBALS['$logger']->info('Returns'.$update_lead_reminder);  
                    $GLOBALS['$logger']->info('Inserting assignment data to each customer');
                    $insert=$this->customer->rep_assign($insertArray);            
                    $update=$this->customer->rep_owner_update($insertrepowner);
                    $GLOBALS['$logger']->info('Updating Status data to each customer');
                     //inserting notification.
                    $notificationsInsert = $this->customer->insertNotificationData($notificationDataArray);
                    // Email to assigned users.
                    // Sending userlist,message body and subject to the library function.
                    $users = $userList;
                    $msgbody = 'Customer '.$custName.' has been assigned';
                    $subject =  'Customer Reassigned';
                    $GLOBALS['$logger']->info('Sending Email to each assigned user');
                    $email = $this->lconnecttcommunication->send_email($users,$subject,$msgbody);
                    $GLOBALS['$logger']->info('Email Function called');
                    $GLOBALS['$logger']->info('Returns' . $email );  
                    echo json_encode($update);
                    
        }
         catch(LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);  
        }
    } 
        else {
          redirect('indexController');
        }
    } 


    public function customerAccept(){          

        if($this->session->userdata('uid')){
            try {
                   $GLOBALS['$logger']->info('Accepted Customer Function Called');
                    $user=$this->session->userdata('uid');
                    $GLOBALS['$logger']->info('Session UserID:'.$this->session->userdata('uid'));
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);  
                    $timestamp= date('Y-m-d H:i:s'); 
                    $lid=explode(":", $data->lid);
                    $count  = count($lid); 
                    $GLOBALS['$logger']->info('CustomerID`s:');
                    $GLOBALS['$logger']->info($lid);
                    $qualified = array();
                    $dt = date('ymdHis');
                    $custName = '';
                    $notificationDataArray = array();
                    $getAssignedManager = '';
                    $GLOBALS['$logger']->info('Updating ManagerOwner for each customerId');
                    for($i=0; $i<count($lid); $i++) {
                        $customerName =  $this->customer->getCustomerName($lid[$i]);
                        $acceptedStatus = $this->customer->updateLeadMgrOwner($lid[$i], $user);
                        if ($acceptedStatus != false) {
                        //qualified will hold the customer_ids of those which are accepted
                        array_push($qualified, $acceptedStatus);
                        }
                        //Notification update.
                        $notifyUpdateData = array('show_status'=>'1');
                        $this->customer->notificationShowStatusAccept($notifyUpdateData,$lid[$i]);
                        $getAssignedManager = $this->customer->fetchAssignedManager($lid[$i],$user);
                        $getUserName = $this->customer->fetchUserName($user);                        
                        // Insert Notification 
                        foreach ($getAssignedManager as $managerOwner) {
                            $notify_id= uniqid($dt);
                            $notificationData= array(
                            'notificationID' =>$notify_id,
                            'notificationShortText'=>'Customer Accepted',
                            'notificationText' =>'Customer '.$customerName[0]->customer_name.' has accepted by '.$getUserName[0]->user_name.'.',
                            'from_user'=>$user,
                            'to_user'=>$managerOwner->managerid,
                            'action_details'=>'customer',
                            'notificationTimestamp'=>$dt,
                            'read_state'=>0,
                            'remarks'=>'assigned',
                            'show_status' =>0,
                            'task_id' =>$lid[$i],
                            'action'=>'accepted'
                            );  

                        //pushing notificationData to notificationDataArray for batch inserting.
                        array_push($notificationDataArray,$notificationData);    
                        } 
                        $GLOBALS['$logger']->info('Notification Data of those which are accepted');
                        $GLOBALS['$logger']->info($notificationDataArray);
                        $notificationsInsert = $this->customer->insertNotificationData($notificationDataArray);  
                        $GLOBALS['$logger']->info('Inserted');                     
                    }              
                    echo json_encode($qualified);
                    $GLOBALS['$logger']->info('customer_ids of those which are accepted');
                    $GLOBALS['$logger']->info($qualified);
            }
             catch(LConnectApplicationException $e) {
                echo $this->exceptionThrower($e);  
            }
        
        }
        else{
            redirect('indexController');
        }
      }

    public function customerReject() {
           if($this->session->userdata('uid')){
                try {
	                    $GLOBALS['$logger']->info('Accepted Customer Function Called');
	                    $user_id=$this->session->userdata('uid');
	                    $GLOBALS['$logger']->info('Session UserID:'.$this->session->userdata('uid'));
	                    $json = file_get_contents("php://input");
	                    $data = json_decode($json);
	                    $timestamp= date('Y-m-d H:i:s'); 
	                    $lid=explode(":", $data->lid);  
	                    $remarks=$data->note;
	                    $dt = date('ymdHis');
	                    $notify_id= uniqid($dt);
	                    $notificationDataArray = array();
	                    $count =count($lid);
	                    $mapping_id = uniqid(rand(),TRUE);
	                    $notificationDataArray = array();  
	                    $custName = array();
	                    $getAssignedManager = '';
                        for($i=0;$i<count($lid);$i++) {
                            $data=array(
                            'mapping_id'=>$mapping_id,
                            'lead_cust_id' => $lid[$i],
                            'from_user_id' => $user_id,
                            'to_user_id' => $user_id,
                            'action'=>'rejected',
                            'type'=>'customer',
                            'state'=>1,
                            'module'=>'manager',
                            'remarks'=>$remarks,
                            'timestamp'=>$timestamp
                            );
                            $result=$this->customer->lead_accept_mgr($data,$lid[$i]);  
                                if($result==1){ 
                                    $count1=$this->customer->count_assign_customer($lid[$i]);
                                    $count2 =$this->customer->count_rejected_customer($lid[$i]);   
                                      if($count1==$count2)
                                      {
                                          $mstatus=array('customer_manager_status'=>3);
                                          $this->customer->update_customertable($lid[$i],$mstatus);
                                      }             
                                }
                            $getAssignedManager = $this->customer->fetchAssignedManager($lid[$i],$user_id); 
                            //Notification update.    
                            $notifyUpdateData = array('show_status'=>'1');
                            $this->customer->notificationShowStatus($notifyUpdateData,$lid[$i],$user_id);
                        }
                        for($i = 0 ; $i<count($lid);$i++) { 
                            $getUserName = $this->customer->fetchUserName($user_id);
                            $customerName =  $this->customer->getCustomerName($lid[$i]);
                            $notify_id= uniqid($dt);
                            $notificationData= array(
                            'notificationID' =>$notify_id,
                            'notificationShortText'=>'Customer Rejected',
                            'notificationText' =>'Customer '.$customerName[0]->customer_name.' has rejected by '.$getUserName[0]->user_name.'.',
                            'from_user'=>$user_id,
                            'to_user'=>$getAssignedManager[$i]->managerid,
                            'action_details'=>'customer',
                            'notificationTimestamp'=>$dt,
                            'read_state'=>0,
                            'remarks'=>'assigned',
                            'show_status' =>0,
                            'task_id' =>$lid[$i],
                            'action'=>'rejected'
                            ); 
                            //pushing notificationData to notificationDataArray for batch inserting.
                            array_push($notificationDataArray, $notificationData);
                        }
                        $GLOBALS['$logger']->info('Notification Data of those which are accepted');
                        $notificationsInsert = $this->customer->insertNotificationData($notificationDataArray); 
                        $GLOBALS['$logger']->info('Rejected Data is inserted for each customerId');
                        $GLOBALS['$logger']->info('Response: '. $result);                      
                        echo  json_encode($result);      
              }
             catch(LConnectApplicationException $e) {
              echo $this->exceptionThrower($e);     
          }         
        }
        else{
            redirect('indexController');
        }
       }  

     public function getCustomerHistory()  {
          if($this->session->userdata('uid')){
            try {   
                    $GLOBALS['$logger']->info('Customer history Function Called');
                    $GLOBALS['$logger']->info('Session UserID:'.$this->session->userdata('uid'));
                    $user=$this->session->userdata('uid');
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $customer_id=$data->customerid;
                    $GLOBALS['$logger']->info('Fetching Customer history for customerid'.$customerid); 
                    $customerCreated=$this->customer->customerHistory($customer_id);
                    $GLOBALS['$logger']->info('Response Customer history'); 
                    $GLOBALS['$logger']->info($customerCreated);                     
                    echo json_encode($customerCreated);
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
                    $GLOBALS['$logger']->info('Schedule Function Called');
                    $GLOBALS['$logger']->info('Session UserID:'.$this->session->userdata('uid'));
                    $user=$this->session->userdata('uid');
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $customer_id=$data->customerId;
                    $GLOBALS['$logger']->info('Fetching Schedule Customer data for customerid'.$customer_id);
                    $customerScheduleData=$this->customer->fetchCustomerScheduleData($customer_id);
                    $GLOBALS['$logger']->info('Response:');
                    $GLOBALS['$logger']->info($customerScheduleData);
                    echo json_encode($customerScheduleData); 
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
                    $GLOBALS['$logger']->info('updateProductPurchase Function Called');
                    $GLOBALS['$logger']->info('Session UserID:'.$this->session->userdata('uid'));
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
                        'id'=>$product->id,
                        'rate' =>$product->rate,
                        'score' =>$product->score,
                        'customer_code'=>$product->customer_code,
                        'renewal_date'=>$product->pro_renewaldate,
                        'priority'=>$product->priority
                        ));
                    }

                  $GLOBALS['$logger']->info('ProductPurchaseData');
                  $GLOBALS['$logger']->info($purchaseArray);       
                    //batch update
                   $GLOBALS['$logger']->info('Batch Updating to Purchase Table');
                  $updateProductPurchase=$this->customer->updateProductPurchaseInfo($purchaseArray,$purchaseID,$product->product_id);
                 if($updateProductPurchase==true){
                    $GLOBALS['$logger']->info('Updated');
                    $purchaseData=$this->customer->customerProductPurchase($customerID);
                    echo json_encode($purchaseData);
                    $GLOBALS['$logger']->info('Response');
                    $GLOBALS['$logger']->info($purchaseData);
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

      public function logSchedule(){
        if($this->session->userdata('uid')){
            try{
                $GLOBALS['$logger']->info('logSchedule Function Called');
                $GLOBALS['$logger']->info('Session UserID:'.$this->session->userdata('uid'));
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $customerId=$data->customerId;
                $GLOBALS['$logger']->info('Fetching Scheduled data for this customerid'.$customerId);
                $data=$this->customer->logScheduleActivity($customerId);
                $GLOBALS['$logger']->info('Response');
                $GLOBALS['$logger']->info($data);                
                echo json_encode($data);
            } 
            catch (LConnectApplicationException $e)  {
              echo $this->exceptionThrower($e); 
            }
        }else{
                redirect('indexController');
        }
    }

    public function get_managerlist_reassign(){
        if($this->session->userdata('uid')){
            try{
                  $GLOBALS['$logger']->info('Getting UserList for Reassigment Function Called');
                  $GLOBALS['$logger']->info('Session UserID:'.$this->session->userdata('uid'));
                  $user= $this->session->userdata('uid');
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $customers=$data->leads;
                  $customer1=array();
                  foreach ($customers as $customer){
                  array_push($customer1, $customer);         
                  } 
                  $GLOBALS['$logger']->info('Fetching UserList'); 
                  $mgr=$this->customer->getListOfManager($customer1,$user); 
                  $GLOBALS['$logger']->info($mgr);
                  echo json_encode($mgr);      
            } 
            catch (LConnectApplicationException $e)  {
                echo $this->exceptionThrower($e); 
            }
       }
       else{
        redirect('indexController');
      }
}


     public function file_upload($path){
       if($this->session->userdata('uid')){
        try{
              $GLOBALS['$logger']->info('FILE UPLOAD Function Called');
              $GLOBALS['$logger']->info('Session UserID:'.$this->session->userdata('uid'));
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
                  $update = $this->customer->updateCustomerPhoto($path,$data);
                  if ($update == TRUE) {
                      echo 1;
                  }
                }
              }
         }
         catch (LConnectApplicationException $e) {
                echo $this->exceptionThrower($e); 
            }
     }else{
            redirect('indexController');
        }
}
      public function get_customerhistory(){
          if($this->session->userdata('uid')){
          try{
              $GLOBALS['$logger']->info('Customer history Function Called');
              $json = file_get_contents("php://input");
              $data1 = json_decode($json);
              $id=$data1->id;
              $GLOBALS['$logger']->info('Fetching customer history for customerid '.$id);
              $data=$this->customer->customer_history($id);
              $data2=$this->customer->customer_historydetails($id);				
              $data3= array ('history'=>$data,'history_detail'=>$data2);
              $GLOBALS['$logger']->info('Response'); 
              $GLOBALS['$logger']->info($data3);               
              echo json_encode($data3); 
          }
          catch (LConnectApplicationException $e){
                  echo $this->exceptionThrower($e); 
            }
          }
          else{
              redirect('indexController');
          }
      	
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
        }
        catch (LConnectApplicationException $e){
            echo $this->exceptionThrower($e); 
        }
    }
    else{
        redirect('indexController');
    }
}

public function customerDocuments(){
    if($this->session->userdata('uid')){
        try{
          $json = file_get_contents("php://input");
          $data = json_decode($json);
          $GLOBALS['$logger']->info("Calling Customer Document Function");
          $GLOBALS['$logger']->info($data);
          $customerId=$data->customer_id;
          $GLOBALS['$logger']->info('Session UserID:'.$this->session->userdata('uid'));
          $GLOBALS['$logger']->info("Fetching Customer Document");
          $data=$this->customer->customerDocumentsById($customerId); 
          $GLOBALS['$logger']->info($data);
          echo json_encode($data);    
        }
        catch (LConnectApplicationException $e){
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