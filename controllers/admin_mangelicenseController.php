<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class admin_mangelicenseController extends Master_Controller{
    public function  __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_mangelicenseModel','licenseinfo');
    }
    public function index(){
        $this->load->view('admin_manage_license');
       
    }
    public function get_licenseinfo(){
        $licenseinfo = $this->licenseinfo->view_data();
        echo json_encode($licenseinfo);
         
       
    }
}
?>
