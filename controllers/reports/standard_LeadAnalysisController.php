<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/..');

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('standard_LeadAnalysisController');

class standard_LeadAnalysisController extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('reports/standard_LeadAnalysisModel','leadModel');
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


	public function generateReport(){
		if($this->session->userdata('uid')){
			try{
				$json = file_get_contents("php://input");
				$data = json_decode($json);
				$type = $data->type;
				$user_id = $this->session->userdata('uid');
				switch ($type) {
					case 'Lead_Distribution_Analysis':
						$category = $data->category;
						$startdate = date('Y-m-d',strtotime($data->startdate));
						$enddate = date('Y-m-d',strtotime($data->enddate));
						$requestData = array(
							'user_id' => $user_id,
							'category' => $category,
							'type' => $type,
							'subtype' => $data->subtype,
							'startdate' => $startdate,
							'enddate' => $enddate	
						);
						if($data->subtype == "NA"){
							$reportData = array();
							$resultset = $this->leadModel->generateReport($requestData);
							for($i=0;$i<count($resultset);$i++){
								switch ($category) {
									case 'representative':
										$reportData[$i]['lname'] = $resultset[$i]->Representative_Name;
										$reportData[$i]['lead_count'] = $resultset[$i]->Total_Count;
										$reportData[$i]['lid'] = $resultset[$i]->user_id;
										break;
									case 'location':
										$reportData[$i]['lname'] = $resultset[$i]->Business_Locations;
										$reportData[$i]['lead_count'] = $resultset[$i]->Total_Count;
										$reportData[$i]['lid'] = $resultset[$i]->location_id;
										break;
									case 'industry':
										$reportData[$i]['lname'] = $resultset[$i]->Industries_Name;
										$reportData[$i]['lead_count'] = $resultset[$i]->Total_Count;
										$reportData[$i]['lid'] = $resultset[$i]->industry_id;
										break;
									case 'product':
										$reportData[$i]['lname'] = $resultset[$i]->Products_Name;
										$reportData[$i]['lead_count'] = $resultset[$i]->Total_Count;
										$reportData[$i]['lid'] = $resultset[$i]->product_id;
										break;
								}
								$reportData[$i]['category_name'] = ucfirst($category); 
							}
							echo json_encode($reportData);
						}else if($data->subtype == "details"){
							$lid = $data->lid;
							$requestData['lid'] = $lid;
							$resultset = $this->leadModel->generateReport($requestData);
							echo json_encode($resultset);
						}
						break;

					case 'Lead_FAT_Analysis':
						$user_type = $data->user_type;
						$userid = $data->user_id;
						$startdate = date('Y-m-d',strtotime($data->startdate));
						$enddate = date('Y-m-d',strtotime($data->enddate));
						if($userid=="All"){
							$userid='';
						}
						$requestData = array(
							'user_id' => $user_id,
							'user_type' => $user_type,
							'rep_id' => $userid,
							'type' => $type,
							'startdate' => $startdate,
							'enddate' => $enddate
						);
						$resultset = $this->leadModel->generateReport($requestData);
						echo json_encode($resultset);
						break;

					case 'Lead_Lifecycle_Analysis':
						$requestData = array(
							'type' => $type,
							'user_id' => $user_id,
							'lead_id' => $data->lead_id,
							'sub_lead_id' => $data->sub_lead_id
						);
						$resultset = $this->leadModel->generateReport($requestData);
						echo json_encode($resultset);
						break;

					case 'Lead_Status_Analysis':
						$startdate = date('Y-m-d',strtotime($data->startdate));
						$enddate = date('Y-m-d',strtotime($data->enddate));
						$requestData = array(
							'user_id' => $user_id,
							'type' => $type,
							'subtype' => $data->subtype,
							'startdate' => $startdate,
							'enddate' => $enddate	
						);
						if($data->subtype == "NA"){
							$reportData = array();
							$resultset = $this->leadModel->generateReport($requestData);
							$reportData[0]['Lead_Status'] = $resultset[0]->Astatus;
							$reportData[0]['Count'] = $resultset[0]->ActiveCount;
							$reportData[0]['lid'] = str_replace(' ', '_', $resultset[0]->Astatus); 
							$reportData[1]['Lead_Status'] = $resultset[0]->Istatus;
							$reportData[1]['Count'] = $resultset[0]->InactiveCount;
							$reportData[1]['lid'] = str_replace(' ', '_', $resultset[0]->Istatus);
							$reportData[2]['Lead_Status'] = $resultset[0]->Sstatus;
							$reportData[2]['Count'] = $resultset[0]->StaleCount;
							$reportData[2]['lid'] = str_replace(' ', '_', $resultset[0]->Sstatus);
							$reportData[3]['Lead_Status'] = $resultset[0]->Dstatus;
							$reportData[3]['Count'] = $resultset[0]->DudCount;
							$reportData[3]['lid'] = str_replace(' ', '_', $resultset[0]->Dstatus);
							echo json_encode($reportData);
						}else if($data->subtype == "details"){
							$lid = $data->lid;
							$requestData['lid'] = $lid;
							$resultset = $this->leadModel->generateReport($requestData);
							echo json_encode($resultset);
						}
						break;
					case 'Lead_Time_Analysis':
						$lead_id = $data->lead_id;
						$sub_lead_id = $data->sub_lead_id;
						$startdate = date('Y-m-d',strtotime($data->startdate));
						$enddate = date('Y-m-d',strtotime($data->enddate));
						$requestData = array(
							'user_id' => $user_id,
							'type' => $type,
							'subtype' => $data->subtype,
							'lead_id' => $lead_id,
							'sub_lead_id' => $sub_lead_id,
							'startdate' => $startdate,
							'enddate' => $enddate
						);
						if($data->subtype=="NA"){
							$reportData = array();
							$resultset = $this->leadModel->generateReport($requestData);
							for($i=0;$i<count($resultset);$i++){
								$reportData[$i]['lname'] = $resultset[$i]->lname;
								$reportData[$i]['lead_count'] = $resultset[$i]->Time_duration;
								$reportData[$i]['lid'] = $resultset[$i]->lid;
							}
							echo json_encode($reportData);
						}else{
							$lid = $data->lid;
							$requestData['lid'] = $lid;
							$resultset = $this->leadModel->generateReport($requestData);
							echo json_encode($resultset);
						}
						break;
                    case 'Split_Cost_Analysis':
                    case 'Activity_Cost_Distribution':
                    case 'Lead_Spend_Analysis' :
                    case 'Lead_Resource_Cost_Split' :
                    case 'Lead_Activity_Cost_Split' :
                    case 'Lead_Average_Activity_Cost' :
                                $startdate=$data->startdate;
                                $enddate=$data->enddate;
                                if($data->startdate!='NA' && $data->enddate!='NA')
                                {
                                   $startdate = date('Y-m-d',strtotime($data->startdate));
        						   $enddate = date('Y-m-d',strtotime($data->enddate));
                                }

        						$requestData = array(
                                    'user_id' => $user_id,
        							'lead_id' => $data->lead_id,
                                    'type' => $type,
        							'subtype' => $data->subtype,
        							'startdate' => $startdate,
        							'enddate' => $enddate
        						);

                                if($data->subtype=='details' && $type=='Split_Cost_Analysis')
                                        $requestData['Cost_type']=$data->Cost_type;
                                if($data->subtype=='details' && $type=='Activity_Cost_Distribution')
                                        $requestData['lid']=$data->lid;
                                if($data->subtype=='details' && $type=='Lead_Spend_Analysis')
                                {
                                   $requestData['activitydate']=$data->activitydate;
                                   if($data->activitydate!='All')
                                    $requestData['activitydate'] = date('Y-m-d',strtotime($requestData['activitydate']));
                                }
                                if($data->subtype=='details' && $type=='Lead_Resource_Cost_Split')
                                   $requestData['resourceid']=$data->resourceid;
                                if($data->subtype=='details' && $type=='Lead_Activity_Cost_Split')
                                   $requestData['activityid']=$data->activityid;
                                if($data->subtype=='details' && $type=='Lead_Average_Activity_Cost')
                                   $requestData['activityid']=$data->activityid;

        						$resultset = $this->leadModel->generateReport($requestData);
        						echo json_encode($resultset);
        						break;
                      case 'Lead_Score_Analysis':
                        $selectionid=$data->selectionid;
                        $selectiontype=$data->selectiontype;

					    $startdate=$data->startdate;
                        $enddate=$data->enddate;
                        if($data->startdate!='NA' && $data->enddate!='NA')
                        {
                           $startdate = date('Y-m-d',strtotime($data->startdate));
						   $enddate = date('Y-m-d',strtotime($data->enddate));
                        }
						$requestData = array(
							'user_id' => $user_id,
							'type' => $type,
							'subtype' => $data->subtype,
							'selectiontype' => $selectiontype,
							'selectionid' => $selectionid,
							'startdate' => $startdate,
							'enddate' => $enddate
						);
                        if(array_key_exists('id',$data))
                                $requestData['lead_id'] = $data->id;
                        $resultset = $this->leadModel->generateReport($requestData);
        				echo json_encode($resultset);
						break;
				}
			}catch(LConnectApplicationException $e){
				$this->exception($e);
			}
		}else{
			redirect('indexController');
		}
	}

	public function save_report(){
		if($this->session->userdata('uid')){
			try{
				$json = file_get_contents("php://input");
                $data = json_decode($json);
                $id=$data->id;
                $type = $data->type;
                $uid = $this->session->userdata('uid');

                $jsonfilter=array();
                switch ($type) {
                	case 'Lead_Distribution_Analysis':
                			$jsonfilter = array(
                				"category" => $data->category,
                				"startdate" => $data->startdate,
                				"enddate" => $data->enddate
                			);
                		if($data->subtype=="details"){
                			$jsonfilter['lid'] = $data->lid;
                		}
                		break;

                	case 'Lead_FAT_Analysis':
                		$jsonfilter = array(
                			"user_type" => $data->user_type,
                			"startdate" => $data->startdate,
                			"enddate" => $data->enddate,
                			"user_id" => $data->user_id
                		);
                		break;

                	case 'Lead_Lifecycle_Analysis':
                		$jsonfilter = array(
                			'lead_id' => $data->lead_id,
                			'sub_lead_id' => $data->sub_lead_id
                		);
                		break;

                	case 'Lead_Status_Analysis':
                		$jsonfilter = array(
            				"startdate" => $data->startdate,
            				"enddate" => $data->enddate
                		);
                		if($data->subtype=="details"){
                			$jsonfilter['lid'] = $data->lid;
                		}
                		break;

                	case 'Lead_Time_Analysis':
                		$jsonfilter = array(
                			'lead_id' => $data->lead_id,
                			'sub_lead_id' => $data->sub_lead_id,
                			'startdate' => $data->startdate,
                			'enddate' => $data->enddate
                		);
                		if($data->subtype=="details"){
                			$jsonfilter['lid'] = $data->lid;
                		}
                		break;
                    case 'Activity_Cost_Distribution':
                        if($data->startdate!='' && $data->enddate!='')
                        {
                            $startdate = date('Y-m-d',strtotime($data->startdate));
  				            $enddate = date('Y-m-d',strtotime($data->enddate));
                        }else{
                                    $startdate='NA';
                                    $enddate='NA';
                        }
                		$jsonfilter = array(
                			'lead_id' => $data->lead_id,
                			'startdate' => $startdate,
                			'enddate' => $enddate
                		);
                		if($data->subtype=="details"){
                			$jsonfilter['lid'] = $data->lid;
                		}
                		break;
                     case 'Split_Cost_Analysis':

                        $startdate=$data->startdate;
                        $enddate=$data->enddate;
                        if($data->startdate!='NA' && $data->enddate!='NA')
                        {
                           $startdate = date('Y-m-d',strtotime($data->startdate));
						   $enddate = date('Y-m-d',strtotime($data->enddate));
                        }
                		$jsonfilter = array(
                			'lead_id' => $data->lead_id,
                			'startdate' => $startdate,
                			'enddate' => $enddate
                		);
                		if($data->subtype=="details"){
                			$jsonfilter['Cost_type'] = $data->Cost_type;
                		}
                		break;
                     case 'Lead_Spend_Analysis':
                        $startdate=$data->startdate;
                        $enddate=$data->enddate;
                        if($data->startdate!='NA' && $data->enddate!='NA')
                        {
                            $startdate = date('Y-m-d',strtotime($data->startdate));
  				            $enddate = date('Y-m-d',strtotime($data->enddate));
                        }
                		$jsonfilter = array(
                			'lead_id' => $data->lead_id,
                			'startdate' => $startdate,
                			'enddate' => $enddate
                		);
                		if($data->subtype=="details"){
                			$jsonfilter['activitydate'] = $data->activitydate;
                		}
                		break;
                     case 'Lead_Score_Analysis':
                        $startdate=$data->startdate;
                        $enddate=$data->enddate;
                        if($data->startdate!='NA' && $data->enddate!='NA')
                        {
                            $startdate = date('Y-m-d',strtotime($data->startdate));
  				            $enddate = date('Y-m-d',strtotime($data->enddate));
                        }
                		$jsonfilter = array(
                			'startdate' => $startdate,
                			'enddate' => $enddate,
                            'selectiontype' =>$data->selectiontype,
                            'selectionid' =>$data->selectionid
                		);
                		if($data->subtype=="details"){
                			$jsonfilter['lid']=$data->lead_id;
                		}
                		break;
                	 case 'Lead_Resource_Cost_Split':
                     case 'Lead_Activity_Cost_Split':
                     case 'Lead_Average_Activity_Cost':
							$startdate=$data->startdate;
							$enddate=$data->enddate;
							if($data->startdate!='NA' && $data->enddate!='NA')
							{
							$startdate = date('Y-m-d',strtotime($data->startdate));
							$enddate = date('Y-m-d',strtotime($data->enddate));
							}
							$jsonfilter = array(
							'lead_id' => $data->lead_id,
							'startdate' => $startdate,
							'enddate' => $enddate
							);
							if($data->subtype=="details" && $type=='Lead_Resource_Cost_Split'){
								$jsonfilter['resourceid']=$data->resourceid;
							}
                            if($data->subtype=="details" && ($type=='Lead_Activity_Cost_Split' || $type=='Lead_Average_Activity_Cost')){
								$jsonfilter['activityid']=$data->activityid;
							}
                			break;

                }
                $data = array(
                	'report_id' => $data->reportid,
                	'report_name' => $data->report_name,
                	'report_parent_id' => $data->report_parent_id,
                	'manager_id' => $uid,
                	'filters' => json_encode($jsonfilter),
                	'remarks' => ''
                );
                $reportdata = $this->leadModel->save_report($data,$uid,$id);
                 echo json_encode($reportdata);
			}catch(LConnectApplicationException $e){
				$this->exception($e);
			}
		}
	}

	public function getsaved_reports(){
        if($this->session->userdata('uid')){
            try{
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $type=$data->type;
                $id=$data->id;
                $reportdata = $this->leadModel->get_savedreportdetails($this->session->userdata('uid'),$type,$id);
                echo json_encode($reportdata);
            }catch(LConnectApplicationException $e){
                $this->exception($e);
            }
        }else{
            redirect('indexController');
        }
    }

    public function get_leads(){
		if($this->session->userdata('uid')){
			try{
				$userid = $this->session->userdata('uid');
				$leads_list = $this->leadModel->get_leads($userid);
				echo json_encode($leads_list);
			}catch(LConnectApplicationException $e){
				$this->exception($e);
			}
		}else{
			redirect('indexController');
		}
	}

     public function get_details(){
		if($this->session->userdata('uid')){
			try{
				$userid = $this->session->userdata('uid');
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $selectiontype=$data->selection_type;
				$leads_list = $this->leadModel->get_details($userid,$selectiontype);
				echo json_encode($leads_list);
			}catch(LConnectApplicationException $e){
				$this->exception($e);
			}
		}else{
			redirect('indexController');
		}
	}

}