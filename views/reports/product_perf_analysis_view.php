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
				get_heirarchy(reportArray[0]);

				
			});


			/* ------------------------opportunity---------------------clear button click-------------------------------------- */

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
					$(".error-alert").text("");
					
					if($("#product_list").val() == "All"){
						$("#selection_type").closest('.col-md-3').fadeIn();
					}else{
						$("#selection_type").closest('.col-md-3').fadeOut();
					}
					var obj={};

					obj.product = $("#product_list").val();

					obj.selectiontype = $("#selection_type").val();
					obj.startDate  = $("#start_date input").val();
					obj.endDate = $("#end_date input").val();
					obj.report_name = $("#report_name") .val();
                    if(reportData.selectiontype == ""){
                           obj.selectiontype = "";
                    }
					var changedInput=0;
					if(reportArray.length == 3 ){

						if( obj.product== reportData.product &&
							obj.endDate == reportData.endDate &&
							obj.startDate == reportData.startDate &&
							obj.selectiontype == reportData.selectiontype ) {
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
						if(	obj.product== reportData.product && 
							obj.endDate == reportData.endDate && 
							obj.startDate == reportData.startDate && 
							obj.selectiontype == reportData.selectiontype){
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
                    obj1.type='prod_performanceanalysis';
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
							reportData.product = data.product;
                            if(data.hasOwnProperty('selectiontype') ){
                                 reportData.selectiontype = data.selectiontype;
                            }else{
                              reportData.selectiontype = '';
                            }

							reportData.startDate = data.startDate;
							reportData.endDate = data.endDate;
							reportData.report_name = data.report_name;
							reportData.type='prod_performanceanalysis';
							
							$(".pageHeader1 h2").text(reportData.report_name);
							
							$("#product_list").val(data.product);
							$("#selection_type").val(data.selectiontype);
							$("#start_date input").val(reportData.startDate);
							$("#end_date input").val(reportData.endDate);
							$("#report_name").val(reportData.report_name);
							reportData.subtype='Summary';
							reportData.name = $("#product_list option:selected").text();
							buildHtmlTable(data.tabledetails,"#excelDataTable" , reportData, "Saved Report", reportData.name);
							if($("#product_list").val() == "All"){
								$("#selection_type").closest('.col-md-3').fadeIn();
							}else{
								$("#selection_type").closest('.col-md-3').fadeOut();
							}

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
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_PerfAnalysisController/get_product')?>",
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
						var select = $("#product_list"), options = "<option value=''>Select</option><option value='All'>All</option>";
						select.empty();
						for(var i=0;i<data.length; i++){
							options += "<option value='"+data[i].Product_ID+"'>"+ data[i].Product_Name+"</option>";

						}
						select.append(options);
						if(getCookie("reportInput") != ''){
							var prviouse_data =  $.parseJSON(getCookie("reportInput"));
							$("#product_list option[value='"+prviouse_data.product+"']").attr("selected", true);
							$("#selection_type option[value='"+prviouse_data.selectiontype+"']").attr("selected", true);
							$("#start_date input").val(prviouse_data.startDate);
							$("#end_date input").val(prviouse_data.endDate);
							if(prviouse_data.selectiontype != ''){
								$("#selection_type").closest('.col-md-3').fadeIn();
							}else{
								$("#selection_type").closest('.col-md-3').fadeOut();
							}
						}
						/* --------------------------------------------------------------------- */

							
							if(reportArray.length == 2 ){
								if (getCookie("reportOutput") != "" && getCookie("reportInput") !="") {
									reportOutput = JSON.parse(getCookie("reportOutput"));
									reportInput = JSON.parse(getCookie("reportInput"));
									name = $("#product_list option[value='"+reportInput.product+"']").text();
									if(reportInput.hasOwnProperty('activity_name')){
										name = reportInput.activity_name
									}
									buildHtmlTable(reportOutput,"#excelDataTable" , reportInput, "Last viewed Report" , name )
								}
							}

							/* --------------------------------------------------------------------- */
							savedreportdisplay();
						loaderHide();
						$("#product_list").change(function(){
							if($(this).val() == 'All'){
								$("#selection_type").val('');
							}
							
						});						
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
					$("#product_list").val(initial.input.product);
					if(initial.input.product == 'All'){
						$("#selection_type").closest('.col-md-3').fadeIn();
					}else{
						$("#selection_type").closest('.col-md-3').fadeOut();
					}
					get_data(initial.input,initial.state,initial.name);
					
					/* breadcrumb(initial.brdcm);	
					chain = initial.brdcm */
					
					/* ------------------------ */
					setCookie('reportOutput', '',1);
					setCookie('reportInput', '',1);
					/* ------------------------ */
				}else{
					
					if($("#product_list").val() == ""){
						$("#product_list").next(".error-alert").text("Select a Product.");
						return;
					}else{
						$("#product_list").next(".error-alert").text("");
						if($("#product_list").val() == "All"){
							if($("#selection_type").val() == ""){
								$("#selection_type").next(".error-alert").text("Select a type.");
								return;
							}else{
								$("#selection_type").next(".error-alert").text("");
							}
						}
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

					

					obj.product = $("#product_list").val();
					if(obj.product == 'All'){
						obj.selectiontype = $("#selection_type").val();
					}else{
						obj.selectiontype = '';
					}
					
					obj.startDate = moment($("#start_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
					obj.endDate = moment($("#end_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
					obj.type='prod_performanceanalysis';
					obj.subtype='Summary';
					
					get_data(obj,"Generated Report",$("#product_list option:selected").text());
					/* -====================================== */
						/* if(count <= 1){
							$("#direction ol li").each(function(){
								chain.push($(this).text());
								chain1.push($(this).text());
							})
						} */
						var initial={};
						var init = {};
						init.product = $("#product_list").val();
						if(obj.product == 'All'){
							init.selectiontype = $("#selection_type").val();
						}else{
							init.selectiontype = '';
						}
						init.startDate = moment($("#start_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
						init.endDate = moment($("#end_date input").val(),"DD-MM-YYYY").format("DD-MM-YYYY");
						init.type='prod_performanceanalysis';
						init.subtype='Summary';
						
						initial.input=init;
						initial.state = "Generated Report";
						initial.name = $("#product_list option:selected").text();
						initial.brdcm = chain1
						
						setCookie('initial', JSON.stringify(initial),1);
						/* =============================================== */
						if (getCookie("initial") != "" ) {
							initial = JSON.parse(getCookie("initial"));
							/* breadcrumb(initial.brdcm);	
							chain = initial.brdcm */

						}
					/* -====================================== */
				}
			}
			/* -======================================================= */
				/*  */
			function child_report(id, name, state){
				if(reportData.hasOwnProperty('startDate') && reportData.hasOwnProperty('endDate') && reportData.hasOwnProperty('subtype')){
					/* chain.push(name) */
					reportData["product"] = id;
					reportData["selectiontype"] = $.trim(name).split(' ').join('_');
					reportData["subtype"] = "Details";
                    $("#selection_type").closest('div.col-md-3').hide();
                    $("#selection_type").val(reportData.selectiontype);
                    $("#product_list").val(reportData.product);
					$("#previousPage").html('<button type="button" class="btn" onclick="go_back()"><i class="fa fa-arrow-left fa-3" aria-hidden="true" style="margin-left: 0px;margin-right: 3px;"></i>Back</button>');
					get_data(reportData,state, name)
				}
			}
			/* -=========================================================================== */
			function get_data(obj,state, name){
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_PerfAnalysisController/generateReport')?>",
				   	data : JSON.stringify(obj),
					dataType:'json',
					success: function(data){
						var temp=[];
						if(error_handler(data)){
							return;
						}
                        
						if(data.length > 0){
							buildHtmlTable(data,"#excelDataTable" , obj, state , name);
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

				export_report('product_perf_analysis_view');
				/* ====================================================================================== */
				type = $("#product_list option[value='"+inputdata.product+"']").text();
                var selectiontype = "";
                if(inputdata.selectiontype != ''){
                    selectiontype = '('+ inputdata.selectiontype.split('_').join(' ') + ')' ;
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
				$("#report_title").html(state+" for product <b class='text-capitalize' >"+ type +' '+ selectiontype+"</b> From <u>"+ moment(inputdata.startDate, 'DD-MM-YYYY').format(dateFormat) +"</u> To <u>"+ moment(inputdata.endDate, 'DD-MM-YYYY').format(dateFormat) +"</u>");


                if(state == "Detailed Report"){
					/* breadcrumb(chain) */
					/* $("#product_list").val(inputdata.product); */
					
					$("#chart_select").show();
					if(reportArray.length == 3 ){
						$("#previousPage").html('<h4><a class="btn" href="<?php echo site_url('manager_standard_analytics'); ?>#savedReport='+reportArray.join('_')+'"><i class="fa fa-arrow-left fa-3" aria-hidden="true"></i> Back</a></h4>')
					}else if(reportArray.length == 2 ){
						$("#previousPage").html('<h4><a class="btn" onclick="go_back()" href="javascript:void(0)"><i class="fa fa-arrow-left fa-3" aria-hidden="true"></i> Back</a></h4>');
					}
					table_built(myList,selector, inputdata, state);
                }else{
					if(name == "All" || inputdata.subtype == "Details" || myList[0].hasOwnProperty('Contact_Number')){
						table_built(myList,selector, inputdata, state);
					}else{
						format(myList,inputdata, state);
					}
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
						var cellValue = $.trim(myList[i][columns[colIndex]]);
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
						if(columns[colIndex] == 'Opportunity_Created_Date'){
							cellValue = moment(cellValue, 'DD-MM-YYYY').format(dateFormat);
						}
						/* if(columns[colIndex] == "Contact_Number"){
							contactformat(cellValue)
						} */
						if(columns[colIndex] == "Contact_Person"){
							cellValueA = cellValue.split('/');
							cellValue = "<span>"+$.trim(cellValueA[0])+"</span>";
							if($.trim(cellValueA[1]) != ""){
								cellValue += " ( <span>"+$.trim(cellValueA[1])+"</span> )";
							}
						}
						
						/* --------------------------------- */
						if(columns[colIndex] != "opportunity_product"){
							datacell += '<td style="text-align: left;">'+ cellValue +'</td>';
						}
						
						
					}
					if(state == "Last viewed Report" || state == "Detailed Report" || state == "Saved Report" || (myList[0].hasOwnProperty('Contact_Number') == false && inputdata.product != "All" )){
						$(selector +" tbody").append('<tr>'+datacell+'</tr>');
					}else{
						$(selector +" tbody").append('<tr onclick="child_report(\''+myList[i].opportunity_product+'\', \''+inputdata.selectiontype+'\',\'Detailed Report\')">'+datacell+'</tr>');
						
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
				   	url:"<?php echo site_url('reports/standard_PerfAnalysisController/save_report/prod_performanceanalysis')?>",
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
				  valueChange()
					generateReport('back')
					
					$("#direction ol").find('.child').remove();
					$("#direction ol").find('.last').addClass("active");
					
					if(reportArray.length == 3 ){
						$("#previousPage").html("");
						$("#previousPage .btn").removeAttr("onclick");
						$("#previousPage .btn").attr("href", "<?php echo site_url('manager_standard_analytics'); ?>#savedReport="+report);
					}
					if(reportArray.length == 2 ){
						$("#previousPage").html("");
					}
				}

				function format(data , inputdata , state){
					$("#piechart_3d").show();
					$("#table_view").hide();
					var columnSet = [];
					var total=0;
					
					for (var ii = 0; ii < data.length; ii++) {
					    data[ii].Product_Count =  parseInt(data[ii].Product_Count);
						total += data[ii].Product_Count;
					}
					
					for (var i = 0; i < data.length; i++) {
                          var temp = [];
                          var columnSet1 = [];
                          var myArray = data[i].Status_.split('_');

                          for (i1 in myArray) {
                              temp.push(capitalizeFirstLetter(myArray[i1]));
                          }
                          data[i].Status_ = temp.join(' ');
                          columnSet1.push(data[i].Status_);
                          columnSet1.push(data[i].Product_Count);
                          /* columnSet1.push("Product Status : "+capitalizeFirstLetter(data[i].Status.split('_').join(' '))+
										"\n Product_Count : " + data[i].Product_Count +
										"\n Percentage : "+ ((data[i].Product_Count/total) * 100).toFixed(1)+" %"); */
						columnSet1.push("Product Status : "+capitalizeFirstLetter(data[i].Status_.split('_').join(' ')));
                         columnSet1.push(data[i].opportunity_product);

						 columnSet.push(columnSet1);
					}

					/* ------------------------------------------------------------------------- */

					google.charts.load('current', {
						'packages': ['corechart']
					});
					google.charts.setOnLoadCallback(drawChart);

					function drawChart(){
						data = new google.visualization.DataTable();
						data.addColumn({ type: 'string', id: 'Name' });
						data.addColumn({ type: 'number', id: 'duration' });	
						data.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'Html': true}});							
						data.addColumn({type: 'string', id: 'title'});

						
						data.addRows(columnSet);
						
						var report_title =state+" Analysis for "+ inputdata.name +" On "+ moment(inputdata.startDate, "DD-MM-YYYY").format(dateFormat);
						var options = {
							pieSliceTextStyle: {
								color: 'white',
							},
							is3D: true,
						};

						var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
						chart.draw(data, options);
						console.log(reportData)
						if(reportData.hasOwnProperty('startDate') && reportData.hasOwnProperty('endDate') && reportData.hasOwnProperty('subtype')){
							var selectHandler = function(e){
								var selectedItem = chart.getSelection()[0];
								if (selectedItem){
								        /* if(reportData.hasOwnProperty('report_name')){
											return;
										} */
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

				/* function reportType(val){
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
				} */
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
							<label for="product_list">Product*</label>
							<div class="form-group">
								<select onchange="valueChange()" class="form-control" id="product_list"></select>
								<span class="error-alert"></span>
							</div>
						</div>
                        <div class="col-md-3 none">
							<label for="opportunity_list">Selection type*</label>
							<div class="form-group">
                            	<select class="form-control" id="selection_type" onchange="valueChange()">
							   	<option value="">Select</option>
								<option value="Live">Active</option>
								<option value="Closed_Won">Closed Won</option>
								<option value="Temporary_Loss">Temporary Loss</option>
								<option value="Permanent_Loss">Permanent Loss</option>
                                </select>
								<span class="error-alert"></span>
							</div>
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
									<select class="form-control" id="report_type" onchange="reportType(this.value)">
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




