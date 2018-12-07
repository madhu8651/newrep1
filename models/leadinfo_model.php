<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('leadinfo_model');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();
class leadinfo_model extends CI_Model{
   public function __construct(){
        parent::__construct();
   }
   public function lead_details($country,$industry,$bussines,$contactType){
       try{
          $userid=$this->session->userdata('uid');
        if($country=='country'){
            $query=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='country'");
            $country= $query->result();
        }
        if($industry=='industry'){
            $query=$GLOBALS['$dbFramework']->query("select a.map_type,a.map_id,a.user_id ,b.hvalue2 from user_mappings a,hierarchy b where a.map_id=b.hkey2 and a.map_type='clientele_industry' and a.user_id='$userid'");
            $industry= $query->result();
        }
        if($bussines=='bussines'){
            $query=$GLOBALS['$dbFramework']->query("select a.map_type,a.map_id,a.user_id ,b.hvalue2 from user_mappings a,hierarchy b where a.map_id=b.hkey2 and map_type='business_location'  and a.user_id='$userid'");
            $bussines= $query->result();
        }
        if($contactType=='contactType'){
            $query=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='Buyer Persona'");
            $contactType= $query->result();
        }
        return array(
            'country' =>$country,            
            'industry'=>$industry,
            'bussines'=>$bussines,
            'contacttype'=>$contactType
        );
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function country(){
        try{
            $query=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='country'");
            return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function state($cid){
        try{
            $query=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='$cid'");
            return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function product_show(){
        try{
            $userid=$this->session->userdata('uid');
            $query=$GLOBALS['$dbFramework']->query("select a.map_id as product_id, a.map_type,b.hvalue2 as product_name from user_mappings a, hierarchy b where a.map_type='product' and a.user_id='$userid' and a.map_id=b.hkey2 group by a.map_id");
            return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
  
    public function product($lid){
        try{
            $query=$GLOBALS['$dbFramework']->query("select * from lead_product_map where lead_id='$lid'");
            return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function industry(){
        try{
            $userid=$this->session->userdata('uid');
            $query=$GLOBALS['$dbFramework']->query("select a.map_type,a.map_id,a.user_id ,b.hvalue2 from user_mappings a,hierarchy b where a.map_id=b.hkey2 and a.map_type='clientele_industry' and a.user_id='$userid'");
            return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
   
    public function business_loc(){
        try{
            $userid=$this->session->userdata('uid');
            $query=$GLOBALS['$dbFramework']->query("select a.map_type,a.map_id,a.user_id ,b.hvalue2 from user_mappings a,hierarchy b where a.map_id=b.hkey2 and map_type='business_location'  and a.user_id='$userid'");
            return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function product_view($lid){
        try{
            $query=$GLOBALS['$dbFramework']->query("select a.product_id,b.hvalue2 from lead_product_map a, hierarchy b where a. product_id=b.hkey2 and lead_id='$lid'");
            return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }
    public function leadsource(){
        try{
            $query=$GLOBALS['$dbFramework']->query("select b.id,b.hierarchy_id,b.hkey2,b.hvalue2,b.hkey1,b.hvalue1 from hierarchy_class a,hierarchy b
            where a.Hierarchy_Class_Name='lead_source' and b.hkey2!='0' and b.Hierarchy_Class_ID=a.hierarchy_class_id
            and b.hkey2 not in (select lead_source_id from lead_source_attributes where end_date is not null
            and (timestampdiff(minute,DATE_ADD(DATE_ADD(now(),INTERVAL 10 HOUR),INTERVAL 30 MINUTE),end_date) <0 ) and lead_source_id=b.hkey2)order by b.hierarchy_id");
            return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function contact(){
        try{
            $query=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='Buyer Persona'");
            return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
       public function lead_opprtunity($oppid){
        try{
            $query=$GLOBALS['$dbFramework']->query("select a.opportunity_name,coalesce( DATE_FORMAT(opportunity_date, '%d-%m-%Y'),'-') AS opportunity_date, b.stage_name,c.user_name from opportunity_details a,sales_stage b, user_details c where  a.stage_owner_id=c.user_id and a.opportunity_stage=b.stage_id and a.lead_cust_id='$oppid'");
            return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }

   public function fetchSuperiorManager() {
       try{
            $query=$GLOBALS['$dbFramework']->query("
                                                    SELECT 
                                                    ud.user_id as superior_id,ud.user_name as superior_name
                                                    FROM
                                                    user_details AS ud,
                                                    user_details AS ud1
                                                    WHERE
                                                    ud.reporting_to IS NULL
                                                    AND ud.user_id = ud1.reporting_to
                                                ");
            return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }

    public function insertNotificationData($notificationDataArray) {
        try{
                $insert = $GLOBALS['$dbFramework']->insert_batch('notifications',$notificationDataArray);
                return true;
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    } 

    public function updateLeadInfoAssigned($updateLeadArray) {   
        try {
            $query= $GLOBALS['$dbFramework']->update_batch('lead_info',$updateLeadArray, 'lead_id');
            return true; 
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
       
    }    

    public function insertLeadCust($assignedDataArray) {
        try{
                $insert = $GLOBALS['$dbFramework']->insert_batch('lead_cust_user_map',$assignedDataArray);
                return true;
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }
    public function getLeadName($custid) {
        try{

        $query=$GLOBALS['$dbFramework']->query("SELECT lead_name FROM lead_info where lead_id in('$custid')");
        return $query->result();  
        }
        catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
        } 
    }

       
    public function fetchAssignedManager($leadCustId,$user) {
        try{

            $query = $GLOBALS['$dbFramework']->query("SELECT lcum.from_user_id as managerid,
                                                      ud.user_name as managername,ud.reporting_to as Admin 
                                                      from lead_cust_user_map as lcum,user_details as ud 
                                                      where lcum.to_user_id = '$user'
                                                      AND lcum.action IN ('assigned','reassigned') 
                                                      AND lcum.module = 'sales'
                                                      AND lcum.lead_cust_id = '$leadCustId'
                                                      AND lcum.from_user_id = ud.user_id
                                                      AND lcum.type = 'lead'");
            return $query->result();
        }
        catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function managerLeadData($leadid,$data) {
        try{

            $update = $GLOBALS['$dbFramework']->update('lead_info' ,$data, array('lead_id' => ($leadid)));
            return $update;
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }    

   public function accept_lead($leadid,$data){
        try{
            $userid=$this->session->userdata('uid');
            $query=$GLOBALS['$dbFramework']->query("select * from lead_reminder where module_id='sales' and lead_id='$leadid'");
            $count_row = $query->num_rows();
            $data1= array(
                'rep_id'=>$userid,
             );
            if($count_row >0){
              $update = $GLOBALS['$dbFramework']->update('lead_reminder' ,$data1, array('lead_id' => $leadid,'module_id' => 'sales'));
            }
            $update = $GLOBALS['$dbFramework']->update('lead_info' ,$data, array('lead_id' => ($leadid)));
            return $update;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }
     public function reject_lead($leadid,$data){
         try{
      $update = $GLOBALS['$dbFramework']->update('lead_info' ,$data, array('lead_id' => ($leadid)));
      return $update;
      } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }
public function new_lead(){
    try{
    $userid=$this->session->userdata('uid');
    $query=$GLOBALS['$dbFramework']->query("SELECT distinct a.lead_industry,a.lead_business_loc,a.lead_id,a.lead_location_coord, a.lead_rep_status,a.lead_website,a.lead_rep_owner,a.lead_name,a.lead_remarks,
        a.lead_zip,a.lead_address,a.lead_logo,a.lead_country,a.lead_source,a.lead_city, a.lead_state,
        b.contact_desg,b.contact_type,b.contact_name,b.contact_id,
        coalesce(GROUP_CONCAT(distinct h22.hvalue2),'') as product_names,
        JSON_UNQUOTE(a.lead_number->'$.phone[0]') as leadphone, JSON_UNQUOTE(a.lead_email->'$.email[0]') as leademail,
        JSON_UNQUOTE(b.contact_number->'$.phone[0]') as employeephone1, JSON_UNQUOTE(b.contact_number->'$.phone[1]') as employeephone2, 
        JSON_UNQUOTE(b.contact_email->'$.email[0]') as employeeemail, JSON_UNQUOTE(b.contact_email->'$.email[1]') as employeeemail2,
        (SELECT c.lookup_value FROM lookup c WHERE c.lookup_id = b.contact_type )as contact ,
        (SELECT c.lookup_value FROM lookup c WHERE a.lead_country = c.lookup_id ) as country,
        (SELECT c.lookup_value FROM lookup c WHERE a.lead_state = c.lookup_id ) as state,
        coalesce ((SELECT d.hvalue2  FROM hierarchy d WHERE a.lead_source = d.hkey2 ),'') as leadsurce,
	coalesce ((SELECT d.hvalue2 FROM hierarchy d WHERE a.lead_industry = d.hkey2 ),'') as industry,
        (SELECT count(*) FROM opportunity_details e WHERE a.lead_id = e.lead_cust_id ) as opportunity
        from lead_info a LEFT JOIN lead_product_map lpm on a.lead_id=lpm.lead_id
	LEFT join hierarchy h22 on lpm.product_id=h22.hkey2,contact_details b   
        where a.lead_id = b.lead_cust_id and a.lead_rep_status=1 and a.lead_closed_reason is null and a.lead_id in
        (SELECT lead_cust_id FROM lead_cust_user_map where to_user_id='$userid' and 
        action in ('assigned','reassigned') and type='lead'  and module='sales' and state=1 group by lead_cust_id)
        and a.lead_id not in  (SELECT lead_cust_id FROM lead_cust_user_map where from_user_id='$userid' and 
        action='rejected'  and type='lead' and module='sales' and state=1 group by lead_cust_id)
        group by a.lead_id");
        return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }
public function accepted_lead(){
    try{
    $userid=$this->session->userdata('uid');
    $query=$GLOBALS['$dbFramework']->query("SELECT distinct a.lead_industry,a.lead_business_loc, a.lead_id,a.lead_location_coord,a.lead_rep_status,a.lead_website,a.lead_name,a.lead_remarks,a.lead_zip,a.lead_address,
        a.lead_logo as lead_picture,a.lead_country,a.lead_source,a.lead_city, a.lead_state,b.contact_desg,
        b.contact_type,b.contact_name,b.contact_id,
         coalesce(GROUP_CONCAT(distinct h22.hvalue2),'') as product_names,
        JSON_UNQUOTE(a.lead_number->'$.phone[0]') as leadphone, JSON_UNQUOTE(a.lead_email->'$.email[0]') as leademail,
        JSON_UNQUOTE(b.contact_number->'$.phone[0]') as employeephone1, JSON_UNQUOTE(b.contact_number->'$.phone[1]') as employeephone2, 
        JSON_UNQUOTE(b.contact_email->'$.email[0]') as employeeemail, JSON_UNQUOTE(b.contact_email->'$.email[1]') as employeeemail2,
        (SELECT c.lookup_value FROM lookup c WHERE c.lookup_id = b.contact_type )as contact ,
        (SELECT c.lookup_value FROM lookup c WHERE a.lead_country = c.lookup_id ) as country,
        (SELECT c.lookup_value FROM lookup c WHERE a.lead_state = c.lookup_id ) as state,
        coalesce ((SELECT d.hvalue2  FROM hierarchy d WHERE a.lead_source = d.hkey2 ),'') as leadsurce,
	coalesce ((SELECT d.hvalue2 FROM hierarchy d WHERE a.lead_industry = d.hkey2 ),'') as industry,
        (SELECT d.hvalue2 FROM hierarchy d WHERE a.lead_business_loc = d.hkey2 ) as location,
        (SELECT count(*) FROM opportunity_details e WHERE a.lead_id = e.lead_cust_id ) as opportunity
        from lead_info a LEFT JOIN lead_product_map lpm on a.lead_id=lpm.lead_id
	LEFT join hierarchy h22 on lpm.product_id=h22.hkey2,contact_details b   
        where a.lead_id = b.lead_cust_id and lead_status=0 and lead_rep_status=2 and a.lead_closed_reason is null
        and a.lead_rep_owner='$userid' group by a.lead_id");
        return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }
     public function active_lead(){
         try{
   $userid=$this->session->userdata('uid');
   $query=$GLOBALS['$dbFramework']->query("SELECT distinct a.lead_industry,a.lead_business_loc,a.lead_id,a.lead_location_coord,a.lead_rep_status,a.lead_website,a.lead_name,a.lead_remarks,a.lead_zip,
	a.lead_address,a.lead_logo,a.lead_country,a.lead_source,a.lead_city, a.lead_state,
	b.contact_desg,b.contact_type,b.contact_name,b.contact_id,
        coalesce(GROUP_CONCAT(distinct h22.hvalue2),'') as product_names,
	JSON_UNQUOTE(a.lead_number->'$.phone[0]') as leadphone, JSON_UNQUOTE(a.lead_email->'$.email[0]') as leademail,
	JSON_UNQUOTE(b.contact_number->'$.phone[0]') as employeephone1, JSON_UNQUOTE(b.contact_number->'$.phone[1]') as employeephone2, 
	JSON_UNQUOTE(b.contact_email->'$.email[0]') as employeeemail, JSON_UNQUOTE(b.contact_email->'$.email[1]') as employeeemail2,
	(SELECT c.lookup_value FROM lookup c WHERE c.lookup_id = b.contact_type )as contact ,
	(SELECT c.lookup_value FROM lookup c WHERE a.lead_country = c.lookup_id ) as country,
	(SELECT c.lookup_value FROM lookup c WHERE a.lead_state = c.lookup_id ) as state,
	coalesce ((SELECT d.hvalue2  FROM hierarchy d WHERE a.lead_source = d.hkey2 ),'') as leadsurce,
	coalesce ((SELECT d.hvalue2 FROM hierarchy d WHERE a.lead_industry = d.hkey2 ),'') as industry,
	(SELECT d.hvalue2 FROM hierarchy d WHERE a.lead_business_loc = d.hkey2 ) as location,
	(SELECT count(*) FROM opportunity_details e WHERE a.lead_id = e.lead_cust_id ) as opportunity
	from lead_info a LEFT JOIN lead_product_map lpm on a.lead_id=lpm.lead_id
	LEFT join hierarchy h22 on lpm.product_id=h22.hkey2,contact_details b 
	where a.lead_id = b.lead_cust_id and a.lead_status=1 and lead_rep_status=2
	and a.lead_rep_owner='$userid' group by a.lead_id");
        return $query->result();
        } catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }
  public function closed_lead(){
      try{
    $userid=$this->session->userdata('uid');
    $query=$GLOBALS['$dbFramework']->query("SELECT distinct a.lead_id,a.lead_location_coord,a.lead_industry,a.lead_business_loc,a.lead_rep_status,a.lead_website,a.lead_name,a.lead_closed_reason,
        a.lead_remarks,a.lead_zip,a.lead_address,a.lead_logo,a.lead_country,a.lead_source,a.lead_city,
        a.lead_state,b.contact_desg,b.contact_type,b.contact_name,b.contact_id,
         coalesce(GROUP_CONCAT(distinct h22.hvalue2),'') as product_names,
        JSON_UNQUOTE(a.lead_number->'$.phone[0]') as leadphone, JSON_UNQUOTE(a.lead_email->'$.email[0]') as leademail,
        JSON_UNQUOTE(b.contact_number->'$.phone[0]') as employeephone1, JSON_UNQUOTE(b.contact_number->'$.phone[1]') as employeephone2, 
        JSON_UNQUOTE(b.contact_email->'$.email[0]') as employeeemail, JSON_UNQUOTE(b.contact_email->'$.email[1]') as employeeemail2,
        (SELECT c.lookup_value FROM lookup c WHERE c.lookup_id = b.contact_type )as contact ,
        (SELECT c.lookup_value FROM lookup c WHERE a.lead_country = c.lookup_id ) as country,
        (SELECT c.lookup_value FROM lookup c WHERE a.lead_state = c.lookup_id ) as state,
        coalesce ((SELECT d.hvalue2  FROM hierarchy d WHERE a.lead_source = d.hkey2 ),'') as leadsurce,
	coalesce ((SELECT d.hvalue2 FROM hierarchy d WHERE a.lead_industry = d.hkey2 ),'') as industry,
        (SELECT d.hvalue2 FROM hierarchy d WHERE a.lead_business_loc = d.hkey2 ) as location,
        (SELECT count(*) FROM opportunity_details e WHERE a.lead_id = e.lead_cust_id ) as opportunity
        from lead_info a LEFT JOIN lead_product_map lpm on a.lead_id=lpm.lead_id
	LEFT join hierarchy h22 on lpm.product_id=h22.hkey2,contact_details b   
        where a.lead_id = b.lead_cust_id and a.lead_status='2' 
        and a.lead_rep_owner='$userid'  group by a.lead_id");
        return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }
    public function duplicate_lead($leadname){
        try{
     $query=$GLOBALS['$dbFramework']->query("select *  from lead_info where UCASE(lead_name)='".strtoupper($leadname)."'");
     $count_row = $query->num_rows();
        if ($count_row > 0){
            return 0;
        }else{
            return 1;
        } } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }
    public function check_editlead($leadname,$leadid){
        try{
       $query=$GLOBALS['$dbFramework']->query("select * from lead_info where LCASE(lead_name)='".$leadname."' and  lead_id!='$leadid'");
       $count_row = $query->num_rows();
        if ($count_row > 0){
            return 0;
        }else{
            return 1;
        } 
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
   public function insert_lead($data) {
       try{
     $insert = $GLOBALS['$dbFramework']->insert('lead_info',$data); 
     return $insert;
     } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }
   public function insert_details($data) {
       try{
    $insert = $GLOBALS['$dbFramework']->insert('contact_details',$data); 
     return $insert;
     } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }
   public function insert_transaction($data) {
       try{
            $insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data); 
            return $insert;
     } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
     }
   }
    public function delete_prod($leadid) {
        try{
     $GLOBALS['$dbFramework']->delete('lead_product_map' , array('lead_id' => $leadid));
     return true;
     } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }
    public function insert_product($data) {
        try{
    $insert = $GLOBALS['$dbFramework']->insert('lead_product_map',$data); 
     return $insert;
     } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }
   public function lead_deatils($lid){
       try{
      $userid=$this->session->userdata('uid');
      $query=$GLOBALS['$dbFramework']->query("SELECT lead_name as leadname, DATE_FORMAT(lead_created_time,'%D %b %Y %h:%i %p') as 'Start_time' from lead_info where lead_id='$lid'");
      return $query->result();
      } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }
   
   public function lead_log($lid){
       try{
      $userid=$this->session->userdata('uid');
      $query=$GLOBALS['$dbFramework']->query("
                                                SELECT 
                                                c.lead_name,
                                                a.logtype,
                                                a.rating,
                                                a.call_type,
                                                a.note,
                                                a.starttime,
                                                a.endtime,
                                                DATE_FORMAT(time, '%D %b %Y %h:%i %p') AS 'Start_time',
                                                TIMEDIFF(endtime, starttime) AS duration,
                                                b.lookup_value,
                                                d.user_name,
                                                a.path as path,
                                                a.logtype as conntype
                                                FROM
                                                rep_log a,
                                                lookup b,
                                                lead_info c,
                                                user_details d
                                                WHERE
                                                a.logtype = b.lookup_id
                                                AND a.leadid = c.lead_id
                                                AND a.leadid = '$lid'
                                                AND a.rep_id = d.user_id
                                            ");
      return $query->result();
      } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
   }

   public function update_info($leadid,$data) {
       try{
	   $update = $GLOBALS['$dbFramework']->update('lead_info' ,$data, array('lead_id' => ($leadid)));
             return $update;
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function update_details($leadid,$data) {
        try{
	  $update = $GLOBALS['$dbFramework']->update('contact_details' ,$data, array('contact_id' => ($leadid)));
      return $update;
      } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
   
     public function update_close($leadid,$data) {
        try{
            $update = $GLOBALS['$dbFramework']->update('lead_info' ,$data, array('lead_id' => ($leadid)));
            return $update;
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
   
     public function activate($leadid,$data) {
         try{
      $update = $GLOBALS['$dbFramework']->update('lead_info' ,$data, array('leadid' => ($leadid)));
      return $update;
      } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    
    public function update_leadPhoto($leadid,$data) {
        try{
	  $update = $GLOBALS['$dbFramework']->update('lead_info' ,$data, array('lead_id' => ($leadid)));
      return $update;
      } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function insert_cust($leadid) {
        try{
      $query=$GLOBALS['$dbFramework']->query("INSERT INTO customer_info(customer_id,customer_name,customer_logo,customer_number,customer_email,customer_website,customer_location_coord,customer_address,customer_city,customer_state,
       customer_country,customer_zip,customer_remarks,customer_edit_status,customer_created_by,customer_source,customer_rep_owner,customer_manager_status,customer_rep_status,customer_created_time,lead_id)
       SELECT customer_id,lead_name,lead_logo,lead_number,lead_email,lead_website,lead_location_coord,lead_address,lead_city,lead_state,
       lead_country,lead_zip,lead_remarks,lead_edit_status,lead_created_by,lead_source,lead_rep_owner,lead_manager_status,lead_rep_status,lead_created_time,lead_id
       FROM lead_info where lead_id='$leadid'");
       return TRUE;
       } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function permanent_close($leadid,$data) {
        try{
      $update = $GLOBALS['$dbFramework']->update('lead_info' ,$data, array('lead_id' => ($leadid)));
      return $update;
      } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

     public function remarks($leadid,$data){
         try{
      $update = $GLOBALS['$dbFramework']->update('contact_details' ,$data, array('lead_cust_id' => ($leadid)));
      return $update;
      } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
     public function temporary_close($leadid,$data) {
      try{
	  $update = $GLOBALS['$dbFramework']->update('lead_info' ,$data, array('lead_id' => ($leadid)));
      return $update;
      } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
     public function check_lead($leadid) {
         try{
       $query=$GLOBALS['$dbFramework']->query("select lead_rep_owner from lead_info where lead_id='$leadid'");
       $ss= $query->result();
       foreach ($ss as $value) {
           $count=$value->lead_rep_owner;
       }
      return $count;
      } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
      
    }
    public function check_fromid() {
        try{
      $userid=$this->session->userdata('uid');
      $query=$GLOBALS['$dbFramework']->query("select reporting_to from user_details where user_id='$userid'");
      $ss= $query->result();
      return $ss;
      } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
      
      }
     public function fetch_reporting($rep_id)  {
        try {
                $query =$GLOBALS['$dbFramework']->query("
                SELECT a.reporting_to as report,a.user_name as name
                FROM user_details a
                WHERE a.user_id='$rep_id'
                group by a.user_id");

                return $query->result(); 
        } 
         catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        } 
    }
      
      public function mananger_owner($leadid) {
        try{
         $query=$GLOBALS['$dbFramework']->query("select lead_manager_owner from lead_info where lead_id='$leadid'");
          return $query->result();
          } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
      }
      public function rep_owner($leadid) {
          try{
      $query=$GLOBALS['$dbFramework']->query("select lead_rep_status,lead_name from lead_info where lead_id='$leadid'");
       return $query->result();
       } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }
      public function notifications($data) {
          try{
      $insert = $GLOBALS['$dbFramework']->insert('notifications',$data); 
     return $insert;
     } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     }
     public function closed_lostlead(){
         try{
       $userid=$this->session->userdata('uid');
      $query=$GLOBALS['$dbFramework']->query("select distinct a.lead_id,a.lead_location_coord,a.lead_industry,a.lead_business_loc,a.lead_rep_status,a.lead_website,a.lead_name,a.lead_closed_reason as reason,
        a.lead_remarks,a.lead_zip,a.lead_address,a.lead_logo,a.lead_country,a.lead_source,a.lead_city,
        a.lead_state,b.contact_desg,b.contact_type,b.contact_name,b.contact_id,
        coalesce(GROUP_CONCAT(distinct h22.hvalue2),'') as product_names,
        JSON_UNQUOTE(a.lead_number->'$.phone[0]') as leadphone, JSON_UNQUOTE(a.lead_email->'$.email[0]') as leademail,
        JSON_UNQUOTE(b.contact_number->'$.phone[0]') as employeephone1, JSON_UNQUOTE(b.contact_number->'$.phone[1]') as employeephone2, 
        JSON_UNQUOTE(b.contact_email->'$.email[0]') as employeeemail, JSON_UNQUOTE(b.contact_email->'$.email[1]') as employeeemail2,
        (SELECT c.lookup_value FROM lookup c WHERE c.lookup_id = b.contact_type )as contact ,
        (SELECT c.lookup_value FROM lookup c WHERE a.lead_country = c.lookup_id ) as country,
        (SELECT c.lookup_value FROM lookup c WHERE a.lead_state = c.lookup_id ) as state,
        coalesce ((SELECT d.hvalue2  FROM hierarchy d WHERE a.lead_source = d.hkey2 ),'') as leadsurce,
	coalesce ((SELECT d.hvalue2 FROM hierarchy d WHERE a.lead_industry = d.hkey2 ),'') as industry,
        (SELECT d.hvalue2 FROM hierarchy d WHERE a.lead_business_loc = d.hkey2 ) as location,
        (SELECT count(*) FROM opportunity_details e WHERE a.lead_id = e.lead_cust_id ) as opportunity
        from lead_info a LEFT JOIN lead_product_map lpm on a.lead_id=lpm.lead_id
	LEFT join hierarchy h22 on lpm.product_id=h22.hkey2,contact_details b   
        where a.lead_id = b.lead_cust_id and (a.lead_status=3 or lead_status=4)
        and a.lead_rep_owner='$userid' group by a.lead_id");
        return $query->result();
        } catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
      }
      
      public function schedulelogs($leadid){
        try{
            $query=$GLOBALS['$dbFramework']->query("SELECT a.status, DATE_FORMAT(meeting_start,'%D %b %Y %h:%i %p') as 'Start_time', DATE_FORMAT(meeting_end,'%D %b %Y %h:%i %p') as 'End_time', a.conntype, a.remarks, b.lead_name as leadname, c.user_name, d.lookup_value as activity 
            from lead_reminder a , lead_info b, user_details c, lookup d where a.lead_id='$leadid' and b.lead_id=a.lead_id 
            and a.rep_id=c.user_id and a.conntype=d.lookup_id and a.status in ('pending','scheduled')");
             return $query->result();
        } catch (LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
        }
      }
      
 public function productdata_edit($leadid){
    $json=array();
    $b=array();
    $c=array();
    $query=$GLOBALS['$dbFramework']->query("select b.id,b.hierarchy_id,b.hkey2,b.hvalue2,b.hkey1,b.hvalue1
       from hierarchy_class a,hierarchy b where a.Hierarchy_Class_Name='lead_source'
       and b.hkey2!='0' and b.Hierarchy_Class_ID=a.hierarchy_class_id  order by b.hierarchy_id;");
            if($query->num_rows()>0){
                $rowdata=$query->result_array();
                for($row=0;$row<count($rowdata);$row++){
                    $lead_srcid=$rowdata[$row]['hkey2'];

                            $query=$GLOBALS['$dbFramework']->query("SELECT lead_source from lead_info where lead_source='$lead_srcid' and lead_id='$leadid'");
                            if($query->num_rows()>0){

                                $json[$row]['id'] = $rowdata[$row]['hkey2'];
                                $json[$row]['name'] = $rowdata[$row]['hvalue2'];
                                $a1=$rowdata[$row]['hkey1'];
                                if($a1=='0'){
                                    $json[$row]['parent'] = "";
                                }else{
                                    $json[$row]['parent'] = $rowdata[$row]['hkey1'];
                                }
                                   $json[$row]['checked'] = true;
                                   $json[$row]['nameAttr'] = 'Editsource';
                            }else{

                                $json[$row]['id'] = $rowdata[$row]['hkey2'];
                                $json[$row]['name'] = $rowdata[$row]['hvalue2'];
                                $a1=$rowdata[$row]['hkey1'];
                                if($a1=='0'){
                                    $json[$row]['parent'] = "";
                                }else{
                                    $json[$row]['parent'] = $rowdata[$row]['hkey1'];
                                }
                                   $json[$row]['checked'] = false;
                                   $json[$row]['nameAttr'] = 'Editsource';
                            }
                }
            }
            return $json;
    }

     public function update_transaction($leadid){
      try{
        $query=$GLOBALS['$dbFramework']->query("UPDATE lead_cust_user_map set state=0 where lead_cust_id='$leadid' and  action in ('assigned','reassigned') and module = 'sales'");
        return TRUE;
      }catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
    }
    public function notificationShowStatus($notifyUpdateData,$cmp_lead,$user_id) {
        try{

            $update = $GLOBALS['$dbFramework']->update('notifications',$notifyUpdateData,array('task_id'=>$cmp_lead,'action' =>'assigned'));
            return $update;
        }
        catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function last_reject($leadid,$remarks){
      try{
        $userid=$this->session->userdata('uid');
        $query=$GLOBALS['$dbFramework']->query("select * from lead_cust_user_map where state=1 and lead_cust_id='$leadid' and action in ('assigned','reassigned') and module='sales'");
        $count_reject = $query->num_rows();
        $result=$query->result();
        $data1= array(
            'mapping_id' =>uniqid(rand(),TRUE) ,
            'lead_cust_id' =>$leadid,
            'type'=>'lead',
            'state' =>1,
            'action'=>"rejected",
            'module'=>"sales",
            'from_user_id'=>$userid,
            'to_user_id'=>$result[0]->from_user_id,
            'remarks'=>$remarks,
            'timestamp'=>date('Y-m-d H:i:s'),
           );
           $insert = $GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data1); 
            if($insert==true ){
                $query2=$GLOBALS['$dbFramework']->query("select * from lead_cust_user_map where lead_cust_id='$leadid' and action='rejected' and state=1 and module='sales'");
                $count_reject1 = $query2->num_rows();
                if($count_reject1==$count_reject){
                    $query3=$GLOBALS['$dbFramework']->query("update lead_info set lead_rep_status=3 where lead_id='$leadid'");
                    $query4=$GLOBALS['$dbFramework']->query("update lead_cust_user_map set state=0 where lead_cust_id='$leadid' and state=1 and module='sales'"); 
                }
            return true; 
            }
           
      }catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
    }
    public function update_reject($leadid){
      try{
          $userid=$this->session->userdata('uid');
        $query=$GLOBALS['$dbFramework']->query("update lead_cust_user_map set state=0 where lead_cust_id='$leadid' and  action in ('assigned','reassigned') and to_user_id='$userid'");
        return TRUE;
      }catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
      }
    }
    public function lead_activities($leadid){
    try{
        $userid=$this->session->userdata('uid');
        $query=$GLOBALS['$dbFramework']->query("update lead_reminder set status='cancel' where 
                status in('pending','scheduled') and remi_date >=CURDATE() and lead_id='$leadid'");
       return $query;
    }catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
    }
   }
   public function opportunity_check($leadid){
        try{
          $query=$GLOBALS['$dbFramework']->query("select * from opportunity_details where lead_cust_id='$leadid' and closed_status!=100");
          $count_row = $query->num_rows();
            if ($count_row > 0){
                return 0;
            }else{
                return 1;
            } 
        } catch (LConnectApplicationException $e) {
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
        }
    }
    
    public function activity_list(){
        try{
          $query=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='activity'");
            return $query->result();
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function contact_list($lead_id){
        try{
          $query=$GLOBALS['$dbFramework']->query("SELECT * FROM contact_details WHERE lead_cust_id='$lead_id'");
            return $query->result();
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function insert_mytask($data) {
        try{
             $insert = $GLOBALS['$dbFramework']->insert('lead_reminder',$data); 
           return $insert;
        }catch (LConnectApplicationException $e) {
               $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
               throw $e;
        }
   }
   public function checkcustom() {
      try{
        $query=$GLOBALS['$dbFramework']->query("select * from admin_attributes where module='lead'");
            if($query->num_rows() > 0){
               return $query->result_array();
             }
             else{
               return 0;
             }
      }
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
     }
    }
    public function get_customfield($leadid){
        try{
            $query=$GLOBALS['$dbFramework']->query("SELECT a.attribute_key,a.attribute_name,a.attribute_type,JSON_UNQUOTE(b.attribute->'$[*].attribute_key')  as attributekey ,
                JSON_UNQUOTE(b.attribute->'$[*].attribute_value') as attributevalue,b.attribute,b.lead_id as id,a.module as module,
                a.attribute_validation_string from admin_attributes as a,lead_info as b where b.lead_id='$leadid' and module='Lead'");
                return  $sdd=$query->result_array();
                 
        }catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
        }
        
    }
	
	    public function fetchAllContacts($leadCustId) {
      try{

        $query=$GLOBALS['$dbFramework']->query("
                SELECT cd.contact_id AS contact_id, cd.contact_name AS contact_name, cd.contact_for AS contact_for,
					coalesce(lo.lookup_value,'-') AS contact_type_name, lo.lookup_id AS contact_type_id,
					li.lead_id AS lead_cust_id, li.lead_name AS lead_cust_name,
					JSON_UNQUOTE(coalesce(cd.contact_number->'$.phone[0]','')) as employeephone1,
					JSON_UNQUOTE(coalesce(cd.contact_number->'$.phone[1]','')) as employeephone2,
					JSON_UNQUOTE(coalesce(cd.contact_email->'$.email[0]','')) as employeeemail,
					JSON_UNQUOTE(coalesce(cd.contact_email->'$.email[1]','')) as employeeemail2,
					coalesce(cd.contact_desg,'-') as contact_desg,coalesce(cd.contact_photo,'') as contact_photo, cd.contact_created_time,
					coalesce(cd.contact_dob,'-') AS contact_dob, cd.contact_address AS contact_address, cd.remarks AS remarks
				FROM lead_info AS li, contact_details AS cd LEFT JOIN `lookup` AS lo ON cd.contact_type = lo.lookup_id AND lo.lookup_name = 'Buyer Persona'
				WHERE li.lead_id='$leadCustId' AND (li.lead_id=cd.lead_cust_id)
				GROUP BY cd.contact_id
				ORDER BY cd.contact_name");
        return $query->result();
      }
      catch(LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;

      }
    }
    

     public function insert_notification($data) {
      try{
        $insert = $GLOBALS['$dbFramework']->insert('notifications',$data); 
        return $insert;
      }
      catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
     }
    }

    public function getContact(){
        $query = $GLOBALS['$dbFramework']->query("select co.contact_number ,li.lead_name, li.lead_number from contact_details co 
        join lead_info li on li.lead_id=co.lead_cust_id");
        return $query->result();
    }

   /* public function getLeadnames(){
        $query = $GLOBALS['$dbFramework']->query("select lead_name from lead_info");
        return $query->result();

    }

    public function getLeadContacts(){
        $query = $GLOBALS['$dbFramework']->query("select lead_number, lead_name from lead_info");
        return $query->result();
    }
*/
    public function check_opportunity_owner($leadid,$userid)
    {
        // checking opportunity owner 
        $opportunity = $GLOBALS['$dbFramework']->query(
                                                "
                                                    SELECT 
                                                    *
                                                    FROM
                                                    opportunity_details
                                                    WHERE
                                                    lead_cust_id = '$leadid' 
                                                    AND
                                                    closed_status != 100
                                                "
                                                );
        // checking opportunity owner.
        $query = $GLOBALS['$dbFramework']->query("
                                                    SELECT 
                                                    *
                                                    FROM
                                                    opportunity_details
                                                    WHERE
                                                    closed_status != 100
                                                    AND lead_cust_id = '$leadid'
                                                    AND (stage_owner_id = '$userid'
                                                    OR owner_id = '$userid')
                                                ");


        $result ['value'] = $query->result();

        // Status 1 -> owner.
        // Status 2 -> opp exits.
        // status 3 -> no opportunity.

        if ($opportunity->num_rows() > 0) 
        {
            if ($query->num_rows() > 0) 
            {
                $result['result'] = 2;
            }
            else
            {
                $result['result'] = 1;
            }
        }
        else
        {
            $result['result'] = 0;
        }

        return $result;
 
    }

    public function insert_opp_log($data)
    {
        try{
                $insert = $GLOBALS['$dbFramework']->insert_batch('oppo_user_map',$data);
                return true;
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function update_opp_data($data)
    {
        try{
                $query= $GLOBALS['$dbFramework']->update_batch('opportunity_details',$data,'opportunity_id');
                return true; 
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function insert_reminder($data)
    {
        try{
                $insert = $GLOBALS['$dbFramework']->insert_batch('lead_reminder',$data);
                return true;
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function get_lead_data($leadid)
    {
        try{
            
            $query= $GLOBALS['$dbFramework']->query("
                                                    SELECT 
                                                    *
                                                    FROM
                                                    lead_info AS li,contact_details as ci
                                                    WHERE
                                                    li.lead_id = '$leadid'
                                                    AND li.lead_id = ci.lead_cust_id
                                                    ");

            $activity = $GLOBALS['$dbFramework']->query("
                                                    SELECT 
                                                    *
                                                    FROM
                                                    lookup
                                                    WHERE
                                                    lookup_name = 'activity'
                                                    AND lookup_value = 'Outgoing Call';
                                                    ");

            $result['lead_data'] = $query->result();
            $result['activity']  = $activity->result();

            return $result;
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }


    public function update_reopen_log($leadid,$data)
    {
        try
        {
            $update = $GLOBALS['$dbFramework']->update('lead_cust_user_map' ,$data, array('lead_cust_id' => ($leadid)));
            return $update;
        } 
        catch (LConnectApplicationException $e)
        {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function re_open_data($leadid,$data)
    {
        try
        {
            $update = $GLOBALS['$dbFramework']->update('lead_info' ,$data, array('lead_id' => ($leadid)));
            return $update;
        } 
        catch (LConnectApplicationException $e)
        {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    } 

    public function check_opportunity_tasks($leadid)
    {
        try{
            
            $query= $GLOBALS['$dbFramework']->query("
                                                    SELECT 
                                                    *
                                                    FROM
                                                    lead_reminder AS lr,
                                                    opportunity_details AS od
                                                    WHERE
                                                    od.lead_cust_id = '$leadid'
                                                    AND lr.status IN ('pending' , 'scheduled')
                                                    AND od.opportunity_id = lr.lead_id
                                                    GROUP BY lr.lead_id
                                                    ");

            return $query->result();

        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function lead_reopen($lead1,$lead2,$lead3){
        try{                    
                $query= $GLOBALS['$dbFramework']->update_batch('lead_info',$lead1, 'lead_id');          
                $query1= $GLOBALS['$dbFramework']->update_batch('lead_cust_user_map',$lead2, 'lead_cust_id');   
                $query2= $GLOBALS['$dbFramework']->insert_batch('lead_cust_user_map',$lead3);

                return $query2;     
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }       
    }
    
 }
?>
