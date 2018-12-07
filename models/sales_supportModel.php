<?php
include_once (ROOT_PATH . '/core/LConnectApplicationException.php');
include_once (ROOT_PATH . '/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH . '/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH . '/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_supportModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class sales_supportModel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

public function chk_parameters_opp($opportunity_ids, $process_type, $product_id) {
        try {
            $userid = $this->session->userdata('uid');
            $query = $GLOBALS['$dbFramework']->query("select opportunity_industry,opportunity_location from
                    opportunity_details where opportunity_id='$opportunity_ids'");
            $result = $query->result();
            $industry = $result[0]->opportunity_industry;
            $location = $result[0]->opportunity_location;

            $query1 = $GLOBALS['$dbFramework']->query("SELECT scp.cycle_id,(select ss.stage_id
                from support_stage_cycle_mapping AS scm, support_sales_stage AS ss
                where scm.cycle_id=scp.cycle_id AND ss.stage_id=scm.stage_id AND ss.stage_sequence=6) AS stage_id
                FROM `support_sales_cycle_parameters` AS scp
                WHERE scp.parameter_product_node ='$product_id' AND scp.parameter_industry_node ='$industry' AND 
                scp.parameter_location_node='$location'  AND scp.parameter_for='$process_type' AND scp.cycle_togglebit = 1
                GROUP BY scp.parameter_id, scp.cycle_id");
            $result_array = $query1->result();
            $count_row = $query1->num_rows();
            if ($count_row > 0){
                $stage_id = $result_array[0]->stage_id;
                $cycle_id = $result_array[0]->cycle_id;
                $qualifier=  $this->chk_qualifier($stage_id);
                $details=array(
                    'stage_id'=>$stage_id, 
                    'cycle_id'=>$cycle_id,
                    'opportunity_id'=>$opportunity_ids,
                    'industry'=>$industry,
                    'location'=>$location,
                );
                return $qualifier_details=array(
                    'qualifier'=>$qualifier,
                    'details'=>$details
                );
            }else {
                return $stage = 0;
            }
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function chk_qualifier($stageid){
        try {
            $userid=$this->session->userdata('uid');
               
                $query = $GLOBALS['$dbFramework']->query("select attribute_name,attribute_value 
                   from support_sales_stage_attributes where stage_id='$stageid' and attribute_name='qualifier'");
                $count = $query->num_rows();
                if($count > 0){
                    foreach ($query->result() as $row){
                        $qualifier_id= $row->attribute_value;
                    }
                
                    $query12 = $GLOBALS['$dbFramework']->query("SELECT * FROM lead_qualifier WHERE lead_qualifier_id='$qualifier_id' 
                                and lead_qualifier_type='support' ORDER BY id;");
                    $arr=$query12->result_array();
                $a=array();
	        for($i=0;$i<count($arr);$i++){
	            $lead_qualifier_id=$arr[$i]['lead_qualifier_id'];
	            $a[$i]['lead_qualifier_name']=$arr[$i]['lead_qualifier_name'];
	            $a[$i]['lead_qualifier_id']=$arr[$i]['lead_qualifier_id'];
                    
	            $query1 = $GLOBALS['$dbFramework']->query("SELECT * FROM qualifier_questions WHERE lead_qualifier_id='$lead_qualifier_id' 
	            	AND que_delete_bit=1 ORDER BY row_order");
	            $arr1=$query1->result_array();
                    
	            for($j=0;$j<count($arr1);$j++){
	                $question_id=$arr1[$j]['question_id'];
	                $a[$i]['question_data'][$j]['question_type']=$arr1[$j]['question_type'];
	                $a[$i]['question_data'][$j]['question_id']=$arr1[$j]['question_id'];
	                $a[$i]['question_data'][$j]['question_text']=$arr1[$j]['question_text'];
	                $a[$i]['question_data'][$j]['answer']=$arr1[$j]['answer'];
	                $a[$i]['question_data'][$j]['mandatory_bit']=$arr1[$j]['mandatory_bit'];

	                $query11= $GLOBALS['$dbFramework']->query("SELECT * FROM qualifier_answers WHERE question_id='$question_id' ORDER BY id");
	                $arr11=$query11->result_array(); 
	                for($j1=0;$j1<count($arr11);$j1++){
	                    $a[$i]['question_data'][$j]['answer_data'][$j1]['answer_id']=$arr11[$j1]['answer_id'];
	                    $a[$i]['question_data'][$j]['answer_data'][$j1]['answer_text']=$arr11[$j1]['answer_text'];
	                }
	            }
	        }
                return $a;
                }else{
                    return 0;
                }
	        
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function getProcessType($userid) {
        try {
            $query = $GLOBALS['$dbFramework']->query("select a.lookup_id,a.lookup_value,b.map_type from lookup a, user_mappings b where a.lookup_name='support_process' and a.lookup_id=b.map_id and b.user_id='$userid'");
            return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function getCustomers(){
        try {
            $query = $GLOBALS['$dbFramework']->query("SELECT a.customer_id,a.customer_name,b.purchase_end_date  FROM customer_info a, product_purchase_info b 
                    where a.customer_id=b.customer_id and b.purchase_end_date > CURDATE();");
            return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function getOpportunities(){
        try {
            $query = $GLOBALS['$dbFramework']->query("select opportunity_id,opportunity_name,lead_cust_id,
                (select CASE sell_type 
                    WHEN 'new_sell' THEN (select lead_name from lead_info where lead_id=lead_cust_id)
                    ELSE (select customer_name from customer_info where customer_id=lead_cust_id)
                END) AS lead_cust_name from opportunity_details where closed_status!=100 and closed_reason is null");
            return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

     public function getContactList($customerid) {
        try {
            $query = $GLOBALS['$dbFramework']->query("select contact_id,contact_name from contact_details where lead_cust_id='$customerid'");
            return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function getproductList($oppo_id) {
        try {
            $query = $GLOBALS['$dbFramework']->query("select a.opportunity_product as prod_id,b.hvalue2 as prod_name from opportunity_details a ,hierarchy b where
            a.opportunity_product=b.hkey2 and opportunity_id='$oppo_id'");
            return $result = $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function customerproduct($cust_id, $userid) {
        try {
            $query = $GLOBALS['$dbFramework']->query("SELECT a.product_id as prod_id,b.hvalue2 as prod_name FROM product_purchase_info a,hierarchy b ,user_mappings c where 
                a.product_id=b.hkey2 and a. product_id=c.map_id and 
                customer_id='$cust_id' and user_id='$userid'");
            return $result = $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function getContactListopp($oop_id) {
        try {
            $query = $GLOBALS['$dbFramework']->query("select REPLACE(opportunity_contact,':',',') as contact_id from opportunity_details where opportunity_id='$oop_id'");
            $result = $query->result();
            $contactids = $result[0]->contact_id;
            $query1 = $GLOBALS['$dbFramework']->query("select contact_id,contact_name from contact_details where contact_id in ('$contactids')");
            return $result_array = $query1->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function insert_data($data1,$data2,$data3,$type1_2) {
        try {
            $response=1; 
            $status_data=$data1;
            $action='passed qualifier';
            foreach($type1_2 as $value){
                $questype=$value->questype;
                $quesid=$value->quesid;
                $ansid=$value->ansid;
                if($questype==1){
                    $query=$this->db->query("select * from qualifier_questions where lower(question_id)=lower('$quesid')");
                    if($query->num_rows()>0) {
                        foreach ($query->result() as $row) {
                            $right_ans= $row->answer;
                        }
                    }
                    $query1=$this->db->query("select * from qualifier_answers where lower(answer_id)=lower('$ansid')");
                    if($query1->num_rows()>0) {
                        foreach ($query1->result() as $row) {
                            $given_ans= $row->answer_text;
                        }
                    }
                    if($right_ans<>$given_ans) {
                        $response=0; 
                        $status='fail';
                        $action='failed qualifier';
                    } else {
                        $response=1;
                        $status='success';
                    }
                }
            }
            if($response==1){
                 $status_data = array_merge(array('remarks' => 'success'), $status_data);
            }else{
                 $status_data = array_merge(array('remarks' => 'fail'), $status_data);
            }
            $user =$this->session->userdata('uid');
            $data4=array(
                'mapping_id'=>uniqid(rand(),TRUE),
                'request_id'=>$data2['request_id'],
                'from_user_id'=>$user,
                'opp_cust_id'=>$status_data['opportunity_id'],
                'to_user_id'=>$user,
                'cycle_id'=>$data2['cycle_id'],  
                'stage_id'=>$status_data['stageid'],  
                'module'=>'sales',
                'action'=>$action,
                'process_type'=>$data2['process_type'],
                'state'=>0,

            );
            $insert1 = $GLOBALS['$dbFramework']->insert('qualifier_tran_details',$status_data); 
            $insert2 = $GLOBALS['$dbFramework']->insert('support_user_map',$data4);
            if($response==1){
                 $req_user_details=$this->create_request($data2,$data3);
                 return $req_user_details;
            }else{
            return $response;
            }
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function create_request($data2,$data1_3){
    try{
        $req_user_id=$this->getToken();
            if($data2['request_for']=='customer'){
                $req_userid='c'.$req_user_id;
                $data2 = array_merge(array('request_user_id' => $req_userid), $data2);
            }else{
                $req_userid='O'.$req_user_id;
                $data2 = array_merge(array('request_user_id' => $req_userid), $data2);
            }
        $query11= $GLOBALS['$dbFramework']->insert_batch('support_user_map', $data1_3); 
        $insert4 = $GLOBALS['$dbFramework']->insert('support_opportunity_details',$data2);
        if($query11==true && $insert4==true){
             RETURN $req_userid;
        }
    }
    catch (LConnectApplicationException $e) {
      $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
      throw $e;
    }
}
public function getToken(){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet); 
    for ($i=0; $i < 7; $i++) {
        $token .= $codeAlphabet[rand(0, $max-1)];
    }
        return $token;
}

public function fetch_new_request($user){
    try{
        
        $query = $GLOBALS['$dbFramework']->query("SELECT a.request_id,DATE_FORMAT(a.request_tat,'%d/%m/%Y') as tat,
            a.cricticality,a.request_user_id,a.closed_status, a.process_type,a.request_for,a.request_name,b.hvalue2 as prod,c.hvalue2 as ind,
            REPLACE(request_contact,':',',') as contact ,
            d.lookup_value ,(select CASE a.request_for WHEN 'opportunity' THEN 
            (select opportunity_name from opportunity_details where opportunity_id=a.opp_cust_id)
            ELSE (select customer_name from customer_info where customer_id=a.opp_cust_id)
            END) AS oppo_cust_name from support_opportunity_details a left join  
            hierarchy b on a.request_product=b.hkey2 
            left join hierarchy c on a.request_industry=c.hkey2 
            left join lookup d on a.process_type=d.lookup_id where 
            request_id in (select request_id from support_user_map
            where to_user_id='$user' and action in('ownership_assigned','stage_assigned','ownership_reassigned') and module='sales' and state=1)
            and request_id not in (select request_id from support_user_map
            where from_user_id='$user' and action='rejected' and module='sales' and state=1)
            and owner_status=1 and closed_status<>100");
        
            $result_array=$query->result();
            for ($i=0;$i<count($result_array);$i++){
            $contact_names= $result_array[$i]->contact;
            $realArray = explode(',', $contact_names);
            $contacts = "'" . implode("','", $realArray) . "'";
            $query1 = $GLOBALS['$dbFramework']->query("select contact_name, JSON_UNQUOTE(contact_number->'$.mobile[0]') as contact_number from contact_details where contact_id in ($contacts)");
            $res = $query1->result();
            for($j=0;$j<count($res);$j++){
                $result_array[$i]->contact_details[$j]['contact_name'] = $res[$j]->contact_name;
                $result_array[$i]->contact_details[$j]['contact_number'] = $res[$j]->contact_number;
             }
        }
        return $result_array;               
    } catch (LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
}
public function fetch_inprogress_request($user){
    try{
        $query = $GLOBALS['$dbFramework']->query("select a.request_id,a.request_contact,a.opp_cust_id,DATE_FORMAT(a.request_tat,'%d/%m/%Y') as tat,
            a.cricticality,a.request_user_id,a.request_for,a.process_type, a.cycle_id,a.process_type,a.request_stage,a.request_name,b.hvalue2 as prod,c.hvalue2 as ind,
            REPLACE(request_contact,':',',') as contact,
            d.lookup_value ,(select CASE a.request_for WHEN 'opportunity' THEN 
            (select opportunity_name from opportunity_details where opportunity_id=a.opp_cust_id)
            ELSE (select customer_name from customer_info where customer_id=a.opp_cust_id)
            END) AS oppo_cust_name,e.user_name from support_opportunity_details a left join  
            hierarchy b on a.request_product=b.hkey2 
            left join hierarchy c on a.request_industry=c.hkey2 
            left join lookup d on a.process_type=d.lookup_id left join user_details e on a.owner_id=e.user_id
            where a.owner_id ='$user' and closed_status<>100");
            $result_array=$query->result();
            for ($i=0;$i<count($result_array);$i++){
            $contact_names= $result_array[$i]->contact;
            $realArray = explode(',', $contact_names);
            $contacts = "'" . implode("','", $realArray) . "'";
            $query1 = $GLOBALS['$dbFramework']->query("select contact_name, JSON_UNQUOTE(contact_number->'$.mobile[0]') as contact_number from contact_details where contact_id in ($contacts)");
            $res = $query1->result();
            for($j=0;$j<count($res);$j++){
                $result_array[$i]->contact_details[$j]['contact_name'] = $res[$j]->contact_name;
                $result_array[$i]->contact_details[$j]['contact_number'] = $res[$j]->contact_number;
             }
        }
        return $result_array;               
    } catch (LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
}
public function get_closed_tickets($user){
    try{
        $query = $GLOBALS['$dbFramework']->query("select a.request_id,DATE_FORMAT(a.request_tat,'%d/%m/%Y') as tat,
            a.cricticality,a.request_user_id,a.request_for,a.process_type, a.request_name,b.hvalue2 as prod,c.hvalue2 as ind,
            REPLACE(request_contact,':',',') as contact ,
            d.lookup_value ,(select CASE a.request_for WHEN 'opportunity' THEN 
            (select opportunity_name from opportunity_details where opportunity_id=a.opp_cust_id)
            ELSE (select customer_name from customer_info where customer_id=a.opp_cust_id)
            END) AS oppo_cust_name from support_opportunity_details a left join  
            hierarchy b on a.request_product=b.hkey2 
            left join hierarchy c on a.request_industry=c.hkey2 
            left join lookup d on a.process_type=d.lookup_id
            where  a.owner_id='$user' and closed_status=100");
            $result_array=$query->result();
            for ($i=0;$i<count($result_array);$i++){
            $contact_names= $result_array[$i]->contact;
            $realArray = explode(',', $contact_names);
            $contacts = "'" . implode("','", $realArray) . "'";
            $query1 = $GLOBALS['$dbFramework']->query("select contact_name, JSON_UNQUOTE(contact_number->'$.mobile[0]') as contact_number from contact_details where contact_id in ($contacts)");
            $res = $query1->result();
            for($j=0;$j<count($res);$j++){
                $result_array[$i]->contact_details[$j]['contact_name'] = $res[$j]->contact_name;
                $result_array[$i]->contact_details[$j]['contact_number'] = $res[$j]->contact_number;
             }
        }
        return $result_array;               
    } catch (LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
 }   
 public function rep_owner($request_id) {
        try{
            $query=$GLOBALS['$dbFramework']->query("select * from support_opportunity_details where request_id='$request_id'");
            $req_owner=$query->result();
            $req_status= $req_owner[0]->owner_status;
                if($req_status==1){
                   return $reuest_details=array(
                    'cycle_id' =>$req_owner[0]->cycle_id,
                    'request_stage'=>$req_owner[0]->request_stage,
                    'opp_cust_id'=>$req_owner[0]->opp_cust_id,
                    'process_type'=>$req_owner[0]->process_type
                  );
                }else{
                    return 0;
                }
                return $query->result();
       } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }
  public function accept_request($request_id,$data) {
        try{
            $update = $GLOBALS['$dbFramework']->update('support_opportunity_details' ,$data, array('request_id' => ($request_id)));
            return $update;
       } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }
    public function update_transaction($request_id){
      try{
        $query=$GLOBALS['$dbFramework']->query("update support_user_map set state=0 where request_id='$request_id' and module='sales' and action in ('ownership_assigned','ownership_reassigned') ");
        return TRUE;
      }catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
    }
    public function insert_transaction($data) {
       try{
         $insert = $GLOBALS['$dbFramework']->insert('support_user_map',$data); 
     return $insert;
     } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
     }
   }
    public function last_reject($request_id,$remarks){
      try{
        
        $userid=$this->session->userdata('uid');
        $query=$GLOBALS['$dbFramework']->query("SELECT * from support_user_map where state=1 and request_id='$request_id' and action in ('ownership_assigned','ownership_reassigned') and module='sales'");
        $count_reject = $query->num_rows();
        $result=$query->result();
        $dt = date('YmdHis');
        $data1= array(
            'mapping_id' =>uniqid(rand(),TRUE),
            'request_id' =>$request_id,
            'cycle_id' =>$result[0]->cycle_id,
            'stage_id'=>$result[0]->stage_id,
            'process_type'=>$result[0]->process_type,
            'opp_cust_id'=>$result[0]->opp_cust_id,
            'state' =>1,
            'action'=>'rejected',
            'module'=>"sales",
            'from_user_id'=>$userid,
            'to_user_id'=>$result[0]->from_user_id,
            'timestamp'=>$dt,
            'remarks'=>$remarks
            );
            $insert = $GLOBALS['$dbFramework']->insert('support_user_map',$data1);
            if($insert==true){
                $query2=$GLOBALS['$dbFramework']->query("SELECT * from support_user_map where request_id='$request_id' and action='rejected' and state=1 and module='sales'");
                $count_reject1 = $query2->num_rows();

                if($count_reject1==$count_reject){

                    $query3=$GLOBALS['$dbFramework']->query("UPDATE support_opportunity_details as sod SET sod.owner_manager_status= 3 where sod.request_id='$request_id'");

                    $query4=$GLOBALS['$dbFramework']->query("UPDATE support_user_map set state=0 where request_id='$request_id' and state=1 and module='sales' and action IN ('ownership_assigned','ownership_reassigned')"); 
                }
            return true; 

            }
           
      }catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
    }
    public function opportunity_contacts($oop_id){
     try{
        $query=$GLOBALS['$dbFramework']->query(" select REPLACE(opportunity_contact,':',',') as contacts_id  from opportunity_details where opportunity_id='$oop_id'");
        $aray_result=$query->result();
        $contact_ids=$aray_result[0]->contacts_id;
        $query1=$GLOBALS['$dbFramework']->query(" select contact_name,contact_id from contact_details where  contact_id in('$contact_ids')");
        return $contact_result=$query1->result();
    }catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    } 
  }
  public function update_details($data,$request_id){
        try{
            $update = $GLOBALS['$dbFramework']->update('support_opportunity_details' ,$data, array('request_id' => ($request_id)));
            return $update;
       } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }
     public function close_request_details($remarks,$request_id,$user_id){
        try{
            $query=$GLOBALS['$dbFramework']->query("select * from support_opportunity_details where  request_id='$request_id'");
            $result=$query->result();
            $dt = date('YmdHis');
            $data1= array(
                'mapping_id' =>uniqid(rand(),TRUE),
                'request_id' =>$request_id,
                'cycle_id' =>$result[0]->cycle_id,
                'stage_id'=>$result[0]->request_stage,
                'process_type'=>$result[0]->process_type,
                'opp_cust_id'=>$result[0]->opp_cust_id,
                'state' =>1,
                'action'=>'closed',
                'module'=>"sales",
                'from_user_id'=>$user_id,
                'to_user_id'=>$user_id,
                'timestamp'=>$dt,
                );
            $data2= array(
                'closed_status' =>100,
                'closed_reason' =>'',
               );
              $insert= $GLOBALS['$dbFramework']->insert('support_user_map',$data1);
              $update = $GLOBALS['$dbFramework']->update('support_opportunity_details' ,$data2, array('request_id' => ($request_id)));
              if($insert==true && $update==true){
                  return 1;
              }
           
          
       } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }
     public function get_contacts($contacts) {
        try{
           
            $query=$GLOBALS['$dbFramework']->query("SELECT GROUP_CONCAT(cd.contact_name ORDER BY cd.contact_name SEPARATOR ',') AS contact_name
                FROM contact_details AS cd WHERE cd.contact_id IN ('$contacts')");
            return $result=$query->result();
            
       } catch (LConnectApplicationException $e) {
            
        }
     }
     
public function get_request_details($request_id){
    try{
        // Fetching Support details from the support_opp_details table.
        $query = $GLOBALS['$dbFramework']->query("
            SELECT a.request_id,DATE_FORMAT(a.request_tat,'%d/%m/%Y') as tat,a.opp_cust_id,a.cycle_id,a.request_stage,a.remarks,a.closed_status,a.owner_id,a.manager_owner_id,a.created_by,a.owner_status,a.cricticality,a.request_product,a.request_industry,a.request_location,a.process_type,a.request_user_id,a.process_type, a.request_name,b.hvalue2 as prod,
                c.hvalue2 as ind,k.hvalue2 as location,REPLACE(request_contact,':',',') as contact ,
                e.stage_name,e.stage_sequence,f.user_name as manager_name,g.user_name as rep_name,
                d.lookup_value as processtype ,
                (SELECT CASE a.request_for WHEN 'opportunity' THEN 
	               (SELECT opportunity_name from opportunity_details where opportunity_id=a.opp_cust_id)
	            ELSE (select customer_name from customer_info where customer_id=a.opp_cust_id)
	            END) AS oppo_cust_name,
                JSON_UNQUOTE(j.contact_number->'$.phone[0]') as contact_number,
	            JSON_UNQUOTE(j.contact_email->'$.email[0]') as contact_email
                FROM support_opportunity_details a 
                left join hierarchy b 
                on 
                a.request_product=b.hkey2 
                left join hierarchy c 
                on 
                a.request_industry=c.hkey2 
                left join lookup d 
                on 
                a.process_type=d.lookup_id 
                left join support_sales_stage e 
                on 
                a.request_stage=e.stage_id 
                left join user_details f 
                on 
                a.manager_owner_id=f.user_id
                left join user_details g 
                on 
                a.owner_id=g.user_id
                left join hierarchy k 
                on 
                a.request_location=k.hkey2 
                left join contact_details j 
                on 
                a.request_contact=j.contact_id 
	           where a.request_id='$request_id'");
            $result=$query->result();
            // Fetching Contact Information
            $contact_id=$result[0]->contact;
            $contact=$this->get_contacts($contact_id);
            // Fetching Stage Id, Stage Name, Stage Attribute for the request id.
            $cycle_id=$result[0]->cycle_id;
            $stage_id=$result[0]->request_stage;
            $stage_sequence=$result[0]->stage_sequence;
            $query1=$GLOBALS['$dbFramework']->query("
                SELECT a.stage_id,a.stage_name,a.stage_sequence,d.lookup_value,
            c.attribute_name,c.attribute_value from support_sales_stage a left join  support_stage_cycle_mapping b on a.stage_id = b.stage_id
            left join support_sales_stage_attributes c on a.stage_id=c.stage_id left join lookup d on c.attribute_name=d.lookup_id
             where ((b.cycle_id='$cycle_id' and b.remarks is not null 
             and a.stage_id='$stage_id' and c.attribute_remarks is null) or
            (b.cycle_id='$cycle_id' 
            and a.stage_id=(select x.stage_id from support_sales_stage x, support_stage_cycle_mapping y
            where  x.stage_id=y.stage_id and x.stage_sequence > $stage_sequence and y.cycle_id='$cycle_id' and c.attribute_remarks is null order by x.stage_sequence  limit 1)))
             order by a.stage_sequence");
            $stage_attributes=$query1->result();
            $query2=$GLOBALS['$dbFramework']->query("SELECT a.support_stage_id,a.support_attribute_name AS attribute_name, a.support_attribute_name AS lookup_value,
                a.support_attribute_value as attribute_value,a.support_attribute_remarks,a.request_id,a.mapping_id,b.stage_sequence 
                FROM support_stage_attributes a,support_sales_stage b where a.support_stage_id=b.stage_id and a.support_stage_id='$stage_id' and request_id='$request_id'");
            $stage_attribute_val=$query2->result();
            
            $request_details=array(
                'request'=>$result,
                'contact'=>$contact,
                'stage_attributes'=>$stage_attributes,
                'stage_value'=>$stage_attribute_val,
            );
            return $request_details;
            
    } catch (LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
 }   
 public function getChildrenForParent($user_id){
    	try {
			$query = $GLOBALS['$dbFramework']->query("
				SELECT user_id, reporting_to FROM user_details");
			$full_structure = $query->result();
			$allParentNodes = [];
			if (version_compare(phpversion(), '7.0.0', '<')) {
			  // php version isn't high enough to support array_column
				foreach($full_structure as $row)  {
					$allParentNodes[$row->user_id] = $row->reporting_to;
				}
			} else {
			  $allParentNodes = array_column(
					  $full_structure, 
					  'reporting_to',
					  'user_id');
			}
			$childNodes = array();
			$this->fetchChildNodes($user_id, $childNodes, $allParentNodes);
			if (count($childNodes) == 0) {
				return '';
			}
			$ids = implode("','", $childNodes);
			return $ids;
		} catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }
    private function fetchChildNodes($givenID, & $childNodes, $allParentNodes)  {
		foreach ($allParentNodes as $user_id => $reporting_to) {
			if ($reporting_to == $givenID)  {
				array_push($childNodes, $user_id);
				$this->fetchChildNodes($user_id, $childNodes, $allParentNodes);                
			}
		}
	}
public function can_see($user,$request_id){
    try{
        // Can See only by ownership_reassigned,ownership_assigned,stage progressed and accepted childrens of request.
        $children = $user."','";
        $children .= $this->getChildrenForParent($user);
        $query=$GLOBALS['$dbFramework']->query("SELECT count(a.request_name) from support_opportunity_details a,support_user_map b  WHERE a.request_id = '$request_id'
            and ((a.owner_id IN ('$children') OR a.manager_owner_id IN ('$children')))
            OR (a.request_id = b.request_id AND b.state = 1 AND b.to_user_id IN ('$children')
            AND b.action IN ('ownership_assigned','ownership_reassigned'))
            OR (a.request_id = b.request_id AND b.to_user_id IN ('$children')
            AND b.action IN ('stage progressed')) 
            group by a.request_id");

        return $result=$query->result();
    }catch(LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');

    }
}
public function log_attr($data){
    try {
        $query= $GLOBALS['$dbFramework']->insert_batch('support_custom_log', $data);
        return $query;
    }catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
}
public function fetch_oppoAttr($request_id)	{
    try {
        $query = $GLOBALS['$dbFramework']->query("select * from support_custom_log where request_id='$request_id'");
        return $query->result();
    } catch (LConnectApplicationException $e) {
    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
    throw $e;
    }		
}
public function get_custom_names()	{
    try {
        $query = $GLOBALS['$dbFramework']->query("select lookup_value,lookup_id from lookup where lookup_name='support_custom'");
        return $query->result();
    }catch (LConnectApplicationException $e) {
    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
    throw $e;
    }		
}
public function insert_log($data){
    try {
        $query= $GLOBALS['$dbFramework']->insert_batch('support_custom_log', $data);
        return $query;
    }catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
}
public function fetch_log_attributes($request_id,$stage_id)	{
    try {
        $query = $GLOBALS['$dbFramework']->query("select * from support_stage_attributes where request_id='$request_id' and support_stage_id='$stage_id'");
        return $query->result();
    }catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }		
}
public function insert_custom_attribute($data){
    try {
        $query= $GLOBALS['$dbFramework']->insert_batch('support_stage_attributes', $data);
        return $query;
    }catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }
}
public function update_attribute($changed_matrix,$remarks,$request_id,$stage_id){  
    try{    
        foreach($changed_matrix as $val){
            $query= $GLOBALS['$dbFramework']->query("update support_stage_attributes set support_attribute_value='".$val['attribute_value']."', support_attribute_remarks='".$remarks."' ,timestamp='".date('Y-m-d H:i:s')."' where request_id='".$request_id."' and support_stage_id='".$stage_id."' and support_attribute_name='".$val['attribute_name']."'");        
        }
         return $query;
    }catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }	
}
public function check_files_for_stage($stage_id, $request_id){
    try {
        $query = $GLOBALS['$dbFramework']->query("select * from support_sales_stage_attributes where stage_id='$stage_id' and attribute_name = 'document_upload'");
        $result=$query->num_rows();
        if($result > 0){
            $query1 = $GLOBALS['$dbFramework']->query("select * from opportunity_document_mapping where opportunity_id='$request_id' and stage_id='$stage_id'");
            $result=$query1->num_rows();
            if($result>0){
                return 1;
            }else{
                return 0;
            }
        }else{
            return 1;
        }
    } catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
    }		
}
    public function get_attributes($request_id,$stage_id){  
        try {
                $query = $GLOBALS['$dbFramework']->query("SELECT * FROM support_stage_attributes where support_stage_id='$stage_id' and request_id='$request_id'");
                return $query->result();
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }	
}
public function oppo_file_upload($given_data){
		try {
                   if(count($given_data['files'])>0){ 
                       $user_id = $this->session->userdata('uid');
			$_FILES  = $given_data['files'];
                        $request_id  = $given_data['request_id'];
                        $stage_id  = $given_data['stage_id'];
			
			$dirpath = './uploads/support_docs/';
			if(!is_dir($dirpath))    {
				mkdir($dirpath);
			}
			$dirpath = './uploads/support_docs/'.$request_id.'/';
			if(!is_dir($dirpath))    {
				mkdir($dirpath);
			}
			$dirpath = './uploads/support_docs/'.$request_id.'/'.$stage_id.'/';
			if(!is_dir($dirpath))    {
				mkdir($dirpath);
			}
			$upload['upload_path'] 	= $dirpath;
			$upload['allowed_types']= 'gif|jpg|jpeg|png|bmp|doc|docx|pdf|rtf|txt|xls|xlsx|csv|mp3|wav|aac|mp4|wma|wmv|mpg|jbg|fax|pptx|epub|xlsm|xltx';
			$upload['overwrite'] 	= true;
			$upload['max_size'] 	= 100000;

			$files = $_FILES;
			$count = count($_FILES['userfile']['name']);
			$finalPath = $dirpath;
			$errors = array();
			$docsData = array();
			$this->load->library('upload');
			for($i = 0; $i < $count; $i++) {
				$_FILES['userfile']['name'] 	= $files['userfile']['name'][$i];
				$_FILES['userfile']['type'] 	= $files['userfile']['type'][$i];
				$_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
				$_FILES['userfile']['error'] 	= $files['userfile']['error'][$i];
				$_FILES['userfile']['size'] 	= $files['userfile']['size'][$i];
				if ($_FILES['userfile']['error'] == 4) {
					continue;
				}
				$this->upload->initialize($upload);
				if (!$this->upload->do_upload())    {
					$error = array('error' => $this->upload->display_errors(),
									'name' => $_FILES['userfile']['name']);
					array_push($errors, $error);
				} else {
					$data = array('upload_data' => $this->upload->data());
					$file_name=$data['upload_data']['file_name'];
					$data1 = array(
						'opportunity_document_mapping_id' => $given_data['mapping_id'],
						'opportunity_id'=> $request_id,
						'document_path' => $finalPath.$file_name,
						'user_id' 		=> $user_id,
						'lead_id' 		=> $given_data['lead_id'],
						'stage_id' 		=> $stage_id,
						'created_date' 	=> date('Y-m-d H:i:s'),
						'remarks' 		=> $given_data['stage_id']
					);
					array_push($docsData, $data1);
				}
			}
			if(count($errors)>0){
                           return $errors;
                        }else{
                            $query= $GLOBALS['$dbFramework']->insert_batch('opportunity_document_mapping', $docsData);
                                return 1;
                        }
                   }else{
                        return 1;
                   }
		} catch (LConnectApplicationException $e) {
			echo $this->exceptionThrower($e);
		}
	}
    public function next_stage_assign($data6,$data2,$data4,$request_id){  
        try {   
            $query1= $GLOBALS['$dbFramework']->insert('support_user_map',$data6);
            $query2= $GLOBALS['$dbFramework']->insert_batch('support_user_map', $data4);
            $query3= $GLOBALS['$dbFramework']->update('support_opportunity_details', $data2,array('request_id' => ($request_id)));
            
                if( $query1==true && $query2==true && $query3==true){
                    return 1;
                }
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }	
    }
    public function fetch_owner_stage($stage_id, $request_id){
        try {
            $query = $GLOBALS['$dbFramework']->query("select from_user_id as old_stage_owner from support_user_map where request_id='$request_id' and stage_id='$stage_id' and action='stage_rejected'");
             return $result = $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	}
    public function get_qualifier($stage_id){
        try{
            $query_qualifier = $GLOBALS['$dbFramework']->query("SELECT attribute_value FROM support_sales_stage_attributes WHERE stage_id='$stage_id' and attribute_name='qualifier'");
                $arr_list=$query_qualifier->result();
                if(count($arr_list)>0){
                    $qualifier_id=$arr_list[0]->attribute_value;
                    $query12 = $GLOBALS['$dbFramework']->query("SELECT * FROM lead_qualifier WHERE lead_qualifier_id='$qualifier_id' 
                                and lead_qualifier_type='support' ORDER BY id;");
                    $arr=$query12->result_array();
                    $a=array();
                    for($i=0;$i<count($arr);$i++){
                        $lead_qualifier_id=$arr[$i]['lead_qualifier_id'];
                        $a[$i]['lead_qualifier_name']=$arr[$i]['lead_qualifier_name'];
                        $a[$i]['lead_qualifier_id']=$arr[$i]['lead_qualifier_id'];

                        $query1 = $GLOBALS['$dbFramework']->query("SELECT * FROM qualifier_questions WHERE lead_qualifier_id='$lead_qualifier_id' 
                            AND que_delete_bit=1 ORDER BY row_order");
                        $arr1=$query1->result_array();

                        for($j=0;$j<count($arr1);$j++){
                            $question_id=$arr1[$j]['question_id'];
                            $a[$i]['question_data'][$j]['question_type']=$arr1[$j]['question_type'];
                            $a[$i]['question_data'][$j]['question_id']=$arr1[$j]['question_id'];
                            $a[$i]['question_data'][$j]['question_text']=$arr1[$j]['question_text'];
                            $a[$i]['question_data'][$j]['answer']=$arr1[$j]['answer'];
                            $a[$i]['question_data'][$j]['mandatory_bit']=$arr1[$j]['mandatory_bit'];

                            $query11= $GLOBALS['$dbFramework']->query("SELECT * FROM qualifier_answers WHERE question_id='$question_id' ORDER BY id");
                            $arr11=$query11->result_array(); 
                            for($j1=0;$j1<count($arr11);$j1++){
                                $a[$i]['question_data'][$j]['answer_data'][$j1]['answer_id']=$arr11[$j1]['answer_id'];
                                $a[$i]['question_data'][$j]['answer_data'][$j1]['answer_text']=$arr11[$j1]['answer_text'];
                            }
                        }
                    }
                    return $a;
            }else{
                return 0;
            }
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
     public function answer_verification($data,$type1_2) {
        try {
             $quali_id = '';
            $dt = date('YmdHis');
            $quali_id .= $dt;
            $quali_trans_id = uniqid($quali_id);  
            $status_data['leadid']=$data['opp_cust_id'];
            $status_data['stageid']=$data['stage_id'];
            $status_data['opportunity_id']=$data['request_id'];
            $status_data['rep_id']=$data['rep_id'];
            $status_data['qualifier_tran_id']=$quali_trans_id;
            $status_data['attempt_data']=$data['question_data'];
            
            $response=1; 
            $action='passed qualifier';
            foreach($type1_2 as $value){
                $questype=$value->questype;
                $quesid=$value->quesid;
                $ansid=$value->ansid;
                if($questype==1){
                    $query=$this->db->query("select * from qualifier_questions where lower(question_id)=lower('$quesid')");
                    if($query->num_rows()>0) {
                        foreach ($query->result() as $row) {
                            $right_ans= $row->answer;
                        }
                    }
                    $query1=$this->db->query("select * from qualifier_answers where lower(answer_id)=lower('$ansid')");
                    if($query1->num_rows()>0) {
                        foreach ($query1->result() as $row) {
                            $given_ans= $row->answer_text;
                        }
                    }
                    if($right_ans<>$given_ans) {
                        $response=0; 
                        $status='fail';
                        $action='failed qualifier';
                    } else {
                        $response=1;
                        $status='success';
                    }
                }
            }
            if($response==1){
                 $status_data = array_merge(array('remarks' => 'success'), $status_data);
            }else{
                 $status_data = array_merge(array('remarks' => 'fail'), $status_data);
            }
           
            $user =$this->session->userdata('uid');
            $data4=array(
                'mapping_id'=>uniqid(rand(),TRUE),
                'request_id'=>$data['request_id'],
                'from_user_id'=>$user,
                'opp_cust_id'=>$data['opp_cust_id'],
                'to_user_id'=>$user,
                'cycle_id'=>$data['cycle_id'],  
                'stage_id'=>$data['stage_id'],  
                'module'=>'sales',
                'action'=>$action,
                'process_type'=>$data['process_type'],
                'state'=>0,
            );
                $insert1 = $GLOBALS['$dbFramework']->insert('qualifier_tran_details',$status_data); 
                $insert2 = $GLOBALS['$dbFramework']->insert('support_user_map',$data4);
                if($response==1 && $insert1==true && $insert2==true){
                    return 1;
                }else{
                    return 0;
                }
           
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function stage_details($request_id) {
        try {
             $query = $GLOBALS['$dbFramework']->query("select a.support_stage_id,b.stage_sequence,b.stage_name,a.request_id,a.support_attribute_remarks,a.support_attribute_value,support_attribute_name from support_stage_attributes a,support_sales_stage b where a.support_stage_id=b.stage_id and a.request_id='$request_id'");
             return $result = $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function get_history($request_id) {
        try {
             $query = $GLOBALS['$dbFramework']->query("select a. user_name as from_user_name,d.timestamp,d.module,d.remarks, b.user_name as to_user_name, c.stage_name,d.action,d.mapping_id from user_details a, 
                    user_details b,support_sales_stage c,support_user_map d where a.user_id=d.from_user_id and b.user_id=d.to_user_id and c.stage_id=d.stage_id 
                    and d.request_id='$request_id' ORDER BY timestamp asc");
             return $result = $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
   
    public function request_documents($request_id){
        try{
	    $query = $GLOBALS['$dbFramework']->query("SELECT a.opportunity_document_mapping_id AS mapping_id,
                    a.created_date as timestamp,a.document_path AS path,a.created_date as created_date,
                    b.user_name AS doc_user_id,c.stage_id AS stage_id,c.stage_name AS stage_name
                    FROM opportunity_document_mapping a, user_details b, support_sales_stage c
                    WHERE a.opportunity_id = '$request_id' AND b.user_id=a.user_id AND c.stage_id=a.stage_id
                    ORDER BY a.id,c.stage_name");
            return $query->result();
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	
    }
    public function request_tasklist($request_id){
        try{
	    $query = $GLOBALS['$dbFramework']->query("
                SELECT lr.event_name as event_name,
				    ud.user_name as user,
				    cd.contact_name as contact_name,
				    lo.lookup_value as activity,
				    lr.meeting_start as start_time,
				    lr.meeting_end as end_time,
				    lr.remarks as remarks,
				    lr.timestamp as timestamp
				FROM
				    user_details ud, contact_details cd, lookup lo, lead_reminder lr
				WHERE
				    lr.lead_id = '$request_id' and
				    lr.status IN ('scheduled', 'pending') and
				    lr.rep_id=ud.user_id and
				    lr.leadempid=cd.contact_id and 
				    lr.conntype=lo.lookup_id
				GROUP BY lr.lead_reminder_id
				ORDER BY lr.meeting_start");
            return $query->result();
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	
    }
     public function request_loglist($request_id){
        try{
	    $query = $GLOBALS['$dbFramework']->query("SELECT rl.log_name as log_name,
				    ud.user_name as user,
				    cd.contact_name as contact_name,
				    lo.lookup_value as activity,
				    rl.starttime as start_time,
				    rl.endtime as end_time,
					rl.rating as rating,
				    rl.note as remarks,
				    rl.time as timestamp,
				    (case when rl.path=\"'no_path'\" then '' else rl.path end) as path
				FROM
				    user_details ud, contact_details cd, lookup lo, rep_log rl
				WHERE
				    rl.leadid = '$request_id' and 
				    rl.rep_id=ud.user_id and
				    rl.leademployeeid=cd.contact_id and 
				    rl.logtype=lo.lookup_id
				GROUP BY rl.id
				ORDER BY starttime");
            return $query->result();
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	
    }
     public function get_allocation_modules($allocation_list){
        try{
            $allocation = "'" . implode("','", $allocation_list) . "'";
	    $query = $GLOBALS['$dbFramework']->query("select b.module_id,a.user_id from user_details a,user_module_plugin_mapping b where a.user_id=b.user_id and  
            a.user_id in($allocation)");
            return $query->result();
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
	
    }
    
    
    
    
    
}