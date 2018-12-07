<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPquJYJq7KSiQPchdgioEVs-xOY4ERUdE&libraries=places" async defer></script>
<div id="leadinfoedit" class="modal fade" data-backdrop="static"  data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancelCust()">x</span>
					<h4 class="modal-title"><b>Edit <span id="edit_lead"></span></b></h4>
						<input type="hidden" id="custom_lead_id" />
				</div>
				<div class="modal-body">								
					<div class="row">
						<div class="col-md-2 ">
							<label for="edit_leadname">Lead Name*</label> 
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" id="edit_leadname" name="edit_leadname" autofocus>
							<input type="hidden" class="form-control" id="leadid" name="leadid" >
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="edit_leadweb">Lead Website</label> 
						</div>
						<div class="col-md-4">
							<input type="text"  class="form-control" id="edit_leadweb" name="edit_leadweb" autofocus>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<label for="edit_leadmail">Lead Email</label> 
						</div>
						<div class="col-md-4">
							<input type="text"name="adminContactDept" class="form-control" id="edit_leadmail" name="edit_leadmail" autofocus>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="edit_leadphone">Lead Phone</label> 
						</div>
						<div class="col-md-4">
							<input type="text"class="form-control" id="edit_leadphone" name="edit_leadphone" autofocus>											
							<span class="error-alert"></span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2">
							<label for="edit_product">Product</label> 
						</div>
						<div class="col-md-4" id="edit_product" name="edit_product">
						   
							<span class="error-alert"></span>
						</div>
						<div class="col-md-6">
							<label id="tree_lead1" >
								<a href="#" >Lead Source<b class="glyphicon glyphicon-menu-right" style="position: absolute;top: 4px;"></b>
								</a>
							</label>
						<div id="tree_leadsource1" class="tree-view" style="display:none"></div>      			
							<span class="error-alert"></span>
							<label class="leadsrcname"></label>
						</div>
						<div class="col-md-2 ">
							<label for="edit_displaypic">Photo</label> 
						</div>
						<div class="col-md-4">	
							<form method="POST" enctype="multipart/form-data" id="edit_photo" name="edit_photo">
								<input type="hidden" name="view_value" value="inprogress_lead"/>
								<label for="edit_pic" class="custom-file-upload"> 
									<img src="" title="Upload Contact Person's Photo" id="leadAvrtEdit" width="30px" height="30px"/>
								</label>
								<input type="file" class="form-control" accept="image/*"  name = "userfile" id="edit_pic" onchange="filevalidation('edit_pic','#leadAvrtEdit')"/>
								<span class="error-alert"></span>
							</form>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2" >
							<label for="edit_country">Country</label> 
						</div>
						<div class="col-md-4">
							<select  class="form-control" id="edit_country" name="edit_country"  autofocus>
							</select>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="edit_state">State</label> 
						</div>
						<div class="col-md-4">
							<select type="text" class="form-control" id="edit_state" name="edit_state" autofocus>
							</select>				
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<label for="edit_city">City</label> 
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" id="edit_city" autofocus>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="edit_zipcode">Zipcode</label> 
						</div>
						<div class="col-md-4">
							<input type="text"  class="form-control" id="edit_zipcode" name="edit_zipcode" autofocus>

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
							<label for="edit_business_location">Business Location</label> 
						</div>
						<div class="col-md-4">
						   <select  class="form-control" id="edit_business_location" name="edit_business_location" >
							</select>

							<span class="error-alert"></span>
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
					 <div class="row none" id="edit_map1">
						<div class="row none" id="edit_okmap">
							<center>
								<button type="button" class="btn"  onclick="googlemap('edit')">Google Map</button>
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
							<!--<button type="button"  class="btn btn-default" id="proceed" style="margin-top:0px" onclick="edit_showmap();">OK</button>-->
							<button type="button"  class="btn btn-default" id="proceed" style="margin-top:0px" onclick="show_map('#edit_map2','#edit_map1','#edit_selectmap','edit_long','edit_latt','edit_maploc');">OK</button>
						</div>
						</div>
					</div>
				  
					<div class="row" >
						<div class="col-md-12 lead_address">
							<center><b>Lead Contact Person Information</b></center>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2">
							<label for="edit_firstcontact">Contact Person*</label> 
						</div>
						<div class="col-md-4 ">
							<input type="text" class="form-control" id="edit_firstcontact" name="edit_firstcontact" autofocus>
							<input type="hidden"  id="employeeid" name="employeeid">
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="edit_disgnation">Designation</label> 
						</div>
						<div class="col-md-4">
							<input type="text"  class="form-control" id="edit_disgnation" name="edit_disgnation" autofocus>
							<span class="error-alert"></span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2">
							<label for="edit_primmobile">Primary Mobile Number*</label>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" id="edit_primmobile" autofocus>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="edit_primmobile2">Secondary Mobile Number</label>
						</div>
						<div class="col-md-4 ">
							<input type="text"  class="form-control" id="edit_primmobile2" name="edit_primmobile2" autofocus>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<label for="edit_primemail">Primary Email</label>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" id="edit_primemail" autofocus>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="edit_primemai2">Secondary Email</label>
						</div>
						<div class="col-md-4">
							<input type="text"  class="form-control" id="edit_primemai2" autofocus>
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
					<div class="row lead_contact_address" >
						<div class="col-md-2">
							<label for="Address">Address</label>  
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
							<textarea class="form-control" id="edit_contadd"></textarea>
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
					<button type="button" class="btn btn-default" onclick="edit_info()">Save</button>
					<button  type="button" class="btn btn-default" onclick="cancelCust()" >Cancel</button>
				</div>
		</div>
	</div>
</div>
<div id="assignment_error" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
				<div class="modal-header">
					<span class="close"  onclick="assignment_close()">&times;</span>
					<h4 class="modal-title"><b>Lconnectt</b></h4>
				</div>
				<div class="modal-body">
					<div class="row assignment_body">
							
					</div>
				</div>
				<div class="modal-footer">
						<input type="button" class="btn" onclick="assignment_close()" value="Cancel">
				</div>
		</div>
	</div>
</div>
<script>


/* ----------------------------------------------------------------------- */
/* -----------------------Edit lead Starts-------------------------------- */
/* ----------------------------------------------------------------------- */
var indus_id = [], indus_loc = [], assignProArray = [];
function industryData(obj){	
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('manager_leadController/getIndustry')?>",
		dataType:'json',
		success: function(data) {
			if(error_handler(data)){
					return;
			}
			var select = $("#edit_industry"), options = "<option value=''>select</option>";
			select.empty();      
			for(var i=0;i<data.length; i++){
				options += "<option value='"+data[i].industry_id+"'>"+ data[i].industry_name +"</option>"; 
			}
			select.append(options);
			$("#edit_industry option[value='"+obj.lead_industry+"']").attr("selected",true);
		}
	});
}	
function locationData(obj){
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('manager_leadController/getLocation')?>",
		dataType:'json',
		success: function(data) {
				if(error_handler(data)){
					return;
				}
				var select = $("#edit_business_location"), options = "<option value=''>select</option>";
				select.empty();      
				for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].business_location_id+"'>"+ data[i].business_location_name +"</option>"; 
				}
				select.append(options);
				$("#edit_business_location option[value='"+obj.lead_business_loc+"']").attr("selected",true);

		}
	});
}	
function selrow(obj){
	console.log(obj)	
	industryData(obj);
	locationData(obj);
	if(obj.rep_industry != null){
		indus_id = obj.rep_industry.split(",");
	}
	if(obj.rep_location != null){
		indus_loc = obj.rep_location.split(",");
	}	
	if(obj.rep_product != null){
		assignProArray = obj.rep_product.split(",");
	}
	var obj1 = {};
	obj1.leadid=obj.leadid;
	$('.custom-file-upload').find('i').remove();
	if(obj.leadlogo !="" && obj.leadlogo !=null){
		$("#leadAvrtEdit").attr("src", "<?php echo site_url()?>/uploads/"+obj.leadlogo);
	}else{
		$("#leadAvrtEdit").attr("src", "<?php echo site_url()?>/uploads/default-pic.jpg");
	}
	/*
	obj.repremarks = window.atob(obj.repremarks); 
	obj.leadtaddress = window.atob(obj.leadtaddress);
	obj.contPrsnAdd = window.atob(obj.contPrsnAdd);
	*/
	
	obj.repremarks = window.atob(unescape(decodeURIComponent(obj.repremarks)));
	obj.leadtaddress = window.atob(unescape(decodeURIComponent(obj.leadtaddress)));
	obj.contPrsnAdd = window.atob(unescape(decodeURIComponent(obj.contPrsnAdd)));
	
	$("#edit_product").html("");
	$("#edit_leadname").val(obj.leadname);
	$("#edit_leadweb").val(obj.leadwebsite);
	$("#edit_leadmail").val(obj.leademail);
	$("#edit_leadphone").val(obj.leadphone);
	$("#edit_city").val(obj.city);
	$("#edit_product").val(obj.product);
	$("#edit_leadsource").val(obj.leadsource);
	$("#edit_country").val(obj.leadcountry);
	$("#edit_state").val(obj.state);
	$("#edit_zipcode").val(obj.zipcode);
	$("#edit_ofcadd").val(obj.leadtaddress);
	$("#edit_splcomments").val(obj.repremarks);
	$("#edit_disgnation").val(obj.employeedesg);
	$("#edit_primmobile").val(obj.employeephone1);
	$("#edit_primmobile2").val(obj.employeephone2);
	$("#edit_primemail").val(obj.employeeemail);
	$("#edit_primemai2").val(obj.employeeemail2);
	$("#edit_firstcontact").val(obj.employeename);
	$("#edit_contacttype").val(obj.contacttypeid);
	$("#leadid").val(obj.leadid);
	$("#employeeid").val(obj.employeeid);
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('manager_leadController/customFieldLead');?>",
		data:JSON.stringify(obj1),
		dataType:'json',
		success: function(data) {
			$("#custom_fields").empty();
			var rowdata1=data.leadCustom;
			console.log(data)	
			if(error_handler(data)){
				return;
			}
			if(rowdata1.length>0){
				$("#custom_head").show();
				for(i=0;i<rowdata1.length;i++){
					$("#custom_lead_id").val(rowdata1[i].id);
					if(rowdata1[i].attribute_type=="Single_Line_Text"){
						$("#custom_fields").append("<div class='col-md-2'><label>"+rowdata1[i].attribute_name+"</label></div><div class='col-md-4'><input type='text' class='form-control custom_fld_lead' id='"+rowdata1[i].attribute_key+"' value='"+rowdata1[i].attribute_value+"' /></div>");
					}
				}			
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
			
			if(obj.hasOwnProperty("coordinate")== false){
				associated_plugin_used(data[0].plugin_id , "edit" , ",")
			}else(
				associated_plugin_used(data[0].plugin_id , "edit" , obj.coordinate)
			)
		}
	});
	
	
	$("#edit_lead").text(obj.leadname);
	$("#logg").val(obj.leadid);
	$("#edit_contadd").val(obj.contPrsnAdd);
	
	$("#edit_industry").val(obj.lead_industry);
	$("#edit_business_location").val(obj.lead_business_loc);
	var img_path=(obj.leadphoto);		

	$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/get_country');?>",
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
					return;
				}
				var select = $("#edit_country"), options = "<option value=''>select</option>";
				select.empty();      
				for(var i=0;i<data.length; i++)	{
					options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>"; 

				}
				select.append(options);
				$("#edit_country option[value='"+obj.leadcountry+"']").attr("selected",true);
			}
	});
	
	var id= obj.leadcountry;
	$.ajax({ 
			type : "POST",
			url : "<?php echo site_url('manager_leadController/get_state'); ?>",
			data : "id="+id,
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
				var select = $("#edit_state"), options = "<option value=''>Select</option>";
				select.empty();      
				for(var i=0;i<data.length; i++)	{
					options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
				}
				select.append(options);
				$("#edit_state option[value='"+obj.state+"']").attr("selected",true);
			}
	});

	$('#edit_country').on('change',function(){
		var id= this.value; 
		$.ajax({
				type: "POST",
				url: "<?php echo site_url('manager_leadController/get_state'); ?>",
				data : "id="+id,
				dataType:'json',
				success: function(data) {
					if(error_handler(data)){
						return;
					}
					var select = $("#edit_state"), options = "<option value=''>select</option>";
					select.empty();      
					for(var i=0;i<data.length; i++){
						options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";	
					}
					select.append(options);
				}
		});
	});
	var id= obj.leadid;
		$.ajax({
				type: "POST",
				url: "<?php echo site_url('manager_leadcontroller/get_productselected'); ?>",
				dataType:'json',
				success: function(data){
					var flag = 0, flag1 = 0;
					if(error_handler(data)){
						return;
					}
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('manager_leadcontroller/product_array'); ?>",
							data : "id="+id,
							dataType:'json',
							success: function(saved_data) {
								if(error_handler(data)){
									return;
								}
								console.log(data);
								$("#edit_product").html("");
								var currencyhtml="";
								currencyhtml +='<div id="product_value1" class="multiselect">';
								currencyhtml +='<ul>';
								for(var j=0;j<data.length; j++){
									flag = flag + 1;
									var bmatch = 0;
									for(var p=0;p<saved_data.length; p++){
										if(saved_data[p].product_id==data[j].product_id){
											bmatch = 1;
											break;
										}                        
									}
									if($("#manager_lead").val() == 'Assign'){
										if(bmatch == 1){
											currencyhtml +='<li><label><input type="checkbox" id="'+data[j].product_id+'" checked value="'+data[j].product_id+'">  '+data[j].product_name+'<label></li>';
										}else{
											currencyhtml +='<li><label><input type="checkbox" id="'+data[j].product_id+'" value="'+data[j].product_id+'" >  '+data[j].product_name+'<label></li>';
										}
									}else{
										if(bmatch == 1){
											currencyhtml +='<li><label><input type="checkbox" id="'+data[j].product_id+'" checked value="'+data[j].product_id+'" disabled>  '+data[j].product_name+'<label></li>';
										}else{
											currencyhtml +='<li><label><input type="checkbox" id="'+data[j].product_id+'" value="'+data[j].product_id+'" disabled>  '+data[j].product_name+'<label></li>';
										}
									}
									
								}
								currencyhtml +='</ul>';
								currencyhtml +='</div>';
								if($("#manager_lead").val() == 'Assign'){
									currencyhtml +='';
								}else{
									currencyhtml +='<div style="color: #d22929;"><i class="fa fa-ban fa-rotate-90" aria-hidden="true" ></i>' +
								" Choosing products depends on executive's permissions. Add them in Admin Console or reassign the prospect.</div>";
								}
								
								$("#edit_product").append(currencyhtml);
								for(var j=0;j<data.length; j++){
									for(i = 0; i < assignProArray.length; i++){
										if(data[j].product_id == assignProArray[i]){
											flag1 = flag1 + 1;
											$("#"+data[j].product_id).prop("disabled", false);
										}
									}
								}
								if(flag > flag1){
									$(".alertProduct").show();
								}else{
									$(".alertProduct").hide();
								}
							}
						});
				}
		});

		$.ajax({
				type: "POST",
				url: "<?php echo site_url('manager_leadController/contacttype'); ?>",
				dataType:'json',
				success: function(data) {
					if(error_handler(data)){
						return;
					}
					var select = $("#edit_contacttype"), options = "<option value=''>select</option>";
					select.empty();      
					for(var i=0;i<data.length; i++){
						options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
					}
					select.append(options);
					$("#edit_contacttype option[value='"+obj.contacttypeid+"']").attr("selected",true);
				}
		});

	$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_leadController/leadsource_edit'); ?>",
			dataType : 'json',
			data :  "id="+id,
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
				edit_tree(data,"tree_leadsource1" );			
				var isInside = false;
				$("#tree_lead1, .leadsrcname").click(function () {
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
	});  


	
}
function assignment_close(){
	$("#assignment_error").modal("hide");	
}
function edit_info(){
	var leadsource1=[], result = '', flag = 0, flag1 = 0;
	if(indus_id.length > 0 && $("#edit_industry").val()!=""){
		for(i = 0; i < indus_id.length; i++){
			if(indus_id[i] == $("#edit_industry").val()){
				flag = 1;
			}
		}
		if(flag == 0){
			$("#assignment_error").modal("show");
			result = "The assigned executive cannot handle the industry you have chosen. Reassign the lead to the right executive or add the chosen industry to the current executive on the Admin Console.";
			$(".assignment_body").empty().append(result);
			return;
		}
	}
	if(indus_loc.length > 0 && $("#edit_business_location").val()!=""){
		for(i = 0; i < indus_loc.length; i++){
			if(indus_loc[i] == $("#edit_business_location").val()){
				flag1 = 1;
			}
		}
		if(flag1 == 0){			
			$("#assignment_error").modal("show");
			result = "The assigned executive cannot handle the location you have chosen. Reassign the lead to the right executive or add the chosen location to the current executive on the Admin Console.";
			$(".assignment_body").empty().append(result);
			return;
		}
	}
	if($.trim($("#edit_leadname").val())==""){
			$("#edit_leadname").closest("div").find("span").text("Lead name is required.");
			$("#edit_leadname").focus();
			return;
	}else if(!validate_name($.trim($("#edit_leadname").val()))){
			$("#edit_leadname").closest("div").find("span").text("No special characters allowed (except &, _,-,.)");
			$("#edit_leadname").focus();
			return;
	}else if(!firstLetter($.trim($("#edit_leadname").val()))){
			$("#edit_leadname").closest("div").find("span").text("First letter should not be Special character.");
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
	
	/* -----------------------------------------------------------------888888888888888888888888888888888 */
	if($.trim($("#edit_ofcadd").val()) != ""){
		if(!comment_validation($.trim($("#edit_ofcadd").val()))){
			$("#edit_ofcadd").closest("div").find("span").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
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
			$("#edit_splcomments").closest("div").find("span").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
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
			$("#edit_primmobile").closest("div").find("span").text("Mobile Number is required.");
			$("#edit_primmobile").focus();
			return;
	}else if(!validate_PhNo($("#edit_primmobile").val())){
			$("#edit_primmobile").closest("div").find("span").text("Enter 10 digit mobile number.");
			$("#edit_primmobile").focus();
			return;
	}else{
			$("#edit_primmobile").closest("div").find("span").text("");
	} 

	if($.trim($("#edit_primmobile2").val())!=""){
		if(!validate_PhNo($("#edit_primmobile2").val())){
				$("#edit_primmobile2").closest("div").find("span").text("Enter 10 digit mobile number.");
				$("#edit_primmobile2").focus();
				return;
		}else{
				$("#edit_primmobile2").closest("div").find("span").text("");
		} 
	} 

	if($.trim($("#edit_primemail").val())!=""){
			if(!validate_email($("#edit_primemail").val())){
					$("#edit_primemail").closest("div").find("span").text("Invalid email address");
					$("#edit_primemail").focus();
					return;
			}else{
					$("#edit_primemail").closest("div").find("span").text("");
			} 
	} 

	if($.trim($("#edit_primemai2").val())!=""){
			if(!validate_email($("#edit_primemai2").val())){
					$("#edit_primemai2").closest("div").find("span").text("Invalid email address");
					$("#edit_primemai2").focus();
					return;
			}else{
					$("#edit_primemai2").closest("div").find("span").text("");
			} 
	} 
	if($.trim($("#edit_pic").val())!=""){
		filevalidation('edit_pic','#leadAvrtEdit');
	}
	$("#tree_leadsource1 input[type=radio]").each(function(){
		if($(this).prop('checked') == true){
			leadsource1.push(($(this).val()));
		}
	});
	var prod=[];    
	$("#product_value1 li input[type=checkbox]").each(function(){
		if($(this).prop('checked')==true){
			prod.push($(this).val());
		}        
	}); 
	
	/* -----------------------------------------------------------------888888888888888888888888888888888 */
	if($.trim($("#edit_contadd").val()) != ""){
		if(!comment_validation($.trim($("#edit_contadd").val()))){
			$("#edit_contadd").closest("div").find("span").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
			$("#edit_contadd").focus();
			return;
		}else{
			$("#edit_contadd").closest("div").find("span").text(" ");
		} 
	}else{
		$("#edit_contadd").closest("div").find("span").text(" ");
	} 
	
	var addObj={};
	var mobiles=[];
	var emails=[], lead_custom=[];
	$("#custom_fields .col-md-4 .custom_fld_lead").each(function(){
		lead_custom.push({attribute_value:$(this).val(),attribute_key:$(this).attr("id")});
	});
	addObj.leadCustom = lead_custom;
	addObj.custom_lead_id = $("#custom_lead_id").val();
	console.log(addObj)
	addObj.product = prod;
	addObj.new_mob=$.trim($("#edit_primmobile").val());
	mobiles.push($.trim($("#edit_primmobile").val()));
	mobiles.push($.trim($("#edit_primmobile2").val()));
	emails.push($.trim($("#edit_primemail").val()));
	emails.push($.trim($("#edit_primemai2").val()));  
	addObj.emails=emails;
	addObj.mobiles=mobiles;
	if(compareContact(mobiles) == "match"){
		$("#edit_primmobile2").closest("div").find("span").text("Mobile Number 1 and Mobile Number 2 should not be same.");
		return;
	}else{
		$("#edit_primmobile2").closest("div").find("span").text("");
		
	}
	
	if(compareContact(emails) == "match"){
		$("#edit_primemai2").closest("div").find("span").text("Email 1 and Email 2 should not be same.");
		return;
	}else{
		$("#edit_primemai2").closest("div").find("span").text("");
	}
	addObj.leadname = $.trim($("#edit_leadname").val());
	addObj.leadwebsite = $.trim($("#edit_leadweb").val());
	addObj.leademail = $.trim($("#edit_leadmail").val());
	addObj.phone = $.trim($("#edit_leadphone").val());
	if(typeof(leadsource1[0])=='undefined'){
		addObj.source ="";
	}
	else{
		addObj.source = leadsource1[0];
	}   	
	addObj.country = $.trim($("#edit_country").val());
	addObj.state = $.trim($("#edit_state").val());
	addObj.city = $.trim($("#edit_city").val());
	addObj.zipcode = $.trim($("#edit_zipcode").val());
	addObj.ofcaddress = $.trim($("#edit_ofcadd").val());
	addObj.splcomments = $.trim($("#edit_splcomments").val());
	addObj.contactname = $.trim($("#edit_firstcontact").val());
	addObj.designation = $.trim($("#edit_disgnation").val());    
	addObj.contacttype = $.trim($("#edit_contacttype").val());
	var longitude = $.trim($("#edit_long").val());
	var lattitude = $.trim($("#edit_latt").val());
	addObj.leadid = $.trim($("#leadid").val());
	addObj.employeeid = $.trim($("#employeeid").val());
	addObj.business_location=$("#edit_business_location").val();
	addObj.industry_name=$("#edit_industry").val();
	addObj.coordinate=longitude+","+lattitude;
	addObj.contPrsnAdd=$.trim($("#edit_contadd").val());
	
	loaderShow();

	$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_leadController/update_lead'); ?>",
			dataType : 'json',
			data    : JSON.stringify(addObj),
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
				if(data=="exist"){
					alert("Lead name already exist");
					loaderHide();
				}
				
				if(data == "true"){
					var fileurl = "<?php echo site_url("manager_leadcontroller/file_upload/"); ?>"+addObj.leadid;
					uploadImage(fileurl, 'edit_photo');
				}	
			}
	});
}

	

/* ---------------------------------------------------------------------- */	
/* -----------------------Edit lead Ends-------------------------------- */	


</script>