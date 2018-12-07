 public function phoneUpdateReminder($value='') {

        $result_set = array(); 
        $user_id=$_POST['rep_id'];  
        $lead_reminder_id = $_POST['lead_reminder_id'];
        $camp_note=$_POST['note'];
        $rating=$_POST['rating'];
        $leademployeeid=$_POST['leademployeeid'];
        $logtype=$_POST['logtype'];
        $cmp_lead=$_POST['leadid'];
        $cmp_phone=$_POST['phone'];  
        $cmp_end_time=$_POST['duration'];
        $personId =$user_id;
        $event_title=$_POST['title'];
        $type=$_POST['type'];
        $status="complete";
        $status1="scheduled";
        $status2="reschedule";

        $completed_start_date=$_POST['newstartdate'];
        $completed_start_time=$_POST['newstarttime'];

        $start_date_time = $_POST['startdate']." ".$_POST['starttime'];
        $start_date_time = date('Y-m-d H:i:s',strtotime($start_date_time));
        $new_start_date_time = $completed_start_date."".$completed_start_time;
        $new_start_date_time = date('Y-m-d H:i:s',strtotime($new_start_date_time));

        $s = new DateTime("1970-01-01 $cmp_end_time", new DateTimeZone('UTC'));
        $seconds = (int)$s->getTimestamp();

        $s1 = new DateTime("1970-01-01 $cmp_end_time", new DateTimeZone('UTC'));
        $seconds1 = (int)$s1->getTimestamp();
                    //add seconds to date object 1 and make it date object 2
        $start = new DateTime($start_date_time);
        $start1 = new DateTime($new_start_date_time);
        $end_date_time = $start->add(new DateInterval('PT'.$seconds.'S')); // adds 674165 secs
        $new_end_date_time = $start1->add(new DateInterval('PT'.$seconds1.'S'));
        $end_date_time = $end_date_time->format('Y-m-d h:i:s');
        $new_end_date_time = $new_end_date_time->format('Y-m-d h:i:s');

        $data = array('remarks' => $camp_note);
        $data1 = array(
        'remarks' => $camp_note,
        'status'=> $status,
        'duration'=>$cmp_end_time
        );

                    $newData1=array(
                        'remarks' => $camp_note,
                        'status'=> $status,
                        'duration'=>$cmp_end_time
                    );

                    $data3 = array(
                        'remarks' => $camp_note,
                        'status'=>$status1,
                        'duration'=>$cmp_end_time
                    );

                    $newData3 = array(
                        'remarks' => $camp_note,
                        'status'=>$status1,
                        'duration'=>$cmp_end_time
                    );

                    $dt = date('ymdHis');     
                    $lead_reminder_id_new = '';
                    $lead_reminder_id_new .= $dt;
                    $lead_reminder_id_new = uniqid($lead_reminder_id_new);

                    $data2 = array(
                    'log_name'=>$event_title,    
                    'note' => $camp_note,   
                    'log_method'=>'auto',            
                    'call_type'=>$status,
                    'reminderid'=>$lead_reminder_id,
                    'leademployeeid' => $leademployeeid,
                    'logtype' => $logtype,   
                    'leadid' => $cmp_lead, 
                    'phone'=>$cmp_phone,
                    'starttime'=>$start_date_time,
                    'endtime'=>$end_date_time,
                    'time'=>$timestamp,
                    'rep_id'=>$user_id,
                    'rating'=>$rating,
                    'type'=>$type
                    );

            $newData2 = array(
            'log_name'=>$event_title,    
            'note' => $camp_note,   
            'log_method'=>'auto',            
            'call_type'=>$status,
            'reminderid'=>$lead_reminder_id_new,
            'leademployeeid' => $leademployeeid,
            'logtype' => $logtype,   
            'leadid' => $cmp_lead, 
            'phone'=>$cmp_phone,
            'starttime'=>$new_start_date_time,
            'endtime'=>$new_end_date_time,
            'time'=>$timestamp,
            'rep_id'=>$user_id,
            'rating'=>$rating,
            'type'=>$type
            );   
            

        $newSchedule=array(
                                    'lead_reminder_id' => $lead_reminder_id_new,
                                    'lead_id'   => $cmp_lead,
                                    'rep_id'    => $user_id,
                                   'leadempid' => $leademployeeid,
                                    'remi_date' => $completed_start_date,
                                   'rem_time'  => $completed_start_time,
                                   'conntype'  => $logtype,
                                    'status'    =>  'complete',
                                   'meeting_start'    => $new_start_date_time,
                                   'meeting_end'      => $new_end_date_time,
                                 //  'addremtime'       => $reminder_time,          
                                    'timestamp'        => $timestamp,
                                    'remarks'          => $camp_note,
                                    'event_name'       => $event_title,
                                   'duration'         => $cmp_end_time,
                               //     'managerid' =>$reporting_to,
                                    'type'=>$type,
                                    'created_by'=>$user_id 
                                );  

         $updateArray=array(
                        'lead_reminder_id'=>$lead_reminder_id,
                        'status'=>'reschedule'
                    );
            
                    if($new_start_date_time!=$start_date_time){
                        
                    $updateOldEventReschedule = $this->mytask->updateOldEventReschedule($updateArray,$lead_reminder_id);
                    $insertRescheduledData = $this->mytask->insert_reminder($newSchedule); 
                            if($insertRescheduledData==1){
                            $insert = $this->mytask->insert_repcomplete($newData2);  
                          //  $update1=$this->mytask->insert_repinfo($user_id,$cmp_lead);
                            echo json_encode($insertRescheduledData);
                            }         
                            //   if the manager has got the sales module, he will perform the same from there
                            
                                
                    }
                    else{
                            if($rating==0)  {
                            $update = $this->mytask->update_reminder($data3,$lead_reminder_id);
                            echo json_encode($update);
                            }
                            else {

                            $update = $this->mytask->update_remindercomplete($data1,$lead_reminder_id,$user_id);
                            if($update==1){
                            $insert = $this->mytask->insert_repcomplete($data2);  
                         //   $update1=$this->mytask->insert_repinfo($user_id,$cmp_lead);
                            echo json_encode($update);
                            }         
                            //   if the manager has got the sales module, he will perform the same from there
                           
                            } 
                    }                               
  }

public function getContacts() {
      $result_set = array(); 
       $leadid=$_POST['lead_id'];
       $type = $_POST['type']; 
       $contactsData = $this->mytask->getContactsForPhone($leadid,$type);  

       if(count($contactsData)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $contactsData;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }
  }

public function phoneGetOpportunities(){
       $result_set = array(); 
       $user_id=$_POST['user_id'];     
      $opportunitiesData = $this->mytask->getOpportunitiesForPhone($user_id);
      
      if(count($opportunitiesData)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $opportunitiesData;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        } 
  }

 public function phoneGetCustomer() {
      $result_set = array(); 
      $user_id=$_POST['user_id'];       
      $customerData1 = $this->mytask->getCustomerForPhone($user_id);
     // $customerData2 = $this->mytask->getCustomerFromOpp($user_id);
     // $customerData = array_merge($customerData1,$customerData2);
      if(count($customerData1)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $customerData1;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        } 
 } 

 public function phoneGetLeads() {
      $result_set = array(); 
      $user_id=$_POST['user_id'];       
      $leadData = $this->mytask->getLeadsForPhone($user_id);
      if(count($leadData)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $leadData;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        } 
 }

  public function Phone_get_mytask()    {
      $result_set = array(); 
      $user_id=$_POST['user_id'];       
      $data = $this->mytask->fetch_mytask($user_id, '');
      $data1= $this->mytask->fetch_mytask1($user_id, '');
      $taskArray= array_merge($data, $data1);
      if(count($taskArray)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $taskArray;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }       
                        
 }

  public function Assigned_for() {
       $result_set = array();
        $lead_id=$_POST['lead_id'];
        $type = $_POST['type'];
        $assignedForName = $this->mytask->fetchAssignedForName($lead_id,$type);
        if(count($assignedForName)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $assignedForName;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }

 }

public function User_details(){
        $result_set = array();
        $repId=$_POST['user_id'];
        $userName = $this->mytask->fetch_userName($repId);
         if(count($userName)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $userName;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }

}

 public function Completed_tasklist(){
        $result_set = array();
        $user_id=$_POST['user_id']; 
        $data = $this->mytask->fetch_completetask($user_id, '');
        $data1= $this->mytask->fetch_mytaskCompleted1($user_id,'');
        $dataArray1=array_merge($data,$data1);
        $data3 = $this->mytask->fetch_mytaskCompletedReplog($user_id, '');
        $data4=array_merge($dataArray1,$data3);
        $data5=$this->mytask->fetch_mytaskCompletedReplogInternal($user_id,'');
        $dataArray = array_merge($data4,$data5);
    
         if(count($dataArray)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $dataArray;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }

}

 public function open_tasklist() {
        $result_set = array();
        $repId=$_POST['lead_userid'];
        print_r($repId);exit();
        
        $openData = $this->mytask->Phone_open_tasklist($repId);
         if(count($openData)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $openData;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }

}



public function insert_rep_log(){
    $result = array();   
    $rep_id=trim($_POST['rep_id']," ");    
    $leademployeeid=$_POST['leademployeeid'];     
    $leadid=$_POST['leadid'];
    $phone=$_POST['phone'];
    $logtype=$_POST['logtype'];
    $rating=$_POST['rating'];    
    $note=$_POST['note'];   
    $starttime=$_POST['starttime'];
    $type=$_POST['type'];  
    $log_name=$_POST['log_name'];
    $log_method=$_POST['log_method'];
    $time =$_POST['timestamp'];
    $event_start_date = date('Y-m-d', strtotime($starttime));
                    $event_start_time = date('H:i', strtotime($starttime));

                    //get seconds from cmp_duration
                    $seconds = new DateTime("1970-01-01 $data->cmp_duration", new DateTimeZone('UTC'));
                    $activity_duration = (int)$seconds->getTimestamp();
                    //add seconds to date object 1(start) and make it date object 2(end)
                    $start = new DateTime($event_start);
                    $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
                    $event_end = $event_end->format('Y-m-d H:i:s');

       $data1 = array(
                    'rep_id'    => $rep_id,
                    'leademployeeid' => $leademployeeid,
                    'leadid'    => $leadid,           
                    'phone'     => $phone,
                    'logtype'   => $logtype,            
                    'rating' => $rating,
                    'call_type'=>'complete',
                    'note'      => $note,
                    'time'      => $time,
                    'starttime' => $event_start_date,
                    'endtime'   => $event_end, 
                    'type' => $type,
                    'module_id'=>'sales',
                    'log_name'=>$log_name,
                    'log_method'=>$log_method
                );                              
    $insert = $this->mytask->insert_phone_replog($data1);        
    echo json_encode($insert);
    if($insert==TRUE){
            $success = true;
            $result['success'] = true;
             echo json_encode($result);
        }else{
           $success = false;
           $result['success'] = false;
           echo json_encode($result);
       }       
    }

    public function open_tasklist() {
        $result_set = array();
        $repId=$_POST['lead_userid'];
        print_r($repId);exit();
        
        $openData = $this->mytask->Phone_open_tasklist($repId);
         if(count($openData)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $openData;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }

    }

    public function Completed_tasklist(){
        $result_set = array();
        $user_id=$_POST['user_id']; 
        $data = $this->mytask->fetch_completetask($user_id, '');
        $data1= $this->mytask->fetch_mytaskCompleted1($user_id,'');
        $dataArray1=array_merge($data,$data1);
        $data3 = $this->mytask->fetch_mytaskCompletedReplog($user_id, '');
        $data4=array_merge($dataArray1,$data3);
        $data5=$this->mytask->fetch_mytaskCompletedReplogInternal($user_id,'');
        $dataArray = array_merge($data4,$data5);
    
         if(count($dataArray)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $dataArray;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }

    }

    public function User_details(){
        $result_set = array();
        $repId=$_POST['user_id'];
        $userName = $this->mytask->fetch_userName($repId);
         if(count($userName)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $userName;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }

    }

    public function Assigned_for() {
       $result_set = array();
        $lead_id=$_POST['lead_id'];
        $type = $_POST['type'];
        $assignedForName = $this->mytask->fetchAssignedForName($lead_id,$type);
        if(count($assignedForName)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $assignedForName;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }

    }


    public function Phone_get_mytask()    {
      $result_set = array(); 
      $user_id=$_POST['user_id'];       
      $data = $this->mytask->fetch_mytask($user_id, '');
      $data1= $this->mytask->fetch_mytask1($user_id, '');
      $taskArray= array_merge($data, $data1);
      if(count($taskArray)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $taskArray;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }       
                        
    }


    public function phoneGetLeads() {
      $result_set = array(); 
      $user_id=$_POST['user_id'];       
      $leadData = $this->mytask->getLeadsForPhone($user_id);
      if(count($leadData)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $leadData;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        } 
    }


    public function phoneGetCustomer() {
      $result_set = array(); 
      $user_id=$_POST['user_id'];       
      $customerData1 = $this->mytask->getCustomerForPhone($user_id);
     // $customerData2 = $this->mytask->getCustomerFromOpp($user_id);
     // $customerData = array_merge($customerData1,$customerData2);
      if(count($customerData1)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $customerData1;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        } 
    }

  public function phoneGetOpportunities(){
       $result_set = array(); 
       $user_id=$_POST['user_id'];     
      $opportunitiesData = $this->mytask->getOpportunitiesForPhone($user_id);
      
      if(count($opportunitiesData)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $opportunitiesData;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        } 
  }

  public function getContacts() {
      $result_set = array(); 
       $leadid=$_POST['lead_id'];
       $type = $_POST['type']; 
       $contactsData = $this->mytask->getContactsForPhone($leadid,$type);  

       if(count($contactsData)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $contactsData;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }
  }

   public function phoneUpdateReminder($value='') {

        $result_set = array(); 
        $user_id=$_POST['rep_id'];  
        $lead_reminder_id = $_POST['lead_reminder_id'];
        $camp_note=$_POST['note'];
        $rating=$_POST['rating'];
        $leademployeeid=$_POST['leademployeeid'];
        $logtype=$_POST['logtype'];
        $cmp_lead=$_POST['leadid'];
        $cmp_phone=$_POST['phone'];  
        $cmp_end_time=$_POST['duration'];
        $personId =$user_id;
        $event_title=$_POST['title'];
        $type=$_POST['type'];
        $status="complete";
        $status1="scheduled";
        $status2="reschedule";

        $completed_start_date=$_POST['newstartdate'];
        $completed_start_time=$_POST['newstarttime'];

        $start_date_time = $_POST['startdate']." ".$_POST['starttime'];
        $start_date_time = date('Y-m-d H:i:s',strtotime($start_date_time));
        $new_start_date_time = $completed_start_date."".$completed_start_time;
        $new_start_date_time = date('Y-m-d H:i:s',strtotime($new_start_date_time));

        $s = new DateTime("1970-01-01 $cmp_end_time", new DateTimeZone('UTC'));
        $seconds = (int)$s->getTimestamp();

        $s1 = new DateTime("1970-01-01 $cmp_end_time", new DateTimeZone('UTC'));
        $seconds1 = (int)$s1->getTimestamp();
                    //add seconds to date object 1 and make it date object 2
        $start = new DateTime($start_date_time);
        $start1 = new DateTime($new_start_date_time);
        $end_date_time = $start->add(new DateInterval('PT'.$seconds.'S')); // adds 674165 secs
        $new_end_date_time = $start1->add(new DateInterval('PT'.$seconds1.'S'));
        $end_date_time = $end_date_time->format('Y-m-d h:i:s');
        $new_end_date_time = $new_end_date_time->format('Y-m-d h:i:s');

        $data = array('remarks' => $camp_note);
        $data1 = array(
        'remarks' => $camp_note,
        'status'=> $status,
        'duration'=>$cmp_end_time
        );

                    $newData1=array(
                        'remarks' => $camp_note,
                        'status'=> $status,
                        'duration'=>$cmp_end_time
                    );

                    $data3 = array(
                        'remarks' => $camp_note,
                        'status'=>$status1,
                        'duration'=>$cmp_end_time
                    );

                    $newData3 = array(
                        'remarks' => $camp_note,
                        'status'=>$status1,
                        'duration'=>$cmp_end_time
                    );

                    $dt = date('ymdHis');     
                    $lead_reminder_id_new = '';
                    $lead_reminder_id_new .= $dt;
                    $lead_reminder_id_new = uniqid($lead_reminder_id_new);

                    $data2 = array(
                    'log_name'=>$event_title,    
                    'note' => $camp_note,   
                    'log_method'=>'auto',            
                    'call_type'=>$status,
                    'reminderid'=>$lead_reminder_id,
                    'leademployeeid' => $leademployeeid,
                    'logtype' => $logtype,   
                    'leadid' => $cmp_lead, 
                    'phone'=>$cmp_phone,
                    'starttime'=>$start_date_time,
                    'endtime'=>$end_date_time,
                    'time'=>$timestamp,
                    'rep_id'=>$user_id,
                    'rating'=>$rating,
                    'type'=>$type
                    );

            $newData2 = array(
            'log_name'=>$event_title,    
            'note' => $camp_note,   
            'log_method'=>'auto',            
            'call_type'=>$status,
            'reminderid'=>$lead_reminder_id_new,
            'leademployeeid' => $leademployeeid,
            'logtype' => $logtype,   
            'leadid' => $cmp_lead, 
            'phone'=>$cmp_phone,
            'starttime'=>$new_start_date_time,
            'endtime'=>$new_end_date_time,
            'time'=>$timestamp,
            'rep_id'=>$user_id,
            'rating'=>$rating,
            'type'=>$type
            );   
            

        $newSchedule=array(
                                    'lead_reminder_id' => $lead_reminder_id_new,
                                    'lead_id'   => $cmp_lead,
                                    'rep_id'    => $user_id,
                                   'leadempid' => $leademployeeid,
                                    'remi_date' => $completed_start_date,
                                   'rem_time'  => $completed_start_time,
                                   'conntype'  => $logtype,
                                    'status'    =>  'complete',
                                   'meeting_start'    => $new_start_date_time,
                                   'meeting_end'      => $new_end_date_time,
                                 //  'addremtime'       => $reminder_time,          
                                    'timestamp'        => $timestamp,
                                    'remarks'          => $camp_note,
                                    'event_name'       => $event_title,
                                   'duration'         => $cmp_end_time,
                               //     'managerid' =>$reporting_to,
                                    'type'=>$type,
                                    'created_by'=>$user_id 
                                );  

         $updateArray=array(
                        'lead_reminder_id'=>$lead_reminder_id,
                        'status'=>'reschedule'
                    );
            
                    if($new_start_date_time!=$start_date_time){
                        
                    $updateOldEventReschedule = $this->mytask->updateOldEventReschedule($updateArray,$lead_reminder_id);
                    $insertRescheduledData = $this->mytask->insert_reminder($newSchedule); 
                            if($insertRescheduledData==1){
                            $insert = $this->mytask->insert_repcomplete($newData2);  
                          //  $update1=$this->mytask->insert_repinfo($user_id,$cmp_lead);
                            echo json_encode($insertRescheduledData);
                            }         
                            //   if the manager has got the sales module, he will perform the same from there
                            
                                
                    }
                    else{
                            if($rating==0)  {
                            $update = $this->mytask->update_reminder($data3,$lead_reminder_id);
                            echo json_encode($update);
                            }
                            else {

                            $update = $this->mytask->update_remindercomplete($data1,$lead_reminder_id,$user_id);
                            if($update==1){
                            $insert = $this->mytask->insert_repcomplete($data2);  
                         //   $update1=$this->mytask->insert_repinfo($user_id,$cmp_lead);
                            echo json_encode($update);
                            }         
                            //   if the manager has got the sales module, he will perform the same from there
                           
                            } 
                    }                               
  }

}

public function insert_reminders() {
       
       $result = array();
       $dt = date('ymdHis');     
       $lead_reminder_id = '';
       $lead_reminder_id .= $dt;
       $lead_reminder_id = uniqid($lead_reminder_id);
       $lead_id =$_POST['lead_id'];
       $rep_id = $_POST['rep_id'];
       $managerid=$_POST['managerid'];
       $leadempid=$_POST['leadempid'];
       $remi_date=$_POST['remi_date'];
       $rem_time = $_POST['rem_time'];
       $conntype = $_POST['conntype'];
       $status = $_POST['status'];
       $meeting_start= $_POST['meeting_start'];
       $meeting_start_time = date('Y-m-d', strtotime($meeting_start));
       $meeting_start_time = date('H:i', strtotime($meeting_start));

        //get seconds from cmp_duration
        $seconds = new DateTime("1970-01-01 $duration", new DateTimeZone('UTC'));
        $activity_duration = (int)$seconds->getTimestamp();
        //add seconds to date object 1(start) and make it date object 2(end)
        $start = new DateTime($meeting_start);
        $event_end = $start->add(new DateInterval('PT'.$activity_duration.'S')); // adds 674165 secs
        $meeting_end = $event_end->format('Y-m-d H:i:s');
       $timestamp =$_POST['timestamp'];
       $remarks = $_POST['remarks'];
       $event_name=$_POST['event_name'];               
       $duration=$_POST['duration'];     
       $type=$_POST['type'];
       $addremtime=$_POST['addremtime'];
       $created_by=$_POST['rep_id'];


        $data1 = array( 
                        'lead_reminder_id'   => $lead_reminder_id,
                        'lead_id'            => $lead_id,
                        'rep_id'             => $rep_id,
                        'managerid'          =>$managerid,
                        'leadempid'          => $leadempid,
                        'remi_date'          => $remi_date,
                        'rem_time'           => $rem_time,
                        'conntype'           => $conntype,
                        'status'             => $status,
                        'meeting_start'      => $meeting_start,
                        'meeting_end'        => $meeting_end,
                        'addremtime'         => $addremtime,          
                        'timestamp'          => $timestamp,
                        'remarks'            => $remarks,
                        'event_name'         => $event_name,
                        'duration'           => $duration,
                        'type'               => $type,
                        'created_by'         =>$created_by,
                        'module_id'          =>'sales'
                );


    
            $insert = $this->mytask->Phone_insert_reminders($data1);

            if($insert==TRUE){
            $success = true;
            $result['success'] = true;
             echo json_encode($result);
            }else{
            $success = false;
            $result['success'] = false;
            echo json_encode($result);
            }       
          
}

 public function get_rep_log() {

        $repId= trim($_POST['lead_userid']," ");
        $result_set= array();
        $repLogData=$this->mytask->get_rep_log($repId);
        if(count($repLogData)>0){
          $success = true;
          $result_set['success'] = true;
          $result_set['data'] = $repLogData;
          echo json_encode( $result_set);
        } else {
          $success = false;
          $result_set['success'] = false;
          echo json_encode($result_set);
        }
    }
