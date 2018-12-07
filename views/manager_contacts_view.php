<!DOCTYPE html>
<html lang="en">
<head>
	<?php require 'scriptfiles.php' ?>
	<style>
		.tooleye{
			font-size: small;
			border: 2px solid;
			padding: 5px;
			border-radius: 4px;
			color: white;
			margin: 2px;
		}
		.contact_address, .contact_remarks{
			height: 75px;
		}
		input[type="file"] {
			display: none;
		}
		.custom-file-upload {
			border: 1px solid #ccc;
			display: inline-block;
			padding: 6px 12px;
			cursor: pointer;
		}
		.drop	{
			margin-left: -12px;
			margin-bottom: 3px;
			margin-top: 11px;
		}
		.round {
			border-radius: 50%;
			overflow: hidden;
			width: 100px;
			height: 100px;
		}
		.round img {
			display: block;
			min-width: 100%;
			min-height: 100%;
		}
		.contact_add {
			max-height: 250px;
			min-height: 300px;
			overflow: auto;
		}
		.view_placeholder	{
			float: left;
			font-weight: bold !important;
		}
	</style>
	
	<script>
	
		var manager_contacts_leads;
		var manager_contacts_customers;
		var manager_opportunity;
		var manager_contacts_contact_types;

		$(document).ready(function(){
			pageload();
			$('#imageUploadA11').on('submit', function (e) {
				e.preventDefault();
			});
			$('#imageUploadE').on('submit', function (e) {
				e.preventDefault();
			});

			$("#add_dob_picker").datetimepicker({
				ignoreReadonly:true,
				allowInputToggle:true,
				format:'YYYY-MM-DD',
				maxDate : moment(),
				useCurrent : false
			});
			$("#edit_dob_picker").datetimepicker({
				ignoreReadonly:true,
				allowInputToggle:true,
				format:'YYYY-MM-DD',
				useCurrent : false,
				maxDate : moment()
			});
		});

		function pageload()	{
			loaderShow();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('manager_contacts/initView'); ?>",
				dataType : 'json',
				cache : false,
				error: function (xhr, error) {
					console.log(xhr.statusText);
				},
				success : function(data){
					if(error_handler(data)){
						return;
					}
					loaderHide();
					console.log(data);
					reloadTable(data.contacts);
					manager_contacts_leads = data.leads;
					manager_contacts_customers = data.customers;
					manager_opportunity = data.opportunity;
					setCustomers(data.leads , 'lead');
					manager_contacts_contact_types = data.contact_type;
					setContactTypes(data.contact_type);
					/*setCustomers(data.customers);*/
				}
			});
		}

		
		function setCustomers(elm, type)	{
			$("#target_type_add").empty();
			$("#target_type_edit").empty();
			var options = "<option value=''>Select</option>";
			if(type == 'lead'){
				for(var i=0;i<elm.length; i++)	{
					status = "";
					if(elm[i].status != '-'){
						status = " (" + elm[i].status.split('_').join(' ') + ")";
					}
					options += "<option value='"+elm[i].lead_id+"'>"+ elm[i].lead_name + status+"</option>";
				}
			}else if(type == 'customer' ){
				for(var i=0;i<elm.length; i++)	{
					options += "<option value='"+elm[i].customer_id+"'>"+ elm[i].customer_name +"</option>";
				}
			}else if(type =='opportunity'){
				for(var i=0;i<elm.length; i++)	{
					status = "";
					if(elm[i].status != '-'){
						status = " (" + elm[i].status.split('_').join(' ') + ")";
					}
					options += "<option value='"+elm[i].opportunity_id+"'>"+ elm[i].opportunity_name + status +"</option>";
				}
			}
			$("#target_type_add").append(options);
			$("#target_type_edit").append(options);
		}

		function setContactTypes(contacttypes) {
			for(i=0; i < contacttypes.length; i++)	{
				var options = "<option value=''>Select</option>";
				$("#add_buyerper").empty();
				$("#edit_buyerper").empty();
				for(var i=0;i<contacttypes.length; i++)	{
					options += "<option value='"+contacttypes[i].contact_type_id+"'>"+ contacttypes[i].contact_type_name +"</option>";
				}
				$('#add_buyerper').append(options);
				$('#edit_buyerper').append(options);
			}		
		}

		function changeTarget(obj) {
			var target = obj.id;
			if (target == 'lead') {
				$('#target_placeholder_add').text('Lead ');
				setCustomers(manager_contacts_leads , 'lead');
			}else if (target == 'customer') {
				$('#target_placeholder_add').text('Customer ');
				setCustomers(manager_contacts_customers, 'customer');
			}else if (target == 'opportunity') {
				$('#target_placeholder_add').text('Opportunity ');
				setCustomers(manager_opportunity , 'opportunity');
			}
		}

		function reloadTable(tableData) {
			$('.modal').modal('hide');
			$('.closeinput').val('');
			$('#tablebody').parent("table").dataTable().fnDestroy();
			$('#tablebody').empty();
			var row = "";
			for(i=0; i < tableData.length; i++ ){
				leadid=	tableData[i].lead_cust_name;					
				var rowdata = JSON.stringify(tableData[i]);
				var site_url = "<?php echo base_url(''); ?>" ;
				if (!tableData[i].contact_photo) {
					site_url = site_url + "images/default-pic.jpg"
				} else {
					site_url = site_url + "uploads/" + tableData[i].contact_photo;
				}
				var contact_nums = JSON.parse(tableData[i].contact_number);
				var emails = JSON.parse(tableData[i].contact_email);
				
				if ((emails != null) && (emails.length >0)) {
					emails = JSON.parse(tableData[i].contact_email)[0];
				} else {
					emails ='-';
				}
				if ((contact_nums != null) && (contact_nums.length >0)) {						
					contact_nums = JSON.parse(tableData[i].contact_number)[0];
				} else {
					contact_nums ='-';
				}
				/* Manju Code -------------------------
				row = "<tr class='table'> \
						<td>" + (i+1) + "</td> \
						<td> <img style='border-radius: 50%;' width='35' height='35' id='tableImg' alt='Avatar' src='"+site_url+"' /></td> \
						<td style='text-align: left;'> <b>" + tableData[i].contact_name + " </b> </td> \
						<td style='text-align: left;'>" + capitalizeFirstLetter(tableData[i].lead_cust_name)+" (<b>"+capitalizeFirstLetter(tableData[i].contact_for)+"</b>)</td> \
						<td style='text-align: left;'>";
				if ((contact_nums != null) && (contact_nums.length >0)) {						
					row += JSON.parse(tableData[i].contact_number)[0];
				} else {
					row +='-';
				}
				row += "</td> \
						<td style='text-align: left;'>";
				if ((emails != null) && (emails.length >0)) {
					row += JSON.parse(tableData[i].contact_email)[0];
				} else {
					row +='-';
				}
				row += "</td> \
						<td style='text-align: left;'>" + tableData[i].contact_desg + "</td> \
						<td style='text-align: left;'>" + tableData[i].contact_type_name + "</td> \
						<td><a data-toggle='modal' href='#view_modal' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a> </td> \
						<td><a data-toggle='modal' href='#edit_modal' onclick='editrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td> \
					</tr>";
				 */	
				row = "<tr class='table'>"+
						"<td>" + (i+1) + "</td>"+
						"<td> <img style='border-radius: 50%;' width='35' height='35' id='tableImg' alt='Avatar' src='"+site_url+"' /></td>"+
						"<td style='text-align: left;'> <b>" + tableData[i].contact_name + " </b> </td>"+
						"<td style='text-align: left;'>" + capitalizeFirstLetter(tableData[i].lead_cust_name)+" (<b>"+capitalizeFirstLetter(tableData[i].contact_for)+"</b>)</td>"+
						"<td style='text-align: left;'>"+contact_nums+"</td>"+
						"<td style='text-align: left;'>"+emails+"</td>"+
						"<td style='text-align: left;'>" + tableData[i].contact_desg + "</td>"+
						"<td style='text-align: left;'>" + tableData[i].contact_type_name + "</td>"+
						"<td><a data-toggle='modal' href='#view_modal' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a> </td>"+
						"<td><a data-toggle='modal' href='#edit_modal' onclick='editrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td>"+
					"</tr>";
					
					
					
					
				$('#tablebody').append(row);
			}
			$('#tableTeam').DataTable();
		}

		function add() {
			var addObj = {};
			if($("#target_type_add").val()==""){
				$("#target_type_add").closest("div").find("span").text($("#target_placeholder_add").text()+" is required.");
				$("#target_type_add").focus();				
				return;
			} else {
				$("#target_type_add").closest("div").find("span").text("");
			}

			/* if($("#add_name").val()==""){
				$("#add_name").closest("div").find("span").text("Name is required.");
				$("#add_name").focus();				
				return;
			} else {
				$("#add_name").closest("div").find("span").text("");
			} */
			
			if($.trim($("#add_name").val())==""){
				$("#add_name").closest("div").find("span").text("Contact Name is required.");
				$("#add_name").focus();
				return;
			}else if(!validate_name($.trim($("#add_name").val()))){
				$("#add_name").closest("div").find("span").text("No special characters allowed (except & _ - .)");
				$("#add_name").focus();
				return;
			}else if(!firstLetterChk($.trim($("#add_name").val()))){
				$("#add_name").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#add_name").focus();
				return;
			}else{
				$("#add_name").closest("div").find("span").text("");
			}
			
			

			if (!validate_name($.trim($("#add_desg").val()))) {
				$("#add_desg").closest("div").find("span").text("Please Enter a valid Designation.");
				$("#add_desg").focus();
				return ;
			} else {
				$("#add_desg").closest("div").find("span").text("");	
			}
			
			addObj.contact_number = {};
			var num1 = $.trim($("#add_phone").val());
			var num2 = $.trim($("#add_phone2").val());
			
			
			if($("#add_phone").val()==""){
				$("#add_phone").closest("div").find("span").text("phone number is required.");
				$("#add_phone").focus();				
				return;
			} else if (!validate_PhNo(num1)) {
				$("#add_phone").closest("div").find("span").text("Please enter 10 digit mobile number.");
				$("#add_phone").focus();
				return ;
			} else {
				$("#add_phone").closest("div").find("span").text("");	
			}
			
			if ((num2!= '') && !validate_PhNo(num2)) {
				$("#add_phone2").closest("div").find("span").text("Please enter 10 digit mobile number.");
				$("#add_phone2").focus();
				return ;
			} else {
				$("#add_phone2").closest("div").find("span").text("");
			}

			if ((num1.length != 0) && (num2 == num1)) {
				$("#add_phone2").closest("div").find("span").text("Phone Numbers can not be same.");
				$("#add_phone2").focus();				
				return;
			} else {
				$("#add_phone2").closest("div").find("span").text("");
			}
			
			addObj.contact_email = {};
			var email1  = $.trim($("#add_email").val());
			var email2  = $.trim($("#add_email2").val());
			
			if ((email1!= '') && !validate_email(email1)) {
				$("#add_email").closest("div").find("span").text("Please check Email ID entered.");
				$("#add_email").focus();
				return ;
			} else {
				$("#add_email").closest("div").find("span").text("");
			}
			
			if ((email2!= '') && !validate_email(email2)) {
				$("#add_email2").closest("div").find("span").text("Please check Email ID entered.");
				$("#add_email2").focus();
				return ;
			} else {
				$("#add_email2").closest("div").find("span").text("");				
			}

			if ((email1.length != 0) && (email1 == email2)) {
				$("#add_email2").closest("div").find("span").text("Emails can not be same.");
				$("#add_email2").focus();				
				return;
			} else {
				$("#add_email2").closest("div").find("span").text("");
			}
			
			
			if($.trim($("#add_address").val()) != ""){
				if(!comment_validation($.trim($("#add_address").val()))){
					$("#add_address").closest("div").find("span").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
					$("#add_address").focus();
					return;
				}else{
					$("#add_address").closest("div").find("span").text(" ");
				} 
			}else{
				$("#add_address").closest("div").find("span").text(" ");
			}
			
			if($.trim($("#add_remarks").val()) != ""){
				if(!comment_validation($.trim($("#add_remarks").val()))){
					$("#add_remarks").closest("div").find("span").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
					$("#add_remarks").focus();
					return;
				}else{
					$("#add_remarks").closest("div").find("span").text(" ");
				} 
			}else{
				$("#add_remarks").closest("div").find("span").text(" ");
			}
			
			
			
			
			addObj.contact_for  = $("input[name=target_type]:checked").val();
			addObj.lead_cust_id = $("#target_type_add").val(); 
			addObj.contact_name = $.trim($("#add_name").val());
			addObj.contact_desg = $.trim($("#add_desg").val());
			addObj.contact_type = $("#add_buyerper").val();
			addObj.contact_dob = $("#add_dob").val();
			addObj.contact_address = $.trim($("#add_address").val());
			addObj.remarks = $.trim($("#add_remarks").val());
			var mobileArray = [num1, num2];
			addObj.contact_number['phone'] = mobileArray;
			var emailArray = [email1, email2];
			addObj.contact_email['email'] = emailArray;			
			console.log(addObj);
			loaderShow();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('manager_contacts/add_leadContact'); ?>",
				dataType : 'json',
				data : JSON.stringify(addObj),
				cache : false,
				success : function(data){
						if(error_handler(data)){
							return;
						}
						console.log(data);
						var contact_id = data['contact_id'];
						contactUploadImage(contact_id, 'add');
						cancel();
				}
			});			
		}

		function viewrow(obj) {
			console.log(obj);
			var numbers = JSON.parse(obj.contact_number);
			var email = JSON.parse(obj.contact_email);

			var site_url = "<?php echo base_url(''); ?>" ;
			if (!obj.contact_photo) {
				site_url = site_url + "images/default-pic.jpg"
			} else 	{
				site_url = site_url + "uploads/" + obj.contact_photo;
			}

			$('#contact_img_view').attr('src', site_url);
			console.log(site_url);
			if (obj.contact_for == 'lead') {
				$('#target_placeholder_view').text('Lead');
			} else if (obj.contact_for == 'customer') {
				$('#target_placeholder_view').text('Customer');
			}

			$("#view_contact_for").text(obj.lead_cust_name);
			$("#view_contact_name").text(obj.contact_name);	
			$("#view_contact_desg").text(obj.contact_desg);
			$("#view_contact_phone1").text(numbers[0]);
			$("#view_contact_phone2").text(numbers[1]);
			$("#view_contact_email1").text(email[0]);
			$("#view_contact_email2").text(email[1]);
			$("#view_contact_buyerper").text(obj.contact_type_name);
			obj.contact_dob = obj.contact_dob == '-' ? '-' : moment(obj.contact_dob).format('DD-MM-YYYY');
			$("#view_contact_dob").text(obj.contact_dob);
			$("#view_contact_address").text(obj.contact_address);
			$("#view_contact_remarks").text(obj.remarks);
		}

		function editrow(obj) {
			console.log(obj);
			var numbers = JSON.parse(obj.contact_number);
			var email = JSON.parse(obj.contact_email);

			var site_url = "<?php echo base_url(''); ?>" ;
			if (!obj.contact_photo) {
				site_url = site_url + "images/default-pic.jpg"
			} else 	{
				site_url = site_url + "uploads/" + obj.contact_photo;
			}
			$('#contactEditPlaceholder').attr('src', site_url);
			console.log(site_url);
			if (obj.contact_for == 'lead') {
				$('#target_placeholder_edit').text('Lead *');
			} else if (obj.contact_for == 'customer') {
				$('#target_placeholder_edit').text('Customer *');
			}

			$("#employeeid1").val(obj.contact_id);	
			$("#target_type_edit").val(obj.lead_cust_name);
			$("#edit_lead_cust_id").val(obj.lead_cust_id);
			$("#edit_name").val(obj.contact_name);	
			$("#edit_desg").val(obj.contact_desg);
			$("#edit_phone").val(numbers[0]);
			$("#edit_phone2").val(numbers[1]);
			$("#edit_email").val(email[0]);
			$("#edit_email2").val(email[1]);
			$("#edit_buyerper option[value='"+obj.contact_type_id+"']").attr("selected",true);

			$("#edit_dob_picker").datetimepicker({
				ignoreReadonly:true,
				allowInputToggle:true,
				format:'YYYY-MM-DD',
				maxDate : moment(),
				defaultDate : obj.contact_dob
			});		
			$("#edit_dob").val(obj.contact_dob);
			$("#edit_address").val(obj.contact_address);
			$("#edit_remarks").val(obj.remarks);
			$("#edit_contact_for").val(obj.contact_for);
		}

		function edit() {
			var editObj = {};
			if($("#edit_lead_cust_id").val()==""){
				$("#target_type_edit").closest("div").find("span").text('Selecting a '+$("#target_placeholder_edit").text() + " is required.");
				$("#target_type_edit").focus();				
				return;
			} else {
				$("#target_type_edit").closest("div").find("span").text("");
			}
			/* if($("#edit_name").val()==""){
				$("#edit_name").closest("div").find("span").text("Name is required.");
				$("#edit_name").focus();				
				return;
			} else {
				$("#edit_name").closest("div").find("span").text("");
			}
			 */
			if($.trim($("#edit_name").val())==""){
				$("#edit_name").closest("div").find("span").text("Contact Name is required.");
				$("#edit_name").focus();
				return;
			}else if(!validate_name($.trim($("#edit_name").val()))){
				$("#edit_name").closest("div").find("span").text("No special characters allowed (except & _ - .)");
				$("#edit_name").focus();
				return;
			}else if(!firstLetterChk($.trim($("#edit_name").val()))){
				$("#edit_name").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#edit_name").focus();
				return;
			}else{
				$("#edit_name").closest("div").find("span").text("");
			}
			
			if (!validate_name($.trim($("#edit_desg").val()))) {
				$("#edit_desg").closest("div").find("span").text("Please Enter a valid Designation.");
				$("#edit_desg").focus();
				return ;
			} else {
				$("#edit_desg").closest("div").find("span").text("");
			}
			
			
			
			editObj.contact_number = {};
			var num1 = $.trim($("#edit_phone").val());
			var num2 = $.trim($("#edit_phone2").val());
			
			if(num1==""){
				$("#edit_phone").closest("div").find("span").text("Phone Number is required.");
				$("#edit_phone").focus();				
				return;
			} else if (!validate_PhNo(num1)) {
				$("#edit_phone").closest("div").find("span").text("Please enter 10 digit number.");
				$("#edit_phone").focus();
				return ;
			} else {
				$("#edit_phone").closest("div").find("span").text("");	
			}
			
			if ((num2!= '') && !validate_PhNo(num2)) {
				$("#edit_phone2").closest("div").find("span").text("Please enter 10 digit number.");
				$("#edit_phone2").focus();
				return ;
			} else {
				$("#edit_phone2").closest("div").find("span").text("");
			}
			
			if ((num1.length != 0) && (num2 == num1)) {
				$("#edit_phone2").closest("div").find("span").text("Phone Numbers can not be same.");
				$("#edit_phone2").focus();				
				return;
			} else {
				$("#edit_phone2").closest("div").find("span").text("");
			}
			
			/* email validation */
			
			editObj.contact_email = {};
			var email1  = $.trim($("#edit_email").val());
			var email2  = $.trim($("#edit_email2").val());
			
			
			if ((email1!= '') && !validate_email(email1)) {
				$("#edit_email").closest("div").find("span").text("Please check Email ID entered.");
				$("#edit_email").focus();
				return ;
			} else {
				$("#edit_email").closest("div").find("span").text("");
			}
			
			if ((email2!= '') && !validate_email(email2)) {
				$("#edit_email2").closest("div").find("span").text("Please check Email ID entered.");
				$("#edit_email2").focus();
				return ;
			} else {
				$("#edit_email2").closest("div").find("span").text("");				
			}
			
			if ((email1.length != 0) && (email1 == email2)) {
				$("#edit_email2").closest("div").find("span").text("Emails can not be same.");
				$("#edit_email2").focus();				
				return;
			} else {
				$("#edit_email2").closest("div").find("span").text("");
			}
			
			/* 
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
			*/
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
			
			if($.trim($("#edit_remarks").val()) != ""){
				if(!comment_validation($.trim($("#edit_remarks").val()))){
					$("#edit_remarks").closest("div").find("span").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
					$("#edit_remarks").focus();
					return;
				}else{
					$("#edit_remarks").closest("div").find("span").text(" ");
				} 
			}else{
				$("#edit_remarks").closest("div").find("span").text(" ");
			} 
			
			
			
			editObj.contact_id = $.trim($("#employeeid1").val());
			editObj.lead_cust_id = $("#edit_lead_cust_id").val();
			editObj.contact_name = $.trim($("#edit_name").val());
			editObj.contact_desg = $.trim($("#edit_desg").val());
			editObj.contact_type = $("#edit_buyerper").val();
			editObj.contact_dob = $("#edit_dob").val();
			editObj.contact_address = $.trim($("#edit_address").val());
			editObj.remarks = $.trim($("#edit_remarks").val());
			editObj.contact_for  = $.trim($("#edit_contact_for").val());/* hidden input */
			var mobileArray = [num1, num2];
			editObj.contact_number['phone'] = mobileArray;
			var emailArray = [email1, email2];
			editObj.contact_email['email'] = emailArray;
			
			console.log(editObj);
			loaderShow();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('manager_contacts/edit_leadContact'); ?>",
				dataType : 'json',
				data : JSON.stringify(editObj),
				cache : false,
				success : function(data){
					if(error_handler(data)){
						return;
					}
					cancel();
					var contact_id = editObj['contact_id'];
					contactUploadImage(contact_id, 'edit');
				}
			});	
		}

		function contactImageLoaded(param) {
			var input,elm;
			var imgid = '';
			if (param == 'add') {
				input = document.getElementById("imageInputAdd");
				imgid = '#contactAddPlaceholder';
				elm = $("#imageInputAdd");
			} else if (param == 'edit')	{
				input = document.getElementById("imageInputEdit");
				imgid = '#contactEditPlaceholder';
				elm = $("#imageInputEdit");
			}
			if (input.files && input.files[0]) {
				var valid_extensions = /(\.jpg|\.jpeg|\.gif|\.bmp|\.png|\.JPG|\.JPEG|\.GIF|\.BMP|\.PNG)$/i;
				if(!valid_extensions.test(input.files[0].name)){
					input.value = "";
					elm.closest('div').find('.error-alert').text("Invalid File type.");
					return;
				}else if(input.files[0].size >= 500000){
					input.value = "";
					elm.closest('div').find('.error-alert').text("File size is too long.");
					return;
				}else{
					elm.closest('div').find('.error-alert').text("");
				}
				var reader = new FileReader();
				reader.onload = function (e) {
					$(imgid).attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			} else {
				alert ("Invalid files");
			}
		}

		function contactUploadImage(contact_id, sender) {
			/* AJAX to submit form */
			var formData;
			if (sender == 'add') {
				formData = new FormData($('#imageUploadA11')[0]);
			} else if (sender == 'edit') {
				formData = new FormData($('#imageUploadE')[0]);
			}
			$.ajax({
				type: 'POST',
				enctype: 'multipart/form-data',
				url: "<?php echo site_url("manager_contacts/file_upload/"); ?>"+contact_id,
				data: formData,
				dataType : 'json',
				cache: false,
				contentType: false,
				processData: false,
				success : function(data){
					if(error_handler(data)) {
						return;
					}
					console.log(data);	
					pageload();		
				}
			});			
		}

		function capitalizeFirstLetter(string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		}

		function cancel(){
			$('#add_dob_picker').data("DateTimePicker").date(null);
			$('#edit_dob_picker').data("DateTimePicker").date(null);
			$('#contactAddPlaceholder').attr('src', '<?php echo base_url(); ?>images/default-pic.jpg');
			$('#contactEditPlaceholder').attr('src', '<?php echo base_url(); ?>images/default-pic.jpg');
			$('.error-alert').text('');
			$('.modal select').val('');
			$('.modal input[type="text"], textarea').val('');
			$('.modal input[type="radio"]').prop('checked', false);
			$("#lead").prop("checked", true)
			$('.modal input[type="checkbox"]').prop('checked', false);
			$('.modal').modal('hide');
		}

		function reset_add_modal() {
			setCustomers(manager_contacts_leads, 'lead');
			$("#add_dob_picker").datetimepicker({
				ignoreReadonly:true,
				allowInputToggle:true,
				format:'YYYY-MM-DD',
				maxDate : moment(),
				useCurrent : false
			});		
		}

	</script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="loader">
		<center><h1 id="loader_txt"></h1></center>  
	</div>
	<?php require 'demo.php'  ?>		
	<?php require 'manager_sidenav.php' ?>
	<div class="content-wrapper body-content">
		<div class="col-lg-12 column">
			<div class="row header1">
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
					<span class="info-icon">
						<div >		
							<img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" data-html="true" title="View all contacts for leads, customers and opportunities here. Click on the <img src='<?php echo site_url(); ?>images/new/Plus_Off.png' width='20px' height='20px' /> to add a new contact to any exist lead customer or opportunity. Click the <span class='glyphicon glyphicon-eye-open tooleye'></span> to view the contact and the <span class='glyphicon glyphicon-pencil tooleye'></span> to edit any details about the contact."/>
						</div>
					</span>
				</div>
				<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Contacts</h2>	
				</div>
				<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
					 <div class="addBtns">
						<a href="#add_modal" class="addPlus" data-toggle="modal">
							<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px" onclick="reset_add_modal();"/>
						</a>
					</div>
					<div style="clear:both"></div>
				</div>
				<div style="clear:both"></div>
			</div>
			<table class="table table-striped" id="tableTeam">
				<thead>  
					<tr>			
						<th class="table_header">Sl No</th>
						<th class="table_header" data-orderable="false"></th>
						<th class="table_header" style="text-align: left;">Name</th>
						<th class="table_header" style="text-align: left;">Belongs To</th>
						<th class="table_header" style="text-align: left;">Phone</th>
						<th class="table_header" style="text-align: left;">E-Mail</th>
						<th class="table_header" style="text-align: left;">Designation</th>
						<th class="table_header" style="text-align: left;">Contact Type</th>
						<th class="table_header" data-orderable="false"></th>
						<th class="table_header" data-orderable="false"></th>
					</tr>	
				</thead>  
				<tbody id="tablebody">					
				</tbody>    
			</table>
		</div>
		<div id="add_modal" class="modal fade" data-backdrop='static'>
			<div class="modal-dialog" >
				<div class="modal-content">
					<div class="modal-header modal-title">
						 <span class="close" onclick="cancel()">&times;</span>
						<center><h4 class="modal-title">Add Contact</h4></center>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-3" >
								<center>
									<form method="POST" enctype="multipart/form-data" id="imageUploadA11">
										<img width="100" height="100" id="contactAddPlaceholder" alt="Avatar" src='<?php echo base_url(); ?>images/default-pic.jpg' class="round" />
										<label for="imageInputAdd" class="custom-file-upload"> <i class="fa fa-cloud-upload"></i> Upload</label>
										<input type="file" class="form-control" accept="image/*"  name = "userfile" id="imageInputAdd" onchange="contactImageLoaded('add');" />
									</form>
								</center> 
								<p> Max file size 512kb </p>
								<span class="error-alert"></span>
							</div>
							<div class="col-md-9">
								<div class="row" style="margin: 10px 10px;">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<input type="radio" value="lead" id="lead" name="target_type" checked onclick="changeTarget(this);" /> <label for="lead"> Lead</label>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-4">
										<input type="radio" value="customer" id="customer" name="target_type" onclick="changeTarget(this);"> <label for="customer"> Customer</label>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-4">
										<input type="radio" value="opportunity" id="opportunity" name="target_type" onclick="changeTarget(this);"> <label for="opportunity"> Opportunity</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="target_type_add" id="target_placeholder_add">Lead </label>*
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<select class="form-control" id="target_type_add">
										</select>
										<span class="error-alert"></span>
									</div>
								</div>									
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="name">Contact Name *</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
											<input type="text" class="form-control" id="add_name"/>
											<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="designation">Designation</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" class="form-control" id="add_desg"/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="ctype">Contact Type</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<select class="form-control" id="add_buyerper">											
										</select>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="phone">Phone *</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" class="form-control" id="add_phone"/>
										<span class="error-alert"></span>
									</div>
								</div>									
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="alterph">Alternate Phone </label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" class="form-control" id="add_phone2"/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="email">E-mail</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" class="form-control" id="add_email"/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="email">Alternate E-mail</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" class="form-control" id="add_email2" />
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="email">Date of Birth</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<div class='input-group date' id='add_dob_picker'>
											<input id="add_dob" placeholder="Select Date of Birth" type='text' class="form-control" readonly="readonly" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="email">Address</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<textarea class="form-control closeinput" rows="4" cols="30" id="add_address" > </textarea>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="email">Remarks</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<textarea class="form-control closeinput" rows="4" cols="30" id="add_remarks"> </textarea>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer" >
							<button type="button" class="btn" onclick="add()" value="Save">Save</button>
							<button type="button" class="btn" onclick="cancel()" value="Cancle" >Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<div id="edit_modal" class="modal fade" data-backdrop='static'>
			<div class="modal-dialog" >
				<div class="modal-content">
					<div class="modal-header modal-title">
						<span class="close" onclick="cancel()">&times;</span>
						<center><h4 class="modal-title">Edit Contact Details</h4></center>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-3" >
								<center>
								<form method="POST" enctype="multipart/form-data" id="imageUploadE">
									<img width="100" height="100" id="contactEditPlaceholder" alt="Avatar" src='<?php echo base_url(); ?>images/default-pic.jpg' class="round" />
									<br />
									<label for="imageInputEdit" class="custom-file-upload"> <i class="fa fa-cloud-upload"></i> Upload</label>
									<input type="file" class="form-control" accept="image/*" name = "userfile" id="imageInputEdit" onchange="contactImageLoaded('edit');" />
								</form>
								</center> 
								<p> Max file size 512kb </p>
								<span class="error-alert"></span>									
							</div>
							<div class="col-md-9">
								<input type="hidden" id="edit_contact_for">
								<input type="hidden" id="edit_lead_cust_id"> </input>
								<input type="hidden" class="form-control" id="target_type_edit" autofocus/>
								<input type="hidden" id="employeeid1"/>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="name1">Contact Name *</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" class="form-control" id="edit_name"/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="designation1">Designation</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" class="form-control" id="edit_desg"/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="ctype1">Contact Type</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<select class="form-control" id="edit_buyerper">
										</select>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="phone1">Phone *</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" class="form-control" id="edit_phone"/>
										<span class="error-alert"></span>
									</div>
								</div>									
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="alterph1">Alternate Phone </label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" class="form-control" id="edit_phone2"/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="email1">E-mail</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" class="form-control" id="edit_email"/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="email2">Alternate E-mail</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" class="form-control" id="edit_email2"/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="email">Date of Birth</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<div class='input-group date' id='edit_dob_picker'>
											<input id="edit_dob" type='text' class="form-control" readonly="readonly" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="email">Address</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<textarea class="form-control" rows="4" cols="30" id="edit_address"> </textarea>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="email">Remarks</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<textarea class="form-control" rows="4" cols="30" id="edit_remarks"> </textarea>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn" onclick="edit()" value="Save">Save</button>
						<button type="button" class="btn" onclick="cancel()" value="Cancel" >Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<div id="view_modal" class="modal fade" data-backdrop='static'>
			<div class="modal-dialog" >
				<div class="modal-content">
					<div class="modal-header modal-title">
						 <span class="close" data-dismiss="modal">&times;</span>
						<center><h4 class="modal-title">Contact Details</h4></center>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-3 col-sm-12 col-xs-12" >
								<center>
									<img width="100" height="100" id="contact_img_view" alt="Avatar" src='<?php echo base_url(); ?>images/default-pic.jpg' class="round" />
								</center> 
							</div>
							<div class="col-md-9 col-sm-12 col-xs-12">
								<div class="row">
									<div class="col-md-4 col-sm-6 col-xs-6">
										<label class="view_placeholder" id="target_placeholder_view">Leads</label> 
									</div>
									<div class="col-md-8 col-sm-6 col-xs-6">
										<label id="view_contact_for"></label>
									</div>
								</div>									
								<div class="row">
									<div class="col-md-4 col-sm-6 col-xs-6">
										<label class="view_placeholder">Contact Name</label> 
									</div>
									<div class="col-md-8 col-sm-6 col-xs-6">
										<label id="view_contact_name"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-6 col-xs-6">
										<label class="view_placeholder">Designation</label> 
									</div>
									<div class="col-md-8 col-sm-6 col-xs-6">
										<label id="view_contact_desg"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-6 col-xs-6">
										<label class="view_placeholder">Contact Type</label> 
									</div>
									<div class="col-md-8 col-sm-6 col-xs-6">
										<label id="view_contact_buyerper"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-6 col-xs-6">
										<label class="view_placeholder">Phone</label> 
									</div>
									<div class="col-md-8 col-sm-6 col-xs-6">
										<label id="view_contact_phone1"></label>
									</div>
								</div>									
								<div class="row">
									<div class="col-md-4 col-sm-6 col-xs-6">
										<label class="view_placeholder">Alternate Phone </label> 
									</div>
									<div class="col-md-8 col-sm-6 col-xs-6">
										<label id="view_contact_phone2"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-6 col-xs-6">
										<label class="view_placeholder">E-mail</label> 
									</div>
									<div class="col-md-8 col-sm-6 col-xs-6">
										<label id="view_contact_email1"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-6 col-xs-6">
										<label class="view_placeholder">Alternate E-mail</label> 
									</div>
									<div class="col-md-8 col-sm-6 col-xs-6">
										<label id="view_contact_email2"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-6 col-xs-6">
										<label class="view_placeholder">Date of Birth</label> 
									</div>
									<div class="col-md-8 col-sm-6 col-xs-6">
										<label id="view_contact_dob"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-6 col-xs-6">
										<label class="view_placeholder">Address</label> 
									</div>
									<div class="col-md-8 col-sm-6 col-xs-6">
										<p id="view_contact_address"></p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-6 col-xs-6">
										<label class="view_placeholder"">Remarks</label> 
									</div>
									<div class="col-md-8 col-sm-6 col-xs-6">
										<p id="view_contact_remarks"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer" >
						<button type="button" class="btn" onclick="cancel()" value="Cancel" >Close</button>
					</div>
				</div>
			</div>
		</div>		
	</div>
	<?php require 'footer.php' ?>
</body>
</html>
