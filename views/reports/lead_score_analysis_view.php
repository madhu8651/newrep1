<!DOCTYPE html>
<html lang="en">
    <head>
		<?php require __DIR__.'/../scriptfiles.php' ?>
		
		<script type="text/javascript">
				
			var data_req ={};
			$(document).ready(function(){
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
					valueChange();
				});
				
				$("#end_date").on("dp.change", function (selected) {					
					valueChange();
				});
				
				/* $("#start_date input").val(moment().format("DD-MM-YYYY"));
				$("#end_date input").val(moment().format("DD-MM-YYYY")); */
               
				get_heirarchy(reportArray[0]);
				if(getCookie("reportInput") != ''){
					var prviouse_data =  $.parseJSON(getCookie("reportInput"));
					list_view(prviouse_data.selectiontype, prviouse_data.selectionid);
					console.log(prviouse_data)
					$("#selection_type option[value='"+prviouse_data.selectiontype+"']").attr("selected", true);
					$("#start_date input").val(prviouse_data.startdate == 'NA'? "" : moment(prviouse_data.startdate, 'DD-MM-YYYY').format('DD-MM-YYYY'));
					$("#end_date input").val(prviouse_data.enddate == 'NA'? "" : moment(prviouse_data.enddate, 'DD-MM-YYYY').format('DD-MM-YYYY'));
				}
				/* --------------------------------------------------------------------- */
				if(reportArray.length == 2 ){
					if (getCookie("reportOutput") != "" && getCookie("reportInput") !="") {
						buildHtmlTable(JSON.parse(getCookie("reportOutput")),"#excelDataTable" , JSON.parse(getCookie("reportInput")), "Last viewed")
						//reportData=JSON.parse(getCookie("reportInput"));
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
                $("#start_date .error-alert").text("");
                $("#end_date .error-alert").text("");
				valueChange();
			}
			/* ---------------------------------------------onchange of saved value-------------------------------------- */
			function valueChange(){
					var obj={};
					obj.selectiontype = $("#selection_type").val();
					obj.selectionid = $("#sub_user").val();
					obj.startdate  = $("#start_date input").val();
					obj.enddate = $("#end_date input").val();
					obj.report_name = $("#report_name") .val();
					if($.trim($("#start_date input").val()) == '' || $.trim($("#end_date input").val()) == ''){
                        obj.startdate  = 'NA';
					    obj.enddate = 'NA';
					}
					var changedInput=0;
					if(reportArray.length == 3 ){						
						if( obj.selectiontype == reportData.selectiontype &&
						obj.selectionid == reportData.selectionid &&
						obj.startdate == reportData.startdate &&
						obj.enddate == reportData.enddate) {
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
					
					if(reportArray.length == 2 || data_req.hasOwnProperty("selectiontype")){
						console.log(data_req)
						if( obj.selectiontype == reportData.selectiontype && 
						obj.selectionid == reportData.selectionid &&
						obj.startdate == reportData.startdate &&
						obj.enddate == reportData.enddate) {
							$("#canvas,#chart_type,#report_title").css("visibility", "visible");
							$("#save_report_btn").show();
							$("#generate_save_btn").attr("disabled","disabled" );
						}else{
							$("#canvas,#chart_type,#report_title").css("visibility", "hidden");
							$("#save_report_btn").hide();
							$("#generate_save_btn").removeAttr("disabled");
						}
						if(data_req.hasOwnProperty("subtype")){
							$("#save_report_btn").show();
						}else{
							$("#save_report_btn").hide();
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
					$("#previousPage").html('<h4 style="position: absolute;"><a class="btn" href="<?php echo site_url('manager_standard_analytics'); ?>#savedReport='+report+'"><i class="fa fa-arrow-left fa-3" aria-hidden="true"></i> Back</a></h4>')
					
					var obj1={};
                    obj1.id = reportArray[reportArray.length-1];
                    obj1.type='Lead_Score_Analysis';
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
							reportData.type = 'Lead_Score_Analysis';
							
							if(data.lid){	
								reportData.lid = data.lid;
								reportData.subtype='details';	
							}else{								
								reportData.subtype='Summary';
							}
							
							$("#sub_user").val(data.lead_id);
							$("#start_date input").val(reportData.startdate);
							$("#end_date input").val(reportData.enddate);
							$("#report_name").val(data.report_nme);
							data_req.lead_id = data.lead_id;
							if(data.startdate != 'NA' && data.enddate != 'NA'){
                                data_req.startdate = moment(data.startdate,'YYYY-MM-DD').format('DD-MM-YYYY');
							    data_req.enddate = moment(data.enddate,'YYYY-MM-DD').format('DD-MM-YYYY');
                            }else{
                                 data_req.startdate = 'NA';
							     data_req.enddate = 'NA';
                            }
							data_req.type='Lead_Score_Analysis';
							if(data.lid){
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
            function list_view(selection, selection_id){
				if(selection_id == ''){
					valueChange();
				}			
				$("#sub_user").next(".error-alert").text("");
				$("#sub_user_lbl").text(capitalizeFirstLetter(selection)+"*");
				
				var obj={};
				obj.selection_type = selection;
				if(obj.selection_type == ""){
					$("#sub_user_lbl").text("");
					$("#sub_user").html('');
					$("#sub_user")
					return;
				}
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_LeadAnalysisController/get_details')?>",
					dataType:'json',
					data: JSON.stringify(obj),
					success: function(data){
						if(error_handler(data)){
							return;
						}
						var select = $("#sub_user"), options = "<option value=''>Select</option>";
						select.empty();
						for(var i=0;i<data.length; i++){
							options += "<option value='"+data[i].id+"'>"+ data[i].name+"</option>";

						}
						select.append(options);						
						$("#sub_user option[value='"+selection_id+"']").attr("selected", true);
						savedreportdisplay();
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
				if($("#selection_type").val() == ""){
					$("#selection_type").next(".error-alert").text("Select a Type.");
					return;
				}else{
					$("#selection_type").next(".error-alert").text("");
				}
				if($("#sub_user").val() == ""){
					$("#sub_user").next(".error-alert").text("Select a "+ $("#selection_type").val());
					return;
				}else{
					$("#sub_user").next(".error-alert").text("");
				}
				
				if($("#start_date input").val() != ""){
					if($("#end_date input").val() == ""){
						$("#end_date").next(".error-alert").text("Select End Date.");
						return;
					}else{
						$("#end_date").next(".error-alert").text("");
					}
				}
				
				
				var obj={};
				reportData={};

			  	obj.selectiontype = $("#selection_type").val();
			  	obj.selectionid = $("#sub_user").val();
			  	obj.lead_name = $("#sub_user option:selected").text();
			   
				if($("#start_date input").val() != ""){
                    obj.startdate = moment($("#start_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
                    obj.enddate = moment($("#end_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
				}else{
                     obj.startdate = 'NA';
                     obj.enddate = 'NA';
				}
				obj.type='Lead_Score_Analysis';
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
			
			/* -============================================================================================= */
			function go_back(){
				$("#details_btn").show();
				generateReport();
				$("#piechart_3d").show();
				$("#table_view").hide();
				$("#direction").show();
				$("#previousPage").html("");
			}
			function buildHtmlTable(myList,selector, inputdata, state) {
				if(reportArray.length == 2 ){
					setCookie('reportOutput', JSON.stringify(myList),1);
					setCookie('reportInput', JSON.stringify(inputdata),1);
				}
				
				var name = $("#sub_user option:selected").text();
				if(state == "Last viewed"){
					$("#details_btn").hide();
					if(inputdata.startdate == 'NA'){
                     $("#report_title").html("<span>"+state+" Analysis for <u><i>"+ inputdata.lead_name +"</i></u> ("+capitalizeFirstLetter(inputdata.selectiontype)+")</span>");
				    }else{
                       $("#report_title").html("<span>"+state+" Analysis for <u><i>"+ inputdata.lead_name +"</i></u> ("+capitalizeFirstLetter(inputdata.selectiontype)+")  From <u>"+ moment(inputdata.startdate, 'DD-MM-YYYY').format(dateFormat) +"</u>  To <u>"+moment(inputdata.enddate, 'DD-MM-YYYY').format(dateFormat) +"</u></span>");
				    }
				}else{
                    if(inputdata.startdate == 'NA'){
                       $("#report_title").html("<span>"+state+" Analysis for <u><i>"+ name +"</i></u> ("+capitalizeFirstLetter(inputdata.selectiontype)+")</span>");
                    }else{
                          $("#report_title").html("<span>"+state+" Analysis for <u><i>"+ name +"</i></u> ("+capitalizeFirstLetter(inputdata.selectiontype)+") From <u>"+ moment(inputdata.startdate, 'DD-MM-YYYY').format(dateFormat) +"</u>  To <u>"+moment(inputdata.enddate, 'DD-MM-YYYY').format(dateFormat) +"</u></span>");
    		        }
				}
                if(reportData.Cost_type == "All"){
                    if(inputdata.startdate == 'NA'){
                       $("#report_title").html("<span>"+state+" Analysis for "+capitalizeFirstLetter(inputdata.selectiontype)+" <u><i>All</i></u></span>");
                    }else{
                         $("#report_title").html("<span>"+state+" Analysis for "+capitalizeFirstLetter(inputdata.selectiontype)+" <u><i>All</i></u>  From <u>"+ moment(inputdata.startdate, 'DD-MM-YYYY').format(dateFormat) +"</u>  To <u>"+moment(inputdata.enddate, 'DD-MM-YYYY').format(dateFormat) +"</u></span>");
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
				
				if(inputdata.hasOwnProperty('id')){
					$("#report_title").append('<span> with respect to <U>'+inputdata.Name+'</U> (Lead)</span>')
					table(myList, selector, state)
				}else{
					graph(myList,inputdata, state);
				}
				
				export_report('lead_score_analysis_view');
			}
			
			function table(myList, selector, state){
				$("#piechart_3d").hide();
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
							if( columns[c] != "category_name"){
								cell += '<th style="text-align: left;width:135px; border-left:1px solid white;">'+ columns[c].split("_").join(" ") +'</th>';
							}							
						}
					}
				}
				$(selector).append('<thead/>');
				$(selector +" thead").append('<tr>'+cell+'</tr>');
				$(selector).append('<tbody/>');
				
				var isPlayer = 0, tableRow="";
				for (var i = 0; i < myList.length; i++) {
					var datacell="";
					for (var colIndex = 0; colIndex < columns.length; colIndex++) {
						var cellValue = $.trim(myList[i][columns[colIndex]]);
						/* --------------------------------- */
						if(columns[colIndex] == "Path"){
							 if(myList[i][columns[colIndex]] == "'blank'"){

								cellValue = "";
							}else if(myList[i][columns[colIndex]] != "'no_path'"){
								isPlayer = 1;
								cellValue = "<?php echo base_url(); ?>uploads/"+cellValue;
								cellValue = 	'<div class="mediPlayer">'+
													'<audio class="listen" preload="none" data-size="250" src="'+cellValue+'"></audio>'+
												'</div>';
							}else{
								cellValue= "Not available"
							}
						}
						/* --------------------------------- */
						if(columns[colIndex] == 'Date'){
							cellValue = moment(cellValue, 'DD-MM-YYYY').format(dateFormat);
						}
						if(columns[colIndex] != "lid"){		
							if( columns[colIndex] != "category_name"){
								datacell += '<td style="text-align: left;">'+ cellValue +'</td>';
							}
						}
					}
					/* if(state !="Details"){
						tableRow = "<tr id='"+myList[i].lid+"' onclick='send_data(this.id)'>"+datacell+"</tr>";
					}else{
						tableRow = "<tr>"+datacell+"</tr>";
					} */
					tableRow = "<tr>"+datacell+"</tr>";
					$(selector +" tbody").append(tableRow);
				}
				if(isPlayer == 1){
					$('.mediPlayer').each(function(){
						$(this).mediaPlayer();
					})
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
			/* ------------------------------------------------------------------------- */
			/* ----------------------save / update / save as function-------------------- */
			/* -------------------------------------------------------------------------- */
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
					reportData.type='Lead_Score_Analysis';
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
				function graph(data , inputdata , state){
					$("#piechart_3d").show();
					$("#table_view").hide();
					var columnSet = [];
					columnSet = [['Lead Name', 'lead_id','User_ID' ,'Lead Score']];
					
					for (var i = 0; i < data.length; i++) {
						var columnSet1 = [];
						for (var key in data[i]){
								if(key.indexOf("RepScore") > 0){
									columnSet1.push(parseFloat(data[i][key]));
								}else{
									columnSet1.push(data[i][key]);
								}
						}
						columnSet.push(columnSet1);
					}
					
					a(columnSet);
					
					$('#canvas #details_btn').remove();
					//$('#canvas').append('<center style="position: absolute;top: 0px;margin-top: 4px;" id="details_btn"><input class="btn" type="button" onclick="details(\'All\', \'All\')" value="Show Details"><center>');
				}
				
				function a(columnSet){
					google.charts.load('current', {'packages':['bar']});
					google.charts.setOnLoadCallback(drawChart);
					
					function drawChart() {
						var newarray = [];
						
						for (var i = 0; i<columnSet.length;i++) {
								newarray1 = columnSet[i].splice(1, 2);
								newarray.push(newarray1)
						}
						var newarray3 = newarray.splice(0, 1);
						
						console.log(columnSet)
						/*for (var i = 0; i<1000;i++) {
							var aa= []
							for (var ii = 0; ii<2;ii++) {
								if(ii == 0){
									aa.push("a"+i);
								}else{
									aa.push(i);
								}
							}
							columnSet.push(aa)
						}
						 
							["yotest23", 3.8]
						*/
						var data = google.visualization.arrayToDataTable(columnSet);
						/*var a1=0,a2=0,total=0;
						 for (var i = 0; i<columnSet.length;i++) {
							if(i > 0){
								a1 = columnSet[i][1]+a1;
								a2 = columnSet[i][2]+a2;	
							}
						} 
						total = a1+a2*/
						/* ----------------------add Column---------------------------- */
						//$('#report_title').append('<span><b><h3 style="display: inline;"> & </h3><i>the total cost is Rs.'+total.toFixed(2)+'</i></b></span>');
						
						data.addColumn({type: 'string', role: 'tooltip'});
						for (var i = 0; i<data.getNumberOfRows();i++) {
							data.setValue(i, 2, ''+newarray[i]);
						}
						/* -------------------------------------------------- */
						
						var options = {
							chart: {
								title: ' ',
								//subtitle: 'Resource Cost (Rs) : '+a1.toFixed(2)+'\nActivity Cost (Rs) : '+ a2.toFixed(2),
							},
							tooltip: {isHtml: true} ,
							bars: 'horizontal',
							hAxis: {format: 'decimal'},
							colors: ['#1b9e77', '#d95f02', '#7570b3']
						};

						var chart = new google.charts.Bar(document.getElementById('piechart_3d'));
						chart.draw(data, google.charts.Bar.convertOptions(options));
						/* ----------------------------------------------------- */
						
						google.visualization.events.addListener(chart, 'select', function () {
							var selection = chart.getSelection();
							if (selection.length) {
								
								var row = selection[0].row;
								if(row == null){
									return;
								}else{
									if(reportData.hasOwnProperty('selectionid')== true){
										details(data.getValue(row, 2) , data.getValue(row, 0))
										console.log(data.getValue(row, 2)+" -- "+ data.getValue(row, 0))
									}
								}
								
								/*var view = new google.visualization.DataView(data);
								view.setColumns([0, 1, {
									type: 'string',
									role: 'style',
									calc: function (dt, i) {
										return (i == row) ? 'color: red' : null;
									}
								}]);
								
								 chart.draw(view, {
									height: 450,
									width: 600
								}); */
							}
						});
						
					}
					
				}
				function details(id ,Name) {
						id = id.split(',');
						$('#canvas #details_btn').remove();
						reportData['id'] = id[0];
						reportData['lead_id'] = id[0];
						reportData['selectionid'] = id[1];
						reportData["Name"] = Name;
						reportData["subtype"] = "details";
						console.log(reportData)
						if(reportArray.length == 2 || reportArray.length == 3){
        		            $.ajax({
        						type: "POST",
        						url:"<?php echo site_url('reports/standard_LeadAnalysisController/generateReport')?>",
        						data : JSON.stringify(reportData),
        						dataType:'json',
        						success: function(data){
        							buildHtmlTable(data,"#excelDataTable" , reportData, "Detailed");
									$("#previousPage").html('<h4><a class="btn" href="javascript:void(0)" onclick="go_back()"><i class="fa fa-arrow-left fa-3" aria-hidden="true"></i> Back</a></h4>');
        						},
        						error:function(data){
        							network_err_alert(data);
        						}
        	                })
					    }
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
				<div class="row report_input_data" >						
					<div class="col-md-10 no-padding" id="report_input_data">
						<div class="row">
							<div class="col-md-3">
								<label for="selection_type">Selection Type*</label>
								<select class="form-control" id="selection_type" onchange="list_view(this.value, '')">
									<option value="">Select</option>
									<option value="representative">Executive</option>
									<option value="industry">Industry</option>
									<option value="location">Location</option>
									<option value="product">Product</option>
								</select>
								<span class="error-alert"></span>
							</div>
							<div class="col-md-3">
								<label for="sub_user" id="sub_user_lbl"></label>
								<select class="form-control" id="sub_user" onchange="valueChange()">
								</select>
								<span class="error-alert"></span>
							</div>
							<div class="col-md-3">
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
							<div class="col-md-3">
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
											<option value="line">Pie Chart</option>
											<option value="text">Text Chart</option>
										</select>
										<span class="error-alert"></span>
									</div>-->
								</div>
								<div class="col-md-3">
									<div>
										<input onkeyup="valueChange()"type="text" class="form-control" id="report_name" placeholder="Report Name (Mandatory)" />
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
						<div class ="table-responsive print table_view none" id="table_view" >
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




