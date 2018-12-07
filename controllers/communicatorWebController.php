<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('communicatorWebController');

class communicatorWebController extends CI_Controller{
	public function __construct(){
	    parent::__construct();
        $this->load->helper('url');
        $this->load->model('communicatorWebModel','commModel');
	}
   /* public function index(){
        $this->load->view('fileupload_view');
	}*/
    public function ExceptionHandler($e)
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
     public function validate_data()
     {
            try
            {
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $name=$data->login_name;
                $mobile=$data->login_phn;
                $email=$data->login_email;
                $mobile_status = $this->commModel->validate_data($name,$mobile,$email);
                echo json_encode($mobile_status);
            }
            catch(LConnectApplicationException $e)
            {
                $this->ExceptionHandler($e);
            }
    }
    public function uploadDoc()
    {
            try{
                  $file_data = array(
					'files' 		=>$_FILES,
					'contactdetail_id' =>$this->input->post('contact_id'),
					'login_name' =>$this->input->post('login_name'),
					'login_phn' =>$this->input->post('login_phn'),
					'login_email' =>$this->input->post('login_email')
				  );
                  //print_r($file_data);
                 $file_dataresponse = $this->commModel->Kyc_file_upload($file_data);
                 echo json_encode($file_dataresponse);

                }
            catch(LConnectApplicationException $e){
                    $this->ExceptionHandler($e);
                }

    }



}

?>