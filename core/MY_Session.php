<?php

class MY_Session extends CI_Session {

	public function __construct() {
		parent::__construct();
	}

	function sess_destroy() {

		$userid = $this->session->userdata('uid');
		//write your update here 
		$this->CI->db->query("UPDATE user_details SET login_state=0 WHERE user_id='$userid'");

		//call the parent 
		parent::sess_destroy();
	}

}