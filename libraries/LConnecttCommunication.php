<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define( 'GOOGLE_API_KEY', 'AIzaSyCHZMDUkh-7kRoACBuiD0WrvfncMO67UPI' );
require(ROOT_PATH.'/libraries/PHPMailer-master/src/PHPMailer.php');
require(ROOT_PATH.'/libraries/PHPMailer-master/src/SMTP.php');
require(ROOT_PATH.'/libraries/PHPMailer-master/src/Exception.php');
//include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
//$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class LConnecttCommunication{
	public function __construct(){
		$this->CI =& get_instance();		
	}	

	public function FileSizeConvert($bytes,$user_id,$page_name){
    	$date=date('Y-m-d H:i:s');
    	$query = $GLOBALS['$dbFramework']->query("call p_AddNetData('$user_id','$bytes','$date','$page_name')");    	
	}

	function encryptIt( $q ) {
		    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
		    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
		    return( $qEncoded );
		}

	function decryptIt( $q ) {
	    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
	    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
	    return( $qDecoded );
	}

	public function send_push_notifiction($users,$message){
		$url = 'https://android.googleapis.com/gcm/send';
	    $fields = array(
	        'registration_ids' => $registration_ids,
	        'data' => $message,
	    );

	    $headers = array(
	        'Authorization:key=' . GOOGLE_API_KEY,
	        'Content-Type: application/json'
	    );
	    echo json_encode($fields);
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

	    $result = curl_exec($ch);
	    if($result === false)
	        die('Curl failed ' . curl_error());

	    curl_close($ch);
	    return $result;
	}

	public function send_email($users,$subject,$msgbody){
        $res1 = $this->CI->db->query("select a.email_settings_id, a.incoming_host, a.incoming_port, a.port_type,
                                                                 a.incoming_ssl, a.outgoing_host, a.outgoing_port, a.outgoing_ssl,
                                                                  b.name, b.email_id, b.password from email_settings a,user_email_settings b
                                                                  where a.email_settings_id=b.email_settings_id and a.email_settings_type='emailsetting'");
		$result1 = $res1->result();
		foreach($result1 as $val1){
            $outgoing_port=$val1->outgoing_port;
            $login_email=$val1->email_id;
            $login_name=$val1->name;
            $login_password=$val1->password;
            $outgoing_server=$val1->outgoing_host;
            $smtp_host='ssl://'.$outgoing_server;
        }
	    $config = Array(
		    'protocol' => 'smtp',
		    'smtp_host' => $smtp_host,
		    'smtp_port' => $outgoing_port,
		    'smtp_user' => $login_email,
		    'smtp_pass' => $login_password,
		    'mailtype'  => 'html',
		    'charset'   => 'iso-8859-1'
		);


		$mail = new PHPMailer\PHPMailer\PHPMailer();
		$mail->IsSMTP(); 

		$mail->CharSet="UTF-8";
		$mail->Host = "ssl://gator3272.hostgator.com";
		$mail->SMTPDebug = 0; 
		$mail->Port = 465 ; //465 or 587

		$mail->SMTPSecure = 'ssl';  
		$mail->SMTPAuth = true; 
		$mail->IsHTML(true);

		//Authentication
		$mail->Username = $login_email;
		$mail->Password = $login_password;

		//Set Params
		$mail->SetFrom($login_email, 'L Connectt Admin');
		//$mail->AddAddress($client_result['client_primary_email']);
	/*    $this->CI->load->library('email',$config);
    	$this->CI->email->set_newline("\r\n");
		$headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
*/

        $sendarray=array();
		$userids = "'".implode("','",$users)."'";
		$res = $this->CI->db->query("SELECT user_name,user_primary_email FROM user_details WHERE user_id IN ($userids)");
		$result = $res->result();
		foreach($result as $val){
		    $username=$val->user_name;
		    $user_primary_email=$val->user_primary_email;

		    $txt= '<table border="0" cellspacing="0" cellpadding="0" style="max-width:600px" align="center">
			   <tbody>
			      <tr>
			         <td>
			            <table width="100%" border="0" cellspacing="0" cellpadding="0">
			               <tbody>
			                  <tr>
			                     <td bgcolor="#999" align="left"><img width="170" src="'.base_url().'images/new/White Logo.png" alt="logo"/></td>
			                  </tr>
			               </tbody>
			            </table>
			         </td>
			      </tr>
			      <tr height="16"></tr>
			      <tr>
			         <td>
			            <table bgcolor="#B5000A" width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width:332px;max-width:600px;border:1px solid #e0e0e0;border-bottom:0;border-top-left-radius:3px;border-top-right-radius:3px">
			               <tbody>
			                  <tr>
			                     <td height="18px" colspan="3"></td>
			                  </tr>
			                  <tr>
			                     <td width="32px"></td>
			                     <td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:24px;color:#ffffff;line-height:1.25">'.$subject.'</td>
			                     <td width="32px"></td>
			                  </tr>
			                  <tr>
			                     <td height="18px" colspan="3"></td>
			                  </tr>
			               </tbody>
			            </table>
			         </td>
			      </tr>
			      <tr>
			         <td>
			            <table bgcolor="#FAFAFA" width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width:332px;max-width:600px;border:1px solid #f0f0f0;border-bottom:1px solid #c0c0c0;border-top:0;border-bottom-left-radius:3px;border-bottom-right-radius:3px">
			               <tbody>
			                  <tr height="16px">
			                     <td width="32px" rowspan="3"></td>
			                     <td></td>
			                     <td width="32px" rowspan="3"></td>
			                  </tr>
			                  <tr>
			                     <td>
			                        <table style="min-width:300px" border="0" cellspacing="0" cellpadding="0">
			                           <tbody>
			                              <tr>
			                                 <td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:13px;color:#202020;line-height:1.5">Hi '.$val->user_name.',</td>
			                              </tr>
			                              <tr>
			                                 <td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:13px;color:#202020;line-height:1.5">
			                                    '.$msgbody.'
			                                 </td>
			                              </tr>
			                              <tr height="32px"></tr>
			                              <tr>
			                                 <td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:13px;color:#202020;line-height:1.5">Best,<br>The <span class="il">L Connectt</span> Team</td>
			                              </tr>
			                              <tr height="16px"></tr>
			                           </tbody>
			                        </table>
			                     </td>
			                  </tr>
			                  <tr height="32px">
							  </tr>
			               </tbody>
			            </table>
			         </td>
			      </tr>
			   </tbody>
			</table>';

            if($user_primary_email==null){
                $val='sales@Lconnectt.com';
               /* $this->CI->email->set_header('L Connectt',$headers);
    			$this->CI->email->set_mailtype("html");
    			$this->CI->email->set_newline("\r\n");
    			$this->CI->email->from($login_email, $login_name);
    			$this->CI->email->to($val);
    			$this->CI->email->cc($login_email);
    			$this->CI->email->subject($subject);
    			$this->CI->email->message($msgbody);*/
    			$mail->AddAddress($val);
				$mail->Subject = $subject;
				$mail->Body = $txt;

            }else{
               /* $this->CI->email->set_header('L Connectt',$headers);
    			$this->CI->email->set_mailtype("html");
    			$this->CI->email->set_newline("\r\n");
    			$this->CI->email->from($login_email, $login_name);
    			$this->CI->email->to($val->user_primary_email);
    			$this->CI->email->subject($subject);
    			$this->CI->email->message($txt);*/
                $mail->ClearAddresses();
    			$mail->AddAddress($val->user_primary_email);
				$mail->Subject = $subject;
				$mail->Body = $txt;
            }


			if(!($mail->send())){
                  array_push($sendarray,$username);
			}
		}
        if(count($sendarray)>0){
            return 0;
        }else{
            return 1;
        }

	}

	public function send_sms($users){
		$sms_username = urlencode("r95");
		$msg_token = urlencode("6u8yiY");
		$sender_id = urlencode("LCNNCT"); // optional (compulsory in transactional sms)
		$clientname="DigiConnectt";
		$headers = array('Content-Type: application/json');

		$userids = "'".implode("','",$users)."'";
		$res = $this->CI->db->query("SELECT user_name,user_primary_mobile,user_primary_email FROM user_details WHERE user_id IN ($userids)");
		$result = $res->result();
		foreach($result as $val){
			$message = urlencode("Hi $val->user_name,You have been added as an User to the L Connectt Console. Please check your $val->user_primary_email for your login credentials.");

			$smsapi="http://manage.hivemsg.com/api/send_transactional_sms.php?username=$sms_username&msg_token=$msg_token&sender_id=$sender_id&message=$message";
			$smsapi .= "&mobile=$val->user_primary_mobile";

			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, $smsapi );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			$result = curl_exec($ch );
			curl_close( $ch );
		}
		return 1;
	}


}
?>