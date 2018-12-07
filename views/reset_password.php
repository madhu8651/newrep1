<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
 		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
		<title>Set Password</title>
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
			        var pwd = $.trim($('#login__password').val());
			        var cpwd = $.trim($('#login__confirm__password').val());

			        if($.trim($('#login__password').val())==""){
			            flag=0;
			            $('#login__password__msg').show();
			        }
			        if($.trim($('#login__confirm__password').val())==""){
			            flag=0;
			            $('#login__confirm__password__msg').show();
			        }
			        if(pwd!=cpwd){
			        	flag=0;
			        	$('#password__mismatch').show();
			        }
			        if(($.trim($('#login__password').val())!="") && ($.trim($('#login__confirm__password').val())!="") && (pwd==cpwd)){
			            flag=1;
			        }
			        if(flag==1){
			            $('#loginbtn').attr('disabled','disabled');

			            var uid = '<?php echo  $user_id; ?>';
			            var addobj = {};
			            addobj.user_id = uid;
			            addobj.password = pwd;
			            //var datastring = "user_id="+uid+"&pwd="+pwd;

			            $.ajax({
			            	type : "post",
			            	url : "<?php echo site_url('loginController/user_set_password'); ?>",
			            	cache : false,
			            	dataType : "json",
			            	data : JSON.stringify(addobj),
			            	success : function(data){
			            		$('#loginbtn').removeAttr('disabled');
			            		if(error_handler(data)){
									return;
								}
								if(data.response==true){
									alert("Password updated successfully!");
									window.location.href="<?php echo base_url(); ?>";
								}else{
									alert("Password not updated");
									window.location.href="<?php echo base_url(); ?>";
								}
			            	}
			            });
			        }
			    });

			    $('#backbtn').click(function(){
			    	window.location.href="<?php echo site_url('indexController'); ?>";
			    });
			});

			$(document).keypress(function(event){
			    var keycode = (event.keyCode ? event.keyCode : event.which);
			    if(keycode == '13'){
			        $('.errMessage').hide();
			        var flag=1;
			        var pwd = $.trim($('#login__password').val());
			        var cpwd = $.trim($('#login__confirm__password').val());

			        if($.trim($('#login__password').val())==""){
			            flag=0;
			            $('#login__password__msg').show();
			        }
			        if($.trim($('#login__confirm__password').val())==""){
			            flag=0;
			            $('#login__confirm__password__msg').show();
			        }
			        if(pwd!=cpwd){
			        	flag=0;
			        	$('#password__mismatch').show();
			        }
			        if(($.trim($('#login__password').val())!="") && ($.trim($('#login__confirm__password').val())!="") && (pwd==cpwd)){
			            flag=1;
			        }
			        if(flag==1){
			            $('#loginbtn').attr('disabled','disabled');

			            var uid = '<?php echo  $user_id; ?>';
			            var addobj = {};
			            addobj.user_id = uid;
			            addobj.password = pwd;
			            //var datastring = "user_id="+uid+"&pwd="+pwd;

			            $.ajax({
			            	type : "post",
			            	url : "<?php echo site_url('loginController/user_set_password'); ?>",
			            	cache : false,
			            	dataType : "json",
			            	data : JSON.stringify(addobj),
			            	success : function(data){
			            		$('#loginbtn').removeAttr('disabled');
			            		if(error_handler(data)){
									return;
								}
								if(data[0].response==true){
									alert("Password updated successfully!");
									window.location.href="<?php echo base_url(); ?>";
								}else{
									alert("Password not updated");
									window.location.href="<?php echo base_url(); ?>";
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
				<?php if($status==1) { ?>
				<div class="content-text">
					<h3>SET PASSWORD</h3>
				</div>
				<form action="" method="POST" class="form form--login" id="loginform">
					<center><div id="txtHint" style="color:red"></div></center>
					<div class="form__field">
				  		<input id="login__password" name="login__password" type="password" class="form__input" placeholder="NEW PASSWORD"/>
					  	<div>
							<center><span id="login__password__msg" class="errMessage">NEW PASSWORD is required</span></center>
					 	</div>
					</div>
					<div class="form__field">
				  		<input id="login__confirm__password" name="login__confirm__password" type="password" class="form__input" placeholder="CONFIRM PASSWORD"/>
					  	<div>
							<center><span id="login__confirm__password__msg" class="errMessage">CONFIRM PASSWORD is required</span></center>
					 	</div>
					 	<div>
							<center><span id="password__mismatch" class="errMessage">PASSWORD should be same</span></center>
					 	</div>
					</div>
					<div class="form__field">
					  	<input type="button" value="SUBMIT" id="loginbtn"/>
					</div>
				</form>
				<?php }else{ ?>
					<div class="content-text">
						<h3>SESSION TIMEOUT 404 PAGE NOT FOUND!</h3>
					</div>
					<form action="" method="POST" class="form form--login" id="loginform">
						<div class="form__field">
						  	<input type="button" value="BACK" id="backbtn"/>
						</div>
					</form>
				<?php } ?>
			</div>
		</div>
	</body>
</html>