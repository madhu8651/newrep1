<?php
/*testing again for schedule task  today for kumar*/
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';


include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_com_personalmailController');

class sales_com_personalmailController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('sales_com_personalmailModel','personalmail');
        $this->load->model('emailExtractModel','emailModel');
        $this->load->library('lconnecttcommunication');
        $this->load->library('unit_test');
    }
    private function exceptionThrower($e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
        $errorArray = array(
        'errorCode' => $e->getErrorCode(),
        'errorMsg' => $e->getErrorMessage()
        );
        $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
        $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
        return json_encode($errorArray);
    }
    public function index(){
        if($this->session->userdata('uid')){
            $active_module = $_SESSION['active_module_name'];
            if($active_module=='sales' || $active_module=='executive' ){
                $this->load->view('sales_com_personalmailView');
            }else{
                $this->load->view('sales_com_personalmailView');
            }

        }else{
            redirect('indexController');
        }
 
    }

    public function get_data($tabtype) {

       if($this->session->userdata('uid')){
            try {
                $get_data = $this->personalmail->get_data($tabtype);
                $domain = $this->personalmail->get_domain($this->session->userdata('uid'));  
                $dom = array();
                if(!empty($domain)){
                    foreach ($domain as $domain => $value) {
                       array_push($dom, $value->email_id);
                    }
                }     
                $arr = array('data'=>$get_data,'domain'=>$dom);
                echo json_encode($arr);
            } catch (LConnectApplicationException $e) {
                echo $this->exceptionThrower($e);
            }
       } else  {
            redirect('indexController');
       }
    }

    public function get_matchdata($matchdata) {

       if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    if($matchdata=='unassoc'){
                        $nametype=$data->nametype;
                        $search_value=$data->search_value;
                        $get_matchdata = $this->personalmail->get_matchdata($nametype,$search_value,$matchdata);
                        echo json_encode($get_matchdata);
                    }else{
                        $email=$data->email;
                        $hidmsgid=$data->hidmsgid;

                        $get_matchdata = $this->personalmail->get_matchdata($hidmsgid,$email,$matchdata);
                        echo json_encode($get_matchdata);
                    }

            } catch (LConnectApplicationException $e) {
                echo $this->exceptionThrower($e);
            }
       } else  {
            redirect('indexController');
       }
    }
    public function remove_unassoc() {

       if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $lead_cust_id=$data->lead_cust_id;
                    $hidmsgid=$data->hidmsgid;
                    $hidemail=$data->hidemail;
                    $type=$data->type;
                    $pagetype=$data->pagetype;
                    $associated_id =$data->associated_id; 
                    $get_matchdata = $this->personalmail->remove_unassoc($lead_cust_id,$hidmsgid,$hidemail,$type,$pagetype,$associated_id);
                    echo json_encode($get_matchdata);
            } catch (LConnectApplicationException $e) {
                echo $this->exceptionThrower($e);
            }
       } else  {
            redirect('indexController');
       }
    }
    public function getAllMail() {

       if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $get_matchdata = $this->personalmail->fetchAllMail();
                    $match_email_details = array();
                    $matched_arr = array();
                    $MailArray = array();
                    //var_dump($get_matchdata);exit();
                    foreach ($get_matchdata as $key => $value) {
                        $get_matchdata1 = $this->personalmail->get_matchdata('name',$value->Mail_ID,'conflict');
                        $match_email_details['matchdata'] = $get_matchdata1;  
                        $match_email_details['Mail_ID'] = $value->Mail_ID;  
                        $match_email_details['Name'] = $value->Name;
                        array_push($matched_arr, $match_email_details);                    
                    }
                    $InternalMails = $this->personalmail->get_domain1($this->session->userdata('uid'));
                    $MailArray = array('allmail'=>$matched_arr,'domain'=>$InternalMails);
                    echo json_encode($MailArray);
            } catch (LConnectApplicationException $e) {
                echo $this->exceptionThrower($e);
            }
       } else  {
            redirect('indexController');
       }
    }

    public function submitFormdata(){
        if($this->session->userdata('uid')){
            try { 
                    $dt = date('ymdHis');
                    $msgid = uniqid($dt);
                    $dmy = date("d-M-Y H:i:s"); 
                    $leadinprogress = array();
                    $user_id = $this->session->userdata('uid'); 
                    $user_name = $this->session->userdata('uname');                    
                    $activeModule = $this->session->userdata('active_module_name');  
                    
                    // Mail Ids of entered in To
                    $mail_to_common2 = json_decode($_POST['mail_to_common1']);

                    $mailIDs =array();                      
                    foreach ($mail_to_common2 as $value) {     
                        array_push($mailIDs,$value->display);
                    }    

                    $mail_to_common = implode(',',$mailIDs);

                    $GLOBALS['$logger']->info('To emails ids');
                    $GLOBALS['$logger']->info($mail_to_common);                   
                  
                    $mailIdCount = count($mailIDs);

                    //Mail Ids of entered in CC
                    $mail_cc_common2 = json_decode($_POST['mail_cc_common']);
                    
                    $ccMailIds =array();                      
                    foreach ($mail_cc_common2 as $value) {     
                        array_push($ccMailIds,$value->display);
                    }

                    $mail_cc_common = implode(',',$ccMailIds);

                    $GLOBALS['$logger']->info('CC emails ids');
                    $GLOBALS['$logger']->info($mail_cc_common);  

                    //Mail Ids of entered in BCC
                    $mail_bcc_common2 = json_decode($_POST['mail_bcc_common']);
                    
                    $bccMailIds =array();                      
                    foreach ($mail_bcc_common2 as $value) {     
                        array_push($bccMailIds,$value->display);
                    }

                    $mail_bcc_common = implode(',',$bccMailIds);


                    $GLOBALS['$logger']->info('BCC emails ids');
                    $GLOBALS['$logger']->info($mail_bcc_common);  
                    
                    $matched_data = array(); 

                    // sending user_id to get the details of user email settings 
                    $data=$this->personalmail->get_out_server($user_id);
                    $result =$this->emailModel->get_personal_mail_connection($user_id);               
                    $username = $result[0]->email_id; 
                    $password = $this->lconnecttcommunication->decryptIt($result[0]->password);
                    //$password = $result[0]->password;
                    $ihostname = '{'.$result[0]->incoming_host.':'.$result[0]->incoming_port.'/imap/ssl}INBOX.Sent'; 
                    $inbox = imap_open ($ihostname, $username , $password) or die("ERROR: " . imap_last_error());              

                    /* SMTP settings */
                    $config = Array(
                    'protocol' => 'smtp',
                    'smtp_host' => 'ssl://'.$data[0]->outgoing_host,
                    'smtp_port' => $data[0]->outgoing_port,
                    'smtp_user' => $data[0]->email_id,
                    //'smtp_pass' => $this->lconnecttcommunication->decryptIt($data[0]->password),
                    'smtp_pass' => $password,
                    'mailtype'  => 'html',
                    'charset'   => 'iso-8859-1'
                    );                  

                    $this->load->library('email',$config);
                    $this->email->set_newline("\r\n");
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= "Content-type:image/jpeg;charset=UTF-8;" . "\r\n";                 

                    //body of the message
                    $txt = $_POST['editor12_common'] . "\r\n";                    
    
                    $this->email->set_header('LConnectt',$headers);
                    $this->email->set_mailtype("html");
                    $this->email->set_newline("\r\n");
                    $this->email->from($data[0]->email_id, $user_name);
                    $this->email->to($mail_to_common);
                    $this->email->cc($mail_cc_common);                                
                    $this->email->bcc($mail_bcc_common);
                    $this->email->subject($_POST['mail_sub_common']);
                    $this->email->message($txt);                     
                    $date=date("Y-m-d H:i:s");

                    $data2=array(); 

                    //checking whether attachment exist or not
                    if($_FILES['mail_attach_common']['error'][0]==0){                   
                        
                        $msg = '';

                        //if attachment exist it will enter here
                        if (array_key_exists('mail_attach_common', $_FILES)) {

                                foreach ($_FILES["mail_attach_common"]["error"] as $key => $error){

                                    if ($error == UPLOAD_ERR_OK) {

                                        $tmp_name = $_FILES["mail_attach_common"]["tmp_name"][$key];                           
                                        $name = $_FILES["mail_attach_common"]["name"][$key];
                                        $name = str_replace(' ', '_', $name);
                                        //$attachment = chunk_split(base64_encode($user_id.$name)); 

                                        /* move file from temporary directory to uploads */
                                        $directory = "./uploads/mail_attachments";
                                        if (!is_dir($directory)) {
                                            mkdir($directory);
                                        }

                                        $directory ="./uploads/mail_attachments/".$user_id;  
                                        if (!is_dir($directory)) {
                                            mkdir($directory);
                                        }    
                                        move_uploaded_file($tmp_name,"uploads/mail_attachments/".$user_id."/".$name);
                                        $this->email->attach("uploads/mail_attachments/".$user_id."/".$name);
                                        $data1=array('message_id'=>$msgid,
                                        'mail_attachment_filename'=>$name,
                                        'mail_attachment_path'=>"mail_attachments/".$user_id."/".$name);
                                        array_push($data2,$data1);
                                        $check = imap_check($inbox);

                                        //print_r($this->email->getSentMIMEMessage());
                                        $filename=$name; 
                                        $attachment = chunk_split(base64_encode($filename)); 

                                        $boundary = "------=".md5(uniqid(rand())); 

                                        //appending sent mails into the users sent mail folder
                                        $msg = ("From: ".$data[0]->email_id."\r\n".
                                        "To: ".$mail_to_common."\r\n".
                                        "cc: ".$mail_cc_common."\r\n".
                                        "bcc: ".$mail_bcc_common."\r\n".
                                        "Subject: ".$_POST['mail_sub_common']."\r\n"
                                        . "Date: ".$dmy."\r\n"       
                                        . "MIME-Version: 1.0\r\n" 
                                        . "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n" 
                                        . "\r\n\r\n" 
                                        . "--$boundary\r\n" 
                                        . "Content-Type: text/html;\r\n\tcharset=\"ISO-8859-1\"\r\n" 
                                        . "Content-Transfer-Encoding: 8bit \r\n" 
                                        . "\r\n\r\n" 
                                        . $_POST['editor12_common']."\r\n" 
                                        . "\r\n\r\n" 
                                        . "--$boundary\r\n" 
                                        ."Content-Type: image/jpeg;\r\n"                            
                                        ."name=\"$filename\"\r\n"       
                                        . "Content-Transfer-Encoding: base64\r\n"
                                        . "Content-Disposition: attachment; filename=\"$filename\"\r\n" 
                                        . "\r\n" . $attachment . "\r\n" 
                                        . "\r\n\r\n\r\n"

                                        . "--$boundary--\r\n\r\n"); 

                                    }
                                        //imap_append function is used to append the email to the email server
                                        imap_append($inbox, $ihostname,$msg, "UNSEEN"); 
                                        $GLOBALS['$logger']->info('emails wrote by the user');
                                        $GLOBALS['$logger']->info($msg);
                                        //   imap_append($inbox, $ihostname,$sent);
                                        $check = imap_check($inbox);
                                        // echo "Msg Count after append : ". $check->Nmsgs . "\n";
                                }
                        }else{
                                $msg = 'Failed to move file to ' . $uploadfile;
                        }
                            $logArr = array();
                                if ($this->email->send()) {                                            
                                            $logArr = array();                                            
                                            $lead_cust_opp_id = '';
                                            $contact_id = '';                                            
                                            $activityCount = 0;
                                            $starttime = new DateTime($_POST['startComposeTime']);            
                                            $endtime = new DateTime($_POST['endComposeTime']);
                                            //$compres = $this->insert_reading_time_compose($_POST['startComposeTime'],$_POST['endComposeTime'],$msgid);
                                            //logging task only for the first matched lead/customer/opportunity
                                            foreach ($mail_to_common2 as $value) {     
                                                if(!empty($value->actualVal) && $activityCount == 0){
                                                    //array_push($maiIDs1,$value->actualVal);
                                                    $matched_data = explode('-',$value->actualVal);
                                                    //array_push($maiIDs,$arr);
                                                    $lead_cust_opp_id = $matched_data[0];
                                                    $contact_id = $matched_data[1];
                                                    $type = $matched_data[2];
                                                    $log_data['rep_id'] = $user_id;
                                                    $log_data['leademployeeid'] = $matched_data[1];
                                                    $log_data['leadid'] = $matched_data[0];
                                                    $log_data['logtype'] = 'EM594ce66d07b9f87';
                                                    $log_data['call_type'] = 'complete';
                                                    $log_data['path'] = null;
                                                    $log_data['time'] = date('Y-m-d H:i:s');
                                                    $log_data['starttime'] = $starttime->format('Y-m-d H:i:s');
                                                    $log_data['endtime'] = $endtime->format('Y-m-d H:i:s');
                                                    $log_data['type'] = $matched_data[2];
                                                    $log_data['module_id'] = $activeModule;
                                                    $log_data['log_name'] = 'Outgoing email to '.$mail_to_common.','.$mail_cc_common;
                                                    $log_data['note'] = $_POST['remark_common'];
                                                    $log_data['rating'] = $_POST['rating_common'];
                                                    $log_data['message_id'] = $msgid;
                                                    $log_data['log_method'] = 'manual';
                                                    $activityCount++; 
                                                    array_push($logArr, $log_data);                       
                                                }  
                                               if(isset($matched_data[2])=='lead'){
                                                    array_push($leadinprogress,$matched_data[0]);
                                                }              
                                                
                                            }   

                                                if($activityCount == 0){
                                                    $type = 'unassociated';
                                                    $log_data['rep_id'] = $user_id;
                                                    $log_data['leademployeeid'] = null;
                                                    $log_data['leadid'] = $msgid;
                                                    $log_data['logtype'] = 'EM594ce66d07b9f87';
                                                    $log_data['call_type'] = 'complete';
                                                    $log_data['path'] = null;
                                                    $log_data['time'] = date('Y-m-d H:i:s');
                                                    $log_data['starttime'] = $starttime->format('Y-m-d H:i:s');
                                                    $log_data['endtime'] = $endtime->format('Y-m-d H:i:s');
                                                    $log_data['type'] = $type;
                                                    $log_data['module_id'] = $activeModule;
                                                    $log_data['log_name'] = 'Outgoing email to '.$mail_to_common.','.$mail_cc_common;
                                                    $log_data['note'] = $_POST['remark_common'];
                                                    $log_data['rating'] = $_POST['rating_common'];
                                                    $log_data['message_id'] = $msgid; 
                                                    $log_data['log_method'] = 'manual';  
                                                    array_push($logArr, $log_data);     
                                                }

                                            $GLOBALS['$logger']->info($logArr);         

                                            //inserting activity for lead/customer/opportunity/unassociated
                                            $res = $this->insert_activity_for_match_data($logArr);

                                            //inset data into support_group_mail table
                                            if($mailIdCount>1 && $activityCount != 0){
                                                $res1 = $this->insert_sentmail($msgid, 'Multiple Association', $data[0]->email_id, $mail_to_common, $_POST['mail_sub_common'],$_POST['editor12_common'],$user_id, $mail_cc_common, $mail_bcc_common,null,null);
                                            }else{
                                                $res1 = $this->insert_sentmail($msgid, $type, $data[0]->email_id, $mail_to_common, $_POST['mail_sub_common'],$_POST['editor12_common'],$user_id, $mail_cc_common, $mail_bcc_common,$lead_cust_opp_id,$contact_id);
                                            }
                                            
                                            //insert attachments into 
                                            $this->personalmail->insert_sent_attachments($data2);
                                            $this->personalmail->update_leadinprogess($leadinprogress);
                                        
                                        echo json_encode(1);

                                        exit;
                                }else{
                                        echo json_encode(0);
                                }

                    }else{
                            if($this->email->send()){
                                        /* imap_append($inbox, $ihostname, $data[0]->email_id
                                        $_POST['mail_to'] , "Seen");*/            
                                       $boundary = "------=".md5(uniqid(rand())); 
                                       //$compres = $this->insert_reading_time_compose($_POST['startComposeTime'],$_POST['endComposeTime'],$msgid);
                                        $starttime = new DateTime($_POST['startComposeTime']);            
                                        $endtime = new DateTime($_POST['endComposeTime']);
                                        $check = imap_check($inbox);
                                        $sent=("From: ".$data[0]->email_id."\r\n".
                                        "To: ".$mail_to_common."\r\n".
                                        "cc: ".$mail_cc_common."\r\n".
                                        "bcc: ".$mail_bcc_common."\r\n".
                                        "Subject: ".$_POST['mail_sub_common']."\r\n".
                                        "Date: ".date("r", strtotime("now"))."\r\n".
                                        "MIME-Version: 1.0\r\n" 
                                        . "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n" 
                                        . "\r\n\r\n" 
                                        . "--$boundary\r\n" 
                                        . "Content-Type: text/html;\r\n\tcharset=\"ISO-8859-1\"\r\n" 
                                        . "Content-Transfer-Encoding: 8bit \r\n" 
                                        . "\r\n\r\n" 
                                        . $_POST['editor12_common']."\r\n" 
                                        . "\r\n\r\n" 
                                        . "--$boundary\r\n"                             
                                        );

                                        imap_append($inbox, $ihostname,$sent);
                                        $GLOBALS['$logger']->info('emails wrote by the user');
                                        $GLOBALS['$logger']->info($sent); 
                                            $logArr = array();                                            
                                            $lead_cust_opp_id = '';
                                            $contact_id = '';                                            
                                            $activityCount = 0;
                                            
                                            foreach ($mail_to_common2 as $value) {     
                                                if(!empty($value->actualVal) && $activityCount == 0){
                                                    //array_push($maiIDs1,$value->actualVal);
                                                    $matched_data = explode('-',$value->actualVal);
                                                    //array_push($maiIDs,$arr);
                                                    $lead_cust_opp_id = $matched_data[0];
                                                    $contact_id = $matched_data[1];
                                                    $type = $matched_data[2];
                                                    $log_data['rep_id'] = $user_id;
                                                    $log_data['leademployeeid'] = $matched_data[1];
                                                    $log_data['leadid'] = $matched_data[0];
                                                    $log_data['logtype'] = 'EM594ce66d07b9f87';
                                                    $log_data['call_type'] = 'complete';
                                                    $log_data['path'] = null;
                                                    $log_data['time'] = date('Y-m-d H:i:s');
                                                    $log_data['starttime'] = $starttime->format('Y-m-d H:i:s');
                                                    $log_data['endtime'] = $endtime->format('Y-m-d H:i:s');
                                                    $log_data['type'] = $matched_data[2];
                                                    $log_data['module_id'] = $activeModule;
                                                    $log_data['log_name'] = 'Outgoing email to '.$mail_to_common.','.$mail_cc_common;
                                                    $log_data['note'] = $_POST['remark_common'];
                                                    $log_data['rating'] = $_POST['rating_common'];
                                                    $log_data['message_id'] = $msgid;
                                                    $log_data['log_method'] = 'manual';
                                                    $activityCount++; 
                                                    array_push($logArr, $log_data);                       
                                                }   

                                                if(isset($matched_data[2])=='lead'){
                                                    array_push($leadinprogress,$matched_data[0]);
                                                }                                               
                                                
                                            }  

                                                if($activityCount == 0){
                                                    $type = 'unassociated';
                                                    $log_data['rep_id'] = $user_id;
                                                    $log_data['leademployeeid'] = null;
                                                    $log_data['leadid'] = $msgid;
                                                    $log_data['logtype'] = 'EM594ce66d07b9f87';
                                                    $log_data['call_type'] = 'complete';
                                                    $log_data['path'] = null;
                                                    $log_data['time'] = date('Y-m-d H:i:s');
                                                    $log_data['starttime'] = $starttime->format('Y-m-d H:i:s');
                                                    $log_data['endtime'] = $endtime->format('Y-m-d H:i:s');
                                                    $log_data['type'] = $type;
                                                    $log_data['module_id'] = $activeModule;
                                                    $log_data['log_name'] = 'Outgoing email to '.$mail_to_common.','.$mail_cc_common;
                                                    $log_data['note'] = $_POST['remark_common'];
                                                    $log_data['rating'] = $_POST['rating_common'];
                                                    $log_data['message_id'] = $msgid;   
                                                    $log_data['log_method'] = 'manual';
                                                    array_push($logArr, $log_data);     
                                                }
                                                
                                            $GLOBALS['$logger']->info($logArr); 
                                           
                                            $res = $this->insert_activity_for_match_data($logArr);
                                            $GLOBALS['$logger']->info($res);
                                            if($mailIdCount>1 && $activityCount != 0){
                                                $res1 = $this->insert_sentmail($msgid, 'Multiple Association', $data[0]->email_id,  $mail_to_common, $_POST['mail_sub_common'],$_POST['editor12_common'],$user_id, $mail_cc_common, $mail_bcc_common,null,null);               
                                            }else{
                                                $res1 = $this->insert_sentmail($msgid, $type, $data[0]->email_id,  $mail_to_common, $_POST['mail_sub_common'],$_POST['editor12_common'],$user_id, $mail_cc_common, $mail_bcc_common,$lead_cust_opp_id,$contact_id);
                                            }
                                        $this->personalmail->update_leadinprogess($leadinprogress);
                                       
                                        $check = imap_check($inbox);
                                        //  echo "Msg Count after append : ". $check->Nmsgs . "\n";
                                        echo json_encode(1);
                                        //example
                                }else{
                                        echo json_encode(0);
                                }
                    }     
            } catch (LConnectApplicationException $e) {
                echo $this->exceptionThrower($e);
            }

        }else  {
            redirect('indexController');
       }
    }   
  
   
    public function insert_sentmail($msgid, $type, $email_id, $mail_to_common, $mail_sub_common,$editor12_common,$user_id, $mail_cc_common, $mail_bcc_common,$lead_cust_opp_id,$contact_id){

            $arr=array('message_id'=>$msgid,
            'type'=>$type,
            'mail_from'=>$email_id,
            'mail_to'=>$mail_to_common,
            'mail_date'=>date("Y-m-d H:i:s"),
            'mail_subject'=>$mail_sub_common,
            'mail_body'=>$editor12_common,
            'user_type'=>'personalmail',
            'user_id'=> $user_id,
            'mail_associated_state'=>9,
            'mail_cc'=>$mail_cc_common,
            'mail_bcc'=>$mail_bcc_common,
            'contact_id'=>$contact_id,
            'lead_cust_opp_id'=>$lead_cust_opp_id,
            'mail_to_address'=>$mail_to_common
            );
            $GLOBALS['$logger']->info('insert sent mail into support_group_mail table');
            $GLOBALS['$logger']->info($arr);
            $res = $this->personalmail->insert_sent_mail($arr);
            return $res;

    }

    public function insert_activity_for_match_data(/*$lead_cust_opp_id,$contact_id,$type,$user_id,$activeModule,$mail_to,$msgid*/$log_data){

        /*$log_data['rep_id'] = $user_id;
        $log_data['leademployeeid'] = $contact_id;
        $log_data['leadid'] = $lead_cust_opp_id;
        $log_data['logtype'] = 'EM594ce66d07b9f87';
        $log_data['call_type'] = 'complete';
        $log_data['path'] = 'no_path';
        $log_data['time'] = date('Y-m-d H:i:s');
        $log_data['starttime'] = date('Y-m-d H:i:s');
        $log_data['endtime'] = date('Y-m-d H:i:s');
        $log_data['type'] = $type;
        $log_data['module_id'] = $activeModule;
        $log_data['log_name'] = 'Outgoing email to '.$mail_to;
        $log_data['rating'] = 4;
        $log_data['message_id'] = $msgid;*/


        $ins = $this->emailModel->insert_activity_matched_data($log_data);

        return $ins;
    }

/*inserting start time and end time when user click to open a e-mail*/
    public function insert_reading_time(){
        
        if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json,TRUE);
                    $msg_id = $data['msg_id'];
                    $activeModule = $this->session->userdata('active_module_name');  

                    if($data['mail_type'] == 'assoc' || $data['mail_type'] == 'unassoc'){
                        $start_time = $data['reading_start_at'];
                        $end_time = $data['reading_end_at'];                        
                        $lead_cust_opp_id = isset($data['lead_cust_opp_id']) ? $data['lead_cust_opp_id'] : $msg_id;
                        $contact_id = isset($data['contact_id']) ? $data['contact_id'] : null;
                        $mail_from = $data['mail_from'];
                        $type = isset($data['type']) ? $data['type']:'unassociated';                    
                        $log_type = $this->personalmail->get_logtype('Incoming E-Mail');
                        $rating = $data['raring'];        
                        $remarks = $data['remarks'];
                        $user_id = $this->session->userdata('uid');

                        $data1=array('rep_id'=>$user_id,
                        'leadid'=>$lead_cust_opp_id,
                        'leademployeeid'=>$contact_id,
                        'starttime'=>date('Y-m-d H:i:s', strtotime($start_time)),
                        'endtime'=>date('Y-m-d H:i:s', strtotime($end_time)),
                        'logtype'=>$log_type[0]->lookup_id,
                        'type'=>$type,
                        'message_id'=>$msg_id,
                        'rating'=>$rating,
                        'call_type'=>'complete',
                        'log_name'=>'Incoming Email From '.$mail_from,
                        'time'=> date('Y-m-d H:i:s'),
                        'log_method'=>'manual',
                        'note' =>$remarks,
                        'module_id'=>$activeModule);

                        $insert=$this->personalmail->insert_log($data1);  
                        $change_read_state=$this->personalmail->change_mail_read_state($msg_id);
                        echo json_encode($insert);
                    }elseif($data['mail_type'] == 'conflict' || $data['mail_type'] == 'allmails' || $data['mail_type'] == 'sent_item' || $data['mail_type'] == 'internal'){
                        $change_read_state=$this->personalmail->change_mail_read_state($msg_id);
                        echo json_encode(true);
                    }   
            }catch (LConnectApplicationException $e) {
                    echo $this->exceptionThrower($e);
            }

        }else  {
                    redirect('indexController');
        }   
    
    }

    public function insert_reading_time_compose($start_time,$end_time,$msg_id){
                   
            $log_type=$this->personalmail->get_logtype('Outgoing E-Mail');        
            $user_id=$this->session->userdata('uid');

            $starttime = new DateTime($start_time);            
            $endtime = new DateTime($end_time);

            $data1=array('rep_id'=>$user_id,
            'leadid'=>$msg_id,
            'starttime'=>$start_time->format('Y-m-d H:i:s'),
            'endtime'=>$end_time->format('Y-m-d H:i:s'),
            'logtype'=>$log_type[0]->lookup_id,
            'type'=>'composing',
            'message_id'=>$msg_id);

            $insert=$this->personalmail->insert_log($data1);  
            return $insert;
    
    }

    public function get_sentmail(){
        if($this->session->userdata('uid')){
            try{
                $result = $this->personalmail->syncronise_sent_mails($this->session->userdata('uid'));               
                echo json_encode($result);
            }catch (LConnectApplicationException $e) {
                        echo $this->exceptionThrower($e);
            }
        }else{
            redirect('indexController');
        }    
    }        
    /*end*/

    public function test_sample()
    {
       $num = rand(10, 100); 
       $client_id = strtoupper(preg_replace('/\s+/', '', 'kumar g'));
       echo $client_id.$num;
    }

    public function sentMailApi()
    {
                    $dt = date('ymdHis');
                    $msgid = uniqid($dt);
                    $dmy = date("d-M-Y H:i:s"); 
                    $user_id = $this->session->userdata('uid');
                    //$user_id = $_POST['user_id'];
                    $activeModule = $this->session->userdata('active_module_name');  
                    //var_dump($_POST);
                    
                    $matched_data = array(); 
                    $data=$this->personalmail->get_out_server($user_id);
                    $result =$this->emailModel->get_personal_mail_connection($user_id);               
                    $username = $result[0]->email_id; 
                    //$password = $this->lconnecttcommunication->decryptIt($result[0]->password);
                    $password = $result[0]->password;
                    $ihostname = '{'.$result[0]->incoming_host.':'.$result[0]->incoming_port.'/imap/ssl}INBOX.Sent'; 
                    $inbox = imap_open ($ihostname, $username , $password) or die("ERROR: " . imap_last_error());              

                    /* SMTP settings */
                    $config = Array(
                    'protocol' => 'smtp',
                    'smtp_host' => 'ssl://'.$data[0]->outgoing_host,
                    'smtp_port' => $data[0]->outgoing_port,
                    'smtp_user' => $data[0]->email_id,
                    //'smtp_pass' => $this->lconnecttcommunication->decryptIt($data[0]->password),
                    'smtp_pass' => $data[0]->password,
                    'mailtype'  => 'html',
                    'charset'   => 'iso-8859-1'
                    );                  

                    $this->load->library('email',$config);
                    $this->email->set_newline("\r\n");
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= "Content-type:image/jpeg;charset=UTF-8;" . "\r\n";                  

                    //$txt = "<h3 style='font-weight:normal;'>Welcome to L-Connectt</h3>" . "\r\n";
                    $txt = $_POST['body'] . "\r\n";                    

                    //$subject = "L-Connectt $_POST['editor12']";
                    $this->email->set_header('L-Connectt',$headers);
                    $this->email->set_mailtype("html");
                    $this->email->set_newline("\r\n");
                    $this->email->from($data[0]->email_id, 'Test Mail');
                    $this->email->to('kumargowdaa87@gmail.com');
                    //$this->email->cc('');
                    //$this->email->bcc($_POST['mail_bcc_common']);
                    $this->email->subject('test Api');
                    $this->email->message($txt);
                    $date=date("Y-m-d H:i:s");
                
                     $data2=array(); 

                    if($_FILES['mail_attach']['error'][0] == 0){                   
                        
                        $msg = '';


                        if (array_key_exists('mail_attach', $_FILES)) {

                                foreach ($_FILES["mail_attach"]["error"] as $key => $error){

                                    if ($error == UPLOAD_ERR_OK) {

                                        $tmp_name = $_FILES["mail_attach"]["tmp_name"][$key];                           
                                        $name = $_FILES["mail_attach"]["name"][$key];
                                        $name = str_replace(' ', '_', $name);
                                       
                                        $directory = "./uploads/mail_attachments";
                                        if (!is_dir($directory)) {
                                        mkdir($directory);
                                        }

                                        $directory ="./uploads/mail_attachments/".$user_id;  
                                        if (!is_dir($directory)) {
                                        mkdir($directory);
                                        }    
                                        move_uploaded_file($tmp_name,"uploads/mail_attachments/".$user_id."/".$name);
                                        $this->email->attach("uploads/mail_attachments/".$user_id."/".$name);
                                        $data1=array('message_id'=>$msgid,
                                        'mail_attachment_filename'=>$name,
                                        'mail_attachment_path'=>"mail_attachments/".$user_id."/".$name);
                                        array_push($data2,$data1);
                                        $check = imap_check($inbox);

                                        //print_r($this->email->getSentMIMEMessage());
                                        $filename=$name; 
                                        $attachment = chunk_split(base64_encode($filename)); 

                                        $boundary = "------=".md5(uniqid(rand())); 

                                        $msg = ("From: ".$data[0]->email_id."\r\n".
                                        "To: ".$_POST['mail_to']."\r\n".
                                        "Subject: ".$_POST['mail_sub']."\r\n"
                                        . "Date: ".$dmy."\r\n"       
                                        . "MIME-Version: 1.0\r\n" 
                                        . "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n" 
                                        . "\r\n\r\n" 
                                        . "--$boundary\r\n" 
                                        . "Content-Type: text/html;\r\n\tcharset=\"ISO-8859-1\"\r\n" 
                                        . "Content-Transfer-Encoding: 8bit \r\n" 
                                        . "\r\n\r\n" 
                                        . $_POST['editor12']."\r\n" 
                                        . "\r\n\r\n" 
                                        . "--$boundary\r\n" 
                                        ."Content-Type: image/jpeg;\r\n"                            
                                        ."name=\"$filename\"\r\n"       
                                        . "Content-Transfer-Encoding: base64\r\n"
                                        . "Content-Disposition: attachment; filename=\"$filename\"\r\n" 
                                        . "\r\n" . $attachment . "\r\n" 
                                        . "\r\n\r\n\r\n"

                                        . "--$boundary--\r\n\r\n"); 

                                    }
                                        imap_append($inbox, $ihostname,$msg, "UNSEEN"); 
                                       
                        } 
                    }
            }             
    }

}    

?>

