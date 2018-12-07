<?php

class admin_industriesModel extends CI_Model{
    
    public function __construct(){
        parent::__construct();
    }
    public function view_data(){
        $query=$this->db->query("SELECT * FROM lookup WHERE lookup_name='industry'");
        return $query->result();
    }
    public function insert_data($data,$industryName) {
        
         $query=$this->db->query("SELECT * FROM lookup WHERE lookup_name='industry' and lookup_value='$industryName'");
         if($query->num_rows()>0)
            {
                return false;
            }
            else
            {
                $insert = $this->db->insert('lookup', $data); 
                return $insert;
            }
   
        
    }
    public function update_data($industryID,$data,$industryName) {
        
        $query=$this->db->query("SELECT * FROM lookup WHERE lookup_name='industry' and lookup_value='$industryName'");
        if($query->num_rows()>0)
        {
            return false;
        }
        else{
            $this->db->where('lookup_id', $industryID);
           $update = $this->db->update('lookup', $data);
           return $update;
            
        }
            
           
 
      

       
    }
    
    
    
    
}


?>

