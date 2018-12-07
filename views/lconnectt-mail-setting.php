<!DOCTYPE html>
<html>
	<head>
		<title>L-Connectt Mail Setting</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
		<link rel="icon" href="<?php echo base_url(); ?>images/LConnectt Fevicon.png" type="image/png">
		<link rel="shortcut icon" href="<?php echo base_url(); ?>images/LConnectt Fevicon.png" type="image/png">
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
		<link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet"/>
		<script src="<?php echo base_url(); ?>js/jquery-1.12.3.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
		<style type="text/css">
			.content-body{
				max-width: 100%;
			}
			.content{
				width: 60%;
				background: #fff;
				opacity: 0.9;
				color: #000;
				padding: 10px;
				margin: 0 auto;
				box-shadow:0 0 12px 6px rgba(100, 0, 10, 0.7); 
				border-radius: 15px;
			}
			.content-text{
				font-size: 25px;
				font-family: Roboto;
				letter-spacing: 1.5px;
				color: #000;
			}
			
			fieldset > .row{
				margin-bottom:10px;
			}
			input[type=checkbox]{
				margin:auto;
				width: 30px;
				height: 30px;
				position: absolute;
				left: 140px;
			}
			input[type=button]{
				margin:auto;
				width: 100px !important;
				height: 30px;
			}
			.btn,.btn-default{
				background: #b5000a !important;
				background-color: #b5000a !important;
				color:#fff !important;
				cursor:pointer;
				border:none
				}
			fieldset {
				border: 1px solid #ccc;
				padding: 10px;
				border-radius: 5px;
			}
			legend {
				border: 1px solid #ccc;
				width:60%;
				padding: 5px 10px;
				border-radius: 5px;
			}
			.chklbl{
				    margin-top: 5px;
			}
		</style>
		<script type="text/javascript">
			
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
								if(valueArray[i].length < 3 || valueArray[i].length > 6){
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
					 
					var obj = {}
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
					console.log(obj)
					/* $.ajax({
						type : "post",
						url : "<?php echo site_url('indexController/get_client_name'); ?>",
						data : JSON.stringify(obj),
						dataType : "json",
						success : function(data){
						},
						error: function(xhr){
						}
					}); */
			}
			
			
		</script>
	</head>
	<body class="align">
		<div class="container-fluid outerdiv">
			<div class="header">
				<span id="headerlogo">
					<img src="<?php echo base_url(); ?>images/new/Logo Semi.png" alt="Logo"/>
				</span>
			</div>
			<div class="content-body">
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="content-text">
								<h3>WELCOME  <span id="clientName"></span></h3>
							</div>
						</div>
					</div>
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
													<label class="chklbl" for="outgoing_ssl_tls">Enable SSL/TLS</label> <input  id="outgoing_ssl_tls" type="checkbox"/>
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
									</div>
									
									<div class="col-md-12">
										<hr>
										<div class="row text-center">
											<div class="col-md-12">
												<input class="btn btn-default" type="button" value="Validate" onclick="validatefield()"> &nbsp;&nbsp;&nbsp;
												<input class="btn btn-default" type="button" value="Save">
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
	</body>
</html>