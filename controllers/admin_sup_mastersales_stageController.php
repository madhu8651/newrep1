<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_sup_mastersales_stageController');

class admin_sup_mastersales_stageController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        //$this->output->enable_profiler(true);
        $this->load->model('admin_sup_mastersales_stageModel','mastersales_stage');
    }
    public function index(){
        if($this->session->userdata('uid')){
         $this->load->view('admin_sup_mastersales_stage_view');
        }else{
            redirect('indexController');
        }
    }
    public function get_data(){
        if($this->session->userdata('uid')){
            try{
                $cycle_namedata = $this->mastersales_stage->get_data();
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

    public function get_data2(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $cycleid = $data->cycleid;

                    $cycle_namedata = $this->mastersales_stage->get_data2($cycleid);
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

    public function get_cycle_name(){
        if($this->session->userdata('uid')){
            try{
                $cycle_namedata = $this->mastersales_stage->cyclename_data();
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
    public function post_data() {
        if($this->session->userdata('uid')){
                try{
                      $json = file_get_contents("php://input");
                      $data = json_decode($json);

                      $dt = date('ymdHis');
                      $stageName = $data->stage_name;
                      $cycleId = $data->CYCLE_NAME;
                      $description = $data->description;
                      $stageID=strtoupper(substr($stageName,0,1));
                      $stageID.=$dt;
                      $stageid=uniqid($stageID);
                      $data1 = array(
                          'stage_id' => $stageid,
                          'stage_name' => $stageName
                      );
                      $dt1 = date('ymdHis');
                      $stage_cycleid=uniqid($dt1);
                      $data2 = array(
                          'stage_cycle_id' => $stage_cycleid,
                          'cycle_id' => $cycleId,
                          'stage_id' => $stageid,
                          'remarks' => $description
                      );
                      $insert = $this->mastersales_stage->insert_data($data1,$data2,$stageName,$cycleId,$stageid);
                      echo json_encode($insert);
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
                  $cycleId=$data->cycleid;
                  $cycle_namedata = $this->mastersales_stage->get_data1($cycleId);
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
    public function update_data(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $dt = date('ymdHis');
                    $stage_name=$data->stage_name;
                    $description=$data->description;
                    $stage_id=$data->stage_id;
                    $edit_cycleid=$data->edit_cycleid;
                    $updateque=$this->mastersales_stage->update_data($stage_id,$stage_name,$edit_cycleid,$description);
                    $tabledata=$updateque['records'];
                    $str=$updateque['str'];
                    echo  json_encode($updateque);
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
    public function update_roworder(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $orderselected=$data->orderselected;
                    $hid_cycleid=$data->hid_cycleid;
                    $update_roworder=$this->mastersales_stage->update_roworder($orderselected,$hid_cycleid);
                    if($update_roworder==TRUE){
                     $get_update_data=$this->mastersales_stage->get_update_data($hid_cycleid);
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

    public function get_cycle(){
        if($this->session->userdata('uid')){
            try{
                    $cycle_data = $this->mastersales_stage->get_cycle();
                    echo json_encode($cycle_data);
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
    public function create_duplicate(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $from=$data->from; // already defined cycle and stage
                    $to=$data->to; // duplicate stages to this cycle

                    $create_duplicate = $this->mastersales_stage->create_duplicate($from,$to);
                    echo json_encode($create_duplicate);
            }catch (LConnectApplicationException $e){
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