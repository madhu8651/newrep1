<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class admin_leaveModel extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    public function view_leave(){
        $query = $this->db->query("select * from leave_category order by leave_category_id");
        return $query->result_array();
    }
    public function insert_data($data){
        $query=$this->db->insert('leave_category',$data);
        return true;
    }
    public function check_category($leavecatname){
        $query=$this->db->query("select *  from leave_category where trim(lower(leave_category_name))=trim(lower('$leavecatname'))");
        return $query->num_rows();

    }
    public function edit_data($leavecatid){
        $query=$this->db->get_where('leave_category',array('leave_category_id' => $leavecatid));
        if($query->num_rows()>0){
            return $query->result_array();
        }
        else{
            return false;
        }
    }
    public function update_data($leavecatid,$data){
        $this->db->where('leave_category_id',$leavecatid);
        $this->db->update('leave_category',$data);
        return true;
    }


}


?>