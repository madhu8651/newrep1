<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_mgr_repModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class manager_mgr_repModel extends CI_Model{
    public function __construct(){
        parent::__construct();
    }
    public function get_lead_source($var) {
        $userid=$this->session->userdata('uid'); /* id to be taken from session */
        $a=array();

           $query = $this->db->query("SELECT a.id,a.user_id as childid,a.user_name as childname,(select role_name from user_roles where a.designation=role_id) as childname1,
                                                b.user_id as parentid,b.user_name as parentname,(select role_name from user_roles where b.designation=role_id) as parentname1,
                                                (select role_value from user_roles where a.designation=role_id) as rolval,a.photo
                                                FROM user_details a,user_details b
                                                WHERE (a.reporting_to = b.user_id) and a.user_state=1 order by id;");
           if($query->num_rows()>0){

                    $arr=$query->result_array();

                    for($i=0;$i<count($arr);$i++){

                            $userid=$arr[$i]['childid'];

                            if($var==$userid){
                                    $a[$i]['hid'] = $arr[$i]['id'];
                    				$a[$i]['id'] = $arr[$i]['childid'];
                    				$a[$i]['name'] = $arr[$i]['childname'];
                    				$a[$i]['parent'] = "0";
                    				$a[$i]['parent_name'] = $arr[$i]['parentname'];
                    				$a[$i]['photo'] = $arr[$i]['photo'];
                                    $getcount=1;
                                    $a[$i]['nodecount'] = $getcount;
                            }else{
                                    $a[$i]['hid'] = $arr[$i]['id'];
                    				$a[$i]['id'] = $arr[$i]['childid'];
                    				$a[$i]['name'] = $arr[$i]['childname'];
									$a[$i]['name1'] = $arr[$i]['childname1']."-- Level ".$arr[$i]['rolval'];
                    				$a[$i]['parent'] = $arr[$i]['parentid'];
                    				$a[$i]['parent_name'] = $arr[$i]['parentname'];
									$a[$i]['parent_name1'] =$arr[$i]['parentname1'];
                    				$a[$i]['photo'] = $arr[$i]['photo'];
                                    $getcount=1;
                                    $a[$i]['nodecount'] = $getcount;
                            }


                            $query1 = $this->db->query("select a.manager_module, (select module_name from module_master where a.manager_module=module_id and a.manager_module<>'0')
                                                        as managermodulename,
                                                        a.sales_module,(select module_name from module_master where a.sales_module=module_id and a.sales_module<>'0' )
                                                        as salesmodulename,
                                                        a.cxo_module,(select module_name from module_master where a.cxo_module=module_id and a.cxo_module<>'0')
                                                        as cxomodulename
                                                         from user_licence a where a.user_id='$userid';");
                             if($query1->num_rows()>0){

                                    $arr1=$query1->result_array();
                                    for($j=0;$j<count($arr1);$j++){

                                            $a[$i]['modulename'][$j]['managermodulename']=$arr1[$j]['managermodulename'];
                                            $a[$i]['modulename'][$j]['manager_module']=$arr1[$j]['manager_module'];
                                            $a[$i]['modulename'][$j]['salesmodulename']=$arr1[$j]['salesmodulename'];
                                            $a[$i]['modulename'][$j]['sales_module']=$arr1[$j]['sales_module'];
                                            $a[$i]['modulename'][$j]['cxomodulename']=$arr1[$j]['cxomodulename'];
                                            $a[$i]['modulename'][$j]['cxo_module']=$arr1[$j]['cxo_module'];

                                    }
                             }

                             $query11 = $this->db->query("select  a.map_id,(select hvalue2 from hierarchy where hkey2=a.map_id)as proname
                                                            from user_mappings a where user_id='$userid' and map_type='product';");
                             if($query11->num_rows()>0){

                                    $arr11=$query11->result_array();
                                    for($k=0;$k<count($arr11);$k++){

                                            $a[$i]['productdata'][$k]['map_id']=$arr11[$k]['map_id'];
                                            $a[$i]['productdata'][$k]['proname']=$arr11[$k]['proname'];


                                    }
                             }
                    }
           }
           return $a;
    }

}
?>