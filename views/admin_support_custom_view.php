<!DOCTYPE html>
<html lang="en">
	<head>
     <style>
     #editmodal .glyphicon-plus-sign {
    border: 1px solid;
    padding: 8px;
    border-radius: 0 5px 5px 0px;
    position: absolute;
    right: 0;
    background: #fff;
}
</style>
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
    			url : "<?php echo site_url('admin_support_customController/get_buyerperson'); ?>",
    			dataType : 'json',
    			cache : false,
    			success : function(data){
    				loaderHide();
    				if(error_handler(data)){
    					return;
    				}
    				loadpage(data);
    				var count = data.length;
    				var strcount = "sup_cust"+(count+1);
    				$('#adddbuyerpCount').val(strcount);
    			}
    		});
        }
		function loadpage(data){
			$('#tablebody').empty();
			var row = "";
            var j=1;

                for(var i1=0; i1 < data.length; i1++){
				    var rowdata = JSON.stringify(data[i1]);

                        if(data[i1].listvalues == '-' || data[i1].listvalues == ""){
                           var str="style='display: none'";
                        }
                        else{
                            var str="";
                        }
                       row += "<tr>"+
                                "<td>" + (j) + "</td>"+
                                "<td>" + data[i1].support_attribute_name + "</td>"+
                                "<td>" + data[i1].support_attribute_type + "</td>"+
                                "<td>" + data[i1].listvalues + "</td>"+
                                "<td>"+
                                    "<a data-toggle='modal' href='#editmodal' "+str+" onclick='selrow("+rowdata+")'>"+
                                        "<span class='glyphicon glyphicon-pencil'></span>"+
                                    "</a>"+
                                "</td>"+
                            "</tr>";
                    j++;
                }
                /* code for sandbox */
                if(data.length >= parseInt(recordcount) && parseInt(recordcount)!= 0){
                    $('#supcustbtn').hide();
                }else{
                    $('#supcustbtn').show();
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

		function cancel(){
			$('.modal').modal('hide');
			$('.modal input[type="text"], textarea').val('');
			$('.modal select').val('');
			$('.modal input[type="radio"]').prop('checked', false);
			$('.modal input[type="checkbox"]').prop('checked', false);
			$('.error-alert').text('');
		}
		function compose(){
			$("#add_activity").focus();
            $("#addmodal .optionSetting").hide();
		}
		function add(){
		    var attributesName = $("#add_activity");

			if($.trim(attributesName.val())==""){
				attributesName.closest("div").find("span").text("Custom Field Name is required.");
				attributesName.focus();
				return;
			}else if(!validate_noSpCh($.trim(attributesName.val()))){
			   attributesName.closest("div").find("span").text("No special characters allowed.");
			   attributesName.focus();
				return;
			}else if(!firstLetterChk($.trim(attributesName.val()))){
				attributesName.closest("div").find("span").text("First letter should not be Numeric or Special character.");
				attributesName.focus();
				return;
			}else if($.trim($("#supportAttributesType").val())==""){
				$("#supportAttributesType").closest("div").find("span").text("Support Attribute Type is required.");
				$("#supportAttributesType").focus();
				return;
			}else{
				attributesName.closest("div").find("span").text("");
				var addObj={};
				addObj.buyerp_name = $.trim(attributesName.val());
                addObj.buyerp_count = $.trim($("#adddbuyerpCount").val());
                addObj.supportAttributesType = $.trim($("#supportAttributesType").val());
                addObj.optionList = "";
                var optionList1 = [];
              	$("#typelistView li").each(function(){
        			optionList1.push($(this).find('span').text().trim())
        		});
                if(optionList1.length == 0 && $.trim($("#supportAttributesType").val()) == "listBox"){
					$("#add_cur").siblings(".error-alert").text("Please Add the List Values");
					return;
				}
                addObj.optionList = optionList1.toString();
                console.log(addObj);
                //return;
                $("body").append('<div class="mask custom-alert"></div><div class="alert alert-danger row custom-alert"> <div class="col-md-9 style="color:#fff"">You Cannot Edit Attribute Name once created. Are you sure,You want to continue? </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');

                $(".Ok").click(function(){
                            $(".custom-alert").remove();
				            loaderShow();
              				$('#tablebody').parent("table").dataTable().fnDestroy();
              				$.ajax({
              					type : "POST",
              					url : "<?php echo site_url('admin_support_customController/post_data'); ?>",
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
              							$("#add_activity").focus();
              							$('#tablebody').parent("table").DataTable();
              							return;
              						}else{
              							cancel();
              							loadpage(data);
              							var count = data.length;
              							var strcount = "sup_cust"+(count+1);
              							$('#adddbuyerpCount').val(strcount);
              						}
              					}
              				});
				});
				$(".notOk").click(function(){
							$(".custom-alert").remove();
				});
			}
		}
		function selrow(obj){
			$("#support_attribute_id").val(obj.support_attribute_id);
			$(".error-alert").text("");
            $("#typelistViewEdit").empty();
            obj.listvalues.split(',').forEach(function( key, val){
                 $("#typelistViewEdit").append('<li><span>'+ key +'</span></li>');
            })

		}
		function edit_save(){

				var addObj={};
                var optionList1 = [];
              	$("#typelistViewEdit li").each(function(){
        			optionList1.push($(this).find('span').text().trim())
        		});
				addObj.optionList = optionList1.toString();
				addObj.support_attribute_id = $.trim($("#support_attribute_id").val());
                console.log(addObj);
                //return;
				loaderShow();
				$('#tablebody').parent("table").dataTable().fnDestroy();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_support_customController/update_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						loaderHide();
						if(error_handler(data)){
							return;
						}
                        window.location.reload(true);
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

        function add_option_list(section){
          if(section == 'add'){
              var optionNameElm = $("#add_cur");
              var typelistView1 = $("#typelistView");
              var typelistView = "typelistView";
          }else{
              var optionNameElm = $("#editList");
              var typelistView1 = $("#typelistViewEdit");
              var typelistView = "typelistViewEdit";
          }




        	var optionName = $.trim(optionNameElm.val());
        	$(".error-alert").text("")

        	if(optionName ==""){
                optionNameElm.closest("div").find("span").text("List Data is required.");
        		optionNameElm.focus();
                return;
            }else{
                 optionNameElm.closest("div").find("span").text("");
            }

        	var html= 0;
        	if($("#"+typelistView+" li").length <= 0){
        		typelistView1.append('<li><span>'+ optionName +'</span><a title="Delete" class="pull-right glyphicon glyphicon-remove-circle"></a></li>');
        		typelistView1.siblings(".error-alert").text("");
                /* code for sandbox */
                if(parseInt(recordcount)!= 0){
                    $('.curbtn1').hide();
                }else{
                    $('.curbtn1').show();
                }
        	}else{
        		$("#"+typelistView+" li").each(function(){
        			if($(this).find('span').text().trim() == optionName){
        				html = 1;
        			}
        		});

        		if(html == 1){
        			typelistView1.siblings(".error-alert").text("Duplicate List Data.");
        			optionNameElm.focus();
        			return;
        		}else{
        			typelistView1.siblings(".error-alert").text("");
        			typelistView1.append('<li><span>'+ optionName +'</span> <a title="Delete" class="pull-right glyphicon glyphicon-remove-circle"></a></li>');
        		}
        	}
        	optionNameElm.val("");
        	$("#"+typelistView+" li a").each(function(){
        		$(this).click(function(){
        			$(this).closest('li').remove();
                    /* code for sandbox */
                    if($("#"+typelistView+" li").length == 0){
                        $('.curbtn1').show();
                    }
        		})
        	});
        }
        function optionType(elm){
          var typelist = $('#addmodal .optionSetting');
          typelist.find(".error-alert").text("");
          typelist.find("input[type=text]").val("");
          typelist.find("#typelistView").empty();
          if($.trim($(elm).val()) == 'listBox'){
                typelist.show();
          }else{
                typelist.hide();
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
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Support Attributes List"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Support_Attributes', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Support Attributes List</h2>
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<!--<a href="#modal_upload" data-toggle="modal" class="addExcel" >
								<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a>-->
							<a href="#addmodal" class="addPlus" id='supcustbtn'  data-toggle="modal" onclick="compose()">
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
							<th class="table_header"width="30%">Support Attributes</th>
							<th class="table_header"width="20%">Support Attribute Type</th>
							<th class="table_header"width="40%"></th>
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
								 <h4 class="modal-title">Edit Support Attributes </h4>
							</div>
							<div class="modal-body">
                                    <div class="row">
                                    <input type='hidden' id="support_attribute_id"/>
    									<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
    										<label for="editList">List Data*</label>
    									</div>
    									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <input type="text" class="form-control closeinput" name="adminContactDept" id="editList"/>
    										<span class="error-alert"></span>
    									</div>
    									<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
    										<a title="Add option" href="#"  class="curbtn1 glyphicon glyphicon-plus-sign" onclick="add_option_list('edit')"></a>
    									</div>
    								</div>
                                    <div class="row">
    									<div class="col-md-12">
    										<ol id="typelistViewEdit"></ol>
    										<p class="error-alert text-center"></p>
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
						<form class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Add Support Attributes</h4>
							</div>
							<div class="modal-body">
									<div class="row">
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
											<label for="add_activity">Support Attributes Name*</label>
										</div>
										<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
											<input type="text" class="form-control closeinput" name="adminContactDept" id="add_activity"/>
											<span class="error-alert"></span>
                                            <input type="hidden" name="adddbuyerpCount" id="adddbuyerpCount"/>
										</div>
									</div>
                                    <div class="row">
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
											<label for="supportAttributesType">Support Attributes Type*</label>
										</div>
										<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
											<select class="form-control" id="supportAttributesType" onchange="optionType(this)">
                                                <option value="">Select</option>
                                                <option value="textBox">Text Box</option>
                                                <option value="date">Date</option>
                                                <option value="time">Time</option>
                                                <option value="listBox">List Box</option>
                                            </select>
											<span class="error-alert"></span>
										</div>
									</div>
                                    <div class="row optionSetting none">
    									<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
    										<label for="add_cur">List Data*</label>
    									</div>
    									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
    										<!--<select name="adminContactDept" class="form-control" id="add_cur"></select>-->
                                            <input type="text" class="form-control closeinput" name="adminContactDept" id="add_cur"/>
    										<span class="error-alert"></span>
    									</div>
    									<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
    										<a title="Add option" href="#" class="curbtn1 glyphicon glyphicon-plus-sign" onclick="add_option_list('add')"></a>
    									</div>
    								</div>
                                    <div class="row optionSetting none">
    									<div class="col-md-12">
    										<ol id="typelistView"></ol>
    										<p class="error-alert text-center"></p>
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
