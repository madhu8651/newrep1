
<!DOCTYPE html>
<html lang="en">
	<head>
	
	<?php require 'scriptfiles.php' ?>
	<script>
	var maindata; 
	function cancel(){
		$('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.error-alert').text('');		
	}
	
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('admin_calendarController/get_data'); ?>",
		dataType : 'json',
		cache : false,
		success : function(data){
			loaderHide();
			maindata = data;
			cancel();
			$('#tablebody').empty();
			var row = "";
			for(i=0; i < data.length; i++ ){						
				var rowdata = JSON.stringify(data[i]);
				row += "<tr><td>" + (i+1) + "</td><td>" + data[i].calendername + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";							
			}					
			$('#tablebody').append(row);
			$('#tablebody').parent("table").DataTable({
														"aoColumnDefs": [
															{ 	
																"bSortable": false, 
																"aTargets": [2] }
															]
													  });					
		}
	});		
		
		function add(){
			if($.trim($("#add_calendar").val())==""){
				$("#add_calendar").closest("div").find("span").text("Calendar Name is required.");
				$("#add_calendar").focus();
				return;
			}else if(!validate_noSpCh($.trim($("#add_calendar").val()))) {
				$("#add_calendar").closest("div").find("span").text("No special characters allowed.");
				$("#add_calendar").focus();
				return;
			}else if(!firstLetterChk($.trim($("#add_calendar").val()))) {
				$("#add_calendar").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#add_calendar").focus();	
				return;
			}else{
				$("#add_calendar").closest("div").find("span").text("");
			}
			var addObj={};
			addObj.calendername = $.trim($("#add_calendar").val());
			loaderShow();
			$('#tablebody').parent("table").dataTable().fnDestroy();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_calendarController/post_data'); ?>",
				dataType : 'json',
				data : JSON.stringify(addObj),
				cache : false,
				success : function(data){
					loaderHide();
					if(data=="false"){						
						$('#alert').modal('show');
						$("#add_calendar").focus();						
						$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [
																	{ 	
																		"bSortable": false, 
																		"aTargets": [2] }
																	]
															  });  
						return;
					}else{
						cancel();
						$('#tablebody').empty();
						var row = "";
						for(i=0; i < data.length; i++ ){						
						var rowdata = JSON.stringify(data[i]);
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].calendername + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";							
						}					
						$('#tablebody').append(row);
						$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [
																	{ 	
																		"bSortable": false, 
																		"aTargets": [2] }
																	]
															  });  
					}                       
				}
			});	
		}
		function selrow(obj){
			$("#edit_calendar1").val(obj.calenderid);
			$("#edit_calendar").val(obj.calendername);
			$(".error-alert").text("");
		}
		function edit_save(){
				if($.trim($("#edit_calendar").val())==""){
					$("#edit_calendar").closest("div").find("span").text("Calendar Name is required.");
					$("#edit_calendar").focus();
					return;
				}else if(!validate_noSpCh($.trim($("#edit_calendar").val()))) {
					$("#edit_calendar").closest("div").find("span").text("No special characters allowed.");
					$("#edit_calendar").focus();
					return;
				}else if(!firstLetterChk($.trim($("#edit_calendar").val()))) {
					$("#edit_calendar").closest("div").find("span").text("First letter should not be Numeric or Special character.");
					$("#edit_calendar").focus();	
					return;
				}else{
					$("#edit_calendar").closest("div").find("span").text("");
				}
				var addObj={};
				addObj.calenderid = $.trim($("#edit_calendar1").val());
				addObj.calendername = $.trim($("#edit_calendar").val());
				loaderShow();
				$('#tablebody').parent("table").dataTable().fnDestroy();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_calendarController/update_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						loaderHide();
						if(data=="false"){							
							$('#alert').modal('show');
							$("#edit_calendar").focus();
							$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [
																	{ 	
																		"bSortable": false, 
																		"aTargets": [2] }
																	]
															  }); 
							return;
						}else{
							cancel();
							$('#tablebody').empty();
							var row = "";
							for(i=0; i < data.length; i++ ){						
								var rowdata = JSON.stringify(data[i]);
								row += "<tr><td>" + (i+1) + "</td><td>" + data[i].calendername + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";							
							}					
							$('#tablebody').append(row);
							$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [
																	{ 	
																		"bSortable": false, 
																		"aTargets": [2] }
																	]
															  });
						}
                                          
					}
				});	
			}
	</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini"> 	
		<div class="loader">
			<center><h1 id="loader_txt"></h1></center>		
		</div>   
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
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Calendar List"/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Calendar List</h2>	
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<a href="#addmodal" class="addPlus" data-toggle="modal" >
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
							<th class="table_header">Sl No</th>
							<th class="table_header">Calendar Name</th>
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
								 <h4 class="modal-title">Edit Calendar</h4>
							</div>
							<div class="modal-body">
									<div class="row">
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
											<label for="edit_calendar">Calendar Name*</label> 
										</div>
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
											<input type="hidden" class="form-control closeinput"  id="edit_calendar1"/>
											<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_calendar" autofocus/>
											<span class="error-alert"></span>
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
								 <h4 class="modal-title">Add Calendar</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
										<label for="add_calendar">Calendar Name*</label> 
									</div>
									<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
										<input type="text" class="form-control closeinput" name="adminContactDept" id="add_calendar" autofocus/>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" class="btn" onclick="add()" value="Save">
								<input type="button" class="btn" id="cancle" onclick="cancel()" value="Cancel" >
							</div>
						</form>
					</div>
				</div>
			</div>			
		</div>
		<div id="alert" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">                                               
						<div class="modal-body">
							<div class="row">
								<center>
									<span>Calendar Name is already exists.</span>
									<br>
									<br>
									<input type="button" class="btn" data-dismiss="modal" value="Ok">
								<center>
							</div>
						</div>                            
                    </div>
                </div>
            </div>
		<?php $this->load->view('footer'); ?>

	</body>
</html>
