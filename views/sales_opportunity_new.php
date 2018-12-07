<!DOCTYPE html>
<html lang="en">
	<head>
	<?php require 'scriptfiles.php' ?>
<script>
var maindata;
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
	loaddata();
});

function loaddata(){
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('sales_opportunitiesController/get_new_opportunities'); ?>",
		dataType : 'json',
		cache : false,
		success : function(data){
            if (error_handler(data)) {
                return ;
            }
			loaderHide();
			$('#tablebody').empty();
			if(data.length > 0){
				$('#opp_rejectbtn').show();
			}
			var row="";
			for(i=0; i < data.length; i++ ){
				var rowdata = JSON.stringify(data[i]);
				var ownership ='';
				if(data[i].owner_status==1 || data[i].owner_status==3){
					if(data[i].ownerreject>0){
						ownership ="<span><font color='#cc0000'><b>Declined<b></font></span>";
					}else{
					    if(data[i].owner_status1 > 0){
                            ownership = "<input type=button class='btn' id='opp_owner' onclick='accept_owner("+rowdata+","+1+")' value='Accept'>";
					    }

					}
				}else{
					if(data[i].ownerreject>0){
						ownership = "<span><font color='#cc0000'><b>Declined<b></font></span>";
					}else{
						if(data[i].owner_name=='pending'){
							ownership="<span><font color='#FF8C00'><b>Pending<b></font></span>"
						} else{
							ownership = data[i].owner_name;
						}
					}
				}
				var stage_ownership ='';
				if(data[i].stage_owner_status==1 || data[i].stage_owner_status==3){
					if(data[i].stagereject>0){
						stage_ownership ="<span><font color='#cc0000'><b>Declined<b></font></span>";
					}else{
					    if(data[i].stage_owner_status1 > 0){
                            stage_ownership = "<input type=button class='btn' id='opp_owner' onclick='accept_owner("+rowdata+","+2+")'value='Accept'>";
					    }

					}
				}else{
					if(data[i].stagereject>0){
					   stage_ownership ="<span><font color='#cc0000'><b>Declined<b></font></span>";
					}else{
					    if(data[i].stage_owner_status1 > 0){
                            stage_ownership = "<input type=button class='btn' id='opp_owner' onclick='accept_owner("+rowdata+","+2+")'value='Accept'>";
					    }else{
                            stage_ownership = data[i].stage_owner_name;
					    }

					}
				}
				var  link = "<?php echo site_url('sales_opportunitiesController/stage_view/'); ?>"+data[i].opportunity_id;
				row += "<tr>"+
					"<td> <input type='radio' value='"+rowdata+"' name='op_id' /> " +(i+1)+"</td>"+
					"<td>" + data[i].opportunity_name + "</td>"+
					"<td>" + data[i].lead_cust_name +"</td>"+
					"<td>" + data[i].product +"</td>"+
					"<td>" + data[i].industry_name +"</td>"+
					"<td>" + data[i].location_name +"</td>"+
					"<td>" + data[i].stage_name +"</td>"+
					"<td>" + ownership +"</td>"+
					"<td>" + stage_ownership +"</td>"+
					"<td><a href='"+link+"'><span class='glyphicon glyphicon-eye-open'></span></a></td>"+
				"</tr>";
			}
			$('#tablebody').append(row);
			$('#tableTeam').DataTable();
		}
	});
}

function cancel(){
	$('.modal').modal('hide');
	$('.modal textarea').val('');
	$('.modal #owner').prop('checked', false);
	$('.modal #stage').prop('checked', false);
}

function cancel1(){
	$('.modal').modal('hide');
	$('.form-control').val("");
}

function accept_owner(obj,owner){
	var addObj={};
	addObj.opportuniy_id =obj.opportunity_id;
	addObj.lead_cust_id =obj.lead_cust_id;
	addObj.sell_type =obj.sell_type;
	addObj.opportunity_stage =obj.opportunity_stage;
	addObj.cycle_id =obj.cycle_id;
	if(owner==1){
		addObj.opportuniy ="ownership";
	}else if(owner==2){
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
			   loaderHide();
			   loaddata();
			}
		}
	});
}

function reject_opportunity(){
	var checkboxChkFlg=0;
	$("#tablebody input[name=op_id]").each(function(){
			if($(this).prop("checked") == true){
				checkboxChkFlg=1;
			}
	});
	if(checkboxChkFlg==0){
		alert("Please select the opportunity");
		return;
	}else{
		$('#reject_lid').modal('show');
		var opp_id=$('input[name=op_id]:checked').val();
		var aa = JSON.parse(opp_id);
		$('#opp_hidden_id').val(aa.opportunity_id);
		$('#reject_stage_id').val(aa.opportunity_stage);
		if(aa.owner_status==1){
			 $('#owner1').css({'display':'block'});
		}
		if(aa.stage_owner_status==1){
			$('#owner2').css({'display':'block'});
		}
	}
}

function final_rejection(){
	var checkboxChkFlg=0;
	$("#owner,#stage").each(function(){
		if($(this).prop("checked") == true){
			checkboxChkFlg=1;
		}
	});
	if(checkboxChkFlg==0){
		$("#owner").closest(".row").find(".error-alert").text("Select Decline type");
		return;
	}else{
		$("#owner").closest(".row").find(".error-alert").text("");
	}
	 if($("#rej_remarks").val()==""){
		$("#rej_remarks").closest("div").find("span").text("Please enter remarks for declining");
		$("#rej_remarks").focus();
		return;
	}else{
		$("#rej_remarks").closest("div").find("span").text("");
	}
	var id= $('#opp_hidden_id').val();
	var stage_id = $('#reject_stage_id').val();
	var addObj={};
	addObj.opp_reject=[];
	var radios = document.getElementsByName("opp_reject");
	for(var j=0;j<radios.length;j++){
		if(radios[j].checked){
		   addObj.opp_reject[j] = radios[j].value;
		}
	}
	var remarks=$("#rej_remarks").val();
	addObj.opportuniy_id =id;
	addObj.stage_id = stage_id;
	addObj.remarks =remarks;
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
			loaderHide();
			loaddata();
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
					<img src="<?php echo site_url()?>/images/new/i_off.png" onmouseover="this.src='<?php echo site_url()?>/images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url()?>/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" data-html="true" title="View new opportunities that have been assigned to you. Accept them to start progressing them through the stages or decline with a reason. Click on the <img src='<?php echo site_url(); ?>images/new/Plus_Off.png' width='20px' height='20px' /> button on the top right to add a new opportunity. Please note that you can only add new opportunities for leads that are in progress."/>
						</div>
					</span>
				</div>
				<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6 pageHeader1 aa">
						<h2>New Opportunities</h2>
				</div>
				<div class="col-xs-2 col-sm-2 col-md-3 col-lg-3 aa">
					<div class="addBtns addPlus">
						<span class="info-icon">
							<img src="<?php echo site_url()?>/images/new/Plus_Off.png" onmouseover="this.src='<?php echo site_url()?>/images/new/Plus_ON.png'" onmouseout="this.src='<?php echo site_url()?>/images/new/Plus_Off.png'" alt="info" width="30px" height="30px" class="dropdown-toggle" data-toggle="dropdown" />
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
                            <th class="table_header" style="text-align: left;">Industry</th>
                            <th class="table_header" style="text-align: left;">Location</th>
							<th class="table_header" style="text-align: left;">Stage</th>
							<th class="table_header" style="text-align: left;">Executive </th>
							<th class="table_header" style="text-align: left;">Stage Executive </th>
							<th class="table_header" data-orderable="false"></th>
						</tr>
					</thead>
					<tbody id="tablebody"></tbody>
				</table>
			</div>
			</div>
			<center>
				<input type="button" id="opp_rejectbtn" class="btn none" onclick="reject_opportunity()" value="Decline">
			</center>
		</div>
		<div id="reject_lid" class="modal fade" data-backdrop="static" >
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<span class="close" onclick="cancel()">x</span>
						<h4 class="modal-title">Choose</h4>
					</div>
					<input type="hidden" id="opp_hidden_id">
					<input type="hidden" id="reject_stage_id">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6" id="owner1" style="display:none">
								<input type="checkbox" name="opp_reject" id="owner" value="Ownership">
								<label for="owner">Opportunity Ownership</label>
							</div>
							<div class="col-md-6" id="owner2" style="display:none">
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
							<input type="button" class="btn" onclick="final_rejection()" value="Decline">
							<input type="button" class="btn" onclick="cancel()" value="Cancel" >
					</div>
				</div>
			</div>
		</div>
	<?php require ('footer.php'); ?>
</body>
</html>