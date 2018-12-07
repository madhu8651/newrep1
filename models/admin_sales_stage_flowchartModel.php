<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_sales_stage_flowchartModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_sales_stage_flowchartModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
     public function get_data(){
                    $cnt=0;
                    $a=array();
                    $b=array();
                    $c=array();
            try{
                    /* cycle present in opportunity */
                                $query=$GLOBALS['$dbFramework']->query("select c.CYCLE_NAME,c.CYCLE_ID from sales_stage a,stage_cycle_mapping b,
                                          sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID  and a.stage_sequence >5 and a.stage_sequence<100 group by c.CYCLE_ID,c.CYCLE_NAME ;");
                                $arr=$query->result_array();

                                for($i=0;$i<count($arr);$i++){

                                    $CYCLE_ID=$arr[$i]['CYCLE_ID'];
                                    $a[$i]['CYCLE_NAME']=$arr[$i]['CYCLE_NAME'];
                                    $a[$i]['CYCLE_ID']=$arr[$i]['CYCLE_ID'];
                                    $a[$i]['oppchk']="0";
                                }
                    return $a;
            }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }


    }

    public function get_data1($cycleid){
                    $cnt=0;
                    $a=array();
                    $b=array();
                    $c=array();
            try{
                    /* cycle present in opportunity */
                                $query=$GLOBALS['$dbFramework']->query("select c.CYCLE_NAME,c.CYCLE_ID from sales_stage a,stage_cycle_mapping b,
                                          sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID and c.CYCLE_ID='".$cycleid."' and a.stage_sequence >5 and a.stage_sequence<100 group by c.CYCLE_ID,c.CYCLE_NAME ;");
                                $arr=$query->result_array();

                                for($i=0;$i<count($arr);$i++){

                                    $CYCLE_ID=$arr[$i]['CYCLE_ID'];
                                    $a[$i]['CYCLE_NAME']=$arr[$i]['CYCLE_NAME'];
                                    $a[$i]['CYCLE_ID']=$arr[$i]['CYCLE_ID'];
                                    $a[$i]['oppchk']="0";


                                    $query1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,a.stage_sequence from sales_stage a,stage_cycle_mapping b,
                                                                sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID and  c.CYCLE_ID='$CYCLE_ID' and a.stage_sequence >5 and a.stage_sequence<100 order by a.stage_sequence");
                                    $arr1=$query1->result_array();
                                    for($j=0;$j<count($arr1);$j++){
                                        $stage_id=$arr1[$j]['stage_id'];
                                        $a[$i]['stagedata'][$j]['stage_name']=$arr1[$j]['stage_name'];
                                        $a[$i]['stagedata'][$j]['stage_id']=$arr1[$j]['stage_id'];
                                        $a[$i]['stagedata'][$j]['id']=$arr1[$j]['id'];
                                        $a[$i]['stagedata'][$j]['remarks']=$arr1[$j]['remarks'];
                                        $a[$i]['stagedata'][$j]['stage_sequence']=$arr1[$j]['stage_sequence'];

                                        $query11=$GLOBALS['$dbFramework']->query("select attribute_name,attribute_value,seqno from sales_stage_attributes where stage_id='$stage_id' order by id");
                                        $arr11=$query11->result_array();
                                        for($k=0;$k<count($arr11);$k++){
                                            $a[$i]['stagedata'][$j]['attributedata'][$k]['attribute_name']=$arr11[$k]['attribute_name'];
                                            $a[$i]['stagedata'][$j]['attributedata'][$k]['attribute_value']=$arr11[$k]['attribute_value'];
                                            $a[$i]['stagedata'][$j]['attributedata'][$k]['seqno']=$arr11[$k]['seqno'];
                                        }

                                    }

                                }
                    return $a;
            }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }


    }

    public function get_owner($cycleid){
        $userid=array();
        try{
                $output=$GLOBALS['$dbFramework']->query("call get_allocationlist('$cycleid','admin');");
                $userid=$output->result();
                return $userid;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }
    public function get_qualifier($qualid,$cycleid){
        $c=array();
        $c1=array();
        $c2=array();
        try{
                $query=$GLOBALS['$dbFramework']->query("select * from lead_qualifier where lead_qualifier_id not in
                                        (select a.attribute_value from sales_stage_attributes a,stage_cycle_mapping b
                                            where a.stage_id=b.stage_id and b.cycle_id='$cycleid'
                                            and a.attribute_name='qualifier') and lead_qualifier_type='admin' and
                                            lead_qualifier_id in (select distinct lead_qualifier_id from  qualifier_questions where que_delete_bit=1) order by id");
                if($query->num_rows()>0){

                                    $arr=$query->result_array();
                                    for($i=0;$i<count($arr);$i++){
                                        $c[$i]['lead_qualifier_name']=$arr[$i]['lead_qualifier_name'];
                                        $c[$i]['lead_qualifier_id']=$arr[$i]['lead_qualifier_id'];
                                    }
                }
                if($qualid<>""){
                    $query1=$GLOBALS['$dbFramework']->query("select * from lead_qualifier where trim(lower(lead_qualifier_id))=trim(lower('$qualid')) and lead_qualifier_type='admin'
                                                            and lead_qualifier_id in (select distinct lead_qualifier_id from  qualifier_questions where que_delete_bit=1) order by id");
                    if($query1->num_rows()>0){

                                    $arr1=$query1->result_array();
                                    for($i1=0;$i1<count($arr1);$i1++){
                                        $c1[$i1]['lead_qualifier_name']=$arr1[$i1]['lead_qualifier_name'];
                                        $c1[$i1]['lead_qualifier_id']=$arr1[$i1]['lead_qualifier_id'];
                                    }
                    }

                }

                $c2=array_merge($c,$c1);
                return $c2;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function get_qualifier1($cycleid){
        try{
               $query=$GLOBALS['$dbFramework']->query("select * from lead_qualifier where lead_qualifier_id not in
                                        (select a.attribute_value from sales_stage_attributes a,stage_cycle_mapping b
                                            where a.stage_id=b.stage_id and b.cycle_id='$cycleid'
                                            and a.attribute_name='qualifier') and lead_qualifier_type='admin'
                                            and lead_qualifier_id in (select distinct lead_qualifier_id from  qualifier_questions where que_delete_bit=1)
                                            order by id");
               return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function get_actnstage($stage_seq,$cycleid){
        try{
                $query=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,a.stage_sequence from sales_stage a,stage_cycle_mapping b,
                                      sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID and
                                           c.CYCLE_ID='$cycleid' and a.stage_sequence >5 and a.stage_sequence<'$stage_seq' order by a.stage_sequence");
                return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }
    public function save_data($hid_stageid,$hid_stage_seq,$ownervalue,$stradd_attr_value,$val_namearr,$hid_cycleid,$val_namearr1){
        $stg_arr=array();
        $chk_seq=0;
        /* ---------------------------------------------------------------------------------------------------------------------------- */
    try{
            /*$json1 = '[{
                        "val_namearr1":{
                        "value":"",
                        "numbers":"",
                        "expected_close_date":""
                        }
                    }]';
            $arr = json_decode($json1);
            $attr = (array)$arr[0]->val_namearr1;*/// convert the object into array

                    $attr = (array)$val_namearr1;// convert the object into array
                    $str=$str1="";
                    foreach($attr as $key => $val)
                    {
                         $attrname=$key;
                         $que=$GLOBALS['$dbFramework']->query("select COALESCE (min(seqno), 0)  as seqno from sales_stage_attributes a,
                                                    stage_cycle_mapping b where a.stage_id=b.stage_id and a.attribute_name='$attrname'
                                                    and b.cycle_id='$hid_cycleid'");
                         if($que->num_rows()>0){
                            foreach ($que->result() as $row)
                            {
                                $seqno=$row->seqno;
                            }
                              if($seqno >0){
                                    if($hid_stage_seq > $seqno){
                                        $stg_arr[$attrname]=$seqno;
                                    }else if($hid_stage_seq < $seqno){
                                        $stg_arr[$attrname]=$hid_stage_seq;
                                    }else if($hid_stage_seq == $seqno){
                                        $stg_arr[$attrname]=$hid_stage_seq;
                                    }else if(!$val_namearr){
                                        $stg_arr[$attrname]=$hid_stage_seq;
                                    }
                                }else{
                                  $stg_arr[$attrname]=$hid_stage_seq;
                                }
                         }
                         $str.="'".$attrname."'".",";
                    }
            /* --------------------------------------------------------------------------------------------- */
            // delete the data of attributes of sequence no equal to selected sequence of stage

            $chkque=$GLOBALS['$dbFramework']->query("select a.id from sales_stage_attributes a,stage_cycle_mapping b where a.stage_id=b.stage_id
                                            and a.seqno='$hid_stage_seq' and b.cycle_id='$hid_cycleid'");
            if($chkque->num_rows()>0){
                 foreach ($chkque->result() as $row)
                        {
                            $id=$row->id;
                            $GLOBALS['$dbFramework']->delete('sales_stage_attributes' , array('id' => $id));
                        }
            }
            /* ----------------------------------------------------------------------------------------------------------------------- */
            // delete the data of selected stage
            if($stradd_attr_value){

                $attr1 = (array)$stradd_attr_value;// convert the object into array
                foreach($attr1 as $key => $val)
                {
                        $attrname1=$key;
                        if (array_key_exists($attrname1,$attr))
                        {
                                $query = $GLOBALS['$dbFramework']->query("select * from sales_stage_attributes where stage_id='$hid_stageid' and attribute_name='$attrname1'");
                                if($query->num_rows()>0){
                                    $GLOBALS['$dbFramework']->delete('sales_stage_attributes' , array('stage_id' => $hid_stageid,'attribute_name'=> $attrname1));
                                }
                        }
                        /*if($attrname1=='value'|| $attrname1=='numbers'|| $attrname1=='expected_close_date'){
                            $query = $GLOBALS['$dbFramework']->query("select * from sales_stage_attributes where stage_id='$hid_stageid' and attribute_name='$attrname1'");
                            if($query->num_rows()>0){
                                $GLOBALS['$dbFramework']->delete('sales_stage_attributes' , array('stage_id' => $hid_stageid,'attribute_name'=> $attrname1));
                            }
                            $query = $GLOBALS['$dbFramework']->query("delete from sales_stage_attributes where stage_id='$hid_stageid' and attribute_name not in ('value','numbers','expected_close_date')");
                        }else{
                            $query = $GLOBALS['$dbFramework']->query("delete from sales_stage_attributes where stage_id='$hid_stageid' and attribute_name not in ('value','numbers','expected_close_date')");
                        }*/
                }
                $str1=$str;
                if($str1<>""){
                    $str1=rtrim($str1,",");
                }
                $query = $GLOBALS['$dbFramework']->query("delete from sales_stage_attributes where stage_id='$hid_stageid' and attribute_name not in (".$str1.")");
            }
            /* ----------------------------------------------------------------------------------------------------------------------------------------- */
            // insert the user name  for seleted stage
            if($ownervalue<>""){

                $GLOBALS['$dbFramework']->query("insert into sales_stage_attributes (stage_id, attribute_name,attribute_value,seqno) values ('$hid_stageid','allocation_matrix','$ownervalue','$hid_stage_seq')");

            }
            /* ------------------------------------------------------------------------------------------------------------------------------------------------- */
            // insert other attributes except the user names for selected stage
            if($stradd_attr_value){
                $attr2 = (array)$stradd_attr_value;// convert the object into array
                foreach($attr2 as $key => $val)
                {
                        $attrname2=$key;
                        $attr_val2=$attr2[$key];

                        if (array_key_exists($attrname2,$attr))
                        {
                            $chk_seq=$stg_arr[$attrname2];
                        }else{
                            $chk_seq=$hid_stage_seq;
                        }
                        /*if($attrname2=='value'|| $attrname2=='numbers'|| $attrname2=='expected_close_date'){
                           $chk_seq=$stg_arr[$attrname2];
                        }else{
                           $chk_seq=$hid_stage_seq;
                        }*/
                        $GLOBALS['$dbFramework']->query("insert into sales_stage_attributes (stage_id, attribute_name,attribute_value,seqno) values ('$hid_stageid','$attrname2','$attr_val2','$chk_seq')");
                }
            }
            /* --------------------------------------------------------------------------------------------------------------------------------------------------------- */
            // insert the value or numbers or expected close date for next and previous stages
            if($val_namearr){
                    $query=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,a.stage_sequence from sales_stage a,stage_cycle_mapping b
                                                             where a.stage_id=b.stage_id and b.cycle_id='$hid_cycleid' and
                                                             a.stage_sequence >'$hid_stage_seq' and a.stage_sequence<100 order by a.stage_sequence");
                    if($query->num_rows()>0){

                        foreach ($query->result() as $row)
                        {
                            $stage_id=$row->stage_id;
                            $stage_name=$row->stage_name;

                            $attr3 = (array)$val_namearr;// convert the object into array
                            foreach($attr3 as $key => $val)
                            {
                                    $attrname3=$key;
                                    $attr_val3=$attr3[$key];
                                    $chk_seq=$stg_arr[$attrname3];

                                    $que1=$GLOBALS['$dbFramework']->query("select * from sales_stage_attributes where stage_id='$stage_id' and attribute_name='$attrname3'  ");
                                    if($que1->num_rows()>0){
                                            $GLOBALS['$dbFramework']->query("update sales_stage_attributes set attribute_value='$attr_val3',seqno='$chk_seq' where stage_id='$stage_id' and attribute_name='$attrname3' ");
                                    }else{
                                            $GLOBALS['$dbFramework']->query("insert into sales_stage_attributes (stage_id, attribute_name,attribute_value,seqno) values ('$stage_id','$attrname3','$attr_val3','$chk_seq')");
                                    }
                            }
                        }
                    }
                    $query=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,a.stage_sequence from sales_stage a,stage_cycle_mapping b
                                                             where a.stage_id=b.stage_id and b.cycle_id='$hid_cycleid' and
                                                             a.stage_sequence <'$hid_stage_seq' and a.stage_sequence<100 order by a.stage_sequence");
                    if($query->num_rows()>0){

                        foreach ($query->result() as $row)
                        {
                            $stage_id=$row->stage_id;
                            $stage_name=$row->stage_name;
                            $stage_sequence=$row->stage_sequence;

                            $attr4 = (array)$val_namearr;// convert the object into array
                            foreach($attr4 as $key => $val)
                            {
                                    $attrname4=$key;
                                    $attr_val4=$attr4[$key];
                                    $chk_seq=$stg_arr[$attrname4];

                                    $que1=$GLOBALS['$dbFramework']->query("select * from sales_stage_attributes where stage_id='$stage_id' and attribute_name='$attrname4'  ");
                                    if($que1->num_rows()>0){
                                            $GLOBALS['$dbFramework']->query("update sales_stage_attributes set attribute_value='$attr_val4',attribute_remarks='$stage_sequence',seqno='$chk_seq' where stage_id='$stage_id' and attribute_name='$attrname4' and attribute_remarks is not null ");
                                    }else{
                                            $GLOBALS['$dbFramework']->query("insert into sales_stage_attributes (stage_id, attribute_name,attribute_value,seqno,attribute_remarks) values ('$stage_id','$attrname4','$attr_val4','$chk_seq','$stage_sequence')");

                                    }
                            }
                        }
                    }
            }// end of value array 1
            /* ------------------------------------------------------------------------------------------------------------------------------------------------------ */
            return TRUE;

        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function get_update_data($cycle_id){

         $b=array();
                /* cycle not present in opportunity */
         try{
                  $query=$GLOBALS['$dbFramework']->query("select c.CYCLE_NAME,c.CYCLE_ID from sales_stage a,stage_cycle_mapping b,
                            sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID and b.cycle_id='$cycle_id' and a.stage_sequence >5 and a.stage_sequence<100 group by c.CYCLE_ID,c.CYCLE_NAME ");
                  $arr=$query->result_array();

                  for($i=0;$i<count($arr);$i++){

                      $CYCLE_ID=$arr[$i]['CYCLE_ID'];

                      $b[$i]['CYCLE_NAME']=$arr[$i]['CYCLE_NAME'];
                      $b[$i]['CYCLE_ID']=$arr[$i]['CYCLE_ID'];
                      $b[$i]['oppchk']="0";


                      $query1=$GLOBALS['$dbFramework']->query("select a.id,a.stage_id,a.stage_name,b.remarks,a.stage_sequence from sales_stage a,stage_cycle_mapping b,
                                                  sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID and  c.CYCLE_ID='$CYCLE_ID' and a.stage_sequence >5 and a.stage_sequence<100 order by a.stage_sequence");
                      $arr1=$query1->result_array();
                      for($j=0;$j<count($arr1);$j++){
                          $stage_id=$arr1[$j]['stage_id'];
                          $b[$i]['stagedata'][$j]['stage_name']=$arr1[$j]['stage_name'];
                          $b[$i]['stagedata'][$j]['stage_id']=$arr1[$j]['stage_id'];
                          $b[$i]['stagedata'][$j]['id']=$arr1[$j]['id'];
                          $b[$i]['stagedata'][$j]['remarks']=$arr1[$j]['remarks'];
                          $b[$i]['stagedata'][$j]['stage_sequence']=$arr1[$j]['stage_sequence'];

                          $query11=$GLOBALS['$dbFramework']->query("select attribute_name,attribute_value,seqno from sales_stage_attributes where stage_id='$stage_id' order by id");
                          $arr11=$query11->result_array();
                          for($k=0;$k<count($arr11);$k++){
                              $b[$i]['stagedata'][$j]['attributedata'][$k]['attribute_name']=$arr11[$k]['attribute_name'];
                              $b[$i]['stagedata'][$j]['attributedata'][$k]['attribute_value']=$arr11[$k]['attribute_value'];
                              $b[$i]['stagedata'][$j]['attributedata'][$k]['seqno']=$arr11[$k]['seqno'];
                          }
                      }
                  }
                return $b;
         }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }

    }
    public function post_desc($cycleid,$stage_id,$description){
        try{
               $query=$GLOBALS['$dbFramework']->query("update stage_cycle_mapping set remarks='".$description."' where stage_id='".$stage_id."' and cycle_id='".$cycleid."'");
               return true;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }


}

?>