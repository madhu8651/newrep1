<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_calendarModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class manager_calendarModel extends CI_Model	{
	
    function __construct()	{
            parent::__construct();
    }
    
   private function getChildrenForParent($user_id) {
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
  }

  private function fetchChildNodes($givenID, & $childNodes, $allParentNodes)  {
        foreach ($allParentNodes as $user_id => $reporting_to) {
            if ($reporting_to == $givenID)  {
                array_push($childNodes, $user_id);
                $this->fetchChildNodes($user_id, $childNodes, $allParentNodes);                
            }
        }
    }

      public function fetch_mytask($user_id) {
          try {

                    $children = $user_id."','";
                    $children .= $this->getChildrenForParent($user_id);
                //Include Hierarchy query to show task of team members
                 $query = $GLOBALS['$dbFramework']->query("
                    SELECT coalesce((CASE 
                    WHEN a.type ='' THEN
                    (SELECT request_name 
                    FROM support_opportunity_details as li
                    WHERE a.lead_id=li.request_id)
                    WHEN a.type = 'opportunity' THEN 
                    (SELECT opportunity_name
                    FROM opportunity_details AS li
                    WHERE a.lead_id = li.opportunity_id)
                    WHEN a.type = 'lead' THEN 
                    (SELECT lead_name  
                    FROM lead_info AS li
                    WHERE a.lead_id = li.lead_id)
                    ELSE 
                    (SELECT customer_name
                    FROM customer_info AS ci
                    WHERE a.lead_id = ci.customer_id)
                    END)
                    ,'-') AS leadname,
                    (CASE WHEN a.type ='support' THEN 
                    (SELECT request_id 
                    FROM support_opportunity_details as li 
                    WHERE a.lead_id=li.opp_cust_id)
                    WHEN a.type = 'opportunity' THEN
                    (SELECT opportunity_id
                    FROM opportunity_details AS li
                    WHERE a.lead_id = li.opportunity_id)
                    WHEN a.type = 'lead' THEN 
                    (SELECT lead_id
                    FROM lead_info AS li
                    WHERE a.lead_id = li.lead_id)
                    ELSE 
                    (SELECT customer_id
                    FROM customer_info AS ci 
                    WHERE a.lead_id = ci.customer_id)
                    END) AS leadid,
                    a.lead_reminder_id AS id, a.event_name AS title, a.remarks AS remarks,
                    a.meeting_start AS start,a.meeting_end as end, a.addremtime, a.status AS status, a.duration as duration, 
                    a.conntype as activity_id,coalesce(b.lookup_value,'-')  as activity_name, 
                    a.leadempid AS employeeid, d.contact_name AS employeename,
                    d.contact_number AS phone, coalesce (ud.user_name,'-') as created_by, 
                    ud1.user_name AS activity_owner, a.type as type,
                    a.conntype as conntype,a.rep_id as person_id,a.type,a.leadempid,a.reminder_members, a.id as row_id,'reminder' as table_name,
                    '' AS mail_subject,'' as message_id,
                    '' AS mail_attachment_path,
                    '' AS mail_form,
                    '' AS mail_to,
                    '' as mail_attachment_filename,
                    '' AS mail_body,
                    '' as from_name,
                    '' as mail_date,
                    '' as mail_cc,
                    '' as mail_bcc
                     FROM lead_reminder AS a 
                     LEFT JOIN user_details as ud 
                     ON 
                     a.created_by = ud.user_id, 
                    lookup AS b, contact_details AS d, user_details as ud1
                    WHERE b.lookup_id=a.conntype  
                    AND a.leadempid=d.contact_id 
                    AND (a.rep_id = ud1.user_id)
                    AND (a.rep_id IN ('$children'))");

                  return $query->result();  
          }	
          catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
          }
     }
      public function compare_datetime($event_start,$event_end, $rep_id){
        try {

                $query = $GLOBALS['$dbFramework']->query("
                                                            SELECT 
                                                            lr.meeting_start, lr.meeting_end
                                                            FROM
                                                            lead_reminder AS lr
                                                            WHERE
                                                            (lr.meeting_start >= '$event_start'
                                                            AND lr.meeting_end <= '$event_end')
                                                            OR (lr.meeting_end >= '$event_start'
                                                            AND lr.meeting_start <= '$event_end')
                                                            AND lr.status IN ('pending' , 'scheduled')
                                                            AND lr.rep_id = '$rep_id'
                                                            GROUP BY lr.id
                                                        ");   
                return $query->num_rows();
        }
        catch (LConnectApplicationException $e) {
          $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
          throw $e;
        }
        
    }
    public function mytask_cal($id){
      $user_id=$this->session->userdata('uid');
      $children = $user_id."','";
      $children .= $this->getChildrenForParent($user_id);
   
      
      $query =$GLOBALS['$dbFramework']->query("
                SELECT (CASE WHEN a.type = 'opportunity' THEN (SELECT opportunity_name from opportunity_details as li where a.lead_id=li.opportunity_id)
                 when a.type='customer'  THEN          
                    (select ci.customer_name from customer_info as ci where a.lead_id=ci.customer_id)                    
                    when a.type='lead'  THEN          
                    (select ci.lead_name from lead_info as ci where a.lead_id=ci.lead_id)
                    END ) AS leadname,
                    ( CASE
                    WHEN a.type = 'opportunity' THEN (SELECT opportunity_name from opportunity_details as li where a.lead_id=li.opportunity_id)
                   WHEN a.type = 'customer' THEN (select ci.customer_id from customer_info as ci where a.lead_id=ci.customer_id)
                   when a.type='lead' then
                   (select ci.lead_id from lead_info as ci where a.lead_id=ci.lead_id)
                                       END ) AS leadid, a.addremtime, a.duration, a.rem_time, a.lead_reminder_id AS id, a.status, 
                a.event_name, a.meeting_start, a.remi_date, a.meeting_start, 
                a.remarks, a.leadempid,a.lead_id, a.remi_date, a.conntype,
                a.reminder_members, b.lookup_id,b.lookup_value, 
                 d.contact_id as employeeid, d.contact_number as phone, d.contact_name as employeename,a.type as type,a.rep_id as person_id, ud.user_id as created_by_id,ud.user_name as created_by,a.id as row_id,'reminder' as table_name
                from lead_reminder a, lookup b,contact_details d,user_details as ud
                WHERE  (a.managerid IN ('$children') OR a.rep_id='$user_id') 
                AND a.lead_reminder_id='$id' AND a.leadempid=d.contact_id and a.status in('pending','reshcedule','scheduled')                
                AND a.conntype=b.lookup_id
                AND a.rep_id = ud.user_id 
                GROUP BY a.lead_reminder_id 
                ORDER BY a.meeting_start ");               
                return $query->result(); 
    }

   /* public function mytask_cal_oppo($id){
      $user_id=$this->session->userdata('uid');
      $children = $user_id."','";
      $children .= $this->getChildrenForParent($user_id);
      
      $query =$GLOBALS['$dbFramework']->query("
                SELECT a.addremtime, a.duration, a.rem_time, a.lead_reminder_id, a.status, 
                a.event_name, a.meeting_start, a.remi_date, a.meeting_start, 
                a.remarks, a.leadempid,a.lead_id, a.remi_date, a.conntype,
                a.reminder_members, b.lookup_id,b.lookup_value, 
                a.lead_id as leadid, d.contact_id as employeeid, d.contact_number as employeephone1, d.contact_name as employeename,a.type as type,a.rep_id as person_id
                from lead_reminder a, lookup b,contact_details d 
                WHERE  (a.managerid IN ('$children') OR a.rep_id='$user_id') 
                and a.type='internal'
                AND a.lead_reminder_id='$id' AND a.leadempid=d.contact_id and a.status in('pending','reshcedule','scheduled')                
                AND a.conntype=b.lookup_id GROUP BY a.lead_reminder_id 
                ORDER BY a.meeting_start ");               
                return $query->result(); 
    }*/
    public function mytask_cal_oppo($id){
       $user_id=$this->session->userdata('uid');
        $children = $user_id."','";
        $children .= $this->getChildrenForParent($user_id);
                
                $query= $GLOBALS['$dbFramework']->query("SELECT a.event_name AS title,
                a.addremtime,a.duration,a.rem_time,a.lead_reminder_id AS id,a.status,
                a.event_name, a.meeting_start,a.remi_date, a.meeting_start,a.remarks,
                a.leadempid,a.lead_id,a.remi_date,a.conntype,a.rep_id AS user_id,
                a.reminder_members,b.lookup_id,b.lookup_value, d.user_id AS employeeid,
                d.phone_num AS phone,d.user_name AS employeename,d.user_name as leadname,
                e.user_name AS person_name,e.user_state,f.Department_id,f.Department_name,
                a.type as type, a.meeting_start as start,a.conntype as activity_id,
                b.lookup_value as activity_name,coalesce(ud.user_name,'-') as created_by,
                e.user_name as activity_owner, a.lead_id as leadid,a.id as row_id,'reminder' as table_name
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
                AND (a.rep_id = e.user_id or a.managerid = e.user_id)
                and a.type='internal'
                AND e.department = f.Department_id
                GROUP BY a.lead_reminder_id
                ORDER BY a.meeting_start ")  ;  
                return $query->result();              
             
    }
	
     public function fetch_mytask1($user_id,$rem_id){
        $children = $user_id."','";
        $children .= $this->getChildrenForParent($user_id);
                if($rem_id=='') {
              $query= $GLOBALS['$dbFramework']->query("SELECT a.lead_reminder_id AS id,a.event_name AS title,
                a.addremtime,a.duration,a.rem_time,a.lead_reminder_id,a.status,
                a.event_name, a.meeting_start,a.remi_date, a.meeting_start,a.remarks,
                a.leadempid,a.lead_id as leadid,a.remi_date,a.conntype,a.rep_id AS person_id,
                a.reminder_members,b.lookup_id,b.lookup_value, d.user_id AS employeeid,
                d.phone_num AS phone,d.user_name AS employeename,d.user_id AS employeeid,
                e.user_name AS activity_owner,e.user_state,f.Department_id,f.Department_name,
                a.type as type, a.meeting_start as start,a.conntype as activity_id,
                b.lookup_value as activity_name,coalesce(ud.user_name,'-') as created_by,
                a.status as reminderstatus,a.meeting_end as meeting_end,d.user_name as leadname,a.timestamp as closed_date,a.reminder_members,a.id as row_id,'reminder' as table_name,'' AS mail_subject,'' as message_id,
                '' AS mail_attachment_path,
                '' AS mail_form,
                '' AS mail_to,
                '' as mail_attachment_filename,
                '' AS mail_body,
                '' as from_name,
                '' as mail_date,
                '' as mail_cc,
                '' as mail_bcc
                from 
                lead_reminder a 
                LEFT JOIN user_details ud 
                ON 
                ud.user_id = a.created_by ,
                lookup b, lead_info c, user_details d,
                user_details e,  department f 
                where                
                a.leadempid = d.user_id
                AND a.conntype = b.lookup_id
                AND (a.rep_id = e.user_id)
                and a.type='internal'
                and (a.rep_id IN ('$children'))
                AND e.department = f.Department_id
                GROUP BY a.lead_reminder_id
                ORDER BY a.meeting_start")  ;  
                return $query->result();                  
            }    
    }

              public function fetch_completedReplog($user_id) {
                  try {
                    $children = $user_id."','";
                    $children .= $this->getChildrenForParent($user_id);
                 $query = $GLOBALS['$dbFramework']->query("
                  SELECT 
                                    (CASE
                                        WHEN
                                            a.type = 'opportunity'
                                        THEN
                                            (SELECT 
                                                    opportunity_name
                                                FROM
                                                    opportunity_details AS li
                                                WHERE
                                                    a.leadid = li.opportunity_id)
                                        WHEN
                                            a.type = 'lead'
                                        THEN
                                            (SELECT 
                                                    lead_name
                                                FROM
                                                    lead_info AS li
                                                WHERE
                                                    a.leadid = li.lead_id)
                                        ELSE (SELECT 
                                                customer_name
                                            FROM
                                                customer_info AS ci
                                            WHERE
                                                a.leadid = ci.customer_id)
                                    END) AS leadname,
                                    (CASE
                                        WHEN
                                            a.type = 'opportunity'
                                        THEN
                                            (SELECT 
                                                    opportunity_id
                                                FROM
                                                    opportunity_details AS li
                                                WHERE
                                                    a.leadid = li.opportunity_id)
                                        WHEN
                                            a.type = 'lead'
                                        THEN
                                            (SELECT 
                                                    lead_id
                                                FROM
                                                    lead_info AS li
                                                WHERE
                                                    a.leadid = li.lead_id)
                                        ELSE (SELECT 
                                                customer_id
                                            FROM
                                                customer_info AS ci
                                            WHERE
                                                a.leadid = ci.customer_id)
                                    END) AS leadid,
                                    a.reminderid AS id,
                                    a.log_name AS title,
                                    a.note AS remarks,
                                    a.starttime AS start,
                                    a.call_type AS status,
                                    '- ' AS duration,
                                    a.logtype AS activity_id,
                                    COALESCE(b.lookup_value, '-') AS activity_name,
                                    a.leademployeeid AS employeeid,
                                    d.contact_name AS employeename,
                                    d.contact_number AS phone,
                                    COALESCE(ud.user_name, '-') AS created_by,
                                    ud1.user_name AS activity_owner,
                                    a.type AS type,
                                    a.reminderid AS id,
                                    a.logtype AS conntype,
                                    a.rep_id AS person_id,
                                    a.type,
                                    a.leademployeeid,
                                    a.endtime AS end,
                                    a.id AS row_id,
                                    'log_table' AS table_name,
                                    COALESCE(sge.mail_subject, '') AS mail_subject,
                                    sge.message_id AS message_id,
                                    '' AS mail_body,
                                    GROUP_CONCAT(sgea.mail_attachment_path) AS mail_attachment_path,
                                    sge.mail_from AS mail_form,
                                    sge.mail_to AS mail_to,
                                    GROUP_CONCAT(sgea.mail_attachment_filename, '') AS mail_attachment_filename,
                                    sge.from_name AS from_name,
                                    sge.mail_date AS mail_date,
                                    sge.mail_cc AS mail_cc,
                                    sge.mail_bcc AS mail_bcc
                                FROM
                                    rep_log a
                                        LEFT JOIN
                                    user_details AS ud ON a.rep_id = ud.user_id
                                        LEFT JOIN
                                    contact_details AS d ON a.leademployeeid = d.contact_id
                                        LEFT JOIN
                                    support_group_emails AS sge ON a.message_id = sge.message_id
                                        LEFT JOIN
                                    support_group_email_attachments AS sgea ON a.message_id = sgea.message_id,
                                    lookup b,
                                    user_details AS ud1
                                WHERE
                                    (a.rep_id IN ('$user_id'))
                                        AND a.call_type IN ('complete')
                                        AND a.logtype = b.lookup_id
                                        AND a.rep_id = ud1.user_id
                                        AND a.reminderid IS NULL
                                GROUP BY a.id
                                ORDER BY a.time DESC
                                ");

                  return $query->result();  
          } 
          catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
          }
     }
}  
?>    
