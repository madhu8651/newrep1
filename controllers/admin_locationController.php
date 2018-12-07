<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class admin_locationController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_locationModel','location');
    }
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('admin_listlocation_view');
        }else{
            redirect('indexController');
        }     
    }
    public function get_location(){
        if($this->session->userdata('uid')){
          try{
                $location = $this->location->view_location();
                echo json_encode($location);
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
    public function get_region(){
           if($this->session->userdata('uid')){
                 try{
                        $location = $this->location->view_region();
                        echo json_encode($location);
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
   
     public function add_location(){
         if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $dt = date('ymdHis');

                    $locationName = $data->locationname;
                    $regionid = $data->regionid;
                    $locationKey = $data->locationcount;
                    $locationID=strtoupper(substr($locationName,0,2));
                    $locationID.=$dt;
                    $locationID = uniqid($locationID);
                    $data = array(
                    'lookup_id' => $locationID,
                    'lookup_name' => $regionid,
                    'lookup_key' => $locationKey,
                    'lookup_value' => $locationName
                    );
                    $insert = $this->location->insert_data($data, $regionid,$locationName);
                    if($insert==1){
                     $location = $this->location->view_location();
                     echo json_encode($location);
                    }
                    else
                    {
                     echo 0;
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
    

 public function edit_data(){
     if($this->session->userdata('uid')){
        try{
                $json = file_get_contents("php://input");
                $data = json_decode($json);
                $locationName = $data->locationname;
                $locationID = $data->locationid;
                $regionid = $data->regionid;

                $data = array(
                    'lookup_value' => $locationName,
                    'lookup_name' => $regionid
                );
                $update = $this->location->update_data($data,$locationID,$regionid,$locationName);
                if($update==1){
                    $location = $this->location->view_location();
                    echo json_encode($location);
                }
                else
                {
                    echo 0;
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