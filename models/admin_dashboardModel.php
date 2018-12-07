<?php
class admin_dashboardModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
    public function view_data(){
        $query=$this->db->query("SELECT * FROM client_info");
        return $query->result();
    }
    
    public function manage_licence(){
        $query=$this->db->query("SELECT client_name,module_purchased,JSON_UNQUOTE(module_used->'$.cxo') as cxo_module_used,
JSON_UNQUOTE(module_used->'$.manager') as manager_module_used,JSON_UNQUOTE(module_used->'$.sales') as sales_module_used,
JSON_UNQUOTE(plugin_purchased->'$.library') as library_purchased,JSON_UNQUOTE(plugin_purchased->'$.expenses') as expenses_purchased,
JSON_UNQUOTE(plugin_purchased->'$.inventory') as inventory_purchased,JSON_UNQUOTE(plugin_purchased->'$.navigator') as navigator_purchased,
JSON_UNQUOTE(plugin_purchased->'$.attendance') as attendance_purchased,JSON_UNQUOTE(plugin_purchased->'$.communicator') as communicator_purchased,
JSON_UNQUOTE(plugin_used->'$.library') as library_used,JSON_UNQUOTE(plugin_used->'$.expenses') as expenses_used,
JSON_UNQUOTE(plugin_used->'$.inventory') as inventory_used,JSON_UNQUOTE(plugin_used->'$.navigator') as navigator_used,
JSON_UNQUOTE(plugin_used->'$.attendance') as attendance_used,JSON_UNQUOTE(plugin_used->'$.communicator') as communicator_used,
licence_start_date AS start_date, licence_end_date AS end_date, versiontype
FROM client_info");
        return $query->result();
    }

    
}
?>

