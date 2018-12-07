<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_dashboardsettingController');

class manager_dashboardsettingController extends Master_Controller{
     public function __construct()
     {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('manager_dashboardsettingModel','dashboard_Model');
    }
     public function index()
     {
         if($this->session->userdata('uid')){
            $this->load->view('newchart');
        }else{
            redirect('indexController');
        }
    }
     public function index1()
     {
         if($this->session->userdata('uid')){
            $this->load->view('dasboard_settings');
        }else{
            redirect('indexController');
        }
    }
     public function get_reportname_tabledata(){
        if($this->session->userdata('uid')){
           try{
                    $reportnames = $this->dashboard_Model->fetch_reportnames();
                    echo json_encode($reportnames);
            }
            catch (LConnectApplicationException $e)  {
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
    public function get_subparam($para)
    {
        if($this->session->userdata('uid')){
           try{
                    $reportingto = $this->dashboard_Model->fetch_reportingto($this->session->userdata('uid'),$para);
                    echo json_encode($reportingto);
            }
            catch (LConnectApplicationException $e)  {
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
     public function post_data()
     {
         if($this->session->userdata('uid')){
               try{
                      $json = file_get_contents("php://input");
                      $data = json_decode($json);

                      $settingid=uniqid(date('ymdHis'));

                      $report_id=$data->report_id;
                      $report_name=$data->report_name;
                      $chart_type=$data->chart_type;
                      $parameter=$data->parameter;
                      $sub_parameter=$data->sub_parameter;
                      $period=$data->period;
                      $datatype=$data->datatype;
                      $target=$data->target;
                      $criteria=$data->criteria;
                      $flag=$data->flag;
                      $dis_area=$data->dis_area;
                      if($period=='Custom')
                      {
                        $start_date=date('Y-m-d',strtotime($data->start_date));
                        $end_date=date('Y-m-d',strtotime($data->end_date));
                      }
                      else
                      {
                        $start_date=date('Y-m-d',strtotime('1970-01-01'));
                        $end_date=date('Y-m-d',strtotime('1970-01-01'));
                      }
                      $module_id=0;
                      $module_id=$this->dashboard_Model->getmodule_id($_SESSION['active_module_name']);

                      if($module_id)
                      {
                             $data = array(
                                  'dash_repo_id' => $report_id,
                                  'chart_type' => $chart_type,
                                  'select_param' => $parameter,
                                  'select_sub_param' => $sub_parameter,
                                  'frequecy' => $period,
                                  'DATA' => $datatype,
                                  'target' => $target,
                                  'criteria' => $criteria,
                                  'flag_value' => $flag,
                                  'display_area' => $dis_area,
                                  'user_id'=> $this->session->userdata('uid'),
                                  'dash_repo_setting_id'=>$settingid,
                                  'updatetime'=>date('Y-m-d'),
                                  'module_id'=>$module_id,
                                  'start_date'=>$start_date,
                                  'end_date'=>$end_date
                            );
                            $insert= $this->dashboard_Model->insert_data($data);

                            echo json_encode($insert);

                      }
                 }
                 catch (LConnectApplicationException $e)  {
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
     function loadtable()
     {
          if($this->session->userdata('uid')){
           try{
                    $loadtabledata = $this->dashboard_Model->loadtabledata($this->session->userdata('uid'));
                    echo json_encode($loadtabledata);
            }
            catch (LConnectApplicationException $e)  {
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
     function  edit_data()
    {
         if($this->session->userdata('uid')){
               try{
                      $json = file_get_contents("php://input");
                      $data = json_decode($json);
                       //print_r($data);
                      $settingid=uniqid(date('ymdHis'));

                      $report_id=$data->report_id;
                      $report_name=$data->report_name;
                      $chart_type=$data->chart_type;
                      $parameter=$data->parameter;
                      $sub_parameter=$data->sub_parameter;
                      $period=$data->period;
                      $datatype=$data->datatype;
                      $target=$data->target;
                      $criteria=$data->criteria;
                      $flag=$data->flag;
                      $dis_area=$data->dis_area;
                      $edit_id=$data->id;
                      if($period=='Custom')
                      {
                        $start_date=date('Y-m-d',strtotime($data->start_date));
                        $end_date=date('Y-m-d',strtotime($data->end_date));
                      }
                      else
                      {
                        $start_date=date('Y-m-d',strtotime('1970-01-01'));
                        $end_date=date('Y-m-d',strtotime('1970-01-01'));
                      }
                      $module_id=0;
                      $module_id=$this->dashboard_Model->getmodule_id($_SESSION['active_module_name']);

                      if($module_id)
                      {
                             $data = array(
                                  'dash_repo_id' => $report_id,
                                  'chart_type' => $chart_type,
                                  'select_param' => $parameter,
                                  'select_sub_param' => $sub_parameter,
                                  'frequecy' => $period,
                                  'DATA' => $datatype,
                                  'target' => $target,
                                  'criteria' => $criteria,
                                  'flag_value' => $flag,
                                  'display_area' => $dis_area,
                                  'user_id'=> $this->session->userdata('uid'),
                                  'dash_repo_setting_id'=>$settingid,
                                  'updatetime'=>date('Y-m-d'),
                                  'module_id'=>$module_id,
                                  'start_date'=>$start_date,
                                  'end_date'=>$end_date
                            );
                            $update= $this->dashboard_Model->update_data($data,$edit_id);

                            echo json_encode($update);

                      }
                 }
                 catch (LConnectApplicationException $e)  {
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
    function swap_data()
    {
        if($this->session->userdata('uid')){
               try{
                      $json = file_get_contents("php://input");
                      $data = json_decode($json);

                      $current_id=$data->current_id;
                      $current_area=$data->current_area;
                      $new_area=$data->new_area;
                      $new_id=$data->new_id;

                      $module_id=0;
                      $module_id=$this->dashboard_Model->getmodule_id($_SESSION['active_module_name']);

                      if($module_id)
                      {
                            $swap= $this->dashboard_Model->swap_data($current_id,$current_area,$new_area,$new_id);
                            echo json_encode($swap);
                      }
                 }
                 catch (LConnectApplicationException $e)  {
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
    function delete_data($delete_id)
    {
       if($this->session->userdata('uid')){
           try{
                    $deleted_data = $this->dashboard_Model->delete_data($this->session->userdata('uid'),$delete_id);
                    echo json_encode($deleted_data);
            }
            catch (LConnectApplicationException $e)  {
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
    function get_tilecount($tilecount)
    {
       if($this->session->userdata('uid')){
           try{
                    $update_data = $this->dashboard_Model->update_tilecount($this->session->userdata('uid'),$tilecount);
                    echo json_encode($update_data);
            }
            catch (LConnectApplicationException $e)  {
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

    /* loading graphs on dashboard */
    public function loadgraphs()
    {
         if($this->session->userdata('uid')){
           try{
                    $loadtabledata = $this->dashboard_Model->loadgraphs($this->session->userdata('uid'));
                    echo json_encode($loadtabledata);
            }
            catch (LConnectApplicationException $e)  {
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
