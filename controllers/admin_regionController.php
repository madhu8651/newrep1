<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class admin_regionController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_regionModel','region');
    }
    public function index(){
        if($this->session->userdata('uid')){
        $this->load->view('admin_list_region_view'); 
        }else{
            redirect('indexController');
        }     
    }
    public function get_region(){
        if($this->session->userdata('uid')){
        $region = $this->region->view_data();
        echo json_encode($region);  
        }else{
            redirect('indexController');
        }
    }
   
     public function add_region(){
        if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $dt = date('ymdHis');
        $regionName = $data->regionname;
        $regionKey = $data->region_count;
        $regionID=strtoupper(substr($regionName,0,2));
        $regionID.=$dt;
        $regionID = uniqid($regionID);
        $data = array(
            'lookup_id' => $regionID,
            'lookup_name' => 'region',
            'lookup_key' => $regionKey,
            'lookup_value' => $regionName
        );
        $insert = $this->region->insert_data($data, $regionName);
        if($insert==1){
        $region = $this->region->view_data();
        echo json_encode($region);
        }
        else
        {   
        $region="false";
        echo json_encode($region);
        }
        }else{
        redirect('indexController');
        } 
    }
    

 public function post_data(){
     if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json);    
        $regionName = $data->regionname; 
        $regionID = $data->regionid;       
        $data = array(
            'lookup_value' => $regionName
        );
        $update = $this->region->update_data($regionID,$data,$regionName);
        if($update==1){
            $region = $this->region->view_data();
            echo json_encode($region);
        }
        else
        {   
            echo 0;
        }   
        }else{
            redirect('indexController');
        }
    }
    public function do_upload(){
    $reg['dataexist']=array();
    $reg1['splchar']=array();
            $config['upload_path'] = './Functions_data';  // folder name where excel is uploaded
            $config['allowed_types'] = 'XLS|xls';
            $config['max_size'] = "2048000"; // Can be set to particular file size , here it is 2 MB(2048 Kb)
            $config['overwrite']  = TRUE;
            $this->load->helper('excel_reader_helper');  // helper files
            $this->load->helper('OLERead_helper');   // helper files
            $this->load->library('upload', $config);

    if ( ! $this->upload->do_upload('camp_file_attachment')){
        $error = array('error' => $this->upload->display_errors());
        $this->session->set_flashdata('error',$error['error']);
        redirect('admin_regionController/index');
            }else{
                    $chkflg=0;
                    $data = array('upload_data' => $this->upload->data());
                    $excel = new Spreadsheet_Excel_Reader();
                    $sev_fname=$data['upload_data']['full_path'];  // full path of excel file in order to read it

        $excel->read($sev_fname);
                    $x=2; // start reading the excel from second row as first row has headers
                    while($x<=$excel->sheets[0]['numRows']) {

                            $region = isset($excel->sheets[0]['cells'][$x][1]) ? $excel->sheets[0]['cells'][$x][1] : '';

                            if($region!=""){
                                    $regnumchk=preg_match( '/\d/', $region );  // check for numbers in region
                                    $regsplchr=preg_match('/[^a-zA-Z\d]/', $region);  // check for spl charecter in region

                                    if($regnumchk==1 || $regsplchr==1){
                                            $chkflg=1;
                                            array_push($reg1['splchar'],$region);    // store region in array
                                    }

                                    if($chkflg==0)// if both region is only character
                                    {
                                                    // Generate Region ID
                                            $regname=trim($region);
                                            $getregiondata=$this->region->getregion_data($regname);   // check if region present

                                            if($getregiondata['count'] >0)  // do this if region present
                                            {
                                                    array_push($reg['dataexist'],$region);  // store region in array

                                            }
                                            else{   // if region not present, insert into regions table

                                                    $count= strlen($region);
                                                    $regionKey = 'regn'.($count+1);
                                                    $regionID=strtoupper(substr($region,0,2));
                                                    $regionID.=$dt;
                                                    $regionID = uniqid($regionID);
                                                    $data = array(
                                                    'lookup_id' => $regionID,
                                                    'lookup_name' => 'region',
                                                    'lookup_key' => $regionKey,
                                                    'lookup_value' => $region
                                                    );

                                                    $insert = $this->region->insert_data($data, $region);
                                                    if($insert==1){
                                                            $region = $this->region->view_data();
                                                            echo json_encode($region);
                                                    }
                                            } // end if

                                    }  // end of if

                            }  // end of if 1
                            $chkflg=0;
                            $x++;
                    } // end of while

            // send the array data to view
            $this->data['region_exists'] = $reg['dataexist'];

            $this->data['region_splchar'] = $reg1['splchar'];

            $this->load->view('admin_regionexluploadView',$this->data,FALSE);
}

}
    
}
?>