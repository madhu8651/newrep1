<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_calendarController');

class admin_calendarController extends Master_Controller{
    
    
     public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('admin_calendarModel','calendar');
    }
    
     public function index(){
         if($this->session->userdata('uid')){
            $this->load->view('admin_calendar_view');
        }else{
            redirect('indexController');
        }
    }
    
    public function get_data(){
        if($this->session->userdata('uid')){
           try{
                    $calendar = $this->calendar->view_data();
                    echo json_encode($calendar);
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
     public function post_data() {
         if($this->session->userdata('uid')){
               try{
                      $json = file_get_contents("php://input");
                      $data = json_decode($json);

                      $dt = date('ymdHis');
                      $calendarName =$data->calendername;
                      $calendarId=strtoupper(substr($calendarName,0,2));
                      $calendarId.=$dt;
                      $data = array(
                      'calenderid' => $calendarId,
                      'calendername' => ucfirst(strtolower($calendarName))
                      );
                      $insert= $this->calendar->insert_data($data,$calendarName);
                      if($insert==1){
                      $calendar= $this->calendar->view_data();
                      echo json_encode($calendar);
                      }
                      else{
                      $calendar="false";
                      echo json_encode($calendar);
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
        }else{
            redirect('indexController');
        }
 
     }
     
     public function update_data(){
        if($this->session->userdata('uid')){
        try{
                   $json = file_get_contents("php://input");
                   $data = json_decode($json);

                   $cId = $data->calenderid;
                   $calendarName =$data->calendername;
                   $calenderid=$cId;

                   $update = $this->calendar->update_calendar($calenderid,$calendarName);

                   if($update==1){
                   $calendar = $this->calendar->view_data();
                   echo json_encode($calendar);
                   }
                   else{
                   $calendar="false";
                   echo json_encode($calendar);

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
 
}





?>
