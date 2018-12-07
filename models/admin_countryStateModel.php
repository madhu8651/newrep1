<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_countryStateModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_countryStateModel extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }
    public function view_data(){
        $query=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='country'");
        return $query->result();
    }
    
    public function insert_data($data,$countryName) {
         try{
        
        $query=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='country' and lookup_value='".$countryName."'");
         if($query->num_rows()>0)
            {
                return false;
            }
            else
            {
                $insert = $GLOBALS['$dbFramework']->insert('lookup', $data);
            return $insert;
            }
        
         }
         catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }
    
    public function view_state(){
        /*$query=$GLOBALS['$dbFramework']->query("SELECT a.lookup_id as countryid,a.lookup_value as countryname,b.lookup_id as stateid,b.lookup_value as statename
                                                FROM lookup a,lookup b
                                                WHERE a.lookup_id = b.lookup_name
                                                AND a.lookup_name = 'country'
                                                ORDER BY b.lookup_value");
        return $query->result();*/
        $a=array();
         try{
           $query = $this->db->query("select * from lookup where lookup_name='country' order by id");
           if($query->num_rows()>0){

                    $arr=$query->result_array();
                    for($i=0;$i<count($arr);$i++){

                            $lookup_id=$arr[$i]['lookup_id'];

                            $a[$i]['id'] = $arr[$i]['id'];
                            $a[$i]['lookup_id'] = $arr[$i]['lookup_id'];
                            $a[$i]['lookup_value'] =$arr[$i]['lookup_value'];
                            $query1 = $this->db->query("select * from lookup where lookup_name='".$lookup_id."'");
                             if($query1->num_rows()>0){

                                    $arr1=$query1->result_array();
                                    for($j=0;$j<count($arr1);$j++){

                                            $a[$i]['state_data'][$j]['lookup_id']=$arr1[$j]['lookup_id'];
                                            $a[$i]['state_data'][$j]['lookup_value']=$arr1[$j]['lookup_value'];

                                    }
                             }
                     }
           }
           return $a;
         }
          catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }

    public function view_state1($countyrid){

            $a=array();
          try{
           $query = $this->db->query("select * from lookup where lookup_name='country' and lookup_id='$countyrid' order by id");
           if($query->num_rows()>0){

                    $arr=$query->result_array();
                    for($i=0;$i<count($arr);$i++){

                            $lookup_id=$arr[$i]['lookup_id'];

                            $a[$i]['id'] = $arr[$i]['id'];
                            $a[$i]['lookup_id'] = $arr[$i]['lookup_id'];
                            $a[$i]['lookup_value'] =$arr[$i]['lookup_value'];



                            $query1 = $this->db->query("select * from lookup where lookup_name='".$lookup_id."'");
                             if($query1->num_rows()>0){

                                    $arr1=$query1->result_array();
                                    for($j=0;$j<count($arr1);$j++){

                                            $a[$i]['state_data'][$j]['lookup_id']=$arr1[$j]['lookup_id'];
                                            $a[$i]['state_data'][$j]['lookup_value']=$arr1[$j]['lookup_value'];

                                    }
                             }
                     }
           }
           return $a;
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }

    public function get_state($countryid){
       try{
        $query=$GLOBALS['$dbFramework']->query("select lookup_id,lookup_value from lookup where lookup_name='".$countryid."' ORDER BY lookup_value");
        return $query->result();
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function insert_state_data($stateobj,$countryid) {
            $dt = date('ymdHis');
            $dup_state=array();
            $j=0;
       try{
            $lookup_name=$countryid;
            foreach ($stateobj as  $value) {
                    $lookup_value=$value->state_name;
                    $lookup_key=$value->statecount;
                    $letter=chr(rand(97,122));
                    $letter.=chr(rand(97,122));
                    $stateID=$letter;
                    $stateID.=$dt;
                    $stateID1=uniqid($stateID);

                    $query=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='".$lookup_name."' and lookup_value='".$lookup_value."'");
                    if($query->num_rows()>0){

                                    $arr1=$query->result_array();
                                   // print_r($arr1);
                                    for($i=0;$i<count($arr1);$i++){

                                                  $dup_state[$j]['lookup_name'] = $arr1[$i]['lookup_name'];
                                                  $dup_state[$j]['statename'] = $arr1[$i]['lookup_value'];
                                                  $j++;
                                    }
                    }else{
                        $query=$GLOBALS['$dbFramework']->query("insert into lookup (lookup_id,lookup_value,lookup_key,lookup_name)
                                                                  values('".$stateID1."','".$lookup_value."','".$lookup_key."','".$lookup_name."')  ");

                    }

            }

            return array(
                    'countryid'=>$lookup_name,
                    'dup_state'=>$dup_state
            );

         }
         catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }
 
    public function delete_state_data($stateid){
        
            //$this->db->where('lookup_id',$stateid);
            //$this->db->delete('lookup');
         try{
            $GLOBALS['$dbFramework']->delete('lookup' , array('LOWER(lookup_id)' => strtolower($stateid)));
            return true;
            }
           catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
          }
        
    }
    
}
?>