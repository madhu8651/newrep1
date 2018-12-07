<!DOCTYPE html>
<html lang="en">
	<head>

	<?php $this->load->view('scriptfiles'); ?>
    <style>
    input[type="file"] {
  display: block!important;
 }
  .switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 20px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #B5000A;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 15px;
  width: 15px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: green;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(19px);
  -ms-transform: translateX(19px);
  transform: translateX(19px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.section-two,
.section-three,
.section-four{
	display:none;
}
ul {
        list-style-type: none;
    }
.tree-view ul{
	padding-left:20px;
	border-left: 1px dotted;
}
.tree-view ul.mytree{
	border-left: 0px;
}
.tree-view ul li label{
	margin-bottom: 0px;
}
.dash-left .glyphicon {
	position: absolute;
}
.dash-left{
	margin-left: -17px;
	float: left;
	position: absolute;
}
	/*------------------ Custom alert------------------------ */
			.mask{
			 width: 100%;
				margin: auto;
				height: 100%;
				position: absolute;
				top: 0;
				background: transparent;
				z-index: 999999;
			}
			.alert.row{
				position: absolute;
				z-index: 99999999;
				top: 0;
				width: 44%;
				margin: 20% 28% !important;
			}

    </style>
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
    			url : "<?php echo site_url('admin_support_processController/get_buyerperson'); ?>",
    			dataType : 'json',
    			cache : false,
    			success : function(data){
    				loaderHide();
    				if(error_handler(data)){
    					return;
    				}
    				loadpage(data);
    				var count = data.length;
    				var strcount = "sup_pro"+(count+1);
    				$('#adddbuyerpCount').val(strcount);
    			}
    		});
        }

		function loadpage(data){
			$('#tablebody').empty();
			var row = "";
            var j=1;
                str="";
                for(var i1=0; i1 < data.length; i1++){
				    var rowdata = JSON.stringify(data[i1]);
                        if(data[i1].togglebit == 0){
                           var str="";
                        }
                        else if(data[i1].togglebit == 1){
                            var str="checked";
                        }
                        if(data[i1].lookup_id=='new_sell'){

                        }else if(data[i1].lookup_id=='up_sell'){

                        }else if(data[i1].lookup_id=='cross_sell'){

                        }else if(data[i1].lookup_id=='renewal'){

                        }else{
                            row += "<tr><td>" + (j) + "</td><td>" + data[i1].lookup_value + "</td><td><a data-toggle='modal' href='#editmodal'  onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td><td><label class='switch'><input  onchange='toggle(\"aa"+data[i1].id+"\",\""+data[i1].lookup_id+"\","+rowdata+")' id='aa"+data[i1].id+"' "+str+" type='checkbox'><div class='slider round'></div></label> </td></tr>";
                            j++;
                        }
                }
                /* code for sandbox */
                if(data.length >= parseInt(recordcount) && parseInt(recordcount)!= 0){
                    $('#supprobtn').hide();
                }else{
                    $('#supprobtn').show();
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

        /* ------------------------------------------------------- toggle button function --------------------------------------------------------------------------- */
function toggle(id,lookupid,obj){
  if($("#"+id).prop('checked')==true){
                        $("body").append('<div class="mask custom-alert"></div><div class="alert row custom-alert" style="background:#3c8dbc"> <div class="col-md-9"> <b>Do you Really wish to Activate the Process?</b> </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
    	                $(".Ok").click(function(){
    		                $(".custom-alert").remove();
                            var tgbit=1;
                            var addObj={};
                            addObj.status="donotcheckinteam";
                            addObj.toggleid = tgbit;
                            addObj.lookupid = lookupid;
                            $.ajax({
                    			type : "POST",
                    			url : "<?php echo site_url('admin_support_processController/update_tg_bit'); ?>",
                    			dataType : 'json',
                    			data : JSON.stringify(addObj),
                    			cache : false,
                    			success : function(data){
    								if(error_handler(data)){
    									return;
    								}
                                    window.location.reload(true);

                    			}
                            });
                        });
    	                    $(".notOk").click(function(){
                                window.location.reload(true);
    	                });

  }else{

        $("body").append('<div class="mask custom-alert"></div><div class="alert row custom-alert" style="background:#3c8dbc"> <div class="col-md-9"> <b>Do you Really wish to Deactive the Process?</b> </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
    	$(".Ok").click(function(){
    		$(".custom-alert").remove();
            var tgbit=0;
            var addObj={};
            addObj.status="checkinteam";
            addObj.lookupid = lookupid;
            addObj.toggleid = tgbit;

            $.ajax({
    			type : "POST",
    			url : "<?php echo site_url('admin_support_processController/update_tg_bit'); ?>",
    			dataType : 'json',
    			data : JSON.stringify(addObj),
    			cache : false,
    			success : function(data){
					if(error_handler(data)){
						return;
					}
                    if(data == 1 || data == '1'){

                        $.confirm({
                			title: 'Support Process',
                			content: 'Process Type is mapped in Teams. Hence Cannot Deactivate.',
                			animation: 'none',
                			closeAnimation: 'scale',
                			buttons: {
                				Ok: function () {
                					window.location.reload(true);
                				}
                			}
                		});
                    }else{
                        window.location.reload(true);
                    }


    			}
            });
    	});
    	$(".notOk").click(function(){
                window.location.reload(true);
    	});

  }
}

		function cancel(){
			$('.modal').modal('hide');
			$('.modal input[type="text"], textarea').val('');
			$('.modal select').val($('.modal select option:first').val());
			$('.modal input[type="radio"]').prop('checked', false);
			$('.modal input[type="checkbox"]').prop('checked', false);
			$('.error-alert').text('');
		}
		function compose(){
			$("#add_activity").focus();
		}
		function add(){
			if($.trim($("#add_activity").val())==""){
				$("#add_activity").closest("div").find("span").text("Process Type is required.");
				$("#edit_activity").focus();
				return;
			}else if(!validate_noSpCh($.trim($("#add_activity").val()))) {
				$("#add_activity").closest("div").find("span").text("No special characters allowed.");
				$("#add_activity").focus();
				return;
			}else if(!firstLetterChk($.trim($("#add_activity").val()))) {
				$("#add_activity").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#add_activity").focus();
				return;
			}else{
				$("#add_activity").closest("div").find("span").text("");
				var addObj={};
				addObj.buyerp_name = $.trim($("#add_activity").val());
                addObj.buyerp_count = $.trim($("#adddbuyerpCount").val());
				loaderShow();
				$('#tablebody').parent("table").dataTable().fnDestroy();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_support_processController/post_data'); ?>",
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
                            $("#alert center span").text("Process Type already exists.");
							$("#add_activity").focus();
							$('#tablebody').parent("table").DataTable();
							return;
						}else{
							cancel();
							loadpage(data);
							var count = data.length;
							var strcount = "sup_pro"+(count+1);
							$('#adddbuyerpCount').val(strcount);
							
						}
					}
				});
			}
		}
		function selrow(obj){
			$("#edit_activity1").val(obj.lookup_id);
			$("#edit_activity").val(obj.lookup_value);
			$(".error-alert").text("");
		}
		function edit_save(){
				if($.trim($("#edit_activity").val())==""){
					$("#edit_activity").closest("div").find("span").text("Process Type is required.");
					$("#edit_activity").focus();
					return;
				}else if(!validate_noSpCh($.trim($("#edit_activity").val()))) {
					$("#edit_activity").closest("div").find("span").text("No special characters allowed.");
					$("#edit_activity").focus();
					return;
				}else if(!firstLetterChk($.trim($("#edit_activity").val()))) {
					$("#edit_activity").closest("div").find("span").text("First letter should not be Numeric or Special character.");
					$("#edit_activity").focus();
					return;
				}else{
					$("#edit_activity").closest("div").find("span").text("");
				}
				var addObj={};
				addObj.buyerp_id = $.trim($("#edit_activity1").val());
				addObj.buyerp_name = $.trim($("#edit_activity").val());
				loaderShow();
				$('#tablebody').parent("table").dataTable().fnDestroy();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_support_processController/update_data'); ?>",
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
                            $("#alert center span").text("Process Type already exists.");
							$("#edit_activity").focus();
							$('#tablebody').parent("table").DataTable();
							return;
						}else{
							cancel();
							loadpage(data);
						}
					}
				});
		}
		function file(){
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
						if(error_handler(data)){
							return;
						}
						cancel();
						$('#tablebody').empty();
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
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Support Process List"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Support_Process', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Support Process List</h2>
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<!--<a href="#modal_upload" data-toggle="modal" class="addExcel" >
								<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a>-->
							<a href="#addmodal" class="addPlus" id='supprobtn' data-toggle="modal" onclick="compose()">
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
							<th class="table_header" width="10%">SL No</th>
							<th class="table_header"width="80%">Support Process</th>
							<th class="table_header"width="10%"></th>
							<th class="table_header"width="10%"></th>
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
								 <h4 class="modal-title">Edit Process </h4>
							</div>
							<div class="modal-body">
									<div class="row">
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
											<label for="edit_activity">Process Type*</label>
										</div>
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
											<input type="hidden" class="form-control closeinput"  id="edit_activity1"/>
											<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_activity"/>
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
								 <h4 class="modal-title">Add Process</h4>
							</div>
							<div class="modal-body">
									<div class="row">
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
											<label for="add_activity">Process Type*</label>
										</div>
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
											<input type="text" class="form-control closeinput" name="adminContactDept" id="add_activity"/>
											<span class="error-alert"></span>
                                            <input type="hidden" name="adddbuyerpCount" id="adddbuyerpCount"/>
										</div>
									</div>
							</div>
							<div class="modal-footer" >
									<input type="button" class="btn" onclick="add()" value="Save">
									<input type="button" class="btn" onclick="cancel()" value="Cancel" >
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="modal_upload" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Buyer Persona Details</h4>
							</div>
							<div class="modal-body">
									<div class="row">
										<div class="col-md-3">
											<label for="add_role">Add Buyer Persona Details*</label>
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
							</div>
							<div class="modal-footer" >
									<input type="button" class="btn" onclick="file()" value="Save">
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
									<span></span>
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
