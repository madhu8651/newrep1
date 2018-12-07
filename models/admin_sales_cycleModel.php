<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_sales_cycleModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_sales_cycleModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }


    public function view_cycledata(){
      try{
                $query=$GLOBALS['$dbFramework']->query("SELECT a.id,a.CYCLE_ID,a.CYCLE_NAME,a.MASTERCYCLE_ID,a.togglebit,
                                    (select master_cyclename from master_sales_cycle where a.MASTERCYCLE_ID=master_cycleid)as master_cyclename
                                      FROM sales_cycle a  order by id");
                return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }
    public function view_mastercycledata(){
        try{
                $query=$GLOBALS['$dbFramework']->query("select c.* from sales_stage a,stage_cycle_mapping b,
                                                        master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid
                                                        and a.stage_sequence >5 and a.stage_sequence<100 group by c.master_cycleid,c.master_cyclename;");
                return $query->result();
        }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }
    public function insert_data($cycledata,$cyclename,$cycleID) {
        try{
              $query = $GLOBALS['$dbFramework']->query("select * from sales_cycle where
                                                        LOWER(CYCLE_NAME)=LOWER('".$cyclename."') and togglebit=1");
              //$query = $GLOBALS['$dbFramework']->query("call common_check_duplicate('insert,sales_cycle','".ucfirst(strtolower($cyclename))."','','admin','');");
              if($query->num_rows()>0){
                return false;
              }
              else{
                  $GLOBALS['$dbFramework']->insert('sales_cycle', $cycledata);
                  $dt=date('ymdHis');
                  $stageid = uniqid($dt);
                  $stagemapid = uniqid($dt);
                  $stageid="C".$stageid;
                  $GLOBALS['$dbFramework']->query("insert into sales_stage(stage_id,stage_name,stage_sequence)value ('$stageid','Closed',100) ");
                  $GLOBALS['$dbFramework']->query("insert into stage_cycle_mapping(stage_cycle_id,cycle_id,stage_id)value ('$stagemapid','$cycleID','$stageid') ");



                  return TRUE;
              }
        }catch (LConnectApplicationException $e){
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                    throw $e;
        }
    }
    public function update_data($cycledata,$cyclename,$cycleid,$cycledata1,$mcycleid) {
        try{

            $query = $GLOBALS['$dbFramework']->query("select * from sales_cycle where
                                                        LOWER(CYCLE_NAME)=LOWER('".$cyclename."') and togglebit=1 and
                                                        CYCLE_ID<>'$cycleid' ");
            //$query = $GLOBALS['$dbFramework']->query("call common_check_duplicate('update,sales_cycle','".ucfirst(strtolower($cyclename))."','".$cycleid."','admin','');");
            if($query->num_rows()>0){
                $query = $GLOBALS['$dbFramework']->update('sales_cycle' ,$cycledata1, array('LOWER(CYCLE_ID)' => strtolower($cycleid)));
                return false;
            }
            else{
                    /* get old master cycle name */
                    $query=$GLOBALS['$dbFramework']->query("select * from sales_cycle where CYCLE_ID='$cycleid' order by id");
                    if($query->num_rows()>0){
                          foreach ($query->result() as $row)
                          {
                               $exist_mstageid = $row->MASTERCYCLE_ID;
                          }
                    }
                    $query = $GLOBALS['$dbFramework']->update('sales_cycle' ,$cycledata, array('LOWER(CYCLE_ID)' => strtolower($cycleid)));

                /* update master stages if stages are given  */



                if($exist_mstageid <> $mcycleid){
                        $que1=$GLOBALS['$dbFramework']->query("SELECT a.*,b.stage_name,b.stage_sequence FROM stage_cycle_mapping a,sales_stage b where
                                                                a.stage_id=b.stage_id and a.cycle_id='".$cycleid."' and b.stage_sequence<>100");
                        if($que1->num_rows()>0){
                          $arr=$que1->result_array();
                          for($i=0;$i<count($arr);$i++){
                                  $stage_cycle_id=$arr[$i]['stage_cycle_id'];

                                  /* update master stage details in stage cycle mapping table */
                                  $upque_new=$GLOBALS['$dbFramework']->query("update stage_cycle_mapping set master_stageid=null,mapseq=null
                                                                              where stage_cycle_id='".$stage_cycle_id."'");
                          }
                        }
                }

                return TRUE;
            }
        }catch (LConnectApplicationException $e){
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                    throw $e;
        }
    }

    public function check_activecycle($cyclename){
      try{
               $que=$GLOBALS['$dbFramework']->query("select * from sales_cycle where
                                                LOWER(CYCLE_NAME)=LOWER('".$cyclename."') and togglebit=1 ");
               if($que->num_rows()>0){

                        foreach ($que->result() as $row)
                        {
                            $CYCLE_ID=$row->CYCLE_ID;
                        }
                        return $CYCLE_ID;
               }else{

                      $CYCLE_ID="nocycle";
                      return $CYCLE_ID;
               }
        }catch (LConnectApplicationException $e){
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                    throw $e;
        }

    }

    public function update_tg_bit($found_cycleid,$toggleid,$selected_cycle_id,$toggleid1){
      try{
           $parameter_id=$parameter_id1="";
           $upque=$GLOBALS['$dbFramework']->query("update sales_cycle set togglebit='$toggleid' where CYCLE_ID='$found_cycleid'");
           $upque1=$GLOBALS['$dbFramework']->query("update sales_cycle_parameters set cycle_togglebit='$toggleid' where cycle_id='$found_cycleid'");

           $upque2=$GLOBALS['$dbFramework']->query("update sales_cycle set togglebit='$toggleid1' where CYCLE_ID='$selected_cycle_id'");
           $upque21=$GLOBALS['$dbFramework']->query("update sales_cycle_parameters set cycle_togglebit='$toggleid1' where cycle_id='$selected_cycle_id'");
      }catch (LConnectApplicationException $e){
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                    throw $e;
      }
                    /*$selque=$GLOBALS['$dbFramework']->query("select distinct parameter_id from sales_cycle_parameters where cycle_id='$cycleid' and cycle_togglebit=0");
                    if($selque->num_rows()>0){
                        foreach ($selque->result() as $row)
                        {
                            $parameter_id=$row->parameter_id;

                        }
                    }

                    $selque=$GLOBALS['$dbFramework']->query("select distinct parameter_id from sales_cycle_parameters where cycle_id='$cycleid' and cycle_togglebit=1");
                    if($selque->num_rows()>0){
                        foreach ($selque->result() as $row)
                        {
                            $parameter_id1=$row->parameter_id;

                        }
                    }
                    if($parameter_id!=""){
                        $upque=$GLOBALS['$dbFramework']->query("update sales_cycle_parameters set cycle_togglebit=1 where parameter_id='$parameter_id'");

                    }
                    if($parameter_id1!=""){
                        $upque=$GLOBALS['$dbFramework']->query("update sales_cycle_parameters set cycle_togglebit=0 where parameter_id='$parameter_id1'");

                    }*/
                    return true;


    }

    public function update_tg_bit1($cycle_id,$toggleid){
        try{
           $upque=$GLOBALS['$dbFramework']->query("update sales_cycle set togglebit='$toggleid' where CYCLE_ID='$cycle_id'");
           $upque1=$GLOBALS['$dbFramework']->query("update sales_cycle_parameters set cycle_togglebit='$toggleid' where cycle_id='$cycle_id'");
           return true;
        }catch (LConnectApplicationException $e){
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                    throw $e;
        }

    }

    public function cycleresetbit(){
        try{
                $upque=$GLOBALS['$dbFramework']->query("update sales_cycle set togglebit='0'");
                return true;
        }catch (LConnectApplicationException $e){
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                    throw $e;
        }

    }

}
?>

