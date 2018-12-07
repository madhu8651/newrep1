<!DOCTYPE html>
<html lang="en">
	<head>
	<?php require 'scriptfiles.php' ?>
	<style>
	.inputBottomGap{
		margin-bottom: 10px;
	}
	.warning-alert	{
		color: darkorange;
	}
	.multiselect1{
		height: 150px;
		overflow: auto;
		border: 1px solid #ccc;
		border-radius: 5px;
  	}
  	.multiselect1 ul{
    	padding: 0px;
  	}
  	.multiselect1 ul li.sel{
    	background: #ccc;
  	}
  	.multiselect1 ul li{
    	padding: 0 10px;
    	text-align: left;
  	}
	.ajax-loader {
		position: absolute;
		width: 32px;
		height: 32px;
		left: 5%;
		top: 5%;
	}
	</style>
	<script>
	var sell_types = JSON.parse('<?php echo(json_encode($sell_types));?>');
/*	for (var i = 0; i < sell_types.length; i++) {
		if(sell_types[i] == 'new_sell')	{
			sell_types[i] = 'New Sell';
		} else if(sell_types[i] == 'up_sell')	{
			sell_types[i] = 'Up Sell';
		} else if(sell_types[i] == 'cross_sell')	{
			sell_types[i] = 'Cross Sell';
		}
	}
*/	var leads;
	var users;
	var createOppo_lead_name = '';
	var createOppo_product_name = '';
	$(document).ready(function() {

		var msg = `<?php 
				if (isset($message)) { 
					echo $message; 
				}?>`;
		if (msg.length > 0) {
			alert(msg);			
		}
		/*get if its a cross sell or up sell or new sell while submtting the form*/
		pageload();		
		$('#target_list').on('change',function(){
			$('#target_contact_list').empty();
			$('#product_list').val("");
			$('#currency_list').val("");
			$('#to_user_id, #stage_user_id').empty();
			createOppo_product_name = '';
			createOppo_lead_name = $(this).find('option:selected').text();
			build_Name();

			var id = $(this).val();
			var industry_id = '';
			var location_id = '';

			if(id != '')	{
				for (var i = leads.length - 1; i >= 0; i--) {
					if (leads[i].lead_id == id)	{
						industry_id = leads[i].industry;
						location_id = leads[i].bloc;
						break;
					}
				}
			}

			console.log(id);
			console.log(industry_id);
			console.log(location_id);

			if ((id != '') && ((industry_id != '')&&(industry_id != null)) && ((location_id != '')&&(location_id != null))) {
				/*start the loader for contacts*/
				$("#industry_list").val(industry_id);
				$("#location_list").val(location_id);
				$("#target_contact_list").css({
												'background':'url(<?php echo base_url();?>images/hourglass.gif)',
												'background-position':'center',
												'background-size':'30px',
												'background-repeat':'no-repeat'
												})
				$("#target_list").closest("div").find(".warning-alert").text("");


				/*
				make an ajax with lead_id
				also send product_id's from indexedDB, intersect it with selected lead's product
				return lead contacts and intersected products
				*/
				var obj = {};
				obj.lead_id = id;
				obj.products = [];
				obj.industry_id = industry_id;
				obj.location_id = location_id;
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('manager_opportunitiesController/get_contactsAndProducts/').$target; ?>",
					data : JSON.stringify(obj),
					dataType : 'json',
					cache : false,
					success : function(data)	{
						if (error_handler(data)) {
							return ;
						}
						console.log(data);
						$("#target_contact_list").removeAttr('style');
						var leadproducts = data.lead_products;
						if (leadproducts != null)	{
							if (leadproducts.length > 0) {
								$("#target_list").closest("div").find(".warning-alert").text("");
								var select = $("#product_list"), options = "<option value=''>Select Product</option>";
								select.empty();      
								for(var i=0;i<leadproducts.length; i++)	{
									options += "<option value='"+leadproducts[i].products_id+"'>"+ leadproducts[i].products_name +"</option>";
								}
								select.append(options);
							} else {
								$("#target_list").closest("div").find(".warning-alert").text("No products assigned for given lead");
								$("#target_list").focus();
							}
						} else {
							$("#target_list").closest("div").find(".warning-alert").text("No products assigned for given lead");
							$("#target_list").focus();
						}

						var contacts = data.contacts;
						if (contacts != null) {
							if (contacts.length > 0) {
								/*stop loader for contacts*/
						 		var options = "<ul>";
							 	for(var i=0; i < contacts.length; i++ )	{
				     				options +='<li><label><input type="checkbox" value="'+contacts[i].contact_id+'">  '+contacts[i].contact_name+'<label></li>';
								}
								options += "</ul>";
								$('#target_contact_list').empty();
				     			$('#target_contact_list').html(options);								
							} else {
								$("#target_list").closest("div").find(".warning-alert").text("No contacts for given lead");
								$("#target_list").focus();							
							}
						} else {
							$("#target_list").closest("div").find(".warning-alert").text("No contacts for given lead");
							$("#target_list").focus();							
						}
					}
				});
			} else {
				var target = "<?php echo $target; ?>";
				$('#target_list').val("");
				$("#target_list").closest("div").find(".warning-alert").text("Check Industry and Location for selected "+target);
				$("#target_list").focus();
			}		
		});
 		
		$('#product_list').on('change',function(){
			createOppo_product_name = " - "+$(this).find('option:selected').text();
			var product_val = $(this).find('option:selected').val();
			if (product_val != '') 
				build_Name();
			$("#product_list").closest("div").find(".error-alert").text("");
			$("#product_list").closest("div").find(".warning-alert").text("");
			$("#currency_list").closest("div").find(".error-alert").text("");
			$("#currency_list").closest("div").find(".warning-alert").text("");
			var product = {}
			product.product_id = $(this).find('option:selected').val();
			product.target_type = "<?php echo $target; ?>"
			product.target_id = $("#target_list").val();

			console.log(product);
			if (product.product_id && product.target_id) {
				/* 
				AJAX to fetch if it's up sell of cross sell
				set the #target appropriately.
				fetch currencies for selected product as well
				*/
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('manager_opportunitiesController/get_sellType'); ?>",
					dataType : 'json',
					cache : false,
					data: JSON.stringify(product),
					success : function(data)	{
						if (error_handler(data)) {
							return ;
						}
						console.log(data);
						var currency = data.currency.currency;
						var sell_type = data.sellType.sell_type;
						$("#sell_type").val(data.sellType.sell_type);
						if (data.sellType.prompt.length > 0) {
							$("#product_list").closest("div").find(".warning-alert").text(data.sellType.prompt);
						}
						if ((data.currency == null) || (data.currency == '')) {
							var msg = "No currencies defined. Contact admin";
							$("#currency_list").closest("div").find(".error-alert").text(msg);
						} else {
							for(i=0; i < currency.length; i++ )	{     
								var select = $("#currency_list"), options = "<option value=''>Select Currency</option>";
								select.empty();      
								options += "<option value='"+currency[i].currency_id+"'>"+ currency[i].currency_name +"</option>";
								select.append(options);          
							}
						}
					}
				});
			}
		});
		$('#currency_list').on('change',function(){
			var currency_val = $(this).find('option:selected').val();
			//if (currency_val != '') 			
				//get_users();
		});
	}); /*document.getReady ends*/
	
	function get_users() {
		// loading icon starts on change of any above parameters
		// load stops only when
		$("#to_user_id, #stage_user_id").css({
								'background':'url(<?php echo base_url();?>images/hourglass.gif)',
								'background-position':'center',
								'background-size':'30px',
								'background-repeat':'no-repeat'
								})		
		var obj = {};
		obj.ind_id = $("#industry_list").val();
		obj.loc_id = $("#location_list").val();
		obj.prod_id = $("#product_list").val();
		obj.sell_type = $("#sell_type").val();
		obj.currency = $("#currency_list").val();
		console.log(obj);
		$('#to_user_id, #stage_user_id').empty();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_opportunitiesController/get_users'); ?>",
			dataType : 'json',
			cache : false,
			data: JSON.stringify(obj),
			success : function(data)	{
				if (error_handler(data)) {
					return ;
				}		
				/* Users Initialization*/
				$("#to_user_id, #stage_user_id").removeAttr('style');
				users = data;
		 		var ownerlist = "<ul>";
		 		var stagelist = "<ul>";
			 	for(var i=0; i < users.length; i++ )	{
	            	if(users[i].sales_module=='0' && users[i].manager_module!='0'){
	            		ownerlist +='<li><label><input type="checkbox" class="mgrlist_manager_owner" value="manager" id="'+users[i].user_id+'">  '+users[i].user_name+' (Manager)<label></li>';
	            		stagelist +='<li><label><input type="checkbox" class="mgrlist_manager_stage" value="manager" name="'+users[i].user_id+'">  '+users[i].user_name+' (Manager)<label></li>';
	            	}
	            	if(users[i].sales_module!='0' && users[i].manager_module=='0'){
	            		ownerlist +='<li><label><input type="checkbox" class="mgrlist_sales_owner" value="sales" id="'+users[i].user_id+'">  '+users[i].user_name+' (Sales)<label></li>';
	            		stagelist +='<li><label><input type="checkbox" class="mgrlist_sales_stage" value="sales" name="'+users[i].user_id+'">  '+users[i].user_name+' (Sales)<label></li>';
	            	}
	            	if(users[i].sales_module!='0' && users[i].manager_module!='0'){
	            		ownerlist +='<li><label><input type="checkbox" class="mgrlist_manager_owner" value="manager" id="'+users[i].user_id+'">  '+users[i].user_name+' (Manager)<label></li>';
	            		ownerlist +='<li><label><input type="checkbox" class="mgrlist_sales_owner" value="sales" id="'+users[i].user_id+'">  '+users[i].user_name+' (Sales)<label></li>';

	            		stagelist +='<li><label><input type="checkbox" class="mgrlist_manager_stage" value="manager" name="'+users[i].user_id+'">  '+users[i].user_name+' (Manager)<label></li>';
	            		stagelist +='<li><label><input type="checkbox" class="mgrlist_sales_stage" value="sales" name="'+users[i].user_id+'">  '+users[i].user_name+' (Sales)<label></li>';
	               	}
				}
				ownerlist += "</ul>";
				$('#to_user_id, #stage_user_id').empty();
     			$('#to_user_id').html(ownerlist);
     			$('#stage_user_id').html(stagelist);
     			
     		}						
     	});
	}

	function pageload() {
		loaderShow();
		init();
		var target = "<?php echo $target; ?>";
		$("#target_placeholder").text(target + " *");
		/*$("#target_contact_list_placeholder").text(target+" Contact");*/
		$("#pagetitle").text("Creating an Opportunity for " + target);
	}
	
	function init()	{
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_opportunitiesController/init/').$target; ?>",
			dataType : 'json',
			cache : false,
			success : function(data)	{
				if (error_handler(data)) {
					return ;
				}
				console.log(data);
				loaderHide();
				var target = "<?php echo $target; ?>";

				leads = data.leads;
				var select = $("#target_list"), options = "<option value=''>Select a "+target+" for Opportunity</option>";
				select.empty();      
				for(var i=0;i<leads.length; i++)	{
					options += "<option value='"+leads[i].lead_id+"'>"+ leads[i].lead_name +"</option>";              
				}
				select.append(options);          
			}
		});
	}

	function build_Name() {
		$('#opportunities_name').empty().removeAttr('placeholder').val(createOppo_lead_name+createOppo_product_name);
	}

	function add()	{
		var addObj={};		
		if($("#target_list").val()=="")	{
			$("#target_list").closest("div").find(".error-alert").text("<?php echo $target;?> selection is required.");
			$("#target_list").focus();
			return;
		} else {
			$("#target_list").closest("div").find(".error-alert").text("");
		}

		addObj.opportunity_contact = [];
		$("#target_contact_list input[type=checkbox]").each(function(){
  			if($(this).prop('checked') == true){
 				addObj.opportunity_contact.push($(this).val());
  			}
		});

		if(addObj.opportunity_contact.length == 0)	{
			$("#target_contact_list").closest("div").find("span").text("At least one Contact is required.");
			$("#target_contact_list").focus();
			return;
		} else {
			$("#target_contact_list").closest("div").find("span").text("");
		}

		if($("#product_list").val()=="")	{
			$("#product_list").closest("div").find(".error-alert").text("Choose a Product.");
			$("#product_list").closest("div").find(".warning-alert").text("");
			$("#product_list").focus();
			return;
		} else {
			$("#product_list").closest("div").find(".warning-alert").text("");
			$("#product_list").closest("div").find(".error-alert").text("");
		}
		if($("#sell_type").val()=="")	{
			$("#product_list").closest("div").find(".error-alert").text("Check your permissions for Sell type");
			$("#product_list").closest("div").find(".warning-alert").text("");
			$("#product_list").focus();
			return;
		} else {
			$("#product_list").closest("div").find(".warning-alert").text("");
			$("#product_list").closest("div").find(".error-alert").text("");
		}

		if($("#currency_list").val()=="")	{
			$("#currency_list").closest("div").find(".error-alert").text("Choose a Currency.");
			$("#currency_list").closest("div").find(".warning-alert").text("");
			$("#currency_list").focus();
			return;
		} else {
			$("#currency_list").closest("div").find(".warning-alert").text("");
			$("#currency_list").closest("div").find(".error-alert").text("");
		}

		if($("#opportunities_name").val()=="")	{
			$("#opportunities_name").closest("div").find("span").text("Opportunity Name is Required.");
			$("#opportunities_name").focus();
			return;
		} else {
			$("#opportunities_name").closest("div").find("span").text("");
		}
		loaderShow();
		$("#create_opp_btn").attr('disabled', true);
		var id = $("#target_list").val();
			addObj.target = $("#target").val();
			addObj.lead_cust_id = $("#target_list").val();
			addObj.opportunity_name = $("#opportunities_name").val();
			addObj.product_list = $("#product_list").val();
			addObj.currency_list = $("#currency_list").val();
			addObj.sell_type = $("#sell_type").val();
			
			addObj.industry_list = $("#industry_list").val();
			addObj.location_list = $("#location_list").val();
			addObj.opp_remarks = $("#opportunities_remarks").val();
		console.log(addObj);

		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_opportunitiesController/get_SalesCycle2'); ?>",
			dataType : 'json',
			cache : false,
			data: JSON.stringify(addObj),
			success : function(data)	{
				if (error_handler(data)) {
					return ;
				}
				console.log(data);
				if (data.status != false) {
					var opp_id = data.opportunity_id;
					document.getElementById('createOppoForm').action = "<?php echo site_url('manager_opportunitiesController/stage_view/'); ?>"+opp_id;
					document.getElementById("createOppoForm").submit();
				} else {
					alert(data.message);
					loaderHide();
					$("#create_opp_btn").attr('disabled', false);
				}
			}
		});
	}

	</script>
	
	</head>
	<body class="hold-transition skin-blue sidebar-mini"> 
	<div class="loader">
  		<center><h1 id="loader_txt"></h1></center>  
 	</div>  
		<?php require 'demo.php'  ?>
		<?php require 'manager_sidenav.php' ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
				<div class="row header1">				
					<div class="col-xs-2 col-sm-2 col-md-3 col-lg-3 aa">
					</div>
					<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6 pageHeader1 aa">
					</div>
					<div class="col-xs-2 col-sm-2 col-md-3 col-lg-3  aa">
 					</div>
					<div style="clear:both"></div>
				</div>
			<div>
				<center>
					<h3 id="pagetitle"> </h3>
				</center>
				<form class="form" action="#" method="post" name="adminClient" id="createOppoForm">
					<input type="hidden" name="opp_details" id="opp_details" />
				</form>
				<input type="hidden" name="target" id="target" value="<?php echo $target;?>" />
				<input type="hidden" name="industry_list" id="industry_list" />
				<input type="hidden" name="location_list" id="location_list" />
				<input type="hidden" name="sell_type" id="sell_type" value="<?php echo $target;?>" />
				<div class="row inputBottomGap">
					<div class="col-md-6">
						<div class="col-md-3">
							<label for="target_list" id="target_placeholder">Lead *</label> 
						</div>
						<div class="col-md-8">
							<select name="target_list" class="form-control" id="target_list" autofocus>
								<option value=""> Select a Lead for Opportunity</option>
							</select>
							<span class="error-alert"></span>
							<span class="warning-alert"></span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="col-md-3">
							<label for="product_list">Product *</label> 
						</div>
						<div class="col-md-8">
							<select class="form-control" id="product_list" name="product_list" autofocus>
								<option value=""> Select Product</option>
							</select>
							<span class="error-alert"></span>
							<span class="warning-alert"></span>
						</div>
					</div>
				</div>
				<div class="row inputBottomGap">
					<div class="col-md-6">
						<div class="col-md-3">
							<label for="currency_list">Currency *</label> 
						</div>
						<div class="col-md-8">
							<select class="form-control" id="currency_list" name="currency_list" autofocus>
								<option value=""> Select Currency</option>
							</select>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="col-md-3">
							<label for="add_opp" title="Suggested" >Opportunity Name *</label>  
						</div>
						<div class="col-md-8">
							<input type="text" placeholder="Enter a Name for the Opportunity" class="form-control" name="opportunities_name" id="opportunities_name"/>
							<span class="error-alert"></span>
						</div>
					</div>
				</div>
				<div class="row inputBottomGap">
					<div class="col-md-6">
						<div class="col-md-3">
							<label for="target_contact_list" id="target_contact_list_placeholder">Contact(s) *</label> 
						</div>
						<div class="col-md-8">
							<div name="target_contact_list" id="target_contact_list" class="multiselect1">
       						</div>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="col-md-3">
							<label for="add_opp" title="Suggested" >Opportunity Remarks</label>  
						</div>
						<div class="col-md-8">
							<textarea rows="6" cols="50" placeholder="Enter additional remarks for the Opportunity" class="form-control" name="opportunities_remarks" id="opportunities_remarks"></textarea>
							<span class="error-alert"></span>
						</div>
					</div>
				</div>
				<!-- <div class="row inputBottomGap">
					<div class="col-md-6">
						<div class="col-md-3">
							<label for="to_user_id">Assign Opportunity Owner</label> 
						</div>
						<div class="col-md-8">
							<div name="to_user_id" id="to_user_id" class="multiselect1">
								</div>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="col-md-3">
							<label for="stage_user_id">Assign First Stage Owner</label> 
						</div>
						<div class="col-md-8">
							<div name="stage_user_id" id="stage_user_id" class="multiselect1">
								</div>
							<span class="error-alert"></span>
						</div>
					</div>
				</div> -->

				<br>
				<div class="row">	
					<center>
						<input type="button" id="create_opp_btn" class="btn bt" onclick="add()" value="Create" style="margin-right: 10px;" />
						<a href="<?php echo site_url('manager_opportunitiesController/unassigned_opportunities');?>"> 
						<input type="button" class="btn" onclick="cancel()" value="Cancel" /> </a>
					</center>
				</div>
			</div>
			</div>
		</div>
		<?php require 'footer.php' ?>		
	</body>
</html>