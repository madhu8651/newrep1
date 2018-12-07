<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_usermailController');

class admin_usermailController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        //$this->output->enable_profiler(true);
        $this->load->model('admin_personal_groupmail_settingModel','groupmailsetting');
    }
    public function index(){
        if($this->session->userdata('uid')){
         $this->load->view('admin_personal_groupmail_setting');
        }else{
            redirect('indexController');
        }
    }



}




















?>

