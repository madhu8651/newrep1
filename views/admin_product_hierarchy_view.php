<!DOCTYPE html>
<html lang="en">
	<head>	
	<?php $this->load->view('scriptfiles'); ?>
	<style>
		#curr_list li{
			list-style-type: none;
		}
		#curr_list{
			padding-left:0px;
		}
		.inputtxt input {
			width: 70px;
			float: right;
			text-align: center;
			border: none;
		}
		.inputtxt input:focus{
			outline:none;
			border-bottom:1px solid #337ab7;
		}
		.currencySection td{
			    vertical-align: middle;
		}
		.currencySection td .glyphicon.glyphicon-plus-sign{
			border: 1px solid;
			padding: 8px;
			border-radius: 5px;
		}
		.currencySection td input,.currencySection td select{
			margin-bottom:0px;
		}
	</style>
<script>
    var testData = [];
    $(document).ready(function(){
		    /* code for sandbox */
		    var url1= window.location.href;
		    var fileNameIndex1 = url1.lastIndexOf("/") + 1;
		    var filename1 = url1.substr(fileNameIndex1);
            sandbox(filename1);
			loadpage();
	});

    function loadpage(){
        loaderShow();
    	$.ajax({
    		type : "post",
    		url : "<?php echo site_url('admin_product_hierarchyController/get_lead_source');?>",
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
    }
    
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
		})
        loaderHide();
	}
	function cancel(){
        $('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first').val());
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
		$("#curr_list li").remove();
        $('.error-alert').text("");
	}
	
	function editsource(source){
		$('#editmodal').modal('show');
		$('#edit_parent').html(source.parent_name);
        $('#parent_id').val(source.parent);
		$('.edit_lead_title').text(source.name);
		$('#edit_lead').val(source.name);
		$('#hierarchy_id').val(source.id);
		$('#hid').val(source.hid);
	}

	function edit_save(){
	    
		if($.trim($("#edit_lead").val())==""){
			$("#edit_lead").closest("div").find("span").text("Product is required.");
			$("#edit_lead").focus();
			return;
		}else if(!validate_name($.trim($("#edit_lead").val()))) {
			$("#edit_lead").closest("div").find("span").text("No special characters allowed (except &, _,-,.).");
			return;
		}else if(!firstLetterChk($.trim($("#edit_lead").val()))) {
			$("#edit_lead").closest("div").find("span").text("First letter should not be Numeric or Special character.");
			$("#edit_lead").focus();
			return;
		}else{
			$("#edit_lead").closest("div").find("span").text("");
		}
		var editSource={};
		editSource.hierarchy_id = $.trim($('#hierarchy_id').val());
		editSource.hid = $.trim($('#hid').val());
		editSource.parent_id = $.trim($('#parent_id').val());
		editSource.node = $.trim($('#edit_lead').val());
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_product_hierarchyController/update_source')?>",
			data : JSON.stringify(editSource),
			dataType : 'json',
			cache : false,
			success : function(data){				
                loaderHide();
				if(error_handler(data)){
					return;
				}
                if(data=='0'){
					$("#alert").modal("show");
					$("#alert center span").text("Product Already Defined");
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
	    
		if($.trim($("#add_lead_sub").val())==""){
			$("#add_lead_sub").closest("div").find("span").text("Product is required.");
			$("#add_lead_sub").focus();            
			return;
		}else if(!validate_name($.trim($("#add_lead_sub").val()))) {
			$("#add_lead_sub").closest("div").find("span").text("No special characters allowed (except &, _,-,.).");
			return;
		}else if(!firstLetterChk($.trim($("#add_lead_sub").val()))) {
			$("#add_lead_sub").closest("div").find("span").text("First letter should not be Numeric or Special character.");
			$("#add_lead_sub").focus();
			return;
		}else{
			$("#add_lead_sub").closest("div").find("span").text("");
		}
		var addSource={};
		addSource.parent_id=$.trim($('#parent_id').val());
		addSource.sourcename=$.trim($('#add_lead_sub').val());
		addSource.parent_name=$('.add_sub_text').html();
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_product_hierarchyController/post_lead_data')?>",
			data : JSON.stringify(addSource),
			dataType : 'json',
			cache : false,
			success : function(data){				
                loaderHide();
				if(error_handler(data)){
					return;
				}
                if(data=='0'){
					$("#alert").modal("show");
					$("#alert center span").text("Product Already Defined");
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


    function load_currency(value){ /* // on load fill currency */
		$.ajax({
			type: "POST",
			url:"<?php echo site_url('admin_product_hierarchyController/get_currency')?>",
			dataType:'json',
			success: function(data){
				if(error_handler(data)){
					return;
				}
				var select = $("#add_cat"), options = "<option value=''>Select Currency</option>";
				select.empty();
				for(var i=0;i<data.length; i++)
				{
					options += "<option value='"+data[i].currency_id+"'>"+ data[i].currency_name +"</option>";
				}
				select.append(options);
				$('#add_cat').val(value);
			 }
		});
	}

	function addArrtbute(nodeid, name){
	    loaderShow();
		$("#addArrtbutePop").find("#title_name").text(name);
	    addObj={};
        addObj.nodeid=nodeid;
        var value="";
        load_currency(value);
        $('#hid_nodeid').val(nodeid);
		$('#addArrtbutePop').modal('show');
         $.ajax({
			type: "POST",
			url:"<?php echo site_url('admin_product_hierarchyController/get_attr_data')?>",
			dataType:'json',
            data : JSON.stringify(addObj),
			success: function(data){
				if(error_handler(data)){
					return;
				}
                //var data=JSON.parse(data[0].json);
				$("#budget").attr("disabled","disabled");
				$("#add_cat").change(function(){
					if($(this).val() != ""){
						$("#budget").removeAttr("disabled");
						$("#budget").closest("div").find("span").text("");
						$("#add_cat").closest("div").find("span").text("");
					}else{
						$("#budget").attr("disabled","disabled");
						$("#budget").val("");
					}
				})
					$(function() {
						var regExp = /[a-z]/i;
						$('#budget').on('keydown keyup', function(e) {
							var value = String.fromCharCode(e.which) || e.key;
							if(value == "a" || value == "b"|| value == "c"|| value == "d" || value == "e" || value == "f"|| value == "g" || value == "h" || value == "i"|| value == "n"){

							}else{
								if (regExp.test(value)) {
									e.preventDefault();
									return false;
								}
							}
							if (!regex.test($('#budget').val())){
								$("#budget").closest("div").find("span").text("Decimal value is required.");
								return;
							}else{
								$("#budget").closest("div").find("span").text("");
							}
						});
					});
				if(data==0){
					$('#addArrtbutePop').modal('show');
					$('#toDate1 input[type=text]').prop( "disabled", true );
				}else{
					$("#prodCode").val(data[0].product_code.replace( new RegExp("\\-xxxxx-","gm"),"\\").replace( new RegExp("\\-xxxx-","gm"),"'"))
					$("#prodDescip").val(data[0].remarks.replace( new RegExp("\\-xxxxx-","gm"),"\\").replace( new RegExp("\\-xxxx-","gm"),"'"));
                    if(data[0].hasOwnProperty('curdata')) {
                        for(i=0;i < data[0].curdata.length; i++){
						    $("#curr_list").append("<li id="+data[0].curdata[i].currency_id+"><a class='glyphicon glyphicon-remove-circle' onclick='del(\""+data[0].curdata[i].currency_id+"\", \"saved\")'></a>&nbsp;&nbsp;"+data[0].curdata[i].currencyname+" ( <b id='row"+data[0].curdata[i].id+"' class='ammount' onclick='change(\""+data[0].curdata[i].currency_id+"\",\""+data[0].curdata[i].product_value+"\")'>"+data[0].curdata[i].product_value+"</b> )<i class='inputtxt'></i></li>");
					    }
                    }
				}
                loaderHide();
			}
		});
	}

	var regex  = /^\d+(?:\.\d{1,2})$/;
	function add_curr(){
		var currID = $.trim($("#add_cat").val());
		var currTxt = $.trim($("#add_cat option:selected").text());
		var currAmount = $.trim($("#budget").val());
		
		if(currID == ""){
			$("#add_cat").closest("div").find("span").text("Select Currency.");
			$("#add_cat").focus();
			return;
		}else{
			$("#add_cat").closest("div").find("span").text("");
		}
		
		if($("#add_cat").val()==""){
			$('#budget').val('0.00');
        }else{
			$("#budget").closest("div").find("span").text("");
            if (!regex.test($('#budget').val())){
    			$("#budget").closest("div").find("span").text("Decimal value is required.");
    			return;
    		}else if($.trim($('#budget').val())== ''){
    		  $("#budget").closest("div").find("span").text("Budget is required.");
    		  return;
    		}else if($.trim($('#budget').val())== '0.00'){
    		  $("#budget").closest("div").find("span").text("Budget is required.");
    		  return;
    		}else{
    		    var budgetAmunt = $.trim($('#budget').val())
				var budgetAmuntVal = $.trim(budgetAmunt).split(".");

				if(budgetAmuntVal[1] == ""){
					var bud= budgetAmuntVal[0]+'.00';
					$.trim($('#budget').val(bud))
				}else{
					$("#budget").closest("div").find("span").text("");
				}
    		}
        }
		var flg=0;
		
		if($("#curr_list li").length > 0){			
			$("#curr_list li").each(function(){		
				if($(this).attr("id") == currID){
					if($(this).css("display") == "none"){
						/* if user wants to add value to the saved -deleted Currency */
						$(this).show().removeAttr("class");
						$(this).find(".ammount").text(currAmount);
						$("#add_cat, #budget").val("");
						flg=2;
					}else{
						$("#add_cat").closest("div").find("span").text("Currency already added.");
						$("#add_cat").focus();
						flg=1;	
					}				
				}				
			})
			if( flg== 0){
				$("#curr_list").append("<li id="+currID+" ><a class='glyphicon glyphicon-remove-circle' onclick='del(\""+currID+"\",\"new\")'></a>&nbsp;&nbsp;"+currTxt+" ( <b class='ammount' onclick='change(\""+currID+"\",\""+currAmount+"\")'>"+currAmount+"</b> )<i class='inputtxt'></i></li>");
				$("#add_cat").closest("div").find("span").text("");
				$("#add_cat").val($("#add_cat option:first").val());
				$("#budget").val("").prop("disabled", true);
			}			
		}else{
			$("#curr_list").append("<li id="+currID+" ><a class='glyphicon glyphicon-remove-circle' onclick='del(\""+currID+"\",\"new\")'></a>&nbsp;&nbsp;"+currTxt+" ( <b class='ammount' onclick='change(\""+currID+"\",\""+currAmount+"\")'>"+currAmount+"</b> )<i class='inputtxt'></i></li>");			
			$("#add_cat").val($("#add_cat option:first").val());
			$("#budget").val("").prop("disabled", true);
		}
	}
	
	function del(id, status){
		if(status == "new"){
			/* removing newly added Currency */
			$("#"+id).remove();
		}
		if(status == "saved"){
			/* hiding saved Currency */
			$("#"+id).addClass("saved").hide();
		}
	}
	function ammountChange(val,id){
		var regExp = /[a-z]/i;
		/* ---------------------each field validation */
		$(function() {
			var regExp = /[a-z]/i;
			$("#"+id).on('keydown keyup', function(e) {
				var value = String.fromCharCode(e.which) || e.key;
				if (regExp.test(value)) {
					e.preventDefault();
					return false;
				}
				if (!regex.test(val)){
					$("#"+id).closest("li").find(".ammount").text("00.00");
					$("#"+id).closest("li").find("span").text("Decimal value is required.");
					return;
				}else{
					$("#"+id).closest("li").find("span").text("");
				}
			});
		});
		
		if(val==""){
			$("#"+id).val('0.00');
			$("#"+id).closest("li").find(".ammount").text("00.00");
			$("#"+id).closest("li").find(".inputtxt input").remove();			
        }else{
			$("#"+id).closest("li").find("span").text(""); 
            if (!regex.test(val)){
				$("#"+id).closest("li").find(".ammount").text("00.00");
    			$("#"+id).closest("li").find("span").text("Decimal value is required.");
				$("#"+id).closest("li").find(".inputtxt input").remove();
    			return;
    		}
			/*-----------------User can change the value to 00
			else if($.trim(val)== ''){
    		 $("#"+id).closest("li").find("span").text("Value is required.");
			  $("#"+id).closest("li").find(".inputtxt input").remove();
    		  return;
    		}else if($.trim(val)== '0.00'){
    		  $("#"+id).closest("li").find("span").text("Value is required.");
			  $("#"+id).closest("li").find(".inputtxt input").remove();
    		  return;
    		} */
			else{
    		    var budgetAmunt = $.trim(val)
				var budgetAmuntVal = $.trim(budgetAmunt).split(".");

				if(budgetAmuntVal[1] == ""){
					var bud= budgetAmuntVal[0]+'.00';
					$.trim($("#"+id).val(bud))
				}else{
					$("#curr_list").find("span").text("");
					$("#"+id).closest("li").find(".ammount").text(val);
					$("#"+id).closest("li").find(".inputtxt input").remove();
				}
    		}
			
        }
		/* ---------------	 */	
	}
	function change(currencyID,ammount ){
		$("#curr_list").find("span").text("");		
		var ammountCh = $("#"+currencyID).find(".ammount").text();		
		$("#"+currencyID).find(".inputtxt").html('<input type="text" id="editable" onfocusout="ammountChange(this.value,this.id)" value="'+ammountCh+'"><span class="error-alert"></span>');
		$("#"+currencyID).find(".inputtxt input").focus();
	}
	
	function attr_save(){
	    
		var addObj ={};
		var currIdArray ={};
		var currIdArray1 ={};
        var flg=0;
        var flg1=0;

		if($.trim($("#prodCode").val())==""){
			$("#prodCode").closest("div").find("span").text("Product Code is required.");
			$("#prodCode").focus();
			return;
		}else{
			$("#prodCode").closest("div").find("span").text("");
		}		
		/*if($("#curr_list li").length == 0){
			$("#add_cat").closest("div").find("span").text("Add atleast one Currency.");
			$("#add_cat").focus();
			return;
		}else{*/
			if($("#add_cat").val()!=""){
				if($.trim($("#budget").val())==""){
					$("#budget").closest("div").find("span").text("Currency value is required.");
					$("#budget").focus();
					return;
				}else if($.trim($("#budget").val())!=""){
					$("#add_cat").closest("div").find("span").text("Add the selected Currency.");
					return;
				}else{
					$("#add_cat").closest("div").find("span").text("");
				}				
			}else{
				$("#budget").closest("div").find("span").text("");
			}
			
			$("#add_cat").closest("div").find("span").text("");
			$("#curr_list li").each(function(){

				if($(this).attr("class") == "saved"){
					/* currIdArray1[$(this).attr('id')]= $(this).find(".ammount").text(); */
					currIdArray1[$(this).find(".ammount").attr('id').replace('row','')]= $(this).find(".ammount").text();
                    flg=1;
				}else{
					currIdArray[$(this).attr('id')]=$(this).find(".ammount").text();
                    flg1=1;
				}
				
			})
		/*}*/

		addObj.prodCode = $.trim($("#prodCode").val().replace(/[']/g, "-xxxx-").replace(/[\\]/g, "-xxxxx-"));
        if(flg==0){
           currIdArray1['empty']="no";
        }
        if(flg1==0){
           currIdArray['empty']="no";
        }
        addObj.currIdInactive = currIdArray1;
		addObj.currId = currIdArray
		/* addObj.prodDescip = $.trim($("#prodDescip").val().replace(/[']/g, "-xxxx-").replace(/[\\]/g, "-xxxxx-")); */
		addObj.prodDescip = $.trim($("#prodDescip").val());
        addObj.nodeid=$('#hid_nodeid').val();
		loaderShow();
		 $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_product_hierarchyController/post_attr_data')?>",
			data : JSON.stringify(addObj),
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
                loaderHide();
			    if(data==0){

                    $('#alert').modal('show');
				    $("#alert .modal-body center span").text("Product Code Already Exists.");
                    return;
			    } else{
                     cancel();
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
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Product Hierarchy"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Products', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Product Hierarchy</h2>
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
										<label for="edit_parent">Product Family</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<label id="edit_parent" style="font-weight:bold!important;"></label> 
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="edit_lead">Product Name</label>
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
										<label for="add_sub">Product Family</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<label class="add_sub_text" style="font-weight:bold!important;"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="add_lead_sub">Product Name</label>
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
			<div id="addArrtbutePop" class="modal fade" data-backdrop="static" >
				<div class="modal-dialog">
					<div class="modal-content">
						<form class="form">
							<div class="modal-header">
								<span class="close" onclick="cancel()">x</span>
								<h4 class="modal-title"><span id="title_name"></span> Attribute</h4>
							</div>
                            <input type="hidden" id="hid_nodeid"/>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-4">
										<label for="prodCode">Product Code*</label>
									</div>
									<div class="col-md-8">
										<input type="text" class="form-control" id="prodCode" />
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<label for="add_cat">Product Price</label>
									</div>
									<div class="col-md-4">
										<select name="adminContactDept" class="form-control" id="add_cat">

										</select>
										<span class="error-alert"></span>
									</div>
									<div class="col-md-4">
										<table class="currencySection">
											<tr>
												<td>
													<input type="text" class="form-control" id="budget"  placeholder="0.00"/>
												</td>
												<td>
													&nbsp;&nbsp;
												</td>
												<td>
													<a href="#" class="glyphicon glyphicon-plus-sign" onclick="add_curr()"></a>
												</td>
											</tr>
										</table>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">									
									<div class="col-md-4">										
									</div>
									<div class="col-md-8">
										<ul id="curr_list">
										</ul>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<label for="prodDescip">Description</label>
									</div>
									<div class="col-md-8">
										<textarea type="text" class="form-control" id="prodDescip"></textarea>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" class="btn" onclick="attr_save()" value="Save" />
								<input type="button" class="btn" onclick="cancel()" value="Cancel" />
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
