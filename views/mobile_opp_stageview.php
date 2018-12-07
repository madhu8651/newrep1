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

	.content-wrapper.body-content {
		top: 0px !important;
	}
	#opp_summary_panel{
		border: 0px;
		-webkit-box-shadow: 0px rgb(0,0,0,0);
		box-shadow: 0px rgba(0,0,0,0);
	}
	.opp_summary_heading{
		border-radius: 10px;
		margin: auto;
		width: 80%;
		box-shadow: 0px 0px 4px 4px rgba(160, 133, 133, 0.78);
	}
	.opp_summary_row	{
		margin-top: 10px;
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
		width: 99%;
		padding-left: 6px;
		padding-right: 6px;
		margin-bottom: 200px;
	}
	.arrow{
		display: inline;
		float: right;
	}
	.stage_body .panel-title{
		color: black;
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
	.table.dataTable{
		width: 100% !important;
	}
    @media only screen and (min-device-width: 300px) and (max-device-width: 632px){
		.static{
			width: 100%;
			margin: 0 0 0 0 !important;
			left:0px;
		}
		.table.table {
			margin-top: 0!important;
			margin-bottom: 0px;
		}
		.opp_stage {
			margin: auto;
		}
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

	function team_members_Add(){
		if($("#check_all").prop("checked") == true){
			$(".team_mem_show1").show();
		}else{
			$(".team_mem_show1").hide();
		}
		console.log(opportunity_details)
		$("#add_notify_contact").html("");
		var currencyhtml="";
		currencyhtml +='<div id="product_value1" class="multiselect">';
		currencyhtml +='<ul>';
			for( i=0; i<opportunity_details.contacts.length;i++){
				currencyhtml +='<li><label><input type="checkbox" value="'+opportunity_details.contacts[i].contact_id+'" name = "contact_list[]" onchange="clear_field()"><span id="name_val">  '+opportunity_details.contacts[i].contact_name+'</span><label></li>';
			}
		currencyhtml +='</ul>';
		currencyhtml +='</div>';
		$("#add_notify_contact").append(currencyhtml);
	}

	$(document).ready(function(){
		init_stageview_page();
		$(".go-top").click(function(){
			$("#questionnaire").animate({ scrollTop: 0 }, "slow");
			return false;
		});
		$(".go-top").click(function() {
			$("#questionnaire").animate({
				scrollTop: 0
			}, 500);
		});
		$("#questionnaire").scroll(function() {
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
			minDate:moment()
		});
		$("#stage_closed_date").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'YYYY-MM-DD',
			minDate:moment()
		});
		$('#opp_progress_form').on('submit', function (e) {
			e.preventDefault();
		});
		/* Code change on 21-08-2018 for closed opprotunity form outside of the progress popup 
		$('#close_status_select').change(function()	{
			var status = $(this).val();
			if (status == 'temporary_loss') {
				$('#temp_date').show();
				$('#close_lead_cust').show();
				$('#lead_cust_close').show();
			} else if (status == 'permanent_loss') {
				$('#close_lead_cust').show();
				$('#lead_cust_close').show();
				$('#temp_date').hide();
				$('#tempdate').val(null);
			} else if (status == 'closed_won') {
				$('#close_lead_cust').hide();
				$('#lead_cust_close').hide();
				$('#temp_date').hide();
				$('#tempdate').val(null);
			}
		});
		*/
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
		oppoObj.user_id = "<?php echo $user_id ?>";
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('webservice_Controller/oppo_details')?>",
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
				/* if not involved with the opportunity, the user should not see it*/
				if (data.canUpdate == "1") {
					$("#opp_update").show();
				} else {
					window.history.back();
				}
				if ((data.closed_reason != null) || (data.canEditProducts == 0)) {
					/* $("#save_products_btn").hide(); */
					$("#proadd_btn").hide();
					$("#opp_products").prop('disabled',true);
				}

				$('#amount_view').text(data.opportunity_value);
				$('#quantity_view').text(data.opportunity_numbers);
				$('#rate_view').text(data.opportunity_rate);
				$('#score_view').text(data.opportunity_score);
				$('#priority_view').text(data.opportunity_priority);
				$('#customer_code_view').text(data.opportunity_customer_code);
				if (data.opportunity_date != "0000-00-00") {
					$("#close_date_view").text(data.opportunity_date);
				}
				$("#opportunity_id").val(data.opportunity_id);
				$("#opp_name").text(data.opportunity_name);
				// if (data.currency_name!=null) {
				// 	$('#currency_short_name').text(data.currency_name.split('-')[1]);
				// 	$('#currency_name').attr('data-original-title',data.currency_name);
				// } else {
				// 	$('#currency_short_name').text('-');
				// 	$('#currency_name').attr('data-original-title','No Currency Defined');
				// }
				$("#lead_name").text(data.lead_name);
				var contacts = [];
				for(i=0; i<data.contacts.length;i++){
					contacts.push(data.contacts[i].contact_name);
				}
				$("#lead_contact").text(contacts.join(', '))
				if (data.sell_type == 'new_sell') {
					$("#sell_type_name").text('New Sell');
					$('#lead_cust_close').show();
					
				}
				else if (data.sell_type == 'up_sell') {
					$("#sell_type_name").text('Up Sell');
					$('#lead_cust_close').hide();
					$('#close_lead_cust').html('');
				}
				else if (data.sell_type == 'cross_sell') {
					$("#sell_type_name").text('Cross Sell');
					$('#lead_cust_close').hide();
					$('#close_lead_cust').html('');
				}
				$('#close_lead_cust').html('<input type="checkbox" name="close_lead_cust"> Also close Lead (<b>'+data.lead_name+'</b>) <span class="glyphicon glyphicon-info-sign" data-placement="right" data-toggle="tooltip" data-original-title="Only if there are no other active opportunities"></span>');
				$('#close_open_activities').html('<input type="checkbox" name="close_lead_cust"> Also close all open activities');
				refresh_product_summary();
				$("#indusrty_name").text(data.industry_name);
				$("#location_name").text(data.location_name);
				$("#stage_name").text(data.stage_name);
				$("#stage_owner").text(data.stage_owner);
				$("#creator_name").text(data.owner_name);
				
				$("#currency_name").text(data.currency_name);
				var C_date = data.created_time;
				$("#created_date_lbl").text(moment(C_date).format("lll"));
				
				if (data.closed_reason == null){
					$("#cur_stage_name").text(data.stage_name);
					/* only stage_owner can progress*/
					if (data.canProgress == "1") {
						$("#opp_progress_btn").show();
						$('#opp_close_btn').show();//can close opprotunity btn -- temp/permanent show
					} else {
						$("#opp_progress_btn").hide();
						$('#opp_close_btn').hide();//can close opprotunity btn temp/permanent hide
					}
					/* only owner can close at any time. */
					if (data.canClose == "1") {
						$("#close_accept_btn").show()
						$("#close_progress_btn").show();
					} else {
						$("#close_accept_btn").hide()
						$("#close_progress_btn").hide();
					}
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
					$('#opp_close_btn').hide();//can close opprotunity btn-- temp/permanent hide
					$('#opp_progress_btn').hide();
					$("#close_accept_btn").hide()
					$("#close_progress_btn").hide();
				}
				$('#owner_mgr_name').text(data.manager_owner_name);
				$('#cur_opp_mgr').text(data.stage_manager_owner_name);
				$('#stage_mgr_name').text(data.stage_manager_owner_name);
				$('#cur_stage_mgr').text(data.stage_owner);
				$("#lead_cust_id").val(data.lead_cust_id);
				$("#stage_id").val(data.opportunity_stage);
				$("#cycle_id").val(data.cycle_id);
				$("#user_id").val(data.user_id);
				$("#sell_type").val(data.sell_type);
				$("#history_table_opp_name").text(data.opportunity_name);
		
				get_stage_history();

				//-----------------Code added on 21-08-2018------------Start
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
				
				var setflg=0;
                opportunity_details.stage_attr.forEach(function(elm){
					if(elm.attribute_value == 'closedwon'){
							close_wondiv = 'show';
					}else{
							setflg=1;
					}
                })
                // Incase the flag is set 1 and the opportunity is in last stage closed won check box will appear automatically
                if(setflg==1){
                        opportunity_details.next_stage_attr.forEach(function(elm){
                        if(elm.next_seq_no == 100){
                                  close_wondiv = 'show';
                                  $("#close_wondiv input[type=checkbox]").attr("disabled","disabled");
                                  $("#close_wondiv input[type=checkbox]").prop("checked",true);
                                  }
                        })
                }
				
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
				//-----------------Code added on 21-08-2018------------End--
				// code added to disable the lead close button based on the logged in user is lead manager or lead rep owner
				$('#lead_cust_close').find('.infoMsg').remove();
                  if(data.leadclosebit == 0){
				   $('#lead_cust_close input[type=checkbox]').attr('disabled', 'disabled');
				   $('#lead_cust_close').append('<div class="col-md-12 text-center infoMsg" style="color:red"><p>'+data.leadclosereason+'</p></div>');
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
        if( opportunity_details.closed_reason == null){
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
				check_opp_actions();
			}
		});
	}

	/* fill accordion menu with data */
	function frame_stage_history(data) {
		$(".stage_body").empty();
		var row = '';
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
				row += '<tr><td style="text-align:left; width:30%;"><strong>Stage Owner</strong></td><td>'+data[i].user_name+'</td><td></td><td></td></tr>';
			}
			if(data[i].opp_close_date){
				row += '<tr><td style="text-align:left; width:30%;"><strong>Expected Close Date</strong></td><td>'+data[i].opp_close_date+'</td><td></td></tr>';
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
					row += '<ul class="doc_list"><li style="margin-left: -38px;"><a href="<?php echo site_url();?>'+path+'" target="_blank">';
					if(format == 'doc' || format == 'docx' || format == 'pdf' || format == 'rtf' || format == 'txt' || format == 'xls' || format == 'csv'){
						row += '<span><i class="fa fa-fw fa-file-text-o" aria-hidden="true"></i></span>';
					}
					if(format == 'jpg' || format == 'jpeg' || format == 'gif' || format == 'bmp' || format == 'png'){
						row += '<span><i class="fa fa-fw fa-file-image-o" aria-hidden="true"></i></span>';
					}
					row+=file_name+' (by '+data[i].docs[j].doc_user_id+')</a>'
					row+='<span><i class="fa fa-fw fa-clock-o" data-toggle="tooltip" title="'+data[i].docs[j].created_date+'" aria-hidden="true"></i></span></li></ul>';
				}
				row +='</td></tr>';
			}
			if(data[i].qualifiers.length>0){
				row +='<tr><td style="text-align:left; width:30%;"><strong>Qualifier</strong></td><td style="text-align:left">';
				for(j=0;j<data[i].qualifiers.length;j++){
					var qual_mapping = data[i].qualifiers[j].mapping_id;
					var qual_user = data[i].qualifiers[j].user_name;
					var qual_name = data[i].qualifiers[j].qualifier_name;
					var qual_status = data[i].qualifiers[j].status;
					row += '<ul class="doc_list">';
						row+='<li style="margin-left: -38px;">';
						if (qual_status == 1) {
							row+='<span href="JavaScript:void(0)" style="color:green;" onclick="view_qualifier_answered(\''+qual_mapping+'\')">';
						} else {
							row+='<span href="JavaScript:void(0)" style="color:red;" onclick="view_qualifier_answered(\''+qual_mapping+'\')">';
						}
						row+='<i class="fa fa-fw fa-file-text-o" aria-hidden="true"></i> '+qual_name+' (by '+qual_user+')';
						row+='<span><i class="fa fa-fw fa-clock-o" data-toggle="tooltip" title="'+data[i].qualifiers[j].timestamp+'" aria-hidden="true"></i></span>';
						row+='</span></li>';
					row+='</ul>';
				}
				row +='</td></tr>';
			}
			if(data[i].remarks){
				row += '<tr><td style="text-align:left; width:30%;"><strong>Remarks</strong></td><td>'+data[i].remarks+'</td><td></td><td></td></tr>';
			}
			if(data[i].timestamp){
				row += '<tr><td style="text-align:left; width:30%;"><strong>Date & Time</strong></td><td>'+moment(data[i].timestamp).format('lll')+'</td><td></td><td></td></tr>';
			}
			row +='</tbody></table><center><a href="javascript: void(0)" class="stage_activity_a" onclick="fetch_stage_activities(\''+data[i].stage_id+'\');"> Stage Activities</a></center></div></div></div></div>';
		}
		if (opportunity_details.closed_reason == null) {
			row+='<div class="panel-group">';
				row+='<div class="panel panel-default">';
					row+='<div class="panel-heading">';
						row+='<h4 class="panel-title"><strong>'+opportunity_details.stage_name+'</strong></h4></div>';
				row+='<div id="some_id" class="panel-collapse collapse in" aria-expanded="true">'
				row+='<div class="panel-body">';
				row+=opportunity_details.stage_desc
				row+='</div><center><a href="javascript: void(0)" class="stage_activity_a" onclick="fetch_stage_activities(\''+opportunity_details.opportunity_stage+'\');"> Stage Activities</a></center></div></div></div>';
		}

		$(".stage_body").append(row);
	}

	function fetch_stage_activities(stage_id) {
		console.log(stage_id);
		$('#opp_logdetails').modal('show');
		load_opp_log(stage_id);
	}

	function delete_product(product_id) {
		var success = confirm("Are you sure you want to delete this Product?")
		if (success == true) {
			console.log(product_id);
			$("#opp_product_table #"+product_id+"").remove();
			for (var i = 0; i < opportunity_details.oppoProducts.length; i++) {
				if (product_id == opportunity_details.oppoProducts[i].product_id) {
					opportunity_details.oppoProducts.splice(i,1);
				}
			}
			reload_product_table();
            $("#opp_products option.saved").each(function(){
                 if (product_id == $(this).val()) {
					$(this).show();
				}
            });
		}
	}

	function edit_product(product_data) {
		console.log(product_data);
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

		for (var i = 0 ; i <= opportunity_details.oppoProducts.length - 1; i++) {
			var row = "<tr id='"+opportunity_details.oppoProducts[i].product_id+"'>"+
					"<td>"+(i+1)+"</td>"+
					"<td> <label class='product_name'>"+opportunity_details.oppoProducts[i].product_name+" </label></td>";
				if (quantity_attr_exists == true && opportunity_details.closed_reason == null) {
					row += "<td> <input type='number' min='0' class='quantity' value='"+opportunity_details.oppoProducts[i].quantity+"'></td>";
				} else if(quantity_attr_exists == false) {
					row += "<td> <input type='number' min='0' style='display:none' class='quantity' value='' disabled></td>";
				}else{
                    row += "<td> <input type='number' min='0' class='quantity' value='"+opportunity_details.oppoProducts[i].quantity+"' disabled></td>";
				}
				if (amount_attr_exists == true && opportunity_details.closed_reason == null) {
					row += "<td> <input type='number' min='0' class='amount' value='"+opportunity_details.oppoProducts[i].amount+"'></td>";
				} else if(amount_attr_exists == false) {
					row += "<td> <input type='number' min='0' style='display:none' class='amount' value='' disabled></td>";
				}else{
                    row += "<td> <input type='number' min='0' lass='amount' value='"+opportunity_details.oppoProducts[i].amount+"' disabled></td>";
				}
				if (opportunity_details.closed_reason == null) {
					if(opportunity_details.oppoProducts.length > 1){
                        row += "<td onclick='delete_product(\""+opportunity_details.oppoProducts[i].product_id+"\");'> <span class='glyphicon glyphicon-trash'></span></td></tr>";
                    }else{
                        row += "<td></td></tr>";
                    }
				} else {
				    $("#save_products_btn").hide();
                    $("#mul_prod_remarks").prop("disabled",true);
					row += "<td></td></tr>"
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
				$.alert({
					title: 'L Connectt',
					content: "Can't add a product already added",
					Ok: function () {
						
					}
				});
				return
			}
		}

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
						obj.quantity = $(td).find('input.quantity').val()
					}
				}
			})
			array.push(obj);
		})
		opportunity_details.oppoProducts = array;

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
						obj.quantity = $(td).find('input.quantity').val()
					}
				}
			})
			array.push(obj);
		})
		if (opportunity_details.oppoProducts.length == 0) {
			$.alert({
				title: 'L Connectt',
				content: "There should be at least one product",
				Ok: function () {
					
				}
			});
			return;
		}

		if($("#mul_prod_remarks").val()==""){
			$("#mul_prod_remarks").closest("div").find("span").text("Please enter remarks for Changes made");
			$("#mul_prod_remarks").focus();
			return;
		} else {
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
		obj.module = 'sales';
		obj.remarks = $("#mul_prod_remarks").val();

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
				loaderHide();
				$.alert({
					title: 'L Connectt',
					content: 'Data has been saved successfully.',
					animation: 'none',
					closeAnimation: 'scale',
					buttons: {
						Ok: function () {
							window.location.reload();
						}
					}
				});
			}
		});
	}

	function check_opp_actions() {
		var oppoObj = {};
		oppoObj.opportunity_id = opportunity_details.opportunity_id;
		oppoObj.user_id = "<?php echo $user_id ?>";
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_opportunitiesController/check_for_opp')?>",
			dataType : 'json',
			data : JSON.stringify(oppoObj),
			cache : false,
			success : function(data){
				if(error_handler(data)) {
					return;
				}
				if (data.length == 0) {
					$('#new_opp_actions').hide();
				} else {
					$('#new_opp_actions').show();
					var ownership = '';
					var stage_ownership = '';
					for (var i = 0; i < data.length; i++) {
						console.log(data[i]);
						if ( (data[i].owner_status==1 || data[i].owner_status==3) && (data[i].ownerreject==0))	{
							ownership = 1;
							$("#opp_accept").attr('onclick', 'accept_opportunity(1)');
						}
						if ( (data[i].stage_owner_status==1 || data[i].stage_owner_status==3) && (data[i].stagereject == 0)){
							stage_ownership = 1;
							$("#opp_accept").attr('onclick', 'accept_opportunity(2)');
						}
					}
					if (ownership == 1 && stage_ownership == 1) {
						/* both are 1 show only one button*/
					} else if (ownership == 1) {
						/* mention that he's accepting ownership */
					} else if (stage_ownership == 1) {
						/* mention that he's accepting stage_ownership */
					}
				}
			}
		});
	}

	function accept_opportunity(flag){
		var obj = opportunity_details;
		var addObj={};
		addObj.opportuniy_id = obj.opportunity_id;
		addObj.lead_cust_id  = obj.lead_cust_id;
		addObj.sell_type 	 = obj.sell_type;
		addObj.opportunity_stage = obj.opportunity_stage;
		addObj.cycle_id 	 = obj.cycle_id;
		if(flag==1){
			addObj.opportuniy ="ownership";
		} else if(flag==2) {
			addObj.opportuniy ="stage";
		}
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_opportunitiesController/accept_opportunity'); ?>",
			data    : JSON.stringify(addObj),
			dataType : 'json',
			cache : false,
			success : function(data){
	            if (error_handler(data)) {
	                return ;
	            }
				if(data==1){
				   window.location.reload();
				}
				loaderHide();
			}
		});
	}

	function reject_opportunity(){
		$('#reject_lid').modal('show');
		if(opportunity_details.owner_status == 1){
			 $('#owner_reject').css({'display':'block'});
		}
		if(opportunity_details.stage_owner_status == 1){
			$('#stage_reject').css({'display':'block'});
		}
	}

	function reject_opportunity_final() {
		var checkboxChkFlg=0;
		$("#owner,#stage").each(function(){
			if($(this).prop("checked") == true){
				checkboxChkFlg=1;
			}
		});
		if(checkboxChkFlg==0){
			$("#owner").closest(".row").find(".error-alert").text("Select Decline type");
			return;
		} else {
			$("#owner").closest(".row").find(".error-alert").text("");
		}
		if($("#rej_remarks").val()==""){
			$("#rej_remarks").closest("div").find("span").text("Please enter remarks for declining");
			$("#rej_remarks").focus();
			return;
		} else {
			$("#rej_remarks").closest("div").find("span").text("");
		}
		var addObj={};
		addObj.opp_reject=[];
		var radios = document.getElementsByName("opp_reject");
		for(var j=0;j<radios.length;j++){
			if(radios[j].checked){
			   addObj.opp_reject[j] = radios[j].value;
			}
		}
		var remarks=$("#rej_remarks").val();
		addObj.opportuniy_id = opportunity_details.opportunity_id;
		addObj.stage_id = opportunity_details.opportunity_stage;
		addObj.remarks = remarks;
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_opportunitiesController/reject_opportunity'); ?>",
			data    : JSON.stringify(addObj),
			dataType : 'json',
			cache : false,
			success : function(data){
	            if (error_handler(data)) {
	                return ;
	            }
				$('#reject_lid').modal('hide');
				window.location.reload();
				loaderHide();
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
							var timestamp = history[i].timestamp;
							/* -------------------- */
							if ((remarks != null) && (remarks.length > 0)) {
								remarks = "<div>Remarks - " + remarks +"</div>";
							}else{
								remarks = "";
							}
							/* -------------------- */
							/*"<br /><b>Stage</b> - " + stage_name + */
							/*on <h5 style='display:inline;'>" + timestamp + "</h5>*/
							var rowhtml = '';
							if (action == 'created') {
								rowhtml = "<tr class='info'>"+
											"<td>"+
											"<div class='created'>"+
												"<div>"+
												"<h4 style='display:inline;'><i class='fa fa-money fa-fw'></i>"+capitalizeFirstLetter(action)+"</h4> for "+ opportunity_details.lead_name + " </div>"+
												"<div><strong> By </strong>- " + from_name + " ("+module+")</div>"+
												"<strong>Stage </strong>- " + stage_name + remarks;
												
								rowhtml += "<h6 style='display:inline;color:#777777'>" + timestamp + "</h6>"+
											"</div>"+
											"</td>"+
										"</tr>";
							}
							else if (assign_actions.indexOf(action) >= 0)	{
								/* get count of this mapping ID in array. */
								assigned_to = 0;
								assigned_to_names = "<ul>";
								for(var c = 0; c < history.length; c++)	{
									if (history[c].mapping_id == history[i].mapping_id) {
										assigned_to++;
										assigned_to_names += '<li class="align-left">'+history[c].to_user_name+' ('+history[c].module+')('+history[c].action +')</li>';
									}
								}
								assigned_to_names += '<ul>'
								if(assigned_to > 1)	{
									to_name = assigned_to + " users <span class='glyphicon glyphicon-info-sign' data-placement='right' data-trigger='hover' data-html='true' data-title='"+assigned_to_names+"' data-toggle='tooltip'></span>";
								}
								rowhtml = '<tr><td><div class="assigned">'+
										'<div><h4 style="display:inline;"><i class="fa fa-users fa-fw"></i>'+capitalizeFirstLetter(action)+'</h4> to '+ to_name + '</div>' +remarks;
								
								rowhtml += "<h6 style='display:inline;color:#777777'>" + timestamp + "</h6></div></td></tr>";
							}
							else if (accept_actions.indexOf(action) >= 0)	{
								rowhtml = "<tr><td><div class='accepted'>"+
											"<div><h4 style='display:inline;color:green;'>"+
												"<i class='fa fa-user-plus fa-fw'></i> " + capitalizeFirstLetter(action) + "(" + module + ")</h4></div>"+
											"<div><strong>By </strong>- "+ to_name + "</div>" + remarks;
								
								rowhtml += "<h6 style='display:inline;color:#777777'>" + timestamp + "</h6></div></td></tr>";
							}
							else if (action == 'updated')	{
								rowhtml = '<tr> <td><div class="remarks">'+
											"<div><h4 style='display:inline;'> <i class='fa fa-pencil fa-fw'></i>"+capitalizeFirstLetter(action)+"</h4> </div>"+
											"<div><strong>By </strong>- " + from_name + "</div>"+ remarks;
								
								rowhtml += "<h6 style='display:inline;color:#777777'>" + timestamp + "</h6></div></td></tr>";
							}
							else if (action == 'passed qualifier') {
								rowhtml = '<tr> <td><div class="passed_qualifier">'+
											'<div><h4 style="display:inline;color:green;"><i class="fa fa-check-square fa-fw"></i>Qualifier Passed</h4> </div>'+
											"<div><strong> By </strong>- " + from_name + "</div>"+
											"<strong>Stage </strong>-" + stage_name + "<div></div>";
								rowhtml += "<h6 style='display:inline;color:#777777'>" + timestamp + "</h6></div></td></tr>";
							}
							else if (action == 'failed qualifier') {
								rowhtml = '<tr> <td><div class="passed_qualifier">'+
											'<div><h4 style="display:inline;color:red;"><i class="fa fa-minus-square fa-fw"></i> Qualifier Failed</h4></div>'+
											'+<div><strong> By </strong>- ' + from_name + '</div>'+
											'<strong>Stage </strong>-' + stage_name + '<div></div>';
								rowhtml += '<h6 style="display:inline;color:#777777">' + timestamp + '</h6></div></td></tr>';
							}
							else if (action == 'stage progressed')	{
								rowhtml = '<tr> <td><div class="stage_changed">'+
											'<div><h4 style="display:inline;"><i class="fa fa-step-forward fa-fw"></i> '+capitalizeFirstLetter(action)+'</h4></div>'+
											'<div><strong>at Stage </strong>-' + stage_name + '<div></div>'+
											'<div><strong>By </strong>- ' + from_name + '</b></div>' + remarks;
								
								rowhtml += '<h6 style="display:inline;color:#777777">' + timestamp + '</h6></div></td></tr>';
							}
							else if (action == 'rejected')	{
								rowhtml = '<tr class="danger"> <td><div class="rejected">'+
											'<div><h4 style="display:inline;color:red;"> <i class="fa fa-ban fa-fw"></i> Stage '+capitalizeFirstLetter(action)+'</h4> at ' + stage_name + ' </div>'+
											'<div><strong> By </strong>- ' + from_name + '</div>' + remarks;
								
								rowhtml += '<h6 style="display:inline;color:#777777">' + timestamp + '</h6></div></td></tr>';
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
								rowhtml = "<tr class='"+class_col+"'> <td><div class='accepted'>"+
											"<div><h4 style='display:inline;'><i class='"+fa_icon+"'></i>"+ 
											capitalizeFirstLetter(action)+"</h4> </div>"+
											"<div><strong> By </strong>- "+ to_name + "</div>"+
											"<strong>Stage </strong>-" + stage_name + remarks;
								
								rowhtml += "<h6 style='display:inline;color:#777777'>" + timestamp + "</h6></div></td></tr>";
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
					rowhtml += "<tr>"+
						"<td>"+(i+1)+"</td>"+
						"<td>"+data[i].log_name+"</td>"+
						"<td>"+data[i].user+"</td>"+
						"<td>"+data[i].contact_name+"</td>"+
						"<td>"+data[i].activity+"</td>"+
						"<td>"+data[i].start_time+"</td>"+
						"<td>"+data[i].end_time+"</td>"+
						"<td>"+data[i].rating+"/4</td>"+
						"<td>"+data[i].remarks+"</td>"+
						"<td>"+data[i].path+"</td>"+
					"</tr>";
				}
				$('#opp_log_div #tablebody').append(rowhtml);
				var objDiv = document.getElementById("opp_log_div");
				objDiv.scrollTop = objDiv.scrollHeight;
			}
		});
	}

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
					rowhtml += "<tr>"+
						"<td>"+(i+1)+"</td>"+
						"<td>"+data[i].event_name+"</td>"+
						"<td>"+data[i].user+"</td>"+
						"<td>"+data[i].contact_name+"</td>"+
						"<td>"+data[i].activity+"</td>"+
						"<td>"+data[i].start_time+"</td>"+
						"<td>"+data[i].end_time+"</td>"+
						"<td>"+data[i].remarks+"</td>"+
					"</tr>";
				}
				$('#opp_task_div #tablebody').append(rowhtml);
				var objDiv = document.getElementById("opp_log_div");
				objDiv.scrollTop = objDiv.scrollHeight;
			}
		});
	}
	/* renders fields based on attributes enabled for the stage */
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
		
		$('#opp_progress').attr('value', 'Progress');
		$("#opp_progress").attr("onclick","opp_progress()");
		
		$('#close_opportunity_details').hide();	
		$("#add_notify_contact").html("");
		
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
		if (next_stage_data.length > 0) {
			for(var i=0;i<next_stage_data.length; i++)	{
				if (next_stage_data[i].next_seq_no == "100") {
					/* hide progress button and show close opportunity button */
					$('#close_progress_btn').hide();
					$('#close_opportunity_details').show();
					if($("#accept").css("display")!="none"){
						/* change title of progress in accept to close & also on click action */
						$('#opp_approve').attr('value', 'Close Opportunity');
						$("#opp_approve").attr("onclick","close_opportunity()");
						$('#opp_reject').hide();
					} else if($("#progress").css("display")!="none"){
						/* change title of progress in progress to close & also on click action */
						// while the user is in last stage then display Won opportunity rather than close Opportunity
						$('#opp_progress').attr('value', 'Won Opportunity');
						$("#opp_progress").attr("onclick","close_opportunity()");
					}
				}
			}
		} else {
			$("#accept").hide();
			$("#progress").hide();
		}
		$("#updatePopup").modal("show");
	}

	/* renders fields based on check mark selection for close opportunity */
	/*---------------------------------------------------------
	function close_opp(item) {
		$('#lead_cust_close').hide();
		$('#close_lead_cust').hide();
		$('#temp_date').hide();
		$('#close_lead_cust').find('input[type = checkbox]').prop('checked', false);
		$('#close_status_select').val('');
		$("#close_status_select").closest('div').find('.error-alert').text('');
		if (item.checked == true) {
			$('#close_opportunity_details').show();
			if($("#accept").css("display")!="none"){
				// change title of progress in accept to close & also on click action //
				$('#opp_approve').attr('value', 'Close Opportunity');
				$("#opp_approve").attr("onclick","close_opportunity()");
				$('#opp_reject').hide();
			} else if($("#progress").css("display")!="none"){
				// change title of progress in progress to close & also on click action //
				$('#opp_progress1').attr('value', 'Close Opportunity');
				$("#opp_progress1").attr("onclick","close_opportunity()");
			}
		} else {
			$('#close_opportunity_details').hide();
			if($("#accept").css("display")!="none"){
				// change title of progress in accept to close & also on click action //
				$('#opp_approve').attr('value', 'Approve');
				$("#opp_approve").attr("onclick","opp_progress()");
				$('#opp_reject').show();
			} else if($("#progress").css("display")!="none"){
				// change title of progress in progress to close & also on click action //
				$('#opp_progress1').attr('value', 'Progress');
				$("#opp_progress1").attr("onclick","opp_progress()");
			}
		}
	}-------------- opp_progress -------*/
	function close_opp(item) {
		/*$('#lead_cust_close').hide();
		$('#close_lead_cust').hide();*/
		$('#temp_date').hide();
		$('#close_status_select').val('');
		$('#close_lead_cust').find('input[type = checkbox]').prop('checked', false);
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
	// called when the close opportunity is done from outside the progress dialogue pop up
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
				$.alert({
					title: 'L Connectt',
					content: data,
					buttons: {
						Ok: function () {
							window.location.reload();
						}
					}
				});	
				/* alert(data);
                cancel1(); */
			},

		});
	}
	/*------------------Close opportunity from outside of progress popup form---------------------*/
	function closeOpportunity_popup(){
		$("#stateChangeActivityduration input[type=text]").val('00:00');
		$("#closeOpportunityPopup .temp-loss").hide();
		$("#closeOpportunityPopup").modal("show");
		$('#lead_cust_close').show();
	}
	/*---------------------------------------*/
	/* get data and setup qualifier and show it --- */
	function setup_questionnaire(data) {
		if(data == false){			
			if (next_stage_seq_no() == "100") {
				close_opportunity();
			} else {
				opp_progress_final();
			}
		} else {
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
	}

	/*function validate_attributes() {
		$("#updatePopup").find(".error-alert").text("");
		var isValid = false;
		var stage_owner = opportunity_details.stage_owner_id;
		var owner = opportunity_details.owner_id;

		if ((stage_owner == '-') || (stage_owner == null)) {
			alert("Can't progress without a stage owner");
			return isValid;
		}
		if ((owner == '-') || (owner == null)) {
			alert("Can't progress without an opportunity owner");
			return isValid;
		}
		for (var i = 0; i < opportunity_details.stage_attr.length; i++) {
			if (opportunity_details.stage_attr[i].attribute_name == 'quantity') {
				for (var j = 0; j < opportunity_details.oppoProducts.length; j++) {
					if (opportunity_details.oppoProducts[j].quantity == "") {
						var product_name = opportunity_details.oppoProducts[j].product_name;
						alert("Quantity not added for product - " + product_name);
						return false;
					}
				}
			}

			else if (opportunity_details.stage_attr[i].attribute_name == 'amount') {
				for (var k = 0; k < opportunity_details.oppoProducts.length; k++) {
					if (opportunity_details.oppoProducts[k].amount == "") {
						var product_name = opportunity_details.oppoProducts[k].product_name;
						alert("Amount not added for product - " + product_name);
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
			if($.trim($("#stage_closed_date").val()) == ""){
				$("#stage_closed_date").closest("div").siblings(".error-alert").text("Expected Close Date is mandatory");
				return isValid;
			}else{
				$("#stage_closed_date").closest("div").siblings(".error-alert").text("");
			}
		}

		var remarkAlrtMsg = "Please Enter Remarks";


		if (parseInt(next_stage_seq_no()) == 100) {
			if($.trim($("#close_status_select").val()) == "" || $("#close_status_select").val() == null) {
				$("#close_status_select").closest('div').find('.error-alert').text('Select a closure type');
				$("#close_status_select").focus();
				return isValid;
			} else {
				$("#close_status_select").closest('div').find('.error-alert').text('');
			}


			if($("#close_status_select").val() == 'temporary_loss'){
				if($("#tempdate").val()==""){
					$("#tempdate").closest("div").find("span").text("Approach date is required");
					return isValid;
				}else{
					$("#tempdate").closest("div").find("span").text("");
				}
			} else {
				$("#tempdate").closest("div").find("span").text("");
			}
			remarkAlrtMsg = 'Enter Closing Remarks';
		}

		if($.trim($("#stage_remarks").val())==""){
			$("#stage_remarks").closest("div").find(".error-alert").text(remarkAlrtMsg);
			$("#stage_remarks").focus();
			return isValid;
		}else if(!comment_validation($.trim($("#stage_remarks").val()))){
			$("#stage_remarks").closest("div").find(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
			$("#stage_remarks").focus();
			return isValid;
		}else {
			$('#stage_remarks').closest("div").find(".error-alert").text("");
		}
		return true;
	}*/
    function validate_attributes(){
		$("#updatePopup").find(".error-alert").text("");

		var isValid = false;
		var stage_owner = opportunity_details.stage_owner_id;
		var owner = opportunity_details.owner_id;

		if ((stage_owner == '-') || (stage_owner == null)) {
			/* alert("Can't progress without a stage owner"); */
			$.alert({
				title: 'L Connectt',
				content: "Can't progress without a stage owner",
				Ok: function () {
					
				}
			});	
			return isValid;
		}
		if ((owner == '-') || (owner == null)) {
			//alert("Can't progress without an opportunity owner");
            
			$.alert({
				title: 'L Connectt',
				content: "Can't progress without a stage owner",
				Ok: function () {
					
				}
			});	
			return isValid;
		}

		for (var i = 0; i < opportunity_details.stage_attr.length; i++) {
			if (opportunity_details.stage_attr[i].attribute_name == 'quantity') {
				for (var j = 0; j < opportunity_details.oppoProducts.length; j++) {
					if (opportunity_details.oppoProducts[j].quantity == "") {
						var product_name = opportunity_details.oppoProducts[j].product_name;
						//alert("Quantity not added for product - " + product_name);
                        
						$.alert({
							title: 'L Connectt',
							content: "Quantity not added for product - " + product_name,
							Ok: function () {
								
							}
						});
						return false;
					}
				}
			}

			else if (opportunity_details.stage_attr[i].attribute_name == 'amount') {
				for (var k = 0; k < opportunity_details.oppoProducts.length; k++) {
					if (opportunity_details.oppoProducts[k].amount == "") {
						var product_name = opportunity_details.oppoProducts[k].product_name;
						//alert("Amount not added for product - " + product_name);
						$.alert({
							title: 'L Connectt',
							content: "Amount not added for product - " + product_name,
							Ok: function () {
								
							}
						});
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


		var remarkAlrtMsg = "Please Enter Remarks";

		if (next_stage_seq_no() == "100" || $('#close_wondiv').css('display')!='none') {

			remarkAlrtMsg = 'Enter Closing Remarks';
		}

		if($.trim($("#stage_remarks").val())==""){
			$("#stage_remarks").closest("div").find(".error-alert").text(remarkAlrtMsg);
			return isValid;
		}else if(!comment_validation($.trim($("#stage_remarks").val()))){
			$("#stage_remarks").closest("div").find(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
			$("#stage_remarks").focus();
			return isValid;
		}else {
			$('#stage_remarks').closest("div").find(".error-alert").text("");
		}

		return true;
	}

	/* save the attributes on the opportunity */
	function opp_save() {
		/* should also be able to edit contacts of opportunity */
		var stage_owner = opportunity_details.stage_owner_id;
		var owner = opportunity_details.owner_id;
		if ((stage_owner == '-') || (stage_owner == null)) {
			$.alert({
				title: 'L Connectt',
				content: "Can't progress without a stage owner",
				Ok: function () {
					
				}
			});
			return ;
		}
		if ((owner == '-') || (owner == null)) {
			$.alert({
				title: 'L Connectt',
				content: "Can't progress without an opportunity owner",
				Ok: function () {
					
				}
			});
			return ;
		}
		
		if($.trim($("#stage_remarks").val())==""){
			$("#stage_remarks").closest("div").find(".error-alert").text("Please Enter Remarks");
			return isValid;
		}else if(!comment_validation($.trim($("#stage_remarks").val()))){
			$("#stage_remarks").closest("div").find(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
			$("#stage_remarks").focus();
			return isValid;
		}else {
			$('#stage_remarks').closest("div").find(".error-alert").text("");
		}

		hide_footer(true);
		opp_id = $("#opportunity_id").val();
		var formData = new FormData($('#opp_progress_form')[0]);
		$.ajax({
			type: 'POST',
			enctype: 'multipart/form-data',
			url: "<?php echo site_url('sales_opportunitiesController/update_opportunity');?>",
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
				console.log(data);
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
					$('#confirm_modal').modal('show');
					$('#confirm_modal').find('p').text('Data has been saved successfully.');
					cancel1();
					init_stageview_page();
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

	/* method which takes action on progressing the stage
	check for qualifier & file upload status */
	function opp_progress() {
		if (validate_attributes() == false) {
			return;
		}
		hide_footer(true);
		var addObj={};
		var next_stage_id = opportunity_details.next_stage_attr[0].next_stage_id;
		addObj.next_stage_id = next_stage_id;
		addObj.stage_id = $('#stage_id').val();
		addObj.opp_id = opportunity_details.opportunity_id;
		
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_opportunitiesController/progress_check'); ?>",
			dataType : 'json',
			data: JSON.stringify(addObj),
			cache : false,
			success : function(data){
				if(error_handler(data)) {
					return;
				}
				
				fileCheck = data.fileCheck;
				data = data.qualifier;
				
				if (fileCheck == true) {
					var fileInput = document.getElementById("fileUp");
					if ((fileInput.files==null || fileInput.files.length <= 0)) {
						$("#file_check_remarks").find("span").text("Uploading the document is mandatory to progress current stage");
						if($("#accept").css("display")!="none"){
							$("#accept").find("input").attr("disabled", false);
						} else if($("#progress").css("display")!="none"){
							$("#progress").find("input").attr("disabled", false);
						}
					} else {
						setup_questionnaire(data);
					}
				} else {
					setup_questionnaire(data);
				}
			}
		});
	}

	/* submit the questionnaire and if successful, proceed with the stage progression */
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
			mainObj.stage_id = $('#stage_id').val();
			mainObj.lead_id = opportunity_details.lead_cust_id;
			mainObj.opp_id = opportunity_details.opportunity_id;
			mainObj.rep_id = opportunity_details.user_id;

			mainObj.type1_2=someObj;
			mainObj.type3=someObj1;

			$("#submit_qual_btn").attr("disabled", true);
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
					$("#submit_qual_btn").attr("disabled", false);
					if (data == 0) {
						$('#confirm_modal').modal('show');
						$('#confirm_modal').find('p').text('Attempted Qualifier was unsuccessful. Opportunity cannot be progressed to the next stage.');
						if($("#accept").css("display")!="none"){
							$("#accept").find("input").attr("disabled", false);
						} else if($("#progress").css("display")!="none"){
							$("#progress").find("input").attr("disabled", false);
						}
					} else if (data == 1) {
						if (next_stage_seq_no() == "100") {
							close_opportunity();
						} else {
							opp_progress_final();
						}
					}
					$('#Questionnaire').modal('hide');
				}
			});
		}
	}

	/* main method which progresses the opportunity */
	function opp_progress_final(){
		opp_id = $("#opportunity_id").val();
		var formData = new FormData($('#opp_progress_form')[0]);
		hide_footer(true);
		loaderShow();
		$.ajax({
			type: 'POST',
			enctype: 'multipart/form-data',
			url: "<?php echo site_url("sales_opportunitiesController/process_opp/progress"); ?>",
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
					cancel1();
					$('#confirm_modal').modal('show');
					$('#confirm_modal').find('p').html('Congratulations! <br>Your opportunity has progressed to the next stage successfully!');
					window.location.reload();
				} else {
				
				}
				loaderHide();
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

	/* when rejecting a stage, this method is called */
	function reject_stage()	{
		if (validate_attributes() == false) {
			return;
		}
		hide_footer(true);
		/* ajax to check prev stage to revert back to */
		opp_id = $("#opportunity_id").val();
		var formData = new FormData($('#opp_progress_form')[0]);
		loaderShow();
		$.ajax({
			type: 'POST',
			enctype: 'multipart/form-data',
			url: "<?php echo site_url("sales_opportunitiesController/process_opp/reject"); ?>",
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
				if (data.status == true) {
					cancel1();
					$('#confirm_modal').modal('show');
					$('#confirm_modal').find('p').text('Your opportunity has been rejected to the predefined stage successfully.');
					window.location.reload();
				}
				loaderHide();
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

	/* called when the opporunity is closed */
	function close_opportunity() {
		if (validate_attributes() == false) {
			return;
		}
		hide_footer(true);
		opp_id = $("#opportunity_id").val();
		var formData = new FormData($('#opp_progress_form')[0]);
		loaderShow();
		$.ajax({
			type: 'POST',
			enctype: 'multipart/form-data',
			url: "<?php echo site_url("sales_opportunitiesController/close_opportunity"); ?>",
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
					var state = $.trim($('#close_status_select').val())
					if ($('#close_status_select').val() == 'closed_won'){
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
					$('#confirm_modal').modal('show');
					$('#confirm_modal').find('p').html('Opportunity has been closed successfully.');
				}
				loaderHide();
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

	function view_qualifier_answered (obj) {
		console.log(obj);
		//take stage_id and load qualifier for that. then fill answers for that
	}

	/* take out values from all input fields in popup and reset the popup*/
	function cancel1() {
		$('.modal').modal('hide');
		$('.modal #tempdate').data("DateTimePicker").date(null);
		$('.modal #stage_closed_date').data("DateTimePicker").date(null);
		$('.modal #temp_date').hide();
		$('.modal input[type="text"], select, textarea').val('');
		$('.modal input[type="file"]').val('');
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
		$('.modal #num_files_lbl').text("0 File(s) added");
		$('.modal #files_uploaded_list').empty();
		$('.modal #file_up_progress').val("0");
		$('.modal #file_list').hide();
		$('.modal .error-alert').text('');
		opportunity_details.oppoProducts = opportunity_products;
	}

	/* for closing the qualifier */
	function cancel_quest() {
		$('#Questionnaire').modal('hide');
		$('#Questionnaire input[type="text"]').val('');
		$('#Questionnaire input[type="select"]').val('');
		$('#Questionnaire input[type="textarea"]').val('');
		$('#Questionnaire input[type="radio"]').prop('checked', false);
		$('#Questionnaire input[type="checkbox"]').prop('checked', false);
		hide_footer(false);
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
		console.log("File(s) selected");
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
		if (totalFileSize > 15000000) {
			$("#file_check_remarks").find("span").text("Uploaded file size exceeds max file size limit (8MB). Upload one at a time.");
			$('.modal input[type="file"]').val('');
			$("#files_uploaded_list").empty();
			$("#file_up_progress").val("0");
			$('#file_up_progress').attr('max', 1);
			$('#file_list').hide();
		}
	}

	/* toggle title on the opportunity summary button*/
	/*function toggle_opp_summary(id) {
		var item = $(id);
		if (item.attr('aria-expanded') === 'false') {
			$("#opp_summary_title").attr('value','Hide Summary');
		} else {
			$("#opp_summary_title").attr('value','Show Summary');
		}
	}*/
    function toggle_opp_summary(id) {
		var item = $(id);
        setTimeout(
            function(){
                //alert(item.attr('aria-expanded'))
                if (item.attr('aria-expanded') === 'true') {
        			$("#opp_summary_title").attr('value','Hide Summary');
        		} else {
        			$("#opp_summary_title").attr('value','Show Summary');
        		}
            }, 300
        );

	}

	function capitalizeFirstLetter(string) {
		return string.charAt(0).toUpperCase() + string.slice(1);
	}

	/* helper function to disable and enable the footer section of a modal*/
	function hide_footer(status) {
		if($("#accept").css("display")!="none"){
			$("#accept").find("input").attr("disabled", status);
		} else if($("#progress").css("display")!="none"){
			$("#progress").find("input").attr("disabled", status);
		}
	}

	function error_handler(data){
		if(data.hasOwnProperty("errorCode")){
			$('body').append('<div class="mask custom-alert" id="execption_custom_alert"><div style="background:url('+base_url+'images/alert.png);background-size: 60px;background-position: center left;background-repeat: no-repeat;" class="alert alert-danger row custom-alert"><div class="col-md-12"><b>Database Error Code : </b>'+data.errorCode+'</div><div class="col-md-12"><b>Database Error Message : </b>'+data.errorMsg+'</div></div></div>');
			var isInside = false;
			$('#execption_custom_alert .custom-alert').hover(function () {
				isInside = true;
			}, function () {
				isInside = false;
			});
			$(document).mouseup(function () {
				if (!isInside){
					/* $('#execption_custom_alert').remove(); */
				}
			});
			return true;
		}
		return false;
	}

</script>
<?php require 'confetti.php' ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="loader">
  <center><h1 id="loader_txt"></h1></center>
</div>
<div class="">
	<div class="col-lg-12 column" id="content">
		<div class="row header1" align="center">
			<input type="button" class="btn" style="float: left;margin: 3px;" href="#viewDocument" onclick="view_opp_documents();" data-toggle="modal" id="view_document" value="Documents" />
			<input type="button" class="btn" style="margin: 3px;" href="#opp_summary" onclick="toggle_opp_summary('#opp_summary')" data-toggle="collapse" id="opp_summary_title" value="Show Summary" />
			<input type="button" class="btn" style="float: right;margin: 3px;" href="#viewHistory" onclick="view_opp_history()" data-toggle="modal" id="view_history" value="History" />
		</div>
		<div class="row opp_summary_row">
			<div class="panel-group">
				<div class="panel panel-default" id="opp_summary_panel">
					<h4 class="opp_stage"> Opportunity Summary </h4>
					<div id="opp_summary" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="row">
								<center><h4 class="opp_stage" id="opp_name"></h4></center>
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
									<input type="button" class="btn opp_details_btns" value="Scheduled Activites" onclick="load_opp_tasks()" href="#opp_taskdetails" data-toggle="modal">
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
		<div class="row static" style="text-align:center">
			<label for="static_elements"> Current Stage </label> <h4 id="cur_stage_name" class="opp_stage"></h4>
            <table class="table">
                <tr>
                    <td>Current Stage Manager<h6 id="cur_opp_mgr" class="opp_stage"></h6></td>
                    <td>Current Stage Executive<h6 id="cur_stage_mgr" class="opp_stage"></h6></td>
                </tr>
            </table>
			<div class="row none" id="new_opp_actions" align="center">
				<input type="button" class="btn" id="opp_decline" value="Decline" onclick="reject_opportunity();">
				&nbsp; &nbsp;
				<input type="button" class="btn" id="opp_accept" value="Accept">
			</div>
			<input type="button" class="btn none " onclick="progress_popup();" value="Progress" id="opp_progress_btn">
			<input type="button" class="btn none" value="Close Opportunity" onclick="closeOpportunity_popup()" id="opp_close_btn">
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
							<label id="close_lead_cust"></label>
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
							<input type="button" class="btn opp_btn1" onclick="close_opportunity_simple('sales');" value="Close Opportunity">
						</div>
					</div>

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
					<!--code change on 21-08-2018 ------------------start
						<div id="close_opportunity_details" class="none">
						<hr>
						<div class="row">
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<label for="close_status_select"> Close Status *</label>
							</div>
							<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
								<select class="form-control" id="close_status_select" name="close_status_select">
									<option value="">Choose Status</option>
									<optgroup label="Opportunity Won">
										<option value="closed_won">Closed Won</option>
									</optgroup>
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
								<label class="none" id="close_lead_cust"></label>
							</div>
						</div>
						<div class="row none" id="temp_date">
							<div class="col-md-4">
								<label> Remind me on Date* </label>
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
					</div>code change on 21-08-2018 ------------------end-->
					<div class="row">
						<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
							<label for="stage_remarks">Remarks *</label>
						</div>
						<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
							<textarea id="stage_remarks" name="stage_remarks" class="form-control"></textarea>
							<span class="error-alert"></span>
						</div>
					</div>
					<input type="hidden" name="opportunity_id" id="opportunity_id">
					<input type="hidden" name="lead_cust_id" id="lead_cust_id">
					<input type="hidden" name="stage_id" id="stage_id">
					<input type="hidden" name="cycle_id" id="cycle_id">
					<input type="hidden" name="user_id" id="user_id">
					<input type="hidden" name="sell_type" id="sell_type">
					<!-- -mailer is not ready---
						<div class="row show_all">
						<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
							<input type="checkbox" id="check_all" onchange="team_members_Add()"/> <label for="check_all">Notify Contacts</label>
						</div>
						<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 team_mem_show1" id="add_notify_contact" name="add_notify_contact"></div>
					</div>-->
				</form>
				</div>
				
				<!--code change on 21-08-2018 ------------------start
					<div class="modal-footer">
					<div id="progress" class="row">
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 no-padding text-left">
							<label class="none" id="close_progress_btn"> <input type="checkbox" onclick="close_opp(this);"> Close Opportunity </label>
						</div>
						<div class="col-xs-8 col-sm-9 col-md-9 col-lg-9 no-padding">
							<input type="button" class="btn opp_btn1" onclick="opp_progress();" value="Progress" id="opp_progress1">
							<input type="button" class="btn opp_btn1" onclick="opp_save();    " value="Save Details" >
						</div>
					</div>
					<div id="accept" class="none row">
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 no-padding text-left">
							<label class="none" id="close_accept_btn"> <input type="checkbox" onclick="close_opp(this);"> Close Opportunity </label>
						</div>
						<div class="col-xs-8 col-sm-9 col-md-9 col-lg-9 no-padding">
							<input type="button" class="btn opp_btn1" onclick="opp_progress();" value="Approve" id="opp_approve">
							<input type="button" class="btn opp_btn1" onclick="reject_stage();" value="Reject" id="opp_reject">
							<input type="button" class="btn opp_btn1" onclick="opp_save();    " value="Save Details" >
						</div>
					</div>
				</div>code change on 21-08-2018 ------------------end-->
				<div class="modal-footer">
					<div id="progress" class="row">
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 no-padding text-left">
							<label class="none" id="close_wondiv"> <input type="checkbox" onclick="close_opp(this);"> Close Won </label>
						</div>
						<div class="col-xs-8 col-sm-9 col-md-9 col-lg-9 no-padding">
							<input type="button" class="btn opp_btn1" onclick="opp_progress();" value="Progress" id="opp_progress">
                            <input type="button" class="btn opp_btn1" onclick="opp_save();    " value="Save Details" >
						</div>
					</div>
                    <div id="accept" class="none row">
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 no-padding text-left">
							<label class="none" id="close_wondiv"> <input type="checkbox" onclick="close_opp(this);" id="close_won" > Close Won </label>
						</div>
						<div class="col-xs-8 col-sm-9 col-md-9 col-lg-9 no-padding">
							<input type="button" class="btn opp_btn1" onclick="opp_progress();" value="Approve" id="opp_approve">
							<input type="button" class="btn opp_btn1" onclick="reject_stage();" value="Reject" id="opp_reject">
							<input type="button" class="btn opp_btn1" onclick="opp_save();    " value="Save Details" >
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="oppo_products" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel1()">x</span>
					<h4 class="modal-title">Opportunity Products</h4>
				</div>
				<div class="modal-body">
					<div class="row table-responsive">
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
							<input type="button" name="Add" value="Add" class="btn" id='proadd_btn' onclick="add_product()">
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
				<div class="modal-footer">
					<input type="button" class="btn" id="save_products_btn" onclick="save_products()" value="Save Changes">
					<input type="button" class="btn" onclick="cancel1()" value="Close">
				</div>
			</div>
		</div>
	</div>
	<div id="viewHistory" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel1()">x</span>
					<center><h4 class="modal-title">Opportunity History</h4></center>
				</div>
				<div class="modal-body">
					<div class="row opportunity_history" id="opp_history_div">
						<table class="table">
							<thead>
								<tr><th class="table_header" style="text-align: center;"><label id="history_table_opp_name"></label></th></tr>
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
						<input type="hidden" id="rep_id">
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
						<button type="button" class="btn btn-primary" id="submit_qual_btn" onclick="SubmitQpaper()" >Submit</button>
					</center>
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
	<div id="error_modal" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel1()">x</span>
					<center><h4 class="modal-title">Errors</h4></center>
				</div>
				<div class="modal-body">
					<div class="row">
						<label>  Something went wrong... Couldn't proceed further</label>
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
	<div id="reject_lid" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel()">x</span>
					<h4 class="modal-title">Choose Decline Type</h4>
				</div>
				<input type="hidden" id="opp_hidden_id">
				<input type="hidden" id="reject_stage_id">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6" id="owner_reject" style="display:none">
							<input type="checkbox" name="opp_reject" id="owner" value="Ownership">
							<label for="owner">Opportunity Ownership</label>
						</div>
						<div class="col-md-6" id="stage_reject" style="display:none">
							<input type="checkbox" name="opp_reject" id="stage" value="Stage_Ownership" >
							<label for="stage">Stage Ownership</label>
						</div>
						<span class="error-alert"></span>
					</div>
					<div class="row" >
						<div class="col-md-12">
							<label>Remarks*</label>
							<textarea name="Remarks" class="form-control" id="rej_remarks"></textarea>
							<span class="error-alert"></span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn" onclick="reject_opportunity_final()" value="Decline">
					<input type="button" class="btn" onclick="cancel1()" value="Cancel" >
				</div>
			</div>
		</div>
	</div>
	
	<div id="confirm_modal" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<center><h4 class="modal-title"></h4></center>
				</div>
				<div class="modal-body">
					<div class="row">
						<p></p>
					</div>
				</div>
				<div class="modal-footer">
					
				</div>
			</div>
		</div>
	</div>
</div>
<!--<?php //require ('footer.php'); ?> not required for mobile-->
</body>
</html>