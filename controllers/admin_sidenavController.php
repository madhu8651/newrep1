<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_sidenavController');

class admin_sidenavController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_sidenavModel','editprofile');
        $this->load->library('lconnecttcommunication');
    }


    public function get_data(){
        if($this->session->userdata('uid')){
        try{
                  $userid=$this->session->userdata('uid'); /* id to be taken from session */
                  $userdata=$this->editprofile->view_userinfo($userid);
                  echo json_encode($userdata);
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

    public function get_data1(){
        if($this->session->userdata('uid')){
          try{
                      $userid=$this->session->userdata('uid'); /* id to be taken from session */
                      $userdata=$this->editprofile->view_userinfo1($userid);
                      echo json_encode($userdata);
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

    public function get_photo(){
        if($this->session->userdata('uid')){
          try{
                  $userid=$this->session->userdata('uid'); /* id to be taken from session */
                  $updatefilename=$this->editprofile->view_userinfo($userid);
                  echo json_encode($updatefilename);
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
    public function update_managerinfo(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $adminName=$data->managername;
                    $adminPhone=$data->managerphone;
                    $adminUserName=$data->managerloginid;
                    $managerid=$this->session->userdata('uid');/* id to be taken from session */

                    $data=array(
                       'user_name' =>$adminName,
                       'login_id'=>$adminUserName
                    );
                    $updateinfo=$this->editprofile->update_managerinfo($managerid,$data,$adminPhone);
                    echo json_encode($updateinfo);
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
    public function do_upload(){
        if($this->session->userdata('uid')){
          try{
                  $managerid=$this->session->userdata('uid'); /* id to be taken from session */
                  $config['upload_path'] = './uploads';
                  $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
                  $config['max_size'] = "2048000"; // Can be set to particular file size , here it is 2 MB(2048 Kb)
                  $config['overwrite']  = TRUE;
              $this->load->library('upload', $config);
                  if ( ! $this->upload->do_upload('userfile'))
                  {
                        $error = array('error' => $this->upload->display_errors());
                        $this->session->set_flashdata('error',$error['error']);
                        $actual_link=$this->input->post('pre_url');
                        redirect($actual_link);
                  }else{
                        $data = array('upload_data' => $this->upload->data());
                        $filename=$data['upload_data']['file_name'];
                        $filedata=array(
                             'photo'=>$filename
                        );
                        $updatefilename=$this->editprofile->do_upload($managerid,$filedata);
                        if($updatefilename==true){
                             $actual_link=$this->input->post('pre_url');
                             redirect($actual_link);
                        }
                  }
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
    public function update_password(){
        if($this->session->userdata('uid')){
          try{
                  $managerid=$this->session->userdata('uid'); /* id to be taken from session */
                  $json = file_get_contents("php://input");
                  $data1 = json_decode($json);
                  $adminNewPassword=$data1->managerloginid;
                  $data=array(
                      'login_pwd' =>$adminNewPassword
                  );
                  $updateinfo=$this->editprofile->update_managerinfo1($managerid,$data);
                  if($updateinfo==TRUE){
                    echo 1;
                  }else{
                    echo 0;
                  }
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
        }else{
            redirect('indexController');
        }
    }

    public function save_timezones(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $timeZone=$data->timeZone;
                    $managerid=$this->session->userdata('uid');/* id to be taken from session */
                    $timezone_settings=$this->editprofile->timezone_settings($managerid,$timeZone);
                    echo json_encode($timezone_settings);
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
    public function get_timezones(){
        if($this->session->userdata('uid')){
        try{
                  $userid=$this->session->userdata('uid'); /* id to be taken from session */
                  $userdata=$this->editprofile->get_timezones($userid);
                  echo json_encode($userdata);
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

    public function save_user_email_settings(){
      if($this->session->uid){
        try {
          $userid = $this->session->uid; /* User ID to be taken from session */
          $json = file_get_contents("php://input");
          $data = json_decode($json);

          $login_name = $data->login_name;
          $login_email = $data->login_email;
          $login_password = $this->lconnecttcommunication->encryptIt($data->login_password);
          //$login_password = $data->login_password;
          $signature = $data->signature;         
          $dt = date('ymdHis');
          $settings_id = uniqid($dt);

          $res = $this->editprofile->get_email_settings($data->incoming_host);
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
                'signature' =>$signature
            );
            $insert = $this->editprofile->save_user_email_settings($email_data);
            echo json_encode($insert);
          }else{
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
            $errorArray = array(
              'errorCode' => 010,
              'errorMsg' => 'Email Settings not found!'
            );
            $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
            $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
            echo json_encode($errorArray);
          }

        } catch (LConnectApplicationException $e) {
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

          $res = $this->editprofile->get_email_settings($data->incoming_host);

          if($res->num_rows()>0){
            $result = $res->result();
            $login_email=$data->login_email;
            $login_name=$data->login_name;
            $login_password=$data->login_password;
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
          /*  $txt = "<h3>Hi $login_name,</h3>" . "\r\n";
            $txt .= "<h3 style='font-weight:normal;'>Welcome to L Connectt</h3>" . "\r\n";
            $txt .= "Test Mail" . "\r\n";
            $txt .= "<h4><i>Thank You, <BR> - Team L-Connectt </i></h4><BR>" . "\r\n";*/

            $subject = "L Connectt User Mail Test";
            $this->email->set_header('LConnectt',$headers);
            $this->email->set_mailtype("html");
            $this->email->set_newline("\r\n");
            $this->email->from($login_email, 'Test Mail');
            $this->email->to($login_email);
            $this->email->subject($subject);    
            //$imgpath = base_url()."images/new/White Logo.png";            

            $txt= '<table border="0" cellspacing="0" cellpadding="0" style="max-width:600px" align="center">
         <tbody>
            <tr>
               <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tbody>
                        <tr>
                           <td bgcolor="#999" align="left"><img width="170" src= "'.base_url().'images/new/White Logo.png" alt="logo"/></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
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
                                       <td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:13px;color:#202020;line-height:1.5"><h3>Hi '.$login_name.', <br><br> This is a test email for User Mail on L Connectt.</h3></td>
                                    </tr>
                                    <tr>
                                       <td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:13px;color:#202020;line-height:1.5">                                                                               
                                       </td>
                                    </tr>
                                    <tr height="32px"></tr>
                                    <tr>
                                       <td><h4><i>Thank You, <BR> - Team L Connectt </i></h4><BR></td>
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
            <tr height="16" bgcolor="#fafafa">
          <td style="max-width:600px;font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:10px;color:#bcbcbc;line-height:1.5">&nbsp;</td>
          </tr>
         </tbody>
      </table>';
       $this->email->message($txt);

            if($this->email->send()){
              echo json_encode("Sent");
            }else{
              echo json_encode("Not sent");
            }
          }else{
            echo json_encode("not found");
          }

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

    public function get_user_email_settings(){
      if($this->session->uid){
        try{
          $userid = $this->session->uid;
          $result = $this->editprofile->get_user_email_settings($userid);
          echo json_encode($result);
        }catch(LConnectApplicationException $e){
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

    public function update_user_email_settings(){
      if($this->session->uid){
        try {
          $userid = $this->session->uid; /* User ID to be taken from session ..*/
          $json = file_get_contents("php://input");
          $data = json_decode($json);
          $res = $this->editprofile->get_email_settings($data->incoming_host);
          $result = $res->result();

          if($data->isSignature == 1){

            $settings_id = $data->settings_id;
            $email_data = array('signature'=>$data->signature,
                          'email_settings_id'=>$result[0]->email_settings_id,
                          'timestamp' => date('Y-m-d H:i:s'),
                          'signature' => $data->signature);

          }else{

                $login_name = $data->login_name;
                $login_email = $data->login_email;          

                /* encryption added for password added */
                $login_password = $this->lconnecttcommunication->encryptIt($data->login_password);
                //$login_password = $data->login_password;
                $settings_id = $data->settings_id;
                $signature = $data->signature;

                $email_data = array(
                'name' => $login_name,
                'email_id' => $login_email,
                'password' => $login_password,
                'email_settings_id'=>$result[0]->email_settings_id,
                'timestamp' => date('Y-m-d H:i:s'),
                'signature' => $signature
                );
          }
          
            $insert = $this->editprofile->update_user_email_settings($email_data,$settings_id);
            echo json_encode($insert);

        } catch (LConnectApplicationException $e) {
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