<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
//include 'Master_Controller.php';
// include '/../core/LConnectApplicationException.php';
// include '/../log4php/src/main/php/Logger.php';
// Logger::configure(dirname(__FILE__).'/../log4php/src/test/resources/configs/config1.xml');

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('loginController');
                           //login
class loginController extends CI_Controller {
    public $log;
    public function __construct(){
        $status = parent::__construct();
        if($status!=1){
            if($this->session->userdata('uid')){
                $this->load->helper('url');
                $this->load->model('loginModel','login');
                $this->logout();
            }else{
                redirect('inactive_client_controller');
            }
        }
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('loginModel','login');
        $this->load->library('lconnecttcommunication');
    }
    public function index(){
        $GLOBALS['$log']->debug("Starting Login Controller");
        $success = false;
        $loginId = trim($_POST['login__username']," ");
        $pwd = trim($_POST['login__password']," ");
        //if tried to access this page directly without (user_name or pwd), redirect him to indexController
        if (!isset($_POST['login__username']) && !isset($_POST['login__password']))	{
			redirect('indexController/index');
			return ;
		}
        try {
            /*---------------------------------------------------*/
            //This needs to be reworked.
            //1. shouldn't send password in query instead, fetch password for user_id and then validate
            //it in code.
            //2. module to be fetched from user_module_plugin_mapping
            $status = $this->login->validateAccess($loginId,$pwd);
            $GLOBALS['$log']->debug("Status Code - ".$status['success']);
            $success = $status['success'];
            $clientid = basename(dirname($_SERVER['SCRIPT_FILENAME']));
            $clientinfo = $this->login->get_client_details();
            $versiontype = $clientinfo[0]->versiontype;
            $dateFormat = $clientinfo[0]->dateformat;
            $dateTimeFormat = $clientinfo[0]->date_timeformat;

            if($success==1){
                $login_status = $status['login_result'];
                $login_state = $login_status[0]->login_state;

                if($login_state==0){
                    $dt = date('ymdHis');
                    $session_id = uniqid($dt);

                    setcookie('session_id',$session_id,strtotime( '+1 year' ));

                    foreach($status['result'] as $data){
                        $userdata = array(
                            'uid' => $data->user_id,
                            'uname' => $data->name,
                            'photo' => $data->photo,
                            'phone' => $data->phone_num,
                            'email' => $data->emailId,
                            'employee_id' => $data->employee_id,
                            'modules' => $data->modules,
                            'reporting_to' => $data->reporting_to,
                            'clientid' => $clientid,
                            'login_time' => gmdate('Y-m-d H:i:s'),
                            'session_id' => $session_id,
                            'timezone' => 'Asia/Kolkata',
                            'login_url' => base_url(),
                            'versiontype' => $versiontype,
                            'date_format'=> $dateFormat,
                            'date_time_format'=> $dateTimeFormat
                        );
                        $this->session->set_userdata($userdata);
                    }

                    $modules = $status['result'];
                    $cxo = $modules[0]->cxo;
                    $sales = $modules[0]->sales;
                    $manager = $modules[0]->manager;
                    $_SESSION['cxo'] = $cxo;
                    $_SESSION['manager'] = $manager;
                    $_SESSION['sales'] = $sales;

                    $plugins = json_decode($modules[0]->plugins);
                    /*echo(count($plugins));
                    exit();*/
                    if(count($plugins) > 0){
                    if($plugins->Navigator==''){
                        $_SESSION['Navigator'] = 0;
                    }else{
                        $_SESSION['Navigator'] = 1;
                    }
                    if($plugins->Communicator==''){
                        $_SESSION['Communicator'] = 0;
                    }else{
                        $_SESSION['Communicator'] = 1;
                    }
                    if($plugins->Attendence==''){
                        $_SESSION['Attendence'] = 0;
                    }else{
                        $_SESSION['Attendence'] = 1;
                    }
                    if($plugins->Expense==''){
                        $_SESSION['Expense'] = 0;
                    }else{
                        $_SESSION['Expense'] = 1;
                    }
                    if($plugins->Library==''){
                        $_SESSION['Library'] = 0;
                    }else{
                        $_SESSION['Library'] = 1;
                    }
                    if($plugins->Inventory==''){
                        $_SESSION['Inventory'] = 0;
                    }else{
                        $_SESSION['Inventory'] = 1;
                    }
                    }
                    $user_id = $this->session->userdata('uid');

                    $login_ip = $this->getRealIpAddr();
                    $ip = array( 'login_ip' => $login_ip );
                    $cond = array( 'user_id' => $user_id );
                    $additional_details  = $this->login->update_login_details($ip,$cond);
                    $lgn_state = $this->login->update_login_state($user_id,"set1",$session_id);

                    if($cxo=='-' && $sales=='-' && $manager!='-'){
                        $_SESSION['active_module'] = $manager;
                        $_SESSION['active_module_name'] = "manager";
                        redirect('manager_dashboardsettingController');
                    }else if($cxo=='-' && $sales!='-' && $manager=='-'){
                        $_SESSION['active_module'] = $sales;
                        $_SESSION['active_module_name'] = "executive";
                        redirect('sales_mytaskController');
                    }else if($cxo=='-' && $sales=='-' && $manager=='-'){
                        $_SESSION['active_module'] = $modules[0]->user_id;
                        $_SESSION['active_module_name'] = "admin";
                        redirect('admin_dashboardController');
                    }else if($cxo!='-' && $sales=='-' && $manager=='-'){
                        redirect('indexController/multiple_login');
                    }else if($cxo!='-' && $sales!='-' && $manager=='-'){
                        redirect('indexController/multiple_login');
                    }else if($cxo!='-' && $sales=='-' && $manager!='-'){
                        redirect('indexController/multiple_login');
                    }else if($cxo!='-' && $sales!='-' && $manager!='-'){
                        redirect('indexController/multiple_login');
                    }else if($cxo=='-' && $sales!='-' && $manager!='-'){
                        redirect('indexController/multiple_login');
                    }
                }else if($login_state==1){
                    if(isset($_COOKIE['session_id'])){
                        $sess_id = $_COOKIE['session_id'];
                        $oldsess_id = $login_status[0]->session_id;

                        foreach($status['result'] as $data){
                            $userdata = array(
                                'uid' => $data->user_id,
                                'uname' => $data->name,
                                'photo' => $data->photo,
                                'phone' => $data->phone_num,
                                'email' => $data->emailId,
                                'employee_id' => $data->employee_id,
                                'modules' => $data->modules,
                                'reporting_to' => $data->reporting_to,
                                'clientid' => $clientid,
                                'login_time' => gmdate('Y-m-d H:i:s'),
                                'session_id' => $sess_id,
                                'timezone' => 'Asia/Kolkata',
                                'login_url' => base_url(),
                                'versiontype' => $versiontype,
                                'date_format'=> $dateFormat,
                                'date_time_format'=> $dateTimeFormat
                            );
                            $this->session->set_userdata($userdata);
                        }

                        $modules = $status['result'];
                        $cxo = $modules[0]->cxo;
                        $sales = $modules[0]->sales;
                        $manager = $modules[0]->manager;
                        $_SESSION['cxo'] = $cxo;
                        $_SESSION['manager'] = $manager;
                        $_SESSION['sales'] = $sales;

                        $plugins = json_decode($modules[0]->plugins);
                        /*echo(count($plugins));
                        exit();*/
                        if(count($plugins) >0){
                        if($plugins->Navigator==''){
                            $_SESSION['Navigator'] = 0;
                        }else{
                            $_SESSION['Navigator'] = 1;
                        }
                        if($plugins->Communicator==''){
                            $_SESSION['Communicator'] = 0;
                        }else{
                            $_SESSION['Communicator'] = 1;
                        }
                        if($plugins->Attendence==''){
                            $_SESSION['Attendence'] = 0;
                        }else{
                            $_SESSION['Attendence'] = 1;
                        }
                        if($plugins->Expense==''){
                            $_SESSION['Expense'] = 0;
                        }else{
                            $_SESSION['Expense'] = 1;
                        }
                        if($plugins->Library==''){
                            $_SESSION['Library'] = 0;
                        }else{
                            $_SESSION['Library'] = 1;
                        }
                        if($plugins->Inventory==''){
                            $_SESSION['Inventory'] = 0;
                        }else{
                            $_SESSION['Inventory'] = 1;
                        }
                        }
                        $user_id = $this->session->userdata('uid');

                        $login_ip = $this->getRealIpAddr();
                        $ip = array( 'login_ip' => $login_ip );
                        $cond = array( 'user_id' => $user_id );
                        $additional_details  = $this->login->update_login_details($ip,$cond);

                        if($sess_id!=$oldsess_id){
                            $lgn_state = $this->login->update_login_state($user_id,"set2",$sess_id);
                        }

                        if($cxo=='-' && $sales=='-' && $manager!='-'){
                            $_SESSION['active_module'] = $manager;
                            $_SESSION['active_module_name'] = "manager";
                            redirect('manager_dashboardsettingController');
                        }else if($cxo=='-' && $sales!='-' && $manager=='-'){
                            $_SESSION['active_module'] = $sales;
                            $_SESSION['active_module_name'] = "executive";
                            redirect('sales_mytaskController');
                        }else if($cxo=='-' && $sales=='-' && $manager=='-'){
                            $_SESSION['active_module'] = $modules[0]->user_id;
                            $_SESSION['active_module_name'] = "admin";
                            redirect('admin_dashboardController');   
                        }else if($cxo!='-' && $sales=='-' && $manager=='-'){
                            redirect('indexController/multiple_login');
                        }else if($cxo!='-' && $sales!='-' && $manager=='-'){
                            redirect('indexController/multiple_login');
                        }else if($cxo!='-' && $sales=='-' && $manager!='-'){
                            redirect('indexController/multiple_login');
                        }else if($cxo!='-' && $sales!='-' && $manager!='-'){
                            redirect('indexController/multiple_login');
                        }else if($cxo=='-' && $sales!='-' && $manager!='-'){
                            redirect('indexController/multiple_login');
                        }

                    }else{
                        $dt = date('ymdHis');
                        $session_id = uniqid($dt);

                        setcookie('session_id',$session_id,strtotime( '+1 year' ));

                        foreach($status['result'] as $data){
                            $userdata = array(
                                'uid' => $data->user_id,
                                'uname' => $data->name,
                                'photo' => $data->photo,
                                'phone' => $data->phone_num,
                                'email' => $data->emailId,
                                'employee_id' => $data->employee_id,
                                'modules' => $data->modules,
                                'reporting_to' => $data->reporting_to,
                                'clientid' => $clientid,
                                'login_time' => gmdate('Y-m-d H:i:s'),
                                'session_id' => $session_id,
                                'timezone' => 'Asia/Kolkata',
                                'login_url' => base_url(),
                                'versiontype' => $versiontype,
                                'date_format'=> $dateFormat,
                                'date_time_format'=> $dateTimeFormat
                            );
                            $this->session->set_userdata($userdata);
                        }

                        $modules = $status['result'];
                        $cxo = $modules[0]->cxo;
                        $sales = $modules[0]->sales;
                        $manager = $modules[0]->manager;
                        $_SESSION['cxo'] = $cxo;
                        $_SESSION['manager'] = $manager;
                        $_SESSION['sales'] = $sales;

                        $plugins = json_decode($modules[0]->plugins);
                        /*echo(count($plugins));
                        exit();*/
                        if(count($plugins) >0){
                        if($plugins->Navigator==''){
                            $_SESSION['Navigator'] = 0;
                        }else{
                            $_SESSION['Navigator'] = 1;
                        }
                        if($plugins->Communicator==''){
                            $_SESSION['Communicator'] = 0;
                        }else{
                            $_SESSION['Communicator'] = 1;
                        }
                        if($plugins->Attendence==''){
                            $_SESSION['Attendence'] = 0;
                        }else{
                            $_SESSION['Attendence'] = 1;
                        }
                        if($plugins->Expense==''){
                            $_SESSION['Expense'] = 0;
                        }else{
                            $_SESSION['Expense'] = 1;
                        }
                        if($plugins->Library==''){
                            $_SESSION['Library'] = 0;
                        }else{
                            $_SESSION['Library'] = 1;
                        }
                        if($plugins->Inventory==''){
                            $_SESSION['Inventory'] = 0;
                        }else{
                            $_SESSION['Inventory'] = 1;
                        }
                        }
                        $user_id = $this->session->userdata('uid');

                        $login_ip = $this->getRealIpAddr();
                        $ip = array( 'login_ip' => $login_ip );
                        $cond = array( 'user_id' => $user_id );
                        $additional_details  = $this->login->update_login_details($ip,$cond);
                        $lgn_state = $this->login->update_login_state($user_id,"set2",$session_id);

                        if($cxo=='-' && $sales=='-' && $manager!='-'){
                            $_SESSION['active_module'] = $manager;
                            $_SESSION['active_module_name'] = "manager";
                            redirect('manager_dashboardsettingController');
                        }else if($cxo=='-' && $sales!='-' && $manager=='-'){
                            $_SESSION['active_module'] = $sales;
                            $_SESSION['active_module_name'] = "executive";
                            redirect('sales_mytaskController');
                        }else if($cxo=='-' && $sales=='-' && $manager=='-'){
                            $_SESSION['active_module'] = $modules[0]->user_id;
                            $_SESSION['active_module_name'] = "admin";
                            redirect('admin_dashboardController');   
                        }else if($cxo!='-' && $sales=='-' && $manager=='-'){
                            redirect('indexController/multiple_login');
                        }else if($cxo!='-' && $sales!='-' && $manager=='-'){
                            redirect('indexController/multiple_login');
                        }else if($cxo!='-' && $sales=='-' && $manager!='-'){
                            redirect('indexController/multiple_login');
                        }else if($cxo!='-' && $sales!='-' && $manager!='-'){
                            redirect('indexController/multiple_login');
                        }else if($cxo=='-' && $sales!='-' && $manager!='-'){
                            redirect('indexController/multiple_login');
                        }

                    }
                }else{
                    $errorCode = '003';
                    $GLOBALS['$log']->error('Multiple user login:  '. $loginId);
                    throw new LConnectApplicationException($errorCode, new Exception(), "One login is allowed! Please logout from other session");
                }
                
            /*---------------------------------------------------*/
            }else if($success==0){
                $errorCode = '001';
                $GLOBALS['$log']->error('Invalid user:  '. $loginId);
                throw new LConnectApplicationException($errorCode, new Exception(), "Username or Password is wrong Please try again");
            }else if($success==2){
                $GLOBALS['$log']->debug('Enter status code 2');
                $errorCode = '002';
                $GLOBALS['$log']->error('Inactive user:  '. $loginId);
                throw new LConnectApplicationException($errorCode, new Exception(), "Username is deactivated");
            }
        } catch(LConnectApplicationException $lae) {
            $GLOBALS['$log']->debug('Error Message:' . $lae->getErrorMessage());
            $errMsg = $lae->getErrorMessage();
            $errorCode = $lae->getErrorCode();
            $array=array('errorCode'=> $errorCode, 'errorMsg' => $errMsg);
            $this->load->view('index', $array);
       }
    }
	
	public function phone_login(){
        $success = true;
        $loginId= trim($_POST['login__username']," ");
        $pwd= trim($_POST['login__password']," ");
        $devID= trim($_POST['login__deviceId']," ");
		$result = array();
		
		$status = $this->login->phone_validate($loginId,$pwd);
            if($status->num_rows()>0){
				foreach($status->result_array() as $data){
                    $userdata = array(
                        'uid' => $data['user_id'],
                        'uname' => $data['user_name'],
                        'product' => $data['user_product'],
                        'photo' => $data['photo'],
                        'rep_id' => $data['rep_id'],
                        'rep_actvstate' => $data['rep_actvstate'],
                        'location_tracking' => $data['location_tracking'],
                        'call_recording' => $data['call_recording'],
                        'holidaycalendar' => $data['holidaycalendar'],
                        'team_id' => $data['team_id'],
                        'rep_mgr' => $data['rep_mgr']
                    );
				}
				
				$update = $this->login->phone_updateRep($devID,$userdata['rep_id']);
				if($update == true){
					$success = true;
					$result['success'] = true;
					$result['data'] = $userdata;
					echo json_encode($result);
				} else{
					$success = false;
					$result['success'] = false;
					echo json_encode($result);
				}
				
			} else{
                $success = false;
				$result['success'] = false;
				echo json_encode($result);
            }
	}
	
	public function phone_getClient(){
		$clientID= trim($_POST['login__clientId']," ");
		$result = array();
		
		$status = $this->login->phone_fetch_client($clientID);
		if($status->num_rows()>0){
			$success = true;
			$result['success'] = true;
			$result['data'] = $status->result();
			echo json_encode($result);
		} else {
			$success = false;
			$result['success'] = false;
			echo json_encode($result);
		}
	}
	
    public function logout(){
        $array_items = array('uid' => '', 'uname' => '','photo' => '','phone' =>'','email' => '','employee_id' => '','modules' => '','reporting_to' => '','clientid' => '','login_time' => '','session_id' => '','timezone' => '','login_url' => '','versiontype' => '');
        $user_id = $this->session->userdata('uid');

        $login_time = $this->session->userdata('login_time');
        $last_login = array( 'last_login_time' => $login_time );
        $cond = array( 'user_id' => $user_id );
        $additional_details  = $this->login->update_login_details($last_login,$cond);
        $login_state = $this->login->update_login_state($user_id,"unset",'1');
        $this->session->unset_userdata($array_items);
        unset($_SESSION['active_module']);
        unset($_SESSION['active_module_name']);
        unset($_SESSION['cxo']);
        unset($_SESSION['manager']);
        unset($_SESSION['sales']);
        unset($_SESSION['Navigator']);
        unset($_SESSION['Communicator']);
        unset($_SESSION['Attendence']);
        unset($_SESSION['Inventory']);
        unset($_SESSION['Library']);
        unset($_SESSION['Expense']);
        $this->session->sess_destroy();
        setcookie("session_id","", time()-3600);
        unset ($_COOKIE['session_id']);
        redirect('lconnectt_controller/close');
    }

    public function checkUserState(){
        $json = file_get_contents("php://input");
        $jdata = json_decode($json); 
        $uid = $jdata->user_id;
        $res = $this->login->checkUserState($uid);
        echo $res->num_rows();
            
    }
	
	public function logout_on_window_close(){
		$array_items = array('uid' => '', 'uname' => '','photo' => '','phone' =>'','email' => '','employee_id' => '','modules' => '','reporting_to' => '','clientid' => '','login_time' => '','session_id' => '','timezone' => '','login_url' => '','versiontype' => '');
        $user_id = $this->session->userdata('uid');
        $login_state = $this->login->update_login_state($user_id,"unset");
        $this->session->unset_userdata($array_items);
        unset($_SESSION['active_module']);
        unset($_SESSION['active_module_name']);
        unset($_SESSION['cxo']);
        unset($_SESSION['manager']);
        unset($_SESSION['sales']);
        unset($_SESSION['Navigator']);
        unset($_SESSION['Communicator']);
        unset($_SESSION['Attendence']);
        unset($_SESSION['Inventory']);
        unset($_SESSION['Library']);
        unset($_SESSION['Expense']);
        $this->session->sess_destroy();
		echo 1;
	}


    function getRealIpAddr(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function checkSessionID(){
        $uid = $this->input->post('user_id');
        $sess_id = $_COOKIE['session_id'];
        $session_status = $this->login->checkSessionID($sess_id,$uid);
        if($session_status==1){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function forgot_password(){
        $GLOBALS['$log']->debug("Starting Login Controller/forgot password");
        $this->load->view('user_reset_password');
    }

    public function send_reset_password_email(){
        $emp_id = $this->input->post('emp_id');

        try{
            $query = $this->login->checkEmployee($emp_id);

            if($query->num_rows()>0){
                $res = $query->result();
                $user_id = $res[0]->user_id;
                $primary_email = $res[0]->user_primary_email;
                $dt = date('Ymdhisu');
                $token = uniqid($dt);
                $update_pwd = $this->login->updatePassword($user_id,$token);

                if($update_pwd==1){
                    $subject = "L Connectt Password Reset";

                    $linkstr= base_url()."loginController/set_password/".$user_id."/".$token;

                    $txt = "<BR>You recently requested to reset your password for your L Connectt account. Click the link below to reset it.". "\r\n";
                    $txt .= "<BR> <center><a href='$linkstr' style='cursor:pointer;text-decoration:none;'><button type='button' style='padding:8px;' bgcolor='#b5000a'>Reset your password</button></a></center><BR>". "\r\n";

                    $txt .= "If you did not request a password reset, please ignore this email." . "\r\n";
                    $users = array($user_id);
                    $send_mail = $this->lconnecttcommunication->send_email($users,$subject,$txt);

                    $response = true;
                    echo $response;
                    
                }
            }else{
                $response = false;
                echo $response;
            }
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
    }

    public function set_password($uid,$token){
        $checktoken = $this->login->checkToken($uid,$token);
        if($checktoken==1){
            $data['status'] = 1;
            $data['user_id'] = $uid;
            $this->load->view('reset_password',$data);
        }else{
            $data['status'] = 0;
            $data['user_id'] = $uid;
            $this->load->view('reset_password',$data);
        }
    }

    public function user_set_password(){
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $user_id = $data->user_id;
        $password = $data->password;
        try{
            $pwd_set = $this->login->user_set_password($user_id,$password);
            if($pwd_set==1){
                $res['response'] = true;
                echo json_encode($res);
            }else{
                $res['response'] = false;
                echo json_encode($res);
            }
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
    }

    public function userBehaviour(){
        if($this->session->userdata('uid')){
            try{
              
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $userId = $this->session->userdata('uid');

                $userData = array(
                            'user_id'=>$userId,
                            'start_timestamp'=>$data->page_start_time,
                            'resource_name'=>$data->page_name
                            );

                $insertData = $this->login->insertUserBehaviourData($userData);
                echo json_encode($insertData);

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
}
?>
