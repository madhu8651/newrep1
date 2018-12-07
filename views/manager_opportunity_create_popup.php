<style type="text/css">
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
	.go-top.visible{
		opacity: .75;
	}
	.go-top{
		-moz-border-radius: 7px 7px 0 0;
		-moz-transition: all .3s;
		-o-transition: all .3s;
		-webkit-border-radius: 7px 7px 0 0;
		-webkit-transition: all .3s;
		background: #434343;
		border-radius: 7px 7px 0 0;
		bottom: 4px;
		color: #fff;
		display: block;
		height: 9px;
		opacity: 0;
		padding: 13px 0 45px;
		position: fixed;
		right: 10px;
		text-align: center;
		text-decoration: none;
		transition: all .3s;
		width: 49px;
		z-index: 1040;
		right: 50px;
		border: 1px solid #434343;
	}
	.questions{
		min-height: 50px;
		border: 1px solid #ccc;
		box-shadow: 0px 3px 12px #ccc;
		padding: 15px 20px;
		transition: all 0.5s ease-in-out;
		margin-bottom: 20px;
	}
	.questions i.fa.fa-star-half-o {
		position: absolute;
		left: 2px;
		top: 1px;
	}
	i.fa.fa-star-half-o {
		color:red;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$(".go-top").click(function(){
			$("html, body").animate({ scrollTop: 0 }, "slow");
			return false;
		});
		$(".go-top").click(function() {
			$("body, html").animate({
				scrollTop: 0
			}, 500);
		});
		$(window).scroll(function() {
			var aTop = 500;
			if($(this).scrollTop()>=aTop){
				$(".go-top").addClass("visible");
			} else {
				$(".go-top").removeClass("visible");
			}
		});

		$('#target_list').on('change',function(){
			var target = $("#target").val();

			$('#target_contact_list').empty();

			$('#sell_type').val("");

			$('#product_list').val("");
			createOppo_product_name = '';
            createOppo_sell_type='';

			$('#currency_list').val("");

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

			if ((id != '') && ((industry_id != '')&&(industry_id != null)) && ((location_id != '')&&(location_id != null))) {
				/*start the loader for contacts*/
				$("#industry_list").val(industry_id);
				$("#location_list").val(location_id);
				$("#target_contact_list").css({
					'background':'url(<?php echo base_url();?>images/hourglass.gif)',
					'background-position':'center',
					'background-size':'30px',
					'background-repeat':'no-repeat'
				});
				$("#target_list").closest("div").find(".warning-alert").text("");


				/*
				make an ajax with lead_id and fetch contacts of that lead
				*/
				var obj = {};
				obj.lead_id = id;
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('common_opportunitiesController/get_contacts/'); ?>"+target,
					data : JSON.stringify(obj),
					dataType : 'json',
					cache : false,
					success : function(data)	{
						if (error_handler(data)) {
							return ;
						}
						console.log(data);
						$("#target_contact_list").removeAttr('style');

						var contacts = data.contacts;
						if ((contacts != null) && (contacts.length > 0)) {
							/*stop loader for contacts*/
							var options = "<ul>";
						 	for(var i=0; i < contacts.length; i++ )	{
								options +='<li><label><input type="checkbox" value="'+contacts[i].contact_id+'">'+contacts[i].contact_name+'<label></li>';
							}
							options += "</ul>";
							$('#target_contact_list').empty();
							$('#target_contact_list').html(options);
						} else {
							$("#target_list").closest("div").find(".warning-alert").text("No contacts found");
							$("#target_list").focus();
						}
					}
				});
			} else {
				var target = $("#target").val();
				$('#target_list').val("");
				$("#target_list").closest("div").find(".warning-alert").text("Check Industry and Location for selected "+target);
				$("#target_list").focus();
			}
		});

        //var createOppo_sell_type="";
		$("#sell_type").on('change', function() {
			var target = $("#target").val();

			$('#product_list').val("");
			createOppo_product_name = '';

			$('#currency_list').val("");

			createOppo_sell_type = $(this).find('option:selected').text();
			build_Name();


			var lead_cust_id = $('#target_list').val();
			if (lead_cust_id == '') {
				$("#target_list").closest("div").find(".warning-alert").text("Select a "+target+".");
				$("#target_list").focus();
				return
			} else {
				$("#target_list").closest("div").find(".warning-alert").text("");
				$("#target_list").focus();
			}

			var sell_type = $(this).val();
			if (sell_type != '') {

				var obj = {};
				obj.lead_cust_id = lead_cust_id;
				obj.sell_type = sell_type;
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('common_opportunitiesController/get_products/'); ?>"+target,
					data : JSON.stringify(obj),
					dataType : 'json',
					cache : false,
					success : function(data)	{
						if (error_handler(data)) {
							return ;
						}
						console.log(data);
						var select = $("#product_list"), options = "<option value=''>Select Product</option>";
						select.empty();
						var leadproducts = data.lead_products;
						if ((leadproducts != null) && (leadproducts.length > 0))	{
							$("#product_list").closest("div").find(".warning-alert").text("");
							for(var i=0;i<leadproducts.length; i++)	{
								options += "<option value='"+leadproducts[i].products_id+"'>"+ leadproducts[i].products_name +"</option>";
							}
							select.append(options);
						} else {
							$("#product_list").closest("div").find(".warning-alert").text("No products found");
							$("#product_list").focus();
						}
					}
				});
			}
		});

		$('#product_list').on('change',function(){
			var product_val = $(this).find('option:selected').val();
			if (product_val == '')	{
				return ;
			}
			createOppo_product_name = " - " + $(this).find('option:selected').text();
			build_Name();
			$("#product_list").closest("div").find(".warning-alert").text("");
			$("#currency_list").empty().append("<option value=''>Select Currency</option>");
			$("#product_list").closest("div").find(".error-alert").text("");
			$("#product_list").closest("div").find(".warning-alert").text("");
			$("#currency_list").closest("div").find(".error-alert").text("");
			$("#currency_list").closest("div").find(".warning-alert").text("");
			var product = {}
			product.product_id = $(this).find('option:selected').val();

			console.log(product);
			if (product.product_id != '') {
				/*
				AJAX to fetch if it's up sell of cross sell
				set the #target appropriately.
				fetch currencies for selected product as well
				*/
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('common_opportunitiesController/get_currencies'); ?>",
					dataType : 'json',
					cache : false,
					data: JSON.stringify(product),
					success : function(data)	{
						if (error_handler(data)) {
							return ;
						}
						console.log(data);
						var currency = data.currency.currency;
						if ((data.currency != null) || (data.currency != '') || (currency.length != 0)) {
							var select = $("#currency_list"), options = "<option value=''>Select Currency</option>";
							select.empty();
							for(i=0; i < currency.length; i++ )	{
								options += "<option value='"+currency[i].currency_id+"'>"+ currency[i].currency_name +"</option>";
							}
							select.append(options);
						}
					}
				});
			}
		});
	});

	function show_opp_create(target) {
		$(".warning-alert").empty();
		$("#target_placeholder").text(target + " *");
		$("#target").val(target);
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_opportunitiesController/init/'); ?>"+target,
			dataType : 'json',
			cache : false,
			success : function(data)	{
				if (error_handler(data)) {
					return ;
				}
				console.log(data);
				loaderHide();
				leads = data.leads;
				var select = $("#target_list"), options = "<option value=''>Select a "+target+"</option>";
				select.empty();
				for(var i=0;i<leads.length; i++)	{
					options += "<option value='"+leads[i].lead_id+"'>"+ leads[i].lead_name +"</option>";
				}
				select.append(options);

				var sell_types = data.sell_types.sell_types
				var select = $("#sell_type"), options = "<option value=''>Select a Sell Type</option>";
				select.empty();
				if (data.target == 'Lead') {
					for(var i=0;i<sell_types.length; i++)	{
						if (sell_types[i] == 'new_sell') {
							options += "<option value='"+sell_types[i]+"'> New Sell </option>";
						}
					}
				} else if (data.target == 'Customer') {
					for(var i=0;i<sell_types.length; i++)	{
						if (sell_types[i] == 'up_sell') {
							options += "<option value='"+sell_types[i]+"'> Up Sell </option>";
						} else if (sell_types[i] == 'cross_sell') {
							options += "<option value='"+sell_types[i]+"'> Cross Sell </option>";
						} else if (sell_types[i] == 'renewal') {
							options += "<option value='"+sell_types[i]+"'> Renewal </option>";
						}
					}
				}
				select.append(options);
			}
		});
		$('#create_oppo_modal').modal('show');
	}

	function build_Name() {
		$('#opportunities_name').empty().removeAttr('placeholder').val(createOppo_lead_name+createOppo_product_name+createOppo_sell_type);
	}


	function validate_opp_add() {
		var isValid = true;
		var addObj={};
		if($("#target_list").val()=="")	{
			$("#target_list").closest("div").find(".error-alert").text($("#target").val() + " selection is required.");
			$("#target_list").focus();
			isValid = false;
			return isValid;
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
			$("#contact_div").find("span").text("At least one Contact is required.");
			$("#contact_div").focus();
			isValid = false;
			return isValid;
		} else {
			$("#contact_div").find("span").text("");
		}

		if($("#product_list").val()=="")	{
			$("#product_list").closest("div").find(".error-alert").text("Choose a Product.");
			$("#product_list").closest("div").find(".warning-alert").text("");
			$("#product_list").focus();
			isValid = false;
			return isValid;
		} else {
			$("#product_list").closest("div").find(".warning-alert").text("");
			$("#product_list").closest("div").find(".error-alert").text("");
		}
		if($("#sell_type").val()=="")	{
			$("#product_list").closest("div").find(".error-alert").text("Select a  Sell type");
			$("#product_list").closest("div").find(".warning-alert").text("");
			$("#product_list").focus();
			isValid = false;
			return isValid;
		} else {
			$("#product_list").closest("div").find(".warning-alert").text("");
			$("#product_list").closest("div").find(".error-alert").text("");
		}

		if($("#opportunities_name").val()=="")	{
			$("#opportunities_name").closest("div").find("span").text("Opportunity Name is Required.");
			$("#opportunities_name").focus();
			isValid = false;
			return isValid;
		} else {
			$("#opportunities_name").closest("div").find("span").text("");
		}
		return isValid;
	}

	function add()	{
		if (validate_opp_add() == false) {
			return ;
		}
		loaderShow();
		var addObj = {};
		$("#create_opp_btn").attr('disabled', true);
		var id = $("#target_list").val();
			addObj.target = $("#target").val();
			addObj.opportunity_contact = [];
			$("#target_contact_list input[type=checkbox]").each(function(){
	  			if($(this).prop('checked') == true){
	 				addObj.opportunity_contact.push($(this).val());
	  			}
			});

			addObj.lead_cust_id = $("#target_list").val();
			addObj.opportunity_name = $("#opportunities_name").val();
			addObj.product_list = $("#product_list").val();
			addObj.currency_list = $("#currency_list").val();
			addObj.sell_type = $("#sell_type").val();

			addObj.industry_list = $("#industry_list").val();
			addObj.location_list = $("#location_list").val();
			addObj.opp_remarks = $("#opportunities_remarks").val();
		console.log(addObj);

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
				loaderHide();
				if (data.status != false) {
					var opp_id = data.opportunity_id;
					var url = "<?php echo site_url('manager_opportunitiesController/stage_view/'); ?>"+opp_id;
					window.location.reload();
				} else {
					alert(data.message);
					if (data.qualifier == true) {
						$("#user_id").val("<?php echo $this->session->userdata('uid');?>");
						$("#lead_id").val($("#target_list").val());
						$("#opp_id").val(data.opp_data.opportunity_id);
						$("#stage_id").val(data.opp_data.stage_id);
						$("#cycle_id").val(data.opp_data.cycle_id);
						setup_questionnaire(data.qualifier_data);
					}
				}
				$("#create_opp_btn").attr('disabled', false);
			}
		});
	}

	function setup_questionnaire(data) {
		$('#create_oppo_modal').modal('hide');
		$("#Questionnaire").modal('show');
		$("#Questionnaire").css({
			    "overflow-x": "hidden",
    			"overflow-y": "auto"
		});

		$('#question-list').empty();
		var row = "";
		for(var i=0; i < data[0].question_data.length; i++){
			if( data[0].question_data[i].mandatory_bit == "1" ){
				row +="<div class='questions star col-lg-12'><i class='fa fa-star-half-o' aria-hidden='true'></i><h4 id='"+data[0].question_data[i].question_id+"'>"+ (i+1)+" ) " + data[0].question_data[i].question_text +"</h4>";
			}else{
				row +="<div class='questions col-lg-12'><h4 id='"+data[0].question_data[i].question_id+"'>"+ (i+1)+" ) " + data[0].question_data[i].question_text +"</h4>";
			}
			if(data[0].question_data[i].question_type == 1 || data[0].question_data[i].question_type == 2){
				row +="<ol type='a'>";
				if(data[0].question_data[i].answer_data != null){
					for(var j=0; j < data[0].question_data[i].answer_data.length; j++){
						row +="<li id='"+data[0].question_data[i].answer_data[j].answer_id+"'><label>";
						row +="<input type='radio' name='"+data[0].question_data[i].question_id+"'>";
						row +=data[0].question_data[i].answer_data[j].answer_text;
						row +="</label></li>";
					}
					row+="<input type='hidden' value='"+data[0].question_data[i].question_type+"' id='questiontype'/>"
				 }
			}
			if(data[0].question_data[i].question_type == 3){
				row +="<div class='row'><div class='col-lg-6 col-sm-12 col-xs-12'><textarea rows='3' class='form-control text-ans'/></div></div>";
			}
			row +="</ol>";
			row +="</div>";
		}
		$("#lead_qualifier_id").val(data[0].lead_qualifier_id)
		$("#lead_qualifier_name").text(data[0].lead_qualifier_name)
		$('#question-list').append(row);
	}

	function SubmitQpaper(){
		var mainObj={};
		var someObj=[];
		var someObj1=[];
		var totalQuestions=0;
		var selectedQuestions=0;
		$(".questions").each(function(){
			if($(this).hasClass('star')){
				totalQuestions++;
				if($(this).find("textarea").length > 0){
					$(this).find("textarea").each(function(){
						if($(this).val()==""){
							return;
							$("#mandatory").text("All Questions marked with an asterisk are manadatory");
						}else{
							selectedQuestions++;
							someObj1.push({
								"ans":$(this).val(),
								"quesid":$(this).closest(".col-lg-12").find("h4").attr("id")
							});
							$("#mandatory").text("");
						}
					})
				}else{
					$(this).find("input:radio").each(function(){
						if($(this).is(":checked")){
							selectedQuestions++;
							someObj.push({
								"ansid":$(this).closest("li").attr("id"),
								"quesid":$(this).closest("ol").siblings("h4").attr("id"),
								"questype":$(this).closest("ol").find("input[type=hidden]").attr("value")
							});
							return false;
						}
					})
				}
			}else{
				if($(this).find("textarea").length > 0){
					$(this).find("textarea").each(function(){
						someObj1.push({
							"ans":$(this).val(),
							"quesid":$(this).closest(".col-lg-12").find("h4").attr("id")
						});
					})
				}else{
					$(this).find("input:radio").each(function(){
						if($(this).is(":checked")){
							someObj.push({
								"ansid":$(this).closest("li").attr("id"),
								"quesid":$(this).closest("ol").siblings("h4").attr("id"),
								"questype":$(this).closest("ol").find("input[type=hidden]").attr("value")
							});
						}
					})
				}
			}
		});
		if(totalQuestions != selectedQuestions){
			$("#mandatory").text("All Questions marked with an asterisk are manadatory.");
			return;
		} else {
			$("#mandatory").text("");
			mainObj.lead_qualifier_id=$("#lead_qualifier_id").val();
			mainObj.stage_id=$("#stage_id").val();
			mainObj.lead_id=$("#lead_id").val();
			mainObj.rep_id=$("#user_id").val();
			mainObj.opp_id=$("#opp_id").val();

			mainObj.type1_2=someObj;
			mainObj.type3=someObj1;

			console.log(mainObj);
			$("#submit_q_btn").attr('disabled', true);
			$.ajax({
				type:"post",
				cache:false,
				url:"<?php echo site_url('common_opportunitiesController/post_data');?>",
				dataType : 'json',
				data:JSON.stringify(mainObj),
				success: function (data) {
					if(error_handler(data)) {
						return;
					}
					$("#submit_q_btn").attr('disabled', false);
					if (data == 0) {
						alert("Successfully answering the qualifier is mandatory to create this opportunity.");
					} else if (data == 1) {
						submit_opp_final();
					}
					cancel_create_modal();
					cancel_quest();
				}
			});
		}
	}

	function submit_opp_final() {
		if (validate_opp_add() == false) {
			return ;
		}
		loaderShow();

		var addObj={};
		addObj.opportunity_id = $("#opp_id").val();
		addObj.opportunity_name = $("#opportunities_name").val();
		addObj.target = $("#target").val();
		addObj.lead_cust_id = $("#target_list").val();
		addObj.opportunity_contact = [];
		$("#target_contact_list input[type=checkbox]").each(function(){
  			if($(this).prop('checked') == true){
 				addObj.opportunity_contact.push($(this).val());
  			}
		});
		addObj.product_list = $("#product_list").val();
		addObj.currency_list = $("#currency_list").val();
		addObj.sell_type = $("#sell_type").val();

		addObj.industry_list = $("#industry_list").val();
		addObj.location_list = $("#location_list").val();
		addObj.opp_remarks = $("#opportunities_remarks").val();
		addObj.stage_id = $("#stage_id").val();
		addObj.cycle_id = $("#cycle_id").val();

		console.log(addObj);
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_opportunitiesController/add_opp_final'); ?>",
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
					var url = "<?php echo site_url('manager_opportunitiesController/stage_view/'); ?>"+opp_id;
					window.location.reload();
				} else {
					alert(data.message);
					if (data.qualifier == true) {
						$("#user_id").val("<?php echo $this->session->userdata('uid');?>");
						$("#lead_id").val($("#target_list").val());
						$("#opp_id").val(data.opp_data.opportunity_id);
						$("#stage_id").val(data.opp_data.stage_id);
						$("#cycle_id").val(data.opp_data.cycle_id);
						setup_questionnaire(data.qualifier_data);
					}
				}
				$("#create_opp_btn").attr('disabled', false);
			}
		});
	}

	function cancel_quest() {
		$('#Questionnaire').modal('hide');
		$('.modal input[type="text"], select, textarea').val('');
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
		$("#opp_id").val('');
		$("#stage_id").val('');
		$("#cycle_id").val('');
		$('#create_oppo_modal .form-control').val("");
		$('#target_contact_list').empty();
	}

	function cancel_create_modal(){
		$('#create_oppo_modal').modal('hide');
		$('#target_contact_list').empty();
		$('.modal input[type="text"],#completed select,#addmodal select, textarea').val('');
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
	}

</script>
<div id="create_oppo_modal" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<span class="close"  onclick="cancel_create_modal();">&times;</span>
				<h4 class="modal-title" id="pagetitle"> Creating Opportunity </h4>
			</div>
			<div class="modal-body">
				<form class="form" action="#" method="post" name="adminClient" id="createOppoForm">
					<input type="hidden" name="opp_details" id="opp_details">
				</form>
				<input type="hidden" name="industry_list" id="industry_list" class="form-control">
				<input type="hidden" name="location_list" id="location_list" class="form-control">
				<input type="hidden" name="target" id="target" class="form-control">
				<input type="hidden" name="cycle_id" id="cycle_id" class="form-control">
				<input type="hidden" name="stage_id" id="stage_id" class="form-control">
				<div class="row inputBottomGap">
						<div class="col-md-4">
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
				<div class="row inputBottomGap">
						<div class="col-md-4">
							<label for="target_contact_list" id="target_contact_list_placeholder">Contact(s) *</label>
						</div>
						<div class="col-md-8" id="contact_div">
							<div name="target_contact_list" id="target_contact_list" class="multiselect form-control">
							</div>
							<span class="error-alert"></span>
						</div>
				</div>
				<div class="row inputBottomGap">
						<div class="col-md-4">
							<label for="product_list">Sell Type *</label>
						</div>
						<div class="col-md-8">
							<select class="form-control" id="sell_type" name="sell_type" autofocus>
								<option value=""> Select Sell Type</option>
							</select>
							<span class="error-alert"></span>
							<span class="warning-alert"></span>
						</div>
				</div>
				<div class="row inputBottomGap">
						<div class="col-md-4">
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
				<div class="row inputBottomGap">
						<div class="col-md-4">
							<label for="currency_list">Currency</label>
						</div>
						<div class="col-md-8">
							<select class="form-control" id="currency_list" name="currency_list" autofocus>
								<option value=""> Select Currency</option>
							</select>
							<span class="error-alert"></span>
						</div>
				</div>
				<div class="row inputBottomGap">
						<div class="col-md-4">
							<label for="add_opp" title="Suggested" >Opportunity Name *</label>
						</div>
						<div class="col-md-8 ">
							<div class="input-group">
								<input type="text" placeholder="Enter a Name for the Opportunity" class="form-control" name="opportunities_name" id="opportunities_name">
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" data-original-title="Suggested name which you are free to change"></span>
								</span>
								<span class="error-alert"></span>
							</div>
						</div>
				</div>
				<div class="row inputBottomGap">
						<div class="col-md-4">
							<label for="add_opp" title="Suggested" >Opportunity Remarks</label>
						</div>
						<div class="col-md-8">
							<textarea rows="6" cols="50" placeholder="Enter additional remarks for the Opportunity" class="form-control" name="opportunities_remarks" id="opportunities_remarks"></textarea>
							<span class="error-alert"></span>
						</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="button" id="create_opp_btn" class="btn bt" onclick="add()" value="Create" style="margin-right: 10px;">
				<input type="button" class="btn" onclick="cancel_create_modal();" value="Cancel">
			</div>
		</div>
	</div>
</div>

<div id="Questionnaire" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<span class="close" onclick="cancel_quest()">x</span>
				<h4 class="modal-title">Qualifier</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12">
						<center>
							<h2>Questions for <span id="lead_qualifier_name"></span></h2>
							<p>Mandatory fields are marked with an asterisk ( <i class='fa fa-star-half-o' aria-hidden='true'></i> ).</p>
						</center>
					</div>
				</div>
				<div class="row">
					<input type="hidden" id="lead_qualifier_id">
					<input type="hidden" id="stage_id">
					<input type="hidden" id="user_id">
					<input type="hidden" id="lead_id">
					<input type="hidden" id="opp_id">
					 <form>
						<div class="col-lg-12" id="question-list">
						</div>
					</form>
					<div class="go-top">
					<i class="fa fa-arrow-circle-o-up fa-3x" aria-hidden="true"></i>
					</div>
				</div>
				<br>
				<span id="mandatory" class="error-alert" style="color:red"></span>
			</div>
			<div class="modal-footer">
				<center>
					<button type="button" class="btn btn-primary" id="submit_q_btn" onclick="SubmitQpaper()" >Submit</button>
				</center>
			</div>
		</div>
	</div>
</div>