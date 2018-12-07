<!DOCTYPE html>
<html lang="en">
    <head>
		<?php require __DIR__.'/../scriptfiles.php' ?>

		<script type="text/javascript">
			var reportData={};
			$(document).ready(function(){
				user_list_();
				
				/* $('#start_date').datetimepicker({
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
					$(this).closest(".row").find("#end_date").data("DateTimePicker").minDate(startDateTime);
					valueChange()
				});

				$("#end_date").on("dp.change", function (selected) {
					valueChange()
				});

				$("#start_date input").val(moment().format("DD-MM-YYYY"));
				$("#end_date input").val(moment().format("DD-MM-YYYY")); */
				
				get_heirarchy(reportArray[0]);


			});


			/* ---------------------------------------------clear button click-------------------------------------- */

			function cancel_report_setting(){
				$("#user_list,#cust_list").val('');
				
				/* $('#start_date').data().DateTimePicker.date(null);
				$('#end_date').data().DateTimePicker.date(null); */
				
				valueChange()
			}
			
			
			/* ---------------------------------------------onchange of saved value-------------------------------------- */
			function valueChange(){
					$(".error-alert").text("");

					var obj={};

					obj.user = $("#user_list").val();
					obj.customer = $("#cust_list").val();
					
					/* obj.startDate  = $("#start_date input").val();
					obj.endDate = $("#end_date input").val(); */
					
					obj.report_name = $("#report_name") .val();

					var changedInput=0;
					if(reportArray.length == 3 ){

						if( obj.user== reportData.user && 
							obj.customer== reportData.customer /* && 
							obj.endDate == reportData.endDate &&
							obj.startDate == reportData.startDate */){
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

					if(reportArray.length == 2 ){
						if(	obj.user== reportData.user && 
							obj.customer== reportData.customer /* && 
							obj.endDate == reportData.endDate &&
							obj.startDate == reportData.startDate  */){
								
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
                    obj1.type='Cus_acqcostanalysis';
				    obj1.subtype='Summary';

					$.ajax({
						type: "POST",
						url:"<?php echo site_url('reports/standard_PerfAnalysisController/getsaved_reports')?>",
						data : JSON.stringify(obj1),
						dataType:'json',

						success: function(data){
							if(error_handler(data)){
								return;
							}

							reportData={};
							reportData.user= reportData.user;
							reportData.customer= reportData.customer; 
							/* reportData.startDate = moment(data.startdate,'DD-MM-YYYY').format('DD-MM-YYYY');
							reportData.endDate = moment(data.enddate,'DD-MM-YYYY').format('DD-MM-YYYY'); */
							reportData.report_name = data.report_name;
							reportData.type='Cus_acqcostanalysis';

							$(".pageHeader1 h2").text(reportData.report_name);

							$("#user_list").val(data.user);
							$("#cust_list").val(data.customer);
							
							/* $("#start_date input").val(reportData.startDate);
							$("#end_date input").val(reportData.endDate); */
							
							$("#report_name").val(reportData.report_name);
							reportData.subtype='Summary';
							reportData.user_name = $("#user_list option:selected").text();
							reportData.customer_name = $("#cust_list option:selected").text();
							buildHtmlTable(data.tabledetails,"#excelDataTable" , reportData, "Saved Report", reportData.name);
							
						},
						error:function(data){
							network_err_alert(data);
						}
					})
				}
            }

			/* --------------------------------------------------------------------------------------------------------
			----------------------------------User list ----------------------------------------------------------------
			------------------------------------------------------------------------------------------------------------ */
            function user_list_(){
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_RepAnalysisController/get_employees')?>",
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
						var select = $("#user_list"), options = "<option value=''>Select</option>";
						select.empty();
						if(data.length > 0){
							for(var i=0;i<data.length; i++){
								if(i == 0){
									cust_list_(data[i].user_id);
								}
								options += "<option value='"+data[i].user_id+"'>"+ data[i].user_name+"</option>";

							}
						}
						
						select.append(options);

						savedreportdisplay();
						loaderHide();

					},
					error:function(data){
						network_err_alert(data);
					}
				});
			}
			
			
			function userChange(user){
				cust_list_(user);
				valueChange();
			}
			/* --------------------------------------------------------------------------------------------------------
			----------------------------------Customer list ----------------------------------------------------------------
			------------------------------------------------------------------------------------------------------------ */
            function cust_list_(user){

				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_CusAnalysisController/get_cust/')?>"+user,
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
						var select = $("#cust_list"), options = "<option value=''>Select</option>";
						select.empty();
						for(var i=0;i<data.length; i++){
							options += "<option value='"+data[i].customer+"'>"+ data[i].customer_name+"</option>";

						}
						select.append(options);
						/* --------------------------------------------------------------------- */
						if(reportArray.length == 2 ){
							if (getCookie("reportOutput") != "" && getCookie("reportInput") !="") {
								reportOutput = JSON.parse(getCookie("reportOutput"));
								reportInput = JSON.parse(getCookie("reportInput"));
								user_name = $("#user_list option[value='"+reportInput.user+"']").text();
								customer_name = $("#cust_list option[value='"+reportInput.customer+"']").text();
								
								buildHtmlTable(reportOutput,"#excelDataTable" , reportInput, "Last viewed Report" , user_name+' '+ customer_name)
							}
						}

						/* --------------------------------------------------------------------- */
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
			var count=0;

			var chain = [],chain1 = [];
			function generateReport(pos){
				var obj={};

				if(pos == 'back'){
					initial = JSON.parse(getCookie("initial"));
					$("#user_list").val(initial.input.user)
					$("#cust_list").val(initial.input.customer)
					get_data(initial.input,initial.state,initial.name);
					/* breadcrumb(initial.brdcm);
					chain = initial.brdcm */

					/* ------------------------ */
					setCookie('reportOutput', '',1);
					setCookie('reportInput', '',1);
					/* ------------------------ */
				}else{

					if($("#user_list").val() == ""){
						$("#user_list").next(".error-alert").text("Select a User.");
						return;
					}else{
						$("#user_list").next(".error-alert").text("");
						
					}
					if($("#cust_list").val() == ""){
						$("#cust_list").next(".error-alert").text("Select a Customer.");
						return;
					}else{
						$("#cust_list").next(".error-alert").text("");
					}

					/* if($("#start_date input").val() == ""){
						$("#start_date").next(".error-alert").text("Select Start date.");
						return;
					}else{
						$("#start_date").next(".error-alert").text("");
					}
					if($("#end_date input").val() == ""){
						$("#end_date").next(".error-alert").text("Select End date.");
						return;
					}else{
						$("#end_date").next(".error-alert").text("");
					}
					*/


					obj.user = $("#user_list").val();
					obj.customer = $("#cust_list").val();
					
					/* obj.startDate = moment($("#start_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
					obj.endDate = moment($("#end_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY"); */
					
					obj.type='Cus_acqcostanalysis';
					obj.subtype='Summary';
					
					user_name = $("#user_list option:selected").text();
					customer_name = $("#cust_list option:selected").text();
					
					get_data(obj, "Generated Report", user_name+' '+customer_name);
					
					var initial={};
					initial.input=obj;
					initial.state = "Generated Report";
					initial.name = user_name+' '+customer_name;

					setCookie('initial', JSON.stringify(initial),1);
			 
					
				}
			}
			/* -================================================================================================================== */

			function child_report(id, name, state){
				if(reportData.hasOwnProperty('startDate') && reportData.hasOwnProperty('endDate') && reportData.hasOwnProperty('subtype')){
					
					reportData["user"] = id;
					reportData["customer"] = name;
					reportData["subtype"] = "Details";
					
					get_data(reportData,state, name)
				}
			}
			/* -================================================================================================================== */
			function get_data(obj,state, name){
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_CusAnalysisController/generateReport')?>",
				   	data : JSON.stringify(obj),
					dataType:'json',
					success: function(data){
						var temp=[];
						if(error_handler(data)){
							return;
						}

						if(data.length > 0){
							buildHtmlTable(data,"#excelDataTable" , obj, state , name)
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

			function buildHtmlTable(myList,selector, inputdata, state , name) {
				
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

				$('#export_to').html("");

				/* $('#export_to').append('<button class="btn" title="PDF" onclick="gimg()"><span class="fa fa-file-pdf-o"></span> PDF  </button>'); */
				$('#export_to').append('<button class="btn" title="CSV" ><span class="fa fa-file-excel-o"></span> CSV  </button>');
				$('#export_to').append('<button class="btn" title="Print Report" onclick="window.print();" ><span class="fa fa-print"></span> Print Report  </button>');
				$('#export_to').append('<button class="btn" title="Schedule Report"><span class="fa fa-clock-o"></span> Schedule  </button>');


				/* ====================================================================================== */
				
				$("#report_title").html(state+" for <b>"+ name +"</b><hr>");

				//table_built(myList,selector, inputdata, state);
				pie_Chart(myList,inputdata, state);
			}


			function table_built(myList,selector, inputdata, state){

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
						if(columns[colIndex] != "opportunity_product"){
							datacell += '<td style="text-align: left;">'+ cellValue +'</td>';
						}
					}
					if(state == "Last viewed Report" || state == "First Level Report" || myList[0].hasOwnProperty('Contact_Number')){
						$(selector +" tbody").append('<tr>'+datacell+'</tr>');
					}else{
						$(selector +" tbody").append('<tr onclick="child_report(\''+myList[i].opportunity_product+'\', \''+inputdata.selectionId+'\',\'First Level Report\')">'+datacell+'</tr>');

					}
				}
				if(isPlayer == 1){
					$('.mediPlayer').mediaPlayer();
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
				}
				if(reportArray.length == 3 ){
					reportData.startDate = moment(reportData.startDate,"DD-MM-YYYY").format("DD-MM-YYYY");
					/* reportData.endDate = moment(reportData.endDate,"DD-MM-YYYY").format("DD-MM-YYYY");	 */
				}
                console.table(reportData);
				$.ajax({
					type: "POST",
				   	url:"<?php echo site_url('reports/standard_PerfAnalysisController/save_report/opp_performanceanalysis')?>",
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
				function go_back(){
					generateReport('back')

					$("#direction ol").find('.child').remove();
					$("#direction ol").find('.last').addClass("active");

					if(reportArray.length == 3 ){
						$("#previousPage .btn").removeAttr("onclick");
						$("#previousPage .btn").attr("href", "<?php echo site_url('manager_standard_analytics'); ?>#savedReport="+report);
					}
					if(reportArray.length == 2 ){
						$("#previousPage").html("");
					}
				}
				
				function pie_Chart(data , inputdata , state){
					$("#piechart_3d").show();
					$("#table_view").hide();
					var columnSet2 = [];
					var columnSet = [];
					var total=0
					
					for (var i = 0; i < data.length; i++) {
						var rowHash = data[i];
						var columnSet1 = [];							
						for (var key in rowHash) {	
							if ($.inArray(key, columnSet) == -1){
								if(key != "user"){
									columnSet1.push(rowHash[key]);
								}
								
							}
						}
						
						var summary = "Title : "+columnSet1[0]+
						"\n Duration :  Hrs "+
						"\n Percentage :  %";
						/* columnSet1.push(createCustomHTMLContent(columnSet1[0],dutation)); */
						columnSet1.push(summary);
						if(data[i].hasOwnProperty('user')){
							columnSet1.push(data[i].opportunity_id);
						}
						
						columnSet.push(columnSet1);
						
					}
					
					/* ------------------------------------------------------------------------- */
					for(i=0; i<columnSet.length; i++){
						columnSet[i][1] = moment.duration(columnSet[i][1]).asSeconds();
					}
					google.charts.load('current', {
						'packages': ['corechart']
					});
					google.charts.setOnLoadCallback(drawChart);

					function drawChart(){
						data = new google.visualization.DataTable();
						data.addColumn({ type: 'string', id: 'Name' });
						data.addColumn({ type: 'number', id: 'duration' });	
						data.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'Html': true}});							
						data.addColumn({type: 'string', id: 'lid'});
						
						
						data.addRows(columnSet);
						var report_title =state+" Report for "+ inputdata.name +" On "+ moment(inputdata.startDate, "DD-MM-YYYY").format('ll');
						var options = {
							
							pieSliceTextStyle: {
								color: 'white',
							},
							/* title: report_title, */
							is3D: true,
							/*colors:["red","blue", "green"]
							 tooltip: { isHtml: true } */
						};

						var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
						chart.draw(data, options);
						if(reportData.hasOwnProperty('startDate') && reportData.hasOwnProperty('endDate') && reportData.hasOwnProperty('type')){
							var selectHandler = function(e){
								var selectedItem = chart.getSelection()[0];
								if (selectedItem){
									var topping = data.getValue(selectedItem.row, 3);
									child_report(topping , data.getValue(selectedItem.row, 0), "Second Level Report");
								}
								chart.setSelection([]);
							}
							google.visualization.events.addListener(chart, 'select', selectHandler);
						}
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
				<div class="row" >
					<div class="col-md-10 no-padding" id="report_input_data">

						<div class="col-md-3">
							<label for="user_list">User Name*</label>
							<div class="form-group">
								<select onchange="userChange(this.value)" class="form-control" id="user_list"></select>
								<span class="error-alert"></span>
							</div>
						</div>
                        	<div class="col-md-3">
							<label for="cust_list">Customer Name*</label>
							<div class="form-group">
                            	<select class="form-control" id="cust_list" onchange="valueChange()">
                                </select>
								<span class="error-alert"></span>
							</div>
						</div>
						<div class="col-md-3">
							<!--<label for="start_date">Start Date*</label>
							<div class="form-group">
								<div class='input-group date' id='start_date'>
									<input type='text' class="form-control" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<span class="error-alert"></span>
							</div>-->
						</div>
						<div class="col-md-3">
							<!--<label for="end_date">End Date*</label>
							<div class="form-group">
								<div class='input-group date' id='end_date'>
									<input type='text' class="form-control" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<span class="error-alert"></span>
							</div>-->
						</div>

					</div>
					<div class="col-md-2 no-padding">
						<br>
						<span class="pull-right">
							<input type="button" class="btn btn-default" id="generate_save_btn" onclick="generateReport('main')"  value="Generate">
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
						<div class="row">
							<div class="col-md-3">
								<!--<div>
									<select class="form-control" onchange="reportType(this.value)">
										<option value="line">Pie Chart</option>
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
						<div class="print graph_view none" id="piechart_3d" ></div>
						<div class ="print table_view" id="table_view" >
							<table class="table" id="excelDataTable" border="1" style="max-width:50%; min-width:300px; margin:auto"></table>
						</div>
					</div>
				</div>
				</div>

			</div>
        </div>

        <?php require __DIR__.'/../footer.php' ?>


    </body>
</html>




