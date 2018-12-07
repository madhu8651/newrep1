<!DOCTYPE html>
<html lang="en">
    <head>
		<?php require __DIR__.'/../scriptfiles.php' ?>
		
		<script type="text/javascript">
			var reportData={};
			$(document).ready(function(){
				list_view()
				$('#start_date').datetimepicker({
                    format: 'DD-MM-YYYY',
					minDate: moment("01-01-2000", 'DD-MM-YYYY')					
                });
				/* $('#end_date').datetimepicker({
                    format: 'DD-MM-YYYY',
					minDate: moment("01-01-2000", 'DD-MM-YYYY'),
					
                });	 */
				$("#start_date").on("dp.change", function (selected) {
					/* $(this).closest(".row").find("#end_date").data("DateTimePicker").clear();
					var startDateTime = moment($("#start_date input").val(),'DD-MM-YYYY');
					$(this).closest(".row").find("#end_date").data("DateTimePicker").minDate(startDateTime); */
					valueChange()
				});
				
				
				$("#start_date input").val(moment().format("DD-MM-YYYY"));
				/* $("#end_date input").val(moment().format("DD-MM-YYYY")); */
				get_heirarchy(reportArray[0]);
				
				/* --------------------------------------------------------------------- */
				if(reportArray.length == 2 ){
					
					if (getCookie("reportOutput") != "" && getCookie("reportInput") !="") {
						buildHtmlTable(JSON.parse(getCookie("reportOutput")),"#excelDataTable" , JSON.parse(getCookie("reportInput")), "Last viewed")
						reportData=JSON.parse(getCookie("reportOutput"));
					}
				}
				
				/* --------------------------------------------------------------------- */
				savedreportdisplay();
			});		

			
			/* ---------------------------------------------clear button click-------------------------------------- */
			
			function cancel_report_setting(){
				$("#report_input_data input, #report_input_data select").val("");
				$('#start_date').data().DateTimePicker.date(null);
				/* $('#end_date').data().DateTimePicker.date(null); */
				valueChange()
			}
			/* ---------------------------------------------onchange of saved value-------------------------------------- */
			function valueChange(){
					var obj={};
					obj.user = $("#sub_user").val();
					obj.selection_type = $("#selection_type").val();
					obj.startDate  = $("#start_date input").val();
				   /* obj.endDate = $("#end_date input").val(); */
					obj.report_name = $("#report_name").val();

					var changedInput=0;
					if(reportArray.length == 3 ){
						/* obj.endDate == reportData.endDate */
						if( obj.user == reportData.user &&
						obj.startDate == reportData.startDate &&
						obj.selection_type == reportData.selection_type
						) {
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
					if(reportArray.length == 2){
						if( obj.user == reportData.user &&
						obj.startDate == reportData.startDate &&
						obj.selection_type == reportData.selection_type) {
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
					$("#previousPage").html('<h4><a class="btn" href="<?php echo site_url('manager_standard_analytics'); ?>#savedReport='+report+'"><i class="fa fa-arrow-left fa-3" aria-hidden="true"></i> Back</a></h4>')

					var obj1={};
                    obj1.id = reportArray[reportArray.length-1];
                    obj1.type='Time_distribution_analysis';
				    obj1.subtype='Detail';

					$.ajax({
						type: "POST",
						url:"<?php echo site_url('reports/standard_RepAnalysisController/getsaved_reports')?>",
						data : JSON.stringify(obj1),
						dataType:'json',
						
						success: function(data){
							if(error_handler(data)){
								return;
							}

							reportData={};
							reportData.user = data.userid;
							reportData.selection_type = data.selection_type;
							reportData.startDate = moment(data.startdate,'DD-MM-YYYY').format('DD-MM-YYYY');
						   /* reportData.endDate = moment(data.enddate,'DD-MM-YYYY').format('DD-MM-YYYY'); */
							reportData.report_name = data.report_name;

							$("#sub_user").val(reportData.user);
							$("#selection_type").val(reportData.selection_type);
							$("#start_date input").val(reportData.startDate);
						  /* $("#end_date input").val(reportData.endDate); */
							$("#report_name").val(reportData.report_name);
								reportData.subtype='Detail';
							reportData.name = $("#sub_user option:selected").text();
							buildHtmlTable(data.tabledetails,"#excelDataTable" , reportData, "Saved");
							
						
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
					url:"<?php echo site_url('reports/standard_RepAnalysisController/get_employees')?>",
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
						var select = $("#sub_user"), options = "<option value=''>Select</option><option value='All'>All</option><optgroup label='User List :'>";
						select.empty();
						for(var i=0;i<data.length; i++){
							options += "<option value='"+data[i].user_id+"'>"+ data[i].user_name+"</option>";
							
						}
						options += "</optgroup>";
						select.append(options);
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
				
				if($("#start_date input").val() == ""){
					$("#start_date").next(".error-alert").text("Select Start date.");
					return;
				}else{
					$("#start_date").next(".error-alert").text("");
				}
				/* if($("#end_date input").val() == ""){
					$("#end_date").next(".error-alert").text("Select End date.");
					return;
				}else{
					$("#end_date").next(".error-alert").text("");
				}
				*/

				var obj={};
				reportData={};

				obj.user = $("#sub_user").val();
				obj.selection_type = $("#selection_type").val();
				obj.startDate = moment($("#start_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
			   /* obj.endDate = moment($("#end_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY"); */
				obj.type='Time_distribution_analysis';
				obj.subtype='Detail';
				obj.name = $("#sub_user option:selected").text();

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
						if(data.length > 0){
							buildHtmlTable(data,"#excelDataTable" , obj, "Generated") 
							if(reportArray.length == 2 ){
								setCookie('reportOutput', JSON.stringify(data),1);
								setCookie('reportInput', JSON.stringify(obj),1);
							}
							reportData = obj;
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
			
			function buildHtmlTable(myList,selector, inputdata, state) {
				console.table(inputdata)
				if(state == "Saved"){
					$("#report_title").html(state+" Analysis  for <u>"+ inputdata.name +"</u>  on <u>"+ moment($("#start_date input").val(), 'DD-MM-YYYY').format(dateFormat) /*+"</u> to <u>"+ $("#end_date input").val() +"</u><hr>"*/);
				}else{
					$("#report_title").html(state+" Analysis for <u>"+ inputdata.name +"</u>  on <u>"+ moment(inputdata.startDate, "DD-MM-YYYY").format(dateFormat) /*+"</u> to <u>"+ moment(inputdata.endDate, "DD-MM-YYYY").format('DD-MM-YYYY') +"</u><hr>"*/);
				}

				
				if(reportArray.length == 2 ){

					if(state == "Generated"){
						$("#canvas, #chart_type, #report_title").css({"visibility": "visible","display": "block"});
						$("#generate_save_btn").attr("disabled" , "disabled");
						$("#save_report_btn").show().removeAttr("disabled");	
					}
				}
				if(reportArray.length == 3 )
                {
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
				}
				
				$(selector).html("")
				var columns = addAllColumnHeaders(myList, selector);
				var row$ = '';
				var cell="";
				
				for (var c = 0; c < columns.length; c++) {
					if(columns[c] == "Note"){
						cell += '<th style="text-align: left;width:200px;">'+ columns[c].split("_").join(" ") +'</th>';
					}else{
						cell += '<th style="text-align: left;width:135px; white-space: nowrap;">'+ columns[c].split("_").join(" ") +'</th>';
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
						
						
						datacell += '<td style="text-align: left;">'+ cellValue +'</td>';
					}
					$(selector +" tbody").append("<tr>"+datacell+"</tr>");
				}
				if(isPlayer == 1){
					$('.mediPlayer').mediaPlayer();
					/* $('.mediPlayer').each(function(){
						$(this).mediaPlayer();
					}) */
				}
				
				$('#export_to').html("");
					
				/* $('#export_to').append('<button class="btn" title="PDF" onclick="gimg()"><span class="fa fa-file-pdf-o"></span> PDF  </button>'); */
				$('#export_to').append('<button class="btn" title="CSV" ><span class="fa fa-file-excel-o"></span> CSV  </button>');
				$('#export_to').append('<button class="btn" title="Print Report" onclick="window.print();" ><span class="fa fa-print"></span> Print Report  </button>');
				$('#export_to').append('<button class="btn" title="Schedule Report"><span class="fa fa-clock-o"></span> Schedule  </button>');
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
				}
				if(reportArray.length == 3 ){
					reportData.startDate = moment(reportData.startDate,"DD-MM-YYYY").format("DD-MM-YYYY");
				   /* reportData.endDate = moment(reportData.endDate,"DD-MM-YYYY").format("DD-MM-YYYY"); */
				}

				console.table(reportData)
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_RepAnalysisController/save_report/time_distribution_analysis')?>",
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
							<div class="col-md-3">
								<label for="sub_user">User*</label>
								<select class="form-control" id="sub_user" onchange="valueChange()">
								</select>
								<span class="error-alert"></span>
							</div>
							<div class="col-md-3">
								<label for="selection_type">Selection Type*</label>
								<select class="form-control" id="selection_type" onchange="valueChange()">
									<option value="">Select</option>
									<option value="All">All</option>
									<option value="Leads">Leads</option>
									<option value="Opportunity">Opportunity</option>
									<option value="Customer">Customer</option>
								</select>
								<span class="error-alert"></span>
							</div>
							
							<div class="col-md-3">
								<label for="start_date"> Date*</label>
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
							<!--<div class="col-md-3">
								<label for="end_date">End Date*</label>
								<div class="form-group">
									<div class='input-group date' id='end_date'>
										<input type='text' class="form-control" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<span class="error-alert"></span>
								</div>
							</div>-->

						</div>
						<div class="col-md-2 no-padding">
							<span class="pull-right">
								<input type="button" class="btn btn-default" id="generate_save_btn" onclick="generateReport()"  value="Generate">
								<input type="button" class="btn btn-default" onclick="cancel_report_setting()" value="Clear" >
							</span>
						</div>
					</div>
					<!---------------------------- ---------------------------- -------------------------- -------------------------------
						---------------------------- ----------------------------- report settings section  -------------------------------
						---------------------------- -------------------------------------------------------------------------------------->
					<div class="row print">
						<div class="col-md-12 no-padding none" id="chart_type">
							<h4>Configure Report</h4><hr>
							<div class="row " >							
								<div class="col-md-3">
									<!--<div>
										<select class="form-control" onchange="reportType(this.value)">
											<option value="">Choose type of report</option>
											<option value="line">Time Line Chart</option>
											<option value="text">Text Chart</option>
										</select>
										<span class="error-alert"></span>
									</div>-->
								</div>
								<div class="col-md-3">
									<div >
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
					<div class="row print">
						<!---------------------------- ---------------------------- -------------------------- -------------------------------
						---------------------------- ----------------------------- report section report section -------------------------------
						---------------------------- -------------------------------------------------------------------------------------->
						<div class="col-md-12 no-padding print" id="canvas">
							<center><h4 id="report_title"></h4></center>
							<div id="timeline-tooltip" class ="print none"></div>
							<div id="table_view" class ="print ">
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




