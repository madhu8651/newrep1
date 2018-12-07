<!DOCTYPE html>
<html lang="en">
    <head>
		
		<?php require __DIR__.'/../scriptfiles.php' ?>
		
		<script type="text/javascript">
			function datechange(){
				gettime();
			}
			$(document).ready(function(){
				list_view()
				$('#selected_date').datetimepicker({
                    format: 'DD-MM-YYYY',
					minDate: moment("01-01-2000", 'DD-MM-YYYY'),
                    //defaultDate:moment()
                });
				
				/* $("#selected_date").on("dp.change", function (e) {
					gettime();
				});*/
				
				$('#selected_start_time').datetimepicker({
                    format: 'HH:mm:ss'
                });
				$('#selected_end_time').datetimepicker({
                    format: 'HH:mm:ss'
                });
				
				
				$("#selected_start_time").on("dp.change", function (e) {
					$('#selected_end_time').data("DateTimePicker").minDate(e.date);
					$("#selected_end_time input[type=text]").val("");
					$('#selected_end_time').data().DateTimePicker.date(null);
					/* gettime(); */
					valueChange();
				});
				
				$("#selected_end_time").on("dp.change", function (e) {					
					valueChange();
				});
				
				 savedreportdisplay();
				 get_heirarchy(reportArray[0]);
				if(reportArray.length == 2 ){
					if (getCookie("reportOutput") != "" && getCookie("reportInput") !="") {
						var value = JSON.parse(getCookie("reportInput"));
						if(value.chartType == 'line'){
							test(JSON.parse(getCookie("reportOutput")), 'LastViewed', JSON.parse(getCookie("reportInput")));
						}else{
							generateTable(JSON.parse(getCookie("reportOutput")), 'LastViewed');
						}						
						reportData = JSON.parse(getCookie("reportOutput"));
					} 
				}
				/* google.charts.load("current", {packages:["timeline"]});
				google.charts.setOnLoadCallback(drawChart); */
			})
		
			/* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			/* -----------------------------------function to display  the saved report---------------------------------------------------------------------------------------------- */
			/* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			function savedreportdisplay(){
				if(reportArray.length == 3 ){
					$("#previousPage").html('<h4><a class="btn" href="<?php echo site_url('manager_standard_analytics'); ?>#savedReport='+report+'"><i class="fa fa-arrow-left fa-3" aria-hidden="true"></i> Back</a></h4>')
					
					var obj1={};
                    obj1.id = reportArray[reportArray.length-1];
				    obj1.type ='Daily_WPA';
				    obj1.subtype ='';
					$.ajax({
						type: "POST",
						url:"<?php echo site_url('reports/standard_RepAnalysisController/getsaved_reports')?>",
						data : JSON.stringify(obj1), 
						dataType:'json',
						
						success: function(data){
						  
							if(error_handler(data)){
								return;
							}
							test(data.tabledetails , "auto");
							generateTable(data.tabledetails);
							
							var obj={};
							obj.user = data.userid;
							obj.starttime = moment(data.starttime,'HH:mm:ss').format('HH:mm:ss');
							obj.endtime = moment(data.endtime,'HH:mm:ss').format('HH:mm:ss');
							obj.selectedDate = moment(data.selecteddate,'DD-MM-YYYY').format('DD-MM-YYYY');
							obj.report_name = data.report_name;
							
							reportData = obj;

							
							$(".pageHeader1").text(obj.report_name);
							/* -------------Set value to input field-------------- */
							
							$("#user_name").val(obj.user);
							$("#selected_date input").val(obj.selectedDate);
							$("#selected_start_time input").val(obj.starttime);
							$("#selected_end_time input").val(obj.endtime );
							$("#report_name").val(data.report_name);
							
							
							/* timing(obj.user, obj.selectedDate); */
							reportData.selectedDate = moment(reportData.selectedDate,'DD-MM-YYYY').format('DD-MM-YYYY');

							var name = $("#user_name option:selected").text();
							$("#report_title").html("Generated Analysis is for <i>"+ name +"</i> on "+ moment(obj.selectedDate, 'DD-MM-YYYY').format(dateFormat) +" from "+ obj.starttime +" to "+ obj.endtime +"<hr>");
						},
						error:function(data){
							network_err_alert(data);
						}
					})
				}
            }
			function valueChange(){
					var obj={};
					obj.user = $("#user_name").val();					
					obj.starttime  = moment($("#selected_start_time input").val(),"HH:mm:ss").format("HH:mm:ss");
					obj.endtime = moment($("#selected_end_time input").val(), "HH:mm:ss").format("HH:mm:ss");
					obj.selectedDate  = moment($("#selected_date input").val(), "DD-MM-YYYY").format("DD-MM-YYYY");
					obj.report_name = $("#report_name") .val();
					
					var changedInput=0;
					if(reportArray.length == 3 ){
						
						if( obj.user == reportData.user && 
						obj.starttime == reportData.starttime &&
						obj.endtime == reportData.endtime &&
						obj.selectedDate == reportData.selectedDate ) {
									changedInput=0;
									
									$("#canvas, #chart_type,#report_title").css("visibility", "visible");
									$("#save_report_btn").hide();
									$("#saveAs_report_btn").show().removeAttr("disabled","disabled" );	
									$("#update_report_btn").show().removeAttr("disabled","disabled" );
									$("#generate_save_btn").attr("disabled" , "disabled");
						}else{
									changedInput=1;
									
									$("#canvas,#chart_type,#report_title").css("visibility", "hidden");
									$("#save_report_btn").hide();
									$("#saveAs_report_btn").show().attr("disabled","disabled" );	
									$("#update_report_btn").show().attr("disabled","disabled" );
									$("#generate_save_btn").removeAttr("disabled" , "disabled");
							
						}
						
						if( obj.report_name == reportData.report_name || changedInput == 1) { 
									$("#save_report_btn").hide();
									$("#saveAs_report_btn,#update_report_btn").show().attr("disabled","disabled" );	
						}else{
									$("#save_report_btn").hide();
									$("#saveAs_report_btn, #update_report_btn").show().removeAttr("disabled" );	
						} 
					}
					
					/* ------------------------------------------------------------ ---------------------------------------------------------------------------------
						1) if the page is saved report page then "generate_report Btn" , "save_as Btn", "update Btn"  should be disabled by default
						2) if input value for generate_report changed user must have to generate report -- "generate_report Btn" will be activated that time and the graph already generated should be blank				    
						
						3) after successfully report generation "save_as Btn", "update Btn" will be activated 
						----------------------faret save------------------------------
						4) alert saved data if data is saved and return value is true
						5) when update is clicked and return value is 0 then alert data duplication error
						6) when update is clicked and return value is 1 then alert data updated sucessfully
						-------------------------------------------------------------- */
						
						
					
					
			}
			
			
			
			/* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			/* -----------------------------------function to get timing of user  for the selected duration ---------------------------------------------------------------------- */
			/* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			function gettime(){
				var user = $("#user_name").val();
				var selectedDate = moment($("#selected_date input").val(),'DD-MM-YYYY').format('DD-MM-YYYY');
                timing(user, selectedDate);				
            }

			
			function timing(user, selectedDate){
				if(user == "" || !moment(selectedDate,'DD-MM-YYYY').isValid()){
					return;
				}
				var obj={};
				obj.user = user;
				obj.selectedDate = selectedDate;
                $.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_RepAnalysisController/get_time')?>",
                    data : JSON.stringify(obj),
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
                        for(var i=0;i<data.length; i++){
                            if(data[i].start_time=='' && data[i].end_time=='')
                            {
                               alert("No Activities or Working Hours defined for the selected user for the particular day");
                               $('#selected_start_time input').val('');
							   $('#selected_end_time input').val('');
                            }else
                            {
                               	$('#selected_start_time input').val(moment(data[i].start_time,'HH:mm:ss').format('HH:mm:ss'));
							    $('#selected_end_time input').val(moment(data[i].end_time,'HH:mm:ss').format('HH:mm:ss'));
                            }

						}
						loaderHide();
						valueChange()
					},
					error:function(data){
						network_err_alert(data);
					}
				});
				
			}
			
			/* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			/* -----------------------------------save / update / save as function---------------------------------------------------------------------------------------------- */
			/* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			function saveReport(state){
               /*  if(state=='save')
                {
                   $("#report_name").val("Daily Work Pattern Analysis");
                }
                else
                {
                  $("#report_name").val(reportData.report_name);

                }
				$("#save_report_modal").modal("show"); */
				save(state)
	
			}
			function save(state){
				
				reportData.report_name= $.trim($("#report_name").val());
				if(reportData['report_name'] == ""){
						$("#report_name").closest("div").find(".error-alert").text("Report name is required.");
						$("#report_name").focus();
						return;
				}else if(!validate_name(reportData.report_name)){
						$("#report_name").closest("div").find(".error-alert").text("No special characters allowed (except &, _,-,.)");
						$("#report_name").focus();
						return;
				}else if(!firstLetterChk(reportData.report_name)){
						$("#report_name").closest("div").find(".error-alert").text("First letter should not be Numeric or Special character.");
						$("#report_name").focus();
						return;
				}else{
						$("#report_name").closest("div").find(".error-alert").text("");
				}
				reportData.report_name = reportData['report_name'];
				
				if(reportArray.length < 2 ){
					return;
				}

                reportData.reportid = reportArray[0];
                reportData.report_parent_id = reportArray[1];
				if(reportArray.length == 2 || state=='saveAs'){
                   reportData.id='0';
				}
				
				if(reportArray.length == 3 && state=='update'){
					reportData.id = reportArray[reportArray.length-1];
				}
				
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_RepAnalysisController/save_report/work_pattern_analysis')?>",
					data : JSON.stringify(reportData),
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
						if(data == 0){
							alert("Report has not been seved succesfully");
							return;
						}
						reportData = {};
						
						
						if(state=='saveAs' || state=='save'){
						  alert("Report has been saved succesfully");
						}else if(state=='update'){
							alert("Report has been updated succesfully");
						}
						location.reload();
						$("#report_name").val("");
						
					},
					error:function(data){
						network_err_alert(data);
					}
				})
			}
			/* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			/* -----------------------------------get employees  List function---------------------------------------------------------------------------------------------- */
			/* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
            function list_view(){
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_RepAnalysisController/get_employees')?>",
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
						var select = $("#user_name"), options = "<option value=''>Select</option>";
						select.empty();
						for(var i=0;i<data.length; i++){
							options += "<option value='"+data[i].user_id+"'>"+ data[i].user_name+"</option>";
						}
						select.append(options);
						if(getCookie("reportInput") != ''){
							var prviouse_data =  $.parseJSON(getCookie("reportInput"));
							console.log(prviouse_data)
							$("#user_name option[value='"+prviouse_data.user+"']").attr("selected", true);							
							$("#selected_date input").val(prviouse_data.selectedDate);
							$("#selected_start_time input").val(prviouse_data.starttime);
							$("#selected_end_time input").val(prviouse_data.endtime);
						}
						loaderHide();
					},
					error:function(data){
						network_err_alert(data);
					}
				});
			}

            
			/* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			/* -----------------------------------Generate report onclick of generate button ---------------------------------------------------------------------------------------------- */
			/* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

			function generateReport(){
				$("#rep_typ_list").val('line');
				/* chart.clearChart(); */
				if($("#user_name").val() == ""){
					$("#user_name").next(".error-alert").text("Select a user.");
					return;
				}else{
					$("#user_name").next(".error-alert").text("");
				}

				if($("#selected_date input").val() == ""){
					$("#selected_date").next(".error-alert").text("Select a Date.");
					return;
				}else{
					$("#selected_date").next(".error-alert").text("");
				}
				if($("#selected_start_time input").val() == ""){
					$("#selected_start_time").next(".error-alert").text("Select start time.");
					return;
				}else{
					$("#selected_start_time").next(".error-alert").text("");
				}
				if($("#selected_end_time input").val() == ""){
					$("#selected_end_time").next(".error-alert").text("Select end time.");
					return;
				}else{
					$("#selected_end_time").next(".error-alert").text("");
				}
				var name = $("#user_name option:selected").text();
				var date = $("#selected_date input").val();
				
				var obj={};
				reportData={};
				obj.user = $("#user_name").val();
				obj.starttime = $("#selected_start_time input").val();
				obj.endtime = $("#selected_end_time input").val();
                obj.selectedDate = moment($("#selected_date input").val(),'DD-MM-YYYY').format('DD-MM-YYYY');
				obj.type='Daily_WPA';
				obj.subtype='';
				obj.chartType=$("#rep_typ_list option:selected").val();
				
				$("#timeline-tooltip,#table_view").hide();
				$("#generate_save_btn").attr("disabled" , "disabled");
				$("#report_title").html("");
				$("#save_report_btn,#saveAs_report_btn, #update_report_btn, #chart_type, #pdf_report_btn").hide();
				
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_RepAnalysisController/generateReport')?>",
				   	data : JSON.stringify(obj),
					dataType:'json',
					success: function(data){
						var temp=[];
						if(error_handler(data)){
							return;
						}
						$("#generate_save_btn").removeAttr("disabled");
						if(data.length > 0){
							main=[];
							var isValid = 0;
							for (i=0; i < data.length; i++){
								
								
								var start = moment(new Date(data[i].actual_startdate));
								var end = moment(new Date(data[i].actual_enddate));
								var cal_duration = moment.duration(moment(end, 'DD-MM-YYYY HH:mm:ss').diff(moment(start, 'DD-MM-YYYY HH:mm:ss'))).asMilliseconds("");
								var duration = moment.utc(cal_duration).format("HH:mm:ss");
								/* console.log(  " start     : "+moment(start, 'DD-MM-YYYY HH:mm:ss').format('HH:mm:ss')+
											"\n end       : "+moment(end, 'DD-MM-YYYY HH:mm:ss').format('HH:mm:ss')+
											"\n Duration  : "+ duration+
											"\n Is before : "+start.isBefore(end)); */
								if(start.isBefore(end) == false){
									isValid++;
								}else{
									main.push(data[i])
								}
							}
							if(isValid > 0){
								alert("In "+isValid+" Records Inappropriate Start-Time and End-Time detected");
								$("#generate_save_btn").removeAttr("disabled");
								
							}
							
							test(main, "manual");
							generateTable(data);
							if(reportArray.length == 2 ){
								localStorage.setItem('reportOutput', JSON.stringify(data))
								setCookie('reportOutput', JSON.stringify(data),1);
								setCookie('reportInput', JSON.stringify(obj),1);
							}
							reportData = obj;
							loaderHide();
							
							$("#report_title").html("Generated Analysis is for <i>"+ name +"</i> on "+ moment(date, 'DD-MM-YYYY').format(dateFormat) +" from "+obj.starttime +" to "+ obj.endtime +"<hr>");
						}else{
							$("#report_title").html("<center>No records available for the selected time duration.</center>")
						}
						
					},
					error:function(data){
						network_err_alert(data);
					}
				});
                /* return $http.post; */
			}
			function cancel_report_setting(){
				$("#user_name").val("");
				$('#selected_date').data().DateTimePicker.date(null);
				$('#selected_start_time').data().DateTimePicker.date(null);
				$('#selected_end_time').data().DateTimePicker.date(null);
				/* $('#schedule_report').modal("show"); */
				
			}
			/* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			/* ------------------------------------------------------------------------------------------------------------------------------- */
			/* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			function test(data , state, inputdata){		
				if(state == "auto"){
					$("#save_report_btn").hide();
					$("#saveAs_report_btn").show().attr("disabled","disabled" );	
					$("#update_report_btn").show().attr("disabled","disabled" );
					$("#generate_save_btn").attr("disabled" , "disabled");						  
				}
				
				if(state == "manual"){
					if(reportArray.length == 3 ){
							$("#save_report_btn").hide();
							/* $("#saveAs_report_btn").show().attr("disabled","disabled" );	
							$("#update_report_btn").show().attr("disabled","disabled" ); */
							$("#saveAs_report_btn, #update_report_btn").show().removeAttr("disabled" );	
					}else{
							$("#save_report_btn").show();
							$("#saveAs_report_btn").hide().attr("disabled","disabled" );	
							$("#update_report_btn").hide().attr("disabled","disabled" );
												  
					}
				}
				
				$("#timeline-tooltip").show();
				$("#chart_type, #pdf_report_btn").show();
				$("#canvas,#chart_type, #report_title").css("visibility", "visible");
				if(state == 'LastViewed'){
					$("#chart_type").hide();
					$("#chart_type").css("visibility", "hidden");
					$("#report_title").html("LastViewed Analysis is for <i>"+ name +"</i> on "+ moment(inputdata.selectedDate, 'DD-MM-YYYY').format(dateFormat) +" from "+ inputdata.starttime +" to "+ inputdata.endtime +"<hr>");
				}else{
					$("#chart_type").show();
					$("#chart_type").css("visibility", "visible");
				}
				var array=[];
				for(i=0; i<data.length; i++){
				   var obj=data[i];
				   array[i]=[];
				   $.each(obj, function(key, value){
					array[i].push(data[i][key]);
				   })
				   array.push(array[i])
				}
				array.splice(-1,1);
				
				builtTimelineReport(array, state);
				tempReportData = array;
				
            }
			/* ---------------------------- this function is written for generating report on cancel of detail activity view popup to fix unselect the selected activity-------------------------------------------------------------------------------- */
			
			function builtTimelineReport(array, state){
				google.charts.load("current", {packages:["timeline"]});
				google.charts.setOnLoadCallback(function(){
					drawChart(array, state);
				
				});
			}
			var tempReportData;
			
			/* -----------------------------------draw google chart ---------------------------------------------------------------------------------------------- */
			/* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			function drawChart(rowdata, state) {
				var container = document.getElementById('timeline-tooltip');
				var chart = new google.visualization.Timeline(container);
				var dataTable = new google.visualization.DataTable();
				
				
				dataTable.addColumn({ type: 'string', id: 'Room' });
				dataTable.addColumn({ type: 'string', id: 'Name' });
				dataTable.addColumn({type: 'string', role: 'title'});
				dataTable.addColumn({type: 'string', role: 'details'});
				dataTable.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
				dataTable.addColumn({ type: 'date', id: 'Start' });
				dataTable.addColumn({ type: 'date', id: 'End' });

				
				var date1 = $("#selected_date input").val();
				date1 = date1.split('-');
				date1 = date1[2] + '-' + date1[1] + '-' + date1[0];
				var newStartDate = $("#selected_end_time input").val();
				
				
				var Start_Date = $("#selected_start_time input").val()
				Start_Date = date1 + ' ' + Start_Date;
				var End_Date = date1 + ' ' + newStartDate;
				var mainArray=[];
				for (i=0; i < rowdata.length; i++){
					
					var subArray = [rowdata[i][0],
									"", 
									rowdata[i][1], 
									detail(	rowdata[i][0],
											rowdata[i][1],
											new Date(rowdata[i][2]),  
											new Date(rowdata[i][3]), 
											rowdata[i][4],
											rowdata[i][5],
											rowdata[i][6],
											rowdata[i][7],
											rowdata[i][8],
											rowdata[i][9],
											rowdata[i][10],
											rowdata[i][11],
											rowdata[i][12],
											rowdata[i][13],
											),
									createCustomHTMLContent(rowdata[i][0],
															new Date(rowdata[i][2]),
															new Date(rowdata[i][3]),
															rowdata[i][11]
															),
									new Date(rowdata[i][2]),
									new Date(rowdata[i][3]),
								]
					
					mainArray.push(subArray)
				}
					
				dataTable.addRows(mainArray);
				var options = {
					hAxis: {
					  minValue: new Date(Start_Date),
					  maxValue: new Date(End_Date)
					}
					/*backgroundColor: '#ffd',
					 focusTarget: 'category',
					tooltip: { isHtml: 'true' }, */
					/* timeline: 	{ 	colorByRowLabel: true,
									rowLabelStyle: {fontName: 'Helvetica', fontSize: 16, color: '#603913' },
									barLabelStyle: { fontName: 'Garamond', fontSize: 15 } 
								},
					 */
				};
					
					
					
				chart.draw(dataTable, options);
				function resize () {
					 chart.draw(dataTable, options);
				}
				if (window.addEventListener) {
					window.addEventListener('resize', resize);
				}
				else {
					window.attachEvent('onresize', resize);
				}

				function selectHandler(){
					var selections = chart.getSelection();
					if(selections.length == 0){
						alert('Nothing selected');
					}else{
						var selection = selections[0];
						$("#view_event_details").modal("show");
						$("#details").html(dataTable.getValue(selection.row, 3));
						/* $("#view_event_details .listen").each(function(){
							$(this).closest('.mediPlayer').mediaPlayer();
						}) */
					}
				};
				if(state != 'LastViewed'){
					google.visualization.events.addListener(chart, 'select', selectHandler);
				}
				 
				if(reportArray.length == 3 ){
					 <!-- Convert the SVG to PDF and download it -->
					var click="return xepOnline.Formatter.Format('timeline-tooltip', {render:'download', srctype:'svg'})";
					
				}
				export_report('work_pattern_analysis');
			}

			
			
			/* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			/* -----------------------------------Mouse hoved on google chart event info display ---------------------------------------------------------------------------------------------- */
			/* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			function createCustomHTMLContent(subject, start, end, contact) {
				start = moment(new Date(start));
				end = moment(new Date(end));
				var cal_duration = moment.duration(moment(end, 'HH:mm:ss').diff(moment(start, 'HH:mm:ss'))).asMilliseconds("");
				var duration = moment.utc(cal_duration).format("HH:mm:ss");
				return '<div class="infopopup" style="" >' +
				'<table class="medals_layout">' + '<tr>'  +
				'<td colspan="2"><b>' + subject + '</b><hr></td</tr><tr>' +
				'<td><b>Start Time: </b></td><td>' +  moment(start, 'HH:mm:ss').format('lll') + '</b></td></tr><tr>' +
				'<td><b>End Time: </b></td><td>' + moment(end, 'HH:mm:ss').format('lll') + '</b></td></tr><tr>' +
				'<td><b>Contact: </b></td><td>' + contact + '</b></td></tr></table></div>';
				/*  + '<tr>' + '<td><b>Comment: </b></td><td>' + duration + '</b></td>' + '</tr>'  */
			}
			
			/* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			/* -----------------------------------onclick of event details display---------------------------------------------------------------------------------------------- */
			/* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			function detail(subject, event, startT, endT, comment, actual_start_time, actual_end_time, Raiting, file_path,Prospect_Type,Prospect_Name,Contact_Name,content, Contact_Number) {
				
				var start = moment(new Date(actual_start_time));
				var end = moment(new Date(actual_end_time));
				var cal_duration = moment.duration(moment(end, 'YYYY-MM-DD HH:mm:ss').diff(moment(start, 'YYYY-MM-DD HH:mm:ss'))).asMilliseconds("");
				var duration = moment.utc(cal_duration).format("HH:mm:ss");
								
				
				var contentVal = eventContent(content , file_path, 'graph');
				
				Raiting = parseInt(Raiting);
				var rate =""; 
				for(q=0; q< Raiting; q++){
					rate +='<big><i style="margin: 5px ;" class="fa fa-star" aria-hidden="true"></i></big>'
				}
				
				if(Contact_Name == null){
					Contact_Name = "";
				}
				if(Prospect_Name == null){
					Prospect_Name = "";
				}else{
					Prospect_Name = Prospect_Name + ' ( '+capitalizeFirstLetter(Prospect_Type)+' )';
				}
				
				return '<div class="modal-header">'+
							 '<span class="close" onclick="cancel1()">x</span>'+
							 '<h4 class="modal-title"><b>' + subject + ' :</b> '+event+'</h4>'+
						'</div>'+
						'<div class="modal-body">'+	
							'<div class="row">'+
								'<div>' +
									'<table class="medals_layout" >' + 
										'<tr><td width="150"><b>Contact Name : </b></td><td> ' +  Contact_Name + '</td></tr>' +
										'<tr><td width="150"><b>Contact Number : </b></td><td> ' + Contact_Number + '</td></tr>' +
										'<tr><td width="150"><b>Prospect Name : </b></td><td> ' + Prospect_Name +' </td></tr>' +
										'<tr><td width="150"><b>Start Time : </b></td><td> ' + moment(actual_start_time, 'YYYY-MM-DD HH:mm:ss').format('lll') + '</td></tr>' + 
										'<tr><td width="150"><b>End Time : </b></td><td> ' + moment(actual_end_time, 'YYYY-MM-DD HH:mm:ss').format('lll') + '</td></tr>' + 
										'<tr><td width="150"><b>Duration : </b></td><td> ' + duration + '</b></td></tr>' + 
										'<tr><td width="150"><b>Comment : </b></td><td> ' + comment + '</b></td></tr>' + 
										'<tr><td width="150"><b>Rating : </b></td><td> ' + rate  + '</td></tr>' + 
										'<tr><td width="150"><b>'+capitalizeFirstLetter(content)+' : </b></td><td> ' + contentVal + '</td></tr>' + 
									'</table>' + 
								'</div>'+
							'</div>'+
						'</div>'+
						'<div class="modal-footer">'+
							'<input type="button" class="btn btn-default" onclick="cancel1()" value="Cancel" >'+
						'</div>';
			 
			}
			
			/* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			/* -----------------------------------generate table  report and display---------------------------------------------------------------------------------------------- */
			/* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			function generateTable(data, type){
				console.log(data)
				if(type == 'LastViewed'){
					$("#table_view").show();
				}
				var html = 	'<table class="table" border="1"><thead>';
					html +=	'<tr>'+
								'<th style="text-align:left">Activity Type</th>'+
								'<th>Activity Name</th>'+
								'<th>Prospect Name</th>'+
								'<th>Contact Name</th>'+
                                '<th>Contact Number</th>'+
								'<th>Start Time</th>'+
								'<th>End Time</th>'+
								'<th>Duration</th>'+
								'<th>Content</th>'+
							'</tr>'+
							'<tbody>';
				for(i=0; i<data.length; i++){
					
					var content = eventContent(data[i].lastkey , data[i].path, 'table');
					
					var startTime = moment(data[i].actual_startdate, 'YYYY-MM-DD HH:mm:ss');
					var endTime = moment(data[i].actual_enddate, 'YYYY-MM-DD HH:mm:ss');
					var cal_duration = moment.duration(endTime.diff(startTime)).asMilliseconds("");
					var duration = moment.utc(cal_duration).format("HH:mm:ss");
					
					var Contact_Name = data[i].Contact_Name;
					var Prospect_Name = data[i].Prospect_Name;
					
					if(Contact_Name == null){
						Contact_Name = "";
					}
					
					if(Prospect_Name == null){
						Prospect_Name = "";
					}else{
						Prospect_Name = Prospect_Name + ' ( '+capitalizeFirstLetter(data[i].Prospect_Type)+' )';
					}
					
					html +=	'<tr>'+
								'<td style="text-align:left">'+data[i].lookup_value+'</td>'+
								'<td>'+data[i].log_name+'</td>'+
								'<td>'+Prospect_Name +'</td>'+
								'<td>'+Contact_Name +'</td>'+
								'<td>'+data[i].Contact_Phone +'</td>'+
								'<td>'+startTime.format("LT")+'</td>'+
								'<td>'+endTime.format("LT")+'</td>'+
								'<td>'+duration+'</td>'+
								'<td><div>'+content+'</td></div>'+
							'</tr>'
				}
				html +=	'</tbody></table>';
				$("#table_view").html(html);
				/* 
				---- Not using plugin for mp3 player----	
					$("#table_view tr .listen").each(function(){
						$(this).closest('.mediPlayer').mediaPlayer();
					})
				*/
			}
			function eventContent(type, content, section){
				var html="";
				if(type == 'Recording'){
					if(content != "'no_path'" && content != "" && content != null ){
						html = 	'<audio controls controlsList="nodownload">'+
											'<source src="<?php echo base_url(); ?>uploads/'+content+'" type="audio/mpeg">'+
											'Your browser does not support the audio tag.'+
									'</audio>';
						
					}else{
						html= '<i title="Recording not available" class="fa fa-microphone-slash fa-2x"></i>';
					}
				}else{
					if(content != "'no_path'" && content != "" && content != null ){
						html = content;
					}else{
						html = "Not available";
					}
				}	
				return html;
			}
			/* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			/* -----------------------------------cancel detail popup---------------------------------------------------------------------------------------------- */
			/* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			function cancel1(){
				builtTimelineReport(tempReportData);
				$("#view_event_details").modal("hide");
				$('audio').each(function(){
					this.pause();
					this.currentTime = 0;
				});
			}
			
			
			/*
            moment($("#selected_date input").val(),'ll').format('YYYY-MM-DD');
            moment($("#selected_start_time input").val(),'LT').format('hh:mm:ss');
            moment($("#selected_end_time input").val(),'LT').format('hh:mm:ss');
            */
			
			
			/* --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			/* -----------------------------------change report type view---------------------------------------------------------------------------------------------- */
			/* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
			function reportType(val){
				 if($('#table_view audio').length >0 ){
					$('#table_view audio').each(function(){
						this.pause(); 
						this.currentTime = 0;
						//$(this).closest(".mediPlayer").find("svg").remove();
					});
					//$('.mediPlayer').mediaPlayer();
				}
				
				if(val == "text"){
					 $("#table_view").show();
					 $("#timeline-tooltip").hide();
				}else{
					$("#timeline-tooltip").show();
					$("#table_view").hide()

				} 
			}
/* ------------------------------------------------------------------------------ */
/* ------------------------------------------------------------------------------ */
	
			</script>

    </head>
	<body class="hold-transition skin-blue sidebar-mini">   
		<!--<div class="loader">
			<center><h1 id="loader_txt"></h1></center>
	</div>-->
        <?php require __DIR__.'/../demo.php' ?>
        <?php require __DIR__.'/../manager_sidenav.php' ?>          
                 
        <div class="content-wrapper body-content">
        	<div class="col-lg-12 column">
			<?php require __DIR__.'/report_header.php' ?>
			<div class="page-container">
				
				<!--------------------------------------------------------- report setting section ---------------------------------------------------------------------->
				<div class="row">						
					<div class="col-md-10 no-padding" >
						<div class="col-md-1">
							<label for="user_name">User*</label>
						</div>
						<div class="col-md-2">
							<select class="form-control" id="user_name" onchange="gettime()" ></select>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-1">
							<label for="selected_date">Date*</label>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class='input-group date' id='selected_date'>
									<input type='text' class="form-control"  onfocusout="datechange()"/>
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<span class="error-alert"></span>
							</div>
						</div>
						<div class="col-md-1">
							<label for="selected_start_time">Start Time*</label>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class='input-group date' id='selected_start_time'>
									<input type='text' class="form-control" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-time"></span>
									</span>
								</div>
								<span class="error-alert"></span>
							</div>
						</div>
						<div class="col-md-1">
							<label for="selected_end_time">End Time*</label>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<div class='input-group date' id='selected_end_time'>
									<input type='text' class="form-control" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-time"></span>
									</span>
								</div>
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
					<div class="col-md-2 no-padding" >
					<span class="pull-right">
						<input type="button" class="btn btn-default" id="generate_save_btn" onclick="generateReport()" value="Generate">
						<input type="button" class="btn btn-default" onclick="cancel_report_setting()" value="Clear" >
						<span>
					</div>
				</div>
				
				<div class="row print">
				<!---------------------------- ----------------------- report Setting section ----------------------------->
					
					<div class="col-md-12 no-padding none" id="chart_type">
						<h4>Configure Report</h4><hr>
						<div class="row" >
							<div class="row">
								<div class="col-md-3">
									<div>
										<select id="rep_typ_list" class="form-control" onchange="reportType(this.value)">
											<option value="line">Time Line Chart</option>
											<option value="text">Text Chart</option>
										</select>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="col-md-3">
									<div>
										<input onkeyup="valueChange()"type="text" class="form-control" id="report_name" placeholder="Report Name (Mandatory)" />
										<span class="error-alert"></span>
									</div>
								</div>
								
								<div class="col-md-6 action_btn">
									<div class="text-left pull-left">
										<!--<center>-->
											<button class="btn btn-default none" id="update_report_btn" onclick="saveReport('update')">Save</button>
											<button class="btn btn-default none" id="saveAs_report_btn" onclick="saveReport('saveAs')">Save As</button>
										<!--</center>
										<center>-->
											<button class="btn btn-default none" id="save_report_btn" onclick="saveReport('save')">Save Report</button>
										<!--</center>-->
									</div>
									<div class="no-padding pull-right" id="export_to"></div>
								</div>
							</div>
						</div>
					</div>
				
				</div>
				
				<div class="row print">
<!-------------------------------- report section ---------------------------------------------------------------------->
					<div class="col-md-12 no-padding print" id="canvas">
						<center><h4 id="report_title"></h4></center>
						<div id="timeline-tooltip" class ="print" style="display:none;"></div>
						<div id="table_view" class ="print none"></div>
						<div id="editor"></div>
					</div>
				</div>
			</div>
			
			<div id="view_event_details" class="modal fade in" data-backdrop="static" aria-hidden="false">
				<div class="modal-dialog">
					<div class="modal-content" id ="details">
						
					</div>
				</div>
			</div>
        </div>
        <?php require __DIR__.'/../footer.php' ?>
		
		
    </body>
</html>