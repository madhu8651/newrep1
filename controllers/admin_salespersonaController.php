<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_salespersonaController');

class admin_salespersonaController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('admin_salespersonaModel','person');
    }
    public function index(){
        if($this->session->userdata('uid')){     
        }else{
            redirect('indexController');
        }
        $this->load->view('admin_salespersona_view');
    }
    public function get_salesperson(){
        if($this->session->userdata('uid')){
            try{
                    $person = $this->person->view_data();
                    echo json_encode($person);
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
                  $salespName = $data->salesp_name;
                  $salespKey = $data->salesp_count;
                  $salespID=strtoupper(substr($salespName,0,2));
                  $salespID.=$dt;
                  $salespID = uniqid($salespID);
                  $data = array(
                      'lookup_id' => $salespID,
                      'lookup_name' => 'Sales Persona',
                      'lookup_key' => $salespKey,
                      'lookup_value' => ucfirst(strtolower($salespName))
                  );
                  $insert = $this->person->insert_data($data,$salespName);
                  if($insert==1){
                      $person = $this->person->view_data();
                      echo json_encode($person);
                  }
                  else
                  {
                      $person="false";
                      echo json_encode($person);
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
                  $salespName = $data->salesp_name;
                  $salespID = $data->salesp_id;

                  $data = array(
                   'lookup_value' => ucfirst(strtolower($salespName))
                  );
                  $update = $this->person->update_data($salespID,$data,$salespName);
                  if($update==1){
                   $person = $this->person->view_data();
                   echo json_encode($person);
                  }
                  else
                  {
                   $person="false";
                   echo json_encode($person);
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

