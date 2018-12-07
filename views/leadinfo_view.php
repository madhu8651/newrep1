<!DOCTYPE html>
<html lang="en">
    <head>
<?php require 'scriptfiles.php' ?>
 <style>
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
$(document).ready(function(){
    loaddata(); 
});
function loaddata(){
	$('#tablebody').parent("table").dataTable().fnDestroy();
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('leadinfo_controller/display_new'); ?>",
		dataType:'json',
		success: function(data){
			if(data.length==0){
				$('#btn_show').hide();
			}
			if(error_handler(data)){
				return;
			}
			loaderHide();
			$('#tablebody').parent("table").dataTable().fnDestroy();
			var row="";
			for(i=0; i < data.length; i++ ){  
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
				row += "<tr><td><input type='checkbox' id='"+data[i].lead_id+"'/></td><td>" + data[i].lead_name +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul><td>" + data[i].industry +"</td></td><td>" + data[i].lead_city +"</td><td>" + data[i].contact_name +"</td><td>" + data[i].employeephone1+"</td><td>" + data[i].leademail +"</td><td>" + data[i].leadsurce +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'data-toggle='modal'><span class='glyphicon glyphicon-eye-open'></span></a></td></tr>";
			}
			$('#tablebody').html("").append(row);
			$('#tablebody').parent("table").DataTable({
				"aoColumnDefs": [{ "bSortable": false, "aTargets": [7] }]
			});
		},
		error:function(data){
			network_err_alert();
		}
	});
}
function cancel1(){
	$('.modal').modal('hide');
	$('.form-control').val("");
	$("#logdetails").hide();
	$("#oop_details").hide();
 } 
 function cancel_rej(){
	$("#reject_lid").modal("hide");
	$('.error-alert').hide();
	$('.form-control').val("");
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
			if(error_handler(data)){
				return;
			}
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
}


function reject_lead(status){
    $("#reject_lid .error-alert").text("");
    if(status=="single" ){
        $("#reject_lid").modal("show");
        var selected_lead = $('#lead_id').val();
        $('#reject_value').val(selected_lead);
    }
    
   if(status=="multiple" ){
       var rejectNewLd =[];
        $("#tablebody tr input[type=checkbox]").each(function(){
            if($(this).prop("checked") ==true){
                rejectNewLd.push($(this).attr("id"));
           }
        });
        if(rejectNewLd.length  == 0){
			alert("Please select at least one lead");
           /*  
			---- E26	4/9/18	New Leads	Only browser pop up should be display. -- Fixed-------
			$("#alert").modal('show');
            $("#alert .modal-body span").text("Please select the leads"); */
            return false;
        }
        $("#reject_lid").modal("show");
        var selected_leadid = rejectNewLd.toString();
        $('#reject_value').val(selected_leadid);
    }
}
function final_rejection(){
	$("#reject_lid .error-alert").text("");
	$("#reject_lid").modal("show");
	if($("#rej_remarks").val()==""){
		$("#rej_remarks").closest("div").find("span").text("Remarks is required.");
		$("#rej_remarks").focus();
		return;
	}else{
		$("#rej_remarks").closest("div").find("span").text("");
	}
	var addObj={};
	addObj.rej_remarks = $('#rej_remarks').val();
	addObj.reject_data=$('#reject_value').val();
	loaderShow();
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('leadinfo_controller/reject_multiple'); ?>",
        dataType: "json",
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){
			if(error_handler(data)){
				return;
            }
            if(data==1){
				$("#reject_lid").modal("hide");
				cancel1();
				loaddata();        
            }
        },
		error:function(data){
			network_err_alert();
		}
    });  
}

function accept_newlead(status){
	var acceptNewLd =[];
	var addObj={};
    if(status=="single" ){
        var accept_id=($('#lead_id').val());
        acceptNewLd.push(accept_id);
        addObj.reject_data=acceptNewLd;
    }
    if(status=="multiple" ){
        $("#tablebody tr input[type=checkbox]").each(function(){
            if($(this).prop("checked") ==true){
                acceptNewLd.push($(this).attr("id"));
            }
        });
        if(acceptNewLd.length  == 0){
			alert("Please select at least one lead");
            /* 
			---- E26	4/9/18	New Leads	Only browser pop up should be display. -- Fixed-------
			$("#alert").modal('show');
            $("#alert .modal-body span").text("Please select the leads"); */
            return false;
        }
        addObj.reject_data=acceptNewLd;
    }
	var total=acceptNewLd.length;
	loaderShow();
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('leadinfo_controller/accept_multiple'); ?>",
		dataType: "json",
		data    : JSON.stringify(addObj),
		cache : false,
		success : function(data){
			if(error_handler(data)){
				return;
			}
			$('#leadview').hide();
			var accept=data.length;
			if(accept==0){
				loaddata();
			}else{
				alert("out of "+total+" leads "+(total-accept)+" are accepted");
				cancel1();
				loaddata();
			}
		},
		error:function(data){
			network_err_alert();
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
						<span class="info-icon toolTipStyle">
							<div >		
								<img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" data-html="true" title="View the leads assigned to you by a manager. Accept a lead to start working on it, or you can decline it with a reason. Click on the <img src='<?php echo site_url(); ?>images/new/Plus_Off.png' width='20px' height='20px' /> button on the top right to add a new lead. The new lead will automatically go to your ‘Accepted’ leads."/>
							</div>
						</span>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>New Leads</h2>	
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
								<th class="table_header"></th>
							</tr>
						</thead>  
						<tbody id="tablebody">
						</tbody>    
					</table>
				</div>
			   <div align="center" id="btn_show">
					<input type="button" class="btn" id="multi_accept" onclick="accept_newlead('multiple')" value="Accept" />
					<input type="button" class="btn" id="multi_reject" onclick="reject_lead('multiple')"  value="Decline" />
				   <span class="error-alert"></span>
			   </div>
            </div>
            <?php require 'lead_add_view.php' ?>
            <div id="leadview" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
						<div class="modal-header">
							<span class="close" onclick="cancel1()">x</span>
							<h4 class="modal-title"><b>view Lead</b>
						</div>
						<input type="hidden" id="lead_id"/>
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
							<div class="row" id="custom_fields_view">
								
							</div>
							<br>
						</div>
						<div class="modal-footer">
							  <input type="button" class="btn" onclick="accept_newlead('single')" value="Accept" >
							  <button type="button" class="btn"    id="lead_rej" onclick="reject_lead('single')" >Decline</button>		
							 <button  type="button" class="btn btn-default" id="btn1_cancel" onclick="cancel1()" >Cancel</button>
						</div>
                    </div>
                </div>
            </div>  
	<div id="reject_lid" class="modal fade" data-backdrop="static" >
			<div class="modal-dialog">
				<div class="modal-content">
					<form  class="form" action="#" method="post" name="adminClient">
						<div class="modal-header">
							<span class="close" onclick="cancel1()">x</span>	
							<h4 class="modal-title">Choose</h4>
						</div>	
                                            <input type="hidden" id="reject_value" />
						<div class="modal-body">							
							<div class="row" id="reject_remarks">	
								<div class="col-md-12">
									<label>Remarks*</label>
									<textarea name="Remarks" class="form-control" id="rej_remarks"></textarea> 
									<span class="error-alert"></span>
								</div>
							</div>							
						</div>
						<div class="modal-footer">
							<input type="button" class="btn" onclick="final_rejection()" value="Reject">
							<input type="button" class="btn" onclick="cancel_rej()" value="Cancel" >
						</div>
					</form>
				</div>
			</div>
		</div>
        </div>
        <div id="alert" class="modal fade" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">                                               
                <div class="modal-body">
                 <div class="row">
                   <span></span>
                   <br>
                   <br>
                   <input type="button" class="btn" data-dismiss="modal" value="Ok">
                 </div>
                </div>                            
            </div>
        </div>
        </div>
        <?php require 'footer.php' ?>

    </body>
</html>
