<?php
defined('BASEPATH') or exit('Cannot access');
include 'Master_Controller.php';

class manager_dashboardController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('manager_dashboardModel','calls');
    }
    public function index(){
        if($this->session->userdata('uid')){ 
            $this->load->view('manager_dashboard_view');
        }else{
            redirect('indexController');
        }
    }
    public function get_dashboard_reports(){
        if($this->session->userdata('uid')){
            $userid = $this->session->userdata('uid');
            $result = array();
            $calls = $this->calls->get_calls_completed($userid);
            mysqli_next_result( $this->db->conn_id );
            $sms = $this->calls->get_sms_completed($userid);
            mysqli_next_result( $this->db->conn_id );
            $emails = $this->calls->get_emails_completed($userid);
            mysqli_next_result( $this->db->conn_id );
            $meetings = $this->calls->get_meetings_completed($userid);
            
            $result[0]['call'] = $calls;
    		$result[0]['title'] = 'Calls Completed';
    		$result[0]['id'] = '1';
    		$result[0]['chart_type'] = 'ColumnChart';
            $result[1]['call'] = $sms;
    		$result[1]['title'] = 'SMS Completed';
    		$result[1]['id'] = '2';
    		$result[1]['chart_type'] = 'BarChart';
            $result[2]['call'] = $emails;
    		$result[2]['title'] = 'Emails Completed';
    		$result[2]['id'] = '3';
    		$result[2]['chart_type'] = 'LineChart';
            $result[3]['call'] = $meetings;
    		$result[3]['title'] = 'Meetings Completed';
    		$result[3]['id'] = '4';
    		$result[3]['chart_type'] = 'ColumnChart';
            echo json_encode($result);
        }else{
            redirect('indexController');
        }
    }
}