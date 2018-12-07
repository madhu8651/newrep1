<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('ROOT_PATH', dirname(__DIR__) . '/');

include_once (ROOT_PATH.'/core/LConnectApplicationException.php');
include_once (ROOT_PATH.'/log4php/src/main/php/Logger.php');
Logger::configure(ROOT_PATH.'/log4php/src/test/resources/configs/config1.xml');
$GLOBALS['$logger'] = Logger::getLogger('manager_standard_analytics');

class manager_standard_analytics extends CI_Controller{

	public function __construct(){
		parent::__construct();
        $this->load->model('reports/standard_RepAnalysisModel','repModel');
	}
    function _remap($method,$params=array())
    {
        if(method_exists($this,$method))
        {
            call_user_func_array(array($this, $method),$params);
        }
        else
        {
            $this->load->view('manager_analytics_landing_page');
        }
    }
	public function index(){
	    if($this->session->userdata('uid')){
            try{
                $this->load->view('manager_analytics_landing_page');
            }
            catch(LConnectApplicationException $e){
                $this->exception($e);
            }
        }
        else{
                redirect('indexController');
        }
	}
    public function getreportlist()
    {
             if($this->session->userdata('uid'))
             {
                try
                {
                        $reportdata = $this->repModel->getreportlist();
                        echo json_encode($reportdata);
                }
                catch(LConnectApplicationException $e)
                {
                       $this->exception($e);
                }
            }
            else
            {
                redirect('indexController');
            }
    }
    public function getsavedreport($val)
    {
             if($this->session->userdata('uid'))
             {
                try
                {
                        $parentid='';
                        if($val=='detail')
                        {
                          $json = file_get_contents("php://input");
                          $data = json_decode($json);
                          $parentid=$data->id;
                        }

                        $reportdata = $this->repModel->getsavedreport($this->session->userdata('uid'),$val,$parentid);
                        echo json_encode($reportdata);
                }
                catch(LConnectApplicationException $e)
                {
                       $this->exception($e);
                }
            }
            else
            {
                redirect('indexController');
            }
    }
    public function get_heirarchy($id)
    {
        if($this->session->userdata('uid'))
             {
                try
                {
                        $hierarchydata = $this->repModel->get_heirarchy($this->session->userdata('uid'),$id);
                        echo json_encode($hierarchydata);
                }
                catch(LConnectApplicationException $e)
                {
                       $this->exception($e);
                }
            }
            else
            {
                redirect('indexController');
            }
    }
    //********************************************************* Representative Analysis***********************************************************
  	public function Daily_Work_Pattern_Analysis($reportid){
  	         if($this->session->userdata('uid'))
             {
                try
                {
                  $this->load->view('reports/manager_work_pattern_analysis_view',$reportid);
                }
                catch(LConnectApplicationException $e)
                {
                        $this->exception($e);
                }
             }
             else
            {
                redirect('indexController');
            }

	}
	public function Closed_Activity_Analysis($reportid){
	       if($this->session->userdata('uid'))
             {
                try
                {
                  $this->load->view('reports/activity_analysis_view',$reportid);
                }
                catch(LConnectApplicationException $e)
                {
                       $this->exception($e);
                }
             }
             else
            {
                redirect('indexController');
            }

	}
	public function Open_Activity_Analysis($reportid){
             if($this->session->userdata('uid'))
             {
                try
                {
                  	$this->load->view('reports/activity_analysis_view',$reportid);
                }
                catch(LConnectApplicationException $e)
                {
                       $this->exception($e);
                }
             }
             else
            {
                redirect('indexController');
            }
	}

	public function Time_Distribution_across_leads_opportunities_Customers($reportid){
	          if($this->session->userdata('uid'))
             {
                try
                {
                  	$this->load->view('reports/time_distribution_analysis_view',$reportid);
                }
                catch(LConnectApplicationException $e)
                {
                        $this->exception($e);
                }
             }
             else
            {
                redirect('indexController');
            }

	}

    public function Time_Distribution_Chart($reportid){
	         if($this->session->userdata('uid'))
             {
                try
                {
                   $this->load->view('reports/time_distribution_analysis_chart_view',$reportid);
                }
                catch(LConnectApplicationException $e)
                {
                        $this->exception($e);
                }
             }
             else
            {
                redirect('indexController');
            }

	}
    public function Punctuality_Score($reportid){
          if($this->session->userdata('uid')){
                try{
                         $this->load->view('reports/punctuality_score_view',$reportid);
                }catch(LConnectApplicationException $e){
                              $this->exception($e);
                }
          }else{
                redirect('indexController');
          }

    }
    public function Productivity_Score_of_Individuals($reportid){
          if($this->session->userdata('uid')){
                try{
                         $this->load->view('reports/rep_productivityScoreView',$reportid);
                }catch(LConnectApplicationException $e){
                              $this->exception($e);
                }
          }else{
                redirect('indexController');
          }
    }
    public function Representative_Cost_Analysis($reportid){
          if($this->session->userdata('uid')){
                try{
                         $this->load->view('reports/rep_costAnalysisView',$reportid);
                }catch(LConnectApplicationException $e){
                              $this->exception($e);
                }
          }else{
                redirect('indexController');
          }
     }
    //********************************************************* Representative Analysis******************End*********************************

    //********************************************************* Manager Analysis*************************************************************
     public function Assignment_Analysis($reportid){
	         if($this->session->userdata('uid'))
             {
                try
                {
                  	$this->load->view('reports/assignment_analysis_view',$reportid);
                }
                catch(LConnectApplicationException $e)
                {
                        $this->exception($e);
                }
             }
             else
            {
                redirect('indexController');
            }

	}
     //********************************************************* Manager Analysis********End***********************************************
 //********************************************************* Opportunity Time Analysis ******Start*******************************************************
     public function Opportunity_Time_Analysis($reportid){
        if($this->session->userdata('uid')){
            try{
              	$this->load->view('reports/opportunity_time_analysis_view',$reportid);
            }
            catch(LConnectApplicationException $e){
                    $this->exception($e);
            }
        }
        else{
            redirect('indexController');
        }

	}
    public function Opportunity_Velocity_Analysis($reportid){
          if($this->session->userdata('uid')){
                try{
                         $this->load->view('reports/opp_velocityAnalysisView',$reportid);
                }catch(LConnectApplicationException $e){
                              $this->exception($e);
                }
          }else{
                redirect('indexController');
          }
     }
     //********************************************************* Opportunity Time Analysis ********End***********************************************

    //********************************************************* Lead Analysis***********************************************************

     public function Lead_Distribution_Analysis($reportid){
		if($this->session->userdata('uid')){
			try{
				$this->load->view('reports/lead_distribution_analysis_view',$reportid);
			}catch(LConnectApplicationException $e){
				$this->exception($e);
			}
		}else{
			redirect('indexController');
		}
    }
	
	public function Lead_FAT_Analysis($reportid){
		if($this->session->userdata('uid')){
			try{
				$this->load->view('reports/lead_fat_analysis_view',$reportid);
			}catch(LConnectApplicationException $e){
				$this->exception($e);
			}
		}else{
			redirect('indexController');
		}
	}
	
	public function Lead_Lifecycle_Analysis($reportid){
		if($this->session->userdata('uid')){
			try{
				$this->load->view('reports/lead_lifecycle_analysis_view',$reportid);
			}catch(LConnectApplicationException $e){
				$this->exception($e);
			}
		}else{
			redirect('indexController');
		}
	}

    public function Lead_Status_Analysis($reportid){
        if($this->session->userdata('uid')){
            try{
                $this->load->view('reports/lead_status_analysis_view',$reportid);
            }catch(LConnectApplicationException $e){
                $this->exception($e);
            }
        }else{
            redirect('indexController');
        }
    }

    public function Lead_Time_Analysis($reportid){
        if($this->session->userdata('uid')){
            try{
                $this->load->view('reports/lead_time_analysis_view',$reportid);
            }catch(LConnectApplicationException $e){
                $this->exception($e);
            }
        }else{
            redirect('indexController');
        }
    }

    public function Lead_Split_Cost_Analysis($reportid){
        if($this->session->userdata('uid')){
            try{
                $this->load->view('reports/lead_split_cost_analysis_view',$reportid);
            }catch(LConnectApplicationException $e){
                $this->exception($e);
            }
        }else{
            redirect('indexController');
        }
    }

    public function Lead_Activity_Cost_Distribution($reportid){
        if($this->session->userdata('uid')){
            try{
                $this->load->view('reports/lead_activity_cost_distribution_view',$reportid);
            }catch(LConnectApplicationException $e){
                $this->exception($e);
            }
        }else{
            redirect('indexController');
        }
    }
    public function Lead_Spend_Analysis($reportid)
    {
         if($this->session->userdata('uid'))
             {
                try
                {
                  	$this->load->view('reports/lead_spend_analysis_view',$reportid);
                }
                catch(LConnectApplicationException $e)
                {
                        $this->exception($e);
                }
             }
             else
            {
                redirect('indexController');
            }
    }
    public function Lead_Resource_Cost_Split($reportid){
        if($this->session->userdata('uid')){
            try{
                $this->load->view('reports/lead_resource_cost_spit_view',$reportid);
            }catch(LConnectApplicationException $e){
                $this->exception($e);
            }
        }else{
            redirect('indexController');
        }
    }
    public function Lead_Activity_Cost_Split($reportid){
        if($this->session->userdata('uid')){
            try{
                $this->load->view('reports/lead_activity_cost_split_view',$reportid);
            }catch(LConnectApplicationException $e){
                $this->exception($e);
            }
        }else{
            redirect('indexController');
        }
    }
    public function Lead_Average_Activity_Cost($reportid){
        if($this->session->userdata('uid')){
            try{
                $this->load->view('reports/lead_average_activity_cost_view',$reportid);
            }catch(LConnectApplicationException $e){
                $this->exception($e);
            }
        }else{
            redirect('indexController');
        }
    }
     public function Lead_Score_Analysis($reportid){
        if($this->session->userdata('uid')){
            try{
                $this->load->view('reports/lead_score_analysis_view',$reportid);
            }catch(LConnectApplicationException $e){
                $this->exception($e);
            }
        }else{
            redirect('indexController');
        }
    }

    //********************************************************* Lead Analysis********End************************************************


    //********************************************************* Performance Analysis*************************************************************
     public function Product_Performance_Analysis($reportid){
	         if($this->session->userdata('uid'))
             {
                try
                {
                  	$this->load->view('reports/product_perf_analysis_view',$reportid);
                }
                catch(LConnectApplicationException $e)
                {
                        $this->exception($e);
                }
             }
             else
            {
                redirect('indexController');
            }

	}
    public function Opportunity_Performance_Analysis($reportid)
    {
         if($this->session->userdata('uid'))
             {
                try
                {
                  	$this->load->view('reports/opportunity_perf_analysis_view',$reportid);
                }
                catch(LConnectApplicationException $e)
                {
                        $this->exception($e);
                }
             }
             else
            {
                redirect('indexController');
            }
    }
     //********************************************************* Performance Analysis********End***********************************************
	 
	 
     //********************************************************* Customer Analysis ********Starts***********************************************
	 public function Customer_Retention_Cost_Analysis($reportid)
    {
         if($this->session->userdata('uid'))
             {
                try
                {
                  	$this->load->view('reports/customer_retention_cost_analysis_view',$reportid);
                }
                catch(LConnectApplicationException $e)
                {
                        $this->exception($e);
                }
             }
             else
            {
                redirect('indexController');
            }
    }
     //********************************************************* Customer Analysis ********End***********************************************
	 


	//********************************************************* Exception***********************************************************
	
	public function exception($lae){
		$GLOBALS['$logger']->debug('!!!Exception Thrown to Controller --- Passing as JSON to View!!!');
		$errorArray = array(
			'errorCode' => $lae->getErrorCode(),
			'errorMsg' => $lae->getErrorMessage()
		);
		$GLOBALS['$logger']->debug('Exception JSON to view - '.json_encode($errorArray));
		$GLOBALS['$logger']->debug("/-------------------------------------------------------------------------/");
		echo json_encode($errorArray);
	}
	
	//********************************************************* Exception********End************************************************
}

?>