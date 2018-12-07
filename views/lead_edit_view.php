<script>
function selrow(obj){
	$('.custom-file-upload').find('i').remove();
	if(obj.lead_logo !="" && obj.lead_logo !=null){
		$("#leadAvrtEdit").attr("src", "<?php echo site_url()?>/uploads/"+obj.lead_logo);
	}else{
		$('.custom-file-upload').append('<i>Upload Lead Photo</i>');
		$("#leadAvrtEdit").attr("src", "<?php echo site_url()?>/uploads/default-pic.jpg");
	}
	
	
    if( navigatorChk==1){
   $('#edit_selectmap').hide();
   $("#edit_map1").show();
   $("#edit_map2").show();
    var coordinates =obj.lead_location_coord; 
    if(coordinates!=null){
        var direction =coordinates.split(",");
        $("#edit_long").val(direction[0]);
        $("#edit_latt").val(direction[1]);
    }
     get_coordinate('edit_long','edit_latt','edit_maploc');
   $("#edit_okmap").click(function(){
        $("#edit_selectmap").show();
        $("#edit_map1").hide();
        $("#edit_map2").hide();
        render_map("edit_long","edit_latt","edit_mapname","edit_search");
    });
}else{
  $('#edit_map2').hide();
  $('#edit_selectmap').hide();
  $('#edit_map1').hide();
  $('#edit_maploc').hide();
  $("#edit_okmap").hide();
}

$("#edit_product").html("");
$("#edit_leadname").val(obj.lead_name);
$("#edit_leadweb").val(obj.lead_website);
$("#edit_leadmail").val(obj.leademail);
$("#edit_leadphone").val(obj.leadphone);
$("#edit_leadsource").val(obj.lead_source);
$("#edit_city").val(obj.lead_city);
$("#edit_zipcode").val(obj.lead_zip);
$("#edit_ofcadd").val(obj.lead_address);
$("#edit_splcomments").val(obj.lead_remarks);
$("#edit_disgnation").val(obj.contact_desg);
$("#edit_primmobile").val(obj.employeephone1);
$("#edit_primmobile2").val(obj.employeephone2);
$("#edit_primemail").val(obj.employeeemail);
$("#edit_primemail2").val(obj.employeeemail2);
$("#edit_firstcontact").val(obj.contact_name);
$("#employeeid").val(obj.contact_id);
$("#leadid").val(obj.lead_id);
var obj1={};
obj1.lead_id=obj.lead_id;
$.ajax({
	type: "POST",
	url: "<?php echo site_url('leadinfo_controller/getCustomData');?>",
	data:JSON.stringify(obj1),
	dataType:'json',
	success: function(data) {
		$("#custom_fieldsE").empty();
		console.log(data);	
		if(error_handler(data)){
			return;
		}
		if(data.length>0){
                    $("#custom_headE").show();
                    for(i=0;i<data.length;i++){
                        if(data[i].attribute_type=="Single_Line_Text"){
                            $("#custom_fieldsE").append("<div class='col-md-2'><label>"+data[i].attribute_name+"</label></div><div class='col-md-4'><input type='text' class='form-control custom_fldE' id='"+data[i].attribute_key+"' value='"+data[i].attribute_value+"' /></div>");
                        }
                    }
		}else{
                    $("#custom_headE").hide();
                }
	}
});	

 $.ajax({
            type : "POST",
            url : "<?php echo site_url('leadinfo_controller/get_leadDetails'); ?>",
            dataType : 'json',
            cache : false,
            success : function(data){
              if(error_handler(data)){
            return;
            }
                  /*-----------------------------------*/
               var select = $("#edit_country"), options = "<option value=''>select</option>";
               select.empty();      
                for(var i=0;i<data.country.length; i++)
               {
                    options += "<option value='"+data.country[i].lookup_id+"'>"+ data.country[i].lookup_value +"</option>";              
               }
               select.append(options);
               $("#edit_country option[value='"+obj.lead_country+"']").attr("selected",true);
               /*-----------------------------------*/
                var industry = $("#edit_industry"), industryoptions = "<option value=''>select</option>";
               industry.empty();      
                for(var i=0;i<data.industry.length; i++)
               {
                    industryoptions += "<option value='"+data.industry[i].map_id+"'>"+ data.industry[i].hvalue2 +"</option>";              
               }
               industry.append(industryoptions);
                 $("#edit_industry option[value='"+obj.lead_industry+"']").attr("selected",true);
           /*-----------------------------------*/
            var business = $("#edit_location"), businessoptions = "<option value=''>select</option>";
               business.empty();      
                for(var i=0;i<data.bussines.length; i++)
               {
                    businessoptions += "<option value='"+data.bussines[i].map_id+"'>"+ data.bussines[i].hvalue2 +"</option>";              
               }
               business.append(businessoptions);
             $("#edit_location option[value='"+obj.lead_business_loc+"']").attr("selected",true);
              /*-----------------------------------*/
              var contact = $("#edit_contacttype"), contactoptions = "<option value=''>select</option>";
               contact.empty();      
                for(var i=0;i<data.contacttype.length; i++)
               {
                    contactoptions += "<option value='"+data.contacttype[i].lookup_id+"'>"+ data.contacttype[i].lookup_value+"</option>";              
               }
               contact.append(contactoptions);
               
                $("#edit_contacttype option[value='"+obj.contact_type+"']").attr("selected",true);
               
               /*-----------------------------------*/
    } 
});
        var id= obj.lead_country;
                $.ajax({ 
                type : "POST",
                url : "<?php echo site_url('leadinfo_controller/get_state'); ?>",
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
                        $("#edit_state option[value='"+obj.lead_state+"']").attr("selected",true);

                 }
            });
            $('#edit_country').on('change',function(){
               var id= this.value; 
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('leadinfo_controller/get_state'); ?>",
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
              var id= obj.lead_id;
         $.ajax({
            type: "POST",
            url: "<?php echo site_url('leadinfo_controller/get_product'); ?>",
            dataType:'json',
            success: function(data){
              if(error_handler(data)){
            return;
            }
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('leadinfo_controller/product_array'); ?>",
                    data : "id="+id,
                    dataType:'json',
                    success: function(saved_data) {
                      if(error_handler(saved_data)){
                      return;
                      }
                    console.log(data);
                    $("#edit_product").html("");
                    var currencyhtml="";
                    currencyhtml +='<div id="product_valueE" class="multiselect">';
                    currencyhtml +='<ul>';

                    for(var j=0;j<data.length; j++){
                        var bmatch = 0;
                        for(var p=0;p<saved_data.length; p++){
                            if(saved_data[p].product_id==data[j].product_id){
                                bmatch = 1;
                                break;
                            }                        
                        }
                        if(bmatch == 1){
                            currencyhtml +='<li><label><input type="checkbox" checked value="'+data[j].product_id+'">  '+data[j].product_name+'<label></li>';
                        }else{
                            currencyhtml +='<li><label><input type="checkbox" value="'+data[j].product_id+'">  '+data[j].product_name+'<label></li>';
                        }
                    }
                    currencyhtml +='</ul>';
                    currencyhtml +='</div>';
                    $("#edit_product").append(currencyhtml)
                   }
                });
                 
            }
        });
         $.ajax({
                type : "POST",
                url : "<?php echo site_url('leadinfo_controller/leadsource_edit'); ?>",
                dataType : 'json',
                data :  "id="+id,
                cache : false,
                success : function(data){
                  if(error_handler(data)){
                    return;
                    }
                    edit_tree(data,"edit_leadsource");
                    var isInside = false;
			
					$("#edit_lead_source ,.leadsrcname").click(function () {
						$("#edit_leadsource").show();
					});
					
					$("#edit_leadsource ,.leadsrcname").hover(function () {
						isInside = true;
					}, function () {
						isInside = false;
					})

					$(document).mouseup(function () {
						if (!isInside)
				        	$("#edit_leadsource").hide();
					});
                }
        });
       
 }
function edit_save(){
   $(".error-alert").text("");
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
	
	if($.trim($("#edit_ofcadd").val())!=""){
            if(!comment_validation($.trim($("#edit_ofcadd").val()))){
                    $("#edit_ofcadd").closest("div").find("span").text("No special characters allowed (except $ & : ( ) # @ _ . , + % ? -)");
                    $("#edit_ofcadd").focus();
                    return;
            }else{
                    $("#edit_ofcadd").closest("div").find("span").text("");
            }  
    }
	if($.trim($("#edit_splcomments").val())!=""){
            if(!comment_validation($.trim($("#edit_splcomments").val()))){
                    $("#edit_splcomments").closest("div").find("span").text("No special characters allowed (except $ & : ( ) # @ _ . , + % ? -)");
                    $("#edit_splcomments").focus();
                    return;
            }else{
                    $("#edit_splcomments").closest("div").find("span").text("");
            }  
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
            $("#edit_primmobile").closest("div").find("span").text("please enter 10 digit mobile number");
            $("#edit_primmobile").focus();
            return;
    }else{
            $("#edit_primmobile").closest("div").find("span").text("");
    } 

    if($.trim($("#edit_primmobile2").val())!=""){
            if(!validate_PhNo($("#edit_primmobile2").val())){
                    $("#edit_primmobile2").closest("div").find("span").text("Please enter 10 digit mobile number");
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

    if($.trim($("#edit_primemail2").val())!=""){
            if(!validate_email($("#edit_primemail2").val())){
                    $("#edit_primemail2").closest("div").find("span").text("Invalid email address");
                    $("#edit_primemail2").focus();
                    return;
            }else{
                    $("#edit_primemail2").closest("div").find("span").text("");
            } 
    }     
      if($.trim($("#edit_primmobile").val())!=""){
        var mob1=$("#edit_primmobile").val();
        var mob2=$("#edit_primmobile2").val();
        if(mob1==mob2){
            $("#edit_primmobile").closest("div").find("span").text("Both primary and secondary number should not be same.");
            $("#edit_primmobile").focus();
            return;
        }else{
            $("#edit_primmobile").closest("div").find("span").text("");
        }
    }
    if($.trim($("#edit_primemail").val())!=""){
        var email1=$("#edit_primemail").val();
        var email2=$("#edit_primemail2").val();
        if(email1==email2){
            $("#edit_primemail").closest("div").find("span").text("Both primary and secondary Email Id should not be same.");
            $("#edit_primemail").focus();
            return;
        }else{
            $("#edit_primemail").closest("div").find("span").text("");
        }
    }
    if($.trim($("#edit_pic").val())!=""){
         filevalidation('edit_pic');
     }
    
    var prodE=[];
    $("#product_valueE li input[type=checkbox]").each(function(){
        if($(this).prop('checked')==true){
            prodE.push($(this).val());
        }        
    });
     var  lead_customE=[];
     $("#custom_fieldsE .col-md-4 .custom_fldE").each(function(){
            var key = $(this).attr("id");
            lead_customE.push({attribute_value:$(this).val(),attribute_key:key});
    });
    var mobilesE=[];
    var emailsE=[];
    var leadmobileE=[];
    var leademailE=[];
    var leadsourceE="";
    $("#edit_leadsource input[name=Editsource]").each(function(){
        if($(this).prop('checked')==true){
            leadsourceE=$(this).val();
        }        
    });
    mobilesE.push($.trim($("#edit_primmobile").val()));
    mobilesE.push($.trim($("#edit_primmobile2").val()));
    emailsE.push($.trim($("#edit_primemail").val()));
    emailsE.push($.trim($("#edit_primemail2").val()));
    leadmobileE.push($.trim($("#edit_leadphone").val()));
    leademailE.push($.trim($("#edit_leadmail").val()));
    var longitude = $.trim($("#long").val());
    var lattitude = $.trim($("#latt").val());
    var addObj={};
    addObj.leadname = $.trim($("#edit_leadname").val());
    addObj.leadwebsite = $.trim($("#edit_leadweb").val());
    addObj.leademail = $.trim($("#edit_leadmail").val());
    addObj.phone = $.trim($("#edit_leadphone").val());
    addObj.product = prodE;
    addObj.leadsource = leadsourceE;
    addObj.country = $.trim($("#edit_country").val());
    addObj.state = $.trim($("#edit_state").val());
    addObj.city = $.trim($("#edit_city").val());
    addObj.zipcode = $.trim($("#edit_zipcode").val());
    addObj.ofcaddress = $.trim($("#edit_ofcadd").val());
    addObj.splcomments = $.trim($("#edit_splcomments").val());
    addObj.contactname = $.trim($("#edit_firstcontact").val());
    addObj.designation = $.trim($("#edit_disgnation").val());
    addObj.contacttype = $.trim($("#edit_contacttype").val());
    addObj.longitude = $.trim($("#edit_long").val());
    addObj.lattitude = $.trim($("#edit_latt").val());
    addObj.leadid = $.trim($("#leadid").val());
    addObj.employeeid = $.trim($("#employeeid").val());
    addObj.industry = $.trim($("#edit_industry").val());
    addObj.business = $.trim($("#edit_location").val());
    addObj.email=emailsE;
    addObj.mobile=mobilesE;
    addObj.coordinate=longitude+","+lattitude;
    addObj.edit_custom= lead_customE;
    loaderShow();
     $.ajax({
        type : "POST",
        url : "<?php echo site_url('leadinfo_controller/update_lead'); ?>",
        dataType : 'json',
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){
          if(error_handler(data)){
            return;
            }
                if(data==0){
                 alert("This Lead name already exists.");
                 return false;
                }else{
                   alert("Data has been updated successfully.");
                   var leadid = data['leadid'];
                   var fileurl = "<?php echo site_url("leadinfo_controller/file_upload/"); ?>"+leadid;
                   uploadImage(fileurl, 'edit_photo');
                }
        }
     });	
}
    </script>
    <div id="leadinfoedit" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
          
                            <div class="modal-header">
                                <span class="close" onclick="cancel()">x</span>
                                <h4 class="modal-title"><b>Edit Lead</b></h4>
                            </div>
                            <div class="modal-body">								
                                <div class="row">
                                    <div class="col-md-2 ">
                                        <label for="edit_leadname">Lead Name*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_leadname" name="edit_leadname" >
                                        <input type="hidden" class="form-control" id="leadid" name="leadid" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_leadweb">Lead Website</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="edit_leadweb" name="edit_leadweb" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_leadmail">Lead Email</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"name="adminContactDept" class="form-control" id="edit_leadmail" name="edit_leadmail" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_leadphone">Lead Phone</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"class="form-control" id="edit_leadphone" name="edit_leadphone" >											
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_product">Product</label> 
                                    </div>
                                    <div class="col-md-4" id="edit_product">
                                        <span class="error-alert"></span>
                                    </div>
									<div class="col-md-6">
										<label id="edit_lead_source" >
											<a href="#" >Lead Source
												<b class="glyphicon glyphicon-menu-right"></b>
											</a>
										</label>
										<div id="edit_leadsource" class="tree-view" style="display:none"></div>
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
											<img src="" title="Upload Lead Photo" id="leadAvrtEdit" width="30px" height="30px"/>
										</label>
										<input type="file" class="form-control" accept="image/*"  name = "userfile" id="edit_pic" onchange="filevalidation('edit_pic', '#leadAvrtEdit')"/>
										<span class="error-alert"></span>
										</form>
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
                                        <select class="form-control" id="edit_industry" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_location">Business Location</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="edit_location" name="edit_location" >
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
                                 <div class="row" id="edit_map1">
                                <div class="row">
                                        <center>
                                                <button type="button" class="btn" id="edit_okmap" >Google Map</button>
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
                                        <input type="text" class="form-control" onfocusout="search_location('edit_long','edit_latt','edit_search','edit_mapname')" id="edit_search" name="edit_search" />
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
                                        <button type="button"  class="btn btn-default" id="proceed" style="margin-top:0px" onclick="get_coordinate('edit_long','edit_latt','edit_maploc')">OK</button>
                                    </div>
                                    </div>
                                </div>
                                <div class="row" >
                                    <div class="col-md-12 lead_address">
                                        <center><b>Lead Contact Information</b></center>
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
                                        <label for="edit_primmobile">Primary Mobile Number*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_primmobile" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_primmobile2">Secondary Mobile Number</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <input type="text"  class="form-control" id="edit_primmobile2" name="edit_primmobile2" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_primemail">Primary Email</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_primemail" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_primemai2">Secondary Email</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="edit_primemail2" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                   
                                    <div class="col-md-2 ">
                                        <label for="edit_contacttype">contact Type</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="edit_contacttype">
                                            
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row none" id="custom_headE">
                                    <div class="col-md-12 lead_address">
                                        <center><b>Custom Fields</b></center>
                                    </div>
                                </div>
				<div class="row" id="custom_fieldsE">
								
                                </div>
                              </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" onclick="edit_save()">Save</button>
                                <button  type="button" class="btn btn-default" onclick="cancel()" >Cancel</button>
                            </div>
                        
                    </div>
                </div>
            </div>