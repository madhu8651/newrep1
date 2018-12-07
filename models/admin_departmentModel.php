<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_departmentModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_departmentModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
    public function view_data(){
         try
          {
              $query=$GLOBALS['$dbFramework']->query("SELECT * FROM department order by Department_name");
              return $query->result();
          }
          catch (LConnectApplicationException $e)
          {
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
          }
    }
    public function insert_data($data,$departmentName) {
       try{

               $query=$GLOBALS['$dbFramework']->query("select * from department where LOWER(Department_name)=LOWER('".$departmentName."')");
               //$query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('department','Department_name','".ucfirst(strtolower($departmentName))."','','')");
               if($query->num_rows()>0)
                  {
                      return false;
                  }
                  else
                  {
                      $insert = $GLOBALS['$dbFramework']->insert('department',$data);
                       return $insert;
                  }
       }
       catch (LConnectApplicationException $e)
       {
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
       }
    }
    public function update_data($departmentID,$data,$departmentName) {
     try{

            $query=$GLOBALS['$dbFramework']->query("select * from department where LOWER(Department_name)=LOWER('".$departmentName."') AND Department_id<>'".$departmentID."' ");
            //$query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('department','Department_name','".ucfirst(strtolower($departmentName))."','','')");
             if($query->num_rows()>0)
                {
                    return false;
                }else
                {

                    $update = $GLOBALS['$dbFramework']->update('department' ,$data, array('LOWER(Department_id)' => strtolower($departmentID)));
                    return $update;
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

