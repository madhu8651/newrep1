<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_holidaysModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_holidaysModel extends CI_Model{
    
    public function __construct(){
        parent::__construct();
    }
     
    public function view_data(){
        /*$query=$GLOBALS['$dbFramework']->query("select a.calenderid,a.calendername,b.holidayname,b.date, b.holidayid from calender a ,
                                                    holiday_list b where a.calenderid=b.calenderid order by date");
        return $query->result_array();*/
        $a=array();
        try{
                   $query = $GLOBALS['$dbFramework']->query("select * from calender order by id");
                   if($query->num_rows()>0){

                            $arr=$query->result_array();
                            for($i=0;$i<count($arr);$i++){

                                    $calenderid=$arr[$i]['calenderid'];

                                    $a[$i]['id'] = $arr[$i]['id'];
                                    $a[$i]['calenderid'] = $arr[$i]['calenderid'];
                                    $a[$i]['calendername'] =$arr[$i]['calendername']; 


                                    $query1 = $GLOBALS['$dbFramework']->query("select * from holiday_list where calenderid='".$calenderid."'");
                                     if($query1->num_rows()>0){

                                            $arr1=$query1->result_array();
                                            for($j=0;$j<count($arr1);$j++){

                                                    $a[$i]['holiday_data'][$j]['holidayid']=$arr1[$j]['holidayid'];
                                                    $a[$i]['holiday_data'][$j]['holidayname']=$arr1[$j]['holidayname'];
                                                    $a[$i]['holiday_data'][$j]['date']=$arr1[$j]['date'];
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
    
    public function show_calender(){
       try{
              $query= $GLOBALS['$dbFramework']->query("select * from calender");
              return $query->result_array();
        }
         catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    
    }
    
    public function insert_data($calenderid,$holidayList){
      try{
            $a=array();
            $j=0;
            $dt = date('ymdHis');
            foreach ($holidayList as  $value) {

                    $holidayname=$value->holidayname;
                    $date=$value->date;
                    $holidayDate=date("Y-m-d", strtotime($date) );

                    $letter=chr(rand(97,122));
                    $letter.=chr(rand(97,122));
                    $holidayId=$letter;
                    $holidayId.=$dt;
                    $holidayId1=uniqid($holidayId);

                    
                    $query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('insert,holiday_list','".$calenderid."','".$holidayDate."','".ucfirst(strtolower($holidayname))."',''); ");
                    if($query->num_rows()>0){
                           $arr=$query->result_array();
                            for($i=0;$i<count($arr);$i++){
                                $a[$j]['holidayname'] = $arr[$i]['holidayname'];
                                $a[$j]['date'] = $arr[$i]['date'];
                                $j++;
                            }
                    }else{

                        $query=$GLOBALS['$dbFramework']->query("insert into holiday_list (holidayid,holidayname,calenderid,date)
                                                                  values('".$holidayId1."','".ucfirst(strtolower($holidayname))."','".$calenderid."','".$holidayDate."')  ");
                    }

            }
            return $a;
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }
    
    public function update_data($calendarID,$holidayDate,$holidayname,$holidayID) {
        $str="";
        try{


                $query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('update,holiday_list','".$calendarID."','".$holidayDate."','".ucfirst(strtolower($holidayname))."','".$holidayID."'); ");
               if($query->num_rows()>0)
                  {
                      $str="0";
                  }
                  else
                  {
                      $query=$GLOBALS['$dbFramework']->query("UPDATE holiday_list SET holidayname = '".ucfirst(strtolower($holidayname))."', date = '".$holidayDate."', calenderid='".$calendarID."' WHERE holidayid='".$holidayID."' ");
                      $str="1";
                  }

                  $a=array();

                   $query = $this->db->query("select * from calender where calenderid='".$calendarID."' order by id");
                   if($query->num_rows()>0){

                            $arr=$query->result_array();
                            for($i=0;$i<count($arr);$i++){

                                    $calenderid=$arr[$i]['calenderid'];

                                    $a[$i]['id'] = $arr[$i]['id'];
                                    $a[$i]['calenderid'] = $arr[$i]['calenderid'];
                                    $a[$i]['calendername'] =$arr[$i]['calendername'];



                                    $query1 = $this->db->query("select * from holiday_list where calenderid='".$calenderid."'");
                                     if($query1->num_rows()>0){

                                            $arr1=$query1->result_array();
                                            for($j=0;$j<count($arr1);$j++){

                                                    $a[$i]['holiday_data'][$j]['holidayid']=$arr1[$j]['holidayid'];
                                                    $a[$i]['holiday_data'][$j]['holidayname']=$arr1[$j]['holidayname'];
                                                    $a[$i]['holiday_data'][$j]['date']=$arr1[$j]['date'];
                                            }
                                     }
                             }
                   }
                   return array(
                        'records' => $a,
                        'str' => $str,
                   );
         }
         catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }
    public function get_added_holidaydata($calenderid){
            $a=array();
         try{
             $query = $this->db->query("select * from calender where calenderid='".$calenderid."' order by id");
             if($query->num_rows()>0){

                      $arr=$query->result_array();
                      for($i=0;$i<count($arr);$i++){

                              $calenderid=$arr[$i]['calenderid'];

                              $a[$i]['id'] = $arr[$i]['id'];
                              $a[$i]['calenderid'] = $arr[$i]['calenderid'];
                              $a[$i]['calendername'] =$arr[$i]['calendername'];

                              $query1 = $this->db->query("select * from holiday_list where calenderid='".$calenderid."'");
                               if($query1->num_rows()>0){

                                      $arr1=$query1->result_array();
                                      for($j=0;$j<count($arr1);$j++){

                                              $a[$i]['holiday_data'][$j]['holidayid']=$arr1[$j]['holidayid'];
                                              $a[$i]['holiday_data'][$j]['holidayname']=$arr1[$j]['holidayname'];
                                              $a[$i]['holiday_data'][$j]['date']=$arr1[$j]['date'];
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
    
    
}


?>

