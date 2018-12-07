
<!DOCTYPE html>
<html lang="en">
	<head>	
	<?php require 'scriptfiles.php' ?>
	<script>
	var mainData;
	function cancel(){
		$('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first'));
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
	}
	function validate_name(name) {
		var nameReg = new RegExp(/^[a-zA-Z0-9 &_]*$/);
		var valid = nameReg.test(name);
		if (!valid) {
			return false;
		} else {
			return true;
		}
	}
	$(document).ready(function(){
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_productController/view_product'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				mainData=data;
				var row = "";
				for(i=0; i < data.length; i++ ){						
					var rowdata = JSON.stringify(data[i]);
					var res="";
					row += "<tr><td>" + (i+1) + "</td><td>" + data[i].product_name +"</td><td>" + data[i].product_custom_id +"</td><td>" + data[i].currency_name+ "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";			
				}					
				$('#tablebody').append(row);					
			}
		});
   });
	function compose(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin_productController/get_product'); ?>",
			dataType:'json',
			success: function(data) {
			   var select = $("#add_pro1"), options = "<option value='' selected>select</option>";
			   select.empty();      

			   for(var i=0;i<data.length; i++)
			   {
					options += "<option value='"+data[i].currency_id+"'>"+ data[i].currency_name +"</option>";              
			   }
			   select.append(options);

			}
		 });
	}
	function add(){			
		if($("#add_product").val()==""){
			$("#add_product").closest("div").find("span").text("Product Name is required.");
			$("#add_product").focus();
			return;
		}else if(!validate_name($.trim($("#add_product").val()))) {
		   $("#add_product").closest("div").find("span").text("No special chracters allowed.");
		   $("#add_product").focus();
		   return;
		}else{
				$("#add_product").closest("div").find("span").text("");
		}
		if($("#add_pro1").val()==""){
			$("#add_pro1").closest("div").find("span").text("Currency is required.");
			$("#add_pro1").focus();
			return;
		}else{
			$("#add_pro1").closest("div").find("span").text("");
		}
		if($("#add_pro2").val()==""){
			$("#add_pro2").closest("div").find("span").text("Product Id is required.");
			$("#add_pro2").focus();
			return;
		}else if(!validate_name($.trim($("#add_pro2").val()))) {
		   $("#add_pro2").closest("div").find("span").text("No special chracters allowed.");
		   $("#add_pro2").focus();
		   return;
		}else{
				$("#add_pro2").closest("div").find("span").text("");
		}
		var addObj={};				
		addObj.productname = $.trim($("#add_product").val());
		addObj.currencyname = $("#add_pro1").val();
		addObj.product_user_id = $.trim($("#add_pro2").val());
		
		//--------------------
		
		var success=1;
		/* for(i=0; i < mainData.length; i++ ){
			if((addObj.productname == mainData[i].product_name) && (addObj.product_user_id == mainData[i].product_custom_id)){
				success=0;						
			}				
		} */
		if(success==0){
			alert("Product Id already exists.")
			return;
		}
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_productController/post_product'); ?>",
			dataType : 'json',
			data    : JSON.stringify(addObj),
			cache : false,
			success : function(data){
				mainData=data;
				cancel();
				$('#tablebody').empty();
				var row = "";
				for(i=0; i < data.length; i++ ){						
					var rowdata = JSON.stringify(data[i]);
					row += "<tr><td>" + (i+1) + "</td><td>" + data[i].product_name +"</td><td>" + data[i].product_custom_id +"</td><td>" + data[i].currency_name+ "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";			
				}					
				  $('#tablebody').append(row);											
			}
		});	
	}
	function selrow(obj){
		$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin_productController/get_product'); ?>",
				dataType:'json',
				success: function(data) {
				   var select = $("#edit_pro1"), options = "<option value=''>select</option>";
				   select.empty(); 
				   for(var i=0;i<data.length; i++){
						options += "<option value='"+data[i].currency_id+"'>"+ data[i].currency_name +"</option>";              
				   }
				   select.append(options);
				   var currency = obj.currency_id;
				   currency = currency.split(',');
				   for(var j=0;j<currency.length;j++){
					   $('#edit_pro1 option[value="'+currency[j]+'"]').attr("selected",true);
					}				   
				}
		 });	
		$("#add_product1").val(obj.product_id);
		$("#edit_product").val(obj.product_name); 
		$("#edit_pro2").val(obj.product_custom_id); 
		$(".error-alert").text("");
	}
	function edit_save(){
		if($("#edit_product").val()==""){
			$("#edit_product").closest("div").find("span").text("Product Name is required.");
			$("#edit_product").focus();
			return;
		}else if(!validate_name($.trim($("#edit_product").val()))) {
		   $("#edit_product").closest("div").find("span").text("No special chracters allowed.");
		   return;
		}else{
			$("#edit_product").closest("div").find("span").text("");
		}
		if($("#edit_pro1").val()==""){
			$("#edit_pro1").closest("div").find("span").text("Currency is required.");
			$("#edit_pro1").focus();
			return;
		}else{
			$("#edit_pro1").closest("div").find("span").text("");
		}
		if($("#edit_pro2").val()==""){
			$("#edit_pro2").closest("div").find("span").text("Product Id is required.");
			$("#edit_pro2").focus();
			return;
		}else if(!validate_name($.trim($("#edit_pro2").val()))) {
		   $("#edit_pro2").closest("div").find("span").text("No special chracters allowed.");
		   return;
		}else{
			$("#edit_pro2").closest("div").find("span").text("");
		}
		var addObj={};
		addObj.productname = $.trim($("#edit_product").val());
		addObj.productID = $("#add_product1").val();
		addObj.currencyname = $("#edit_pro1").val();
		addObj.product_user_id = $.trim($("#edit_pro2").val());	
		//--------------------
		
		var success=1;
		/* for(i=0; i < mainData.length; i++ ){
			if((addObj.productname == mainData[i].product_name) && (addObj.product_user_id == mainData[i].product_custom_id)){
				success=0;						
			}				
		} */
		if(success==0){
			alert("Product Id already exists.")
			return;
		}
		
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_productController/update_product'); ?>",
			dataType : 'json',
			data : JSON.stringify(addObj),
			cache : false,
			success : function(data){
				mainData=data;
				cancel();
				$('#tablebody').empty();
					var row = "";
					for(i=0; i < data.length; i++ ){						
					var rowdata = JSON.stringify(data[i]);
					row += "<tr><td>" + (i+1) + "</td><td>" + data[i].product_name +"</td><td>" + data[i].product_custom_id +"</td><td>" + data[i].currency_name+ "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";			
					}					
					$('#tablebody').append(row);
			}
		});	
	}
	/* function file(){			
		if($("#fileUp").val()==""){
			$("#fileUp").closest("div").find("span").text("File is required.");
			return;
		}else{
				$("#fileUp").closest("div").find("span").text("");
		}
		var addObj={};
			addObj.fileup = $("#fileUp").val();
			console.log(addObj);
		
			$.ajax({
				type : "POST",
				url : "",
				dataType : 'json',
				data : addObj,
				cache : false,
				success : function(data){
					$('.modal').modal('hide');
					$('.closeinput').val('');
					$('#tablebody').empty();										
				}
			});
	} */
	</script>	
	</head>
	<body class="hold-transition skin-blue sidebar-mini"> 	
	<div class="loader"></div>  
		<!---------------------------------------------------------
		Header bar
		------------------------------------------------------------>
		<?php  require 'demo.php'  ?>
		<!---------------------------------------------------------
		side nav
		------------------------------------------------------------>				
		<?php require 'admin_sidenav.php' ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">			
			<div class="row header1">				
					<div class="col-xs-2 col-sm-8 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Product List"/>
							</div>
						</span>
					</div>
					<div class="col-xs-2 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Product List</h2>	
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<!--<a href="#modal_upload" data-toggle="modal" class="addExcel" >
								<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a>-->
							<a href="#addmodal" class="addPlus" data-toggle="modal" onclick="compose()">
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<table class="table">
					<thead>  
						<tr>			
							<th class="table_header">SL No</th>
							<th class="table_header">Product</th>
							<th class="table_header">Product Id</th>
							<th class="table_header">Product Currency</th>
							<th class="table_header"></th>		   
						</tr>
					</thead>  
					<tbody id="tablebody">
					</tbody>    
				</table>
			</div>
			<div id="editmodal" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="editpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Edit Product</h4>
							</div>
							<div class="modal-body">	
								<div class="row">
									<div class="col-md-4">
										<label for="edit_product">Product Name*</label> 
									</div>
									<div class="col-md-8">
										<input type="hidden" class="form-control closeinput"  id="add_product1"/>
										<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_product" autofocus/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<label for="edit_pro1">Currency *</label> 
									</div>
									<div class="col-md-8">
										<select id="edit_pro1" class="form-control"  multiple>
											
										</select>	
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<label for="edit_pro2">Product Id *</label> 
									</div>
									<div class="col-md-8">
										<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_pro2"/>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" class="btn" onclick="edit_save()" value="Save">
								<input type="button" class="btn" id="cancle" onclick="cancel()" value="Cancel">
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="addmodal" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Add Product</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-4">
										<label for="add_product">Product Name*</label> 
									</div>
									<div class="col-md-8">											
										<input type="text" class="form-control closeinput" name="adminContactDept" id="add_product" autofocus/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<label for="add_pro1">Currency *</label> 
									</div>
									<div class="col-md-8">
										<select id="add_pro1" class="form-control"  multiple>
											
										</select>	
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<label for="add_pro2">Product Id *</label> 
									</div>
									<div class="col-md-8">
										<input type="text" class="form-control closeinput" name="adminContactDept" id="add_pro2"/>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" class="btn" onclick="add()" value="Save">
								<input type="button" class="btn" onclick="cancel()" value="Cancel">
							</div>
						</form>
					</div>
				</div>
			</div>
			<!--<div id="modal_upload" class="modal fade" >
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" data-dismiss="modal">&times;</span>
								 <h3>Product Details</h3>
							</div>
							<div class="modal-body">								
								<center>
									<div class="row">
										<div class="col-md-3">
											<label for="add_role">Add Product Details*</label> 
										</div>
										<div class="col-md-5">
											<input name="fileUp" type='file' id="fileUp" class='form-control'  file-input="files"/>
											<span class="error-alert"></span>
										</div>										
									</div>									
								</center>
							</div>
							<div class="modal-footer" id="modal_footer">								
									<div class="row">
										<div class="col-md-6">
											<a class="btn btn-primary" href="insert_product_data.html">
												Download Template
											</a>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-6">
											<input type="button" class="btn btn-primary" onclick="file()" value="Save">
											<input type="button" class="btn btn-primary" data-dismiss="modal" value="Cancle" >
										</div>
									</div>							
							</div>
						</form>
					</div>
				</div>
			</div>-->
		</div>
		<?php require 'footer.php' ?>

	</body>
</html>
