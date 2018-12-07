<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_customFieldController');

class admin_customFieldController extends Master_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model("admin_customFieldModel","custom");
    }
    public function index(){
        if($this->session->userdata('uid')){
            $this->load->view('admin_custom_field_view');
        }else{
            redirect('indexController');
        } 
    }
/* -- Get list of custom fields and passing on to View -- */
    public function get_data() {
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $pageid = $data->pageid;
                    $custom_field = $this->custom->get_data($pageid);
                    echo json_encode($custom_field);
            }catch(LConnectApplicationException $e){
                $GLOBALS['$log']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                'errorCode' => $e->getErrorCode(),
                'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$log']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$log']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
        }else{
            redirect('indexController');
        }
    }

    /* -- Adding Custom fields for leads/customers/opportunity/users -- */
    public function post_data() {
        if($this->session->userdata('uid')){
            try{
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $module = $data->module;
                switch($module){
                    case 'Lead': $attr_relation_id = "custom_lead_info";
                                break;
                    case 'Customer': $attr_relation_id = "custom_customer_info";
                                break;
                    case 'User': $attr_relation_id = "custom_user_details";
                                break;
                    case 'Opportunity': $attr_relation_id = "custom_opportunity_details";
                                break;
                }

                $currencyObj = $data->currencyObj;
                $insertArray1=array();
                $dt = date('ymdHis');
                $attr_id = $dt;

                $insert = $this->custom->post_data($currencyObj,$module,$attr_relation_id);
                $custom_field = $this->custom->get_data($module);
                $a=array(
                       'getdata'=>$custom_field,
                       'dup_roles'=>$insert
                  );
                  echo json_encode($a);

            }catch(LConnectApplicationException $e){
                $GLOBALS['$log']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                'errorCode' => $e->getErrorCode(),
                'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$log']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$log']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
        }else{
            redirect('indexController');
        }
    }
    /* -- Edit and Update Custom fields for leads/customers/opportunity/users -- */
    public function update_data() {
        if($this->session->userdata('uid')){
            try{
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $module = $data->module;
                $field_name = $data->field_name;
                $field_type = $data->field_type;
                $id = $data->id;
                switch($module){
                    case 'Lead': $attr_relation_id = "custom_lead_info";
                                break;
                    case 'Customer': $attr_relation_id = "custom_customer_info";
                                break;
                    case 'User': $attr_relation_id = "custom_user_details";
                                break;
                    case 'Opportunity': $attr_relation_id = "custom_opportunity_details";
                                break;
                }
                
                $data_array = array(
                    "attribute_relation_id" => $attr_relation_id,
                    "attribute_name" => ucfirst(strtolower($field_name)),
                    "attribute_type" => $field_type,
                    "module" => $module
                );
                $update = $this->custom->update_data($data_array,$id);
                if($update==0){
                        $custom_field = $this->custom->get_data($module);
                        echo json_encode($custom_field);
                }
                else
                {
                        $custom_field=0;
                        echo json_encode($custom_field);
                }
            }catch(LConnectApplicationException $e){
                $GLOBALS['$log']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
                $errorArray = array(
                'errorCode' => $e->getErrorCode(),
                'errorMsg' => $e->getErrorMessage()
                );
                $GLOBALS['$log']->debug('Exception JSON to view - '.json_encode($errorArray));
                $GLOBALS['$log']->debug("/-------------------------------------------------------------------------/");
                echo json_encode($errorArray);
            }
        }else{
            redirect('indexController'); // If session not exist redirect to indexController
        }
    }
}

