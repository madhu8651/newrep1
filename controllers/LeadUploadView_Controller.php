<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('LeadUploadView_Controller');

class LeadUploadView_Controller extends Master_Controller{
    public function __construct(){
        parent::__construct();
    }
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('LeadUploadView');
          // $this->load->view('manager-exel-file-upload');
        }else{
            redirect('indexController');
        }
    }

}

?>