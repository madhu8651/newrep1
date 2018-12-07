<style>
	.addSupport hr{
		margin: 5px 0px;
	}
	.addSupport{
		width: 150px;
		position: absolute;
		right: 15px;
		background: #fff;
		z-index: 999;
		border-radius: 5px;
		padding: 10px 5px;
		border: 1px solid #ddd;
		box-shadow: 1px 1px 10px #000;
	}
	
</style> 
<script>
$(document).ready(function(){
	var Support = '<div class="addSupport none">'+
					'<div>'+
						'<a href="javascript:void(0)" onclick="add_request(\'opportunity\')">Opportunity</a>'+
					'</div><hr>'+
					'<div>'+
						'<a href="javascript:void(0)" onclick="add_request(\'customer\')">Customer </a>'+
					'</div>'+
				'</div>';
	$("#addSupport").append(Support);
	var isInside = false;
					
	$("#addSupport").click(function () {
		$(".addSupport").show();
	});
	
	$("#addSupport, .addSupport").hover(function () {
		isInside = true;
	}, function () {
		isInside = false;
	})

	$(document).mouseup(function () {
		if (!isInside)
		$(".addSupport").hide();
	});
});
function add_request(type){
		//type = opportunity/customer
	$("#leadinfoAdd .assignment").hide();
	$('#leadinfoAdd .opportunity, #leadinfoAdd .customer').hide();
	
	if(type == 'opportunity'){
		$('#leadinfoAdd .opportunity').show();
	}else{
		$('#leadinfoAdd .customer').show();
	}
	
	
	$("#popupType").val(type);
	var obj={};
	obj.selectionType = type;
	loaderShow();
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/associatedTo'); ?>",
        dataType : 'json',
		data: JSON.stringify(obj),
        cache : false,
        success : function(data){
			loaderHide();
			if(error_handler(data)){
				return;
			}
			
			$('#leadinfoAdd').modal('show');
			var options = ""; 
			$('#leadinfoAdd .opportunity select, #leadinfoAdd .customer select').empty();
			options += "<option value=''>Select</option>";
			if(type == 'opportunity' && data.hasOwnProperty('opportunity')){
				$.each(data.opportunity , function(key,val){
					options += "<option value='"+val.opportunity_id+"'>"+ val.opportunity_name+"</option>";
				});
				$('#leadinfoAdd .opportunity select').html(options);
			}else{
				$.each(data.customer , function(key,val){
					options += "<option value='"+val.leadid+"'>"+ val.leadname+"</option>";
				});
				$('#leadinfoAdd .customer select').html(options);
			}
			
			if(data.processType.length > 0){
				var select = $("#process_type"), processOpt = "<option value=''>Choose Process Type</option>";
				select.empty(); 
				$.each(data.processType , function(key,val){
					processOpt += "<option value='"+val.lookup_id+"'>"+ val.lookup_value+"</option>"; 
				})
				select.append(processOpt);
			}else{
				$("#process_type").html("<option value=''>Process type not available</option>");
			}
			
			if(data.contactType.length > 0){
				var select = $("#add_buyerper"), processOpt = "<option value=''>Choose Contact Type</option>";
				select.empty(); 
				$.each(data.contactType , function(key,val){
					processOpt += "<option value='"+val.contact_type_id+"'>"+ val.contact_type_name+"</option>"; 
				})
				select.append(processOpt);
			}else{
				$("#add_buyerper").html("<option value=''>Contact type not available</option>");
			}
			

        },
        error:function(data){
            network_err_alert();
        }
    });
	
	
}
function fetchContact(element){
	var obj={};
	obj.selectionType = $("#popupType").val();
	obj.id = $(element).val();
	
	$("#addContactLink").html('<a href="javascript:void(0)" onclick="addContact(\''+obj.id+'\',\''+obj.selectionType+'\')" title="Add Contact" class="glyphicon glyphicon-plus">Add</a>');
	$("#ticketName").val($(element).find('option:selected').text());
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/getProductAndContacts'); ?>",
        dataType : 'json',
		data: JSON.stringify(obj),
        cache : false,
        success : function(data){
			var options = "";
			
			$('#MultipleContact').empty();
			var errorMsg ="";
			//“Check for industry/location/product for the selected customer”.
			if(data.businessLocation == false && data.industry == false && data.product.length <= 0){
				errorMsg = 'Check for industry and location and product for the selected customer';
			}else if(data.businessLocation == false){
				errorMsg = 'Check for location for the selected customer';
			}else if(data.industry == false){
				errorMsg = 'Check for industry for the selected customer';
			}else if(data.product.length <= 0){
				errorMsg = 'Check for product for the selected customer';
			} 
			
			if(errorMsg != ""){
				$.alert({
					title: 'L Connectt',
					content: errorMsg,
					closeIcon: true,
					closeIconClass: 'fa fa-close',
				});
			}
			
			
			container = "MultipleContact";
			//------------------------ contact
			if(data.contact.length > 0){
				var contactOpt = '<div><label><input type="checkbox" onchange="chkAll(\''+container+'\')" class="selectAll"/> Select All</label></div>';
				contactOpt += '<ol>';
				$.each(data.contact , function(key,val){
					contactOpt += "<li class='opt'><label><input type='checkbox' value='"+val.contact_id+"'/> "+ val.contact_name+"</label><input value='"+val.contact_email+"' type='hidden'/></li>"; 
				})
				contactOpt += '</ol>';
				$('#MultipleContact').html(contactOpt);
			}else{
				$('#MultipleContact').html('Contact(s) not available');
			}
			
			//------------------------ Product
			if(data.product.length > 0){
				var productOpt = '<option value="">Select</option>';
				$.each(data.product , function(key,val){
					//productOpt += "<li class='opt'><label><input type='checkbox' value='"+val.productId+"'/> "+ val.productName+"</label></li>"; 
					productOpt += "<option value='"+val.productId+"'>"+ val.productName+"</option>"; 
				})
				productOpt += '</ol>';
				$("#product_id").html(productOpt);
			}else{
				$("#product_id").html('<option value="">No product(s) available</option>');
			}
        },
        error:function(data){
            network_err_alert();
        }
    });
}
function chkAll(wrapper){
	//alert(wrapper)
	/* $("#stageExecutive li input[type=checkbox]").each(function(){
		if($(this).prop('checked')==true){
			stageExec.push($(this).val());
		}        
	});  */
	if($("#"+wrapper+" .selectAll").prop('checked')==true){
		$("#"+wrapper).find("li.opt input[type=checkbox]").prop("checked", true);
	}else{
		$("#"+wrapper).find("li.opt input[type=checkbox]").prop("checked", false);
	}
}

function assignRequestSave(){
	/* 1.executive (like old function)
	2.stageExecutive(like oldfunction)

	4.process
	6.id > opp_cust_id
	7.request_id */
	var assignObj ={};
	
	assignObj.id = $("#idHidden").val();
	assignObj.request_id = $("#requestIdHidden").val();
	assignObj.process = $("#processTypeHidden").val();
	assignObj.executive = {};
	assignObj.stageExecutive = {};
	
	var user =[];
	$("#usr_list li input[type=checkbox]").each(function(){
		if($(this).prop('checked')==true){
			user.push($(this).val());
		}        
	});
	
	
	if($("#TypeHidden").val() == 'exec'){
		assignObj.executive = {'id':user, 'type':'supportOwners'};
	}else if($("#TypeHidden").val() == 'stageExec'){
		assignObj.stageExecutive = {'id':user, 'type':'stageOwners'};
	}
	if(user.length <= 0){
		$("#usr_list").next('.error-alert').text('Select at least one user to assign.');
		return;
	}else{
		$("#usr_list").next('.error-alert').text('');
	}
	
	loaderShow();
	
	$.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/supportAssigning/'); ?>",
        dataType : 'json',
		data: JSON.stringify(assignObj),
        cache : false,
        success : function(data){
			loaderHide();
			if(error_handler(data)){
				return;
			}
			
        },
        error:function(data){
            network_err_alert();
        }
    });
}
function assign(assignment, type){
	var requestobj ={};
	if(typeof(assignment) != "undefined"){
		augData = assignment.split(',');
		/* ------For save function------ */
		$("#TypeHidden").val(type);
		$("#requestIdHidden").val(augData[0]);
		$("#idHidden").val(augData[2]);
		$("#processTypeHidden").val(augData[4]);
		/* ------------ */
		requestobj.request_id   = augData[0];
		requestobj.product = augData[1];
		requestobj.opp_cust_id = augData[2];
		requestobj.assignedType = augData[3];
		/* requestobj.process = augData[4]; */
		requestobj.newSupport = "";
	}else{
		requestobj.type = $("#popupType").val();//cust/opp
		//----------------------------
		requestobj.opp_cust_id = "";
		if(requestobj.type == 'customer'){
			requestobj.opp_cust_id = $("#leadinfoAdd .customer").find('select').val();//cust ID/opp  ID
			if(requestobj.opp_cust_id == ""){
				$("#leadinfoAdd .customer").find('select').focus();
				$("#leadinfoAdd .customer").find('.error-alert').text('Select an opportunity');
				return;
			}else{
				$("#leadinfoAdd .customer").find('.error-alert').text('');
			}
		}else{
			if(requestobj.opp_cust_id == ""){
				$("#leadinfoAdd .opportunity").find('select').focus();
				$("#leadinfoAdd .opportunity").find('.error-alert').text('Select an opportunity');
				return;
			}else{
				$("#leadinfoAdd .opportunity").find('.error-alert').text('');
			}
			requestobj.opp_cust_id = $("#leadinfoAdd .opportunity").find('select').val();//cust ID/opp ID
		}
		//----------------------------
		requestobj.product=$("#product_id").val();
		if(requestobj.product == ""){
			$("#product_id").focus();
			$("#product_id").next('.error-alert').text('Select a product');
			return;
		}else{
			$("#product_id").next('.error-alert').text('');
		}
		//----------------------------
		requestobj.newSupport = "newSupport";				
	}
	//In assignUserList, Please send selected industry,location and product with key is same as name.
	//and also send newSupport
	//var assignment = data[i].product +','+ data[i].opp_cust_id +','+'newSupport';
	$("#leadinfoAdd .assignment").show();
	$("#executive").empty();
	$("#stageExecutive").empty(); 
	
	loaderShow();
	$.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/assignUserList'); ?>",
        dataType : 'json',
		data: JSON.stringify(requestobj),
        cache : false,
        success : function(data){
			loaderHide();
			if(error_handler(data)){
				return;
			}
			if(typeof(assignment) != "undefined"){
				$('#assign_request').modal('show');
				if(data.length > 0){
					$('#usr_list').html(UserListHtml(data , 'usr_list'));
				}else{
					$('#usr_list').html('No user matches the parameters of the support ccyle');
				}
			}else{
				//------------------------ executive supportOwners
				if(data.supportOwners.length > 0){
					$('#executive').html(UserListHtml(data.supportOwners , 'executive'));
				}else{
					$('#executive').html('Executive Not available');
				}
				//------------------------ stageExecutive stageOwners
				if(data.stageOwners.length > 0){
					$('#stageExecutive').html(UserListHtml(data.stageOwners , 'stageExecutive'));
				}else{
					$('#stageExecutive').html('Stage Executive Not available');
				}
			}
        },
        error:function(data){
            network_err_alert();
        }
    });
}
function UserListHtml(data, cointener){
	var contactOpt = '<div><label><input type="checkbox" onchange="chkAll(\''+cointener+'\')" class="selectAll"/> Select All</label></div>';
	contactOpt += '<ol>';
	$.each(data , function(key,val){
		contactOpt += "<li class='opt'><label><input type='checkbox' value='"+val.user_id+"'/> "+ val.user_name+"</label></li>"; 
	})
	contactOpt += '</ol>';
	return contactOpt;
}
function addContact(id, type){
	$("#add_dob_picker").datetimepicker({
		ignoreReadonly:true,
		allowInputToggle:true,
		format:'DD-MM-YYYY',
		maxDate : moment(),
		useCurrent : false
	});

	$("#custOppId").val(id+'-'+type);//hidden fieldsto store customer/opportunity id for save contact
	$('#addContact').modal('show');
}
function closeContactFrom(){
	//$("#custOppId").val('');
	$("#addContact input").val('');
	$("#addContact select").val('');
	$("#addContact textarea").val('');
	$('#addContact').modal('hide');
}
function isEmailCheck(){
	var isEmail = [];
	if($("#email_Check").is(":checked")){
		$("#emailBody").attr('placeholder','Mandatory');
		$("#MultipleContact li").each(function(){
			if($(this).find('input[type=checkbox]').is(":checked")){
				var email = JSON.parse($(this).find('input[type=hidden]').val());
				$.each(email.email, function(key, val){
					if($.trim(val) != ""){
						isEmail.push($.trim(val))
					}
				})
				
			}
		});
		if(isEmail.length > 0){
			console.log(isEmail);
		}else{
			alert("Customer/Opportunity does not have an email id");
			$("#email_Check").prop("checked", false);
		}
		
	}else{
		$("#emailBody").attr('placeholder','Optional');
	}
} 
function add() {
	var addObj = {};
	
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
	
	
	var idAndType = $("#custOppId").val().split('-')
	
	addObj.contact_for = idAndType[1]; 
	addObj.lead_cust_id = idAndType[0]; 
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
				loaderHide();
				contactUploadImage(data['contact_id'], 'add');
				closeContactFrom();
				
				//------------------------ contact
				var contactOpt = '';
				var email = {"email":addObj.contact_email['email']};
				
				contactOpt += "<li class='opt'><label><input type='checkbox' value='"+data['contact_id']+"'/> "+ addObj.contact_name+"</label><input value='"+JSON.stringify(email)+"' type='hidden'/></li>";
				
				if($('#MultipleContact ol').length > 0){
					$('#MultipleContact  ol').append(contactOpt);
				}else{
					$('#MultipleContact').append('<div><label><input type="checkbox" onchange="chkAll(\'MultipleContact\')" class="selectAll"/> Select All</label></div><ol>'+contactOpt+'</ol>');
				}
				
		}
	});			
}
//contact image form submit 
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
			//pageload();		
		}
	});			
}
//contact image validation
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

var raise_data={};
function save_request(){
   /*  var activity_ownerArray=[];
    $("#activity_owner div input[type=checkbox]").each(function(){
        if($(this).prop( "checked" ) == true){
            activity_ownerArray.push($(this).val());
        }
    });
    if(activity_ownerArray.length <= 0 ){
        $("#activity_owner").next(".error-alert").text("Select atleast one owner.");
        return;
    }else{
        $("#activity_owner").next(".error-alert").text("");
    } */
    /*---------------------support_criticality----------------------------*/
    /* if($("#support_criticality").val() == ""){
        $("#support_criticality").next(".error-alert").text("Support Criticality is required.");
        $("#support_criticality").focus();
        return;
    }else{
         $("#support_criticality").next(".error-alert").text("");
    } */
    /*---------------------Committed TAT----------------------------*/
   /*  if($.trim($("#c_tat input[type=text]").val()) == ""){
        $("#c_tat").next(".error-alert").text("Support Criticality is required.");
        return;
    }else{
         $("#c_tat").next(".error-alert").text("");
    } */
    /*---------------------Request Remarks----------------------------*/
    /* if($.trim($("#remarks").val()) == ""){
        $("#remarks").next(".error-alert").text("Request Remarks is required.");
         $("#remarks").focus()
        return;
    }else{
         $("#remarks").next(".error-alert").text("");
    }
    var clientContactArray=[];
    $("#client_contact div input[type=checkbox]").each(function(){
        if($(this).prop( "checked" ) == true){
            clientContactArray.push($(this).val());
        }
    });
    var activityowner=[];
    $("#activity_owner div input[type=checkbox]").each(function(){
        if($(this).prop( "checked" ) == true){
            activityowner.push($(this).val());
        }
    });
    var email_list=[];
    $("#email_list li").each(function(){
       email_list.push($(this).attr('id'));
    }); */
    $("#leadinfoAdd .error-alert").text("")
    var requestobj={};
    requestobj.type = $("#popupType").val();//cust/opp
	//----------------------------
	requestobj.id = "";
	if(requestobj.type == 'customer'){
		requestobj.id = $("#leadinfoAdd .customer").find('select').val();//cust ID/opp  ID
		if(requestobj.id == ""){
			$("#leadinfoAdd .customer").find('select').focus();
			$("#leadinfoAdd .customer").find('.error-alert').text('Select an opportunity');
			return;
		}else{
			$("#leadinfoAdd .customer").find('.error-alert').text('');
		}
	}else{
		if(requestobj.id == ""){
			$("#leadinfoAdd .opportunity").find('select').focus();
			$("#leadinfoAdd .opportunity").find('.error-alert').text('Select an opportunity');
			return;
		}else{
			$("#leadinfoAdd .opportunity").find('.error-alert').text('');
		}
		requestobj.id = $("#leadinfoAdd .opportunity").find('select').val();//cust ID/opp ID
	}
	//----------------------------
	var cont=[];
    
	$("#MultipleContact li input[type=checkbox]").each(function(){
		if($(this).prop('checked')==true){
			cont.push($(this).val());
		}        
	});
	if(cont.length <= 0){
		$("#MultipleContact").next('.error-alert').text('Select at least one contact');
		return;
	}else{
		$("#MultipleContact").next('.error-alert').text('');
	}
	requestobj.contact = cont;//Contact id
	//----------------------------
	requestobj.process = $("#process_type").val();//Process Type id
	if(requestobj.process == ""){
		$("#process_type").focus();
		$("#process_type").next('.error-alert').text('Select process type');
		return;
	}else{
		$("#process_type").next('.error-alert').text('');
	}
	//----------------------------
	requestobj.product=$("#product_id").val();
	if(requestobj.product == ""){
		$("#product_id").focus();
		$("#product_id").next('.error-alert').text('Select a product');
		return;
	}else{
		$("#product_id").next('.error-alert').text('');
	}
	//-------------------------------
	requestobj.ticketName= $.trim($("#ticketName").val());
	if(requestobj.ticketName == ""){
		$("#ticketName").focus();
		$("#ticketName").next('.error-alert').text('Ticket name is required');
		return;
	}else{
		$("#ticketName").next('.error-alert').text('');
	}
	//-------------------------------
	requestobj.remarks = $.trim($("#remarks").val());
	if(validateSpclChr(requestobj.remarks) == "invalid"){
		$("#remarks").next(".error-alert").text("No special characters allowed  ( \ ) ( } { / ; ' '' $ . )" );
		$("#remarks").focus();
		return;
	}
	//-------------------------------
	//Executive
	var exec =[];
	$("#executive li input[type=checkbox]").each(function(){
		if($(this).prop('checked')==true){
			exec.push($(this).val());
		}        
	});
	
	requestobj.executive = {'id':exec, 'type':'supportOwners'};
	//Stage Executive
	var stageExec =[];
	$("#stageExecutive li input[type=checkbox]").each(function(){
		if($(this).prop('checked')==true){
			stageExec.push($(this).val());
		}        
	}); 
	requestobj.stageExecutive = {'id':stageExec, 'type':'stageOwners'};
	
	requestobj.emailBody = "";//emai body
	
	
	if($("#email_Check").prop('checked')==true){
		requestobj.isEmail = true;//need to send emai body
		
		requestobj.emailBody = $.trim($("#emailBody").val());//emai body
		if(requestobj.emailBody == ""){
			$("#emailBody").next(".error-alert").text("Email body is required.");
			$("#emailBody").focus();
			return;
		}else if(validateSpclChr(requestobj.emailBody) == "invalid"){
			$("#emailBody").next(".error-alert").text("No special characters allowed  ( \ ) ( } { / ; ' '' $ . )" );
			$("#emailBody").focus();
			return;
		}else{
			$("#emailBody").next(".error-alert").text("");
		}
	}else{
		requestobj.isEmail = false;//need not to send emai body
	}
	
    console.log(requestobj);
      //loaderShow();
       //loaderHide();
    $.ajax({ 	
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/raiseSupportRequest'); ?>",
        data: JSON.stringify(requestobj),
        dataType : 'json',
        cache : false,
        success : function(data){
                loaderHide();
                if(error_handler(data)){
                    return;
                }
				//alert :-- “A Support Ticket has been successfully created! The ticket number is ___ ”.
				/* Before saving check whether a support cycle exists for the entered combination of product,industry,location,process type.If not then alert ”No Support cycle exists for the entered combination”. */
                alert(data.message);
				/* $.alert({
					title: 'L Connectt',
					content: errorMsg,
					closeIcon: true,
					closeIconClass: 'fa fa-close',
				}); */
					/* message: "An Support with same name already exists."
					qualifier: false
					status: false */
                /*if (data.qualifier == true) {
                    setup_questionnaire(data.qualifier_data);
                    raise_data=data.request_data;
                    console.log(raise_data);
                } */
        },
        error:function(data){
            network_err_alert();
        }
    });
}
function cancelRequest(){
	$("#leadinfoAdd").modal('hide');
	$("#leadinfoAdd input").val('');
	$("#leadinfoAdd select").val('');
	$("#leadinfoAdd textarea").val('');
	$("#leadinfoAdd .multiselect").empty();
	$("#leadinfoAdd input[type=checkbox]").prop("checked", false);
}
/* ------------------------------------------- */
/* ------------------------------------------- */
/* ------------------------------------------- */
function get_details(){
    cust_id="";
    $("#client_contact").html("");
    var select_val = $("#request_for").val();
    if(select_val == "customer"){
            $(".cust_row").show();
            $(".opp_row").hide();
            $('#opportunity_ids option').remove();
            $('#product_id option').remove();
            get_customers();
    }
    if(select_val == "opportunity"){
            $(".cust_row").hide();
            $(".opp_row").show();
            $('#product_id option').remove();
            get_opportunity();
            $("#customer").typeahead("destroy");
    }
}
var cust_id="";
function get_customers(){
    cust_id="";
    $.ajax({ 
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/get_customers'); ?>",
        dataType : 'json',		
        cache : false,
        success : function(data){
            if(error_handler(data)){
                return;
            }
            var customerData = [];
            for(i=0; i<data.length; i++){
                customerData.push(data[i]);
                var dataSource= new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('customer_id','customer_name'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    local: customerData									
                });
            }
            if(customerData.length>0){
                dataSource.initialize();
                $('#customer').typeahead({
                        minLength: 0,
                        highlight: true,
                        hint: false
                },{ 
                        name: 'email',
                        display: function(item) {
                                return item.customer_name
                        },
                        source: dataSource.ttAdapter(),
                        suggestion: function(data) {
                                return '<b>' + data.customer_name + '</b>' 
                        }
                });
                $('#customer').on('typeahead:selected', function (e, datum){					
                        var match=1;
                        if($.trim($(this).text())== datum.customer_id){
                            match=0;
                            return;
                        }
                        if(match==0){
                            $('#customer').val("");
                            return;
                        }
                        if ($("#customer").length <= 1) {
                            $('#customer').closest("div").find("span.error-alert").text("");
                            loaderShow();
                            cust_id = datum.customer_id;
                            
                            var leadObj = {};
                            leadObj.customerid = datum.customer_id;
                            loaderShow();
                            $.ajax({ 	
                                type : "POST",
                                url : "<?php echo site_url('manager_supportrequestcontroller/get_contactsforCustomer'); ?>",
                                data:JSON.stringify(leadObj),
                                dataType : 'json',
                                cache : false,
                                success : function(data){
                                    loaderHide();
                                    if(error_handler(data)){
                                        return;
                                     }
                                    var select = $("#client_contact"), options = "";
                                    select.empty();      
                                    for(var i=0;i<data.length; i++)	{
                                        options += "<div><label><input type='checkbox' value='"+data[i].contact_id+"'/> "+ data[i].contact_name +"</label></div>";                                                }
                                    select.append(options);
                                }
                            });
                            $.ajax({ 	
                                type : "POST",
                                url : "<?php echo site_url('manager_supportrequestcontroller/get_CustomerProduct'); ?>",
                                data: JSON.stringify(leadObj),
                                dataType : 'json',
                                cache : false,
                                success : function(data){
                                    loaderHide();
                                    if(error_handler(data)){
                                        return;
                                    }
                                    var select = $("#product_id"), options = "<option value=''>Choose Product</option>";
                                    select.empty();      
                                    for(var i=0;i<data.length; i++)	{
                                            options += "<option value='"+data[i].prod_id+"'>"+ data[i].prod_name+"</option>";            
                                    }
                                    select.append(options);
                                },
                                error:function(contact){
                                    network_err_alert();
                                }
                            });
                        }else{
                            $('#customer').closest("div").find("span.error-alert").text("Can add only one lead");
                            return;
                        }
                    });
		 }
		},
                error:function(data){
                    network_err_alert();
                }
            });
            
        }
function get_opportunity(){
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/get_opportunitylist'); ?>",
        dataType : 'json',
        cache : false,
        success : function(data){
            loaderHide();
            if(error_handler(data)){
                            return;
                     }
            var select = $("#opportunity_ids"), options = "<option value=''>Choose Opportunities</option>";
            select.empty();      
            for(var i=0;i<data.length; i++)	{
                    options += "<option value='"+data[i].opportunity_id+"'>"+ data[i].opportunity_name+"( "+data[i].lead_cust_name+" )</option>";            
            }
            select.append(options);
        },
        error:function(data){
            network_err_alert();
        }
    });
}
function get_contacts(id){
   var oppobj={};
    oppobj.oppo_id=id;
    $.ajax({ 	
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/get_contactsforOpportunity'); ?>",
        data: JSON.stringify(oppobj),
        dataType : 'json',
        cache : false,
        success : function(contact){
            loaderHide();
            if(error_handler(contact)){
                return;
            }
            var select = $("#client_contact"),options="";
            select.empty();      
            for(var i=0; i<contact.length; i++){
                    options += "<div><label><input type='checkbox' value='"+contact[i].contact_id+"'/> "+ contact[i].contact_name +"</label></div>";
            }
            select.append(options);
        },
        error:function(contact){
            network_err_alert();
        }
    });
	
   /*  var oppobj={};
    oppobj.oppo_id=id;
    $.ajax({ 	
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/get_OpportunityProducts'); ?>",
        data: JSON.stringify(oppobj),
        dataType : 'json',
        cache : false,
        success : function(data){
            loaderHide();
            if(error_handler(data)){
                return;
            }
            var select = $("#product_id"), options = "<option value=''>Choose Product</option>";
            select.empty();      
            for(var i=0;i<data.length; i++)	{
                    options += "<option value='"+data[i].prod_id+"'>"+ data[i].prod_name+"</option>";            
            }
            select.append(options);
        },
        error:function(data){
            network_err_alert();
        }
    }); */
}
function getuserlist(){
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/get_emails'); ?>",
        dataType : 'json',					
        cache : false,
        success : function(data){
            if(error_handler(data)){
                return;
            }
            var jsonData = data;
            var dataSource = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('user_name', 'email','department_name','designation'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                local: jsonData
            });
            dataSource.initialize();
            $('#email_members').typeahead({
                minLength: 0,
                highlight: true,
                hint: false
            },{ 
                name: 'email',
                display: function(item) {
                    return item.user_name + ' ( ' + item.department_name+ ' ) ( ' + item.designation +' )'
                },
                source: dataSource.ttAdapter(),
                suggestion: function(data) {
                    return '<b>' + data.user_name + '–' + data.user_id + '</b>' 
                }
            });
            $('#email_members').on('typeahead:selected', function (e, datum) {
                var match=1;
                $("#email_list li").each(function(){
                    if($.trim($(this).attr('id'))== datum.user_id){
                            match=0;
                    }
                });
                if(match==0){
                    $('#email_members').val("");
                    return;
                }
                if ($("#email_list li").length <= 12) {
                    $("#email_list").append("<li id="+ datum.user_id+">"+ datum.user_name+" <a style='color:red;' class='glyphicon glyphicon-remove-sign' onclick='del(\""+datum.user_id+"\")'></a></li>");
                    $('#email_members').closest("div").find("span.error-alert").text("");
                    $('#email_members').val("");	
                }else{
                    alert("Can't add more than 12 Users");
                    $('#email_members').closest("div").find("span.error-alert").text("Can't add more than 12 Users");
                    return;
                }

            });
        }
    });
}
function del(id){	
    $("#"+id).remove();
}
function cancel1(){
   // $('#c_tat').data("DateTimePicker").clear();
    $('.modal').modal('hide');
    $('.modal .multiselect').html('');
    $('.form-control').val("");
    $("#first_section input , #first_section select ").removeAttr("disabled");
    $('#second_section').hide();
    $("#customer").typeahead("destroy");  
    $('#client_contact ,#activity_owner').html("");
    $('#client_contact').removeClass("disable")
} 
function chk_paramaters(){
    var request_name = $.trim($("#request_name").val());
    if(request_name == ""){
        $("#request_name").next(".error-alert").text("Request Name is required.");
        $("#request_name").focus();
        return;
    }else if(!validate_name(request_name)){
        $("#request_name").next(".error-alert").text("No special characters allowed (except &, _,-,.)");
        $("#request_name").focus();
        return;
    }else if(!firstLetterChk(request_name)){
        $("#request_name").next(".error-alert").text("First letter should not be Numeric or Special character.");
        $("#request_name").focus();
        return;		
    }else{
        $("#request_name").next(".error-alert").text("");
    }
    if($("#request_for").val() == ""){
        $("#request_for").next(".error-alert").text("Request For is required.");
        $("#request_for").focus();
        return;
    }else{
         $("#request_for").next(".error-alert").text("");
    }
    if($("#request_for").val() == "opportunity"){
        if($("#opportunity_ids").val() == ""){
            $("#opportunity_ids").next(".error-alert").text("Opportunity is required.");
            $("#opportunity_ids").focus();
            return;
        }else{
             $("#opportunity_ids").next(".error-alert").text("");
        }
    }
    if($("#request_for").val() == "customer"){
        if(cust_id == ""){
            $("#leadField").find(".error-alert").text("Customer is required.");
            $("#customer").focus();
            return;
        }else{
             $("#leadField").find(".error-alert").text("");
        }
    }
    if($("#product_id").val() == ""){
        $("#product_id").next(".error-alert").text("Product is required.");
        $("#product_id").focus();
        return;
    }else{
         $("#product_id").next(".error-alert").text("");
    }
    var clientContactArray=[];
    $("#client_contact div input[type=checkbox]").each(function(){
        if($(this).prop( "checked" ) == true){
            clientContactArray.push($(this).val());
        }
    });
    if(clientContactArray.length <= 0 ){
        $("#client_contact").next(".error-alert").text("Select atleast one Client Contact.");
        return;
    }else{
        $("#client_contact").next(".error-alert").text("");
    }
    /*---------------------process_type ----------------------------*/
    if($("#process_type").val() == ""){
        $("#process_type").next(".error-alert").text("Support Process is required.");
        $("#process_type").focus();
        return;
    }else{
         $("#process_type").next(".error-alert").text("");
    }
    var Obj={};
    Obj.request_name = request_name;
    Obj.request_for = $("#request_for").val();
    /*---------------------opportunity_ids----------------------------*/
    if($("#request_for").val() == "opportunity"){
        Obj.opportunity_id = $("#opportunity_ids").val();
    }else{
        Obj.opportunity_id="";
    }
    /*---------------------customer----------------------------*/
    if($("#request_for").val() == "customer"){
        Obj.customer_id = cust_id;
    }else{
        Obj.customer_id="";
    }
    Obj.client_contact = clientContactArray;    
    Obj.process_type = $("#process_type").val();
    Obj.product_id = $("#product_id").val();
    loaderShow();
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/get_process_cycle'); ?>",
        dataType : 'json',
        data:JSON.stringify(Obj),
        cache : false,
        success : function(data){
            if(error_handler(data)){
                return;
            }
            if(data==0){
                loaderHide();
                alert("Provided parameters does not match to any cycle, Please contact Admin.");
                return ;
            }else if(data==1){
                loaderHide();
                alert("1st stage doesnt have allocation matrix.");
                return;
            }else if(data==2){
                loaderHide();
                alert("There is no location and industry is allocated to customer");
                return;
            }else{
                loaderHide();
              
              if(data.contacts_list.length >0){
                $("#second_section").show();
                $("#client_contact").addClass("disable");
                $("#first_section input , #first_section select ").attr("disabled", "disabled");
                $("#chk_avail_btn").addClass("none");
                $("#stage").val(data.stage);
                $("#cycle").val(data.cycle);
                $("#industry").val(data.industry);
                $("#location").val(data.location);
                $("#opp_cust_id").val(data.opp_id);
                var select = $("#activity_owner"),options="";
                select.empty();   
                 options += "<div><label><input class='selectAll' type='checkbox' onchange='chkAll(\"activity_owner\")' /> Select All </label></div>";  
                for(var i=0; i<data.contacts_list.length; i++){
                    if(data.contacts_list[i].manager_module != "0"){
                      options += "<div class='li'><label><input type='checkbox' value='"+data.contacts_list[i].user_id+"-mgr'/> "+ data.contacts_list[i].user_name+" - "+ data.contacts_list[i].Department_name+" ( Manager ) </label></div>";  
                    }
                    if(data.contacts_list[i].sales_module != "0"){
                      options += "<div class='li'><label><input type='checkbox' value='"+data.contacts_list[i].user_id+"-sales'/> "+ data.contacts_list[i].user_name+" - "+ data.contacts_list[i].Department_name+" ( Executive )</label></div>";  
                    }    
                }
                select.append(options);
                $("#activity_owner div.li input[type=checkbox]").each(function(){
                    $(this).change(function(){
                         $(this).closest(".multiselect").find(".selectAll").prop("checked", false)
                    })
                });
                getuserlist();
                $("#email_check").change(function(){
		    if(this.checked) {
                        $(".email_section").show();
                        $("#email_members").show();
		    } else {
		    	$(".email_section").hide();
                        $("#email_members").hide();
                        $("#email_list").html("");
                        email_members = [];
		    }
                });
                $("#save_request_btn").removeClass("none"); 
                $('#c_tat').datetimepicker({
                    ignoreReadonly:true,
                    allowInputToggle:true,
                    format:'lll',
                    minDate: moment()
                });
                }else{
                alert('allocation matrix is empty');
                 return;
                }
            }
        },
        error:function(data){
            network_err_alert();
        }
    });
}
/* function chkAll(wrapper){
    if($("#"+wrapper).find(".selectAll").is(":checked")){
        $("#"+wrapper).find("li input[type=checkbox]").prop("checked", true);
    }else{
        $("#"+wrapper).find("li input[type=checkbox]").prop("checked", false);
    }
} */

var que_date;
function setup_questionnaire(data) {
que_date = data;
        $("#Questionnaire").modal('show');
        $("#Questionnaire").css({
            "overflow-x": "hidden",
            "overflow-y": "auto"
        });

        $('#question-list').empty();
        var row = "";
        for(var i=0; i < data[0].question_data.length; i++){								
                if( data[0].question_data[i].mandatory_bit == "1" ){									
                        row +="<div class='questions star col-lg-12'><i class='fa fa-star-half-o' aria-hidden='true'></i><h4 id='"+data[0].question_data[i].question_id+"'>"+ (i+1)+" ) " + data[0].question_data[i].question_text +"</h4>";
                }else{
                        row +="<div class='questions col-lg-12'><h4 id='"+data[0].question_data[i].question_id+"'>"+ (i+1)+" ) " + data[0].question_data[i].question_text +"</h4>";
                }
                if(data[0].question_data[i].question_type == 1 || data[0].question_data[i].question_type == 2){
                        row +="<ol type='a'>";
                        if(data[0].question_data[i].answer_data != null){
                                for(var j=0; j < data[0].question_data[i].answer_data.length; j++){
                                        row +="<li id='"+data[0].question_data[i].answer_data[j].answer_id+"'><label>";
                                        row +="<input type='radio' name='"+data[0].question_data[i].question_id+"'>";
                                        row +=data[0].question_data[i].answer_data[j].answer_text;
                                        row +="</label></li>";
                                }
                                row+="<input type='hidden' value='"+data[0].question_data[i].question_type+"' id='questiontype'/>"
                         }
                }
                if(data[0].question_data[i].question_type == 3){
                        row +="<div class='row'><div class='col-lg-6 col-sm-12 col-xs-12'><textarea rows='3' class='form-control text-ans'/></div></div>";
                }
                row +="</ol>";
                row +="</div>";
        }
        $("#lead_qualifier_id").val(data[0].lead_qualifier_id)
        $("#lead_qualifier_name").text(data[0].lead_qualifier_name)
        $('#question-list').append(row);			
}
function cancel_quest(){
$("#Questionnaire").modal("hide")
}
function SubmitQpaper(){
    var mainObj={};
    var someObj=[];
    var someObj1=[];
    var totalQuestions=0;
    var selectedQuestions=0;
    $(".questions").each(function(){
        if($(this).hasClass('star')){
            totalQuestions++;
            if($(this).find("textarea").length > 0){
                $(this).find("textarea").each(function(){
                    if($(this).val()==""){
                        return;
                        $("#mandatory").text("All Questions marked with an asterisk are manadatory");
                    }else{
                        selectedQuestions++;
                        someObj1.push({
                                "ans":$(this).val(), 
                                "quesid":$(this).closest(".col-lg-12").find("h4").attr("id")
                        });
                        $("#mandatory").text("");
                    }
                });
            }else{
                $(this).find("input:radio").each(function(){
                    if($(this).is(":checked")){
                        selectedQuestions++;	
                        someObj.push({
                                "ansid":$(this).closest("li").attr("id"),
                                "attempted_ans_txt":$(this).closest("li").text(),
                                "quesid":$(this).closest("ol").siblings("h4").attr("id"),
                                "questype":$(this).closest("ol").find("input[type=hidden]").attr("value")
                        });
                        return false;
                    }
                });
            }
            }else{
                if($(this).find("textarea").length > 0){
                    $(this).find("textarea").each(function(){							
                        someObj1.push({
                            "ans":$(this).val(), 
                            "quesid":$(this).closest(".col-lg-12").find("h4").attr("id")
                        });
                    });
                }else{
                    $(this).find("input:radio").each(function(){
                        if($(this).is(":checked")){	
                            someObj.push({
                                "ansid":$(this).closest("li").attr("id"),
                                "attempted_ans_txt":$(this).closest("li").text(),
                                "quesid":$(this).closest("ol").siblings("h4").attr("id"),
                                "questype":$(this).closest("ol").find("input[type=hidden]").attr("value"),
                                "ans_txt":$(this).closest("ol").find("input[type=hidden]").attr("value")
                            });
                        }
                    });
                }
            }
    });
    if(totalQuestions != selectedQuestions){
        $("#mandatory").text("All Questions marked with an asterisk are manadatory.");
        return;
    }else{
        $("#mandatory").text("");
        mainObj.lead_qualifier_id=$("#lead_qualifier_id").val();
        mainObj.stage_id=$("#stage").val();
        mainObj.rep_id="<?php echo $this->session->userdata('uid');?>";
        mainObj.opp_cust_id=$("#opp_cust_id").val();
        mainObj.cycle=$("#cycle").val();
        mainObj.industry=$("#industry").val();
        mainObj.location=$("#location").val();
        mainObj.request_id=raise_data.request_id;
        mainObj.request_name=raise_data.request_name;
        mainObj.request_for=raise_data.request_for;
        mainObj.contact_id=raise_data.contacts;
        mainObj.process=raise_data.process;
        mainObj.owner=raise_data.owner_set;
        mainObj.critical=raise_data.critical;
        mainObj.product=raise_data.product;
        mainObj.tat=raise_data.tat;
        mainObj.email=raise_data.email;
        mainObj.remarks=raise_data.remarks;
        mainObj.type1_2=someObj;
        mainObj.type3=someObj1;
        mainObj.que_date=que_date;
        
        for(q=0; q< que_date[0].question_data.length; q++){
            /*------------------------------------for type:1 question -----------------------------------------------*/
            if(que_date[0].question_data[q].question_type == "1" || que_date[0].question_data[q].question_type == "2"){
                for(Sq1=0; Sq1< mainObj.type1_2.length; Sq1++){
                    que_date[0].question_data[q]["attempted_ans"]="";
                    if(mainObj.type1_2[Sq1].quesid  == que_date[0].question_data[q].question_id){
                        que_date[0].question_data[q]["attempted_ans"] = mainObj.type1_2[Sq1].attempted_ans_txt;
                        break;
                    }
                }
            }
        
            /*------------------------------------for type:3 question -----------------------------------------------*/
        
            if(que_date[0].question_data[q].question_type == "3"){
                for(Sq3=0; Sq3< mainObj.type3.length; Sq3++){
                    if(mainObj.type3[Sq3].quesid  == que_date[0].question_data[q].question_id){
                        que_date[0].question_data[q].answer = mainObj.type3[Sq3].ans;
                    }
                }
            }
        }
        console.log(mainObj);
        loaderShow();
        $.ajax({
            type:"post",
            cache:false,
            url:"<?php echo site_url('manager_supportrequestcontroller/verify_qualifier');?>",
            dataType : 'json',
            data:JSON.stringify(mainObj),
            success: function (data) {
                if(error_handler(data)) {
                  return;
                }
                if (data == 0){
                    alert("Successfully answering the qualifier is mandatory to create this opportunity.");
                    loaderHide();
                }else{
                    loaderHide();
                    $('#alert').modal('show');
                    $('#alert .row span').text("").text('Request has been raised with the Request Id -'+data);
                    $('#Questionnaire').modal('hide');
                    $('#leadinfoAdd').modal('hide');
                    loaddata();
                }
            }
        });
    }
}
</script>
            <div id="leadinfoAdd" class="modal fade" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <span class="close" onclick="cancelRequest()">x</span>
                            <h4 class="modal-title"><b>Raise Request</b>
                        </div>
                        <div class="modal-body">
							<input type="hidden" id="popupType"/>
							<div class="row opportunity none"> 
								<div class="col-md-4">
                                    <label>Opportunity*</label> 
								</div>
								<div class="col-md-6">
									<select class="form-control" onchange="fetchContact(this)">
									</select>
									<span class="error-alert"></span>
								</div>
							</div>
							<div class="row customer none">
								<div class="col-md-4">
									<label>Customer*</label> 
								</div>
								<div class="col-md-6">
									<select class="form-control" onchange="fetchContact(this)">
									</select>
									<span class="error-alert"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label>Contact(s)*</label> 
								</div>
								<div class="col-md-6">
									<div class="form-control multiselect" id="MultipleContact">                                        
									</div>
									<span class="error-alert"></span>
								</div>
								<div class="col-md-2" id="addContactLink">
									
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label>Process Type*</label> 
								</div>
								<div class="col-md-6">
									<select class="form-control" id="process_type" >
									</select>
									<span class="error-alert"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label>Product*</label> 
								</div>
								<div class="col-md-6">
									<select class="form-control" id="product_id">
									</select>
									<span class="error-alert"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label>Ticket Name*</label> 
								</div>
								<div class="col-md-6">
									<input type="text" class="form-control" id="ticketName"/>
									<span class="error-alert"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label>Remarks</label>  
								</div>
								<div class="col-md-6">
									<textarea type="text" class="form-control" id="remarks"></textarea>
									<span class="error-alert"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 no-padding">
									<button id="assignmentBtn" onclick="assign()" class="btn">Assign</button>
								</div>
								
								<div class="col-md-6 assignment none">
									Executive
									<div class="form-control multiselect" id="executive">                                        
									</div>
								</div>
								<div class="col-md-6 assignment none">
									Stage Executive
									<div class="form-control multiselect" id="stageExecutive">                                        
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label> <input type="checkbox"  id="email_Check" onchange="isEmailCheck()"/> 
									Send email to customer</label>
								</div>
								<div class="col-md-8">
									<textarea class="form-control" id="emailBody" placeholder="Optional"></textarea>
									<span class="error-alert"></span>
								</div>
							</div>
							<!-----------------------
							<div class="row ">
							<hr>
							</div>
                            <div id="first_section">
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="request_name">Request Name*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="request_name" name="request_name" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
								<input type="hidden" id="stage" name='stage'/>
                                 <input type="hidden" id="cycle" name='cycle'/>
                                 <input type="hidden" id="industry" name='industry'/>
                                 <input type="hidden" id="location" name='location'/>
                                 <input type="hidden" id="opp_cust_id" name='opp_cust_id'/>
                                 
                                 
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="request_for">Request For*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" id="request_for" onchange="get_details()">
                                            <option value="">Select</option>
                                            <option value="opportunity">Opportunity</option>
                                            <option value="customer">Customer</option>
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row cust_row none">
                                    <div class="col-md-4">
                                        <label for="customer"><b>Customer*</b></label>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="leadField">
                                            <input type="text" id="customer" class="form-control" placeholder="Enter Customer:">
                                            <span class="error-alert"></span>	
                                        </div>
                                    </div>
                                </div>
                                <div class="row opp_row none">
                                    <div id="oppGroup">
                                        <div class="col-md-4">
                                                <label for="opportunity_ids"><b>Opportunity*</b></label>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="oppField">
                                                <select class="form-control" id="opportunity_ids" onchange="get_contacts(this.value)">
                                                </select>
                                                <span class="error-alert"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="product_id">Product*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" id="product_id">                                        
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="client_contact"> Client Contact*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-control multiselect" id="client_contact">                                        
                                        </div>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="process_type"> Support Process*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" id="process_type" name="process_type">
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                            </div>
                            <div id="second_section" class="none">
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="activity_owner">Activity Owner*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-control multiselect" id="activity_owner">                                        
                                        </div>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="support_criticality">Support Criticality*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" id="support_criticality" name="support_criticality" autofocus>
                                            <option value=''>Select</option>
                                            <option value='high'>High</option>
                                            <option value='medium'>Medium</option>
                                            <option value='low'>Low</option>
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="c_tat">Committed TAT*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class='input-group date' id="c_tat">
                                                <input type='text' class="form-control" placeholder="DD-MM-YYYY" readonly />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                            <span class="error-alert"></span>
                                        </div>	
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="remarks" title="Suggested" >Request Remarks*</label>  
                                    </div>
                                    <div class="col-md-6">
                                        <textarea rows="4" cols="50" placeholder="Enter additional remarks for the Support Request" class="form-control" name="remarks" id="remarks"></textarea>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row" id="email_grant">
                                    <div class="col-md-2 pull-left">
                                            <input type="checkbox" name="check" id="email_check" />
                                            <label for="email_check"> Email Alert </label>
                                    </div>
                                    <div class="col-md-8 email_section" style="display: none;">
                                            <input id='email_members' class="form-control" placeholder="Send Emails to:" />
                                    </div>
                                </div>
                                <div class="row">
                                    <ul style="padding:0px"class="email_id" id="email_list"></ul>
                                </div>
                            </div>-->
                        </div>
                        <!--<div class="modal-footer">
                           <input type="button" class="btn none"  value="Raise Request" id="save_request_btn"onclick="save_request();" >
                           <input type="button" class="btn"  value="Check Availability" id="chk_avail_btn"  onclick="chk_paramaters();">
                           <button  type="button" class="btn btn-default" id="btn1_cancel" onclick="cancel1()" >Cancel</button>
                        </div>-->
						<div class="modal-footer">
							<button class="btn btn-default" onclick="save_request()" >Create</button>
							<button class="btn btn-default" onclick="cancelRequest()" >Cancel</button>
                        </div>
                    </div>                               
                </div>
            </div>
              
                <div id="Questionnaire" class="modal fade" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <span class="close" onclick="cancel_quest()">x</span>	
                                <h4 class="modal-title">Qualifier</h4>
                            </div>				
                            <div class="modal-body">									
                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>
                                            <h2>Questions for <span id="lead_qualifier_name"></span></h2>
                                            <p>Mandatory fields are marked with an asterisk ( <i class='fa fa-star-half-o' aria-hidden='true'></i> ).</p>
                                        </center>
                                    </div>
                                </div>
                                <div class="row">
                                        <input type="hidden" id="lead_qualifier_id">
                                        <input type="hidden" id="stage_id">
                                        <input type="hidden" id="user_id">
                                        <input type="hidden" id="lead_id">
                                        <input type="hidden" id="opp_id">
                                         <form>
                                                <div class="col-lg-12" id="question-list">					
                                                </div>
                                        </form>
                                        <div class="go-top">
                                        <i class="fa fa-arrow-circle-o-up fa-3x" aria-hidden="true"></i>
                                        </div>
                                </div>
                                <br>
                                <span id="mandatory" class="error-alert" style="color:red"></span>
                            </div>
                            <div class="modal-footer">
                                <center>
                                    <button type="button" class="btn btn-primary" id="submit_q_btn" onclick="SubmitQpaper()" >Submit</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="alert" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">                                               
                            <div class="modal-body">
                             <div class="row">
                               <span> </span>
                             </div>
                            </div>                            
                        </div>
                    </div>
                </div>
		<div id="addContact" class="modal fade" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header modal-title">
						 <span class="close" onclick="closeContactFrom()">×</span>
						<center><h4 class="modal-title">Add Contact</h4></center>
					</div>
					<div class="modal-body">
						<input type="hidden" id="custOppId">
						<div class="row">
							<div class="col-md-3">
								<center>
									<form method="POST" enctype="multipart/form-data" id="imageUploadA11">
										<img width="100" height="100" id="contactAddPlaceholder" alt="Avatar" src="<?php echo site_url();?>/images/default-pic.jpg" class="img-circle">
										<label for="imageInputAdd" class="custom-file-upload"> <i class="fa fa-cloud-upload"></i> Upload</label>
										<input type="file" class="form-control" accept="image/*" name="userfile" id="imageInputAdd" onchange="contactImageLoaded('add');">
									</form>
								</center> 
								<p> Max file size 512kb </p>
								<span class="error-alert"></span>
							</div>
							<div class="col-md-9">									
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
										<select class="form-control" id="add_buyerper"></select>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="phone">Phone *</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" class="form-control" id="add_phone" />
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
										<input type="text" class="form-control" id="add_email" />
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="email">Alternate E-mail</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<input type="text" class="form-control" id="add_email2"/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<label for="email">Date of Birth</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-8">
										<div class="input-group date" id="add_dob_picker">
											<input id="add_dob" placeholder="Select Date of Birth" type="text" class="form-control" readonly="readonly">
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
										<textarea class="form-control closeinput" rows="4" cols="30" id="add_address"> </textarea>
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
					<div class="modal-footer">
							<button class="btn" onclick="add()" value="Save">Save</button>
							<button class="btn" onclick="closeContactFrom()" value="Cancle">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<div id="assign_request" class="modal fade" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					<input type="hidden" id="TypeHidden"/>
					<input type="hidden" id="processTypeHidden"/>
					<input type="hidden" id="requestIdHidden"/>
					<input type="hidden" id="idHidden"/>
					<div class="modal-header">
						<span class="close"  onclick="cancel1()">&times;</span>
						<h4 class="modal-title">Support Ticket Assignment</h4>
					</div>
					<div class="modal-body">
						<div class="row targetrow ">
							<div class="col-md-2">
								<label for="usr_list">User list*</label> 
							</div>
							<div class="col-md-10">											
								<div class="form-control multiselect" id="usr_list">                                        
								</div>
							   <span class="error-alert"></span>
							</div>	
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn" onclick="assignRequestSave()" value="Assign">
						<input type="button" class="btn" onclick="cancel1()" value="Cancel">
					</div>
				</div>
			</div>
		</div>