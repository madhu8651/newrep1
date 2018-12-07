<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_sup_sales_stageModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_sup_sales_stageModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
    public function get_data(){
                    $cnt=0;
                    $a=array();
                    $b=array();
                    $c=array();
                    $d=array();
                    $dupbit=0;

        try{
                    /* cycle present in opportunity */
                    $oppquery=$GLOBALS['$dbFramework']->query("select distinct a.cycle_id,b.CYCLE_NAME,b.togglebit from support_stage_cycle_mapping a,support_sales_cycle b
                                                where a.cycle_id=b.CYCLE_ID   and a.cycle_id  in (select cycle_id from support_opportunity_details where a.cycle_id=cycle_id)");    // check the opportunity against cycle id
                    if($oppquery->num_rows()>0){

                            $rowdata=$oppquery->result_array();
                            for($row=0;$row<count($rowdata);$row++){

                                $oppCYCLE_ID=$rowdata[$row]['cycle_id'];

                                $query=$GLOBALS['$dbFramework']->query("select c.CYCLE_NAME,c.CYCLE_ID from support_sales_stage a,support_stage_cycle_mapping b,
                                          support_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID and b.cycle_id='$oppCYCLE_ID'  and a.stage_sequence >5 and a.stage_sequence<100 group by c.CYCLE_ID,c.CYCLE_NAME ");
                                if($query->num_rows()>0){
                                      $dupbit=1;
                                }

                                $a[$row]['CYCLE_NAME']=$rowdata[$row]['CYCLE_NAME'];
                                $a[$row]['CYCLE_ID']=$rowdata[$row]['cycle_id'];
                                $a[$row]['togglebit']=$rowdata[$row]['togglebit'];
                                $a[$row]['oppchk']="1";
                            }

                    }
                     /* cycle not present in opportunity */

                    $oppquery=$GLOBALS['$dbFramework']->query("select distinct a.cycle_id,b.CYCLE_NAME,b.togglebit from support_stage_cycle_mapping a,support_sales_cycle b
                                                where a.cycle_id=b.CYCLE_ID  and a.cycle_id not in (select cycle_id from support_opportunity_details where a.cycle_id=cycle_id)");    // check the opportunity against cycle id
                    if($oppquery->num_rows()>0){

                            $rowdata=$oppquery->result_array();
                            for($row=0;$row<count($rowdata);$row++){

                                $oppCYCLE_ID=$rowdata[$row]['cycle_id'];

                                $query=$GLOBALS['$dbFramework']->query("select c.CYCLE_NAME,c.CYCLE_ID from support_sales_stage a,support_stage_cycle_mapping b,
                                          support_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID and b.cycle_id='$oppCYCLE_ID'  and a.stage_sequence >5 and a.stage_sequence<100 group by c.CYCLE_ID,c.CYCLE_NAME ");
                                if($query->num_rows()>0){
                                      $dupbit=1;
                                }

                                $b[$row]['CYCLE_NAME']=$rowdata[$row]['CYCLE_NAME'];
                                $b[$row]['CYCLE_ID']=$rowdata[$row]['cycle_id'];
                                $b[$row]['togglebit']=$rowdata[$row]['togglebit'];
                                $b[$row]['oppchk']="0";
                            }

                    }
                    $d[0]['dupbit']=$dupbit;
                    $c=array_merge($a,$b,$d);
                    return $c;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

    public function get_data2($cycleid){
                    $cnt=0;
                    $a=array();
                    $b=array();
                    $c=array();

        try{
                    /* cycle present in opportunity */
                    $oppquery=$GLOBALS['$dbFramework']->query("select distinct cycle_id from support_stage_cycle_mapping where cycle_id ='$cycleid'");    // check the opportunity against cycle id
                    if($oppquery->num_rows()>0){

                            $rowdata=$oppquery->result_array();
                            for($row=0;$row<count($rowdata);$row++){

                                $oppCYCLE_ID=$rowdata[$row]['cycle_id'];

                                //$a[$row]['CYCLE_NAME']=$rowdata[$row]['CYCLE_NAME'];
                                $a[$row]['CYCLE_ID']=$rowdata[$row]['cycle_id'];
                                $a[$row]['oppchk']="1";


                                $query=$GLOBALS['$dbFramework']->query("select c.CYCLE_NAME,c.CYCLE_ID from support_sales_stage a,support_stage_cycle_mapping b,
                                          support_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID and b.cycle_id='$oppCYCLE_ID'  and a.stage_sequence >5 and a.stage_sequence<100 group by c.CYCLE_ID,c.CYCLE_NAME ");
                                $arr=$query->result_array();

                                for($i=0;$i<count($arr);$i++){


                                    $query1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,b.master_stageid,b.mapseq,a.stage_sequence
                                                                ,(select stage_name from support_sales_stage where stage_id=b.master_stageid )
                                                                as masterstagename from support_sales_stage a,support_stage_cycle_mapping b,
                                                                support_sales_cycle c where a.stage_id=b.stage_id
                                                                and b.cycle_id=c.CYCLE_ID and
                                                                    c.CYCLE_ID='$oppCYCLE_ID' and
                                                                    a.stage_sequence >5 and a.stage_sequence<100 order by a.stage_sequence");
                                    $arr1=$query1->result_array();
                                    for($j=0;$j<count($arr1);$j++){
                                        $a[$row]['stagedata'][$j]['stage_name']=$arr1[$j]['stage_name'];
                                        $a[$row]['stagedata'][$j]['stage_id']=$arr1[$j]['stage_id'];
                                        $a[$row]['stagedata'][$j]['id']=$arr1[$j]['id'];
                                        $a[$row]['stagedata'][$j]['remarks']=$arr1[$j]['remarks'];
                                        $a[$row]['stagedata'][$j]['master_stageid']=$arr1[$j]['master_stageid'];
                                        $a[$row]['stagedata'][$j]['masterstagename']=$arr1[$j]['masterstagename'];
                                        $a[$row]['stagedata'][$j]['mapseq']=$arr1[$j]['mapseq'];
                                        $a[$row]['stagedata'][$j]['stage_sequence']=$arr1[$j]['stage_sequence'];
                                        //$a[$row]['stagedata'][$j]['CYCLE_ID']=$oppCYCLE_ID;
                                    }
                                  //$a[$i] = array(0 => $cnt);

                                }

                            }
                    }


                    $c=array_merge($a,$b);
                    return $a;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

    public function cyclename_data(){
        try{
                $query=$GLOBALS['$dbFramework']->query("select * from support_sales_cycle order by id");
                return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function get_master_stage($cycleid,$status,$stgseq){

        try{
               $b=array();
               $str="";
                /* check for master stage null */
               $query=$GLOBALS['$dbFramework']->query(" SELECT a.master_stageid FROM support_stage_cycle_mapping a,support_sales_stage b where
                                                        a.stage_id=b.stage_id and a.cycle_id='".$cycleid."' and b.stage_sequence<>100
                                                        and a.master_stageid is null;");
               if($query->num_rows()>0){
                    $str= "null";
               }else{
                    $str="notnull";
               }

                $query=$GLOBALS['$dbFramework']->query("select * from support_sales_cycle where CYCLE_ID='".$cycleid."' order by id");
                if($query->num_rows()>0){
                      foreach ($query->result() as $row)
                      {
                           $mstageid = $row->MASTERCYCLE_ID;
                      }
                }
                if($status=="add"){
                        $que1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,a.stage_sequence from support_sales_stage a,support_stage_cycle_mapping b,
                                                support_master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid and
                                                c.master_cycleid='".$mstageid."'
                                                and a.stage_sequence >= (select max(d.mapseq) from support_sales_stage e,support_stage_cycle_mapping d,
                                                support_sales_cycle f where e.stage_id=d.stage_id and d.cycle_id=f.CYCLE_ID and
                                                    f.CYCLE_ID='".$cycleid."' and d.mapseq is not null)
                                                order by a.stage_sequence");

                        if($que1->num_rows()>0){

                                $b= $que1->result();

                        }else{

                                $que=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,a.stage_sequence from support_sales_stage a,support_stage_cycle_mapping b,
                                                support_master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid and
                                                c.master_cycleid='".$mstageid."'  order by a.stage_sequence");
                                $b= $que->result();
                        }

                 } else{
                      /* get max seq no of sales stage which has master stage  */
                      $que1=$GLOBALS['$dbFramework']->query("select max(e.stage_sequence) as mapseq from support_sales_stage e,support_stage_cycle_mapping d,
                                                                support_sales_cycle f where e.stage_id=d.stage_id and d.cycle_id=f.CYCLE_ID and
                                                                f.CYCLE_ID='".$cycleid."' and d.mapseq is not null;");
                      if($que1->num_rows()>0){
                            foreach ($que1->result() as $row)
                            {
                                $max_mapseq = $row->mapseq;
                            }
                      }else{
                                $max_mapseq=0;
                      }
                      if($stgseq > $max_mapseq){

                                $que1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,a.stage_sequence from support_sales_stage a,support_stage_cycle_mapping b,
                                                support_master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid and
                                                c.master_cycleid='".$mstageid."'
                                                and a.stage_sequence >= (select max(d.mapseq) from support_sales_stage e,support_stage_cycle_mapping d,
                                                support_sales_cycle f where e.stage_id=d.stage_id and d.cycle_id=f.CYCLE_ID and
                                                    f.CYCLE_ID='".$cycleid."' and d.mapseq is not null)
                                                order by a.stage_sequence");

                                if($que1->num_rows()>0){

                                        $b= $que1->result();

                                }else{

                                        $que=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,a.stage_sequence from support_sales_stage a,support_stage_cycle_mapping b,
                                                        support_master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid and
                                                        c.master_cycleid='".$mstageid."'  order by a.stage_sequence");
                                        $b= $que->result();
                                }
                      }else{

                                $que1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,a.stage_sequence from support_sales_stage a,support_stage_cycle_mapping b,
                                                support_master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid and
                                                c.master_cycleid='".$mstageid."'
                                                and a.stage_sequence <= (select min(d.mapseq) from support_sales_stage e,support_stage_cycle_mapping d,
                                                support_sales_cycle f where e.stage_id=d.stage_id and d.cycle_id=f.CYCLE_ID and
                                                    f.CYCLE_ID='".$cycleid."' and d.mapseq is not null)
                                                order by a.stage_sequence");

                                if($que1->num_rows()>0){

                                        $b= $que1->result();
                                }
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
    public function get_master_stage_edit($id,$prevId,$nextId,$activeCycleID){
        try{
              /*$que1=$GLOBALS['$dbFramework']->query("select  distinct b.master_stageid,b.mapseq,
                                      (select stage_name from sales_stage where stage_id=b.master_stageid )
                                      as masterstagename from stage_cycle_mapping b
                                      where (
                                              b.id = IFNULL((select min(b.id) from stage_cycle_mapping b where b.id = '$prevId'),0)
                                              or  b.id = IFNULL((select max(b.id) from stage_cycle_mapping b where b.id = '$nextId'),0)
                                              or	b.id = IFNULL((select max(b.id) from stage_cycle_mapping b where b.id = '$id'),0)
                                      )  and b.mapseq is not null");*/

              if($prevId<>0){
                    $que1=$GLOBALS['$dbFramework']->query("select mapseq from support_stage_cycle_mapping where id=".$prevId.";");
                     if($que1->num_rows()>0){
                            foreach ($que1->result() as $row)
                                  {
                                          $strtpt = $row->mapseq;
                                  }
                     }


                     if($nextId<>0){
                            $que1=$GLOBALS['$dbFramework']->query("select mapseq from support_stage_cycle_mapping where id=".$nextId.";");
                            if($que1->num_rows()>0){
                                foreach ($que1->result() as $row)
                                      {
                                              $endpt=$row->mapseq;
                                      }
                            }
                             $que1=$GLOBALS['$dbFramework']->query("select * from support_sales_cycle where CYCLE_ID='".$activeCycleID."';");
                             if($que1->num_rows()>0){
                                    foreach ($que1->result() as $row)
                                          {
                                                  $MASTERCYCLE_ID=$row->MASTERCYCLE_ID;
                                          }
                             }

                             $que1=$GLOBALS['$dbFramework']->query("select a.stage_id as master_stageid,a.stage_name as masterstagename,a.stage_sequence as mapseq
                                                                      from support_sales_stage a,support_stage_cycle_mapping b,
                                                                      support_master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid and
                                                                      c.master_cycleid='".$MASTERCYCLE_ID."' and a.stage_sequence>=".$strtpt." and a.stage_sequence<=".$endpt."
                                                                      order by a.stage_sequence;");
                             if($que1->num_rows()>0){
                                  return $que1->result();
                             }
                     }else{
                            $endpt=100;
                            $que1=$GLOBALS['$dbFramework']->query("select * from support_sales_cycle where CYCLE_ID='".$activeCycleID."';");
                            if($que1->num_rows()>0){
                                foreach ($que1->result() as $row)
                                      {
                                              $MASTERCYCLE_ID=$row->MASTERCYCLE_ID;
                                      }
                            }

                            $que1=$GLOBALS['$dbFramework']->query("select a.stage_id as master_stageid,a.stage_name as masterstagename,a.stage_sequence as mapseq
                                                                  from support_sales_stage a,support_stage_cycle_mapping b,
                                                                  support_master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid and
                                                                  c.master_cycleid='".$MASTERCYCLE_ID."' and a.stage_sequence>=".$strtpt." and a.stage_sequence<=".$endpt."
                                                                  order by a.stage_sequence;");
                            if($que1->num_rows()>0){
                              return $que1->result();
                            }
                     }
              }else{
                            $endpt=100;
                            $que1=$GLOBALS['$dbFramework']->query("select mapseq from support_stage_cycle_mapping where id=".$nextId.";");
                            if($que1->num_rows()>0){
                                foreach ($que1->result() as $row)
                                      {
                                              $endpt=$row->mapseq;
                                      }
                            }
                             $que1=$GLOBALS['$dbFramework']->query("select * from support_sales_cycle where CYCLE_ID='".$activeCycleID."';");
                             if($que1->num_rows()>0){
                                    foreach ($que1->result() as $row)
                                          {
                                                  $MASTERCYCLE_ID=$row->MASTERCYCLE_ID;
                                          }
                             }

                             $que1=$GLOBALS['$dbFramework']->query("select a.stage_id as master_stageid,a.stage_name as masterstagename,a.stage_sequence as mapseq
                                                                      from support_sales_stage a,support_stage_cycle_mapping b,
                                                                      support_master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid and
                                                                      c.master_cycleid='".$MASTERCYCLE_ID."' and a.stage_sequence<=".$endpt."
                                                                      order by a.stage_sequence;");
                             if($que1->num_rows()>0){
                                  return $que1->result();
                             }
              }






        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }


    public function insert_data($data1,$data2,$stageName,$cycleId,$new_stageid) {
        try{
                $str="";
                $query = $GLOBALS['$dbFramework']->query("select a.stage_name from support_sales_stage a, support_stage_cycle_mapping b
                                                            where a.stage_id=b.stage_id and
                                                                LOWER(a.stage_name)=LOWER('".$stageName."')
                                                                and trim(lower(b.cycle_id))=trim(lower('$cycleId'))");
                //$query = $GLOBALS['$dbFramework']->query("call common_check_duplicate('insert,sales_stage','".ucfirst(strtolower($stageName))."','".$cycleId."','admin','');");
                if($query->num_rows()>0){
                  $str="0";
                }
                else{
                    $str="1";
                    $GLOBALS['$dbFramework']->insert('support_sales_stage', $data1);
                    $que=$GLOBALS['$dbFramework']->query("select id from support_sales_stage order by id desc");
                    if($que->num_rows()>0){
                            $row = $que->row();
                            $lastid = $row->id;
                    }
                    $GLOBALS['$dbFramework']->insert('support_stage_cycle_mapping', $data2);


                    $que=$GLOBALS['$dbFramework']->query("select max(a.stage_sequence) as maxid from support_sales_stage a,support_stage_cycle_mapping b where a.stage_id=b.stage_id and  a.stage_sequence<100 and b.cycle_id='$cycleId'");
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

                    $upd=$GLOBALS['$dbFramework']->query("update support_sales_stage set stage_sequence='$maxid' where id='$lastid' ");
                }

                 $b=array();
                 $oppquery=$GLOBALS['$dbFramework']->query("select distinct cycle_id from support_stage_cycle_mapping where cycle_id ='$cycleId'");    // check the opportunity against cycle id
                 if($oppquery->num_rows()>0){

                        $rowdata=$oppquery->result_array();
                        for($row=0;$row<count($rowdata);$row++){

                            $oppCYCLE_ID=$rowdata[$row]['cycle_id'];

                            $query=$GLOBALS['$dbFramework']->query("select c.CYCLE_NAME,c.CYCLE_ID from support_sales_stage a,support_stage_cycle_mapping b,
                                      support_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID and b.cycle_id='$oppCYCLE_ID' and a.stage_sequence >5 and a.stage_sequence<100 group by c.CYCLE_ID,c.CYCLE_NAME ");
                            $arr=$query->result_array();

                            for($i=0;$i<count($arr);$i++){

                                $CYCLE_ID=$arr[$i]['CYCLE_ID'];

                                $b[$row]['CYCLE_NAME']=$arr[$i]['CYCLE_NAME'];
                                $b[$row]['CYCLE_ID']=$arr[$i]['CYCLE_ID'];
                                $b[$row]['oppchk']="0";


                                $query1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,b.master_stageid,b.mapseq,a.stage_sequence
                                                                        ,(select stage_name from support_sales_stage where stage_id=b.master_stageid )
                                                                        as masterstagename from support_sales_stage a,support_stage_cycle_mapping b,
                                                                        support_sales_cycle c where a.stage_id=b.stage_id
                                                                        and b.cycle_id=c.CYCLE_ID and
                                                                            c.CYCLE_ID='$CYCLE_ID' and
                                                                            a.stage_sequence >5 and a.stage_sequence<100 order by a.stage_sequence");
                                $arr1=$query1->result_array();
                                for($j=0;$j<count($arr1);$j++){
                                    $b[$row]['stagedata'][$j]['stage_name']=$arr1[$j]['stage_name'];
                                    $b[$row]['stagedata'][$j]['stage_id']=$arr1[$j]['stage_id'];
                                    $b[$row]['stagedata'][$j]['id']=$arr1[$j]['id'];
                                    $b[$row]['stagedata'][$j]['remarks']=$arr1[$j]['remarks'];
                                    $b[$row]['stagedata'][$j]['master_stageid']=$arr1[$j]['master_stageid'];
                                    $b[$row]['stagedata'][$j]['masterstagename']=$arr1[$j]['masterstagename'];
                                    $b[$row]['stagedata'][$j]['mapseq']=$arr1[$j]['mapseq'];
                                    $b[$row]['stagedata'][$j]['stage_sequence']=$arr1[$j]['stage_sequence'];
                                    //$b[$row]['stagedata'][$j]['CYCLE_ID']=$oppCYCLE_ID;
                                }

                            }

                        }

                 }
                  return array(
                      'records' => $b,
                      'str' => $str,
                  );
                return TRUE;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function update_data($stage_id,$stage_name,$edit_cycleid,$description,$masterid,$seqid){

        try{
                $str="";
                $query = $GLOBALS['$dbFramework']->query("select a.stage_name,a.stage_id from support_sales_stage a, support_stage_cycle_mapping b
                                                            where a.stage_id=b.stage_id and
                                                                LOWER(a.stage_name)=LOWER('".$stage_name."')
                                                                and trim(lower(b.cycle_id))=trim(lower('$edit_cycleid')) and a.stage_id<>'".$stage_id."'");
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
                        $GLOBALS['$dbFramework']->query("update support_stage_cycle_mapping set remarks='$description',master_stageid='$masterid',mapseq='$seqid'
                                        where  trim(lower(stage_id))=trim(lower('$stage_id'))");
                    }
                }
                else{

                    $str="1";
                    $GLOBALS['$dbFramework']->query("update support_sales_stage set stage_name='".$stage_name."' where  trim(lower(stage_id))=trim(lower('$stage_id'))");
                    $GLOBALS['$dbFramework']->query("update support_stage_cycle_mapping set remarks='$description',master_stageid='$masterid',mapseq='$seqid'
                                         where  trim(lower(stage_id))=trim(lower('$stage_id'))");

                }


                 $b=array();
                 $oppquery=$GLOBALS['$dbFramework']->query("select distinct cycle_id from support_stage_cycle_mapping where cycle_id ='$edit_cycleid'");    // check the opportunity against cycle id
                 if($oppquery->num_rows()>0){

                        $rowdata=$oppquery->result_array();
                        for($row=0;$row<count($rowdata);$row++){

                            $oppCYCLE_ID=$rowdata[$row]['cycle_id'];

                            $query=$GLOBALS['$dbFramework']->query("select c.CYCLE_NAME,c.CYCLE_ID from support_sales_stage a,support_stage_cycle_mapping b,
                                      support_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID and b.cycle_id='$oppCYCLE_ID' and a.stage_sequence >5 and a.stage_sequence<100 group by c.CYCLE_ID,c.CYCLE_NAME ");
                            $arr=$query->result_array();

                            for($i=0;$i<count($arr);$i++){

                                $CYCLE_ID=$arr[$i]['CYCLE_ID'];

                                $b[$row]['CYCLE_NAME']=$arr[$i]['CYCLE_NAME'];
                                $b[$row]['CYCLE_ID']=$arr[$i]['CYCLE_ID'];
                                $b[$row]['oppchk']="0";


                                $query1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,b.master_stageid,b.mapseq,a.stage_sequence
                                                                        ,(select stage_name from support_sales_stage where stage_id=b.master_stageid )
                                                                        as masterstagename from support_sales_stage a,support_stage_cycle_mapping b,
                                                                        support_sales_cycle c where a.stage_id=b.stage_id
                                                                        and b.cycle_id=c.CYCLE_ID and
                                                                            c.CYCLE_ID='$CYCLE_ID' and
                                                                            a.stage_sequence >5 and a.stage_sequence<100 order by a.stage_sequence");
                                $arr1=$query1->result_array();
                                for($j=0;$j<count($arr1);$j++){
                                    $b[$row]['stagedata'][$j]['stage_name']=$arr1[$j]['stage_name'];
                                    $b[$row]['stagedata'][$j]['stage_id']=$arr1[$j]['stage_id'];
                                    $b[$row]['stagedata'][$j]['id']=$arr1[$j]['id'];
                                    $b[$row]['stagedata'][$j]['remarks']=$arr1[$j]['remarks'];
                                    $b[$row]['stagedata'][$j]['master_stageid']=$arr1[$j]['master_stageid'];
                                    $b[$row]['stagedata'][$j]['masterstagename']=$arr1[$j]['masterstagename'];
                                    $b[$row]['stagedata'][$j]['mapseq']=$arr1[$j]['mapseq'];
                                    $b[$row]['stagedata'][$j]['stage_sequence']=$arr1[$j]['stage_sequence'];
                                    //$b[$row]['stagedata'][$j]['CYCLE_ID']=$oppCYCLE_ID;
                                }

                            }

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

                       $updateque1=$GLOBALS['$dbFramework']->query("update support_sales_stage set stage_sequence='$j' where id='$row' ");

                      $j++;
                  }
                  return TRUE;
         }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }


    }

    public function get_update_data($cycle_id){
        $b=array();
        try{
                  $oppquery=$GLOBALS['$dbFramework']->query("select distinct cycle_id from support_stage_cycle_mapping where cycle_id ='$cycle_id'");    // check the opportunity against cycle id
                  if($oppquery->num_rows()>0){

                      $rowdata=$oppquery->result_array();
                      for($row=0;$row<count($rowdata);$row++){

                          $oppCYCLE_ID=$rowdata[$row]['cycle_id'];



                          $query=$GLOBALS['$dbFramework']->query("select c.CYCLE_NAME,c.CYCLE_ID from support_sales_stage a,support_stage_cycle_mapping b,
                                    support_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID and b.cycle_id='$oppCYCLE_ID' and a.stage_sequence >5 and a.stage_sequence<100 group by c.CYCLE_ID,c.CYCLE_NAME ");
                          $arr=$query->result_array();

                          for($i=0;$i<count($arr);$i++){

                              $CYCLE_ID=$arr[$i]['CYCLE_ID'];

                              $b[$row]['CYCLE_NAME']=$arr[$i]['CYCLE_NAME'];
                              $b[$row]['CYCLE_ID']=$arr[$i]['CYCLE_ID'];
                              $b[$row]['oppchk']="0";


                              $query1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,b.master_stageid,b.mapseq,a.stage_sequence
                                                                      ,(select stage_name from support_sales_stage where stage_id=b.master_stageid )
                                                                      as masterstagename from support_sales_stage a,support_stage_cycle_mapping b,
                                                                      support_sales_cycle c where a.stage_id=b.stage_id
                                                                      and b.cycle_id=c.CYCLE_ID and
                                                                          c.CYCLE_ID='$CYCLE_ID' and
                                                                          a.stage_sequence >5 and a.stage_sequence<100 order by a.stage_sequence");
                              $arr1=$query1->result_array();
                              for($j=0;$j<count($arr1);$j++){
                                  $b[$row]['stagedata'][$j]['stage_name']=$arr1[$j]['stage_name'];
                                  $b[$row]['stagedata'][$j]['stage_id']=$arr1[$j]['stage_id'];
                                  $b[$row]['stagedata'][$j]['id']=$arr1[$j]['id'];
                                  $b[$row]['stagedata'][$j]['remarks']=$arr1[$j]['remarks'];
                                  $b[$row]['stagedata'][$j]['master_stageid']=$arr1[$j]['master_stageid'];
                                  $b[$row]['stagedata'][$j]['masterstagename']=$arr1[$j]['masterstagename'];
                                  $b[$row]['stagedata'][$j]['mapseq']=$arr1[$j]['mapseq'];
                                  $b[$row]['stagedata'][$j]['stage_sequence']=$arr1[$j]['stage_sequence'];
                                  //$b[$row]['stagedata'][$j]['CYCLE_ID']=$oppCYCLE_ID;
                              }

                          }
                      }
                  }
                  return $b;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function get_cycle(){
        try{
                $que=$GLOBALS['$dbFramework']->query("select c.CYCLE_NAME,c.CYCLE_ID from support_sales_stage a,support_stage_cycle_mapping b,
                                                  support_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID
                                                  and a.stage_sequence >5 and a.stage_sequence<100 group by c.CYCLE_ID,c.CYCLE_NAME  ");
                return $que->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

    public function create_duplicate($from,$to){

                    $map_array1=$stg_array1=$stg_att_array1=array();
        try{

                    /* check for master cycle of from cycle*/
                    $mque=$GLOBALS['$dbFramework']->query("select * from support_sales_cycle where CYCLE_ID='".$from."'");
                    if($mque->num_rows()>0){
                        foreach ($mque->result() as $row)
                        {
                            $fromcycle_mcid = $row->MASTERCYCLE_ID;
                        }
                    }
                    /* check for master cycle of to cycle*/
                    $mque=$GLOBALS['$dbFramework']->query("select * from support_sales_cycle where CYCLE_ID='".$to."'");
                    if($mque->num_rows()>0){
                        foreach ($mque->result() as $row)
                        {
                            $fromcycle_mcid1 = $row->MASTERCYCLE_ID;
                        }
                    }



                                $que1=$GLOBALS['$dbFramework']->query("SELECT a.*,b.stage_name,b.stage_sequence FROM support_stage_cycle_mapping a,support_sales_stage b where
                                                                a.stage_id=b.stage_id and a.cycle_id='".$from."' and b.stage_sequence<>100");
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

                                              if($fromcycle_mcid!=$fromcycle_mcid1){
                                                    /*$que_new=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,a.stage_sequence from sales_stage a,stage_cycle_mapping b,
                                                                        master_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.master_cycleid and
                                                                    c.master_cycleid='$fromcycle_mcid1' and a.stage_sequence=".$mapseq."  order by a.stage_sequence;");
                                                    if($que_new->num_rows()>0){
                                                        foreach ($que_new->result() as $row)
                                                        {
                                                            $new_msid = $row->stage_id;
                                                            $new_stgseq = $row->stage_sequence;
                                                        }
                                                    }else{
                                                       $mapseq=$new_stgseq;
                                                    }
                                                    $master_stageid=$new_msid;*/
                                                    $master_stageid=null;
                                                    $mapseq=null;
                                              }

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

                                              $que11=$this->db->query("SELECT b.* FROM support_stage_cycle_mapping a,support_sales_stage_attributes b where
                                                                        a.stage_id=b.stage_id and
                                                                        a.cycle_id='".$from."' and b.stage_id='$stage_id';");
                                               if($que11->num_rows()>0){

                                                    $arr1=$que11->result_array();
                                                    for($i1=0;$i1<count($arr1);$i1++){

                                                              $att_stage_id=$arr1[$i1]['stage_id'];
                                                              $attribute_name=$arr1[$i1]['attribute_name'];
                                                              $attribute_value=$arr1[$i1]['attribute_value'];
                                                              $attribute_remarks=$arr1[$i1]['attribute_remarks'];
                                                              $seqno=$arr1[$i1]['seqno'];
                                                              $stg_att_array=array(
                                                                          'stage_id'=>$stage_id1,
                                                                          'attribute_name'=>$attribute_name,
                                                                          'attribute_value'=>$attribute_value,
                                                                          'attribute_remarks'=>$seqno
                                                              );
                                                              array_push($stg_att_array1,$stg_att_array);
                                                    }
                                               }
                                      }

                                     $var1 = $GLOBALS['$dbFramework']->insert_batch('support_stage_cycle_mapping',$map_array1);
                                     $var2 = $GLOBALS['$dbFramework']->insert_batch('support_sales_stage',$stg_array1);
                                     $var3 = $GLOBALS['$dbFramework']->insert_batch('support_sales_stage_attributes',$stg_att_array1);
                                }




                   $str="1";
                   $b=array();
                   $oppquery=$GLOBALS['$dbFramework']->query("select distinct cycle_id from support_stage_cycle_mapping where cycle_id ='$cycle_id'");    // check the opportunity against cycle id
                   if($oppquery->num_rows()>0){

                          $rowdata=$oppquery->result_array();
                          for($row=0;$row<count($rowdata);$row++){

                              $oppCYCLE_ID=$rowdata[$row]['cycle_id'];



                              $query=$GLOBALS['$dbFramework']->query("select c.CYCLE_NAME,c.CYCLE_ID from support_sales_stage a,support_stage_cycle_mapping b,
                                        support_sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID and b.cycle_id='$oppCYCLE_ID' and a.stage_sequence >5 and a.stage_sequence<100 group by c.CYCLE_ID,c.CYCLE_NAME ");
                              $arr=$query->result_array();

                              for($i=0;$i<count($arr);$i++){

                                  $CYCLE_ID=$arr[$i]['CYCLE_ID'];

                                  $b[$row]['CYCLE_NAME']=$arr[$i]['CYCLE_NAME'];
                                  $b[$row]['CYCLE_ID']=$arr[$i]['CYCLE_ID'];
                                  $b[$row]['oppchk']="0";


                                  $query1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,b.master_stageid,b.mapseq,a.stage_sequence
                                                                          ,(select stage_name from support_sales_stage where stage_id=b.master_stageid )
                                                                          as masterstagename from support_sales_stage a,support_stage_cycle_mapping b,
                                                                          support_sales_cycle c where a.stage_id=b.stage_id
                                                                          and b.cycle_id=c.CYCLE_ID and
                                                                              c.CYCLE_ID='$CYCLE_ID' and
                                                                              a.stage_sequence >5 and a.stage_sequence<100 order by a.stage_sequence");
                                  $arr1=$query1->result_array();
                                  for($j=0;$j<count($arr1);$j++){
                                      $b[$row]['stagedata'][$j]['stage_name']=$arr1[$j]['stage_name'];
                                      $b[$row]['stagedata'][$j]['stage_id']=$arr1[$j]['stage_id'];
                                      $b[$row]['stagedata'][$j]['id']=$arr1[$j]['id'];
                                      $b[$row]['stagedata'][$j]['remarks']=$arr1[$j]['remarks'];
                                      $b[$row]['stagedata'][$j]['master_stageid']=$arr1[$j]['master_stageid'];
                                      $b[$row]['stagedata'][$j]['masterstagename']=$arr1[$j]['masterstagename'];
                                      $b[$row]['stagedata'][$j]['mapseq']=$arr1[$j]['mapseq'];
                                      $b[$row]['stagedata'][$j]['stage_sequence']=$arr1[$j]['stage_sequence'];
                                      //$b[$row]['stagedata'][$j]['CYCLE_ID']=$oppCYCLE_ID;
                                  }
                              }

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