<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_leadsourceController');

class admin_leadsourceController extends Master_Controller{
    public function __construct(){
        parent::__construct();
       $this->load->model('admin_leadsourceModel','source');
    }
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('admin_lead_source_view');
        }else{
            redirect('indexController');
        }
    }
    public function get_lead_source() {
        if($this->session->userdata('uid')){
            $source = $this->source->get_lead_source();
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
             $state=0;
             if($state==1){
                        $json[$i]['status'] = "yes";
             }else{
                    $json[$i]['status'] = "no";
             }
             $hkey2=$source[$i]->hkey2;
             $getcount=$this->source->get_child_count($hkey2,$state);
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
			$state=$data->state;

            $update = $this->source->update_source($source,$hid,$parent_id,$hid1);
            if($update==1){
              if($state==0){
                 $this->get_lead_source();
              }else{
                 $this->get_inactive_data($state);
              }
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
            $state = $data->state;

            $dt = date('ymdHis');
            $letter=chr(rand(97,122));
            $letter.=chr(rand(97,122));
            $sourceID1=$letter;
            $sourceID1.=$dt;
            $sourceID = uniqid($sourceID1);
            $hierarchy_id = $dt;
            $hierarchy_id = uniqid($hierarchy_id);
			$hierarchy = $this->source->get_hierarchy_id();
			$h_class_id = $hierarchy[0]->Hierarchy_Class_ID;
			$data = array(
			    'hierarchy_id' => $hierarchy_id,
				'hierarchy_class_id' => $h_class_id,
				'hkey1' => $parent_id,
				'hvalue1' => $parent_name,
				'hkey2' => $sourceID,
				'hvalue2' =>$source
			);
			$insert = $this->source->insert_hierarchy($data,$source,$parent_id);
			if($insert==true){
			    if($state==0){
                  $this->get_lead_source();
			    }else{
                  $this->get_inactive_data($state);
			    }
			}else{
			    echo "0";
			}

        }else{
            redirect('indexController');
        }
    }
    public function get_currency(){
       if($this->session->userdata('uid')){
            $currency = $this->source->currency();
            echo json_encode($currency);
        }else{
            redirect('indexController');
        }

    }
    public function get_attr_data(){
       if($this->session->userdata('uid')){
                $json = file_get_contents("php://input");
                $data = json_decode($json);

                $nodeid=$data->nodeid;
                $parentid=$data->parentid;
                $getattr=$this->source->getattr_data($nodeid,$parentid);
                echo json_encode($getattr);

        }else{
            redirect('indexController');
        }
    }
    public function post_attr_data(){
        if($this->session->userdata('uid')){

                $json = file_get_contents("php://input");
                $data = json_decode($json);

                $startdate=$data->startdate;
                $startdate1=date('y-m-d H:i:s',strtotime($startdate));
                $enddate=$data->enddate;
                $enddate1=date('y-m-d H:i:s',strtotime($enddate));
                $budget=$data->budget;
                $curid=$data->curid;
                $nodeid=$data->nodeid;
                $parentid=$data->parentid;
                $leadlagtime=$data->leadlagtime;
                $state=$data->state;
                $costtype=$data->costtype;
                $firstaccesstime=$data->firstaccesstime;
                //$leadlagtime1=date('H:i',strtotime($leadlagtime));

                if($enddate==""){
                    $attrdata=array(
                        'lead_source_id'=>$nodeid,
                        'start_date'=>$startdate1,
                        'currency_id'=>$curid,
                        'budget_value'=>$budget,
                        'lead_lag_time'=>$leadlagtime,
                        'first_access_time'=>$firstaccesstime,
                        'lead_cost_type'=>$costtype
                    );
                }else{
                    $attrdata=array(
                        'lead_source_id'=>$nodeid,
                        'start_date'=>$startdate1,
                        'end_date'=>$enddate1,
                        'currency_id'=>$curid,
                        'budget_value'=>$budget,
                        'lead_lag_time'=>$leadlagtime,
                        'first_access_time'=>$firstaccesstime,
                        'lead_cost_type'=>$costtype
                    );
                }

                $insert_attr=$this->source->insert_attr_data($attrdata,$nodeid,$parentid);
                if($insert_attr==TRUE){
                    if($state == 0){
                        $this->get_lead_source();
                    }else{

                        $this->get_inactive_data($state);
                    }
                }else{
                    echo 1;
                }


        }else{
            redirect('indexController');
        }

    }
    public function get_inactive_src(){
         if($this->session->userdata('uid')){
                $json = file_get_contents("php://input");
                $data = json_decode($json);

                $state=$data->state;
                if($state ==1){
                     $source=$this->source->get_inactive_data($state);
                     echo json_encode($source);
                }else{
                        $source=$this->source->get_inactive_data($state);
                        $json = array();
                        $hkey11="";
                        for($i=0;$i<count($source);$i++){
                             $json[$i]['hid'] = $source[$i]->hierarchy_id;
                             $json[$i]['id'] = $source[$i]->hkey2;
                             $json[$i]['name'] = $source[$i]->hvalue2;
                             $json[$i]['parent'] = $source[$i]->hkey1;
                             $json[$i]['parent_name'] = $source[$i]->hvalue1;
                             if($state==1){
                                    $json[$i]['status'] = "yes";
                             }else{
                                    $json[$i]['status'] = "no";
                             }
                             $hkey2=$source[$i]->hkey2;
                             $getcount=$this->source->get_child_count($hkey2,$state);
                             $json[$i]['nodecount'] = $getcount;
                        }
                        echo json_encode($json);
                }





        }else{
            redirect('indexController');
        }

    }
    public function get_inactive_data($state){
         if($this->session->userdata('uid')){

                if($state ==1){
                     $source=$this->source->get_inactive_data($state);
                     echo json_encode($source);
                }else{
                        $source=$this->source->get_inactive_data($state);
                        $json = array();
                        $hkey11="";
                        for($i=0;$i<count($source);$i++){
                             $json[$i]['hid'] = $source[$i]->hierarchy_id;
                             $json[$i]['id'] = $source[$i]->hkey2;
                             $json[$i]['name'] = $source[$i]->hvalue2;
                             $json[$i]['parent'] = $source[$i]->hkey1;
                             $json[$i]['parent_name'] = $source[$i]->hvalue1;
                             if($state==1){
                                    $json[$i]['status'] = "yes";
                             }else{
                                    $json[$i]['status'] = "no";
                             }
                             $hkey2=$source[$i]->hkey2;
                             $getcount=$this->source->get_child_count($hkey2,$state);
                             $json[$i]['nodecount'] = $getcount;
                        }
                        echo json_encode($json);
                }
                /*$source=$this->source->get_inactive_data($state);

                $json = array();
                $hkey11="";
                for($i=0;$i<count($source);$i++){
                 $json[$i]['hid'] = $source[$i]->hierarchy_id;
                 $json[$i]['id'] = $source[$i]->hkey2;
                 $json[$i]['name'] = $source[$i]->hvalue2;
                 $json[$i]['parent'] = $source[$i]->hkey1;
                 $json[$i]['parent_name'] = $source[$i]->hvalue1;
                 if($state==1){
                        $json[$i]['status'] = "yes";
                 }else{
                        $json[$i]['status'] = "no";
                 }
                 $hkey2=$source[$i]->hkey2;
                 $getcount=$this->source->get_child_count($hkey2,$state);
                 $json[$i]['nodecount'] = $getcount;

                }
                echo json_encode($json);*/

        }else{
            redirect('indexController');
        }

    }


}

?>