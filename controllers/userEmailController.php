<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$logger'] = Logger::getLogger('userEmailController');

class userEmailController extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('emailExtractModel','emailModel');
		$this->load->model('admin_sidenavModel','editprofile');
		$this->load->model('sales_com_personalmailModel','personalmail');
		$this->load->library('lconnecttcommunication');
	}
	public function exception($lae){
		$GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
		$errorArray = array(
				'errorCode' => $lae->getErrorCode(),
				'errorMsg' => $lae->getErrorMessage()
		);
		$GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
		$GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
		echo json_encode($errorArray);
	}

	public function mail_personal_connection($uid){ 

		$result =$this->emailModel->get_personal_mail_connection($uid); 		
		$folder_type='INBOX';
		
		$ihostname = '{'.$result[0]->incoming_host.':'.$result[0]->incoming_port.'/imap/ssl}'.$folder_type; 
		$username = $result[0]->email_id; 
		$password = $this->lconnecttcommunication->decryptIt($result[0]->password);		
		$inbox = imap_open ($ihostname, $username , $password) or die("ERROR: " . imap_last_error());
		return $inbox;
	}	

	public function getUserDetails()
	{
		$data = $this->personalmail->getUserEmailDetails();
		foreach ($data as $value) {
			$this->get_personal_emails($value->user_id);
		}
	}
	public function get_personal_emails($user_id){		
		
		$GLOBALS['$log']->debug("Logged in USER id - ".$user_id);
		$inbox = $this->mail_personal_connection($user_id,'');
		$r1 = $this->emailModel->get_personal_mail_connection($user_id);
		//var_dump($inbox);exit();
		$GLOBALS['$log']->debug("personal Mail ID - ".$r1[0]->email_id);
		$mailid = $r1[0]->email_id;		
		$checkmail = $this->emailModel->check_emails($mailid,$user_id);
		$GLOBALS['$log']->debug("No of rows in Email table - ".$checkmail->num_rows());
		$data = array();
		$notification1 = array();
		$log_data = array();
		$notification2 = array();
		$log_data1 = array();

		/*$date=date ( "d M Y", strToTime ( "-10 days" ) );
										//$emails = imap_sort($inbox[$i],SORTARRIVAL ,1,SE_UID);
										$emails = imap_search($inbox, 'SINCE "'.$date.'"', SE_UID);
										foreach($emails as $iemail){

													$dt = date('ymdHis');
													$message_id = uniqid($dt);
													$msgno = imap_msgno($inbox, $iemail);
													$obj = imap_headerinfo($inbox, $msgno);
													var_dump($obj->MailDate);
													$var = date('Y-m-d H:i:s',$obj->MailDate);
													var_dump($var);

										}	

										exit();*/

		if($checkmail->num_rows()==0){
										$GLOBALS['$log']->debug("------- Entered ALL section when no data in support_group_emails table-------");
										$date=date ( "d M Y", strToTime ( "-10 days" ) );
										//$emails = imap_sort($inbox[$i],SORTARRIVAL ,1,SE_UID);
										$emails = imap_search($inbox, 'SINCE "'.$date.'"', SE_UID);

				if($emails!=false){
									$GLOBALS['$log']->debug("Found Email Object");
									rsort($emails);

					foreach($emails as $iemail){
													$dt = date('ymdHis');
													$message_id = uniqid($dt);
													$msgno = imap_msgno($inbox, $iemail);
													$obj = imap_headerinfo($inbox, $msgno);
													$GLOBALS['$log']->debug("Email object - ".json_encode($obj));
													$fromemail = $obj->from[0]->mailbox."@".$obj->from[0]->host;
													$GLOBALS['$log']->debug("Foreach Index before retrive message - ".$iemail);
													$mbody = $this->retrieve_message($inbox,$iemail);
													$attachments = $this->retrieve_attachment($inbox,$msgno);

													$toAddress = $obj->to[0]->mailbox."@".$obj->to[0]->host;
													$multipleAdress = $obj->toaddress;
													
													$ccAddress = isset($obj->ccaddress) ? $obj->ccaddress : '';

													$GLOBALS['$log']->debug("Foreach Index after retrieve message - ".$iemail);

													try{
															$check_mail = $this->emailModel->check_cust_email($fromemail);
															$check_opportunity = $this->emailModel->check_oppo_exist($fromemail);
															$check_customer = $this->emailModel->check_customer_exist($fromemail);
													}catch(LConnectApplicationException $e){
																							$this->exception($e);
													}

													$res = $check_mail->result();
													$res1 = $check_customer->result();
													$res2 = $check_opportunity->result();
							//inserting internal mail based on match						
							$Internal = ($this->emailModel->fetchInternalEmails($fromemail)) == 0 ? NULL: 'Internal';
							$state = ($this->emailModel->fetchInternalEmails($fromemail)) == 0 ? 0: 11;

							if($check_opportunity->num_rows()==0 && $check_customer->num_rows()==0){
								/*when lead contacts not exist in our system and not associated with opportunity and customer then it will be fall here*/
										switch ($check_mail->num_rows()) {										

											case '0': 	//echo 'unassociated contacts will be falls here';
														$GLOBALS['$log']->debug("lead unassociated --All section--");
														$data['type'] = $Internal;
														$data['contact_id'] = null;
														$data['lead_cust_opp_id']=null;
														$data['message_id'] = $message_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress;
														$data['mail_to_address'] = $multipleAdress;
														$data['mail_cc'] = $ccAddress;
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = $state;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'No match found';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;

														if($state == 11){				
															$notifiy = $this->emailModel->getUserNameNotify($fromemail);
															
															$dt = date('ymdHis');	
															$notification1['notificationID'] = uniqid($dt)."1";
															$notification1['notificationShortText'] = 'New Email Received';
															$notification1['notificationText'] = 'New Internal Email received from '.$notifiy[0]->name;
															$notification1['notificationTimestamp'] = date('Y-m-d H:i:s');
															$notification1['from_user'] = $user_id;
															$notification1['to_user'] = $user_id;
															$notification1['action_details'] = 'email';
															$notification1['read_state'] = 0;	
															array_push($notification2, $notification1);			
														}


											break;

											case '1': 	//echo 'lead associated contacts are falls here<br>';
														$GLOBALS['$log']->debug("lead associated --All section--");
														$data['type'] = $res[0]->contact_for;
														$data['contact_id'] = $res[0]->contact_id;
														$data['message_id'] = $message_id;
														$data['lead_cust_opp_id']=$res[0]->lead_cust_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress; 
														$data['mail_to_address'] = $multipleAdress;
														$data['mail_cc'] = $ccAddress;
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = 1;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'Match Found';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;
														$data['associated_user_id']=$user_id;

														/*Inserting notification for lead*/
										$GLOBALS['$log']->debug("insering Lead notification --All section--");

														$dt = date('ymdHis');	
														$notification1['notificationID'] = uniqid($dt)."1";
														$notification1['notificationShortText'] = 'New Email Received';
														$notification1['notificationText'] = 'New Email received from '.$res[0]->contact_name;
														$notification1['notificationTimestamp'] = date('Y-m-d H:i:s');
														$notification1['from_user'] = $user_id;
														$notification1['to_user'] = $user_id;
														$notification1['action_details'] = 'email';
														$notification1['read_state'] = 0;	
														array_push($notification2, $notification1);				
														
														/*Inserting activity for lead*/
										/*$GLOBALS['$log']->debug("insering Lead activity --All section--");

														$log_data['rep_id'] =$user_id;
														$log_data['leademployeeid'] = $res[0]->contact_id;
														$log_data['leadid'] = $res[0]->lead_cust_id;
														$log_data['logtype'] = 'EM594ce66d07b9f83';
														$log_data['call_type'] = 'complete';
														$log_data['path'] = 'no_path';
														$log_data['time'] = date('Y-m-d H:i:s');
														$log_data['starttime'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$log_data['endtime'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$log_data['type'] = $res[0]->contact_for;
														$log_data['module_id'] = $activeModule;
														$log_data['log_name'] = 'Incoming email from '.$obj->fromaddress;
														$log_data['rating'] = 4;
														$log_data['message_id'] = $message_id;
														array_push($log_data1,$log_data);*/
																								
													//var_dump($log_data);									
											break;

											default : 	//echo ' conflict <br>';
														$GLOBALS['$log']->debug("lead conflicts --All section--");
														$data['type'] = null;
														$data['contact_id'] = null;
														$data['lead_cust_opp_id']=null;
														$data['message_id'] = $message_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress; 
														$data['mail_to_address'] = $multipleAdress;
														$data['mail_cc'] = $ccAddress;
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = 2;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'lead conflicts';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;	

										}

							}else if($check_customer->num_rows()!=0 && $check_opportunity->num_rows()==0){
									/*when contact associated with customer and doesnot have opportunity then it will enter here*/
										switch ($check_customer->num_rows()) {										

											case '0': 	$GLOBALS['$log']->debug("customer unassociated --All section--");	
														$data['type'] = null;
														$data['contact_id'] = null;
														$data['lead_cust_opp_id']=null;
														$data['message_id'] = $message_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress;
														$data['mail_to_address'] = $multipleAdress; 
														$data['mail_cc'] = $ccAddress;
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = 0;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'No match found';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;																			
											break;

											case '1': 	//echo 'contacts associated with customer will falls here<br>';
														$GLOBALS['$log']->debug("customer associated --All section--");
														$data['type'] = $res1[0]->contact_for;
														$data['contact_id'] = $res1[0]->contact_id;
														$data['lead_cust_opp_id']=$res1[0]->customer_id;
														$data['message_id'] = $message_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress; 
														$data['mail_to_address'] = $multipleAdress;
														$data['mail_cc'] = $ccAddress;
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = 1;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'cutomer Match Found';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;	
														$data['associated_user_id']=$user_id;

														/*Inserting notification for customer*/
									$GLOBALS['$log']->debug("insering customer notification --All section--");
														$dt = date('ymdHis');	
														$notification1['notificationID'] = uniqid($dt)."2";
														$notification1['notificationShortText'] = 'New Email Received';
														$notification1['notificationText'] = 'New Email received from '.$res1[0]->contact_name;
														$notification1['notificationTimestamp'] = date('Y-m-d H:i:s');
														$notification1['from_user'] = $user_id;
														$notification1['to_user'] = $user_id;
														$notification1['action_details'] = 'email';
														$notification1['read_state'] = 0;	
														array_push($notification2, $notification1);					
														
												/*Inserting activity for customer*/
								/*$GLOBALS['$log']->debug("insering customer activity --All section--");
														$log_data['rep_id'] =$user_id;
														$log_data['leademployeeid'] = $res1[0]->contact_id;
														$log_data['leadid'] = $res1[0]->customer_id;
														$log_data['logtype'] = 'EM594ce66d07b9f83';
														$log_data['call_type'] = 'complete';
														$log_data['path'] = 'no_path';
														$log_data['time'] = date('Y-m-d H:i:s');
														$log_data['starttime'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$log_data['endtime'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$log_data['type'] = $res1[0]->contact_for;
														$log_data['module_id'] = $activeModule;
														$log_data['log_name'] = 'Incoming email from '.$obj->fromaddress;
														$log_data['rating'] = 4;
														$log_data['message_id'] = $message_id;	
														array_push($log_data1,$log_data);	*/

														break;

											default : 	//echo ' when two customers having same contact then it will be conflict so it will be entered here <br>';
														$GLOBALS['$log']->debug("customer conflicts --All section--");
														$data['type'] = null;
														$data['contact_id'] = null;
														$data['lead_cust_opp_id']=null;
														$data['message_id'] = $message_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress; 
														$data['mail_to_address'] = $multipleAdress;
														$data['mail_cc'] = $ccAddress;
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = 2;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'conflicts customer';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;	
											}

							}else{

										switch ($check_opportunity->num_rows()) {

											case '1':	//echo 'contacts associated with opportunity are entered here';
														$GLOBALS['$log']->debug("opportunity associated --All section--");
														$GLOBALS['$log']->debug("Associated with single opportunity");
														$data['type'] = $res2[0]->contact_for;
														$data['contact_id'] = $res2[0]->contact_id;
														$data['lead_cust_opp_id']=$res2[0]->opportunity_id;
														$data['message_id'] = $message_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress; 
														$data['mail_to_address'] = $multipleAdress;
														$data['mail_cc'] = $ccAddress;
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = 1;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'opportunity match';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;	
														$data['associated_user_id']=$user_id;

														/*Inserting notification for opportunity*/
									$GLOBALS['$log']->debug("insering opportunity notification --All section--");

														$dt = date('ymdHis');	
														$notification1['notificationID'] = uniqid($dt)."3";
														$notification1['notificationShortText'] = 'New Email Received';
														$notification1['notificationText'] = 'New Email received from '.$res2[0]->contact_name;
														$notification1['notificationTimestamp'] = date('Y-m-d H:i:s');
														$notification1['from_user'] = $user_id;
														$notification1['to_user'] = $user_id;
														$notification1['action_details'] = 'email';
														$notification1['read_state'] = 0;	
														array_push($notification2, $notification1);	
														
														/*Inserting activity for opportunity*/
									/*$GLOBALS['$log']->debug("insering opportunity activity --All section--");
														$log_data['rep_id'] =$user_id;
														$log_data['leademployeeid'] = $res2[0]->contact_id;
														$log_data['leadid'] = $res2[0]->opportunity_id;
														$log_data['logtype'] = 'EM594ce66d07b9f83';
														$log_data['call_type'] = 'complete';
														$log_data['path'] = 'no_path';
														$log_data['time'] = date('Y-m-d H:i:s');
														$log_data['starttime'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$log_data['endtime'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$log_data['type'] = $res2[0]->contact_for;
														$log_data['module_id'] = $activeModule;
														$log_data['log_name'] = 'Incoming email from '.$obj->fromaddress;
														$log_data['rating'] = 4;
														$log_data['message_id'] = $message_id;
														array_push($log_data1,$log_data);*/								
											break;

											default : 	//echo 'when two opportunity having same contact then it will be conflict so it will be entered here';
														$GLOBALS['$log']->debug("opportunity conflicts --All section--");
														$data['type'] = null;
														$data['contact_id'] = null;
														$data['lead_cust_opp_id']=null;
														$data['message_id'] = $message_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress;
														$data['mail_to_address'] = $multipleAdress; 
														$data['mail_cc'] = $ccAddress;
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = 2;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'opportunity conflicts';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;
										}													


							}
								try{
										$in = $this->emailModel->insert_emails($data);
										if($in == 1){
											$GLOBALS['$log']->debug("New email inserted");
										}
								}catch(LConnectApplicationException $e){
										$this->exception($e);
								}
								

							if(count($attachments)!=0){
								//if each mail have an attachment then it will be taken care here
									//$attachments=quoted_printable_decode($attachments);
									foreach($attachments as $at){
										if($at['is_attachment']==1){
											$directory = "./uploads/".$user_id;
											if (!is_dir($directory)) {
												mkdir($directory);
											}
											$file1 = $at['filename'];
											$filename = $at['attachment'];
											file_put_contents($directory."/".$file1, $filename);
											$data1 = array(
												'message_id' => $message_id,
												'mail_attachment_path' => $user_id."/".$file1,
												'mail_attachment_filename' => $file1
											);
											$GLOBALS['$log']->debug("Built Email attachment".json_encode($data1));											
											try{
												$in1 = $this->emailModel->insert_email_attachments($data1);
												if($in1 == 1){
													$GLOBALS['$log']->debug("New attachment added");
												}
											}catch(LConnectApplicationException $e){
												$this->exception($e);
											}
										}
									}
								//var_dump($data1);									
								}
	
								//var_dump($data);		
					}	
						try{
							//var_dump($notification2);
							$insert1 = $this->emailModel->insert_notification($notification2);

						}catch(LConnectApplicationException $e){
							$this->exception($e);
						}

						/*try{
								$ins = $this->emailModel->insert_activity($log_data1);	
								if($ins == 1){
									$GLOBALS['$log']->debug("New Activity inserted");
								}				
						}catch(LConnectApplicationException $e){
							$this->exception($e);
						}	*/						
						if(!empty(isset($_POST['user_id']))){
							$resultData['success'] = 'true';
							$resultData['data'] = 1;
							echo json_encode($resultData);
						}else{
							echo 'emails read successfull for:'.$mailid.'\r\n';							
						}

						
				}else{
						if(!empty(isset($_POST['user_id']))){
							$resultData['success'] = 'false';
							$resultData['data'] = 0;
							echo json_encode($resultData);
						}else{
							echo 0;
						}
				}	
				

		}else{	
			
						$GLOBALS['$log']->debug("------- Entered OTHER section -------");
						$result = $this->emailModel->get_latest_email($mailid,$user_id);
						$date = $result[0]->last_date;
						
						$end_mail_date = $this->emailModel->get_mail_lastdate($mailid,$user_id);
						$end_date = $end_mail_date[0]->end_date;

						$emails = imap_sort($inbox,SORTARRIVAL ,1,SE_UID, 'SINCE "'.$date.'"');	
							rsort($emails);
							
						if($emails!=false){
							
							foreach($emails as $iemail){								
								$cnt = 0;
								$msgno = imap_msgno($inbox, $iemail);
								$obj = imap_headerinfo($inbox, $msgno);									
								$dt = date('ymdHis');
								$message_id = uniqid($dt);						
								$objectdate = date('Y-m-d H:i:s',strtotime($obj->MailDate));
								$val = date('Y-m-d H:i:s',strtotime($obj->MailDate))>$end_date? 'True' : 'False';
								$GLOBALS['$log']->debug('Email Compare result Outside '.$val);
								$GLOBALS['$log']->debug("Email Server read_date Outside - ".json_encode($objectdate));
								$GLOBALS['$log']->debug("Email EndDate in DB Outside - ".$end_date);		
								$mdate = date('Y-m-d H:i:s',strtotime($obj->MailDate));				
								if($mdate > $end_date){

													$GLOBALS['$log']->debug("Email Server read_date Inside - ".json_encode($objectdate));

													$GLOBALS['$log']->debug("Email EndDate in DB Inside - ".$end_date);
													$GLOBALS['$log']->debug('Email Compare result Inside');
													$GLOBALS['$log']->debug(date('Y-m-d H:i:s',strtotime($obj->MailDate))>$end_date? 'True' : 'False');
													// $msgno = imap_msgno($inbox, $iemail);
													// $obj = imap_headerinfo($inbox, $msgno);											
													$GLOBALS['$log']->debug("Email object - ".json_encode($obj));
													$fromemail = $obj->from[0]->mailbox."@".$obj->from[0]->host;
													$GLOBALS['$log']->debug("Foreach Index before retrive message - ".$iemail);
													$mbody = $this->retrieve_message($inbox,$iemail);
													$attachments = $this->retrieve_attachment($inbox,$msgno);

													$toAddress = $obj->to[0]->mailbox."@".$obj->to[0]->host;
													$multipleAdress = $obj->toaddress;

													$ccAddress = isset($obj->ccaddress) ? $obj->ccaddress : '';
													$GLOBALS['$log']->debug("Foreach Index after retrieve message - ".$iemail);

													try{
															$check_mail = $this->emailModel->check_cust_email($fromemail);
															$check_opportunity = $this->emailModel->check_oppo_exist($fromemail);
															$check_customer = $this->emailModel->check_customer_exist($fromemail);
													}catch(LConnectApplicationException $e){
																							$this->exception($e);
													}												
													$res = $check_mail->result();
													$res1 = $check_customer->result();
													$res2 = $check_opportunity->result();

							$Internal = ($this->emailModel->fetchInternalEmails($fromemail)) == 0 ? NULL: 'Internal';
							$state = ($this->emailModel->fetchInternalEmails($fromemail)) == 0 ? 0: 11;

							if($check_opportunity->num_rows()==0 && $check_customer->num_rows()==0){
								/*when lead contacts not exist in our system and not associated with opportunity and customer then it will be fall here*/
										switch ($check_mail->num_rows()) {										

											case '0': 	//echo 'unassociated contacts will be falls here';
														$GLOBALS['$log']->debug("lead unassociated --All section--");
														$data['type'] = $Internal;
														$data['contact_id'] = null;
														$data['lead_cust_opp_id']=null;
														$data['message_id'] = $message_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress; 
														$data['mail_to_address'] = $multipleAdress;
														$data['mail_cc'] = $ccAddress; 
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = $state;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'No match found';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;

														if($state == 11){				
															$notifiy = $this->emailModel->getUserNameNotify($fromemail);
															
															$dt = date('ymdHis');	
															$notification1['notificationID'] = uniqid($dt)."1";
															$notification1['notificationShortText'] = 'New Email Received';
															$notification1['notificationText'] = 'New Internal Email received from '.$notifiy[0]->name;
															$notification1['notificationTimestamp'] = date('Y-m-d H:i:s');
															$notification1['from_user'] = $user_id;
															$notification1['to_user'] = $user_id;
															$notification1['action_details'] = 'email';
															$notification1['read_state'] = 0;	
															array_push($notification2, $notification1);			
														}

											break;

											case '1': 	//echo 'lead associated contacts are falls here<br>';
														$GLOBALS['$log']->debug("lead associated --All section--");
														$data['type'] = $res[0]->contact_for;
														$data['contact_id'] = $res[0]->contact_id;
														$data['message_id'] = $message_id;
														$data['lead_cust_opp_id']=$res[0]->lead_cust_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress; 
														$data['mail_to_address'] = $multipleAdress;
														$data['mail_cc'] = $ccAddress;
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = 1;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'Match Found';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;
														$data['associated_user_id']=$user_id;

														/*Inserting notification for lead*/
										$GLOBALS['$log']->debug("insering Lead notification --All section--");

														$dt = date('ymdHis');	
														$notification1['notificationID'] = uniqid($dt)."1";
														$notification1['notificationShortText'] = 'New Email Received';
														$notification1['notificationText'] = 'New Email received from '.$res[0]->contact_name;
														$notification1['notificationTimestamp'] = date('Y-m-d H:i:s');
														$notification1['from_user'] = $user_id;
														$notification1['to_user'] = $user_id;
														$notification1['action_details'] = 'email';
														$notification1['read_state'] = 0;	
														array_push($notification2, $notification1);				
														
														/*Inserting activity for lead*/
										/*$GLOBALS['$log']->debug("insering Lead activity --All section--");

														$log_data['rep_id'] =$user_id;
														$log_data['leademployeeid'] = $res[0]->contact_id;
														$log_data['leadid'] = $res[0]->lead_cust_id;
														$log_data['logtype'] = 'EM594ce66d07b9f83';
														$log_data['call_type'] = 'complete';
														$log_data['path'] = 'no_path';
														$log_data['time'] = date('Y-m-d H:i:s');
														$log_data['starttime'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$log_data['endtime'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$log_data['type'] = $res[0]->contact_for;
														$log_data['module_id'] = $activeModule;
														$log_data['log_name'] = 'Incoming email from '.$obj->fromaddress;
														$log_data['rating'] = 4;
														$log_data['message_id'] = $message_id;
														array_push($log_data1,$log_data);*/
																								
													//var_dump($log_data);									
											break;

											default : 	//echo ' conflict <br>';
														$GLOBALS['$log']->debug("lead conflicts --All section--");
														$data['type'] = null;
														$data['contact_id'] = null;
														$data['lead_cust_opp_id']=null;
														$data['message_id'] = $message_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress; 
														$data['mail_to_address'] = $multipleAdress;
														$data['mail_cc'] = $ccAddress;
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = 2;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'lead conflicts';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;	

										}

							}else if($check_customer->num_rows()!=0 && $check_opportunity->num_rows()==0){
									/*when contact associated with customer and doesnot have opportunity then it will enter here*/
										switch ($check_customer->num_rows()) {										

											case '0': 	$GLOBALS['$log']->debug("customer unassociated --All section--");	
														$data['type'] = null;
														$data['contact_id'] = null;
														$data['lead_cust_opp_id']=null;
														$data['message_id'] = $message_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress;
														$data['mail_to_address'] = $multipleAdress;
														$data['mail_cc'] = $ccAddress; 
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = 0;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'No match found';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;																			
											break;

											case '1': 	//echo 'contacts associated with customer will falls here<br>';
														$GLOBALS['$log']->debug("customer associated --All section--");
														$data['type'] = $res1[0]->contact_for;
														$data['contact_id'] = $res1[0]->contact_id;
														$data['lead_cust_opp_id']=$res1[0]->customer_id;
														$data['message_id'] = $message_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress;
														$data['mail_to_address'] = $multipleAdress;
														$data['mail_cc'] = $ccAddress; 
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = 1;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'cutomer Match Found';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;	
														$data['associated_user_id']=$user_id;

														/*Inserting notification for customer*/
									$GLOBALS['$log']->debug("insering customer notification --All section--");
														$dt = date('ymdHis');	
														$notification1['notificationID'] = uniqid($dt)."2";
														$notification1['notificationShortText'] = 'New Email Received';
														$notification1['notificationText'] = 'New Email received from '.$res1[0]->contact_name;
														$notification1['notificationTimestamp'] = date('Y-m-d H:i:s');
														$notification1['from_user'] = $user_id;
														$notification1['to_user'] = $user_id;
														$notification1['action_details'] = 'email';
														$notification1['read_state'] = 0;	
														array_push($notification2, $notification1);					
														
												/*Inserting activity for customer*/
								/*$GLOBALS['$log']->debug("insering customer activity --All section--");
														$log_data['rep_id'] =$user_id;
														$log_data['leademployeeid'] = $res1[0]->contact_id;
														$log_data['leadid'] = $res1[0]->customer_id;
														$log_data['logtype'] = 'EM594ce66d07b9f83';
														$log_data['call_type'] = 'complete';
														$log_data['path'] = 'no_path';
														$log_data['time'] = date('Y-m-d H:i:s');
														$log_data['starttime'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$log_data['endtime'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$log_data['type'] = $res1[0]->contact_for;
														$log_data['module_id'] = $activeModule;
														$log_data['log_name'] = 'Incoming email from '.$obj->fromaddress;
														$log_data['rating'] = 4;	
														$log_data['message_id'] = $message_id;
														array_push($log_data1,$log_data);	*/

														break;

											default : 	//echo ' when two customers having same contact then it will be conflict so it will be entered here <br>';
														$GLOBALS['$log']->debug("customer conflicts --All section--");
														$data['type'] = null;
														$data['contact_id'] = null;
														$data['lead_cust_opp_id']=null;
														$data['message_id'] = $message_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress;
														$data['mail_to_address'] = $multipleAdress;
														$data['mail_cc'] = $ccAddress; 
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = 2;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'conflicts customer';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;	
											}

							}else{

										switch ($check_opportunity->num_rows()) {

											case '1':	//echo 'contacts associated with opportunity are entered here';
														$GLOBALS['$log']->debug("opportunity associated --All section--");
														$GLOBALS['$log']->debug("Associated with single opportunity");
														$data['type'] = $res2[0]->contact_for;
														$data['contact_id'] = $res2[0]->contact_id;
														$data['lead_cust_opp_id']=$res2[0]->opportunity_id;
														$data['message_id'] = $message_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress;
														$data['mail_to_address'] = $multipleAdress;
														$data['mail_cc'] = $ccAddress; 
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = 1;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'opportunity match';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;	
														$data['associated_user_id']=$user_id;

														/*Inserting notification for opportunity*/
									$GLOBALS['$log']->debug("insering opportunity notification --All section--");

														$dt = date('ymdHis');	
														$notification1['notificationID'] = uniqid($dt)."3";
														$notification1['notificationShortText'] = 'New Email Received';
														$notification1['notificationText'] = 'New Email received from '.$res2[0]->contact_name;
														$notification1['notificationTimestamp'] = date('Y-m-d H:i:s');
														$notification1['from_user'] = $user_id;
														$notification1['to_user'] = $user_id;
														$notification1['action_details'] = 'email';
														$notification1['read_state'] = 0;	
														array_push($notification2, $notification1);	
														
														/*Inserting activity for opportunity*/
									/*$GLOBALS['$log']->debug("insering opportunity activity --All section--");
														$log_data['rep_id'] =$user_id;
														$log_data['leademployeeid'] = $res2[0]->contact_id;
														$log_data['leadid'] = $res2[0]->opportunity_id;
														$log_data['logtype'] = 'EM594ce66d07b9f83';
														$log_data['call_type'] = 'complete';
														$log_data['path'] = 'no_path';
														$log_data['time'] = date('Y-m-d H:i:s');
														$log_data['starttime'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$log_data['endtime'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$log_data['type'] = $res2[0]->contact_for;
														$log_data['module_id'] = $activeModule;
														$log_data['log_name'] = 'Incoming email from '.$obj->fromaddress;
														$log_data['rating'] = 4;
														$log_data['message_id'] = $message_id;
														array_push($log_data1,$log_data);*/								
											break;

											default : 	//echo 'when two opportunity having same contact then it will be conflict so it will be entered here';
														$GLOBALS['$log']->debug("opportunity conflicts --All section--");
														$data['type'] = null;
														$data['contact_id'] = null;
														$data['lead_cust_opp_id']=null;
														$data['message_id'] = $message_id;
														$data['mail_from'] = $fromemail;
														$data['mail_to'] = $toAddress;
														$data['mail_to_address'] = $multipleAdress;
														$data['mail_cc'] = $ccAddress; 
														$data['mail_date'] = date('Y-m-d H:i:s',strtotime($obj->MailDate));
														$data['mail_subject'] = imap_qprint($obj->Subject);
														$data['mail_body'] = $mbody;
														$data['mail_read_state'] = 0;
														$data['mail_associated_state'] = 2;
														$data['from_name'] = $obj->fromaddress;
														$data['remarks'] = 'opportunity conflicts';
														$data['user_type'] ='personalmail';
														$data['user_id']=$user_id;
										}													


							}	
									try{
											$in = $this->emailModel->insert_emails($data);
											if($in == 1){
												$GLOBALS['$log']->debug("New email inserted");
											}
									}catch(LConnectApplicationException $e){
											$this->exception($e);
									}
								

									if(count($attachments)!=0){
										//if each mail have an attachment then it will be taken care here
										//$attachments=quoted_printable_decode($attachments);
										foreach($attachments as $at){
											if($at['is_attachment']==1){
												$directory = "./uploads/".$user_id;
													if (!is_dir($directory)) {
														mkdir($directory);
													}
													$file1 = $at['filename'];
													$filename = $at['attachment'];
													file_put_contents($directory."/".$file1, $filename);
													$data1 = array(
														'message_id' => $message_id,
														'mail_attachment_path' => $user_id."/".$file1,
														'mail_attachment_filename' => $file1
													);
													$GLOBALS['$log']->debug("Built Email attachment".json_encode($data1));											
													try{
														$in1 = $this->emailModel->insert_email_attachments($data1);
														if($in1 == 1){
															$GLOBALS['$log']->debug("New attachment added");
														}
													}catch(LConnectApplicationException $e){
														$this->exception($e);
													}
											}
										}
										//var_dump($data1);									
									}
									try{
										//var_dump($notification2);
										$insert1 = $this->emailModel->insert_notification($notification2);

									}catch(LConnectApplicationException $e){
										$this->exception($e);
									}

									/*try{
											$ins = $this->emailModel->insert_activity($log_data1);	
											if($ins == 1){
												$GLOBALS['$log']->debug("New Activity inserted");
											}				
									}catch(LConnectApplicationException $e){
										$this->exception($e);
									}		*/
	
								
								}//end compare if
								

							}
								if(!empty(isset($_POST['user_id']))){
										$resultData['success'] = 'true';
										$resultData['data'] = 1;
										echo json_encode($resultData);
								}else{
										echo 'emails read successfull for:'.$mailid.'\r\n';	
								}
								

						}else{
								if(!empty(isset($_POST['user_id']))){
										$resultData['success'] = 'false';
										$resultData['data'] = 0;
										echo json_encode($resultData);
								}else{
										echo 'emails read unsuccessfull for:'.$mailid.'\r\n';
								}

						}		
					
			
		}	
		
		imap_close($inbox);
	}	
	

	public function retrieve_message($mbox, $messageid){
		$GLOBALS['$log']->debug("Retrive message id - ".$messageid);
	   return $this->getBody($messageid,$mbox);
	}

	public function getBody($uid, $imap) {
		$GLOBALS['$log']->debug("getBody UID - ".$uid);
		$body = $this->get_part($imap, $uid, "TEXT/HTML");
	    if ($body == "") {
			 $body = $this->get_part($imap, $uid, "TEXT/PLAIN"); 
	    }
	    return $body;
	}

	public function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false){
	    if (!$structure) {
				$GLOBALS['$log']->debug("get_part UID - ".$uid);
	           $structure = imap_fetchstructure($imap, $uid,FT_UID);
	    }
	    if ($structure) {
			if ($mimetype == $this->get_mime_type($structure)) {
	            if (!$partNumber) {
	                $partNumber = 1;
	            }
	            $text = imap_fetchbody($imap, $uid, $partNumber,FT_UID);
	            switch ($structure->encoding) {
	                case 3: return imap_base64($text);
	                case 4: return imap_qprint($text);
	                default: return $text;
	           }
	       }

	        if ($structure->type == 1) {
	            foreach ($structure->parts as $index => $subStruct) {
	                $prefix = "";
	                if ($partNumber) {
	                    $prefix = $partNumber . ".";
	                }
					$data = $this->get_part($imap, $uid, $mimetype, $subStruct, $prefix.($index + 1));
	                if ($data) {
	                    return $data;
	                }
	            }
	        }
	    }
	    return false;
	}

	public function get_mime_type($structure) {
		$primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION","AUDIO", "IMAGE", "VIDEO", "OTHER");

		if ($structure->subtype) {
		   return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
		}
		return "TEXT/PLAIN";
	} 

	public function retrieve_attachment($mbox, $messageid){
		$GLOBALS['$log']->debug("Retrive attachment message id - ".$messageid);
		$structure = imap_fetchstructure($mbox,$messageid);
		
		$attachments = array();
		if(isset($structure->parts) && count($structure->parts)) {
			for($i = 0; $i < count($structure->parts); $i++) {
				$attachments[$i] = array(
						'is_attachment' => false,
						'filename' => '',
						'name' => '',
						'attachment' => '');
		
				if($structure->parts[$i]->ifdparameters) {
					foreach($structure->parts[$i]->dparameters as $object) {
						if(strtolower($object->attribute) == 'filename') {
							$attachments[$i]['is_attachment'] = true;
							$attachments[$i]['filename'] = $object->value;
						}
					}
				}
		
				if($structure->parts[$i]->ifparameters) {
					foreach($structure->parts[$i]->parameters as $object) {
						if(strtolower($object->attribute) == 'name') {
							$attachments[$i]['is_attachment'] = true;
							$attachments[$i]['name'] = $object->value;
						}
					}
				}
		
				if($attachments[$i]['is_attachment']) {
					$attachments[$i]['attachment'] = imap_fetchbody($mbox, $messageid, $i+1);
					if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
						$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
					}
					elseif($structure->parts[$i]->encoding == 4) { // 4 = UOTQED-PRINTABLE
						$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
					}
				}
			} 
		}
		return $attachments;
	}

	public function validateEmailsAPI(){
		
		$resultData = array();

		$dt = date('ymdHis');

		$res = $this->editprofile->get_email_settings($_POST['incoming_host']);

		if($res->num_rows()>0){
			$result = $res->result();
			$login_email=$_POST['login_email'];
			$login_name=$_POST['login_name'];
			$login_password=$_POST['login_password'];
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
					$resultData['success'] = 'true';
					$resultData['data'] = 'Sent';
					echo json_encode($resultData);

			}else{
			  	$resultData['success'] = 'false';
				$resultData['data'] = 'Not sent';
				echo json_encode($resultData);

			}
		}else{
				$resultData['success'] = 'false';
				$resultData['data'] = 'Not Found';
				echo json_encode($resultData);
		}
    }

    public function saveUserEmailsDetailsAPI(){
    	$resultData = array();
		$userid = $_POST['user_id'];//$_POST['user_id']; 
		$json = file_get_contents("php://input");
		$data = json_decode($json);
		//$login_password = $this->lconnecttcommunication->encryptIt($data->login_password);

		$login_email=$_POST['login_email'];
		$login_name=$_POST['login_name'];
		$login_password=$_POST['login_password'];

		$dt = date('ymdHis');
		$settings_id = uniqid($dt);

		$res = $this->editprofile->get_email_settings($_POST['incoming_host']);

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
				'user_email_settings_id' => $settings_id
				);
				$insert = $this->editprofile->save_user_email_settings($email_data);
				echo json_encode($insert);
				$resultData['success'] = 'true';
				$resultData['data'] = $insert;
				echo json_encode($resultData);
		}else{
				$resultData['success'] = 'false';
				$resultData['data'] = 'Not Found';
				echo json_encode($resultData);
		}
	} 

	public function getMailsAPI() {
            	$resultData = array();
         
                $get_data = $this->personalmail->get_data($_POST['tabtype']);

                $domain = $this->personalmail->get_domain($_POST['user_id']);  
                $dom = array();
                if(!empty($domain)){
                    foreach ($domain as $domain => $value) {
                       array_push($dom, $value->email_id);
                    }
                }     
                $arr = array('mailData'=>$get_data,'domain'=>$dom);               
				$resultData['success'] = 'true';
				$resultData['data'] = $arr;
				echo json_encode($resultData);               
				
	}

	 public function getMatchdataAPI() {
	 				//echo $_POST['user_id'];exit();
	 				$resultData = array();
                    if($_POST['matchdata']=='unassoc'){                        
                        $search_value=$_POST['searchValue'];
                        $nametype = '';
                        $get_matchdata = $this->personalmail->get_matchdata($nametype,$search_value,$_POST['matchdata']);
                        if(count($get_matchdata)>0){
                        	$resultData['success'] = 'true';
                        	$resultData['data'] = $get_matchdata;
                        	echo json_encode($resultData);	
                        }else{
                        	$resultData['success'] = 'false';
                        	$resultData['data'] = 'No Match Found';
                        	echo json_encode($resultData);
                        }
                        
                    }else{
                        $search_value= $_POST['searchValue'];
                        $msgid='';                       
                        $get_matchdata = $this->personalmail->get_matchdata($msgid,$search_value,$_POST['matchdata']);
                        if(count($get_matchdata)>0){
                        	$resultData['success'] = 'true';
                        	$resultData['data'] = $get_matchdata;
                        	echo json_encode($resultData);	
                        }else{
                        	$resultData['success'] = 'false';
                        	$resultData['data'] = 'No Match Found';
                        	echo json_encode($resultData);
                        }
                    }
    }
    
    public function getUserEmailSettings(){
    	$resultData =  array();
        $result = $this->editprofile->get_user_email_settings($_POST['user_id']);
        if(count($result)>0){
        	$resultData['success'] = 'true';
        	$resultData['data'] = $result;
        	echo json_encode($resultData);	
        }else{
        	$resultData['success'] = 'false';
        	$resultData['data'] = $result;
        	echo json_encode($resultData);
        }
        
    }      

	public function get_mailid(){
		$res = $this->emailModel->check_cust_email('kumarg781991@gmail.com');
		var_dump($res->result());
	}
	// Function to Auto populate , 

	public function userEmailData()
	{
		
		$data = $this->emailModel->userEmail($this->session->userdata('uid'));
		echo json_encode($data);

	}

}