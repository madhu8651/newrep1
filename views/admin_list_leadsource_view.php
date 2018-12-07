<!DOCTYPE html>
<html lang="en">
	<head>	
	<?php require 'scriptfiles.php' ?>	
	<script>
		ffunction cancel(){
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
			url : "",
			dataType : 'json',			
			cache : false,
			success : function(data){
				cancel();
				$('#tablebody').empty();
				var row = "";
				for(i=0; i < data.length; i++ ){						
					var rowdata = JSON.stringify(data[i]);
					row += "<tr><td>" + data[i].lead_id + "</td><td>" + data[i].lead_name +"</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";						
				}					
				$('#tablebody').append(row);					
			}
		});
		function add(){
			if($.trim($("#add_lead").val())==""){
				$("#add_lead").closest("div").find("span").text("leadsource is required.");
				$("#add_lead").focus();
				return;
			}
			else if(!validate_name($.trim($("#add_lead").val()))) {
			   $("#add_lead").closest("div").find("span").text("Enter Only Chracters");
			   return;
			}else{
				$("#add_lead").closest("div").find("span").text("");
			}
			var addObj={};
				addObj.lead_name = $("#add_lead").val();
			
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
							row += "<tr><td>" + data[i].lead_id + "</td><td>" + data[i].lead_name +"</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";						
						}					
						$('#tablebody').append(row);	
					}
				});	
		}
		function selrow(obj){
			$("#hiddenID").val(obj.lead_id);
			$("#edit_lead").val(obj.lead_name);			
			$(".error-alert").text("");			
		}
		function editsave(){
			if($.trim($("#edit_lead").val())==""){
				$("#edit_lead").closest("div").find("span").text("leadsource is required.");
				$("#edit_lead").focus();
				return;
			}
			else if(!validate_name($.trim($("#edit_lead").val()))) {
			   $("#edit_lead").closest("div").find("span").text("Enter Only Chracters");
			   return;
			}
			else{
				$("#edit_lead").closest("div").find("span").text("");
			}
			var addObj={};		
			addObj.lead_id = $("#hiddenID").val();
			addObj.lead_name = $("#edit_lead").val();				
			
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
						row += "<tr><td>" + data[i].lead_id + "</td><td>" + data[i].lead_name +"</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";						
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
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Lead Source List"/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Lead Source List</h2>	
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<!--<a href="#modal_upload" data-toggle="modal" class="addExcel" >
								<img src="/images/new/Xl_Off.png" onmouseover="this.src='/images/new/Xl_ON.png'" onmouseout="this.src='/images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a>-->
							<a href="#addmodal" class="addPlus" data-toggle="modal" id="compose">
								<img src="/images/new/Plus_Off.png" onmouseover="this.src='/images/new/Plus_ON.png'" onmouseout="this.src='/images/new/Plus_Off.png'" width="30px" height="30px"/>
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
							<th class="table_header">Lead Source</th>							
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
								 <h4 class="modal-title">Edit Lead Source</h4>
							</div>
							<div class="modal-body">		
									<div class="row">
										<div class="col-md-4">
											<label for="edit_lead">Add Lead Source*</label> 
										</div>
										<div class="col-md-8">
											<input type="hidden" class="form-control closeinput" name="hiddenID" id="hiddenID"/>
											<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_lead" autofocus/>
											<span class="error-alert"></span>
										</div>
										<br><br>
									
									</div>
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="editsave()" id="save" value="Save">
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
								 <h4 class="modal-title">Add Lead Source</h4>
							</div>
							<div class="modal-body">		
									<div class="row">
										<div class="col-md-4">
											<label for="add_lead">Add Lead Source*</label> 
										</div>
										<div class="col-md-8">
											<input type="text" class="form-control closeinput" name="adminContactDept" id="add_lead" autofocus/>
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
		</div>
		<?php require 'footer.php' ?>

	</body>
</html>
