<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_calendarModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_calendarModel extends CI_Model{
    
     public function __construct(){
        parent::__construct();
    }
    
    public function view_data(){

        $query=$GLOBALS['$dbFramework']->query("select * from calender");
        return $query->result_array();
    }
    
     public function insert_data($data,$calendarName)
     {
       try{

              $query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('calender','calendername','".ucfirst(strtolower($calendarName))."','','')");
              if($query->num_rows()>0)
                 {
                     return false;
                 }
                 else
                 {
                     $insert = $GLOBALS['$dbFramework']->insert('calender', $data);
                     return $insert;
                 }
         }
         catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }
     public function update_calendar($calenderid,$calendarName)
     {
        try{

                $query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('calender','calendername','".ucfirst(strtolower($calendarName))."','','')");
                if($query->num_rows()>0)
                   {
                       return false;
                   }
                else{
                   $update=$GLOBALS['$dbFramework']->query("UPDATE calender SET calendername = '".ucfirst(strtolower($calendarName))."' WHERE calenderid='".$calenderid."'");
                   return $update;

                }
         }
         catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }
    
    
}




?>
