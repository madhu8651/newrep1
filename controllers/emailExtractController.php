<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$logger'] = Logger::getLogger('emailExtractController');

class emailExtractController extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('emailExtractModel','emailModel');
		$this->load->model('admin_sidenavModel','editprofile');
		$this->load->model('sales_com_personalmailModel','personalmail');
		$this->load->library('lconnecttcommunication');
	}
	public function exception($lae){
		$GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
		$errorArray = array(
				'errorCode' => $lae->getErrorCode(),
				'errorMsg' => $lae->getErrorMessage()
		);
		$GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
		$GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
		echo json_encode($errorArray);
	}
	
	public function mail_connection($uid){ 
		$result =$this->emailModel->get_group_mail_connection($uid); 
		$mails = array();
		foreach ($result as $value) {
			$ihostname = '{'.$value->incoming_host.':'.$value->incoming_port.'/imap/ssl}INBOX'; 
			$username = $value->email_id; 
			$password = $value->password;
			
			$inbox = imap_open ($ihostname, $username , $password) or die("ERROR: " . imap_last_error());
			array_push($mails, $inbox);
		}
		return $mails;
	}

	public function mail_personal_connection($uid,$folder_type){ 
		$result =$this->emailModel->get_personal_mail_connection($uid); 
		if($folder_type==''){
			$folder_type='INBOX';
		}else{
			$folder_type='INBOX.Sent';
		}
		//var_dump($result);
		//$mails = array();
		//foreach ($result as $value) {
			$ihostname = '{'.$result[0]->incoming_host.':'.$result[0]->incoming_port.'/imap/ssl}'.$folder_type; 
			$username = $result[0]->email_id; 
			$password = $this->lconnecttcommunication->decryptIt($result[0]->password);
			//$password = $result[0]->password;			
			$inbox = imap_open ($ihostname, $username , $password) or die("ERROR: " . imap_last_error());
			//array_push($mails, $inbox);
		//}
		return $inbox;
	}

	public function retrieve_message($mbox, $messageid){
		$GLOBALS['$log']->debug("Retrive message id - ".$messageid);
	   return $this->getBody($messageid,$mbox);
	}

	public function getBody($uid, $imap) {
		$GLOBALS['$log']->debug("getBody UID - ".$uid);
		$body = $this->get_part($imap, $uid, "TEXT/HTML");
	    if ($body == "") {
			 $body = $this->get_part($imap, $uid, "TEXT/PLAIN"); 
	    }
	    return $body;
	}

	public function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false){
	    if (!$structure) {
				$GLOBALS['$log']->debug("get_part UID - ".$uid);
	           $structure = imap_fetchstructure($imap, $uid,FT_UID);
	    }
	    if ($structure) {
			if ($mimetype == $this->get_mime_type($structure)) {
	            if (!$partNumber) {
	                $partNumber = 1;
	            }
	            $text = imap_fetchbody($imap, $uid, $partNumber,FT_UID);
	            switch ($structure->encoding) {
	                case 3: return imap_base64($text);
	                case 4: return imap_qprint($text);
	                default: return $text;
	           }
	       }

	        if ($structure->type == 1) {
	            foreach ($structure->parts as $index => $subStruct) {
	                $prefix = "";
	                if ($partNumber) {
	                    $prefix = $partNumber . ".";
	                }
					$data = $this->get_part($imap, $uid, $mimetype, $subStruct, $prefix.($index + 1));
	                if ($data) {
	                    return $data;
	                }
	            }
	        }
	    }
	    return false;
	}

	public function get_mime_type($structure) {
		$primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION","AUDIO", "IMAGE", "VIDEO", "OTHER");

		if ($structure->subtype) {
		   return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
		}
		return "TEXT/PLAIN";
	} 

	public function retrieve_attachment($mbox, $messageid){
		$GLOBALS['$log']->debug("Retrive attachment message id - ".$messageid);
		$structure = imap_fetchstructure($mbox,$messageid);
		
		$attachments = array();
		if(isset($structure->parts) && count($structure->parts)) {
			for($i = 0; $i < count($structure->parts); $i++) {
				$attachments[$i] = array(
						'is_attachment' => false,
						'filename' => '',
						'name' => '',
						'attachment' => '');
		
				if($structure->parts[$i]->ifdparameters) {
					foreach($structure->parts[$i]->dparameters as $object) {
						if(strtolower($object->attribute) == 'filename') {
							$attachments[$i]['is_attachment'] = true;
							$attachments[$i]['filename'] = $object->value;
						}
					}
				}
		
				if($structure->parts[$i]->ifparameters) {
					foreach($structure->parts[$i]->parameters as $object) {
						if(strtolower($object->attribute) == 'name') {
							$attachments[$i]['is_attachment'] = true;
							$attachments[$i]['name'] = $object->value;
						}
					}
				}
		
				if($attachments[$i]['is_attachment']) {
					$attachments[$i]['attachment'] = imap_fetchbody($mbox, $messageid, $i+1);
					if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
						$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
					}
					elseif($structure->parts[$i]->encoding == 4) { // 4 = UOTQED-PRINTABLE
						$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
					}
				}
			} 
		}
		return $attachments;
	}

	public function get_sentmail(){

		$user_id = $this->session->userdata('uid');
		$result =$this->emailModel->get_personal_mail_connection($user_id); 

		$incoming_host ='imap.gmail.com';
		$incoming_port = 993;
		
			$ihostname = '{'.$incoming_host.':'.$incoming_port.'/imap/ssl}INBOX'; 
			$username = 'testdigiconnectt@gmail.com'; 
			//$password = $this->lconnecttcommunication->decryptIt($result[0]->password);			
			$password = 'digiconnectt123';	
			$inbox = imap_open ($ihostname, $username , $password) or die("ERROR: " . imap_last_error());
		
		  	$date=date ( "d M Y", strToTime ( "-100 days" ) );           
            $emails = imap_search($inbox, 'SINCE "'.$date.'"', SE_UID);
            foreach ($emails as $iemail) {
                $obj = imap_headerinfo($inbox, $iemail);
            }
            //rsort($emails);
           print_r($obj->ccaddress);

           // imap_append($inbox, $result[0]->incoming_host.'INBOX.Sent',$mail->getSentMIMEMessage(), "\\Seen");

			/*foreach($emails as $iemail){
				$msgno = imap_msgno($inbox, $iemail);
				$obj = imap_headerinfo($inbox, $iemail);
				// print_r($obj);echo "<br>";
				echo $obj->toaddress."<br>";
				echo $obj->subject."<br>";
				echo $obj->Date."<br>";
				$mbody = $this->retrieve_message($inbox,$iemail);

				echo $mbody."<br>";
				echo "<br>";

			}	*/
	}

	public function validateEmailsAPI(){
		
		$resultData = array();

		$dt = date('ymdHis');

		$res = $this->editprofile->get_email_settings($_POST['incoming_host']);

		if($res->num_rows()>0){
			$result = $res->result();
			$login_email=$_POST['login_email'];
			$login_name=$_POST['login_name'];
			$login_password=$_POST['login_password'];
			$outgoing_port=$result[0]->outgoing_port;
			$outgoing_server=$result[0]->outgoing_host;

			$smtp_host='ssl://'.$outgoing_server;

			$config = Array(
			    'protocol' => 'smtp',
			    'smtp_host' => $smtp_host,
			    'smtp_port' => $outgoing_port,
			    'smtp_user' => $login_email,
			    'smtp_pass' => $login_password,
			    'mailtype'  => 'html',
			    'charset'   => 'iso-8859-1'
			);
			$this->load->library('email',$config);

			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$txt = "<h3>Hi $login_name,</h3>" . "\r\n";
			$txt .= "<h3 style='font-weight:normal;'>Welcome to L-Connectt</h3>" . "\r\n";
			$txt .= "Test Mail" . "\r\n";
			$txt .= "<h4><i>Thank You, <BR> - Team L-Connectt </i></h4><BR>" . "\r\n";

			$subject = "L-Connectt $login_name";
			$this->email->set_header('L-Connectt',$headers);
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->from($login_email, 'Test Mail');
			$this->email->to($login_email);
			$this->email->subject($subject);
			$this->email->message($txt);
			if($this->email->send()){
					$resultData['success'] = 'true';
					$resultData['data'] = 'Sent';
					echo json_encode($resultData);

			}else{
			  	$resultData['success'] = 'false';
				$resultData['data'] = 'Not sent';
				echo json_encode($resultData);

			}
		}else{
				$resultData['success'] = 'false';
				$resultData['data'] = 'Not Found';
				echo json_encode($resultData);
		}
    }

    public function saveUserEmailsDetailsAPI(){
    	$resultData = array();
		$userid = $_POST['user_id'];//$_POST['user_id']; 
		$json = file_get_contents("php://input");
		$data = json_decode($json);
		$login_password = $this->lconnecttcommunication->encryptIt($_POST['login_password']);
		$login_email=$_POST['login_email'];
		$login_name=$_POST['login_name'];
		$login_password=$login_password;
		$signature = $_POST['signature'];
		
		$dt = date('ymdHis');
		$settings_id = isset($_POST['settings_id']) ? $_POST['settings_id'] : uniqid($dt);

		$res = $this->editprofile->get_email_settings($_POST['incoming_host']);
		//check for user mail id exist in the user_email_settings table, if yes update else insert
		
		if($res->num_rows()>0){
				$result = $res->result();
				$email_data = array(
				'email_settings_id' => $result[0]->email_settings_id,
				'user_id' => $userid,
				'name' => $login_name,
				'email_id' => $login_email,
				'password' => $login_password,
				'settings_key' => 'personalsetting',
				'timestamp' => date('Y-m-d H:i:s'),
				'user_email_settings_id' => $settings_id,
				'signature' => $signature
				);

				//check whether useremail alread exist
				$checkforuser = $this->editprofile->getUserExist($userid);

				if($checkforuser>0){					
					$insert = $this->editprofile->update_user_email_settings($email_data,$settings_id);
				}else{
					$insert = $this->editprofile->save_user_email_settings($email_data);
				}
								
				$resultData['success'] = 'true';
				$resultData['data'] = $insert;
				echo json_encode($resultData);
		}else{
				$resultData['success'] = 'false';
				$resultData['data'] = 'Not Found';
				echo json_encode($resultData);
		}
	} 

	public function getMailsAPI() {
            	$resultData = array();
         
                $get_data = $this->personalmail->get_data($_POST['tabtype']);

                $domain = $this->personalmail->get_domain($_POST['user_id']);  
                $dom = array();
                if(!empty($domain)){
                    foreach ($domain as $domain => $value) {
                       array_push($dom, $value->email_id);
                    }
                }     
                $arr = array('mailData'=>$get_data,'domain'=>$dom);               
				$resultData['success'] = 'true';
				$resultData['data'] = $arr;
				echo json_encode($resultData);               
				
	}

	 public function getMatchdataAPI() {
	 				//echo $_POST['user_id'];exit();
	 				$resultData = array();
                    if($_POST['matchdata']=='unassoc'){                        
                        $search_value=$_POST['searchValue'];
                        $nametype = '';
                        $get_matchdata = $this->personalmail->get_matchdata($nametype,$search_value,$_POST['matchdata']);
                        if(count($get_matchdata)>0){
                        	$resultData['success'] = 'true';
                        	$resultData['data'] = $get_matchdata;
                        	echo json_encode($resultData);	
                        }else{
                        	$resultData['success'] = 'false';
                        	$resultData['data'] = 'No Match Found';
                        	echo json_encode($resultData);
                        }
                        
                    }else{
                        $search_value= $_POST['searchValue'];
                        $msgid='';                       
                        $get_matchdata = $this->personalmail->get_matchdata($msgid,$search_value,$_POST['matchdata']);
                        if(count($get_matchdata)>0){
                        	$resultData['success'] = 'true';
                        	$resultData['data'] = $get_matchdata;
                        	echo json_encode($resultData);	
                        }else{
                        	$resultData['success'] = 'false';
                        	$resultData['data'] = 'No Match Found';
                        	echo json_encode($resultData);
                        }
                    }
    }
    
    public function getUserEmailSettings(){
    	$resultData =  array();
        $result = $this->editprofile->get_user_email_settings($_POST['user_id']);
        if(count($result)>0){
        	$resultData['success'] = 'true';
        	$resultData['data'] = $result;
        	echo json_encode($resultData);	
        }else{
        	$resultData['success'] = 'false';
        	$resultData['data'] = $result;
        	echo json_encode($resultData);
        }
        
    }      

	public function get_mailid(){
		$res = $this->emailModel->check_cust_email('kumarg781991@gmail.com');
		var_dump($res->result());
	}

	// Function to Auto populate , 

	public function userEmailData()
	{
		
		$data = $this->emailModel->userEmail($this->session->userdata('uid'));
		echo json_encode($data);

	}

}