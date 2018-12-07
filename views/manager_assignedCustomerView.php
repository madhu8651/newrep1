<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'scriptfiles.php' ?>
<script src="<?php echo site_url(); ?>js/prefixfree.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPquJYJq7KSiQPchdgioEVs-xOY4ERUdE&libraries=places" async defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<style>
audio{
	width: 225px;
}
.filter_select{
margin-top: 16px;	
}
.filter_label{	
	margin-top: 25px;	
}
.lead_address{
	background-color:#c1c1c1;
	padding: 10px 12px;
	margin-bottom: 17px;
	margin-top: 6px;
}
.lead_opper{
	background-color:#c1c1c1;
	padding: 10px 12px;
	margin-bottom:0;
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
.btn_log{
	margin-bottom: 5px;
}
.apport_label label{
	font-weight:bold!important;	
}
#files{  display:block;
                }

.ui-datepicker-month{
	margin-left: 19px!important;
	border: 1px solid lightgrey!important;
	border-radius: 5px!important;
	margin-right: 2px!important;
}
.ui-datepicker-year{
	border-radius: 5px;
	border-color: lightgrey;
}
.multiselect{
	height: 83px;
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


	.multiselect2{
	height: 180px;
	overflow: auto;
	border: 1px solid #ccc;
	border-radius: 5px;
	}
	.multiselect2 ul{
			padding: 0px;
	}
	.multiselect2 ul li.sel{
			background: #ccc;
	}
	.multiselect2 ul li{
			padding: 0 10px;
	}
	#leadview{
		overflow: auto;
	}
	#fetch_btn{
		margin-top: 4px;
    	margin-bottom: 4px;
	}
	.product_heading{
		background: #cecece;
		padding: 8px;
		color: black;
		font-weight: bold;
		margin-bottom: 5px;
	}
	.pro_Value,.e_pro_Value{
		margin-top: -39px;
		padding-left: 41px;
	}
	.provalue1{
		width: 50px;
		padding-top: 3px;
		margin-top: -1px;
	}
	.edit_purchase{
		float: right;
		margin-right: 14px;
	}
	#purchase_info{
		float: right;
		padding: 2px 9px!important;
	}
	#tablebody .tooltip.bottom .tooltip-arrow{
		color:black;
	}
	#tablebody .tooltip.bottom .tooltip-inner{
		background:black;
		color:white;
		text-align:left;
	}
	body{
		padding-right:0px!important;
	}
	.rejected_lead,
	.legend{
		background-color: rgba(180, 0, 10, 0.20) !important;
	}
	
	.legend{
		width: 30px;
		height: 30px;
		margin: -5px 10px 0px 0px;
	}
	.legend-wrapper{
		width: 200px;
		margin:auto;
		float: none; 
		margin-left:25px;
	}
</style>
<script>
function validate_cost(phone) {
	var nameReg = new RegExp(/^[0-9].+$/);
	var valid = nameReg.test(phone);	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function validate_quantity(phone) {
	var nameReg = new RegExp(/^[0-9]+$/);
	var valid = nameReg.test(phone);	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
var cust_data,purchase_data;
var mod_val,id_arr=[];
$(document).ready(function(){
	assignedLoad();	
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('manager_customerController/getUserModule');?>",
		dataType:'json',
		data:JSON.stringify(finalArray),
		success: function(data) {
			if(error_handler(data)){
				return;
			}
			mod_val = data[0].user_id;
			console.log(mod_val)
		}
	});
	$('#files').change(handleFile);
	$("#end_date").datepicker({
		changeMonth: true, 
		changeYear: true, 
		dateFormat: "dd/mm/yy",
		yearRange: "-90:+00"
	});	
	$('#select_map').hide();
	$("#map2").hide();
});

function assignedLoad1(){
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('manager_customerController/getMyCustomerDetails');?>",
		dataType:'json',
		success: function(data) {
				console.log(data)
				loaderHide();
				if(error_handler(data)){
                    return;
                }
				$('#tablebody1').parent("table").dataTable().fnDestroy();
				if(data.length > 0){
				$('#accept_newCustomer').removeClass('hidden');
				$('#reject_btn').removeClass('hidden');
			}
				var row = "";
				for(i=0; i < data.length; i++ ){
				data[i].customer_remarks= window.btoa(data[i].customer_remarks);
				data[i].customer_address= window.btoa(data[i].customer_address); 	
				 var status="";
                        if(data[i].status == "Active"){
                        status = "<b style='color:green'>Active</b>"
                        }else{
                        status = "<b style='color:red'>Inactive</b>"
                        }	

				var rowdata = JSON.stringify(data[i]);
				var pList = "";
				var pList1 = "";
				if(data[i].customer_products != "-"){
				var pArray = data[i].customer_products.split(",")
					for(p=0; p< pArray.length; p++){
						if(p<=1){        
							pList += "<li>"+pArray[p]+"</li>";
						}
						if(p > 1){
							pList1 += '<li>'+pArray[p]+'</li>';
						}
					}
					if(pArray.length > 2){
						pList += '<span rel="tooltip" data-toggle="tooltip" data-trigger="hover" data-placement="bottom" data-html="true" data-title="'+pList1+'"><u>'+(pArray.length - 2)+' more</u></span>';
					}
				}
				 row += "<tr><td>" + (i+1) + "</td><td>" + data[i].customer_name +"</td><td>" + data[i].contact_name +"</td><td>" + data[i].contact_desg+ "</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>" + data[i].customer_number +"</td><td>" + data[i].customer_email +"</td><td>" + data[i].customer_city +"</td><td>" + status +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td></td></tr>";			
			}		
			$('#tablebody1').empty();			
			$('#tablebody1').parent("table").removeClass('hidden');    
			$('#tablebody1').append(row);
			$('#tablebody1').parent("table").DataTable({
                "aoColumnDefs": [{ "bSortable": false, "aTargets": [9,10] }]
               });
		}
	});
	
}
function assignedLoad(){
	var reporting=[];	
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('manager_customerController/getCustomerAssignedDetails');?>",
		dataType:'json',
		success: function(data) {
			console.log(data)
			if(error_handler(data)){
                    return;
                }
			loaderHide();
			 customers=data;	
			if(customers.length > 0){
				$('#assign_btn1').removeClass('hidden');
			}
			
			$('#tablebody').parent("table").dataTable().fnDestroy();
				var row = "", owner_name = "";
				for(i=0; i < customers.length; i++ ){
					customers[i].customer_remarks= window.btoa(customers[i].customer_remarks);
					customers[i].customer_address= window.btoa(customers[i].customer_address);
					var status="";
					if(customers[i].status == "Active"){
						status = "<b style='color:green'>Active</b>"
					}else{
						status = "<b style='color:red'>Inactive</b>"
					}						
					var rowdata = JSON.stringify(customers[i]);	
					console.log(rowdata)
					var pList = "",num='myCustomer';
					var pList1 = "";
					if(customers[i].customer_products != "-"){
					var pArray = customers[i].customer_products.split(",")
						for(p=0; p< pArray.length; p++){
							if(p<=1){        
								pList += "<li>"+pArray[p]+"</li>";
							}
							if(p > 1){
								pList1 += '<li>'+pArray[p]+'</li>';
							}
						}
						if(pArray.length > 2){
							pList += '<span rel="tooltip" data-toggle="tooltip" data-trigger="hover" data-placement="bottom" data-html="true" data-title="'+pList1+'"><u>'+(pArray.length - 2)+' more</u></span>';
						}
					}
					if(customers[i].user_name == "-"){
						owner_name = "<b>Pending for acceptance</b>";
					}else{
						owner_name = customers[i].user_name;
					}
					if(customers[i].rejected_manager == '3' && customers[i].rejected_sales == '3'){
						row += "<tr class='rejected_lead'><td>" + "<input type='checkbox' name='"+customers[i].customer_rep_owner+"'val = '"+customers[i].customer_manager_owner+"' id='"+customers[i].customer_id+"' class='assign_class'/>" + "</td><td>" + (i+1) + "</td><td>" + customers[i].customer_name +"</td><td>" + customers[i].contact_name +"</td><td>" + customers[i].contact_desg+"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td><b>Rejected by All</b></td><td>" + customers[i].customer_number+"</td><td>" + customers[i].customer_email+"</td><td>" + customers[i].customer_city +"</td><td>" + status +"</td><td>" + customers[i].customer_manager_owner +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+",\"myCustomer\")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a href='#customerinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
					}else if(customers[i].rejected_manager == '3' && customers[i].rejected_sales <= '2'){
						row += "<tr class='rejected_lead'><td>" + "<input type='checkbox' name='"+customers[i].customer_rep_owner+"'val = '"+customers[i].customer_manager_owner+"' id='"+customers[i].customer_id+"' class='assign_class'/>" + "</td><td>" + (i+1) + "</td><td>" + customers[i].customer_name +"</td><td>" + customers[i].contact_name +"</td><td>" + customers[i].contact_desg+"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>" + owner_name +"</td><td>" + customers[i].customer_number+"</td><td>" + customers[i].customer_email+"</td><td>" + customers[i].customer_city +"</td><td>" + status +"</td><td><b>Rejected by customer managers<b></td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+",\"myCustomer\")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a href='#customerinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";

					}else if(customers[i].rejected_manager <= '2' && customers[i].rejected_sales == '3'){
						row += "<tr class='rejected_lead'><td>" + "<input type='checkbox' name='"+customers[i].customer_rep_owner+"'val = '"+customers[i].customer_manager_owner+"' id='"+customers[i].customer_id+"' class='assign_class'/>" + "</td><td>" + (i+1) + "</td><td>"+ customers[i].customer_name +"</td><td>" + customers[i].contact_name +"</td><td>" + customers[i].contact_desg+"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td><b>Rejected by customer Executives</b></td><td>" + customers[i].customer_number+"</td><td>" + customers[i].customer_email+"</td><td>" + customers[i].customer_city +"</td><td>" + status +"</td><td>"+ customers[i].customer_manager_owner +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+",\"myCustomer\")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a href='#customerinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";	

					}else if(customers[i].rejected_manager == '1'){
						row += "<tr><td>" + "<input type='checkbox' name='"+customers[i].customer_rep_owner+"'val = '"+customers[i].customer_manager_owner+"' id='"+customers[i].customer_id+"' class='assign_class'/>" + "</td><td>" + (i+1) + "</td><td>" + customers[i].customer_name +"</td><td>" + customers[i].contact_name +"</td><td>" + customers[i].contact_desg+"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>" + owner_name +"</td><td>" + customers[i].customer_number+"</td><td>" + customers[i].customer_email+"</td><td>" + customers[i].customer_city +"</td><td>" + status +"</td><td><b>Pending for acceptance</b></td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+",\"myCustomer\")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a href='#customerinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
					}else{
						row += "<tr><td>" + "<input type='checkbox' name='"+customers[i].customer_rep_owner+"'val = '"+customers[i].customer_manager_owner+"' id='"+customers[i].customer_id+"' class='assign_class'/>" + "</td><td>" + (i+1) + "</td><td>" + customers[i].customer_name +"</td><td>" + customers[i].contact_name +"</td><td>" + customers[i].contact_desg+"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>" + owner_name +"</td><td>" + customers[i].customer_number+"</td><td>" + customers[i].customer_email+"</td><td>" + customers[i].customer_city +"</td><td>" + status +"</td><td>" + customers[i].customer_manager_owner +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+",\"myCustomer\")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a href='#customerinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
					}
			}					
			$('#tablebody').empty();
			$('#tablebody').parent("table").removeClass('hidden');				
			$('#tablebody').append(row);
			$('#tablebody').parent("table").DataTable({
			"aoColumnDefs": [{ "bSortable": false, "aTargets": [9,10] }]
			});
				var checkid=[];
					$('#tablebody tr input[type=checkbox]').each(function(){
						checkid.push($(this).attr("val"));
						for (var i = 0; i<reporting.length; i++) {
						for(j=0;j<checkid.length;j++){
							if (reporting[i].user_id==checkid[j] ) {
								$(this).removeAttr("disabled");
							}
						}
					}
					});	
			$('.legend-wrapper').remove();
			$('.dataTables_length').append('<label class="legend-wrapper" ><div class="legend pull-left" title="Rejected Customers"></div> <b>Rejected Customers</b></label>');
			assignedLoad1();
		}
	});
}
function cancelCust(){
	$('.modal').modal('hide');
	$('.modal .form-control[type=text],.modal textarea').val("");
	$('.modal select.form-control').val($('.modal select.form-control option:first').val());
	$(".contact_type1").remove(); 
	$(".proDetail").remove(); 
}
function add_cancel(){
	$('.modal').modal('hide');
	$('.form-control').val("");
	$('#select_map').hide();
	$('#map1').show();
}
function cancel1(){	
	$('.prod_purchase_info').empty();
	$(".prod_purchase_info").css("height", 0);
	$(".prod_purchase_info").css("margin-bottom", 0);
	$(".prod_purchase_info").css("overflow", "none");
	$('.modal').modal('hide');
	$('.form-control').val("");
	//assignedLoad();	 
}
function close_modal(){
	$(".error-alert").empty();
	$('.modal').modal('hide');
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
	$('#files').val("");
	$(".contact_type1").remove(); 
	$(".proDetail").remove(); 
}
 function close_assign1(){
	$('#modalstart1').modal('hide');	
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
	$("#modal_upload").modal("hide");
	$('#modal_upload #files').val("");
 } 

function close_modal1(){
	$('#modalpurchase').modal('hide');
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
	$("#product_Currency").empty();
	$('#selcd_pro').empty();
	$('#pro_owner').empty();
	$('#add_product').hide();
	$('.fetch_btn').hide();
	$('.error-alert').empty();
}
function close_purchase(){
	$('#editmodalpurchase').modal('hide');
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
	$('#selcd_pro').empty();
	$('#add_product').hide();
	$('.fetch_btn').hide();
}

function checkAllMgrs(e)	{
	$('li input:checkbox',$("#mgrlist")).prop('checked',e.checked);
}
/* function append_dec(a){
	alert(a)
	var text_val1;
	var text_val = $(this).val();
	var strng_val = text_val.substr(0, text_val.indexOf('.'));
	if(strng_val){		
		text_val1 = text_val;
	}else{		
		text_val1 = text_val + '.00';
	}
	$(this).val(text_val1)
} */

</script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
	<div class="loader">
		<center><h1 id="loader_txt"></h1></center>
	</div>
        <?php require 'demo.php' ?>
        <?php require 'manager_sidenav.php' ?>
        <div class="content-wrapper body-content">
            <div class="col-lg-12 column">
				<div class="row header1">				
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
	                        <div>	
								<img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" data-html="true" title="1.‘My Customers’ are all the customers that you are the manager owner of.<br/>2.‘Team Customers’ are customers that managers or executives under you are working on "/>
	                        </div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Assigned Customers</h2>	
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						<div class="addBtns">
							
							<a  class="addExcel" onclick="addExl()" >
								<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a>
						</div>
                        <div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#first_tab_div"><h4>My Customers</h4></a></li>
					<li><a data-toggle="tab" href="#second_tab_div"><h4>Team Customers</h4></a></li>
				</ul>
				<div class="tab-content">
					<div id="first_tab_div" class="tab-pane fade in active">
						<div class="table-responsive">
							<table class="table hidden">
								<thead>  
								<tr>
								<th class="table_header"></th>
									<th class="table_header">SL No</th>
									<th class="table_header">Name</th>
									<th class="table_header">Contact Person</th>
									<th class="table_header"> Designation</th>
									<th class="table_header"> Products</th>
									<th class="table_header">Owned By</th>
									<th class="table_header">Phone</th>		
									<th class="table_header">Email</th>
									<th class="table_header">Location</th>	
									<th class="table_header">Customer Status</th>
									<th class="table_header">Customer Manager Owner</th>
									<th class="table_header"></th>
									<th class="table_header"><input type="hidden" id="purchase" /></th>		
								</tr>
								</thead>  
								<tbody id="tablebody">
								</tbody>    
							</table>
						</div>
						<div align="center">
							<input type="button" class="btn hidden" id="assign_btn1" onclick="assign_btn(1)" value="Reassign"/>
						</div>
					</div>
					<!------------------------------------------------------>
					<div id="second_tab_div" class="tab-pane fade">
						<div class="table-responsive">
							<table class="table hidden" style="width: 100%;">
								<thead>  
								<tr>
									<th>SL No</th>
									<th>Name</th>
									<th>Contact Person</th>
									<th> Designation</th>
									<th> Products</th>
									<th>Phone</th>		
									<th>Email</th>
									<th>Location</th>	
									<th>Customer Status</th>
									<th></th>
									<th></th>		
								</tr>
								</thead>  
								<tbody id="tablebody1">
								</tbody>    
							</table>
						</div>
					</div>
				</div>
				
            </div>
            <?php require 'manager_customerEdit_module.php' ?>
            <?php require 'manager_customerView_module.php' ?>
            <?php require 'manager_customerFile_module.php' ?>              
        </div>
        <?php require 'footer.php' ?>

    </body>
</html>
