<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_activityModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_activityModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
    public function view_data(){
        try{
            $a=$b=array();
            $j=$k=0;
                   $query = $GLOBALS['$dbFramework']->query("select * from lookup WHERE lookup_name='activity' order by id");
                   if($query->num_rows()>0){
                            $arr=$query->result_array();
                            for($i=0;$i<count($arr);$i++){

                                    $lookup_id=$arr[$i]['lookup_id'];
                                    $query1 = $GLOBALS['$dbFramework']->query("select distinct conntype  from lead_reminder  where conntype='$lookup_id'");
                                    if($query1->num_rows()>0){
                                        $a[$j]['id'] = $arr[$i]['id'];
                                        $a[$j]['lookup_id'] = $arr[$i]['lookup_id'];
                                        $a[$j]['lookup_name'] =$arr[$i]['lookup_name'];
                                        $a[$j]['lookup_value'] =$arr[$i]['lookup_value'];
                                        $a[$j]['remarks'] =$arr[$i]['remarks'];
                                        $j++;
                                    }else{

                                            $query2 = $GLOBALS['$dbFramework']->query("select distinct logtype  from rep_log  where logtype='$lookup_id'");
                                            if($query2->num_rows()>0){
                                                $a[$j]['id'] = $arr[$i]['id'];
                                                $a[$j]['lookup_id'] = $arr[$i]['lookup_id'];
                                                $a[$j]['lookup_name'] =$arr[$i]['lookup_name'];
                                                $a[$j]['lookup_value'] =$arr[$i]['lookup_value'];
                                                $a[$j]['remarks'] =$arr[$i]['remarks'];
                                                $j++;
                                            }else{

                                                $b[$k]['id'] = $arr[$i]['id'];
                                                $b[$k]['lookup_id'] = $arr[$i]['lookup_id'];
                                                $b[$k]['lookup_name'] =$arr[$i]['lookup_name'];
                                                $b[$k]['lookup_value'] =$arr[$i]['lookup_value'];
                                                $b[$k]['remarks'] =$arr[$i]['remarks'];
                                                $k++;
                                            }
                                    }
                             }
                   }
                   return array(
                        'saveddata'=>$a,
                        'ntsaveddata'=>$b
                   );


        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function insert_data($data,$buyerpName) {
        try{
                /*$query=$GLOBALS['$dbFramework']->query("select * from lookup where
                                                    CONCAT(UCASE(LEFT(lookup_value, 1)),
                                                            LCASE(SUBSTRING(lookup_value, 2)))='".ucfirst(strtolower($buyerpName))."'
                                                            and lookup_name='activity'");*/
                $query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('lookup','lookup_value','".ucfirst(strtolower($buyerpName))."','lookup_name','activity')");
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
                /*$query=$GLOBALS['$dbFramework']->query("select * from lookup where
                                                    CONCAT(UCASE(LEFT(lookup_value, 1)),
                                                            LCASE(SUBSTRING(lookup_value, 2)))='".ucfirst(strtolower($buyerpName))."'
                                                            and lookup_name='activity'");*/
                $query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('lookup','lookup_value','".ucfirst(strtolower($buyerpName))."','lookup_name','activity')");
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
}
?>

