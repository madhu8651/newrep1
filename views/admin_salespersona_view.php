<!DOCTYPE html>
<html lang="en">
	<head>
	<?php $this->load->view('scriptfiles'); ?>
	<script>


    $(document).ready(function(){
            /* code for sandbox */
		    var url1= window.location.href;
		    var fileNameIndex1 = url1.lastIndexOf("/") + 1;
		    var filename1 = url1.substr(fileNameIndex1);
            sandbox(filename1);
			loaddata();
		});

        function loaddata(){
               $.ajax({
          		type : "POST",
          		url : "<?php echo site_url('admin_salespersonaController/get_salesperson'); ?>",
          		dataType : 'json',
          		cache : false,
          		success : function(data){
          			loaderHide();
          			if(error_handler(data)){
          				return;
          			}
          			loadpage(data);
          			var count = data.length;
          			var strcount = "sal"+(count+1);
          			$('#adddesalespCount').val(strcount);
          		}
          	});
        }

	function compose(){
			$("#add_buyerp").focus();
	}
	function cancel(){
		$('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
		$('.error-alert').text('');
	}
	function loadpage(data){
		$('#tablebody').empty();
		var row = "";
		for(var i=0, j=1; i < data.length; i++, j++){						
			var rowdata = JSON.stringify(data[i]);
			row += "<tr><td>" + (j) + "</td><td>" + data[i].lookup_value + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
		}
        /* code for sandbox */
        if(data.length >= parseInt(recordcount) && parseInt(recordcount)!= 0){
            $('#salespbtn').hide();
        }else{
            $('#salespbtn').show();
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

			
	function add(){                            
		if($.trim($("#add_saleprsn").val())==""){
			$("#add_saleprsn").closest("div").find("span").text("Executive persona is required.");
			$("#add_saleprsn").focus();
			return;
		}else if(!validate_noSpCh($.trim($("#add_saleprsn").val()))) {
			$("#add_saleprsn").closest("div").find("span").text("No special characters allowed.");
			$("#add_saleprsn").focus();
			return;
		}else if(!firstLetterChk($.trim($("#add_saleprsn").val()))) {
			$("#add_saleprsn").closest("div").find("span").text("First letter should not be Numeric or Special character.");
			$("#add_saleprsn").focus();	
			return;
		}else{
			$("#add_saleprsn").closest("div").find("span").text("");
			var addObj={};
			addObj.salesp_name = $.trim($("#add_saleprsn").val());
			addObj.salesp_count = $.trim($('#adddesalespCount').val());
			loaderShow();
			$('#tablebody').parent("table").dataTable().fnDestroy();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_salespersonaController/post_data'); ?>",
				dataType : 'json',
				data : JSON.stringify(addObj),
				cache : false,
				success : function(data){
					loaderHide();
					if(error_handler(data)){
						return;
					}
					if(data=="false"){
						$('#alert').modal('show');
						$("#add_saleprsn").focus();	
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
						loadpage(data);
						var count = data.length;
						var strcount = "sal"+(count+1); 
						$('#adddesalespCount').val(strcount);										
					}
				}
			});	
		}
	}
	function selrow(obj){
		$("#edit_salesp1").val(obj.lookup_id);
		$("#edit_salesp").val(obj.lookup_value);
		$(".error-alert").text("");
	}
	function edit_save(){
			if($("#edit_salesp").val()==""){
				$("#edit_salesp").closest("div").find("span").text("Executive person is required.");
				return;
			}else if(!validate_noSpCh($.trim($("#edit_salesp").val()))) {
				$("#edit_salesp").closest("div").find("span").text("No special characters allowed.");
				$("#edit_salesp").focus();
				return;
			}else if(!firstLetterChk($.trim($("#edit_salesp").val()))) {
				$("#edit_salesp").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#edit_salesp").focus();	
				return;
			}else{
				$("#edit_salesp").closest("div").find("span").text("");
			}
			var addObj={};
			addObj.salesp_id = $.trim($("#edit_salesp1").val());
			addObj.salesp_name = $.trim($("#edit_salesp").val());
			loaderShow();
			$('#tablebody').parent("table").dataTable().fnDestroy();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_salespersonaController/update_data'); ?>",
				dataType : 'json',
				data : JSON.stringify(addObj),
				cache : false,
				success : function(data){
					loaderHide();
					if(error_handler(data)){
						return;
					}
					if(data=="false"){
						$('#alert').modal('show');
						$("#edit_salesp").focus();
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
						loadpage(data);
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
		<?php $this->load->view('demo');  ?>
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
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Executive Persona"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Executive_Persona', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Executive Persona</h2>
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<!--<a href="#modal_upload" data-toggle="modal" class="addExcel" >
								<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a>-->
							<a href="#addmodal" class="addPlus" data-toggle="modal" id='salespbtn' onclick="compose()">
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
							<th class="table_header" width="10%">Sl No</th>
							<th class="table_header" width="80%">Executive Persona</th>
							<th class="table_header" width="10%"></th>		   
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
								 <h4 class="modal-title">Edit Executive Persona</h4>
							</div>
							<div class="modal-body">
									<div class="row">
										<div class="col-md-3">
											<label for="edit_salesp">Executive Persona*</label>
										</div>
										<div class="col-md-9">
											<input type="hidden" class="form-control closeinput"  id="edit_salesp1"/>
											<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_salesp" autofocus/>
											<span class="error-alert"></span>
                                            <input type="hidden" name="editdepartmentCount" id="editdepartmentCount"/>
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
						<form id="addpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Add Executive Persona</h4>
							</div>
							<div class="modal-body">	
									<div class="row">
										<div class="col-md-3">
											<label for="add_saleprsn">Executive Persona*</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control closeinput" name="adminContactDept" id="add_saleprsn" autofocus/>
											<span class="error-alert"></span>
                                            <input type="hidden" name="adddesalespCount" id="adddesalespCount"/>
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
		<div id="alert" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">                                               
						<div class="modal-body">
							<div class="row">
								<center>
									<span>Executive Persona already exist.</span>
									<br>
									<br>
									<input type="button" class="btn" data-dismiss="modal" value="Ok">
								</center>
							</div>
						</div>                            
                    </div>
                </div>
            </div>
		<?php $this->load->view('footer'); ?>

	</body>
</html>
