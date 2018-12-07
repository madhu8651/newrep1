<!DOCTYPE html>
<html lang="en">
	<head>	
	<?php require 'scriptfiles.php' ?>
	<script>
		function cancel(){
		$('.modal').modal('hide');
		$('input[type="text"], select, textarea').val('');
		$('input[type="radio"]').prop('checked', false);
		$('input[type="checkbox"]').prop('checked', false);
		}
		function validate_name(name) {
			var nameReg = new RegExp(/^[a-zA-Z]+$/);
			var valid = nameReg.test(name);
			
			if (!valid) {
				return false;
			} else {
				return true;
			}
		}
		function compose(){
			$("#add_dept").focus();
		}	
		$.ajax({
			type : "POST",
			cache:false,
			url : "dat.json",
			dataType : 'json',			
			cache : false,
			success : function(data){
				console.log(data);
				$('.modal').modal('hide');
				$('.closeinput').val('');
				$('#tablebody').empty();
				var row = "";
				for(i=0; i < data.length; i++ ){						
					var rowdata = JSON.stringify(data[i]);
					row += "<tr><td>" + data[i].exp_id + "</td><td>" + data[i].exp_name +"</td><td>" + data[i].docup +"</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";						
				}					
				$('#tablebody').append(row);					
			}
		});
		function add(){
			if($.trim($("#add_exp").val()=="")){
				$("#add_exp").closest("div").find("span").text("Category is required.");
					$("#add_exp").focus();
				return;
			}else if(!validate_name($.trim($("#add_exp").val()))){
				$("#add_exp").closest("div").find("span").text("Enter Only Chracters");
					$("#add_exp").focus();
				return;
			}else{
				$("#add_exp").closest("div").find("span").text("");
			}
			var addObj={};
				addObj.expense_name = $("#add_exp").val();
				addObj.docup = $("input[name='add_doc']:checked").val();
				console.log(addObj);
				$.ajax({
					type : "POST",
					url : "dat.json",
					dataType : 'json',
					data : addObj,
					cache : false,
					success : function(data){
						$('.modal').modal('hide');
						$('.closeinput').val('');
						$('#tablebody').empty();										
					}
				});	
		}
		function selrow(obj){
			$("#hiddenID").val(obj.exp_id);
			$("#edit_exp").val(obj.exp_name);
			if(obj.docup==0){
				$("#edit_doc0").prop('checked', true);
			}
			if(obj.docup==1){
				$("#edit_doc1").prop('checked', true);
			}
			$(".error-alert").text("");			
		}
		function editsave(){
			if($.trim($("#edit_exp").val()=="")){
				$("#edit_exp").closest("div").find("span").text("Category is required.");
					$("#edit_exp").focus();
				return;
			}else if(!validate_name($.trim($("#edit_exp").val()))){
				$("#edit_exp").closest("div").find("span").text("Enter Only Chracters");
					$("#edit_exp").focus();
				return;
			}else{
				$("#edit_exp").closest("div").find("span").text("");
			}
			var addObj={};		
			addObj.exp_id = $("#hiddenID").val();
			addObj.exp_name = $("#edit_exp").val();				
			addObj.docup = $("input[name='edit_doc']:checked").val();
			console.log(addObj);
			//alert("dwwd");
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
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Expenses list"/>
							</div>
						</span>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Expenses list</h2>	
					</div>
					<div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
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
							<th class="table_header">Category</th>
							<th class="table_header">Required Documents upload</th>	
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
								 <span class="close" data-dismiss="modal">&times;</span>
								 <h4 class="modal-title">Expenses List</h4>
							</div>
							<div class="modal-body">	
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="edit_exp">Add Expenses*</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<input type="hidden" class="form-control closeinput" name="hiddenID" id="hiddenID"/>
										<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_exp"/>
										<span class="error-alert"></span>
									</div>
									<br><br>
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="edit_exp">Required Documents upload?</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<label for="doc1">YES</label>	
										<input type="radio"  name="edit_doc" id="edit_doc1" value="1"/>
										<label for="doc2">NO</label>
										<input type="radio" name="edit_doc" id="edit_doc0"  value="0" />
									</div>
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
			<div id="addmodal" class="modal fade" >
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" data-dismiss="modal">&times;</span>
								 <h4 class="modal-title">Expenses List</h4>
							</div>
							<div class="modal-body">	
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="add_exp">Add Expenses*</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<input type="text" class="form-control closeinput" name="adminContactDept" id="add_exp"/>
										<span class="error-alert"></span>
									</div>
										<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="add_doc">Required Documents upload?</label> 
									</div>											
										<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<label for="doc1">YES</label>
										<input type="radio" name="add_doc"  id="add_doc" value="1"/>
										<label for="doc2">NO</label>	
										<input type="radio" name="add_doc" id="add_doc" value="0"/>									
									</div>
								</div>
							</div>							
							<div class="modal-footer">
								<input type="button" class="btn" onclick="add()" value="Save">
								<input type="button" class="btn" data-dismiss="modal" value="Cancel">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php require 'footer.php' ?>

	</body>
</html>
