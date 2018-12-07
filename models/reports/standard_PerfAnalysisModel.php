<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('standard_PerfAnalysisModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class standard_PerfAnalysisModel extends CI_Model{

     public function __construct(){
        parent::__construct();
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
			$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
			throw $e;
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
     public function get_product($userid)
    {
        try{
                $children ="'".$this->getChildrenForParent($userid)."'";
                $query=$GLOBALS['$dbFramework']->query("select opp.opportunity_product as Product_ID ,hr.hvalue2 as Product_Name
                from opportunity_details opp join hierarchy hr on hr.hkey2=opp.opportunity_product left join user_details ud
                on ud.user_id = opp.manager_owner_id where  ud.user_id in ('".$userid."',".$children.")  group by opportunity_product order by hr.hvalue2");
                return $query->result();
            }
            catch (LConnectApplicationException $e)
            {
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
            }
    }
  //  public function generateReport($userid,$type,$subtype,$val1,$val2,$val3,$val4)
    public function generateReport($userid,$data)
    {
        try{
            switch($data->type)
            {
                Case 'prod_performanceanalysis':
                     $start_date=date("Y-m-d",strtotime($data->startDate));
                     $end_date=date("Y-m-d",strtotime($data->endDate));
                     $selectiontype='';
                        if($data->product!='All' && array_key_exists('selectiontype',$data) && $data->selectiontype!='')  //condition detailed report
                         {
                            $query=$GLOBALS['$dbFramework']->query("CALL  r_Product_performance_Analysis('".$userid."','".$data->selectiontype."','".$data->product."','".$start_date."','".$end_date."')");
                         }else{
                             if(array_key_exists('selectiontype',$data)){
                               $selectiontype=$data->selectiontype;
                             }
                            // echo"CALL  r_Product_performance_Summary('".$userid."','".$data->product."','".$selectiontype."','".$start_date."','".$end_date."')";
                            $query=$GLOBALS['$dbFramework']->query("CALL  r_Product_performance_Summary('".$userid."','".$data->product."','".$selectiontype."','".$start_date."','".$end_date."')");
                         }
                     return $query->result();
                break;
                Case 'opp_performanceanalysis':
                     $start_date=date("Y-m-d",strtotime($data->startDate));
                     $end_date=date("Y-m-d",strtotime($data->endDate));
                     $selectiontype='';
                        if($data->opportunity!='All')  //condition detailed report
                         {
                            //echo"CALL  r_Opportunity_Performance_Summary('".$userid."','".$data->opportunity."','detail','".$start_date."','".$end_date."')";
                            $query=$GLOBALS['$dbFramework']->query("CALL  r_Opportunity_Performance_Summary('".$userid."','".$data->opportunity."','detail','".$start_date."','".$end_date."')");
                         }else{
                             if(array_key_exists('selectionId',$data)){
                               $selectiontype=$data->selectionId;
                             }
                            // echo"CALL  r_Opportunity_Performance_Summary('".$userid."','".$data->opportunity."','".$selectiontype."','".$start_date."','".$end_date."')";
                             $query=$GLOBALS['$dbFramework']->query("CALL  r_Opportunity_Performance_Summary('".$userid."','".$data->opportunity."','".$selectiontype."','".$start_date."','".$end_date."')");
                         }
                     return $query->result();
                break;

            }
         }
         catch (LConnectApplicationException $e)
         {
                 $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                 throw $e;
         }
    }
    public function common_reportsave($reportdata,$managerid,$updateid)
    {
        try{
              if($updateid=='0')
              {
                      $reportname=$reportdata['report_name'];
                      $query=$GLOBALS['$dbFramework']->query("SELECT COUNT(*) as cnt FROM reports
                      WHERE report_name REGEXP '^(".$reportname."\\\([0-9]+\\\)|".$reportname.")$' and manager_id='".$managerid."'");
                      $cnt=$query->result()[0]->cnt;
                      if($cnt>0)
                      {
                        $reportdata['report_name']=$reportname."(".$cnt++.")";
                      }
                      $query=$GLOBALS['$dbFramework']->insert('reports',$reportdata);
              }
              else
              {
                 $reportid=$reportdata['report_id'];
                 $report_name=$reportdata['report_name'];
                 $report_parent_id=$reportdata['report_parent_id'];
                 $filters=$reportdata['filters'];
                 $filters1=json_decode($filters);
                 $framestring='';
                 if(array_key_exists('userid',$filters1))
                 {
                    $framestring.="AND json_extract(filters,'$.userid')='".$filters1->userid."'";
                 }
                 if(array_key_exists('selecteddate',$filters1))
                 {
                    $framestring.="AND json_extract(filters,'$.selecteddate')='".$filters1->selecteddate."'";
                 }
                 if(array_key_exists('starttime',$filters1))
                 {
                    $framestring.="AND json_extract(filters,'$.starttime')='".$filters1->starttime."'";
                 }
                 if(array_key_exists('endtime',$filters1))
                 {
                    $framestring.="AND json_extract(filters,'$.endtime')='".$filters1->endtime."'";
                 }
                 if(array_key_exists('startdate',$filters1))
                 {
                    $framestring.="AND json_extract(filters,'$.startdate')='".$filters1->startdate."'";
                 }
                 if(array_key_exists('enddate',$filters1))
                 {
                    $framestring.="AND json_extract(filters,'$.enddate')='".$filters1->enddate."'";
                 }
                 if(array_key_exists('selectiontype',$filters1))
                 {
                    $framestring.="AND json_extract(filters,'$.selectiontype')='".$filters1->selectiontype."'";
                 }
                  if(array_key_exists('product',$filters1))
                 {
                    $framestring.="AND json_extract(filters,'$.product')='".$filters1->product."'";
                 }
                 $query=$GLOBALS['$dbFramework']->query("select count(*) as cnt from reports where report_id='".$reportid."'  AND report_parent_id='".$report_parent_id."' AND
                                                  manager_id='".$managerid."' AND report_name='".$report_name."'   ".$framestring."");
                      $cnt=$query->result()[0]->cnt;
                      if($cnt>0)
                      {
                          $query=0;
                      }
                      else
                      {
                          $query=$GLOBALS['$dbFramework']->update('reports',$reportdata,array('id'=>$updateid,'manager_id'=>$managerid));
                      }

            }
           	return $query;
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function get_savedreportdetails($loginid,$type,$subtype,$id)
    {
        try{
               $a=array();
               //echo"SELECT * FROM reports where id='".$id."' and manager_id='".$loginid."'";
               $query = $GLOBALS['$dbFramework']->query("SELECT * FROM reports where id='".$id."' and manager_id='".$loginid."'");
               $data=array();
               if($query->num_rows())
               {
                  foreach($query->result() as $rows1)
                  {
                       $jsondata=json_decode($rows1->filters);
                       $data=array(
                                 'startDate'=>$jsondata->startdate,
                                 'endDate'=>$jsondata->enddate,
								 'report_name'=>$rows1->report_name,
                                 'type'=>$type
                               );

                       switch($type)
                       {
                          case 'prod_performanceanalysis' :
                                $selectiontype='';
                                if(array_key_exists('selectiontype',$jsondata))
                                {
                                    $data['selectiontype']=$jsondata->selectiontype;
                                }
                                $data['product']=$jsondata->product;

                                $tabledetails=$this->generateReport($loginid,(object)$data);
                                $data['tabledetails']=$tabledetails;
                         break;
                         case 'opp_performanceanalysis' :
                                $selectiontype='';
                                if(array_key_exists('selectionId',$jsondata))
                                {
                                    $data['selectionId']=$jsondata->selectionId;
                                }
                                $data['opportunity']=$jsondata->opportunity;

                                $tabledetails=$this->generateReport($loginid,(object)$data);
                                $data['tabledetails']=$tabledetails;

                         break;
                       }
                  }
                  return $data;
               }
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
}

?>