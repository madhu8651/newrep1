<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_mastersales_cycleController');

class admin_mastersales_cycleController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_mastersales_cycleModel','mastercycle');
    }
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('admin_mastersales_cycle_view');
        }else{
            redirect('indexController');
        }
    }
    public function get_cycle(){
        if($this->session->userdata('uid')){
            try{
                $location = $this->mastercycle->view_cycledata();
                echo json_encode($location);
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

     public function post_data(){
         if($this->session->userdata('uid')){
            try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $dt = date('ymdHis');

                  $cyclename = $data->cyclename;
                  $cycleID = uniqid($dt);
                  $cycledata = array(
                      'master_cycleid' => $cycleID,
                      'master_cyclename' =>$cyclename,
                  );
                  $insert = $this->mastercycle->insert_data($cycledata,$cyclename,$cycleID);
                  if($insert==TRUE){
                      echo 0;
                  }
                  else
                  {
                      echo 1;
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


    public function update_data(){
     if($this->session->userdata('uid')){
            try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);

                  $cyclename = $data->cyclename;
                  $cycleid = $data->cycleid;

                  $cycledata = array(
                      'master_cyclename' =>$cyclename
                  );
                  $update = $this->mastercycle->update_data($cycledata,$cyclename,$cycleid);
                  if($update==TRUE){
                       echo 0;
                  }
                  else
                  {
                      echo 1;
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
}
?>