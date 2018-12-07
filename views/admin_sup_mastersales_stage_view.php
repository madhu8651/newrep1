
<!DOCTYPE html>
<html lang="en">
	<head>
	<?php $this->load->view('scriptfiles'); ?>
	<script>
	var base_url = '<?php echo base_url(); ?>';
	</script>
	<style>

	.body-content .nav.nav-tabs .active a{
		font-weight: 800 !important;
		color: #b5000a !important;
	}
	</style>
   <script>
	$(window).load(function() {

		loadpage();
        auto_load();

	});

    function compose(){
            var activeCycle = $.trim($("#CYCLE_NAME li.active").text());
            var activeCycleID = $("#CYCLE_NAME li.active a").attr('id');
            $("#addmodal .modal-title").text("").text("Add Stage For "+ activeCycle);
                //alert(activeCycleID);
			    $("#addmodal .modal-header").append('<input type="hidden" value="'+activeCycleID+'">');
             if($("#add_cyclename option").length > 1){
                 $("#addmodal").modal('show');
             }else{
                alert("No Sales Cycle added.");
             }
    }
	/* --------------------------------------Load Table fuction */
    var flg=0,p=[];
	function loadpage(){
		/*$('#stagedata').find("table").dataTable().fnDestroy(); */
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sup_mastersales_stageController/get_data'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){

				if(error_handler(data)){
							return;
						}
				$('#CYCLE_NAME').empty();
				$('#stagedata').empty();
				var row = "";
				var row1 = "";
                $("#main_data_content").show();
                $("#no_data_section").addClass('none').html("");
                if(data[0].hasOwnProperty("CYCLE_ID")==true){
                        // loaderHide();
                        var row = "";
                        dupbit=data[data.length-1].dupbit;
    					for(var i=0,j=0; i < (data.length-1); i++,j++ ){

    						var rowdata = data[i].CYCLE_ID;
    							if(i==0){
    								row += "<li onclick='renderSingleTable1(\""+data[i].CYCLE_ID+"\",\""+dupbit+"\")' class='active'><a id='id"+data[i].CYCLE_ID+"'  href='#"+data[i].CYCLE_ID+"' data-toggle='tab'>" + data[i].CYCLE_NAME+"</li>";
    							}else{
    								row += "<li><a onclick='renderSingleTable1(\""+data[i].CYCLE_ID+"\",\""+dupbit+"\")' id='id"+data[i].CYCLE_ID+"'  href='#"+data[i].CYCLE_ID+"' data-toggle='tab'>" + data[i].CYCLE_NAME+"</li>";
    							}

    					}

    					$('#CYCLE_NAME').append(row);
    					renderSingleTable1(data[0].CYCLE_ID,dupbit);

                }else{
                    loaderHide();
                    if(p.length>0){
                            $("#main_data_content").hide();
                            $("#no_data_section").removeClass('none').append('<center style="padding-top: 60px;"><h4>No data available. <br>Add data by clicking on the Plus button.</h4></center>');
                    }else{
                            $("#main_data_content").hide();
                            $("#no_data_section").removeClass('none').append("<center style='padding-top: 60px;'><h4>No data available. Click on the below link or visit <b>Master Sales Cycle</b> page to add data<br> <a href='<?php echo site_url('admin_mastersales_cycleController'); ?>'><u>Master Sales Cycle </u></a></h4></center>");
                    }
                }
			}
		});
	}

 /* ------------------------------------------------------------------------------------------------------------------------------- */
 function renderSingleTable1(cycleid,dupbit){

        var addObj={};
        addObj.cycleid=cycleid;
         loaderShow();
         $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sup_mastersales_stageController/get_data2'); ?>",
			dataType : 'json',
            data: JSON.stringify(addObj),
			cache : false,
			success : function(data){
                    loaderHide();
				    if(error_handler(data)){
							return;
				    }
                    $('#stagedata').empty();
                    var row1 = "";
                    oppCycleid=[];
                        i=0;
					    row1 += '<div class="tab-pane active" id="'+cycleid+'">';

                        if(data[i].hasOwnProperty("stagedata")){
        						    row1 += '<table class="table"><thead>';
            						row1 += "<tr><th width='20%'>Sl No</th><th width='20%'>Stage Name</th><th width='20%'>Description</th><th width='20%'>Edit</th></tr></thead><tbody class='ui-sortable'>";
                                    var duplicateBtn = 0;
            						for(var k=0; k < data[i].stagedata.length; k++ ){
            							var jsondata=JSON.stringify([{"CYCLE_ID":data[i].CYCLE_ID,
            														"stage_name":data[i].stagedata[k].stage_name,
            														"stage_id":data[i].stagedata[k].stage_id,
            														"remarks":data[i].stagedata[k].remarks
            														}]);
            							var remarks = "";
            							if(data[i].stagedata[k].remarks == null ){
            								remarks = "";
            							}else{
            								remarks = data[i].stagedata[k].remarks;
            							}
            							row1 += "<tr id='" + data[i].stagedata[k].id + "'><td>" + (k+1) + "</td><td>" + data[i].stagedata[k].stage_name + "</td><td>" + remarks + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+jsondata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
            						}
                            }else{
								row1 += "<center style='padding-top: 60px;'><h4>No data available.</h4></center>";
								duplicateBtn = 1;
							}


					row1 += '</tbody></table>';
                    if(data[i].hasOwnProperty("stagedata")){
                              row1 += "<center><button id='saveorderElm' type='button' class='btn btn-primary' onclick='saveorder(\""+data[i].CYCLE_ID+"\")'>Save Stage Order</button></center>";

                    }

					if(duplicateBtn == 1){

						if(data[0].hasOwnProperty("stagedata") ==false && dupbit==1){
							row1 += "<center id='createDuplicateElm'><button type='button' class='btn btn-primary' onclick='createDuplicate(\""+data[i].CYCLE_ID+"\")'>Duplicate Stage Flowchart</button></center>";
						}
					}else{

					}
                    row1 += '</div>';
                    $('#stagedata').append(row1);
                    $(function() {
					$( ".ui-sortable" ).sortable({
						placeholder: "ui-state-highlight"
					});
				});
			}
         });

 }

 /*--------------------------------------- auto fill cycle select box --------------------------------------------------------  */
    function auto_load(){
	   $.ajax({
		type : "POST",
		url :"<?php echo site_url('admin_sup_mastersales_stageController/get_cycle_name'); ?>" ,
		dataType : 'json',
		cache : false,
		success : function(data){
    			if(error_handler(data)){
    			    return;
    			}
                p=data;

                    $('#add_cyclename').empty();
    				$('#add_cyclename').append("<option value='-1'>--Select--</option>");
    				for(var i=0; i < data.length; i++ ){
    					$('#add_cyclename').append("<option value='"+data[i].master_cycleid+"'>"+data[i].master_cyclename +"</option>");
    				}

                //loadpage();
			}
		});
    }

 /* -----------------------------------------------------------------------------------------------------------------------------  */

 /* -------------------------------------- save stage code  ----------------------------------------------------------------------- */
    function addSave(){
			var salesStages={};
			/*if($.trim($("#add_cyclename").val())=="-1"){
				$("#add_cyclename").closest("div").find("span").text("Cycle Name is required.");
				$("#add_cyclename").focus();
				return;
			}else{
				$("#add_cyclename").closest("div").find("span").text("");
			}*/
			if($.trim($("#add_stagename").val())==""){
				$("#add_stagename").closest("div").find("span").text("Stage Name is required.");
				$("#add_stagename").focus();
				return;
			}else if(!validate_name($.trim($("#add_stagename").val()))) {
				$("#add_stagename").closest("div").find("span").text("No special characters allowed (except &, _).");
				$("#add_stagename").focus();
				return;
			}else if(!firstLetterChk($.trim($("#add_stagename").val()))) {
				$("#add_stagename").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#add_stagename").focus();
				return;
			}else{
				$("#add_stagename").closest("div").find("span").text("");
			}


			salesStages.CYCLE_NAME=$.trim($("#CYCLE_NAME li.active a").attr('id').replace("id", ""));
            //alert(salesStages.CYCLE_NAME);
			salesStages.stage_name=$.trim($("#add_stagename").val());
			salesStages.description=$.trim($("#add_stageDesc").val());
			$("#hidden1").val(JSON.stringify(salesStages));
            $('#addmodal').modal('hide');
			//$('#confirm1').modal('show');
            proceed1();
			$('input[type="text"],select').val();
		$('#confirm1 .btn.btn-primary').focus();

	}

	function proceed1(){
		var addSalesStage = $.trim($('#hidden1').val());
		$('#addmodal').modal('hide');
		$('#confirm1').modal('hide');
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sup_mastersales_stageController/post_data'); ?>",
			dataType : 'json',
			data : addSalesStage,
			cache : false,
			success : function(data){
				loaderHide();
			    cancel();
				if(error_handler(data)){
					return;
				}
                      var str= data.str;

				if(str=="0"){
				    $('#alert').modal('show');
				    $("#alert .modal-body center span").text("Stagename already defined in selected cycle.");
				}else{
                    renderSingleTable(data);
				}

			}
		});
	}

 /* --------------------------------------------------------------------------------------------------------------------------------------- */

 /* ----------------------------------------------- edit stage and description code --------------------------------------------------------- */

 /* ----------------------------------- on select of each table row fill cycle, stage and description */
    function selrow(obj){
			$("#edit_stagename").val(obj[0].stage_name);
			$("#edit_stagedesc").val(obj[0].remarks);
			$("#edit_stageid").val(obj[0].stage_id);
			$("#edit_cycleid").val(obj[0].CYCLE_ID);
	}
 /* ---------------------------------------------------------------------------------------------------------------------------- */
    function editsave(){
			if($.trim($("#edit_stagename").val())==""){
				$("#edit_stagename").closest("div").find("span").text("Stage Name is required.");
				$("#edit_stagename").focus();
				return;
			}else if(!validate_name($.trim($("#edit_stagename").val()))) {
				$("#edit_stagename").closest("div").find("span").text("No special characters allowed (except &, _).");
				$("#edit_stagename").focus();
				return;
			}else if(!firstLetterChk($.trim($("#edit_stagename").val()))) {
				$("#edit_stagename").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#edit_stagename").focus();
				return;
			}else{
				$("#edit_stagename").closest("div").find("span").text("");
			}
			var stageobj={};
			stageobj.stage_name= $.trim($("#edit_stagename").val());
			stageobj.description= $.trim($("#edit_stagedesc").val());
			stageobj.stage_id = $("#edit_stageid").val();
			stageobj.edit_cycleid = $("#edit_cycleid").val();
            var active_cycle =$("#edit_cycleid").val();
            $("#active_cycle").text(active_cycle);
			$("#hidden").val(JSON.stringify(stageobj));
			//$('#confirm').modal('show');
			$('#editmodal').modal('hide');
            proceed();
			$('#confirm .btn.btn-primary').focus();
	}

    function proceed(){
			var stageobj = $.trim($("#hidden").val());
			$('#confirm').modal('hide');
			$('#editmodal').modal('hide');

            var str="";
			loaderShow();

			$.ajax({
				    type : "POST",
					url : "<?php echo site_url('admin_sup_mastersales_stageController/update_data'); ?>",
					dataType : 'json',
					data : stageobj,
					cache : false,
					success : function(data){
                            loaderHide();
        					cancel();
        					if(error_handler(data)){
        						return;
        					}
        					str= data.str;

        					if(str=="0"){
        					    $('#alert').modal('show');
        						$("#alert .modal-body center span").text("Stagename already defined in selected cycle.");
        					}else{
                                renderSingleTable(data);
        					}
				    }
			});
	}
 /* -------------------------------------------------------------------------------------------------------------------------------------------- */

 /* ------------------------------------------------- save the row order of stages ----------------------------------------------------------------- */
    function saveorder(cycleid){
          var selectedLanguage =[];
          var rowOrder=[];
          $("#"+cycleid+" tbody tr").each(function(){
                selectedLanguage.push({"roworder":$.trim($(this).attr("id"))});
          });
          var addObj={};
          var hid_cycleid=cycleid;
          addObj.orderselected=selectedLanguage;
          addObj.hid_cycleid=hid_cycleid;
		  loaderShow();
            $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sup_mastersales_stageController/update_roworder'); ?>",
			dataType : 'json',
			data : JSON.stringify(addObj),
			cache : false,
			success : function(data){
				if(error_handler(data)){
							return;
						}

				loaderHide();

                cancel();
                var row1="";
				row1 += '<table class="table"><thead>';
          		row1 += "<tr><th width='20%'>Sl No</th><th width='20%'>Stage Name</th><th width='20%'>Description</th><th width='20%'>Edit</th></tr></thead><tbody class='ui-sortable'>";
          		for(var k=0; k < data.records[0].stagedata.length; k++ ){
					var remarks = "";
					if(data.records[0].stagedata[k].remarks == null ){
						remarks = "";
					}else{
						remarks = data.records[0].stagedata[k].remarks;
					}

					var jsondata=JSON.stringify([{"CYCLE_ID":data.records[0].CYCLE_ID,"stage_name":data.records[0].stagedata[k].stage_name,"stage_id":data.records[0].stagedata[k].stage_id,"remarks":remarks}])
					row1 += "<tr id='" + data.records[0].stagedata[k].id + "' class=class='ui-sortable-handle'><td>" + (k+1) + "</td><td>" + data.records[0].stagedata[k].stage_name + "</td><td>" + remarks + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+jsondata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
				}
                row1 += "</tbody></table>";
			    $("#"+data.records[0].CYCLE_ID).html("").html(row1);

          		$("#"+data.records[0].CYCLE_ID).find("#createDuplicateElm, #saveorderElm").remove();
          		$("#"+data.records[0].CYCLE_ID).append("<center id='saveorderElm'><button type='button' class='btn btn-primary' onclick='saveorder(\""+data.records[0].CYCLE_ID+"\")'>Save Stage Order</button></center>");
          		$("body").removeAttr("style");
                  $(function() {
          					$( ".ui-sortable" ).sortable({
          						placeholder: "ui-state-highlight"
          					});
          		});

				$('#alert').modal('show');
				$("#alert .modal-body center span").text("Stages Order Saved Successfully");
			}
       });
    }

     /* --------------------------------------------------------------------------------------------------------------- */
        var to;
        function createDuplicate(cycleId){
			$("#duplicatesycle").modal('show');
			var totitle= $("#id"+cycleId).text();
			var fromtitle= $("#edit_cycle option:selected").text();
			$("#duplicatesycle .modal-title").text("To "+totitle+" From "+fromtitle );
			$("#duplicatesycle .modal-title").find('input [type=hidden]').remove();
			$("#duplicatesycle .modal-title").append('<input type="hidden" id="hidval" value="'+cycleId+'"/>');
            to=cycleId;

			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_sup_mastersales_stageController/get_cycle'); ?>",
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
						return;
					}
					var select = $("#edit_cycle"), options = "<option value=''>Choose Cycle</option>";
					select.empty();
					for(var i=0;i<data.length; i++){
						if(cycleId != data[i].CYCLE_ID ){
							options += "<option value='"+data[i].CYCLE_ID+"'>"+ data[i].CYCLE_NAME +"</option>";
						}
					}
					select.append(options);
					$("#edit_cycle").change(function(){
						if($(this).val() !="" ){
							var fromtitle= $("#edit_cycle option:selected").text();
							$("#duplicatesycle .modal-title").text("To "+totitle+" From "+fromtitle );
						}else{
							var fromtitle= "";
							$("#duplicatesycle .modal-title").text("To "+totitle+" From "+fromtitle );
						}

					})
				}
			});
		}

        function duplicateSubmit(){
			$("#duplicatesycle .modal-title").find('input [type=hidden]').val();
			$("#edit_cycle").val();
			if($("#edit_cycle").val() == ""){
				$("#edit_cycle").closest("div").find("span").text("Cycle Name is required.");
				$("#edit_cycle").focus();
				return;
			}else{
				$("#edit_cycle").closest("div").find("span").text("");
			}
			var addObj = {};
			addObj.from = $("#edit_cycle").val();

				addObj.to =to;
				loaderShow();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_sup_mastersales_stageController/create_duplicate'); ?>",
				data : JSON.stringify(addObj),
				dataType : 'json',
				cache : false,
				success : function(data){
					loaderHide();
					cancel();
					if(error_handler(data)){
						return;
					}
					renderSingleTable(data);
				}
            });
		}

        	function renderSingleTable(data){
            		var row1="";

                    row1 += '<table class="table"><thead>';
            		row1 += "<tr><th width='20%'>Sl No</th><th width='20%'>Stage Name</th><th width='20%'>Description</th><th width='20%'>Edit</th></tr></thead><tbody class='ui-sortable'>";
            		for(var k=0; k < data.records[0].stagedata.length; k++ ){
							var remarks = "";
							if(data.records[0].stagedata[k].remarks == null ){
								remarks = "";
							}else{
								remarks = data.records[0].stagedata[k].remarks;
							}

							var jsondata=JSON.stringify([{"CYCLE_ID":data.records[0].CYCLE_ID,"stage_name":data.records[0].stagedata[k].stage_name,"stage_id":data.records[0].stagedata[k].stage_id,"remarks":remarks}])
							row1 += "<tr id='" + data.records[0].stagedata[k].id + "' class=class='ui-sortable-handle'><td>" + (k+1) + "</td><td>" + data.records[0].stagedata[k].stage_name + "</td><td>" + remarks + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+jsondata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
						}
                    row1 += "</tbody></table>";
					$("#"+data.records[0].CYCLE_ID).html("").html(row1);
            		$("#"+data.records[0].CYCLE_ID).find("#createDuplicateElm, #saveorderElm").remove();
            		$("#"+data.records[0].CYCLE_ID).append("<center id='saveorderElm'><button type='button' class='btn btn-primary' onclick='saveorder(\""+data.records[0].CYCLE_ID+"\")'>Save Stage Order</button></center>");
            		$("body").removeAttr("style");
                    $(function() {
            					$( ".ui-sortable" ).sortable({
            						placeholder: "ui-state-highlight"
            					});
            		});
	        }

/* -------------------------------------------------------------------------------------------------------------------------------------- */
    function cancel(){
		$('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first').val());
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
        auto_load();
		$('.error-alert').text('');
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
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Support Master Stage Flowchart"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Master_Stage_Flowchart', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Support Master Stage Flowchart</h2>
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<a href="#" onClick="compose()" class="addPlus">
							<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
						</a>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
			</div>
			<ul class="nav nav-tabs">
				<li>
					<a href="<?php echo site_url('admin_sup_mastersales_cycleController'); ?>">Support Master Cycle</a>
				</li>
				<li class="active">
					<a href="<?php echo site_url('admin_sup_mastersales_stageController'); ?>">Support Master Stage Flowchart</a>
				</li>
			</ul>

			<div class="tab-content">
				<div id="main_data_content">
					<div class="col-xs-3 verticle-tab">
						<ul class="nav nav-tabs tabs-left" id="CYCLE_NAME">
						</ul>
					</div>
					<div class="col-xs-9 tab-col" >
						<br>
						<div class="tab-content" id="stagedata">
						</div>
					</div>
				</div>
            </div>
            <div class="none" id="no_data_section"></div>
            <div id="editmodal" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="editpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Edit Support Master Stages</h4>
							</div>
                            <div class="modal-body">
								<div class="row">
									<div class="col-md-3 col-sm-3 col-xs-3">
										<label for="edit_stagename">Stage Name*</label>
									</div>
									<div class="col-md-9 col-sm-9 col-xs-9">
										<input type="hidden" id="edit_stageid"/>
										<input type="hidden" id="edit_cycleid"/>
										<input type="hidden" id="hid_cycleid "/>
										<input type="text" class="form-control closeinput" name="adminstagename" id="edit_stagename"/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3 col-sm-3 col-xs-3">
										<label for="edit_stagedesc">Stage Description</label>
									</div>
									<div class="col-md-9 col-sm-9 col-xs-9">
										<input type="text" class="form-control closeinput"  id="edit_stagedesc"/>
										<span class="error-alert"></span>
									</div>
								</div>

							</div>
							<div class="modal-footer">
                                <button type="button" class="btn btn-primary" onclick="editsave()" >Save</button>
								<button type="button" class="btn btn-default" onclick="cancel()">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="confirm" class="modal fade" data-backdrop="static">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<form class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <h4 class="modal-title">Alert <span class="glyphicon glyphicon-alert"></span></h4>
							</div>
                            <div class="modal-body">
								<input type="hidden" id="hidden">
								<p>Do you want to proceed..!</p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-primary" autofocus tabindex="1" onclick="proceed()">Proceed</button>
								<button type="button" class="btn btn-default" tabindex="2" onclick="cancel()">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="confirm1" class="modal fade" data-backdrop="static">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<form class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <h4 class="modal-title">Alert <span class="glyphicon glyphicon-alert"></span></h4>
							</div>
                            <div class="modal-body">
								<input type="hidden" id="hidden1">
								<p>Do you want to proceed...!</p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-primary" autofocus tabindex="1" onclick="proceed1()">Proceed</button>
								<button type="button" class="btn btn-default" tabindex="2" onclick="cancel()">Cancel</button>
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
								 <h4 class="modal-title">Add Support Master Stages</h4>
							</div>
							<div class="modal-body">

								<div class="row">
									<div class="col-md-3 col-sm-3 col-xs-3">
										<label for="add_stagename">Stage Name*</label>
									</div>
									<div class="col-md-9 col-sm-9 col-xs-9">
										<input type="text" class="form-control closeinput" name="adminstagename" id="add_stagename"/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3 col-sm-3 col-xs-3">
										<label for="add_stageDesc">Stage Description</label>
									</div>
									<div class="col-md-9 col-sm-9 col-xs-9">
										<input type="text" class="form-control closeinput" name="adminstagename" id="add_stageDesc"/>
										<span class="error-alert"></span>
									</div>
								</div>
                                <div class="row" style="display: none">
									<div class="col-md-3 col-sm-3 col-xs-3">
										<label for="add_cyclename">Cycle Name*</label>
									</div>
									<div class="col-md-9 col-sm-9 col-xs-9">
										<select class="form-control closeinput" name="admincyclename" id="add_cyclename">
										</select>
										<span class="error-alert"></span>
									</div>
								</div>

							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-primary" onclick="addSave()" >Save</button>
								<button type="button" class="btn btn-default" onclick="cancel()">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
            <div id="duplicatesycle" class="modal fade" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editpopup" class="form" action="#" method="post" name="adminClient">
                            <div class="modal-header">
                                 <span class="close" onclick="cancel()">x</span>
                                 <h4 class="modal-title"></h4>
                            </div>
							<div class="modal-body">
								<div class="row">
								  <div class="col-md-3">
									  <label for="edit_cycle">Support Master Cycle*</label>
								  </div>
								  <div class="col-md-9">
										<select class="form-control" id="edit_cycle">
										</select>
										<span class="error-alert"></span>
								  </div>
								</div>
							</div>
							<div class="modal-footer">
                                <input type="button" class="btn" onclick="duplicateSubmit()" value="Save">
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
		</div>
		<?php require 'footer.php' ?>
	</body>
</html>
