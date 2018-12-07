<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/..');

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('standard_OppAnalysisController');

class standard_OppAnalysisController extends CI_Controller{
	public function __construct(){
	    parent::__construct();
        $this->load->helper('url');
        $this->load->model('reports/standard_OppAnalysisModel','OppModel');
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
     public function get_opportunity()
     {
          if($this->session->userdata('uid'))
          {
            try
            {
                $manager_info = $this->OppModel->get_opportunity($this->session->userdata('uid'));
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
    public function fetch_filter_data()
    {
       if($this->session->userdata('uid')){
        try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $data_array=array(
                          'userid'=>$this->session->userdata('uid'),
                          'type_name'=>$data->filter
                  );
                  if(isset($data->product) && $data->product!=''){
                     $data_array['product']=$data->product;
                  }if(isset($data->industry) && $data->industry!=''){
                     $data_array['industry']=$data->industry;
                  }if(isset($data->bus_location) && $data->bus_location!=''){
                     $data_array['bus_location']=$data->bus_location;
                  }

                  $result = $this->OppModel->fetch_filter_data($data_array);
                  echo json_encode($result);
        }catch(LConnectApplicationException $e){
                        $this->ExceptionHandler($e);
               }
        }else{
                redirect('indexController');
        }
    }
    public function generateReport()
    {
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    switch($data->type)
                    {
                        Case 'opp_timeanalysis':
                              $reportdata = $this->OppModel->generateReport($this->session->userdata('uid'),$data);
                              echo json_encode($reportdata);
                        break;
                        Case 'opp_velocity_analysis':
                              $reportdata = $this->OppModel->generateReport($this->session->userdata('uid'),$data);
                              echo json_encode($reportdata);
                        break;

                    }
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
                        Case 'opp_timeanalysis':
                                                $jsonfilter=array(
                                                    'opportunity'=>$data->opportunity,
                                                    'selectiontype'=>$data->selectiontype,
                                                    'startdate'=>$data->startDate,
                                                    'enddate'=>$data->endDate
                                                 );
                                                 if($data->subtype=='Details')
                                                 {
                                                    $jsonfilter['selectiontypeid']=$data->subtype_Id;
                                                 }

                        break;
                        Case 'opp_velocity_analysis':
                                                $jsonfilter=array(
                                                    'product'=>$data->product,
                                                    'industry'=>$data->industry,
                                                    'bus_location'=>$data->bus_location,
                                                    'sell_type'=>$data->sell_type,
                                                    'startdate'=>$data->startDate,
                                                    'enddate'=>$data->endDate
                                                 );
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
                    $reportdata = $this->OppModel->common_reportsave($data,$this->session->userdata('uid'),$id);
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
                            $id=$data->id;
                            $reportdata = $this->OppModel->get_savedreportdetails($this->session->userdata('uid'),$type,$id);

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