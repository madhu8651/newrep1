<!DOCTYPE html>
<html lang="en">
    <head>
		<?php require __DIR__.'/../scriptfiles.php' ?>
		
		<script type="text/javascript">
				
               
			var data_req ={};
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
					$(this).closest(".row").find("#end_date").data("DateTimePicker").clear();
					var startDateTime = moment($("#start_date input").val(),'DD-MM-YYYY');
					if($("#start_date input").val().trim() == ""){
						startDateTime=moment();
					}
					$(this).closest(".row").find("#end_date").data("DateTimePicker").minDate(startDateTime);
					valueChange()
				});
				
				$("#end_date").on("dp.change", function (selected) {					
					valueChange()
				});
				
				$("#start_date input").val(moment().format("DD-MM-YYYY"));
				$("#end_date input").val(moment().format("DD-MM-YYYY"));
				get_heirarchy(reportArray[0]);
				
				/* --------------------------------------------------------------------- */

				
				

				/* --------------------------------------------------------------------- */
				
			});
			
			
			/* ---------------------------------------------clear button click-------------------------------------- */
			
			function cancel_report_setting(){
				$("#report_input_data input, #report_input_data select").val("");
				$('#start_date').data().DateTimePicker.date(null);
				$('#end_date').data().DateTimePicker.date(null);
				valueChange();
			}
			/* ---------------------------------------------onchange of saved value-------------------------------------- */
			function valueChange(){
					var obj={};
					obj.lead_id = $("#sub_user").val();					
					obj.sub_lead_id = $("#user").val();					
					obj.report_name = $("#report_name") .val();
					
					var changedInput=0;
					if(reportArray.length == 3 ){
						if( obj.lead_id == reportData.lead_id ) {
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
					if(reportArray.length == 2 && data_req.hasOwnProperty("lead_id")){
						
						if( obj.lead_id == data_req.lead_id	&& obj.sub_lead_id == data_req.sub_lead_id) {
							$("#canvas,#chart_type,#report_title").css("visibility", "visible");
							$("#save_report_btn").show();
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
                    obj1.type='Lead_Lifecycle_Analysis';
					console.log(obj1)
					$.ajax({
						type: "POST",
						url:"<?php echo site_url('reports/standard_LeadAnalysisController/getsaved_reports')?>",
						data : JSON.stringify(obj1),
						dataType:'json',

						success: function(data){
							console.log(data)
							if(error_handler(data)){
								return;
							}

							reportData={};
							reportData.lead_id = data.lead_id;
							reportData.type = 'Lead_Lifecycle_Analysis';
							
							$("#sub_user").val(data.lead_id);
							$("#user").val(data.sub_lead_id);
							$("#report_name").val(data.report_nme);
							data_req.lead_id = data.lead_id; 
							data_req.type='Lead_Lifecycle_Analysis';
							var val = $("#user").val();
							if(val == "Activity" ){
								buildHtmlTable(data.tabledetails[0].Activity,"#excelDataTable1" , reportData, "Saved");	
							}
							if(val == "Log_Activity"){
								buildHtmlTable(data.tabledetails[0].Action,"#excelDataTable" , reportData, "Saved"); 
							}
							if(val == "All"){
								buildHtmlTable(data.tabledetails[0].Action,"#excelDataTable1" , reportData, "Saved");	
								buildHtmlTable(data.tabledetails[1].Activity,"#excelDataTable1" , reportData, "Saved"); 
								$("#excelDataTable").show();
								$("#excelDataTable1").show();
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
						if(getCookie("reportInput") != ''){
							var prviouse_data =  $.parseJSON(getCookie("reportInput"));
							console.log(prviouse_data)
							$("#sub_user option[value='"+prviouse_data.lead_id+"']").attr("selected", true);
							$("#user option[value='"+prviouse_data.sub_lead_id+"']").attr("selected", true);
						}
						savedreportdisplay();
						if(reportArray.length == 2 ){
							if (getCookie("reportOutput") != "" && getCookie("reportInput") !="") {
								var data = JSON.parse(getCookie("reportOutput"));
								var key_arr = [];
								buildHtmlTable(JSON.parse(getCookie("reportOutput")),"#excelDataTable" , JSON.parse(getCookie("reportInput")), "Last viewed");
								reportData=JSON.parse(getCookie("reportInput"));
								console.log(data)
								console.log(data[0])
								for(key in data[0]){
									key_arr.push(key);
								}
								if(data.length == 2){
									buildHtmlTable(data[0].Action,"#excelDataTable" , JSON.parse(getCookie("reportInput")), "Last viewed");
									buildHtmlTable(data[1].Activity,"#excelDataTable1" , JSON.parse(getCookie("reportInput")), "Last viewed");
								}if(key_arr[0] == "Activity"){
									buildHtmlTable(data[0].Activity,"#excelDataTable" , JSON.parse(getCookie("reportInput")), "Last viewed");
								}else{
									buildHtmlTable(data[0].Action,"#excelDataTable" , JSON.parse(getCookie("reportInput")), "Last viewed");
								}
								
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
					$("#sub_user").next(".error-alert").text("Select a user.");
					return;
				}else{
					$("#sub_user").next(".error-alert").text("");
				}
				
				if($("#user").val() == ""){
					$("#user").next(".error-alert").text("Select a log type.");
					return;
				}else{
					$("#user").next(".error-alert").text("");
				}
				
				
				var obj={};
				reportData={};
				
				obj.lead_id = $("#sub_user").val();
				obj.sub_lead_id = $("#user").val();
				obj.type='Lead_Lifecycle_Analysis';	
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_LeadAnalysisController/generateReport')?>",
				   	data : JSON.stringify(obj),
					dataType:'json',
					success: function(data){
						console.log(data)
						var temp=[];
						if(error_handler(data)){
							return;
						}
						if(data.length > 0){
							var val = $("#user").val();
							if(val == "Activity" ){
								buildHtmlTable(data[0].Activity,"#excelDataTable1" , obj, "Generated");	
							}
							if(val == "Log_Activity"){
								buildHtmlTable(data[0].Action,"#excelDataTable" , obj, "Generated"); 
							}
							if(val == "All"){
								buildHtmlTable(data[1].Activity,"#excelDataTable1" , obj, "Generated");	
								buildHtmlTable(data[0].Action,"#excelDataTable" , obj, "Generated"); 
								$("#excelDataTable").show();
								$("#excelDataTable1").show();
							}
							if(reportArray.length == 2 ){
								setCookie('reportOutput', JSON.stringify(data),1);
								setCookie('reportInput', JSON.stringify(obj),1);
							}
							
							reportData = obj;
							data_req = obj;
						}else{
							$("#report_title").css({"visibility": "visible","display": "block"}).html("<center>No records available for the selected time duration.</center>");
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
				generateReport();
				$("#piechart_3d").show();
				$("#table_view").hide();
				$("#chart_select").show();
				$("#direction").show();
				$("#remarks_descriction").show();
				$("#chart_select").val("line");
				$("#previousPage").html("");
			}
			function buildHtmlTable(myList,selector, inputdata, state) {
				reportType('text');	
				$("#chart_select").hide();	
				var name = $("#sub_user option:selected").text();
				if(state == "Last viewed"){
					$("#report_title").html(state+" Analysis is for <u>"+ name +"</u><hr>");
				}else if(state == "Saved"){
					$("#report_title").html(state+" Analysis is for <u>"+ name +"</u><hr>");
				}else{
					var val = $("#user").val();
					if(val == "Activity"){
						$("#report_title").html(state+" Analysis is for <u>"+ name +"</u><hr>");
						$("#excelDataTable").hide();
						$("#excelDataTable1").show();
					}
					if(val == "Log_Activity"){
						$("#report_title").html(state+" Analysis is for <u>"+ name +"</u><hr>");
						$("#excelDataTable").show();
						$("#excelDataTable1").hide();
					}
					if(val == "All"){
						$("#report_title").html(state+" Analysis is for <u>"+ name +"</u><hr>");
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
					
					if(state == "Child Report"){
						$("#save_report_btn").hide();
						$("#saveAs_report_btn, #update_report_btn").show();	
					}
				}
				
				$(selector).html("")
				var columns = addAllColumnHeaders(myList, selector);
				var row$ = '';
				var cell="";
				
				for (var c = 0; c < columns.length; c++) {
					if(columns[c] == "Note"){
						cell += '<th style="text-align: left;">'+ columns[c].split("_").join(" ") +'</th>';
					}else{
						if(columns[c] != "lid"){		
							if( columns[c] != "category_name"){
								cell += '<th style="text-align: left;">'+ columns[c].split("_").join(" ") +'</th>';
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
						var cellValue = myList[i][columns[colIndex]];
						/* --------------------------------- */
						if(columns[colIndex] == "Path"){
							if(myList[i][columns[colIndex]] != "'no_path'"){
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
						if(columns[colIndex] == 'Log_Action_Date' || columns[colIndex] == 'Action_Date' || columns[colIndex] == 'Activity_Completed_Date'){
							cellValue = moment(cellValue, 'DD-MM-YYYY').format(dateFormat);
						}
						if(columns[colIndex] != "lid"){		
							if( columns[colIndex] != "category_name"){
								datacell += '<td style="text-align: left;">'+ cellValue +'</td>';
							}
						}
					}
					if(state !="Child Report"){
						tableRow = "<tr>"+datacell+"</tr>";
					}else{
						tableRow = "<tr>"+datacell+"</tr>";
					}
					$(selector +" tbody").append(tableRow);
				}
				if(isPlayer == 1){
					$('.mediPlayer').mediaPlayer();
					/* $('.mediPlayer').each(function(){
						$(this).mediaPlayer();
					}) */
				}
				$('#export_to').html("");
					
				//$('#export_to').append('<button class="btn" title="PDF" onclick="gimg()"><span class="fa fa-file-pdf-o"></span> PDF  </button>');
				//$('#export_to').append('<button class="btn" title="CSV" ><span class="fa fa-file-excel-o"></span> CSV  </button>');
				$('#export_to').append('<button class="btn" title="Print Report" onclick="window.print();" ><span class="fa fa-print"></span> Print Report  </button>');
				//$('#export_to').append('<button class="btn" title="Schedule Report"><span class="fa fa-clock-o"></span> Schedule  </button>');
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
					reportData.sub_lead_id=$("#user").val();
				}
				
				if(reportArray.length == 3 && state=='update'){
					reportData.id = reportArray[reportArray.length-1];
					reportData.type='Lead_Lifecycle_Analysis';
					reportData.sub_lead_id=$("#user").val();
				}		
				console.log(reportData)
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
				<div class="row" >						
					<div class="col-md-10 no-padding" id="report_input_data">
						<div class="col-md-4">
							<label for="sub_user">Leads*</label>
							<select class="form-control" id="sub_user" onchange="valueChange()">
								
							</select>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-4">
							<label for="sub_user">Sub Type*</label>
							<select class="form-control" id="user" onchange="valueChange()">
								<option value="">Select</option>
								<option value="All">All</option>
								<option value="Activity">Activity</option>
								<option value="Log_Activity">Action</option>
							</select>
							<span class="error-alert"></span>
						</div>					
					</div>
					<div class="col-md-2 no-padding">
						<br>
						<span class="pull-right">
							<input type="button" class="btn btn-default" id="generate_save_btn" onclick="generateReport()"  value="Generate">
							<input type="button" class="btn btn-default" onclick="cancel_report_setting()" value="Clear" >
						</span>
					</div>
					
					
				</div>
				<hr>
				<div class="row print">
				<!---------------------------- ----------------------- report Setting section ----------------------------->
					
					<div class="col-md-12 no-padding none" id="chart_type">
						<h4>Configure Report</h4><hr>
						<div class="row " >							
							<div class="row">
								<div class="col-md-3">
									<div>
										<select class="form-control" id="chart_select" onchange="reportType(this.value)">
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




