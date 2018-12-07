<?php
class admin_productModel extends CI_Model{
     public function __construct(){
        parent::__construct();
  }
  public function view_data(){
        $query=$this->db->query("select a.product_id,a.product_name,a.product_custom_id,group_concat(c.currency_id separator ',') as currency_id,
group_concat(c.currency_name separator ', ') as currency_name 
from product_master a,product_currency_mapping b ,currency c 
where a.product_id=b.product_id 
and b.currency_id=c.currency_id group by product_id");
        return $query->result();
  }
  public function currency(){
        $query=$this->db->query("select a.currency_id,a.currency_name
from currency a,currency_category b
where a.currency_category_id=b.currency_category_id
and b.currency_category_name='Products'");
        return $query->result();
  }
  
  public function insert_data($data) {
        $insert = $this->db->insert('product_master', $data); 
        return $insert;
    }
    public function currency_data($data) {
        $insert1 = $this->db->insert('product_currency_mapping', $data); 
        return $insert1;
    }
     public function table_data() {
        $query=$this->db->query("select a.product_id,a.product_name,a.product_custom_id,b.currency_id,group_concat(c.currency_name separator ', ') as currency_name from product_master a,product_currency_mapping b , currency c where a.product_id=b.product_id and b.currency_id=c.currency_id  group by product_id");
        return $query->result();
    }
    public function update_data($pid,$data) {
       $this->db->where('product_id', $pid);
       $update = $this->db->update('product_master', $data);
       return $update;
    }
    public function update_currency($cid,$data) {
       $this->db->where('product_id', $cid);
       $update1 = $this->db->update('product_currency_mapping', $data);
       return $update1;
    }
}
?>

