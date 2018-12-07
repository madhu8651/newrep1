<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/..');

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('standard_RepAnalysisController');

class standard_RepAnalysisController extends CI_Controller{
	public function __construct(){
	    parent::__construct();
        $this->load->helper('url');
        $this->load->model('reports/standard_RepAnalysisModel','repModel');
	}
	public function index(){
			$this->load->view('manager_work_pattern_analysis_view');
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
     public function get_employees()
     {
          if($this->session->userdata('uid'))
          {
            try
            {
                $manager_info = $this->repModel->view_employees($this->session->userdata('uid'));
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
                  if($data->team!=''){
                     $data_array['team']=$data->team;
                  }
                  if(isset($data->off_location) && $data->off_location!=''){
                     $data_array['off_location']=$data->off_location;
                  }
                  $result = $this->repModel->fetch_filter_data($data_array);
                  echo json_encode($result);
        }catch(LConnectApplicationException $e){
                        $this->ExceptionHandler($e);
               }
        }else{
                redirect('indexController');
        }
    }
    public function get_time()
    {
         if($this->session->userdata('uid'))
             {
                try
                {
                        $json = file_get_contents("php://input");
                        $data = json_decode($json);
                        $user=$data->user;
                        $selecteddate=$data->selectedDate;
                        $time_info = $this->repModel->fetch_time($this->session->userdata('uid'),$user,$selecteddate);
                        echo json_encode($time_info);
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
             if($this->session->userdata('uid'))
             {
                try
                {
                        $json = file_get_contents("php://input");
                        $data = json_decode($json);
                        $type=$data->type;
                        $subtype=$data->subtype;
                        if($type=='Daily_WPA')
                        {
                              $user=$data->user;
                              $selecteddate=$data->selectedDate;
                              $starttime=$data->starttime;
                              $endtime=$data->endtime;
                              $reportdata = $this->repModel->generateReport($this->session->userdata('uid'),$type,$subtype,$user,$selecteddate,$starttime,$endtime);
                              echo json_encode($reportdata);
                        }
                        else if($type=='Activity_Analysis')
                        {
                              $user=$data->user;
                              $startdate=$data->startDate;
                              $enddate=$data->endDate;
                              $subtype=$data->subtype;
                              $status=$data->status;
                              $reportdata = $this->repModel->generateReport($this->session->userdata('uid'),$type,$subtype,$user,$startdate,$enddate,$status);
                              echo json_encode($reportdata);
                        }
                        else if($type=='Time_distribution_analysis')
                        {
                              $user=$data->user;
                              $startdate=$data->startDate;
                              if($subtype=='Detail')
                              {
                                  $selection_type=$data->selection_type;
                                  $reportdata = $this->repModel->generateReport($this->session->userdata('uid'),$type,$subtype,$user,$startdate,'',$selection_type);
                              }
                              else
                              {
                                 $reportdata = $this->repModel->generateReport($this->session->userdata('uid'),$type,$subtype,$user,$startdate,'','');
                              }
                              echo json_encode($reportdata);
                        }
                        else if($type=='Punctuality_Score' || $type=='Productivity_Score' || $type=='Rep_Cost_Analysis')
                        {
                              $startdate=$data->startDate;
                              $enddate=$data->endDate;
                              $subtype=$data->subtype;
                              $filterarray=array(
                                'team'=>$data->team,
                                'location'=>$data->location,
                                'resource'=>$data->resource
                              );
                              $reportdata = $this->repModel->generateReport($this->session->userdata('uid'),$type,$subtype,$filterarray,$startdate,$enddate,'');
                              echo json_encode($reportdata);
                        }

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
                            $reportdata = $this->repModel->get_savedreportdetails($this->session->userdata('uid'),$type,$subtype,$id);
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

    public function save_report($reporttype)
    {
          if($this->session->userdata('uid'))
             {
                try
                {
                            $json = file_get_contents("php://input");
                            $data = json_decode($json);
                            $id=$data->id;
                            $jsonfilter=array(
                                                'userid'=>$this->session->userdata('uid')
                            );
                             if($reporttype=='work_pattern_analysis')
                             {
                                      $user=$data->user;
                                      $selecteddate=$data->selectedDate;
                                      $starttime=$data->starttime;
                                      $starttime  = date("H:i:s", strtotime($starttime));
                                      $endtime=$data->endtime;
                                      $endtime  = date("H:i:s", strtotime($endtime));

                                      $jsonfilter=array(
                                        'userid'=>$user,
                                        'selecteddate'=>$selecteddate,
                                        'starttime'=>$starttime,
                                        'endtime'=>$endtime
                                     );
                            }
                           else if($reporttype=='activity_analysis')
                           {

                                        $jsonfilter=array(
                                          'userid'=>$data->user,
                                          'startdate'=>$data->startDate,
                                          'enddate'=>$data->endDate,
                                          'status'=>$data->status
                                       );
                           }
                           else if($reporttype=='time_distribution_analysis')
                           {
                                        $subtype=$data->subtype;
                                        if($subtype=='Summary')
                                        {
                                             $jsonfilter=array(
                                                'userid'=>$data->user,
                                                'selecteddate'=>$data->startDate
                                             );
                                        }
                                        else
                                        {
                                            $jsonfilter=array(
                                                'userid'=>$data->user,
                                                'selectiontype'=>$data->selection_type,
                                                'selecteddate'=>$data->startDate
                                             );
                                        }
                           }
                           else if($reporttype=='Punctuality_Score' || $reporttype=='Rep_Cost_Analysis')
                           {
                                       $jsonfilter['team']=$data->team;
                                       $jsonfilter['location']=$data->location;
                                       $jsonfilter['resource']=$data->resource;
                                       $jsonfilter['startdate']=$data->startDate;
                                       $jsonfilter['enddate']=$data->endDate;
                           }
                      $data=array
                      (
                           //'actualreportname'=>$data->actual_report_name,
                           'report_id'=>$data->reportid,
                           'report_name'=>$data->report_name,
                           'report_parent_id'=>$data->report_parent_id,
                           'manager_id'=>$this->session->userdata('uid'),
                           'filters'=>json_encode($jsonfilter),
                           'remarks'=>''
                      );
                      if($id=='0')
                      {
                              $reportdata = $this->repModel->common_reportsave($data,$this->session->userdata('uid'),'');
                              echo json_encode($reportdata);
                      }
                      else
                      {
                              $reportdata = $this->repModel->common_reportsave($data,$this->session->userdata('uid'),$id);
                              echo json_encode($reportdata);
                      }
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