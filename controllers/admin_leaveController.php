<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class admin_leaveController extends Master_Controller{
    public function  __construct(){
        parent::__construct();
        $this->load->model('admin_leaveModel','leaves');
    }
    public function index(){
        $this->data['leave_category'] = $this->leaves->view_leave();
        $this->load->view('admin_list_leaves_categoey_view',$this->data,FALSE);
    }
    public function check_category(){
        $leavecatname=$this->input->post('category');
        $chkleave=$this->leaves->check_category($leavecatname);
        echo  $chkleave;
    }
    public function post_data(){
        $dt=date('ymdHis');
        $leavecatname=$this->input->post('categoryname');
        $leavecatid=strtoupper(substr($leavecatname,0,2));
        $leavecatid.=$dt;
        $data=array(
            'leave_category_id'=>$leavecatid,
            'leave_category_name'=>$leavecatname,

        );
        $insert=$this->leaves->insert_data($data);
        if($insert==TRUE)
        {
            redirect('admin_leaveController/index');
        }

    }
    public function edit_data(){
        $leavecatid=$this->input->post('categoryid');
        $getdata=$this->leaves->edit_data($leavecatid);
        echo json_encode($getdata);

    }
    public function update_data(){
        $leavecatid=$this->input->post('categoryID');
        $leavecatname=$this->input->post('edit_categoryname');
        $data=array(
           'leave_category_name'=>$leavecatname
        );
        $updatequery=$this->leaves->update_data($leavecatid,$data);
        if($updatequery==TRUE)
        {
            redirect('admin_leaveController/index');
        }

    }

}
?>
