<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_mytaskController');

class sales_mytaskController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('sales_mytaskModel','mytask');
        $this->load->library('lconnecttcommunication');
        $this->CI =& get_instance();
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
    public function index() {
        if($this->session->userdata('uid')){
            try{
                $GLOBALS['$log']->debug("sales_mytaskController");
                $this->load->view('sales_mytask_view');
            }catch (LConnectApplicationException $e)  {
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

    public function get_activity(){
        if($this->session->userdata('uid')){
            try {
                    $var = $this->mytask->fetch_activity();
                    echo json_encode($var); 
            }
            catch(LConnectApplicationException $e) {
                   echo $this->exceptionThrower($e);
            }
          
        }else{
            redirect('indexController');
        }
          
    }

    public function get_mytask()    {
        if($this->session->userdata('uid')){  
            try{
               $user_id=$this->session->userdata('uid');         
                    $data = $this->mytask->fetch_mytask($user_id, '');
                    $data1= $this->mytask->fetch_mytask1($user_id, '');
                    $taskArray= array_merge($data, $data1);
                    $taskData= array ('taskArray'=>$taskArray,'user_id'=>$user_id);
                    echo json_encode($taskData); 
            }catch (LConnectApplicationException $e)  {
                   echo $this->exceptionThrower($e);
            }          
        }else{
            redirect('indexController');
        }   
    }

     public function get_leads()    {
        if($this->session->userdata('uid')){
            try {
                  //  $user =$this->session->userdata('uid');
                   $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $user=$this->session->userdata('uid');
                    $var =$this->mytask->fetch_leads($user);
                    $var2= $this->mytask->fetch_customer($user);
                    $var1 =$this->mytask->fetch_internal();
                    $var3=array_merge($var,$var2);
                    $taskarr=array('leacust'=>$var3,'inter'=>$var1);
                    echo json_encode($taskarr);  
            }
            catch (LConnectApplicationException $e)  {
                    echo $this->exceptionThrower($e);
                }                   
        }else{
            redirect('indexController');
        }
    }

    public function get_employeeNumbers()   {
        if($this->session->userdata('uid')){
            try{
                $GLOBALS['$logger']->info('UserID : '.$this->session->userdata('uid')); 
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $GLOBALS['$logger']->info('Fetching Number:');
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $type = $data->type;
                $contactid = $data->contactid; 
                $GLOBALS['$logger']->info('contactID:'.$contactid);
                if($data->type!='internal'){                                      
                    $GLOBALS['$logger']->info('Fetching Number Except Internal');     
                    $lead_data = $this->mytask->fetch_contactNumber($contactid,$type);
                    $GLOBALS['$logger']->info('Response:');
                    $GLOBALS['$logger']->info($lead_data);
                }
                else if($data->type=='support'){
                $GLOBALS['$logger']->info('Fetching Number of support contact');     
                $lead_data = $this->mytask->fetch_contactNumberSupport($contactid,$type);
                $GLOBALS['$logger']->info('Response:');
                $GLOBALS['$logger']->info($lead_data);
                }
                else{
                    $GLOBALS['$logger']->info('Fetching Number Internal');    
                    $lead_data = $this->mytask->fetch_userNumber($contactid);
                    $GLOBALS['$logger']->info('Response:');
                    $GLOBALS['$logger']->info($lead_data);
                } 

                echo json_encode($lead_data);   
            } catch (LConnectApplicationException $e)  {
                   echo $this->exceptionThrower($e);
            }                         
        } else {
            redirect('indexController');                
        }                       
    }

    public function get_atctivity(){       
        if($this->session->userdata('uid')){
            try{
                $var = $this->mytask->fetch_activity();
                echo json_encode($var);           
            } catch (LConnectApplicationException $e)  {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                    $errorArray = array(
                            'errorCode' => $e->getErrorCode(), 
                            'errorMsg' => $e->getErrorMessage()
                    );
                    $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                    $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                    echo json_encode($errorArray);
            } 
        } else {
                redirect('indexController');
            }
        }
    public function get_atctivitycompleted()    {
        if($this->session->userdata('uid')){
            try{
                $var = $this->mytask->fetch_activity_complete();
                echo json_encode($var);
            }catch (LConnectApplicationException $e)  {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                    $errorArray = array(
                            'errorCode' => $e->getErrorCode(), 
                            'errorMsg' => $e->getErrorMessage()
                    );
                    $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                    $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                    echo json_encode($errorArray);
            }           
        } else {
            redirect('indexController');
        }       
    }

    public function get_contactsForLead()   {
     
        if($this->session->userdata('uid')){
            try{
                $GLOBALS['$logger']->info('UserID : '.$this->session->userdata('uid')); 
                $json = file_get_contents("php://input");
                $json = file_get_contents("php://input");
                $data = json_decode($json);

                $leadid = $data->leadid;
                $type = $data->type;
                $supportid = $data->supportid;
                if($supportid !=''){
                    $GLOBALS['$logger']->info('Lead ID:'.$leadid);
                    $GLOBALS['$logger']->info('Fetching Contact For Contact Type:');
                    $lead_data = $this->mytask->fetch_ContactsForsupport($user,$supportid);
                    $GLOBALS['$logger']->info($lead_data);
                    echo json_encode($lead_data);
                }
                else{

                    $GLOBALS['$logger']->info('Lead ID:'.$leadid);
                    //$accessType = $data->access;
                    // ($this->session->userdata('uid') || ($accessType == 'phone'))
                    $GLOBALS['$logger']->info('Fetching Contact For Contact Type:');
                    $lead_data = $this->mytask->fetch_ContactsForLead($leadid,$type);
                    $GLOBALS['$logger']->info($lead_data);
                    echo json_encode($lead_data);
                    
                }              
            }catch (LConnectApplicationException $e)  {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                    $errorArray = array(
                            'errorCode' => $e->getErrorCode(), 
                            'errorMsg' => $e->getErrorMessage()
                    );
                    $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                    $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                    echo json_encode($errorArray);
            } 
        }else {
             redirect('indexController');                
        }                       
    }

    public function add_mytask()    {
        if($this->session->userdata('uid')){
            try{
                $user_id=$this->session->userdata('uid');
                $json = file_get_contents("php://input");
                $data = json_decode($json);  
                //generate a unique lead_reminder_id
                $dt = date('ymdHis');     
                //id of rep who has logged in
                $rep_id = $this->session->userdata("uid"); 
                $username = $this->session->userdata('uname');
                //fetching representative he is reporting in to which manager
                /*$reporting_to=$this->mytask->fetch_reporting($rep_id);
                $reporting_to= $reporting_to[0]->report; */           
                            
                //title of the reminder event
                $event_title = $data->event_title;
                //type of activity (id from lookup)
                $event_activity_type = $data->event_activity;
                //lead to which the activity is associated with
                $event_lead     = $data->event_lead;
                // type selected by the user
                $event_type=$data->event_type; 
               /* print_r($event_type);
                exit();*/
                //contact which is associated with that lead
                $event_contact  = $data->event_contact;
                //any remarks on the particular reminder
                $camp_note = $data->camp_note;
                //contains the number of minutes before which the alarm has to ring
                $reminder_time = $data->reminder_time;
                $status = "scheduled";
                $timestamp = date('Y-m-d H:i:s');
                //contains whether the email alert needs to be set
                //if 1 - send a mail to user himself at the time of event
                $email_alert = $data->email_alert;
                //contains members (user_ids from user_details) in a comma separated list
                $membersArr = (array) $data->reminder_members;
                $members = join(",", $membersArr);
                //contains start of the event
                $event_start = $data->event_start_date;
                //contains the estimated duration of the activity
                $duration=  $data->active_duration;

                //start date and start time split from the obtained event_start
                $event_start_date = date('Y-m-d', strtotime($event_start));
                $event_start_time = date('H:i', strtotime($event_start));

                //get seconds from cmp_duration
                $seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
                $activity_duration = (int)$seconds->getTimestamp();
                //add seconds to date object 1(start) and make it date object 2(end)
                $start = new DateTime($event_start);
                $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
                $event_end = $event_end->format('Y-m-d H:i:s');
                
                // $cmp_phone=$data->cmp_phone;
                $event_lead_name = $data->event_lead_name;
                $event_contact_name = $data->event_contact_name;
                $event_member_name=$this->mytask->getusername($user_id);
                $event_member_name=$event_member_name[0]->user_name;
                $event_activity_name = $data->event_activity_name;
                $repIdArray ='';
                $oppStageId = NULL;
                $oppStage='';
                $notificationDataArray = array();
                if(empty($data->event_members)){
                    $repIdArray = array($rep_id);
                }
                else{
                    
                    $repIdArray = $data->event_members;
                }
                $moduleName = '';
                $batchArray=array();
                 foreach ($repIdArray as $rep_id) {
                        //Check for the module name
                        if($event_type == 'lead') {
                        $GLOBALS['$logger']->info('Fetching Contact Type Owner Data');
                        $getOwner = $this->mytask->checkLeadOwner($event_lead);
                        $managerOwner=$getOwner[0]->manager_owner;
                            if($getOwner[0]->rep_owner == $rep_id){
                                $moduleName = 'sales';
                            }
                            else if($getOwner[0]->manager_owner == $rep_id){
                                    $moduleName = 'manager';
                            }
                            else{
                                $moduleName = 'user';
                            }
                        $GLOBALS['$logger']->info('Owner'. $moduleName);
                        } 
                        else if($event_type == 'customer') {
                            $GLOBALS['$logger']->info('Fetching Contact Type Owner Data');
                            $getOwner = $this->mytask->checkCustomerOwner($event_lead);
                            $managerOwner=$getOwner[0]->manager_owner;
                             if($getOwner[0]->rep_owner == $rep_id){
                                $moduleName = 'sales';
                            }
                            else if($managerOwner=$getOwner[0]->manager_owner == $rep_id){
                                $moduleName = 'manager';
                            }
                            else{
                                $moduleName = 'user';
                            }
                        $GLOBALS['$logger']->info('Owner'. $moduleName);
                        }
                        else if($event_type == 'opportunity') {
                            $GLOBALS['$logger']->info('Fetching Contact Type Owner Data');
                            $getOwner = $this->mytask->checkOpportunityOwner($event_lead);
                            $oppStage=$this->mytask->getOpportunityStageId($event_lead);
                            $oppStageId = $oppStage[0]->opportunity_stage;
                            $managerOwner=$getOwner[0]->manager_owner;
                            if($getOwner[0]->rep_owner == $rep_id){
                                $moduleName = 'sales';
                            }
                            else if($managerOwner=$getOwner[0]->manager_owner == $rep_id){
                                $moduleName = 'manager';
                            }
                            else{
                                $moduleName = 'user';
                            }
                            $GLOBALS['$logger']->info('Owner'. $moduleName);
                        }
                        else if($event_type == 'support') {
                            $GLOBALS['$logger']->info('Fetching Contact Type Owner Data');
                            $getOwner = $this->mytask->checkSupportOwner($event_lead);
                            $managerOwner=$getOwner[0]->manager_owner;
                            if($getOwner[0]->rep_owner == $rep_id){
                                $moduleName = 'sales';
                            }
                            else if($managerOwner=$getOwner[0]->manager_owner == $rep_id){
                                $moduleName = 'manager';
                            }
                            else{
                                $moduleName = 'user';
                            }
                            $GLOBALS['$logger']->info('Owner'. $moduleName);
                        }
                        else{
                            $getOwner = $this->mytask->checkForTheModule($rep_id);
                            $GLOBALS['$logger']->info('Fetching Contact Type Owner Data');

                            if($getOwner[0]->manager_owner !=NULL || $getOwner[0]->manager_owner != '' || $getOwner[0]->manager_owner != 0){
                                $moduleName = 'manager';
                                $GLOBALS['$logger']->info('Fetching Contact Type Owner Data');
                                $ReportingTo=$this->mytask->fetch_reporting($rep_id);
                                $managerOwner=$ReportingTo[0]->report;
                                $GLOBALS['$logger']->info('Owner'. $managerOwner);
                            }
                            else if($getOwner[0]->rep_owner !=NULL || $getOwner[0]->rep_owner != '' || $getOwner[0]->rep_owner != 0)
                            {
                                $moduleName = 'sales';
                                $GLOBALS['$logger']->info('Fetching Contact Type Owner Data');
                                $ReportingTo=$this->mytask->fetch_reporting($rep_id);
                                $managerOwner=$ReportingTo[0]->report;
                                $GLOBALS['$logger']->info('Owner'. $managerOwner);
                            }
                            else{
                                $moduleName = 'cxo';
                                $GLOBALS['$logger']->info('Fetching Contact Type Owner Data');
                                $ReportingTo=$this->mytask->fetch_reporting($rep_id);
                                $managerOwner=$ReportingTo[0]->report;
                                $GLOBALS['$logger']->info('Owner'. $managerOwner);
                            }
                         }
                            $lead_reminder_id = '';
                            $lead_reminder_id .= $dt;
                            $lead_reminder_id = uniqid($lead_reminder_id); //reminder_id uniquely created 

                            $data1 = array(
                                'lead_reminder_id' => $lead_reminder_id,
                                'lead_id'   => $event_lead,
                                'rep_id'    => $rep_id,
                                'leadempid' => $event_contact,
                                'remi_date' => $event_start_date,
                                'rem_time'  => $event_start_time,
                                'conntype'  => $event_activity_type,
                                'status'    => $status,
                                'meeting_start'    => $event_start,
                                'meeting_end'      => $event_end,
                                'addremtime'       => $reminder_time,          
                                'timestamp'        => $timestamp,
                                'remarks'          => $camp_note,
                                'event_name'       => $event_title,
                                'duration'         => $duration,
                                'email_alert'      => $email_alert,
                                'reminder_members' => $members,
                                'managerid' =>$managerOwner,
                                'type'=>$event_type,
                                'created_by'=>$user_id,
                                'module_id'=>$moduleName,
                                'opportunity_id'=>$oppStageId
                            );
                        
                        array_push($batchArray,$data1);  

                        $dt = date('ymdHis');
                        $notify_id = uniqid($dt); // notification id is uniquely created.
                        $notificationData= array(
                            'notificationID' =>$notify_id,
                            'notificationShortText'=>'Task Assigned by '.$username.' for '.$event_lead_name. ' ('.$event_type.')',
                            'notificationText' =>$event_activity_name.' scheduled - '.date('Y-m-d H:i:s', strtotime($event_start)),
                            'from_user'=>$user_id,
                            'to_user'=>$rep_id,
                            'action_details'=>'Task',
                            'notificationTimestamp'=>$dt,
                            'read_state'=>0,
                            'remarks'=>'Task added',
                            'show_status'=>0,
                            'task_id'=>$event_lead
                        ); 

                        array_push($notificationDataArray,$notificationData); 
                        // pushing notification data to the array for batch inserting.
                    }

                    $notificationsInsert = $this->mytask->insertNotificationData($notificationDataArray);

                if($members!='') {
                        $GLOBALS['$logger']->info('Invite members in array');
                        $users1=$membersArr;
                        $GLOBALS['$logger']->info($users1);
                        $msgbody1 = 'This is to update you on the upcoming activity ('.$event_activity_name.') at '.$event_start.' on '.$event_lead_name. '('.$event_type.') with '.$event_contact_name.'.';
                        $subject1 = $event_activity_name.' scheduled - '.$event_start;
                       $email1 = $this->lconnecttcommunication->send_email($users1,$subject1,$msgbody1);
                    }

                $users =$repIdArray;

                    $msgbody = 'There is a new task ('.$event_activity_name.') assigned by '.$username.' for you on the '.$event_lead_name. ' ('.$event_type.') with '.$event_contact_name.' on '.$event_start.'.';
                    $subject = $event_activity_name.' scheduled - '.$event_start;    


                $GLOBALS['$logger']->info('Inserting Data');
                $insert = $this->mytask->insert_reminder($batchArray);
                $GLOBALS['$logger']->info('Inserted');
                    if($insert==1){
                        $GLOBALS['$logger']->info('Sending invite to members');
                         $email = $this->lconnecttcommunication->send_email($users,$subject,$msgbody); 
                         //sending notifications for all the email alert users
                            $memberids = implode("','", $membersArr);
                            $res = $this->CI->db->query("SELECT user_id as user, user_name,user_primary_email FROM user_details WHERE user_id IN ('$memberids')");                            
                            $result = $res->result();                            
                            $notifyArray = array();

                            foreach ($result as $val) {
                                $notify_id = uniqid($dt); // notification id is uniquely created.
                                $notificationData1 = array(
                                'notificationID' => $notify_id.rand(),
                                'notificationShortText'=>'Task Assigned by '.$username.' for '.$event_lead_name. ' ('.$event_type.')',
                                'notificationText' => $event_activity_name.' scheduled - '.date('Y/m/d H:i:s', strtotime($event_start)),
                                'from_user'=> $user_id,
                                'to_user'=> $val->user,
                                'action_details'=> 'Task',
                                'notificationTimestamp'=> $dt,
                                'read_state'=>0,
                                'remarks'=>'Task added',
                                'show_status'=>0,
                                'task_id'=> $event_lead
                                );      

                                array_push($notifyArray, $notificationData1);                    
                            }

                            $notificationsInsert1 = $this->mytask->insertNotificationData($notifyArray);

                         $GLOBALS['$logger']->info('Email Sent Successfully'); 
                        echo json_encode($insert);
                    }
                    else{   
                        echo json_encode($insert);
                    }

            }catch (LConnectApplicationException $e)  {
                    $GLOBALS['$logger']->info('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');

                    $errorArray = array(
                            'errorCode' => $e->getErrorCode(), 
                            'errorMsg' => $e->getErrorMessage()
                    );
                    $GLOBALS['$logger']->info('Exception JSON to view - '.json_encode($errorArray));
                    $GLOBALS['$logger']->info("/-------------------------------------------------------------------------/");
                    echo json_encode($errorArray);
            }           
        } else {
                redirect('indexController');
        }
    }


    public function add_mytaskcomplete()   {
        if($this->session->userdata('uid')){
            try{
               // $user_id=$this->session->userdata('uid');
                $json = file_get_contents("php://input");
                $data = json_decode($json);   

                $rep_id = $this->session->userdata("uid");
                $event_lead=$data->event_lead;
                $event_type=$data->event_type;   
                $event_title = $data->event_title;         
                // it is taken care for customer or lead pending work              
                $leademployeeid=$data->event_contact;
                $logtype=$data->event_activity;       
                $call_type=$data->event_rating;
                $camp_note=$data->camp_note;
                $cmp_phone=$data->cmp_phone;  
                $event_start = $data->event_start_date;
                //contains start of the event
                //contains the estimated duration of the activity
                $duration=$data->cmp_duration;
                //start date and start time split from the obtained event_start
                $event_start_date = date('Y-m-d', strtotime($event_start));
                $event_start_time = date('H:i', strtotime($event_start));
                //get seconds from cmp_duration
                $seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
                $activity_duration = (int)$seconds->getTimestamp();
                //add seconds to date object 1(start) and make it date object 2(end)
                $start = new DateTime($event_start);
                $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
                $event_end = $event_end->format('Y-m-d H:i:s');
               // $event_end = date('Y-m-d H:i:s');
                $oppStageId=NULL;
                
                if($event_type=='opportunity'){
                    $oppStage=$this->mytask->getOpportunityStageId($event_lead);
                    $oppStageId=$oppStage[0]->opportunity_stage;  
                }                
                
                $data1 = array(
                    'log_name'=>$event_title,
                    'rep_id'    => $rep_id,
                    'leademployeeid' => $leademployeeid,
                    'leadid'    => $event_lead,           
                    'phone'     => $cmp_phone,
                    'logtype'   => $logtype,            
                    'rating' => $call_type,
                    'call_type'=>'complete',
                    'note'      => $camp_note,
                    'time'      => date('Y-m-d H:i:s'),
                    'starttime' => $event_start,
                    'endtime'   => $event_end, 
                    'type' => $event_type,
                    'module_id'=>'sales',
                    'stage_id'=>$oppStageId,
                    'log_method'=>'manual'
                );
                
                $insert = $this->mytask->insert_taskcomplete($data1);
                
                $update=$this->mytask->insert_repinfo($rep_id,$event_lead);
                echo json_encode($insert);
            }catch (LConnectApplicationException $e)  {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                    $errorArray = array(
                            'errorCode' => $e->getErrorCode(), 
                            'errorMsg' => $e->getErrorMessage()
                    );
                    $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                    $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                    echo json_encode($errorArray);
            }   
        } else {
            redirect('indexController');            
        }
    }


    // New Changes Functions  

    // New Changes Functions.


    public function rescheduleEvent()
    {
        if($this->session->userdata('uid')){
            try{
                // reschedule event when user want to complete activity manually.
                $GLOBALS['$logger']->info('reschedule old task event funtion ');
                $user_id=$this->session->userdata('uid'); // Session id
                $json = file_get_contents("php://input"); 
                $username = $this->session->userdata('uname'); // Session name
                $data = json_decode($json);
                $GLOBALS['$logger']->info('Data for the reschedule event');
                $GLOBALS['$logger']->info($data);

                // assign json values based on keys.

                $lead_reminder_id=$data->lead_reminder_id;
                $rescheduleRemarks=$data->camp_note1;
                $leadName = $data->cmp_lead_name;
                $assignedUserName =$data->cmp_member_name;
                $contactName = $data->cmp_contact_name;
                $activityName = $data->cmp_activity_name;
                $contactUserId =$data->cmp_member_id;
                $activityStartTime=$data->cmp_meeting_start;



                // update status as reschedule based on lead reminder id.

                $data = array('remarks'=>$rescheduleRemarks,
                              'status'=>'reschedule',
                              'created_by'=>$user_id);

                $update = $this->mytask->update_reminderschedule($data,$lead_reminder_id);

                echo $update;

            }
            catch(LConnectApplicationException $e){
                    echo $this->exceptionThrower($e); 
            }
        }
        else{
            redirect('indexController');
        }
    }

    public function completeTaskManually()
    {
        if($this->session->userdata('uid'))
        {
            try
            {
                // this function is called when user is trying to complete the task manually after rescheduling.

                $GLOBALS['$logger']->info('Completed Activity After rescheduling');
                // Session id & Name.
                $user_id = $this->session->userdata('uid');
                $userName = $this->session->userdata('uname');
                $GLOBALS['$logger']->info('Session Id '.$user_id);

                //JSON data .
                $json = file_get_contents("php://input");
                $data = json_decode($json);

                // assign json decoded values.


                $lead_reminder_id=$data->lead_reminder_id;             
                $note2=$data->note2;            
                $logtype=$data->act2;   
                $employeeid=$data->contact3;
                $leadid=$data->lead3;           
                $phone2=$data->phone1;
                $phone2 = json_decode($phone2);
                $phone1=$phone2->phone[0];
                $conntype = $data->conntype;
                $rating=0;
                $membersArr = (array) $data->reminder_members;
                $members = join(",", $membersArr);
                $reminder_time=$data->reminder_time;
                $event_start = $data->start_date2;
                $activity_owner = $data->person_id;
                $type = $data->type;
                $event_title = $data->event_name;
                $timestamp=date('Y-m-d H:i:s');
                //To get reporting person 
                $ReportingTo=$this->mytask->fetch_reporting($user_id);
                $reporting_to=$ReportingTo[0]->report;
                //contains the estimated duration of the activity
                $duration=  $data->actve_duration2;

                //start date and start time split from the obtained event_start
                $event_start_date = date('Y-m-d', strtotime($event_start));
                $event_start_time = date('H:i', strtotime($event_start));

                //get seconds from cmp_duration
                $seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
                $activity_duration = (int)$seconds->getTimestamp();
                //add seconds to date object 1(start) and make it date object 2(end)
                $start = new DateTime($event_start);
                $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
                $event_end = $event_end->format('Y-m-d H:i:s');

                $moduleName = '';
                $getOwner = '';
                $oppStageId=NULL;

                if($type == 'lead') {
                $GLOBALS['$logger']->info('Fetching Contact Type Owner Module Data');
                $getOwner = $this->mytask->checkLeadOwner($leadid);
                    if($getOwner[0]->rep_owner == $user_id){
                        $moduleName = 'sales';
                    }
                    else{
                        $moduleName = 'manager';
                    }
                $GLOBALS['$logger']->info('Module Name'. $moduleName);
                }
                else if($type == 'customer') {
                    $GLOBALS['$logger']->info('Fetching Contact Type Owner Module Data');
                    $getOwner = $this->mytask->checkCustomerOwner($leadid);
                     if($getOwner[0]->rep_owner == $user_id){
                        $moduleName = 'sales';
                    }
                    else{
                        $moduleName = 'manager';
                    }
                    $GLOBALS['$logger']->info('Module Name'. $moduleName);

                }
                else if($type == 'opportunity') {
                    $GLOBALS['$logger']->info('Fetching Contact Type Owner Module Data');
                    $getOwner = $this->mytask->checkOpportunityOwner($leadid);
                    $oppStage=$this->mytask->getOpportunityStageId($leadid);
                    $oppStageId=$oppStage[0]->opportunity_stage;
                     if($getOwner[0]->rep_owner == $user_id){
                        $moduleName = 'sales';
                    }
                    else{
                        $moduleName = 'manager';
                    }
                    $GLOBALS['$logger']->info('Module Name'. $moduleName);
                } 
                else{
                    $getOwner = $this->mytask->checkForTheModule($user_id);
                    $GLOBALS['$logger']->info('Fetching Contact Type Owner Module Data');

                    if($getOwner[0]->manager_owner !=NULL || $getOwner[0]->manager_owner != '' || $getOwner[0]->manager_owner != 0){
                        $moduleName = 'manager';
                    }
                    else if($getOwner[0]->rep_owner !=NULL || $getOwner[0]->rep_owner != '' || $getOwner[0]->rep_owner != 0)
                    {
                        $moduleName = 'sales';
                    }
                    else{
                        $moduleName = 'cxo';
                    }
                    $GLOBALS['$logger']->info('Module Name'. $moduleName);
                }                               

                // Completed Activity Data
                    $data2=array(
                    'log_name'=>$event_title,    
                    'note' => $note2,               
                    'call_type'=>'complete',
                    // 'reminderid'=>$lead_reminder_id,
                    'leademployeeid' => $employeeid,
                    'logtype' => $logtype,   
                    'leadid' => $leadid, 
                    'phone'=>$phone1,
                    'starttime'=>$event_start,
                    'endtime'=>$event_end,
                    'rep_id'=>$user_id,
                    'type'=>$type,
                    'time'=>$timestamp,
                    'rating'=>$rating,
                    'log_method'=>'manual',
                    'module_id'=>$moduleName,
                    'stage_id'=>$oppStageId
                    );

                    // Adding data to replog
                    $insert = $this->mytask->insert_repcomplete($data2);
                    $update=$this->mytask->insert_repinfo($user_id,$leadid);

                    if($insert == TRUE){
                        echo json_encode($insert);
                    }

            }
            catch(LConnectApplicationException $e)
            {
                echo $this->exceptionThrower($e); 
            }
        } 
        else{
            redirect('indexController');
        } 
    } 

      

    public function update_mytask(){
        if($this->session->userdata('uid')){
            try {
                    $user_id =$this->session->userdata('uid');
                    $username = $this->session->userdata('uname'); 
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $lead_reminder_id = $data->lead_reminder_id;
                    $camp_note=$data->camp_note;
                    $rating=$data->event_rating;
                    $leademployeeid=$data->cmp_contact;
                    $logtype=$data->cmp_activity;
                    $cmp_lead=$data->cmp_lead;
                    $cmp_phone1=$data->cmp_phone;
                    $cmp_phone1 = json_decode($cmp_phone1);
                    $cmp_phone=$cmp_phone1->phone[0];
                    $cmp_end_time=$data->cmp_end_time;
                    $personId=$user_id;
                    $event_title=$data->event_title;
                    $type=$data->type;
                    $status="complete";
                    $status1="scheduled";
                    $status2="reshcedule";
                    $oppStageId = "";

                    $completed_start_date=$data->completed_start_date;
                    $completed_start_time=$data->completed_start_time;

                    $start_date_time = $data->event_start_date." ".$data->cmp_start_time;
                    $start_date_time = date('Y-m-d H:i:s',strtotime($start_date_time));
                    $new_start_date_time = $data->completed_start_date."".$data->completed_start_time;
                    $new_start_date_time = date('Y-m-d H:i:s',strtotime($new_start_date_time));


                    //get seconds from cmp_duration
                    $s = new DateTime("1970-01-01 $data->cmp_end_time", new DateTimeZone('UTC'));
                    $seconds = (int)$s->getTimestamp();

                    $s1 = new DateTime("1970-01-01 $data->cmp_end_time", new DateTimeZone('UTC'));
                    $seconds1 = (int)$s1->getTimestamp();
                    //add seconds to date object 1 and make it date object 2
                    $start = new DateTime($start_date_time);
                    $start1 = new DateTime($new_start_date_time);
                    $end_date_time = $start->add(new DateInterval('PT'.$seconds.'S')); // adds 674165 secs
                    $new_end_date_time = $start1->add(new DateInterval('PT'.$seconds1.'S'));
                    $end_date_time = $end_date_time->format('Y-m-d H:i:s');
                    $new_end_date_time = $new_end_date_time->format('Y-m-d H:i:s');
                    $timestamp = date('Y-m-d H:i:s');
                    
                    $notifyUpdateData = array('show_status'=>'1');

                    $this->mytask->notificationShowStatus($notifyUpdateData,$cmp_lead,$user_id);

                    if($type == 'opportunity') {
                            $GLOBALS['$logger']->info('Fetching Contact Type Owner Data');
                            $getOwner = $this->mytask->checkOpportunityOwner($cmp_lead);
                            $managerOwner=$getOwner[0]->manager_owner;
                            $oppStage=$this->mytask->getOpportunityStageId($cmp_lead);
                            $oppStageId = $oppStage[0]->opportunity_stage;
                    }

                    $data = array('remarks' => $camp_note);
                    $data1 = array(
                    'remarks' => $camp_note,
                    'status'=> $status,
                    'duration'=>$cmp_end_time,
                    'timestamp'=>$timestamp
                    );

                    $newData1=array(
                        'remarks' => $camp_note,
                        'status'=> $status,
                        'duration'=>$cmp_end_time,
                        'timestamp'=>$timestamp
                    );

                    $data3 = array(
                        'remarks' => $camp_note,
                        'status'=>'pending',
                        'duration'=>$cmp_end_time,
                        'timestamp'=>$timestamp
                    );

                    $newData3 = array(
                        'remarks' => $camp_note,
                        'status'=>$status1,
                        'duration'=>$cmp_end_time,
                        'timestamp'=>$timestamp
                    );

                    $dt = date('ymdHis');     
                    $lead_reminder_id_new = '';
                    $lead_reminder_id_new .= $dt;
                    $lead_reminder_id_new = uniqid($lead_reminder_id_new);


                    $data2 = array(
                    'note' => $camp_note,   
                    'log_method'=>'manual',            
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
                    'type'=>$type,
                    'module_id'=>'sales',
                    'log_name'=>$event_title,
                    'stage_id'=>$oppStageId
                    );

                    $newData2 = array(
                    'note' => $camp_note,   
                    'log_method'=>'manual',            
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
                    'type'=>$type,
                    'module_id'=>'sales',
                    'log_name'=>$event_title,
                    'stage_id'=>$oppStageId
                    );

                   

                    $newSchedule=array(
                                        'lead_reminder_id' => $lead_reminder_id_new,
                                        'lead_id'   => $cmp_lead,
                                        'rep_id'    => $personId,
                                       'leadempid' => $leademployeeid,
                                        'remi_date' => $completed_start_date,
                                       'rem_time'  => $completed_start_time,
                                       'conntype'  => $logtype,
                                        'status'    =>  'complete',
                                       'meeting_start'    => $new_start_date_time,
                                       'meeting_end'      => $new_end_date_time,
                                     //  'addremtime'       => $reminder_time,          
                                        'timestamp'        => $timestamp,
                                        'remarks'          => $camp_note,
                                        'event_name'       => $event_title,
                                       'duration'         => $cmp_end_time,
                                   //     'managerid' =>$reporting_to,
                                        'type'=>$type,
                                        'created_by'=>$user_id 
                                    );

                    $batchArray=array();
                    array_push($batchArray,$newSchedule);
                    $updateArray=array(
                        'lead_reminder_id'=>$lead_reminder_id,
                        'status'=>'reschedule'
                    );

                            if($rating==0)  {
                            $update = $this->mytask->update_reminder($data3,$lead_reminder_id);
                            echo json_encode($update);
                            }
                            else {
                            $update = $this->mytask->update_remindercomplete($data1,$lead_reminder_id,$user_id);
                            $insert = $this->mytask->insert_repcomplete($data2);
                            if($update==1){
                            if($type =='lead'){
                               $getOwner = $this->mytask->checkLeadOwner($cmp_lead);
                                if($getOwner[0]->rep_owner == $user_id){
                                    $update1=$this->mytask->insert_repinfo($user_id,$cmp_lead);
                                }
                                else{
                                    $update1=0;
                                } 
                            }
                            else if($type == 'customer'){
                                $getOwner = $this->mytask->checkCustomerOwner($cmp_lead);
                                if($getOwner[0]->rep_owner == $user_id){
                                    $update1=$this->mytask->insert_repinfo($user_id,$cmp_lead);
                                }
                                else{
                                    $update1=0;
                                }
                            }
                            echo json_encode($update);
                            }         
                            //   if the manager has got the sales module, he will perform the same from there
                           
                            } 

                    

/*                    if($new_start_date_time!=$start_date_time){
                        
                    $updateOldEventReschedule = $this->mytask->updateOldEventReschedule($updateArray,$lead_reminder_id);
                    $insertRescheduledData = $this->mytask->insert_reminder($batchArray); 
                            if($insertRescheduledData==1){
                            $insert = $this->mytask->insert_repcomplete($newData2);
                            if($type =='lead'){
                               $getOwner = $this->mytask->checkLeadOwner($cmp_lead);
                                if($getOwner[0]->rep_owner == $user_id){
                                    $update1=$this->mytask->insert_repinfo($user_id,$cmp_lead);
                                }
                                else{
                                    $update1=0;
                                } 
                            }
                            else if($type == 'customer'){
                                $getOwner = $this->mytask->checkCustomerOwner($cmp_lead);
                                if($getOwner[0]->rep_owner == $user_id){
                                    $update1=$this->mytask->insert_repinfo($user_id,$cmp_lead);
                                }
                                else{
                                    $update1=0;
                                }
                            }

                            echo json_encode($insertRescheduledData);
                            }         
                            //   if the manager has got the sales module, he will perform the same from there
                                         
                    }
                    else{
                            if($rating==0)  {
                            $update = $this->mytask->update_reminder($data3,$lead_reminder_id);
                            echo json_encode($update);
                            }
                            else {
                            $update = $this->mytask->update_remindercomplete($data1,$lead_reminder_id,$user_id);
                            $insert = $this->mytask->insert_repcomplete($data2);
                            if($update==1){
                            if($type =='lead'){
                               $getOwner = $this->mytask->checkLeadOwner($cmp_lead);
                                if($getOwner[0]->rep_owner == $user_id){
                                    $update1=$this->mytask->insert_repinfo($user_id,$cmp_lead);
                                }
                                else{
                                    $update1=0;
                                } 
                            }
                            else if($type == 'customer'){
                                $getOwner = $this->mytask->checkCustomerOwner($cmp_lead);
                                if($getOwner[0]->rep_owner == $user_id){
                                    $update1=$this->mytask->insert_repinfo($user_id,$cmp_lead);
                                }
                                else{
                                    $update1=0;
                                }
                            }
                            echo json_encode($update);
                            }         
                            //   if the manager has got the sales module, he will perform the same from there
                           
                            } 
                    }*/

                    
            }
            catch (LConnectApplicationException $e)  {
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
        else {
        redirect('indexController');
        }

        }
    

    public function update_reschedule() {
        if($this->session->userdata('uid')){
            try{
                $json = file_get_contents("php://input");
                $username = $this->session->userdata('uname');
                $data = json_decode($json);
                $lead_reminder_id=$data->lead_reminder_id;             
                $note2=$data->note2;            
                $logtype=$data->act2;   
                $employeeid=$data->contact3;
                $leadid=$data->lead3;           
                $phone2=$data->phone1;
                $phone2 = json_decode($phone2);
                $phone1=$phone2->phone[0];
                $conntype = $data->conntype;
                $rating=0;
                $reminder_time=$data->reminder_time;
                $event_start = $data->start_date2;
                $membersArr = (array) $data->reminder_members;
                $members = join(",", $membersArr);
                $activity_owner = $this->session->userdata('uid');
                $user_id = $this->session->userdata('uid');
                $type = $data->type;
                $event_title = $data->event_name;
                $ReportingTo=$this->mytask->fetch_reporting($activity_owner);
                $reporting_to=$ReportingTo[0]->report;
                //contains the estimated duration of the activity
                $duration=  $data->actve_duration2;
                //start date and start time split from the obtained event_start
                $event_start_date = date('Y-m-d', strtotime($event_start));
                $event_start_time = date('H:i', strtotime($event_start));
                //get seconds from cmp_duration
                $seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
                $activity_duration = (int)$seconds->getTimestamp();
                //add seconds to date object 1(start) and make it date object 2(end)
                $start = new DateTime($event_start);
                $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
                $event_end = $event_end->format('Y-m-d H:i:s');
                //$reminder_members = (array) $data->email_members;
                //$members = join(",", $reminder_members);

                $status="reschedule";
                $timestamp=date('Y-m-d H:i:s');
                $notificationDataArray = array();

                //Updating Notiffication Status. 
                $notifyUpdateData = array('show_status'=>'1');

                $this->mytask->notificationShowStatus($notifyUpdateData,$leadid,$user_id);                

                //This array data will be used to update reschedule data                     
                $data1=array(
                // 'remi_date' => $event_start_date,
                //    'remarks' => $note2,
                //    'rem_time' => $event_start_time,
                //    'addremtime'=>$reminder_time,          
                //    'meeting_start'=>$event_start,
                     'status'=>$status,
                    'timestamp'=>$timestamp,
                //    'meeting_end'=>$event_end
                );

                $data2=array(
                'log_name'=>$event_title, 
                'note' => $note2,               
                'call_type'=>'reschedule',
                'reminderid'=>$lead_reminder_id,
                'leademployeeid' => $employeeid,
                'logtype' => $logtype,   
                'leadid' => $leadid, 
                'phone'=>$phone1,
                'starttime'=>$event_start,
                'endtime'=>$event_end,
                'rep_id'=>$user_id,
                'type'=>$type,
                'time'=>$timestamp,
                'rating'=>$rating,
                'log_method'=>'manual',
                'module_id'=>'sales'
                );

                $dt = date('ymdHis');     
                $lead_reminder_id_new = '';
                $lead_reminder_id_new .= $dt;
                $lead_reminder_id_new = uniqid($lead_reminder_id_new);

                 $reshceduleData = array(
                                        'lead_reminder_id' => $lead_reminder_id_new,
                                        'lead_id'   => $leadid,
                                        'rep_id'    => $activity_owner,
                                       'leadempid' => $employeeid,
                                        'remi_date' => $event_start_date,
                                       'rem_time'  => $event_start_time,
                                       'conntype'  => $conntype,
                                        'status'    =>  'scheduled',
                                       'meeting_start'    => $event_start,
                                       'meeting_end'      => $event_end,
                                       'addremtime'       => $reminder_time,          
                                        'timestamp'        => $timestamp,
                                        'remarks'          => $note2,
                                        'event_name'       => $event_title,
                                       'duration'         => $duration,
                                        'managerid' =>$reporting_to,
                                        'type'=>$type,
                                        'created_by'=>$user_id,
                                        'module_id'=>'sales',
                                        'reminder_members'=>$members  
                                    );
                 $batchArray=array();
                 array_push($batchArray,$reshceduleData);

                 // notification inserting . 
                    $dt = date('ymdHis');
                    $notify_id= uniqid($dt);
                    $notificationDataArray = array();
                    $notificationData= array(
                     'notificationID' =>$notify_id,
                     'notificationShortText'=>'Task '.$data->cmp_activity_name.' rescheduled by '.$username.' for '.$data->cmp_lead_name. ' ('.$type.')',
                     'notificationText' =>$data->cmp_activity_name.' rescheduled - '.date('Y-m-d H:i:s', strtotime($event_start)),
                     'from_user'=>$user_id,
                     'to_user'=>$activity_owner,
                     'action_details'=>'Task',
                     'notificationTimestamp'=>$dt,
                     'read_state'=>0,
                     'remarks'=>'Task Created',
                     'show_status'=>0,
                     'task_id'=>$leadid
                    );
                    array_push($notificationDataArray,$notificationData); //array_push to notificationDataArray

                    //batching inserting. 
                    $notificationsInsert = $this->mytask->insertNotificationData($notificationDataArray);
                    

                $update = $this->mytask->update_reminderschedule($data1,$lead_reminder_id);
                $insert = $this->mytask->insert_repcomplete($data2);
                $insertRescheduledData = $this->mytask->insert_reminder($batchArray);
                $users1 = $membersArr;
                    if($members!='') {
                        $msgbody1 = 'There is a new task ('.$data->cmp_activity_name.')  assigned by '.$username.' for you on the '.$data->cmp_lead_name. ' ('.$type.') with '.$data->cmp_contact_name.' on '.$event_start.'.';
                        $subject1 = $data->cmp_activity_name.' scheduled - '.$event_start;
                        $email1 = $this->lconnecttcommunication->send_email($membersArr ,$subject1,$msgbody1); 
                    }
                $users = array($activity_owner);
                $msgbody = 'There is a rescheduled task ('.$data->cmp_activity_name.')  assigned by '.$username.' for you on the '.$data->cmp_lead_name. ' ('.$type.') with '.$data->cmp_contact_name.' on '.$event_start.'.';
                $subject = $data->cmp_activity_name.' scheduled - '.$event_start;

                    if($insertRescheduledData ==1){
                        $email = $this->lconnecttcommunication->send_email($users,$subject,$msgbody);  
                        echo json_encode($insertRescheduledData);
                        }
                else{                      
                    echo json_encode($insertRescheduledData);
                } 
                
            }catch (LConnectApplicationException $e)  {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                    $errorArray = array(
                            'errorCode' => $e->getErrorCode(), 
                            'errorMsg' => $e->getErrorMessage()
                    );
                    $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                    $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                    echo json_encode($errorArray);
            }           
        }else {
            redirect('indexController'); 
        }
    }
    public function cancelEvent(){
       if($this->session->userdata('uid')){
            try{
                    $user_id =$this->session->userdata('uid');
                    $json = file_get_contents("php://input"); 
                    $data=json_decode($json); 
                    $lead_reminder_id=$data->lead_reminder_id;
                    $camp_note1=$data->camp_note1;
                    $data1 = array(
                        'cancel_remarks'=>$camp_note1,
                        'status'=>'cancel',
                        'created_by'=>$user_id,
                        'timestamp' => date('Y-m-d H:i:s')
                        );

                    $cancel=$this->mytask->cancelTask($lead_reminder_id,$data1);
                    if($cancel==1){
                        $data = $this->mytask->fetch_mytask($user_id, '');
                        echo json_encode($data); 
                    }
            }
            catch (LConnectApplicationException $e)  {
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
       else {
        redirect('indexController');
        }
    }

    public function get_contactsForOpportunity() {
        if($this->session->userdata('uid')){
            try {   
                    $GLOBALS['$logger']->info('UserID : '.$this->session->userdata('uid')); 
                    $user =$this->session->userdata('uid');
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $GLOBALS['$logger']->info('Fetching Opportunity Contacts For User -' . $user); 
                    $oppData=$this->mytask->fetch_contactsForOpportunity($data->opp_id);
                    $GLOBALS['$logger']->info('Response:'); 
                    $GLOBALS['$logger']->info($oppData); 
                    echo json_encode($oppData);
            }
            catch(LConnectApplicationException $e){
                    echo $this->exceptionThrower($e);
            }
            
        }else{
            redirect('indexController');
        }  
    }
    
    public function get_emails()    {
        if($this->session->userdata('uid')){   
            try{         
                $user = $this->session->userdata('uid');
                $data=$this->mytask->fetch_emails($user);
                echo json_encode($data);
            }catch (LConnectApplicationException $e)  {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                    $errorArray = array(
                            'errorCode' => $e->getErrorCode(), 
                            'errorMsg' => $e->getErrorMessage()
                    );
                    $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                    $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                    echo json_encode($errorArray);
            }           
        }else {
            redirect('indexController');
        }
    }

    public function get_members()    {
        if($this->session->userdata('uid')){   
            try{
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $leadid = $data->leadid;

                $user = $this->session->userdata('uid');
                $data=$this->mytask->fetch_members($user, $leadid);                
                echo json_encode($data);
            }catch (LConnectApplicationException $e)  {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                    $errorArray = array(
                            'errorCode' => $e->getErrorCode(), 
                            'errorMsg' => $e->getErrorMessage()
                    );
                    $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                    $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                    echo json_encode($errorArray);
            }         
        } else {
            redirect('indexController');
        }
    }

    public function get_editable_emails()    {
        if($this->session->userdata('uid')){
            try{
                $user_id = $this->session->userdata('uid');
                $sendMailID = array();

                $json = file_get_contents("php://input");   
                $data = json_decode($json);

                $savedIDs = explode(',', $data->remmem);   
                $users=$this->mytask->fetch_emails($user_id);
                        foreach ($users as $user) {
                            $user_id = $user->user_id;
                            $user_name = $user->user_name;
                            foreach ($savedIDs as $savedID) {
                                if ($user_id ==  $savedID) {
                                    $arrayName = array('user_id' => $user_id, 'user_name' => $user_name);
                                    array_push($sendMailID, $arrayName);
                                }
                            }
                        }
            //get reminder_members and parse them for ',' and form an array
            //fetch just user_id and user_name for elements in above array 
                $var['allEmailID'] = $users;
                $var['sendSavedID'] = $sendMailID;
                echo json_encode($var);
            }catch (LConnectApplicationException $e)  {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                    $errorArray = array(
                            'errorCode' => $e->getErrorCode(), 
                            'errorMsg' => $e->getErrorMessage()
                    );
                    $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                    $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                    echo json_encode($errorArray);
            }         
        } else {
            redirect('indexController');
        }
    }

    public function get_taskcal()   {
        if($this->session->userdata('uid')){  
            try{
                $json = file_get_contents("php://input"); 
                $data1=json_decode($json);  
                $id=$data1->id; 
                $user = $this->session->userdata('uid');
                $data = $this->mytask->fetch_mytask($user, $id);
                echo json_encode($data);
            }catch (LConnectApplicationException $e)  {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                    $errorArray = array(
                            'errorCode' => $e->getErrorCode(), 
                            'errorMsg' => $e->getErrorMessage()
                    );
                    $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                    $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                    echo json_encode($errorArray);
            }   
        } else {
            redirect('indexController');
        }
    }

    public function get_opportunities(){
        if($this->session->userdata('uid')){
            try {
                    $user =$this->session->userdata('uid');
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);                   
                    $oppData=$this->mytask->fetch_opportunities($user);
                    echo json_encode($oppData);
            }catch (LConnectApplicationException $e)  {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                    $errorArray = array(
                            'errorCode' => $e->getErrorCode(), 
                            'errorMsg' => $e->getErrorMessage()
                    );
                    $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                    $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                    echo json_encode($errorArray);
            }         
        } else {
            redirect('indexController');
        }
    }


    public function checkForPending() {
        if($this->session->userdata('uid')){
            try {
                $data=array();
                $insertArray=array();              
                $dbCurrentTimeData = $this->mytask->selectCurrentData(); 
                $data=json_decode(json_encode($dbCurrentTimeData), true);  
                          
                foreach ($data as $lid) {
                    $data2=array(
                    'lead_reminder_id'=>$lid['lead_reminder_id'],
                    'status'=>'pending');
                   array_push($insertArray, $data2);                  
                }                               
                $update_schedule=$this->mytask->update_schedule_reminder($insertArray);  
                echo $update_schedule;    
               
            }
            catch (LConnectApplicationException $e)  {
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

      public function getCompletedReshceduleTask(){
        if($this->session->userdata('uid')){
            try{
                $user_id=$this->session->userdata('uid');         
                $data = $this->mytask->fetch_completetask($user_id, '');
                $data1= $this->mytask->fetch_mytaskCompleted1($user_id,'');
                $dataArray1=array_merge($data,$data1);
                $data3 = $this->mytask->fetch_mytaskCompletedReplog($user_id, '');
                $data4=array_merge($dataArray1,$data3);
                $data5=$this->mytask->fetch_mytaskCompletedReplogInternal($user_id,'');

                $dataArray = array_merge($data4,$data5);
                $time = array();
                foreach ($dataArray as $key => $value) 
                {
                   $time[$key] = $value->closed_date;
                }
                array_multisort($time,SORT_DESC,$dataArray);
                $completedArray=array('completedArray'=>$dataArray,'user_id'=>$user_id);
                echo json_encode($completedArray);   


            }
            catch (LConnectApplicationException $e)  {
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
        else {
        redirect('indexController');
        }   
    }

    public function get_support_request(){
        if($this->session->userdata('uid')){
            try {
                    $GLOBALS['$logger']->info('UserID : '.$this->session->userdata('uid')); 
                    $user_id =$this->session->userdata('uid');
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $leadid=$data->leadid;
                    $GLOBALS['$logger']->info('Fetching team members under the user and him'); 
                    $data=$this->mytask->fetch_Support($user_id,$leadid);
                    $GLOBALS['$logger']->info('Response'); 
                    $GLOBALS['$logger']->info($data); 
                    echo json_encode($data);
            }
            catch(LConnectApplicationException $e){
                    echo $this->exceptionThrower($e); 
            }
            
        }else{
            redirect('indexController');
        }
     
    }    

    public function get_rep_log() {

        $repId= trim($_POST['lead_userid']," ");
        $result_set= array();
        $repLogData=$this->mytask->get_rep_log($repId);
        if(count($repLogData)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $repLogData;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }
    }


    public function insert_reminders() {
       
       $result = array();
       $dt = date('ymdHis');     
       $lead_reminder_id = '';
       $lead_reminder_id .= $dt;
       $lead_reminder_id = uniqid($lead_reminder_id);
       $lead_id =$_POST['lead_id'];
       $rep_id = $_POST['rep_id'];
       $managerid=$_POST['managerid'];
       $leadempid=$_POST['leadempid'];
       $remi_date=$_POST['remi_date'];
       $rem_time = $_POST['rem_time'];
       $conntype = $_POST['conntype'];
       $status = $_POST['status'];
       $meeting_start= $_POST['meeting_start'];
       $meeting_start_time = date('Y-m-d', strtotime($meeting_start));
       $meeting_start_time = date('H:i', strtotime($meeting_start));

        //get seconds from cmp_duration
        $seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
        $activity_duration = (int)$seconds->getTimestamp();
        //add seconds to date object 1(start) and make it date object 2(end)
        $start = new DateTime($meeting_start);
        $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
        $meeting_end = $event_end->format('Y-m-d H:i:s');
       $timestamp =$_POST['timestamp'];
       $remarks = $_POST['remarks'];
       $event_name=$_POST['event_name'];               
       $duration=$_POST['duration'];     
       $type=$_POST['type'];
       $addremtime=$_POST['addremtime'];
       $created_by=$_POST['rep_id'];


        $data1 = array( 
                        'lead_reminder_id'   => $lead_reminder_id,
                        'lead_id'            => $lead_id,
                        'rep_id'             => $rep_id,
                        'managerid'          =>$managerid,
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
                        'created_by'         =>$created_by,
                        'module_id'          =>'sales'
                );


    
            $insert = $this->mytask->Phone_insert_reminders($data1);

            if($insert==TRUE){
            $success = true;
            $result['success'] = true;
             echo json_encode($result);
            }else{
            $success = false;
            $result['success'] = false;
            echo json_encode($result);
            }       
          
    }

public function insert_rep_log(){
    $result = array();   
    $rep_id=trim($_POST['rep_id']," ");    
    $leademployeeid=$_POST['leademployeeid'];     
    $leadid=$_POST['leadid'];
    $phone=$_POST['phone'];
    $logtype=$_POST['logtype'];
    $rating=$_POST['rating'];    
    $note=$_POST['note'];   
    $starttime=$_POST['starttime'];
    $type=$_POST['type'];  
    $log_name=$_POST['log_name'];
    $log_method=$_POST['log_method'];
    $time =$_POST['timestamp'];
    $event_start_date = date('Y-m-d', strtotime($starttime));
                    $event_start_time = date('H:i', strtotime($starttime));

                    //get seconds from cmp_duration
                    $seconds = new DateTime("1970-01-01 $data->cmp_duration", new DateTimeZone('UTC'));
                    $activity_duration = (int)$seconds->getTimestamp();
                    //add seconds to date object 1(start) and make it date object 2(end)
                    $start = new DateTime($event_start);
                    $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
                    $event_end = $event_end->format('Y-m-d H:i:s');

       $data1 = array(
                    'rep_id'    => $rep_id,
                    'leademployeeid' => $leademployeeid,
                    'leadid'    => $leadid,           
                    'phone'     => $phone,
                    'logtype'   => $logtype,            
                    'rating' => $rating,
                    'call_type'=>'complete',
                    'note'      => $note,
                    'time'      => $time,
                    'starttime' => $event_start_date,
                    'endtime'   => $event_end, 
                    'type' => $type,
                    'module_id'=>'sales',
                    'log_name'=>$log_name,
                    'log_method'=>$log_method
                );                              
    $insert = $this->mytask->insert_phone_replog($data1);        
    echo json_encode($insert);
    if($insert==TRUE){
            $success = true;
            $result['success'] = true;
             echo json_encode($result);
        }else{
           $success = false;
           $result['success'] = false;
           echo json_encode($result);
       }       
    }

    public function open_tasklist() {
        $result_set = array();
        $repId=$_POST['lead_userid'];
        print_r($repId);exit();
        
        $openData = $this->mytask->Phone_open_tasklist($repId);
         if(count($openData)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $openData;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }

    }

    public function Completed_tasklist(){
        $result_set = array();
        $user_id=$_POST['user_id']; 
        $data = $this->mytask->fetch_completetask($user_id, '');
        $data1= $this->mytask->fetch_mytaskCompleted1($user_id,'');
        $dataArray1=array_merge($data,$data1);
        $data3 = $this->mytask->fetch_mytaskCompletedReplog($user_id, '');
        $data4=array_merge($dataArray1,$data3);
        $data5=$this->mytask->fetch_mytaskCompletedReplogInternal($user_id,'');
        $dataArray = array_merge($data4,$data5);
    
         if(count($dataArray)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $dataArray;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }

    }

    public function User_details(){
        $result_set = array();
        $repId=$_POST['user_id'];
        $userName = $this->mytask->fetch_userName($repId);
         if(count($userName)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $userName;
          echo json_encode( $result_set);
        } else {
          $success = false;
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
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $assignedForName;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }

    }


    public function Phone_get_mytask()    {
      $result_set = array(); 
      $user_id=$_POST['user_id'];       
      $data = $this->mytask->fetch_mytask($user_id, '');
      $data1= $this->mytask->fetch_mytask1($user_id, '');
      $taskArray= array_merge($data, $data1);
      if(count($taskArray)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $taskArray;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }       
                        
    }


    public function phoneGetLeads() {
      $result_set = array(); 
      $user_id=$_POST['user_id'];       
      $leadData = $this->mytask->getLeadsForPhone($user_id);
      if(count($leadData)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $leadData;
          echo json_encode( $result_set);
        } else {
          $success = false;
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
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $customerData1;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        } 
    }

  public function phoneGetOpportunities(){
       $result_set = array(); 
       $user_id=$_POST['user_id'];     
      $opportunitiesData = $this->mytask->getOpportunitiesForPhone($user_id);
      
      if(count($opportunitiesData)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $opportunitiesData;
          echo json_encode( $result_set);
        } else {
          $success = false;
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
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $contactsData;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }
  }

   public function phoneUpdateReminder($value='') {

        $result_set = array(); 
        $user_id=$_POST['rep_id'];  
        $lead_reminder_id = $_POST['lead_reminder_id'];
        $camp_note=$_POST['note'];
        $rating=$_POST['rating'];
        $leademployeeid=$_POST['leademployeeid'];
        $logtype=$_POST['logtype'];
        $cmp_lead=$_POST['leadid'];
        $cmp_phone=$_POST['phone'];  
        $cmp_end_time=$_POST['duration'];
        $personId =$user_id;
        $event_title=$_POST['title'];
        $type=$_POST['type'];
        $status="complete";
        $status1="scheduled";
        $status2="reschedule";

        $completed_start_date=$_POST['newstartdate'];
        $completed_start_time=$_POST['newstarttime'];

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
        $end_date_time = $end_date_time->format('Y-m-d h:i:s');
        $new_end_date_time = $new_end_date_time->format('Y-m-d h:i:s');

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
                                 //  'addremtime'       => $reminder_time,          
                                    'timestamp'        => $timestamp,
                                    'remarks'          => $camp_note,
                                    'event_name'       => $event_title,
                                   'duration'         => $cmp_end_time,
                               //     'managerid' =>$reporting_to,
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
                          //  $update1=$this->mytask->insert_repinfo($user_id,$cmp_lead);
                            echo json_encode($insertRescheduledData);
                            }         
                            //   if the manager has got the sales module, he will perform the same from there
                            
                                
                    }
                    else{
                            if($rating==0)  {
                            $update = $this->mytask->update_reminder($data3,$lead_reminder_id);
                            echo json_encode($update);
                            }
                            else {

                            $update = $this->mytask->update_remindercomplete($data1,$lead_reminder_id,$user_id);
                            if($update==1){
                            $insert = $this->mytask->insert_repcomplete($data2);  
                         //   $update1=$this->mytask->insert_repinfo($user_id,$cmp_lead);
                            echo json_encode($update);
                            }         
                            //   if the manager has got the sales module, he will perform the same from there
                           
                            } 
                    }                               
  }

    public function userList() {
         if($this->session->userdata('uid')){
            try {
                    $GLOBALS['$logger']->info('UserID : '.$this->session->userdata('uid')); 
                    $user =$this->session->userdata('uid');
                    $GLOBALS['$logger']->info('Fetching User List'.$user); 
                    $GLOBALS['$logger']->info('Fetching Internal Data'); 
                    $data =$this->mytask->fetch_internal1();
                    echo json_encode($data);
            }
            catch(LConnectApplicationException $e){
                     echo $this->exceptionThrower($e); 
            }

           
        }else{
            redirect('indexController');
        }
    }

     public function getAllActivitesOfLeadCustSup() {
       if($this->session->userdata('uid')){
            try {
                    $GLOBALS['$logger']->info('UserID : '.$this->session->userdata('uid')); 
                    $user =$this->session->userdata('uid');
                    $json = file_get_contents("php://input"); 
                    $data=json_decode($json);
                    $leadId = $data->leadid;
                    $leadEmpId = $data->leadempid;
                    $type = $data->type;
                    $date = $data->date;
                    $allActivities = '';
                    $GLOBALS['$logger']->info('Fetching All Activies of Contact Type:'.$leadId.' Contact Person: '.$leadEmpId ); 
                   if($type != 'internal' && $type != 'unassociated'){
                        $GLOBALS['$logger']->info('Fetching Lead/Customer/Support Data'); 
                        $allActivities = $this->mytask->fetchAllActivitesOfLeadCustSup($leadId,$leadEmpId,$date);
                        $GLOBALS['$logger']->info('Response Data'); 
                        $GLOBALS['$logger']->info($allActivities);
                    }
                    elseif ($type == 'unassociated') 
                    {                        
                        $GLOBALS['$logger']->info('Fetching Lead/Customer/Support Data'); 
                        $allActivities = $this->mytask->fetchAllActivitesOfUserMail($leadEmpId,$date);
                        $GLOBALS['$logger']->info('Response Data'); 
                        $GLOBALS['$logger']->info($allActivities);
                    }
                    else{
                        $GLOBALS['$logger']->info('Fetching Internal Data'); 
                        $allActivities = $this->mytask->fetchAllActivitesOfInternal($leadId,$leadEmpId,$date);
                        $GLOBALS['$logger']->info('Response Data'); 
                        $GLOBALS['$logger']->info($allActivities);
                    }
                    echo json_encode($allActivities);
            }
            catch(LConnectApplicationException $e){
                     echo $this->exceptionThrower($e); 
            }

           
        }else{
            redirect('indexController');
        }     
    }   

}

?>


