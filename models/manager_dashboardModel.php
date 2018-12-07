<?php
defined('BASEPATH') or exit('Cannot access');

class manager_dashboardModel extends CI_Model{
    public function __construct(){
        parent::__construct();
    }
    public function get_calls_completed($userid){
        $query = $this->db->query("CALL r_CallsCompleted_Report('$userid')");
        return $query->result();
    }
    public function get_sms_completed($userid){
        $query = $this->db->query("CALL r_SMSDashbord_Report('$userid')");
        return $query->result();
    }
    public function get_meetings_completed($userid){
        $query = $this->db->query("CALL r_Meetingcompleted_Report('$userid')");
        return $query->result();
    }
    public function get_emails_completed($userid){
        $query = $this->db->query("CALL r_EMailDashbord_Report('$userid')");
        return $query->result();
    }
}