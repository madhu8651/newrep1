<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_opportunitiesModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();
class sales_opportunitiesModel extends CI_Model	{

	function __construct()	{
		parent::__construct();
		$this->load->model('common_opportunitiesModel','opp_common');
	}
//-----Fetch lead belong to given executive----//
	public function fetch_leads_sales($user_id){
		try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT li.lead_id, li.lead_name, li.lead_industry as industry, li.lead_business_loc as bloc
				FROM `lead_info` AS li
				WHERE ((li.customer_id IS NULL) OR (li.customer_id='')) AND
				(li.lead_status = 1) AND (li.lead_rep_status = 2) AND (li.lead_rep_owner='$user_id')
				ORDER BY li.lead_name");
			return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------Fetch customer belong to given Executive-----//
	public function fetch_customers_sales($user_id){
		try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT ci.customer_id as lead_id, ci.customer_name as lead_name, ci.customer_industry as industry, ci.customer_business_loc as bloc
				FROM `customer_info` AS ci
				WHERE (ci.customer_rep_status = 2) AND (ci.customer_rep_owner = '$user_id')
				ORDER BY ci.customer_name");
			return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------Fetch Reporting manager to given user----//
	public function fetch_reporting_manager($user_id){
		try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT reporting_to as manager_id FROM user_details where user_id='$user_id'");
			return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------Fetch if qualifier passed orfailed----//
	public function fetch_qualifiers($opportunity_id){
		$query = $GLOBALS['$dbFramework']->query("
			SELECT mapping_id, stage_id, opportunity_id, from_user_id, timestamp
			FROM oppo_user_map
			WHERE opportunity_id = '$opportunity_id'
			AND action IN ('passed qualifier' , 'failed qualifier')");
		return $query->result();
	}
   //------Check if files are uploaded for given Stage of opportunity------//
    public function check_files_for_stage($stage_id, $opp_id){
    	// returns true if files are required to progress to stage
    	try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT ssa.stage_id AS ssa_stage, odm.stage_id AS odm_stage
				FROM sales_stage_attributes ssa
				left join opportunity_document_mapping odm on (odm.stage_id=ssa.stage_id AND odm.opportunity_id='$opp_id')
				WHERE ssa.stage_id='$stage_id'
					AND ssa.attribute_remarks IS NULL
					AND ssa.attribute_name = 'document_upload'
				GROUP BY ssa.stage_id");
			$count = $query->num_rows();
			if ($count > 0) {
				$result = $query->result();
				$odm_stage = $result[0]->odm_stage;
				if ($odm_stage != NULL) {
					return false; // files are already uploaded so can progress
				} else {
					return true; // else true meaning you have to upload
				}
			}
			return false; //no document attribute for the stage and hence can progress
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
//-------Change Stage owner to owner for opportunity-----//
    public function change_stage_owner_to_owner($opportunity_id,$uid){
    	try {
    	    if($uid==''){
                    $update = $GLOBALS['$dbFramework']->query(" UPDATE opportunity_details SET stage_owner_id=owner_id WHERE opportunity_id='$opportunity_id'");
			        return $update;
    	    }else{
                    $update = $GLOBALS['$dbFramework']->query(" UPDATE opportunity_details SET stage_owner_id='".$uid."' WHERE opportunity_id='$opportunity_id'");
			        return $update;
    	    }

    	} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
    	}
    }
//-----Check if given stage is the first stage-----//
    public function is_first_stage($stage_id) {
    	try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT stage_sequence as seq_no
				FROM sales_stage
				WHERE stage_id='$stage_id'");
			$result = $query->result();
			if ($result[0]->seq_no == '6') {
				return true;
			}else{
                return false;
			}

    	} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
    	}
    }
//------Fetch next stage for given stage in the sales cycle-----//
	public function fetch_nextStage($stage_id, $cycle_id){
		try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT ss.stage_id AS stage_id, ss.stage_name AS stage_name, ss.stage_sequence AS seq_no
				FROM sales_stage ss, stage_cycle_mapping scm
				WHERE scm.cycle_id = '$cycle_id'
				    AND scm.stage_id = ss.stage_id
				    AND ss.stage_sequence>(select stage_sequence from sales_stage where stage_id='$stage_id')
				GROUP BY ss.stage_id
				ORDER BY ss.stage_sequence
				LIMIT 1");
			$result = $query->result();
			if (count($result) == 0) {
				return NULL;
			}
			return $result;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//-------Fetch reject stage-----//
	public function fetch_reject_stage($current_stage){
		try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT attribute_value as rollback_stage
				FROM sales_stage_attributes
				WHERE stage_id = '$current_stage' AND attribute_name = 'action_button'");
			$result = $query->result();
			return $result[0]->rollback_stage;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------Fetch owner of the given stage----//
	public function fetch_owner_stage($stage_id, $opp_id){
		try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT (CASE to_user_id
					WHEN (select count(*) FROM user_details WHERE user_id=to_user_id and user_state='1')>0 THEN to_user_id
					ELSE (select owner_id FROM opportunity_details WHERE opportunity_id='$opp_id')
				END) as old_stage_owner
				FROM oppo_user_map
				WHERE stage_id='$stage_id' AND opportunity_id='$opp_id' AND action IN ('stage progressed')");
			$result = $query->result();
			return $result[0]->old_stage_owner;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------Create a customer from lead------//
	public function create_customer($lead_cust_id, $customer_id){
		try {
			$user_id = $this->session->userdata('uid');
			$query = $GLOBALS['$dbFramework']->query("
				INSERT INTO customer_info (
					customer_id, 			customer_name, 		customer_logo,
					customer_number, 		customer_email, 	customer_website,
					customer_location_coord,customer_address, 	customer_city,
					customer_state, 		customer_country, 	customer_zip,
					customer_remarks, 		customer_source, 	customer_created_by,
					customer_manager_owner, lead_id, 			customer_industry,
					customer_business_loc, 	contact_number, 	customer_status,
					customer_manager_status)
				SELECT
					'$customer_id', 	lead_name, 		lead_logo,
					lead_number,		lead_email, 	lead_website,
					lead_location_coord,lead_address, 	lead_city,
					lead_state, 		lead_country, 	lead_zip,
					lead_remarks, 		lead_source, 	'$user_id',
					lead_manager_owner, lead_id, 		lead_industry,
					lead_business_loc, contact_number,  0,
					1
				FROM lead_info WHERE lead_id='$lead_cust_id'");
			return $query;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//-------Function to update 'lead_info' table---//
	public function update_lead($data, $lead_cust_id){
		try {
			$var = $GLOBALS['$dbFramework']->update('lead_info', $data, array('lead_id' => $lead_cust_id));
			return $var;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//----Function to insert into 'lead_cust_user_map' table----//
	public function log_lead($data){
		try {
			$insertQuery = $GLOBALS['$dbFramework']->insert('lead_cust_user_map', $data);
			return $insertQuery;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------Function to insert into 'product_purchase_info' table-----//
	public function insert_prod_purchase_info($lead_cust_id, $data){
		$opp_id = $data['opportunity_id'];
		$quantity = $data['stage_numbers'];
		$amount = $data['stage_value'];
		$rate = $data['stage_rate'];
		$score = $data['stage_score'];
		$customer_code = $data['stage_customer_code'];
		$priority = $data['stage_priority'];
		$user_id = $data['user_id'];
		$currency_id = $data['currency_id'];
		try {
			$prod = $GLOBALS['$dbFramework']->query("
				SELECT product_id as product_id, quantity as quantity, amount as amount
				FROM oppo_product_map
				WHERE opportunity_id='$opp_id'");
			$results = $prod->result();
			$purchase_id = uniqid();
			$purchase_array = array();
			foreach ($results as $result) {
				$data = array(
					'purchase_id'=> $purchase_id,
					'customer_id'=> $lead_cust_id,
					'product_id'=> $result->product_id,
					'purchase_start_date'=> date('Y-m-d H:i:s'),
					'opportunity_id'=> $opp_id,
					'timestamp'=> date('Y-m-d H:i:s'),
					'Quantity' => $result->quantity,
					'amount' => $result->amount,
					'currency' => $currency_id,
					'rate' => $rate,
					'score' => $score,
					'customer_code' => $customer_code,
					'priority' => $priority,
					'product_owner' => $user_id
				);
				array_push($purchase_array, $data);
			}
			$insertQuery = $GLOBALS['$dbFramework']->insert_batch('product_purchase_info', $purchase_array);
			return $insertQuery;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------Function which returns corresponding customer for lead----//
	public function get_customer_for_lead($lead_id) {
		try {
			$prod = $GLOBALS['$dbFramework']->query("
				SELECT customer_id as customer_id
				FROM lead_info WHERE lead_id='$lead_id'");
			$result = $prod->result();
			return $result[0]->customer_id;
		} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
		}
	}
//------Function which assign users in allocation list-----//
	public function assign_allocation_list($alloc_users, $given_data,$singleuser)	{
		try {
		    $arr=array();
			$owner_id = $given_data['user_id'];
			$opp_id = $given_data['opportunity_id'];
			$lead_cust_id = $given_data['lead_cust_id'];
			$cycle_id = $given_data['cycle_id'];
			$stage_id = $given_data['stage_id'];
			$sell_type = $given_data['sell_type'];
            if($singleuser==''){
                $user_list = implode('\',\'', $alloc_users);
            }else{
                $user_list="";
            }

            $query1 = $GLOBALS['$dbFramework']->query("SELECT ud.user_name FROM user_details ud WHERE ud.user_id IN ('$user_list')");
			$array= $query1->result();
            $str='';
            for ($i=0; $i < count($array); $i++) {
				$str.= $array[$i]->user_name.",";
            }


			$query = $GLOBALS['$dbFramework']->query("
				SELECT ud.user_id, ul.sales_module, ul.manager_module
				FROM user_details ud, user_licence ul
				WHERE ud.user_id IN ('$user_list')
				AND ud.user_state=1 AND ud.user_id=ul.user_id
				AND ud.user_id IN  (SELECT t1.user_id
					FROM opportunity_details od, user_mappings t1
					JOIN user_mappings t2 ON t1.user_id = t2.user_id
					JOIN user_mappings t3 ON t1.user_id = t3.user_id
					JOIN user_mappings t4 ON t1.user_id = t4.user_id
					WHERE od.opportunity_id='$opp_id' AND (t1.map_type = 'product' AND t1.map_id=od.opportunity_product
						AND t2.map_type='business_location' AND t2.map_id=od.opportunity_location
						AND t3.map_type='clientele_industry' AND t3.map_id=od.opportunity_industry
						AND t4.map_type='sell_type' AND t4.map_id=od.sell_type)
				) ");

                /*AND ud.user_id NOT IN (
				SELECT to_user_id
				FROM oppo_user_map
				WHERE opportunity_id='$opp_id' AND (action IN ('stage assigned','stage reassigned','ownership assigned', 'ownership reassigned')) AND state=1)
				GROUP BY ud.user_id*/

/*				SELECT ul.user_id, ul.sales_module, ul.manager_module
				FROM user_licence ul
				WHERE ul.user_id IN ('$user_list')*/
			$array = $query->result();
			$mapping_id = uniqid(rand());
			$log_trans_data = array();
			$reset_sales = false;
			$reset_manager = false;
			for ($i=0; $i < count($array); $i++){
			    $mapping_id = uniqid(rand());
				$user_id = $array[$i]->user_id;
				$sales_module = $array[$i]->sales_module;
				$manager_module = $array[$i]->manager_module;
                if($singleuser=='' && $manager_module == '0'){
                    if ($sales_module != '0' ) {
    					$reset_sales = true;
    					array_push($log_trans_data, array(
    					'mapping_id' 	=> $mapping_id,
    					'opportunity_id'=> $opp_id,
    					'lead_cust_id'	=> $lead_cust_id,
    					'from_user_id' 	=> $owner_id,
    					'to_user_id' 	=> $user_id,
    					'cycle_id' 		=> $cycle_id,
    					'stage_id' 		=> $stage_id,
    					'module' 		=> 'sales',
    					'action' 		=> 'stage reassigned',
    					'sell_type' 	=> $sell_type,
    					'timestamp' 	=> date('Y-m-d H:i:s'),
    					'remarks' 		=> '',
    					'state'			=> 1
    					));
				    }
                }else if($singleuser=='' && $sales_module != '0' && $manager_module != '0'){
                    if ($manager_module != '0' ) {
      					$reset_manager = true;

      					array_push($log_trans_data, array(
      					'mapping_id' 	=> $mapping_id,
      					'opportunity_id'=> $opp_id,
      					'lead_cust_id'	=> $lead_cust_id,
      					'from_user_id' 	=> $owner_id,
      					'to_user_id' 	=> $user_id,
      					'cycle_id' 		=> $cycle_id,
      					'stage_id' 		=> $stage_id,
      					'module' 		=> 'manager',
      					'action' 		=> 'stage reassigned',
      					'sell_type' 	=> $sell_type,
      					'timestamp' 	=> date('Y-m-d H:i:s'),
      					'remarks' 		=> '',
      					'state'			=> 1
      					));
				    }
                    $updateData = array(
					    'stage_owner_status' => 0,
					    'stage_owner_id' => null,
                        'opportunity_value'=>null,
                        'stage_manager_owner_id'=>null,
                        'stage_manager_owner_status'=>1
				    );
				    $var = $GLOBALS['$dbFramework']->update('opportunity_details', $updateData, array('opportunity_id' => $opp_id));
                }else{

                        if ($manager_module != '0' ) {
          					$reset_manager = true;

          					array_push($log_trans_data, array(
          					'mapping_id' 	=> $mapping_id,
          					'opportunity_id'=> $opp_id,
          					'lead_cust_id'	=> $lead_cust_id,
          					'from_user_id' 	=> $owner_id,
          					'to_user_id' 	=> $user_id,
          					'cycle_id' 		=> $cycle_id,
          					'stage_id' 		=> $stage_id,
          					'module' 		=> 'manager',
          					'action' 		=> 'stage reassigned',
          					'sell_type' 	=> $sell_type,
          					'timestamp' 	=> date('Y-m-d H:i:s'),
          					'remarks' 		=> '',
          					'state'			=> 1
          					));
    				    }
    				    else { // New Changes.
          					$reset_sales = true;
          					array_push($log_trans_data, array(
          					'mapping_id' 	=> $mapping_id,
          					'opportunity_id'=> $opp_id,
          					'lead_cust_id'	=> $lead_cust_id,
          					'from_user_id' 	=> $owner_id,
          					'to_user_id' 	=> $user_id,
          					'cycle_id' 		=> $cycle_id,
          					'stage_id' 		=> $stage_id,
          					'module' 		=> 'sales',
          					'action' 		=> 'stage reassigned',
          					'sell_type' 	=> $sell_type,
          					'timestamp' 	=> date('Y-m-d H:i:s'),
          					'remarks' 		=> '',
          					'state'			=> 1
          					));
    				    }
                }
			}

            $query= $GLOBALS['$dbFramework']->insert_batch('oppo_user_map', $log_trans_data);

			if ($reset_sales == true && $reset_manager == false ){
				$updateData = array(
					'stage_owner_status' => 1,
					'stage_owner_id' => null,
                    'opportunity_value'=>'onlyexe'

				);
				$var = $GLOBALS['$dbFramework']->update('opportunity_details', $updateData, array('opportunity_id' => $opp_id));

				/*
                $GLOBALS['$dbFramework']->query("
					UPDATE oppo_user_map SET state=0
					WHERE opportunity_id='$opp_id'
					AND action IN ('stage assigned', 'accepted', 'reassigned', 'stage rejected')
					AND module='sales'
				");*/
			}
			if ($singleuser != '' ){
			    $log_trans_data = array();
                $update = $GLOBALS['$dbFramework']->query(" UPDATE opportunity_details SET stage_owner_id=owner_id,stage_manager_owner_id=manager_owner_id,stage_manager_owner_status=2 WHERE opportunity_id='$opp_id'");
                $prod = $GLOBALS['$dbFramework']->query("SELECT stage_owner_id as stage_owner_id FROM opportunity_details WHERE opportunity_id='$opp_id'");
			    $result = $prod->result();
			    $uid=$result[0]->stage_owner_id;
                array_push($log_trans_data, array(
          					'mapping_id' 	=> $mapping_id,
          					'opportunity_id'=> $opp_id,
          					'lead_cust_id'	=> $lead_cust_id,
          					'from_user_id' 	=> $owner_id,
          					'to_user_id' 	=> $uid,
          					'cycle_id' 		=> $cycle_id,
          					'stage_id' 		=> $stage_id,
          					'module' 		=> 'sales',
          					'action' 		=> 'stage accepted',
          					'sell_type' 	=> $sell_type,
          					'timestamp' 	=> date('Y-m-d H:i:s'),
          					'remarks' 		=> '',
          					'state'			=> 1
          					));
                $query= $GLOBALS['$dbFramework']->insert_batch('oppo_user_map', $log_trans_data);

			}

            //---------------- notification code----------------------------------
             $insertArray1=array();
            // from user---- is the one who performs action
            //to user---- is the one who is to be notified.
            for ($i=0; $i < count($array); $i++) {
                    $dt = date('ymdHis');
                    $letter=chr(rand(97,122));
                    $letter.=chr(rand(97,122));
                    $notify_id1=$letter;
                    $notify_id1.=$dt;
                    $notify_id = uniqid($notify_id1);
                    $remarks="multiple assign of opp from manager module ";
                    $get_fromuser=$array[$i]->user_id;
                    $getusername=$this->opp_mgr->getusernamae($get_fromuser);
                    //$getusername1=$this->opp_mgr->getusernamae($user);
            		$data2= array(
            			'notificationID' =>$notify_id,
            			'notificationShortText'=>'Opportunity Assigned',
            			'notificationText' =>'Opportunity Assigned to '.$getusername,
            			'from_user'=>$given_data['user_id'],
            			'to_user'=>$get_fromuser,
            			'action_details'=>'Opportunity',
            			'notificationTimestamp'=>$dt,
            			'read_state'=>0,
            			'remarks'=>$remarks,
            		);
                    array_push($insertArray1, $data2);
            }
            $insert_notify= $this->opp_mgr->rej_opp_notification($insertArray1);
            //---------------------------------------------------------end -----------------
            return $str;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------Function to add an opportunity----//
	public function addOpportunity($data, $owner_id) {
		$insertArray = array();

		$opportunity_id = $data['opportunity_id'];;
		$opportunity_name = $data['opportunity_name'];
		$lead_id = $data['lead_cust_id'];
		$lead_contact = $data['opportunity_contact'];
		$product_id = $data['product_id'];
		$currency_id = $data['currency_id'];
		$industry_id = $data['industry_id'];
		$location_id = $data['location_id'];
		$manager_id = $data['manager_id'];
		$sell_type = $data['sell_type'];
		$opp_remarks = $data['opp_remarks'];
		/* what's to be inserted to the opportunity_details table
			'owner_status' => 2, //owner (rep) has accepted
			'owner_manager_status' => 2, // manager owner has accepted
			'stage_owner_status' => 2, //stage owner (rep) has accepted
			'stage_manager_owner_status' => 2 //stage owner (manager has accepted)
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
			'sell_type' => $sell_type,
			'created_by' => $owner_id,
			'oppowner' => $owner_id,
			'created_timestamp' => date('Y-m-d H:i:s'),

			'owner_id' => $owner_id,
			'owner_status' => 2,

			'manager_owner_id' => $manager_id,
			'owner_manager_status' => 2,

			'stage_owner_id' => $owner_id,
			'stage_owner_status' => 2,

			'stage_manager_owner_id' => $manager_id,
			'stage_manager_owner_status' => 2
		);

		array_push($insertArray, array(
			'mapping_id'=> uniqid(rand()),
			'opportunity_id'=> $opportunity_id,
			'lead_cust_id'=> $lead_id,
			'from_user_id'=> $owner_id,
			'to_user_id'=> $owner_id,
			'cycle_id'=> $data['cycle_id'],
			'stage_id'=> $data['stage_id'],
			'module'=> 'sales',
			'action'=> 'created',
			'state' => 1,
			'sell_type'=> $sell_type,
			'timestamp'=> date('Y-m-d H:i:s'),
			'remarks'=> $opp_remarks
		));
		array_push($insertArray, array(
			'mapping_id'=> uniqid(rand()),
			'opportunity_id'=> $opportunity_id,
			'lead_cust_id'=> $lead_id,
			'from_user_id'=> $owner_id,
			'to_user_id'=> $owner_id,
			'cycle_id'=> $data['cycle_id'],
			'stage_id'=> $data['stage_id'],
			'module'=> 'sales',
			'action'=> 'ownership accepted',
			'state' => 1,
			'sell_type'=> $sell_type,
			'timestamp'=> date('Y-m-d H:i:s'),
			'remarks'=> null
		));
		array_push($insertArray, array(
			'mapping_id'=> uniqid(rand()),
			'opportunity_id'=> $opportunity_id,
			'lead_cust_id'=> $lead_id,
			'from_user_id'=> $owner_id,
			'to_user_id'=> $owner_id,
			'cycle_id'=> $data['cycle_id'],
			'stage_id'=> $data['stage_id'],
			'module'=> 'sales',
			'action'=> 'stage accepted',
			'state' => 1,
			'sell_type'=> $sell_type,
			'timestamp'=> date('Y-m-d H:i:s'),
			'remarks'=> null
		));
		array_push($insertArray, array(
			'mapping_id'=> uniqid(rand()),
			'opportunity_id'=> $opportunity_id,
			'lead_cust_id'=> $lead_id,
			'from_user_id'=> $manager_id,
			'to_user_id'=> $manager_id,
			'cycle_id'=> $data['cycle_id'],
			'stage_id'=> $data['stage_id'],
			'module'=> 'manager',
			'action'=> 'ownership accepted',
			'state' => 1,
			'sell_type'=> $sell_type,
			'timestamp'=> date('Y-m-d H:i:s'),
			'remarks'=> null
		));
		array_push($insertArray, array(
			'mapping_id'=> uniqid(rand()),
			'opportunity_id'=> $opportunity_id,
			'lead_cust_id'=> $lead_id,
			'from_user_id'=> $manager_id,
			'to_user_id'=> $manager_id,
			'cycle_id'=> $data['cycle_id'],
			'stage_id'=> $data['stage_id'],
			'module'=> 'manager',
			'action'=> 'stage accepted',
			'state' => 1,
			'sell_type'=> $sell_type,
			'timestamp'=> date('Y-m-d H:i:s'),
			'remarks'=> null
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
			$returnArray= array('message' => 'Couldn\'t create opportunity as something went wrong.', 'status'=>false);
			return json_encode($returnArray);
		} else {
			$insert = $this->opp_common->map_opportunity($insertArray);
			$insert = $this->opp_common->map_opp_products(array(0 => $insert_product));
		}
		$returnArray= array('message' => '', 'status'=>true, 'opportunity_id'=>$opportunity_id);
		return json_encode($returnArray);
	}

    public function check_if_user_is_oppown_n_stgown($userid,$opp_id){
        try {

            $query=$GLOBALS['$dbFramework']->query(" SELECT * FROM oppo_user_map WHERE opportunity_id='".$opp_id."'
                                        AND to_user_id='".$userid."' AND ACTION IN ('ownership assigned','stage assigned') AND state=1;");
            $cnt=$query->num_rows();
            if($cnt > 1){
                return 1;
            }else{
                return $query->result();
            }

        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

//-----Function That fetches If new oppotunity can be accepted or not-----//
	public function new_opp_details($opportunity_id = '', $user = '') {
		try {
			$query = $GLOBALS['$dbFramework']->query("
			SELECT
			a.owner_id,a.owner_status,
			IF (a.owner_id IS NOT NULL, (select user_name from user_details where user_id=a.owner_id),'pending') as owner_name,
			(select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and state=1 and from_user_id='$user' and action ='ownership rejected') as ownerreject,

			a.stage_owner_id,a.stage_owner_status,
			IF (a.stage_owner_id IS NOT NULL, (select user_name from user_details where user_id=a.stage_owner_id),'pending') as stage_owner_name,
			(select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and from_user_id='$user' and state=1 and action ='stage rejected') as stagereject

			from oppo_user_map h, opportunity_details a
			where a.opportunity_id='$opportunity_id' and a.opportunity_id=h.opportunity_id
			and h.to_user_id='$user' and h.state=1 and h.module='sales' and
			h.action in('stage assigned','ownership assigned','ownership reassigned','stage reassigned') and
			(a.owner_status=1 or a.stage_owner_status=1) and a.closed_status!=100
			group by a.opportunity_id");

			return $query->result();
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}

	/*-=-=-=-=-=-=-=-=--=-=-=-=KEERTI FUNCTIONS-=-=-=-=-=-=-=-=-=-=-=-=-*/
//-----Function that fetches all new new opportunities for given user----//
	/*public function fetch_new_opportunities($user){
		try {
			//Change Query Based on the requirement.
			// 1. Check for the stage and ownership assigmnent and re-assignment


			$query = $GLOBALS['$dbFramework']->query("SELECT
    													opportunity_id
													FROM
    												oppo_user_map
													WHERE
    												action IN ('stage assigned','stage reassigned') AND action IN ('ownership assigned','ownership reassigned')
        											AND state = 1
        											AND module = 'sales'
        											AND to_user_id = '$user'");

			$query1 = $GLOBALS['$dbFramework']->query("
														SELECT
														opportunity_id
														FROM
														oppo_user_map
														WHERE
														action IN ('stage assigned','stage reassigned')
														AND opportunity_id IN (SELECT
														opportunity_id
														FROM
														oppo_user_map
														WHERE
														(action NOT IN ('stage accepted')) group by stage_id
														)
														AND stage_id IN (
														SELECT
														stage_id
														FROM
														oppo_user_map
														WHERE
														(action NOT IN ('stage accepted')) group by stage_id
														)
														AND state = 1
														AND module = 'sales'
														AND to_user_id = '$user'
													");

			$query2 = $GLOBALS['$dbFramework']->query("
													SELECT
    													opportunity_id
													FROM
    												oppo_user_map
													WHERE
    												action IN ('ownership assigned','ownership reassigned')
        											AND state = 1
        											AND module = 'sales'
        											AND to_user_id = '$user'");


			if ($query->num_rows() > 0)
			{
			$query=$GLOBALS['$dbFramework']->query("
			SELECT
			a.opportunity_id, a.opportunity_name,
			coalesce(a.opportunity_value, '-') as opportunity_value,
			coalesce(a.opportunity_numbers, '-') as opportunity_quantity,
			coalesce(a.opportunity_date, '-') as expected_close_date,
			a.sell_type, c.hvalue2 as product,j.hvalue2 as industry_name, k.hvalue2 as location_name,
			a.opportunity_stage, i.stage_name,a.cycle_id,h.mapping_id,
			a.lead_cust_id, (select CASE a.sell_type
            	WHEN 'new_sell' THEN (select lead_name from lead_info where lead_id=a.lead_cust_id)
            	ELSE (select customer_name from customer_info where customer_id=a.lead_cust_id)
            END) AS lead_cust_name,

			a.owner_id,e.user_name as rep_owner,a.owner_status,d.user_name as manager_owner,
			IF (a.owner_id IS NOT NULL, (select user_name from user_details where user_id=a.owner_id),'pending') as owner_name,
			(select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and state=1 and from_user_id='$user' and action ='ownership rejected') as ownerreject,
			(select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and state=1 and to_user_id='$user' and action in ('ownership assigned','ownership reassigned')) as owner_status1,

			a.stage_owner_id,g.user_name as stagerep_owner,a.stage_owner_status,f.user_name as stagemanager_owner,
			IF (a.stage_owner_id IS NOT NULL, (select user_name from user_details where user_id=a.stage_owner_id),'pending') as stage_owner_name,
            (select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and from_user_id='$user' and state=1 and action ='stage rejected') as stagereject,
            (select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and to_user_id='$user' and state=1 and action in ('stage assigned','stage reassigned')) as stage_owner_status1

            from oppo_user_map h, opportunity_details a
            left join hierarchy c on a.opportunity_product=c.hkey2
            left join user_details d on a.manager_owner_id=d.user_id
            left join user_details e on a.owner_id=e.user_id
            left join user_details f on a.stage_manager_owner_id=f.user_id
            left join user_details g on a.stage_owner_id=g.user_id
            left join sales_stage i  on a.opportunity_stage=i.stage_id
			left join hierarchy j  on a.opportunity_industry=j.hkey2
			left join hierarchy k  on a.opportunity_location=k.hkey2
            where a.closed_reason is NULL  and
            a.opportunity_id=h.opportunity_id
            AND
            h.opportunity_id
			in
			(SELECT
			opportunity_id
			FROM
			oppo_user_map
			WHERE
			action IN ('stage assigned' , 'ownership assigned',
			'ownership reassigned','stage reassigned')
			AND state = 1
			AND module = 'sales'
			AND to_user_id = '$user')
			AND
			h.opportunity_id
			not in
			(SELECT
			opportunity_id
			FROM
			oppo_user_map
			WHERE
			(action IN ('stage rejected') AND action IN ('ownership rejected'))
			AND state = 1
			AND module = 'sales'
			AND from_user_id = '$user')
            and a.closed_status!=100
            group by a.opportunity_id
            ");
	        return $query->result();
			}
			elseif ($query1->num_rows() > 0)
			{

			$query=$GLOBALS['$dbFramework']->query("
			SELECT
			a.opportunity_id, a.opportunity_name,
			coalesce(a.opportunity_value, '-') as opportunity_value,
			coalesce(a.opportunity_numbers, '-') as opportunity_quantity,
			coalesce(a.opportunity_date, '-') as expected_close_date,
			a.sell_type, c.hvalue2 as product,j.hvalue2 as industry_name, k.hvalue2 as location_name,
			a.opportunity_stage, i.stage_name,a.cycle_id,h.mapping_id,
			a.lead_cust_id, (select CASE a.sell_type
            	WHEN 'new_sell' THEN (select lead_name from lead_info where lead_id=a.lead_cust_id)
            	ELSE (select customer_name from customer_info where customer_id=a.lead_cust_id)
            END) AS lead_cust_name,

			a.owner_id,e.user_name as rep_owner,a.owner_status,d.user_name as manager_owner,
			IF (a.owner_id IS NOT NULL, (select user_name from user_details where user_id=a.owner_id),'pending') as owner_name,
			(select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and state=1 and from_user_id='$user' and action ='ownership rejected') as ownerreject,
			(select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and state=1 and to_user_id='$user' and action in ('ownership assigned','ownership reassigned')) as owner_status1,

			a.stage_owner_id,g.user_name as stagerep_owner,a.stage_owner_status,f.user_name as stagemanager_owner,
			IF (a.stage_owner_id IS NOT NULL, (select user_name from user_details where user_id=a.stage_owner_id),'pending') as stage_owner_name,
            (select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and from_user_id='$user' and state=1 and action ='stage rejected') as stagereject,
            (select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and to_user_id='$user' and state=1 and action in ('stage assigned','stage reassigned')) as stage_owner_status1

            from oppo_user_map h, opportunity_details a
            left join hierarchy c on a.opportunity_product=c.hkey2
            left join user_details d on a.manager_owner_id=d.user_id
            left join user_details e on a.owner_id=e.user_id
            left join user_details f on a.stage_manager_owner_id=f.user_id
            left join user_details g on a.stage_owner_id=g.user_id
            left join sales_stage i  on a.opportunity_stage=i.stage_id
			left join hierarchy j  on a.opportunity_industry=j.hkey2
			left join hierarchy k  on a.opportunity_location=k.hkey2
            where a.closed_reason is NULL and
            a.opportunity_id=h.opportunity_id
            AND
            h.opportunity_id
			in
			(SELECT
			opportunity_id
			FROM
			oppo_user_map
			WHERE
			action IN ('stage assigned','stage reassigned')
			AND state = 1
			AND module = 'sales'
			AND to_user_id = '$user')
			AND
			h.opportunity_id
			not in
			(SELECT
			opportunity_id
			FROM
			oppo_user_map
			WHERE
			(action IN ('stage rejected'))
			AND state = 1
			AND module = 'sales'
			AND from_user_id = '$user')
            and a.closed_status!=100
            group by a.opportunity_id
            ");
             return $query->result();
			}
			elseif ($query2->num_rows() > 0)
			{

			$query=$GLOBALS['$dbFramework']->query("
			SELECT
			a.opportunity_id, a.opportunity_name,
			coalesce(a.opportunity_value, '-') as opportunity_value,
			coalesce(a.opportunity_numbers, '-') as opportunity_quantity,
			coalesce(a.opportunity_date, '-') as expected_close_date,
			a.sell_type, c.hvalue2 as product,j.hvalue2 as industry_name, k.hvalue2 as location_name,
			a.opportunity_stage, i.stage_name,a.cycle_id,h.mapping_id,
			a.lead_cust_id, (select CASE a.sell_type
            	WHEN 'new_sell' THEN (select lead_name from lead_info where lead_id=a.lead_cust_id)
            	ELSE (select customer_name from customer_info where customer_id=a.lead_cust_id)
            END) AS lead_cust_name,

			a.owner_id,e.user_name as rep_owner,a.owner_status,d.user_name as manager_owner,
			IF (a.owner_id IS NOT NULL, (select user_name from user_details where user_id=a.owner_id),'pending') as owner_name,
			(select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and state=1 and from_user_id='$user' and action ='ownership rejected') as ownerreject,
			(select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and state=1 and to_user_id='$user' and action in ('ownership assigned','ownership reassigned')) as owner_status1,

			a.stage_owner_id,g.user_name as stagerep_owner,a.stage_owner_status,f.user_name as stagemanager_owner,
			IF (a.stage_owner_id IS NOT NULL, (select user_name from user_details where user_id=a.stage_owner_id),'pending') as stage_owner_name,
            (select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and from_user_id='$user' and state=1 and action ='stage rejected') as stagereject,
            (select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and to_user_id='$user' and state=1 and action in ('stage assigned','stage reassigned')) as stage_owner_status1

            from oppo_user_map h, opportunity_details a
            left join hierarchy c on a.opportunity_product=c.hkey2
            left join user_details d on a.manager_owner_id=d.user_id
            left join user_details e on a.owner_id=e.user_id
            left join user_details f on a.stage_manager_owner_id=f.user_id
            left join user_details g on a.stage_owner_id=g.user_id
            left join sales_stage i  on a.opportunity_stage=i.stage_id
			left join hierarchy j  on a.opportunity_industry=j.hkey2
			left join hierarchy k  on a.opportunity_location=k.hkey2
            where  a.closed_reason is NULL  and
            a.opportunity_id=h.opportunity_id
            AND
            h.opportunity_id
			in
			(SELECT
			opportunity_id
			FROM
			oppo_user_map
			WHERE
			action IN ('ownership assigned','ownership reassigned')
			AND state = 1
			AND module = 'sales'
			AND to_user_id = '$user')
			AND
			h.opportunity_id
			not in
			(SELECT
			opportunity_id
			FROM
			oppo_user_map
			WHERE
			(action IN ('ownership rejected'))
			AND state = 1
			AND module = 'sales'
			AND from_user_id = '$user')
            and a.closed_status!=100
            group by a.opportunity_id
            ");
            return $query->result();
			}
			else
			{

				return array();
			}

        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }*/
    public function fetch_new_opportunities($user){
        try {

                $query=$GLOBALS['$dbFramework']->query("SELECT
			a.opportunity_id, a.opportunity_name,
			COALESCE(a.opportunity_value, '-') AS opportunity_value,
			COALESCE(a.opportunity_numbers, '-') AS opportunity_quantity,
			COALESCE(date_format(a.opportunity_date, '%d-%m-%Y'), '-') AS expected_close_date,
			a.sell_type, c.hvalue2 AS product,j.hvalue2 AS industry_name, k.hvalue2 AS location_name,
			a.opportunity_stage, i.stage_name,a.cycle_id,h.mapping_id,
			a.lead_cust_id, (SELECT CASE a.sell_type
            	WHEN 'new_sell' THEN (SELECT distinct lead_name FROM lead_info WHERE lead_id=a.lead_cust_id)
            	ELSE (SELECT distinct customer_name FROM customer_info WHERE customer_id=a.lead_cust_id)
            END) AS lead_cust_name,

			a.owner_id,e.user_name AS rep_owner,a.owner_status,d.user_name AS manager_owner,
			IF (a.owner_id IS NOT NULL, (SELECT user_name FROM user_details WHERE user_id=a.owner_id),'pending') AS owner_name,
			(SELECT COUNT(*) FROM oppo_user_map WHERE opportunity_id=a.opportunity_id AND state=1 AND from_user_id='$user' AND ACTION ='ownership rejected') AS ownerreject,
			(SELECT COUNT(*) FROM oppo_user_map WHERE opportunity_id=a.opportunity_id AND state=1 AND to_user_id='$user' AND ACTION IN ('ownership assigned','ownership reassigned')) AS owner_status1,

			a.stage_owner_id,g.user_name AS stagerep_owner,a.stage_owner_status,f.user_name AS stagemanager_owner,
			IF (a.stage_owner_id IS NOT NULL, (SELECT user_name FROM user_details WHERE user_id=a.stage_owner_id),'pending') AS stage_owner_name,
            (SELECT COUNT(*) FROM oppo_user_map WHERE opportunity_id=a.opportunity_id AND from_user_id='$user' AND state=1 AND ACTION ='stage rejected') AS stagereject,
		(SELECT COUNT(*) FROM oppo_user_map WHERE opportunity_id=a.opportunity_id AND to_user_id='$user' AND state=1 AND ACTION IN ('stage assigned','stage reassigned')) AS stage_owner_status1


            FROM oppo_user_map h, opportunity_details a
            LEFT JOIN hierarchy c ON a.opportunity_product=c.hkey2
            LEFT JOIN user_details d ON a.manager_owner_id=d.user_id
            LEFT JOIN user_details e ON a.owner_id=e.user_id
            LEFT JOIN user_details f ON a.stage_manager_owner_id=f.user_id
            LEFT JOIN user_details g ON a.stage_owner_id=g.user_id
            LEFT JOIN sales_stage i  ON a.opportunity_stage=i.stage_id
			LEFT JOIN hierarchy j  ON a.opportunity_industry=j.hkey2
			LEFT JOIN hierarchy k  ON a.opportunity_location=k.hkey2
            WHERE a.opportunity_id=h.opportunity_id AND (a.owner_id IS NULL OR a.stage_owner_id IS NULL)AND
            (a.owner_status=1 OR a.stage_owner_status=1 or a.stage_owner_status=3 or a.owner_status=3)
            AND h.to_user_id='$user' AND h.state=1 AND h.module='sales' AND
            h.action IN('stage assigned','ownership assigned','ownership reassigned','stage reassigned') AND a.closed_status!=100
            GROUP BY a.opportunity_id;");
                return $query->result();

        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
//------Function that fetches all in progress opportunities for given user----//
	public function fetch_inprogress_opportunities($user){
        try {
            $query=$GLOBALS['$dbFramework']->query("
			SELECT
			a.opportunity_id, a.opportunity_name,
			coalesce(a.opportunity_value, '-') as opportunity_value,
			coalesce(a.opportunity_numbers, '-') as opportunity_quantity,
			coalesce(date_format(a.opportunity_date, '%d-%m-%Y'), '-') as expected_close_date,
			a.sell_type, c.hvalue2 as product,j.hvalue2 as industry_name, k.hvalue2 as location_name,
			a.opportunity_stage, i.stage_name,a.cycle_id,
			a.lead_cust_id, (select CASE a.sell_type
				WHEN 'new_sell' THEN (select distinct lead_name from lead_info where lead_id=a.lead_cust_id)
				ELSE (select distinct customer_name from customer_info where customer_id=a.lead_cust_id)
			END) AS lead_cust_name,

			a.owner_id,e.user_name as rep_owner,a.owner_status,d.user_name as manager_owner,
			IF (a.owner_id IS NOT NULL, (select user_name from user_details where user_id=a.owner_id),'pending') as owner_name,
			(select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and state=0 and from_user_id='$user' and action ='ownership rejected') as ownerreject,

			a.stage_owner_id,g.user_name as stagerep_owner,a.stage_owner_status,f.user_name as stagemanager_owner,
			IF (a.stage_owner_id IS NOT NULL, (select user_name from user_details where user_id=a.stage_owner_id),'pending') as stage_owner_name,
			(select count(*) from oppo_user_map where opportunity_id=a.opportunity_id and from_user_id='$user' and state=0 and action ='stage rejected') as stagereject

			from oppo_user_map h, opportunity_details a
			left join hierarchy c on a.opportunity_product=c.hkey2
			left join user_details d on a.manager_owner_id=d.user_id
			left join user_details e on a.owner_id=e.user_id
			left join user_details f on a.stage_manager_owner_id=f.user_id
			left join user_details g on a.stage_owner_id=g.user_id
			left join sales_stage i on a.opportunity_stage=i.stage_id
			left join hierarchy j  on a.opportunity_industry=j.hkey2
			left join hierarchy k  on a.opportunity_location=k.hkey2
            where a.opportunity_id=h.opportunity_id and (a.closed_reason IS NULL) AND
            ((a.owner_id='$user' AND a.owner_status=2) OR (a.stage_owner_id='$user' AND a.stage_owner_status=2)
            OR (h.action IN ('stage progressed', 'rejected','reopen') and h.to_user_id='$user'))
            group by a.opportunity_id");
            return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
//-------Function that fetches all closed opportunities for given user------//
// query that fethces only the closed won opportunities
    public function fetch_closed_opportunities($user){
         try {

            //	coalesce(a.opportunity_date, '-') as expected_close_date,
            $query=$GLOBALS['$dbFramework']->query("
			SELECT  a.opportunity_id, a.opportunity_name,a.closed_status,a.closed_reason as reason,
			coalesce(a.opportunity_value, '-') as opportunity_value,
			coalesce(a.opportunity_numbers, '-') as opportunity_quantity,
			(Select date_format(TIMESTAMP, '%d-%m-%Y') from oppo_user_map where opportunity_id=a.opportunity_id and action ='closed won') as expected_close_date,
			a.sell_type, c.hvalue2 as product,h.hvalue2 as industry_name, i.hvalue2 as location_name,
			a.opportunity_stage, j.stage_name,a.cycle_id,
			a.lead_cust_id, (select CASE a.sell_type
				WHEN 'new_sell' THEN (select distinct lead_name from lead_info where lead_id=a.lead_cust_id)
				ELSE (select distinct customer_name from customer_info where customer_id=a.lead_cust_id)
			END) AS lead_cust_name,
			a.owner_id,e.user_name as rep_owner,a.owner_status,d.user_name as manager_owner,
			a.stage_owner_id,g.user_name as stagerep_owner,a.stage_owner_status,f.user_name as stagemanager_owner
			from opportunity_details a
			left join hierarchy c on a.opportunity_product=c.hkey2
			left join user_details d on a.manager_owner_id=d.user_id
			left join user_details e on a.owner_id=e.user_id
			left join user_details f on a.stage_manager_owner_id=f.user_id
			left join user_details g on a.stage_owner_id=g.user_id
			left join hierarchy h  on a.opportunity_industry=h.hkey2
			left join hierarchy i  on a.opportunity_location=i.hkey2
			left join sales_stage j on a.opportunity_stage=j.stage_id
            where  a.closed_status=100 and (a.owner_id='$user' or a.stage_owner_id='$user') and a.closed_reason in ('closed_won')
            group by a.opportunity_id");
	    	return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
//  query that fethces only the closed lost opportunities
     public function fetch_closed_lost_opportunities($user){
         try {

            $query=$GLOBALS['$dbFramework']->query("
			SELECT  a.opportunity_id, a.opportunity_name,a.closed_status,a.closed_reason as reason,opportunity_contact,
			coalesce(a.opportunity_value, '-') as opportunity_value,
			coalesce(a.opportunity_numbers, '-') as opportunity_quantity,
			(Select date_format(TIMESTAMP, '%d-%m-%Y') from oppo_user_map where opportunity_id=a.opportunity_id and action in ('temporary loss','permanent loss') and state=1) as expected_close_date,
			a.sell_type, c.hvalue2 as product,h.hvalue2 as industry_name, i.hvalue2 as location_name,
			a.opportunity_stage, j.stage_name,j.stage_id,a.cycle_id,
			a.lead_cust_id, (select CASE a.sell_type
				WHEN 'new_sell' THEN (select distinct lead_name from lead_info where lead_id=a.lead_cust_id)
				ELSE (select distinct customer_name from customer_info where customer_id=a.lead_cust_id)
			END) AS lead_cust_name,
			a.owner_id,e.user_name as rep_owner,a.owner_status,d.user_name as manager_owner,
			a.stage_owner_id,g.user_name as stagerep_owner,a.stage_owner_status,f.user_name as stagemanager_owner,k.user_name AS closed_by
			from opportunity_details a
			left join hierarchy c on a.opportunity_product=c.hkey2
			left join user_details d on a.manager_owner_id=d.user_id
			left join user_details e on a.owner_id=e.user_id
			left join user_details f on a.stage_manager_owner_id=f.user_id
			left join user_details g on a.stage_owner_id=g.user_id
			left join hierarchy h  on a.opportunity_industry=h.hkey2
			left join hierarchy i  on a.opportunity_location=i.hkey2
			left join sales_stage j on a.opportunity_stage=j.stage_id
            left JOIN user_details k ON k.user_id=(SELECT from_user_id FROM oppo_user_map oum WHERE oum.opportunity_id=a.opportunity_id AND oum.action IN('permanent loss','temporary loss') AND oum.state=1)
            where  a.closed_status=100 and (a.owner_id='$user' or a.stage_owner_id='$user') and a.closed_reason in ('temporary_loss','permanent_loss')
            group by a.opportunity_id");

            $opp_close=$query->result();
           /********** query to check whether logged in user has the authority to delete the lead or not and give authority for reopen and
             to check whether the lead is not closedwon******************/
            for($i=0;$i<count($query->result_array());$i++)
            {
               //echo"SELECT lead_id FROM lead_info where ('$user'=lead_manager_owner or lead_rep_owner='$user') and lead_id='".$opp_close[$i]->lead_cust_id."'";

                //closed won leads cannot be reopened
                $query1=$GLOBALS['$dbFramework']->query("SELECT lead_id FROM lead_info where  lead_id='".$opp_close[$i]->lead_cust_id."' and lead_status<>2");
                //$check_reopen=$query1->result_array();
                if($query1->num_rows()>0)
                {
                   // lead is in progress
                  /* echo"SELECT lead_id,lead_manager_owner,lead_rep_owner FROM lead_info where lead_id='".$opp_close[$i]->lead_cust_id."'
                                                           and lead_status NOT IN(3,4)";*/
                  $query2=$GLOBALS['$dbFramework']->query("SELECT lead_id,lead_manager_owner,lead_rep_owner FROM lead_info where lead_id='".$opp_close[$i]->lead_cust_id."'
                                                           and lead_status IN(3,4);");
                  //$check_reopen1=$query2->result_array();
                  if($query2->num_rows()>0)
                  {
                     $result2=$query2->result_array();
                     $opp_close[$i]->lead_manager_owner=$result2[0]['lead_manager_owner'];
                     $opp_close[$i]->lead_rep_owner=$result2[0]['lead_rep_owner'];
                     $opp_close[$i]->reopen='true';
                     $opp_close[$i]->reopen_reason='Reopening Opportunity will automatically reopen the Lead';
                  }
                  else
                  {
                     $opp_close[$i]->reopen='true';
                     $opp_close[$i]->reopen_reason='';
                  }
                }else{
                  $opp_close[$i]->reopen='false';
                  $opp_close[$i]->reopen_reason='Unable to reopen Opportunity since the Lead is closed won';
                }
                /*************** query to populate the list of activities incase of temporary loss********************/

                $data = array();
			    $data['opportunity_contact'] = $opp_close[$i]->opportunity_contact;
                $contacts = $this->opp_common->fetch_extraDetails($data);
               //print_r($contacts);
    			$contact_array = array();
    			foreach ($contacts as $c) {
    				array_push($contact_array, array('contact_id' => $c->contact_id, 'contact_name' => $c->contact_name));
    			}
    			$opp_close[$i]->contactlist=$contact_array;

                //$query_contactlist=$GLOBALS['$dbFramework']->query("SELECT * FROM contact_details WHERE lead_cust_id='".$opp_close[$i]->lead_cust_id."'");
                //$opp_close[$i]->contactlist=$query_contactlist->result();
            }

            $query_activity=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='activity'");
            $activitydata=$query_activity->result();

            return array(
                  'opportunitydata'=>$opp_close,
                  'activitydata'=>$activitydata
            );

        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    //****************** function to reopen,perm-loss,temp-loss the opportunity**********************************//
    public function changestate($opp_usermap_data,$updateoppdetails,$extradata)
    {
     try{
            if($opp_usermap_data['action']=='reopen')
            {
              // condition to check whether the lead is been closed,if closed then reopen the lead and then go ahead with reopening of opportunity
              //this happens only if the logged in user is the lead manager owner or lead repowner
              if($extradata['leadclosedstatus']==3 || $extradata['leadclosedstatus']==4) //which means lead is 3-temporary or 4-permanent closed
              {
                    $lead1=array(
                    'lead_closed_reason'=>NULL,
                    'lead_status'=>1
                    );
                    $lead2=array(
                    'state'=>0
                    );
                    $lead3=array(
                    'state'=>1,
                    'action'=>'reopened',
                    'from_user_id'=>$opp_usermap_data['from_user_id'],
                    'to_user_id'=>$opp_usermap_data['from_user_id'],
                    'type'=>'lead',
                    'mapping_id'=>uniqid(rand(),TRUE),
                    'module'=>'sales',
                    'timestamp'=>date('Y-m-d H:i:s'),
                    'lead_cust_id' =>$opp_usermap_data['lead_cust_id'],
                    );
                 	$query_lead1= $GLOBALS['$dbFramework']->update('lead_info',$lead1,array('lead_id'=> $opp_usermap_data['lead_cust_id']));
        			$query_lead2= $GLOBALS['$dbFramework']->update('lead_cust_user_map',$lead2, array('lead_cust_id'=> $opp_usermap_data['lead_cust_id']));
        			$query_lead3= $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$lead3);

                    //sending a notification to lead owner and lead_manager_owner that lead is opened
                    $z=2;

                    while($z)
                    {
                          $dt = date('ymdHis');
                          $notify_id= uniqid($dt);
                          $data_notify= array(
                              'notificationID' =>$notify_id,
                              'from_user'=>$opp_usermap_data['from_user_id'],
                              'action_details'=>'Opportunity',
                              'notificationTimestamp'=>date('Y-m-d H:i:s'),
                              'read_state'=>0,
                              'remarks'=>$extradata['remarks'],
                              'notificationShortText'=>'Lead Reopened',
                              'notificationText'=>'Lead '.$extradata['lead_cust_name'].' reopened since Opportunity '.$extradata['opportunity_name'].' was reopened'
                            );
                            if($z==2){
                                $data_notify['to_user']=$extradata['lead_manager_owner'];
                            }else{
                                $data_notify['to_user']=$extradata['lead_rep_owner'];
                            }
                            $z--;
                            $insert_notify = $GLOBALS['$dbFramework']->insert('notifications',$data_notify);
                    }

              }

            }
            if($opp_usermap_data['action']=='temporary loss')
            {
               // check whether the associated lead is in permanent loss state,if yes check whether loggedin user has authority to change the state of the lead
               // if not then prompt the user that he cannot change the state of the opportunity to temporary loss since lead is in permanent loss state
               if($extradata['leadclosedstatus']==4)
               {
                  $auth=$GLOBALS['$dbFramework']->query("SELECT lead_id FROM lead_info where
                                                 ((lead_manager_owner='".$opp_usermap_data['from_user_id']."') or (lead_rep_owner='".$opp_usermap_data['from_user_id']."') )
                                                 and lead_id='".$opp_usermap_data['lead_cust_id']."' ");
                  if($auth->num_rows()<=0)
                  {
                    return 2;
                  }else{

                           $lead1=array(
                                  'lead_closed_reason'=>'temporary_loss',
                                  'lead_status'=>3,
                                  'lead_approach_date' =>$extradata['approachdate'],
                                  );
                          $lead2=array(
                                  'state'=>0
                                  );
                          $lead3=array(
                                  'state'=>1,
                                  'action'=>'closed',
                                  'from_user_id'=>$opp_usermap_data['from_user_id'],
                                  'to_user_id'=>$opp_usermap_data['from_user_id'],
                                  'type'=>'lead',
                                  'mapping_id'=>uniqid(rand(),TRUE),
                                  'module'=>'sales',
                                  'timestamp'=>date('Y-m-d H:i:s'),
                                  'lead_cust_id' =>$opp_usermap_data['lead_cust_id'],
                                  'mapping_id' =>uniqid(rand(),TRUE),
                                  'remarks'=>"Lead closed temporarily due to temporary close of opportunity"
                                  );
                       	$query_lead1= $GLOBALS['$dbFramework']->update('lead_info',$lead1,array('lead_id'=> $opp_usermap_data['lead_cust_id']));
              			$query_lead2= $GLOBALS['$dbFramework']->update('lead_cust_user_map',$lead2, array('lead_cust_id'=> $opp_usermap_data['lead_cust_id']));
              			$query_lead3= $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$lead3);
                   }
               }// end of lead closed state 4
                        // inserting task for temp_loss
                        $dt = date('ymdHis');
                        $lead_reminder_id = '';
                        $lead_reminder_id .= $dt;
                        $lead_reminder_id = uniqid($lead_reminder_id);
                        //calculate the meeting end
                        $duration=$extradata['activityDuration'];
                        $seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
                        $activity_duration = (int)$seconds->getTimestamp();
                        $start = new DateTime($extradata['approachdate']);
                        $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
                        $event_end = $event_end->format('Y-m-d H:i:s');
                        $event_start_date=date('Y-m-d', strtotime($extradata['approachdate']));
                        $event_start_time = date('H:i', strtotime($extradata['approachdate']));
                        $event_start=$start->format('Y-m-d H:i:s');
                        $data_leadreminder = array(
                                        'lead_reminder_id' => $lead_reminder_id,
                                        'opportunity_id' => $opp_usermap_data['stage_id'],
                                        'lead_id'   => $opp_usermap_data['opportunity_id'],
                                        'rep_id'    => $opp_usermap_data['from_user_id'],
                                        'leadempid' => $extradata['contactType'],  //'contactid',
                                        'remi_date' => $event_start_date,
                                        'rem_time'  => $event_start_time,
                                        'conntype'  => $extradata['futureActivity'],
                                        'status'    => "scheduled",
                                        'meeting_start'    => $event_start,
                                        'meeting_end'      => $event_end,
                                        'addremtime'       => $extradata['alertBefore'],
                                        'timestamp'        => date('Y-m-d H:i:s'),
                                        'remarks'          => $extradata['remarks'],
                                        'event_name'       => $extradata['title'],
                                        'duration'         => $extradata['activityDuration'],
                                        'type' => "opportunity",
                                        'created_by'=>$opp_usermap_data['from_user_id'],
                                        'module_id'=>'manager'
                        );

                        //inserting data in lead reminder
                        $insert_leadreminder = $GLOBALS['$dbFramework']->insert('lead_reminder',$data_leadreminder);
            }

            $update = $GLOBALS['$dbFramework']->update('opportunity_details' ,$updateoppdetails, array('opportunity_id' => ($opp_usermap_data['opportunity_id'])));
            if($update)
            {
                //this will help to show only those opportunities which are closed and not reopened yet.
               $update_opp2 = $GLOBALS['$dbFramework']->query("Update oppo_user_map set state='0' where opportunity_id='".$opp_usermap_data['opportunity_id']."' and
                                                       (action='permanent loss' or action='temporary loss')");

               $insert = $GLOBALS['$dbFramework']->insert('oppo_user_map',$opp_usermap_data);


               //insertion in notification table

                $dt = date('ymdHis');
                $notify_id= uniqid($dt);
                $data_notify= array(
                              'notificationID' =>$notify_id,
                              'from_user'=>$opp_usermap_data['from_user_id'],
                              'to_user'=>$extradata['stage_manager_owner_id'],
                              'action_details'=>'Opportunity',
                              'notificationTimestamp'=>date('Y-m-d H:i:s'),
                              'read_state'=>0,
                              'remarks'=>$extradata['remarks']
                            );
                 if($extradata['stage_manager_owner_id']==null)
                 {
                        $data_notify['to_user']=$extradata['manager_owner_id'];
                 }
                if($opp_usermap_data['action']=='permanent loss')
                {
                     $data_notify['notificationShortText']='Opportunity State changed to Closed(Permanent)';
                     $data_notify['notificationText']='Opportunity '.$extradata['opportunity_name'].' closed';
                }else if($opp_usermap_data['action']=='temporary loss'){
                      if($extradata['leadclosedstatus']==4)
                        {
                           $data_notify['notificationShortText']='State of Lead and Opportunity changed to Closed(Temporary)';
                           $data_notify['notificationText'] =$extradata['lead_cust_name'].' Lead closed Since Opportunity '.$extradata['opportunity_name'].' closed and a reminder task created ';
                        }else{
                           $data_notify['notificationShortText']='State of Opportunity changed to Closed(Temporary)';
                           $data_notify['notificationText'] ='Opportunity '.$extradata['opportunity_name'].' closed and a reminder task created ';
                        }
                }else if($opp_usermap_data['action']=='reopen'){

                        if($extradata['leadclosedstatus']==3 || $extradata['leadclosedstatus']==4) //which means lead is 3-temporary or 4-permanent closed
                        {
                           $data_notify['notificationShortText']='Lead and Opportunity re-opened';
                           $data_notify['notificationText'] =' Lead '.$extradata['lead_cust_name'].' and Opportunity '.$extradata['opportunity_name'].' reopened';
                        }else{
                           $data_notify['notificationShortText']='Opportunity re-opened';
                           $data_notify['notificationText'] ='Opportunity '.$extradata['opportunity_name'].' reopened';
                        }
                }
                $insert_notify = $GLOBALS['$dbFramework']->insert('notifications',$data_notify);
               return $insert;
            }else{
               return 0;
            }
    	} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    //****************** function to reopen,perm-loss,temp-loss the opportunity**********************************//
    public function reopen($opp_usermap_data,$updateoppdetails,$extradata)
    {
     try{
              // condition to check whether the lead is been closed,if closed then reopen the lead and then go ahead with reopening of opportunity
              //this happens only if the logged in user is the lead manager owner or lead repowner
              if($extradata['leadclosedstatus']==3 || $extradata['leadclosedstatus']==4) //which means lead is 3-temporary or 4-permanent closed
              {
                    $lead1=array(
                    'lead_closed_reason'=>NULL,
                    'lead_status'=>1
                    );
                    $lead2=array(
                    'state'=>0
                    );
                    $lead3=array(
                    'state'=>1,
                    'action'=>'reopened',
                    'from_user_id'=>$opp_usermap_data['from_user_id'],
                    'to_user_id'=>$opp_usermap_data['from_user_id'],
                    'type'=>'lead',
                    'mapping_id'=>uniqid(rand(),TRUE),
                    'module'=>'sales',
                    'timestamp'=>date('Y-m-d H:i:s'),
                    'lead_cust_id' =>$opp_usermap_data['lead_cust_id'],
                    );
                 	$query_lead1= $GLOBALS['$dbFramework']->update('lead_info',$lead1,array('lead_id'=> $opp_usermap_data['lead_cust_id']));
        			$query_lead2= $GLOBALS['$dbFramework']->update('lead_cust_user_map',$lead2, array('lead_cust_id'=> $opp_usermap_data['lead_cust_id']));
        			$query_lead3= $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$lead3);
              }

            $update = $GLOBALS['$dbFramework']->update('opportunity_details' ,$updateoppdetails, array('opportunity_id' => ($opp_usermap_data['opportunity_id'])));
            if($update)
            {
                //this will help to show only those opportunities which are closed and not reopened yet.
               $update_opp2 = $GLOBALS['$dbFramework']->query("Update oppo_user_map set state='0' where opportunity_id='".$opp_usermap_data['opportunity_id']."' and
                                                       (action='permanent loss' or action='temporary loss')");


               $insert = $GLOBALS['$dbFramework']->insert('oppo_user_map',$opp_usermap_data);



               //insertion in notification table
                $dt = date('ymdHis');
                $notify_id= uniqid($dt);
                $data_notify= array(
                              'notificationID' =>$notify_id,
                              'from_user'=>$opp_usermap_data['from_user_id'],
                              'to_user'=>$extradata['stage_manager_owner_id'],
                              'action_details'=>'Opportunity',
                              'notificationTimestamp'=>date('Y-m-d H:i:s'),
                              'read_state'=>0,
                              'remarks'=>$extradata['remarks']
                            );

                 if($extradata['stage_manager_owner_id']==null)
                 {
                        $data_notify['to_user']=$extradata['manager_owner_id'];
                 }

                        if($extradata['leadclosedstatus']==3 || $extradata['leadclosedstatus']==4) //which means lead is 3-temporary or 4-permanent closed
                        {
                           $data_notify['notificationShortText']='Lead and Opportunity re-opened';
                           $data_notify['notificationText'] =' Lead '.$extradata['lead_cust_name'].' and Opportunity '.$extradata['opportunity_name'].' reopened by '.$extradata['stage_owner_name'];
                        }else{
                           $data_notify['notificationShortText']='Opportunity re-opened';
                           $data_notify['notificationText'] ='Opportunity '.$extradata['opportunity_name'].' reopened by '.$extradata['stage_owner_name'];
                        }
                $insert_notify = $GLOBALS['$dbFramework']->insert('notifications',$data_notify);
                if(isset($extradata['lead_manager_owner']) && isset($extradata['lead_rep_owner']))
                {
                           //sending a notification to lead owner and lead_manager_owner that lead is reopened
                      $z=2;
                      while($z)
                      {
                            $dt = date('ymdHis');
                            $notify_id= uniqid($dt);
                            $data_notify= array(
                                'notificationID' =>$notify_id,
                                'from_user'=>$opp_usermap_data['from_user_id'],
                                'action_details'=>'Opportunity',
                                'notificationTimestamp'=>date('Y-m-d H:i:s'),
                                'read_state'=>0,
                                'remarks'=>$extradata['remarks'],
                                'notificationShortText'=>'Lead Reopened',
                                'notificationText'=>'Lead '.$extradata['lead_cust_name'].' reopened since Opportunity '.$extradata['opportunity_name'].' was reopened'
                              );
                              if($z==2){
                                  $data_notify['to_user']=$extradata['lead_manager_owner'];
                                  if(($extradata['lead_manager_owner']!=$extradata['manager_owner_id'])||($extradata['stage_manager_owner_id']!=$extradata['lead_manager_owner']))
                                  {
                                    $insert_notify = $GLOBALS['$dbFramework']->insert('notifications',$data_notify);
                                  }
                              }else{
                                  $data_notify['to_user']=$extradata['lead_rep_owner'];
                                  $insert_notify = $GLOBALS['$dbFramework']->insert('notifications',$data_notify);
                              }
                              $z--;

                      }
                    //sending a notification to lead owner and lead_manager_owner that lead is reopened*******end
                }
                return $insert;
            }else{
               return 0;
            }
    	} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    //-----------Function to insert a future task in lead reminder after temporary loss of opportunity-------//
    public function insert_lead_reminder($leadreminder_data)
    {
      try{

    		$insert_leadreminder = $GLOBALS['$dbFramework']->insert('lead_reminder',$leadreminder_data);
    		return $insert_leadreminder;
    	} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    //-----Function to get the activity for the temp_loss of opportunities -----------//
    public function get_activityList()
    {
      try{
    	    $query_activity=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='activity'");
            return $query_activity->result();
    	} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
     //-----Function to get the activity for the temp_loss of opportunities -----------//
    public function get_contactdetails($contactid)
    {
      try{
    	   $query_contactlist=$GLOBALS['$dbFramework']->query("SELECT * FROM contact_details WHERE contact_id='".$contactid."'");
           return $query_contactlist;
    	} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
//-------Function that fetch an opportunity owner------//
    public function opp_owner($opp_id){
    	try{
    		$query=$GLOBALS['$dbFramework']->query("SELECT owner_status FROM opportunity_details WHERE opportunity_id='$opp_id'");
    		return $query->result();
    	} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

//------Function to accept opportunity by updating-----//
    public function accept_opp($opp_id,$data){
        try{
            $update = $GLOBALS['$dbFramework']->update('opportunity_details' ,$data, array('opportunity_id' => ($opp_id)));
            return $update;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
//-----Function to update Transaction table----//
   	public function update_transaction($opportunity_id){
   		try{
   			$query=$GLOBALS['$dbFramework']->query("
   				UPDATE oppo_user_map
   				SET state='0'
   				WHERE opportunity_id='$opportunity_id' and module='sales'
   				and action in ('ownership reassigned','ownership assigned', 'ownership accepted','ownership rejected')");
   			return $query;
   		} catch (LConnectApplicationException $e) {
   			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
   			throw $e;
   		}
    }
//------Function to update stage ownership in transaction table----//
    public function update_stage_transaction($opportunity_id){
    	try{
    		$query=$GLOBALS['$dbFramework']->query("
    			UPDATE oppo_user_map
    			SET state='0'
    			WHERE opportunity_id='$opportunity_id' and module='sales'
    			and action in ('stage reassigned','stage assigned', 'stage accepted','stage rejected') ");
    		return $query;
    	} catch (LConnectApplicationException $e) {
    		$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
    		throw $e;
    	}
    }
//-------Insert into transaction table----//
    public function insert_transaction($data) {
    	try{
    		$insert = $GLOBALS['$dbFramework']->insert('oppo_user_map',$data);
    		return $insert;
    	} catch (LConnectApplicationException $e) {
    		$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
    		throw $e;
    	}
    }
//-----Function that fetches Stage owners----//
    public function stage_owner($opp_id){
    	try{
    		$query=$GLOBALS['$dbFramework']->query("select * from opportunity_details where opportunity_id='$opp_id'");
    		return $query->result();
    	} catch (LConnectApplicationException $e) {
    		$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
//----Function to reject opportunity ownership----//
    public function assign_count($opportunity){
        try {
            $userid=$this->session->userdata('uid');
            $query1=$GLOBALS['$dbFramework']->query("
            	SELECT *
            	from oppo_user_map
            	where opportunity_id='$opportunity' and (action IN ('ownership assigned','ownership reassigned')) and to_user_id='".$userid."' and state='1' and module='sales'");
            $ss = $query1->result();
            $data2= array(
                'mapping_id'=>uniqid(rand()),
                'opportunity_id'=>$opportunity,
                'lead_cust_id'=> $ss[0]->lead_cust_id,
                'from_user_id'=> $userid,
                'to_user_id'=> $ss[0]->from_user_id,
                'cycle_id'=> $ss[0]->cycle_id,
                'stage_id'=>$ss[0]->stage_id,
                'module'=>'sales' ,
                'sell_type'=> $ss[0]->sell_type,
                'timestamp'=> date('Y-m-d H:i:s'),
                'action' => 'ownership rejected',
                'state' => '1'
            );
            $insert = $GLOBALS['$dbFramework']->insert('oppo_user_map',$data2);
            $query4=$GLOBALS['$dbFramework']->query("
                	UPDATE oppo_user_map
                	SET state='0'
                	WHERE opportunity_id='$opportunity' and state='1' and module='sales' and
                	mapping_id ='".$ss[0]->mapping_id."' and to_user_id='".$userid."'");

            $total_assigned = $query1->num_rows();

            $query2=$GLOBALS['$dbFramework']->query("
            	SELECT *
            	FROM oppo_user_map
            	WHERE opportunity_id='$opportunity' and (action='ownership rejected') and state='1' and module='sales'");

            $total_rejects = $query2->num_rows();

            if($total_rejects==$total_assigned){
               /* query4 was here before */
                $query3=$GLOBALS['$dbFramework']->query("
                	UPDATE opportunity_details
                	SET owner_status='3'
                	WHERE opportunity_id='$opportunity'");
            }
            return true;
        } catch (LConnectApplicationException $e){
        	$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
  //------Function that checks if ownership/Stage ownership is claimed----//
    public function check_state($op_id,$opp_reject){
        $return_status = array();
        for($i=0;$i<count($opp_reject);$i++){
            if($opp_reject[$i]=='Ownership'){
                try {
                    $query=$GLOBALS['$dbFramework']->query("select owner_status from opportunity_details where opportunity_id='$op_id'");
                   $opp= $query->result();
                    $owner_status= $opp[0]->owner_status;
                    $return_status['Ownership'] = $owner_status;
                } catch (LConnectApplicationException $e) {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                    throw $e;
                }
            } else if($opp_reject[$i]=='Stage_Ownership') {
                try {
                    $query=$GLOBALS['$dbFramework']->query("select stage_owner_status from opportunity_details where opportunity_id='$op_id'");
                    $opp= $query->result();
                    $owner_status= $opp[0]->stage_owner_status;
                    $return_status['Stage_Ownership'] = $owner_status;

                } catch (LConnectApplicationException $e) {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                    throw $e;
                }
            }
        }
        return $return_status;
    }
//-----Function to reject stage ownership of opportunity---//
     public function assign_count_stage($opportunity){
        try {
            $userid=$this->session->userdata('uid');
            $query1=$GLOBALS['$dbFramework']->query("
            	SELECT *
            	from oppo_user_map
            	where opportunity_id='$opportunity' and (action IN ('stage assigned','stage reassigned')) and state='1' and to_user_id='".$userid."' and module='sales'");

            $ss = $query1->result();

            $data2= array(
                'mapping_id'=>uniqid(rand()),
                'opportunity_id'=>$opportunity,
                'lead_cust_id'=> $ss[0]->lead_cust_id,
                'from_user_id'=> $userid,
                'to_user_id'=> $ss[0]->from_user_id,
                'cycle_id'=> $ss[0]->cycle_id,
                'stage_id'=>$ss[0]->stage_id,
                'module'=>'sales' ,
                'sell_type'=> $ss[0]->sell_type,
                'timestamp'=> date('Y-m-d H:i:s'),
                'action' => 'stage rejected',
                'state' => '1'
            );
            $insert = $GLOBALS['$dbFramework']->insert('oppo_user_map',$data2);
            $query4=$GLOBALS['$dbFramework']->query("
                	UPDATE oppo_user_map
                	SET state='0'
                	WHERE opportunity_id='$opportunity' and state='1' and module='sales' and
                	mapping_id ='".$ss[0]->mapping_id."' and to_user_id='".$userid."' ");

            $total_assigned = $query1->num_rows();

            $query2=$GLOBALS['$dbFramework']->query("
            	SELECT *
            	FROM oppo_user_map
            	WHERE opportunity_id='$opportunity' and (action='stage rejected') and state='1' and module='sales'");

            $total_rejects = $query2->num_rows();

            if($total_rejects==$total_assigned){
                 /* query4 was here before */
                $query3=$GLOBALS['$dbFramework']->query("
                	UPDATE opportunity_details
                	SET stage_owner_status='3'
                	WHERE opportunity_id='$opportunity'");
            }
            return true;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    //----Function to insert into 'notifications' table----//
	public function rej_opp_notification($data){
		try {
			$insertQuery = $GLOBALS['$dbFramework']->insert('notifications', $data);
			return $insertQuery;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
    //----Function to get the owner who assigned the opp to rep from 'opp user map' table//
	public function get_from_userid($op_id){
		try {
			$Query = $GLOBALS['$dbFramework']->query("SELECT distinct from_user_id FROM oppo_user_map where opportunity_id='".$op_id."'
                                                    and action = 'ownership assigned' or 'stage assigned' or 'ownership reassigned' or 'stage reassigned'; ");
            if($Query->num_rows()>0){
                          foreach ($Query->result() as $row1)
                          {
                               $from_userid=$row1->from_user_id;
                          }
            }else{
                $from_userid="";
            }
			return $from_userid;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}

    //----Function to get the owner who assigned the opp to manager from 'opp user map' table//
	public function get_from_userid1($op_id,$gvnuserid){
		try {
			$Query = $GLOBALS['$dbFramework']->query("SELECT distinct from_user_id FROM oppo_user_map where opportunity_id='".$op_id."' and to_user_id='".$gvnuserid."'
                                                and action = 'ownership assigned' or 'stage assigned' or 'ownership reassigned' or 'stage reassigned'; ");
            if($Query->num_rows()>0){
                          foreach ($Query->result() as $row1)
                          {
                               $from_userid=$row1->from_user_id;
                          }
            }else{
                $from_userid="";
            }
			return $from_userid;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
    public function getowner($op_id,$typeown){
		try {
		    if($typeown=='oppowner'){
                $Query = $GLOBALS['$dbFramework']->query("select oppowner from opportunity_details where opportunity_id='".$op_id."' ");
                if($Query->num_rows()>0){
                          foreach ($Query->result() as $row1)
                          {
                               $oppowner=$row1->oppowner;
                          }
                }else{
                    $oppowner="";
                }
		    }else{

                $Query = $GLOBALS['$dbFramework']->query("SELECT * FROM oppo_user_map where opportunity_id='".$op_id."'
                                                            and action='stage progressed' and module='sales' and state=0 order by id desc limit 1;");
                if($Query->num_rows()>0){
                          foreach ($Query->result() as $row1)
                          {
                               $oppowner=$row1->to_user_id;
                          }
                }else{
                    $oppowner="";
                }
		    }
            if($typeown=='stagemanager'){
                $Query = $GLOBALS['$dbFramework']->query("select stage_manager_owner_id from opportunity_details where opportunity_id='".$op_id."' ");
                if($Query->num_rows()>0){
                          foreach ($Query->result() as $row1)
                          {
                               $oppowner=$row1->stage_manager_owner_id;
                          }
                }else{
                    $oppowner="";
                }
		    }


			return $oppowner;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
    public function getrepmgr($uid){
		try {
		        $Query = $GLOBALS['$dbFramework']->query("select reporting_to from user_details where user_id='".$uid."' ");
                if($Query->num_rows()>0){
                          foreach ($Query->result() as $row1)
                          {
                               $oppowner=$row1->reporting_to;
                          }
                }else{
                    $oppowner="";
                }
                return $oppowner;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}

}
?>
