<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_support_processController');

class admin_support_processController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('admin_support_processModel','support_process');
    }
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('admin_support_process_view');
        }else{
            redirect('indexController');
        }
 
    }
    public function get_buyerperson(){
        if($this->session->userdata('uid')){
            try{
                $buyer = $this->support_process->view_data();
                echo json_encode($buyer);
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
                    $buyerp_Name = $data->buyerp_name;
                    $buyerpName=$buyerp_Name;
                    $buyerpKey = $data->buyerp_count;
                    $buyerpID=strtoupper(substr($buyerpName,0,2));
                    $buyerpID.=$dt;
                    $buyerpID = uniqid($buyerpID);
                    $data = array(
                        'lookup_id' => $buyerpID,
                        'lookup_name' => 'support_process',
                        'lookup_key' => $buyerpKey,
                        'lookup_value' =>$buyerpName
                    );
                    $insert = $this->support_process->insert_data($data,$buyerpName);
                    if($insert==1){
                        $buyer = $this->support_process->view_data();
                        echo json_encode($buyer);
                    }
                     else
                    {
                        $buyer="false";
                        echo json_encode($buyer);
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
                  $buyerp_Name = ucfirst($data->buyerp_name);
                  $buyerpName=$buyerp_Name;
                  $buyerpID = $data->buyerp_id;

                  $data = array(
                      'lookup_value' => $buyerpName
                  );
                  $update = $this->support_process->update_data($buyerpID,$data,$buyerpName);
                  if($update==1){
                      $buyer = $this->support_process->view_data();
                      echo json_encode($buyer);
                  }
                   else
                  {
                      $buyer="false";
                      echo json_encode($buyer);
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

    public function update_tg_bit(){

        if($this->session->userdata('uid')){
          try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $status = $data->status;
                    $lookupid = $data->lookupid;
                    $toggleid = $data->toggleid;
                    $update = $this->support_process->update_tg_bit($lookupid,$toggleid,$status);

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
}
?>

