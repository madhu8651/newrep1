<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_industry_hierarchyModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_industry_hierarchyModel extends CI_Model{
    public function __construct(){
        parent::__construct();
    }
    public function get_lead_source() {
        try{
              $query = $GLOBALS['$dbFramework']->query(" call get_hierarchy_details('industry','onload','','');");
              return $query->result();
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }
    public function insert_hierarchy_class($param) {
      try{
        $insert = $GLOBALS['$dbFramework']->insert('hierarchy_class',$param);
        return $insert;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }
    public function insert_hierarchy($param,$source,$parent_id) {
       try{

              //$que=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('industry','insert','".ucfirst(strtolower($source))."','".$parent_id."');");
              $que=$GLOBALS['$dbFramework']->query("SELECT b.id,b.hierarchy_id,b.hkey2,b.hvalue2,b.hkey1,b.hvalue1
                                        				FROM hierarchy_class a,hierarchy b
                                        				WHERE a.Hierarchy_Class_Name='industry'
                                        				AND b.hkey2!='0' AND b.Hierarchy_Class_ID=a.hierarchy_class_id
                                        				AND LOWER(b.hvalue2)=LOWER('$source')
                                                        AND b.hkey1='".$parent_id."' ORDER BY b.hierarchy_id;");
                      if($que->num_rows()>0){

                           return false;

                      }else{

                          $insert = $GLOBALS['$dbFramework']->insert('hierarchy',$param);
                          return true;

                      }
          }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }

    }
    public function get_hierarchy_id(){
       try{
              $query = $GLOBALS['$dbFramework']->query("select Hierarchy_Class_ID from hierarchy_class where Hierarchy_Class_Name='industry'");
              return $query->result();
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function update_source($param,$id,$parent_id,$hid) {

      try{

        //$que=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('industry','update','".ucfirst(strtolower($param))."','".$parent_id."');");
        $que=$GLOBALS['$dbFramework']->query("SELECT b.id,b.hierarchy_id,b.hkey2,b.hvalue2,b.hkey1,b.hvalue1
                                        				FROM hierarchy_class a,hierarchy b
                                        				WHERE a.Hierarchy_Class_Name='industry'
                                        				AND b.hkey2!='0' AND b.Hierarchy_Class_ID=a.hierarchy_class_id
                                        				AND LOWER(b.hvalue2)=LOWER('$param')
                                                        AND b.hkey1='".$parent_id."' AND b.hierarchy_id!='".$hid."' ORDER BY b.hierarchy_id;");
                if($que->num_rows()>0){

                     return false;

                }else{

                    $updateque=$GLOBALS['$dbFramework']->query("update hierarchy set hvalue2='".$param."' where hkey2='$id'");
                    $updateque=$GLOBALS['$dbFramework']->query("update hierarchy set hvalue1='".$param."' where hkey1='$id'");
                    return true;

                }
         }
         catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function get_child_count($hkey1){
       try{
              $count=$GLOBALS['$dbFramework']->query("select count(hkey1) as childcount from hierarchy where hkey1='$hkey1'");
              if($count->num_rows()>0){
                   foreach ($count->result() as $row)
                          {
                              $id=$row->childcount;
                          }
              }
              return $id;
       }
       catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
       }

    }
}
?>