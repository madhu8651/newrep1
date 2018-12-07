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
                                        <label for="edit_primemail">Email 1 </label> 
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
                                    <div class="col-md-2 ">
                                    </div>
                                    <div class="col-md-4">
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
/*------------------------------Add Photo Starts----------------------------------------------------------------*/
var obj_val = '', indus_id = [], indus_loc = [];
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
/*------------------------------Add Photo Ends----------------------------------------------------------------*/
function industryData(obj){	
	$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_customerController/getIndustry')?>",
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
}
function locationData(obj){		
	$.ajax({
        type: "POST",
        url: "<?php echo site_url('manager_customerController/getLocation')?>",
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
}
/*------------------------------Edit Customer Starts----------------------------------------------------------------*/
function selrow(obj){
	console.log(obj)
$('.error-alert').text("");
var obj1 = {};
industryData(obj);
locationData(obj);
if(obj.rep_industry != null){
	indus_id = obj.rep_industry.split(",");
}
if(obj.rep_location != null){
	indus_loc = obj.rep_location.split(",");
}
$('.custom-file-upload').find('i').remove();
if(obj.customer_logo !="" && obj.customer_logo !=null){
	$("#leadAvrtEdit").attr("src", "<?php echo site_url()?>/uploads/"+obj.customer_logo);
}else{
	$("#leadAvrtEdit").attr("src", "<?php echo site_url()?>/uploads/default-pic.jpg");
}
obj1.customerid=obj.customer_id;
obj.customer_remarks = window.atob(obj.customer_remarks);
obj.customer_address = window.atob(obj.customer_address);
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
	url: "<?php echo site_url('manager_customerController/customFieldCustomer');?>",
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

 $.ajax({
        type: "POST",
        url: "<?php echo site_url('manager_customerController/getCountry')?>",
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
                url : "<?php echo site_url('manager_customerController/getState')?>",
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
                    url: "<?php echo site_url('manager_customerController/getState')?>",
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
            url: "<?php echo site_url('manager_customerController/getContactType')?>",
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
            url: "<?php echo site_url('manager_customerController/getProductData'); ?>",
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

		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_customerController/getLeadSource'); ?>",
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
		});
 
}
function assignment_close(){
	$("#assignment_error").modal("hide");	
}
function edit_save(){
	console.log(indus_id)
	console.log($("#edit_industry").val())
	var result = '', flag = 0, flag1 = 0;
	if(indus_id.length > 0 && $("#edit_industry").val()!=""){
		for(i = 0; i < indus_id.length; i++){
			if(indus_id[i] == $("#edit_industry").val()){
				flag = 1;
			}
		}
		if(flag == 0){
			$("#assignment_error").modal("show");
			result = "The assigned executive cannot handle the industry you have chosen. Reassign the customer to the right executive or add the chosen industry to the current executive on the Admin Console.";
			$(".assignment_body").empty().append(result);
			return;
		}
	}
	if(indus_loc.length > 0 && $("#edit_business_location").val()!=""){
		for(i = 0; i < indus_loc.length; i++){
			if(indus_loc[i] == $("#edit_business_location").val()){
				flag1 = 1;
			}
			if(flag1 == 0){
				$("#assignment_error").modal("show");
				result = "The assigned executive cannot handle the location you have chosen. Reassign the customer to the right executive or add the chosen location to the current executive on the Admin Console.";
				$(".assignment_body").empty().append(result);
				return;
			}
		}
	}
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
	
	/* -----------------------------------------------------------------888888888888888888888888888888888 */
	if($.trim($("#edit_address").val()) != ""){
		if(!comment_validation($.trim($("#edit_address").val()))){
			$("#edit_address").closest("div").find("span").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
			$("#edit_address").focus();
			return;
		}else{
			$("#edit_address").closest("div").find("span").text(" ");
		} 
	}else{
		$("#edit_address").closest("div").find("span").text(" ");
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
        url : "<?php echo site_url('manager_customerController/postUpdateInfo')?>",
        dataType : 'json',
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){			
			if(error_handler(data)){
				return;
			}else if(data=="exists"){
				loaderHide();
				alert("This Customer Name is already exists.");
			}else{
				$("#customerinfoedit").modal("hide");
				alert("Data has been updated successfully.");
				var fileurl = "<?php echo site_url("manager_customerController/file_upload/"); ?>"+addObj.customerid;
				uploadImage(fileurl, 'edit_photo');
			}
			
		}
	});	 
}
/*----------------------------------------------Edit Customer Ends----------------------------------------------------------------*/
/*----------------------------------------------Map Starts----------------------------------------------------------------*/
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
/*----------------------------------------------Map Ends----------------------------------------------------------------*/
</script>