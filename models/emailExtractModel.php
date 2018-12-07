<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// define('ROOT_PATH', dirname(__DIR__) . '/');
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('emailExtractModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class emailExtractModel extends CI_Model{
	public function __construct(){
		parent::__construct();
	}

	public function exception($lae){
		$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $lae;
	}

	public function check_emails($mailid,$user_id){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT * FROM support_group_emails WHERE mail_to='".$mailid."' and user_id='$user_id' and mail_associated_state != 9 ");
			return $query;
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function insert_emails($data){
		try{
			$insert = $GLOBALS['$dbFramework']->insert('support_group_emails',$data);
			return $insert;
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function insert_email_attachments($data){
		try{	
			$insert = $GLOBALS['$dbFramework']->insert('support_group_email_attachments',$data);
			return $insert;
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}
	public function get_latest_email($mailid,$user_id){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT DATE_FORMAT(DATE(mail_date),'%d %b %Y') as last_date FROM support_group_emails WHERE mail_to = '".$mailid."' and user_id='$user_id' and mail_associated_state!=9 ORDER BY mail_date DESC LIMIT 1");
			return $query->result();
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function get_latest_dates($date,$user_id){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT mail_date FROM support_group_emails WHERE DATE(mail_date) = '".$date."' and user_id='$user_id' and mail_associated_state!=9 ");
			return $query->result();
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function get_group_mail_connection($user_id){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT um.map_id as settings_id,um.map_key,um.map_value as read_permission,es.incoming_host,es.incoming_port,es.port_type,es.incoming_ssl,ues.email_id,ues.password
			FROM user_mappings um,email_settings es,user_email_settings ues
			WHERE um.user_id = '".$user_id."'
			AND um.map_type = 'groupmail'
			AND um.map_id = ues.user_email_settings_id
			AND ues.email_settings_id = es.email_settings_id");
			return $query->result();
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function get_personal_mail_connection($user_id){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT es.email_settings_id as settings_id,es.incoming_host,es.incoming_port,es.port_type,es.incoming_ssl,ues.email_id,ues.password
				FROM email_settings es,user_email_settings ues
				WHERE ues.user_id='".$user_id."'
				and ues.settings_key in('personalsetting','emailsetting')
				AND ues.email_settings_id = es.email_settings_id");
				return $query->result();
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}


	public function check_cust_email($email){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT li.lead_name as name, cd.contact_name, cd.lead_cust_id, cd.contact_for, cd.contact_id
			from contact_details cd, lead_info li
			where li.lead_id=cd.lead_cust_id 			
			and (li.lead_closed_reason is null or li.lead_closed_reason in('temporary_loss','permanent_loss'))
			and json_contains(contact_email,'[\"".$email."\"]','$.email');");
		return $query;
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function check_oppo_exist($email){
		try{

			$query = $GLOBALS['$dbFramework']->query("SELECT c.contact_id, od.opportunity_name as name, c.contact_name, c.lead_cust_id,od.opportunity_id, 'opportunity' as contact_for
			from contact_details c, opportunity_details od
			where json_contains(c.contact_email,'[\"".$email."\"]','$.email')		
            and find_in_set(c.contact_id, replace(od.opportunity_contact,':',','))
			and closed_reason is null;");
		return $query;
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function check_customer_exist($email){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT ci.customer_name as name, c.contact_name, c.lead_cust_id, c.contact_id, ci.customer_id, 'customer' as contact_for
			from contact_details c,customer_info ci
			where json_contains(contact_email,'[\"".$email."\"]','$.email')
			and (c.lead_cust_id=ci.lead_id or c.lead_cust_id = ci.customer_id)
			group by customer_id");			

		return $query;
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}


	public function insert_notification($data){
		try{
			$query = $GLOBALS['$dbFramework']->insert_batch('notifications',$data);
			return $query;
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function insert_activity($data){
		try{
			$insert = $GLOBALS['$dbFramework']->insert_batch('rep_log',$data);
			return $insert;
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function get_opportunities($cust_id){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT * FROM opportunity_details
			WHERE lead_cust_id = '".$cust_id."'
			AND closed_reason is null");
			return $query;
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

	public function get_mail_lastdate($mailid,$user_id){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT mail_date as end_date FROM support_group_emails WHERE mail_to = '".$mailid."' and user_id='$user_id' and mail_associated_state!=9 ORDER BY mail_date DESC LIMIT 1");
			return $query->result();
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function insert_activity_matched_data($data){
		try{
			$insert = $GLOBALS['$dbFramework']->insert_batch('rep_log',$data);
			return $insert;
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function userEmail($user_id)
	{
		try
		{
			$query = $GLOBALS['$dbFramework']->query("SELECT es.incoming_host, ud.user_name as user_name,user_primary_email as email,ud.user_id as user_id from user_details ud, email_settings es where ud.user_id = '$user_id' group by ud.user_id");
			
			return $query->result();
		}
		catch(LConnectApplicationException $e)
		{
			$this->exception($e);
		}
	}

	public function fetchInternalEmails($fromemail){
		$query = $GLOBALS['$dbFramework']->query("SELECT email_id from user_email_settings
												where email_id = '$fromemail'");
			
		return $query->num_rows();
	}

	public function getUserNameNotify($fromemail)
	{
		$query = $GLOBALS['$dbFramework']->query("SELECT user_name as name from user_details
												where user_primary_email = '$fromemail'");
			
		return $query->result();
	}


}