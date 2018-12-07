<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('lconnectt_mail_setting_Model');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();



class lconnectt_mail_setting_Model extends CI_Model{
     public function __construct(){
        parent::__construct();
     }
     public function get_details(){
            try{
                    $query = $GLOBALS['$dbFramework']->query("select a.email_settings_id, a.incoming_host, a.incoming_port, a.port_type,
                                                                 a.incoming_ssl, a.outgoing_host, a.outgoing_port, a.outgoing_ssl,
                                                                  b.name, b.email_id, b.password from email_settings a,user_email_settings b
                                                                  where a.email_settings_id=b.email_settings_id");
                    return $query->result();

            }
                catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }
      }

      public function insert_data($data1,$data2,$emailid){
            try{
                    if($emailid==""){
                        $var = $GLOBALS['$dbFramework']->insert('user_email_settings',$data1);
                        $var1 = $GLOBALS['$dbFramework']->insert('email_settings',$data2);
                    }else{
                         $update = $GLOBALS['$dbFramework']->update('user_email_settings' ,$data1, array('LOWER(email_settings_id)' => strtolower($emailid)));
                         $update1 = $GLOBALS['$dbFramework']->update('email_settings' ,$data2, array('LOWER(email_settings_id)' => strtolower($emailid)));
                    }

                    return true;

            }
                catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }
      }


  }
?>
    