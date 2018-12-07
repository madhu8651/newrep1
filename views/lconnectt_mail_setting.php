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
			.error{
				color:red;
			}
		</style>
		<script type="text/javascript">
		
            var addobj = {};
            var emailid = "";
			/*Validation : No special character */
			function validate_noSpCh(name) {
				var nameReg = new RegExp(/^[\\)(}{/;'"$]*$/);
				var inputArray = name.split("")
				for(i=0;i<inputArray.length; i++){
					var valid = nameReg.test(inputArray[i]);
					if (valid == true) {
						return "invalid";
					}
				}
			}

            $(document).ready(function(){
		        load();
	        });

            function load(){
                  $.ajax({
						type : "post",
						url : "<?php echo site_url('lconnectt_mail_setting_controller/get_details'); ?>",
						dataType : "json",
                        cache : false,
						success : function(data){
							loaderHide()
                                if(data.length >0){
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
                                   $("#login_name").val(data[0].name);
                                   $("#login_email").val(data[0].email_id);
                                   $("#login_password").val(data[0].password);
                                }
						},
						error: function(xhr){
						}
					});
            }

			/* Validation : first character digit */
			function firstLetterChk(name) {
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
							if(!firstLetterChk(valueArray[i])){
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
			
			function validate_email(name) {
				var nameReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
				var valid = nameReg.test(name);
				if (!valid) {
					return false;
				} else {
					return true;
				}
			}


			
			function validatefield(){
				
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
					}else if(!firstLetterChk($.trim($("#login_name").val()))){
						$("#login_name").next(".error").text("First letter should not be any Special character.");
						$("#login_name").focus();
						return;
					}else if(validate_noSpCh($.trim($("#login_name").val())) == "invalid"){
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
					obj.login_email = $.trim($("#login_email").val());
					obj.login_password = $.trim($("#login_password").val());
					obj.emailid = emailid;
					
					loaderShow();
					 $.ajax({
						type : "post",
						url : "<?php echo site_url('lconnectt_mail_setting_controller/validate_email'); ?>",
						data : JSON.stringify(obj),
						dataType : "json",
                        cache : false,
						success : function(data){
						        loaderHide();
                                if(error_handler(data)){
						                return;
					            }
                                 addobj=obj;
                                 if(data=="Sent"){
                                     $("#email_save").removeAttr("disabled");
                                     $("#validatebtn").attr("disabled","disabled");
                                     $("#validatebtn").addClass("validated");
									 
									 $("#incoming_server,#outgoing_server,#incoming_port,#outgoing_port, #incoming_ssl_tls,#incoming_account_type,#login_name,#login_email,#login_password").attr("disabled","disabled")
                                    
                                     $("#server_msg").html('<div class="alert alert-success"><strong>Authenticated Succesfully. </strong> </div>');
									 
                                 }else{
									 $("#validatebtn").removeClass("validated");
                                     $("#email_save").attr("disabled","disabled");
                                     $("#validatebtn").removeAttr("disabled");
									 $("#server_msg").html('<div class="alert alert-warning"><strong>Authentication Revoke.</strong></div>');
                                 }

						},
						error: function(xhr){
						}
					});
			}

            function saveemail_details(){
                console.log(addobj);
                loaderShow();
                $.ajax({
						type : "post",
						url : "<?php echo site_url('lconnectt_mail_setting_controller/save_email'); ?>",
						data : JSON.stringify(addobj),
						dataType : "json",
                        cache : false,
						success : function(data){
							loaderHide();
							if(error_handler(data)){
									return;
							}
							alert("saved");
							window.location.reload(true);
						},
						error: function(xhr){
						}
				});

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
		
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
				<div class="row header1">
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div data-toggle="tooltip" title="Hooray!" >
								<img src="<?php echo base_url()?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url()?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url()?>images/new/i_off.png'" alt="info" width="30" height="30" data-toggle="tooltip" data-placement="right" title="E-Mail Setting"/>
							</div>
						</span>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>E-Mail Setting</h2>
					</div>
					<div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
						<div class="addBtns">
							<div style="clear:both"></div>
						</div>
					</div>
					<div style="clear:both"></div>
				</div>
				
					<div class="mail_setting_content">
					
						<div class="row">
							<div class="col-md-12">
								<form class="form">
									<div class="row">
									<!------------------------------Incoming Mail Setting:----------------------------------------->
										<div class="col-md-6">
											<fieldset>
												<legend>Incoming Mail Setting:</legend>
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
																	<option value="pop3">POP3</option>
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
												<legend>Outgoing Mail Setting:</legend>
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
										<!------------------------------Login Setting----------------------------------------->
										<div class="col-md-12">
										<hr>
											<div class="row">
												<div class="col-md-3">
												</div>
												<div class="col-md-6">
													<fieldset>
														<legend>Login Setting:</legend>
														<div class="row">
															<div class="col-md-3">
																<label for="login_name">Name*</label>
															</div>
															<div class="col-md-9">
																<input class="form-control" id="login_name" type="text" placeholder="Send messages using this name">
																<span class="error"></span>
															</div>
														</div>
														<div class="row">
															<div class="col-md-3">													
																<label for="login_email">Email ID*</label>
															</div>
															<div class="col-md-9">
																<input class="form-control" id="login_email" type="email">
																<span class="error"></span>
															</div>
														</div>
														<div class="row">
															<div class="col-md-3">													
																<label for="login_password">Password*</label>
															</div>
															<div class="col-md-9">
																<input class="form-control" id="login_password" type="password">
																<span class="error"></span>
															</div>
														</div>
													</fieldset>
												</div>
												<div class="col-md-3">
												</div>
											</div>
											<div id="server_msg">
											</div>
											
										</div>
										
										<div class="col-md-12">
											<hr>
											<div class="row text-center">
												<div class="col-md-12">
													<input class="btn btn-default" type="button" id="validatebtn" value="Validate" onclick="validatefield()"> &nbsp;&nbsp;&nbsp;
													<input class="btn btn-default" type="button" value="Save" id="email_save" onclick="saveemail_details()" disabled="disabled">
												</div>
											</div>
										</div>
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
