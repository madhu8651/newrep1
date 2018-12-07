<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
 		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
		<title>Forgot Password</title>
		<link rel="icon" href="<?php echo base_url(); ?>images/LConnectt Fevicon.png" type="image/png">
		<link rel="shortcut icon" href="<?php echo base_url(); ?>images/LConnectt Fevicon.png" type="image/png">
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
		<link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet"/>
		<script src="<?php echo base_url(); ?>js/jquery-1.12.3.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
		<style>
		   .errMessage{
			   color:#FF0000;
			   font-size:13px;
		   }
		</style>
		<script type="text/javascript">
			function  error_handler(data){
				if(data.hasOwnProperty("errorCode")){
					$('body').append('<div class="mask custom-alert" id="execption_custom_alert"><div style="background:url('+base_url+'images/alert.png);background-size: 60px;background-position: center left;background-repeat: no-repeat;" class="alert alert-danger row custom-alert"><div class="col-md-12"><b>Database Error Code : </b>'+data.errorCode+'</div><div class="col-md-12"><b>Database Error Message : </b>'+data.errorMsg+'</div></div></div>');
					var isInside = false;
					$('#execption_custom_alert .custom-alert').hover(function () {
						isInside = true;
					}, function () {
						isInside = false;
					})

					$(document).mouseup(function () {
						if (!isInside){
							/* $('#execption_custom_alert').remove(); */
						}
						
					});
					return true;
				}
				return false;
			}

			$(document).ready(function(){
				$('.errMessage').hide();

				$('#loginbtn').click(function(){
			        $('.errMessage').hide();
			        var flag=1;
			        if($.trim($('#login__username').val())==""){
			            flag=0;
			            $('#login__username__msg').show();
			        }
			        if(($.trim($('#login__username').val())!="")){
			            flag=1;
			        }
			        if(flag==1){
			            $('#loginbtn').attr('disabled','disabled');

			            var emp_id = $.trim($('#login__username').val());

			            $.ajax({
			            	type : "post",
			            	url : "<?php echo site_url('loginController/send_reset_password_email'); ?>",
			            	cache : false,
			            	data : "emp_id="+emp_id,
			            	success : function(data){
			            		$('#loginbtn').removeAttr('disabled');
			            		if(error_handler(data)){
									return;
								}
								if(data==true){
									alert("Reset password link has been sent to your registered Email-ID. Thank you");
								}else{
									alert("User not found");
								}
			            	}
			            });
			        }
			    });
			});

			$(document).keypress(function(event){
			    var keycode = (event.keyCode ? event.keyCode : event.which);
			    if(keycode == '13'){
			        $('.errMessage').hide();
			        var flag=1;
			        if($.trim($('#login__username').val())==""){
			            flag=0;
			            $('#login__username__msg').show();
			        }
			        if(($.trim($('#login__username').val())!="")){
			            flag=1;
			        }
			        if(flag==1){
			            $('#loginbtn').attr('disabled','disabled');
			            var emp_id = $.trim($('#login__username').val());

			            $.ajax({
			            	type : "post",
			            	url : "<?php echo site_url('loginController/send_reset_password_email'); ?>",
			            	cache : false,
			            	data : "emp_id="+emp_id,
			            	success : function(data){
			            		$('#loginbtn').removeAttr('disabled');
			            		if(error_handler(data)){
									return;
								}
								if(data==true){
									alert("Reset password link has been sent to your registered Email-ID. Thank you");
									window.close();
								}else{
									alert("User not found");
									window.close();
								}
			            	}
			            });
			        }
			    }
			});
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
				<div class="content-text">
					<h3>FORGOT PASSWORD</h3>
				</div>
				<form action="" method="POST" class="form form--login" id="loginform">
					<center><div id="txtHint" style="color:red"></div></center>
					<div class="form__field">
				  		<input id="login__username" name="login__username" type="text" class="form__input" placeholder="EMPLOYEE ID"/>
					  	<div>
							<center><span id="login__username__msg" class="errMessage">EMPLOYEE ID is required</span></center>
					 	</div>
					</div>
					<div class="form__field">
					  	<input type="button" value="SUBMIT" id="loginbtn"/>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>