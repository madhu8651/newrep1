<?php
    $androidappurl = "https://play.google.com/store?hl=en";
    $appleappurl = "https://www.apple.com/in/ios/app-store/";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<title>L Connectt Login</title>
<link rel="icon" href="<?php echo base_url(); ?>images/LConnectt Fevicon.png" type="image/png">
<link rel="shortcut icon" href="<?php echo base_url(); ?>images/LConnectt Fevicon.png" type="image/png">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
<link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet"/>
<script src="<?php echo base_url(); ?>js/jquery-1.12.3.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
<script>
$(document).keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        $('.errMessage').hide();
        var flag=1;
        if($.trim($('#login__username').val())==""){
            flag=0;
            $('#login__username__msg').show();
        }
        if($.trim($('#login__password').val())==""){
            flag=0;
            $('#login__password__msg').show();
        }
        if(($.trim($('#login__username').val())!="") && ($.trim($('#login__password').val())!="")){
            flag=1;
        }
        if(flag==1){
            $('#loginbtn').attr('disabled','disabled');
            document.getElementById('loginform').submit();
        }
    }
});

function showUser() {
	if(document.getElementById("login__username").value==""){
		document.getElementById("valid").innerHTML="Required";
	}
	if(document.getElementById("login__password").value==""){
		document.getElementById("valid1").innerHTML="Required";
	}
	if((document.getElementById("login__username").value) && (document.getElementById("login__password").value)){
		var name=document.getElementById("login__username").value;
		var pswd=document.getElementById("login__password").value;
		if (name == "") {
			document.getElementById("txtHint").innerHTML = "";
			return;
		} else {
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
/*
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var x=xmlhttp.responseText;
					if(x=="admin" || x=="manager"){
						window.location="home.php";
					}else if(x=="rep"){
						window.location="inside_sales_home.php";
					}else if(x=="Invalid User"){
						document.getElementById("txtHint").innerHTML = x;
					}else if(x=="notmanager"){
						document.getElementById("txtHint").innerHTML = "Can't login into the system";
					}
				}
			}
*/
			xmlhttp.open("GET","lconnectWelcome?q="+name+"&r="+pswd,true);
			xmlhttp.send();
		}
	}
}
$(document).ready(function(){
    $("body").css("overflow", "auto");
    $('.errMessage').hide();
	var ss = "<?php if(isset($errorCode)){echo $errorCode;}?>";
	var errMsg = "<?php if(isset($errorMsg)){ echo $errorMsg; } ?>";
	if(ss.length> 0){
		$("#invalid_login").show();
		$("#invalid_login").text(errMsg);
		
	}

	var fullurl = window.location.href;
	var url = fullurl.split('/');
	var clientid = url[3];

	var addobj = {};
	addobj.clientid = clientid;

	$.ajax({
		type : "post",
		url : "<?php echo site_url('indexController/get_client_name'); ?>",
		data : JSON.stringify(addobj),
		dataType : "json",
		success : function(data){
			$('#clientName').html(data[0].client_name);
		}
	})

    $('#showPassword').click(function(){
		if($(this).is(':checked')){
			$('#login__password').prop('type','text');
		}else{
			$('#login__password').prop('type','password');
		}
    });
    $('#loginbtn').click(function(){
        $('.errMessage').hide();
        var flag=1;
        if($.trim($('#login__username').val())==""){
            flag=0;
            $('#login__username__msg').show();
        }
        if($.trim($('#login__password').val())==""){
            flag=0;
            $('#login__password__msg').show();
        }
        if(($.trim($('#login__username').val())!="") && ($.trim($('#login__password').val())!="")){
            flag=1;
        }
        if(flag==1){
            $('#loginbtn').attr('disabled','disabled');
            document.getElementById('loginform').submit();
        }
    });
});
</script>
<style>
   .errMessage{
	   color:#FF0000;
	   font-size:13px;
   }
</style>
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
				<h3>WELCOME <span id="clientName"></span></h3>
			</div>
			<form action="<?php echo site_url('loginController/index'); ?>" method="POST" class="form form--login" id="loginform">
				<center><div id="txtHint" style="color:red"></div></center>
				<div class="form__field">
				  <input id="login__username"  name="login__username" type="text" class="form__input"  placeholder="USER NAME"/>
				  <div>
					   <center><span id="login__username__msg" class="errMessage">USER NAME is required</span></center>
				  </div>
				</div>
				<div class="form__field">
				  <input id="login__password" name="login__password" type="password" class="form__input" placeholder="**********"/>
				  <div>
					   <center><span id="login__password__msg" class="errMessage">PASSWORD is required</span> </center>
				  </div>
				  <div>
					  <center><span id="invalid_login" class="errMessage"></span> </center>
				  </div>
				</div>
				<div class="form__field">
					<span class="forgot"><input type="checkbox" id="showPassword" class="form__checkbox"/> <label for="showPassword" class="indexLabel">Show Password</label></span>
				</div>
				<div class="form__field">
				  <input type="button" value="SIGN IN"  id="loginbtn"/>
				</div>
			</form>
			<div class="forgot-password"><a href="<?php echo site_url('loginController/forgot_password'); ?>">Forgot Password?</a></div>
		</div>
		<div class="footer">
			<div class="col-md-6 col-sm-6 col-xs-6" id="androidspan"><a href="<?php echo $androidappurl; ?>"><img src="<?php echo base_url(); ?>images/new/Android New.png"></a></div>
			<div class="col-md-6 col-sm-6 col-xs-6" id="applespan"><a href="<?php echo $appleappurl; ?>"><img src="<?php echo base_url(); ?>images/new/Apple New.png"></a></div>
		</div>
	</div>
</body>
</html>

