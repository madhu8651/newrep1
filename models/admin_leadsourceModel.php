<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_leadsourceModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_leadsourceModel extends CI_Model{
    public function __construct(){
        parent::__construct();
    }
    public function get_lead_source() {
        $query = $GLOBALS['$dbFramework']->query("call get_hierarchy_details('lead_source','lead_src','','');");
        return $query->result();
    }
    public function insert_hierarchy_class($param) {
        $insert = $GLOBALS['$dbFramework']->insert('hierarchy_class',$param);
        return $insert;
    }
    public function insert_hierarchy($param,$source,$parent_id) {

        //$que=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('lead_source','insert','".ucfirst(strtolower($source))."','".$parent_id."');");
        $que=$GLOBALS['$dbFramework']->query("SELECT b.id,b.hierarchy_id,b.hkey2,b.hvalue2,b.hkey1,b.hvalue1
                                        				FROM hierarchy_class a,hierarchy b
                                        				WHERE a.Hierarchy_Class_Name='lead_source'
                                        				AND b.hkey2!='0' AND b.Hierarchy_Class_ID=a.hierarchy_class_id
                                        				AND LOWER(b.hvalue2)=LOWER('$source')
                                                        AND b.hkey1='".$parent_id."' ORDER BY b.hierarchy_id;");
                if($que->num_rows()>0){

                     return false;

                }else{
                    /* update enddate of parent if attribute present for parent */
                    $update=$GLOBALS['$dbFramework']->query("update lead_source_attributes set end_date=null where lead_source_id='".$parent_id."'");
                    $insert = $GLOBALS['$dbFramework']->insert('hierarchy',$param);
                    return true;

                }
    }
    public function get_hierarchy_id(){
        $query = $GLOBALS['$dbFramework']->query("select Hierarchy_Class_ID from hierarchy_class where Hierarchy_Class_Name='lead_source'");
        return $query->result();
    }
    public function update_source($param,$id,$parent_id,$hid) {


        //$que=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('lead_source','update','".ucfirst(strtolower($param))."','".$parent_id."');");
        $que=$GLOBALS['$dbFramework']->query("SELECT b.id,b.hierarchy_id,b.hkey2,b.hvalue2,b.hkey1,b.hvalue1
                                        				FROM hierarchy_class a,hierarchy b
                                        				WHERE a.Hierarchy_Class_Name='lead_source'
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
    public function get_child_count($hkey1,$state){
        if($state==1){
             $count=$GLOBALS['$dbFramework']->query("select count(hkey1) as childcount from hierarchy where hkey1='$hkey1'");
        }else{
             $count=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('lead_source','lead_src','count','".$hkey1."');");
        }
        if($count->num_rows()>0){
             foreach ($count->result() as $row)
                    {
                        $id=$row->childcount;
                    }
        }
        return $id;
    }

    public function currency(){
        $query=$GLOBALS['$dbFramework']->query("select a.currency_id,a.currency_name from currency a,currency_category b where a.currency_category_id=b.currency_category_id
                                    and b.currency_category_name='Lead Source'");
        return $query->result();
    }
    public function getattr_data($nodeid,$parentid){
        /* check for in active parent */
        $time_chk=$GLOBALS['$dbFramework']->query("select (timestampdiff(minute,DATE_ADD(DATE_ADD(now(),INTERVAL 10 HOUR),INTERVAL 30 MINUTE),end_date)) as tm from lead_source_attributes
                                                                            where lead_source_id='".$parentid."' and end_date is not null ;");
        if($time_chk->num_rows()>0){
            foreach ($time_chk->result() as $row1)
            {
                     $time= $row1->tm;
            }
            if($time < 0){
                    return "yes";
            }else{
                    $query=$GLOBALS['$dbFramework']->query("select * from lead_source_attributes where lead_source_id='$nodeid'");
                    if($query->num_rows()>0){
                         return $query->result();
                    }else{
                        return $query->num_rows();
                    }
            }
        }else{
                    $query=$GLOBALS['$dbFramework']->query("select * from lead_source_attributes where lead_source_id='$nodeid'");
                    if($query->num_rows()>0){
                         return $query->result();
                    }else{
                        return $query->num_rows();
                    }
        }


    }
    public function insert_attr_data($attrdata,$nodeid,$parentid){

        $GLOBALS['$dbFramework']->delete('lead_source_attributes' , array('lead_source_id' => $nodeid));
        $insert = $GLOBALS['$dbFramework']->insert('lead_source_attributes',$attrdata);
        return TRUE;

    }
    public function get_inactive_data($state){
            $json = array();
            if($state==1){
                $i=0;
                $query = $GLOBALS['$dbFramework']->query("call get_hierarchy_details('lead_source','onload','','');");
                if($query->num_rows()>0){
                          foreach ($query->result() as $row)
                          {
                                $json[$i]['hid'] = $row->hierarchy_id;
                                $json[$i]['id'] = $row->hkey2;
                                $sourceid=$row->hkey2;
                                $json[$i]['name'] = $row->hvalue2;
                                $json[$i]['parent'] = $row->hkey1;
                                $json[$i]['parent_name'] = $row->hvalue1;
                                $json[$i]['status'] = "yes";

                                $time_chk=$GLOBALS['$dbFramework']->query("select (timestampdiff(minute,DATE_ADD(DATE_ADD(now(),INTERVAL 10 HOUR),INTERVAL 30 MINUTE),end_date)) as tm from lead_source_attributes
                                                                            where lead_source_id='".$sourceid."' and end_date is not null ;");
                                if($time_chk->num_rows()>0){
                                    foreach ($time_chk->result() as $row1)
                                    {
                                             $time= $row1->tm;
                                    }
                                    if($time < 0){
                                        $json[$i]['nodecount'] = 0;
                                    }else{
                                        $json[$i]['nodecount'] = 1;
                                    }
                                }else{
                                     $json[$i]['nodecount'] = 1;
                                }
                                $i++;
                          }
                }
                return $json;

            }else{

                $query = $GLOBALS['$dbFramework']->query("call get_hierarchy_details('lead_source','lead_src','','');");
                return $query->result();

            }

    }


}
?>