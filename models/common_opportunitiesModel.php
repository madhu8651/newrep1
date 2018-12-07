<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();
$GLOBALS['$log'] = Logger::getLogger('manager_opportunitiesModel');

class common_opportunitiesModel extends CI_Model	{

	function __construct()	{
		parent::__construct();
	}
//---getting user sale types---//
	public function fetch_userPrivilages($user_id){
		try {
			$sell_types = array();
			$finalArray = array();
			$finalArray['leads'] = 0;
			$finalArray['customers'] = 0;
			$query = $GLOBALS['$dbFramework']->query("
				SELECT map_id from user_mappings where user_id='$user_id' and map_type='sell_type'");
			foreach ($query->result_array() as $row)	{
				$sell_type = $row['map_id'];
				array_push($sell_types, $sell_type);
				if ($sell_type=='new_sell') {
					$finalArray['leads']=1;
				} else if (($sell_type=='cross_sell')||($sell_type='up_sell')){
					$finalArray['customers']=1;
				}
			}
			$finalArray['sell_types'] = $sell_types;
			return $finalArray;
		} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//---get detailed hierarchy info for given hierarchy class---//
	public function getHierarchyRows($ids, $hierarchy_class){
		//ids - comma separated hkey2 or hierarchy_ids
		//hierarchy class - self explanatory
		try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT h.hkey2 AS ".$hierarchy_class."_id, h.hvalue2 AS ".$hierarchy_class."_name
				FROM hierarchy AS h, hierarchy_class AS hc
				WHERE hc.Hierarchy_Class_Name='$hierarchy_class' AND h.hierarchy_class_id=hc.Hierarchy_Class_ID
				AND (h.hierarchy_id IN ('$ids')) OR (h.hkey2 IN ('$ids'))
				GROUP BY h.hierarchy_id");
			return $query->result();
		} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------get child node for parent node for given user---//
	public function getChildrenForParent($user_id) {
		try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT user_id, reporting_to FROM user_details");
			$full_structure = $query->result();
			$allParentNodes = [];
			if (version_compare(phpversion(), '7.0.0', '<')) {
			  // php version isn't high enough to support array_column
				foreach($full_structure as $row)  {
					$allParentNodes[$row->user_id] = $row->reporting_to;
				}
			} else {
			  $allParentNodes = array_column(
					  $full_structure,
					  'reporting_to',
					  'user_id');
			}
			$childNodes = array();
			$this->fetchChildNodes($user_id, $childNodes, $allParentNodes);
			if (count($childNodes) == 0) {
				return '';
			}
			$ids = implode("','", $childNodes);
			return $ids;
		} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------reccursive helper function for getChildrenForParent(), getHierarchyRows()-----//
	private function fetchChildNodes($givenID, & $childNodes, $allParentNodes)  {
		foreach ($allParentNodes as $user_id => $reporting_to) {
			if ($reporting_to == $givenID)  {
				array_push($childNodes, $user_id);
				$this->fetchChildNodes($user_id, $childNodes, $allParentNodes);
			}
		}
	}

	#--------------------------------------------------------------------------------#
//-----fetch contacts for given Lead/cust id---//
	public function fetch_Contacts($target_id, $target){
		try {
			if ($target == 'Lead') {
				$query = $GLOBALS['$dbFramework']->query("
					SELECT  contact_id, contact_name
					FROM `contact_details` cd, lead_info li
					WHERE (li.lead_id='$target_id' AND (cd.lead_cust_id=li.customer_id or cd.lead_cust_id=li.lead_id))
					GROUP BY cd.contact_id");
				return $query->result();

			} else if ($target == 'Customer') {
				$query = $GLOBALS['$dbFramework']->query("
					SELECT cd.contact_id, cd.contact_name
    	            FROM contact_details cd
	                WHERE cd.lead_cust_id = '$target_id' OR cd.lead_cust_id IN (
	                SELECT lead_id
	                FROM customer_info
	                WHERE customer_id = '$target_id');");
				return $query->result();
			}
		} catch (LConnectApplicationException $e) {
	        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
	        throw $e;
	    }
	}
//-----fetch products for user considering sell type---//
	public function fetch_Products($lead_cust_id, $user_id, $sell_type){
		//fetching products associated with a lead/customer -> string separated by ,
		try {
			$result = array();
			if (strtolower($sell_type) == 'new_sell') {
				$leadProductsQuery = $GLOBALS['$dbFramework']->query('
					SELECT lpm.product_id
					FROM `lead_product_map` lpm, `hierarchy` h, `user_mappings` um
					WHERE lpm.lead_id = "'.$lead_cust_id.'" AND
					um.user_id="'.$user_id.'" AND
					um.map_type="product" AND
					lpm.product_id=um.map_id AND
					h.hkey2 = um.map_id');
				$result = $leadProductsQuery->result();
			}
			else if (strtolower($sell_type) == 'up_sell') {
				// take customer ID, go to product purchase info, and get products which only can be sold by the user
				$query = $GLOBALS['$dbFramework']->query("
					SELECT um.map_id as product_id
					FROM product_purchase_info ppi, user_mappings um
					WHERE customer_id='$lead_cust_id' and um.user_id='$user_id' and um.map_type='product'
					and ppi.product_id=um.map_id
					GROUP BY um.map_id");
				$result = $query->result();
			}
			else if (strtolower($sell_type) == 'cross_sell') {
				$query = $GLOBALS['$dbFramework']->query("
					SELECT um.map_id as product_id
					FROM user_mappings as um, hierarchy h
					WHERE um.user_id='$user_id' and um.map_type='product'
					and um.map_id NOT IN (select product_id from product_purchase_info where customer_id='$lead_cust_id')
					GROUP BY um.map_id");
				$result = $query->result();
			}
			else if (strtolower($sell_type) == 'renewal') {
				// take customer ID, go to product purchase info, and get products which only can be sold by the user
				$query = $GLOBALS['$dbFramework']->query("
					SELECT um.map_id as product_id
					FROM product_purchase_info ppi, user_mappings um
					WHERE customer_id='$lead_cust_id' and um.user_id='$user_id' and um.map_type='product'
					and ppi.product_id=um.map_id
					GROUP BY um.map_id");
				$result = $query->result();
			}
			$productsArray = array();
			foreach ($result as $product) {
				array_push($productsArray, $product->product_id);
			}
			$productString = implode('\',\'', $productsArray);
			$leadProducts = $this->getHierarchyRows($productString, 'products');
			if (count($leadProducts) == 0) {
				return;
			}
			return $leadProducts;
		} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//-----fetch currencies for given product----//
	public function fetch_currencies($product_id, $user_id){
		try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT c.currency_id, c.currency_name
				FROM user_mappings AS um, currency AS c, user_details AS ud, hierarchy AS h
				WHERE um.user_id='$user_id' AND  ud.user_id=um.user_id AND um.map_type='product'
				AND um.map_key='currency' AND um.map_value=c.currency_id
				AND h.hkey2='$product_id' AND um.map_id = h.hkey2
				GROUP BY c.currency_id
				");
			$prompt_msg = '';
			$currencies = $query->result();
			if (count($currencies) < 0) {
				$prompt_msg = 'Currencies not found for the product';
			}
			$data = array('currency' => $currencies, 'msg' => $prompt_msg);
			return $data;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//-----Given lead & product id it will fetch sale type----//
	public function fetch_sellType($product_id, $target_id, $target_type, $user_id){
		try {
			$user_priv = $this->fetch_userPrivilages($user_id);
			$user_sell_types = $user_priv['sell_types'];
			$sellType = '';
			$prompt_msg = '';
	        if (strtolower($target_type) == 'lead') {
	        	$sellType = 'new_sell';
	        } else if (strtolower($target_type) == 'customer') {
	        	$query = $GLOBALS['$dbFramework']->query("
					SELECT purchase_end_date>current_timestamp() as product_validity
					FROM product_purchase_info
					WHERE customer_id='$target_id' AND product_id='$product_id'
	        	"); // query to get all entries of valid purchases by a customer for a given product
	        	$sell_type_result = $query->result();
	        	$var = count($sell_type_result);
	        	if ($var == 0) {
	        		$sellType = 'cross_sell'; // it's cross_sell as there are no valid purchases for given product
	        	} else {
					$sellType = 'up_sell'; // it's up_sell as there are/were already few valid purchases
	        	}
	        }
	        if (in_array($sellType, $user_sell_types) == false) {
	        	return array(
	        		'sell_type'=>'',
	        		'prompt'=>'You are not permitted to sell this.');
	        } else {
	        	return array(
	        		'sell_type'=>$sellType,
	        		'prompt'=>'');
	        }
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//---insert qualifier answers for DB----//
	public function insert_data($lead_qualifier_id,$type1_2,$type3,$stage_id,$data1){
		$response=1;  // all options selected are correct
		$dt=date('ymdHis');
		$qualifierid=uniqid($dt);
		$action='passed qualifier';
		foreach($type1_2 as $value){
			$questype=$value->questype;
			$quesid=$value->quesid;
			$ansid=$value->ansid;
			if($questype==1) {
				$query=$this->db->query("select * from qualifier_questions where lower(question_id)=lower('$quesid')");
				if($query->num_rows()>0) {
					foreach ($query->result() as $row) {
						$right_ans= $row->answer;
					}
				}
				$query1=$this->db->query("select * from qualifier_answers where lower(answer_id)=lower('$ansid')");
				if($query1->num_rows()>0) {
					foreach ($query1->result() as $row) {
						$given_ans= $row->answer_text;
					}
				}
				if($right_ans<>$given_ans) {
					$response=0;  // all options selected are not correct
					$status='fail';
					$action='failed qualifier';
				} else {
					$status='success';
			}
			$insquery = "INSERT INTO qualifier_tran_details(rep_id, leadid, opportunity_id, stageid, question_id, answer_id, rt_answer, qualifier_tran_id, timestamp, remarks)
			VALUES ('".$data1['repid']."','".$data1['leadid']."','".$data1['oppid']."','$stage_id','$quesid','$ansid','$right_ans','$qualifierid','".date('Y-m-d H:i:s')."','$status')";
			$this->db->query($insquery);
			}
			else if($questype==2) {
				$insquery = "INSERT INTO qualifier_tran_details(rep_id,leadid,opportunity_id,stageid,question_id,answer_id,qualifier_tran_id, timestamp, remarks)
				VALUES ('".$data1['repid']."','".$data1['leadid']."','".$data1['oppid']."','$stage_id','$quesid','$ansid','$qualifierid','".date('Y-m-d H:i:s')."','success')";
				$this->db->query($insquery);
			}
		}
		foreach($type3 as $value){
			$quesid=$value->quesid;
			$ans=$value->ans;
			$insquery = "INSERT INTO qualifier_tran_details(stageid,question_id,qualifier_tran_id,rep_id,leadid,opportunity_id, timestamp, remarks, rt_answer)
			VALUES ('$stage_id','$quesid','$qualifierid','".$data1['repid']."','".$data1['leadid']."','".$data1['oppid']."','".date('Y-m-d H:i:s')."','success','".$ans."')";
			$this->db->query($insquery);
		}
		$insquery = "INSERT INTO oppo_user_map
		(mapping_id, opportunity_id, lead_cust_id, from_user_id, to_user_id, cycle_id, stage_id, action, timestamp)
		VALUES ('".$qualifierid."','".$data1['oppid']."','".$data1['leadid']."','".$data1['repid']."','".$data1['repid']."','0','$stage_id','$action','".date('Y-m-d H:i:s')."')";
		$this->db->query($insquery);
		//echo $response;
		return $response;
	}
//----given Four parameters(P.I,L,S) it will fetch corresponding sales Cycle and its first stage-----//
	public function fetch_SalesCycle_firstStage($data){
        try {
	        $id1 = $data['product_id'];
	        $id2 = $data['industry_id'];
	        $id3 = $data['location_id'];
	        $sell_type = $data['sell_type'];

	        $query = $GLOBALS['$dbFramework']->query('
	        	SELECT scp.cycle_id,
				(select ss.stage_id
				 from stage_cycle_mapping AS scm, sales_stage AS ss
				 where scm.cycle_id=scp.cycle_id AND ss.stage_id=scm.stage_id AND ss.stage_sequence=6) AS stage_id
				FROM `sales_cycle_parameters` AS scp
				WHERE scp.parameter_product_node = "'.$id1.'" AND scp.parameter_industry_node = "'.$id2.'" AND
				      scp.parameter_location_node = "'.$id3.'" AND scp.parameter_for="'.$sell_type.'" AND scp.cycle_togglebit = 1
				GROUP BY scp.parameter_id, scp.cycle_id');
			return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//-----Fetch all the attributes of opportunity-----//
	public function fetch_oppoAttr($opp_id)	{
		try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT opportunity_numbers AS numbers,
				opportunity_date as close_date,
				opportunity_value as value,
				opportunity_rate as rate,
				opportunity_score as score,
				opportunity_customer_code as customer_code,
				opportunity_priority as priority,
				opportunity_stage as stage,
				opportunity_currency as currency
				FROM opportunity_details
				WHERE opportunity_id='$opp_id'
				");
			return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//-----Fetch attributes assigned for given stage----//
	public function fetch_stageAttributes($cycle_id, $stage_id){
		try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT ssa.stage_id AS stage_id, ss.stage_name AS stage_name, ss.stage_sequence AS seq_no, ssa.attribute_name AS attribute_name, ssa.attribute_value AS attribute_value
				FROM `stage_cycle_mapping` AS scm, `sales_stage` AS ss, `sales_stage_attributes` AS ssa
				WHERE scm.`cycle_id`='$cycle_id'
				AND ss.stage_id='$stage_id'
				AND ss.`stage_id`=scm.`stage_id`
				AND scm.`stage_id`=ssa.`stage_id`
				AND ssa.attribute_remarks IS NULL
				ORDER BY ss.`stage_sequence`");
			return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------fetch the last/closed stage for a given cycle-----//
	public function fetch_close_stage($cycle_id)	{
		try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT ss.stage_id
				FROM stage_cycle_mapping AS scm, sales_stage AS ss
				where scm.cycle_id='$cycle_id' AND ss.stage_sequence=100 AND ss.stage_id=scm.stage_id
				GROUP BY ss.stage_id");
			return $query->result();
		} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
		}
	}

	#--------------------------------------------------------------------------------#
//----Fetch contact details of an opportunity-----//
	public function fetch_extraDetails($data){
	    try {
			$employeeid = $data['opportunity_contact'];
			$employeeid = explode(':', $employeeid);
			$employeeid = implode('\',\'', $employeeid);

			$query = $GLOBALS['$dbFramework']->query("
				SELECT cd.contact_id AS contact_id, cd.contact_name AS contact_name
				FROM `contact_details` AS cd
				WHERE cd.contact_id IN ('$employeeid')");
			return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//-----Fetch complete data for a given opportunity-----//
	public function fetch_OpportunityDetails($opp_id, $user_id){
		//from opp_id fetch - opportunity_name, leadid, opportunity_prod, opportunity_contact, opportunity_stage, cycle_id, owner_id, stage_owner_id, sell_type
		//with the obtained stage_id, get it's stage attributes
		try {
			$user=$this->session->userdata('uid');
			$children = $user."','";
			$children .= $this->getChildrenForParent($user);
			$viewData = array();
			$opportunity_query = $GLOBALS['$dbFramework']->query("
				SELECT od.opportunity_id as opportunity_id, od.opportunity_name as opportunity_name,
                od.opportunity_contact as opportunity_contact, od.cycle_id as cycle_id, od.sell_type as sell_type,
				od.opportunity_stage, ss.stage_name,scm.remarks AS stage_desc,
                od.opportunity_product, hProd.hvalue2 AS product_name,
                od.opportunity_industry, hInd.hvalue2 AS industry_name,
                od.opportunity_location,hLoc.hvalue2 AS location_name,
                od.opportunity_currency as opportunity_currency, c.currency_name as currency_name,
				od.opportunity_value as opportunity_value,
                od.opportunity_numbers as opportunity_numbers,
                od.opportunity_date as opportunity_date,
				od.opportunity_rate as opportunity_rate,
				od.opportunity_score as opportunity_score,
				od.opportunity_customer_code as opportunity_customer_code,
				od.opportunity_priority as opportunity_priority,
                od.closed_reason as closed_reason,
                od.created_timestamp as created_time,
				od.lead_cust_id as lead_cust_id, (select CASE od.sell_type
					WHEN 'new_sell' THEN (select lead_name from lead_info where lead_id=od.lead_cust_id)
					ELSE (select customer_name from customer_info where customer_id=od.lead_cust_id)
				END) AS lead_name,
				(CASE WHEN (od.closed_reason IS NULL) AND (od.owner_id IN ('$children') OR od.manager_owner_id IN ('$children'))
				THEN 1
				ELSE 0 END) AS canReassign,

	            od.owner_id as owner_id, od.owner_status as owner_status,
	            coalesce((select user_name from user_details WHERE user_id=od.owner_id),'-') AS owner_name,

                od.manager_owner_id as manager_owner_id, od.owner_manager_status as owner_manager_status,
                coalesce((select user_name from user_details WHERE user_id=od.manager_owner_id),'-') AS manager_owner_name,

				od.stage_owner_id as stage_owner_id, od.stage_owner_status as stage_owner_status,
				coalesce((select user_name from user_details WHERE user_id=od.stage_owner_id),'-') AS stage_owner,

                od.stage_manager_owner_id as stage_manager_owner_id, od.stage_manager_owner_status as stage_manager_owner_status,
                coalesce((select user_name from user_details WHERE user_id=od.stage_manager_owner_id),'-') AS stage_manager_owner_name

				FROM opportunity_details AS od
				LEFT JOIN currency c ON od.opportunity_currency=c.currency_id
                LEFT JOIN sales_stage ss ON od.opportunity_stage=ss.stage_id
                LEFT JOIN stage_cycle_mapping scm ON  od.opportunity_stage=scm.stage_id
                LEFT JOIN hierarchy hProd ON od.opportunity_product = hProd.hkey2
                LEFT JOIN hierarchy hInd ON od.opportunity_industry = hInd.hkey2
                LEFT JOIN hierarchy hLoc ON od.opportunity_location = hLoc.hkey2
				WHERE od.opportunity_id='$opp_id'
				GROUP BY od.opportunity_id;");

			$array = $opportunity_query->result();
            // the lead_manager_owner or the lead_rep_owner and any one above them  up the heirarchy can close the lead and its activities

            $query_leadclosebit=$GLOBALS['$dbFramework']->query("SELECT lead_id FROM lead_info WHERE (lead_manager_owner in ('$children') OR lead_rep_owner in ('$children'))
                                                                 and lead_id='".$array[0]->lead_cust_id."'");
            $leadclosebit=0;
            $leadclosereason='No Authority to close the lead';
			if($query_leadclosebit->num_rows()>0) {
                $leadclosebit=1;
                $leadclosereason='';
			}
			foreach (get_object_vars($array[0]) as $key => $object) {
				$viewData[$key] = $object;
			}
            $viewData['leadclosebit']=$leadclosebit;
            $viewData['leadclosereason']=$leadclosereason;
			// fetch opportunity contacts
			$data = array();
			$data['opportunity_contact'] = $viewData['opportunity_contact'];
			$contacts = $this->fetch_extraDetails($data);
			$contact_array = array();
			foreach ($contacts as $c) {
				array_push($contact_array, array('contact_id' => $c->contact_id, 'contact_name' => $c->contact_name));
			}
			$viewData['contacts'] = $contact_array;


			// fetch opportunity current stage attributes
			$stage_id = $viewData['opportunity_stage'];
            $docquery=$GLOBALS['$dbFramework']->query("select * from opportunity_document_mapping where opportunity_id='".$opp_id."' and stage_id='".$stage_id."'");
            $docrowcnt = $docquery->result();
			$viewData['docrowcnt'] = $docrowcnt;
			$cycle_id = $viewData['cycle_id'];
			$stageAttributes = $this->fetch_stageAttributes($cycle_id, $stage_id);
			$viewData['stage_attr'] = $stageAttributes;

			// fetch opportunity next stage attributes
			$nextStageAttr = $GLOBALS['$dbFramework']->query("
				SELECT
				    ss.stage_id AS next_stage_id,
				    ss.stage_name AS next_stage_name,
				    ss.stage_sequence AS next_seq_no,
				    ssa.attribute_name AS next_attribute_name,
				    ssa.attribute_value AS next_attribute_value
				FROM stage_cycle_mapping scm,
					sales_stage ss LEFT JOIN sales_stage_attributes ssa ON ssa.stage_id = ss.stage_id
				WHERE scm.cycle_id = '$cycle_id'
					AND scm.stage_id = ss.stage_id
					AND ss.stage_sequence = (
						SELECT min(ss1.stage_sequence)
						FROM sales_stage ss1, stage_cycle_mapping scm1
						WHERE scm1.cycle_id = '$cycle_id'
							AND scm1.stage_id = ss1.stage_id
							AND ss1.stage_sequence > (select ss2.stage_sequence from sales_stage ss2 where ss2.stage_id = '$stage_id')
						)
					AND ssa.`attribute_remarks` IS NULL
				ORDER BY ss.stage_sequence");
			$nextStageAttributes = $nextStageAttr->result();
			$viewData['next_stage_attr'] = $nextStageAttributes;

			// fetch opportunity products
			$oppoProductsQuery = $GLOBALS['$dbFramework']->query("
				SELECT opm.opp_prod_id as opp_prod_id, opm.opportunity_id as opportunity_id,
				opm.product_id as product_id, h.hvalue2 as product_name,
				IF(opm.quantity IS NOT NULL, opm.quantity, '') as quantity,
				IF(opm.amount IS NOT NULL, opm.amount, '') as amount,
				opm.timestamp as timestamp, opm.remarks as remarks
				FROM oppo_product_map as opm, hierarchy h
				WHERE opm.opportunity_id='$opp_id' and h.hkey2=opm.product_id");
			$oppoProducts = $oppoProductsQuery->result();
			$viewData['oppoProducts'] = $oppoProducts;



			return $viewData;
		} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
		}
	}
//-----Fetch audit trail for oppotunity products----//
	public function fetch_oppo_product_trail($opportunity_id) {
		try {
			// take opportunity cycle_id,
			// visit sales_cycle_parameters for it & fetch all products intersecting with opportunity owner products
			$query = $GLOBALS['$dbFramework']->query("
				SELECT
				    opl.mapping_id AS mapping_id,
				    opl.quantity as quantity,
				    opl.amount as amount,
				    opl.timestamp AS timestamp,
				    oum.remarks as remarks,
				    h.hvalue2 as product_name,
				    ss.stage_name as stage_name,
				    ud.user_name as user_name
				FROM
				    oppo_prd_log opl,
				    oppo_user_map oum,
				    sales_stage ss,
				    user_details ud,
				    hierarchy h
				WHERE
				    opl.opportunity_id = '$opportunity_id'
				    AND opl.mapping_id = oum.mapping_id
				    AND opl.stage_id = ss.stage_id
				    AND opl.user_id = ud.user_id
				    AND h.hkey2 = opl.product_id");
			return $query->result();
		} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
		}
	}
//------fetch multiple products associated with opportunities----//
	public function fetch_oppo_products($user_id, $opportunity_id) {
		try {
			// take opportunity cycle_id,
			// visit sales_cycle_parameters for it & fetch all products intersecting with opportunity owner products
			$query = $GLOBALS['$dbFramework']->query("
				SELECT scp.parameter_product_node as product_id, h.hvalue2 as product_name
				FROM sales_cycle_parameters as scp, opportunity_details as od, user_mappings as um, hierarchy as h
				WHERE od.opportunity_id='$opportunity_id' and scp.cycle_id=od.cycle_id
				and um.user_id=od.owner_id and um.map_type='product' and um.map_id=scp.parameter_product_node
				and h.hkey2=scp.parameter_product_node AND scp.parameter_product_node NOT IN
				(SELECT product_id FROM oppo_product_map WHERE opportunity_id='$opportunity_id')
				GROUP BY scp.parameter_product_node
				ORDER BY h.hvalue2");
			return $query->result();
		} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
		}
	}
//------Delete all products for the opportunity----//
	public function delete_oppo_products($opp_id) {
		try {
			// take opportunity cycle_id,
			// visit sales_cycle_parameters for that cycle and fetch all products intersecting with user products
			$query = $GLOBALS['$dbFramework']->query("
				DELETE FROM oppo_product_map WHERE opportunity_id='$opp_id'");
		} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
		}
	}
//-----Fetch entire history for each stage of an oppotunity----//
	public function fetch_stage_history($opp_id){
		try {

               /*	SELECT oum.mapping_id AS mapping_id, oum.stage_id as stage_id, ss.stage_name,
					oum.to_user_id as user_id, ud.user_name as user_name,
					oal.opp_numbers as opp_numbers, oal.opp_value as opp_value, oal.opp_close_date as opp_close_date,
					oal.oppo_rate as opp_rate, oal.oppo_score as opp_score, oal.oppo_customer_code as opp_customer_code,oal.oppo_priority as opp_priority,
					(CASE WHEN (od.stage_owner_id IN ('$children') OR od.stage_manager_owner_id IN ('$children'))
					THEN 1
					ELSE 0
					END) AS canReassign,
					oum.action as action, oum.remarks as remarks, oum.timestamp as timestamp
				FROM sales_stage as ss, user_details ud, opportunity_details od,
				oppo_user_map as oum LEFT JOIN oppo_attr_log as oal on oum.mapping_id=oal.mapping_id
				WHERE oum.opportunity_id = '$opp_id'
				AND od.opportunity_id=oum.opportunity_id
				AND (oum.action IN ('stage progressed','rejected', 'closed won', 'temporary loss', 'permanent loss'))
				AND ss.stage_id=oum.stage_id
				AND ud.user_id=oum.to_user_id
				GROUP BY oum.mapping_id
				ORDER BY oum.timestamp   */
        /************* oal.opp_numbers as opp_numbers converted to null b'coz the column doesnot store the quantity ,it just stores the stage manager id***/
			$user=$this->session->userdata('uid');
			$children = $user."','";
			$children .= $this->getChildrenForParent($user);
			$query = $GLOBALS['$dbFramework']->query("
				SELECT oum.mapping_id AS mapping_id, oum.stage_id as stage_id, ss.stage_name,
					oum.to_user_id as user_id, ud.user_name as user_name,
					null as opp_numbers, oal.opp_value as opp_value, oal.opp_close_date as opp_close_date,
					oal.oppo_rate as opp_rate, oal.oppo_score as opp_score, oal.oppo_customer_code as opp_customer_code,oal.oppo_priority as opp_priority,
					(CASE WHEN (od.stage_owner_id IN ('$children') OR od.stage_manager_owner_id IN ('$children'))
					THEN 1
					ELSE 0
					END) AS canReassign,
					oum.action as action, oum.remarks as remarks, oum.timestamp as timestamp
				FROM sales_stage as ss, user_details ud, opportunity_details od,
				oppo_user_map as oum LEFT JOIN oppo_attr_log as oal on oum.mapping_id=oal.mapping_id
				WHERE oum.opportunity_id = '$opp_id'
				AND od.opportunity_id=oum.opportunity_id
				AND (oum.action IN ('stage progressed','rejected', 'closed won', 'temporary loss', 'permanent loss'))
				AND ss.stage_id=oum.stage_id
				AND ud.user_id=oum.to_user_id
				GROUP BY oum.mapping_id
				ORDER BY oum.timestamp");
			return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//-----Fetch all document associted with opportunity----//
	public function fetch_documents_opp($opp_id){
		try {
		$query = $GLOBALS['$dbFramework']->query("
			SELECT odm.opportunity_document_mapping_id AS mapping_id,
				odm.created_date as timestamp,
			    odm.document_path AS path,
			    ud.user_name AS doc_user_id,
			    odm.stage_id AS stage_id,
			    ss.stage_name AS stage_name,
			    odm.created_date as created_date
			FROM opportunity_document_mapping odm, user_details ud, sales_stage ss
			WHERE odm.opportunity_id = '$opp_id' AND ud.user_id=odm.user_id AND ss.stage_id=odm.stage_id
			ORDER BY odm.id, ss.stage_name");
		return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//-----Fetch answer qualifiers for the given opportunity-----//
	public function fetch_qualifier_attempts($opportunity_id)	{
		try{
			$query = $GLOBALS['$dbFramework']->query("
				SELECT
				    oum.mapping_id as mapping_id,
				    oum.stage_id as stage_id,
				    (CASE oum.action WHEN 'passed qualifier' THEN 1 else 0 END) AS status,
				    lq.lead_qualifier_name AS qualifier_name,lq.lead_qualifier_id AS qualifier_id,
				    oum.from_user_id AS user_id,
				    ud.user_name as user_name,
				    oum.timestamp as timestamp
				FROM
				    oppo_user_map oum,
				    qualifier_tran_details qtd,
				    qualifier_questions qq,
				    lead_qualifier lq,
				    user_details ud
				WHERE
				    oum.opportunity_id = '$opportunity_id'
				        AND oum.action IN ('passed qualifier' , 'failed qualifier')
				        AND oum.mapping_id = qtd.qualifier_tran_id
				        AND qtd.question_id = qq.question_id
				        AND qq.lead_qualifier_id = lq.lead_qualifier_id
				        AND ud.user_id=oum.from_user_id
				GROUP BY oum.mapping_id");
			return $query->result();
		} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
		}
	}
//-------Fetch internal activities/History for given opportunity-----//
	public function fetch_oppo_history($opportunity_id) {
		try {
			$opportunity_query = $GLOBALS['$dbFramework']->query("
				SELECT oum.mapping_id as mapping_id,
				fud.user_name as from_user_name,
				tud.user_name as to_user_name,
				ss.stage_name as stage_name, oum.action as action, oum.module as module,
				oum.timestamp as timestamp, oum.remarks as remarks
				FROM oppo_user_map oum, user_details fud, user_details tud, sales_stage ss
				WHERE oum.opportunity_id = '$opportunity_id' AND oum.from_user_id=fud.user_id AND oum.to_user_id=tud.user_id
				AND oum.stage_id=ss.stage_id
				GROUP BY oum.id
				ORDER BY timestamp asc, oum.id asc");
			return $opportunity_query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------fetch Complete lead details for given lead id-----//
	public function fetch_lead($lead_id) {
		try {
			$query = $GLOBALS['$dbFramework']->query("
			SELECT distinct a.lead_industry,a.lead_business_loc,a.lead_id,a.lead_location_coord,a.lead_rep_status,a.lead_website,a.lead_name,a.lead_remarks,a.lead_zip,
	        a.lead_address,a.lead_logo,a.lead_country,a.lead_source,a.lead_city, a.lead_state,
	        b.contact_desg,b.contact_type,b.contact_name,b.contact_id,
	        JSON_UNQUOTE(a.lead_number->'$.leadphone[0]') as leadphone, JSON_UNQUOTE(a.lead_email->'$.leademail[0]') as leademail,
	        JSON_UNQUOTE(b.contact_number->'$.mobile[0]') as employeephone1, JSON_UNQUOTE(b.contact_number->'$.mobile[1]') as employeephone2,
	        JSON_UNQUOTE(b.contact_email->'$.email[0]') as employeeemail, JSON_UNQUOTE(b.contact_email->'$.email[1]') as employeeemail2,
	        (SELECT c.lookup_value FROM lookup c WHERE c.lookup_id = b.contact_type )as contact ,
	        (SELECT c.lookup_value FROM lookup c WHERE a.lead_country = c.lookup_id ) as country,
	        (SELECT c.lookup_value FROM lookup c WHERE a.lead_state = c.lookup_id ) as state,
	        (SELECT d.hvalue2 FROM hierarchy d WHERE a.lead_source = d.hkey2 ) as leadsurce,
	        (SELECT d.hvalue2 FROM hierarchy d WHERE a.lead_industry = d.hkey2 ) as industry,
	        (SELECT d.hvalue2 FROM hierarchy d WHERE a.lead_business_loc = d.hkey2 ) as location,
	        (SELECT count(*) FROM opportunity_details e WHERE a.lead_id = e.lead_cust_id ) as opportunity
	        from lead_info a,contact_details b
	        where a.lead_id='$lead_id' AND a.lead_id = b.lead_cust_id");
	        return $query->result();
		} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
		}
	}
//---Fetch all completed activities for given opportunity, Stages(optional)---//
	public function fetch_opp_log($opportunity_id, $stage_id='')	{
		try{
			$queryString = "SELECT rl.log_name as log_name,
				    ud.user_name as user,
				    cd.contact_name as contact_name,
				    lo.lookup_value as activity,
				    rl.starttime as start_time,
				    rl.endtime as end_time,
					rl.rating as rating,
				    rl.note as remarks,
				    rl.time as timestamp,coalesce(rl.path,'') as path
				   -- (case when rl.path=\"'no_path'\" then '' else rl.path end) as path
				FROM
				    user_details ud, contact_details cd, lookup lo, rep_log rl
				WHERE
				    rl.leadid = '$opportunity_id' and ";
			if ($stage_id != '') {
				$queryString .= "rl.stage_id = '$stage_id' and";
			}
			$queryString .= "
				    rl.rep_id=ud.user_id and
				    rl.leademployeeid=cd.contact_id and
				    rl.logtype=lo.lookup_id
				GROUP BY rl.id
				ORDER BY starttime";
			$query=$GLOBALS['$dbFramework']->query($queryString);
			return $query->result();
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
       }
	}
//----Fetch all scheduled activities for given opportunities----//
	public function fetch_opp_task($opportunity_id)	{
		try{
			$query=$GLOBALS['$dbFramework']->query("
				SELECT lr.event_name as event_name,
				    ud.user_name as user,
				    cd.contact_name as contact_name,
				    lo.lookup_value as activity,
				    lr.meeting_start as start_time,
				    lr.meeting_end as end_time,
				    lr.remarks as remarks,
				    lr.timestamp as timestamp
				FROM
				    user_details ud, contact_details cd, lookup lo, lead_reminder lr
				WHERE
				    lr.lead_id = '$opportunity_id' and
				    lr.status IN ('scheduled', 'pending') and
				    lr.rep_id=ud.user_id and
				    lr.leadempid=cd.contact_id and
				    lr.conntype=lo.lookup_id
				GROUP BY lr.lead_reminder_id
				ORDER BY lr.meeting_start");
			return $query->result();
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
       }
	}
//-----Fetch audit trail of attributes for given opportunities----//
	public function fetch_attr_log($opportunity_id) {
		try {
			$query=$GLOBALS['$dbFramework']->query("
				SELECT
				    ss.stage_name as stage_name,
				    ud.user_name as user,
					IF((oal.opp_value IS NULL) or (oal.opp_value=''),'-',oal.opp_value) as amount,
					IF((oal.opp_numbers IS NULL) or (oal.opp_numbers=''),'-',(SELECT user_name FROM user_details WHERE oal.opp_numbers=user_id)) as quantity,
					IF((oal.opp_close_date IS NULL) or (oal.opp_close_date=''),'-',oal.opp_close_date) as close_date,
					IF((oal.oppo_rate IS NULL) or (oal.oppo_rate=''),'-',oal.oppo_rate) as oppo_rate,
					IF((oal.oppo_score IS NULL) or (oal.oppo_score=''),'-',oal.oppo_score) as oppo_score,
					IF((oal.oppo_customer_code IS NULL) or (oal.oppo_customer_code=''),'-',oal.oppo_customer_code) as oppo_customer_code,
					IF((oal.oppo_priority IS NULL) or (oal.oppo_priority=''),'-',oal.oppo_priority) as oppo_priority,
				    oal.timestamp as timestamp,
				    oal.remarks as remarks
				FROM
				    oppo_attr_log oal,
				    sales_stage ss,
				    user_details ud
				WHERE oal.opportunity_id='$opportunity_id'
				    AND ss.stage_id = oal.stage_id
				    AND oal.user_id = ud.user_id
				ORDER BY oal.id");
			return $query->result();
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}

	#--------------------------------------------------------------------------------#
//-----Given the Stage id check if qualifier exists----//
	public function check_qualifiers($stageid){
		try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT ssa.*
	            FROM sales_stage ss, stage_cycle_mapping scm, sales_stage_attributes ssa
	            WHERE scm.cycle_id = (select cycle_id from stage_cycle_mapping where stage_id='$stageid')
	            AND scm.stage_id = ss.stage_id
	            AND ss.stage_sequence=(select stage_sequence from sales_stage where stage_id='$stageid')
	            AND ssa.stage_id = ss.stage_id
	            AND ssa.attribute_name = 'qualifier'
	            GROUP BY ss.stage_id
	            ORDER BY ss.stage_sequence
	            LIMIT 1");
			$count = $query->num_rows();
			if($count > 0){
				foreach ($query->result() as $row)	{
					$qualifier_id= $row->attribute_value;
				}
			} else {
				return false;
			}
	        $query = $GLOBALS['$dbFramework']->query("
	        	SELECT * FROM lead_qualifier
	        	WHERE lead_qualifier_id='$qualifier_id'
	        	ORDER BY id;");
	        $arr=$query->result_array();
	        $a=array();
	        for($i=0;$i<count($arr);$i++)	{
	            $lead_qualifier_id=$arr[$i]['lead_qualifier_id'];
	            $a[$i]['lead_qualifier_name']=$arr[$i]['lead_qualifier_name'];
	            $a[$i]['lead_qualifier_id']=$arr[$i]['lead_qualifier_id'];
	            $query1=$this->db->query("
	            	SELECT *
	            	FROM qualifier_questions
	            	WHERE lead_qualifier_id='$lead_qualifier_id'
	            	AND que_delete_bit=1
	            	ORDER BY row_order");
	            $arr1=$query1->result_array();
	            for($j=0;$j<count($arr1);$j++)	{
	                $question_id=$arr1[$j]['question_id'];
	                $a[$i]['question_data'][$j]['question_type']=$arr1[$j]['question_type'];
	                $a[$i]['question_data'][$j]['question_id']=$arr1[$j]['question_id'];
	                $a[$i]['question_data'][$j]['question_text']=$arr1[$j]['question_text'];
	                $a[$i]['question_data'][$j]['answer']=$arr1[$j]['answer'];
	                $a[$i]['question_data'][$j]['mandatory_bit']=$arr1[$j]['mandatory_bit'];

	                $query11=$this->db->query("
	                	SELECT *
	                	FROM qualifier_answers
	                	WHERE question_id='$question_id'
	                	ORDER BY id");
	                $arr11=$query11->result_array();
	                for($j1=0;$j1<count($arr11);$j1++){
	                    $a[$i]['question_data'][$j]['answer_data'][$j1]['answer_id']=$arr11[$j1]['answer_id'];
	                    $a[$i]['question_data'][$j]['answer_data'][$j1]['answer_text']=$arr11[$j1]['answer_text'];
	                }
	            }
	        }
	        return $a;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function check_qualifiers1($qualifier_id,$quamapid){
		try {

	        $query = $GLOBALS['$dbFramework']->query("
	        	SELECT * FROM lead_qualifier
	        	WHERE lead_qualifier_id='$qualifier_id'
	        	ORDER BY id;");
	        $arr=$query->result_array();
	        $a=array();
	        for($i=0;$i<count($arr);$i++)	{
	            $lead_qualifier_id=$arr[$i]['lead_qualifier_id'];
	            $a[$i]['lead_qualifier_name']=$arr[$i]['lead_qualifier_name'];
	            $a[$i]['lead_qualifier_id']=$arr[$i]['lead_qualifier_id'];
	            $query1=$this->db->query("
	            	SELECT *
	            	FROM qualifier_questions
	            	WHERE lead_qualifier_id='$lead_qualifier_id'
	            	AND que_delete_bit=1
	            	ORDER BY row_order");
	            $arr1=$query1->result_array();
	            for($j=0;$j<count($arr1);$j++)	{
	                $question_id=$arr1[$j]['question_id'];

                    $quatransquery=$GLOBALS['$dbFramework']->query("select * from qualifier_tran_details WHERE qualifier_tran_id='$quamapid'
                                            and question_id='$question_id'");
                    $qcount = $quatransquery->num_rows();
        			if($qcount > 0){
        				foreach ($quatransquery->result() as $row)	{
        					$quatrans_ansid= $row->answer_id;
                            if($quatrans_ansid == null){
                                $quatrans_ansid= $row->rt_answer;
                            }
        				}
        			}

	                $a[$i]['question_data'][$j]['question_type']=$arr1[$j]['question_type'];
	                $a[$i]['question_data'][$j]['question_id']=$arr1[$j]['question_id'];
	                $a[$i]['question_data'][$j]['question_text']=$arr1[$j]['question_text'];
	                $a[$i]['question_data'][$j]['answer']=$arr1[$j]['answer'];
	                $a[$i]['question_data'][$j]['mandatory_bit']=$arr1[$j]['mandatory_bit'];
	                $a[$i]['question_data'][$j]['transans']=$quatrans_ansid;

	                $query11=$this->db->query("
	                	SELECT *
	                	FROM qualifier_answers
	                	WHERE question_id='$question_id'
	                	ORDER BY id");
	                $arr11=$query11->result_array();
	                for($j1=0;$j1<count($arr11);$j1++){
	                    $a[$i]['question_data'][$j]['answer_data'][$j1]['answer_id']=$arr11[$j1]['answer_id'];
	                    $a[$i]['question_data'][$j]['answer_data'][$j1]['answer_text']=$arr11[$j1]['answer_text'];
	                }
	            }
	        }
	        return $a;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

//----Check if opportunity has passed qualifiers-----//
    public function check_qualifier_passed($opportunity_id = '')	{
    	try {
	    	$query = $GLOBALS['$dbFramework']->query("
	    		SELECT id
	    		FROM oppo_user_map
	    		WHERE opportunity_id='$opportunity_id' AND action='passed qualifier'");
	    	$result = $query->result();
	    	if (count($result) == 0) {
	    		return false;
	    	} else {
	    		return true;
	    	}
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
//-----Check if an opportunity exists with given parameter----//
    public function validate_oppo_params($data)	{
    	try {
			$lead_cust_id = $data['lead_cust_id'];
			$product_id = $data['product_id'];
			$currency_id = $data['currency_id'];
			$industry_id = $data['industry_id'];
			$location_id = $data['location_id'];
			$sell_type = $data['sell_type'];

	    	$query = $GLOBALS['$dbFramework']->query("
	    		SELECT id
	    		FROM opportunity_details
	    		WHERE (closed_reason IS NULL) AND (lead_cust_id='$lead_cust_id') AND (opportunity_product='$product_id')
	    		AND (sell_type='$sell_type') AND (opportunity_industry='$industry_id') AND (opportunity_location='$location_id')
	    		AND (opportunity_currency='$currency_id')");
	    	$result = $query->result();
	    	return count($result);
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
//-----Check if opportunity name is valid----//
    public function isValidOppName($data) {
    	try {
	    	$name = $data['opportunity_name'];
	    	$name = strtolower($name);
	    	$query = $GLOBALS['$dbFramework']->query("
	    		SELECT * FROM opportunity_details WHERE opportunity_name='$name'");
	    	if ($query->num_rows() == 0) {
	    		return 1; //its a valid name so return 1
	    	}
	    	return 0;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
//-----Check if given user can update/access opportunity----//
	public function canUpdate($user, $opp_id) {
		try {
			$children = $user."','";
			$children .= $this->getChildrenForParent($user);
			$query = $GLOBALS['$dbFramework']->query("
				SELECT od.*
				FROM opportunity_details od, oppo_user_map oum1, oppo_user_map oum2
				WHERE od.opportunity_id = '$opp_id'
				        AND (owner_id IN ('$children')
				        OR manager_owner_id IN ('$children')
				        OR stage_owner_id IN ('$children')
				        OR stage_manager_owner_id IN ('$children')
				        OR (od.opportunity_id = oum1.opportunity_id
				        AND oum1.state = 1
				        AND oum1.to_user_id IN ('$children')
				        AND oum1.action IN ('ownership assigned' , 'stage assigned',
				        'ownership reassigned',
				        'stage reassigned'))
				        OR (od.opportunity_id = oum2.opportunity_id
				        AND oum2.to_user_id IN ('$children')
				        AND oum2.action IN ('stage progressed')))
				GROUP BY od.opportunity_id");
			$arr = $query->result();
			if (count($arr) > 0) {
				return 1;
			} else {
				return 0;
			}
		} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------Check if given lead id is already been closed-----//
	public function lead_closed_status($lead_cust_id) {
		try {
			$query = $GLOBALS['$dbFramework']->query("
			SELECT lead_status
			FROM lead_info
			WHERE lead_id='$lead_cust_id'");
			$array = $query->result();
			return $array[0]->lead_status;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------Check if there is any active/open opportunities for given lead----//
	public function active_oppos($opp_id, $lead_cust_id) {
		try {
			$prod = $GLOBALS['$dbFramework']->query("
				SELECT opportunity_id
				FROM opportunity_details
				WHERE lead_cust_id='$lead_cust_id' AND closed_reason IS NULL");
			if ($prod->num_rows() > 0) {
				return true;
			}
			return false;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}

	#--------------------------------------------------------------------------------#
//------Function to insert into 'opportunity_details' table----//
	public function add_opportunityBasic($data){
		try {
			$query = $GLOBALS['$dbFramework']->insert('opportunity_details',$data);
			return $query;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//----Function to update into 'opportunity_user_map' table ( changing state of opportunity to 0 present in oppusermap which are permanent lost or temporary lost )
// so that at a given time only 1 state can be 1 for a given opportunity
	public function changestateopp($oppid){
		// inserts in to the opportunity transaction table - oppo user map
		try {
			$query= $GLOBALS['$dbFramework']->query("Update oppo_user_map set state=0 where opportunity_id='".$oppid."' and action IN('permanent loss','temporary loss')");
			return $query;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//----Function to insert into 'opportunity_user_map' table
	public function map_opportunity($data){
		// inserts in to the opportunity transaction table - oppo user map
		try {
			$query= $GLOBALS['$dbFramework']->insert_batch('oppo_user_map', $data);
			return $query;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//----Function to insert into 'oppo_product_map' table
	public function map_opp_products($data){
		// inserts in to the opportunity transaction table - oppo product map
		try {
			$query= $GLOBALS['$dbFramework']->insert_batch('oppo_product_map', $data);
			return $query;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//-------Function to insert into 'oppo_prd_log' table
	public function map_opp_product_log($data){
		// inserts in to the opportunity transaction table - oppo product map
		try {
			$query= $GLOBALS['$dbFramework']->insert_batch('oppo_prd_log', $data);
			return $query;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//-----Function to insert into 'oppo_attr_log' table
	public function log_attr($data){
		try {
			$query= $GLOBALS['$dbFramework']->insert('oppo_attr_log', $data);
			return $query;
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}
//------Function to insert into 'opportunity_document_mapping' table
	public function batch_doc_upload($docsData){
		try {
			$query= $GLOBALS['$dbFramework']->insert_batch('opportunity_document_mapping', $docsData);
			return $query;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//-----Update 'opportunity_details' table for given id----//
	public function updateOpportunity($updateData, $opp_id){
		try {
			$var = $GLOBALS['$dbFramework']->update('opportunity_details', $updateData, array('opportunity_id' => $opp_id));
			return $var;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
//------Check if lead owner and opportunity owner are same----//
	public function check_lead_owner_opp_owner($opportunity_id, $lead_cust_id) {
		try {
			$query = $GLOBALS['$dbFramework']->query("
			SELECT od.opportunity_id
			FROM opportunity_details od, lead_info li
			WHERE li.lead_id='$lead_cust_id' AND od.opportunity_id='$opportunity_id' AND li.lead_rep_owner=od.owner_id");
			$num_rows = $query->num_rows();
			if ($num_rows > 0) {
				return true; // you can convert it to customer
			}
			return false;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
// -----Updating the scheduled task to cancel on check of close all future activities ------//
//(depending on if close all lead is checked or not,if not checked then opportunity task will be canceled ,else both
// task related to opportunity and lead is canceled

public function close_activity($lead_oppoid,$type,$username) {
		try {
		   // $userid=$this->session->userdata('uid');
            $cancel_remarks="Cancelled by ".$username;
          //  echo"update lead_reminder set status='cancel',cancel_remarks='$cancel_remarks' where
          //  status in('pending','scheduled') and remi_date >=CURDATE() and lead_id='$lead_oppoid'";
            $query = $GLOBALS['$dbFramework']->query("update lead_reminder set status='cancel',cancel_remarks='$cancel_remarks' where
            status in('pending','scheduled') and remi_date >=CURDATE() and lead_id='$lead_oppoid'");
            // lead_id will store leadid if lead task else will store oppotunity id if opportunity task
		  return $query;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}

//------Perform File upload for given data----//
	public function oppo_file_upload($given_data) {
		try {
			$user_id = $this->session->userdata('uid');
			$_FILES  = $given_data['files'];
			$stage_remarks 		= $given_data['stage_remarks'];
			$opportunity_id 	= $given_data['opportunity_id'];
			$lead_cust_id 		= $given_data['lead_cust_id'];
			$stage_id 			= $given_data['stage_id'];
			$cycle_id 			= $given_data['cycle_id'];
			$sell_type 			= $given_data['sell_type'];
			$mapping_id 		= $given_data['mapping_id'];

			$dirpath = './uploads/opportunity_docs/';
			if(!is_dir($dirpath))    {
				mkdir($dirpath);
			}
			$dirpath = './uploads/opportunity_docs/'.$opportunity_id.'/';
			if(!is_dir($dirpath))    {
				mkdir($dirpath);
			}
			$dirpath = './uploads/opportunity_docs/'.$opportunity_id.'/'.$stage_id.'/';
			if(!is_dir($dirpath))    {
				mkdir($dirpath);
			}


			$upload['upload_path'] 	= $dirpath;
			$upload['allowed_types']= 'gif|jpg|jpeg|png|bmp|doc|docx|pdf|rtf|txt|xls|xlsx|csv|mp3|wav|aac|mp4|wma|wmv|mpg|jbg|fax|pptx|epub|xlsm|xltx';
			$upload['overwrite'] 	= true;
			$upload['max_size'] 	= 100000;

			$files = $_FILES;
			$count = count($_FILES['userfile']['name']);
			$finalPath = $dirpath;
			$errors = array();
			$docsData = array();
			$this->load->library('upload');
			for($i = 0; $i < $count; $i++) {
				$_FILES['userfile']['name'] 	= $files['userfile']['name'][$i];
				$_FILES['userfile']['type'] 	= $files['userfile']['type'][$i];
				$_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
				$_FILES['userfile']['error'] 	= $files['userfile']['error'][$i];
				$_FILES['userfile']['size'] 	= $files['userfile']['size'][$i];
				if ($_FILES['userfile']['error'] == 4) {
					continue;
				}
				$this->upload->initialize($upload);
				if (!$this->upload->do_upload())    {
					$error = array('error' => $this->upload->display_errors(),
									'name' => $_FILES['userfile']['name']);
					array_push($errors, $error);
				} else {
					$data = array('upload_data' => $this->upload->data());
					$file_name=$data['upload_data']['file_name'];
					$data = array(
						'opportunity_document_mapping_id' => $mapping_id,
						'opportunity_id'=> $opportunity_id,
						'document_path' => $finalPath.$file_name,
						'user_id' 		=> $user_id,
						'lead_id' 		=> $lead_cust_id,
						'stage_id' 		=> $stage_id,
						'created_date' 	=> date('Y-m-d H:i:s'),
						'remarks' 		=> $stage_remarks
					);
					array_push($docsData, $data);
				}
			}
			$file_data['mapping_id']= $mapping_id;
			$file_data['docs'] 		= $docsData;
			$file_data['errors'] 	= $errors;
			return $file_data;
		} catch (LConnectApplicationException $e) {
			echo $this->exceptionThrower($e);
		}
	}

	/*
	public function getAllRows($hierarchy_class)	{
		try {
			$allRowsQuery = $GLOBALS['$dbFramework']->query("
				SELECT h.id, h.hierarchy_id, h.hkey1, h.hvalue1, h.hkey2, h.hvalue2
				FROM hierarchy AS h, hierarchy_class AS hc
				WHERE hc.Hierarchy_Class_Name='$hierarchy_class' AND h.hierarchy_class_id=hc.Hierarchy_Class_ID
				GROUP BY h.hierarchy_id");
			//fetches all entries for a given hierarchy class
			$full_structure = $allRowsQuery->result();
			//an array of all parents referred by children
			$allParentNodes = [];
			if (version_compare(phpversion(), '7.0.0', '<')) {
				// php version isn't high enough to support array_column
				foreach($full_structure as $row)	{
					$allParentNodes[$row->hkey2] = $row->hkey1;
				}
			} else {
				$allParentNodes = array_column(
									$full_structure,
									'hkey1',  // parent column
									'hkey2'); // child column
			}
			return $allParentNodes;
		} catch (LConnectApplicationException $e) {
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
		}
	}*/

}
?>

