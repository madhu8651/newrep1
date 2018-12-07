<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class admin_productController extends Master_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_productModel','product');
    }
    public function index(){
        if($this->session->userdata('uid')){
           $this->load->view('admin_product_view');
        }else{
            redirect('indexController');
        }     
    }
     public function view_product(){
        if($this->session->userdata('uid')){
        $product = $this->product->view_data();
        echo json_encode($product);  
        }else{
           redirect('indexController');
        }
     }
      public function get_product(){
        if($this->session->userdata('uid')){
        $product1 = $this->product->currency();
        echo json_encode($product1); 
        }else{
          redirect('indexController');
        } 
     }

      public function post_product() {
        if($this->session->userdata('uid')){
         $json = file_get_contents("php://input");
          $data = json_decode($json);
          $dt = date('ymdHis');
          $productname = $data->productname;
          $currencyname = $data->currencyname;
          $product_user_id = $data->product_user_id;
          $productid= uniqid($dt);
          $data = array(
              'product_id' => $productid,
              'product_name' => $productname,
              'product_custom_id'=>$product_user_id
          );
         $insert = $this->product->insert_data($data);
          if($insert==TRUE){
          $product_map_id= uniqid($dt);
              for($i=0;$i<count($currencyname);$i++){
                  $data = array(
                  'product_currency_map_id' => $product_map_id,
                  'product_id' => $productid,
                  'currency_id'=>$currencyname[$i]
                   );
              $insert1 = $this->product->currency_data($data);
           }
            if($insert1==TRUE){
             $product_data = $this->product->table_data();
             echo json_encode($product_data);

         }
        }
        }else{
            redirect('indexController');
        }
    }
    public function update_product(){
        if($this->session->userdata('uid')){
            $json = file_get_contents("php://input");
            $data = json_decode($json);
            $productname = $data->productname;
            $currencyname = $data->currencyname;
            $product_user_id = $data->product_user_id;
            $productid=$data->productID;
            $data = array(
            'product_id' => $productid,
            'product_name' => $productname,
            'product_custom_id'=>$product_user_id
            );
            $update = $this->product->update_data($productid,$data);
            if($update==1){
            for($i=0;$i<count($currencyname);$i++){
              $data = array(
              'product_id' => $productid,
              'currency_id'=>$currencyname[$i]
               );
            $update1 = $this->product->update_currency($productid,$data);
            }
            if($update1==TRUE){
            $product_data = $this->product->table_data();
            echo json_encode($product_data);
            }
            }
            }else{
                redirect('indexController');
            }
    }
}
?>