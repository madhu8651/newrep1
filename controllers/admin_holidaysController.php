<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_holidaysController');

class admin_holidaysController extends Master_Controller{
    
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('admin_holidaysModel','holidays');
        
    }
    
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('admin_holidays_view');
        }else{
            redirect('indexController');
        }
        
    }

    public function get_holidays(){
       if($this->session->userdata('uid')){
          try{
                  $holidays = $this->holidays->view_data();
                  echo json_encode($holidays);
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
    
    public function get_calendar(){
        if($this->session->userdata('uid')){
           try{
                  $holidays = $this->holidays->show_calender();
                  echo json_encode($holidays);
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
    
    public function post_data() {
        if($this->session->userdata('uid')){
           try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);

                  $calenderid=$data->calenderid;
                  $holidayList=$data->holidayList; // array
                  $insert = $this->holidays->insert_data($calenderid,$holidayList);
                  $dupdata=json_encode($insert);
                  $getdata = $this->holidays->get_added_holidaydata($calenderid);
                  $holdata=json_encode($getdata);
                  $a= array(
                    'dupdata'=>$insert,
                    'holdata'=> $getdata
                  ) ;
                  echo   json_encode($a);
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
    
     public function update_data(){
         if($this->session->userdata('uid')){
            try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);

                  $calendarID=$data->calenderid;
                  $holidayID=$data->holidayid;
                  $date = $data->date;
                  $holidayDate=date("Y-m-d",strtotime($date));
                  $holidayname = $data->holidayname;

                  $update = $this->holidays->update_data($calendarID,$holidayDate,$holidayname,$holidayID);
                  echo json_encode($update);
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

}


 
?>

