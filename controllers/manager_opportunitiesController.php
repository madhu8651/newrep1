<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_opportunitiesController');

class manager_opportunitiesController extends Master_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('lconnecttcommunication');
		$this->load->model('common_opportunitiesModel','opp_common');
		$this->load->model('manager_opportunitiesModel','opp_mgr');
		$this->load->model('sales_opportunitiesModel','opp_sales');
		$this->load->library('unit_test');
	}

	private function exceptionThrower($e)    {
		$GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
		$errorArray = array(
			'errorCode' => $e->getErrorCode(),
			'errorMsg' => $e->getErrorMessage()
		);
		$GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
		$GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
		return json_encode($errorArray);
	}
//-----Get user list for assignment of opportunity-----//
    public function get_assignees() {
		//used only while creating an opportunity as it's only one opportunity
		if ($this->session->userdata('uid')) {
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$user_id = $this->session->userdata('uid');
				$data->user_id = $user_id;
				// Get users based on stage assignment or ownership assignment.
				if ($data->btn_status == 'oppOwner')
				{
					$users = $this->opp_mgr->fetch_users($data,'oppOwner');
				}
				else
				{
					$users = $this->opp_mgr->fetch_users($data,'stageOwner');
				}

				echo json_encode($users);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-RECEIVED OPPORTUNITIES-=-=-=-=-=-=-=-=-=-=-=-=-*/
//----Function to Load view of received opportunity-----//
	public function received_opportunities() {
		if($this->session->userdata('uid')){
			$user_id = $this->session->userdata('uid');
			$data = $this->opp_common->fetch_userPrivilages($user_id);
			$this->load->view('manager_opportunity_received', $data);
		}else{
			redirect('indexController');
		}
	}
//----Function to get data for received opportunities-----//
	public function get_received_opportunities() {
		if($this->session->userdata('uid')){
			try {
				$user_id = $this->session->userdata('uid');
				$new = $this->opp_mgr->fetch_received_opportunities($user_id);
				echo json_encode($new);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}
//------Function to accept opportunity----//
	public function accept_opportunity(){
    	try {
	    	$userid= $this->session->userdata('uid');
            $json = file_get_contents("php://input");
            $data = json_decode($json);

            $given_data = array();
            $given_data['userid'] = $userid;
            $given_data['opp_owner']= $data->opportuniy;
            $given_data['opp_id'] = $data->opportuniy_id;
            $given_data['lead_cust_id'] = $data->lead_cust_id;
            $given_data['sell_type'] = $data->sell_type;
            $given_data['opportunity_stage'] = $data->stage_id;
            $given_data['cycle_id']= $data->cycle_id;
            $given_data['mapping_id'] = uniqid(rand());
            // get if its the first stage
            // if yes, accept both stage and ownership
            // if no, accept any of two
            $is_first_stage = $this->opp_sales->is_first_stage($given_data['opportunity_stage']);
            if ($is_first_stage == true)
            {

            	if($given_data['opp_owner']=='ownership'){
            		$own_accept = $this->accept_opp_ownership($given_data);
            	}
            	else if($given_data['opp_owner']=='stage') {
            		$stg_accept = $this->accept_opp_stage($given_data);
            	}
            	if ($own_accept == 1 OR $stg_accept == 1) {
            		echo 1;
            	}
            } else {
	            if($given_data['opp_owner']=='ownership'){
	            	echo $this->accept_opp_ownership($given_data);
	            } else if($given_data['opp_owner']=='stage') {
	            	echo $this->accept_opp_stage($given_data);
	            }
            }
        } catch (LConnectApplicationException $e) {
    		echo $this->exceptionThrower($e);
    	}
    }
//-----Helper function to accept opportunity ownership------//
    private function accept_opp_ownership($given_data) {

        $opp_ownership= $this->opp_mgr->owner_manager_status($given_data['opp_id']);

        $opp_status= $opp_ownership[0]->owner_manager_status;


        if($opp_status=='1'){
            $data = array(
               'manager_owner_id'=>$given_data['userid'],
               'owner_manager_status'=>2
            );
            $update = $this->opp_common->updateOpportunity($data, $given_data['opp_id']);


            $update1 = $this->opp_mgr->update_oppo_transaction($given_data['opp_id'], 'manager', 'ownership');

            $data2= array(
                'mapping_id' => uniqid(rand()),
                'opportunity_id' => $given_data['opp_id'],
                'lead_cust_id' => $given_data['lead_cust_id'],
                'from_user_id' => $given_data['userid'],
                'to_user_id' => $given_data['userid'],
                'cycle_id' => $given_data['cycle_id'],
                'stage_id' =>$given_data['opportunity_stage'],
                'module' => 'manager',
                'sell_type' => $given_data['sell_type'],
                'timestamp' => date('Y-m-d H:i:s'),
                'action' => 'ownership accepted',
                'state' => '1'
            );

            $insertArray1=array();

            //---------------- notification code----------------------------------
            $get_fromuser= $this->opp_mgr->get_from_userid1($given_data['opp_id'],$given_data['userid']);
            $getusername=$this->opp_mgr->getusernamae($get_fromuser);
            $getusername1=$this->opp_mgr->getusernamae($given_data['userid']);
            //to user---- is the one who is to be notified.
            // from user---- is the one who performs action
            $dt = date('ymdHis');
            $notify_id= uniqid($dt);
            $remarks="accept of opp from manager module ";
    		$data21= array(
    			'notificationID' =>$notify_id,
    			'notificationShortText'=>'Opportunity Accepted',
    			'notificationText' =>'Opportunity Accepted by '.$getusername1.' given by '.$getusername,
    			'from_user'=>$get_fromuser,
    			'to_user'=>$given_data['userid'],
    			'action_details'=>'Opportunity',
    			'notificationTimestamp'=>$dt,
    			'read_state'=>0,
    			'remarks'=>$remarks,
    			'show_status'=>0
    		);
            array_push($insertArray1, $data21);

            if($get_fromuser!=""){
                    $get_fromuser= $this->opp_mgr->rej_opp_notification($insertArray1);
            }
            //---------------------------------------------------------end -----------------

            $update2 = $this->opp_common->map_opportunity(array(0 => $data2));

            if($update==true && $update1==true && $update2==true){
               return 1;
            }
        }
    }
//------Helper function to accept opportunity stage ownership----//
    private function accept_opp_stage($given_data)	{
    	$opp_stage= $this->opp_mgr->stage_manager_owner_status($given_data['opp_id']);
        $opp_status= $opp_stage[0]->stage_manager_owner_status;
        if($opp_status=='1'){
            $data= array(
               'stage_manager_owner_id'=>$given_data['userid'],
               'stage_manager_owner_status'=>2
            );
            $update = $this->opp_common->updateOpportunity($data, $given_data['opp_id']);

            $update1 = $this->opp_mgr->update_oppo_transaction($given_data['opp_id'], 'manager', 'stage');

            $data2= array(
                'mapping_id'=> uniqid(rand()),
                'opportunity_id'=>$given_data['opp_id'],
                'lead_cust_id'=> $given_data['lead_cust_id'],
                'from_user_id'=> $given_data['userid'],
                'to_user_id'=> $given_data['userid'],
                'cycle_id'=> $given_data['cycle_id'],
                'stage_id'=>$given_data['opportunity_stage'],
                'module'=> 'manager',
                'sell_type'=> $given_data['sell_type'],
                'timestamp'=> date('Y-m-d H:i:s'),
                'action' => 'stage accepted',
                'state' => '1'
            );
            $update2 = $this->opp_common->map_opportunity(array(0 => $data2));
            if($update==true && $update1==true && $update2==true){
                return 1;
            }
        }
    }
//-----Function to reject an opportunity-----//
    public function reject_opportunity() {
		if($this->session->userdata('uid')){
		    $user_id = $this->session->userdata('uid');
		    $json = file_get_contents("php://input");
		    $data=json_decode($json);

		    $op_id = $data->opportuniy_id;
		    $stage_id = $data->stage_id;
		    $opp_reject = $data->opp_reject;
		    $remarks = $data->remarks;
		    $status=array();
		    $is_first_stage = $this->opp_sales->is_first_stage($stage_id);
		    if ($is_first_stage == true) {

		    	if($opp_reject[0] == 'Ownership')
		    	{
			        $check_assign= $this->opp_mgr->assign_count($op_id, $remarks);
			        if($check_assign==true)
			        {
			            $status[0]=1;
			        }
		    	}
		    	else
		    	{
		    		if($opp_reject[1] == 'Stage_Ownership')
		    		{

						$check_assign_stage= $this->opp_mgr->assign_count_stage($op_id, $remarks);
						if($check_assign_stage==true)
						{
							$status[1]=1;
						}
		    		}
		    	}
		    } else {
		    	$check_state= $this->opp_mgr->check_state($op_id,$opp_reject);
			    if(isset($check_state['Ownership'])){
			        $check_assign= $this->opp_mgr->assign_count($op_id, $remarks);
			        if($check_assign==true){
			            $status[0]=1;
			        }
			    }
			    if(isset($check_state['Stage_Ownership'])){
					$check_assign_stage= $this->opp_mgr->assign_count_stage($op_id, $remarks);
					if($check_assign_stage==true){
			            $status[1]=1;
			        }
			    }
		    }
		    $insertArray1=array();

            //---------------- notification code----------------------------------
            $get_fromuser= $this->opp_mgr->get_from_userid($op_id);
            //to user---- is the one who is to be notified.
            // from user---- is the one who performs action
            $dt = date('ymdHis');
            $notify_id= uniqid($dt);
            $remarks="rejection of opp from manager module";
    		$data2= array(
    			'notificationID' =>$notify_id,
    			'notificationShortText'=>'Opportunity Rejected',
    			'notificationText' =>'Opportunity Rejected',
    			'from_user'=>$user_id,
    			'to_user'=>$get_fromuser,
    			'action_details'=>'Opportunity',
    			'notificationTimestamp'=>$dt,
    			'read_state'=>0,
    			'remarks'=>$remarks,
    			'show_status'=>0
    		);
            array_push($insertArray1, $data2);
            if($get_fromuser!=""){
                    $get_fromuser= $this->opp_mgr->rej_opp_notification($insertArray1);
            }
            //---------------------------------------------------------end -----------------


		    echo json_encode($status);
		} else {
			redirect('indexController');
		}
	}
	/*-=-=-=-=-=-=-=-=-=-=-=-UNASSIGNED OPPORTUNITIES-=-=-=-=-=-=-=-=-=-=-=-=-*/
//------Function to load view of unassigned opportunity------//
	public function unassigned_opportunities() {
		if($this->session->userdata('uid')){
			$user_id = $this->session->userdata('uid');
			$data = $this->opp_common->fetch_userPrivilages($user_id);
			$this->load->view('manager_opportunity_unassigned', $data);
		}else{
			redirect('indexController');
		}
	}
//----Function to get data for unassigned opportunity-----//
	public function get_unassigned_opportunities() {
		if($this->session->userdata('uid')){
			try {
				$user_id = $this->session->userdata('uid');
				$new = $this->opp_mgr->fetch_unassigned_opportunities($user_id);
				echo json_encode($new);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}
//----Function assign opportunity----//
	public function assign_opportunities() {
		if($this->session->userdata('uid')){
			try {
				$user = $this->session->userdata('uid');
				$json = file_get_contents("php://input");
				$data = json_decode($json);
                $oppo = $data->oppo;
                $leadsflow = $data->leadsflow;
                $opp_name = $oppo->opp_name;
                $opp_id = $oppo->opp_id;
                $btn_status = $oppo->btn_status;
			   	$userPackets = $data->users;
				$sales_module = false;
				$manager_module = false;

				$mapping_id = uniqid(rand());
				$insertArray = array();
				$insertArray1 = array();
				$userList = array();

				$is_first_stage = $this->opp_sales->is_first_stage($oppo->stage_id);

			    foreach ($userPackets as $packet) {
			    	array_push($userList, $packet->to_user_id);
		            if ($is_first_stage == true) {

                        if($btn_status== 'oppOwner'){
                            $data=array(
    							'mapping_id'=> $mapping_id,
    							'opportunity_id'=> $oppo->opp_id,
    							'lead_cust_id'=> $oppo->lead_cust_id,
    							'from_user_id'=> $user,
    							'to_user_id'=> $packet->to_user_id,
    							'cycle_id'=> $oppo->cycle_id,
    							'stage_id'=> $oppo->stage_id,
    							'module'=> $packet->module,
    							'sell_type'=> $oppo->sell_type,
    							'action' => 'ownership assigned',
    							'timestamp'=> date('Y-m-d H:i:s'),
    							'state'=> 1
						    );
						    array_push($insertArray, $data);
                        }
                        if($btn_status== 'stageOwner'){
                            $data=array(
    							'mapping_id'=> $mapping_id,
    							'opportunity_id'=> $oppo->opp_id,
    							'lead_cust_id'=> $oppo->lead_cust_id,
    							'from_user_id'=> $user,
    							'to_user_id'=> $packet->to_user_id,
    							'cycle_id'=> $oppo->cycle_id,
    							'stage_id'=> $oppo->stage_id,
    							'module'=> $packet->module,
    							'sell_type'=> $oppo->sell_type,
    							'action' => 'stage assigned',
    							'timestamp'=> date('Y-m-d H:i:s'),
    							'state'=> 1
						    );
                            array_push($insertArray, $data);
                        }

		            } else {
						$data=array(
							'mapping_id'=> $mapping_id,
							'opportunity_id'=> $oppo->opp_id,
							'lead_cust_id'=> $oppo->lead_cust_id,
							'from_user_id'=> $user,
							'to_user_id'=> $packet->to_user_id,
							'cycle_id'=> $oppo->cycle_id,
							'stage_id'=> $oppo->stage_id,
							'module'=> $packet->module,
							'sell_type'=> $oppo->sell_type,
							'timestamp'=> date('Y-m-d H:i:s'),
							'state'=> 1
						);
						if ($leadsflow == 'opportunity') {
							$data['action'] = 'ownership assigned';
						} else if ($leadsflow == 'stage') {
							$data['action'] = 'stage assigned';
						}
						array_push($insertArray, $data);
		            }
					if ($packet->module == 'sales')
						$sales_module = true;
					if ($packet->module == 'manager')
						$manager_module = true;
  			    }

  			    if ($is_first_stage == true) {
					if ($sales_module == true) {

                        if($btn_status== 'oppOwner'){
                            $updateData = array(
    							'owner_status' => 1,
    							'owner_id' => null
						    );
                        }
                        if($btn_status== 'stageOwner'){
                            $updateData = array(
                                'stage_owner_status' => 1,
    							'stage_owner_id' => null
						    );
                        }
						$this->opp_mgr->reassign_reset($opp_id,$updateData);
					} else if ($manager_module == true) {
						$updateData = array(
							'owner_manager_status' => 1,
							'stage_manager_owner_status' => 1
						);
						$this->opp_mgr->reassign_reset($opp_id,$updateData);
					}
  			    } else {
					if ($leadsflow=='opportunity') {
						if ($sales_module == true) {
							$updateData = array(
								'owner_status' => 1,
								'owner_id' => null
							);
							$this->opp_mgr->reassign_reset($opp_id,$updateData);
						} else if ($manager_module == true) {
							$updateData = array(
								'owner_manager_status' => 1
							);
							$this->opp_mgr->reassign_reset($opp_id,$updateData);
						}
					} else if ($leadsflow=='stage') {
		            	if ($sales_module == true) {
		            		$updateData = array(
		            			'stage_owner_status' => 1
		            		);
							$this->opp_mgr->reassign_reset($opp_id,$updateData);
		                } else if ($manager_module == true) {
		            		$updateData = array(
		            			'stage_manager_owner_status' => 1,
		            			'stage_manager_owner_id' => null
		            		);
							$this->opp_mgr->reassign_reset($opp_id,$updateData);
		                }
					}
  			    }

			   	$insert=$this->opp_common->map_opportunity($insertArray);
				//echo $insert;
				$msgbody = 'Opportunity - <strong>'.$opp_name.'</strong> has been assigned';
				$subject =  'Opportunity Assigned';
				$email = $this->lconnecttcommunication->send_email($userList,$subject,$msgbody);

                //---------------- notification code----------------------------------

                // from user---- is the one who performs action
                //to user---- is the one who is to be notified.
                if($btn_status== 'oppOwner'){
                  $notstr="Opportunity Ownership Assigned to" ;

                }
                if($btn_status== 'stageOwner'){
                  $notstr="Opportunity Stage Ownership Assigned to";
                }
                foreach ($userPackets as $packet) {
                        $dt = date('ymdHis');
                        $letter=chr(rand(97,122));
                        $letter.=chr(rand(97,122));
                        $notify_id1=$letter;
                        $notify_id1.=$dt;
                        $notify_id = uniqid($notify_id1);
                        $remarks="multiple assign of opp from manager module ";
                        $get_fromuser=$packet->to_user_id;
                        $getusername=$this->opp_mgr->getusernamae($get_fromuser);
                        $getusername1=$this->opp_mgr->getusernamae($user);
                		$data2= array(
                			'notificationID' =>$notify_id,
                			'notificationShortText'=>'Opportunity Assigned',
                			'notificationText' =>$notstr.' '.$getusername.' by '.$getusername1,
                			'from_user'=>"$user",
                			'to_user'=>$get_fromuser,
                			'action_details'=>'Opportunity',
                			'notificationTimestamp'=>$dt,
                			'read_state'=>0,
                			'remarks'=>$remarks,
                			'show_status'=>0
                		);
                        array_push($insertArray1, $data2);
                }
                $insert_notify= $this->opp_mgr->rej_opp_notification($insertArray1);
                //---------------------------------------------------------end -----------------

                echo json_encode($insert);

			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
		  redirect('indexController');
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-ASSIGNED OPPORTUNITIES-=-=-=-=-=-=-=-=-=-=-=-=-*/
//----Function to load view of assigned opportunities-----//
	public function assigned_opportunities() {
		if($this->session->userdata('uid')){
			$user_id = $this->session->userdata('uid');
			$data = $this->opp_common->fetch_userPrivilages($user_id);
			$this->load->view('manager_opportunity_assigned', $data);
		}else{
			redirect('indexController');
		}
	}
//----Function to get data of assigned opportunities----//
	public function get_assigned_opportunities($status) {
		if($this->session->userdata('uid')){
			try {
				$user_id = $this->session->userdata('uid');
				$new = $this->opp_mgr->fetch_assigned_opportunities($user_id,$status);
				echo json_encode($new);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}
//----Function to re-assign ownership----//

	public function reassign_opportunities() {
		//should reassign to only those who have not accepted
		if($this->session->userdata('uid')){
			try {
				$user = $this->session->userdata('uid');
				$json = file_get_contents("php://input");
				$data = json_decode($json);

				$opp_id = $data->opp_id;
				$lead_cust_id = $data->lead_cust_id;
				$cycle_id = $data->cycle_id;
				$stage_id = $data->stage_id;
				$sell_type = $data->sell_type;
				$userPackets = $data->users;
				$remarks = $data->remarks;
				$btn_status = $data->btn_status;

				$mapping_id = uniqid(rand());
				$stage_mapping_id = uniqid(rand());
				$insertArray = array();
				$sales_module = false;
				$manager_module = false;

				$userList = array();
				$is_first_stage = $this->opp_sales->is_first_stage($stage_id);

				foreach ($userPackets as $packet) {
					array_push($userList, $packet->to_user_id);
					if ($is_first_stage == true) {
						if($btn_status== 'oppOwner'){
							$data=array(
								'mapping_id'=> $mapping_id,
								'opportunity_id'=> $opp_id,
								'lead_cust_id'=> $lead_cust_id,
								'from_user_id'=> $user,
								'to_user_id'=> $packet->to_user_id,
								'cycle_id'=> $cycle_id,
								'stage_id'=> $stage_id,
								'module'=> $packet->module,
								'action'=> 'ownership reassigned',
								'sell_type'=> $sell_type,
								'timestamp'=> date('Y-m-d H:i:s'),
								'remarks'=> $remarks,
								'state'=>1
							);
							array_push($insertArray, $data);
						}
						if($btn_status== 'stageOwner'){
						$data=array(
							'mapping_id'=> $stage_mapping_id,
							'opportunity_id'=> $opp_id,
							'lead_cust_id'=> $lead_cust_id,
							'from_user_id'=> $user,
							'to_user_id'=> $packet->to_user_id,
							'cycle_id'=> $cycle_id,
							'stage_id'=> $stage_id,
							'module'=> $packet->module,
							'action'=> 'stage reassigned',
							'sell_type'=> $sell_type,
							'timestamp'=> date('Y-m-d H:i:s'),
							'remarks'=> $remarks,
							'state'=>1
						);
						array_push($insertArray, $data);
						}
					} else {
						$data=array(
							'mapping_id'=> $mapping_id,
							'opportunity_id'=> $opp_id,
							'lead_cust_id'=> $lead_cust_id,
							'from_user_id'=> $user,
							'to_user_id'=> $packet->to_user_id,
							'cycle_id'=> $cycle_id,
							'stage_id'=> $stage_id,
							'module'=> $packet->module,
							'action'=> 'ownership reassigned',
							'sell_type'=> $sell_type,
							'timestamp'=> date('Y-m-d H:i:s'),
							'remarks'=> $remarks,
							'state'=>1
						);
						array_push($insertArray, $data);
					}

					if ($packet->module == 'sales')
						$sales_module = true;
					if ($packet->module == 'manager')
						$manager_module = true;
					}

  			    if ($is_first_stage == true) {
					if ($sales_module == true) {

                        if($btn_status== 'oppOwner'){
                            $updateData = array(
    							'owner_status' => 1,
    							'owner_id' => null,
						    );
						$this->opp_mgr->update_oppo_transaction($opp_id,'sales', 'ownership'); //update all sales ownership rows
                        }

                        if($btn_status== 'stageOwner'){
                            $updateData = array(
                                'stage_owner_status' => 1,
    							'stage_owner_id' => null
						    );
						$this->opp_mgr->update_oppo_transaction($opp_id,'sales', 'stage'); //update all sales ownership rows
                        }
						$this->opp_mgr->reassign_reset($opp_id,$updateData);

					} else if ($manager_module == true) {
						if($btn_status== 'oppOwner'){
							$updateData = array(
								'owner_manager_status' => 1
							);
						}
						$this->opp_mgr->reassign_reset($opp_id,$updateData);
						$this->opp_mgr->update_oppo_transaction($opp_id,'manager', 'ownership'); //update all manager ownership rows
						if($btn_status== 'stageOwner'){
							$updateData = array(

								'stage_manager_owner_status' => 1
							);
						}
						$this->opp_mgr->update_oppo_transaction($opp_id,'manager', 'stage'); //update all manager ownership rows
					}
  			    } else {
					if ($sales_module == true) {
						$updateData = array(
							'owner_status' => 1,
							'owner_id' => null
						);
						$this->opp_mgr->reassign_reset($opp_id,$updateData);
						$this->opp_mgr->update_oppo_transaction($opp_id,'sales', 'ownership'); //update all sales ownership rows
					}
					if ($manager_module == true) {
						$updateData = array(
							'owner_manager_status' => 1
						);
						$this->opp_mgr->reassign_reset($opp_id,$updateData);
						$this->opp_mgr->update_oppo_transaction($opp_id,'manager', 'ownership'); //update all manager ownership rows
					}
  			    }

  			   	$insert=$this->opp_common->map_opportunity($insertArray);
				$msgbody = 'Opportunity has been Reassigned';
				$subject =  'Opportunity Reassigned';
				$email = $this->lconnecttcommunication->send_email($userList,$subject,$msgbody);


				//---------------- notification code----------------------------------

                // from user---- is the one who performs action
                //to user---- is the one who is to be notified.
                $insertArray1 = array();
                foreach ($userPackets as $packet) {
                        $dt = date('ymdHis');
                        $letter=chr(rand(97,122));
                        $letter.=chr(rand(97,122));
                        $notify_id1=$letter;
                        $notify_id1.=$dt;
                        $notify_id = uniqid($notify_id1);
                        $remarks="multiple assign of opp from manager module ";
                        $get_fromuser=$packet->to_user_id;
                        $getusername=$this->opp_mgr->getusernamae($get_fromuser);
                        $getusername1=$this->opp_mgr->getusernamae($user);
                		$data2= array(
                			'notificationID' =>$notify_id,
                			'notificationShortText'=>'Opportunity Re-Assigned',
                			'notificationText' =>'Opportunity Reassigned to '.$getusername.' by '.$getusername1,
                			'from_user'=>$user,
                			'to_user'=>$get_fromuser,
                			'action_details'=>'Opportunity',
                			'notificationTimestamp'=>$dt,
                			'read_state'=>0,
                			'remarks'=>$remarks,
                		);
                        array_push($insertArray1, $data2);
                }
                $insert_notify= $this->opp_mgr->rej_opp_notification($insertArray1);

                echo $insert;

			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
		  redirect('indexController');
		}
	}
//---Function to re-assign stage-ownership---//
	public function reassign_stages(){
		//should reassign to only those who have not accepted
		if($this->session->userdata('uid')){
			try {
				$user = $this->session->userdata('uid');
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$opp_id = $data->opp_id;
				$lead_cust_id = $data->lead_cust_id;
				$cycle_id = $data->cycle_id;
				$stage_id = $data->stage_id;
				$sell_type = $data->sell_type;
				$userPackets = $data->users;
				$remarks = $data->remarks;
				$mapping_id = uniqid(rand());
				$insertArray = array();
				$sales_module = false;
				$manager_module = false;
				foreach ($userPackets as $packet){
					$data=array(
						'mapping_id'=> $mapping_id,
						'opportunity_id'=> $opp_id,
						'lead_cust_id'=> $lead_cust_id,
						'from_user_id'=> $user,
						'to_user_id'=> $packet->to_user_id,
						'cycle_id'=> $cycle_id,
						'stage_id'=> $stage_id,
						'module'=> $packet->module,
						'action'=> 'stage reassigned',
						'sell_type'=> $sell_type,
						'timestamp'=> date('Y-m-d H:i:s'),
						'remarks'=> $remarks,
						'state'=>1
					);
					array_push($insertArray, $data);
					if ($packet->module == 'sales')
						$sales_module = true;
					if ($packet->module == 'manager')
						$manager_module = true;
				}
            	if ($sales_module == true){
            		$updateData = array(
            			'stage_owner_status' => 1,
            			'stage_owner_id' => null,
                        'opportunity_value' => null

            		);
					$this->opp_mgr->reassign_reset($opp_id,$updateData);
					$this->opp_mgr->update_oppo_transaction($opp_id,'sales', 'stage'); //update all sales ownership rows
                }
            	if ($manager_module == true){
            		$updateData = array(
            			'stage_manager_owner_status' => 1,
            			'stage_manager_owner_id' => null,
                        'opportunity_value' => null
            		);
					$this->opp_mgr->reassign_reset($opp_id,$updateData);
					$this->opp_mgr->update_oppo_transaction($opp_id,'manager', 'stage'); //update all sales ownership rows
                }
				$insert=$this->opp_common->map_opportunity($insertArray);
				//---------------- notification code----------------------------------

                // from user---- is the one who performs action
                //to user---- is the one who is to be notified.
                $insertArray1 = array();
                foreach ($userPackets as $packet) {
                        $dt = date('ymdHis');
                        $letter=chr(rand(97,122));
                        $letter.=chr(rand(97,122));
                        $notify_id1=$letter;
                        $notify_id1.=$dt;
                        $notify_id = uniqid($notify_id1);
                        $remarks="multiple assign of opp from manager module ";
                        $get_fromuser=$packet->to_user_id;
                        $getusername=$this->opp_mgr->getusernamae($get_fromuser);
                        $getusername1=$this->opp_mgr->getusernamae($user);
                		$data2= array(
                			'notificationID' =>$notify_id,
                			'notificationShortText'=>'Opportunity Stage Ownership Re-Assigned',
                			'notificationText' =>'Opportunity Stage Ownership Reassigned to '.$getusername.' by '.$getusername1,
                			'from_user'=>$user,
                			'to_user'=>$get_fromuser,
                			'action_details'=>'Opportunity',
                			'notificationTimestamp'=>$dt,
                			'read_state'=>0,
                			'remarks'=>$remarks,
                		);
                        array_push($insertArray1, $data2);
                }
                $insert_notify= $this->opp_mgr->rej_opp_notification($insertArray1);


				echo $insert;
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
		  redirect('indexController');
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-CLOSED OPPORTUNITIES-=-=-=-=-=-=-=-=-=-=-=-=-*/
//-----function to load view of Closed opportunities------//
	public function closed_opportunities() {
		if($this->session->userdata('uid')){
			$user_id = $this->session->userdata('uid');
			$data = $this->opp_common->fetch_userPrivilages($user_id);
            $this->load->view('manager_opportunity_closed', $data);

		}else{
			redirect('indexController');
		}
	}
    public function closed_lost_opportunities() {
		if($this->session->userdata('uid')){
			$user_id = $this->session->userdata('uid');
			$data = $this->opp_common->fetch_userPrivilages($user_id);
            $this->load->view('manager_opportunity_closed_lost', $data);

		}else{
			redirect('indexController');
		}
	}
//-----Function to get data for closed opportunities----//
	public function get_closed_opportunities($opt) {
		if($this->session->userdata('uid')){
			try {
				$user_id = $this->session->userdata('uid');
				$new = $this->opp_mgr->fetch_closed_opportunities($user_id,$opt);
				echo json_encode($new);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

    public function get_closed_lost_opportunities($opt) {
		if($this->session->userdata('uid')){
			try {
				$user_id = $this->session->userdata('uid');
				$new = $this->opp_mgr->fetch_closed_lost_opportunities($user_id,$opt);
				echo json_encode($new);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	/*-=-=-=-=-=-=-=-=-CREATE OPPORTUNITY PAGE METHODS-=-=-=-=-=-=-=-=-*/
//------Function to load view for create opportunity-----//
	public function createOpportunity($target='') {
		if($this->session->userdata('uid')) {
			if (($target == '') || (strtolower($target) != 'lead' && strtolower($target) != 'customer')) {
				$this->unassigned_opportunities();
			} else {
				$user_id = $this->session->userdata('uid');
				$data = $this->opp_common->fetch_userPrivilages($user_id);
				$data['target'] = $target;
				$this->load->view('manager_opportunity_create', $data);
			}
		}else{
			redirect('indexController');
		}
	}
//----Function to fetch details for create opportunity view-----//
	public function init($target) {
		if ($this->session->userdata('uid')) {
			try {
				$returnArray = array();
				$user_id = $this->session->userdata('uid');
				$sell_types = $this->opp_common->fetch_userPrivilages($user_id);
				$returnArray['sell_types'] = $sell_types;
				$returnArray['target'] = $target;
				if (strtolower($target) == 'lead') {
					$leads = $this->opp_mgr->fetch_Leads($user_id);
					$returnArray['leads'] = $leads;
				} else if (strtolower($target) == 'customer') {
					$customers = $this->opp_mgr->fetch_Customers($user_id);
					$returnArray['leads'] = $customers;
				}
				echo json_encode($returnArray);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
//------Function to get sales cycle for given details----//
	public function get_SalesCycle2() {
		if($this->session->userdata('uid')){
			$owner_id = $this->session->userdata('uid');
			$json = file_get_contents("php://input");
			$obj = json_decode($json);

			$opportunity_id = '';
			$dt = date('YmdHis');
			$opportunity_id .= $dt;
			$opportunity_id = uniqid($opportunity_id);
			$data['opportunity_id'] = $opportunity_id;
			$data['opportunity_name'] = $obj->opportunity_name;
			$data['target'] = $obj->target;
			$data['lead_cust_id'] = $obj->lead_cust_id;
			$data['opportunity_contact'] = implode(':',$obj->opportunity_contact);
			$data['product_id'] = $obj->product_list;
			$data['currency_id'] = $obj->currency_list;
			$data['industry_id'] = $obj->industry_list;
			$data['location_id'] = $obj->location_list;
			$data['opp_remarks'] = $obj->opp_remarks;
			$data['sell_type'] = $obj->sell_type;

			try {
				//$can_create_opp = $this->opp_common->validate_oppo_params($data);
				$can_create_opp = $this->opp_common->isValidOppName($data);
				if ($can_create_opp == 0) {
					$returnArray= array(
						'message' => 'An Opportunity with same name already exists.',
						'status'=>false,
						'qualifier'=>false);
					echo json_encode($returnArray);
					return ;
				}

				$data1 = $this->opp_common->fetch_SalesCycle_firstStage($data);
				if (count($data1) == 0) {
					$returnArray= array(
						'message' => 'No sales cycle found for the selected combination. Please contact admin.',
						'status'=>false,
						'qualifier'=>false);
					echo json_encode($returnArray);
					return ;
				}
				$data['cycle_id'] = $data1[0]->cycle_id;
				$data['stage_id'] = $data1[0]->stage_id;
				if ($data['stage_id'] == null) {
					$returnArray= array(
						'message' => 'Opportunity could not be created as Sales cycle does not have a proper stage. Contact admin.',
						'status'=>false,
						'qualifier'=>false);
					echo json_encode($returnArray);
					return ;
				}
				$salesStageAttributes = $this->opp_common->fetch_stageAttributes($data['cycle_id'], $data['stage_id']);
				$allocation_list_exists = false;
				$qualifier_exists = false;
				$allocation_list = '';
				foreach ($salesStageAttributes as $attribute) {
					if ($attribute->attribute_name == 'allocation_matrix') {
						$allocation_list_exists = true;
						$allocation_list = $attribute->attribute_value;
					}
					if ($attribute->attribute_name == 'qualifier') {
						$qualifier_exists = true;
					}
				}
				//if allocation list attribute is enabled for stage -
				if ($allocation_list_exists) {
					$allocation_list_users = explode(':', $allocation_list);
					//if my name is not in list - return an error saying you can't proceed
					if (in_array($owner_id, $allocation_list_users) == false) {
						$returnArray= array(
							'message' => 'You cannot create this opportunity as we could not find your name in Allocation list',
							'status' => false,
							'qualifier' => false
						);
						echo json_encode($returnArray);
						return ;
					}
				}
                else{
                    $returnArray= array(
							'message' => 'You cannot create this opportunity as Allocation List is not defined',
							'status' => false,
							'qualifier' => false
					);
					echo json_encode($returnArray);
					return ;
				}
				if ($qualifier_exists) {
					$qualifier_data = $this->opp_common->check_qualifiers($data['stage_id']);
					$returnArray= array(
						'message' => 'Answer this qualifier to proceed further.',
						'status' => false,
						'qualifier' => true,
						'qualifier_data' => $qualifier_data,
						'opp_data' => $data
					);
					echo json_encode($returnArray);
					return ;
				}
				echo $this->addOpportunity($data, $owner_id);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
//-----Function to add opportunity after answering qualifier-----//
	public function add_opp_final()	{
		if($this->session->userdata('uid')){
			$owner_id = $this->session->userdata('uid');
			$json = file_get_contents("php://input");
			$obj = json_decode($json);

			$data['opportunity_id']	= $obj->opportunity_id;
			$data['opportunity_name'] = $obj->opportunity_name;
			$data['target'] = $obj->target;
			$data['lead_cust_id'] = $obj->lead_cust_id;
			$data['opportunity_contact'] = implode(':',$obj->opportunity_contact);
			$data['product_id'] = $obj->product_list;
			$data['currency_id'] = $obj->currency_list;
			$data['industry_id'] = $obj->industry_list;
			$data['location_id'] = $obj->location_list;
			$data['opp_remarks'] = $obj->opp_remarks;
			$data['sell_type'] = $obj->sell_type;
			$data['cycle_id'] = $obj->cycle_id;
			$data['stage_id'] = $obj->stage_id;

			try {
				// check if qualifier passed for given opportunity...
				$has_passed_qualifier = $this->opp_common->check_qualifier_passed($data['opportunity_id']);
				if ($has_passed_qualifier == true) {
					echo $this->addOpportunity($data, $owner_id);
					return ;
				} else {
					$returnArray= array(
						'message' => 'Could not create opportunity as you have not passed the qualifier',
						'status'=>false,
						'qualifier'=>false);
					echo json_encode($returnArray);
				}
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
//-----Main function to adds opportunity-------//
	private function addOpportunity($data, $owner_id) {
		try {
			$opportunity_id = $data['opportunity_id'];
			$opportunity_name = $data['opportunity_name'];
			$lead_id = $data['lead_cust_id'];
			$lead_contact = $data['opportunity_contact'];
			$product_id = $data['product_id'];
			$currency_id = $data['currency_id'];
			$industry_id = $data['industry_id'];
			$location_id = $data['location_id'];
			$sell_type = $data['sell_type'];
			$opp_remarks = $data['opp_remarks'];
			/* what's to be inserted to the opportunity_details table
				'owner_manager_status' => 2, //ownership manager has accepted
				'stage_manager_owner_status' => 2, //stage manager has accepted
			*/
			$insert_data = array(
				'opportunity_id' => $opportunity_id,
				'opportunity_name' => $opportunity_name,
				'lead_cust_id' => $lead_id,
				'opportunity_product' => $product_id,
				'opportunity_currency' => $currency_id,
				'opportunity_industry' => $industry_id,
				'opportunity_location' => $location_id,
				'opportunity_contact' => $lead_contact,
				'opportunity_stage' => $data['stage_id'],
				'cycle_id' => $data['cycle_id'],
				'created_by' => $owner_id,
				'created_timestamp' => date('Y-m-d H:i:s'),
				'manager_owner_id' => $owner_id,
				'owner_manager_status' => 2,
				'stage_manager_owner_id' => $owner_id,
				'stage_manager_owner_status' => 2,
				'owner_status' => 0,
				'stage_owner_status' => 0,
				'sell_type' => $sell_type
			);
			$insertArray = array();
			array_push($insertArray, array(
				'mapping_id'=> uniqid(rand()),
				'opportunity_id'=> $opportunity_id,
				'lead_cust_id'=> $lead_id,
				'from_user_id'=> $owner_id,
				'to_user_id'=> $owner_id,
				'cycle_id'=> $data['cycle_id'],
				'stage_id' => $data['stage_id'],
				'module'=> 'manager',
				'action'=> 'created',
				'sell_type'=> $sell_type,
				'timestamp'=> date('Y-m-d H:i:s'),
				'remarks'=> $opp_remarks,
				'state'=>0
			));
			array_push($insertArray, array(
				'mapping_id'=> uniqid(rand()),
				'opportunity_id'=> $opportunity_id,
				'lead_cust_id'=> $lead_id,
				'from_user_id'=> $owner_id,
				'to_user_id'=> $owner_id,
				'cycle_id'=> $data['cycle_id'],
				'stage_id'=> $data['stage_id'],
				'module'=> 'manager',
				'action'=> 'ownership accepted',
				'sell_type'=> $sell_type,
				'timestamp'=> date('Y-m-d H:i:s'),
				'remarks'=> null,
				'state'=>1
			));
			array_push($insertArray, array(
				'mapping_id'=> uniqid(rand()),
				'opportunity_id'=> $opportunity_id,
				'lead_cust_id'=> $lead_id,
				'from_user_id'=> $owner_id,
				'to_user_id'=> $owner_id,
				'cycle_id'=> $data['cycle_id'],
				'stage_id'=> $data['stage_id'],
				'module'=> 'manager',
				'action'=> 'stage accepted',
				'sell_type'=> $sell_type,
				'timestamp'=> date('Y-m-d H:i:s'),
				'remarks'=> null,
				'state'=>1
			));

			$insert_product = array(
				'opp_prod_id' => uniqid(),
				'opportunity_id' => $opportunity_id,
				'product_id' => $product_id,
				'quantity' => null,
				'amount' => null,
				'timestamp' => date('Y-m-d H:i:s'),
				'remarks' => null
			);

			$status = $this->opp_common->add_opportunityBasic($insert_data);
			if ($status == FALSE) {
				$returnArray= array('message' => 'Something went wrong. Could not create opportunity. Please try again.', 'status'=>false, 'qualifier'=>false);
				echo json_encode($returnArray);
				return ;
			}else {
				$insert=$this->opp_common->map_opportunity($insertArray); /* what's to be inserted into oppo_user_map */
				$insert = $this->opp_common->map_opp_products(array(0 => $insert_product));
			}
			$returnArray= array('message' => '', 'status'=>true, 'opportunity_id'=>$opportunity_id);
			echo json_encode($returnArray);
		} catch (LConnectApplicationException $e) {
			echo $this->exceptionThrower($e);
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-OPP STAGE DETAIL VIEW-=-=-=-=-=-=-=-=-=-=-=-=-*/
//-------Function to load view of opportunity details------//
	public function stage_view($opp_id,$str)    {
		if($this->session->userdata('uid')){
			try {

				$user_id = $this->session->userdata('uid');
				$data['opportunity_id'] = $opp_id;
				$data['user_id'] = $user_id;
                $data['typeofpage']=$str;
				$this->load->view('manager_opportunity_stageview', $data);
			} catch (LConnectApplicationException $e) {
				$GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
				$errorArray = array(
					'errorCode' => $e->getErrorCode(),
					'errorMsg' => $e->getErrorMessage()
				);
				$GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
				$GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
				$this->session->set_flashdata('errors',$errorArray);
				redirect($_SERVER['HTTP_REFERER'], $errorArray);
			}
		}else{
			redirect('indexController');
		}
	}
//------Helper function to upload files----//
	private function upload_file($given_data)	{
		$file_data = $this->opp_common->oppo_file_upload($given_data);
		if (count($file_data['errors']) > 0) {
			echo json_encode(array('errors' => $file_data['errors'], 'status' => false));
			return 0;
		}
		if (count($file_data['docs']) > 0) {
			$this->opp_common->batch_doc_upload($file_data['docs']);
		}
		return 1;
	}

	// close the opportunity
	public function close_opportunity() {
		if($this->session->userdata('uid')){
			$given_data = array(
				'files' => $_FILES,
				'stage_value' 		=> $this->input->post('stage_value'),
				'stage_numbers' 	=> $this->input->post('stage_numbers'),
				'stage_closed_date' => $this->input->post('stage_closed_date'),
				'stage_rate'		=> $this->input->post('stage_rate'),
				'stage_score'		=> $this->input->post('stage_score'),
				'stage_customer_code'=>$this->input->post('stage_customer_code'),
				'stage_priority'	=> $this->input->post('stage_priority'),
				'stage_remarks' 	=> $this->input->post('stage_remarks'),
				'opportunity_id' 	=> $this->input->post('opportunity_id'),
				'lead_cust_id' 		=> $this->input->post('lead_cust_id'),
				'stage_id' 			=> $this->input->post('stage_id'),
				'cycle_id' 			=> $this->input->post('cycle_id'),
				'sell_type' 		=> $this->input->post('sell_type'),
				'mapping_id'		=> uniqid(rand()),
				'user_id'			=> $this->session->userdata('uid'),
				'close_status' 		=> $this->input->post('close_status_select'),
				'tempdate'	 		=> $this->input->post('tempdate'),
			);
			if (($given_data['stage_closed_date'] == '0000-00-00') || ($given_data['stage_closed_date'] == '')) {
				$given_data['stage_closed_date'] = null;
			}
			if (($given_data['tempdate'] == '0000-00-00') || ($given_data['tempdate'] == '')) {
				$given_data['tempdate'] = null;
			}
		    if (isset($_POST['close_lead_cust'])) {
				$given_data['lead_cust_close'] = $this->input->post('close_lead_cust');
		    } else {
		    	$given_data['lead_cust_close'] = 'off';
		    }
            if(isset($_POST['close_activity'])){
           	    $given_data['close_activity'] = $this->input->post('close_activity');
		    } else {
		    	$given_data['close_activity'] = 'off';
		    }
			try {
				$upload = $this->upload_file($given_data);
				if ($upload == 0) {
					return;
				}
				$file_check = $this->opp_sales->check_files_for_stage($given_data['stage_id'], $given_data['opportunity_id']);
				if ($file_check == true) {
					$error = array('error' => 'Document upload is mandatory to proceed further', 'name' => '');
					echo json_encode(array('errors' => array(0=>$error), 'status' => false));
					return ;
				}
				$log_attr_data = array(
					'mapping_id' => $given_data['mapping_id'],
					'opportunity_id'=> $given_data['opportunity_id'],
					'stage_id'=> $given_data['stage_id'],
					'user_id'=> $given_data['user_id'],
					'opp_value'=> $given_data['stage_value'],
					'opp_numbers'=> $given_data['stage_numbers'],
					'opp_close_date'=> $given_data['stage_closed_date'],
					'oppo_rate' => $given_data['stage_rate'],
					'oppo_score' => $given_data['stage_score'],
					'oppo_customer_code' => $given_data['stage_customer_code'],
					'oppo_priority' => $given_data['stage_priority'],
					'timestamp'=> date('Y-m-d H:i:s'),
					'remarks'=> $given_data['stage_remarks']
				);
				$this->opp_common->log_attr($log_attr_data);

				$oppo_attr = $this->opp_common->fetch_oppoAttr($given_data['opportunity_id']);
				if ($oppo_attr == null) {
					return ;
				}
				$changed_attr = array();
				if ($oppo_attr[0]->numbers != $given_data['stage_numbers']) {
					$changed_attr['opportunity_numbers'] = $given_data['stage_numbers'];
				}
				if ($oppo_attr[0]->close_date != $given_data['stage_closed_date']) {
					$changed_attr['opportunity_date'] = $given_data['stage_closed_date'];
				}
				if ($oppo_attr[0]->value != $given_data['stage_value']) {
					$changed_attr['opportunity_value'] = $given_data['stage_value'];
				}
				if ($oppo_attr[0]->rate != $given_data['stage_rate']) {
					$changed_attr['opportunity_rate'] = $given_data['stage_rate'];
				}
				if ($oppo_attr[0]->score != $given_data['stage_score']) {
					$changed_attr['opportunity_score'] = $given_data['stage_score'];
				}
				if ($oppo_attr[0]->customer_code != $given_data['stage_customer_code']) {
					$changed_attr['opportunity_customer_code'] = $given_data['stage_customer_code'];
				}
				if ($oppo_attr[0]->priority != $given_data['stage_priority']) {
					$changed_attr['opportunity_priority'] = $given_data['stage_priority'];
				}
				if ($oppo_attr[0]->stage != $given_data['stage_id']) {
					$changed_attr['opportunity_stage'] = $given_data['stage_id'];
				}
				$given_data['currency_id'] = $oppo_attr[0]->currency;
				$changed_attr['closed_reason'] = $given_data['close_status'];
				$changed_attr['closed_status'] = '100';
				$changed_attr['opportunity_approach_date'] = $given_data['tempdate'];
				$this->opp_common->updateOpportunity($changed_attr, $given_data['opportunity_id']);

				$log_trans_data = array(
					'mapping_id' => $given_data['mapping_id'],
					'opportunity_id'=> $given_data['opportunity_id'],
					'lead_cust_id' => $given_data['lead_cust_id'],
					'from_user_id'=> $given_data['user_id'],
					'to_user_id'=> $given_data['user_id'],
					'cycle_id' => $given_data['cycle_id'],
					'stage_id' => $given_data['stage_id'],
					'module' => 'manager',
					'timestamp'=> date('Y-m-d H:i:s'),
					'sell_type' => $given_data['sell_type'],
					'remarks' => $given_data['stage_remarks'],
				);
				if ($given_data['close_status']=="closed_won") {
					$log_trans_data['action'] = 'closed won';
				} else if ($given_data['close_status']=="temporary_loss") {
					$log_trans_data['action'] = 'temporary loss';
				} else if ($given_data['close_status']=="permanent_loss") {
					$log_trans_data['action'] = 'permanent loss';
				}
				$this->opp_common->map_opportunity(array(0 => $log_trans_data));

                 /**********************Close the Activity related to opportunity********************/
                  $username=$this->opp_mgr->getusernamae($this->session->userdata('uid'));
                if($given_data['close_activity']=='on')
                {
                   $this->opp_common->close_activity($given_data['opportunity_id'],'opportunity',$username);
                }
                /*********************end of****************************************************************/

				if ($given_data['sell_type'] == 'new_sell') {
					$lead_closed_status = $this->opp_common->lead_closed_status($given_data['lead_cust_id']);
					if($given_data['close_status'] == 'closed_won') {
						if ($lead_closed_status != '2') { //lead is not closed_win so go ahead and update it
							//create customer
							$customer_id = ''.date('YmdHis');
							$customer_id = uniqid($customer_id);
							$customer_add_status = $this->opp_sales->create_customer($given_data['lead_cust_id'], $customer_id);
							if ($customer_add_status == false) {
								$error = array('error' => 'Opportunity Updated. Failed to create Customer. Try uploading via Excel.', 'name' => '');
								echo json_encode(array('errors' => array(0=>$error), 'status' => false));
								return ;
								//---------------------------------------------------------------
							}
							$this->opp_sales->insert_prod_purchase_info($customer_id, $given_data);
							//update lead as closed won
							$lead_update_data = array(
								'customer_id' 		=> $customer_id,
								'lead_status' 		=> 2,
								'lead_closed_reason'=> $given_data['close_status'],
								'lead_updated_by' 	=> $given_data['user_id'],
								'lead_updated_time' => date('Y-m-d H:i:s')
							);
							$this->opp_sales->update_lead($lead_update_data, $given_data['lead_cust_id']);
							//insert into lead_cust_user_map
							$log_lead_close = array(
								'lead_cust_id' 	=> $given_data['lead_cust_id'],
								'from_user_id' 	=> $given_data['user_id'],
								'to_user_id'	=> $given_data['user_id'],
								'module' 		=> 'manager',
								'action'		=> 'closed',
								'timestamp' 	=> date('Y-m-d H:i:s'),
								'state' 		=> 1,
								'type' 			=> 'lead',
								'mapping_id' 	=> $given_data['mapping_id'],
							);
							$insert = $this->opp_sales->log_lead($log_lead_close);
							$log_lead_close = array(
								'lead_cust_id' 	=> $customer_id,
								'from_user_id' 	=> $given_data['user_id'],
								'to_user_id'	=> $given_data['user_id'],
								'module' 		=> 'manager',
								'action'		=> 'created',
								'timestamp' 	=> date('Y-m-d H:i:s'),
								'state' 		=> 0,
								'type' 			=> 'customer',
								'mapping_id' 	=> $given_data['mapping_id']."1",
							);
							$insert = $this->opp_sales->log_lead($log_lead_close);
						} else {
							// get customer id from given lead id
							$customer_id = $this->opp_sales->get_customer_for_lead($given_data['lead_cust_id']);
							// insert into product purchase info from given customer id
							$this->opp_sales->insert_prod_purchase_info($customer_id, $given_data);
						}
					} else { // it is a temporary loss or a permanent loss
						if($given_data['lead_cust_close'] == 'on') {
							if (($lead_closed_status == '2') || ($lead_closed_status == '3') || ($lead_closed_status == '4')) {
								$error = array('error' => 'Lead already closed. Just closed the opportunity', 'name' => '');
								echo json_encode(array('errors' => array(0=>$error), 'status' => false));
								return ;
							}
							$active_oppos = $this->opp_common->active_oppos($given_data['opportunity_id'], $given_data['lead_cust_id']);
							if($active_oppos == true)	{
								//sorry you can't
								$error = array('error' => 'Lead cannot be closed as there are other active opportunities on this Lead.', 'name' => '');
								echo json_encode(array('errors' => array(0=>$error), 'status' => false));
								return ;
							} else {
								// there are no active opportunities so go ahead and close it

								/*--------------- CLOSE ALL ACTIVITIES ON LEAD AS WELL??---------------*/

								$lead_update_data = array(
									'lead_closed_reason'=> $given_data['close_status'],
									'lead_approach_date'=> $given_data['tempdate'],
									'lead_updated_by' 	=> $given_data['user_id'],
									'lead_updated_time' => date('Y-m-d H:i:s')
								);
								if ($given_data['close_status'] == 'temporary_loss'){
									$lead_update_data['lead_status'] = '3';
								} else if ($given_data['close_status'] == 'permanent_loss') {
									$lead_update_data['lead_status'] = '4';
								}
								$update_lead_status = $this->opp_sales->update_lead($lead_update_data, $given_data['lead_cust_id']);
								//lead_closed to lead_cust_user_map
								$log_lead_close = array(
									'lead_cust_id'	=> $given_data['lead_cust_id'],
									'from_user_id'	=> $given_data['user_id'],
									'to_user_id'	=> $given_data['user_id'],
									'module' 		=> 'manager',
									'action' 		=> 'closed',
									'timestamp' 	=> date('Y-m-d H:i:s'),
									'state' 		=> 1,
									'type' 			=> 'lead',
									'mapping_id'	=> $given_data['mapping_id'],
								);
								$insert = $this->opp_sales->log_lead($log_lead_close);
							}
						}
					}
				} else {
					if($given_data['close_status'] == 'closed_won') {
						// insert into product purchase info
						$this->opp_sales->insert_prod_purchase_info($given_data['lead_cust_id'], $given_data);
					}
				}
				echo json_encode(array('errors' => array(), 'status' => true));
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}

    /********** reopening of opp************************************************************/
    public function changestate(){

      $json = file_get_contents("php://input");
      $data = json_decode($json);

      if($this->session->userdata('uid')){
			try {
                    $login_user=$this->session->userdata('uid');
                    $opp_data= $this->opp_sales->stage_owner($data->opportunity_id); // gets all data from opportunity details
                    $opportunity_stage=$opp_data[0]->opportunity_stage;
                    $cycle_id=$opp_data[0]->cycle_id;
                    $opp_usermap_data = array(
      					'mapping_id' => uniqid(rand()),
      					'opportunity_id'=> $data->opportunity_id,
      					'lead_cust_id' => $data->lead_cust_id,
      					'from_user_id'=> $login_user,
      					'to_user_id'=> $login_user,
      					'cycle_id' => $cycle_id,
      					'stage_id' => $opportunity_stage,
      					'module' => 'manager',
                          'action'=>$data->lossType,
      					'timestamp'=> date('Y-m-d H:i:s'),
      					'sell_type' => 'new_sell',
      					'state' => 1,
      					'remarks' => $data->remarks
      			);
                  $lead_closed_status = $this->opp_common->lead_closed_status($opp_usermap_data['lead_cust_id']);
                  $extradata=array();
                  $updateoppdetails=array();
                  $extradata['leadclosedstatus']=$lead_closed_status;
                  $extradata['lead_cust_name']=$data->lead_cust_name;
                  $extradata['remarks']=$data->remarks;
                  $extradata['lead_cust_name']=$data->lead_cust_name;
                  $extradata['opportunity_name']=$data->opportunity_name;
                  $extradata['remarks']=$data->remarks;

                  $opp_data= $this->opp_sales->stage_owner($data->opportunity_id); // gets all data from opportunity details
                  $stage_owner_id=$opp_data[0]->stage_owner_id;
                  $getusername=$this->opp_mgr->getusernamae($stage_owner_id);
                  $stage_manager_owner_id=$opp_data[0]->stage_manager_owner_id;
                  $loginusername=$this->opp_mgr->getusernamae($login_user);
                  $extradata['stage_owner_id'] = $stage_owner_id;
                  $extradata['stage_manager_owner_id'] = $stage_manager_owner_id;
                  $extradata['manager_owner_id'] = $opp_data[0]->manager_owner_id;
                  $extradata['stage_owner_name'] = $getusername;
                  $extradata['loggedin_username'] = $loginusername;

                  $log_attr_data = array(
      					'mapping_id' => $opp_usermap_data['mapping_id'],
      					'opportunity_id'=> $opp_usermap_data['opportunity_id'],
      					'stage_id'=> $opp_data[0]->opportunity_stage,
      					'user_id'=> $login_user,
      					'opp_close_date'=> $opp_data[0]->opportunity_date,
                        'opp_numbers'=> $stage_manager_owner_id,
      					'oppo_rate' => $opp_data[0]->opportunity_rate,
      					'oppo_score' => $opp_data[0]->opportunity_score,
      					'oppo_customer_code' => $opp_data[0]->opportunity_customer_code,
      					'oppo_priority' => $opp_data[0]->opportunity_priority,
      					'timestamp'=> date('Y-m-d H:i:s'),
      					'remarks'=>"changed state to ".$opp_usermap_data['action']." from ".$opp_data[0]->closed_reason
      				);

                  if($opp_usermap_data['action']=='reopen')
                  {
                    	$updateoppdetails['closed_reason'] = NULL;
      				$updateoppdetails['closed_status'] = '0';
                      $updateoppdetails['opportunity_approach_date'] = NULL;

                  }else{
                            $updateoppdetails['closed_reason'] = $opp_usermap_data['action'];
                            $updateoppdetails['closed_status'] = '100';
                            $updateoppdetails['opportunity_approach_date'] = $data->date;
                            if ($opp_usermap_data['action']=="temporary_loss") {
                                $extradata['title']=$data->title;
                                $extradata['futureActivity']=$data->futureActivity;
                                $extradata['activityDuration']=$data->activityDuration;
                                $extradata['alertBefore']=$data->alertBefore;
                                $extradata['contactType']=$data->contactType;
                                $extradata['approachdate']=$data->date;
                             //   $extradata['contact_id']=$data->contact_id;
            					$opp_usermap_data['action'] = 'temporary loss';
            				} else if ($opp_usermap_data['action']=="permanent_loss") {
            					$opp_usermap_data['action'] = 'permanent loss';
            				}
                  }

                  if($opp_usermap_data['action']=='')
                    {
                       echo"action is blank";
                       exit;
                    }

                  $insert = $this->opp_mgr->changestate($opp_usermap_data,$updateoppdetails,$extradata);
                  if(!$insert)
                          {
                              echo json_encode(array('errors' => 'Opportunity could not be reopened,Please try again', 'status' => false));

                          }else if('$insert'=='2'){
                              echo json_encode('Cannot change state of opportunity,since the Lead is in Permanent Loss State');
                          }else{
                              $this->opp_common->log_attr($log_attr_data);
                              echo json_encode('Opportunity State Changed successfully');
                          }
            }catch (LConnectApplicationException $e){
				echo $this->exceptionThrower($e);
			}
        }else{
			redirect('indexController');
		}
    }

     /********** Reopen in loop state of closed lost opp************************************************************/
    public function reopenbulk(){

      $json = file_get_contents("php://input");
      $data = json_decode($json);
      //print_r ($data);

      if($this->session->userdata('uid')){
	  try {
                  foreach($data as $reopenarray)
                  {
                      $extradata=array();
                      $updateoppdetails=array();
                      $login_user=$this->session->userdata('uid');
                      $opp_data= $this->opp_sales->stage_owner($reopenarray->opportunity_id); // gets all data from opportunity details
                      $opportunity_stage=$opp_data[0]->opportunity_stage;
                      $cycle_id=$opp_data[0]->cycle_id;
                      $opp_usermap_data = array(
        					'mapping_id' => uniqid(rand()),
        					'opportunity_id'=> $reopenarray->opportunity_id,
        					'lead_cust_id' => $reopenarray->lead_cust_id,
        					'from_user_id'=> $login_user,
        					'to_user_id'=> $login_user,
        					'cycle_id' => $cycle_id,
        					'stage_id' => $opportunity_stage,
        					'module' => 'manager',
                            'action'=>'reopen',
        					'timestamp'=> date('Y-m-d H:i:s'),
        					'sell_type' => 'new_sell',
        					'state' => 1,
        					'remarks' => $reopenarray->remarks
        			);
                    $opp_data= $this->opp_sales->stage_owner($reopenarray->opportunity_id); // gets all data from opportunity details
                    $stage_owner_id=$opp_data[0]->stage_owner_id;
                    $owner_id=$opp_data[0]->owner_id;
                    $manager_owner_id=$opp_data[0]->manager_owner_id;
                    $stage_manager_owner_id=$opp_data[0]->stage_manager_owner_id;

                    $log_attr_data = array(
        					'mapping_id' => $opp_usermap_data['mapping_id'],
        					'opportunity_id'=> $opp_usermap_data['opportunity_id'],
        					'stage_id'=> $opp_data[0]->opportunity_stage,
        					'user_id'=> $login_user,
        					'opp_close_date'=> $opp_data[0]->opportunity_date,
                            'opp_numbers'=> $stage_manager_owner_id,
        					'oppo_rate' => $opp_data[0]->opportunity_rate,
        					'oppo_score' => $opp_data[0]->opportunity_score,
        					'oppo_customer_code' => $opp_data[0]->opportunity_customer_code,
        					'oppo_priority' => $opp_data[0]->opportunity_priority,
        					'timestamp'=> date('Y-m-d H:i:s'),
        					'remarks'=>"changed state to ".$opp_usermap_data['action']." from ".$opp_data[0]->closed_reason
        				);
                    $lead_closed_status = $this->opp_common->lead_closed_status($opp_usermap_data['lead_cust_id']);

                    $extradata['leadclosedstatus']=$lead_closed_status;
                    $extradata['lead_cust_name']=$reopenarray->lead_cust_name;
                    $extradata['remarks']=$reopenarray->remarks;
                    $extradata['opportunity_name']=$reopenarray->opportunity_name;


                    $updateoppdetails['closed_reason'] = NULL;
        			$updateoppdetails['closed_status'] = '0';
                    $updateoppdetails['opportunity_approach_date'] = NULL;

                    $getusername=$this->opp_mgr->getusernamae($stage_owner_id);
                    $extradata['stage_owner_id'] = $stage_owner_id;
                    $extradata['stage_owner_name'] = $getusername;
                    $extradata['manager_owner_id'] = $manager_owner_id;
                    $extradata['stage_manager_owner_id'] = $stage_manager_owner_id;

                    $insert = $this->opp_sales->reopen($opp_usermap_data,$updateoppdetails,$extradata);
                    if($insert)
                    {
                      $this->opp_common->log_attr($log_attr_data);
                    }
                 }// end of foreach

                  if(!$insert)
                  {
                       echo json_encode(array('errors' => 'Opportunity could not be reopened,Please try again', 'status' => false));
                      // echo"Opportunity could not be reopened,Please try again";
                        return;
                  }else{

                        echo json_encode("Selected Opportunity Reopened successfully");
                       // echo json_encode(array('errors' => 'Opportunity State Changed successfully', 'status' => true));
                        return;
                  }

              }catch (LConnectApplicationException $e){
    				echo $this->exceptionThrower($e);
    		  }

        }else{
			redirect('indexController');
		}
    }

}
?>
