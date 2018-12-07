<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_supportrequestcontroller');

class manager_supportrequestcontroller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('manager_supportrequestmodel','support');
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

    public function index()
    {
        if($this->session->userdata('uid'))
        {
            try
            { 

                $this->load->view('manager_support_new');
            }
            catch (LConnectApplicationException $e)
            {
                echo $this->exceptionThrower($e);
            }
        }
        else{
            redirect('indexController');
        }    
    }

    public function receviedSupportForUser()
    {
       if($this->session->userdata('uid'))
        {
            try
            {

                $data = $this->support->receviedSupport($this->session->userdata('uid'));
                echo json_encode($data);

            }
            catch (LConnectApplicationException $e)
            {
                echo $this->exceptionThrower($e);
            }
        }
        else{
            redirect('indexController');
        } 
    }


    public function associatedTo()
    {
       if($this->session->userdata('uid'))
        {
            try
            {
                $json = file_get_contents("php://input");
                $data = json_decode($json);

                $associatedDetails = $this->support->associatedToData($this->session->userdata('uid'),$data->selectionType);

                echo json_encode($associatedDetails);

            }
            catch (LConnectApplicationException $e)
            {
                echo $this->exceptionThrower($e);
            }
        }
        else
        {
            redirect('indexController');
        }
    }


    public function getProductAndContacts() 
    {
        if($this->session->userdata('uid'))
        {
            try
            {
               $userid= $this->session->userdata('uid');
               $json = file_get_contents("php://input");
               $data = json_decode($json);
               $type = $data->selectionType;
               $id   = $data->id;
               $typeData=$this->support->associatedProductContacts($id,$type);  
               echo json_encode($typeData);
            }
            catch (LConnectApplicationException $e)
            {
                 echo $this->exceptionThrower($e);
            }
        }
        else
        {
            redirect('indexController');
        }    
    }

    public function assignUserList()
    {
        if($this->session->userdata('uid'))
        {
            try
            {
               $userid= $this->session->userdata('uid');
               $json = file_get_contents("php://input");
               $data = json_decode($json);
               if ($data->newSupport == 'newSupport') 
               {

                    $industry         = $this->support->getIndustry($data->opp_cust_id);
                    $location         =$this->support->getBusinessLocation($data->opp_cust_id);
                    $associatedChildrens = $this->support->getAssocaitedChildrens($userid,$industry['value'],$location['value'],$data->product);
                    echo json_encode($associatedChildrens);
               }
               else
               {    $assignedAs = $data->assignedType;
                    $requestId  = $data->request_id;
                    $selectedUsers = $this->support->listOfAssignee($userid,$assignedAs,$requestId);
                    echo json_encode($selectedUsers);
               }
            }
            catch (LConnectApplicationException $e)
            {
                 echo $this->exceptionThrower($e);
            }
        }
        else
        {
            redirect('indexController');
        }        
    }

    public function assignedSupportRequest()
    {
        if($this->session->userdata('uid'))
        {
            try
            {
               $userid= $this->session->userdata('uid');
               $json = file_get_contents("php://input");
               $data = json_decode($json);
               $selectedUsers = $this->support->listOfAssignee($userid,'');
               echo json_encode($selectedUsers);
            }
            catch (LConnectApplicationException $e)
            {
                 echo $this->exceptionThrower($e);
            }
        }
        else
        {
            redirect('indexController');
        }       
    }

    public function unassignedSupport()
    {
        if($this->session->userdata('uid'))
        {
            try
            { 

                $this->load->view('manager_support_unassigned');
            }
            catch (LConnectApplicationException $e)
            {
                echo $this->exceptionThrower($e);
            }
        }
        else{
            redirect('indexController');
        }
    }

    public function unassignedSupportRequest()
    {
        if($this->session->userdata('uid'))
        {
            try
            {
               $userid= $this->session->userdata('uid');
               $json = file_get_contents("php://input");
               $data = json_decode($json);
               $unassigned = $this->support->unassignedRequest($userid);
               echo json_encode($unassigned);
            }
            catch (LConnectApplicationException $e)
            {
                 echo $this->exceptionThrower($e);
            }
        }
        else
        {
            redirect('indexController');
        }        
    }




    public function support_inprogres(){
        if($this->session->userdata('uid')){
            try{
              $this->load->view('manager_supportinprogress');
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
    public function support_closed(){
        if($this->session->userdata('uid')){
            try{
              $this->load->view('manager_supportclosed');
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
    public function assigned_request(){
        if($this->session->userdata('uid')){
            try{
              $this->load->view('manager_support_assigned');
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
                if($request_for=='opportunity'){
                   $allocation_list=$this->support->chk_parameters_opp($opportunity_ids,$process_type,$product_id);
                    if($allocation_list==0 || $allocation_list==1){
                       echo $allocation_list;
                   }else{
                       echo json_encode($allocation_list);
                   }
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

    public function raiseSupportRequest()
    {
        if($this->session->userdata('uid'))
        {
            try
            {
                $userId                 = $this->session->userdata('uid');
                $json                   = file_get_contents("php://input");
                $data                   = json_decode($json);
                $requestName            = $data->ticketName;
                $addArray               = array();
                $type                   = $data->type;
                $contacts                = $data->contact;
                $checkName = $this->support->isValidSupName($requestName);
                if ($checkName == 0) {
                    $returnArray= array(
                        'message' => 'An Support with same name already exists.',
                        'status'=>false,
                        'qualifier'=>false);
                    echo json_encode($returnArray);
                    return ;
                }
                else
                {  
                    
                    $request                = '';
                    $dt                     = date('YmdHis');
                    $request                .= $dt;
                    $request_id              = uniqid($request);
                    $oppCustId              = $data->id;

                    $IndustryResult         = $this->support->getIndustry($oppCustId);
                    $BusinessLocationResult =$this->support->getBusinessLocation($oppCustId);

                    $industry               = $IndustryResult['value'];
                    $businessLocation       = $BusinessLocationResult['value'];
                    $product                = $data->product;
                    $processType            = $data->process;
                    $remarks                = $data->remarks;

                    $forCycle               = array(
                                                'industry_id'=>$industry,
                                                'location_id'=>$businessLocation,
                                                'product_id' =>$product,
                                                'process_type'=>$processType,
                                                );

                    $cycleResponse = $this->getProcessCycle($forCycle);

                    if ($cycleResponse) 
                    {
                        //UserMap,
                        $response = $this->insertedData($data,$request_id,$userId,$forCycle);
                        
                        if ($response['status'] == TRUE) 
                        {
                            echo json_encode($response);
                        }

                        // $qualifier=$this->support->chk_qualifier($data->stage,$data1,$userid);
                        // $returnArray= array(
                        //         'message' => 'Answer this qualifier to proceed further.', 
                        //         'status' => false,
                        //         'qualifier' => true,
                        //         'qualifier_data' => $qualifier,
                        //         'request_data' => $data1
                        // );
                        // echo json_encode($returnArray);
                        // return ;

                    }

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
        }
        else{
            redirect('indexController');
        }    
    }
    public function get_emails(){
        if($this->session->userdata('uid')){
            try {
                    $user =$this->session->userdata('uid');
                    $data=$this->support->fetch_emails($user);
                    echo json_encode($data);
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
     public function verify_qualifier(){
        if($this->session->userdata('uid')){
            try {
                $user =$this->session->userdata('uid');
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $rep_id=$data->rep_id;
                $opp_cust_id=$data->opp_cust_id;
                $request_id=$data->request_id;
                $lead_qualifier_id=$data->lead_qualifier_id;
                $stage_id = $data->stage_id; 
                $cycle_id = $data->cycle; 
                $request_name= $data->request_name;   
                $request_for= $data->request_for;    
                $contact_id= $data->contact_id;    
                $process = $data->process;   
                $owner = $data->owner;   
                $product= $data->product; 
                $industry = $data->industry;   
                $location= $data->location;    
                $crictical= $data->critical;   
                $tat = $data->tat;
                $question_data = $data->que_date;
                $remarks = $data->remarks;    
                $type1_2=$data->type1_2;
                $email= $data->email; 
                $rep_status=0;
                $mgr_status=0;
               for($j=0;$j< count($owner);$j++){
                  $ar_owner=explode('-',$owner[$j]);
                    if($ar_owner[1]=='sales'){
                      $rep_status ++;
                    }else if($ar_owner[1]=='mgr'){
                      $mgr_status ++;
                    }
                }
                $owner_state=0;
                $mgr_state=2;
                if($rep_status>0){
                   $owner_state=1;
                }
                if($mgr_status>0){
                    $mgr_state=1;
                }
                
                if(count($email)>0){
                    $email_users= implode(",",$email);
                    $list=$this->support->get_userlist($email_users);
                    $userList=array();
                    foreach($list as $val){
                        $user_name=$val->user_id;
                        array_push($userList, $user_name);
                    }
                  $users = $userList;
                  $msgbody = 'Request'.$request_name.' has been assigned';
                  $subject =  'Request Assigned';
                  $userList=array();
                 // $email_assign= $this->lconnecttcommunication->send_email($users,$subject,$msgbody);
                }
              
                $owner_list= implode(",",$owner);
                $list1=$this->support->get_userlist($owner_list);
                $user_List=array();
                 
                foreach($list1 as $val){
                    $user_name=$val->user_id;
                    array_push($user_List, $user_name);
                }
                  $users1 = $user_List;
                  $msgbody1 = 'Request'.$request_name.' has been assigned';
                  $subject1 =  'Request Assigned';
                    // $email_assign= $this->lconnecttcommunication->send_email($users1,$subject1,$msgbody1);
                 
                $quali_id = '';
                $dt = date('YmdHis');
                $quali_id .= $dt;
                $quali_trans_id = uniqid($quali_id);  
                
                $data1=array(
                    'rep_id'=>$rep_id,
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
                    'manager_owner_id'=> $user,
                    'owner_manager_status'=>$mgr_state,
                    'created_by'=> $user,
                    'owner_status'=> $owner_state,
                    'created_timestamp'=> $dt,
                    'cricticality'=> $crictical,
                    'request_for'=> $request_for,
                    'remarks'=> $remarks
                );
                $action=array('created','ownership_accpted');
                $data1_3=array();
                  for($j=0;$j <count($action);$j++){
                       $data3=array('mapping_id'=>uniqid(rand(),TRUE),'request_id'=> $request_id,'opp_cust_id'=> $opp_cust_id,'from_user_id'=>$user,'to_user_id'=> $user,'cycle_id'=> $cycle_id,'stage_id'=>$stage_id,'module'=> 'manager','process_type'=>$process,'timestamp'=> $dt,'action'=> $action[$j],'state'=> 1,);
                         array_push($data1_3,$data3) ; 
                  }
                  $data1_4=array();
                for($i=0;$i<count($owner);$i++){
                       $ar_owner=explode('-',$owner[$i]);
                       if( $ar_owner[1]=='sales'){
                           $module='sales';
                       }else{
                           $module='manager';
                       }
                    
                    $data4=array(
                     'mapping_id'=>uniqid(rand(),TRUE),
                     'request_id'=> $request_id,
                     'opp_cust_id'=> $opp_cust_id,
                     'from_user_id'=>$user,
                     'to_user_id'=> $ar_owner[0],
                     'cycle_id'=> $cycle_id,
                     'stage_id'=>$stage_id,
                     'process_type'=>$process,
                     'module'=> $module,
                     'timestamp'=> $dt,
                     'action'=> 'ownership_assigned',
                     'state'=> 1
                    );
                   
                     array_push($data1_4,$data4) ;
                }
                $insert=$this->support->insert_data($type1_2,$data1,$data2,$data1_3,$data1_4);
                echo json_encode($insert);
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
   
     public function get_ticketDetails(){
        if($this->session->userdata('uid')){
            try {
                // Assigned ticket data to the user by his managers.
                    $user =$this->session->userdata('uid');
                    $data=$this->support->fetch_tickets($user);
                    echo json_encode($data);
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
    public function get_inprogress_tickets(){
        if($this->session->userdata('uid')){
            try {
                //Fetching inprogress tickets for assigned or accepted by manager. 
                $user =$this->session->userdata('uid');
                $GLOBALS['$logger']->info('Fetching inprogress tickets is called by '.$user.'.');
                $data=$this->support->inprogress_tickets($user);
                $GLOBALS['$logger']->info('Response');
                $GLOBALS['$logger']->info($data);
                echo json_encode($data);
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
    public function closed_tickets(){
        if($this->session->userdata('uid')){
            try {
                $user =$this->session->userdata('uid');
                $data=$this->support->get_closed_tickets($user);
                echo json_encode($data);
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
    public function accept_multiple(){
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
            for($i=0;$i<$count;$i++){
                $req_owner= $this->support->manager_owner($req_id[$i]); 
                if($req_owner!=0){
                    $data1= array(
                       'manager_owner_id'=>$userid,
                       'owner_manager_status'=>2
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
                     'module'=>"manager",
                     'from_user_id'=>$userid,
                     'to_user_id'=>$userid,
                     'timestamp'=>$dt,
                     );
                    $update = $this->support->accept_request($req_id[$i],$data1);
                    $update2 = $this->support->update_transaction($req_id[$i]);
                    $update1 = $this->support->insert_transaction($data2);
                }else{
                     array_push($rejectedleads,$req_id[$i]); 
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
        }else{
            redirect('indexController');
        }
    }
    public function reject_multiple(){
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
                $req_owner= $this->support->manager_owner($rejected_ids[$i]); 
                if($req_owner!=0){
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
            
            $data1=array(
                'request_name'=> $req_name,
                'request_contact'=>implode(':',$con_name),
                'request_tat'=>$comm_tat,
                'cricticality'=>$critc,
            );
            $data2= array(
                     'mapping_id' =>uniqid(rand(),TRUE),
                     'request_id' =>$req_id,
                     'cycle_id' =>$edit_cycle,
                     'stage_id'=>$edit_stage,
                     'process_type'=>$edit_process,
                     'opp_cust_id'=>$edit_oppo,
                     'state' =>1,
                     'action'=>"edited",
                     'module'=>"manager",
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
 public function reassign_contacts(){
    if($this->session->userdata('uid')){
        try {
            $userid= $this->session->userdata('uid');
            $json = file_get_contents("php://input");
            $data=json_decode($json);
            $req_id = $data->req_id;
            $data1=$this->support->fetch_reassign_contacts($userid,$req_id);
            echo json_encode($data1);
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

 public function save_reassign(){
    if($this->session->userdata('uid')){
        try {
            $userid= $this->session->userdata('uid');
            $json = file_get_contents("php://input");
            $data=json_decode($json);
            $userPackets =implode(',',$data->user_list);
            $request=$data->user_list;
            $insertArray = array();
              $mapping_id = uniqid(rand(),TRUE);
              $status_arr= array();
              foreach ($request as $request) {
                foreach ($userPackets as $packet) {
                    $data1= array(
                        'mapping_id' =>uniqid(rand(),TRUE),
                        'request_id' =>$req_id,
                        'cycle_id' =>$edit_cycle,
                        'stage_id'=>$edit_stage,
                        'process_type'=>$edit_process,
                        'opp_cust_id'=>$edit_oppo,
                        'state' =>1,
                        'action'=>"ownership_reassigned",
                        'module'=>"manager",
                        'from_user_id'=>$userid,
                        'to_user_id'=>'',
                        'timestamp'=>$dt,
                        'remarks'=>$remarks
                     );
                  array_push($insertArray, $data1);
                }
              }
            
            echo json_encode($data1);
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
            $data1=array(
                
            );
            $insert=$this->support->close_request_details($remarks,$req_id,$userid);
            echo json_encode($insert);
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
public function stage_view(){
        if($this->session->userdata('uid')){
            try{
              $this->load->view('manager_request_stageview');
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
public function get_assigned_tickets(){
    if($this->session->userdata('uid')){
        try{
            //  Accepted or assigned tickets of Executive's. 
            $userid= $this->session->userdata('uid');
            $GLOBALS['$logger']->info('Fetching Assigned tickets by user id '.$userid.'.');
            $tickets_list=$this->support->assigned_tickets($userid);
            $GLOBALS['$logger']->info('Response');
            $GLOBALS['$logger']->info($tickets_list);
            echo json_encode($tickets_list);
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
public function assign_request(){
    if($this->session->userdata('uid')){
        try{
            $userid= $this->session->userdata('uid');
            $json = file_get_contents("php://input");
            $data=json_decode($json);
            $req_id=$data->id;
            $tickets_list=$this->support->ticket_assignment($userid,$req_id);
            echo json_encode($tickets_list);
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
public function save_support_request(){
    if($this->session->userdata('uid')){
        try{
            $userid= $this->session->userdata('uid');
            $json = file_get_contents("php://input");
            $data=json_decode($json);
            $req_id=$data->req_id;
            $ownerlist=$data->ownerlist;
            $tickets_list=$this->support->assign_support_request($userid,$req_id,$ownerlist);
            echo $tickets_list;
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
public function audit_details(){
    if($this->session->userdata('uid')){
        try{

            $json = file_get_contents("php://input");
            $data=json_decode($json);
            $req_id=$data->req_id;
            $GLOBALS['$logger']->info('Fetching Audit Details of Request Id: '.$req_id);
            $audit_tails=$this->support->get_audit_details($req_id);
            $GLOBALS['$logger']->info($audit_tails);
            echo json_encode($audit_tails);
            
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
function reopen_request(){
     if($this->session->userdata('uid')){
        try{
            $userid= $this->session->userdata('uid');
            $json = file_get_contents("php://input");
            $data = json_decode($json);
            $note=$data->note;
            $reqst_id=$data->reqst_id;
            $mgr=$this->support->request_reopen($note,$userid,$reqst_id);                 
            echo $mgr; 				
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
public function reassign_request() {
    if($this->session->userdata('uid')){
        try{
            $userid= $this->session->userdata('uid');
            $json = file_get_contents("php://input");
            $data=json_decode($json);
            $req_id = $data->req_id;
            $remarks = $data->remarks;
            $ownerlist = $data->users;
            $reassignData = $this->support->reassignRequestStage($req_id,$remarks,$ownerlist,$userid);
            echo json_encode($reassignData);
            
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

    private function getProcessCycle($data)
    {
       
        $product_id = $data['product_id'];
        $industry_id = $data['industry_id'];
        $location_id = $data['location_id'];
        $processType = $data['process_type'];

        return 1;
        
    }

    private function insertedData($data,$request_id,$userId,$CycleData)
    {
        $owner_id = $userId;

        $insertData = array(
                            'request_id' => $request_id,
                            'request_name' => $data->ticketName,
                            'opp_cust_id' => $data->id,
                            'request_product' => $data->product,
                            'request_industry' => $CycleData['industry_id'],
                            'request_location' => $CycleData['location_id'],
                            'request_contact' => implode(":",$data->contact),
                            'request_stage' => '',
                            'cycle_id' => '',
                            'created_by' => $userId,
                            'created_timestamp' => date('Y-m-d H:i:s'),
                            'manager_owner_id' => $userId,
                            'owner_manager_status' => 2,
                            'stage_manager_owner_id' => $userId,
                            'stage_manager_owner_status' => 2,
                            'owner_status' => 0,
                            'stage_owner_status' => 0,
                            'process_type' => $data->process,
                            'request_for'=>$data->type,
                            'stage_manager_owner_id'=>'',
                            'stage_owner_id'=>NULL,
                            'closed_reason'=>NULL
                            );

                        $insertArray = array();
                        array_push($insertArray, 
                                                array(
                                                'mapping_id'=> uniqid(rand()),
                                                'request_id'=> $request_id,
                                                'opp_cust_id'=> $data->id,
                                                'from_user_id'=> $owner_id,
                                                'to_user_id'=> $owner_id,
                                                //'cycle_id'=> $data['cycle_id'],
                                                //'stage_id' => $data['stage_id'],
                                                'module'=> 'manager',
                                                'action'=> 'created',
                                                'process_type'=> $data->process,
                                                'timestamp'=> date('Y-m-d H:i:s'),
                                                'remarks'=> null,
                                                'state'=>0
                                                )
                                    );

                        array_push($insertArray, 
                                                array(
                                                'mapping_id'=> uniqid(rand()),
                                                'request_id'=> $request_id,
                                                'opp_cust_id'=> $data->id,
                                                'from_user_id'=> $owner_id,
                                                'to_user_id'=> $owner_id,
                                               // 'cycle_id'=> $data['cycle_id'],
                                                //'stage_id'=> $data['stage_id'],
                                                'module'=> 'manager',
                                                'action'=> 'ownership accepted',
                                                'process_type'=> $data->process,
                                                'timestamp'=> date('Y-m-d H:i:s'),
                                                'remarks'=> null,
                                                'state'=>1
                                                )
                                            );

                        array_push($insertArray, 
                                                array(
                                                'mapping_id'=> uniqid(rand()),
                                                'request_id'=> $request_id,
                                                'opp_cust_id'=> $data->id,
                                                'from_user_id'=> $owner_id,
                                                'to_user_id'=> $owner_id,
                                               // 'cycle_id'=> $data['cycle_id'],
                                               // 'stage_id'=> $data['stage_id'],
                                                'module'=> 'manager',
                                                'action'=> 'stage accepted',
                                                'process_type'=> $data->process,
                                                'timestamp'=> date('Y-m-d H:i:s'),
                                                'remarks'=> null,
                                                'state'=>1
                                                )
                                 );

            $status = $this->support->create_request($data->type,$insertData,'');

            if ($status['status'] == FALSE) 
            {
                $returnArray= array('message' => 'Something went wrong. Could not create Support. Please try again.', 'status'=>false, 'qualifier'=>false);
                echo json_encode($returnArray);
                return ;
            }
            else 
            {
                $insert=$this->support->map_support($insertArray); 

                $supportAssignment = $this->supportAssigning($data,$request_id);

                if ($supportAssignment == true) 
                {
                    $returnArray= array('message' => '', 'status'=>true, 'ticket_id'=>$status['ticket']);

                    return ($returnArray);
                }
            }

            
    }

    public function supportAssigning($data,$request_id)
    {
        
        if($this->session->userdata('uid'))
        {
            try
            { 
                $sales_module = false;
                $manager_module = false;
                $mapping_id = uniqid(rand());
                $insertArray = array();
                $insertArray1 = array();
                $userList = array();
                $ownerShipType = $stageOwnershipType = '';
                $type ='';
                $process = '';
                $ticketName = '';
                $sessionUser = $this->session->userdata('uid');
                $ownerShipIds = $stageOwnershipIds = '';
                $ownerShipArray = $stageOwnershipArray = array();
                $opp_cust_id = '';
                $requestId = '';
                $stageOwnerShip = '';
                
                if (!isset($this->uri->segments[3])) 
                {
                    $ownerShip = $data->executive;
                    $stageOwnerShip = $data->stageExecutive;
                    $ownerShipIds = $ownerShip->id;
                    $stageOwnershipIds = $stageOwnerShip->id;
                    $type = $data->type;
                    $process = $data->process;
                    $ticketName = $data->ticketName;
                    $ownerShipType = $ownerShip->type;
                    $opp_cust_id = $data->id;
                    $requestId = $request_id;
                    $stageOwnershipType = $stageOwnerShip->type;
                    
                }
                else
                {

                    $json = file_get_contents("php://input");
                    $data=json_decode($json);

                    $ownerShip = $data->executive;
                    $stageOwnerShip = $data->stageExecutive;

                    if (isset($stageOwnerShip->id)) 
                    {
                        $stageOwnershipIds = $stageOwnerShip->id;
                        $stageOwnershipType = $stageOwnerShip->type;
                    }
                    elseif (isset($ownerShip->id))  
                    {
                        $ownerShipIds = $ownerShip->id;
                        $ownerShipType = $ownerShip->type;
                    }
                    $process = $data->process;
                    $opp_cust_id = $data->id;
                    $requestId = $data->request_id;
                }
                // Support Manager Or ExecOwners.
                switch ($ownerShipType) 
                {
                    case 'supportOwners': 
                    foreach ($ownerShipIds as $key => $value) 
                    {
                        $data= array(
                            'mapping_id'=> $mapping_id,
                            'request_id'=> $requestId,
                            'opp_cust_id'=> $opp_cust_id,
                            'from_user_id'=> $sessionUser,
                            'to_user_id'=> $value,
                            'cycle_id'=> '',
                            'stage_id'=> '',
                            'module'=> 'sales',
                            'process_type'=> $process,
                            'timestamp'=> date('Y-m-d H:i:s'),
                            'state'=> 1,
                            'action'=>'ownership assigned',
                        );

                        array_push($ownerShipArray, $data);
                    }

                    break;
                    
                    default:
                        # code...
                        break;
                }

                switch ($stageOwnershipType) 
                {
                    case 'stageOwners':
                    foreach ($stageOwnershipIds as $key => $value) 
                    {
                        $data= array(
                            'mapping_id'=> $mapping_id,
                            'request_id'=> $requestId,
                            'opp_cust_id'=> $opp_cust_id,
                            'from_user_id'=> $sessionUser,
                            'to_user_id'=> $value,
                            'cycle_id'=> '',
                            'stage_id'=> '',
                            'module'=> 'sales',
                            'process_type'=> $process,
                            'timestamp'=> date('Y-m-d H:i:s'),
                            'state'=> 1,
                            'action'=>'stage assigned',
                        );

                        array_push($stageOwnershipArray, $data);
                    }
                    
                    default:
                        # code...
                        break;
                }

                if (!empty($stageOwnershipArray)) 
                {
                   $insert=$this->support->map_support($stageOwnershipArray);

                   // update status to support details.

                   if ($insert == TRUE) 
                   {
                      $updateArray = array('owner_status' => 1,'owner_id' => null);

                      $this->support->reassign_reset($requestId,$updateArray);
                   }
                   else  
                    {
                        $returnArray= array('message' => 'Something went wrong. Could not create Support. Please try again.', 'status'=>false, 'qualifier'=>false);
                        echo json_encode($returnArray);
                        return ;
                    }
                }

                if (!empty($ownerShipArray)) 
                {
                   $insert=$this->support->map_support($ownerShipArray);

                   // update status to support details.

                   if ($insert == TRUE) 
                   {
                        $updateArray = array('stage_owner_status' => 1,'stage_owner_id' => null);

                        $this->support->reassign_reset($requestId,$updateArray);
                   }
                   else  
                    {
                        $returnArray= array('message' => 'Something went wrong. Could not create Support. Please try again.', 'status'=>false, 'qualifier'=>false);
                        echo json_encode($returnArray);
                        return ;
                    }
                }

                if (!isset($this->uri->segments[3])) 
                {
                    return true;
                }
                else
                {
                    $returnArray= array('message' => 'Successfully assigned support to users.', 'status'=>true, 'qualifier'=>false);
                    echo json_encode($returnArray);
                    return;
                }



            }
            catch (LConnectApplicationException $e) 
            {
                echo $this->exceptionThrower($e);
            }
        }
        else
        {
          redirect('indexController');
        }
    }

    public function supportDirectory()
    {
        if($this->session->userdata('uid'))
        {
            try
            { 

                $this->load->view('manager_support_directory');
            }
            catch (LConnectApplicationException $e)
            {
                echo $this->exceptionThrower($e);
            }
        }
        else{
            redirect('indexController');
        }
    }

}