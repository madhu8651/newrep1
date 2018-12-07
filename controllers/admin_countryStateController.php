<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_countryStateController');

class admin_countryStateController extends Master_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('admin_countryStateModel','countryState');
    }
    
    public function index(){
        if($this->session->userdata('uid')){
            $this->load->view('admin_countryStateView');
        }else{
            redirect('indexController');
        }   
    }
    
    public function get_country(){
        if($this->session->userdata('uid')){
           try{
                  $country = $this->countryState->view_data();
                  echo json_encode($country);
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
    public function get_state(){
        if($this->session->userdata('uid')){
             try{
                    $state = $this->countryState->view_state();
                    echo json_encode($state);
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
 
    
    public function post_country(){
        if($this->session->userdata('uid')){
          try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $dt = date('ymdHis');
                    $countryName = $data->country_name;
                    $countryKey = $data->country_count;
                    $countryID=strtoupper(substr($countryName,0,2));
                    $countryID.=$dt;
                    $countryID= uniqid($countryID);
                    $data = array(
                    'lookup_id' => $countryID,
                    'lookup_name' => 'country',
                    'lookup_key' => $countryKey,
                    'lookup_value' => $countryName
                    );
                    $insert = $this->countryState->insert_data($data,$countryName);
                    if($insert==1){
                    $country= $this->countryState->view_data();
                    echo json_encode($country);
                    }
                    else{
                    $country="false";
                    echo json_encode($country);
                    }
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
    
    
    
    public function post_state(){
        if($this->session->userdata('uid')){
        try{
            $json = file_get_contents("php://input");
            $data = json_decode($json);
            $countryid=$data->countyrid;
            $stateobj = $data->stateobj;
            $insert_data = $this->countryState->insert_state_data($stateobj,$countryid);
		    $getdata = $this->countryState->view_state1($insert_data['countryid']);
            $a=array(
               'dupdata'=>$insert_data['dup_state'],
               'getdata'=>$getdata
            );
            echo json_encode($a);
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

    
     /* public function delete_data(){
        if($this->session->userdata('uid')){
           $json = file_get_contents("php://input");
           $data = json_decode($json);
           $stateid=$data->stateid;
           $delete=$this->countryState->delete_state_data($stateid);
           if($delete==true){
           $state = $this->countryState->view_state();
           echo json_encode($state);
           }
           else{
           echo json_encode($state,FALSE);
           } 
        }else{
           redirect('indexController');
        }
   
     }*/public function delete_data(){
        if($this->session->userdata('uid')){
         try{
                   $json = file_get_contents("php://input");
                   $data = json_decode($json);
                   $stateid=$data->stateid;
                   $countyrid=$data->countyrid;
                   $delete=$this->countryState->delete_state_data($stateid);
                   if($delete==true){
                   $state = $this->countryState->view_state1($countyrid);
                   echo json_encode($state);
                   }
                   else{
                   echo json_encode($state,FALSE);
                   }
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
