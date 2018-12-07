<!DOCTYPE html>
<html>
	<head>
		<title>L Connectt</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
		<link rel="icon" href="<?php echo base_url(); ?>images/LConnectt Fevicon.png" type="image/png">
		<link rel="shortcut icon" href="<?php echo base_url(); ?>images/LConnectt Fevicon.png" type="image/png">
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
		<link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet"/>
		<script src="<?php echo base_url(); ?>js/jquery-1.12.3.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
		<style type="text/css">
			.content-body{max-width: 100%;}
			.content{width: 40%;height: 250px;background: #fff;opacity: 0.7;color: #000;padding: 10px;margin: 0 auto;box-shadow:0 0 12px 6px rgba(100, 0, 10, 0.7); border-radius: 15px;}
			.heading{font-size: 25px;font-family: Roboto;letter-spacing: 1.5px;}
			div.text-center img{background: #000;border: 1px solid #000;border-radius: 10px;opacity: 0.8;}
			div.text-center a{text-decoration: none;cursor: pointer;}
			label.module_name{font-size: 18px;font-family: Roboto;line-height: 3.0;letter-spacing: 2px;}
			.content-text{color:#000;}
		</style>
		<script type="text/javascript">
			var state = <?php echo $state; ?>;
			if(state==2){
				window.close();
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
			});

			function loginpage(){
				var win = null;
				if ((win == null) || (win.closed)) {
					var clientid = '<?php echo $clientid; ?>';
					var url = "<?php echo base_url(); ?>"+"indexController";
					win = window.open(url,clientid,'directories=no,titlebar=no,toolbar=no,location=no,status=yes,menubar=no');
					win.focus();
				}else{
					document.writeln("<h3>Session already started!..</h3>");
					win.focus();
				}
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
						<div class="col-md-12 text-center">
							<div class="content-text">
								<h3>WELCOME  <span id="clientName"></span></h3>
							</div>
						</div>
					</div><br/><br/>
					<div class="row">
						<div class="col-md-12 text-center">
							<form class="form form--login">
								<div class="form__field">
					  				<input type="button" value="Continue  to  Login" id="loginbtn" onclick="loginpage();" />
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>