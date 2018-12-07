<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_calendarModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();
class sales_calendarModel extends CI_Model{
    
    public function __construct(){
        parent::__construct();
    } 
    
    public function fetch_leadcontact()	{
        try{
            $query = $GLOBALS['$dbFramework']->query("SELECT leadid, leadname from lead_info");
            return $query;
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }    
    }
      
    public function fetch_ContactsForLead($leadid){
        try{
            $query = $GLOBALS['$dbFramework']->query("
            SELECT employeeid, employeename
            FROM `lead_details`
            WHERE leadid = '$leadid'");
            return $query;
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }    
    }
        
    public function fetch_activity()	{
        try{
            $query = $GLOBALS['$dbFramework']->query("SELECT * from lookup WHERE lookup_name='activity'");
            return $query->result();
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }    
    }
    public function fetch_activity_complete()	{
        try{
    		$query = $GLOBALS['$dbFramework']->query("SELECT * from lookup WHERE lookup_name='activity'");
    		return $query;
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }    
    } 
    
    /* public function fetch_mytask($id)   {
        try{
            $query = $GLOBALS['$dbFramework']->query("
            SELECT a.lead_reminder_id AS id, a.event_name AS title, a.remarks AS remarks, a.meeting_start AS start,
            a.status AS status, a.duration as duration,
            a.conntype as activity_id, b.lookup_value as activity_name, 
            a.leadempid AS employeeid, d.contact_name AS employeename, JSON_UNQUOTE(d.contact_number->'$.mobile[0]') AS phone, a.lead_id AS leadid, c.lead_name as leadname 
            FROM lead_reminder AS a, lookup AS b, lead_info AS c, contact_details AS d , customer_info ci
            WHERE a.rep_id='$id' and b.lookup_id=a.conntype AND (a.lead_id=c.lead_id or a.lead_id=ci.customer_id) AND a.leadempid=d.contact_id
            group by a.lead_reminder_id");       
            return $query->result();
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }    		
     }*/

     public function fetch_mytask($user_id) {
          try {

                 /* $children = $user_id."','";
                  $children .= $this->getChildrenForParent($user_id);*/
                //Include Hierarchy query to show task of team members
                 $query = $GLOBALS['$dbFramework']->query("call sales_calendar_fetch_mytask('$user_id')");
                  return $query->result();  
          } 
          catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
          }
     }
    
    public function fetch_mytask1($user_id,$rem_id){
       /* $children = $user_id."','";
        $children .= $this->getChildrenForParent($user_id);*/
                if($rem_id=='') {
                $query= $GLOBALS['$dbFramework']->query("
                SELECT d.user_name AS employeename,d.user_name as leadname,d.phone_num as phone,
                e.user_name AS person_name,e.user_state,
                e.user_name as activity_owner,a.lead_id as leadid, e.user_id as person_id,a.event_name AS title,a.addremtime,a.duration,a.rem_time,
                a.lead_reminder_id as id,a.status,a.event_name, a.meeting_start,
                a.remi_date, a.meeting_start,a.remarks,a.leadempid as employeeid,a.lead_id as leadid,e.user_id as person_id,a.lead_reminder_id as id, a.type,a.meeting_end as end, a.addremtime,a.meeting_start as start,a.conntype as activity_id,
                a.remi_date,a.conntype,a.rep_id AS user_id,a.reminder_members,b.lookup_id,b.lookup_value as activity_name,coalesce(ud.user_name,'-') as created_by,a.id as row_id,'reminder' as table_name,'' AS mail_subject,'' as message_id,
                '' AS mail_attachment_path,
                '' AS mail_form,
                '' AS mail_to,
                '' as mail_attachment_filename,
                '' AS mail_body,
                '' as from_name,
                '' as mail_date,
                '' as mail_cc,
                '' as mail_bcc

                from lead_reminder as a 
                LEFT JOIN user_details ud ON 
                ud.user_id = a.created_by,user_details as d,user_details as e,lookup as b,department as f
                where a.type='internal' 
                and a.leadempid=d.user_id
                and a.rep_id in ('$user_id')
                and a.rep_id = e.user_id
                and e.department=f.Department_id
                and a.conntype=b.lookup_id
                group by a.lead_reminder_id
                ");  
                return $query->result();                  
            }    
    }
    public function insert_reminder($data){
        try{
        	$query=$GLOBALS['$dbFramework']->insert('lead_reminder',$data);
         	return $query;
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }    
    }
    public function insert_taskcomplete($data) {
        try{
        	$query=$GLOBALS['$dbFramework']->insert('rep_log',$data);
         	return $query;
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }    
    }

    public function compare_datetime($event_start,$event_end, $rep_id){       
        try{
            $query = $GLOBALS['$dbFramework']->query("SELECT lr.meeting_start, lr.meeting_end
                                                        From lead_reminder as lr
                                                        WHERE 
                                                        ((lr.meeting_start>='$event_start' and lr.meeting_end<='$event_end') 
                                                        or (lr.meeting_start<='$event_start' and lr.meeting_end>='$event_end')
                                                        or (lr.meeting_start<='$event_start' and lr.meeting_end>='$event_end')) and lr.status in ('pending','scheduled')
                                                        and lr.rep_id = '$rep_id'");     
            return $query->num_rows();
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }            
    }

         public function fetch_completedReplog($user_id) {
          try {
                
                 $query = $GLOBALS['$dbFramework']->query("
                   SELECT (CASE
                    WHEN a.type = 'opportunity' THEN (SELECT opportunity_name from opportunity_details as li where a.leadid=li.opportunity_id)
                    WHEN a.type = 'lead' THEN (SELECT lead_name from lead_info as li where a.leadid=li.lead_id)
                    ELSE (select customer_name from customer_info as ci where a.leadid=ci.customer_id)
                    END ) AS leadname,
                    ( CASE
                    WHEN a.type = 'opportunity' THEN (SELECT opportunity_id from opportunity_details as li where a.leadid=li.opportunity_id)
                    WHEN a.type = 'lead' THEN (SELECT lead_id from lead_info as li where a.leadid=li.lead_id)
                    ELSE (select customer_id from customer_info as ci where a.leadid=ci.customer_id)
                    END ) AS leadid,
                    a.reminderid AS id, a.log_name AS title, a.note AS remarks,
                    a.starttime AS start, a.call_type AS status, '- ' as duration, 
                    a.logtype as activity_id,coalesce(b.lookup_value,'-')  as activity_name, 
                    a.leademployeeid AS employeeid, d.contact_name AS employeename,
                    d.contact_number AS phone, coalesce (ud.user_name,'-') as created_by, 
                    ud1.user_name AS activity_owner, a.type as type,a.reminderid AS lead_reminder_id,
                    a.logtype as conntype,a.rep_id as person_id,a.type,a.leademployeeid,a.endtime as end,a.id as row_id,'log_table' as table_name,
                    COALESCE(sge.mail_subject, '') AS mail_subject,sge.message_id AS message_id,
                    sge.mail_body AS mail_body,
                    group_concat(sgea.mail_attachment_path,'') AS mail_attachment_path,
                    sge.mail_from AS mail_form,
                    sge.mail_to_address AS mail_to,
                    group_concat(sgea.mail_attachment_filename,'')  as mail_attachment_filename,
                    sge.from_name as from_name,
                    sge.mail_date as mail_date,
                    sge.mail_cc as mail_cc,
                    sge.mail_bcc as mail_bcc
                     FROM rep_log AS a 
                     LEFT JOIN user_details as ud 
                     ON 
                     a.rep_id = ud.user_id
                     LEFT JOIN support_group_emails AS sge 
                     ON 
                     a.message_id = sge.message_id
                     LEFT JOIN
                    support_group_email_attachments AS sgea ON a.message_id = sgea.message_id, 
                    lookup AS b, contact_details AS d, user_details as ud1
                    WHERE b.lookup_id=a.logtype 
                    AND a.call_type IN ('complete')
                    AND a.reminderid IS NULL
                    AND a.leademployeeid=d.contact_id 
                    AND a.rep_id = ud1.user_id
                    AND a.rep_id IN ('$user_id')
                    ");

                  return $query->result();  
          } 
          catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
          }
     }

      public function mytask_cal_oppo($id){
        try{
              $query= $GLOBALS['$dbFramework']->query("SELECT a.event_name AS title,
              a.addremtime,a.duration,a.rem_time,a.lead_reminder_id,a.status,
              a.event_name, a.meeting_start,a.remi_date, a.meeting_start,a.remarks,
              a.leadempid as employeeid,a.lead_id,a.remi_date,a.conntype,a.rep_id AS user_id,
              a.reminder_members,b.lookup_id,b.lookup_value, d.user_id AS employeeid,
              d.phone_num AS phone,d.user_name AS employeename,
              e.user_name AS person_name,e.user_state,f.Department_id,f.Department_name,
              a.type as leadname, a.meeting_start as start,a.conntype as activity_id,
              b.lookup_value as activity_name,coalesce(ud.user_name,'-') as created_by,
              e.user_name as activity_owner, a.lead_id as leadid,a.reminder_members
              from 
              lead_reminder a 
              LEFT JOIN user_details ud 
              ON 
              ud.user_id = a.created_by ,
              lookup b, lead_info c, user_details d,
              user_details e,  department f 
              where                
              a.leadempid = d.user_id
              and a.lead_reminder_id='$id'
              AND a.status IN ('pending','scheduled')
              AND a.conntype = b.lookup_id
              AND a.rep_id = e.user_id
              and a.type='internal'
              AND e.department = f.Department_id
              GROUP BY a.lead_reminder_id
              ORDER BY a.meeting_start ")  ;  
              return $query->result();
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
          }
                    
      }  
    
    
}


?>
