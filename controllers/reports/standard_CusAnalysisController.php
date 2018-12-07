<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/..');

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('standard_CusAnalysisController');
 //heloo
class standard_CusAnalysisController extends CI_Controller{
	public function __construct(){
	    parent::__construct();
        $this->load->helper('url');
        $this->load->model('reports/standard_CusAnalysisModel','CusModel');
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
                $manager_info = $this->CusModel->view_employees($this->session->userdata('uid'));
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
    public function get_cust($userid)
    {
         if($this->session->userdata('uid')){
         try{
                $cust_info = $this->CusModel->get_cust($userid);
                echo json_encode($cust_info);
         }
         catch(LConnectApplicationException $e){
                $this->ExceptionHandler($e);
         }
         }
         else{
              redirect('indexController');
          }
    }
    public function generateReport()
    {
             if($this->session->userdata('uid'))
             {
                try{
                        $json = file_get_contents("php://input");
                        $data = json_decode($json);
                        $type=$data->type;
                        $subtype=$data->subtype;
                        switch($type)
                        {
                           case 'Cus_retentioncostanalysis' :
                                  $user=$data->user;
                                  $customer=$data->customer;
                                  if($subtype=='Summary')
                                     $reportdata = $this->CusModel->generateReport($this->session->userdata('uid'),$type,$subtype,$user,$customer,'NA','NA');
                                  else
                                     $reportdata = $this->CusModel->generateReport($this->session->userdata('uid'),$type,$subtype,$user,$customer,$data->lookup_id,'NA');
                                  echo json_encode($reportdata);
                           break;
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

                            $reportdata = $this->CusModel->get_savedreportdetails($this->session->userdata('uid'),$type,$subtype,$id);
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
                             if($reporttype=='Cus_retentioncostanalysis')
                             {
                                      $user=$data->user;
                                      $customer=$data->customer;

                                      $jsonfilter=array(
                                        'userid'=>$user,
                                        'customerid'=>$customer
                                     );
                                     if($data->subtype=='Details')
                                     {
                                        $jsonfilter+=['activityid'=>$data->lookup_id];
                                     }
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

                      $reportdata = $this->CusModel->common_reportsave($data,$this->session->userdata('uid'),$id);
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