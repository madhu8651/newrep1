<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_com_groupmailController');

class sales_com_groupmailController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('sales_com_groupmailModel','groupmail');
    }
    private function exceptionThrower($e) {
		$GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
		$errorArray = array(
		'errorCode' => $e->getErrorCode(),
		'errorMsg' => $e->getErrorMessage()
		);
		$GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
		$GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
		return json_encode($errorArray);
	}
    public function index(){
        if($this->session->userdata('uid')){
            $active_module = $_SESSION['active_module_name'];
            if($active_module=='sales' || $active_module=='executive' ){
                $this->load->view('sales_com_groupmailView');
            }else{
                $this->load->view('manager_com_groupmailView');
            }

        }else{
            redirect('indexController');
        }
 
    }

    public function get_data($tabtype) {

       if($this->session->userdata('uid')){
            try {
    			    $get_data = $this->groupmail->get_data($tabtype);
    			    echo json_encode($get_data);
    		} catch (LConnectApplicationException $e) {
    			echo $this->exceptionThrower($e);
    		}
       } else  {
			redirect('indexController');
	   }
	}

    public function get_matchdata($matchdata) {

       if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    if($matchdata=='unassoc'){
                        $nametype=$data->nametype;
                        $search_value=$data->search_value;
    			        $get_matchdata = $this->groupmail->get_matchdata($nametype,$search_value,$matchdata);
    			        echo json_encode($get_matchdata);
                    }else{
                        $email=$data->email;
                        $hidmsgid=$data->hidmsgid;

                        $get_matchdata = $this->groupmail->get_matchdata($hidmsgid,$email,$matchdata);
    			        echo json_encode($get_matchdata);
                    }


    		} catch (LConnectApplicationException $e) {
    			echo $this->exceptionThrower($e);
    		}
       } else  {
			redirect('indexController');
	   }
	}
    public function remove_unassoc() {

       if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $lead_cust_id=$data->lead_cust_id;
                    $hidmsgid=$data->hidmsgid;
                    $hidemail=$data->hidemail;
                    $type=$data->type;
                    $pagetype=$data->pagetype;

    			    $get_matchdata = $this->groupmail->remove_unassoc($lead_cust_id,$hidmsgid,$hidemail,$type,$pagetype);
    			    echo json_encode($get_matchdata);
    		} catch (LConnectApplicationException $e) {
    			echo $this->exceptionThrower($e);
    		}
       } else  {
			redirect('indexController');
	   }
	}



}
?>

