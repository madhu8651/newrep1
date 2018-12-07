<!DOCTYPE html>
<html lang="en">
    <head>
		<?php require __DIR__.'/../scriptfiles.php' ?>

		<script type="text/javascript">
			var leadDate ={};
			var data_req ={};
			var reportData={};
			$(document).ready(function(){
				list_view();
				$('#start_date').datetimepicker({
                    format: 'DD-MM-YYYY',
					minDate: moment("01-01-2000", 'DD-MM-YYYY')
                });
				$('#end_date').datetimepicker({
                    format: 'DD-MM-YYYY',
					minDate: moment("01-01-2000", 'DD-MM-YYYY'),

                });
				$("#start_date").on("dp.change", function (selected) {
					//$(this).closest(".row").find("#end_date").data("DateTimePicker").clear();
					var startDateTime = moment($("#start_date input").val(),'DD-MM-YYYY');
					if($("#start_date input").val().trim() == ""){
						startDateTime=moment();
					}
					$(this).closest(".row").find("#end_date").data("DateTimePicker").minDate(startDateTime);
					valueChange('startDate');
				});

				$("#end_date").on("dp.change", function (selected) {
					valueChange('endDate');
				});

				$("#start_date input").val(moment().format("DD-MM-YYYY"));
				$("#end_date input").val(moment().format("DD-MM-YYYY"));
				$("#end_date").data("DateTimePicker").minDate(moment().format("DD-MM-YYYY"));
				get_heirarchy(reportArray[0]);

				/* --------------------------------------------------------------------- */


				if(reportArray.length == 2 ){
					if (getCookie("reportOutput") != "" && getCookie("reportInput") !="") {
						buildHtmlTable(JSON.parse(getCookie("reportOutput")),"#excelDataTable" , JSON.parse(getCookie("reportInput")), "Last viewed")
						reportData=JSON.parse(getCookie("reportInput"));
					}
				}

				/* --------------------------------------------------------------------- */

			});


			/* ---------------------------------------------clear button click-------------------------------------- */

			function cancel_report_setting(){
				$("#canvas,#chart_type,#report_title").css("visibility", "hidden");
				$("#report_input_data input, #report_input_data select").val("");
				$("#report_input_data .error-alert").text("");
				$("#start_date input").val(moment().format("DD-MM-YYYY"));
				$("#end_date input").val(moment().format("DD-MM-YYYY"));
				$("#end_date").data("DateTimePicker").minDate(moment().format("DD-MM-YYYY"));
                $("#start_date .error-alert").text("");
                $("#end_date .error-alert").text("");
				valueChange('all');
			}
			/* ---------------------------------------------onchange of saved value-------------------------------------- */
			function valueChange(element){
					var obj={};
					/* set  lead Inception Date*/
					if(element == 'lead'){
						leadDate.forEach(e => {
							if(e.lead_id == $("#sub_user").val()){
								$("#start_date input").val(e.leadInceptionDate);
								//$("#end_date input").val(e.leadInceptionDate);
								//$("#end_date").data("DateTimePicker").minDate(e.leadInceptionDate);
							}
						})
					}
					obj.lead_id = $("#sub_user").val();
					obj.startdate  = $("#start_date input").val();
					obj.enddate = $("#end_date input").val();
					obj.report_name = $("#report_name") .val();
					if($.trim(obj.startdate) == '' || $.trim(obj.enddate) == ''){
                        obj.startdate  = 'NA';
					    obj.enddate = 'NA';
					}

					var changedInput=0;
					if(reportArray.length == 3 ){
						if( obj.lead_id == reportData.lead_id && obj.startdate == reportData.startdate && obj.enddate == reportData.enddate ) {
							changedInput=0;

							$("#canvas, #chart_type,#report_title").css("visibility", "visible");
							$("#save_report_btn").hide();
							$("#saveAs_report_btn , #update_report_btn").show().removeAttr("disabled","disabled" );
							$("#generate_save_btn").attr("disabled" , "disabled");
						}else{
							changedInput=1;

							$("#canvas,#chart_type,#report_title").css("visibility", "hidden");
							$("#save_report_btn").hide();
							$("#saveAs_report_btn, #update_report_btn").show().attr("disabled","disabled" );
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

					if(reportArray.length == 2 || data_req.hasOwnProperty("lead_id")){

						if( obj.lead_id == reportData.lead_id && obj.startdate == reportData.startdate && obj.enddate == reportData.enddate ) {
							$("#canvas,#chart_type,#report_title").css("visibility", "visible");
							//$("#save_report_btn").show();
							$("#generate_save_btn").attr("disabled","disabled" );
						}else{
							$("#canvas,#chart_type,#report_title").css("visibility", "hidden");
							$("#save_report_btn").hide();
							$("#generate_save_btn").removeAttr("disabled");
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
			/* ---------------------------------------------function to display the saved report-------------------------------------- */
			function savedreportdisplay(){
				if(reportArray.length == 3 ){
					$("#previousPage").html('<h4><a class="btn" href="<?php echo site_url('manager_standard_analytics'); ?>#savedReport='+report+'"><i class="fa fa-arrow-left fa-3" aria-hidden="true"></i> Back</a></h4>')

					var obj1={};
                    obj1.id = reportArray[reportArray.length-1];
                    obj1.type='Lead_Average_Activity_Cost';
					obj1.subtype='Summary';
					$.ajax({
						type: "POST",
						url:"<?php echo site_url('reports/standard_LeadAnalysisController/getsaved_reports')?>",
						data : JSON.stringify(obj1),
						dataType:'json',

						success: function(data){
							if(error_handler(data)){
								return;
							}

							reportData={};
							reportData.lead_id = data.lead_id;
                            if(data.startdate != 'NA' && data.enddate != 'NA'){
                                reportData.startdate = moment(data.startdate,'YYYY-MM-DD').format('DD-MM-YYYY');
							    reportData.enddate = moment(data.enddate,'YYYY-MM-DD').format('DD-MM-YYYY');
                                $("#start_date input").val(reportData.startdate);
							    $("#end_date input").val(reportData.enddate);
                            }else{
                                 reportData.startdate = 'NA';
							     reportData.enddate = 'NA';
                                 $("#start_date input").val('');
							     $("#end_date input").val('');
                            }

							reportData.type = 'Lead_Average_Activity_Cost';
							reportData.report_name = data.report_nme;

							if(data.hasOwnProperty('resourceid')){
								reportData.resourceid = data.resourceid;
								reportData.subtype='details';
							}else{
								reportData.subtype='Summary';
							}

							$("#sub_user").val(data.lead_id);
							$("#report_name").val(data.report_nme);

							data_req.lead_id = data.lead_id;

                            if(data.startdate != 'NA' && data.enddate != 'NA'){
                                data_req.startdate = moment(data.startdate,'YYYY-MM-DD').format('DD-MM-YYYY');
							    data_req.enddate = moment(data.enddate,'YYYY-MM-DD').format('DD-MM-YYYY');
                            }else{
                                 data_req.startdate = 'NA';
							     data_req.enddate = 'NA';
                            }
                            data_req.report_name = data.report_nme;
							data_req.type='Lead_Average_Activity_Cost';
							if(data.hasOwnProperty('resourceid')){
								buildHtmlTable(data.tabledetails,"#excelDataTable" , reportData, "Details");
							}else{
								buildHtmlTable(data.tabledetails,"#excelDataTable" , reportData, "Saved");
							}

						},
						error:function(data){
							network_err_alert(data);
						}
					})
				}
            }

			/* --------------------------------------------------------------------------------------------------------
			----------------------------------get employees List function----------------------------------------------
			------------------------------------------------------------------------------------------------------------ */
            function list_view(){
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_LeadAnalysisController/get_leads')?>",
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
						var select = $("#sub_user"), options = "<option value=''>Select</option>";
						select.empty();
						for(var i=0;i<data.length; i++){
							options += "<option value='"+data[i].lead_id+"'>"+ data[i].lead_name+"</option>";

						}
						select.append(options);
						leadDate = data;
						savedreportdisplay();
						if(getCookie("reportInput") != ''){
							var prviouse_data =  $.parseJSON(getCookie("reportInput"));
							$("#sub_user option[value='"+prviouse_data.lead_id+"']").attr("selected", true);
							$("#start_date input").val(prviouse_data.startdate == "NA"?"": moment(prviouse_data.startdate, 'DD-MM-YYYY').format('DD-MM-YYYY'));
							$("#end_date input").val(prviouse_data.enddate == "NA"?"": moment(prviouse_data.enddate, 'DD-MM-YYYY').format('DD-MM-YYYY'));
							
							if(prviouse_data.startdate != "NA"){
								$("#end_date").data("DateTimePicker").minDate(prviouse_data.startdate);
							}
							
						}
						loaderHide();
					},
					error:function(data){
						network_err_alert(data);
					}
				});
			}
			/* --------------------------------------------------------------------------------------------------------
			----------------------------------get employees List function----------------------------------------------
			------------------------------------------------------------------------------------------------------------ */
			function generateReport(){
				if($("#sub_user").val() == ""){
					$("#sub_user").next(".error-alert").text("Select a lead.");
					return;
				}else{
					$("#sub_user").next(".error-alert").text("");
				}

				if($("#start_date input").val() != ""){
					if($("#end_date input").val() == ""){
						$("#end_date").next(".error-alert").text("Select End date.");
						return;
					}else{
						$("#end_date").next(".error-alert").text("");
					}
				}


				var obj={};
				reportData={};

				obj.lead_id = $("#sub_user").val();
				obj.lead_name = $("#sub_user option:selected").text();
				if($("#start_date input").val() != ""){
                    obj.startdate = moment($("#start_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
                    obj.enddate = moment($("#end_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
				}else{
                     obj.startdate = 'NA';
                     obj.enddate = 'NA';
				}



				obj.type='Lead_Average_Activity_Cost';
                obj.subtype='Summary';
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_LeadAnalysisController/generateReport')?>",
				   	data : JSON.stringify(obj),
					dataType:'json',
					success: function(data){
						var temp=[];
						if(error_handler(data)){
							return;
						}
						if(data.length > 0){
							$(".graph_view").css("height","450px");
							$("#details_btn").show();
							buildHtmlTable(data,"#excelDataTable" , obj, "Generated");
							reportData = obj;
							data_req = obj;
						}else{
							$("#report_title").css({"visibility": "visible","display": "block"}).html("<center>No records available for the selected time duration.</center>");
							$("#piechart_3d").empty();
							$(".graph_view").css("height","0px");
							$("#details_btn").hide();
						}
					},
					error:function(data){
						network_err_alert(data);
					}
				});
			}

			/* -================================================================================================================== */
			/* -================================================================================================================== */
			function go_back(){
				$("#details_btn").show();
				generateReport();
				$("#piechart_3d").show();
				$("#table_view").hide();
				$("#previousPage").html("");
			}
			function buildHtmlTable(myList,selector, inputdata, state) {
				if(state != 'Last viewed'){
					if(reportArray.length == 2 ){
						setCookie('reportOutput', JSON.stringify(myList),1);
						setCookie('reportInput', JSON.stringify(inputdata),1);
					}
				}
				
				if(state == "Detailed"){
					table(myList, selector, inputdata, state)
				}else{
					if(inputdata.subtype == "details"){
						text_chart(myList, selector, state, inputdata.subtype);
					}else{
						format(myList,inputdata, state);
						text_chart(myList, selector, state, "summary");
						$("#canvas,#chart_type,#report_title").css("visibility", "visible");
					}					
				}


				var name = $("#sub_user option:selected").text();
                
				if(state == "Last viewed"){
					$("#details_btn").hide();
					if(inputdata.startdate == 'NA'){
                     $("#report_title").html(state+" Analysis for <u>"+ inputdata.lead_name +"</u> (Lead)");
				    }else{
                       $("#report_title").html(state+" Analysis for <u>"+ name +"</u> (Lead) From <u>"+ moment(inputdata.startdate, 'DD-MM-YYYY').format(dateFormat) +"</u>  To <u>"+ moment(inputdata.enddate, 'DD-MM-YYYY').format(dateFormat) +"</u>");
				    }


				}else{
                    if(inputdata.startdate == 'NA'){
						if(state == "Detailed"){
							$("#report_title").html("Detailed Analysis for <u>"+ name +"</u> (Lead)");
						}else{
							$("#report_title").html(state+" Analysis for <u>"+ name +"</u> (Lead)");
						}
                       
                    }else{
						if(state == "Detailed"){
							$("#report_title").html("Detailed Analysis for <u>"+ name +"</u> (Lead) From <u>"+ moment(inputdata.startdate, 'DD-MM-YYYY').format(dateFormat) +"</u>  To <u>"+ moment(inputdata.enddate, 'DD-MM-YYYY').format(dateFormat) +"</u>");
						}else{
							$("#report_title").html(state+" Analysis for <u>"+ name +"</u> (Lead) From <u>"+ moment(inputdata.startdate, 'DD-MM-YYYY').format(dateFormat) +"</u>  To <u>"+ moment(inputdata.enddate, 'DD-MM-YYYY').format(dateFormat) +"</u>");
						}                         
    		        }
				}

                if(reportData.activityid == "All"){
                    if(inputdata.startdate == 'NA'){
                       $("#report_title").html(state+" Analysis is for <u>All</u>");
                    }else{
                         $("#report_title").html(state+" Analysis is for <u>All</u>  From <u>"+ moment(inputdata.startdate, 'DD-MM-YYYY').format(dateFormat) +"</u>  To <u>"+ moment(inputdata.enddate, 'DD-MM-YYYY').format(dateFormat) +"</u>");
    		        }
                }


				if(reportArray.length == 2 ){

					if(state == "Generated"){
						$("#canvas, #chart_type, #report_title").css({"visibility": "visible","display": "block"});
						$("#generate_save_btn").attr("disabled" , "disabled");
						$("#save_report_btn").show().removeAttr("disabled");
					}
				}
				if(reportArray.length == 3 ){
					$("#canvas, #chart_type, #report_title").css({"visibility": "visible","display": "block"});
					$("#generate_save_btn").attr("disabled" , "disabled");
					if(state == "Generated"){
						$("#save_report_btn").hide();
						$("#saveAs_report_btn, #update_report_btn").show().removeAttr("disabled");
					}

					if(state == "Saved"){
						$("#save_report_btn").hide();
						$("#saveAs_report_btn, #update_report_btn").show();
					}

					if(state == "Details"){
						$("#save_report_btn").hide();
						$("#saveAs_report_btn, #update_report_btn").show();
					}
				}

				$('#export_to').html("");

				//$('#export_to').append('<button class="btn" title="PDF" onclick="gimg()"><span class="fa fa-file-pdf-o"></span> PDF  </button>');
				//$('#export_to').append('<button class="btn" title="CSV" ><span class="fa fa-file-excel-o"></span> CSV  </button>');
				$('#export_to').append('<button class="btn" title="Print Report" onclick="window.print();" ><span class="fa fa-print"></span> Print Report  </button>');
				//$('#export_to').append('<button class="btn" title="Schedule Report"><span class="fa fa-clock-o"></span> Schedule  </button>');
			}
			function table(myList, selector, inputdata, state){

				google.charts.load('current', {
					'packages': ['corechart']
				});
				google.charts.setOnLoadCallback(drawChart);

				function drawChart(){
				var row_num = [];
				data = new google.visualization.DataTable();
				data.addColumn({ type: 'string', id: 'lname' });
				data.addColumn({ type: 'number', id: 'activity_cost' });
				data.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'Html': true}});
				var total_sum = 0;
				for(i = 0; i < myList.length; i++){
					total_sum = total_sum + parseFloat(myList[i].Total_Sum);
					data.addRow([myList[i].Resource_Name.split("_").join(" "), parseFloat(myList[i].Total_Average), "Total Sum Rs. " + myList[i].Total_Sum + "\nTotal activity average " + myList[i].Total_Average]);
				}	
				options = {
					vAxis: {
					  title: 'Average'
					}
				};
				var chart = new google.visualization.ColumnChart(document.getElementById('piechart_3d'));
					chart.draw(data, options);
					var selectHandler1 = function(e) {
						$('#piechart_3d').css('cursor','pointer')
					}
					if(state != "Last viewed"){
						google.visualization.events.addListener(chart, 'onmouseover', selectHandler1);
					}
				}

			}
			function addAllColumnHeaders(myList, selector) {
				var columnSet = [];
				for (var i = 0; i < myList.length; i++) {
				var rowHash = myList[i];
				for (var key in rowHash) {
					if ($.inArray(key, columnSet) == -1) {
						columnSet.push(key);
					}
				}
			  }
			  return columnSet;
			}
			/* ----------------------------------------------------------------------------------------------------------------- */
			/* -----------------------------------save / update / save as function--------------------------------------------- */
			/* ----------------------------------------------------------------------------------------------------------------- */
			function saveReport(state){
				save(state)
			}
			function save(state){
				reportData.report_name = $.trim($("#report_name").val());
				if(reportData.report_name == ""){
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

				if(reportArray.length < 2 ){
					return;
				}
				var id ="";
                reportData.reportid = reportArray[0];
                reportData.report_parent_id = reportArray[1];
				if(reportArray.length == 2 || state=='saveAs'){
                   reportData.id='0';
				}

				if(reportArray.length == 3 && state=='update'){
					reportData.id = reportArray[reportArray.length-1];
					reportData.type='Lead_Average_Activity_Cost';
				}

				var reportType = "";
				if(reportData.subtype == "details"){
					setCookie('reportType', JSON.stringify(reportData.subtype),1);
				}
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_LeadAnalysisController/save_report')?>",
					data : JSON.stringify(reportData),
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
						if(data == 0){
							alert("Report has not been saved succesfully");
							location.reload();
						}else{
							if(state=='saveAs' || state=='save'){
								alert("Report has been saved succesfully");
							}else if(state=='update'){
								alert("Report has been updated succesfully");
							}
						   	location.reload();
							$("#report_name").val("");
						}
					},
					error:function(data){
						network_err_alert(data);
					}
				})
			}


			</script>

			<script type="text/javascript">
				function format(data , inputdata , state){
					
						
						$("#piechart_3d").show();
						$("#table_view").hide();
						var columnSet2 = [];
						for (var i = 0; i < data.length; i++) {
							var rowHash = data[i];
							var columnSet1 = [];
							for (var key in rowHash){
									columnSet1.push(rowHash[key]);
							}
							columnSet2.push(columnSet1);
						}					
						var columnSet4 = [];
						for(i = 0; i < columnSet2.length; i++){
						var columnSet3 = [];
							columnSet3.push(columnSet2[i][0]);
							columnSet3.push(columnSet2[i][4]);
							columnSet3.push(columnSet2[i][1]);
							
							columnSet4.push(columnSet3);
						}
						google.charts.load('current', {
							'packages': ['corechart']
						});
						google.charts.setOnLoadCallback(drawChart);

						function drawChart(){
						var row_num = [];
						data = new google.visualization.DataTable();
						data.addColumn({ type: 'string', id: 'lname' });
						data.addColumn({ type: 'number', id: 'lead_count' });
						data.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'Html': true}});
						data.addColumn({type: 'string', id: 'lead_count1', role: 'style' });
                        var total = total1=0;

                        for(jj=0;jj<columnSet2.length;jj++){
							columnSet2[jj][2] = parseFloat(columnSet2[jj][2]);
							total =  total + columnSet2[jj][2];
							
							columnSet2[jj][3] = parseInt(columnSet2[jj][3]);
							total1 =  total1 + columnSet2[jj][3];
							
                        }
						var avg = total/ total1 ; 
                        $('#report_title').append('<span><b><h3 style="display: inline;"> & </h3><i>total activity average '+avg.toFixed(2)+'</i></b></span>');
						
						
						for(j=0;j<columnSet4.length;j++){
							data.addRow([   columnSet4[j][0].split("_").join(" "),
											parseFloat(columnSet4[j][1]),
											columnSet4[j][0].split("_").join(" ")+"\nAverage Cost Rs. " + parseFloat(columnSet4[j][1]).toFixed(2),
											columnSet4[j][2]
							]);
						}
						var report_title =state+" Analysiss for "+ inputdata.lname +" On "+ moment(inputdata.startdate, "DD-MM-YYYY").format(dateFormat);


						var options = {
							legend: {position: "none"},
							vAxis: {
							  title: 'Average'
							}
						};

						var chart = new google.visualization.ColumnChart(document.getElementById('piechart_3d'));
							chart.draw(data, options);
							var selectHandler = function(e) {
							var selectedItem = chart.getSelection()[0];
							if (selectedItem) {
								$("#details_btn").hide();
								$("#previousPage").html('<h4><a class="btn" href="#" onclick="go_back()"><i class="fa fa-arrow-left fa-3" aria-hidden="true"></i> Back</a></h4>');

								var topping = data.getValue(selectedItem.row, 3);
									$("#table_view").hide();
									$("#chart_select").hide();
									$("#previousPage").html('<h4><a class="btn" href="#" onclick="go_back()"><i class="fa fa-arrow-left fa-3" aria-hidden="true"></i> Back</a></h4>');
									reportData["activityid"] = topping;
									reportData["subtype"] = "details";
									if(reportArray.length == 2 || reportArray.length == 3){
										$.ajax({
    										type: "POST",
    										url:"<?php echo site_url('reports/standard_LeadAnalysisController/generateReport')?>",
    										data : JSON.stringify(reportData),
    										dataType:'json',
    										success: function(data){
    										    $('#canvas #details_btn').remove();
    											buildHtmlTable(data,"#excelDataTable" , reportData, "Detailed");
    										},
    										error:function(data){
    											network_err_alert(data);
    										}
									    })
									}
							}
							chart.setSelection([]);
						}
							var selectHandler1 = function(e) {
								$('#piechart_3d').css('cursor','pointer')
							}
							if(state != "Last viewed"){
								google.visualization.events.addListener(chart, 'select', selectHandler);
								google.visualization.events.addListener(chart, 'onmouseover', selectHandler1);
							}
						}
                        $('#canvas #details_btn').remove();
					   /*$('#canvas').append('<center style="position: absolute; top: 0px;margin-top: 4px;" id="details_btn"><input class="btn" type="button" onclick="details()" value="Show Details"><center>');   */
					}

					function text_chart(myList, selector, state, type){
						
						if(state != "Last viewed"){
							text_view(myList, selector, state, type);
						}
						if(type == "details"){
							table(myList, selector, state);
						}else{
							text_view(myList, selector, state, type);
						}
					}
					function text_view(myList, selector, state, type){
                         
						if(type == "details"){
							$("#piechart_3d").hide();
						}else{
							$("#piechart_3d").show();
						}
						$("#table_view").show();
						$(selector).html("")
						var columns = addAllColumnHeaders(myList, selector);
						var row$ = '';
						var cell="";
						for (var c = 0; c < columns.length; c++) {
							if(columns[c] == "Note"){
								cell += '<th style="text-align: left;width:200px;">'+ columns[c].split("_").join(" ") +'</th>';
							}else{
								if(columns[c] != "lid"){		
									if( columns[c] != "lookup_id"){
										cell += '<th style="text-align: left;width:135px; border-left:1px solid white;">'+ columns[c].split("_").join(" ") +'</th>';
									}							
								}
							}
						}
						$(selector).append('<thead/>');
						$(selector +" thead").append('<tr>'+cell+'</tr>');
						$(selector).append('<tbody/>');
						var total = total1=0;
						var isPlayer = 0, tableRow="";
						for (var i = 0; i < myList.length; i++) {
							var datacell="";
							for (var colIndex = 0; colIndex < columns.length; colIndex++) {
								var cellValue = myList[i][columns[colIndex]];
								if(columns[colIndex] != "lid"){		
									if( columns[colIndex] != "lookup_id"){
										datacell += '<td style="text-align: left;">'+ cellValue +'</td>';
									}
								}
							}
							tableRow = "<tr>"+datacell+"</tr>";
							$(selector +" tbody").append(tableRow);
							/* -------------------------- */
							myList[i].Total_Sum = parseFloat(myList[i].Total_Sum);
							total =  total + myList[i].Total_Sum;
							
							myList[i].Activity_Count = parseInt(myList[i].Activity_Count);
							total1 =  total1 + myList[i].Activity_Count;
							/* -------------------------- */
						}
						if(isPlayer == 1){
							$('.mediPlayer').each(function(){
								$(this).mediaPlayer();
							})
						}
						
						/* ===============setup add total row ================= */
						

						var avg = total/ total1 ; 
						var addRowObj={};
						addRowObj['Activity_Name']= "<b>Overall</b>";
						addRowObj['Total_Sum']= "<b>"+total+"</b>";
						addRowObj['Activity_Count']= "<b>"+total1+"</b>";
						addRowObj['Total_Average']= "<b>"+avg.toFixed(2)+"</b>";
						
						var removeClo =["lookup_id"];
						addRow(addRowObj,selector, removeClo);
						
						/* var filter={  
						  "Activity_Count":{"type":"int", "value":"0"},
						  "Total_Average":{"type":"float", "value":"0"}
						}
						var label =[];
						$.each(myList[0], function(key1, row){
							label.push(key1);
						})
						addRow(addArray(filter, myList, label[0]), selector, removeClo); */
						/* ================================ */
					}
				
                
				function createCustomHTMLContent(subject, time) {
					return '<div class="infopopup" style="" >' +
						'<table class="medals_layout">'  +
							'<tr><td colspan="2"><b>' + subject + '</b><hr></td></tr>'+
							'<tr><td><b>Duration: </b></td><td>' +  time + '</b></td></tr>'+
						'</table>' +
					'</div>';
				}

				function reportType(val){
					if($('#table_view audio').length >0 ){
						$('#table_view audio').each(function(){
							this.pause();
							this.currentTime = 0;
							$(this).closest(".mediPlayer").find("svg").remove();
						});
						$('.mediPlayer').mediaPlayer();
					}

					if(val == "text"){
						 $("#canvas .table_view").show();
						 $("#canvas .graph_view").hide();
					}else{
						$("#canvas .graph_view").show();
						$("#canvas .table_view").hide()

					}
				}
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
				<div class="row report_input_data">
					<div class="col-md-10 no-padding" id="report_input_data">
						<div class="col-md-4">
							<label for="sub_user">Lead*</label>
							<select class="form-control" id="sub_user" onchange="valueChange('lead')">
							</select>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-4">
							<label for="start_date">From</label>
							<div class="form-group">
								<div class='input-group date' id='start_date'>
									<input type='text' class="form-control" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<span class="error-alert"></span>
							</div>
						</div>
						<div class="col-md-4">
							<label for="end_date">To</label>
							<div class="form-group">
								<div class='input-group date' id='end_date'>
									<input type='text' class="form-control" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<span class="error-alert"></span>
							</div>
						</div>

					</div>
					<div class="col-md-2 no-padding">
						<span class="pull-right">
							<input type="button" class="btn btn-default" id="generate_save_btn" onclick="generateReport()"  value="Generate">
							<input type="button" class="btn btn-default" onclick="cancel_report_setting()" value="Clear" >
						</span>
					</div>
				</div>
				<div class="row print">
				<!---------------------------- ----------------------- report Setting section ----------------------------->

					<div class="col-md-12 no-padding none" id="chart_type">
						<h4>Configure Report</h4><hr>
						<div class="row " >
							<div class="row">
								<div class="col-md-3">
									<!--<div>
										<select class="form-control" id="chart_select" onchange="reportType(this.value)">
											<option value="line">Time Line Chart</option>
											<option value="text">Text Chart</option>
										</select>
										<span class="error-alert"></span>
									</div>-->
								</div>
								<div class="col-md-3">
									<div>
										<input onkeyup="valueChange('reportName')"type="text" class="form-control" id="report_name" placeholder="Report Name (Mandatory)" />
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="col-md-6 action_btn">
									<div class="no-padding pull-left">
										<center>
											<button class="btn btn-default none" id="update_report_btn" disabled onclick="saveReport('update')">Save</button>
											<button class="btn btn-default none" id="saveAs_report_btn" disabled onclick="saveReport('saveAs')">Save As</button>
										</center>
										<center>
											<button class="btn btn-default none" id="save_report_btn" disabled onclick="saveReport('save')" >Save Report</button>
										</center>
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
						<div class="print graph_view" id="piechart_3d" ></div>
						<div class ="print table_view none" id="table_view" >
							<table class="table" id="excelDataTable" border="1"></table>
							<table class="table" id="excelDataTable1" border="1"></table>
						</div>
					</div>
				</div>
				</div>

			</div>
        </div>

        <?php require __DIR__.'/../footer.php' ?>


    </body>
</html>




