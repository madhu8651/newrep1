<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_currencyModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();



class admin_currencyModel extends CI_Model{
     public function __construct(){
        parent::__construct();
     }
      public function view_data(){
       try{

                $q1=$GLOBALS['$dbFramework']->query("SELECT  json_extract(plugin_purchased, '$.expenses') AS expense FROM client_info");
                       if($q1->num_rows()>0){
                          foreach ($q1->result() as $row)
                          {
                              $id=$row->expense;
                          }
                      }

                      if($id > 0)
                      {
                         $query = $GLOBALS['$dbFramework']->query("select * from currency_category  order by id");
                      }
                      else
                      {
                          $query = $GLOBALS['$dbFramework']->query("select * from currency_category where currency_category_name<>'Expenses' order by id");
                      }

                   if($query->num_rows()>0){

                            $arr=$query->result_array();
                            for($i=0;$i<count($arr);$i++){

                                    $currency_category_id=$arr[$i]['currency_category_id'];

                                    $a[$i]['id'] = $arr[$i]['id'];
                                    $a[$i]['currency_category_id'] = $arr[$i]['currency_category_id'];
                                    $a[$i]['currency_category_name'] =$arr[$i]['currency_category_name'];
                                    
                             }
                   }
                   return $a;
            }
                catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }
      }
      public function view_data1($categoryid){
       try{
                $q1=$GLOBALS['$dbFramework']->query("SELECT  json_extract(plugin_purchased, '$.expenses') AS expense FROM client_info");
                       if($q1->num_rows()>0){
                          foreach ($q1->result() as $row)
                          {
                              $id=$row->expense;
                          }
                      }

                      if($id > 0)
                      {
                         $query = $GLOBALS['$dbFramework']->query("select * from currency_category where currency_category_id='".$categoryid."' order by id");
                      }
                      else
                      {
                          $query = $GLOBALS['$dbFramework']->query("select * from currency_category where currency_category_id='".$categoryid."' and currency_category_name<>'Expenses' order by id");
                      }

                   if($query->num_rows()>0){

                            $arr=$query->result_array();
                            for($i=0;$i<count($arr);$i++){

                                    $currency_category_id=$arr[$i]['currency_category_id'];

                                    $a[$i]['id'] = $arr[$i]['id'];
                                    $a[$i]['currency_category_id'] = $arr[$i]['currency_category_id'];
                                    $a[$i]['currency_category_name'] =$arr[$i]['currency_category_name'];



                                    $query1 = $GLOBALS['$dbFramework']->query("select * from currency where currency_category_id='".$currency_category_id."'");
                                     if($query1->num_rows()>0){

                                            $arr1=$query1->result_array();
                                            for($j=0;$j<count($arr1);$j++){

                                                    $a[$i]['currency_data'][$j]['currency_category_id']=$arr1[$j]['currency_category_id'];
                                                    $a[$i]['currency_data'][$j]['currency_id']=$arr1[$j]['currency_id'];
                                                    $a[$i]['currency_data'][$j]['currency_name']=$arr1[$j]['currency_name'];
                                            }
                                     }
                             }
                   }
                   return $a;
            }
                catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }
      }
       public function get_currency()
       {
            try{
                       $q1=$GLOBALS['$dbFramework']->query("SELECT  json_extract(plugin_purchased, '$.expenses') AS expense FROM client_info");
                       if($q1->num_rows()>0){
                          foreach ($q1->result() as $row)
                          {
                              $id=$row->expense;
                          }
                      }
                      
                      if($id > 0)
                      {
                         $query=$GLOBALS['$dbFramework']->query("select * from currency_category");
                      }
                      else
                      {
                          $query=$GLOBALS['$dbFramework']->query("select * from currency_category where currency_category_name<>'Expenses'");
                      }
                      return $query->result();
            }
            catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
      }
       public function insert_data($categoryid,$currencyObj) {

         try{
            $dt = date('ymdHis');
            $i=0;
            $dup_currency='';
            foreach ($currencyObj as  $value) {

                    $currencyname=$value->currency_name;

                    $letter=chr(rand(97,122));
                    $letter.=chr(rand(97,122));
                    $holidayId=$letter;
                    $holidayId.=$dt;
                    $holidayId1=uniqid($holidayId);

                    //$query=$GLOBALS['$dbFramework']->query("SELECT * FROM currency WHERE currency_category_id='".$categoryid."' and currency_name='".$currencyname."'");
                    $query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('currency','".$categoryid."','".$currencyname."','insert','');");
                    if($query->num_rows()>0){

                             $arr1=$query->result_array();
                             $dup_currency[$i]['currency_name'] = $arr1[0]['currency_name'];

                             $i++;

                    }else{

                        $query=$GLOBALS['$dbFramework']->query("insert into currency (currency_id,currency_category_id,currency_name)
                                                                  values('".$holidayId1."','".$categoryid."','".$currencyname."')  ");
                    }

            }

            return $dup_currency;
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }



    }
    public function update_data($currencyid,$data,$categoryid) {
       try{
                    /*$query=$GLOBALS['$dbFramework']->query("select * from currency where
                                                            currency_name='".$data['currency_name']."' and currency_category_id='".$categoryid."'");*/
                    $query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('currency','".$data['currency_name']."','".$categoryid."','update','');");
                    if($query->num_rows()>0)
                    {
                         return $query->num_rows();

                    }
                    else
                    {
                        $update = $GLOBALS['$dbFramework']->update('currency' ,$data, array('LOWER(currency_id)' => strtolower($currencyid)));
                        return 0;
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
    