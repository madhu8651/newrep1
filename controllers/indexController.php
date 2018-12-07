<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
//include 'Master_Controller.php';

//include '/../log4php/src/main/php/Logger.php';
include (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');

class indexController extends CI_Controller {

    public $log;

    public function __construct(){
    	$status = parent::__construct();
    	if($status!=1){
    		redirect('inactive_client_controller');
    	}
    }

	public function index(){
		if($this->session->userdata('uid')){
			$uid = $this->session->userdata('uid');
			$cxo = $_SESSION['cxo'];
            $manager = $_SESSION['manager'];
            $sales = $_SESSION['sales'];
			
			if($cxo=='-' && $sales=='-' && $manager!='-'){
                $_SESSION['active_module'] = $manager;
                $_SESSION['active_module_name'] = "manager";
                redirect('manager_dashboardsettingController');
            }else if($cxo=='-' && $sales!='-' && $manager=='-'){
                $_SESSION['active_module'] = $sales;
                $_SESSION['active_module_name'] = "executive";
                redirect('sales_mytaskController');
            }else if($cxo=='-' && $sales=='-' && $manager=='-'){
                $_SESSION['active_module'] = $modules[0]->user_id;
                $_SESSION['active_module_name'] = "admin";
                redirect('admin_dashboardController');   
            }else if($cxo!='-' && $sales=='-' && $manager=='-'){
                redirect('indexController/multiple_login');
            }else if($cxo!='-' && $sales!='-' && $manager=='-'){
                redirect('indexController/multiple_login');
            }else if($cxo!='-' && $sales=='-' && $manager!='-'){
                redirect('indexController/multiple_login');
            }else if($cxo!='-' && $sales!='-' && $manager!='-'){
                redirect('indexController/multiple_login');
            }else if($cxo=='-' && $sales!='-' && $manager!='-'){
                redirect('indexController/multiple_login');
            }
		}else{
			$GLOBALS['$log'] = Logger::getLogger('IndexController');            
			$GLOBALS['$log']->debug("Starting Login Page..");
			$this->load->view('index');
		}
	}

	public function multiple_login(){
		if($this->session->userdata('uid')){
			$modules = json_decode($this->session->userdata('modules'));
			$data['manager'] = $modules->Manager;
			$data['sales'] = $modules->sales;
			$this->load->view('multiple_login_view',$data);
		}else{
			redirect('indexController');
		}
	}

	public function get_client_name(){
		$json = file_get_contents("php://input");
		$data = json_decode($json);
		$clientid = $data->clientid;
		$result = $this->db->query("SELECT client_name FROM client_info WHERE client_id='$clientid'");
		echo json_encode($result->result());
	}

	public function set_module(){
		$module = $this->input->post('module');
		if($module=="manager"){
			$_SESSION['active_module'] = $this->input->post('manager_id');
            $_SESSION['active_module_name'] = "manager";
            echo true;
		}else if($module=="sales"){
			$_SESSION['active_module'] = $this->input->post('sales_id');
            $_SESSION['active_module_name'] = "executive";
            echo true;
		}
	}
       
}