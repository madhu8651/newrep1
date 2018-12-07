<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class admin_salescycleController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_salescycleModel','cycle');
    }
    public function index(){
        if($this->session->userdata('uid')){
         $this->load->view('admin_salescycleView');  
        }else{
            redirect('indexController');
        }       
    }
    public function display_data(){
        if($this->session->userdata('uid')){
        $sales = $this->cycle->view_data();
        echo json_encode($sales);    
        }else{
            redirect('indexController');
        }
    }
    public function department(){
        if($this->session->userdata('uid')){
        $department = $this->cycle->dept_data();
        echo json_encode($department);  
        }else{
            redirect('indexController');
        }
    }
    public function teams(){
        if($this->session->userdata('uid')){
        $teams = $this->cycle->team_data();
        echo json_encode($teams);   
        }else{
            redirect('indexController');
        }
    }
     public function product_data(){
        if($this->session->userdata('uid')){
        $teamid = $this->input->post('id');
        $product = $this->cycle->product_data($teamid);
        echo json_encode($product); 
        }else{
            redirect('indexController');
        }
    }
    public function industry(){
        if($this->session->userdata('uid')){
        $teams = $this->cycle->industry();
        echo json_encode($teams);   
        }else{
            redirect('indexController');
        }
    }
    public function locations(){
        if($this->session->userdata('uid')){
        $location = $this->cycle->location();
        echo json_encode($location);  
        }else{
            redirect('indexController');
        }
    }
     public function post_data(){
        if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        ///print_r( $data);exit;
        $dt = date('ymdHis');
        $cycleid= uniqid($dt);
        $departmentName = $data->deprtmt;
        $team = $data->teams;
        $product = $data->product;
        $salescycle = $data->salescyle;
        $location = $data->location;
        $industrycycle = $data->industryname;
        $toggleid=1;
        $data = array(
            'CYCLE_ID' => $cycleid,
            'CYCLE_NAME' => $salescycle,
            'CYCLE_DEPARTMENT' => $departmentName,
            'CYCLE_TEAM' => $team,
            'CYCLE_PRODUCT' => $product,
            'CYCLE_INDUSTRY' => $industrycycle,
            'CYCLE_LOCATION' => $location,
            'CYCLE_TOGGLEBIT' => $toggleid
        );
        $insert = $this->cycle->insert_data($data);
        echo json_encode($insert); 
        }else{
            redirect('indexController');
        }
    }

    public function update_data(){
        if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $departmentName = $data->deprtmt;
        $team = $data->teams;
        $product = $data->product;
        $salescycle = $data->salescyle;
        $location = $data->location;
        $industrycycle = $data->industryname;
        $cycleID=$data->cycleid;
        $toggleid=$data->toggleid;
        $data = array(
                'CYCLE_NAME' => $salescycle,
                'CYCLE_DEPARTMENT' => $departmentName,
                'CYCLE_TEAM' => $team,
                'CYCLE_PRODUCT' => $product,
                'CYCLE_INDUSTRY' => $industrycycle,
                'CYCLE_LOCATION' => $location,
                'CYCLE_TOGGLEBIT' => $toggleid
         );
        $update = $this->cycle->update_data($cycleID,$data);
        echo json_encode($update);  
        }else{
            redirect('indexController');
        }

    }
    public function update_tg_data(){
        if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $departmentName = $data->deprtmt;
        $team = $data->teams;
        $product = $data->product;
        $location = $data->location;
        $industrycycle = $data->industryname;
        $cycleID=$data->cycleid;
        $toggleid=$data->toggleid;
        $data1 = array(
               'CYCLE_ID' => $cycleID,
               'CYCLE_DEPARTMENT' => $departmentName,
               'CYCLE_TEAM' => $team,
               'CYCLE_PRODUCT' => $product,
               'CYCLE_INDUSTRY' => $industrycycle,
               'CYCLE_LOCATION' => $location,
               'CYCLE_TOGGLEBIT' => $toggleid
        );
        $update_tgbit = $this->cycle->update_tg_bit($data1);
        echo json_encode($update_tgbit);  
        }else{
            redirect('indexController');
        }
    }

    public function update_tg_data1(){
        if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $cycleID=$data->cycleid;
        $toggleid=$data->toggleid;
        $update_tgbit = $this->cycle->update_tg_bit1($cycleID,$toggleid);
        echo json_encode($update_tgbit);  
        }else{
            redirect('indexController');
        } 
    }




}
?>

