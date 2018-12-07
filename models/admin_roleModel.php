<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_roleModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_roleModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
    public function view_department(){
       try{
              $query=$GLOBALS['$dbFramework']->query("SELECT * FROM department ORDER BY Department_name");
              return $query->result();
          }
         catch (LConnectApplicationException $e)
         {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }
    public function view_role(){

        $a=array();
        try{
                $query = $GLOBALS['$dbFramework']->query("select * from department order by id");
                if($query->num_rows()>0){
                          $arr=$query->result_array();
                          for($i=0;$i<count($arr);$i++)
                          {
                                $Department_id=$arr[$i]['Department_id'];

                                $a[$i]['id'] = $arr[$i]['id'];
                                $a[$i]['Department_id'] = $arr[$i]['Department_id'];
                                $a[$i]['Department_name'] =$arr[$i]['Department_name'];

                          }
                }
                return $a;
         }
         catch (LConnectApplicationException $e)
         {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }

   

     public function view_role1($Department_id){

        $a=array();
        try{
                   $query = $GLOBALS['$dbFramework']->query("select * from department where Department_id='$Department_id'  order by id");
                   if($query->num_rows()>0){

                            $arr=$query->result_array();
                            for($i=0;$i<count($arr);$i++){

                                    $Department_id=$arr[$i]['Department_id'];

                                    $a[$i]['id'] = $arr[$i]['id'];
                                    $a[$i]['Department_id'] = $arr[$i]['Department_id'];
                                    $a[$i]['Department_name'] =$arr[$i]['Department_name'];

                                    $query1 = $GLOBALS['$dbFramework']->query("select * from user_roles where department_id='".$Department_id."'");
                                     if($query1->num_rows()>0){
                                            $arr1=$query1->result_array();
                                            for($j=0;$j<count($arr1);$j++){

                                                    $a[$i]['role_data'][$j]['role_id']=$arr1[$j]['role_id'];
                                                    $a[$i]['role_data'][$j]['role_name']=$arr1[$j]['role_name'];
                                            }
                                     }
                             }
                   }
                   return $a;
             }
             catch (LConnectApplicationException $e)
             {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
             }
    }

    public function insert_data($roleobj,$department_id) {

           $dt = date('ymdHis');
           $dup_roles=array();
            $j=0;
           try{
                  foreach ($roleobj as  $value)
                  {
                        $role_name=$value->rolename;
                        $role_name=$role_name;
                        $letter=chr(rand(97,122));
                        $letter.=chr(rand(97,122));
                        $roleID=$letter;
                        $roleID.=$dt;
                        $roleID1=uniqid($roleID);


                        $query=$GLOBALS['$dbFramework']->query("SELECT * from user_roles where LOWER(role_name)=LOWER('".$role_name."') AND department_id='".$department_id."'");
                        //$query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('user_roles','role_name','".ucfirst(strtolower($role_name))."','department_id','".$department_id."')");
                        if($query->num_rows()>0){

                                    $arr1=$query->result_array();
                                    for($i=0;$i<count($arr1);$i++)
                                    {
                                                  $dup_roles[$j]['role_name'] = $arr1[$i]['role_name'];
                                                  $j++;
                                    }
                        }
                        else
                        {
                            $query=$GLOBALS['$dbFramework']->query("insert into user_roles (role_id,role_name,department_id) values('".$roleID1."','".$role_name."','".$department_id."')  ");
                        }
                  }

                 return array(
                      'departmentid'=>$department_id,
                      'duproles'=>$dup_roles
                 );

           }
           catch (LConnectApplicationException $e)
           {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
           }
    }
    public function update_data($data,$roleID,$roleName,$deptid) {
          try{

                    $query=$GLOBALS['$dbFramework']->query("SELECT * from user_roles where LOWER(role_name)=LOWER('".$roleName."') AND department_id='".$deptid."' and role_id<>'".$roleID."'");
                    //$query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('user_roles','role_name','".ucfirst(strtolower($roleName))."','department_id','".$deptid."')");
                    if($query->num_rows()>0)
                    {
                         return $query->num_rows();

                    }
                    else
                    {
                        $update = $GLOBALS['$dbFramework']->update('user_roles' ,$data, array('LOWER(role_id)' => strtolower($roleID)));
                        return 0;
                    }
          }
          catch (LConnectApplicationException $e)
          {
                      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                      throw $e;
          }
    }
}
?>

