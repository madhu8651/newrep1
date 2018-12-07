
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
		height: 60px;
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
		margin: 2px;
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
			height:200px;
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
	</style>
	<script>
	
	var team = [],pro=[],cur=[];
	var team1 = [],pro1=[],cur1=[];
    function save_rep(){
		var addObj={};
		addObj.Rooster=$("#Rooster").val();
		addObj.salerep_target=$("#salerep_target").val();
		addObj.callCurrency=$("#callCurrency").val();
		addObj.callCost=$("#callCost").val();
		addObj.smsCurrency=$("#smsCurrency").val();
		addObj.smsCost=$("#smsCost").val();
		addObj.rep_id=$("#salerep_id").val();
		addObj.productArray = [];
		$("#attr_value1 input[type=checkbox]").each(function(){
			if($(this).prop('checked')== true){
				addObj.productArray.push($(this).val());
			}
		});
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_salesRepresentativeController/save_rep_data');?>", 
			data:JSON.stringify(addObj),
			dataType : 'json',
			cache : false,
			success : function(data){
				}
			});
		console.log(addObj);
    }
	function cancel(){
		$('.modal input[type="text"], textarea').val('');
		$('.modal').modal('hide');
		$('#salerep_target').val('');
		$('#targetPeriod').val('');
		$('.targetrow3').hide();
		$('.targetrow2').hide();
		$('.targetrow1').hide();
		$('.cons_spli').hide();
	}
	function save_target(){
		if($.trim($("#targetProduct").val())==""){
			$("#targetProduct").closest("div").find("span").text("Product is required");
			$("#targetProduct").focus();				
			return;
		}else{
			$("#targetProduct").closest("div").find("span").text("");
		}
		if($.trim($("#targetCurrency").val())==""){
			$("#targetCurrency").closest("div").find("span").text("Currency is required");
			return;
		}
		else{
			$("#targetCurrency").closest("div").find("span").text("");
		}
		if($.trim($("#targetPeriod").val())==""){
			$("#targetPeriod").closest("div").find("span").text("Period is required.");
			$("#targetPeriod").focus();				
			return;
		}else{
			$("#targetPeriod").closest("div").find("span").text("");
		}
		if($.trim($("#salerep_target").val())==""){
			$("#salerep_target").closest("div").find("span").text("Target type is required.");
			$("#salerep_target").focus();				
			return;
		}else{
			$("#salerep_target").closest("div").find("span").text("");
		}
		if($("#consol").is(":checked")){
			if($.trim($("#targetTarget1").val())==""){
				$("#targetTarget1").closest("div").find("span").text("Target value is required.");
				$("#targetTarget1").focus();				
				return;
			}
			else{
				$("#targetTarget1").closest("div").find("span").text("");
			}
			if($.trim($("#targetStartDate1").val())==""){
				$("#targetStartDate1").find("span").text("Target value is required.");
				$("#targetStartDate1").focus();				
				return;
			}
			else{
				$("#targetStartDate1").closest("div").find("span").text("");
			}
		}
		if($("#spilt").is(":checked")){
			if($.trim($("#new_sales").val())==""){
				$("#new_sales").closest("div").find("span").text("value is required.");
				$("#new_sales").focus();				
				return;
			}
			else{
				$("#new_sales").closest("div").find("span").text("");
			}
			if($.trim($("#new_sale_date").val())==""){
				$("#new_sale_date").find("span").text("Date is required.");
				$("#new_sale_date").focus();				
				return;
			}
			else{
				$("#new_sale_date").closest("div").find("span").text("");
			}
			if($.trim($("#up_sales").val())==""){
				$("#up_sales").closest("div").find("span").text("value is required.");
				$("#up_sales").focus();				
				return;
			}
			else{
				$("#up_sales").closest("div").find("span").text("");
			}
			if($.trim($("#up_sale_date").val())==""){
				$("#up_sale_date").find("span").text("Date is required.");
				$("#up_sale_date").focus();				
				return;
			}
			else{
				$("#up_sale_date").closest("div").find("span").text("");
			}
			if($.trim($("#cross_sales").val())==""){
				$("#cross_sales").closest("div").find("span").text("value is required.");
				$("#cross_sales").focus();				
				return;
			}
			else{
				$("#cross_sales").closest("div").find("span").text("");
			}
			if($.trim($("#cross_sale_date").val())==""){
				$("#cross_sale_date").find("span").text("Date is required.");
				$("#cross_sale_date").focus();				
				return;
			}
			else{
				$("#cross_sale_date").closest("div").find("span").text("");
			}
		}

		var targetObj = {};
		var product_ids = [];
		product_ids.push($('#targetProduct').val());
		var period = $("#targetPeriod").val();
		var target_type = $("#salerep_target").val();
		var target_currency = $("#targetCurrency").val();
		var rep_id=$("#salerep_id").val(); 
		targetObj = {
				"product_ids": product_ids,
				"period" : period,
				"target_type": target_type,
				"target_currency": target_currency,
				"rep_id":rep_id
			};
		targetObj["category_details"] = {};
		if($("#spilt").is(":checked")){
			targetObj["category"] = 'split';			
			var new_target_value = $("#new_sales").val();
			var new_target_date = $("#new_sale_date").val();
			var up_target_value = $("#up_sales").val();
			var up_target_date = $("#up_sale_date").val();
			var cross_target_value = $("#cross_sales").val();
			var cross_target_date = $("#cross_sale_date").val();
			targetObj["category_details"]["new"] = {"value":new_target_value, "start_date":new_target_date};
			targetObj["category_details"]["up"] = {"value":up_target_value, "start_date":up_target_date};
			targetObj["category_details"]["cross"] = {"value":cross_target_value, "start_date":cross_target_date};
		} 
		if($("#consol").is(":checked")){
			targetObj["category"] = 'consolidated';

			var common_target_value = $("#targetTarget1").val();
			var common_target_date = $("#targetStartDate1").val();
			targetObj["category_details"]["common"] = {"value":common_target_value, "start_date":common_target_date};
		}
		console.log(targetObj);

		 $.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_salesRepresentativeController/saveTarget');?>", 
			data:JSON.stringify(targetObj),
			dataType : 'json',
			cache : false,
			success : function(data){ 
			   		if(data=="false"){
			   			alert('Target is already defined');
			   		}
			   		else{
			   			var target_row="";					
					   for(var i=0;i<data.length; i++){	
					   		var rowdata=JSON.stringify(data[i]);
							var target1 = JSON.parse(data[i].target_data);
							console.log(target1)
							if(target1.category == "split"){
								$(".target_table").show();
								target_row +='<tr><td>'+(i+1)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + target1.target_type+'</td><td>' + 'new' +'</td><td>' + target1.category_details.new.start_date + '</td><td>'+target1.category_details.new.value + '</td><td>'+ data[i].currency_name + "</td><td><a data-toggle='modal' href='#edit_target' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
								
								target_row +='<tr><td>'+''+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + target1.target_type+'</td><td>' + 'up' +'</td><td>' + target1.category_details.up.start_date + '</td><td>'+target1.category_details.up.value + '</td><td>'+ data[i].currency_name + "</td><td>" + "</td></tr>";
								
								target_row +='<tr><td>'+''+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + target1.target_type+'</td><td>' + 'cross' +'</td><td>' + target1.category_details.cross.start_date + '</td><td>'+target1.category_details.cross.value + '</td><td>'+ data[i].currency_name + "</td><td>" + "</td></tr>";
							}if(target1.category == "consolidated"){
								$(".target_table").show();
								target_row +='<tr><td>'+(i+1)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + target1.target_type+'</td><td>' + target1.category + '</td><td>' + target1.category_details.common.start_date + '</td><td>'+target1.category_details.common.value + '</td><td>'+data[i].currency_name + "</td><td><a data-toggle='modal' href='#edit_target' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
							}
							
						 }
						$("#tablebody").append(target_row);
			   		}
			   }	
		});  
	      }
	function save_target_edit(){
		if($.trim($("#targetPeriod_edit").val())==""){
			$("#targetPeriod_edit").closest("div").find("span").text("Period is required.");
			$("#targetPeriod_edit").focus();				
			return;
		}else{
			$("#targetPeriod_edit").closest("div").find("span").text("");
		}
		if($.trim($("#salerep_target_edit").val())==""){
			$("#salerep_target_edit").closest("div").find("span").text("Target type is required.");
			$("#salerep_target_edit").focus();				
			return;
		}else{
			$("#salerep_target_edit").closest("div").find("span").text("");
		} 

		if($.trim($("#targetCurrency_edit").val())==""){
			$("#targetCurrency_edit").closest("div").find("span").text("Target Currency is required.");
			$("#targetCurrency_edit").focus();				
			return;
		}else{
			$("#targetCurrency_edit").closest("div").find("span").text("");
		} 
		if($("#consol_edit").is(":checked")){
			if($.trim($("#targetTarget1_edit").val())==""){
				$("#targetTarget1_edit").closest("div").find("span").text("Target value is required.");
				$("#targetTarget1_edit").focus();				
				return;
			}
			else{
				$("#targetTarget1_edit").closest("div").find("span").text("");
			}
			if($.trim($("#targetStartDate1_edit").val())==""){
				$("#targetStartDate1_edit").find("span").text("Target value is required.");
				$("#targetStartDate1_edit").focus();				
				return;
			}
			else{
				$("#targetStartDate1_edit").closest("div").find("span").text("");
			}
		}
		if($("#spilt_edit").is(":checked")){
			if($.trim($("#new_sales_edit").val())==""){
				$("#new_sales_edit").closest("div").find("span").text("value is required.");
				$("#new_sales_edit").focus();				
				return;
			}
			else{
				$("#new_sales").closest("div").find("span").text("");
			}
			if($.trim($("#sale_date_edit").val())==""){
				$("#sale_date_edit").find("span").text("Date is required.");
				$("#sale_date_edit").focus();				
				return;
			}
			else{
				$("#sale_date_edit").closest("div").find("span").text("");
			}
			if($.trim($("#up_sales_edit").val())==""){
				$("#up_sales_edit").closest("div").find("span").text("value is required.");
				$("#up_sales_edit").focus();				
				return;
			}
			else{
				$("#up_sales_edit").closest("div").find("span").text("");
			}
			if($.trim($("#sale_Udate_edit").val())==""){
				$("#sale_Udate_edit").find("span").text("Date is required.");
				$("#sale_Udate_edit").focus();				
				return;
			}
			else{
				$("#sale_Udate_edit").closest("div").find("span").text("");
			}
			if($.trim($("#cross_sales_edit").val())==""){
				$("#cross_sales_edit").closest("div").find("span").text("value is required.");
				$("#cross_sales_edit").focus();				
				return;
			}
			else{
				$("#cross_sales_edit").closest("div").find("span").text("");
			}
			if($.trim($("#sale_Cdate_edit").val())==""){
				$("#sale_Cdate_edit").find("span").text("Date is required.");
				$("#sale_Cdate_edit").focus();				
				return;
			}
			else{
				$("#sale_Cdate_edit").closest("div").find("span").text("");
			}
		}


		var targetObj = {};
		var product_ids = [];
		product_ids.push($("#multiselect_edit1").val());
		console.log(product_ids)
		var period = $("#targetPeriod_edit").val();
		var target_type = $("#salerep_target_edit").val();
		var target_currency = $("#targetCurrency_edit").val();
		var rep_id=$("#salerep_id").val();    
		var target_id=$("#target_id").val();  
		targetObj = {
				"product_ids": product_ids,
				"period" : period,
				"target_type": target_type,
				"target_currency": target_currency,
				"rep_id":rep_id,
				"target_id":target_id
			};      
		targetObj["category_details"] = {};
		if($("#spilt_edit").is(":checked")){
			targetObj["category"] = 'split';			
			var new_target_value = $("#new_sales_edit").val();
			var new_target_date = $("#sale_date_edit").val();
			var up_target_value = $("#up_sales_edit").val();
			var up_target_date = $("#sale_Udate_edit").val();
			var cross_target_value = $("#cross_sales_edit").val();
			var cross_target_date = $("#sale_Cdate_edit").val();
			targetObj["category_details"]["new"] = {"value":new_target_value, "start_date":new_target_date};
			targetObj["category_details"]["up"] = {"value":up_target_value, "start_date":up_target_date};
			targetObj["category_details"]["cross"] = {"value":cross_target_value, "start_date":cross_target_date};
		} 
		if($("#consol_edit").is(":checked")){
			targetObj["category"] = 'consolidated';

			var common_target_value = $("#targetTarget1_edit").val();
			var common_target_date = $("#targetStartDate1_edit").val();
			targetObj["category_details"]["common"] = {"value":common_target_value, "start_date":common_target_date};
		}console.log(targetObj);
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_salesRepresentativeController/update_target');?>", 
			data:JSON.stringify(targetObj),
			dataType : 'json',
			cache : false,
			success : function(data){
				$("#tablebody").empty();
				console.log(data)
				var target_row="";
						//console.log(data[0].product_name);						
					   for(var i=0;i<data.length; i++){	
					   		var rowdata=JSON.stringify(data[i]);
							var target1 = JSON.parse(data[i].target_data);
							console.log(target1)
							if(target1.category == "split"){
								$(".target_table").show();
								target_row +='<tr><td>'+(i+1)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + target1.target_type+'</td><td>' + 'new' +'</td><td>' + target1.category_details.new.start_date + '</td><td>'+target1.category_details.new.value + '</td><td>'+ target1.target_currency + "</td><td><a data-toggle='modal' href='#edit_target' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
								
								target_row +='<tr><td>'+''+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + target1.target_type+'</td><td>' + 'up' +'</td><td>' + target1.category_details.up.start_date + '</td><td>'+target1.category_details.up.value + '</td><td>'+ target1.target_currency + "</td><td>" + "</td></tr>";
								
								target_row +='<tr><td>'+''+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + target1.target_type+'</td><td>' + 'cross' +'</td><td>' + target1.category_details.cross.start_date + '</td><td>'+target1.category_details.cross.value + '</td><td>'+ target1.target_currency + "</td><td>" + "</td></tr>";
							}if(target1.category == "consolidated"){
								$(".target_table").show();
								target_row +='<tr><td>'+(i+1)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + target1.target_type+'</td><td>' + target1.category + '</td><td>' + target1.category_details.common.start_date + '</td><td>'+target1.category_details.common.value + '</td><td>'+target1.target_currency + "</td><td><a data-toggle='modal' href='#edit_target' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
							}
							
						 }
						$("#tablebody").append(target_row);
						$("#edit_target").modal("hide");
			   }	
		});
		   
	}                
	$(document).ready(function(){
		load();
		/*date field starts*/		  
		$("#startDateTimePicker").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY'
		});
		$("#targetStartDate1_edit1").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY'
		});
		$("#up_sale_date1").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY'
		});
		$("#new_sale_date1").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY'
		});
		$("#cross_sale_date1").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY'
		});
		$("#sale_date_edit1").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY'
		});
		$("#sale_Udate_edit1").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY'
		});
		$("#sale_Cdate_edit1").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'DD-MM-YYYY'
		});
		  /*date field ends*/
		$("#salerep_target").on("change", function(){
			var target = $("#salerep_target").val();
			if(target == "numbers"){
				$('.targetrow').show();
				$('.targetrow3').show();
				$('.targetrow1').show();
				$('.cons_spli').show();
			}else if(target == "revenue"){
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
				$("#spilt").prop("checked",false);
				$("#consol").prop("checked",true);
			}
		});
		$(".cons_spli input[type=radio]").click(function(){
			if($("#spilt").is(":checked")){
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
    arr.rep_id="<?php echo $result;?>";    
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('manager_salesRepresentativeController/get_rep_data');?>",
		data:JSON.stringify(arr),                        
		dataType : 'json',
		cache : false,
		success : function(data){
			console.log(data);
			$("#rep_email").html(JSON.parse(data[0].email));
			$("#rep_name").html(data[0].repname);
			$("#rep_dep").html(data[0].Department_name);
			$("#salerep_team_name").html(data[0].teamname);
			$("#salerep_person_reprtg").html(data[0].manager);
			$("#salerep_region").html(data[0].region);
			$("#rep_loc").html(data[0].location);
			$("#salerep_DOB").html(moment(data[0].dob,'YYYY-MM-DD').format('DD-MM-YYYY'));
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
			$("#rep_name").val(data[0].rep_id);
			$("#targetCurrency").val(data[0].currency);
			$("#salerep_id").val(data[0].rep_id);	
			var multipl=""	
			var multipl1=""	
            var target_row="";	
			var addObj1={};
			addObj1.rep_id = $("#salerep_id").val();
			/* var addObj={};
			addObj.team_id =$("#salerep_teamid").val(); */
            $.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_salesRepresentativeController/get_rep_products');?>",
			data:JSON.stringify(addObj1),
			dataType : 'json',
			cache : false,
			success : function(data){
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
			
				
            /* $.ajax({
					type : "POST",
					url : "<?php echo site_url('manager_salesRepresentativeController/get_rep_products');?>", 
					data:JSON.stringify(addObj1),
					dataType : 'json',
					cache : false,
					success : function(data){
					console.log(data);
					for(var i=0;i<data.length; i++){
						multipl +='<li><label> '+data[i].product_name+'<label></li>';	
						multipl1 +='<li><label><input type="checkbox" value="'+data[i].product_id+'">  '+data[i].product_name+'<label></li>';	
						team.push(data[i].product_id,data[i].product_name);
					}		
					$(".multiselect ul").append(multipl);					
					$(".multiselect1 ul").append(multipl1);
					$(".multiselect ul li input[type=checkbox]").prop('checked', true);
				}		  
				}); */
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('manager_salesRepresentativeController/get_target');?>", 
					data:JSON.stringify(addObj1),
					dataType : 'json',
					cache : false,
					success : function(data){
						console.log(data);
						var target_row="";
						//console.log(data[0].product_name);						
					   for(var i=0;i<data.length; i++){	
					   		var rowdata=JSON.stringify(data[i]);
							var target1 = JSON.parse(data[i].target_data);
							console.log(target1)
							if(target1.category == "split"){
								$(".target_table").show();
								target_row +='<tr><td>'+(i+1)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + target1.target_type+'</td><td>' + 'new' +'</td><td>' + target1.category_details.new.start_date + '</td><td>'+target1.category_details.new.value + '</td><td>'+ data[i].currency_name + "</td><td><a data-toggle='modal' href='#edit_target' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
								
								target_row +='<tr><td>'+''+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + target1.target_type+'</td><td>' + 'up' +'</td><td>' + target1.category_details.up.start_date + '</td><td>'+target1.category_details.up.value + '</td><td>'+ data[i].currency_name + "</td><td>" + "</td></tr>";
								
								target_row +='<tr><td>'+''+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + target1.target_type+'</td><td>' + 'cross' +'</td><td>' + target1.category_details.cross.start_date + '</td><td>'+target1.category_details.cross.value + '</td><td>'+ data[i].currency_name + "</td><td>" + "</td></tr>";
							}if(target1.category == "consolidated"){
								$(".target_table").show();
								target_row +='<tr><td>'+(i+1)+'</td><td>' + data[i].product_name + '</td><td>' + target1.period + '</td><td>' + target1.target_type+'</td><td>' + target1.category + '</td><td>' + target1.category_details.common.start_date + '</td><td>'+target1.category_details.common.value + '</td><td>'+data[i].currency_name + "</td><td><a data-toggle='modal' href='#edit_target' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
							}
							
						 }
						$("#tablebody").append(target_row);
					}
							  
				});
				
					
			}
		});
}        
		$.ajax({
			type: "POST",
			url:"<?php echo base_url('js/currencylist.json'); ?>",
			dataType:'json',
			success: function(data){
				var select = $("#callCurrency"), options = "<option value=''>select</option>";
				select.empty();      
				for(var i=0;i<data.length; i++){
				options += "<option value='"+data[i].value+"'>"+ data[i].innertext +"</option>";              
			}
			select.append(options);
			}
		});
        $.ajax({
			type: "POST",
			url:"<?php echo base_url('js/currencylist.json'); ?>",
			dataType:'json',
			success: function(data){
				var select = $("#smsCurrency"), options = "<option value=''>select</option>";
				select.empty();      
				for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].value+"'>"+ data[i].innertext +"</option>";              
				}
				select.append(options);
			}
		});	
	
		function update_rep(){
			var addObj={};
			addObj.team_id =$("#salerep_teamid").val();
			$("#salerep_target").prop("disabled", false);
			$("#callCurrency").prop("disabled", false);
			$("#callCost").prop("disabled", false);
			$("#smsCurrency").prop("disabled", false);
			$("#smsCost").prop("disabled", false);
			$("#Rooster").prop("disabled", false); 
			$("#add_target").show();
			$("#rep_update").show();
		}
		function target_btn(){
			$(".target_area").show();
			var addObj={};
			addObj.rep_id = $("#salerep_id").val();     
			 $.ajax({
				type : "POST",
				url : "<?php echo site_url('manager_salesRepresentativeController/getTargetProducts');?>", 
				data:JSON.stringify(addObj),
				dataType : 'json',
				cache : false,
				success : function(data){

				var select = $("#targetProduct"), options = "<option value=''>select</option>";
				select.empty();      
				for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].product_id+"'>"+ data[i].product_name +"</option>";              
				}
				select.append(options);	

				}		  
			}); 
		}
		function add_pro(){
			$("#product_add").modal('show');
            var addObj={};
			addObj.team_id =$("#salerep_teamid").val();
			 $.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_salesRepresentativeController/get_products');?>",
			data:JSON.stringify(addObj),
			dataType : 'json',
			cache : false,
			success : function(data){
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
					for(var y=0;y<cur.length;y++){	
						for(var z=0;z<cur1.length;z++){
							if($.trim(cur[y]) == $.trim(cur1[z])){
								$("#currency_value .multiselect2 ul li input[value='"+cur[y]+"']").prop('checked', true);
							}
						}
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
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('manager_salesRepresentativeController/save_products');?>",
				data:JSON.stringify(addObj),                        
				dataType : 'json',
				cache : false,
				success : function(data){
					if(data=="false"){
						alert("Products are assigned");
						$("#product_add").modal('hide');
					}else{
						//window.location.reload();
					}					
				}
			});
		} 
		function selrow(obj){
			var target1 = JSON.parse(obj.target_data);		
			console.log(obj.product_id)
			var addObj={};
			get_currency1(obj.product_id,target1.target_currency);
			$("#multiselect_edit").text(obj.product_name);
			$("#multiselect_edit1").val(obj.product_id);			
			$("#targetPeriod_edit").val(target1.period);
			$("#salerep_target_edit").val(target1.target_type);
			//$("#targetCurrency_edit").val(target1.target_currency);
			$("#targetCurrency_edit option[value='"+target1.target_currency+"']").attr('checked', true);
			addObj.rep_id =$("#salerep_id").val();
			addObj.target_id =$("#target_id").val(obj.target_id);
			if(target1.category=='split'){
				console.log(obj.category)
				$("#spilt_edit").prop("checked",true);
				$(".target_sales_type1").show();
				$(".consol_target").hide();
				$("#up_sales_edit").val(target1.category_details.up.value);
				$("#new_sales_edit").val(target1.category_details.up.value);
				$("#cross_sales_edit").val(target1.category_details.up.value);
				$("#sale_Udate_edit").val(target1.category_details.up.start_date);
				
			}if(target1.category=='consolidated'){
				$("#consol_edit").prop("checked",true);
				$(".target_sales_type1").hide();
				$(".consol_target").show();
				$("#targetTarget1_edit").val(target1.category_details.common.value);
				$("#targetStartDate1_edit").val(target1.category_details.common.start_date);
			}
			$(".cons_spli_edit input[type=radio]").click(function(){
				if($("#spilt_edit").is(":checked")){
					$(".target_sales_type1").show();
					$(".consol_target").hide();
				}else{
					$(".target_sales_type1").hide();
					$(".consol_target").show();
				}
			});			
			 $.ajax({
				type : "POST",
				url : "<?php echo site_url('manager_salesRepresentativeController/update_target');?>",
				data:JSON.stringify(addObj),                        
				dataType : 'json',
				cache : false,
				success : function(data){
					
					}
			}); 
		} 
     
	
	function back(){
        var site_url = "<?php echo site_url('manager_salesRepresentativeController/');?>";
        window.location.href = site_url;
    }function pro_btn(){
        $("#add_product").show();
        $(".edit_product").hide();
    }	
	function get_currency(cur_id){
		var addObj={};
		addObj.product_id=cur_id;
		//alert(addObj.product_id);
		$.ajax({
				type: "POST",
				url:"<?php echo site_url('manager_salesRepresentativeController/getTargetCurrency');?>",
				dataType:'json',
				data:JSON.stringify(addObj),
				success: function(data){
				
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
	function get_currency1(cur_id,pro){
		var addObj={};
		addObj.product_id=cur_id;
		console.log(pro);
		$.ajax({
				type: "POST",
				url:"<?php echo site_url('manager_salesRepresentativeController/getTargetCurrency');?>",
				dataType:'json',
				data:JSON.stringify(addObj),
				success: function(data){
					console.log(data)
					var select = $("#targetCurrency_edit"), options = "<option value=''>select</option>";
					select.empty();      
					for(var i=0;i<data.length; i++){
						options += "<option value='"+data[i].currency_id+"'>"+ data[i].currency_name +"</option>";              
					}
					select.append(options);
					$("#targetCurrency_edit option[value='"+pro+"']").attr('selected', true);
				}
			});	
	}
	</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">   
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
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pageHeader1 aa">
							<h2 >Sales Representative Information</h2>	
					</div>					
					<div style="clear:both"></div>
				</div>
				<div class="row row_rep" align="center">
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label for="Name">Name</label>
					</div>
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label id="rep_name" ></label> 
						<input type="hidden" id="rep_id" name=""></input>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4 ">
						<label>Department</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
						<label id="rep_dep"></label>   
					</div>
				</div>
				<div class="row row_rep" align="center">
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label>Team</label>
					</div>
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label id="salerep_team_name"></label>
                        <input type="hidden" id="salerep_teamid" name="" />
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4 ">
						<label>Reporting INTO </label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
						<label id="salerep_person_reprtg"></label>
					</div>
				</div>
				<div class="row row_rep" align="center">
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label>Designation</label>
					</div>
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label id="salerep_designation" ></label>
					</div>	
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4 ">
						<label>Location</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
						<label id="rep_loc"></label>
					</div>
				</div>
				<div class="row row_rep" align="center">
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label>Date Of Birth</label>
					</div>
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label id="salerep_DOB"></label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4 ">
						<label>Gender</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
						<label id="rep_gender"></label>
					</div>
				</div>
				<div class="row row_rep" align="center">
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label>Mobile No</label>
					</div>
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label id="salerep_Mobile"></label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4 ">
						<label>Email ID</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
						<label id="rep_email" ></label> 
					</div>
				</div>
				<div class="row row_heading row_rep" align="center">
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
				<div class="row row_heading row_rep" align="center">
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-6">
						<label><b>Office Address</b></label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-6">
						<label><b>Residence Address</b></label>
					</div>
				</div>
				<div class="row row_rep" align="center">
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-6">
						<label id="salerep_client_address" for="Name"></label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-6">
						<label id="rep_resaddress" for="Name"></label>
					</div>
				</div>
				<div class="row row_heading row_rep" align="center">
					<div class="col-md-11 col-xs-11 col-sm-11 col-lg-11">
						<label><b>User Functions</b></label>
					</div>
					<div class="col-md-1 col-xs-1 col-sm-1 col-lg-1">
						<a onclick='update_rep()'><span class='glyphicon glyphicon-pencil sec_edit'></span></a>
					</div>
				</div>
				<div class="row row_rep" align="center">
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label>Work Roster</label>
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
						<label>Expenses</label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
						<label id="salerep_expenses"></label>
					</div>
				</div>
				<div class="row row_rep" align="center">
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label>Travel Allowance</label>
					</div>
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label id="salerep_travel"></label>
					</div>
					<div class="col-md-4 col-xs-4 col-sm-4 col-lg-4 ">
						<label>Cost/Outgoing Call/Min</label>
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
				<div class="row row_rep" align="center">
					<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2 ">
						<label>Cost/Outgoing SMS</label>
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
						<div class="text_r">
							<input type="button" class="btn none" onclick="target_btn()" id="add_target" href="#target_add" data-toggle="modal" value="Add Target"/>
						</div>
					</div>					
				</div>
				<div class="table-responsive">
						<table class="table target_table none">					
						<thead>  
							<tr>			
								<th class="table_header" >SL No</th>
								<th class="table_header" >Product</th>
								<th class="table_header" >Period</th>
								<th class="table_header" >Target Type</th>
								<th class="table_header" >Sales Type</th>
								<th class="table_header" >Start Date</th>
								<th class="table_header" >Target</th>
                                                                <th class="table_header" >Target Value</th>
								<th class="table_header" ></th>
							</tr>
						</thead> 
						<tbody id="tablebody">
						</tbody>
					</table>
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
									<div class="row targetrow ">
										<div class="col-md-2">
											<label for="targetProduct">Product</label> 
										</div>
										<div class="col-md-4">	
											<select name="targetProduct" class="form-control" id="targetProduct" onchange="get_currency(this.value)">
												<option value="">Choose Product</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label>Currency type</label> 
										</div>
										<div class="col-md-4">
											<select name="targetCurrency" class="form-control"  id="targetCurrency">
												<option value="">Choose Currency</option>
											</select>
											<input type="hidden" id="edit_cur_hidden"/>
											<span class="error-alert"></span>
										</div>
									</div>											
									<div class="row targetrow target_up">
										<div class="col-md-2">
											<label for="targetPeriod">Period</label> 
										</div>
										<div class="col-md-4">	
											<select name="targetPeriod" class="form-control" id="targetPeriod">
												<option value="">Choose</option>
												<option value="Monthly">Monthly</option>
												<option value="Quarterly">Quarterly</option>
												<option value="Half Yearly">Half Yearly</option>
												<option value="Yearly">Yearly</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label>Target type</label> 
										</div>
										<div class="col-md-4">	
											<select name="ContactLocation" id="salerep_target" class="form-control">
													<option value="">Choose</option>
													<option value="numbers">Numbers</option>
													<option value="revenue">Revenue</option>		
												</select>
												<span class="error-alert"></span>
										</div>										
									</div>
								</div>
								
								<div class="row targetrow3 text_c none">
									<div class="col-md-3">
									
									</div>
									<div class="col-md-6">
										<h4><b>Sales Type</b></h4>
									</div>
									<div class="col-md-3">
									
									</div>
								</div>
								<div class="row cons_spli none">
									<div class="col-md-3">
										<input type="radio" id="consol" name="radio1" checked /> <label for="consol">Consolidated</label>
									</div>
									
									<div class="col-md-3">
										<input type="radio" id="spilt" name="radio1" /> <label for="spilt">Split</label>
									</div>
									
								</div>
								<div class="row targetrow1 none">
									<div class="col-md-2">
										<label for="targetTarget1">Target</label> 
									</div>
									<div class="col-md-4">	
										<input type="text" class="form-control" name="targetTarget1" id="targetTarget1" />
										<input type="hidden" name="salerep_id" id="salerep_id">
										<span class="error-alert"></span>
									</div>
									<div class="col-md-2">
										<label for="targetStartDate1">Start Date</label> 
									</div>
									<div class="col-md-4">	
										 <div class='input-group date' id='startDateTimePicker'>
										   <input id="targetStartDate1" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
										   <span class="input-group-addon">
										   <span class="glyphicon glyphicon-calendar"></span>
										   </span>
										</div>
										
										<span class="error-alert"></span>
									</div>
									
								</div>
								<div class="target_sales_type none">
									<div class="row new_sale">
										<div class="col-md-2">
											<label for="new_sales">New Sales</label> 
										</div>
										<div class="col-md-4">	
											<input type="text" class="form-control" name="new_sales" id="new_sales" />
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="new_sale_date">Start Date</label> 
										</div>
										<div class="col-md-4">	
											<div class='input-group date' id='new_sale_date1'>
											   <input id="new_sale_date" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
											   <span class="input-group-addon">
											   <span class="glyphicon glyphicon-calendar"></span>
											   </span>
											</div>
											<span class="error-alert"></span>
										</div>
										
									</div>
									<div class="row up_sale ">
										<div class="col-md-2">
											<label for="up_sales">Up Sales</label> 
										</div>
										<div class="col-md-4">	
											<input type="text" class="form-control" name="up_sales" id="up_sales" />
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="up_sale_date">Start Date</label> 
										</div>
										<div class="col-md-4">
											<div class='input-group date' id='up_sale_date1'>
											   <input id="up_sale_date" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
											   <span class="input-group-addon">
											   <span class="glyphicon glyphicon-calendar"></span>
											   </span>
											</div>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row cross_sale ">
										<div class="col-md-2">
											<label for="cross_sales">Cross Sales</label> 
										</div>
										<div class="col-md-4">	
											<input type="text" class="form-control" name="cross_sales" id="cross_sales" />
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="cross_sale_date">Start Date</label> 
										</div>
										<div class="col-md-4">
											<div class='input-group date' id='cross_sale_date1'>
											   <input id="cross_sale_date" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
											   <span class="input-group-addon">
											   <span class="glyphicon glyphicon-calendar"></span>
											   </span>
											</div>
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
									<div class="row  ">
										<div class="col-md-2">
											<label for="target_value_edit">Product</label> 
										</div>
										<div class="col-md-4">	
											<label id="multiselect_edit"></label>
											<input type="hidden" id="multiselect_edit1" />
										</div>
										<div class="col-md-2">
											<label for="targetPeriod_edit">Period</label> 
										</div>
										<div class="col-md-4">	
											<select name="targetPeriod_edit" class="form-control" id="targetPeriod_edit">
												<option value="">Choose</option>
												<option value="Monthly">Monthly</option>
												<option value="Quarterly">Quarterly</option>
												<option value="Half Yearly">Half Yearly</option>
												<option value="Yearly">Yearly</option>
											</select>
											<span class="error-alert"></span>
										</div>
									</div>	
									<div class="row  target_up">
										<div class="col-md-2">
											<label for="salerep_target_edit">Target type</label> 
										</div>
										<div class="col-md-4">	
											<select name="salerep_target_edit" id="salerep_target_edit" class="form-control">
													<option value="">Choose</option>
													<option value="numbers">Numbers</option>
													<option value="revenue">Revenue</option>		
												</select>
												<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="targetCurrency_edit">Currency type</label> 
										</div>
										<div class="col-md-4">
											<select name="targetCurrency_edit" class="form-control"  id="targetCurrency_edit">
												
											</select>
											<input type="hidden" id="edit_cur_hidden"/>
											<span class="error-alert"></span>
										</div>
									</div>
								
								<div class="row  text_c">
									<div class="col-md-3">
									
									</div>
									<div class="col-md-6">
										<h4><b>Target Type</b></h4>
									</div>
									<div class="col-md-3">
									
									</div>
								</div>
								<div class="row cons_spli_edit">
									<div class="col-md-3">
										<input type="radio" id="consol_edit" name="radio1" checked /> <label for="consol_edit">Consolidated</label>
									</div>
									
									<div class="col-md-3">
										<input type="radio" id="spilt_edit" name="radio1" /> <label for="spilt_edit">Split</label>
									</div>
									
								</div>
								<div class="row consol_target">
									<div class="col-md-2">
										<label for="targetTarget1_edit">Target</label> 
									</div>
									<div class="col-md-4">	
										<input type="text" class="form-control" name="targetTarget1_edit" id="targetTarget1_edit" />
										<input type="hidden" name="salerep_id" id="salerep_id">
										<input type="hidden" name="target_id" id="target_id">
										<span class="error-alert"></span>
									</div>
									<div class="col-md-2">
										<label for="targetStartDate1_edit">Start Date</label> 
									</div>
									<div class="col-md-4">	
										<div class='input-group date' id='targetStartDate1_edit1'>
										   <input id="targetStartDate1_edit" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
										   <span class="input-group-addon">
										   <span class="glyphicon glyphicon-calendar"></span>
										   </span>
										</div>
										<span class="error-alert"></span>
									</div>
									
								</div>
								<div class="target_sales_type1 none">
									<div class="row new_sale">
										<div class="col-md-2">
											<label for="new_sales_edit">New Sales</label> 
										</div>
										<div class="col-md-4">	
											<input type="text" class="form-control" name="new_sales_edit" id="new_sales_edit" />
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="sale_date_edit">Start Date</label> 
										</div>
										<div class="col-md-4">
											<div class='input-group date' id='sale_date_edit1'>
											   <input id="sale_date_edit" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
											   <span class="input-group-addon">
											   <span class="glyphicon glyphicon-calendar"></span>
											   </span>
											</div>
											<span class="error-alert"></span>
										</div>
										
									</div>
									<div class="row up_sale ">
										<div class="col-md-2">
											<label for="up_sales_edit">Up Sales</label> 
										</div>
										<div class="col-md-4">	
											<input type="text" class="form-control" name="up_sales_edit" id="up_sales_edit" />
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="sale_Udate_edit">Start Date</label> 
										</div>
										<div class="col-md-4">	
											<div class='input-group date' id='sale_Udate_edit1'>
											   <input id="sale_Udate_edit" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
											   <span class="input-group-addon">
											   <span class="glyphicon glyphicon-calendar"></span>
											   </span>
											</div>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row cross_sale ">
										<div class="col-md-2">
											<label for="cross_sales_edit">Cross Sales</label> 
										</div>
										<div class="col-md-4">	
											<input type="text" class="form-control" name="cross_sales_edit" id="cross_sales_edit" />
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="sale_Cdate_edit">Start Date</label> 
										</div>
										<div class="col-md-4">
											<div class='input-group date' id='sale_Cdate_edit1'>
											   <input id="sale_Cdate_edit" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
											   <span class="input-group-addon">
											   <span class="glyphicon glyphicon-calendar"></span>
											   </span>
											</div>
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
