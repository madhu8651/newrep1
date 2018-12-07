<?php
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
include_once (ROOT_PATH.'/core/LConnectDataAccess.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_customFieldModel');
$GLOBALS['$dbFramework'] = new LConnectDataAccess();

class admin_customFieldModel extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    /* -- Get list of custom fields from DB and passing on to Controller -- */
    public function get_data($pageid) {
         $a=array();
        try{
                $query = $GLOBALS['$dbFramework']->query("select distinct module from admin_attributes where module='".$pageid."' order by id");
                   if($query->num_rows()>0){

                            $arr=$query->result_array();
                            for($i=0;$i<count($arr);$i++){

                                    $module=$arr[$i]['module'];
                                    $a[$i]['module'] = $arr[$i]['module'];

                                    $query1 = $GLOBALS['$dbFramework']->query("select * from admin_attributes where module='".$module."'");
                                     if($query1->num_rows()>0){

                                            $arr1=$query1->result_array();
                                            for($j=0;$j<count($arr1);$j++){

                                                    $a[$i]['attribute'][$j]['attribute_name']=$arr1[$j]['attribute_name'];
                                                    $a[$i]['attribute'][$j]['attribute_type']=$arr1[$j]['attribute_type'];
                                                    $a[$i]['attribute'][$j]['attribute_key']=$arr1[$j]['attribute_key'];
                                            }
                                     }
                             }
                   }
                   return $a;
        }catch(LConnectApplicationException $e){
            $GLOBALS['$log']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    /* -- Adding Custom fields for leads/customers/opportunity/users -- */
    public function post_data($currencyObj,$module,$attr_relation_id) {
        try{
                $dt = date('ymdHis');
                $dup_roles=array();
                $j=0;
                foreach ($currencyObj as  $value)
                {
                      $field_name=$value->field_name;
                      $field_type=$value->field_type;
                      $field_name=ucfirst(strtolower($field_name));
                      $letter=chr(rand(97,122));
                      $letter.=chr(rand(97,122));
                      $attr_id1=$letter;
                      $attr_id1.=$dt;
                      $attr_id=uniqid($attr_id1);

                      $query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('custom_field','".ucfirst(strtolower($field_name))."','insert','".$module."','')");
                      if($query->num_rows()>0){

                                  $arr1=$query->result_array();
                                  for($i=0;$i<count($arr1);$i++)
                                  {
                                                $dup_roles[$j]['attribute_name'] = $arr1[$i]['attribute_name'];
                                                $dup_roles[$j]['attribute_type'] = $arr1[$i]['attribute_type'];
                                                $j++;
                                  }
                      }
                      else
                      {
                          $query=$GLOBALS['$dbFramework']->query("insert into admin_attributes (attribute_relation_id,attribute_name
                                                                    ,attribute_type,attribute_key,module)
                                                                    values('".$attr_relation_id."','".ucfirst(strtolower($field_name))."',
                                                                    '".$field_type."','".$attr_id."','".$module."')  ");
                      }
                }

                return $dup_roles;


        }catch(LConnectApplicationException $e){
            $GLOBALS['$log']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
    /* -- Edit and Update Custom fields for leads/customers/opportunity/users -- */
    public function update_data($param,$id) {
        try{
                    $query=$GLOBALS['$dbFramework']->query("call common_check_duplicate('custom_field','".$param['attribute_name']."','".$id."','".$param['module']."','update');");
                    if($query->num_rows()>0)
                    {
                         return $query->num_rows();

                    }
                    else
                    {
                        $update = $GLOBALS['$dbFramework']->update('admin_attributes' ,$param, array('LOWER(attribute_key)' => strtolower($id)));
                        return 0;
                    }
        }catch(LConnectApplicationException $e){
            $GLOBALS['$log']->debug('!!!Exception Thrown to Model --- Passing to Controller!!!');
            throw $e;
        }
    }
}
?>