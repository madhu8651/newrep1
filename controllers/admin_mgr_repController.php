<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_mgr_repController');

class admin_mgr_repController extends Master_Controller{
    public function __construct(){
        parent::__construct();
       $this->load->model('admin_mgr_repModel','mgr_rep');
    }
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('admin_mgr_repView');
        }else{
            redirect('indexController');
        }
    }
    public function get_lead_source(){
        if($this->session->userdata('uid')){
            try{
                    $source=$this->mgr_rep->get_lead_source();
                    echo json_encode($source);
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