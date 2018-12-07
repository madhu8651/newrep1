<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';
# added by Harish for tessting SVN

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_currencyController');

class admin_currencyController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_currencyModel','currency');
    }
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('admin_currency_view');
        }else{
            redirect('indexController');
        }     
    }
    public function get_data($datatype){
        if($this->session->userdata('uid')){
            try{
                    if($datatype=='getcatg'){
                            $currency = $this->currency->view_data();
                            echo json_encode($currency);
                    }else{
                            $json = file_get_contents("php://input");
                            $data = json_decode($json);
                            $categoryid = $data->catgid;
                            $currency = $this->currency->view_data1($categoryid);
                            echo json_encode($currency);
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
    public function currency(){
         if($this->session->userdata('uid')){
             try{
                      $addcurrency = $this->currency->get_currency();
                      echo json_encode($addcurrency);
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
     public function add_currency(){
        if($this->session->userdata('uid')){
          try{
                      $json = file_get_contents("php://input");
                      $data = json_decode($json);
                      $dt = date('ymdHis');
                      $categoryid = $data->currency_cat_id;
                      $currencyObj = $data->currencyObj;// array

                      $insert = $this->currency->insert_data($categoryid,$currencyObj);

                      $currency = $this->currency->view_data1($categoryid);
                      $data = array(
                             'dup_currency' => $insert,
                             'currency'=> $currency
                      );
                      echo json_encode($data);
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
     public function update_data(){
        if($this->session->userdata('uid')){
               try{
                      $json = file_get_contents("php://input");
                      $data = json_decode($json);
                      $categoryid = $data->CURRENCY_CATEGORY_ID;
                      $currency = $data->CURRENCY_ID;
                      $currencyid= $data->CURRENCYID;
                         $data = array(
                             'currency_category_id' => $categoryid,
                             'currency_name'=> $currency
                      );
                      $update = $this->currency->update_data($currencyid,$data,$categoryid);

                      if($update==0){
                        $currency = $this->currency->view_data1($categoryid);
        			    echo json_encode($currency);
                      }
                      else
                      {
                            $currency=0;
                            echo json_encode($currency);
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
    
}
?>