<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_userModel1');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class admin_userModel1 extends CI_Model{
     public function __construct(){
        parent::__construct();
    }



//Data for  user details listing
public function parse_cron($crontab) {
            $cron = explode(" ",$crontab);
            $seconds = str_pad($cron[0], 2, '0', STR_PAD_LEFT);
            $minutes = explode(",",$cron[1]);
            $hours = explode(",",$cron[2]);

            $sminutes = str_pad($minutes[0], 2, '0', STR_PAD_LEFT);
            $eminutes = str_pad($minutes[1], 2, '0', STR_PAD_LEFT);

            $shours = str_pad($hours[0], 2, '0', STR_PAD_LEFT);
            $ehours = str_pad($hours[1], 2, '0', STR_PAD_LEFT);

            $work = array();
            $work['start_time'] = $shours.":".$sminutes;
            $work['end_time'] = $ehours.":".$eminutes;
            switch($cron[5]){
                    case '1': $work['day_of_week'] = "SUN";
                                    break;
                    case '2': $work['day_of_week'] = "MON";
                                    break;
                    case '3': $work['day_of_week'] = "TUE";
                                    break;
                    case '4': $work['day_of_week'] = "WED";
                                    break;
                    case '5': $work['day_of_week'] = "THU";
                                    break;
                    case '6': $work['day_of_week'] = "FRI";
                                    break;
                    case '7': $work['day_of_week'] = "SAT";
                                    break;
            }
            return $work;
}

public function view_data1(){
   try{
        $a=$b=$c=array();
        $query=$GLOBALS['$dbFramework']->query("select staff_tbl.*,manager_tbl.user_name as Manager,dpt.Department_name , teams.teamname,desg.role_name,desg.role_value,
                                                licence.manager_module,licence.sales_module,licence.cxo_module
                                                from user_details as staff_tbl
												LEFT  JOIN user_details as manager_tbl2
                                                on manager_tbl2.reporting_to=manager_tbl2.user_id
                                                LEFT join user_details as manager_tbl
                                                on staff_tbl.reporting_to=manager_tbl.user_id
                                                LEFT join department as dpt
                                                on staff_tbl.department=dpt.Department_id
                                                LEFT join teams as teams
                                                on staff_tbl.team_id=teams.teamid
                                                LEFT join user_roles as desg
                                                on staff_tbl.designation=desg.role_id
                                                LEFT join user_licence as licence
                                                on staff_tbl.user_id=licence.user_id
                                                where staff_tbl.user_name !='Admin' order by staff_tbl.id; ");
        $a=$query->result();


        $client_id = basename(dirname($_SERVER['SCRIPT_FILENAME']));
        $query1=$GLOBALS['$dbFramework']->query("select sum(json_extract(module_used, '$.cxo') +json_extract(module_used, '$.manager')+json_extract(module_used, '$.sales'))
                                                    as modulecnt,client_id,module_purchased from client_info where client_id='".$client_id."' ");
        $b=$query1->result();

        $query1=$GLOBALS['$dbFramework']->query("SELECT COUNT(*)AS activeuser_cnt FROM user_details WHERE user_state=1 AND user_gender IS NOT NULL; ");
        $c=$query1->result();

        return array(
                    'udata' =>  $a,
                    'cldata'=>$b,
                    'ucount'=>$c
        );

       }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
       }
}

public function check_emailsetting(){
    try{
                $query=$GLOBALS['$dbFramework']->query("select a.* from user_email_settings a,email_settings b
                                                                where a.email_settings_id=b.email_settings_id;");
                return $query->result();
        }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
        }
}

/*---------------------------------------------------------------------------------------------------------------------*/
public function view_modules($modules,$plugins){
                    if($modules=='modules'){
                        try{

                              $query=$GLOBALS['$dbFramework']->query("select module_id,module_name,
                                                                        case when module_name='CXO'
                                                                        then (SELECT  module_purchased FROM client_info)
                                                                        when module_name='Manager'
                                                                        then (SELECT  module_purchased FROM client_info)
                                                                        when module_name='Sales'
                                                                        then (SELECT  module_purchased FROM client_info)
                                                                        end as module_purchased,
                                                                        case when module_name='CXO'
                                                                        then (SELECT sum(json_extract(module_used, '$.cxo') +
                                                                        					json_extract(module_used, '$.manager')+
                                                                                            json_extract(module_used, '$.sales') ) FROM client_info)
                                                                        when module_name='Manager'
                                                                        then (SELECT  sum(json_extract(module_used, '$.cxo') +
                                                                        					json_extract(module_used, '$.manager')+
                                                                                            json_extract(module_used, '$.sales') ) FROM client_info)
                                                                        when module_name='Sales'
                                                                        then (SELECT  sum(json_extract(module_used, '$.cxo') +
                                                                        					json_extract(module_used, '$.manager')+
                                                                                            json_extract(module_used, '$.sales') ) FROM client_info)
                                                                        end as module_count,
                                                                        case when module_name='CXO'
                                                                        then (SELECT  json_extract(module_used, '$.cxo') FROM client_info)
                                                                        when module_name='Manager'
                                                                        then (SELECT  json_extract(module_used, '$.manager') FROM client_info)
                                                                        when module_name='Sales'
                                                                        then (SELECT  json_extract(module_used, '$.sales') FROM client_info)
                                                                        end as module_used
                                                                        from module_master order by module_name ;
                                                                        ");
                              $a= $query->result();
                          }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
                    }

                    if($plugins=='plugins'){
                     try{
                              $query=$GLOBALS['$dbFramework']->query("select plugin_id,plugin_name,
                                                                        case when plugin_name='Navigator'
                                                                        then (SELECT  json_extract(plugin_purchased, '$.navigator') FROM client_info)
                                                                        when plugin_name='Communicator'
                                                                        then (SELECT  json_extract(plugin_purchased, '$.communicator') FROM client_info)
                                                                        when plugin_name='Attendance'
                                                                        then (SELECT  json_extract(plugin_purchased, '$.attendance') FROM client_info)
                                                                        when plugin_name='Expenses'
                                                                        then (SELECT  json_extract(plugin_purchased, '$.expenses') FROM client_info)
                                                                        when plugin_name='Library'
                                                                        then (SELECT  json_extract(plugin_purchased, '$.library') FROM client_info)
                                                                        when plugin_name='Inventory'
                                                                        then (SELECT  json_extract(plugin_purchased, '$.inventory') FROM client_info)
                                                                        end as plugin_purchased,

                                                                        case when plugin_name='Navigator'
                                                                        then (SELECT  json_extract(plugin_used, '$.navigator') FROM client_info)
                                                                        when plugin_name='Communicator'
                                                                        then (SELECT  json_extract(plugin_used, '$.communicator') FROM client_info)
                                                                        when plugin_name='Attendance'
                                                                        then (SELECT  json_extract(plugin_used, '$.attendance') FROM client_info)
                                                                        when plugin_name='Expenses'
                                                                        then (SELECT  json_extract(plugin_used, '$.expenses') FROM client_info)
                                                                        when plugin_name='Library'
                                                                        then (SELECT  json_extract(plugin_used, '$.library') FROM client_info)
                                                                        when plugin_name='Inventory'
                                                                        then (SELECT  json_extract(plugin_used, '$.inventory') FROM client_info)
                                                                        end as plugin_used
                                                                        from plugin_master order by plugin_name;
                                                                  ");
                              $b= $query->result();
                        }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }

                    }
                    return array(
                    'modules' =>  $a,
                    'plugin'=>$b

                    );


}
public function view_department($department,$calender,$currency,$salespersona,$groupmail){
         try{
                if($department=='department'){
                    $query=$GLOBALS['$dbFramework']->query("SELECT * FROM department where Department_id in
                                                        (select distinct department_id from user_roles )  ORDER BY Department_name");
                    $a= $query->result();
                }


                if($calender=='calender'){
                    $query=$GLOBALS['$dbFramework']->query("SELECT * FROM calender ORDER BY calendername");
                    $d= $query->result();
                }
                if($currency=='currency'){
                    $query=$GLOBALS['$dbFramework']->query("select a.currency_id,a.currency_name from currency a,
                                                currency_category b where a.currency_category_id=b.currency_category_id
                                                and b.currency_category_name='Spend Calculation'");
                    $e= $query->result();
                }
                if($salespersona=='salespersona'){
                    $query=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='Sales Persona'");
                    $f= $query->result();
                }
                if($groupmail=='groupmail'){
                    $query=$GLOBALS['$dbFramework']->query("SELECT * FROM user_email_settings WHERE settings_key='groupsetting'");
                    $g= $query->result();
                }
                return array(
                    'dept' =>  $a,
                    'calender'=>$d,
                    'currency'=>$e,
                    'salespersona'=>$f,
                    'groupmail'=>$g
                );
         }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
         }
}
public function view_roledata($depid){
    try{
                $query=$GLOBALS['$dbFramework']->query("SELECT * FROM user_roles WHERE department_id='$depid' and role_value>0 order by role_value");
                return $query->result();
        }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}
public function view_designation($roleid) {
     try{
                $query = $GLOBALS['$dbFramework']->query("select role_value from user_roles where role_id='$roleid'");
                $result = $query->result();
                $value = $result[0]->role_value;
                if($value==1){
                    $query1 = $GLOBALS['$dbFramework']->query("select role_id,role_name,role_value
                                        from user_roles where role_value < '$value' and department_id='' ");
                    return $query1->result();
                }else{

                    $query2 = $GLOBALS['$dbFramework']->query("call rolelevel_user(".$value.",'notcxo','role1',''); ");
                    return $query2->result();
                }
      }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}
public function reportingname($rptoid,$uid){
  try{
                $query=$GLOBALS['$dbFramework']->query("SELECT a.user_id,a.user_name,b.manager_module
                                                              ,b.cxo_module FROM user_details a,user_licence b
                                                              WHERE a.designation='".$rptoid."' and a.user_id<>'".$uid."'  and
                                                              a.user_id=b.user_id and a.user_state=1 and
                                                              (b.manager_module<>'0' or b.cxo_module<>'0'); ");
                return $query->result();

      }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}
public function view_teamsdata($depid){
   try{
                $query=$GLOBALS['$dbFramework']->query("SELECT * FROM teams where  department_id='$depid'");
                return $query->result();
      }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}
public function view_teams($teamid,$busiid,$indusid){
   try{
                $sellstr=$sellstr1="";
                $str1=array();
                if($teamid<>""){
                        $query=$GLOBALS['$dbFramework']->query("SELECT regionid FROM teams where  teamid='$teamid'");    // selltype

                        foreach ($query->result() as $row1)
                        {
                                $regionid=$row1->regionid;
                                $str1=explode(',',$regionid);
                                $result = "'" . implode ( "', '", $str1 ) . "'";
                                $sellstr="";
                                $query11=$GLOBALS['$dbFramework']->query("select * from lookup where lookup_name='support_process' and lookup_id in (".$result."); ");
                                $a1= $query11->result();
                        }

                        $query=$GLOBALS['$dbFramework']->query("SELECT a.product_id as locid,b.hvalue2 as ofcLoc,b.hvalue1 as ofcLoc1 FROM
                                                        product_currency_mapping a,hierarchy b where
                                                        a.product_id=b.hkey2 and a.team_id='$teamid' and a.remarks='ofloc'");
                        $b= $query->result();

                        $a=array();
                        $remark="product";
                        $que=$GLOBALS['$dbFramework']->query("select distinct a.product_id,(select distinct hvalue2 from hierarchy where
                                                                hkey2=a.product_id) as productname,(select distinct hvalue1 from hierarchy where
                                                                hkey2=a.product_id) as productname1 from product_currency_mapping a where a.team_id='$teamid'
                                                                    and a.remarks='product'and a.togglebit=1;");
                        $arr=$que->result_array();
                        $row=0;
                                        if($que->num_rows()>0){
                                          for($i=0;$i<count($arr);$i++){
                                              $product_id=$arr[$i]['product_id'];
                                              $a[$row]['productname']=$arr[$i]['productname']." (".$arr[$i]['productname1'].")";
                                              $a[$row]['product_id']=$arr[$i]['product_id'];

                                              $query1=$GLOBALS['$dbFramework']->query("select a.currency_id,(select currency_name from currency where
                                                                                        a.currency_id=currency_id)as currencyname from product_currency_mapping a
                                                                                        where product_id='$product_id' and a.team_id='$teamid'
                                                                                            and a.togglebit=1 order by id ");
                                              $arr1=$query1->result_array();
                                                  for($j=0;$j<count($arr1);$j++){
                                                              $a[$row]['curdata'][$j]['currency_id']=$arr1[$j]['currency_id'];
                                                              $a[$row]['curdata'][$j]['currencyname']=$arr1[$j]['currencyname'];
                                                  }
                                               $row++;
                                          }
                                        }
                        $e=$a;

                }
                if($busiid <>""){
                        /* businness location, industry leaf nodes */
                        $bquery=$GLOBALS['$dbFramework']->query("SELECT a.product_id as nodeid,b.hvalue2 as nodename,b.hvalue1 as nodename1 FROM
                                                        product_currency_mapping a,hierarchy b where
                                                        a.product_id=b.hkey2 and a.team_id='$teamid' and a.remarks='busloc'");
                        if($bquery->num_rows()>0){
                            $c= $bquery->result();
                        }else{
                            $a=array();
                            $cnt=0;
                            $row=0;
                            $hierarchy_class="business_location";
                            $query=$GLOBALS['$dbFramework']->query(" call get_tree_leafnode('".$hierarchy_class."','".$busiid."'); ");

                            if($query->num_rows()>0){
                                 $arr2=$query->result_array();
                                    for($j=0;$j<count($arr2);$j++){
                                        $hkey2=$arr2[$j]['nodeid'];
                                        $nodename=$arr2[$j]['nodename'];
                                        $nodename1=$arr2[$j]['nodename1'];

                                            $a[$cnt]['nodeid']=$hkey2;
                                            $a[$cnt]['nodename']=$nodename;
                                            $a[$cnt]['nodename1']=$nodename1;
                                            $cnt=$cnt+1;
                                    }
                           }
                           $c=$a;

                        }


                }

                if($indusid <>""){
                      /* businness location, industry leaf nodes */
                      $iquery=$GLOBALS['$dbFramework']->query("SELECT a.product_id as nodeid,b.hvalue2 as nodename,b.hvalue1 as nodename1 FROM
                                                        product_currency_mapping a,hierarchy b where
                                                        a.product_id=b.hkey2 and a.team_id='$teamid' and a.remarks='indus'");
                      if($iquery->num_rows()>0){
                          $d= $iquery->result();
                      }else{
                            $a=array();
                            $cnt=0;
                            $row=0;
                            $hierarchy_class="industry";
                            $query=$GLOBALS['$dbFramework']->query("call get_tree_leafnode('".$hierarchy_class."','".$indusid."'); ");

                                 if($query->num_rows()>0){
                                       $arr2=$query->result_array();
                                          for($j=0;$j<count($arr2);$j++){
                                              $hkey2=$arr2[$j]['nodeid'];
                                              $nodename=$arr2[$j]['nodename'];
                                              $nodename1=$arr2[$j]['nodename1'];

                                                  $a[$cnt]['nodeid']=$hkey2;
                                                  $a[$cnt]['nodename']=$nodename;
                                                  $a[$cnt]['nodename1']=$nodename1;
                                                  $cnt=$cnt+1;
                                          }
                                 }
                            $d=$a;

                      }

                }
                      return array(
                          'selltype' =>  $a1,
                          'offloc'=> $b,
                          'business'=>$c,
                          'industry'=>$d,
                          'procurdata'=>$e

                      );

    }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}
public function get_offcLocation($teamid){
   try{
                $query=$GLOBALS['$dbFramework']->query("SELECT a.product_id as locid,b.hvalue2 as ofcLoc,b.hvalue1 as ofcLoc1 FROM
                                                        product_currency_mapping a,hierarchy b where
                                                        a.product_id=b.hkey2 and a.team_id='$teamid' and a.remarks='ofloc'");
                return $query->result();
     }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }

}
public function check_phone($phno,$addtype){
   try{
                if($addtype=='phone'){
                        $query=$GLOBALS['$dbFramework']->query("select * from user_details where json_extract(phone_num, '$.*') like '%$phno%';");
                        return $query->num_rows();
                }else if($addtype=='email'){
                        $query=$GLOBALS['$dbFramework']->query("select * from user_details where json_extract(emailId, '$.*') like '%$phno%';");
                        return $query->num_rows();
                }
       }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }

}

public function insert_data($data){
                $var = $GLOBALS['$dbFramework']->insert('user_details',$data);
		        return $var;

}
public function insert_mobileno($json_data,$userid) {
      try{
                $update = $GLOBALS['$dbFramework']->update('user_details' ,$json_data, array('LOWER(user_id)' => strtolower($userid)));
		        return $update;
        }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}
public function update_data1($data, $userid,$add_user_loc,$add_selltype,$save_user_email)	{
   try{

		            $update = $GLOBALS['$dbFramework']->update('user_details' ,$data, array('LOWER(user_id)' => strtolower($userid)));
                    $map_type="office_location";
                    $delque= $GLOBALS['$dbFramework']->query("delete from user_mappings where user_id='".$userid."' and map_type='".$map_type."'");

                    $dt = date('ymdHis');
                    $add_user_loc1=explode(',',$add_user_loc);
                    for($i=0;$i<count($add_user_loc1);$i++){
                        $locid=$add_user_loc1[$i];
                        $letter=chr(rand(97,122));
                        $letter.=chr(rand(97,122));
                        $procurmapID=$letter;
                        $procurmapID.=$dt;
                        $procurmapID1=uniqid($procurmapID);

                        $que=$GLOBALS['$dbFramework']->query("insert into user_mappings(user_mapping_id,user_id,map_type,map_id,transaction_Id)
                                                                                values('".$procurmapID1."','".$userid."','".$map_type."',
                                                                                        '".$locid."','".$data['team_id']."')");
                    }

                    $map_type="sell_type";
                    $delque= $GLOBALS['$dbFramework']->query("delete from user_mappings where user_id='".$userid."' and map_type='".$map_type."'");
                    $add_selltype1=explode(',',$add_selltype);
                    for($i=0;$i<count($add_selltype1);$i++){
                        $locid=$add_selltype1[$i];
                        $letter=chr(rand(97,122));
                        $letter.=chr(rand(97,122));
                        $procurmapID=$letter;
                        $procurmapID.=$dt;
                        $procurmapID1=uniqid($procurmapID);

                        $que=$GLOBALS['$dbFramework']->query("insert into user_mappings(user_mapping_id,user_id,map_type,map_id,transaction_Id)
                                                                                values('".$procurmapID1."','".$userid."','".$map_type."',
                                                                                        '".$locid."','".$data['team_id']."')");
                    }

                    /* check for team on edit-- if found different, remove data of products, busi loc and ind  */
                    $que1=$GLOBALS['$dbFramework']->query("SELECT distinct transaction_id FROM user_mappings where user_id='".$userid."' and map_type='product';");
                    if($que1->num_rows()>0){
                        foreach ($que1->result() as $row1)
                        {
                            $team=$row1->transaction_id;

                        }

                        if($data['team_id']!=$team){
                               $map_type="product";
                               $query=$GLOBALS['$dbFramework']->query("delete FROM user_mappings where user_id='$userid' and map_type='$map_type'");
                               $map_type="clientele_industry";
                               $query=$GLOBALS['$dbFramework']->query("delete FROM user_mappings where user_id='$userid' and map_type='$map_type'");
                               $map_type="business_location";
                               $query=$GLOBALS['$dbFramework']->query("delete FROM user_mappings where user_id='$userid' and map_type='$map_type'");
                        }

                    }

                    /* update reporting desgination of user reporting to selected user */
                    $que1=$GLOBALS['$dbFramework']->query("select user_id,user_name from user_details where reporting_to='".$userid."';");
                    if($que1->num_rows()>0){
                        foreach ($que1->result() as $row1)
                        {
                            $uid=$row1->user_id;
                            $update = $GLOBALS['$dbFramework']->query("update user_details set reporting_desg='".$data['designation']."' where user_id='".$uid."'");
                        }
                    }

                    /* insert group mail details */
                    $map_type='groupmail';
                    $delque= $GLOBALS['$dbFramework']->query("delete from user_mappings where user_id='".$userid."' and map_type='".$map_type."'");
                    foreach ($save_user_email as  $value) {

                            $mailid=$value->user;
                            $permisi=$value->permisi;
                            $que=$GLOBALS['$dbFramework']->query("insert into user_mappings(user_mapping_id,user_id,map_type,map_id,map_key,map_value)
                                                                                values('".$procurmapID1."','".$userid."','".$map_type."',
                                                                                        '".$mailid."','permission','".$permisi."')");

                    }

                 return true;
        }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}

public function insert_repdata($data1){
  try{
                    $var = $GLOBALS['$dbFramework']->insert('user_reporting',$data1);
                    return $var;
    }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}

public function update_repdata($reportingdata,$userid){
   try{
                   $update = $GLOBALS['$dbFramework']->update('user_reporting' ,$reportingdata, array('LOWER(user_id)' => strtolower($userid),'activebit'=>1));
		           return $update;
    }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}

public function update_emailId($json_data,$userid) {
    try{
		        $update = $GLOBALS['$dbFramework']->update('user_details' ,$json_data, array('LOWER(user_id)' => strtolower($userid)));
		        return $update;
     }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}
public function insert_user_licence($data1){
    try{
                    $var = $GLOBALS['$dbFramework']->insert('user_licence',$data1);
                    return $var;
    }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}

//----------To Update User Licence----------------------//
public function update_user_licence($data1,$userid){
      try{
                    $query=$GLOBALS['$dbFramework']->query("SELECT * FROM user_licence where user_id='$userid'");
                    $query->result_array();
                    if($query->num_rows()>0){
                            $update = $GLOBALS['$dbFramework']->update('user_licence' ,$data1, array('LOWER(user_id)' => strtolower($userid)));
                            return $update;
                    }else{
                            $var = $GLOBALS['$dbFramework']->insert('user_licence',$data1);
                            return $var;
                    }
       }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}

public function module_plugin_mapping($json_data1){
     try{
                    $var = $GLOBALS['$dbFramework']->insert('user_module_plugin_mapping',$json_data1);
                    return $var;
       }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}

//----------To Update Module Plugin Mappzend_logo_guid()ng----------------------//
public function update_module_plugin_mapping($json_data1,$userid)	{
      try{
                    $query=$GLOBALS['$dbFramework']->query("SELECT * FROM user_module_plugin_mapping where user_id='$userid'");
                    $query->result_array();
                    if($query->num_rows()>0){
                        $update = $GLOBALS['$dbFramework']->update('user_module_plugin_mapping' ,$json_data1, array('LOWER(user_id)' => strtolower($userid)));
                        return $update;
                    }else{
                        $var = $GLOBALS['$dbFramework']->insert('user_module_plugin_mapping',$json_data1);
                        return $var;
                    }
        }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}

public function updateclientinfo($clientinfo){
     try{
                    //$client_id="TESTPRO34"; // put the client id from table
                    //new code added
                    $client_id = basename(dirname($_SERVER['SCRIPT_FILENAME']));
                    $update = $GLOBALS['$dbFramework']->update('client_info' ,$clientinfo, array('LOWER(client_id)' => strtolower($client_id)));
                    return $update;
     }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }

}

public function insert_Ins($data){
    try{
                    $var = $GLOBALS['$dbFramework']->insert_batch('user_mappings',$data);
	                return $var;
      }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}
public function productivityDetails($data1){
    try{
                    $var = $GLOBALS['$dbFramework']->insert('representative_details',$data1);
                    return $var;
       }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}
public function user_attributes($data2,$add_user){
       try{
                    $var = $GLOBALS['$dbFramework']->insert('user_attributes',$data2);
                    return $var;
        }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}
public function timezonedata($timezonedata){
       try{
                    $var1 = $GLOBALS['$dbFramework']->insert('user_timezone_setting',$timezonedata);
                    return $var1;
        }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}
public function insert_procurrency($add_user,$prodCurrencymain,$user_team){

     try{
                    $map_type="product";
                    $query=$GLOBALS['$dbFramework']->query("SELECT * FROM user_mappings where user_id='$add_user' and map_type='$map_type'");
                          $query->result_array();
                          if($query->num_rows()>0){
                                   $query1=$GLOBALS['$dbFramework']->query("DELETE FROM user_mappings where user_id='$add_user' and map_type='$map_type'");
                           }

                     $map_type="product";
                     $map_value="currency";
                     $dt=date('ymdHis');
                     foreach ($prodCurrencymain as $val){
                                        $prod=$val->prod;
                                        $currency=$val->currency;

                              if($currency <> ""){
                                  $currency=rtrim($currency,',');
                                  $currency1=explode(',',$currency);
                                  for($i=0;$i<count($currency1);$i++){
                                      $letter=chr(rand(97,122));
                                      $letter.=chr(rand(97,122));
                                      $procurmapID=$letter;
                                      $procurmapID.=$dt;
                                      $procurmapID1=uniqid($procurmapID);
                                      $curid=$currency1[$i];

                                      $que=$GLOBALS['$dbFramework']->query("insert into user_mappings(user_mapping_id,user_id,map_type,map_id,map_key,map_value,transaction_Id)
                                                                                values('".$procurmapID1."','".$add_user."','".$map_type."',
                                                                                '".$prod."','".$map_value."','".$curid."','".$user_team."')");
                                  }
                              }else{
                                      $letter=chr(rand(97,122));
                                      $letter.=chr(rand(97,122));
                                      $procurmapID=$letter;
                                      $procurmapID.=$dt;
                                      $procurmapID1=uniqid($procurmapID);
                                      $que=$GLOBALS['$dbFramework']->query("insert into user_mappings(user_mapping_id,user_id,map_type,map_id,transaction_Id)
                                                                                values('".$procurmapID1."','".$add_user."','".$map_type."',
                                                                                        '".$prod."','".$user_team."')");
                                  }
                               }

                  return TRUE;
        }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}

public function get_remaininguser_data($userid,$loc_team,$reporting_toid){
     try{
        /* ---------------------------- modules ---------------------------------------------- */
            $get_modules=$GLOBALS['$dbFramework']->query("select module_id from user_module_plugin_mapping where user_id='$userid'");
            $a1=$get_modules->result();
        /* ---------------------------- modules ---------------------------------------------- */
            $que=$GLOBALS['$dbFramework']->query("select * from user_roles where  role_id='".$reporting_toid."'; ");
            $que->result();
            if($que->num_rows()>0){
                  foreach ($que->result() as $row)
                  {
                       $role_value = $row->role_value;
                       if($role_value==0){
                              $get_modules1=$GLOBALS['$dbFramework']->query("select module_id from user_module_plugin_mapping where user_id='".$userid."' ");
                              $a11=$get_modules1->result();
                       }
                  }
            }else{

                    $get_modules1=$GLOBALS['$dbFramework']->query("select module_id from user_module_plugin_mapping where user_id='".$reporting_toid."' ");
                    $a11=$get_modules1->result();
            }

        /* ---------------------------- plugins ---------------------------------------------- */
            $get_plugin=$GLOBALS['$dbFramework']->query("select plugin_id from user_module_plugin_mapping where user_id='$userid'");
            $b=$get_plugin->result();
        /* ---------------------------- timezone ---------------------------------------------- */
            $get_timezone=$GLOBALS['$dbFramework']->query("select timezone from user_timezone_setting order by id desc limit 1");
            $b1=$get_timezone->result();
        /* ---------------------------- officelocation ---------------------------------------------- */
            $row=0;
            $loc_team1=explode(',',$loc_team);

                for($x=0;$x<count($loc_team1);$x++){
                    $val=$loc_team1[$x];
                    $query=$GLOBALS['$dbFramework']->query("SELECT hvalue2,hkey2 from hierarchy where hkey2='$val'");
                    $rowdata=$query->result_array();
                    for($i=0;$i<count($rowdata);$i++){
                        $ofcLoc[$row]['name']=$rowdata[$i]['hvalue2'];
                        $ofcLoc[$row]['id']=$rowdata[$i]['hkey2'];
                    }
                    $row++;
                }
            $c=$ofcLoc;
        /* ---------------------------- business loction ---------------------------------------------- */
            $query=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('business_location','user_mappings','".$userid."','bussinessLoc1,bussinessLoc');");
            $d=$query->result();

        /* ---------------------------- industries ---------------------------------------------- */
            $query=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('clientele_industry','user_mappings','".$userid."','clientInds1,clientInds');");

            $e=$query->result();

        /* ---------------------------- Product Currency ---------------------------------------------- */
            $a=array();
            $get_products=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('product','user_mappings','".$userid."','productname1,productname');");
            $arr=$get_products->result_array();
            $row=0;
                        if($get_products->num_rows()>0){
                          for($i=0;$i<count($arr);$i++){
                              $product_id=$arr[$i]['map_id'];
                             $a[$row]['product_id']=$arr[$i]['map_id'];
                             $a[$row]['productname']=$arr[$i]['productname'];

                              $query1=$GLOBALS['$dbFramework']->query("select a.map_value,(select currency_name from currency where a.map_value=currency_id)as currencyname from user_mappings a where map_id='$product_id'  and user_id='$userid' order by id ");
                              $arr1=$query1->result_array();
                                  for($j=0;$j<count($arr1);$j++){
                                              $a[$row]['curdata'][$j]['currency_id']=$arr1[$j]['map_value'];
                                              $a[$row]['curdata'][$j]['currencyname']=$arr1[$j]['currencyname'];

                                  }
                               $row++;
                          }
                        }
            $f=$a;
            /* ---------------------------- working Details ---------------------------------------------- */
            $work=array();
            $query=$GLOBALS['$dbFramework']->query("SELECT expression FROM user_attributes where user_id='$userid' ");
            $query->result();
            if($query->num_rows()>0){
                foreach ($query->result() as $row)
                {
                     $workingArr = $row->expression;
                }
                $workingArr = json_decode($workingArr);
                if(count($workingArr)!=0){
                    for($i=0;$i<count($workingArr);$i++){
                         $work[] = $this->parse_cron($workingArr[$i]);
                    }

                    $g=$work;
                }else{
                   $g=$workingArr;
                }

            }else{
              $g=$work;
            }

            /* ---------------------------- Productivity Details ---------------------------------------------- */
            $query=$GLOBALS['$dbFramework']->query("SELECT a.*,c.calendername,
                                                              (select currency_name from currency where a.resource_currency=currency_id)as resource_curcyName,
                                                              (select currency_name from currency where a.outgoingcall_currency=currency_id)as outgoingcall_curcyName,
                                                              (select currency_name from currency where a.outgoingsms_currency=currency_id)as outgoingsms_curcyName
                                                              FROM representative_details a,calender c
                                                              where a.user_id='$userid'
                                                              and a.holiday_calender=c.calenderid");
            $h=$query->result();
            /* ---------------------------- sell type ---------------------------------------------- */
            $query=$GLOBALS['$dbFramework']->query("SELECT a.map_type,a.map_id FROM user_mappings a where user_id='$userid' and map_type='sell_type'");

            $i=$query->result();

            /* ---------------------------- groupmail ---------------------------------------------- */
            $query=$GLOBALS['$dbFramework']->query("SELECT a.map_type,a.map_id,a.map_key,a.map_value,b.email_id FROM
                                                        user_mappings a,user_email_settings b where a.user_id='".$userid."' and
                                                    a.map_type='groupmail' and b.user_email_settings_id=a.map_id;");

            $b2=$query->result();

            return array(
               'modules'=>$a1,
               'rep_modules'=>$a11,
               'plugin'=>$b,
               'officeloc'=>$c,
               'businessloc'=>$d,
               'industry'=>$e,
               'procur'=>$f,
               'workdetails'=>$g,
               'prodetails'=>$h,
               'selltype'=>$i,
               'timezone'=>$b1,
               'groupmail'=>$b2

            );
       }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }

}

public function update_userPhoto($data, $userid)	{
   try{
		        $update = $GLOBALS['$dbFramework']->update('user_details' ,$data, array('LOWER(user_id)' => strtolower($userid)));
		        return $update;
       }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}


/* -------------------------------------------------------- update code queries ------------------------------------------- */
//----------To Update User Details----------------------//
public function update_userinfo($data,$userid) {
       try{
                    $update = $GLOBALS['$dbFramework']->update('user_details' ,$data, array('LOWER(user_id)' => strtolower($userid)));
                    return $update;
       }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}


//----------To Update productivity Details----------------------//
public function update_productivityDetails($data1,$userid){
    try{
                    $query=$GLOBALS['$dbFramework']->query("SELECT * FROM representative_details where user_id='$userid'");
                    $query->result_array();
                    if($query->num_rows()>0){
                        $update = $GLOBALS['$dbFramework']->update('representative_details' ,$data1, array('LOWER(user_id)' => strtolower($userid)));
                        return $update;
                    }else{
                        $var = $GLOBALS['$dbFramework']->insert('representative_details',$data1);
                        return $var;
                    }
     }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}

//----------To Update User Attributes----------------------//
public function update_user_attributes($data,$userid){
       try{

                    $query=$GLOBALS['$dbFramework']->query("SELECT * FROM user_attributes where user_id='$userid'");
                    $query->result_array();
                    if($query->num_rows()>0){
                      $update = $GLOBALS['$dbFramework']->update('user_attributes' ,$data, array('LOWER(user_id)' => strtolower($userid)));
                      return $update;
                    }else{
                      $var = $GLOBALS['$dbFramework']->insert('user_attributes',$data);
                      return $var;
                    }
        }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
        }
}

//----------To Update Client Industry----------------------//
 public function update_Ins($insertArray,$map_type,$add_user){
   try{
                  $query=$GLOBALS['$dbFramework']->query("SELECT * FROM user_mappings where user_id='$add_user' and map_type='$map_type'");
                  $query->result_array();
                  if($query->num_rows()>0){
                        $query1=$GLOBALS['$dbFramework']->query("DELETE FROM user_mappings where user_id='$add_user' and map_type='$map_type'");
                  }
                  $var = $GLOBALS['$dbFramework']->insert_batch('user_mappings',$insertArray);
                  return TRUE;
    }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}

public function get_fulluser_data($userid){
   try{
            $query=$GLOBALS['$dbFramework']->query("select staff_tbl.*,manager_tbl.user_name as Manager,dpt.Department_name , teams.teamname,desg.role_name,salespersona.lookup_value
                                            from user_details as staff_tbl
                                            LEFT  JOIN user_details as manager_tbl2
                                            on manager_tbl2.reporting_to=manager_tbl2.user_id
                                            LEFT join user_details as manager_tbl
                                            on staff_tbl.reporting_to=manager_tbl.user_id
                                            LEFT join department as dpt
                                            on staff_tbl.department=dpt.Department_id
                                            LEFT join teams as teams
                                            on staff_tbl.team_id=teams.teamid
                                            LEFT join user_roles as desg
                                            on staff_tbl.designation=desg.role_id
                                            LEFT join lookup as salespersona
                                            on staff_tbl.user_product=salespersona.lookup_id
                                            where staff_tbl.user_name !='Admin' and staff_tbl.user_id='$userid' order by staff_tbl.id ");
            $a2= $query->result();
        /* ---------------------------- modules ---------------------------------------------- */
            $get_modules=$GLOBALS['$dbFramework']->query("select module_id from user_module_plugin_mapping where user_id='$userid'");
            $a1=$get_modules->result();
        /* ---------------------------- plugins ---------------------------------------------- */
            $get_plugin=$GLOBALS['$dbFramework']->query("select plugin_id from user_module_plugin_mapping where user_id='$userid'");
            $b=$get_plugin->result();

        /* ---------------------------- officelocation ---------------------------------------------- */

            $query=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('office_location','user_mappings','".$userid."','bussinessLoc1,bussinessLoc');");
            $c=$query->result();

        /* ---------------------------- business loction ---------------------------------------------- */
            $query=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('business_location','user_mappings','".$userid."','bussinessLoc1,bussinessLoc');");
            $d=$query->result();

        /* ---------------------------- industries ---------------------------------------------- */
            $query=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('clientele_industry','user_mappings','".$userid."','clientInds1,clientInds');");

            $e=$query->result();

        /* ---------------------------- Product Currency ---------------------------------------------- */
            $a=array();
            $get_products=$GLOBALS['$dbFramework']->query("SELECT  distinct a.map_type,a.map_id,
                                                        (select distinct hvalue2 from hierarchy where hkey2=a.map_id) as productname,
                                                        (select distinct hvalue1 from hierarchy where hkey2=a.map_id) as productname1
                                                        FROM user_mappings a where user_id='".$userid."' and map_type='product';");
            $arr=$get_products->result_array();
            $row=0;
                        if($get_products->num_rows()>0){
                          for($i=0;$i<count($arr);$i++){
                              $product_id=$arr[$i]['map_id'];
                             $a[$row]['product_id']=$arr[$i]['map_id'];
                             $a[$row]['productname']=$arr[$i]['productname']."<b> (".$arr[$i]['productname1'].")</b>";

                              $query1=$GLOBALS['$dbFramework']->query("select a.map_value,(select currency_name from currency where a.map_value=currency_id)as currencyname from user_mappings a where map_id='$product_id'  and user_id='$userid' order by id ");
                              $arr1=$query1->result_array();
                                  for($j=0;$j<count($arr1);$j++){
                                              $a[$row]['curdata'][$j]['currency_id']=$arr1[$j]['map_value'];
                                              $a[$row]['curdata'][$j]['currencyname']=$arr1[$j]['currencyname'];

                                  }
                               $row++;
                          }
                        }
            $f=$a;
            /* ---------------------------- working Details ---------------------------------------------- */
            $g="";
            $query=$GLOBALS['$dbFramework']->query("SELECT expression FROM user_attributes where user_id='$userid' ");
            $query->result();
            if($query->num_rows()>0){
                foreach ($query->result() as $row)
                {
                     $workingArr = $row->expression;
                }
                $workingArr = json_decode($workingArr);
                if(count($workingArr)!=0){
                    for($i=0;$i<count($workingArr);$i++){
                         $work[] = $this->parse_cron($workingArr[$i]);
                    }

                    $g=$work;
                }else{
                   $g=$workingArr;
                }

            }

            /* ---------------------------- Productivity Details ---------------------------------------------- */
            $query=$GLOBALS['$dbFramework']->query("SELECT a.*,c.calendername,
                                                              (select currency_name from currency where a.resource_currency=currency_id)as resource_curcyName,
                                                              (select currency_name from currency where a.outgoingcall_currency=currency_id)as outgoingcall_curcyName,
                                                              (select currency_name from currency where a.outgoingsms_currency=currency_id)as outgoingsms_curcyName
                                                              FROM representative_details a,calender c
                                                              where a.user_id='$userid'
                                                              and a.holiday_calender=c.calenderid");
            $h=$query->result();
            /* ---------------------------- sell type ---------------------------------------------- */
            $query=$GLOBALS['$dbFramework']->query("select a.lookup_id ,a.lookup_value as map_id from lookup a, user_mappings b
                                                    where a.lookup_id=b.map_id and b.user_id='$userid' and b.map_type='sell_type';");

            $i=$query->result();

            /* ---------------------------- user app details ---------------------------------------------- */
            $query=$GLOBALS['$dbFramework']->query("select * from user_app_details
                                                    where user_id='$userid' ;");

            $app=$query->result();

            /* ---------------------------- Target Add/Edit details ---------------------------------------------- */
            $checkSuperior = $GLOBALS['$dbFramework']->query("
                                                                    SELECT 
                                                                    *
                                                                    FROM
                                                                    user_details AS ud,
                                                                    user_details AS ud1
                                                                    WHERE
                                                                    ud.reporting_to = ud1.user_id
                                                                    AND ud1.user_name = 'Admin'
                                                                    AND ud.user_id = '$userid'

                                                            ");
         $moduleQuery = $GLOBALS['$dbFramework']->query("
                                                                    SELECT 
                                                                    *
                                                                    FROM
                                                                    user_licence AS ul
                                                                    WHERE 
                                                                    ul.user_id = '$userid'

                                                    ");
        $moduleArray = $moduleQuery->result();                                          
        $permission = array();

                if ($checkSuperior->num_rows() > 0) 
                {
                    $permission = array(
                        'canAddHimself' => 'Yes',
                        'canEditHimself' => 'Yes',
                        'manager_module' =>$moduleArray[0]->manager_module,
                        'sales_module'=>$moduleArray[0]->sales_module,
                        'canAdd'=> 'Yes',
                        'canEdit'=>'Yes',
                        'user_id'=>$moduleArray[0]->user_id,
                        'loginUser'=>$this->session->userdata('uid')
                    );

                    
                }
                else
                {
                    $permission = array(
                        'canAddHimself' => 'No',
                        'canEditHimself' => 'No',
                        'manager_module' =>$moduleArray[0]->manager_module,
                        'sales_module'=>$moduleArray[0]->sales_module,
                        'canAdd'=> 'Yes',
                        'canEdit'=>'Yes',
                        'user_id'=>$moduleArray[0]->user_id,
                        'loginUser'=>$this->session->userdata('uid')
                    );
                }


            return array(
               'user'=>$a2,
               'modules'=>$a1,
               'plugin'=>$b,
               'officeloc'=>$c,
               'businessloc'=>$d,
               'industry'=>$e,
               'procur'=>$f,
               'workdetails'=>$g,
               'prodetails'=>$h,
               'selltype'=>$i,
               'appdetails'=>$app,
               'permission'=>$permission
            );

     }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}

public function check_deactive($userid,$department,$roleid){
   try{
              $a=array();
              $b=array();
              $c=array();
              $chk_module=$GLOBALS['$dbFramework']->query("select * from user_licence where user_id='".$userid."' and (manager_module<>'0' or cxo_module<>'0')");
              $chk_module->result();
              if($chk_module->num_rows()>0){

                    $chkrep=$GLOBALS['$dbFramework']->query("select * from user_details where reporting_to='".$userid."' and user_state=1 ");
                    $chkrep->result();
                    if($chkrep->num_rows()>0){
                                        $que=$GLOBALS['$dbFramework']->query("call rolelevel_user(0,'designation','".$userid."','');");
                                          if($que->num_rows()>0){
                                                  foreach ($que->result() as $row)
                                                  {
                                                       $reporting_desg=$row->reporting_desg;
                                                       $user_id1=$row->user_id;
                                                       $deptname=$row->deptname;
                                                       $rolename=$row->rolename;
                                                       $user_name=$row->user_name;
                                                       $cxo_module=$row->cxo_module;

                                                       array_push($a,$user_id1,$user_name,$deptname,$rolename);

                                                       if($reporting_desg==""){
                                                            $reporting_desg=$this->session->userdata('uid'); /* id to be taken from session */
                                                       }
                                                      /* if data found in array get users on top level */

                                                        $que1=$GLOBALS['$dbFramework']->query("select * from user_roles where role_id='$reporting_desg';");
                                                         if($que1->num_rows()>0){
                                                            foreach ($que1->result() as $row1)
                                                            {
                                                                 $role_value=$row1->role_value;
                                                                 if($cxo_module<>"0"){
                                                                            $c[$user_id1]=array();
                                                                            $query2 = $GLOBALS['$dbFramework']->query("call rolelevel_user(".$role_value.",'cxo','user','".$userid."');");
                                                                           if($query2->num_rows()>0){
                                                                               $b=$query2->result();
                                                                               array_push($c[$user_id1],$b);
                                                                           }else{
                                                                               array_push($b,"nouser");
                                                                           }
                                                                 }else{
                                                                          $c[$user_id1]=array();
                                                                          $query2 = $GLOBALS['$dbFramework']->query("call rolelevel_user(".$role_value.",'notcxo','user','".$userid."');");
                                                                          if($query2->num_rows()>0){
                                                                              $b=$query2->result();
                                                                              array_push($c[$user_id1],$b);
                                                                          }else{
                                                                              array_push($b,"nouser");
                                                                          }
                                                                    }
                                                            }
                                                         }
                                                  }
                                          }else{
                                                       array_push($a,"nouser");
                                          }
                                          return array(
                                              'users'=>$a,
                                              'reporting_users'=>$b,
                                              'users_rep'=>$c
                                          );
                    }else{

                        $update_query=$GLOBALS['$dbFramework']->query("update user_details set user_state=0,app_login_state=2  where user_id='".$userid."'");
                        /* update the module in client info this is required for module count maintaining code */
                        $clid = basename(dirname($_SERVER['SCRIPT_FILENAME']));
                        //$modquery=$GLOBALS['$dbFramework']->query("call updateclientmod('".$userid."','".$clid."','deactive');");

                        return array(
                            'users'=>$a,
                            'reporting_users'=>$b
                        );

                    }

              }else{

                $update_query=$GLOBALS['$dbFramework']->query("update user_details set user_state=0,app_login_state=2  where user_id='".$userid."'");
                $clid = basename(dirname($_SERVER['SCRIPT_FILENAME']));
                /* update the module in client info  this is required for module count maintaining code */
                //$modquery=$GLOBALS['$dbFramework']->query("call updateclientmod('".$userid."','".$clid."','deactive');");

                return array(
                    'users'=>$a,
                    'reporting_users'=>$b
                );

              }
        }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}

public function post_replacement_data($userid,$reportingdesg,$olduserID,$status,$data1,$remcnt){
   try{
            $clid = basename(dirname($_SERVER['SCRIPT_FILENAME']));
            if($status=='deactive'){

                    $update_query=$GLOBALS['$dbFramework']->query("update user_details set user_state=0,app_login_state=2  where user_id='".$olduserID."'");


                    /* update the module in client info this is required for module count maintaining code */
                    //$modquery=$GLOBALS['$dbFramework']->query("call updateclientmod('".$olduserID."','".$clid."','deactive');");

                    /* -------------------------------------------------------------------------------------------------------------------- */
                    $getdata=$GLOBALS['$dbFramework']->query("select * from user_details where reporting_to='".$olduserID."' and user_state=1  ");
                    $getdata->result();
                    if($getdata->num_rows()>0){
                          foreach ($getdata->result() as $row)
                          {
                               $user_id = $row->user_id;

                               $update_query=$GLOBALS['$dbFramework']->query("update user_details set reporting_to='".$userid."',reporting_desg='".$reportingdesg."'
                                                                                where user_id='".$user_id."'");
                          }

                    }
                    $arry1=array();
                    $getdata=$GLOBALS['$dbFramework']->query("select a.user_id from user_reporting a,user_details b where a.user_id=b.user_id and
                                                                 a.reporting_to='".$olduserID."' and b.user_state=1  ");
                    $getdata->result();
                    if($getdata->num_rows()>0){
                          foreach ($getdata->result() as $row)
                          {
                               $user_id = $row->user_id;

                               $update_query=$GLOBALS['$dbFramework']->query("update user_reporting set activebit=0
                                                                               where user_id='".$user_id."' ");

                               $arry=array(
                                    'user_id' =>$user_id,
                                    'reporting_to'=>$userid // selected replacement user

                               );
                               array_push($arry1, $arry);
                          }
                          $var = $GLOBALS['$dbFramework']->insert_batch('user_reporting',$arry1);
                          return $var;
                    }


            }else if($status=='active'){

                   
                    $chk_module=$GLOBALS['$dbFramework']->query("select * from user_licence where user_id='".$olduserID."' and (manager_module<>'0' or cxo_module<>'0')");
                    $chk_module->result();
                    if($chk_module->num_rows()>0){

                            if(intval($remcnt)>=0){
                                    $update_query=$GLOBALS['$dbFramework']->query("update user_details set user_state=1,app_login_state=0  where user_id='".$olduserID."'");
                                     /* this is required for module count maintaining code */
                                    //$modquery=$GLOBALS['$dbFramework']->query("call updateclientmod('".$olduserID."','".$clid."','active');");

                            }else{

                                    $query=$GLOBALS['$dbFramework']->query("SELECT * FROM user_licence where user_id='".$olduserID."'");
                                    $query->result_array();
                                    if($query->num_rows()>0){
                                            $update = $GLOBALS['$dbFramework']->update('user_licence' ,$data1, array('LOWER(user_id)' => strtolower($olduserID)));
                                     }else{
                                            $var = $GLOBALS['$dbFramework']->insert('user_licence',$data1);
                                    }
                                    $update_query=$GLOBALS['$dbFramework']->query("update user_details set user_state=1,app_login_state=0  where user_id='".$olduserID."'");

                                    /* this is required for module count maintaining code */
                                    //$modquery=$GLOBALS['$dbFramework']->query("call updateclientmod('".$olduserID."','".$clid."','active');");


                            }


                            $getdata=$GLOBALS['$dbFramework']->query("select * from user_details where reporting_to='".$olduserID."' and user_state=1  ");
                            $getdata->result();
                            if($getdata->num_rows()>0){
                                  foreach ($getdata->result() as $row)
                                  {
                                       $user_id = $row->user_id;

                                       $update_query=$GLOBALS['$dbFramework']->query("update user_details set reporting_to='".$userid."',reporting_desg='".$reportingdesg."'
                                                                                        where user_id='".$user_id."'");
                                  }

                            }else{
                                        $update_query=$GLOBALS['$dbFramework']->query("update user_details set reporting_to='".$userid."',reporting_desg='".$reportingdesg."'
                                                                                        where user_id='".$olduserID."'");
                            }
                            $arry1=array();
                            $getdata=$GLOBALS['$dbFramework']->query("select a.user_id from user_reporting a,user_details b where a.user_id=b.user_id and
                                                                         a.reporting_to='".$olduserID."' and b.user_state=1  ");
                            $getdata->result();
                            if($getdata->num_rows()>0){
                                  foreach ($getdata->result() as $row)
                                  {
                                       $user_id = $row->user_id;

                                       $update_query=$GLOBALS['$dbFramework']->query("update user_reporting set activebit=0
                                                                                       where user_id='".$user_id."' ");

                                       $arry=array(
                                            'user_id' =>$user_id,
                                            'reporting_to'=>$userid // selected replacement user

                                       );
                                       array_push($arry1, $arry);
                                  }
                                  $var = $GLOBALS['$dbFramework']->insert_batch('user_reporting',$arry1);
                                  return $var;
                            }else{
                                    $update_query=$GLOBALS['$dbFramework']->query("update user_reporting set activebit=0
                                                                                       where user_id='".$olduserID."' ");

                                    $insert_repto=$GLOBALS['$dbFramework']->query("insert into user_reporting (user_id,reporting_to)
                                                                        values ('".$olduserID."','".$userid."') ");
                                    return $insert_repto;
                            }
                    }else{

                        if(intval($remcnt)>=0){
                                    $update_query=$GLOBALS['$dbFramework']->query("update user_details set user_state=1,app_login_state=0  where user_id='".$olduserID."'");
                                    /* this is required for module count maintaining code */
                                    //$modquery=$GLOBALS['$dbFramework']->query("call updateclientmod('".$olduserID."','".$clid."','active');");

                        }else{
                                $query=$GLOBALS['$dbFramework']->query("SELECT * FROM user_licence where user_id='".$olduserID."'");
                                $query->result_array();
                                if($query->num_rows()>0){
                                        $update = $GLOBALS['$dbFramework']->update('user_licence' ,$data1, array('LOWER(user_id)' => strtolower($olduserID)));
                                 }else{
                                        $var = $GLOBALS['$dbFramework']->insert('user_licence',$data1);
                                }
                                $update_query=$GLOBALS['$dbFramework']->query("update user_details set user_state=1,app_login_state=0  where user_id='".$olduserID."'");

                                /* this is required for module count maintaining code */
                                //$modquery=$GLOBALS['$dbFramework']->query("call updateclientmod('".$olduserID."','".$clid."','active');");

                        }

                        $update_query=$GLOBALS['$dbFramework']->query("update user_details set reporting_to='".$userid."',reporting_desg='".$reportingdesg."'
                                                                                    where user_id='".$olduserID."'");
                        $update_query=$GLOBALS['$dbFramework']->query("update user_reporting set activebit=0
                                                                                       where user_id='".$olduserID."' ");

                        $insert_repto=$GLOBALS['$dbFramework']->query("insert into user_reporting (user_id,reporting_to)
                                                                        values ('".$olduserID."','".$userid."') ");
                        return $insert_repto;

                    }

             }

        }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}

public function check_active($userid,$department,$roleid,$remcnt){
  try{
                $a=array();
                $b=array();
                $clid = basename(dirname($_SERVER['SCRIPT_FILENAME']));
                $chk_module=$GLOBALS['$dbFramework']->query("select * from user_licence where user_id='".$userid."' and (manager_module<>'0' or cxo_module<>'0')");
                $chk_module->result();
                if($chk_module->num_rows()>0){

                        $get_repto=$GLOBALS['$dbFramework']->query("select reporting_to from user_details where user_id='".$userid."' ");
                        $get_repto->result();
                        if($get_repto->num_rows()>0){
                                foreach ($get_repto->result() as $row)
                                {
                                     $reporting_to = $row->reporting_to;
                                }
                                $chkactive=$GLOBALS['$dbFramework']->query("select * from user_details where user_id='".$reporting_to."' and user_state=1 ");
                                $chkactive->result();
                                if($chkactive->num_rows()>0){
                                    if(intval($remcnt) >=0){
                                            $update_query=$GLOBALS['$dbFramework']->query("update user_details set user_state=1,app_login_state=0  where user_id='".$userid."'");

                                         /* update the module in client info this is required for module count maintaining code */
                                            //$modquery=$GLOBALS['$dbFramework']->query("call updateclientmod('".$userid."','".$clid."','active');");
                                        /* -------------------------------------------------------------------------------------------------------------------- */
                                    }
                                    return 0;
                                }else{

                                      $getdata=$GLOBALS['$dbFramework']->query("select a.*,b.cxo_module,b.manager_module  from user_roles a,user_licence b,user_details c
                                                                                where c.user_id=b.user_id and a.role_id=c.designation and a.department_id='".$department."' and a.role_id='".$roleid."'
                                                                                and c.user_id='".$userid."' order by a.role_value;");

                                      $getdata->result();
                                      if($getdata->num_rows()>0){
                                            foreach ($getdata->result() as $row)
                                            {
                                                 $role_value = $row->role_value;
                                                 $cxo_module = $row->cxo_module;
                                            }
                                            if($cxo_module <> "0"){

                                                    $getuser=$GLOBALS['$dbFramework']->query("call rolelevel_user(".$role_value.",'cxo','','');");
                                                    return $getuser->result();
                                            }else{

                                                     $getuser=$GLOBALS['$dbFramework']->query("call rolelevel_user(".$role_value.",'notcxo','','');");
                                                     return $getuser->result();
                                            }


                                      }
                                }
                        }
                }else{
                        $get_repto=$GLOBALS['$dbFramework']->query("select reporting_to from user_details where user_id='".$userid."' ");
                        $get_repto->result();
                        if($get_repto->num_rows()>0){
                                foreach ($get_repto->result() as $row)
                                {
                                     $reporting_to = $row->reporting_to;
                                }
                                $chkactive=$GLOBALS['$dbFramework']->query("select * from user_details where user_id='".$reporting_to."' and user_state=1 ");
                                $chkactive->result();
                                if($chkactive->num_rows()>0){
                                    if(intval($remcnt) >=0){
                                            $update_query=$GLOBALS['$dbFramework']->query("update user_details set user_state=1,app_login_state=0  where user_id='".$userid."'");

                                        /* update the module in client info this is required for module count maintaining code*/
                                            //$modquery=$GLOBALS['$dbFramework']->query("call updateclientmod('".$userid."','".$clid."','active');");
                                        /* -------------------------------------------------------------------------------------------------------------------- */
                                    }
                                    return 0;
                                }else{


                                        $getdata=$GLOBALS['$dbFramework']->query("select a.*,b.cxo_module,b.manager_module  from user_roles a,user_licence b,user_details c
                                                                                where c.user_id=b.user_id and a.role_id=c.designation and a.department_id='".$department."' and a.role_id='".$roleid."'
                                                                                and c.user_id='".$userid."' order by a.role_value;");
                                        $getdata->result();
                                        if($getdata->num_rows()>0){
                                            foreach ($getdata->result() as $row)
                                            {
                                                 $role_value = $row->role_value;
                                                 $cxo_module = $row->cxo_module;
                                            }
                                        }
                                        if($cxo_module <> "0"){

                                                    $getuser=$GLOBALS['$dbFramework']->query("call rolelevel_user(".$role_value.",'cxo','','');");
                                                    return $getuser->result();
                                        }else{

                                                     $getuser=$GLOBALS['$dbFramework']->query("call rolelevel_user(".$role_value.",'notcxo','','');");
                                                     return $getuser->result();
                                        }

                                }
                        }
                }

       }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
       }
}

public function choose_replacement($user_id1){
       try{
             $a=array();
             $b=array();
             $que=$GLOBALS['$dbFramework']->query("call rolelevel_user(0,'reporting_desg','".$user_id1."','');");
                if($que->num_rows()>0){
                    foreach ($que->result() as $row)
                    {
                         $reporting_desg=$row->reporting_desg;
                         $user_id=$row->user_id;
                         $deptname=$row->deptname;
                         $rolename=$row->rolename;
                         $user_name=$row->user_name;

                         array_push($a,$user_id,$user_name,$deptname,$rolename);

                    }
                }
                /* if data found in array get users on top level */
                if(count($a)>0){
                        $que1=$GLOBALS['$dbFramework']->query("select * from user_roles where role_id='$reporting_desg';");
                         if($que1->num_rows()>0){
                            foreach ($que1->result() as $row1)
                            {
                                 $role_value=$row1->role_value;
                                 $query2 = $GLOBALS['$dbFramework']->query("call rolelevel_user(".$role_value.",'notcxo','role','".$user_id1."');");
                                 if($query2->num_rows()>0){
                                     $b=$query2->result();
                                 }else{
                                     array_push($b,"nouser");
                                 }

                            }
                         }

                }else{
                     array_push($a,"nouser");
                }
                return array(
                    'users'=>$a,
                    'reporting_users'=>$b
                );



       }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
       }
}

public function update_reportingdata($rep_arr,$olduserID,$type){
      try{

            /* update reporting to in user details */
                if($type=="deactive"){
                      $update_query=$GLOBALS['$dbFramework']->query("update user_details set user_state=0,app_login_state=2  where user_id='".$olduserID."'");
                      $clid = basename(dirname($_SERVER['SCRIPT_FILENAME']));
                      /* update the module in client info this is required for module count maintaining code */
                      //$modquery=$GLOBALS['$dbFramework']->query("call updateclientmod('".$olduserID."','".$clid."','deactive');");
                      /* -------------------------------------------------------------------------------------------------------------------- */


                }else if($type=="active"){
                    $update_query=$GLOBALS['$dbFramework']->query("update user_details set user_state=1,app_login_state=0  where user_id='".$olduserID."'");
                }
                $arry1=array();
                foreach ($rep_arr as  $value){

                        $userid=$value->user_id;
                        $reporting_to_id=$value->reporting_to_id;
                        $reporting_to_desg=$value->reporting_to_desg;

                        $updateque=$GLOBALS['$dbFramework']->query("update user_details set reporting_to='".$reporting_to_id."', reporting_desg='".$reporting_to_desg."' where user_id='".$userid."' and user_state=1");

                        $update_query=$GLOBALS['$dbFramework']->query("update user_reporting set activebit=0
                                                                               where user_id='".$userid."' ");

                        $arry=array(
                            'user_id' =>$userid,
                            'reporting_to'=>$reporting_to_id // selected replacement user

                        );
                        array_push($arry1, $arry);
                }
                $var = $GLOBALS['$dbFramework']->insert_batch('user_reporting',$arry1);
                return $type;

      }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
      }


}

public function check_forcxo($userid1,$add_role){
       try{
            /* check for cxo people below selected user */
            $query2 = $GLOBALS['$dbFramework']->query("SELECT a.user_id,a.user_name,b.manager_module
                                                        ,b.cxo_module FROM user_details a,user_licence b
                                                        WHERE a.reporting_to='".$userid1."' and
                                                        a.user_id=b.user_id and a.user_state=1 and
                                                        b.cxo_module<>'0';");
                                 if($query2->num_rows()>0){
                                     return 1;
                                 }else{
                                    return 0;
                                 }

      }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
      }
}

public function replacement_rolechange($userid1,$selmod,$add_role){
        $a=array();
        $b=array();
        $c=array();
        $str="";
        try{

              $que=$GLOBALS['$dbFramework']->query("select role_value from user_roles where role_id='".$add_role."' ");
              if($que->num_rows()>0){
                    foreach ($que->result() as $row)
                    {
                            $level_cnt=$row->role_value;

                    }
              }

              $que=$GLOBALS['$dbFramework']->query("select a.role_value,a.role_id from user_roles a, user_details b
                                                    where a.role_id=b.designation and b.user_id='".$userid1."'; ");
              if($que->num_rows()>0){
                    foreach ($que->result() as $row)
                    {
                            $new_levelcnt=$row->role_value;
                            $rolesid=$row->role_id;
                    }
              }

              /* get the reporting desg from user details  */
              $que=$GLOBALS['$dbFramework']->query("call rolelevel_user(0,'designation','".$userid1."','');");
              if($que->num_rows()>0){
                  foreach ($que->result() as $row)
                  {
                       $reporting_desg=$row->reporting_desg;
                       $user_id=$row->user_id;
                       $deptname=$row->deptname;
                       $rolename=$row->rolename;
                       $user_name=$row->user_name;

                       if($reporting_desg==""){
                              $reporting_desg=$this->session->userdata('uid'); /* id to be taken from session */
                       }

                       $que1=$GLOBALS['$dbFramework']->query("select * from user_roles where role_id='$reporting_desg';");
                       if($que1->num_rows()>0){
                          foreach ($que1->result() as $row1)
                          {
                               $role_value=$row1->role_value;
                                    /* compare the level count */
                                   if($level_cnt == $role_value){
                                       array_push($a,$user_id,$user_name,$deptname,$rolename);
                                   }else if($role_value==0){
                                       array_push($a,$user_id,$user_name,$deptname,$rolename);
                                       $str.="'".$user_id."'".",";
                                   }
                          }

                      }
                  }
                  if($str<>""){
                          $str=rtrim($str,",");
                  }
              }
              if($selmod==1){
                        /* get the reporting desg from user details  */
                        $que=$GLOBALS['$dbFramework']->query("call rolelevel_user(0,'designation','".$userid1."','');");
                        if($que->num_rows()>0){
                            foreach ($que->result() as $row)
                            {
                                 $reporting_desg=$row->reporting_desg;
                                 $user_id=$row->user_id;
                                 $deptname=$row->deptname;
                                 $rolename=$row->rolename;
                                 $user_name=$row->user_name;

                                 if($reporting_desg==""){
                                        $reporting_desg=$this->session->userdata('uid'); /* id to be taken from session */
                                 }

                                 $que1=$GLOBALS['$dbFramework']->query("select * from user_roles where role_id='$reporting_desg';");
                                 if($que1->num_rows()>0){
                                    foreach ($que1->result() as $row1)
                                    {
                                         $role_value=$row1->role_value;
                                             $c[$user_id]=array();
                                                            $query2 = $GLOBALS['$dbFramework']->query("call rolelevel_user(".$role_value.",'notcxo','user','".$userid1."'); ");
                                                            if($query2->num_rows()>0){
                                                                $b=$query2->result();
                                                                array_push($c[$user_id],$b);
                                                                array_push($a,$user_id,$user_name,$deptname,$rolename);
                                                            }

                                                       if($role_value==$level_cnt){
                                                            $str.="'".$user_id."'".",";
                                                       }
                                    }

                                }
                            }
                            if($str<>""){
                                    $str=rtrim($str,",");
                            }
                        }

              }
                /* if data found in array get users on top level */
                if(count($a)>0){

                              $query2 = $GLOBALS['$dbFramework']->query("call rolelevel_user(".$level_cnt.",'notcxo','user','".$userid1."'); ");
                              if($query2->num_rows()>0){
                                  $b=$query2->result();
                              }
                }
                return array(
                    'users'=>$a,
                    'reporting_users'=>$b,
                    'users_rep'=>$c
                );



        }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
        }
}

public function resend_mail($userid,$token){
    try{
                $query=$GLOBALS['$dbFramework']->query("update user_details set login_pwd=null,password_reset_token='".$token."'
                                                        where user_id='".$userid."' ");
                return true;
        }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
        }
}

//----------To Update APP LOGIN STATE IN User Details----------------------//
public function applock($bit,$userid) {
       try{
                    $update = $GLOBALS['$dbFramework']->query("update user_details set app_login_state=".$bit."
                                                        where user_id='".$userid."' ");
                    return $bit;
       }catch (LConnectApplicationException $e){
                              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                              throw $e;
                         }
}
public function appreset($userid) {
	try{
		$del= $GLOBALS['$dbFramework']->query("delete from user_app_details where user_id='".$userid."' ");
		$update = $GLOBALS['$dbFramework']->query("update user_details set app_login_state=0
											where user_id='".$userid."' ");
		return 1;
	}catch (LConnectApplicationException $e){
		$GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
		throw $e;
	}
}

    public function update_user_emails($user_personal_eId,$userid)
    {   
        try{     
            $update = $GLOBALS['$dbFramework']->query("UPDATE user_email_settings set email_id='$user_personal_eId', password = '' where user_id='$userid'");
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }


}


?>