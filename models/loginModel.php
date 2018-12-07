<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('loginModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class loginModel extends CI_Model{
    public function __construct(){
        parent::__construct();
    }
    public function validateAccess($loginID,$password) {
    	$res = array();
        $query=$GLOBALS['$dbFramework']->query("SELECT a.user_id,a.user_name as name,a.phone_num,a.emailId,a.login_pwd,a.photo,a.employee_id,a.reporting_to,
IF(b.module_id->'$.cxo'!='0',JSON_UNQUOTE(b.module_id->'$.cxo'),'-') as cxo,IF(b.module_id->'$.sales'!='0',JSON_UNQUOTE(b.module_id->'$.sales'),'-') as sales,
IF(b.module_id->'$.Manager'!='0',JSON_UNQUOTE(b.module_id->'$.Manager'),'-') as manager,b.module_id as modules,b.plugin_id as plugins
FROM user_details a,user_module_plugin_mapping b
WHERE a.user_id = b.user_id
AND a.login_id =  '$loginID' GROUP BY a.user_id");
        if($query->num_rows()>0){
        	$result = $query->result();
        	$stored_password = $result[0]->login_pwd;
        	if($stored_password==$password){
				$qry1 = $GLOBALS['$dbFramework']->query("SELECT user_id,user_name FROM user_details WHERE user_state=1 AND  login_id='$loginID'");
				if($qry1->num_rows()>0){
					$qry2 = $GLOBALS['$dbFramework']->query("SELECT user_id,user_name,login_state,session_id FROM user_details WHERE login_id='$loginID'");
					if($qry2->num_rows()>0){
						$res['success'] = 1;
						$res['result'] = $result;
						$res['login_result'] = $qry2->result();
						return $res;
					}
				}else{
					$res['success'] = 2;
					return $res;
				}
        	}else{
        		$res['success'] = 0;
        		return $res;
        	}
        }else{
        	$res['success'] = 0;
        	return $res;
        }
	}
	
	public function checkUserState($uid){
		$query = $GLOBALS['$dbFramework']->query("SELECT user_id,user_name FROM user_details WHERE user_state=0 AND user_id='$uid'");
		return $query;
	}

	public function update_login_state($user_id,$set,$session_id){
		if($set=="set1"){
			$query = $GLOBALS['$dbFramework']->query("UPDATE user_details SET login_state = 1,session_id = '$session_id' WHERE user_id = '$user_id'");
			return $query;
		}else if($set=="unset"){
			$query = $GLOBALS['$dbFramework']->query("UPDATE user_details SET login_state = 0,session_id = null WHERE user_id = '$user_id'");
			return $query;
		}else if($set=="set2"){
			$query = $GLOBALS['$dbFramework']->query("UPDATE user_details SET session_id = '$session_id' WHERE user_id = '$user_id'");
			return $query;
		}
	}

	public function get_plugins($plugins){
		$query = $GLOBALS['$dbFramework']->query("SELECT plugin_id,plugin_name FROM plugin_master WHERE plugin_id IN ($plugins);");
		return $query;
	}

	public function update_login_details($login_ip,$cond){
		$query = $GLOBALS['$dbFramework']->update("user_details",$login_ip,$cond);
		return $query;
	}

	//phone function
	public function phone_validate($loginID,$password) {
		$query=$GLOBALS['$dbFramework']->query("SELECT a.user_id,a.user_name,a.user_product,a.photo,a.team_id,a.reporting_to as rep_mgr,a.user_state as rep_actvstate,b.location_tracking,b.call_recording,b.holiday_calender as holidaycalendar,b.user_id as rep_id FROM user_details a,representative_details b WHERE b.user_id = a.user_id AND a.login_id='$loginID' AND a.login_pwd='$password' AND a.user_state=1");
		
		return $query;
	}
	
	//phone function
	public function phone_updateRep($devID, $repId) {
		$query1=$GLOBALS['$dbFramework']->query("UPDATE representative_details SET rep_devid='$devID' , rep_lgnstate = '1' WHERE user_id ='$repId'");
		return $query1;
	}
	
	public function phone_fetch_client($clientID){
		$query = $GLOBALS['$dbFramework']->query("SELECT client_name,licence_end_date FROM `client_info` WHERE client_id='$clientID'");
		return $query;
	}

	public function checkSessionID($sess_id,$uid){
		$query = $GLOBALS['$dbFramework']->query("SELECT session_id FROM user_details WHERE user_id = '$uid' ");
		$result = $query->result();
		$dbsess_id = $result[0]->session_id;
		if($dbsess_id==$sess_id){
			return 1;
		}else{
			return 0;
		}
	}

	public function checkEmployee($emp_id){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT user_primary_email,user_id,login_id,login_pwd,user_name FROM user_details WHERE employee_id='$emp_id'");
			return $query;
		}catch(LConnectApplicationException $e){
			$GLOBALS['$log']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
		}
	}

	public function updatePassword($user_id,$token){
		try{
			$query = $GLOBALS['$dbFramework']->query("UPDATE user_details SET login_pwd='qwerty0917mnop',password_reset_token='$token' WHERE user_id='$user_id'");
			return $query;
		}catch(LConnectApplicationException $e){
			$GLOBALS['$log']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
		}
	}

	public function checkToken($uid,$token){
		$query = $GLOBALS['$dbFramework']->query("SELECT password_reset_token FROM user_details WHERE user_id='$uid'");
		if($query->num_rows()>0){
			$result = $query->result();
        	$stored_token = $result[0]->password_reset_token;
        	if($stored_token==$token){
        		return 1;
        	}else{
        		return 0;
        	}
		}
	}

	public function user_set_password($user_id,$password){
		try{
			$query = $GLOBALS['$dbFramework']->query("UPDATE user_details SET login_pwd='$password',password_reset_token=null,login_state=0,session_id=null WHERE user_id='$user_id'");
			return $query;
		}catch(LConnectApplicationException $e){
			$GLOBALS['$log']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
		}
	}

	public function insertUserBehaviourData($userData) {
		try{
			$query = $GLOBALS['$dbFramework']->insert('user_beh_details',$userData); 
			return $query;
		}catch(LConnectApplicationException $e){
			$GLOBALS['$log']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
		}
	}

	public function get_client_details(){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT * FROM client_info");
			return $query->result();
		}catch(LConnectApplicationException $e){
			$GLOBALS['$log']->debug('!!!Exception Thrown to Model ---- Passing to Controller!!!');
			throw $e;
		}
	}
}
?>