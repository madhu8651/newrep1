<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_support_customController');

class admin_support_customController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('admin_support_customModel','support_custom');
    }
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('admin_support_custom_view');
        }else{
            redirect('indexController');
        }
 
    }
    public function get_buyerperson(){
        if($this->session->userdata('uid')){
            try{
                $buyer = $this->support_custom->view_data();
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
                    $supportAttributesType = $data->supportAttributesType;
                    $optionList1 = $data->optionList;
                    $buyerpID=strtoupper(substr($buyerpName,0,2));
                    $buyerpID.=$dt;
                    $buyerpID = uniqid($buyerpID);
                    $data = array(
                        'support_attribute_id' => $buyerpID,
                        'support_attribute_name' => $buyerp_Name,
                        'support_attribute_type' => $supportAttributesType,
                        'listvalues' =>$optionList1
                    );
                    $insert = $this->support_custom->insert_data($data,$buyerp_Name);
                    if($insert==1){
                        $buyer = $this->support_custom->view_data();
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
                  $support_attribute_id = $data->support_attribute_id;
                  $optionList = $data->optionList;

                  $data = array(
                      'listvalues' => $optionList
                  );
                  $update = $this->support_custom->update_data($support_attribute_id,$data);
                  if($update==1){
                      $buyer = $this->support_custom->view_data();
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
}
?>

