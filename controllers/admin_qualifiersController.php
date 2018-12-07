<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');
include 'Master_Controller.php';

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$log'] = Logger::getLogger('admin_qualifiersController');

class admin_qualifiersController extends Master_Controller{
    public function __construct(){
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('admin_qualifiersModel','qualifiers');
    }
    public function index(){
        if($this->session->userdata('uid')){
         $this->load->view('admin_qualifiers_view');  
        }else{
            redirect('indexController');
        }      
    }
    public function get_qualifier(){
        if($this->session->userdata('uid')){
            try{
                $qualifier_data = $this->qualifiers->view_data();
                echo json_encode($qualifier_data);
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
                  $data = json_decode($json);
                  $dt = date('ymdHis');

                  $qualifiername=$data->qualifiername;
                  $qualifierid=uniqid($dt);
                  $data = array(
                       'lead_qualifier_id' => $qualifierid,
                       'lead_qualifier_name' => $qualifiername,
                       'lead_qualifier_type' => 'admin'

                   );
                  $insert = $this->qualifiers->insert_data($data,$qualifiername);
                  if($insert==TRUE){
                       $qualifier_data = $this->qualifiers->view_data();
                       echo json_encode($qualifier_data);
                  }
                  else{
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

    public function update_qualifiername(){
        if($this->session->userdata('uid')){
            try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $dt = date('ymdHis');

                  $qualifiername=$data->qualifierName;
                  $qualifierid=$data->qualifierId;
                  $data = array(
                       'lead_qualifier_name' => $qualifiername
                   );
                  $update = $this->qualifiers->update_qualifiername($data,$qualifiername,$qualifierid);
                  if($update==TRUE){
                       $qualifier_data = $this->qualifiers->view_data();
                       echo json_encode($qualifier_data);
                  }
                  else{
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


    public function get_queansdata(){
        if($this->session->userdata('uid')){
            try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $queid=$data->qualid;
                  $qualifier_ansdata = $this->qualifiers->get_queansdata($queid);
                  echo json_encode($qualifier_ansdata);
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

    public function post_queans(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $dt = date('ymdHis');
                    $que_type=$data->questiontype;
                    $que=$data->question_text;
                    $manbit=$data->manbit;
                    if($que_type=="1"){
                      $anscol=$data->answer;
                    }
                    $qualifier_id=$data->quaid;
                    $ansdata=$data->answer_text;
                    $letter=chr(rand(97,122));
                    $letter.=chr(rand(97,122));
                    $queID=$letter;
                    $queID.=$dt;
                    $queid=uniqid($queID);
                    if($que_type=="1"){
                        $que_tabledata=array(
                            'question_id'=>$queid,
                            'lead_qualifier_id'=>$qualifier_id,
                            'question_type'=>$que_type,
                            'question_text'=>$que,
                            'answer'=>$anscol,
                            'mandatory_bit'=>$manbit
                        );
                    }else{
                        $que_tabledata=array(
                            'question_id'=>$queid,
                            'lead_qualifier_id'=>$qualifier_id,
                            'question_type'=>$que_type,
                            'question_text'=>$que,
                            'mandatory_bit'=>$manbit
                        );
                    }
                    $insert_queans = $this->qualifiers->insert_data_queans($que_tabledata,$queid,$ansdata,$que,$qualifier_id);
                    if($insert_queans==TRUE){
                        $qualifier_ansdata = $this->qualifiers->get_queansdata($qualifier_id);
                        echo json_encode($qualifier_ansdata);

                    }else{
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
    public function update_roworder(){
        if($this->session->userdata('uid')){
            try{
                  $json = file_get_contents("php://input");
                  $data = json_decode($json);
                  $orderselected=$data->orderselected;
                  $qualifier_id=$data->qualid;
                  $update_roworder=$this->qualifiers->update_roworder($orderselected);
                  if($update_roworder==TRUE){
                    $qualifier_ansdata = $this->qualifiers->get_queansdata($qualifier_id);
                    echo json_encode($qualifier_ansdata);
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

    public function delete_question(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $questionid=$data->questionid;
                    $delquestion=$this->qualifiers->delete_question($questionid);
                    $qualifier_id=$delquestion;
                    $qualifier_ansdata = $this->qualifiers->get_queansdata($qualifier_id);
                    echo json_encode($qualifier_ansdata);
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

    public function update_mandatorychk(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $questionid=$data->questionid;
                    $manbit=$data->manbit;
                    $delquestion=$this->qualifiers->update_mandatorychk($questionid,$manbit);
                    $qualifier_id=$delquestion;
                    $qualifier_ansdata = $this->qualifiers->get_queansdata($qualifier_id);
                    echo json_encode($qualifier_ansdata);
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

    public function update_queans(){
        if($this->session->userdata('uid')){
            try{
                    $json = file_get_contents("php://input");
                    $data = json_decode($json);
                    $up_qId=$data->qId;
                    $up_qualid=$data->qualid;
                    $up_qtxt=$data->qtxt;
                    $arr_atxt=$data->atxt;
                    $update_data=$this->qualifiers->update_queans($up_qId,$up_qualid,$up_qtxt,$arr_atxt);
                    if($update_data==TRUE){
                            $qualifier_ansdata = $this->qualifiers->get_queansdata($up_qualid);
                            echo json_encode($qualifier_ansdata);
                    }else{
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