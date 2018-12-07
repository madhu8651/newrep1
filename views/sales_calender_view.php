<!DOCTYPE html>
<html lang="en">
  <head>  
  <?php require 'scriptfiles.php' ?>
  <style>
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
.twitter-typeahead {
    width: 100%;
}
.modal-backdrop{
  z-index:-1;
}
.error-alert{
  color:red;
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
.content-wrapper.body-content section.row{
  height:46px;
}

.item.ui-sortable{
  padding: 15px;
}
.que-container {
   margin-top: -20px;
}
.delete-row{
  width: 20px;
    height: 20px;
    float: right;
    text-align: center;
    line-height: 16px;
    background: #B5000A;
    border-radius: 50%;
  margin-top: -35px;
    margin-right: -15px;
}
.li-shortable{
  min-height: 50px;
    border: 1px solid #ccc;
    box-shadow: 0px 3px 12px #ccc;
    padding: 5px;
}
.que-container{
  width:90%;
  float:left;
}
.clear-both{
  clear:both
}
.modal-dialog .modal-header div.padding-left, 
.modal-dialog .modal-body div.padding-left, 
.modal-dialog .modal-footer div.padding-left{
  padding-left:20px;
}
#calendar{
  width:80%;
  margin:auto;
}

/*------------*Calendar style-------------*/
.ui-datepicker-header .ui-datepicker-title{
      text-align: center;
    font-size: 16px;
    border-bottom: 2px solid rgb(181, 0, 10);
    color: #B5000A;
}
#ui-datepicker-div table.ui-datepicker-calendar tr td:hover{
  background: #ccc;
}
#ui-datepicker-div table.ui-datepicker-calendar tr td,
#ui-datepicker-div table.ui-datepicker-calendar tr th{
  width:20px;
  height:20px;
  text-align:center;
  border-radius: 10px;
}
#ui-datepicker-div table.ui-datepicker-calendar,
#ui-datepicker-div table.ui-datepicker-calendar tr{
  width:100%;
}
#ui-datepicker-div td a.ui-state-highlight{
  color: #B5000A;
  font-size: 16px;
    font-weight: bold;
}
#ui-datepicker-div .ui-datepicker-month{
  outline: none;
    border: none;
}
#ui-datepicker-div{
  background: white;
    border: 5px solid rgb(181, 0, 10);
    width: 200px;
  padding:10px;
  border-radius: 5px;
}
.ui-datepicker-header.ui-widget-header .ui-datepicker-next{
  float: right;
}
.ui-datepicker-header.ui-widget-header .ui-datepicker-prev{
  float: left;
}
.fc-widget-header{
    background-color:#B5000A;
    color: #FFF;
}
.fc-toolbar {
  color: #B5000A;
  margin-top:4em;
}
.icon-big{
  position: absolute;
  right: 50px;
  top: 15px;
  font-size: 20px;
  /*color: #B5000A;*/
}
@media (max-width:767px){ 
  .modal-dialog .modal-header div.padding-left, 
  .modal-dialog .modal-body div.padding-left, 
  .modal-dialog .modal-footer div.padding-left{
    padding-left:0px;
  }
}
#process_cancel{
	margin-left: 16px;
}
.legend{
	background: orange;
    width: 10px;
    height: 16px;
    margin-top: 10px;
}
.legend1{
	background: blue;
    width: 10px;
    height: 16px;
    margin-top: 10px;
    margin-left: 10px;
}
.legend2{
	background: red;
    width: 10px;
    height: 16px;
    margin-top: 10px;
}
.legend3{
	background: purple;
    width: 10px;
    height: 16px;
    margin-top: 10px;	
    margin-left: -12px;
}
.legend4{
	background: green;
    width: 10px;
    height: 16px;
    margin-top: 10px;
    margin-left: -6px;
}
.legend_name{
	margin-top:8px;
}
.legend_row{
	margin-bottom: -32px;
}
.tooltiptopicevent{
	width:auto; 
	height:auto; 
	background:#cdcdcd; 
	position:absolute; 
	z-index:10001; 
	padding:5px 5px 5px 5px; 
	line-height: 100%;
}
body{
	padding-right:0px !important;
}
.fc-day-header a{
	color:white!important;
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
  </style>
<link href='<?php echo base_url(); ?>css/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>css/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo base_url(); ?>js/moment.min.js'></script>
<script src='<?php echo base_url(); ?>js/fullcalendar.js'></script>
  <script>
		var dateToday = new Date();
		var CurrentDate = "", assigned_date = "", completed_value;
		var date_chk =  new RegExp(/^\d{2}\-\d{2}\-\d{4}$/);
		var time_chk =  new RegExp(/^\d{2}\:\d{2}$/);
		var text_chk_spcl_chr =  new RegExp(/^[a-zA-Z0-9 &_.-]*$/);    
		var rating1 = 0, ticket_val;
		var mainData=[];

		var rating1 = 0;
		var activity = "Previous";

		var selectedLead = {};
		var selectedOppo = {};
		var email_members = [];
		var reminder_members = [];
	function disable_button(btn_id, status) {
		$(btn_id).prop('disabled', status);
	}
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
				console.log(data)
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
	function cancel_completed(){
		$("#completed_Modal").modal("hide");
		$("#confirm_process1").prop('checked', false);
	}
	function complete_alert(){
		var obj = completed_value;
		console.log(obj)
		var addEventObj ={};		
		
		addEventObj.camp_note1 = obj.remarks;
		addEventObj.lead_reminder_id=$('#process').val();
		addEventObj.cmp_lead_name =					$("#lead_hidden").val();
		addEventObj.cmp_member_name =					$("#member_hidden").val();
		addEventObj.cmp_contact_name =					$("#contact_hidden").val();
		addEventObj.cmp_activity_name =					$("#activity_hidden").val();
		addEventObj.cmp_member_id =					$("#member_id_hidden").val();
		addEventObj.cmp_meeting_start =					$("#start_hidden").val();
		$.ajax({ 	
			type : "POST",
			url : "<?php echo site_url('manager_mytaskController/rescheduleEvent'); ?>",	
			data: JSON.stringify(addEventObj),     
			dataType : 'json',
			cache : false,
			success : function(data){
				$("#completed_Modal").modal("hide");
				$("#completed").modal("hide");
				$('#completed_data_modal').modal('show');
				console.log(obj);
				time_overlap = false;							
				$("#event_id_hidden").val(obj.employeeid);							
				$("#lemp_id").val(obj.employeeid);							
				$("#event_leadid_hidden").val(obj.leadid);
				$("#event_person_id_hidden").val(obj.person_id);
				$('#event_created_by_hiddden').val(obj.created_by);
				$("#event_type_hidden").val(obj.type);
				$("#act22").val(obj.activity_id);
				
				$("#completed").modal("hide");
				$('#complete_event_name2').text(obj.title);
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
				$("#complete_act2").val(obj.activity_name);
				/* $("#complete_act2").append("<option value='"+obj.activity_id+"'>"+obj.activity_name+"</option>");
				$("#complete_act2").prop("disabled", true); */
				
				$("#complete_lead2").val(obj.leadname);/* 
				$("#complete_lead2").append("<option value='"+obj.leadid+"'>"+obj.leadname+"</option>");
				$("#complete_lead2").prop("disabled", true); */
				
				$("#complete_contact2").val(obj.employeename);
				/* $("#complete_contact2").append("<option value='"+obj.employeeid+"'>"+obj.employeename+"</option>");
				$("#complete_contact2").prop("disabled", true); */
				
				var startTime = moment(obj.start._i).format('DD-MM-YYYY');
				$("#complete_start_date2").val(moment(obj.start._i).format('lll'));
				
				var durationTime = moment(obj.duration, 'HHmmss');
				$("#complete_actve_duration2").val(durationTime.format('HH:mm'));
				
				$("#reminder_time2").val(obj.addremtime);
				$("#complete_note2").val(obj.remarks);
				
				var data = {};
				data.remmem = reminder_members;
				$.ajax({ 	/* //ajax call for edit pending task of lead_activity */
					type : "POST",
					url : "<?php echo site_url('manager_mytaskController/get_editable_emails'); ?>",	
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
							$("#complete_email_list_edit").append("<li id="+ data.sendSavedID[i].user_id+"><span>"+ data.sendSavedID[i].user_name+" </span><a style='color:red;' class='glyphicon glyphicon-remove-sign' onclick='del(\""+data.sendSavedID[i].user_id+"\", reminder_members)'></a></li>");
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
								
								/*meeting_members = [];
								sales_mytask_selected_user_attendees = [];
								sales_mytask_selected_lead_attendees = [];*/
								$("#complete_email_list_edit").append("<li id="+ datum.user_id+">"+ datum.user_name+" <a style='color:red;' class='glyphicon glyphicon-remove-sign' onclick='del(\""+datum.user_id+"\", reminder_members)'></a></li>");
									$('#complete_email_search').closest("div").find("span.error-alert").text("");
									$('#complete_email_search').val("");	
								
							} else {
								alert("Can't add more than 12 Users");
								$('#complete_email_search').closest("div").find("span.error-alert").text("Can't add more than 12 Users");
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
				var oldobj={};
					oldobj.startdate= moment(obj.start._i).format('lll');
					oldobj.duration= obj.duration;
					oldobj.alertBefore= obj.addremtime;
					oldobj.note= obj.remarks;
					
				var newobj={};
					newobj.startdate= moment($.trim($("#complete_start_date2").val()), 'lll').format('lll');
					newobj.duration= moment($.trim($("#complete_actve_duration2").val()), 'H [Hrs] m [mins]').format('HH:mm:ss');
					newobj.alertBefore= $.trim($('#reminder_time2').val());
					newobj.note= $.trim($('#complete_note2').val());
				
				
				$("#complete_actve_duration2").on("dp.change", function (e) {
					
					/* ----------------------------- */
					newobj.duration= moment($.trim($("#complete_actve_duration2").val()), 'H [Hrs] m [mins]').format('HH:mm:ss');
					compare1(newobj,oldobj);
				});
				
				$("#completed_startpicker").on("dp.change", function (e) {	
					newobj.startdate= moment($.trim($("#complete_start_date2").val()), 'lll').format('lll');
					compare1(newobj,oldobj);
				})

				$('#reminder_time2').change(function(){
					newobj.alertBefore= $.trim($('#reminder_time2').val());
					compare1(newobj,oldobj);
				});
				$('#complete_note2').keyup(function(){
					newobj.note= $.trim($('#complete_note2').val());
					compare1(newobj,oldobj);
				});
		
			}
		});
	}
	/* compare function */
	function compare1(newObj,oldObj){
		$(".error-alert").text("");
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
		$("#completed_start").prop("disabled", false);
	}
	/* ----------------------------------------------------------------
	*************************** completed Submitting completed data **********************************
	-----------------------------------------------------------------------	*/
	function completed_popup(showAddModel)	{
		var addEventObj={};
		if($.trim($("#complete_start_date2").val()) == ""){
			$("#complete_start_date2").closest("div").siblings(".error-alert").text("Start date is required.");
			return;	
		} else 	{
			date = moment($.trim($("#complete_start_date2").val()), 'lll').format('YYYY-MM-DD HH:mm:ss');
			$("#complete_start_date2").closest("div").siblings(".error-alert").text("");
		}
		
		if($.trim($("#complete_actve_duration2").val()) == ""){
			$("#complete_actve_duration2").closest("div").find(".error-alert").text("Specify activity duration.");
			return;	
		}else{
			var duration1 = $.trim($("#complete_actve_duration2").val()).split(":");
			if(duration1[0] == "0" && duration1[1] == "0"){
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
		addEventObj.lead_reminder_id = 	$.trim($("#event_id_hidden").val()); 			/* ---	event id */
		addEventObj.event_name = 		$.trim($("#complete_event_name2").val());					/* ---	event Name */
		addEventObj.conntype = 			$.trim($("#complete_act2").val());						/* ---	activity id */
		addEventObj.act2 = 				$.trim($('#act22').val());						/* ---	activity Name */
		addEventObj.lead3 = 			$("#event_leadid_hidden").val();		/* ---	lead id */
		addEventObj.contact3 = 			$.trim($('#lemp_id').val()); 					/* ---	employee id */
		addEventObj.start_date2 = 		$.trim(date);									/* ---	start date */ 
		addEventObj.actve_duration2 = 	$.trim(duration);								/* ---	duration */
		addEventObj.note2 = 			$.trim($('#complete_note2').val());						/* ---	remarks */
		addEventObj.phone1 = 			getMobileNumber($('.event_phone_hidden').text()); 	/* ---	phone number */
		addEventObj.reminder_time = 	$.trim($('#reminder_time2').val());				/* ---	reminder time */
		addEventObj.created_by = 		$.trim($('#event_created_by_hiddden').val());	/* ---	created by */
		addEventObj.person_id = 		$.trim($("#event_person_id_hidden").val());		/* ---	person id */
		addEventObj.type = 				$.trim($("#event_type_hidden").val());			/* ---	type */		
		addEventObj.cmp_lead_name =					$("#lead_hidden").val();
		addEventObj.cmp_member_name =					$("#member_hidden").val();
		addEventObj.cmp_contact_name =					$("#contact_hidden").val();
		addEventObj.cmp_activity_name =					$("#activity_hidden").val();
		addEventObj.cmp_member_id =					$("#member_id_hidden").val();
		addEventObj.cmp_meeting_start =					$("#start_hidden").val();
		/* ajax call for rescdule activity */
		/* if(time_overlap == false){
			overlap(addEventObj.start_date2,addEventObj.actve_duration2);
			return;
		} */
		console.log(addEventObj)
		loaderShow();
		$.ajax({ 
			type : "POST",
			url : "<?php echo site_url('manager_mytaskController/completeTaskManually'); ?>",
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
		$("#email_grant").hide();
		$("#email_search").val("");
		$(".email_section").hide();		
		
		$("#lead, #customer, #opportunity, #internal").removeAttr("title");
		$("#lead, #internal, #customer").typeahead("destroy");
		/* ------------------------------------------------ */
		$('.modal').modal('hide');
		$('input[type="text"],#completed select,#addmodal select, textarea').val('');
		$('input[type="radio"]').prop('checked', false);
		$('input[type="checkbox"]').prop('checked', false);
    	$('#rederr').hide();    	
		$("#email_list li").each(function(){
			del($.trim($(this).attr('id')));
		});
		$("#process_complete").show();
		$("#task_save").hide();
		$("#save_task").show();
		$(".process_task").show();
		$(".team_mem_show,#prev_act").show();
		
		$(".show_all").show();
		$(".ticket_main").hide();
		$(".ticket_selection").hide();
		$(".team_mem_show1").hide();
		
		$("#next_act").hide();
		$(".cancel_task").hide();
		$(".act_dur").hide();
		$(".rate_act").hide();
		$(".lead_row").hide();
		$(".cust_row").hide();
		$(".opp_row").hide();
		$(".inter_row").hide();
    	$(".numberClass").hide();
    	$(".rating_task").hide();
		selectedLead = {};
		email_members = [];
	}
	

		
	var user_cal='';
	$(document).ready(function(){
		$(".team_mem_show").hide();
		$('#tableFilter').tooltip({
			html: true,
			title: '<center><h4> Options </h4></center><br><select title="Show Activities for" class="form-control drop" id="typeSelection" style="margin: 0 auto;" onchange="reloadTable()"><option value="both"> Leads &amp; Opportunities</option><option value="lead"> Leads</option><option value="oppo"> Opportunities</option></select>', 
			trigger: "click focus",
			placement: "auto",
			animation: true
		}); 
		$("#actve_duration").on("dp.change", function (e) {
			var startDateTime = moment($.trim($("#start_date").val()), 'lll').format('YYYY-MM-DD HH:mm:ss');		
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
			format:'H:m',
		});
		$("#actve_duration2").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'H:m',
		});

		$("#reschedule_startpicker").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'lll',
			minDate: moment(),
		});
		$("#completed_startpicker").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'lll',
		});
		
		pageload();
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

  function disable_button(btn_id, status) {
    $(btn_id).prop('disabled', status);
  }
  var lobj='';
  var data_opp='';
  function select_ticket_type(){
			var select_type = $("#ticket_type").val();
			var leadObj = {};
			leadObj = ticket_val;	
			leadObj.supportid = $("#select_ticket option:selected").val();			
			console.log(leadObj)
			loaderShow();
			$.ajax({ 	/* //ajax call for get rep_names */
				type : "POST",
				url : "<?php echo site_url('sales_mytaskController/get_contactsForLead'); ?>",
				data:JSON.stringify(leadObj),
				dataType : 'json',
				cache : false,
				success : function(data){
					loaderHide();
					console.log(data)
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
										console.log(data)
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
										console.log(data)
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
		var  data5=[];
		for(i=0;i<data_opp.length;i++){
			if(data_opp[i].opportunity_id==id){
				data5.push(data_opp[i]);
			}
		}   

		var select = $("#contact"), options = "<option value=''>Choose Contacts</option>";
		select.empty();      
		for(var i=0;i<data5.length; i++)  {
			options += "<option value='"+data5[i].contact_id+"'>"+ data5[i].contact_name +"</option>"; 
			selectedLead['id'] = data5[i].opportunity_id;      
			selectedLead['name'] = data5[i].opportunity_name; 
			selectedLead['type'] = 'opportunity';
		}
		select.append(options); 
    }
	
function pageload(a){
	$.ajax({
	type : "POST",
	url :"<?php echo site_url('sales_mytaskController/checkForPending');?>",
	dataType:'json',
	cache : false,
	success :function(data){
		console.log(data)
		$.ajax({
        type : "POST",
        url : "<?php echo site_url('sales_calendarController/initCal'); ?>",
        dataType : 'json',
        cache : false,
        success : function(data){
			if(a==1){
				addpopup();
			}
			$('#actve_duration').data("DateTimePicker").date('00:00');
			console.log(data)
			loaderHide();
			if(error_handler(data)){
				return;
			}
			for(e=0; e<data.length; e++){				
				if(data[e].hasOwnProperty("start")){
					var start = data[e].start.split("+");				
					data[e].start = start[0];
				}
				if(data[e].hasOwnProperty("end")){
					var end = data[e].end.split("+");
					data[e].end = end[0];
				}
				/*if(data[e].hasOwnProperty("phone")){
					data[e].phone = JSON.parse(data[e].phone);
					data[e].phone.mobile = data[e].phone.mobile.toString()
				}*/
				
			}
			$("#close_task").val("<?php echo $this->session->userdata('uid');?>");
			
			$('#calendar').fullCalendar('destroy'); /* ------- destroy calender before rendering ------------- */
			/* ---------------------------- Rendering  calender ---------------------------------------- */
			$('#calendar').fullCalendar({
				header: {
					left: 'prev,next today',
					center: 'title',
					/* right: 'listDay,listWeek,month' --------------- removed list view prashanth's requirement 20-07-2017 ------*/
					right: 'agendaDay,agendaWeek,month'
				},
				/* --------------- removed list view prashanth's requirement 20-07-2017 --------------------
				views: {
					listDay: { buttonText: 'list day' },
					listWeek: { buttonText: 'list week' }
				}, 
				--------------------------------------------------------------------------------------------*/

				defaultView: 'agendaDay',
				defaultDate: "<?php echo date('Y-m-d');?>",	/* for today date "moment()" */
				navLinks: true, 							/* can click day/week names to navigate views */
				editable: false, 							/* to make editable event -drag and drop */
				eventLimit: true,  							/* allow "more" link when too many events */
				startEditable :false,
				timeFormat: 'hh:mm a',
				eventOverlap: function(stillEvent, movingEvent) {
					return stillEvent.allDay && movingEvent.allDay;
				},
				events: data,
				eventMouseover: function (data, event, view) {
					if(data.status=='complete'){						
						tooltip = '<div class="tooltiptopicevent" ><b> '+data.activity_name+' </b> : '+ data.employeename+' ('+data.leadname+') ('+data.type+')<br /><b>Status : </b>Completed<br /><b>Remarks : </b> '+ data.remarks + '<br /><b>Activity Owner :</b> '+ data.activity_owner + '<br /><b>Completed By : </b> '+ data.created_by + '<br /><b>Start Time : </b>'+ moment(data.start).format('DD-MM-YYYY HH:mm:ss') + '<br /><b>Duration : </b>'+ data.duration + '<br /></div>';
						$("body").append(tooltip);
					}else if(data.status=='pending'){
						tooltip = '<div class="tooltiptopicevent" ><b> '+data.activity_name+' </b> : '+ data.employeename+' ('+data.leadname+') ('+data.type+')<br /><b>Status : </b>Pending<br /><b>Remarks : </b> '+ data.remarks + '<br /><b>Activity Owner :</b> '+ data.activity_owner + '<br /><b>Scheduled By : </b> '+ data.created_by + '<br /><b>Start Time : </b>'+ moment(data.start).format('DD-MM-YYYY HH:mm:ss') + '<br /><b>Duration : </b>'+ data.duration + '<br /></div>';
						$("body").append(tooltip);
					}else if(data.status=='cancel'){
						tooltip = '<div class="tooltiptopicevent" ><b> '+data.activity_name+' </b> : '+ data.employeename+' ('+data.leadname+') ('+data.type+')<br /><b>Status : </b>Cancelled<br /><b>Remarks : </b> '+ data.remarks + '<br /><b>Activity Owner :</b> '+ data.activity_owner + '<br /><b>Cancelled By : </b> '+ data.created_by + '<br /><b>Start Time : </b>'+ moment(data.start).format('DD-MM-YYYY HH:mm:ss') + '<br /><b>Duration : </b>'+ data.duration + '<br /></div>';
						$("body").append(tooltip);
					}else if(data.status=='scheduled'){	
						tooltip = '<div class="tooltiptopicevent" ><b> '+data.activity_name+' </b> : '+ data.employeename+' ('+data.leadname+') ('+data.type+')<br /><b>Status : </b>Scheduled<br /><b>Remarks : </b> '+ data.remarks + '<br /><b>Activity Owner :</b> '+ data.activity_owner + '<br /><b>Scheduled By : </b> '+ data.created_by + '<br /><b>Start Time : </b>'+ moment(data.start).format('DD-MM-YYYY HH:mm:ss') + '<br /><b>Duration : </b>'+ data.duration + '<br /></div>';
						$("body").append(tooltip);
					}else if(data.status=='reschedule'){
						tooltip = '<div class="tooltiptopicevent" ><b> '+data.activity_name+' </b> : '+ data.employeename+' ('+data.leadname+') ('+data.type+')<br /><b>Status : </b>Rescheduled<br /><b>Remarks : </b> '+ data.remarks + '<br /><b>Activity Owner :</b> '+ data.activity_owner + '<br /><b>Rescheduled By : </b> '+ data.created_by + '<br /><b>Start Time : </b>'+ moment(data.start).format('DD-MM-YYYY HH:mm:ss') + '<br /><b>Duration : </b>'+ data.duration + '<br /></div>';
						$("body").append(tooltip);
					}
					
					$(this).mouseover(function (e) {
						$(this).css('z-index', 10000);
						$('.tooltiptopicevent').fadeIn('500');
						$('.tooltiptopicevent').fadeTo('10', 1.9);
					}).mousemove(function (e) {
						$('.tooltiptopicevent').css('top', e.pageY + 10);
						$('.tooltiptopicevent').css('left', e.pageX + 20);
					});
				},
				eventMouseout: function (data, event, view) {
					$(this).css('z-index', 8);
					$('.tooltiptopicevent').remove();
				},
				/* ---------------------------- Event click on calender ---------------------------------------- */
				eventClick: function(event,jsEvent, view) {	
					$('#process').val(event.lead_reminder_id);
					completed_value = event;
					if (event.url) {
						window.open(event.url);
						return false;
					}
					
					/* modal construct---------Start */
					if(event.status!='complete' && event.status!='cancel' && event.status!='reschedule'){
						console.log(event)
						CurrentDate = moment().format("YYYY-MM-DD");
						assigned_date = $.fullCalendar.formatDate( event.start, "YYYY-MM-DD");	
						$("#start_hidden").val(event.start._i);
						$("#member_id_hidden").val(event.person_id);
						$("#lead_hidden").val(event.leadname);
						$("#member_hidden").val(event.activity_owner);
						$("#contact_hidden").val(event.employeename);
						$("#activity_hidden").val(event.activity_name);
						/* $('#event_name').val(event.title);
						$("#createdBy").val(event.created_by);
						$("#personId").val(event.person_id);
						
						$("#conntype").val(event.activity_id);
						$("#contact3").val(event.leadempid); */
						$("#event_leadid_hidden").val(event.leadid);
						$("#event_person_id_hidden").val(event.person_id);
						$('#event_created_by_hiddden').val(event.created_by);
						$("#event_type_hidden").val(event.type);
						console.log(event.reminder_members)
						
						/* REQUIREMENT 19- START	29-08-2018	Manager	Calender	team calendar popups should be the same as the team task view pop up */
						$("#completed .newView").html('');
						var input={};
						input.leadid = event.leadid;
						/* input.leadempid = event.employeeid; */
						input.type = event.type;
						if(event.type == "unassociated"){
							input.leadempid = event.message_id;
						}else{
							input.leadempid = event.employeeid;
						}
						input.date = moment(event.start._i).format('YYYY-MM-DD HH:mm:ss');
						if(input.leadid == null){
							console.log("event.leadid not available. Please check with Developers");
							return;
						}
						$.ajax({
							type : "POST",
							url : "<?php echo site_url('sales_mytaskController/getAllActivitesOfLeadCustSup'); ?>",
							dataType : 'json',
							data : JSON.stringify(input),					
							cache : false,
							success : function(data){
								$("#completed .newView").html('');
								getAllActivites = data;
								/* Function added */
								getAllActivites.forEach(function (elm, i) {
									var selectedRowIndex = $.trim(event.table_name)+$.trim(event.row_id);
									var rowIndex = $.trim(elm.table_name)+$.trim(elm.row_id);
									if(selectedRowIndex == rowIndex){
										display(getAllActivites , i , '');
										document.getElementById('clickCounterNumber').value = i;
									}
								});
							},
							error: function(data){
								network_err_alert(data);
							}
						});
						/* REQUIREMENT 19- End */
						
						html =  `<div id="completed" class="modal fade" data-backdrop="static" data-keyboard="false">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <div class="modal-header">
											<a class="close" onclick="cancel()">×</a>
											<input type="hidden" id="event_id" class="form-control" value="`+event.id+`">
											<p class="hidden event_phone_hidden">`+event.phone+`</p>
											<input type="hidden" class="event_strt_date" value="`+event.start._i+`">
											
											<h4 class="modal-title">`+event.title+`</h4>
											<span title="Reschedule" onclick="reschedulepopup('`+event.id+`','`+event.title+`','`+event.activity_id+`','`+event.activity_name+`','`+event.activity_owner+`','`+event.leadname+`','`+event.leadid+`','`+event.employeename+`','`+event.employeeid+`','`+event.start._i+`','`+event.duration+`','`+event.remarks+`','`+event.addremtime+`','`+event.person_id+`','`+event.created_by+`','`+event.type+`','`+event.reminder_members+`')" class="glyphicon glyphicon-time icon-big"></span> 
										  </div>  
										  <div class="modal-body">
										  <div class="row">
											  <input type="hidden" id="clickCounterNumber" value="0"/>
											  <div class="newView"></div>
											  
										  </div>
										  <div class="row">
											  <div>
												<center>
													<span class="task_comp"><input type='checkbox' id='confirm_process1' onchange="confirm_process(1000, '`+event.start._i+`')"><label for='confirm_process1'>Task Completed</label></span>
													<input type="checkbox" id="process_cancel" onchange="cancel_process1()"> <label for="process_cancel">Cancel Event</label>
												</center>                 
											  </div>
										  </div>

											<div class="row" style="display:none;">
											  <div class="col-md-2">
												<label for="event_name">Event Name*</label>
											  </div>
											  <div class="col-md-4">
												<input type="text" id="event_name" class="form-control" value="`+event.title+`">
												<span class="error-alert"></span>
											  </div>
											  <div class="col-md-2" >           
												<label for="cmp_activity">Activity</label>
											  </div>
											  <div class="col-md-4" id="completed_choose">
												<select class="form-control" id="cmp_activity" disabled>
												<option value="`+event.activity_id+`">`+event.activity_name+`</option>
												</select>                     
												<span class="error-alert"></span>
											  </div>
											</div>
											<div class="row" style="display:none;">
											  <div class="col-md-1 padding-left" >                    
												<label for="cmp_lead">Lead</label>
											  </div>
											  <div class="col-md-3">
												<select class="form-control" id="cmp_lead" disabled>
												<option value="`+event.leadid+`">`+event.leadname+`</option>
												</select>
												<span class="error-alert"></span>
											  </div>
											  <div class="col-md-1 padding-left">                   
												<label for="cmp_contact">Contact</label>
											  </div>
											  <div class="col-md-3">
												<select class="form-control" id="cmp_contact" disabled>
												<option value="`+event.employeeid+`">`+event.employeename+`</option>
												</select>                     
												<span class="error-alert"></span>
											  </div>
											  <div class="col-md-1">        
												<label for="reminder_time1">Alert Before*</label>
											  </div>
											  <div class="col-md-3">
												<select class="form-control" id="reminder_time1" >
												<option value="" >Select</option>
												<option value="5">5 mins</option>
												<option value="15">15 mins</option>
												<option value="30">30 mins</option>
												</select>
												<span class="error-alert"></span>
											  </div>
											</div>
											<div class="row">
											  <div class="col-md-1" style="display:none;">                  
												<label for="cmp_start_date">Start date*</label>
											  </div>
											  <div class="col-md-3" style="display:none;">
												<input type="text" id="cmp_start_date" class="form-control" placeholder="DD-MM-YYYY" maxlength="10" value="`+$.fullCalendar.formatDate( event.start, "DD-MM-YYYY")+`" disabled>
												<span class="error-alert"></span>
											  </div>
											  <div class="col-md-1 padding-left" style="display:none;">                   
												<label for="cmp_start_time">Start Time*</label>
											  </div>
											  <div class="col-md-3" style="display:none;">
												<input type="text" id="cmp_start_time" class="form-control" placeholder="HH:MM" maxlength="5" value="`+$.fullCalendar.formatDate( event.start, "HH:mm")+`" disabled>  
												<span class="error-alert"></span>
											  </div>                        
											</div>
										   <div class="row process_task">
											<div class="col-md-1">
											</div>
												<div class="col-md-2">										
													<label for="cmp_note">Note*</label>
												</div>
												<div class="col-md-8">
													<textarea id="cmp_note" class="form-control" value=""></textarea>										
													<span class="error-alert"></span>
												</div>				
												<div class="col-md-1">
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
													<div class="col-md-1"></div>  
													<div class="col-md-2">                  
														<label for="completed_starts">Starts*</label>
													</div>                      
													<div class="col-md-7">
														<input type="hidden" id="personId" value="`+event.person_id+`">
														<input type="hidden" id="completed_type_hidden" value="`+event.type+`">
														<div class="form-group">
															<div class='input-group' id='completed_starts'>
																<input placeholder="Pick Start Time" type='text' id="completed_start" class="form-control" disabled />
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
												<div class="row act_dur">
													<div class="col-md-1"></div>  
													<div class="col-md-2">                  
														<label for="cmp_duration">Activity duration*</label>
													</div>                      
													<div class="col-md-8">
														<input type="text" id="cmp_duration" class="form-control" placeholder="HH:MM" value="`+event.duration+`" maxlength="5" readonly /> 
														<span class="error-alert"></span>
													</div>
													<div class="col-md-1"></div>
												</div>

											<div class="row rate_act">
												<div class="col-md-1"></div>                    
												<div class="col-md-2">                    
												  <label>Rating activity*</label>
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
											  </center>
												</div>
											</div>
										  </div>
										  <div class="modal-footer">
											<button type="button" class="btn btn-primary" id="save_task" onclick="processComplete('false')" >Save</button>
											<button type="button" class="btn btn-primary none" id="task_save" onclick="cSave_process()" >Save</button>
											<button type="button" class="btn btn-primary " id="process_complete" onclick="processComplete('true')" >Save &amp; Create</button>
											<button type="button" class="btn btn-default cancel_close" style="display:none;" onclick="cancel()">Close</button>
											<button type="button" class="btn btn-default cancel_cancel" onclick="cancel()">Cancel</button>
										  </div> 
										</div> 
									  </div>
									</div>` 
						/*  modalWindow ends here*/
						
						$("#editEvent").html(html);
						$("#completed").modal("show");
						/* ---------------------------------------------------------------------------------------
						******************* checking loggedin user id and event owner id****************************
						------------------------------------------------------------------------------------------*/	
						if("<?php echo $this->session->userdata('uid');?>" == event.person_id && assigned_date < CurrentDate || assigned_date == CurrentDate){
							$(".task_comp").show();
						}else{
							$(".task_comp").hide();
						}
						
					}
					/* ---------modal construct---------End----------- */
					/* ---change the border color just for fun-------- */
			
					$(this).css('border-color', 'red');
					$("#rating_activity .glyphicon.glyphicon-star").css("color",'#d2cfcf');
			
					$(function() {
						$( "#cmp_start_date" ).datepicker({
						dateFormat: 'dd-mm-yy', 
						maxDate: new Date()
						});
					});
				}
			});
		}
    });
	}
});
		$.ajax({
            type : "POST",
            url : "<?php echo site_url('sales_mytaskController/get_atctivity'); ?>",
            dataType : 'json',					
            cache : false,
            success : function(data)	{
              if(error_handler(data)){
                    return;
                }
            	var select1 = $("#activity1"), options1 = "<option value=''>Choose Activity</option>";
            	var select2 = $("#activity"), options2 = "<option value=''>Choose Activity</option>";
            	select1.empty();
            	select2.empty();
            	for(var i=0;i<data.length; i++)	{
            		if(data[i].lookup_value == 'Outgoing Call' ||data[i].lookup_value == 'Outgoing SMS' ||data[i].lookup_value == 'Outgoing E-Mail'||data[i].lookup_value == 'Meeting'||data[i].lookup_value == 'Skype call'){
						options1 += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";
					}
					options2 += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";	
            	}
            	select1.append(options1);
            	select2.append(options2);
            }
        });
        
        
    }

	function confirm_process(fotmtype, time){
		if(fotmtype == "add"){
			/* --------------------------------------------------- */
			$("#contact").closest(".row").hide();
			$("#email_grant").hide();
			$("#lead, #customer, #opportunity, #internal").val("");		
			$("#rating, .numberClass").hide();
			$("#lead, #internal, #customer").typeahead("destroy");
			/* -------------------------------------------------- */
			if($("#confirm_processAdd").prop('checked')==true){
				$('#actve_duration').data("DateTimePicker").date('00:00');
				$("#task_completed").show();
				$("#task_create").hide();
				$(".error-alert").text("");
				$(".rating_task").show();
				$(".team_mem_show").hide();
				$(".lead_row").hide();
				$(".cust_row").hide();
				$(".opp_row").hide();
				$(".inter_row").hide();
				$(".email_alert").hide();
				$(".numberCall").show();
				$("#rating_activity_add .glyphicon.glyphicon-star").css("color",'#d2cfcf');
			    $("#next_act,#activity").show();
				//$(".numberClass").show();
			    $("#activity1,#prev_act,.reminder_time_hide").hide();
			    $("#addmodal #actve_duration,#task_type,#contact").val("");
				
				$(".show_all").hide();
				$(".team_mem_show1").hide();
				$(".ticket_main").hide();
				$(".ticket_selection").hide();
				
			    $(".rating_msg ").text("");
			    $(".email_section").hide();
			    $("#email_list").html("");
				$('#timepicker1').show();
				$('#timepicker').hide();
				$("#startDateTimePicker").data("DateTimePicker").clear();
				$("#add-modal-title").text("Log Completed Task");
				$('#add-modal-title').addClass('animated rubberBand').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
			    	$(this).removeClass('animated rubberBand');
				});
			    rating1 =-1;
			} else {
				$('#actve_duration').data("DateTimePicker").date('00:00');
				$("#task_completed").show();
				$("#task_create").hide();
				$(".error-alert").text("");
				$(".team_mem_show").show();
				$("#rating_activity_add .glyphicon.glyphicon-star").css("color",'#d2cfcf');
			    $("#rating,#next_act,#activity").hide();
			    $("#prev_act,#activity1,.reminder_time_hide").show();
				$("#addmodal #actve_duration,#task_type,#contact").val("");
				$(".numberClass").hide();
			    $(".numberCall").hide();
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
				$("#check_all").prop("checked", false);
				$(".ticket_main").hide();
				$(".ticket_selection").hide();
				
				$('#timepicker').show();
				$('#timepicker1').hide();
				$("#startDateTimePicker1").data("DateTimePicker").clear();
				$("#add-modal-title").text("Create Event");
				$('#add-modal-title').addClass('animated rubberBand').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
			    	$(this).removeClass('animated rubberBand');
				});

			    rating1 =0;
			}				
		}
		if(fotmtype == "1000"){
				time =  moment(time).format('lll')
				
				$('#completed_starts').datetimepicker({
					useCurrent: false,
					ignoreReadonly: true,
					allowInputToggle:true,
					format: 'lll',
					minDate: new Date()
				});
				$('#completed_starts input').val(time)
				
				$("#completed_starts").on("dp.change", function (selected) {
					$('#completed_starts').data("DateTimePicker").minDate(new Date());
					$('#completed_starts').data("DateTimePicker").maxDate(time);
				});
				
				$("#cmp_duration").datetimepicker({
					ignoreReadonly:true,
					allowInputToggle:true,
					format:'H:m',
				});
			if($("#confirm_process1").prop('checked')==true){
				if("<?php echo $this->session->userdata('uid');?>" == $("#member_id_hidden").val() && assigned_date < CurrentDate){
					$("#completed_Modal").modal("show");			
				}else{				
					$(".error-alert").text("");
					$("#rating_activity .glyphicon.glyphicon-star").css("color",'#d2cfcf');
					$(".rating_msg").text("");	
					$("#completed_rating").show();
					$("#cmp_duration").show();				
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
				$(".error-alert").text("");
				$("#process_complete").hide();
				$("#rating_activity .glyphicon.glyphicon-star").css("color",'#d2cfcf');
				$(".rating_msg").text("");				
				$("#completed_rating").hide();
				$("#cmp_duration").hide();
				rating1 =0;
			}
		}

	}
	/* -------------------------------------------------------------------------------------------------------
	***************************** raiting function *************************************
	----------------------------------------------------------------------------------------------------------- */
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
	
	/* -------------------------------------------------------------------------------------------------------
	***************************** Add event popup display *************************************
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
							contactArray.push({"con": contact[key][i].trim(), "type": "phone"})
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
	
	function addpopup()	{
		time_overlap = false;
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
		/* -----------------ajax call to get contact number on selecting lead name -------------------------*/
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
				$.ajax({ /* ajax call for get rep_names */
						type : "POST",
						url : "<?php echo site_url('sales_mytaskController/get_employeeNumbers'); ?>",
						data:JSON.stringify(contactObj),
						dataType : 'json',
						cache : false,
						success : function(data){
							console.log(data)
						
						if(error_handler(data)){
							return;
						}
						var number = contactformat(data[0].contact_number);				
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
				
				$("#email_list").append("<li id="+ datum.user_id +"><span>"+ datum.user_name+" </span><a style='color:red;' class='glyphicon glyphicon-remove-sign' onclick='del(\""+datum.user_id+"\", "+email_members+")'></a></li>");
				$('#email_members').closest("div").find("span.error-alert").text("");
				$('#email_members').val("");
			} else {
				alert("Can't add more than 12 Users");
				$('#email_members').closest("div").find("span.error-alert").text("Can't add more than 12 Users");
				return;
			}
		});
		
		/* ----------------------------------------------------- */
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
			$("#event_nameadd").closest("div").find("span").text("No special character.");
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
			$("#start_date1").closest("div").siblings("span").text("Start date is required.");
			return;	
		} else 	{
			date = moment($.trim($("#start_date1").val()), 'lll').format('YYYY-MM-DD HH:mm:ss');
			$("#start_date1").closest("#startDateTimePicker1").siblings("span").text("");
		}
		
		if($.trim($("#actve_duration").val()) == ""){
			$("#actve_duration").closest("div").find(".error-alert").text("Specify estimated activity duration.");
			return;	
		} else{
			var duration1 = $.trim($("#actve_duration").val()).split(":");
			if(duration1[0] == "0" && duration1[1] == "0"){
				$("#actve_duration").closest("div").find(".error-alert").text("Select valid duration time.");
				return;
			}else{
				duration = moment($.trim($("#actve_duration").val()), 'H [Hrs] m [mins] ss [sec]').format('HH:mm:ss');
				$("#actve_duration").closest("#actve_duration").siblings(".error-alert").text("");
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
			console.log(teamName_arr)
			if(team_arr.length > 0){
				$("#product_err").find("span").text("");
			}else{
				$("#product_err").find("span").text("Please Select Atleast one user");
				return;
			}
		}
		
		addEventObj.event_title = $.trim($("#event_nameadd").val());
		addEventObj.event_activity = $.trim($("#activity").val());
		addEventObj.event_contact = $.trim($("#contact").val());
		addEventObj.event_start_date = date;
		addEventObj.cmp_phone = $('#number').val();
		addEventObj.cmp_duration = duration;
		addEventObj.event_rating = rating1;
		addEventObj.camp_note = $.trim($("#note").val()).replace(/\n|\r/g, " ");
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
			addEventObj.event_members =	event_mem;
			addEventObj.event_member_name =	event_mem_name;
		}
		if(time_overlap == false){
			overlap(addEventObj.event_start_date,addEventObj.cmp_duration);
			return;
		}
		loaderShow();
		$.ajax({		/* AJAX	call for When task completed button	clicked	 */
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/add_mytaskcomplete'); ?>",
			dataType : 'json',
			data : JSON.stringify(addEventObj),					
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
				if(data==true) {
					if(a == 1){
						pageload(1);
					}else{
						pageload();
					}
					cancel();									
				}else{
					alert("There was an error adding the contact");
				}

			}
		});
	}
	/* --------------------------------------------------------------------------------------------------------------
	**************************** typeahead function Changed ****************************************
	----------------------------------------------------------------------------------------------------------------- */
	function typeChanged() {
		var typeSelected = $("#type").val();
		if (typeSelected == 'lead') {
			/* hide following fields - contact, number, lead and opportunity			 */
			$("#leadGroup, #contactGroup, #numberGroup, #oppGroup").hide();
			/* show lead text field, contact text field, number text field */
			$("#leadGroup, #contactGroup, #numberGroup").show();
		}
		else if (typeSelected == 'opp') {
			//hide following fields - contact, number, lead and opportunity			
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
	
	/* --------------------------------------------------------------------------------------------------------------
	**************************** delete  function not using ****************************************
	----------------------------------------------------------------------------------------------------------------- */
	function del(id, array){
		$("#"+id).remove();
		var index = array.indexOf(id);
		if (index >= 0) {
			array.splice( index, 1 );	
		}
	}

	/* --------------------------------------------------------------------------------------------------------------
	**************************** save button click -- When Completed Task checkbox is not checkex ****************************************
	----------------------------------------------------------------------------------------------------------------- */
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
			console.log(teamName_arr)
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
			addEventObj.event_members =	event_mem;
			addEventObj.event_member_name =	event_mem_name;
		}
		addEventObj.reminder_members = 		reminder_members;
		addEventObj.event_title = $.trim($("#event_nameadd").val());
		addEventObj.event_activity = $.trim($("#activity1").val());
		addEventObj.event_contact = $.trim($("#contact").val());	
		addEventObj.event_start_date = date;
		addEventObj.event_contact_name = 	$.trim($("#contact option:selected").text());
		addEventObj.event_activity_name = 	$.trim($("#activity1 option:selected").text());
		addEventObj.reminder_time=reminder_time;
		addEventObj.camp_note = $.trim($("#note").val()).replace(/\n|\r/g, " ");
		addEventObj.number = $.trim($("#number").val());
		addEventObj.active_duration= duration;
		addEventObj.email_alert = $("#email_check").prop("checked");
		console.log(addEventObj);
		if(time_overlap == false){
			overlap(addEventObj.event_start_date,addEventObj.active_duration);
			return;
		}
		disable_button('#prev_act', true);
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
				
				disable_button('#prev_act', false);
				if (data==true) {
					if(a == 1){
						pageload(1);
					}else{
						pageload();
					}					
					cancel();									
				} else {
					alert("There was an error adding the contact");
				}
			}
		});
	}

	/* function selrow(obj){} removed by tapash ---not used any where*/
	
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
		addEventObj.lead_reminder_id=$('#event_id').val();
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/cancelEvent'); ?>",
			dataType : 'json',
			data : JSON.stringify(addEventObj),					
			cache : false,
			success : function(data){
				if(error_handler(data)){
                    return;
                }
				cancel();
				pageload();
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
	/* -------------------------------------------------------------------
	***********************************************************************
	------------------------------------------------------------------ */
	function processComplete(showAddModel){
		var addEventObj ={};
		var time=$(".event_strt_date").val();
		time = moment(time).format("lll");
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
			if($.trim($("#completed_starts input").val()) == ""){
				$("#completed_starts").siblings(".error-alert").text("Start date is required.");
				return;	
			} else 	{
				$("#completed_starts").siblings(".error-alert").text("");
				addEventObj.completed_start_date = moment($.trim($("#completed_starts input").val()), 'lll').format('YYYY-MM-DD');
				addEventObj.completed_start_time = moment($.trim($("#completed_starts input").val()), 'lll').format('HH:mm:ss');
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
		}else{			
			addEventObj.completed_start_date = moment(time, 'lll').format('YYYY-MM-DD');
			addEventObj.completed_start_time = moment(time, 'lll').format('HH:mm:ss');
		}
			
			addEventObj.event_title = 		$.trim($("#event_name").val());			/* -- event title */
			addEventObj.event_rating = 		rating1;								/* -- event rating	 */
			addEventObj.event_start_date = 	$.trim($("#cmp_start_date").val());		/* -- event start date */
			addEventObj.cmp_start_time = 	$.trim($("#cmp_start_time").val());		/* -- event start time */
			addEventObj.personId = 			$.trim($("#personId").val());			/* -- event start time */
			addEventObj.type = 				$.trim($("#completed_type_hidden").val());		/* -- event start time */
			addEventObj.cmp_end_time = 		cmp_end_time;							/* -- event duration */
			addEventObj.camp_note = 		$.trim($("#cmp_note").val()).replace(/\n|\r/g, " ");			/* -- event remarks */
			addEventObj.lead_reminder_id = 	$.trim($("#event_id").val());			/* -- lead_reminder_id */
			addEventObj.cmp_activity = 		$("#cmp_activity").val();				/* -- activity_id */
			addEventObj.cmp_contact = 		$("#cmp_contact").val();				/* -- employee id */
			addEventObj.cmp_lead = 			$("#cmp_lead").val();					/* -- lead id */
			addEventObj.cmp_phone = 		getMobileNumber($('.event_phone_hidden').text());		/* -- phone number */
		
		
		console.log(addEventObj)
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
				if (data == false){
					alert("There was an error adding the contact");
				}else{
					if (showAddModel =='true'){
						cancel();
						pageload(1);
					}else{
						pageload();
						cancel();												
					}
				}
  			 }
		});
	}
	/* ----------------------------------------------------------------------
	**************************** Reschedule Popup Show with Value *****************************************
	---------------------------------------------------------------------- */
	function reschedulepopup(	eventID, 
								eventName, 
								activity_id, 
								activity_name,
								activity_owner,
								leadname,
								leadid,
								employeename,
								employeeid,
								start,
								duration,
								remarks,
								addremtime,
								person_id,
								created_by,
								type,
								reminder_members,
							){
		
		time_overlap = false;
		$("#event_id_hidden").val(eventID);							
		$("#event_leadid_hidden").val(leadid);
		$("#event_person_id_hidden").val(person_id);
		$('#event_created_by_hiddden').val(created_by);
		$("#event_type_hidden").val(type);

		
		$("#completed").modal("hide");
		$('#reschedule').modal('show');
		$('#event_name2').text(eventName);
		
		$("#act21 option").remove();
		$("#act21").append("<option value='"+activity_id+"'>"+activity_name+"</option>");
		$("#act21").prop("disabled", true);
		
		if(type=="lead"){			
			$('#lead21_name').text("Lead*");
		}else if(type=="customer"){
			$('#lead21_name').text("Customer*");
		}else if(type=="opportunity"){
			$('#lead21_name').text("Opportunity*");
		}else if(type=="internal"){
			$('#lead21_name').text("Internal*");
		}else if(obj.type=="support"){
			$('#lead2_name').text("Ticket*");
		}
		
		$("#lead21 option").remove();
		$("#lead21").append("<option value='"+leadid+"'>"+leadname+"</option>");
		$("#lead21").prop("disabled", true);
		
		$("#contact21 option").remove();
		$("#contact21").append("<option value='"+employeeid+"'>"+employeename+"</option>");
		$("#contact21").prop("disabled", true);
		
		var startTime = moment(start).format('DD-MM-YYYY');
		$("#start_date2").val(moment(start).format('lll'));
		
		var durationTime = moment(duration, 'HHmmss');
		$("#actve_duration2").val(durationTime.format('HH:mm'));
		
		$("#reminder_time2").val(addremtime);
		$("#note2").val(remarks);
		
		
		var data = {};
		data.remmem = reminder_members;
		$.ajax({ 	/* //ajax call for edit pending task of lead_activity */
			type : "POST",
			url : "<?php echo site_url('sales_mytaskController/get_editable_emails'); ?>",	
			data: JSON.stringify(data),     
			dataType : 'json',
			cache : false,
			success : function(data){
				console.log(data)
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
					});
					if(match==0){
						$('#email_search').val("");
						return;
					}
					if ($("#email_list_edit li").length <= 12) {
						reminder_members.push(datum.user_id);
						
						/*meeting_members = [];
						sales_mytask_selected_user_attendees = [];
						sales_mytask_selected_lead_attendees = [];*/
						$("#email_list_edit").append("<li id="+ datum.user_id+"><span>"+ datum.user_name+" </span><a style='color:red;' class='glyphicon glyphicon-remove-sign' onclick='del(\""+datum.user_id+"\", reminder_members)'></a></li>");
							$('#email_search').closest("div").find("span.error-alert").text("");
							$('#email_search').val("");	
						
					} else {
						alert("Can't add more than 12 Users");
						$('#email_search').closest("div").find("span.error-alert").text("Can't add more than 12 Users");
						return;
					}
					console.log(email_data.sendSavedID[0])
					console.log(reminder_members)
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
		var oldobj={};
			oldobj.startdate= moment(start).format('lll');
			oldobj.duration= durationTime.format('HH:mm');
			oldobj.alertBefore= addremtime;
			oldobj.note= remarks;
			
		var newobj={};
			newobj.startdate= moment($.trim($("#start_date2").val()), 'lll').format('lll');
			newobj.duration= moment($.trim($("#actve_duration2").val()), 'H [Hrs] m [mins]').format('HH:mm');
			newobj.alertBefore= $.trim($('#reminder_time2').val());
			newobj.note= $.trim($('#note2').val());
			
			/* newobj.startdate="";
			newobj.duration= "";
			newobj.alertBefore= "";
			newobj.note= ""; */
		
		
		$("#actve_duration2").on("dp.change", function (e) {
			newobj.duration= moment($.trim($("#actve_duration2").val()), 'H [Hrs] m [mins]').format('HH:mm');
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
			console.log(oldObj[k] +"------"+ newObj[k])
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
	/* ----------------------------------------------------------------
	*************************** Submitting reschedule data **********************************
	-----------------------------------------------------------------------	*/
	function reschedule_popup()	{
		var addEventObj={};
		if($.trim($("#start_date2").val()) == ""){
			$("#start_date2").closest("div").siblings(".error-alert").text("Start date is required.");
			return;	
		} else 	{
			date = moment($.trim($("#start_date2").val()), 'lll').format('YYYY-MM-DD HH:mm:ss');
			$("#start_date2").closest("div").siblings(".error-alert").text("");
		}
		
		if($.trim($("#actve_duration2").val()) == ""){
			$("#actve_duration2").closest("div").find(".error-alert").text("Specify activity duration.");
			return;	
		}else{
			var duration1 = $.trim($("#actve_duration2").val()).split(":");
			if(duration1[0] == "0" && duration1[1] == "0"){
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
		addEventObj.lead_reminder_id = 	$.trim($("#event_id_hidden").val()); 			/* ---	event id */
		addEventObj.event_name = 		$.trim($("#event_name").val());					/* ---	event Name */
		addEventObj.conntype = 			$.trim($("#act21").val());						/* ---	activity id */
		addEventObj.act2 = 				$.trim($('#act21').text());						/* ---	activity Name */
		addEventObj.lead3 = 			$("#event_leadid_hidden").val();		/* ---	lead id */
		addEventObj.contact3 = 			$.trim($('#contact21').val()); 					/* ---	employee id */
		addEventObj.start_date2 = 		$.trim(date);									/* ---	start date */ 
		addEventObj.actve_duration2 = 	$.trim(duration);								/* ---	duration */
		addEventObj.note2 = 			$.trim($('#note2').val()).replace(/\n|\r/g, " ");						/* ---	remarks */
		addEventObj.phone1 = 			getMobileNumber($('.event_phone_hidden').text()); 	/* ---	phone number */
		addEventObj.reminder_time = 	$.trim($('#reminder_time2').val());				/* ---	reminder time */
		addEventObj.created_by = 		$.trim($('#event_created_by_hiddden').val());	/* ---	created by */
		addEventObj.person_id = 		$.trim($("#event_person_id_hidden").val());		/* ---	person id */
		addEventObj.type = 				$.trim($("#event_type_hidden").val());			/* ---	type */
		if(time_overlap == false){
			overlap(addEventObj.start_date2,addEventObj.actve_duration2);
			return;
		}
		/* ajax call for rescdule activity */
		
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
					pageload();
					cancel();                 
				}else{
					alert("There was an error adding the contact");
				}
			}
		});

	}

	
	
	/* ----------------Next-Previous button for view activity only visible for open section -----Starts---------------------- */
		
		var getAllActivites = [];
		function display(array , j, state){
			var array1=[];
			if(state == "open" || state == ""){
				var nw = '';
				var old = '<input class="btn pull-left" type="button"  onclick="older()" value="Previous"/> ';
				if(j == (array.length - 1)){
					old =''
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
			html =	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Activity</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+array[j].lookup_value+'</label>'+													
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Activity Owner</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+array[j].person_name+'</label>'+													
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>'+array[j].type+'</b></label>'+
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
							'<label><b>Contact</b></label>'+
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
							'<label><b>Phone Number</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+phone_row+'</label>'+																		
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Start</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+moment(array[j].meeting_start).format('DD-MM-YYYY HH:mm:ss')+'</label>'+
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Duration</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+duration+'</label>'+
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Closed By</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+(array[j].status == 'pending' ? '' : array[j].created_by)+'</label>'+
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Scheduled By </b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+array[j].created_by+'</label>'+
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Status</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+array[j].status+'</label>'+
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html +=	'<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Rating</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+rating+'</label>'+
						'</div>'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'</div>';
			html += '<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Note</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label style="word-wrap: break-word;">'+array[j].remarks+'</label>'+
						'</div>'+
					'</div>';
			if(array[j].cancel_remarks != "" && array[j].hasOwnProperty('cancel_remarks')){
				html += '<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Cancellation Remarks</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+array[j].cancel_remarks+'</label>'+
						'</div>'+
					'</div>';
			}
			var url_path = "<?php echo base_url(); ?>uploads/";
			if(array[j].conntype == 'CALL594ce66d07b45' || array[j].conntype == 'CALL594ce66d07b46' || array[j].conntype == 'ME594ce66d07b9fd4'){
				if(array[j].hasOwnProperty('path') && array[j].path != null){
					html += '<div class="row">'+
						'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
						'<div class="col-md-3 col-sm-3 col-xs-3">'+										
							'<label><b>Content</b></label>'+
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
							'<label><b>Content</b></label>'+
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
							'<label><b>Content</b></label>'+
						'</div>'+
						'<div class="col-md-5 col-sm-5 col-xs-5">'+
							'<label>'+array[j].path == null ? "" : array[j].path +'</label>'+
						'</div>'+
					'</div>';
				}else{
					html += '<div class="row">'+
					'<div class="col-md-2 col-sm-1 col-xs-1" ></div>'+
					'<div class="col-md-3 col-sm-3 col-xs-3">'+										
						'<label><b>Content</b></label>'+
					'</div>'+
					'<div class="col-md-5 col-sm-5 col-xs-5">'+
						'<label></label>'+
					'</div>'+
					'</div>';
				}
			}
			
					
			html += 	'<div class="row">'+
							'<center>'+
								old+
								nw +
							'</center>'+
						'</div>';
			$("#completed .newView").append(html + '<hr>');
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
	</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">  
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
						  <div>    
							<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-toggle="tooltip" data-placement="bottom" data-html="true" title="View your tasks for the day, week and month. Click the <img src='<?php echo site_url(); ?>images/new/Plus_Off.png' width='20px' height='20px' /> button on the top right of this page to add a new task to this calendar."/>
						  </div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('My_Calendar', 'video_body', 'Executive')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
							<h2 >My Calendar</h2>	<?php //echo phpversion();?>
					</div>
					<div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
						<div class="addBtns">
							<a class="addPlus" onclick="addpopup()"><img src="<?php echo site_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/></a>
						</div>
						<input type="hidden" id="start_hidden" />
						<input type="hidden" id="member_id_hidden" />
						<input type="hidden" id="lead_hidden" />
						<input type="hidden" id="member_hidden" />
						<input type="hidden" id="activity_hidden" />
						<input type="hidden" id="contact_hidden" />
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="row legend_row">
					<div class="col-md-2" style="margin-left: 54px;">
					</div>
					<div class="col-md-1 legend">
					</div>
					<div class="col-md-1 legend_name">
						Rescheduled
					</div>
					<div class="col-md-1 legend1">
					</div>
					<div class="col-md-1 legend_name">
						Scheduled
					</div>
					<div class="col-md-1 legend2">
					</div>
					<div class="col-md-1 legend_name">
						Pending 
					</div>
					<div class="col-md-1 legend3">
					</div>
					<div class="col-md-1 legend_name">
						Canceled  
					</div>
					<div class="col-md-1 legend4">
					</div>
					<div class="col-md-1 legend_name">
						Completed  
					</div>
				</div> 
				<div class="row">
					<div id='calendar'></div>
				</div>
			</div>
			<div id="completed_data_modal" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form class="form" action="#" method="post" name="adminClient">
							<div class="modal-header ">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Complete Activity<label id="complete_event_name2"></label></h4>
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
								<button type="button" class="btn btn-primary" onclick="completed_popup('true')" >Save &amp; Create</button>							
								<button type="button" class="btn btn-default" onclick="cancel()">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="reschedule" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup2" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header ">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Reschedule Activity for <label id="event_name2"></label></h4>
							</div>
							<div class="modal-body">	
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2" >
										<label for="act21">Activity</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<select id="act21" class="form-control" disabled></select>	
									</div>	
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div> 
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2" >
										<label  id="lead21_name">Lead*</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<select id="lead21" class="form-control" disabled></select>	
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
								</div>
								<div class="row">
									<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2" >
										<label for="contact21">Contact*</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<select id="contact21" class="form-control" disabled></select>	
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
								<div class="row">
								<div class="col-md-1 col-sm-1 col-xs-1" ></div>
									<div class="col-md-2 col-sm-2 col-xs-2">										
										<label for="actve_duration2">Activity duration*</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" id="actve_duration2" class="form-control" placeholder="HH:MM"  maxlength="5" readonly />									
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
										<input id='email_search' class="form-control"></input>
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
							<div class="modal-footer" id="previous_btn">
								<input type="hidden" id="event_id_hidden">
								<input type="hidden" id="event_leadid_hidden">
								<input type="hidden" id="event_person_id_hidden">
								<input type="hidden" id="event_created_by_hiddden">
								<input type="hidden" id="event_type_hidden">
								<!-------------------------->
								<input type="hidden" id="reschd1">
								<input type="hidden" id="phone1">           
								<!--<input type="hidden" id="leadCustId">removed by tapash-- not used anywhere   -->
								<input type="hidden" id="createdBy">
								<input type="hidden" id="event_name">
								<input type="hidden" id="lemp_id">
								<!--<input type="hidden" id="type">-->
								<input type="hidden" id="act22">
								<input type="hidden" id="contact3">
								<!--<input type="hidden" id="mangName"> --removed by tapash not used any where
								<input type="hidden" id="start_date2">
								<input type="hidden" id="lead2">
								<input type="hidden" id="contact2">
								<input type="hidden" id="empl">
								<input type="hidden" id="act2" class="form-control">-->	
								<input type="hidden" id="lead3" class="form-control">
								
								<button type="button" class="btn btn-primary" id="reschedule_save_bnt" onclick="reschedule_popup()" disabled>Save</button>
								<button type="button" class="btn btn-default" onclick="cancel()">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		
			<div id="editEvent"></div>					
			<div id="addmodal" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
					<div class="modal-content mytask_add_modal">
						<form id="addpopup" class="form" action="#" method="post" name="adminClient">
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
									<input type="hidden" name="process"  id='process'>
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
									<div class="col-md-2"></div>
								</div>
								<div class="row opp_row none">
									<div id="oppGroup">
										<div class="col-md-1"></div>
										<div class="col-md-2">
											<label for="lead"><b>Opportunity*</b></label>
										</div>
										<div class="col-md-8">
											<div id="oppField">
										<!-- 		<input type="text" id="opportunity" class="form-control" placeholder="Enter Opportunity:"></input> -->
										<select class="form-control" id="opportunity" onchange="get_opp()"></select>

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
										<label for="contact">Contact*</label>
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
										<input type="text" id="actve_duration" class="form-control myTextInput" placeholder="HH:MM"  maxlength="5" readonly />			
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row reminder_time_hide" >
									<div class="col-md-1"></div>
									<div class="col-md-2">
										<label for="reminder_time" >Alert Before*</label>
									</div>
									<div class="col-md-8">
										<select class="form-control" id="reminder_time" >
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
							<div class="modal-footer" id="previous_btn">
							<input type="hidden" id="phone2"/>
								<button type="button" class="btn btn-primary" id="prev_act" onclick="addevent()" >Save</button>
								<button type="button" class="btn btn-primary" style="display:none" id="next_act" onclick="addevent1()" >Save</button>
								<button type="button" class="btn btn-primary" id="task_create" onclick="addevent(1)" >Save &amp; Create</button>
								<button type="button" class="btn btn-primary none" id="task_completed" onclick="addevent1(1)" >Save &amp; Create</button>
								<button type="button" class="btn btn-default" onclick="cancel()">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>		
			<?php require 'footer.php' ?>

	</body>
</html>
