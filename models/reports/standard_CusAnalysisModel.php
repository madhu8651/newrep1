<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('standard_CusAnalysisModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class standard_CusAnalysisModel extends CI_Model{

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
    public function get_cust($userid)
    {
        try{
                $children ="'".$this->getChildrenForParent($userid)."'";
                $query=$GLOBALS['$dbFramework']->query("select cu.customer_name,cu.customer_id as customer from customer_info cu join rep_log rl on rl.leadid=cu.customer_id
                where  rl.type='customer' and rl.rep_id in ('".$userid."')  group by lead_id");
                return $query->result();
            }
            catch (LConnectApplicationException $e)
            {
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
            }
    }
    public function generateReport($userid,$type,$subtype,$val1,$val2,$val3,$val4)
    {
        try{
            switch($type)
            {
                Case 'Cus_retentioncostanalysis':
                     //echo"CALL  r_Customer_Auisition_Cost_Summary('".$userid."','".$val1."','".$val4."')";
                     if($subtype=='Summary')
                       $query=$GLOBALS['$dbFramework']->query("CALL  r_Customer_Retention_Cost_Summary('".$userid."','".$val1."','".$val2."')");
                     else
                       $query=$GLOBALS['$dbFramework']->query("CALL  r_Customer_Retention_Cost_Analysis('".$userid."','".$val1."','".$val3."','".$val2."')");


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
                 if(array_key_exists('customerid',$filters1))
                 {
                    $framestring.="AND json_extract(filters,'$.customerid')='".$filters1->customerid."'";
                 }
                 if(array_key_exists('activityid',$filters1))
                 {
                    $framestring.="AND json_extract(filters,'$.activityid')='".$filters1->activityid."'";
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
               $query = $GLOBALS['$dbFramework']->query("SELECT * FROM reports where id='".$id."' and manager_id='".$loginid."'");
               if($query->num_rows())
               {
                  foreach($query->result() as $rows1)
                  {
                       $jsondata=json_decode($rows1->filters);
                       switch($type)
                       {
                          case 'Cus_retentioncostanalysis' :
                                $activityid='NA';
                                if(array_key_exists('activityid',$jsondata))
                                {
                                    $subtype='Details';
                                    $activityid=$jsondata->activityid;
                                }
                                $tabledetails=$this->generateReport($loginid,$type,$subtype,$jsondata->userid,$jsondata->customerid,$activityid,'NA');
                         break;

                       }
                  }
                  if($type=='Cus_retentioncostanalysis')
                  {
                              return array
                               (
                                 'user'=>$jsondata->userid,
                                 'customer'=>$jsondata->customerid,
                                 'tabledetails'=>$tabledetails,
								 'report_name'=>$rows1->report_name
                               );
                   }

               }
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
}

?>