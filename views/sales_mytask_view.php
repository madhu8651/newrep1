<!DOCTYPE html>
<html lang="en">
	<head>
	<?php require 'scriptfiles.php' ?>

	<style> 
	
	.drop	{
		margin-left: -12px;
	    margin-bottom: 3px;
	    margin-top: 11px;
	}
	
	.main-sidebar, .left-side{
		z-index:0;
	}
	.completed_btn{
		padding: 2px 4px;
		margin:0;
	}
	/*-----------Type suggestion-------------*/
.tt-dropdown-menu {
	background: #fff;
	border: 1px solid #ccc;
	border-radius: 5px 0 5px 5px; 
	text-align: left;
	width: 300px;
	left: auto !important;
	margin-top: -4px;
	z-index: 4;
	overflow: hidden;
}
.tt-suggestion p {
	cursor: pointer;
	padding: 10px 20px;
	margin: 0;
	display: block;
}
.tt-suggestion {
	padding: 0;
	margin: 0;
}
.tt-suggestion p:hover {
	background-color: #eee;
}
.tt-input {
	background-color: #fff !important;
}
#email_list li, #email_list_edit li, #member_list li {
    display: inline-table;
    margin-right: 5px;
    background: #ccc;
    height: 25px;
    line-height: 25px;
    padding: 0px 5px;
    border-radius: 5px;
	margin-bottom: 5px;
}
#email_list, #email_list_edit, #member_list {
	margin-top: 5px;
	margin-left: -25px;
}
.twitter-typeahead	{
	width: 100%;
}
.remarks {
	height: 150px;
}
.task_remarks	{
	resize: vertical;
}

.mytask_add_modal	{
	width: 100%;
}
#process_cancel{
	margin-left: 16px;
}
.sec_table{
	width:100%!important;
}
.sec_table thead tr .sec_th{
	width:28px!important;
}
#active_color .active a{
	color:#B5000A!important;
	font-weight:bold!important;
}
.overlap.alert{
	z-index: 999; 
	position: absolute;
	width: 60%;
	bottom: 30px;
	right:20%;
	left:20%;
	text-align: center;
} 
.rating_msg{
	position: absolute;
    right: 0px;
}
.table.dataTable{
	width: 100% !important;
}
#mail_body{
	-webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
    font-weight: 400;
    font-size: 14px;
	border: none;
    white-space: pre-line;
    background: no-repeat;
}
	</style>
	
	<script>
	 var navigatorChk,team_mid;
	 var doc_data = "";
	<?php  
	if( $_SESSION['Navigator']==1){?>
	 navigatorChk=1;
	 <?php }
	 else{?>
	 	navigatorChk=0;
	 	<?php
	 }
	 ?>
	 var ownid, ticket_val;
	var time_chk =  new RegExp(/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/);
	var text_chk_spcl_chr =  new RegExp(/^[a-zA-Z0-9 &_.-]*$/);
	
	var rating1 = 0;
	var activity = "Previous";

	var selectedLead = {};
	var selectedOppo = {};
	var email_members = [];
	var reminder_members = [],email_data,email_arr=[];

	var arr1={};
	function clear_field(){
		$("#selcd_pro").empty();
	}
	function team_members_Add(){
		if($("#check_all").prop("checked") == true){
			$(".team_mem_show1").show();
			$(".team_mem_show").hide();
		}else{
			$(".team_mem_show").show();
			$(".team_mem_show1").hide();
		}
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/userList'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				$("#add_product").html("");
				var currencyhtml="";
				currencyhtml +='<div id="product_value1" class="multiselect">';
				currencyhtml +='<ul>';
				for(var j=0;j<data.length; j++){
					if(data[j].sales_module != "0" && data[j].manager_module != "0"){
						currencyhtml +='<li><label><input type="checkbox" value="'+data[j].user_id+'" onchange="clear_field()"><span id="name_val">  '+data[j].user_name+' (Manager) (Executive)</span><label></li>';
					}		
					else if(data[j].manager_module != "0"){
						currencyhtml +='<li><label><input type="checkbox" value="'+data[j].user_id+'" onchange="clear_field()"><span id="name_val">  '+data[j].user_name+' (Manager)</span><label></li>';
					}else if(data[j].sales_module != "0"){
						currencyhtml +='<li><label><input type="checkbox" value="'+data[j].user_id+'" onchange="clear_field()"><span id="name_val">  '+data[j].user_name+'  (Executive)</span><label></li>';
					}	
				}
				currencyhtml +='</ul>';
				currencyhtml +='</div>';
				$("#add_product").append(currencyhtml);
			}			
		});		
	}
	$(document).ready(function(){
		pageload();		
		var url1= window.location.href;
		var fileNameIndex1 = url1.lastIndexOf("/") + 1;
		var filename1 = url1.substr(fileNameIndex1);
        sandbox(filename1);
		$('#completed_starts').datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'lll',
			maxDate: moment(),
		});
	    $('#tableFilter').tooltip({
			html: true,
			title: '<center><h4> Options </h4></center><br><select title="Show Activities for" class="form-control drop" id="typeSelection" style="margin: 0 auto;" onchange="reloadTable()"><option value="both"> Leads &amp; Opportunities</option><option value="lead"> Leads</option><option value="oppo"> Opportunities</option></select>', 
			trigger: "click focus",
			placement: "auto",
			animation: true
		}); 
 		
		
		$("#startDateTimePicker").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'lll',
			minDate: moment(),
		});
		$("#startDateTimePicker1").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'lll',
			maxDate: moment(),
		});
		
		
		$("#actve_duration").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'HH:mm',
			defaultDate:'1970-01-01 00:00'
		});

		$("#cmp_duration").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'HH:mm'
		});


		$("#reschedule_startpicker").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'lll',
			minDate: moment()
		});
		$("#completed_startpicker").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'lll',
		});
		$("#actve_duration2").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'HH:mm',
			defaultDate:'1970-01-01 00:00:00'
		});
		$("#actve_duration").on("dp.change", function (e) {
			if($("#confirm_processAdd").prop('checked') == true){
				var startDateTime = moment($.trim($("#start_date1").val()), 'lll').format('YYYY-MM-DD HH:mm:ss');
			}else{
				var startDateTime = moment($.trim($("#start_date").val()), 'lll').format('YYYY-MM-DD HH:mm:ss');
			}			
			var timeDuradion = moment($.trim($("#actve_duration").val()), 'H [Hrs] m [mins] ss [sec]').format('HH:mm:ss');
			if(startDateTime != "Invalid date"){
				overlap(startDateTime,timeDuradion);
			}
		});
		$("#actve_duration2").on("dp.change", function (e) {
			var startDateTime = moment($.trim($("#start_date2").val()), 'lll').format('YYYY-MM-DD HH:mm:ss');		
			var timeDuradion = 	moment($.trim($("#actve_duration2").val()), 'H [Hrs] m [mins]').format('HH:mm');	
			if(startDateTime != "Invalid date"){
				overlap(startDateTime,timeDuradion);
			}
		});
		/* disable alphabets character entry for date field */
		$(function() {
			var regExp = /[a-zA-Z]/i;
			$('#actve_duration, #cmp_start_time, #cmp_duration').on('keydown keyup', function(e) {
				var value = String.fromCharCode(e.which) || e.key;
				if (regExp.test(value)) {
					e.preventDefault();
					return false;
				}
			});
		});
		$("#cmp_duration, #actve_duration2, #start_time2, #actve_duration").keypress(function(){
		   $this = $(this);       
		   if($this.val().length == 2){
			   $this.val($this.val() + ":");
		   }
		});

		$("#email_check").change(function() {
		    if(this.checked) {
			    $(".email_section").show();
			    $("#email_members").show();
		    } else {
		    	$(".email_section").hide();
			    $("#email_members").hide();
			    $("#email_list").html("");
			    email_members = [];
		    }
		});
		$("#meeting_check").change(function() {
		    if(this.checked) {
			    $(".meeting_section").show();
		    } else {
		    	$(".meeting_section").hide();
			    $("#member_list").html("");
			    email_members = [];
		    }
		});
	});
	
	
		var lobj='';
		var data_opp='';
		function select_ticket_type(){
			var select_type = $("#ticket_type").val();
			var leadObj = {};
			leadObj = ticket_val;	
			leadObj.supportid = $("#select_ticket option:selected").val();			
			loaderShow();
			$.ajax({ 	/* //ajax call for get rep_names */
				type : "POST",
				url : "<?php echo site_url('sales_mytaskController/get_contactsForLead'); ?>",
				data:JSON.stringify(leadObj),
				dataType : 'json',
				cache : false,
				success : function(data){
					loaderHide();
					loaderHide();
					if(error_handler(data)){
							return;
						 }
					var select = $("#contact"), options = "<option value=''>Choose Contacts</option>";
					select.empty();      
					for(var i=0;i<data.length; i++)	{
						options += "<option value='"+data[i].employeeid+"'>"+ data[i].employeename +"</option>";            
					}
					select.append(options);
				}
			});
			
		}
		function save_id(val){
			$("#team_members1").empty();
			$("#lead, #customer, #opportunity, #internal").val("");
			$("#lead, #customer, #opportunity, #internal").removeAttr("title");
			$("#email_members").hide();
			if($("#confirm_processAdd").prop("checked")== true){
				$("#rating, .numberClass").show();
				$(".email_alert").hide();
			}else{
				$(".email_alert").show();
				$("#rating, .numberClass").hide();
			}
			$("#contact, #number").empty();
			$("#contact").closest(".row").hide();
			$("#email_check").prop("checked",false);
			$("#email_list").html("");
			$("#email_members").val("");
			var team_val = $("#team_members1").val();
			$("#team_members1").closest("div").find("span").text("");
			$("#contact").closest(".row").show();
			var select_val = $("#task_type").val();
			var select_val1 = $("#ticket_type").val();
			if(val){
				if(select_val1 == "lead"){
					$(".lead_error").closest("div").find("span").text("");	
					$(".lead_row").show();
					$(".cust_row").hide();
					$(".opp_row").hide();
					$(".inter_row").hide();
					$(".ticket_main").show();
				}
				if(select_val1 == "customer"){
					$(".lead_error").closest("div").find("span").text("");	
					$(".lead_row").hide();
					$(".cust_row").show();
					$(".opp_row").hide();
					$(".inter_row").hide();
					$(".ticket_main").show();
				}	
			}else{
				if(select_val == "lead"){
					$("#lead, #customer, #opportunity, #internal, #ticket_type").val("");
					$(".lead_error").closest("div").find("span").text("");	
					$(".lead_row").show();
					$(".cust_row").hide();
					$(".opp_row").hide();
					$(".inter_row").hide();
					$(".ticket_main").hide();
					$(".ticket_selection").hide();
				}
				if(select_val == "customer"){
					$("#lead, #customer, #opportunity, #internal, #ticket_type").val("");
					$(".lead_error").closest("div").find("span").text("");	
					$(".lead_row").hide();
					$(".cust_row").show();
					$(".opp_row").hide();
					$(".inter_row").hide();
					$(".ticket_main").hide();
					$(".ticket_selection").hide();
				}
				if(select_val == "opportunity"){
					$("#lead, #customer, #opportunity, #internal, #ticket_type").val("");
					$(".lead_error").closest("div").find("span").text("");	
					$(".lead_row").hide();
					$(".cust_row").hide();
					$(".opp_row").show();
					$(".inter_row").hide();
					$(".ticket_main").hide();
					$(".ticket_selection").hide();
				}
				if(select_val == "internal"){
					$("#lead, #customer, #opportunity, #internal, #ticket_type").val("");
					$(".lead_error").closest("div").find("span").text("");	
					$(".lead_row").hide();
					$(".cust_row").hide();
					$(".opp_row").hide();
					$(".inter_row").show();
					$(".ticket_main").hide();
					$(".ticket_selection").hide();
				}
				if(select_val == "ticket"){
					$("#lead, #customer, #opportunity, #internal, #ticket_type").val("");
					$(".ticket_main").show();
					$(".lead_error").closest("div").find("span").text("");	
					$(".lead_row").hide();
					$(".cust_row").hide();
					$(".opp_row").hide();
					$(".inter_row").hide();
					$(".ticket_selection").hide();
				}
			}				
			if(select_val == ""){
				$("#lead, #customer, #opportunity, #internal, #ticket_type").val("");
				$(".lead_row, .cust_row, .opp_row, .inter_row, .ticket_main, .ticket_selection").hide();
				$("#contact").closest(".row").hide();
				$("#email_grant").hide();					
				$("#rating, .numberClass").hide();
			}
			var teamObj = {};	
			team_mid = $("#team_members1").val();
			if($("#confirm_processAdd").is(":checked")){
				teamObj.team_mid = $("#close_task").val();
			}else{	
				teamObj.team_mid = team_mid;
			}
			$("#lead, #internal, #customer").typeahead("destroy");
			var data1='';  
			/* //ajax call to get leadnames */
			$.ajax({ 
				type : "POST",
				url : "<?php echo site_url('sales_mytaskController/get_leads'); ?>",
				dataType : 'json',		
				data : JSON.stringify(teamObj),
				cache : false,
				success : function(data)	{
					if(error_handler(data)){
						return;
					}

					data1=data['inter'];
					data2=data['leacust'];
					
					/* ------------Lead data----------------- */
					var leadData = [];
					var custData = [];
					var userData = [];
					for(i=0; i<data1.length; i++){
						if(data1[i].user_state == 1){						
									userData.push(data1[i]);
									var dataSource2 = new Bloodhound({
									datumTokenizer: Bloodhound.tokenizers.obj.whitespace('user_id', 'user_name'),
									queryTokenizer: Bloodhound.tokenizers.whitespace,
									local: userData									
								});
						}
					}	
					for(i=0; i<data2.length; i++){
						if(data2[i].type == "lead"){
							leadData.push(data2[i]);
							var dataSource = new Bloodhound({
							datumTokenizer: Bloodhound.tokenizers.obj.whitespace('leadid', 'leadname','type'),
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							local: leadData
						});
						}
						if(data2[i].type == "customer"){
							custData.push(data2[i]);
							var dataSource1 = new Bloodhound({
							datumTokenizer: Bloodhound.tokenizers.obj.whitespace('leadid', 'leadname','type'),
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							local: custData
						});
						}					
					}

					if(userData.length>0){
					dataSource2.initialize();

					$('#internal').typeahead({
						minLength: 0,
						highlight: true,
						hint: false
					},{ 
						name: 'email',
						display: function(item) {
							return item.user_name 
						},
						source: dataSource2.ttAdapter(),
						suggestion: function(data1) {
							return '<b>' + data1.user_name + '–' + data1.user_id + '</b>' 
						}
					});
					

					$('#internal').on('typeahead:selected', function (e, datum) {
						$("#contact").empty();
						$("#contact").val("");
						$("#number").empty();
						$("#number").val("");
						
						var match=1;
						if($.trim($(this).text())== datum.user_id){
							match=0;
							return;
						}
						if(match==0){
							$('#internal').val("");
							return;
						}

						if ($("#internal").length <= 1) {
							$('#internal').closest("div").find("span.error-alert").text("");
							$('#internal').val(datum.user_name);
							$("#internal").attr('title', datum.usesr_name);
							selectedLead['id'] = datum.user_id;
							selectedLead['name'] = datum.user_name;
							selectedLead['type'] = 'internal';
							lobj='internal';
							var data3 = [];	
							for(var i=0;i<data1.length; i++){
								if(data1[i].user_id==datum.user_id){
									data3.push(data1[i]);
								}
							}	

						var select = $("#contact"), options = "<option value=''>Choose Contacts</option>";
						select.empty();      
						for(var i=0;i<data3.length; i++)	{
						options += "<option value='"+data3[i].user_id+"'>"+ data3[i].user_name +"</option>";            
						}
						select.append(options);
						
						} else {
							$('#internal').closest("div").find("span.error-alert").text("Can add only contact");
							return;
						}
					});	
					}
					
					if(leadData.length>0){
					dataSource.initialize();

					$('#lead').typeahead({
						minLength: 0,
						highlight: true,
						hint: false
					},{ 
						name: 'email',
						display: function(item) {
							return item.leadname +' ('+ item.type + ')'
						},
						source: dataSource.ttAdapter(),
						suggestion: function(data2) {
							return '<b>' + data2.leadname + '–' + data2.leadid + '</b>' 
						}
					});
					

					$('#lead').on('typeahead:selected', function (e, datum) {
						$("#contact").empty();
						$("#contact").val("");
						$("#number").empty();
						$("#number").val("");
						
						var match=1;
						if($.trim($(this).text())== datum.leadid){
							match=0;
							return;
						}
						if(match==0){
							$('#lead').val("");
							return;
						}

						if ($("#lead").length <= 1) {
							$('#lead').closest("div").find("span.error-alert").text("");
							$('#lead').val(datum.leadname);
							$("#lead").attr('title', datum.leadname);
							selectedLead['id'] = datum.leadid;
							selectedLead['name'] = datum.leadname;
							selectedLead['type'] = datum.type;	

							var leadObj = {};
							leadObj.leadid = datum.leadid;
							leadObj.type=datum.type;
							lobj=datum.type;
							leadObj.supportid="";	
							loaderShow();
							if($('.ticket_main').css('display') != 'none'){
								ticket_val = leadObj;
								$.ajax({ 
									type : "POST",
									url : "<?php echo site_url('sales_mytaskController/get_support_request'); ?>",
									dataType : 'json',		
									data : JSON.stringify(leadObj),
									cache : false,
									success : function(data){
										loaderHide();
										$(".ticket_selection ").show();
										if(error_handler(data)){
												return;
											 }
										var select = $("#select_ticket"), options = "<option value=''>Choose Ticket</option>";
										select.empty();      
										for(var i=0;i<data.length; i++)	{
											options += "<option value='"+data[i].leadid+"'>"+ data[i].leadname +"</option>";            
										}
										select.append(options);
									}
								});
							}else{
								$.ajax({ 	/* //ajax call for get rep_names */
									type : "POST",
									url : "<?php echo site_url('sales_mytaskController/get_contactsForLead'); ?>",
									data:JSON.stringify(leadObj),
									dataType : 'json',
									cache : false,
									success : function(data){
											loaderHide();
											if(error_handler(data)){
												return;
											 }
										var select = $("#contact"), options = "<option value=''>Choose Contacts</option>";
										select.empty();      
										for(var i=0;i<data.length; i++)	{
											options += "<option value='"+data[i].employeeid+"'>"+ data[i].employeename +"</option>";            
										}
										select.append(options);
									}
								});
								
							}
						} else {
							$('#lead').closest("div").find("span.error-alert").text("Can add only one lead");
							return;
						}
						return;
					});	
					}
					if(custData.length>0){
					dataSource1.initialize();
					$('#customer').typeahead({
						minLength: 0,
						highlight: true,
						hint: false
					},{ 
						name: 'email',
						display: function(item) {
							return item.leadname +' ('+ item.type + ')'
						},
						source: dataSource1.ttAdapter(),
						suggestion: function(data2) {
							return '<b>' + data2.leadname + '–' + data2.leadid + '</b>' 
						}
					});
					$('#customer').on('typeahead:selected', function (e, datum) {
						$("#contact").empty();
						$("#contact").val("");
						$("#number").empty();
						$("#number").val("");					
						var match=1;
						if($.trim($(this).text())== datum.leadid){
							match=0;
							return;
						}
						if(match==0){
							$('#customer').val("");
							return;
						}

						if ($("#customer").length <= 1) {
							$('#customer').closest("div").find("span.error-alert").text("");
							$('#customer').val(datum.leadname);
							$("#customer").attr('title', datum.leadname);
							selectedLead['id'] = datum.leadid;
							selectedLead['name'] = datum.leadname;
							selectedLead['type'] = datum.type;

							var leadObj = {};
							leadObj.leadid = datum.leadid;
							leadObj.type=datum.type;	
							leadObj.supportid="";	
							lobj=datum.type;							
							loaderShow();
							if($('.ticket_main').css('display') != 'none'){
								ticket_val = leadObj;
								$.ajax({ 
									type : "POST",
									url : "<?php echo site_url('sales_mytaskController/get_support_request'); ?>",
									dataType : 'json',		
									data : JSON.stringify(leadObj),
									cache : false,
									success : function(data){
										loaderHide();
										$(".ticket_selection ").show();
										if(error_handler(data)){
												return;
											 }
										var select = $("#select_ticket"), options = "<option value=''>Choose Ticket</option>";
										select.empty();      
										for(var i=0;i<data.length; i++)	{
											options += "<option value='"+data[i].leadid+"'>"+ data[i].leadname +"</option>";            
										}
										select.append(options);
									}
								});
							}else{
								$.ajax({ 	/* //ajax call for get rep_names */
									type : "POST",
									url : "<?php echo site_url('sales_mytaskController/get_contactsForLead'); ?>",
									data:JSON.stringify(leadObj),
									dataType : 'json',
									cache : false,
									success : function(data){
										loaderHide();
										if(error_handler(data)){
												return;
											 }
										var select = $("#contact"), options = "<option value=''>Choose Contacts</option>";
										select.empty();      
										for(var i=0;i<data.length; i++)	{
											options += "<option value='"+data[i].employeeid+"'>"+ data[i].employeename +"</option>";            
										}
										select.append(options);
									}
								});
								
							}
							
							
						} else {
							$('#customer').closest("div").find("span.error-alert").text("Can add only one lead");
							return;
						}
					});
					}
				}
		});
	
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/get_opportunities'); ?>",
			dataType : 'json',
			data:JSON.stringify(teamObj),	
			cache : false,
			success : function(data){
				data_opp=data;
				if(error_handler(data)){
                    return;
                }
				var select1 = $("#opportunity"), options1 = "<option value=''>Choose Opportunities</option>";
				select1.empty();
				for(var i=0;i<data.length; i++)	{
					options1 += "<option value='"+data[i].opportunity_id+"'>"+ data[i].opportunity_name +"</option>";
				}
				select1.append(options1);
			}
		});

	}
	var selectedLead={};
	function get_opp(){
		var id=$("#opportunity").val();
		var obj = {};
		obj.opp_id = id;

		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/get_contactsForOpportunity'); ?>",
			dataType : 'json',
			data:JSON.stringify(obj),	
			cache : false,
			success : function(data){
				if(error_handler(data)){
                    return;
                }
				var select = $("#contact"), options = "<option value=''>Choose Contacts</option>";
				select.empty();      
				for(var i=0;i<data.length; i++)	{
					options += "<option value='"+data[i].contact_id+"'>"+ data[i].contact_name +"</option>"; 
					selectedLead['id'] = data[i].opportunity_id;      
					selectedLead['name'] = data[i].opportunity_name; 
					selectedLead['type'] = 'opportunity';
				}
				select.append(options);	
			}
		});
	}	
	/* ----------------------------------------------------------------------------------------------
		**************************** get all scheduled task  *****************************************
	-------------------------------------------------------------------------------------------------- */
	
	function scheduled_task_list(a){
				loaderShow();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('sales_mytaskController/get_mytask'); ?>",
					dataType : 'json',
					cache : false,
					success : function(data){
						$("#user_id_val").val(data.user_id);
						loaderHide();						
						ownid=data.user_id;
						$("#close_task").val(data.user_id);
						if(error_handler(data)){
							return;
						}				
						data = data.taskArray;						
						$('#tableBody').parent("table").dataTable().fnDestroy();
						$('.closeinput').val('');
						$('#tableBody').empty();
						var row = "",state = "open";;
						var ownerName="";
						var type="";
						activeData = data;//16-11-2018
						
						for(i=0; i < data.length; i++ ){
							data[i].remarks = window.btoa(data[i].remarks);
							var rowdata = JSON.stringify(data[i]).replace('\'', '\\');
							var todayDate = moment();
							if(data[i].user_state==0){
								ownerName="<b style='color:red'>"+ data[i].person_name + "</b>";
							}else{
								ownerName= data[i].person_name ;
							}
							if(data[i].type==null){
								if(data[i].leadname == "" || data[i].leadname == "-"){
									type = '';
								}else{
									type = data[i].leadname;
								}
							}	
							else{
								if(data[i].leadname == "" || data[i].leadname == "-"){
									type = data[i].type;
								}else{
									type = data[i].leadname +  " (" + data[i].type + ")" ;
								}
							}

							var givenDate = moment(data[i].meeting_start);
							
							var compositKey = data[i].row_id+data[i].table_name;//16-11-2018
							if(data[i].status=='pending'){

							   //	row += "<tr><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].lookup_value + "</td><td>" + data[i].leadname+ type + "</td><td>" + data[i].employeename + "</td><td>"+ownerName +" ("+data[i].Department_name+")"+"</td><td>"+data[i].created_by+"</td><td style='color:red'>"+data[i].status+"</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
								row += "<tr><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].event_name + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + data[i].employeename + "</td><td>"+data[i].created_by+"</td><td style='color:red'>"+data[i].status+"</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
							}else if(data[i].status=='cancel'){

							   //	row += "<tr><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].lookup_value + "</td><td>" + data[i].leadname+ type + "</td><td>" + data[i].employeename + "</td><td>"+ownerName +" ("+data[i].Department_name+")"+"</td><td>"+data[i].created_by+"</td><td >Cancelled</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
								row += "<tr><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].event_name + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + data[i].employeename + "</td><td>"+data[i].created_by+"</td><td >Cancelled</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
							}else if(data[i].status=='complete'){

							  //	row += "<tr><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].lookup_value + "</td><td>" + data[i].leadname+ type + "</td><td>" + data[i].employeename + "</td><td>"+ownerName +" ("+data[i].Department_name+")"+"</td><td>"+data[i].created_by+"</td><td >Completed</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
								row += "<tr><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].event_name + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + data[i].employeename + "</td><td>"+data[i].created_by+"</td><td >Completed</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
							}else if(data[i].status=='scheduled'){

							  //	row += "<tr><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].lookup_value + "</td><td>" + data[i].leadname+ type + "</td><td>" + data[i].employeename + "</td><td>"+ownerName +" ("+data[i].Department_name+")"+"</td><td>"+data[i].created_by+"</td><td style='color:blue'>Scheduled</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
								row += "<tr><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].event_name + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + data[i].employeename + "</td><td>"+data[i].created_by+"</td><td style='color:blue'>Scheduled</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
							}else if(data[i].status=='reshcedule'){

							  //	row += "<tr><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].lookup_value + "</td><td>" + data[i].leadname+ type + "</td><td>" + data[i].employeename + "</td><td>"+ownerName +" ("+data[i].Department_name+")"+"</td><td>"+data[i].created_by+"</td><td >Rescheduled</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
								row += "<tr><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].event_name + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + data[i].employeename + "</td><td>"+data[i].created_by+"</td><td >Rescheduled</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
							}
							else if ( todayDate.diff(givenDate,'days') == 0 ) {/* today */
								var status_name = "";
								if(data[i].status == "pending"){
									status_name = "Pending";
								}
								if(data[i].status == "scheduled"){
									status_name = "Scheduled";
								}
							   //	row += "<tr><td>" + (i+1)+ "</td><td> " + givenDate.format('lll') +"</td><td>" + data[i].lookup_value + "</td><td>" + data[i].leadname+ type + "</td><td>" + data[i].employeename + "</td><td>"+ ownerName +" ("+data[i].Department_name+")"+"</td><td>"+data[i].created_by+"</td><td style='color:blue'>"+status_name+"</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
								row += "<tr><td>" + (i+1)+ "</td><td> " + givenDate.format('lll') +"</td><td>" + data[i].event_name + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + data[i].employeename + "</td><td>"+data[i].created_by+"</td><td style='color:blue'>"+status_name+"</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";

							} else if ( todayDate.diff(givenDate,'days') == 1 ) { /* yesterday */
								var status_name = "";
								if(data[i].status == "pending"){
									status_name = "Pending";
								}
								if(data[i].status == "scheduled"){
									status_name = "Scheduled";
								}
							   //	row += "<tr'><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].lookup_value + "</td><td>" + data[i].leadname+ type  + "</td><td>" + data[i].employeename + "</td><b>"+ownerName +" ("+data[i].Department_name+")"+ "</b></td><td>"+data[i].created_by+"</td><td style='color:red'>"+status_name+"</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
								row += "<tr'><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].event_name + "</td><td>" + data[i].lookup_value + "</td><td>" + type  + "</td><td>" + data[i].employeename + "</td><td>"+data[i].created_by+"</td><td style='color:red'>"+status_name+"</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";

							}else if( todayDate.diff(givenDate,'days') == -1 ) { /* tomorrow */
								var status_name = "";
								if(data[i].status == "pending"){
									status_name = "Pending";
								}
								if(data[i].status == "scheduled"){
									status_name = "Scheduled";
								}
							  //	row += "<tr><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].lookup_value + "</td><td>" + data[i].leadname+ type + "</td><td>" + data[i].employeename + "</td><td>"+ownerName+" ("+data[i].Department_name+")"+"</td><td>"+data[i].created_by+"</td><td style='color:blue'>"+status_name+"</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
								row += "<tr><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].event_name + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + data[i].employeename + "</td><td>"+data[i].created_by+"</td><td style='color:blue'>"+status_name+"</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";

							}else{
							  //	row += "<tr><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].lookup_value + "</td><td>" + data[i].leadname+ type + "</td><td>" + data[i].employeename + "</td><td>"+ownerName +" ("+data[i].Department_name+")"+"</td><td>"+data[i].created_by+"</td><td style='color:blue'>"+data[i].status+"</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
								row += "<tr><td>" + (i+1)+ "</td><td>" + givenDate.format('lll') +"</td><td>" + data[i].event_name + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + data[i].employeename + "</td><td>"+data[i].created_by+"</td><td style='color:blue'>"+data[i].status+"</td><td><a data-toggle='modal' href='#completed' onclick='selrow(\""+compositKey+"\")'><button type='button' class='btn completed_btn'>update</button></a></td><td><a onclick='reschedulepopup(\""+compositKey+"\")' class='glyphicon glyphicon-time'  title='Reschedule'></a></td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
							}
							
						}
						$('#tableBody').parent("table").removeClass('hidden');
						$('#tableBody').append(row);					
						$('#tableBody').closest("table").DataTable({
							"aoColumnDefs": [{ "bSortable": false, "aTargets": [8,9,10] }]
						});
						
						if(a==1){
							addpopup();
						}
																	
					}
				});
			}
			
			function completed_reshcedule_task(a){
				var tabl_length = $('#tableBody1').children().length;
				if( a==1){				
					loaderShow();
				}else if(tabl_length>0){
					loaderHide();
				}else{
					loaderShow();
				}
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('sales_mytaskController/getCompletedReshceduleTask'); ?>",
					dataType : 'json',
					cache : false,
					success : function(data){
						ownid=data.user_id;
						data = data.completedArray;
						loaderHide();
						if(error_handler(data)){
							return;
						}
						$('#tableBody1').parent("table").dataTable().fnDestroy();
						$('.closeinput').val('');
						$('#tableBody1').empty();
						var row = "", state = "closed";
						var ownerName="", employe_name = "", scheduleType = '', titleName = '';
						activeData = data;//16-11-2018
						for(i=0; i < data.length; i++ ){
							data[i].remarks = window.btoa(data[i].remarks);
							var rowdata = JSON.stringify(data[i]);
							var todayDate = moment();
							if(data[i].user_state==0){
								ownerName="<b style='color:red'>"+ data[i].person_name + "</b>";
							}else{
								ownerName= data[i].person_name ;
							}
							var Prospect = data[i].leadname;
							if(data[i].type==null){
								if($.trim(data[i].leadname) == "" || $.trim(data[i].leadname) == "-"){
									type = '';
								}else{
									type = data[i].leadname;
								}
							}	
							else{
								if($.trim(data[i].leadname) == "" || $.trim(data[i].leadname) == "-"){
									type = capitalizeFirstLetter(data[i].type);
								}else{
									type = data[i].leadname +  " (" + capitalizeFirstLetter(data[i].type) + ")" ;
								}
							}	
							
							employe_name = data[i].employeename == null ? "" : $.trim(data[i].employeename);
							if(data[i].type == 'mobileDialler' || ($.trim(data[i].leadid) == "" && $.trim(data[i].leadid) == 0 )){
									type="";
									Prospect = 'Not in App';
									employe_name = data[i].employeenumber;
							}
							
							if($.trim(data[i].employeephone1) == "" ){
								employe_name = data[i].employeenumber;
							}
							/*Addition  14-11-2018*/
							if((data[i].lookup_id == "EM594ce66d07b9f87") && data[i].type == "unassociated" && data[i].employeename == null){
								//outgoing mail senction
								if(data[i].mail_to != null){
									employe_name = "";
									List = '<ul>';
									data[i].mail_to.split(',').forEach(function(e,i){
										if(i < 1){
											employe_name += '<span>'+e+'</span><br>';
										}else{
											List += '<li>'+e+'</li>';
										}
									})
									List += '</ul>';
									if((data[i].mail_to.split(',')).length >= 1 && (((data[i].mail_to.split(',')).length) - 1) > 0){
										
										employe_name += '<u rel="tooltip" data-toggle="tooltip" data-trigger="hover" data-placement="bottom" data-html="true" data-title="'+List+'"> '+(((data[i].mail_to.split(',')).length) - 1) +' more </u>';
									}
									//alert(List)//need to work-19-11-2018
								}
							}else if(data[i].lookup_id == "EM594ce66d07b9f83" && data[i].type == "unassociated" && data[i].employeename == null){
								//incoming mail senction
								employe_name = data[i].mail_form;
							}
														
							var givenDate = moment(data[i].meeting_start);
							var closedDate =moment(data[i].closed_date);
							if(data[i].message_id == null || data[i].message_id == ''){
								scheduleType = givenDate.format('lll');
								titleName = data[i].event_name;
							}else{
								scheduleType = 'Unscheduled';
								titleName = data[i].mail_subject;
							}
							/* titleName = titleName.length >40? titleName.substring(0, 40)+'...': titleName; */
							titleName = titleName.split(',').join(', ');
							
							var compositKey = data[i].row_id+data[i].table_name;//16-11-2018
							if(data[i].reminderstatus == 'reschedule'){
								row = "<tr class='warning'><td>" + (i+1)+ "</td><td> "+ closedDate.format('lll') +"</td><td> " + scheduleType +"</td><td>" + titleName + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + employe_name + "</td><td>"+data[i].created_by+"</td><td>Rescheduled</td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
							}
							else if(data[i].reminderstatus == 'complete'){
									row = "<tr><td>" + (i+1)+ "</td><td> "+ closedDate.format('lll') +"</td><td>" + scheduleType +"</td><td>" + titleName + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + employe_name + "</td><td>"+data[i].created_by+"</td><td>Completed</td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
							}else if(data[i].reminderstatus == 'cancel'){
									row = "<tr><td>" + (i+1)+ "</td><td> "+ closedDate.format('lll') +"</td><td>" + scheduleType +"</td><td>" + titleName + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + employe_name + "</td><td>"+data[i].created_by+"</td><td>Cancelled</td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
							}
							else if ( todayDate.diff(givenDate,'days') == 0 ){
								row = "<tr><td>" + (i+1)+ "</td><td>" +closedDate.format('lll')+"</td><td>" +scheduleType +"</td><td>" + titleName + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + employe_name + "</td><td>"+data[i].created_by+"</td><td>"+data[i].reminderstatus+"</td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";

							} else if ( todayDate.diff(givenDate,'days') == 1 ){ /* yesterday */
								row = "<tr><td>" + (i+1)+ "</td><td>"+ closedDate.format('lll') +"</td><td>"+ scheduleType +"</td><td>" + titleName + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + employe_name + "</td><td>"+data[i].created_by+"</td><td>"+data[i].reminderstatus+"</td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";

							} else if ( todayDate.diff(givenDate,'days') == -1 ){ /* tomorrow */
								row = "<tr><td>" + (i+1)+ "</td><td>"+data[i].closed_date +"</td><td>" + scheduleType +"</td><td>" + titleName + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + employe_name + "</td><td>"+data[i].created_by+"</td><td>"+data[i].reminderstatus+"</td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";

							} else if(data[i].reminderstatus == 'cancel'){
								row = "<tr><td>" + (i+1)+ "</td><td>"+data[i].closed_date +"</td><td>" + scheduleType +"</td><td>" + titleName + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + employe_name + "</td><td>"+data[i].created_by+"</td><td>Cancelled</td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
							}else{
								row = "<tr><td>" + (i+1)+ "</td><td>"+data[i].closed_date +"</td><td>" + scheduleType +"</td><td>" + titleName + "</td><td>" + data[i].lookup_value + "</td><td>" + type + "</td><td>" + employe_name + "</td><td>"+data[i].created_by+"</td><td>"+data[i].reminderstatus+"</td><td><a onclick='reschedulepopup1(\""+compositKey+"\",\""+state+"\")' class='glyphicon glyphicon-eye-open'></a></td></tr>";
							}
							$('#tableBody1').parent("table").removeClass('hidden'); 			
							$('#tableBody1').append(row);
							
						}
						$('#tableBody1').closest("table").DataTable({
							"aoColumnDefs": [{ "bSortable": false, "aTargets": [9] }]
						});
						if(data.length <=0 ){
							$('#tableBody1 tr td ').attr("colspan","9");	
						}
						
						if(a==3){
							addpopup();
						}
					}
				});	
			}
			
	/* ----------------------------------------------------------------------------------------------
		**************************** page load *****************************************
	-------------------------------------------------------------------------------------------------- */
	var activeData = {};//global data -- 16-11-2018
	function pageload()	{
		$.ajax({
			type : "POST",
			url :"<?php echo site_url('sales_mytaskController/checkForPending');?>",
			dataType:'json',
			cache : false,
			success :function(data){
				$('#rederr').hide();
				scheduled_task_list()
				/* completed_reshcedule_task() */
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('sales_mytaskController/get_activity'); ?>",
					dataType : 'json',					
					cache : false,
					success : function(data)	{
						if(error_handler(data)){
							return;
						}
						var sel_val;
						var select1 = $("#activity1"), options1 = "<option value=''>Choose Activity</option>";
						var select2 = $("#activity"), options2 = "<option value=''>Choose Activity</option>";
						select1.empty();
						select2.empty();
						for(var i=0;i<data.length; i++)	{
							if(data[i].lookup_value == 'Planned Trip'){
								sel_val=data[i].lookup_id;
							}
							if(data[i].lookup_value == 'Outgoing Call' ||data[i].lookup_value == 'Outgoing SMS' ||data[i].lookup_value == 'Outgoing E-Mail'||data[i].lookup_value == 'Meeting'||data[i].lookup_value == 'Skype call'){
								options1 += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";
							}
							options2 += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";	
						}
						select1.append(options1);
						select2.append(options2);
						 if(navigatorChk==0){
							if(sel_val){
								$("#activity option[value='"+sel_val+"']").remove();
								$("#activity1 option[value='"+sel_val+"']").remove();
							}                			
						}
					}
				});				
				/* this will run both function one after another */
				 //return scheduled_task_list().then(completed_reshcedule_task);
			}
		});	
    }

	
	/* ----------------------------------------------------------------------------------------------
		**************************** Completed Task check uncheck *****************************************
	-------------------------------------------------------------------------------------------------- */
	function confirm_process(fotmtype){
		if(fotmtype == "add"){
			/* ------------------------------------- */
			$("#contact").closest(".row").hide();
			$("#email_grant").hide();
			$("#lead, #customer, #opportunity, #internal").val("");
			$("#rating, .numberClass").hide();
			$("#lead, #internal, #customer").typeahead("destroy");
			/* --------------------------------- */
			if($("#confirm_processAdd").prop('checked')==true){
				$("#task_completed").show();
				$("#task_create").hide();
				$(".error-alert").text("");
				$(".team_mem_show").hide();
				$(".rating_task").show();
				$(".lead_row").hide();
				$(".cust_row").hide();
				$(".opp_row").hide();
				$(".inter_row").hide();
				$(".email_alert").hide();
				$("#rating_activity_add .glyphicon.glyphicon-star").css("color",'#d2cfcf');
			    $("#next_act,#activity").show();
			   
				$(".team_mem_show1").hide();
				$(".ticket_main").hide();
				$(".ticket_selection").hide();
				
			    $("#activity1,#prev_act,.reminder_time_hide").hide();
			    $("#addmodal #actve_duration,#task_type,#contact").val("");
				
				$(".show_all").hide();
				
			    $(".rating_msg ").text("");
			    $(".email_section").hide();
			    $("#email_list").html("");
				
				$('#timepicker1').show();
				$('#timepicker').hide();
				$('#startDateTimePicker').data("DateTimePicker").clear();
				
				$("#add-modal-title").text("Log Completed Task");
				$('#add-modal-title').addClass('animated rubberBand').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
			    	$(this).removeClass('animated rubberBand');
				});
			    rating1 =-1;
			} else {
				$("#task_completed").hide();
				$("#task_create").show();
				$(".error-alert").text("");				
				$(".team_mem_show").show();
				$("#check_all").prop("checked", false);
				$("#rating_activity_add .glyphicon.glyphicon-star").css("color",'#d2cfcf');
			    $("#rating,#next_act,#activity").hide();
			    $("#prev_act,#activity1,.reminder_time_hide").show();
				$("#addmodal #actve_duration,#task_type,#contact").val("");
			    $(".numberClass").hide();
			    $(".rating_msg").text(""); 
			    $(".email_alert").show();
				$(".lead_row").hide();
				$(".cust_row").hide();
				$(".opp_row").hide();
				$(".inter_row").hide();
				$("#email_grant").hide();
			    if ($("#email_check").prop("checked") == true)	{
			    	$(".email_section").show();
			    }
			    
				$(".show_all").show();
				$(".ticket_main").hide();
				$(".ticket_selection").hide();
				
				$('#timepicker').show();
				$('#timepicker1').hide();
				$('#startDateTimePicker1').data("DateTimePicker").clear();
				
				$("#add-modal-title").text("Create Event");
				$('#add-modal-title').addClass('animated rubberBand').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
			    	$(this).removeClass('animated rubberBand');
				});

			    rating1 =0;
			}				
		}
		if(fotmtype == "edit"){
			if($("#confirm_process1").prop('checked')==true){	
				var currentDate	= moment().format("YYYY-MM-DD");
				var assignedDate = moment($.trim($("#assignedDate_hidden").val())).format('YYYY-MM-DD');
				var user_id = $.trim($("#userId_hidden").val());
				if(ownid == user_id && assignedDate < currentDate){
					$("#completed_Modal").modal("show");			
				}else{
					$(".error-alert").text("");
					$("#rating_activity .glyphicon.glyphicon-star").css("color",'#d2cfcf');
					$(".rating_msg").text("");	
					$("#completed_rating").show();
					$("#cmp_duration").show();
					$(".starts_time").show();
					
					$("#process_cancel").prop("checked",false);
					$(".process_task").show();
					$("#save_task").show();
					$("#process_complete").show();
					$(".cancel_task").hide();
					$("#task_save").hide();
					$(".act_dur").show();
					$(".rate_act").show();
					rating1 =-1;
				}
			}else{
				$(".starts_time").hide();
				$(".error-alert").text("");
				$("#rating_activity .glyphicon.glyphicon-star").css("color",'#d2cfcf');
				$(".rating_msg").text("");				
				$("#completed_rating").hide();
				$("#cmp_duration").hide();
				rating1 =0;
			}
		}
	}
	
	/* ----------------------------------------------------------------------------------------------
		**************************** raiting function*****************************************
		-------------------------------------------------------------------------------------------------- */
	function rating(rating){
		$("#rating_error").text("");
		$("#rating_error1").text("");
		
		if($("#confirm_process1").prop('checked')==true || $("#confirm_processAdd").prop('checked')==true){	
			if(rating==4){
				$(".rating1,.rating2,.rating3,.rating4").css("color",'#d2cfcf');
				$(".rating1,.rating2,.rating3,.rating4").css("color",'#B5000A');
				$(".rating_msg").text("Completely achieved");
			}
			if(rating==3){
				$(".rating1,.rating2,.rating3,.rating4").css("color",'#d2cfcf');
				$(".rating1,.rating2,.rating3").css("color",'#B5000A');
				$(".rating_msg").text("Achieved but not completely");
			}
			if(rating==2){
				$(".rating1,.rating2,.rating3,.rating4").css("color",'#d2cfcf');
				$(".rating1,.rating2").css("color",'#B5000A');
				$(".rating_msg").text("Partially achieved");
			}
			if(rating==1){
				$(".rating1,.rating2,.rating3,.rating4").css("color",'#d2cfcf');
				$(".rating1").css("color",'#B5000A');
				$(".rating_msg").text("Did not achieved");
			}
			rating1 = rating;
		}else{
			$("#rating_activity_add .glyphicon.glyphicon-star").removeAttr("style");
			rating1 =0;
		}
	}
	function cancel(){
		$("#task_completed").hide();
		$("#task_create").show();
		$("#activity1").show();
		$(".reminder_time_hide").show();
		$("#activity").hide();
		$('#actve_duration').data("DateTimePicker").date('00:00')
		$('#timepicker1').hide();
		$('#timepicker').show();
		$(".error-alert").val("");
		$("#reschedule_save_bnt").attr("disabled","disabled");
		/* -------------------------------------------- */
		$("#add-modal-title").text("Create Event");
		$("#lead, #customer, #opportunity, #internal").val("");
		$("#rating, .numberClass").hide();
		$("#contact, #number").empty();
		$("#contact").closest(".row").hide();
		$("#email_search").val("");
		$("#email_grant").hide();
		$(".email_section").hide();
		$("#lead, #customer, #opportunity, #internal").removeAttr("title");		
		
		$("#lead, #internal, #customer").typeahead("destroy");
		/* ------------------------------------------------ */
		$('#rederr').hide();
		$('.modal').modal('hide');
		$('.modal input[type="text"],#completed select,#addmodal select, textarea').val('');
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
    	
		$(".act_dur").hide();
		$(".rate_act").hide();
		$(".lead_row").hide();
		$(".cust_row").hide();
		$(".opp_row").hide();
		$(".inter_row").hide();
    	$(".numberClass").hide();
    	$(".rating_task").hide();
		$("#task_save").hide();		
		$(".cancel_task").hide();
    	$(".starts_time").hide();

		$(".show_all").show();
		$(".ticket_main").hide();
		$(".ticket_selection").hide();
		$(".team_mem_show1").hide();
		$("#save_task").show();
		$("#process_complete").show();
		$(".team_mem_show").show();
		$(".process_task").show();
		
		$("#email_list li").each(function(){
			del($.trim($(this).attr('id')));
		});
		selectedLead = {};
		email_members = [];
	}
		
	function addpopup()	{
		time_overlap = false;
		$('#timepicker').show();
		$('#timepicker1').hide();
		
		$("#rating_activity_add .glyphicon.glyphicon-star,#rating_activity .glyphicon.glyphicon-star").removeAttr("style");
		
		/* ajax call to get emails of all members within designation, department,...*/
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/get_emails'); ?>",
			dataType : 'json',					
			cache : false,
			success : function(data){					
				if(error_handler(data)){
                    return;
                }
				var jsonData = data;
				var dataSource = new Bloodhound({
					datumTokenizer: Bloodhound.tokenizers.obj.whitespace('user_name', 'email','department_name','designation'),
					queryTokenizer: Bloodhound.tokenizers.whitespace,
					local: jsonData
				});

				dataSource.initialize();

				$('#email_members').typeahead({
					minLength: 0,
					highlight: true,
					hint: false
				},{ 
					name: 'email',
					display: function(item) {
						return item.user_name + ' ( ' + item.department_name+ ' ) ( ' + item.designation +' )'
					},
					source: dataSource.ttAdapter(),
					suggestion: function(data) {
						return '<b>' + data.user_name + '–' + data.user_id + '</b>' 
					}
				});
			}
		});

		/* ajax call to get contact number on selecting lead name */
		$("#contact").on('change',function(){
			var id=this.value;
			var contactObj = {};
			contactObj.contactid = id;
			if($(".ticket_main").css("display") != "none"){
				contactObj.type = "support";
			}else{
				contactObj.type = lobj;
			}
			if(contactObj.contactid !=""){
				$.ajax({ 	/* ajax call for get rep_names */
						type : "POST",
						url : "<?php echo site_url('sales_mytaskController/get_employeeNumbers'); ?>",
						data:JSON.stringify(contactObj),
						dataType : 'json',
						cache : false,
						success : function(data){
							if(error_handler(data)){
		                    	return;
		                	}
							if(data.length > 0){
								var number = contactformat(data[0].contact_number);	
							}										
							var select = $("#number"), options = "<option value=''>Choose Contact Number</option>";
							select.empty();      
							for (c=0; c<number.length; c++)	{
								options += "<option value='"+number[c].con+"'>"+number[c].con+" ( "+number[c].type+" )</option>";	
							}
							select.append(options);
						}
				});
			}
		});




		$('#email_members').on('typeahead:selected', function (e, datum) {
			var match=1;
			$("#email_list li").each(function(){
				if($.trim($(this).attr('id'))== datum.user_id){
					match=0;
				}
			});
			if(match==0){
				$('#email_members').val("");
				return;
			}
			if ($("#email_list li").length <= 12) {
				email_members.push(datum.user_id);
				
				/*meeting_members = [];
				sales_mytask_selected_user_attendees = [];
				sales_mytask_selected_lead_attendees = [];*/
				$("#email_list").append("<li id="+ datum.user_id+"><span>"+ datum.user_name+" </span><a style='color:red;' class='glyphicon glyphicon-remove-sign' onclick='del(\""+datum.user_id+"\", email_members)'></a></li>");
					$('#email_members').closest("div").find("span.error-alert").text("");
					$('#email_members').val("");	
				
			} else {
				alert("Can't add more than 12 Users");
				$('#email_members').closest("div").find("span.error-alert").text("Can't add more than 12 Users");
				return;
			}
		});
		
		$('#addmodal').modal('show');
		$('#log_previous').prop('checked', true);	 
		$('#confirm_processAdd').prop('checked', false);
	}
	
	/* -------------------------------------------------------------------------------------------------------
	***************************** check event time overlap *************************************
	----------------------------------------------------------------------------------------------------------- */
	var time_overlap = false;
	function overlap(startDateTime,timeDuradion){
			var arr1={};
			arr1.date = startDateTime;		
			arr1.duration = timeDuradion;
			if( arr1.duration === "00:00:00"){
				return;
			}
			$.ajax({	
		        type : "POST",
		        url : "<?php echo site_url('sales_calendarController/checkduration'); ?>",
		        data:JSON.stringify(arr1),
		        dataType : 'json',
		        cache : false,
		        success : function(data){
					if(error_handler(data)){
						return;
					}
		        	if(data==1){
						if($("#rederr").length<=0){
							htm = '<div class="mask custom-alert" id="rederr">'+
							   '<div class="alert alert-danger row custom-alert">'+ 
								   '<div class="col-md-12">'+
										'<center><p><b>There is another activity scheduled for the same time defined for the new activity. Do you still want to continue?</b></p></center>'+
								   '</div>'+
								   '<div class="col-md-12">'+
										'<center>'+
											'<input type="button" class="btn no" value="No"/> '+
											' <input type="button" class="btn yes" value="Yes"/>'+
										'</center>'+
								   '</div>'+
								'</div>'+
						   '</div>';
						   $("body").append(htm);	
						}					    	
		        	}else{
						time_overlap = true;
		        		$("#rederr").remove()
		        		$('div.start_date span').html("message1");
		        	}
					$("#rederr .no").click(function(){
						$("#rederr").remove();
						time_overlap = false;
					});
					$("#rederr .yes").click(function(){
						$("#rederr").remove();
						time_overlap = true;
					});
		        }
			}); 
	}
	
	/* -------------------------------------------------------------------------------------------------------
	***************************** save button click --When Completed Task Checkbox is checked *************************************
	----------------------------------------------------------------------------------------------------------- */
	function addevent1(a){
		var addEventObj ={};
		var date, duration;
		if($.trim($("#event_nameadd").val()) == ""){
			$("#event_nameadd").focus();
			$("#event_nameadd").closest("div").find("span").text("Event Name is required.");
			return;	
		}else if(text_chk_spcl_chr.test($.trim($("#event_nameadd").val())) == false) {
			$("#event_nameadd").focus();
			$("#event_nameadd").closest("div").find("span").text("No spcial character.");
			return;	
		}else{
			$("#event_nameadd").closest("div").find("span").text("");
		}	
		if($.trim($("#activity").val()) == ""){
			$("#activity").closest("div").find("span").text("Activity is required.");
			return;	
		}else{
			$("#activity").closest("div").find("span").text("");
		}
		if($("#task_type").val()!="" ){
			$(".lead_error").closest("div").find("span").text("");
			$(".error-alert").text("");
			var select_val = $("#task_type").val();
			if(select_val == "lead"){
				if($.trim($("#lead").val()) == ""){
					$("#lead").focus();
					$(".alert_errors").closest("div").find("span").text("Please enter a lead");
					return;	
				}else{
					$(".alert_errors").closest("div").find("span").text("");
				}
			}
			if(select_val == "customer"){
				if($.trim($("#customer").val()) == ""){
					$("#customer").focus();
					$(".alert_errors1").closest("div").find("span").text("Please enter a customer");
					return;	
				}else{
					$(".alert_errors1").closest("div").find("span").text("");
				}
			}
			if(select_val == "opportunity"){
				if($.trim($("#opportunity").val()) == ""){
					$("#opportunity").focus();
					$(".alert_errors2").closest("div").find("span").text("Please select an opportunity ");
					return;	
				}else{
					$(".alert_errors2").closest("div").find("span").text("");
				}
			}
			if(select_val == "internal"){
				if($.trim($("#internal").val()) == ""){
					$("#internal").focus();
					$(".alert_errors3").closest("div").find("span").text("Please enter a Contact");
					return;	
				}else{
					$(".alert_errors3").closest("div").find("span").text("");
				}
			}
		}else{
			$(".lead_error").closest("div").find("span").text("Please select lead, customer, opportunity or internal.");
			return;
		}
		
		if($.trim($("#contact").val()) == ""){
			$("#contact").closest("div").find("span").text("Contact is required.");
			return;	
		}else{
			$("#contact").closest("div").find("span").text("");
		}
		if($.trim($("#start_date1").val()) == ""){
			$("#start_date1").closest("#startDateTimePicker1").siblings(".error-alert").text("Start Date is required");			
			return;	
		} else 	{
			date = moment($.trim($("#start_date1").val()), 'lll').format('YYYY-MM-DD HH:mm:ss');
			$("#start_date1").closest("#startDateTimePicker1").siblings(".error-alert").text("");
		}
		
		
		if($.trim($("#actve_duration").val()) == ""){
			$("#actve_duration").closest("div").find(".error-alert").text("Specify estimated activity duration");
			return;	
		}else{
			var duration1 = $.trim($("#actve_duration").val()).split(":");
			if((duration1[0] == "0" && duration1[1] == "0") || (duration1[0] == "00" && duration1[1] == "00")){
				$("#actve_duration").closest("div").find(".error-alert").text("Select valid duration time.");
				return;
			}else{
				duration = moment($.trim($("#actve_duration").val()), 'H [Hrs] m [mins] ss [sec]').format('HH:mm:ss');
				$("#actve_duration").closest("div").find(".error-alert").text("");
			}			
		}	
		if(rating1 == -1){
			$("#rating_error1").text("Raiting is required.");
			return;	
		}else if(rating1 == 0 || rating1 > 0){
			$("#rating_error1").text("");				
		}
		
		/* if($.trim($("#note").val()) == ""){
			$("#note").closest("div").find("span").text("Remarks is required.");
			return;	
		}else  */
		if(!comment_validation($.trim($("#note").val()))){
			$("#note").closest("div").find("span").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
			$("#note").focus();
			return;
		}else{
			$("#note").closest("div").find("span").text("");
		}
		if($("#check_all").prop("checked") == true){
			var team_arr = [], teamName_arr = [];
			$("#product_value1 li input[type=checkbox]").each(function(){
				if($(this).prop('checked')==true){
					team_arr.push($(this).val());
					teamName_arr.push($.trim($(this).closest("li").find("span").text()));
				}        
			});
			if(team_arr.length > 0){
				$("#product_err").find("span").text("");
			}else{
				$("#product_err").find("span").text("Please Select Atleast one user");
				return;
			}
		}
		addEventObj.event_title = 		$.trim($("#event_nameadd").val());
		addEventObj.event_activity = 	$.trim($("#activity").val());
		addEventObj.event_contact = 	$.trim($("#contact").val());
		addEventObj.event_start_date = 	date;
		addEventObj.cmp_phone = 		$('#number').val();
		addEventObj.cmp_duration = 		$.trim(duration);
		addEventObj.event_rating = 		rating1;
		addEventObj.camp_note = 		$.trim($("#note").val()).replace(/\n|\r/g, " ");
		if($(".ticket_main").css("display") != "none"){
			addEventObj.event_lead = $("#select_ticket option:selected").val();
			addEventObj.event_lead_name = $("#select_ticket option:selected").text();
			addEventObj.event_type=			"support";
		}else{
			addEventObj.event_lead_name=	selectedLead['name'];
			addEventObj.event_lead = 		selectedLead['id'];
			addEventObj.event_type=			selectedLead['type'];
		}
		if($("#check_all").prop("checked") == true){
			addEventObj.event_members =	team_arr;
			addEventObj.event_member_name =	teamName_arr;
		}else{
			var event_mem = [];
			var event_mem_name = [];
			event_mem.push($.trim($("#user_id_val").val()));
			addEventObj.event_members =	event_mem;
			var str = $.trim($("#team_members1 option:selected").text());
			var event_member_name = "";/* str.substring(0, str.indexOf("(")); */
			event_mem_name.push(event_member_name);
			addEventObj.event_member_name =	event_mem_name;
		}		
		if(time_overlap == false){
			overlap(addEventObj.event_start_date,addEventObj.cmp_duration);
			return;
		}
		loaderShow();
		$.ajax({		/* //AJAX	call for When task completed button	clicked	 */
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/add_mytaskcomplete'); ?>",
			dataType : 'json',
			data : JSON.stringify(addEventObj),					
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
				if (data==true) {
					active_tab("completed_reshcedule_task_tab" , "tableBody1", "closed", a);
					if(a == 1){
						pageload(1);
					}else{
						pageload();
					}
					cancel();									
				} else {
					loaderHide();
					alert("There was an error adding the contact");
				}

			}
		});
	}

	/* -------------------------------------------------------------------------------------------------------
	***************************** phone number format *************************************
	----------------------------------------------------------------------------------------------------------- */	
	function contactformat(data){
		var contact = JSON.parse(data);
		var contactArray=[];
		for (var key in contact){
		
			if(contact[key].length > 0){
				for(i=0; i<contact[key].length; i++){
					if(contact[key][i].trim() != ""){
						if(key == "home"){
							contactArray.push({"con": contact[key][i].trim(), "type": "home"})
						}
						if(key == "main"){
							contactArray.push({"con": contact[key][i].trim(), "type": "main"})
						}
						if(key == "mobile"){
							contactArray.push({"con": contact[key][i].trim(), "type": "mobile"})
						}
						if(key == "phone"){
							contactArray.push({"con": contact[key][i].trim(), "type": "mobile"})
						}
						if(key == "leadphone"){
							contactArray.push({"con": contact[key][i].trim(), "type": "leadphone"})
						}
						if(key == "work"){
							contactArray.push({"con": contact[key][i].trim(), "type": "work"})
						}
						/* ----------------- */
						if(key == "personal"){
							contactArray.push({"con": contact[key][i].trim(), "type": "personal"})
						}
					}
					
				}				
			}
		}
		return contactArray;
	}
	
	/* -------------------------------------------------------------------------------------------------------
	***************************** save button click --When Completed Task Checkbox is Not checked *************************************
	----------------------------------------------------------------------------------------------------------- */
	function addevent(a){
		var addEventObj ={};
		var date, duration;
		if($.trim($("#event_nameadd").val()) == ""){
			$("#event_nameadd").focus();
			$("#event_nameadd").closest("div").find("span").text("Event Name is required.");
			return;	
		}else if(text_chk_spcl_chr.test($.trim($("#event_nameadd").val())) == false) {
			$("#event_nameadd").focus();
			$("#event_nameadd").closest("div").find("span").text("No special character allowed.");
			return;	
		}else{
			$("#event_nameadd").closest("div").find("span").text("");
		}
		if($.trim($("#activity1").val()) == ""){
			$("#activity1").focus();
			$("#activity1").closest("div").find("span").text("Activity is required.");
			return;	
		}else{
			$("#activity1").closest("div").find("span").text("");
		}
		if($("#task_type").val()!="" ){
			$(".lead_error").closest("div").find("span").text("");
			$(".error-alert").text("");
			var select_val = $("#task_type").val();
			if(select_val == "lead"){
				if($.trim($("#lead").val()) == ""){
					$("#lead").focus();
					$(".alert_errors").closest("div").find("span").text("Please enter a lead");
					return;	
				}else{
					$(".alert_errors").closest("div").find("span").text("");
				}
			}
			if(select_val == "customer"){
				if($.trim($("#customer").val()) == ""){
					$("#customer").focus();
					$(".alert_errors1").closest("div").find("span").text("Please enter a customer");
					return;	
				}else{
					$(".alert_errors1").closest("div").find("span").text("");
				}
			}
			if(select_val == "opportunity"){
				if($.trim($("#opportunity").val()) == ""){
					$("#opportunity").focus();
					$(".alert_errors2").closest("div").find("span").text("Please select an opportunity ");
					return;	
				}else{
					$(".alert_errors2").closest("div").find("span").text("");
				}
			}
			if(select_val == "internal"){
				if($.trim($("#internal").val()) == ""){
					$("#internal").focus();
					$(".alert_errors3").closest("div").find("span").text("Please enter a Contact");
					return;	
				}else{
					$(".alert_errors3").closest("div").find("span").text("");
				}
			}
		}else{
			$(".lead_error").closest("div").find("span").text("Please select lead, customer, opportunity or internal.");
			return;
		}
		
		if($.trim($("#contact").val()) == ""){
			$("#contact").closest("div").find("span").text("Contact is required.");
			return;	
		}else{
			$("#contact").closest("div").find("span").text("");
		}
		
		if($("#check_all").prop("checked") == true){
			var team_arr = [], teamName_arr = [];
			$("#product_value1 li input[type=checkbox]").each(function(){
				if($(this).prop('checked')==true){
					team_arr.push($(this).val());
					teamName_arr.push($.trim($(this).closest("li").find("span").text()));
				}        
			});
			if(team_arr.length > 0){
				$("#product_err").find("span").text("");
			}else{
				$("#product_err").find("span").text("Please Select Atleast one user");
				return;
			}
		}
			
		if($.trim($("#start_date").val()) == ""){
			$("#start_date").closest("#startDateTimePicker").siblings(".error-alert").text("Start Date is required")
			return;	
		} else 	{
			date = moment($.trim($("#start_date").val()), 'lll').format('YYYY-MM-DD HH:mm:ss');
			$("#start_date").closest("#startDateTimePicker").siblings(".error-alert").text("");
		}
		
		if($.trim($("#actve_duration").val()) == ""){
			$("#actve_duration").closest("div").find(".error-alert").text("Specify estimated activity duration");
			return;	
		} else 	{
			var duration1 = $.trim($("#actve_duration").val()).split(":");
			if((duration1[0] == "0" && duration1[1] == "0") || (duration1[0] == "00" && duration1[1] == "00")){
				$("#actve_duration").closest("div").find(".error-alert").text("Select valid duration time.");
				return;
			}else{
				duration = moment($.trim($("#actve_duration").val()), 'H [Hrs] m [mins] ss [sec]').format('HH:mm:ss');
				$("#actve_duration").closest("div").find(".error-alert").text("");
			}
		}
		
		if($.trim($("#reminder_time").val()) == ""){
			$("#reminder_time").focus();
			$("#reminder_time").closest("div").find("span").text("Alert time is required.");
			return;	
		} else 	{
			$("#reminder_time").closest("div").find("span").text("");
		}

		/* if($.trim($("#note").val()) == ""){
			$("#note").closest("div").find("span").text("Remarks is required.");
			return;	
		}else  */
		if(!comment_validation($.trim($("#note").val()))){
			$("#note").closest("div").find("span").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
			$("#note").focus();
			return;
		}else{
			$("#note").closest("div").find("span").text("");
		}
		
		if($("#reminder_time").val()==""){
			var reminder_time = 0;			
		}else{
			reminder_time =$.trim($("#reminder_time").val());
		}
		
		if(rating1 == -1){
			$("#rating_error1").text("Raiting is required.");
			return;	
		}else if(rating1 == 0 || rating1 > 0){
			$("#rating_error1").text("");				
		}	
		var email_arr=[],reminder_members, email_name=[];
		$("#email_list li").each(function(){
			email_arr.push($(this).attr("id"));
			email_name.push($.trim($(this).find("span").text()));
		});
		if($("#email_check").is(":checked") && $("#email_members").val() != ""){
			var name = $("#email_members").val(), flag = 0;
			var name1 = name.split("(");
			if(email_name.length > 0){
				for(i = 0; i < email_name.length; i++){
					if(email_name[i] == $.trim(name1[0])){
						flag = 1;
					}
				}
				if(flag == 1){
					$('#email_members').closest("div").find("span.error-alert").text("");
				}else{
					$('#email_members').closest("div").find("span.error-alert").text("Please enter the email ID of any member in your organization who uses L Connectt.");
					return;
				}
			}else if(name != ''){
				$('#email_members').closest("div").find("span.error-alert").text("Please enter the email ID of any member in your organization who uses L Connectt.");
				return;
			}			
		}
		if($("#email_check").is(":checked")){
			reminder_members = email_arr;
		}else{
			reminder_members = email_arr;
		}
		if($(".ticket_main").css("display") != "none"){
			addEventObj.event_lead = $("#select_ticket option:selected").val();
			addEventObj.event_lead_name = $("#select_ticket option:selected").text();
			addEventObj.event_type=			"support";
		}else{
			addEventObj.event_lead_name=	selectedLead['name'];
			addEventObj.event_lead = 		selectedLead['id'];
			addEventObj.event_type=			selectedLead['type'];
		}
		if($("#check_all").prop("checked") == true){
			addEventObj.event_members =	team_arr;
			addEventObj.event_member_name =	teamName_arr;
		}else{
			var event_mem = [];
			var event_mem_name = [];
			event_mem.push($.trim($("#user_id_val").val()));
			addEventObj.event_members =	event_mem;
			var str = $.trim($("#team_members1 option:selected").text());
			var event_member_name = "";//str.substring(0, str.indexOf("("));
			event_mem_name.push(event_member_name);
			addEventObj.event_member_name =	event_mem_name;
		}
				
		addEventObj.reminder_members = 		reminder_members;		
		addEventObj.event_title = 		$.trim($("#event_nameadd").val());
		addEventObj.event_activity = 	$.trim($("#activity1").val());
		addEventObj.event_contact = 	$.trim($("#contact").val());	
		addEventObj.event_start_date = 	date;
		addEventObj.event_contact_name = 	$.trim($("#contact option:selected").text());
		addEventObj.event_activity_name = 	$.trim($("#activity1 option:selected").text());
		addEventObj.reminder_time =		reminder_time;
		addEventObj.camp_note = 		$.trim($("#note").val()).replace(/\n|\r/g, " ");
		addEventObj.number = 			$.trim($("#number").val());
		addEventObj.active_duration= 	duration;
		addEventObj.email_alert = 		$("#email_check").prop("checked");
		if(time_overlap == false){
			overlap(addEventObj.event_start_date,addEventObj.active_duration);
			return;
		}
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/add_mytask'); ?>",
			dataType : 'json',
			data : JSON.stringify(addEventObj),					
			cache : false,
			success : function(data){
						if(error_handler(data)){
							return;
						}
						$('#rederr').hide();						
						if (data==true){
							active_tab("scheduled_task_tab" , "tableBody", "scheduled_task",a);
							if(a == 1){
								pageload(1);
							}else{
								pageload();
							}
							cancel();	
						}else{
							loaderHide();
							alert("There was an error adding the contact");
						}
			}
		});
	}
	function active_tab(tab , container, data,a){
		$(".dataTables_wrapper").each(function(){
			$(this).parent(".tab-pane.fade").removeClass("active").removeClass("in");
		})
		$("#active_color li").each(function(){
			$(this).removeClass("active");
		})
		$("#"+tab).addClass("active");
		$("#"+container).closest(".tab-pane.fade").addClass("active").addClass("in");
		if(data == "scheduled_task"){
			
			if(a == 1){
				scheduled_task_list(1);
			}else{
				scheduled_task_list();
			}
		}
		if(data == "closed"){
			if(a == 3){
				completed_reshcedule_task(3);
			}else{
				completed_reshcedule_task(1);
			}
			
		}		
	}
	
	/* -------------------------------------------------------------------------------------------------------
	***************************** Update button click on main page *************************************
	----------------------------------------------------------------------------------------------------------- */
	function selrow(key){	
		//previously row data was sent useing function paremeter
		//take obj from global data -- 16-11-2018
		var obj = {}
		activeData.forEach(function(rowData, i){
			if(key == rowData.row_id + rowData.table_name){
				obj = rowData;
			}
		})
		completed_value = obj;
		$("#start_hidden").val(obj.meeting_start);
		$("#member_id_hidden").val(obj.person_id);
		$("#lead_hidden").val(obj.leadname);
		$("#member_hidden").val(obj.person_name);
		$("#contact_hidden").val(obj.employeename);
		$("#activity_hidden").val(obj.lookup_value);
		$("#label_add").empty();
		$("#completed_starts").val(moment(obj.meeting_start).format('lll'));	
		$("#personId").val(obj.person_id);
		$("#completed_type_hidden").val(obj.type);
		$("#old_comp_start_date").val(obj.meeting_start);
		$("#userId_hidden").val(obj.user_id);
		$("#assignedDate_hidden").val(obj.meeting_start);
		
		var currentDate	= moment().format("YYYY-MM-DD");
		var assignedDate = moment(obj.meeting_start).format('YYYY-MM-DD');
		if(ownid == obj.user_id  && assignedDate <= currentDate || assignedDate == currentDate){
			var row="";
			row = '<input type="checkbox" id="confirm_process1" onchange="confirm_process(\'edit\')"> <label id="label_confrm" for="confirm_process1"> Task Completed</label>';			
		}
		$("#label_add").append(row);		
		$('#process').val(obj.lead_reminder_id);
		$('#phone').val(obj.employeephone1);	
		$("#cmp_duration").val(obj.duration);
		$("#leadCustId").val(obj.leadid);

		$("#rating_activity_add .glyphicon.glyphicon-star,#rating_activity .glyphicon.glyphicon-star").css("color",'#d2cfcf');
		$.ajax({ 		/* //ajax call for edit pending task of lead_contact_name */
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/get_leads'); ?>",
			dataType : 'json',					
			cache : false,
			success : function(data){	
				if(error_handler(data)){
						return;
				}
				var select = $("#cmp_lead"), options = "<option value=''>Choose lead</option>";
				select.empty();      
			    for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].leadid+"'>"+ data[i].leadname +"</option>";              
			    }
				select.append(options);
				$('#cmp_lead option[value="'+obj.leadid+'"]').attr("selected",true);
			}
		});

		$.ajax({		/* //ajax call for edit pending task of lead_rep_name */
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/get_activity'); ?>",
			dataType : 'json',					
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}	
				var select = $("#cmp_activity"), options = "<option value=''>Choose Act</option>";
				select.empty();      
				for(var i=0;i<data.length; i++)	{
					options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";            
				}
				select.append(options);
				$('#cmp_activity option[value="'+obj.lookup_id+'"]').attr("selected",true);						  			
			}

		});
		var obj1={};
		obj1.leadid=obj.leadid;
		obj1.type=obj.type;
		$.ajax({ 	/* ajax call for edit pending task of lead_activity */
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/get_contactsForLead'); ?>",	
			data: JSON.stringify(obj1),     
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
				var select = $("#cmp_contact"), options = "<option value=''>Choose Contacts</option>";
				select.empty();      
				for(var i=0;i<data.length; i++)	{
					options += "<option value='"+data[i].employeeid+"'>"+ data[i].employeename +"</option>";            
				}
				select.append(options);						  			
				$('#cmp_contact option[value="'+obj.employeeid+'"]').attr("selected",true);
			}
		});
		$("#popup_title").text(obj.event_name);
		$("#event_name").val(obj.event_name);				
		$("#cmp_start_date").val(obj.remi_date);
		$("#cmp_start_time").val(obj.rem_time);
		$("#cmp_duration").val(obj.duration);
		$("#cmp_activity").val(obj.lookup_id);
		$("#cmp_contact_hidden").val(obj.employeeid);
		$("#cmp_lead").val(obj.leadid);
		//$("#cmp_note").val(window.atob(obj.remarks));
		$(function() {
			$( "#cmp_start_date" ).datepicker({
				dateFormat: 'dd-mm-yy', 
				maxDate: new Date()
			});
		});
		/* var dates = $('#cmp_start_date').datepicker({
			dateFormat: 'yy-mm-dd',
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			minDate: dateToday,
			onSelect: function(selectedDate) {
				var option = this.id == "from" ? "minDate" : "maxDate",
					instance = $(this).data("datepicker"),
					date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
				dates.not(this).datepicker("option", option, date);
			 }
		});*/
		rating(0);
	}
	
	
	function cancel_process1(){
		if($("#process_cancel").prop("checked")==true){
			$(".cancel_close").show();
			$(".cancel_cancel").hide();
			$("#confirm_process1").prop("checked",false);
			$(".process_task").hide();
			$("#save_task").hide();
			$("#process_complete").hide();
			$(".cancel_task").show();
			$("#task_save").show();
			$(".act_dur").hide();
			$(".starts_time").hide();
			$(".rate_act").hide();
			$(".error-alert").text("");
		}else{
			$(".cancel_close").hide();
			$(".cancel_cancel").show();
			$("#save_task").show();
			$("#process_complete").show();
			$("#task_save").hide();
			$(".process_task").show();
			$(".cancel_task").hide();
			$(".starts_time").show();
		}
		
	}
	function cSave_process(){
		var addEventObj ={};
		if($("#cmp_note1").val()==""){
			$("#cmp_note1").focus();
			$("#cmp_note1").closest("div").find("span").text("Remarks is required.");
			return;	
		}else if(!comment_validation($.trim($("#cmp_note1").val()))){
			$("#cmp_note1").closest("div").find("span").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
			$("#cmp_note1").focus();
			return;
		}else{
			$("#cmp_note1").closest("div").find("span").text("");
		}
		addEventObj.camp_note1 = $.trim($("#cmp_note1").val()).replace(/\n|\r/g, " ");
		addEventObj.lead_reminder_id=$('#process').val();
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/cancelEvent'); ?>",
			dataType : 'json',
			data : JSON.stringify(addEventObj),					
			cache : false,
			success : function(data){
				cancel();
				scheduled_task_list();
			}
		});
	}
	function cancel_completed(){
		$("#completed_Modal").modal("hide");
		$("#confirm_process1").prop('checked', false);
	}
	function complete_alert(){
		var obj = completed_value;
		var addEventObj ={};
		addEventObj.camp_note1 = window.atob(obj.remarks);
		addEventObj.lead_reminder_id=$('#process').val();
		addEventObj.cmp_lead_name =					$("#lead_hidden").val();
		addEventObj.cmp_member_name =					$("#member_hidden").val();
		addEventObj.cmp_contact_name =					$("#contact_hidden").val();
		addEventObj.cmp_activity_name =					$("#activity_hidden").val();
		addEventObj.cmp_member_id =					$("#member_id_hidden").val();
		addEventObj.cmp_meeting_start =					$("#start_hidden").val();
		$.ajax({ 	
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/rescheduleEvent'); ?>",	
			data: JSON.stringify(addEventObj),     
			dataType : 'json',
			cache : false,
			success : function(data){
				$("#completed_Modal").modal("hide");
				$("#completed").modal("hide");
				$('#completed_data_modal').modal('show');
				$("#start_hidden").val(obj.meeting_start);
				$("#member_id_hidden").val(obj.person_id);
				$("#lead_hidden").val(obj.leadname);
				$("#member_hidden").val(obj.person_name);
				$("#contact_hidden").val(obj.employeename);
				$("#activity_hidden").val(obj.lookup_value);
				if(obj.type=="lead"){			
					$('#lead2_name').text("Lead*");
				}else if(obj.type=="customer"){
					$('#lead2_name').text("Customer*");
				}else if(obj.type=="opportunity"){
					$('#lead2_name').text("Opportunity*");
				}else if(obj.type=="internal"){
					$('#lead2_name').text("Internal*");
				}else if(obj.type=="support"){
					$('#lead2_name').text("Ticket*");
				}
				$('#event_name').val(obj.event_name);
				$("#createdBy").val(obj.created_by);
				$("#personId").val(obj.person_id);
				$("#mangName").val(obj.person_name);
				$("#type").val(obj.type);
				$("#conntype").val(obj.conntype);
				var data = {};
				data.remmem = obj.reminder_members;
				$.ajax({ 	
					type : "POST",
					url : "<?php echo site_url('sales_mytaskController/get_editable_emails'); ?>",	
					data: JSON.stringify(data),     
					dataType : 'json',
					cache : false,
					success : function(data){
						email_data=data;
						if(error_handler(data)){
								return;
								}
						var jsonData = data.allEmailID; 
						$("#complete_email_list_edit").empty();
						reminder_members = [];
						for(i= 0; i<data.sendSavedID.length; i++){
							reminder_members.push(data.sendSavedID[i].user_id);
							$("#complete_email_list_edit").append("<li id="+ data.sendSavedID[i].user_id+">"+ data.sendSavedID[i].user_name+" <a style='color:red;' class='glyphicon glyphicon-remove-sign' onclick='del(\""+data.sendSavedID[i].user_id+"\", reminder_members)'></a></li>");
							$('#complete_email_list_edit').closest("div").find("span.error-alert").text("");
							$('#complete_email_list_edit').val("");

						}
						var dataSource = new Bloodhound({
							datumTokenizer: Bloodhound.tokenizers.obj.whitespace('user_name', 'email','department_name','designation'),
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							local: jsonData
						});
						dataSource.initialize();

						$('#complete_email_search').typeahead({
							minLength: 0,
							highlight: true
						},{
							name: 'email',
							display: function(item) {
								return item.user_name + ' ( ' + item.department_name+ ' ) ( ' + item.designation +' )'
							},
							source: dataSource.ttAdapter(),
							suggestion: function(data) {
								return '<b>' + data.user_name + '–' + data.user_id + '</b>' 
							}

						});

						$('#complete_email_search').on('typeahead:selected', function (e, datum) {
							var match=1;
							$("#complete_email_list_edit li").each(function(){
								if($.trim($(this).attr('id'))== datum.user_id){
									match=0;
								}
							});
							if(match==0){
								$('#complete_email_search').val("");
								return;
							}
							if ($("#complete_email_list_edit li").length <= 12) {
								reminder_members.push(datum.user_id);								
								$("#complete_email_list_edit").append("<li id="+ datum.user_id+">"+ datum.user_name+" <a style='color:red;' class='glyphicon glyphicon-remove-sign' onclick='del(\""+datum.user_id+"\", reminder_members)'></a></li>");
									$('#complete_email_search').closest("div").find("span.error-alert").text("");
									$('#complete_email_search').val("");	
								
							} else {
								alert("Can't add more than 12 Users");
								$('#complete_email_search').closest("div").find("span.error-alert").text("Can't add more than 12 Users");
								return;
							}
							if(email_data.sendSavedID.length>=0){
								$("#completed_save_bnt").removeAttr("disabled");	
							}else{
								$("#completed_save_bnt").attr("disabled","disabled");
							}
						});
					}
				});

				$("#event_name2").text(obj.event_name);		
				$('#reschd1').val(obj.lead_reminder_id);
				$("#complete_act2").val(obj.lookup_value);
				$("#act22").val(obj.lookup_id);
				$("#complete_contact2").val(obj.employeename);
				$("#complete_lead2").val(obj.leadname);
				$("#event_name2").val(obj.event_name);				
				$("#contact3").val(obj.employeeid);
				$("#lead3").val(obj.leadid);
				$("#phone1").val(obj.employeephone1);
				$("#complete_start_date2").val(moment(obj.meeting_start).format('lll'));
				$("#start_time2").val(obj.rem_time);
				$("#complete_actve_duration2").val(obj.duration);
				$("#reminder_time2").val(obj.addremtime);
				$("#complete_note2").val(window.atob(obj.remarks));
				
				var oldobj={};
					oldobj.startdate= moment(obj.meeting_start).format('lll');
					oldobj.duration= obj.duration;
					oldobj.alertBefore= obj.addremtime;
					oldobj.note= window.atob(obj.remarks);
					
				var newobj={};
					newobj.startdate= moment($.trim($("#complete_start_date2").val()), 'lll').format('lll');
					newobj.duration= moment($.trim($("#complete_actve_duration2").val()), 'H [Hrs] m [mins]').format('HH:mm');
					newobj.alertBefore= $.trim($('#reminder_time2').val());
					newobj.note= $.trim($('#complete_note2').val());
				
				time_overlap = false;
				$("#complete_actve_duration2").on("dp.change", function (e) {			
					newobj.duration= moment($.trim($("#complete_actve_duration2").val()), 'H [Hrs] m [mins]').format('HH:mm');
					compare1(newobj,oldobj)
				});
				
				$("#reschedule_startpicker").on("dp.change", function (e) {	
					newobj.startdate= moment($.trim($("#complete_start_date2").val()), 'lll').format('lll');
					compare1(newobj,oldobj)
				})

				$('#reminder_time2').change(function(){
					newobj.alertBefore= $.trim($('#reminder_time2').val());
					compare1(newobj,oldobj)
				});
				$('#complete_note2').keyup(function(){
					newobj.note= $.trim($('#complete_note2').val());
					compare1(newobj,oldobj)
				});
			}
		});
		
	}
	/* conpare function */
	function compare1(newObj,oldObj){
		$(".error-alert").text("")
		var changeChk = 0;
		for (var k in oldObj) {
			if (oldObj[k] != newObj[k]){				
				changeChk = 1;
			}			
		}
		if(changeChk == 1){
			$("#completed_save_bnt").removeAttr("disabled");
		}else{
			$("#completed_save_bnt").attr("disabled","disabled");
		}
	}
	function enable(){
		$("#completed_starts").prop("disabled", false);
	}
	/* ----------------------------------------------------------------
	*************************** completed Submitting completed data **********************************
	-----------------------------------------------------------------------	*/
	function completed_popup(showAddModel)	{
		var addEventObj={};
		
		if($.trim($("#complete_start_date2").val()) == ""){
			$("#complete_start_date2").closest("#completed_startpicker").siblings(".error-alert").text("Start Date is required");
			return;	
		} else 	{
			date = moment($.trim($("#complete_start_date2").val()), 'lll').format('YYYY-MM-DD HH:mm:ss');
			$("#complete_start_date2").closest("#completed_startpicker").siblings(".error-alert").text("");
		}
		
		if($.trim($("#complete_actve_duration2").val()) == ""){
			$("#complete_actve_duration2").closest("div").find(".error-alert").text("Specify activity duration");
			return;	
		}else {
			var duration1 = $.trim($("#complete_actve_duration2").val()).split(":");
			if((duration1[0] == "0" && duration1[1] == "0") || (duration1[0] == "00" && duration1[1] == "00")){
				$("#complete_actve_duration2").closest("div").find(".error-alert").text("Select valid duration time.");
				return;
			}else{
				duration = moment($.trim($("#complete_actve_duration2").val()), 'H [Hrs] m [mins] ss [sec]').format('HH:mm:ss');
				$("#complete_actve_duration2").closest("div").find(".error-alert").text("");
			}
		}
		
		if($.trim($("#complete_note2").val())==""){
			$("#complete_note2").focus();
			$("#complete_note2").closest("div").find("span").text("Remarks is required.");
			return;	
		}else if(!comment_validation($.trim($("#complete_note2").val()))){
			$("#complete_note2").closest("div").find("span").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
			$("#complete_note2").focus();
			return;
		}else{
			$("#complete_note2").closest("div").find("span").text("");
		}
		var email_arr=[],reminder_members;
		$("#complete_email_list_edit li").each(function(){
			email_arr.push($(this).attr("id"));
		});
		addEventObj.reminder_members =	email_arr;
		addEventObj.lead_reminder_id =	$.trim($('#reschd1').val());
		addEventObj.event_name = 		$.trim($("#event_name").val());
		addEventObj.conntype = 			$.trim($("#conntype").val());
		addEventObj.act2 =				$.trim($('#act22').val());
		addEventObj.lead3 =				$('#lead3').val();
		addEventObj.contact3 =			$.trim($('#contact3').val());
		addEventObj.start_date2 =		$.trim(date);
		addEventObj.actve_duration2 =	$.trim(duration);
		addEventObj.note2 =				$.trim($('#complete_note2').val()).replace(/\n|\r/g, " ");
		addEventObj.phone1 =			$.trim($('#phone1').val());
		addEventObj.phone1 =			getMobileNumber($('#phone1').val());
		addEventObj.reminder_time =		$.trim($('#reminder_time2').val());
		addEventObj.created_by =		$.trim($('#createdBy').val());
		addEventObj.person_id = 		$.trim($('#personId').val());
		addEventObj.type = 				$.trim($("#type").val());
		addEventObj.cmp_lead_name =					$("#lead_hidden").val();
		addEventObj.cmp_member_name =					$("#member_hidden").val();
		addEventObj.cmp_contact_name =					$("#contact_hidden").val();
		addEventObj.cmp_activity_name =					$("#activity_hidden").val();
		addEventObj.cmp_member_id =					$("#member_id_hidden").val();
		addEventObj.cmp_meeting_start =					$("#start_hidden").val();
		/* //ajax call for rescdule activity */
	
		/* if(time_overlap == false){
			overlap(addEventObj.start_date2,addEventObj.actve_duration2);
			return;
		} */
		loaderShow();
		$.ajax({ 
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/completeTaskManually'); ?>",
			dataType : 'json',
			data : JSON.stringify(addEventObj),					
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
				if (showAddModel =='true') {
					cancel();
					pageload(1);
				} else {
					pageload();
					cancel();
				}
			}
		}); 
	}	
	
	function processComplete(showAddModel){
		var addEventObj ={};
		if($("#cmp_note").val()==""){
			$("#cmp_note").focus();
			$("#cmp_note").closest("div").find("span").text("Note is required.");
			return;	
		}else if(!comment_validation($.trim($("#cmp_note").val()))){
			$("#cmp_note").closest("div").find("span").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
			$("#cmp_note").focus();
			return;
		}else{
			$("#cmp_note").closest("div").find("span").text("");
		}
		
		var cmp_end_time = moment($.trim($("#cmp_duration").val()), 'H [Hrs] m [mins]').format('HH:mm');
		if($("#confirm_process1").is(":checked")){
			if($.trim($("#completed_starts").val()) == ""){
				$("#completed_starts").siblings(".error-alert").text("Start date is required.");
				return;	
			} else 	{
				$("#completed_starts").siblings(".error-alert").text("");
			}
			if($.trim($("#cmp_duration").val()) == ""){
				$("#cmp_duration").closest("div").find(".error-alert").text("Specify estimated activity duration.");
				return;	
			} else{
				var duration1 = $.trim($("#cmp_duration").val()).split(":");
				if(duration1[0] == "0" && duration1[1] == "0"){
					$("#cmp_duration").closest("div").find(".error-alert").text("Select valid duration time.");
					return;
				}else{
					cmp_end_time = moment($.trim($("#cmp_duration").val()), 'H [Hrs] m [mins] ss [sec]').format('HH:mm:ss');
					$("#cmp_duration").closest("#cmp_duration").siblings(".error-alert").text("");
				}
				
			}			
			if(rating1 == -1){
				$("#rating_error").text("Raiting is required.");
				return;	
			}else if(rating1 == 0 || rating1 > 0){
				$("#rating_error").text("");
			}
		}
		
		addEventObj.completed_start_date = 		moment().format('YYYY-MM-DD');
		addEventObj.completed_start_time = 		moment().format('HH:mm:ss');
		addEventObj.cmp_end_time = 				cmp_end_time;
		addEventObj.event_title = 				$.trim($("#event_name").val());
		addEventObj.event_rating = 				rating1;				
		addEventObj.event_start_date = 			$.trim($("#cmp_start_date").val());
		addEventObj.cmp_start_time = 			$.trim($("#cmp_start_time").val());
		addEventObj.personId = 					$.trim($("#personId").val());			/* -- event start time */
		addEventObj.type = 						$.trim($("#completed_type_hidden").val());		/* -- event start time */
		addEventObj.camp_note = 				$.trim($("#cmp_note").val()).replace(/\n|\r/g, " ");
		addEventObj.lead_reminder_id =			$('#process').val();
		addEventObj.cmp_activity =				$("#cmp_activity").val();
		addEventObj.cmp_contact =				$("#cmp_contact_hidden").val();
		addEventObj.cmp_phone =					getMobileNumber($('#phone').val());
		addEventObj.cmp_lead =					$('#leadCustId').val();
		/* //AJAX Call---------------------- */
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/update_mytask'); ?>",
			dataType : 'json',
			data : JSON.stringify(addEventObj),					
			cache : false,
			success : function(data){
					if(error_handler(data)){
						return;
					}
					if (data == false) {
						alert("There was an error adding the contact");
					}else{
						if (showAddModel =='true') {
							cancel();
							scheduled_task_list(1);
						}else{
							cancel();
							scheduled_task_list();
						}
					}
  			 }
		}); 
	}
	/* -------------------------------------------------------------------
	**************************** making array of phone number *******************************************
	------------------------------------------------------------------ */
	
	function getMobileNumber(rowdata){
		var num = JSON.parse(rowdata);
		var obj={}
		var contactArray =[];
		for (var key in num){
			if(num[key].length > 0){
				for(i=0; i<num[key].length; i++){
					if(num[key][i].trim() != ""){
						if(key == "home"){
							contactArray.push(num[key][i].trim())
						}
						if(key == "main"){
							contactArray.push(num[key][i].trim())
						}
						if(key == "mobile"){
							contactArray.push(num[key][i].trim())
						}
						if(key == "phone"){
							contactArray.push(num[key][i].trim())
						}
						if(key == "leadphone"){
							contactArray.push(num[key][i].trim())
						}
						if(key == "work"){
							contactArray.push(num[key][i].trim())
						}
						/* ----------------- */
						if(key == "personal"){
							contactArray.push(num[key][i].trim())
						}
					}
					
				}
				obj[key]= contactArray
				contactArray=[];
			}
		}
		return JSON.stringify(obj);
	}
	
	/* ----------------------------------------------------------------------
	**************************** Reschedule Popup Show with Value *****************************************
	---------------------------------------------------------------------- */
	function reschedulepopup(key){
		//previously row data was sent useing function paremeter
		//take obj from global data -- 16-11-2018
		var obj = {}
		activeData.forEach(function(rowData, i){
			if(key == rowData.row_id + rowData.table_name){
				obj = rowData;
			}
		})
		time_overlap = false;
		$("#start_hidden").val(obj.meeting_start);
		$("#member_id_hidden").val(obj.user_id);
		$("#lead_hidden").val(obj.leadname);
		$("#member_hidden").val(obj.person_name);
		$("#contact_hidden").val(obj.employeename);
		$("#activity_hidden").val(obj.lookup_value);
		$('#reschedule').modal('show');
		if(obj.type=="lead"){			
			$('#lead2_name').text("Lead*");
		}else if(obj.type=="customer"){
			$('#lead2_name').text("Customer*");
		}else if(obj.type=="opportunity"){
			$('#lead2_name').text("Opportunity*");
		}else if(obj.type=="internal"){
			$('#lead2_name').text("Internal*");
		}else if(obj.type=="support"){
			$('#lead2_name').text("Ticket*");
		}
		$('#event_name').val(obj.event_name);
		$("#createdBy").val(obj.created_by);
		$("#personId").val(obj.person_id);
		$("#mangName").val(obj.person_name);
		$("#type").val(obj.type);
		$("#conntype").val(obj.conntype);
		var data = {};
		data.remmem = obj.reminder_members;
		$.ajax({ 	/* ajax call for edit pending task of lead_activity */
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/get_editable_emails'); ?>",	
			data: JSON.stringify(data),     
			dataType : 'json',
			cache : false,
			success : function(data){				
				email_data=data;
				if(error_handler(data)){
					return;
				}
				var jsonData = data.allEmailID; 
				$("#email_list_edit").empty();
				reminder_members = [];
				for(i= 0; i<data.sendSavedID.length; i++){
					reminder_members.push(data.sendSavedID[i].user_id);
					$("#email_list_edit").append("<li id="+ data.sendSavedID[i].user_id+"><span>"+ data.sendSavedID[i].user_name+" </span><a style='color:red;' class='glyphicon glyphicon-remove-sign' onclick='del(\""+data.sendSavedID[i].user_id+"\", reminder_members)'></a></li>");
					$('#email_list_edit').closest("div").find("span.error-alert").text("");
					$('#email_list_edit').val("");

				}
				var dataSource = new Bloodhound({
					datumTokenizer: Bloodhound.tokenizers.obj.whitespace('user_name', 'email','department_name','designation'),
					queryTokenizer: Bloodhound.tokenizers.whitespace,
					local: jsonData
				});
				dataSource.initialize();

				$('#email_search').typeahead({
					minLength: 0,
					highlight: true
				},{
					name: 'email',
					display: function(item) {
						return item.user_name + ' ( ' + item.department_name+ ' ) ( ' + item.designation +' )'
					},
					source: dataSource.ttAdapter(),
					suggestion: function(data) {
						return '<b>' + data.user_name + '–' + data.user_id + '</b>' 
					}

				});

				$('#email_search').on('typeahead:selected', function (e, datum) {
					var match=1;
					$("#email_list_edit li").each(function(){
						if($.trim($(this).attr('id'))== datum.user_id){
							match=0;
						}
					})
					if(match==0){
						$('#email_search').val("");
						return;
					}
					if($("#email_list_edit li").length <= 12){
						reminder_members.push(datum.user_id);
						$("#email_list_edit").append("<li id="+ datum.user_id+"><span>"+ datum.user_name+" </span><a style='color:red;' class='glyphicon glyphicon-remove-sign' onclick='del(\""+datum.user_id+"\", reminder_members)'></a></li>");
						$("#email_search").closest("div").find("span.error-alert").text("");
						$('#email_search').val("");
					}else{
						$("#email_search").closest("div").find("span.error-alert").text("Can't add more than 12 Users");
						return;
					}
					if(email_data.sendSavedID.length>=0){
						$("#reschedule_save_bnt").removeAttr("disabled");	
					}else{
						$("#reschedule_save_bnt").attr("disabled","disabled");
					}
					/* for(i=0;i<email_data.sendSavedID.length;i++){
						for(j=0;j<reminder_members.length;j++){
							if(reminder_members[j]==email_data.sendSavedID[i]){
								$("#reschedule_save_bnt").attr("disabled","disabled");
							}else{
								$("#reschedule_save_bnt").removeAttr("disabled");								
							}							
						}
					} */
				});
			}
		});

			
		$("#event_name2").text(obj.event_name);		
		$('#reschd1').val(obj.lead_reminder_id);
		$("#act2").val(obj.lookup_value);
		$("#act22").val(obj.lookup_id);
		$("#contact2").val(obj.employeename);
		$("#lead2").val(obj.leadname);
		$("#contact3").val(obj.employeeid);
		$("#lead3").val(obj.leadid);
		$("#phone1").val(obj.employeephone1);
		$("#start_date2").val(moment(obj.meeting_start).format('lll'));
		$("#start_time2").val(obj.rem_time);
		$("#actve_duration2").val(obj.duration);
		$("#reminder_time2").val(obj.addremtime);
		$("#note2").val(window.atob(obj.remarks));

		var oldobj={};
			oldobj.startdate= moment(obj.meeting_start).format('lll');
			oldobj.duration= obj.duration;
			oldobj.alertBefore= obj.addremtime;
			oldobj.note= obj.remarks;
			
		var newobj={};
			newobj.startdate= moment($.trim($("#start_date2").val()), 'lll').format('lll');
			newobj.duration= moment($.trim($("#actve_duration2").val()), 'H [Hrs] m [mins]').format('HH:mm:ss');
			newobj.alertBefore= $.trim($('#reminder_time2').val());
			newobj.note= $.trim($('#note2').val());
			
			/* newobj.startdate="";
			newobj.duration= "";
			newobj.alertBefore= "";
			newobj.note= ""; */
		
		
		$("#actve_duration2").on("dp.change", function (e) {
			newobj.duration= moment($.trim($("#actve_duration2").val()), 'H [Hrs] m [mins]').format('HH:mm:ss');
			compare(newobj,oldobj)
		});
		
		$("#reschedule_startpicker").on("dp.change", function (e) {	
			newobj.startdate= moment($.trim($("#start_date2").val()), 'lll').format('lll');
			compare(newobj,oldobj)
		})

		$('#reminder_time2').change(function(){
			newobj.alertBefore= $.trim($('#reminder_time2').val());
			compare(newobj,oldobj)
		});
		$('#note2').keyup(function(){
			newobj.note= $.trim($('#note2').val());
			compare(newobj,oldobj)
		});
		

	}
	/* conpare function */
	function compare(newObj,oldObj){
		$(".error-alert").text("")
		var changeChk = 0;
		for (var k in oldObj) {
			if (oldObj[k] != newObj[k]){				
				changeChk = 1;
			}			
		}
		if(changeChk == 1){
			$("#reschedule_save_bnt").removeAttr("disabled");
		}else{
			$("#reschedule_save_bnt").attr("disabled","disabled");
		}
	}
	
	/* ----------------Next-Previous button for view activity only visible for open section -----Starts---------------------- */
		
		var getAllActivites = [];
		function display(array , j, state){
			var array1=[];
			if(state == "open" || state == "closed" || state == ""){
				var nw = '';
				var old = '<input class="btn pull-left" type="button"  onclick="older()" value="Previous"/> ';
				if(j == (array.length - 1)){
					old ='';
				}			
				if(j > 0){
					nw = '<input class="btn pull-right" type="button" onclick="newer()" value="Next" />';
				}
			}else{
				var nw = '';
				var old = '';
				array1.push(array);
				array = array1
			}
			var phone_num = JSON.parse(array[j].employeephone1), phone_row = "";
			if(array[j].employeephone1!= null){
				if(phone_num.hasOwnProperty('phone')){
					/* array[j].employeephone1 - value is coming like json obj*/
					for(i = 0; i < phone_num.phone.length; i++){
						if((i + 1) ==  phone_num.phone.length){
							phone_row += phone_num.phone[i];
							if(phone_num.phone[i] == ""){
								phone_num.phone[i] = phone_num.phone[i-1].split(",").join(" ");
								phone_row = phone_num.phone[i];
							}
						}else{
							phone_row += phone_num.phone[i] + ", ";
						}
					}
				}else{
					/* array[j].employeephone1 - value is coming like array */
					phone_row = array[j].employeephone1.replace(/["]/g,'').replace(/\[([^\]]+)\]/g, '$1');
					a = phone_row.split(',');
					b=[];
					for(i = 0; i < a.length; i++){
						if(a[i].trim() != ""){
							b.push(a[i].trim())
						}
						phone_row = b.join(" ,")
					}
				}
			}else{
				phone_row = "";
			}
			if(array[j].employeename!=null){
				array[j].employeename = array[j].employeename;
			}else{
				array[j].employeename = "";
			}
			var star_time = moment(array[j].meeting_start);
			var end_time = moment(array[j].meeting_end);		
			var cal_duration = moment.duration(moment(end_time, 'YYYY-MM-DD HH:mm:ss').diff(moment(star_time, 'YYYY-MM-DD HH:mm:ss'))).asMilliseconds("");
			var duration = moment.utc(cal_duration).format("HH:mm:ss");
			
			if(array[j].type == 'support'){
				array[j].type = 'Ticket';
			}
			array[j].type = capitalizeFirstLetter(array[j].type);
			
			$("#reschedule1 .newView").html('');
			if(array[j].type == 'mobileDialler' || ( $.trim(array[j].leadid) == "" || $.trim(array[j].leadid) == 0 )){
				array[j].leadname = 'Not in App';
				phone_row = array[j].employeenumber;
			}
			
			if($.trim(array[j].employeephone1) == "" ){
				phone_row = array[j].employeenumber;
			}
			var rating = '';
			if(array[j].rating){
				rating = array[j].rating;
			}else{
				raiting = '';
			}
			var statusName = '';
			if(array[j].status == 'pending'){
				statusName = '<b class="text-capitalize" style="color:red;">Pending</b>';
			}else if(array[j].status == 'reschedule'){
				statusName = '<b class="text-capitalize" style="color:orange;">Rescheduled</b>';
			}else if(array[j].status == 'Completed'){
				statusName = '<b class="text-capitalize" style="color:green;">Completed</b>';
			}else if(array[j].status == 'scheduled'){
				statusName = '<b class="text-capitalize" style="color:blue;">Scheduled</b>';
			}else if(array[j].status == 'cancel'){
				statusName = '<b class="text-capitalize" style="color:purple;">Cancelled</b>';
			}
			console.log(array[j])
			html =	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Activity:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+array[j].lookup_value+'</label>'+													
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Activity Owner:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+array[j].person_name+'</label>'+													
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>'+array[j].type+':</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+array[j].leadname+'</label>'+																		
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			if(array[j].type != 'mobileDialler' && (array[j].leadid.trim() != "" && array[j].leadid.trim() != 0 )){
				var contact = array[j].employeename;
				//Addition 14-11-2018
				if((array[j].lookup_id == "EM594ce66d07b9f87") && capitalizeFirstLetter(array[j].type) == "Unassociated" && (array[j].employeename == null || $.trim(array[j].employeename)) == ""){
					//outgoing mail senction
					contact = array[j].mail_to.split(',').join(', ');
				}else if(array[j].lookup_id == "EM594ce66d07b9f83" && capitalizeFirstLetter(array[j].type) == "Unassociated" && (array[j].employeename == null || $.trim(array[j].employeename)) == ""){
					//incoming mail section
					contact = array[j].mail_form;
				}
				html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Contact:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+contact+'</label>'+
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';	
			}
			
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Phone Number:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+phone_row+'</label>'+																		
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Start:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+moment(array[j].meeting_start).format('DD-MM-YYYY HH:mm:ss')+'</label>'+
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Duration:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+duration+'</label>'+
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Closed By:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+(array[j].status == 'pending' ? '' : array[j].created_by)+'</label>'+
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Scheduled By:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+array[j].created_by+'</label>'+
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Status:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+statusName+'</label>'+
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Rating:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+rating+'</label>'+
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			var url_path = "<?php echo base_url(); ?>uploads/";
			if(array[j].conntype == 'CALL594ce66d07b45' || array[j].conntype == 'CALL594ce66d07b46' || array[j].conntype == 'ME594ce66d07b9fd4'){
				if(array[j].hasOwnProperty('path') && array[j].path != null){
					html += '<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Content:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label><audio controls controlsList="nodownload"><source src='+url_path+array[j].path+'>'+
							'Your browser does not support the audio element.'+
							'</audio></label>'+
						'</div>'+
						'</div>';
				}else{
					html += '<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Content:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label></label>'+
						'</div>'+
						'</div>';
				}					
			}else{
				if(array[j].hasOwnProperty('path') && array[j].path != null){
					html += '<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Content:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+array[j].path == null ? "" : array[j].path +'</label>'+
						'</div>'+
					'</div>';
				}else{
					html += '<div class="row">'+
					'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'<div class="col-md-3 col-sm-3 col-xs-3">'+										
						'<label><b>Content:</b></label>'+
					'</div>'+
					'<div class="col-md-5 col-sm-5 col-xs-5">'+
						'<label></label>'+
					'</div>'+
					'</div>';
				}
			}
			if(array[j].message_id != ''){
				if(array[j].message_id != null){
					html += '<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Content:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label><a href="#" id="'+array[j].message_id+'" onclick="view_details(this.id)">Click here to see details</a></label>'+
						'</div>'+
						'</div>';
				}
			}
			if(array[j].cancel_remarks != "" && array[j].hasOwnProperty('cancel_remarks')){
				html += '<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Cancellation Remarks:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+array[j].cancel_remarks+'</label>'+
						'</div>'+
					'</div>';
			}
			html += '<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Activity Note:</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+array[j].remarks+'</label>'+
						'</div>'+
					'</div>';
			
					
			html += 	'<div class="row">'+
							'<center>'+
								old+
								nw +
							'</center>'+
						'</div>';
			$("#reschedule1 .newView").append(html);
		}
		function older(){
			var value = parseInt(document.getElementById('clickCounterNumber').value, 10);
			value = isNaN(value) ? 0 : value;
			value++;
			document.getElementById('clickCounterNumber').value = value;
			display(getAllActivites , value, "");
		}
		function newer(){
			var value = parseInt(document.getElementById('clickCounterNumber').value, 10);
			value = isNaN(value) ? 0 : value;
			value--;
			document.getElementById('clickCounterNumber').value = value;
			display(getAllActivites , value, "");
		}
	/* ----------------------------Ends Here--------------- */
	/*-----------------------------message details-----------*/
	 function show_attachment(data){
		 $("#attachment_box").modal("show");
		 console.log(data)
			console.log(doc_data.attachments[0].mail_attachment_path)
			for(i=0;i<doc_data.attachments.length;i++){
				if(data == doc_data.attachments[i].mail_attachment_path){				
					$(".file_attach").css('height', '376px');
					$('.file_attach').css({'background-image':'url(<?php echo base_url(); ?>uploads/' + doc_data.attachments[i].mail_attachment_path + ')','background-repeat':' no-repeat','background-size': 'contain'});
				}
			}
	 }
	function view_details(id){
		$("#messageDetails").modal("show");
		var obj = {};
		obj.msg_id = id;
		$.ajax({
				type : "POST",
				url : "<?php echo site_url('manager_mytaskController/fetchMailActivities'); ?>",
				dataType : 'json',
				data : JSON.stringify(obj),					
				cache : false,
				success : function(data){
					console.log(data)
					var row = '';
					$("#sub_date").html("").append(data.email[0].mail_date);
					$("#from_name").html("").append(data.email[0].mail_from);
					$("#to_name").html("").append(data.email[0].mail_to);
					$("#cc_name").html("").append(data.email[0].mail_cc);
					$("#bcc_name").html("").append(data.email[0].mail_bcc);
					$("#sub_name").html("").append(data.email[0].mail_subject);
					$("#mail_body").html("").append(data.email[0].mail_body);
					if(data.hasOwnProperty('attachments')){
						$('.attach_file').show();
					 row += "<ul class='attach_class'>";
						for(i=0;i<data.attachments.length;i++){
						  var path = data.attachments[i].mail_attachment_path;
						 var file_name = path.split('/').pop();
						 var format = file_name.split('.').pop();
						 var file_name = file_name.split('.')[0];
						 doc_data = data;
						 console.log(file_name)
							if(format == 'jpg' || format == 'jpeg' || format == 'gif' || format == 'bmp' || format == 'png'){
								row +='<li id="'+path+'" onclick="show_attachment(this.id)">'+data.attachments[i].mail_attachment_filename+'</li>';
							}
							if(format == 'doc' || format == 'docx' || format == 'pdf' || format == 'rtf' || format == 'txt' || format == 'xls' || format == 'csv'){
								if(format == 'pdf'){
									row +='<li><a href="https://lconnectt.in/pdfviewer/viewer.html?file=<?php echo base_url(); ?>uploads/' + data.attachments[i].mail_attachment_path +'" target="_blank">'+data.attachments[i].mail_attachment_filename+'</a></li>';
								}else{
									row +='<li>'+data.attachments[i].mail_attachment_filename+' (Cannot open this kind of files)</li>';
								}
							}
						}
						row +='</ul>';
					}else{
					  $('.attach_file').hide();
					}
					$("#attach_file").empty();
					$("#attach_file").append(row);
					$("#mail_body").find('style').remove();
					$("#mail_body").find('a').attr('href', 'javascript:void(0)').css("color", "black");
				},
				error: function(data){
					loaderHide();
					network_err_alert(data);
				}
			});
	}
	function closeMsgDetails(){
		$("#messageDetails").modal("hide");
	}
	function attachment_match(){
		$("#attachment_box").modal("hide");
	}
	/* ----------------------------Ends Here--------------- */
	function reschedulepopup1(key, state)	{
		//previously row data was sent useing function paremeter
		//take obj from global data -- 16-11-2018
		var obj = {}
		activeData.forEach(function(rowData, i){
			if(key == rowData.row_id + rowData.table_name){
				obj = rowData;
			}
		})
		
		$("#event_name_view").text(obj.event_name);	
		if(state == 'open' || state == 'closed'){
			$("#reschedule1 .newView").html('');
			var input={};
			input.leadid = obj.leadid;
			input.type = obj.type;
			input.date = obj.meeting_start;
			
			if(obj.type == "unassociated"){
				input.leadempid = obj.message_id;
			}else{
				input.leadempid = obj.leadempid;
			}
			if(typeof(input.leadid) == "undefined"){
				console.log("event.leadid not available. Please check with Developers");
				return;
			}
			loaderShow();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('sales_mytaskController/getAllActivitesOfLeadCustSup'); ?>",
				dataType : 'json',
				data : JSON.stringify(input),					
				cache : false,
				success : function(data){
					loaderHide();
					getAllActivites = data;
					getAllActivites.forEach(function (elm, i) {
						var selectedRowIndex = $.trim(obj.table_name)+$.trim(obj.row_id);
						var rowIndex = $.trim(elm.table_name)+$.trim(elm.row_id);
						if(selectedRowIndex == rowIndex){
							display(getAllActivites , i , state);
							document.getElementById('clickCounterNumber').value = i;
							$('#reschedule1').modal('show');
						}
					});
				},
				error: function(data){
					loaderHide();
					network_err_alert(data);
				}
			});
		}else{
			obj.remarks = window.atob(obj.remarks);
			loaderHide();
			display(obj , 0 , state);
		}
		
	}

	
	/* ----------------------------------------------------------------
	*************************** reschedule Submitting reschedule data **********************************
	-----------------------------------------------------------------------	*/
	function reschedule_popup()	{
		var addEventObj={};
		
		if($.trim($("#start_date2").val()) == ""){
			$("#start_date2").closest("#reschedule_startpicker").siblings(".error-alert").text("Start Date is required");
			return;	
		} else 	{
			date = moment($.trim($("#start_date2").val()), 'lll').format('YYYY-MM-DD HH:mm:ss');
			$("#start_date2").closest("#reschedule_startpicker").siblings(".error-alert").text("");
		}
		
		if($.trim($("#actve_duration2").val()) == ""){
			$("#actve_duration2").closest("div").find(".error-alert").text("Specify activity duration");
			return;	
		}else {
			var duration1 = $.trim($("#actve_duration2").val()).split(":");
			if((duration1[0] == "0" && duration1[1] == "0") || (duration1[0] == "00" && duration1[1] == "00")){
				$("#actve_duration2").closest("div").find(".error-alert").text("Select valid duration time.");
				return;
			}else{
				duration = moment($.trim($("#actve_duration2").val()), 'H [Hrs] m [mins] ss [sec]').format('HH:mm:ss');
				$("#actve_duration2").closest("div").find(".error-alert").text("");
			}
		}
		
		if($.trim($('#reminder_time2').val())==""){
			$("#reminder_time2").focus();
			$("#reminder_time2").closest("div").find(".error-alert").text("Select alert before time.");
			return;	
		}else{
			$("#reminder_time2").closest("div").find(".error-alert").text("");
		}
		
		if($.trim($("#note2").val())==""){
			$("#note2").focus();
			$("#note2").closest("div").find("span").text("Remarks is required.");
			return;	
		}else if(!comment_validation($.trim($("#note2").val()))){
			$("#note2").closest("div").find("span").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
			$("#note2").focus();
			return;
		}else{
			$("#note2").closest("div").find("span").text("");
		}
		
		var email_arr=[],reminder_members, email_name=[];
		$("#email_list_edit li").each(function(){
			email_arr.push($(this).attr("id"));
			email_name.push($.trim($(this).find("span").text()));
		});
		if($("#email_search").val() != ""){
			var name = $("#email_search").val(), flag = 0;
			var name1 = name.split("(");
			if(email_name.length > 0){
				for(i = 0; i < email_name.length; i++){
					if(email_name[i] == $.trim(name1[0])){
						flag = 1;
					}
				}
				if(flag == 1){
					$('#email_search').closest("div").find("span.error-alert").text("");
				}else{
					$('#email_search').closest("div").find("span.error-alert").text("Please enter the email ID of any member in your organization who uses L Connectt.");
					return;
				}
			}else if(name != ''){
				$('#email_search').closest("div").find("span.error-alert").text("Please enter the email ID of any member in your organization who uses L Connectt.");
				return;
			}			
		}
		addEventObj.cmp_lead_name =					$("#lead_hidden").val();
		addEventObj.cmp_member_name =					$("#member_hidden").val();
		addEventObj.cmp_contact_name =					$("#contact_hidden").val();
		addEventObj.cmp_activity_name =					$("#activity_hidden").val();
		addEventObj.cmp_member_id =					$("#member_id_hidden").val();
		addEventObj.cmp_meeting_start =					$("#start_hidden").val();
		addEventObj.reminder_members =	email_arr;
		addEventObj.lead_reminder_id =	$.trim($('#reschd1').val());
		addEventObj.event_name = 		$.trim($("#event_name").val());
		addEventObj.conntype = 			$.trim($("#conntype").val());
		addEventObj.act2 =				$.trim($('#act22').val());
		addEventObj.lead3 =				$('#lead3').val();
		addEventObj.contact3 =			$.trim($('#contact3').val());
		addEventObj.start_date2 =		$.trim(date);
		addEventObj.actve_duration2 =	$.trim(duration);
		addEventObj.note2 =				$.trim($('#note2').val()).replace(/\n|\r/g, " ");
		addEventObj.phone1 =			$.trim($('#phone1').val());
		addEventObj.phone1 =			getMobileNumber($('#phone1').val());
		addEventObj.reminder_time =		$.trim($('#reminder_time2').val());
		addEventObj.created_by =		$.trim($('#createdBy').val());
		addEventObj.person_id = 		$.trim($('#personId').val());
		addEventObj.type = 				$.trim($("#type").val());
		if(time_overlap == false){
			overlap(addEventObj.start_date2,addEventObj.actve_duration2);
			return;
		}
		loaderShow();
		$.ajax({ 
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/update_reschedule'); ?>",
			dataType : 'json',
			data : JSON.stringify(addEventObj),					
			cache : false,
			success : function(data){
				if(error_handler(data)){
						return;
						}
				
				if (data==true) {
					scheduled_task_list();
					cancel();									
				} else {
					loaderHide();
					alert("There was an error adding the contact");
				}
			}
		});
	}	

	/* ----------------------------------------------------------------------
	**************************** lead search typeahed *****************************************
	---------------------------------------------------------------------- */
	function typeChanged() {
		var typeSelected = $("#type").val();
		if (typeSelected == 'lead') {
			/* hide following fields - contact, number, lead and opportunity	 */		
			$("#leadGroup, #contactGroup, #numberGroup, #oppGroup").hide();
			/* show lead text field, contact text field, number text field */
			$("#leadGroup, #contactGroup, #numberGroup").show();
		}
		else if (typeSelected == 'opp') {
			/* hide following fields - contact, number, lead and opportunity			 */
			$("#leadGroup, #contactGroup, #numberGroup, #oppGroup").hide();
			/* show oppo field, contact text field, number text field */
			$("#contactGroup, #numberGroup, #oppGroup").show();
		}
		else if (typeSelected == 'internal') {
			/* hide following fields - contact, number, lead and opportunity */
			$("#leadGroup, #contactGroup, #numberGroup, #oppGroup").hide();
		}
		$("#lead, contact, number, opportunity").val("");
	}
	/* -------------------------------------------------------------------------------------------------------
	***************************** delet function --not using *************************************
	----------------------------------------------------------------------------------------------------------- */
	function del(id, array){
		$("#reschedule_save_bnt").removeAttr("disabled");
		$("#"+id).remove();
		var index = array.indexOf(id);
		if (index >= 0) {
			array.splice( index, 1 );	
		}
	}
	</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini my-task-page">  
		<div class="loader">
			<center><h1 id="loader_txt"></h1></center>
	</div>
		<?php  require 'demo.php'  ?>
		<?php require 'sales_sidenav.php' ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
				<div class="row header1">
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >
								<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-toggle="tooltip" data-placement="bottom" data-html="true" title="Click the <img src='<?php echo site_url(); ?>images/new/Plus_Off.png' width='20px' height='20px' /> button on the top right of this page to schedule a new task for a lead, opportunity or customer. Switch between tabs on the page to view open and closed tasks."/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Team_Tasks', 'video_body', 'Executive')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
							<h2 >My Tasks</h2>	<?php //echo phpversion();?>
							<input type="hidden" id="user_id_val" />
					</div>
					<div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
						<div class="addBtns">
							<a class="addPlus" onclick="addpopup()"><img src="<?php echo site_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/></a>
						</div>
					</div>
					<div style="clear:both"></div>
					<input type="hidden" id="start_hidden" />
					<input type="hidden" id="member_id_hidden" />
					<input type="hidden" id="lead_hidden" />
					<input type="hidden" id="member_hidden" />
					<input type="hidden" id="activity_hidden" />
					<input type="hidden" id="contact_hidden" />
					<input type="hidden" id="assignedDate_hidden" />
					<input type="hidden" id="userId_hidden" />
				</div>
				<ul class="nav nav-tabs" id="active_color"> 
					<li class="active" id="scheduled_task_tab"><a data-toggle="tab" href="#open">Open</a></li>
					<li id="completed_reshcedule_task_tab"><a onclick="completed_reshcedule_task(0)" data-toggle="tab" href="#close">Closed</a></li>				
				</ul>
			   <div class="tab-content">
					<div id="open" class="tab-pane fade in active">
						<table class="table">
							<thead>
								<tr>
									<th>SL No</th>
									<th>Scheduled On</th>
									<th>Title</th>
									<th>Activity Type</th>
									<th>Prospect</th>
									<th>Contact</th>
								  <!--	<th>Activity Owner</th>    -->
									<th>Scheduled By</th>
									<th>Status</th>
									<th></th>
									<th></th>
									<th><input type="hidden" id="close_task" /></th>
								</tr>
							</thead>
							<tbody id="tableBody">
							</tbody>
						</table>
					</div>
					<div id="close" class="tab-pane fade">
					 <table class="table sec_table">
							<thead>
								<tr>
									<th>SL No </th>
									<th>Closed At</th>
									<th>Scheduled On</th>
									<th>Title</th>
									<th>Activity Type</th>
									<th>Prospect</th>
									<th>Contact</th>
								   <!--	<th>Activity Owner</th> -->
									<th>Closed By</th>
									<th>Status</th>
									<th><!--<span class='glyphicon glyphicon-filter' id="tableFilter"></span>--></th>
								</tr>
							</thead>
							<tbody id="tableBody1">
							</tbody>
						</table>
					</div>					
				  </div> 				
			</div>
			<div id="completed_data_modal" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form class="form" action="#" method="post" name="adminClient">
							<div class="modal-header ">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Complete Activity<label id="event_name2"></label></h4>
							</div>
							<div class="modal-body">	
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2" >
										<label for="activity2">Activity</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" id="complete_act2" class="form-control" disabled>	
									</div>	
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div> 
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2" >
										<label  id="lead2_name"></label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" id="complete_lead2" class="form-control" disabled>	
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div>
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2" >
										<label for="contact2">Contact*</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" id="complete_contact2" class="form-control" disabled>	
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div>
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2">										
										<label for="start_date2">Start date*</label>
									</div>
									<div class='col-md-8  col-sm-8 col-xs-8'>
									    <div class="form-group">
									        <div class='input-group date' id='completed_startpicker'>
									            <input id="complete_start_date2" placeholder="Pick Start Time" type='text' class="form-control" readonly="readonly" />
									            <span class="input-group-addon">
									                <span class="glyphicon glyphicon-calendar"></span>
									            </span>
									        </div>
    										<span class="error-alert"></span>
									    </div>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div>							
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2">										
										<label for="actve_duration2">Activity duration*</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" id="complete_actve_duration2" class="form-control" placeholder="HH:MM" readonly>									
										<span class="error-alert"></span>																		
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div>
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2">										
										<label>Note*</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<textarea id="complete_note2" class="form-control"></textarea>											
										<span class="error-alert"></span>
									</div>				
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>														
								</div>
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2" >
										<label for="email_search">Set Email Alert</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8" >
										<input id='complete_email_search' class="form-control"  />
										<span class="error-alert"></span>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<ul id="complete_email_list_edit"></ul>
									</div>
								</div>

							</div>							
							<div class="modal-footer">	
								<button type="button" class="btn btn-primary" id="completed_save_bnt" onclick="completed_popup('false')" disabled >Save</button>	
								<button type="button" class="btn btn-primary" id="process_complete" onclick="completed_popup('true')" >Save &amp; Create</button>							
								<button type="button" class="btn btn-default" onclick="cancel()">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="reschedule" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form  class="form" action="#" method="post" name="adminClient">
							<div class="modal-header ">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Reschedule Activity for <label id="event_name2"></label></h4>
							</div>
							<div class="modal-body">	
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2" >
										<label for="activity2">Activity</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" id="act2" class="form-control" disabled>	
									</div>	
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div> 
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2" >
										<label  id="lead2_name"></label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" id="lead2" class="form-control" disabled>	
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div>
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2" >
										<label for="contact2">Contact*</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" id="contact2" class="form-control" disabled>	
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div>
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2">										
										<label for="start_date2">Start date*</label>
									</div>
									<div class='col-md-8  col-sm-8 col-xs-8'>
									    <div class="form-group">
									        <div class='input-group date' id='reschedule_startpicker'>
									            <input id="start_date2" placeholder="Pick Start Time" type='text' class="form-control" readonly="readonly" />
									            <span class="input-group-addon">
									                <span class="glyphicon glyphicon-calendar"></span>
									            </span>
									        </div>
    										<span class="error-alert"></span>
									    </div>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div>
								<!-- <div class="row">
									<div class="col-md-3 col-sm-3 col-xs-3">										
										<label for="start_time2">Start Time*</label>
									</div>
									<div class="col-md-9 col-sm-9 col-xs-9">
										<input type="text" id="start_time2" class="form-control" placeholder="HH:MM"  maxlength="5">	
										<span class="error-alert"></span>
									</div>	
								</div>					 -->			
								<div class="row">
								<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2">										
										<label for="actve_duration2">Activity duration*</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" id="actve_duration2" class="form-control" placeholder="HH:MM"  maxlength="5">									
										<span class="error-alert"></span>																		
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div>	
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2">										
											<label for="reminder_time2">Alert Before*</label>
									</div>
										<div class="col-md-8 col-sm-8 col-xs-8">
											<select class="form-control" id="reminder_time2">
												<option value="">Select</option>
												<option value="5">5 mins</option>
												<option value="15">15 mins</option>
												<option value="30">30 mins</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div>
								<div class="row">
								<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2">										
										<label>Note*</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<textarea id="note2" class="form-control"></textarea>											
										<span class="error-alert"></span>
									</div>				
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>														
								</div>
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2" >
										<label for="email_search">Set Email Alert</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8" >
										<input id='email_search' class="form-control">
										<span class="error-alert"></span>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<ul id="email_list_edit"></ul>
									</div>
								</div>

							</div>							
							<div class="modal-footer">
								<input type="hidden" id="reschd1">
								<input type="hidden" id="phone1">
								<input type="hidden" id="leadCustId">
								<input type="hidden" id="createdBy">
								<input type="hidden" id="event_name">
								<input type="hidden" id="personId">
								<input type="hidden" id="mangName">
								<input type="hidden" id="conntype">
								<input type="hidden" id="type">
								<input type="hidden" id="act22" class="form-control">	
								<input type="hidden" id="contact3" class="form-control">	
								<input type="hidden" id="lead3" class="form-control">	
								<button type="button" class="btn btn-primary" id="reschedule_save_bnt" onclick="reschedule_popup()" disabled>Save</button>							
								<button type="button" class="btn btn-default" onclick="cancel()">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="reschedule1" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form  class="form" action="#" method="post" name="adminClient">
							<div class="modal-header ">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title text-center"><label id="event_name_view"></label></h4>
							</div>
							<div class="modal-body">
								<div class="newView">
									
								</div>
								<!---------------------------
								<div class="row">
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>
									<div class="col-md-3 col-sm-3 col-xs-3" >
										<label for="activity2"><b>Activity</b></label>
									</div>
									<div class="col-md-5 col-sm-5 col-xs-5">
										<label id="act3"></label>
									</div>	
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>
								</div> 
								<div class="row">
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>
									<div class="col-md-3 col-sm-3 col-xs-3" >
										<label  id="lead_name"></label>
									</div>
									<div class="col-md-5 col-sm-5 col-xs-5">
										<label id="lead4"></label>	
									</div>
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>
								</div>
								<div class="row">
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>
									<div class="col-md-3 col-sm-3 col-xs-3" >
										<label for="contact2"><b>Contact</b></label>
									</div>
									<div class="col-md-5 col-sm-5 col-xs-5">
										<label id="contact4"></label>
									</div>
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>
								</div>
								<div class="row">
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>
									<div class="col-md-3 col-sm-3 col-xs-3" >
										<label for="phone3"><b>Phone Number</b></label>
									</div>
									<div class="col-md-5 col-sm-5 col-xs-5">
										<label id="phone3"></label>
									</div>
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>
								</div>
								<div class="row">
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>
									<div class="col-md-3 col-sm-3 col-xs-3">										
										<label for="start_date2"><b>Start date</b></label>
									</div>
									<div class='col-md-5 col-sm-5 col-xs-5'>
										<label id="start_date3"></label>
									</div>
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>
								</div>		
								<div class="row">
								<div class="col-md-2 col-sm-1 col-xs-1" ></div>
									<div class="col-md-3 col-sm-3 col-xs-3">										
										<label for="actve_duration2"><b>Activity duration</b></label>
									</div>
									<div class="col-md-5 col-sm-5 col-xs-5">
										<label id="actve_duration3"></label>																		
									</div>
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>
								</div>	
								----------------------- old comented area Start ------------
								<div class="row">
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>
									<div class="col-md-3 col-sm-3 col-xs-3">										
											<label for="reminder_time2"><b>Alert Before</b></label>
									</div>
										<div class="col-md-5 col-sm-5 col-xs-5">											
											<label id="reminder_time3"></label>											
										</div>
										<div class="col-md-2 col-sm-1 col-xs-1" ></div>
								</div>
								-------------- old comented area end ----------------------
								<div class="row">
								<div class="col-md-2 col-sm-1 col-xs-1" ></div>
									<div class="col-md-3 col-sm-3 col-xs-3">										
										<label><b>Note</b></label>
									</div>
									<div class="col-md-5 col-sm-5 col-xs-5">											
										<label id="note3"></label>
									</div>				
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>														
								</div>
								<div class="row cancel_column none">
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>
									<div class="col-md-3 col-lg-3 col-sm-3 col-xs-3">										
										<label><b>Cancellation Remarks</b></label>
									</div>
									<div class="col-md-5 col-sm-5 col-xs-5">											
										<label id="cancelation_remark"></label>
									</div>				
									<div class="col-md-2 col-sm-1 col-xs-1" ></div>														
								</div>
								------------------------ old comented area start ----
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2" >
										<label for="email_search">Set Email Alert</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8" >
										<input id='email_search' class="form-control"></input>
										<span class="error-alert"></span>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div>
								--------------------------- old comented area end-----
								<div class="row">
									<div class="col-md-12">
										<ul id="email_list_edit"></ul>
									</div>
								</div>
								-->
							</div>							
							<div class="modal-footer">
								<input type="hidden" id="clickCounterNumber" value="0"/>
								<!--<input type="hidden" id="act2" class="form-control">
								<input type="hidden" id="reschd1">
								<input type="hidden" id="phone1">
								<input type="hidden" id="leadCustId">
								<input type="hidden" id="contact3" class="form-control">	
								<input type="hidden" id="lead3" class="form-control"> -->								
								<button type="button" class="btn btn-default" onclick="cancel()">Close</button>
							</div>
						</form>
					</div>
				</div>
			</div>
							
			<div id="addmodal" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content mytask_add_modal">
						<form  class="form" action="#" method="post" name="adminClient">
							<div class="modal-header ">
								 <span class="close" onclick="cancel()">x</span>
								 <center><h4 id="add-modal-title" class="modal-title">Create Event</h4></center>
							</div>
							<div class="modal-body">
								<div class="row">
									<div>
										<center>
											<input type="checkbox" id="confirm_processAdd" onchange="confirm_process('add')">
											<label for="confirm_processAdd">Completed Task</label>						
										</center>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1"></div>
									<div class="col-md-2 col-sm-2 col-xs-2">
										<label for="event_nameadd">Title*</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" id="event_nameadd" class="form-control">
										<span class="error-alert"></span>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1"></div>
								</div>
								<div class="row">
									<div class="col-md-1"></div>
									<div class="col-md-2" >										
										<label for="activity">Activity*</label>
									</div>
									<div class="col-md-8">
										<select class="form-control" id="activity" style="display:none">
											
										</select>
										<select class="form-control" id="activity1">
											
										</select>											
										<span class="error-alert"></span>
									</div>
									<div class="col-md-1"></div>							
								</div>
								
								<div class="row">
									<div id="leadGroup" >										
										<div class="col-md-1"></div>
										<div class="col-md-2">
											<label for="task_type">Contact Type*</label>
										</div>
										<div class="col-md-8">
											<select class="form-control" id="task_type" onchange="save_id(0)">
												<option value="">Choose</option>
												<option value="lead">Lead</option>
												<option value="customer">Customer</option>
												<option value="opportunity">Opportunity</option>
												<option value="internal">Internal</option>												
												<option value="ticket">Ticket</option>												
											</select>
										</div>
										<div class="col-md-1"></div>
									</div>
								</div>
								<div class="row ticket_main none">
									<div id="leadGroup" >										
										<div class="col-md-1"></div>
										<div class="col-md-2">
											<label for="ticket_type">Ticket Type*</label>
										</div>
										<div class="col-md-8">
											<select class="form-control" id="ticket_type" onchange="save_id(1)">
												<option value="">Choose</option>
												<option value="lead">Lead</option>
												<option value="customer">Customer</option>												
											</select>
										</div>
										<div class="col-md-1"></div>
									</div>
								</div>
								<div class="row lead_error">
									<div align="center">
										<span class="error-alert"></span>	
									</div>
								</div>
								<div class="row lead_row none">
									<div class="col-md-1"></div>
										<div class="col-md-2">
											<label for="lead"><b>Lead*</b></label>
										</div>
									<div class="col-md-8">
										<div id="leadField">
											<input type="text" id="lead" class="form-control" placeholder="Enter Lead:"></input>
										</div>
										<div class="alert_errors">
											<span class="error-alert"></span>	
										</div>
									</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row cust_row none">
									<div class="col-md-1"></div>
										<div class="col-md-2">
											<label for="customer"><b>Customer*</b></label>
										</div>
									<div class="col-md-8">
										<div id="leadField">
											<input type="text" id="customer" class="form-control" placeholder="Enter Customer:"></input>
										</div>
										<div class="alert_errors1">
											<span class="error-alert"></span>	
										</div>
									</div>
									<div class="col-md-1"></div>
								</div>
								<div class="row opp_row none">
									<div id="oppGroup">
										<div class="col-md-1"></div>
										<div class="col-md-2">
											<label for="opportunity"><b>Opportunity*</b></label>
										</div>
										<div class="col-md-8">
											<div id="oppField">
												<!-- <input type="text" id="opportunity" class="form-control" placeholder="Enter Opportunity:"></input> -->
												<select class="form-control" id="opportunity" onchange="get_opp()">
											
												</select>

											</div>
											<div class="alert_errors2">
												<span class="error-alert"></span>	
											</div>
										</div>
										<div class="col-md-1"></div>
									</div>
								</div>
								<div class="row inter_row none">
									<div class="col-md-1"></div>
										<div class="col-md-2">
											<label for="internal"><b>Internal*</b></label>
										</div>
									<div class="col-md-8">
										<div id="interField">
											<input type="text" id="internal" class="form-control" placeholder="Enter Contact:"></input>
										</div>
										<div class="alert_errors3">
											<span class="error-alert"></span>	
										</div>
									</div>
									<div class="col-md-1"></div>
								</div>
								<div class="row ticket_selection none">
									<div id="leadGroup" >										
										<div class="col-md-1"></div>
										<div class="col-md-2">
											<label for="select_ticket">Ticket Name*</label>
										</div>
										<div class="col-md-8">
											<select class="form-control" id="select_ticket" onchange="select_ticket_type()">
																								
											</select>
										</div>
										<div class="col-md-1"></div>
									</div>
								</div>
								<div class="row none">
									<div class="col-md-1"></div>
									<div class="col-md-2">										
										<label for="contact">Contact* </label>
									</div>
									<div class="col-md-8">
										<select class="form-control" id="contact">
										</select>											
										<span class="error-alert"></span>
									</div>
									<div class="col-md-1"></div>
								</div>
								<div class="row numberClass none">
									<div class="col-md-1"></div>
									<div class="col-md-2">										
										<label for="number">Number </label>
									</div>
									<div class="col-md-8">
										<select class="form-control" id="number">
										</select>											
										<span class="error-alert"></span>
									</div>
									<div class="col-md-1"></div>				
								</div>
								<div class="row show_all">
									<div class="col-md-1">
									</div>
									<div class="col-md-8">
										<input type="checkbox" id="check_all" onchange="team_members_Add()"/> <label for="check_all">Show All</label>
									</div>
									<div class="col-md-2">
									</div>
								</div>
								<div class="row team_mem_show1 none">
									<div class="col-md-1"></div>
									<div class="col-md-2" >										
										<label for="activity">Activity Owner*</label>
									</div>
									<div class="col-md-8" id="add_product" name="edit_product1">
																		
									</div>
															
								</div>
								<div class="row">
									<div class="col-md-1">
									
									</div>
									<div class="col-md-10">
										<div id="product_err" align="right">
											 <span class="error-alert" style="margin-right: 158px;"></span>
										</div>	
									</div>
									<div class="col-md-1">
									
									</div>
								</div>
								<div class="row" id="timepicker">
									<div class="col-md-1"></div>
									<div class="col-md-2">          
										<label for="start_date">Starts*</label>
									</div>
									<div class='col-md-8'>
										<div class="form-group">
											 <div class='input-group date' id='startDateTimePicker'>
												 <input id="start_date" placeholder="Pick Start Time" type='text' class="form-control" readonly="readonly"/>
												 <span class="input-group-addon">
													 <span class="glyphicon glyphicon-calendar"></span>
												 </span>
											 </div>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="col-md-1"></div>
								</div>
								<div class="row none" id="timepicker1">
									<div class="col-md-1"></div>
									<div class="col-md-2">          
										<label for="start_date1">Starts*</label>
									</div>
									<div class='col-md-8'>             
										<div class="form-group">
											<div class='input-group date' id='startDateTimePicker1'>
												 <input id="start_date1" placeholder="Pick Start Time" type='text' class="form-control" readonly="readonly"/>
												 <span class="input-group-addon">
													 <span class="glyphicon glyphicon-calendar"></span>
												 </span>
											 </div>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="col-md-1"></div>
								</div>
								<div class="row">
									<div class="col-md-1"></div>
									<div class="col-md-2">
										<label for="actve_duration">Duration*</label>
									</div>
									<div class="col-md-8">
										<input type="text" id="actve_duration" class="form-control" placeholder="HH:MM"  maxlength="5">			
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-1"></div>
									<div class="col-md-2">
										<label for="reminder_time" class="reminder_time_hide">Alert Before*</label>
									</div>
									<div class="col-md-8">
										<select class="form-control reminder_time_hide" id="reminder_time" >
											<option value="">Select</option>
											<option value="5">5 mins</option>
											<option value="15">15 mins</option>
											<option value="30">30 mins</option>
										</select>
										<span class="error-alert"></span>
									</div>									
								</div>
								<div class="row rating_task">
									<div id="rating" style="display:none">
										<div class="col-md-1"></div>
										<div class="col-md-3">										
											<label>Rating activity*</label>
										</div>
										<div class="col-md-7">
											<label id="rating_activity_add">
												<i class="glyphicon glyphicon-star rating1" onclick="rating(1)"></i>
												<i class="glyphicon glyphicon-star rating2" onclick="rating(2)"></i>
												<i class="glyphicon glyphicon-star rating3" onclick="rating(3)"></i>
												<i class="glyphicon glyphicon-star rating4" onclick="rating(4)"></i>
											</label>
											<span class="rating_msg" ></span>
											<br/>
											<label class="error-alert" id="rating_error1"></label>
										</div>									
									</div>
								</div>
								<div class="row"> 
									<div class="col-md-1"></div>
										<div class="col-md-10">
											<textarea id="note" placeholder="Enter Remarks" class="form-control task_remarks"></textarea>
											<span class="error-alert"></span>
										</div>
									<div class="col-md-1"></div>
								</div>
								<div class="row email_alert none" id="email_grant">
									<div class="col-md-1"></div>
									<div class="col-md-2 pull-left">
										<input type="checkbox" name="check" id="email_check" />
										<label for="email_check"> Email Alert </label>
									</div>
									<div class="col-md-8 email_section" style="display: none;">
										<input id='email_members' class="form-control" placeholder="Send Emails to:" />
										<span class="error-alert"></span>
									</div>
									<div class="col-md-1"></div>
								</div>					
								<div class="row">
									<ul id="email_list"></ul>
								</div>
							</div>							
							<div class="modal-footer">
							<input type="hidden" id="phone2"/>
								<button type="button" class="btn btn-primary" id="prev_act" onclick="addevent()" >Save</button>
								<button type="button" class="btn btn-primary" style="display:none" id="next_act" onclick="addevent1()" >Save</button>								
								<button type="button" class="btn btn-primary" id="task_create" onclick="addevent(1)" >Save &amp; Create</button>
								<button type="button" class="btn btn-primary none" id="task_completed" onclick="addevent1(3)" >Save &amp; Create</button>
								<button type="button" class="btn btn-default" onclick="cancel()">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			
			
			<div id="completed" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form  class="form" action="#" method="post" name="adminClient">
							<div class="modal-header ">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Updating <label id="popup_title"></label></h4>
							</div>
							<input type="hidden" class="form-control" id="cmp_contact_hidden" disabled>
							<div class="modal-body">
							<div class="row" style="display:none">
									<div class="col-md-2">
										<label for="event_name">Event Name*</label>
									</div>
									<div class="col-md-4">
										<input type="text" id="event_name" class="form-control">
										<span class="error-alert"></span>
									</div>
									<div class="col-md-2" >										
										<label for="cmp_activity">Activity</label>
									</div>
									<div class="col-md-4" id="completed_choose">
										<select class="form-control" id="cmp_activity" disabled>
											<option value="">Choose</option>
											
										</select>											
										<span class="error-alert"></span>
									</div>	
								</div>
								<div class="row" style="display:none">																	
									<div class="col-md-1" >										
										<label for="cmp_lead">Lead</label>
									</div>
									<div class="col-md-3">
										<select class="form-control" id="cmp_lead" disabled>
											<option value="">Choose</option>
										</select>											
										<span class="error-alert"></span>
									</div>
									<div class="col-md-1 padding-left">										
										<label for="cmp_contact">Contact</label>
									</div>
									<div class="col-md-3">
										
										<select class="form-control" id="cmp_contact" disabled>
											<option value="">Choose</option>
										</select>											
										<span class="error-alert"></span>
									</div>
									<div class="col-md-1">										
										<label for="reminder_time1">Alert Before*</label>
									</div>
									<div class="col-md-3">
										<select class="form-control" id="reminder_time1">
											<option value="">Select</option>
											<option value="5">5 mins</option>
											<option value="15">15 mins</option>
											<option value="30">30 mins</option>
										</select>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row" style="display:none">
									<div class="col-md-1">										
										<label for="cmp_start_date">Start date*</label>
									</div>
									<div class="col-md-3">
										<input type="text" id="cmp_start_date" class="form-control" placeholder="DD-MM-YYYY" maxlength="10" readonly>
										<span class="error-alert" disabled></span>
									</div>
									<div class="col-md-1 padding-left">										
										<label for="cmp_start_time">Start Time*</label>
									</div>
									<div class="col-md-3">
										<input type="text" id="cmp_start_time" class="form-control" placeholder="HH:MM" maxlength="5" disabled>										
										<span class="error-alert"></span>
									</div>
									
									
								</div> 

<!-- 								<div class="row">
									<div class="col-md-12" id="comment_display">	
									</div>
								</div> -->
								<div class="row">
										<div>
											<center>
													<span id="label_add"></span>
													<input type="checkbox" id="process_cancel" onchange="cancel_process1()"> <label for="process_cancel">Cancel Event</label>
											</center>									
										</div>
								</div>
								<br>
								<div class="row process_task">
								<div class="col-md-1">
								</div>
									<div class="col-md-2">										
										<label for="cmp_note">Note*</label>
									</div>
									<div class="col-md-8">
										<textarea id="cmp_note" class="form-control"></textarea>											
										<span class="error-alert"></span>
									</div>				
									<div class="col-md-1">
									</div>														
								</div>
								<div class="row starts_time none">
									<div class="col-md-1">
									</div>
									<div class="col-md-2">										
										<label for="starts_date">Starts*</label>
									</div>
									<div class="col-md-7">
											<input type="hidden" id="completed_type_hidden">
											<input type="hidden" id="old_comp_start_date">
											<div class="form-group">
												<div class='input-group' >
													<input placeholder="Pick Start Time" id='completed_starts' type='text' class="form-control" disabled />
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<span class="error-alert"></span>
											</div>
									</div>				
									<div class="col-md-2">
										<input type="button" class="btn" onclick="enable()" value="Edit" />
									</div>
								</div>
								<div class="row cancel_task none">
									<div class="col-md-1">
									</div>
									<div class="col-md-2">										
										<label for="cmp_note1">Cancellation Remarks*</label>
									</div>
									<div class="col-md-8">
										<textarea id="cmp_note1" class="form-control"></textarea>											
										<span class="error-alert"></span>
									</div>				
									<div class="col-md-1">
									</div>														
								</div>
								
								<div class="none" id="completed_rating" >		
								<div class="row act_dur">
										<div class="col-md-1">
										</div>
										<div class="col-md-2">										
												<label for="cmp_duration">Activity duration*</label>
											</div>
											<div class="col-md-8">
												<input type="text" id="cmp_duration" class="form-control" placeholder="HH:MM" maxlength="5">									
												<span class="error-alert"></span>
											</div> 
											<div class="col-md-1">
											</div>
								</div>	
								<div class="row rate_act">				
									<div class="col-md-1">
									</div>
													
										<div class="col-md-2" >										
											<label>Rate activity*</label>
										</div>
										<div class="col-md-8">
											<label id="rating_activity">
												<i class="glyphicon glyphicon-star rating1" onclick="rating(1)"></i>
												<i class="glyphicon glyphicon-star rating2" onclick="rating(2)"></i>
												<i class="glyphicon glyphicon-star rating3" onclick="rating(3)"></i>
												<i class="glyphicon glyphicon-star rating4" onclick="rating(4)"></i>
											</label>
											<span class="rating_msg" ></span>
											<br/>											
											<label class="error-alert" id="rating_error"></label>
										</div>																	
								</div>
							</div>
							<div class="modal-footer">													
									<input type="hidden" name="process"  id='process'>
									<input type="hidden" name="phone"  id='phone'>
									<button type="button" class="btn btn-default cancel_close" style="display:none;" onclick="cancel()">Close</button>
									
									<button type="button" class="btn btn-primary" id="save_task" onclick="processComplete('false')" >Save</button>
									<button type="button" class="btn btn-primary none" id="task_save" onclick="cSave_process()" >Save</button>
									<button type="button" class="btn btn-primary" id="process_complete" onclick="processComplete('true')" >Save &amp; Create</button>
									<button type="button" class="btn btn-default cancel_cancel" onclick="cancel()">Cancel</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="messageDetails" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
					<div class="modal-content modal-lg">
						<div class="modal-header">
							<span class="close"  onclick="closeMsgDetails()">&times;</span>
							<h4 class="modal-title">Mail Details</h4>
						</div>
						<div class="modal-body">
							<!--<div class="row">
                                <div class="col-md-12 text-right">
									<a onclick="replySelectedMail()" href="javascript:void(0)" title="Reply Mail">
										<i class="fa fa-reply fa-2x btn"></i>
									</a>
									<a onclick="replyAllSelectedMail()" href="javascript:void(0)" title="Reply Mail to all">
										<i class="fa fa-reply-all fa-2x btn"></i>
									</a>
									<a onclick="forwardSelectedMail()" href="javascript:void(0)" title="Forward Mail">
										<i class="fa fa-arrow-circle-right fa-2x btn"></i>
									</a>
								</div>
							</div>-->
							<div class="row">
                                <div class="col-md-2">
									<label ><b>Date: </b></label>
								</div>
								<div class="col-md-10">
									<label id="sub_date"></label>
								</div>
							</div>
                            <div class="row">
                                <div class="col-md-2">
									<label><b>From: </b></label>
								</div>
								<div class="col-md-10">
									<p id="from_name"></p>
								</div>
							</div>
                            <div class="row">
                                <div class="col-md-2">
									<label><b>To: </b></label>
								</div>
								<div class="col-md-10">
									<p id="to_name"></p>
								</div>
							</div>
							<div class="row">
                                <div class="col-md-2">
									<label><b>CC: </b></label>
								</div>
								<div class="col-md-10">
									<p id="cc_name"></p>
								</div>
							</div>
							<div class="row">
                                <div class="col-md-2">
									<label><b>BCC: </b></label>
								</div>
								<div class="col-md-10">
									<p id="bcc_name"></p>
								</div>
							</div>
                            <div class="row">
								<div class="col-md-2">
									<label><b>Subject: </b></label>
								</div>
								<div class="col-md-10">
									<p id="sub_name"></p>
								</div>

							</div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label><b>Message: </b></label>
                                </div>
                                <div class="col-md-10">
                                    <pre id="mail_body"></pre>
                                </div>
                            </div>
                            <div class="row attach_file">
                                <div class="col-md-2">
                                    <label><b>Attachment: </b></label>
                                </div>
                                <div class="col-md-10">
                                    <label id="attach_file"></label>
                                </div>
                            </div>
                            <!--<div class="row ratingCommentSection">
								<hr>
								<div class="col-md-2">
                                    <label><b>Rating activity*</b></label>
                                </div>
                                <div class="col-md-6 text-center">
									<label class="rating_activity_add">
										<i class="glyphicon glyphicon-star rating1" onclick="rating(1)" style="color: rgb(181, 0, 10);"></i>
										<i class="glyphicon glyphicon-star rating2" onclick="rating(2)" style="color: rgb(181, 0, 10);"></i>
										<i class="glyphicon glyphicon-star rating3" onclick="rating(3)" style="color: rgb(181, 0, 10);"></i>
										<i class="glyphicon glyphicon-star rating4" onclick="rating(4)" style="color: rgb(210, 207, 207);"></i>
									</label>
									<br>
									<span class="error-alert rating_error"></span>
                                </div>
								<div class="col-md-4">
                                    <span class="rating_msg"></span>
                                </div>
								<div class="col-md-12">
                                    <textarea class="form-control" placeholder="Enter Remarks"></textarea>
									<span class="error-alert"></span>
                                </div>
							</div>-->
                            <div class="row">
                                <div class="col-md-2">
                                    <label ></label>
                                </div>
                                <div class="col-md-10">
                                    <label id="matchbtn"></label>
                                </div>
                            </div>
						</div>
						<div class="modal-footer">
                            <!--<input type="button" class="btn my_btn" id="raise_ticket" onclick="raise_ticket(this)" value="Raise Ticket" /> Requirement Doc 10th Oct 11(Priority 8)
                            <input type="button" class="btn my_btn" id="create_lead" onclick="raise_ticket(this)" value="Create Lead" />
                            <input type="button" class="btn my_btn" id="create_contact" onclick="raise_ticket(this)" value="Create Contact" />-->
							<input type="button" class="btn" onclick="closeMsgDetails()" value="Close">
						</div>
					</div>
				</div>
			</div>
			<div id="attachment_box" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<span class="close"  onclick="attachment_match()">&times;</span>
							<h4 class="modal-title">Attached File</h4>
						</div>
						<div class="modal-body">
							<div class="row">
				                 <div class="col-md-3">

                                 </div>
                                 <div class="col-md-6 file_attach">

                                 </div>
                                 <div class="col-md-3">

                                 </div>
							</div>
						</div>
						<div class="modal-footer">
							<input type="button" class="btn" onclick="attachment_match()" value="Cancel">
						</div>
					</div>
				</div>
			</div>
			
			</div>		 
			<?php require 'footer.php' ?>

	</body>
</html>
