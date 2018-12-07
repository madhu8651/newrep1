<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/..');

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('standard_ManAnalysisController');

class standard_ManAnalysisController extends CI_Controller{
	public function __construct(){
	    parent::__construct();
        $this->load->helper('url');
        $this->load->model('reports/standard_ManAnalysisModel','manModel');
	}
	public function index(){
			$this->load->view('manager_work_pattern_analysis_view');
	}
     public function get_employees()
     {
          if($this->session->userdata('uid')){
            try{
                $manager_info = $this->manModel->view_employees($this->session->userdata('uid'));
                echo json_encode($manager_info);
            }
            catch(LConnectApplicationException $e){
                $this->ExceptionHandler($e);
            }
          }else{
              redirect('indexController');
          }
    }


    public function generateReport()
    {
             if($this->session->userdata('uid'))
             {
                try
                {
                        $json = file_get_contents("php://input");
                        $data = json_decode($json);
                        $type=$data->type;
                        $subtype=$data->subtype;
                        if($type=='assign_analysis')
                        {
                            //  $reportdata = $this->manModel->generateReport($this->session->userdata('uid'),$type,$subtype,$user,$startdate,$enddate,$selection_type);
                              $reportdata = $this->manModel->generateReport($this->session->userdata('uid'),$data);
                              echo json_encode($reportdata);
                        }
                }
                catch(LConnectApplicationException $e)
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
            }
            else
            {
                redirect('indexController');
            }
    }
      public function save_report($reporttype)
    {
          if($this->session->userdata('uid'))
             {
                try
                {
                            $json = file_get_contents("php://input");
                            $data = json_decode($json);
                            $id=$data->id;
                           if($reporttype=='assign_analysis')
                           {
                                $jsonfilter=array(
                                    'userid'=>$data->user,
                                    'selectiontype'=>$data->selection_type,
                                    'startdate'=>$data->startDate,
                                    'enddate'=>$data->endDate
                                 );
                           }
                      $data=array
                      (
                           'report_id'=>$data->reportid,
                           'report_name'=>$data->report_name,
                           'report_parent_id'=>$data->report_parent_id,
                           'manager_id'=>$this->session->userdata('uid'),
                           'filters'=>json_encode($jsonfilter),
                           'remarks'=>''
                      );
                      if($id=='0')
                      {
                              $reportdata = $this->manModel->common_reportsave($data,$this->session->userdata('uid'),'');
                              echo json_encode($reportdata);
                      }
                      else
                      {
                              $reportdata = $this->manModel->common_reportsave($data,$this->session->userdata('uid'),$id);
                              echo json_encode($reportdata);
                      }
                }
                catch(LConnectApplicationException $e)
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
            }
            else
            {
                redirect('indexController');
            }
    }
    public function getsaved_reports()
    {
            if($this->session->userdata('uid'))
             {
                try
                {
                            $json = file_get_contents("php://input");
                            $data = json_decode($json);
                            $type=$data->type;
                            $subtype=$data->subtype;
                            $id=$data->id;

                            $reportdata = $this->manModel->get_savedreportdetails($this->session->userdata('uid'),$type,$subtype,$id);
                            echo json_encode($reportdata);
                }
                catch(LConnectApplicationException $e)
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
            }
            else
            {
                redirect('indexController');
            }
    }




}

?>