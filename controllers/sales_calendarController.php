<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_calendarController');
require 'utils.php';


class sales_calendarController extends Master_Controller    {
    
    public function __construct()   {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('sales_calendarModel','calendar');
    }
    
    public function index() {
        if($this->session->userdata('uid')){
            try{
                $id=$this->session->userdata('uid');
                $data=$this->calendar->fetch_mytask($id);
                $this->load->view('sales_calender_view',$data);
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
    
    public function initCal() {
        if($this->session->userdata('uid')){
                try{
                    $user_id =$this->session->userdata('uid');  
                    $data = $this->calendar->fetch_mytask($user_id);
                    $data1= $this->calendar->fetch_mytask1($user_id, '');
                    $input_arrays1=array_merge($data, $data1);
                    $data3=$this->calendar->fetch_completedReplog($user_id);
                    $input_arrays=array_merge($input_arrays1,$data3);
                    $input_arrays = json_encode($input_arrays);
                    $input_arrays = (array) json_decode($input_arrays);
                    // Accumulate an output array of event data arrays.
                    $output_arrays = array();
                    foreach ($input_arrays as $array) {
					// Convert the input array into a useful Event object
					$event = new Event((array)$array);
					if ($event->properties['status']=='complete') {
					$event->properties['color'] = 'green';
					} else if ($event->properties['status']=='scheduled') { 
					$event->properties['color'] = 'blue';
					}else if ($event->properties['status']=='pending') { 
					$event->properties['color'] = 'red';
					}
					else if ($event->properties['status']=='cancel') {
							$event->properties['color'] = 'purple';
					} else {
					$event->properties['color'] = 'orange';
					}
					// If the event is in-bounds, add it to the output
					$output_arrays[] = $event->toArray();
					}
                    // Send JSON to the client.
                    echo json_encode($output_arrays);
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

    public function get_mytask(){  
        if($this->session->userdata('uid')){
            try{
                $data=$this->calendar->fetch_mytask();
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
        }else{
            redirect('indexController');
        }    	
    }    
    public function get_leadcontact(){
        if($this->session->userdata('uid')){
            try{
                $var = $this->calendar->fetch_leadcontact();
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
        }else{
            redirect('indexController');
        }
       
    }
    
    public function get_contactsForLead(){
        if($this->session->userdata('uid')){
            try{
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $leadid = $data->leadid;
                $lead_data = $this->calendar->fetch_ContactsForLead($leadid);
                echo json_encode($lead_data);  
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
            try{
                $var = $this->calendar->fetch_activity();
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
        }else{
            redirect('indexController');
        }
    }
    
    public function get_atctivitycompleted()    {
        if($this->session->userdata('uid')){
            try{
                $var = $this->calendar->fetch_activity_complete();
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
        }else{
            redirect('indexController');
        }
    }

    public function add_mytask() {
        if($this->session->userdata('uid')){
            try{          
                $json = file_get_contents("php://input");
                $data = json_decode($json);     
                $event_title =$data->event_title;
                $lead_reminder_id=substr($event_title,0,2);
                $dt = date('ymdHis');     
                $lead_reminder_id.=$dt;
                $lead_reminder_id = uniqid($lead_reminder_id); //reminder_id uniquely created 
                $event_activity=$data->event_activity;
                $event_lead=$data->event_lead;
                $event_contact=$data->event_contact;
                $event_start_date=$data->event_start_date;
                $start_time=$data->start_time;
                $reminder_time=$data->reminder_time;
                $camp_note=$data->camp_note;    
                $start_time=$data->start_time;
                $status="pending";

                $data = array(
                    'lead_reminder_id' => $lead_reminder_id,
                    'lead_id' => $event_lead,           
                    'leadempid' => $event_contact,
                    'conntype' => $event_activity,
                    'event_name' => $event_title,
                    'remi_date' => $event_start_date,
                    'remarks' => $camp_note,
                    'addremtime'=>$reminder_time,          
                    'meeting_start'=>$start_time,
                    'status'=>$status
                );  

                $insert = $this->calendar->insert_reminder($data);
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
        }else{
            redirect('indexController');
        }
    }
    
    
    public function add_mytaskcomplete(){
        if($this->session->userdata('uid')){
            try{ 
                $json = file_get_contents("php://input");
                $data = json_decode($json);     
                $rep_id="A";
                $dt = date('ymdHis');     
                $rep_id.=$dt;
                $rep_id = uniqid($rep_id); //_id uniquely created 
                $event_lead=$data->event_lead;       
                $leademployeeid=$data->event_contact;
                $logtype=$data->event_activity;
                $start_time=$data->start_time;       
                	$stime=date('d-m-Y h:i A',strtotime('$start_time'));
                $call_type=$data->event_rating;
                $cmp_duration=$data->cmp_duration;
                $camp_note=$data->camp_note;

                $data = array(
                    'rep_id' => $rep_id,
                    'leadid' => $event_lead,           
                    'leademployeeid' => $leademployeeid,
                    'logtype' => $logtype,            
                    'starttime'=>$stime,            
                    'call_type'=>$call_type,
                    'time'=>$cmp_duration,
                    'note'=>$camp_note,
                );
                $insert = $this->calendar->insert_taskcomplete($data);
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
        }else{
            redirect('indexController');
        }
    }  

    public function checkduration(){

        if($this->session->userdata('uid')){
            try{

                $json = file_get_contents("php://input");
                $data = json_decode($json);  
                $rep_id = $this->session->userdata("uid"); 
                $event_start = $data->date;
                //contains the estimated duration of the activity
                $duration=  $data->duration;

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
                $ros=$this->calendar->compare_datetime($event_start, $event_end, $rep_id);
              //  echo json_encode($ros);
                    if($ros>0) {
                       $count=1; 
                       echo json_encode($count);  
                    }else{ 
                            $count=0; 
                            echo json_encode($count);
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
         }else{
            redirect('indexController');
        }
    }


        public function get_taskcal() {
        if($this->session->userdata('uid')){
            try {
                    $user =$this->session->userdata('uid');
                    $json = file_get_contents("php://input"); 
                    $data1=json_decode($json);  
                    $id=$data1->id; 
                    $data = $this->calendar->mytask_cal($id);                   
                    $input_arrays=array_merge($data,$this->calendar->mytask_cal_oppo($id));
                    echo json_encode($input_arrays);  
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
} 

?>

