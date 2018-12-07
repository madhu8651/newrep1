<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require 'scriptfiles.php' ?>

	<script>

	$(document).ready(function(){	
		pageload();	
		$("#stateChangeDate").closest('div').datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY HH:mm:ss',
			minDate: moment(),
		});
		
		$("#stateChangeActivityduration").find('.form-control').datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'HH:mm',
			defaultDate: new Date().setHours(0,0,0),
		});
	});
	var finalArray = {};
	function assign_btn(){
		var flagchk=0;
		finalArray['leads'] = [];
		$("#tablebody tr input[type=checkbox]:checked").each(function () {
			/*	$("#modalstart1").modal("show");*/
				finalArray['leads'].push($(this).attr('id'));
				
				flagchk=1;
		});
		if(flagchk==0){
			$.alert({
				title: 'L Connectt',
				content: 'Need to select at least one lead to proceed further.',
				closeIcon: true,
				closeIconClass: 'fa fa-close',
			});
			return;	
		}else{
			$("#reopenLeads").modal("show");
		}    	
	}

	function closeReopenLeads(){
		$("#reopenLeads").modal("hide");
		finalArray = {};
		$("#ReopenLeadsRemarks").val("");
		$("#existingExecutive").prop('checked',false)
	}

	var arr={};
	var managers = {};
	function assign_date(){
		/*if(idval!=''){
			$("#modalstart1").modal("show");
		}*/
		/* ----- rowner mowner ----------Addition -- Assign to existing executive checkbox added------------------ */
		
		var remarks = $.trim($("#ReopenLeadsRemarks").val());
		finalArray.assign = false;
		if($("#existingExecutive").prop('checked') == true){
			finalArray.assign = true;
		}
		if(remarks == "" ){
			$("#ReopenLeadsRemarks").next(".error-alert").text("Remarks is required");
			$("#ReopenLeadsRemarks").focus();
			return;
		}else if(!comment_validation(remarks)){
			$("#ReopenLeadsRemarks").next(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
			$("#ReopenLeadsRemarks").focus();
			return;
		}else{
			$("#ReopenLeadsRemarks").next(".error-alert").text("");
		}		
		finalArray.remarks = remarks;
		var reOpen=[];
		finalArray.leads.forEach(function(element) {
			var tempvar = {};
			tempvar.rowner = "";
			tempvar.mowner = "";
			closeLostData.forEach(function(all){
				if(all.leadid == element){
					tempvar.rowner = all.rowner;
					tempvar.mowner = all.mowner;
				}
			})
			
			tempvar.leadId = element;
			tempvar.remarks = finalArray.remarks;
			tempvar.assign = finalArray.assign;
			tempvar.reopen = true;
			reOpen.push(tempvar);
		});
		/* ---------------Addition -- Assign to existing executive checkbox added------------------ */
		
		
		var multipl2="";
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/get_managerlist_reassign1');?>",
			dataType:'json',
			data:JSON.stringify(reOpen),
			success: function(data) {
				if(error_handler(data)){
						return;
				}
				if(data==1){
					closeReopenLeads();
					$.alert({
						title: 'L Connectt',
						content: 'Selected lead(s) has been reopened successfully.',
						closeIcon: true,
						closeIconClass: 'fa fa-close',
					});
					pageload();
				}else{
					$.alert({
						title: 'L Connectt',
						content: 'Some thing went wrong.',
						closeIcon: true,
						closeIconClass: 'fa fa-close',
					});
				}
				
				/*managers = data;
				for(var i=0;i<data.length; i++){
					if(data[i].sales_module=='0' && data[i].manager_module!='0'){
						multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'">'+data[i].user_name+' (Manager)<label></li>';
					}
					if(data[i].sales_module!='0' && data[i].manager_module=='0'){
						multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'">'+data[i].user_name+' (Sales)<label></li>';
					}
					if(data[i].sales_module!='0' && data[i].manager_module!='0'){
						multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'">'+data[i].user_name+' (Manager)<label></li>';
						multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'">'+data[i].user_name+' (Sales)<label></li>';
					}

				}
				$(".multiselect2 ul").empty();
				$(".multiselect2 ul").append(multipl2);*/
			}
		});
			
	}
		

	function assign_save() {
		$('#modalstart1').modal('hide');

		var lead_ids = [];
		finalArray['users'] = [];

		$(".mgrlist_sales, .mgrlist_manager").each(function(){
			if($(this).prop('checked')== true){
				var localObj = {};
				localObj['to_user_id'] = $(this).attr('id');
				localObj['module'] = $(this).val();
				finalArray['users'].push(localObj);
			}
		});
		$("#tablebody tr input:checkbox").each(function () {   
			if($(this).prop("checked")==true){
				$("#modalstart1").modal("show");
				lead_ids.push($(this).attr('id'));
			}
		});
		if (finalArray['users'].count == 0) {
			$.alert({
				title: 'L Connectt',
				content: 'Select a user to assign.',
				closeIcon: true,
				closeIconClass: 'fa fa-close',
			});
			return;
		} else if (finalArray['leads'].count == 0)	{
			/* alert('Select Lead(s) to assign'); */
			$.alert({
				title: 'L Connectt',
				content: 'Select Lead(s) to assign',
				closeIcon: true,
				closeIconClass: 'fa fa-close',
			});
		}
		$.ajax({
				type: "POST",
				url: "<?php echo site_url('manager_leadController/reassign_lost'); ?>",
				data:JSON.stringify(finalArray),
				dataType:'json',
				success: function(data) {
					if(error_handler(data)){
							return;
					}
					if(data==1){
						$('#modalstart1').modal('hide');
						$('.form-control').val("");         		
					}
					pageload();               
				}
		});

	}
	
	
	/* ------------------Click on the change status button on the view popup window ---------------- */
	
	var selectedrow;
	function assign_btn2() {
		
		$("#stateChangeActivityduration input[type=text]").val('00:00')
		loaderShow();
		futureActivity()/*  defined in manager-leadinfo-view-modal page */
		get_contacts()/*  defined in manager-leadinfo-view-modal page */
		var id = $("#logg").val();	
		
		/* assign_date(id); Previous code*/
		/* ------------------New code ---------------- */
		 /* fetching the selected row data from the global variable */
		closeLostData.forEach(function(element){
			if(element.leadid == id){
				selectedrow = element;
			}
		})
		$("#StateChange").modal('show');
		$('#StateChange .modal-header .modal-title').text("Status Change from "+ selectedrow.reason.split("_").join(" "));
		$("#StateChange .error-alert").text('');
		/* setting the titel of popup window  and the first radio button value and label*/
		if(selectedrow.reason == "permanent_loss"){
			$('#StateChange #lossType').next("span").text("Temporary Loss");
			$('#StateChange #lossType').val("temporary_loss");
		}else if(selectedrow.reason == "temporary_loss"){
			$('#StateChange #lossType').next("span").text("Permanent Loss");
			$('#StateChange #lossType').val("permanent_loss");			
		}
		/* $("#stateChangeDate").val(moment().format("DD-MM-YYYY")) */
		
	}
	/* change status popup window radio button chang function  */
	function StateChangeFunction(){
		$("#StateChange .error-alert").text('');
		$('#stateChangeDateSection, #stateChangeRemarksSection, #existingExecutiveSection').hide();
		if($("#lossType").prop("checked") == true){
			
			if($('#StateChange #lossType').val() == "temporary_loss"){				
				$('#stateChangeDateSection, #stateChangeRemarksSection').show();
				$('#StateChange .temp-loss').show();
			}
			if($('#StateChange #lossType').val() == "permanent_loss"){				
				$('#stateChangeRemarksSection').show();
				$('#StateChange .temp-loss').hide();
			}		
			
		}else if($("#stateChangeReopen").prop("checked") == true){
			$('#existingExecutiveSection').show();
			$('#stateChangeRemarksSection').show();
			$('#StateChange .temp-loss').hide();
		}
	}
	/* submit  change status form*/
	function saveStateChange(){
		$("#StateChange .error-alert").text('');
		var object = {};
		object.leadId = selectedrow.leadid;		
		object.mowner = selectedrow.mowner;		
		object.rowner = selectedrow.rowner;		
		object.remarks = $.trim($("#stateChangeRemarks").val());
		object.futureActivityChk = false;
		object.assign = false;
		object.reopen = false;
		
		object.date = $.trim($("#stateChangeDate").val());
		object.title = $.trim($("#StateChangeTitle input[type=text]").val());
		object.futureActivity = $.trim($("#StateChangeFutureActivity select").val());
		object.activityDuration = $.trim($("#stateChangeActivityduration input[type=text]").val());
		object.alertBefore = $.trim($("#stateChangeAlertBefore select").val());
		object.contactType = $.trim($("#stateChangeContactType select").val());
		var lossReopen = false;
		
		if($("#lossType").prop("checked") == true){
			object.lossType = $('#StateChange #lossType').val();
			lossReopen = true;		 
			if($('#StateChange #lossType').val() == "temporary_loss"){
				$('#StateChange .temp-loss').show();
				/* if user select temporary_loss only date and remark is required */
				if(object.title == "" ){
					$("#StateChangeTitle").find(".error-alert").text('Title is required');
					return;
				}
				if(object.futureActivity == "" ){
					$("#StateChangeFutureActivity").find(".error-alert").text('Future Activity is required');
					return;
				}
				if(object.date == "" ){
					$("#stateChangeDate").closest("div").siblings(".error-alert").text('Date is required');
					return;
				}
				if(object.activityDuration == "" ){
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
				
								
				if(object.remarks == "" ){
					$("#stateChangeRemarks").next(".error-alert").text("Remarks is required");
					$("#stateChangeRemarks").focus();
					return;
				}else if(!comment_validation(object.remarks)){
					$("#stateChangeRemarks").next(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
					$("#stateChangeRemarks").focus();
					return;
				}
			}
			
			if($('#StateChange #lossType').val() == "permanent_loss"){
				$('#StateChange .temp-loss').hide();
				/* if user select permanent_loss only remark is required */
				if(object.remarks == "" ){
					$("#stateChangeRemarks").next(".error-alert").text("Remarks is required");
					$("#stateChangeRemarks").focus();
					return;
				}else if(!comment_validation(object.remarks)){
					$("#stateChangeRemarks").next(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
					$("#stateChangeRemarks").focus();
					return;
				}
				object.date = "";
				object.title = "";
				object.futureActivity = "";
				object.activityDuration = "";
				object.alertBefore = "";
				object.contactType = "";
				
				object.remarks = remarks;
			}
		}
		
		if($("#stateChangeReopen").prop("checked") == true){
			if(object.remarks == "" ){
				$("#stateChangeRemarks").next(".error-alert").text("Remarks is required");
				$("#stateChangeRemarks").focus();
				return;
			}else if(!comment_validation(object.remarks)){
				$("#stateChangeRemarks").next(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
				$("#stateChangeRemarks").focus();
				return;
			}
			object.date = "";
			object.title = "";
			object.futureActivity = "";
			object.activityDuration = "";
			object.alertBefore = "";
			object.contactType = "";
			lossReopen = true;
			object.reopen = true;
		}
		if(lossReopen == false){
			$(".error-alert.common").text('Please select any radio button');
			return;
		}
		if($("#CloseFutureActivity").prop("checked") == true){
			object.futureActivityChk = true;
		}
		if($("#stateChangeExistingExecutive").prop("checked") == true){
			object.assign = true;
		}
		
		/* Ajax call for submit */
		
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/check_state_lead'); ?>",
			data:JSON.stringify(object),
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
					return;
				}
				/* alert(data.message); */
				$.alert({
					title: 'L Connectt',
					content: data.message,
					closeIcon: true,
					closeIconClass: 'fa fa-close',
				});
				closeStateChange();
				pageload();  
				cancel1();/*Close view popup*/				
			}
		});
		
	}
	/* close change status form */
	function closeStateChange(){
		selectedrow ={};
		$('#StateChange').modal('hide');	
		$('#StateChange .form-control').val("");
		$('#StateChange input[type=radio] , #StateChange input[type=checkbox]').prop("checked", false);
		$('#stateChangeDateSection, #stateChangeRemarksSection, #existingExecutiveSection, #StateChange .temp-loss').hide();
	}
	
	var myLeads =[];
	var teamLeads =[];
	var selectedSection = "myLeads";
	function myLeadsFunc() {
		rendertable(myLeads);
		selectedSection = "myLeads";
	}
	function teamLeadsFunc() {
		rendertable(teamLeads);
		selectedSection = "teamLeads";
	}
	function OtherWonLeads() {
		/* fetching team leads  */
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/OtherLostLeads'); ?>",
			dataType:'json',
			success: function(data) {
				rendertable(data);
				teamLeads = data;
			}
		})
	}
	function rendertable(leads){
		
		$('#tablebody').parent("table").dataTable().fnDestroy();
		$('#tablebody').html("")
		if(leads.length > 0){
			closeLostData = leads
			$('#assign1').removeClass('hidden');
		}else{
			$('#assign1').addClass('hidden');
		}

		var row = "";
		var luser=''; 
		for(i=0; i < leads.length; i++ ){
			/* 
			leads[i].repremarks= window.btoa(leads[i].repremarks);
			leads[i].leadtaddress= window.btoa(leads[i].leadtaddress);
			leads[i].contPrsnAdd= window.btoa(leads[i].contPrsnAdd);	
			 */
			leads[i].repremarks= window.btoa(unescape(encodeURIComponent(leads[i].repremarks)));
			leads[i].leadtaddress= window.btoa(unescape(encodeURIComponent(leads[i].leadtaddress)));
			leads[i].contPrsnAdd= window.btoa(unescape(encodeURIComponent(leads[i].contPrsnAdd)));
		
			var rowdata = JSON.stringify(leads[i]);
			var lstate='';
			var statusColor=""
			if(leads[i].reason=='temporary_loss'){
				lstate="<b style=color:#bd6903>Temporary Lost</b>";
				/* lstate='<b style="color:#bd6903;font-size: 25px;"><i class="fa fa-star-half-o fa-2" aria-hidden="true"></i></b>'; */
			}				
			else if(leads[i].reason=='permanent_loss'){
				lstate="<b style=color:red>Permanent Lost</b>";		
				/* lstate='<b style="color:red;font-size: 25px;"><i class="fa fa-star fa-2" aria-hidden="true"></i></b>';		 */
				
			}
			if(leads[i].user_state=='0'){
			 luser="<b style=color:red>"+leads[i].user_name+"</b>"
			}
			else{
				luser=leads[i].user_name;
			}			
				
			var pList = "";
			var pList1 = "";
			if(leads[i].product_names != "-"){
				var pArray = leads[i].product_names.split(",")
				for(p=0; p< pArray.length; p++){
					if(p<=1){								
						pList += "<li>"+pArray[p]+"</li>";
					}
					if(p > 1){
						pList1 += '<li>'+pArray[p]+'</li>';
					}
				}
				if(pArray.length > 2){
					pList += '<span rel="tooltip" data-toggle="tooltip" data-trigger="hover" data-placement="bottom" data-html="true" data-title="'+pList1+'"><u> '+(pArray.length - 2)+' more</u></span>';
				}
			}
			
			row += "<tr "+statusColor+"><td><input type='checkbox' name='"+leads[i].lead_rep_owner+"' val = '"+leads[i].lead_manager_owner+"'id='"+leads[i].leadid+"' class='assign_class'/> " + (i+1) + "</td><td>" + leads[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>"+leads[i].industry_name +"</td><td>"+ luser + "</td><td>" + leads[i].city +"</td><td>"+ leads[i].employeephone1 +"</td><td>" + leads[i].leadsource +"</td><td>"+ lstate +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";					
		}	             
		$('#tablebody').parent("table").removeClass('hidden');    
		$('#tablebody').append(row);
		$('#tablebody').parent("table").DataTable({
													"aoColumnDefs": [{ "bSortable": false, "aTargets": [9,10] }]
												});
		$("#tableTeam thead th").each(function(){
			$(this).removeAttr("style")
		});
		$('#tablebody [rel="tooltip"]').tooltip();
		var checkid=[];
	}
	var closeLostData = [];
	var reporting=[];   
	function pageload(){
		OtherWonLeads()
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/fetch_lost_leads'); ?>",
			//url: "<?php echo site_url('manager_leadController/OtherLostLeads'); ?>",
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
					return;
				}
				loaderHide();
				leads=data['data'];	
				rendertable(leads);
				myLeads = leads;
			}
		});
		$('#select_map').hide();
		$("#map2").hide();
		$("#okmap").click(function(){
			$("#select_map").show();
			$("#map1").hide();
			$("#map2").hide();
			rendergmap();
		});
		$("#edit_okmap").click(function(){
			$("#edit_selectmap").show();
			$("#edit_map2").hide();
			$("#edit_map1").hide();
			edit_rendergmap();
		});
					
	}



	  function change1(){
		var id= $('#country option:selected').val(); 
		$.ajax({
				type: "POST",
				url: "<?php echo site_url('manager_leadController/get_state'); ?>",
				data : "id="+id,
				dataType:'json',
				success: function(data) {
					if(error_handler(data)){
						return;
					}
				 var select = $("#state"), options = "<option value=''>select</option>";
				   select.empty();      

				   for(var i=0;i<data.length; i++)
				   {
						options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
				   }
				   select.append(options);

				}
		});
	}



	function cancelCust(){
		$('.modal').modal('hide');
		$('.modal .form-control[type=text],.modal textarea').val("");
		$('.modal select.form-control').val($('.modal select.form-control option:first').val());
		$(".contact_type1").remove(); 
	}
	 function add_cancel(){
		$('.modal').modal('hide');
		$('.modal .form-control[type=text],.modal textarea').val("");
		$('.modal select.form-control').val($('.modal select.form-control option:first').val());
		$(".contact_type02").remove();
		$('#select_map').hide();
		$('#map1').show();

	 }
	 function cancel1(){
		$('.modal').modal('hide');
		$('.modal .form-control').val("");
	 } 
	 function cancel_opp(){
		$('.form-control').val("");		
		$("#Temp_date").hide();
		$("#remarks").hide();
		$("#remarks1").hide();
		$("#remarks2").hide();
		$('.modal input[type="text"], select, textarea').val('');
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
	 }

	 function close_modal(){
		$('#modalstart1').modal('hide');	
		$('.modal .form-control').val("");
		$("#modal_upload").modal("hide");
		$('#modal_upload #files').val("");
		$('.leadsrcname').html("");
	 } 
	
	</script>
    </head>
       <body class="hold-transition skin-blue sidebar-mini lcont-lead-page">   
        <?php require 'demo.php' ?>
        <?php require 'manager_sidenav.php' ?>
        <div class="content-wrapper body-content">
            <div class="col-lg-12 column">
				<div class="row header1">				
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
	                        <div>	
								<img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" data-html="true" title="1.‘My Leads’ are all the leads that are owned by you that did not convert to customers.<br/>2.‘Team Leads’ are all the lost leads that you do not own that managers and executives under you have closed."/>
	                        </div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Lost Leads</h2>	
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						<div class="addBtns" onclick="add_lead()">
							<!--<a href="#leadinfoAdd" class="addPlus" data-toggle="modal" >
								<img src="<?php echo site_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>-->
							<a  class="addExcel" onclick="addExl()" >
								<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a>
						</div>
                        <div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#" data-toggle="tab" onclick ="myLeadsFunc()"><h4>My Lead</h4></a>
					</li>
					<li >
						<a href="#" data-toggle="tab" onclick ="teamLeadsFunc()"><h4>Team Lead</h4></a>
					</li>
				</ul>
				
				<div class="table-responsive">
					
					<table class="table" id="tableTeam">
						<thead>  
						<tr>	
							<!-- <th class="table_header"><input type="checkbox" name="select_all" id="select_all_leads" onclick="checkAllLeads(this)"></th> 
							<th class="table_header">#</th>
							<th class="table_header">Name</th>
							<th class="table_header">Contact</th>
							<th class="table_header">Designation</th>
							<th class="table_header">Owned By</th>
							<th class="table_header">Phone</th>		
							<th class="table_header">Email</th>
							<th class="table_header">Location</th>	
							<th class="table_header">Lead Source</th>	
							<th class="table_header">Status</th>
							<th class="table_header"></th>
							<th class="table_header"></th>-->
							
							<th class="table_header" width="5%">#</th>
							<th class="table_header" width="20%">Name</th>
							<th class="table_header" width="10%">Products</th>
							<th class="table_header" width="10%">Industry</th>
							<th class="table_header" width="10%">Owned By</th>
							<th class="table_header" width="10%">City</th>
							<th class="table_header" width="10%">Phone</th>		
							<th class="table_header" width="10%">Lead Source</th>	
							<th class="table_header" width="10%">Status</th>
							<th class="table_header" width="3%"></th>
							<th class="table_header" width="2%"></th>	

													
						</tr>
						</thead>  
						<tbody id="tablebody">
						</tbody>    
					</table>
				</div>
				<div align="center">
					<input type="button" class="btn hidden" onclick="assign_btn()" id="assign1" value="Reopen"/>
				</div>
            </div>
                       
        </div>
		
			<?php require 'manager-leadinfo-add-modal.php' ?>  
			<?php require 'manager-leadinfo-edit-modal.php' ?>  
			<?php require 'manager-leadinfo-view-modal.php' ?>  		
			<?php require 'manager-exel-file-upload.php' ?> 
			<input type="hidden" id="manager_lead" value="Change Status"/>
			<!-- previously the value was Reopen 
			it has been changed to Change State on (14-08-2018)
			Change State to Change Status on (14-08-2018)
			-->
			
              <div id="modalstart1" class="modal fade" data-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addpopup" class="form">
                                <div class="modal-header">
                                        <span class="close"  onclick="close_modal()">&times;</span>
                                        <h4 class="modal-title">Lead Assignment</h4>
                                </div>
								<div class="modal-body">

									<div class="row targetrow ">
										<div class="col-md-2">
											<label for="mgrlist">User List*</label> 
										</div>
										<div class="col-md-10">											
											<div id="mgrlist" class="multiselect2" >
												<ul>
												</ul>
											</div>
											<span class="error-alert"></span>
										</div>
									</div>
									<label><input type="checkbox" name="select_all_mgr" onclick="checkAllMgrs()"> Select All</label>
								</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="assign_save()" value="Assign">
									<!--<input type="button" class="btn" onclick="cancel()" value="Cancel" >-->
									<input type="button" class="btn" onclick="close_modal()" value="Cancel">
							</div>
						</form>
					</div>
				</div>
			</div>
		
		
        <div id="counterList"  class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                        <h4 class="modal-body"> </h4>
                </div>
            </div>
        </div>
		<div id="reopenLeads" class="modal fade" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<span class="close"  onclick="closeReopenLeads()">&times;</span>
						<h4 class="modal-title">Reopen Lead</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6">
								<label><input type="checkbox" id="existingExecutive"> Assign to existing executive</label>
							</div>
						</div>
						
						<div class="row" id="reopenLeadsSection">
							<div class="col-md-3">
								<label>Remarks*</label> 
							</div>
							<div class="col-md-9">
								<textarea maxlength="500" rows="5" class="form-control" id="ReopenLeadsRemarks"></textarea>
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn" onclick="assign_date()" value="Save">
						<input type="button" class="btn" onclick="closeReopenLeads()" value="Cancel">
					</div>
				</div>
			</div>
		</div>
		<div id="StateChange" class="modal fade" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<span class="close"  onclick="closeStateChange()">&times;</span>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-3">
								<label>
									<input type="radio" name="StateChange" onchange="StateChangeFunction()" id="lossType"> 
									<span>Temp Loss / Permanent Loss</span>
								</label> 
							</div>
							<div class="col-md-3">
								<label>
									<input type="radio" name="StateChange" onchange="StateChangeFunction()" id="stateChangeReopen"> Reopen
								</label>
							</div>
							<div class="col-md-6">
								<label><input type="checkbox" id="CloseFutureActivity"> Close future activity</label>
								<span class="error-alert"></span>
							</div>
						</div>
						
						<div class="row temp-loss none" id="StateChangeTitle">
							<div class="col-md-3">										
								<span>Title*</span>
							</div>
							<div class="col-md-9">
								<input type="text" class="form-control">
								<span class="error-alert"></span>							
							</div>
						</div>
						<div class="row temp-loss none" id="StateChangeFutureActivity">
							<div class="col-md-3">										
								<span>Future Activity*</span>
							</div>
							<div class="col-md-9">
								<select class="form-control">
									
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
										<input id="stateChangeDate" placeholder="Pick a date" type="text" class="form-control" readonly="readonly">
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
								<input type="text" class="form-control" placeholder="HH:MM" maxlength="5">
								<span class="error-alert"></span>							
							</div>
						</div>
						<div class="row temp-loss none" id="stateChangeAlertBefore">
							<div class="col-md-3">										
								<span>Alert Before*</span>
							</div>
							<div class="col-md-9">
								<select class="form-control">
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
								<select class="form-control">
									
								</select>
								<span class="error-alert"></span>							
							</div>
						</div>
						<div class="row none" id="stateChangeRemarksSection">
							<div class="col-md-3">
								<label>Remarks*</label> 
							</div>
							<div class="col-md-9">
								<textarea maxlength="500" rows="6" class="form-control" id="stateChangeRemarks"></textarea>
								<span class="error-alert"></span>
							</div>
						</div>
						<div class="row none" id="existingExecutiveSection">
							<div class="col-md-12">
								<label>
									<input type="checkbox" id="stateChangeExistingExecutive"> Assign to existing Executive
								</label>
							</div>
						</div>
						<span class="error-alert common text-center"></span>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn" onclick="saveStateChange()" value="Save">
						<input type="button" class="btn" onclick="closeStateChange()" value="Cancel">
					</div>
				</div>
			</div>
		</div>
        <?php require 'footer.php' ?>

    </body>
</html>
