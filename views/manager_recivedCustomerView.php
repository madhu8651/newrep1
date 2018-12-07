<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'scriptfiles.php' ?>
<script src="/js/prefixfree.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPquJYJq7KSiQPchdgioEVs-xOY4ERUdE&libraries=places" async defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<style>
audio{
	width: 199px;
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
#files{  display:block;
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
	#tablebody .tooltip.bottom .tooltip-arrow{
		color:black;
	}
	#tablebody .tooltip.bottom .tooltip-inner{
		background:black;
		color:white;
		text-align:left;
	}
</style>
<script>
$(document).ready(function(){
    $('#files').change(handleFile);
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
	function edit_tree(data, container){
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
			/*$(this).closest("label").find('input[type=radio]').remove();*/
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
	})
	/* hide/show child node on click of plus/minus */
	$("#"+container+"  label.glyphicon").each(function(){
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
$(document).on('focus',".end_date", function(){
    $(this).datepicker({
		changeMonth: true, 
		changeYear: true, 
		dateFormat: "dd/mm/yy",
		yearRange: "-90:+00"
	});
	
});
function assignedLoad(){
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('manager_customerController/getRecivedCustomerDetails');?>",
		dataType:'json',
		success: function(data) {
				loaderHide();
				if(error_handler(data)){
                    return;
                }
				$('#tablebody').parent("table").dataTable().fnDestroy();
				if(data.length > 0){
					$('#accept_newCustomer').removeClass('hidden');
					$('#reject_btn1').removeClass('hidden');
				}else{
					$('#accept_newCustomer').addClass('hidden');
					$('#reject_btn1').addClass('hidden');
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
				 row += "<tr><td>" + "<input type='checkbox' name='foo[]' id='"+data[i].customer_id+"' class='assign_class'/>" + "</td><td>" + (i+1) + "</td><td>" + data[i].customer_name +"</td><td>" + data[i].contact_name +"</td><td>" + data[i].contact_desg+ "</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>" + data[i].customer_number +"</td><td>" + data[i].customer_email +"</td><td>" + data[i].customer_city +"</td><td>" + status +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td></td></tr>";			
			}					
			$('#tablebody').parent("table").removeClass('hidden'); 
			$('#tablebody').empty();   
			$('#tablebody').append(row);
			$('#tablebody').parent("table").DataTable({
                "aoColumnDefs": [{ "bSortable": false, "aTargets": [9,10] }]
               });
		}
	});
}
/*-----------------customer log------------------------*/
function leadlog(){
	  var customer={};
	  customer.customerId=$("#customer_id").val();
	 $.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_customerController/getCustomerLogDetails');?>",
			data : JSON.stringify(customer),
			dataType:'json',
			success: function(data) {
				console.log(data)
				$("#logdetails").addClass("active").addClass("in");
				$("#logdetails_cust").addClass("active");
				$('#logdetails').empty();
				if(error_handler(data)){
                    return;
                }
				if(data.length > 0){
				var tolltip = "";
					
					var row = "";
					row += '<div class="row">';
					row += '<table class="table"><thead><tr>';
					row += '<th>SL No</th><th>Activity Owner</th><th>Date-time</th><th>Activity</th><th>Ratings</th><th>Duration</th><th>Remarks</th><th>Status</th><th>Call Recorded</th>';					
					row += '</tr></thead><tbody>';
					for(i=0; i < data.length; i++ ){
						if (data[i].note.length > 20){
							tolltip = "rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='left'  data-title='"+data[i].note+"'";
							data[i].note = data[i].note.substring(0,20) + ' ...';
						}
						var start=data[i].start;
						var end=data[i].end;
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
						row += "<tr><td>" + (i+1) + "</td>"+
							"<td>" + data[i].rep_name +"</td>"+
							"<td>" + data[i].starttime +"</td>"+
							"<td>" + data[i].activity + "</td>"+
							"<td id='ff'>" + rating + "</td>"+
							"<td>" + duration +"</td>"+
							"<td class='tt'><span "+ tolltip +">" + data[i].note +"</span></td>"+
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
/*----------------------------------------------------------Customer log Ends---------------------------------------------------------------*/
/*----------------------------------------------------------Customer History Starts---------------------------------------------------------------*/
function hist_log(){
	  var customer={};
	  customer.customer_id=$("#customer_id").val();
	 $.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_customerController/oppDetailsCustomer');?>",
			data : JSON.stringify(customer),
			dataType:'json',
			success: function(data) {
				console.log(data)
				$('#history').empty();
				if(error_handler(data)){
                    return;
                }
				if(data.length > 0){
					var tolltip = "";
					var row = "";
					var url_path = "<?php echo base_url(); ?>uploads/";
					row += '<div class="row">';
					row += '<table class="table"><thead><tr>';
					row += '<th>SL No</th><th>Customer Name</th><th>Date-time</th><th>Activity</th><th>Ratings</th><th>Duration</th><th>Remarks</th><th>Call Recorded</th>';
					row += '</tr></thead><tbody>';
					for(i=0; i < data.length; i++ ){ 
						if (data[i].note.length > 20){
							tolltip = "rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='left'  data-title='"+data[i].note+"'";
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
						row += "<tr><td>" + (i+1) + "</td>"+
							"<td>" + data[i].customer_name +"</td>"+
							"<td>" + data[i].starttime +"</td>"+
							"<td>" + data[i].activity + "</td>"+
							"<td id='ff'>" + rating + "</td>"+
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
/*----------------------------------------------------------Customer History Ends---------------------------------------------------------------*/
	/*------------------------------Scheduled log---------------------------------------*/
	function schedule_fetch(){
		var customer={};
		customer.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	  	customer.customerId=$("#customer_id").val();
	  	
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_customerController/getScheduleTask'); ?>",
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
			url: "<?php echo site_url('manager_customerController/getCustomerOppDetails');?>",
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
						var url = "<?php echo site_url("manager_opportunitiesController/stage_view/")?>"+data[i].opportunity_id;						 
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

function cancelCust(){
	$('.modal').modal('hide');
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
	$(".contact_type1").remove(); 
	$(".proDetail").remove(); 
}
function add_cancel(){
	$('.modal').modal('hide');
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
	$('#select_map').hide();
	$('#map1').show();
}
function close_modal(){
$('#modalstart1').modal('hide');	
$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
$("#modal_upload").modal("hide");
$('#modal_upload #files').val("");
} 
function close_modal1(){
	$('.modal').modal('hide');
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
	$('#files').val("");
	$(".contact_type1").remove(); 
	$(".proDetail").remove(); 
}
function cancel1(){
	$('.prod_purchase_info').empty();	
	$(".prod_purchase_info").css("height", 0);
	$(".prod_purchase_info").css("margin-bottom", 0);
	$(".prod_purchase_info").css("overflow", "none");
	$('.modal').modal('hide');
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
}

function viewrow(obj){
var obj1 = {};
obj1.customerid=obj.customer_id;
$("#customer_id").val(obj.customer_id);
leadlog();
$("#activity_tab_view .tab-pane.fade").each(function(){
	$(this).removeClass("active").removeClass("in");
});
$("#activity_tab_view .nav-tabs li").each(function(){
	$(this).removeClass("active");
});

	$("#label_leadmail, #label_leadphone").html("");
	
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
	
contact_prsn_list(obj.customer_id , "contact_prsn_list", "customer", "manager");
obj.customer_remarks = window.atob(obj.customer_remarks);
obj.customer_address = window.atob(obj.customer_address);
$("#view_leadname").val(obj.customer_name);
$('#view_customer').text(obj.customer_name);
$("#label_product").html(obj.product);
$("#label_leadsource").html(obj.leadsource);
$("#view_ofcadd").html(obj.customer_address);
$("#view_splcomments").html(obj.customer_remarks);
$("#view_long").val(obj.leadlng);
$("#view_latt").val(obj.leadlat);              
$("#customer_id").val(obj.customer_id);
$.ajax({
	type: "POST",
	url: "<?php echo site_url('manager_customerController/customFieldCustomer');?>",
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
	url: "<?php echo site_url('manager_leadController/get_plugin_data');?>",
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
            url: "<?php echo site_url('manager_customerController/getProductPurchaseInfo'); ?>",
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


function reject_btn(section){
	/* from the lending page */
	if( section== "multiple"){
		if(!($("#tablebody tr input[type=checkbox]").is(':checked'))){
			alert("Please select atleast one lead for reject.");
			return;		
		}else{
			$("#reject_save_section").val("").val("multiple");
			$("#modalstart1").modal("show");
		}
	}
	/* from the view info popup section */
	if( section== "single"){
		$("#reject_save_section").val("").val("single");
		$("#modalstart1").modal("show");			
	}
	
}

/* from the lending page */
function assign_btn(){
	var check = [];
	if(!($("#tablebody tr input[type=checkbox]").is(':checked'))){
		alert("Please select atleast one Customer for accept.");
		return;
	}
	
    $("#tablebody tr input:checkbox:checked").each(function () {
        check.push($(this).attr('id'));
    });	

    if(check.length>0){    	
    	assign_date(check.join(":"), "");	
    } 	
}

/* from the view info popup section */
function assign_btn2() {
	var id=$("#logg").val();	
	assign_date(id,"");
}

function assign_save(){
	var check = [];
	var notes = $.trim($("#notes").val());
	
	/* from the lending page */
	if( $("#reject_save_section").val() == "multiple"){
		$("#tablebody tr input:checkbox:checked").each(function () {
			check.push($(this).attr('id'));
		});
		if($.trim($("#notes").val()) == ""){
			$("#notes").closest("div").find(".error-alert").text("Remarks is required.");
			return;
		}else{
			$("#notes").closest("div").find(".error-alert").text("");
		}
		if(check.length>0){   	
			assign_date(check.join(":"), notes);	
		}
	}

/* from the view info popup section */
	if( $("#reject_save_section").val() == "single"){
		if($.trim($("#notes").val()) == ""){
			$("#notes").closest("div").find(".error-alert").text("Remarks is required.");
			return;
		}else{
			$("#notes").closest("div").find(".error-alert").text("");
		}
		var id=$("#logg").val(); 	
		assign_date(id, notes);	
		
	}
		
    
}


function assign_date(idval,note){
	var arr = {};
	arr.lid = idval;
	loaderShow();
	if(note == ""){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_customerController/customerAccept'); ?>",
			data:JSON.stringify(arr),
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
					return;
				}
				$('.form-control').val("");
				var totalLeadCount = idval.split(':').length;
				var failureLeadCount = data.length;
				//var successLeadCount = totalLeadCount - failureLeadCount;					
				var finalStr = failureLeadCount + " of " + totalLeadCount + " Customer(s) accepted";
				alert(finalStr);
				assignedLoad();
			}
		});
	}else{
		arr.note = note;
		$.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_customerController/customerReject'); ?>",
            data:JSON.stringify(arr),
            dataType:'json',
            success: function(data) {
				if(error_handler(data)){
					return;
				}
          		if(data == true){         		
					close_modal();
					assignedLoad();          		
          		}           
            }
    	});
	}
    
}
function hide_lead_histry(){
	$('#lead_hist').modal('hide');
	$('html').addClass('modal-open');
	
}
function lead_history(){
    $('#lead_hist').modal('show');
    var cust_histid={};
    cust_histid.id=$('#customer_id').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('manager_customerController/get_customerhistory'); ?>",
        data : JSON.stringify(cust_histid),
        dataType:'json',
        success: function(data) {
            if(error_handler(data)){
                    return;
            }
            var history=data.history;
            if(history){		
				$('#history_min').empty();
				var mapping_ids = [];
				for (var i = 0; i < history.length; i++){
					if (mapping_ids.indexOf(history[i].mapping_id) < 0){
						mapping_ids.push(history[i].mapping_id);		
						var action = history[i].action;
						var from_name = history[i].from_user_name;
						var to_name = history[i].to_user_name;
						var lead_cust_name = history[i].lead_cust_name;				
						var remarks = history[i].remarks;
						var timestamp = moment(history[i].timestamp).format('LL') +" at "+ moment(history[i].timestamp).format('LT'); 
						var rowhtml = '';
						if (action == 'created') {

								rowhtml += '<div class="created"><div><b><h3 style="display:inline;">'+capitalizeFirstLetter(action)+'</h3></b>by <u><b>' + from_name + '</b></u> (<i>' + capitalizeFirstLetter(data.history[i].module)+ '</i>)  for '+ lead_cust_name +'</div>' ;
								rowhtml += 'on <h5 style="display:inline;color:#777777">' + timestamp + '</h5></div>';
						} 
						else if (action == 'accepted'){
								rowhtml += '<div class="created"><div><b><h3 style="display:inline;">'+capitalizeFirstLetter(action)+'</h3></b>by <u><b>' + to_name + '</b></u> (<i>' + capitalizeFirstLetter(data.history[i].module) + '</i>)  for '+ lead_cust_name +'</div>';
								rowhtml += 'on <h5 style="display:inline;color:#777777">' + timestamp + '</h5></div>';
						} 
						else if ((action == 'assigned') || (action == 'reassigned')){
										/* get count of this mapping ID in array. */
										assigned_to = 0;
										assigned_to_names = [];
										for(var c = 0; c < history.length; c++)	{
												if (history[c].mapping_id == history[i].mapping_id) {
												assigned_to++;
												assigned_to_names.push(history[c].to_user_name);
												}
										}

								if(assigned_to > 1)	{
										to_name = assigned_to + " users";
								}
								rowhtml = '<div class="assigned"> <div><b><h3 style="display:inline;">'+capitalizeFirstLetter(action)+'</h3></b>to <u><b>'+ to_name + '</b></u> (<i>' + capitalizeFirstLetter(data.history[i].module) + '</i>) </div>';
								rowhtml += 'on <h5 style="display:inline;color:#777777">' + timestamp + '</h5></div>';

						}
						else if (action == 'added remarks')	{
								rowhtml = '<div class="remarks"><div><b><h3 style="display:inline;">'+capitalizeFirstLetter(action)+'</h3></b>by <u><b>' + from_name + '</b></u> (<i>' + capitalizeFirstLetter(data.history[i].module) + '</i>) </div>';
								rowhtml += 'on <h5 style="display:inline;color:#777777">' + timestamp + '</h5></div>';
						} 							
						row =   '<tr><td>'+ rowhtml + '</td></tr>';
						$('#history_min').append(row);	
					}								
				}
			}	
			/* Log Details................ */
			var historyDetail = data.history_detail;
			var detail="";
			$('#history_detail').empty();
			detail += '<thead><tr><th>Action</th><th>Performed By</th><th>In module</th><th>Assigned To</th><th>In module</th><th>Date-Time</th><th>Remarks</th></tr></thead>'
			if(historyDetail.length > 0){
				detail += '<tbody>'
				for(j =0; j< historyDetail.length; j++){
					
					var action = historyDetail[j].action;
					var from_name = historyDetail[j].from_user_name;
					var to_name = historyDetail[j].to_user_name;
					var remarks = historyDetail[j].remarks;
					var tolltip = "";
					if(remarks != null){
						if (remarks.length > 20){
							remarks = remarks.substring(0,20) + ' ...';
							tolltip = "rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='left'  data-title='"+historyDetail[j].remarks+"'";
						}
					}else{
						remarks = "";
					}
													
					var lead_cust_name = historyDetail[j].lead_cust_name;				
					var timestamp = moment(historyDetail[j].timestamp).format('LL') +" at "+ moment(historyDetail[j].timestamp).format('LT');
					
					
					if(historyDetail[j]. module == null){
						module = ""
					}else{
						module = historyDetail[j]. module ;
					}
					
					
					if (action == 'created') {
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td> - </td><td> - </td><td>'+timestamp+'</td><td  class="no_opacity_tooltip" ><p '+tolltip+'>'+remarks +'</p></td></tr>';
					}
					else if ((action == 'assigned') || (action == 'reassigned')){
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td> manager </td><td>'+to_name+'</td><td>'+module+'</td><td>'+timestamp+'</td><td  class="no_opacity_tooltip"><p '+tolltip+'>'+remarks +'</p></td></tr>';
					}
					else if (action == 'rejected'){
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td>'+to_name+'</td><td> manager </td><td>'+timestamp+'</td><td><p '+tolltip+'>'+remarks +'</p></td></tr>';
					} 
					else if (action == 'edited'){
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td> - </td><td> - </td><td>'+timestamp+'</td><td  class="no_opacity_tooltip"><p '+tolltip+'>'+remarks +'</p></td></tr>';
					} 
					else if (action == 'closed'){
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td> - </td><td> - </td><td>'+timestamp+'</td><td  class="no_opacity_tooltip"><p '+tolltip+'>'+remarks +'</p></td></tr>';
					} 
					else if (action == "in progress"){
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td> - </td><td> - </td><td>'+timestamp+'</td><td  class="no_opacity_tooltip"><p '+tolltip+'>'+remarks +'</p></td></tr>';
					} 
					else if (action == "rejected"){
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td> - </td><td> - </td><td>'+timestamp+'</td><td  class="no_opacity_tooltip"><p '+tolltip+'>'+remarks +'</p></td></tr>';
					} 
					else if (action == "reopened"){
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td> - </td><td> - </td><td>'+timestamp+'</td><td  class="no_opacity_tooltip"><p '+tolltip+'>'+remarks +'</p></td></tr>';
					} 
					
				}
				
				detail += '</tbody>';
				$('#history_detail').append(detail);
			}
					
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
        <?php require 'manager_sidenav.php' ?>
        <div class="content-wrapper body-content">
            <div class="col-lg-12 column">
				<div class="row header1">				
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >	
								<img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" data-html="true" title="These are all the customers that have been assigned to you. Once accepted, they will move into ‘Unassigned’ customers. A bulk upload of the past customers can be done through the <img src='<?php echo site_url(); ?>images/new/Xl_Off.png' width='20px' height='20px' /> on the top right of this page."/>
							</div>
						</span>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Received Customers</h2>	
					</div>
					 <div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
					   <div class="addBtns">
							<a  class="addExcel" onclick="addExl()" >
							<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/></a>
						</div>
							<div style="clear:both"></div>
					</div>
					
				</div>
				<div class="table-responsive">
					<table class="table hidden" id="tableTeam">
						<thead>  
						<tr>
							<th class="table_header"></th>	
							<th class="table_header">Sl No</th>
							<th class="table_header">Name</th>
							<th class="table_header">Contact Person</th>
							<th class="table_header"> Designation</th>
							<th class="table_header"> Products</th>
							<th class="table_header">Phone</th>
							<th class="table_header">Email</th>
							<th class="table_header">Location</th>	
							<th class="table_header">Customer Status</th>
							<th class="table_header"></th>
							<th class="table_header"></th>
						</tr>
						</thead>  
						<tbody id="tablebody">
						</tbody>    
					</table>
					<div align="center">
					<input type="hidden" id="from_user_id" name="from_user_id" />
					<input type="button" class="btn hidden" id="accept_newCustomer" onclick="assign_btn()" value="Accept"/>
					<input type="button" class="btn hidden" id="reject_btn1" onclick="reject_btn('multiple')" value="Decline"/>
				</div>
				</div>
            </div>
            <?php require 'manager_customerEdit_module.php' ?> 
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
										<pre class="pre" id="view_ofcadd"></pre> 
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<pre class="pre" id="view_splcomments"></pre> 
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
								<div class="row" id="activity_tab_view">
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
							<span onclick="lead_history()" class="btn pull-left"  title="Click to view Audit Trail">Audit Trail</span>
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
         <div id="modalstart1" class="modal fade" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addpopup" class="form">
								<input type="hidden" id="reject_save_section"/>
                                <div class="modal-header">
                                        <span class="close"  onclick="close_modal()">&times;</span>
                                        <h4 class="modal-title">Please enter the remarks</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
										<div class="col-md-2">
											<label for="notes">Remark*</label>
										</div>
										<div class="col-md-10">
											<textarea id="notes" class="form-control"></textarea>											
											<span class="error-alert"></span>
										</div>
									</div>                                    
								</div>
								<div class="modal-footer">
									 <input type="button" class="btn" onclick="assign_save()" value="Save">
									<input type="button" class="btn" onclick="close_modal()" value="Cancel">
								</div>
							</form>
						</div>
					</div>
				</div> 
            <?php require 'manager_customerFile_module.php' ?> 
        </div>
        <?php require 'footer.php' ?>

    </body>
</html>
