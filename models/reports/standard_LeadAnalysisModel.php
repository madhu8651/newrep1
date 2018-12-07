<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('standard_LeadAnalysisModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class standard_LeadAnalysisModel extends CI_Model{
	public function __construct(){
		parent::__construct();
	}

	public function exception($lae){
		$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $lae;
	}

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
				//M166, M168
				return '';
			}
			$ids = implode("','", $childNodes);
			return $ids;
		} catch (LConnectApplicationException $e) {
			$this->exception($e);
		}
	}

	private function fetchChildNodes($givenID, & $childNodes, $allParentNodes)  {
		foreach ($allParentNodes as $user_id => $reporting_to) {
			if ($reporting_to == $givenID)  {
				array_push($childNodes, $user_id);
				$this->fetchChildNodes($user_id, $childNodes, $allParentNodes);
			}
		}
	}

	public function generateReport($data){
		try{
			switch ($data['type']) {
				case 'Lead_Distribution_Analysis':
						if($data['subtype'] == "NA"){
							$qry = "CALL r_Leaddistribution_Summary('".$data['user_id']."','".$data['category']."','".$data['startdate']."','".$data['enddate']."')";
							$query = $GLOBALS['$dbFramework']->query($qry);
							return $query->result();
						}else if($data['subtype'] == "details"){
							$qry = "CALL r_Leaddistribution_Analysis('".$data['user_id']."','".$data['category']."','".$data['lid']."','".$data['startdate']."','".$data['enddate']."')";
							$query = $GLOBALS['$dbFramework']->query($qry);
							return $query->result();
						}
					break;

				case 'Lead_FAT_Analysis':
					$qry = "CALL r_LeadFirstAction_Analysis('".$data['user_id']."','".$data['user_type']."','".$data['rep_id']."','".$data['startdate']."','".$data['enddate']."')";
					$query = $GLOBALS['$dbFramework']->query($qry);
					return $query->result();
					break;

				case 'Lead_Lifecycle_Analysis':
					$result = array();
					if($data['sub_lead_id']=='All' || $data['sub_lead_id']=='Log_Activity'){
						$qry = "Call `r_LeadlifeCycle_Analysis`('".$data['user_id']."','".$data['lead_id']."','Log_Activity')";
						$query = $GLOBALS['$dbFramework']->query($qry);
						$result[0]['Action'] = $query->result();
					}
					if($data['sub_lead_id']=='All' || $data['sub_lead_id']=='Activity'){
						$qry1 = "Call `r_LeadlifeCycle_Analysis`('".$data['user_id']."','".$data['lead_id']."','Activity')";
						$query1 = $GLOBALS['$dbFramework']->query($qry1);
						if(count($result)>0){
							$result[1]['Activity'] = $query1->result();
						}else{
							$result[0]['Activity'] = $query1->result();
						}
					}
					return $result;
					break;

				case 'Lead_Status_Analysis':
					if($data['subtype']=='NA'){
						$qry = "CALL r_LeadStatus_Summary('".$data['user_id']."','".$data['startdate']."','".$data['enddate']."')";
						$query = $GLOBALS['$dbFramework']->query($qry);
						return $query->result();
					}else if($data['subtype']=='details'){
						$qry = "CALL r_LeadStatus_Analysis('".$data['user_id']."','".$data['lid']."','".$data['startdate']."','".$data['enddate']."')";
						$query = $GLOBALS['$dbFramework']->query($qry);
						return $query->result();
					}
					break;
				case 'Lead_Time_Analysis':
					if($data['subtype']=="NA"){
						$qry = "CALL r_LeadTime_Summary('".$data['user_id']."','".$data['lead_id']."','".$data['sub_lead_id']."','".$data['startdate']."','".$data['enddate']."')";
						$query = $GLOBALS['$dbFramework']->query($qry);
						return $query->result();
					}else{
						$qry = "CALL r_LeadTime_Analysis('".$data['user_id']."','".$data['lead_id']."','".$data['sub_lead_id']."','".$data['lid']."','".$data['startdate']."','".$data['enddate']."')";
						$query = $GLOBALS['$dbFramework']->query($qry);
						return $query->result();
					}
					break;
                case 'Activity_Cost_Distribution':
                    if($data['subtype']=="details"){
                        $query = $GLOBALS['$dbFramework']->query("CALL r_Lead_Spend_Cost_Distribution_Analysis('".$data['user_id']."','".$data['lid']."',
                                                                 '".$data['lead_id']."','".$data['startdate']."','".$data['enddate']."')");

                        return $query->result();
                    }else{
                        $query = $GLOBALS['$dbFramework']->query("CALL r_Lead_Spend_Cost_Distribution_Summary('".$data['user_id']."','".$data['lead_id']."',
                                                                  '".$data['startdate']."','".$data['enddate']."')");
                        return $query->result();
                    }
                break;
                case 'Split_Cost_Analysis':
                    if($data['subtype']=="details"){
                        $query = $GLOBALS['$dbFramework']->query("CALL r_Lead_Spend_SplitCost_Analysis('".$data['user_id']."','".$data['Cost_type']."',
                                                                 '".$data['lead_id']."','".$data['startdate']."','".$data['enddate']."')");

                        return $query->result();
                    }else{
                        $query = $GLOBALS['$dbFramework']->query("CALL r_Lead_Spend_SplitCost_Sumary('".$data['user_id']."','".$data['lead_id']."',
                                                                  '".$data['startdate']."','".$data['enddate']."')");
                        foreach($query->result() as $row)
                        {
                          $result=array(
                                 'name'=>'Resource_Cost',
                                 'value'=>$row->Resource_Cost
                            );
                          $result2=array(
                                 'name'=>'Activity_Cost',
                                 'value'=>$row->Activity_Cost
                            );

                        }
                        return array(
                               $result,
                               $result2

                        );
                    }
                break;
                 case 'Lead_Spend_Analysis':
                    if($data['subtype']=="details"){
                                 // echo"CALL r_Lead_Spend_Analysis('".$data['user_id']."','".$data['activitydate']."',
                                      //                           '".$data['lead_id']."','".$data['startdate']."','".$data['enddate']."')";
                        $query = $GLOBALS['$dbFramework']->query("CALL r_Lead_Spend_Analysis('".$data['user_id']."','".$data['activitydate']."',
                                                                 '".$data['lead_id']."','".$data['startdate']."','".$data['enddate']."')");

                        return $query->result();
                    }else{

                        $query = $GLOBALS['$dbFramework']->query("CALL r_Lead_Spend_Summary('".$data['user_id']."','".$data['lead_id']."',
                                                                  '".$data['startdate']."','".$data['enddate']."')");
                        return $query->result();
                    }
                break;
                case 'Lead_Resource_Cost_Split':
                    if($data['subtype']=="details"){
                        /*echo"CALL r_Lead_Spend_Analysis('".$data['user_id']."','".$data['resourceid']."',
                                                                 '".$data['lead_id']."','".$data['startdate']."','".$data['enddate']."')";*/
                        $query = $GLOBALS['$dbFramework']->query("CALL r_Lead_Spend_ResourceCost_Split_Analysis('".$data['user_id']."','".$data['resourceid']."',
                                                                 '".$data['lead_id']."','".$data['startdate']."','".$data['enddate']."')");
                        return $query->result();
                    }else{
                        //echo"CALL r_Lead_Spend_ResourceCost_Split_Summary('".$data['user_id']."','".$data['lead_id']."',
                                                                 // '".$data['startdate']."','".$data['enddate']."')";
                        $query = $GLOBALS['$dbFramework']->query("CALL r_Lead_Spend_ResourceCost_Split_Summary('".$data['user_id']."','".$data['lead_id']."',
                                                                  '".$data['startdate']."','".$data['enddate']."')");
                        return $query->result();
                    }
                break;
                case 'Lead_Activity_Cost_Split':
                    if($data['subtype']=="details"){
                        /*echo"CALL r_Lead_Spend_Activitycost_Split_Analysis('".$data['user_id']."','".$data['activityid']."',
                                                                 '".$data['lead_id']."','".$data['startdate']."','".$data['enddate']."')";*/
                        $query = $GLOBALS['$dbFramework']->query("CALL r_Lead_Spend_Activitycost_Split_Analysis('".$data['user_id']."','".$data['activityid']."',
                                                                 '".$data['lead_id']."','".$data['startdate']."','".$data['enddate']."')");
                        return $query->result();
                    }else{
                       /* echo"CALL r_Lead_Spend_Activitycost_Split_Summary('".$data['user_id']."','".$data['lead_id']."',
                                                                  '".$data['startdate']."','".$data['enddate']."')";*/
                        $query = $GLOBALS['$dbFramework']->query("CALL r_Lead_Spend_Activitycost_Split_Summary('".$data['user_id']."','".$data['lead_id']."',
                                                                  '".$data['startdate']."','".$data['enddate']."')");
                        return $query->result();
                    }
                break;
                case 'Lead_Average_Activity_Cost':
                    if($data['subtype']=="details"){
                      //  echo"CALL r_Lead_Spend_AVG('".$data['user_id']."','".$data['lead_id']."','".$data['activityid']."',
                        //                                    '".$data['startdate']."','".$data['enddate']."')";
                        $query = $GLOBALS['$dbFramework']->query("CALL r_Lead_Spend_AVG('".$data['user_id']."','".$data['lead_id']."','".$data['activityid']."',
                                                            '".$data['startdate']."','".$data['enddate']."')");
                        return $query->result();
                    }else{
                       /* echo"CALL r_Lead_Spend_Average_ActivityCost_Sumary('".$data['user_id']."','".$data['lead_id']."',
                                                                  '".$data['startdate']."','".$data['enddate']."')";*/

                        $query = $GLOBALS['$dbFramework']->query("CALL r_Lead_Spend_Average_ActivityCost_Sumary('".$data['user_id']."','".$data['lead_id']."',
                                                                  '".$data['startdate']."','".$data['enddate']."')");
                        return $query->result();
                    }
                break;
                case 'Lead_Score_Analysis':
                    if($data['subtype']=="details"){
                       /* echo"CALL r_Lead_Spend_AVG('".$data['user_id']."','".$data['lead_id']."','".$data['activityid']."',
                                                            '".$data['startdate']."','".$data['enddate']."')";*/
                      // echo"CALL r_LeadScore_Analysis('".$data['user_id']."','".$data['selectiontype']."','".$data['selectionid']."','".$data['lead_id']."',
                                                           // '".$data['startdate']."','".$data['enddate']."')";
                        $query = $GLOBALS['$dbFramework']->query("CALL r_LeadScore_Analysis('".$data['user_id']."','".$data['selectiontype']."','".$data['lead_id']."','".$data['selectionid']."',
                                                            '".$data['startdate']."','".$data['enddate']."')");
                        return $query->result();
                    }else{
                       /* echo"CALL r_LeadScore_Summary('".$data['user_id']."','".$data['selectiontype']."','".$data['selectionid']."',
                                                                  '".$data['startdate']."','".$data['enddate']."')";*/
                        $query = $GLOBALS['$dbFramework']->query("CALL r_LeadScore_Summary('".$data['user_id']."','".$data['selectiontype']."','".$data['selectionid']."',
                                                                  '".$data['startdate']."','".$data['enddate']."')");
                        return $query->result();
                    }
                break;
			}
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function save_report($data,$managerid,$id){
		try{
			if($id=='0'){
				$reportname=$data['report_name'];
	            $query=$GLOBALS['$dbFramework']->query("SELECT COUNT(*) as cnt FROM reports
	              WHERE report_name REGEXP '^(".$reportname."\\\([0-9]+\\\)|".$reportname.")$' and manager_id='".$managerid."'");
	            $cnt=$query->result()[0]->cnt;
	            if($cnt>0){
	                $data['report_name']=$reportname."(".$cnt++.")";
	            }
	            $query=$GLOBALS['$dbFramework']->insert('reports',$data);
			}else{
				$reportid=$data['report_id'];
                $report_name=$data['report_name'];
                $report_parent_id=$data['report_parent_id'];
                $filters=$data['filters'];
                $filters1=json_decode($filters);
                $framestring='';
                if(array_key_exists('lid',$filters1)){
                    $framestring.="AND json_extract(filters,'$.lid')='".$filters1->lid."'";
                }
                if(array_key_exists('startdate',$filters1)){
                    $framestring.="AND json_extract(filters,'$.startdate')='".$filters1->startdate."'";
                }
                if(array_key_exists('enddate',$filters1)){
                    $framestring.="AND json_extract(filters,'$.enddate')='".$filters1->enddate."'";
                }
                if(array_key_exists('category',$filters1)){
                    $framestring.="AND json_extract(filters,'$.category')='".$filters1->category."'";
                }
                if(array_key_exists('user_type',$filters1)){
                    $framestring.="AND json_extract(filters,'$.user_type')='".$filters1->user_type."'";
                }
                if(array_key_exists('user_id',$filters1)){
                    $framestring.="AND json_extract(filters,'$.user_id')='".$filters1->user_id."'";
                }
                if(array_key_exists('lead_id',$filters1)){
                	$framestring.="AND json_extract(filters,'$.lead_id')='".$filters1->lead_id."'";
                }
                if(array_key_exists('sub_lead_id',$filters1)){
                	$framestring.="AND json_extract(filters,'$.sub_lead_id')='".$filters1->sub_lead_id."'";
                }
                if(array_key_exists('Cost_type',$filters1)){
                	$framestring.="AND json_extract(filters,'$.Cost_type')='".$filters1->Cost_type."'";
                }
                $query=$GLOBALS['$dbFramework']->query("select count(*) as cnt from reports where report_id='".$reportid."'  AND report_parent_id='".$report_parent_id."' AND manager_id='".$managerid."' AND report_name='".$report_name."'   ".$framestring."");
                $cnt=$query->result()[0]->cnt;
                if($cnt>0){
                    $query=0;
                }else{
                    $query=$GLOBALS['$dbFramework']->update('reports',$data,array('id'=>$id,'manager_id'=>$managerid));
                }
			}
			return $query;
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function get_savedreportdetails($loginid,$type,$id){
		try{
			$query = $GLOBALS['$dbFramework']->query("SELECT * FROM reports WHERE id='$id' AND manager_id='$loginid'");

			if($query->num_rows()){
				foreach($query->result() as $row){
					$jsondata = json_decode($row->filters);
                   
					$GLOBALS['$log']->debug("Reportdata : ".$row->filters);
					if(array_key_exists("lid", $jsondata)){
						$subtype = "details";
					}else{
						$subtype = "NA";
					}
					switch ($type) {
						case 'Lead_Distribution_Analysis':
							$rdata = array(
								'user_id' => $loginid,
								'category' => $jsondata->category,
								'type' => $type,
								'subtype' => $subtype,
								'startdate' => date('Y-m-d',strtotime($jsondata->startdate)),
								'enddate' => date('Y-m-d',strtotime($jsondata->enddate))	
							);
							if($subtype == "details"){
								$rdata['lid'] = $jsondata->lid;
							}
							$resultset = $this->generateReport($rdata);
							$GLOBALS['$log']->debug("JSON response : ".json_encode($resultset));
							if($subtype == "NA"){
								$reportData = array();
								for($i=0;$i<count($resultset);$i++){
									switch ($jsondata->category) {
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
									$reportData[$i]['category_name'] = ucfirst($jsondata->category); 
								}
								return array(
									'startdate' => $jsondata->startdate,
									'enddate' => $jsondata->enddate,
									'report_nme' => $row->report_name,
									'category' => $jsondata->category,
									'tabledetails' => $reportData
								);
							}else{

                                    
								return array(
									'startdate' => $jsondata->startdate,
									'enddate' => $jsondata->enddate,
									'report_nme' => $row->report_name,
									'category' => $jsondata->category,
									'tabledetails' => $resultset,
									'lid' => $jsondata->lid
								);
							}
							break;
						case 'Lead_FAT_Analysis':
							$rep_id = $jsondata->user_id;
							if($rep_id=="All"){
								$rep_id='';
							}
							$rdata = array(
								'user_id' => $loginid,
								'user_type' => $jsondata->user_type,
								'rep_id' => $rep_id,
								'type' => $type,
								'startdate' => date('Y-m-d',strtotime($jsondata->startdate)),
								'enddate' => date('Y-m-d',strtotime($jsondata->enddate))	
							);
							$resultset = $this->generateReport($rdata);
							$GLOBALS['$log']->debug("JSON response : ".json_encode($resultset));
							return array(
									'startdate' => $jsondata->startdate,
									'enddate' => $jsondata->enddate,
									'report_nme' => $row->report_name,
									'user_type' => $jsondata->user_type,
									'user_id' => $jsondata->user_id,
									'tabledetails' => $resultset
								);
							break;
						case 'Lead_Lifecycle_Analysis':
							$rdata = array(
								'lead_id' => $jsondata->lead_id,
								'sub_lead_id' => $jsondata->sub_lead_id,
								'type' => $type,
								'user_id' => $loginid
							);
							$resultset = $this->generateReport($rdata);
							$GLOBALS['$log']->debug("JSON response : ".json_encode($resultset));
							return array(
								'lead_id' => $jsondata->lead_id,
								'sub_lead_id' => $jsondata->sub_lead_id,
								'report_nme' => $row->report_name,
								'tabledetails' => $resultset
							);
							break;

						case 'Lead_Status_Analysis':
							$rdata = array(
								'user_id' => $loginid,
								'type' => $type,
								'subtype' => $subtype,
								'startdate' => date('Y-m-d',strtotime($jsondata->startdate)),
								'enddate' => date('Y-m-d',strtotime($jsondata->enddate))	
							);
							if($subtype == "details"){
								$rdata['lid'] = $jsondata->lid;
							}
							$resultset = $this->generateReport($rdata);
							$GLOBALS['$log']->debug("JSON response : ".json_encode($resultset));
							if($subtype=="NA"){
								$reportData = array();
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
								return array(
									'startdate' => $jsondata->startdate,
									'enddate' => $jsondata->enddate,
									'report_nme' => $row->report_name,
									'tabledetails' => $reportData
								);
							}else{
								return array(
									'startdate' => $jsondata->startdate,
									'enddate' => $jsondata->enddate,
									'report_nme' => $row->report_name,
									'tabledetails' => $resultset,
									'lid' => $jsondata->lid
								);
							}
							break;

						case 'Lead_Time_Analysis':
							$rdata = array(
								'user_id' => $loginid,
								'type' => $type,
								'subtype' => $subtype,
								'startdate' => date('Y-m-d',strtotime($jsondata->startdate)),
								'enddate' => date('Y-m-d',strtotime($jsondata->enddate)),
								'lead_id' => $jsondata->lead_id,
								'sub_lead_id' => $jsondata->sub_lead_id
							);
							if($subtype == "details"){
								$rdata['lid'] = $jsondata->lid;
							}
							$resultset = $this->generateReport($rdata);
							$GLOBALS['$log']->debug("JSON response : ".json_encode($resultset));
							if($subtype=="NA"){
								$reportData = array();
								for($i=0;$i<count($resultset);$i++){
									$reportData[$i]['lname'] = $resultset[$i]->lname;
									$reportData[$i]['lead_count'] = $resultset[$i]->Time_duration;
									$reportData[$i]['lid'] = $resultset[$i]->lid;
								} 
								return array(
									'startdate' => $jsondata->startdate,
									'enddate' => $jsondata->enddate,
									'report_nme' => $row->report_name,
									'lead_id' => $jsondata->lead_id,
									'sub_lead_id' => $jsondata->sub_lead_id,
									'tabledetails' => $reportData
								);
							}else{
								if($jsondata->sub_lead_id=='Resource')
									$query1 = $GLOBALS['$dbFramework']->query("SELECT user_name as name FROM user_details where user_id='".$jsondata->lid."'");
								else
									$query1 = $GLOBALS['$dbFramework']->query("SELECT lookup_value as name FROM lookup where lookup_id='".$jsondata->lid."'");

								foreach($query1->result() as $rows2)
								{
								   $name=$rows2->name;
								}
								return array(
									'startdate' => $jsondata->startdate,
									'enddate' => $jsondata->enddate,
									'report_nme' => $row->report_name,
									'tabledetails' => $resultset,
									'lid' => $jsondata->lid,
									'lead_id' => $jsondata->lead_id,
									'sub_lead_id' => $jsondata->sub_lead_id,
									'name' => $name
								);
							}
							break;
                        case 'Activity_Cost_Distribution':
                             $startdate=$jsondata->startdate;
                             if($startdate!='NA')
                                     $startdate=date('Y-m-d',strtotime($jsondata->startdate));
                             $enddate=$jsondata->enddate;
                             if($enddate!='NA')
                                    $enddate=date('Y-m-d',strtotime($jsondata->enddate));
							$rdata = array(
								'user_id' => $loginid,
								'type' => $type,
								'subtype' => $subtype,
								'startdate' => $startdate,
								'enddate' => $enddate,
								'lead_id' => $jsondata->lead_id
							);
							if($subtype == "details"){
								$rdata['lid'] = $jsondata->lid;
							}
							$resultset = $this->generateReport($rdata);
							$GLOBALS['$log']->debug("JSON response : ".json_encode($resultset));
							$reportData = array();
							$reportData= array(
									'startdate' => $jsondata->startdate,
									'enddate' => $jsondata->enddate,
									'report_nme' => $row->report_name,
									'lead_id' => $jsondata->lead_id,
									'tabledetails' => $resultset
								);

                            if($subtype=='details')
                                $reportData['lid'] = $jsondata->lid;

                            return $reportData;

							break;
                         case 'Split_Cost_Analysis':
                                 $startdate=$jsondata->startdate;
                                 if($startdate!='NA')
                                         $startdate=date('Y-m-d',strtotime($jsondata->startdate));
                                 $enddate=$jsondata->enddate;
                                 if($enddate!='NA')
                                        $enddate=date('Y-m-d',strtotime($jsondata->enddate));
                                 $subtype='Summary';
    							$rdata = array(
    								'user_id' => $loginid,
    								'type' => $type,
    								'subtype' => $subtype,
    								'startdate' => $startdate,
    								'enddate' => $enddate,
    								'lead_id' => $jsondata->lead_id
    							);
    							if(array_key_exists("Cost_type", $jsondata)) {
    								$rdata['Cost_type'] = $jsondata->Cost_type;
    								$rdata['subtype'] = 'details';
    							}
    							$resultset = $this->generateReport($rdata);
    							$GLOBALS['$log']->debug("JSON response : ".json_encode($resultset));
    							$reportData = array();
    							$reportData= array(
    									'startdate' => $jsondata->startdate,
    									'enddate' => $jsondata->enddate,
    									'report_nme' => $row->report_name,
    									'lead_id' => $jsondata->lead_id,
    									'tabledetails' => $resultset
    								);
                                if(array_key_exists("Cost_type", $jsondata))
                                    $reportData['Cost_type'] = $jsondata->Cost_type;
                                return $reportData;
    							break;
                          case 'Split_Cost_Analysis':
                                   $startdate=$jsondata->startdate;
                                   if($startdate!='NA')
                                           $startdate=date('Y-m-d',strtotime($jsondata->startdate));
                                   $enddate=$jsondata->enddate;
                                   if($enddate!='NA')
                                          $enddate=date('Y-m-d',strtotime($jsondata->enddate));
      							   $rdata = array(
      								'user_id' => $loginid,
      								'type' => $type,
      								'subtype' => $subtype,
      								'startdate' => $startdate,
      								'enddate' => $enddate,
      								'lead_id' => $jsondata->lead_id
      							);
      							if($subtype == "details"){
      								$rdata['activitydate'] = $jsondata->activitydate;
      							}
      							$resultset = $this->generateReport($rdata);
      							$GLOBALS['$log']->debug("JSON response : ".json_encode($resultset));
      							$reportData = array();
      							$reportData= array(
      									'startdate' => $jsondata->startdate,
      									'enddate' => $jsondata->enddate,
      									'report_nme' => $row->report_name,
      									'lead_id' => $jsondata->lead_id,
      									'tabledetails' => $resultset
      								);
                                  if(array_key_exists("Cost_type", $jsondata))
                                      $reportData['Cost_type'] = $jsondata->Cost_type;
                                  return $reportData;
							break;
                          case 'Lead_Score_Analysis':
      							$rdata = array(
                                    'user_id' => $loginid,
      								'selectiontype' => $jsondata->selectiontype,
      								'selectionid' => $jsondata->selectionid,
      								'type' => $type,
                                    'subtype' => $subtype,
                                    'startdate' => $jsondata->startdate,
      								'enddate' => $jsondata->enddate
      							);
                                if(array_key_exists("lid", $jsondata))
                                      $rdata['lead_id'] = $jsondata->lid;
      							$resultset = $this->generateReport($rdata);
      							$GLOBALS['$log']->debug("JSON response : ".json_encode($resultset));
      							$reportData = array();
      							$reportData= array(
      									'startdate' => $jsondata->startdate,
      									'enddate' => $jsondata->enddate,
      									'report_nme' => $row->report_name,
      									'selectiontype' => $jsondata->selectiontype,
      								    'selectionid' => $jsondata->selectionid,
      									'tabledetails' => $resultset
      								);
                                  if(array_key_exists("lid", $jsondata))
                                      $reportData['lid'] = $jsondata->lid;
                                  return $reportData;
						  break;

                          case 'Lead_Resource_Cost_Split':
                          case 'Lead_Activity_Cost_Split':
                          case 'Lead_Average_Activity_Cost':
                                    $reportData = array();
                                    $startdate=$jsondata->startdate;
                                   if($startdate!='NA')
                                           $startdate=date('Y-m-d',strtotime($jsondata->startdate));
                                   $enddate=$jsondata->enddate;
                                   if($enddate!='NA')
                                          $enddate=date('Y-m-d',strtotime($jsondata->enddate));
                                $rdata = array(
                                    'user_id' => $loginid,
                                    'type' => $type,
                                    'subtype' => $subtype,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate,
                                    'lead_id' => $jsondata->lead_id
                                );
                                if($subtype == "details" && $type=='Lead_Resource_Cost_Split'){
                                    $rdata['activitydate'] = $jsondata->activitydate;
                                }
                                if(array_key_exists("activityid", $jsondata) && ($type=='Lead_Activity_Cost_Split' || $type=='Lead_Average_Activity_Cost')){
                                    $rdata['subtype'] = 'details';
                                    $rdata['activityid'] = $jsondata->activityid;
                                }
                                $resultset = $this->generateReport($rdata);
                                $GLOBALS['$log']->debug("JSON response : ".json_encode($resultset));

                                $reportData= array(
                                        'startdate' => $jsondata->startdate,
                                        'enddate' => $jsondata->enddate,
                                        'report_nme' => $row->report_name,
                                        'lead_id' => $jsondata->lead_id,
                                        'tabledetails' => $resultset
                                    );
                                  if(array_key_exists("resourceid", $jsondata) && $type=='Lead_Resource_Cost_Split')
                                      $reportData['resourceid'] = $jsondata->resourceid;
                                  if(array_key_exists("activityid", $jsondata) && ($type=='Lead_Activity_Cost_Split'))
                                      $reportData['activityid'] = $jsondata->activityid;
                                  return $reportData;

                        case 'Lead_Spend_Analysis':
                                 $startdate=$jsondata->startdate;
                                 if($startdate!='NA')
                                         $startdate=date('Y-m-d',strtotime($jsondata->startdate));
                                 $enddate=$jsondata->enddate;
                                 if($enddate!='NA')
                                        $enddate=date('Y-m-d',strtotime($jsondata->enddate));
                                 $subtype='Summary';
                                $rdata = array(
                                    'user_id' => $loginid,
                                    'type' => $type,
                                    'subtype' => $subtype,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate,
                                    'lead_id' => $jsondata->lead_id
                                );
                                if(array_key_exists("activitydate", $jsondata)) {
                                    $rdata['activitydate'] = $jsondata->activitydate;
                                    $rdata['subtype'] = 'details';
                                }
                                $resultset = $this->generateReport($rdata);
                                $GLOBALS['$log']->debug("JSON response : ".json_encode($resultset));
                                $reportData = array();
                                $reportData= array(
								
                                        'startdate' => date('d-m-Y',strtotime($jsondata->startdate)),
                                        'enddate' => date('d-m-Y',strtotime($jsondata->enddate)),
                                        'report_nme' => $row->report_name,
                                        'lead_id' => $jsondata->lead_id,
                                        'tabledetails' => $resultset
                                    );

                                return $reportData;
                                break;
					}
				}
			}
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

	public function get_leads($uid){
		try{
			$children ="'".$this->getChildrenForParent($uid)."'";
		  /*	$query = $GLOBALS['$dbFramework']->query("
				SELECT distinct lead_id,lead_name,DATE_FORMAT(lead_created_time,'%d-%m-%Y') AS leadInceptionDate
				FROM lead_info
				WHERE (lead_manager_owner IN ('".$uid."',".$children.")
				OR lead_rep_owner IN ('".$uid."',".$children.")) order by lead_name"); */
                $query = $GLOBALS['$dbFramework']->query("
				 Select DISTINCT a.lead_id,a.lead_name,
                CASE
                	WHEN lead_created_time >(SELECT MIN(starttime) FROM rep_log b WHERE a.lead_id=b.leadid) THEN DATE_FORMAT(c.starttime,'%d-%m-%Y')
                	ELSE DATE_FORMAT(lead_created_time,'%d-%m-%Y')
                END AS leadInceptionDate
                FROM lead_info a,rep_log c WHERE a.lead_id=c.leadid
				and (lead_manager_owner IN ('".$uid."',".$children.")
				OR lead_rep_owner IN ('".$uid."',".$children.")) GROUP BY c.leadid order by lead_name");
			return $query->result();
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}

    	public function get_details($uid,$selectiontype){
		try{
			$children ="'".$this->getChildrenForParent($uid)."'";
            if($selectiontype=='location')
            {
                $query=$GLOBALS['$dbFramework']->query("SELECT distinct hkey2 as id,hr.hvalue2 AS name
                                                        FROM hierarchy hr JOIN lead_info li ON li.lead_business_loc=hr.hkey2
                                                        and (lead_manager_owner IN ('".$uid."',".$children.")
    				                                    OR lead_rep_owner IN ('".$uid."',".$children.")) order by hr.hvalue2");

            }else if($selectiontype=='industry'){

                $query=$GLOBALS['$dbFramework']->query("SELECT distinct hkey2 as id,hr.hvalue2 AS name FROM hierarchy hr JOIN lead_info li ON li.lead_industry=hr.hkey2
                                                        and (lead_manager_owner IN ('".$uid."',".$children.")
    				                                    OR lead_rep_owner IN ('".$uid."',".$children.")) order by hr.hvalue2");

            }else if($selectiontype=='product'){
                $query=$GLOBALS['$dbFramework']->query("SELECT  distinct hkey2 as id,hr.hvalue2 AS name
                                                        FROM hierarchy hr
                                                        JOIN lead_product_map lpm ON lpm.product_id = hr.hkey2
                                                        join lead_info li on li.lead_id= lpm.lead_id
                                                        where (lead_manager_owner IN ('".$uid."',".$children.")
    				                                    OR lead_rep_owner IN ('".$uid."',".$children.")) order by hr.hvalue2 ");


            }else if($selectiontype=='representative'){
               	$query=$GLOBALS['$dbFramework']->query("SELECT a.user_id as id,a.user_name as name FROM  user_details a
                                                        WHERE a.user_id in ('".$uid."',".$children.") order by user_name ");


            }
            return $query->result();
		}catch(LConnectApplicationException $e){
			$this->exception($e);
		}
	}
}