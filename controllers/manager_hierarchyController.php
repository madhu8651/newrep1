<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class manager_hierarchyController extends Master_Controller{
    public function __construct(){
        parent::__construct();
       $this->load->model('manager_hierarchyModel','mgr_rep');
    }
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('manager_hierarchyView');
        }else{
            redirect('indexController');
        }
    }
    public function get_lead_source(){
        if($this->session->userdata('uid')){

            //$userid=$this->session->userdata('uid'); /* id to be taken from session */
            $userid='170704111951595b79d73d732';
            $source = $this->mgr_rep->get_lead_source($userid);
            echo json_encode($source);
        }else{
             redirect('indexController');
        }
    }

}

?>