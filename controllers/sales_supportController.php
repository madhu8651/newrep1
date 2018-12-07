<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_supportController');

class sales_supportController extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('sales_supportModel','support');
    }
    public function index(){
        if($this->session->userdata('uid')){
            try{
              $this->load->view('sales_newrequest_view');
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
    public function inprogres_request(){
        if($this->session->userdata('uid')){
            try{
              $this->load->view('sales_inprogress_request');
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
    public function stage_view(){
        if($this->session->userdata('uid')){
            try{
              $this->load->view('sales_request_stageview');
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

 public function closed_request(){
        if($this->session->userdata('uid')){
            try{
              $this->load->view('sales_closed_request');
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
    public function get_processType(){
        if($this->session->userdata('uid')){
            try{
               $userid= $this->session->userdata('uid');
               $contact_list=$this->support->getProcessType($userid);
               echo json_encode($contact_list);
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
    
      public function get_process_cycle(){
        if($this->session->userdata('uid')){
            try{

               $json = file_get_contents("php://input");
               $data = json_decode($json);
               $request_for = $data->request_for; 
               $opportunity_ids = $data->opportunity_id;   
               $customer_id = $data->customer_id;
               $product_id = $data->product_id;
               $process_type= $data->process_type;
               $client_contact = $data->client_contact;
               $name = $data->name;
               $for= $data->for;
               $crictical= $data->crictical;
               $tat = $data->tat;
               $remarks = $data->remarks;
                $request = '';
                $dt = date('YmdHis');
                $request .= $dt;
                $request_id = uniqid($request);
                $data1=array(
                    'request_name'=>$name,
                    'request_for'=>$for,
                    'critical'=>$crictical,
                    'tat'=>$tat,
                    'remarks'=>$remarks,
                    'contacts'=>$client_contact,
                    'request_for'=>$request_for,
                    'opportunity_ids'=>$opportunity_ids,
                    'customer_id'=>$customer_id,
                    'product'=>$product_id,
                    'process'=>$process_type,
                    'remarks'=>$remarks,
                    'request_id'=>$request_id,
               );
                if($request_for=='opportunity'){
                   $qualifier_ids=$this->support->chk_parameters_opp($opportunity_ids,$process_type,$product_id,$data1);
                   $returnArray= array(
                        'message' => 'Answer this qualifier to proceed further.', 
                        'status' => false,
                        'qualifier' => true,
                        'qualifier_data' => $qualifier_ids['qualifier'],
                        'request_data' => $data1,
                        'details' => $qualifier_ids['details']
                    );
                    echo json_encode($returnArray);
                }elseif ($request_for=='customer'){
                    $allocation_list=$this->support->chk_parameters_cust($customer_id,$process_type,$product_id);
                    if($allocation_list==0 || $allocation_list==1){
                       echo $allocation_list;
                   }else{
                        echo json_encode($allocation_list);
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
    
    public function get_customers(){
        if($this->session->userdata('uid')){
            try{
                $customerlist=$this->support->getCustomers();
                echo json_encode($customerlist);exit;
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
    public function get_opportunitylist(){
        if($this->session->userdata('uid')){
            try{
                $oppo_list=$this->support->getOpportunities();
                echo json_encode($oppo_list);
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
    public function get_contactsforCustomer(){
        if($this->session->userdata('uid')){
            try{
               $json = file_get_contents("php://input");
               $data = json_decode($json);
               $cust_id = $data->customerid;                
               $contact_list=$this->support->getContactList($cust_id);
               echo json_encode($contact_list);
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
      public function get_contactsforOpportunity(){
        if($this->session->userdata('uid')){
            try{
               $json = file_get_contents("php://input");
               $data = json_decode($json);
               $oppo_id = $data->oppo_id;  
               $contact_list=$this->support->getContactListopp($oppo_id);
               echo json_encode($contact_list);
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
     public function get_OpportunityProducts() {
        if($this->session->userdata('uid')){
            try{
                $json = file_get_contents("php://input");
               $data = json_decode($json);
               $oppo_id = $data->oppo_id;   
               $getproductList=$this->support->getproductList($oppo_id);
               echo json_encode($getproductList);
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
     public function get_CustomerProduct() {
        if($this->session->userdata('uid')){
            try{
               $userid= $this->session->userdata('uid');
               $json = file_get_contents("php://input");
               $data = json_decode($json);
               $cust_id = $data->customerid;   
               $getproductList=$this->support->customerproduct($cust_id,$userid);
               echo json_encode($getproductList);
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
    public function verify_qualifier() {
        if($this->session->userdata('uid')){
            try{
                $userid =$this->session->userdata('uid');
                $mgr_module= $this->session->userdata('manager');
                $sale_module= $this->session->userdata('sales');
                if($mgr_module=='MA1705221245451234567891011' && $sale_module=='SA1705221245591234567891011'){
                    $managerid=$userid;
                }else{
                     $managerid= $this->session->userdata('reporting_to');
                }
               $json = file_get_contents("php://input");
               $data = json_decode($json);
               $cycle_id = $data->cycle;
               $opp_cust_id = $data->opp_cust_id;   
               $stage_id = $data->stage_id;
               $industry = $data->industry;
               $location= $data->location;
               $request_id = $data->request_id;
               $request_name = $data->request_name;
               $request_for= $data->request_for;
               $contact_id= $data->contact_id;
               $tat = $data->tat;
               $remarks = $data->remarks;
               $critical= $data->critical;
               $product = $data->product;
               $process = $data->process;
               $question_data = $data->que_date;
               $type1_2=$data->type1_2;
               
                $quali_id = '';
                $dt = date('YmdHis');
                $quali_id .= $dt;
                $quali_trans_id = uniqid($quali_id);  
                
                $data1=array(
                    'rep_id'=>$userid,
                    'qualifier_tran_id'=>$quali_trans_id,
                    'leadid'=> $opp_cust_id,
                    'stageid'=> $stage_id,
                    'opportunity_id'=>$request_id,
                    'attempt_data'=> json_encode($question_data),
                    'timestamp'=> $dt,
                );
                $data2=array(
                    'request_id'=>$request_id,
                    'request_name'=> $request_name,
                    'opp_cust_id'=> $opp_cust_id,
                    'request_contact'=>implode(":",$contact_id),
                    'request_product'=> $product,
                    'request_industry'=> $industry,
                    'request_location'=>$location,
                    'request_stage'=> $stage_id,
                    'cycle_id'=> $cycle_id,
                    'request_tat'=> $tat,
                    'process_type'=>$process,
                    'owner_id'=> $userid,
                    'created_by'=> $userid,
                    'manager_owner_id'=> $managerid,
                    'owner_manager_status'=>2,
                    'owner_status'=> 2,
                    'created_timestamp'=> $dt,
                    'cricticality'=> $critical,
                    'request_for'=> $request_for,
                    'remarks'=> $remarks
                );
                $action=array('created','ownership_accpted');
                $data3=array();
                for($j=0;$j <count($action);$j++){
                    $data1_3=array(
                         'mapping_id'=>uniqid(rand(),TRUE),
                         'request_id'=> $request_id,
                         'opp_cust_id'=> $opp_cust_id,
                         'from_user_id'=>$userid,
                         'to_user_id'=> $userid,
                         'cycle_id'=> $cycle_id,
                         'stage_id'=>$stage_id,
                         'process_type'=>$process,
                         'module'=> 'sales',
                         'timestamp'=> $dt,
                         'action'=> $action[$j],
                         'state'=> 1
                    );
                    array_push($data3,$data1_3) ; 
                }
               $getproductList=$this->support->insert_data($data1,$data2,$data3,$type1_2);
               echo json_encode($getproductList);
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
    
     public function get_ticketDetails() {
        if($this->session->userdata('uid')){
            try{
               $userid= $this->session->userdata('uid');
               $getproductList=$this->support->fetch_new_request($userid);
               echo json_encode($getproductList);
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
    public function inprogress_ticketDetails() {
        if($this->session->userdata('uid')){
            try{
                // Accepted request data and contact details of request id's.
               $userid= $this->session->userdata('uid');
               $getproductList=$this->support->fetch_inprogress_request($userid);
               echo json_encode($getproductList);
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
    public function closed_ticketDetails() {
        if($this->session->userdata('uid')){
            try{
               $userid= $this->session->userdata('uid');
               $getproductList=$this->support->get_closed_tickets($userid);
               echo json_encode($getproductList);
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

    // Accept Assigned Support Request.
    public function accept_multiple() {
        if($this->session->userdata('uid')){
            try{
                $userid= $this->session->userdata('uid');
                $json = file_get_contents("php://input");
                $data=json_decode($json);
                $dt = date('ymdHis');
                $req_id= $data->reject_data;
                $count =count($req_id);
                $mapping_id = uniqid(rand(),TRUE);                
                $rejectedleads=array();
                // Multiple Accept 
                for($i=0;$i<$count;$i++){
                    //checking owner status of the request id if it is assigned or not. (status = 1).
                    //if assigned sending cycle_id,request_stage,opp_cust_id,process_type data in response.
                    $req_owner= $this->support->rep_owner($req_id[$i]); 
                    // if data - inserting owner_id and status and in user_map.
                    if($req_owner!=0){
                        $data1= array(
                           'owner_id'=>$userid,
                           'owner_status'=>2
                        );
                        $data2= array(
                         'mapping_id' =>$mapping_id,
                         'request_id' =>$req_id[$i],
                         'cycle_id' =>$req_owner['cycle_id'],
                         'stage_id'=>$req_owner['request_stage'],
                         'process_type'=>$req_owner['process_type'],
                         'opp_cust_id'=>$req_owner['opp_cust_id'],
                         'state' =>1,
                         'action'=>"ownership_accepted",
                         'module'=>"sales",
                         'from_user_id'=>$userid,
                         'to_user_id'=>$userid,
                         'timestamp'=>$dt,
                         );
                       $update = $this->support->accept_request($req_id[$i],$data1);
                       // Updating user_map to state 0.
                       $update2 = $this->support->update_transaction($req_id[$i]);
                       // Inserting to user_map.
                       $update1 = $this->support->insert_transaction($data2);
                    }else{
                        // Final array of id's.
                         array_push($rejectedleads,$req_id[$i]); 
                    }
                }
                if(count($rejectedleads)>0){
                    echo 1;
                }else{
                    echo 0;
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

   public function reject_multiple(){
    // Same as LeadCust.
    if($this->session->userdata('uid')){
        try{
          $userid= $this->session->userdata('uid');
          $json = file_get_contents("php://input");
          $data=json_decode($json);
          $dt = date('ymdHis');
          $req_id = $data->reject_data;
          $remarks = $data->rej_remarks;
          $rejected_ids=explode(',',$req_id);
          $count =count($rejected_ids);
          $rejectedleads=array();
          $rejectcount=count($rejectedleads);
            for($i=0;$i<$count;$i++){
                // fetching all the support data of request id.
                $req_owner= $this->support->rep_owner($rejected_ids[$i]); 
                if($req_owner!=0){
                    // if data adding data to usermap as rejected with status, adding usermap data and updating status in support_opportunity_details and usermap.
                    $check_assign= $this->support->last_reject($rejected_ids[$i],$remarks); 
                }else{
                    array_push($rejectedleads,$rejected_ids[$i]); 
                }
            }
            echo 1;
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
public function get_contacts(){
    if($this->session->userdata('uid')){
        try {
            $json = file_get_contents("php://input");
            $data=json_decode($json);
            $opp_id = $data->opp_id;
            $req_for = $data->req_for;
            if($req_for=='customer'){
               $contact_list=$this->support->getContactList($opp_id);
               echo json_encode($contact_list);
            }elseif($req_for=='opportunity'){
               $data1=$this->support->opportunity_contacts($opp_id);
               echo json_encode($data1);
            }
        }
        catch(LConnectApplicationException $e){
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
 public function update_request(){
     if($this->session->userdata('uid')){
        try {
            // updating request using request_id,cycle_id,opp_cust_id,stage_id and process type.
            $userid= $this->session->userdata('uid');
            $json = file_get_contents("php://input");
            $data=json_decode($json);
            $dt = date('ymdHis');
            $req_name = $data->req_name;
            $con_name = $data->con_name;
            $critc = $data->critc;
            $remarks = $data->remarks;
            $comm_tat = $data->comm_tat;
            $req_id = $data->req_id;
            $edit_cycle = $data->edit_cycle;
            $edit_oppo = $data->edit_oppo;
            $edit_stage = $data->edit_stage;
            $edit_process = $data->edit_process;
            // support details.
            $data1=array(
                'request_name'=> $req_name,
                'request_contact'=>implode(':',$con_name),
                'request_tat'=>$comm_tat,
                'cricticality'=>$critc,
            );
            // support_usermap.
            $data2= array(
                     'mapping_id' =>uniqid(rand(),TRUE),
                     'request_id' =>$req_id,
                     'cycle_id' =>$edit_cycle,
                     'stage_id'=>$edit_stage,
                     'process_type'=>$edit_process,
                     'opp_cust_id'=>$edit_oppo,
                     'state' =>1,
                     'action'=>"edited",
                     'module'=>"sales",
                     'from_user_id'=>$userid,
                     'to_user_id'=>$userid,
                     'timestamp'=>$dt,
                     'remarks'=>$remarks
                     );
                $update=$this->support->update_details($data1,$req_id);
                $insert=$this->support->insert_transaction($data2);
                if($update==true && $insert== true ){
                    echo 1;
                }
          }
        catch(LConnectApplicationException $e){
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
public function close_request(){
    if($this->session->userdata('uid')){
        try {
            $userid= $this->session->userdata('uid');
            $json = file_get_contents("php://input");
            $data=json_decode($json);
            $remarks=$data->remarks;
            $req_id=$data->req_id;
            //close status support = 100
            $insert=$this->support->close_request_details($remarks,$req_id,$userid);
            echo 1;
        }
        catch(LConnectApplicationException $e){
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

public function request_details(){
    if($this->session->userdata('uid')){
        try {
            // On each view . 
            $json = file_get_contents("php://input");
            $data=json_decode($json);
            $request_id=$data->request_id;
            $userid= $this->session->userdata('uid');
            $GLOBALS['$logger']->info('Fetching Request Details of request_id '.$request_id.' by user '.$userid.'.');
            //request userid assigned or manager assigned or progressed based on usermap data.
            $GLOBALS['$logger']->info('Checking the Request Owners for View');
            $canSeeStatus = $this->support->can_see($userid, $request_id);
            $GLOBALS['$logger']->info('Request Owners Data');
            $GLOBALS['$logger']->info($canSeeStatus);

            if($canSeeStatus!=0){
                // fetching user details of request. Fetching all current and next stage // contact_details.
                $data1=$this->support->get_request_details($request_id);
                $GLOBALS['$logger']->info('Request Details Data');
                $GLOBALS['$logger']->info($data1);
                echo json_encode($data1);
            }else{
                $GLOBALS['$logger']->info('Request Details Data');
                echo 0;
            }
        }catch(LConnectApplicationException $e){
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
public function get_attributue_val(){
    if($this->session->userdata('uid')){
        try {
            //Progressing Stage - Updating.
            $userid = $this->session->userdata('uid');
            $GLOBALS['$logger']->info('Progressing Stage function by '.$userid); 
            $json = file_get_contents("php://input");
            $data=json_decode($json);
            $request_id=$data->req_id;
            $GLOBALS['$logger']->info("Request Id ".$request_id);
            $stage_id=$data->stage_id;
            $GLOBALS['$logger']->info("Stage Id ".$stage_id);
            $GLOBALS['$logger']->info("Fetching attributes of request_id and stage_id ");
            $attributes = $this->support->get_attributes($request_id,$stage_id);
            $GLOBALS['$logger']->info("Response");
            $GLOBALS['$logger']->info($attributes);
            echo json_encode($attributes);
        }catch(LConnectApplicationException $e){
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

public function save_stage_attributes_alt(){
    if($this->session->userdata('uid')){
        try {
            $attibute=array();
            $custom_name=array();
            $custom_val=array();
            $attribute_log=array();
            $custom_data=array();
            $dt = date('YmdHis');
            $file_list=$_FILES['userfile']['name'];
            $files_property= $_FILES;
            $timeframe = $this->input->post('timeframe');
            $timeframe_value 	= $this->input->post('timeframe_value');
            $max_time=$timeframe_value.'-'.$timeframe;
            $expected_close_date =$this->input->post('expected_close_date');
            $request_id =$this->input->post('req_id');
            $stage_id =$this->input->post('sta_id');
            $opp_cust_id = $this->input->post('oppor_id');
            $remarks = $this->input->post('remarks');
            $mapping_id=uniqid(rand());
            $upload_doc=array(
                'stage_id'=>$stage_id,
                'opportunity_id'=>$request_id,
                'lead_id'=>$opp_cust_id,
                'created_date'=>$dt,
                'request_id'=>$request_id,
                'remarks'=>$remarks,
                'files'=>$files_property,
                'mapping_id'=>$mapping_id
            ); 
                $file_data = $this->support->oppo_file_upload($upload_doc);
                if ($file_data!=1){
                    echo json_encode(array('errors' => $file_data, 'status' => false));
		}else{
                    if($timeframe!='' || $timeframe!=null){
                        $attibute1 = array('max_permission' => $max_time);
                    }else{
                        $attibute1 = array('max_permission' => '');
                    }
                   
                    if($expected_close_date!='' || $expected_close_date!=null){
                        $attibute = array('expected_close_date' => $expected_close_date);
                    }else{
                        $attibute = array('expected_close_date' => '');
                    }
                    $aaa=$attibute1+$attibute;
                    $custom_fields = $this->support->get_custom_names();
                    for($i=0;$i<count($custom_fields);$i++){
                       $custom_val=$this->input->post($custom_fields[$i]->lookup_id);
                       if(isset($custom_val)){
                           $custom_id=$custom_fields[$i]->lookup_value;
                           $keyval = array($custom_id => $custom_val);
                          $custom_name+= $keyval;
                       }
                    }
                    $result=array_merge($aaa,$custom_name);
                    foreach ($result as $key => $value) {
                        $attri_log=array(
                            'mapping_id' =>$mapping_id,
                            'stage_id' =>$stage_id,
                            'time_stamp' =>$dt,
                            'request_id' =>$request_id,
                            'opp_cust_id'=>$opp_cust_id,
                            'attribute_name'=>$key,
                            'attribute_value'=>$value,
                            'remarks' =>$remarks,
                            'time_stamp' =>$dt,
                        );
                        $data1=array(
                            'mapping_id'=>$mapping_id,
                            'support_stage_id'=>$stage_id,
                            'support_attribute_value'=>$value,
                            'support_attribute_remarks'=>$remarks,
                            'support_attribute_name'=>$key,
                            'request_id'=>$request_id,
                            'timestamp'=>$dt
                        );
                        array_push($attribute_log,$attri_log);
                        array_push($custom_data,$data1);
                    }
                    //print_r($attribute_log);
                   // print_r($custom_data);exit;
                    
                    $insert=$this->support->insert_log($attribute_log);
                    $changed_matrix=array();
                    if($insert=true){
                        $insert1=$this->support->fetch_log_attributes($request_id,$stage_id);
                       // print_r($insert1);exit;
                            if($insert1== null){
                                $insert1=$this->support->insert_custom_attribute($custom_data);
                                echo true;
                            }else{
                                for($i=0;$i<count($attribute_log);$i++){
                                    for($j=0;$j<count($insert1);$j++){                           
                                        if($attribute_log[$i]['attribute_name'] == $insert1[$j]->support_attribute_name){
                                            if($attribute_log[$i]['attribute_value']!= $insert1[$j]->support_attribute_value){
                                                $changed_value=array('attribute_value'=>$attribute_log[$i]['attribute_value'],
                                                                    'attribute_name'=>$attribute_log[$i]['attribute_name']
                                                );
                                                    array_push($changed_matrix,$changed_value);
                                            }
                                        }
                                    }
                                }
                                if(count($changed_matrix)>0){
                                    $insert2=$this->support->update_attribute($changed_matrix,$remarks,$request_id,$stage_id);
                                    if($insert2==true){
                                        echo json_encode(array('errors' => $file_data['errors'], 'status' => true));
                                    }
                                }else{
                                    echo json_encode(array('errors' => $file_data['errors'], 'status' => true));
                                }
                            }
                    }
                }
    }catch(LConnectApplicationException $e){
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
public function save_stage_attributes(){
   if($this->session->userdata('uid')){
    try {
        // Progress Checking.
        $userid= $this->session->userdata('uid');
        $attibute=array();
        $attibute1=array();
        $custom_name=array();
        $custom_val=array();
        $attribute_log=array();
        $custom_data=array();
        $dt = date('YmdHis');
        $files_property= $_FILES; 
        $file_list=$_FILES['userfile']['name'];
        $request_id =$this->input->post('req_id');
        $stage_id =$this->input->post('sta_id');
        $qualifier_data = $this->support->get_qualifier($stage_id);
        $file_chk_status= $this->support->check_files_for_stage($stage_id,$request_id);
        if($file_chk_status==0){
           if($file_list[0]!=""){
                $files_data=1;
            }else{
                $files_data=0;
            }
        }else{
            $files_data=$file_chk_status;
        }
        if($qualifier_data==0){
            $timeframe = $this->input->post('timeframe');
            $timeframe_value 	= $this->input->post('timeframe_value');
            $max_time=$timeframe_value.'-'.$timeframe;
            $expected_close_date =$this->input->post('expected_close_date');
            $cycle_id	= $this->input->post('cyc_id');
            $opp_cust_id = $this->input->post('oppor_id');
            $remarks = $this->input->post('remarks');
            $allocation = $this->input->post('allocation');
            $process = $this->input->post('process_id');
            $next_stage_id = $this->input->post('next_stage_id');
            $next=explode('-',$next_stage_id);

            $mapping_id=uniqid(rand());
            $upload_doc=array(
                'stage_id'=>$stage_id,
                'opportunity_id'=>$request_id,
                'lead_id'=>$opp_cust_id,
                'created_date'=>$dt,
                'request_id'=>$request_id,
                'remarks'=>$remarks,
                'files'=>$files_property,
                'mapping_id'=>$mapping_id
            ); 
            $data6= array(
                'mapping_id' =>$mapping_id,
                'request_id' =>$request_id,
                'cycle_id' =>$cycle_id,
                'stage_id'=>$stage_id,
                'process_type'=>$process,
                'opp_cust_id'=>$opp_cust_id,
                'state' =>1,
                'action'=>"stage_progressed",
                'module'=>"sales",
                'from_user_id'=>$userid,
                'to_user_id'=>$userid,
                'timestamp'=>$dt,
                'remarks'=>$remarks
                );
                $data4=array();
                $allocation_list=explode(':',$allocation);
                if(count($allocation_list>0)){
                    if(in_array($userid, $allocation_list)){
                        if($next[1]==100){
                            $action='stage_closed';
                        }else{
                             $action='ownership_accpted';
                        }
                        $data2= array(
                        'mapping_id' =>$mapping_id,
                        'request_id' =>$request_id,
                        'cycle_id' =>$cycle_id,
                        'stage_id'=>$next[0],
                        'process_type'=>$process,
                        'opp_cust_id'=>$opp_cust_id,
                        'state' =>1,
                        'action'=>$action,
                        'module'=>"sales",
                        'from_user_id'=>$userid,
                        'to_user_id'=>$userid,
                        'timestamp'=>$dt,
                        'remarks'=>$remarks
                        );
                        
                        array_push($data4,$data2);
                        $data3=array(
                            'request_stage'=>$next[0],
                            'cycle_id'=> $cycle_id,
                            'owner_id'=> $userid,
                            'owner_status'=> 2,
                            'remarks'=> $remarks
                        );
                    }else{
                        $count=count($allocation_list);
                         $module_list = $this->support->get_allocation_modules($allocation_list);
                        for($l=0;$l<count($module_list);$l++){
                            $module_id=$module_list[$l]->module_id;
                            $list= json_decode($module_id);
                            $sales_module=  $list->sales;
                            $mgr_module=  $list->Manager;
                            if($sales_module != '0'){
                                $data2= array(
                                'mapping_id' =>$mapping_id,
                                'request_id' =>$request_id,
                                'cycle_id' =>$cycle_id,
                                'stage_id'=>$next[0],
                                'process_type'=>$process,
                                'opp_cust_id'=>$opp_cust_id,
                                'state' =>1,
                                'action'=>"ownership_assigned",
                                'module'=>"sales",
                                'from_user_id'=>$userid,
                                'to_user_id'=>$allocation_list[$l],
                                'timestamp'=>$dt,
                                'remarks'=>$remarks
                                );
                                array_push($data4,$data2);
                            }
                            if($mgr_module != '0'){
                               $owner_manager_status=1;
                                    $data2= array(
                                    'mapping_id' =>$mapping_id,
                                    'request_id' =>$request_id,
                                    'cycle_id' =>$cycle_id,
                                    'stage_id'=>$next[0],
                                    'process_type'=>$process,
                                    'opp_cust_id'=>$opp_cust_id,
                                    'state' =>1,
                                    'action'=>"ownership_assigned",
                                    'module'=>"manager",
                                    'from_user_id'=>$userid,
                                    'to_user_id'=>$allocation_list[$l],
                                    'timestamp'=>$dt,
                                    'remarks'=>$remarks
                                    );
                                    array_push($data4,$data2);
                            }
                        }
                        $data3=array(
                            'request_stage'=>$next[0],
                            'cycle_id'=> $cycle_id,
                            'owner_id'=>null,
                            'owner_status'=> 2,
                            'remarks'=> $remarks
                            );
                    }
                }
               $file_data = $this->support->oppo_file_upload($upload_doc);
                if ($file_data!=1){
                    echo json_encode(array('errors' => $file_data, 'status' => false));
		}else{
                    if($timeframe!='' || $timeframe!=null){
                        $attibute1 = array('max_permission' => $max_time);
                    }
                  if($expected_close_date!='' || $expected_close_date!=null){
                       $attibute = array('expected_close_date' => $expected_close_date);
                    }
                    $aaa=$attibute1+$attibute;
                    $custom_fields = $this->support->get_custom_names();
                    for($i=0;$i<count($custom_fields);$i++){
                       $custom_val=$this->input->post($custom_fields[$i]->lookup_id);
                       if(isset($custom_val)){
                           $custom_id=$custom_fields[$i]->lookup_value;
                           $keyval = array($custom_id => $custom_val);
                          $custom_name+= $keyval;
                       }
                    }
                    $result=array_merge($aaa,$custom_name);
                   foreach ($result as $key => $value) {
                       $attri_log=array(
                            'mapping_id' =>$mapping_id,
                            'stage_id' =>$stage_id,
                            'time_stamp' =>$dt,
                            'request_id' =>$request_id,
                            'opp_cust_id'=>$opp_cust_id,
                            'attribute_name'=>$key,
                            'attribute_value'=>$value,
                            'remarks' =>$remarks,
                            'time_stamp' =>$dt,
                        );
                        $data1=array(
                            'mapping_id'=>$mapping_id,
                           'support_stage_id'=>$stage_id,
                            'support_attribute_value'=>$value,
                            'support_attribute_remarks'=>$remarks,
                           'support_attribute_name'=>$key,
                            'request_id'=>$request_id,
                            'timestamp'=>$dt
                        );
                        
                        array_push($attribute_log,$attri_log);
                        array_push($custom_data,$data1);
                   }
                    $insert=$this->support->insert_log($attribute_log);
                    $changed_matrix=array();
                    if($insert=true){
                        $insert1=$this->support->fetch_log_attributes($request_id,$stage_id);
                            if($insert1== null){
                                $insert1=$this->support->insert_custom_attribute($custom_data);
                                echo true;
                            }else{
                                for($i=0;$i<count($attribute_log);$i++){
                                    for($j=0;$j<count($insert1);$j++){                           
                                        if($attribute_log[$i]['attribute_name'] == $insert1[$j]->support_attribute_name){
                                            if($attribute_log[$i]['attribute_value']!= $insert1[$j]->support_attribute_value){
                                                $changed_value=array('attribute_value'=>$attribute_log[$i]['attribute_value'],
                                                                    'attribute_name'=>$attribute_log[$i]['attribute_name']
                                                );
                                                    array_push($changed_matrix,$changed_value);
                                            }
                                        }
                                    }
                                }
                                if(count($changed_matrix)>0){
                                    $insert2=$this->support->update_attribute($changed_matrix,$remarks,$request_id,$stage_id);
                                }
                            }
                    }
                    $insert5=$this->support->next_stage_assign($data6,$data3,$data4,$request_id);
                    if($insert5==true){
                        echo 1;
                    }
                }
            }else{
                $finalArray['fileCheck'] = $files_data;
                $finalArray['qualifier'] = $qualifier_data;
                echo json_encode($finalArray);
            }
    }catch(LConnectApplicationException $e){
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
public function progress_check(){
    if($this->session->userdata('uid')){
        try {
           $json = file_get_contents("php://input");
            $data = json_decode($json);
            $stage_id=$data->stage_id;
            $next_stage_id=$data->next_stage_id;
            $req_id = $data->req_id;
            $qualifier_id = $data->qualifier_id;
            $finalArray = array();
            if(isset($qualifier_id)){
                $qualifier_data = $this->support->get_qualifier($qualifier_id);
            }else{
                $qualifier_data=0;
            }
            $files_data = $this->support->check_files_for_stage($stage_id,$req_id);
	    $finalArray['fileCheck'] = $files_data;
            $finalArray['qualifier'] = $qualifier_data;
            echo json_encode($finalArray); 
        }catch(LConnectApplicationException $e){
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
public function progress_nextstage(){
    if($this->session->userdata('uid')){
        try {
            $userid= $this->session->userdata('uid');
            $attibute=array();
            $custom_name=array();
            $custom_val=array();
            $attribute_log=array();
            $custom_data=array();
            $dt = date('YmdHis');
            $files_property= $_FILES;
            $timeframe = $this->input->post('timeframe');
            $timeframe_value 	= $this->input->post('timeframe_value');
            $max_time=$timeframe_value.'-'.$timeframe;
            $expected_close_date =$this->input->post('expected_close_date');
            $request_id =$this->input->post('req_id');
            $cycle_id	= $this->input->post('cyc_id');
            $stage_id =$this->input->post('sta_id');
            $opp_cust_id = $this->input->post('oppor_id');
            $remarks = $this->input->post('remarks');
            $allocation = $this->input->post('allocation');
            $process = $this->input->post('process_id');
            $next_stage_id = $this->input->post('next_stage_id');
            $next=explode('-',$next_stage_id);

            $mapping_id=uniqid(rand());
            $upload_doc=array(
                'stage_id'=>$stage_id,
                'opportunity_id'=>$request_id,
                'lead_id'=>$opp_cust_id,
                'created_date'=>$dt,
                'request_id'=>$request_id,
                'remarks'=>$remarks,
                'files'=>$files_property,
                'mapping_id'=>$mapping_id
            ); 
            $data6= array(
                'mapping_id' =>$mapping_id,
                'request_id' =>$request_id,
                'cycle_id' =>$cycle_id,
                'stage_id'=>$stage_id,
                'process_type'=>$process,
                'opp_cust_id'=>$opp_cust_id,
                'state' =>1,
                'action'=>"stage_progressed",
                'module'=>"sales",
                'from_user_id'=>$userid,
                'to_user_id'=>$userid,
                'timestamp'=>$dt,
                'remarks'=>$remarks
                );
                $data4=array();
                $allocation_list=explode(':',$allocation);
                if(count($allocation_list>0)){
                    if(in_array($userid, $allocation_list)){
                        if($next[1]==100){
                            $action='stage_closed';
                        }else{
                             $action='ownership_accpted';
                        }
                        $data2= array(
                        'mapping_id' =>$mapping_id,
                        'request_id' =>$request_id,
                        'cycle_id' =>$cycle_id,
                        'stage_id'=>$next[0],
                        'process_type'=>$process,
                        'opp_cust_id'=>$opp_cust_id,
                        'state' =>1,
                        'action'=>$action,
                        'module'=>"sales",
                        'from_user_id'=>$userid,
                        'to_user_id'=>$userid,
                        'timestamp'=>$dt,
                        'remarks'=>$remarks
                        );
                        
                        array_push($data4,$data2);
                        $data3=array(
                            'request_stage'=>$next[0],
                            'cycle_id'=> $cycle_id,
                            'owner_id'=> $userid,
                            'owner_status'=> 2,
                            'remarks'=> $remarks
                        );
                    }else{
                        $count=count($allocation_list);
                         $module_list = $this->support->get_allocation_modules($allocation_list);
                        for($l=0;$l<count($module_list);$l++){
                            $module_id=$module_list[$l]->module_id;
                            $list= json_decode($module_id);
                            $sales_module=  $list->sales;
                            $mgr_module=  $list->Manager;
                            if($sales_module != '0'){
                                $data2= array(
                                'mapping_id' =>$mapping_id,
                                'request_id' =>$request_id,
                                'cycle_id' =>$cycle_id,
                                'stage_id'=>$next[0],
                                'process_type'=>$process,
                                'opp_cust_id'=>$opp_cust_id,
                                'state' =>1,
                                'action'=>"ownership_assigned",
                                'module'=>"sales",
                                'from_user_id'=>$userid,
                                'to_user_id'=>$allocation_list[$l],
                                'timestamp'=>$dt,
                                'remarks'=>$remarks
                                );
                                array_push($data4,$data2);
                            }
                            if($mgr_module != '0'){
                               $owner_manager_status=1;
                                    $data2= array(
                                    'mapping_id' =>$mapping_id,
                                    'request_id' =>$request_id,
                                    'cycle_id' =>$cycle_id,
                                    'stage_id'=>$next[0],
                                    'process_type'=>$process,
                                    'opp_cust_id'=>$opp_cust_id,
                                    'state' =>1,
                                    'action'=>"ownership_assigned",
                                    'module'=>"manager",
                                    'from_user_id'=>$userid,
                                    'to_user_id'=>$allocation_list[$l],
                                    'timestamp'=>$dt,
                                    'remarks'=>$remarks
                                    );
                                    array_push($data4,$data2);
                            }
                        }
                        $data3=array(
                            'request_stage'=>$next[0],
                            'cycle_id'=> $cycle_id,
                            'owner_id'=>null,
                            'owner_status'=> 2,
                            'remarks'=> $remarks
                            );
                    }
                }
                
               $file_data = $this->support->oppo_file_upload($upload_doc);
                if ($file_data!=1){
                    echo json_encode(array('errors' => $file_data, 'status' => false));
		}else{
                    if($timeframe!='' || $timeframe!=null){
                        $attibute1 = array('max_permission' => $max_time);
                    }else{
                          $attibute1=array();
                    }
                  if($expected_close_date!='' || $expected_close_date!=null){
                       $attibute = array('expected_close_date' => $expected_close_date);
                    }else{
                          $attibute=array();
                    }
                    $aaa=$attibute1+$attibute;
                    $custom_fields = $this->support->get_custom_names();
                    for($i=0;$i<count($custom_fields);$i++){
                       $custom_val=$this->input->post($custom_fields[$i]->lookup_id);
                       if(isset($custom_val)){
                           $custom_id=$custom_fields[$i]->lookup_value;
                           $keyval = array($custom_id => $custom_val);
                          $custom_name+= $keyval;
                       }
                    }
                    $result=array_merge($aaa,$custom_name);
                   foreach ($result as $key => $value) {
                       $attri_log=array(
                           'mapping_id' =>$mapping_id,
                           'stage_id' =>$stage_id,
                            'time_stamp' =>$dt,
                            'request_id' =>$request_id,
                            'opp_cust_id'=>$opp_cust_id,
                            'attribute_name'=>$key,
                            'attribute_value'=>$value,
                            'remarks' =>$remarks,
                            'time_stamp' =>$dt,
                        );
                        $data1=array(
                            'mapping_id'=>$mapping_id,
                           'support_stage_id'=>$stage_id,
                            'support_attribute_value'=>$value,
                            'support_attribute_remarks'=>$remarks,
                           'support_attribute_name'=>$key,
                            'request_id'=>$request_id,
                            'timestamp'=>$dt
                        );
                        
                        array_push($attribute_log,$attri_log);
                        array_push($custom_data,$data1);
                   }
                    $insert=$this->support->insert_log($attribute_log);
                    $changed_matrix=array();
                    if($insert=true){
                        $insert1=$this->support->fetch_log_attributes($request_id,$stage_id);
                            if($insert1== null){
                                $insert1=$this->support->insert_custom_attribute($custom_data);
                                echo true;
                            }else{
                                for($i=0;$i<count($attribute_log);$i++){
                                    for($j=0;$j<count($insert1);$j++){                           
                                        if($attribute_log[$i]['attribute_name'] == $insert1[$j]->support_attribute_name){
                                            if($attribute_log[$i]['attribute_value']!= $insert1[$j]->support_attribute_value){
                                                $changed_value=array('attribute_value'=>$attribute_log[$i]['attribute_value'],
                                                                    'attribute_name'=>$attribute_log[$i]['attribute_name']
                                                );
                                                    array_push($changed_matrix,$changed_value);
                                            }
                                        }
                                    }
                                }
                                if(count($changed_matrix)>0){
                                    $insert2=$this->support->update_attribute($changed_matrix,$remarks,$request_id,$stage_id);
                                }
                            }
                    }
               
                    $insert5=$this->support->next_stage_assign($data6,$data3,$data4,$request_id);
                    if($insert5==true){
                        echo 5;
                    }
                }
        }catch(LConnectApplicationException $e){
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
public function get_stage_history(){
    if($this->session->userdata('uid')){
        try {
           $json = file_get_contents("php://input");
            $data = json_decode($json);
            $request_id=$data->request_id;
            $stage_data = $this->support->stage_details($request_id);
            echo(json_encode($stage_data));
        }catch(LConnectApplicationException $e){
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

public function reject_stage(){
    if($this->session->userdata('uid')){
        try {
            $userid= $this->session->userdata('uid');
            $attibute=array();
            $attibute1=array();
            $custom_name=array();
            $custom_val=array();
            $attribute_log=array();
            $custom_data=array();
            $dt = date('YmdHis');
            $files_property= $_FILES;
            $timeframe = $this->input->post('timeframe');
            $timeframe_value 	= $this->input->post('timeframe_value');
            $max_time=$timeframe_value.'-'.$timeframe;
            $expected_close_date =$this->input->post('expected_close_date');
            $request_id =$this->input->post('req_id');
            $cycle_id	= $this->input->post('cyc_id');
            $stage_id =$this->input->post('sta_id');
            $opp_cust_id = $this->input->post('opp_id');
            $process = $this->input->post('process_id');
            $remarks = $this->input->post('remarks');
            $reject_stage = $this->input->post('reject_owner');
            $mapping_id=uniqid(rand());
            $upload_doc=array(
                'stage_id'=>$stage_id,
                'opportunity_id'=>$request_id,
                'lead_id'=>$opp_cust_id,
                'created_date'=>$dt,
                'request_id'=>$request_id,
                'remarks'=>$remarks,
                'files'=>$files_property,
                'mapping_id'=>$mapping_id
            ); 
                $file_data = $this->support->oppo_file_upload($upload_doc);
                if ($file_data!=1){
                    echo json_encode(array('errors' => $file_data, 'status' => false));
		}else{
                    if($timeframe!='' || $timeframe!=null){
                        $attibute1 = array('max_permission' => $max_time);
                    }else{
                        $attibute1 = array('max_permission' => '');
                    }
                    if($expected_close_date!='' || $expected_close_date!=null){
                        $attibute = array('expected_close_date' => $expected_close_date);
                    }else{
                        $attibute = array('expected_close_date' => '');
                    }
                    $aaa=$attibute1+$attibute;
                    $custom_fields = $this->support->get_custom_names();
                    for($i=0;$i<count($custom_fields);$i++){
                       $custom_val=$this->input->post($custom_fields[$i]->lookup_id);
                       if(isset($custom_val)){
                           $custom_id=$custom_fields[$i]->lookup_value;
                           $keyval = array($custom_id => $custom_val);
                          $custom_name+= $keyval;
                       }
                    }
                    $result=array_merge($aaa,$custom_name);
                    foreach ($result as $key => $value) {
                        $attri_log=array(
                            'mapping_id' =>$mapping_id,
                            'stage_id' =>$stage_id,
                            'time_stamp' =>$dt,
                            'request_id' =>$request_id,
                            'opp_cust_id'=>$opp_cust_id,
                            'attribute_name'=>$key,
                            'attribute_value'=>$value,
                            'remarks' =>$remarks,
                            'time_stamp' =>$dt,
                        );
                        $data1=array(
                            'mapping_id'=>$mapping_id,
                            'support_stage_id'=>$stage_id,
                            'support_attribute_value'=>$value,
                            'support_attribute_remarks'=>$remarks,
                            'support_attribute_name'=>$key,
                            'request_id'=>$request_id,
                            'timestamp'=>$dt
                        );
                        array_push($attribute_log,$attri_log);
                        array_push($custom_data,$data1);
                    }
                    $insert=$this->support->insert_log($attribute_log);
                    $changed_matrix=array();
                    if($insert=true){
                        $insert1=$this->support->fetch_log_attributes($request_id,$stage_id);
                            if($insert1== null){
                                $insert1=$this->support->insert_custom_attribute($custom_data);
                                echo true;
                            }else{
                                for($i=0;$i<count($attribute_log);$i++){
                                    for($j=0;$j<count($insert1);$j++){                           
                                        if($attribute_log[$i]['attribute_name'] == $insert1[$j]->support_attribute_name){
                                            if($attribute_log[$i]['attribute_value']!= $insert1[$j]->support_attribute_value){
                                                $changed_value=array('attribute_value'=>$attribute_log[$i]['attribute_value'],
                                                                    'attribute_name'=>$attribute_log[$i]['attribute_name']
                                                );
                                                    array_push($changed_matrix,$changed_value);
                                            }
                                        }
                                    }
                                }
                                if(count($changed_matrix)>0){
                                    $insert2=$this->support->update_attribute($changed_matrix,$remarks,$request_id,$stage_id);
                                }
                                $result=$this->support->fetch_owner_stage($reject_stage,$request_id);
                                $old_stage_owner= $result[0]->old_stage_owner;
                                $data6= array(
                                    'mapping_id' =>$mapping_id,
                                    'request_id' =>$request_id,
                                    'cycle_id' =>$cycle_id,
                                    'stage_id'=>$stage_id,
                                    'process_type'=>$process,
                                    'opp_cust_id'=>$opp_cust_id,
                                    'state' =>1,
                                    'action'=>"stage_rejected",
                                    'module'=>"sales",
                                    'from_user_id'=>$userid,
                                    'to_user_id'=>$userid,
                                    'timestamp'=>$dt,
                                    'remarks'=>$remarks
                                    );
                                $data7= array(
                                    'mapping_id' =>$mapping_id,
                                    'request_id' =>$request_id,
                                    'cycle_id' =>$cycle_id,
                                    'stage_id'=>$reject_stage,
                                    'process_type'=>$process,
                                    'opp_cust_id'=>$opp_cust_id,
                                    'state' =>1,
                                    'action'=>"ownership_assigned",
                                    'module'=>"sales",
                                    'from_user_id'=>$userid,
                                    'to_user_id'=>$old_stage_owner,
                                    'timestamp'=>$dt,
                                    'remarks'=>$remarks
                                    );
                                $data8=array(
                                    'request_stage'=>$reject_stage,
                                    'cycle_id'=> $cycle_id,
                                    'owner_id'=>$old_stage_owner,
                                    'remarks'=> $remarks
                                    );
                                $result1=$this->support->insert_reject_details($data6,$data7,$data8,$data8);
                                if($result1==true){
                                    echo 1;
                                }
                            }
                    }
                }
        }catch(LConnectApplicationException $e){
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
function check_qualifier(){
    if($this->session->userdata('uid')){
        try {
          $json = file_get_contents("php://input");
            $data = json_decode($json);
            $rep_id = $data->rep_id;   
            $stage_id = $data->stage_id;
            $opp_cust_id = $data->opp_cust_id;   
            $request_id = $data->request_id;
            $process_type = $data->process;
            $question_data = $data->que_date;
            $cycle_id = $data->cycle_id;
            
            $type1_2=$data->type1_2;
            $data_array=array(
                'rep_id'=>$rep_id,
                'cycle_id'=>$cycle_id,
                'opp_cust_id'=>$opp_cust_id,
                'stage_id'=>$stage_id,
                'request_id'=>$request_id,
                'process_type'=>$process_type,
                'question_data'=>json_encode($question_data),
                'type1_2'=>$type1_2,
              );
            $qualifier_data = $this->support->answer_verification($data_array,$type1_2);
            echo json_encode($qualifier_data); 
        }catch(LConnectApplicationException $e){
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

function get_request_history(){
    if($this->session->userdata('uid')){
        try {

            $json = file_get_contents("php://input");
            $data = json_decode($json);
            $req_id = $data->req_id;  
            $GLOBALS['$logger']->info('History of request id : '.$req_id); 
            $history=$this->support->get_history($req_id);
            $GLOBALS['$logger']->info($history);
            echo json_encode($history);
        }catch(LConnectApplicationException $e){
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
function get_documents(){
    if($this->session->userdata('uid')){
        try {

            $json = file_get_contents("php://input");
            $data = json_decode($json);
            $req_id = $data->req_id;
            $GLOBALS['$logger']->info('Fetching Documents of Request id : '.$req_id);
            $docs = $this->support->request_documents($req_id);
            $GLOBALS['$logger']->info($docs);
            echo json_encode($docs);				
        }catch (LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);				
        }
    }else{
            redirect('indexController');
    }
}
function scheduled_task(){
    if($this->session->userdata('uid')){
        try {
            $GLOBALS['$logger']->info('Fetching Scheduled Task');
            $json = file_get_contents("php://input");
            $data = json_decode($json);
            $req_id = $data->req_id;
            $userid = $this->session->userdata('uid');
            $GLOBALS['$logger']->info('Fetching Scheduled Task of request id: '.$req_id.' by userid : '.$userid);
            $task = $this->support->request_tasklist($req_id);
            $GLOBALS['$logger']->info($task);
            echo json_encode($task);				
        }catch (LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);				
        }
    }else{
            redirect('indexController');
    }
}
function request_log(){
    if($this->session->userdata('uid')){
        try {
            // Fetching Support Log Activites.
            $userid = $this->session->userdata('uid'); 
            $GLOBALS['$logger']->info('Fetching Log Activites. USER_ID: '.$userid);
            $json = file_get_contents("php://input");
            $data = json_decode($json);
            $req_id = $data->req_id;
            $GLOBALS['$logger']->info('Request Id: '.$req_id);
            $log = $this->support->request_loglist($req_id);
            $GLOBALS['$logger']->info($log);
            echo json_encode($log);				
        }catch (LConnectApplicationException $e) {
            echo $this->exceptionThrower($e);				
        }
    }else{
            redirect('indexController');
    }
}


   
}

