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
    loaddata();   
    $('.input-group.date').datetimepicker({
        minDate:new Date(),
        ignoreReadonly: true,
        allowInputToggle:true,
        format: 'DD-MM-YYYY'
    });
/* $('#scheduled_activity').hide();
$("#Scheduled_log").click(function(){
   $('#scheduled_activity').toggle();
});
$('#logdetails').hide();
$("#leadlog").click(function(){
     $('#logdetails').toggle();
});
 $("#opp_log").click(function(){
     $('#oop_details').toggle();
 }); */
 
});
function loaddata(){
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('leadinfo_controller/display_accept'); ?>",
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
		},
		error:function(data){
			network_err_alert();
		}
	});
}
function cancel(){
	$('.modal').modal('hide');
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
	$('#opp_table').empty();
	$('#activitylog').empty();
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
	personal.logo = obj.lead_picture;
	personal.source = obj.leadsurce;
	
	
	/* $("#label_leadweb").html(obj.lead_website);
	$("#label_leadmail").text(obj.leademail);
	$("#label_leadphone").text(obj.leadphone);
	$("#label_leadsource").html(obj.leadsurce);
	$("#label_country").html(obj.country);
	$("#label_state").html(obj.state);
	$("#label_city").html(obj.lead_city);
	$("#label_zipcode").html(obj.lead_zip);
	$("#label_indus").html(obj.industry);
	$("#label_business").html(obj.location);
	if(obj.lead_picture!=null){
		$('#leadpic').attr('src', "<?php echo base_url(); ?>uploads/"+obj.lead_picture);
	}else{
		$('#leadpic').attr('src', "<?php echo base_url(); ?>images/default-pic.jpg");
	} */
	
	$('#leadname_label').text(obj.lead_name);
	$('#lead_id').val(obj.lead_id);
	$("#view_ofcadd").val(obj.lead_address);
	$("#view_splcomments").val(obj.lead_remarks);
	
	var id=obj.lead_id;
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
	
	contact_prsn_list(obj.lead_id, "contact_prsn_list" ,"lead","sales");
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
                                        <img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" data-html="true" title="View the leads you have accepted. Complete a task to put them ‘In Progress.’ Click the <img src='<?php echo site_url(); ?>images/new/Plus_Off.png' width='20px' height='20px' /> button on the top right to add a new lead."/>
                                </div>
                        </span>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
                            <h2>Accepted Leads</h2>	
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
                            <div class="modal-header">
                                <span class="close" onclick="cancel1()">x</span>
                                <h4 class="modal-title"><b>view Lead</b></h4>
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
                            <div class="modal-footer">
                                    <button  type="button" class="btn btn-default" id="btn1_cancel" onclick="cancel1()" >Cancel</button>
                            </div>
                    </form>
                    </div>
                </div>
            </div> 
        </div>
        <?php require 'footer.php' ?>

    </body>
</html>
