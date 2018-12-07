<?php
class admin_regionModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
    public function view_data(){
       $query=$this->db->query("SELECT * FROM lookup WHERE lookup_name='region'");
        return $query->result();
    }
    public function insert_data($data, $regionName) {
        $query=$this->db->query("SELECT * FROM lookup WHERE lookup_name='region' and lookup_value='$regionName'");
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
    public function update_data($regionID,$data, $regionName) {
        $query=$this->db->query("SELECT * FROM lookup WHERE lookup_name='region' and lookup_value='$regionName'");
         if($query->num_rows()>0)
            {
                return false;
            }
            else
            {
               $this->db->where('lookup_id', $regionID);
               $update = $this->db->update('lookup', $data);
               return $update;
            }
    }
    public function getregion_data($regname){
           $this->db->select('*');
           $this->db->from('regions');
           $this->db->like('regionname',$regname);
           $query = $this->db->get();

           if($query->num_rows()>0){
             $records=$query->result();

              return array(
                  'records' => $records,
                  'count' => count($records)
                  );
          }
          else{
            return $query->num_rows();
          }
    }



}
?>

