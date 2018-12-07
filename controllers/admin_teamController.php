<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_teamController');

class admin_teamController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_teamModel','teams');
    }
    public function index(){
        if($this->session->userdata('uid')){
          $this->load->view('admin_team_view');
        }else{
            redirect('indexController');
        }

    }
    public function get_teamdata(){
        if($this->session->userdata('uid')){
          try{
                $team_data = $this->teams->view_data();
                echo json_encode($team_data);
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
    public function department(){
        if($this->session->userdata('uid')){
                     try{
                              $department = $this->teams->dept_data();
                              echo json_encode($department);
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
    public function getselltype(){
        if($this->session->userdata('uid')){
                     try{
                              $getselltype = $this->teams->getselltype();
                              echo json_encode($getselltype);
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
    public function get_productdata(){
        if($this->session->userdata('uid')){
           try{
                      $product_data = $this->teams->view_productdata();
                      $json = array();
                      $hkey11="";
                      for($i=0;$i<count($product_data);$i++){

                          $json[$i]['id'] = $product_data[$i]->hkey2;
                          $json[$i]['name'] = $product_data[$i]->hvalue2;
                          $a=$product_data[$i]->hkey1;
                          if($a=='0'){
                              $json[$i]['parent'] = "";
                          }else{
                            $json[$i]['parent'] = $product_data[$i]->hkey1;
                          }
                          $json[$i]['checked'] = false;
                          $json[$i]['nameAttr'] = 'Addprod';

                      }
                      echo json_encode($json);
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
     public function business_location(){
        if($this->session->userdata('uid')){
           try{
                    $product_data = $this->teams->view_business();
                    $json = array();
                    $hkey11="";
                    for($i=0;$i<count($product_data);$i++){

                        $json[$i]['id'] = $product_data[$i]->hkey2;
                        $json[$i]['name'] = $product_data[$i]->hvalue2;
                        $a=$product_data[$i]->hkey1;
                        if($a=='0'){
                            $json[$i]['parent'] = "";
                        }else{
                          $json[$i]['parent'] = $product_data[$i]->hkey1;
                        }
                        $json[$i]['checked'] = false;
                        $json[$i]['nameAttr'] = 'Addbusiness';

                    }
                    echo json_encode($json);
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
    public function get_industry(){
        if($this->session->userdata('uid')){
           try{
                  $product_data = $this->teams->view_industry();
                  $json = array();
                  $hkey11="";
                  for($i=0;$i<count($product_data);$i++){

                      $json[$i]['id'] = $product_data[$i]->hkey2;
                      $json[$i]['name'] = $product_data[$i]->hvalue2;
                      $a=$product_data[$i]->hkey1;
                      if($a=='0'){
                          $json[$i]['parent'] = "";
                      }else{
                        $json[$i]['parent'] = $product_data[$i]->hkey1;
                      }
                      $json[$i]['checked'] = false;
                      $json[$i]['nameAttr'] = 'Addindustry';

                  }
                  echo json_encode($json);
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

    public function get_locationdata(){
        if($this->session->userdata('uid')){
          try{
                $location_data = $this->teams->view_locationdata();
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
                    $json[$i]['nameAttr'] = 'Addloc';

                }
                echo json_encode($json);
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
    public function post_data(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $dt = date('ymdHis');

                    $locNode=$data->locNode;
                    $proNode=$data->proNode;
                    $busNode=$data->busNode;
                    $indNode=$data->indusNode;
                    $teamname=$data->teamname;
                    $deptname=$data->deptname;
                    $cust_management=$data->cust_management;
                    $prodCurrencymain=$data->prodCurrency; // array of product n currency
                    $OfficeLocLastNode=$data->OfficeLocLastNode; // array of office location
                    $bussiLocLastNode=$data->bussiLocLastNode; // array of busii location
                    $indusLocLastNode=$data->indusLocLastNode; // array of indi location
                    $sellType=$data->sellType; // array of sell type

                    $teamid=uniqid($dt);
                    $teamdata=array(
                        'teamid' => $teamid,
                        'teamname' => $teamname,
                        'location' => $locNode,
                        'productid' => $proNode,
                        'department_id' => $deptname,
                        'business_location_id'=>$busNode,
                        'industry_id'=>$indNode,
                        'customer_management'=>$cust_management,
                        'regionid'=>$sellType
                    );

                    $insert = $this->teams->insert_data($teamdata,$teamname,$teamid,$prodCurrencymain,$OfficeLocLastNode,$bussiLocLastNode,$indusLocLastNode);
                    if($insert==TRUE){
                        echo "1";
                    }else{
                        echo "0";
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
    public function update_data(){
        if($this->session->userdata('uid')){

         try{
                  //  echo "hi";exit;
                 $json = file_get_contents("php://input");
                 $data = json_decode($json);

                       $locNode=$data->locNode;
                       $proNode=$data->proNode;
                       $teamname=$data->teamname;
                       $deptname=$data->deptid;
                       $busNode=$data->busNode;
                       $indNode=$data->indusNode;
                       $cust_management=$data->cust_management;
                       $prodCurrency=$data->prodCurrency; // array
                       $OfficeLocLastNode=$data->OfficeLocLastNode; // array of office location
                       $bussiLocLastNode=$data->bussiLocLastNode; // array of office location
                       $indusLocLastNode=$data->indusLocLastNode; // array of office location
                       $sellType=$data->sellTypeE; // array of sell type

                       $teamid=$data->teamid;
                       $teamdata=array(

                          'teamname' => $teamname,
                          'location' => $locNode,
                          'productid' => $proNode,
                          'department_id' => $deptname,
                          'business_location_id'=>$busNode,
                          'industry_id'=>$indNode,
                          'customer_management'=>$cust_management,
                          'regionid'=>$sellType
                       );
                       $teamdata1=array(
                          'location' => $locNode,
                          'productid' => $proNode,
                          'department_id' => $deptname,
                          'business_location_id'=>$busNode,
                          'industry_id'=>$indNode,
                          'customer_management'=>$cust_management,
                          'regionid'=>$sellType
                       );
                        $update=$this->teams->update_data($teamdata,$teamdata1,$teamname,$teamid,$prodCurrency,$OfficeLocLastNode,$bussiLocLastNode,$indusLocLastNode);
                       if($update==TRUE){
                            echo "1";
                       }else{
                            echo "0";
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
          }
          else{
              redirect('indexController');
         }
    }
    public function get_locationdata_edit(){
         if($this->session->userdata('uid')){
              try{
                $json = file_get_contents("php://input");
                $data = json_decode($json);

                $teamid=$data->teamid;
                $locationdata_edit=$this->teams->locationdata_edit($teamid);
                echo json_encode($locationdata_edit);
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
     public function get_businessdata_edit(){
         if($this->session->userdata('uid')){
             try{
                $json = file_get_contents("php://input");
                $data = json_decode($json);

                $teamid=$data->teamid;
                $businessdata_edit=$this->teams->businessdata_edit($teamid);
                echo json_encode($businessdata_edit);
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
     public function get_industry_edit(){
         if($this->session->userdata('uid')){
                   try{
                      $json = file_get_contents("php://input");
                      $data = json_decode($json);

                      $teamid=$data->teamid;
                      $industrydata_edit=$this->teams->industrydata_edit($teamid);
                      echo json_encode($industrydata_edit);
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
    public function get_productdata_edit(){

          if($this->session->userdata('uid')){
              try{
                      $json = file_get_contents("php://input");
                      $data = json_decode($json);

                      $teamid=$data->teamid;
                      $productdata_edit=$this->teams->productdata_edit($teamid);
                      echo json_encode($productdata_edit);
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
    public function get_currency(){

        if($this->session->userdata('uid')){
            try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);

                  $proarray=$data->proarry;
                  $getcur = $this->teams->currency_data($proarray);
                  echo json_encode($getcur);
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
    public function get_viewdata(){
        if($this->session->userdata('uid')){
           try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);

                  $teamid=$data->teamid;
                  $business_location_id=$data->business_location_id;
                  $industry_id=$data->industry_id;
                  $get_viewdata = $this->teams->get_viewdata($teamid,$business_location_id,$industry_id);
                  echo json_encode($get_viewdata);
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