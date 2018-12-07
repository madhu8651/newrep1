<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_support_processModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_support_processModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
    public function view_data(){
        try{
            $query = $GLOBALS['$dbFramework']->query("select * from lookup WHERE lookup_name='support_process' order by id");
            return $query->result();

        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function insert_data($data,$buyerpName) {
        try{
                $query=$GLOBALS['$dbFramework']->query("select * from lookup where
                                                    LOWER(lookup_value)=LOWER('".$buyerpName."')
                                                            and lookup_name='support_process'");
                //$query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('lookup','lookup_value','".ucfirst(strtolower($buyerpName))."','lookup_name','support_process')");
                if($query->num_rows()>0)
                {
                    return false;
                }
                else
                {
                    $insert = $GLOBALS['$dbFramework']->insert('lookup', $data);
                    return $insert;
                }
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function update_data($buyerpID,$data,$buyerpName) {
        try{
                $query=$GLOBALS['$dbFramework']->query("select * from lookup where
                                                    LOWER(lookup_value)=LOWER('".$buyerpName."')
                                                            and lookup_name='support_process' and lookup_id <> '".$buyerpID."'");
                //$query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('lookup','lookup_value','".ucfirst(strtolower($buyerpName))."','lookup_name','support_process')");
                if($query->num_rows()>0)
                {
                    return false;
                }
                else
                {
                   $update = $GLOBALS['$dbFramework']->update('lookup' ,$data, array('LOWER(lookup_id)' => strtolower($buyerpID)));
                   return $update;
                }
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function update_tg_bit($lookupid,$toggleid,$status) {
        try{
                $str1=array();
                $str2="";
                if($status == "checkinteam"){
                    $query=$GLOBALS['$dbFramework']->query("select * from teams ");
                    $arr=$query->result_array();
                    if($query->num_rows()>0){
                        for($i=0;$i<count($arr);$i++){
                            $str=$arr[$i]['regionid'];
                            $str1=explode(',',$str);

                            if (in_array("$lookupid", $str1))
                            {
                               $str2 = "found";
                            }
                        }
                    }
                }
                if($str2 == "found")
                {
                    return false;
                }
                else
                {
                   $update = $GLOBALS['$dbFramework']->query("update lookup set togglebit=".$toggleid." where lookup_id='".$lookupid."'");
                   return $update;
                }
          }catch (LConnectApplicationException $e){
              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
              throw $e;
          }

    }

}
?>

