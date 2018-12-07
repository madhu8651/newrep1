<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class notificationController extends CI_Controller{
    public function  __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('notificationModel','notifymodel');
    }
   /* public function index($userid=null){

        if($this->session->userdata('uid'))
          {
            if(isset($userid)){
                $_SESSION['active_module'] = $userid;
                if($_SESSION['active_module_name'] == "manager")
                {
                    $this->load->view('manager_mytaskView');
                }
                else if($_SESSION['active_module_name'] == "sales")
                {
                    $this->load->view('sales_mytask_view');
                }
                else
                {
                   $this->load->view('admin_dashboard_view');
                }
            }

        }else
        {
            redirect('indexController');
        }
    }*/

  /*  public function get_clientinfo(){
        if($this->session->userdata('uid')){
            $clientinfo = $this->clientinfo->view_data();
            $data=json_encode($clientinfo);
            echo $data;
        }else{
            redirect('indexController');
        }
    }*/
  /*  public function get_userlicense(){
        if($this->session->userdata('uid')){
            $managerinfo = $this->clientinfo->manage_licence();
            echo json_encode($managerinfo);
        }else{
            redirect('indexController');
        }
    }*/
    public function getnotifydata()
    {
         $data = $this->notifymodel->displaynotifydata();
         echo json_encode($data);
    }
    public function getcount()
    {
         $data = $this->notifymodel->displaycount();
         header("Content-Type: text/event-stream\n\n");
         header("Cache-Control: no-cache");
         echo "data: {$data}\n\n";
       // echo json_encode($data);

    }
    public function displaynotifyview()
    {
       $this->load->view('notificationView');
    }
    public function displaynotifydata($state)
    {
          //echo"hello";
          if($state=='read')
          {
             $data = $this->notifymodel->notifydata('',$state); //extracts the array format with the help of key (value is still in json format)
          }
          else{

              if(isset($_POST['ids']))
              $data = $this->notifymodel->notifydata(json_encode($_POST['ids']),$state); //extracts the array format with the help of key (value is still in json format)
              else
              $data = $this->notifymodel->notifydata('',$state);
          }

         echo json_encode($data);
    }


}
?>
