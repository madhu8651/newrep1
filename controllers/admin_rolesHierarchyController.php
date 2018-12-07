<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_rolesHierarchyController');

class admin_rolesHierarchyController extends Master_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('admin_rolesHierarchyModel','hierarchy');
    }
    public function index() {
		if($this->session->userdata('uid')){
            $this->load->view('admin_list_hierarchy');
        }else{
            redirect('indexController');
        }
    }
    public function get_user_roles() {
		if($this->session->userdata('uid')){
           try{
        			$roles = $this->hierarchy->get_user_roles();
        			echo json_encode($roles);
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

    public function get_user_rolesE() {
		if($this->session->userdata('uid')){
           try{
                    $json = file_get_contents("php://input");
        			$data = json_decode($json);

        			$role_value=$data->role_value;// array value
        			$roles = $this->hierarchy->get_user_rolesE($role_value);
        			echo json_encode($roles);
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

    public function get_levels() {
		if($this->session->userdata('uid')){
           try{
        			$get_levels = $this->hierarchy->get_levels();
        			echo json_encode($get_levels);
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


    public function get_levelcount(){
		if($this->session->userdata('uid')){
		   try{
        			$get_levelcount=$this->hierarchy->get_levelcount();
        			echo json_encode($get_levelcount);
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

    public function post_data(){
		if($this->session->userdata('uid')){
		   try{
        			$json = file_get_contents("php://input");
        			$data = json_decode($json);

        			$rolesid=$data->rolesid;// array value
        			$level_cnt=$data->levelCount;
        			$status=$data->status;

        			$insert=$this->hierarchy->update_hierarchy($rolesid,$level_cnt,$status);
        			echo json_encode($insert);
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

    public function post_data1(){
		if($this->session->userdata('uid')){
		   try{
        			$json = file_get_contents("php://input");
        			$data = json_decode($json);

        			$rolesid=$data->rolesid;// array value
        			$level_cnt=$data->levelCount;
        			$new_levelcnt=$data->new_levelcnt;
        			$chk=$data->chk;

        			$insert=$this->hierarchy->update_hierarchy1($rolesid,$level_cnt,$new_levelcnt,$chk);
        			echo json_encode($insert);
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

    public function update_reportingdata(){
		if($this->session->userdata('uid')){
		   try{
        			$json = file_get_contents("php://input");
        			$data = json_decode($json);

        			$rep_arr=$data->rep_arr;// array value
        			$level_cnt=$data->levelCount;
        			$rolesid=$data->rolesid;
        			$new_levelcnt=$data->new_levelcnt;

        			$insert=$this->hierarchy->update_reportingdata($rolesid,$level_cnt,$rep_arr,$new_levelcnt);
        			echo json_encode($insert);
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

    public function update_reportingdata1(){
		if($this->session->userdata('uid')){
		   try{
        			$json = file_get_contents("php://input");
        			$data = json_decode($json);

        			$rep_arr=$data->rep_arr;// array value
        			$level_cnt=$data->levelCount;
        			$rolesid=$data->rolesid;
        			$new_levelcnt=$data->new_levelcnt;
                    $status=$data->status;

        			$insert=$this->hierarchy->update_reportingdata1($rolesid,$level_cnt,$rep_arr,$new_levelcnt,$status);
        			echo json_encode($insert);
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

    public function move_roledata(){
		if($this->session->userdata('uid')){
		   try{
        			$json = file_get_contents("php://input");
        			$data = json_decode($json);

        			$rolesid=$data->rolesid;// array value
        			$level_cnt=$data->levelCount;
        			$new_levelcnt=$data->new_levelcnt;

        			$insert=$this->hierarchy->move_roledata($rolesid,$level_cnt,$new_levelcnt);
        			echo json_encode($insert);
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

    public function remove_role(){
        if($this->session->userdata('uid')){
           try{
            			$json = file_get_contents("php://input");
            			$data = json_decode($json);

            			$rolesid=$data->role_id;

            			$remove_role=$this->hierarchy->remove_role($rolesid);
                        if($remove_role==TRUE){
                            echo 0;
                        }else{
                            echo 1;
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
    public function save_order(){
        if($this->session->userdata('uid')){
            try{
          			$json = file_get_contents("php://input");
          			$data = json_decode($json);

          			$orderselected=$data->orderselected;// array value

          			$insert=$this->hierarchy->update_roworder($orderselected);
          			echo json_encode($insert);
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