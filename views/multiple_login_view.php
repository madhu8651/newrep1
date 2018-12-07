<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
		<title>Login</title>
		<link rel="icon" href="<?php echo base_url(); ?>images/LConnectt Fevicon.png" type="image/png">
		<link rel="shortcut icon" href="<?php echo base_url(); ?>images/LConnectt Fevicon.png" type="image/png">
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
		<link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet"/>
		<script src="<?php echo base_url(); ?>js/jquery-1.12.3.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
		<style type="text/css">
			.content-body{max-width: 100%;}
			.content{width: 60%;height: 350px;background: #fff;opacity: 0.7;color: #000;padding: 10px;margin: 0 auto;box-shadow:0 0 12px 6px rgba(100, 0, 10, 0.7); border-radius: 15px;}
			.heading{font-size: 25px;font-family: Roboto;letter-spacing: 1.5px;}
			div.text-center img{background: #000;border: 1px solid #000;border-radius: 10px;opacity: 0.8;}
			div.text-center a{text-decoration: none;cursor: pointer;}
			label.module_name{font-size: 18px;font-family: Roboto;line-height: 3.0;letter-spacing: 2px;}
			.modules{cursor: pointer;}
		</style>
		<script>
			$(document).ready(function(){
				$('.modules').click(function(){
					var id = this.id;
					var manager_id = "<?php echo $_SESSION['manager']; ?>";
					var sales_id = "<?php echo $_SESSION['sales']; ?>";
					ds = "module="+id+"&manager_id="+manager_id+"&sales_id="+sales_id;
					$.ajax({
						type : "post",
						url : "<?php echo site_url('indexController/set_module'); ?>",
						cache : false,
						data : ds,
						success : function(data){
							if(id=="manager"){
								window.location.href = "<?php echo base_url() ;?>manager_dashboardsettingController";
							}
							if(id=="sales"){
								window.location.href = "<?php echo base_url() ;?>sales_mytaskController";
							}
						}
					});
					
				});
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
				<div class="content">
					<div class="row">
						<div class="col-md-12 text-center">
							<label class="heading">Please select a Module to proceed...</label>
						</div>
					</div><br/><br/>
					<table class="table">
						<tbody>
							<tr>
								<?php
									if($_SESSION['cxo']!='-'){
								?>
								<td class="text-center">
									<a href="#" data-toggle="tooltip" title="Under Construction!"><img src="<?php echo base_url(); ?>images/icons/cxo.png" /></a>
								</td>
								<?php
									}
									if($_SESSION['manager']!='-'){
								?>
								<td class="text-center">
									<a id="manager" class="modules"><img src="<?php echo base_url(); ?>images/icons/manager.png" /></a>
								</td>
								<?php
									}
									if($_SESSION['sales']!='-'){
								?>
								<td class="text-center">
									<a id="sales" class="modules"><img src="<?php echo base_url(); ?>images/icons/sales.png" /></a>
								</td>
								<?php
									}
								?>
								</tr>
								<tr>
								<?php
									if($_SESSION['cxo']!='-'){
								?>
								<td class="text-center">
									<label class="module_name">CXO</label>
								</td>
								<?php
									}
									if($_SESSION['manager']!='-'){
								?>
								<td class="text-center">
									<label class="module_name">Manager</label>
								</td>
								<?php
									}
									if($_SESSION['sales']!='-'){
								?>
								<td class="text-center">
									<label class="module_name">Executive</label>
								</td>
								<?php 
									}
								?>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>