<?php

defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$logger'] = Logger::getLogger('myoperator_Controller');

class myoperator_Controller extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('myoperator_Model','myoperator');
	}

	public function exception($lae){
		$GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
		$errorArray = array(
			'errorCode' => $lae->getErrorCode(),
			'errorMsg' => $lae->getErrorMessage()
		);
		$GLOBALS['$logger']->debug('Exception JSON to View - '.json_encode($errorArray));
		$GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
		echo json_encode($errorArray);
	}

	public function check_contact(){
		try{
			$mobile = $this->input->post('mobile');
			$result = $this->myoperator->check_contact($mobile);
			if($result=="0"){
				echo "Number ".$mobile." doesn't match to any Lead/Customer";
			}else{
				echo "Match found ".$result;
			}
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}
}
