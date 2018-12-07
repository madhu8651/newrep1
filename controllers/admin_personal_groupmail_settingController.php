<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_personal_groupmail_settingController');

class admin_personal_groupmail_settingController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('admin_personal_groupmail_settingModel','groupmailsetting');
    }

    public function index(){

        if($this->session->userdata('uid')){
           $this->load->view('admin_personal_groupmail_setting');
        }else{
            redirect('indexController');
        }
    }

    public function get_details($tabtype){
        if($this->session->userdata('uid')){
              try{
                    if($tabtype=='personal'){
                            $get_details = $this->groupmailsetting->get_detailsP();
                            echo json_encode($get_details);
                    }else{
                            $get_details = $this->groupmailsetting->get_detailsG();
                            echo json_encode($get_details);
                    }

              }
              catch (LConnectApplicationException $e){
                        $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                        $errorArray = array(
                                'errorCode' => $e->getErrorCode(),
                                'errorMsg' => $e->getErrorMessage()
                        );
                        $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                        $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                        echo json_encode($errorArray);
              }
        }else{
            redirect('indexController');
        }
    }



    public function validate_email(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $dt = date('ymdHis');

                    $emailid1=$data->emailid1;
                    $btntype=$data->btntype;
                    $incoming_account_type=$data->incoming_account_type;
                    $incoming_port=$data->incoming_port;
                    $incoming_server=$data->incoming_server;
                    $incoming_ssl_tls=$data->incoming_ssl_tls;
                    $login_email=$data->login_email;
                    $login_name=$data->login_name;
                    $login_password=$data->login_password;
                    $outgoing_port=$data->outgoing_port;
                    $outgoing_server=$data->outgoing_server;
                    $outgoing_ssl_tls=$data->outgoing_ssl_tls;
                    $emailid=$data->emailid;
                    $userid=$this->session->userdata('uid'); /* id to be taken from session */
                    $dt = date('ymdHis');
                    if($emailid1==""){
                        $letter=chr(rand(97,122));
                        $letter.=chr(rand(97,122));
                        $email_setID=$letter;
                        $email_setID.=$dt;
                    }else{
                        $email_setID=$emailid1;
                    }
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

                        $data1=array(
                             'email_settings_id'=>$emailid,
                             'user_id'=>$userid,
                             'name'=>$login_name,
                             'email_id'=>$login_email,
                             'password'=>$login_password,
                             'settings_key'=>'groupsetting',
                             'settings_value'=>'yes',
                             'timestamp'=>$dt,
                             'user_email_settings_id'=>$email_setID
                        );
                        $insert = $this->groupmailsetting->insert_data1($data1,$btntype,$emailid1);
                        echo json_encode("Sent");

                    }else{
                        echo json_encode("Not sent");
                    }

              }
              catch (LConnectApplicationException $e)  {
                                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                                    $errorArray = array(
                                            'errorCode' => $e->getErrorCode(),
                                            'errorMsg' => $e->getErrorMessage()
                                    );
                                    $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                                    $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                                    echo json_encode($errorArray);
              }
        }else{
            redirect('indexController');
        }

    }

    public function save_email(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $dt = date('ymdHis');
                    $userid=$this->session->userdata('uid'); /* id to be taken from session */

                    $incoming_account_type=$data->incoming_account_type;
                    $incoming_port=$data->incoming_port;
                    $time_zone=$data->time_zone;
                    $incoming_server=$data->incoming_server;
                    $incoming_ssl_tls=$data->incoming_ssl_tls;

                    $outgoing_port=$data->outgoing_port;
                    $outgoing_server=$data->outgoing_server;
                    $outgoing_ssl_tls=$data->outgoing_ssl_tls;
                    $emailid=$data->emailid;
                    $tabtype=$data->tabtype;

                    //echo $emailid;exit();

                    if($emailid==""){
                        $letter=chr(rand(97,122));
                        $letter.=chr(rand(97,122));
                        $email_setID=$letter;
                        $email_setID.=$dt;

                    }else{
                      $email_setID=$emailid;
                    }

                    if($tabtype=='personal'){
                       // echo $email_setID;exit();
                        $data = $this->groupmailsetting->get_detailsP();
                        foreach ($data as $data => $value) {
                            if($value->incoming_host == $incoming_server && $value->outgoing_host == $outgoing_server && $value->email_settings_id==$email_setID){
                               break;
                            }else if($value->incoming_host == $incoming_server && $value->outgoing_host == $outgoing_server){
                                echo json_encode('false');
                                die();
                            }
                        }

                        $data2=array(
                            'email_settings_id'=>$email_setID,
                            'incoming_host'=>$incoming_server,
                            'incoming_port'=>$incoming_port,
                            'time_zone'=>$time_zone,
                            'port_type'=>$incoming_account_type,
                            'incoming_ssl'=>$incoming_ssl_tls,
                            'outgoing_host'=>$outgoing_server,
                            'outgoing_port'=>$outgoing_port,
                            'outgoing_ssl'=>$outgoing_ssl_tls,
                            'email_settings_type'=>'personalsetting',
                            'timestamp'=>$dt
                        );
                        $insert = $this->groupmailsetting->insert_data($tabtype,$data2,$emailid);
                        echo json_encode($insert);

                    }else{
                        $data2=array(
                            'email_settings_id'=>$email_setID,
                            'incoming_host'=>$incoming_server,
                            'incoming_port'=>$incoming_port,
                            'port_type'=>$incoming_account_type,
                            'incoming_ssl'=>$incoming_ssl_tls,
                            'outgoing_host'=>$outgoing_server,
                            'outgoing_port'=>$outgoing_port,
                            'outgoing_ssl'=>$outgoing_ssl_tls,
                            'email_settings_type'=>'groupsetting',
                            'timestamp'=>$dt
                        );

                        $insert = $this->groupmailsetting->insert_data($tabtype,$data2,$emailid);
                        echo json_encode($insert);
                    }
              }
              catch (LConnectApplicationException $e){
                                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                                    $errorArray = array(
                                            'errorCode' => $e->getErrorCode(),
                                            'errorMsg' => $e->getErrorMessage()
                                    );
                                    $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                                    $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                                    echo json_encode($errorArray);
              }
        }else{
            redirect('indexController');
        }

    }

    public function update_tg_bit(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $tgbit=$data->tgbit;
                    $set_id=$data->set_id;

                    $update = $this->groupmailsetting->update_tg_bit($tgbit,$set_id);
                    echo json_encode($update);

            }catch (LConnectApplicationException $e){
                                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                                    $errorArray = array(
                                            'errorCode' => $e->getErrorCode(),
                                            'errorMsg' => $e->getErrorMessage()
                                    );
                                    $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                                    $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                                    echo json_encode($errorArray);
              }
        }
    }



}

?>