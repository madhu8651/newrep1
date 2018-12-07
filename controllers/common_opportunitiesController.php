<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('common_opportunitiesController');

class common_opportunitiesController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
		$this->load->model('common_opportunitiesModel','opp_common');
		$this->load->model('manager_opportunitiesModel','opp_mgr');
		$this->load->model('sales_opportunitiesModel','opp_sales');
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

	/*-=-=-=-=-=-=-=-=--=-=-=-=CREATE OPPORTUNITY PAGE-=-=-=-=-=-=-=-=-=-=-=-=-*/
	//Fetching existing contact info after checking user credentials for a given lead---//
	public function get_contacts($target) {
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$lead_id = $data->lead_id;
				$user_id = $this->session->userdata('uid');
				$finalArray = array();
				$lead_contacts = $this->opp_common->fetch_Contacts($lead_id, $target);
				$finalArray['contacts'] = $lead_contacts;
				echo json_encode($finalArray);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else  {
			redirect('indexController');
		}
	}
//-----fetching products after checking user credentials, lead cust id and sell type---//
	public function get_products($target) {
		if ($this->session->userdata('uid')) {
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);

				$lead_id = $data->lead_cust_id;
				$sell_type = $data->sell_type;
				$user_id = $this->session->userdata('uid');

				$finalArray = array();
				$target_products = $this->opp_common->fetch_Products($lead_id, $user_id, $sell_type);
				$finalArray['lead_products'] = $target_products;
				echo json_encode($finalArray);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
//----show currencies for corresponding products---//
	public function get_currencies() {
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$given_product_id = $data->product_id;
				$user_id = $this->session->userdata('uid');
				$data = array();
				$currencies = $this->opp_common->fetch_currencies($given_product_id, $user_id);
				$data['currency'] = $currencies;
				echo json_encode($data);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else  {
			redirect('indexController');
		}
	}
	//---given a stage id it will check if that stage has qualifier---//
	public function check_qualifier() {
		try {
			$json = file_get_contents("php://input");
			$data = json_decode($json);
			$stage_id = $data->stage_id;
			$qualifier_data = $this->opp_common->check_qualifiers($stage_id);
			echo json_encode($qualifier_data);
		} catch (LConnectApplicationException $e) {
			echo $this->exceptionThrower($e);
		}
	}
//----Submit qualifier answers---//
    public function post_data(){
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $stage_id=$data->stage_id;
        $rep_id=$data->rep_id;
        $lead_id=$data->lead_id;
        $opp_id=$data->opp_id;
        $lead_qualifier_id=$data->lead_qualifier_id;
        $type1_2=$data->type1_2;
        $type3=$data->type3;

        $data1=array(
            'repid'=>$rep_id,
            'leadid'=> $lead_id,
            'oppid'=> $opp_id
        );

        $insert=$this->opp_common->insert_data($lead_qualifier_id,$type1_2,$type3,$stage_id,$data1);
        echo json_encode($insert);
        //  echo $insert;
    }

	/*-=-=-=-=-=-=-=-=-=-=-=-OPP STAGE DETAIL VIEW-=-=-=-=-=-=-=-=-=-=-=-=-*/

	// fetch all required data to populate the view given the oppertunity id
	public function oppo_details()	{
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$opp_id = $data->opportunity_id;
				$user_id = $this->session->userdata('uid');
				$data = $this->opp_common->fetch_OpportunityDetails($opp_id, $user_id);
				$data['activityList'] = $this->opp_sales->get_activityList();
				$data['user_id'] = $user_id;
				$opp = $data['opportunity_id'];

				$updateStatus = $this->opp_common->canUpdate($user_id, $opp);
				$data['canUpdate'] = $updateStatus;

				// only stage owner can progress
				if ($user_id != $data['stage_owner_id']){
					$data['canProgress'] = 0;
				} else{
					$data['canProgress'] = 1;
				}
				// only manager_owner or owner can edit products.
				if (($user_id == $data['owner_id']) || ($user_id == $data['manager_owner_id'])) {
					$data['canEditProducts'] = 1;
				} else {
					$data['canEditProducts'] = 0;
				}

				// previously only owner or manager could close it...
				// now anyone can close because of recruitment domain
				// if ( ($user_id != $data['owner_id']) || ($user_id != $data['manager_owner_id']) ){
				// 	$data['canClose'] = 0;
				// } else {
					$data['canClose'] = 1;
				// }
				echo json_encode($data);
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
//---Get all the products associated with oppertunity---//
	public function get_oppo_products() {
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
			    $user_id = $data->user_id;
				$opportunity_id = $data->opportunity_id;
				$opp_products = $this->opp_common->fetch_oppo_products($user_id, $opportunity_id);
                
				echo json_encode($opp_products);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
//----Fetch Audit trail of oppertunity products---//
	public function get_oppo_product_trail() {
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$opportunity_id = $data->opportunity_id;
				$opp_products = $this->opp_common->fetch_oppo_product_trail($opportunity_id);
				echo json_encode($opp_products);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
//------it will insert products to an existing oppertunity---//
	public function post_oppo_products() {
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);

				$oppoProducts = $data->oppoProducts;
				$opportunity_id = $data->opportunity_id;
				$lead_cust_id = $data->lead_cust_id;
				$cycle_id = $data->cycle_id;
				$stage_id = $data->stage_id;
				$sell_type = $data->sell_type;
				$module = $data->module;
				$remarks = $data->remarks;
				$mapping_id = uniqid(rand());

				$user_id = $this->session->userdata('uid');
				$array_final = array();
				$audit_trail = array();

				foreach ($oppoProducts as $product) {
					array_push($array_final, array(
						'opp_prod_id' => uniqid(rand()),
						'opportunity_id' => $product->opportunity_id,
						'product_id' => $product->product_id,
						'quantity' => $product->quantity,
						'amount' => $product->amount,
						'timestamp' => date('Y-m-d H:i:s'),
						'remarks' => null
					));
					array_push($audit_trail, array(
						'mapping_id' => $mapping_id,
						'opportunity_id' => $opportunity_id,
						'stage_id' => $stage_id,
						'user_id' => $user_id,
						'product_id' => $product->product_id,
						'quantity' => $product->quantity,
						'amount' => $product->amount,
						'remarks' => null,
						'status' => null,
						'timestamp' => date('Y-m-d H:i:s'),
					));
				}
				$transaction = array(
				    'mapping_id' => $mapping_id,
				    'opportunity_id' => $opportunity_id,
				    'from_user_id' => $user_id,
				    'to_user_id' => $user_id,
				    'lead_cust_id' => $lead_cust_id,
				    'cycle_id' => $cycle_id,
				    'stage_id' =>$stage_id,
				    'module' => $module,
				    'sell_type' => $sell_type,
				    'timestamp' => date('Y-m-d H:i:s'),
				    'action' => 'updated',
				    'state' => '1',
				    'remarks' => $remarks
				);
				if (count($oppoProducts) > 0) {
					$this->opp_common->delete_oppo_products($opportunity_id);
				}
				$insert = $this->opp_common->map_opp_products($array_final);
				$insert2 = $this->opp_common->map_opportunity(array(0 => $transaction));
				$insert3 = $this->opp_common->map_opp_product_log($audit_trail);
				echo $insert;
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
//----view lead details of a givrn oppertunity---//
	public function view_lead()	{
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$lead_id = $data->lead_cust_id;
				$lead_details = $this->opp_common->fetch_lead($lead_id);
				echo json_encode($lead_details);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
//----view customer details of a given oppertunity----//
	public function view_customer()	{
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$lead_cust_id = $data->lead_cust_id;
				$lead_details = $this->opp_common->fetch_customer($lead_cust_id);
				echo json_encode($lead_details);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
//-----get all activities associated with oppertunity and stage id(optinal)----//
	public function get_opportunity_activity_log()	{
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$opportunity_id = $data->opportunity_id;
				$stage_id = '';
				if (isset($data->stage_id)) {
					$stage_id = $data->stage_id;
				}
				$lead_details = $this->opp_common->fetch_opp_log($opportunity_id, $stage_id);
				echo json_encode($lead_details);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
//-----get all scheduled activities for oppertunity---//
	public function get_opportunity_task_list()	{
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$opportunity_id = $data->opportunity_id;
				$lead_details = $this->opp_common->fetch_opp_task($opportunity_id);
				echo json_encode($lead_details);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}
//----get audit trail of opportunity attributes----//
	public function get_attr_log() {
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$opportunity_id = $data->opportunity_id;
				$attr_log = $this->opp_common->fetch_attr_log($opportunity_id);
				echo json_encode($attr_log);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}

	// stage-wise details for opportunity
	public function get_stage_history() {
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$opp_id = $data->opportunity_id;
				$stage_history = $this->opp_common->fetch_stage_history($opp_id);
				$docs = $this->opp_common->fetch_documents_opp($opp_id);
				$qualifiers = $this->opp_common->fetch_qualifier_attempts($opp_id);
				//query for all documents in opportunity_document_mapping
				//iterate through data and for a match found with stage id
				//intersperse document_mapping rows to that
				$data = $stage_history;
				for ($i=0; $i < sizeof($data); $i++) {
					$stage_history[$i]->docs = [];
					$stage_history[$i]->qualifiers = [];
					foreach ($docs as $doc) {
						//all files uploaded in a particular stage is pushed to docs array
						if (($data[$i]->stage_id == $doc->stage_id)) {
							array_push($stage_history[$i]->docs, json_decode(json_encode($doc)));
						}
					}
					foreach ($qualifiers as $qualifier) {
						//all files uploaded in a particular stage is pushed to docs array
						if (($data[$i]->stage_id == $qualifier->stage_id)) {
							array_push($stage_history[$i]->qualifiers, json_decode(json_encode($qualifier)));
						}
					}
				}
				echo json_encode($stage_history);
			} catch (LConnectApplicationException $e)  {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}

	// fetch all the documents involved in the opportunity
	public function get_opp_documents() {
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$opp_id = $data->opportunity_id;
				$docs = $this->opp_common->fetch_documents_opp($opp_id);
				echo json_encode($docs);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		} else {
			redirect('indexController');
		}
	}

	// complete opportunity history
	public function get_opportunity_remarks() {
		if($this->session->userdata('uid')){
			try {
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$opportunity_id = $data->opportunity_id;
				$data = $this->opp_common->fetch_oppo_history($opportunity_id);
				echo json_encode($data);
			} catch (LConnectApplicationException $e) {
				echo $this->exceptionThrower($e);
			}
		}else{
			redirect('indexController');
		}
	}

}