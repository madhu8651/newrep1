<!DOCTYPE html>
<html>
	<head>
		<?php $this->load->view('scriptfiles'); ?>
		<style type="text/css">
			
			.mail_setting_content{
				width: 100%;
				max-width:1200px;
				background: #fff;
				opacity: 0.9;
				color: #000;
				padding: 10px;
				margin: 0 auto;
				box-shadow:0 5px 10px 2px rgb(171, 167, 167); 
				border-radius: 15px;
				margin-top:20px
			} 
			.mail_setting_content .content-text{
				font-size: 25px;
				font-family: Roboto;
				letter-spacing: 1.5px;
				color: #000;
			}
			
			.mail_setting_content fieldset > .row{
				margin-bottom:10px;
			}
			.mail_setting_content input[type=checkbox]{
				margin:auto;
				width: 30px;
				height: 30px;
				position: absolute;
				left: 140px;
			}
			.mail_setting_content input[type=button]{
				margin:auto;
				width: 100px !important;
				height: 30px;
			}
			.mail_setting_content .btn,
			.mail_setting_content .btn-default{
				background: #b5000a !important;
				background-color: #b5000a !important;
				color:#fff !important;
				cursor:pointer;
				border:none
				}
			.mail_setting_content fieldset {
				border: 1px solid #ccc;
				padding: 10px;
				border-radius: 5px;
			}
			.mail_setting_content legend {
				border: 1px solid #ccc;
				width:60%;
				padding: 5px 10px;
				border-radius: 5px;
			}
			.mail_setting_content .chklbl{
				    margin-top: 5px;
			}
			.validated{
				background:green !important;
			}
			.bg-green, .callout.callout-success, .alert-success, .label-success, .modal-success .modal-body {
					background-color: #a1f179 !important;
				    padding: 5px;
					text-align: center;
					margin-top: 10px;
			}
			.bg-yellow, .callout.callout-warning, .alert-warning, .label-warning, .modal-warning .modal-body {
					background-color: #f39c1275 !important;
				    padding: 5px;
					text-align: center;
					margin-top: 10px;
			}
			.error{color: red}

  .switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 20px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #B5000A;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 15px;
  width: 15px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: green;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(19px);
  -ms-transform: translateX(19px);
  transform: translateX(19px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
/*------------------ Custom alert------------------------ */
			.mask{
			 width: 100%;
				margin: auto;
				height: 100%;
				position: absolute;
				top: 0;
				background: transparent;
				z-index: 999999;
			}
			.alert.row{
				position: absolute;
				z-index: 99999999;
				top: 0;
				width: 44%;
				margin: 20% 28% !important;
			}

		</style>
		<script type="text/javascript">
		    //email_save  addbtn
            var addobj = {};
            var emailid =eid= "";
            var emailid1 = "";
            var emailarr = [];
			/*Validation : No special character */
			function validateSpclChr(name) {
				var nameReg = new RegExp(/^[\\)(}{/;'"$]*$/);
				var inputArray = name.split("")
				for(i=0;i<inputArray.length; i++){
					var valid = nameReg.test(inputArray[i]);
					if (valid == true) {
						return "invalid";
					}
				}
			}
            	/* Validation : first character digit */
			function namefirstLtrChk(name) {
				var nameReg = new RegExp(/^[a-zA-Z0-9 -]*$/);
				var valid = nameReg.test(name);
				if (!valid) {
					return false;
				} else {
					return true;
				}
			}
			/* Validation : first character digit */
			function domainfirstLtrChk(name) {
				var nameReg = new RegExp(/^[a-zA-Z0-9]*$/);
				var valid = nameReg.test(name);
				if (!valid) {
					return false;
				} else {
					return true;
				}
			}
			function domain_name(name){
				if(name.indexOf('.') !== -1){
					var valueArray = name.split(".");
					if(valueArray.length < 3 || valueArray.length > 4){
						return "invalid array length ";
					}else{
						for(i=0;i<valueArray.length; i++){
							if(valueArray[i].length == 0){
								return "empty array";
							}
							if(!domainfirstLtrChk(valueArray[i])){
								return "NO special character";
							}
							if(i == 0){
								if(valueArray[i].length < 3 || valueArray[i].length > 15){
									return "invalid array1 value length ";
								}
							}
							if(i == 1){
								if(valueArray[i].length < 1 ){
									return "invalid array2 value length ";
								}
							}
							if(i == 2){
								if(valueArray[i].length < 2 || valueArray[i].length > 10){
									return "invalid array3 value length ";
								}
							}
							if(i == 3){
								if(valueArray[i].length < 2 || valueArray[i].length > 6){
									return "invalid array4 value length ";
								}
							}
						}
						return "valid";
					}
				}else{
					return "invalid no dot";
				}
			}
			function validate_port(name) {
				var nameReg = new RegExp(/^\d{3}$/);
				var valid = nameReg.test(name);
				if (!valid) {
					return false;
				} else {
					return true;
				}
			}

            $('#pageHeader1').text('User Mail Settings');
            $(document).ready(function(){
                if(versiontype=='lite'){
                   $('#email_save').prop('disabled',true);
                   $('#addbtn').prop('disabled',true);
                }
                var tabtype='';
				var url1= window.location.href;
				var fileNameIndex1 = url1.lastIndexOf("/") + 1;
				var filename11 = url1.substr(fileNameIndex1);
				
				if(filename11.indexOf('admin_usermailController') >= 0){
					$("#timeZoneSetting, #userElamiList").hide();// requirement given to hide this...
					tabtype='personal';
                    $('#pageHeader1 h2').text('User Mail Settings');
                    $('.info-icon').attr('data-original-title', 'User Mail Settings');
                    $('.info-icon').html('<div data-toggle="tooltip">'+
								            '<img src="<?php echo base_url()?>images/new/i_off.png" alt="info" width="30" height="30" data-toggle="tooltip" data-placement="right" title="User Mail Settings"/>'+
							            '</div>');

                    $(".info-icon img").on( "mouseenter", function() {
                        $(this).attr('src', '<?php echo base_url()?>images/new/i_ON.png');
                    }).on( "mouseleave", function() {
                        $(this).attr('src', '<?php echo base_url()?>images/new/i_off.png');
                    });
				}
				if(filename11.indexOf('admin_personal_groupmail_settingController') >= 0){
					tabtype='group';
					$('#pageHeader1 h2').text('Group Mail Settings');
					$("#loginSetting").show();
					$("#timeZoneSetting, #userElamiList").hide();
					$("#footer_sec").parent(".row").parent(".col-md-12").hide();
				}
				load(tabtype);
	        });

            function selrow(data){
            		//data = JSON.parse(data);
            	 	emailid=data.email_settings_id;
	               $("#incoming_server").val(data.incoming_host);
	               $("#incoming_port").val(data.incoming_port);
	               $("#outgoing_server").val(data.outgoing_host);
	               $("#outgoing_port").val(data.outgoing_port);
	               $("#time_zone").val(data.time_zone);
	               $("#incoming_account_type").val(data.port_type);
	               if(data.incoming_ssl==1){
	                    $("#incoming_ssl_tls").prop("checked",true);
	               }else{
	                    $("#incoming_ssl_tls").prop("checked",false);
	               }
            }
            function load(tabtype){
                    if(tabtype=='personal'){
						$('#time_zone').timezones();
                        loaderShow();
                        $.ajax({
        						type : "post",
        						url : "<?php echo site_url('admin_personal_groupmail_settingController/get_details/personal'); ?>",
        						dataType : "json",
                                cache : false,
        						success : function(data){
        							loaderHide()
									if(versiontype != "premium"){
										/*--- for Lite and Professional versiontype allowing to add only single user mail settings----*/
                                        if(data.length >0){
                                            emailid=data[0].email_settings_id;
                                           $("#incoming_server").val(data[0].incoming_host);
                                           $("#incoming_port").val(data[0].incoming_port);
                                           $("#outgoing_server").val(data[0].outgoing_host);
                                           $("#outgoing_port").val(data[0].outgoing_port);
                                           $("#time_zone").val(data[0].time_zone);
                                           $("#incoming_account_type").val(data[0].port_type);
                                           if(data[0].incoming_ssl==1){
                                                $("#incoming_ssl_tls").prop("checked",true);
                                           }else{
                                                $("#incoming_ssl_tls").prop("checked",false);
                                           }

                                        }else{
											emailid=emailid1="";
											$("#incoming_server").val("");
											$("#incoming_port").val("");
											$("#outgoing_server").val("");
											$("#outgoing_port").val("");
											$("#incoming_account_type").val("pop3");
											$("#incoming_ssl_tls").prop("checked",false);
                                        }
                                    }else{
										/*--- for Premium versiontype allowing to add multiple user mail settings----*/
											emailid=emailid1="";
											$("#incoming_server").val("");
											$("#incoming_port").val("");
											$("#outgoing_server").val("");
											$("#outgoing_port").val("");
											$("#incoming_account_type").val("pop3");
											$("#incoming_ssl_tls").prop("checked",false);
										$("#UserTable tbody").html("");
										var html = "";
										if(data.length >0){
											for(i=0; i<data.length;i++){
														var rowdata=JSON.stringify(data[i]);
														html += "<tr>"+
															"<td>"+(i+1)+"</td>"+
															"<td>"+data[i].incoming_host+"</td>"+
															"<td>"+data[i].incoming_port+"</td>"+
															"<td>"+data[i].port_type+"</td>"+
															"<td>"+data[i].outgoing_host+"</td>"+
															"<td>"+data[i].outgoing_port+"</td>"+
															"<td>"+data[i].time_zone+"</td>"+
															"<td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td>"
														"</tr>"
											}
										}
										$("#UserTable tbody").html(html);
									}
        						},
        						error: function(xhr){
        						}
					    });
                    }else{
                                loaderShow();
                                $.ajax({
            						type : "post",
            						url : "<?php echo site_url('admin_personal_groupmail_settingController/get_details/group'); ?>",
            						dataType : "json",
                                    cache : false,
            						success : function(data){
            							loaderHide()
                                            if(data.length >0){
                                              console.log(data)
                                                emailid=data[0].email_settings_id;

                                               $("#incoming_server").val(data[0].incoming_host);
                                               $("#incoming_port").val(data[0].incoming_port);
                                               $("#outgoing_server").val(data[0].outgoing_host);
                                               $("#outgoing_port").val(data[0].outgoing_port);
                                               $("#incoming_account_type").val(data[0].port_type);
                                               if(data[0].incoming_ssl==1){
                                                    $("#incoming_ssl_tls").prop("checked",true);
                                               }else{
                                                    $("#incoming_ssl_tls").prop("checked",false);
                                               }
                                               if(versiontype!='lite'){
                                                  $('#addbtn').removeAttr('disabled');
                                               }

                                               if(data[0].hasOwnProperty('emaildata')){

                                                    load_page(data[0].emaildata)
                                               }else{
                                                    loaderHide();
                                               }
                                            }else{
                                                    loaderHide();
                                                    emailid=emailid1="";
                                                   $("#incoming_server").val("");
                                                   $("#incoming_port").val("");
                                                   $("#outgoing_server").val("");
                                                   $("#outgoing_port").val("");
                                                   $("#incoming_account_type").val("pop3");
                                                   $("#incoming_ssl_tls").prop("checked",false);

                                            }
            						},
            						error: function(xhr){
            						}
            					});
                    }

            }
            function load_page(data){
				$('#User_list').dataTable().fnDestroy();
                loaderHide();
            	var row = "";
            	var str = "";
                var j=1;
                for(var i=0; i < data.length; i++){
                     var rowdata = window.btoa(JSON.stringify(data[i]));
                     emailarr.push(data[i].email_id);
                        if(data[i].settings_value == 'no'){
                           var str="";
                        }
                        else if(data[i].settings_value == 'yes'){
                            var str="checked";
                        }
                        row += "<tr><td>" + (i+1) + "</td><td>" + data[i].name +"</td><td>"+ data[i].email_id +"</td><td><label class='switch'><input  onchange='toggle(\"aa"+data[i].id+"\",\""+data[i].user_email_settings_id+"\",\""+rowdata+"\")' id='aa"+data[i].id+"' "+str+" type='checkbox'><div class='slider round'></div></label> </td><td></td><td><a data-toggle='modal' href='#editmodal' onclick='edit_user(\""+rowdata+"\")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
                }
					
					$('#User_list tbody').html('').html(row);
					$('#User_list').DataTable();
					$('#User_list').removeAttr('style');
            }
            function edit_user(data){
                data = window.atob(data);
                data=JSON.parse(data)
                  	$("#addmodal").modal('show');
                    $("#login_name").val(data.name);
                    $("#login_email").val(data.email_id);
                    eid=data.email_id;
                    $("#login_password").val(data.password);
                    emailid1=data.user_email_settings_id;
                    $('#savebtn1').hide();
                    $('#savebtn2').show();
            }

            function toggle(id,set_id,obj){
                var addObj={};
                if($("#"+id).prop('checked')==true){
                    var tgbit="yes";
                    addObj.set_id = set_id;
                    addObj.tgbit = tgbit;
                    $("body").append('<div class="mask custom-alert"></div><div class="alert row custom-alert" style="background:#3c8dbc"> <div class="col-md-9"> <b>Do you Really wish to Active the Email?</b> </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
                }else{
                    var tgbit="no";
                    addObj.set_id = set_id;
                    addObj.tgbit = tgbit;
                    $("body").append('<div class="mask custom-alert"></div><div class="alert row custom-alert" style="background:#3c8dbc"> <div class="col-md-9"> <b>Do you Really wish to Deactive the Email?</b> </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
                }
              	$(".Ok").click(function(){
              		$(".custom-alert").remove();
                    loaderShow();
                      $.ajax({
              			type : "POST",
              			url : "<?php echo site_url('admin_personal_groupmail_settingController/update_tg_bit'); ?>",
              			dataType : 'json',
              			data : JSON.stringify(addObj),
              			cache : false,
              			success : function(data){
              			    //loaderHide();
          					if(error_handler(data)){
          						return;
          					}
              				cancel();
              				load('group');
              			}
                      });
              	});
              	$(".notOk").click(function(){
                          cancel();
              			load('group');
              	});


            }

            function saveemail_details(tabtype){
                    if($.trim($("#incoming_server").val())==""){
						$("#incoming_server").next(".error").text("Server Name is required.");
						$("#incoming_server").focus();
						return;
					} else if(domain_name($.trim($("#incoming_server").val())) != "valid"){
						$("#incoming_server").next(".error").text("Invalid Server Name");
						$("#incoming_server").focus();
						return;
					}else{
						$("#incoming_server").next(".error").text("");
					}

					if($.trim($("#incoming_port").val())==""){
						$("#incoming_port").next(".error").text("Port Number is required.");
						$("#incoming_port").focus();
						return;
					} else if(!validate_port($.trim($("#incoming_port").val()))){
						$("#incoming_port").next(".error").text("Enter only numbers and numbers must be only 3 digits.");
						$("#incoming_port").focus();
						return;
					}else{
						$("#incoming_port").next(".error").text("");
					}
				/* ------------------------------Outgoing Mail Setting:----------------------------------------- */
					/* outgoing_server
					outgoing_port
					outgoing_ssl_tls */
					if($.trim($("#outgoing_server").val())==""){
						$("#outgoing_server").next(".error").text("Server Name is required.");
						$("#outgoing_server").focus();
						return;
					} else if(domain_name($.trim($("#outgoing_server").val())) != "valid"){
						$("#outgoing_server").next(".error").text("Invalid Server Name");
						$("#outgoing_server").focus();
						return;
					}else{
						$("#outgoing_server").next(".error").text("");
					}

					if($.trim($("#outgoing_port").val())==""){
						$("#outgoing_port").next(".error").text("Port Number is required.");
						$("#outgoing_port").focus();
						return;
					} else if(!validate_port($.trim($("#outgoing_port").val()))){
						$("#outgoing_port").next(".error").text("Enter only numbers and numbers must be only 3 digits.");
						$("#outgoing_port").focus();
						return;
					}else{
						$("#outgoing_port").next(".error").text("");
					}

                    obj={};
                    obj.incoming_server = $.trim($("#incoming_server").val());
					obj.incoming_port = $.trim($("#incoming_port").val());

					if($("#incoming_ssl_tls").prop("checked") == true){
						obj.incoming_ssl_tls = 1;
					}else{
						obj.incoming_ssl_tls = 0
					}
					obj.incoming_account_type = $.trim($("#incoming_account_type").val());

					obj.outgoing_server = $.trim($("#outgoing_server").val());
					obj.outgoing_port = $.trim($("#outgoing_port").val());
					if($("#outgoing_ssl_tls").prop("checked") == true){
						obj.outgoing_ssl_tls = 1;
					}else{
						obj.outgoing_ssl_tls = 0
					}
                    obj.tabtype=tabtype;
                    obj.emailid = emailid;
                    //obj.time_zone = $("#time_zone").val(); // requirement given to hide it
                    obj.time_zone = "";
                    addobj=obj;
                    console.log(addobj);
					loaderShow();
					$.ajax({
						type : "post",
						url : "<?php echo site_url('admin_personal_groupmail_settingController/save_email'); ?>",
						data : JSON.stringify(addobj),
						dataType : "json",
                        cache : false,
						success : function(data){
							loaderHide();
							if(error_handler(data)){
									return;
							}
                            $('#addbtn').removeAttr('disabled');
                            if(tabtype == 'personal'){
                            	if(data == "false"){
	                            	alert("This server settings is already exists.");
	                            	return;
	                            }else{
	                            	alert("Data has been saved succesfully.");
	                            	window.location.reload(true);
	                            }
                            }else{
                            	alert("Data has been saved succesfully.");
								window.location.reload(true);
                            }
							
						},
						error: function(xhr){
							network_err_alert(xhr);
						}
					});
				}

			
			function validatefield(btntype){
				$(".error").text("")
				/* ------------------------------Incoming Mail Setting:----------------------------------------- */
					/* incoming_server
					incoming_port
					incoming_ssl_tls 
					incoming_account_type */
					
					if($.trim($("#incoming_server").val())==""){
						$("#incoming_server").next(".error").text("Server Name is required.");
						$("#incoming_server").focus();
						return;
					} else if(domain_name($.trim($("#incoming_server").val())) != "valid"){
						$("#incoming_server").next(".error").text("Invalid Server Name");
						$("#incoming_server").focus();
						return;
					}else{
						$("#incoming_server").next(".error").text("");
					}

					if($.trim($("#incoming_port").val())==""){
						$("#incoming_port").next(".error").text("Port Number is required.");
						$("#incoming_port").focus();
						return;
					} else if(!validate_port($.trim($("#incoming_port").val()))){
						$("#incoming_port").next(".error").text("Enter only numbers and numbers must be only 3 digits.");
						$("#incoming_port").focus();
						return;
					}else{
						$("#incoming_port").next(".error").text("");
					}  					
				/* ------------------------------Outgoing Mail Setting:----------------------------------------- */
					/* outgoing_server 
					outgoing_port 
					outgoing_ssl_tls */
					if($.trim($("#outgoing_server").val())==""){
						$("#outgoing_server").next(".error").text("Server Name is required.");
						$("#outgoing_server").focus();
						return;
					} else if(domain_name($.trim($("#outgoing_server").val())) != "valid"){
						$("#outgoing_server").next(".error").text("Invalid Server Name");
						$("#outgoing_server").focus();
						return;
					}else{
						$("#outgoing_server").next(".error").text("");
					}
					
					if($.trim($("#outgoing_port").val())==""){
						$("#outgoing_port").next(".error").text("Port Number is required.");
						$("#outgoing_port").focus();
						return;
					} else if(!validate_port($.trim($("#outgoing_port").val()))){
						$("#outgoing_port").next(".error").text("Enter only numbers and numbers must be only 3 digits.");
						$("#outgoing_port").focus();
						return;
					}else{
						$("#outgoing_port").next(".error").text("");
					}
				/* ------------------------------Login Setting----------------------------------------- */
					
					if($.trim($("#login_name").val())==""){
						$("#login_name").next(".error").text("Name is required.");
						$("#login_name").focus();
						return;
					}else if(!namefirstLtrChk($.trim($("#login_name").val()))){
						$("#login_name").next(".error").text("First letter should not be any Special character.");
						$("#login_name").focus();
						return;
					}else if(validateSpclChr($.trim($("#login_name").val())) == "invalid"){
						$("#login_name").next(".error").text("No special characters allowed  ( \ ) ( } { / ; ' '' $ . )" );
						$("#login_name").focus();
						return;
					}else{
						$("#login_name").next(".error").text("");
					}

					if($.trim($("#login_email").val())==""){
						$("#login_email").next(".error").text("Email ID is required.");
						$("#login_email").focus();
						return;
					} else if(!validate_email($.trim($("#login_email").val()))){
						$("#login_email").next(".error").text("Invalid Email ID");
						$("#login_email").focus();
						return;
					}else{
						$("#login_email").next(".error").text("");
					}
					if($.trim($("#login_password").val())==""){
						$("#login_password").next(".error").text("Password is required.");
						$("#login_password").focus();
						return;
					}else{
						$("#login_password").next(".error").text("");
					}
                    var obj = {};
                    if(btntype=='add'){
                          if(emailarr.length){
                    		for(var i=0;i<emailarr.length;i++){
                    			if($("#login_email").val()!=emailarr[i]){
                    				obj.login_email = $.trim($("#login_email").val());
                    			}else{
                    				$("#login_email").closest("div").find("span").text("Already exists");
                                    return;
                    			}
                    		}
                    		}else{
                    			   obj.login_email = $.trim($("#login_email").val());
                    	  }
                    }else{
                            flg=0;
                            if($.trim($("#login_email").val())==eid){
                                        alert($.trim($("#login_email").val()));
                          		        obj.login_email = $.trim($("#login_email").val());
                                        flg=1;
                            }
                            if(flg==0){
                                for(i=0;i<emailarr.length;i++){
                                    if($.trim($("#login_email").val())!=emailarr[i]){
                                        obj.login_email = $.trim($("#login_email").val());
                      				}else{
                      						$("#login_email").closest("div").find("span").text("Already exists");
                                            return;
                      				}
                                }
                            }
                    }


					obj.incoming_server = $.trim($("#incoming_server").val());
					obj.incoming_port = $.trim($("#incoming_port").val());

					if($("#incoming_ssl_tls").prop("checked") == true){
						obj.incoming_ssl_tls = 1;
					}else{
						obj.incoming_ssl_tls = 0
					}
					obj.incoming_account_type = $.trim($("#incoming_account_type").val());

					obj.outgoing_server = $.trim($("#outgoing_server").val());
					obj.outgoing_port = $.trim($("#outgoing_port").val());
					if($("#outgoing_ssl_tls").prop("checked") == true){
						obj.outgoing_ssl_tls = 1;
					}else{
						obj.outgoing_ssl_tls = 0
					}

					obj.login_name = $.trim($("#login_name").val());
					//obj.login_email = $.trim($("#login_email").val());
					obj.login_password = $.trim($("#login_password").val());
					obj.emailid = emailid;
					obj.emailid1 = emailid1;
					obj.btntype = btntype;

					loaderShow();
					 $.ajax({
						type : "post",
						url : "<?php echo site_url('admin_personal_groupmail_settingController/validate_email'); ?>",
						data : JSON.stringify(obj),
						dataType : "json",
                        cache : false,
						success : function(data){
						        loaderHide();
                                if(error_handler(data)){
						                return;
					            }
								cancel();
                                 addobj=obj;
                                 if(data=="Sent"){
                                     /*$("#email_save").removeAttr("disabled");
                                     $("#validatebtn").attr("disabled","disabled");
                                     $("#validatebtn").addClass("validated");
									 $("#incoming_server,#outgoing_server,#incoming_port,#outgoing_port, #incoming_ssl_tls,#incoming_account_type,#login_name,#login_email,#login_password").attr("disabled","disabled");
                                     $("#server_msg").html('<div class="alert alert-success"><strong>Authenticated Succesfully. </strong> </div>');*/
                                     $("#addmodal").modal('hide');
                                     load('group');
                                 }else{
									 $("#validatebtn").removeClass("validated");
                                     $("#email_save").attr("disabled","disabled");
                                     $("#validatebtn").removeAttr("disabled");
									 $("#server_msg").html('<div class="alert alert-warning"><strong>Authentication Revoke.</strong></div>');
                                 }
						},
						error: function(xhr){
							network_err_alert(xhr);
						}
					});
			}


			function Personal_f(){
				$("#footer_sec").parent(".row").parent(".col-md-12").show();
				$("#loginSetting #timeZoneSetting, #userElamiList").slideUp();
				$("#footer_sec").html("").append('<input class="btn btn-default" type="button" value="Save" onclick="saveemail_details(\'personal\')">');
                load('personal');
			}
			function Group_f(){

				$("#loginSetting #timeZoneSetting, #userElamiList").slideDown();
                $("#footer_sec").html("");
                $("#footer_sec").parent(".row").parent(".col-md-12").hide();
                //$("#footer_sec").html("").append('<input class="btn btn-default" type="button" value="Save" onclick="saveemail_details(\'group\')">');
                load('group');
			}
			function add_user(){
                  	$("#addmodal").modal('show');
                    $('#savebtn1').show();
                    $('#savebtn2').hide();
            }

            function validatefield1(e){
              alert($(e).closest('tr').find('input[type=email]').val())
            }

            function cancel(){
               $("#addmodal").modal('hide');
               $("#addmodal input[type=text]").val('');
               $("#addmodal input[type=email]").val('');
               $("#addmodal input[type=password]").val('');
               $('.error').text('');
            }



		</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini userpage">
	
		<div class="loader">
				<center><h1 id="loader_txt"></h1></center>
		</div>
		<!---------------------------------------------------------
		Header bar
		------------------------------------------------------------>
		<?php  $this->load->view('demo');  ?>
		<!---------------------------------------------------------
		side nav
		------------------------------------------------------------>

		<?php $this->load->view('admin_sidenav'); ?>
		<input id='hid_emailsetid' type="hidden" name='hid_emailsetid' />
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
				<div class="row header1">
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div data-toggle="tooltip">
								<img src="<?php echo base_url()?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url()?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url()?>images/new/i_off.png'" alt="info" width="30" height="30" data-toggle="tooltip" data-placement="right" title="Group Mail Settings"/>
							</div>
						</span>
					</div>
					<div id="pageHeader1" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
							<h2></h2>
					</div>
					<div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
						<div class="addBtns">
							<div style="clear:both"></div>
						</div>
					</div>
					<div style="clear:both"></div>
				</div>
				
				<div class="mail_setting_content">
					<!--<ul  class="nav nav-tabs">
						<li class="active" onclick="Personal_f()"><a data-toggle="tab" href="#Personal" >Personal</a></li>
						<li onclick="Group_f()"><a data-toggle="tab" href="#Group">Group</a></li>
					</ul>-->
					<br>
					<div class="row tab-content">
						<div class="col-md-12 tab-pane in active" id="Group">
							<form class="form">
								<div class="row">
								<!------------------------------Incoming Mail Setting:----------------------------------------->
									<div class="col-md-6">
										<fieldset>
											<legend>Incoming Mail Settings:</legend>
											<div class="row">
												<div class="col-md-3">
													<label for="incoming_server">Incoming server*</label>
												</div>
												<div class="col-md-9">
													<input class="form-control" id="incoming_server" type="text">
													<span class="error"></span>
												</div>
											</div>
											<div class="row">
												<div class="col-md-3">													
													<label for="">Incoming port*</label>
												</div>
												<div class="col-md-9">
													<input class="form-control" id="incoming_port" type="text" maxlength="3">
													<span class="error"></span>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<label class="chklbl" for="incoming_ssl_tls">Enable SSL/TLS</label> <input id="incoming_ssl_tls" type="checkbox"/> 
													<span class="error"></span>
												</div>
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-6">
															<label class="chklbl" for="incoming_account_type">Account type*</label>
														</div>
														<div class="col-md-6">
															<select class="form-control" id="incoming_account_type">
																<option value="pop3" selected="selected">POP3</option>
																<option value="imap">IMAP</option>
															</select>
														</div>
													</div>
													<span class="error"></span>
												</div>
											</div>
										</fieldset>
									</div>
									<!------------------------------Outgoing Mail Setting:----------------------------------------->
									<div class="col-md-6">
										<fieldset>
											<legend>Outgoing Mail Settings:</legend>
											<div class="row">
												<div class="col-md-3">
													<label for="outgoing_smtp">Outgoing Server(SMTP)*</label>
												</div>
												<div class="col-md-9">
													<input class="form-control" id="outgoing_server" type="text">
													<span class="error"></span>
												</div>
											</div>
											<div class="row">
												<div class="col-md-3">													
													<label for="outgoing_port">Outgoing port*</label>
												</div>
												<div class="col-md-9">
													<input class="form-control" id="outgoing_port" type="text" maxlength="3">
													<span class="error"></span>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<label class="chklbl" for="outgoing_ssl_tls">Enable SSL/TLS</label> <input  id="outgoing_ssl_tls" type="checkbox" disabled="disabled" checked="checked"/>
													<span class="error"></span>
												</div>
												<div class="col-md-6">
												</div>
											</div>
										</fieldset>
									</div>
									<div class="col-md-12 none" id="timeZoneSetting">
										<hr>
										<div class="row text-center" >
											<div class="col-md-4">
												<label for="time_zone">Time Zone Settings*<br><b>Select your server Time Zone</b></label>
											</div>
											<div class="col-md-8">
												<select class="form-control" id="time_zone">
													<option>Choose</option>
												</select>
											</div>
										</div>
									</div>
									
									<!------------------------------Login Setting----------------------------------------->
									<div class="col-md-12 none" id="loginSetting">
									    <hr>
                                        <div class="row text-center" >
  											<div class="col-md-12">
  											   <input class="btn btn-default" type="button" value="Save" id="email_save" onclick="saveemail_details('group')" >
  											</div>
  										</div>

                                        <table class="table"  id="User_list">
                                            <thead>
                                                  <tr>
                                                    <th>SL. No</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                    <th width="100px"><input onclick="add_user()" type="button" id='addbtn' class="btn" value="Add" disabled="disabled"/></th>
                                                  </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

									</div>
									<div class="col-md-12">
  										<hr>
  										<div class="row text-center" >
  											<div class="col-md-12" id="footer_sec">
  												<input class="btn btn-default" type="button" value="Save" onclick="saveemail_details('personal')">
  											</div>
  										</div>
  									</div>
									<div class="col-md-12 none"  id="userElamiList">   <!-- requirement given to hide the table -->
										<hr>
										<table class="table" id="UserTable">
                                            <thead>
                                                  <tr>
                                                    <th>SL. No</th>
                                                    <th>Incoming server</th>
                                                    <th>Incoming port</th>
                                                    <th>Incoming Account Type</th>
                                                    <th>Outgoing Server(SMTP)</th>
                                                    <th>Outgoing port</th>
                                                    <th>Time Zone</th>
                                                    <th>Edit</th>
                                                  </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
									</div>
								</div>
							</form>

						</div>
					</div>
					<div style="clear:both"></div>
				</div>
					<div id="addmodal" class="modal fade" data-backdrop="static">
        				<div class="modal-dialog">
        					<div class="modal-content">
        						<form id="addpopup" class="form">
        							<div class="modal-header">
        								<span class="close"  onclick="cancel()">&times;</span>
        								<h4 class="modal-title">Login Settings:</h4>
        							</div>
        							<div class="modal-body">

        									<div class="row">
        										<div class="col-md-4">
        											<label for="login_name">Name*</label>
        										</div>
                                                <div class="col-md-8">
                                                    <input class="form-control" id="login_name" type="text" placeholder="Send messages using this name">
        											<span class="error"></span>
        										</div>
        									</div>
                                            	<div class="row">
        										<div class="col-md-4">
        										   <label for="login_email">Email ID*</label>
        										</div>
                                                <div class="col-md-8">
                                                    <input class="form-control" id="login_email" type="email">
        											<span class="error"></span>
        										</div>
        									</div>
                                            	<div class="row">
        										<div class="col-md-4">
        											<label for="login_password">Password*</label>
        										</div>
                                                <div class="col-md-8">
                                                    <input class="form-control" id="login_password" type="password">
        											<span class="error"></span>
        										</div>
        									</div>
                                            <div id="server_msg"></div>

        							</div>
        							<div class="modal-footer">
        									<input type="button" class="btn" id='savebtn1' onclick="validatefield('add')" value="Save">
        									<input type="button" class="btn" id='savebtn2' onclick="validatefield('edit')" value="Save">
        									<input type="button" class="btn" onclick="cancel()" value="Cancel" >
        							</div>
        						</form>
        					</div>
        				</div>
        			</div>
			</div>
		</div>

		<?php $this->load->view('footer'); ?>
	</body>
</html>
