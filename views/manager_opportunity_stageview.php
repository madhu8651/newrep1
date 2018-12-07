<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'scriptfiles.php' ?>
<style>
	.stage_activity_a {
		border-style: solid;
		border-width: 1px;
		padding: 3px;
		color: darkred;
		border-radius: 5px;
	}
	.targetrow {
		margin-bottom: 10px;
	}
	.multiselect{
		height: 150px;
		overflow: auto;
		border: 1px solid #ccc;
		border-radius: 5px;
  	}
  	.multiselect ul{
    	padding: 0px;
  	}
  	.multiselect ul li.sel{
    	background: #ccc;
  	}
  	.multiselect ul li{
    	padding: 0 10px;
    	text-align: left;
  	}
	#opp_summary_panel{
		border: 0px;
		-webkit-box-shadow: none;
		box-shadow: none;
	}
	.opp_summary_heading{
		border-radius: 10px;
		margin: auto;
		width: 30%;
		box-shadow: 0px 0px 4px 4px rgba(160, 133, 133, 0.78);
	}
	.opp_summary_row	{
		margin-top: 10px;
	}
	.static_close {
		position: fixed;
		bottom: 8px;
		background: rgba(198, 203, 203, 0.94);
		border-radius: 7px;
		padding: 5px 0px 5px;
		padding: 24px 0px 23px;
		text-align: left;
		right: auto;
		margin-left: 10px;
	}
	.static_close * {
		cursor: pointer;
	}
	.close_opp_mgr_lbl {
		color: red;
	}
	.static{
		position: fixed;
		bottom: 8px;
		left: 50%;
		width: 40%;
		background: rgba(198, 203, 203, 0.9);
		border-radius: 7px;
		padding: 5px 0px 5px;
		margin: 0 0 0 -20% !important;
		text-align: center;
   	}
	.static_elements	{
		text-align: center;
		font-weight: bold;
		padding: 1px 15px 1px;
	}
	.oppo_details .row{
		border-bottom:1px solid black;
	}
	.oppo_details {
		margin-bottom: 4px;
	}
	.opp_details_btns	{
		margin: 10px 20px 10px !important;
	}
	.opportunity_history	{
		margin-top: 10px;
		margin-bottom: 10px;
		overflow-y: auto;
		height: 55vh;
	}
	.opportunity_actions	{
		overflow-y: auto;
		/*height: 85vh;*/
	}
	.created, .assigned, .remarks, .stage_changed, .accepted, .rejected {
		text-align: left;
	}
	b{
		color: #b5000a;
	}
	.opp_stage{
		text-align: center;
		font-weight: bold;
		padding: 1px 10px 1px;
	}
	.stage_body{
		width: 100%;
		padding: 15px;
		margin-bottom: 140px;
	}
	.arrow{
		display: inline;
		float: right;
	}
	.stage_body .panel-title{
		color: black;
	}
	#view_document{
		margin-right: 35px;
	}
	.opp_btn,.opp_btn1{
		float: right;
		margin-right: 10px !important;
	}
	.documents ul{
		list-style-type:none;
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
	.doc_list{
		list-style-type:none;
	}
	.table_hover tr:hover{
		background:white!important;
	}
	.color_rej{
		color:red!important;
	}
	.doc_remarks {
		color: darkslategray;
		font-style: oblique;
		display: block;
	}
	.stage_popup{
		text-align: right;
		cursor: pointer;
	}
	.table.dataTable{
		width: 100% !important;
	}
	@media only screen and (min-device-width: 340px) and (max-device-width: 632px){

		.static{
			position: fixed;
			bottom: 8px;
			width: 100%;
			margin: 0 0 0 0 !important;
			left:0px;
		}
	}
    /*------------------ Custom alert------------------------ */
			.mask{
			 width: 100%;
				margin: auto;
				height: 100%;
				position: absolute;
				top: 0;
				background: transparent;
				z-index: 999999;
			}
			.alert.row{
				position: absolute;
				z-index: 99999999;
				top: 0;
				width: 44%;
				margin: 20% 28% !important;
			}
</style>
<script>
	var val= 0;
	function chevron(id){
		var item = $("#chevron-"+id);
		item.removeClass('fa fa-chevron-down down_arrow fa-chevron-up up_arrow');
		if ($("#"+id).attr('aria-expanded') === 'false') {
			item.addClass('fa fa-chevron-up up_arrow');
		} else {
			item.addClass('fa fa-chevron-down down_arrow');
		}
	}
	var opportunity_details;
	var opportunity_products;
	var cur_stage_data;
	var next_stage_data;
    var pagetype="";

	$(document).ready(function(){
	    pagetype= "<?php echo $typeofpage;?>";
		init_stageview_page();
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
		$("#tempdate").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'YYYY-MM-DD',
			minDate:moment(),
		});
		$("#stage_closed_date").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'YYYY-MM-DD',
			minDate:moment(),
		});
		$('#opp_progress_form').on('submit', function (e) {
			e.preventDefault();
		});

		/*

		----Code change on 21-08-2018 for closed opprotunity form outside of the progress popup --
		$('#temp_date').show();
		$('#close_status_select').change(function()	{
			var status = $(this).val();
			if (status == 'temporary_loss') {
				$('#temp_date').show();
				$('#alsoCloseLead').show();
				$('#lead_cust_close').show();
			} else if (status == 'permanent_loss') {
				$('#alsoCloseLead').show();
				$('#lead_cust_close').show();
				$('#temp_date').hide();
				$('#tempdate').val(null);
			}
		});
		-------------------------------------------*/
		/*---------- Code added on 21-08-2018 ----starts--------------------*/
		$('#close_status_select').change(function()	{
			var status = $(this).val();
			if (status == 'temporary_loss') {
				$('#closeOpportunityPopup .temp-loss').show();
			} else if (status == 'permanent_loss') {
				$('#closeOpportunityPopup .temp-loss').hide();
			}
		});

		$("#stage_closed_date1").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY HH:mm:ss',
			minDate:moment()
		});
		$("#stateChangeActivityduration input[type=text]").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'HH:mm',
			defaultDate:'1970-01-01 00:00'
		});
		$('#opp_close_form').on('submit', function (e) {
			e.preventDefault();
		});
		/*---------- Code added on 21-08-2018 ----Ends--------------------*/
	});

	/* basic opportunity details and fill all hidden fields and stuff, setup page based on stage */
	function init_stageview_page() {
		loaderShow();
		var oppoObj = {}
		oppoObj.opportunity_id = "<?php echo $opportunity_id;?>";



		$.ajax({
			type : "POST",
			url : "<?php echo site_url('common_opportunitiesController/oppo_details')?>",
			dataType : 'json',
			data : JSON.stringify(oppoObj),
			cache : false,
			success : function(data){
				if(error_handler(data)) {
					return;
				}
				opportunity_details = data;
				opportunity_products = JSON.parse(JSON.stringify(data.oppoProducts));
				cur_stage_data = data.stage_attr;
				next_stage_data = data.next_stage_attr;
				/*if not involved with the opportunity, the user should not see it*/
				if (data.canUpdate != "1") {
					//alert("You can't view this opportunity.");
                    $("body").append('<div class="mask custom-alert"></div><div class="alert alert-danger row custom-alert"> <div class="col-md-9">You cannot view this Opportunity.</div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
                	$(".Ok").click(function(){
                	    $(".custom-alert").remove();
                		window.history.back();
                	});
                	$(".notOk").click(function(){
                		$(".custom-alert").remove();
                        window.history.back();
                	});

				}
				if ((data.closed_reason != null) || (data.canEditProducts != 1)) {
					$("#save_products_btn").hide();
					$("#proadd_btn").hide();
					$("#opp_products").prop('disabled',true);
				}
                if(pagetype == 'teamopp'){
                    $("#save_products_btn").hide();
					$("#proadd_btn").hide();
					$("#opp_products").prop('disabled',true);
                    $('#static_close').hide();
                    $('#reassign_btn').hide();
                    $('#schactbtn').prop('disabled',true);
                }
				$('#amount_view').text(data.opportunity_value);
				$('#quantity_view').text(data.opportunity_numbers);
				$('#rate_view').text(data.opportunity_rate);
				$('#score_view').text(data.opportunity_score);
				$('#priority_view').text(data.opportunity_priority);
				$('#customer_code_view').text(data.opportunity_customer_code);

                if (data.opportunity_date != "0000-00-00" && data.opportunity_date != null) {
					$("#close_date_view").text( moment(data.opportunity_date).format('ll'));
				}
				$("#opportunity_id").val(data.opportunity_id);
				$("#opp_name").text(data.opportunity_name);
				$("#lead_name").text(data.lead_name);
				var contacts = [];
				for(i=0; i<data.contacts.length;i++){
					contacts.push(data.contacts[i].contact_name);
				}
				$("#lead_contact").text(contacts.join(', '))
				if (data.sell_type == 'new_sell') {
					$("#sell_type_name").text('New Sell');
					$('#lead_cust_close').show();
					$('#alsoCloseLead').html('<input type="checkbox" name="close_lead_cust"> Also close Lead (<b>'+data.lead_name+'</b>) <span class="glyphicon glyphicon-info-sign" data-placement="right" data-toggle="tooltip" data-original-title="Only if there are no other active opportunities"></span>');
					$('#close_open_activities').html('<input type="checkbox" name="close_activity"> Also close all open activities');
				}
				else if (data.sell_type == 'up_sell') {
					$("#sell_type_name").text('Up Sell');
					$('#lead_cust_close').hide();
					$('#alsoCloseLead, #close_open_activities').html('');
				}
				else if (data.sell_type == 'cross_sell') {
					$("#sell_type_name").text('Cross Sell');
					$('#lead_cust_close').hide();
					$('#alsoCloseLead, #close_open_activities').html('');
				}
				refresh_product_summary();
				$("#indusrty_name").text(data.industry_name);
				$("#location_name").text(data.location_name);
				$("#stage_name").text(data.stage_name);
				$("#stage_owner").text(data.stage_owner);
				$("#creator_name").text(data.owner_name);

				$("#currency_name").text(data.currency_name);
				var C_date = data.created_time;
				$("#created_date_lbl").text(moment(C_date).format("lll"));
				$('#owner_mgr_name').text(data.manager_owner_name);
                $("#cur_opp_mgr").text(data.stage_manager_owner_name);
				$('#stage_mgr_name').text(data.stage_manager_owner_name);
                $("#cur_stage_mgr").text(data.stage_owner);

				if (data.closed_reason == null){ /* meaning opp is open still */
					$("#cur_stage_name").text(data.stage_name);
					if (data.canReassign != "1") {
						$('#reassign_btn').hide()
					} else {
						$('#reassign_btn').show()
					}
					$('#close_opportunity_details').show();

				} else {
					if (data.closed_reason == 'closed_won') {
						$('#cur_stage_name').css('color', 'green');
						$("#cur_stage_name").text('Closed Won!');
					} else if (data.closed_reason == 'permanent_loss') {
						$('#cur_stage_name').css('color', 'red');
						$("#cur_stage_name").text('Permanent Loss');
					} else if (data.closed_reason == 'temporary_loss') {
						$('#cur_stage_name').css('color', 'darkgoldenrod');
						$("#cur_stage_name").text('Temporary Loss');
					}
					$('#static_close').hide();
					$('#reassign_btn').hide();
				}
				$("#lead_cust_id").val(data.lead_cust_id);
				$("#stage_id").val(data.opportunity_stage);
				$("#cycle_id").val(data.cycle_id);
				$("#user_id").val(data.user_id);
				$("#sell_type").val(data.sell_type);
				$("#history_table_opp_name").text(data.opportunity_name)
				get_stage_history();


				//-----------------Code added on 22-08-2018------------Start
				$("#lead_cust_id1").val(data.lead_cust_id);
                $("#stage_id1").val(data.opportunity_stage);
                $("#cycle_id1").val(data.cycle_id);
                $("#user_id1").val(data.user_id);
                $("#sell_type1").val(data.sell_type);
				$("#opportunity_id1").val(data.opportunity_id);
				$("#stage_manager_owner_id").val(data.stage_manager_owner_id);

				$("#lead_name1").val(data.lead_name);
                $("#opportunity_name1").val(data.opportunity_name);
                $("#stage_owner_name1").val(data.stage_owner);
                $("#stage_owner_id1").val(data.stage_owner_id);
                $("#manager_owner_name1").val(data.manager_owner_name);
				$("#manager_owner_id1").val(data.manager_owner_id);

				var close_wondiv = 'hide';
				opportunity_details.stage_attr.forEach(function(elm){
					if(elm.attribute_value == 'closedwon'){
						close_wondiv = 'show';
					}
				})
				if(close_wondiv == 'show'){
					$('#close_wondiv').show();
				}else{
					$('#close_wondiv').hide();
				}

				if(data.hasOwnProperty('activityList')){
					data.activityList.forEach(function(elm){
						$('#StateChangeFutureActivity select').append('<option value="'+elm.lookup_id+'">'+elm.lookup_value+'</option>');
					});
				}

				data.contacts.forEach(function(elm){
					$('#stateChangeContactType select').append('<option value="'+elm.contact_id+'">'+elm.contact_name+'</option>');
				});
				/*-----------------Code added on 22-08-2018------------End--*/
                 //leadclosebit is 1 only if the logged in user is lead manager or lead rep owner or any one who is above him in the heirarchy
                $('#lead_cust_close').find('.infoMsg').remove();
                if(data.leadclosebit == 0){
				  // $('#lead_cust_close input[type=checkbox]').attr('disabled', 'disabled');
                   $('#alsoCloseLead').hide();
				   if (data.sell_type == 'new_sell') {
                	   //	$('#lead_cust_close').append('<div class="col-md-12 text-center infoMsg" style="color:red"><p>'+data.leadclosereason+'</p></div>');
				   }
				}else{
				   $('#lead_cust_close input[type=checkbox]').removeAttr('disabled');
				}
			}
		});
	}

	function refresh_product_summary() {
	    // added can edit product clause by swati 06.9.2018
		$("#product_name").text('');
        var products;
        if(opportunity_details.oppoProducts.length > 1){
             products = opportunity_details.oppoProducts.length + " Product(s)"

        }else{
            products = opportunity_details.oppoProducts[0].product_name;
        }
        if(opportunity_details.canEditProducts == 1 && opportunity_details.closed_reason == null){
           $('.view_title').text('View or Edit');
        } else{
           $('.view_title').text('View More');
        }
		$("#product_name").text(products);
	}

	/* get data for accordion  */
	function get_stage_history() {
		var oppoObj = {}
		oppoObj.opportunity_id = $("#opportunity_id").val();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('common_opportunitiesController/get_stage_history')?>",
			dataType : 'json',
			data : JSON.stringify(oppoObj),
			cache : false,
			success : function(data){
				loaderHide();
				if(error_handler(data)) {
					return;
				}
				frame_stage_history(data);
			}
		});
	}

	/* fill accordion menu with data */
	function frame_stage_history(data) {
		$(".stage_body").empty();
		var row = '';
		var next_ss = next_stage_seq_no();
		var canReassignStage = false;
		for(i=0;i<data.length;i++){
			var map_id = data[i].mapping_id;
			row+='<div class="panel-group">';
				row+='<div class="panel panel-default">';
				if (i == data.length-1) {
					row += '<a data-toggle="collapse" href="#'+map_id+'" onclick="chevron(\''+map_id+'\')" aria-expanded="true">';
				} else {
					row += '<a data-toggle="collapse" href="#'+map_id+'" onclick="chevron(\''+map_id+'\')" aria-expanded="false">';
				}
					row+='<div class="panel-heading">';
					row+='<h4 class="panel-title ';if(data[i].action == "rejected"){row +='color_rej'}row += '"><strong>'+data[i].stage_name+'</strong>';
					row+='<div class="arrow"><i class="fa fa-fw fa-chevron-down down_arrow" id="chevron-'+map_id+'" aria-hidden="true"></i></div>';
					row+='</h4>';
					row+='</div>'
				row+='</a>';
			if (i == data.length-1) {
				row+='<div id="'+map_id+'" class="panel-collapse collapse in" aria-expanded="true">'
			} else {
				row+='<div id="'+map_id+'" class="panel-collapse collapse" aria-expanded="false">'
			}
				row+='<div class="panel-body">';
				row+='<table class="table table_hover">';
				row+='<tbody>';
			if(data[i].user_name){
				row += '<tr><td style="text-align:left; width:30%;"><strong>Stage Owner</strong></td><td>'+data[i].user_name+'</td><td></td></tr>';
			}
			if(data[i].opp_close_date){
				row += '<tr><td style="text-align:left; width:30%;"><strong>Expected Close Date</strong></td><td>'+  moment(data[i].opp_close_date).format('ll')+'</td><td></td></tr>';
			}
			if(data[i].opp_numbers){
				row += '<tr><td style="text-align:left; width:30%;"><strong>Quantity</strong></td><td>'+data[i].opp_numbers+'</td><td></td></tr>';
			}
			if(data[i].opp_value){
				row += '<tr><td style="text-align:left; width:30%;"><strong>Amount</strong></td><td>'+data[i].opp_value+'</td><td></td></tr>';
			}
			if(data[i].opp_rate){
				row += '<tr><td style="text-align:left; width:30%;"><strong>Rate</strong></td><td>'+data[i].opp_rate+'</td><td></td></tr>';
			}
			if(data[i].opp_score){
				row += '<tr><td style="text-align:left; width:30%;"><strong>Score</strong></td><td>'+data[i].opp_score+'</td><td></td></tr>';
			}
			if(data[i].opp_customer_code){
				row += '<tr><td style="text-align:left; width:30%;"><strong>Customer Code</strong></td><td>'+data[i].opp_customer_code+'</td><td></td></tr>';
			}
			if(data[i].opp_priority){
				row += '<tr><td style="text-align:left; width:30%;"><strong>Priority</strong></td><td>'+data[i].opp_priority+'</td><td></td></tr>';
			}
			if(data[i].docs.length>0){
				row +='<tr><td style="text-align:left; width:30%;"><strong>Documents</strong></td><td style="text-align:left">';
				for(j=0;j<data[i].docs.length;j++){
					var path = data[i].docs[j].path;
					var file_name = path.split('/').pop();
					var format = file_name.split('.').pop();
					var file_name = file_name.split('.')[0];
					row += '<ul class="doc_list no-padding"><li><a href="<?php echo site_url();?>'+path+'" target="_blank">';
					if(format == 'doc' || format == 'docx' || format == 'pdf' || format == 'rtf' || format == 'txt' || format == 'xls' || format == 'csv'){
						row += '<span><i class="fa fa-fw fa-file-text-o" aria-hidden="true"></i></span>';
					}
					if(format == 'jpg' || format == 'jpeg' || format == 'gif' || format == 'bmp' || format == 'png'){
						row += '<span><i class="fa fa-fw fa-file-image-o" aria-hidden="true"></i></span>';
					}
					row+=file_name+' (by '+data[i].docs[j].doc_user_id+')</a>'
					row+='<span><i class="fa fa-fw fa-clock-o" data-toggle="tooltip" title="'+data[i].docs[j].created_date+'" aria-hidden="true"></i></span></li></ul>';
				}
				row +='</td><td></td></tr>';
			}
			if(data[i].qualifiers.length>0){
				row +='<tr><td style="text-align:left; width:30%;"><strong>Qualifier</strong></td><td style="text-align:left">';
				for(j=0;j<data[i].qualifiers.length;j++){
					var qual_mapping = data[i].qualifiers[j].mapping_id;
					var qual_user = data[i].qualifiers[j].user_name;
					var qual_name = data[i].qualifiers[j].qualifier_name;
					var qual_status = data[i].qualifiers[j].status;
                    var qual_qualifier_id = data[i].qualifiers[j].qualifier_id;
					/*Changed a tag to span on 31-08-2018 ----
					view_qualifier_answered() function has no code to execute..*/
					row += '<ul class="doc_list no-padding">';
						row+='<li>';
						if (qual_status == 1) {
							row+='<a href="JavaScript:void(0)" style="color:green;" onclick="view_qualifier_answered(\''+qual_mapping+'\',\''+qual_qualifier_id+'\',\''+qual_status+'\')">';
						} else {
							row+='<a href="JavaScript:void(0)" style="color:red;" onclick="view_qualifier_answered(\''+qual_mapping+'\',\''+qual_qualifier_id+'\',\''+qual_status+'\')">';
						}
						row+='<i class="fa fa-fw fa-file-text-o" aria-hidden="true"></i> '+qual_name+' (by '+qual_user+')';
						row+='<span><i class="fa fa-fw fa-clock-o" data-toggle="tooltip" title="'+data[i].qualifiers[j].timestamp+'" aria-hidden="true"></i></span>';
						row+='</a></li>';
					row+='</ul>';
				}
				row +='</td><td></td></tr>';
			}
			if(data[i].remarks){
				row += '<tr><td style="text-align:left; width:30%;"><strong>Remarks</strong></td><td>'+data[i].remarks+'</td><td></td></tr>';
			}
			if(data[i].timestamp){
				row += '<tr><td style="text-align:left; width:30%;"><strong>Date & Time</strong></td><td>'+ moment(data[i].timestamp).format('lll')+'</td><td></td></tr>';
			}
			row +='</tbody></table><center><p><a href="javascript: void(0)" class="stage_activity_a" onclick="fetch_stage_activities(\''+data[i].stage_id+'\');"> Stage Activities</a></p></center>';
			row +='</div></div></div></div>';
			if ((opportunity_details.closed_reason == null) && (i == data.length-1) && (data[i].canReassign == 1)) {
				canReassignStage = true
			}
		}
		if (opportunity_details.closed_reason == null) {
			row+='<div class="panel-group">';
			row+='<div class="panel panel-default">';
			row+='<div class="panel-heading"><h4 class="panel-title"><strong>'+opportunity_details.stage_name+'</strong></h4></div>';
			row+='<div id="some_id" class="panel-collapse collapse in" aria-expanded="true">'
			row+='<div class="panel-body">';
			row+= '<p>'+opportunity_details.stage_desc+'</p>';
			if (canReassignStage && pagetype !='teamopp' ) {
				row+='<center>'+
						'<p>'+
							'<a href="javascript: void(0)" class="btn" onclick="reassign(\'stage\')" >Reassign Stage</a>&nbsp&nbsp'+
							'<a href="javascript: void(0)" class="stage_activity_a" onclick="fetch_stage_activities(\''+opportunity_details.opportunity_stage+'\');"> Stage Activities</a>'+
						'</p>'+
					'</center>';
			}else{
				if ((opportunity_details.closed_reason == null) && (opportunity_details.canReassign == 1)) {
					row+='<center>'+
						'<p>'+
							'<a href="javascript: void(0)" class="btn" onclick="reassign(\'stage\')" >Reassign Stage</a>&nbsp&nbsp'+
							' <a href="javascript: void(0)" class="stage_activity_a" onclick="fetch_stage_activities(\''+opportunity_details.opportunity_stage+'\');"> Stage Activities</a>'+
						'</p>'+
					'</center>';
				}
			}
			row+='</div></div></div></div>';
		}

		$(".stage_body").append(row);
	}

	function fetch_stage_activities(stage_id) {
		$('#opp_logdetails').modal('show');
		load_opp_log(stage_id);
	}

	function delete_product(product_id) {

        $("body").append('<div class="mask custom-alert"></div><div class="alert alert-danger row custom-alert"> <div class="col-md-9">Are you sure you want to delete this Product?</div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
    	$(".Ok").click(function(){
    	    $(".custom-alert").remove();
    		$("#opp_product_table #"+product_id+"").remove();
			for (var i = 0; i < opportunity_details.oppoProducts.length; i++) {
				if (product_id == opportunity_details.oppoProducts[i].product_id) {
					opportunity_details.oppoProducts.splice(i,1)
					//return
				}
			}
			reload_product_table();
            $("#opp_products option.saved").each(function(){
                 if (product_id == $(this).val()) {
					$(this).show();
				}
            })
    	});
    	$(".notOk").click(function(){
    		$(".custom-alert").remove();
    	});
	}

	function product_trail() {
		var oppoObj = {};
		oppoObj.opportunity_id = "<?php echo $opportunity_id; ?>";
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('common_opportunitiesController/get_oppo_product_trail')?>",
			dataType : 'json',
			data : JSON.stringify(oppoObj),
			cache : false,
			success : function(data){
				if(error_handler(data)) {
					return;
				}

				$('#prd_attr_table #prd_attr_tablebody').empty();
				var rowhtml = '';
				for (var i = 0; i < data.length; i++) {
					rowhtml += '<tr>\
						<td>'+(i+1)+'</td>\
						<td>'+data[i].stage_name+'</td>\
						<td>'+data[i].product_name+'</td>\
						<td>'+data[i].amount+'</td>\
						<td>'+data[i].quantity+'</td>\
						<td>'+data[i].user_name+'</td>\
						<td>'+data[i].timestamp+'</td>\
						<td>'+data[i].remarks+'</td>\
					</tr>';
				}
				$('#prd_attr_table #prd_attr_tablebody').append(rowhtml);
			}
		});
	}

	function show_opp_products() {
		$("#oppo_products").modal('show');

		var oppoObj = {};
		oppoObj.user_id = "<?php echo $user_id; ?>"
		oppoObj.opportunity_id = "<?php echo $opportunity_id; ?>";
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('common_opportunitiesController/get_oppo_products')?>",
			dataType : 'json',
			data : JSON.stringify(oppoObj),
			cache : false,
			success : function(data){
				if(error_handler(data)) {
					return;
				}
                var j=0;
				$("#opp_products").empty();
				var options = "<option value=''>Select a Product & Add</option>";
                for (var j = 0 ; j < opportunity_details.oppoProducts.length; j++){
                     options += "<option class='saved' style='display:none' value='"+opportunity_details.oppoProducts[j].product_id+"'>"+ opportunity_details.oppoProducts[j].product_name +"</option>";
                }
				for(var i=0;i<data.length; i++)	{
                    options += "<option value='"+data[i].product_id+"'>"+ data[i].product_name +"</option>";
				}
				$("#opp_products").append(options);
			}
		});
		reload_product_table();
		product_trail();
	}

	function reload_product_table() {
		$("#opp_product_table").empty()
		var quantity_attr_exists = false;
		var amount_attr_exists = false;
		for (var j = 0; j < opportunity_details.stage_attr.length; j++) {
			if (opportunity_details.stage_attr[j].attribute_name == 'quantity') {
				quantity_attr_exists = true;
			}
			if (opportunity_details.stage_attr[j].attribute_name == 'amount') {
				amount_attr_exists = true;
			}
		}
        //added can edit product clause by swati 06.9.2018
		for (var i = 0 ; i <= opportunity_details.oppoProducts.length - 1; i++) {
			var row = "<tr id='"+opportunity_details.oppoProducts[i].product_id+"'> \
					<td>"+(i+1)+"</td>\
					<td> <label class='product_name'>"+opportunity_details.oppoProducts[i].product_name+" </label></td>";
				if ((quantity_attr_exists == true && opportunity_details.closed_reason == null && opportunity_details.canEditProducts == 1 )) {
					row += "<td> <input type='number' min='0' class='quantity' value='"+opportunity_details.oppoProducts[i].quantity+"'></td>";
				} else if(opportunity_details.canEditProducts == 0) {
					row += "<td> <input type='number'  min='0' class='quantity' value='"+opportunity_details.oppoProducts[i].quantity+"' disabled></td>";
				}else{
                    row += "<td> <input type='number'  style='display: none'  min='0' class='quantity' value='' disabled></td>";
				}
				if ((amount_attr_exists == true && opportunity_details.closed_reason == null && opportunity_details.canEditProducts == 1)) {
					row += "<td> <input type='number'  min='0' class='amount' value='"+opportunity_details.oppoProducts[i].amount+"'></td>";
				} else if(opportunity_details.canEditProducts == 0) {
					row += "<td> <input type='number'  min='0' class='amount' value='"+opportunity_details.oppoProducts[i].amount+"' disabled></td>";
				}else{
                    row += "<td> <input type='number' style='display: none'  min='0' class='amount' value='' disabled></td>";
				}
				if ((opportunity_details.closed_reason == null && opportunity_details.canEditProducts == 1 )) {
                    if(opportunity_details.oppoProducts.length > 1){
                    row += "<td onclick='delete_product(\""+opportunity_details.oppoProducts[i].product_id+"\");'> <span class='glyphicon glyphicon-trash'></span></td></tr>";
                    }else{
                    row += "<td></td></tr>";
                    }
				} else {
				    $("#mul_prod_remarks").prop("disabled",true);
					row += "<td></td></tr>";
				}

			$("#opp_product_table").append(row)
		}
	}

	function add_product() {
		var selected_product_id = $("#opp_products").val();
		var selected_product_name = $("#opp_products option:selected").text();
		if (selected_product_id == "") {
			return
		}
        $("#opp_products").val("");
		for (var i = 0; i < opportunity_details.oppoProducts.length; i++) {
			if (selected_product_id == opportunity_details.oppoProducts[i].product_id) {
				//alert("Can't add a product already added");
                $('#alert').modal('show');
				$("#alert .modal-body center span").text("Can't add a product already added");
				return
			}
		}

		var obj = {
			"product_name" 	: selected_product_name,
			"product_id"	: selected_product_id,
			"opportunity_id": opportunity_details.opportunity_id,
			"amount"		: '',
			"quantity"		: '',
			"timestamp"		: null,
			"remarks"		: null,
			"opp_prod_id"	: null,
		};
		opportunity_details.oppoProducts.push(obj);
		reload_product_table()
	}

	function save_products() {
		var array = [];
		$('#opp_product_table tr').each(function(row_index, row) {
			var obj = {};
			$(row.children).each(function(td_index, td) {
				obj.product_id = row.id;
				obj.opportunity_id = opportunity_details.opportunity_id;
				if ($(td).find("label").is('label') == true) {
					if ($(td).find('label.product_name').attr('class') == 'product_name')
						obj.product_name = $(td).find('label.product_name').text();
				}

				if ($(td).find("input").is('input') == true) {
					if ($(td).find('input.amount').attr('class') == 'amount') {
                            obj.amount = $(td).find('input.amount').val();
					} else if ($(td).find('input.quantity').attr('class') == 'quantity') {
                            obj.quantity = $(td).find('input.quantity').val();
					}
				}
			})
			array.push(obj);
		})
		if (opportunity_details.oppoProducts.length == 0) {
			//alert("There should be at least one product");
            $('#alert').modal('show');
			$("#alert .modal-body center span").text("There should be at least one product");
			return;
		}
		if($.trim($("#mul_prod_remarks").val()) == ""){
			$("#mul_prod_remarks").closest("div").find("span").text("Please enter remarks for Changes made");
			$("#mul_prod_remarks").focus();
			return;
		} else if(!comment_validation($.trim($("#mul_prod_remarks").val()))){
			$("#mul_prod_remarks").closest("div").find("span").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
			$("#mul_prod_remarks").focus();
			return;
		}else {
			$("#mul_prod_remarks").closest("div").find("span").text("");
		}

		opportunity_details.oppoProducts = JSON.parse(JSON.stringify(array));
		obj = {};
		obj.oppoProducts = JSON.parse(JSON.stringify(array));
		obj.opportunity_id = $("#opportunity_id").val();
		obj.lead_cust_id = $("#lead_cust_id").val();
		obj.cycle_id = $("#cycle_id").val();
		obj.stage_id = $("#stage_id").val();
		obj.sell_type = $("#sell_type").val();
		obj.module = 'manager';
		obj.remarks = $.trim($("#mul_prod_remarks").val());
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('common_opportunitiesController/post_oppo_products')?>",
			dataType : 'json',
			data : JSON.stringify(obj),
			cache : false,
			success : function(data){
				if(error_handler(data)) {
					return;
				}
				window.location.reload();
			}
		});
	}

	/* get data for opportunity history table view and fill it */
	function view_opp_history() {
		$('#tablebody').empty();
		$("#opp_history_div").css({
				'background':'url(<?php echo base_url();?>images/hourglass.gif)',
				'background-position':'center',
				'background-size':'30px',
				'background-repeat':'no-repeat'
				});
		/* opportunity remarks */
		var oppoObj = {}
		oppoObj.opportunity_id = $("#opportunity_id").val();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('common_opportunitiesController/get_opportunity_remarks')?>",
			dataType : 'json',
			data : JSON.stringify(oppoObj),
			cache : false,
			success : function(data){
				if(error_handler(data)) {
					return;
				}
				var history = data;
				if(history) {
					$("#opp_history_div").removeAttr('style');
					$('#tablebody').empty();
					var mapping_ids = [];
					for (var i = 0; i < history.length; i++) {
						if (mapping_ids.indexOf(history[i].mapping_id) < 0) {
							mapping_ids.push(history[i].mapping_id);

							var assign_actions = ['stage assigned','ownership assigned', 'stage reassigned', 'ownership reassigned'];
							var accept_actions = ['stage accepted', 'ownership accepted'];
							var closed_actions = ['closed won', 'temporary loss', 'permanent loss'];
							var action = history[i].action;
							var from_name = history[i].from_user_name;
							var to_name = history[i].to_user_name;
							var module = history[i].module;
							var stage_name = history[i].stage_name;
							var remarks = history[i].remarks;
							var timestamp = moment(history[i].timestamp).format('DD-MM-YYYY HH:mm:ss');

							/*"<br /><b>Stage</b> - " + stage_name + */
							/*on <h5 style='display:inline;'>" + timestamp + "</h5>*/
							var rowhtml = '';
							if (action == 'created') {
								rowhtml = "<tr class='info'> \
											<td> \
											<div class='created'> \
												<div> \
												<h4 style='display:inline;'><i class='fa fa-money fa-fw'></i>"+capitalizeFirstLetter(action)+"</h4>\
												for "+ opportunity_details.lead_name + " </div>\
												<div><strong> By </strong>- " + from_name + " ("+module+")</div>\
												<strong>Stage </strong>- " + stage_name;
								if ((remarks != null) && (remarks.length > 0)) {
									rowhtml +="<div>Remarks - " + remarks +"</div>";
								}
								rowhtml += "<h6 style='display:inline;color:#777777'>" + timestamp + "</h6>\
											</div>\
											</td>\
										</tr>";
							}
							else if (assign_actions.indexOf(action) >= 0)	{
								//get count of this mapping ID in array.
								assigned_to = 0;
								assigned_to_names = "<ul>";
								for(var c = 0; c < history.length; c++)	{
									if (history[c].mapping_id == history[i].mapping_id) {
										assigned_to++;
										assigned_to_names += '<li class="text-left">'+history[c].to_user_name+' ('+history[c].module+')('+history[c].action +')</li>';
									}
								}
								assigned_to_names += '<ul>'
								if(assigned_to > 1)	{
									to_name = assigned_to + " users <span class='glyphicon glyphicon-info-sign' data-placement='right' data-trigger='hover' data-html='true' data-title='"+assigned_to_names+"' data-toggle='tooltip'></span>";
								}
								rowhtml = `<tr><td><div class="assigned">
										<div><h4 style='display:inline;'><i class="fa fa-users fa-fw"></i>`+capitalizeFirstLetter(action)+`</h4>
										to `+ to_name + `</div>`;
								if ((remarks != null) && (remarks.length > 0)) {
									rowhtml +="<div>Remarks - " + remarks +"</div>";
								}
								rowhtml += `<h6 style='display:inline;color:#777777'>` + timestamp + `</h6></div></td></tr>`;
							}
							else if (accept_actions.indexOf(action) >= 0)	{
								rowhtml = `<tr> <td><div class="accepted">
											<div><h4 style='display:inline;color:green;'>
											<i class="fa fa-user-plus fa-fw"></i>`+capitalizeFirstLetter(action)+` (`+module+`)</h4></div>
											<div><strong>By </strong>- `+ to_name + `</div>`;
								if ((remarks != null) && (remarks.length > 0)) {
									rowhtml +="<div>Remarks - " + remarks +"</div>";
								}
								rowhtml += `<h6 style='display:inline;color:#777777'>` + timestamp + `</h6></div></td></tr>`;
							}
							else if (action == 'updated')	{
								rowhtml = `<tr> <td><div class="remarks">
											<div><h4 style='display:inline;'> <i class="fa fa-pencil fa-fw"></i>`+capitalizeFirstLetter(action)+`</h4> </div>
											<div><strong>By </strong>- ` + from_name + `</div>`;
								if ((remarks != null) && (remarks.length > 0)) {
									rowhtml +="<div>Remarks - " + remarks +"</div>";
								}
								rowhtml += `<h6 style='display:inline;color:#777777'>` + timestamp + `</h6></div></td></tr>`;
							}
							else if (action == 'passed qualifier') {
								rowhtml = `<tr> <td><div class="passed_qualifier">
											<div><h4 style='display:inline;color:green;'><i class="fa fa-check-square fa-fw"></i>Qualifier Passed</h4> </div>
											<div><strong> By </strong>- ` + from_name + `</div>
											<strong>Stage </strong>-` + stage_name + `<div></div>`;
								rowhtml += `<h6 style='display:inline;color:#777777'>` + timestamp + `</h6></div>`;
							}
							else if (action == 'failed qualifier') {
								rowhtml = `<tr> <td><div class="passed_qualifier">
											<div><h4 style='display:inline;color:red;'><i class="fa fa-minus-square fa-fw"></i> Qualifier Failed</h4></div>
											<div><strong> By </strong>- ` + from_name + `</div>
											<strong>Stage </strong>-` + stage_name + `<div></div>`;
								rowhtml += `<h6 style='display:inline;color:#777777'>` + timestamp + `</h6></div>`;
							}
							else if (action == 'stage progressed')	{
								rowhtml = `<tr> <td><div class="stage_changed">
											<div><h4 style='display:inline;'><i class="fa fa-step-forward fa-fw"></i>`+capitalizeFirstLetter(action)+`</h4>	</div>
											<div><strong>at Stage </strong>-` + stage_name + `<div></div>
											<div><strong>By </strong>- ` + from_name + `</b></div>`;
								if ((remarks != null) && (remarks.length > 0)) {
									rowhtml +="<div>Remarks - " + remarks +"</div>";
								}
								rowhtml += `<h6 style='display:inline;color:#777777'>` + timestamp + `</h6></div>`;
							}
							else if (action == 'rejected')	{
								rowhtml = `<tr class='danger'> <td><div class="rejected">
											<div><h4 style='display:inline;color:red;'> <i class="fa fa-ban fa-fw"></i> Stage `+capitalizeFirstLetter(action)+`</h4>
											at ` + stage_name + ` </div>
											<div><strong> By </strong>- ` + from_name + `</div>`;
								if ((remarks != null) && (remarks.length > 0)) {
									rowhtml +="<div>Remarks - " + remarks +"</div>";
								}
								rowhtml += `<h6 style='display:inline;color:#777777'>` + timestamp + `</h6></div></td></tr>`;
							}
							else if (closed_actions.indexOf(action) >= 0)	{
								var class_col='danger';
								var fa_icon = 'fa fa-times fa-fw';
								if (action == 'closed won') {
									class_col = 'success';
									fa_icon = 'fa fa-check fa-fw';
								} else if (action == 'temporary loss') {
									class_col='warning';
									fa_icon = 'fa fa-exclamation fa-fw';
								}
								rowhtml = `<tr class='`+class_col+`'> <td><div class="accepted">
											<div><h4 style='display:inline;'><i class="`+fa_icon+`"></i>`+capitalizeFirstLetter(action)+`</h4> </div>
											<div><strong> By </strong>- `+ to_name + `</div>
											<strong>Stage </strong>-` + stage_name;
								if ((remarks != null) && (remarks.length > 0)) {
									rowhtml +="<div>Remarks - " + remarks +"</div>";
								}
								rowhtml += `<h6 style='display:inline;color:#777777'>` + timestamp + `</h6></div></td></tr>`;
							}

							else {
								rowhtml += "<tr> <td>"+history[i].action + " by " + from_name + "</td></tr>";
							}
							$('#tablebody').append(rowhtml);
						}
					}
					var objDiv = document.getElementById("opp_history_div");
					objDiv.scrollTop = objDiv.scrollHeight;
				}
			}
		});
		view_attr_log()
	}

	/*get data on all attribute changes for the opportunity*/
	function view_attr_log() {
		$('#attr_table #attr_tablebody').empty();
		var oppoObj = {}
		oppoObj.opportunity_id = $('#opportunity_id').val();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('common_opportunitiesController/get_attr_log')?>",
			dataType : 'json',
			data : JSON.stringify(oppoObj),
			cache : false,
			success : function(data){
				if(error_handler(data)) {
					return;
				}
				$('#attr_table #attr_tablebody').empty();
				var rowhtml = '';
				for (var i = 0; i < data.length; i++) {
					rowhtml += '<tr>\
						<td>'+(i+1)+'</td>\
						<td>'+data[i].stage_name+'</td>\
						<td>'+data[i].user+'</td>\
                        <td>'+data[i].quantity+'</td>\
						<td>'+data[i].close_date+'</td>\
						<td>'+data[i].oppo_rate+'</td>\
						<td>'+data[i].oppo_score+'</td>\
						<td>'+data[i].oppo_customer_code+'</td>\
						<td>'+data[i].oppo_priority+'</td>\
						<td>'+data[i].timestamp+'</td>\
						<td>'+data[i].remarks+'</td>\
					</tr>';
				}
				$('#attr_table #attr_tablebody').append(rowhtml);
                /*<td>'+data[i].amount+'</td>\
				<td>'+data[i].quantity+'</td>\*/
			}
		});
	}
	/*get data on all documents uploads for the opportunity*/
	function view_opp_documents() {
		var oppoObj = {}
		oppoObj.opportunity_id = $('#opportunity_id').val();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('common_opportunitiesController/get_opp_documents')?>",
			dataType : 'json',
			data : JSON.stringify(oppoObj),
			cache : false,
			success : function(data){
				if(error_handler(data)) {
					return;
				}
				frame_documents_uploaded(data);
			}
		});
	}

	/* fill the table for all the document uploads of opportunity  */
	function frame_documents_uploaded(data) {
		$('#viewDocument .table tbody').empty();
		var row1 = '';
		if(data.length > 0){
			for(i=0;i<data.length;i++){
				row1 +='<tr><td style="text-align:left">'+(i+1)+'</td><td style="text-align:left">'+data[i].stage_name+'</td><td style="text-align:left">';
				var path = data[i].path;
				var file_name = path.split('/').pop();
				var format = file_name.split('.').pop();
				var file_name = file_name.split('.')[0];
				if(format == 'doc' || format == 'docx' || format == 'pdf' || format == 'rtf' || format == 'txt' || format == 'xls' || format == 'csv'){
					row1 += '<ul class="doc_list"><li style="margin-left: -38px;"><a href="'+data[i].path+'" target="_blank"><i class="fa fa-fw fa-file-text-o" aria-hidden="true"></i> '+file_name+' (by '+data[i].doc_user_id+')</a><i class="fa fa-fw fa-clock-o" data-toggle="tooltip" title="'+data[i].timestamp+'" aria-hidden="true"></i></li></ul>';
				}
				if(format == 'jpg' || format == 'jpeg' || format == 'gif' || format == 'bmp' || format == 'png'){
					row1 += '<ul class="doc_list"><li style="margin-left: -38px;"><a href="<?php echo site_url();?>'+data[i].path+'" target="_blank"><i class="fa fa-fw fa-file-image-o" aria-hidden="true"></i>  '+file_name+' (by '+data[i].doc_user_id+')</a>  <i class="fa fa-fw fa-clock-o" data-toggle="tooltip" title="'+data[i].timestamp+'" aria-hidden="true"></i></li></ul>';
				}
				row1 +='</td></tr>';
			}
		}else{
			row1 +='<tr><td colspan="3" class="text-center">No data available in table</td></tr>';
		}
		$('#viewDocument .table tbody').append(row1);

		if(data.length > 0){
			$('#viewDocument .table').DataTable();
		}
	}

	/* get lead details of the opportunity */
	function view_lead() {
		var lead_cust_id = $("#lead_cust_id").val();
		var sell_type = $('#sell_type').val();
		var obj = {};
		obj.lead_cust_id = lead_cust_id;
		if (sell_type == 'new_sell') {
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('common_opportunitiesController/view_lead')?>",
				dataType : 'json',
				data : JSON.stringify(obj),
				cache : false,
				success : function(data){
					if(error_handler(data)) {
						return;
					}
					viewrow(data[0]);
					$('#leadview').modal('show');
				}
			});
		} else {
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('common_opportunitiesController/view_customer')?>",
				dataType : 'json',
				data : JSON.stringify(obj),
				cache : false,
				success : function(data){
					if(error_handler(data)) {
						return;
					}
					viewrow(data[0]);
					$('#customerview').modal('show');
				}
			});
		}
	}

	/* fill the modal view of lead details */
	function viewrow(obj){
		$("#oop_details").hide();
  		$("#view_map2").hide();
		$('#leadname_label').text(obj.lead_name);
		$('#lead_id').val(obj.lead_id);
		$("#label_leadweb").html(obj.lead_website);
		$("#label_leadmail").text(obj.leademail);
		$("#label_leadphone").text(obj.leadphone);
		$("#label_leadsource").html(obj.leadsurce);
		$("#label_country").html(obj.country);
		$("#label_state").html(obj.state);
		$("#label_city").html(obj.lead_city);
		$("#label_zipcode").html(obj.lead_zip);
		$("#view_ofcadd").val(obj.lead_address);
		$("#view_splcomments").val(obj.lead_remarks);
		$("#label_designation").html(obj.contact_desg);
		$("#label_primmobile").text(obj.employeephone1);
		$("#label_primmobile2").text(obj.employeephone2);
		$("#label_primemail").text(obj.employeeemail);
		$("#label_primemai2").text(obj.employeeemail2);
		$("#label_contacttype").html(obj.contact);
		$("#lead_firstcontact").html(obj.contact_name);
		$("#label_indus").html(obj.industry);
		$("#label_business").html(obj.location);
		if(obj.lead_logo!=null){
		    $('#leadpic').attr('src', "<?php echo base_url(); ?>uploads/"+obj.lead_logo);
		}else{
		    $('#leadpic').attr('src', "<?php echo base_url(); ?>images/default-pic.jpg");
		}
		var id=obj.lead_id;
 		$.ajax({
            type: "POST",
            url: "<?php echo site_url('leadinfo_controller/product_view'); ?>",
            data : "id="+id,
            dataType:'json',
            success: function(data) {
				if(error_handler(data)){
					return;
				}
				var html="";
				html +='<div class="multiselect">';
				html +='<ul>';
				for(var j=0;j<data.length; j++){
					html+="<li>"+data[j].hvalue2+"</li>";
				}
				html +='</ul>';
				html +='</div>';
				$("#label_product").html(html);
			}
        });
	}

	/* get the data for the activities logged for the opportunity and fill the table view */
	function load_opp_log(stage_id='') {
		$('#opp_log_div #tablebody').empty();
		$("#opp_log_div").css({
			'background':'url(<?php echo base_url();?>images/hourglass.gif)',
			'background-position':'center',
			'background-size':'30px',
			'background-repeat':'no-repeat'
		});
		/* opportunity remarks */
		var oppoObj = {}
		oppoObj.opportunity_id = $("#opportunity_id").val();
		if (stage_id != '') {
			oppoObj.stage_id = stage_id;
		}
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('common_opportunitiesController/get_opportunity_activity_log')?>",
			dataType : 'json',
			data : JSON.stringify(oppoObj),
			cache : false,
			success : function(data){
				if(error_handler(data)) {
					return;
				}
				$("#opp_log_div").removeAttr('style');
				var rowhtml = '';
				for (var i = 0; i < data.length; i++) {
					rowhtml += "<tr>\
						<td>"+(i+1)+"</td>\
						<td>"+data[i].log_name+"</td>\
						<td>"+data[i].user+"</td>\
						<td>"+data[i].contact_name+"</td>\
						<td>"+data[i].activity+"</td>\
						<td>"+data[i].start_time+"</td>\
						<td>"+data[i].end_time+"</td>\
						<td>"+data[i].rating+"/4</td>\
						<td>"+data[i].remarks+"</td>\
						<td>"+data[i].path+"</td>\
					</tr>";
				}
				$('#opp_log_div #tablebody').append(rowhtml);
				var objDiv = document.getElementById("opp_log_div");
				objDiv.scrollTop = objDiv.scrollHeight;
			}
		});
	}

	/* get the data for the scheduled activities for the opportunity and fill the table view */
	/* get the data for the scheduled activities for the opportunity and fill the table view */
	function load_opp_tasks() {
		$('#opp_task_div #tablebody').empty();
		$("#opp_task_div").css({
			'background':'url(<?php echo base_url();?>images/hourglass.gif)',
			'background-position':'center',
			'background-size':'30px',
			'background-repeat':'no-repeat'
		});
		/* opportunity remarks */
		var oppoObj = {}
		oppoObj.opportunity_id = $("#opportunity_id").val();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('common_opportunitiesController/get_opportunity_task_list')?>",
			dataType : 'json',
			data : JSON.stringify(oppoObj),
			cache : false,
			success : function(data){
				if(error_handler(data)) {
					return;
				}
				$("#opp_task_div").removeAttr('style');
				var rowhtml = '';
				for (var i = 0; i < data.length; i++) {
					rowhtml += "<tr>\
						<td>"+(i+1)+"</td>\
						<td>"+data[i].event_name+"</td>\
						<td>"+data[i].user+"</td>\
						<td>"+data[i].contact_name+"</td>\
						<td>"+data[i].activity+"</td>\
						<td>"+data[i].start_time+"</td>\
						<td>"+data[i].end_time+"</td>\
						<td>"+data[i].remarks+"</td>\
					</tr>";
				}
				$('#opp_task_div #tablebody').append(rowhtml);
				var objDiv = document.getElementById("opp_log_div");
				objDiv.scrollTop = objDiv.scrollHeight;
			}
		});
	}

	function progress_popup() {
		/*
		 based on sales stage attributes show values
		 if action buttons attribute is present, show appropriate div.
		 if value is present, show the div in update popup
		 if numbers is present, show the div in update popup
		 if close_date is present, show the div in update popup
		 if document upload is present, show the div in update popup
		*/
		$("#cur_stage_name_popup").text(opportunity_details.stage_name);
		$("#stage_detail_remarks").text(opportunity_details.stage_desc);
		if (opportunity_details.currency_name!=null) {
			$('#currency_short_name').text(opportunity_details.currency_name.split('-')[1]);
			$('#currency_name').attr('data-original-title',opportunity_details.currency_name);
		} else {
			$('#currency_short_name').text('-');
			$('#currency_name').attr('data-original-title','No Currency Defined');
		}

		var opp_val = opportunity_details.opportunity_value;
		var opp_numbers = opportunity_details.opportunity_numbers;
		var opp_close_date = opportunity_details.opportunity_date;
		var opp_rate = opportunity_details.opportunity_rate;
		var opp_score = opportunity_details.opportunity_score;
		var opp_customer_code = opportunity_details.opportunity_customer_code;
		var opp_priority = opportunity_details.opportunity_priority;

		$("#accept").hide();
		$("#progress").show();
		$("#amount").hide();
		$("#quantity").hide();
		$('#rate').hide();
		$('#score').hide();
		$('#customer_code').hide();
		$('#priority').hide();
		$("#closed_date").hide();
		$('#file_list').hide();

		$('#opp_progress').attr('value', 'Close Opportunity');
		$("#opp_progress").attr("onclick","close_opportunity()");

		/* $('#close_opportunity_details').hide(); */

		if (cur_stage_data.length > 0) {
			for(var i=0;i<cur_stage_data.length; i++)	{
				if(cur_stage_data[i].attribute_name=='action_button'){
					$("#accept").show();
					$("#progress").hide();
					continue;
				}

				if(cur_stage_data[i].attribute_name=='rate'){
					$("#rate").show();
					$("#stage_rate").val(opp_rate);
					$("#stage_rate").attr("placeholder", cur_stage_data[i].attribute_value);
					$("#glyph_rate").attr("data-original-title", cur_stage_data[i].attribute_value);
					continue;
				}

				if(cur_stage_data[i].attribute_name=='score'){
					$("#score").show();
					$("#stage_score").val(opp_score);
					$("#stage_score").attr("placeholder", cur_stage_data[i].attribute_value);
					$("#glyph_score").attr("data-original-title", cur_stage_data[i].attribute_value);
					continue;
				}

				if(cur_stage_data[i].attribute_name=='cust_code'){
					$("#customer_code").show();
					$("#stage_customer_code").val(opp_customer_code);
					$("#stage_customer_code").attr("placeholder", cur_stage_data[i].attribute_value);
					$("#glyph_customer_code").attr("data-original-title", cur_stage_data[i].attribute_value);
					continue;
				}

				if(cur_stage_data[i].attribute_name=='priority'){
					$("#priority").show();
					$("#stage_priority").val(opp_priority);
					$("#stage_priority").attr("placeholder", cur_stage_data[i].attribute_value);
					$("#glyph_priority").attr("data-original-title", cur_stage_data[i].attribute_value);
					continue;
				}

				if(cur_stage_data[i].attribute_name=='expected_close_date'){
					$("#closed_date").show();
					if (opp_close_date != "0000-00-00") {
						$("#stage_closed_date").val(opp_close_date);
					}
					$("#stage_closed_date").attr("placeholder", cur_stage_data[i].attribute_value);
					$("#glyph_close_date").attr("data-original-title", cur_stage_data[i].attribute_value);
					continue;
				}

				if(cur_stage_data[i].attribute_name=='document_upload'){
					$("#glyph_doc_remarks").attr("data-original-title", cur_stage_data[i].attribute_value);
					$("#doc_upload_placeholder").text('Document Upload *')
					$("#doc_upload_remarks").find('span').text(cur_stage_data[i].attribute_value);
					continue;
				}
			}
		}
		$("#updatePopup").modal("show");
	}

	/* renders fields based on check mark selection for close opportunity */
	function close_opp(item) {
		$('#lead_cust_close').hide();
		$('#alsoCloseLead').hide();
		$('#temp_date').hide();
		$('#close_status_select').val('');
		$('#alsoCloseLead').find('input[type = checkbox]').prop('checked', false);
		$("#close_status_select").closest('div').find('.error-alert').text('');

		if (item.checked == true) {
			$('#close_opportunity_details').show();
			if($("#accept").css("display")!="none"){
				/* change title of progress in accept to close & also on click action */
				$('#opp_approve').attr('value', 'Close Opportunity');
				$("#opp_approve").attr("onclick","close_opportunity()");
				$('#opp_reject').hide();
			} else if($("#progress").css("display")!="none"){
				/* change title of progress in progress to close & also on click action */
				$('#opp_progress').attr('value', 'Close Opportunity');
				$("#opp_progress").attr("onclick","close_opportunity()");
			}
		} else {
			$('#close_opportunity_details').hide();
			if($("#accept").css("display")!="none"){
				/* change title of progress in accept to close & also on click action */
				$('#opp_approve').attr('value', 'Approve');
				$("#opp_approve").attr("onclick","opp_progress()");
				$('#opp_reject').show();
			} else if($("#progress").css("display")!="none"){
				/* change title of progress in progress to close & also on click action */
				$('#opp_progress').attr('value', 'Progress');
				$("#opp_progress").attr("onclick","opp_progress()");
			}
		}
	}

	function validate_attributes() {
		$("#updatePopup").find(".error-alert").text("");
		var isValid = false;
		var stage_owner = opportunity_details.stage_owner_id;
		var owner = opportunity_details.owner_id;

		/* ---------------------------------------------------------------------- 10-07-2018
		without manager owner and stage woner manager can close the opportunity
		------------------------------------------------------------------------
		if ((stage_owner == '-') || (stage_owner == null)) {
			alert("Can't progress without a stage owner");
			return isValid;
		}
		if ((owner == '-') || (owner == null)) {
			alert("Can't progress without an opportunity owner");
			return isValid;
		} */

		for (var i = 0; i < opportunity_details.stage_attr.length; i++) {
			if (opportunity_details.stage_attr[i].attribute_name == 'quantity') {
				for (var j = 0; j < opportunity_details.oppoProducts.length; j++) {
					if (opportunity_details.oppoProducts[j].quantity == "") {
						var product_name = opportunity_details.oppoProducts[j].product_name;
						//alert("Quantity not added for product - " + product_name);
                        $('#alert').modal('show');
						$("#alert .modal-body center span").text("Quantity not added for product - " + product_name);
						return false;
					}
				}
			}

			else if (opportunity_details.stage_attr[i].attribute_name == 'amount') {
				for (var k = 0; k < opportunity_details.oppoProducts.length; k++) {
					if (opportunity_details.oppoProducts[k].amount == "") {
						var product_name = opportunity_details.oppoProducts[k].product_name;
						//alert("Amount not added for product - " + product_name);
                        $('#alert').modal('show');
						$("#alert .modal-body center span").text("Amount not added for product - " + product_name);
						return false;
					}
				}
			}
		}

		if($("#rate").css("display")!="none"){
			if($.trim($("#stage_rate").val()) == ""){
				$("#stage_rate").closest("div").siblings(".error-alert").text("Rate is mandatory");
				$("#stage_rate").focus();
				return isValid;
			}else{
				$("#stage_rate").closest("div").siblings(".error-alert").text("");
			}
		}

		if($("#score").css("display")!="none"){
			if($.trim($("#stage_score").val()) == ""){
				$("#stage_score").closest("div").siblings(".error-alert").text("Score is mandatory");
				$("#stage_score").focus();
				return isValid;
			}else{
				$("#stage_score").closest("div").siblings(".error-alert").text("");
			}
		}

		if($("#customer_code").css("display") != "none"){
			if($.trim($("#stage_customer_code").val()) == ""){
				$("#stage_customer_code").closest("div").siblings(".error-alert").text("Customer Code is mandatory");
				$("#stage_customer_code").focus();
				return isValid;
			}else{
				$("#stage_customer_code").closest("div").siblings(".error-alert").text("");
			}
		}

		if($("#priority").css("display") != "none"){
			if($.trim($("#stage_priority").val()) == ""){
				$("#stage_priority").closest("div").siblings(".error-alert").text("Priority is mandatory");
				$("#stage_priority").focus();
				return isValid;
			}else{
				$("#stage_priority").closest("div").siblings(".error-alert").text("");
			}
		}

		if($("#closed_date").css("display")!="none"){
			if($("#stage_closed_date").val()==""){
				$("#stage_closed_date").closest("div").siblings(".error-alert").text("Expected Close Date is mandatory");
				return isValid;
			}else{
				$("#stage_closed_date").closest("div").siblings(".error-alert").text("");
			}
		}




		if (next_stage_seq_no() == "100" || $('#close_opportunity_details').css('display')!='none') {
			if($.trim($("#close_status_select").val()) == "" || $("#close_status_select").val() == null) {
				$("#close_status_select").closest('div').find('.error-alert').text('Select a closure type');
				$("#close_status_select").focus();
				return isValid;
			} else {
				$("#close_status_select").closest('div').find('.error-alert').text('');
			}

			if($("#close_status_select").val() == 'temporary_loss'){
				if($("#tempdate").val()==""){
					$("#tempdate").closest("div").siblings("span").text("Approach date is required");
					return isValid;
				}else{
					$("#tempdate").closest("div").siblings("span").text("");
				}
			} else {
				$("#tempdate").closest("div").siblings("span").text("");
			}

			if($.trim($("#stage_remarks").val())==""){
				$("#stage_remarks").closest("div").find("span").text("Enter Closing Remarks");
				$("#stage_remarks").focus();
				return isValid;
			}else if(!comment_validation($.trim($("#stage_remarks").val()))){
				$("#stage_remarks").closest("div").find(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
				$("#stage_remarks").focus();
				return isValid;
			}else {
				$("#stage_remarks").closest("div").find("span").text("");
			}
		}
		/*
		if(($.trim($("#stage_remarks").val()))==""){
			$("#stage_remarks").closest("div").find(".error-alert").text("Please Enter Remarks");
			return isValid;
		}else if(!comment_validation($.trim($("#stage_remarks").val()))){
			$("#stage_remarks").closest("div").find(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
			$("#stage_remarks").focus();
			return isValid;
		}else{
			$('#stage_remarks').closest("div").find(".error-alert").text("");
		} */
		return true;
	}

	/* called when the opporunity is closed */
	function close_opportunity() {
		if (validate_attributes() == false) {
			return;
		}
		hide_footer(true);
		opp_id = $("#opportunity_id").val();
		var formData = new FormData($('#opp_progress_form')[0]);
		$.ajax({
			type: 'POST',
			enctype: 'multipart/form-data',
			url: "<?php echo site_url('manager_opportunitiesController/close_opportunity'); ?>",
			data: formData,
			dataType : 'json',
			cache: false,
			contentType: false,
			processData: false,
			success : function(data){
				if(error_handler(data)) {
					return;
				}
				hide_footer(false);
				if (data.errors.length > 0) {
					cancel1();
					$('#error_modal').modal('show')
					var str = ''
					$('#error_table #tablebody').empty();
					for (var i = 0; i < data.errors.length; i++) {
						str += "<tr><td>"+(i+1)+"</td><td>"+ data.errors[i].error + "</td><td>" + data.errors[i].name + "</td></tr>";
					}
					$('#error_table #tablebody').append(str);
				}

				if (data.status) {
					//alert('Opportunity Closed!');
                    $('#alert').modal('show');
					$("#alert .modal-body center span").text("Opportunity Closed!");
					if ($('#close_status_select').val()=='closed_won') {
						cancel1();
						show_confetti();
						setTimeout(function() {
							hide_confetti();
							window.location.reload();
						}, 5000);
					} else {
						cancel1();
						window.location.reload();
					}
				}
			},
			xhr: function() {
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) {
					myXhr.upload.addEventListener('progress', function(e) {
						if (e.lengthComputable) {
							$('progress').attr({
								value: e.loaded,
								max: e.total,
							});
						}
					} , false);
				}
				return myXhr;
			}
		});
	}

	function update_opportunity() {
		/* ajax to get contacts and prepopulate it with contacts sent to this view(separate it by comma before) */
		/*alert("should be able to change contacts");
		*/
		cancel1();
	}

	function view_qualifier_answered (quamapid,quaid,qual_status) {

		//take stage_id and load qualifier for that. then fill answers for that
        var addObj={};
        addObj.quaid=quaid;
        addObj.quamapid= quamapid;
        $.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_opportunitiesController/getqualifierdata'); ?>",
			dataType : 'json',
			data: JSON.stringify(addObj),
			cache : false,
			success : function(data){
				if(error_handler(data)) {
					return;
				}
				console.log(data);
				data = data.qualifier;
                setup_questionnaire(data,'viewans',qual_status);

			}
		});
	}
    /* get data and setup qualifier and show it */
	function setup_questionnaire(data,typeofsheet,qual_status) {

			$('#question-list').empty();
			$("#Questionnaire").modal('show');
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
                        if(typeofsheet == 'quaans'){
                            for(var j=0; j < data[0].question_data[i].answer_data.length; j++){
							    row +="<li id='"+data[0].question_data[i].answer_data[j].answer_id+"'><label>";
							    row +="<input type='radio' name='"+data[0].question_data[i].question_id+"'>";
							    row +=data[0].question_data[i].answer_data[j].answer_text;
							    row +="</label></li>";
						    }
                        }else{
                            $("#submit_qual_btn").hide();
                            for(var j=0; j < data[0].question_data[i].answer_data.length; j++){
                                if((data[0].question_data[i].transans == data[0].question_data[i].answer_data[j].answer_id) && qual_status ==0){
                                    row +="<li style='color:red;' id='"+data[0].question_data[i].answer_data[j].answer_id+"'><label>";
                                    row +=data[0].question_data[i].answer_data[j].answer_text;
							        row +="</label></li>";
                                }else if((data[0].question_data[i].transans == data[0].question_data[i].answer_data[j].answer_id) && qual_status ==1){
                                    row +="<li style='color:green;' id='"+data[0].question_data[i].answer_data[j].answer_id+"'><label>";
                                    row +=data[0].question_data[i].answer_data[j].answer_text;
							        row +="</label></li>";
                                }else{
                                    row +="<li id='"+data[0].question_data[i].answer_data[j].answer_id+"'><label>";
                                    row +=data[0].question_data[i].answer_data[j].answer_text;
							        row +="</label></li>";
                                }
                                //row +="<input style='color:red;' type='radio' name='"+data[0].question_data[i].question_id+"'>";
						    }
                        }

						row+="<input type='hidden' value='"+data[0].question_data[i].question_type+"' id='questiontype'/>"
					 }
				}
				if(data[0].question_data[i].question_type == 3){
				    if(typeofsheet == 'quaans'){
                        row +="<div class='row'><div class='col-lg-6 col-sm-12 col-xs-12'><textarea rows='3' class='form-control text-ans'/></div></div>";
				    }else{
				        row +="<div class='row'><div class='col-lg-6 col-sm-12 col-xs-12'>"+data[0].question_data[i].transans+"</div></div>";
				    }

				}
				row +="</ol>";
				row +="</div>";
			}

			$('#question-list').append(row);

	}

	/* take out values from all input fields in popup and reset the popup*/
	function cancel1() {
		$('.modal').modal('hide');
		$('.modal #tempdate').data("DateTimePicker").date(null);
		$('.modal #stage_closed_date').data("DateTimePicker").date(null);
		$(".modal #temp_date").hide();
		$('.modal input[type="text"], select, textarea').val('');
		$('.modal input[type="file"], select, textarea').val('');
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
		$(".modal #num_files_lbl").text("0 File(s) added");
		$(".modal #files_uploaded_list").empty();
		$(".modal #file_up_progress").val("0");
		$('.modal #file_list').hide();
		$('.modal .error-alert').text('');
		opportunity_details.oppoProducts = opportunity_products;
	}

	/* for closing the qualifier */
	function cancel_quest() {
		$('#Questionnaire').modal('hide');
		$('.modal input[type="text"], select, textarea').val('');
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
	}

	/* get next stage sequence number */
	function next_stage_seq_no()	{
		if (next_stage_data.length > 0)
			return next_stage_data[0].next_seq_no;
		else
			return "0";
	}

	/* helper function to setup files upload */
	function file() {
		var fileInput = document.getElementById("fileUp");
		var imgid = '#contactAddPlaceholder';
		$("#files_uploaded_list").empty();
		$("#file_up_progress").val("0");
		$('#file_up_progress').attr('max', 1);
		if (fileInput.files!=null && fileInput.files.length > 0) {
			$('#file_list').show();
			$("#file_check_remarks").find("span").text("");
			var fileNames = '';
			var totalFileSize = 0;
			for (var i = 0; i < fileInput.files.length; i++) {
				fileNames += "<li> "+fileInput.files[i].name +"</li>";
				totalFileSize += fileInput.files[i].size;
			}
			$('#file_up_progress').attr('max', totalFileSize);
			$("#files_uploaded_list").append(fileNames);
		}
		if (totalFileSize >= 8388608) {
			$("#file_check_remarks").find("span").text("Uploaded file size exceeds max file size limit (8MB). Upload one at a time.");
			$('.modal input[type="file"]').val('');
			$("#files_uploaded_list").empty();
			$("#file_up_progress").val("0");
			$('#file_up_progress').attr('max', 1);
			$('#file_list').hide();
		}
	}

	/* toggle title on the opportunity summary button*/
	function toggle_opp_summary(id) {
		var item = $(id);
        setTimeout(
            function(){
                //alert(item.attr('aria-expanded'))
                if (item.attr('aria-expanded') === 'true') {
        			$("#opp_summary_title").text('Hide Opportunity Summary');
        		} else {
        			$("#opp_summary_title").text('Show Opportunity Summary');
        		}
            }, 300
        );

	}

	function capitalizeFirstLetter(string) {
		return string.charAt(0).toUpperCase() + string.slice(1);
	}

	/* helper function to disable and enable the footer section of a modal*/
	function hide_footer(status) {
		$('#opp_progress').attr('disabled', status);
		if($("#accept").css("display")!="none"){
			$("#accept").find("input").attr("disabled", status);
		} else if($("#progress").css("display")!="none"){
			$("#progress").find("input").attr("disabled", status);
		} else if($("#opp_progress").css("display")!="none"){
			$("#opp_progress").find("input").attr("disabled", status);
		}
	}

	var finalArray = {};
	function reassign(data){
		var flagchk=0;
		var localObj = {};
		localObj.currency = opportunity_details['opportunity_currency'];
		localObj.sell_type = $('#sell_type').val();
		localObj.prod_id = opportunity_details['opportunity_product'];
		localObj.ind_id = opportunity_details['opportunity_industry'];
		localObj.loc_id = opportunity_details['opportunity_location'];
		localObj.opp_id = $('#opportunity_id').val();
		$("#reassign_opp").modal("show");
		if(data == "stage"){
			localObj.btn_status = 'stageOwner'
			$("#reassign_opp .modal-header .modal-title").text("Reassign Opportunity Stage");
		}else{
			$("#reassign_opp .modal-header .modal-title").text("Reassign Opportunity");
			localObj.btn_status = 'oppOwner';
		}


		$("#reassign_opp").find(".error-alert").text(" ");
		$("#mgrlist").css({
						'background':'url(<?php echo base_url();?>images/hourglass.gif)',
						'background-position':'center',
						'background-size':'30px',
						'background-repeat':'no-repeat'
						})

	  	$.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_opportunitiesController/get_assignees');?>",
            dataType:'json',
            data:JSON.stringify(localObj),
            success: function(data) {
            	if (error_handler(data)) {
					return ;
				}
            	$("#mgrlist").removeAttr('style');
            	$("#mgrlist ul").empty();

            	var multipl2 = '',flg = 0;
				if(data.length > 0){
					for(var i=0;i<data.length; i++){
						if(data[i].sales_module=='0' && data[i].manager_module!='0'){
							multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'"> '+data[i].user_name+' (Manager)<label></li>';
							flg = 1;
						}
						if(data[i].sales_module!='0' && data[i].manager_module=='0'){
							multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'"> '+data[i].user_name+' (Executive)<label></li>';
							flg = 1;
						}
						if(data[i].sales_module!='0' && data[i].manager_module!='0'){
							multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'"> '+data[i].user_name+' (Manager)<label></li>';
							multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'"> '+data[i].user_name+' (Executive)<label></li>';
							flg = 1;
						}
					}
				}

				if(flg == 1){
					$("#mgrlist ul").append('<li><label><input type="checkbox" name="select_all_mgr" onclick="checkAllMgrs(this)"> Select All</label></li>');
				}else{
					multipl2 = '<center>No user found</center>';
				}

	    		$("#mgrlist ul").append(multipl2);


		    }
        });
        if (data == 'stage') {
        	$('#reassign_type').val(data);
        } else if (data == 'ownership') {
        	$('#reassign_type').val(data);
        } else {
        	$('#reassign_type').val('');
        }
	}

	function assign_save(){
		$("#reassign_opp").find(".error-alert").text(" ");

		finalArray['users'] = [];
		$("#mgrlist .mgrlist_sales, #mgrlist .mgrlist_manager").each(function(){
			if($(this).prop('checked')== true){
				var localObj = {};
				localObj['to_user_id'] = $(this).attr('id');
				localObj['module'] = $(this).val();
				finalArray['users'].push(localObj);
			}
		});
		if (finalArray['users'].length == 0) {
			/* alert('Select a user to assign'); */
			$("#mgrlist").siblings(".error-alert").text("Select atleast one user to assign");
			return;
		}
		finalArray['opp_id'] = $('#opportunity_id').val();
		finalArray['lead_cust_id'] = $('#lead_cust_id').val();
		finalArray['stage_id'] = $('#stage_id').val();
		finalArray['cycle_id'] = $('#cycle_id').val();
		finalArray['sell_type'] = $('#sell_type').val();
		finalArray['remarks'] = $.trim($("#assign_remarks").val());
		var type = $('#reassign_type').val();
		/* check reassign type to know if its stage reassignment or ownership reassignment and then redirect to corresponding url*/
		var url = '';
		if (type == 'stage') {
			finalArray['btn_status'] = 'stageOwner';
			url = "<?php echo site_url('manager_opportunitiesController/reassign_stages'); ?>"
		} else {
			finalArray['btn_status'] = 'oppOwner';
			url = "<?php echo site_url('manager_opportunitiesController/reassign_opportunities'); ?>"
		}

		if(finalArray.remarks != ""){
			if(!comment_validation(finalArray.remarks)){
				$("#assign_remarks").closest("div").find(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
				$("#assign_remarks").focus();
				return;
			}else{
				$("#assign_remarks").closest("div").find(".error-alert").text(" ");
			}
		}else{
				$("#assign_remarks").closest("div").find(".error-alert").text(" ");
		}

		loaderShow();
	    $.ajax({
            type: "POST",
            url: url,
            data:JSON.stringify(finalArray),
            dataType:'json',
            success: function(data) {
            	if (error_handler(data)) {
					return ;
				}
	          	loaderHide();
	          	if(data>=1) {
		          	close_modal();
	          	}
	          	init_stageview_page();
            }
	    });
	}

	function close_modal(){
		$('#reassign_opp').modal('hide');
		$("#mgrlist ul").empty();
		$('.modal input[type="text"],#completed select,#addmodal select, textarea').val('');
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
	}

	function checkAllMgrs(e)	{
		$('li input:checkbox',$("#mgrlist")).prop('checked',e.checked);
	}

	/*----------------------- close opportunity popup open -- new popup 22-08-2018------------------------*/

	function  closeOpportunity_popup(){
		$("#stateChangeActivityduration input[type=text]").val('00:00');
		$("#closeOpportunityPopup .temp-loss").hide();
		$("#closeOpportunityPopup").modal("show");
		$('#lead_cust_close').show();
	}
	/*----------------------- close opportunity save  -- new popup 22-08-2018 ------------------------*/
	function close_opportunity_simple(module) {
		$("#closeOpportunityPopup .error-alert").text('');
        if($("#close_status_select").val()=="")
        {
           $("#close_status_select").closest("select").siblings(".error-alert").text("Close Status is mandatory");
           return;
        }else{
           $("#close_status_select").closest("select").siblings(".error-alert").text("");
        }

        /*if($("#stage_closed_date1").val()==""){
				$("#stage_closed_date1").closest("div").siblings(".error-alert").text("Close Date is mandatory");
				return ;
		}else{
				$("#stage_closed_date1").closest("div").siblings(".error-alert").text("");
		}*/
		if($("#close_status_select").val() == 'temporary_loss'){
			var object = {};
			object.date = $.trim($("#stage_closed_date1").val());
			object.title = $.trim($("#StateChangeTitle input[type=text]").val());
			object.futureActivity = $.trim($("#StateChangeFutureActivity select").val());
			object.activityDuration = $.trim($("#stateChangeActivityduration input[type=text]").val());
			object.alertBefore = $.trim($("#stateChangeAlertBefore select").val());
			object.contactType = $.trim($("#stateChangeContactType select").val());

			if(object.title == "" ){
				$("#StateChangeTitle").find(".error-alert").text('Title is required');
				return;
			}
			if(object.futureActivity == "" ){
				$("#StateChangeFutureActivity").find(".error-alert").text('Future Activity is required');
				return;
			}
			if(object.date == "" ){
				$("#stage_closed_date1").closest("div").siblings(".error-alert").text('Date is required');
				return;
			}
			if(object.activityDuration == "" ){
				$("#stateChangeActivityduration").find(".error-alert").text('Activity duration is required');
				return;
			}else if(parseInt(object.activityDuration.split(':').join('')) === 0){
				$("#stateChangeActivityduration").find(".error-alert").text('Activity duration is required');
				return;
			}
			if(object.alertBefore == "" ){
				$("#stateChangeAlertBefore").find(".error-alert").text('Alert before is required');
				return;
			}
			if(object.contactType == "" ){
				$("#stateChangeContactType").find(".error-alert").text('Contact type is required');
				return;
			}
			/*if($("#tempdate").val()==""){
				$("#tempdate").closest("div").find(".error-alert").text("Approach date is required");
				return ;
			}else{
				$("#tempdate").closest("div").find(".error-alert").text("");
			}*/
    	} else {
			$("#tempdate").closest("div").find("span").text("");
			$("#stage_closed_date1").val("");
			$("#StateChangeTitle input[type=text]").val("");
			$("#StateChangeFutureActivity select").val("");
			$("#stateChangeActivityduration input[type=text]").val("");
			$("#stateChangeAlertBefore select").val("");
			$("#stateChangeContactType select").val("");
    	}


        if($.trim($("#stage_remarks1").val())==""){
			$("#stage_remarks1").closest("div").find(".error-alert").text("Closing Remarks is mandatory");
			return ;
		}else if(!comment_validation($.trim($("#stage_remarks1").val()))){
			$("#stage_remarks1").closest("div").find(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
			$("#stage_remarks1").focus();
			return ;
		}else {
			$('#stage_remarks1').closest("div").find(".error-alert").text("");
		}

         if($.trim($("#stage_id1").val())==""){
          //alert('Stage ID not found, Get back to Developers. Please Do not refresh the page');
          $('#alert').modal('show');
		  $("#alert .modal-body center span").text("Stage ID not found, Get back to Developers. Please Do not refresh the page");
          return;
        }
        if($.trim($("#cycle_id1").val()) == ""){
          //alert('Cycle ID not found, Get back to Developers. Please Do not refresh the page');
          $('#alert').modal('show');
		  $("#alert .modal-body center span").text("Cycle ID not found, Get back to Developers. Please Do not refresh the page");
          return;
        }

	  	hide_footer(true);
		opp_id = $("#opportunity_id").val();
		var formData = new FormData($('#opp_close_form')[0]);

		$.ajax({
			type: 'POST',
			enctype: 'multipart/form-data',
			url: "<?php echo site_url("sales_opportunitiesController/close_opportunity_simple/"); ?>"+module,
			data: formData,
			dataType : 'json',
			cache: false,
			contentType: false,
			processData: false,
			success : function(data){
				if(error_handler(data)) {
					return;
				}
				hide_footer(false);
                //alert(data);
                $('#alert').modal('show');
				$("#alert .modal-body center span").text(data);
                cancel1();
    			loaderShow();
    			window.location.reload();
			}

		});
	}
</script>
</head>
<?php require 'confetti.php' ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="loader">
  <center><h1 id="loader_txt"></h1></center>
</div>
<?php require 'demo.php' ?>
<?php require 'manager_sidenav.php' ?>
<div class="content-wrapper body-content">
	<div class="col-lg-12 column" id="content">
		<div class="row header1">
			<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1 aa">
				<span class="info-icon">
					<div>
						<img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Opportunity Details"/>
					</div>
				</span>
			</div>
			<div class="col-xs-8 col-sm-8 col-md-10 col-lg-10 pageHeader1 aa">
					<h2><label id="opp_name1">Opportunity Details </label><span class="error-alert"></span></h2>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1 aa">
				<div class="addBtns addPlus">
					<input type="button" class="btn" href="#viewDocument" onclick="view_opp_documents();" data-toggle="modal" id="view_document" value="Documents" />
					<input type="button" class="btn" href="#viewHistory" onclick="view_opp_history()" data-toggle="modal" id="view_history" value="History" />
				</div>
			</div>
		</div>
		<div class="row opp_summary_row">
			<div class="panel-group">
				<div class="panel panel-default" id="opp_summary_panel">
					<a data-toggle="collapse" href="#opp_summary" onclick="toggle_opp_summary('#opp_summary')" class aria-expanded="false">
						<div class="panel-heading opp_summary_heading">
							<b><h4 class="panel-title opp_stage" id="opp_summary_title"> Show Opportunity Summary</h4></b>
						</div>
					</a>
					<div id="opp_summary" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="row">
								<center><h4 class="opp_stage" id="opp_name"></h4></center>
							</div>
							<div class="row">
								<center>
									<input type="button" onclick="reassign('ownership')" value="Reassign Ownership" class="btn" id="reassign_btn">
								</center>
							</div>
							<div class="row">
								<div class="col-md-6 oppo_details">
									<div class="row">
										<div class="col-md-3 apport_label">
											<label for="lead_name">Prospect</label>
										</div>
										<div class="col-md-9">
											<label id="lead_name"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3 apport_label">
											<label for="lead_contact">Contact(s)</label>
										</div>
										<div class="col-md-9">
											<label id="lead_contact"></label>
										</div>
									</div>
									<div class="row"></div>
									<div class="row">
										<div class="col-md-3 apport_label">
											<label for="sell_type_name">Sell Type</label>
										</div>
										<div class="col-md-9">
											<label  id="sell_type_name"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3 apport_label">
											<label for="indusrty_name">Industry</label>
										</div>
										<div class="col-md-9">
											<label  id="indusrty_name"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3 apport_label">
											<label for="location_name">Location</label>
										</div>
										<div class="col-md-9">
											<label  id="location_name"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3 apport_label">
											<label for="product_name">Product</label>
										</div>
										<div class="col-md-9">
											<label id="product_name"></label>
											<a href='#' onclick="show_opp_products();">
											<span class="view_title"></span></a>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3 apport_label">
											<label for="currency_name">Currency</label>
										</div>
										<div class="col-md-9">
											<label id="currency_name"></label>
										</div>
									</div>
									<div class="row"></div>
									<div class="row">
										<div class="col-md-3 apport_label">
											<label for="close_date_view">Expected Close Date</label>
										</div>
										<div class="col-md-9">
											<label id="close_date_view"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3 apport_label">
											<label for="stage_name">Current Stage</label>
										</div>
										<div class="col-md-9">
											<label  id="stage_name"></label>
										</div>
									</div>
									<div class="row"></div>
								</div>
								<div class="col-md-6 oppo_details">
                                    <div class="row">
										<div class="col-md-5 apport_label">
											<label for="owner_mgr_name">Manager</label>
										</div>
										<div class="col-md-7">
											<label  id="owner_mgr_name"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5 apport_label">
											<label for="creator_name">Owner</label>
										</div>
										<div class="col-md-7">
											<label id="creator_name"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5 apport_label">
											<label for="stage_mgr_name">Current Stage Manager</label>
										</div>
										<div class="col-md-7">
											<label  id="stage_mgr_name"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5 apport_label">
											<label for="stage_owner">Current Stage Executive</label>
										</div>
										<div class="col-md-7">
											<label  id="stage_owner"></label>
										</div>
									</div>

									<div class="row"></div>
									<div class="row">
										<div class="col-md-5 apport_label">
											<label for="rate_view">Rate</label>
										</div>
										<div class="col-md-7">
											<label id="rate_view"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5 apport_label">
											<label for="score_view">Score</label>
										</div>
										<div class="col-md-7">
											<label id="score_view"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5 apport_label">
											<label for="priority_view">Priority</label>
										</div>
										<div class="col-md-7">
											<label id="priority_view"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5 apport_label">
											<label for="customer_code_view">Customer Code</label>
										</div>
										<div class="col-md-7">
											<label id="customer_code_view"></label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-5 apport_label">
											<label for="created_date_lbl">Created On</label>
										</div>
										<div class="col-md-7">
											<label id="created_date_lbl"></label>
										</div>
									</div>
									<div class="row"></div>
								</div>
							</div>
							<div class="row">
								<center>
									<!-- <input type="button" class="btn opp_details_btns" value="Lead Details" onclick="view_lead();"> -->
									<input type="button" class="btn opp_details_btns" id="schactbtn" value="Scheduled Activites" onclick="load_opp_tasks()" href="#opp_taskdetails" data-toggle="modal">
									<input type="button" class="btn opp_details_btns" value="Completed Activites" onclick="load_opp_log()" href="#opp_logdetails" data-toggle="modal">
								</center>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<h4 class="opp_stage"> Stage Summary </h4>
		</div>
		<div class="row stage_body">
		</div>
		<div class="static_close" id="static_close">
			<label for="mgr_close_opp_btn" style="padding: 3px 11px 0px;">
				<i class='fa fa-times close_opp_mgr_lbl'></i> Close Opportunity
			</label>
			<!--<input type="button" class="btn hidden" onclick="progress_popup();" id="mgr_close_opp_btn"> change rrequiremment on 22-08-2018-->
			<input type="button" class="btn hidden" onclick="closeOpportunity_popup()" id="mgr_close_opp_btn">
		</div>
		<div class="row static">
			<label for="static_elements"> Current Stage </label>
			<h4 id="cur_stage_name" class="opp_stage"></h4>
            <table class="table">
                <tr>
                    <td>Current Stage Manager<h6 id="cur_opp_mgr" class="opp_stage"></h6></td>
                    <td>Current Stage Executive<h6 id="cur_stage_mgr" class="opp_stage"></h6></td>
                </tr>
            </table>


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

						 <form>
							<div class="col-lg-12" id="question-list">
							</div>
						</form>
						<!--<div class="go-top">
						<i class="fa fa-arrow-circle-o-up fa-3x" aria-hidden="true"></i>
						</div>-->
					</div>
					<br>
					<span id="mandatory" class="error-alert" style="color:red"></span>
				</div>
				<div class="modal-footer">
					<center>
						<button type="button" class="btn btn-primary" id="submit_qual_btn" onclick="SubmitQpaper()" >Submit</button>
					</center>
				</div>
			</div>
		</div>
	</div>
	<div id="updatePopup" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel1()">x</span>
					<h4 class="modal-title">Update &amp; Progress Opportunity</h4>
				</div>
				<div class="modal-body">
					<div class="row" style="text-align:center">
						<label for="cur_stage_name_popup"> Current Stage </label> <h4 id="cur_stage_name_popup" class="opp_stage"></h4>
						<label id="stage_detail_remarks"> </label>
					</div>
					<hr>
					<form id="opp_progress_form" method="POST" enctype="multipart/form-data">
						<input type="hidden" name="opportunity_id" id="opportunity_id">
						<input type="hidden" name="lead_cust_id" id="lead_cust_id">
						<input type="hidden" name="stage_id" id="stage_id">
						<input type="hidden" name="cycle_id" id="cycle_id">
						<input type="hidden" name="user_id" id="user_id">
						<input type="hidden" name="sell_type" id="sell_type">

						<div class="row" id="rate">
							<div class="col-md-4">
								<label for="stage_rate"> Rate *</label>
							</div>
							<div class="col-md-8">
								<div class='input-group date'>
									<input type="text" class="form-control closeinput" name="stage_rate" id="stage_rate" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" id="glyph_rate"></span>
									</span>
								</div>
								<span class="error-alert"></span>
							</div>
						</div>

						<div class="row" id="score">
							<div class="col-md-4">
								<label for="stage_score"> Score *</label>
							</div>
							<div class="col-md-8">
								<div class='input-group date'>
									<input type="text" class="form-control closeinput" name="stage_score" id="stage_score" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" id="glyph_score"></span>
									</span>
								</div>
								<span class="error-alert"></span>
							</div>
						</div>

						<div class="row" id="customer_code">
							<div class="col-md-4">
								<label for="stage_customer_code"> Customer Code *</label>
							</div>
							<div class="col-md-8">
								<div class='input-group date'>
									<input type="text" class="form-control closeinput" name="stage_customer_code" id="stage_customer_code" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" id="glyph_customer_code"></span>
									</span>
								</div>
								<span class="error-alert"></span>
							</div>
						</div>

						<div class="row" id="priority">
							<div class="col-md-4">
								<label for="stage_priority"> Priority *</label>
							</div>
							<div class="col-md-8">
								<div class='input-group date'>
									<input type="text" class="form-control closeinput" name="stage_priority" id="stage_priority" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" id="glyph_priority"></span>
									</span>
								</div>
								<span class="error-alert"></span>
							</div>
						</div>

						<div class="row" id="closed_date">
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<label for="stage_closed_date">Expected Close Date *</label>
							</div>
							<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
								<div class='input-group date'>
									<input type="text" class="form-control closeinput" name="stage_closed_date" id="stage_closed_date" readonly />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" id="glyph_close_date"></span>
									</span>
								</div>
								<span class="error-alert"></span>
							</div>
						</div>

						<div class="row" id="document_upload">
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<label for="fileUp" id="doc_upload_placeholder">Document Upload </label>
							</div>
							<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
								<div class='input-group date'>
									<input type='file' name="userfile[]" id="fileUp" onchange="file();" class='form-control closeinput' multiple/><br />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" id="glyph_doc_remarks" data-original-title="-" ></span>
									</span>
								</div>
								<div id="doc_upload_remarks">
									<span style="font-weight: 400; font-style: italic; font-size: 13px;"></span>
								</div>
							</div>
							<div align="center" id="file_check_remarks">
								<span class="error-alert"></span>
							</div>
						</div>
						<div class="row none" id="file_list">
							<div class="row" align="center" >
								<label> <h5>List of File(s) to be Uploaded - </h5></label>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<ul id="files_uploaded_list">
								</ul>
								<span class="error-alert"></span>
							</div>
							<progress id="file_up_progress" value="0" style="width: 100%;"> </progress>
						</div>

						<!--<div id="close_opportunity_details" class="none">
							<hr>
							<div class="row">
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
									<label for="close_status_select"> Close Status *</label>
								</div>
								<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
									<select id="close_status_select" class="form-control" name="close_status_select">
										<option value="">Choose Status</option>
										<optgroup label="Opportunity Lost">
											<option value="temporary_loss">Temporary Loss</option>
											<option value="permanent_loss">Permanent Loss</option>
										</optgroup>
									</select>
									<span class="error-alert"></span>
								</div>
							</div>
							<br>
							<div class="row none" id="lead_cust_close">
								<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
									<label class="none" id="alsoCloseLead"></label>
								</div>
							</div>
							<div class="row none" id="temp_date">
								<div class="col-md-4">
									<label> Remind me on Date * </label>
								</div>
								<div class="col-md-8">
									<div class='input-group date' id='tempdate_picker'>
										<input id="tempdate" name="tempdate" placeholder="Approach this opportunity again on..." type='text' class="form-control" readonly/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<span class="error-alert"></span>
								</div>
							</div>
						</div>-->

						<div class="row">
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<label for="stage_remarks">Remarks *</label>
							</div>
							<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
								<textarea id="stage_remarks" name="stage_remarks" class="form-control"></textarea>
								<span class="error-alert"></span>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<div class="col-md-12">
						<input type="button" class="btn opp_btn1" onclick="close_opportunity();" value="Close Opportunity" id="opp_progress">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="viewHistory" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel1()">x</span>
					<center><h4 class="modal-title">Opportunity History</h4></center>
				</div>
				<div class="modal-body">
					<ul class="nav nav-tabs nav-justified">
						<li class="active" id="scheduled_activity_li"><a data-toggle="tab" href="#opp_history_div">History</a></li>
						<li ><a data-toggle="tab" href="#logDetails">Audit Trail</a></li>
					</ul>
					<div class="tab-content">
						<div class="row opportunity_history tab-pane fade in active" id="opp_history_div">
							<table class="table">
								<thead>
									<tr><th class="table_header" style="text-align: center;"><label id="history_table_opp_name"></label></th></tr>
								</thead>
								<tbody id="tablebody"></tbody>
							</table>
						</div>
						<div id="logDetails" class="tab-pane fade">
							<table id="attr_table" class="table">
								<thead>
									<th class="table_header">#</th>
									<th class="table_header">Stage</th>
									<th class="table_header">Stage User</th>
									<!--<th class="table_header">Amount</th> -->
									<th class="table_header">Stage Manager</th>
									<th class="table_header">Closed Date</th>
									<th class="table_header">Rate</th>
									<th class="table_header">Score</th>
									<th class="table_header">Customer Code</th>
									<th class="table_header">Priority</th>
									<th class="table_header">Timestamp</th>
									<th class="table_header">Remarks</th>
								</thead>
								<tbody id="attr_tablebody">

								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn" onclick="cancel1()" value="Close" >
				</div>
			</div>
		</div>
	</div>
	<div id="oppo_products" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel1()">x</span>
					<h4 class="modal-title">Opportunity Products</h4>
				</div>

				<div class="modal-body">
					<ul class="nav nav-tabs nav-justified">
						<li class="active" id="scheduled_activity_li"><a data-toggle="tab" href="#prd_edit_view">View/Edit Products</a></li>
						<li ><a data-toggle="tab" href="#product_trail">Audit Trail</a></li>
					</ul>
					<div class="tab-content">
						<div class="row tab-pane fade in active" id="prd_edit_view">
							<div class="row">
								<table class="table">
									<thead>
										<th class="table_header">#</th>
										<th class="table_header">Product Name</th>
										<th class="table_header">Quantity</th>
										<th class="table_header">Amount</th>
										<th class="table_header"></th>
									</thead>
									<tbody id="opp_product_table">
									</tbody>
								</table>
							</div>
							<hr>
							<div class="row">
								<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
									<select id="opp_products" name="opp_products" class="form-control">
										<option value="">Select a Product &amp; Add</option>
									</select>
								</div>
								<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
									<input type="button" name="Add" value="Add" class="btn" id="proadd_btn" onclick="add_product()">
								</div>
								<span class="error-alert"></span>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<label>Remarks*</label>
									<textarea name="Remarks" class="form-control" id="mul_prod_remarks"></textarea>
									<span class="error-alert"></span>
								</div>
							</div>
						</div>
						<div id="product_trail" class="tab-pane fade">
							<table id="prd_attr_table" class="table">
								<thead>
									<th class="table_header">#</th>
									<th class="table_header">Stage</th>
									<th class="table_header">Product</th>
									<th class="table_header">Amount</th>
									<th class="table_header">Quantity</th>
									<th class="table_header">User</th>
									<th class="table_header">Timestamp</th>
									<th class="table_header">Remarks</th>
								</thead>
								<tbody id="prd_attr_tablebody">
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn" id="save_products_btn" onclick="save_products()" value="Save Changes">
					<input type="button" class="btn" onclick="cancel1();" value="Close">
				</div>
			</div>
		</div>
	</div>
	<div id="viewDocument" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel1()">x</span>
					<h4 class="modal-title">View Documents</h4>
				</div>
				<div class="modal-body">
					<div class="row documents">
						<table class="table">
							<thead>
								<th class="table_header">#</th>
								<th class="table_header">Stage Name</th>
								<th class="table_header">Document Name</th>
							</thead>
							<tbody id="tableBody">
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn" onclick="cancel1()" value="Close">
				</div>
			</div>
		</div>
	</div>
	<div id="opp_logdetails" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel1()">x</span>
					<center><h4 class="modal-title">Completed Activities</h4></center>
				</div>
				<div class="modal-body">
					<div class="row stage_popup">
							<!--<a href="<?php echo site_url('manager_mytaskController')?>"> Log an Activity</a> -->
					</div>
					<div class="row opportunity_history" id="opp_log_div">
						<table class="table">
							<thead>
									<th class="table_header">#</th>
									<th class="table_header">Activity Name</th>
									<th class="table_header">Logged by</th>
									<th class="table_header">Contact</th>
									<th class="table_header">Activity</th>
									<th class="table_header">Started on</th>
									<th class="table_header">Ended on</th>
									<th class="table_header">Rating</th>
									<th class="table_header">Remarks</th>
									<th class="table_header"></th>
							</thead>
							<tbody id="tablebody"></tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn" onclick="cancel1()" value="Close" >
				</div>
			</div>
		</div>
	</div>
	<div id="opp_taskdetails" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel1()">x</span>
					<center><h4 class="modal-title">Scheduled Activities</h4></center>
				</div>
				<div class="modal-body">
					<div class="row stage_popup">
							<a href="<?php echo site_url('manager_calendarController')?>"> Schedule a New Activity</a>
					</div>
					<div class="row opportunity_history" id="opp_task_div">
						<table class="table">
							<thead>
									<th class="table_header">#</th>
									<th class="table_header">Event Name</th>
									<th class="table_header">Scheduled by</th>
									<th class="table_header">Contact Person</th>
									<th class="table_header">Activity</th>
									<th class="table_header">Starts at</th>
									<th class="table_header">Ends at</th>
									<th class="table_header">Remarks</th>
							</thead>
							<tbody id="tablebody"></tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn" onclick="cancel1()" value="Close" >
				</div>
			</div>
		</div>
	</div>
	<div id="reassign_opp" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close"  onclick="close_modal()">&times;</span>
					<h4 class="modal-title">Reassign Opportunity</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" id="reassign_type">
					<center>
						<span style="color: darkgrey"> Selecting a Executive replaces Executive owner</span>
						<br>
						<span style="color: darkgrey"> Selecting a Manager replaces Manager owner</span>
					</center>
					<hr>
					<div class="row targetrow">
						<div class="col-md-2">
							<label for="mgrlist">Users</label>
						</div>
						<div class="col-md-10">
							<div id="mgrlist" class="multiselect">
							<ul></ul>
							</div>
							<span class="error-alert"> </span>
						</div>
					</div>
					<div class="row targetrow">
						<div class="col-md-2">
							<label for="assign_remarks">Reassign Remarks</label>
						</div>
						<div class="col-md-10">
							<textarea cols="80" rows="5" placeholder="Enter remarks for reassigning Opportunity(s)" id="assign_remarks" class="form-control"></textarea>
							<span class="error-alert"> </span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn" onclick="assign_save()" value="Reassign">
					<input type="button" class="btn" onclick="close_modal()" value="Cancel">
				</div>
			</div>
		</div>
	</div>
	<div id="error_modal" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel1()">x</span>
					<center><h4 class="modal-title">Errors</h4></center>
				</div>
				<div class="modal-body">
					<div class="row">
						<label>Something went wrong... Couldn't proceed further</label>
					</div>
					<div class="row">
						<table class="table" id="error_table">
							<thead>
								<tr>
									<th class="table_header" style="text-align: left;">#</th>
									<th class="table_header" style="text-align: left;">Remarks</th>
									<th class="table_header" style="text-align: left;">Filename</th>
								</tr>
							</thead>
							<tbody id="tablebody"></tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn" onclick="cancel1()" value="Close" >
				</div>
			</div>
		</div>
	</div>

</div>
<div id="closeOpportunityPopup" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel1()">x</span>
					<h4 class="modal-title">Close Opportunity</h4>
				</div>
				<div class="modal-body">
                    <form id="opp_close_form" method="POST" enctype="multipart/form-data">
					<div>
						<div class="row">
    						<!--<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
    							<label for="stage_closed_date">Close Date *</label>
    						</div>
    						<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
    							<div class='input-group date'>
    								<input type="text" class="form-control closeinput" name="stage_closed_date1" id="stage_closed_date1" readonly />
    								<span class="input-group-addon">
    									<span class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" id="glyph_close_date1"></span>
    								</span>
    							</div>
    							<span class="error-alert"></span>
    						</div>-->
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label for="close_status_select"> Close Status *</label>
						</div>
						<div class="col-md-9">
							<select id="close_status_select" class="form-control" name="close_status_select">
								<option value="">Choose Status</option>
									<option value="temporary_loss">Temporary Loss</option>
									<option value="permanent_loss">Permanent Loss</option>
							</select>
								<span class="error-alert"></span>
						</div>
					</div>
					<div class="row none" id="temp_date">
						<div class="col-md-3">
							<label> Remind me on Date* </label>
						</div>
						<div class="col-md-9">
							<div class='input-group date' id='tempdate_picker'>
								<input id="tempdate" name="tempdate" placeholder="Approach this opportunity again on..." type='text' class="form-control" readonly/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row temp-loss none" id="StateChangeTitle">
						<div class="col-md-3">
							<span>Title*</span>
						</div>
						<div class="col-md-9">
							<input type="text" name="ChangeTitle" class="form-control">
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row temp-loss none" id="StateChangeFutureActivity">
						<div class="col-md-3">
							<span>Future Activity*</span>
						</div>
						<div class="col-md-9">
							<select class="form-control" name="FutureActivity">
								<option value="">Select</option>

							</select>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row temp-loss none" id="stateChangeDateSection">
						<div class="col-md-3">
							<label>Date*</label>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<div class="input-group date">
									<input id="stage_closed_date1" name="stage_closed_date1" placeholder="Pick a date" type="text" class="form-control" readonly="readonly">
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
					<div class="row temp-loss none" id="stateChangeActivityduration">
						<div class="col-md-3">
							<span>Activity duration*</span>
						</div>
						<div class="col-md-9">
							<input type="text" class="form-control" name="Activityduration" placeholder="HH:MM" maxlength="5">
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row temp-loss none" id="stateChangeAlertBefore">
						<div class="col-md-3">
							<span>Alert Before*</span>
						</div>
						<div class="col-md-9">
							<select class="form-control" name="AlertBefore">
								<option value="">Select</option>
								<option value="5">5 mins</option>
								<option value="15">15 mins</option>
								<option value="30">30 mins</option>
							</select>
						<span class="error-alert"></span>
						</div>
					</div>
					<div class="row temp-loss none" id="stateChangeContactType">
						<div class="col-md-3">
							<span>Contact Type*</span>
						</div>
						<div class="col-md-9">
							<select class="form-control" name="ContactType">
								<option value="">Select</option>
							</select>
							<span class="error-alert"></span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-3">
							<label for="stage_remarks">Remarks *</label>
						</div>
						<div class="col-md-9">
							<textarea id="stage_remarks1" name="stage_remarks1" class="form-control"></textarea>
							<span class="error-alert"></span>
						</div>
					</div>
                    <div class="row" id="lead_cust_close">
						<div class="col-md-6">
							<label id="alsoCloseLead"></label>
						</div>
						<div class="col-md-6">
							<label id="close_open_activities"></label>
						</div>
					</div>
					<input type="hidden" name="lead_name" id="lead_name1" >
				  	<input type="hidden" name="opportunity_name" id="opportunity_name1" >
					<input type="hidden" name="stage_owner_name" id="stage_owner_name1" >
					<input type="hidden" name="stage_owner_id" id="stage_owner_id1" >
					<input type="hidden" name="manager_owner_name" id="manager_owner_name1" >
					<input type="hidden" name="manager_owner_id" id="manager_owner_id1" >

					<input type="hidden" name="stage_manager_owner_id" id="stage_manager_owner_id" >
				  	<input type="hidden" name="opportunity_id1" id="opportunity_id1" >
					<input type="hidden" name="lead_cust_id1" id="lead_cust_id1" >
					<input type="hidden" name="stage_id1" id="stage_id1" >
					<input type="hidden" name="cycle_id1" id="cycle_id1" >
					<input type="hidden" name="user_id1" id="user_id1" >
					<input type="hidden" name="sell_type1" id="sell_type1" >

                </form>
				</div>
				<div class="modal-footer">
					<div  class="row">
						<div class="col-md-12">
							<input type="button" class="btn opp_btn1" onclick="close_opportunity_simple('manager');" value="Close Opportunity">
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
    <div id="alert" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
						<div class="modal-body">
							<div class="row">
								<center>
									<span></span>
									<br>
									<br>
									<input type="button" class="btn" data-dismiss="modal" value="Ok">
								</center>
							</div>
						</div>
                    </div>
                </div>
            </div>
<?php require ('footer.php'); ?>
</body>
</html>