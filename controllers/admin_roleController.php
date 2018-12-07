<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_roleController');

class admin_roleController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('admin_roleModel','role');
    }
    public function index(){
        if($this->session->userdata('uid')){
            $this->load->view('admin_list_userrole_view');
        }else{
            redirect('indexController');
        } 
    }
    public function get_userrole($datatype){
        if($this->session->userdata('uid')){
             try{

                    if($datatype=='getdept'){
                        $userrole = $this->role->view_role();
                        echo json_encode($userrole);
                    }else{
                        $json = file_get_contents("php://input");
                        $data = json_decode($json);
                        $deptid = $data->deptid;
                        $userrole = $this->role->view_role1($deptid);
                        echo json_encode($userrole);
                    }

                }
                catch (LConnectApplicationException $e)
                {
                            $GLOBALS['$log']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                            $errorArray = array(
                            'errorCode' => $e->getErrorCode(),
                            'errorMsg' => $e->getErrorMessage()
                            );
                            $GLOBALS['$log']->debug('Exception JSON to view - '.json_encode($errorArray));
                            $GLOBALS['$log']->debug("/-------------------------------------------------------------------------/");
                            echo json_encode($errorArray);
                }
        }else{
            redirect('indexController');
        }
    }
    public function get_department(){
        if($this->session->userdata('uid')){
                  try{
                          $userdept = $this->role->view_department();
                          echo json_encode($userdept);
                     }
                    catch (LConnectApplicationException $e)
                    {
                            $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                            $errorArray = array(
                            'errorCode' => $e->getErrorCode(),
                            'errorMsg' => $e->getErrorMessage()
                            );
                            $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                            $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                            echo json_encode($errorArray);
                    }
        }else{
            redirect('indexController');
        }
    }
    public function post_data() {

    if($this->session->userdata('uid')){
         try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $roleobj = $data->roleobj;
                  $department_id=$data->deprt_id;
                  $insert = $this->role->insert_data($roleobj,$department_id);
                  $department_id=$insert['departmentid'];
                  $getdata = $this->role->view_role1($department_id);
                  $a=array(
                       'getdata'=>$getdata,
                       'dup_roles'=>$insert['duproles']
                  );
                  echo json_encode($a);
          }
          catch (LConnectApplicationException $e)
          {
                  $GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                  $errorArray = array(
                  'errorCode' => $e->getErrorCode(),
                  'errorMsg' => $e->getErrorMessage()
                  );
                  $GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
                  $GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
                  echo json_encode($errorArray);
          }
        }else{
            redirect('indexController');
        }

    }
    public function update_data(){
        if($this->session->userdata('uid')){
         try{
                $json = file_get_contents("php://input");
                $data = json_decode($json);

                $roleName = $data->rolename;
                $deptid = $data->deprt_id;
                $roleid = $data->roleid;
                $data = array(
                'role_name' => $roleName,
                'department_id' => $deptid
                );
                $update = $this->role->update_data($data,$roleid, $roleName,$deptid);
                      if($update==0){
                        $get_roledata = $this->role->view_role1($deptid);
        			    echo json_encode($get_roledata);
                      }
                      else
                      {
                            $get_roledata=0;
                            echo json_encode($get_roledata);
                      }
             }
             catch (LConnectApplicationException $e)
             {
					$GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
					$errorArray = array(
							'errorCode' => $e->getErrorCode(),
							'errorMsg' => $e->getErrorMessage()
					);
					$GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
					$GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
					echo json_encode($errorArray);
			 }
        }else{
            redirect('indexController');
        }
    }
}
?>

