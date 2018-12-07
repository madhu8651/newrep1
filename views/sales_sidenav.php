
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
.my_cust,.my_leads{
	display:none;
}
</style>
<script>
$.ajax({ 
	type : "POST",
	url : "<?php echo site_url('sales_customerController/userPrivilages'); ?>",
	dataType : 'json',		
	cache : false,
	success : function(data)	{
		console.log(data)
		if(data.customers == 0){
			$(".my_cust").hide();
		} else {
			$(".my_cust").show();
		}

		if(data.leads == 0){
			$('.my_leads').hide();
		}else{
			$('.my_leads').show();
		}
		if(versiontype == 'lite'){
			$('.support-nav-li').hide();
		}else{
			$('.support-nav-li').show();
		}
	}
});

</script>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel -->
		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu">

			<li class="active treeview version_name" style="height:42px"></li>

			<li class="active treeview"><a href="<?php echo site_url('sales_mytaskController'); ?>"><i class="fa fa-home" ></i>
				<span>Home</span></a></li>

			<li class="treeview"><a href="<?php echo site_url('sales_mytaskController'); ?>"><i class="fa fa-tasks" ></i>
				<span>My Tasks</span></a></li>

			<li class="treeview"><a href="<?php echo site_url('sales_calendarController'); ?>"><i class="fa fa-calendar"></i>
				<span>Calendar</span></a></li>

			<li class="treeview my_leads"> <a href="#"><i class="fa fa-users"></i> 
				<span>Leads</span> <i class="fa fa-angle-down pull-right"></i></a>
				<ul class="treeview-menu">
					<li><a href="<?php echo site_url('leadinfo_controller'); ?>" ><i class="fa fa-circle-o"></i>
						<span>New</span></a></li>
					<li><a href="<?php echo site_url('leadinfo_controller/accepted_leads'); ?>" ><i class="fa fa-circle-o"></i>
						<span>Accepted</span></a></li>
					<li><a href="<?php echo site_url('leadinfo_controller/in_progress'); ?>" ><i class="fa fa-circle-o"></i>
						<span>In Progress</span></a></li>
					<li><a href="#" ><i class="fa fa-circle-o"></i>
						<span>Closed</span><i class="fa fa-angle-down pull-right"></i></a>
						<ul class="treeview-menu submenu">
							<li><a href="<?php echo site_url('leadinfo_controller/closed'); ?>"><i class="fa fa-circle-o"></i>
								<span>Won</span></a></li>
							<li><a href="<?php echo site_url('leadinfo_controller/closed_lost'); ?>"><i class="fa fa-circle-o"></i>
								<span>Lost</span></a></li>
						</ul>
					</li>
				</ul>
			</li>

			<li class="treeview"> <a href="#"><i class="fa fa-money"></i>
				<span>Opportunities</span> <i class="fa fa-angle-down pull-right"></i></a>
				<ul class="treeview-menu ">
					<li><a href="<?php echo site_url('sales_opportunitiesController/new_opportunities'); ?>"><i class="fa fa-circle-o"></i>
						<span>New</span></a></li>
					<li><a href="<?php echo site_url('sales_opportunitiesController/inprogress_opportunities'); ?>"><i class="fa fa-circle-o"></i>
						<span>In Progress</span></a></li>
                    <li><a href="#" ><i class="fa fa-circle-o"></i>
						<span>Closed</span><i class="fa fa-angle-down pull-right"></i></a>
						<ul class="treeview-menu submenu">
							<li><a href="<?php echo site_url('sales_opportunitiesController/closed_opportunities'); ?>"><i class="fa fa-circle-o"></i>
								<span>Won</span></a></li>
							<li><a href="<?php echo site_url('sales_opportunitiesController/closed_lost_opportunities'); ?>"><i class="fa fa-circle-o"></i>
								<span>Lost</span></a></li>
						</ul>
					</li>

				</ul>
			</li>

			<li class="treeview"><a href="<?php echo site_url('sales_contactListController'); ?>"><i class="fa fa-phone-square"></i>
				<span>Contacts</span></a></li>

			<li class="treeview my_cust"><a href="<?php echo site_url(''); ?>"><i class="fa fa-male"></i>
				<span>Customers</span><i class="fa fa-angle-down pull-right"></i></a>
				<ul class="treeview-menu">
					<li><a href="<?php echo site_url('sales_customerController/assignedCustomerView'); ?>" ><i class="fa fa-download"></i>
						<span>Received</span></a></li>
					<li><a href="<?php echo site_url('sales_customerController/acceptedCustomerView'); ?>" ><i class="fa fa-users"></i>
						<span>Accepted</span></a></li>
					<li><a href="<?php echo site_url('sales_customerController/myCustomerView'); ?>" ><i class="fa fa-users"></i>
						<span>My Customers</span></a></li>
				</ul>
			</li>
			
			<li class="treeview">
				<a href="<?php echo site_url('sales_com_personalmailController'); ?>">
					<i class="fa fa-at"></i> 
					<span>User Email</span>
				</a>
			</li>
            <!-- --- comented by tapash- recomended by prashanth -on 30-03-2018  removed for version V1.1 15-05-2018 
			<li class="treeview support-nav-li">
				<a href="<?php echo site_url(''); ?>">
					<i class="fa fa-male"></i>
					<span>Support Request</span>
					<i class="fa fa-angle-down pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo site_url('sales_supportController'); ?>" >
							<i class="fa fa-download"></i>
							<span>Received</span>
						</a>
					</li>
					<li>
						<a href="<?php echo site_url('sales_supportController/inprogres_request'); ?>" >
							<i class="fa fa-users"></i>
							<span>In progress</span>
						</a>
					</li>
					<li>
						<a href="<?php echo site_url('sales_supportController/closed_request'); ?>" >
							<i class="fa fa-users"></i>
							<span>Closed</span>
						</a>
					</li>
				</ul>
			</li>
			-->
			<!--
			 <li class="treeview my_cust"><a href="<?php echo site_url(''); ?>"><i class="fa fa-male"></i>
				<span>Communicator</span><i class="fa fa-angle-down pull-right"></i></a>
				<ul class="treeview-menu">
					<li><a href="#" ><i class="fa fa-users"></i>
						<span>User Mail</span></a></li>
					<li><a href="<?php echo site_url('sales_com_groupmailController'); ?>" ><i class="fa fa-users"></i>
						<span>Group Mail</span></a></li>
				</ul>
			</li> 
			-->

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

     

	 

