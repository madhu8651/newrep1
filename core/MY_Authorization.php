<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Authorization extends CI_Controller{
	public function __construct(){  
		parent::__construct();
	}

	public function checkClient($clientid){
		$instanceid = basename(dirname($_SERVER['SCRIPT_FILENAME']));
		if($clientid == $instanceid){
			return true;
		}else{
			return false;
		}
	}
}