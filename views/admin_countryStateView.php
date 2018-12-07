
<!DOCTYPE html>
<html lang="en">
	<head>
	
	<?php require 'scriptfiles.php' ?>
	<style>
	#addmodal1 .glyphicon-plus-sign{
		border: 1px solid;
		padding: 8px;
		border-radius: 0 5px 5px 0px;
		background: #fff;
	}
	#countryState li{
		    border-bottom: 1px solid #ccc;
			margin-bottom: 5px;
	}
	#countryState li a.glyphicon.glyphicon-remove-circle {
		float: right;
	}
	#countryState{
		margin-top:10px;
	}
	.body-content .nav.nav-tabs .active a{
		font-weight: 800 !important;
		color: #b5000a !important;
	}

	</style>
	<script>
	$(document).ready(function(){
		load();
		$(".addBtns1").hide();
		$("#pghead1").hide();
		$("#icon1").hide();
		$("#state").hide();
		
		
	});
	function cancel(){
	  $('.modal').modal('hide');
      $('.modal select').val($('.modal select option:first').val());
      $('.error-alert').text("");
      $('#countryState').html("");
	  
		$("#addmodal1 .modal-header input[type=hidden]").remove();
	 }
	function a(){
		$(".addBtns").show();		
		$(".addBtns1").hide();
		$("#pghead").show();
		$("#pghead1").hide();
		$("#icon").show();		
		$("#icon1").hide();		
	}
	function b(){
		state_add();
		$(".addBtns1").show();		
		$(".addBtns").hide();
		$("#pghead1").show();
		$("#pghead").hide();
		$("#icon1").show();		
		$("#icon").hide();
	}
	function load(){
        	/*Country data starts*/
        	$('#tablebody').find("table").dataTable().fnDestroy();
        	$.ajax({
        		type : "POST",
        		url : "<?php echo site_url('admin_countryStateController/get_country');?>",
        		dataType : 'json',
        		cache : false,
        		success : function(data){
        			loaderHide();
        			if(error_handler(data)){
        				return;
        			}
        			$('#tablebody tr').remove();
        			var row = "";
        			for(i=0; i < data.length; i++ ){
        				var rowdata = JSON.stringify(data[i]);
        				row += "<tr><td>" + (i+1)+ "</td><td>" + data[i].lookup_value + "</td></tr>";
        			}
        			var count = data.length;
        			var strcount = "country"+(count+1);
        			$('#addcountryCount').val(strcount);
        			$('#tablebody').append(row);
        			$('#tablebody').parent("table").DataTable();
        				if(data.length > 0){
        					$("#state").show();
        				}else{
        					$("#state").hide();
        				}
        		}
        	});
	}

    function load_statetable(data){
		var state_data_table ="";
		state_data_table += '<table class="table" ><thead>';
		state_data_table += "<tr><th width='20%'>Sl No</th><th width='60%'>State Name</th><th width='20%'>Delete</th></tr></thead><tbody class='ui-sortable'>";

        for(var i=0; i < data.length; i++ ){
				var jsondata=JSON.stringify([{
				                                "holiday_date":data[i].lookup_id,
											    "holiday_name":data[i].lookup_value
											}]);

				state_data_table += "<tr><td>" + (i+1) + "</td><td>" + data[i].lookup_value + "</td><td><a href='#alert_delete' data-toggle='modal' onclick='del_data("+JSON.stringify(data[i].lookup_id)+")'><span class='fa fa-trash-o'></span></a></td></tr>";
		}
		state_data_table += '</tbody></table>';
		return state_data_table;
	}


	function state_add(){
		var hideshow = [];
		$('#country_side li').each(function(){
			hideshow.push($(this).val());
		});
		if(hideshow.length>0){				
			loaderHide();
		}else{
			loaderShow();
		}
		$('#State_side table').find("table").dataTable().fnDestroy();
		/*state data starts*/
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_countryStateController/get_state');?>",
					dataType : 'json',
					cache : false,
					success : function(data1){
						loaderHide();
						if(error_handler(data1)){
							return;
						}
						$('#country_side').empty();
							$('#State_side').empty();
							var row = "";
							var row1 = "";
							for(var i=0,j=0; i < data1.length; i++,j++ ){
								var rowdata = data1[i].lookup_id;
								if(i==0){
									row += "<li class='active'><a id='id"+data1[i].lookup_id+"' href='#"+data1[i].lookup_id+"' data-toggle='tab'>" + data1[i].lookup_value+"</li>";
									row1 += '<div class="tab-pane active" id="'+data1[i].lookup_id+'">';
								}else{
									row += "<li><a id='id"+data1[i].lookup_id+"' href='#"+data1[i].lookup_id+"' data-toggle='tab'>" + data1[i].lookup_value+"</li>";
									row1 += '<div class="tab-pane" id="'+data1[i].lookup_id+'">';
								}
								if(data1[i].hasOwnProperty("state_data")){
									row1 += load_statetable(data1[i].state_data);
                                }else{
                                    row1 += "<center style='padding-top: 60px;'><h4>No data available.</h4></center>";

                                }
								row1 += '</div>';
							}

                            $('#country_side').append(row);
            				$('#State_side').append(row1);
            				$('#State_side table').DataTable({
            													"aoColumnDefs": [
            														{
            															"bSortable": false,
            															"aTargets": [2] }
            														]
            												  });
            				$('#State_side table').removeAttr("style");
				
						}
					});
					/*states data ends*/
	}
	/*Country data ends*/

	/*add country starts*/
	function compose(){
		populateCountries1("add_country"); 
		
	}
	function add(){
		if($("#add_country").val()=="Select"){
			$("#add_country").closest("div").find("span").text("Country is required.");
			$("#add_country").focus();			
			return;
		}else{
			$("#add_country").closest("div").find("span").text("");
		}
		var addObj={};
		addObj.country_name = $.trim($("#add_country").val());
		addObj.country_count = $('#addcountryCount').val();
		loaderShow();
		$('#tablebody').parent("table").dataTable().fnDestroy();

		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_countryStateController/post_country');?>",
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
                    $('#alert').find('span').text('Country Name already exists.');
					$('#tablebody').parent("table").DataTable();
                    return;
				}
				else{
					cancel();
					$('#tablebody tr').remove();
					 var row = "";
					for(i=0; i < data.length; i++ ){						
						var rowdata = JSON.stringify(data[i]);
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].lookup_value + "</td></tr>";	
					}				   
					var count = data.length;
					var strcount = "country"+(count+1);
					$('#addcountryCount').val(strcount);
					$('#tablebody').append(row);
					$('#tablebody').parent("table").DataTable();
                        if(data.length > 0){
							$('#State_side table').find("table").dataTable().fnDestroy();
        					$("#state").show();
        					state_add();
        				}else{
        					$("#state").hide();
        				}
				}

			}
		});	
	}
	/*add country ends*/
	/*add state starts*/
	function compose1(){
		var activeCalendar = $.trim($("#country_side li.active").text());
            var activeCalendarID = $("#country_side li.active a").attr('id');
            $("#addmodal1 .modal-title").text("").text("Add State For "+ activeCalendar);

			$("#addmodal1 .modal-header").append('<input type="hidden" value="'+activeCalendarID+'">');
			change1(activeCalendar);
	}	
	function change1(countryID){
		populateStates(countryID,"add_stateCoun1"); 				
	}
	function add1(){
		
		var  csObj=[];
		var  addObj={};
        var count = $('#State_side .tab-pane.active table tbody tr').length;
       // alert(count);
		$("#countryState li").each(function(){
			csObj.push({
						 "state_name" : $(this).find('.stateName').text(),
						 "statecount" : "state"+(count++)
						});
		})
		if(csObj.length <= 0){
			$("#countryState").siblings(".error-alert").text("Please add state.");
			$("#add_stateCoun").focus();
			return;
		}else{
			$("#countryState").siblings(".error-alert").text("");
		}
        addObj.stateobj=csObj;
		 var calid=$.trim($("#addmodal1 .modal-header input[type=hidden]").val().replace("id", ""));
		 addObj.countyrid = $.trim($("#addmodal1 .modal-header input[type=hidden]").val().replace("id", ""));
				//$("#"+calid).find("table").dataTable().fnDestroy();
            loaderShow();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_countryStateController/post_state');?>",
				dataType : 'json',
				data : JSON.stringify(addObj),
				cache : false,
				success : function(data){

					loaderHide();
					if(error_handler(data)){
						return;
					}
					if(data==0)
                    {
						$("#alert").modal('show');
					}
                    else
                    {
                        loaderHide();
                        var row = "";
        				row += load_statetable(data.getdata[0].state_data);

                        if(data.dupdata.length > 0)
                        {
                            $('#countryState').html("");
                            for(var q=0;q<data.dupdata.length;q++)
                            {
                                $("#countryState").append('<li><span class="stateName"><b>'+ data.dupdata[q].statename +'</b></span>('+data.getdata[0].lookup_value+')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="error-alert">(Duplicate data found)</i><a title="Delete" class="glyphicon glyphicon-remove-circle"></a></li>');
                            }
                                              	$("#countryState li a").each(function(){
                                    				$(this).click(function(){
                                    					$(this).closest('li').remove();
                                    				})
                                    			});
                        }
                        else
                        {
             	                cancel();
                        }
                        if( $("#"+calid +" table").length > 0){
                             $("#"+calid).find("table").dataTable().fnDestroy();
                        }

        				$("#"+calid).html("").html(row);
                        $("#"+calid).find("table").each(function(){
        					$(this).DataTable({"aoColumnDefs": [{
        											"bSortable": false,
        											"aTargets": [2]
        											}
        										]});
        				});

					}
				}
			});
	}
	/*add state ends*/
		function del_data(obj){
			$("#delete").val(obj);
		}
        function delete_state(){
			var state = $("#delete").val();
			var addObj={}
			var calid = $("#country_side li.active a").attr('id').replace("id","");
			addObj.stateid = state;
			addObj.countyrid = $("#country_side li.active a").attr('id').replace("id","");
			$('#State_side table').parent("table").dataTable().fnDestroy();
			loaderShow();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_countryStateController/delete_data');?>",
				dataType : 'json',
				data : JSON.stringify(addObj),
				cache : false,
				success : function(data){
                    loaderHide();
					if(error_handler(data)){
						return;
					}

                    var row = "";
					if(data[0].hasOwnProperty('state_data')){
						row += load_statetable(data[0].state_data);
					}else{
						row += "<center style='padding-top: 60px;'><h4>No data available.</h4></center>";
					}

                    if( $("#"+calid +" table").length > 0){
						$("#"+calid).find("table").dataTable().fnDestroy();
                    }

      				$("#"+calid).html("").html(row);
                      $("#"+calid).find("table").each(function(){
      					$(this).DataTable({"aoColumnDefs": [{
      											"bSortable": false,
      											"aTargets": [2]
      											}
      										]});
      				});
		            cancel();
				}
			});
        }
		function add_sate(){
			var countryNameTxt = $.trim($("#country_side li.active").text());
			var countryName = $("#country_side li.active a").attr("id").replace("id",""); 
			var stateName = $("#add_stateCoun1").val();
			if(stateName=="-1"){
				$("#add_stateCoun1").closest("div").find("span").text("State is required.");
				$("#add_stateCoun1").focus();
				return;
			}else{
					$("#add_stateCoun1").closest("div").find("span").text("");
			}
			var html= 0;
			if($("#countryState li").length <= 0){
				$("#countryState").append('<li><span class="stateName">'+stateName+'</span>  <b>(' +countryNameTxt +')  </b><a title="Delete" class="glyphicon glyphicon-remove-circle"></a></li>');
				$("#countryState").siblings(".error-alert").text("");
			}else{
				$("#countryState li").each(function(){
					if(($(this).find('.stateName').text() == stateName) && ($(this).attr('class') == countryName) ){
						html = 1;
					}
				});
				if(html == 1){
					$("#countryState").siblings(".error-alert").text("Duplicate entry.");
					$("#add_stateCoun").focus();
					return;
				}else{
					$("#countryState").siblings(".error-alert").text("");
					$("#countryState").append('<li class="'+countryName+'"><span class="stateName">'+stateName +'</span>  <b>(' +countryNameTxt +')  </b><a title="Delete" class="glyphicon glyphicon-remove-circle"></a></li>');
				}
			}
			populateStates(countryNameTxt,"add_stateCoun1"); 
			$("#countryState li a").each(function(){
				$(this).click(function(){					
					$(this).closest('li').remove();
				})
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
							<div id="icon">		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Country List"/>
							</div>
							<div id="icon1" class="none">		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="State List"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Country_and_State', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2 id="pghead">Country List</h2>	
							<h2 id="pghead1" class="none">State List</h2>	
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<a href="#addmodal" class="addPlus" data-toggle="modal" onclick="compose()">
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
                        </div>
						<div class="addBtns1 none">
							<a href="#addmodal1" class="addPlus" data-toggle="modal" onclick="compose1()">
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
                        </div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="container-fluid">
					
				  <ul class="nav nav-tabs">
					<li class="active" onclick="a()"><a data-toggle="tab" href="#country">Country</a></li>
					<li onclick="b()" id="state"><a data-toggle="tab" href="#state1">State</a></li>    
				  </ul>
				  <div class="tab-content tab_countstat">
					<div id="country" class="tab-pane fade in active" >					
					 <table class="table" >
						<thead>  
							<tr>			
								<th class="table_header" width="10%">Sl No</th>
								<th class="table_header" width="90%">Country</th>
							</tr>
						</thead>  
						<tbody id="tablebody">
						</tbody>
					</table>
					</div>
					<div id="state1" class="tab-pane fade">
						<div class="col-xs-3 verticle-tab">
							<ul class="nav nav-tabs tabs-left" id="country_side">
							</ul>
						</div>
						<div class="col-xs-9 tab-col" > 
							<div class="tab-content" id="State_side">
							</div>
						</div>					
					</div>				
				  </div>
			
				</div>	
			</div>
			<div id="editmodal" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="editpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Edit Country-State</h4>
							</div>
							<div class="modal-body">	
									<div class="row">
										<div class="col-md-2">
											<label for="edit_stateCoun">Country*</label> 
										</div>
										<div class="col-md-4">
											<select name="adminContactDept" class="form-control" id="edit_stateCoun" >
												
											</select>											
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="edit_stateCoun1">State*</label> 
										</div>
										<div class="col-md-4">
											<select name="adminContactDept" class="form-control" id="edit_stateCoun1" >
												<input type="hidden" name="editstateCount" id="editstateCount"/>		
											</select>											
											<span class="error-alert"></span>
										</div>
									</div>	
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="edit_save()" value="Save">
									<input type="button" class="btn"  onclick="cancel()" value="Cancel" >
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
								 <h4 class="modal-title">Add Country</h4>
							</div>
							<div class="modal-body">	
									<div class="row">
										<div class="col-md-3">
											<label for="add_country">Country*</label> 
										</div>
										<div class="col-md-9">
											<input type="hidden" name="addcountryCount" id="addcountryCount"/>
											<select name="adminContactDept" class="form-control" id="add_country" autofocus> 
												  												
											</select>											
											<span class="error-alert"></span>
										</div>										
									</div>	
							</div>
							<div class="modal-footer">
									<input type="button" class="btn btn-default" onclick="add()" value="Save">
									<input type="button" class="btn btn-default"  onclick="cancel()" value="Cancel" >
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="addmodal1" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Add Country-state</h4>
							</div>
							<div class="modal-body">
									<div class="row">
										<!--<div class="col-md-2">
											<label for="add_stateCoun">Country*</label>
										</div>
										<div class="col-md-4">
											<select name="adminContactDept" class="form-control" onchange="change1()"  id="add_stateCoun" >
												
											</select>											
											<span class="error-alert"></span>
										</div>-->
										<div class="col-md-2">
											<label for="add_stateCoun1">State*</label>
										</div>
										<div class="col-md-6">
											<select name="adminContactDept" class="form-control" id="add_stateCoun1" >
												<input type="hidden" name="addstateCount" id="addstateCount"/>	
											</select>											
											<span class="error-alert"></span>
										</div>
                                        <div class="col-md-2">
											<a title="Add State" href="#" class="glyphicon glyphicon-plus-sign" onclick="add_sate()"></a>
										</div>
										<div class="col-md-12">
											<ol id="countryState">
											
											</ol>
											<span class="error-alert"></span>											
										</div>
									</div>	
							</div>
							<div class="modal-footer" >
									<input type="button" class="btn" onclick="add1()" value="Save">
									<input type="button" class="btn"  onclick="cancel()" value="Cancel" >
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
                                    <span>State already defined.</span>
                                    <br>
                                    <br>
                                    <input type="button" class="btn" data-dismiss="modal" value="Ok">
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<div id="alert_delete" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <center>
                                    <span>Are you sure you want to delete.</span>
									<input type="hidden" id="delete" />
                                    <br>
                                    <br>
                                    <input type="button" class="btn" onclick="delete_state()" value="Delete">
                                    <input type="button" class="btn" data-dismiss="modal" value="Cancel">
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
