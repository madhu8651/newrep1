
<!DOCTYPE html>
<html lang="en">
	<head>

	<?php require 'scriptfiles.php' ?>
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
        $(".custom-alert").remove();
	}
    var timeValChk  = /^([0-9]{2}):[0-5][0-9]$/;
    $(document).ready(function(){
        /* code for sandbox */
        var url1= window.location.href;
        var fileNameIndex1 = url1.lastIndexOf("/") + 1;
        var filename1 = url1.substr(fileNameIndex1);
        $('#first_access_time .glyphicon.glyphicon-remove').on('click', function(){
					$('#first_access_time input[type=text]').val("");
					$('#first_access_time').siblings(".error-alert").text("");
		});
        sandbox(filename1);
        loadpage();
    });
    $(function() {
					var regExp = /[a-z]/i;
					$('#first_access_time input[type=text]').on('keydown keyup', function() {
						$(this).val($(this).val().replace(/(\d{2})\:?(\d{2})/,'$1:$2'));
					})
					$('#first_access_time input[type=text]').on('keydown keyup', function(e) {
						var value = String.fromCharCode(e.which) || e.key;
						if(value == "a" || value == "b"|| value == "c"|| value == "d" || value == "e" || value == "f"|| value == "g" || value == "h" || value == "i"|| value == "n"){

						}else{
							if (regExp.test(value)) {
								e.preventDefault();
								return false;
							}
						}
						if (!timeValChk.test($('#first_access_time input[type=text]').val())){
							$('#first_access_time').siblings(".error-alert").text("Invalid Time format.");
							return;
						}else{
							$('#first_access_time').siblings(".error-alert").text("");
						}
					});
	});
    var activecnt=0;
    function loadpage(){

		$('#tablebody').parent("table").dataTable().fnDestroy();
        $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sup_sales_cycleController/get_cycle'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
			    activecnt=0;
				loaderHide();
				cancel();
				if(error_handler(data)){
					return;
				}
				$('#tablebody').empty();
				var row = "";

				for(i=0; i < data.length; i++ ){
					var rowdata = JSON.stringify(data[i]);
                    if(data[i].togglebit == 0){
                       var str="";
                    }
                    else if(data[i].togglebit == 1){
                        var str="checked";
                        activecnt++;
                    }
					row += "<tr><td>" + (i+1) + "</td><td>" + data[i].CYCLE_NAME +"</td><td>"+ data[i].master_cyclename +"</td><td>"+ data[i].tatdays +"</td><td>"+ data[i].tattime +"</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td><td><label class='switch'><input  onchange='toggle(\"aa"+data[i].id+"\",\""+data[i].CYCLE_ID+"\","+rowdata+")' id='aa"+data[i].id+"' "+str+" type='checkbox'><div class='slider round'></div></label> </td></tr>";
				}
                /* code for sandbox */
                if(data.length >= parseInt(recordcount) && parseInt(recordcount)!= 0 && parseInt(activecnt)==2){
                    $('#salescylbtn').hide();
                }else{
                    $('#salescylbtn').show();
                }



				$('#tablebody').append(row);
				$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [
																	{
																		"bSortable": false,
																		"aTargets": [5,6] }
																	]
															  });
                if(versiontype=='lite' && parseInt(activecnt)>2){
                      resetbit();
                }

			}
		});
    }

function resetbit(){

  $.ajax({
        type : "POST",
        url : "<?php echo site_url('admin_sup_sales_cycleController/resetbit'); ?>",
        dataType : 'json',
        cache : false,
        success : function(data){
            if(error_handler(data)){
                return;
            }
            loadpage();

        }
    });

}

/* ------------------------------------------------------- toggle button function --------------------------------------------------------------------------- */
function toggle(id,cycle_id,obj){
  if($("#"+id).prop('checked')==true){
                        //alert(activecnt);
                        if(parseInt(activecnt)==2 && versiontype=='lite'){

                                $("body").append('<div class="mask custom-alert"></div><div class="alert alert-danger row custom-alert"> <div class="col-md-9">Maximum of two Sales Cycles can be Active for Lite Version!</div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
            							$(".Ok").click(function(){
            								$(".custom-alert").remove();
                                            //window.location.reload(true);
                                            loadpage();
            							});
            							$(".notOk").click(function(){
            								$(".custom-alert").remove()
            								//window.location.reload(true);
                                            loadpage();
            							});

                        }else{
                              var tgbit=1;
                              var addObj={};
                              addObj.toggleid = tgbit;
                              addObj.cycle_id = cycle_id;
                              addObj.cycle_name = obj.CYCLE_NAME;
                              $.ajax({
                      			type : "POST",
                      			url : "<?php echo site_url('admin_sup_sales_cycleController/check_activecycle'); ?>",
                      			dataType : 'json',
                      			data : JSON.stringify(addObj),
                      			cache : false,
                      			success : function(data){
      								if(error_handler(data)){
      									return;
      								}
                                      if(data!="nocycle"){

                                              $("body").append('<div class="mask custom-alert"></div><div class="alert alert-danger row custom-alert"> <div class="col-md-9">Sales Cycle is found active .Do you wish to deactivate the cycle  </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
                  							$(".Ok").click(function(){
                  								$(".custom-alert").remove()
                  								updatebit(data,cycle_id);
                  							});
                  							$(".notOk").click(function(){
                  								$(".custom-alert").remove()
                  								cancel();
                  								loadpage();
                  							});
                                      }else{
                                              updatebit1(cycle_id);
                                      }
                      			}
                              });

                      }

  }else{

        $("body").append('<div class="mask custom-alert"></div><div class="alert row custom-alert" style="background:#3c8dbc"> <div class="col-md-9"> <b>Do you Really wish to Deactive the Cycle?</b> </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
    	$(".Ok").click(function(){
    		$(".custom-alert").remove();
            var tgbit=0;
            var addObj={};
            addObj.status="truechk1";
            addObj.cycle_id = cycle_id;
            addObj.toggleid = tgbit;

            $.ajax({
    			type : "POST",
    			url : "<?php echo site_url('admin_sup_sales_cycleController/update_tg_bit'); ?>",
    			dataType : 'json',
    			data : JSON.stringify(addObj),
    			cache : false,
    			success : function(data){
					if(error_handler(data)){
						return;
					}
    				cancel();
    				loadpage();
    			}
            });
    	});
    	$(".notOk").click(function(){
                cancel();
    			loadpage();
    	});

  }
}

function updatebit(found_cycleid,selected_cycle_id){

             addObj={};
             var tgbit=0;
             addObj.status="truechk";
             addObj.found_cycleid=found_cycleid;
             addObj.toggleid = 0;
             addObj.selected_cycle_id=selected_cycle_id;
             addObj.toggleid1 = 1;
             $.ajax({
    			type : "POST",
    			url : "<?php echo site_url('admin_sup_sales_cycleController/update_tg_bit'); ?>",
    			dataType : 'json',
    			data : JSON.stringify(addObj),
    			cache : false,
    			success : function(data){
					if(error_handler(data)){
						return;
					}
    				cancel();
    				loadpage();

    			}
            });

}

function updatebit1(cycle_id){

         $("body").append('<div class="mask custom-alert"></div><div class="alert row custom-alert" style="background:#3c8dbc"> <div class="col-md-9"> <b>Do you Really wish to Active the Cycle?</b> </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
    	 $(".Ok").click(function(){
    		$(".custom-alert").remove();
            var tgbit=1;
            var addObj={};
            addObj.status="truechk1";
            addObj.cycle_id = cycle_id;
            addObj.toggleid = tgbit;

            $.ajax({
    			type : "POST",
    			url : "<?php echo site_url('admin_sup_sales_cycleController/update_tg_bit'); ?>",
    			dataType : 'json',
    			data : JSON.stringify(addObj),
    			cache : false,
    			success : function(data){
					if(error_handler(data)){
						return;
					}
    				cancel();
    				loadpage();
    			}
            });
    	});
    	$(".notOk").click(function(){
               cancel();
    		   loadpage();
    	});

}

		function compose(){
			$("#addmodal").modal("show")
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin_sup_sales_cycleController/get_mastercycle'); ?>",
				dataType:'json',
				success: function(data) {
					if(error_handler(data)){
						return;
					}
					var select = $("#add_mastercycle"), options = "<option value=''>select</option>";
					select.empty();
					for(var i=0;i<data.length; i++){
						options += "<option value='"+data[i].master_cycleid+"'>"+ data[i].master_cyclename +"</option>";
					}
					select.append(options);
				}
			});
		}


		function add(){

			if($.trim($("#add_salescycle").val())==""){
				$("#add_salescycle").closest("div").find("span").text("Support Cycle is required.");
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
            if($.trim($("#add_mastercycle").val())==""){
				$("#add_mastercycle").closest("div").find("span").text("Support Master Cycle is required.");
				$("#add_mastercycle").focus();
				return;
			}else{
				$("#add_mastercycle").closest("div").find("span").text("");
			}
            if($.trim($('#first_access_time input[type=text]').val()) != ""){

                var LLTime = $.trim($('#first_access_time input[type=text]').val()).split(":");

    			if (!timeValChk.test($.trim($('#first_access_time input[type=text]').val()))){
    				$('#first_access_time').siblings(".error-alert").text("Invalid Time format.");
                    loaderHide();
    				return;
    			}else if((LLTime[1] > 60)){
    				$('#first_access_time').siblings(".error-alert").text("Invalid Time format.");
                    loaderHide();
    				return;
    			}else{
    				$('#first_access_time').siblings(".error-alert").text("");
    			}
            }
			var addObj={};
				addObj.mcycleid = $("#add_mastercycle").val();
				addObj.cyclename = $.trim($("#add_salescycle").val());
                addObj.firstaccesstime=$.trim($('#first_access_time input[type=text]').val());
                addObj.days=$.trim($("#dys").val());
                console.log(addObj);
				loaderShow();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_sup_sales_cycleController/post_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						if(error_handler(data)){
							return;
						}
						if(data==1){
							$.confirm({
                    			title: 'Support Cycle',
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
			$("#edit_salescycle").val(obj.CYCLE_NAME);
			$("#hidcycleid").val(obj.CYCLE_ID);
			$("#dysE").val(obj.tatdays);
			$(".error-alert").text("");
            var a1=obj.tattime.split(":");
            var llt1=a1[0]+":"+a1[1];
			$('#first_access_timeE input[type=text]').val(llt1);
            $.ajax({
    				type: "POST",
    				url: "<?php echo site_url('admin_sup_sales_cycleController/get_mastercycle'); ?>",
    				dataType:'json',
    				success: function(data) {
						if(error_handler(data)){
							return;
						}
    					var select = $("#edit_mastercycle"), options = "<option value=''>select</option>";
    					select.empty();
    					for(var i=0;i<data.length; i++){
    						options += "<option value='"+data[i].master_cycleid+"'>"+ data[i].master_cyclename +"</option>";
    					}
    					select.append(options);
                        $("#edit_mastercycle").val(obj.MASTERCYCLE_ID);
    				}
    		});
		}

		function edit_save(){

				if($("#edit_salescycle").val()==""){
					$("#edit_salescycle").closest("div").find("span").text("Support Cycle is required.");
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

                if($.trim($("#edit_mastercycle").val())==""){
					$("#edit_mastercycle").closest("div").find("span").text("Support Master Cycle is required.");
					$("#edit_mastercycle").focus();
					return;
				}else{
					$("#edit_mastercycle").closest("div").find("span").text("");
				}
                if($.trim($('#first_access_timeE input[type=text]').val()) != ""){

                        var LLTime = $.trim($('#first_access_timeE input[type=text]').val()).split(":");

            			if (!timeValChk.test($.trim($('#first_access_timeE input[type=text]').val()))){
            				$('#first_access_timeE').siblings(".error-alert").text("Invalid Time format.");
                            loaderHide();
            				return;
            			}else if((LLTime[1] > 60)){
            				$('#first_access_timeE').siblings(".error-alert").text("Invalid Time format.");
                            loaderHide();
            				return;
            			}else{
            				$('#first_access_timeE').siblings(".error-alert").text("");
            			}
                }
				var addObj={};
				addObj.mcycleid = $("#edit_mastercycle").val();
                addObj.cyclename = $("#edit_salescycle").val();
                addObj.cycleid = $("#hidcycleid").val();
                addObj.firstaccesstime=$.trim($('#first_access_timeE input[type=text]').val());
                addObj.days=$.trim($("#dysE").val());
				loaderShow();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_sup_sales_cycleController/update_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						if(error_handler(data)){
							return;
						}
						if(data==1){
							$.confirm({
                    			title: 'Support Cycle',
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
        function chg(){
            if($('#tat').prop('checked')==true){

                  $('#dys').prop('disabled',false);
                  $('#first_access_time_txt').prop('disabled',false);
            }else{
                 $('#dys, #first_access_time_txt').prop('disabled',true);
                 $("#dys").val("");
                 $("#dys").closest("div").find("span").text("");
                 $('#first_access_time input[type=text]').val("");
                 $('#first_access_time .glyphicon.glyphicon-remove').on('click', function(){
    					$('#first_access_time input[type=text]').val("");
    					$('#first_access_time').siblings(".error-alert").text("");
    		     });
            }
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
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Support Cycle"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Sales_Cycle', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Support Cycle</h2>
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<!--<a href="#modal_upload" data-toggle="modal" class="addExcel" >
								<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a>-->
							<a class="addPlus" id='salescylbtn' onclick="compose()">
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="<?php echo site_url('admin_sup_sales_cycleController'); ?>">Support Cycle</a>
					</li>
					<li>
						<a href="<?php echo site_url('admin_sup_sales_stageController'); ?>">Support Stage Flowchart</a>
					</li>
				</ul>
				<table class="table" id="tableTeam">
					<thead>
						<tr>
							<th class="table_header">Sl No</th>
							<th class="table_header">Support Cycle</th>
							<th class="table_header">Support Master Cycle</th>
							<th class="table_header">TAT in Days</th>
							<th class="table_header">TAT in HH:MM</th>
							<th class="table_header"></th>
							<th class="table_header"></th>
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
								 <h4 class="modal-title">Edit Support Cycle</h4>
							</div>
							<div class="modal-body">

									<div class="row">
										<div class="col-md-4">
											<label for="edit_loc">Support Cycle*</label>
										</div>
										<div class="col-md-8">

											<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_salescycle" autofocus/>
											<span class="error-alert"></span>
											<input type="hidden" class="form-control closeinput"  id="edit_region2"/>
										</div>
									</div>
                                    <div class="row">
										<div class="col-md-4">
											<label for="edit_region">Support Master Cycle *</label>
										</div>
										<div class="col-md-8">
											<select name="adminContactDept" class="form-control" id="edit_mastercycle" >
                                              <option value="">Select</option>
											</select>
											<span class="error-alert"></span>
										</div>
									</div>
                                    <div class="row">
                                        <div class="col-md-4">
                                        </div>
                                        <div class="col-md-8">
                                                <label for="tatE">
											        <!--<input type="checkbox" name="approve7" value="tat" id="tat" onclick="chg();"/> Turn Around Time (TAT in Days Hours & minutes)       -->
											        Turn Around Time (TAT in Days Hours & minutes)
										        </label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">

                                        </div>
    									<div class="col-md-4">
    										<input type='number' min='0' name="dys" id="dysE" placeholder="Days" class="form-control" >
                                            <span class="error-alert"></span>
    									</div>
    									<div class="col-md-4">
    										<div class='input-group date' id="first_access_timeE">
    											<input type='text' id="first_access_time_txtE" class="form-control" maxlength="5" placeholder="HH:MM"/>
    											<span class="input-group-addon">
    												<label class="glyphicon glyphicon-remove" for="first_access_time_txtE" title="Clear"></label>
    											</span>
    										</div>
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
								 <h4 class="modal-title">Add Support Cycle</h4>
							</div>
							<div class="modal-body">

									<div class="row">
										<div class="col-md-4">
											<label for="add_region1">Support Cycle*</label>
										</div>
										<div class="col-md-8">
											<input type="text" class="form-control closeinput" name="adminContactDept" id="add_salescycle" autofocus/>
											<span class="error-alert"></span>
											<input type="hidden" name="addlocCount" id="addlocCount"/>
										</div>
									</div>
                                    <div class="row">
										<div class="col-md-4">
											<label for="add_region">Support Master Cycle *</label>
										</div>
										<div class="col-md-8">
											<select name="adminContactDept" class="form-control" id="add_mastercycle" >
                                                <option value="">Select</option>
											</select>
											<span class="error-alert"></span>
										</div>
									</div>
                                    <div class="row">
                                        <div class="col-md-4">
                                        </div>
                                        <div class="col-md-8">
                                                <label for="tat">
											        <!--<input type="checkbox" name="approve7" value="tat" id="tat" onclick="chg();"/> Turn Around Time (TAT in Days Hours & minutes)       -->
											        Turn Around Time (TAT in Days Hours & minutes)
										        </label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">

                                        </div>
    									<div class="col-md-4">
    										<input type='number' min='0' name="dys" id="dys" placeholder="Days" class="form-control" >
                                            <span class="error-alert"></span>
    									</div>
    									<div class="col-md-4">
    										<div class='input-group date' id="first_access_time">
    											<input type='text' id="first_access_time_txt" class="form-control" maxlength="5" placeholder="HH:MM"/>
    											<span class="input-group-addon">
    												<label class="glyphicon glyphicon-remove" for="first_access_time_txt" title="Clear"></label>
    											</span>
    										</div>
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

		</div>
		<?php require 'footer.php' ?>

	</body>
</html>
