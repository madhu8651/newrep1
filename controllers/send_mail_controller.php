<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';
include 'lconnectt_controller.php';

class send_mail_controller extends CI_Controller{
	public function __construct(){
		parent::__construct();
		//$this->load->library('lconnecttcommunication');
	}
	public function send_mail(){
		$config = Array(
		    'protocol' => 'smtp',
		    'smtp_host' => 'ssl://sg3plcpnl0032.prod.sin3.secureserver.net',
		    'smtp_port' => 465,
		    'smtp_user' => 'lconnect@likhitech.in',
		    'smtp_pass' => 'Lc0nnect123',
		    'mailtype'  => 'html', 
		    'charset'   => 'iso-8859-1'
		);
		$this->load->library('email',$config);

		$headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $txt = "<h3>Hi $val->user_name,</h3>" . "\r\n";
        $txt .= "<h3 style='font-weight:normal;'>Welcome to L-Connectt</h3>" . "\r\n";
		$txt .= "Test Mail" . "\r\n";
        $txt .= "<h4><i>Thank You, <BR> - Team L-Connectt </i></h4><BR>" . "\r\n";
		
		$subject = "L-Connectt Admin";
		//$send_mail = $this->lconnecttcommunication->send_email($users,$subject,$txt);
		$this->email->set_header('L-Connectt',$headers);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");
		$this->email->from('lconnect@likhitech.in', 'Likitech Admin');
		$this->email->to('pawan@digiconnectt.com');
		$this->email->subject($subject);
		$this->email->message($txt);
		if($this->email->send()){
			echo "Sent";
		}else{
			echo "Not sent";
		}
	}
	public function print_public(){
		echo date('Y-m-d H:i:s A');
	}
}