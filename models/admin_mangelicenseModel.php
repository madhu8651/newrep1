<?php
class admin_mangelicenseModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
    public function view_data(){
        $query=$this->db->query("select a.user_name,b.user_id,b.manager_module,b.sales_module,c.role_name
from user_details a,user_licence b,user_roles c
where a.user_id=b.user_id
and a.designation=c.role_id");
        return $query->result();
    }   
}
?>

