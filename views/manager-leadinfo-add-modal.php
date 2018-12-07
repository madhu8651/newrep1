<div id="leadinfoAdd" class="modal fade" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="add_cancel()">x</span>
					<h4 class="modal-title"><b>Add Lead</b></h4>
				</div>
				<div class="modal-body">								
					<div class="row">
						<div class="col-md-2">
							
							<label for="leadname">Lead Name*</label> 
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" id="leadname" name="leadname" autofocus>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="leadweb">Lead Website</label> 
						</div>
						<div class="col-md-4">
							<input type="text"  class="form-control" id="leadweb" name="leadweb" autofocus>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<label for="leadmail">Lead Email</label> 
						</div>
						<div class="col-md-4">
							<input type="text" name="adminContactDept" class="form-control" id="leadmail" name="leadmail" autofocus>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="leadphone">Lead Phone</label> 
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" id="leadphone" name="leadphone" autofocus>											
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<label for="product">Product</label> 
						</div>
						<div class="col-md-4">
						   <div id="product">
						   </div>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-6">
							<label for="tree_leadsource"  id="tree_lead" >
								<a href="#" >Lead Source<b class="glyphicon glyphicon-menu-right" style="position: absolute;top: 4px;"></b></a>
							</label>
							<div id="tree_leadsource" class="tree-view" style="display:none"></div>
							<span class="error-alert"></span>
							<label class="leadsrcname"></label>
						</div>
						<div class="col-md-2">
							<label>Photo</label> 
						</div>
						<!--<div class="col-md-4">									
							<form method="POST" enctype="multipart/form-data" id="add_lead_photo_frm" name="edit_photo">
								<input type="hidden" name="view_value" value="inprogress_lead"/>
								<label for="add_lead_photo" class="custom-file-upload"> <i class="fa fa-cloud-upload"></i> Upload</label>
								<input type="file" class="form-control" accept="image/*"  name="userfile" id="add_lead_photo" onchange="filevalidation('add_lead_photo')"/>
								<span class="error-alert"></span>
							</form>
						</div>-->
						<div class="col-md-4">
							<form method="POST" enctype="multipart/form-data" id="add_photo" name="upload_photo">
								<input type="hidden" name="view_value" value="leadinfo_view"/>
								<label for="display_pic" class="custom-file-upload">
									<img src="" title="Upload Lead Photo" id="leadAvrtAdd" width="30px" height="30px"/>
									
								</label>
								<input type="file" class="form-control" accept="image/*"  name = "userfile" id="display_pic" onchange="filevalidation('display_pic','#leadAvrtAdd')"/>
								<span class="error-alert"></span>
							</form>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2" >
							<label for="country">Country</label> 
						</div>
						<div class="col-md-4">
							<select  class="form-control" id="country" name="country" onchange="change1()" autofocus>
							</select>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="state">State</label> 
						</div>
						<div class="col-md-4">
							<select type="text" class="form-control" id="state" name="state" autofocus>
							</select>				
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<label for="city">City</label> 
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" id="city" autofocus>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2 ">
							<label for="zipcode">Zipcode</label> 
						</div>
						<div class="col-md-4 ">
							<input type="text"  class="form-control" id="zipcode" name="zipcode" autofocus>

							<span class="error-alert"></span>
						</div>
					</div>
					  <div class="row">
						<div class="col-md-2">
							<label for="add_industry">Industry</label> 
						</div>
						<div class="col-md-4">
						   <select  class="form-control" id="add_industry" name="add_industry" >
							</select>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="add_business_location">Business Location</label> 
						</div>
						<div class="col-md-4">
						   <select  class="form-control" id="add_business_location" name="add_business_location" >
							</select>

							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row lead_address" >
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
							<center><b>Office Address</b></center>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6  ">
							<center><b>Special Comments</b></center>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
							<textarea class="form-control" id="ofcadd"></textarea>
							<span class="error-alert"></span>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
							<textarea class="form-control" id="splcomments"></textarea>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row" id="map1">
					<div class="row none" id="okmap">
						<center>
							<button type="button" class="btn" onclick="googlemap('add')">Google Map</button>
						</center>
					</div>
					 </div>
					<div class="row" id="map2" >
						<div class="row" id="maploc" style="width:100% px;height:150px;border:1px;">
						 </div>
					</div>
					<div class="row" id="select_map" >
						<div class="row" id="mapname" >
						 </div>
						<div class="row">
							<div class="col-md-1 ">
							<label for="search">Search</label> 
						</div>
						<div class="col-md-4 ">
							<input type="text" class="form-control" onfocusout="codeAddress('search','long','latt','mapname');" id="search" name="search" />
						</div>
						<div class="col-md-1">
							<label for="long">Longitude</label> 
						</div>
						<div class="col-md-2 ">
							<input type="text" class="form-control" id="long" name="long"/>
						</div>
						<div class="col-md-1">
							<label for="latt">Latitude</label> 
						</div>
						<div class="col-md-2 ">
							<input type="text" class="form-control" id="latt" name="latt" />
						</div>
						
						<div class="col-md-1 ">
							<button type="button"  class="btn btn-default" id="proceed" style="margin-top:0px" onclick="show_map('#map1','#map2','#select_map','long','latt','maploc');">OK</button>
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
							<label for="firstcontact">Contact Person*</label> 
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" id="firstcontact" name="firstcontact" autofocus>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2 ">
							<label for="disgnation">Designation</label> 
						</div>
						<div class="col-md-4">
							<input type="text"  class="form-control" id="disgnation" name="disgnation" autofocus>
							<span class="error-alert"></span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2">
							<label for="primmobile">Primary Mobile Number *</label>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" id="primmobile" autofocus>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="primmobile2">Secondary Mobile Number </label>
						</div>
						<div class="col-md-4">
							<input type="text"  class="form-control" id="primmobile2" name="primmobile" autofocus>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<label for="primemail"> Primary Email </label>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" id="primemail" autofocus>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="primemail2">Secondary Email</label>
						</div>
						<div class="col-md-4">
							<input type="text"  class="form-control" id="primemail2" autofocus>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<label for="contacttypes">Buyer Persona</label> 
						</div>
						<div class="col-md-4">
							<select class="form-control" id="contacttypes">
								
							</select>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row lead_contact_address" >
						<div class="col-md-2">
							<label for="Address">Address</label>  
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
							<textarea class="form-control" id="contadd"></textarea>
							<span class="error-alert"></span>
						</div>

					</div>
					<div class="add_leadContact">
					
					</div>
					<div class="row none" id="custom_add">
						<div class="col-md-12 lead_address">
							<center><b>Custom Fields</b></center>
						</div>
					</div>
					<div class="row" id="custom_add_fields">
					
					</div>	
				  </div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" onclick="save_leadinfo()">Save</button>
					<button  type="button" class="btn btn-default" onclick="add_cancel()" >Cancel</button>
				</div>
		</div>
	</div>
</div>
<script>
	var email_val = '';
	function edit_tree(data, container){
		$("#"+container).siblings('.leadsrcname').text('');
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
		$("#"+container+"  input[type=radio]").each(function( index ){
			
			if(index == 0){
				$(this).hide();
			}
			$(this).click(function(){
				if($(this).prop('checked')==true){
					$("#"+container).siblings('.leadsrcname').text($(this).closest('label').text());
				}
			})
			if($(this).prop('checked')==true){
				$("#"+container).siblings('.leadsrcname').text($(this).closest('label').text());
			}
		})
		$("#"+container).append("<center><button id='clearSeclection'>clear</button></center>");
		$('#clearSeclection').click(function(){
			$("#"+container).find('input[type=radio]').prop('checked', false);
			$("#"+container).siblings('.leadsrcname').text('');
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
</script>
<script>
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
			if(formid == "add_photo"){
				add_cancel()
			}else if(formid == "edit_photo"){
				cancelCust()
			}
			if(data== 1){
				if(email_val){
					$.alert({
						title: 'L Connectt',
						content: 'Data has been saved successfully.',
						closeIcon: true,
						closeIconClass: 'fa fa-close',
					});
					loaderHide();
				}else{
					pageload();
					$.alert({
						title: 'L Connectt',
						content: 'Data has been saved successfully.',
						closeIcon: true,
						closeIconClass: 'fa fa-close',
					});
				}
			}
		},
		error: function(data){
			network_err_alert(data);
		}

    });
}
function displayOnchange(input, displayArea, fileName) {
	var reader = new FileReader();
	reader.readAsDataURL(input.files[0]);
	reader.onload = function (e) {
		$('.custom-file-upload').find('i').remove();
		$('.custom-file-upload').append('<i>'+fileName+'</i>');
		$(displayArea).attr('src', e.target.result);
		
	}
}
function setDefaultImage(displayArea){
	$(displayArea).attr("src", "<?php echo site_url()?>uploads/default-pic.jpg");
	$('.custom-file-upload').find('i').remove();
	$('.custom-file-upload').append('<i>Upload Lead Photo</i>');
}
function filevalidation(input,displayArea){
	var elm = document.getElementById(input);
   
	if (elm.files && elm.files[0]){
		setDefaultImage(displayArea);  
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
			displayOnchange(elm,displayArea , elm.files[0].name);
			$("#"+input).closest('div').find('.error-alert').text("");
		}
	}
}
	function googlemap(section){
		if(section == "add"){
			$("#select_map").show();
			$("#map1").hide();
			$("#map2").hide();
			rendergmap('','','mapname','search','long','latt');
		}
		if(section == "edit"){
			$("#edit_selectmap").show();
			$("#edit_map2").hide();
			$("#edit_map1").hide();
			rendergmap('edit_map1','edit_map2','edit_mapname','edit_search','edit_long','edit_latt');
		}
	}
	var DetailsforValidation={};/*for duplicate contact check*/
	
	function add_lead(email_id){
		email_val = email_id;
		/* ----get contact information for duplicate check --Starts---------------- */
		if(email_id){
			$("#primemail").val(email_id);
		}else{
			$("#primemail").val("");
		}
		$.ajax({
			type: "POST",
			url:"<?php echo site_url('manager_leadController/DetailsforValidation')?>",
			dataType:'json',
			success: function(data){
				if(error_handler(data)){
					return;
				}
				data.forEach(function(element) {
					element.lead_number = JSON.parse(element.lead_number);
					element.contact_number = JSON.parse(element.contact_number);
					if(typeof(element.lead_number.phone) == 'string'){
						element.lead_number.phone = element.lead_number.phone.split('');
					}
					element.phone = element.lead_number.phone.concat(element.contact_number.phone);
				});
				DetailsforValidation = data;
				
			},
			error:function(data){
				network_err_alert();
			}
		});
		
		/* ----get contact information for duplicate check ----End ------------------ */
		setDefaultImage('#leadAvrtAdd');
		$("#leadinfoAdd .error-alert").empty();
		/* ---------------------------- */
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/AddCustomFiledLead');?>",
			dataType:'json',
			success: function(data) {
				$("#custom_add_fields").empty();
				var rowdata1=data.leadCustom;
				console.log(data)	
				if(error_handler(data)){
					return;
				}
				if(rowdata1.length>0){
					$("#custom_add").show();
					for(i=0;i<rowdata1.length;i++){
						if(rowdata1[i].attribute_type=="Single_Line_Text"){
							$("#custom_add_fields").append("<div class='col-md-2'><label>"+rowdata1[i].attribute_name+"</label></div><div class='col-md-4'><input type='text' class='form-control custom_fld_lead' id='"+rowdata1[i].attribute_key+"' value='"+rowdata1[i].attribute_value+"' /></div>");
						}
					}			
				}
			},
			error: function(data){
				network_err_alert(data);
			}
		});	
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/getIndustry')?>",
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
							return;
				}
				var select = $("#add_industry"), options = "<option value=''>select</option>";
				select.empty();      
				for(var i=0;i<data.length; i++)
				{
					options += "<option value='"+data[i].industry_id+"'>"+ data[i].industry_name +"</option>"; 
				}
				select.append(options);
			},
			error: function(data){
				network_err_alert(data);
			}
		});

		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/getLocation')?>",
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
							return;
				}
				var select = $("#add_business_location"), options = "<option value=''>select</option>";
				select.empty();      
				for(var i=0;i<data.length; i++)
				{
					options += "<option value='"+data[i].business_location_id+"'>"+ data[i].business_location_name +"</option>"; 
				}
			   select.append(options);

			},
			error: function(data){
				network_err_alert(data);
			}
		});

		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/get_product'); ?>",
			dataType:'json',
			success: function(data){
				if(error_handler(data)){
						return;
				}
				$("#product").html("");
				var currencyhtml="";
				currencyhtml +='<div id="product_value" class="multiselect">';
				currencyhtml +='<ul>';

				for(var j=0;j<data.length; j++){								
						currencyhtml +='<li><label><input type="checkbox" value="'+data[j].product_id+'">  '+data[j].product_name+'<label></li>';
				}
				currencyhtml +='</ul>';
				currencyhtml +='</div>';
				$("#product").append(currencyhtml)
			}
		});

		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_leadController/lead_source'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
				edit_tree(data,"tree_leadsource" );
			
				var isInside = false;
					
				$("#tree_lead, .leadsrcname").click(function () {
					$("#tree_leadsource").show();
				});
				
				$("#tree_leadsource, .leadsrcname").hover(function () {
					isInside = true;
				}, function () {
					isInside = false;
				})

				$(document).mouseup(function () {
					if (!isInside)
					$("#tree_leadsource").hide();
				});
			},
			error: function(data){
				network_err_alert(data);
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
				var select = $("#contacttypes"), options = "<option value=''>select</option>";
				select.empty();      
				for(var i=0;i<data.length; i++)
				{
					options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
				}
				select.append(options);

			},
			error: function(data){
				network_err_alert(data);
			}
		});
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/get_country');?>",
			dataType:'json',
			success: function(data) {
				/*$("#select_map").show();
				$("#map1").hide();
				$("#map2").hide();
				 rendergmap('mapname','search','long','latt'); */
				
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

			},
			error: function(data){
				network_err_alert(data);
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
				associated_plugin_used(data[0].plugin_id , "add", "")
				
			},
			error: function(data){
				network_err_alert(data);
			}
		});	
		$('#edit_selectmap,#edit_maploc,#edit_map1,#view_map_render,#select_map,#maploc,#map1').hide();
	}

	function associated_plugin_used(associated_plugin , section , savedCordinate){
		/* --------- hide/ show map section if user have Navigator module------------ */
		$('#edit_selectmap,#edit_maploc,#edit_map1,#view_map_render,#select_map,#maploc,#map1').hide();
		var pluginJSON= JSON.parse(associated_plugin);
		for(var key in pluginJSON){
			if(key == "Navigator" && pluginJSON[key].length > 0){
				$('#okmap,#select_map,#maploc').removeClass('none');
				$('#edit_selectmap,#edit_maploc,#view_map_render,#select_map').show();
				$('#edit_okmap,#edit_map2,#edit_map1').show();
				if(section == 'add'){
					rendergmap('', '', 'mapname','search','long','latt');
					
				}
				if(section == 'edit'){
					if(savedCordinate != ","){
						var latlng= savedCordinate;			
						var arr = latlng.split(',');	
						$("#edit_long").val(arr[0]);
						$("#edit_latt").val(arr[1]);
						show_map('#edit_map2','#edit_map1','#edit_selectmap','edit_long','edit_latt','edit_maploc');
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
						
						setTimeout(function(){
						   show_map(' ',' ',' ','view_long','view_latt','view_maploc');
						}, 2000);
						
					}
				}
				
			}
		}
		/* ------------------------------------ */
	}
	
	/* ----------------------------------------------------- */
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
	
	/* ----------------------------------------------------- */
	
	function save_leadinfo(){	
		
		/* -------------------------------------------------------------------------------------------------------- */
		$(".error-alert").text("");
		if($.trim($("#leadname").val())==""){
				$("#leadname").closest("div").find("span").text("Lead name is required.");
				$("#leadname").focus();
				return;
		}else if(!validate_name($.trim($("#leadname").val()))){
				$("#leadname").closest("div").find("span").text("No special characters allowed (except &, _,-,.)");
				$("#leadname").focus();
				return;
		}else if(!firstLetter($.trim($("#leadname").val()))){
				$("#leadname").closest("div").find("span").text("First letter should not be Special character.");
				$("#leadname").focus();
				return;
		}else{
				$("#leadname").closest("div").find("span").text("");
		}  
		
		if($.trim($("#leadweb").val())!=""){
				if(!validate_website($.trim($("#leadweb").val()))){
						$("#leadweb").closest("div").find("span").text("Invalid website address.");
						$("#leadweb").focus();
						return;
				}else{
						$("#leadweb").closest("div").find("span").text("");
				}  
		}

		if($.trim($("#leadmail").val())!=""){
				if(!validate_email($.trim($("#leadmail").val()))){
						$("#leadmail").closest("div").find("span").text("Invalid email address.");
						$("#leadmail").focus();
						return;
				}else{
						$("#leadmail").closest("div").find("span").text("");
				}  
		}

		if($.trim($("#leadphone").val())!=""){
				if(!validate_PhNo($.trim($("#leadphone").val()))){
						$("#leadphone").closest("div").find("span").text("Enter 10 digit mobile number.");
						$("#leadphone").focus();
						return;
				}else{
						$("#leadphone").closest("div").find("span").text("");
				}  
		}

		if($.trim($("#city").val())!=""){
				if(!validate_location($.trim($("#city").val()))){
						$("#city").closest("div").find("span").text("No special characters allowed (except &)");
						$("#city").focus();
						return;
				}else{
						$("#city").closest("div").find("span").text("");
				}  
		}
		if($.trim($("#zipcode").val())!=""){
			if(!validate_zip($.trim($("#zipcode").val()))){
				$("#zipcode").closest("div").find("span").text("Invalid zipcoce");
				$("#zipcode").focus();
				return;
			}else{
				$("#zipcode").closest("div").find("span").text("");
			}  
		}
		
		/* -----------------------------------------------------------------888888888888888888888888888888888 */
		if($.trim($("#ofcadd").val()) != ""){
			if(!comment_validation($.trim($("#ofcadd").val()))){
				$("#ofcadd").closest("div").find("span").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
				$("#ofcadd").focus();
				return;
			}else{
				$("#ofcadd").closest("div").find("span").text(" ");
			} 
		}else{
				$("#ofcadd").closest("div").find("span").text(" ");
		}  
		if($.trim($("#splcomments").val()) != ""){
			if(!comment_validation($.trim($("#splcomments").val()))){
				$("#splcomments").closest("div").find("span").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
				$("#splcomments").focus();
				return;
			}else{
				$("#splcomments").closest("div").find("span").text(" ");
			} 
		}else{
				$("#splcomments").closest("div").find("span").text(" ");
		} 
		
		if($.trim($("#firstcontact").val())==""){
			$("#firstcontact").closest("div").find("span").text("Contact Name is required.");
			$("#firstcontact").focus();
			return;
		}else if(!validate_name($.trim($("#firstcontact").val()))){
			$("#firstcontact").closest("div").find("span").text("No special characters allowed (except &, _,-,.)");
			$("#firstcontact").focus();
			return;
		}else if(!firstLetterChk($.trim($("#firstcontact").val()))){
			$("#firstcontact").closest("div").find("span").text("First letter should not be Numeric or Special character.");
			$("#firstcontact").focus();
			return;
		}else{
			$("#firstcontact").closest("div").find("span").text("");
		}          
		
		if($.trim($("#disgnation").val())!=""){
				if(!validate_name($.trim($("#disgnation").val()))){
						$("#disgnation").closest("div").find("span").text("No special characters allowed (except &, _,-,.)");
						$("#disgnation").focus();
						return;
				}else{
						$("#disgnation").closest("div").find("span").text("");
				}  
		}

		if($.trim($("#primmobile").val())==""){
				$("#primmobile").closest("div").find("span").text("Mobile Number is required.");
				$("#primmobile").focus();
				return;
		}else if(!validate_PhNo($("#primmobile").val())){
				$("#primmobile").closest("div").find("span").text("Enter 10 digit mobile number");
				$("#primmobile").focus();
				return;
		}else{
				$("#primmobile").closest("div").find("span").text("");
		} 

		if($.trim($("#primmobile2").val())!=""){
				if(!validate_PhNo($("#primmobile2").val())){
						$("#primmobile2").closest("div").find("span").text("Enter 10 digit mobile number");
						$("#primmobile2").focus();
						return;
				}else{
						$("#primmobile2").closest("div").find("span").text("");
				} 
		} 

		if($.trim($("#primemail").val())!=""){
				if(!validate_email($("#primemail").val())){
						$("#primemail").closest("div").find("span").text("Invalid email address");
						$("#primemail").focus();
						return;
				}else{
						$("#primemail").closest("div").find("span").text("");
				} 
		} 

		if($.trim($("#primemail2").val())!=""){
				if(!validate_email($("#primemail2").val())){
						$("#primemail2").closest("div").find("span").text("Invalid email address");
						$("#primemail2").focus();
						return;
				}else{
						$("#primemail2").closest("div").find("span").text("");
				} 
		}  
		/* -------------------------------------------------------------------------------------------------------- */
		if($.trim($("#display_pic").val())!=""){
			filevalidation('display_pic','#leadAvrtAdd');
		}
		var leadsource =[];
	    $("#tree_leadsource input[type=radio]").each(function(){
	        if($(this).prop('checked') == true){
	                leadsource.push($.trim($(this).val()));
	        }
	    });
    	var prod=[];
    	$("#product_value li input[type=checkbox]").each(function(){
	        if($(this).prop('checked')==true){
	            prod.push($(this).val());
	        }        
	    }); 

/* -----------------------------------------------------------------888888888888888888888888888888888 */
		if($.trim($("#contadd").val()) != ""){
			if(!comment_validation($.trim($("#contadd").val()))){
				$("#contadd").closest("div").find("span").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
				$("#contadd").focus();
				return;
			}else{
				$("#contadd").closest("div").find("span").text(" ");
			} 
		}else{
			$("#contadd").closest("div").find("span").text(" ");
		}
		
	    var addObj={};
	    var mobiles=[];
	    var emails=[];
	    var leademail=[];
	    addObj.product = prod;
	    addObj.new_mob=$.trim($("#primmobile").val());
	    mobiles.push($.trim($("#primmobile").val()));
	    mobiles.push($.trim($("#primmobile2").val()));
	    emails.push($.trim($("#primemail").val()));
	    emails.push($.trim($("#primemail2").val()));
		if(compareContact(mobiles) == "match"){
			$("#primmobile2").closest("div").find("span").text("Mobile Number 1 and Mobile Number 2 should not be same.");
			return;
		}else{
			$("#primmobile2").closest("div").find("span").text("");
			
		}
		
		if(compareContact(emails) == "match"){
			$("#primemail2").closest("div").find("span").text("Email 1 and Email 2 should not be same.");
			return;
		}else{
			$("#primemail2").closest("div").find("span").text("");
		}
	    addObj.emails=emails;
	    addObj.mobiles=mobiles;
	    addObj.leadname = $.trim($("#leadname").val());
	    addObj.leadwebsite = $.trim($("#leadweb").val());
	    leademail.push($.trim($("#leadmail").val()));
	    addObj.leademail =leademail;
	    addObj.phone = $.trim($("#leadphone").val());
	    if(typeof(leadsource[0])=='undefined'){
	   		addObj.source ="";
	   	}
	   	else{
	   		addObj.source = leadsource[0];
	   	}
		var lead_custom=[];
		$("#custom_add_fields .col-md-4 .custom_fld_lead").each(function(){
			lead_custom.push({attribute_value:$(this).val(),attribute_key: $(this).attr("id")});
		});
		addObj.leadCustom = lead_custom;
	    addObj.country = $.trim($("#country").val());
	    addObj.state = $.trim($("#state").val());
	    addObj.city = $.trim($("#city").val());
	    addObj.zipcode = $.trim($("#zipcode").val());
	    addObj.ofcaddress = $.trim($("#ofcadd").val());
	    addObj.splcomments = $.trim($("#splcomments").val());
	    addObj.contactname = $.trim($("#firstcontact").val());
    	addObj.designation = $.trim($("#disgnation").val());
	    addObj.contacttype = $.trim($("#contacttypes").val());
		var longitude = $.trim($("#long").val());
		var lattitude = $.trim($("#latt").val());
		addObj.add_industry= $("#add_industry").val();
		addObj.add_business_location=$("#add_business_location").val();
		addObj.coordinate=longitude+","+lattitude;
		addObj.contPrsnAdd=$.trim($("#contadd").val());
		
		/*------------------temp variable used for ---- duplicate checking----- Starts ----------*/
		var chk1 =[];
		var chk3 ={"leadname":[],"leadphone":[],"primmobile":[],"secondary":[]};

		/*------------------Duplicate name checking---------------*/
		DetailsforValidation.forEach(function(element) {
			if(element.lead_name == addObj.leadname){
				if(chk1.indexOf('nameChk') < 0){
					chk1.push('nameChk');
					if(chk3.leadname.indexOf($.trim(element.lead_name)) === -1){
						chk3.leadname.push(element.lead_name);
					}
				}
			}
			var leadphone = phnformat($.trim($("#leadphone").val()));
			var primmobile = phnformat($.trim($("#primmobile").val()));
			var primmobile2 = phnformat($.trim($("#primmobile2").val()));
			
			$.each( element.phone, function( i, phNo ){
				if(phNo != "" || leadphone != ""){
					if(phnformat(phNo)== leadphone){
						if(chk1.indexOf('LeadPhoneNumberChk') <= 0){
							chk1.push('LeadPhoneNumberChk');
							if(chk3.leadphone.indexOf($.trim(element.lead_name)) === -1){
								chk3.leadphone.push(element.lead_name);
							}
						}
					}
				}
				if(phNo != "" || primmobile != ""){
					if(phnformat(phNo)== primmobile){
						if(chk1.indexOf('primaryPhoneNumberChk') <= 1){
							chk1.push('primaryPhoneNumberChk');
							if(chk3.primmobile.indexOf($.trim(element.lead_name)) === -1){
								chk3.primmobile.push(element.lead_name);
							}							
						}
					}
				}
				if(phNo != "" || primmobile2 != ""){
					if(phnformat(phNo)== primmobile2){
						if(chk1.indexOf('secondaryPhoneNumberChk') <= 2){
							chk1.push('secondaryPhoneNumberChk');
							if(chk3.secondary.indexOf($.trim(element.lead_name)) === -1){
								chk3.secondary.push(element.lead_name);
							}
						}
					}
				}
			});
		});
		
		var msg = "",count = 0;
		if(chk3.leadname.length > 0){
			count++;
			msg += count+') Lead name already exists.<br>';
		}
		/* ----------------- */
		if(chk3.leadphone.length == 1){
			count++;
			msg += count+') This Lead contact number is already associated with '+chk3.leadphone+' Lead.<br>';
		}else if(chk3.leadphone.length > 1){
			count++;
			msg += count+') This Lead contact number is already associated with multiple Lead.<br>';
		}
		/* -------------------------------- */
		if(chk3.primmobile.length == 1){
			count++;
			msg += count+') This Primary contact number is already associated with '+chk3.primmobile+' Lead.<br>';
		}else if(chk3.primmobile.length > 1){
			count++;
			msg += count+') Primary contact number is already associated with multiple Lead.<br>';
		}
		/* ----------------------------------- */
		if(chk3.secondary.length == 1){
			count++;
			msg += count+') This Secondary contact number is already associated with '+chk3.secondary+' Lead.<br>';
		}else if(chk3.secondary.length > 1){
			count++;
			msg += count+') This Secondary contact number is already associated with multiple Lead.<br>';
		}
		if(count == 0){
			addLeadSubmit(JSON.stringify(addObj));
		}else{
			$.confirm({
				title: 'L Connectt',
				content: msg + '<br><br>Do you still wish to continue!',
				animation: 'none',
				closeAnimation: 'scale',
				closeIcon: true,
				closeIconClass: 'fa fa-close',
				buttons: {
					Ok: function () {
						addLeadSubmit(JSON.stringify(addObj));	 
					},
					Cancel: function () {
						
					}
				}
			});
		}
		
	}
	function addLeadSubmit(addObj){
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_leadController/post_leadinfo');?>",
			dataType : 'json',
			data    : addObj,
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
				if(data=="exist"){
					loaderHide();
					alert("Lead name already exist");	
				}
				if(data.status == "true"){
					var leadid = data['leadid'];
					var fileurl = "<?php echo site_url("manager_leadController/file_upload/"); ?>"+leadid;
					uploadImage(fileurl, 'add_photo');
				}	
			},
			error: function(data){
				network_err_alert(data);
			}
		});
	}
	/* taking last 10 digit of mobile number for duplicate check
	removing all special cherecter from the number  */
	function phnformat(contact){
		if(contact != "" && contact != null && contact != 'null' ){
			temp = contact.replace(/[+-. ]/g, "")
			return temp.substr(temp.length - 10);
		}else{
			return contact;
		}
	}
	
	function compareContact(contact){
		if(contact[0] != ""){
			if(contact[0] == contact[1]){
				return "match"
			}else{
				return "diff"
			}
		}else{
			return "diff"
		}
	}
/* -----------------------Add lead Ends-------------------------------- */	

	/* ---------------------------------- */
	function rendergmap(googleBtn,markerMap,mapname, searchBoxElm, longitude, lattitude) {
		if(googleBtn != ""){
			$("#"+googleBtn+", #"+ markerMap).hide();
		}
		var mapOptions = {
			center: new google.maps.LatLng(12.93325692, 77.57465679),
			zoom: 12,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var infoWindow = new google.maps.InfoWindow();
		var latlngbounds = new google.maps.LatLngBounds();
		var map = new google.maps.Map(document.getElementById(mapname), mapOptions);		
		var input = document.getElementById(searchBoxElm);
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
				document.getElementById(longitude).value = e.latLng.lat();
				document.getElementById(lattitude).value = e.latLng.lng();
			});
	}
	

	/* ------------------------------------------ */
	function show_map(map1, map2, selectmap, longitude, lattitude, maploc){
		if(map1 != " "){
			$(map1+","+map2).show();
		}
		if(selectmap != " "){
			$(selectmap).hide();
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
	/* -------------------------------------------- */
	function codeAddress(searchBox, longitude, lattitude, mapid) {
	    geocoder = new google.maps.Geocoder();
	    var address = document.getElementById(searchBox).value;
	    geocoder.geocode( { 'address': address}, function(results, status) {
	            if (status == google.maps.GeocoderStatus.OK) {
	                    document.getElementById(longitude).value = results[0].geometry.location.lat();
	                    document.getElementById(lattitude).value = results[0].geometry.location.lng();
	                    map_marker(longitude,lattitude, mapid);
	            }else {
	                    alert("Geocode was not successful for the following reason: " + status);
	            }
	    });
	}
	
	/* ----------------Set pointer in Map---------------------------- */
	function map_marker(longitude, lattitude, mapname){
		
		var lat=document.getElementById(longitude).value;
		var log=document.getElementById(lattitude).value;
		var myCenter=new google.maps.LatLng(lat,log);
		var mapProp = {
			center:myCenter,
			zoom:14,
			mapTypeId:google.maps.MapTypeId.ROADMAP
		};
		var map=new google.maps.Map(document.getElementById(mapname),mapProp);
		var marker=new google.maps.Marker({
			position:myCenter,
		});
		marker.setMap(map);
	}
</script>