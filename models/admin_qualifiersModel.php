<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_qualifiersModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_qualifiersModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }

    public function view_data(){
        try{
            $query=$GLOBALS['$dbFramework']->query("select * from lead_qualifier where lead_qualifier_type='admin' order by id ");
            $arr=$query->result_array();
            $a=array();
            for($i=0;$i<count($arr);$i++){

                  $lead_qualifier_id=$arr[$i]['lead_qualifier_id'];
                  $a[$i]['lead_qualifier_id']=$arr[$i]['lead_qualifier_id'];
                  $a[$i]['lead_qualifier_name']=$arr[$i]['lead_qualifier_name'];


                  $query1=$GLOBALS['$dbFramework']->query("select * from sales_stage_attributes where attribute_value='$lead_qualifier_id' ");
                  $arr1=$query1->result_array();
                      for($j=0;$j<count($arr1);$j++){
                          $stage_id=$arr1[$j]['stage_id'];

                          $query11=$GLOBALS['$dbFramework']->query("select c.CYCLE_NAME,c.CYCLE_ID,a.stage_name,a.stage_id from sales_stage a,stage_cycle_mapping b,
                                              sales_cycle c where a.stage_id=b.stage_id and b.cycle_id=c.CYCLE_ID and a.stage_id='$stage_id'
                                              group by c.CYCLE_NAME,c.CYCLE_ID,a.stage_name,a.stage_id ");
                          $arr11=$query11->result_array();
                              for($j1=0;$j1<count($arr11);$j1++){

                                  $a[$i]['stagedata'][$j]['CYCLE_NAME']=$arr11[$j1]['CYCLE_NAME'];
                                  $a[$i]['stagedata'][$j]['CYCLE_ID']=$arr11[$j1]['CYCLE_ID'];
                                  $a[$i]['stagedata'][$j]['stage_name']=$arr11[$j1]['stage_name'];
                                  $a[$i]['stagedata'][$j]['stage_id']=$arr11[$j1]['stage_id'];

                              }

                      }

             }
             return $a;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }
    public function insert_data($data,$qualifiername){
        try{

              $query=$GLOBALS['$dbFramework']->query("select * from lead_qualifier where LOWER(lead_qualifier_name)=LOWER('".$qualifiername."') AND lead_qualifier_type='admin'");
              //$query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('lead_qualifier','lead_qualifier_name','".ucfirst(strtolower($qualifiername))."','lead_qualifier_type','admin')");
              if($query->num_rows()>0){
                return false;
              }
              else{
                  $GLOBALS['$dbFramework']->insert('lead_qualifier', $data);
                  return TRUE;
              }
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

    public function update_qualifiername($data,$qualifiername,$qualifierid){
        try{
              
              $query=$GLOBALS['$dbFramework']->query("select * from lead_qualifier where LOWER(lead_qualifier_name)=LOWER('".$qualifiername."') AND lead_qualifier_type='admin' and lead_qualifier_id<>'$qualifierid'");
              //$query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('lead_qualifier','lead_qualifier_name','".ucfirst(strtolower($qualifiername))."','lead_qualifier_type','admin')");
              if($query->num_rows()>0){
                return false;
              }
              else{
                  $GLOBALS['$dbFramework']->query("update lead_qualifier set lead_qualifier_name='".$qualifiername."' where lead_qualifier_id='$qualifierid'");
                  return TRUE;
              }
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

    public function insert_data_queans($que_tabledata,$queid,$ansdata,$que,$qualifier_id){
        try{
              $dt=date('ymdHis');
              $query = $GLOBALS['$dbFramework']->queryWhere('qualifier_questions' , array('question_text' => $que,'lead_qualifier_id'=> $qualifier_id,'que_delete_bit'=> 1 ));
              if($query->num_rows()>0){
                return false;
              }
              else{
                  $GLOBALS['$dbFramework']->insert('qualifier_questions', $que_tabledata);

                  foreach ($ansdata as  $value) {

                      $anstext=$value->answer_text;
                      $ansID="ans";
                      $ansID.=$dt;
                      $ansid=uniqid($ansID);

                      $GLOBALS['$dbFramework']->query("update qualifier_questions set remarks='".$ansid."' where trim(lower(answer))=trim(lower('".$anstext."')) and answer is not null");

                      $GLOBALS['$dbFramework']->query("insert into qualifier_answers(question_id,answer_id,answer_text) values ('".$queid."','".$ansid."','".$anstext."')");
                  }

                  return TRUE;
              }
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }
    public function get_queansdata($queid){
        try{
              $query=$GLOBALS['$dbFramework']->query("SELECT id,question_id,question_text,answer,mandatory_bit FROM qualifier_questions
                                                      WHERE lead_qualifier_id='".$queid."' and que_delete_bit=1 order by row_order,id");
              $arr=$query->result_array();
              $a=array();
              for($i=0;$i<count($arr);$i++){

                  $question_id=$arr[$i]['question_id'];
                  $a[$i]['question_text']=$arr[$i]['question_text'];
                  $a[$i]['question_id']=$arr[$i]['question_id'];
                  $a[$i]['id']=$arr[$i]['id'];
                  $a[$i]['answer']=$arr[$i]['answer'];
                  $a[$i]['mandatory_bit']=$arr[$i]['mandatory_bit'];

                  $query1=$GLOBALS['$dbFramework']->query("SELECT answer_text,answer_id,id FROM qualifier_answers WHERE question_id='".$question_id."' order by id");
                  $arr1=$query1->result_array();
                  for($j=0;$j<count($arr1);$j++){
                      $a[$i]['ansdata'][$j]['answer_text']=$arr1[$j]['answer_text'];
                      $a[$i]['ansdata'][$j]['answer_id']=$arr1[$j]['answer_id'];

                  }

              }
              return $a;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }
    public function update_roworder($orderselected){
        try{
              $j=1;
              foreach ($orderselected as  $value) {
                   $row=$value->roworder;

                   $updateque=$GLOBALS['$dbFramework']->query("update qualifier_questions set row_order=".$j." where id=".$row." ");
                   $j++;
              }
              return TRUE;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function delete_question($questionid){
        try{
              $query=$GLOBALS['$dbFramework']->query("select * from  qualifier_questions where lower(question_id)=lower('".$questionid."')");
              if($query->num_rows()>0){

                  foreach ($query->result() as $row)
                  {
                     $qualifier_id= $row->lead_qualifier_id;

                  }
              }
              $GLOBALS['$dbFramework']->query("update qualifier_questions set que_delete_bit=0 where question_id= '".$questionid."' ");
              return $qualifier_id;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

    public function update_mandatorychk($questionid,$manbit){
        try{
              $query=$GLOBALS['$dbFramework']->query("select * from  qualifier_questions where lower(question_id)=lower('".$questionid."') ");
              if($query->num_rows()>0){

                  foreach ($query->result() as $row)
                  {
                     $qualifier_id= $row->lead_qualifier_id;

                  }
              }
              $GLOBALS['$dbFramework']->query("update qualifier_questions set mandatory_bit='$manbit' where question_id= '".$questionid."' ");
              return $qualifier_id;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

    public function update_queans($up_qId,$up_qualid,$up_qtxt,$arr_atxt){
        try{
              $query = $GLOBALS['$dbFramework']->queryWhere('qualifier_questions' , array('trim(lower(question_text))' => strtolower($up_qtxt),'lead_qualifier_id'=> $up_qualid,'que_delete_bit'=> 1));
              if($query->num_rows()>0){
                  foreach ($query->result() as $row)
                  {
                       $id1 = $row->id;
                  }
                  if($id1 <> $up_qId){
                      return false;
                  }else{
                        foreach ($arr_atxt as  $value) {

                            $ansid=$value->AId;
                            $anstxt=$value->txt;

                            $updateans=$GLOBALS['$dbFramework']->query("update qualifier_answers set answer_text='".$anstxt."' where answer_id='".$ansid."' ");
                            $updatequeans=$GLOBALS['$dbFramework']->query("update qualifier_questions set answer='".$anstxt."' where remarks='".$ansid."' and remarks is not null ");

                        }
                        return true;
                 }
              }
              else{

                 $updateque=$GLOBALS['$dbFramework']->query("update qualifier_questions set question_text='".$up_qtxt."' where id='".$up_qId."' ");
                 foreach ($arr_atxt as  $value) {

                      $ansid=$value->AId;
                      $anstxt=$value->txt;

                      $updateans=$GLOBALS['$dbFramework']->query("update qualifier_answers set answer_text='".$anstxt."' where answer_id='".$ansid."' ");
                      $updatequeans=$GLOBALS['$dbFramework']->query("update qualifier_questions set answer='".$anstxt."' where remarks='".$ansid."' and remarks is not null ");

                 }
                 return TRUE;
              }
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }


}

?>