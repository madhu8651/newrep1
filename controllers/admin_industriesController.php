<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class admin_industriesController extends Master_Controller{
    
    
     public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('admin_industriesModel','industry');
    }
    
     public function index(){
         if($this->session->userdata('uid')){
            $this->load->view('admin_industries_view');
        }else{
            redirect('indexController');
        }
    }
    
     public function get_industry(){
         if($this->session->userdata('uid')){
            $industry = $this->industry->view_data();
            echo json_encode($industry);
        }else{
            redirect('indexController');
        }
    }
    
    public function post_data() {
        if($this->session->userdata('uid')){
            $json = file_get_contents("php://input");
            $data = json_decode($json);
            $dt = date('ymdHis');
            $industryName = $data->industryName;
            $industryKey = $data->industry_count;
            $industryID=strtoupper(substr($industryName,0,2));
            $industryID.=$dt;
            $industryID = uniqid($industryID);
            $data = array(
               'lookup_id' => $industryID,
               'lookup_name' => 'industry',
               'lookup_key' => $industryKey,
               'lookup_value' => $industryName
            );
            $insert = $this->industry->insert_data($data,$industryName);
            if($insert==1){
               $industry= $this->industry->view_data();
               echo json_encode($industry);
            }
            else{
               $industry="false";  
               echo json_encode($industry);

            }   
        }else{
            redirect('indexController');
        }
        
    }
    
      public function update_data(){
          if($this->session->userdata('uid')){
            $json = file_get_contents("php://input");
            $data = json_decode($json);
            $industryName = $data->industryName;
            $industID = $data->industID;
            $industryID=$industID;
            $data = array(
             'lookup_value' => $industryName
            );
            $update = $this->industry->update_data($industryID,$data,$industryName);
            if($update==1){
             $industry = $this->industry->view_data();
             echo json_encode($industry);
            }
            else{
             $industry="false"; 
             echo json_encode($industry);

            }
        }else{
            redirect('indexController');
        }
    }
  
}

?>

