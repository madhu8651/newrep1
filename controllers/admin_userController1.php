<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_userController1');

class admin_userController1 extends Master_Controller{
      public function  __construct(){
          parent::__construct();
          $this->load->helper('url');
          $this->load->library('session');
          $this->load->model('admin_userModel1','userinfo');
          $this->load->library('lconnecttcommunication');
      }
      public function index(){
          if($this->session->userdata('uid')){
          $this->load->view('admin_user_add1');
          }else{
              redirect('indexController');
          }

      }

      public function json_cron($work){
              $stime = explode(":",$work->start_time);
              $etime = explode(":",$work->end_time);
              $week = $work->day_of_week;

              $expression = "0 ".(int)$stime[1].",".(int)$etime[1]." ".(int)$stime[0].",".(int)$etime[0]." * * ";
              switch($week){
                case 'SUN' : $expression .= "1";
                      break;
                case 'MON' : $expression .= "2";
                      break;
                case 'TUE' : $expression .= "3";
                      break;
                case 'WED' : $expression .= "4";
                      break;
                case 'THU' : $expression .= "5";
                      break;
                case 'FRI' : $expression .= "6";
                      break;
                case 'SAT' : $expression .= "7";
                      break;
              }
              return $expression;
      }
      public function generatePassword() {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $charactersLength = strlen($characters);
        $randomString3 = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString3 .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString3;
      }

       public function get_users(){
           if($this->session->userdata('uid')){
                try{
                     $userinfo = $this->userinfo->view_data1();
                     $data=json_encode($userinfo);
                     echo $data;
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
      public function check_emailsetting(){
           if($this->session->userdata('uid')){
                try{
                     $check_emailsetting = $this->userinfo->check_emailsetting();
                     $data=json_encode($check_emailsetting);
                     echo $data;
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
      public function get_modules(){
    if($this->session->userdata('uid')){
        try{
              $userdept = $this->userinfo->view_modules('modules','plugins');
              echo json_encode($userdept);
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
    public function get_department(){
    if($this->session->userdata('uid')){
        try{
              $userdept = $this->userinfo->view_department('department','calender','currency','salespersona','groupmail');
              echo json_encode($userdept);
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
    public function get_rolesdata(){
    if($this->session->userdata('uid')){
        try{
              $json = file_get_contents("php://input");
              $data = json_decode($json);

              $depid=$data->dept_id;
              $roles_data = $this->userinfo->view_roledata($depid);
              echo json_encode($roles_data);
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
    public function get_designation() {
    if($this->session->userdata('uid')){
        try{
              $json = file_get_contents("php://input");
              $data = json_decode($json);
              $roleid = $data->roleid;
              $roles = $this->userinfo->view_designation($roleid);
              echo json_encode($roles);
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
    public function get_reportingname() {
    if($this->session->userdata('uid')){
        try{
              $json = file_get_contents("php://input");
              $data = json_decode($json);
              $rptoid = $data->reporting_id;
              $uid = $data->uid;
              $reporting = $this->userinfo->reportingname($rptoid,$uid);
              echo json_encode($reporting);
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
    public function get_teamdata(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $depid=$data->depid;
                    $teams_data = $this->userinfo->view_teamsdata($depid);
                    echo json_encode($teams_data);
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
    public function get_teams_dependdata(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $teamid=$data->teamid;
                    $busiid=$data->busiid;
                    $indusid=$data->indusid;
                    $dataarray=array();
                    $teamloc_data = $this->userinfo->view_teams($teamid,$busiid,$indusid);
                    echo json_encode($teamloc_data);
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
    public function check_phone($addtype){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $phno=$data->phno;
                    $ph_data = $this->userinfo->check_phone($phno,$addtype);
                    echo json_encode($ph_data);
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
                      $jdata = json_decode($json);
                      $dt = date('ymdHis');

                       $dt = date('ymdHis');

                      $userid=uniqid($dt);
                      $username=$jdata->user_name;
                      $lastname=$jdata->last_name;
                      $dofb=$jdata->user_dob;
                      $gender=$jdata->user_gender;
                      $mobile = $jdata->mobile;
                      $home =$jdata->home;
                      $work =$jdata->work;
                      $main = $jdata->main;
                      $resaddress=$jdata->user_resadd;
                      $add_employee_Id=$jdata->add_user_eId;
                      $add_sales_persona=$jdata->add_sales_persona;
                      $save_user_email = $jdata->save_user_email; //array;

                      $sendDetails = $jdata->sendLoginDetails;
                      $primary_email = $sendDetails->email;
                      $primary_mobile = $sendDetails->phn;

                      $dt = date('Ymdhisu');
                      $token = uniqid($dt);

                      $name=$username.' '.$lastname;
                      if($dofb!=""){
                            $dob = date('Y-m-d', strtotime($dofb));

                            $data=array(
                                  'user_id' => $userid,
                                  'user_name' => $name,
                                  'address1' => $resaddress,
                                  'dob' => $dob,
                                  'user_gender'=>$gender,
                                  'employee_id'=>$add_employee_Id,
                                  'login_id'=>$add_employee_Id,
                                  'user_product'=>$add_sales_persona,
                                  'user_primary_email'=>$primary_email,
                                  'user_primary_mobile'=>$primary_mobile,
                                  'password_reset_token'=>$token
                              );
                    }else{

                          $data=array(
                                  'user_id' => $userid,
                                  'user_name' => $name,
                                  'address1' => $resaddress,
                                  'user_gender'=>$gender,
                                  'employee_id'=>$add_employee_Id,
                                  'login_id'=>$add_employee_Id,
                                  'user_product'=>$add_sales_persona,
                                  'user_primary_email'=>$primary_email,
                                  'user_primary_mobile'=>$primary_mobile,
                                  'password_reset_token'=>$token
                          );
                      }

                      $insert = $this->userinfo->insert_data($data);
                      /* Send Email block */

                      $uniqueid = basename(dirname($_SERVER['SCRIPT_FILENAME']));
                      $subject = "L Connectt Access for " .  $uniqueid;

                      $linkstr=base_url();

                      $pwdlink= base_url()."loginController/set_password/".$userid."/".$token;

                      $txt = "<h3 style='font-weight:normal;'>Welcome to L Connectt</h3>" . "\r\n";
                      $txt .= "<h4>You have been added as an user for the L Connectt console. Click <a href='$linkstr'>here</a> to login to your L Connectt Console after setting the password</h4>" . "\r\n";
                      $txt .= "<b>Login ID </b>: $add_employee_Id  <BR>" . "\r\n";
                      $txt .= "<h4>Use the below link to set your login password</h4> <BR>". "\r\n";
                      $txt .= "<BR> <center><a href='$pwdlink' style='cursor:pointer;text-decoration:none;'><button type='button' style='padding:8px;'>Set your password</button></a></center><BR>". "\r\n";

                      $users = array($userid);

                      $send_mail = $this->lconnecttcommunication->send_email($users,$subject,$txt);

                      /* End of Send Email block */

                      /*Send SMS block*/

                       $send_sms = $this->lconnecttcommunication->send_sms($users);

                      /*End of Send SMS block*/


                      $mobile_no=array(
                          'mobile'=>$mobile,
                          'home'=>$home,
                          'work'=>$work,
                          'main'=>$main

                      );
                      $json_data['phone_num'] = json_encode($mobile_no);
                      $return = $this->userinfo->insert_mobileno($json_data,$userid);


                      $add_user_dep=$jdata->add_user_dep;
                      $add_user_role=$jdata->add_user_role;
                      $add_rep_into=$jdata->add_rep_into;
                      $session_userid=$this->session->userdata('uid'); /* id to be taken from session */
                      if($add_rep_into==""){
                          $add_rep_into=$session_userid;
                      }
                      $officlocArr = (array) $jdata->OfcLocTarget;
                      $add_user_loc = join(",", $officlocArr);

                      $selltypeArr = (array) $jdata->selltype;
                      $add_selltype = join(",", $selltypeArr);

                      $user_work_eId=$jdata->work1;
                      $user_personal_eId =$jdata->personal;

                      $add_user_team=$jdata->add_user_team;
                      $add_rept_desg=$jdata->add_rept_desg;


                      $data=array(
                          'department' => $add_user_dep,
                          'designation' => $add_user_role,
                          'reporting_to'=> $add_rep_into,
                          'location' => $add_user_loc,
                          'team_id' => $add_user_team,
                          'reporting_desg' => $add_rept_desg,
                      );

                      $update = $this->userinfo->update_data1($data,$userid,$add_user_loc,$add_selltype,$save_user_email);

                      $reportingdata=array(
                            'user_id'=> $userid,
                            'reporting_to'=> $add_rep_into
                      );
                      $insertrepdata = $this->userinfo->insert_repdata($reportingdata);
                      $emailId=array(
                          'work'=>$user_work_eId,
                          'personal'=>$user_personal_eId

                      );
                      $json_data['emailId'] = json_encode($emailId);
                      $return = $this->userinfo->update_emailId($json_data,$userid);
                     
                      $add_sales=$jdata->add_sales;
                      $add_manager=$jdata->add_manager;
                      $add_CXO=$jdata->add_CXO;
                      $custo_assign=$jdata->custo_assign;
                      $add_plugin=$jdata->pluginArr; // string data
                      if($add_plugin <> ""){
                            $add_plugin1=explode(",",$add_plugin);

                            $plugins=array(
                                 'Attendence'=>$add_plugin1[0],
                                 'Communicator' =>$add_plugin1[1],
                                 'Expense'=>$add_plugin1[2],
                                 'Inventory'=>$add_plugin1[3],
                                 'Library'=>$add_plugin1[4],
                                 'Navigator'=>$add_plugin1[5]
                            );
                      }


                      $mapid=uniqid($dt);

                      $data1=array(
                          'user_id'=> $userid,
                          'manager_module' =>$add_manager,
                          'sales_module' => $add_sales,
                          'cxo_module'=>$add_CXO

                      );
                      $insert = $this->userinfo->insert_user_licence($data1);

                      $modules=array(
                       'sales'=>$add_sales,
                       'Manager' =>$add_manager,
                       'cxo'=>$add_CXO,
                       'custo_assign'=>$custo_assign
                       );

                      $module=json_encode($modules);
                      if($add_plugin <> ""){
                        $plugin=json_encode($plugins);
                        $json_data1=array(
                           'mapping_id' =>$mapid,
                           'user_id'    =>$userid,
                           'module_id'  =>$module,
                           'plugin_id' =>$plugin
                         );
                      }else{
                        $json_data1=array(
                         'mapping_id' =>$mapid,
                         'user_id'    =>$userid,
                         'module_id'  =>$module
                        );
                      }

                      $insert1 = $this->userinfo->module_plugin_mapping($json_data1);
                      $addMod=$jdata->addMod;
                      $addMod1=json_encode($addMod);
                      $addPlugin=$jdata->addPlugin;
                      $addPlugin1=json_encode($addPlugin);
                      $clientinfo=array(
                          'module_used'=> $addMod1,
                          'plugin_used' =>$addPlugin1
                      );

                      $updateclientinfo = $this->userinfo->updateclientinfo($clientinfo);
                      $timeZone =$jdata->timeZone;
                      $gmdate=gmdate('Y-m-d H:i:s');
                      $timezonedata=array(
                             'user_id'=>$userid,
                             'tz_start_time'=>$gmdate,
                             'timezone'=>$timeZone
                      );
                      $insert1 = $this->userinfo->timezonedata($timezonedata);
                      $insertArray11=array();
                        $letter=chr(rand(97,122));
                        $letter.=chr(rand(97,122));
                        $dashboardID=$letter;
                        $dashboardID.=$dt;
                        $dashboardID1=uniqid($dashboardID);
                        $data_dashboard=array(
                                  'user_mapping_id'=>$dashboardID1,
                                  'user_id' =>$userid,
                                  'map_type'=>"dashboard",
                                  'map_key' =>"displaybox",
                                  'map_value'=>"6"

                        );
                        array_push($insertArray11, $data_dashboard);
                        $insert_bussLoc=$this->userinfo->insert_Ins($insertArray11);
                      echo json_encode($userid);

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

    public function post_data1(){
        if($this->session->userdata('uid')){
                try{
                      $json = file_get_contents("php://input");
                      $data = json_decode($json);

                      $dt = date('ymd');
                      $prodCurrencymain =$data->prodCurrency;
                      $busLocArr = $data->bussLocTarget;
                      $industryArr = $data->inductryTarget;
                      $add_user=$data->add_user;
                      $user_team=$data->add_team;

                      $insertArray=array();
                      if(count($industryArr)>0){
                             foreach($industryArr as $val){
                                $letter=chr(rand(97,122));
                                $letter.=chr(rand(97,122));
                                $clIndstryID=$letter;
                                $clIndstryID.=$dt;
                                $clIndstryID1=uniqid($clIndstryID);
                                $data_clIndstry=array(
                                    'user_mapping_id'=>$clIndstryID1,
                                    'user_id' =>$add_user,
                                    'map_type'=>"clientele_industry",
                                    'map_id' =>$val,
                                    'transaction_Id'=>$user_team
                                );
                                array_push($insertArray, $data_clIndstry);
                             }
                             $insert_Insd=$this->userinfo->insert_Ins($insertArray);
                        }

                        $insertArray1=array();
                        if(count($busLocArr)>0){
                            foreach($busLocArr as $val1){
                              $letter=chr(rand(97,122));
                              $letter.=chr(rand(97,122));
                              $bussLocID=$letter;
                              $bussLocID.=$dt;
                              $bussLocID1=uniqid($bussLocID);
                              $data_bussLoc=array(
                                  'user_mapping_id'=>$bussLocID1,
                                  'user_id' =>$add_user,
                                  'map_type'=>"business_location",
                                  'map_id' =>$val1,
                                  'transaction_Id'=>$user_team

                              );
                              array_push($insertArray1, $data_bussLoc);

                            }
                            $insert_bussLoc=$this->userinfo->insert_Ins($insertArray1);
                        }


                        $insert_ProdctCurr=$this->userinfo->insert_procurrency($add_user,$prodCurrencymain,$user_team);
                        $attr_id=uniqid($dt);
                        $add_user_Hcal=$data->add_user_Hcal;
                        $user_call_rec=$data->user_call_rec;
                        $user_accounting=$data->user_accounting;
                        $resourceCurrency=$data->resourceCurrency;
                        $callCurrency=$data->callCurrency;
                        $smsCurrency=$data->smsCurrency;
                        $resourceCost=$data->resourceCost;
                        $callCost=$data->callCost;
                        $smsCost=$data->smsCost;
                        $add_user=$data->add_user;
                        $workingDayArr =$data->workingdays;


                        foreach($workingDayArr as $val){
                          $expression[] = $this->json_cron($val);
                        }
                        $workingdays=json_encode($expression);

                        $data1=array(
                              'user_id' =>$add_user,
                              'call_recording'=>$user_call_rec,
                              'accounting'=>$user_accounting,
                              'resource_currency'=>$resourceCurrency,
                              'resource_cost'=>$resourceCost,
                              'outgoingcall_currency'=>$callCurrency,
                              'outgoingcall_cost'=>$callCost,
                              'outgoingsms_currency'=>$smsCurrency,
                              'outgoingsms_cost'=>$smsCost,
                              'holiday_calender'=>$add_user_Hcal
                          );
                        $insert = $this->userinfo->productivityDetails($data1);

                        $data2=array(
                            'user_attribute_id'=>$attr_id,
                            'user_id'=>$add_user,
                            'attribute_type'=>'workingDetails',
                            'expression'=>$workingdays
                          );


                        $insert1 = $this->userinfo->user_attributes($data2,$add_user);
                        echo json_encode($insert1);

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

    public function get_remaininguser_data(){
           if($this->session->userdata('uid')){
                try{
                      $json = file_get_contents("php://input");
                      $data = json_decode($json);

                      $userid=$data->user_id;
                      $loc_team=$data->loc_team;
                      $reporting_toid=$data->reporting_toid;
                      $get_remaininguser_data=$this->userinfo->get_remaininguser_data($userid,$loc_team,$reporting_toid);
                      echo json_encode($get_remaininguser_data);
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

    public function updata_section1data(){
           if($this->session->userdata('uid')){
                try{
                      $json = file_get_contents("php://input");
                      $jdata = json_decode($json);

                      $dt = date('ymd');
                      $userid=$jdata->add_user;
                      $username=$jdata->user_name;
                      $lastname=$jdata->last_name;
                      $dofb=$jdata->user_dob;
                      $gender=$jdata->user_gender;
                      $mobile = $jdata->mobile;
                      $home =$jdata->home;
                      $work =$jdata->work;
                      $main = $jdata->main;
                      $resaddress=$jdata->user_resadd;
                      $add_employee_Id=$jdata->add_user_eId;
                      $add_sales_persona=$jdata->add_sales_persona;
                      $primary_email=$jdata->user_primary_email;
                      $primary_mobile=$jdata->user_primary_mobile;
                      $save_user_email = $jdata->save_user_email; //array;

                      $name=$username;
                      $password  = $this->generatePassword();
                      if($dofb!=""){
                            $dob = date('Y-m-d', strtotime($dofb));

                            $data=array(

                                  'user_name' => $name,
                                  'address1' => $resaddress,
                                  'dob' => $dob,
                                  'user_gender'=>$gender,
                                  'employee_id'=>$add_employee_Id,
                                  'login_id'=>$add_employee_Id,
                                  'user_product'=>$add_sales_persona,
                                  'user_primary_email'=>$primary_email,
                                  'user_primary_mobile'=>$primary_mobile
                              );
                    }else{

                          $data=array(
                                  'user_name' => $name,
                                  'address1' => $resaddress,
                                  'user_gender'=>$gender,
                                  'employee_id'=>$add_employee_Id,
                                  'login_id'=>$add_employee_Id,
                                  'user_product'=>$add_sales_persona,
                                  'user_primary_email'=>$primary_email,
                                  'user_primary_mobile'=>$primary_mobile
                          );
                      }

                      $insert = $this->userinfo->update_userinfo($data,$userid);

                      $mobile_no=array(
                          'mobile'=>$mobile,
                          'home'=>$home,
                          'work'=>$work,
                          'main'=>$main
                      );
                      $json_data['phone_num'] = json_encode($mobile_no);
                      $return = $this->userinfo->insert_mobileno($json_data,$userid);


                      $add_user_dep=$jdata->add_user_dep;
                      $add_user_role=$jdata->add_user_role;
                      $add_rep_into=$jdata->add_rep_into;
                      $session_userid=$this->session->userdata('uid'); /* id to be taken from session */
                      if($add_rep_into==""){
                          $add_rep_into=$session_userid;
                      }
                      $officlocArr = (array) $jdata->OfcLocTarget;
                      $add_user_loc = join(",", $officlocArr);

                      $selltypeArr = (array) $jdata->selltype;
                      $add_selltype = join(",", $selltypeArr);

                      $user_work_eId=$jdata->work1;
                      $user_personal_eId =$jdata->personal;

                      $add_user_team=$jdata->add_user_team;
                      $add_rept_desg=$jdata->add_rept_desg;


                      $data=array(
                          'department' => $add_user_dep,
                          'designation' => $add_user_role,
                          'reporting_to'=> $add_rep_into,
                          'location' => $add_user_loc,
                          'team_id' => $add_user_team,
                          'reporting_desg' => $add_rept_desg,
                      );

                      $update = $this->userinfo->update_data1($data,$userid,$add_user_loc,$add_selltype,$save_user_email);

                      $reportingdata=array(

                            'reporting_to'=> $add_rep_into
                      );
                      $insertrepdata = $this->userinfo->update_repdata($reportingdata,$userid);
                      $emailId=array(
                          'work'=>$user_work_eId,
                          'personal'=>$user_personal_eId

                      );
                      $json_data['emailId'] = json_encode($emailId);
                      $return = $this->userinfo->update_emailId($json_data,$userid);

                       //updating useremails settings email id and making password blank
						
						if(isset($user_personal_eId[0])){
							$this->userinfo->update_user_emails($user_personal_eId[0],$userid);
						}
                      

                      $add_sales=$jdata->add_sales;
                      $add_manager=$jdata->add_manager;
                      $add_CXO=$jdata->add_CXO;
                      $custo_assign=$jdata->custo_assign;
                      $add_plugin=$jdata->pluginArr; // string data

                      if($add_plugin <> ""){
                            $add_plugin1=explode(",",$add_plugin);

                            $plugins=array(
                                 'Attendence'=>$add_plugin1[0],
                                 'Communicator' =>$add_plugin1[1],
                                 'Expense'=>$add_plugin1[2],
                                 'Inventory'=>$add_plugin1[3],
                                 'Library'=>$add_plugin1[4],
                                 'Navigator'=>$add_plugin1[5]
                            );

                      }

                      $mapid=uniqid($dt);

                      $data1=array(
                          'manager_module' =>$add_manager,
                          'sales_module' => $add_sales,
                          'cxo_module'=>$add_CXO
                      );
                      $insert = $this->userinfo->update_user_licence($data1,$userid);

                      $modules=array(
                       'sales'=>$add_sales,
                       'Manager' =>$add_manager,
                       'cxo'=>$add_CXO,
                       'custo_assign'=>$custo_assign
                       );

                      $module=json_encode($modules);
                      if($add_plugin <> ""){
                          $plugin=json_encode($plugins);
                          $json_data1=array(
                             'mapping_id' =>$mapid,
                             'module_id'  =>$module,
                             'plugin_id' =>$plugin
                          );
                      }else{
                          $json_data1=array(
                             'mapping_id' =>$mapid,
                             'module_id'  =>$module
                          );
                      }
                      $insert1 = $this->userinfo->update_module_plugin_mapping($json_data1,$userid);
                      $addMod=$jdata->addMod;
                      $addMod1=json_encode($addMod);
                      $addPlugin=$jdata->addPlugin;
                      $addPlugin1=json_encode($addPlugin);
                      $clientinfo=array(
                          'module_used'=> $addMod1,
                          'plugin_used' =>$addPlugin1
                      );

                       $updateclientinfo = $this->userinfo->updateclientinfo($clientinfo);
                      echo json_encode($userid);
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




    public function updata_section2data(){
           if($this->session->userdata('uid')){
                try{
                      $json = file_get_contents("php://input");
                      $data = json_decode($json);

                      $dt = date('ymd');
                      $prodCurrencymain =$data->prodCurrency;
                      $busLocArr = $data->bussLocTarget;
                      $industryArr = $data->inductryTarget;
                      $add_user=$data->add_user;
                      $user_team=$data->add_team;

                      $insertArray=array();
                      $map_type='clientele_industry';
                      if(count($industryArr)>0){
                             foreach($industryArr as $val){
                                $letter=chr(rand(97,122));
                                $letter.=chr(rand(97,122));
                                $clIndstryID=$letter;
                                $clIndstryID.=$dt;
                                $clIndstryID1=uniqid($clIndstryID);
                                $data_clIndstry=array(
                                    'user_mapping_id'=>$clIndstryID1,
                                    'user_id' =>$add_user,
                                    'map_type'=>"clientele_industry",
                                    'map_id' =>$val,
                                    'transaction_Id'=>$user_team
                                );
                                array_push($insertArray, $data_clIndstry);
                             }
                             $insert_Insd=$this->userinfo->update_Ins($insertArray,$map_type,$add_user);
                        }

                        $insertArray1=array();
                        $map_type='business_location';
                        if(count($busLocArr)>0){
                            foreach($busLocArr as $val1){
                              $letter=chr(rand(97,122));
                              $letter.=chr(rand(97,122));
                              $bussLocID=$letter;
                              $bussLocID.=$dt;
                              $bussLocID1=uniqid($bussLocID);
                              $data_bussLoc=array(
                                  'user_mapping_id'=>$bussLocID1,
                                  'user_id' =>$add_user,
                                  'map_type'=>"business_location",
                                  'map_id' =>$val1,
                                  'transaction_Id'=>$user_team

                              );
                              array_push($insertArray1, $data_bussLoc);

                            }
                            $insert_bussLoc=$this->userinfo->update_Ins($insertArray1,$map_type,$add_user);
                        }
                        $insert_ProdctCurr=$this->userinfo->insert_procurrency($add_user,$prodCurrencymain,$user_team);
                        $attr_id=uniqid($dt);
                        $add_user_Hcal=$data->add_user_Hcal;
                        $user_call_rec=$data->user_call_rec;
                        $user_accounting=$data->user_accounting;
                        $resourceCurrency=$data->resourceCurrency;
                        $callCurrency=$data->callCurrency;
                        $smsCurrency=$data->smsCurrency;
                        $resourceCost=$data->resourceCost;
                        $callCost=$data->callCost;
                        $smsCost=$data->smsCost;
                        $add_user=$data->add_user;
                        $workingDayArr =$data->workingdays;
                        //$timeZone =$data->timeZone;

                        foreach($workingDayArr as $val){
                          $expression[] = $this->json_cron($val);
                        }
                        $workingdays=json_encode($expression);

                        $data1=array(
                              'user_id'=>$add_user,
                              'call_recording'=>$user_call_rec,
                              'accounting'=>$user_accounting,
                              'resource_currency'=>$resourceCurrency,
                              'resource_cost'=>$resourceCost,
                              'outgoingcall_currency'=>$callCurrency,
                              'outgoingcall_cost'=>$callCost,
                              'outgoingsms_currency'=>$smsCurrency,
                              'outgoingsms_cost'=>$smsCost,
                              'holiday_calender'=>$add_user_Hcal
                          );
                        $insert = $this->userinfo->update_productivityDetails($data1,$add_user);

                        $data2=array(
                            'user_id'=>$add_user,
                            'user_attribute_id'=>$attr_id,
                            'attribute_type'=>'workingDetails',
                            'expression'=>$workingdays
                          );
                        $insert1 = $this->userinfo->update_user_attributes($data2,$add_user);
                        echo json_encode($insert1);

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

    public function check_deactive(){
            if($this->session->userdata('uid')){
              try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $userid=$data->userID;
                    $department=$data->department;
                    $roleid=$data->roleid;

                    $check_deactive = $this->userinfo->check_deactive($userid,$department,$roleid);
                    echo json_encode($check_deactive);
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

    public function post_replacement_data($status){

             if($this->session->userdata('uid')){
               try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $userid=$data->userID;
                    $olduserID=$data->olduserID;
                    $reportingdesg=$data->reportingdesg;
                    $remcnt=$data->remcnt;
                    $data1=array();
                    if($remcnt < 0){

                        $userid=$data->userID;
                        $olduserID=$data->olduserID;
                        $reportingdesg=$data->reportingdesg;
                        $remcnt=$data->remcnt;
                        $add_sales=$data->add_sales;
                        $add_manager=$data->add_manager;
                        $add_CXO=$data->add_CXO;
                        $data1=array(
                          'user_id'=> $olduserID,
                          'manager_module' =>$add_manager,
                          'sales_module' => $add_sales,
                          'cxo_module'=>$add_CXO
                        );
                        $replacement_data = $this->userinfo->post_replacement_data($userid,$reportingdesg,$olduserID,$status,$data1,$remcnt);
                        echo json_encode($replacement_data);

                    }else{

                        $replacement_data = $this->userinfo->post_replacement_data($userid,$reportingdesg,$olduserID,$status,$data1,$remcnt);
                        echo json_encode($replacement_data);

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

    public function check_active(){

            if($this->session->userdata('uid')){
              try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $userid=$data->userID;
                    $department=$data->department;
                    $roleid=$data->roleid;
                    $remcnt=$data->remcnt;

                    $check_active = $this->userinfo->check_active($userid,$department,$roleid,$remcnt);
                    echo json_encode($check_active);
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

    public function choose_replacement(){

           if($this->session->userdata('uid')){
              try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $userid=$data->userid1;

                    $check_active = $this->userinfo->choose_replacement($userid);
                    echo json_encode($check_active);
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

    public function update_reportingdata(){

            if($this->session->userdata('uid')){
              try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $rep_arr=$data->rep_arr; // array data
                    $olduserID=$data->olduserID;
                    $type=$data->type;

                    $check_active = $this->userinfo->update_reportingdata($rep_arr,$olduserID,$type);
                    echo json_encode($check_active);
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

    public function check_forcxo(){

            if($this->session->userdata('uid')){
              try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $userid1=$data->userid1; // array data
                    $add_role=$data->add_role; // array data

                    $check_active = $this->userinfo->check_forcxo($userid1,$add_role);
                    echo json_encode($check_active);
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

    public function replacement_rolechange(){
            if($this->session->userdata('uid')){
              try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);

                    $selmod=$data->selmod;
                    $userid1=$data->userid1;
                    $add_role=$data->add_role;

                    $check_active = $this->userinfo->replacement_rolechange($userid1,$selmod,$add_role);
                    echo json_encode($check_active);

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

    public function file_upload($path)    {
      if($this->session->userdata('uid')){
            try{
              $config['upload_path']   = './uploads';
              $config['allowed_types'] = 'gif|jpg|png|bmp';
              $config['max_size'] = "1024000"; // Can be set to particular file size , here it is 2 MB(2048 Kb)
              $config['overwrite']  = TRUE;
              $this->load->library('upload', $config);
              if ( ! $this->upload->do_upload('adminphoto'))    {
                  $error = array('error' => $this->upload->display_errors());
                  $this->index();
              }
              else {
                $data = array('upload_data' => $this->upload->data());
                $old_path=$data['upload_data']['full_path'];
                $old_fname = $data['upload_data']['file_name'];
                $new_fname = $path.$data['upload_data']['file_ext'];
                $new_path = str_replace($old_fname, $new_fname, $old_path);
                if (rename($old_path, $new_path)) {

                  $usersphoto = $new_fname;
                  $data = array(
                    'photo' => $usersphoto,
                  );
                  $insert = $this->userinfo->update_userPhoto($data, $path);
                  if ($insert == TRUE) {
                    $this->index();
                  }
                        }
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