<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('standard_ManAnalysisModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class standard_ManAnalysisModel extends CI_Model{

     public function __construct(){
        parent::__construct();
    }
     public function getChildrenForParent($user_id) {
		try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT user_id, reporting_to FROM user_details ");
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
    public function view_employees($userid)
    {
        try{
                $children ="'".$this->getChildrenForParent($userid)."'";
                $query=$GLOBALS['$dbFramework']->query(" SELECT a.user_id,a.user_name FROM  user_details a,user_module_plugin_mapping b
                                                        WHERE a.user_id in ('".$userid."',".$children.")  and a.user_id=b.user_id
                                                        and json_extract(module_id,'$.Manager')<>'0' ");
                return $query->result();
        }
        catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
        }
    }
    //public function generateReport($userid,$type,$subtype,$repid,$val1,$val2,$val3)
    public function generateReport($userid,$data)
    {
        try{
            if($data->type=='assign_analysis')
            {
                 $result=$this->get_assignmentReport($userid,$data);
            } //end of assignment analysis
            return $result;
         }
         catch (LConnectApplicationException $e)
         {
                 $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                 throw $e;
         }
    }
    public function get_assignmentReport($userid,$data)
    {
        $start_date=date("Y-m-d",strtotime($data->startDate));
        $end_date=date("Y-m-d",strtotime($data->endDate));
        $a=array();
        // echo"CALL  r_Managerassignment_Analysis('".$userid."','".$repid."','".$val3."','".$start_date."','".$end_date."')";
        if($data->selection_type=='Customer' || $data->selection_type=='Leads'){
            if($data->selection_type=='Customer'){
                $type='customer';
                $string="join customer_info e ON a.lead_cust_id=e.customer_id";
                $st_name="customer_name";
                $name='Customer';
            }
            else if($data->selection_type=='Leads'){
                $type='lead';
                $string="join lead_info e ON a.lead_cust_id=e.lead_id";
                $st_name="lead_name";
                $name='Lead';
            }

            $leadcustid_prev='';

            $query=$GLOBALS['$dbFramework']->query("SELECT DISTINCT lead_cust_id,".$st_name.",from_user_id,to_user_id,DATE_FORMAT(TIMESTAMP,'%d-%m-%Y %H:%i:%s') as TIMESTAMP,c.user_name AS assign_from,d.user_name AS Assigned_to
            FROM lead_cust_user_map a
            JOIN user_licence b ON a.from_user_id=b.user_id AND b.manager_module<>'0'
            join user_details c ON a.from_user_id=c.user_id
            join user_details d ON a.to_user_id=d.user_id ".$string."
            WHERE ACTION='assigned' AND TYPE='".$type."' AND module='sales' AND  from_user_id='".$data->user."' and
            DATE(TIMESTAMP) between ('".$start_date."') and ('".$end_date."') ORDER BY a.id");
            if($query->num_rows()>0){
            $res_arr=$query->result_array();
            $x=0;
            $leadcustid_prev='';
            $arr_assignto='';
            $comma='';
            for($i=0;$i<count($res_arr);$i++){
            $leadcustid=$res_arr[$i]['lead_cust_id'];
            $fromuserid=$res_arr[$i]['from_user_id'];
            $assign_to=$res_arr[$i]['Assigned_to'];
            if($leadcustid!=$leadcustid_prev && $leadcustid_prev!=''){
                  $x++;
                  $arr_assignto='';
                  $arr_assignto=$assign_to;
            }else{
                  if($leadcustid_prev!='')
                  {
                  $comma=' , ';
                  }
                  $arr_assignto.=$comma.$assign_to;
            }
            if($leadcustid!=$leadcustid_prev){
                  $query1=$GLOBALS['$dbFramework']->query("SELECT DATE_FORMAT(TIMESTAMP,'%d-%m-%Y %H:%i:%s') as TIMESTAMP FROM lead_cust_user_map a
                  WHERE (ACTION='accepted' OR ACTION='created') AND TYPE='".$type."' AND module='manager' and lead_cust_id='".$leadcustid."'
                  and to_user_id='".$fromuserid."' ORDER BY a.id");
                  if($query1->num_rows()>0){
                        $res_arr1=$query1->result_array();
                        for($ii=0;$ii<count($res_arr1);$ii++){
                            $duration1=$res_arr1[$ii]['TIMESTAMP'];
                  }
                  }else{
                      $query2=$GLOBALS['$dbFramework']->query("SELECT DATE_FORMAT(TIMESTAMP,'%d-%m-%Y %H:%i:%s') as TIMESTAMP FROM lead_cust_user_map a
                      WHERE (ACTION='created') AND TYPE='".$type."' AND (module='sales' or module='manager') and lead_cust_id='".$leadcustid."'
                      ORDER BY a.id");
                      $res_arr2=$query2->result_array();
                      for($iy=0;$iy<count($res_arr2);$iy++){
                          $duration1=$res_arr2[$iy]['TIMESTAMP'];
                      }
                  }
            }
            $a[$x][$name]=$res_arr[$i][$st_name];

            $a[$x]['Assigned_to']=$arr_assignto;
            $a[$x]['Generate_Accepted_Date']=$duration1;
            $a[$x]['Assigned_Date']=$res_arr[$i]['TIMESTAMP'];

            $leadcustid_prev=$leadcustid;
            }
            }
        }else{
            $query=$GLOBALS['$dbFramework']->query("SELECT DISTINCT a.opportunity_id,from_user_id,to_user_id,DATE_FORMAT(timestamp,'%d-%m-%Y %H:%i:%s') as TIMESTAMP,
            c.user_name AS assign_from,d.user_name AS Assigned_to,action,opportunity_name
            FROM oppo_user_map a
            JOIN user_licence b ON a.from_user_id=b.user_id AND b.manager_module<>'0'
            join user_details c ON a.from_user_id=c.user_id
            join user_details d ON a.to_user_id=d.user_id
            join opportunity_details e ON a.opportunity_id=e.opportunity_id
            WHERE (ACTION='stage assigned' or ACTION='ownership assigned') AND module='sales' AND  from_user_id='".$data->user."' and
            DATE(timestamp) between ('".$start_date."') and ('".$end_date."') ORDER BY a.id");
            if($query->num_rows()>0){
                  $res_arr=$query->result_array();
                  $x=0;
                  $opportunityid_prev='';
                  $action_prev='';
                  $arr_assignto='';
                  $comma='';
                  for($i=0;$i<count($res_arr);$i++){
                      //$leadcustid=$res_arr[$i]['lead_cust_id'];
                      $opportunityid=$res_arr[$i]['opportunity_id'];
                      $action=$res_arr[$i]['action'];
                      $fromuserid=$res_arr[$i]['from_user_id'];
                      $assign_to=$res_arr[$i]['Assigned_to'];

                      if((($opportunityid!=$opportunityid_prev) || ($opportunityid==$opportunityid_prev && $action!=$action_prev)) && $opportunityid_prev!='' && $action_prev!=''){
                          $x++;
                          $arr_assignto='';
                          $arr_assignto=$assign_to;
                      }else{
                          if($opportunityid_prev!='')
                          {
                          $comma=' , ';
                          }
                          $arr_assignto.=$comma.$assign_to;
                      }
                      if($opportunityid!=$opportunityid_prev && $action!=$action_prev){
                          $query1=$GLOBALS['$dbFramework']->query("SELECT DATE_FORMAT(TIMESTAMP,'%d-%m-%Y %H:%i:%s') as TIMESTAMP FROM oppo_user_map a
                          WHERE ACTION in ('stage accepted','ownership accepted','created') AND module='manager' and opportunity_id='".$opportunityid."'
                          and to_user_id='".$fromuserid."' ORDER BY a.id");
                          if($query1->num_rows()>0){
                              $res_arr1=$query1->result_array();
                              for($ii=0;$ii<count($res_arr1);$ii++){
                                  $duration1=$res_arr1[$ii]['TIMESTAMP'];
                              }
                          }else{
                              $query2=$GLOBALS['$dbFramework']->query("SELECT DATE_FORMAT(TIMESTAMP,'%d-%m-%Y %H:%i:%s') as TIMESTAMP FROM oppo_user_map a
                              WHERE (ACTION='created')  AND (module='sales' or module='manager')  and opportunity_id='".$opportunityid."'
                              ORDER BY a.id");
                              $res_arr2=$query2->result_array();
                              for($iy=0;$iy<count($res_arr2);$iy++){
                                  $duration1=$res_arr2[$iy]['TIMESTAMP'];
                              }
                          }
                      }
                      $a[$x]['Opportunity']=$res_arr[$i]['opportunity_name'];
                      $a[$x]['Assigned_to']=$arr_assignto;
                      $a[$x]['Action']=$action;
                      $a[$x]['Generate_Accepted_Date']=$duration1;
                      $a[$x]['Assigned_Date']=$res_arr[$i]['TIMESTAMP'];

                      $opportunityid_prev=$opportunityid;
                      $action_prev=$action;
                  }
            }
        }
        return $a;
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

    public function get_savedreportdetails($loginid,$type,$subtype,$id)
    {
        try{
               $data=array();
               $query = $GLOBALS['$dbFramework']->query("SELECT * FROM reports where id='".$id."' and manager_id='".$loginid."'");
               if($query->num_rows())
               {
                  foreach($query->result() as $rows1)
                  {
                       $jsondata=json_decode($rows1->filters);

                       switch($type)
                       {
                          Case 'assign_analysis' :
                           $data=array(
                                     'user'=>$jsondata->userid,
                                     'startDate'=>$jsondata->startdate,
                                     'endDate'=>$jsondata->enddate,
                                     'selection_type'=>$jsondata->selectiontype,
                                     'report_name'=>$rows1->report_name,
                                     'type'=>$type
                                 );

                            $tabledetails=$this->generateReport($loginid,(object)$data);
                            $data['tabledetails']=$tabledetails;
                           break;
                       }
                  }
              }
              return $data;

        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
}

?>