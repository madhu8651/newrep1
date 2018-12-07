<?php
class admin_locationModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    } 
   
    public function view_region(){
        try{
                $query=$this->db->query("SELECT * FROM lookup WHERE lookup_name='region' ORDER BY lookup_name");
                return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function view_location(){
        try{
                $query=$this->db->query("SELECT a.lookup_id as regionid,a.lookup_value as regionname,b.lookup_id as locationid,b.lookup_value as locationname
                                        FROM lookup a,lookup b
                                        WHERE a.lookup_id = b.lookup_name
                                        AND a.lookup_name = 'region'
                                        ORDER BY b.lookup_value");
                return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function insert_data($data, $regionid,$locationName) {
        try{
              $query=$this->db->query("SELECT lookup_id FROM `lookup` WHERE lookup_name='$regionid' and lookup_value='$locationName'");
              if($query->num_rows()>0)
              {
                  return false;
              }
              else
              {
                  $insert = $this->db->insert('lookup', $data);
                  return $insert;
              }
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function update_data($data, $locationID, $regionid,$locationName) {
        try{
              $query=$this->db->query("SELECT lookup_id FROM `lookup` WHERE lookup_name='$regionid' and lookup_value='$locationName'");
              if($query->num_rows()>0)
              {
                  return false;
              }
              else
              {
                  $this->db->where('lookup_id', $locationID);
                  $update = $this->db->update('lookup', $data);
                  return $update;
              }
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
}
?>

