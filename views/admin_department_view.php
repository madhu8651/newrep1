<!DOCTYPE html>
<html lang="en">
	<head>	
	<?php $this->load->view('scriptfiles'); ?>
	<script>
	$(document).ready(function(){
		loadpage();
	})
	
	function cancel(){
		$('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first'));
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
	}
	
	function compose(){
		$("#add_dept").focus();		
	}	
	$('.modal').modal({backdrop:'static',keyboard:false, show:true});
	
	function loadpage(){
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_departmentController/get_department'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				loaderHide();
				$('#tablebody').empty();
				var row = "";
				for(var i=0, j=1; i < data.length; i++, j++){						
					var rowdata = JSON.stringify(data[i]);
					row += "<tr><td>" + (j) + "</td><td>" + data[i].Department_name + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";							
				}
				$('#tablebody').append(row);
				$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [{ "bSortable": false, "aTargets": [2] }]
															});			
			}
		});	
	}
	
	
		function add(){
			if($.trim($("#add_dept").val())==""){
				$("#add_dept").closest("div").find("span").text("Department is required.");
				$("#add_dept").focus();				
				return;
			}else if(!validate_name($.trim($("#add_dept").val()))) {
				$("#add_dept").closest("div").find("span").text("No special characters allowed (except &, _,-,.).");
				return;
			}else if(!firstLetterChk($.trim($("#add_dept").val()))) {
				$("#add_dept").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#add_dept").focus();	
				return;
			}else{
				$("#add_dept").closest("div").find("span").text("");
				var addObj={};
				addObj.deprtmt_name =$.trim($("#add_dept").val());
				loaderShow();
				$('#tablebody').parent("table").dataTable().fnDestroy();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_departmentController/post_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						loaderHide();
						if(data=="false"){
							$("#alert").modal('show');
							$("#add_dept").focus();
						}else{
							cancel();
							$('#tablebody').empty();
							var row = "";
							for(i=0; i < data.length; i++ ){						
								var rowdata = JSON.stringify(data[i]);
								row += "<tr><td>" + (i+1) + "</td><td>" + data[i].Department_name + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";							
							}
							$('#tablebody').append(row);  
							
							$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [{ "bSortable": false, "aTargets": [2] }]
															});		
                        }							
					}
				});	
			}
		}
		function selrow(obj){
			$("#edit_dept1").val(obj.Department_id);
			$("#edit_dept").val(obj.Department_name);
			$(".error-alert").text("");
		}
		function edit_save(){
				if($.trim($("#edit_dept").val())==""){
					$("#edit_dept").closest("div").find("span").text("Department is required.");
					$("#edit_dept").focus();
					return;
				}else if(!validate_name($.trim($("#edit_dept").val()))) {
					$("#edit_dept").closest("div").find("span").text("No special characters allowed (except &, _,-,.).");
					$("#edit_dept").focus();
					return;
				}else if(!firstLetterChk($.trim($("#edit_dept").val()))) {
					$("#edit_dept").closest("div").find("span").text("First letter should not be Numeric or Special character.");
					$("#edit_dept").focus();	
					return;
				}else{
					$("#edit_dept").closest("div").find("span").text("");
				}
				var addObj={};
				addObj.deprt_id = $("#edit_dept1").val();
				addObj.deprtmt_name = $.trim($("#edit_dept").val());
				loaderShow();
				$('#tablebody').parent("table").dataTable().fnDestroy();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_departmentController/update_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						loaderHide();
						if(data=="false"){
							$("#alert").modal('show');
							$("#edit_dept").focus();
						}else{
							cancel();
							$('#tablebody').empty();
							var row = "";
							for(i=0; i < data.length; i++ ){						
								var rowdata = JSON.stringify(data[i]);
								row += "<tr><td>" + (i+1) + "</td><td>" + data[i].Department_name + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";							
							}					
							$('#tablebody').append(row); 							
							$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [{ "bSortable": false, "aTargets": [2] }]
															});	
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
	<div class="loader">
		<center><h1 id="loader_txt"></h1></center>		
	</div>
		<!---------------------------------------------------------
		Header bar
		------------------------------------------------------------>
		<?php $this->load->view('demo');  ?>
		<!---------------------------------------------------------
		side nav
		------------------------------------------------------------>		
		
		<?php $this->load->view('admin_sidenav'); ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
			<div class="row header1">				
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Department List"/>
							</div>
						</span>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Department List</h2>	
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
							<th class="table_header">Department</th>
							<th class="table_header"></th>		   
						</tr>
					</thead>  
					<tbody id="tablebody">
					</tbody>    
				</table>
			</div>
			<div id="editmodal" class="modal fade" data-backdrop="static" >
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="editpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								<span class="close"  onclick="cancel()">&times;</span>
								<h4 class="modal-title">Edit Department</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="edit_dept">Department*</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<input type="hidden" class="form-control closeinput"  id="edit_dept1"/>
										<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_dept"/>
										<span class="error-alert"></span>
										
									</div>
								</div>
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" id="save" onclick="edit_save()" value="Save">
									<input type="button" class="btn" id="cancle" onclick="cancel()" value="Cancel" >
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="addmodal" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form">
							<div class="modal-header">
								<span class="close"  onclick="cancel()">&times;</span>
								<h4 class="modal-title">Add Department</h4>
							</div>
							<div class="modal-body">	
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="add_dept">Department*</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<input type="text" class="form-control closeinput" name="adminContactDept" id="add_dept"/>
										<span class="error-alert"></span>
										
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
								 <h3>Department Details</h3>
							</div>
							<div class="modal-body">								
								<center>
									<div class="row">
										<div class="col-md-3">
											<label for="add_role">Add Department Details*</label> 
										</div>
										<div class="col-md-5">
											<input name="fileUp" type='file' id="fileUp" class='form-control'  file-input="files"/>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-4">
											<a class="btn btn-primary" href="Fnct_template/department.xls">
												Download Template
											</a>
											<span class="error-alert"></span>
										</div>
									</div>									
								</center>
							</div>
							<div class="modal-footer" id="modal_footer">
								<center>
									<input type="button" class="btn" onclick="file()" value="Save">
									<input type="button" class="btn" onclick="cancel()" value="Cancel" >
								</center>
							</div>
						</form>
					</div>
				</div>
			</div>-->
			<div id="alert" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">                                               
						<div class="modal-body">
							<div class="row">
								<center>
									<span>Department is already Exits.</span>
									<br>
									<br>
									<input type="button" class="btn" data-dismiss="modal" value="Ok">
								<center>
							</div>
						</div>                            
                    </div>
                </div>
            </div>
		</div>
		<?php $this->load->view('footer'); ?>

	</body>
</html>
