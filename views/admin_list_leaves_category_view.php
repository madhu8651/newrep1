<!DOCTYPE html>
<html lang="en">
	<head>	
	<?php require 'scriptfiles.php' ?>
	<script>
		function cancel(){
		$('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first'));
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
	}
	function validate_name(name) {
		var nameReg = new RegExp(/^[a-zA-Z &_]*$/);
		var valid = nameReg.test(name);
		if (!valid) {
			return false;
		} else {
			return true;
		}
	}
		$.ajax({
			type : "POST",
			cache:false,
			url : "leave.json",
			dataType : 'json',			
			cache : false,
			success : function(data){
				console.log(data);
				cancel();
				$('#tablebody').empty();
				var row = "";
				for(i=0; i < data.length; i++ ){						
					var rowdata = JSON.stringify(data[i]);
					row += "<tr><td>" + data[i].leave_id + "</td><td>" + data[i].leave_name +"</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";						
				}					
				$('#tablebody').append(row);					
			}
		});
		function add(){
			if($.trim($("#add_leave").val()=="")){
				$("#add_leave").closest("div").find("span").text("Leave Category Name is required");
				$("#add_leave").focus();
				return;
			}else if(!validate_name($.trim($("#add_leave").val()))){
				$("#add_leave").closest("div").find("span").text("Enter Only Chracters");
				$("#add_leave").focus();
				return;
			}else{
				$("#add_leave").closest("div").find("span").text("");
			}
			var addObj={};
				addObj.leave_name = $("#add_leave").val();
			
				console.log(addObj);
				$.ajax({
					type : "POST",
					url : "leave.json",
					dataType : 'json',
					data : addObj,
					cache : false,
					success : function(data){
						cancel();
						$('#tablebody').empty();
						var row = "";
						for(i=0; i < data.length; i++ ){						
							var rowdata = JSON.stringify(data[i]);
							row += "<tr><td>" + data[i].leave_id + "</td><td>" + data[i].leave_name +"</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";						
						}					
						$('#tablebody').append(row);											
					}
				});	
		}
		function selrow(obj){
			$("#hiddenID").val(obj.leave_id);
			$("#edit_leave").val(obj.leave_name);			
			$(".error-alert").text("");			
		}
		function editsave(){
			if($.trim($("#edit_leave").val()=="")){
				$("#edit_leave").closest("div").find("span").text("Leave Category Name is required");
				$("#edit_leave").focus();
				return;
			}else if(!validate_name($.trim($("#edit_leave").val()))){
				$("#edit_leave").closest("div").find("span").text("Enter Only Chracters");
				$("#edit_leave").focus();
				return;
			}else{
				$("#edit_leave").closest("div").find("span").text("");
			}
			var addObj={};		
			addObj.leave_id = $("#hiddenID").val();
			addObj.leave_name = $("#edit_leave").val();				
			
			console.log(addObj);
			$.ajax({
				type : "POST",
				url : "",
				dataType : 'json',
				data : addObj,
				cache : false,
				success : function(data){
					cancel();
					$('#tablebody').empty();
					var row = "";
					for(i=0; i < data.length; i++ ){						
						var rowdata = JSON.stringify(data[i]);
						row += "<tr><td>" + data[i].leave_id + "</td><td>" + data[i].leave_name +"</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";						
					}					
					$('#tablebody').append(row);				
				}
			});
		}
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
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Leaves Category List"/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Leaves Category List</h2>	
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
							<th class="table_header">Leave Category Name</th>							
							<th class="table_header"></th>		   
						</tr>
					</thead>  
					<tbody id="tablebody">
					</tbody>    
				</table>
			</div>
			<div id="editmodal" class="modal fade" data-backdrop="false">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="editpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Leaves Category List</h4>
							</div>
							<div class="modal-body">	
								<div class="row">
									<div class="col-md-4">
										<label for="edit_leave">Add Leave Category*</label> 
									</div>
									<div class="col-md-8">
										<input type="hidden" class="form-control closeinput" name="hiddenID" id="hiddenID"/>
										<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_leave"/>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" class="btn" onclick="editsave()" id="save" value="Save">
								<input type="button" class="btn" onclick="cancel()" value="Cancel" >
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="addmodal" class="modal fade" data-backdrop="false">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Leaves Category List</h4>
							</div>
							<div class="modal-body">	
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="add_leave">Add Leave Category*</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<input type="text" class="form-control closeinput" name="adminContactDept" id="add_leave"/>
										<span class="error-alert"></span>
									</div>										
								</div>
							</div>							
							<div class="modal-footer" id="modal_footer">
								<input type="button" class="btn" onclick="add()" value="Save">
								<input type="button" class="btn" onclick="cancel()" value="Cancel">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php require 'footer.php' ?>

	</body>
</html>
