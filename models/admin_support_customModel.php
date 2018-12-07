<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_support_customModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();


class admin_support_customModel extends CI_Model{
     public function __construct(){
        parent::__construct();
    }
    public function view_data(){
        try{
            $query = $GLOBALS['$dbFramework']->query("select id,support_attribute_id,support_attribute_name,support_attribute_type,COALESCE(listvalues, '-') AS listvalues from support_attribute  order by id");
            return $query->result();

        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function insert_data($data,$buyerp_Name) {
        try{
                $query=$GLOBALS['$dbFramework']->query("select * from support_attribute where
                                                    LOWER(support_attribute_name)=LOWER('".$buyerp_Name."')");
                if($query->num_rows()>0)
                {
                    return false;
                }
                else
                {
                    $insert = $GLOBALS['$dbFramework']->insert('support_attribute', $data);
                    return $insert;
                }
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    public function update_data($buyerpID,$data) {
        try{

                   $update = $GLOBALS['$dbFramework']->update('support_attribute' ,$data, array('LOWER(support_attribute_id)' => strtolower($buyerpID)));
                   return $update;
        }catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
}
?>

