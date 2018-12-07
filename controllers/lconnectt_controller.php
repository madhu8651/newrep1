<?php
defined('BASEPATH') or die("Can't open directly");

class lconnectt_controller extends CI_Controller{
	public function __construct(){
		$status = parent::__construct();
		if($status!=1){
    		redirect('inactive_client_controller');
    	}
	}
	public function index(){
		$data['state'] = 1;
		$data['clientid'] = basename(dirname($_SERVER['SCRIPT_FILENAME']));
		$this->load->view('lconnectt_view',$data);
	}
	public function close(){
		$data['state'] = 2;
		$data['clientid'] = basename(dirname($_SERVER['SCRIPT_FILENAME']));
		$this->load->view('lconnectt_view',$data);
	}
	
	/* ---Admin Mail Setting page --- */
	public function lconnecttmailsetting(){
		$this->load->view('lconnectt-mail-setting');
	}
}
?>