<?php if($_SESSION['Navigator']==1){
  require 'location.php';?>
 <?php }?>
<script>

	var email_val = '';
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
	
	/* ----------------------------------------------------- */
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
			/*displayList($(this).val());*/
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
	$("#"+container+"  input[type=radio]").each(function(index){
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
function cancel(){
    $('.modal').modal('hide');
    $('.form-control').val("");
    $("#leadsrcname").text("");
    $("#leadsrcname").text(""); 
    $("#custom_fieldsE input[type=text]").val("");
}
function add_cancel(){
	$('.modal').modal('hide');
	$('.form-control').val("");
	$('#select_map').hide();
	$('#map1').show();

 }
var  navigatorChk=0;
<?php if($_SESSION['Navigator']==1){?>
 navigatorChk=1;
 <?php }?>

var DetailsforValidation;/*for duplicate contact check*/
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
		url:"<?php echo site_url('leadinfo_controller/DetailsforValidation')?>",
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
			network_err_alert(data);
		}
	});
	
	/* ----get contact information for duplicate check ----End ------------------ */
	$("#leadinfoAdd .error-alert").empty();
	setDefaultImage('#leadAvrtAdd');
	/* ---------------------------- */
	if( navigatorChk==1){
	   $('#select_map').hide();
	   $("#map2").hide();
	   $("#okmap").click(function(){
			$("#select_map").show();
			$("#map1").hide();
			$("#map2").hide();
			render_map("long","latt","mapname","search_loc");
		});
	}else{
	  $('#okmap').hide();
	  $('#select_map').hide();
	  $('#maploc').hide();
	}
    $.ajax({
		type: "POST",
		url: "<?php echo site_url('leadinfo_controller/customFieldLead');?>",
		dataType:'json',
		success: function(data) {
			if(data==0){
			}else{
				$('#custom_fields').empty();
				$("#custom_head").show();
				for(i=0;i<data.length;i++){
					if(data[i].attribute_type=="Single_Line_Text"){
						$("#custom_fields").append("<div class='col-md-2'><label>"+data[i].attribute_name+"</label></div><div class='col-md-4'><input type='text' class='form-control custom_fld' id='"+data[i].attribute_key+"'/></div>");
					}
				}
		   }
		},
		error:function(data){
			network_err_alert(data);
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
			var select = $("#country"), options = "<option value=''>select</option>";
			select.empty();      
			for(var i=0;i<data.country.length; i++)
			{
				options += "<option value='"+data.country[i].lookup_id+"'>"+ data.country[i].lookup_value +"</option>";
			}
			select.append(options);
			/*-----------------------------------*/
			var industry = $("#industry"), industryoptions = "<option value=''>select</option>";
			industry.empty();      
			for(var i=0;i<data.industry.length; i++)
			{
				industryoptions += "<option value='"+data.industry[i].map_id+"'>"+ data.industry[i].hvalue2 +"</option>";
			}
			industry.append(industryoptions);

			/*-----------------------------------*/
			var business = $("#bus_loc"), businessoptions = "<option value=''>select</option>";
			business.empty();      
			for(var i=0;i<data.bussines.length; i++)
			{
				businessoptions += "<option value='"+data.bussines[i].map_id+"'>"+ data.bussines[i].hvalue2 +"</option>";
			}
			business.append(businessoptions);

			/*-----------------------------------*/
			var contact = $("#contacttype"), contactoptions = "<option value=''>select</option>";
			contact.empty();      
			for(var i=0;i<data.contacttype.length; i++)
			{
				contactoptions += "<option value='"+data.contacttype[i].lookup_id+"'>"+ data.contacttype[i].lookup_value+"</option>";              
			}
			contact.append(contactoptions);
			/*-----------------------------------*/
		},
		error:function(data){
			network_err_alert(data);
		} 
    });         
		$('#country').on('change',function(){
		var id= this.value; 
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('leadinfo_controller/get_state'); ?>",
				data : "id="+id,
				dataType:'json',
				success: function(data){
					if(error_handler(data)){
						return;
					}
					var select = $("#state"), options = "<option value=''>select</option>";
					select.empty();      

					for(var i=0;i<data.length; i++)
					{
						options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
					}
					select.append(options);

				},
				error:function(data){
					network_err_alert(data);
				}
			});
		});
        $.ajax({
            type : "POST",
            url : "<?php echo site_url('leadinfo_controller/lead_source'); ?>",
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
			error:function(data){
				network_err_alert(data);
			}
        });

       $.ajax({
            type: "POST",
            url: "<?php echo site_url('leadinfo_controller/get_product'); ?>",
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
            },
			error:function(data){
				network_err_alert(data);
			}
        });
    
}
function save_leadinfo(){
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
	
	if($.trim($("#ofcadd").val())!=""){
            if(!comment_validation($.trim($("#ofcadd").val()))){
                    $("#ofcadd").closest("div").find("span").text("No special characters allowed (except $ & : ( ) # @ _ . , + % ? -)");
                    $("#ofcadd").focus();
                    return;
            }else{
                    $("#ofcadd").closest("div").find("span").text("");
            }  
    }
	if($.trim($("#splcomments").val())!=""){
            if(!comment_validation($.trim($("#splcomments").val()))){
                    $("#splcomments").closest("div").find("span").text("No special characters allowed (except $ & : ( ) # @ _ . , + % ? -)");
                    $("#splcomments").focus();
                    return;
            }else{
                    $("#splcomments").closest("div").find("span").text("");
            }  
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
   
    if($.trim($("#primmobile").val())!=""){
        var mob1=$("#primmobile").val();
        var mob2=$("#primmobile2").val();
        if(mob1==mob2){
            $("#primmobile").closest("div").find("span").text("Both primary and secondary number should not be same.");
            $("#primmobile").focus();
            return;
        }else{
            $("#primmobile").closest("div").find("span").text("");
        }
    }
    if($.trim($("#primemail").val())!=""){
        var email1=$("#primemail").val();
        var email2=$("#primemail2").val();
        if(email1==email2){
            $("#primemail").closest("div").find("span").text("Both primary and secondary Email Id should not be same.");
            $("#primemail").focus();
            return;
        }else{
            $("#primemail").closest("div").find("span").text("");
        }
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
            $("#primmobile").closest("div").find("span").text("Mobile Nummber is required.");
            $("#primmobile").focus();
            return;
    }else if(!validate_PhNo($("#primmobile").val())){
            $("#primmobile").closest("div").find("span").text("please enter 10 digit mobile number");
            $("#primmobile").focus();
            return;
    }else{
            $("#primmobile").closest("div").find("span").text("");
    } 

    if($.trim($("#primmobile2").val())!=""){
            if(!validate_PhNo($("#primmobile2").val())){
                    $("#primmobile2").closest("div").find("span").text("please enter 10 digit mobile number");
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
        
    if($.trim($("#display_pic").val())!=""){
		 filevalidation('display_pic','#leadAvrtAdd');
    }
    var leadsource="";
    $("#tree_leadsource input[name=Addlead]").each(function(){
        if($(this).prop('checked')==true){
            leadsource=$(this).val();
        }        
    });
  var  lead_custom=[];
     $("#custom_fields .col-md-4 .custom_fld").each(function(){
            var key = $(this).attr("id");
            lead_custom.push({attribute_value:$(this).val(),attribute_key:key});
    });
    var mobiles=[];
    var emails=[];
    var leadmobile=[];
    var leademail=[];
    mobiles.push($.trim($("#primmobile").val()));
    mobiles.push($.trim($("#primmobile2").val()));
    emails.push($.trim($("#primemail").val()));
    emails.push($.trim($("#primemail2").val()));
     leadmobile.push($.trim($("#leadphone").val()));
    leademail.push($.trim($("#leadmail").val()));
    var longitude = $.trim($("#long").val());
    var lattitude = $.trim($("#latt").val());
    var addObj={};
    addObj.leadname = $.trim($("#leadname").val());
    addObj.leadwebsite = $.trim($("#leadweb").val());
    addObj.leademail = leademail;
    addObj.phone = leadmobile;
    addObj.leadsource = leadsource;
    addObj.customlead = lead_custom;
    var prod=[];
    $("#product_value li input[type=checkbox]").each(function(){
        if($(this).prop('checked')==true){
            prod.push($(this).val());
        }        
    });
    addObj.product = prod;
    addObj.country = $.trim($("#country").val());
    addObj.state = $.trim($("#state").val());
    addObj.city = $.trim($("#city").val());
    addObj.zipcode = $.trim($("#zipcode").val());
    addObj.ofcaddress = $.trim($("#ofcadd").val());
    addObj.splcomments = $.trim($("#splcomments").val());
    addObj.contactname = $.trim($("#firstcontact").val());
    addObj.designation = $.trim($("#disgnation").val());
    addObj.contacttype = $.trim($("#contacttype").val());
    addObj.industry = $.trim($("#industry").val());
    addObj.bussiness= $.trim($("#bus_loc").val());
    addObj.email=emails;
    addObj.mobile=mobiles;
    addObj.coordinate=longitude+","+lattitude;
	
	
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
	
	/*------------------ ---- duplicate checking----- Ends ----------*/
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
			url : "<?php echo site_url('leadinfo_controller/add_lead')?>",
			dataType : 'json',
			data    : addObj,
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
				else if(data==0){
					/* alert("This Lead name already exists."); */
					$.alert({
						title: 'L Connectt',
						content: 'This Lead name already exists.',
						closeIcon: true,
						closeIconClass: 'fa fa-close',
					});	
					loaderHide();
				}else{
					var leadid = data['leadid'];
					var fileurl = "<?php echo site_url("leadinfo_controller/file_upload/"); ?>"+leadid;
					uploadImage(fileurl, 'add_photo','add');
				}
			},
			error: function(data){
				network_err_alert(data);
			}
		});
	}
/*  taking last 10 digit of mobile number for duplicate check
	removing all special cherecter from the number  */

	function phnformat(contact){
		if(contact != "" && contact != null && contact != 'null' ){
			temp = contact.replace(/[+-. ]/g, "")
			return temp.substr(temp.length - 10);
		}else{
			return contact;
		}
	}
function uploadImage(url, formid,add) {
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
			if(add=='add'){
				/* $("#alert").modal('show');
				$("#alert .modal-body span").text("Data Saved"); */
				$.alert({
					title: 'L Connectt',
					content: 'Data has been saved successfully.',
					closeIcon: true,
					closeIconClass: 'fa fa-close',
				});
			}
			if(data== 1){
				if(email_val){
					loaderHide();
					add_cancel(); 
				}else{
					loaderHide();
					add_cancel(); 
					loaddata();
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
function cancel(){
	$('.modal').modal('hide');
	$('.form-control').val("");
        $('.error-alert').hide();
}
function add_cancel(){
	$('.modal').modal('hide');
	$('.form-control').val("");
	$('#select_map').hide();
	$('#map1').show();
 }
 
    </script>
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
                                        <input type="text" class="form-control" id="leadname" name="leadname" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="leadweb">Lead Website</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="leadweb" name="leadweb" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="leadmail">Lead Email</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"name="adminContactDept" class="form-control" id="leadmail" name="leadmail" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="leadphone">Lead Phone</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"class="form-control" id="leadphone" name="leadphone" >											
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="product">Product</label> 
                                    </div>
                                    <div class="col-md-4" id="product">
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-6">
										<label for="tree_leadsource"  id="tree_lead" >
											<a href="#" >Lead Source
												<b class="glyphicon glyphicon-menu-right"></b>
											</a>
										</label>
										<div id="tree_leadsource" class="tree-view" style="display:none"></div>
										<span class="error-alert"></span>
										<label class="leadsrcname"></label>
                                    </div>
									<div class="col-md-2">
										<label>Photo</label> 
									</div>
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
									<!--<div class="col-md-2">
                                        <label for="displaypic">Photo</label> 
                                    </div>
									<div class="col-md-4">
										<form method="POST" enctype="multipart/form-data" id="add_photo" name="upload_photo">
											<input type="hidden" name="view_value" value="leadinfo_view"/>
											<label for="display_pic" class="custom-file-upload"> 
												<i class="fa fa-cloud-upload"></i> Upload
											</label>
											<input type="file" class="form-control" accept="image/*"  name = "userfile" id="display_pic" onchange="filevalidation('display_pic')"/>
											<span class="error-alert"></span>
										</form>
									</div>-->
                                </div>
                                <div class="row">
                                    <div class="col-md-2" >
                                        <label for="country">Country</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select  class="form-control" id="country" name="country" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="state">State</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select type="text" class="form-control" id="state" name="state" >
                                        </select>				
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="city">City</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="city" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 ">
                                        <label for="zipcode">Zipcode</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <input type="text"  class="form-control" id="zipcode" name="zipcode" >

                                        <span class="error-alert"></span>
                                    </div>
                                </div>
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
                                        <label for="bus_loc">Business Location</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                      <select class="form-control" id="bus_loc" name="bus_loc" >
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
                            <div class="row">
                                <center>
                                 <button type="button" class="btn" id="okmap" >Google Map</button>
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
                                <input type="text" class="form-control" onfocusout="search_location('long','latt','search_loc','mapname')" id="search_loc" name="search" />
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
                                  <button type="button"  class="btn btn-default" id="proceed" style="margin-top:0px" onclick="get_coordinate('long','latt','maploc')">OK</button>
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
                                        <label for="firstcontact">Contact Person*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="firstcontact" name="firstcontact" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 ">
                                        <label for="disgnation">Designation</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="disgnation" name="disgnation" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="primmobile"> Primary Mobile Number *</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="primmobile" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="primmobile2">Secondary Mobile Number</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="primmobile2" name="primmobile" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="primemail"> Primary Email </label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="primemail" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="primemail2">Secondary Email</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="primemail2" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-2">
                                        <label for="contacttype">Contact Type</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="contacttype">
                                            
                                        </select>
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
                                <button type="button" class="btn btn-default" onclick="save_leadinfo()">Save</button>
                                <button  type="button" class="btn btn-default" onclick="add_cancel()" >Cancel</button>
                            </div>
                        
                    </div>
                </div>
            </div>

 <div id="alert" class="modal fade" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">                                               
                <div class="modal-body">
                 <div class="row">
                   <span>Data has been saved successfully!</span>
                   <br>
                   <br>
                   <input type="button" class="btn" data-dismiss="modal" value="Ok">
                 </div>
                </div>                            
            </div>
        </div>
        </div>