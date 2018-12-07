<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_opportunitiesController');

class sales_opportunitiesController extends Master_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('sales_opportunitiesModel','opp_sales');
		$this->load->model('manager_opportunitiesModel','opp_mgr');
		$this->load->model('common_opportunitiesModel','opp_common');
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

	/*-=-=-=-=-=-=-=-=-=-=-=-NEW OPPORTUNITIES-=-=-=-=-=-=-=-=-=-=-=-=-*/
//----Load new opportunity view---//
	public function new_opportunities() {
		if($this->session->userdata('uid')){
			$user_id = $this->session->userdata('uid');
			$data = $this->opp_common->fetch_userPrivilages($user_id);
			$this->load->view('sales_opportunity_new', $data);
		}else{
			redirect('indexController');
		}
	}
//----get data for new opportunity view---//
	public function get_new_opportunities() {
		if($this->session->userdata('uid')){
			try {
				$user_id = $this->session->userdata('uid');
				$new = $this->opp_sales->fetch_new_opportunities($user_id);
				echo json_encode($new);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}
//----Accept Stage Ownership/Ownership of new opportunity---//
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
            $given_data['opportunity_stage'] = $data->opportunity_stage;
            $given_data['cycle_id']= $data->cycle_id;
            $given_data['mapping_id'] = uniqid(rand());
            // get if its the first stage
            // if yes, accept both stage and ownership
            // if no, accept any of two

            //$is_first_stage = $this->opp_sales->is_first_stage($given_data['opportunity_stage']);

            if($given_data['opp_owner']=='ownership'){
           	    echo $this->accept_opp_ownership($given_data);
            } else if($given_data['opp_owner']=='stage') {
           	    echo $this->accept_opp_stage($given_data);
            }
            /*if ($is_first_stage == true) {
            	$own_accept = $this->accept_opp_ownership($given_data);
            	$stg_accept = $this->accept_opp_stage($given_data);
            	if ($own_accept == 1 && $stg_accept == 1) {
            		echo 1;
            	}
            } else {
	            if($given_data['opp_owner']=='ownership'){
	            	echo $this->accept_opp_ownership($given_data);
	            } else if($given_data['opp_owner']=='stage') {
	            	echo $this->accept_opp_stage($given_data);
	            }
            }*/
        } catch (LConnectApplicationException $e) {
    		echo $this->exceptionThrower($e);
    	}
    }
//-----Helper function for accept_opportunity()-----//
    private function accept_opp_ownership($given_data) {
        $opp_ownership= $this->opp_sales->opp_owner($given_data['opp_id']);
        $opp_status= $opp_ownership[0]->owner_status;
        $repotmgr=$this->opp_sales->getrepmgr($given_data['userid']);
        //if($opp_status=='1'){
            $data1= array(
               'owner_id'=>$given_data['userid'],
               'oppowner'=>$given_data['userid'],
               'owner_status'=>2
            );
            $data2= array(
                'mapping_id' => uniqid(rand()),
                'opportunity_id' => $given_data['opp_id'],
                'lead_cust_id' => $given_data['lead_cust_id'],
                'from_user_id' => $given_data['userid'],
                'to_user_id' => $given_data['userid'],
                'cycle_id' => $given_data['cycle_id'],
                'stage_id' =>$given_data['opportunity_stage'],
                'module' => 'sales',
                'sell_type' => $given_data['sell_type'],
                'timestamp' => date('Y-m-d H:i:s'),
                'action' => 'ownership accepted',
                'state' => '1'
            );

             $insertArray1=array();

            //---------------- notification code----------------------------------
            $get_fromuser= $this->opp_sales->get_from_userid1($given_data['opp_id'],$given_data['userid']);
            //to user---- is the one who is to be notified.
            // from user---- is the one who performs action
            $dt = date('ymdHis');
            $notify_id= uniqid($dt);
            $remarks="accept of opp from executive module ";
            $getusername=$this->opp_mgr->getusernamae($get_fromuser);
            $getusername1=$this->opp_mgr->getusernamae($given_data['userid']);
    		$data21= array(
    			'notificationID' =>$notify_id,
    			'notificationShortText'=>'Opportunity Ownership Accepted',
    			'notificationText' =>'Opportunity Ownership Accepted by '.$getusername1.' given by '.$getusername,
    			'from_user'=>$given_data['userid'],
    			'to_user'=>$get_fromuser,
    			'action_details'=>'Opportunity',
    			'notificationTimestamp'=>$dt,
    			'read_state'=>0,
    			'remarks'=>$remarks,
    			'show_status'=>0
    		);
            //array_push($insertArray1, $data2);
            if($get_fromuser!=""){
                 $get_fromuser= $this->opp_sales->rej_opp_notification($data21);
            }

            //---------------------------------------------------------end -----------------

            $update = $this->opp_sales->accept_opp($given_data['opp_id'],$data1);
            $update2 = $this->opp_sales->update_transaction($given_data['opp_id']);
            $update1 = $this->opp_sales->insert_transaction($data2);
            if($update==true && $update1==true && $update2==true){
                return 1;
            }
       // }
    }
//------Helper function for accept_opportunity() for accepting stage ownership----//
    private function accept_opp_stage($given_data)	{
    	$opp_stage= $this->opp_sales->stage_owner($given_data['opp_id']);
        $opp_status= $opp_stage[0]->stage_owner_status;
        $opp_value=$opp_stage[0]->opportunity_value;
        $stage_manager_owner_id=$opp_stage[0]->stage_manager_owner_id;

        //if($opp_status=='1' || $opp_status=='0'){
            $repotmgr=$this->opp_sales->getrepmgr($given_data['userid']);
            if($opp_value=='onlyexe'){
                $data1= array(
                    'stage_owner_id'=>$given_data['userid'],
                    'stage_owner_status'=>2,
                    'stage_manager_owner_id'=>$repotmgr
                );
            }else{
                $data1= array(
                    'stage_owner_id'=>$given_data['userid'],
                    'stage_owner_status'=>2
                );

            }
            if($opp_value=='onlyexe'){
                $data2= array(
                  'mapping_id'=> uniqid(rand()),
                  'opportunity_id'=>$given_data['opp_id'],
                  'lead_cust_id'=> $given_data['lead_cust_id'],
                  'from_user_id'=> $repotmgr,
                  'to_user_id'=> $given_data['userid'],
                  'cycle_id'=> $given_data['cycle_id'],
                  'stage_id'=>$given_data['opportunity_stage'],
                  'module'=> 'sales',
                  'sell_type'=> $given_data['sell_type'],
                  'timestamp'=> date('Y-m-d H:i:s'),
                  'action' => 'stage accepted',
                  'state' => '1'
              );
            }else{
                $data2= array(
                  'mapping_id'=> uniqid(rand()),
                  'opportunity_id'=>$given_data['opp_id'],
                  'lead_cust_id'=> $given_data['lead_cust_id'],
                  'from_user_id'=> $given_data['userid'],
                  'to_user_id'=> $given_data['userid'],
                  'cycle_id'=> $given_data['cycle_id'],
                  'stage_id'=>$given_data['opportunity_stage'],
                  'module'=> 'sales',
                  'sell_type'=> $given_data['sell_type'],
                  'timestamp'=> date('Y-m-d H:i:s'),
                  'action' => 'stage accepted',
                  'state' => '1'
              );

            }
            $update = $this->opp_sales->accept_opp($given_data['opp_id'],$data1);
            $update2 = $this->opp_sales->update_stage_transaction($given_data['opp_id']);
            $update1 = $this->opp_sales->insert_transaction($data2);

            //---------------- notification code----------------------------------
            $get_fromuser= $this->opp_sales->get_from_userid1($given_data['opp_id'],$given_data['userid']);
            //to user---- is the one who is to be notified.
            // from user---- is the one who performs action
            $dt = date('ymdHis');
            $notify_id= uniqid($dt);
            $remarks="accept of opp from executive module ";
            $getusername=$this->opp_mgr->getusernamae($get_fromuser);
            $getusername1=$this->opp_mgr->getusernamae($given_data['userid']);
    		$data21= array(
    			'notificationID' =>$notify_id,
    			'notificationShortText'=>'Opportunity Stage Accepted',
    			'notificationText' =>'Opportunity Stage Accepted by '.$getusername1.' given by '.$getusername,
    			'from_user'=>$given_data['userid'],
    			'to_user'=>$get_fromuser,
    			'action_details'=>'Opportunity',
    			'notificationTimestamp'=>$dt,
    			'read_state'=>0,
    			'remarks'=>$remarks,
    			'show_status'=>0
    		);
            //array_push($insertArray1, $data2);
            if($get_fromuser!=""){
                 $get_fromuser= $this->opp_sales->rej_opp_notification($data21);
            }

            //---------------------------------------------------------end -----------------

            if($update==true && $update1==true && $update2==true){
                return 1;
            }
        //}
    }
//------Reject ownership/Stage Ownership if an opportunity-----//
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

		    $get_fromuser = "";
		    $is_first_stage = $this->opp_sales->is_first_stage($stage_id);

		    if ($is_first_stage == true) {

		    	if($opp_reject[0] == 'Ownership')
		    	{
			        $check_assign= $this->opp_sales->assign_count($op_id);
			        if($check_assign==true)
			        {
			            $status[0]=1;
			        }

			        //---------------- notification code----------------------------------
		            $get_fromuser= $this->opp_sales->get_from_userid($op_id);
		            //to user---- is the one who is to be notified.
		            // from user---- is the one who performs action
		            $dt = date('ymdHis');
		            $notify_id= uniqid($dt);
		            $remarks="reject of opp from executive module";
		            $getusername=$this->opp_mgr->getusernamae($get_fromuser);
		            $getusername1=$this->opp_mgr->getusernamae($user_id);
		    		$data2= array(
		    			'notificationID' =>$notify_id,
		    			'notificationShortText'=>'Opportunity Rejected',
		    			'notificationText' =>'Opportunity Rejected  by '.$getusername1.' given by '.$getusername ,
		    			'from_user'=>$user_id,
		    			'to_user'=>$get_fromuser,
		    			'action_details'=>'Opportunity',
		    			'notificationTimestamp'=>$dt,
		    			'read_state'=>0,
		    			'remarks'=>$remarks,
		    		);

		    		if($get_fromuser!=""){
                		$get_fromuser= $this->opp_sales->rej_opp_notification($data2);
           			 }
		    	}
                if($opp_reject[1] == 'Stage_Ownership')
		    	{

						$check_assign_stage= $this->opp_sales->assign_count_stage($op_id);
						if($check_assign_stage==true)
						{
							$status[1]=1;
						}

						//---------------- notification code----------------------------------
						$get_fromuser= $this->opp_sales->get_from_userid($op_id);
						//to user---- is the one who is to be notified.
						// from user---- is the one who performs action
						$dt = date('ymdHis');
						$notify_id= uniqid($dt);
						$remarks="reject of opp from executive module";
						$getusername=$this->opp_mgr->getusernamae($get_fromuser);
						$getusername1=$this->opp_mgr->getusernamae($user_id);
						$data2= array(
						'notificationID' =>$notify_id,
						'notificationShortText'=>'Opportunity Stage Rejected',
						'notificationText' =>'Opportunity Stage Rejected  by '.$getusername1.' given by '.$getusername ,
						'from_user'=>$user_id,
						'to_user'=>$get_fromuser,
						'action_details'=>'Opportunity',
						'notificationTimestamp'=>$dt,
						'read_state'=>0,
						'remarks'=>$remarks,
						);
		    	}

		    	if($get_fromuser!=""){
                        $get_fromuser= $this->opp_sales->rej_opp_notification($data2);
           		}


		    }
		    else {
		    	$check_state= $this->opp_sales->check_state($op_id,$opp_reject);
			    if(isset($check_state['Ownership'])){
			        $check_assign= $this->opp_sales->assign_count($op_id);
			        if($check_assign==true){
			            $status[0]=1;
			        }

				    //---------------- notification code----------------------------------
		            $get_fromuser= $this->opp_sales->get_from_userid($op_id);
		            //to user---- is the one who is to be notified.
		            // from user---- is the one who performs action
		            $dt = date('ymdHis');
		            $notify_id= uniqid($dt);
		            $remarks="reject of opp from executive module";
		            $getusername=$this->opp_mgr->getusernamae($get_fromuser);
		            $getusername1=$this->opp_mgr->getusernamae($user_id);
		    		$data2= array(
		    			'notificationID' =>$notify_id,
		    			'notificationShortText'=>'Opportunity Rejected',
		    			'notificationText' =>'Opportunity Rejected  by '.$getusername1.' given by '.$getusername ,
		    			'from_user'=>$user_id,
		    			'to_user'=>$get_fromuser,
		    			'action_details'=>'Opportunity',
		    			'notificationTimestamp'=>$dt,
		    			'read_state'=>0,
		    			'remarks'=>$remarks,
		    		);

		    		if($get_fromuser!=""){
                		$get_fromuser= $this->opp_sales->rej_opp_notification($data2);
           			 }

			    }
			    if(isset($check_state['Stage_Ownership'])){
					$check_assign_stage= $this->opp_sales->assign_count_stage($op_id);
					if($check_assign_stage==true){
			            $status[1]=1;
			        }

	        		    //---------------- notification code----------------------------------
		            $get_fromuser= $this->opp_sales->get_from_userid($op_id);
		            //to user---- is the one who is to be notified.
		            // from user---- is the one who performs action
		            $dt = date('ymdHis');
		            $notify_id= uniqid($dt);
		            $remarks="reject of opp from executive module";
		            $getusername=$this->opp_mgr->getusernamae($get_fromuser);
		            $getusername1=$this->opp_mgr->getusernamae($user_id);
		    		$data2= array(
		    			'notificationID' =>$notify_id,
		    			'notificationShortText'=>'Opportunity Stage Rejected',
		    			'notificationText' =>'Opportunity Stage Rejected  by '.$getusername1.' given by '.$getusername ,
		    			'from_user'=>$user_id,
		    			'to_user'=>$get_fromuser,
		    			'action_details'=>'Opportunity',
		    			'notificationTimestamp'=>$dt,
		    			'read_state'=>0,
		    			'remarks'=>$remarks,
		    		);

					if($get_fromuser!=""){
						$get_fromuser= $this->opp_sales->rej_opp_notification($data2);
					}
			    }
		    }

            //---------------------------------------------------------end -----------------
		    echo json_encode($status);

		} else {
			redirect('indexController');
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=ACCEPTED OPPORTUNITIES-=-=-=-=-=-=-=-=-=-=-=-=-*/
//------Load in progress opportunities for view----//
	public function inprogress_opportunities() {
		if($this->session->userdata('uid')){
			$user_id = $this->session->userdata('uid');
			$data = $this->opp_common->fetch_userPrivilages($user_id);
			$this->load->view('sales_opportunity_inprogress', $data);
		}else{
			redirect('indexController');
		}
	}
//-----Get Data for in progress opprtunities----//
	public function get_inprogress_opportunities() {
		if($this->session->userdata('uid')){
			try {
				$user_id = $this->session->userdata('uid');
				$new = $this->opp_sales->fetch_inprogress_opportunities($user_id);
				echo json_encode($new);
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-CLOSED OPPORTUNITIES-=-=-=-=-=-=-=-=-=-=-=-=-*/
//------Load view for closed opportunities------//
	public function closed_opportunities() {
		if($this->session->userdata('uid')){
			try{
				$user_id = $this->session->userdata('uid');
				$data = $this->opp_common->fetch_userPrivilages($user_id);
				$this->load->view('sales_opportunity_closed', $data);
			 } catch (LConnectApplicationException $e){
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}
    public function closed_lost_opportunities() {
		if($this->session->userdata('uid')){
			try{
				$user_id = $this->session->userdata('uid');
				$data = $this->opp_common->fetch_userPrivilages($user_id);
				$this->load->view('sales_opportunity_closed_lost', $data);
			 } catch (LConnectApplicationException $e){
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}
//----get dATA for closed oppoertunities view----//
	public function get_closed_opportunities() {
		if($this->session->userdata('uid')){
			try {
				$user_id = $this->session->userdata('uid');
				$new = $this->opp_sales->fetch_closed_opportunities($user_id);
				echo json_encode($new);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}
    // for temp loss and permanent loss
    public function get_closed_lost_opportunities() {
		if($this->session->userdata('uid')){
			try {
				$user_id = $this->session->userdata('uid');
				$new = $this->opp_sales->fetch_closed_lost_opportunities($user_id);
				echo json_encode($new);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	/*-=-=-=-=-=-=-=-=--=-=-=-=CREATE OPPORTUNITY PAGE-=-=-=-=-=-=-=-=-=-=-=-=-*/
//----Load opportunity create view------//
	public function createOpportunity($target='') {
		if($this->session->userdata('uid')) {
			if (($target == '') || (strtolower($target) != 'lead' && strtolower($target) != 'customer')) {
				$this->new_stages();
			} else {
				try {
					$user_id = $this->session->userdata('uid');
					$data = $this->opp_common->fetch_userPrivilages($user_id);
					$data['target'] = $target;
					$this->load->view('sales_opportunity_create', $data);
				} catch (LConnectApplicationException $e) {
					echo $this->exceptionThrower($e);
				}
			}
		}else{
			redirect('indexController');
		}
	}
//-----Initialize opportunity create view with details------//
	public function init($target) {
		if ($this->session->userdata('uid')) {
			try {
				$returnArray = array();
				$user_id = $this->session->userdata('uid');
				$sell_types = $this->opp_common->fetch_userPrivilages($user_id);
				$returnArray['sell_types'] = $sell_types;
				$returnArray['target'] = $target;
				if (strtolower($target) == 'lead')	{
					$leads = $this->opp_sales->fetch_leads_sales($user_id);
					$returnArray['leads'] = $leads;
				}
				else if (strtolower($target) == 'customer') {
					$customers = $this->opp_sales->fetch_customers_sales($user_id);
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
//-------Get Sales Cycle for given parameter details-------//
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
			$data['manager_id'] = '';
			$isManager = $this->session->userdata('manager');
			$isSales = $this->session->userdata('sales');
			if (($isManager != '-') && ($isSales != '-')) { //he has both manager and sales
				$data['manager_id'] = $owner_id;
			} else {
				$data['manager_id'] = $this->session->userdata('reporting_to');
			}

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
							'message' => 'You cannot create this opportunity as we could not find your name in allocation list',
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
				echo $this->opp_sales->addOpportunity($data, $owner_id);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
//------Add opportunity after answering qualifier------//
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
			$data['manager_id'] = '';
			$isManager = $this->session->userdata('manager');
			$isSales = $this->session->userdata('sales');
			if (($isManager != '-') && ($isSales != '-')) { //he has both manager and sales
				$data['manager_id'] = $owner_id;
			} else {
				$data['manager_id'] = $this->session->userdata('reporting_to');
			}

			try {
				// check if qualifier passed for given opportunity...
				$has_passed_qualifier = $this->opp_common->check_qualifier_passed($data['opportunity_id']);
				if ($has_passed_qualifier == true) {
					echo $this->opp_sales->addOpportunity($data, $owner_id);
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

	/*-=-=-=-=-=-=-=-=-=-=-=-OPP STAGE DETAIL VIEW-=-=-=-=-=-=-=-=-=-=-=-=-*/
	// load view of opportunity details
	public function stage_view($opp_id) {
		if($this->session->userdata('uid')){
			try {
				$user_id = $this->session->userdata('uid');
				// check if opp_id exists in opportunity_details and user can view (canUpdate) it.
				// throw exception if not
				$data['opportunity_id'] = $opp_id;
				$data['user_id'] = $user_id;
				$this->load->view('sales_opportunity_stageview', $data);
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
		} else {
			redirect('indexController');
		}
	}
//-----Check for opportunity acceptance----//
	public function check_for_opp()	{
		if($this->session->userdata('uid')){
			$owner_id = $this->session->userdata('uid');
			$json = file_get_contents("php://input");
			$obj = json_decode($json);
			try {
				$opportunity_id = $obj->opportunity_id;
				$user_id = $obj->user_id;
				$data = $this->opp_sales->new_opp_details($opportunity_id, $user_id);
				echo json_encode($data);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
//------Helper function to upload file----//
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

	// save entered attributes of opportunities
	public function update_opportunity() {
		if($this->session->userdata('uid')){
			try {
				$given_data = array(
					'files' 		=> $_FILES,
					'stage_closed_date' => $this->input->post('stage_closed_date'),
					'stage_value' 	=> $this->input->post('stage_value'),
					'stage_numbers' => $this->input->post('stage_numbers'),
					'stage_rate'	=> $this->input->post('stage_rate'),
					'stage_score'	=> $this->input->post('stage_score'),
					'stage_customer_code'=>$this->input->post('stage_customer_code'),
					'stage_priority'=> $this->input->post('stage_priority'),
					'stage_remarks' => $this->input->post('stage_remarks'),
					'opportunity_id'=> $this->input->post('opportunity_id'),
					'lead_cust_id' 	=> $this->input->post('lead_cust_id'),
					'stage_id' 		=> $this->input->post('stage_id'),
					'cycle_id' 		=> $this->input->post('cycle_id'),
					'sell_type' 	=> $this->input->post('sell_type'),
					'mapping_id'	=> uniqid(rand()),
					'user_id'		=> $this->session->userdata('uid'),
					'contact_list'	=> $this->input->post('contact_list')
				);
				if (($given_data['stage_closed_date'] == '0000-00-00') || ($given_data['stage_closed_date'] == '')) {
					$given_data['stage_closed_date'] = null;
				}
				$upload = $this->upload_file($given_data);
				if ($upload == 0) {
					return;
				}
                $opp_data= $this->opp_sales->stage_owner($given_data['opportunity_id']); // gets all data from opportunity details
                $stage_manager_owner_id=$opp_data[0]->stage_manager_owner_id;
				$log_attr_data = array(
					'mapping_id' => $given_data['mapping_id'],
					'opportunity_id'=> $given_data['opportunity_id'],
					'stage_id'=> $given_data['stage_id'],
					'user_id'=> $given_data['user_id'],
                    'opp_numbers'=>$stage_manager_owner_id,
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
					return false;
				}
				$changed_attr = array();
				/*if ($oppo_attr[0]->numbers != $given_data['stage_numbers']) {
					$changed_attr['opportunity_numbers'] = $given_data['stage_numbers'];
				}*/
				if ($oppo_attr[0]->close_date != $given_data['stage_closed_date']) {
					$changed_attr['opportunity_date'] = $given_data['stage_closed_date'];
				}
			   /*	if ($oppo_attr[0]->value != $given_data['stage_value']) {
					$changed_attr['opportunity_value'] = $given_data['stage_value'];
				}*/
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
				$this->opp_common->updateOpportunity($changed_attr, $given_data['opportunity_id']);

				$log_trans_data = array(
					'mapping_id' => $given_data['mapping_id'],
					'opportunity_id'=> $given_data['opportunity_id'],
					'lead_cust_id' => $given_data['lead_cust_id'],
					'from_user_id'=> $given_data['user_id'],
					'to_user_id'=> $given_data['user_id'],
					'cycle_id' => $given_data['cycle_id'],
					'stage_id' => $given_data['stage_id'],
					'module' => 'sales',
					'action' => 'updated',
					'timestamp'=> date('Y-m-d H:i:s'),
					'sell_type' => $given_data['sell_type'],
					'remarks' => $given_data['stage_remarks'],
				);
				$updateStatus=$this->opp_common->map_opportunity(array(0 => $log_trans_data));
				$finalArray = array('errors' => array(), 'status' => $updateStatus);
				echo(json_encode($finalArray));
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	// conditions to check before progressing an opportunity
    public function progress_check(){
    	try {
	        $json = file_get_contents("php://input");
	        $data = json_decode($json);
	        $stage_id=$data->stage_id;
	        $next_stage_id=$data->next_stage_id;
	        $opportunity_id = $data->opp_id;
	        $finalArray = array();
	        $qualifier_data = $this->opp_common->check_qualifiers($next_stage_id);
	        $finalArray['qualifier'] = $qualifier_data;
	        $files_data = $this->opp_sales->check_files_for_stage($stage_id,$opportunity_id);
	        $finalArray['fileCheck'] = $files_data;
	        echo json_encode($finalArray);
    	} catch (LConnectApplicationException $e) {
    		echo $this->exceptionThrower($e);
    	}
    }

    public function getqualifierdata(){
    	try {
	        $json = file_get_contents("php://input");
	        $data = json_decode($json);
	        $quaid=$data->quaid;
	        $quamapid=$data->quamapid;
	        $qualifier_data = $this->opp_common->check_qualifiers1($quaid,$quamapid);
	        $finalArray['qualifier'] = $qualifier_data;

	        echo json_encode($finalArray);
    	} catch (LConnectApplicationException $e) {
    		echo $this->exceptionThrower($e);
    	}
    }

    // this function is called while moving stages
	public function process_opp($type='') {
		//returns -
		if (($type == '') || (strtolower($type) != 'progress' && strtolower($type) != 'approve' && strtolower($type) != 'reject')) {
			$error = array('error' => 'Something went wrong. Please try again', 'name' => '');
			echo json_encode(array('errors' => array(0=>$error), 'status' => false));
			return;
		}
		if($this->session->userdata('uid')){
			try {
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
					'contact_list'		=> $this->input->post('contact_list')
				);
				if (($given_data['stage_closed_date'] == '0000-00-00') || ($given_data['stage_closed_date'] == '')) {
					$given_data['stage_closed_date'] = null;
				}

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



				if ($type == 'progress') {
					//fetch all data for given opp_id and call progress opportunity function
					$array = $this->progress_opportunity($given_data);
					echo json_encode($array);
					return ;
				}
				if ($type == 'reject') {
					$array = $this->get_reject_stage($given_data);
					echo json_encode($array);
					return ;
				}
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

	// progress opportunity to next stage
	public function progress_opportunity($given_data) {
		try {

		    $proceed=0;
            $stgownby=false;
            $ownr=$status="";
			$next_stage_id1 = $this->opp_sales->fetch_nextStage($given_data['stage_id'], $given_data['cycle_id']);

			if ($next_stage_id1 == null) {
				$error = array('error' => 'Something went wrong in fetching next stage. Please try again later', 'name' => '');
				echo json_encode(array('errors' => array(0=>$error), 'status' => false));
				return;
			}
			$next_stage_id = $next_stage_id1[0]->stage_id;
            if(intval($next_stage_id1[0]->seq_no)>6){
                $pre_stage_id=intval($next_stage_id1[0]->seq_no)-1;
                // fetch opp owner
                $getoppowner=$this->opp_sales->getowner($given_data['opportunity_id'],'oppowner');
                $getoppowner_nm=$this->opp_mgr->getusernamae($getoppowner);

                $salesStageAttributes = $this->opp_common->fetch_stageAttributes($given_data['cycle_id'], $next_stage_id);
      			$allocation_list_exists = false;
      			$allocation_list = '';
      			foreach ($salesStageAttributes as $attribute) {
      				if ($attribute->attribute_name == 'allocation_matrix') {
      					$allocation_list_exists = true;
      					$allocation_list = $attribute->attribute_value;
      				}
      			}
                if ($allocation_list_exists == true) {
				    $allocation_list_users = explode(':', $allocation_list);
                    if(intval($next_stage_id1[0]->seq_no)==7){
    				    if (in_array($getoppowner, $allocation_list_users) == false){

                                    $insert=$getoppowner;

                                   if (in_array($given_data['user_id'], $allocation_list_users) == false){
                                        $status = $this->opp_sales->assign_allocation_list($allocation_list_users, $given_data,'');
                                   }else{
                                        $proceed=1;
                                   }

                        }else{

                                   $stgownby= $this->opp_sales->change_stage_owner_to_owner($given_data['opportunity_id'],'');
                                   $ownr=$getoppowner_nm;
                                   $proceed=1;
                        }
                    }else{
                            if (in_array($getoppowner, $allocation_list_users) == false){
                                // fetch the previous stage owner
                                $getprestgown=$this->opp_sales->getowner($given_data['opportunity_id'],'prestgowner');

                                $getoppowner_nm=$this->opp_mgr->getusernamae($getprestgown);
                                if (in_array($getprestgown, $allocation_list_users) == false){

                                    $status = $this->opp_sales->assign_allocation_list($allocation_list_users, $given_data,'');
                                    $proceed=1;
                                }else{

                                        $stgownby = $this->opp_sales->change_stage_owner_to_owner($given_data['opportunity_id'],$getprestgown);
                                        $proceed=1;
                                }

                            }else{
                                    $stgownby = $this->opp_sales->change_stage_owner_to_owner($given_data['opportunity_id'],$getoppowner);
                                    $proceed=1;

                            }
                    }
                }else{

                    //$getstgemnager=$this->opp_sales->getowner($given_data['opportunity_id'],'stagemanager');
                    $status = $this->opp_sales->assign_allocation_list("", $given_data,'yes');
                    $proceed=1;

                }

            }
            $sentnc='';
            if($status <> ''){
                $sentnc='opp assigned to following people '.$status;
            }

            if($proceed==1 || intval($next_stage_id1[0]->seq_no)==7){
                    $oppo_attr = $this->opp_common->fetch_oppoAttr($given_data['opportunity_id']);
        			if ($oppo_attr == null) {
        				return ;
        			}
        			$changed_attr = array();
        			/*if ($oppo_attr[0]->numbers != $given_data['stage_numbers']) {
        				$changed_attr['opportunity_numbers'] = $given_data['stage_numbers'];
        			}*/
        			if ($oppo_attr[0]->close_date != $given_data['stage_closed_date']) {
        				$changed_attr['opportunity_date'] = $given_data['stage_closed_date'];
        			}
        			/*if ($oppo_attr[0]->value != $given_data['stage_value']) {
        				$changed_attr['opportunity_value'] = $given_data['stage_value'];
        			}*/
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
        			if ($oppo_attr[0]->stage != $next_stage_id) {
        				$changed_attr['opportunity_stage'] = $next_stage_id;
        			}

                    $opp_data= $this->opp_sales->stage_owner($given_data['opportunity_id']); // gets all data from opportunity details
                    $stage_manager_owner_id=$opp_data[0]->stage_manager_owner_id;
                    $opportunity_numbers=$opp_data[0]->opportunity_numbers;
                    if($opportunity_numbers == null){$opportunity_numbers="";}
                    $sq_str="";
                    if($stage_manager_owner_id == null){
                        $sq_str=$opportunity_numbers.",".$next_stage_id1[0]->seq_no;
                        $changed_attr['opportunity_numbers']=$sq_str;
                    }

        			$this->opp_common->updateOpportunity($changed_attr, $given_data['opportunity_id']);

                    $log_attr_data = array(
    					'mapping_id' => $given_data['mapping_id'],
    					'opportunity_id'=> $given_data['opportunity_id'],
    					'stage_id'=> $given_data['stage_id'],
    					'user_id'=> $given_data['user_id'],
    					'opp_numbers'=>$stage_manager_owner_id,
    					'opp_close_date'=> $given_data['stage_closed_date'],
    					'oppo_rate' => $given_data['stage_rate'],
    					'oppo_score' => $given_data['stage_score'],
    					'oppo_customer_code' => $given_data['stage_customer_code'],
    					'oppo_priority' => $given_data['stage_priority'],
    					'timestamp'=> date('Y-m-d H:i:s'),
    					'remarks'=> $given_data['stage_remarks']
    				);
				    $this->opp_common->log_attr($log_attr_data);

        			$log_trans_data = array(
        				'mapping_id' => $given_data['mapping_id'],
        				'opportunity_id'=> $given_data['opportunity_id'],
        				'lead_cust_id' => $given_data['lead_cust_id'],
        				'from_user_id'=> $given_data['user_id'],
        				'to_user_id'=> $given_data['user_id'],
        				'cycle_id' => $given_data['cycle_id'],
        				'stage_id' => $given_data['stage_id'],
        				'module' => 'sales',
        				'action' => 'stage progressed',
        				'timestamp'=> date('Y-m-d H:i:s'),
        				'sell_type' => $given_data['sell_type'],
        				'remarks' => $given_data['stage_remarks'],
        			);
        			$insert=$this->opp_common->map_opportunity(array(0 => $log_trans_data)); //log into transaction table as stage changed

        			$msgbody = '<strong> Your Opportunity has been Progressed.</strong> <br> Remarks - '.$given_data['stage_remarks'];
        			$subject = 'Opportunity Progressed';

            }
            //$email = $this->lconnecttcommunication->send_email($given_data['contact_list'],$subject,$msgbody);
            $array =  array('errors' => array(), 'status' => $insert,'stgownby'=>$sentnc);
			return $array;
		} catch (LConnectApplicationException $e) {
			echo $this->exceptionThrower($e);
		}
	}

	// reject to a prior stage and to that stage owner directly
	public function get_reject_stage($given_data) {
		try {
			$prevStage = $this->opp_sales->fetch_reject_stage($given_data['stage_id']);
			if ($prevStage == null) {
				$error = array('error' => 'Something went wrong in fetching predefined stage.', 'name' => '');
				echo json_encode(array('errors' => array(0=>$error), 'status' => false));
				return;
			}
			$old_stage_owner = $this->opp_sales->fetch_owner_stage($prevStage, $given_data['opportunity_id']);

			//$old_stage_manger_owner = $this->opp_sales->fetch_reporting_manager($old_stage_owner);
			//$old_stage_manger_owner = $old_stage_manger_owner[0]->manager_id;

			$oppo_attr = $this->opp_common->fetch_oppoAttr($given_data['opportunity_id']);
			if ($oppo_attr == null) {
				return ;
			}
			$changed_attr = array();
			/*if ($oppo_attr[0]->numbers != $given_data['stage_numbers']) {
				$changed_attr['opportunity_numbers'] = $given_data['stage_numbers'];
			}*/
			if ($oppo_attr[0]->close_date != $given_data['stage_closed_date']) {
				$changed_attr['opportunity_date'] = $given_data['stage_closed_date'];
			}
			/*if ($oppo_attr[0]->value != $given_data['stage_value']) {
				$changed_attr['opportunity_value'] = $given_data['stage_value'];
			}*/
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
			if ($oppo_attr[0]->stage != $prevStage) {
				$changed_attr['opportunity_stage'] = $prevStage;
			}
			$changed_attr['stage_owner_id'] = $old_stage_owner;
			//$changed_attr['stage_manager_owner_id'] = $old_stage_manger_owner;
			$this->opp_common->updateOpportunity($changed_attr, $given_data['opportunity_id']);


			$log_trans_data = array(
				'mapping_id' => $given_data['mapping_id'],
				'opportunity_id'=> $given_data['opportunity_id'],
				'lead_cust_id' => $given_data['lead_cust_id'],
				'from_user_id'=> $given_data['user_id'],
				'to_user_id'=> $given_data['user_id'],
				'cycle_id' => $given_data['cycle_id'],
				'stage_id' => $given_data['stage_id'],
				'module' => 'sales',
				'action' => 'rejected',
				'timestamp'=> date('Y-m-d H:i:s'),
				'sell_type' => $given_data['sell_type'],
				'remarks' => $given_data['stage_remarks'],
			);
			$insert=$this->opp_common->map_opportunity(array(0 => $log_trans_data)); //log into transaction table as stage changed

			return array('errors' => array(), 'status' => $insert);
		} catch (LConnectApplicationException $e) {
			echo $this->exceptionThrower($e);
		}
	}

	// close the opportunity  for closed won
	public function close_opportunity() {
		if($this->session->userdata('uid')){
			$given_data = array(
				'files' => $_FILES,
				'stage_value' 		 => $this->input->post('stage_value'),
				'stage_numbers' 	 => $this->input->post('stage_numbers'),
				'stage_closed_date'  => $this->input->post('stage_closed_date'),
				'stage_rate'		 => $this->input->post('stage_rate'),
				'stage_score'		 => $this->input->post('stage_score'),
				'stage_customer_code'=> $this->input->post('stage_customer_code'),
				'stage_priority'	 => $this->input->post('stage_priority'),
				'stage_remarks' 	 => $this->input->post('stage_remarks'),
				'opportunity_id' 	 => $this->input->post('opportunity_id'),
				'lead_cust_id' 		 => $this->input->post('lead_cust_id'),
				'stage_id' 			 => $this->input->post('stage_id'),
				'cycle_id' 			 => $this->input->post('cycle_id'),
				'sell_type' 		 => $this->input->post('sell_type'),
				'mapping_id'		 => uniqid(rand()),
				'user_id'			 => $this->session->userdata('uid'),
				'close_status' 		 => 'closed_won',
				'tempdate'	 		 => $this->input->post('tempdate'),  //Remind me on Date
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

				#BUG ID -
				// $owner_check = $this->opp_common->check_lead_owner_opp_owner($given_data['opportunity_id'], $given_data['lead_cust_id']);
				// if (($given_data['close_status'] == 'closed_won') && ($owner_check != true)) {
				// 	$error = array('error' => 'Lead Rep Owner and Opportunity Owner are not the same. Kindly request the manager to reassign the lead to opportunity owner', 'name' => '');
				// 	echo json_encode(array('errors' => array(0=>$error), 'status' => false));
				// 	return ;
				// }

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
				/*if ($oppo_attr[0]->numbers != $given_data['stage_numbers']) {
					$changed_attr['opportunity_numbers'] = $given_data['stage_numbers'];
				}*/
				if ($oppo_attr[0]->close_date != $given_data['stage_closed_date']) {
					$changed_attr['opportunity_date'] = $given_data['stage_closed_date'];
				}
				/*if ($oppo_attr[0]->value != $given_data['stage_value']) {
					$changed_attr['opportunity_value'] = $given_data['stage_value'];
				}*/
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
					'module' => 'sales',
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
								'module' 		=> 'sales',
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
								'module' 		=> 'sales',
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
									'module' 		=> 'sales',
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
    	// close the opportunity
	public function close_opportunity_simple($module){

		if($this->session->userdata('uid')){
			$given_data = array(
            	'stage_value' 		 => $this->input->post('stage_value'),
            	'stage_numbers' 	 => $this->input->post('stage_numbers'),
				'stage_closed_date'  => $this->input->post('stage_closed_date'),
				'stage_rate'		 => $this->input->post('stage_rate'),
				'stage_score'		 => $this->input->post('stage_score'),
				'stage_customer_code'=> $this->input->post('stage_customer_code'),
				'stage_priority'	 => $this->input->post('stage_priority'),
				'stage_remarks' 	 => $this->input->post('stage_remarks1'),
				'opportunity_id' 	 => $this->input->post('opportunity_id1'),
				'lead_cust_id' 		 => $this->input->post('lead_cust_id1'),
				'stage_id' 			 => $this->input->post('stage_id1'),
				'cycle_id' 			 => $this->input->post('cycle_id1'),
				'sell_type' 		 => $this->input->post('sell_type1'),
				'mapping_id'		 => uniqid(rand()),
				'user_id'			 => $this->session->userdata('uid'),
				'close_status' 		 => $this->input->post('close_status_select'),
				'stage_manager_owner_id' => $this->input->post('stage_manager_owner_id'),
				'manager_owner_id' => $this->input->post('manager_owner_id'),
				'manager_owner_name' => $this->input->post('manager_owner_name'),
				'stage_owner_id' => $this->input->post('stage_owner_id'),
				'stage_owner_name' => $this->input->post('stage_owner_name'),

			   	'tempdate'	 		 => $this->input->post('tempdate'),  //Remind me on Date
                'title'              => $this->input->post('ChangeTitle'),
                'futureActivity'     => $this->input->post('FutureActivity'),
                'activityDuration'   => $this->input->post('Activityduration'),
                'alertBefore'        => $this->input->post('AlertBefore'),
                'contactType'        => $this->input->post('ContactType'),
                'opportunity_name'   => $this->input->post('opportunity_name'),
                'lead_name'        => $this->input->post('lead_name')
			);



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


				#BUG ID -
				// $owner_check = $this->opp_common->check_lead_owner_opp_owner($given_data['opportunity_id'], $given_data['lead_cust_id']);
				// if (($given_data['close_status'] == 'closed_won') && ($owner_check != true)) {
				// 	$error = array('error' => 'Lead Rep Owner and Opportunity Owner are not the same. Kindly request the manager to reassign the lead to opportunity owner', 'name' => '');
				// 	echo json_encode(array('errors' => array(0=>$error), 'status' => false));
				// 	return ;
				// }
                  /********** since there are multiple value and number for the product a different table is provided to store product value n numbers
                    Hence the opp_numbers will store the stage_manager
                  	'opp_value'=> $given_data['stage_value'],
				  	'opp_numbers'=> $given_data['stage_numbers'],   ******/
				    $log_attr_data = array(
					'mapping_id' => $given_data['mapping_id'],
					'opportunity_id'=> $given_data['opportunity_id'],
					'stage_id'=> $given_data['stage_id'],
					'user_id'=> $given_data['user_id'],
					'opp_close_date'=> $given_data['stage_closed_date'],
                    'opp_numbers'=> $given_data['stage_manager_owner_id'],
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
					'module' => $module,
					'timestamp'=> date('Y-m-d H:i:s'),
					'sell_type' => $given_data['sell_type'],
					'state' => 1,
					'remarks' => $given_data['stage_remarks']
				);

                 /**********************Close the Activity related to opportunity********************/
                $username=$this->opp_mgr->getusernamae($this->session->userdata('uid'));
                if($given_data['close_activity']=='on')
                {
                   $this->opp_common->close_activity($given_data['opportunity_id'],'opportunity',$username);
                }
                /*********************end of****************************************************************/
				if ($given_data['close_status']=="closed_won") {
					$log_trans_data['action'] = 'closed won';
				} else if ($given_data['close_status']=="temporary_loss") {
					$log_trans_data['action'] = 'temporary loss';
                    //scheduling future task for the temploss opportunity
                        $dt = date('ymdHis');
                        $lead_reminder_id = '';
                        $lead_reminder_id .= $dt;
                        $lead_reminder_id = uniqid($lead_reminder_id);
                        //calculate the meeting end
                        $duration=$given_data['activityDuration'];
                        $seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
                        $activity_duration = (int)$seconds->getTimestamp();
                        $start = new DateTime($given_data['tempdate']);
                        $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
                        $event_end = $event_end->format('Y-m-d H:i:s');
                        $event_start_date=date('Y-m-d', strtotime($given_data['tempdate']));
                        $event_start_date1=date('Y-m-d H:i:s', strtotime($given_data['tempdate']));
                        $event_start_time = date('H:i', strtotime($given_data['tempdate']));
                        $data_leadreminder = array(
                                        'lead_reminder_id' => $lead_reminder_id,
                                        'lead_id'   => $given_data['opportunity_id'],
                                        'opportunity_id' => $given_data['stage_id'],  // contains stageid
                                        'rep_id'    => $given_data['user_id'],
                                        'leadempid' => $given_data['contactType'],  //'contactid',
                                        'remi_date' => $event_start_date,
                                        'rem_time'  => $event_start_time,
                                        'conntype'  => $given_data['futureActivity'],
                                        'status'    => "scheduled",
                                        'meeting_start'    => $event_start_date1,
                                        'meeting_end'      => $event_end,
                                        'addremtime'       => $given_data['alertBefore'],
                                        'timestamp'        => date('Y-m-d H:i:s'),
                                        'remarks'          => $given_data['stage_remarks'],
                                        'event_name'       => $given_data['title'],
                                        'duration'         => $given_data['activityDuration'],
                                        'type' => "opportunity",
                                        'created_by'=>$given_data['user_id'],
                                        'module_id'=>$module
                        );

                        //inserting data in lead reminder
                        $this->opp_sales->insert_lead_reminder($data_leadreminder);

				} else if ($given_data['close_status']=="permanent_loss") {
					$log_trans_data['action'] = 'permanent loss';
				}
                // changing state of opportunity to 0 present in oppusermap which are permanent lost or temporary lost
                $this->opp_common->changestateopp($given_data['opportunity_id']);
                //adding data in opp_usermap table
				$this->opp_common->map_opportunity(array(0 => $log_trans_data));

                 //add notification for opportunity closed
                $dt = date('ymdHis');
                $notify_id= uniqid($dt);
                $data_notify= array(
                              'notificationID' =>$notify_id,
                              'action_details'=>'Opportunity',
                              'notificationTimestamp'=>date('Y-m-d H:i:s'),
                              'read_state'=>0,
                              'remarks'=>$given_data['stage_remarks']
                            );
                 $username=$this->opp_mgr->getusernamae($given_data['user_id']);
                if($module=='sales')
                {
                    $data_notify['from_user']=$given_data['user_id'];
                    $data_notify['to_user']=$given_data['stage_manager_owner_id'];
                    if($given_data['stage_manager_owner_id']==null || $given_data['stage_manager_owner_id']=='')
                    {
                       $data_notify['to_user']=$given_data['manager_owner_id'];
                    }

                    if($given_data['close_status']=="permanent_loss")
                    {
                         $data_notify['notificationShortText']='Opportunity Closed(Permanent)';
                         $data_notify['notificationText']='Opportunity '.$given_data['opportunity_name'].' closed';
                    }else{
                        $data_notify['notificationShortText']='Opportunity Closed(Temporary)';
                        $data_notify['notificationText'] ='Opportunity '.$given_data['opportunity_name'].' closed and a reminder task created';
                    }
                }else{

                    $data_notify['from_user']=$given_data['user_id'];
                    $data_notify['to_user']=$given_data['stage_owner_id'];
                    if($given_data['stage_owner_id']==null || $given_data['stage_owner_id']==''){
                        $data_notify['to_user']=$given_data['stage_manager_owner_id'];
                        if($given_data['stage_manager_owner_id']==null || $given_data['stage_manager_owner_id']=='')
                        {
                          $data_notify['to_user']=$given_data['manager_owner_id'];
                        }

                    }
                    if($given_data['close_status']=="permanent_loss")
                    {
                         $data_notify['notificationShortText']='Opportunity Closed(Permanent)';
                         $data_notify['notificationText']='Opportunity '.$given_data['opportunity_name'].' closed';
                    }else{
                        $data_notify['notificationShortText']='Opportunity Closed(Temporary)';
                        $data_notify['notificationText'] ='Opportunity '.$given_data['opportunity_name'].' closed and a reminder task created';
                    }
                }
                 $insert_notify = $GLOBALS['$dbFramework']->insert('notifications',$data_notify);
                /***********end of notification**************************************************************************/


				if ($given_data['sell_type'] == 'new_sell') {
					$lead_closed_status = $this->opp_common->lead_closed_status($given_data['lead_cust_id']);
					if($given_data['close_status'] == 'closed_won') {

					} else { // it is a temporary loss or a permanent loss
						if($given_data['lead_cust_close'] == 'on') {
							if (($lead_closed_status == '2') || ($lead_closed_status == '3') || ($lead_closed_status == '4')) {
							   // $error = array('error' => 'Lead already closed. Just closed the opportunity', 'name' => '');
							   //	echo json_encode(array('errors' => array(0=>$error), 'status' => false,'status1'=>false));
                               echo json_encode('Lead already closed. Just closed the opportunity');
								return ;
							}
							$active_oppos = $this->opp_common->active_oppos($given_data['opportunity_id'], $given_data['lead_cust_id']);
							if($active_oppos == true)	{
								//sorry you can't
							   //	$error = array('error' => 'Lead cannot be closed as there are other active opportunities on this Lead and the current opportunity is closed.', 'name' => '');
							   //	echo json_encode(array('errors' => array(0=>$error), 'status' => false,'status1'=>false));
                                echo json_encode('Lead cannot be closed as there are other active opportunities on this Lead and the current opportunity is closed.');
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
									'module' 		=> $module,
									'action' 		=> 'closed',
									'timestamp' 	=> date('Y-m-d H:i:s'),
									'state' 		=> 1,
									'type' 			=> 'lead',
									'mapping_id'	=> $given_data['mapping_id'],
								);
							   $insert = $this->opp_sales->log_lead($log_lead_close);
                              // echo json_encode(array('errors' => array(), 'status' => true,'status1'=>true));

                                /**********************Close the Activity related to lead********************/
                                $username=$this->opp_mgr->getusernamae($this->session->userdata('uid'));
                                if($given_data['close_activity']=='on')
                                {
                                   $this->opp_common->close_activity($given_data['lead_cust_id'],'lead',$username);
                                }
                                /*********************end of****************************************************************/
                               echo json_encode('Lead along with Opportunity closed');

                               return;
							}
						}

					}
				}
				echo json_encode('Opportunity closed successfully');
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}

    /********** Change state of closed lost opp************************************************************/
    public function changestate(){

      $json = file_get_contents("php://input");
      $data = json_decode($json);

      if($this->session->userdata('uid')){
			try {

                      $extradata=array();
                      $updateoppdetails=array();
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
        					'module' => 'sales',
                            'action'=>$data->lossType,
        					'timestamp'=> date('Y-m-d H:i:s'),
        					'sell_type' => 'new_sell',
        					'state' => 1,
        					'remarks' => $data->remarks
        			);

                    $stage_owner_id=$opp_data[0]->stage_owner_id;
                    $owner_id=$opp_data[0]->owner_id;
                    $manager_owner_id=$opp_data[0]->manager_owner_id;
                    $stage_manager_owner_id=$opp_data[0]->stage_manager_owner_id;
                    $extradata['manager_owner_id']=$manager_owner_id;
                    $extradata['stage_manager_owner_id']=$stage_manager_owner_id;

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
                    $extradata['lead_cust_name']=$data->lead_cust_name;
                    $extradata['remarks']=$data->remarks;
                    $extradata['opportunity_name']=$data->opportunity_name;

                    if($opp_usermap_data['action']=='reopen')
                    {
                      	$updateoppdetails['closed_reason'] = NULL;
        				$updateoppdetails['closed_status'] = '0';
                        $updateoppdetails['opportunity_approach_date'] = NULL;
                         // for notification to be sent to leadowner and lead_rep_owner only when reopen is clicked and lead is in closed state
                         if(isset($data->lead_manager_owner) && isset($data->lead_rep_owner))
                         {
                             $extradata['lead_manager_owner']=$data->lead_manager_owner;
                             $extradata['lead_rep_owner']=$data->lead_rep_owner;
                         }

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
        					$opp_usermap_data['action'] = 'temporary loss';
        				} else if ($opp_usermap_data['action']=="permanent_loss") {
        					$opp_usermap_data['action'] = 'permanent loss';
        				}
                    }


                    $getusername=$this->opp_mgr->getusernamae($stage_owner_id);
                    $extradata['stage_owner_id'] = $stage_owner_id;
                    $extradata['stage_owner_name'] = $getusername;
                    $extradata['manager_owner_id'] = $manager_owner_id;

                    $insert = $this->opp_sales->changestate($opp_usermap_data,$updateoppdetails,$extradata);
                   // echo json_encode($insert);

                   // exit;
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
        					'cycle_id' => $opportunity_stage,
        					'stage_id' => $cycle_id,
        					'module' => 'sales',
                            'action'=>'reopen',
        					'timestamp'=> date('Y-m-d H:i:s'),
        					'sell_type' => 'new_sell',
        					'state' => 1,
        					'remarks' => $reopenarray->remarks
        			);

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
                    $extradata['manager_owner_id']=$manager_owner_id;
                    $extradata['stage_manager_owner_id']=$stage_manager_owner_id;

                    if(isset($reopenarray->lead_manager_owner) && isset($reopenarray->lead_rep_owner))
                    {
                             $extradata['lead_manager_owner']=$reopenarray->lead_manager_owner;
                             $extradata['lead_rep_owner']=$reopenarray->lead_rep_owner;
                    }
                    $updateoppdetails['closed_reason'] = NULL;
        			$updateoppdetails['closed_status'] = '0';
                    $updateoppdetails['opportunity_approach_date'] = NULL;

                    $getusername=$this->opp_mgr->getusernamae($stage_owner_id);
                    $extradata['stage_owner_id'] = $stage_owner_id;
                    $extradata['stage_owner_name'] = $getusername;
                    $extradata['manager_owner_id'] = $manager_owner_id;

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
                       // return;
                  }else{

                        echo json_encode("Selected Opportunity Reopened successfully");
                       // echo json_encode(array('errors' => 'Opportunity State Changed successfully', 'status' => true));
                       // return;
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
