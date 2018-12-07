<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_mastersales_stageModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_mastersales_stageModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
    public function get_data(){
                    $b=array();
                    $d=array();
                    $dupbit=0;
            try{
                    $query=$GLOBALS['$dbFramework']->query("select * from master_sales_cycle order by id ");
                    $arr=$query->result_array();
                    for($i=0;$i<count($arr);$i++){

                        $CYCLE_ID=$arr[$i]['master_cycleid'];

                        $b[$i]['CYCLE_NAME']=$arr[$i]['master_cyclename'];
                        $b[$i]['CYCLE_ID']=$arr[$i]['master_cycleid'];
                        $b[$i]['oppchk']="0";

                        $query=$GLOBALS['$dbFramework']->query("select c.* from sales_stage a,stage_cycle_mapping b,
                                                                master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid
                                                                    and b.cycle_id='$CYCLE_ID'  and a.stage_sequence >5 and a.stage_sequence<100 group by c.master_cycleid,c.master_cyclename; ");
                        if($query->num_rows()>0){
                            $dupbit=1;
                        }
                    }
                    $d[0]['dupbit']=$dupbit;
                    $c=array_merge($b,$d);
                    return $c;
            }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }

    }

        public function get_data2($cycleid){
                    $b=array();
            try{
                    $query=$GLOBALS['$dbFramework']->query("select * from master_sales_cycle where master_cycleid ='$cycleid'");
                    $arr=$query->result_array();
                    for($i=0;$i<count($arr);$i++){

                        $CYCLE_ID=$arr[$i]['master_cycleid'];

                        //$b[$i]['CYCLE_NAME']=$arr[$i]['master_cyclename'];
                        $b[$i]['CYCLE_ID']=$arr[$i]['master_cycleid'];
                        $b[$i]['oppchk']="0";


                        $query1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks from sales_stage a,stage_cycle_mapping b,
                                                    master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid and  c.master_cycleid='$CYCLE_ID' and a.stage_sequence >5 and a.stage_sequence<100 order by a.stage_sequence");
                        $arr1=$query1->result_array();
                        for($j=0;$j<count($arr1);$j++){
                            $b[$i]['stagedata'][$j]['stage_name']=$arr1[$j]['stage_name'];
                            $b[$i]['stagedata'][$j]['stage_id']=$arr1[$j]['stage_id'];
                            $b[$i]['stagedata'][$j]['id']=$arr1[$j]['id'];
                            $b[$i]['stagedata'][$j]['remarks']=$arr1[$j]['remarks'];
                        }

                    }
                    return $b;
            }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }

    }

    public function cyclename_data(){
        try{
                $query=$GLOBALS['$dbFramework']->query("select * from master_sales_cycle order by id");
                return $query->result();
        }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }
    public function insert_data($data1,$data2,$stageName,$cycleId,$new_stageid) {

        try{
                $str="";
                $query = $GLOBALS['$dbFramework']->query("select a.stage_name from sales_stage a,
                                                            stage_cycle_mapping b where a.stage_id=b.stage_id and
                                                                LOWER(a.stage_name)=LOWER('".$stageName."')
                                                                and trim(lower(b.cycle_id))=trim(lower('$cycleId'))");
                //$query = $GLOBALS['$dbFramework']->query("call common_check_duplicate('insert,sales_stage','".ucfirst(strtolower($stageName))."','".$cycleId."','admin','');");
                if($query->num_rows()>0){
                        $str="0";
                }
                else{
                    $str="1";
                    $GLOBALS['$dbFramework']->insert('sales_stage', $data1);

                    $que=$GLOBALS['$dbFramework']->query("select id from sales_stage order by id desc");
                    if($que->num_rows()>0){
                            $row = $que->row();
                            $lastid = $row->id;
                    }

                    $GLOBALS['$dbFramework']->insert('stage_cycle_mapping', $data2);
                    $que=$GLOBALS['$dbFramework']->query("select max(a.stage_sequence) as maxid from sales_stage a,stage_cycle_mapping b where a.stage_id=b.stage_id and  a.stage_sequence<100 and b.cycle_id='$cycleId'");
                    if($que->num_rows()>0){
                            foreach ($que->result() as $row)
                            {
                                    $maxid = $row->maxid;
                                    $maxid=$maxid+1;
                                    if($maxid==1){
                                      $maxid=6;
                                    }
                            }
                    }else{
                       $maxid=6;
                    }
                    $upd=$GLOBALS['$dbFramework']->query("update sales_stage set stage_sequence='$maxid' where id='$lastid' ");
                }
                $b=array();
                $query=$GLOBALS['$dbFramework']->query("select c.master_cyclename,c.master_cycleid from sales_stage a,stage_cycle_mapping b,
                          master_sales_cycle c where a.stage_id=b.stage_id and  b.cycle_id=c.master_cycleid and c.master_cycleid='$cycleId' and a.stage_sequence >5 and a.stage_sequence<100 group by c.master_cycleid,c.master_cyclename ");
                $arr=$query->result_array();

                for($i=0;$i<count($arr);$i++){

                    $CYCLE_ID=$arr[$i]['master_cycleid'];

                    $b[$i]['CYCLE_NAME']=$arr[$i]['master_cyclename'];
                    $b[$i]['CYCLE_ID']=$arr[$i]['master_cycleid'];
                    $b[$i]['oppchk']="0";


                    $query1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks from sales_stage a,stage_cycle_mapping b,
                                                master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid and  c.master_cycleid='$CYCLE_ID' and a.stage_sequence >5 and a.stage_sequence<100 order by a.stage_sequence");
                    $arr1=$query1->result_array();
                    for($j=0;$j<count($arr1);$j++){
                        $b[$i]['stagedata'][$j]['stage_name']=$arr1[$j]['stage_name'];
                        $b[$i]['stagedata'][$j]['stage_id']=$arr1[$j]['stage_id'];
                        $b[$i]['stagedata'][$j]['id']=$arr1[$j]['id'];
                        $b[$i]['stagedata'][$j]['remarks']=$arr1[$j]['remarks'];
                    }
                }

                return array(
                    'records' => $b,
                    'str' => $str,
                );
        }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function update_data($stage_id,$stage_name,$edit_cycleid,$description){
            try{
                    $str="";
                    $query = $GLOBALS['$dbFramework']->query("select a.stage_name,a.stage_id from sales_stage a,
                                                                stage_cycle_mapping b where a.stage_id=b.stage_id and
                                                                    LOWER(a.stage_name)=LOWER('".$stage_name."')
                                                                    and trim(lower(b.cycle_id))=trim(lower('$edit_cycleid')) and a.stage_id <>'".$stage_id."'");
                    //$query = $GLOBALS['$dbFramework']->query("call common_check_duplicate('insert,sales_stage','".ucfirst(strtolower($stage_name))."','".$edit_cycleid."','admin','');");
                    if($query->num_rows()>0){
                        foreach ($query->result() as $row)
                        {
                             $stage_id1 = $row->stage_id;
                        }

                        if(strtolower($stage_id1) <> strtolower($stage_id)){
                            $str="0";
                        }else{

                             $str="1";
                             $GLOBALS['$dbFramework']->query("update stage_cycle_mapping set remarks='$description' where  trim(lower(stage_id))=trim(lower('$stage_id'))");
                        }

                    }
                    else{

                        $str="1";
                        $GLOBALS['$dbFramework']->query("update sales_stage set stage_name='".$stage_name."' where  trim(lower(stage_id))=trim(lower('$stage_id'))");
                        $GLOBALS['$dbFramework']->query("update stage_cycle_mapping set remarks='$description' where  trim(lower(stage_id))=trim(lower('$stage_id'))");

                    }

                    $b=array();
                    $query=$GLOBALS['$dbFramework']->query("select c.master_cyclename,c.master_cycleid from sales_stage a,stage_cycle_mapping b,
                              master_sales_cycle c where a.stage_id=b.stage_id and  b.cycle_id=c.master_cycleid and c.master_cycleid='$edit_cycleid' and a.stage_sequence >5 and a.stage_sequence<100 group by c.master_cycleid,c.master_cyclename ");
                    $arr=$query->result_array();

                    for($i=0;$i<count($arr);$i++){

                        $CYCLE_ID=$arr[$i]['master_cycleid'];

                        $b[$i]['CYCLE_NAME']=$arr[$i]['master_cyclename'];
                        $b[$i]['CYCLE_ID']=$arr[$i]['master_cycleid'];
                        $b[$i]['oppchk']="0";


                        $query1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks from sales_stage a,stage_cycle_mapping b,
                                                    master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid and  c.master_cycleid='$CYCLE_ID' and a.stage_sequence >5 and a.stage_sequence<100 order by a.stage_sequence");
                        $arr1=$query1->result_array();
                        for($j=0;$j<count($arr1);$j++){
                            $b[$i]['stagedata'][$j]['stage_name']=$arr1[$j]['stage_name'];
                            $b[$i]['stagedata'][$j]['stage_id']=$arr1[$j]['stage_id'];
                            $b[$i]['stagedata'][$j]['id']=$arr1[$j]['id'];
                            $b[$i]['stagedata'][$j]['remarks']=$arr1[$j]['remarks'];
                        }
                    }

                    return array(
                        'records' => $b,
                        'str' => $str,
                    );
            }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }

    }

    public function update_roworder($orderselected,$hid_cycleid){
        try{
                $j=6;
                foreach ($orderselected as  $value) {
                     $row=$value->roworder;

                     $que=$GLOBALS['$dbFramework']->query("select stage_id from sales_stage where id='$row' ");
                     if($que->num_rows()>0){

                            foreach ($que->result() as $row1)
                            {
                                 $mstageid = $row1->stage_id;
                            }
                     }

                     $updateque1=$GLOBALS['$dbFramework']->query("update sales_stage set stage_sequence='$j' where id='$row' ");

                     $updateque2=$GLOBALS['$dbFramework']->query("update stage_cycle_mapping set mapseq='$j' where master_stageid='$mstageid'");

                    $j++;
                }
                return TRUE;
        }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function get_update_data($cycle_id){
            $str="1";
            $b=array();
            try{
                    $query=$GLOBALS['$dbFramework']->query("select c.master_cyclename,c.master_cycleid from sales_stage a,stage_cycle_mapping b,
                              master_sales_cycle c where a.stage_id=b.stage_id and  b.cycle_id=c.master_cycleid and c.master_cycleid='$cycle_id' and a.stage_sequence >5 and a.stage_sequence<100 group by c.master_cycleid,c.master_cyclename ");
                    $arr=$query->result_array();
                    for($i=0;$i<count($arr);$i++){
                        $CYCLE_ID=$arr[$i]['master_cycleid'];

                        $b[$i]['CYCLE_NAME']=$arr[$i]['master_cyclename'];
                        $b[$i]['CYCLE_ID']=$arr[$i]['master_cycleid'];
                        $b[$i]['oppchk']="0";

                        $query1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks from sales_stage a,stage_cycle_mapping b,
                                                    master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid and  c.master_cycleid='$CYCLE_ID' and a.stage_sequence >5 and a.stage_sequence<100 order by a.stage_sequence");
                        $arr1=$query1->result_array();
                        for($j=0;$j<count($arr1);$j++){
                            $b[$i]['stagedata'][$j]['stage_name']=$arr1[$j]['stage_name'];
                            $b[$i]['stagedata'][$j]['stage_id']=$arr1[$j]['stage_id'];
                            $b[$i]['stagedata'][$j]['id']=$arr1[$j]['id'];
                            $b[$i]['stagedata'][$j]['remarks']=$arr1[$j]['remarks'];
                        }
                    }
                    return array(
                        'records' => $b,
                        'str' => $str,
                    );
            }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }
    }

    public function get_cycle(){
        try{
                $que=$GLOBALS['$dbFramework']->query("select c.master_cyclename as CYCLE_NAME,c.master_cycleid as CYCLE_ID from sales_stage a,stage_cycle_mapping b,
                                                  master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid
                                                  and a.stage_sequence >5 and a.stage_sequence<100 group by c.master_cycleid,c.master_cyclename  ");
                return $que->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function create_duplicate($from,$to){

                    $map_array1=$stg_array1=$stg_att_array1=array();
        try{
                   $que1=$GLOBALS['$dbFramework']->query("SELECT a.*,b.stage_name,b.stage_sequence FROM stage_cycle_mapping a,sales_stage b where
                                          a.stage_id=b.stage_id and
                                        a.cycle_id='".$from."' and b.stage_sequence<>100");
                   if($que1->num_rows()>0){
                        $arr=$que1->result_array();
                        for($i=0;$i<count($arr);$i++){

                                $letter=chr(rand(97,122));
                                $letter.=chr(rand(97,122));
                                $stage_cycle_id=$arr[$i]['stage_cycle_id'];

                                $stage_cycle_id1=$letter . substr($stage_cycle_id, 1);


                                $cycle_id=$to;
                                $letter=chr(rand(97,122));
                                $letter.=chr(rand(97,122));
                                $stage_id=$arr[$i]['stage_id'];
                                $stage_id1=$letter . substr($stage_id, 1);


                                $master_stageid=$arr[$i]['master_stageid'];
                                $mapseq=$arr[$i]['mapseq'];
                                $remarks=$arr[$i]['remarks'];
                                $stage_name=$arr[$i]['stage_name'];
                                $stage_sequence=$arr[$i]['stage_sequence'];

                                $map_array=array(
                                      'stage_cycle_id'=>$stage_cycle_id1,
                                      'cycle_id'=>$cycle_id,
                                      'stage_id'=>$stage_id1,
                                      'master_stageid'=>$master_stageid,
                                      'mapseq'=>$mapseq,
                                      'remarks'=>$remarks
                                );
                                array_push($map_array1,$map_array);

                                $stg_array=array(
                                       'stage_id'=>$stage_id1,
                                       'stage_name'=>$stage_name,
                                       'stage_sequence'=>$stage_sequence
                                );
                                array_push($stg_array1,$stg_array);

                        }

                       $var1 = $GLOBALS['$dbFramework']->insert_batch('stage_cycle_mapping',$map_array1);
                       $var2 = $GLOBALS['$dbFramework']->insert_batch('sales_stage',$stg_array1);

                   }
                    $str="1";
                    $b=array();
                    $query=$GLOBALS['$dbFramework']->query("select c.master_cyclename,c.master_cycleid from sales_stage a,stage_cycle_mapping b,
                              master_sales_cycle c where a.stage_id=b.stage_id and  b.cycle_id=c.master_cycleid and c.master_cycleid='$cycle_id' and a.stage_sequence >5 and a.stage_sequence<100 group by c.master_cycleid,c.master_cyclename ");
                    $arr=$query->result_array();

                    for($i=0;$i<count($arr);$i++){

                        $CYCLE_ID=$arr[$i]['master_cycleid'];

                        $b[$i]['CYCLE_NAME']=$arr[$i]['master_cyclename'];
                        $b[$i]['CYCLE_ID']=$arr[$i]['master_cycleid'];
                        $b[$i]['oppchk']="0";


                        $query1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks from sales_stage a,stage_cycle_mapping b,
                                                    master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid and  c.master_cycleid='$CYCLE_ID'
                                                    and a.stage_sequence >5 and a.stage_sequence<100 order by a.stage_sequence");
                        $arr1=$query1->result_array();
                        for($j=0;$j<count($arr1);$j++){
                            $b[$i]['stagedata'][$j]['stage_name']=$arr1[$j]['stage_name'];
                            $b[$i]['stagedata'][$j]['stage_id']=$arr1[$j]['stage_id'];
                            $b[$i]['stagedata'][$j]['id']=$arr1[$j]['id'];
                            $b[$i]['stagedata'][$j]['remarks']=$arr1[$j]['remarks'];
                        }
                    }

                    return array(
                        'records' => $b,
                        'str' => $str,
                    );

        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }


}

?>