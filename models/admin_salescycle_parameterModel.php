<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_salescycle_parameterModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_salescycle_parameterModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
public function view_data(){

    $a=array();
    try{
            $query=$this->db->query("select distinct a.parameter_id,
                                    a.parameter_product,(select distinct hvalue2 from hierarchy where hkey2=a.parameter_product) as productP,
                                    a.parameter_industry,(select distinct hvalue2 from hierarchy where hkey2=a.parameter_industry) as industryP,
                                    a.parameter_location,(select distinct hvalue2 from hierarchy where hkey2=a.parameter_location) as locationP,
                                    a.cycle_id,(select CYCLE_NAME from sales_cycle where CYCLE_ID=a.cycle_id) as cyclename,
                                    a.cycle_togglebit from sales_cycle_parameters a order by a.id ");


            if($query->num_rows()>0){

                    $arr=$query->result_array();
                    for($i=0;$i<count($arr);$i++){

                            $cycle_id=$arr[$i]['cycle_id'];

                            $a[$i]['parameter_id'] = $arr[$i]['parameter_id'];
                            $a[$i]['parameter_product'] = $arr[$i]['parameter_product'];
                            $a[$i]['productP'] = $arr[$i]['productP'];
                            $a[$i]['parameter_industry'] =$arr[$i]['parameter_industry'];
                            $a[$i]['industryP'] =$arr[$i]['industryP'];
                            $a[$i]['parameter_location'] =$arr[$i]['parameter_location'];
                            $a[$i]['locationP'] =$arr[$i]['locationP'];
                            $a[$i]['cycle_togglebit'] =$arr[$i]['cycle_togglebit'];
                            $a[$i]['cyclename'] =$arr[$i]['cyclename'];
                            $a[$i]['cycle_id'] =$arr[$i]['cycle_id'];


                            /* ------------------------------ products ---------------------------------------------------------- */
                            $query1 = $this->db->query("select distinct a.parameter_product_node,
                                                        (select distinct hvalue2 from hierarchy where hkey2=a.parameter_product_node) as product
                                                            from sales_cycle_parameters a where cycle_id='".$cycle_id."';");
                             if($query1->num_rows()>0){

                                    $arr1=$query1->result_array();
                                    for($j=0;$j<count($arr1);$j++){

                                            $a[$i]['pro_data'][$j]['parameter_product_node']=$arr1[$j]['parameter_product_node'];
                                            $a[$i]['pro_data'][$j]['product']=$arr1[$j]['product'];
                                    }
                             }

                             /* ------------------------------------------------------ industry ---------------------------------- */

                             $query1 = $this->db->query("select distinct a.parameter_industry_node,
                                                        (select distinct hvalue2 from hierarchy where hkey2=a.parameter_industry_node) as industry
                                                            from sales_cycle_parameters a where cycle_id='".$cycle_id."';");
                             if($query1->num_rows()>0){

                                    $arr1=$query1->result_array();
                                    for($j=0;$j<count($arr1);$j++){

                                            $a[$i]['ind_data'][$j]['parameter_industry_node']=$arr1[$j]['parameter_industry_node'];
                                            $a[$i]['ind_data'][$j]['industry']=$arr1[$j]['industry'];
                                    }
                             }

                             /* ----------------------------------------------- location ---------------------------------------------- */

                             $query1 = $this->db->query("select distinct a.parameter_location_node,
                                                        (select distinct hvalue2 from hierarchy where hkey2=a.parameter_location_node) as location
                                                            from sales_cycle_parameters a where cycle_id='".$cycle_id."';");
                             if($query1->num_rows()>0){

                                    $arr1=$query1->result_array();
                                    for($j=0;$j<count($arr1);$j++){

                                            $a[$i]['loc_data'][$j]['parameter_location_node']=$arr1[$j]['parameter_location_node'];
                                            $a[$i]['loc_data'][$j]['location']=$arr1[$j]['location'];
                                    }
                             }

                             $query1 = $this->db->query("select distinct a.parameter_for
                                                            from sales_cycle_parameters a where cycle_id='".$cycle_id."';");
                             if($query1->num_rows()>0){

                                    $arr1=$query1->result_array();
                                    for($j=0;$j<count($arr1);$j++){

                                            $a[$i]['param_data'][$j]['parameter_for']=$arr1[$j]['parameter_for'];

                                    }
                             }

                     }
            }
            return $a;

    }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
    }

}
public function dept_data(){
    try{
            $query=$GLOBALS['$dbFramework']->query("select * from department a where a.Department_id in (select distinct department_id from teams where
                                    a.Department_id=department_id)  order by id");
            return $query->result();
    }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
    }
}
public function team_data($deptid){
    try{
            $query=$GLOBALS['$dbFramework']->query("SELECT * FROM teams where department_id='$deptid'");
            return $query->result();
    }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
    }
}
public function view_productdata(){
    try{
            $query = $GLOBALS['$dbFramework']->query("call get_hierarchy_details('products','onload','','');");
            return $query->result();
    }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
    }
}
public function view_industrydata(){
    try{
            $query = $GLOBALS['$dbFramework']->query("call get_hierarchy_details('industry','onload','','');");
            return $query->result();
    }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
    }
}
public function view_locationdata(){
    try{
            $query = $GLOBALS['$dbFramework']->query("call get_hierarchy_details('business_location','onload','','');");
            return $query->result();
    }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
    }
}
public function product_data($teamid){
    try{
                $query=$GLOBALS['$dbFramework']->query("select distinct a.product_id,
                                (select distinct hvalue2 from hierarchy where hkey2=a.product_id) as productname
                                from product_currency_mapping a where a.team_id='$teamid'
                                and a.togglebit=1 and a.remarks='product'; ");
                return $query->result();
    }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
    }
}
public function cycle_data(){
    try{
            $query=$GLOBALS['$dbFramework']->query("select * from sales_cycle where togglebit=1 order by id ");
            return $query->result();
    }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
    }
}
public function insert_data($data) {
    try{
            $var = $GLOBALS['$dbFramework']->insert_batch('sales_cycle_parameters',$data);
            return $var;
    }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
    }
}
public function industry_edit($paramid){
            $json=array();
            $b=array();
            $c=array();

    try{

            $query = $GLOBALS['$dbFramework']->query("call edit_tree('industry','parameter_page','$paramid','Editindus');");
            if($query->num_rows()>0){
                $json=$query->result();

            }
            return $json;
    }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
    }
}
public function business_edit($paramid){
            $json=array();
            $b=array();
            $c=array();
    try{

            $query = $GLOBALS['$dbFramework']->query("call edit_tree('business_location','parameter_page','$paramid','Editloc');");
            if($query->num_rows()>0){
                $json=$query->result();

            }
            return $json;
    }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
    }
}
public function product_edit($paramid){
            $json=array();
            $b=array();
            $c=array();
    try{

            $query = $GLOBALS['$dbFramework']->query("call edit_tree('products','parameter_page','$paramid','Editprod');");
            if($query->num_rows()>0){
                $json=$query->result();

            }
            return $json;
    }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
    }
}

public function update_data($maindata1,$hid_parameterid) {
    try{
            $delque=$GLOBALS['$dbFramework']->query("delete from sales_cycle_parameters where parameter_id='$hid_parameterid'");
            $var = $GLOBALS['$dbFramework']->insert_batch('sales_cycle_parameters',$maindata1);
            return $var;
    }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
    }
}

public function delDuplicate($listCombId) {
    try{

                $var = $GLOBALS['$dbFramework']->query("call SP_SPLIT('$listCombId',':','admin',0);");
                return $var;


    }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
    }
}

public function checkDuplicate($listCombId,$status,$hid_parameterid){

    try{
          if($status=="add"){

                $output=$GLOBALS['$dbFramework']->query("call SP_SPLIT('$listCombId',':','admin',1);");
                  $arr=$output->num_rows();
                  if($arr > 0){
                      return $output->result();
                  }else{
                      return $arr;
                  }


          }else if($status =="update"){
                  $output=$GLOBALS['$dbFramework']->query("call UPDATE_SP_SPLIT('$listCombId','$hid_parameterid',':','admin',1);");
                  $arr=$output->num_rows();
                  if($arr > 0){
                      return $output->result();
                  }else{
                       return $arr;
                  }
          }
    }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
    }
}

}
?>



























