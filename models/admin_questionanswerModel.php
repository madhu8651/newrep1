<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();
$GLOBALS['$log'] = Logger::getLogger('admin_questionanswerModel');

class admin_questionanswerModel extends CI_Model{
	public function __construct(){
		parent::__construct();
	}

}

?>