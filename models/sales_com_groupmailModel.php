<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_com_groupmailModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class sales_com_groupmailModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }

    public function get_data($tabtype){
            try{
            $active_module = $_SESSION['active_module_name'];
            $a=$b=$c=array();
            $j=$k=$g=0;
            $repid=$this->session->userdata('uid');/* id to be taken from session */
            if($tabtype=='assoc'){
                    $query = $GLOBALS['$dbFramework']->query("select * from support_group_emails where mail_associated_state=1 and user_type is null order by id");
                    if($query->num_rows()>0){
                            $arr=$query->result_array();
                            for($i=0;$i<count($arr);$i++){
                                    $lead_cust_opp_id =$arr[$i]['lead_cust_opp_id'];
                                    $message_id=$arr[$i]['message_id'];
                                    $type = $arr[$i]['type'];
                                    if($active_module=='sales'|| $active_module=='executive'){
                                        if($type=='lead'){
                                             $query3 = $GLOBALS['$dbFramework']->query("select * from lead_info where lead_id='".$lead_cust_opp_id."' and lead_rep_owner='".$repid."';");
                                        }else if($type=='customer'){
                                            $query3 = $GLOBALS['$dbFramework']->query("select * from customer_info where customer_id='".$lead_cust_opp_id."' and customer_rep_owner='".$repid."';");
                                        }
                                    }else{
                                        if($type=='lead'){
                                            $query3 = $GLOBALS['$dbFramework']->query("select * from lead_info where lead_id='".$lead_cust_opp_id."' and lead_manager_owner='".$repid."';");
                                        }else if($type=='customer'){
                                            $query3 = $GLOBALS['$dbFramework']->query("select * from customer_info where customer_id='".$lead_cust_opp_id."' and customer_manager_owner='".$repid."';");
                                        }
                                    }

                                    if($query3->num_rows()>0){
                                            $a[$j]['message_id'] = $arr[$i]['message_id'];
                                            $a[$j]['associate'] = $arr[$i]['type'];
                                            $a[$j]['contact_id'] =$arr[$i]['contact_id'];
                                            $a[$j]['lead_cust_opp_id'] =$arr[$i]['lead_cust_opp_id'];
                                            $a[$j]['mail_from'] =$arr[$i]['mail_from'];
                                            $a[$j]['from_name'] =$arr[$i]['from_name'];
                                            $a[$j]['mail_to'] =$arr[$i]['mail_to'];
                                            $a[$j]['mail_date'] =date('d-m-y H:i:s',strtotime($arr[$i]['mail_date']));
                                            $a[$j]['mail_subject'] =$arr[$i]['mail_subject'];
                                            $a[$j]['mail_body'] =$arr[$i]['mail_body'];
                                            $a[$j]['mail_read_state'] =$arr[$i]['mail_read_state'];
                                            $a[$j]['mail_associated_state'] =$arr[$i]['mail_associated_state'];
                                            $a[$j]['associated_user_id'] =$arr[$i]['associated_user_id'];

                                            $query2 = $GLOBALS['$dbFramework']->query("select * from support_group_email_attachments where message_id='".$message_id."' order by id");
                                            if($query2->num_rows()>0){
                                                  $arr1=$query2->result_array();
                                                  for($i1=0;$i1<count($arr1);$i1++){
                                                      $a[$j]['attachment'][$i1]['mail_attachment_filename'] = $arr1[$i1]['mail_attachment_filename'];
                                                      $a[$j]['attachment'][$i1]['mail_attachment_path'] = $arr1[$i1]['mail_attachment_path'];
                                                  }
                                                  $j++;
                                            }else{
                                              $j++;
                                            }
                                    }
                             }
                    }
            }elseif($tabtype=='unassoc'){
                    $query = $GLOBALS['$dbFramework']->query("select * from support_group_emails where mail_associated_state=0 and user_type is null order by id");
                    if($query->num_rows()>0){
                            $arr=$query->result_array();
                            for($i=0;$i<count($arr);$i++){
                                    $lead_cust_opp_id =$arr[$i]['lead_cust_opp_id'];
                                    $message_id=$arr[$i]['message_id'];
                                    $a[$k]['message_id'] = $arr[$i]['message_id'];
                                    $a[$k]['associate'] = $arr[$i]['type'];
                                    $a[$k]['contact_id'] =$arr[$i]['contact_id'];
                                    $a[$k]['lead_cust_opp_id'] =$arr[$i]['lead_cust_opp_id'];
                                    $a[$k]['mail_from'] =$arr[$i]['mail_from'];
                                    $a[$k]['from_name'] =$arr[$i]['from_name'];
                                    $a[$k]['mail_to'] =$arr[$i]['mail_to'];
                                    $a[$k]['mail_date'] =date('d-m-y H:i:s',strtotime($arr[$i]['mail_date']));
                                    $a[$k]['mail_subject'] =$arr[$i]['mail_subject'];
                                    $a[$k]['mail_body'] =$arr[$i]['mail_body'];
                                    $a[$k]['mail_read_state'] =$arr[$i]['mail_read_state'];
                                    $a[$k]['mail_associated_state'] =$arr[$i]['mail_associated_state'];
                                    $a[$k]['associated_user_id'] =$arr[$i]['associated_user_id'];
                                    $query2 = $GLOBALS['$dbFramework']->query("select * from support_group_email_attachments where message_id='".$message_id."' order by id");
                                    if($query2->num_rows()>0){
                                          $arr1=$query2->result_array();
                                          for($i1=0;$i1<count($arr1);$i1++){
                                              $a[$k]['attachment'][$i1]['mail_attachment_filename'] = $arr1[$i1]['mail_attachment_filename'];
                                              $a[$k]['attachment'][$i1]['mail_attachment_path'] = $arr1[$i1]['mail_attachment_path'];
                                          }
                                          $k++;
                                    }else{
                                      $k++;
                                    }
                             }
                    }
            }elseif($tabtype=='conflict'){
                    $cnt=0;
                    $query = $GLOBALS['$dbFramework']->query("select * from support_group_emails where (mail_associated_state=2 or mail_associated_state=4) and user_type is null order by id");
                    if($query->num_rows()>0){
                            $arr=$query->result_array();
                            for($i=0;$i<count($arr);$i++){
                                    $lead_cust_opp_id =$arr[$i]['lead_cust_opp_id'];
                                    $message_id=$arr[$i]['message_id'];
                                    $a[$g]['message_id'] = $arr[$i]['message_id'];
                                    $a[$g]['associate'] = $arr[$i]['type'];
                                    $a[$g]['contact_id'] =$arr[$i]['contact_id'];
                                    $a[$g]['lead_cust_opp_id'] =$arr[$i]['lead_cust_opp_id'];
                                    $a[$g]['mail_from'] =$arr[$i]['mail_from'];
                                    $a[$g]['from_name'] =$arr[$i]['from_name'];
                                    $a[$g]['mail_to'] =$arr[$i]['mail_to'];
                                    $a[$g]['mail_date'] =date('d-m-y H:i:s',strtotime($arr[$i]['mail_date']));
                                    $a[$g]['mail_subject'] =$arr[$i]['mail_subject'];
                                    $a[$g]['mail_body'] =$arr[$i]['mail_body'];
                                    $a[$g]['mail_read_state'] =$arr[$i]['mail_read_state'];
                                    $a[$g]['mail_associated_state'] =$arr[$i]['mail_associated_state'];
                                    $a[$g]['associated_user_id'] =$arr[$i]['associated_user_id'];
                                    $query2 = $GLOBALS['$dbFramework']->query("select * from support_group_email_attachments where message_id='".$message_id."' order by id");
                                    if($query2->num_rows()>0){
                                          $arr1=$query2->result_array();
                                          for($i1=0;$i1<count($arr1);$i1++){
                                              $a[$g]['attachment'][$i1]['mail_attachment_filename'] = $arr1[$i1]['mail_attachment_filename'];
                                              $a[$g]['attachment'][$i1]['mail_attachment_path'] = $arr1[$i1]['mail_attachment_path'];
                                          }
                                          $g++;
                                    }else{
                                      $g++;
                                    }
                             }
                    }
            }else if($tabtype == 'allmails'){
                $query = $GLOBALS['$dbFramework']->query("select * from support_group_emails and user_type is null order by id");
                if($query->num_rows()>0){
                    $arr=$query->result_array();
                    for($i=0;$i<count($arr);$i++){
                        $lead_cust_opp_id =$arr[$i]['lead_cust_opp_id'];
                        $message_id=$arr[$i]['message_id'];
                        $a[$k]['message_id'] = $message_id;
                        $a[$k]['associate'] = $arr[$i]['type'];
                        $a[$k]['contact_id'] =$arr[$i]['contact_id'];
                        $a[$k]['lead_cust_opp_id'] =$arr[$i]['lead_cust_opp_id'];
                        $a[$k]['mail_from'] =$arr[$i]['mail_from'];
                        $a[$k]['from_name'] =$arr[$i]['from_name'];
                        $a[$k]['mail_to'] =$arr[$i]['mail_to'];
                        $a[$k]['mail_date'] =date('d-m-y H:i:s',strtotime($arr[$i]['mail_date']));
                        $a[$k]['mail_subject'] =$arr[$i]['mail_subject'];
                        $a[$k]['mail_body'] =$arr[$i]['mail_body'];
                        $a[$k]['mail_read_state'] =$arr[$i]['mail_read_state'];
                        $a[$k]['mail_associated_state'] =$arr[$i]['mail_associated_state'];
                        $a[$k]['associated_user_id'] =$arr[$i]['associated_user_id'];
                        $query2 = $GLOBALS['$dbFramework']->query("select * from support_group_email_attachments where message_id='".$message_id."' order by id");
                        if($query2->num_rows()>0){
                            $arr1=$query2->result_array();
                            for($i1=0;$i1<count($arr1);$i1++){
                                 $a[$k]['attachment'][$i1]['mail_attachment_filename'] = $arr1[$i1]['mail_attachment_filename'];
                                 $a[$k]['attachment'][$i1]['mail_attachment_path'] = $arr1[$i1]['mail_attachment_path'];
                             }
                             $k++;
                        }else{
                            $k++;
                        }
                    }
                }
            }
            return $a;

        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function get_matchdata($nametype,$search_value,$matchdata){
        try{

            $active_module = $_SESSION['active_module_name'];

            $a=$b=$c=array();
            $repid=$this->session->userdata('uid');/* id to be taken from session */
            if($matchdata=='unassoc'){
                if($nametype=='number'){
                        if($active_module=='sales' || $active_module=='executive'){
                            $query = $GLOBALS['$dbFramework']->query(" SELECT b.* FROM contact_details b,customer_info a
                                                                        where b.lead_cust_id=a.customer_id and a.customer_rep_owner='".$repid."'
                                                                    and json_extract(b.contact_number, '$.*') like '%$search_value%';");
                            return $query->result();
                        }else{
                            $query = $GLOBALS['$dbFramework']->query(" SELECT b.* FROM contact_details b,customer_info a
                                                                        where b.lead_cust_id=a.customer_id and a.customer_manager_owner='".$repid."'
                                                                    and json_extract(b.contact_number, '$.*') like '%$search_value%';");
                            return $query->result();
                        }
                }else{
                        if($active_module=='sales' || $active_module=='executive'){
                            $query = $GLOBALS['$dbFramework']->query(" SELECT b.* FROM contact_details b,customer_info a
                                                                        where b.lead_cust_id=a.customer_id and a.customer_rep_owner='".$repid."'
                                                                    and b.contact_name like '%$search_value%';");
                            return $query->result();
                        }else{
                            $query = $GLOBALS['$dbFramework']->query(" SELECT b.* FROM contact_details b,customer_info a
                                                                        where b.lead_cust_id=a.customer_id and a.customer_manager_owner='".$repid."'
                                                                    and b.contact_name like '%$search_value%';");
                            return $query->result();

                        }
                }
           }else{

                        $str=explode('_',$nametype);
                        $str1=$str[0];
                        if($str1=='msg'){
                                if($active_module=='sales' || $active_module=='executive'){
                                    $query = $GLOBALS['$dbFramework']->query(" SELECT b.* FROM contact_details b,customer_info a
                                                                                where b.lead_cust_id=a.customer_id and a.customer_rep_owner='".$repid."'
                                                                            and json_extract(b.contact_email, '$.*') like '%$search_value%';");
                                    return $query->result();
                                }else{
                                    $query = $GLOBALS['$dbFramework']->query(" SELECT b.* FROM contact_details b,customer_info a
                                                                                where b.lead_cust_id=a.customer_id and a.customer_manager_owner='".$repid."'
                                                                            and json_extract(b.contact_email, '$.*') like '%$search_value%';");
                                    return $query->result();
                                }
                        }else{
                            $leadcustid=$str[1];
                            $query = $GLOBALS['$dbFramework']->query(" select * from opportunity_details where lead_cust_id='".$leadcustid."' and closed_reason is null;");
                            return $query->result();
                        }



           }

        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function remove_unassoc($lead_cust_id,$hidmsgid,$hidemail,$type,$pagetype){
        try{
            $repid=$this->session->userdata('uid');/* id to be taken from session */
            if($pagetype=='conflict'){
                $str=explode('_',$hidmsgid);
                $str1=$str[0];
                $msgid=$str[1];
                $hidmsgid=$msgid;
                $msgid1=$str[2];
            }
            if($pagetype=='unassoc' || ($pagetype=='conflict' && $str1=='msg')){
                    $query=$GLOBALS['$dbFramework']->query("select distinct contact_id from contact_details where lead_cust_id='".$lead_cust_id."'; ");
                    if($query->num_rows()>0){
                         foreach ($query->result() as $row)
                                {
                                    $contact_id=$row->contact_id;
                                }
                    }
                    /* update support group mail */
                    $query=$GLOBALS['$dbFramework']->query("update support_group_emails set contact_id='".$contact_id."',lead_cust_opp_id='".$lead_cust_id."',
                                                                type='".$type."', associated_user_id='".$repid."',mail_associated_state=1 where message_id='".$hidmsgid."'");



                    $query1=$GLOBALS['$dbFramework']->query("select * from contact_details where json_extract(contact_email, '$.*') like '%$hidemail%' and contact_id='".$contact_id."' ; ");
                    if($query1->num_rows()==0){
                            $query=$GLOBALS['$dbFramework']->query("UPDATE contact_details SET contact_email=json_array_append(contact_email,'$.email','".$hidemail."')
                                                                          WHERE contact_id='".$contact_id."'");
                    }
            }else if($pagetype=='conflict' && $str1=='conf'){
                   $dt = date('ymdHis');
                   $query=$GLOBALS['$dbFramework']->query("select distinct stage_owner_id,opportunity_stage from opportunity_details where opportunity_id='".$lead_cust_id."' and stage_owner_id is not null ; ");
                   if($query->num_rows()>0){
                         foreach ($query->result() as $row)
                                {
                                    $repid=$row->stage_owner_id;
                                    $opportunity_stage=$row->opportunity_stage;
                                }
                   }else{
                        $query=$GLOBALS['$dbFramework']->query("select distinct owner_id,opportunity_stage from opportunity_details where opportunity_id='".$lead_cust_id."' and owner_id is not null ; ");
                        if($query->num_rows()>0){
                               foreach ($query->result() as $row)
                                      {
                                          $repid=$row->owner_id;
                                          $opportunity_stage=$row->opportunity_stage;
                                      }
                        }else{
                                $query=$GLOBALS['$dbFramework']->query("select distinct manager_owner_id,opportunity_stage from opportunity_details where opportunity_id='".$lead_cust_id."' and manager_owner_id is not null ; ");
                                if($query->num_rows()>0){
                                       foreach ($query->result() as $row)
                                              {
                                                  $repid=$row->manager_owner_id;
                                                  $opportunity_stage=$row->opportunity_stage;
                                              }
                                }
                        }
                   }

                   $query=$GLOBALS['$dbFramework']->query("select distinct contact_id from support_group_emails where message_id='".$msgid1."'; ");
                    if($query->num_rows()>0){
                         foreach ($query->result() as $row)
                                {
                                    $contact_id=$row->contact_id;
                                }
                    }
                    $log_name='Incoming email from '.$hidemail;
                    $insertque=$GLOBALS['$dbFramework']->query("insert into rep_log (rep_id, leademployeeid, leadid, phone, logtype, call_type,
                                                                    path, note, time, message_id, type,
                                                                    log_name, module_id, stage_id)values ('".$repid."','".$contact_id."','".$lead_cust_id."','',
                                                                    'EM594ce66d07b9f83','complete','no_path','','".$dt."','".$msgid."','".$type."','".$log_name."','sales','".$opportunity_stage."')");

                   $query=$GLOBALS['$dbFramework']->query("update support_group_emails set mail_associated_state=1 where message_id='".$msgid1."'");

            }


            return true;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }



}
?>

