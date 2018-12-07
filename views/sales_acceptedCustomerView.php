<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'scriptfiles.php' ?>
<script src="/js/prefixfree.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPquJYJq7KSiQPchdgioEVs-xOY4ERUdE&libraries=places" async defer></script>
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
	.tree-view ul{
        padding-left:20px;
        border-left: 1px dotted;
}
.tree-view ul.mytree{
        border-left: 0px;
}
.tree-view ul li label{
        margin-bottom: 0px;
}
.dash-left .glyphicon {
        position: absolute;
}
.dash-left{
        margin-left: -17px;
        float: left;
        position: absolute;
}
.tree-view input{
    margin-top: 0px;
}

#tree_leadsource{
		position: absolute;
		background: white;
		z-index: 99;
		top: -50px;
		left: 100px;
		border: 1px solid #ccc;
		padding: 10px;
		border-radius: 5px;
	}
	#tree_leadsource1{
		position: absolute;
		background: white;
		z-index: 99;
		top: -50px;
		left: 100px;
		border: 1px solid #ccc;
		padding: 10px;
		border-radius: 5px;
	}
	.multiselect2{
	height: 60px;
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
	.pro_Value{
		margin-top: -39px;
		padding-left: 38px;
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
</style>
<script type="text/javascript">
/* Validation : first character digit */
	function firstLetter(name) {
		var nameReg = new RegExp(/^[a-zA-Z0-9]/);
		var valid = nameReg.test(name);
		if (!valid) {
			return false;
		} else {
			return true;
		}
	}
	function comment_validation(name) {
			var nameReg = new RegExp(/^[a-zA-Z0-9 $&:()#@\n_.,+%?-]*$/);
			var valid = nameReg.test(name);
			if (!valid) {
				return false;
			} else {
				return true;
			}
	}

function filevalidation(input){
	var elm = document.getElementById(input);
	if (elm.files && elm.files[0]){
		var reader = new FileReader();
		var valid_extensions = /(\.jpg|\.jpeg|\.gif|\.bmp|\.png|\.JPG|\.JPEG|\.GIF|\.BMP|\.PNG)$/i;   
		if(!valid_extensions.test(elm.files[0].name)){ 
			$("#"+input).val("");
			$("#"+input).closest('div').find('.error-alert').text("Invalid File type.");
			return;  
		}else if(elm.files[0].size >= 1000000){
			$("#"+input).val("");
			$("#"+input).closest('div').find('.error-alert').text("File size is too long.");
			return;
		}else{
			$("#"+input).closest('div').find('.error-alert').text("");
		}
	}
}
</script>
<script>
/* 	function edit_tree(data, container){
    $("#"+container).html("");
	var oflocArray = convert(data);
	var $ul = $('<ul class="mytree"></ul>');
	getList(oflocArray, $ul);
	$ul.appendTo("#"+container);
	var display_list=[];

	$("#"+container+" input[type=radio]").each(function(){

		if($(this).closest('li').children('ul').length > 0){

			$(this).closest('label').closest('div').find('.glyphicon').addClass('glyphicon-minus-sign');
		}else{
			$(this).closest("label").css({'text-decoration':'underline','font-style':'italic'}).addClass('lastnode');
		}

		$(this).change(function(){
			display_list=[];
			if($(this).prop('checked')==true){
				if($(this).closest('li').children('ul').length > 0){
					$(this).closest('li').children('ul').find(".lastnode").each(function(){
						display_list.push($.trim($(this).text()));
					});
				}
			}
			var html= '';
			for(i=0; i< display_list.length; i++){
				html +="<li>"+display_list[i]+"</li>"
			}

			$(this).closest(".row").find('ol').html(html);
		})
	});

	$("#"+container+" input[type=radio]").each(function(){
		if($(this).prop('checked')==true){
				displayList($(this).val());
			}
	}) */
	/* hide/show child node on click of plus/minus */
	/* $("#"+container+"  label.glyphicon").each(function(){
		$(this).click(function(){
			if($(this).closest('li').children('ul').length > 0 || $(this).closest('li').children('ul').css('display')=="block" ){

				$(this).closest('li').children('ul').hide(1000);
				$(this).closest('div').find('.glyphicon-minus-sign').removeClass('glyphicon-minus-sign').addClass('glyphicon-plus-sign');
			}
			if($(this).closest('li').children('ul').css('display')=="none" ){

				$(this).closest('li').children('ul').show(1000);
				$(this).closest('div').find('.glyphicon-plus-sign').removeClass('glyphicon-plus-sign').addClass('glyphicon-minus-sign');
			}
		})
	})
}	
 */

 function convert(data){
			var map = {};
			for(var i = 0; i < data.length; i++){
				 var obj = data[i];
				obj.children= [];
				map[obj.id] = obj;
				var parent = obj.parent || '-';
				if(!map[parent]){
					map[parent] = {
						children: []
					};
				}
				map[parent].children.push(obj);
			}
			return map['-'].children;
	    }
		 /* ------------------------ constructing tree structure ---------------- */
		function getList(item, $list) {
			if($.isArray(item)){
				$.each(item, function (key, value) {
					getList(value, $list);
				});

			}

			if (item) {
				var $li = $('<li />');
				if (item.name) {

					if(item.checked == true){
						$li.append($("<div><div class='dash-left'><label class='glyphicon' id='"+item.id+"'></label>---</div><label> <input name='"+item.nameAttr+"' type='radio' value='"+item.id+"' id='id"+item.id+"'  checked>  " + item.name + "</label></div>"));
					}else{
						$li.append($("<div><div class='dash-left'><label class='glyphicon' id='"+item.id+"'></label>---</div><label> <input  name='"+item.nameAttr+"' type='radio' value='"+item.id+"' id='id"+item.id+"'>  " + item.name + "</label></div>"));
					}
				}
				if (item.children && item.children.length) {
					var $sublist = $("<ul class=child-count-"+item.children.length+"></ul>");
					getList(item.children, $sublist)
					$li.append($sublist);
				}
				$list.append($li)
			}
		}
  

</script>
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
/* function add_contact(){
	$('#leadinfoedit .modal-body .edit_CustContact').append("<div class='contact_type1'><div class='row' ><div class='col-md-12 lead_address'><center><b>Customer Contact Person Information</b></center></div></div><div class='row'><div class='col-md-2'><label>Contact Person*</label></div><div class='col-md-4'><input type='text' class='form-control edit_firstcontact' name='edit_firstcontact' ><span class='error-alert'></span></div><div class='col-md-2'><label>Designation</label></div><div class='col-md-4'><input type='text' class='form-control edit_disgnation' name='edit_disgnation' ><span class='error-alert'></span></div></div><div class='row'><div class='col-md-2'> <label>Mobile Number 1</label></div><div class='col-md-4'><input type='text' class='form-control edit_primmobile' ><span class='error-alert'></span></div><div class='col-md-2'><label>Mobile Number 2*</label></div><div class='col-md-4'><input type='text' class='form-control edit_primmobile2' name='edit_primmobile2' ><span class='error-alert'></span></div></div><div class='row'><div class='col-md-2'><label>Email 1</label></div><div class='col-md-4'><input type='text' class='form-control edit_primemail' ><span class='error-alert'></span></div><div class='col-md-2'><label>Email 2</label></div><div class='col-md-4'><input type='text' class='form-control edit_primemai2'><span class='error-alert'></span></div></div><div class='row'><div class='col-md-2'><label for='edit_displaypic'>Photo</label></div><div class='col-md-4'><label for='adminImageUploadE' class='custom-file-upload'><i class='fa fa-cloud-upload'></i> Image Upload</label><input type='file' class='form-controz' id='displaypic1' ><span class='error-alert'></span></div><div class='col-md-2'><label for='edit_contacttype'>Contact Type</label></div><div class='col-md-4'><select class='form-control' id='edit_contacttype'></select><span class='error-alert'></span></div></div></div>");
} */

/* function add_proDetails(){		
	$('#customerinfoedit .modal-body .edit_custPro').append('<div class="row lead_address"><div class="col-md-12 col-sm-12 col-xs-12"><center><b>Product Purchase Information</b></center></div></div><div class="row"><div class="col-md-2"><label for="edit_product"> Product*</label> </div><div class="col-md-4 edit_product1"  name="edit_product1"><span class="error-alert"></span></div><div class="col-xs-2"><label for="">Value</label></div><div class="col-xs-4"><input class="form-control" id="pro_Value"/><span class="error-alert"></span></div></div><div class="row"><div class="col-xs-2"> <label for="">Number</label> </div><div class="col-xs-4"><input type="" class="form-control" id="edit_Number" name="edit_Number"> </input><span class="error-alert"></span> </div><div class="col-xs-2"><label for="">Opportunity Owner</label></div><div class="col-xs-4"><input class="form-control" id="pro_owner"/><span class="error-alert"></span></div></div><div class="row"><div class="col-xs-2"><label for="">Start Date</label></div><div class="col-xs-4"><input class="form-control" id="edit_start_date" placeholder="DD-MM-YYYY" disabled /><span class="error-alert"></span></div><div class="col-xs-2"><label for="">End Date</label></div><div class="col-xs-4"><input class="form-control" id="edit_end_date" placeholder="DD-MM-YYYY" readonly /></div></div>');
} */
$(document).on('focus',".end_date", function(){
    $(this).datepicker({
		changeMonth: true, 
		changeYear: true, 
		dateFormat: "dd/mm/yy",
		yearRange: "-90:+00"
	});
	
});
function assignedLoad(){
	var reporting=[];	
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('sales_customerController/getAcceptedCustomerDetails');?>",
		dataType:'json',
		success: function(data) {
			console.log(data)
			if(error_handler(data)){
                    return;
                }
			loaderHide();
			console.log(data);
		//	reporting=data['reportingPersons'];
			 customers=data;	
			if(customers.length > 0){
				$('#assign_btn').removeClass('hidden');
			}
			
			$('#tablebody').parent("table").dataTable().fnDestroy();
				var row = "";
				for(i=0; i < customers.length; i++ ){
				 var status="";
				if(customers[i].status == "Active"){
					status = "<b style='color:green'>Active</b>"
				}else{
					status = "<b style='color:red'>Inactive</b>"
				}						
				var rowdata = JSON.stringify(customers[i]);
				console.log(rowdata)
				var pList = "",num=1;
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
				row += "<tr><td>" + (i+1) + "</td><td>" + customers[i].customer_name +"</td><td>" +pList+"</td><td>" + customers[i].customer_city +"</td><td>" + status +"</td><td>" + customers[i].customer_manager_owner +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a href='#' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>"	

			}					
			$('#tablebody').empty();
			$('#tablebody').parent("table").removeClass('hidden');				
			$('#tablebody').append(row);
			$('#tablebody').parent("table").DataTable({
			"aoColumnDefs": [{ "bSortable": false, "aTargets": [6,7] }]
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
		}
	});
}
$(document).ready(function(){
	$('[data-toggle="tooltip"').tooltip();
	assignedLoad();
	$("#end_date").datepicker({
		changeMonth: true, 
		changeYear: true, 
		dateFormat: "dd/mm/yy",
		yearRange: "-90:+00"
	});	
	$('#select_map').hide();
	$("#map2").hide();	
});
/*-----------------customer log------------------------*/
function leadlog(){
	  var customer={};
	  customer.customerId=$("#customer_id").val();
	 $.ajax({
			type: "POST",
			url: "<?php echo site_url('sales_customerController/getCustomerLogDetails');?>",
			data : JSON.stringify(customer),
			dataType:'json',
			success: function(data) {
				$("#logdetails").addClass("active").addClass("in");
				$("#logdetails_cust").addClass("active");
				$('#logdetails').empty();
				if(error_handler(data)){
                    return;
                }
				if(data.length > 0){
					var row = "", tolltip = '';
					var url_path = "<?php echo base_url(); ?>uploads/";
					row += '<div class="row">';
					row += '<table class="table"><thead><tr>';
					row += '<th>SL No</th><th>Activity Owner</th><th>Date-time</th><th>Activity</th><th>Ratings</th><th>Duration</th><th>Remarks</th><th>Status</th><th>Call Recorded</th>';
					row += '</tr></thead><tbody>';
					for(i=0; i < data.length; i++ ){ 
						if (data[i].note.length > 20){
							tolltip = "rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='right'  data-title='"+data[i].note+"'";
							data[i].note = data[i].note.substring(0,20) + ' ...';
						}
						var start=data[i].start;
						var end=data[i].end;
						var cal_duration = moment.duration(moment(end, 'YYYY/MM/DD HH:mm:ss').
						diff(moment(start, 'YYYY/MM/DD HH:mm:ss'))).asMilliseconds("");
						var duration = moment.utc(cal_duration).format("HH:mm:ss");
						var rowdata = JSON.stringify(data[i]);	
						
						var rating = "";
						for(a=1; a< 5; a++){
							if(data[i].rating != ""){
								if(a <= parseInt(data[i].rating)){
									rating += "<i class='fa fa-star' aria-hidden='true'></i>";
								}else{
									rating += "<i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>";
								}
							}else{
								rating += "<i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>";
							}
						}
						row += "<tr>"+
									"<td>" + (i+1) + "</td>"+
									"<td>" + data[i].rep_name +"</td>"+
									"<td>" + data[i].starttime +"</td>"+
									"<td>" + data[i].activity + "</td>"+
									"<td class='ff'>" + rating +"</td>"+
									"<td>" + duration +"</td>"+
									"<td class='tt'><p "+ tolltip+">" + data[i].note +"</p></td>"+
									"<td>" + data[i].status +"</td>";
								if(data[i].conntype == 'CALL594ce66d07b45' || data[i].conntype == 'CALL594ce66d07b46' || data[i].conntype == 'ME594ce66d07b9fd4'){
									if(data[i].hasOwnProperty('path')){
										if(data[i].path == null){
											row +=	"<td>No audio and sms attached</td>";
										}else{
											row +=	"<td><audio controls controlsList='nodownload'><source src="+url_path+data[i].path+">"+
											"Your browser does not support the audio element."+
											"</audio></td>";
										}
										
									}else{
										row +=	"<td>No audio and sms attached</td>";
									}
								}else{
									if(data[i].hasOwnProperty('path')){
										if(data[i].path == null){
											row +=	"<td>No audio and sms attached</td>";
										}else{
											row +=	"<td>"+data[i].path+"</td>";
										}										
									}else{
										row +=	"<td>No audio and sms attached</td>";
									}
								}						
								row += "</tr>";
					}     
					row +='</tbody>';   						
					row +='</table>';   
					row +='</div>';   
					$('#logdetails').append(row); 
				}else{
					$('#logdetails').empty().append('<center><h4>No Customer History Found</h4></center>');
				}			
			}
		});
	 
	}
	/*----------------------------------------------------------customer history----------------------------------------------------------------*/
function hist_log(){
	  var customer={};
	  customer.customer_id=$("#customer_id").val();
	 $.ajax({
			type: "POST",
			url: "<?php echo site_url('sales_customerController/oppDetailsCustomer');?>",
			data : JSON.stringify(customer),
			dataType:'json',
			success: function(data) {
				$('#history').empty();
				if(error_handler(data)){
                    return;
                }
				if(data.length > 0){
					var tolltip = "";
					var row = "";
					row += '<div class="row">';
					row += '<table class="table"><thead><tr>';
					row += '<th>SL No</th><th>Customer Name</th><th>Date-time</th><th>Activity</th><th>Ratings</th><th>Duration</th><th>Remarks</th><th>Call Recorded</th>';
					row += '</tr></thead><tbody>';
					for(i=0; i < data.length; i++ ){ 
						if (data[i].note.length > 20){
							tolltip = "rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='right'  data-title='"+data[i].note+"'";
							data[i].note = data[i].note.substring(0,20) + ' ...';
						}
						var start=data[i].starttime;
						var end=data[i].endtime;
						var cal_duration = moment.duration(moment(end, 'YYYY/MM/DD HH:mm:ss').
						diff(moment(start, 'YYYY/MM/DD HH:mm:ss'))).asMilliseconds("");
						var duration = moment.utc(cal_duration).format("HH:mm:ss");
						var rowdata = JSON.stringify(data[i]);	
						var url_path = "<?php echo base_url(); ?>uploads/";
						var rating = "";
						for(a=1; a< 5; a++){
							if(data[i].rating != ""){
								if(a <= parseInt(data[i].rating)){
									rating += "<i class='fa fa-star' aria-hidden='true'></i>";
								}else{
									rating += "<i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>";
								}
							}else{
								rating += "<i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>";
							}
						}
						row += "<tr>"+
								"<td>" + (i+1) + "</td>"+
								"<td>" + data[i].customer_name +"</td>"+
								"<td>" + data[i].starttime +"</td>"+
								"<td>" + data[i].activity + "</td>"+
								"<td class='ff'>" + rating +"</td>"+
								"<td>" + duration +"</td>"+
								"<td class='tt'><span "+ tolltip +">" + data[i].note +"</span></td>";
								if(data[i].conntype == 'CALL594ce66d07b45' || data[i].conntype == 'CALL594ce66d07b46' || data[i].conntype == 'ME594ce66d07b9fd4'){
									if(data[i].hasOwnProperty('path')){
										if(data[i].path == null){
											row +=	"<td>No audio and sms attached</td>";
										}else{
											row +=	"<td><audio controls controlsList='nodownload'><source src="+url_path+data[i].path+">"+
											"Your browser does not support the audio element."+
											"</audio></td>";
										}
										
									}else{
										row +=	"<td>No audio and sms attached</td>";
									}
								}else{
									if(data[i].hasOwnProperty('path')){
										if(data[i].path == null){
											row +=	"<td>No audio and sms attached</td>";
										}else{
											row +=	"<td>"+data[i].path+"</td>";
										}										
									}else{
										row +=	"<td>No audio and sms attached</td>";
									}
								}
								row += "</tr>";
					}     
					row +='</tbody>';   						
					row +='</table>';   
					row +='</div>';   
					$('#history').append(row); 
				}else{
					$('#history').empty().append('<center><h4>No Customer History Found</h4></center>');
				}			
			}
		});
	 
	}
/*----------------------------------------------------------Customer History Ends------------------------------------------------------------*/
	/*------------------------------Scheduled log---------------------------------------*/
	function schedule_fetch(){
		var customer={};
		customer.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	  	customer.customerId=$("#customer_id").val();
	  	
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('sales_customerController/getScheduleTask'); ?>",
			data : JSON.stringify(customer),
			dataType:'json',
			success: function(data) {
				$('#logdetails1').empty();
				if(error_handler(data)){
                    	return;
                }
				if(data.length > 0){
					var row = "";
					row += '<div class="row">';
					row += '<div class="col-md-12 lead_view">';
					row += '<div class="col-md-12 col-sm-12 col-xs-12">';
					row += '<center><b>Scheduled Log</b></center>';
					row += '</div>';
					row += '</div>';
					row += '<table class="table"><thead><tr>';
					row += '<th>#</th><th>Activity Owner</th><th>Start-Date</th><th>Duration</th><th>Activity</th><th>Asset</th><th>Remarks</th>';
					row += '</tr></thead><tbody>';
					for(i=0; i < data.length; i++ ){  
						var rowdata = JSON.stringify(data[i]);	
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].activity_owner +"</td><td>" + data[i].start_date +"</td><td>" + data[i].duration + "</td><td>" + data[i].activity +"</td><td>" + data[i].status +"</td><td style='width: 27%'>"+
							data[i].remarks+"</td></tr>";
					} 
					row +='</tbody>';   						
					row +='</table>';   
					row +='</div>';   
					$('#logdetails1').append(row); 
				}else{
					$('#logdetails1').empty().append('<center><h4>No Activities Scheduled</h4></center>');
				}
			}
		});	
	}
	/*------------------------------Opportunity log-------------------------------------*/
	function opp_log(){
		var customer={};
		customer.customerId=$("#customer_id").val();
			 $.ajax({
			type: "POST",
			url: "<?php echo site_url('sales_customerController/getCustomerOppDetails');?>",
			data : JSON.stringify(customer),
			dataType:'json',
			success: function(data) {
				$('#opp_details1').empty();
				if(error_handler(data)){
                    return;
                }
				if(data.length > 0){
					var row = "";
					row += '<div class="row">';
					row += '<table class="table"><thead><tr>';
					row += '<th>SL No</th><th>Name</th><th>Product</th><th>Sales Stage</th>	<th>Expected Close Date</th><th>Stage Owner</th>';
					row += '</tr></thead><tbody>';
					for(i=0; i < data.length; i++ ){						
						var rowdata = JSON.stringify(data[i]);
						var url = "<?php echo site_url("sales_opportunitiesController/stage_view/")?>"+data[i].opportunity_id;						 
						row += "<tr><td>" + (i+1) + "</td><td><a href='"+url+"'>" + data[i].opportunity_name +"</a></td><td>" + data[i].opportunity_product +"</td><td>" + data[i].stage_name+ "</td><td>"+data[i].opportunity_date +"</td><td>"+data[i].stage_owner +"</td></tr>";			
					}
					row +='</tbody>';   						
					row +='</table>';   
					row +='</div>';					
					$('#opp_details1').empty().append(row);
				}else{
					$('#opp_details1').append('<center><h4>No Opportunity Created</h4></center>');
				}
			}
		});
	}
/* =========================================================== */
function pro(){
			var ownerId='';
			$("#selcd_pro").empty();
			$("#product_Currency").empty();
			$("#product_value1").empty();
			var addObj={};
			addObj.ownerId=$("#pro_owner").val();
			ownerId=$("#pro_owner").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('sales_customerController/getProductData'); ?>",
				data : JSON.stringify(addObj),
				dataType:'json',
				success: function(data) {
					cust_data = data;
						if(error_handler(data)){
						return;
					}
					if(data==""){
						$(".fetch_btn").hide();
						$("#add_product").html("");
					}else{
						$("#add_product").html("");
						$("#add_product").append(currencyhtml);
						var currencyhtml="";
						currencyhtml +='<div id="product_value1" class="multiselect">';
						currencyhtml +='<ul>';
						for(var j=0;j<data.length; j++){
								currencyhtml +='<li><label><input type="checkbox" value="'+data[j].product_id+'"><span id="name_val">  '+data[j].product_name+'</span><label></li>';
						}
						currencyhtml +='</ul>';
						currencyhtml +='</div>';
						$("#add_product").append(currencyhtml);
						$("#add_product").show();
						$(".fetch_btn").show();
					}
				}
			});
		}
	function purchaseInfoFunction(){
		$('#modalpurchase').modal('show');
 		var ownerId='';
 		$.ajax({
            type: "POST",
            url: "<?php echo site_url('sales_customerController/getManagerlist'); ?>",
            dataType:'json',
            success: function(data){
            	if(error_handler(data)){
                    return;
                }
				var select = $("#pro_owner"), options = "<option value=''>select</option>";
				select.empty();      
				for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].user_id+"'>"+ data[i].user_name +"</option>";              
				}
				select.append(options);
                 
            }
        });

		/* 
 		$('#product_Currency').on('change',function(){
			var addObj={};
			addObj.user_id=$('#pro_owner').val();
			addObj.currency_id=this.value;
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('sales_customerController/getProductData'); ?>",
				data : JSON.stringify(addObj),
				dataType:'json',
				success: function(data) {
					if(error_handler(data)){
						return;
					}
					$("#add_product").html("");
					$("#add_product").append(currencyhtml);
					var currencyhtml="";
					currencyhtml +='<div id="product_value1" class="multiselect">';
					currencyhtml +='<ul>';
					for(var j=0;j<data.length; j++){								
							currencyhtml +='<li><label><input type="checkbox" value="'+data[j].product_id+'">  '+data[j].product_name+'<label></li>';
					}
					currencyhtml +='</ul>';
					currencyhtml +='</div>';
					$("#add_product").append(currencyhtml);
				}
			});
		}); */

	}
	/* =========================================================== */

function fetch_btn() {
	
		$(".error-alert").empty();
 		var in_value=[];
 		var addObj={};
 		$("#product_value1 ul li input[type=checkbox]").each(function(){
 			if($(this).is(":checked")){
 				in_value.push($(this).val());
 			}
 		});
		if(in_value.length == 0){
			$("#product_err").find("span").text("Product is required.");
			$("#product_err").focus();				
			return;
		}
		$("#selcd_pro").empty();
		for(i=0;i<in_value.length;i++){

			if(cust_data[i].product_id==in_value[i]){
				var row='';
				row +='<div class="'+cust_data[i].product_id+' product_field"><div class="row product_heading">'+cust_data[i].product_name+'</div><div class="row"><div class="col-md-2"><label for="">Quantity*</label></div><div class="col-md-4"><input class="form-control product_Number" name="product_Number"/><span class="error-alert"></span></div><div class="col-md-2"><label for="">Cost*</label></div><div class="col-md-4"><input type="" class="form-control pro_Value1" value="" disabled ><input type="" class="form-control pro_Value" ><span class="error-alert"></span></div></div><div class="row"><div class="col-md-2"><label for="">Start Date*</label></div><div class="col-md-4"><input type="text" id="actve_duration" class="form-control product_start_date" placeholder="DD-MM-YYYY"  maxlength="5" >	<span class="error-alert"></span></div><div class="col-md-2"><label for="">End Date</label></div><div class="col-md-4"><input class="form-control product_end_date" placeholder="DD-MM-YYYY" /><span class="error-alert"></span></div></div></div>';				
			}
			$("#selcd_pro").append(row);
		}
		$(".product_end_date").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'lll',
			minDate: new Date()
		});
		$(".product_start_date").each(function(){
			$(this).datetimepicker({
				ignoreReadonly:true,
				allowInputToggle:true,
				format:'lll',
			});
			$(this).on("dp.change", function (selected) {
				var startDateTime = moment($.trim($(this).val()), 'lll');
				$(this).closest(".row").find(".product_end_date").data("DateTimePicker").minDate(startDateTime);
				
			})
		})
		
 		addObj.productArray=in_value; 
 		addObj.ownerId=$('#pro_owner').val();		
 		$.ajax({
			type: "POST",
			url: "<?php echo site_url('sales_customerController/postProduct'); ?>",
			data : JSON.stringify(addObj),
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
                    return;
                }
			 var select = $("#product_Currency"), options = "<option value=''>select</option>";
			   select.empty();      

			   for(var i=0;i<data.length; i++)
			   {
					options += "<option value='"+data[i].currency_id+"'>"+ data[i].currency_name +"</option>";              
			   }
			   select.append(options);
			}
		});
 	
	
}
/* function edit_rendergmap() {
	var mapOptions = {
		center: new google.maps.LatLng(12.93325692, 77.57465679),
		zoom: 12,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var infoWindow = new google.maps.InfoWindow();
	var latlngbounds = new google.maps.LatLngBounds();
	var map = new google.maps.Map(document.getElementById("edit_mapname"), mapOptions);
	
	var input = document.getElementById('edit_search');
	var searchBox = new google.maps.places.SearchBox(input);
	var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.setTypes(['geocode']);
		
	map.addListener('bounds_changed', function() {
	  searchBox.setBounds(map.getBounds());
	});

	var markers = [];
	
	searchBox.addListener('places_changed', function() {
	  var places = searchBox.getPlaces();

	  if (places.length == 0) {
		return;
	  }

	  markers.forEach(function(marker) {
		marker.setMap(null);
	  });
	  markers = [];

	  var bounds = new google.maps.LatLngBounds();
	  places.forEach(function(place) {
		if (!place.geometry) {
		  alert("Returned place contains no geometry");
		  return;
		}
		var icon = {
		  url: place.icon,
		  size: new google.maps.Size(71, 71),
		  origin: new google.maps.Point(0, 0),
		  anchor: new google.maps.Point(17, 34),
		  scaledSize: new google.maps.Size(25, 25)
		};

		markers.push(new google.maps.Marker({
		  map: map,
		  icon: icon,
		  title: place.name,
		  position: place.geometry.location
		}));

		if (place.geometry.viewport) {
		  bounds.union(place.geometry.viewport);
		} else {
		  bounds.extend(place.geometry.location);
		}
	  });
	  map.fitBounds(bounds);
	   var place = autocomplete.getPlace();
		if (!place.geometry) {
			return;
		}

		var address = '';
		if (place.address_components) {
			address = [
				(place.address_components[0] && place.address_components[0].short_name || ''),
				(place.address_components[1] && place.address_components[1].short_name || ''),
				(place.address_components[2] && place.address_components[2].short_name || '')
				].join(' ');
		}
	});
	
	google.maps.event.addListener(map, 'click', function(e){
		
		var latlngstr = "lat=" + e.latLng.lat() + "&lng=" + e.latLng.lng();
		document.getElementById("edit_long").value = e.latLng.lat();
		document.getElementById("edit_latt").value = e.latLng.lng();
	});
} */
function add_lead(){
	$.ajax({
		type: "POST",
		url: "",
		dataType:'json',
		success: function(data) {
			if(error_handler(data)){
                    return;
                }
		 var select = $("#product"), options = "<option value=''>select</option>";
		   select.empty();      
			for(var i=0;i<data.length; i++)
		   {
				options += "<option value='"+data[i].product_id+"'>"+ data[i].product_name +"</option>";              
		   }
		   select.append(options);
		}
	});
	$.ajax({
		type: "POST",
		url: "",
		dataType:'json',
		success: function(data) {
			if(error_handler(data)){
                    return;
                }
		 var select = $("#product"), options = "<option value=''>select</option>";
		   select.empty();      
			for(var i=0;i<data.length; i++)
		   {
				options += "<option value='"+data[i].product_id+"'>"+ data[i].product_name +"</option>";              
		   }
		   select.append(options);
		}
	});
	$.ajax({
		type: "POST",
		url: "",
		dataType:'json',
		success: function(data) {
			if(error_handler(data)){
                    return;
                }
		 var select = $("#contacttype"), options = "<option value=''>select</option>";
		   select.empty();      
			for(var i=0;i<data.length; i++)
		   {
				options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
		   }
		   select.append(options);
		}
	});
	$.ajax({
		type: "POST",
		url: "",
		dataType:'json',
		success: function(data) {
			if(error_handler(data)){
                    return;
                }
		 var select = $("#country"), options = "<option value=''>select</option>";
		   select.empty();      
			for(var i=0;i<data.length; i++)
		   {
				options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
		   }
		   select.append(options);
		}
	});
	$('#country').on('change',function(){
	   var id= this.value; 
		$.ajax({
			type: "POST",
			url: "",
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
	});
 }
/* function rendergmap() {
	var mapOptions = {
		center: new google.maps.LatLng(12.93325692, 77.57465679),
		zoom: 12,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var infoWindow = new google.maps.InfoWindow();
	var latlngbounds = new google.maps.LatLngBounds();
	var map = new google.maps.Map(document.getElementById("mapname"), mapOptions);
	
	var input = document.getElementById('search');
	var searchBox = new google.maps.places.SearchBox(input);
	var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.setTypes(['geocode']);
		
	map.addListener('bounds_changed', function() {
	  searchBox.setBounds(map.getBounds());
	});

	var markers = [];
	
	searchBox.addListener('places_changed', function() {
	  var places = searchBox.getPlaces();

	  if (places.length == 0) {
		return;
	  }

	  markers.forEach(function(marker) {
		marker.setMap(null);
	  });
	  markers = [];

	  var bounds = new google.maps.LatLngBounds();
	  places.forEach(function(place) {
		if (!place.geometry) {
		  alert("Returned place contains no geometry");
		  return;
		}
		var icon = {
		  url: place.icon,
		  size: new google.maps.Size(71, 71),
		  origin: new google.maps.Point(0, 0),
		  anchor: new google.maps.Point(17, 34),
		  scaledSize: new google.maps.Size(25, 25)
		};

		markers.push(new google.maps.Marker({
		  map: map,
		  icon: icon,
		  title: place.name,
		  position: place.geometry.location
		}));

		if (place.geometry.viewport) {
		  bounds.union(place.geometry.viewport);
		}else {
		  bounds.extend(place.geometry.location);
		}
	  });
	  map.fitBounds(bounds);
	   var place = autocomplete.getPlace();
		if (!place.geometry) {
			return;
		}

		var address = '';
		if (place.address_components) {
			address = [
			(place.address_components[0] && place.address_components[0].short_name || ''),
			(place.address_components[1] && place.address_components[1].short_name || ''),
			(place.address_components[2] && place.address_components[2].short_name || '')
			].join(' ');
		}
	});	
	google.maps.event.addListener(map, 'click', function(e){		
		var latlngstr = "lat=" + e.latLng.lat() + "&lng=" + e.latLng.lng();
		document.getElementById("long").value = e.latLng.lat();
		document.getElementById("latt").value = e.latLng.lng();
    });
} */
function cancelCust(){
	$('.modal').modal('hide');
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
	$(".error-alert").empty("");
	$('.modal select.form-control').val();
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
}
/* function codeAddress() {
    geocoder = new google.maps.Geocoder();
    var address = document.getElementById("search").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			document.getElementById("long").value = results[0].geometry.location.lat();
			document.getElementById("latt").value = results[0].geometry.location.lng();
			map_marker();
		}else{
			alert("Geocode was not successful for the following reason: " + status);
		}
    });
}
function map_marker(){
	var lat=document.getElementById("long").value;
	var log=document.getElementById("latt").value;
	var myCenter=new google.maps.LatLng(lat,log);
	var mapProp = {
	  center:myCenter,
	  zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	};
	var map=new google.maps.Map(document.getElementById("mapname"),mapProp);
	var marker=new google.maps.Marker({
	  position:myCenter,
	});
	marker.setMap(map);
}
function map_marker1(){
	var lat=document.getElementById("edit_long").value;
	var log=document.getElementById("edit_latt").value;
	var myCenter=new google.maps.LatLng(lat,log);
	var mapProp = {
	  center:myCenter,
	  zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	};
	var map=new google.maps.Map(document.getElementById("edit_mapname"),mapProp);
	var marker=new google.maps.Marker({
	  position:myCenter,
	});
	marker.setMap(map);
}
function show_map(){
	$("#map2").show();
	$("#map1").show();
	$("#select_map").hide();

	var lat=document.getElementById("long").value;
	var log=document.getElementById("latt").value;

	var myCenter=new google.maps.LatLng(lat,log);
	var mapProp = {
	  center:myCenter,
	  zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	  };

	var map=new google.maps.Map(document.getElementById("maploc"),mapProp);

	var marker=new google.maps.Marker({
	  position:myCenter,
	  });
	marker.setMap(map);
}
function edit_showmap(){
$("#edit_map2").show();
$("#edit_map1").show();
$("#edit_selectmap").hide();

var lat=document.getElementById("edit_long").value;
var log=document.getElementById("edit_latt").value;

var myCenter=new google.maps.LatLng(lat,log);
var mapProp = {
  center:myCenter,
  zoom:14,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("edit_maploc"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,
  });

marker.setMap(map);
}
function editadd(){
var lat=document.getElementById("edit_long").value;
var log=document.getElementById("edit_latt").value;

var myCenter=new google.maps.LatLng(lat,log);
var mapProp = {
  center:myCenter,
  zoom:14,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("edit_maploc"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,
  });

marker.setMap(map);
} */
/* function save_leadinfo(){
	if($.trim($("#leadname").val())==""){
		$("#leadname").closest("div").find("span").text("Lead name is required.");
		$("#leadname").focus();
		return;
    }else if(!validate_name($.trim($("#leadname").val()))){
		$("#leadname").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#leadname").closest("div").find("span").text("");
    }  
	if($.trim($("#leadweb").val())==""){
		$("#leadweb").closest("div").find("span").text("Lead name is required.");
		$("#leadweb").focus();
		return;
    }else if(!validate_website($.trim($("#leadweb").val()))){
		$("#leadweb").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#leadweb").closest("div").find("span").text("");
    }   
	if($.trim($("#leadmail").val())==""){
		$("#leadmail").closest("div").find("span").text("Email is required.");
		$("#leadmail").focus();
		return;
    }else if(!validate_email($.trim($("#leadmail").val()))){
		$("#leadmail").closest("div").find("span").text("Enter Only Chracters");
    } else{
		$("#leadmail").closest("div").find("span").text("");
    } 
	if($.trim($("#leadphone").val())==""){
		$("#leadphone").closest("div").find("span").text("Phone is required.");
		$("#leadphone").focus();
		return;
    }else if(!validate_phone($.trim($("#leadphone").val()))){
		$("#leadphone").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#leadphone").closest("div").find("span").text("");
    } 
	if($.trim($("#city").val())==""){
		$("#city").closest("div").find("span").text("City is required.");
		$("#city").focus();
		return;
    }else if(!validate_city($.trim($("#city").val()))){
		$("#city").closest("div").find("span").text("Enter Only Chracters");
    } else{
		$("#city").closest("div").find("span").text("");
    } 
    if($.trim($("#zipcode").val())==""){
		$("#zipcode").closest("div").find("span").text("Zipcode is required.");
		$("#zipcode").focus();
		return;
    }else if(!validate_zipcode($.trim($("#zipcode").val()))){
		$("#zipcode").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#zipcode").closest("div").find("span").text("");
    }           
    if($.trim($("#firstcontact").val())==""){
		$("#firstcontact").closest("div").find("span").text("Contact Name is required.");
		$("#firstcontact").focus();
		return;
    }else if(!validate_contact($.trim($("#firstcontact").val()))){
		$("#firstcontact").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#firstcontact").closest("div").find("span").text("");
    }             
    if($.trim($("#disgnation").val())==""){
		$("#disgnation").closest("div").find("span").text("Designation is required.");
		$("#disgnation").focus();
		return;
    }else if(!validate_designation($.trim($("#disgnation").val()))){
		$("#disgnation").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#disgnation").closest("div").find("span").text("");
    } 
	if($.trim($("#primmobile").val())==""){
		$("#primmobile").closest("div").find("span").text("Mobile Nummber is required.");
		$("#primmobile").focus();
		return;
    }else if(!validate_phone($("#primmobile").val())){
		$("#primmobile").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#primmobile").closest("div").find("span").text("");
    }  
    var addObj={};
    addObj.leadname = $.trim($("#leadname").val());
    addObj.leadwebsite = $.trim($("#leadweb").val());
    addObj.leademail = $.trim($("#leadmail").val());
    addObj.phone = $.trim($("#leadphone").val());
    addObj.product = $.trim($("#product").val());
    addObj.source = $.trim($("#leadsource").val());
    addObj.country = $.trim($("#country").val());
    addObj.state = $.trim($("#state").val());
    addObj.city = $.trim($("#city").val());
    addObj.zipcode = $.trim($("#zipcode").val());
    addObj.ofcaddress = $.trim($("#ofcadd").val());
    addObj.splcomments = $.trim($("#splcomments").val());
    addObj.contactname = $.trim($("#firstcontact").val());
    addObj.designation = $.trim($("#disgnation").val());
    addObj.mobile1 = $.trim($("#primmobile").val());
    addObj.mobile2 = $.trim($("#primmobile2").val());
    addObj.email1 = $.trim($("#primemail").val());
    addObj.email2 = $.trim($("#primemail2").val());
    addObj.contacttype = $.trim($("#contacttype").val());
    addObj.longitude = $.trim($("#long").val());
    addObj.lattitude = $.trim($("#latt").val());
    
    $.ajax({
        type : "POST",
        url : "lead_source.json",
        dataType : 'json',
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){
            $('.modal').modal('hide');
            $('.form-control').val("");
            $('#tablebody').empty();
            var row = "";
            for(i=0; i < data.length; i++ ){						
            var rowdata = JSON.stringify(data[i]);
           
             row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].employeename +"</td><td>" + data[i].employeedesg+ "</td><td>" + data[i].leadphone +"</td><td>" + data[i].leademail +"</td><td>" + data[i].city +"</td><td>" + data[i].leadsource +"</td><td><a data-toggle='modal' href='#leadview' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
            }					
              $('#tablebody').append(row);
        
}
});	


} */
function editAddress() {
    geocoder = new google.maps.Geocoder();
    var address = document.getElementById("edit_search").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                    document.getElementById("edit_long").value = results[0].geometry.location.lat();
                    document.getElementById("edit_latt").value = results[0].geometry.location.lng();
                    map_marker1();
            }else {
                    alert("Geocode was not successful for the following reason: " + status);
            }
    });
   }
function selrow(obj){
var obj1 = {};
$('.custom-file-upload').find('i').remove();
if(obj.customer_logo !="" && obj.customer_logo !=null){
	$("#leadAvrtEdit").attr("src", "<?php echo site_url()?>/uploads/"+obj.customer_logo);
}else{
	$("#leadAvrtEdit").attr("src", "<?php echo site_url()?>/uploads/default-pic.jpg");
}
obj1.customerid=obj.customer_id;
$("#customerinfoedit").modal('show');
$("#edit_customer").html(obj.customer_name);
$("#edit_leadname").val(obj.customer_name);
$("#edit_leadweb").val(obj.customer_website);
$("#edit_leadmail").val(obj.customer_email);
$("#edit_leadphone").val(obj.customer_number);
$("#edit_city").val(obj.city);
$("#edit_zipcode").val(obj.customer_zip);
$("#edit_ofcadd").val(obj.customer_address);
$("#edit_splcomments").val(obj.customer_remarks);
$("#edit_disgnation").val(obj.contact_desg);
$("#edit_primmobile").val(obj.mobile_number1);
$("#edit_primmobile2").val(obj.mobile_number2);
$("#edit_primemail").val(obj.contact_email1);
$("#edit_primemai2").val(obj.contact_email2);
$("#edit_firstcontact").val(obj.contact_name);
$("#edit_contacttype").val(obj.contact_type);
$("#leadid").val(obj.customer_id);
$("#employeeid").val(obj.contact_id);
$("#edit_long").val(obj.leadlng);
$("#edit_latt").val(obj.leadlat);
$("#pro_Value").val(obj.pro_Value);
$("#edit_Number").val(obj.edit_Number);
$('#edit_selectmap').hide();
$("#edit_country").val(obj.customer_country);
$("#edit_state").val(obj.customer_state);
$('#edit_map1').show();
$('#edit_address').val(obj.contact_address);
$('#edit_start_date').val(obj.purchase_start_date);
$('#edit_end_date').val(obj.purchase_end_date);
$('#edit_industry').val(obj.customer_industry);
$('#edit_business_location').val(obj.customer_business_loc);
$.ajax({
	type: "POST",
	url: "<?php echo site_url('sales_customerController/customFieldCustomer');?>",
	data:JSON.stringify(obj1),
	dataType:'json',
	success: function(data) {
		$("#custom_fields").empty();
		var rowdata=data.customerCustom, row='';
		var rowdata1=data.leadCustom, row1='';
		console.log(data)	
		if(error_handler(data)){
			return;
		}
		if(rowdata.length>0){
			var j = 0;
			$("#custom_head").show();
			for(i=0;i<rowdata.length;i++){
				$("#custom_customer_id").val(rowdata[i].id);
				if(i==j){
					row += '<div class="row">';
					j = j + 2;	
					if(rowdata[i].attribute_type=="Single_Line_Text"){
						row += "<div class='col-md-2'><label>"+rowdata[i].attribute_name+"</label></div><div class='col-md-4'><input type='text' class='form-control custom_fld' id='"+rowdata[i].attribute_key+"' value='"+rowdata[i].attribute_value+"' /></div>";
					}
				}else{
					if(rowdata[i].attribute_type=="Single_Line_Text"){
						row += "<div class='col-md-2'><label>"+rowdata[i].attribute_name+"</label></div><div class='col-md-4'><input type='text' class='form-control custom_fld' id='"+rowdata[i].attribute_key+"' value='"+rowdata[i].attribute_value+"' /></div>";
					}
				}
				if(i == (j - 1)){
					row += '</div>';
				}
			}
			$("#custom_fields").append(row);
		}
		if(rowdata1.length>0){
			var h = 0;
			$("#custom_head").show();
			for(i=0;i<rowdata1.length;i++){
				$("#custom_lead_id").val(rowdata1[i].id);
				if(i==j){
					row1 += '<div class="row">';
					j = j + 2;	
					if(rowdata1[i].attribute_type=="Single_Line_Text"){
						row1 += "<div class='col-md-2'><label>"+rowdata1[i].attribute_name+"</label></div><div class='col-md-4'><input type='text' class='form-control custom_fld' id='"+rowdata1[i].attribute_key+"' value='"+rowdata1[i].attribute_value+"' /></div>";
					}
				}else{
					if(rowdata1[i].attribute_type=="Single_Line_Text"){
						row1 += "<div class='col-md-2'><label>"+rowdata1[i].attribute_name+"</label></div><div class='col-md-4'><input type='text' class='form-control custom_fld' id='"+rowdata1[i].attribute_key+"' value='"+rowdata1[i].attribute_value+"' /></div>";
					}
				}
				if(i == (j - 1)){
					row1 += '</div>';
				}
			}
			$("#custom_fields").append(row1);
		}
	}
});
$.ajax({
	type: "POST",
	url: "<?php echo site_url('sales_customerController/get_plugin_data');?>",
	dataType:'json',
	success: function(data) {
		console.log(data)
		if(error_handler(data)){
			return;
		}
		if(obj.hasOwnProperty("coordinate")== false){
			associated_plugin_used(data[0].plugin_id , "edit" , ",")
		}else(
			associated_plugin_used(data[0].plugin_id , "edit" , obj.coordinate)
		)
		
	}
});	
$.ajax({
        type: "POST",
        url: "<?php echo site_url('sales_customerController/getIndustry')?>",
        dataType:'json',
        success: function(data) {
        	if(error_handler(data)){
                    return;
                }
        var select = $("#edit_industry"), options = "<option value=''>select</option>";
           select.empty();      
           for(var i=0;i<data.length; i++)
           {
                options += "<option value='"+data[i].industry_id+"'>"+ data[i].industry_name +"</option>"; 

           }
           select.append(options);
           $("#edit_industry option[value='"+obj.customer_industry+"']").prop("selected",true);
        }
        });

$.ajax({
        type: "POST",
        url: "<?php echo site_url('sales_customerController/getLocation')?>",
        dataType:'json',
        success: function(data) {
        	if(error_handler(data)){
                    return;
                }
        var select = $("#edit_business_location"), options = "<option value=''>select</option>";
           select.empty();      
           for(var i=0;i<data.length; i++)
           {
                options += "<option value='"+data[i].business_location_id+"'>"+ data[i].business_location_name +"</option>"; 

           }
           select.append(options);
           $("#edit_business_location option[value='"+obj.customer_business_loc+"']").prop("selected",true);
        }
        });


 $.ajax({
        type: "POST",
        url: "<?php echo site_url('sales_customerController/getCountry')?>",
        dataType:'json',
        success: function(data) {
        	if(error_handler(data)){
                    return;
                }
        var select = $("#edit_country"), options = "<option value=''>select</option>";
           select.empty();      
           for(var i=0;i<data.length; i++)
           {
                options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>"; 

           }
           select.append(options);
           $("#edit_country option[value='"+ obj.customer_country +"']").attr("selected",true);

        }
        });
        var id= obj.customer_country;
                $.ajax({ 
                type : "POST",
                url : "<?php echo site_url('sales_customerController/getState')?>",
                data : "id="+id,
                dataType : 'json',
                cache : false,
                success : function(data){
                	if(error_handler(data)){
                    return;
                }
                    var select = $("#edit_state"), options = "<option value=''>Select</option>";
                        select.empty();      
                        for(var i=0;i<data.length; i++)
                        {
                             options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
                        }
                        select.append(options);
                        $("#edit_state option[value='"+obj.customer_state +"']").attr("selected",true);

                 }
            });
            $('#edit_country').on('change',function(){

               var id= this.value; 
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('sales_customerController/getState')?>",
                    data : "id="+id,
                    dataType:'json',
                    success: function(data) {
                    	if(error_handler(data)){
                    		return;
                		}
                     var select = $("#edit_state"), options = "<option value=''>select</option>";
                       select.empty();      

                       for(var i=0;i<data.length; i++)
                       {
                            options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
                       }
                       select.append(options);

                    }
	        });
            });
 
      
         $.ajax({
            type: "POST",
            url: "<?php echo site_url('sales_customerController/getContactType')?>",
            dataType:'json',
            success: function(data) {
            	if(error_handler(data)){
                    return;
                }
             var select = $("#edit_contacttype"), options = "<option value=''>select</option>";
               select.empty();      
                for(var i=0;i<data.length; i++)
               {
                    options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
               }
               select.append(options);
               $("#edit_contacttype option[value='"+obj.contact_type+"']").attr("selected",true);
            }
        });

          $.ajax({
            type: "POST",
            url: "<?php echo site_url('sales_customerController/getProductData'); ?>",
            dataType:'json',
            success: function(data){
            	if(error_handler(data)){
                    return;
                }
                 $("#edit_product1").html("");
            var currencyhtml1="";
            currencyhtml1 +='<div id="" class="multiselect product_value2">';
            currencyhtml1 +='<ul>';
                    for(var j=0;j<data.length; j++){								
                            currencyhtml1 +='<li><label><input type="checkbox" value="'+data[j].product_id+'">  '+data[j].product_name+'<label></li>';
                    }
                    currencyhtml1 +='</ul>';
                    currencyhtml1 +='</div>';
                    $("#edit_product1").append(currencyhtml1);
            }
        });

		/* $.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_customerController/getLeadSource'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
                    return;
                }
			var isInside = false;
			$("#tree_lead1").click(function () {
			$("#tree_leadsource1").show();
			});

			$("#tree_leadsource1").hover(function () {
			isInside = true;
			}, function () {
			isInside = false;
			})

		$(document).mouseup(function () {
		if (!isInside)
			$("#tree_leadsource1").hide();
		});
		}
		}); */
 
}
function edit_save(){
  	var leadsource1=[];
	var mobile_val = $("#edit_primmobile2").val();
	var mobile_val1 = $("#edit_primmobile").val();
	var email_val = $("#edit_primemail").val();
	var email_val1 = $("#edit_primemai2").val();  
	if($.trim($("#edit_leadname").val())==""){
				$("#edit_leadname").closest("div").find("span").text("Lead name is required.");
				$("#edit_leadname").focus();
				return;
		}else if(!validate_name($.trim($("#edit_leadname").val()))){
				$("#edit_leadname").closest("div").find("span").text("No special characters allowed (except &, _,-,.)");
				$("#edit_leadname").focus();
				return;
		}else if(!firstLetter($.trim($("#edit_leadname").val()))){
				$("#edit_leadname").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#edit_leadname").focus();
				return;
		}else{
				$("#edit_leadname").closest("div").find("span").text("");
		}
		
		if($.trim($("#edit_leadweb").val())!=""){
			if(!validate_website($.trim($("#edit_leadweb").val()))){
					$("#edit_leadweb").closest("div").find("span").text("Invalid website address.");
					$("#edit_leadweb").focus();
					return;
			}else{
					$("#edit_leadweb").closest("div").find("span").text("");
			}  
		}
		
		if($.trim($("#edit_leadmail").val())!=""){
			if(!validate_email($.trim($("#edit_leadmail").val()))){
					$("#edit_leadmail").closest("div").find("span").text("Invalid email address.");
					$("#edit_leadmail").focus();
					return;
			}else{
					$("#edit_leadmail").closest("div").find("span").text("");
			}  
		}
		
		if($.trim($("#edit_leadphone").val())!=""){
			if(!validate_PhNo($.trim($("#edit_leadphone").val()))){
					$("#edit_leadphone").closest("div").find("span").text("Enter 10 digit mobile number.");
					$("#edit_leadphone").focus();
					return;
			}else{
					$("#edit_leadphone").closest("div").find("span").text("");
			}  
		}
		
		if($.trim($("#edit_city").val())!=""){
			if(!validate_location($.trim($("#edit_city").val()))){
					$("#edit_city").closest("div").find("span").text("No special characters allowed (except &)");
					$("#edit_city").focus();
					return;
			}else{
					$("#edit_city").closest("div").find("span").text("");
			}  
		}
		
		if($.trim($("#edit_zipcode").val())!=""){
			if(!validate_zip($.trim($("#edit_zipcode").val()))){
				$("#edit_zipcode").closest("div").find("span").text("Invalid zipcoce");
				$("#edit_zipcode").focus();
				return;
			}else{
				$("#edit_zipcode").closest("div").find("span").text("");
			}  
		}
		if($.trim($("#edit_ofcadd").val()) != ""){
			if(!comment_validation($.trim($("#edit_ofcadd").val()))){
				$("#edit_ofcadd").closest("div").find("span").text("No special characters allowed (except $ & : ( ) # @ _ . , + % ? -)");
				$("#edit_ofcadd").focus();
				return;
			}else{
				$("#edit_ofcadd").closest("div").find("span").text(" ");
			} 
		}else{
				$("#edit_ofcadd").closest("div").find("span").text(" ");
		}  
		if($.trim($("#edit_splcomments").val()) != ""){
			if(!comment_validation($.trim($("#edit_splcomments").val()))){
				$("#edit_splcomments").closest("div").find("span").text("No special characters allowed (except $ & : ( ) # @ _ . , + % ? -)");
				$("#edit_splcomments").focus();
				return;
			}else{
				$("#edit_splcomments").closest("div").find("span").text(" ");
			} 
		}else{
				$("#edit_splcomments").closest("div").find("span").text(" ");
		}
		if($.trim($("#edit_firstcontact").val())==""){
			$("#edit_firstcontact").closest("div").find("span").text("Contact Name is required.");
			$("#edit_firstcontact").focus();
			return;
		}else if(!validate_name($.trim($("#edit_firstcontact").val()))){
			$("#edit_firstcontact").closest("div").find("span").text("No special characters allowed (except &, _,-,.)");
			$("#edit_firstcontact").focus();
			return;
		}else if(!firstLetterChk($.trim($("#edit_firstcontact").val()))){
			$("#edit_firstcontact").closest("div").find("span").text("First letter should not be Numeric or Special character.");
			$("#edit_firstcontact").focus();
			return;
		}else{
			$("#edit_firstcontact").closest("div").find("span").text("");
		}     
		
		if($.trim($("#edit_disgnation").val())!=""){
			if(!validate_name($.trim($("#edit_disgnation").val()))){
				$("#edit_disgnation").closest("div").find("span").text("No special characters allowed (except &, _,-,.)");
				$("#edit_disgnation").focus();
				return;
			}else{
				$("#edit_disgnation").closest("div").find("span").text("");
			}  
		}          
		if($.trim($("#edit_primmobile").val())==""){
				$("#edit_primmobile").closest("div").find("span").text("Mobile Nummber is required.");
				$("#edit_primmobile").focus();
				return;
		}else if(!validate_PhNo($("#edit_primmobile").val())){
				$("#edit_primmobile").closest("div").find("span").text("Enter 10 digit mobile number.");
				$("#edit_primmobile").focus();
				return;
		}else{
				$("#edit_primmobile").closest("div").find("span").text("");
		} 
		if(mobile_val==mobile_val1){
			$("#edit_primmobile2").closest("div").find("span").text("Mobile1 number and Mobile2 number can not be same.");
			return;
		}else{
			$("#edit_primmobile2").closest("div").find("span").text("");
		}
		if($.trim($("#edit_primemail").val()) != ""){
			if($.trim($("#edit_primemail").val())==""){
				$("#edit_primemail").closest("div").find("span").text("Email is required.");
				$("#edit_primemail").focus();
				return;
			}else if(!validate_email($("#edit_primemail").val())){
					$("#edit_primemail").closest("div").find("span").text("Invalid email address.");
					$("#edit_primemail").focus();
					return;
			}else{
					$("#edit_primemail").closest("div").find("span").text("");
			} 
			if(email_val==email_val1){
				$("#edit_primemai2").closest("div").find("span").text("Email one and Email two can not be same.");
				return;
			}else{
				$("#edit_primemai2").closest("div").find("span").text("");
			}
		}
    
    if($.trim($("#display_pic").val())!=""){
		filevalidation('display_pic');
	}
	var addObj={};
    var mobiles=[];
    var emails=[], cust_custom=[], lead_custom=[];
    $("#custom_fields .col-md-4 .custom_fld").each(function(){
		var key = $(this).attr("id");
		cust_custom.push({attribute_value:$(this).val(),attribute_key:key});
	});
	addObj.customerCustom = cust_custom;
	addObj.custom_customer_id = $("#custom_customer_id").val();
	$("#custom_fields .col-md-4 .custom_fld_lead").each(function(){
		lead_custom.push({attribute_value:$(this).val(),attribute_key:$(this).attr("id")});
	});
	var cust_mail = [], cust_phone = [];
	addObj.leadCustom = lead_custom;
	addObj.custom_lead_id = $("#custom_lead_id").val();
    
    
    addObj.customer_name = $.trim($("#edit_leadname").val());
    addObj.customer_website = $.trim($("#edit_leadweb").val());
	cust_mail.push($.trim($("#edit_leadmail").val()));
    cust_phone.push($.trim($("#edit_leadphone").val()));
	addObj.customer_email = {};
	addObj.customer_phone = {};
	addObj.customer_email['email'] = cust_mail;
	addObj.customer_phone['phone'] = cust_phone;
    addObj.customer_country = $.trim($("#edit_country").val());
    addObj.customer_state = $.trim($("#edit_state").val());
    addObj.customer_city = $.trim($("#edit_city").val());
    addObj.customer_zipcode = $.trim($("#edit_zipcode").val());
    addObj.customer_ofcaddress = $.trim($("#edit_ofcadd").val());
    addObj.customer_splcomments = $.trim($("#edit_splcomments").val());
    addObj.customer_contactname = $.trim($("#edit_firstcontact").val());
    addObj.customer_designation = $.trim($("#edit_disgnation").val());
    addObj.contacttype = $.trim($("#edit_contacttype").val());
    addObj.customerid = $.trim($("#leadid").val());
    addObj.contact_id = $.trim($("#employeeid").val());
	addObj.customer_industry=$.trim($("#edit_industry").val());
	addObj.customer_business_location=$.trim($("#edit_business_location").val());
	addObj.contactname = $.trim($("#edit_firstcontact").val());
    addObj.designation = $.trim($("#edit_disgnation").val());
    addObj.address=$.trim($("#edit_address").val());
	addObj.coordinate=$.trim($("#edit_long").val())+","+$.trim($("#edit_latt").val());
    mobiles.push($.trim($("#edit_primmobile").val()));
    mobiles.push($.trim($("#edit_primmobile2").val()));
    emails.push($.trim($("#edit_primemail").val()));
    emails.push($.trim($("#edit_primemai2").val())); 
	addObj.contactEmail = {}; 
	addObj.contactNumber = {}; 
	addObj.contactEmail['email']=emails;
    addObj.contactNumber['phone']=mobiles;
	console.log(addObj)
	loaderShow();
     $.ajax({
        type : "POST",
        url : "<?php echo site_url('sales_customerController/postUpdateInfo')?>",
        dataType : 'json',
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){
				//loaderHide();			
			if(error_handler(data)){
				return;
			}else if(data=="exists"){
				alert("This Customer Name is already exists.");
			}else{
				$("#customerinfoedit").modal("hide");
				alert("Data has been updated successfully.");
				var fileurl = "<?php echo site_url("sales_customerController/file_upload/"); ?>"+addObj.customerid;
				uploadImage(fileurl, 'edit_photo');
			}
			
		}
	});	 
}
function uploadImage(url, formid) {
	$('#'+formid).on('submit', function (e){
		e.preventDefault();
	});
	var formData = new FormData($('#'+formid)[0]);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: url,
        data: formData,
        dataType : 'json',
        cache: false,
        contentType: false,
        processData: false,
            success : function(data){
				if(formid == "edit_photo"){
					cancelCust()
				}
                if(data== 1){
                    assignedLoad();					
                }
            }

    });
}
/* function viewloc(){
var lat=document.getElementById("view_long").value;
var log=document.getElementById("view_latt").value;

var myCenter=new google.maps.LatLng(lat,log);
var mapProp = {
  center:myCenter,
  zoom:14,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("view_maploc"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,
  });

marker.setMap(map);
} */
function viewrow(obj){         
var obj1 = {};
obj1.customerid=obj.customer_id;  
$("#customer_id").val(obj.customer_id);
leadlog();
$(".tab-pane.fade").each(function(){
	$(this).removeClass("active").removeClass("in");
});
$(".nav-tabs li").each(function(){
	$(this).removeClass("active");
}); 
$('.custom-file-upload').find('i').remove();
	
	
	var personal={};
	personal.name = obj.customer_name;
	personal.email = obj.customer_email;
	personal.phone = obj.customer_number;
	personal.website = obj.customer_website;
	personal.country = obj.country_name;
	personal.state = obj.state_name;
	personal.city = obj.city;
	personal.zip = obj.customer_zip;
	personal.industry = obj.industry_name;
	personal.Blocation = obj.customer_city;
	personal.logo = obj.customer_logo;
	
	leadinfoView(personal , 'Customer', 'custInfoView');
	contact_prsn_list(obj.customer_id , "contact_prsn_list", "customer", "sales");
	$("#view_leadname").val(obj.customer_name);
	$('#view_customer').text(obj.customer_name);
	$("#label_product").html(obj.product);
	$("#label_leadsource").html(obj.leadsource);
	$("#view_ofcadd").val(obj.customer_address);
	$("#view_splcomments").val(obj.customer_remarks);
	$("#view_long").val(obj.leadlng);
	$("#view_latt").val(obj.leadlat);
$.ajax({
	type: "POST",
	url: "<?php echo site_url('sales_customerController/customFieldCustomer');?>",
	data:JSON.stringify(obj1),
	dataType:'json',
	success: function(data) {
		console.log(data)
		$("#custom_fields_view").empty();
		var rowdata=data.customerCustom, row='';
		var rowdata1=data.leadCustom, row1='';
		console.log(data)
		if(error_handler(data)){
			return;
		}
		if(rowdata.length>0){
			var j = 0;
			$("#custom_head_view").show();
			for(i=0;i<rowdata.length;i++){
				if(i==j){
					row += '<div class="row">';
					j = j + 2;					
					if(rowdata[i].attribute_type=="Single_Line_Text"){
						row += "<div class='col-md-3'><label><b>"+rowdata[i].attribute_name+"</b></label></div><div class='col-md-3'><label id='customer_custom'>"+rowdata[i].attribute_value+"<label></div>";
					}
					
				}else{
					if(rowdata[i].attribute_type=="Single_Line_Text"){
						row += "<div class='col-md-3'><label><b>"+rowdata[i].attribute_name+"</b></label></div><div class='col-md-3'><label id='customer_custom'>"+rowdata[i].attribute_value+"<label></div>";
					}
				}
				if(i == (j - 1)){
					row += '</div>';
				}
				
			}
			$("#custom_fields_view").append(row);
		}
		if(rowdata1.length>0){
			var h = 0;
			$("#custom_head_view").show();
			for(i=0;i<rowdata1.length;i++){
				if(rowdata1[i].attribute_type=="Single_Line_Text"){
					if(i==h){
						row1 += '<div class="row">';
						h = h + 2;					
						if(rowdata1[i].attribute_type=="Single_Line_Text"){
							row1 += "<div class='col-md-3'><label><b>"+rowdata1[i].attribute_name+"</b></label></div><div class='col-md-3'><label id='customer_custom'>"+rowdata1[i].attribute_value+"<label></div>";
						}
						
					}else{
						if(rowdata1[i].attribute_type=="Single_Line_Text"){
							row1 += "<div class='col-md-3'><label><b>"+rowdata1[i].attribute_name+"</b></label></div><div class='col-md-3'><label id='customer_custom'>"+rowdata1[i].attribute_value+"<label></div>";
						}
					}
					if(i == (h - 1)){
						row1 += '</div>';
					}
				}
			}
			$("#custom_fields_view").append(row1);
		}
	}
});
$.ajax({
	type: "POST",
	url: "<?php echo site_url('sales_customerController/get_plugin_data');?>",
	dataType:'json',
	success: function(data) {
		if(error_handler(data)){
			return;
		}
		associated_plugin_used(data[0].plugin_id , "view", obj.coordinate)
		
	}
});	

    var addObj={};
    addObj.customer_id=$("#customer_id").val();
       $.ajax({
            type: "POST",
            url: "<?php echo site_url('sales_customerController/getProductPurchaseInfo'); ?>",
            dataType:'json',
            data:JSON.stringify(addObj),
            success: function(data){ 
					
					purchase_data = data;
					if(error_handler(data)){
						return;
					}
					$(".prod_purchase_info").empty();
					var row = "";					
					for (i = data.length-1;i>=0; i--) {
						var rowdata1 = JSON.stringify(data[i]);
						row += "<div id='"+data[i].purchase_id+"' style='border: 1px solid #d6cece;padding: 3px;'><div class='row product_heading' style='text-align:center'>Purchase Order "+(i+1)+"</div><div class='row'><div class='col-md-2'><label>Product Owner</label></div><div class='col-md-4'>"+data[i].product_owner+"</div><div class='col-md-2'><label>Currency</label></div><div class='col-md-4'>"+data[i].currency_name+"</div></div><div class='row'><div class='col-md-2'><label>Reference Number</label></div><div class='col-md-4'>"+data[i].reference_number+"</div></div>";
						for(j=0;j<data[i].prod_data.length;j++){
							if(data.length-1==0){
								$(".prod_purchase_info").css("height", 184);
								$(".prod_purchase_info").css("margin-bottom", 8);
								$(".prod_purchase_info").css("overflow", "scroll");
							}
							if(data.length-1>0){
								$(".prod_purchase_info").css("height", 300);
								$(".prod_purchase_info").css("margin-bottom", 8);
								$(".prod_purchase_info").css("overflow", "scroll");
							}
								var rowdata = data[i].prod_data[j];
								var end_cmng_date,renewal_cmng_date;
							
								
								rowdata.purchase_end_date = $.trim(rowdata.purchase_end_date);
								rowdata.renewal_date = $.trim(rowdata.renewal_date);
								
								if(	rowdata.purchase_end_date == "0000-00-00 00:00:00" ||
									rowdata.purchase_end_date == "-" ||
									rowdata.purchase_end_date == "" ){
									end_cmng_date = '';
								}else{
									end_cmng_date=moment(rowdata.purchase_end_date).format('lll');
								}
								
								if(	rowdata.renewal_date == "0000-00-00 00:00:00" ||
									rowdata.renewal_date == "-" ||
									rowdata.renewal_date == "" ){
									renewal_cmng_date = '';
								}else{
									renewal_cmng_date=moment(rowdata.renewal_date).format('lll');
								}
								
								row +="<div class='row'><b>"+(j+1)+"."+rowdata.product_name+"</b></div><div class='row'><div class='col-md-2'><label>Quantity</label></div><div class='col-md-4'><label>"+rowdata.quantity+"</label></div><div class='col-md-2'><label>Cost</label></div><div class='col-md-4'><label>"+rowdata.amount+"</label></div></div><div class='row'><div class='col-md-2'><label>Start Date</label></div><div class='col-md-4'><label>"+moment(rowdata.purchase_start_date).format('lll')+"</label></div><div class='col-md-2'><label>End Date</label></div><div class='col-md-4'><label>"+end_cmng_date+"</label></div></div><div class='row'><div class='col-md-2'><label>Renewal Date</label></div><div class='col-md-4'><label>"+renewal_cmng_date+"</label></div>";
								if(rowdata.rate){
									row +="<div class='col-md-2'><label>Rate</label></div><div class='col-md-4'><label>"+rowdata.rate+"</label></div></div>";
								}else{
									row +="</div>";
								}if(rowdata.score!='' && rowdata.customer_code!=''){
									row +="<div class='row'><div class='col-md-2'><label>Score</label></div><div class='col-md-4'><label>"+rowdata.score+"</label></div><div class='col-md-2'><label>Customer Code</label></div><div class='col-md-4'><label>"+rowdata.customer_code+"</label></div></div>";
								}else if(rowdata.customer_code!=''){
									row +="<div class='row'><div class='col-md-2'><label>Customer Code</label></div><div class='col-md-4'><label>"+rowdata.customer_code+"</label></div></div>";
								}else if(rowdata.score!=''){
									row +="<div class='row'><div class='col-md-2'><label>Score</label></div><div class='col-md-4'><label>"+rowdata.score+"</label></div></div>";
								}if(rowdata.priority){
									row +="<div class='row'><div class='col-md-2'><label>Priority</label></div><div class='col-md-4'><label>"+rowdata.priority+"</label></div></div>";
								}	
						}								
						row +="</div>";
					}				
					$(".prod_purchase_info").append(row); 
            }
        });
}
function associated_plugin_used(associated_plugin , section , savedCordinate){
	/* --------- hide/ show map section if user have Navigator module------------ */
	$('#edit_maploc,#edit_map2,#edit_map1, .map-marker').hide();
	var pluginJSON= JSON.parse(associated_plugin);
	for(var key in pluginJSON){
		if(key == "Navigator" && pluginJSON[key].length > 0){
			$('#edit_selectmap,#view_map_render,#select_map,#edit_map1,.map-marker').show();
			$('#edit_okmap,#edit_map2').show();
			if(section == 'add'){
				rendergmap('mapname','search','long','latt');
			}
			if(section == 'edit'){
				if(savedCordinate != ","){
					var latlng= savedCordinate;			
					var arr = latlng.split(',');	
					$("#edit_long").val(arr[0]);
					$("#edit_latt").val(arr[1]);
					$('#edit_maploc').show();
					show_map('#edit_map1','#edit_map2','#edit_selectmap','edit_long','edit_latt','edit_maploc');
				}else{
					rendergmap('edit_map1','edit_map2','edit_mapname','edit_search','edit_long','edit_latt');
				}
			}
			if(section == 'view'){
				if(savedCordinate != ","){
					var latlng= savedCordinate;			
					var arr = latlng.split(',');	
					$("#view_long").val(arr[0]);
					$("#view_latt").val(arr[1]);
					$("#view_maploc").html("");
					$("#view_maploc").show();
					
					setTimeout(function(){
					   show_map(' ',' ',' ','view_long','view_latt','view_maploc');
					}, 2000);
					
				}
			}
			
		}
	}
	/* ------------------------------------ */
}

function googlemap(section){
		if(section == "add"){
			$("#select_map").show();
			$("#map1").hide();
			$("#map2").hide();
			rendergmap('mapname','search','long','latt');
		}
		if(section == "edit"){
			$("#edit_selectmap").show();
			rendergmap('edit_map1','edit_map2','edit_mapname','edit_search','edit_long','edit_latt');
		}
	}
/* -------------------------------------------- */
	function codeAddress(searchBox, longitude, lattitude, mapid) {
	    geocoder = new google.maps.Geocoder();
	    var address = document.getElementById(searchBox).value;
	    geocoder.geocode( { 'address': address}, function(results, status) {
	            if (status == google.maps.GeocoderStatus.OK) {
	                    document.getElementById(longitude).value = results[0].geometry.location.lat();
	                    document.getElementById(lattitude).value = results[0].geometry.location.lng();
	                    show_map(' ',' ',' ',longitude,lattitude, mapid);
	            }else {
	                    alert("Geocode was not successful for the following reason: " + status);
	            }
	    });
	}
/* ----------------Set pointer in Map---------------------------- */
	
function show_map(map1, map2, selectmap, longitude, lattitude, maploc){
	
	if(map1 != " "){
		$(selectmap).hide();
		$(map1+", "+ map2 +", "+maploc).show();
	}
	var lat=document.getElementById(longitude).value;
	var log=document.getElementById(lattitude).value;
	var myCenter=new google.maps.LatLng(lat,log);
	var mapProp = {
	  center:myCenter,
	  zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	  };

	var map=new google.maps.Map(document.getElementById(maploc),mapProp);

	var marker=new google.maps.Marker({
	  position:myCenter,
	  });

	marker.setMap(map);
}

/* function edit_rendergmap() { */
function rendergmap(googleBtn,markerMap,mapElm,searchElm,longitudeElm,lattitudeElm) {
	$("#"+googleBtn+", #"+ markerMap).hide();
	
	var mapOptions = {
		center: new google.maps.LatLng(12.93325692, 77.57465679),
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var infoWindow = new google.maps.InfoWindow();
	var latlngbounds = new google.maps.LatLngBounds();
	var map = new google.maps.Map(document.getElementById(mapElm), mapOptions);
	
	var input = document.getElementById(searchElm);
	var searchBox = new google.maps.places.SearchBox(input);
	var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.setTypes(['geocode']);
		
	map.addListener('bounds_changed', function() {
	  searchBox.setBounds(map.getBounds());
	});

	var markers = [];
	
	searchBox.addListener('places_changed', function() {
	  var places = searchBox.getPlaces();

	  if (places.length == 0) {
		return;
	  }

	  markers.forEach(function(marker) {
		marker.setMap(null);
	  });
	  markers = [];

	  var bounds = new google.maps.LatLngBounds();
	  places.forEach(function(place) {
		if (!place.geometry) {
		  alert("Returned place contains no geometry");
		  return;
		}
		var icon = {
		  url: place.icon,
		  size: new google.maps.Size(71, 71),
		  origin: new google.maps.Point(0, 0),
		  anchor: new google.maps.Point(17, 34),
		  scaledSize: new google.maps.Size(25, 25)
		};

		markers.push(new google.maps.Marker({
		  map: map,
		  icon: icon,
		  title: place.name,
		  position: place.geometry.location
		}));

		if (place.geometry.viewport) {
		  bounds.union(place.geometry.viewport);
		} else {
		  bounds.extend(place.geometry.location);
		}
	  });
	  map.fitBounds(bounds);
	   var place = autocomplete.getPlace();
		if (!place.geometry) {
			return;
		}

		var address = '';
		if (place.address_components) {
			address = [
				(place.address_components[0] && place.address_components[0].short_name || ''),
				(place.address_components[1] && place.address_components[1].short_name || ''),
				(place.address_components[2] && place.address_components[2].short_name || '')
				].join(' ');
		}
	});
	
	google.maps.event.addListener(map, 'click', function(e){
		
		var latlngstr = "lat=" + e.latLng.lat() + "&lng=" + e.latLng.lng();
		document.getElementById(longitudeElm).value = e.latLng.lat();
		document.getElementById(lattitudeElm).value = e.latLng.lng();
	});
}
function save_purchaseEdit(){
	/* ------------Product field set validation------------ */
		
		var product_field_chk=0;
		var e_prod=[],e_pro_price=[],e_pro_quantity=[],e_pro_strDate=[], e_pro_endDate=[];	
		$(".e_product_Number").each(function(){
		if($(this).val()==""){
			$(this).closest("div").find("span").text("Quantity is required.");
			product_field_chk =1;
		}else if(!validate_quantity($.trim($(this).val()))) {
			$(this).closest("div").find("span").text("Please enter only numbers");
			product_field_chk =1;
		}else{
			$(this).closest("div").find("span").text("");
			e_pro_quantity.push($(this).val());	
		}			
    });
	$(".e_pro_Value").each(function(){
		if($(this).val()==""){
			$(this).closest("div").find("span").text("Cost is required.");
			product_field_chk =1;
		}else if(!validate_quantity($.trim($(this).val()))) {
			$(this).closest("div").find("span").text("Only numbers and . allowed");
			product_field_chk =1;
		}else{
			$(this).closest("div").find("span").text("");
			e_pro_price.push($(this).val());
		}		
    });
	$(".e_product_start_date").each(function(){
		if($(this).val()==""){
			$(this).closest("div").find("span").text("Start date is required.");
			product_field_chk =1;
		}else{
			$(this).closest("div").find("span").text("");
			e_pro_strDate.push($(this).val());
		}			
    });
	/* $(".e_product_end_date").each(function(){
		if($(this).val()==""){
			$(this).closest("div").find("span").text("End date is required.");
			product_field_chk =1;
		}else{
			$(this).closest("div").find("span").text("");
			e_pro_endDate.push($(this).val());
		}			
    });  */	
	if(product_field_chk ==1){
			return;
		}
	$("#selcd_pro1 .product_heading span").each(function(){
            e_prod.push($(this).attr("class"));       
    });
	var sum_arry = [],purchase_arry=[];
	for(i=0;i<e_prod.length;i++){
		var obj = {};
		obj.product_id = e_prod[i];
		obj.pro_cost = e_pro_price[i];
		obj.pro_quantity = e_pro_quantity[i];
		var start_datetime = moment(e_pro_strDate[i], 'lll').format('YYYY-MM-DD HH:MM:SS');
		var end_datetime1 = moment(e_pro_endDate[i], 'lll').format('YYYY-MM-DD HH:MM:SS');
		obj.pro_strDate = start_datetime;
		obj.pro_endDate = end_datetime1;
		sum_arry.push(obj);
	}
    var addObj={};
    addObj.product = sum_arry;
    addObj.purchase_id=$.trim($("#purchase").val());
	addObj.id=$("#e_id").val();
    addObj.product_Currency=$.trim($("#e_currency1").val());
    addObj.purchase_doc=$.trim($("#e_purchase_doc").val());
    addObj.customer_id=$("#customer_id").val();
    addObj.ref_number=$("#e_ref_number").val();
	purchase_arry.push(addObj);
	loaderShow();
	$.ajax({
        type : "POST",
        url : "<?php echo site_url('sales_customerController/updateProductPurchase')?>",
        dataType : 'json',
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){ 
			$("#editmodalpurchase").modal("hide");
			loaderHide();
			if(error_handler(data)){
				return;
			}
			$(".prod_purchase_info").empty();
			var row = "";					
			for (i = data.length-1;i>=0; i--) {
				var rowdata1 = JSON.stringify(data[i]);
				row += "<div id='"+data[i].purchase_id+"' style='border: 1px solid #d6cece;padding: 3px;'><div class='row product_heading' style='text-align:center'>Purchase Order "+(i+1)+"</div><div class='row'><div class='col-md-2'><label>Product Owner</label></div><div class='col-md-4'>"+data[i].product_owner+"</div><div class='col-md-2'><label>Currency</label></div><div class='col-md-4'>"+data[i].currency_name+"</div></div><div class='row'><div class='col-md-2'><label>Reference Number</label></div><div class='col-md-4'>"+data[i].reference_number+"</div></div>";
				for(j=0;j<data[i].prod_data.length;j++){
					if(data.length-1==0){
						$(".prod_purchase_info").css("height", 184);
						$(".prod_purchase_info").css("margin-bottom", 8);
						$(".prod_purchase_info").css("overflow", "scroll");
					}
					if(data.length-1>0){
						$(".prod_purchase_info").css("height", 300);
						$(".prod_purchase_info").css("margin-bottom", 8);
						$(".prod_purchase_info").css("overflow", "scroll");
					}
					var rowdata = data[i].prod_data[j];
						var end_cmng_date,renewal_cmng_date;
						if(rowdata.purchase_end_date=="0000-00-00 00:00:00"){
							end_cmng_date='';
						}else{
							end_cmng_date=moment(rowdata.purchase_end_date).format('lll');
						}if(rowdata.renewal_date=="0000-00-00 00:00:00"){
							renewal_cmng_date='';
						}else{
							renewal_cmng_date=moment(rowdata.renewal_date).format('lll');
						}
						row +="<div class='row'><b>"+(j+1)+"."+rowdata.product_name+"</b></div><div class='row'><div class='col-md-2'><label>Quantity</label></div><div class='col-md-4'><label>"+rowdata.quantity+"</label></div><div class='col-md-2'><label>Cost</label></div><div class='col-md-4'><label>"+rowdata.amount+"</label></div></div><div class='row'><div class='col-md-2'><label>Start Date</label></div><div class='col-md-4'><label>"+moment(rowdata.purchase_start_date).format('lll')+"</label></div><div class='col-md-2'><label>End Date</label></div><div class='col-md-4'><label>"+end_cmng_date+"</label></div></div><div class='row'><div class='col-md-2'><label>Renewal Date</label></div><div class='col-md-4'><label>"+renewal_cmng_date+"</label></div>";
						if(rowdata.rate){
							row +="<div class='col-md-2'><label>Rate</label></div><div class='col-md-4'><label>"+rowdata.rate+"</label></div></div>";
						}else{
							row +="</div>";
						}if(rowdata.score!='' && rowdata.customer_code!=''){
							row +="<div class='row'><div class='col-md-2'><label>Score</label></div><div class='col-md-4'><label>"+rowdata.score+"</label></div><div class='col-md-2'><label>Customer Code</label></div><div class='col-md-4'><label>"+rowdata.customer_code+"</label></div></div>";
						}else if(rowdata.customer_code!=''){
							row +="<div class='row'><div class='col-md-2'><label>Customer Code</label></div><div class='col-md-4'><label>"+rowdata.customer_code+"</label></div></div>";
						}else if(rowdata.score!=''){
							row +="<div class='row'><div class='col-md-2'><label>Score</label></div><div class='col-md-4'><label>"+rowdata.score+"</label></div></div>";
						}if(rowdata.priority){
							row +="<div class='row'><div class='col-md-2'><label>Priority</label></div><div class='col-md-4'><label>"+rowdata.priority+"</label></div></div>";
						}
				}								
				row +="</div>";
			}				
			$(".prod_purchase_info").append(row); 
			close_modal1();
		}
	});
}
function editselrow(obj){	
	$("#e_currency1").val(obj.currency_id);
	$("#e_id").val(obj.prod_data.id);
	$("#editmodalpurchase").modal("show");
	$("#purchase").val(obj.purchase_id);
	$("#e_opp_owner").text(obj.purchase_id);
	$("#e_currency").text(obj.currency_name);	
	$("#selcd_pro1").empty();
	var row = "",cureency_type,last2;
	for(i=0;i<obj.prod_data.length;i++){
		var rowdata = obj.prod_data[i];
		cureency_type = obj.currency_name;
		last2 = cureency_type.slice(-3);		
		row +='<div class="'+rowdata.product_id+' product_field"><div class="row product_heading"><span class="'+rowdata.product_id+'">'+rowdata.product_name+'</span></div><div class="row"><div class="col-md-2"><label for="">Quantity*</label></div><div class="col-md-4"><input class="form-control e_product_Number" name="e_product_Number" value="'+rowdata.quantity+'"/><span class="error-alert"></span></div><div class="col-md-2"><label for="">Cost*</label></div><div class="col-md-4"><input type="" class="form-control e_pro_Value1" value="" disabled ><input type="" class="form-control e_pro_Value" id="'+rowdata.product_id+'" value="'+rowdata.amount+'"><span class="error-alert"></span></div></div><div class="row"><div class="col-md-2"><label for="">Start Date*</label></div><div class="col-md-4"><input type="text" id="actve_duration1" class="form-control e_product_start_date" placeholder="DD-MM-YYYY"  maxlength="5" value="'+rowdata.purchase_start_date+'">	<span class="error-alert"></span></div><div class="col-md-2"><label for="">End Date</label></div><div class="col-md-4"><input class="form-control e_product_end_date" placeholder="DD-MM-YYYY" value="'+rowdata.purchase_end_date+'"/><span class="error-alert"></span></div></div></div>';
	}
	$("#selcd_pro1").append(row);
	if(last2 == "USD"){
		$(".e_pro_Value1").val("USD");
	}
	if(last2 == "INR"){
		$(".e_pro_Value1").val("INR");
	}else{
		$(".e_pro_Value1").val("");
		$(".e_pro_Value1").val("");
	}
	$(".e_product_start_date").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'lll',
	});
	$(".e_product_end_date").datetimepicker({
		ignoreReadonly:true,
		allowInputToggle:true,
		format:'lll',
	});
}	
function addProductPurchase(){
	if($.trim($("#pro_owner").val())==""){
			$("#pro_owner").closest("div").find("span").text("Owner is required.");
			$("#pro_owner").focus();				
			return;
		}else{
			$("#pro_owner").closest("div").find("span").text("");
		}
	if($.trim($("#product_Currency").val())==""){
			$("#product_Currency").closest("div").find("span").text("Currency is required.");
			$("#product_Currency").focus();				
			return;
		}else{
			$("#product_Currency").closest("div").find("span").text("");
		}
		if($.trim($("#product_Currency").val())==""){
			$("#product_Currency").closest("div").find("span").text("Currency is required.");
			$("#product_Currency").focus();				
			return;
		}else{
			$("#product_Currency").closest("div").find("span").text("");
		}

		/* ------------Product field set validation------------ */
		/* var product_field_chk=0;
		$("#selcd_pro .product_field").each(function(){
			var product_Number= $(this).find(".product_Number").val();
			var pro_Value= $(this).find(".pro_Value").val();
			var product_start_date= $(this).find(".product_start_date").val();
			var product_end_date= $(this).find(".product_end_date").val();
			
			if($.trim(product_Number) == ""){
				$(this).find(".product_Number").closest("div").find("span").text("Quantity is required.");
				//$(this).find(".product_Number").focus();				
				product_field_chk =1;
			}else if(!validate_quantity($.trim($(".product_Number").val()))) {
				$(".product_Number").closest("div").find("span").text("Please enter only numbers");
				return;
			}else{
				$(this).find(".product_Number").closest("div").find("span").text("");
			}
			
			if($.trim(pro_Value) ==""){
				$(this).find(".pro_Value").closest("div").find("span").text("Cost is required.");
				//$(this).find(".pro_Value").focus();				
				product_field_chk =2;
			}else if(!validate_cost($.trim($(".pro_Value").val()))) {
				$(".pro_Value").closest("div").find("span").text("Only numbers and . allowed");
				return;
			}else{
				$(this).find(".pro_Value").closest("div").find("span").text("");
			}
			
			if($.trim(product_start_date) == ""){
				$(this).find(".product_start_date").closest("div").find("span").text("Start date is required.");				
				product_field_chk =3;
			}else{
				$(this).find(".product_start_date").closest("div").find("span").text("");
			}
			
			if($.trim(product_end_date) == ""){
				$(this).find(".product_end_date").closest("div").find("span").text("End date is required.");			
				product_field_chk =4;
			}else{
				$(this).find(".product_end_date").closest("div").find("span").text("");
			}	
			
		});
		if(product_field_chk ==1){
			return;
		}else if(product_field_chk ==2){
			return;
		}else if(product_field_chk ==3){
			return;
		}else if(product_field_chk ==4){
			return;
		} */		

	 var product_field_chk=0;
	 var prod=[],pro_price=[],pro_quantity=[],pro_strDate=[], pro_endDate=[];
    $(".pro_Value").each(function(){
		if($(this).val()==""){
			$(this).closest("div").find("span").text("Cost is required.");
			product_field_chk =1;
		}else if(!validate_quantity($.trim($(this).val()))) {
			$(this).closest("div").find("span").text("Only numbers and . allowed");
			product_field_chk =1;
		}else{
			$(this).closest("div").find("span").text("");
			pro_price.push($(this).val());
		}		
    });
	$(".product_Number").each(function(){
		if($(this).val()==""){
			$(this).closest("div").find("span").text("Quantity is required.");
			product_field_chk =1;
		}else if(!validate_quantity($.trim($(this).val()))) {
			$(this).closest("div").find("span").text("Please enter only numbers");
			product_field_chk =1;
		}else{
			$(this).closest("div").find("span").text("");
			pro_quantity.push($(this).val());	
		}			
    });
	$(".product_start_date").each(function(){
		if($(this).val()==""){
			$(this).closest("div").find("span").text("Start date is required.");
			product_field_chk =1;
		}else{
			$(this).closest("div").find("span").text("");
			pro_strDate.push($(this).val());
		}			
    });
	/* $(".product_end_date").each(function(){
		if($(this).val()==""){
			$(this).closest("div").find("span").text("End date is required.");
			product_field_chk =1;
		}else{
			$(this).closest("div").find("span").text("");
			pro_endDate.push($(this).val());
		}			
    }); */
	if(product_field_chk ==1){
			return;
		}
	$("#product_value1 li input[type=checkbox]").each(function(){
        if($(this).prop('checked')==true){
            prod.push($(this).val());
        }        
    });
	var sum_arry = [],purchase_arry=[];
	for(i=0;i<prod.length;i++){
		var obj = {};
		obj.product_id = prod[i];
		obj.pro_cost = pro_price[i];
		obj.pro_quantity = pro_quantity[i];
		var start_datetime = moment(pro_strDate[i], 'lll').format('YYYY-MM-DD HH:MM:SS');
		var end_datetime1 = moment(pro_endDate[i], 'lll').format('YYYY-MM-DD HH:MM:SS');
		obj.pro_strDate = start_datetime;
		obj.pro_endDate = end_datetime1;
		sum_arry.push(obj);
	}
    var addObj={};
    addObj.product = sum_arry;
    addObj.pro_owner=$.trim($("#pro_owner").val());
    addObj.product_Currency=$.trim($("#product_Currency").val());
    addObj.purchase_doc=$.trim($("#purchase_doc").val());
    addObj.customer_id=$("#customer_id").val();
    addObj.ref_number=$("#ref_number").val();
	purchase_arry.push(addObj);
	loaderShow();
	$.ajax({
        type : "POST",
        url : "<?php echo site_url('sales_customerController/addProductPurchase')?>",
        dataType : 'json',
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){ 
		loaderHide();
        $("#editmodalpurchase").modal("hide");
			loaderHide();
			if(error_handler(data)){
				return;
			}
			$(".prod_purchase_info").empty();
			var row = "";					
			for (i = data.length-1;i>=0; i--) {
				var rowdata1 = JSON.stringify(data[i]);
				row += "<div id='"+data[i].purchase_id+"' style='border: 1px solid #d6cece;padding: 3px;'><div class='row product_heading' style='text-align:center'>Purchase Order "+(i+1)+"</div><div class='row'><div class='col-md-2'><label>Product Owner</label></div><div class='col-md-4'>"+data[i].product_owner+"</div><div class='col-md-2'><label>Currency</label></div><div class='col-md-4'>"+data[i].currency_name+"</div></div><div class='row'><div class='col-md-2'><label>Reference Number</label></div><div class='col-md-4'>"+data[i].reference_number+"</div></div>";
				for(j=0;j<data[i].prod_data.length;j++){
					if(data.length-1==0){
						$(".prod_purchase_info").css("height", 184);
						$(".prod_purchase_info").css("margin-bottom", 8);
						$(".prod_purchase_info").css("overflow", "scroll");
					}
					if(data.length-1>0){
						$(".prod_purchase_info").css("height", 300);
						$(".prod_purchase_info").css("margin-bottom", 8);
						$(".prod_purchase_info").css("overflow", "scroll");
					}
					var rowdata = data[i].prod_data[j];
						var end_cmng_date,renewal_cmng_date;
						if(rowdata.purchase_end_date=="0000-00-00 00:00:00"){
							end_cmng_date='';
						}else{
							end_cmng_date=moment(rowdata.purchase_end_date).format('lll');
						}if(rowdata.renewal_date=="0000-00-00 00:00:00"){
							renewal_cmng_date='';
						}else{
							renewal_cmng_date=moment(rowdata.renewal_date).format('lll');
						}
						row +="<div class='row'><b>"+(j+1)+"."+rowdata.product_name+"</b></div><div class='row'><div class='col-md-2'><label>Quantity</label></div><div class='col-md-4'><label>"+rowdata.quantity+"</label></div><div class='col-md-2'><label>Cost</label></div><div class='col-md-4'><label>"+rowdata.amount+"</label></div></div><div class='row'><div class='col-md-2'><label>Start Date</label></div><div class='col-md-4'><label>"+moment(rowdata.purchase_start_date).format('lll')+"</label></div><div class='col-md-2'><label>End Date</label></div><div class='col-md-4'><label>"+end_cmng_date+"</label></div></div><div class='row'><div class='col-md-2'><label>Renewal Date</label></div><div class='col-md-4'><label>"+renewal_cmng_date+"</label></div>";
						if(rowdata.rate){
							row +="<div class='col-md-2'><label>Rate</label></div><div class='col-md-4'><label>"+rowdata.rate+"</label></div></div>";
						}else{
							row +="</div>";
						}if(rowdata.score!='' && rowdata.customer_code!=''){
							row +="<div class='row'><div class='col-md-2'><label>Score</label></div><div class='col-md-4'><label>"+rowdata.score+"</label></div><div class='col-md-2'><label>Customer Code</label></div><div class='col-md-4'><label>"+rowdata.customer_code+"</label></div></div>";
						}else if(rowdata.customer_code!=''){
							row +="<div class='row'><div class='col-md-2'><label>Customer Code</label></div><div class='col-md-4'><label>"+rowdata.customer_code+"</label></div></div>";
						}else if(rowdata.score!=''){
							row +="<div class='row'><div class='col-md-2'><label>Score</label></div><div class='col-md-4'><label>"+rowdata.score+"</label></div></div>";
						}if(rowdata.priority){
							row +="<div class='row'><div class='col-md-2'><label>Priority</label></div><div class='col-md-4'><label>"+rowdata.priority+"</label></div></div>";
						}
				}								
				row +="</div>";
			}				
			$(".prod_purchase_info").append(row);
        
         $('#modalpurchase').modal('hide');  
		close_modal1();
	}
});	 
 
	}

function close_modal(){
	$('.modal').modal('hide');
	$('.modal .form-control[type=text],.modal textarea').val("");
	$('.modal select.form-control').val($('.modal select.form-control option:first').val());
	$(".contact_type1").remove(); 
	$(".proDetail").remove(); 
}

function close_modal1(){
	$('#modalpurchase').modal('hide');
	$('.modal select.form-control').val($('.modal select.form-control option:first').val());
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox]').val("");
	$("#product_Currency").empty();
	$('#selcd_pro').empty();
	$('#add_product').hide();
	$('.fetch_btn').hide();
}
function close_purchase(){
	$('#editmodalpurchase').modal('hide');
	$('.modal select.form-control').val($('.modal select.form-control option:first').val());
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox]').val("");
	$('#selcd_pro').empty();
	$('#add_product').hide();
	$('.fetch_btn').hide();
}
function checkAllLeads(e) {
		$('tr input:checkbox',$("#tableTeam")).prop('checked',e.checked);
	}
	function checkAllMgrs(e)	{
		$('li input:checkbox',$("#mgrlist")).prop('checked',e.checked);
	}
function change_val(){
	var cureency_type = $("#product_Currency option:selected").text();
	var last2 = cureency_type.slice(-3);
	if(last2 == "USD"){
		$(".pro_Value1").val("USD");
	}
	if(last2 == "INR"){
		$(".pro_Value1").val("INR");
	}else{
		$(".pro_Value1").val("");
		$(".pro_Value").val("");
	}
}

</script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini"> 
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
								<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-toggle="tooltip" data-placement="bottom" title="View all the customers that you own. You can create opportunities for any of these from the Opportunity tabs."/>
							</div>
						</span>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Accepted Customer</h2>	
					</div>
					<div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="table-responsive">
					<table class="table hidden" id="tableTeam">
						<thead>  
						<tr>
							<th class="table_header">SL No</th>
							<th class="table_header">Name</th>
							<th class="table_header"> Products</th>
							<th class="table_header">Location</th>	
							<th class="table_header">Customer Status</th>
							<th class="table_header">Customer Manger Owner</th>
							<th class="table_header"><input type="hidden" id="purchase" /></th>	
							<th class="table_header"></th>	
						</tr>
						</thead>  
						<tbody id="tablebody">
						</tbody>    
					</table>
				</div>
            </div>
               <div id="customerinfoedit" class="modal fade" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                            <div class="modal-header">
                                <span class="close" onclick="cancelCust()">x</span>
                                <h4 class="modal-title"><b>Edit <span id="edit_customer"></span></b></h4>
									<input type="hidden" id="custom_customer_id" />
									<input type="hidden" id="custom_lead_id" />
                            </div>
                            <div class="modal-body">								
                                <div class="row">
                                    <div class="col-md-2 ">
                                        <label for="edit_leadname">Customer Name*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_leadname" name="edit_leadname" >
                                        <input type="hidden" class="form-control" id="leadid" name="leadid" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_leadweb">Customer Website</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="edit_leadweb" name="edit_leadweb" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_leadmail">Customer Email</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"name="adminContactDept" class="form-control" id="edit_leadmail" name="edit_leadmail" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_leadphone">Customer Phone</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"class="form-control" id="edit_leadphone" name="edit_leadphone" >											
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2" >
                                        <label for="edit_country">Country</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select  class="form-control" id="edit_country" name="edit_country" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_state">State</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select type="text" class="form-control" id="edit_state" name="edit_state" >
                                        </select>				
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_city">City</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_city" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_zipcode">Zipcode</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="edit_zipcode" name="edit_zipcode" >

                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                   <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_industry">Industry</label> 
                                    </div>
                                    <div class="col-md-4">
                                       <select  class="form-control" id="edit_industry" name="edit_industry" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_business_location">Location</label> 
                                    </div>
                                    <div class="col-md-4">
                                       <select  class="form-control" id="edit_business_location" name="edit_business_location" >
                                        </select>

                                        <span class="error-alert"></span>
                                    </div>
                                </div>
								<div class="row">
									<div class="col-md-2 ">
										<label for="edit_displaypic">Photo</label> 
									</div>
									<div class="col-md-4">	
										<form method="POST" enctype="multipart/form-data" id="edit_photo" name="edit_photo">
											<input type="hidden" name="view_value" value="inprogress_lead"/>
											<label for="edit_pic" class="custom-file-upload"> 
												<img src="" title="Upload Contact Person's Photo" id="leadAvrtEdit" width="30px" height="30px"/>
											</label>
											<!--<label for="edit_pic" class="custom-file-upload"> <i class="fa fa-cloud-upload"></i> Upload</label>-->
											<input type="file" class="form-control" accept="image/*"  name = "userfile" id="edit_pic" onchange="filevalidation('edit_pic')"/>
											<span class="error-alert"></span>
										</form>
									</div>
								</div>
								<div class="row lead_address">
									<div class="col-md-6 col-sm-6 col-xs-6">
										<center><b>Office Address</b></center>
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<center><b>Special Comments</b></center>
									</div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <textarea class="form-control" id="edit_ofcadd"></textarea>
										<span class="error-alert"></span>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <textarea class="form-control" id="edit_splcomments"></textarea>
										<span class="error-alert"></span>
                                    </div>
                                </div>
								<div class="row" id="edit_map2" >
									<div class="row" id="edit_maploc" style="width:100% px;height:150px;border:1px;">
									</div>
                                </div>
                                 <div class="row" id="edit_map1">
									<div class="row">
										<center>
											<button type="button" class="btn" id="edit_okmap" onclick="googlemap('edit')">Google Map</button>
										</center>
									 </div>
                                 </div>                               
                                <div class="row" id="edit_selectmap" >
									<div class="row" id="edit_mapname" style="width:100% px;height:150px;border:1px;">
									</div>
                                    <div class="row">
                                        <div class="col-md-1">
                                        <label for="search">Search</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" onfocusout="codeAddress('edit_search','edit_long','edit_latt','edit_mapname');" id="edit_search" name="edit_search" />
                                    </div>
                                  <div class="col-md-1">
                                   <label for="edit_long">Longitude</label> 
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" id="edit_long" name="edit_long"/>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="edit_latt">Latitude</label> 
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" id="edit_latt" name="edit_latt" />
                                    </div>
                                    
									
                                    <div class="col-md-1">
                                        <button type="button"  class="btn btn-default" id="proceed" style="margin-top:0px" onclick="show_map('#edit_map1','#edit_map2','#edit_selectmap','edit_long','edit_latt','edit_maploc');">OK</button>
                                    </div>
                                    </div>
                                </div>
                              
                                <div class="row" >
                                    <div class="col-md-12 lead_address">
                                        <center><b>Customer Contact Person Information</b></center>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_firstcontact">Contact Person*</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <input type="text" class="form-control" id="edit_firstcontact" name="edit_firstcontact" >
                                        <input type="hidden"  id="employeeid" name="employeeid">
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_disgnation">Designation</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="edit_disgnation" name="edit_disgnation" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_primmobile">Mobile Number 1 *</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_primmobile" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_primmobile2">Mobile Number 2</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <input type="text"  class="form-control" id="edit_primmobile2" name="edit_primmobile2" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_primemail">Email 1</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_primemail" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_primemai2">Email 2</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="edit_primemai2" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
									<div class="col-md-2 ">
                                        <label for="edit_contacttype">Buyer Persona</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="edit_contacttype">
                                            
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
								<div class="row">
                                    <div class="col-md-2 ">
                                        <label for="edit_address">Address</label> 
                                    </div>
                                    <div class="col-md-10">									
										<textarea class="form-control" id="edit_address"></textarea>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>		
								<div class="row none" id="custom_head">
                                    <div class="col-md-12 lead_address">
                                        <center><b>Custom Fields</b></center>
                                    </div>
                                </div>
								<div class="row" id="custom_fields">
								
                                </div>					
                              </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" id="edit_info" onclick="edit_save()">Save</button>
                                <button  type="button" class="btn btn-default" onclick="cancelCust()" >Cancel</button>
                            </div>
                    </div>
                </div>
            </div>
             <div id="leadview" class="modal fade" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="viewpopup" class="form" action="#" method="post" >
                            <div class="modal-header">
                                <span class="close" onclick="cancel1()">x</span>
								<!--<span style='float: right; margin-right: 54px;margin-top: 4px;'onclick="lead_history(
                                )" class="fa fa-history"></span> -->  
                                <h4 class="modal-title"><b>View <span id="view_customer"></span></b></h4>
                                <input type="hidden" id="customer_id">
                            </div>
                            <div class="modal-body">								
                                <div id='custInfoView'></div>
								<div class="row lead_address">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<center><b>Product Purchase Information</b></center>
									</div>
                                </div>
                                <div class="prod_purchase_info">
                                </div>
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
										<textarea class="form-control" id="view_ofcadd" readonly="readonly"></textarea>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<textarea class="form-control" id="view_splcomments" readonly="readonly"></textarea>
									</div>
								</div>
								<div class="row lead_address map-marker">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<center><b>Google Map</b></center>
									</div>
								</div>
                                <input type="hidden" id="view_latt">
                                 <input type="hidden" id="view_long">
                                <div class="row map-marker" id="view_map2" >
									<div class="row" id="view_maploc" style="width:100% px;height:150px;border:1px;">
									 </div>
                                </div>
							   <div class="row" >
									<div class="col-md-12 lead_address">
										<center><b>Customer Contact Person Information</b></center>
									</div>
								</div>
								<div class="row" id="contact_prsn_list">
						
								</div>
                                <!--<div class="row">
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_firstcontact">Contact Person</label> 
											</div>
											<div class="col-md-4">
												<label id="view_firstcontact"></label> 
											</div>                                    
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_designation">Designation</label> 
											</div>
											<div class="col-md-4">
												<label id="view_disgnation"></label> 
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primmobile">Mobile Number 1</label> 
											</div>
											<div class="col-md-4">
												<label id="view_primmobile"></label> 
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primmobile2">Mobile Number 2</label> 
											</div>
											<div class="col-md-4">
												 <label id="view_primmobile2"></label> 
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primemail">Email 1</label> 
											</div>
											<div class="col-md-4">
												 <label id="view_primemail"></label> 
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_contacttype">Buyer Persona</label> 
											</div>
											<div class="col-md-4">
												<label id="view_contacttype"></label> 
												<input type="hidden" id="lead_id"/>
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
												<label id="view_primemai2"></label> 
											</div>                                  
										</div>										
									</div>
									<div class="col-md-12">
										<div class="col-md-2 apport_label">
											<label>Address</label> 
										</div>
										<div class="col-md-4">
											 <pre class="pre" id="view_contactAdd" ></pre>
										</div>
									</div>
								</div>	-->
								<br/>
								<div class="row">
									<ul class="nav nav-tabs">
										<li class="active" onclick="leadlog();" id="logdetails_cust"><a data-toggle="tab" href="#logdetails">Customer History</a></li>
										<li onclick="schedule_fetch();"><a data-toggle="tab" href="#logdetails1">Scheduled Activities</a></li>
										<li onclick="opp_log();"><a data-toggle="tab" href="#opp_details1">Opportunities</a></li>
										<li onclick="hist_log();"><a data-toggle="tab" href="#history">History</a></li>
									</ul>
									<div class="tab-content">
										<div id="logdetails" class="tab-pane fade in active">
											
										</div>
										<!------------------------------------------------------>
										<div id="logdetails1" class="tab-pane fade">
										
										</div>
										<!------------------------------------------------------>
										<div id="opp_details1" class="tab-pane fade">
											  
										</div>
										<div id="history" class="tab-pane fade">
											
										</div>
									</div>
								</div>
								<div class="row none" id="custom_head_view">
                                    <div class="col-md-12 lead_address">
                                        <center><b>Custom Fields</b></center>
                                    </div>
                                </div>
								<div class="row" id="custom_fields_view">
                                    
                                </div>
							<div class="row" id="opportunity">
							</div>					
                          <div class="modal-footer">
							<!--<span onclick="lead_history()" class="btn pull-left"  title="Click to view Audit Trail">Audit Trail</span>-->
							<button  type="button" class="btn btn-default" onclick="cancel1()" >Cancel</button>
                          </div>
                        </div>
                      </form>
                    </div>
                </div>
            </div>
			<div id="lead_hist" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<span class="close" onclick="hide_lead_histry()">&times;</span>
							<h4 class="modal-title">Audit Report</h4>
						</div>
						<div class="modal-body">
							
							<ul class="nav nav-tabs nav-justified">
								<li class="active" id="scheduled_activity_li"><a data-toggle="tab" href="#logLess"><h4>History Summary</h4></a></li>
								<li ><a data-toggle="tab" href="#logDetails"><h4>Details</h4></a></li>
							</ul>
							<div class="tab-content">
								<div id="logLess" class="tab-pane fade in active">
									<table class="table">
										<!--<thead>
											<tr>
												<th class="table_header">lead History</th>
											</tr>
										</thead>-->  
										<tbody id="history_min">				
										</tbody>    
									</table>
								</div>
								<!------------------------------------------------------>
								<div id="logDetails" class="tab-pane fade">
									<table id="history_detail" class="table">
										
									</table>
								</div>
								
							</div>
						</div>
					
						<div class="modal-footer">
						   <input type="button" class="btn" value="Cancel" onclick="hide_lead_histry()">                           
						</div>
					</div>
				</div>
			</div>
			  <div id="modalpurchase" class="modal fade" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form id="addpopup" class="form">
                                <div class="modal-header">
                                        <span class="close"  onclick="close_modal1()">&times;</span>
                                        <h4 class="modal-title">Add Purchase Information</h4>
                                </div>
                               <div class="modal-body">
                            	<div class="row">
                            		<div class="col-md-2">
                                        <label for="">Opportunity Owner*</label>
                                    </div>
                                    <div class="col-md-4">
                                    	<select  class="form-control" id="pro_owner" onchange="pro()" name="pro_owner" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="add_product"> Product*</label> 
                                    </div>
                                    <div class="col-md-4" id="add_product" name="edit_product1">                                       
                                       
                                    </div>
									<div id="product_err" align="right">
										 <span class="error-alert" style="margin-right: 158px;"></span>
									</div>
                                </div>
                                 <div class="row fetch_btn none">
                                	<div align="right"><input type="button" class="btn" value="Fetch Currency" onclick="fetch_btn()"></div>
                                </div>
								<div class="row">									
									<div class="col-md-2">
                                        <label for="">Reference Number</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="ref_number" style="display:block!important"/>
                                        <span class="error-alert"></span>
                                    </div>
									<div class="col-md-2">
                                        <label for="">Currency*</label>
                                    </div>
                                    <div class="col-md-4">
                                          <select  class="form-control" id="product_Currency" name="product_Currency" onchange="change_val()" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
								<div class="row">									
									<div class="col-md-2">
                                        <label for="">Document</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="file" class="form-control" id="purchase_doc" style="display:block!important"/>
                                        <span class="error-alert"></span>
                                    </div>									
                                </div>
								<div id="selcd_pro">
									
								</div>
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="addProductPurchase()" value="Save">
									<!--<input type="button" class="btn" onclick="cancel()" value="Cancel" >-->
									<input type="button" class="btn" onclick="close_modal1()" value="Cancel">
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="editmodalpurchase" class="modal fade" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form id="addpopup" class="form">
                                <div class="modal-header">
                                        <span class="close"  onclick="close_purchase()">&times;</span>
                                        <h4 class="modal-title">Add Purchase Information</h4>
                                </div>
                    	              <div class="modal-body">
                            	<div class="row">
                            		<div class="col-md-2">
                                        <label for="">Opportunity Owner</label>
                                    </div>
                                    <div class="col-md-4">
                                    	<label id="e_opp_owner"></label>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Currency</label>
                                    </div>
                                    <div class="col-md-4">
                                          <label id="e_currency"></label>
										  <input type="hidden" id="e_currency1" />
										  <input type="hidden" id="e_id"/>
                                    </div>
                                </div>
								<div class="row">
									<div class="col-md-2">
                                        <label for="">Reference Number</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="e_ref_number" style="display:block!important"/>
                                        <span class="error-alert"></span>
                                    </div>
									<div class="col-md-2">
                                        <label for="">Document</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="file" class="form-control" id="e_purchase_doc" style="display:block!important"/>
                                        <span class="error-alert"></span>
                                    </div>																		
                                </div>
                               <div id="selcd_pro1">
									
								</div>
							</div>	
							<div class="modal-footer">
									<input type="button" class="btn" onclick="save_purchaseEdit()" value="Save">
									<!--<input type="button" class="btn" onclick="cancel()" value="Cancel" >-->
									<input type="button" class="btn" onclick="close_purchase()" value="Cancel">
							</div>
						</form>
					</div>
				</div>
			</div>
			    <div id="modalstart1" class="modal fade" data-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addpopup" class="form">
                                <div class="modal-header">
                                        <span class="close"  onclick="close_modal()">&times;</span>
                                        <h4 class="modal-title">Customer Assignment</h4>
                                </div>
                            <div class="modal-body">

                                <div class="row targetrow ">
										<div class="col-md-2">
											<label for="mgrlist">Manager list</label> 
										</div>
										<div class="col-md-10">											
											<div id="mgrlist" class="multiselect2" >
												<ul>
												</ul>
											</div>
											<span class="error-alert"></span>
										</div>
										<!-- <div class="col-md-2">
											<label for="replist">Replist</label> 
										</div>
										<div class="col-md-4">			
											<div id="replist" class="multiselect1">
												<ul>
												</ul>													
											</div>
											<span class="error-alert"></span>
										</div>	 -->					
								</div>
								<input type="checkbox" name="select_all_mgr" onclick="checkAllMgrs(this)"> Select All                                  	
                               <!--  <div class="row targetrow ">
															                                      
	                           	</div> -->
	                           		
                                    <!-- <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <label for="product">End Date</label> 
                                            </div>
                                            <div class="col-md-4">
                                                <div class='input-group date' id='startDateTimePicker'>
                                                    <input id="start_date" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
                                                    <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            //   <input type="checkbox" id="test" name="test" class="checkbox_check" checked/><p>Send E-mail notification</p> 
                                                <span class="error-alert"></span>
                                            </div>
                                        </div>
                                    </div> -->
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="assign_save()" value="ReAssign">
									<!--<input type="button" class="btn" onclick="cancel()" value="Cancel" >-->
									<input type="button" class="btn" onclick="close_modal()" value="Cancel">
							</div>
						</form>
					</div>
				</div>
			</div>          
        </div>
        <?php require 'footer.php' ?>

    </body>
</html>
