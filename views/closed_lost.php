<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'scriptfiles.php' ?>
<style>
audio{
	width: 225px;
}
 .lead_address{
 background-color:#c1c1c1;
 padding: 10px 12px;
 margin-bottom: 17px;
 margin-top: 6px;
}
.lead_view{
 background-color:#c1c1c1;
 padding: 10px 12px;
}
#mapname,#edit_mapname{
 width: 100%;
 height: 150px;
 border: 1px;
 position: relative;
 overflow: hidden;
 margin-bottom: 12px;
}
#tree_leadsource,#edit_leadsource{
 position: absolute;
 background: white;
 z-index: 99;
 top: -50px;
 left: 100px;
 border: 1px solid #ccc;
 padding: 10px;
 border-radius: 5px;
}
</style>
<script>
var closeLostData;
function loaddata(){
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('leadinfo_controller/display_lostlead'); ?>",
        dataType:'json',
        success: function(data){
			if(error_handler(data)){
				return;
			}
			if(data.length > 0){
				$("#reopenLeadBtn").show();
			}else{
				$("#reopenLeadBtn").hide();
			}
			
            loaderHide();
			$('#tablebody').parent("table").dataTable().fnDestroy();
			var row="";
			closeLostData = data;
			for(i=0; i < data.length; i++ ){ 
				var losstype ='';
				if(data[i].reason=="temporary_loss"){              
				  losstype = "<span><font color='#ff9900'><b>Temporary Lost</b></font></span>";              
				}
				else if(data[i].reason=="permanent_loss"){
				   losstype = "<span><font color='#cc0000'><b>Permanent Lost<b></font></span>";
				} 
				var rowdata = JSON.stringify(data[i]);
				var pList = "";
				var pList1 = "";
				if(data[i].product_names != "-"){
					var pArray = data[i].product_names.split(",")
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
				row += "<tr><td><input class='lead_id_class' type='checkbox' value='"+data[i].lead_id+"'" +(i+1)+"></td><td>" + data[i].lead_name +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul><td>" + data[i].industry +"</td></td><td>" + data[i].lead_city +"</td><td>" + data[i].contact_name +"</td><td>" + data[i].employeephone1+"</td><td>" + data[i].leademail +"</td><td>" + data[i].leadsurce +"</td><td>"+losstype+"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'data-toggle='modal'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a href='#leadinfoedit' onclick='selrow("+rowdata+")' data-toggle='modal'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
			}
			$('#tablebody').html("").append(row);
			$('#tablebody').parent("table").DataTable({
			"aoColumnDefs": [{ "bSortable": false, "aTargets": [8,9] }]
			}); 
        },
		error:function(data){
			network_err_alert();
		}
    });
}
$(document).ready(function(){	
	$('#stateChangeDate').closest('div').datetimepicker({
		minDate:new Date(),
		ignoreReadonly: true,
		allowInputToggle:true,
		format: 'DD-MM-YYYY HH:mm:ss',
		minDate: moment(),
	});
	$("#stateChangeActivityduration input[type=text]").datetimepicker({
		ignoreReadonly:true,
		allowInputToggle:true,
		format:'HH:mm',
		defaultDate: new Date().setHours(0,0),
	});
	loaddata();   
/* 	$("#leadlog").click(function(){
		$('#logdetails').toggle();
	});
	
	$("#opp_log").click(function(){
		$('#oop_details').toggle();
	}); */
});

function cancel(){
	$('.modal').modal('hide');
	$('.form-control').val("");
}
function cancel_rej(){
	$('#reject').modal('hide');
	$('.error-alert').hide();
	$('.form-control').val("");
}
function add_cancel(){
	$('.modal').modal('hide');
	$('.form-control').val("");
	$('#select_map').hide();
	$('#map1').show();
}
function cancel1(){
	$('.modal').modal('hide');
	$('.form-control').val("");
	$('#logtable').empty();
	$('#opp_table').empty();
	$("#custom_fields_view input[type=text]").val("");
}
 
function cancel_opp(){
	$('#closed_opp').modal('hide');
	$('.form-control').val("");		
	$("#Temp_date").hide();
	$("#remarks").hide();
	$("#remarks1").hide();
	$("#remarks2").hide();
	$("#lost_id").hide();		
	$('#closed_opp input[type="text"],textarea').val('');
	$('#closed_opp input[type="radio"]').prop('checked', false);
	$('#closed_opp input[type="checkbox"]').prop('checked', false);
	$(".error-alert").text("");
	$(".input-group.date").data("DateTimePicker").date(null);
	$(".input-group.date").data("DateTimePicker").destroy();
}

function viewrow(obj){
	$('.displayArea').closest('.tab-pane.fade').removeClass('active').removeClass('in');
	$('.notFound').remove();
	$('.displayArea').show();
	$('#logdetails').addClass('active').addClass('in');
	$('#leadlog, #Scheduled_log, #opp_log').closest('li').removeClass('active');
	$('#leadlog').closest('li').addClass('active');
	tab(obj.lead_id);
	
	if( navigatorChk==1){
	  var coordinates =obj.lead_location_coord; 
		if(coordinates!=null){
			var direction =coordinates.split(",");
		   $("#label_long").val(direction[0]);
		   $("#label_latt").val(direction[1]);
		}
		get_coordinate("label_long","label_latt","view_maploc");
		$("#view_map2").show();          
	}else{
	  $("#view_map2").hide();
	}
	
	var personal={};
	personal.name = obj.lead_name;
	personal.email = obj.leademail;
	personal.phone = obj.leadphone;
	personal.website = obj.lead_website;
	personal.country = obj.country;
	personal.state = obj.state;
	personal.city = obj.lead_city;
	personal.zip = obj.lead_zip;
	personal.industry = obj.industry;
	personal.Blocation = obj.location;
	personal.logo = obj.lead_logo;
	personal.source = obj.leadsurce;
	
	/* 
	$("#label_leadweb").html(obj.lead_website);
	$("#label_leadmail").text(obj.leademail);
	$("#label_leadphone").text(obj.leadphone);
	$("#label_leadsource").html(obj.leadsurce);
	$("#label_country").html(obj.country);
	$("#label_state").html(obj.state);
	$("#label_city").html(obj.lead_city);
	$("#label_zipcode").html(obj.lead_zip);
	
	$("#label_indus").html(obj.industry);
	$("#label_business").html(obj.location);
	if(obj.lead_logo!=null){
		$('#leadpic').attr('src', "<?php echo base_url(); ?>uploads/"+obj.lead_logo);
	}else{
		$('#leadpic').attr('src', "<?php echo base_url(); ?>images/default-pic.jpg");
	}
	*/
	$('#leadname_label').text(obj.lead_name);
	$('#lead_id').val(obj.lead_id);
	$("#view_ofcadd").val(obj.lead_address);
	$("#view_splcomments").val(obj.lead_remarks);
	contact_prsn_list(obj.lead_id, "contact_prsn_list" ,"lead","sales");
	
	var obj1={};
	obj1.lead_id=obj.lead_id;
    $.ajax({
		type: "POST",
		url: "<?php echo site_url('leadinfo_controller/getCustomData');?>",
		data:JSON.stringify(obj1),
		dataType:'json',
		success: function(data) {
            if(data==0){
				$("#custom_head_view").hide();
            }else{
				$("#custom_fields_view").empty();
                $("#custom_head_view").show();
                for(i=0;i<data.length;i++){
                    if(data[i].attribute_type=="Single_Line_Text"){
						$("#custom_fields_view").append("<div class='col-md-2'><label><b>"+data[i].attribute_name+"</b></label></div><div class='col-md-4'><label id='customer_custom'>"+data[i].attribute_value+"<label></div>");
                    }
                }
			}
		},
		error:function(data){
			network_err_alert();
		}
    });
	
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
			personal.product = data;
			leadinfoView(personal , 'Lead', 'leadInfoView');
		},
		error:function(data){
			network_err_alert();
		}
	});
		
	
	/* schedule_logs + opportunity */
         
}

/* ------------------Click on the change status button on the view popup window ---------------- */
function fetchContactsActivity(id){
	var addObj={};
	addObj.leadid = id;
	$.ajax({
		type: "POST",
		url:"<?php echo site_url('manager_leadController/fetchActivity')?>",
		data : JSON.stringify(addObj),
		dataType:'json',
		success: function(data){
			if(error_handler(data)){
				return;
			}
			
			var select = $("#StateChangeFutureActivity select"), options = "<option value=''>Select</option>";
			select.empty();
			for(var i=0;i<data.activityArray.length; i++){
				options += "<option value='"+data.activityArray[i].lookup_id+"'>"+ data.activityArray[i].lookup_value+" </option>";
			}
			select.append(options);
			/* ----------------------- */
			var select1 = $("#stateChangeContactType select"), options1 = "<option value=''>Select</option>";			
			select1.empty();
			for(var i=0;i<data.contactArray.length; i++){
				options1 += "<option value='"+data.contactArray[i].contact_id+"'>"+ data.contactArray[i].contact_name+" </option>";
			}
			select1.append(options1);
		},
		error:function(data){
			network_err_alert();
		}
	});
}

	var selectedrow;
function assign_btn2() {
	
		var id = $("#lead_id").val();
		/* fetching the selected row data from the global variable */
		closeLostData.forEach(function(element){
			if(element.lead_id == id){
				selectedrow = element;
			}
		})
		fetchContactsActivity(id);
		$("#StateChange").modal('show');
		$("#stateChangeActivityduration input[type=text]").val('00:00');
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
		$('#stateChangeDateSection, #stateChangeRemarksSection').hide();
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
	/* submit  change status form */
	function saveStateChange(){
		$("#StateChange .error-alert").text('');
		var object = {};
		object.leadId = selectedrow.lead_id;	
		/* object.mowner = selectedrow.mowner;		
		object.rowner = selectedrow.rowner; */			
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
		/* Ajax call for submit */
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('leadinfo_controller/check_state_lead'); ?>",
			data:JSON.stringify(object),
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
					return;
				}
				closeStateChange();
				loaddata();  
				cancel1() /*Close view popup*/	
				alert(data.message);			
			}
		});
		
	}
	/* close change status form */
	function closeStateChange(){
		selectedrow ={};
		$('#StateChange').modal('hide');	
		$('#StateChange .form-control').val("");
		$('#StateChange input[type=radio] , #StateChange input[type=checkbox]').prop("checked", false);
		$('#stateChangeDateSection, #stateChangeRemarksSection').hide();
		$('#StateChange .temp-loss').hide();
	}
	
	
	
	var finalArray = {};
	function reopenLead(){
		var flagchk=0;
		finalArray['leads'] = [];
		$("#tablebody tr .lead_id_class:checked").each(function () {
			finalArray['leads'].push($(this).val());
			flagchk=1;
		});
		if(flagchk==0){
			alert("Need to select at least one lead to proceed further");
			return;	
		}else{
			$("#reopenLeads").modal("show");
		}    	
	}

	function closeReopenLeads(){
		$("#reopenLeads").modal("hide");
		finalArray = {};
		$("#ReopenLeadsRemarks").val("");
	}
	function reopenLeadSave(){
		/* ---------------Addition -- Assign to existing executive checkbox added------------------ */
		
		var remarks = $.trim($("#ReopenLeadsRemarks").val());
		
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
			/* tempvar.rowner = "";
			tempvar.mowner = "";
			closeLostData.forEach(function(all){
				if(all.leadid == element){
					tempvar.rowner = all.rowner;
					tempvar.mowner = all.mowner;
				}
			}) */
			tempvar.leadId = element;
			tempvar.remarks = finalArray.remarks;
			tempvar.assign = finalArray.assign;
			tempvar.reopen = true;
			reOpen.push(tempvar);
		});

		$.ajax({
			type: "POST",
			url: "<?php echo site_url('leadinfo_controller/multiple_reopen'); ?>",
			dataType:'json',
			data:JSON.stringify(finalArray),
			success: function(data) {
				if(error_handler(data)){
					return;
				}

				closeReopenLeads();
				loaddata();  
				alert('Selected Lead(s) reopened successfully')
			}
		});
	}
</script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini lcont-lead-page">   
          <div class="loader">
   <center><h1 id="loader_txt"></h1></center>  
  </div>
        <?php require 'demo.php' ?>
        <?php require 'sales_sidenav.php' ?>
        <div class="content-wrapper body-content">
            <div class="col-lg-12 column">
				<div class="row header1">				
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >		
								<img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" title="View all the leads that did not convert to customers. Select the ones to reassign."/>
							</div>
						</span>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Lost Leads</h2>	
					</div>
					<div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
						 <div class="addBtns" onclick="add_lead();">
							<a href="#leadinfoAdd" class="addPlus" data-toggle="modal" >
								<img src="/images/new/Plus_Off.png" onmouseover="this.src='/images/new/Plus_ON.png'" onmouseout="this.src='/images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="table-responsive">
					<table class="table" id="tableTeam">
						<thead>  
							<tr>
								<th class="table_header">Sl No</th>
								<th class="table_header">Lead Name</th>
								<th class="table_header">Products</th>
								<th class="table_header">Industry</th>
								<th class="table_header">City</th>
								<th class="table_header">Contact Name</th>		
								<th class="table_header">Phone</th>
								<th class="table_header">Email</th>	
								<th class="table_header">Lead Source</th>
								<th class="table_header">Status</th>
								<th class="table_header"></th>
								<th class="table_header"></th>
							</tr>
						</thead>  
						<tbody id="tablebody">
						</tbody>    
					</table>
					<center>
						<input type='button' id="reopenLeadBtn" value='Reopen' class='btn none' onClick='reopenLead()'>
					</center>
				</div>
            </div>
			<?php require 'lead_add_view.php' ?>
			<?php require 'lead_edit_view.php' ?>
            <div id="leadview" class="modal fade" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
						<input type="hidden" id="lead_id"/>
                        <form id="viewpopup" class="form" action="#" method="post" >
                            <div class="modal-header">
                                <span class="close" onclick="cancel1()">x</span>
                                <h4 class="modal-title"><b>View Lead</b></h4>
                            </div>
                            <div class="modal-body">
								<div id="leadInfoView"></div>
                                
                                <div class="row lead_address" >
									<div class="col-md-6 col-sm-6 col-xs-6">
										<center><b>Office Address</b></center>
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<center><b>Special Comments</b></center>
									</div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <textarea class="form-control pre" id="view_ofcadd" readonly="readonly"></textarea>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <textarea class="form-control pre" id="view_splcomments" readonly="readonly"></textarea>
                                    </div>
                                </div>
                                <input type="hidden" id="label_latt">
                                <input type="hidden" id="label_long">
                                <div class="row" id="view_map2" >
									<div class="row" id="view_maploc" style="width:100% px;height:150px;border:1px;"></div>
                                </div>
								<div class="row" >
									<div class="col-md-12 lead_address">
										<center><b>Lead Contact Information</b></center>
									</div>
                                </div>
								<div class="row" id="contact_prsn_list"></div>
								<!--<div class="row">
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_firstcontact">Contact Person</label> 
											</div>
											<div class="col-md-4">
												<label id="lead_firstcontact"></label> 
												<input type="hidden"  id="employeeid" name="employeeid">
												<span class="error-alert"></span>
											</div>                                    
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_designation">Designation</label> 
											</div>
											<div class="col-md-4">
												<label id="label_designation"></label> 
												<span class="error-alert"></span>
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primmobile">Mobile Number 1</label> 
											</div>
											<div class="col-md-4">
												<label id="label_primmobile"></label> 
												<span class="error-alert"></span>
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primmobile2">Mobile Number 2</label> 
											</div>
											<div class="col-md-4">
												 <label id="label_primmobile2"></label> 
												<span class="error-alert"></span>
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primemail">Email 1</label> 
											</div>
											<div class="col-md-4">
												 <label id="label_primemail"></label> 
												  <span class="error-alert"></span>
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_contacttype">contact Type</label> 
											</div>
											<div class="col-md-4">
												<label id="label_contacttype"></label> 
												<input type="hidden" id="lead_id"/>
												<span class="error-alert"></span>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="row">
										<div class="col-md-2 apport_label">
											<label for="view_displaypic">Photo</label> 
										</div>
										<div class="col-md-4">
											 <img width="100" height="100" id="leadpic"/>
										</div>
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primemai2">Email 2</label> 
											</div>
											<div class="col-md-4">
												<label id="label_primemai2"></label> 
												<span class="error-alert"></span>
											</div>                                  
										</div>										
									</div>
								</div> -->
                                <div class="row none" id="custom_head_view">
                                    <div class="col-md-12 lead_address">
                                        <center><b>Custom Fields</b></center>
                                    </div>
                                </div>
								<div class="row" id="custom_fields_view"></div>
                                <br>
								<?php require 'sales-view-tab.php' ?>
                                
							</div>
                            <div class="modal-footer">
								<input type="button" class="btn"  id="manager_lead_save" onclick="assign_btn2()" value="Change Status"/>
                                 <button  type="button" class="btn btn-default" id="btn1_cancel" onclick="cancel1()" >Cancel</button>
                            </div>
						</form>
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
						
						<span class="error-alert common text-center"></span>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn" onclick="saveStateChange()" value="Save">
						<input type="button" class="btn" onclick="closeStateChange()" value="Cancel">
					</div>
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
						<input type="button" class="btn" onclick="reopenLeadSave()" value="Save">
						<input type="button" class="btn" onclick="closeReopenLeads()" value="Cancel">
					</div>
				</div>
			</div>
		</div>
        <?php require 'footer.php' ?>
    </body>
</html>
