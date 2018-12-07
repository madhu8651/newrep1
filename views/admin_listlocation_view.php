
<!DOCTYPE html>
<html lang="en">
	<head>
	
	<?php require 'scriptfiles.php' ?>
	<style>
	input[type="file"] {
  display: block!important;
 }
	
	</style>
	
	<script>
	function cancel(){
		$('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first').val());
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
	}
	function validate_name(name) {
		var nameReg = new RegExp(/^[a-zA-Z &]*$/);
		var valid = nameReg.test(name);
		if (!valid) {
			return false;
		} else {
			return true;
		}
	}
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_locationController/get_location'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				cancel();
				$('#tablebody').empty();
				var row = "";
				for(i=0; i < data.length; i++ ){						
					var rowdata = JSON.stringify(data[i]);
					row += "<tr><td>" + (i+1) + "</td><td>" + data[i].regionname +"</td><td>" + data[i].locationname + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";			
				}					
						var count = data.length;
                        var strcount = "loc"+(count+1); 
                        $('#addlocCount').val(strcount);
						$('#tablebody').append(row);						
			}
		});	
		function compose(){
			$("#addmodal").modal("show")
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin_locationController/get_region'); ?>",
				dataType:'json',
				success: function(data) {
					var select = $("#add_region"), options = "<option value=''>select</option>";
					select.empty();
					for(var i=0;i<data.length; i++){
						options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";           
					}
					select.append(options);
				}
			});
		}
	
	
		function add(){
			if($.trim($("#add_region").val())==""){
				$("#add_region").closest("div").find("span").text("Region is required.");
				$("#add_region").focus();
				return;
			}else{
				$("#add_region").closest("div").find("span").text("");
			}

			if($.trim($("#add_loc").val())==""){
				$("#add_loc").closest("div").find("span").text("Location Name is required.");
				$("#add_loc").focus();
				return;
			}else if(!validate_name($.trim($("#add_loc").val()))){
				$("#add_loc").closest("div").find("span").text("No special chracters allowed.");
				$("#add_loc").focus();
				return;
			}else{
				$("#add_loc").closest("div").find("span").text("");
			}

			var addObj={};
				addObj.regionid = $("#add_region").val();
				addObj.locationname = $.trim($("#add_loc").val());				
                                addObj.locationcount = $('#addlocCount').val();			
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_locationController/add_location'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						if(data==0){
							alert("Location already exist");
						}else{
							cancel();
							$('#tablebody').empty();
							var row = "";
							for(i=0; i < data.length; i++ ){						
								var rowdata = JSON.stringify(data[i]);
								row += "<tr><td>" + (i+1) + "</td><td>" + data[i].regionname +"</td><td>" + data[i].locationname + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";			
							}
							var count = data.length;
							var strcount = "loc"+(count+1); 
							$('#addlocCount').val(strcount);
							$('#tablebody').append(row);
												
						}
					}
				});	
		}
		function selrow(obj){			
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin_locationController/get_region'); ?>",				
				dataType:'json',
				success: function(data) {
					var select = $("#edit_region"), options = "<option value=''>select</option>";
					select.empty();
					for(var i=0;i<data.length; i++){
						options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
					}
					select.append(options);
					$('#edit_region option[value="'+obj.regionid+'"]').attr("selected",true);
				}
			});
			$("#edit_region2").val(obj.locationid);
			$("#edit_loc").val(obj.locationname);
			$(".error-alert").text("");
		}
		
		function edit_save(){					
				if($.trim($("#edit_region").val())==""){
					$("#edit_region").closest("div").find("span").text("Region is required.");
					$("#edit_region").focus();
					return;
				}else{
					$("#edit_region").closest("div").find("span").text("");
				}
				
				if($("#edit_loc").val()==""){
					$("#edit_loc").closest("div").find("span").text("Location is required.");
					$("#edit_loc").focus();
					return;
				}else if(!validate_name($.trim($("#edit_loc").val()))){
					$("#edit_loc").closest("div").find("span").text("No special chracters allowed.");
					$("#edit_loc").focus();
					return;
				}
				else{
					$("#edit_loc").closest("div").find("span").text("");
				}
				
				var addObj={};				
				addObj.regionid = $("#edit_region").val();
				addObj.locationid = $("#edit_region2").val();
				addObj.locationname = $("#edit_loc").val();			
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_locationController/edit_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						if(data==0){
							alert("Location already exist");
						}else{					
							cancel();
							$('#tablebody').empty();
							var row = "";
							for(i=0; i < data.length; i++ ){						
								var rowdata = JSON.stringify(data[i]);
								row += "<tr><td>" + (i+1) + "</td><td>" + data[i].regionname +"</td><td>" + data[i].locationname + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";			
							}
							$('#tablebody').append(row);
						}
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
			$.ajax({
				type : "POST",
				url : "",
				dataType : 'json',
				data : addObj,
				cache : false,
				success : function(data){
					cancel();
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
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Location"/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Location</h2>	
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<!--<a href="#modal_upload" data-toggle="modal" class="addExcel" >
								<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a>-->
							<a class="addPlus" onclick="compose()">
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<table class="table" id="tableTeam">
					<thead>  
						<tr>			
							<th class="table_header">SL No</th>
							<th class="table_header">Region</th>
							<th class="table_header">Location</th>
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
								 <h4 class="modal-title">Edit Location</h4>
							</div>
							<div class="modal-body">	
									<div class="row">
										<div class="col-md-3">
											<label for="edit_region">Add Region*</label> 
										</div>
										<div class="col-md-9">
											<select name="adminContactDept" class="form-control" id="edit_region">
												
											</select>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<label for="edit_loc">Location Name*</label> 
										</div>
										<div class="col-md-9">
											
											<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_loc" autofocus/>
											<span class="error-alert"></span>
											<input type="hidden" class="form-control closeinput"  id="edit_region2"/>
										</div>
									</div>
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="edit_save()" value="Save">
									<input type="button" class="btn" id="cancle" onclick="cancel()" value="Cancel" >
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
								 <h4 class="modal-title">Add Location</h4>
							</div>
							<div class="modal-body">	
									<div class="row">
										<div class="col-md-3">
											<label for="add_region">Add Region*</label> 
										</div>
										<div class="col-md-9">
											<select name="adminContactDept" class="form-control" id="add_region">
												
											</select>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<label for="add_region1">Location Name*</label> 
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control closeinput" name="adminContactDept" id="add_loc" autofocus/>
											<span class="error-alert"></span>
											<input type="hidden" name="addlocCount" id="addlocCount"/>
										</div>
									</div>
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="add()" value="Save">
									<input type="button" class="btn" onclick="cancel()" value="Cancel" >
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
								 <h3>User Role Details</h3>
							</div>
							<div class="modal-body">								
								<center>
									<div class="row">
										<div class="col-md-3">
											<label for="add_region">Add User Role Details*</label> 
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
											<a class="btn btn-primary" href="Fnct_template/userrole.xls">
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
			</div>
		</div>-->
		<?php require 'footer.php' ?>

	</body>
</html>
