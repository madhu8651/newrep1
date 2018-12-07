<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_product_hierarchyModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();



class admin_product_hierarchyModel extends CI_Model{
    public function __construct(){
        parent::__construct();
    }
    public function get_lead_source() {
        $query = $GLOBALS['$dbFramework']->query(" call get_hierarchy_details('products','onload','','');");
        return $query->result();
    }
    public function insert_hierarchy_class($param) {
               try{
                    $insert = $GLOBALS['$dbFramework']->insert('hierarchy_class',$param);
                    return true;
               }
               catch (LConnectApplicationException $e){
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                    throw $e;
               }

    }
     public function currency(){
        try{
                $query=$GLOBALS['$dbFramework']->query("select a.currency_id,a.currency_name from currency a,currency_category b where a.currency_category_id=b.currency_category_id
                                            and b.currency_category_name='Products'");
                return $query->result();
        }
        catch (LConnectApplicationException $e)
        {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                    throw $e;
        }
    }
    public function getattr_data($nodeid){
             try{
                        $query=$GLOBALS['$dbFramework']->query("select distinct product_code,remarks,product_id from product_attributes where product_id='$nodeid' and togglebit=1 ");
                        $arr=$query->result_array();
                        $a=array();
                        for($i=0;$i<count($arr);$i++){

                            $product_id=$arr[$i]['product_id'];
                            $a[$i]['product_code']=$arr[$i]['product_code'];
                            $a[$i]['remarks']=$arr[$i]['remarks'];


                            $query1=$GLOBALS['$dbFramework']->query("select a.id,a.currency_id,a.product_value,(select currency_name from currency where a.currency_id=currency_id)as currencyname
                                                        from product_attributes a where product_id='$product_id' and togglebit=1 AND a.currency_id IS NOT NULL order by id ");
                            $arr1=$query1->result_array();
                                for($j=0;$j<count($arr1);$j++){
                                            $a[$i]['curdata'][$j]['currency_id']=$arr1[$j]['currency_id'];
                                            $a[$i]['curdata'][$j]['product_value']=$arr1[$j]['product_value'];
                                            $a[$i]['curdata'][$j]['currencyname']=$arr1[$j]['currencyname'];
                                            $a[$i]['curdata'][$j]['id']=$arr1[$j]['id'];
                                }

                          }
                          return $a;
                          /*$query=$GLOBALS['$dbFramework']->query("call new_procedure('$nodeid'); ");
                          return $query->result();*/



             }
             catch (LConnectApplicationException $e)
             {
                        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                        throw $e;
             }
    }
    public function insert_hierarchy($param,$source,$parent_id) {
       try{

              //$que=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('products','insert','".ucfirst(strtolower($source))."','".$parent_id."');");
              $que=$GLOBALS['$dbFramework']->query("SELECT b.id,b.hierarchy_id,b.hkey2,b.hvalue2,b.hkey1,b.hvalue1
                                        				FROM hierarchy_class a,hierarchy b
                                        				WHERE a.Hierarchy_Class_Name='products'
                                        				AND b.hkey2!='0' AND b.Hierarchy_Class_ID=a.hierarchy_class_id
                                        				AND LOWER(b.hvalue2)=LOWER('$source')
                                                        AND b.hkey1='".$parent_id."' ORDER BY b.hierarchy_id;");
                      if($que->num_rows()>0){
                           return false;
                      }else{
                          $insert = $GLOBALS['$dbFramework']->insert('hierarchy',$param);
                          return true;
                      }
             }
             catch (LConnectApplicationException $e)
             {
                        $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                        throw $e;
             }
    }
    public function get_hierarchy_id(){
        try
        {
              $query = $GLOBALS['$dbFramework']->query("select Hierarchy_Class_ID from hierarchy_class where Hierarchy_Class_Name='products'");
              return $query->result();
        }
        catch (LConnectApplicationException $e)
        {
              $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
              throw $e;
        }
    }
    public function update_source($param,$id,$parent_id,$hid) {

        try
        {

                //$que=$GLOBALS['$dbFramework']->query("call get_hierarchy_details('products','update','".ucfirst(strtolower($param))."','".$parent_id."');");
                $que=$GLOBALS['$dbFramework']->query("SELECT b.id,b.hierarchy_id,b.hkey2,b.hvalue2,b.hkey1,b.hvalue1
                                        				FROM hierarchy_class a,hierarchy b
                                        				WHERE a.Hierarchy_Class_Name='products'
                                        				AND b.hkey2!='0' AND b.Hierarchy_Class_ID=a.hierarchy_class_id
                                        				AND LOWER(b.hvalue2)=LOWER('$param')
                                                        AND b.hkey1='".$parent_id."' AND b.hierarchy_id!='".$hid."' ORDER BY b.hierarchy_id;");
                        if($que->num_rows()>0){

                             return false;

                        }else{

                            $updateque=$GLOBALS['$dbFramework']->query("update hierarchy set hvalue2='".$param."' where hkey2='$id'");
                            $updateque=$GLOBALS['$dbFramework']->query("update hierarchy set hvalue1='".$param."' where hkey1='$id'");
                            return true;

                        }
         }
         catch (LConnectApplicationException $e)
          {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
          }
    }
    public function get_child_count($hkey1){
        try
        {
              $count=$GLOBALS['$dbFramework']->query("select count(hkey1) as childcount from hierarchy where hkey1='$hkey1'");
              if($count->num_rows()>0){
                   foreach ($count->result() as $row)
                          {
                              $id=$row->childcount;
                          }
              }
              return $id;
       }
       catch (LConnectApplicationException $e)
       {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
       }
    }
    public function postattr_data($prodCode,$prodDescip,$currId,$nodeid,$currIdInactive){
        $dt=date('ymdHis');
        try
        {
                $query = $GLOBALS['$dbFramework']->query("select * from product_attributes where
                                                        CONCAT(UCASE(LEFT(product_code, 1)),
                                                                LCASE(SUBSTRING(product_code, 2)))='".ucfirst(strtolower($prodCode))."' and product_id<>'$nodeid'");
                  if($query->num_rows()>0){
                       return false;
                  }else{

                      if($currId){
                          $attr = (array)$currId;// convert the object into array
                          foreach($attr as $key => $val)
                          {
                                  $attrname=$key;
                                  if($attrname <> "empty"){
                                      $attr_val=$attr[$key];

                                      $letter=chr(rand(97,122));
                                      $letter.=chr(rand(97,122));
                                      $curmapID=$letter;
                                      $curmapID.=$dt;
                                      $curmapID1=uniqid($curmapID);

                                      $que=$GLOBALS['$dbFramework']->query("select * from product_attributes where product_id='$nodeid' and currency_id='$attrname' ");
                                      if($que->num_rows()>0){
                                              foreach ($que->result() as $row)
                                              {
                                                  $id=$row->id;
                                              }
                                              $upque=$GLOBALS['$dbFramework']->query("update product_attributes set togglebit=0 where product_id='$nodeid' and currency_id is null ");
                                              $upque=$GLOBALS['$dbFramework']->query("update product_attributes set product_code='$prodCode',
                                                                          remarks='$prodDescip',product_value='$attr_val',togglebit=1 where id='$id' ");
                                      }else{

                                              $upque=$GLOBALS['$dbFramework']->query("update product_attributes set togglebit=0 where product_id='$nodeid' and currency_id is null ");

                                              $GLOBALS['$dbFramework']->query("insert into product_attributes(cur_product_map_id,currency_id,product_id,product_code,
                                                          product_value,remarks)values('$curmapID1','$attrname','$nodeid','$prodCode','$attr_val','$prodDescip')");
                                      }
                                  }else{

                                          $que=$GLOBALS['$dbFramework']->query("select * from product_attributes where product_id='$nodeid' and currency_id is null ");
                                          if($que->num_rows()>0){
                                                  foreach ($que->result() as $row)
                                                  {
                                                      $id=$row->id;
                                                  }
                                                  $upque=$GLOBALS['$dbFramework']->query("update product_attributes set product_code='$prodCode',
                                                                              remarks='$prodDescip',togglebit=1 where id='$id' ");
                                          }else{

                                              $letter=chr(rand(97,122));
                                              $letter.=chr(rand(97,122));
                                              $curmapID=$letter;
                                              $curmapID.=$dt;
                                              $curmapID1=uniqid($curmapID);

                                              $upque=$GLOBALS['$dbFramework']->query("update product_attributes set togglebit=0 where product_id='$nodeid' and currency_id is not null ");


                                              $GLOBALS['$dbFramework']->query("insert into product_attributes(cur_product_map_id,product_id,product_code,
                                                          remarks)values('$curmapID1','$nodeid','$prodCode','$prodDescip')");
                                          }
                                  }
                          }

                      }
                      if($currIdInactive){

                              $attr = (array)$currIdInactive;// convert the object into array
                              foreach($attr as $key => $val)
                              {
                                      $attrname=$key;
                                      if($attrname <> "empty"){

                                          $upquery=$GLOBALS['$dbFramework']->query("update product_attributes set togglebit=0 where id='$attrname'");
                                      }
                              }
                      }
                      return true;
                  }
          }
          catch (LConnectApplicationException $e)
          {
                    $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                    throw $e;
          }
    }
}
?>