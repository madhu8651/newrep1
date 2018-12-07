<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$logger'] = Logger::getLogger('admin_userController1');

class lconnectt_commonController extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('admin_userModel1','userinfo');
        $this->load->model('manager_teamManagersModel','teamManagers');
        $this->load->library('lconnecttcommunication');
    }
    public function get_fulluser_data(){
        if($this->session->userdata('uid')){
            try{
                $json = file_get_contents("php://input");
                $data = json_decode($json);

                $userid=$data->userid;

                $get_fulluser_data = $this->userinfo->get_fulluser_data($userid);
                echo json_encode($get_fulluser_data);
            }catch (LConnectApplicationException $e)  {
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
    public function resend_mail(){
        if($this->session->userdata('uid')){
            try{
                $json = file_get_contents("php://input");
                $data = json_decode($json);


                $dt = date('Ymdhisu');
                $token = uniqid($dt);

                $userid=$data->user_id;
                $add_employee_Id=$data->loginid;
                $mail_data = $this->userinfo->resend_mail($userid,$token);


                 /* Send Email block */

                  $uniqueid = basename(dirname($_SERVER['SCRIPT_FILENAME']));
                  $subject = "L Connectt Access for " .  $uniqueid;

                  $linkstr=base_url();

                  $pwdlink= base_url()."loginController/set_password/".$userid."/".$token;

                  $txt = "<h3 style='font-weight:normal;'>Welcome to L Connectt</h3>" . "\r\n";
                  $txt .= "<h4>You have been added as an user for the L Connectt console. Click <a href='$linkstr'>here</a> to login to your L Connectt Console!</h4>" . "\r\n";
                  $txt .= "<b>Login ID </b>: $add_employee_Id  <BR>" . "\r\n";
                  $txt .= "<h4>Use the below link to set your login password</h4> <BR>". "\r\n";
                  $txt .= "<BR> <center><a href='$pwdlink' style='cursor:pointer;text-decoration:none;'><button type='button' style='padding:8px;'>Set your password</button></a></center><BR>". "\r\n";

                  $users = array($userid);

                  $send_mail = $this->lconnecttcommunication->send_email($users,$subject,$txt);
                  echo json_encode($send_mail);
                  /* End of Send Email block */
            }catch (LConnectApplicationException $e)  {
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
    public function get_target(){
       if($this->session->userdata('uid')){
            try {
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $managerId=$data->manager_id;
                $targetData=$this->teamManagers->targetDetails($managerId);
                echo json_encode($targetData);
            }catch(LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                'errorCode' => $e->getErrorCode(), 
                'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");

                echo json_encode($errorArray);    
            }    
        }else {
            redirect('indexController');
        }
    }

    public function lock_unlock_ph(){
       if($this->session->userdata('uid')){
            try {
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $btntext=$data->btntext;
                $userid=$data->userid;
                if($btntext=='Lock Phone'){
                    $bit=2;
                }else{
                    $bit=0;
                }
                $applockfn=$this->userinfo->applock($bit,$userid);
                echo json_encode($applockfn);
            }catch(LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                'errorCode' => $e->getErrorCode(),
                'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");

                echo json_encode($errorArray);
            }
        }else {
            redirect('indexController');
        }
    }

	public function reset_ph(){
       if($this->session->userdata('uid')){
            try {
                $json = file_get_contents("php://input");
                $data = json_decode($json);

                $userid=$data->userid;

                $appresetfn=$this->userinfo->appreset($userid);
                echo json_encode($appresetfn);
            }catch(LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                'errorCode' => $e->getErrorCode(),
                'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");

                echo json_encode($errorArray);
            }
        }else {
            redirect('indexController');
        }
    }

}