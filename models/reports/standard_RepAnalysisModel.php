<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('standard_RepAnalysisModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class standard_RepAnalysisModel extends CI_Model{

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

	private function fetchChildNodes($givenID, & $childNodes, $allParentNodes){
		foreach ($allParentNodes as $user_id => $reporting_to) {
			if ($reporting_to == $givenID)  {
				array_push($childNodes, $user_id);
				$this->fetchChildNodes($user_id, $childNodes, $allParentNodes);
			}
		}
	}
    public function getmodule_id($modulename)
    {
        try{
                $query = $GLOBALS['$dbFramework']->query("SELECT module_id FROM module_master where module_name='$modulename'");
                $moduleid=$query->result()[0]->module_id;
                return $moduleid ;
		        //return  $query->result();
            }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
    }
    public function view_employees($userid)
    {
        try{

                $children ="'".$this->getChildrenForParent($userid)."'";
                $query=$GLOBALS['$dbFramework']->query("SELECT a.user_id,a.user_name FROM  user_details a
                                                        WHERE a.user_id in ('".$userid."',".$children.") order by a.user_name");
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
                 switch($filterarray['type_name'])
                 {
                    case 'team' :  $query=$GLOBALS['$dbFramework']->query("SELECT distinct teamid as dataid,teamname as dataname
                                          FROM teams a,user_details u WHERE a.teamid=u.team_id AND u.user_id in ('".$userid."',".$children.") order by teamname");
                                   return $query->result();
                    break;
                    case 'off_location' :   $clause='';
                                        if($filterarray['team']!='All'){
                                           $clause="and a.team_id='".$filterarray['team']."'";
                                        }
                                      $query=$GLOBALS['$dbFramework']->query("SELECT DISTINCT product_id as dataid,b.hvalue1 as dataname
                                                                       FROM product_currency_mapping a,hierarchy b,user_details c
                                                                       WHERE a.remarks='ofloc' AND hkey2=product_id and a.team_id=c.team_id
                                                                       AND c.user_id in ('".$userid."',".$children.")".$clause. " order by dataname");
                                      return $query->result();
                    break;
                    case 'resource_name' :    $clause='';
                                        if($filterarray['team']!='All'){
                                           $clause="and b.team_id='".$filterarray['team']."'";
                                        }
                                        if($filterarray['off_location']!='All'){
                                           $clause.="and map_id='".$filterarray['off_location']."'";
                                        }
                                      $query=$GLOBALS['$dbFramework']->query("SELECT distinct b.user_id as dataid,user_name as dataname FROM user_mappings a,user_details b
                                                                       WHERE map_type='office_location' and b.user_id in ('".$userid."',".$children.") ".$clause." order by dataname");
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
    public function parse_cron($crontab,$day)
    {
           // echo $crontab;
            $cron = explode(" ",$crontab);
            $seconds = str_pad($cron[0], 2, '0', STR_PAD_LEFT);
            $minutes = explode(",",$cron[1]);
            $hours = explode(",",$cron[2]);

            $sminutes = str_pad($minutes[0], 2, '0', STR_PAD_LEFT);
            $eminutes = str_pad($minutes[1], 2, '0', STR_PAD_LEFT);

            $shours = str_pad($hours[0], 2, '0', STR_PAD_LEFT);
            $ehours = str_pad($hours[1], 2, '0', STR_PAD_LEFT);
            $start_time='';
            $end_time='';
            $stdt='';
            //$work = array();
            // echo $cron[5].'------';
            // echo $day.'------';
            switch($cron[5])
            {
                    case '1': if($day=="Sun")
                              {
                                 $start_time = $shours.":".$sminutes.":00";
                                 $end_time = $ehours.":".$eminutes.":00";
                                 $stdt=$start_time."--".$end_time;
                              }
                              break;
                    case '2': if($day=="Mon")
                              {
                                 $start_time = $shours.":".$sminutes.":00";
                                 $end_time = $ehours.":".$eminutes.":00";
                                 $stdt=$start_time."--".$end_time;
                              }
                              break;
                    case '3': if($day=="Tue")
                              {
                                 $start_time = $shours.":".$sminutes.":00";
                                 $end_time = $ehours.":".$eminutes.":00";
                                 $stdt=$start_time."--".$end_time;
                              }
                              break;
                    case '4': if($day=="Wed")
                              {
                                 $start_time = $shours.":".$sminutes.":00";
                                 $end_time = $ehours.":".$eminutes.":00";
                                 $stdt=$start_time."--".$end_time;
                              }
                              break;
                    case '5': if($day=="Thu")
                              {
                                 $start_time = $shours.":".$sminutes.":00";
                                 $end_time = $ehours.":".$eminutes.":00";
                                 $stdt=$start_time."--".$end_time;
                              }
                              break;
                    case '6':if($day=="Fri")
                              {
                                 $start_time = $shours.":".$sminutes.":00";
                                 $end_time = $ehours.":".$eminutes.":00";
                                 $stdt=$start_time."--".$end_time;
                              }
                              break;
                    case '7': if($day=="Sat")
                              {
                                 $start_time = $shours.":".$sminutes.":00";
                                 $end_time = $ehours.":".$eminutes.":00";
                                 $stdt=$start_time."--".$end_time;
                              }
                              break;
            }
            return $stdt;
            //echo $start_time."-->".$actual_sttime."-->".$end_time."-->".$actual_endtime;

    }
    public function fetchworkinghrs($user,$selecteddate)
    {
               $day = date('D', strtotime($selecteddate));
              // echo "$day";
               $query=$GLOBALS['$dbFramework']->query("SELECT expression FROM user_attributes where user_id='".$user."'");
               $query->result();
               $work=array();
               if($query->num_rows()>0)
               {
                      foreach ($query->result() as $row)
                      {
                           $workingArr = $row->expression;
                      }
                      $workingArr = json_decode($workingArr);

                      if(count($workingArr)!=0)
                      {
                          for($i=0;$i<count($workingArr);$i++)
                          {
                               $work = $this->parse_cron($workingArr[$i],$day);
                               if($work!='')
                               {
                                  break;
                               }
                          }
                          if($work=='')
                          {
                            $work='TimeNotDefined';
                          }
                          return $work;
                      }
               }
    }
    public function fetch_time($userid,$user,$selecteddate)
    {
        try{
               $g='';

               $selecteddate=date("Y-m-d",strtotime($selecteddate));
             //  echo"CALL  r_workpatternanalysis('".$user."','".$selecteddate."')";
               $result_set=$this->db->query("CALL  r_workpatternanalysis('".$user."','".$selecteddate."')");
               $query_actual=$GLOBALS['$dbFramework']->query("SELECT time(MIN(starttime)) as Start_Time,time(MAX(endtime)) as End_Time FROM temp_wpa ");
               $actual_sttime=$query_actual->result()[0]->Start_Time;
               $actual_endtime=$query_actual->result()[0]->End_Time;
               // echo $actual_sttime."--".$actual_endtime;
               $fetch_workinghrs=$this->fetchworkinghrs($user,$selecteddate);
               $grapharray=array();
               if($fetch_workinghrs!='TimeNotDefined' && $actual_sttime!='' && $actual_endtime!='')
               {
                     $g=explode("--",$fetch_workinghrs);
                     $start_time = $g[0];
                     $end_time = $g[1];
                      if(strtotime($start_time) > strtotime($actual_sttime) && $actual_sttime!='')
                      {
                         $start_time=$actual_sttime;
                      }
                      if(strtotime($end_time)<strtotime($actual_endtime) && $actual_endtime!='')
                      {
                         $end_time=$actual_endtime;
                      }

                      $a=array(
                            'start_time' => $start_time,
                            'end_time' => $end_time
                          );
                      array_push($grapharray,$a);
               }
               else if($fetch_workinghrs!='TimeNotDefined' && $actual_sttime=='' && $actual_endtime=='')
               {
                     $g=explode("--",$fetch_workinghrs);
                     $start_time = $g[0];
                     $end_time = $g[1];
                     $a=array(
                            'start_time' => $start_time,
                            'end_time' => $end_time
                          );
                      array_push($grapharray,$a);
               }
               else if($fetch_workinghrs=='TimeNotDefined' && $actual_sttime!='' && $actual_endtime!='')
               {
                     $start_time=$actual_sttime;
                      $end_time=$actual_endtime;
                     $a=array(
                            'start_time' => $start_time,
                            'end_time' => $end_time
                          );
                      array_push($grapharray,$a);
               }
               else
               {
                    $a=array(
                            'start_time' => '',
                            'end_time' => ''
                          );
                      array_push($grapharray,$a);
               }

                return $grapharray;
            }catch (LConnectApplicationException $e)
            {
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
            }
    }
    public function generateReport($userid,$type,$subtype,$repid,$val1,$val2,$val3)
    {
         try
         {
            switch($type)
            {
               Case 'Daily_WPA' :
                     $start_time  = date("H:i:s", strtotime($val2));
                     $end_time  = date("H:i:s", strtotime($val3));
                     $val1=date("Y-m-d",strtotime($val1));
                    // echo"CALL  r_workpatternanalysis('".$repid."','".$val1."')";
                     $result_set=$this->db->query("CALL  r_workpatternanalysis('".$repid."','".$val1."')");
                     // echo"CALL  r_workpatternanalysis('".$repid."','".$val1."')";
                     $query=$GLOBALS['$dbFramework']->query("SELECT lookup_value,lookup_id,note,actualstarttime,actualendtime,starttime AS Start_Date,endtime AS End_Date,
                                                             logname,rating,path,prospecttype,opportunityname,leadname,custname,contactname,contactphone
                                                             FROM temp_wpa WHERE (TIME(starttime) BETWEEN '".$start_time."' AND '".$end_time."'
                                                             AND TIME(endtime) BETWEEN '".$start_time."' AND '".$end_time."') ORDER BY starttime ");


                    $arr=$query->result_array();
                    $grapharray = array();

                     for($i=0;$i<count($arr);$i++)
                     {
                         $temp = array();
                         $lookup_value=$arr[$i]['lookup_value'];
                         $starttime=$arr[$i]['Start_Date'];
                         $actualstartdate=$arr[$i]['actualstarttime'];
                         $note=$arr[$i]['note'];
                         $log_name=$arr[$i]['logname'];
                         $rating=$arr[$i]['rating'];
                         $path=$arr[$i]['path'];
                         $endtime=$arr[$i]['End_Date'];
                         $actualendtime=$arr[$i]['actualendtime'];
                         $prospecttype=$arr[$i]['prospecttype'];
                         if($prospecttype=='lead')
                         {
                           $prospect_name=$arr[$i]['leadname'];
                         }
                         else if($prospecttype=='opportunity')
                         {
                            $prospect_name=$arr[$i]['opportunityname'];
                         }
                         else
                         {
                            $prospect_name=$arr[$i]['custname'];
                         }
                         if($arr[$i]['lookup_id']=='CALL594ce66d07b45' || $arr[$i]['lookup_id']=='CALL594ce66d07b46' || $arr[$i]['lookup_id']=='ME594ce66d07b9fd4')
                         {
                             $lastkey='Recording';
                         }else{
                             $lastkey='Content';
                         }


                         $contactname=$arr[$i]['contactname'];
                         $contactphone=$arr[$i]['contactphone'];
                         $temp= array(
                                    'lookup_value'=>$lookup_value,
                                    'log_name'=>$log_name,
                                    'Start_Date'=> $starttime,
                                    'End_Date'=> $endtime,
                                    'note'=>$note,
                                    'actual_startdate'=>$actualstartdate,
                                    'actual_enddate'=>$actualendtime,
                                    'rating'=>$rating,
									'path'=>$path,
                                    'Prospect_Type'=>$prospecttype,
                                    'Prospect_Name'=>$prospect_name,
                                    'Contact_Name'=>$contactname,
                                    'lastkey'=>$lastkey,
                                    'Contact_Phone'=>$contactphone,

                                );

                          array_push($grapharray,$temp);
                     }
                     return $grapharray;
                     break;
                Case 'Activity_Analysis' :
                      $start_date=date("Y-m-d",strtotime($val1));
                      $end_date=date("Y-m-d",strtotime($val2));
                      if($subtype == 'Completed'){
                            $query=$GLOBALS['$dbFramework']->query("CALL  r_Closed_Activity_Analysis('".$userid."','".$repid."','".$val3."','".$start_date."','".$end_date."')");
                            return $query->result();
                      }else{
                            $query=$GLOBALS['$dbFramework']->query("CALL  r_Open_Activity_Analysis('".$userid."','".$repid."','".$val3."','".$start_date."','".$end_date."')");
                            return $query->result();
                      }
                      break;
                Case 'Time_distribution_analysis' :
                      if($subtype=='Detail')
                      {
                            $start_date=date("Y-m-d",strtotime($val1));
                            $query=$GLOBALS['$dbFramework']->query("CALL  r_Timedistribution_Analysis('".$userid."','".$repid."','".$val3."','".$start_date."')");
                            return $query->result();
                      }
                      else if($subtype=='Summary')
                      {
                            $start_date=date("Y-m-d",strtotime($val1));
                            //echo "$start_date";
                            $timearray=$this->fetchworkinghrs($repid,$start_date);
                            if($timearray!='TimeNotDefined'){
                                //echo"$timearray";
                                $g=explode("--",$timearray);
                                $start_time = strtotime("1980-01-01 $g[0]");
                                $to_time = strtotime("1980-01-01 $g[1]");
                                $totalworkinghrs1=$totalworkinghrs=date("H:i:s", strtotime("1980-01-01 00:00:00") + ($to_time - $start_time));
                            }else{
                                $totalworkinghrs1=$totalworkinghrs="00:00:00";
                            }

                            $ar=array();
                            $query=$GLOBALS['$dbFramework']->query("CALL  r_Timedistribution_chart_Summary('".$userid."','".$repid."','".$start_date."')");
                            $bobble=$query->result_array();
                            $timedurationval="";
                            $found=0;
                            foreach($query->result_array() as $value)
                            {
                               $found=1;
                               $timeval=$value['Activity_TimeDuration'];
                               $timedurationval=($timedurationval+strtotime($timeval))-strtotime('00:00:00');
                            }
                            if($found==1)
                            {
                               $timeduration=date("H:i:s", strtotime("1980-01-01 00:00:00") + ($timedurationval));
                                $totalworkinghrs=strtotime("1980-01-01 $totalworkinghrs");
                                $timeduration=strtotime("1980-01-01 $timeduration");
                                if ($timeduration < $totalworkinghrs) {
                                      $idletime=date("H:i:s", strtotime("1980-01-01 00:00:00") + ($totalworkinghrs - $timeduration));
                                      $new_array = array('Activity_Name'=>'Idle Time','Activity_TimeDuration'=>$idletime);
                                  }
                                  else
                                  {
                                    $idletime=date("H:i:s", strtotime("1980-01-01 00:00:00") + ($timeduration - $totalworkinghrs));
                                    $new_array = array('Activity_Name'=>'Over Time','Activity_TimeDuration'=>$idletime);
                                  }
                                array_push($bobble,$new_array);
                                return array(
                                        'totalworkinghrs'=>$totalworkinghrs1,
                                        'arraydata'=>$bobble
                                );
                            }
                            else
                            {
                                 return (object)[];
                            }
                      }
                      break;
                Case 'Punctuality_Score' :
                            $start_date=date("Y-m-d",strtotime($val1));
                            $end_date=date("Y-m-d",strtotime($val2));
                            $query=$GLOBALS['$dbFramework']->query("CALL  r_Punctuality_Score_Summary('".$userid."','".$repid['location']."','".$repid['team']."','".$repid['resource']."'
                                                                    ,'".$start_date."','".$end_date."')");
                            return $query->result();
                      break;
                Case 'Productivity_Score' :
                      //generateReport($this->session->userdata('uid'),$type,$subtype,$filterarray $repid,$startdate $val1,$enddate $val2,'');
                      if($subtype=='Summary')
                      {
                            $start_date=date("Y-m-d",strtotime($val1));
                            $end_date=date("Y-m-d",strtotime($val2));
                          //  $query=$GLOBALS['$dbFramework']->query("CALL  r_Timedistribution_Analysis('".$userid."','".$repid."','".$val3."','".$start_date."')");
                          //  return $query->result();
                      }
                      break;
                Case 'Rep_Cost_Analysis' :
                            $start_date=date("Y-m-d",strtotime($val1));
                            $end_date=date("Y-m-d",strtotime($val2));
                            $query=$GLOBALS['$dbFramework']->query("CALL  r_ExecutiveCost_Analysis('".$userid."','".$repid['location']."','".$repid['team']."','".$repid['resource']."'
                                                                    ,'".$start_date."','".$end_date."')");
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
              if($updateid=='')
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
                 $framestring.=$this->appendFilterdata($filters1,'team');
                 $framestring.=$this->appendFilterdata($filters1,'location');
                 $framestring.=$this->appendFilterdata($filters1,'resource');

               /* echo"select count(*) as cnt from reports where report_id='".$reportid."'  AND report_parent_id='".$report_parent_id."' AND
                                                  manager_id='".$managerid."' ".$framestring."";*/
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
    public function appendFilterdata($filters1,$key){

          $str="AND json_extract(filters,'$.".$key."')='".$filters1->$key."'";
          return $str;
    }
    public function getreportlist()
    {
       /* $query=$GLOBALS['$dbFramework']->query("SELECT hkey2 as id,b.hierarchy_id as hid,b.hvalue2 as name,b.hkey1 as parent,b.hvalue1 as parent_name,remarks
                                                FROM hierarchy_class a,hierarchy b WHERE a.Hierarchy_Class_Id=b.hierarchy_class_id
                                                AND Hierarchy_Class_Name='Reports' AND  hkey2!='0'");*/
         $query=$GLOBALS['$dbFramework']->query("SELECT child_id as id,id as hid,child_name as name,parent_id as parent,parent_name as parent_name,remarks
                                                ,version_type as versiontype FROM reports_hierarchy");
        return $query->result();
    }
    public function getsavedreport($managerid,$val,$parentid)
    {
        try{
              $i=0;

              if($val=='list')
              {
                  $query_in = $GLOBALS['$dbFramework']->query("SELECT distinct report_id  from reports where  manager_id='".$managerid."'");
                  $reparray=array();

                  foreach ($query_in->result() as $row)
                  {
                    $this->find_parent($row->report_id,$reparray,'');
                  }
                  $string_version = "('".implode("','", $reparray)."')";

                 /* $query=$GLOBALS['$dbFramework']->query("SELECT hkey2 as id,b.hierarchy_id as hid,b.hvalue2 as name,b.hkey1 as parent,b.hvalue1 as parent_name,remarks
                                                FROM hierarchy_class a,hierarchy b WHERE a.Hierarchy_Class_Id=b.hierarchy_class_id
                                                AND Hierarchy_Class_Name='Reports' AND  hkey2!='0' and hkey2 in $string_version order by b.id ");*/
                   $query=$GLOBALS['$dbFramework']->query("SELECT child_id as id,id as hid,child_name as name,parent_id as parent,parent_name as parent_name,remarks,
                                                version_type as versiontype
                                                FROM reports_hierarchy WHERE  child_id!='0' and child_id in $string_version order by id ");

                   return $query->result();
              }
              else
              {
                 /* $query1=$GLOBALS['$dbFramework']->query("SELECT report_name as reportname,a.id as reportid,report_id as reportPageId,report_parent_id as id,
                                                    b.hvalue2 FROM reports a,hierarchy b  WHERE report_id='".$parentid."' and  manager_id='".$managerid."'
                                                    and b.hkey2=a.report_id");*/
                 $query1=$GLOBALS['$dbFramework']->query("SELECT report_name as reportname,a.id as reportid,report_id as reportPageId,report_parent_id as id,
                                                    b.child_name as hvalue2 FROM reports a,reports_hierarchy b  WHERE report_id='".$parentid."' and  manager_id='".$managerid."'
                                                    and b.child_id=a.report_id");
                  return $query1->result();

              }
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }
    public function get_heirarchy($loginid,$id)
    {
         try{
               $reparray=array();
              $this->find_parent($id,$reparray,'getname');
              return $reparray ;
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function find_parent($id,&$link,$method)
    {
               /*$query = $GLOBALS['$dbFramework']->query("SELECT * FROM hierarchy where hkey2='".$id."'");   */
                $query = $GLOBALS['$dbFramework']->query("SELECT * FROM reports_hierarchy where child_id='".$id."'");
               if($method=='')
               {
                   if($query->num_rows())
                   {
                      foreach($query->result() as $rows1)
                      {
                        if (!in_array($rows1->child_id,$link)) {
                            array_push($link,$rows1->child_id);
                        }
                         $this->find_parent($rows1->parent_id,$link,'');
                      }
                   }
               }
               else
               {
                   if($query->num_rows())
                   {
                      foreach($query->result() as $rows1)
                      {
                            array_push($link,$rows1->child_name);
                            $this->find_parent($rows1->parent_id,$link,'getname');
                      }
                   }
               }
    }
    public function get_savedreportdetails($loginid,$type,$subtype,$id)
    {
        try{
               $a=array();
              // echo"SELECT * FROM reports where id='".$id."' and manager_id='".$loginid."'";
               $query = $GLOBALS['$dbFramework']->query("SELECT * FROM reports where id='".$id."' and manager_id='".$loginid."'");
               if($query->num_rows())
               {
                  foreach($query->result() as $rows1)
                  {
                       $jsondata=json_decode($rows1->filters);
                       switch($type)
                       {
                          Case 'Daily_WPA' :
                                $tabledetails=$this->generateReport($loginid,$type,$subtype,$jsondata->userid,$jsondata->selecteddate,$jsondata->starttime,$jsondata->endtime);
                                break;
                          Case 'Activity_Analysis' :
                                $que= $GLOBALS['$dbFramework']->query("SELECT json_extract(filters,'$.status') AS status1 FROM reports where id='".$id."' and manager_id='".$loginid."'");
                                $result = $que->result();
                                $value = $result[0]->status1;
                                $value1 = str_replace('"', '', $value);
                                $tabledetails=$this->generateReport($loginid,$type,$subtype,$jsondata->userid,$jsondata->startdate,$jsondata->enddate,$value1);
                                break;
                          Case 'Time_distribution_analysis' :
                                if($subtype=='Detail')
                                    $tabledetails=$this->generateReport($loginid,$type,$subtype,$jsondata->userid,$jsondata->selecteddate,'',$jsondata->selectiontype);
                                else
                                    $tabledetails=$this->generateReport($loginid,$type,$subtype,$jsondata->userid,$jsondata->selecteddate,'','');
                                break;
                          Case 'Punctuality_Score' :
                          Case 'Productivity_Score' :
                          Case 'Rep_Cost_Analysis' :
                                    $filterarray=array(
                                      'team'=>$jsondata->team,
                                      'location'=>$jsondata->location,
                                      'resource'=>$jsondata->resource
                                    );
                                    $tabledetails=$this->generateReport($this->session->userdata('uid'),$type,$subtype,$filterarray,$jsondata->startdate,$jsondata->enddate,'');
                                break;

                       }
                  }
                   $savedReportArray=array(
                                  'userid'=>$jsondata->userid,
                                  'tabledetails'=>$tabledetails,
                                  'report_name'=>$rows1->report_name
                   );
                   if($type=='Daily_WPA')
                   {
                          $savedReportArray['selecteddate']=$jsondata->selecteddate;
                          $savedReportArray['starttime']=$jsondata->starttime;
                          $savedReportArray['endtime']=$jsondata->endtime;
                   }else if($type=='Activity_Analysis')
                   {
                          $savedReportArray['startdate']=$jsondata->startdate;
                          $savedReportArray['enddate']=$jsondata->enddate;
                          $savedReportArray['status']=$value1;
                   }else if($type=='Time_distribution_analysis')
                   {
                         $savedReportArray['startdate']=$jsondata->selecteddate;
                         if($subtype=='Detail'){
                              $savedReportArray['selection_type']=$jsondata->selectiontype;
                         }
                   }else if($type=='Punctuality_Score' || $type=='Rep_Cost_Analysis' || $type=='Productivity_Score'){
                         $savedReportArray['team']=$jsondata->team;
                         $savedReportArray['location']=$jsondata->location;
                         $savedReportArray['resource']=$jsondata->resource;
                         $savedReportArray['startdate']=$jsondata->startdate;
                         $savedReportArray['enddate']=$jsondata->enddate;
                   }
                   return $savedReportArray;
            }
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
}

?>