<!DOCTYPE html>
<html lang="en">
    <head>
    <?php require 'scriptfiles.php' ?>
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
    $("#create_oppo").append(li_html);
    loadData();
	
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

function loadData() {
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('sales_opportunitiesController/get_closed_lost_opportunities'); ?>",
        dataType : 'json',
        cache : false,
        success : function(data){
            if (error_handler(data)) {
                return ;
            }
            loaderHide();
            $('#tablebody').empty();
            console.log(data);
            if(data['opportunitydata'].length > 0){
				data.activitydata.forEach(function(elm){
					$('#StateChangeFutureActivity select').append('<option value="'+elm.lookup_id+'">'+elm.lookup_value+'</option>');
				});
				closeLostData = data['opportunitydata'];
                $('#opp_accept').removeClass('hidden');
                $('#reopenOpportunityBtn').show();

            }
            var row="";
            for(i=0; i < data['opportunitydata'].length; i++ ){
                var rowdata = JSON.stringify(data['opportunitydata'][i]);
                var losstype ='';
                if(data['opportunitydata'][i].reason=="temporary_loss"){
                   losstype = "<span><font color='#ff9900'><b>Temp Loss</b></font></span>";
                }
                else if(data['opportunitydata'][i].reason=="permanent_loss"){
                    losstype = "<span><font color='#cc0000'><b>Perm Loss<b></font></span>";
                }

                var link = "<?php echo site_url('sales_opportunitiesController/stage_view/'); ?>"+data['opportunitydata'][i].opportunity_id;
                row += "<tr>"+
							"<td><input onchange='reopenChkBox()'type='checkbox' value='"+data['opportunitydata'][i].opportunity_id+"'> " + (i+1)+"</td>"+
							"<td>" + data['opportunitydata'][i].opportunity_name + "</td>"+
							"<td>" + data['opportunitydata'][i].lead_cust_name +"</td>"+
							"<td>" + data['opportunitydata'][i].product +"</td>"+
							/* --- hiding (03-09-2018)-----<td>" + data['opportunitydata'][i].opportunity_value +"</td>"+ */
							"<td>" + data['opportunitydata'][i].expected_close_date +"</td>"+
							"<td>" + data['opportunitydata'][i].stage_name +"</td>"+
							"<td>" + data['opportunitydata'][i].rep_owner +"</td>"+
							"<td>" + data['opportunitydata'][i].stagerep_owner +"</td>"+
							"<td>" + data['opportunitydata'][i].closed_by +"</td>"+
							"<td>" + losstype +"</td>"+
							"<td style='text-align: center'>" +
								"<input type='button' value='Change Status' class='btn' onClick='changeState(\""+data['opportunitydata'][i].opportunity_id+"\")'> "+

							"</td>"+
                            "<td>"+
			 		            "<a href='"+link+"'><span class='glyphicon glyphicon-eye-open'></span></a>"+
			  		        "</td>"+
						"</tr>";
        }
        //"<a href='"+link+"'><span class='glyphicon glyphicon-eye-open'></span></a>"+
        $('#tablebody').append(row);
		$('#tableTeam').DataTable({
            "aoColumnDefs": [{ "bSortable": false, "aTargets": [9] }]
        });
        }
    });
}
function cancel(){
    $('.modal').modal('hide');
    $('input, select, textarea').val('');
    $('input[type="radio"]').prop('checked', false);
    $('input[type="checkbox"]').prop('checked', false);
}
 
 /* ------------------Click on the change status button on the view popup window ---------------- */
	var closeLostData = [];
	var selectedrow;
	function changeState(id) {
		 /* fetching the selected row data from the global variable */
		closeLostData.forEach(function(element){
			if(element.opportunity_id == id){
				selectedrow = element;
				element.contactlist.forEach(function(elm1){
					$('#stateChangeContactType select').html("").append('<option value="">Select</option>');
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
		object.opportunity_id = selectedrow.opportunity_id;
        object.lead_cust_id 	= selectedrow.lead_cust_id;
        object.cycle_id 		= selectedrow.cycle_id;
        object.stage_id 		= selectedrow.stage_id;
        object.sell_type 		= selectedrow.sell_type;
        object.lead_cust_name 		= selectedrow.lead_cust_name;
        object.opportunity_name 		= selectedrow.opportunity_name;
        object.reason 		= selectedrow.reason;



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
            object.lead_manager_owner = selectedrow.lead_manager_owner;
            object.lead_rep_owner = selectedrow.lead_rep_owner;
		}
		//if(object.hasOwnProperty("lossType") == false && object.hasOwnProperty("reopen") == false){
		if(object.lossType == ''){
			$(".error-alert.common").text('Please select any radio button');
			return;
		}
		/* Ajax call for submit */
       // console.log(object);
       // return;
        loaderShow();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('sales_opportunitiesController/changestate'); ?>",
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
                    selectedOpp.opportunity_name = element.opportunity_name;
                    selectedOpp.lead_manager_owner = element.lead_manager_owner;
                    selectedOpp.lead_rep_owner = element.lead_rep_owner;
					reopenOpp.push(selectedOpp);
				}
			})
		})

		console.log(reopenOpp)
		/* Ajax call for submit */

		$.ajax({
			type: "POST",
			url: "<?php echo site_url('sales_opportunitiesController/reopenbulk'); ?>",
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
    </script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
            <div class="loader">
    <center><h1 id="loader_txt"></h1></center>
</div>
        <?php require 'demo.php' ?>
        <?php require 'sales_sidenav.php' ?>
        <?php require 'sales_opportunity_create_popup.php' ?>
            <div class="content-wrapper body-content">
                <div class="col-lg-12 column">
                <div class="row header1">
                    <div class="col-xs-2 col-sm-2 col-md-3 col-lg-3 aa">
                        <span class="info-icon toolTipStyle">
                            <div >
                        <img src="<?php echo site_url()?>/images/new/i_off.png" onmouseover="this.src='<?php echo site_url()?>/images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url()?>/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" title="View all the leads that could not become customers.
														Temporary Loss is when an opportunity is not able to convert to a customer immediately but there is an opportunity in the future. 
														Permanent Loss is when there is no opportunity to convert to a customer in the future."/>
                            </div>
                        </span>
                    </div>
                    <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6 pageHeader1 aa">
                            <h2>
                            Lost Opportunities</h2>
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
                <div class="table-responsive">
                     <table class="table" id="tableTeam">
                        <thead>
                            <tr>
                            <th class="table_header">Sl No</th>
                            <th class="table_header" style="text-align: left;">Name</th>
                            <th class="table_header" style="text-align: left;">Prospect</th>
                            <th class="table_header" style="text-align: left;">Product</th>
                            <!--<th class="table_header" style="text-align: left;">Amount</th>-->
                            <th class="table_header" style="text-align: left;">Close Date</th>
                            <th class="table_header" style="text-align: left;">Stage</th>
                            <th class="table_header" style="text-align: left;">Executive </th>
                            <th class="table_header" style="text-align: left;">Stage Executive </th>
                            <th class="table_header" style="text-align: left;">Closed By</th>
                            <th class="table_header" style="text-align: left;">Status </th>
                            <th class="table_header" data-orderable="false"></th>
                            <th class="table_header" data-orderable="false"></th>
                            </tr>
                        </thead>
                        <tbody id="tablebody">
                        </tbody>
                    </table>
                </div>
				<center>
					<input type='button' id="reopenOpportunityBtn" value='Reopen' class='btn none' onClick='reopenOpportunity()'>
				</center>
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
							<div class="col-md-12">
								<textarea rows="6" class="form-control no-padding" placeholder="Please mention reason (Mandatory)" maxlength="500"></textarea>
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