<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_dashboardsettingModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class manager_dashboardsettingModel extends CI_Model{

     public function __construct(){
        parent::__construct();
    }
    public function fetch_reportnames()
    {
        try{

                $query=$GLOBALS['$dbFramework']->query("SELECT lookup_id as dash_repo_id,lookup_value as dash_repo_name FROM lookup where lookup_name='dashboard'");
                return $query->result();
            }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }

    }
    public function fetch_reportingto($userid,$param)
    {
        try{
                $children = "'".$userid."','";
			    $children .= $this->getChildrenForParent($userid)."'";
              if($param=='Individual')
              {

                  $query=$GLOBALS['$dbFramework']->query("SELECT a.user_id,a.user_name FROM  user_details a,user_module_plugin_mapping b
                                                        WHERE a.user_id = b.user_id
                                                         AND json_extract(b.module_id, '$.custo_assign')='0'
                                                         AND a.user_id in (".$children.") order by user_name");

              }
              else if($param=='Team')
              {

                $query=$GLOBALS['$dbFramework']->query("SELECT a.team_id,teamname FROM  user_details a,user_module_plugin_mapping b,teams c
                                                        WHERE a.user_id = b.user_id AND a.team_id=c.teamid
                                                        AND json_extract(b.module_id, '$.custo_assign')='0'
                                                        AND a.user_id in (".$children.")  GROUP BY teamid");
              }
              else
              {

                  $query=$GLOBALS['$dbFramework']->query("SELECT map_id as business_location_id,(SELECT hvalue2 FROM hierarchy
                                                          WHERE hkey2=map_id) AS bus_locname
                                                           FROM  user_details a,user_module_plugin_mapping b,user_mappings c
                                                           WHERE a.user_id = b.user_id AND a.user_id=c.user_id AND map_type='business_location'
                                                           AND json_extract(b.module_id, '$.custo_assign')='0'
                                                           AND a.user_id in (".$children.")
                                                           GROUP BY map_id");
              }

                return $query->result();
            }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
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
    public function insert_data($data)
    {
        try{

                 $query = $GLOBALS['$dbFramework']->query("SELECT * FROM dash_repo_settings WHERE user_id='".$data['user_id']."' AND dash_repo_id='".$data['dash_repo_id']."'
                                                           AND chart_type='".$data['chart_type']."' AND display_area='".$data['display_area']."'
                                                           AND select_param='".$data['select_param']."' AND select_sub_param='".$data['select_sub_param']."' AND frequecy='".$data['frequecy']."'
                                                           AND DATA='".$data['DATA']."'
                                                           AND target='".$data['target']."' AND criteria='".$data['criteria']."' AND flag_value='".$data['flag_value']."'
                                                           AND module_id='".$data['module_id']."' AND start_date='".$data['start_date']."'
                                                           AND end_date='".$data['end_date']."'");
                 $arr=$query->result_array();

                 if(count($arr)>0)
                 {
                     return false;
                 }
                 else
                 {
                        $var = $GLOBALS['$dbFramework']->insert('dash_repo_settings',$data);
		                return true;
                 }
            }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
    }
    public function update_data($data,$editid)
    {
        try{

                 $query = $GLOBALS['$dbFramework']->query("SELECT * FROM dash_repo_settings WHERE user_id='".$data['user_id']."' AND dash_repo_id='".$data['dash_repo_id']."'
                                                           AND chart_type='".$data['chart_type']."' AND display_area='".$data['display_area']."'
                                                           AND select_param='".$data['select_param']."' AND select_sub_param='".$data['select_sub_param']."' AND frequecy='".$data['frequecy']."'
                                                           AND DATA='".$data['DATA']."'
                                                           AND target='".$data['target']."' AND criteria='".$data['criteria']."' AND flag_value='".$data['flag_value']."'
                                                           AND module_id='".$data['module_id']."' AND start_date='".$data['start_date']."'
                                                           AND end_date='".$data['end_date']."'");
                 $arr=$query->result_array();

                 if(count($arr)>0)
                 {
                         return false;
                 }
                 else
                 {
                         $var=$GLOBALS['$dbFramework']->update('dash_repo_settings' ,$data, array('id' => $editid));
		                 return true;
                 }
            }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
    }
    public function loadtabledata($userid)
    {
        try{
               $a=array();

                $var = $GLOBALS['$dbFramework']->query("SELECT a.id,user_id,a.dash_repo_id,lookup_value as dash_repo_name,dash_repo_setting_id,chart_type,select_param,select_sub_param,
                                                        frequecy,DATA,target,criteria,flag_value,updatetime,module_id,display_area,start_date,end_date
                                                        from dash_repo_settings a,lookup b where user_id='$userid'
                                                        and a.dash_repo_id=b.lookup_id");
                $arr=$var->result_array();
                $row=0;
                for($i=0;$i<count($arr);$i++)
                {
                     $paramid=$arr[$i]['select_sub_param'];
                     if($arr[$i]['select_param']=='Individual')
                     {
                            $var1 = $GLOBALS['$dbFramework']->query("SELECT user_name  FROM user_details c WHERE c.user_id='".$paramid."'");
                            $arr1=$var1->result_array();
                     }
                     else if($arr[$i]['select_param']=='Team')
                     {
                            $var1 = $GLOBALS['$dbFramework']->query("SELECT teamname as user_name FROM teams c WHERE c.teamid='".$paramid."'");
                            $arr1=$var1->result_array();
                     }
                     else
                     {
                            $var1= $GLOBALS['$dbFramework']->query("SELECT hvalue2 as user_name FROM hierarchy c WHERE c.hkey2='".$paramid."'");
                            $arr1=$var1->result_array();
                     }

                    $a[$i]['id']=$arr[$i]['id'];
                    $a[$i]['user_id']=$arr[$i]['user_id'];
                    $a[$i]['dash_repo_id']=$arr[$i]['dash_repo_id'];;
                    $a[$i]['dash_repo_name']=$arr[$i]['dash_repo_name'];
                    $a[$i]['dash_repo_setting_id']=$arr[$i]['dash_repo_setting_id'];
                    $a[$i]['chart_type']=$arr[$i]['chart_type'];
                    $a[$i]['select_param']=$arr[$i]['select_param'];
                    $a[$i]['select_sub_param']=$arr[$i]['select_sub_param'];
                    $a[$i]['sub_param_name']=$arr1[0]['user_name'];
                    $a[$i]['frequecy']=$arr[$i]['frequecy'];
                    $a[$i]['DATA']=$arr[$i]['DATA'];
                    $a[$i]['target']=$arr[$i]['target'];
                    $a[$i]['criteria']=$arr[$i]['criteria'];
                    $a[$i]['flag_value']=$arr[$i]['flag_value'];
                    $a[$i]['updatetime']=$arr[$i]['updatetime'];
                    $a[$i]['module_id']=$arr[$i]['module_id'];
                    $a[$i]['display_area']=$arr[$i]['display_area'];
                    $a[$i]['start_date']=$arr[$i]['start_date'];
                    $a[$i]['end_date']=$arr[$i]['end_date'];

                }
                 // echo"SELECT map_value FROM user_mappings WHERE map_key='displaybox' AND map_type='dashboard' AND user_id='$userid'";
                  $var_maxid = $GLOBALS['$dbFramework']->query("SELECT map_value FROM user_mappings WHERE map_key='displaybox' AND map_type='dashboard' AND user_id='".$userid."'");
                 $arr1=$var_maxid->result_array();
                 $min_display_area=$arr1[0]['map_value'];

                 return  array(
                         'titlecount'=>$min_display_area,
                         'tabledata'=>$a
                 );



            }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
    }
    public function swap_data($current_id,$current_area,$new_area,$new_id)
    {
           try{
                 if($new_id==''){
                    $query = $GLOBALS['$dbFramework']->query("Update dash_repo_settings set display_area='".$new_area."' where id='".$current_id."'");
                 }
                 else{
                   $query = $GLOBALS['$dbFramework']->query("Update dash_repo_settings set display_area='".$new_area."' where id='".$current_id."'");
                   $query1 = $GLOBALS['$dbFramework']->query("Update dash_repo_settings set display_area='".$current_area."' where id='".$new_id."'");
                 }
                 if($query){
                     return true;
                 }
                 else{
                       return false;
                 }
            }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
    }

    public function delete_data($userid,$delete_id)
    {
        try{
                 $query = $GLOBALS['$dbFramework']->query("Delete from dash_repo_settings where id='".$delete_id."'");
                 if($query)
                 {
                     return true;
                 }
                 else
                 {
                       return false;
                 }
            }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
    }
    public function update_tilecount($userid,$tilecount)
    {
        try{
                 $query = $GLOBALS['$dbFramework']->query("update user_mappings set map_value='".$tilecount."' WHERE map_key='displaybox' AND map_type='dashboard'
                                                        AND user_id='".$userid."'");

                 if($query)
                 {
                     return $tilecount;
                 }
                 else
                 {
                       return false;
                 }
            }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
    }
    public function loadgraphs($userid)
    {
         try
         {
                $a=array();
                $var = $GLOBALS['$dbFramework']->query("SELECT a.select_sub_param,
                                                        select_param,chart_type,frequecy,data,target,criteria,flag_value,display_area,
                                                        a.dash_repo_id,lookup_value as title,start_date,end_date,updatetime
                                                        from dash_repo_settings a,lookup b where user_id='$userid'
                                                        and a.dash_repo_id=b.lookup_id");
                $arr=$var->result_array();
                $row=0;
                for($i=0;$i<count($arr);$i++)
                {
                     $paramid=$arr[$i]['select_sub_param'];
                     if($arr[$i]['select_param']=='Individual')
                     {
                            $var1 = $GLOBALS['$dbFramework']->query("SELECT user_name  FROM user_details c WHERE c.user_id='".$paramid."'");
                            $arr1=$var1->result_array();
                     }
                     else if($arr[$i]['select_param']=='Team')
                     {
                            $var1 = $GLOBALS['$dbFramework']->query("SELECT teamname as user_name FROM teams c WHERE c.teamid='".$paramid."'");
                            $arr1=$var1->result_array();
                     }
                     else
                     {
                            $var1= $GLOBALS['$dbFramework']->query("SELECT hvalue2 as user_name FROM hierarchy c WHERE c.hkey2='".$paramid."'");
                            $arr1=$var1->result_array();
                     }
                    $a[$i]['call'][$row]['callersname']=$arr1[0]['user_name'] ;


                    if($arr[$i]['dash_repo_id']=='dashrep001')  //Calls Completed
                    {

                       $result_set=$this->db->query("CALL dbr_CallsCompleted('$userid','".$arr[$i]['select_param']."','$paramid','".$arr[$i]['frequecy']."','".$arr[$i]['start_date']."','".$arr[$i]['end_date']."',@totalcalls)");
                       $query = $this->db->query('SELECT @totalcalls AS out_param');
                    }
                    else if($arr[$i]['dash_repo_id']=='dashrep003') //Meetings Completed
                    {

                       $result_set=$this->db->query("CALL dbr_MeetingCompleted('$userid','".$arr[$i]['select_param']."','$paramid','".$arr[$i]['frequecy']."','".$arr[$i]['start_date']."','".$arr[$i]['end_date']."',@Total_Meetings)");
                       $query = $this->db->query('SELECT @Total_Meetings AS out_param');
                    }
                    else if($arr[$i]['dash_repo_id']=='dashrep007')//SMS Completed
                    {

                       $result_set=$this->db->query("CALL dbr_SMSCompleted('$userid','".$arr[$i]['select_param']."','$paramid','".$arr[$i]['frequecy']."','".$arr[$i]['start_date']."','".$arr[$i]['end_date']."',@totalsms)");
                       $query = $this->db->query('SELECT @totalsms AS out_param');
                    }
                    else if($arr[$i]['dash_repo_id']=='dashrep008') //Emails Completed
                    {

                       $result_set=$this->db->query("CALL dbr_EmailCompleted('$userid','".$arr[$i]['select_param']."','$paramid','".$arr[$i]['frequecy']."','".$arr[$i]['start_date']."','".$arr[$i]['end_date']."',@totalemails)");
                       $query = $this->db->query('SELECT @totalemails AS out_param');
                    }
                    else if($arr[$i]['dash_repo_id']=='dashrep004') //Sales Closure
                    {
                       $result_set=$this->db->query("CALL dbr_SalesClosure('$userid','".$arr[$i]['select_param']."','$paramid','".$arr[$i]['frequecy']."','".$arr[$i]['start_date']."','".$arr[$i]['end_date']."',@totalsalesclosure)");
                       $query = $this->db->query('SELECT @totalsalesclosure AS out_param');
                    }
                    else if($arr[$i]['dash_repo_id']=='dashrep006') //PreQual Lost
                    {

                       $result_set=$this->db->query("CALL dbr_PreQualLost('$userid','".$arr[$i]['select_param']."','$paramid','".$arr[$i]['frequecy']."','".$arr[$i]['start_date']."','".$arr[$i]['end_date']."',@totalPreQualLost)");
                       $query = $this->db->query('SELECT @totalPreQualLost AS out_param');
                    }
                    else if($arr[$i]['dash_repo_id']=='dashrep005' ) //Revenue Pipeline
                    {
                       $result_set=$this->db->query("CALL dbr_RevenuePipeline('$userid','".$arr[$i]['select_param']."','$paramid','".$arr[$i]['frequecy']."','".$arr[$i]['start_date']."','".$arr[$i]['end_date']."',@totalRevenue)");
                       $query = $this->db->query('SELECT @totalRevenue AS out_param');
                    }
                    else if($arr[$i]['dash_repo_id']=='dashrep009' )//Productivity Time
                    {

                       $result_set=$this->db->query("CALL dbr_TimeManagement('$userid','".$arr[$i]['select_param']."','$paramid','".$arr[$i]['frequecy']."','".$arr[$i]['start_date']."','".$arr[$i]['end_date']."',@totaltimespend )");
                       $query = $this->db->query('SELECT @totaltimespend  AS out_param');
                    }
                    else if($arr[$i]['dash_repo_id']=='dashrep002' ) //Call Time
                    {
                        //echo"CALL r_Totalcallduration_Dashboard('$userid','".$arr[$i]['select_param']."','$paramid','".$arr[$i]['frequecy']."','".$arr[$i]['start_date']."','".$arr[$i]['end_date']."',@total_Callduration )";
                       $result_set=$this->db->query("CALL dbr_TotalCallduration('$userid','".$arr[$i]['select_param']."','$paramid','".$arr[$i]['frequecy']."','".$arr[$i]['start_date']."','".$arr[$i]['end_date']."',@total_Callduration )");
                       $query = $this->db->query('SELECT @total_Callduration  AS out_param');
                    }
                    else if($arr[$i]['dash_repo_id']=='dashrep010' ) //Product Pipeline
                    {
                       $result_set=$this->db->query("CALL dbr_ProductPipeline('$userid','".$arr[$i]['select_param']."','$paramid','".$arr[$i]['frequecy']."','".$arr[$i]['start_date']."','".$arr[$i]['end_date']."',@total_product )");
                       $query = $this->db->query('SELECT @total_product  AS out_param');
                    }

                    $row1 = $query->row();
                    if($row1->out_param=='')
                    {
                       $total_val=0;
                    }
                    else
                    {
                       $total_val=$row1->out_param;
                    }
                    $a[$i]['call'][$row]['num_calls']=$total_val;
                    $a[$i]['call'][$row]['User_ID']=$userid;
                    $a[$i]['chart_type']=$arr[$i]['chart_type'];
                    $a[$i]['id']=$arr[$i]['display_area'];
                    $a[$i]['frequecy']=$arr[$i]['frequecy'];
                    $a[$i]['title']=$arr[$i]['title'];
                    $a[$i]['target']=$arr[$i]['target'];
                    $a[$i]['criteria']=$arr[$i]['criteria'];
                    $a[$i]['flag_value']=$arr[$i]['flag_value'];
                    $a[$i]['select_param']=$arr[$i]['select_param'];

                       if($a[$i]['frequecy']=='Monthly')
                       {
                           $a[$i]['displaydata']=date('M');
                       }
                       else if($a[$i]['frequecy']=='Daily')
                       {
                            $a[$i]['displaydata']=date('d-m-Y');
                       }
                       else if($a[$i]['frequecy']=='Weekly')
                       {
                            $a[$i]['displaydata']='';

                       }
                       else if($a[$i]['frequecy']=='Quaterly')
                       {
                           if(intval('1')>=intval(date('m')) && intval(date('m'))<=intval('3'))
                           {
                             $a[$i]['displaydata']='Jan-Mar';
                           }
                           else if(intval('4')>=intval(date('m')) && intval(date('m'))<=intval('6'))
                           {
                             $a[$i]['displaydata']='Apr-Jun';
                           }
                           else if(intval('7')>=intval(date('m')) && intval(date('m'))<=intval('9'))
                           {
                              $a[$i]['displaydata']='Jul-Sep';
                           }
                           else
                           {
                              $a[$i]['displaydata']='Oct-Dec';
                           }

                       }
                    if($arr[$i]['start_date']=='1970-01-01' && $arr[$i]['end_date']=='1970-01-01')
                    {
                       $a[$i]['start_date']=date('Y-m-d');
                      $a[$i]['end_date']='';
                    }
                    else
                    {
                      $a[$i]['start_date']=$arr[$i]['start_date'];
                      $a[$i]['end_date']=$arr[$i]['end_date'];
                    }
                }

                 $var_maxid = $GLOBALS['$dbFramework']->query("SELECT map_value FROM user_mappings WHERE map_key='displaybox' AND map_type='dashboard' AND user_id='".$userid."'");
                 $arr1=$var_maxid->result_array();
                 $min_display_area=$arr1[0]['map_value'];
                 if($min_display_area=='')
                 {
                   $min_display_area=6;
                 }


                 return  array(
                         'minarea'=>$min_display_area,
                         'data_graph'=>$a
                 );
         }
         catch (LConnectApplicationException $e)
         {
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
         }
    }
}

?>