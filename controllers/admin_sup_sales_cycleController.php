<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_sup_sales_cycleController');

class admin_sup_sales_cycleController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_sup_sales_cycleModel','cycle');
    }
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('admin_sup_sales_cycle_view');
        }else{
            redirect('indexController');
        }
    }
    public function get_cycle(){
        if($this->session->userdata('uid')){
           try{
                    $cycledata = $this->cycle->view_cycledata();
                    echo json_encode($cycledata);
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
    public function get_mastercycle(){
        if($this->session->userdata('uid')){
        try{
                    $mastercycle = $this->cycle->view_mastercycledata();
                    echo json_encode($mastercycle);
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
                    $dt = date('ymdHis');

                    $cyclename = $data->cyclename;
                    $mcycleid = $data->mcycleid;
                    $firstaccesstime= $data->firstaccesstime;
                    $days= $data->days;
                    $cycleID = uniqid($dt);
                    $cycledata = array(
                        'CYCLE_ID' => $cycleID,
                        'CYCLE_NAME' => $cyclename,
                        'MASTERCYCLE_ID' => $mcycleid,
                        'tatdays' =>$days,
                        'tattime' => $firstaccesstime
                    );
                    $insert = $this->cycle->insert_data($cycledata,$cyclename,$cycleID);
                    if($insert==TRUE){
                        echo 0;
                    }
                    else
                    {
                        echo 1;
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


    public function update_data(){
     if($this->session->userdata('uid')){
           try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);

                  $cyclename = $data->cyclename;
                  $mcycleid = $data->mcycleid;
                  $cycleid = $data->cycleid;
                  $firstaccesstime= $data->firstaccesstime;
                  $days= $data->days;

                  $cycledata = array(
                      'CYCLE_NAME' =>$cyclename,
                      'MASTERCYCLE_ID' => $mcycleid,
                      'tatdays' =>$days,
                      'tattime' => $firstaccesstime
                  );
                  $cycledata1 = array(
                      'MASTERCYCLE_ID' => $mcycleid,
                      'tatdays' =>$days,
                      'tattime' => $firstaccesstime
                  );
                  $update = $this->cycle->update_data($cycledata,$cyclename,$cycleid,$cycledata1,$mcycleid);
                  if($update==TRUE){
                       echo 0;
                  }
                  else
                  {
                      echo 1;
                  }
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

    public function check_activecycle(){

        if($this->session->userdata('uid')){
          try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $cycleid = $data->cycle_id;
                    $cyclename = $data->cycle_name;

                    $update = $this->cycle->check_activecycle($cyclename);
                    echo json_encode($update);
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

    public function update_tg_bit(){

        if($this->session->userdata('uid')){
          try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $status = $data->status;
                    if($status=='truechk'){
                            $found_cycleid = $data->found_cycleid;
                            $toggleid = $data->toggleid;
                            $selected_cycle_id = $data->selected_cycle_id;
                            $toggleid1 = $data->toggleid1;

                            $update = $this->cycle->update_tg_bit($found_cycleid,$toggleid,$selected_cycle_id,$toggleid1);

                    }else{
                         $cycle_id= $data->cycle_id;
                         $toggleid = $data->toggleid;
                         $update = $this->cycle->update_tg_bit1($cycle_id,$toggleid);
                    }

                    if($update==TRUE){
                         echo 0;
                    }
                    else
                    {
                        echo 1;
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

    public function resetbit(){

        if($this->session->userdata('uid')){
          try{

                    $update = $this->cycle->cycleresetbit();
                    echo json_encode($update);
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