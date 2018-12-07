<!DOCTYPE html>
<html lang="en">
    <head>
		<?php require __DIR__.'/../scriptfiles.php' ?>
		<style>
        #report_title{
          height:auto !important;
        }

        </style>
		<script type="text/javascript">
			var reportData={};
			$(document).ready(function(){
				list_view()
				$('#start_date').datetimepicker({
                    format: 'DD-MM-YYYY',
					minDate: moment("01-01-2000", 'DD-MM-YYYY'),
					maxDate:moment()
                });
				$('#end_date').datetimepicker({
                    format: 'DD-MM-YYYY',
					minDate: moment(),
					maxDate:moment()
                });
				$("#start_date").on("dp.change", function (selected) {
					//$(this).closest(".row").find("#end_date").data("DateTimePicker").clear();
					var startDateTime = moment($("#start_date input").val(),'DD-MM-YYYY HH:mm:ss');
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
				if(reportArray.length == 2 ){

					if (getCookie("reportOutput") != "" && getCookie("reportInput") !="") {
						reportOutput = JSON.parse(getCookie("reportOutput"));
						reportInput = JSON.parse(getCookie("reportInput"));
						buildHtmlTable(reportOutput,"#excelDataTable" , reportInput, "Last viewed");
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
					obj.user = $("#sub_user").val();
					obj.selection_type = $("#selection_type").val();
					obj.startDate  = $("#start_date input").val();
				   //	obj.endDate = $("#end_date input").val();
					obj.report_name = $("#report_name").val();

					var changedInput=0;
					if(reportArray.length == 3 ){
						//obj.endDate == reportData.endDate
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
					$("#previousPage").html('<h4 style="position: absolute;top: 73px;"><a class="btn" href="<?php echo site_url('manager_standard_analytics'); ?>#savedReport='+report+'"><i class="fa fa-arrow-left fa-3" aria-hidden="true"></i> Back</a></h4>')

					var obj1={};
                    obj1.id = reportArray[reportArray.length-1];
                    obj1.type='assign_analysis';
				    obj1.subtype='';


					$.ajax({
						type: "POST",
						url:"<?php echo site_url('reports/standard_ManAnalysisController/getsaved_reports')?>",
						data : JSON.stringify(obj1),
						dataType:'json',
						success: function(data){
							if(error_handler(data)){
								return;
							}
							reportData={};
							reportData.user = data.user;
							reportData.selection_type = data.selection_type;
							reportData.startDate = moment(data.startDate,'DD-MM-YYYY').format('DD-MM-YYYY');
						   	reportData.endDate = moment(data.endDate,'DD-MM-YYYY').format('DD-MM-YYYY');
							reportData.report_name = data.report_name;

							$("#sub_user").val(reportData.user);
							$("#selection_type").val(reportData.selection_type);
							$("#start_date input").val(reportData.startDate);
						  	$("#end_date input").val(reportData.endDate);
							$("#report_name").val(reportData.report_name);

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
					url:"<?php echo site_url('reports/standard_ManAnalysisController/get_employees')?>",
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
						var select = $("#sub_user"), options = "<option value=''>Select</option>";
						select.empty();
						for(var i=0;i<data.length; i++)
                        {
							options += "<option value='"+data[i].user_id+"'>"+ data[i].user_name+"</option>";
						}
						options += "</optgroup>";
						select.append(options);
						if(getCookie("reportInput") != ''){
							var prviouse_data =  $.parseJSON(getCookie("reportInput"));
							$("#sub_user option[value='"+prviouse_data.user+"']").attr("selected", true);
							$("#selection_type option[value='"+prviouse_data.selection_type+"']").attr("selected", true);
							$("#start_date input").val(moment(prviouse_data.startDate, 'DD-MM-YYYY').format('DD-MM-YYYY'));
							$("#end_date input").val(moment(prviouse_data.endDate, 'DD-MM-YYYY').format('DD-MM-YYYY'));
						}
						savedreportdisplay();
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
                if($("#selection_type").val() == ""){
					$("#selection_type").next(".error-alert").text("Select a type.");
					return;
				}else{
					$("#selection_type").next(".error-alert").text("");
				}

				if($("#start_date input").val() == ""){
					$("#start_date").next(".error-alert").text("Select start date.");
					return;
				}else{
					$("#start_date").next(".error-alert").text("");
				}
				if($("#end_date input").val() == ""){
					$("#end_date").next(".error-alert").text("Select end date.");
					return;
				}else{
					$("#end_date").next(".error-alert").text("");
				}


				var obj={};
				reportData={};

				obj.user = $("#sub_user").val();
				obj.selection_type = $("#selection_type").val();
				obj.startDate = moment($("#start_date input").val(),"DD-MM-YYYY HH:mm:ss").format("DD-MM-YYYY HH:mm:ss");
			   	obj.endDate = moment($("#end_date input").val(),"DD-MM-YYYY HH:mm:ss").format("DD-MM-YYYY HH:mm:ss");
				obj.type='assign_analysis';
				obj.subtype='';
				obj.name = $("#sub_user option:selected").text();

				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_ManAnalysisController/generateReport')?>",
				   	data : JSON.stringify(obj),
					dataType:'json',
					success: function(data){
						console.log(data)
						var temp=[];
						if(error_handler(data)){
							return;
						}
						// if(data.resultoutput.length > 0){
						if(data.length > 0){
						   /*	for(i=0; i<data.length; i++){
								data[i].Lead_Genarate_Date = moment(data[i].Lead_Genarate_Date).format("DD-MM-YYYY HH:mm:ss")
							}*/
							buildHtmlTable(data,"#excelDataTable" , obj, "Generated")
							if(reportArray.length == 2 ){
								sessionStorage.setItem('reportOutput', JSON.stringify(data));
								sessionStorage.setItem('reportInput', JSON.stringify(obj));


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
			  var total=0;
				if(state == "Saved"){
					$("#report_title").html(state+" Analysis  for <u>"+ inputdata.name +"</u>  from "+
                    "<u>"+ moment($("#start_date input").val(), 'DD-MM-YYYY HH:mm:ss').format(dateTimeFormat) +"</u> to <u>"+
                    moment($("#end_date input").val(), 'DD-MM-YYYY HH:mm:ss').format(dateTimeFormat) +"</u>" );
				}else{
					$("#report_title").html(state+" Analysis  for <u>"+ inputdata.name +"</u>  from <u>"+
                    moment(inputdata.startDate, 'DD-MM-YYYY HH:mm:ss').format(dateTimeFormat) +"</u> to <u>"+
                    moment(inputdata.endDate, 'DD-MM-YYYY HH:mm:ss').format(dateTimeFormat) +"</u>" );
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
				/* add  Time_To_Assign key  to myList*/
				for (var c1 = 0; c1 < myList.length; c1++) {
			console.log(myList[c1])
					var date1 = moment(myList[c1].Generate_Accepted_Date, 'DD-MM-YYYY HH:mm:ss');
					var date2 = moment(myList[c1].Assigned_Date , 'DD-MM-YYYY HH:mm:ss');
					var diff = date2.diff(date1, 'seconds');
					if(myList[c1].hasOwnProperty('Time_To_Assign') == false){
						myList[c1]['Time_To_Assign'] = diff;
					}
				}
				$(selector).html("");
				var columns = addAllColumnHeaders(myList, selector);
				var row$ = '';
				var cell="";

				for (var c = 0; c < columns.length; c++) {
					if(columns[c] == 'Generate_Accepted_Date'){
						columns[c] = columns[c].split("_");
						cell += '<th style="text-align: left;width:135px; white-space: nowrap;">'+columns[c][1]+' '+columns[c][2] +'</th>';
						columns[c] = columns[c].join('_');
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
						console.log(columns[colIndex])
						var breakup =[]
						if(columns[colIndex] == 'Generate_Accepted_Date'){
							cellValue = moment(cellValue, 'DD-MM-YYYY HH:mm:ss').format(dateTimeFormat);
						}
						if(columns[colIndex] == 'Assigned_Date'){
							cellValue = moment(cellValue, 'DD-MM-YYYY HH:mm:ss').format(dateTimeFormat);
						}
                         if(columns[colIndex] === "Time_To_Assign"){
                            /* 	
							-------converting into seconds and making sum -----*/
							total = total + parseInt(cellValue); 
							
							cellValue = ddhhmmss(parseInt(cellValue));
						} 
						datacell += '<td style="text-align: left;">'+ cellValue +'</td>';
					}
					$(selector +" tbody").append("<tr>"+datacell+"</tr>");
				}

				var avgTxt = "<p>Average time duration of "+ myList.length  + " " + inputdata.selection_type + " is " +  ddhhmmss(parseInt(total/myList.length)) +'</p>';
				
				/* var avgTxt = "<p>Average time duration of "+ myList.length  + " Leads is " +  moment.utc(parseInt(total/myList.length)*1000).format('HH:mm:ss') +'</p>'; */
                if(inputdata.selection_type != 'Opportunity'){
                    $("#report_title").append(avgTxt);
                }
				export_report('assignment_analysis_view');
			}

			function dayformat(breakup){
				if( parseInt(breakup[0]) >= 24){
					var quotient = Math.floor(parseInt(breakup[0])/24);
					var remainder = parseInt(breakup[0]) % 24;
					if(remainder == 0){
						return quotient +' Days 00 Hrs '+ breakup[1]+' Min ' + breakup[2] + ' Sec';
					}else{
						return quotient +' days '+ remainder +' Hrs ' + breakup[1]+' Min ' + breakup[2] + ' Sec' ;
					}

				}else{
					 return breakup[0] +' Hrs ' + breakup[1]+' Min ' + breakup[2] + ' Sec';
				}
			}
			
		
			function ddhhmmss(s){
				var fianlly = "";
				var d = s / 3600;
				var d1 = s % 3600;
				
				var m = d1/60;
				var m1 = d1%60;
				finalVal = parseInt(d) +':'+ parseInt(m) +':'+ m1;
				return dayformat(finalVal.split(':'));
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
					/* reportData.endDate = moment(reportData.endDate,"DD-MM-YYYY HH:mm:ss").format("DD-MM-YYYY HH:mm:ss"); */
				}

				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_ManAnalysisController/save_report/assign_analysis')?>",
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
				<div class="row" >
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
                                <option value="Leads">Lead</option>
                                <option value="Opportunity">Opportunity</option>
                                <option value="Customer">Customer</option>
							</select>
							<span class="error-alert"></span>
						</div>

						<div class="col-md-3">
							<label for="start_date">Start Date*</label>
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
				<!---------------------------- ---------------------------- -------------------------- -------------------------------
					---------------------------- ----------------------------- report settings section  -------------------------------
					---------------------------- -------------------------------------------------------------------------------------->
					<div class="col-md-12 no-padding none" id="chart_type">
						<h4>Configure Report</h4><hr>
						<div class="row " >
							<div class="row">
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




