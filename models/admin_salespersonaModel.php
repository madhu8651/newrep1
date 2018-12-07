<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_salespersonaModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();



class admin_salespersonaModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
    public function view_data(){
        try{
            $query=$GLOBALS['$dbFramework']->query("SELECT * FROM lookup WHERE lookup_name='Sales Persona'");
            return $query->result();
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function insert_data($data, $salespName) {
        try{
                /*$query=$GLOBALS['$dbFramework']->query("select * from lookup where
                                                      CONCAT(UCASE(LEFT(lookup_value, 1)),
                                                      LCASE(SUBSTRING(lookup_value, 2)))='".ucfirst(strtolower($salespName))."'
                                                      and lookup_name='Sales Persona' order by id ");*/
                $query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('lookup','lookup_value','".ucfirst(strtolower($salespName))."','lookup_name','Sales Persona')");

                if($query->num_rows()>0)
                  {
                      return false;
                  }
                  else
                  {
                      $insert = $GLOBALS['$dbFramework']->insert('lookup', $data);
                      return $insert;
                  }
         }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }
    public function update_data($salespID,$data,$salespName) {
        try{
             /*$query=$GLOBALS['$dbFramework']->query("select * from lookup where
                                                    CONCAT(UCASE(LEFT(lookup_value, 1)),
                                                    LCASE(SUBSTRING(lookup_value, 2)))='".ucfirst(strtolower($salespName))."'
                                                    and lookup_name='Sales Persona' order by id");*/
             $query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('lookup','lookup_value','".ucfirst(strtolower($salespName))."','lookup_name','Sales Persona')");
             if($query->num_rows()>0)
                {
                    return false;
                }
                else
                {
                   $update = $GLOBALS['$dbFramework']->update('lookup' ,$data, array('LOWER(lookup_id)' => strtolower($salespID)));
                   return $update;
                }
         }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
         }
    }
}
?>

