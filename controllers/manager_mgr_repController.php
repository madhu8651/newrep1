<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_contactListController');

class manager_mgr_repController extends Master_Controller{
    public function __construct(){
        parent::__construct();
       $this->load->model('manager_mgr_repModel','mgr_rep');
    }
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('manager_mgr_repView');
        }else{
            redirect('indexController');
        }
    }
    public function get_lead_source(){
        if($this->session->userdata('uid')){

            $userid=$this->session->userdata('uid'); /* id to be taken from session */
            // $userid='170704111951595b79d73d732';
            $source = $this->mgr_rep->get_lead_source($userid);
            echo json_encode($source);
        }else{
             redirect('indexController');
        }
    }

}

?>