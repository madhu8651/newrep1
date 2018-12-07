<style>
   .active1{ color: #fff !important;}
   input[type="file"] {
    display: none;
}
.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
</style>


<!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
			<ul class="sidebar-menu">
				<li class="active treeview version_name" style="height:42px"></li>
				<li class="active treeview">
					<a href="<?php echo site_url('admin_dashboardController'); ?>">
						<i class="fa fa-home" ></i> <span>Home</span>
					</a>
				</li>
                
				<li class="treeview">
				  <a href="#">
					<i class="fa fa-table"></i> <span>Functions</span>
					<i class="fa fa-angle-down pull-right"></i>
				  </a>
					<ul class="treeview-menu submenu ">
						<li>
						  <a href="#"><i class="fa fa-circle-o"></i> Company <i class="fa fa-angle-down pull-right"></i></a>
							<ul class="treeview-menu">
							<li><a href="<?php echo site_url('admin_roleController'); ?>" ><i class="fa fa-circle-o"></i> Department & Roles</a></li>
							<li><a href="<?php echo site_url('admin_rolesHierarchyController'); ?>"><i class="fa fa-circle-o"></i>Roles Hierarchy</a></li>
						  </ul>
						</li>
						<li>
						  <a href="#"><i class="fa fa-circle-o"></i> Commerce <i class="fa fa-angle-down pull-right"></i></a>
						  <ul class="treeview-menu">
							<li><a href="<?php echo site_url('admin_currencyController');?>"><i class="fa fa-circle-o"></i> Currency</a></li>
								<li><a href="<?php echo site_url('admin_product_hierarchyController'); ?>"><i class="fa fa-circle-o"></i> Product </a></li>
							<li><a href="<?php echo site_url('admin_industry_hierarchyController'); ?>"><i class="fa fa-circle-o"></i>Clientele Industry </a></li>

						  </ul>
						</li>
                        <!-- support (swati) --- comented by tapash- recomended by prashanth -on 30-03-2018 :removed for version V1.1 15-05-2018: -->
                        <li class="support-nav-li">
						  <a href="#"><i class="fa fa-circle-o"></i> Support Settings <i class="fa fa-angle-down pull-right"></i></a>
						  <ul class="treeview-menu">
							<li><a href="<?php echo site_url('admin_support_processController'); ?>"><i class="fa fa-circle-o"></i> Support Process</a></li>
							<li><a href="<?php echo site_url('admin_support_customController'); ?>"><i class="fa fa-circle-o"></i> Support Attributes</a></li>

						  </ul>
						</li>

						<li>
						  <a href="#"><i class="fa fa-circle-o"></i> Operations <i class="fa fa-angle-down pull-right"></i></a>
							<ul class="treeview-menu">

								<li><a href="<?php echo site_url('admin_holidaysController');?>"><i class="fa fa-circle-o"></i>Holiday Calendar</a></li>

								<li><a href="<?php echo site_url('admin_countryStateController'); ?>"><i class="fa fa-circle-o"></i> Country & State</a></li>

								<li><a href="#"><i class="fa fa-circle-o"></i>Locations <i class="fa fa-angle-down pull-right"></i></a>
									<ul class="treeview-menu">
										<li><a href="<?php echo site_url('admin_office_location'); ?>"><i class="fa fa-circle-o"></i> Office Locations</a></li>
										<li><a href="<?php echo site_url('admin_blocation_hierarchyController'); ?>"><i class="fa fa-circle-o"></i> Business Locations</a></li>
									</ul>
								</li>                                
								<li><a href="<?php echo site_url('admin_teamController'); ?>"><i class="fa fa-circle-o"></i> Teams</a></li>
								<li><a href="#"><i class="fa fa-circle-o"></i> Persona <i class="fa fa-angle-down pull-right"></i></a>
									<ul class="treeview-menu submenu">
										<li>
											<a href="<?php echo site_url('admin_buyerpersonaController'); ?>"><i class="fa fa-circle-o"></i> Buyer Persona</a>
										</li>
										<li>
											<a href="<?php echo site_url('admin_salespersonaController'); ?>"><i class="fa fa-circle-o"></i> Executive Persona</a>
										</li>
									 </ul>
								</li>
                                <li><a href="<?php echo site_url('admin_activityController'); ?>"><i class="fa fa-circle-o"></i>Activity</a></li> 
							</ul>
						</li>
						<li>
						  <a href="#"><i class="fa fa-circle-o"></i> Marketing <i class="fa fa-angle-down pull-right"></i></a>
						  <ul class="treeview-menu">
							<li><a href="<?php echo site_url('admin_leadsourceController'); ?>"><i class="fa fa-circle-o"></i> Lead Source</a></li>
						  </ul>
						</li>
					</ul>
				</li>
                <!--<li class="treeview">
        				<li>
        					<a href="<?php echo site_url('admin_distribution_matrixController'); ?>"><i class="fa fa-envelope"></i> <span>Distribution matrix</span></a>
        				</li>
                </li>-->
                <li class="treeview">
                      <a href="#">
                          <i class="fa fa-envelope"></i> <span>E-Mail Settings</span>
                          <i class="fa fa-angle-down pull-right"></i>
                      </a>
                      <ul class="treeview-menu submenu ">
                          <li>
                              <a href="<?php echo site_url('admin_lconnectt_mail_setting_controller'); ?>"><i class="fa fa-circle-o"></i> <span>Admin Mail Settings</span></a>
                          </li>
                          <li>
                              <a href="<?php echo site_url('admin_usermailController'); ?>"><i class="fa fa-circle-o"></i> <span>User Mail Settings</span></a>
                          </li>
                      </ul>
                </li>

				<li class="treeview">
					<a href="<?php echo site_url('admin_customFieldController'); ?>"><i class="fa fa-table"></i> <span>Custom Fields</span></a>
				</li>
				<li class="treeview">
					<a href="<?php echo site_url('admin_userController1'); ?>"><i class="fa fa-users"></i> <span>Users</span></a>
				</li>

				<li class="treeview">
					<a href="<?php echo site_url('admin_mgr_repController'); ?>"><i class="fa fa-sitemap"></i> <span>Reporting Tree</span></a>
				</li>
				
				<li>
					<a href="#">
						<i class="fa fa-cart-arrow-down"></i>
						<span>Sales</span>
						<i class="fa fa-angle-down pull-right"></i>
					</a>
					<ul class="treeview-menu submenu">
                        <li class="MasterCycleNav"><a href="<?php echo site_url('admin_mastersales_cycleController'); ?>"><i class="fa fa-circle-o" target="def"></i>Master Sales Cycle </a></li>
						<!--<li><a href="#"><i class="fa fa-circle-o"></i> Master Cycle <i class="fa fa-angle-down pull-right"></i></a>  -->
						   <!--	<ul class="treeview-menu submenu"> -->

								<!-- Removed for  m33 requirement---------
								<li><a href="<?php echo site_url('admin_mastersales_stageController'); ?>"><i class="fa fa-circle-o"></i> Master Stage Flowchart</a></li>-->
						   <!--	</ul> -->
						<!--</li>  -->
						<li><a href="#"><i class="fa fa-circle-o"></i> Sales Cycle <i class="fa fa-angle-down pull-right"></i></a>
							<ul class="treeview-menu submenu">
							 
								<li class="SalesCycleNav"><a href="<?php echo site_url('admin_sales_cycleController'); ?>"><i class="fa fa-circle-o" target="def"></i> Sales Cycle </a></li>
								<li><a href="<?php echo site_url('admin_salescycle_parameterController'); ?>"><i class="fa fa-circle-o" target="def"></i> Sales Cycle Parameter</a></li>
								<!-- Removed for  m33 requirement---------<li><a href="<?php echo site_url('admin_sales_stageController'); ?>"><i class="fa fa-circle-o"></i> Sales Stage Flowchart</a></li>-->
								<li><a href="<?php echo site_url('admin_qualifiersController'); ?>"><i class="fa fa-circle-o" target="def"></i> Qualifiers</a></li>
								<li><a href="<?php echo site_url('admin_sales_stage_flowchartController'); ?>"><i class="fa fa-circle-o" target="def"></i> Stage Attributes</a></li>

							</ul>
						</li>
                         <li class="treeview">
					            <a href="<?php echo site_url('LeadUploadView_Controller'); ?>"><i class="fa fa-circle-o"></i> <span>Lead Excel Upload</span></a>
				        </li>
					</ul>
				</li>
				<!-- support (swati) --- comented by tapash- recomended by prashanth -on 30-03-2018 :removed for version V1.1 15-05-2018:  -->
                <li class="support-nav-li">
					<a href="#">
						<i class="fa fa-cart-arrow-down"></i>
						<span>Support</span>
						<i class="fa fa-angle-down pull-right"></i>
					</a>
					<ul class="treeview-menu submenu">
                        <li class="MasterSupportCycle"><a href="<?php echo site_url('admin_sup_mastersales_cycleController'); ?>"><i class="fa fa-circle-o" target="def"></i>Support Master Cycle </a></li>
						<!--<li><a href="#"><i class="fa fa-circle-o"></i>Support Master Cycle <i class="fa fa-angle-down pull-right"></i></a>
							<ul class="treeview-menu submenu">

								<li><a href="<?php echo site_url('admin_sup_mastersales_stageController'); ?>"><i class="fa fa-circle-o"></i>Support Master Stage Flowchart</a></li>
							</ul>
						</li>-->
						<li><a href="#"><i class="fa fa-circle-o"></i> Support Cycle <i class="fa fa-angle-down pull-right"></i></a>
							<ul class="treeview-menu submenu">

								<li class="SupportCycleNav"><a href="<?php echo site_url('admin_sup_sales_cycleController'); ?>"><i class="fa fa-circle-o" target="def"></i> Support Cycle </a></li>
								<li><a href="<?php echo site_url('admin_sup_salescycle_parameterController'); ?>"><i class="fa fa-circle-o" target="def"></i> Support Cycle Parameter</a></li>
								<!--<li><a href="<?php echo site_url('admin_sup_sales_stageController'); ?>"><i class="fa fa-circle-o"></i> Support Stage Flowchart</a></li>-->
								<li><a href="<?php echo site_url('admin_sup_qualifiersController'); ?>"><i class="fa fa-circle-o" target="def"></i>Support Qualifiers</a></li>
								<li><a href="<?php echo site_url('admin_sup_sales_stage_flowchartController'); ?>"><i class="fa fa-circle-o" target="def"></i>Support Stage Attributes</a></li>

							</ul>
						</li>
					</ul>
				</li>

				<!--------------------------------------removed for version V1.1 15-05-2018
                <li class="treeview">
                    <a href="#">
                            <i class="fa fa-envelope"></i><span>Communicator Settings</span>
                            <i class="fa fa-angle-down pull-right"></i>
                    </a>
                    <ul class="treeview-menu submenu ">
                        <li>
                            <a href="<?php echo site_url('admin_personal_groupmail_settingController'); ?>"><i class="fa fa-circle-o"></i> <span>Group Mail Settings</span></a>
                        </li>
                    </ul>
                </li>
                 --------------------------------------- -->


				<!--<li class="treeview">
					<a href="list_customfields.php" target="def">
						<i class="fa fa-users"></i> <span>Custom Fields</span>
					</a>
				</li>-->
				
				<!--<li class="treeview">
					<a href="#" target="def">
						<i class="fa fa-comments-o"></i> <span>Communicator Settings</span> <i class="fa fa-angle-down pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li><a href="chat_settings.php" target="def"><i class="fa fa-circle-o"></i> Chat Settings</a></li>
						<li><a href="list_email_settings.php" target="def"><i class="fa fa-circle-o"></i> Emails Settings</a></li>
						<li><a href="notice_settings.php" target="def"><i class="fa fa-circle-o"></i> Notice Settings</a></li>
					</ul>
			  </li>-->
			</ul>
        </section>
        <!-- /.sidebar -->
    </aside>
<script>
	$(document).ready(function(){
		var row = "";
		if(versiontype == "lite"){
			row = "<div class='version_type'>Lite</div>";
			$('.support-nav-li').hide();
		}else if(versiontype == "standard"){
			row = "<div class='version_type'>Professional</div>";
			$('.support-nav-li').show();
		}else if(versiontype == "premium"){
			row = "<div class='version_type'>Premium</div>";
			$('.support-nav-li').show();
		}else{
			row = "";
		}
		$(".version_name").empty().append(row);
	});
</script>





