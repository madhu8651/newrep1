<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<style>
#invalid_list table tr th div{
	width:250px
}
.table-bordered,.table-bordered > tbody > tr > th,.table-bordered > tbody > tr > td{ border:1px solid #ccc!important; }
</style>



<script>
var count = "", row_value = '', left_lead = "";

function counterList(){
	$("#counterList").modal("hide");
	$("#progressbar1").text("");
	location.reload();
}
function addExl(){
    
	if(moduleName == "Admin"){
		$(".button_fetch").show();
	}
	if(versiontype != "premium"){
		if(count == 0 && moduleName == "Admin"){
			alert("Your upload limit is exceeded");
			return;
		}
	}
	/* if(versiontype == "lite" && moduleName != "Admin"){
		alert('This feature is not available in Lite Version!');
		return;
	} */
	
	$("#modal_upload").modal("show");
	$(".progress").addClass('none');
    $("#modal_upload").modal("show");
	$(".progress .progress-bar.progress-bar-success").text("").css("width" , "0%");
	$(".progress .progress-bar.progress-bar-danger").text("").css("width" , "0%");
}
function lead_count(){
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('excelUploadController/getLeadsToAddCount'); ?>",
		dataType : 'json',
		cache : false,
		success : function(data){
			if(error_handler(data)){
			  return;
			}
			count = row_value - parseInt(data[0].count);
			if(count < 0){
				count = 0;
			}
			console.log(count);
            addExl();
		}
	});

}
function clear_loc(){
	if(moduleName == "Admin"){
		$("#bus_loc").val("");
		$("#user_list").empty();
		$(".user_section ").hide();
	}
}
function clear_user(){
	if(moduleName == "Admin"){
		$("#user_list").empty();
		$(".user_section ").hide();
	}
}
function user_list(){
	var obj = {};
	if($.trim($("#industry").val()) == ""){
		$("#industry").closest("div").find("span").text("Industry is required");
		return;
	}else{
		$("#industry").closest("div").find("span").text("");
	}
	if($.trim($("#bus_loc").val()) == ""){
		$("#bus_loc").closest("div").find("span").text("Location is required");
		return;
	}else{
		$("#bus_loc").closest("div").find("span").text("");
	}
	obj.industry = $("#industry").val();
	obj.business_loc = $("#bus_loc").val();
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('excelUploadController/getUsersToAssign'); ?>",
		dataType : 'json',
		data: JSON.stringify(obj),
		cache : false,
		success : function(data){
			console.log(data)
			if(error_handler(data)){
			  return;
			}
			var userhtml="";
			userhtml +='<ul>';

			for(var j=0;j<data.length; j++){	
					if(data[j].manager != 0){
						userhtml +='<li><label><input type="checkbox" id ="'+data[j].user_id+'" value="Manager">  '+data[j].user_name+'<label></li>';
					}else{
						userhtml +='<li><label><input type="checkbox" id="'+data[j].user_id+'" value="Sales">  '+data[j].user_name+'<label></li>';
					}					
			}
			userhtml +='</ul>';
			$("#user_list").empty().append(userhtml)
			$(".user_section").show();
		}
	});
}
$(document).ready(function(){
		var url_link1, url_link2, url_link3, url_link4;
		if(moduleName == "Admin"){
			url_link1 = "<?php echo site_url('excelUploadController/lead_source');?>";
			url_link2 = "<?php echo site_url('excelUploadController/getLocation');?>";
			url_link3 = "<?php echo site_url('excelUploadController/getIndustry');?>";
			url_link4 = "<?php echo site_url('excelUploadController/get_data');?>";
		}else{
			url_link1 = "<?php echo site_url('manager_leadController/lead_source');?>";
			url_link2 = "<?php echo site_url('manager_leadController/getLocation');?>";
			url_link3 = "<?php echo site_url('manager_leadController/getIndustry');?>";
			url_link4 = "<?php echo site_url('manager_leadController/get_data');?>";
		}
      $.ajax({
            type : "POST",
            url : url_link1,
            dataType : 'json',
            cache : false,
            success : function(data){
                if(error_handler(data)){
                  return;
                }
                edit_tree(data,"leadsource_excel_upload" );
		
				var isInside = false;
					
				$("#excel_upload_tree_lead").click(function () {
					$("#leadsource_excel_upload").show();
				});
				
				$("#leadsource_excel_upload").hover(function () {
					isInside = true;
				}, function () {
					isInside = false;
				})
				/*-----get the count of lead-------*/
				if(moduleName == "Admin"){
					lead_count();
					if(versiontype=='lite'){
						row_value = 50;
					}else if(versiontype=='standard'){
						row_value = 500;
					}
					$(".button_fetch").show();
				}else{
					$(".user_section").hide();
					$(".button_fetch").hide();
				}				
				$(document).mouseup(function () {
					if (!isInside)
					$("#leadsource_excel_upload ").hide();
				});
            }
        });
        $.ajax({
            type : "POST",
            url : url_link2,
            dataType : 'json',
            cache : false,
            success : function(data){
                if(error_handler(data)){
                  return;
                }
            var business = $("#bus_loc"), businessoptions = "<option value=''>select</option>";
            business.empty();      
             for(var i=0;i<data.length; i++)
            {
                 businessoptions += "<option value='"+data[i].business_location_id+"'>"+ data[i].business_location_name +"</option>";              
            }
            business.append(businessoptions);
            }
        });
        $.ajax({
            type : "POST",
            url : url_link3,
            dataType : 'json',
            cache : false,
            success : function(data){
                if(error_handler(data)){
                  return;
                }
                var industry = $("#industry"), industryoptions = "<option value=''>select</option>";
               industry.empty();      
                for(var i=0;i<data.length; i++)
               {
                    industryoptions += "<option value='"+data[i].industry_id+"'>"+ data[i].industry_name +"</option>";              
               }
               industry.append(industryoptions);
                
            }
        });
		
        $('#files').show();
        $('#files').change(handleFile);
        
		$("#save_file").click(function(){
			var addobj={}, user_val = [];
			if($.trim($("#industry").val()) == ""){
				$("#industry").closest("div").find("span").text("Industry is required");
				return;
			}else{
				$("#industry").closest("div").find("span").text("");
			}
			if($.trim($("#bus_loc").val()) == ""){
				$("#bus_loc").closest("div").find("span").text("Location is required");
				return;
			}else{
				$("#bus_loc").closest("div").find("span").text("");
			}
			$("#user_list li input[type=checkbox]").each(function(){
				if($(this).prop('checked')==true){
					user_val.push({'to_user_id': $(this).attr('id') , 'module': $(this).val()});
				}        
			}); 
			if(moduleName == "Admin"){
				addobj.users = user_val;				
				if($(".user_section").css("display") == "none"){
					$("#btn_fetch").closest("div").find("div").text("Please fetch user to continue");
					return;
				}else{
					$("#btn_fetch").closest("div").find("div").text("");
				}
				if(user_val.length <= 0 && $(".user_section").css("display") != "none"){
					$(".error_user").text("Please select atleast one user");
					return;
				}else{
					$(".error_user").text("");
				}
			}
			if($('#files').val() == ''){
			   $("#files").closest("div").find("span").text("Attachment file is required");
			   $('#files').val("");
			   return;
			}else{
				/* var exce=remove_duplicates(result, 'Lead Phone Number 1*'); */
				/* var exce=remove_duplicates(result, ''); */
				var exce=result;
				for(i=0;i<(exce.length);i++){
					exce[i].Lead_id="";
					exce[i].Contact_id="";
				}
				var leadsource="";
				 $("#leadsource_excel_upload input[name=Addlead]").each(function(){
					if($(this).prop('checked')==true){
						leadsource=$(this).val();
					}        
				});
				
				if(exce.length==0){
					$('#files').val("");
					return;
				} 
				$("#files").closest("div").find("span").text("");				
				
				addobj.lead=exce;
				addobj.source=leadsource;
				addobj.industry = $.trim($("#industry").val());
				addobj.bussiness= $.trim($("#bus_loc").val());
				
				console.log(addobj)
				loaderShow();
				left_lead = left_lead + 1;
				$("#counterList #invalid_list").html("");
				$.ajax({
				   type : "POST",
				   url : url_link4,
				   dataType : 'json',
				   data :JSON.stringify(addobj),
				   cache : false,
				   success : function(data){
						if(error_handler(data)){
							 return;
						}
						exce=[];result=[];
						loaderHide();
						$("#modal_upload").modal("hide");
						$('#modal_upload #files').val("");
						var rejected =total-data;
						if(versiontype=='lite' && left_lead > 50){
							alert("You exceed the limit " + total + " added out of " +  left_lead);
						}else if(versiontype=='standard' && left_lead > 500){
							alert("You exceed the limit " + total + " added out of " +  left_lead);
						}
						close_modal();						
						pageload();
						$("#counterList .modal-header").hide();
						$("#counterList table").remove();
						$("#counterList").modal("show");
						$("#counterList").find(".modal-dialog").removeClass("full-screen");
						if(moduleName == "Admin"){
							$("#counterList #success_result").text("Out of "+total+" Leads, "+rejected+ " are rejected");
						}else{
							$("#counterList #success_result").text("Out of "+total+" Customers, "+rejected+ " are rejected");
						}
						
				   }
			   });
			}
			
		});
});

    var result=[];
    var total;
    function handleFile(e){
		$(".progress .progress-bar.progress-bar-success").text("").css("width" , "0%");
		$(".progress .progress-bar.progress-bar-danger").text("").css("width" , "0%");
        var files = e.target.files;
        var excel =files[0];
		/* var valid_extensions = /(\.xlsx|\.jpeg|\.gif|\.bmp|\.png|\.JPG|\.JPEG|\.GIF|\.BMP|\.PNG)$/i;  */  
		var valid_extensions = /(\.xlsx)$/i;   
		if(!valid_extensions.test(excel.name)){
			$("#files").closest("div").find("span").text("Invalid File type.");
			$('#files').val("");
			return;
		}else if(excel.size > 10480000){
			$("#files").closest("div").find("span").text("File size is too long.");
			$('#files').val("");
			return;
		}else{
			$("#files").closest("div").find("span").text("");
			var reader = new FileReader();
			reader.readAsBinaryString(excel);
			reader.onload = function(e) {
				
				var data = e.target.result;
				var workbook = XLSX.read(data, {type: 'binary'});
				
				workbook.SheetNames.forEach(function(sheetName) {
					var roa = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName]);
					left_lead = roa.length;
					/* var Customer_heading = ["Customer Name*^",	
					"Customer Company Website",	
					"Customer Contact Name*^",	
					"Customer Contact Designation",	
					"Customer Phone Number 1*",	
					"Customer Phone Number 2",	
					"Customer E-mail ID 1",	
					"Customer E-mail ID 2",	
					"Address",
					"Country",
					"State",
					"City",	
					"Zipcode",	
					"Special Comments"]
					*/
					var Lead_heading_mendatory ={
									"Lead Name*^" : "yes",	
									"Lead Company Website" : "no",	
									"Lead Contact Name*^" : "yes",	
									"Lead Contact Designation" : "no",	
									"Lead Phone Number 1*" : "yes",	
									"Lead Phone Number 2" : "no",	
									"Lead E-mail ID 1" : "no",	
									"Lead E-mail ID 2" : "no",	
									"Address" : "no",	
									"Country" : "no",
									"State" : "no",
									"City" : "no",	
									"Zipcode" : "no",	
									"Special Comments" : "no"
									}
					var aa=0;
					if(roa.length > 0){
						/* previously sending all data (valid or invalid)
						result = roa; 
						*/
						/* new code */
						result=[]
						var invalid=[];
						var InfectedRow=2;
						for(i=0; i<roa.length; i++){
							
							if(i >= count && moduleName == "Admin"){
								break;
							}
							rowData = excel_field_validation(roa[i],Lead_heading_mendatory)
							if(rowData.status == "invalid_excel"){								
								$("#files").closest("div").find("span").text("Invalid data format.");
								$('#files').val("");
								return;
							}
							if(rowData.status =="valid"){
								result.push(rowData.Data)
							}
							if(rowData.status =="invalid"){
								rowData.Data["Infected Row"]= InfectedRow;
								invalid.push(rowData.Data);
							}
							InfectedRow++;
							aa++;
						}
						if(invalid.length > 0){
							create_table(Lead_heading_mendatory,invalid)			
						}
						
						$(".progress").removeClass('none');
						var percentage = Math.floor((result.length/aa)*100)
						$(".progress .progress-bar.progress-bar-success").text(percentage+"% Success").css("width" , percentage+"%");
						$(".progress .progress-bar.progress-bar-danger").text((100-percentage)+"% Fail").css("width" , (100-percentage)+"%");
					}else{
						$("#files").closest("div").find("span").text("Data not available in the selected Excel file.");
					}
					/* var number1 = 10;
					var number2 = 100;

					alert(Math.floor((number1 / number2) * 100)); */

					
				});
				console.log(result);
				total=result.length;
			};
		}
       
    }
	/* invalid data table*/
	function create_table(heading,tableData){
		$("#counterList .modal-header").show();
		$("#counterList .modal-header .modal-title").text("Invalid row(s) from uploded excel file")
		heading["Infected Row"]="";
		var html = "";
		$("#counterList").modal("show");
		$("#counterList #success_result").text("");
		$("#counterList").find(".modal-dialog").addClass("full-screen");
		html = '<table class = "table table-bordered"><thead>'
		html += "<tr>";
	
		/* $.each(heading, function(title, value){	
			html += "<th>" +title+ "</th>"
		}) */
		
		html += '<tr>'+
					'<th ><div>Name*</div></th>'+
					'<th><div>Website</div></th>'+
					'<th><div>Contact Name*</div></th>'+
					'<th><div>Designation</div></th>'+
					'<th><div>Phone No. 1*</div></th>'+
					'<th><div>Phone No. 2</div></th>'+
					'<th><div>E-mail ID 1</div></th>'+
					'<th><div>E-mail ID 2</div></th>'+
					'<th><div>Address</div></th>'+
					'<th><div>Country</div></th>'+
					'<th><div>State</div></th>'+
					'<th><div>City</div></th>'+
					'<th><div>Zipcode</div></th>'+
					'<th><div>Comments</div></th>'+
					'<th><div>Infected Row</div></th>'+
					'</tr>';
		
		html += "</tr></thead><tbody>";
		for(j=0; j<tableData.length; j++){
			html += "<tr>";
			$.each(heading, function(title1, value){	
				$.each(tableData[j], function(key, value){				
						if(title1 == key){
							html += "<td>" + value+ "</td>"
						}
				})
			})
			html += "</tr>";
		}
		html += "</tbody></table>";
		$("#counterList #invalid_list").html("").append(html)
		$("#progressbar1").text("Validating row");
	}

	/* each field validation*/
    function excel_field_validation(rowData,mendatory){
		var validChk = 0;
		/* ------------------------------------------------------------
		- Mendatory Fields Check
		------------------------------------------------------------ */
		
		/*for(j=0; j<header.length; j++){
			if(!rowData.hasOwnProperty(header[j])){
				rowData[header[j]] ="";
				 
				var mendatoryChk = 0;
				$.each(mendatory, function(key, value){
					if(header[j] == key && value == "yes"){
						rowData[key] ="";						
					}
					if(header[j] == key && value == "no"){
						rowData[key] ="";
					}
					
				}) 
				if( mendatoryChk == 1){
					validChk =1;
				} 
								
			}
			
		}*/

		$.each(mendatory, function(key, value){
			if(!rowData.hasOwnProperty(key)){
				rowData[key] ="";
			}
			
		}) 
		/* -----------------------------------------------------------
		- Field's Value Validation - Only if value presents
		------------------------------------------------------------ */
		var allData= 0;
		var validData= 0;
		var mobiles=[];
		var emails=[];
		$.each(rowData, function(key, value){
			allData++;
			/* lead name validation */
			if($.trim(key) == "Lead Name*^"){
				if($.trim(value) == ""){
					rowData["Lead Name*^"]  = "<b class='error-alert' title='Lead Name is required.'>--</b>";
					validChk = 1;
				}else if(!validate_name($.trim(value))){
					rowData["Lead Name*^"] = "<b class='error-alert' title='No special characters allowed (except &, _,-,.)'>"+value+"</b>";
					validChk = 1;							
				}else if(!firstLetter($.trim(value))){
					rowData["Lead Name*^"]  = "<b class='error-alert' title='First letter should not be Numeric or Special character.'>"+value+"</b>";
					validChk = 1;		
				}
				validData++;
			}
			
			/* website validation */
			if($.trim(key)=="Lead Company Website"){
				if($.trim(value) != ""){
					if(!validate_website($.trim(value))){
						rowData["Lead Company Website"]  = "<b class='error-alert' title='Invalid website address.'>"+value+"</b>";
						validChk = 1;
					}
				}
				validData++;
			}
			
			/* contact name validation */
			if($.trim(key) == "Lead Contact Name*^"){
				if($.trim(value) == ""){
					rowData["Lead Contact Name*^"]  = "<b class='error-alert' title='Lead Contact Name is required.'>--</b>";
					validChk = 1;
				}else if(!validate_name($.trim(value))){
					rowData["Lead Contact Name*^"]  = "<b class='error-alert' title='No special characters allowed (except &, _,-,.)'>"+value+"</b>";
					validChk = 1;
				}else if(!firstLetterChk($.trim(value))){
					rowData["Lead Contact Name*^"]  = "<b class='error-alert' title='First letter should not be Numeric or Special character.'>"+value+"</b>";
					validChk = 1;	
				} 
				validData++;
			}
			
			/* Lead Contact Designation validation */
			if($.trim(key)=="Lead Contact Designation"){
				if($.trim(value) != ""){
					if(!validate_name($.trim(value))){
						rowData["Lead Contact Designation"]  = "<b class='error-alert' title='No special characters allowed (except &, _,-,.)'>"+value+"</b>";
						validChk = 1;
					}
				}
				validData++;
			}
			
			/* Lead Phone Number 1* - (primary) validation */
			
			if($.trim(key)=="Lead Phone Number 1*"){
				if($.trim(value) == ""){
					rowData["Lead Phone Number 1*"]  = "<b class='error-alert' title='Lead Phone Number is required.'>--</b>";
					validChk = 1;
				}else if(!validate_PhNo($.trim(value))){
					rowData["Lead Phone Number 1*"]  = "<b class='error-alert' title='Enter 10 digit mobile number.'>"+value+"</b>";
					validChk = 1;
				}
				mobiles.push($.trim(value))
				validData++;
			}
			
			/* Lead Phone Number 2 - (secondary) validation */
			if($.trim(key)=="Lead Phone Number 2"){
				if($.trim(value) != ""){
					if(!validate_PhNo($.trim(value))){
						rowData["Lead Phone Number 2"]  = "<b class='error-alert' title='Enter 10 digit mobile number.'>"+value+"</b>";
						validChk = 1;
					}
				}
				mobiles.push($.trim(value))
				validData++;			
			}

			/* Lead Phone Number 2 - (secondary) validation */
			if($.trim(key)=="Lead E-mail ID 1"){
				if($.trim(value) != ""){
					if(!validate_email($.trim(value))){
						rowData["Lead E-mail ID 1"]  = "<b class='error-alert' title='Invalid email address'>"+value+"</b>";
						validChk = 1;
					}
				}
				emails.push($.trim(value))
				validData++;
			}

			/* Lead Phone Number 2 - (secondary) validation */
			if($.trim(key)=="Lead E-mail ID 2"){
				if($.trim(value) != ""){
					if(!validate_email($.trim(value))){
						rowData["Lead E-mail ID 2"]  = "<b class='error-alert' title='Invalid email address'>"+value+"</b>";
						validChk = 1;
					}
				}
				emails.push($.trim(value))
				validData++;
			}
			
			/* Address validation */
			if($.trim(key)=="Address"){
				/* if($.trim(value) != ""){
					if(!validate_location($.trim(value))){
						alert("No special characters allowed (except &)");
					}
				}  */
				validData++;
			}
			
			
			/* Country validation */
			if($.trim(key)=="Country"){
				if($.trim(value) != ""){
					if(!validate_location($.trim(value))){
						rowData["Country"]  = "<b class='error-alert' title='No special characters allowed (except &)'>"+value+"</b>";
						validChk = 1;
					}
				}
				validData++;				
			}

			/* Country validation */
			if($.trim(key)=="State"){
				if($.trim(value) != ""){
					if(!validate_location($.trim(value))){
						rowData["State"]  = "<b class='error-alert' title='No special characters allowed (except &)'>"+value+"</b>";
						validChk = 1;
					}
				}
				validData++;
			}

			/* Country validation */
			if($.trim(key)=="City"){
				if($.trim(value) != ""){
					if(!validate_location($.trim(value))){
						rowData["City"]  = "<b class='error-alert' title='No special characters allowed (except &)'>"+value+"</b>";
						validChk = 1;
					}
				}
				validData++;
			}

			/* Zipcode validation */
			if($.trim(key)=="Zipcode"){
				if($.trim(value) != ""){
					if(!validate_zip($.trim(value))){
						rowData["Zipcode"]  = "<b class='error-alert' title='No special characters allowed (except -)'>"+value+"</b>";
						validChk = 1;
					}
				}
				validData++;
			}
			
			/* Special Comments validation */
			if($.trim(key)== "Special Comments"){
				if($.trim(value) != ""){
					if(!comment_validation($.trim(value))){
						rowData["Special Comments"]  = "<b class='error-alert' title='No special characters allowed (except $ & : ( ) # @ _ . , + % ? -)'>"+value+"</b>";
						validChk = 1;
					}
				}
				validData++;
			}
		})

		/* ------------------------------------------------------------
		- Main Validation
		------------------------------------------------------------ */
		
		
		var data={};
		/* if(validChk == 3){
			data.status="invalid excel";
			data.Data=rowData
		} */

		if(compareContact(mobiles) == "match"){
			rowData["Lead Phone Number 2"]  = "<b class='error-alert' title='Mobile Number 1 and Mobile Number 2 should not be same.'>"+mobiles[1]+"</b>";
			validChk = 1;
		}

		if(compareContact(emails) == "match"){
			rowData["Lead E-mail ID 2"]  = "<b class='error-alert' title='Email 1 and Email 2 should not be same.'>"+emails[1]+"</b>";
			validChk = 1;
		}
		
		if((allData-1) > validData){
			data.status="invalid_excel";
			data.Data=rowData
			return data;
		}

		if(validChk == 1){
			data.status="invalid";
			data.Data=rowData
			return data;
		}
		if(validChk == 0){
			data.status="valid";
			data.Data=rowData
			return data;
		}
			
		
		
	}

	/* duplicate lead/customer name remove */
    function remove_duplicates(original_array, objKey){
        var final_array = [];
        var json_data= [];
        var value;
        for(var i = 0; i < original_array.length; i++) {
            value = original_array[i][objKey];

            if(json_data.indexOf(value) === -1) {
              final_array.push(original_array[i]);
              json_data.push(value);
            }
        }
      return final_array;
    }
</script>


<div id="modal_upload" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<span class="close" onclick="close_modal()">&times;</span>
				<h4 class="modal-title">Upload File</h4>
			</div>
			<div class="modal-body">
                         
				<div class="row">
					<div class="col-md-2">
						<label for="industry">Industries*</label> 
					</div>
					<div class="col-md-4">
						<select class="form-control" id="industry" onchange="clear_loc()">
						</select>
						<span class="error-alert"></span>
					</div>
					<div class="col-md-2 ">
						<label for="bus_loc">Location*</label> 
					</div>
					<div class="col-md-4 ">
					  <select class="form-control" id="bus_loc" name="bus_loc" onchange="clear_user()">
						</select>
						<span class="error-alert"></span>
					</div>
				</div>
				<div class="row button_fetch none" style="text-align:right;">
					<button class="btn" id="btn_fetch" onclick="user_list()">Fetch Users</button>
					<div class="error-alert"></div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<label for="leadsource_excel_upload"  id="excel_upload_tree_lead" >
								<a href="#" >Lead Source<b class="glyphicon glyphicon-menu-right" style="position: absolute;top: 4px;"></b></a>
							</label>
							<div id="leadsource_excel_upload" class="tree-view" style="display:none"></div>
							<span class="error-alert"></span>
							<label class="leadsrcname"></label>
						</div>
						<br>
						<div class="row">
							<div class="col-md-4 no-padding">
								<label for="add_role">Select File*</label> 
							</div>
							<div class="col-md-8 no-padding">
								<input name="files" type='file' id="files" file-input="files"/>
								<br><span class="error-alert"></span>
							</div>
						</div>
					</div>
					<div class="col-md-6 user_section none">
						<div class="row">
							
							<div class="col-md-4 no-padding">
								<label for="add_role">User*</label> 
							</div>
							<div class="col-md-8 no-padding">
								<div id="user_list" class="multiselect"></div>
								<br><span class="error-alert error_user"></span>
							</div>
						</div>
					</div>
				</div>
				
				
				<br>
				<div class="row">
					<div  class="col-md-12" id="progressbar1"></div>				
				</div>	
				<div class="progress none">
				  <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width:0%"></div>
				  <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width:0%"></div>
				</div>
			</div>
			<div class="modal-footer" id="modal_footer">
				<a class="btn btn-primary pull-left" href="<?php echo base_url(); ?>/uploads/lead_template.xlsx">
							Download Template
				</a>
				<input type="button" class="btn" id="save_file" value="Upload">
				<input type="button" class="btn" value="Cancel" onclick="close_modal()">
			</div>
		</div>
	</div>
</div>

<div id="counterList"  class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog full-screen">
		<div class="modal-content">
			<div class="modal-header">
				<span class="close" onclick="counterList()">&times;</span>
				<h4 class="modal-title">Invalid row(s) from uploded excel file</h4>
			</div>
			<div class="modal-body"> 
				<h4 id="success_result"> </h4>
				<div id="invalid_list"> 
				</div>
			</div>
			<div class="modal-footer" id="modal_footer">
				<center><input type="button" class="btn" value="Ok" onclick="counterList()"></center>
			</div>
		</div>
	</div>
</div>
<style>
.full-screen{
		width: 80%;
	  padding: 20px;
}
#invalid_list{
	    overflow: auto;
}
</style>
