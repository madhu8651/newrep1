<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'scriptfiles.php' ?>
<style>
	.rightdropdown	{
		right: 0;
		left: auto;
		text-align: right;
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
	}
    #myopp .table,
    #teamopp .table{
      width:100% !important;
    }

</style>
<script>
	$(document).ready(function(){
		
		var leads = "<?php echo $leads;?>";
		var customers = "<?php echo $customers;?>";
		var sell_types = JSON.parse('<?php echo(json_encode($sell_types));?>');
		var li_html = "";
		$("#create_oppo").empty();
		if (leads == "1") {
			li_html += '<li><a onClick="show_opp_create(\'Lead\')"><h4>Lead</h4></a></li>';
		}
		if (customers == "1") {
			li_html += '<li><a onClick="show_opp_create(\'Customer\')"><h4>Customer</h4></a></li>';
		}
		loadData('myopp_closed');
		$("#create_oppo").append(li_html);
		$("#stateChangeDate").closest('div').datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY HH:mm:ss',
			minDate: moment(),
		});
		$("#stateChangeActivityduration input[type=text]").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'HH:mm',
			defaultDate:'1970-01-01 00:00'
		});
});
function loadData(opt){

    if(opt=='myopp_closed')
    {
       var url_temp="<?php echo site_url('manager_opportunitiesController/get_closed_lost_opportunities/myopp'); ?>";
       var url_temp1="/myopp";
    }else{
       var url_temp="<?php echo site_url('manager_opportunitiesController/get_closed_lost_opportunities/teamopp'); ?>";
       var url_temp1="/teamopp";
    }
	$.ajax({
		type : "POST",
		url : url_temp,
		dataType : 'json',
		cache : false,
		success : function(data){
			if (error_handler(data)) {
				return ;
			}
			loaderHide();
            if(opt=='myopp_closed')
            {
               $('#tablebody').parent("table").dataTable().fnDestroy();
            }else{
               $('#tablebody1').parent("table").dataTable().fnDestroy();
            }

			if(data['opportunitydata'].length > 0){
				closeLostData = data['opportunitydata'];
                $('#reopenOpportunityBtn').show();
				data.activitydata.forEach(function(elm){
					$('#StateChangeFutureActivity select').append('<option value="'+elm.lookup_id+'">'+elm.lookup_value+'</option>');
				});
            }
			var row="";
			for(i=0; i < data['opportunitydata'].length; i++ ){
			var link = "<?php echo site_url('manager_opportunitiesController/stage_view/'); ?>"+data['opportunitydata'][i].opportunity_id+url_temp1;
			var losstype ='';
				if(data['opportunitydata'][i].reason=="temporary_loss"){
				   losstype = "<span><font color='#ff9900'><b>Temp Loss</b></font></span>";
				} else if(data['opportunitydata'][i].reason=="permanent_loss"){
					losstype = "<span><font color='#cc0000'><b>Perm Loss</b></font></span>";
				} else if(data['opportunitydata'][i].reason=="closed_won"){
					losstype = "<span><font color='#3FB624'><b>Closed won</font></b></span>";
				}
				 row += "<tr>"+
					"<td><input onchange='reopenChkBox()' type='checkbox' value='"+data['opportunitydata'][i].opportunity_id+"'> " + (i+1)+ "</td>"+
					"<td>" + data['opportunitydata'][i].opportunity_name +"</td>"+
					"<td>" + data['opportunitydata'][i].lead_cust_name +"</td>"+
                	"<td>" + data['opportunitydata'][i].product+ "</td>"+
					"<td>" + data['opportunitydata'][i].expected_close_date+ "</td>"+
					"<td>" + data['opportunitydata'][i].stage_name +"</td>"+
					"<td>" + data['opportunitydata'][i].stage_owner +"</td>"+
					"<td>" +losstype + "</td>";

                  if(opt=='myopp_closed')
                  {
                      row+="<td>"+
      						"<input type='button' value='Change Status' class='btn' onClick='changeState(\""+data['opportunitydata'][i].opportunity_id+"\")'> "+
      					"</td>"+
                          	"<td>"+
      			 		"<a href='"+link+"'><span class='glyphicon glyphicon-eye-open'></span></a>"+
      			  		"</td>"+
      				    "</tr>";
                  }else{
                      row+="<td>"+
      			 		"<a href='"+link+"'><span class='glyphicon glyphicon-eye-open'></span></a>"+
      			  		"</td>"+
      				    "</tr>";
                  }
		 	}

              //	"<td>" + data['opportunitydata'][i].opportunity_value+ "</td>"+
			  //	"<td>" + data['opportunitydata'][i].opportunity_quantity+ "</td>"+
              //	"<td>" + data['opportunitydata'][i].industry_name+ "</td>"+
			  //	"<td>" + data['opportunitydata'][i].location_name+ "</td>"+

            if(opt=='myopp_closed')
            {
               	$('#tablebody').html('').append(row);
			    $('#myTableTeam').DataTable();
            }else{
               	$('#tablebody1').html('').append(row);
			    $('#team_tableTeam').DataTable();
            }

		}
	});
}
 /* ------------------Click on the change status button on the view popup window ---------------- */
	function reopenChkBox(){
		var flag = 0
		closeLostData.forEach(function(element){
			var selectedOpp = {};
			$("#tablebody tr > td input[type=checkbox]:checked").each(function(){
				if(element.opportunity_id == $(this).val()){
					if(element.reopen == "false"){
						flag = 1;
					}
				}
			})
		})
		$("#reopenOpportunityBtn").removeAttr("disabled");
		$("#reopenOpportunityBtn").closest('center').find('p').remove();
		if(flag == 1){
			$("#reopenOpportunityBtn").attr("disabled", "disabled");
			$("#reopenOpportunityBtn").closest('center').append('<p class="error-alert">No Authority to reopen the opportunity</p>')
		}
	}
	var closeLostData = [];
	var selectedrow;
	function changeState(id) {
		 /* fetching the selected row data from the global variable */
		$("#StateChange .error-alert").text('');	
		closeLostData.forEach(function(element){
			if(element.opportunity_id == id){
				selectedrow = element;
				$('#stateChangeContactType select').html("").append('<option value="">Select</option>');
				element.contactlist.forEach(function(elm1){
					$('#stateChangeContactType select').append('<option value="'+elm1.contact_id+'">'+elm1.contact_name+'</option>');
				})
			}
		})
		$("#StateChange").modal('show');
		$('#StateChange .modal-header .modal-title').text("Status Change from "+ selectedrow.reason.split("_").join(" "));
		/* setting the titel of popup window  and the first radio button value and label*/
		if(selectedrow.reason == "permanent_loss"){
			$('#StateChange #lossType').next("span").text("Temporary Loss");
			$('#StateChange #lossType').val("temporary_loss");
		}else if(selectedrow.reason == "temporary_loss"){
			$('#StateChange #lossType').next("span").text("Permanent Loss");
			$('#StateChange #lossType').val("permanent_loss");			
		}
		if(selectedrow.reopen != "true"){
			$("#stateChangeReopen").attr("disabled", "disabled");
		}else{
			$("#stateChangeReopen").removeAttr("disabled");
		}

		$("#stateChangeReopen").closest('div').find('i').remove();
        if(selectedrow.reopen_reason != ""){
          $("#stateChangeReopen").closest('div').append("<i><br><span class='glyphicon glyphicon-info-sign'></span>"+selectedrow.reopen_reason+"</i>")
        }
		
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
			$('#stateChangeRemarksSection').show();
			$('#StateChange .temp-loss').hide();
		}
	}
	/* submit  change status form*/
	function saveStateChange(){
		$("#StateChange .error-alert").text('');
		var object = {};
		object.opportunity_id = $.trim(selectedrow.opportunity_id);
        object.lead_cust_id 	= $.trim(selectedrow.lead_cust_id);
        object.cycle_id 		= $.trim(selectedrow.cycle_id);
        object.stage_id 		= $.trim(selectedrow.stage_id);
        object.sell_type 		= $.trim(selectedrow.sell_type);
        object.lead_cust_name 		= $.trim(selectedrow.lead_cust_name);
        object.opportunity_name 		= $.trim(selectedrow.opportunity_name);

		object.remarks = $.trim($("#stateChangeRemarks").val());
		object.date = $.trim($("#stateChangeDate").val());
		object.title = $.trim($("#StateChangeTitle input[type=text]").val());
		object.futureActivity = $.trim($("#StateChangeFutureActivity select").val());
		object.activityDuration = $.trim($("#stateChangeActivityduration input[type=text]").val());
		object.alertBefore = $.trim($("#stateChangeAlertBefore select").val());
		object.contactType = $.trim($("#stateChangeContactType select").val());
		object.lossType = '';

		if($("#lossType").prop("checked") == true){
			object.lossType = $('#StateChange #lossType').val();
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
			object.lossType = 'reopen';
			object.reopen = true;
		}
		//if(object.hasOwnProperty("lossType") == false && object.hasOwnProperty("reopen") == false){
		if(object.lossType == ''){
			$(".error-alert.common").text('Please select any radio button');
			return;
		}
		
		
		console.log(object);
        loaderShow();
		/* Ajax call for submit */
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_opportunitiesController/changestate'); ?>",
			data:JSON.stringify(object),
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
					return;
				}
                loaderHide();
				alert(data);
				location.reload();
			}
		});
		
	}
	/* close change status form */
	function closeStateChange(){
		selectedrow ={};
		$('#StateChange').modal('hide');	
		$('#StateChange .form-control').val("");
		$('#StateChange input[type=radio] , #StateChange input[type=checkbox]').prop("checked", false);
		$('#stateChangeDateSection, #stateChangeRemarksSection, #existingExecutiveSection').hide();
		$('#StateChange .temp-loss').hide();
	}
	
	/* On click of reopen opportunity button  if no checkbox selected from the table row system will prompt a popup alert
	else a textarea will appear mandatory  */
	function reopenOpportunity(){
		var isTrue = false;
		$("#tablebody tr > td input[type=checkbox]:checked").each(function(){
			isTrue = true;
		})
		
		if(isTrue == false){
			$("#reopenOpp").modal('show');
			$("#reopenOpp .error-alert.common").text('Need to select at least one Opportunity to proceed further');
			$("#reopenOpp textarea, #reopenOpp .modal-footer").hide();
		}else{
			$("#reopenOpp").modal('show');
			$("#reopenOpp textarea, #reopenOpp .modal-footer").show();
			$("#reopenOpp .error-alert.common").text('');
		}
	}
	/* close  close Reopen Opportunity popup*/
	function closeReopenOpportunity(){
		$("#reopenOpp").modal('hide');
		$("#reopenOpp textarea").val("");		
	}

    	/* save Reopen Opportunity*/
	function saveReopenOpportunity(){
		var reopenOpp=[];
		var remarks = $.trim($("#reopenOpp textarea").val());
		if(remarks == "" ){
			$("#reopenOpp .error-alert.common").text("Remarks is required");
			$("#reopenOpp textarea").focus();
			return;
		}else if(!comment_validation(remarks)){
			$("#reopenOpp .error-alert.common").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
			$("#reopenOpp textarea").focus();
			return;
		}else{
			$("#reopenOpp .error-alert.common").text("");
		}
		closeLostData.forEach(function(element){
			var selectedOpp = {};
			$("#tablebody tr > td input[type=checkbox]:checked").each(function(){
				if(element.opportunity_id == $(this).val()){
					selectedOpp.opportunity_id 	= element.opportunity_id;
					selectedOpp.lead_cust_id 	= element.lead_cust_id;
					selectedOpp.cycle_id 		= element.cycle_id;
					selectedOpp.stage_id 		= element.stage_id;
					selectedOpp.sell_type 		= element.sell_type;
					selectedOpp.remarks 		=  remarks;
                    selectedOpp.lead_cust_name 	= element.lead_cust_name;
                    selectedOpp.opportunity_name 	= element.opportunity_name;
					reopenOpp.push(selectedOpp);

				}
			})
		})

		console.log(reopenOpp)
		/* Ajax call for submit */
       //return;
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_opportunitiesController/reopenbulk'); ?>",
			data:JSON.stringify(reopenOpp),
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
					return;
				}
                alert(data);
				location.reload();
			}
		});
	}

	/* save Reopen Opportunity*/
	/*function saveReopenOpportunity(){
		var reopenOpp=[];
		var remarks = $.trim($("#reopenOpp textarea").val());
		if(remarks == "" ){
			$("#reopenOpp .error-alert.common").text("Remarks is required");
			$("#reopenOpp textarea").focus();
			return;
		}else if(!comment_validation(remarks)){
			$("#reopenOpp .error-alert.common").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
			$("#reopenOpp textarea").focus();
			return;
		}else{
			$("#reopenOpp .error-alert.common").text("");
		}
		closeLostData.forEach(function(element){
			var selectedOpp = {};
			$("#tablebody tr > td input[type=checkbox]:checked").each(function(){
				if(element.opportunity_id == $(this).val()){
					selectedOpp.opportunity_id 	= element.opportunity_id;
					selectedOpp.lead_cust_id 	= element.lead_cust_id;
					selectedOpp.cycle_id 		= element.cycle_id;
					selectedOpp.stage_id 		= element.stage_id;
					selectedOpp.sell_type 		= element.sell_type;
					selectedOpp.remarks 		=  remarks;
					reopenOpp.push(selectedOpp);
				}
			})
		})


		return;
		$.ajax({
			type: "POST",
			url: "",
			data:JSON.stringify(reopenOpp),
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
					return;
				}
				loadData();
			}
		});
	}*/
</script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="loader">
	<center><h1 id="loader_txt"></h1></center>
</div>
<?php  require 'demo.php'  ?>
<?php require 'manager_sidenav.php' ?>
<?php require 'manager_opportunity_create_popup.php' ?>
<div class="content-wrapper body-content">
	<div class="col-lg-12 column">
		<div class="row header1">
			<div class="col-xs-2 col-sm-2 col-md-3 col-lg-3 aa">
				<span class="info-icon">
					<div >
						<img src="<?php echo site_url()?>/images/new/i_off.png" onmouseover="this.src='<?php echo site_url()?>/images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url()?>/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Closed Opportunities List"/>
					</div>
				</span>
			</div>
			<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6 pageHeader1 aa">
				<h2>Lost Opportunities</h2>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-3 col-lg-3 aa">
				<div class="addBtns addPlus">
					<span class="info-icon">
						<!--<img src="<?php echo site_url()?>/images/new/Plus_Off.png" onmouseover="this.src='<?php echo site_url()?>/images/new/Plus_ON.png'" onmouseout="this.src='<?php echo site_url()?>/images/new/Plus_Off.png'" alt="info" width="30px" height="30px" class="dropdown-toggle" data-toggle="dropdown" />-->
						<ul class="dropdown-menu rightdropdown pull-right" id="create_oppo"></ul>
					</span>
				</div>
			</div>
		</div>
        <ul class="nav nav-tabs">
					<li class="active" onclick="loadData('myopp_closed')"><a data-toggle="tab" href="#myopp">My Opportunity</a></li>
					<li onclick="loadData('teamopp_closed')" id="state"><a data-toggle="tab" href="#teamopp">Team Opportunity</a></li>
		</ul>
        <div class="tab-content tab_countstat">
    		<!--<div class="table-responsive"> -->
            <div id="myopp" class="tab-pane fade in active">
    		   <table class="table" id="myTableTeam">
                    <thead>
                          <tr>
                          <th class="table_header">Sl No</th>
                          <th class="table_header" style="text-align: left;">Name</th>
                          <th class="table_header" style="text-align: left;">Prospect</th>
                          <th class="table_header" style="text-align: left;">Product</th>
                           <!--	<th class="table_header" style="text-align: left;">Industry</th>
                          <th class="table_header" style="text-align: left;">Location</th>-->
                            <!--	<th class="table_header" style="text-align: left;">Amount</th>
                          <th class="table_header" style="text-align: left;">Quantity</th>-->
                          <th class="table_header" style="text-align: left;">Close Date</th>
                          <th class="table_header" style="text-align: left;">Sales Stage</th>
                          <th class="table_header" style="text-align: left;">Closed by</th>
                          <th class="table_header" style="text-align: left;" data-orderable="false">Status</th>
                          <th class="table_header" data-orderable="false"></th>
                          <th class="table_header" data-orderable="false"></th>
                          </tr>
                    </thead>
                    <tbody id="tablebody">

                    </tbody>
			</table>
             <center>
        			<input type='button' id="reopenOpportunityBtn" value='Reopen' class='btn none' onClick='reopenOpportunity()'>
        	 </center>
    		</div>
            <div id="teamopp" class="tab-pane fade">
    			 <table class="table" id="team_tableTeam">
    				 <thead>
                          <tr>
                          <th class="table_header">Sl No</th>
                          <th class="table_header" style="text-align: left;">Name</th>
                          <th class="table_header" style="text-align: left;">Prospect</th>
                          <th class="table_header" style="text-align: left;">Product</th>
                           <!--	<th class="table_header" style="text-align: left;">Industry</th>
                          <th class="table_header" style="text-align: left;">Location</th>-->
                            <!--	<th class="table_header" style="text-align: left;">Amount</th>
                          <th class="table_header" style="text-align: left;">Quantity</th>-->
                          <th class="table_header" style="text-align: left;">Close Date</th>
                          <th class="table_header" style="text-align: left;">Sales Stage</th>
                          <th class="table_header" style="text-align: left;">Closed by</th>
                          <th class="table_header" data-orderable="false"></th>
                          <th class="table_header" data-orderable="false"></th>
                          </tr>
                    </thead>
                    <tbody id="tablebody1">

                    </tbody>
    			</table>

    		</div>
        </div>

	</div>
</div>
		<div id="reopenOpp" class="modal fade" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<span class="close"  onclick="closeReopenOpportunity()">&times;</span>
						<h4 class="modal-title">Reopen Opportunities</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12 no-padding">
								<textarea rows="6" class="form-control" placeholder="Please mention reason (Mandatory)" maxlength="500"></textarea>
							</div>
						</div>
						<p class="error-alert common text-center"></p>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn" onclick="saveReopenOpportunity()" value="Save">
						<input type="button" class="btn" onclick="closeReopenOpportunity()" value="Cancel">
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
							<div class="col-md-9">
								<label>
									<input type="radio" name="StateChange" onchange="StateChangeFunction()" id="stateChangeReopen"> Reopen
								</label>
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
								<select class="form-control"></select>
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
<?php require ('footer.php'); ?>
</body>
</html>