<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_leadController');

class manager_leadController extends Master_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('manager_lead_model','manager');
		$this->load->model('leadinfo_model','lead');
		$this->load->library('lconnecttcommunication');
		$this->load->library('pushNotification');
		$this->load->model('manager_mytaskmodel','mytask');
	//	$this->load->library('usertracking'); 
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
			$GLOBALS['$log']->debug("loading manager_leadController");
			$this->load->view('manager_lead_info');  
		}
		else{
				redirect('indexController');
			}
				
	}
	public function assignedLeads_view(){
		if($this->session->userdata('uid')){
			try{
				$this->load->view('manager_assignedLeadsView');
				} catch (LConnectApplicationException $e)  {
					echo $this->exceptionThrower($e);
				}				
		}else{
			redirect('indexController');
		}
	}

	public function manager_closed_lead_Won(){
		if($this->session->userdata('uid')){
			try{
				$this->load->view('manager_closed_leads_won_view');	
			}			
			catch (LConnectApplicationException $e)  {
					echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	public function manager_closed_lead_Lost(){
		if($this->session->userdata('uid')){
			try{
				$this->load->view('manager_closed_leads_lost_view');
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	public function lead_status(){
		if($this->session->userdata('uid')){
			try{
				$this->load->view('manager_leadstates');
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	public function get_data(){
            if($this->session->userdata('uid')){
		try{
					$GLOBALS['$logger']->debug('get_data from excel_upload');
					$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));
                    $userid=$this->session->userdata('uid');
                    $json = file_get_contents("php://input");
                    $data = json_decode($json,TRUE);
                    $lead_data=$data['lead'];
                    $lead_source=$data['source'];
                    $country = $this->manager->country();
                    $state = $this->manager->get_state();
                    $lead_details = $this->manager->get_leads();
                    $date =  gmdate(date('Y-m-d H:i:s'));
                     $newDate = date("Y-m-d H:i:s");
                    $selected_leads=array();
                    $lead_info=array();
                    $contact_info=array();
                    $transaction=array();
                     $mapping_id = uniqid(rand(),TRUE); 
                    $contact_number;
                    for ($i=0;$i<count($lead_data);$i++){
                        $dt = date('ymdHis');
                        $leadid = $dt;
                        $lead_id = $leadid.uniqid();
                        $contact_id=uniqid($leadid);
                        $leads = array();
                        $leads['lead_id'] =null;
                        $leads['lead_name']=null;
                        $leads['lead_website']=null;
                        $leads['lead_source']=null;
                        $leads['lead_address']=null;
                        $leads['lead_country']=null;
                        $leads['lead_state']=null;
                        $leads['lead_city']=null;
                        $leads['lead_zip']=null;
                        $leads['lead_city']=null;
                        $leads['lead_remarks']=null;
                        $leadContact['phone'] = [""];
                        $leads['lead_number'] = json_encode($leadContact);
                        $contact = array();
                        $contact['contact_id']=null;
                        $contact['contact_name']=null;
                        $contact['contact_desg']=null;
                        $contact['contact_desg']=null;
                        $contact['contact_number']='';
                        $contact['contact_email']=null;
						$leads['lead_industry']=$data['industry'];
						$leads['lead_business_loc']=$data['bussiness'];
                        if(isset($lead_data[$i]['Lead Name*^']) && isset($lead_data[$i]['Lead Contact Name*^'])&& isset($lead_data[$i]['Lead Phone Number 1*'])){
                            $status = true;   
                            foreach ($lead_details as $key=> $val){
                                $lead_name= $val->leadname;
                                if($lead_name== strtolower($lead_data[$i]['Lead Name*^'])){
                                    $status = false;
                                    break;
                                }
                            }
                            if($status==true){
                                $cont_num = array();
                                $cont_email= array();
                                foreach ($lead_data[$i] as $k=>$v){
                                        switch ($k){
                                            case 'Lead_id': $leads['lead_id'] = $lead_id;
                                                    $contact['lead_cust_id'] = $lead_id;
                                                    break;
                                            case 'Contact_id':$contact['contact_id'] = $contact_id;
                                                    break;
                                            case 'Lead Name*^': $leads['lead_name'] = $v;
                                                    break;
                                            case 'Lead Company Website': $leads['lead_website'] = $v;
                                                    break;
                                            case 'Lead Contact Name*^': $contact['contact_name'] = $v;
                                                    break;
                                            case 'Lead Contact Designation': $contact['contact_desg'] = $v;
                                                    break;
                                            case 'Lead Phone Number 1*': $cont_num['phone'][0] = $v;
                                                    $contact_number=$cont_num['phone'][0];
                                                    break;
                                            case 'Lead Phone Number 2':$cont_num['phone'][1] = $v;
                                                    break;
                                            case 'Lead E-mail ID 1': $cont_email['email'][0] = $v;
                                                    break;
                                            case 'Lead E-mail ID 2': $cont_email['email'][1] = $v;
                                                    break;
                                            case 'Address': $leads['lead_address'] = $v;
                                                    break;
                                            case 'Country':foreach ($country as $key=> $val){
                                                $country_val= $val->country_name;
                                                    if($country_val== strtolower($v)){
                                                            $leads['lead_country']=$val->lookup_id;
                                                    }
                                                }
                                                    break;
                                            case 'State':foreach ($state as $key=> $val){
                                                $state_val= $val->state_name;
                                                if($state_val== strtolower($v)){
                                                        $leads['lead_state']=$val->state_id;
                                                }
                                            }
                                                    break;
                                            case 'City': $leads['lead_city'] = $v;
                                                    break;
                                            case 'Zipcode': $leads['lead_zip'] = $v;
                                                    break;
                                            case 'Special Comments': $leads['lead_remarks'] = $v;
                                                    break;
                                        }
                                } 
                                $contact['contact_number'] = json_encode($cont_num);
                                $contact['contact_email'] = json_encode($cont_email);
                                $contact['contact_created_time'] =$date;
                                $contact['contact_created_by'] =$userid;
                                $contact['contact_for'] ='lead';
                                $leads['lead_created_by'] =$userid;
                                $leads['lead_created_time'] =$date;
                                $leads['lead_manager_owner'] =$userid;
                                $leads['lead_manager_status'] =2;
                                $leads['lead_status'] =0;
                                $leads['contact_number'] =$contact_number;
                                $leads['lead_source']=$lead_source;
                                $mapping_id = uniqid(rand(),TRUE);
                                 $data5 = array('lead_cust_id' =>$lead_id,'type'=>'lead','state' =>'1','action'=>"created",'module'=>"manager",
                                'from_user_id'=>$userid, 'to_user_id'=>$userid,'timestamp'=>$newDate,'mapping_id'=>$mapping_id,);
                                array_push( $transaction,$data5);
                                array_push( $lead_info,$leads);
                                array_push( $contact_info,$contact);
                                array_push( $selected_leads,$leads['lead_name']);
                            }	
                        }
                    }
                    if(count($lead_info)>0){
                        $insert = $this->manager->insert_details($lead_info,$contact_info,$transaction);
                        if($insert==TRUE ){
                            echo count($selected_leads);
                        }
                    }else{
                        echo count($selected_leads);
                    }
		}catch (LConnectApplicationException $e){
                      echo $this->exceptionThrower($e);
		}
            }else{
                    redirect('indexController');
            }
	}
	public function fetch(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch unassigned_leads from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));
				$user=$this->session->userdata('uid');
				//$this->usertracking->track_this();   
				$data = $this->manager->fetch_leads($user);        
				$data_fetch=array('data'=>$data, 'user_check'=>$user);
				echo json_encode($data_fetch);
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($data_fetch);
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
				redirect('indexController');
		}
	}


	public function fetch_assigned(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch assigned_leads from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$user=$this->session->userdata('uid');
				$data=$this->manager->fetch_assignleads($user);
				$data1=$this->manager->getReportingPersons($user);         			      
				$assignedData = array('data' =>$data ,'user_check'=>$user);
				echo json_encode($assignedData);				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($assignedData);
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}
		else{
			redirect('indexController');
		}
	}
	public function fetch_assigned_others(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch fetch_assigned_team_leads from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$user=$this->session->userdata('uid');
				$data=$this->manager->fetch_assignleads_others($user);
				$data1=$this->manager->getReportingPersons($user);            
				$assignedData = array('data' =>$data ,'user_check'=>$user);
				echo json_encode($assignedData);			
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($assignedData);
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}
		else{
			redirect('indexController');
		}
	}

	public function fetch_won_leads(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch fetch_won_leads from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));	
				$user=$this->session->userdata('uid');
				$data=$this->manager->fetch_leads_won($user);           
				$assignedData = array('data' =>$data);
				echo json_encode($assignedData);				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($assignedData);	
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
				redirect('indexController');
		}
	}

	public function fetch_lost_leads(){
		if($this->session->userdata('uid')){
			try {
				$GLOBALS['$logger']->debug('fetch fetch_lost_leads from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$user=$this->session->userdata('uid');
				$data=$this->manager->fetch_leads_lost($user);           
				$assignedData = array('data' =>$data);
				echo json_encode($assignedData);				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($assignedData);	
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	public function fetch_leadhistory(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch fetch_leadhistory from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$json = file_get_contents("php://input");
				$data1 = json_decode($json);
				$id=$data1->id;
				$data=$this->manager->lead_history($id);
				$data1=$this->manager->lead_historydetails($id);				
                $data3= array ('history'=>$data,'history_detail'=>$data1);
				echo json_encode($data3);      				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($data3);	
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
				redirect('indexController');
		}
	
	}

	public function fetch_leadlog(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch fetch_leadlog from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$json = file_get_contents("php://input");
				$data1 = json_decode($json);
				$id=$data1->id;
				$data=$this->manager->fetch_logs($id);
				echo json_encode($data);				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($data);	
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}
	public function fetch_opportunity(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch fetch_opportunity from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$json = file_get_contents("php://input");
				$data1 = json_decode($json);
				$id=$data1->id;
				$data=$this->manager->opportunity_list($id);
				echo json_encode($data);				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($data);	
			}catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
				redirect('indexController');
		}
	}
	public function fetch_unAssignedLog(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch fetch_unAssignedLog from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$json = file_get_contents("php://input");
				$data1 = json_decode($json);
				$id=$data1->id;
				$data=$this->manager->logs_unassigned($id);
				echo json_encode($data);				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($data);	
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
			}else{
					redirect('indexController');
			}
	}
	public function fetch_received_lead(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch fetch_received_leads from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$user=$this->session->userdata('uid');
				$data=$this->manager->fetch_receivedlead($user);
				echo json_encode($data);   				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($data);	   
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	public function logs_schedule(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch fetch_logs_schedule from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$json = file_get_contents("php://input");
				$data1 = json_decode($json);
				$id=$data1->id;
				$data=$this->manager->logs_scheduleactivity($id);
				echo json_encode($data);				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($data);	
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
				redirect('indexController');
		}
	}
		

	public function get_rep_info(){       
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch get_rep_info from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$rep_info = $this->manager->view_data();
				echo json_encode($rep_info);				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($rep_info);	
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
				redirect('indexController');
		}
	}
		
		
	public function get_rep_data(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch get_rep_data from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));			
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$rep_id=$data->rep_id;
				$rep_info = $this->manager->rep_data($rep_id);
				echo json_encode($rep_info);			
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($rep_info);	
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	public function get_managerlist(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch get_managerlist from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));			
				$user= $this->session->userdata('uid');
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$leads=$data->leads;
				$lead1=array();
					foreach ($leads as $lead){
						array_push($lead1, $lead);         
					}  
				$mgr=$this->manager->getrep_products($lead1,$user);  
				echo json_encode($mgr);      				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($mgr);	
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	public function get_managerlist_reassign(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch get_managerlist_reassign from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$user= $this->session->userdata('uid');
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$leads=$data->leads;
				$lead1=array();
					foreach ($leads as $lead){
						array_push($lead1, $lead);         
					}  
				$mgr=$this->manager->getrep_products($lead1,$user);                 
				echo json_encode($mgr); 				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($mgr);	     
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
				redirect('indexController');
		}
	}

	/* reopen_lead_reassign_function
	public function get_managerlist_reassign1(){
		if($this->session->userdata('uid')){
			try{
				$user= $this->session->userdata('uid');
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$leads=$data->leads;			 
				$lead1=array();
					foreach ($leads as $lead){
						array_push($lead1, $lead);         
					}        
				$mgr=$this->manager->getrep_products($lead1,$user);                 
				echo json_encode($mgr);      
			} catch (LConnectApplicationException $e)  {
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
	}*/

	public function get_managerlist_reassign1(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch get_managerlist_reassign1 from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$user= $this->session->userdata('uid');
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$leads=$data;	

				//var_dump($leads); exit();		 
				$lead1=array();
				$lead2=array();
				$lead3=array();
				$lead4=array();
				$lead5= array();
				$notifications_data= array();
				$notify_id= uniqid(date('ymdHis'));
			
					foreach ($leads as $lead){	

					$lead_data      = $this->lead->get_lead_data($lead->leadId);
                    $name           = $lead_data['lead_data'][0]->lead_name;
					$mapping_id = uniqid(rand(),TRUE);

					//updating lead_info table for reopening a lead, updating lead_closed_reason and lead_status
					$data1=array('lead_id'=>$lead->leadId,
						'lead_closed_reason'=>NULL,
						'lead_status'=>1);

					//updating lead_cust_user_map table and resting previous state columns to 0 for selected lead
					$data2=array('lead_cust_id'=>$lead->leadId,
								'state'=>0); 

					// inserting reopening lead into lead_cust_user_map
					$data3=array('lead_cust_id'=>$lead->leadId,
								'state'=>1,
								'action'=>'reopened',
								'from_user_id'=>$user,
								'to_user_id'=>$user,								
								'type'=>'lead',
								'mapping_id'=>$mapping_id,
								'module'=>'manager',
								'timestamp'=>date('Y-m-d H:i:s'));

					if($lead->assign == true && $lead->rowner!=""){
						$repArr =array('lead_cust_id'=>$lead->leadId,
						'state'=>1,
						'action'=>'accepted',
						'from_user_id'=>$lead->rowner,
						'to_user_id'=>$lead->rowner,								
						'type'=>'lead',
						'mapping_id'=>$mapping_id.rand(),
						'module'=>'sales',
						'timestamp'=>date('Y-m-d H:i:s'));  
						array_push($lead4, $repArr);

						$notifications1 = array(
						'notificationID' =>$notify_id.rand(),
						'notificationShortText'=> 'Lead Reopened',
						'notificationText' => $name.' lead reopened by '.$this->session->userdata('uname'),
						'from_user'=>$user,
						'to_user'=>$lead->rowner,
						'action_details'=>'lead',
						'notificationTimestamp'=>date('ymdHis'),
						'read_state'=>0,
						'remarks'=>$lead->remarks,
						);
						array_push($notifications_data, $notifications1);

					}else{
						$leadTableUpdate = array(/*'lead_id'=>$lead->leadId,
												'lead_status'=>0,
												'lead_rep_status'=>0,
												'lead_manager_status'=>2,*/
												'lead_id'=>$lead->leadId,
												'lead_rep_owner'=>null);
						array_push($lead5, $leadTableUpdate);
					}

						// Notifications  Data                        

                        $notifications =        array(
                                                    'notificationID' =>$notify_id,
                                                    'notificationShortText'=> 'Lead Reopened',
                                                    'notificationText' => $name.' lead reopened by '.$this->session->userdata('uname'),
                                                    'from_user'=>$user,
                                                    'to_user'=>$user,
                                                    'action_details'=>'lead',
                                                    'notificationTimestamp'=>date('ymdHis'),
                                                    'read_state'=>0,
                                                    'remarks'=>$lead->remarks,
                                                );


						// pushing data into empty array
						array_push($lead1,$data1);    
						array_push($lead2, $data2);	
						array_push($lead3, $data3);								
						array_push($notifications_data, $notifications); 				   
					}   
					//passing array into lead_reopen function
					$mgr=$this->manager->lead_reopen($lead1,$lead2,$lead3,$lead4,$lead5);  

					//inserting notification for reopening an lead
					$insert_notifications = $this->lead->insertNotificationData($notifications_data);               
					echo json_encode($mgr);  				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($mgr);	  
			}
			catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}		

	}


	public function get_replist1(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch get_replist1 from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$user= $this->session->userdata('uid');
				$rep=$this->manager->get_listof_rep($id);    
				echo json_encode($rep);  				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($rep);	    
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	public function get_replist(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch get_replist from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));			
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$mgr_id=explode(":", $data->mgr_id);
				$reps=array();
					for($i=0;$i<count($mgr_id);$i++) {     
						$repid = $this->manager->rep_list($mgr_id[$i]);     
					 		for($j=0;$j<count($repid);$j++){
								array_push($reps, $repid[$j]);            
					 		}
					}          
				echo json_encode($reps);  				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($reps);	                   
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
				redirect('indexController');
		}
	}

	public function getIndustry(){ 
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch get_industry from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$user=$this->session->userdata('uid');
				$industry = $this->manager->industry($user);
				echo json_encode($industry);  				
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($industry);	
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	public function getLocation(){ 
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('fetch getLocation from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$user= $this->session->userdata('uid');
				$location = $this->manager->location($user);
				echo json_encode($location);			
				$GLOBALS['$logger']->debug('response received successfully');
				$GLOBALS['$logger']->debug($location);	
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}
	
	public function sendemails(){
			if($this->session->userdata('uid')){
				$json = file_get_contents("php://input");
				$data = json_decode($json);

				$leadid=explode(":", $data->lid);
				$arr=array();
				for($i=0;$i<count($leadid);$i++) {
						$leadname=$this->manager->leadretrieve($leadid[$i]);            
						$obj['leadid']=$leadid[$i];
						$obj['leadname']=$leadname;
						array_push($arr, $obj);
				}
				// print_r($arr);
				$from_email =  $data->memail;; 
				$to_email = $data->email;
				// echo $from_email;
				// echo $to_email;
				//Load email library 
				echo $data->manager;
				$this->load->library('email');       
				$this->email->from($from_email, $data->manager); 
				$this->email->to($to_email);
				$this->email->subject('lead assignment for sales representatives'); 
				$this->email->message('these leads are assigned to you'); 

				//Send mail 
				if($this->email->send()) 
				$this->session->set_flashdata("email_sent","Email sent successfully."); 
				else 
				$this->session->set_flashdata("email_sent","Error in sending Email."); 
			}        //$this->load->view('email_form'); 
			else{
						redirect('indexController');
			}
		}


	public function sendmail_reassign(){
		if($this->session->userdata('uid')){
			try{
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$leadid=explode(":", $data->lid);
				$arr=array();
					for($i=0;$i<count($leadid);$i++){
						$leadname=$this->manager->leadretrieve($leadid[$i]);            
						$obj['leadid']=$leadid[$i];
						$obj['leadname']=$leadname;
						array_push($arr, $obj);
					}        
				$from_email =  $data->memail;; 
				$to_email = $data->email;        
				echo $data->manager;
				$this->load->library('email');       
				$this->email->from($from_email, $data->manager); 
				$this->email->to($to_email);
				$this->email->subject('lead assignment for sales representatives'); 
				$this->email->message('these leads are reassigned to you'); 
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
			}
	}

			
	public function post_id(){ 
		/*dev_remarks----------------------------------*/
		/*when assigning_lead to manager update lead_manager_status is 1 */
		/*when assigning_lead to sales update lead_rep_status as 1 */
		/*when assigning_lead to sales and manager update both lead_manager_status and lead_rep_status as 1 */
		
		if($this->session->userdata('uid')){ 
			try{
				$GLOBALS['$logger']->debug('assigned data from manager to rep/manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$user = $this->session->userdata('uid');
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$leads = $data->leads;
				$mapping_id = uniqid(rand(),TRUE);
				$userPackets = $data->users;
				$insertArray = array();
				$status_arr= array();
				$userList = array();
				$lName = "";
				$notificationDataArray = array();
				foreach ($leads as $lead) {
					foreach ($userPackets as $packet) {						
						$data=array(
							'mapping_id'=>$mapping_id,
							'lead_cust_id' => $lead,
							'to_user_id' => $packet->to_user_id,
							'from_user_id'=>$user,
							'module'=>$packet->module,
							'type'=>'lead',
							'state'=>1,
							'action'=>'assigned',
							'timestamp'=>date('Y-m-d H:i:s')
						);
						array_push($insertArray, $data);
						if($packet->module=='manager'){
							$data1=array('lead_manager_status'=>1,
							'lead_id'=>$lead);
						}else{
							$data1=array('lead_rep_status'=>1,
							'lead_id'=>$lead);
						}
						array_push($status_arr,$data1);

						$message = array(
				            'title' => 'Lead Assigned',
				            'message' => 'Digiconnectt',
				            'subtitle' => '',
				            'tickerText' => '',
				            'msgcnt' => 1,
				            'vibrate' => 1,
				            'sound'		=> 1
				        );
					}					
				}
								
					$insert=$this->manager->rep_assign($insertArray);			
					$GLOBALS['$logger']->debug('response received successfully');
					$GLOBALS['$logger']->debug($insert);	
					$update=$this->manager->lead_status_assigned($status_arr);					
					$GLOBALS['$logger']->debug('response received successfully');
					$GLOBALS['$logger']->debug($update);
					$notificationDataArray = array();

						foreach ($leads as $lead) {
							$leadName =  $this->manager->getLeadName($lead);                   
							$lName = $leadName[0]->lead_name;
							$getUserName = $this->manager->fetchUserName($user);
							foreach ($userPackets as $packet) {
								$dt = date('ymdHis');
								$notify_id= uniqid($dt);
								$notificationData= array(
								'notificationID' =>$notify_id,
								'notificationShortText'=>'Lead Assigned',
								'notificationText' =>'Lead '.$lName.' has been assigned to you from '.($getUserName[0]->user_name).'.',
								'from_user'=>"$user",
								'to_user'=>$packet->to_user_id,
								'action_details'=>'lead',
								'notificationTimestamp'=>$dt,
								'read_state'=>0,
								'remarks'=>'assigned',
								'task_id'=>$lead
								);
								array_push($userList, $packet->to_user_id);
								array_push($notificationDataArray,$notificationData); 
							}
						} 
						
						//batch inserting . 

						$notificationsInsert = $this->manager->insertNotificationData($notificationDataArray);

						$users = $userList;                 
						$msgbody = 'Lead '.$lName.' has been assigned';
						$subject =  'Lead Assigned';		
						$GLOBALS['$logger']->debug('send_mail to the users from manager');
						$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));							
						$email = $this->lconnecttcommunication->send_email($users,$subject,$msgbody);  
						$GLOBALS['$logger']->debug('response received successfully');
						$GLOBALS['$logger']->debug($email);	 				
						echo $insert;		
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}	
		}else {
				redirect('indexController');
		}
		
	} 

	public function reassign(){ 
		if($this->session->userdata('uid')){  
			try{
				$GLOBALS['$logger']->debug('reassign_leads from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$user = $this->session->userdata('uid');
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$leads = $data->leads;
				$leadids = '';
				$notificationDataArray = array();
				$userPackets = $data->users;
				foreach ($leads as $lead) {
					$leadids .=$lead."','";
				}
				$leadNamesArray = $this->manager->getLeadName($leadids); 
				for ($i=0; $i <count($leadNamesArray) ; $i++) { 
						foreach ($userPackets as $packet) {
						$dt = date('ymdHis');
						$notify_id= uniqid($dt);
						$notificationData= array(
						'notificationID' =>$notify_id,
						'notificationShortText'=>'Lead Reassigned',
						'notificationText' =>'Lead '.$leadNamesArray[$i]->lead_name.' has been reassigned to you from '.($this->session->userdata('uname')).'.',
						'from_user'=>"$user",
						'to_user'=>$packet->to_user_id,
						'action_details'=>'lead',
						'notificationTimestamp'=>$dt,
						'read_state'=>0,
						'remarks'=>'assigned',
						);
						array_push($notificationDataArray,$notificationData);								
						}
				}
				$mapping_id = uniqid(rand(),TRUE);
				$insertArray = array();
				$insertrepowner=array();
				$lead_state=array();				
				$rep_activity=array();
				$sales='';
				$manager='';
				$manager_activity=array();
					foreach ($leads as $lead) {
						foreach ($userPackets as $packet) {
							$data=array(
							'mapping_id' => $mapping_id,
							'lead_cust_id' => $lead,
							'to_user_id' => $packet->to_user_id,
							'from_user_id'=>$user,
							'module'=>$packet->module,
							'type'=>'lead',
							'state'=>1,
							'action'=>'reassigned',
							'timestamp'=>date('Y-m-d H:i:s')
							);							
							array_push($insertArray, $data);
						if($packet->module=='manager'){
							$data1=array('lead_manager_status'=>1,
							'lead_id'=>$lead,
							'lead_manager_owner'=>$user);
							/*$data2=array('state'=>0,
								'lead_cust_id'=>$lead);		*/	
							$manager='manager';								
							array_push($lead_state,$lead);												
							array_push($manager_activity, $lead);
							
						}else{
							$data1=array('lead_rep_status'=>1,
							'lead_id'=>$lead,
							'lead_rep_owner'=>null);
							/*$data2=array('state'=>0,
								'lead_cust_id'=>$lead);*/
							$sales='sales';								
							array_push($lead_state,$lead);												
							array_push($rep_activity, $lead);
						}					
					
						array_push($insertrepowner,$data1);									
						         
						}
					}
					if($manager!=''){
							$reassign_zero=$this->manager->reassign_state_zero($lead_state,$manager);
					}else{
						$reassign_zero=$this->manager->reassign_state_zero($lead_state,$sales);
					}							
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($reassign_zero);
				$update_lead_reminder=$this->manager->update_reminder_table($manager_activity,$rep_activity);			
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($update_lead_reminder);
				$insert=$this->manager->rep_assign($insertArray);							
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($insert);				
				$update=$this->manager->rep_owner_update($insertrepowner);				
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($update);
				$userList = array();               
                $lName = "";
 				$notificationsInsert = $this->manager->insertNotificationData($notificationDataArray);
                  $users = $userList;                 
                  $msgbody = 'Lead '.$lName.' has been Reassigned';
                  $subject =  'Lead Reassigned';               
                $email = $this->lconnecttcommunication->send_email($users,$subject,$msgbody);                
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($email);
                echo json_encode($update);			
			
		}catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
		}				
		}else {
			 redirect('indexController');
			}
	} 

	public function reassign_lost(){ 
		if($this->session->userdata('uid')){  
			try{
				$GLOBALS['$logger']->debug('reassign_lost_leads from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$user = $this->session->userdata('uid');
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$leads = $data->leads;
				$mapping_id = uniqid(rand(),TRUE);
				$userPackets = $data->users;
				$insertArray = array();
				$insertrepowner=array();
					foreach ($leads as $lead) {
						foreach ($userPackets as $packet) {
							$data=array(
								'mapping_id' => $mapping_id,
								'lead_cust_id' => $lead,
								'to_user_id' => $packet->to_user_id,
								'from_user_id'=>$user,
								'module'=>$packet->module,
								'type'=>'lead',
								'state'=>1,
								'action'=>'reopened',
								'timestamp'=>date('Y-m-d H:i:s')
								);
							$data1=array(
								'lead_id'=>$lead,
								'lead_rep_status'=>'',
								'lead_manager_status'=>0,
								'lead_closed_reason'=>'',
								'lead_status'=>0);
							array_push($insertArray, $data);
							array_push($insertrepowner,$data1);          
						}
					}
				$insert=$this->manager->rep_assign($insertArray);						
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($insert);
				if($insert==true){
					$update=$this->manager->rep_owner_update($insertrepowner);
					$GLOBALS['$logger']->debug('response received successfully');						
					$GLOBALS['$logger']->debug($update);
					echo $update;
				}
				} catch (LConnectApplicationException $e)  {
					echo $this->exceptionThrower($e);
				}				
		}else{
				redirect('indexController');
		}
	}  

	public function add_date(){
		if($this->session->userdata('uid')){
			try{
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				echo $data->DATEVAL;
			} catch (LConnectApplicationException $e)  {
			echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	 
	public function get_product(){  
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('get_product from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));	
				$user=$this->session->userdata('uid');
				$product = $this->manager->product($user);						
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($product);
				echo json_encode($product);
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	public function get_productselected(){  
		if($this->session->userdata('uid')){
			try{	
				$GLOBALS['$logger']->debug('fetch_product_selected_by_the_user from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));
				$product = $this->manager->get_selproduct();							
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($product);
				echo json_encode($product);
			}catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	public function product_array(){
		if($this->session->userdata('uid')){
			try{
			$lid = $this->input->post('id');
			$GLOBALS['$logger']->debug('inserting_product from manager');
			$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));	
			$product = $this->manager->products($lid);				
			$GLOBALS['$logger']->debug('response received successfully');						
			$GLOBALS['$logger']->debug($product);
			echo json_encode($product);
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}
		else{
				redirect('indexController');
		}  
	}

	public function product_views(){
		if($this->session->userdata('uid')){
			try{
				$lid = $this->input->post('id');
				$GLOBALS['$logger']->debug('product_views from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));		
				$product = $this->manager->product_view($lid);
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($product);
				echo json_encode($product);
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
				redirect('indexController');
		}  
	}

	public function lead_source(){
		if($this->session->userdata('uid')){ 
			try{    
				$GLOBALS['$logger']->debug('get_leadsource from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));				
				$product_data = $this->manager->leadsource1();
				$json = array();
				$hkey11="";
					for($i=0;$i<count($product_data);$i++){
									$json[$i]['id'] = $product_data[$i]->hkey2;
									$json[$i]['name'] = $product_data[$i]->hvalue2;
									$a=$product_data[$i]->hkey1;
									if($a=='0'){
										$json[$i]['parent'] = "";
									}else{
									$json[$i]['parent'] = $product_data[$i]->hkey1;
									}
									$json[$i]['checked'] = false;
									$json[$i]['nameAttr'] = 'Addlead';
					}					
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($json);
				echo json_encode($json); 
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
						redirect('indexController');
		}
	}

	public function leadsource_edit(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('leadsource_edit from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));	
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$leadid = $this->input->post('id');
				$productdata_edit=$this->manager->productdata_edit($leadid);						
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($productdata_edit);
				echo json_encode($productdata_edit);
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
				redirect('indexController');
		}
	}
		

	public function get_country(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('reassign_leads from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));	
				$country=$this->manager->view_country();							
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($country);
				echo json_encode($country);
				} catch (LConnectApplicationException $e)  {
					echo $this->exceptionThrower($e);
				}
			}else{
				redirect('indexController');
		}
	}
	
	public function post_leadinfo(){
		/*dev_remarks creating a lead lead_manager_status is 2, lead_rep_status is 0 and
		lead_status is 0*/
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('add_lead from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));	
				$user=$this->session->userdata('uid');    
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$leadname = ucfirst(strtolower($data->leadname)); 
				$check=$this->manager->check_leadname($leadname,'');
				$mapping_id = uniqid(rand(),TRUE);
				$dt = date('ymdHis');
				   
				$leadwebsite = $data->leadwebsite;
				$leademail=array();
				$leademail['email'] = $data->leademail;
				$leadphone=array();
				$leadphone['phone'][0] = $data->phone; 
				$productid= $data->product;
				$str = '';
					foreach($productid as $key=>$item) {
						$str .= $item.',';
					}   
				$productids= rtrim($str, ',');  
				$leadsource = $data->source;
				$leadcountry = $data->country;
				$leadstate = $data->state;
				$city = $data->city;
				$zipcode = $data->zipcode;
				$ofcaddress = $data->ofcaddress;
				$splcomments = $data->splcomments;
				$contactname = $data->contactname;
				$designation = $data->designation;
				$mob=array();
				$mob['phone']=$data->mobiles;				   
				$emails=array();
				$emails['email']=$data->emails;
				$contacttype = $data->contacttype;
				/*$longitude= $data->longitude;
				$lattitude = $data->lattitude;*/
				$coordinate=$data->coordinate;
				$lead_industry=$data->add_industry;
				$lead_business_loc=$data->add_business_location;
				$lead=strtoupper(substr($leadname,0,2));
				$leadid= $lead.uniqid($dt);
				$timestamp=date('y-m-d H:i:s');
				$contPrsnAdd=$data->contPrsnAdd;
				$leadCustom=$data->leadCustom;
				$data1 = array(
					'lead_id' => $leadid,
					'lead_name' => $leadname,
					'lead_number' =>json_encode($leadphone),        
					'lead_email' => json_encode($leademail),
					'lead_source' =>$leadsource,        
					'lead_country' => $leadcountry,
					'lead_state' =>$leadstate,
					'lead_city'=> $city,
					'lead_zip'=> $zipcode,
					'lead_website'=> $leadwebsite,                
					'lead_location_coord'=> $coordinate,        
					'lead_remarks'=> $splcomments,
					'lead_address'=> $ofcaddress,
					'lead_created_by'=>$user,
					'lead_created_time'=>$timestamp,
					'lead_manager_status'=>'2',
					'lead_rep_status'=>'0',
					'lead_status'=>'0',
					'lead_industry'=>$lead_industry,
					'lead_business_loc'=>$lead_business_loc,
					'lead_manager_owner'=>$user,
					'attribute'=>json_encode($leadCustom),
					'contact_number'=>$data->mobiles[0]				
				);
				if($productids!=''){
					$productids=explode(",", $productids);       
					for($i=0;$i<count($productids);$i++) {
						$data4=array(
						'product_id'=>$productids[$i],
						'lead_id'=>$leadid,
						'timestamp'=>$timestamp
						);
						$insert3=$this->manager->insert_product($data4);
					}      
				}	 
				$empid=uniqid($dt);
				$data2 = array(
					'contact_id' =>$empid,
					'lead_cust_id' =>$leadid,
					'contact_name'=> $contactname,
					'contact_desg' => $designation,
					'contact_email' =>json_encode($emails),
					'contact_number'=> json_encode($mob),        
					'contact_type'=>$contacttype,
					'contact_created_time'=>$timestamp,
					'contact_created_by'=>$user,
					'contact_for'=>'lead',
					'contact_address'=>$contPrsnAdd
				);
					 
				$data3 = array(
					'lead_cust_id' =>$leadid,
					'from_user_id' =>$user,
					'to_user_id' =>$user,      
					'action'=>'created',
					'module'=>'manager',
					'type'=> 'lead',
					'state' => 1,
					'timestamp'=>$timestamp,
					'mapping_id'=>$mapping_id
				);
		 
			 
			$insert = $this->manager->insert_lead1($data1);			
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($insert);
				if($insert==TRUE){
					$insert1 = $this->manager->insert_details1($data2);					
					$GLOBALS['$logger']->debug('response received successfully');						
					$GLOBALS['$logger']->debug($insert1);
						if($insert1==TRUE){
							$insert2 = $this->manager->insert_rep($data3);						
							$GLOBALS['$logger']->debug('response received successfully');						
							$GLOBALS['$logger']->debug($insert2);
								if($insert1==TRUE){
									$response = array();
									$response['leadid'] = $leadid;
									$response['status'] = 'true';									
									$GLOBALS['$logger']->debug('response received successfully');						
									$GLOBALS['$logger']->debug($response);
									echo json_encode($response);
								}
						}
				} 				
				
			}catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	public function get_state(){ 
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('get_state from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));					
				$cid = $this->input->post('id');
				$state = $this->manager->state($cid);				
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($state);
				echo json_encode($state); 
			}catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	public function contacttype(){
		if($this->session->userdata('uid')){ 
			try{
				$GLOBALS['$logger']->debug('contacttype from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));					
				$contacts = $this->manager->contact();					
				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($contacts);		     
				echo json_encode($contacts);
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	
		
	public function update_lead(){        
		if($this->session->userdata('uid')){   
			try{
				$GLOBALS['$logger']->debug('update_lead from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));	
				$userid=$this->session->userdata('uid');            
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$leadname = ucfirst(strtolower($data->leadname));
				$leadid=$data->leadid;
				$check=$this->manager->check_leadname($leadname,$leadid);				
					$dt = date('ymdHis');
					$timestamp=date('y-m-d H:i:s');				
					$leadwebsite = $data->leadwebsite;
					$leaemail=array();
					$leademail['email'] = $data->leademail;
					$leadphone=array();
					$leadphone['phone'] = $data->phone;
					$productid= $data->product;
					$leadsource = $data->source;
					$leadcountry = $data->country;
					$state = $data->state;
					$city = $data->city;
					$zipcode = $data->zipcode;
					$ofcaddress = $data->ofcaddress;
					$splcomments = $data->splcomments;
					$contactname = $data->contactname;
					$designation = $data->designation;
					$mobiles=array();
					$mobiles['phone'] = $data->mobiles;       
					$emails=array();
					$emails['email'] = $data->emails;       
					$contacttype = $data->contacttype;
					/*  $longitude= $data->longitude;
					$lattitude = $data->lattitude;*/
					$coordinate=$data->coordinate;				
					$employeeid=$data->employeeid;
					$edit_business_location=$data->business_location;
					$edit_industry=$data->industry_name;  
					$contPrsnAdd=$data->contPrsnAdd;
					$leadCustom=$data->leadCustom;

					$data1 = array(
						'lead_id' => $leadid,
						'lead_name' => $leadname,
						'lead_number' =>json_encode($leadphone),         
						'lead_email' => json_encode($leademail),
						'lead_source' =>$leadsource,          
						'lead_country' => $leadcountry,
						'lead_state' =>$state,
						'lead_city'=> $city,
						'lead_zip'=> $zipcode,
						'lead_website'=> $leadwebsite,
						'lead_location_coord'=> $coordinate,
						'lead_updated_by'=>$userid,
						'lead_updated_time'=>$timestamp,
						'lead_remarks'=> $splcomments,
						'lead_address'=> $ofcaddress,
						'lead_business_loc'=>$edit_business_location,
						'lead_industry'=>$edit_industry,
						'attribute'=>json_encode($leadCustom)				
					);
			 
					$data2 = array(
						'lead_cust_id' =>$leadid,
						'contact_name'=> $contactname,
						'contact_desg' => $designation,
						'contact_email' =>json_encode($emails),
						'contact_number'=> json_encode($mobiles),
						'contact_updated_by' => $userid,
						'contact_updated_time' =>$timestamp,
						'contact_type'=>$contacttype,
						'contact_for'=>'lead',
						'contact_address'=>$contPrsnAdd
					);
					$mapping_id = uniqid(rand(),TRUE);
					$edit=array('lead_cust_id'=>$leadid,
							'to_user_id'=>$userid,
							'timestamp'=>$timestamp,
							'action'=>'edited',
							'module'=>'manager',
							'type'=>'lead',
							'mapping_id'=>$mapping_id);
					$insert=$this->manager->insert_lead_cust_table($edit);						
					$GLOBALS['$logger']->debug('response received successfully');						
					$GLOBALS['$logger']->debug($insert);				 
					$update = $this->manager->update_info($leadid,$data1);
					$GLOBALS['$logger']->debug('response received successfully');						
					$GLOBALS['$logger']->debug($update);
					if($update==TRUE){          
						$update1 = $this->manager->update_details($employeeid,$data2);				
						$GLOBALS['$logger']->debug('response received successfully');						
						$GLOBALS['$logger']->debug($update1);
							if($update1==TRUE){
								$update3 = $this->manager->delete_prod($leadid);								
								$GLOBALS['$logger']->debug('response received successfully');						
								$GLOBALS['$logger']->debug($update3);
									if($update3==TRUE){
										$count=count($productid);
											for($i=0;$i<$count;$i++){
												$data6 = array(
													'lead_id' =>$leadid,
													'product_id'=>$productid[$i],
													'timestamp' =>$dt,
												);
												$insert5 = $this->manager->insert_product($data6);
											$GLOBALS['$logger']->debug('response received successfully');				
											$GLOBALS['$logger']->debug($insert5);
											}                      										
											/*$response = array();
											$response['leadid'] = $leadid;*/
											$response = 'true';											
											$GLOBALS['$logger']->debug('response received successfully');				
											$GLOBALS['$logger']->debug($response);
											echo json_encode($response);
									}
							} 
					}  
				} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
				}
		} else{
				redirect('indexController');
		} 
	}

	public function lead_accept(){
		/*
			this function can benefit from rework.
			instead of querying for each lead id, send all at once, and then insert for those
			which i am eligible for, in a single query.
		*/
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('lead_accept from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));		
				$userName = $this->session->userdata('uname');		
				$user=$this->session->userdata('uid');
				$json = file_get_contents("php://input");
				$data = json_decode($json);   
				$timestamp= date('Y-m-d H:i:s'); 
				$lid=explode(":", $data->lid); 
				$qualified = array();
				$dt = date('ymdHis');
                $lName = '';
                $notify_id= uniqid($dt);
                $leadArray = array();
				$notificationDataArray = array();
					for($i=0; $i<count($lid); $i++) {
						$acceptedStatus = $this->manager->updateLeadMgrOwner($lid[$i], $user);
							if ($acceptedStatus != false) {
								//qualified will hold the lead_ids of those which are accepted
								array_push($qualified, $acceptedStatus);
							}
							// array push
							array_push($leadArray, $lid[$i]);

					}

					$getAssignedManagerArray = array();
					foreach ($leadArray as $key => $value) {
					// Fetching Assigned Managers.
						$getAssignedManager = $this->manager->fetchAssignedManager($value,$user); 

						array_push($getAssignedManagerArray,$getAssignedManager);
					}

					for ($i=0; $i <count($lid) ; $i++) { 

					$notifyUpdateData = array('show_status'=>'1');
                        //$this->manager->notificationShowStatus($notifyUpdateData,$lid[$i],$user);	

                        $superiorManager = $this->manager->fetchSuperiorManager();
                 		//if lead Assigned by Admin. Rejected by assign to Superior manager.

						$leadName =  $this->manager->getLeadName($lid[$i]);
                        $lName = $leadName[0]->lead_name;

                        if($getAssignedManagerArray[$i][0]->managername == 'Admin'){

                        	$notificationData= array(
                            'notificationID' =>$notify_id,
                            'notificationShortText'=>'Lead Accepted',
                            'notificationText' =>'Lead '.$lName.' are accepted by '.$userName.'.',
                            'from_user'=>$user,
                            'to_user'=>$superiorManager[0]->superior_id,
                            'action_details'=>'lead',
                            'notificationTimestamp'=>$dt,
                            'read_state'=>0,
                            'remarks'=>'Accepted',
                            'show_status'=>0
                            );

                            array_push($notificationDataArray, $notificationData);

                        }
                        else{

                        	$notificationData= array(
							'notificationID' =>$notify_id,
							'notificationShortText'=>'Lead Accepted',
							'notificationText' =>'Lead '.$lName.' are accepted by '.$userName.'.',
							'from_user'=>$user,
							'to_user'=>$getAssignedManagerArray[$i][0]->managerid,
							'action_details'=>'lead',
							'notificationTimestamp'=>$dt,
							'read_state'=>0,
							'remarks'=>'Accepted',
							'show_status'=>0
							);
							array_push($notificationDataArray,$notificationData); 
                        }
                    }   

				// batch inserting notification data. 
				
				$notificationsInsert = $this->manager->insertNotificationData($notificationDataArray);

				$GLOBALS['$logger']->debug('response received successfully');	
				$GLOBALS['$logger']->debug($qualified);		
				echo json_encode($qualified);
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}


	public function lead_reject(){
		if($this->session->userdata('uid')){
			try{
				$GLOBALS['$logger']->debug('lead_reject from manager');
				$GLOBALS['$logger']->debug('UserID:'.$this->session->userdata('uid'));
				$user=$this->session->userdata('uid');
				$user_name = $this->session->userdata('uname');
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				//$timestamp=date('y-m-d H:i:s');
				$lid=explode(":", $data->lid);           
				$remarks=$data->note;
				$mapping_id = uniqid(rand(),TRUE);
				$dt = date('ymdHis');
                $notify_id= uniqid($dt);
                $lName = '';
                $updateLeadArray = array();
                $assignedDataArray=array();
                $assignedNotificationArray= array();
                $notificationDataArray = array();
				$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
				$timestamp=$date->format('Y-m-d H:i:s'); 
				$leadArray = array(); 
				$assignedRejectedDataArray = array();
				$result = '';
				for ($i=0; $i <count($lid) ; $i++) { 
					
						array_push($leadArray, $lid[$i]);
				}

				// Notifications for each assigned user and assigned to manager who assigned the lead.

				$getAssignedManagerArray = array();
				foreach ($leadArray as $key => $value) {
					// Fetching Assigned Managers.
					$getAssignedManager = $this->manager->fetchAssignedManager($value,$user); 

					array_push($getAssignedManagerArray,$getAssignedManager);
				}

				//var_dump($getAssignedManagerArray);

				for ($i=0; $i <count($lid) ; $i++) { 

					$notifyUpdateData = array('show_status'=>'1');
                        $this->manager->notificationShowStatus($notifyUpdateData,$lid[$i],$user);						
						$leadName =  $this->manager->getLeadName($lid[$i]);
                        $lName = $leadName[0]->lead_name;


                        //Fetch Superior Manager who reports to Admin
                		$superiorManager = $this->manager->fetchSuperiorManager();
                 		//if lead Assigned by Admin. Rejected by assign to Superior manager.


                		if($getAssignedManagerArray[$i][0]->Admin == NULL){

                			// Rejected Admin Assigned Leads assigned to Superior Manager.

							// Array Push to assignedRejectedDataArray from assignedRejectedData

							// Update Previously assigned data to be '0'

							$data=array(
								'mapping_id'=>$mapping_id,
								'lead_cust_id' => $lid[$i],
								'from_user_id' => $user,
								'to_user_id' => $user,
								'action'=>'rejected',
								'type'=>'lead',
								'state'=>1,
								'module'=>'manager',
								'remarks'=>$remarks,
								'timestamp'=>$timestamp
							);
							$result=$this->manager->lead_accept_mgr($data,$user,$lid[$i]);	
							$GLOBALS['$logger']->debug('response received successfully');						
							$GLOBALS['$logger']->debug($result);
							if($result==1){	
								$count1=$this->manager->count_assign_lead($lid[$i]);							
								$GLOBALS['$logger']->debug('response received successfully');						
								$GLOBALS['$logger']->debug($count1);
								$count2	=$this->manager->count_rejected_lead($lid[$i]);								
								$GLOBALS['$logger']->debug('response received successfully');						
								$GLOBALS['$logger']->debug($count2);
								if($count1==$count2)
								{
									$mstatus=array('lead_manager_status'=>3);
									$this->manager->update_leadtable($lid[$i],$mstatus);
								}							
							}

							$assignedData= array(
							'mapping_id' =>uniqid(rand(),TRUE),
							'lead_cust_id' =>$lid[$i],
							'type'=>'lead',
							'state' =>1,
							'action'=>"assigned",
							'module'=>"manager",
							'from_user_id'=>$getAssignedManagerArray[$i][0]->managerid,
							'to_user_id'=>$superiorManager[0]->superior_id,
							'timestamp'=>$dt,
							);

							//array_push to assignedDataArray
							array_push($assignedDataArray, $assignedData);

							// update lead manager state to 1 in leadinfo
                        	$updateLead=array('lead_manager_status'=>1,
                            'lead_id'=>$lid[$i]);

                            //array_push to updateLeadArray

                            array_push($updateLeadArray, $updateLead);

							$assignedNotification= array(
							'notificationID' =>$notify_id,
							'notificationShortText'=>'Lead Assigned',
							'notificationText' => $lName ." lead which is assigned from Admin has been rejected by ".$user_name.'.Now its been assigned to you',
							'from_user'=>$getAssignedManagerArray[$i][0]->managerid,
							'to_user'=>$superiorManager[0]->superior_id,
							'action_details'=>'lead',
							'notificationTimestamp'=>$dt,
							'read_state'=>0,
							'remarks'=>$remarks,
							'task_id'=>$lid[$i],
							);

							// array_push to assignedNotificationArray

							array_push($assignedNotificationArray, $assignedNotification);

                 		}
                 		else {

                 			 $notificationData= array(
                            'notificationID' =>$notify_id,
                            'notificationShortText'=>'Lead Rejected',
                            'notificationText' =>'Lead '.$lName.' is decline by '.$user_name.'.',
                            'from_user'=>$user,
                            'to_user'=>$getAssignedManagerArray[$i][0]->managerid,
                            'action_details'=>'lead',
                            'notificationTimestamp'=>$dt,
                            'read_state'=>0,
                            'remarks'=>$remarks,
                            );
                        
                        	array_push($notificationDataArray,$notificationData); 

							$data=array(
								'mapping_id'=>$mapping_id,
								'lead_cust_id' => $lid[$i],
								'from_user_id' => $user,
								'to_user_id' => $user,
								'action'=>'rejected',
								'type'=>'lead',
								'state'=>1,
								'module'=>'manager',
								'remarks'=>$remarks,
								'timestamp'=>$timestamp
							);
							$result=$this->manager->lead_accept_mgr($data,$user,$lid[$i]);	
							$GLOBALS['$logger']->debug('response received successfully');						
							$GLOBALS['$logger']->debug($result);
							if($result==1){	
								$count1=$this->manager->count_assign_lead($lid[$i]);							
								$GLOBALS['$logger']->debug('response received successfully');						
								$GLOBALS['$logger']->debug($count1);
								$count2	=$this->manager->count_rejected_lead($lid[$i]);								
								$GLOBALS['$logger']->debug('response received successfully');						
								$GLOBALS['$logger']->debug($count2);
								if($count1==$count2)
								{
									$mstatus=array('lead_manager_status'=>3);
									$this->manager->update_leadtable($lid[$i],$mstatus);
								}							
							}

                 		}

				}

               	// Inserting Batch assignedRejectedDataArray to leadCustUserMap

               	if(!empty($assignedDataArray)){
               		// Adding Assignment data 
					$assignedUser = $this->manager->assignedUserData($assignedDataArray);
               	} 


				// Adding Notifications.
				if(!empty($notificationDataArray)){
					$notificationsInsert = $this->manager->insertNotificationData($notificationDataArray);
				}

				if(!empty($assignedNotificationArray)){
					$notificationAssignedData = $this->manager->insertNotificationAssigned($assignedNotificationArray);
				}
				
				//updating Lead info
				$updateLeadData = $this->manager->updateLeadInfoData($updateLeadArray);

				$GLOBALS['$logger']->debug('response received successfully');						
				$GLOBALS['$logger']->debug($result);
				echo  json_encode($result); 
				
				} catch (LConnectApplicationException $e)  {
					echo $this->exceptionThrower($e);
				}
		}else{
			redirect('indexController');
		}
	}

	public function get_plugin_data(){
		$user=$this->session->userdata('uid');
		$opuput = $this->manager->user_plugin($user);
		echo json_encode($opuput);
		
	}

	public function customFieldLead() {
    if($this->session->userdata('uid')){
    try{
        $GLOBALS['$logger']->debug('Custom Field function called');
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $leadId=$data->leadid;
        $getCustomLead='';
        $finalArrayLead=array();
      	$getCustomLead=$this->manager->fetchCustomLead($leadId);
          $GLOBALS['$logger']->debug("Lead Custom Field");
          $GLOBALS['$logger']->debug($getCustomLead);
          for($i=0;$i<count($getCustomLead);$i++){
            if($getCustomLead[$i]->attribute!='' OR $getCustomLead[$i]->attribute!=NULL){
               $arr = json_decode($getCustomLead[$i]->attribute);

               for($j=0;$j<count($getCustomLead);$j++){
                  if($getCustomLead[$i]->attribute_key == $arr[$i]->attribute_key){
                    $someArray=array(
                      'attribute_key'=>$getCustomLead[$i]->attribute_key,
                      'attribute_value'=>$arr[$i]->attribute_value,
                      'attribute_name'=>$getCustomLead[$i]->attribute_name,
                      'attribute_validation_string'=>$getCustomLead[$i]->attribute_validation_string,
                      'attribute_type'=>$getCustomLead[$i]->attribute_type,
                      'module'=>$getCustomLead[$i]->module,
                      'id'=>$getCustomLead[$i]->id
                    );
                  }
                  else{
                    $someArray=array(
                      'attribute_key'=>$getCustomLead[$i]->attribute_key,
                      'attribute_value'=>'',
                      'attribute_name'=>$getCustomLead[$i]->attribute_name,
                      'attribute_validation_string'=>$getCustomLead[$i]->attribute_validation_string,
                      'attribute_type'=>$getCustomLead[$i]->attribute_type,
                      'module'=>$getCustomLead[$i]->module,
                      'id'=>$getCustomLead[$i]->id
                    );
                  }
                }
            }
            else{
               $someArray=array(
                      'attribute_key'=>$getCustomLead[$i]->attribute_key,
                      'attribute_value'=>'',
                      'attribute_name'=>$getCustomLead[$i]->attribute_name,
                      'attribute_validation_string'=>$getCustomLead[$i]->attribute_validation_string,
                      'attribute_type'=>$getCustomLead[$i]->attribute_type,
                      'module'=>$getCustomLead[$i]->module,
                      'id'=>$getCustomLead[$i]->id
                    );
            } 

            array_push($finalArrayLead,$someArray);             
          } 
        $finalCustom=array('leadCustom'=>$finalArrayLead);
        echo json_encode($finalCustom);   
        }
        catch (LConnectApplicationException $e){
       echo $this->exceptionThrower($e);
}
    }else{
        redirect('indexController');
    }
}

public function AddCustomFiledLead() {

	 $GLOBALS['$logger']->debug('Custom Field function called');
	 $getCustomLead='';
     $finalArrayLead=array();
     $getCustomLead=$this->manager->fetchAddCustomLead();
     $GLOBALS['$logger']->debug("Lead Custom Field");
     $GLOBALS['$logger']->debug($getCustomLead);
     for($i=0;$i<count($getCustomLead);$i++){
		$someArray=array(
			'attribute_key'=>$getCustomLead[$i]->attribute_key,
			'attribute_value'=>'',
			'attribute_name'=>$getCustomLead[$i]->attribute_name,
			'attribute_validation_string'=>$getCustomLead[$i]->attribute_validation_string,
			'attribute_type'=>$getCustomLead[$i]->attribute_type,
			'module'=>$getCustomLead[$i]->module,
		);
		array_push($finalArrayLead,$someArray);             
      }
      $finalCustom=array('leadCustom'=>$finalArrayLead);
      echo json_encode($finalCustom);  

}
	public function file_upload($path){
      	if($this->session->userdata('uid')){
        	try{
        		$user_id=$this->session->userdata('uid');
		        $config['upload_path']   = './uploads';
		        $config['allowed_types'] = 'gif|jpg|png|bmp';
		        $config['max_size'] = "5024000"; 
		        $config['overwrite']  = TRUE;
		        $this->load->library('upload', $config);
		        $GLOBALS['$logger']->debug("file upload");
		        if (!$this->upload->do_upload('userfile')){
		                $error = array('error' => $this->upload->display_errors());
		                 echo 1;
		                 $GLOBALS['$logger']->debug("file upload unsuccess");
		        } else {
			        $data = array(
			         'upload_data' => $this->upload->data()
			        );
			        $size=$data["upload_data"]["file_size"];				        	
			       	$this->lconnecttcommunication->FileSizeConvert($size,$user_id,'manager_lead_page');	
			        $old_path=$data['upload_data']['full_path'];
			        $old_fname = $data['upload_data']['file_name'];
			        $new_fname = $path.$data['upload_data']['file_ext'];
			        $new_path = str_replace($old_fname, $new_fname, $old_path);
			        if (rename($old_path, $new_path)){
			            $leadphoto = $new_fname;
			            $data = array(
			                'lead_logo' => $leadphoto,
			            );
			            $update = $this->manager->update_leadPhoto($path,$data );
			            if ($update == TRUE) {
			            	$GLOBALS['$logger']->debug("file upload success");
			                echo 1;
			            }
			        }
			    }
        	}catch (LConnectApplicationException $e){
               echo $this->exceptionThrower($e);
            }
     	}else{
            redirect('indexController');
        }
	}

public function contactsForLeadCust() {
    if($this->session->userdata('uid')){
        try{
            $GLOBALS['$logger']->info('contacts for lead customer function called');
            $json = file_get_contents("php://input");
            $data = json_decode($json);
            $leadCustId = $data->leadid;
            $GLOBALS['$logger']->info("Lead Cust ID : $leadCustId");
            $contactDataArray = $this->manager->fetchAllContacts($leadCustId);
            $GLOBALS['$logger']->info('Fetching Contact Records');
            $GLOBALS['$logger']->info('Fetched');
            $GLOBALS['$logger']->info('Response in Array');
            $GLOBALS['$logger']->info($contactDataArray);
            echo json_encode($contactDataArray);            
        }
        catch(LConnectApplicationException $e){
            echo $this->exceptionThrower($e);
        }
    }
    else{
    redirect('indexController');
    }
}

public function getEmailList($value='') {
				$mbox=array();
				$hs='sg3plcpnl0032.prod.sin3.secureserver.net';
				$inport = 993;
				$ihostname = '{'.$hs.':'.$inport.'/imap/ssl}INBOX';  
				$username = 'lconnect@likhitech.in'; 
				$password = 'Lc0nnect123';
				$mbox = imap_open ($ihostname, $username , $password) or die("ERROR: " . imap_last_error());
				$msgnos = imap_sort($mbox,SORTARRIVAL ,1,SE_UID);
				imap_headerinfo($hs);
				//$msgnos = imap_search($mbox, 'ALL',SE_UID);
				print_r($mbox);	
}	

	public function DetailsforValidation()	{	
			$ContactArray = $this->manager->getContact();		
			echo json_encode($ContactArray);
	}

	public function close_lead(){
     if($this->session->userdata('uid'))
     {
         try
        {
        	//For closed_won status 2 is to be updated to lead_info table
        	//temporary loss status 3 is to be updated to lead_info table
        	//permanent loss status 4 is to be updated to lead_info table
            $userid= $this->session->userdata('uid');
            $json = file_get_contents("php://input");
            $data = json_decode($json);
            $close_opportunity_insert_array = array();
            $close_opportunity_update_array = array();
            $opportunity_tasks_array = array();
            $leadid = $data->leadid;
            $reason= $data->reason;
            $remarks = $data->remarks;
            $approach_date = $data->date;
            $name= $data->lead_name;
            $lead_activity= $data->activity;
            $future_activity= $data->future_activity;
            $duration= $data->duration;
            $alert_before= $data->reminder;
            $contact_id= $data->contact_id;
            $task_title= $data->lead_title;
            $dt = date('ymdHis');     
            $lead_reminder_id = '';
            $lead_reminder_id .= $dt;
            $lead_reminder_id = uniqid($lead_reminder_id);
            $mapping_id = uniqid(rand(),TRUE);  
            $newDate = date("Y-m-d H:i:s");
            $event_start_date=date('Y-m-d', strtotime($approach_date));
            $event_start_time = date('H:i', strtotime($approach_date));
            $seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
            $activity_duration = (int)$seconds->getTimestamp();
            $start = new DateTime($approach_date);
            $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
            $event_end = $event_end->format('Y-m-d H:i:s');
            // Fetch all opportunity data;

            $opportunity_data = $this->manager->check_opportunity_owner($leadid,$userid);


            $opportunity_tasks = $this->lead->check_opportunity_tasks($leadid);
			if($data->reason == 'permanent_loss'){
				$closed_reason = 'permanent loss';
			}
			if($data->reason == 'temporary_loss'){
				$closed_reason ='temporary loss';
			}

			//echo $closed_reason; exit(); 	

            for ($i=0; $i <count($opportunity_data['value']) ; $i++)
            { 
                $log_trans_data = array(
                    'mapping_id' => uniqid(rand(),TRUE),
                    'opportunity_id'=> $opportunity_data['value'][$i]->opportunity_id,
                    'lead_cust_id' => $opportunity_data['value'][$i]->lead_cust_id,
                    'from_user_id'=> $userid,
                    'to_user_id'=> $userid,
                    'cycle_id' => $opportunity_data['value'][$i]->cycle_id,
                    'stage_id' => $opportunity_data['value'][$i]->opportunity_stage,
                    'module' => 'manager',
                    'timestamp'=> date('Y-m-d H:i:s'),
                    'sell_type' => $opportunity_data['value'][$i]->sell_type,                   
                    'action'=>$closed_reason,
                    'state'=>1,
                    'remarks'=>'opportunity is closed from lead'
                );
               
               
                $update_opp_data = array(
                                    'opportunity_id' => $opportunity_data['value'][$i]->opportunity_id,
                                    'closed_status' => '100',
                                    'opportunity_approach_date'=>$data->date,
                                    'closed_reason'=>$data->reason,
                                    'opportunity_date'=>date('Y-m-d')                         
                                    );

                if ($data->reason == 'temporary_loss') 
                {
                    $dt = date('ymdHis');
                    $lead_reminder_id = '';
                    $lead_reminder_id .= $dt;
                    $lead_reminder_id = uniqid($lead_reminder_id); 

                    $opportunity_tasks = array(
                        'lead_reminder_id'  => $lead_reminder_id.rand(),
                        'lead_id'           => $opportunity_data['value'][$i]->opportunity_id,
                        'rep_id'            => $userid,
                        'leadempid'         => $contact_id,
                        'remi_date'         => $event_start_date,
                        'rem_time'          => $event_start_time,
                        'conntype'          => $future_activity,
                        'status'            => "scheduled",
                        'meeting_start'     => $approach_date,
                        'meeting_end'       => $event_end,
                        'addremtime'        => $alert_before,          
                        'timestamp'         => $newDate,
                        'remarks'           => $remarks,
                        'event_name'        => $task_title,
                        'duration'          => $duration,
                        'type'              => "opportunity",
                        'created_by'        =>$userid,
                        'module_id'         =>'manager'
                    ); 

                    array_push($opportunity_tasks_array, $opportunity_tasks);
                }

                

                // Closing all opportunity, if lead is closing permanent.

               	if ($lead_activity == 1)
                {
                    $this->lead->lead_activities($opportunity_data['value'][$i]->opportunity_id);
                }

                //array_push($close_opportunity_update_array1, $update_opp_data1);
                array_push($close_opportunity_update_array, $update_opp_data);
                array_push($close_opportunity_insert_array, $log_trans_data);

            }

            $data6 = array(
                'lead_reminder_id' => $lead_reminder_id,
                'lead_id'   => $leadid,
                'rep_id'    => $userid,
                'leadempid' => $contact_id,
                'remi_date' => $event_start_date,
                'rem_time'  => $event_start_time,
                'conntype'  => $future_activity,
                'status'    => "scheduled",
                'meeting_start'    => $approach_date,
                'meeting_end'      => $event_end,
                'addremtime'       => $alert_before,          
                'timestamp'        => $newDate,
                'remarks'          => $remarks,
                'event_name'       => $task_title,
                'duration'         => $duration,
                'type' => "lead",
                'created_by'=>$userid,
                'module_id'=>'manager'
                    );

           $data1 = array(
                'lead_status' => 4,
                'lead_closed_reason' =>$reason,
             );
            $data3 = array(
                'lead_status' => 3,
                'lead_closed_reason' =>$reason,
                'lead_approach_date' =>$newDate,
             );
            $data4 = array(
                'mapping_id' =>$mapping_id,
                'lead_cust_id' =>$leadid,
                'type'=>'lead',
                'action'=>"closed",
                'module'=>"manager",
                'from_user_id'=>$userid,
                'to_user_id'=>$userid,
                'state'=>1,
                'timestamp'=>$dt,
                'remarks'=>$remarks,
              );
            $notify_id= uniqid($dt);
            $data5= array(
              'notificationID' =>$notify_id,
              'notificationShortText'=>'Lead Closed',
              'notificationText' =>$name.' lead closed by force close',
              'from_user'=>$userid,
              'to_user'=>$userid,
              'action_details'=>'lead',
              'notificationTimestamp'=>$dt,
              'read_state'=>0,
              'remarks'=>$remarks,
            );
           
            if($reason=='permanent_loss')
            {            	
            		
            	$update1 = $this->lead->permanent_close($leadid,$data1);
            	$insert = $this->lead->insert_transaction($data4);

               // opportunity data's.
               if (!empty($close_opportunity_insert_array) && !empty($close_opportunity_update_array)) 
               {      

                   $log_data = $this->lead->insert_opp_log($close_opportunity_insert_array);

                   if ($log_data == 1) 
                   {
                       $update_data = $this->lead->update_opp_data($close_opportunity_update_array);                       
                   }
                
               		
               }

               if($lead_activity==1)
               {
                  $update4 = $this->lead->lead_activities($leadid);    
               }
            }

            if($reason=='temporary_loss'){
               $update1 = $this->lead->temporary_close($leadid,$data3);

               $insert = $this->lead->insert_transaction($data4);

               // opportunity data's.

               if (!empty($close_opportunity_insert_array) && !empty($close_opportunity_update_array)) 
               {               	  
                   $log_data = $this->lead->insert_opp_log($close_opportunity_insert_array);
                   if ($log_data == 1) 
                   {
                       $update_data = $this->lead->update_opp_data($close_opportunity_update_array);
                   }

                   $opp_tasks = $this->lead->insert_reminder($opportunity_tasks_array);
               }
               if($lead_activity==1)
               {
                    $update4 = $this->lead->lead_activities($leadid);  
               }
    		      $insert6 = $this->lead->insert_mytask($data6);
                  
            }

            $notify = $this->lead->notifications($data5);
            if($notify == TRUE){
               echo 1;
            }
        }
        catch (LConnectApplicationException $e)
        {
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
  public function leadManagerOwner(){
  	$user_id = $this->session->userdata('uid');
  	var_dump($this->manager->getManagerOwner($user_id));
  }
	
  public function checkOwnerStatus(){
  	$status = $this->manager->validateOpportunityandStageOwner('LE1808110703255b6e3ce5a1c4f');
  	var_dump($status);
  }

    public function check_opportunity(){ 
       if($this->session->userdata('uid')){
        try{
        	$userid = $this->session->userdata('uid');
			$lid = $_POST['id'];
			$opp_chk = $this->manager->opportunity_check($lid,$userid);
			echo json_encode($opp_chk);
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

     public function check_state_lead()
    {
        if($this->session->userdata('uid'))
        {
            try
            {

                $GLOBALS['$logger']->info('contacts for lead customer function called');
                $json = file_get_contents("php://input");
                $data = json_decode($json);

                var_dump($data); exit();

                $userid = $this->session->userdata('uid'); 

                $loss_type          = isset($data->lossType) ? $data->lossType: 'Reopened';
                $remarks            = $data->remarks;
                $reopen             = $data->reopen;
                $cancel_pending     = $data->futureActivityChk; 
                $leadid             = $data->leadId;

                // Fetching Lead Name & Contact Person Data.

                $lead_data      = $this->lead->get_lead_data($leadid);
               // var_dump($lead_data);exit();
                $name           = $lead_data['lead_data'][0]->lead_name;
                $leadExecutive 	= $lead_data['lead_data'][0]->lead_rep_owner;
                $contact_id     = $data->contactType;
                $mapping_id     = uniqid(rand(),TRUE);

                // Log Data.

                    $log_data = array(
                        'mapping_id'    => $mapping_id,
                        'lead_cust_id'  => $leadid,
                        'type'          => 'lead',
                        'action'        => "closed",
                        'module'        => "manager",
                        'from_user_id'  => $userid,
                        'to_user_id'    => $userid,
                        'state'         => 1,
                        'timestamp'     => date('ymdHis'),
                        'remarks'       => $remarks
                    );

                // Notification Data.

                    $notify_id= uniqid(date('ymdHis'));

                    if($loss_type == 'Reopened') 
                    {
                        $notificationShortText = $name.' lead reopened by '.$this->session->userdata('uname');
                    }
                    else
                    {
                        $notificationShortText = $name.' lead closed by force close';
                    }
                    
                    $notifications = array(
                                'notificationID' =>$notify_id,
                                'notificationShortText'=> 'Lead '.$loss_type,
                                'notificationText' =>$notificationShortText,
                                'from_user'=>$userid,
                                'to_user'=>$userid,
                                'action_details'=>'lead',
                                'notificationTimestamp'=>date('ymdHis'),
                                'read_state'=>0,
                                'remarks'=>$remarks,
                            );

                    $notifications1 = array(
                                'notificationID' =>$notify_id.rand(),
                                'notificationShortText'=> 'Lead '.$loss_type,
                                'notificationText' =>$notificationShortText,
                                'from_user'=>$userid,
                                'to_user'=>$leadExecutive,
                                'action_details'=>'lead',
                                'notificationTimestamp'=>date('ymdHis'),
                                'read_state'=>0,
                                'remarks'=>$remarks,
                            );

                    $opportunity_tasks = $this->lead->check_opportunity_tasks($leadid);

                // If Loss Type is temporary . 

                if ($loss_type == 'temporary_loss') 
                {

                    $approach_date   = $data->date;
                    // if contact_type for activity is not mentioned , add call.
                    $future_activity = $data->futureActivity;

                    $duration        = $data->activityDuration;
                    $alert_before    = $data->alertBefore;
                    $task_title      = isset($data->title) ? $data->title : "Reconnect with ".$name;

                    $newDate = date("Y-m-d H:i:s");
                    $event_start_date = date('Y-m-d', strtotime($approach_date));
                    $event_start_time = date('H:i:s', strtotime($approach_date));
                    $seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
                    $activity_duration = (int)$seconds->getTimestamp();
                    $start = new DateTime($approach_date);
                    $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
                    $event_end = $event_end->format('Y-m-d H:i:s');
                    $dt = date('ymdHis');
                    $lead_reminder_id = '';
                    $lead_reminder_id .= $dt;
                    $lead_reminder_id = uniqid($lead_reminder_id);


                    $scheduled_task = array(
                        'lead_reminder_id' => $lead_reminder_id,
                        'lead_id'   => $leadid,
                        'rep_id'    => $userid,
                        'leadempid' => $contact_id,
                        'remi_date' => $event_start_date,
                        'rem_time'  => $event_start_time,
                        'conntype'  => $future_activity,
                        'status'    => "scheduled",
                        'meeting_start' => $event_start_date,
                        'meeting_end'   => $event_end,
                        'addremtime'    => $alert_before,
                        'timestamp'     => $newDate,
                        'remarks'       => $remarks,
                        'event_name'    => $task_title,
                        'duration'      => $duration,
                        'type'          => "lead",
                        'created_by'    => $userid,
                        'module_id'     => 'manager'
                    ); 

                   // var_dump($scheduled_task);
                   // exit();

                    $temporary_loss = array(
                        'lead_status' => 3,
                        'lead_closed_reason' =>$loss_type,
                        'lead_approach_date' =>$approach_date,
                    );


                    // Update data and insert.
                    // if cancel pending is true, cancel future activity

                    $update_temporary_data = $this->lead->temporary_close($leadid,$temporary_loss);

                    $insert_log_data = $this->lead->insert_transaction($log_data);

                  

                    // Notification data inserting.

                    $insert_notifications = $this->lead->notifications($notifications);
                    $insert_notifications = $this->lead->notifications($notifications1);

                     if($cancel_pending == true)
                    {
                        $cancel_activites = $this->lead->lead_activities($leadid); 
                        // If exists,

                        if (!empty($opportunity_tasks)) 
                        {
                            foreach ($opportunity_tasks as $key => $value) 
                            {
                               $cancel_activites = $this->lead->lead_activities($value->opportunity_id); 
                            }

                        }   
                    }

                      // Schedule an event for approach date.

                    $insert_task = $this->lead->insert_mytask($scheduled_task);


                    $data = array(
                            'status' => TRUE,
                            'message' => 'lead changed to temporary loss.'
                            );
                    echo json_encode($data);

                }

                // If loss type is permanent

                if ($loss_type == 'permanent_loss') 
                {
                    // Update lead data and insert log.

                    $permanent_loss_data = array(
                    'lead_status' => 4,
                    'lead_closed_reason' => $loss_type,
                    );

                    $update_permanent_data = $this->lead->permanent_close($leadid,$permanent_loss_data);

                    $insert_log_data = $this->lead->insert_transaction($log_data);

                    // Notification data inserting.

                    $insert_notifications = $this->lead->notifications($notifications);
                    $insert_notifications = $this->lead->notifications($notifications1);

                       if($cancel_pending == true)
                    {
                        $cancel_activites = $this->lead->lead_activities($leadid);   

                        // If exists,

                        if (!empty($opportunity_tasks)) 
                        {
                            foreach ($opportunity_tasks as $key => $value) 
                            {
                               $cancel_activites = $this->lead->lead_activities($value->opportunity_id); 
                            }

                        }  
                    }

                    $data = array(
                            'status' => TRUE,
                            'message' => 'lead changed to permanent loss.'
                            );
                    echo json_encode($data);
                }

                // reopen lead . 
 
                if ($reopen == true) 
                {
                   $reopen_data =array(
                                        'lead_id'           =>$leadid,
                                        'lead_closed_reason'=>NULL,
                                        'lead_status'       =>1
                                        );

                    $log_data   = array(
                                            'lead_cust_id'=>$leadid,
                                            'state'=>0
                                            ); 

                    $log_trans_data=array(
                                    'lead_cust_id'=>$leadid,
                                    'state'=>1,
                                    'action'=>'reopened',
                                    'from_user_id'=>$data->mowner,
                                    'to_user_id'=>isset($data->assign) ? $data->rowner: $dta->mowner,                                
                                    'type'=>'lead',
                                    'mapping_id'=>$mapping_id,
                                    'module'=>'manager',
                                    'timestamp'=>date('Y-m-d H:i:s')
                                    );

                    $update_reopen_data = $this->lead->re_open_data($leadid,$reopen_data);

                    $update_log_data = $this->lead->update_reopen_log($leadid,$log_data);

                    $insert_log_data = $this->lead->insert_transaction($log_trans_data);

                    if($data->assign == true && $data->rowner!=""){
                    	$repArr=array(
                                    'lead_cust_id'=>$leadid,
                                    'state'=>1,
                                    'action'=>'accepted',
                                    'from_user_id'=>$data->rowner,
                                    'to_user_id'=>$data->rowner,                                
                                    'type'=>'lead',
                                    'mapping_id'=>$mapping_id.rand(),
                                    'module'=>'sales',
                                    'timestamp'=>date('Y-m-d H:i:s')
                                    );

                    	$notifications1 = array(
                                'notificationID' =>$notify_id.rand(),
                                'notificationShortText'=> 'Lead '.$loss_type,
                                'notificationText' =>$notificationShortText,
                                'from_user'=>$userid,
                                'to_user'=>$data->rowner,
                                'action_details'=>'lead',
                                'notificationTimestamp'=>date('ymdHis'),
                                'read_state'=>0,
                                'remarks'=>$remarks,
                            );

                    	$insert_log_data1 = $this->lead->insert_transaction($repArr);

                    	$insert_notifications = $this->lead->notifications($notifications1);
                    }else{
                    	$leadTableUpdate = array(/*'lead_id'=>$leadid,
												'lead_status'=>0,
												'lead_rep_status'=>0,
												'lead_manager_status'=>2,*/
												'lead_id'=>$leadid,
												'lead_rep_owner'=>null);
                    	$insert_log_data2 = $this->lead->updateLeadinfoReopen($leadTableUpdate,$leadid);						
                    }

                    // Notification data inserting.

                    $insert_notifications = $this->lead->notifications($notifications);


                    $data = array(
                            'status' => TRUE,
                            'message' => 'lead is re opened.'
                            );

                    echo json_encode($data);
                }
                       
            }
            catch(LConnectApplicationException $e)
            {
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
        else
        {
            redirect('indexController');
        }
    }
	public function fetchActivity()
	{
	if($this->session->userdata('uid')){
	try {
	$json = file_get_contents("php://input");
	$data = json_decode($json,TRUE);
	$leadid = $data['leadid'];
	$var = $this->mytask->fetch_activity();
	$contactArray = $this->manager->fetchContactsForLead($leadid);
	$arr=array('activityArray'=>$var, 'contactArray'=>$contactArray);
	echo json_encode($arr); 
	}catch(LConnectApplicationException $e) {
	echo $this->exceptionThrower($e);   
	}

	}else{
	redirect('indexController');
	}			
	}

	public function checkManagerAcceptReject()
	{
		if($this->session->userdata('uid'))
		{
			try {
			$result = $this->manager->managerPermissionForAcceptReject($this->session->userdata('uid'));
			echo json_encode($result);
			}
			catch(LConnectApplicationException $e) {
			echo $this->exceptionThrower($e);   
			}    				
		}
		else
		{
		redirect('indexController');
		}

	}
	// Function for fetching Others Won Lead 
	public function OtherWonLeads($value='')
	{
		if($this->session->userdata('uid'))
		{
			try 
			{
				$otherWonLeads = $this->manager->fetchOtherWonLead($this->session->userdata('uid'));
				echo json_encode($otherWonLeads);
			}
			catch(LConnectApplicationException $e) 
			{
			echo $this->exceptionThrower($e);   
			}    				
		}
		else
		{
		redirect('indexController');
		}
	}

	// Function for fetching Others Lost Lead 
	public function OtherLostLeads($value='')
	{
		if($this->session->userdata('uid'))
		{
			try 
			{
				$otherLostLeads = $this->manager->fetchOtherLostLead($this->session->userdata('uid'));
				echo json_encode($otherLostLeads);
			}
			catch(LConnectApplicationException $e) 
			{
			echo $this->exceptionThrower($e);   
			}    				
		}
		else
		{
		redirect('indexController');
		}
	}
    				
}


?>
