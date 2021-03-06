<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_sup_sales_stageController');

class admin_sup_sales_stageController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_sup_sales_stageModel','sales_stage');
    }
    public function index(){
        if($this->session->userdata('uid')){
         $this->load->view('admin_sup_sales_stage_view');
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
    public function get_data2(){
        if($this->session->userdata('uid')){
            try{

                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $cycleid=$data->cycleid;
                    $cycle_namedata = $this->sales_stage->get_data2($cycleid);
                    echo json_encode($cycle_namedata);
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
    public function get_cycle_name(){
        if($this->session->userdata('uid')){
            try{
                    $cycle_namedata = $this->sales_stage->cyclename_data();
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
                  $description = $data->description;
                  $masterid = $data->masterid;
                  $seqid = $data->seqid;


                  $stageID=strtoupper(substr($stageName,0,1));
                  $stageID.=$dt;
                  $stageid=uniqid($stageID);
                  $data1 = array(
                      'stage_id' => $stageid,
                      'stage_name' => $stageName,
                  );
                  $dt1 = date('ymdHis');
                  $stage_cycleid=uniqid($dt1);
                  $data2 = array(
                      'stage_cycle_id' => $stage_cycleid,
                      'cycle_id' => $cycleId,
                      'stage_id' => $stageid,
                      'remarks' => $description,
                      'master_stageid' => $masterid,
                      'mapseq' => $seqid
                  );
                  $insert = $this->sales_stage->insert_data($data1,$data2,$stageName,$cycleId,$stageid);
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
                    $cycle_namedata = $this->sales_stage->get_data1($cycleId);
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

                    $masterid = $data->masterid;
                    $seqid = $data->seqid;

                    $updateque=$this->sales_stage->update_data($stage_id,$stage_name,$edit_cycleid,$description,$masterid,$seqid);
                    $tabledata=$updateque['records'];
                    $str=$updateque['str'];
                    //echo  json_encode($updateque['str'])."<@@>".json_encode($updateque['records']);
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
                    $update_roworder=$this->sales_stage->update_roworder($orderselected,$hid_cycleid);
                    if($update_roworder==TRUE){
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
    public function get_master_stage(){

        if($this->session->userdata('uid')){
            try{
                    $cycleid=$this->input->post('id');
                    $status=$this->input->post('status');
                    $stgseq=$this->input->post('stgseq');
                    $cycle_namedata = $this->sales_stage->get_master_stage($cycleid,$status,$stgseq);
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
    public function get_master_stage_edit(){

        if($this->session->userdata('uid')){
            try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $id=$data->id;
                  $prevId=$data->prevId;
                  $nextId=$data->nextId;
                  $activeCycleID=$data->activeCycleID;
                  $cycle_namedata = $this->sales_stage->get_master_stage_edit($id,$prevId,$nextId,$activeCycleID);
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
    public function get_cycle(){
        if($this->session->userdata('uid')){
            try{
                    $cycle_data = $this->sales_stage->get_cycle();
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

                    $create_duplicate = $this->sales_stage->create_duplicate($from,$to);
                    echo json_encode($create_duplicate);
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