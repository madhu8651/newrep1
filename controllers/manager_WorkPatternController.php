<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_contactListController');

class manager_WorkPatternController extends CI_Controller{
	public function __construct(){
		parent::__construct();
	}
	public function index(){
			$this->load->view('manager_work_pattern_analysis_view');
	}
	
}

?>