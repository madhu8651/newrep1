
<!DOCTYPE html>
<html lang="en">
	<head>
	
	<?php $this->load->view('scriptfiles'); ?>
	<script>
	var mainData;
	function cancel(){
		$('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first').val());
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
		url : "<?php echo site_url('admin_industriesController/get_industry'); ?>",
		dataType : 'json',
		cache : false,
		success : function(data){
			mainData = data;
			$('.modal').modal('hide');
			$('.closeinput').val('');
			$('#tablebody').empty();
			var row = "";
			for(i=0; i < data.length; i++ ){						
				var rowdata = JSON.stringify(data[i]);
				row += "<tr><td>" + (i+1)+ "</td><td>" + data[i].lookup_value + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";							
			}
			var count = data.length;
			var strcount = "indust"+(count+1); 
			$('#addindustryCount').val(strcount);
			$('#tablebody').append(row);					
		}
	});			
		function add(){
			if($.trim($("#add_industry").val())==""){
				$("#add_industry").closest("div").find("span").text("Industry is required.");
				$("#add_industry").focus();
				return;
			}else if(!validate_name($.trim($("#add_industry").val()))){
				$("#add_industry").closest("div").find("span").text("No special chracters allowed.");
				$("#add_industry").focus();
				return;
			}else{
				$("#add_industry").closest("div").find("span").text("");
			}
			var addObj={};
			addObj.industryName = $.trim($("#add_industry").val());
			addObj.industry_count = $('#addindustryCount').val();
					
				//--------------------		
				var success=1;
				for(i=0; i < mainData.length; i++ ){
					if(addObj.industryName == mainData[i].lookup_value){
						success=0;						
					}				
				}
				if(success==0){
					alert("Industry already exists.")
					return;
				}
				//-----------------------
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_industriesController/post_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){    
                                                if(data=="false"){
                                                    
                                                    alert("Industry Name is already Exits");
                                                    $('.modal').modal('hide');
                                                    $('.closeinput').val('');
                                                }
                                                
                                                else{
                                                  cancel();
						$('#tablebody').empty();
						var row = "";
						for(i=0; i < data.length; i++ ){						
							var rowdata = JSON.stringify(data[i]);
							row += "<tr><td>" + (i+1) + "</td><td>" + data[i].lookup_value + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";							
						}
						var count = data.length;
						var strcount = "indust"+(count+1); 
						$('#addindustryCount').val(strcount);
						$('#tablebody').append(row);  
                                                }
						          
						
					}
				});	
		}
		function selrow(obj){
			$("#edit_industry1").val(obj.lookup_id);
			$("#edit_industry").val(obj.lookup_value);			
			$(".error-alert").text("");
			
		}
		function edit_save(){
				if($.trim($("#edit_industry").val())==""){
					$("#edit_industry").closest("div").find("span").text("Industry is required.");
					$("#edit_industry").focus();
					return;
				}else if(!validate_name($.trim($("#edit_industry").val()))){
					$("#edit_industry").closest("div").find("span").text("No special chracters allowed.");
					$("#edit_industry").focus();
					return;
				}
				else{
					$("#edit_industry").closest("div").find("span").text("");
				}
				var addObj={};
				addObj.industID = $("#edit_industry1").val();
				addObj.industryName = $.trim($("#edit_industry").val());
				
				//--------------------		
				var success=1;
				for(i=0; i < mainData.length; i++ ){
					if(addObj.industryName == mainData[i].lookup_value){
						success=0;						
					}				
				}
				if(success==0){
					alert("Industry already exists.")
					return;
				}
				//-----------------------
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_industriesController/update_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){

                                               
                                                 if(data=="false"){
                                                    
                                                    alert("Industry Name is already Exits");
                                                    $('.modal').modal('hide');
                                                    $('.closeinput').val('');
                                                }
                                                else{
                                                  
                                                  cancel();

						$('#tablebody').empty();
                                                 var row = "";
						for(i=0; i < data.length; i++ ){						
							var rowdata = JSON.stringify(data[i]);
							row += "<tr><td>" + (i+1) + "</td><td>" + data[i].lookup_value + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";							
						}					
						$('#tablebody').append(row);

                                                }
	

         
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
		<?php  $this->load->view('demo');  ?>
		<!---------------------------------------------------------
		side nav
		------------------------------------------------------------>		
		
		<?php $this->load->view('admin_sidenav'); ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
			<div class="row header1">				
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Industry List"/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Industry List</h2>	
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<!--<a href="#modal_upload" data-toggle="modal" class="addExcel" >
								<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a>-->
							<a href="#addmodal" class="addPlus" data-toggle="modal" >
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<table class="table" id="tableTeam" >
					<thead>  
						<tr>			
							<th class="table_header">SL No</th>
							<th class="table_header">Industry Name</th>
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
								 <h4 class="modal-title">Edit Industry</h4>
							</div>
							<div class="modal-body">
									<div class="row">
										<div class="col-md-3">
											<label for="edit_industry">Add Industry*</label> 
										</div>
										<div class="col-md-9">
											<input type="hidden" class="form-control closeinput"  id="edit_industry1"/>
											<input type="text" class="form-control closeinput" name="editdepartmentCount" id="edit_industry" autofocus/>
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
								 <h4 class="modal-title">Add Industry</h4>
							</div>
							<div class="modal-body">	
									<div class="row">
										<div class="col-md-3">
											<label for="add_industry">Add Industry*</label> 
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control closeinput" name="adminContactDept" id="add_industry" autofocus>
											<span class="error-alert"></span>
                                            <input type="hidden" name="addindustryCount" id="addindustryCount"/>
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
		</div>
		<?php $this->load->view('footer'); ?>

	</body>
</html>
