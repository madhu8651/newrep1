<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_blocation_hierarchyController');

class admin_blocation_hierarchyController extends Master_Controller{
    public function __construct(){
        parent::__construct();
       $this->load->model('admin_blocation_hierarchyModel','bloc_hierarchy');
    }
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('admin_blocation_hierarchy_view');
        }else{
            redirect('indexController');
        }
    }
    public function get_lead_source() {
        if($this->session->userdata('uid')){
            try{
                  $source = $this->bloc_hierarchy->get_lead_source();
                  $json = array();
                  $hkey11="";
                  for($i=0;$i<count($source);$i++){
                   $json[$i]['hid'] = $source[$i]->hierarchy_id;
                   $json[$i]['id'] = $source[$i]->hkey2;
                   $var=$source[$i]->hvalue2;
                   $json[$i]['name'] =$var ;
                   $json[$i]['parent'] = $source[$i]->hkey1;
                   $var1=$source[$i]->hvalue1;
                   $json[$i]['parent_name'] = $var1;

                   $getcount=1;
                   $json[$i]['nodecount'] = $getcount;

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
    public function update_source() {
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
        			$data = json_decode($json);
                    $hid=$data->hierarchy_id;
                    $hid1=$data->hid;
                    $parent_id=$data->parent_id;
        			$source=$data->node;
                    $update = $this->bloc_hierarchy->update_source($source,$hid,$parent_id,$hid1);
                    if($update==true){
        				$this->get_lead_source();
        			}else{
                        echo "0";
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
	
	public function post_lead_data() {
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
        			$data = json_decode($json);
                    $parent_id = $data->parent_id;
                    $source = $data->sourcename;
                    $parent_name = $data->parent_name;

                    $dt = date('ymdHis');
                    $letter=chr(rand(97,122));
                    $letter.=chr(rand(97,122));
                    $sourceID1=$letter;
                    $sourceID1.=$dt;
                    $sourceID = uniqid($sourceID1);
                    $hierarchy_id = $dt;
                    $hierarchy_id = uniqid($hierarchy_id);
        			$hierarchy = $this->bloc_hierarchy->get_hierarchy_id();
        			$h_class_id = $hierarchy[0]->Hierarchy_Class_ID;
        			$data = array(
        				'hierarchy_id' => $hierarchy_id,
        				  'hierarchy_class_id' => $h_class_id,
        				  'hkey1' => $parent_id,
        				  'hvalue1' => $parent_name,
        				  'hkey2' => $sourceID,
        				  'hvalue2' => $source
        			  );
        			$insert = $this->bloc_hierarchy->insert_hierarchy($data,$source,$parent_id);
        			if($insert==true){
        				$this->get_lead_source();
        			}else{
                        echo "0";
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