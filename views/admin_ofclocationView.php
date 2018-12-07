<!DOCTYPE html>
<html lang="en">
	<head>	
	<?php $this->load->view('scriptfiles'); ?>
	<style>

</style>
<script>
    var testData = [];
    $(document).ready(function(){
        loaderShow();
      	$.ajax({
      		type : "post",
      		url : "<?php echo site_url('admin_office_location/get_lead_source');?>",
      		dataType : "json",
      		cache : false,
      		success : function(data){
				if(error_handler(data)){
						return;
					}
      			testData = JSON.parse(JSON.stringify(data));
      			lead_source();
      		}
      	});
    });
    function lead_source(){
		org_chart = $('#orgChart').orgChart({
		 data: testData,
		 showAddControl: true,
		 showDeleteControl: false,
		 allowEdit: false,
		 showEditControl: true
		});

        $(".node").each(function(){
			if(!$(this).hasClass("child0")){
				$(this).find('.addArrt').hide();
			}
		});
        loaderHide();
	}
	function cancel(){
        $('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first').val());
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
		$('.error-alert').text('');
	}
	function editsource(source){
		$('#editmodal').modal('show');
		$('#edit_parent').html(source.parent_name);
        $('#parent_id').val(source.parent);
		$('#edit_lead').val(source.name);
		$('#hierarchy_id').val(source.id);
		$('#hid').val(source.hid);
		$('.edit_lead_title').text(source.name);
	}
	function edit_save(){
	    loaderShow();
		if($.trim($("#edit_lead").val())==""){
			$("#edit_lead").closest("div").find("span").text("Office Location is required.");
			$("#edit_lead").focus();
            loaderHide();
			return;
		}else if(!validate_location($.trim($("#edit_lead").val()))) {
			$("#edit_lead").closest("div").find("span").text("No special characters allowed (except &).");
			$("#edit_lead").focus();
            loaderHide();
			return;
		}else if(!firstLetterChk($.trim($("#edit_lead").val()))) {
			$("#edit_lead").closest("div").find("span").text("First letter should not be Numeric or Special character.");
			$("#edit_lead").focus();
            loaderHide();
			return;
		}else{
			$("#edit_lead").closest("div").find("span").text("");
		}
		
		var editdata={};
		editdata.hierarchy_id = $.trim($('#hierarchy_id').val());
		editdata.hid = $.trim($('#hid').val());
		editdata.parent_id = $.trim($('#parent_id').val());
		editdata.node = $.trim($('#edit_lead').val());
		
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_office_location/update_source')?>",
			data : JSON.stringify(editdata),
			dataType : 'json',
			cache : false,
			success : function(data){				
                loaderHide();
				if(error_handler(data)){
						return;
					}
                if(data=='0'){
                    $("#alert").modal("show");
					$("#alert center span").text("Office Location Already Defined");
					return;
                }else{
					cancel();
                    testData = [];
				    testData = JSON.parse(JSON.stringify(data));
				    lead_source();
                }
			}
		});
	}
	function addsource(source){
		$('#addsub').modal('show');
		$('.add_sub_text').html(source.name);
		$('#parent_id').val(source.id);
	}
	function add_source(){
	    loaderShow();
		if($.trim($("#add_lead_sub").val())==""){
			$("#add_lead_sub").closest("div").find("span").text("Office Location is required.");
			$("#add_lead_sub").focus();
            loaderHide();
			return;
		}else if(!validate_location($.trim($("#add_lead_sub").val()))) {
			$("#add_lead_sub").closest("div").find("span").text("No special characters allowed (except &).");
			$("#add_lead_sub").focus();
            loaderHide();
			return;
		}else if(!firstLetterChk($.trim($("#add_lead_sub").val()))) {
			$("#add_lead_sub").closest("div").find("span").text("First letter should not be Numeric or Special character.");
			$("#add_lead_sub").focus();
            loaderHide();
			return;
		}else{
			$("#add_lead_sub").closest("div").find("span").text("");
		}
		
		var adddata={};
		adddata.parent_id = $.trim($('#parent_id').val());
		adddata.sourcename = $.trim($('#add_lead_sub').val());
		adddata.parent_name = $.trim($('.add_sub_text').html());
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_office_location/post_lead_data')?>",
			data : JSON.stringify(adddata),
			dataType : 'json',
			cache : false,
			success : function(data){
                loaderHide();
				if(error_handler(data)){
						return;
					}
                if(data=='0'){
                    $("#alert").modal("show");
					$("#alert center span").text("Office Location Already Defined");
					return;
                }else{
					cancel();	
    				testData = [];
    				testData = JSON.parse(JSON.stringify(data));
    				lead_source();
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
							<div>		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Office Location"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Office_Location', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Office Location</h2>	
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						<table class="pull-right zoom">
							<tr>
								<td>
									<span class="glyphicon glyphicon-zoom-in" data-toggle="tooltip" data-placement="bottom" title="Zoom in"></span>
								</td>
								<td>
								&nbsp;&nbsp;
								</td>
								<td>
									<span class="glyphicon glyphicon-zoom-out" data-toggle="tooltip" data-placement="bottom" title="Zoom out"></span>
								</td>
							</tr>						
						</table>
					</div>
					<div style="clear:both"></div>
				</div>
				<div id="orgChartContainer">
					<div id="orgChart"></div>
				</div>
			</div>
			<div id="editmodal" class="modal fade" data-backdrop="static" >
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="editpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								<span class="close" onclick="cancel()">x</span>
								<h4 class="modal-title">Edit <span class="edit_lead_title"></span> Node</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="edit_parent">Parent Source</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<label id="edit_parent" style="font-weight:bold!important;"></label> 
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="edit_lead">Office Location</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<input type="text" class="form-control closeinput" name="edit_lead" id="edit_lead"/>
										<span class="error-alert"></span>
										<input type="hidden" class="form-control closeinput" name="hierarchy_id" id="hierarchy_id"/>
										<input type="hidden" class="form-control closeinput" name="hid" id="hid"/>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" class="btn" onclick="edit_save()" value="Save" />
								<input type="button" class="btn" onclick="cancel()" value="Cancel" />
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="addsub" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								  <h4 class="modal-title">Add <span class="add_sub_text"></span> Node</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="add_sub">Parent Source</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<label class="add_sub_text" style="font-weight:bold!important;"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="add_lead_sub">Office Location</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<input type="text" class="form-control closeinput" name="add_lead_sub" id="add_lead_sub"/>
										<span class="error-alert"></span>
										<input type="hidden" id="parent_id" name="parent_id"/>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" class="btn" onclick="add_source()" value="Save">
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
		<?php $this->load->view('footer'); ?>

	</body>
</html>
