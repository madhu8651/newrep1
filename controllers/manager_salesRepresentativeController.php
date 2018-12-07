<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('manager_salesRepresentativeController');

class manager_salesRepresentativeController extends Master_Controller{

    public function __construct(){
      parent::__construct();
      $this->load->helper('url');
      $this->load->model('manager_salesRepresentativeModel','salesRep');
    }
    
    public function index(){
      if($this->session->userdata('uid')){
        $this->load->view('manager_salesRepresentativeView');
      }
      else{
            redirect('indexController');
      }
    }

    public function get_rep_info(){
      if($this->session->userdata('uid')){
        $user=$this->session->userdata('uid');
        $rep_info = $this->salesRep->view_data($user);
        echo json_encode($rep_info);
      }
      else{
            redirect('indexController');
      }    
    }
    
    public function post_id($data){
      if($this->session->userdata('uid')){
        $data1['result']=$data;
        $this->load->view('manager_saleRepInfoView',$data1);  
      }
      else{
            redirect('indexController');
      }         
    } 
    public function get_rep_data(){
      if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $products_names=array();
        $rep_id=$data->rep_id;
        $rep_info = $this->salesRep->rep_data($rep_id);
        echo json_encode($rep_info);  
      }
      else{
            redirect('indexController');
      }
       
    }
    
    public function get_products(){
      if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $teamid=$data->team_id;
        $product_data = $this->salesRep->view_productdata($teamid);
        echo json_encode($product_data);
      }
      else{
            redirect('indexController');
      }
        
    }
    
    public function save_rep_data(){
      if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json);        
        // $Rooster=$data->Rooster;
        $callcost=$data->callCost;
        $callCurrency=$data->callCurrency;  
        $smsCost=$data->smsCost;
        $smsCurrency=$data->smsCurrency;
        $rep_id=$data->rep_id;
        $data=array(
          //   'working_days'=>$Rooster,
          'outgoingcall_currency'=>$callCurrency,
          'outgoingcall_cost'=>$callcost,
          'outgoingsms_currency'=>$smsCurrency,
          'outgoingsms_cost'=>$smsCost     
        );
        $update=$this->salesRep->update_rep_data($data,$rep_id);      
          if($update==1){
            echo json_encode($update);
          }
        }
        else{
            redirect('indexController');
        }     
    } 
    
    public function saveTarget()  {
      if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
      
        $rep_id=$data->rep_id;
        $productIds=$data->product_ids;
        $dt = date('ymdHis');
        $targetName="Target";
        $targetID=strtoupper(substr($targetName,0,2));
        $targetID.=$dt;
        $targetID = uniqid($targetID);
        $userName="User";
        $userMappingID=strtoupper(substr($userName,0,2));
        $userMappingID.=$dt;
        $userMappingID = uniqid($userMappingID);
        $targetCurrency=$data->target_currency;
          foreach ($productIds as $product_id) {
            $data1=array('user_mapping_id' =>$userMappingID,
              'user_id'=>$rep_id,
              'map_type'=>'product',
              'map_id'=>$product_id,
              'map_key'=>'target',
              'map_value'=>$targetID,
              'transaction_id'=>'',
              'transaction_type'=>''

            );
            $insertUserMapping=$this->salesRep->insertTargetMap($data1,$product_id,$rep_id,$targetCurrency);
             if($insertUserMapping==1){
              $insert_target=$this->salesRep->insert_target($targetID,json_encode($data));
              $targetData=$this->salesRep->targetDetails($rep_id);
              echo json_encode($targetData);
           }
           else{
              $targetData="false";
              echo json_encode($targetData);
           }
          }
 
      }
      else{
            redirect('indexController');
      }
        
    }

    public function get_target(){
      if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json); 
        $rep_id=$data->rep_id;
        $targetData=$this->salesRep->targetDetails($rep_id);
        echo json_encode($targetData);    
      }
      else{
            redirect('indexController');
      }
     
    }
  
  
    public function save_products(){
      if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $dt = date('ymd');
        $prodCurrency =$data->prodCurrency;
        $add_user=$data->rep_id;
        $user_team=$data->team_id;        
        $insert_ProdctCurr=$this->salesRep->insert_procurrency($add_user,$prodCurrency,$user_team);
        echo json_encode($insert_ProdctCurr);  
      }
      else{
            redirect('indexController');
      }
    
    }
  
    public function get_rep_products(){
      if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        $rep_id=$data->rep_id;
        $rep_products=$this->salesRep->rep_products($rep_id);
        echo json_encode($rep_products);  
      }
      else{
            redirect('indexController');
      }
    
    }
    
    public function update_target(){
      if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json); 
        $targetID=$data->target_id;
        $update=$this->salesRep->update_rep_target($targetID,json_encode($data));
          if($update==1){
            $rep_id=$data->rep_id;
            $targetData=$this->salesRep->targetDetails($rep_id);
            echo json_encode($targetData);    
          }
      }
      else{
            redirect('indexController');
      }              
    }

    public function getTargetProducts(){
      if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json); 
        $rep_id=$data->rep_id;
        $targetProducts=$this->salesRep->targetProductsInfo($rep_id);
        echo json_encode($targetProducts);    
      }
      else{
            redirect('indexController');
      }    
    }

    public function getTargetCurrency(){
      if($this->session->userdata('uid')){
        $json = file_get_contents("php://input");
        $data = json_decode($json);
        // var_dump($data);
        $productId=$data->product_id; 
        $targetCurrency=$this->salesRep->targetCurrencyInfo($productId);
        echo json_encode($targetCurrency);     
      }
      else{
            redirect('indexController');
      } 
    }    
}
?>

