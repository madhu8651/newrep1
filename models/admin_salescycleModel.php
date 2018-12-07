<?php
class admin_salescycleModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
    public function view_data(){
        $query=$this->db->query("select a.id,a.CYCLE_ID,a.CYCLE_NAME,a.CYCLE_DEPARTMENT,a.CYCLE_TEAM,a.CYCLE_PRODUCT,a.CYCLE_INDUSTRY,a.CYCLE_LOCATION,
                                    a.CYCLE_TOGGLEBIT,(select Department_name from department where  a.CYCLE_DEPARTMENT=Department_id)as department,
                                    (select teamname from teams where a.CYCLE_TEAM=teamid)as teamname,(select product_name from product_master where a.CYCLE_PRODUCT=product_id)as productname,
                                    (select lookup_value from lookup where lookup_name='industry' and a.CYCLE_INDUSTRY=lookup_id)as industry,
                                    (select lookup_value from lookup where lookup_id=a.CYCLE_LOCATION and lookup_key like 'loc%' )as locationname
                                from sales_cycle a");

        return $query->result();
    }
    public function dept_data(){
        $query=$this->db->query("select * from department order by id");
        return $query->result();
    }
    public function team_data(){
        $query=$this->db->query("SELECT * FROM teams");
        return $query->result();
    }
     public function industry(){
        $query=$this->db->query("select * from lookup where lookup_name='industry'");
        return $query->result();
    }
     public function location(){
        $query=$this->db->query("select lookup_id as locationid,lookup_value as locationname from lookup where lookup_key like 'loc%'");
        return $query->result();
    }
    public function product_data($teamid){
    $query=$this->db->query("select a.product_name,a.product_id from product_master a,team_product_mapping b
                                where a.product_id=b.product_id and b.team_id='$teamid'");

    return $query->result();
    }
     public function insert_data($data) {
        $query=$this->db->query("SELECT * FROM sales_cycle WHERE LCASE(CYCLE_NAME) = '".strtolower($data['CYCLE_NAME'])."'");
        if($query->num_rows()>0){
                return 0;
        }
        else{
                /* check the active state for already saved cycle have same parameters ------------ */
                $query1=$this->db->query("select CYCLE_TOGGLEBIT,CYCLE_NAME from sales_cycle where LCASE(CYCLE_DEPARTMENT) = '".strtolower($data['CYCLE_DEPARTMENT'])."'
                and LCASE(CYCLE_TEAM) = '".strtolower($data['CYCLE_TEAM'])."' and LCASE(CYCLE_PRODUCT) = '".strtolower($data['CYCLE_PRODUCT'])."'
                and LCASE(CYCLE_INDUSTRY) = '".strtolower($data['CYCLE_INDUSTRY'])."' and LCASE(CYCLE_LOCATION) = '".strtolower($data['CYCLE_LOCATION'])."' and CYCLE_TOGGLEBIT=1 ");
                if($query1->num_rows()>0){
                  foreach ($query1->result() as $row)
                  {
                         $togglebit=$row->CYCLE_TOGGLEBIT;
                         $CYCLE_NAME=$row->CYCLE_NAME;
                  }
                      /* if active send the cycle name to view--------------- */
                      if($togglebit == 1){
                            return $CYCLE_NAME;
                      }else{

                          $this->db->insert('sales_cycle', $data);
                          /* ----------------------------------------- insert into stage and stage mapping table ------------ */

                          $dt = date('ymdHis');
                          $stageName="Initial Discussion";
                          $stageID=strtoupper(substr($stageName,0,1));
                          $stageID.=$dt;
                          $stageid=uniqid($stageID);

                          $stageName1="Closed Stage";
                          $stageID1=strtoupper(substr($stageName1,0,1));
                          $stageID1.=$dt;
                          $stageid1=uniqid($stageID1);

                          $dt1="123";
                          $stage_map_id=uniqid($dt);
                          $dt1.=$dt;
                          $stage_map_id1=uniqid($dt1);

                          //$this->db->query("insert into sales_stage(stage_id,stage_name)values('$stageid','$stageName')");
                          //$this->db->query("insert into stage_cycle_mapping(stage_cycle_id,cycle_id,stage_id) values('$stage_map_id','".($data['CYCLE_ID'])."','$stageid')");

                          $this->db->query("insert into sales_stage(stage_id,stage_name,stage_sequence)values('$stageid1','$stageName1',100)");
                          $this->db->query("insert into stage_cycle_mapping(stage_cycle_id,cycle_id,stage_id) values('$stage_map_id1','".($data['CYCLE_ID'])."','$stageid1')");

                          /* ------------------------------------------------------------------------------------------------------------------ */

                          $CYCLE_NAME="nocycle";
                          return $CYCLE_NAME;
                      }

                }else{

                    $this->db->insert('sales_cycle', $data);

                     /* ----------------------------------------- insert into stage and stage mapping table ------------ */

                          $dt = date('ymdHis');
                          $stageName="Initial Discussion";
                          $stageID=strtoupper(substr($stageName,0,1));
                          $stageID.=$dt;
                          $stageid=uniqid($stageID);

                          $stageName1="Closed Stage";
                          $stageID1=strtoupper(substr($stageName1,0,1));
                          $stageID1.=$dt;
                          $stageid1=uniqid($stageID1);

                          $dt1="r";
                          $stage_map_id=uniqid($dt);
                          $dt1.=$dt;
                          $stage_map_id1=uniqid($dt1);

                          //$this->db->query("insert into sales_stage(stage_id,stage_name,stage_sequence)values('$stageid','$stageName',5)");
                          //$this->db->query("insert into stage_cycle_mapping(stage_cycle_id,cycle_id,stage_id) values('$stage_map_id','".($data['CYCLE_ID'])."','$stageid')");

                          $this->db->query("insert into sales_stage(stage_id,stage_name,stage_sequence)values('$stageid1','$stageName1',100)");
                          $this->db->query("insert into stage_cycle_mapping(stage_cycle_id,cycle_id,stage_id) values('$stage_map_id1','".($data['CYCLE_ID'])."','$stageid1')");

                     /* ------------------------------------------------------------------------------------------------------------------ */

                    $CYCLE_NAME="nocycle";
                    return $CYCLE_NAME;

                }

        }
    }
     public function update_data($cycleID,$data) {

                $query=$this->db->query("SELECT * FROM sales_cycle WHERE LCASE(CYCLE_NAME) = '".strtolower($data['CYCLE_NAME'])."'");
                if($query->num_rows()>0){
                        return 0;
                }
                else{
                        /* check the active state for already saved cycle have same parameters ------------ */
                        $query1=$this->db->query("select CYCLE_TOGGLEBIT,CYCLE_NAME from sales_cycle where LCASE(CYCLE_DEPARTMENT) = '".strtolower($data['CYCLE_DEPARTMENT'])."'
                        and LCASE(CYCLE_TEAM) = '".strtolower($data['CYCLE_TEAM'])."' and LCASE(CYCLE_PRODUCT) = '".strtolower($data['CYCLE_PRODUCT'])."'
                        and LCASE(CYCLE_INDUSTRY) = '".strtolower($data['CYCLE_INDUSTRY'])."' and LCASE(CYCLE_LOCATION) = '".strtolower($data['CYCLE_LOCATION'])."' and CYCLE_TOGGLEBIT=1 ");
                        if($query1->num_rows()>0){
                          foreach ($query1->result() as $row)
                          {
                                 $togglebit=$row->CYCLE_TOGGLEBIT;
                                 $CYCLE_NAME=$row->CYCLE_NAME;
                          }
                              /* if active send the cycle name to view--------------- */
                              if($togglebit == 1){
                                    return $CYCLE_NAME;
                              }else{

                                  $this->db->where('CYCLE_ID', $cycleID);
                                  $this->db->update('sales_cycle', $data);
                                  $CYCLE_NAME="nocycle";
                                  return $CYCLE_NAME;
                              }

                        }else{

                            $this->db->where('CYCLE_ID', $cycleID);
                            $this->db->update('sales_cycle', $data);
                            $CYCLE_NAME="nocycle";
                            return $CYCLE_NAME;

                        }

                }

     }
     public function update_tg_bit($data1){

            /* check the active state for already saved cycle have same parameters ------------ */
                $query1=$this->db->query("select CYCLE_TOGGLEBIT,CYCLE_NAME from sales_cycle where LCASE(CYCLE_DEPARTMENT) = '".strtolower($data1['CYCLE_DEPARTMENT'])."'
                and LCASE(CYCLE_TEAM) = '".strtolower($data1['CYCLE_TEAM'])."' and LCASE(CYCLE_PRODUCT) = '".strtolower($data1['CYCLE_PRODUCT'])."'
                and LCASE(CYCLE_INDUSTRY) = '".strtolower($data1['CYCLE_INDUSTRY'])."' and LCASE(CYCLE_LOCATION) = '".strtolower($data1['CYCLE_LOCATION'])."' and CYCLE_TOGGLEBIT=1 ");
                if($query1->num_rows()>0){
                  foreach ($query1->result() as $row)
                  {
                         $togglebit=$row->CYCLE_TOGGLEBIT;
                         $CYCLE_NAME=$row->CYCLE_NAME;
                  }
                      /* if active send the cycle name to view--------------- */
                      if($togglebit == 1){
                            return $CYCLE_NAME;
                      }else{

                          $this->db->query("update sales_cycle set CYCLE_TOGGLEBIT='".($data1['CYCLE_TOGGLEBIT'])."' where CYCLE_ID='".($data1['CYCLE_ID'])."'");
                          $CYCLE_NAME="nocycle";
                          return $CYCLE_NAME;
                      }

                }else{

                            $this->db->query("update sales_cycle set CYCLE_TOGGLEBIT='".($data1['CYCLE_TOGGLEBIT'])."' where CYCLE_ID='".($data1['CYCLE_ID'])."'");
                            $CYCLE_NAME="nocycle";
                            return $CYCLE_NAME;
                }

     }
     public function update_tg_bit1($cycleID,$toggleid){

            $query=$this->db->query("update sales_cycle set CYCLE_TOGGLEBIT='$toggleid' where CYCLE_ID='$cycleID'");
            return TRUE;

     }


}
?>

