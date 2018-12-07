<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_departmentController');

class admin_departmentController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('admin_departmentModel','department');
    }
    public function index(){
        if($this->session->userdata('uid')){
            if(parent::checkClient()){
              $this->load->view('admin_department_view');
            }else{
              $url = $this->session->userdata('login_url');
              redirect($url."loginController/logout");
            }
        }else{
            redirect('indexController');
        } 
    }
    public function get_department(){
        if($this->session->userdata('uid')){
              try{
                    $department = $this->department->view_data();
                    echo json_encode($department);
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
    public function post_data() {
        if($this->session->userdata('uid')){
            try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $dt = date('ymdHis');
                  $departmentName = $data->deprtmt_name;
                  $departmentID=strtoupper(substr($departmentName,0,2));
                  $departmentID.=$dt;
                  $departmentID = uniqid($departmentID);
                  $data = array(
                   'Department_id' => $departmentID,
                   'Department_name' => $departmentName
                  );
                  $insert = $this->department->insert_data($data,$departmentName);
                  $GLOBALS['$logger']->debug("Department ID : ".$departmentID);
                  if($insert==1){
                         $department = $this->department->view_data();
                         echo json_encode($department);
                  }
                  else{
                         $department="false";
                         echo json_encode($department);
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
    public function update_data(){
        if($this->session->userdata('uid')){
            try{

                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $departmentName = $data->deprtmt_name;
                  $departmentID = $data->deprt_id;
                  $data = array(
                    'Department_name' => $departmentName
                  );
                  $update = $this->department->update_data($departmentID,$data,$departmentName);

                  if($update==1){
                    $department = $this->department->view_data();
                    echo json_encode($department);
                  }
                  else{
                    $department="false";
                    echo json_encode($department);
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

