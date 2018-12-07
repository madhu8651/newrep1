<!DOCTYPE html>
<html lang="en">
	<head>
	<script src="<?php echo base_url()?>xlsx.full.min.js"></script>
	<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>-->
	<?php $this->load->view('scriptfiles'); ?>
	<script>
		function cancel(){
			$('.modal').modal('hide');
			$('.modal input[type="text"], textarea').val('');
			$('.modal select').val($('.modal select option:first'));
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
		function compose(){
			$("#add_dept").focus();
		}
		$.ajax({
			type : "POST",
			cache:false,
			url : "<?php echo site_url('admin_regionController/get_region'); ?>",
			dataType : 'json',			
			cache : false,
			success : function(data){
				console.log(data);
				//$('.modal').modal('hide');
				$('.closeinput').val('');
				$('#tablebody').empty();
				var row = "";
				for(var i=0, j=1; i < data.length; i++, j++){						
					var rowdata = JSON.stringify(data[i]);
					row += "<tr><td>" + (j) + "</td><td>" + data[i].lookup_value +"</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";						
				}	
				var count = data.length;
				var strcount = "reg"+(count+1); 
				$('#addregionCount').val(strcount);				
				$('#tablebody').append(row);					
			}
		});
		function add(){
			if($.trim($("#add_region").val())==""){
				$("#add_region").closest("div").find("span").text("Region is required.");
				$("#add_region").focus();
				return;
			}if(!validate_name($.trim($("#add_region").val()))){
				$("#add_region").closest("div").find("span").text("Enter Only Chracters");
				$("#add_region").focus();
				return;
			}else{
				$("#add_region").closest("div").find("span").text("");
			}
			var addObj={};
				addObj.regionname = $.trim($("#add_region").val());
				addObj.region_count = $('#addregionCount').val();
                                console.log(addObj.regionname);
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_regionController/add_region'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						if(data=='false'){
							alert("Region already exist");
						}else{
							cancel();
							$('#tablebody').empty();
							var row = "";
							for(i=0; i < data.length; i++ ){						
								var rowdata = JSON.stringify(data[i]);
								row += "<tr><td>" + (i+1) + "</td><td>" + data[i].lookup_value + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";							
							}
							var count = data.length;
							var strcount = "reg"+(count+1); 
							$('#addregionCount').val(strcount);
							$('#tablebody').append(row);																
						}
					}
				});	
		}
		function selrow(obj){
			$("#hiddenID").val(obj.lookup_id);
			$("#edit_region").val(obj.lookup_value);			
			$(".error-alert").text("");			
		}
		function editsave(){
			if($.trim($("#edit_region").val())==""){
				$("#edit_region").closest("div").find("span").text("Region is required.");
				$("#edit_region").focus();
				return;
			}if(!validate_name($.trim($("#edit_region").val()))){
				$("#edit_region").closest("div").find("span").text("Enter Only Chracters");
				$("#edit_region").focus();
				return;
			}else{
				$("#edit_region").closest("div").find("span").text("");
			}
			var addObj={};		
			addObj.regionid = $("#hiddenID").val();
			addObj.regionname = $("#edit_region").val();				
			
			console.log(addObj);
			//alert("dwwd");
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_regionController/post_data'); ?>",
				dataType : 'json',
				data :JSON.stringify(addObj),
				cache : false,
				success : function(data){
						if(data==0){
							alert("Region aleready exist");
						}else{							
							cancel();
							$('#tablebody').empty();	
							var row = "";
							for(i=0; i < data.length; i++ ){						
								var rowdata = JSON.stringify(data[i]);
								row += "<tr><td>" + (i+1) + "</td><td>" + data[i].lookup_value + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";							
							}
							var count = data.length;
							var strcount = "reg"+(count+1); 
							$('#addregionCount').val(strcount);
							$('#tablebody').append(row);							
						}
					}
			});
		}		
		/* function file(){			
			if($("#fileUp").val()==""){
				$("#fileUp").closest("div").find("span").text("File is required.");
				return;
			}
			else
			{
				var url = $('#fileUp').val().replace(/C:\\fakepath\\/i, '');

				console.log(url);
			      //var url = $('#fileUpload').files[0].name;
			var oReq = new XMLHttpRequest();
			oReq.open("GET", url, true);
			oReq.responseType = "arraybuffer";

			oReq.onload = function(e) {
			  var arraybuffer = oReq.response;

			  /* convert data to binary string */
			  /*var data = new Uint8Array(arraybuffer);
			  var arr = new Array();
			  for(var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
			  var bstr = arr.join("");

			  /* Call XLSX */
			  //var workbook = XLSX.read(bstr, {type:"binary"});

			  /* DO SOMETHING WITH workbook HERE */
			 	//var first_sheet_name = workbook.SheetNames[0];

			/* Get worksheet */
				/*var worksheet = workbook.Sheets[first_sheet_name];
				console.log(XLSX.utils.sheet_to_json(worksheet));
			}

			oReq.send();

				//});

			}
			var addObj={};
				addObj.fileup = $("#fileUp").val();
			//	console.log(addObj);
			
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
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Region List"/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Region List</h2>	
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
							<th class="table_header">Region</th>							
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
								 <h4 class="modal-title">Edit Region</h4>
							</div>
							<div class="modal-body">
									<div class="row">
										<div class="col-md-4">
											<label for="edit_region">Add Region*</label> 
										</div>
										<div class="col-md-8">
											<input type="hidden" class="form-control closeinput" name="hiddenID" id="hiddenID"/>
											<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_region" autofocus/>
											<span class="error-alert"></span>
										</div>									
									</div>
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="editsave()" id="save" value="Save">
									<input type="button" class="btn" onclick="cancel()" value="Cancel">
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
								 <h4 class="modal-title">Add Region</h4>
							</div>
							<div class="modal-body">		
								<div class="row">
									<div class="col-md-4">
										<label for="add_region">Add Region*</label> 
									</div>
									<div class="col-md-8">
										<input type="text" class="form-control closeinput" name="adminContactDept" id="add_region" autofocus/>
										<span class="error-alert"></span>
										 <input type="hidden" name="addregionCount" id="addregionCount"/>
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
			</div>-->
		</div>
		<?php require 'footer.php' ?>

	</body>
</html>
