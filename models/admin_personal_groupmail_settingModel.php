<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_personal_groupmail_settingModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();



class admin_personal_groupmail_settingModel extends CI_Model{
     public function __construct(){
        parent::__construct();
     }
     public function get_detailsG(){
            try{

                    $query = $GLOBALS['$dbFramework']->query("select * from email_settings where email_settings_type='groupsetting'");
                    $arr=$query->result_array();
                    $a=array();
                    for($i=0;$i<count($arr);$i++){

                        $email_settings_id=$arr[$i]['email_settings_id'];
                        $a[$i]['incoming_host']=$arr[$i]['incoming_host'];
                        $a[$i]['incoming_port']=$arr[$i]['incoming_port'];
                        $a[$i]['port_type']=$arr[$i]['port_type'];
                        $a[$i]['incoming_ssl']=$arr[$i]['incoming_ssl'];
                        $a[$i]['outgoing_host']=$arr[$i]['outgoing_host'];
                        $a[$i]['outgoing_ssl']=$arr[$i]['outgoing_ssl'];
                        $a[$i]['outgoing_port']=$arr[$i]['outgoing_port'];
                        $a[$i]['email_settings_id']=$arr[$i]['email_settings_id'];
                        $query1=$GLOBALS['$dbFramework']->query("select * from user_email_settings where settings_key='groupsetting' and email_settings_id='".$email_settings_id."' ");
                        $arr1=$query1->result_array();
                            for($j=0;$j<count($arr1);$j++){
                                        $a[$i]['emaildata'][$j]['name']=$arr1[$j]['name'];
                                        $a[$i]['emaildata'][$j]['email_id']=$arr1[$j]['email_id'];
                                        $a[$i]['emaildata'][$j]['password']=$arr1[$j]['password'];
                                        $a[$i]['emaildata'][$j]['settings_value']=$arr1[$j]['settings_value'];
                                        $a[$i]['emaildata'][$j]['user_email_settings_id']=$arr1[$j]['user_email_settings_id'];
                                        $a[$i]['emaildata'][$j]['id']=$arr1[$j]['id'];
                            }

                      }
                      return $a;

            }
                catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }
      }
      public function get_detailsP(){
            try{
                    $query = $GLOBALS['$dbFramework']->query("select * from email_settings where email_settings_type='personalsetting'");
                    return $query->result();

            }
                catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }
      }

      public function insert_data($data1,$data2,$emailid){
            try{

                    if($data1=='personal'){
                        if($emailid==""){

                            $var1 = $GLOBALS['$dbFramework']->insert('email_settings',$data2);
                        }else{

                             $update1 = $GLOBALS['$dbFramework']->update('email_settings' ,$data2, array('LOWER(email_settings_id)' => strtolower($emailid)));
                        }
                    }else{
                        if($emailid==""){
                            $var1 = $GLOBALS['$dbFramework']->insert('email_settings',$data2);
                        }else{

                             $update1 = $GLOBALS['$dbFramework']->update('email_settings' ,$data2, array('LOWER(email_settings_id)' => strtolower($emailid)));
                        }
                    }
                    return true;

            }
                catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }
      }
      public function insert_data1($data1,$btntype,$emailid1){
            try{
                    if($btntype=='add'){
                        $var1 = $GLOBALS['$dbFramework']->insert('user_email_settings',$data1);
                        return true;
                    }else{
                        $update1 = $GLOBALS['$dbFramework']->update('user_email_settings' ,$data1, array('LOWER(user_email_settings_id)' => strtolower($emailid1)));
                        return true;
                    }


            }
                catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }
      }
      public function update_tg_bit($tgbit,$set_id){
            try{

                        $update1 = $GLOBALS['$dbFramework']->query("update user_email_settings set settings_value='".$tgbit."' where user_email_settings_id='".$set_id."'");
                        return true;

            }
                catch (LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
            }
      }


  }
?>
    