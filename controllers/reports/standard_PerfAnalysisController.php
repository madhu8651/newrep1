<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/..');

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('standard_PerfAnalysisController');

class standard_PerfAnalysisController extends CI_Controller{
	public function __construct(){
	    parent::__construct();
        $this->load->helper('url');
        $this->load->model('reports/standard_PerfAnalysisModel','PerfModel');
	}
    public function ExceptionHandler($e)
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
     public function get_product()
     {
          if($this->session->userdata('uid'))
          {
            try
            {
                $manager_info = $this->PerfModel->get_product($this->session->userdata('uid'));
                echo json_encode($manager_info);
            }
            catch(LConnectApplicationException $e)
            {
                $this->ExceptionHandler($e);
            }
          }
          else
          {
              redirect('indexController');
          }
    }

    public function generateReport()
    {
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $type=$data->type;
                    $subtype=$data->subtype;
                    switch($type)
                    {
                        Case 'prod_performanceanalysis':
                        Case 'opp_performanceanalysis':
                              $reportdata = $this->PerfModel->generateReport($this->session->userdata('uid'),$data);
                              //print_r($reportdata);
                        break;
                    }
                    echo json_encode($reportdata);
                }
            catch(LConnectApplicationException $e){
                    $this->ExceptionHandler($e);
                }
        }
        else{
            redirect('indexController');
        }
    }
    public function save_report($reporttype)
    {
          if($this->session->userdata('uid')){
                try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $id=$data->id;
                    switch($reporttype)
                    {
                        Case 'prod_performanceanalysis':
                                                 $jsonfilter=array(
                                                    'product'=>$data->product,
                                                    'startdate'=>$data->startDate,
                                                    'enddate'=>$data->endDate
                                                 );
                                                 if($data->product=='All' || $data->selectiontype!='')
                                                    $jsonfilter['selectiontype']=$data->selectiontype;
                        break;
                        Case 'opp_performanceanalysis':
                                                $jsonfilter=array(
                                                    'opportunity'=>$data->opportunity,
                                                    'startdate'=>$data->startDate,
                                                    'enddate'=>$data->endDate
                                                 );
                                                 if($data->opportunity=='All')
                                                    $jsonfilter['selectionId']=$data->selectionId;
                        break;
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
                    $reportdata = $this->PerfModel->common_reportsave($data,$this->session->userdata('uid'),$id);
                    echo json_encode($reportdata);

                }
                catch(LConnectApplicationException $e)
                {
                       $this->ExceptionHandler($e);
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
                            $reportdata = $this->PerfModel->get_savedreportdetails($this->session->userdata('uid'),$type,$subtype,$id);
                            echo json_encode($reportdata);
                }
                catch(LConnectApplicationException $e)
                {
                       $this->ExceptionHandler($e);
                }
            }
            else
            {
                redirect('indexController');
            }
    }




}

?>