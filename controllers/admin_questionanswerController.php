<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_opportunitiesController');

class admin_questionanswerController extends Master_Controller{
    public function __construct(){
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_questionanswerModel','question_ans');
    }
    
    public function index($p1,$p2,$p3,$p4){
        $data['stageid'] = $p1;
        $data['leadid'] = $p2;
        $data['repid'] = $p3;
        $data['oppid'] = $p4;
        $this->load->view('admin_questionanswerView',$data,FALSE);
    }

    public function get_data(){
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $stageid=$data->stageid;
        $qualifier_data = $this->question_ans->view_data($stageid);
        echo json_encode($qualifier_data);
    }
}

?>