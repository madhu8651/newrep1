<?php

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_sidenavModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class admin_sidenavModel extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    public function view_userinfo($userid){
        try{
                $query=$GLOBALS['$dbFramework']->query("select a.*,b.plugin_id from user_details a,user_module_plugin_mapping b where a.user_id=b.user_id
                                                and a.user_id='".$userid."'");
                return $query->result();
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }
    public function view_userinfo1($userid){
        try{
                $query=$GLOBALS['$dbFramework']->query("SELECT a.user_name, a.dob, a.user_gender, a.employee_id,
                                    a.designation, c.department_name, a.phone_num, b.role_name, d.user_name as reporting_to,
                                    e.teamname, a.emailId, f.module_id,  group_concat(distinct h.hvalue2) as businesslocation,
                                   (select concat(hi.hvalue2) from user_mappings as um, hierarchy hi where um.user_id='$userid' and um.map_id=hi.hkey2
                                    and um.map_type='clientele_industry') as clientele
                                    from user_details a, user_roles b, department c, user_details d, teams e,
                                    user_module_plugin_mapping f, user_mappings g, hierarchy h , user_mappings i, hierarchy j
                                    where a.user_id='$userid'
                                    and g.user_id='$userid'
                                    and g.map_id=h.hkey2
                                    and g.map_type='business_location'
                                    and a.reporting_to=d.user_id
                                    and a.department=b.department_id
                                    and a.department=c.department_id
                                    and e.teamid=a.team_id
                                    and a.user_id=f.user_id
                                    group by a.user_id");
                return $query->result();
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }
     public function update_managerinfo($managerid,$data,$adminPhone){
        try{
                $GLOBALS['$dbFramework']->query("update user_details set phone_num = JSON_SET(phone_num, \"$.mobile[0]\",'$adminPhone' ) where user_id = '$managerid'");
                $GLOBALS['$dbFramework']->update('user_details' ,$data, array('LOWER(user_id)' => strtolower($managerid)));
                return true;
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }
    public function update_managerinfo1($managerid,$data){
        try{
                $GLOBALS['$dbFramework']->update('user_details' ,$data, array('LOWER(user_id)' => strtolower($managerid)));
                return true;
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

    public function do_upload($managerid,$filedata){
        try{
                $GLOBALS['$dbFramework']->update('user_details' ,$filedata, array('LOWER(user_id)' => strtolower($managerid)));
                return true;
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }
    public function timezone_settings($managerid,$timeZone){
        try{
                $query=$GLOBALS['$dbFramework']->query("SELECT * FROM user_timezone_setting where user_id='$managerid' order by id desc limit 1");
                $query->result();
                $gmdate=gmdate('Y-m-d H:i:s');
                if($query->num_rows()>0){
                      /* update end time */
                      foreach ($query->result() as $row)
                      {
                            $latest_id = $row->id;

                      }
                      $update_query=$GLOBALS['$dbFramework']->query("update user_timezone_setting set tz_end_time='".$gmdate."'  where user_id='".$managerid."' and id=".$latest_id."");
                      $insertque=$GLOBALS['$dbFramework']->query("insert into user_timezone_setting(user_id,tz_start_time,timezone)
                                                                values('".$managerid."','".$gmdate."','".$timeZone."')");
                }
                return true;
        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

    public function get_timezones($userid){
        try{
                $query=$GLOBALS['$dbFramework']->query("SELECT * FROM user_timezone_setting where user_id='$userid' order by id desc limit 1");
                return $query->result();

        }
        catch (LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }

    }

    public function get_email_settings($incoming_host){
        try{
            $query = $GLOBALS['$dbFramework']->query("SELECT * FROM email_settings WHERE email_settings_type='personalsetting' and incoming_host='$incoming_host'");
            return $query;
        }catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function save_user_email_settings($data){
        try{
            $insert = $GLOBALS['$dbFramework']->insert('user_email_settings',$data);
            return $insert;
        }catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

  public function get_user_email_settings($userid){
        try{
            $query = $GLOBALS['$dbFramework']->query("SELECT ues.*, es.incoming_host, '1' as emailset FROM user_email_settings ues, email_settings es WHERE ues.user_id = '".$userid."' and es.email_settings_id = ues.email_settings_id 
            and ues.settings_key='personalsetting'");
                if(count($query->result())>0){
                    return $query->result();
                }else{
                        $query = $GLOBALS['$dbFramework']->query("SELECT es.incoming_host, ud.user_name as user_name,user_primary_email as email,ud.user_id as user_id 
                        from user_details ud, email_settings es where es.email_settings_type='personalsetting'and ud.user_id = '$userid' group by ud.user_id");
                        if(count($query->result())==0){ 
                                $query1 = $GLOBALS['$dbFramework']->query("SELECT es.incoming_host, ud.user_name as user_name,user_primary_email as email,ud.user_id as user_id,'0' as emailset
                                from user_details ud, email_settings es where ud.user_id = '$userid' group by ud.user_id");
                                return $query1->result();
                        }else{
                                $query1 = $GLOBALS['$dbFramework']->query("SELECT es.incoming_host, ud.user_name as user_name,user_primary_email as email,ud.user_id as user_id,'1' as emailset
                                from user_details ud, email_settings es where ud.user_id = '$userid' group by ud.user_id");
                                return $query1->result();
                        }   
                }
        }catch(LConnectApplicationException $e){
                $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
                throw $e;
        }
}

    public function update_user_email_settings($data,$id){
        try{
            $insert = $GLOBALS['$dbFramework']->update('user_email_settings',$data,array('user_email_settings_id' => $id));
            return $insert;
        }catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }

    public function getUserExist($userid){
     try{
            $data = $GLOBALS['$dbFramework']->select("SELECT user_id from user_email_settings
                                                    where user_id = '$userid'");
            if(count($data->result())>0){
                return 1;
            }else{
                return 0;
            }
        }catch(LConnectApplicationException $e){
            $GLOBALS['$logger']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }   
    }

}

?>