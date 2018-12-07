<?php

defined('BASEPATH') or exit('No direct script access allowed');
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('myoperator_Model');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class myoperator_Model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}

	public function exception($lae){
		$GLOBALS['$log']->debug('!!!Exception Thrown from Model --- Passing to Controller!!!');
        throw $lae;
	}

	public function check_contact($mobile){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT lead_cust_id,contact_for,contact_id
from contact_details
where json_contains(contact_number,'[\"".$mobile."\"]','$.phone');");
			if($query->num_rows()==1){
				$res = $query->result();
				$dt = date('ymdHis');
				$notificationid = uniqid($dt);
				if($res[0]->contact_for=='lead'){
					$qry = $this->get_lead_details($res[0]->lead_cust_id);
					$notification = array(
						'notificationID' => $notificationid,
						'notificationShortText' => 'Call from lead '.$qry[0]->lead_name,
						'notificationText' => 'Incoming call from '.$qry[0]->lead_name,
						'notificationTimestamp' => date('Y-m-d H:i:s'),	
						'to_user' => $qry[0]->lead_rep_owner,
						'action_details' => 'Call',
						'read_state' => 0							
					);
					$rs = $this->insert_notification($notification);
					return "1";
				}else if($res[0]->contact_for=='customer'){
					$qry = $this->get_customer_details($res[0]->lead_cust_id);
					$notification = array(
						'notificationID' => $notificationid,
						'notificationShortText' => 'Call from lead '.$qry[0]->customer_name,
						'notificationText' => 'Incoming call from '.$qry[0]->customer_name,
						'notificationTimestamp' => date('Y-m-d H:i:s'),	
						'to_user' => $qry[0]->customer_rep_owner,
						'action_details' => 'Call',
						'read_state' => 0							
					);
					$rs = $this->insert_notification($notification);
					return "1";
				}
			}else{
				return "0";
			}
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function get_customer_details($cust_id){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT * FROM customer_info WHERE customer_id = '".$cust_id."'");
			return $query->result();
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function get_lead_details($lead_id){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT * FROM lead_info WHERE lead_id = '".$lead_id."'");
			return $query->result();
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function insert_notification($data){
		try{
			$query = $GLOBALS['$dbFramework']->insert('notifications',$data);
			return $query;
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}
}