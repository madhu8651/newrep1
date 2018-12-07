<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('sales_com_personalmailModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class sales_com_personalmailModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }

    public function get_domain($user_id){
        try{
           $query = $GLOBALS['$dbFramework']->query("SELECT  user_primary_email as email_id from user_details where user_state = 1 and user_name != 'Admin'");
           return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
            
    }

    public function get_domain1($user_id){
        try{
                $query = $GLOBALS['$dbFramework']->query("SELECT user_primary_email as Mail_ID, user_name as Name from user_details where user_state = 1 and user_name!='Admin'");
                return $query->result();
        }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }

    }

    public function get_data($tabtype) {
        try{
            if(!empty(isset($_POST['user_id']))){
                $userId = $_POST['user_id'];
            }else{
                $userId = $this->session->userdata('uid');
            }
            
            $activeModule = $this->session->userdata('active_module_name');
            // Declaring Array
            $supportGroupEmailArray = array();
            // Declaring Email Array 
            $finalArray = array();
            $emailDataArray  = array();
            $emailAttachementDataArray = array();
            
            switch ($tabtype) {
               
                case 'unassoc' :
                                    $query = $GLOBALS['$dbFramework']->query("
                                                            SELECT 
                                                            sg.*,sge.mail_attachment_filename,sge.mail_attachment_path
                                                            FROM
                                                            support_group_emails sg left join support_group_email_attachments sge on
                                                            sg.message_id = sge.message_id
                                                            WHERE
                                                            sg.mail_associated_state in(0, 11)
                                                            AND sg.user_type = 'personalmail'
                                                            AND sg.user_id='$userId'
                                                            ORDER BY sg.mail_date DESC
                                                        ");
               

                                    $supportGroupEmailArray = $query->result();
                break;
                
                case 'assoc' :   $query = $GLOBALS['$dbFramework']->query("
                                                            SELECT 
                                                            sg.*,sge.mail_attachment_filename,sge.mail_attachment_path
                                                            FROM
                                                            support_group_emails sg left join support_group_email_attachments sge on
                                                            sg.message_id = sge.message_id
                                                            WHERE
                                                            sg.mail_associated_state in(1,10)
                                                            AND sg.user_type = 'personalmail'
                                                            AND sg.user_id='$userId'
                                                            ORDER BY sg.mail_date DESC
                                                        ");

                                $supportGroupEmailArray = $query->result();
                   
                break;

                case 'conflict' :   $query = $GLOBALS['$dbFramework']->query("
                                                            SELECT 
                                                           sg.*,sge.mail_attachment_filename,sge.mail_attachment_path
                                                            FROM
                                                            support_group_emails sg left join support_group_email_attachments sge on
                                                            sg.message_id = sge.message_id
                                                            WHERE
                                                            (sg.mail_associated_state = 2
                                                            OR sg.mail_associated_state = 4)
                                                            AND sg.user_type = 'personalmail'
                                                            AND sg.user_id='$userId'
                                                            ORDER BY sg.mail_date DESC");

                                    $supportGroupEmailArray = $query->result();
                  
                break;

                case 'allmails' :   $query = $GLOBALS['$dbFramework']->query("
                                                            SELECT 
                                                           sg.*,sge.mail_attachment_filename,sge.mail_attachment_path
                                                            FROM
                                                            support_group_emails sg left join support_group_email_attachments sge on
                                                            sg.message_id = sge.message_id
                                                            WHERE
                                                            sg.user_type = 'personalmail'
                                                            AND sg.user_id='$userId'
                                                            and mail_associated_state!=9
                                                            ORDER BY sg.mail_date DESC");

                                    $supportGroupEmailArray = $query->result();

                break;

                case 'sent' :   $query = $GLOBALS['$dbFramework']->query("
                                                            SELECT 
                                                           sg.*,sge.mail_attachment_filename,sge.mail_attachment_path
                                                            FROM
                                                            support_group_emails sg left join support_group_email_attachments sge on
                                                            sg.message_id = sge.message_id
                                                            WHERE
                                                            sg.user_type = 'personalmail'
                                                            AND sg.user_id='$userId'
                                                            and mail_associated_state = 9
                                                            ORDER BY sg.mail_date DESC");

                                    $supportGroupEmailArray = $query->result();

                break;
            }

            if(!empty($supportGroupEmailArray)){
                    
                    foreach ($supportGroupEmailArray as $key => $value) 
                    {
                        // Get All LeadCustId , Message Id, Attachments based on the lead,customer and opportunity.
        
                                $query = $GLOBALS['$dbFramework']->query("
                                                    SELECT 
                                                    sge.mail_attachment_path,sge.mail_attachment_filename
                                                    FROM
                                                    support_group_email_attachments AS sge
                                                    where sge.message_id='$value->message_id'
                                                ");
                                $attachmentArray = $query->result();



                                if(!empty($attachmentArray)){
                                    
                                    $emailDataArray = array(
                                        'message_id'        => $value->message_id,
                                        'associate'         => $value->type,
                                        'contact_id'        => $value->contact_id,
                                        'lead_cust_opp_id'  =>$value->lead_cust_opp_id,
                                        'mail_from'         =>$value->mail_from,
                                        'from_name'         =>$value->from_name,
                                        'mail_to'           =>$value->mail_to_address,
                                        'mail_cc'           =>$value->mail_cc,
                                        'mail_date'         =>date('d-m-Y H:i:s',strtotime($value->mail_date)),
                                        'mail_subject'      =>$value->mail_subject, 
                                        'mail_body'         =>$value->mail_body,   
                                        'mail_read_state'   =>$value->mail_read_state,
                                        'mail_associated_state'=>$value->mail_associated_state, 
                                        'associated_user_id'=>$value->associated_user_id,
                                        'attachment'        =>$attachmentArray,
                                        'mail_to_address'   =>$value->mail_to_address
                                    ); 
                                }else{
                                      $emailDataArray = array(
                                        'message_id'        => $value->message_id,
                                        'associate'         => $value->type,
                                        'contact_id'        => $value->contact_id,
                                        'lead_cust_opp_id'  =>$value->lead_cust_opp_id,
                                        'mail_from'         =>$value->mail_from,
                                        'from_name'         =>$value->from_name,
                                        'mail_to'           =>$value->mail_to_address,
                                        'mail_cc'           =>$value->mail_cc,
                                        'mail_date'         =>date('d-m-Y H:i:s',strtotime($value->mail_date)),
                                        'mail_subject'      =>$value->mail_subject, 
                                        'mail_body'         =>$value->mail_body,   
                                        'mail_read_state'   =>$value->mail_read_state,
                                        'mail_associated_state'=>$value->mail_associated_state, 
                                        'associated_user_id'=>$value->associated_user_id);

                                }

                            //Add attachment only if message id same.
                            // fetch email attachment of each message id.              
                       
                    array_push($finalArray, $emailDataArray);
                    } 
            }
          return $finalArray;

        }
        catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

   
    public function get_matchdata($nametype,$search_value,$matchdata) {
        try{
            // Matching Contacts of LeadCustOpp based on name or phone.
            $activeModule =  $this->session->userdata('active_module_name');
            
            if(!empty(isset($_POST['user_id']))){
                $repId = $_POST['user_id'];
                $activeModule = 'sales';
            }else{
               $repId = $this->session->userdata('uid');
            }

           // $repId = $this->session->userdata('uid');
            $leadArray = array();
            $customerArray = array();
            $opportunityArray = array();
            $finalArray = array();
                // if its is matched to all , show every data.
            if($matchdata == 'unassoc'){
                if($activeModule=='sales' || $activeModule=='executive' || $activeModule =='manager' ){
                    // Lead Data.
                    $val = '[[:<:]]';
                    $val1 = '[[:>:]]';
                    $val3 = $val.$search_value.$val1;                   
                    //echo $val3; exit();
                    $query = $GLOBALS['$dbFramework']->query("
                                                        SELECT 
                                                            cd.*, li.lead_id as associated_id, li.lead_name AS name, 'lead' AS type
                                                        FROM
                                                            contact_details AS cd,
                                                            lead_info AS li
                                                        WHERE
                                                            cd.lead_cust_id = li.lead_id
                                                                AND (JSON_EXTRACT(cd.contact_number, '$.phone') RLIKE '$val3'
                                                                OR cd.contact_name = '$search_value')
                                                                /*and li.lead_closed_reason is null*/
                                                                group by cd.contact_id
                                                        ");
                    $leadArray = $query->result();
                    // Customer Data

                    $query1 = $GLOBALS['$dbFramework']->query("
                                                        SELECT 
                                                            cd.*, ci.customer_id as associated_id, ci.customer_name AS name, 'customer' AS type
                                                        FROM
                                                            contact_details AS cd,
                                                            lead_info AS li,    
                                                            customer_info AS ci
                                                        WHERE
                                                            (cd.lead_cust_id=ci.customer_id or cd.lead_cust_id = ci.lead_id)
                                                                AND (JSON_EXTRACT(cd.contact_number, '$.phone') RLIKE '$val3'
                                                                OR cd.contact_name = '$search_value')
                                                                and li.lead_closed_reason ='closed_won'
                                                                group by cd.contact_id
                                                        "); 
                    $customerArray = $query1->result();

                    // Opportunity Data

                    $query2 = $GLOBALS['$dbFramework']->query(
                                                            /*SELECT cd.*, od.opportunity_id as associated_id, od.opportunity_name AS name,'opportunity' AS type
                                                            FROM
                                                            opportunity_details AS od,
                                                            contact_details as cd
                                                            WHERE
                                                            od.lead_cust_id = cd.lead_cust_id
                                                            and (JSON_EXTRACT(cd.contact_number, '$.*') LIKE '%$search_value%'
                                                            OR cd.contact_name LIKE '%$search_value%')
                                                            AND od.closed_reason IS NULL
                                                            order by cd.contact_id*/
                                                            "SELECT cd.*, od.opportunity_id as associated_id, od.opportunity_name AS name,'opportunity' AS type
                                                            FROM
                                                            contact_details as cd
                                                            LEFT JOIN
                                                            opportunity_details AS od ON cd.lead_cust_id = od.lead_cust_id
                                                            OR cd.lead_cust_id != od.lead_cust_id
                                                            WHERE
                                                            FIND_IN_SET(cd.contact_id,
                                                            REPLACE(od.opportunity_contact,
                                                            ':',
                                                            ','))
                                                            AND (JSON_EXTRACT(cd.contact_number, '$.phone') RLIKE '$val3'
                                                            OR cd.contact_name = '$search_value')
                                                            AND od.closed_reason IS NULL
                                                            order by cd.contact_id       
                                                            ");
                    $opportunityArray = $query2->result();
                    $finalArrayData = array();
                    // lead data array is push to final array.
                    foreach ($leadArray as $key => $value) {
                        array_push($finalArrayData,$leadArray[$key]);                        
                    }
                    // customer data array is push to final array.
                    foreach ($customerArray as $key => $value) {
                        array_push($finalArrayData,$customerArray[$key]);
                    }
                    // opportunity data array is push to final array.
                    foreach ($opportunityArray as $key => $value) {
                        array_push($finalArrayData,$opportunityArray[$key]);
                    }
                    return $finalArrayData;
                }                
            } else if($matchdata == 'conflict'){ 
                    if($activeModule=='sales' || $activeModule=='executive' || $activeModule =='manager' ){
                        // Lead Data.
                        $query = $GLOBALS['$dbFramework']->query("
                                                            SELECT 
                                                                cd.*, li.lead_id as associated_id, li.lead_name AS name, 'lead' AS type
                                                            FROM
                                                                contact_details AS cd,
                                                                lead_info AS li
                                                            WHERE
                                                            cd.lead_cust_id = li.lead_id
                                                            AND (JSON_EXTRACT(cd.contact_email, '$.*') LIKE '%$search_value%')
                                                            /*AND li.lead_closed_reason is null*/
                                                            group by cd.contact_id
                                                            ");
                        $leadArray = $query->result();
                        // Customer Data

                        $query1 = $GLOBALS['$dbFramework']->query("
                                                        SELECT 
                                                            cd.*, ci.customer_id as associated_id, ci.customer_name AS name, 'customer' AS type
                                                        FROM
                                                            contact_details AS cd,
                                                            lead_info AS li,    
                                                            customer_info AS ci
                                                        WHERE
                                                        (cd.lead_cust_id=ci.customer_id or cd.lead_cust_id = ci.lead_id)
                                                        AND (JSON_EXTRACT(cd.contact_email, '$.*') LIKE '%$search_value%')
                                                        AND li.lead_closed_reason ='closed_won'
                                                        group by cd.contact_id
                                                        ");  
                        $customerArray = $query1->result();

                        // Opportunity Data

                        $query2 = $GLOBALS['$dbFramework']->query("SELECT cd.*, od.opportunity_id AS associated_id,
                                                                    od.opportunity_name AS name,'opportunity' AS type
                                                                FROM
                                                                    contact_details AS cd
                                                                        LEFT JOIN
                                                                    opportunity_details AS od ON cd.lead_cust_id = od.lead_cust_id
                                                                        OR cd.lead_cust_id != od.lead_cust_id
                                                                WHERE
                                                                    FIND_IN_SET(cd.contact_id,
                                                                            REPLACE(od.opportunity_contact,
                                                                                ':',
                                                                                ','))
                                                                        AND (JSON_EXTRACT(cd.contact_email, '$.*') LIKE '%$search_value%')
                                                                        AND od.closed_reason IS NULL
                                                                ORDER BY cd.contact_id              
                                                            ");

                        $opportunityArray = $query2->result();
                        $finalArrayData = array();
                        // lead data array is push to final array.
                        if(!empty($leadArray)){
                            foreach ($leadArray as $key => $value) {
                                array_push($finalArrayData,$leadArray[$key]);                        
                            }
                        }    
                        // customer data array is push to final array.
                        if(!empty($customerArray)){
                            foreach ($customerArray as $key => $value) {
                                array_push($finalArrayData,$customerArray[$key]);
                            }
                        }    
                        // opportunity data array is push to final array.
                        if(!empty($opportunityArray)){
                            foreach ($opportunityArray as $key => $value) {
                                array_push($finalArrayData,$opportunityArray[$key]);
                            }
                        }    

                        return $finalArrayData;
                    }                
                }
           

        }
        catch(LConnectApplicationException $e){

        }
    }

    
    public function remove_unassoc($lead_cust_id,$hidmsgid,$hidemail,$type,$pagetype,$associated_ids){
        try{
                if(!empty(isset($_POST['user_id']))){
                     $repid = $_POST['user_id'];
                     $activeModule = 'sales';    
                }else{
                    $repid=$this->session->userdata('uid');
                    $activeModule = $this->session->userdata('active_module_name');    
                }    
               /* id to be taken from session */                          
               /* var_dump($pagetype);
                var_dump($hidmsgid);
               var_dump($type);
               var_dump($hidemail);
               var_dump($lead_cust_id);*/

                $ass_arr = explode('_',$associated_ids);
                $associated_id =$ass_arr[0];
                $contact_id =$ass_arr[1];
                $contact_name =$ass_arr[2];

                    if($pagetype=='conflict'){
                        $str=explode('_',$hidmsgid);
                        $hidmsgid=$str[1];
                    }

                    switch ($pagetype) {

                        case 'unassoc': 
                            
                            $GLOBALS['$logger']->debug('enetered unassociated type');

                            $dt = date('ymdHis');
                            $log_name='Incoming email from '.$hidemail;
                          

                            $GLOBALS['$logger']->debug('matching details are'.$hidemail.''.$contact_id.''.$type.''.$hidmsgid.''.$repid);

                            $query=$GLOBALS['$dbFramework']->query("UPDATE support_group_emails set contact_id='".$contact_id."',lead_cust_opp_id='".$associated_id."', remarks='match found',
                                                                type='".$type."', associated_user_id='".$repid."',mail_associated_state=1 where message_id='".$hidmsgid."'");
                            //$GLOBALS['$logger']->debug('update status for support_group_emails of matched data'.$query);

                            $query1=$GLOBALS['$dbFramework']->query("select * from contact_details where json_extract(contact_email, '$.*') like '%$hidemail%' and contact_id='".$contact_id."'");
                             
                            if($query1->num_rows()==0){
                                $GLOBALS['$logger']->debug('if contact_email column json is blank it retuns zero then empty that column and insert new email');

                                    $ab = '{"email": ["", ""]}';
                                  
                                    $query11 = $GLOBALS['$dbFramework']->query("SELECT contact_id,contact_email 
                                    from contact_details WHERE contact_email LIKE '".$ab."'AND contact_id = '$contact_id'");                            
                                    //if contact_email column json is blank it retuns zero then empty that column and insert 
                                    if($query11->num_rows()==1){

                                        $contactEmpty = $GLOBALS['$dbFramework']->query("UPDATE contact_details set  contact_email = NULL
                                            where contact_id = '$contact_id'");
                                        $GLOBALS['$logger']->debug('empty the contact email column'.$contactEmpty);
                                        
                                        $val1 = '{"email": ["'.$hidemail.'"]}';
                                        $query22 = $GLOBALS['$dbFramework']->query("UPDATE contact_details set  contact_email = '".$val1."'
                                            where contact_id = '$contact_id'");

                                    }else{
                                         $query33 = $GLOBALS['$dbFramework']->query("UPDATE contact_details SET contact_email=json_array_append(contact_email,'$.email','".$hidemail."')
                                            WHERE contact_id='".$contact_id."'");
                                           $GLOBALS['$logger']->debug('contact details table email-id appended'.$query);
                                    }
                            }
                             
                          /* logging task commented   
                            $data1 = array('rep_id'=>$repid,
                                            'leademployeeid'=>$contact_id,
                                            'leadid'=>$associated_id,
                                            'logtype'=>'EM594ce66d07b9f83',
                                            'call_type'=>'complete',
                                            'path'=>'no_path',
                                            'time'=>$dt,
                                            'message_id'=>$hidmsgid,
                                            'type'=>$type,
                                            'log_name'=>$log_name,
                                            'module_id'=>$activeModule,
                                            'starttime'=>$dt,
                                            'endtime'=>$dt,
                                            'rating'=>4
                            );
                            $insert_log = $GLOBALS['$dbFramework']->insert('rep_log',$data1);

                            //$GLOBALS['$logger']->debug('inserting log to rep_log table'.$insertlog);*/

                            $data = array('notificationID'=>$dt.uniqid(),
                                'notificationShortText'=>'New Email Received',
                                'notificationText'=>'New Email received from '.$contact_name,
                                'from_user'=>$repid,
                                'action_details'=>'email',
                                'notificationTimestamp'=>$dt,
                                'to_user'=>$repid
                            );

                            $insert_notification = $GLOBALS['$dbFramework']->insert('notifications',$data);

                            return $insert_notification;

                            break;
                        
                       case 'conflict':

                            $GLOBALS['$logger']->debug('enetered conflict type');

                            $dt = date('ymdHis');
                            $log_name='Incoming email from '.$hidemail;
                           
                            $GLOBALS['$logger']->debug('matching details are'.$hidemail.''.$contact_id.''.$type.''.$hidmsgid.''.$repid);

                            $query=$GLOBALS['$dbFramework']->query("UPDATE support_group_emails set contact_id='".$contact_id."',lead_cust_opp_id='".$lead_cust_id."', remarks='match found',
                                                                type='".$type."', associated_user_id='".$repid."',mail_associated_state=10 where message_id='".$hidmsgid."'");
                            //$GLOBALS['$logger']->debug('update status for support_group_emails of matched data'.$query);

                            $query1=$GLOBALS['$dbFramework']->query("select * from contact_details where json_extract(contact_email, '$.*') like '%$hidemail%' and contact_id='".$contact_id."'");

                            //$GLOBALS['$logger']->debug('verifiying whethere contact_email exist or not'.$query1->result());

                            if($query1->num_rows()==0){
                                $GLOBALS['$logger']->debug('appending email-id to contact details if it is not there ');

                            $query=$GLOBALS['$dbFramework']->query("UPDATE contact_details SET contact_email=json_array_append(contact_email,'$.email','".$hidemail."')
                                    WHERE contact_id='".$contact_id."'");                               
                            }
                            
                           /* logging task commented  
                            $data1 = array('rep_id'=>$repid,
                                            'leademployeeid'=>$contact_id,
                                            'leadid'=>$associated_id,
                                            'logtype'=>'EM594ce66d07b9f83',
                                            'call_type'=>'complete',
                                            'path'=>'no_path',
                                            'time'=>$dt,
                                            'message_id'=>$hidmsgid,
                                            'type'=>$type,
                                            'log_name'=>$log_name,
                                            'module_id'=>$activeModule,
                                            'starttime'=>$dt,
                                            'endtime'=>$dt,
                                            'rating'=>4
                            );
                            $insert_log = $GLOBALS['$dbFramework']->insert('rep_log',$data1);*/

                            //$GLOBALS['$logger']->debug('inserting log to rep_log table'.$insertlog);

                            $data = array('notificationID'=>$dt.uniqid(),
                                'notificationShortText'=>'New Email Received',
                                'notificationText'=>'New Email received from '.$contact_name,
                                'from_user'=>$repid,
                                'action_details'=>'email',
                                'notificationTimestamp'=>$dt,
                                'to_user'=>$repid
                            );

                            $insert_notification = $GLOBALS['$dbFramework']->insert('notifications',$data);

                            return $insert_notification;

                            break;

                        case 'sentItem':
                            $str=explode(',',$hidemail);
                            $notificationarray = array();

                            $GLOBALS['$logger']->debug('enetered sentItem mail type');

                            $dt = date('ymdHis');
                           
                          

                            $GLOBALS['$logger']->debug('matching details are'.''.$contact_id.''.$type.''.$hidmsgid.''.$repid);

                            $query=$GLOBALS['$dbFramework']->query("UPDATE support_group_emails set contact_id='".$contact_id."',lead_cust_opp_id='".$associated_id."', remarks='match found',type='".$type."', associated_user_id='".$repid."',mail_associated_state=10 where message_id='".$hidmsgid."'");

                            $GLOBALS['$logger']->debug('update status for support_group_emails of matched data'.$query);

                        foreach ($str as $mail => $mailid) {

                             $query1=$GLOBALS['$dbFramework']->query("select * from contact_details where json_extract(contact_email, '$.*') like '%$mailid%' and contact_id='".$contact_id."'");
                             
                            if($query1->num_rows()==0){
                                $GLOBALS['$logger']->debug('if contact_email column json is blank it retuns zero then empty that column and insert new email');

                                    $ab = '{"email": ["", ""]}';
                                  
                                    $query11 = $GLOBALS['$dbFramework']->query("SELECT contact_id,contact_email 
                                    from contact_details WHERE contact_email LIKE '".$ab."'AND contact_id = '$contact_id'");                            
                                    //if contact_email column json is blank it retuns zero then empty that column and insert 
                                    if($query11->num_rows()==1){

                                        $contactEmpty = $GLOBALS['$dbFramework']->query("UPDATE contact_details set  contact_email = NULL
                                            where contact_id = '$contact_id'");
                                        $GLOBALS['$logger']->debug('empty the contact email column'.$contactEmpty);
                                        
                                        $val1 = '{"email": ["'.$mailid.'"]}';
                                        $query22 = $GLOBALS['$dbFramework']->query("UPDATE contact_details set  contact_email = '".$val1."'
                                            where contact_id = '$contact_id'");

                                    }else{
                                         $query33 = $GLOBALS['$dbFramework']->query("UPDATE contact_details SET contact_email=json_array_append(contact_email,'$.email','".$mailid."')
                                            WHERE contact_id='".$contact_id."'");
                                           $GLOBALS['$logger']->debug('contact details table email-id appended'.$query);
                                    }
                            }                           

                        }

                        $data = array('notificationID'=>$dt.uniqid(),
                                'notificationShortText'=>'New Email Sent',
                                'notificationText'=>'New Email Sent to '.$contact_name,
                                'from_user'=>$repid,
                                'action_details'=>'email',
                                'notificationTimestamp'=>$dt,
                                'to_user'=>$repid
                        );  
                            $insert_notification = $GLOBALS['$dbFramework']->insert('notifications',$data);                       
                            return $insert_notification;
                            break;                            
                    }


        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

    public function fetchAllMail(){
        try{
            /* $query=$GLOBALS['$dbFramework']->query("select ud.user_name as User_Name,json_unquote(ud.emailId-> '$.work[0]') as First_Email_ID,json_unquote(ud.emailId-> '$.personal[0]') as Secound_Email_ID,
                                        li.lead_name,json_unquote(li.lead_email-> '$.email[0]') as Lead_Email_ID,
                                        ci.customer_name as Customer_Name, json_unquote(ci.customer_email->'$.email[0]') as Customer_Mail_ID,
                                        cd.contact_name as Contact_Name, json_unquote(cd.contact_email->'$.email[0]') as Contact_Mail_ID
                                        from user_details ud 
                                        left join lead_info li on li.lead_manager_owner=ud.user_id
                                        left join opportunity_details opp on opp.lead_cust_id=li.lead_id
                                        left join customer_info ci on ci.lead_id= opp.lead_cust_id
                                        left join contact_details cd on cd.lead_cust_id=li.lead_id
                                        order by ud.user_name"
                                        ); */
            $query=$GLOBALS['$dbFramework']->query("Call p_getAll_Mail_ID()");
            
            return $query->result();                            
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function get_logtype($lookup_value){
        try{
            $query=$GLOBALS['$dbFramework']->query("SELECT lookup_id from lookup 
                where lookup_name='activity'
                and lookup_value='$lookup_value'");
            return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

     public function insert_log($data1){
        try{
            $insert = $GLOBALS['$dbFramework']->insert('rep_log',$data1);
            return $insert;

        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function get_out_server($userid){
         $query = $GLOBALS['$dbFramework']->query("SELECT es.*,ues.email_id,ues.password from email_settings es, user_email_settings ues
                                                    where es.email_settings_id=ues.email_settings_id
                                                    and es.email_settings_type in('personalsetting','emailsetting')
                                                    and ues.user_id='$userid'");
        return $query->result();        
    }

    public function insert_sent_mail($arr){
        try{  
            
            $insert = $GLOBALS['$dbFramework']->insert('support_group_emails',$arr);
            return $insert;

        } catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }   
    }
    
    public function insert_sent_attachments($data){
        try{  
            $insert = $GLOBALS['$dbFramework']->insert_batch('support_group_email_attachments',$data);
            return $insert;
            
        } catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }   
    }

    public function syncronise_sent_mails($rep_id){
        try{
            $query = $GLOBALS['$dbFramework']->query("select message_id, type, user_type, mail_from, mail_to,mail_to_address, mail_date, mail_subject, mail_body, mail_cc, mail_bcc from support_group_emails where mail_associated_state=9 and user_type='personalmail' and user_id='$rep_id' order by mail_date DESC");
            $k=0;
            if($query->num_rows()>0){                   
                    $arr=$query->result_array();                    

                    for($i=0;$i<count($arr);$i++){
                        $message_id=$arr[$i]['message_id'];
                        $a[$k]['message_id'] = $arr[$i]['message_id'];
                        $a[$k]['associate'] =$arr[$i]['type'];
                        $a[$k]['mail_from'] =$arr[$i]['mail_from'];                    
                        $a[$k]['mail_to'] =$arr[$i]['mail_to'];
                        $a[$k]['mail_to_address'] =$arr[$i]['mail_to_address'];
                        $a[$k]['mail_date'] =date('d-m-Y H:i:s',strtotime($arr[$i]['mail_date']));
                        $a[$k]['mail_subject'] =$arr[$i]['mail_subject'];
                        $a[$k]['mail_body'] =$arr[$i]['mail_body'];
                        $a[$k]['mail_cc'] =$arr[$i]['mail_cc'];
                        $a[$k]['mail_bcc'] =$arr[$i]['mail_bcc']; 

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
                    return $a;
            }else{
                return $a[]=[];
            } 
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    } 
    public function change_mail_read_state($msg_id){
        $query=$GLOBALS['$dbFramework']->query("update support_group_emails set mail_read_state='1'
            where message_id='$msg_id'");
        return $query;
    }   

    public function getUserEmailDetails(){
        $query=$GLOBALS['$dbFramework']->query("SELECT user_id, name, email_id, password from user_email_settings
                                                where settings_key = 'personalsetting'");
        return $query->result();
    }

    public function update_leadinprogess($leadinprogress){
         $lid=implode("','", $leadinprogress);
         $query=$GLOBALS['$dbFramework']->query("UPDATE lead_info set lead_status = 1 
                                                where lead_closed_reason is null
                                                and lead_id in('$lid')");
        return $query;
    }

}
?>

