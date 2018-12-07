<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('standard_OppAnalysisModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class standard_OppAnalysisModel extends CI_Model{

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
     public function get_opportunity($userid)
    {
        try{
                $children ="'".$this->getChildrenForParent($userid)."'";
            /*echo "SELECT opportunity_id,opportunity_name FROM opportunity_details
                                                WHERE ((owner_id IN('".$userid."',".$children.")) or (manager_owner_id in('".$userid."',".$children.")))";*/
                $query=$GLOBALS['$dbFramework']->query("SELECT opportunity_id,opportunity_name FROM opportunity_details
                                                WHERE ((owner_id IN('".$userid."',".$children.")) or (manager_owner_id in('".$userid."',".$children."))) order by opportunity_name");
                return $query->result();
            }
            catch (LConnectApplicationException $e)
            {
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
            }
    }
    public function fetch_filter_data($filterarray){
            try{
                 $children ="'".$this->getChildrenForParent($filterarray['userid'])."'";
                 $userid=$filterarray['userid'];
                 $i=1;
                 $arr=array();
                 $join='';
                 $table='';
                 $flag=0;
                 $prod='';
                 $industry='';
                 $bus_location='';
                //print_r($filterarray);
                (isset($filterarray['product']) && $filterarray['product']!='All')?$prod=1:$prod=0;
                (isset($filterarray['industry']) && $filterarray['industry']!='All')?$industry=1:$industry=0;
                (isset($filterarray['bus_location']) && $filterarray['bus_location']!='All')?$bus_location=1:$bus_location=0;
                (isset($filterarray['sell_type']) && $filterarray['sell_type']!='All')?$sell_type=1:$sell_type=0;

                if($prod==1 && $filterarray['type_name']!='product'){
                    $table=',user_mappings b ';
                    $join=" and b.map_type='product' AND b.map_id='".$filterarray['product']."'";
                    $join=" and a.user_id=b.user_id";
                }if($industry==1 && $filterarray['type_name']!='industry'){
                    $table.=',user_mappings c';
                    $join.=" and c.map_type='clientele_industry' AND c.map_id='".$filterarray['industry']."'";
                    if(!$prod){
                      $join.=" and a.user_id=c.user_id";
                    }else{
                      $join.=" and b.user_id=c.user_id";
                    }
                }if($bus_location==1 && $filterarray['type_name']!='bus_location'){
                       $table.=',user_mappings d';
                       $join.=" and d.map_type='business_location' AND d.map_id='".$filterarray['bus_location']."'";
                    if(!$prod && $industry && $bus_location){
                       $join.=" and a.user_id=c.user_id and c.user_id=d.user_id";
                    }if($prod && !$industry && $bus_location){
                       $join.=" and a.user_id=b.user_id and b.user_id=d.user_id";
                    }if(!$prod && !$industry && $bus_location){
                       $join.=" and a.user_id=d.user_id";
                    }if($prod && $industry && !$bus_location){
                       $join.=" and a.user_id=b.user_id and b.user_id=c.user_id";
                    }
                }

                switch($filterarray['type_name'])
                {
                        case 'product' : $clause='product';
                        break;
                        case 'industry' : $clause='clientele_industry';

                        break;
                        case 'bus_location' : $clause='business_location';

                        break;
                        case 'sell_type' : $clause='sell_type';
                        $flag=1;
                        break;
                     }
                     $i++;
                     if($flag==1){
                        // echo"SELECT distinct a.map_id AS dataid,a.map_id AS dataname FROM user_mappings a ".$table." WHERE
                          // a.user_id  in ('".$userid."',".$children.") and a.map_type='". $clause. "' ".$join." order by a.map_id";
                         $query=$GLOBALS['$dbFramework']->query("SELECT distinct a.map_id AS dataid,a.map_id AS dataname FROM user_mappings a ".$table." WHERE
                           a.user_id  in ('".$userid."',".$children.") and a.map_type='". $clause. "' ".$join." order by a.map_id");
                         $arr[$clause]=$query->result();
                     }else{
                        // echo"SELECT distinct a.map_id AS dataid,hvalue2 AS dataname FROM user_mappings a,hierarchy h ".$table." WHERE  h.hkey2=a.map_id
                        // AND a.user_id  in ('".$userid."',".$children.")and a.map_type='". $clause. "' ".$join." order by hvalue2";
                         $query=$GLOBALS['$dbFramework']->query("SELECT distinct a.map_id AS dataid,hvalue2 AS dataname FROM user_mappings a,hierarchy h ".$table." WHERE  h.hkey2=a.map_id
                         AND a.user_id  in ('".$userid."',".$children.")and a.map_type='". $clause. "' ".$join." order by hvalue2");
                         $arr[$clause]=$query->result();
                     }

                 return  $arr;

            }
            catch (LConnectApplicationException $e)
            {
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
            }
    }
    public function generateReport($userid,$data)
    {
        try{
            switch($data->type)
            {
                Case 'opp_timeanalysis':

                     if($data->startDate!='NA')
                     {
                         $data->startDate=date("Y-m-d",strtotime($data->startDate));
                     }
                     if($data->endDate!='NA')
                     {
                         $data->endDate=date("Y-m-d",strtotime($data->endDate));
                     }
                     if($data->subtype=='Summary')
                          $query=$GLOBALS['$dbFramework']->query("CALL  r_OppoTime_Summary('".$userid."','".$data->opportunity."','".$data->selectiontype."','".$data->startDate."','".$data->endDate."')");
                     else
                          $query=$GLOBALS['$dbFramework']->query("CALL  r_Oppo_time_Analysis('".$userid."','".$data->opportunity."','".$data->selectiontype."','".$data->subtype_Id."','".$data->startDate."','".$data->endDate."')");
                      return $query->result();
                break;
                Case 'opp_velocity_analysis':
                     $data->startDate=date("Y-m-d",strtotime($data->startDate));
                     $data->endDate=date("Y-m-d",strtotime($data->endDate));
                    // echo"CALL  r_Opportunity_Velocity('".$userid."','".$data->product."','".$data->industry."','".$data->bus_location."','".$data->startDate."','".$data->endDate."')";
                     $query=$GLOBALS['$dbFramework']->query("CALL  r_Opportunity_Velocity('".$userid."','".$data->product."','".$data->industry."','".$data->bus_location."','".$data->startDate."','".$data->endDate."')");
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
                 if(array_key_exists('opportunity',$filters1))
                 {
                    $framestring.="AND json_extract(filters,'$.opportunity')='".$filters1->opportunity."'";
                 }
                 if(array_key_exists('selectiontypeid',$filters1))
                 {
                    $framestring.="AND json_extract(filters,'$.selectiontypeid')='".$filters1->selectiontypeid."'";
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

    public function get_savedreportdetails($loginid,$type,$id)
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
                          case 'opp_timeanalysis' :
                                $data=array(
                                   'startDate'=>$jsondata->startdate,
                                   'endDate'=>$jsondata->enddate,
                                   'opportunity'=>$jsondata->opportunity,
                                   'selectiontype'=>$jsondata->selectiontype,
                                   'subtype'=>'Summary',
                                   'type'=>'opp_timeanalysis'
                                );
                                if(array_key_exists('selectiontypeid',$jsondata))
                                {
                                    $data['subtype']='Details';
                                    $data['subtype_Id']=$jsondata->selectiontypeid;

                                    if($jsondata->selectiontype=='Resource')
                                        $query1 = $GLOBALS['$dbFramework']->query("SELECT user_name as name FROM user_details where user_id='".$jsondata->selectiontypeid."'");
                                    else
                                        $query1 = $GLOBALS['$dbFramework']->query("SELECT lookup_value as name FROM lookup where lookup_id='".$jsondata->selectiontypeid."'");

                                    foreach($query1->result() as $rows2)
                                    {
                                       $name=$rows2->name;
                                    }
                                }
                                $tabledetails=$this->generateReport($loginid,(object)$data);
                       }
                  }
                  if($type=='opp_timeanalysis')
                  {
                          $arr=array
                          (
                                 'startdate'=>$jsondata->startdate,
                                 'enddate'=>$jsondata->enddate,
                                 'opportunity'=>$jsondata->opportunity,
                                 'selectiontype'=>$jsondata->selectiontype,
                                 'tabledetails'=>$tabledetails,
								 'report_name'=>$rows1->report_name

                          );
                          if(array_key_exists('selectiontypeid',$jsondata))
                          {
                                $arr['subtype_Id']=$jsondata->selectiontypeid;
                                $arr['name']=$name;
                          }
                           return $arr;
                   }
               }
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
}

?>