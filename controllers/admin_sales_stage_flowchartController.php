<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_sales_stage_flowchartController');

class admin_sales_stage_flowchartController extends Master_Controller{
    public function __construct(){
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_sales_stage_flowchartModel','sales_stage');
    }
    public function index(){
        if($this->session->userdata('uid')){
          $this->load->view('admin_sales_stage_flowchart_view');
        }else{
            redirect('indexController');
        }
    }
    public function get_data(){
        if($this->session->userdata('uid')){
            try{
                $cycle_namedata = $this->sales_stage->get_data();
                echo json_encode($cycle_namedata);
            }catch (LConnectApplicationException $e)  {
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

    public function get_data1(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $cycleid=$data->cycleid;
                    $cycle_namedata = $this->sales_stage->get_data1($cycleid);
                    echo json_encode($cycle_namedata);
            }catch (LConnectApplicationException $e)  {
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

    public function get_owner(){
        if($this->session->userdata('uid')){
            try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);

                  $cycleid=$data->cycleid;
                  $ownerdata=$this->sales_stage->get_owner($cycleid);
                  echo json_encode($ownerdata);
            }catch (LConnectApplicationException $e)  {
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
    public function get_qualifier(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $qualid=$data->qualid;
                    $cycleid=$data->cycleid;
                    $qualifier=$this->sales_stage->get_qualifier($qualid,$cycleid);
                    echo json_encode($qualifier);
            }catch (LConnectApplicationException $e)  {
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
    public function get_qualifier1(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $cycleid=$data->cycleid;
                    $qualifier=$this->sales_stage->get_qualifier1($cycleid);
                    echo json_encode($qualifier);
            }catch (LConnectApplicationException $e)  {
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
    public function get_actnstage(){
        if($this->session->userdata('uid')){
            try{
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $stage_seq=$data->stage_seq;
                $cycleid=$data->cycleid;
                $actnstage_data=$this->sales_stage->get_actnstage($stage_seq,$cycleid);
                echo json_encode($actnstage_data);
            }catch (LConnectApplicationException $e)  {
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
                  $hid_stageid=$data->hid_stageid;
                  $hid_cycleid=$data->hid_cycleid;
                  $hid_stage_seq=$data->hid_stage_seq;
                  $stradd_attr_value=$data->stradd_attr_value;// array value
                  $ownervalue=$data->ownervalue;
                  $val_namearr=$data->val_namearr; // array value
                  $val_namearr1=$data->val_namearr1; // array value
                  
                  $insert=$this->sales_stage->save_data($hid_stageid,$hid_stage_seq,$ownervalue,$stradd_attr_value,$val_namearr,$hid_cycleid,$val_namearr1);

                  if($insert==TRUE){
                      $get_update_data=$this->sales_stage->get_update_data($hid_cycleid);
                      echo json_encode($get_update_data);
                  }
            }catch (LConnectApplicationException $e)  {
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
    public function post_desc(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $cycleid=$data->CYCLE_ID;
                    $stage_id=$data->stage_id;
                    $description=$data->description;
                    $post_desc = $this->sales_stage->post_desc($cycleid,$stage_id,$description);
                    echo json_encode($post_desc);

            }catch (LConnectApplicationException $e)  {
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