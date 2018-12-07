<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Controller extends CI_Controller{
	//$timezone = $this->session->userdata('timezone');

	public function __construct(){
		$status = parent::__construct();
		if($status!=1){
            if($this->session->userdata('uid')){
                redirect('loginController/logout');
            }else{
                redirect('inactive_client_controller');
            }
        }
        $query = $this->db->query('SELECT client_timezone FROM client_info');
        $res = $query->result();
        $timezone = $res[0]->client_timezone;
		date_default_timezone_set($timezone);
		if($this->session->userdata('uid')){
			if(!$this->checkClient()){
				$url = $this->session->userdata('login_url');
            	redirect($url."loginController/logout");
			}else{
				$classname = get_class($this);
				$active_module = $_SESSION['active_module_name'];

				switch ($active_module) {
					case 'admin': $admin_class = array(
													'admin_activityController',
													'admin_blocation_hierarchyController',
													'admin_buyerpersonaController',
													'admin_calendarController',
													'admin_countryStateController',
													'admin_currencyController',
													'admin_customFieldController',
													'admin_dashboardController',
													'admin_departmentController',
													'admin_holidaysController',
													'admin_industry_hierarchyController',
													'admin_leadsourceController',
													'admin_leaveController',
													'admin_mangelicenseController',
													'admin_mastersales_cycleController',
													'admin_mastersales_stageController',
													'admin_mgr_repController',
													'admin_office_location',
													'admin_product_hierarchyController',
													'admin_qualifiersController',
													'admin_questionanswerController',
													'admin_roleController',
													'admin_rolesHierarchyController',
													'admin_sales_cycleController',
													'admin_sales_stage_flowchartController',
													'admin_sales_stageController',
													'admin_salescycle_parameterController',
													'admin_salespersonaController',
													'admin_sidenavController',
													'admin_teamController',
													'admin_userController1',
													'admin_lconnectt_mail_setting_controller',
													'notificationController',
													'indexController',
													'loginController',
													'lconnectt_commonController',
													'admin_customFieldController',
                                                    'admin_support_processController',
													'admin_support_customController',
                                                    'admin_sup_mastersales_cycleController',
                                                    'admin_sup_mastersales_stageController',
                                                    'admin_sup_sales_cycleController',
                                                    'admin_sup_sales_stageController',
                                                    'admin_sup_qualifiersController',
                                                    'admin_sup_salescycle_parameterController',
                                                    'admin_sup_sales_stage_flowchartController',
													'admin_personal_groupmail_settingController',
													'admin_distribution_matrixController',
                                                    'admin_usermailController',
                                                    'sales_com_personalmailController',
                                                    'LeadUploadView_Controller',
                                                    'manager_teamManagersController'
												);
									if(!in_array($classname,$admin_class)){
										redirect($url."admin_dashboardController");
									}
						break;
					case 'manager':	$manager_class = array(
														'manager_calendarController',
														'manager_contacts',
														'manager_customerController',
														'manager_dashboardController',             
														'manager_hierarchyController',
														'manager_leadController',                  
														'manager_mgr_repController',
														'manager_mytaskController',                
														'manager_opportunitiesController',
														'manager_salesRepresentativeController',   
														'manager_teamManagersController',
														'manager_WorkPatternController',
														'common_opportunitiesController',
														'admin_sidenavController',
														'notificationController',
														'indexController',
														'loginController',
														'lconnectt_commonController',
                                                        'manager_dashboardsettingController',
														'sales_com_groupmailController',
														'sales_com_personalmailController',
														'leadinfo_controller',
                                                        'sales_opportunitiesController'
									);
									if(!in_array($classname,$manager_class)){
										redirect($url."manager_dashboardController");
									}
						break;
					case 'sales': $sales_class = array(
													'sales_calendarController',
													'sales_contactListController',
													'sales_customerController',
													'sales_mytaskController',
													'sales_opportunitiesController',
													'common_opportunitiesController',
													'admin_sidenavController',
													'leadinfo_controller',
													'notificationController',
													'indexController',
													'loginController',
													'lconnectt_commonController',
													'sales_com_groupmailController',
													'sales_com_personalmailController',
													'manager_teamManagersController'

								  );
								if(!in_array($classname,$sales_class)){
									redirect($url."sales_mytaskController");
								}
						break;
				}
			}
		}else{
			redirect('indexController');
		}
		
	}

	public function checkClient(){
		$originalclientid = $this->session->userdata('clientid');
		$clientid = basename(dirname($_SERVER['SCRIPT_FILENAME']));
		if($originalclientid==$clientid){
			return true;
		}else{
			return false;
		}
	}
}