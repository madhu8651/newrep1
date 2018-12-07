<style>
#invalid_list table tr th div{
	width:250px
}
.table-bordered,.table-bordered > tbody > tr > th,.table-bordered > tbody > tr > td{ border:1px solid #ccc!important; }
#invalid_list{
	    overflow: auto;
}
</style>



<script>
	function addExl(){
		if(versiontype == "lite"){
			alert('This feature is not available in Lite Version!');
			return;
		}
		$("#modal_upload").modal("show");
		$(".progress").addClass('none');
		$("#files").closest("div").find("span").text("");
		$('#files').change(handleFile);
		$(".progress .progress-bar.progress-bar-success").text("").css("width" , "0%");
		$(".progress .progress-bar.progress-bar-danger").text("").css("width" , "0%");
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
	$(document).ready(function(){
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_leadController/getLocation'); ?>",
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
			url : "<?php echo site_url('manager_leadController/getIndustry'); ?>",
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
	});
	
	var result=[];
    var total;
    function handleFile(e) {
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
				var Lead_heading_mendatory ={
									"Customer Name*^" : "yes",	
									"Customer Company Website" : "no",	
									"Customer Contact Name*^" : "yes",	
									"Customer Contact Designation" : "no",	
									"Customer Phone Number 1*" : "yes",	
									"Customer Phone Number 2" : "no",	
									"Customer E-mail ID 1" : "no",	
									"Customer E-mail ID 2" : "no",	
									"Address" : "no",	
									"Country" : "no",	
									"State" : "no",
									"City" : "no",	
									"Zipcode" : "no",	
									"Special Comments" : "no"
								}
								

				var aa=0;
				if(roa.length > 0 ){
					result=[]
					var invalid=[]
					var InfectedRow=2
					for(i=0; i<roa.length; i++){
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
				/* if(roa.length > 0 ){
						result = roa;
				} */
				
			});
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
		html = '<table class = "table"><thead>'
		html += "<tr>";
	
		/* $.each(heading, function(title, value){	
			html += "<th>" +title+ "</th>"
		}) */
		html += '<tr>'+
					'<th ><div>Name*</div></th>'+
					'<th><div>Website</div></th>'+
					'<th><div>Name</div></th>'+
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
	/* each field validation*/
	function excel_field_validation(rowData,mendatory){
		var validChk = 0;
		/* ------------------------------------------------------------
		- Mendatory Fields Check
		------------------------------------------------------------ */
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
			/* Customer name validation */
			if($.trim(key) == "Customer Name*^"){
				if($.trim(value) == ""){
					rowData["Customer Name*^"]  = "<b class='error-alert' title='Customer Name is required.'>--</b>";
					validChk = 1;
				}else if(!validate_name($.trim(value))){
					rowData["Customer Name*^"] = "<b class='error-alert' title='No special characters allowed (except &, _,-,.)'>"+value+"</b>";
					validChk = 1;							
				}else if(!firstLetter($.trim(value))){
					rowData["Customer Name*^"]  = "<b class='error-alert' title='First letter should not be Special character.'>"+value+"</b>";
					validChk = 1;		
				}
				validData++;
			}
			
			/* website validation */
			if($.trim(key)=="Customer Company Website"){
				if($.trim(value) != ""){
					if(!validate_website($.trim(value))){
						rowData["Customer Company Website"]  = "<b class='error-alert' title='Invalid website address.'>"+value+"</b>";
						validChk = 1;
					}
				}
				validData++;
			}
			
			/* contact name validation */
			if($.trim(key) == "Customer Contact Name*^"){
				if($.trim(value) == ""){
					rowData["Customer Contact Name*^"]  = "<b class='error-alert' title='Customer Contact Name is required.'>--</b>";
					validChk = 1;
				}else if(!validate_name($.trim(value))){
					rowData["Customer Contact Name*^"]  = "<b class='error-alert' title='No special characters allowed (except &, _,-,.)'>"+value+"</b>";
					validChk = 1;
				}else if(!firstLetterChk($.trim(value))){
					rowData["Customer Contact Name*^"]  = "<b class='error-alert' title='First letter should not be Numeric or Special character.'>"+value+"</b>";
					validChk = 1;	
				} 
				validData++;
			}
			
			/* Customer Contact Designation validation */
			if($.trim(key)=="Customer Contact Designation"){
				if($.trim(value) != ""){
					if(!validate_name($.trim(value))){
						rowData["Customer Contact Designation"]  = "<b class='error-alert' title='No special characters allowed (except &, _,-,.)'>"+value+"</b>";
						validChk = 1;
					}
				}
				validData++;
			}
			
			/* Customer Phone Number 1* - (primary) validation */
			if($.trim(key)=="Customer Phone Number 1*"){
				if($.trim(value) == ""){
					rowData["Customer Phone Number 1*"]  = "<b class='error-alert' title='Customer Phone Number is required.'>--</b>";
					validChk = 1;
				}else if(!validate_PhNo($.trim(value))){
					rowData["Customer Phone Number 1*"]  = "<b class='error-alert' title='Enter 10 digit mobile number.'>"+value+"</b>";
					validChk = 1;
				}
				mobiles.push($.trim(value))
				validData++;
			}
			
			/* Customer Phone Number 2 - (secondary) validation */
			if($.trim(key)=="Customer Phone Number 2"){
				if($.trim(value) != ""){
					if(!validate_PhNo($.trim(value))){
						rowData["Customer Phone Number 2"]  = "<b class='error-alert' title='Enter 10 digit mobile number.'>"+value+"</b>";
						validChk = 1;
					}
				}
				mobiles.push($.trim(value))
				validData++;			
			}

			/* Customer Phone Number 2 - (secondary) validation */
			if($.trim(key)=="Customer E-mail ID 1"){
				if($.trim(value) != ""){
					if(!validate_email($.trim(value))){
						rowData["Customer E-mail ID 1"]  = "<b class='error-alert' title='Invalid email address'>"+value+"</b>";
						validChk = 1;
					}
				}
				emails.push($.trim(value))
				validData++;
			}

			/* Customer Phone Number 2 - (secondary) validation */
			if($.trim(key)=="Customer E-mail ID 2"){
				if($.trim(value) != ""){
					if(!validate_email($.trim(value))){
						rowData["Customer E-mail ID 2"]  = "<b class='error-alert' title='Invalid email address'>"+value+"</b>";
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
			rowData["Customer Phone Number 2"]  = "<b class='error-alert' title='Mobile Number 1 and Mobile Number 2 should not be same.'>"+mobiles[1]+"</b>";
			validChk = 1;
		}

		if(compareContact(emails) == "match"){
			rowData["Customer E-mail ID 2"]  = "<b class='error-alert' title='Email 1 and Email 2 should not be same.'>"+emails[1]+"</b>";
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
		for(var i = 0; i < original_array.length; i++){
			value = original_array[i][objKey];
			if(json_data.indexOf(value) === -1){
				final_array.push(original_array[i]);
				json_data.push(value);
			}
		}
		return final_array;
	}
	
	/* final save */
	function save_file(){
		if($('#files').val()!=''){
			var file = $('#files').val();
			var file = file.split(".");
		}else{
			$("#files").closest("div").find("span").text("Attachment file is required");
			return;
		}
		/* var exce=remove_duplicates(result, 'Customer Phone Number 1*'); */
		/* var exce=remove_duplicates(result, ''); */
		var exce=result;
		for(i=0;i<(exce.length);i++){
			 exce[i].Customer_id="";
			 exce[i].Contact_id="";
		}
		if(exce.length==0){
			$('#files').val("");
			return;
		} 
		$("#files").closest("div").find("span").text("");
		var addobj={};
		addobj.customer=exce;
		addobj.industry = $.trim($("#industry").val());
		addobj.bussiness= $.trim($("#bus_loc").val());
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_customerController/get_data'); ?>",
			dataType : 'json',
			data :JSON.stringify(addobj),
			cache : false,
			success : function(data){
				exce=[];result=[];
				loaderHide();
				if(error_handler(data)){
					return;
				}
				close_modal();
				$("#modal_upload").modal("hide");
				$("#counterList .modal-header").hide();
				$('#modal_upload #files').val("");
				var rejected =total-data;
				$("#counterList table").remove();
				$("#counterList").modal("show");
				$("#counterList").find(".modal-dialog").removeClass("full-screen");
				$("#counterList #success_result").text("Out of "+total+" Customers, "+rejected+ " are rejected");
				assignedLoad();
			}
		});
		
	}
	
	function counterList(){
		$("#counterList").modal("hide");
		$("#progressbar1").text("");
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
                                        <label for="industry">Industries</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="industry" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 ">
                                        <label for="bus_loc">Location</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                      <select class="form-control" id="bus_loc" name="bus_loc" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                            <br>
				<div class="row">
					<div class="col-md-2">
						<label for="add_role">Select File*</label> 
					</div>
					<div class="col-md-10">
						<input name="files" type='file' id="files" file-input="files"/>
						<br><span class="error-alert"></span>
					</div>
				</div>
				<div class="row">
				<br>
					<div class="progress none">
						<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width:0%"></div>
						<div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width:0%"></div>
					</div>					
				</div>	
							
			</div>
			<div class="modal-footer" id="modal_footer">
				<a class="btn btn-primary pull-left" href="<?php echo base_url(); ?>/uploads/Customer_template.xlsx">
								Download Template
						</a>
				<input type="button" class="btn" onclick="save_file()" value="Save">
				<input type="button" class="btn" value="Cancel" onclick="close_modal()" >

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
</style>