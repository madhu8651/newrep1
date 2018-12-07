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
$(document).ready(function(){
        $('.input-group.date').datetimepicker({
            ignoreReadonly:true,
            allowInputToggle:true,
            format:'lll',
            minDate: moment()
        });
        $("#activity_duration").datetimepicker({
            ignoreReadonly:true,
            allowInputToggle:true,
            format:'HH:mm',
            defaultDate:'1970-01-01 00:00:00'
		});
        loaddata();   
	/* $('#logdetails').hide();
	$("#leadlog").click(function(){
		$('#logdetails').toggle();
	});
	$("#opp_log").click(function(){
		$('#oop_details').toggle();
	});
	$('#scheduled_activity').hide();
	$("#Scheduled_log").click(function(){
		$('#scheduled_activity').toggle();
	}); */
	
 $("#won").click(function(){
    if($("#won").is(':checked')){
            $("#won").prop("value", 1);
            $("#lost").prop("value", 0);
            $("#Temporary").prop("value",0);
            $("#Permanent").prop("value",0);
            $("#remarks").show();
            $("#remarks1").hide();
            $("#remarks2").hide();
            $("#lost_id").hide();
            $("#Temp_date,#future_activity,#act_duration,#reminder,#contact_details,#task_title").hide();
    }else{
            $("#won").prop("value", 0);
            $("#remarks1").hide();
            $("#remarks2").hide();
            $("#remarks").hide();
            $("#lost_id").hide();
            $("#Temp_date,#future_activity,#act_duration,#reminder,#contact_details,#task_title").hide();
            $("#Permanent").hide();
    }
});
$("#lost").click(function(){
        $("#won").prop('value',0);
        $("#Temporary").prop('checked',false);
        $("#Permanent").prop('checked',false);
        if($("#lost").is(':checked')){
				$("#Temporary").prop('checked',true);
                $("#lost").prop("value", 1);
                $("#won").prop("value", 0);
                $("#lost_id").show();
                $("#remarks").hide();
                $("#remarks1").hide();
                $("#remarks2").hide();
				/* copy paste from below on 28-06-2018 --- E17 V1.1 */
				if($("#Temporary").is(':checked')){
						$("#Temporary").prop("value",1);
						$("#Permanent").prop("value",0);
						$("#Temp_date,#remarks2,#future_activity,#act_duration,#reminder,#contact_details,#task_title").show();
						$("#remarks,#remarks1").hide();
				}else{
						$("#Temporary").prop("value",0);
						$("#Temp_date,#future_activity,#act_duration,#reminder,#contact_details,#task_title").hide();
						$("#remarks").hide();
						$("#remarks1").hide();
						$("#remarks2").hide();
				}
				
        }else{
                $("#lost").prop("value", 0);
                $("#lost_id").hide();
                $("#remarks").hide();
                $("#remarks1").hide();
                $("#remarks2").hide();
        }
});
$("#Temporary").click(function(){
        if($("#Temporary").is(':checked')){
                $("#Temporary").prop("value",1);
                $("#Permanent").prop("value",0);
                $("#Temp_date,#remarks2,#future_activity,#act_duration,#reminder,#contact_details,#task_title").show();
                $("#remarks,#remarks1").hide();
        }else{
                $("#Temporary").prop("value",0);
                $("#Temp_date,#future_activity,#act_duration,#reminder,#contact_details,#task_title").hide();
                $("#remarks").hide();
                $("#remarks1").hide();
                $("#remarks2").hide();
        }
});
$("#Permanent").click(function(){
        if($("#Permanent").is(':checked')){
                $("#Permanent").prop("value",1);
                $("#Temporary").prop("value",0);			
                $("#remarks1").show();
                $("#remarks2").hide();
                $("#remarks").hide();
                $("#Temp_date,#future_activity,#act_duration,#reminder,#contact_details,#task_title").hide();
        }else{
                $("#Permanent").prop("value",0);
                $("#remarks").hide();
                $("#remarks1").hide();
                $("#remarks2").hide();
                $("#Temp_date,#future_activity,#act_duration,#reminder,#contact_details,#task_title").hide();
        }
});
});
function loaddata(){
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('leadinfo_controller/display_active'); ?>",
            dataType:'json',
            success: function(data) {
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
                    row += "<tr><td>" +(i+1)+ "</td><td>" + data[i].lead_name +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul><td>" + data[i].industry +"</td></td><td>" + data[i].lead_city +"</td><td>" + data[i].contact_name +"</td><td>" + data[i].employeephone1+"</td><td>" + data[i].leademail +"</td><td>" + data[i].leadsurce +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'data-toggle='modal'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a href='#leadinfoedit' onclick='selrow("+rowdata+")' data-toggle='modal'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";			
           }
                $('#tablebody').html("").append(row);
                $('#tablebody').parent("table").DataTable({
                    "aoColumnDefs": [{ "bSortable": false, "aTargets": [8] }]
               });

                
            }
        });
}
function cancel(){
	$('.modal').modal('hide');
	$('.form-control').val("");
        $('.error-alert').hide();
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
	$('#activitylog').empty();
	$('#opp_table').empty();
	$('#logtable').empty();
	$("#custom_fields_view input[type=text]").val("");
 } 
function cancel_opp(){
	$('#closed_opp').modal('hide');
	$('.form-control').val("");		
	$("#Temp_date,#future_activity,#contact_details,#task_title,#act_duration,#reminder,#remarks,#remarks1,#remarks2,#lost_id").hide();
	$('#closed_opp input[type="text"],textarea').val('');
	$('#closed_opp input[type="radio"]').prop('checked', false);
	$('#closed_opp input[type="checkbox"]').prop('checked', false);
	$(".error-alert").text("");
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
	
	$('#leadname_label').text(obj.lead_name);
	$('#close_leadname').val(obj.lead_name);
	$('#lead_id').val(obj.lead_id);
	
	/* $("#label_leadweb").html(obj.lead_website);
	$("#label_leadmail").text(obj.leademail);
	$("#label_leadphone").text(obj.leadphone);
	$("#label_leadsource").html(obj.leadsurce);
	$("#label_country").html(obj.country);
	$("#label_state").html(obj.state);
	$("#label_city").html(obj.lead_city);
	$("#label_zipcode").html(obj.lead_zip); */
	
	$("#view_ofcadd").val(obj.lead_address);
	$("#view_splcomments").val(obj.lead_remarks);
	
	/* $("#label_designation").html(obj.contact_desg);
	$("#label_primmobile").text(obj.employeephone1);
	$("#label_primmobile2").text(obj.employeephone2);
	$("#label_primemail").text(obj.employeeemail);
	$("#label_primemai2").text(obj.employeeemail2);
	$("#label_contacttype").html(obj.contact);
	$("#lead_firstcontact").html(obj.contact_name); */
	
	$("#label_indus").html(obj.industry);
	$("#label_business").html(obj.location);
	
	/* if(obj.lead_logo!=null){
		$('#leadpic').attr('src', "<?php echo base_url(); ?>uploads/"+obj.lead_logo);
	}else{
		$('#leadpic').attr('src', "<?php echo base_url(); ?>images/default-pic.jpg");
	} */
	
	contact_prsn_list(obj.lead_id, "contact_prsn_list" ,"lead","sales");
	
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('leadinfo_controller/product_view'); ?>",
		data : "id="+obj.lead_id,
		dataType:'json',
		success: function(data){
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
	
	var obj1={};
	obj1.lead_id = obj.lead_id;
    $.ajax({
		type: "POST",
		url: "<?php echo site_url('leadinfo_controller/getCustomData');?>",
		data:JSON.stringify(obj1),
		dataType:'json',
		success: function(data){
			if(data==0){
				$("#custom_head_view").hide();
			}else{
				$('#custom_fields_view').empty();
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
}
function add1(){
	$(".error-alert").text("")
	if($('#lead_activity').is(":checked")){
		var activity=1;
    }else{
       var activity=0;
    }
    if($("#won").prop("checked")== false && $("#lost").prop("checked")== false ){
        $("#won").closest(".row").find(".error-alert").text("Select Temporary or Permanent.");
        return;
    }else{
        $("#won").closest(".row").find(".error-alert").text("");
    }
	if($("#lost").prop("checked")== true){                    
		/* if($("#Permanent").prop("checked")== false && $("#Temporary").prop("checked")== false ){ */
		if($("#Temporary").prop("checked")== false){                        
			$("#Temporary").closest(".row").find(".error-alert").text("Select loss type.");
			return;
		}else{
			$("#Temporary").closest(".row").find(".error-alert").text("");
		}
               
		if($("#Permanent").prop("checked")== true){
			if($("#lost_remarks").val()==""){
				$("#lost_remarks").closest("div").find(".error-alert").text("Remarks is required.");
				$("#lost_remarks").focus();				
				return;
			}else if(!comment_validation($.trim($("#lost_remarks").val()))){
				$("#lost_remarks").closest("div").find(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
				$("#lost_remarks").focus();
				return;
			}else{
				$("#lost_remarks").closest("div").find(".error-alert").text("");
			}
		}
		if($("#Temporary").prop("checked")== true){
			if($.trim($("#activity_title").val())==""){
				$("#activity_title").next(".error-alert").text("Title is required.");
				$("#activity_title").focus();	
				return;
			}else if(!validate_name($.trim($("#activity_title").val()))){
				$("#activity_title").next(".error-alert").text("No special character.");
				$("#activity_title").focus();	
				return;
			}else{
				$("#activity_title").next(".error-alert").text("");
			}
			
			if($("#future_activity select").val() == ""){
				$("#future_activity select").next(".error-alert").text("Future activity is required.");
				$("#future_activity select").focus();	
				return;
			}else{
				$("#future_activity select").next(".error-alert").text("");
			}
			
			if($("#tempdate").val()==""){
				$("#tempdate").closest(".form-group").find(".error-alert").text("Date is required.");
				return;
			}else{
				$("#tempdate").closest(".form-group").find(".error-alert").text("");
			}
			
			if($.trim($("#activity_duration").val()) == ""){
				$("#activity_duration").next(".error-alert").text("Specify estimated activity duration.");
				return;	
			} else{
				var duration1 = $.trim($("#activity_duration").val()).split(":");
				if( parseInt(duration1[0]) == 0 && parseInt(duration1[1]) == 0){
					$("#activity_duration").next(".error-alert").text("Select valid duration time.");
					return;
				}else{
					$("#activity_duration").next(".error-alert").text("");
				}
			}
			
			if($("#reminder_time").val()==""){
				$("#reminder_time").closest("div").find("span").text("Select alert before time.");
				$("#reminder_time").focus();				
				return;
			}else{
				$("#reminder_time").closest("div").find("span").text("");
			}
			
			if($("#contact_details select").val()==""){
				$("#contact_details select").closest("div").find("span").text("Select contact type.");
				$("#contact_details select").focus();				
				return;
			}else{
				$("#contact_details select").closest("div").find("span").text("");
			}
			
			if($.trim($("#temp_remarks").val())==""){
				$("#temp_remarks").closest("div").find(".error-alert").text("Remarks is required.");
				$("#temp_remarks").focus();				
				return;
			}else if(!comment_validation($.trim($("#temp_remarks").val()))){
				$("#temp_remarks").closest("div").find(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
				$("#temp_remarks").focus();
				return;
			}else{
				$("#temp_remarks").closest("div").find(".error-alert").text("");
			}		
		}
	}
	var addObj={};
	if($("#won").prop("checked")== true){
		if($.trim($("#won_remarks").val()) == ""){
			$("#won_remarks").closest("div").find(".error-alert").text("Remarks is required.");
			return;
		}else if(!comment_validation($.trim($("#won_remarks").val()))){
				$("#won_remarks").closest("div").find(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
				$("#won_remarks").focus();
				return;
		}else{
			$("#won_remarks").closest("div").find(".error-alert").text("");
		}
		addObj.remarks = $.trim($("#won_remarks").val());
		addObj.reason='permanent_loss';
		addObj.leadid = $.trim($("#lead_id").val());
		addObj.lead_name = $.trim($("#close_leadname").val());	
		addObj.lead_title ="";
		addObj.date ="";
		addObj.activity = activity;
		addObj.duration = "";
		addObj.activity = activity;
		addObj.future_activity = "";
		addObj.reminder = "";
		addObj.contact_id = "";
    }else{
		if($("#Temporary").prop("checked")== true){
			addObj.reason ='temporary_loss';
			addObj.remarks = $.trim($("#temp_remarks").val());
			addObj.leadid = $.trim($("#lead_id").val());
			addObj.lead_name = $.trim($("#close_leadname").val());
			addObj.lead_title = $.trim($("#activity_title").val());
			var startDateTime = moment($.trim($("#tempdate").val()), 'lll').format('YYYY-MM-DD HH:mm:ss');
			addObj.date = startDateTime;
			var timeDuradion = moment($.trim($("#activity_duration").val()), 'H [Hrs] m [mins] ss [sec]').format('HH:mm:ss');
			addObj.duration = timeDuradion;
			addObj.activity = activity;
			addObj.future_activity = $("#future_activity select").val();
			addObj.reminder = $("#reminder_time").val();
			addObj.contact_id = $("#contact_details select").val();
		}
	}
	loaderShow();
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('leadinfo_controller/close_lead'); ?>",
		dataType : 'json',
		data : JSON.stringify(addObj),
		cache : false,
		success : function(data){		
			if(error_handler(data)){
				return;
			}
			loaderHide();
			if(data==1){
				alert("Lead has been closed successfully!");
				cancel_opp();
				cancel1();
				loaddata(); 
			}
		},
		error:function(data){
			network_err_alert();
		}		
	});	
}

function futureActivity(){
	$.ajax({
		type: "POST",
		url:"<?php echo site_url('leadinfo_controller/get_activityList')?>",
		dataType:'json',
		success: function(data){
			if(error_handler(data)){
				return;
			}
			var select = $("#future_activity select"), options = "<option value=''>Select</option>";
			select.empty();
			for(var i=0;i<data.length; i++){
				options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value+" </option>";
			}
			select.append(options);
		},
		error:function(data){
			network_err_alert();
		}
	});
}

function get_contacts(){
	var leadid = $.trim($("#lead_id").val());
	$.ajax({
		type: "POST",
		url:"<?php echo site_url('leadinfo_controller/get_contactList/')?>"+leadid,
		dataType:'json',
		success: function(data){
			if(error_handler(data)){
				return;
			}
			var select = $("#contact_details select"), options = "<option value=''>Select</option>";
			select.empty();
			for(var i=0;i<data.length; i++){
				options += "<option value='"+data[i].contact_id+"'>"+ data[i].contact_name+" </option>";
			}
			select.append(options);
		},
		error:function(data){
			network_err_alert();
		}
	});
}

function check_opportunity(){
	get_contacts();
	futureActivity();
    var id=$('#lead_id').val();
    loaderShow();
    $.ajax({
        type: "POST",
        url:  "<?php echo site_url('leadinfo_controller/check_owner'); ?>",
        data : "id="+id,
        dataType:'json',
        success: function(data){
			if(error_handler(data)){
				return;
			}
			loaderHide();
			if(data == 0){
				$('#closed_opp').modal('show');
			}else if(data == 1){
				alert("User does not have the authority to close the lead!")
				return;
			}else if(data == 2){
				var alertMsg = confirm("One or more opportunity exists \nDo you still want to close the lead!");
				if (alertMsg == true) {
					$('#closed_opp').modal('show');
				}
				
			}
			/* if(data==0){
				alert("You can not close the Lead, since there exist an active Opportunity for this.");
				return false;
			}
			else if(data==1){
				$('#closed_opp').modal('show');
			} */
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
						<span class="info-icon">
							<div>		
								<img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" title="View the leads that are currently in progress. All of these can be turned into opportunities whenever necessary."/>
							</div>
						</span>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>In Progress Leads</h2>	
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
								<th class="table_header"></th>		
							</tr>
						</thead>  
						<tbody id="tablebody">
						</tbody>    
					</table>
                </div>
            </div>
			<?php require 'lead_add_view.php' ?>
			<?php require 'lead_edit_view.php' ?>
            <div id="leadview" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
						<input type="hidden" id="lead_id"/>
                        <form id="viewpopup" class="form" action="#" method="post" >
							<input type="hidden" id="close_leadname">
                            <div class="modal-header">
                                <span class="close" onclick="cancel1()">x</span>
                                <h4 class="modal-title"><b>view Lead</b>
                               </h4>
                            </div>
                            <div class="modal-body">
							<div id='leadInfoView'></div>
                               
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
								<div class="row" id="view_maploc" style="width:100% px;height:150px;border:1px;">
								</div>
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
							</div>-->   
							<div class="row none" id="custom_head_view">
								<div class="col-md-12 lead_address">
									<center><b>Custom Fields</b></center>
								</div>
							</div>
							<div class="row" id="custom_fields_view"></div>
							<br>
							 <?php require 'sales-view-tab.php' ?>
							
							</div>
							
                            <div class="modal-footer" style="padding-left:0px">
								<span class="pull-left" >									
									<button type="button" class="btn"  onclick="check_opportunity();" id="lead_close" data-toggle="modal">Close Lead</button>
								</span>
                                 <button  type="button" class="btn btn-default" id="btn1_cancel" onclick="cancel1()" >Cancel</button>
                            </div>
                          </div>
                      </form>
                    </div>
                </div>
            </div> 
            <div id="closed_opp" class="modal fade" data-backdrop="static" >
                <div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<span class="close" onclick="cancel_opp()" >x</span>	
							<h4 class="modal-title">Choose Loss Type</h4>
						</div>				
						<div class="modal-body">						
							<div class="row">
								<div class="col-md-4">
									<input type="radio" name="radio1"  id="won" value="1"/> <label for="won">Permanent</label> 
								</div>
								<div class="col-md-4">
									<input type="radio" name="radio1" id="lost" value="0"/> <label for="lost">Temporary</label> 
								</div>
								<div class="col-md-4">
									<input type="checkbox" name="lead_activity" id="lead_activity"/> <label for="lead_activity">Close all future activities</label> 
								</div>
								<span class="error-alert"></span>
							</div><br/>
							<div class="row none" id="lost_id">		
								<div class="col-md-6">
									<input type="radio"  checked name="Temporary" value="1" id="Temporary"/> <label for="Temporary">Push to Myself</label>
								</div>
								<!--<div class="col-md-6">
									<input title="We will Update soon."type="radio" disabled="disabled" name="Permanent" value="0" id="Permanent"/> <label for="Permanent">Push to Sales Mil</label>
								</div>-->
								<span class="error-alert"></span>
							</div>
							<div class="row none" id="task_title">
								<div class="col-md-3">										
									<span>Title*</span>
								</div>
								<div class="col-md-9">
									<input type="text" id="activity_title" class="form-control" >
									<span class="error-alert"></span>							
								</div>
							</div>
							<div class="row none" id="future_activity">
								<div class="col-md-3">										
									<span>Future Activity*</span>
								</div>
								<div class="col-md-9">
									<select  class="form-control"></select>
									<span class="error-alert"></span>							
								</div>
							</div>
							<div class="row none" id="Temp_date">
								<div class="col-md-3">										
										<span>Remind Me On*</span>
								</div>
								<div class="col-md-9">
									<div class="form-group">
										<div class='input-group date'>
											<input type='text' class="form-control" placeholder="DD-MM-YYYY" id="tempdate" readonly />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<span class="error-alert"></span>
									</div>							
								</div>
							</div>
							<div class="row none" id="act_duration">
								<div class="col-md-3">										
										<span>Activity duration*</span>
								</div>
								<div class="col-md-9">
									<input type="text" id="activity_duration" class="form-control" placeholder="HH:MM"  maxlength="5">									
									<span class="error-alert"></span>							
								</div>
							</div>
							<div class="row none" id="reminder">
								<div class="col-md-3">										
									<span>Alert Before*</span>
								</div>
								<div class="col-md-9">
									<select class="form-control" id="reminder_time">
										<option value="">Select</option>
										<option value="5">5 mins</option>
										<option value="15">15 mins</option>
										<option value="30">30 mins</option>
									</select>
								<span class="error-alert"></span>						
								</div>
							</div>
							<div class="row none" id="contact_details">
								<div class="col-md-3">										
									<span>Contact Type*</span>
								</div>
								<div class="col-md-9">
									<select  class="form-control"></select>
									<span class="error-alert"></span>							
								</div>
							</div>
							<div class="row none" id="remarks">	
								<div class="col-md-3">
									<label>Remarks*</label>
								</div>
								<div class="col-md-9">
									<textarea name="Remarks" class="form-control" id="won_remarks"></textarea> 
									<span class="error-alert"></span>
								</div>
							</div>
							<div class="row none" id="remarks1">
								<div class="col-md-3">
									<label>Remarks*</label>
								</div>	
								<div class="col-md-9">
									<textarea name="Remarks" class="form-control" id="lost_remarks"></textarea> 
									<span class="error-alert"></span>
								</div>
							</div>
							<div class="row none" id="remarks2">	
								<div class="col-md-3">
									<label>Remarks*</label>
								</div>
								<div class="col-md-9">
									<textarea name="Remarks" class="form-control" id="temp_remarks"></textarea> 
									<span class="error-alert"></span>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<input type="button" class="btn" onclick="add1()" value="Close">
							<input type="button" class="btn" onclick="cancel_opp()" value="Cancel" >
						</div>
					</div>
				</div>
			</div>
        </div>
        <?php require 'footer.php' ?>

    </body>
</html>
