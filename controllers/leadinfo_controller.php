<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('leadinfo_controller');

class leadinfo_controller extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('leadinfo_model','lead');
        $this->load->model('manager_lead_model','manager');
		$this->load->library('lconnecttcommunication');
    }
    public function index(){
        if($this->session->userdata('uid')){
            try{
              $this->load->view('leadinfo_view');
            }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
        }else{
            redirect('indexController');
        }    
    }
    public function accepted_leads(){
        if($this->session->userdata('uid')){
            try{
           $this->load->view('sales_acceptedleadView');
            }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
        }else{
            redirect('indexController');
        }
    }
     public function closed_lost(){
        if($this->session->userdata('uid')){
            try{
           $this->load->view('closed_lost');
            }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
        }else{
            redirect('indexController');
        }
    }
    public function in_progress(){
        if($this->session->userdata('uid')){
            try{
           $this->load->view('inprogress_lead');
            }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
        }else{
            redirect('indexController');
        }
    }
    public function closed(){
        if($this->session->userdata('uid')){
            try{
            $this->load->view('closed_lead');
             }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
        }else{
            redirect('indexController');
        }
    }
     public function display_active(){
        if($this->session->userdata('uid')){
            try{
        $active = $this->lead->active_lead();
        echo json_encode($active);
         }catch (LConnectApplicationException $e){
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
    public function display_new(){
        if($this->session->userdata('uid')){
        try{
        $new = $this->lead->new_lead();
          echo json_encode($new);
           }catch (LConnectApplicationException $e){
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
    public function display_accept(){
        if($this->session->userdata('uid')){
            try{
        $accept = $this->lead->accepted_lead();
        echo json_encode($accept);
           }catch (LConnectApplicationException $e){
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
    public function get_leadDetails(){
        if($this->session->userdata('uid')){
          try{
            $lead = $this->lead->lead_details('country','industry','bussines','contactType');
            echo json_encode($lead);
         }catch (LConnectApplicationException $e){
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
    public function lead_source(){
        if($this->session->userdata('uid')){
            try{
            $product_data = $this->lead->leadsource();
            $json = array();
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
             }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
        }else{
            redirect('indexController');
        }
    }
    public function get_product(){
       if($this->session->userdata('uid')){
           try{
            $product = $this->lead->product_show();
            echo json_encode($product);
           }catch (LConnectApplicationException $e){
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
    public function accept_multiple(){
        if($this->session->userdata('uid')){
            try{
            $userid= $this->session->userdata('uid');
            $user_name= $this->session->userdata('uname');
            $reporting_to= $this->session->userdata('reporting_to');
            $json = file_get_contents("php://input");
            $data=json_decode($json);
            $dt = date('ymdHis');
            $leadid = $data->reject_data;
            $count =count($leadid);
            $mapping_id = uniqid(rand(),TRUE);                
            $rejectedleads=array();
            $getAssignedManagerArray = array();
            $notificationDataArray = array();
            $managerData = array();
            for ($i=0; $i <$count ; $i++) { 
                $getAssignedManager = $this->lead->fetchAssignedManager($leadid[$i],$userid);
                array_push($getAssignedManagerArray,$getAssignedManager);
            }

            //Fetch Superior Manager who reports to Admin
            $superiorManager = $this->lead->fetchSuperiorManager();                    

            for ($i=0; $i <$count; $i++) { 
                $leadName =  $this->lead->getLeadName($leadid[$i]); 
                    $notify_id= uniqid($dt);
                    if($getAssignedManagerArray[$i][0]->managername == 'Admin'){
                        $notificationData= array(
                            'notificationID' =>$notify_id,
                            'notificationShortText'=>'Lead Accepted',
                            'notificationText' =>'Lead '.$leadName[0]->lead_name.' has accepted by '.$user_name.'.',
                            'from_user'=>$userid,
                            'to_user'=>$superiorManager[0]->superior_id,
                            'action_details'=>'lead',
                            'notificationTimestamp'=>$dt,
                            'read_state'=>0,
                            'task_id'=>$leadid[$i],
                            'show_status'=>0,
                            'action'=>'accepted'
                        );  
                    }
                    else{

                        $notificationData= array(
                            'notificationID' =>$notify_id,
                            'notificationShortText'=>'Lead Accepted',
                            'notificationText' =>'Lead '.$leadName[0]->lead_name.' has accepted by '.$user_name.'.',
                            'from_user'=>$userid,
                            'to_user'=>$getAssignedManagerArray[$i][0]->managerid,
                            'action_details'=>'lead',
                            'notificationTimestamp'=>$dt,
                            'read_state'=>0,
                            'task_id'=>$leadid[$i],
                            'show_status'=>0,
                            'action'=>'accepted'
                        );  

                    }
                    
                    array_push($notificationDataArray, $notificationData);      
            }

            $leadManagerOwner = '';
            //Inserting Notification
            
            $notificationsInsert = $this->lead->insertNotificationData($notificationDataArray);
            for($i=0;$i<$count;$i++){
                $lead_owner= $this->lead->rep_owner($leadid[$i]); 
                $lead_status= $lead_owner[0]->lead_rep_status;
                if($lead_status==1){
                    $data1= array(
                       'lead_rep_owner'=>$userid,
                       'lead_rep_status'=>2
                    );

                    // If its assigned by Admin
                    if($getAssignedManagerArray[$i][0]->managername =='Admin'){

                        // Fetching Reporting person.

                        $ReportingTo=$this->lead->fetch_reporting($userid);
                        $managerOwner=$ReportingTo[0]->name;
                        $managerOwnerId = $ReportingTo[0]->report;

                            if($managerOwner == 'Admin'){
                                $leadManagerOwner = $userid;
                            }
                            else{

                                $leadManagerOwner = $managerOwnerId;
                            }

                            $managerData= array(
                            'lead_manager_owner'=>$leadManagerOwner
                            );
                    }

                    $data2= array(
                     'mapping_id' =>$mapping_id ,
                     'lead_cust_id' =>$leadid[$i],
                     'type'=>'lead',
                     'state' =>1,
                     'action'=>"accepted",
                     'module'=>"sales",
                     'from_user_id'=>$userid,
                     'to_user_id'=>$userid,
                     'timestamp'=>$dt,
                     );
                    $update = $this->lead->accept_lead($leadid[$i],$data1);
                    $updateManagerData=$this->lead->managerLeadData($leadid[$i],$managerData);
                    $update2 = $this->lead->update_transaction($leadid[$i]);
                    $update1 = $this->lead->insert_transaction($data2);
                    
                }else{
                     array_push($rejectedleads,$leadid[$i]); 
                }
            }
            echo json_encode($rejectedleads);
          }catch (LConnectApplicationException $e){
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

// Rejecting Lead 

public function reject_multiple($value='') {
    if($this->session->userdata('uid')){ 
        try{

            $userid= $this->session->userdata('uid');
            $json = file_get_contents("php://input");
            $reporting_to= $this->session->userdata('reporting_to');
            $user_name= $this->session->userdata('uname'); 
            $data=json_decode($json);
            $dt = date('ymdHis');
            $leadid = $data->reject_data;
            $remarks = $data->rej_remarks; 
            $rejected_ids=explode(',',$leadid);
            $count =count($rejected_ids); 
            $assignedData = array(); 
            $notificationData = array(); 
            $notificationDataArray = array();
            $assignedDataArray = array();
            $getAssignedManagerArray = array();
            $rejectedleads = array();
            $updateLead = array();
            $updateLeadArray = array();
            $notificationDataArray1 = array();
            $leadName = '';

            // Fetching Assigned Lead Managers.
            for ($i=0; $i <$count ; $i++) { 
                $getAssignedManager = $this->lead->fetchAssignedManager($rejected_ids[$i],$userid);
                array_push($getAssignedManagerArray,$getAssignedManager);
                
            } 
            // Inserting rejected data
            for($i=0;$i<$count;$i++){ 
                $leadowner= $this->lead->rep_owner($rejected_ids[$i],$userid); 
                $lead_status= $leadowner[0]->lead_rep_status;
                $leadName =  $this->lead->getLeadName($rejected_ids[$i]); 

                if($lead_status==1) {
                    $check_assign= $this->lead->last_reject($rejected_ids[$i],$remarks); 
                }
                else {
                    array_push($rejectedleads,$leadid[$i]); 
                }

                $notifyUpdateData = array('show_status'=>'1');
                $this->lead->notificationShowStatus($notifyUpdateData,$rejected_ids[$i],$userid);

                // Lead Rejected Notification.
                $notify_id1= uniqid($dt);
                    $notificationData1= array(
                        'notificationID' =>$notify_id1,
                        'notificationShortText'=>'Lead Rejected',
                        'notificationText' => $leadName[0]->lead_name ." lead has been rejected by ".$user_name,
                        'from_user'=>$userid,
                        'to_user'=>$getAssignedManagerArray[$i][0]->managerid,
                        'action_details'=>'lead',
                        'notificationTimestamp'=>$dt,
                        'read_state'=>0,
                        'remarks'=>$remarks,
                        'task_id'=>$rejected_ids[$i],
                        );
                    array_push($notificationDataArray1, $notificationData1);
            } 
            // Inserting Rejected Noitification Data.
            if(!empty($notificationData1)){
                    $this->lead->insertNotificationData($notificationDataArray1);
                }
            // Check if lead Assigned from Admin & Assigned to Superior.
            // Insert Notification of Assigned Data & Assigned Data.
            // Update leadinfo    
            for($i=0;$i<$count;$i++){ 
                $notify_id= uniqid($dt);
                $leadName =  $this->lead->getLeadName($rejected_ids[$i]); 
                $superiorManager = $this->lead->fetchSuperiorManager();
                    if($getAssignedManagerArray[$i][0]->Admin  == NULL){
                        $assignedData= array(
                                        'mapping_id' =>uniqid(rand(),TRUE),
                                        'lead_cust_id' =>$rejected_ids[$i],
                                        'type'=>'lead',
                                        'state' =>1,
                                        'action'=>"assigned",
                                        'module'=>"manager",
                                        'from_user_id'=>$getAssignedManagerArray[$i][0]->managerid,
                                        'to_user_id'=>$superiorManager[0]->superior_id,
                                        'timestamp'=>$dt,
                                    );

                        $updateLead = array('lead_manager_status'=>1,
                            'lead_id'=>$rejected_ids[$i]); 

                        $notificationData= array(
                                            'notificationID' =>$notify_id,
                                            'notificationShortText'=>'Lead Assigned',
                                            'notificationText' => $leadName[0]->lead_name ." lead has assigned from the Admin has been rejected by ".$user_name.' and its been assigned to you',
                                            'from_user'=>$getAssignedManagerArray[$i][0]->managerid,
                                            'to_user'=>$superiorManager[0]->superior_id,
                                            'action_details'=>'lead',
                                            'notificationTimestamp'=>$dt,
                                            'read_state'=>0,
                                            'remarks'=>$remarks,
                                            'task_id'=>$rejected_ids[$i],
                                        );
                    }


                    // Array push for batch insert.
                    array_push($notificationDataArray, $notificationData);
                    array_push($assignedDataArray, $assignedData);
                    array_push($updateLeadArray, $updateLead);

                }        
                // Inserting Data Notification.
                if(!empty($notificationData)){
                    $this->lead->insertNotificationData($notificationDataArray);
                }
                // Inserting Assigned Data to Supieror.
                if(!empty($assignedData)){
                    $this->lead->insertLeadCust($assignedDataArray);
                }
                // Update Lead Data 
                if(!empty($updateLead)){
                    $this->lead->updateLeadInfoAssigned($updateLeadArray);
                }
                           
            echo 1;                

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

    public function product_array(){
     if($this->session->userdata('uid')){
     try{
        $lid = $this->input->post('id');
        $product = $this->lead->product($lid);
         echo json_encode($product);
       }catch (LConnectApplicationException $e){
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
    public function product_view(){
     if($this->session->userdata('uid')){
     try{
     $lid = $this->input->post('id');
     $product = $this->lead->product_view($lid);
     echo json_encode($product);
      }catch (LConnectApplicationException $e){
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
     public function get_state(){
     if($this->session->userdata('uid')){
         try{
      $cid = $this->input->post('id');
      $state = $this->lead->state($cid);
      echo json_encode($state);
       }catch (LConnectApplicationException $e){
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
    public function log_lead(){
      if($this->session->userdata('uid')){
          try{
      $lid = $this->input->post('id');
      $leaddeatail = $this->lead->lead_deatils($lid);
      echo json_encode($leaddeatail);
       }catch (LConnectApplicationException $e){
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
    public function  dislpay_loglead(){
      if($this->session->userdata('uid')){
          try{
      $lid = $this->input->post('id');
      $leaddeatail = $this->lead->lead_log($lid);
      echo json_encode($leaddeatail);
       }catch (LConnectApplicationException $e){
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
   public function opportunity(){
    if($this->session->userdata('uid')){
        try{
      $oppid = $this->input->post('id');
      $opportunities = $this->lead->lead_opprtunity($oppid);
      echo json_encode($opportunities);
       }catch (LConnectApplicationException $e){
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
  public function add_lead(){
    if($this->session->userdata('uid')){
    try{
       $userid= $this->session->userdata('uid');
       $mgr_module= $this->session->userdata('manager');
       $sale_module= $this->session->userdata('sales');
       if($mgr_module=='MA1705221245451234567891011' && $sale_module=='SA1705221245591234567891011'){
           $managerid=$userid;
       }else{
            $managerid= $this->session->userdata('reporting_to');
       }
       $json = file_get_contents("php://input");
       $data = json_decode($json);
       $dt = date('ymdHis');
       $leadname = $data->leadname;
       $leadwebsite = $data->leadwebsite;
       $leademail['email'] = $data->leademail;
       $leadphone['phone'] = $data->phone;
       $productid= $data->product;
       $leadsource = $data->leadsource;
       $leadcountry = $data->country;
       $state = $data->state;
       $city = $data->city;
       $zipcode = $data->zipcode;
       $ofcaddress = $data->ofcaddress;
       $splcomments = $data->splcomments;
       $contactname = $data->contactname;
       $designation = $data->designation;
       $contacttype = $data->contacttype;
       if($contacttype=="" || $contacttype==null)
       {
           $contact_type=$contacttype;
       }else{
           $contact_type='PR1706291008305954d19eeaef9';
       }
       $coordinate= $data->coordinate;
       $industry = $data->industry;
       $bussiness= $data->bussiness;
       $custom_lead= $data->customlead;
       $leadcontact['phone']= $data->mobile;
       $contactemail['email']= $data->email;
       $mapping_id = uniqid(rand(),TRUE);  
       $insertchk = $this->lead->duplicate_lead($leadname);
       $insertchk = 1;
       if($insertchk==1){
        $leadid= uniqid($dt);
        $data1 = array(
            'lead_id' => $leadid,
            'lead_name' => $leadname,
            'lead_number' =>json_encode($leadphone),
            'lead_email'=> json_encode($leademail),
            'lead_website' => $leadwebsite,
            'lead_location_coord' =>$coordinate,
            'lead_address'=> $ofcaddress,
            'lead_city' => $city,
            'lead_state' =>$state,
            'lead_country'=> $leadcountry,
            'lead_zip'=> $zipcode,
            'lead_remarks'=> $splcomments,
            'lead_source'=> $leadsource,
            'lead_created_by'=> $userid,
            'lead_status'=> 0,
            'lead_rep_status'=>2,
            'lead_created_time'=>$dt,
            'lead_rep_owner'=>$userid,
            'lead_industry'=>$industry,
            'lead_business_loc'=>$bussiness,
            'lead_manager_owner'=>$managerid,
            'contact_number'=> $leadcontact['phone'][0],
            'lead_manager_status'=>2,
            'attribute'=>json_encode($custom_lead),
             );
         $empid=uniqid($dt);
         $data2 = array(
                'contact_id' =>$empid,
                'lead_cust_id' =>$leadid,
                'contact_name'=> $contactname,
                'contact_desg' => $designation,
                'contact_email' => json_encode($contactemail),
                'contact_number'=> json_encode($leadcontact),
                'contact_type'=>$contacttype,
                'contact_created_time'=>$dt,
                'contact_created_by'=>$userid,
                'contact_for'=>"lead",
              );
          $data3 = array(
                'mapping_id' =>$mapping_id ,
                'lead_cust_id' =>$leadid,
                'type'=>'lead',
                'state' =>0,
                'action'=>"created",
                'module'=>"sales",
               'from_user_id'=>$userid,
               'to_user_id'=>$userid,
                'timestamp'=>$dt,
              );
           $data4 = array(
                'mapping_id' =>$mapping_id ,
                'lead_cust_id' =>$leadid,
                'type'=>'lead',
                'state' =>1,
                'action'=>"accepted",
                'module'=>"sales",
                'from_user_id'=>$userid,
                'to_user_id'=>$userid,
                'timestamp'=>$dt,
              );
            $insert = $this->lead->insert_lead($data1);
            $insert1 = $this->lead->insert_details($data2);
            $insert2 = $this->lead->insert_transaction($data3);
            $insert3 = $this->lead->insert_transaction($data4);
            if($insert ==TRUE && $insert1 ==TRUE){
                $count=count($productid);
                for($i=0;$i<$count;$i++){
                 $data6 = array(
                'lead_id' =>$leadid,
                'product_id'=>$productid[$i],
                'timestamp' =>$dt,
                 );
                  $insert5 = $this->lead->insert_product($data6);
                }
                 $response = array();
                 $response['leadid'] = $leadid;
                 echo json_encode($response);
            }
        }else{
            echo 0;
        } }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
       }else{
            redirect('indexController');
       }
    }
  public function leadsource_edit(){
          if($this->session->userdata('uid')){
              try{
                $leadid = $this->input->post('id');
                $productdata_edit=$this->lead->productdata_edit($leadid);
                echo json_encode($productdata_edit);
                 }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }

          }else{
              redirect('indexController');
          }
    }
    public function update_lead(){
     if($this->session->userdata('uid')){
      try{
      $userid= $this->session->userdata('uid');
      $cuurentdt = date('ymdHis');
       $newDate = date("Y-m-d H:i:s");
      $json = file_get_contents("php://input");
      $data = json_decode($json);
      $leadname = $data->leadname;
      $leadid = $data->leadid;
       $leadwebsite = $data->leadwebsite;
       $leademail['email'] = $data->leademail;
       $leadphone['phone'] = $data->phone;
       $productid= $data->product;
       $leadsource = $data->leadsource;
       $leadcountry = $data->country;
       $state = $data->state;
       $city = $data->city;
       $zipcode = $data->zipcode;
       $ofcaddress = $data->ofcaddress;
       $splcomments = $data->splcomments;
       $contactname = $data->contactname;
       $designation = $data->designation;
       $contacttype = $data->contacttype;
       $coordinate= $data->coordinate;
       $industry = $data->industry;
       $bussiness= $data->business;
       $contact_id= $data->employeeid;
        $mapping_id = uniqid(rand(),TRUE); 
       $leadcontact['phone']= $data->mobile;
       $contactemail['email']= $data->email;
       $edit_custom = $data->edit_custom;
       
       $insertchk = $this->lead->check_editlead($leadname,$leadid);
       $insertchk = 1;
        if($insertchk==1){
            $data1 = array(
                'lead_id' => $leadid,
                'lead_name' => $leadname,
                'lead_number' =>json_encode($leadphone),
                'lead_email'=> json_encode($leademail),
                'lead_website' => $leadwebsite,
                'lead_location_coord' =>$coordinate,
                'lead_address'=> $ofcaddress,
                'lead_city' => $city,
                'lead_state' =>$state,
                'lead_country'=> $leadcountry,
                'lead_zip'=> $zipcode,
                'lead_remarks'=> $splcomments,
                'lead_source'=> $leadsource,
                'lead_created_by'=> $userid,
                'lead_rep_owner'=>$userid,
                'lead_industry'=>$industry,
                'lead_business_loc'=>$bussiness,
                'lead_updated_by'=>$userid,
                'lead_updated_time'=>$newDate,
                'contact_number'=> $leadcontact['phone'][0],
                'attribute'=>json_encode($edit_custom),
             );
          
         $data2 = array(
                'lead_cust_id' =>$leadid,
                'contact_name'=> $contactname,
                'contact_desg' => $designation,
                'contact_email' => json_encode($contactemail),
                'contact_number'=> json_encode($leadcontact),
                'contact_type'=>$contacttype,
                'contact_updated_time'=>$cuurentdt,
                'contact_updated_by'=>$userid,
                'contact_for'=>"lead",
              );
          $data3 = array(
                'mapping_id'=>$mapping_id,
                'lead_cust_id' =>$leadid,
                'type'=>'lead',
                'action'=>"edited",
                'module'=>'sales',
                'state' =>1,
                'from_user_id'=>$userid,
                'to_user_id'=>$userid,
                'timestamp'=>$cuurentdt,
              );

           $update = $this->lead->update_info($leadid,$data1);
           $update1 = $this->lead->update_details($contact_id,$data2);
           $update2 = $this->lead->insert_transaction($data3);
           $update3 = $this->lead->delete_prod($leadid);
            if($update==TRUE && $update1==TRUE && $update2==TRUE && $update3==TRUE){
                $count=count($productid);
                for($i=0;$i<$count;$i++){
                 $data6 = array(
                    'lead_id' =>$leadid,
                    'product_id'=>$productid[$i],
                    'timestamp' =>$cuurentdt,
                 );
                  $insert5 = $this->lead->insert_product($data6);
                }
                $lead['leadid']= $leadid ;
                echo json_encode($lead);
            }
        }else{
            echo 0; 
        } }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
       }else{
            redirect('indexController');
        }
       }
    public function update_progreslead(){
        if($this->session->userdata('uid')){
            try{
      $userid= $this->session->userdata('uid');
      $json = file_get_contents("php://input");
       $data = json_decode($json);
       $leadname = $data->leadname;
       $leadwebsite = $data->leadwebsite;
       $leademail = $data->leademail;
       $leadphone = $data->phone;
       $productid= $data->product;
       $leadsource = $data->source;
       $leadcountry = $data->country;
       $state = $data->state;
       $city = $data->city;
       $zipcode = $data->zipcode;
       $ofcaddress = $data->ofcaddress;
       $splcomments = $data->splcomments;
       $contactname = $data->contactname;
       $designation = $data->designation;
       $mobile1 = $data->mobile1;
       $mobile2 = $data->mobile2;
       $email1 = $data->email1;
       $primemail2 = $data->email2;
       $contacttype = $data->contacttype;
       $longitude= $data->longitude;
       $lattitude = $data->lattitude;
       $leadid=$data->leadid;
       $employeeid=$data->employeeid;
       
         $data1 = array(
                'lead_id' => $leadid,
                'leadname' => $leadname,
                'leadphone' =>$leadphone,
                'productid'=> $productid,
                'leademail' => $leademail,
                'leadsource' =>$leadsource,
                'productid'=> $productid,
                'leadcountry' => $leadcountry,
                'state' =>$state,
                'city'=> $city,
                'zipcode'=> $zipcode,
                'leadwebsite'=> $leadwebsite,
                'leadlat'=> $lattitude,
                'leadlng'=> $longitude,
                'repremarks'=> $splcomments,
                'leadtaddress'=> $ofcaddress,
             );
         $data2 = array(
                'leadid' =>$leadid,
                'employeename'=> $contactname,
                'employeedesg' => $designation,
                'employeeemail' =>$email1,
                'employeephone1'=> $mobile1,
                'employeephone2' => $mobile2,
                'employeeemail2' => $primemail2,
                'contact_type'=>$contacttype,
             );
          $data3 = array(
                'rep_id' =>$userid,
             );
        $update = $this->lead->update_info($leadid,$data1);
        if($update==TRUE){          
        $update1 = $this->lead->update_details($employeeid,$data2);
            if($update1==TRUE){
             $update2 = $this->lead->update_rep($leadid,$data3);
             $new = $this->lead->active_lead();
             echo json_encode($new);
            }
        } 
       }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
     }else{
            redirect('indexController');
        }
    }
    public function Activate(){
    if($this->session->userdata('uid')){ 
        try{
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $leadid = $data->leadid;
        $lead_remarks = $data->actiavte_remarks;
        $data1 = array(
                'leadstate' =>5
               );
        $data2 = array(
                'lead_state' =>5,
                'remarks' =>$lead_remarks
               );
        $update = $this->lead->activate($leadid,$data1);
        if($update==TRUE){          
        $update1 = $this->lead->update_remarks($leadid,$data2);
         
               if($update1== TRUE){
           echo 1;
         }
         else{
             echo 0;
         }
         }
        }catch (LConnectApplicationException $e){
              $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
              $errorArray = array(
                  'errorCode' => $e->getErrorCode(), 
                  'errorMsg' => $e->getErrorMessage()
              );
              $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
              $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
              echo json_encode($errorArray);
          }
      }else{
            redirect('indexController');
      }
    }
 public function extend_date(){
 if($this->session->userdata('uid')){
     try{
    $json = file_get_contents("php://input");
    $data = json_decode($json);
   $leadid = $data->leadid;
   $extend_date = $data->extend_date;
   $newDate = date("Y-m-d", strtotime($extend_date));
   $lead_remarks = $data->ropen_remarks;
   $data1 = array(
            'extended_date' =>$newDate
           );
   $data2 = array(
            'remarks' =>$lead_remarks
           );
    $update = $this->lead->activate($leadid,$data1);
    if($update==TRUE){          
    $update1 = $this->lead->update_remarks($leadid,$data2);
    if($update1== TRUE){
        $close = $this->lead->closed_lead();
      echo json_encode($close);
     }
     }
      }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
 }else{
        redirect('indexController');
    }
}
public function display_lostlead(){
     if($this->session->userdata('uid')){
         try{
        $close = $this->lead->closed_lostlead();
         echo json_encode($close); 
          }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
     }else{
        redirect('indexController');
    }
    
}
   public function file_upload($path){
       if($this->session->userdata('uid')){
        try{
        $user_id= $this->session->userdata('uid');
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
       $this->lconnecttcommunication->FileSizeConvert($size,$user_id,'leadinfo_controller');
        $old_path=$data['upload_data']['full_path'];
        $old_fname = $data['upload_data']['file_name'];
        $new_fname = $path.$data['upload_data']['file_ext'];
        $new_path = str_replace($old_fname, $new_fname, $old_path);
        if (rename($old_path, $new_path)){
            $leadphoto = $new_fname;
            $data = array(
                'lead_logo' => $leadphoto,
            );
            $update = $this->lead->update_leadPhoto($path,$data );
            if ($update == TRUE) {
                echo 1;
            }
          }
        }
         }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
     }else{
            redirect('indexController');
        }
}
  public function display_closed(){
        if($this->session->userdata('uid')){
            try{
             $close = $this->lead->closed_lead();
             echo json_encode($close);
              }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
        }else{
            redirect('indexController');
        }
   }
public function close_lead(){
     if($this->session->userdata('uid'))
     {
         try
        {
            $userid= $this->session->userdata('uid');

            $json = file_get_contents("php://input");
            $data = json_decode($json);

            $close_opportunity_insert_array = array();
            $close_opportunity_update_array = array();
            $opportunity_tasks_array = array();
            $leadid = $data->leadid;
            $reason= $data->reason;
            $remarks = $data->remarks;
            $approach_date = $data->date;
            $name= $data->lead_name;
            $lead_activity= $data->activity;
            $future_activity= $data->future_activity;
            $duration= $data->duration;
            $alert_before= $data->reminder;
            $contact_id= $data->contact_id;
            $task_title= $data->lead_title;
            $dt = date('ymdHis');     
            $lead_reminder_id = '';
            $lead_reminder_id .= $dt;
            $lead_reminder_id = uniqid($lead_reminder_id);
            $mapping_id = uniqid(rand(),TRUE);  
            $newDate = date("Y-m-d H:i:s");
            $event_start_date=date('Y-m-d', strtotime($approach_date));
            $event_start_time = date('H:i', strtotime($approach_date));
            $seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
            $activity_duration = (int)$seconds->getTimestamp();
            $start = new DateTime($approach_date);
            $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
            $event_end = $event_end->format('Y-m-d H:i:s');
            // Fetch all opportunity data;
            $opportunity_data = $this->lead->check_opportunity_owner($leadid,$userid);
            $lead_data      = $this->lead->get_lead_data($leadid);            
            $leadManager    = $lead_data['lead_data'][0]->lead_manager_owner;  

            // Check opportunity tasks, 

            $opportunity_tasks = $this->lead->check_opportunity_tasks($leadid);

            for ($i=0; $i <count($opportunity_data['value']) ; $i++)
            { 

                if($data->reason == 'permanent_loss')
                {
                    $closed_reason = 'permanent loss';
                }
                if($data->reason == 'temporary_loss')
                {
                    $closed_reason ='temporary loss';
                }

                $log_trans_data = array(
                    'mapping_id' => uniqid(rand(),TRUE),
                    'opportunity_id'=> $opportunity_data['value'][$i]->opportunity_id,
                    'lead_cust_id' => $opportunity_data['value'][$i]->lead_cust_id,
                    'from_user_id'=> $userid,
                    'to_user_id'=> $userid,
                    'cycle_id' => $opportunity_data['value'][$i]->cycle_id,
                    'stage_id' => $opportunity_data['value'][$i]->opportunity_stage,
                    'module' => 'sales',
                    'timestamp'=> date('Y-m-d H:i:s'),
                    'sell_type' => $opportunity_data['value'][$i]->sell_type,
                    'remarks' => $data->remarks,
                    'action'=>$closed_reason
                );


                $update_opp_data = array(
                                    'opportunity_id' => $opportunity_data['value'][$i]->opportunity_id,
                                    'closed_status' => '100',
                                    'opportunity_approach_date'=>$data->date,
                                    'closed_reason'=>$data->reason,
                                    'opportunity_date'=>date('Y-m-d')
                                    );

                if ($data->reason == 'temporary_loss') 
                {
                    $dt = date('ymdHis');
                    $lead_reminder_id = '';
                    $lead_reminder_id .= $dt;
                    $lead_reminder_id = uniqid($lead_reminder_id); 

                    $opportunity_tasks = array(
                        'lead_reminder_id'  => $lead_reminder_id.rand(),
                        'lead_id'           => $opportunity_data['value'][$i]->opportunity_id,
                        'rep_id'            => $userid,
                        'leadempid'         => $contact_id,
                        'remi_date'         => $event_start_date,
                        'rem_time'          => $event_start_time,
                        'conntype'          => $future_activity,
                        'status'            => "scheduled",
                        'meeting_start'     => $approach_date,
                        'meeting_end'       => $event_end,
                        'addremtime'        => $alert_before,          
                        'timestamp'         => $newDate,
                        'remarks'           => $remarks,
                        'event_name'        => $task_title,
                        'duration'          => $duration,
                        'type'              => "opportunity",
                        'created_by'        =>$userid,
                        'module_id'         =>'sales'
                    ); 

                    array_push($opportunity_tasks_array, $opportunity_tasks);
                }


                // Closing all opportunity, if lead is closing permanent.

                if ($lead_activity == 1)
                {
                    $this->lead->lead_activities($opportunity_data['value'][$i]->opportunity_id);
                }


                array_push($close_opportunity_update_array, $update_opp_data);
                array_push($close_opportunity_insert_array, $log_trans_data);

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
                'meeting_start'    => $approach_date,
                'meeting_end'      => $event_end,
                'addremtime'       => $alert_before,          
                'timestamp'        => $newDate,
                'remarks'          => $remarks,
                'event_name'       => $task_title,
                'duration'         => $duration,
                'type' => "lead",
                'created_by'=>$userid,
                'module_id'=>'sales'
                    );

           $data1 = array(
                'lead_status' => 4,
                'lead_closed_reason' =>$reason,
             );
            $data3 = array(
                'lead_status' => 3,
                'lead_closed_reason' =>$reason,
                'lead_approach_date' =>$newDate,
             );
            $data4 = array(
                'mapping_id' =>$mapping_id,
                'lead_cust_id' =>$leadid,
                'type'=>'lead',
                'action'=>"closed",
                'module'=>"sales",
                'from_user_id'=>$userid,
                'to_user_id'=>$userid,
                'state'=>1,
                'timestamp'=>$dt,
                'remarks'=>$remarks,
              );

            $notify_id= uniqid($dt);
            $data5= array(
              'notificationID' =>$notify_id,
              'notificationShortText'=>'Lead Closed '.$data->reason,
              'notificationText' =>$name.' lead closed by force close',
              'from_user'=>$userid,
              'to_user'=>$userid,
              'action_details'=>'lead',
              'notificationTimestamp'=>$dt,
              'read_state'=>0,
              'remarks'=>$remarks,
            );

            $closedNotifications = array(
              'notificationID' =>$notify_id.rand(),
              'notificationShortText'=>'Lead Closed '.$data->reason,
              'notificationText' =>$name.' lead closed by force close',
              'from_user'=>$userid,
              'to_user'=>$leadManager,
              'action_details'=>'lead',
              'notificationTimestamp'=>$dt,
              'read_state'=>0,
              'remarks'=>$remarks,
            );
           
            if($reason=='permanent_loss')
            {
               $update1 = $this->lead->permanent_close($leadid,$data1);
               $insert = $this->lead->insert_transaction($data4);
               // opportunity data's.
               if (!empty($close_opportunity_insert_array) && !empty($close_opportunity_update_array)) 
               {
                   $log_data = $this->lead->insert_opp_log($close_opportunity_insert_array);
                   if ($log_data == 1) 
                   {
                       $update_data = $this->lead->update_opp_data($close_opportunity_update_array);
                   }
               }

               if($lead_activity==1)
               {
                  $update4 = $this->lead->lead_activities($leadid);  

                  if (!empty($opportunity_tasks)) 
                        {
                            foreach ($opportunity_tasks as $key => $value) 
                            {
                               $cancel_activites = $this->lead->lead_activities($value->opportunity_id); 
                            }

                        }   
               }
            }

            if($reason=='temporary_loss'){
               $update1 = $this->lead->temporary_close($leadid,$data3);

               $insert = $this->lead->insert_transaction($data4);

               // opportunity data's.

               if (!empty($close_opportunity_insert_array) && !empty($close_opportunity_update_array)) 
               {
                   $log_data = $this->lead->insert_opp_log($close_opportunity_insert_array);
                   if ($log_data == 1) 
                   {
                       $update_data = $this->lead->update_opp_data($close_opportunity_update_array);
                   }

                   $opp_tasks = $this->lead->insert_reminder($opportunity_tasks_array);
               }
               if($lead_activity==1)
               {
                    $update4 = $this->lead->lead_activities($leadid);  

                    if (!empty($opportunity_tasks)) 
                        {
                            foreach ($opportunity_tasks as $key => $value) 
                            {
                               $cancel_activites = $this->lead->lead_activities($value->opportunity_id); 
                            }

                        } 
               }
    		      $insert6 = $this->lead->insert_mytask($data6);
                  
            }

            $notify = $this->lead->notifications($data5);
            $notify1 = $this->lead->notifications($closedNotifications);
            if($notify == TRUE && $insert == TRUE ){
               echo 1;
            }
        }
        catch (LConnectApplicationException $e)
        {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
        }
    }else{
      redirect('indexController');
    }
  }
   public function schedule_logs(){
       if($this->session->userdata('uid')){
        try{
          $lid = $this->input->post('id');
          $logs = $this->lead->schedulelogs($lid);
          echo json_encode($logs);
         }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
     }else{
            redirect('indexController');
        }
}
public function check_opportunity(){ 
       if($this->session->userdata('uid')){
        try{
          $lid = $this->input->post('id');
          $opp_chk = $this->lead->opportunity_check($lid);
          echo json_encode($opp_chk);
         }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
     }else{
            redirect('indexController');
        }
    }
    public function get_activityList(){ 
       if($this->session->userdata('uid')){
        try{
          $activity = $this->lead->activity_list();
          echo json_encode($activity);
         }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
     }else{
            redirect('indexController');
        }
    }
    public function get_contactList($lead_id){ 
       if($this->session->userdata('uid')){
        try{
          $contact = $this->lead->contact_list($lead_id);
          echo json_encode($contact);
         }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
     }else{
            redirect('indexController');
        }
    }
    public function customFieldLead() {
    if($this->session->userdata('uid')){
    try{
        $checkcustomfied=$this->lead->checkcustom();
        echo json_encode($checkcustomfied);
   
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
    }else{
        redirect('indexController');
    }
}
    public function getCustomData(){
        if($this->session->userdata('uid')){
            try{
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $leadid=$data->lead_id;
                $finalArrayCustomer=array();
                 $someArray=array();
                $customdetails=$this->lead->get_customfield($leadid);
               for($i=0;$i<count($customdetails);$i++){
                    if($customdetails[$i]['attribute']!='' && $customdetails[$i]['attribute']!=NULL){
                        $arr_key= json_decode($customdetails[$i]['attributekey']);
                         $arr_val= json_decode($customdetails[$i]['attributevalue']);
                        for($j=0;$j<count($arr_key);$j++){
                        if($customdetails[$i]['attribute_key']==$arr_key[$j]){
                            $someArray=array(
                                'attribute_key'=>$customdetails[$i]['attribute_key'],
                                'attribute_value'=>$arr_val[$j],
                                'attribute_name'=>$customdetails[$i]['attribute_name'],
                                'attribute_validation_string'=>$customdetails[$i]['attribute_validation_string'],
                                'attribute_type'=>$customdetails[$i]['attribute_type'],
                                'module'=>$customdetails[$i]['module'],
                                'id'=>$customdetails[$i]['id']
                            );
                        }
                      }
                    }
                    else{
                       $someArray=array(
                            'attribute_key'=>$customdetails[$i]['attribute_key'],
                            'attribute_value'=>'',
                            'attribute_name'=>$customdetails[$i]['attribute_name'],
                            'attribute_validation_string'=>$customdetails[$i]['attribute_validation_string'],
                            'attribute_type'=>$customdetails[$i]['attribute_type'],
                            'module'=>$customdetails[$i]['module'],
                            'id'=>$customdetails[$i]['id']
                        );
                    } 
            array_push($finalArrayCustomer,$someArray);             
          }
        echo  json_encode($finalArrayCustomer);
            }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                 echo json_encode($errorArray);
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
            $leadCustId = $data->leadid;
            $GLOBALS['$logger']->info("Lead Cust ID : $leadCustId");
            $contactDataArray = $this->lead->fetchAllContacts($leadCustId);
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

    public function DetailsforValidation()  
    {       
        /*$leadNamesArray = $this->lead->getLeadnames();
        $leadContactArray = $this->lead->getLeadContacts();*/
        $ContactArray = $this->lead->getContact();
       /* $resultData = array('leadNameArray'=>$leadNamesArray,'leadContactArray'=>$leadContactArray,'ContactArray'=>$ContactArray);*/
        echo json_encode($ContactArray);
    }

    // Check for the opportunity owner & Stage owner

    public function check_owner()
    {
        if($this->session->userdata('uid'))
        {
            try
            {
                $GLOBALS['$logger']->info('Check opportunity owners function called');
                $leadid = $_POST['id'];
                $GLOBALS['$logger']->info("Lead Cust ID : $leadid");
                $check_opportunity_owner = $this->lead->check_opportunity_owner($leadid,$this->session->userdata('uid'));
                echo json_encode($check_opportunity_owner['result']);          
            }
            catch(LConnectApplicationException $e)
            {
                echo $this->exceptionThrower($e);
            }
        }
        else
        {
            redirect('indexController');
        }
    }

    // Change State of lead.

    public function check_state_lead()
    {
        if($this->session->userdata('uid'))
        {
            try
            {
                $GLOBALS['$logger']->info('contacts for lead customer function called');
                $json = file_get_contents("php://input");
                $data = json_decode($json);

                $userid = $this->session->userdata('uid'); 

                $loss_type          = isset($data->lossType) ? $data->lossType: 'Reopened';

                $remarks            = $data->remarks;
                $reopen             = $data->reopen;
                $cancel_pending     = $data->futureActivityChk; 
                $leadid             = $data->leadId;

                // Fetching Lead Name & Contact Person Data.

                $lead_data      = $this->lead->get_lead_data($leadid);
                $name           = $lead_data['lead_data'][0]->lead_name;
                $leadManager    = $lead_data['lead_data'][0]->lead_manager_owner;  
                $contact_id     = $data->contactType;
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

                // Change Names of loss type.

                    if($loss_type == 'temporary_loss')
                    {
                        $lossType = 'Temporary Loss';
                    }
                    elseif ($loss_type == 'permanent_loss') 
                    {
                        $lossType = 'Permanent Loss';
                    }
                    else
                    {
                        $lossType = 'Reopened';
                    }

                // Notification Data.

                    $notify_id= uniqid(date('ymdHis'));

                    if($loss_type == 'Reopened') 
                    {
                        $notificationShortText = $name.' lead reopened by '.$this->session->userdata('uname');
                    }
                    else
                    {
                        $notificationShortText = $name.' lead closed by force close';
                    }

                    $notifications = array(
                                'notificationID' =>$notify_id,
                                'notificationShortText'=> 'Lead '.$lossType,
                                'notificationText' =>$notificationShortText,
                                'from_user'=>$userid,
                                'to_user'=>$userid,
                                'action_details'=>'lead',
                                'notificationTimestamp'=>date('ymdHis'),
                                'read_state'=>0,
                                'remarks'=>$remarks,
                            );

                    $notifications1 = array(
                                'notificationID' =>$notify_id.rand(),
                                'notificationShortText'=> 'Lead '.$lossType,
                                'notificationText' =>$notificationShortText,
                                'from_user'=>$userid,
                                'to_user'=>$leadManager,
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

                    $approach_date   = $data->date;
                    // if contact_type for activity is not mentioned , add call.
                    $future_activity = $data->futureActivity;

                    $duration        = $data->activityDuration;
                    $alert_before    = $data->alertBefore;
                    $task_title      = isset($data->title) ? $data->title : "Reconnect with ".$name;

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
                        'rep_id'    => $userid,
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
                        'module_id'     => 'sales'
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
                    $insert_notifications1 = $this->lead->notifications($notifications1);

                    //Check opportunity task exits. 




                    if($cancel_pending == true)
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

                    $data = array(
                            'status' => TRUE,
                            'message' => 'lead changed to temporary loss.'
                            );
                    echo json_encode($data);

                }

                // If loss type is permanent

                if ($loss_type == 'permanent_loss') 
                {
                    echo 'here i am'; exit();
                    // Update lead data and insert log.

                    $permanent_loss_data = array(
                    'lead_status' => 4,
                    'lead_closed_reason' => $loss_type,
                    );

                    $update_permanent_data = $this->lead->permanent_close($leadid,$permanent_loss_data);

                    $insert_log_data = $this->lead->insert_transaction($log_data);

                    // Notification data inserting.

                    $insert_notifications = $this->lead->notifications($notifications);
                    $insert_notifications1 = $this->lead->notifications($notifications1);


                    if($cancel_pending == true)
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

                    $data = array(
                            'status' => TRUE,
                            'message' => 'lead changed to permanent loss.'
                            );
                    echo json_encode($data);
                }

                // reopen lead . 
 
                if ($reopen == true) 
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
                    $insert_notifications1 = $this->lead->notifications($notifications1);

                    $data = array(
                            'status' => TRUE,
                            'message' => 'lead is re opened.'
                            );

                    echo json_encode($data);
                }
                       
            }
            catch(LConnectApplicationException $e)
            {
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
        else
        {
            redirect('indexController');
        }
    }

    public function multiple_reopen()
    {
        if($this->session->userdata('uid'))
        {
            try{
                    $GLOBALS['$logger']->debug('multiple reopen function called');
                    $GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));              
                    $user= $this->session->userdata('uid');
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $remarks = $data->remarks;
                    //Lead Arrays,
                    $leads=$data->leads; 
                    // Array to push.            
                    $reopened_lead_data_array   =array();
                    $log_update_data_array      =array();
                    $log_trans_data_array       =array();
                    $notifications_data         = array();

                    // Reopen . 
                    foreach ($leads as $lead)
                    {
                        // Fetching Lead Name & Contact Person Data.

                        $lead_data      = $this->lead->get_lead_data($lead);
                        $name           = $lead_data['lead_data'][0]->lead_name;
                        $mapping_id     = uniqid(rand(),TRUE);

                        $reopened_lead_data =    array(
                                                    'lead_id'=>$lead,
                                                    'lead_closed_reason'=>NULL,
                                                    'lead_status'=>1
                                                    );
                        $log_update_data    =   array(
                                                    'lead_cust_id'=>$lead,
                                                    'state'=>0
                                                    ); 

                        $log_trans_data     =   array(
                                                    'lead_cust_id'=>$lead,
                                                    'state'=>1,
                                                    'action'=>'reopened',
                                                    'from_user_id'=>$user,
                                                    'to_user_id'=>$user,                                
                                                    'type'=>'lead',
                                                    'mapping_id'=>$mapping_id,
                                                    'module'=>'sales',
                                                    'timestamp'=>date('Y-m-d H:i:s')
                                                    ); 
                        // Notifications 
                        $notify_id= uniqid(date('ymdHis'));

                        $notifications =        array(
                                                    'notificationID' =>$notify_id,
                                                    'notificationShortText'=> 'Lead Reopened',
                                                    'notificationText' => $name.' lead reopened by '.$this->session->userdata('uname'),
                                                    'from_user'=>$user,
                                                    'to_user'=>$user,
                                                    'action_details'=>'lead',
                                                    'notificationTimestamp'=>date('ymdHis'),
                                                    'read_state'=>0,
                                                    'remarks'=>$remarks,
                                                );

                            array_push($reopened_lead_data_array,$reopened_lead_data);    
                            array_push($log_update_data_array, $log_update_data); 
                            array_push($log_trans_data_array, $log_trans_data); 
                            array_push($notifications_data, $notifications);                       
                    } 

                    $reopened = $this->lead->lead_reopen($reopened_lead_data_array,$log_update_data_array,$log_trans_data_array);
                    // Inserting Notifications.
                    $insert_notifications = $this->lead->insertNotificationData($notifications_data);

                    if ($reopened == true) 
                    {
                        $data = array(
                            'status' => TRUE,
                            'message' => 'lead is re opened.'
                            );

                        echo json_encode($data['status']);
                    }

                $GLOBALS['$logger']->debug('response received successfully');
                $GLOBALS['$logger']->debug($data);     
            }
            catch (LConnectApplicationException $e)  
            {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                    'errorCode' => $e->getErrorCode(), 
                    'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                 echo json_encode($errorArray);
            }
        }else{
            redirect('indexController');
        }
        
    }

    public function fetchActivity()
    {
        if($this->session->userdata('uid'))
        {
            try {
                $var = $this->mytask->fetch_activity();
                $contactArray = $this->manager->fetchContactsForLead($leadid);
                $arr=array('activityArray'=>$var, 'contactArray'=>$contactArray);
                echo json_encode($arr); 
            }
            catch(LConnectApplicationException $e) 
            {
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
        else
        {
            redirect('indexController');
        }   
    }

    
 }
?>