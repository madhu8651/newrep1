<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_rolesHierarchyModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class admin_rolesHierarchyModel extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    public function get_user_roles(){
        try{
                $query = $GLOBALS['$dbFramework']->query("select a.Department_name as deptname,b.role_id as roleid,b.role_name as rolename,b.role_value
                                    from department a,user_roles b
                                        where a.Department_id=b.department_id
                                    and ISNULL(NULLIF(b.role_key,''))
                                                            order by b.role_name");
                return $query->result();
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function get_user_rolesE($role_value){
        try{
                $a=$b=array();
                $query = $GLOBALS['$dbFramework']->query("select a.Department_name as deptname,b.role_id as roleid,b.role_name as rolename,b.role_value
                                    from department a,user_roles b
                                        where a.Department_id=b.department_id
                                    and (ISNULL(NULLIF(b.role_key,'')) or b.role_value>0)  and b.role_value<>".$role_value."
                                                            order by b.role_value;");
                $a=$query->result();

                $query = $GLOBALS['$dbFramework']->query("select a.Department_name as deptname,b.role_id as roleid,b.role_name as rolename,b.role_value
                                    from department a,user_roles b
                                        where a.Department_id=b.department_id
                                    and (ISNULL(NULLIF(b.role_key,'')) or b.role_value>0)  and b.role_value=".$role_value."
                                                            order by b.role_value;");
                $b=$query->result();
                return array(
                       'selrow_role'=>$a,
                       'chk_role'=>$b
                );
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function get_levels(){
        try{
                $query = $GLOBALS['$dbFramework']->query("select distinct role_value from user_roles where role_value>0 order by role_value ;");
                return $query->result();
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function get_levelcount(){
            $a=array();
        try{
                  $que=$GLOBALS['$dbFramework']->query("select distinct role_value from user_roles where role_value<>0  order by role_value");
                  $arr=$que->result_array();
                  for($i=0;$i<count($arr);$i++){

                      $roleval=$arr[$i]['role_value'];
                      $a[$i]['role_value']=$arr[$i]['role_value'];

                      $que1=$GLOBALS['$dbFramework']->query("select role_id from user_roles where role_value='$roleval'");
                      $arr1=$que1->result_array();
                      for($i1=0;$i1<count($arr1);$i1++){

                          $role_id=$arr1[$i1]['role_id'];


                          $que2=$GLOBALS['$dbFramework']->query("select a.Department_name as deptname,b.role_id as roleid,b.role_name as rolename
                                                      from department a,user_roles b where a.Department_id=b.department_id and b.role_id='$role_id'
                                                  order by b.role_name");
                          $arr2=$que2->result_array();
                          for($i2=0;$i2<count($arr2);$i2++){

                               $str="";
                               $str=$arr2[$i2]['rolename']." (".$arr2[$i2]['deptname'].")";
                               $a[$i]['levelname'][$i1]['attribute_name']=$str;
                               $a[$i]['levelname'][$i1]['role_id']=$arr1[$i1]['role_id'];
                          }
                      }
                  }

                  $que=$GLOBALS['$dbFramework']->query("select max(role_value)as role_value from user_roles");
                  if($que->num_rows()>0){

                          foreach ($que->result() as $row)
                          {
                                  $maxid = $row->role_value;
                                  $maxid=$maxid+1;
                          }
                  }else{
                     $maxid=1;
                  }
                 return array(
                  'records' => $a,
                  'str' => $maxid,
              );

         }
         catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }

    }

    public function update_hierarchy($rolesid,$level_cnt,$status){

        if($status=='before' && $level_cnt>0){
              $newcnt=$level_cnt;
              $role_val1=0;
              $que=$GLOBALS['$dbFramework']->query("select * from user_roles where role_value>='$level_cnt' order by role_value ;");
              if($que->num_rows()>0){
                  foreach ($que->result() as $row)
                  {
                          $role_value=$row->role_value;

                          $id=$row->id;
                          $role_id=$row->role_id;

                          if($role_val1!=$role_value){
                            $a=$role_value+1;
                          }

                          $GLOBALS['$dbFramework']->query("update user_roles set role_key='level',role_value='$a' where id='$id' and role_id='$role_id'");

                          $role_val1=$role_value;
                  }
              }
        }

        if($status=='after' && $level_cnt>0){

            $role_val1=0;
            $que=$GLOBALS['$dbFramework']->query("select * from user_roles where role_value>'$level_cnt' order by role_value ;");
              if($que->num_rows()>0){
                  foreach ($que->result() as $row)
                  {
                          $role_value=$row->role_value;

                          $id=$row->id;
                          $role_id=$row->role_id;

                          if($role_val1!=$role_value){
                            $a=$role_value+1;
                          }

                          $GLOBALS['$dbFramework']->query("update user_roles set role_key='level',role_value='$a' where id='$id' and role_id='$role_id'");

                          $role_val1=$role_value;
                  }
              }
              $level_cnt=$level_cnt+1;
        }

        if($rolesid){
           $a = (array)$rolesid;// convert the object into array
           if($level_cnt==0){
             $level_cnt=1;
           }
            try{
                    foreach($a as $k => $v)
                    {
                        $arr_val= $a[$k]; // replace the comma with colon
                        $GLOBALS['$dbFramework']->query("update user_roles set role_key='level',role_value='$level_cnt' where role_id='$arr_val'");

                    }
            }
            catch (LConnectApplicationException $e)
            {
                  $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                  throw $e;
            }
        }
        return true;
    }

    public function update_hierarchy1($rolesid,$level_cnt,$new_levelcnt,$chk){
        $a=array();
        $b=array();
        $c=array();
        $str="";
        $str1="";
        try{
                if($level_cnt==1){

                        $que=$GLOBALS['$dbFramework']->query("SELECT a.user_id,a.user_name,b.manager_module,b.cxo_module FROM user_details a,user_licence b
                                                            WHERE a.designation='".$rolesid."' and a.user_id=b.user_id and a.user_state=1 and
                                                                ( b.sales_module<>'0');");
                        if($que->num_rows()>0){

                            foreach ($que->result() as $row1)
                            {
                                         $user_id=$row1->user_id;
                                         $que1=$GLOBALS['$dbFramework']->query("SELECT a.user_id,a.user_name,b.manager_module,b.cxo_module FROM user_details a,user_licence b
                                                            WHERE a.designation='".$rolesid."' and a.user_id=b.user_id and
                                                            b.user_id='".$user_id."' and a.user_state=1 and (b.manager_module<>'0' or b.cxo_module<>'0');");
                                        if($que1->num_rows()==0){
                                             return "cannot_move";
                                        }else{
                                          return array(
                                              'users'=>$a,
                                              'reporting_users'=>$b
                                          );
                                        }
                            }
                        }else{
                             return array(
                                              'users'=>$a,
                                              'reporting_users'=>$b
                             );

                        }

                }else{
                        /* moving roles bottom to top  */
                        if( $new_levelcnt > $level_cnt){
                            $que=$GLOBALS['$dbFramework']->query("select c.reporting_desg,c.user_id,c.user_name,(select Department_name from department b where b.Department_id=c.department) as deptname,
                                                                    (select role_name from user_roles b where b.role_id=c.designation) as rolename,a.cxo_module
                                                                    from user_details c,user_licence a where a.user_id=c.user_id and c.designation='".$rolesid."' and c.user_state=1;");
                                  if($que->num_rows()>0){
                                      foreach ($que->result() as $row)
                                      {
                                           $reporting_desg=$row->reporting_desg;
                                           $user_id=$row->user_id;
                                           $deptname=$row->deptname;
                                           $rolename=$row->rolename;
                                           $user_name=$row->user_name;
                                           $cxo_module=$row->cxo_module;

                                           if($reporting_desg==""){
                                                  $reporting_desg=$this->session->userdata('uid'); /* id to be taken from session */
                                           }

                                           $que1=$GLOBALS['$dbFramework']->query("select * from user_roles where role_id='$reporting_desg';");
                                           if($que1->num_rows()>0){
                                              foreach ($que1->result() as $row1)
                                              {
                                                   $role_value=$row1->role_value;
                                                        /* compare the level count */
                                                       if($level_cnt <= $role_value){
                                                                 array_push($a,$user_id,$user_name,$deptname,$rolename);
                                                                 $c[$user_id]=array();
                                                                 if($cxo_module <>"0"){
                                                                      $query2 = $GLOBALS['$dbFramework']->query("select b.user_id,b.user_name,b.designation,a.role_name,
                                                                                                    (select Department_name from department where Department_id=b.department) as deptname
                                                                                                    from user_roles a,user_details b,user_licence c
                                                                                                    where a.role_id=b.designation and b.user_id=c.user_id and b.user_state=1 and
                                                                                                    (c.cxo_module<>'0')and a.role_value < ".$level_cnt."
                                                                                                    and a.department_id!=''
                                                                                                    and a.role_key is not null; ");
                                                                      if($query2->num_rows()>0){
                                                                        $b=$query2->result();
                                                                        array_push($c[$user_id],$b);
                                                                      }

                                                                 }else{

                                                                      $query2 = $GLOBALS['$dbFramework']->query("select b.user_id,b.user_name,b.designation,a.role_name,
                                                                                                    (select Department_name from department where Department_id=b.department) as deptname
                                                                                                    from user_roles a,user_details b,user_licence c
                                                                                                    where a.role_id=b.designation and b.user_id=c.user_id and b.user_state=1 and
                                                                                                    (c.manager_module<>'0' or c.cxo_module<>'0')and a.role_value < ".$level_cnt."
                                                                                                    and a.department_id!=''
                                                                                                    and a.role_key is not null; ");
                                                                      if($query2->num_rows()>0){
                                                                        $b=$query2->result();
                                                                        array_push($c[$user_id],$b);
                                                                      }
                                                                 }
                                                       }else if($role_value==0){
                                                                array_push($a,$user_id,$user_name,$deptname,$rolename);
                                                                $str.="'".$user_id."'".",";
                                                                $c[$user_id]=array();
                                                                 if($cxo_module <>"0"){
                                                                        $str1=$str;
                                                                        if($str1<>""){
                                                                            $str1=rtrim($str1,",");
                                                                        }
                                                                        $query2 = $GLOBALS['$dbFramework']->query("select b.user_id,b.user_name,b.designation,a.role_name,
                                                                                                                  (select Department_name from department where Department_id=b.department) as deptname
                                                                                                                  from user_roles a,user_details b,user_licence c
                                                                                                                  where a.role_id=b.designation and b.user_id=c.user_id and b.user_state=1 and
                                                                                                                  (c.cxo_module<>'0')and a.role_value < ".$level_cnt."
                                                                                                                  and a.department_id!='' and b.user_id not in (".$str1.")
                                                                                                                  and a.role_key is not null; ");
                                                                        if($query2->num_rows()>0){
                                                                          $b=$query2->result();
                                                                          array_push($c[$user_id],$b);
                                                                        }

                                                                 }else{

                                                                        $str1=$str;
                                                                        if($str1<>""){
                                                                            $str1=rtrim($str1,",");
                                                                        }

                                                                        $query2 = $GLOBALS['$dbFramework']->query("select b.user_id,b.user_name,b.designation,a.role_name,
                                                                                                                  (select Department_name from department where Department_id=b.department) as deptname
                                                                                                                  from user_roles a,user_details b,user_licence c
                                                                                                                  where a.role_id=b.designation and b.user_id=c.user_id and b.user_state=1 and
                                                                                                                  (c.manager_module<>'0' or c.cxo_module<>'0')and a.role_value < ".$level_cnt."
                                                                                                                  and a.department_id!='' and b.user_id not in (".$str1.")
                                                                                                                  and a.role_key is not null; ");
                                                                        if($query2->num_rows()>0){
                                                                          $b=$query2->result();
                                                                          array_push($c[$user_id],$b);
                                                                        }
                                                                 }
                                                       }
                                              }
                                          }
                                      }
                                  }
                                  return array(
                                      'users'=>$a,
                                      'reporting_users'=>$b,
                                      'users_rep'=>$c
                                  );
                            }

                            /* moving level top to bottom */
                            /* =============================================================================================================== */
                            if( $new_levelcnt < $level_cnt){
                            $que=$GLOBALS['$dbFramework']->query("select c.reporting_desg,c.user_id,c.user_name,(select Department_name from department b where b.Department_id=c.department) as deptname,
                                                                    (select role_name from user_roles b where b.role_id=c.designation) as rolename,a.cxo_module
                                                                    from user_details c,user_licence a where a.user_id=c.user_id and c.designation='".$rolesid."' and c.user_state=1;");
                                  if($que->num_rows()>0){
                                      foreach ($que->result() as $row)
                                      {
                                           $reporting_desg=$row->reporting_desg;
                                           $user_id=$row->user_id;
                                           $deptname=$row->deptname;
                                           $rolename=$row->rolename;
                                           $user_name=$row->user_name;
                                           $cxo_module=$row->cxo_module;

                                           if($reporting_desg==""){
                                                  $reporting_desg=$this->session->userdata('uid'); /* id to be taken from session */
                                           }

                                           $que1=$GLOBALS['$dbFramework']->query("select * from user_roles where role_id='$reporting_desg';");
                                           if($que1->num_rows()>0){
                                              foreach ($que1->result() as $row1)
                                              {
                                                   $role_value=$row1->role_value;
                                                        /* compare the level count */
                                                       if($level_cnt <= $role_value){
                                                           array_push($a,$user_id,$user_name,$deptname,$rolename);
                                                           $str.="'".$user_id."'".",";
                                                           $c[$user_id]=array();
                                                                 if($cxo_module <>"0"){
                                                                        $str1=$str;
                                                                        if($str1<>""){
                                                                            $str1=rtrim($str1,",");
                                                                        }
                                                                        $query2 = $GLOBALS['$dbFramework']->query("select b.user_id,b.user_name,b.designation,a.role_name,
                                                                                                                  (select Department_name from department where Department_id=b.department) as deptname
                                                                                                                  from user_roles a,user_details b,user_licence c
                                                                                                                  where a.role_id=b.designation and b.user_id=c.user_id and b.user_state=1 and
                                                                                                                  (c.cxo_module<>'0')and a.role_value < ".$level_cnt."
                                                                                                                  and a.department_id!='' and b.user_id not in (".$str1.")
                                                                                                                  and a.role_key is not null; ");
                                                                        if($query2->num_rows()>0){
                                                                          $b=$query2->result();
                                                                          array_push($c[$user_id],$b);
                                                                        }

                                                                 }else{
                                                                        $str1=$str;
                                                                        if($str1<>""){
                                                                            $str1=rtrim($str1,",");
                                                                        }
                                                                        $query2 = $GLOBALS['$dbFramework']->query("select b.user_id,b.user_name,b.designation,a.role_name,
                                                                                                                  (select Department_name from department where Department_id=b.department) as deptname
                                                                                                                  from user_roles a,user_details b,user_licence c
                                                                                                                  where a.role_id=b.designation and b.user_id=c.user_id and b.user_state=1 and
                                                                                                                  (c.manager_module<>'0' or c.cxo_module<>'0')and a.role_value < ".$level_cnt."
                                                                                                                  and a.department_id!='' and b.user_id not in (".$str1.")
                                                                                                                  and a.role_key is not null; ");
                                                                        if($query2->num_rows()>0){
                                                                          $b=$query2->result();
                                                                          array_push($c[$user_id],$b);
                                                                        }
                                                                 }
                                                       }else if($role_value==0){
                                                                 $c[$user_id]=array();
                                                                 array_push($a,$user_id,$user_name,$deptname,$rolename);
                                                                 $str.="'".$user_id."'".",";
                                                                 if($cxo_module <>"0"){
                                                                              $str1=$str;
                                                                              if($str1<>""){
                                                                                  $str1=rtrim($str1,",");
                                                                              }
                                                                              $query2 = $GLOBALS['$dbFramework']->query("select b.user_id,b.user_name,b.designation,a.role_name,
                                                                                                                        (select Department_name from department where Department_id=b.department) as deptname
                                                                                                                        from user_roles a,user_details b,user_licence c
                                                                                                                        where a.role_id=b.designation and b.user_id=c.user_id and b.user_state=1 and
                                                                                                                        (c.cxo_module<>'0')and a.role_value < ".$level_cnt."
                                                                                                                        and a.department_id!='' and b.user_id not in (".$str1.")
                                                                                                                        and a.role_key is not null; ");
                                                                              if($query2->num_rows()>0){
                                                                                $b=$query2->result();
                                                                                array_push($c[$user_id],$b);
                                                                              }

                                                                 }else{
                                                                              $str1=$str;
                                                                              if($str1<>""){
                                                                                  $str1=rtrim($str1,",");
                                                                              }
                                                                              $query2 = $GLOBALS['$dbFramework']->query("select b.user_id,b.user_name,b.designation,a.role_name,
                                                                                                                        (select Department_name from department where Department_id=b.department) as deptname
                                                                                                                        from user_roles a,user_details b,user_licence c
                                                                                                                        where a.role_id=b.designation and b.user_id=c.user_id and b.user_state=1 and
                                                                                                                        (c.manager_module<>'0' or c.cxo_module<>'0')and a.role_value < ".$level_cnt."
                                                                                                                        and a.department_id!='' and b.user_id not in (".$str1.")
                                                                                                                        and a.role_key is not null; ");
                                                                              if($query2->num_rows()>0){
                                                                                $b=$query2->result();
                                                                                array_push($c[$user_id],$b);
                                                                              }
                                                                 }
                                                       }
                                              }

                                          }
                                      }

                                  }
                                  /* ====================================================================================================== */

                                  $que=$GLOBALS['$dbFramework']->query("select u.designation as reporting_desg,u.user_id,u.user_name,(select Department_name from department b where b.Department_id=u.department) as deptname,
                                                                        (select role_name from user_roles b where b.role_id=u.designation) as rolename,d.cxo_module
                                                                        from user_details u,user_roles c,user_licence d where u.reporting_desg='$rolesid'
                                                                         and u.user_id=d.user_id and u.designation=c.role_id and c.role_value<=".$level_cnt."  and u.user_state=1;");
                                  if($que->num_rows()>0){
                                      foreach ($que->result() as $row)
                                      {
                                           $reporting_desg=$row->reporting_desg;
                                           $user_id=$row->user_id;
                                           $deptname=$row->deptname;
                                           $rolename=$row->rolename;
                                           $user_name=$row->user_name;
                                           $cxo_module=$row->cxo_module;

                                           if($reporting_desg==""){
                                                  $reporting_desg=$this->session->userdata('uid'); /* id to be taken from session */
                                           }
                                           $que1=$GLOBALS['$dbFramework']->query("select * from user_roles where role_id='$reporting_desg';");
                                           if($que1->num_rows()>0){
                                              foreach ($que1->result() as $row1)
                                              {
                                                   $role_value=$row1->role_value;
                                                       $c[$user_id]=array();
                                                            if($cxo_module <>"0"){
                                                                    $query2 = $this->db->query("select b.user_id,b.user_name,b.designation,a.role_name,
                                                                                      (select Department_name from department where Department_id=b.department) as deptname
                                                                                      from user_roles a,user_details b,user_licence c
                                                                                      where a.role_id=b.designation and b.user_id=c.user_id and b.user_state=1 and
                                                                                      (c.cxo_module<>'0')and a.role_value < ".$role_value."
                                                                                      and a.department_id!='' and a.role_id<>'".$rolesid."'
                                                                                      and a.role_key is not null; ");
                                                                    if($query2->num_rows()>0){
                                                                        $b=$query2->result();
                                                                        array_push($c[$user_id],$b);
                                                                    }
                                                            }else{
                                                                    $query2 =$this->db->query("select b.user_id,b.user_name,b.designation,a.role_name,
                                                                                      (select Department_name from department where Department_id=b.department) as deptname
                                                                                      from user_roles a,user_details b,user_licence c
                                                                                      where a.role_id=b.designation and b.user_id=c.user_id and b.user_state=1 and
                                                                                      (c.manager_module<>'0' or c.cxo_module<>'0')and a.role_value < ".$role_value."
                                                                                      and a.department_id!='' and a.role_id<>'".$rolesid."'
                                                                                      and a.role_key is not null; ");
                                                                    if($query2->num_rows()>0){
                                                                          $b=$query2->result();
                                                                          array_push($c[$user_id],$b);
                                                                    }
                                                            }
                                                       if($role_value==$level_cnt){
                                                            $str.="'".$user_id."'".",";
                                                        }
                                                        $nwrol=$role_value;
                                              }
                                           }
                                           array_push($a,$user_id,$user_name,$deptname,$rolename);
                                      }

                                  }

                                  return array(
                                      'users'=>$a,
                                      'reporting_users'=>$b,
                                      'users_rep'=>$c
                                  );
                            }
                            if($new_levelcnt == $level_cnt){
                                return array(
                                        'users'=>$a,
                                        'reporting_users'=>$b
                                    );
                            }
                }
        }
        catch (LConnectApplicationException $e)
        {
                  $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                  throw $e;
        }

    }


    public function move_roledata($rolesid,$level_cnt,$new_levelcnt){
        /* update new level to selected role  */
        $arr=array();
        $arry1=array();
        $msg="";
          try{

              if($level_cnt==1){
                    $GLOBALS['$dbFramework']->query("update user_roles set role_key='level',role_value='$level_cnt' where role_id='$rolesid'");
                    $sessionid=$this->session->userdata('uid'); /* id to be taken from session */
                    $que=$GLOBALS['$dbFramework']->query("select * from user_details where designation='".$rolesid."';");
                        if($que->num_rows()>0){
                            foreach ($que->result() as $row)
                            {
                                  $userid=$row->user_id;
                                  $updateque=$GLOBALS['$dbFramework']->query("update user_details set reporting_to='".$sessionid."', reporting_desg='' where user_id='".$userid."'");
                                  $update_query=$GLOBALS['$dbFramework']->query("update user_reporting set activebit=0
                                                                               where user_id='".$userid."' ");

                                  $arry=array(
                                      'user_id' =>$userid,
                                      'reporting_to'=>$sessionid // selected replacement user

                                  );
                                  array_push($arry1, $arry);

                            }
                        }
                        $var = $GLOBALS['$dbFramework']->insert_batch('user_reporting',$arry1);
              }/*else{*/
                       $GLOBALS['$dbFramework']->query("update user_roles set role_key='level',role_value='$level_cnt' where role_id='$rolesid'");
                      /* check if all levels are in correct order else reset  */
                        $str1="";

                        $que1=$GLOBALS['$dbFramework']->query("select * from user_roles where role_value='$new_levelcnt' order by role_value ;");
                        if($que1->num_rows()==0){
                                $role_val1=0;
                                $que=$GLOBALS['$dbFramework']->query("select * from user_roles where role_value>=".$new_levelcnt."  order by role_value ;");
                                if($que->num_rows()>0){
                                    foreach ($que->result() as $row)
                                    {
                                            $role_value=$row->role_value;
                                            $user_id=$row->user_id;

                                            $id=$row->id;
                                            $role_id=$row->role_id;

                                            if($role_val1!=$role_value){
                                              $a=$role_value-1;
                                            }
                                            if($a==1){
                                                $que1=$this->db->query("SELECT a.user_id from user_details a where a.designation='".$role_id."'");
                                                if($que1->num_rows()>0){
                                                    foreach ($que1->result() as $row1)
                                                    {
                                                                 $user_id=$row1->user_id;
                                                                 array_push($arr,$user_id);
                                                    }
                                                }
                                               /* check for sales module for level 1 users */
                                                $que1=$GLOBALS['$dbFramework']->query("SELECT a.user_id,a.user_name,b.manager_module,b.cxo_module FROM user_details a,user_licence b
                                                                WHERE a.designation='".$role_id."' and a.user_id=b.user_id and a.user_state=1 and
                                                                    ( b.sales_module<>'0');");
                                                if($que1->num_rows()>0){

                                                    foreach ($que1->result() as $row1)
                                                    {
                                                                 $user_id=$row1->user_id;
                                                                 $que11=$GLOBALS['$dbFramework']->query("SELECT a.user_id,a.user_name,b.manager_module,b.cxo_module FROM user_details a,user_licence b
                                                                                    WHERE a.designation='".$role_id."' and a.user_id=b.user_id and
                                                                                    b.user_id='".$user_id."' and a.user_state=1 and (b.manager_module<>'0' or b.cxo_module<>'0');");
                                                                if($que11->num_rows()==0){
                                                                     $msg="cannot_move";
                                                                }
                                                    }
                                                }


                                            }
                                            $str1.="role_key='level',role_value='$a' where id='$id' and role_id='$role_id'"."," ;
                                            //$GLOBALS['$dbFramework']->query("update user_roles set role_key='level',role_value='$a' where id='$id' and role_id='$role_id'");
                                            $role_val1=$role_value;
                                    }
                                }
                      /*  } */
                            if($msg==""){

                                      $str1=rtrim($str1,",");
                                      $ar=explode(",",$str1);
                                      for($w=0;$w<count($ar);$w++){
                                          $v=$ar[$w];
                                         $this->db->query("update user_roles set $v");

                                      }

                                      if(count($arr) >0){
                                          $sessionid=$this->session->userdata('uid'); /* id to be taken from session */
                                          for($x=0; $x<count($arr);$x++){
                                               $userid=$arr[$x];
                                               $updateque=$GLOBALS['$dbFramework']->query("update user_details set reporting_to='".$sessionid."', reporting_desg='' where user_id='".$userid."'");
                                               $update_query=$GLOBALS['$dbFramework']->query("update user_reporting set activebit=0
                                                                                             where user_id='".$userid."' ");

                                                $arry=array(
                                                    'user_id' =>$userid,
                                                    'reporting_to'=>$sessionid // selected replacement user

                                                );
                                                array_push($arry1, $arry);
                                          }
                                          $var = $GLOBALS['$dbFramework']->insert_batch('user_reporting',$arry1);
                                      }

                            }else{
                                    $GLOBALS['$dbFramework']->query("update user_roles set role_key='level',role_value='$new_levelcnt' where role_id='$rolesid'");
                            }
              }
              return $msg;

          }
          catch (LConnectApplicationException $e)
          {
                  $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                  throw $e;
          }

    }

    public function update_reportingdata($rolesid,$level_cnt,$rep_arr,$new_levelcnt){
         try{

                /* update reporting to in user details */
                $arry1=array();
                foreach ($rep_arr as  $value) {

                        $userid=$value->user_id;
                        $reporting_to_id=$value->reporting_to_id;
                        $reporting_to_desg=$value->reporting_to_desg;

                        $updateque=$GLOBALS['$dbFramework']->query("update user_details set reporting_to='".$reporting_to_id."', reporting_desg='".$reporting_to_desg."' where user_id='".$userid."'");

                        $update_query=$GLOBALS['$dbFramework']->query("update user_reporting set activebit=0
                                                                               where user_id='".$userid."' ");

                        $arry=array(
                            'user_id' =>$userid,
                            'reporting_to'=>$reporting_to_id // selected replacement user

                        );
                        array_push($arry1, $arry);
                }
                $var = $GLOBALS['$dbFramework']->insert_batch('user_reporting',$arry1);


                /* --- update the role level in user roles */
                $GLOBALS['$dbFramework']->query("update user_roles set role_key='level',role_value='$level_cnt' where role_id='$rolesid'");


                /* check if all levels are in correct order else reset  */
                $que1=$GLOBALS['$dbFramework']->query("select * from user_roles where role_value='$new_levelcnt' order by role_value ;");
                if($que1->num_rows()==0){
                        $role_val1=0;
                        $que=$GLOBALS['$dbFramework']->query("select * from user_roles where role_value>='$new_levelcnt' order by role_value ;");
                        if($que->num_rows()>0){
                            foreach ($que->result() as $row)
                            {
                                    $role_value=$row->role_value;

                                    $id=$row->id;
                                    $role_id=$row->role_id;

                                    if($role_val1!=$role_value){
                                      $a=$role_value-1;
                                    }

                                    $GLOBALS['$dbFramework']->query("update user_roles set role_key='level',role_value='$a' where id='$id' and role_id='$role_id'");

                                    $role_val1=$role_value;
                            }
                        }
                }
                return true;
         }
         catch (LConnectApplicationException $e)
         {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                    throw $e;
         }
    }

    public function update_reportingdata1($rolesid,$level_cnt,$rep_arr,$new_levelcnt,$status){
        try{
                if($status=='before' && $level_cnt>0){
                      $newcnt=$level_cnt;
                      $role_val1=0;
                      /* get the count of role */
                      $quer=$GLOBALS['$dbFramework']->query("select count(*) as cnt from user_roles where role_value=".$new_levelcnt." order by role_value ;");
                      if($quer->num_rows()>0){
                          foreach ($quer->result() as $rowr)
                          {
                                $cnt=$rowr->cnt;
                          }
                      }

                      $que=$GLOBALS['$dbFramework']->query("select * from user_roles where role_value>='$level_cnt' order by role_value ;");
                      if($que->num_rows()>0){
                          foreach ($que->result() as $row)
                          {
                                  $role_value=$row->role_value;

                                  $id=$row->id;
                                  $role_id=$row->role_id;

                                  if($role_val1!=$role_value){
                                    $a=$role_value+1;
                                  }

                                  $GLOBALS['$dbFramework']->query("update user_roles set role_key='level',role_value='$a' where id='$id' and role_id='$role_id'");

                                  $role_val1=$role_value;
                          }
                      }
                }

                if($status=='after' && $level_cnt>0){

                    $role_val1=0;
                    /* get the count of role */
                      $quer=$GLOBALS['$dbFramework']->query("select count(*) as cnt from user_roles where role_value=".$new_levelcnt." order by role_value ;");
                      if($quer->num_rows()>0){
                          foreach ($quer->result() as $rowr)
                          {
                                $cnt=$rowr->cnt;
                          }
                      }
                    $que=$GLOBALS['$dbFramework']->query("select * from user_roles where role_value>'$level_cnt' order by role_value ;");
                      if($que->num_rows()>0){
                          foreach ($que->result() as $row)
                          {
                                  $role_value=$row->role_value;

                                  $id=$row->id;
                                  $role_id=$row->role_id;

                                  if($role_val1!=$role_value){
                                    $a=$role_value+1;
                                  }

                                  $GLOBALS['$dbFramework']->query("update user_roles set role_key='level',role_value='$a' where id='$id' and role_id='$role_id'");

                                  $role_val1=$role_value;
                          }
                      }
                      $level_cnt=$level_cnt+1;
                }

                if($rolesid){
                   $a = (array)$rolesid;// convert the object into array
                   if($level_cnt==0){
                     $level_cnt=1;
                   }

                            foreach($a as $k => $v)
                            {
                                $arr_val= $a[$k]; // replace the comma with colon
                                $GLOBALS['$dbFramework']->query("update user_roles set role_key='level',role_value='$level_cnt' where role_id='$arr_val'");

                            }

                }

                if($cnt==1){
                     $a1=0;
                     $que=$GLOBALS['$dbFramework']->query("select * from user_roles where role_value>=1 order by role_value ;");
                      if($que->num_rows()>0){
                          foreach ($que->result() as $row)
                          {
                                  $role_value=$row->role_value;

                                  $id=$row->id;
                                  $role_id=$row->role_id;

                                  if($role_val1!=$role_value){
                                    $a=$a1+1;
                                    $a1++; 
                                  }

                                  $GLOBALS['$dbFramework']->query("update user_roles set role_key='level',role_value='$a' where id='$id' and role_id='$role_id'");

                                  $role_val1=$role_value;
                          }
                      }
                }

                /* update reporting to in user details */
                $arry1=array();
                foreach ($rep_arr as  $value) {

                        $userid=$value->user_id;
                        $reporting_to_id=$value->reporting_to_id;
                        $reporting_to_desg=$value->reporting_to_desg;

                        $updateque=$GLOBALS['$dbFramework']->query("update user_details set reporting_to='".$reporting_to_id."', reporting_desg='".$reporting_to_desg."' where user_id='".$userid."'");

                        $update_query=$GLOBALS['$dbFramework']->query("update user_reporting set activebit=0
                                                                               where user_id='".$userid."' ");

                        $arry=array(
                            'user_id' =>$userid,
                            'reporting_to'=>$reporting_to_id // selected replacement user

                        );
                        array_push($arry1, $arry);
                }
                $var = $GLOBALS['$dbFramework']->insert_batch('user_reporting',$arry1);
                return true;
        }
        catch (LConnectApplicationException $e)
        {
              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
              throw $e;
        }

    }


    public function remove_role($rolesid){
         try{
                 $GLOBALS['$dbFramework']->query("update user_roles set role_key=null,role_value='0' where role_id='$rolesid'");
                 return true;
         }
         catch (LConnectApplicationException $e)
         {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                    throw $e;
         }
    }
    public function update_roworder($orderselected){

            $j=0;
            $role_val1=0;
            $attr = (array)$orderselected;// convert the object into array
     try{
                foreach($attr as $key => $val)
                {
                        $roleid=$key;
                        $role_val=$attr[$key];

                        if($role_val1!=$role_val){
                            $j++;
                        }

                        $que=$GLOBALS['$dbFramework']->query("select * from user_roles where role_id='$roleid' and role_value='$role_val'");
                        if($que->num_rows()>0){
                            foreach ($que->result() as $row)
                            {
                                    $id=$row->id;
                                    $updateque1=$GLOBALS['$dbFramework']->query("update user_roles set role_value='$j' where id='$id'");
                            }
                        }
                        $role_val1=$role_val;

                }
                return TRUE;
          }
          catch (LConnectApplicationException $e)
          {
                  $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                  throw $e;
          }

    }


}

?>