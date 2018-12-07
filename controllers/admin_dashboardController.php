<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

class admin_dashboardController extends Master_Controller{
    public function  __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_dashboardModel','clientinfo');
        $this->load->library('lconnecttcommunication');
    }
    public function index(){
        if($this->session->userdata('uid')){
            $this->load->view('admin_dashboard_view');
        }else{
            redirect('indexController');
        }
    }
    
    public function get_clientinfo(){
        if($this->session->userdata('uid')){
            $clientinfo = $this->clientinfo->view_data();
            $data=json_encode($clientinfo);
            //echo $data;
        }else{
            redirect('indexController');
        } 
    }
    public function get_userlicense(){
        if($this->session->userdata('uid')){
            $managerinfo = $this->clientinfo->manage_licence();
            echo json_encode($managerinfo);
        }else{
            redirect('indexController');
        }
    }
    public function upgrade_mail(){
		if($this->session->userdata('uid')){
		    try{
    			$json = file_get_contents("php://input");
    			$data = json_decode($json);


                $dt = date('Ymdhisu');
                $token = uniqid($dt);

    			$userid=$this->session->userdata('uid');/* id to be taken from session */
    			$versionType=$data->versionType;
    			$userCount=$data->userCount;
    			$clientname=$data->clientname;

                 /* Send Email block */

                  $uniqueid = basename(dirname($_SERVER['SCRIPT_FILENAME']));
                  $subject = "Request for paid version from L-Connectt Lite";

                  //$linkstr=base_url();

                  //$pwdlink= base_url()."loginController/set_password/".$userid."/".$token;

                  $txt="<h4>Hi,</h4>";
                  $txt.="<p>The customer <b>'$clientname'</b> has requested for an upgrade <br/>" ;
                  $txt.="to the paid version from the Lite version of L-Connectt. <br/>";
                  $txt.="Please find below the request details.<br/>";
                  $txt.="Details of requested version is <b>$versionType</b> and number of module user licenses are <b>$userCount</b>.</p>";
                  $txt.="<p>Thank You</p>";


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
}
?>
