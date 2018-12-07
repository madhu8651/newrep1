
<!DOCTYPE html>
<html lang="en">
	<head>
	
	<?php require 'scriptfiles.php' ?>
	<style>
	.modal-backdrop{
		z-index:-1;
	}
	.error-alert{
		color:red;
	}
	.header1{
		background:rgb(30, 40, 44);
		padding:2px;
	}
	.pageHeader1{
		text-align:center;		
		color:white;
		height:41px;
		font-size:22px;
	}
	.pageHeader1 h2{
		margin-bottom: 0;
		margin-top: 0;
	}
	.column{
		padding:0;
	}
	.addExcel{
		bottom: 0;
	}
	.addPlus{
		   bottom: 0;
	}
	.table.table{
		margin-top:0;
	}
	
	.table thead tr th{
		text-align:center;
		border-right: 1px solid white;
	}
	.table tr th{
		text-align:center;
	}		
	.content-wrapper.body-content section.row{
		height:46px;
	}
	.aa{		
		float: left;
		position: relative;
		height: 41px;
		line-height: 35px;
	}
	.info-icon div{
		margin-left: 14px;
	}
	.sidebar{
			margin-top: 0px;
	}
	@media only screen and (min-device-width: 320px) and (max-device-width: 480px){
		   .aa h2{			
			font-size:16px;
			padding-left: -10px;
			margin-top: 9px;
		}
		.aa{
				margin-top: 62px;
				font-size:18px;
			}
		.addBtns{
			margin-right: -19px;
		}
		.aa{
			margin-left: 0px;
			font-size:16px;
			padding:0;
		}
		.sidebar{
				margin-top: 56px;
		}
		form{
			overflow:auto;
		}
		.inputTxt{
			margin-right: -10px;
		}
		.txtSelect{
			margin-left: -8px;
		}
    }
	@media only screen and (min-device-width: 768px) and (max-device-width: 1024px){
		  .aa h2{			
			font-size:22px;	
			margin-top: 6px;
		}		
    }
	#showexpenses1 .txtLabel{
		width:15%!important;
	}
	#showexpenses1 .txtInput{
		width:30%!important;
	}
	.txtLabel{width:20%!important;}
	.txtInput{width:30%!important;}
	.txtSelect{width:68%!important;}
	.inputTxt{width: 30%;float: right;display: inline-block;margin-top: -40px;}
	.viewfrom{
		padding: 5px 18px 5px 18px;
	}
	.active1{ color: #fff !important;}
	   input[type="file"] {
		display: none;
	}
	.custom-file-upload {
		border: 1px solid #ccc;
		display: inline-block;
		padding: 6px 12px;
		cursor: pointer;
	}
	.textarea{
		height:90px!important;
	}
	.table tr th{
		    height: 30px;
	}
	.inputsel{
		width: 50%;
		margin-right: 80px;
	}
	.inputtext{
		width: 48%;
		margin-top: -40px;
		margin-left: 161px;		
	}
	.tbwidth{
		width:16.66%;
	}
	.txtLabel1{
		    padding: 12px;
			width:20%;
	}
	.text_c{
		text-align: center;
	}
        .text_r{
		text-align: right;
	}
	#salerep_Product{
		height: 66px;
	}
	.multiselect.disable{
		background-color: #eee;
		opacity: 1;
	}
	.multiselect{
		height: 60px;
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
			text-align: left;
	}
	.multiselect1{
		height: 60px;
		overflow: auto;
		border: 1px solid #ccc;
		border-radius: 5px;
	}
	.multiselect1 ul{
			padding: 0px;
	}
	.multiselect1 ul li.sel{
			background: #ccc;
	}
	.multiselect1 ul li{
			padding: 0 10px;
	}
	.multiselect2,.multiselect3,.multiselect_pro{
		height: 75px;
		overflow: auto;
		border: 1px solid #ccc;
		border-radius: 5px;
	}
	.multiselect2 ul,.multiselect3 ul,.multiselect_pro ul{
			padding: 0px;
	}
	.multiselect2 ul li.sel,.multiselect3 ul li.sel,.multiselect_pro ul li.sel{
			background: #ccc;
	}
	.multiselect2 ul li,.multiselect3 ul li,.multiselect_pro ul li{
			padding: 0 10px;
	}
	.targetrow2 {
		margin-top: 6px;
	}
	.target_up {
		margin-top: 6px;
	}
	.targetrow1{
		margin-top: 6px;
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
	.man_S_margin{
		margin-left:10px;
	}
	.pro_M_width{
		width:15%;
	}
	.pro_M_margin{
		margin-left: -37px;
	}
	.row_rep{
		margin: 8px!important;
		border-top: 1px solid #f4f4f4;
		line-height: 1.42857143;
		vertical-align: top;
		border-collapse: collapse;
		padding: 5px;
	}
	.row_heading{
		background: #808080;
		color: #fff;
		padding: 5px;
		font-weight:bold;
		text-align: center;
	}
	.sec_edit{
		color:white;
	}
	.edit_product .glyphicon {
		margin-top: 2px;
		font-size: small;
		border: 2px solid;
		padding: 5px;
		margin-right: 4px;
		border-radius: 4px;
	}
	.row_heading .glyphicon {
		margin-top: -2px;
		font-size: small;
		border: 2px solid;
		padding: 5px;
		margin-right: 4px;
		border-radius: 4px;
		color:white;
	}
	#currency_value .col-md-5 {
			border: 1px solid #ccc;
			margin-bottom: 8px;
			border-radius: 5px;
			margin-right: -12px;
			margin-left: 55px;
			padding: 0px;
		}
		#currency_value .col-md-6(even){
			margin-right:5px;
		}#currency_value_list .col-md-5 {
			border: 1px solid #ccc;
			margin-bottom: 8px;
			border-radius: 5px;
			margin-right: -12px;
			margin-left: 55px;
			padding: 0px;
		}
		#currency_value_list .col-md-6(even){
			margin-right:5px;
		}#currency_value_list_pro .col-md-5 {
			border: 1px solid #ccc;
			margin-bottom: 8px;
			border-radius: 5px;
			margin-right: -12px;
			margin-left: 55px;
			padding: 0px;
		}
		#currency_value_list_pro .col-md-6(even){
			margin-right:5px;
		}
		.without-curr{
			padding-left: 0px;
			height: 112px;
			overflow: auto;
		}
		.highlight{
			color:#B5000A;
			font-weight: bold !important;
			width: 100%;
			background: #eee;
		}
		.highlight.error{
			color:#FFF;
			background:red;
		}
		.pro_cur_user{
			height:212px;
			overflow-y:auto;
		}
		#add_product{
			margin-left: 73px;
			margin-bottom: 3px;
		}
		.pro_add col-md-2{
			    margin-bottom: 3px;
		}
		.error_pro{
			text-align: center;
		}
		#error{
			color:red;
			font-weight:bold;
		}
		.label_head{
			font-weight:bold!important;
		}
		.prod_leaf_node{
			margin-left: 6px;
		}
		.prod_leaf_node1{
			margin-left: 4px;
		}
	</style>
	<script>
	
	var team = [],pro=[],cur=[];
	var team1 = [],pro1=[],cur1=[], data_get ="";
	function number(name) {
		var nameReg = new RegExp(/^[0-9,]*$/);
		var valid = nameReg.test(name);
		if (!valid) {
			return false;
		} else {
			return true;
		}
	}
	function changeCase(val, id){
		if(!number($.trim($("#"+id).val()))) {
			$("#"+id).closest("div").find("span").text("Only numbers are allowed");
			$("#"+id).focus();	
			return;
		}else{
			$("#"+id).closest("div").find("span").text("");
		}
		if(val != ''){
			val = val.split(",").join("");
			var result = (Math.floor(val / 1)).toLocaleString();
			$("#"+id).val(result);
		}
	}
    function save_rep(){
		var addObj={};
		addObj.Rooster=$("#Rooster").val();
		addObj.salerep_target=$("#salerep_target").val();
		addObj.callCurrency=$("#callCurrency").val();
		addObj.callCost=$("#callCost").val();
		addObj.smsCurrency=$("#smsCurrency").val();
		addObj.smsCost=$("#smsCost").val();
		addObj.manager_id=$("#salerep_id").val();
		addObj.productArray = [];
		$("#attr_value1 input[type=checkbox]").each(function(){
			if($(this).prop('checked')== true){
				addObj.productArray.push($(this).val());
			}
		});
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_teamManagersController/save_manager_data');?>", 
			data:JSON.stringify(addObj),
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
                    return;
                }
				location.reload();
				}
			});
		console.log(addObj);
    }
    function error_handler(data){
        if(data.hasOwnProperty("errorCode")){
                    alert(data.errorCode+"  "+data.errorMsg);
                    return true;
        }
        return false;
    }
	function cancel(){
		$('.modal input[type="text"], textarea, select').val('');
		$(".error-alert").text("");
		$('.modal').modal('hide');
		$('#salerep_target').val('');
		$('#targetPeriod').val('');
		$('.targetrow3').hide();
		$('.targetrow2').hide();
		$('.targetrow1').hide();
		$('.cons_spli').hide(); 
		$('.new_sale1').hide();
		$('.cross_sale1').hide();
		$('.up_sale1').hide();
		$('.renewal').hide();
		$(".dates").hide();
		$(".targetCurrency").hide();
		$("#endDateTimePicker").data("DateTimePicker").clear();
	}
	function save_target(){
		if($.trim($("#targetProduct").val())==""){
			$("#targetProduct").closest("div").find("span").text("Product is required");
			$("#targetProduct").focus();				
			return;
		}else{
			$("#targetProduct").closest("div").find("span").text("");
		}
		if($.trim($("#salerep_target").val())==""){
			$("#salerep_target").closest("div").find("span").text("Target type is required.");
			$("#salerep_target").focus();				
			return;
		}else{
			$("#salerep_target").closest("div").find("span").text("");
		}
		if($.trim($("#targetPeriod").val())==""){
			$("#targetPeriod").closest("div").find("span").text("Period is required.");
			$("#targetPeriod").focus();				
			return;
		}else{
			$("#targetPeriod").closest("div").find("span").text("");
		}
		if($.trim($("#salerep_target").val()) == "revenue"){
			if($.trim($("#targetCurrency").val())==""){
				$("#targetCurrency").closest("div").find("span").text("Currency is required");
				return;
			}
			else{
				$("#targetCurrency").closest("div").find("span").text("");
			}
		}
		var startDate = '', endDate='';
		if($.trim($("#targetPeriod").val()) == "Custom"){
			if($.trim($("#targetStartDate1").val())==""){
				$(".targetSDate").find("span").text("Date is required.");		
				return;
			}
			else{
				$(".targetSDate").closest("div").find("span").text("");
			}
			if($.trim($("#targetEndDate1").val())==""){
				$(".targetEDate").find("span").text("Date is required.");		
				return;
			}
			else{
				$(".targetEDate").closest("div").find("span").text("");
			}
		}else{
			if($.trim($("#targetStartDate1").val())==""){
				$(".targetSDate").find("span").text("Date is required.");			
				return;
			}
			else{
				$(".targetSDate").closest("div").find("span").text("");
			}
		}
		if($.trim($("#targetPeriod").val()) == "Custom"){
			startDate = $.trim($("#targetStartDate1").val());
			endDate = $.trim($("#targetEndDate1").val());
		}else{
			startDate = $.trim($("#targetStartDate1").val());
			endDate = '';
		}
		if($("#consol").is(":checked")){
			if($.trim($("#targetTarget1").val())==""){
				$("#targetTarget1").closest("div").find("span").text("Target value is required.");
				$("#targetTarget1").focus();				
				return;
			}else if(!number($.trim($("#targetTarget1").val()))) {
				$("#targetTarget1").closest("div").find("span").text("Only numbers are allowed");
				$("#targetTarget1").focus();	
				return;
			}else{
				$("#targetTarget1").closest("div").find("span").text("");
			}
		}
		if($("#spilt_a").is(":checked")){
			if($(".new_sale1").css("display")=="block"){
				if($.trim($("#new_sales").val())==""){
					$("#new_sales").closest("div").find("span").text("value is required.");
					$("#new_sales").focus();				
					return;
				}else if(!number($.trim($("#new_sales").val()))) {
					$("#new_sales").closest("div").find("span").text("Only numbers are allowed");
					$("#new_sales").focus();	
					return;
				}else{
					$("#new_sales").closest("div").find("span").text("");
				}
			}

			if($(".up_sale1").css("display")=="block"){
				if($.trim($("#up_sales").val())==""){
					$("#up_sales").closest("div").find("span").text("value is required.");
					$("#up_sales").focus();				
					return;
				}else if(!number($.trim($("#up_sales").val()))) {
					$("#up_sales").closest("div").find("span").text("Only numbers are allowed");
					$("#up_sales").focus();	
					return;
				}else{
					$("#up_sales").closest("div").find("span").text("");
				}
			}

			if($(".cross_sale1").css("display")=="block"){
				if($.trim($("#cross_sales").val())==""){
					$("#cross_sales").closest("div").find("span").text("value is required.");
					$("#cross_sales").focus();				
					return;
				}else if(!number($.trim($("#cross_sales").val()))) {
					$("#cross_sales").closest("div").find("span").text("Only numbers are allowed");
					$("#cross_sales").focus();	
					return;
				}else{
					$("#cross_sales").closest("div").find("span").text("");
				}
			}

			if($(".renewal").css("display")=="block"){
				if($.trim($("#renewal").val())==""){
					$("#renewal").closest("div").find("span").text("value is required.");
					$("#renewal").focus();				
					return;
				}else if(!number($.trim($("#renewal").val()))) {
					$("#renewal").closest("div").find("span").text("Only numbers are allowed");
					$("#renewal").focus();	
					return;
				}else{
					$("#renewal").closest("div").find("span").text("");
				}
			}
		}
		var targetObj = {};
		var product_ids = [];
		product_ids.push($('#targetProduct').val());
		var period = $("#targetPeriod").val();
		var target_type = $("#salerep_target").val();
		var target_currency = $("#targetCurrency").val();
		var manager_id=$("#salerep_id").val(); 
		var checked = '';
		if($("#teamTarget").is(":checked")){
			checked = "teamTarget";
		}else{
			checked = "individualTarget";
		}
		targetObj = {
				"product_ids": product_ids,
				"period" : period,
				"target_type": target_type,
				"target_currency": target_currency,
				"manager_id":manager_id,
				"startDate":startDate,
				"endDate":endDate,
				"checked":checked
			};
		targetObj["category_details"] = {};
		if($("#spilt_a").is(":checked")){
			targetObj["category"] = 'Split';			
			var new_target_value = $("#new_sales").val();
			new_target_value = new_target_value.split(",").join("");
			var up_target_value = $("#up_sales").val();
			up_target_value = up_target_value.split(",").join("");
			var cross_target_value = $("#cross_sales").val();
			cross_target_value = cross_target_value.split(",").join("");
			var renewal = $("#renewal").val();
			renewal = renewal.split(",").join("");
			targetObj["category_details"]["New_Sell"] = {"value":new_target_value};
			targetObj["category_details"]["Up_Sell"] = {"value":up_target_value};
			targetObj["category_details"]["Cross_Sell"] = {"value":cross_target_value};
			targetObj["category_details"]["Renewal"] = {"value":renewal};
		} 
		if($("#consol").is(":checked")){
			targetObj["category"] = 'Consolidated';

			var common_target_value = $("#targetTarget1").val();
			common_target_value = common_target_value.split(",").join("");
			targetObj["category_details"]["common"] = {"value":common_target_value};
		}
		console.log(targetObj);
		loaderShow();
		 $.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_teamManagersController/saveTarget');?>", 
			data:JSON.stringify(targetObj),
			dataType : 'json',
			cache : false,
			success : function(data){
				loaderHide();
					if(error_handler(data)){
                    return;
                }
					//$("#target_add").modal('hide');
			   		if(data==false){
			   			alert('Target is already defined');
			   			//cancel();
						return
			   		}
			   		else{
			   			var target_row="", target_row1='', currencyName = '', dateRange = '', currencySplit = '', count=0, count1=0;
						$('#tablebody').empty();						
						$('#tablebody1').empty();						
					   for(var i=0;i<data.length; i++){	
					   		var rowdata=JSON.stringify(data[i]);
							var target1 = JSON.parse(data[i].target_data);
							if(data[i].currency_name == null){
								currencyName = '';
							}else{
								currencyName = data[i].currency_name;
							}
							if(target1.target_type == 'Numbers'){
								currencySplit = '';
							}else{
								currencySplit = currencyName.split("- ");
								currencySplit = ' ('+currencySplit[1]+')';
							}
							
							/*------------------ Month calculation ----------------------*/
							moment.addRealMonth = function addRealMonth(d , monthCount) {
							  var fm = moment(d).add(monthCount, 'M');
							  var fmEnd = moment(fm).endOf('month');
							  return d.date() != fm.date() && fm.isSame(fmEnd.format('DD-MM-YYYY')) ? fm.add(1, 'd') : fm;  
							}
							var nextMonth = ""
							
							var period = {"Monthly":1, "Quarterly":3, "HalfYearly":6, "Yearly":12};
							
							$.each( period, function(key, val){
								if(target1.period.split(' ').join('') == key){
									nextMonth = moment.addRealMonth(moment(target1.startDate,'DD-MM-YYYY') , val);
									nextMonth =  moment(nextMonth).subtract(1, "days").format("DD-MM-YYYY");
								}	
							})
							/*----------------------------------------*/
							console.log(nextMonth)
							var date = target1.startDate +" to "+ nextMonth ;
							if(target1.period == 'Custom'){
								dateRange = target1.startDate + ' to ' + target1.endDate;
							}else{
								dateRange = date;
							}
							if(target1.checked == 'teamTarget'){
								count = count + 1;
								$("#open").show();
								$(".target_table").show();
								if(target1.category == "Split"){
									if(target1.category_details.New_Sell.value!=""){
										target_row +='<tr><td>'+(count)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + dateRange +'</td><td>' + 'New Sell' +'</td><td>' + target1.target_type + currencySplit + '</td><td>'+(Math.floor(target1.category_details.New_Sell.value / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"new_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									if(target1.category_details.Up_Sell.value!=""){
										target_row +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Up Sell' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Up_Sell.value / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"up_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>" ;
									}
									if(target1.category_details.Cross_Sell.value!=""){
										target_row +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Cross Sell' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Cross_Sell.value / 1)).toLocaleString()+ "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"cross_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									if(target1.category_details.Renewal.value!=""){
										target_row +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Cross' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Renewal.value  / 1)).toLocaleString()+ "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"cross_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									
								}
								if(target1.category == "Consolidated"){
									target_row +='<tr><td>'+(count)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + dateRange +'</td><td>' + target1.category + '</td><td>' + target1.target_type + currencySplit + '</td><td>'+(Math.floor(target1.category_details.common.value  / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
								}
							}else{
								count1 = count1 + 1;
								$("#close").show();
								$(".target_table1").show();
								if(target1.category == "Split"){
									if(target1.category_details.New_Sell.value!=""){
										target_row1 +='<tr><td>'+(count1)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + dateRange +'</td><td>' + 'New Sell' +'</td><td>' + target1.target_type + currencySplit + '</td><td>'+(Math.floor(target1.category_details.New_Sell.value / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"new_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									if(target1.category_details.Up_Sell.value!=""){
										target_row1 +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Up Sell' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Up_Sell.value / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"up_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>" ;
									}
									if(target1.category_details.Cross_Sell.value!=""){
										target_row1 +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Cross Sell' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Cross_Sell.value / 1)).toLocaleString()+ "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"cross_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									if(target1.category_details.Renewal.value!=""){
										target_row1 +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Renewal' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Renewal.value  / 1)).toLocaleString()+ "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"cross_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									
								}
								if(target1.category == "Consolidated"){
									target_row1 +='<tr><td>'+(count1)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + dateRange +'</td><td>' + target1.category + '</td><td>' + target1.target_type + currencySplit + '</td><td>'+(Math.floor(target1.category_details.common.value  / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
								}
							}
							
							
						 }
						$("#tablebody").append(target_row);
						$("#tablebody1").append(target_row1);
				cancel();
			   		}
			   }	
		});  
	      }
	function save_target_edit(){
		if($.trim($("#salerep_target_edit").val())==""){
			$("#salerep_target_edit").closest("div").find("span").text("Target type is required.");
			$("#salerep_target_edit").focus();				
			return;
		}else{
			$("#salerep_target_edit").closest("div").find("span").text("");
		} 
		if($.trim($("#targetPeriod_edit").val())==""){
			$("#targetPeriod_edit").closest("div").find("span").text("Period is required.");
			$("#targetPeriod_edit").focus();				
			return;
		}else{
			$("#targetPeriod_edit").closest("div").find("span").text("");
		}
		if($.trim($("#salerep_target_edit").val()) == "revenue"){
			if($.trim($("#targetCurrency_edit").val())==""){
				$("#targetCurrency_edit").closest("div").find("span").text("Currency is required");
				return;
			}
			else{
				$("#targetCurrency_edit").closest("div").find("span").text("");
			}
		}
		var startDate = '', endDate='';
		if($.trim($("#targetPeriod_edit").val()) == "Custom"){
			if($.trim($("#E_targetStartDate1").val())==""){
				$(".E_targetSDate").find("span").text("Date is required.");		
				return;
			}
			else{
				$(".E_targetSDate").closest("div").find("span").text("");
			}
			if($.trim($("#E_targetEndDate1").val())==""){
				$(".E_targetEDate").find("span").text("Date is required.");		
				return;
			}
			else{
				$(".E_targetEDate").closest("div").find("span").text("");
			}
		}else{
			if($.trim($("#E_targetStartDate1").val())==""){
				$(".E_targetSDate").find("span").text("Date is required.");			
				return;
			}
			else{
				$(".E_targetSDate").closest("div").find("span").text("");
			}
		}
		if($.trim($("#targetPeriod_edit").val()) == "Custom"){
			startDate = $.trim($("#E_targetStartDate1").val());
			endDate = $.trim($("#E_targetEndDate1").val());
		}else{
			startDate = $.trim($("#E_targetStartDate1").val());
			endDate = '';
		} 
		if($("#consol_edit").is(":checked")){
			if($.trim($("#targetTarget1_edit").val())==""){
				$("#targetTarget1_edit").closest("div").find("span").text("Target value is required.");
				$("#targetTarget1_edit").focus();				
				return;
			}else if(!number($.trim($("#targetTarget1_edit").val()))) {
				$("#targetTarget1_edit").closest("div").find("span").text("Only numbers are allowed");
				$("#targetTarget1_edit").focus();	
				return;
			}else{
				$("#targetTarget1_edit").closest("div").find("span").text("");
			}
		}
		if($("#spilt_edit").is(":checked")){
			if($(".new_sale_e ").css("display")=="block"){
				if($.trim($("#new_sales_edit").val())==""){
					$("#new_sales_edit").closest("div").find("span").text("value is required.");
					$("#new_sales_edit").focus();				
					return;
				}else if(!number($.trim($("#new_sales_edit").val()))) {
					$("#new_sales_edit").closest("div").find("span").text("Only numbers are allowed");
					$("#new_sales_edit").focus();	
					return;
				}else{
					$("#new_sales_edit").closest("div").find("span").text("");
				}
			}
			if($(".up_sale_e").css("display")=="block"){
				if($.trim($("#up_sales_edit").val())==""){
					$("#up_sales_edit").closest("div").find("span").text("value is required.");
					$("#up_sales_edit").focus();				
					return;
				}else if(!number($.trim($("#up_sales_edit").val()))) {
					$("#up_sales_edit").closest("div").find("span").text("Only numbers are allowed");
					$("#up_sales_edit").focus();	
					return;
				}else{
					$("#up_sales_edit").closest("div").find("span").text("");
				}
			}
			if($(".cross_sale_e").css("display")=="block"){
				if($.trim($("#cross_sales_edit").val())==""){
					$("#cross_sales_edit").closest("div").find("span").text("value is required.");
					$("#cross_sales_edit").focus();				
					return;
				}else if(!number($.trim($("#cross_sales_edit").val()))) {
					$("#cross_sales_edit").closest("div").find("span").text("Only numbers are allowed");
					$("#cross_sales_edit").focus();	
					return;
				}else{
					$("#cross_sales_edit").closest("div").find("span").text("");
				}
			}
			if($(".e_renewal").css("display")=="block"){
				if($.trim($("#e_renewal").val())==""){
					$("#e_renewal").closest("div").find("span").text("value is required.");
					$("#e_renewal").focus();				
					return;
				}else if(!number($.trim($("#e_renewal").val()))) {
					$("#e_renewal").closest("div").find("span").text("Only numbers are allowed");
					$("#e_renewal").focus();	
					return;
				}else{
					$("#e_renewal").closest("div").find("span").text("");
				}
			}
		}
		var targetObj = {};
		var product_ids = [];
		product_ids.push($("#multiselect_edit").val());
		var period = $("#targetPeriod_edit").val();
		var target_type = $("#salerep_target_edit").val();
		var target_currency = $("#targetCurrency_edit").val();
		var manager_id=$("#salerep_id").val();    
		var target_id=$("#target_id").val();  
		var checked = '';
		if($("#E_teamTarget").is(":checked")){
			checked = "teamTarget";
		}else{
			checked = "individualTarget";
		}
		targetObj = {
				"product_ids": product_ids,
				"period" : period,
				"target_type": target_type,
				"target_currency": target_currency,
				"manager_id":manager_id,
				"target_id":target_id,
				"startDate":startDate,
				"endDate":endDate,
				"checked":checked
			};      
		targetObj["category_details"] = {};
		if($("#spilt_edit").is(":checked")){
			targetObj["category"] = 'Split';			
			var new_target_value = $("#new_sales_edit").val();
			new_target_value = new_target_value.split(",").join("");
			var up_target_value = $("#up_sales_edit").val();
			up_target_value = up_target_value.split(",").join("");
			var cross_target_value = $("#cross_sales_edit").val();
			cross_target_value = cross_target_value.split(",").join("");
			var renewal = $("#e_renewal").val();
			renewal = renewal.split(",").join("");
			targetObj["category_details"]["New_Sell"] = {"value":new_target_value};
			targetObj["category_details"]["Up_Sell"] = {"value":up_target_value};
			targetObj["category_details"]["Cross_Sell"] = {"value":cross_target_value};
			targetObj["category_details"]["Renewal"] = {"value":renewal};
		} 
		if($("#consol_edit").is(":checked")){
			targetObj["category"] = 'Consolidated';

			var common_target_value = $("#targetTarget1_edit").val();
			common_target_value = common_target_value.split(",").join("");
			targetObj["category_details"]["common"] = {"value":common_target_value};
		}
		console.log(targetObj);
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_teamManagersController/update_target');?>", 
			data:JSON.stringify(targetObj),
			dataType : 'json',
			cache : false,
			success : function(data){
				loaderHide();
					if(error_handler(data)){
                    return;
                }
				cancel();

				console.log(data)
				var target_row="", target_row1='', currencyName = '', dateRange = '', currencySplit = '', count=0, count1=0;
						$('#tablebody').empty();						
						$('#tablebody1').empty();						
					   for(var i=0;i<data.length; i++){	
					   		var rowdata=JSON.stringify(data[i]);
							var target1 = JSON.parse(data[i].target_data);
							if(data[i].currency_name == null){
								currencyName = '';
							}else{
								currencyName = data[i].currency_name;
							}
							if(target1.target_type == 'Numbers'){
								currencySplit = '';
							}else{
								currencySplit = currencyName.split("- ");
								currencySplit = ' ('+currencySplit[1]+')';
							}
							
							/*------------------ Month calculation ----------------------*/
							moment.addRealMonth = function addRealMonth(d , monthCount) {
							  var fm = moment(d).add(monthCount, 'M');
							  var fmEnd = moment(fm).endOf('month');
							  return d.date() != fm.date() && fm.isSame(fmEnd.format('DD-MM-YYYY')) ? fm.add(1, 'd') : fm;  
							}
							var nextMonth = ""
							
							var period = {"Monthly":1, "Quarterly":3, "HalfYearly":6, "Yearly":12};
							
							$.each( period, function(key, val){
								if(target1.period.split(' ').join('') == key){
									nextMonth = moment.addRealMonth(moment(target1.startDate,'DD-MM-YYYY') , val);
									nextMonth =  moment(nextMonth).subtract(1, "days").format("DD-MM-YYYY");
								}	
							})
							/*----------------------------------------*/
							console.log(nextMonth)
							var date = target1.startDate +" to "+ nextMonth ;
							if(target1.period == 'Custom'){
								dateRange = target1.startDate + ' to ' + target1.endDate;
							}else{
								dateRange = date;
							}
							if(target1.checked == 'teamTarget'){
								count = count + 1;
								$("#open").show();
								$(".target_table").show();
								if(target1.category == "Split"){
									if(target1.category_details.New_Sell.value!=""){
										target_row +='<tr><td>'+(count)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + dateRange +'</td><td>' + 'New Sell' +'</td><td>' + target1.target_type + currencySplit + '</td><td>'+(Math.floor(target1.category_details.New_Sell.value / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"new_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									if(target1.category_details.Up_Sell.value!=""){
										target_row +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Up Sell' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Up_Sell.value / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"up_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>" ;
									}
									if(target1.category_details.Cross_Sell.value!=""){
										target_row +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Cross Sell' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Cross_Sell.value / 1)).toLocaleString()+ "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"cross_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									if(target1.category_details.Renewal.value!=""){
										target_row +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Renewal' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Renewal.value  / 1)).toLocaleString()+ "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"cross_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									
								}
								if(target1.category == "Consolidated"){
									target_row +='<tr><td>'+(count)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + dateRange +'</td><td>' + target1.category + '</td><td>' + target1.target_type + currencySplit + '</td><td>'+(Math.floor(target1.category_details.common.value  / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
								}
							}else{
								count1 = count1 + 1;
								$("#close").show();
								$(".target_table1").show();
								if(target1.category == "Split"){
									if(target1.category_details.New_Sell.value!=""){
										target_row1 +='<tr><td>'+(count1)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + dateRange +'</td><td>' + 'New Sell' +'</td><td>' + target1.target_type + currencySplit + '</td><td>'+(Math.floor(target1.category_details.New_Sell.value / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"new_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									if(target1.category_details.Up_Sell.value!=""){
										target_row1 +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Up Sell' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Up_Sell.value / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"up_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>" ;
									}
									if(target1.category_details.Cross_Sell.value!=""){
										target_row1 +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Cross Sell' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Cross_Sell.value / 1)).toLocaleString()+ "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"cross_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									if(target1.category_details.Renewal.value!=""){
										target_row1 +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Cross' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Renewal.value  / 1)).toLocaleString()+ "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"cross_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									
								}
								if(target1.category == "Consolidated"){
									target_row1 +='<tr><td>'+(count1)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + dateRange +'</td><td>' + target1.category + '</td><td>' + target1.target_type + currencySplit + '</td><td>'+(Math.floor(target1.category_details.common.value  / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
								}
							}
							
							
						 }
						$("#tablebody").append(target_row);
						$("#tablebody1").append(target_row1);
						
			   }	
		});
		   
	}                
	$(document).ready(function(){
		load();
		/*date field starts*/	
		$("#endDateTimePicker").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY'
		});	  
		$("#startDateTimePicker").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY'
		});
		$("#endDateTimePicker").on("dp.change", function (selected) {
			var startDateTime = moment($.trim($("#targetStartDate1").val()), 'DD-MM-YYYY');
			$("#endDateTimePicker").data("DateTimePicker").minDate(startDateTime);
			
		})
		$("#E_startDateTimePicker").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY'
		});
		$("#E_endDateTimePicker").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY'
		});
		$("#E_endDateTimePicker").on("dp.change", function (selected) {
			var startDateTime = moment($.trim($("#E_targetStartDate1").val()), 'DD-MM-YYYY');
			$("#E_endDateTimePicker").data("DateTimePicker").minDate(startDateTime);			
		})
		  /*date field ends*/
		$("#salerep_target").on("change", function(){
			var target = $("#salerep_target").val();
			if(target == "Numbers"){
				$('.targetrow').show();
				$('.targetrow3').show();
				$('.targetrow1').show();
				$('.cons_spli').show();
			}else if(target == "Revenue"){
				$('.targetrow').show();
				$('.targetrow1').show();
				$('.targetrow3').show();
				$('.cons_spli').show();
			}else{
				$('.targetrow1').hide();
				$('.targetrow2').hide();
				$('.targetrow3').hide();
				$('.cons_spli').hide();
				$('.target_sales_type ').hide();
				$("#spilt_a").prop("checked",false);
				$("#consol").prop("checked",true);
			}
		});
		$(".cons_spli input[type=radio]").click(function(){
			if($("#spilt_a").is(":checked")){
				$(".target_sales_type").show();
				$('.targetrow1').hide();
			}else{
				$(".target_sales_type").hide();
				$('.targetrow1').show();
			}
		});
	
	});
function load(){
    var arr={};      
    arr.manager_id="<?php echo $result;?>";    
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('manager_teamManagersController/get_manager_data');?>",
		data:JSON.stringify(arr),                        
		dataType : 'json',
		cache : false,
		success : function(data){
			if(error_handler(data)){
                    return;
                }
			console.log(data);
			$("#rep_email").html(JSON.parse(data[0].email));
			$("#rep_name").html(data[0].repname);
			$("#rep_dep").html(data[0].Department_name);
			$("#salerep_team_name").html(data[0].teamname);
			$("#salerep_person_reprtg").html(data[0].manager);
			$("#salerep_region").html(data[0].region);
			$("#rep_loc").html(data[0].location);
			$("#salerep_DOB").html( (data[0].dob == null ? '' : moment(data[0].dob).format('DD-MM-YYYY')) );
			$("#rep_gender").html(data[0].user_gender);
			$("#salerep_Mobile").html(JSON.parse(data[0].phone1));	
			$("#salerep_client_address").html(data[0].address1);
			$("#rep_resaddress").html(data[0].address2);
			$("#Rooster").val(data[0].working_days);
			$("#salerep_designation").html(data[0].designation);
			$("#salerep_expenses").html(data[0].expenses);
			$("#salerep_travel").html(data[0].travel_cost);
			$("#callCost").val(data[0].outgoingcall_cost);
			$("#smsCost").val(data[0].outgoingsms_cost);
            $("#callCurrency").val(data[0].outgoingcall_currency);
			$("#smsCurrency").val(data[0].outgoingsms_currency);
			$("#salerep_teamid").val(data[0].team_id);
			console.log(data[0].team_id);
			$("#rep_name").val(data[0].manager_id);
			$("#targetCurrency").val(data[0].currency);
			$("#salerep_id").val(data[0].manager_id);	
			var multipl=""	
			var multipl1=""	
            var target_row="";	
			var addObj1={};
			addObj1.manager_id = $("#salerep_id").val();
            $.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_teamManagersController/get_manager_products');?>",
			data:JSON.stringify(addObj1),
			dataType : 'json',
			cache : false,
			success : function(data){
				data_get = data;
				if(error_handler(data)){
                    return;
                }
				console.log(data)
				var currencyhtml ="";
				var currencyhtml1 ="";
				$("#currency_value_list_pro").html("");
					for(var i=0;i<data.length; i++){
						pro1.push(data[i].product_id);
						if(data[i].hasOwnProperty('curdata')) {
							$("#currency_value_list_pro").append('<div class="col-md-5" id="currencyList'+i+'"><label class="prod_leaf_node"> '+data[i].product_name+'</label></div>');
							currencyhtml="";
							currencyhtml +='<div id="currency_value'+i+'" class="multiselect_pro">';
							currencyhtml +='<ul>';						
							for(var j=0;j<data[i].curdata.length; j++){	
								cur1.push(data[i].curdata[j].currency_id);
								currencyhtml +='<li>' +(j+1) + '<label>.'+data[i].curdata[j].currency_name+'<label></li>';
							}
							currencyhtml +='</ul>';
							currencyhtml +='</div>';
							$("#currencyList"+i).append(currencyhtml)
						}
					}
					for(var i=0;i<data.length; i++){
						if(data[i].hasOwnProperty('curdata')) {						
						
						}else{
							currencyhtml1 += '<div class="col-md-12" id="currencyList'+i+'"><label class="prod_leaf_node"><input type="checkbox" value="'+data[i].product_id+'">  '+data[i].product_name+'</label></div>';
						}
					}						
					if( currencyhtml1.length > 0){
						$("#currency_value_list_pro").append("<div class='without-curr col-md-5'>"+ currencyhtml1 +"</div>");
					}
		} 
			});
				
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('manager_teamManagersController/get_target');?>", 
					data:JSON.stringify(addObj1),
					dataType : 'json',
					cache : false,
					success : function(data){
					loaderHide();
							if(error_handler(data)){
							return;
							}
						var target_row="", target_row1='', currencyName = '', dateRange = '', currencySplit = '', count=0, count1=0;
						$('#tablebody').empty();						
						$('#tablebody1').empty();						
					   for(var i=0;i<data.length; i++){	
					   		var rowdata=JSON.stringify(data[i]);
							var target1 = JSON.parse(data[i].target_data);
							if(data[i].currency_name == null){
								currencyName = '';
							}else{
								currencyName = data[i].currency_name;
							}
							if(target1.target_type == 'Numbers'){
								currencySplit = '';
							}else{
								currencySplit = currencyName.split("- ");
								currencySplit = ' ('+currencySplit[1]+')';
							}
							/*------------------ Month calculation ----------------------*/
							moment.addRealMonth = function addRealMonth(d , monthCount) {
							  var fm = moment(d).add(monthCount, 'M');
							  var fmEnd = moment(fm).endOf('month');
							  return d.date() != fm.date() && fm.isSame(fmEnd.format('DD-MM-YYYY')) ? fm.add(1, 'd') : fm;  
							}
							var nextMonth = ""
							
							var period = {"Monthly":1, "Quarterly":3, "HalfYearly":6, "Yearly":12};
							
							$.each( period, function(key, val){
								if(target1.period.split(' ').join('') == key){
									nextMonth = moment.addRealMonth(moment(target1.startDate,'DD-MM-YYYY') , val);
									nextMonth =  moment(nextMonth).subtract(1, "days").format("DD-MM-YYYY");
								}	
							})
							/*----------------------------------------*/
							console.log(nextMonth)
							var date = target1.startDate +" to "+ nextMonth ;
							if(target1.period == 'Custom'){
								dateRange = target1.startDate + ' to ' + target1.endDate;
							}else{
								dateRange = date;
							}
							if(target1.checked == 'teamTarget'){
								count = count + 1;
								$("#open").show();
								$(".target_table").show();
								if(target1.category == "Split"){
									if(target1.category_details.New_Sell.value!=""){
										target_row +='<tr><td>'+(count)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + dateRange +'</td><td>' + 'New Sell' +'</td><td>' + target1.target_type + currencySplit + '</td><td>'+(Math.floor(target1.category_details.New_Sell.value / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"new_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									if(target1.category_details.Up_Sell.value!=""){
										target_row +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Up Sell' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Up_Sell.value / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"up_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>" ;
									}
									if(target1.category_details.Cross_Sell.value!=""){
										target_row +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Cross Sell' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Cross_Sell.value / 1)).toLocaleString()+ "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"cross_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									if(target1.category_details.Renewal.value!=""){
										target_row +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Renewal' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Renewal.value  / 1)).toLocaleString()+ "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"cross_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									
								}
								if(target1.category == "Consolidated"){
									target_row +='<tr><td>'+(count)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + dateRange +'</td><td>' + target1.category + '</td><td>' + target1.target_type + currencySplit + '</td><td>'+(Math.floor(target1.category_details.common.value  / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
								}
							}else{
								count1 = count1 + 1;
								$("#close").show();
								$(".target_table1").show();
								if(target1.category == "Split"){
									if(target1.category_details.New_Sell.value!=""){
										target_row1 +='<tr><td>'+(count1)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + dateRange +'</td><td>' + 'New Sell' +'</td><td>' + target1.target_type + currencySplit + '</td><td>'+(Math.floor(target1.category_details.New_Sell.value / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"new_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									if(target1.category_details.Up_Sell.value!=""){
										target_row1 +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Up Sell' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Up_Sell.value / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"up_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>" ;
									}
									if(target1.category_details.Cross_Sell.value!=""){
										target_row1 +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Cross Sell' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Cross_Sell.value / 1)).toLocaleString()+ "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"cross_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									if(target1.category_details.Renewal.value!=""){
										target_row1 +='<tr><td>'+''+'</td><td>' + '' + '</td><td>' + '' + '</td><td>' + '' +'</td><td>' + 'Renewal' +'</td><td>' + '' + '</td><td>'+(Math.floor(target1.category_details.Renewal.value  / 1)).toLocaleString()+ "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+", \"cross_sell\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
									}
									
								}
								if(target1.category == "Consolidated"){
									target_row1 +='<tr><td>'+(count1)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + dateRange +'</td><td>' + target1.category + '</td><td>' + target1.target_type + currencySplit + '</td><td>'+(Math.floor(target1.category_details.common.value  / 1)).toLocaleString() + "</td><td><a data-toggle='modal' href='#edit_target' class='edit_Target' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
								}
							}
							
							
						 }
						$("#tablebody").append(target_row);
						$("#tablebody1").append(target_row1);
						$.ajax({
							type: "POST",
							url : "<?php echo site_url('manager_teamManagersController/checkSuperior');?>", 
							dataType:'json',
							data:JSON.stringify(addObj1),
							success: function(data){
								console.log(data[0].canAdd)
								if(data[0].canAddHimself == "Yes" || data[0].canEditHimself == "Yes" && data[0].loginUser == data[0].user_id){
									$("#add_target").show();
									$(".edit_Target").show();
								}else if((data[0].canAdd == "Yes" || data[0].canEdit == "Yes") && data[0].loginUser == data[0].user_id){
									$("#add_target").hide();
									$(".edit_Target").hide();
								}else{
									$("#add_target").show();
									$(".edit_Target").show();
								}
								if(data[0].manager_module !='0' && data[0].sales_module != "0"){
									$(".individualTarget, .E_individualTarget").show();
									$(".individualTarget").prop("checked", true);
									$(".teamTarget, .E_teamTarget").show();
								}else if(data[0].manager_module !='0'){
									$(".teamTarget, .E_teamTarget").show();
									$(".teamTarget").prop("checked", true);
									$(".individualTarget, .E_individualTarget").hide();
								}else if(data[0].sales_module !='0'){
									$(".teamTarget, .E_teamTarget").hide();
									$(".individualTarget, .E_individualTarget").show();
									$(".individualTarget").prop("checked", true);
								}else{
									$(".teamTarget, .E_teamTarget").hide();
									$(".individualTarget, .E_individualTarget").hide();
								}
							}
						});

					}
							  
				});
				
					
			}
		});
}        
		$.ajax({
			type: "POST",
			url : "<?php echo site_url('manager_teamManagersController/teamSMSCurrency');?>", 
			dataType:'json',
			success: function(data){
				var select = $("#callCurrency"), options = "<option value=''>select</option>";
				select.empty();
				var select1 = $("#smsCurrency"), options = "<option value=''>select</option>";
				select1.empty();       
				for(var i=0;i<data.length; i++){
				options += "<option value='"+data[i].currency_id+"'>"+ data[i].currency_name +"</option>";              
			}
			select.append(options);
			select1.append(options);
			}
		});
        /* $.ajax({
			type: "POST",
			url:"<?php echo base_url('js/currencylist.json'); ?>",
			dataType:'json',
			success: function(data){
				var select1 = $("#smsCurrency"), options = "<option value=''>select</option>";
				select1.empty();      
				for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].value+"'>"+ data[i].innertext +"</option>";              
				}
				select1.append(options);
			}
		});	 */
	
		function update_rep(){
			var addObj={};
			addObj.team_id =$("#salerep_teamid").val();
			$("#salerep_target").prop("disabled", false);
			$("#callCurrency").prop("disabled", false);
			$("#callCost").prop("disabled", false);
			$("#smsCurrency").prop("disabled", false);
			$("#smsCost").prop("disabled", false);
			$("#Rooster").prop("disabled", false); 
			//$("#add_target").show();
			$("#rep_update").show();
		}
		
		function target_btn(){
			$(".target_area").show();
			$("#consol").prop("checked", true);
			var addObj={};
			addObj.rep_id = $("#salerep_id").val();     
			 $.ajax({
				type : "POST",
				url : "<?php echo site_url('manager_teamManagersController/getTargetProducts');?>", 
				data:JSON.stringify(addObj),
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
                    return;
                }
				var select = $("#targetProduct"), options = "<option value=''>Choose Product</option>";
				select.empty();      
				for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].product_id+"'>"+ data[i].product_name +"</option>";              
				}
				select.append(options);	

				}		  
			}); 
			 var addObj1={};
			addObj1.user_id=$("#salerep_id").val(); 
			addObj1.team_id =$("#salerep_teamid").val();   
			 $.ajax({
				type : "POST",
				url : "<?php echo site_url('manager_teamManagersController/getSellType');?>", 
				data:JSON.stringify(addObj1),
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
                    return;
                }
					console.log(data)
					for(i=0;i<data.length;i++){
					if(data[i].sell_type == "cross_sell"){
				     	$("#new_hide1").val(data[0].sell_type);
				     }if(data[i].sell_type == "new_sell"){
				     	$("#new_hide2").val(data[0].sell_type);
				     }if(data[i].sell_type == "up_sell"){
						$("#new_hide").val(data[0].sell_type);
				     }if(data[i].sell_type == "renewal"){
						$("#new_hide3").val(data[0].sell_type);
				     }
				 }
				}	  
			}); 
			
		}
		function splitChange(){
			if($("#new_hide2").val()!=""){
				$(".new_sale1").show();
			}
			if($("#new_hide1").val()!=""){
				$(".cross_sale1").show();
			}
			if($("#new_hide").val()!=""){
				$(".up_sale1").show();
			}
			if($("#new_hide3").val()!=""){
				$(".renewal").show();
			}
		}
		function add_pro(){
			$("#product_add").modal('show');
            var addObj={};
			addObj.team_id =$("#salerep_teamid").val();
			 $.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_teamManagersController/get_products');?>",
			data:JSON.stringify(addObj),
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
                    return;
                }
				console.log(data)
				
				var currencyhtml ="";
				var currencyhtml1 ="";
				$("#currency_value").html("");
					for(var i=0;i<data.length; i++){
						pro.push(data[i].product_id);
						if(data[i].hasOwnProperty('curdata')) {
							$("#currency_value").append('<div class="col-md-5" id="currencyList1'+i+'"><label class="prod_leaf_node1"><input id="prod1'+data[i].product_id+'"onchange="checkUncheck1(this.id)" type="checkbox" value="'+data[i].product_id+'">  '+data[i].productname+'</label></div>');
							currencyhtml="";
							currencyhtml +='<div id="currency_value'+i+'" class="multiselect2">';
							currencyhtml +='<ul>';						
							for(var j=0;j<data[i].curdata.length; j++){	
								cur.push(data[i].curdata[j].currency_id);
								if(data[i].curdata[j].currency_id!=null){
									currencyhtml +='<li><label><input type="checkbox" value="'+data[i].curdata[j].currency_id+'" disabled>  '+data[i].curdata[j].currencyname+'<label></li>';
								}
							}
							currencyhtml +='</ul>';
							currencyhtml +='</div>';
							$("#currencyList1"+i).append(currencyhtml)
						}
					}
					for(var i=0;i<data.length; i++){
						if(data[i].hasOwnProperty('curdata')) {						
						
						}else{
							currencyhtml1 += '<div class="col-md-12" id="currencyList1'+i+'"><label class="prod_leaf_node1"><input type="checkbox" value="'+data[i].product_id+'">  '+data[i].productname+'</label></div>';
						}
					}						
					if( currencyhtml1.length > 0){
						$("#currency_value").append("<div class='without-curr col-md-5'>"+ currencyhtml1 +"</div>");
					}
					console.log()
					console.log()
					for(var k=0;k<pro.length;k++){	
						for(var j=0;j<pro1.length;j++){
							if($.trim(pro[k]) == $.trim(pro1[j])){
								$("#currency_value input[value='"+pro[k]+"']").prop('checked', true);
							}
							
					if($("#currency_value input[value='"+pro[k]+"']").prop('checked') == true){
						$("#currency_value input[value='"+pro[k]+"']").closest("label").addClass("highlight");						
						$("#currency_value input[value='"+pro[k]+"']").closest(".col-md-5").find(".multiselect2").find("input[type=checkbox]").removeAttr("disabled");
					}else{
						$("#currency_value input[value='"+pro[k]+"']").closest("label").removeClass("highlight");
						$("#currency_value input[value='"+pro[k]+"']").closest(".col-md-5").removeAttr("style")
						$("#currency_value input[value='"+pro[k]+"']").closest(".col-md-5").find(".multiselect2").find("input[type=checkbox]").attr('disabled', 'disabled');
						$("#currency_value input[value='"+pro[k]+"']").closest(".col-md-5").find(".multiselect2").find("input[type=checkbox]").prop('checked', false);
					}
						}
					}
					/* for(var y=0;y<cur.length;y++){	
						for(var z=0;z<cur1.length;z++){
							if($.trim(cur[y]) == $.trim(cur1[z])){
								$("#currency_value .multiselect2 ul li input[value='"+cur[y]+"']").prop('checked', true);
							}
						}
					} */
					if(data_get.length != 0){
						$("#currency_value .prod_leaf_node1 input[type='checkbox']").each(function(){
							for(chk=0; chk<data_get.length; chk++){
								if($(this).val() == data_get[chk].product_id){
									if($(this).closest(".col-md-5").find(".multiselect2").length > 0){
										$(this).closest("label").addClass("highlight");
									}
									$(this).prop("checked", true);
									$(this).closest(".col-md-5").find(".multiselect2").find("input[type='checkbox']").prop("disabled", false);
									$(this).closest(".col-md-5").find(".multiselect2").find("input[type='checkbox']").each(function(){
										for(chk1=0; chk1<data_get[chk].curdata.length; chk1++){
											if($(this).val() == data_get[chk].curdata[chk1].currency_id){
												$(this).prop("checked", true).prop("disabled", false);
											}
										}
									})
								}
							}
						})
					}
		} 
			});
            
	}
		/* enable/disable currency checkbox  */
		function checkUncheck(id){
			var selected = $("#"+$.trim(id));
			if(selected.prop("checked") == true){
				selected.closest("label").addClass("highlight");
				selected.closest(".col-md-5").find(".multiselect_pro").find("input[type=checkbox]").removeAttr("disabled");
			}else{
				selected.closest("label").removeClass("highlight");
				selected.closest(".col-md-5").removeAttr("style")
				selected.closest(".col-md-5").find(".multiselect_pro").find("input[type=checkbox]").attr('disabled', 'disabled');
				selected.closest(".col-md-5").find(".multiselect_pro").find("input[type=checkbox]").prop('checked', false);
			}
		}
		function checkUncheck1(id){
			var selected1 = $("#"+$.trim(id));
			if(selected1.prop("checked") == true){
				selected1.closest("label").addClass("highlight");
				selected1.closest(".col-md-5").find(".multiselect2").find("input[type=checkbox]").removeAttr("disabled");
			}else{
				selected1.closest("label").removeClass("highlight");
				selected1.closest(".col-md-5").removeAttr("style")
				selected1.closest(".col-md-5").find(".multiselect2").find("input[type=checkbox]").attr('disabled', 'disabled');
				selected1.closest(".col-md-5").find(".multiselect2").find("input[type=checkbox]").prop('checked', false);
			}
		}
		function checkUncheck2(id){
			var selected1 = $("#"+$.trim(id));
			if(selected1.prop("checked") == true){
				selected1.closest("label").addClass("highlight");
				selected1.closest(".col-md-5").find(".multiselect1").find("input[type=checkbox]").removeAttr("disabled");
			}else{
				selected1.closest("label").removeClass("highlight");
				selected1.closest(".col-md-5").removeAttr("style")
				selected1.closest(".col-md-5").find(".multiselect1").find("input[type=checkbox]").attr('disabled', 'disabled');
				selected1.closest(".col-md-5").find(".multiselect1").find("input[type=checkbox]").prop('checked', false);
			}
		}
		function save_product(){
			var addObj={};
			addObj.productTarget1 = [];
			addObj.rep_id =$("#salerep_id").val();
			addObj.team_id =$("#salerep_teamid").val();
			$("#target_value1 input[type=checkbox]").each(function(){					
				if($(this).prop('checked')== true){
					addObj.productTarget1.push($(this).val());
					
				}
			});
			if(addObj.productTarget1.length > 0){
				addObj.value=1;
			}else{
				addObj.value=0;
			}
			var prodCurrencyObj={};
						 
			var prodCurrencymain=[];
			var aa1="";
			var aa=[];
			var successFlag =0;
			$(".prod_leaf_node1 input[type=checkbox]").each(function(){					
				if($(this).prop("checked") == true){
					var prod=$(this).val();
					var cur=[];
					var length = $(this).closest(".col-md-5").find(".multiselect2 input[type=checkbox]").length;
					
										
					if($(this).closest(".col-md-5").find(".multiselect2 ul li").length > 0){	
						var length2 =0;		
						$(this).closest(".col-md-5").find(".multiselect2 input[type=checkbox]").each(function(){	
							
							if($(this).prop("checked")==true){																				
								cur.push($(this).val());
							}else{
								length2 = length2+1;
								if(parseInt(length) == parseInt(length2)){
									$(this).closest(".col-md-5").find(".prod_leaf_node1").addClass("error");
									$(this).closest(".col-md-5").css({"border": "1px solid red"});
								}else{									
									$(this).closest(".col-md-5").find(".prod_leaf_node1").removeClass("error");
									$(this).closest(".col-md-5").removeAttr("style");
								}									
							}							
						})
						if(parseInt(length) == parseInt(length2)){
							successFlag =1;
						}
					}else{
						length2 =-1
						if(parseInt(length) == parseInt(length2)){
							successFlag =1;
						}
					}
								
					prodCurrencyObj={"prod" :prod,"currency":cur.toString()};
					prodCurrencymain.push(prodCurrencyObj);
				}
				
			})
			if(successFlag ==1){					
				return;
			}
			if(prodCurrencymain.length <= 0){
				$('#error').text("Please select atleast one product");
				$('.prod_leaf_node1').closest(".col-md-5").find(".prod_leaf_node1").addClass("error");
				$('.prod_leaf_node1').closest(".col-md-5").css({"border": "1px solid red"});
				return;
			}
			addObj.prodCurrency = prodCurrencymain;
			console.log(addObj);			
			loaderShow();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('manager_teamManagersController/save_products');?>",
				data:JSON.stringify(addObj),                        
				dataType : 'json',
				cache : false,
				success : function(data){	
				console.log(data)
				loaderHide();
				if(error_handler(data)){
					return;
                }
				if(data=="false"){
					alert("Products are assigned");
					$("#product_add").modal('hide');
				}else{
					window.location.reload();
				}					
				}
			});
		} 
		function selrow(obj, obj1){
			console.log(obj)
			console.log(obj1)
			var addObj1={};
			addObj1.user_id=$("#salerep_id").val(); 
			addObj1.team_id =$("#salerep_teamid").val();    
			 
			var target1 = JSON.parse(obj.target_data);		
			console.log(obj.product_id)
			var addObj={};
			addObj.rep_id = $("#salerep_id").val();  
			get_currency1(obj.product_id,target1.target_currency, target1.target_type);
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('manager_teamManagersController/getTargetProducts');?>", 
				data:JSON.stringify(addObj),
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
                    return;
                }
				var select = $("#multiselect_edit"), options = "<option value=''>Choose Product</option>";
				select.empty();      
				for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].product_id+"'>"+ data[i].product_name +"</option>";              
				}
				select.append(options);	
				$("#multiselect_edit option[value="+obj.product_id+"]").prop("selected", true);
				}		  
			});			
			$("#multiselect_edit").val(obj.product_name);
			$("#multiselect_edit1").val(obj.product_id);			
			$("#targetPeriod_edit").val(target1.period);
			$("#E_targetStartDate1").val(target1.startDate);
			$("#E_targetEndDate1").val(target1.endDate);
			if(target1.checked == 'teamTarget'){
				$("#E_teamTarget").prop("checked", true);
			}else{
				$("#E_individualTarget").prop("checked", true);
			}
			
			if(target1.period == "Custom"){
				$(".e_dates").show();
				$(".E_endDate").show();
			}else if(target1.period == ''){
				$(".e_dates").hide();
				$(".E_endDate").hide();
			}else{
				$(".e_dates").show();
				$(".E_endDate").hide();
			}
			$("#salerep_target_edit").val(target1.target_type);
			//$("#targetCurrency_edit").val(target1.target_currency);
			//$("#targetCurrency_edit option[value='"+target1.target_currency+"']").attr('checked', true);
			addObj.manager_id =$("#salerep_id").val();
			addObj.target_id =$("#target_id").val(obj.target_id);
			if(target1.category=='Split'){
				if(obj1 == 'cross_sell'){
					$(".cross_sale_e").show();
					$(".new_sale_e").hide();
					$(".up_sale_e").hide();				     	
					$(".renewal").hide();				     	
				 }if(obj1 == 'new_sell'){
					$(".new_sale_e").show();
					$(".cross_sale_e").hide();
					$(".up_sale_e").hide();				     	
					$(".renewal").hide();
				 }if(obj1 == 'up_sell'){
					$(".up_sale_e").show();
					$(".new_sale_e").hide();
					$(".cross_sale_e").hide();				     	
					$(".renewal").hide();						
				 }if(obj1 == 'renewal'){
					$(".up_sale_e").hide();
					$(".new_sale_e").hide();
					$(".cross_sale_e").hide();				     	
					$(".renewal").show();						
				 }
				/* $.ajax({
				type : "POST",
				url : "<?php echo site_url('manager_teamManagersController/getSellType');?>", 
				data:JSON.stringify(addObj1),
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
                    return;
                }
					console.log(data)
					if(data[0].sell_type == obj1){
						$("#new_hide1").val(data[0].sell_type);
				      	$(".cross_sale_e").show();
				      	$(".new_sale_e").hide();
				      	$(".up_sale_e").hide();				     	
				      	$(".renewal").hide();				     	
				     }if(data[1].sell_type == obj1){
				     	$("#new_hide2").val(data[0].sell_type);
				      	$(".new_sale_e").show();
				      	$(".cross_sale_e").hide();
				      	$(".up_sale_e").hide();				     	
				      	$(".renewal").hide();
				     }if(data[2].sell_type == obj1){
						$("#new_hide").val(data[0].sell_type);
				      	$(".up_sale_e").show();
				      	$(".new_sale_e").hide();
				      	$(".cross_sale_e").hide();				     	
				      	$(".renewal").hide();						
				     }if(data[3].sell_type == obj1){
						$("#new_hide").val(data[0].sell_type);
				      	$(".up_sale_e").hide();
				      	$(".new_sale_e").hide();
				      	$(".cross_sale_e").hide();				     	
				      	$(".renewal").show();						
				     }
				}	  
			});  */
				console.log(obj.category)
				$("#spilt_edit").prop("checked",true);
				$(".target_sales_type1").show();
				$(".consol_target").hide();
				$("#up_sales_edit").val(target1.category_details.Up_Sell.value);
				changeCase(target1.category_details.Up_Sell.value, 'up_sales_edit');
				$("#new_sales_edit").val(target1.category_details.New_Sell.value);
				changeCase(target1.category_details.New_Sell.value, 'new_sales_edit');
				$("#cross_sales_edit").val(target1.category_details.Cross_Sell.value);
				changeCase(target1.category_details.Cross_Sell.value, 'cross_sales_edit');
				$("#e_renewal").val(target1.category_details.Renewal.value);
				changeCase(target1.category_details.Renewal.value, 'e_renewal');
			}if(target1.category=='Consolidated'){
				$("#consol_edit").prop("checked",true);
				$(".target_sales_type1").hide();
				$(".consol_target").show();
				$("#targetTarget1_edit").val(target1.category_details.common.value);
				changeCase(target1.category_details.common.value, 'targetTarget1_edit');
			}
			$(".cons_spli_edit input[type=radio]").click(function(){
				if($("#spilt_edit").is(":checked")){
					$(".target_sales_type1").show();
					if( obj1 == "new_sell"){
						$(".new_sale_e").show();
						$(".up_sale_e").hide();
						$(".cross_sale_e").hide();
						$(".e_renewal").hide();
					}else if( obj1 == "up_sell"){
						$(".up_sale_e").show();
						$(".new_sale_e").hide();
						$(".cross_sale_e").hide();
						$(".e_renewal").hide();
					}else if( obj1 == "cross_sell"){
						$(".up_sale_e").hide();
						$(".new_sale_e").hide();
						$(".cross_sale_e").show();
						$(".e_renewal").hide();
					}else if( obj1 == "renewal"){
						$(".up_sale_e").hide();
						$(".new_sale_e").hide();
						$(".cross_sale_e").hide();
						$(".e_renewal").show();
					}else{
						$(".target_sales_type1").show();
						$(".cross_sale_e").show();
						$(".new_sale_e").show();
						$(".up_sale_e").show();
					}					
					$(".consol_target").hide();
				}else{
					$(".target_sales_type1").hide();
					$(".consol_target").show();
				}
			});			
		} 
     
	
	function back(){
        /* var site_url = "<?php echo site_url('manager_teamManagersController/');?>";
        window.location.href = site_url; */
		window.history.back();
    }function pro_btn(){
        $("#add_product").show();
        $(".edit_product").hide();
    }	
	function get_currency(cur_id){
		var addObj={};
		addObj.product_id=cur_id;
		addObj.rep_id =$("#salerep_id").val();
		$.ajax({
				type: "POST",
				url:"<?php echo site_url('manager_teamManagersController/getTargetCurrency');?>",
				dataType:'json',
				data:JSON.stringify(addObj),
				success: function(data){
					if(error_handler(data)){
                    return;
                }
					
					console.log(data)
					var select = $("#targetCurrency"), options = "<option value=''>select</option>";
					select.empty();      
					for(var i=0;i<data.length; i++){
						options += "<option value='"+data[i].currency_id+"'>"+ data[i].currency_name +"</option>";              
					}
					select.append(options);
				}
			});	
	}
	function get_currency1(cur_id,pro, target_type){
		var addObj={};
		addObj.product_id=cur_id;
		addObj.rep_id =$("#salerep_id").val();
		console.log(pro);
		$.ajax({
				type: "POST",
				url:"<?php echo site_url('manager_teamManagersController/getTargetCurrency');?>",
				dataType:'json',
				data:JSON.stringify(addObj),
				success: function(data){
					if(error_handler(data)){
                    return;
                }
					console.log(data)
					var select = $("#targetCurrency_edit"), options = "<option value=''>select</option>";
					select.empty();      
					for(var i=0;i<data.length; i++){
						options += "<option value='"+data[i].currency_id+"'>"+ data[i].currency_name +"</option>";              
					}
					select.append(options);
					if(target_type == "Numbers"){
						$(".targetCurrency_edit").hide();
						$("#targetCurrency_edit option[value='']").attr('selected', true);
					}else{
						$(".targetCurrency_edit").show();
						$("#targetCurrency_edit option[value='"+pro+"']").attr('selected', true);
					}
				}
			});	
	}
	function currencyShow(val){
		if(val == 'Revenue'){
			$(".targetCurrency").show();
		}else{
			$(".targetCurrency").hide();
			$("#targetCurrency option[value='']").attr('selected', true);
		}
	}
	function currencyShow_edit(val){
		if(val == 'Revenue'){
			$(".targetCurrency_edit").show();
		}else{
			$(".targetCurrency_edit").hide();
			$("#targetCurrency_edit option[value='']").attr('selected', true);
		}
	}
	function customTarget(val){
		if(val == 'Custom'){
			$(".dates").show();
			$(".endDate").show();
		}else if(val == ''){
			$(".dates").hide();
			$(".endDate").hide();
		}else{
			$(".dates").show();
			$(".endDate").hide();
		}
	}
	function E_customTarget(val){
		if(val == 'Custom'){
			$(".e_dates").show();
			$(".E_endDate").show();
		}else if(val == ''){
			$(".e_dates").hide();
			$(".E_endDate").hide();
		}else{
			$(".e_dates").show();
			$(".E_endDate").hide();
		}
	}
	</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">  
	<div class="loader">
		<center><h1 id="loader_txt"></h1></center>
	</div> 
		<!---------------------------------------------------------
		Header bar
		------------------------------------------------------------>
		<?php  require 'demo.php'  ?>
		<!---------------------------------------------------------
		side nav
		------------------------------------------------------------>		
		
		<?php require 'manager_sidenav.php' ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
				<div class="row header1">
				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >								
								<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-toggle="tooltip" data-placement="right" title="User Information"/>
							</div>
						</span>
					</div>				
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pageHeader1 aa">
							<h2 >User Information</h2>	
					</div>					
					<div style="clear:both"></div>
				</div>
				<div class="row row_rep" >
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label for="Name" class="label_head">Name</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4 ">
						<label id="rep_name" ></label> 
						<input type="hidden" id="rep_id" name=""></input>
					</div>
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label class="label_head">Department</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
						<label id="rep_dep"></label>   
					</div>
				</div>
				<div class="row row_rep" >
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label class="label_head">Team</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4 ">
						<label id="salerep_team_name"></label>
                        <input type="hidden" id="salerep_teamid" name="" />
					</div>
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label class="label_head">Reporting Into </label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
						<label id="salerep_person_reprtg"></label>
					</div>
				</div>
				<div class="row row_rep" >
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label class="label_head">Designation</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4 ">
						<label id="salerep_designation" ></label>
					</div>	
					<!--<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4 ">
						<label>Location</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
						<label id="rep_loc"></label>
					</div>-->
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label class="label_head">Date Of Birth</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4 ">
						<label id="salerep_DOB"></label>
					</div>
				</div>
				<div class="row row_rep" >
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label class="label_head">Gender</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
						<label id="rep_gender"></label>
					</div>
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label class="label_head">Mobile No</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4 ">
						<label id="salerep_Mobile"></label>
					</div>
				</div>
				<div class="row row_rep" >					
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label class="label_head">Email ID</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
						<label id="rep_email" ></label>
					</div>
				</div>
			   <div class="row row_heading row_rep" >
					<div class="col-md-11 col-xs-11 col-sm-11 col-lg-11">
						<label><b>Product and Currency</b></label>
					</div>
					
					<div class="col-md-1 col-xs-1 col-sm-1 col-lg-1 edit_product">
						<a onclick='pro_btn()'><span class='glyphicon glyphicon-pencil'></span></a>
					</div>
				</div>
				<div class="row pro_add">
					<div class="col-md-10 col-xs-10 col-sm-10 col-lg-10">
						
					</div>
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2">
						<input type="button" class="btn none" id="add_product" value="Add More" onclick="add_pro()"/>
					</div>			
				</div>	
				<div class="row pro_cur_user" id="currency_value_list_pro">
								
				</div>	
				<div class="row row_heading row_rep" >
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-6">
						<label><b>Office Location</b></label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-6">
						<label><b>Residence Address</b></label>
					</div>
				</div>
				<div class="row row_rep" >
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-6">
						<label id="salerep_client_address" for="Name"></label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-6">
						<label id="rep_resaddress" for="Name"></label>
					</div>
				</div>
				<div class="row row_heading row_rep" >
					<div class="col-md-11 col-xs-11 col-sm-11 col-lg-11">
						<label><b>User Functions</b></label>
					</div>
					<div class="col-md-1 col-xs-1 col-sm-1 col-lg-1">
						<a onclick='update_rep()'><span class='glyphicon glyphicon-pencil sec_edit'></span></a>
					</div>
				</div>
				<div class="row row_rep" >
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label class="label_head">Work Roster</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
						<select name="Rooster" id="Rooster" class="form-control" disabled>
							<option value="">Choose</option>	
							<option value="custom">Custom</option>
							<option value="fixed">Fixed</option>
						</select>
						<span class="error-alert"></span>
					</div>
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2">
						<label class="label_head">Expenses</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
						<label id="salerep_expenses"></label>
					</div>
				</div>
				<div class="row row_rep" >
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label class="label_head">Travel Allowance</label>
					</div>
					<div class="col-md-4 col-xs-2 col-sm-2 col-lg-4 ">
						<label id="salerep_travel"></label>
					</div>
					<div class="col-md-2 col-xs-4 col-sm-4 col-lg-2 ">
						<label class="label_head">Cost/Outgoing Call/Min</label>
					</div>
					<div class="col-md-3 col-xs-3 col-sm-3 col-lg-3">
						<select name="callCurrency" id="callCurrency" class="form-control " disabled>
							<option value="">Choose Currency</option>							
						</select>
					</div>
					<div class="col-md-1 col-xs-1 col-sm-1 col-lg-1">
						<input type="text" name="callCost" id="callCost" placeholder="00.00" class="form-control " disabled />
					</div>
				</div>
				<div class="row row_rep" >
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label class="label_head">Cost/Outgoing SMS</label>
					</div>
					<div class="col-md-3 col-xs-3 col-sm-3 col-lg-3">
						<select name="smsCurrency" id="smsCurrency" class="form-control" disabled>
							<option value="">Choose Currency</option>							
						</select>
					</div>
					<div class="col-md-1 col-xs-1 col-sm-1 col-lg-1">
						<input type="text" name="smsCost" id="smsCost" placeholder="00.00" class="form-control \" disabled />
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6 col-lg-6 ">
						
					</div>					
				</div>
				<div class="row row_heading row_rep" >
					<div class="col-md-11 col-xs-11 col-sm-11 col-lg-11">
						<label><b>Target Details</b></label>
					</div>
					<div class="col-md-1 col-xs-1 col-sm-1 col-lg-1">
						<a onclick="target_btn()" class="none" id="add_target" href="#target_add" data-toggle="modal">
							<span class="glyphicon glyphicon-plus"></span></span>
						</a>
					</div>
				</div>
					<div id="open" class="none" style="margin: 8px!important;">
						<div class="row">
							<div class="col-md-11" style="text-align:center;">
								<label><b>Team Target</b></label>
							</div>
						</div>
						<table class="table target_table none">					
							<thead>  
								<tr>			
									<th class="table_header" >SL</th>
									<th class="table_header" >Product</th>
									<th class="table_header" >Period</th>
									<th class="table_header" >Date Range</th>
									<th class="table_header" >Target Model</th>
									<th class="table_header" >Target Type</th>
									<th class="table_header" >Target</th>
									<th class="table_header" ></th>
								</tr>
							</thead> 
							<tbody id="tablebody">
							</tbody>
						</table>
						<div id="teamText">
						
						</div>
					</div>
					<div id="close" class="none" style="margin: 8px!important;">
						<div class="row">
							<div class="col-md-11" style="text-align:center;">
								<label><b>Individual Target</b></label>
							</div>
						</div>
						<table class="table target_table1 none">					
							<thead>  
								<tr>			
									<th class="table_header" >SL</th>
									<th class="table_header" >Product</th>
									<th class="table_header" >Period</th>
									<th class="table_header" >Date Range</th>
									<th class="table_header" >Target Model</th>
									<th class="table_header" >Target Type</th>
									<th class="table_header" >Target</th>
									<th class="table_header" ></th>
								</tr>
							</thead> 
							<tbody id="tablebody1">
							</tbody>
						</table>
						<div id="individualText">
						
						</div>
					</div>
				</div>
					 <center>
						<input type="button" name="bck"  value="Back" onclick="back()" class="btn"/>
						<input type="button" name="save"  value="Save" id="rep_update" onclick="save_rep()" class="btn none"/>
					</center>
			<div id="target_add" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="addpopup" class="form" action="#" method="post" >
                            <div class="modal-header">
                                <span class="close" onclick="cancel()">x</span>
                                <h4 class="modal-title"><b>Add Target</b></h4>
                            </div>
                            <div class="modal-body">								
                                <div class="target_area none">
									<div class="row" style="margin-bottom:12px;">
										<div class="col-md-6" style="text-align:right;">
											<input type="radio" class="individualTarget none" id="individualTarget" name="radio">
											<label class="individualTarget none" for="individualTarget">Individual Target</label>
										</div>
										<div class="col-md-6">
											<input type="radio" class="teamTarget none" id="teamTarget" name="radio">
											<label class="teamTarget none" for="teamTarget">Team Target</label>
										</div>
									</div>
									<div class="row targetrow ">
										<div class="col-md-2">
											<label for="targetProduct">Product * </label> 
										</div>
										<div class="col-md-4">	
											<select name="targetProduct" class="form-control" id="targetProduct" onchange="get_currency(this.value)">
												<option value="">Choose Product</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label>Target type *</label> 
										</div>
										<div class="col-md-4">	
											<select name="ContactLocation" id="salerep_target" class="form-control" onchange="currencyShow(this.value)">
												<option value="">Choose</option>
												<option value="Numbers">Numbers</option>
												<option value="Revenue">Revenue</option>		
											</select>
											<span class="error-alert"></span>
										</div>
									</div>											
									<div class="row targetrow target_up">
										<div class="col-md-2">
											<label for="targetPeriod">Period *</label> 
										</div>
										<div class="col-md-4">	
											<select name="targetPeriod" class="form-control" id="targetPeriod" onchange="customTarget(this.value)">
												<option value="">Choose</option>
												<option value="Monthly">Monthly</option>
												<option value="Quarterly">Quarterly</option>
												<option value="Half Yearly">Half Yearly</option>
												<option value="Yearly">Yearly</option>
												<option value="Custom">Custom</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2 targetCurrency none">
											<label>Currency type * </label> 
										</div>
										<div class="col-md-4 targetCurrency none">
											<select name="targetCurrency" class="form-control"  id="targetCurrency">
												<option value="">Choose Currency</option>
											</select>
											<input type="hidden" id="edit_cur_hidden"/>
											<span class="error-alert"></span>
										</div>										
									</div>
									<div class="row dates none">
										<div class="col-md-2">
											<label for="targetStartDate1">Start Date *</label> 
										</div>
										<div class="col-md-4">	
											 <div class='input-group date' id='startDateTimePicker'>
											   <input id="targetStartDate1" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
											   <span class="input-group-addon">
											   <span class="glyphicon glyphicon-calendar"></span>
											   </span>
											</div>
											<div class="targetSDate">
												<span class="error-alert"></span>
											</div>											
										</div>
										<div class="col-md-2 endDate">
											<label for="targetEndDate1">End Date *</label> 
										</div>
										<div class="col-md-4 endDate">	
											 <div class='input-group date' id='endDateTimePicker'>
											   <input id="targetEndDate1" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
											   <span class="input-group-addon">
											   <span class="glyphicon glyphicon-calendar"></span>
											   </span>
											</div>
											<div class="targetEDate">
												<span class="error-alert"></span>
											</div>
										</div>									
									</div>
								</div>
								
								<div class="row targetrow3 text_c none">
									<div class="col-md-3">
									
									</div>
									<div class="col-md-6">
										<h4><b>Target Model </b></h4>
									</div>
									<div class="col-md-3">
									
									</div>
								</div>
								<div class="row cons_spli none">
									<div class="col-md-3">
										<input type="radio" id="consol" name="radio1" checked /> <label for="consol">Consolidated</label>
									</div>
									
									<div class="col-md-3">
										<input type="radio" id="spilt_a" onclick="splitChange()" name="radio1" /> <label for="spilt_a">Split</label>
									</div>
									
								</div>
								<div class="row targetrow1 none">
									<div class="col-md-2">
										<label for="targetTarget1">Target *</label> 
									</div>
									<div class="col-md-4">	
										<input type="text" class="form-control" name="targetTarget1" id="targetTarget1" onkeyup="changeCase(this.value,this.id)"/>
										<input type="hidden" name="salerep_id" id="salerep_id">
										<span class="error-alert"></span>
									</div>
									
								</div>
								<div class="target_sales_type none">
									<div class="row">
										<div class="col-md-2 new_sale1 none">
											<label for="new_sales">New Sell *</label> 
										</div>
										<div class="col-md-4 new_sale1 none">	
											<input type="text" class="form-control" name="new_sales" id="new_sales" onkeyup="changeCase(this.value,this.id)"/>
											<input type="hidden" id="new_hide" />
											<input type="hidden" id="new_hide1" />
											<input type="hidden" id="new_hide2" />
											<input type="hidden" id="new_hide3" />
											<span class="error-alert"></span>
										</div>	
										<div class="col-md-2 up_sale1 none">
											<label for="up_sales">Up Sell *</label> 
										</div>
										<div class="col-md-4 up_sale1 none">	
											<input type="text" class="form-control" name="up_sales" id="up_sales" onkeyup="changeCase(this.value,this.id)"/>
											<span class="error-alert"></span>
										</div>									
									</div>
									<div class="row">
										<div class="col-md-2 cross_sale1 none">
											<label for="cross_sales">Cross Sell *</label> 
										</div>
										<div class="col-md-4 cross_sale1 none">	
											<input type="text" class="form-control" name="cross_sales" id="cross_sales" onkeyup="changeCase(this.value,this.id)"/>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2 renewal none">
											<label for="renewal">Renewal *</label> 
										</div>
										<div class="col-md-4 renewal none">	
											<input type="text" class="form-control" name="renewal" id="renewal" />
											<span class="error-alert"></span>
										</div>
									</div>
								</div>
                              </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-default" onclick="save_target()" value="Save" />
                                <input  type="button" class="btn btn-default" onclick="cancel()" value="Cancel" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
			<div id="edit_target" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="addpopup" class="form" action="#" method="post" >
                            <div class="modal-header">
                                <span class="close" onclick="cancel()">x</span>
                                <h4 class="modal-title"><b>Edit Target</b></h4>
                            </div>
                            <div class="modal-body">			
									<div class="row  text_c ">
										<div class="col-md-3">
										
										</div>
										<div class="col-md-6">
											<h4><b>Target</b></h4>
										</div>
										<div class="col-md-3">
										
										</div>
									</div>
									<div class="row" style="margin-bottom:12px;">
										<div class="col-md-6" style="text-align:right;">
											<input type="radio" class="E_individualTarget none" id="E_individualTarget" name="radio">
											<label class="E_individualTarget none" for="E_individualTarget">Individual Target</label>
										</div>
										<div class="col-md-6">
											<input type="radio" class="E_teamTarget none" id="E_teamTarget" name="radio">
											<label class="E_teamTarget none" for="E_teamTarget">Team Target</label>
										</div>
									</div>
									<div class="row  ">
										<div class="col-md-2">
											<label for="target_value_edit">Product</label> 
										</div>
										<div class="col-md-4">	
											<select name="multiselect_edit" id="multiselect_edit" class="form-control">
											
											</select>
											<input type="hidden" id="multiselect_edit1" />
										</div>
										<div class="col-md-2">
											<label for="salerep_target_edit">Target type *</label> 
										</div>
										<div class="col-md-4">	
											<select name="salerep_target_edit" id="salerep_target_edit" onchange="currencyShow_edit(this.value)" class="form-control">
													<option value="">Choose</option>
													<option value="Numbers">Numbers</option>
													<option value="Revenue">Revenue</option>		
												</select>
												<span class="error-alert"></span>
										</div>
									</div>	
									<div class="row  target_up">
										<div class="col-md-2">
											<label for="targetPeriod_edit">Period *</label> 
										</div>
										<div class="col-md-4">	
											<select name="targetPeriod_edit" class="form-control" id="targetPeriod_edit" onchange="E_customTarget(this.value)">
												<option value="">Choose</option>
												<option value="Monthly">Monthly</option>
												<option value="Quarterly">Quarterly</option>
												<option value="Half Yearly">Half Yearly</option>
												<option value="Yearly">Yearly</option>
												<option value="Custom">Custom</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2 targetCurrency_edit">
											<label for="targetCurrency_edit">Currency type *</label> 
										</div>
										<div class="col-md-4 targetCurrency_edit">
											<select name="targetCurrency_edit" class="form-control"  id="targetCurrency_edit">
												
											</select>
											<input type="hidden" id="edit_cur_hidden"/>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row e_dates none">
										<div class="col-md-2">
											<label for="E_targetStartDate1">Start Date *</label> 
										</div>
										<div class="col-md-4">	
											 <div class='input-group e_date' id='E_startDateTimePicker'>
											   <input id="E_targetStartDate1" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
											   <span class="input-group-addon">
											   <span class="glyphicon glyphicon-calendar"></span>
											   </span>
											</div>
											<div class="E_targetSDate">
												<span class="error-alert"></span>
											</div>											
										</div>
										<div class="col-md-2 E_endDate none">
											<label for="E_targetEndDate1">End Date *</label> 
										</div>
										<div class="col-md-4 E_endDate none">	
											 <div class='input-group date' id='E_endDateTimePicker'>
											   <input id="E_targetEndDate1" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
											   <span class="input-group-addon">
											   <span class="glyphicon glyphicon-calendar"></span>
											   </span>
											</div>
											<div class="E_targetEDate">
												<span class="error-alert"></span>
											</div>
										</div>									
									</div>
								
								<div class="row  text_c">
									<div class="col-md-3">
									
									</div>
									<div class="col-md-6">
										<h4><b>Target Model</b></h4>
									</div>
									<div class="col-md-3">
									
									</div>
								</div>
								<div class="row cons_spli_edit">
									<div class="col-md-3">
										<input type="radio" id="consol_edit" name="radio1" checked /> <label for="consol_edit">Consolidated </label>
									</div>
									
									<div class="col-md-3">
										<input type="radio" id="spilt_edit" name="radio1" /> <label for="spilt_edit">Split</label>
									</div>
									
								</div>
								<div class="row consol_target">
									<div class="col-md-2">
										<label for="targetTarget1_edit">Target *</label> 
									</div>
									<div class="col-md-4">	
										<input type="text" class="form-control" name="targetTarget1_edit" id="targetTarget1_edit" onkeyup="changeCase(this.value,this.id)" />
										<input type="hidden" name="salerep_id" id="salerep_id">
										<input type="hidden" name="target_id" id="target_id">
										<span class="error-alert"></span>
									</div>									
								</div>
								<div class="target_sales_type1 none">
									<div class="row">
										<div class="col-md-2 new_sale_e none">
											<label for="new_sales_edit">New Sell *</label> 
										</div>
										<div class="col-md-4 new_sale_e none">	
											<input type="text" class="form-control" name="new_sales_edit" id="new_sales_edit" onkeyup="changeCase(this.value,this.id)"/>
											<span class="error-alert"></span>
										</div>	
										<div class="col-md-2 up_sale_e none">
											<label for="up_sales_edit">Up Sell *</label> 
										</div>
										<div class="col-md-4 up_sale_e none">	
											<input type="text" class="form-control" name="up_sales_edit" id="up_sales_edit" onkeyup="changeCase(this.value,this.id)"/>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row  none">
										
									</div>
									<div class="row">
										<div class="col-md-2 cross_sale_e none">
											<label for="cross_sales_edit">Cross Sell *</label> 
										</div>
										<div class="col-md-4 cross_sale_e none">	
											<input type="text" class="form-control" name="cross_sales_edit" id="cross_sales_edit" onkeyup="changeCase(this.value,this.id)"/>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2 e_renewal none">
											<label for="renewal">Renewal *</label> 
										</div>
										<div class="col-md-4 e_renewal none">	
											<input type="text" class="form-control" name="renewal" id="e_renewal" onkeyup="changeCase(this.value,this.id)"/>
											<span class="error-alert"></span>
										</div>										
									</div>
								</div>
                              </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-default" onclick="save_target_edit()" value="Save" />
                                <input  type="button" class="btn btn-default" onclick="cancel()" value="Cancel" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
			<div id="product_add" class="modal fade none" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="addpopup" class="form" action="#" method="post" >
                            <div class="modal-header">
                                <span class="close" onclick="cancel()">x</span>
                                <h4 class="modal-title"><b>Add Product</b></h4>
                            </div>
                            <div class="modal-body">
								<div class="row">
									<div class="col-md-4 error_pro">
									
									</div>
									<div class="col-md-4">
										<span id="error"></span>
									</div>
									<div class="col-md-4">
									
									</div>
								</div>
								<div class="row pro_cur_user" id="currency_value">
								
								</div>	
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-default" onclick="save_product()" value="Save" />
                                <input  type="button" class="btn btn-default" onclick="cancel()" value="Cancel" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
		</div>
		<?php require 'footer.php' ?>

	</body>
</html>
