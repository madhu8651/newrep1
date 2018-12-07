<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_sup_salescycle_parameterController');

class admin_sup_salescycle_parameterController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        //$this->output->enable_profiler(true);
        $this->load->model('admin_sup_salescycle_parameterModel','sup_cycle');
    }
    public function index(){
        if($this->session->userdata('uid')){
         $this->load->view('admin_sup_salescycle_parameterView');
        }else{
            redirect('indexController');
        }
    }
    public function display_data(){
        if($this->session->userdata('uid')){
          try{
                $sales = $this->sup_cycle->view_data();
                echo json_encode($sales);
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
    public function department(){
        if($this->session->userdata('uid')){
          try{
                $department = $this->sup_cycle->dept_data();
                echo json_encode($department);
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
    public function teams(){
        if($this->session->userdata('uid')){
          try{
                $deptid = $this->input->post('id');
                $teams = $this->sup_cycle->team_data($deptid);
                echo json_encode($teams);
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
     public function product_data(){
        if($this->session->userdata('uid')){
          try{
                $teamid = $this->input->post('id');
                $product = $this->sup_cycle->product_data($teamid);
                echo json_encode($product);
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
    public function product(){

        if($this->session->userdata('uid')){
            try{
                  $industry_data = $this->sup_cycle->view_productdata();
                  $json = array();
                  $hkey11="";
                  for($i=0;$i<count($industry_data);$i++){

                      $json[$i]['id'] = $industry_data[$i]->hkey2;
                      $json[$i]['name'] = $industry_data[$i]->hvalue2;
                      $a=$industry_data[$i]->hkey1;
                      if($a=='0'){
                          $json[$i]['parent'] = "";
                      }else{
                        $json[$i]['parent'] = $industry_data[$i]->hkey1;
                      }

                      $json[$i]['checked'] = false;
                      $json[$i]['nameAttr'] = "Addprod";

                  }
                  echo json_encode($json);
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
    public function industry(){
        if($this->session->userdata('uid')){
             try{
                  $industry_data = $this->sup_cycle->view_industrydata();
                  $json = array();
                  $hkey11="";
                  for($i=0;$i<count($industry_data);$i++){

                      $json[$i]['id'] = $industry_data[$i]->hkey2;
                      $json[$i]['name'] = $industry_data[$i]->hvalue2;
                      $a=$industry_data[$i]->hkey1;
                      if($a=='0'){
                          $json[$i]['parent'] = "";
                      }else{
                        $json[$i]['parent'] = $industry_data[$i]->hkey1;
                      }

                      $json[$i]['checked'] = false;
                      $json[$i]['nameAttr'] = "Addindus";

                  }
                  echo json_encode($json);
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
    public function locations(){
        if($this->session->userdata('uid')){
            try{
                  $location_data = $this->sup_cycle->view_locationdata();
                  $json = array();
                  $hkey11="";
                  for($i=0;$i<count($location_data);$i++){

                      $json[$i]['id'] = $location_data[$i]->hkey2;
                      $json[$i]['name'] = $location_data[$i]->hvalue2;
                      $a=$location_data[$i]->hkey1;
                      if($a=='0'){
                          $json[$i]['parent'] = "";
                      }else{
                        $json[$i]['parent'] = $location_data[$i]->hkey1;
                      }

                      $json[$i]['checked'] = false;
                      $json[$i]['nameAttr'] = "Addbloc";

                  }
                  echo json_encode($json);
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
    public function get_cycle(){
        if($this->session->userdata('uid')){
          try{
                $cyclename = $this->sup_cycle->cycle_data();
                echo json_encode($cyclename);
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

                      $salescyle= $data->salescyle;
                      $cycledata=explode("_",$salescyle);
                      $cycleid=$cycledata[0];
                      $toggleid = $cycledata[1];

                      $ind_parent=$data->ind_parent;
                      $bus_parent=$data->bus_parent;
                      $pro_parent=$data->pro_parent;
                      $listCombId=$data->listCombId; //string of combination

                      $listCombId=rtrim($listCombId,":");
                      $listCombId1=explode(":",$listCombId);
                      $maindata1=array();

                      $dt = date('ymdHis');
                      $letter=chr(rand(97,122));
                      $letter.=chr(rand(97,122));
                      $paramID=$letter;
                      $paramID.=$dt;
                      $parameterid= uniqid($paramID);

                      for($a=0;$a<count($listCombId1);$a++){

                           $listCombId2=explode(",",$listCombId1[$a]);

                           $maindata = array(

                                'parameter_id' => $parameterid,
                                'cycle_id' => $cycleid,
                                'parameter_product' => $pro_parent,
                                'parameter_product_node' => $listCombId2[0],
                                'parameter_industry' => $ind_parent,
                                'parameter_industry_node' => $listCombId2[1],
                                'parameter_location' => $bus_parent,
                                'parameter_location_node' => $listCombId2[2],
                                'parameter_for' => $listCombId2[3],
                                'cycle_togglebit' => $toggleid
                           );

                                array_push($maindata1,$maindata);
                      }


                      $delDuplicate=$this->sup_cycle->delDuplicate($listCombId);
                      $insert = $this->sup_cycle->insert_data($maindata1);
                      echo json_encode($insert);
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

        public function get_productdata_edit(){
          if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $paramid=$data->cycle_id;
                    $industry_edit=$this->sup_cycle->product_edit($paramid);
                    echo json_encode($industry_edit);

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
    public function get_industry_edit(){
         if($this->session->userdata('uid')){
           try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $paramid=$data->paramid;
                    $industry_edit=$this->sup_cycle->industry_edit($paramid);
                    echo json_encode($industry_edit);
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
    public function get_business_edit(){
          if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $paramid=$data->paramid;
                    $business_edit=$this->sup_cycle->business_edit($paramid);
                    echo json_encode($business_edit);
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

                    $salescyle= $data->salescyle;
                    $hid_parameterid= $data->hid_parameterid;
                    $cycledata=explode("_",$salescyle);
                    $cycleid=$cycledata[0];
                    $toggleid = $cycledata[1];

                    $ind_parent=$data->ind_parent;
                    $bus_parent=$data->bus_parent;
                    $pro_parent=$data->pro_parent;
                    $listCombId=$data->listCombId; //string of combination

                    $listCombId=rtrim($listCombId,":");
                    $listCombId1=explode(":",$listCombId);
                    $maindata1=array();

                    $dt = date('ymdHis');
                    $letter=chr(rand(97,122));
                    $letter.=chr(rand(97,122));
                    $paramID=$letter;
                    $paramID.=$dt;
                    $parameterid= uniqid($paramID);

                    for($a=0;$a<count($listCombId1);$a++){

                         $listCombId2=explode(",",$listCombId1[$a]);

                         $maindata = array(
                              'parameter_id' => $parameterid,
                              'cycle_id' => $cycleid,
                              'parameter_product' => $pro_parent,
                              'parameter_product_node' => $listCombId2[0],
                              'parameter_industry' => $ind_parent,
                              'parameter_industry_node' => $listCombId2[1],
                              'parameter_location' => $bus_parent,
                              'parameter_location_node' => $listCombId2[2],
                              'parameter_for' => $listCombId2[3],
                              'cycle_togglebit' => $toggleid
                         );

                              array_push($maindata1,$maindata);
                    }
                    $delDuplicate=$this->sup_cycle->delDuplicate($listCombId);
                    $insert = $this->sup_cycle->update_data($maindata1,$hid_parameterid);
                    echo json_encode($insert);
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

    /* -------------------- new changes 23.08.2017 --------------------------- */

     public function checkDuplicate($status){

           if($this->session->userdata('uid')){
                try{
                          $json = file_get_contents("php://input");
                          $data = json_decode($json);

                          $listCombId=$data->listCombId;
                          $hid_parameterid=$data->hid_parameterid;
                          $checkDuplicate=$this->sup_cycle->checkDuplicate($listCombId,$status,$hid_parameterid);
                          echo json_encode($checkDuplicate);
                          
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

