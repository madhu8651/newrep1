
<!DOCTYPE html>
<html lang="en">
	<head>

	<?php require 'scriptfiles.php' ?>
	<style>
	
	/* ---------------------------------------- */
	#addmodal_h .glyphicon-plus-sign{
		border: 1px solid;
		padding: 8px;
		border-radius: 0 5px 5px 0px;
		position: absolute;
		right: 0;
		background: #fff;
	}
	#holidayListAdd li{
		    border-bottom: 1px solid #ccc;
			margin-bottom: 5px;
	}
	#holidayListAdd li a.glyphicon.glyphicon-remove-circle {
		float: right;
	}
	#holidayListAdd{
		margin-top:10px;
	}
	.body-content .nav.nav-tabs .active a{
		font-weight: 800 !important;
		color: #b5000a !important;
	}
	</style>
	<script>
	
	$(document).ready(function(){
	    /* code for sandbox */
        var url1= window.location.href;
        var fileNameIndex1 = url1.lastIndexOf("/") + 1;
        var filename1 = url1.substr(fileNameIndex1);
        sandbox(filename1);
		load1();
		load();
		$(".addBtns1").hide();
		$("#pghead1").hide();
		$("#icon1").hide();		
	});
	function a(){
		$(".addBtns").show();		
		$(".addBtns1").hide();
		$("#pghead").show();
		$("#pghead1").hide();
		$("#icon").show();		
		$("#icon1").hide();		
	}
	function b(){
		$(".addBtns1").show();		
		$(".addBtns").hide();
		$("#pghead1").show();
		$("#pghead").hide();
		$("#icon1").show();		
		$("#icon").hide();
	}
	function cancel_h(){
		$('.modal').modal('hide');
		$('.error-alert').text("");
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first').val());
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
		$('.error-alert').text("");
		$("#addmodal_h .modal-header input[type=hidden]").remove();
		$("#editmodal_h .modal-header input[type=hidden]").remove();
        $("#holidayListAdd li").remove();
	}

    function loadtable(data){
		var holiday_data_table ="";
		holiday_data_table += '<table class="table"><thead>';
		holiday_data_table += "<tr><th width='20%'>SL No</th><th width='20%'>Holiday Name</th><th width='20%'>Date</th><th width='20%'>Edit</th></tr></thead><tbody class='ui-sortable'>";
		for(var i=0; i < data.length; i++ ){
			var jsondata=JSON.stringify(data[i]);
            var remarks = "";
        	if(data[i].date == null ){
        		remarks = "";
        	}else{
        		remarks = moment(data[i].date).format("DD-MM-YYYY")
        	}
			holiday_data_table +=  "<tr><td>" + (i+1) + "</td><td>" + data[i].holidayname + "</td><td>"+remarks+"</td><td><a data-toggle='modal' href='#editmodal_h' onclick='selrow("+jsondata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
		}
		holiday_data_table += '</tbody></table>';
		return holiday_data_table;
	}

	function load(){
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_holidaysController/get_holidays'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				loaderHide();
				if(error_handler(data)){
					return;
				}
				$('#calendarName').empty();
				$('#holiDayList').empty();
				var row = "";
				var row1 = "";
				for(var i=0,j=0; i < data.length; i++,j++ ){
					var rowdata = data[i].calenderid;
                    /* ------------------------------------------------------------------------------------------------- */
                    if(i==0){
						row += "<li class='active'><a  href='#"+data[i].calenderid+"' data-toggle='tab'>" + data[i].calendername+"</li>";
						row1 += '<div class="tab-pane active" id="'+data[i].calenderid+'">';
					}else{
						row += "<li><a href='#"+data[i].calenderid+"' data-toggle='tab'>" + data[i].calendername+"</li>";
						row1 += '<div class="tab-pane" id="'+data[i].calenderid+'">';
					}
                    if(data[i].hasOwnProperty('holiday_data')){

                        row1 += loadtable(data[i].holiday_data);
                    }else{
                        row1 += "<center style='padding-top: 60px;'><h4>No data available.</h4></center>";

                    }
					row1 += '</div>';


				}
				$('#calendarName').append(row);
				$('#holiDayList').append(row1);
				$('#holiDayList table').DataTable({
													"aoColumnDefs": [
														{
															"bSortable": false,
															"aTargets": [3] }
														]
												  });
				$('#holiDayList table').removeAttr("style");

			}
		});
	}
		function compose_h(){
			$("#startDateTimePicker1").datetimepicker({
				ignoreReadonly:true,
				allowInputToggle:true,
				format:'DD-MM-YYYY',
			});
            $("#startDateTimePicker1").data().DateTimePicker.date(null);
            var activeCalendar = $.trim($("#calendarName li.active").text());
            var activeCalendarID = $("#calendarName li.active a").attr('id');
            $("#addmodal_h .modal-title").text("").text("Add Holiday For "+ activeCalendar);

			$("#addmodal_h .modal-header").append('<input type="hidden" value="'+activeCalendarID+'">');

		}
		function add_h(){
			var addObj={};
			var  csObj=[];


			$("#holidayListAdd li").each(function(){			
				csObj.push({
							 "date" : $(this).find('b').text(),
							 "holidayname" : $(this).find('span').text()
							});
			});
            if($("#add_holDate").val()==""){
			        //$("#add_holDate").closest("div").siblings(".error-alert").text("Date is required");
                    if(csObj.length <= 0){
    			        $("#add_holDate").focus();
    			        return;
                    }
		    }
		    else{
			        $("#add_holDate").closest("div").siblings(".error-alert").text("");
		    }
			if(csObj.length <= 0){
				$("#holidayListAdd").siblings(".error-alert").text("Please add Holiday.");				
				return;
			}else{
				$("#holidayListAdd").siblings(".error-alert").text("");
			}
				addObj.holidayList = csObj;
                var calid=$.trim($.trim($("#holiDayList .tab-pane.active").attr('id')));
				addObj.calenderid = $.trim($.trim($("#holiDayList .tab-pane.active").attr('id')));
				loaderShow();
				$("#"+calid).find("table").dataTable().fnDestroy();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_holidaysController/post_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
                        loaderHide();

						if(error_handler(data)){
							return;
						}
                        /* ---------------------------------------------------------------------------- */

        				var row = "";
        				row += loadtable(data.holdata[0].holiday_data);
        				if(data.dupdata.length>0)
                        {
                              $('#holidayListAdd').html("");
                                    for(var q=0;q<data.dupdata.length;q++)
                                    {

                                        $("#holidayListAdd").append('<li ><span>'+ data.dupdata[q].holidayname +'</span>(<b> ' +data.dupdata[q].date +' </b>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="error-alert">(Duplicate data found)</i><a title="Delete" class="glyphicon glyphicon-remove-circle"></a></li>');
                                    }
                                                      	$("#holidayListAdd li a").each(function(){
                                            				$(this).click(function(){
                                            					$(this).closest('li').remove();
                                            				})
                                            			});
                        }
                        else
                        {
                          	cancel_h();
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
				});
		}
		function selrow(obj){
			$("#startDateTimePicker").datetimepicker({
				ignoreReadonly:true,
				allowInputToggle:true,
				format:'DD-MM-YYYY',
			});
			var activeCalendar = $.trim($("#calendarName li.active").text());
            var activeCalendarID = $.trim($.trim($("#holiDayList .tab-pane.active").attr('id')));
            $("#editmodal_h .modal-title").text("").text("Edit Holiday For "+ activeCalendar);
			
			$("#editmodal_h .modal-header").append('<input type="hidden" value="'+activeCalendarID+'">')
			
			$("#edit_holDate").val(moment(obj.date).format("DD-MM-YYYY"));
			$("#edit_holiday").val(obj.holidayname);
            $("#edit_holidayid1").val(obj.holidayid);
			$(".error-alert").text("");
		}
		function edit_save_h(){				
				if($("#edit_holDate").val()==""){
					$("#edit_holDate").closest("div").siblings(".error-alert").text("Date is required");
					$("#edit_holDate").focus();
					return;
				}
				else{
					$("#edit_holDate").closest("div").siblings(".error-alert").text("");
				}
				if($.trim($("#edit_holiday").val())==""){
					$("#edit_holiday").closest("div").find("span").text("Holiday Name is required");
					$("#edit_holiday").focus();
					return;
				}else if(!validate_location($.trim($("#edit_holiday").val()))) {
					$("#edit_holiday").closest("div").find("span").text("No special characters allowed (except &).");
					$("#edit_holiday").focus();
					return;
				}else if(!firstLetterChk($.trim($("#edit_holiday").val()))) {
					$("#edit_holiday").closest("div").find("span").text("First letter should not be Numeric or Special character.");
					$("#edit_holiday").focus();
					return;
				}else{
					$("#edit_holiday").closest("div").find("span").text("");
				}
				var addObj={};
                var calid=$.trim($("#editmodal_h .modal-header input[type=hidden]").val().replace("id", ""));
				addObj.calenderid = $.trim($("#editmodal_h .modal-header input[type=hidden]").val().replace("id", ""));
				addObj.date = $.trim($("#edit_holDate").val());
				addObj.holidayname = $.trim($("#edit_holiday").val());
                addObj.holidayid = $("#edit_holidayid1").val();				
				loaderShow();
				$("#"+calid).find("table").dataTable().fnDestroy();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_holidaysController/update_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						loaderHide();
						if(error_handler(data)){
							return;
						}
                        cancel_h();
                        str= data.str;
                        if(str=="0"){
                                $('#alert_h').modal('show');
								$("#"+data.records[0].calenderid).find("table").DataTable({
																							"aoColumnDefs": [
																								{ 	
																									"bSortable": false, 
																									"aTargets": [2] }
																								]
																						  });
                                return;
                        }else{

                                cancel();
                				var row = "";

                				row += loadtable(data.records[0].holiday_data);

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

			/* ------------------------------------- */
			/* ------------------------------------- */
		function add_holiday_list(){
			var hDate = $("#add_holDate").val();
			var hName = $.trim($("#add_holiday").val());
			$(".error-alert").text("")
			if(hDate == ""){
				$("#add_holDate").closest("div").siblings(".error-alert").text("Date is required");
				$("#add_holDate").focus();
				return;
			}else{
					$("#add_holDate").closest("div").siblings(".error-alert").text("");
			}
			
			if(hName == ""){
				$("#add_holiday").closest("div").find("span").text("Holiday Name is required");
				$("#add_holiday").focus();
				return;
			}else if(!validate_location(hName)) {
				$("#add_holiday").closest("div").find("span").text("No special characters allowed (except &).");
				$("#add_holiday").focus();
				return;
			}else if(!firstLetterChk(hName)) {
				$("#add_holiday").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#add_holiday").focus();
				return;
			}else{
					$("#add_holiday").closest("div").find("span").text("");
			}
			
			var html= 0;
			if($("#holidayListAdd li").length <= 0){
				$("#holidayListAdd").append('<li><span>'+ hName +'</span>(<b> ' +hDate +' </b>)<a title="Delete" class="glyphicon glyphicon-remove-circle"></a></li>');
				$("#holidayListAdd").siblings(".error-alert").text("");
			}else{
				$("#holidayListAdd li").each(function(){
					/*if($(this).find('span').text().trim() == hName){
						html = 1;
					}else if($(this).find('b').text().trim() == hDate){
						html = 2;
					}*/
                    if($(this).find('span').text().trim() == hName && $(this).find('b').text().trim() == hDate){
                        html = 2;
                    }
				});
				
				if(html == 1){
					$("#holidayListAdd").siblings(".error-alert").text("Duplicate Holiday Name.");
					$("#add_holiday").focus();
					return;
				}else if(html == 2){
					$("#holidayListAdd").siblings(".error-alert").text("Duplicate Data.");
					return;
				}else{
					$("#holidayListAdd").siblings(".error-alert").text("");
					$("#holidayListAdd").append('<li><span>'+ hName +'</span>(<b> ' +hDate +' </b>)<a title="Delete" class="glyphicon glyphicon-remove-circle"></a></li>');
				}
			}
			$("#add_holiday").val("");
			$('#startDateTimePicker1').data().DateTimePicker.date(null);
			$("#holidayListAdd li a").each(function(){
				$(this).click(function(){
					$(this).closest('li').remove();
				})
			});
		}
		/*calender*/
		var maindata; 
	function cancel(){
		$('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.error-alert').text('');		
	}
function load1(){
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('admin_calendarController/get_data'); ?>",
		dataType : 'json',
		cache : false,
		success : function(data){
			loaderHide();
			if(error_handler(data)){
				return;
			}
			maindata = data;
			cancel();
			$('#tablebody').empty();
			var row = "";
			for(i=0; i < data.length; i++ ){						
				var rowdata = JSON.stringify(data[i]);
				row += "<tr><td>" + (i+1) + "</td><td>" + data[i].calendername + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow1("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
			}					
			$('#tablebody').append(row);
			$('#tablebody').parent("table").DataTable({
														"aoColumnDefs": [
															{
																"bSortable": false,
																"aTargets": [2] }
															]
													  });


            if(data.length > 0){
							    $('#holiDayList table').find("table").dataTable().fnDestroy();
        					    $("#state").show();
                                load();
              				}else{
              					$("#state").hide();
              		        }
            /* code for sandbox */
            if(data.length >= parseInt(recordcount) && parseInt(recordcount)!= 0){
                $('#calbtn').hide();
            }else{
                $('#calbtn').show();
            }
		}
	});		
}	
		function add(){
			if($.trim($("#add_calendar").val())==""){
				$("#add_calendar").closest("div").find("span").text("Calendar Name is required.");
				$("#add_calendar").focus();
				return;
			}else if(!validate_noSpCh($.trim($("#add_calendar").val()))) {
				$("#add_calendar").closest("div").find("span").text("No special characters allowed.");
				$("#add_calendar").focus();
				return;
			}else if(!firstLetterChk($.trim($("#add_calendar").val()))) {
				$("#add_calendar").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#add_calendar").focus();	
				return;
			}else{
				$("#add_calendar").closest("div").find("span").text("");
			}
			var addObj={};
			addObj.calendername = $.trim($("#add_calendar").val());
			loaderShow();
			$('#tablebody').parent("table").dataTable().fnDestroy();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_calendarController/post_data'); ?>",
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
						$("#add_calendar").focus();						
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
                        $('#tablebody').empty();
						var row = "";
						for(i=0; i < data.length; i++ ){
						var rowdata = JSON.stringify(data[i]);
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].calendername + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow1("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
						}
						$('#tablebody').append(row);
						load();
						$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [
																	{
																		"bSortable": false,
																		"aTargets": [2] }
																	]
															  });
                        if(data.length > 0){
							    $('#holiDayList table').find("table").dataTable().fnDestroy();
        					    $("#state").show();
                                load();
              				}else{
              					$("#state").hide();
              		        }
                        /* code for sandbox */
                            if(data.length >= parseInt(recordcount) && parseInt(recordcount)!= 0){
                                $('#calbtn').hide();
                            }else{
                                $('#calbtn').show();
                            }
					}
				}
			});	
		}
		function selrow1(obj){
			$("#edit_calendar1").val(obj.calenderid);
			$("#edit_calendar").val(obj.calendername);
			$(".error-alert").text("");
		}
		function edit_save(){
				if($.trim($("#edit_calendar").val())==""){
					$("#edit_calendar").closest("div").find("span").text("Calendar Name is required.");
					$("#edit_calendar").focus();
					return;
				}else if(!validate_noSpCh($.trim($("#edit_calendar").val()))) {
					$("#edit_calendar").closest("div").find("span").text("No special characters allowed.");
					$("#edit_calendar").focus();
					return;
				}else if(!firstLetterChk($.trim($("#edit_calendar").val()))) {
					$("#edit_calendar").closest("div").find("span").text("First letter should not be Numeric or Special character.");
					$("#edit_calendar").focus();	
					return;
				}else{
					$("#edit_calendar").closest("div").find("span").text("");
				}
				var addObj={};
				addObj.calenderid = $.trim($("#edit_calendar1").val());
				addObj.calendername = $.trim($("#edit_calendar").val());
				loaderShow();
				$('#tablebody').parent("table").dataTable().fnDestroy();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_calendarController/update_data'); ?>",
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
							$("#edit_calendar").focus();
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
							$('#tablebody').empty();
							var row = "";
							for(i=0; i < data.length; i++ ){
								var rowdata = JSON.stringify(data[i]);
								row += "<tr><td>" + (i+1) + "</td><td>" + data[i].calendername + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow1("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
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
							<div id="icon">		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Calendar List"/>
							</div>
							<div id="icon1" class="none">		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Holidays List"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Holiday_Calendar', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2 id="pghead">Holiday Calendar</h2>	
							<h2 id="pghead1" class="none">Holiday Calendar</h2>	
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<a href="#addmodal" class="addPlus" id='calbtn' data-toggle="modal" >
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
                        </div>
						<div class="addBtns1 none">
							<a href="#addmodal_h" class="addPlus" data-toggle="modal" onclick="compose_h()">
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
                        </div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
			<!----------------->
					
				</div>
				<div class="container-fluid">
					
				  <ul class="nav nav-tabs">
					<li class="active" onclick="a()"><a data-toggle="tab" href="#country">Calendar List</a></li>
					<li onclick="b()" id="state"><a data-toggle="tab" href="#state1">Holidays List</a></li>    
				  </ul>
				  <div class="tab-content tab_countstat">
					<div id="country" class="tab-pane fade in active" >					
					 <table class="table">
						<thead>  
							<tr>			
								<th class="table_header" width="10%">SL No</th>
								<th class="table_header" width="80%">Calendar Name</th>
								<th class="table_header" width="10%"></th>		   
							</tr>
						</thead>  
						<tbody id="tablebody">
						</tbody>    
					</table>
					</div>
					<div id="state1" class="tab-pane fade">
						<div class="col-xs-3 verticle-tab">
							<ul class="nav nav-tabs tabs-left" id="calendarName">
							</ul>
						</div>
						<div class="col-xs-9 tab-col" > 
							<div class="tab-content" id="holiDayList">
							</div>
						</div>					
					</div>				
				  </div>
			
				</div>
				<!--<div class="col-xs-3 verticle-tab">
					<ul class="nav nav-tabs tabs-left" id="calendarName">
					</ul>
				</div>
				<div class="col-xs-9 tab-col" > 
					<div class="tab-content" id="holiDayList">
					</div>
				</div>-->

			</div>
			<div id="editmodal_h" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="editpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel_h()">x</span>
								 <h4 class="modal-title">Edit Holiday</h4>
							</div>
							<div class="modal-body">
									<div class="row">
										<div class="col-md-3">
											<label for="edit_holDate">Date*</label>
										</div>
										<div class="col-md-9">
											<div class='input-group date' id='startDateTimePicker'>
													 <input id="edit_holDate" placeholder="Pick Date" type='text' class="form-control" readonly="readonly" />
													 <span class="input-group-addon">
														 <i class="glyphicon glyphicon-calendar"></i>
													 </span>
												 </div>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<label for="edit_holiday">Holiday Name*</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_holiday" autofocus/>
                                             <input type="hidden" class="form-control closeinput"  id="edit_holidayid1"/>
											<span class="error-alert"></span>
										</div>
									</div>
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="edit_save_h()" value="Save">
									<input type="button" class="btn" id="cancle" onclick="cancel_h()" value="Cancel" >
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="addmodal_h" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel_h()">x</span>
								 <h4 class="modal-title">Add Holiday</h4>
							</div>
							<div class="modal-body">
									<div class="row">
										<div class="col-md-3">
											<label for="add_holDate">Date*</label>
										</div>
										<div class="col-md-7">
											<div class='input-group date' id='startDateTimePicker1'>
													 <input id="add_holDate" placeholder="Pick Start Time" type='text' class="form-control" readonly="readonly" />
													 <span class="input-group-addon">
														 <i class="glyphicon glyphicon-calendar"></i>
													 </span>
												 </div>
											<span class="error-alert"></span>
										</div>										
									</div>
									<div class="row">
										<div class="col-md-3">
											<label for="add_holiday">Holiday Name*</label>
										</div>
										<div class="col-md-7 col-sm-10 col-xs-10">
											<input type="text" class="form-control closeinput" name="adminContactDept" id="add_holiday" autofocus/>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2">
											<a title="Add Holiday List" href="#" class="glyphicon glyphicon-plus-sign" onclick="add_holiday_list()"></a>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-12">
											<ol id="holidayListAdd">
											
											</ol>
											<span class="error-alert"></span>											
										</div>	
									</div>
									
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="add_h()" value="Save">
									<input type="button" class="btn" id="cancle" onclick="cancel_h()" value="Cancel" >
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="alert_h" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
						<div class="modal-body">
							<div class="row">
								<center>
									<span>Holiday is already assigned in this date.</span>
									<br>
									<br>
									<input type="button" class="btn" data-dismiss="modal" value="Ok">
								</center>
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
								 <h4 class="modal-title">Edit Calendar</h4>
							</div>
							<div class="modal-body">
									<div class="row">
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
											<label for="edit_calendar">Calendar Name*</label> 
										</div>
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
											<input type="hidden" class="form-control closeinput"  id="edit_calendar1"/>
											<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_calendar" autofocus/>
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
								 <h4 class="modal-title">Add Calendar</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
										<label for="add_calendar">Calendar Name*</label> 
									</div>
									<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
										<input type="text" class="form-control closeinput" name="adminContactDept" id="add_calendar" autofocus/>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" class="btn" onclick="add()" value="Save">
								<input type="button" class="btn" id="cancle" onclick="cancel()" value="Cancel" >
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
									<span>Calendar Name is already exists.</span>
									<br>
									<br>
									<input type="button" class="btn" data-dismiss="modal" value="Ok">
								</center>
							</div>
						</div>                            
                    </div>
                </div>
        </div>
		<?php require 'footer.php' ?>

	</body>
</html>
