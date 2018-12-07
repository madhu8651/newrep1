
<!DOCTYPE html>
<html lang="en">
	<head>

	<?php require 'scriptfiles.php' ?>
	<style>
	input[type="file"] {
		display: block!important;
	}
	.body-content .nav.nav-tabs .active a{
		font-weight: 800 !important;
		color: #b5000a !important;
	}
	</style>

	<script>
	function cancel(){
		$('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first').val());
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
		$('.error-alert').text('');
	}
    $(document).ready(function(){
        /* code for sandbox */
        var url1= window.location.href;
        var fileNameIndex1 = url1.lastIndexOf("/") + 1;
        var filename1 = url1.substr(fileNameIndex1);
        sandbox(filename1);
        loadpage();
    });
    function loadpage(){
		$('#tablebody').parent("table").dataTable().fnDestroy();
        $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sup_mastersales_cycleController/get_cycle'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				loaderHide();
				cancel();
				if(error_handler(data)){
					return;
				}
				$('#tablebody').empty();
				var row = "";
				for(i=0; i < data.length; i++ ){
					var rowdata = JSON.stringify(data[i]);
					row += "<tr><td>" + (i+1) + "</td><td>" + data[i].master_cyclename +"</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
				}
                /* code for sandbox */
                if(data.length >= parseInt(recordcount) && parseInt(recordcount)!= 0){
                    $('#mascylbtn').hide();
                }else{
                    $('#mascylbtn').show();
                }
				$('#tablebody').append(row);
				$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [
																	{
																		"bSortable": false,
																		"aTargets": [2] }
																	]
															  });
                /*if(data.length >0){
                    $('.addBtns').hide();
                }*/

			}
		});
    }

		function compose(){
		   	$("#addmodal").modal("show");
		}
		function add(){
			if($.trim($("#add_salescycle").val())==""){
				$("#add_salescycle").closest("div").find("span").text("Support Master Cycle Name is required.");
				$("#add_salescycle").focus();
				return;
			}else if(!validate_name($.trim($("#add_salescycle").val()))) {
				$("#add_salescycle").closest("div").find("span").text("No special characters allowed (except &, _).");
				$("#add_salescycle").focus();
				return;
			}else if(!firstLetterChk($.trim($("#add_salescycle").val()))) {
				$("#add_salescycle").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#add_salescycle").focus();
				return;
			}else{
				$("#add_salescycle").closest("div").find("span").text("");
			}
			var addObj={};
			addObj.cyclename = $.trim($("#add_salescycle").val());
			loaderShow();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_sup_mastersales_cycleController/post_data'); ?>",
				dataType : 'json',
				data : JSON.stringify(addObj),
				cache : false,
				success : function(data){
					loaderHide();
					if(error_handler(data)){
						return;
					}
					if(data==1){
						$.confirm({
                    			title: 'Support Master Cycle',
                    			content: 'Cycle Already Exist.',
                    			animation: 'none',
                    			closeAnimation: 'scale',
                    			buttons: {
                    				Ok: function () {
                    					window.location.reload(true);
                    				}
                    			}
                	    });
					}
					loadpage();
				}
			});
		}
		function selrow(obj){
			$("#edit_salescycle").val(obj.master_cyclename);
			$("#hidcycleid").val(obj.master_cycleid);
			$(".error-alert").text("");
		}

		function edit_save(){
				if($("#edit_salescycle").val()==""){
					$("#edit_salescycle").closest("div").find("span").text("Support Master Cycle Name is required.");
					$("#edit_salescycle").focus();
					return;
				}else if(!validate_name($.trim($("#edit_salescycle").val()))) {
					$("#edit_salescycle").closest("div").find("span").text("No special characters allowed (except &, _).");
					$("#edit_salescycle").focus();
					return;
				}else if(!firstLetterChk($.trim($("#edit_salescycle").val()))) {
					$("#edit_salescycle").closest("div").find("span").text("First letter should not be Numeric or Special character.");
					$("#edit_salescycle").focus();
					return;
				}else{
					$("#edit_salescycle").closest("div").find("span").text("");
				}
				var addObj={};
                addObj.cyclename = $.trim($("#edit_salescycle").val());
                addObj.cycleid = $.trim($("#hidcycleid").val());
				loaderShow();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_sup_mastersales_cycleController/update_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						loaderHide();
						if(error_handler(data)){
							return;
						}
						if(data==1){
							$.confirm({
                    			title: 'Support Master Cycle',
                    			content: 'Cycle Already Exist.',
                    			animation: 'none',
                    			closeAnimation: 'scale',
                    			buttons: {
                    				Ok: function () {
                    					window.location.reload(true);
                    				}
                    			}
                		    });
						}
                        loadpage();
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
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Master Sales Cycle"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Master_Cycle', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Support Master Cycle</h2>
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<!--<a href="#modal_upload" data-toggle="modal" class="addExcel" >
								<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a>-->
							<a class="addPlus" id='mascylbtn' onclick="compose()">
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="<?php echo site_url('admin_sup_mastersales_cycleController'); ?>">Support Master Cycle</a>
					</li>
					<li>
						<a href="<?php echo site_url('admin_sup_mastersales_stageController'); ?>">Support Master Stage Flowchart</a>
					</li>
				</ul>
				<table class="table" id="tableTeam">
					<thead>
						<tr>
							<th class="table_header" width="20%">Sl No</th>
							<th class="table_header" width="60%">Support Master Cycle</th>
                            <th class="table_header" width="20%"></th>
						</tr>
					</thead>
					<tbody id="tablebody">
					</tbody>
				</table>
			</div>
			<div id="editmodal" class="modal fade" data-backdrop="static">
                <input type="hidden" id="hidcycleid" />
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="editpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Edit Support Master Cycle</h4>
							</div>
							<div class="modal-body">

									<div class="row">
										<div class="col-md-3">
											<label for="edit_loc">Support Master Cycle*</label>
										</div>
										<div class="col-md-9">

											<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_salescycle" autofocus/>
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
								 <h4 class="modal-title">Add Support Master Cycle</h4>
							</div>
							<div class="modal-body">

									<div class="row">
										<div class="col-md-3">
											<label for="add_region1">Support Master Cycle*</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control closeinput" name="adminContactDept" id="add_salescycle" autofocus/>
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
            <div id="alert" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
						<div class="modal-body">
							<div class="row">
								<center>
									<span>Cycle already exist.</span>
									<br>
									<br>
									<input type="button" class="btn" data-dismiss="modal" value="Ok">
								</center>
							</div>
						</div>
                    </div>
                </div>
            </div>

		</div>
		<?php require 'footer.php' ?>

	</body>
</html>
