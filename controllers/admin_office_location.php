<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_office_location');

class admin_office_location extends Master_Controller{
    public function __construct(){
        parent::__construct();
       $this->load->model('admin_ofclocationModel','office');
    }
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('admin_ofclocationView');
        }else{
            redirect('indexController');
        }
    }
    public function get_lead_source(){
        if($this->session->userdata('uid')){
            $source = $this->office->get_lead_source();
            $json = array();
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
        }else{
             redirect('indexController');
        }
    }
    public function update_source() {
        if($this->session->userdata('uid')){

            $json = file_get_contents("php://input");
			$data = json_decode($json);
            $hid=$data->hierarchy_id;
            $hid1=$data->hid;
            $parent_id=$data->parent_id;
			$source=$data->node;
            $update = $this->office->update_source($source,$hid,$parent_id,$hid1);
            if($update==true){
				$this->get_lead_source();
			}else{
                echo "0";
			}
        }else{
            redirect('indexController');
        }
        
    }
	public function post_lead_data() {
        if($this->session->userdata('uid')){
            $json = file_get_contents("php://input");
			$data = json_decode($json);
            $parent_id = $data->parent_id;
            $source = $data->sourcename;
            $parent_name = $data->parent_name;

            $dt = date('ymdHis');
            $sourceID=strtoupper(substr($source,0,2));
            $sourceID.=$dt;
            $sourceID = uniqid($sourceID);
            $hierarchy_id = $dt;
            $hierarchy_id = uniqid($hierarchy_id);
			$hierarchy = $this->office->get_hierarchy_id();
			$h_class_id = $hierarchy[0]->Hierarchy_Class_ID;
			$data = array(
				'hierarchy_id' => $hierarchy_id,
				  'hierarchy_class_id' => $h_class_id,
				  'hkey1' => $parent_id,
				  'hvalue1' => $parent_name,
				  'hkey2' => $sourceID,
				  'hvalue2' => $source
			  );
			$insert = $this->office->insert_hierarchy($data,$source,$parent_id);
			if($insert==true){
				$this->get_lead_source();
			}else{
                echo "0";
			}
        }else{
            redirect('indexController');
        }   
    }
}

?>