<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_mytaskmodel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class manager_mytaskmodel extends CI_Model	{
	
    function __construct()	{
            parent::__construct();
    }

      private function getChildrenForParent($user_id) {
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
        }
         catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
      
    }

    public function fetch_internal1(){
        try{
            $query=$GLOBALS['$dbFramework']->query("SELECT ud.user_state,ud.user_name,ud.user_id,ul.sales_module,ul.manager_module from user_details as ud,user_licence as ul
            where ud.user_name!='admin' 
            and ud.user_state=1 
            and ud.user_id=ul.user_id");
            return $query->result();
        }catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }       
    }

     public function fetch_mytask($user_id, $rem_id){
        try { //
                $children = $user_id."','";
                $children .= $this->getChildrenForParent($user_id);        
                if($rem_id=='') {
                $query = $GLOBALS['$dbFramework']->query("
                 SELECT coalesce((CASE 
                 WHEN a.type ='support' THEN
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
                (CASE WHEN a.type ='support' THEN (SELECT request_id FROM support_opportunity_details as li 
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
                a.addremtime,a.duration,a.rem_time,a.lead_reminder_id as id,a.status,a.event_name,
                a.meeting_start,a.remi_date, a.meeting_end,a.remarks,a.leadempid,a.lead_id,
                a.remi_date,a.conntype,a.rep_id AS user_id,a.reminder_members,a.type,coalesce(ud.user_name,'-') as created_by,
                b.lookup_id,b.lookup_value,
                d.contact_id AS employeeid,d.contact_number AS employeephone1,d.contact_name AS employeename,
                e.user_name AS person_name,e.user_state,e.user_id AS person_id,
                f.Department_id,f.Department_name,a.lead_reminder_id,ul.sales_module,a.id as row_id,'reminder' as table_name,'' AS mail_subject,'' as message_id
                FROM
                lead_reminder a
                LEFT JOIN user_details ud 
                ON 
                ud.user_id = a.created_by,
                lookup b,
                lead_info c,
                contact_details d,
                user_details e,
                department f,user_licence ul
                WHERE
                (a.rep_id IN ('$children'))
                AND a.leadempid = d.contact_id
                AND a.status IN ('pending','scheduled')
                AND a.conntype = b.lookup_id
                AND (a.rep_id = e.user_id)
                AND e.department = f.Department_id
				and (ul.user_id=a.rep_id)
                GROUP BY a.lead_reminder_id
                ORDER BY a.meeting_start
                ");               
                } 
                else {
                $query =$GLOBALS['$dbFramework']->query("
                SELECT a.addremtime, a.duration, a.rem_time, a.lead_reminder_id, a.status, 
                a.event_name, a.meeting_start, a.remi_date, a.meeting_start, 
                a.remarks, a.leadempid,a.lead_id, a.remi_date, a.conntype,
                a.reminder_members, b.lookup_id,b.lookup_value, c.lead_name as leadname,
                c.lead_id as leadid, d.contact_id as employeeid, d.contact_number as employeephone1, d.contact_name as employeename,a.rep_id as person_id,ul.sales_module
                from lead_reminder a, lookup b,lead_info c,contact_details d ,user_licence ul
                WHERE c.lead_id=a.lead_id AND (a.managerid IN ('$children') OR a.rep_id='$user_id') 
                AND a.lead_reminder_id='$rem_id' AND a.leadempid=d.contact_id and a.status in('pending','reschedule','scheduled')
                AND a.conntype=b.lookup_id and ul.user_id=a.rep_id GROUP BY a.lead_reminder_id 
                ORDER BY a.meeting_start ");
                }
                return $query->result(); 
        } 
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }   
    }
	    
	public function fetch_completetask($user_id, $rem_id){
        try {
                $children = $user_id."','";
                $children .= $this->getChildrenForParent($user_id);
                
                if($rem_id=='') {
                $query = $GLOBALS['$dbFramework']->query("
                 SELECT coalesce((CASE 
                 WHEN a.type ='support' THEN
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
                (CASE WHEN a.type ='support' THEN (SELECT request_id FROM support_opportunity_details as li
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
                a.addremtime,a.duration,a.rem_time,a.lead_reminder_id,a.status,a.event_name,
                a.meeting_start,a.remi_date, a.meeting_end,a.remarks,a.leadempid,a.lead_id,
                a.remi_date,a.conntype,a.rep_id AS user_id,a.reminder_members,a.status as reminderstatus,a.timestamp as closed_date,coalesce(ud.user_name,'-') as created_by ,
                b.lookup_id,b.lookup_value,
                d.contact_id AS employeeid,d.contact_number AS employeephone1,d.contact_name AS employeename,
                e.user_name AS person_name,e.user_state,
                f.Department_id,f.Department_name,a.type as type,a.meeting_end as meeting_end,
                coalesce(a.cancel_remarks,'') as cancel_remarks, '' as path,a.id as row_id,'reminder' as table_name, '' AS mail_subject,'' as message_id,
                '' AS mail_attachment_path,
                '' AS mail_form,
                '' AS mail_to,
                '' as mail_attachment_filename,
                '' AS mail_body,
                '' as from_name,
                '' as mail_date,
                '' as mail_cc,
                '' as mail_bcc
                FROM
                lead_reminder a
                LEFT JOIN contact_details d
                ON
                a.leadempid = d.contact_id 
                LEFT JOIN user_details as ud
                ON 
                ud.user_id = a.created_by,
                lookup b,
                lead_info c,
                user_details e,
                department f
                WHERE
                (a.rep_id IN ('$children'))
                AND a.status IN ('reschedule','cancel')
                AND a.conntype = b.lookup_id
                AND a.type NOT IN ('internal')
                AND a.rep_id = e.user_id
                AND e.department = f.Department_id
                GROUP BY a.lead_reminder_id
                ORDER BY a.timestamp DESC
                ");
                } 
                else {
                $query =$GLOBALS['$dbFramework']->query("
                SELECT a.addremtime, a.duration, a.rem_time, a.lead_reminder_id, a.status, 
                a.event_name, a.meeting_start, a.remi_date, a.meeting_start, 
                a.remarks, a.leadempid,a.lead_id, a.remi_date, a.conntype,
                a.reminder_members, b.lookup_id,b.lookup_value, c.lead_name as leadname,
                c.lead_id as leadid, d.contact_id as employeeid, d.contact_number as employeephone1, d.contact_name as employeename 
                from lead_reminder a, lookup b,lead_info c,contact_details d 
                WHERE c.lead_id=a.lead_id AND (a.rep_id='$user_id') 
                AND a.lead_reminder_id='$rem_id' AND a.leadempid=d.contact_id and a.status in('pending','reschedule')
                AND a.conntype=b.lookup_id GROUP BY a.lead_reminder_id 
                ORDER BY a.meeting_start ");
                }
                return $query->result(); 
        } 
        catch (LConnectApplicationException $e) {
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
    
    public function fetch_team_members($user_id,$leadid,$type){
        //Get Only active user list 
        try {   
                $children = $user_id."','";
                $children .= $this->getChildrenForParent($user_id);
                if($type == 'internal'){
                $query=$GLOBALS['$dbFramework']->query("
                SELECT ud.user_id, (CASE ud.user_id WHEN '$user_id' THEN   group_concat(ud.user_name,'( Myself )') ELSE ud.user_name END) as user_name
                FROM user_details as ud
                WHERE 
                ud.reporting_to IN ('$children')
                AND ud.user_state = 1
                GROUP BY user_id");
                return $query->result(); 
                }
                else if($type == 'customer') {
                $query=$GLOBALS['$dbFramework']->query("
                SELECT ud.user_id, (CASE ud.user_id WHEN '$user_id' THEN   group_concat(ud.user_name,'( Myself )') ELSE ud.user_name END) as user_name
                FROM user_details as ud,customer_info as li
                WHERE ((ud.user_id=li.customer_manager_owner) OR (ud.user_id = li.customer_rep_owner))
                AND li.customer_id = '$leadid'
                AND ud.user_state = 1
                GROUP BY user_id");
                return $query->result(); 
                }
                else if($type == 'lead') {
                $query=$GLOBALS['$dbFramework']->query("
                SELECT ud.user_id, (CASE ud.user_id WHEN '$user_id' THEN   group_concat(ud.user_name,' (Myself)') ELSE ud.user_name END) as user_name
                FROM user_details as ud,lead_info as li
                WHERE ((ud.user_id=li.lead_manager_owner) OR (ud.user_id = li.lead_rep_owner))
                AND li.lead_id = '$leadid'
                and li.customer_id IS NULL
                AND ud.user_state = 1
                GROUP BY user_id");
                return $query->result(); 
                } 
				else if($type == 'opportunity') {
                    $query=$GLOBALS['$dbFramework']->query("
                    SELECT ud.user_id, (CASE ud.user_id WHEN '$user_id' THEN   group_concat(ud.user_name,'( Myself )') ELSE ud.user_name END) as user_name
                    FROM user_details as ud,opportunity_details as od
                    WHERE 
                    od.opportunity_id = '$leadid'
                    and (od.owner_id = ud.user_id
                        OR od.stage_owner_id = ud.user_id 
                        OR od.manager_owner_id = ud.user_id
                        OR od.stage_manager_owner_id = ud.user_id)
                    AND ud.user_state = 1
                    GROUP BY ud.user_id");
                    return $query->result();               
                }

               
        } 
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
       
    }
    
    public function fetch_activity(){
        try {
                $query = $GLOBALS['$dbFramework']->query("SELECT * from lookup WHERE lookup_name='activity'");
                return $query->result(); 
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
        
    }
    public function fetch_leads($user){
        try {
                $children = $user."','";
                $children.= $this->getChildrenForParent($user);
                $query = $GLOBALS['$dbFramework']->query(
                "SELECT li.lead_id as leadid, li.lead_name as leadname,'lead' as type
                FROM lead_info li
                WHERE 
                li.lead_manager_owner IN ('$children')
                and li.customer_id IS NULL
                GROUP BY li.lead_id
                ORDER BY li.lead_name"
                );
                return $query->result(); 
        } 
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     
    }

    public function fetch_ContactsForsupport($user,$supportid)  {
            try {
                    $children = $user."','";
                    $children.= $this->getChildrenForParent($user);
                    $query =$GLOBALS['$dbFramework']->query("
                    SELECT od.request_id, od.request_name,od.opp_cust_id, cd.contact_name as employeename,
                    cd.contact_number->'$.phone[0]' AS employeephone1, cd.contact_id as employeeid
                    FROM support_opportunity_details AS od left join contact_details cd on find_in_set(contact_id, replace(od.request_contact,':',',')),user_details as ud
                    WHERE (od.owner_id = ud.user_id
                    OR od.owner_id = ud.user_id 
                    OR od.manager_owner_id = ud.user_id
                    )
                    AND (ud.user_id IN ('$children') or ud.reporting_to IN ('$children')) 
                    AND od.request_id ='$supportid'
                    GROUP BY od.request_name");
                    return $query->result();
            }
            catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }
        //Include opportunity based on the sesion Id.
        //opportunity_id', 'opportunity_name', 'opportunity_prod', 'leadid
      
    } 

    public function fetch_Support($user_id,$leadid)  {
            try {
                    $children = $user_id."','";
                    $children.= $this->getChildrenForParent($user_id);
                    $query =$GLOBALS['$dbFramework']->query("
                    SELECT li.request_id as leadid, li.request_name as leadname
                    FROM support_opportunity_details li
                    WHERE 
                    (li.owner_id IN ('$children') OR li.manager_owner_id IN ('$children'))
                    and li.opp_cust_id = '$leadid'
                    GROUP BY li.request_id
                    ORDER BY li.request_name");
                    return $query->result();
            }
            catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }
        //Include opportunity based on the sesion Id.
        //opportunity_id', 'opportunity_name', 'opportunity_prod', 'leadid
    } 

    public function fetch_customer($user) {
        try {
             $children = $user."','";
             $children.= $this->getChildrenForParent($user);
            $query =$GLOBALS['$dbFramework']->query("
                (SELECT ci.customer_id as leadid, ci.customer_name as leadname,'customer' as type
                FROM  customer_info ci
                WHERE 
                ci.customer_manager_owner IN ('$children')
                GROUP BY ci.customer_id
                ORDER BY ci.customer_name)
                UNION
                (SELECT ci.customer_id as leadid,ci.customer_name as leadname,'customer' as type 
                FROM oppo_user_map as opum,lead_info as li,customer_info as ci
                where opum.lead_cust_id=li.lead_id
                and li.lead_id=ci.lead_id
                and opum.to_user_id IN ('$children')
                group by li.lead_id)");
                return $query->result(); 
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }
    
    public function fetch_contactNumber($contactid,$type) {
        //Query should changed according to tables and Include Customer also.
        try {  
                 if($type=='customer'){
                    $query =$GLOBALS['$dbFramework']->query("
                    SELECT ld.contact_number, li.customer_number as 'Lead Phone'
                    FROM `contact_details` AS ld, customer_info AS li
                    WHERE ld.contact_id = '$contactid' and
                    (ld.lead_cust_id=li.customer_id or ld.lead_cust_id=li.lead_id)");
                    return $query->result(); 
                }
                else{
                    $query =$GLOBALS['$dbFramework']->query("
                    SELECT ld.contact_number, li.lead_number as 'Lead Phone'
                    FROM `contact_details` AS ld, lead_info AS li
                    WHERE ld.contact_id = '$contactid' and
                    ld.lead_cust_id=li.lead_id");
                    return $query->result(); 
                }
                
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
       
    }

    public function fetch_contactNumberSupport($contactid,$type) {
       try {
                $query =$GLOBALS['$dbFramework']->query("
                            SELECT cd.contact_number, '' as 'Lead Phone'
                            FROM opportunity_details AS od left join contact_details cd on find_in_set(contact_id, replace(od.opportunity_contact,':',','))
                            WHERE cd.contact_id = '$contactid' 
                            group by cd.contact_id");
                    return $query->result();      

        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function fetch_userNumber($contactID) {
        //Query should changed according to tables and Include Customer also.
        try {
                $query =$GLOBALS['$dbFramework']->query("
                SELECT ld.phone_num as contact_number
                FROM `user_details` AS ld 
                WHERE ld.user_id = '$contactID'");
               /* $arr=$query->result_array();
                $OldArray=array();
                for($i=0;$i<count($arr);$i++){ 
                    array_push($OldArray,$arr[$i]['contact_number']);
                    $row[$i]['phone']= ($OldArray);  
                    $someArray = array('contact_number' =>(json_encode($row[$i])));
                }
                return array(($someArray));*/
                return $query->result();
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
       
    }
    
    public function fetch_ContactsForLead($leadid){
        //Query should changed according to tables and Include Customer also.
        try {
                $query =$GLOBALS['$dbFramework']->query("
                SELECT cd.contact_id as employeeid, cd.contact_name as employeename
                FROM contact_details cd
                WHERE cd.lead_cust_id = '$leadid'
                OR cd.lead_cust_id IN (
                SELECT lead_id
                FROM customer_info
                WHERE customer_id = '$leadid'
                )");
                return $query->result(); 
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
		
	}

    public function fetch_activity_complete(){
        try {
                $query =$GLOBALS['$dbFramework']->query("SELECT * from lookup WHERE lookup_name='activity'");
                return $query->result();
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
		
	}
        
      public function fetch_opportunities($user)  {
            try {
                    $children = $user."','";
                    $children.= $this->getChildrenForParent($user);
                    $query =$GLOBALS['$dbFramework']->query("
                    SELECT od.opportunity_id, od.opportunity_name,od.lead_cust_id, cd.contact_name,
                    cd.contact_number->'$.phone[0]' AS employeephone1, cd.contact_id
                    FROM opportunity_details AS od left join contact_details cd on find_in_set(contact_id, replace(od.opportunity_contact,':',',')),user_details as ud
                    WHERE (od.owner_id = ud.user_id
                    OR od.stage_owner_id = ud.user_id 
                    OR od.manager_owner_id = ud.user_id
                    OR od.stage_manager_owner_id = ud.user_id)
                    AND (ud.user_id IN ('$children') or ud.reporting_to IN ('$children')) 
                    GROUP BY od.opportunity_name");
                    return $query->result();
            }
            catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }
        //Include opportunity based on the sesion Id.
        //opportunity_id', 'opportunity_name', 'opportunity_prod', 'leadid
    } 

    public function fetch_contactsForOpportunity($opp_id)   {
        $query =$GLOBALS['$dbFramework']->query("
        SELECT od.opportunity_id, od.opportunity_name, cd.contact_name,
        cd.contact_number->'$.phone[0]' AS employeephone1, cd.contact_id
        FROM opportunity_details AS od 
            left join contact_details cd on find_in_set(contact_id, replace(od.opportunity_contact,':',','))
        WHERE (od.opportunity_id='$opp_id')");
        return $query->result();
    }
     
     public function fetch_emails($user_id){
        //Include Manager Id in the query.
        try {
                $query =$GLOBALS['$dbFramework']->query("SELECT a.user_id,
                a.user_name, a.emailId->'$.work[0]' as emailId, b.department_name, c.role_name as designation from user_details a, department b, user_roles c where a.designation=c.role_id and a.department=b.Department_id and a.user_id != '$user_id' ");
                return $query->result(); 
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
     
    }
    
    public function insert_reminder($batchArray) {
        try {
        
                $query=$GLOBALS['$dbFramework']->insert_batch('lead_reminder',$batchArray);
                return true;
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    	
    }

    public function insertNotificationData($notificationDataArray) {
        try{
                $insert = $GLOBALS['$dbFramework']->insert_batch('notifications',$notificationDataArray);
                return $insert;
        } 
        catch (LConnectApplicationException $e) {
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
    
     public function update_reminderschedule($data1,$lead_reminder_id){
        try {
                $update = $GLOBALS['$dbFramework']->update('lead_reminder', $data1,array('lead_reminder_id'=>$lead_reminder_id));          
                return $update;  
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    	
    }
    public function insert_repcomplete($data2){
        try {
                $query=$GLOBALS['$dbFramework']->insert('rep_log',$data2);
                return TRUE;    
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
         	
    }
     public function update_reminder($data3,$lead_reminder_id){
        try {
                $update = $GLOBALS['$dbFramework']->update('lead_reminder', $data3, array('lead_reminder_id'=>$lead_reminder_id));       
                return $update;
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
     
    }

    public function cancelTask($lead_reminder_id,$data1) {
         try {  
                $update = $GLOBALS['$dbFramework']->update('lead_reminder', $data1, array('lead_reminder_id'=>$lead_reminder_id));       
                return $update;
            }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function update_remindercomplete($data1,$lead_reminder_id,$user_id){
        try {
                $query=$GLOBALS['$dbFramework']->query("SELECT lead_reminder_id FROM lead_reminder WHERE lead_reminder_id='$lead_reminder_id' AND rep_id='$user_id' ");

                if($query->num_rows() > 0)
                {
                $update =$GLOBALS['$dbFramework']->update('lead_reminder', $data1,array('lead_reminder_id'=>$lead_reminder_id));       
                return $update;
                }
                else
                {

                return false;
                } 
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
        
         
    }
     public function insert_taskcomplete($data1){
        try {
                $query=$GLOBALS['$dbFramework']->insert('rep_log',$data1);
                return TRUE;
        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }       
    }
    
     public function insert_repinfo($user_id,$event_lead) {
        //Query Should be change based on the tables.
            try {
                    $query = $this->db->query("
                    SELECT from_user_id 
                    FROM lead_cust_user_map 
                    WHERE lead_cust_id='$event_lead' AND action='in progress'");
                    if(!$query->result())   {
                    $data1=array(
                    'lead_cust_id'=>$event_lead,
                    'from_user_id'=>$user_id,
                    'to_user_id'=>$user_id,
                    'action' => 'in progress',
                    'module'=>'manager',
                    'timestamp'=> date('Y-m-d H:i:s'),
                    'state'=>1,
                    'type'=>'lead'
                    );

                    $query=$GLOBALS['$dbFramework']->insert('lead_cust_user_map',$data1);

                    $getCustomer=$this->db->query("SELECT customer_id 
                                        FROM customer_info
                                        WHERE customer_id='$event_lead'");
                    if($getCustomer->num_rows() > 0){
                    $data3=array(
                    'customer_status'=>1
                    );
                    $update1 = $GLOBALS['$dbFramework']->update('customer_info', $data3,array('customer_id'=> $event_lead)); 
                    return $update1;
                    }
                    else {
                    $data2=array(
                    'lead_status'=>1       
                    );
                    $update = $GLOBALS['$dbFramework']->update('lead_info', $data2,array('lead_id'=> $event_lead));   
                    return $update;
                    }
            } 

        }
        catch (LConnectApplicationException $e) {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }   
    }
    
    public function fetch_mytask1($user_id,$rem_id){
        $children = $user_id."','";
        $children .= $this->getChildrenForParent($user_id);
                if($rem_id=='') 
                {
                $query= $GLOBALS['$dbFramework']->query("SELECT a.event_name AS title,
                a.addremtime,a.duration,a.rem_time,a.lead_reminder_id,a.status,
                a.event_name, a.meeting_start,a.remi_date, a.meeting_start,a.remarks,
                a.leadempid,a.lead_id,a.remi_date,a.conntype,a.rep_id AS user_id,
                a.reminder_members,b.lookup_id,b.lookup_value, d.user_id AS employeeid,d.user_name as leadname,
                d.phone_num AS employeephone1,d.user_name AS employeename,
                e.user_name AS person_name,e.user_state,f.Department_id,f.Department_name,
                a.type as type, a.meeting_start as start,a.conntype as activity_id,
                b.lookup_value as activity_name,coalesce(ud.user_name,'-') as created_by,
                e.user_name as activity_owner, a.lead_id as leadid, e.user_id as person_id,
                a.lead_reminder_id as id, a.type,ul.sales_module, '' as employeenumber,
                a.id as row_id,
                'reminder' as table_name,'' AS mail_subject,'' as message_id,
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
                user_details e,  department f,user_licence ul 
                where                
                a.leadempid = d.user_id
                AND a.status IN ('pending','scheduled')
                AND a.conntype = b.lookup_id
                AND (a.rep_id = e.user_id)
                and a.type='internal'
                and (a.rep_id IN ('$children'))
                AND e.department = f.Department_id
				and (ul.user_id=a.rep_id)
                GROUP BY a.lead_reminder_id
                ORDER BY a.meeting_start");  
                return $query->result();                  
            }    
    }

        public function fetch_mytaskCompleted1($user_id,$rem_id){
                $children = $user_id."','";
                $children .= $this->getChildrenForParent($user_id);
                if($rem_id=='') {
                $query= $GLOBALS['$dbFramework']->query("SELECT a.event_name AS title,
                a.addremtime,a.duration,a.rem_time,a.lead_reminder_id AS id,a.status,
                a.event_name, a.meeting_start,a.remi_date, a.meeting_start,a.remarks,
                a.leadempid,a.lead_id,a.remi_date,a.conntype,a.rep_id AS user_id,
                a.reminder_members,b.lookup_id,b.lookup_value,d.user_id AS employeeid,
                d.phone_num AS employeephone1,d.user_name AS employeename,
                e.user_name AS person_name,e.user_state,f.Department_id,f.Department_name,
                a.type as type, a.meeting_start as start,a.conntype as activity_id,
                b.lookup_value as activity_name,coalesce(ud.user_name,'-') as created_by,
                a.status as reminderstatus,a.meeting_end as meeting_end,d.user_name as leadname,a.timestamp as closed_date,coalesce(a.cancel_remarks,'') as cancel_remarks,'' as employeenumber,'' as path,a.id as row_id,'reminder' as table_name,'' AS mail_subject,'' as message_id,
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
                AND a.status IN ('reschedule','cancel')
                AND a.conntype = b.lookup_id
                AND a.rep_id = e.user_id
                and a.type='internal'
                and a.rep_id IN ('$children')
                AND e.department = f.Department_id
                GROUP BY a.lead_reminder_id
                ORDER BY a.timestamp desc ")  ;  
                return $query->result();                  
            }    
    }

    public function selectCurrentData() {
        try {
                $today = date('Y-m-d H:i:s', time()); 
                $qry = "
                SELECT lead_reminder_id
                FROM `lead_reminder`
                WHERE meeting_start < '$today'
                AND status = 'scheduled'";   
                $query =$GLOBALS['$dbFramework']->query($qry);
                $GLOBALS['$log']->debug('selectCurrentData : '.$qry);
                return $query->result(); 

        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }
    
    public function update_schedule_reminder($data) {
        try {                              
                $query= $GLOBALS['$dbFramework']->update_batch('lead_reminder',$data, 'lead_reminder_id');
            return true;
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

        public function fetch_mytaskCompletedReplog($user_id,$rem_id){
        try {  
                $children = $user_id."','";
                $children .= $this->getChildrenForParent($user_id);                    
                $query= $GLOBALS['$dbFramework']->query("
                                        SELECT 
                                        COALESCE((CASE
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
                                                END),
                                                ' ') AS leadname,
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
                                        '-' AS duration,
                                        '-' AS rem_time,
                                        a.log_name AS event_name,
                                        a.reminderid,
                                        a.call_type AS reminderstatus,
                                        a.starttime AS meeting_start,
                                        a.time AS remi_date,
                                        a.note AS remarks,
                                        a.leademployeeid AS leadempid,
                                        a.leadid,
                                        a.logtype AS conntype,
                                        a.rep_id AS user_id,
                                        a.type AS type,
                                        a.time AS closed_date,
                                        COALESCE(ud.user_name, '-') AS created_by,
                                        a.endtime AS meeting_end,
                                        a.path AS path,
                                        b.lookup_id,
                                        b.lookup_value,
                                        a.rating AS rating,
                                        d.contact_id AS employeeid,
                                        d.contact_number AS employeephone1,
                                        d.contact_name AS employeename,
                                        e.user_name AS person_name,
                                        e.user_state,
                                        f.Department_id,
                                        f.Department_name,
                                        '' AS cancel_remarks,
                                        a.phone AS employeenumber,
                                        a.id AS row_id,
                                        'log_table' AS table_name,
                                        COALESCE(sge.mail_subject, '') AS mail_subject,
                                        sge.message_id AS message_id,
                                        '' AS mail_body,
                                        group_concat(sgea.mail_attachment_path) AS mail_attachment_path,
                                        sge.mail_from AS mail_form,
                                        sge.mail_to_address AS mail_to,
                                        group_concat(sgea.mail_attachment_filename,'')  as mail_attachment_filename,
                                        sge.from_name as from_name,
                                        sge.mail_date as mail_date,
                                        sge.mail_cc as mail_cc,
                                        sge.mail_bcc as mail_bcc
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
                                        lead_info c,
                                        user_details e,
                                        department f
                                    WHERE
                                        (a.rep_id IN ('$children'))
                                            AND a.call_type IN ('complete')
                                            AND a.logtype = b.lookup_id
                                            AND a.type NOT IN ('internal')
                                            AND a.rep_id = e.user_id
                                            AND e.department = f.Department_id
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



    public function fetch_mytaskCompletedReplogInternal($user_id) {
       
        try{
            $children = $user_id."','";
            $children .= $this->getChildrenForParent($user_id);
            $query= $GLOBALS['$dbFramework']->query("
                SELECT a.type,a.log_name as event_name,a.reminderid,a.call_type as                                     reminderstatus, a.starttime as meeting_start,a.endtime as meeting_end,
                                                    a.time as remi_date,a.note as remarks,a.leademployeeid as leadempid,a.leadid,a.logtype as conntype,
                                                    a.rep_id AS user_id,a.time as closed_date,coalesce(ud.user_name,'-') as created_by,a.path as path,
                                                    b.lookup_id,b.lookup_value,
                                                    d.user_id AS employeeid,d.phone_num AS employeephone1,d.user_name AS employeename,
                                                    e.user_name AS person_name,e.user_state,c.user_name as leadname,
                                                    f.Department_id,f.Department_name,'' as cancel_remarks,a.phone as employeenumber,a.rating as rating,a.id as row_id,'log_table' as table_name,COALESCE(sge.mail_subject, '') AS mail_subject,sge.message_id as message_id,sge.mail_body AS mail_body,
                                                        group_concat(sgea.mail_attachment_path,'') AS mail_attachment_path,
                                                        sge.mail_from AS mail_form,
                                                        sge.mail_to_address AS mail_to,
                                                        group_concat(sgea.mail_attachment_filename,'')  as mail_attachment_filename,
                                                        sge.from_name as from_name,
                                                        sge.mail_date as mail_date,
                                                        sge.mail_cc as mail_cc,
                                                        sge.mail_bcc as mail_bcc
                                                    from rep_log as a 
                                                    LEFT JOIN user_details as d ON 
                                                    a.leademployeeid = d.user_id
                                                    left join user_details as ud
                                                    on a.rep_id = ud.user_id
                                                    LEFT JOIN
                                                    support_group_emails AS sge 
                                                    ON a.message_id = sge.message_id
                                                    LEFT JOIN
                                                    support_group_email_attachments AS sgea ON a.message_id = sgea.message_id
                                                    ,
                                                    lookup b,
                                                    user_details e,
                                                    department f,
                                                    user_details c
                                                    where 
                                                    a.call_type IN ('complete')
                                                    AND a.logtype = b.lookup_id
                                                   -- AND a.reminderid IS NULL
                                                    AND a.type = 'internal'
                                                    AND a.rep_id IN ('$children')
                                                    AND a.rep_id = e.user_id
                                                    AND a.leadid = c.user_id
                                                    AND e.department = f.Department_id
                                                    group by a.id
                                                    ORDER BY a.time desc");
            return $query->result();
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }

    }


    public function updateOldEventReschedule($updateArray,$lead_reminder_id){
        try{

         $update =$GLOBALS['$dbFramework']->update('lead_reminder',$updateArray,array('lead_reminder_id'=>$lead_reminder_id)); 
         return $update;      
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
        
    }

    public function checkForTheModule($rep_id) {
        try {
            
            $query=$GLOBALS['$dbFramework']->query("SELECT ul.manager_module as manager_owner,ul.sales_module as rep_owner
                                                    FROM user_licence as ul
                                                    WHERE ul.user_id = '$rep_id'"); 
            return $query->result();
        } 
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function checkLeadOwner($lead_id) {
       try{

         $query =$GLOBALS['$dbFramework']->query("SELECT 
                                                lead_manager_owner as manager_owner,
                                                lead_rep_owner as rep_owner
                                                FROM
                                                lead_info
                                                WHERE
                                                lead_id='$lead_id'");

         return $query->result();      
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
   }

   public function checkCustomerOwner($customer_id) {
       try{

         $query =$GLOBALS['$dbFramework']->query("SELECT 
                                                customer_manager_owner as manager_owner,
                                                customer_rep_owner as rep_owner
                                                FROM
                                                customer_info
                                                WHERE
                                                customer_id='$customer_id'");

         return $query->result();      
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
   }

   

      public function getOpportunityStageId($event_lead) {
       try{

        $query =$GLOBALS['$dbFramework']->query("SELECT 
                                                od.opportunity_stage as opportunity_stage
                                                FROM
                                                opportunity_details as od
                                                WHERE
                                                od.opportunity_id='$event_lead'");

         return $query->result();      
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
   }



   public function checkOpportunityOwner($oppid) {
       try{
            
         $query =$GLOBALS['$dbFramework']->query("SELECT 
                                                stage_manager_owner_id as manager_owner,
                                                owner_id as rep,
                                                stage_owner_id as rep_owner
                                                FROM
                                                opportunity_details
                                                WHERE
                                                opportunity_id='$oppid'");

         return $query->result();      
        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
   }

    public function checkSupportOwner($reqid) {
       try{
            
         $query =$GLOBALS['$dbFramework']->query("SELECT 
                                                manager_owner_id as manager_owner,
                                                owner_id as rep_owner
                                                FROM
                                                support_opportunity_details
                                                WHERE
                                                request_id='$reqid'");

         return $query->result();      

        }
        catch (LConnectApplicationException $e) {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
   }   

   public function getUserName($user_id){
      $query =$GLOBALS['$dbFramework']->query("SELECT 
                                                ud.user_name
                                                FROM
                                                user_details as ud
                                                WHERE
                                                ud.user_id='$user_id'");

         return $query->result();  
   }

   public function fetchSupportTask($user_id,$rem_id){
        $children = $user_id."','";
        $children .= $this->getChildrenForParent($user_id);
                if($rem_id=='') {
                $query= $GLOBALS['$dbFramework']->query("
                    SELECT  a.lead_id as lead_id,sopd.request_name as lead_name,
                    a.addremtime,a.duration,a.rem_time,a.lead_reminder_id as id,a.status,a.event_name,
                    a.meeting_start,a.remi_date, a.meeting_start,a.remarks,a.leadempid,a.lead_id,
                    a.remi_date,a.conntype,a.rep_id AS user_id,a.reminder_members,a.type,coalesce(ud.user_name,'-') as created_by,
                    b.lookup_id,b.lookup_value,
                    d.contact_id AS employeeid,d.contact_number AS employeephone1,d.contact_name AS employeename,
                    e.user_name AS person_name,e.user_state,e.user_id AS person_id,
                    f.Department_id,f.Department_name,a.lead_reminder_id,ul.sales_module
                    FROM
                    lead_reminder a
                    LEFT JOIN user_details ud 
                    ON 
                    ud.user_id = a.created_by,
                    lookup b,
                    lead_info c,
                    contact_details d,
                    user_details e,
                    department f,user_licence ul,support_opportunity_details as sopd
                    WHERE
                    (a.rep_id IN ('1708030427505982a646bcdbc'))
                    AND a.leadempid = d.contact_id
                    AND a.status IN ('pending','scheduled')
                    AND a.conntype = b.lookup_id
                    AND a.lead_id=sopd.request_id
                    AND (a.rep_id = e.user_id)
                    AND e.department = f.Department_id
                    and (ul.user_id=a.rep_id)
                    GROUP BY a.lead_reminder_id
                    ORDER BY a.meeting_start
                ");  
                return $query->result();                  
            }    
    }

    public function fetchAllActivitesOfLeadCustSup($leadId,$leadEmpId,$date) {
        try{

            // Open task list form lead reminder table.
            $queryOpen = $GLOBALS['$dbFramework']->query("SELECT coalesce((CASE 
                 WHEN a.type ='support' THEN
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
                (CASE WHEN a.type ='support' THEN (SELECT request_id FROM support_opportunity_details as li 
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
                a.addremtime,a.duration,a.rem_time,a.lead_reminder_id as id,a.status,a.event_name,
                a.meeting_start,a.remi_date, a.meeting_end,a.remarks,a.leadempid,a.lead_id,
                a.remi_date,a.conntype,a.rep_id AS user_id,a.reminder_members,a.type,coalesce(ud.user_name,'-') as created_by,
                b.lookup_id,b.lookup_value,
                d.contact_id AS employeeid,d.contact_number AS employeephone1,d.contact_name AS employeename,
                e.user_name AS person_name,e.user_state,e.user_id AS person_id,
                f.Department_id,f.Department_name,a.lead_reminder_id,ul.sales_module,coalesce(a.cancel_remarks,'') as cancel_remarks,a.id as row_id,'reminder' as table_name,'' AS mail_subject,'' as message_id,
                '' AS mail_attachment_path,
                '' AS mail_form,
                '' AS mail_to,
                '' as mail_attachment_filename,
                '' AS mail_body,
                '' as from_name,
                '' as mail_date,
                '' as mail_cc,
                '' as mail_bcc
                FROM
                lead_reminder a
                LEFT JOIN user_details ud 
                ON 
                ud.user_id = a.created_by,
                lookup b,
                lead_info c,
                contact_details d,
                user_details e,
                department f,user_licence ul
                WHERE
                a.leadempid = '$leadEmpId' 
                AND a.lead_id = '$leadId'
                AND a.leadempid = d.contact_id
                AND a.status in ('pending','scheduled','cancel','reschedule')
                AND a.conntype = b.lookup_id
                AND (a.rep_id = e.user_id)
                AND e.department = f.Department_id
                and (ul.user_id=a.rep_id)
                GROUP BY a.lead_reminder_id
                ORDER BY a.id desc
            ");
            // close task list form rep log table.

            $queryClosed= $GLOBALS['$dbFramework']->query("SELECT coalesce((CASE WHEN a.type = 'opportunity' THEN 
                (SELECT opportunity_name
                FROM opportunity_details AS li
                WHERE a.leadid = li.opportunity_id)
                WHEN a.type = 'lead' THEN 
                (SELECT lead_name  
                FROM lead_info AS li
                WHERE a.leadid = li.lead_id)
                ELSE 
                (SELECT customer_name
                FROM customer_info AS ci
                WHERE a.leadid = ci.customer_id)
                END)
                ,'-') AS leadname,
                (CASE WHEN a.type = 'opportunity' THEN
                (SELECT opportunity_id
                FROM opportunity_details AS li
                WHERE a.leadid = li.opportunity_id)
                WHEN a.type = 'lead' THEN 
                (SELECT lead_id
                FROM lead_info AS li
                WHERE a.leadid = li.lead_id)
                ELSE 
                (SELECT customer_id
                FROM customer_info AS ci 
                WHERE a.leadid = ci.customer_id)
                END) AS leadid,
                '-' as duration,'-' as rem_time,a.log_name as event_name,a.reminderid,a.call_type as reminderstatus,
                a.starttime as meeting_start ,a.time as remi_date, a.note as remarks,a.leademployeeid as leadempid,a.leadid,a.logtype as conntype,a.rep_id AS user_id,a.type as type,a.time as closed_date,coalesce(ud.user_name,'-') as created_by,a.endtime as meeting_end,a.path as path,
                b.lookup_id,b.lookup_value,a.rating as rating,
                d.contact_id AS employeeid,d.contact_number AS employeephone1,d.contact_name AS employeename,
                e.user_name AS person_name,e.user_state,
                f.Department_id,f.Department_name,'' as cancel_remarks,a.phone as employeenumber,'Completed' as status,a.id as row_id,'log_table' as table_name,COALESCE(sge.mail_subject, '') AS mail_subject,sge.message_id AS message_id,
                sge.mail_body AS mail_body,
                group_concat(sgea.mail_attachment_path,'') AS mail_attachment_path,
                sge.mail_from AS mail_form,
                sge.mail_to_address AS mail_to,
                group_concat(sgea.mail_attachment_filename,'')  as mail_attachment_filename,
                sge.from_name as from_name,
                sge.mail_date as mail_date,
                sge.mail_cc as mail_cc,
                sge.mail_bcc as mail_bcc
                FROM
                rep_log a
                left join user_details as ud
                on a.rep_id = ud.user_id
                left join contact_details as d
                on a.leademployeeid = d.contact_id
                LEFT JOIN
                support_group_emails AS sge 
                ON a.message_id = sge.message_id
                LEFT JOIN
                support_group_email_attachments AS sgea 
                ON a.message_id = sgea.message_id,
                lookup b,
                lead_info c,
                user_details e,
                department f
                WHERE
                a.call_type IN ('complete')
                AND a.logtype = b.lookup_id
                AND a.leadid = '$leadId'
                AND a.leademployeeid = '$leadEmpId' 
                AND a.type NOT IN ('internal,unassociated')
               -- AND a.reminderid IS NULL
                AND a.rep_id = e.user_id
                AND e.department = f.Department_id
                group by a.id
                ORDER BY a.id desc");

                //Declare Array

                $totalArray = array();

                $totalArray =  array_merge($queryOpen->result(),$queryClosed->result());

                // Multisorting Based on time.

                $time = array();

                foreach ($totalArray as $key => $value) 
                {
                    $time[$key] = $value->meeting_start;
                }

                array_multisort($time,SORT_DESC,$totalArray);

                return $totalArray;

            //


        }
        catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function fetchAllActivitesOfInternal($leadId,$leadEmpId,$date) {
        try{

            $queryOpen = $GLOBALS['$dbFramework']->query("SELECT a.event_name AS title,
                a.addremtime,a.duration,a.rem_time,a.lead_reminder_id,a.status,
                a.event_name, a.meeting_start,a.remi_date, a.meeting_start,a.remarks,
                a.leadempid,a.lead_id as leadid,a.remi_date,a.conntype,a.rep_id AS user_id,
                a.reminder_members,b.lookup_id,b.lookup_value, d.user_id AS employeeid,d.user_name as leadname,
                d.phone_num AS employeephone1,d.user_name AS employeename,
                e.user_name AS person_name,e.user_state,f.Department_id,f.Department_name,
                a.type as type, a.meeting_start as start,a.conntype as activity_id,
                b.lookup_value as activity_name,coalesce(ud.user_name,'-') as created_by,
                e.user_name as activity_owner, a.lead_id as leadid, e.user_id as person_id,
                a.lead_reminder_id as id, a.type,ul.sales_module,coalesce(a.cancel_remarks,'') as cancel_remarks,a.id as row_id,'reminder' as table_name,'' AS mail_subject,'' as message_id,
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
                user_details e,  department f,user_licence ul 
                where                
                a.leadempid = d.user_id 
                AND a.leadempid = '$leadEmpId'
                AND a.lead_id = '$leadId'
                AND a.conntype = b.lookup_id
                AND (a.rep_id = e.user_id)
                and a.type='internal'
                AND a.status in ('pending','scheduled','cancel','reschedule')
                AND e.department = f.Department_id
                and (ul.user_id=a.rep_id)
                GROUP BY a.lead_reminder_id
                ORDER BY a.id desc
            ");
            

         $queryClosed = $GLOBALS['$dbFramework']->query("
                                                    SELECT a.type,a.log_name as event_name,a.reminderid,a.call_type                                            as reminderstatus, a.starttime as meeting_start,a.endtime 
                                                    as meeting_end,
                                                    a.time as remi_date,a.note as remarks,a.leademployeeid as leadempid,a.leadid,a.logtype as conntype,
                                                    a.rep_id AS user_id,a.time as closed_date,coalesce(ud.user_name,'-') as created_by,a.path as path,
                                                    b.lookup_id,b.lookup_value,
                                                    d.user_id AS employeeid,d.phone_num AS employeephone1,d.user_name AS employeename,
                                                    e.user_name AS person_name,e.user_state,c.user_name as leadname,
                                                    f.Department_id,f.Department_name,'' as cancel_remarks,a.phone as employeenumber,a.rating as rating,'Completed' as status,a.id as row_id,'log_table' as table_name,
                                                    COALESCE(sge.mail_subject, '') AS mail_subject,
                                                    sge.message_id AS message_id,
                                                    sge.mail_body AS mail_body,
                                                    group_concat(sgea.mail_attachment_path,'') AS mail_attachment_path,
                                                    sge.mail_from AS mail_form,
                                                    sge.mail_to_address AS mail_to,
                                                    group_concat(sgea.mail_attachment_filename,'')  as mail_attachment_filename,
                                                    sge.from_name as from_name,
                                                    sge.mail_date as mail_date,
                                                    sge.mail_cc as mail_cc,
                                                    sge.mail_bcc as mail_bcc
                                                    from rep_log as a 
                                                    LEFT JOIN user_details as d ON 
                                                    a.leademployeeid = d.user_id
                                                    left join user_details as ud
                                                    on a.rep_id = ud.user_id
                                                    LEFT JOIN
                                                support_group_emails AS sge 
                                                ON a.message_id = sge.message_id
                                                 support_group_emails AS sge 
                                                 ON a.message_id = sge.message_id
                                                LEFT JOIN
                                                support_group_email_attachments AS sgea 
                                                ON a.message_id = sgea.message_id,
                                                    lookup b,
                                                    user_details e,
                                                    department f,
                                                    user_details c
                                                    where 
                                                    a.call_type IN ('complete')
                                                    AND a.leadid = '$leadId'
                                                    AND a.leademployeeid = '$leadEmpId' 
                                                    AND a.logtype = b.lookup_id
                                                    -- AND a.reminderid IS NULL
                                                    AND a.type = 'internal'
                                                    AND a.rep_id = e.user_id
                                                    AND a.leadid = c.user_id
                                                    AND e.department = f.Department_id
                                                    group by a.id
                                                    ORDER BY a.id desc
                                                ");
            
                //Declare Array

                $totalArray = array();

                $totalArray =  array_merge($queryOpen->result(),$queryClosed->result());

                // Multisorting Based on time.

                $time = array();

                foreach ($totalArray as $key => $value) 
                {
                    $time[$key] = $value->meeting_start;
                }

                array_multisort($time,SORT_ASC,$totalArray);

                return $totalArray;
        }
        catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function notificationShowStatus($notifyUpdateData,$cmp_lead,$user_id) {
        try{

            $update = $GLOBALS['$dbFramework']->update('notifications',$notifyUpdateData,array('task_id'=>$cmp_lead,'to_user' =>$user_id));
            return $update;
        }
        catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function fetchAllActivitesOfUserMail($leadEmpId,$date)
    {
        try{            
            $userMailQuery = $GLOBALS['$dbFramework']->query(
                                            "
                                            SELECT 
                                            COALESCE((CASE
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
                                                    END),
                                                    '-') AS leadname,
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
                                            '-' AS duration,
                                            '-' AS rem_time,
                                            a.log_name AS event_name,
                                            a.reminderid,
                                            a.call_type AS reminderstatus,
                                            a.starttime AS meeting_start,
                                            a.time AS remi_date,
                                            a.note AS remarks,
                                            a.leademployeeid AS leadempid,
                                            a.leadid,
                                            a.logtype AS conntype,
                                            a.rep_id AS user_id,
                                            a.type AS type,
                                            a.time AS closed_date,
                                            COALESCE(ud.user_name, '-') AS created_by,
                                            a.endtime AS meeting_end,
                                            a.path AS path,
                                            b.lookup_id,
                                            b.lookup_value,
                                            a.rating AS rating,
                                            d.contact_id AS employeeid,
                                            d.contact_number AS employeephone1,
                                            d.contact_name AS employeename,
                                            e.user_name AS person_name,
                                            e.user_state,
                                            f.Department_id,
                                            f.Department_name,
                                            '' AS cancel_remarks,
                                            a.phone AS employeenumber,
                                            'Completed' AS status,
                                            a.id AS row_id,
                                            'log_table' AS table_name,
                                            COALESCE(sge.mail_subject, '') AS mail_subject,
                                            sge.mail_body AS mail_body,
                                            group_concat(sgea.mail_attachment_path,',') AS mail_attachment_path,
                                            sge.mail_from AS mail_form,
                                            sge.mail_to_address AS mail_to,
                                            group_concat(sgea.mail_attachment_filename,',')  as mail_attachment_filename,
                                            sge.from_name as from_name,
                                            sge.mail_date as mail_date,
                                            sge.mail_cc as mail_cc,
                                            sge.mail_bcc as mail_bcc,
                                            sge.message_id as message_id
                                            FROM
                                            rep_log a
                                                LEFT JOIN
                                            user_details AS ud ON a.rep_id = ud.user_id
                                                LEFT JOIN
                                            contact_details AS d ON a.leademployeeid = d.contact_id
                                                LEFT JOIN
                                            support_group_emails AS sge ON a.message_id = sge.message_id
                                            LEFT JOIN
                                            support_group_email_attachments AS sgea 
                                            ON a.message_id = sgea.message_id,
                                            lookup b,
                                            lead_info c,
                                            user_details e,
                                            department f
                                            WHERE
                                            a.call_type IN ('complete')
                                                AND a.logtype = b.lookup_id
                                                AND a.message_id = '$leadEmpId'
                                                AND a.type IN ('unassociated')
                                                AND a.reminderid IS NULL
                                                AND a.rep_id = e.user_id
                                                AND e.department = f.Department_id
                                            GROUP BY a.id
                                            ORDER BY a.id DESC
                                            "
                                                    );
            return $userMailQuery->result();
        }
        catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }

    public function fetchTaskEmailAttachments($msg_id)
    {
        try{
             $userMailQuery = $GLOBALS['$dbFramework']->query("SELECT sge.* from support_group_emails sge
            where sge.message_id = '$msg_id'");

            $mailAttchments = $GLOBALS['$dbFramework']->query("SELECT sgea.* from support_group_email_attachments sgea
            where sgea.message_id = '$msg_id'");

            $MailArray = array('email'=>$userMailQuery->result(), 'attachments'=>$mailAttchments->result());

            return $MailArray;
        }
        catch(LConnectApplicationException $e){
        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
        throw $e;
        }
    }
    
}
?>
