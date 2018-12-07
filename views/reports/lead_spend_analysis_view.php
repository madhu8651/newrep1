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
						buildHtmlTable(JSON.parse(getCookie("reportOutput")),"#excelDataTable" , JSON.parse(getCookie("reportInput")), "Last Viewed Report")
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
					if($.trim($("#start_date input").val()) == '' || $.trim($("#end_date input").val()) == ''){
                        obj.startdate  = 'NA';
					    obj.enddate = 'NA';
					}
					var changedInput=0;
					if(reportArray.length == 3 ){
						$("#save_report_btn").hide();
						if( obj.lead_id == reportData.lead_id &&
						obj.startdate == reportData.startdate &&
						obj.enddate == reportData.enddate) {
							changedInput=0;
							$("#canvas, #chart_type,#report_title").css("visibility", "visible");
							$("#saveAs_report_btn , #update_report_btn").show().removeAttr("disabled","disabled" );	
							$("#generate_save_btn").attr("disabled" , "disabled");
						}else{
							changedInput=1;
							$("#canvas,#chart_type,#report_title").css("visibility", "hidden");
							$("#saveAs_report_btn, #update_report_btn").show().attr("disabled","disabled" );	
							$("#generate_save_btn").removeAttr("disabled" , "disabled");
						}
						if( obj.report_name == reportData.report_name || changedInput == 1) {
							$("#saveAs_report_btn,#update_report_btn").show().attr("disabled","disabled" );	
						}else{
							$("#saveAs_report_btn, #update_report_btn").show().removeAttr("disabled" );	
						} 
					}
					
					if(reportArray.length == 2 || data_req.hasOwnProperty("lead_id")){
						
						if( obj.lead_id == reportData.lead_id &&
						obj.startdate == reportData.startdate &&
						obj.enddate == reportData.enddate) {
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
					$("#previousPage").html('<h4 style="position: absolute;top: 73px;"><a class="btn" href="<?php echo site_url('manager_standard_analytics'); ?>#savedReport='+report+'"><i class="fa fa-arrow-left fa-3" aria-hidden="true"></i> Back</a></h4>')
					
					var obj1={};
                    obj1.id = reportArray[reportArray.length-1];
                    obj1.type='Lead_Spend_Analysis';
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
							reportData.startdate = moment(data.startdate,'DD-MM-YYYY').format('DD-MM-YYYY');
							reportData.enddate = moment(data.enddate,'DD-MM-YYYY').format('DD-MM-YYYY'); 
							reportData.type = 'Lead_Spend_Analysis';
							reportData.report_name = data.report_nme;

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
							data_req.startdate = moment(data.startdate,'DD-MM-YYYY').format('DD-MM-YYYY');
							data_req.enddate = moment(data.enddate,'DD-MM-YYYY').format('DD-MM-YYYY'); 
							data_req.type='Lead_Spend_Analysis';
							if(data.lid){
								buildHtmlTable(data.tabledetails,"#excelDataTable" , reportData, "Details Report");
							}else{								
								buildHtmlTable(data.tabledetails,"#excelDataTable" , reportData, "Saved Report");
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
						loaderHide();
                        /* --------------------------------------------------------------------- */
        				if(reportArray.length == 2 ){
        					if(getCookie("reportInput") != ''){
								var prviouse_data =  $.parseJSON(getCookie("reportInput"));
								$("#sub_user option[value='"+prviouse_data.lead_id+"']").attr("selected", true);
								$("#start_date input").val(moment(prviouse_data.startdate, 'DD-MM-YYYY').format('DD-MM-YYYY'));
								$("#end_date input").val(moment(prviouse_data.enddate, 'DD-MM-YYYY').format('DD-MM-YYYY'));
								if(prviouse_data.startdate != "NA"){
								$("#end_date").data("DateTimePicker").minDate(prviouse_data.startdate);
							}
							}
        				}

        				/* --------------------------------------------------------------------- */
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
					$("#sub_user").next(".error-alert").text("Select a Lead.");
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


                if($("#start_date input").val() != ""){
                    obj.startdate = moment($("#start_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
                    obj.enddate = moment($("#end_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
				}else{
                     obj.startdate = 'NA';
                     obj.enddate = 'NA';
				}

				obj.type='Lead_Spend_Analysis';
                obj.subtype='Summary';
				user_name = $("#sub_user option:selected").text();
				get_data(obj, 'Generated Report');
				$("#previousPage").html('');
				
			}
			
			function get_data(obj ,state){
				
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
							buildHtmlTable(data,"#excelDataTable",obj ,state)
							if(reportArray.length == 2 ){
								setCookie('reportOutput', JSON.stringify(data),1);
								setCookie('reportInput', JSON.stringify(obj),1);
							}
							
							reportData = obj;
							data_req = obj;
							
						}else{
							$("#report_title").css({"visibility": "visible","display": "block"}).html("<center>No records available for the selected time duration.</center>");
							$("#piechart_3d, #chart_type, #table_view").hide();
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
				$("#previousPage").html('');
				generateReport();
				$("#piechart_3d").show();
				$("#table_view").hide();
				$("#chart_select").show();
				$("#direction").show();
				$("#chart_select").val("line");
				$("#previousPage").html("");
			}
			function buildHtmlTable(myList,selector, input, state) {
                var u = $('#sub_user').find('option[value="'+input.lead_id+'"]').text();
				var reportName = '';
				if(state == 'Last Viewed Report'){
					reportName = 'Last Viewed Analysis';
				}
				if(state == 'Generated Report'){
					reportName = 'Generated Analysis';
				}
				if(state == 'Detailed Report'){
					reportName = 'Detailed Analysis';
				}
                if(input.startdate == 'NA'){
					if(input.hasOwnProperty('activitydate')){
						if(input.activitydate == "All"){
							$("#report_title").html(reportName+" for <u>"+ u +"</u> (Lead)");
						}else{
							$("#report_title").html(reportName+" for <u>"+ u +"</u> (Lead) on "+ input.activitydate);
						}
					}else{
						$("#report_title").html(reportName+" for <u>"+ u +"</u>");
					}
                    
    		    }else{
					if(input.hasOwnProperty('activitydate')){
						if(input.activitydate == "All"){
							$("#report_title").html(reportName+" for <u>"+ u +"</u> (Lead) From <u>"+ moment(input.startdate, 'DD-MM-YYYY').format(dateFormat) +"</u>  To <u>"+moment(input.enddate , 'DD-MM-YYYY').format(dateFormat) +"</u>");
						}else{
							$("#report_title").html(reportName+" for <u>"+ u +"</u> (Lead) on "+ input.activitydate+" From <u>"+ moment(input.startdate, 'DD-MM-YYYY').format(dateFormat) +"</u>  To <u>"+moment(input.enddate, 'DD-MM-YYYY').format(dateFormat) +"</u>");
						}
						
					}else{
						$("#report_title").html(reportName+" for <u>"+ u +"</u> From <u>"+ moment(input.startdate, 'DD-MM-YYYY').format(dateFormat) +"</u>  To <u>"+moment(input.enddate, 'DD-MM-YYYY').format(dateFormat) +"</u>");
					}
    		    }
				
				if(input.hasOwnProperty('activitydate')){
					table_built(myList,selector);
				}else{
					line_Chart(myList, state);
					
					if(state.split(' ').join('') != "LastViewedReport"){
						$('#canvas #details_btn').remove();
						$('#canvas').append('<center style="position: absolute;top: 0px;margin-top: 4px;" id="details_btn"><input class="btn" type="button" onclick="details(\'All\')" value="Show Details"><center>');
					}
				}
                
				if(reportArray.length == 2 ){
					if(state == "Generated Report"){
						$("#canvas, #chart_type, #report_title").css({"visibility": "visible","display": "block"});
						$("#generate_save_btn").attr("disabled" , "disabled");
						$("#save_report_btn").show().removeAttr("disabled");
					}
				}
				if(reportArray.length == 3 ){
					$("#canvas, #chart_type, #report_title").css({"visibility": "visible","display": "block"});
					$("#generate_save_btn").attr("disabled" , "disabled");
					if(state == "Generated Report"){
						$("#save_report_btn").hide();
						$("#saveAs_report_btn, #update_report_btn").show().removeAttr("disabled");
					}

					if(state == "Saved Report"){
						$("#save_report_btn").hide();
						$("#saveAs_report_btn, #update_report_btn").show();
					}
				}
				
				export_report('Lead_Spend_Analysis');

			}
			
			function table_built(myList,selector){
				
				$("#table_view").show();
				$("#piechart_3d").hide();
				$(selector).html("")
				var columns = addAllColumnHeaders(myList, selector);
				if(columns.length > 5){
					$(selector).css({'max-width': '100%'})
				}else{
					$(selector).css({'max-width': '50%'});
				}
				var row$ = '';
				var cell="";

				for (var c = 0; c < columns.length; c++) {
					if(columns[c] == "Note"){
						cell += '<th style="text-align: left;width:200px;">'+ columns[c].split("_").join(" ") +'</th>';

					}else{
						if(columns[c] != "opportunity_product"){
							cell += '<th style="text-align: left;width:135px; white-space: nowrap;">'+ columns[c].split("_").join(" ") +'</th>';
						}
					}

				}
				$(selector).append('<thead/>');
				$(selector +" thead").append('<tr>'+cell+'</tr>');
				$(selector).append('<tbody/>');

				var isPlayer = 0;
				for (var i = 0; i < myList.length; i++) {
					var datacell="";
					for (var colIndex = 0; colIndex < columns.length; colIndex++) {
						var cellValue = myList[i][columns[colIndex]];
						/* --------------------------------- */
						if(columns[colIndex] == "Media_File" || columns[colIndex] == "Path"){
							columns[colIndex] == "Media_File";
							if(myList[i][columns[colIndex]] == "'blank'"){
								cellValue= " ";
							}else if(myList[i][columns[colIndex]] != "'no_path'" ){
								isPlayer = 1;
								cellValue = "<?php echo base_url(); ?>uploads/"+cellValue;
								cellValue = 	'<audio controls controlsList="nodownload">'+
													'<source src="<?php echo base_url(); ?>uploads/'+cellValue+'" type="audio/mpeg">'+
													'Your browser does not support the audio tag.'+
												'</audio>';
							}else{
								cellValue= "Not available";
							}
						}
						if(columns[colIndex] == 'Activity_Date'){
							cellValue = moment(cellValue, 'DD-MM-YYYY').format(dateFormat);
						}
						/* --------------------------------- */
						if(columns[colIndex] != "opportunity_product"){
							datacell += '<td style="text-align: left;">'+ cellValue +'</td>';
						}
					}
					$(selector +" tbody").append('<tr>'+datacell+'</tr>');
				}
				if(isPlayer == 1){
					$('.mediPlayer').mediaPlayer();
				}
				
				/* ================================ */
				var filter={  
				  "Resource_Cost":{"type":"float", "value":"0"},
				  "Activity_Cost":{"type":"float", "value":"0"},
				  "Total_Cost":{"type":"float", "value":"0"}
				}
				var removeClo =[];
				var label =[];
				$.each(myList[0], function(key1, row){
					label.push(key1)
				})
				addRow(addArray(filter, myList, label[0]), selector,removeClo);
				/* ================================ */
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
					reportData.type='Lead_Spend_Analysis';
				}
				
				//return;
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
			
				function line_Chart(data,state){
					console.log(data)
					$("#piechart_3d").show();
					$("#table_view").hide();
					var columnSet2 = [];
					var columnSet = [];
					var total=0
					for (var ii = 0; ii < data.length; ii++) {
						/* data[ii].Activity_Cost = parseInt(data[ii].Activity_Cost) * 100;  */
						data[ii].Activity_Cost = parseFloat(data[ii].Activity_Cost);
						total += data[ii].Activity_Cost;
						
					}
					$('#report_title').append('<span><b><h3 style="display: inline;"> & </h3><i>the total cost is Rs.'+total.toFixed(2)+'</i></b></span>');
					for (var i = 0; i < data.length; i++) {
						var rowHash = data[i];
						var columnSet1 = [];							
						for (var key in rowHash) {	
							if ($.inArray(key, columnSet) == -1){
								if(key != "lookup_id"){
									columnSet1.push(rowHash[key]);
								}
								
							}
						}
						
						var summary = "Date : "+moment(columnSet1[2], 'DD-MM-YYYY').format(dateFormat)+
						"\n Cost :  "+(data[i].Activity_Cost)+ " Rs "+
						"\n Percentage : "+ (total == 0? 0:((data[i].Activity_Cost/total) * 100).toFixed(1))+" %";
						/* columnSet1.push(createCustomHTMLContent(columnSet1[0],dutation)); */
						console.log(data[i].Activity_Cost +'--'+total +'--'+data[i].Activity_Cost/total)
						columnSet1.push(summary);
						if(data[i].hasOwnProperty('lookup_id')){
							columnSet1.push(data[i].lookup_id);
						}
						columnSet.push(columnSet1);
						
					}
					for (var j = 0; j < columnSet.length; j++) {
						columnSet[j][1] = columnSet[j][1];
					}
					
					/* ------------------------------------------------------------------------- */
					google.charts.load('current', {
						'packages': ['corechart']
					});
					google.charts.setOnLoadCallback(drawChart);
					
					 function drawChart() {
						var dataTable = new google.visualization.DataTable();
						dataTable.addColumn('string', 'Year');
						dataTable.addColumn('number', 'Currency');
						dataTable.addColumn({type: 'string', role: 'annotation'});
						dataTable.addColumn({type: 'string', role: 'tooltip', p: {html:true}});
						/* dataTable.addColumn('date', 'string'); */
						
						dataTable.addRows(columnSet)
						
						var options = { 
									/* tooltip: {isHtml: true} */
										pointSize: 20,
										pointShape: { type: 'star', sides: 5, dent: 0.5 }
									};
						var chart = new google.visualization.LineChart(document.getElementById('piechart_3d'));
						chart.draw(dataTable, options);
						
						var selectHandler = function(e){
							var selectedItem = chart.getSelection()[0];
							if (selectedItem){
								var topping = dataTable.getValue(selectedItem.row, 2);
								details(topping);
								
							}
							chart.setSelection([]);
						}
						/* google.visualization.events.addListener(chart, 'select', selectHandler); */
						var selectHandler1 = function(e) { 
								$('#piechart_3d').css('cursor','pointer')
							}
						if(state != "Last Viewed Report"){
							google.visualization.events.addListener(chart, 'select', selectHandler);
							google.visualization.events.addListener(chart, 'onmouseover', selectHandler1);
						}	
                        
					} 

				}
                function details(id){
				    $('#canvas #details_btn').remove();
					reportData["activitydate"] = id;
					reportData["subtype"] = "details";
					if(reportData.hasOwnProperty('lead_id')){
						get_data(reportData,'Detailed Report');
						
						$("#previousPage").html('<button type="button" class="btn" onclick="go_back()"><i class="fa fa-arrow-left fa-3" aria-hidden="true" style="margin-left: 0px;margin-right: 3px;"></i>Back</button>');
					}
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
						</div>
					</div>
				</div>
				</div>
				
			</div>
        </div>
        
        <?php require __DIR__.'/../footer.php' ?>
		

    </body>
</html>




