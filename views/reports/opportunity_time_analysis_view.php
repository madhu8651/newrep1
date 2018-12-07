<!DOCTYPE html>
<html lang="en">
    <head>
		<?php require __DIR__.'/../scriptfiles.php' ?>
		
		<script type="text/javascript">

			/* var date = moment('01-03-2018');
			var firstdaywk = moment(date).startOf('week').format("DD-MM-YYYY");
			alert(" firstDay of week : "+ firstdaywk +
			"\n lastday of week : "+ moment(firstdaywk , "DD-MM-YYYY").add(7,'days').format("DD-MM-YYYY")) */
			function getMonday(d) {
				d = new Date(d);
				var day = d.getDay(), diff = d.getDate() - day + (day == 0 ? -7:0); /*  adjust when day is sunday */
				return new Date(d.setDate(diff));
			}
			var chain = [],chain1 = [];	
			var reportData={}
			var reportInput={}
			/* --------------------------------------------------------------- */
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
					var startDateTime = moment($("#start_date input").val(),'DD-MM-YYYY');
					$(this).closest(".row").find("#end_date").data("DateTimePicker").minDate(startDateTime);
					valueChange()
				});
				
				$("#end_date").on("dp.change", function (selected) {					
					valueChange()
				});
				
				$("#start_date input").val(moment().format("DD-MM-YYYY"));
				$("#end_date input").val(moment().format("DD-MM-YYYY"));
				get_heirarchy(reportArray[0])

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
				valueChange()
			}
			/* ---------------------------------------------onchange of saved value-------------------------------------- */
			function valueChange(){
					var obj={};
					obj.opportunity = $("#opportunity_list").val();
                    obj.selectiontype = $("#sub_type").val();
					 /*obj.selectiontype = $("#selection_type").val(); */
					obj.startDate  = $("#start_date input").val();
					obj.endDate = $("#end_date input").val();
					obj.report_name = $("#report_name") .val();

					var changedInput=0;
					if(reportArray.length == 3 ){
						
						if( obj.opportunity== reportData.opportunity && obj.endDate == reportData.endDate &&
						obj.startDate == reportData.startDate && obj.selectiontype == reportData.selectiontype
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
					if(reportArray.length == 2 ){
						if(obj.opportunity== reportData.opportunity && obj.endDate == reportData.endDate &&
							obj.startDate == reportData.startDate && obj.selectiontype == reportData.selectiontype ){
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
                    obj1.type='opp_timeanalysis';
				    obj1.subtype='Summary';

					$.ajax({
						type: "POST",
						url:"<?php echo site_url('reports/standard_OppAnalysisController/getsaved_reports')?>",
						data : JSON.stringify(obj1),
						dataType:'json',
						success: function(data){
							if(error_handler(data)){
								return;
							}
							console.log(data)
							reportData={};
							/*reportData.user = data.userid;
							 reportData.selection_type = data.selection_type; */
							reportData.startDate = moment(data.startdate,'DD-MM-YYYY').format('DD-MM-YYYY');
							reportData.endDate = moment(data.enddate,'DD-MM-YYYY').format('DD-MM-YYYY');
							reportData.report_name = data.report_name;
							reportData.opportunity = data.opportunity;
							reportData.selectiontype = data.selectiontype;
							reportData.subtype='Summary';
							
							for(i=0; i< data.tabledetails.length; i++){
								if(data.tabledetails[i].hasOwnProperty("STATUS")){
									reportData["subtype_name"] = data.tabledetails[i].Activity_Name;
								}
							}
							if(data.hasOwnProperty("subtype_Id")){
								reportData["subtype_Id"] = data.subtype_Id;
								reportData.subtype='Details';
							}
							$(".pageHeader1 h2").text(data.report_name);

							$("#report_name").val(reportData.report_name);
							$("#opportunity_list").val(reportData.opportunity);
							$("#sub_type").val(reportData.selectiontype);
							$("#start_date input").val(reportData.startDate);
							$("#end_date input").val(reportData.endDate);
							
						   if(data.hasOwnProperty("name")){
							   reportData.name = data.name;
							}else{
                               reportData.name = $("#opportunity_list option:selected").text();
							}


							reportInput = reportData;
							buildHtmlTable(data.tabledetails,"#excelDataTable" , reportData, "Saved Report",reportData.name);
							
						
						},
						error:function(data){
							network_err_alert(data);
						}
					})
				}
            }
			
			/* --------------------------------------------------------------------------------------------------------
			----------------------------------opportunity--------------------------------------------------------------------------------------------
			------------------------------------------------------------------------------------------------------------ */
            function list_view(){
				console.log(getCookie("reportInput"))
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_OppAnalysisController/get_opportunity')?>",
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
						sort_list(data, 'string', 'opportunity_name');

						var select = $("#opportunity_list"), options = "<option value=''>Select</option>";
						select.empty();
						for(var i=0;i<data.length; i++){
							options += "<option value='"+data[i].opportunity_id+"'>"+ data[i].opportunity_name+"</option>";

						}
						select.append(options);
						if(getCookie("reportInput") != ''){
							var prviouse_data =  $.parseJSON(getCookie("reportInput"));
							$("#opportunity_list option[value='"+prviouse_data.opportunity+"']").attr("selected", true);
							$("#sub_type option[value='"+prviouse_data.selectiontype+"']").attr("selected", true);
							$("#start_date input").val(prviouse_data.startDate);
							$("#end_date input").val(prviouse_data.endDate);
						}
						/* --------------------------------------------------------------------- */

							
							if(reportArray.length == 2 ){
								if (getCookie("reportOutput") != "" && getCookie("reportInput") !="") {
									reportOutput = JSON.parse(getCookie("reportOutput"));
									reportInput = JSON.parse(getCookie("reportInput"));
									
									name = $("#opportunity_list option[value='"+reportInput.opportunity+"']").text();
									
									if(reportInput.hasOwnProperty('subtype_name')){
										name = reportInput.subtype_name
									}
									buildHtmlTable(reportOutput,"#excelDataTable" , reportInput, "Last viewed Report" , name )
								}
							}

							/* --------------------------------------------------------------------- */
						loaderHide();
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
			var count = 0;
			function generateReport(){
				
				if($("#opportunity_list").val() == ""){
					$("#opportunity_list").next(".error-alert").text("Select a Option.");
					return;
				}else{
					$("#opportunity_list").next(".error-alert").text("");
				}
                if($("#sub_type").val() == ""){
					$("#sub_type").next(".error-alert").text("Select a Option.");
					return;
				}else{
					$("#sub_type").next(".error-alert").text("");
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
				reportInput={};

				obj.opportunity = $("#opportunity_list").val();
				obj.selectiontype = $("#sub_type").val();
				obj.startDate = moment($("#start_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
				obj.endDate = moment($("#end_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
				obj.type='opp_timeanalysis';
				obj.subtype='Summary';
				/* obj.name = $("#sub_user option:selected").text(); */
				get_data(obj,"Generated Report",$("#opportunity_list option:selected").text());
				
				/* -====================================== */
				count++;
				if(count <= 1){
					$("#direction ol li").each(function(){
						chain.push($(this).text());
						chain1.push($(this).text());
					})
					var temp = {};
					temp.chain = chain;
					temp.input = obj;
					/* setCookie('temp', JSON.stringify(temp),1); */
				}
				 
				if(chain.length - 1 == chain1.length){
					chain.splice(-1,1);
				}
				if(chain.length - 2 == chain1.length){
					chain.splice(-1,1);chain.splice(-1,1);
				}
				
				/* breadcrumb(chain)  */
				/* -====================================== */
				
			}
			/* -================================================================================================================== */
			
			function child_report(id, name, state){
				 if(reportData.hasOwnProperty('startDate') && reportData.hasOwnProperty('endDate') && reportData.hasOwnProperty('type')){

						if(chain.length - 1 == chain1.length){
							chain.splice(-1,1);
						}
						if(chain.length - 2 == chain1.length){
							chain.splice(-1,1);chain.splice(-1,1);
						}
						chain.push(name);
						
						var actid=id.split("--"), mainId="";
						
						reportData["subtype"] = "Summary";
						if(actid.length == 1){
							mainId=actid[0];
						}else if(actid.length >= 2 && state=="Detailed Report"){
							mainId=actid[0];
							reportData["subtype"] = "Details";
							reportData["subtype_Id"] = actid[1];
							reportData["subtype_name"] = name;
							reportData["selectiontype"] = actid[2];
						}
					   
					   reportData["opportunity"] = mainId;
					   get_data(reportData,state, name)

				 }
			}
			/* -================================================================================================================== */
			function get_data(obj,state, name){
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_OppAnalysisController/generateReport')?>",
				   	data : JSON.stringify(obj),
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
                       
						if(data.length > 0){
							buildHtmlTable(data,"#excelDataTable" , obj, state , name)
							if(reportArray.length == 2 ){
								setCookie('reportOutput', JSON.stringify(data),1);
								setCookie('reportInput', JSON.stringify(obj),1);
							}
							$("#chart_type").show();
							reportData = obj;
						}else{
							$("#chart_type").hide();
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
				export_report('opportunity_time_analysis_view');

				/* ====================================================================================== */

                if(state == "First Level Report"){
					/* $("#direction ol li").removeClass("active");
					$("#direction ol").append('<li class="active child"><a href="#">'+name+'</a></li>'); */
					/* breadcrumb(chain) */
					
					/* ----ramoved to avoid back --
					$("#opportunity_list").val(inputdata.opportunity); */
					
					$("#chart_select").show();
					if(reportArray.length == 3 ){
						$("#previousPage").html('<h4><a class="btn" href="<?php echo site_url('manager_standard_analytics'); ?>#savedReport='+reportArray.join('_')+'"><i class="fa fa-arrow-left fa-3" aria-hidden="true"></i> Back</a></h4>');
						$("#previousPage").html('<h4><a class="btn" href="<?php echo site_url('manager_standard_analytics'); ?>#savedReport='+reportArray.join('_')+'"><i class="fa fa-arrow-left fa-3" aria-hidden="true"></i> Back</a></h4>');
					}else if(reportArray.length == 2 ){
						$("#previousPage").html('<button type="button" class="btn" onclick="go_back()"><i class="fa fa-arrow-left fa-3" aria-hidden="true" style="margin-left: 0px;margin-right: 3px;"></i>Back</button>');
					}
					format(myList,inputdata, state);
                }else if(state == "Detailed Report"){
					/* $("#direction ol li").removeClass("active");
					$("#direction ol").append('<li class="active child"><a href="#">'+name+'</a></li>'); */
					/* breadcrumb(chain) */
					table_built(myList,selector, inputdata, state);
					if(reportArray.length == 3 ){
						$("#previousPage").html('<h4><a class="btn" href="<?php echo site_url('manager_standard_analytics'); ?>#savedReport='+reportArray.join('_')+'"><i class="fa fa-arrow-left fa-3" aria-hidden="true"></i> Back</a></h4>');
					}else if(reportArray.length == 2 ){
						$("#previousPage").html('<button type="button" class="btn" onclick="go_back()"><i class="fa fa-arrow-left fa-3" aria-hidden="true" style="margin-left: 0px;margin-right: 3px;"></i>Back</button>');
					}
					
				}else{
					if(reportInput.hasOwnProperty('subtype_Id')){
						table_built(myList,selector, inputdata, state);
					}else{
						format(myList,inputdata, state);
					}

				}
                 var detailrepTitle="";
                 if(inputdata.hasOwnProperty('subtype_Id')){
                    if(state == "Last viewed Report" ||state == "Detailed Report" ||state == "Saved Report"){
                        detailrepTitle = '<b>'+$("#opportunity_list option[value='"+inputdata.opportunity+"']").text() +'</b> for';
                    }
                 }
				if(state == "Last viewed Report"){
					state = "Last viewed Analysis";
				}
				if(state == "Detailed Report"){
					state = "Detailed Analysis";
				}
				if(state == "Saved Report"){
					state = "Saved Analysis";
				}
				if(state == "Generated Report"){
					state = "Generated Analysis";
				}
                //detailed report of opportuniaty <opp> for <resource/activity>
				$("#report_title").html(state+" of Opportunity "+detailrepTitle+" <b>"+name+"</b> From <u>"+ moment(inputdata.startDate, "DD-MM-YYYY").format(dateFormat) +"</u> To <u>"+ moment(inputdata.endDate, "DD-MM-YYYY").format(dateFormat) +"</u>");

				
			}
			
			
			function chk_phnNo_frmt(num){
				var type = '', nO = [];
				if(num.indexOf('phone') > 0){
					num = JSON.parse(num);
					$.each(num, function(key, value){
						if(num.hasOwnProperty('phone')){
							type = 'json';
							for(i=0; i< num.phone.length; i++){
								if($.trim(num.phone[i]) != ""){
									nO.push($.trim(num.phone[i]));
								}
							}
						}
					})
				}else{
					type = 'array';
				}

				if(type == 'json'){
					return nO.join(', ');
				}else{
					return num;
				}
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
						if(columns[c] != "opportunity_id"){
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
						var cellValue = $.trim(myList[i][columns[colIndex]]);
						console.log(columns[colIndex])
						/* --------------------------------- */
						if(columns[colIndex] == "Contact_Number"){
							cellValue = chk_phnNo_frmt(cellValue);
						}
						if(columns[colIndex] == "Content"){
							if(cellValue == "'no_path'" || cellValue == null || cellValue == "" ){
								cellValue= "Not available";
							}else if (cellValue.indexOf('.m4a') > 0 || cellValue.indexOf('.mp3') > 0 ){
								isPlayer = 1;
								cellValue = "<?php echo base_url(); ?>uploads/"+cellValue;
								/* cellValue = 	'<div class="mediPlayer">'+
													'<audio class="listen" preload="none" data-size="250" src="'+cellValue+'"></audio>'+
												'</div>'; */
									cellValue = '<audio controls controlsList="nodownload">' +
												  '<source src="'+cellValue+'" type="audio/ogg">' +
												  '<source src="'+cellValue+'" type="audio/mpeg">' +
												'</audio>';
							}
						}
						if(columns[colIndex] == 'Start_Date'){
							cellValue = moment(cellValue, 'DD-MM-YYYY HH:mm:ss').format(dateTimeFormat);
						}
						if(columns[colIndex] == 'End_Date'){
							cellValue = moment(cellValue, 'DD-MM-YYYY HH:mm:ss').format(dateTimeFormat);
						}
						/* --------------------------------- */
						if(columns[colIndex] != "id"){
							datacell += '<td style="text-align: left;">'+ cellValue +'</td>';
						}
					}
					if(myList[i].hasOwnProperty("Name")){
						var event = myList[i].Name
					}
					if(myList[i].hasOwnProperty("Name")){
						var event = myList[i].Name
					}
					if(state == "Last viewed Report" ||state == "Detailed Report" ){
						$(selector +" tbody").append('<tr>'+datacell+'</tr>');
					}else{
						$(selector +" tbody").append('<tr onclick="child_report(\''+myList[i].ID+'\', \''+event+'\',\'First Level Report\')">'+datacell+'</tr>');

					}
				}
				if(isPlayer == 1){
					$('.mediPlayer').mediaPlayer();
					/* $('.mediPlayer').each(function(){
						$(this).mediaPlayer();
					}) */
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
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_OppAnalysisController/save_report/opp_timeanalysis')?>",
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
					generateReport();
					$("#previousPage").html('');
					/* 
					location.reload();
					$("#direction ol").find('.child').remove();
					$("#direction ol").find('.last').addClass("active"); 
					*/
					
					
					/* $("#table_view").hide();
					$("#chart_select").show(); 
					
					$("#previousPage").html("");
					$("#chart_select").val("line");
					
					if($("#piechart_3d").css('display') == 'none'){
						$("#piechart_3d").show();
						$("#table_view").hide();
						return;
					}else if($("#table_view").css('display') == 'none'){
						$("#table_view").show();
						$("#piechart_3d").hide();
						return;
					}*/
				}
				
				function format(data , inputdata , state){
					$("#piechart_3d").show();
					$("#table_view").hide();
					var columnSet2 = [];
					var columnSet = [];
					var total=0
					for (var j = 0; j < data.length; j++) {
						total += moment(data[j]["Time_duration"],"HH.mm.ss").diff(moment().startOf('day'), 'seconds');
					}
					for (var i = 0; i < data.length; i++) {
						var rowHash = data[i];
						var columnSet1 = [];							
						for (var key in rowHash) {	
							if ($.inArray(key, columnSet) == -1){
								if(key != "ID"){
									columnSet1.push(rowHash[key]);
								}

							}
						}
						
						/*
						var time = moment(rowHash["Time_duration"] , "HH:mm:ss");
						var dutation = time.format("HH") +" Hrs "+time.format("mm")+" Min "+ time.format("ss") +" Sec"; 
						var sec = time.diff(moment().startOf('day'), 'seconds');
						*/
						
						var sec = moment(rowHash["Time_duration"],"HH.mm.ss").diff(moment().startOf('day'), 'seconds');
						
						var time = rowHash["Time_duration"].split(":");
							
						var day_hour = time[0] +" Hrs ";
						
						if(parseInt(time[0]) >= 24){
							var quotient = Math.floor(parseInt(time[0])/24);
							var remainder = parseInt(time[0]) % 24;
							if(remainder == 0){
								day_hour = quotient +" Days ";
							}else{
								day_hour = quotient +" Days " + remainder + " Hrs ";
							}
						}
						
						var summary = 	inputdata.selectiontype+" : "+ columnSet1[0] +
										"\n Duration : " + day_hour +
										time[1] +" Min "+ 
										time[2] +" Sec"+
										"\n Percentage : "+ ((sec/total) * 100).toFixed(1)+" %";
						/* columnSet1.push(createCustomHTMLContent(columnSet1[0],dutation)); */
						columnSet1.push(summary);
						if(data[i].hasOwnProperty('ID')){
							columnSet1.push(inputdata.opportunity+'--'+data[i].ID+'--'+inputdata.selectiontype);
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
						data.addColumn({type: 'string', id: 'ID'});
						
						
						data.addRows(columnSet);
						var report_title =state+" Analysis for "+ inputdata.name +" On "+ moment(inputdata.startDate, "DD-MM-YYYY").format(dateFormat);
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
									child_report(topping , data.getValue(selectedItem.row, 0), "Detailed Report");
								}
								chart.setSelection([]);
							}
							google.visualization.events.addListener(chart, 'select', selectHandler);
						}
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

						<div class="col-md-3">
							<label for="opportunity_list">Opportunity*</label>
							<div class="form-group">
								<select onchange="valueChange()" class="form-control" id="opportunity_list"></select>
								<span class="error-alert"></span>
							</div>
						</div>
                        <div class="col-md-3">
							<label for="sub_type">Sub Type*</label>
							<select class="form-control" id="sub_type" onchange="valueChange()">
								<option value="">Choose</option>
								<option value="Resource">Resource</option>
								<option value="Activity">Activity</option>
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
							<input type="button" class="btn btn-default" id="generate_save_btn" onclick="generateReport(1)"  value="Generate">
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




