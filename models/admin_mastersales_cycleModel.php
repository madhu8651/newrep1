<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_mastersales_cycleModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();



class admin_mastersales_cycleModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }


    public function view_cycledata(){
        try{
            $query=$GLOBALS['$dbFramework']->query("SELECT * FROM master_sales_cycle order by id");
            return $query->result();
        }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }

    }
    public function insert_data($cycledata,$cyclename,$cycleID) {
        try{

                $query=$GLOBALS['$dbFramework']->query("select * from master_sales_cycle where LOWER(master_cyclename)=LOWER('".$cyclename."')");
                //$query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('master_sales_cycle','master_cyclename','".ucfirst(strtolower($cyclename))."','','')");
                if($query->num_rows()>0){
                  return false;
                }
                else{

                    $GLOBALS['$dbFramework']->insert('master_sales_cycle', $cycledata);

                    return TRUE;
                }
        }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }
    public function update_data($cycledata,$cyclename,$cycleid) {
        try{

                $query=$GLOBALS['$dbFramework']->query("select * from master_sales_cycle where LOWER(master_cyclename)=LOWER('".$cyclename."') and master_cycleid<>'".$cycleid."'");
                //$query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('master_sales_cycle','master_cyclename','".ucfirst(strtolower($cyclename))."','','')");
                if($query->num_rows()>0){
                    return false;
                }
                else{
                    $query = $GLOBALS['$dbFramework']->update('master_sales_cycle' ,$cycledata, array('LOWER(master_cycleid)' => strtolower($cycleid)));
                    return TRUE;
                }
        }catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
    }
}
?>

