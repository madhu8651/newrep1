<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';
include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');

class manager_teamManagersController extends Master_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('admin_userModel1','userinfo');
        $this->load->model('manager_teamManagersModel','teamManagers');
    }
     public function index(){
       if($this->session->userdata('uid')){
            try {
                    $this->load->view('manager_teamManagersView');     
            }
            catch(LConnectApplicationException $e) {
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

    public function get_manager_info(){ 
       if($this->session->userdata('uid')){
            try {
                    $user=$this->session->userdata('uid');
                    $manager_info = $this->teamManagers->view_data($user);
                    echo json_encode($manager_info);
            }
            catch(LConnectApplicationException $e) {
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

    public function post_id($data){
        if($this->session->userdata('uid')){
            try {
                    $data1['result']=$data;
                    $this->load->view('manager_teamManagerInfoView',$data1);               
            }
            catch(LConnectApplicationException $e) {
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

    public function get_manager_data(){
        if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $products_names=array();
                    $manager_id=$data->manager_id;
                    $manager_info = $this->teamManagers->manager_data($manager_id);
                    echo json_encode($manager_info);  
            }
             catch(LConnectApplicationException $e) {
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
        else {
        redirect('indexController');
        }
       
    }
      
    public function get_products(){
        if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $teamid=$data->team_id;
                    $product_data = $this->teamManagers->view_productdata($teamid);
                    echo json_encode($product_data);                
            }
            catch(LConnectApplicationException $e) {
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

    public function save_manager_data(){
       if($this->session->userdata('uid')) {
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    // $Rooster=$data->Rooster;
                    $callcost=$data->callCost;
                    $callCurrency=$data->callCurrency;  
                    $smsCost=$data->smsCost;
                    $smsCurrency=$data->smsCurrency;
                    $managerId=$data->manager_id;
                    $data=array(
                    //   'working_days'=>$Rooster,
                    'outgoingcall_currency'=>$callCurrency,
                    'outgoingcall_cost'=>$callcost,
                    'outgoingsms_currency'=>$smsCurrency,
                    'outgoingsms_cost'=>$smsCost     
                    );
                    $update=$this->teamManagers->update_manager_data($data,$managerId);      
                    if($update==1){
                    echo json_encode($update);
                    }
            }
            catch(LConnectApplicationException $e) {
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

    public function getSellType()
    {
        if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data=json_decode($json);
                    $userID=$data->user_id;
                    $teamID=$data->team_id;
                    $sellType=$this->teamManagers->teamSellType($userID,$teamID);
                    echo json_encode($sellType);   
            }

            catch(LConnectApplicationException $e) {
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

    public function saveTarget(){
       if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $managerId=$data->manager_id;
                    $productIds=$data->product_ids;
                    $dt = date('ymdHis');
                    $targetName="Target";
                    $targetID=strtoupper(substr($targetName,0,2));
                    $targetID.=$dt;
                    $targetID = uniqid($targetID);
                    $userName="User";
                    $userMappingID=strtoupper(substr($userName,0,2));
                    $userMappingID.=$dt;
                    $targetFor = $data->checked;
                    // Get Team id if checked is team based on user_id
                    $getTeamId = $this->teamManagers->getTeamId($managerId);
                    $teamId = $getTeamId[0]->team_id;
                    if($targetFor == 'teamTarget')
                    {
                        $targetForID = $teamId;
                    }
                    else
                    {
                        $targetForID = $managerId;
                    }
                    
                    $userMappingID = uniqid($userMappingID);
                    $targetCurrency=$data->target_currency;

                    foreach ($productIds as $product_id) 
                    {
                            $data1=array(
                            'user_mapping_id' =>$userMappingID,
                            'user_id'=>$targetForID,
                            'map_type'=>'product',
                            'map_id'=>$product_id,
                            'map_key'=>'target',
                            'map_value'=>$targetID,
                            'transaction_id'=>'',
                            'transaction_type'=>''
                            );


                        $insertUserMapping=$this->teamManagers->insertTargetMap($data1,$data);
                        if($insertUserMapping==1)
                        {
                            $insert_target=$this->teamManagers->insert_target($targetID,json_encode($data));
                            $targetData=$this->teamManagers->targetDetails($managerId);
                            echo json_encode($targetData);
                        }
                        else
                        {
                            $targetData= false;
                            echo json_encode($targetData);
                        }

                    }
            }
            catch(LConnectApplicationException $e) {
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
        else {
            redirect('indexController');
        }
        
    }

    public function get_target(){
       if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json); 
                    $managerId=$data->manager_id;
                    $targetData=$this->teamManagers->targetDetails($managerId);
                    echo json_encode($targetData);      
            }
             catch(LConnectApplicationException $e) {
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
        else {
            redirect('indexController');
        }
    }

    public function save_products(){
       if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $dt = date('ymd');
                    $prodCurrency =$data->prodCurrency;
                    $add_user=$data->rep_id;
                    $user_team= $data->team_id;
                    $insert_ProdctCurr=$this->teamManagers->insert_procurrency($add_user,$prodCurrency,$user_team);
                    echo json_encode($insert_ProdctCurr);     
            }
            catch(LConnectApplicationException $e) {
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

    public function get_manager_products(){
       if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $manager_id=$data->manager_id;
                    $rep_products=$this->teamManagers->manager_products($manager_id);
                    echo json_encode($rep_products);  
            }
            catch(LConnectApplicationException $e) {
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

    public function update_target(){  
      if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json); 

                    $targetID=$data->target_id;
                    $managerId=$data->manager_id;
                    $productIds=$data->product_ids;
                    $targetArray = array();
                    foreach ($productIds as $product_id) 
                    {
                            $data1=array(
                            'user_id'=>$managerId,
                            'map_type'=>'product',
                            'map_id'=>$product_id,
                            'map_key'=>'target',
                            'map_value'=>$targetID,
                            );
                        array_push($targetArray,$data1);
                    }

                    $update=$this->teamManagers->update_rep_target($targetID,json_encode($data),$targetArray);
                    if($update==1){
                        $rep_id=$data->manager_id;
                        $targetData=$this->teamManagers->targetDetails($rep_id);
                    echo json_encode($targetData);  
                    }       
            }
            catch(LConnectApplicationException $e) {
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

    public function getTargetProducts()
    {
       if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json); 
                    $rep_id=$data->rep_id;
                    $targetProducts=$this->teamManagers->targetProductsInfo($rep_id);
                    echo json_encode($targetProducts);  
            }
             catch(LConnectApplicationException $e) {
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

    public function getTargetCurrency()  {
       if($this->session->userdata('uid')){
            try {
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $productId=$data->product_id;
                    $user=$data->rep_id; 
                    $targetCurrency=$this->teamManagers->targetCurrencyInfo($user,$productId);
                    echo json_encode($targetCurrency);                
            }
            catch(LConnectApplicationException $e) {
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


    public function teamSMSCurrency(){
        if($this->session->userdata('uid')){
            try {
                    $GLOBALS['$logger']->info('!!!Team Currency Function');
                    $GLOBALS['$logger']->info('UserID : '.$this->session->userdata('uid'));
                    $userId=$this->session->userdata('uid');
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $getCurrency = $this->teamManagers->getCurrency($userId);
                    echo json_encode($getCurrency);            
            }
            catch(LConnectApplicationException $e) {
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

    public function checkSuperior()
    {
        if($this->session->userdata('uid')){
                try {
                        $GLOBALS['$logger']->info('!!!Team checkSuperior Function');
                        $GLOBALS['$logger']->info('UserID : '.$this->session->userdata('uid'));
                        $json = file_get_contents("php://input");
                        
                        $data = json_decode($json);
						$userId=$data->manager_id;
                        $checkSuperior = $this->teamManagers->checkSuperior($userId,$this->session->userdata('uid'));
                        echo json_encode($checkSuperior);

                }
                catch(LConnectApplicationException $e) {
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
            }        # code...
    }


    public function getTeamRelatedData()
    {
        if($this->session->userdata('uid'))
        {
            try {
                    $GLOBALS['$logger']->info('!!!Team getTeamRealtedData Function');
                    $GLOBALS['$logger']->info('UserID : '.$this->session->userdata('uid'));


                    $json               = file_get_contents("php://input");
                    $data               = json_decode($json);
                    $teamId             = $data->teamid;
                    $userId             = $data->userid;

                    $teamRelatedData    = $this->teamManagers->fetchTeamRelatedData($teamId,$userId);
                    echo json_encode($teamRelatedData); 

            }
            catch(LConnectApplicationException $e) 
            {
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
        else
        {
            redirect('indexController');
        }   
    }


    public function updateUserData()
    {
        if($this->session->userdata('uid'))
        {
            try {
                    $GLOBALS['$logger']->info('!!!Team getTeamRealtedData Function');
                    $GLOBALS['$logger']->info('UserID : '.$this->session->userdata('uid'));

                    $json               = file_get_contents("php://input");
                    $data               = json_decode($json);
                    // Bussiness Location
                    $businessLocData    = $data->business;
                    // Clientele Industy data
                    $industryData       = $data->clientele;
                    // Assigned User data
                    $assignedUser       = $data->user_id;

                    $data = array(
                                'businessLocData' => $businessLocData,
                                'industryData'    => $industryData,
                                'assignedUser'    => $assignedUser
                                 ); 
                    $success  = $this->teamManagers->updateBusinessDetails($data);
                    echo json_encode($success);
            }
            catch(LConnectApplicationException $e) 
            {
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
        else
        {
            redirect('indexController');
        }         # code...
    }


    public function updateProductivity()
    {
            if($this->session->userdata('uid'))
        {
            try {
                    $GLOBALS['$logger']->info('!!!Team getTeamRealtedData Function');
                    $GLOBALS['$logger']->info('UserID : '.$this->session->userdata('uid'));

                    $json               = file_get_contents("php://input");
                    $data               = json_decode($json);
                    // Product Data
                    $prodCurData        = $data->prodData;
                    // User Expense data
                    $spendCalData       = $data->spedCalData;
                    // User Id
                    $assignedUser       = $data->user_id;
                    // Working Data
                    $workingHoursData   = $data->weekData;
                    // Woring Calendar  
                    $workingCalendar    = $data->holiday_calender; 

                    $data               = array 
                                            (
                                                'prodCurData'       => $prodCurData,
                                                'assignedUser'      => $assignedUser,
                                                'workingHoursData'  => $workingHoursData,
                                                'spendCalData'      => $spendCalData,
                                                'workingCalendar'   => $workingCalendar
                                            );
                    // Inserting product currency data into the related tables, 

                    $success = $this->teamManagers->insertProductivityData($data);

                    return json_encode($success);



            }
            catch(LConnectApplicationException $e) 
            {
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
        else
        {
            redirect('indexController');
        }
    } 


}

?>

