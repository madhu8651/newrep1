<!DOCTYPE html>
<html lang="en">
    <head>
		<?php require __DIR__.'/../scriptfiles.php' ?>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>	
		<script src="<?php echo base_url()?>js/html2canvas.js"></script>
<script src="<?php echo base_url()?>js/jspdf.min.js"></script>
<script>
 function gimg(){
	 html2canvas($("#canvas"), {
	    onrendered: function(canvas) {
	       var imgsrc = canvas.toDataURL('image/png',1.0);
		    var pdf = new jsPDF('landscape');

		  pdf.addImage(imgsrc, 'JPEG', 10, 50, 250, 100); 
		  pdf.save("download.pdf");
        }
     });
 }
  
</script>
		<style>
			.infopopup{
				padding:5px 5px 5px 5px; 
				width:200px
			}
			.infopopup hr{
				margin:5px 0px;
			}
			.infopopup table{
				width:100%;
			}
			.setting{
				font-size: 30px;
				height: 40px;
				width: 40px;
				position: absolute;
				margin-top: -42px;
				right: 0px;
			}
			.page-container{
				width:100%;
				max-width:1500px;
				margin:auto;
				clear:both;
				padding: 0px 20px;
			}
			select optgroup{
				background:#000;
				color:#fff;
				font-style:normal;
				font-weight:normal;
			}
			
			#export_to button{        
			width: 120px;
			text-align: left;
			margin:0px;
			margin-bottom: 5px;
			}
			#export_to button .fa{    font-size: 20px;}
			.no-padding{
				padding :0px;
			}
			/* ----------------------------------------------------------------------- */
			.mediPlayer .control {
				opacity        : 0; /* transition: opacity .2s linear; */
				pointer-events : none;
				cursor         : pointer;
			}

			.mediPlayer .not-started .play, 
			.mediPlayer .paused .play,
			.mediPlayer .playing .pause			{
				opacity : 1;

			}


			.mediPlayer .playing .play {
				opacity : 0;
			}

			.mediPlayer .ended .stop {
				opacity        : 1;
				pointer-events : none;
			}

			.mediPlayer .precache-bar .done {
				opacity : 0;
			}

			.mediPlayer .not-started .progress-bar, .mediPlayer .ended .progress-bar {
				display : none;
			}

			.mediPlayer .ended .progress-track {
				stroke-opacity : 1;
			}

			.mediPlayer .progress-bar,
			.mediPlayer .precache-bar {
				transition        : stroke-dashoffset 500ms;

				stroke-dasharray  : 298.1371428256714;
				stroke-dashoffset : 298.1371428256714;
			}
			.graph_view{
				height:450px;
			}
			/* ------------------------------------------------------------------------------------------------------------------------------ */
			@media print  {
				.row{
					display:none;
				}
				.row.print #chart_type{
					display:none;
				}
				.row.print{
					display:block;
				}
				.row, .row.print *{
					padding:0px
				}
				table{
					font-size:60%
				}
				table thead tr{
					background:#000;
					color:#fff;
				}
			}
		</style>
		<script type="text/javascript">
				
               
			var data_req ={};
			$(document).ready(function(){
				list_view()
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

				
				if(reportArray.length == 2 ){
					if (getCookie("reportOutput") != "" && getCookie("reportInput") !="") {
						buildHtmlTable(JSON.parse(getCookie("reportOutput")),"#excelDataTable" , JSON.parse(getCookie("reportInput")), "Last viewed")
						reportData=JSON.parse(getCookie("reportInput"));
					}
				}

				/* --------------------------------------------------------------------- */
				savedreportdisplay();
			});
			
			
			/* ---------------------------------------------clear button click-------------------------------------- */
			
			function cancel_report_setting(){
				$("#report_input_data input, #report_input_data select").val("");
				$('#start_date').data().DateTimePicker.date(null);
				$('#end_date').data().DateTimePicker.date(null);
				valueChange()
				/* if(reportArray.length == 2 ){
					$("#canvas,#chart_type,#report_title").css("visibility", "hidden");
					$("#save_report_btn").hide();
					$("#generate_save_btn").removeAttr("disabled");
				} */
			}
			/* ---------------------------------------------onchange of saved value-------------------------------------- */
			function valueChange(){
					var obj={};
					obj.category = $("#sub_user").val();
					obj.startdate  = $("#start_date input").val();
					obj.enddate = $("#end_date input").val();
					obj.report_name = $("#report_name") .val();
					
					var changedInput=0;
					if(reportArray.length == 3 ){
						if( obj.category == reportData.category &&
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
					
					if(reportArray.length == 2 && data_req.hasOwnProperty("category")){
						
						if( obj.category == data_req.category &&
						obj.startdate == data_req.startdate &&
						obj.enddate == data_req.enddate) {
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
                    obj1.type='Lead_Distribution_Analysis';
				    obj1.subtype='NA';

					$.ajax({
						type: "POST",
						url:"<?php echo site_url('reports/standard_LeadAnalysisController/getsaved_reports')?>",
						data : JSON.stringify(obj1),
						dataType:'json',

						success: function(data){
						console.log(reportArray)
							console.log(data)
							if(error_handler(data)){
								return;
							}

							reportData={};
							reportData.category = data.category;
							/* reportData.selection_type = data.selection_type; */
							reportData.startdate = moment(data.startdate,'DD-MM-YYYY').format('DD-MM-YYYY');
							reportData.enddate = moment(data.enddate,'DD-MM-YYYY').format('DD-MM-YYYY'); 
							//reportData.report_name = data.report_nme;
							reportData.type = 'Lead_Distribution_Analysis';
							if(data.lid){	
								reportData.lid = data.lid;
								reportData.subtype='details';	
							}else{								
								reportData.subtype='NA';
							}
							
							$("#sub_user").val(reportData.category);
							/* $("#selection_type").val(reportData.selection_type); */
							$("#start_date input").val(reportData.startdate);
							$("#end_date input").val(reportData.enddate);
							$("#report_name").val(data.report_nme);
							//reportData.name = $("#sub_user option:selected").text();
							data_req.category = data.category; 
							data_req.startdate = moment(data.startdate,'DD-MM-YYYY').format('DD-MM-YYYY');
							data_req.enddate = moment(data.enddate,'DD-MM-YYYY').format('DD-MM-YYYY'); 
							data_req.type='Lead_Distribution_Analysis';
							if(data.lid){
								buildHtmlTable(data.tabledetails,"#excelDataTable" , reportData, "Child Report");
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
					url:"<?php echo site_url('reports/standard_RepAnalysisController/get_employees')?>",
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
						var select = $("#sub_user"), options = "<option value=''>Select</option>";
						select.empty();
						for(var i=0;i<data.length; i++){
							options += "<option value='"+data[i].user_id+"'>"+ data[i].user_name+"</option>";
							
						}
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
				if($("#end_date input").val() == ""){
					$("#end_date").next(".error-alert").text("Select End date.");
					return;
				}else{
					$("#end_date").next(".error-alert").text("");
				}
				
				
				var obj={};
				reportData={};
				
				obj.user_type = $("#sub_user option:selected").text();
				obj.user_id = $("#sub_user").val();
				obj.startdate = moment($("#start_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
				obj.enddate = moment($("#end_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
				obj.type='Lead_FAT_Analysis';	
				console.log(obj)
				return;
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
							buildHtmlTable(data,"#excelDataTable" , obj, "Generated") 
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
			function send_data(clicked_id){
				$("#previousPage").html('<button type="button" class="btn" onclick="go_back()"><i class="fa fa-arrow-left fa-3" aria-hidden="true" style="margin-left: 0px;margin-right: 3px;"></i>Back</button>');
				$("#piechart_3d").hide();
				$("#table_view").show();
				$("#chart_select").hide();
				$("#direction").hide();
				$("#remarks_descriction").hide();
				data_req["lid"] = clicked_id;
				data_req["subtype"] = "details";
				console.log(data_req)
				if(reportArray.length == 2 || reportArray.length == 3){
					$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_LeadAnalysisController/generateReport')?>",
					data : JSON.stringify(data_req),
					dataType:'json',
					success: function(data){
						console.log(data)
						buildHtmlTable(data,"#excelDataTable" , data_req, "Child Report");
					},
					error:function(data){
						network_err_alert(data);
					}
				})
				}
				
			}
			function buildHtmlTable(myList,selector, inputdata, state) {
				if(state == "Child Report"){
					reportType('text');	
					$("#chart_select").hide();		
				}else{
					reportType('line');	
					$("#chart_select").val("line");
					$("#chart_select").show();
				} 
				console.log(myList)
				if(state !="Child Report"){
					format(myList,inputdata, state);					
				}
				/* if(state == "Saved"){
					$("#report_title").html(state+" report is for <u>"+ inputdata.name +"</u>  from <u>"+ $("#start_date input").val() +"</u> to <u>"+ $("#end_date input").val() +"</u><hr>");
				}else{
					$("#report_title").html(state+" report is for <u>"+ inputdata.name +"</u>  from <u>"+ moment(inputdata.startDate, "DD-MM-YYYY").format('DD-MM-YYYY') +"</u> to <u>"+ moment(inputdata.endDate, "DD-MM-YYYY").format('DD-MM-YYYY') +"</u><hr>");
				} */
				if(state == "Saved"){
					$("#report_title").html(state+" report is for <u>"+ inputdata.category +"</u>  From <u>"+ $("#start_date input").val() +"</u>  From <u>"+ $("#end_date input").val() +"</u><hr>");
				}else{
					$("#report_title").html(state+" report is for <u>"+ inputdata.category +"</u>  From <u>"+ moment(inputdata.startdate, "DD-MM-YYYY").format('DD-MM-YYYY') +"</u>  To <u>"+ moment(inputdata.enddate, "DD-MM-YYYY").format('DD-MM-YYYY') +"</u><hr>");
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
						cell += '<th style="text-align: left;width:200px;">'+ columns[c].split("_").join(" ") +'</th>';
					}else{
						if(columns[c] != "lid"){		
							if( columns[c] != "category_name"){
								cell += '<th style="text-align: left;width:135px; white-space: nowrap;">'+ columns[c].split("_").join(" ") +'</th>';
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
						
						if(columns[colIndex] != "lid"){		
							if( columns[colIndex] != "category_name"){
								datacell += '<td style="text-align: left;">'+ cellValue +'</td>';
							}
						}
					}
					if(state !="Child Report"){
						tableRow = "<tr id='"+myList[i].lid+"' onclick='send_data(this.id)'>"+datacell+"</tr>";
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
					
				$('#export_to').append('<button class="btn" title="PDF" onclick="gimg()"><span class="fa fa-file-pdf-o"></span> PDF  </button>');
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
						/* if(key != "lid"){
							if(key != "category_name"){
																
							}
						} */
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
				console.log(reportData)
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
					reportData.type='Lead_Distribution_Analysis';
				}
				if(reportArray.length == 3 ){
					//reportData.startDate = moment(reportData.startDate,"DD-MM-YYYY").format("DD-MM-YYYY");				
					/* reportData.endDate = moment(reportData.endDate,"DD-MM-YYYY").format("DD-MM-YYYY");	 */
				}
				console.log(reportData)		
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_LeadAnalysisController/save_report')?>",
					data : JSON.stringify(reportData),
					dataType:'json',
					success: function(data){
						console.log(data)
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
					  
						var columnSet2 = [];
						var columnSet = [];
						for (var i = 0; i < data.length; i++) {
							var rowHash = data[i];
							var columnSet1 = [];							
							for (var key in rowHash) {	
								if ($.inArray(key, columnSet) == -1){
									if(key == "lead_count"){
										columnSet1.push(parseInt(rowHash[key]));
									}else{
										columnSet1.push(rowHash[key]);
									}
								}
							}
							
							columnSet.push(columnSet1);
							
						}
						
						
						/* ------------------------------------------------------------------------- */
						
						
						/* google.charts.load("current", {packages:["corechart"]});
						google.charts.setOnLoadCallback(drawChart);
						function drawChart() {
							aa = columnSet
							var data = google.visualization.arrayToDataTable(aa); 

							var options = {
								title: 'My Daily Activities',
								is3D: true,
							};
							var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
							chart.draw(data, options);
						} 
						createCustomHTMLContent(rowdata[i][0],
															new Date(rowdata[i][2]),
															new Date(rowdata[i][3]),
															rowdata[i][4]
															),
						
						*/
						
						google.charts.load('current', {
							'packages': ['corechart']
						});
						google.charts.setOnLoadCallback(drawChart);

						function drawChart(){

						data = new google.visualization.DataTable();
						data.addColumn({ type: 'string', id: 'lname' });
						data.addColumn({ type: 'number', id: 'lead_count' });
						data.addColumn({type: 'string', id: 'lid'});
						data.addColumn({ type: 'string', id: 'category_name' });
						
						data.addRows(columnSet);
						var report_title =state+" Report for "+ inputdata.lname +" On "+ moment(inputdata.startdate, "DD-MM-YYYY").format('ll');
						
						
						var options = {							
							pieSliceTextStyle: {
								color: 'black',
							},
							is3D: true,
						};

						var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
							chart.draw(data, options);
							var selectHandler = function(e) { 
							var selectedItem = chart.getSelection()[0];
							if (selectedItem) {
								var topping = data.getValue(selectedItem.row, 2);
									$("#piechart_3d").hide();
									$("#table_view").show();
									$("#chart_select").hide();
									$("#direction").hide();
									$("#remarks_descriction").hide();
									$("#previousPage").html('<button type="button" class="btn" onclick="go_back()"><i class="fa fa-arrow-left fa-3" aria-hidden="true" style="margin-left: 0px;margin-right: 3px;"></i>Back</button>');
									reportData["lid"] = topping;
									reportData["subtype"] = "details";
									console.log(reportData)
									if(reportArray.length == 2 || reportArray.length == 3){
										$.ajax({
										type: "POST",
										url:"<?php echo site_url('reports/standard_LeadAnalysisController/generateReport')?>",
										data : JSON.stringify(reportData),
										dataType:'json',
										success: function(data){	
											console.log(data)
											buildHtmlTable(data,"#excelDataTable" , reportData, "Child Report");
										},
										error:function(data){
											network_err_alert(data);
										}
									})
									}
							}
							chart.setSelection([]);
						}
						google.visualization.events.addListener(chart, 'select', selectHandler);
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
				<div class="row" >						
					<div class="col-md-10 no-padding" id="report_input_data">
						<div class="col-md-4">
							<label for="sub_user">Category*</label>
							<select class="form-control" id="sub_user" onchange="valueChange()">
								<option value="">Choose</option>
								<option value="representative">Representative</option>
								<option value="industry">Industry</option>
								<option value="location">Business</option>
								<option value="product">Product</option>
							</select>
							<span class="error-alert"></span>
						</div>
						
						<div class="col-md-4">
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
						<div class="col-md-4">
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
						<br>
						<span class="pull-right">
							<input type="button" class="btn btn-default" id="generate_save_btn" onclick="generateReport()"  value="Generate">
							<input type="button" class="btn btn-default" onclick="cancel_report_setting()" value="Clear" >
						</span>
					</div>
					
					
				</div>
				<hr>
				<div class="row print">
					
					<!---------------------------- ---------------------------- -------------------------- -------------------------------
					---------------------------- ----------------------------- report section report section -------------------------------
					---------------------------- -------------------------------------------------------------------------------------->
					<div class="col-md-8 no-padding print" id="canvas">
						<center><h4 id="report_title"></h4></center>
						<div class="print graph_view" id="piechart_3d" ></div>
						<div class ="print table_view none" id="table_view" >
							<table class="table" id="excelDataTable" border="1"></table>
						</div>
					</div>
					
					
					
					<!---------------------------- ---------------------------- -------------------------- -------------------------------
					---------------------------- ----------------------------- report settings section  -------------------------------
					---------------------------- -------------------------------------------------------------------------------------->
					<div class="col-md-4 no-padding none" id="chart_type">
						<h4>Configure Report</h4><hr>
						<div class="row " >							
							<div class="row">
								<div class="col-md-9">
									<div>
										<select class="form-control" id="chart_select" onchange="reportType(this.value)">

											<option value="line">Pie Chart</option>
											<option value="text">Text Chart</option>
										</select>
										<span class="error-alert"></span>
									</div>
									<div >
										<input onkeyup="valueChange()"type="text" class="form-control" id="report_name" placeholder="Report Name (Mandatory)" />
										<span class="error-alert"></span>
									</div>
									<br>
									<center>
										<input type="button" class="btn btn-default none" id="update_report_btn" disabled onclick="saveReport('update')" value="Save">
										<input type="button" class="btn btn-default none" id="saveAs_report_btn" disabled onclick="saveReport('saveAs')" value="Save As">
									</center>
									<center>
										<input type="button" class="btn btn-default none" id="save_report_btn" disabled onclick="saveReport('save')" value="Save Report">
									</center>
								</div>
								<div class="col-md-3 no-padding" id="export_to">
										
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
				
			</div>
        </div>
        
        <?php require __DIR__.'/../footer.php' ?>
		

    </body>
</html>




