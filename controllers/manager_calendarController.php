<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_calendarController');
require 'utils.php';

class manager_calendarController extends Master_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('manager_calendarModel','calendar');
		$this->load->model('manager_mytaskmodel','mytask');
	}
	
	 public function index() { 
	  if($this->session->userdata('uid')){
	  		$GLOBALS['$logger']->info('Loading Calendar View');
			$this->load->view('manager_calendarView'); 
		}else{
			redirect('indexController');
		}    
	}

	public function get_cal_user() { 
	  if($this->session->userdata('uid')){
	  			echo  json_encode($user_id=$this->session->userdata('uid'));
		}else{
			redirect('indexController');
		}    
	}
	 
	public function initCal() {
	 if($this->session->userdata('uid')){
	 		$GLOBALS['$logger']->info('!!!!!Calling initCal');
            $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
			$user_id =$this->session->userdata('uid'); 
			$GLOBALS['$logger']->info('!!!!!Fetching Opentask Data - Lead,Customer and Opportunity');
			$data = $this->calendar->fetch_mytask($user_id);
			$GLOBALS['$logger']->info($data);
			$GLOBALS['$logger']->info('!!!!!Fetching Opentask Data - Internal');
			$data1= $this->calendar->fetch_mytask1($user_id, '');
			$GLOBALS['$logger']->info($data1);
            $input_arrays1=array_merge($data, $data1);
            $GLOBALS['$logger']->info('!!!!!Fetching Closedtask Data');
            $data3=$this->calendar->fetch_completedReplog($user_id);
            $input_arrays=array_merge($input_arrays1,$data3);
            $GLOBALS['$logger']->info('Final Data');
			$input_arrays = json_encode($input_arrays);
			$GLOBALS['$logger']->info($input_arrays);
			$input_arrays = (array) json_decode($input_arrays);
			$GLOBALS['$logger']->info('!!!!!Accumulate an output array of event data arrays');
			// Accumulate an output array of event data arrays.
			$output_arrays = array();
			foreach ($input_arrays as $array) {
			$GLOBALS['$logger']->info('!!!!!Convert the input array into a useful Event object');	
			$event = new Event((array)$array);
			if ($event->properties['status']=='complete') 
			{
			$event->properties['color'] = 'green';
			} 
			else if ($event->properties['status']=='scheduled')
			{ 
				$event->properties['color'] = 'blue';
			}
			else if ($event->properties['status']=='pending') 
			{ 
				$event->properties['color'] = 'red';
			}
			else if ($event->properties['status']=='cancel') 
			{
                    $event->properties['color'] = 'purple';
            } 
            else if ($event->properties['status'] == 'reschedule')
            {
            	$event->properties['color'] = 'orange';
            }
			$GLOBALS['$logger']->info('!!!!!If the event is in-bounds, add it to the output');
			// If the event is in-bounds, add it to the output
			$output_arrays[] = $event->toArray();
			}
			$GLOBALS['$logger']->info('Send JSON to the client');
			// Send JSON to the client.
			$GLOBALS['$logger']->info($output_arrays);
			echo json_encode($output_arrays);

		}else{
			redirect('indexController');
		}	
}
 
 
public function checkduration(){
	 if($this->session->userdata('uid')){
	 		$GLOBALS['$logger']->info('!!!!!Calling checkduration');
            $GLOBALS['$logger']->info('UserID:'.$this->session->userdata('uid'));
			$json = file_get_contents("php://input");
			$data = json_decode($json);  
			$rep_id = $this->session->userdata("uid"); 
			$event_start = $data->date;
			$GLOBALS['$logger']->info('contains the estimated duration of the activity');
			//contains the estimated duration of the activity
			$duration=  $data->duration;
			$GLOBALS['$logger']->info($duration);
			$GLOBALS['$logger']->info('start date and start time split from the obtained event_start');
			//start date and start time split from the obtained event_start
			$event_start_date = date('Y-m-d', strtotime($event_start));
			$event_start_time = date('H:i', strtotime($event_start));
			$GLOBALS['$logger']->info($event_start_date);
			$GLOBALS['$logger']->info($event_start_time);
			$GLOBALS['$logger']->info('get seconds from cmp_duration');
			//get seconds from cmp_duration
			$seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
			$GLOBALS['$logger']->info($seconds);
			$activity_duration = (int)$seconds->getTimestamp();
			//add seconds to date object 1(start) and make it date object 2(end)
			$start = new DateTime($event_start);
			$event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
			$event_end = $event_end->format('Y-m-d H:i:s');
			$GLOBALS['$logger']->info('comparing event start and event end time');   
			$ros=$this->calendar->compare_datetime($event_start, $event_end, $rep_id);
			//  echo json_encode($ros);
			if($ros>0)
			{
			$count=1; 
			$GLOBALS['$logger']->info($count);  
			echo json_encode($count);  
			 
			}else{ 
			$count=0; 
			$GLOBALS['$logger']->info($count); 
			echo json_encode($count);
			}
		}
		else{
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

