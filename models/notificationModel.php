<?php
class notificationModel extends CI_Model{
     public function __construct(){
        parent::__construct();
         $this->load->library('session');

    }
    public function displaynotifydata()
    {
       $userid=$this->session->userdata('uid');
       //echo"SELECT id as notify_id,notificationShortText FROM notifications where read_state=0 and to_user='$userid' order by id desc";
       $query = $this->db->query("SELECT id as notify_id,notificationShortText,DATE_FORMAT(notificationTimestamp,\"%d-%m-%Y %H:%i:%s\") AS notificationTimestamp
                FROM notifications where read_state=0  and to_user='$userid' order by id desc");
       return $query->result();
    }
    public function displaycount()
    {
         $userid=$this->session->userdata('uid');
         //echo"SELECT notificationShortText FROM notifications where read_state=0 and to_user='$userid' order by id desc";
         $query = $this->db->query("SELECT notificationShortText FROM notifications where read_state=0 and to_user='$userid' order by id desc");
         return $query->num_rows();
    }
    public function notifydata($idarray, $state)
    {
        $userid=$this->session->userdata('uid');
        /*$query = $this->db->query("SELECT id,notificationShortText,notificationText,(select user_name from user_details where user_id=from_user) as username,
                                       DATE_FORMAT(notificationTimestamp,\"%d-%m-%Y %H:%i:%s\") AS notifydate  FROM notifications where
                                       to_user='$userid' and show_status=0  order by id desc");*/
        $query = $this->db->query("SELECT id,notificationShortText,notificationText,
                                   CASE
                                        WHEN (SELECT COUNT(*) FROM user_details WHERE user_id=from_user)>0
                                             THEN (SELECT user_name FROM user_details WHERE user_id=from_user)
                                        ELSE (SELECT hvalue2 FROM hierarchy WHERE hkey2=from_user)
                                   END as username,
                                   DATE_FORMAT(notificationTimestamp,\"%d-%m-%Y %H:%i:%s\") AS notifydate  FROM notifications where
                                   to_user='$userid' and show_status=0  order by id desc");
        if($state == 'unread' && $idarray!=''){

            $array_id=json_decode($idarray); // converts an object to array form
            $string_id = implode(',',$array_id); //converts an array into string format

            $query1=$this->db->query("UPDATE notifications SET read_state=1 where to_user='$userid' and id in (".$string_id.")");
            $query1=$this->db->query("UPDATE notifications SET show_status=1 where to_user='$userid' and id in (".$string_id.") and
                           (action_details='lead' or  action_details='opportunity' or action_details='customer') and (action='reject' or action='accept')");
        }
        return $query->result();

    }
}
?>