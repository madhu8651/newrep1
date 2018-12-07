<!DOCTYPE html>
<html lang="en">
    <head>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<?php require 'scriptfiles.php' ?>
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
		</style>
		<script type="text/javascript">
			
			$(document).ready(function(){
				  
				list_view()
				$('#selected_date').datetimepicker({
                    format: 'DD-MM-YYYY',
					minDate: moment("01-01-2000", 'DD-MM-YYYY')					
                });
				$('#selected_start_time').datetimepicker({
                    format: 'LT'
                });
				$('#selected_end_time').datetimepicker({
                    format: 'LT'
                });
				$("#selected_start_time").on("dp.change", function (e) {
					$('#selected_end_time').data("DateTimePicker").minDate(e.date);
					$("#selected_end_time input[type=text]").val("");
					$('#selected_end_time').data().DateTimePicker.date(null);
				});
				
				
				google.charts.load("current", {packages:["timeline"]});
				google.charts.setOnLoadCallback(drawChart);
			})
			function list_view(){
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('manager_teamManagersController/get_manager_info')?>",
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
						var select = $("#user_name"), options = "<option value=''>Select</option>";
						select.empty();
						for(var i=0;i<data.length; i++){
							var modul_name =""
							var modul = JSON.parse(data[i].modules);
							if(modul.cxo != "0"){
								modul_name += '(<span>cxo</span>)';
							}
							if(modul.Manager != "0"){
								modul_name += '(<span>manager</span>)';
							}
							if(modul.sales != "0"){
								modul_name += '(<span >sales</span>)';
							} 
							options += "<option value='"+data[i].rep_id+"'>"+ data[i].repname+" "+modul_name+"</option>";
						}
						select.append(options);
						loaderHide();
					}
				});
			}
			
			function cancel_report_setting(selectedModal){
				$("#"+selectedModal).modal("hide");
				$("#user_name").val("");
				$('#selected_date').data().DateTimePicker.date(null);
				$('#selected_start_time').data().DateTimePicker.date(null);
				$('#selected_end_time').data().DateTimePicker.date(null);
			}
			
			function drawChart() {
				var container = document.getElementById('timeline-tooltip');
				var chart = new google.visualization.Timeline(container);
				var dataTable = new google.visualization.DataTable();

				dataTable.addColumn({ type: 'string', id: 'Room' });
				dataTable.addColumn({ type: 'string', id: 'Name' });    
				dataTable.addColumn({type: 'string', role: 'title'});
				dataTable.addColumn({type: 'string', role: 'details'});
				dataTable.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
				dataTable.addColumn({ type: 'date', id: 'Start' });
				dataTable.addColumn({ type: 'date', id: 'End' });
				var rowdata=	[	
									[ 'Meeting',  		'CSS Fundamentals',    "2017-10-22 08:00:00", "2017-10-22 10:00:50", "manju" ],
									[ 'Meeting',  		'Intro JavaScript',    "2017-10-22 14:30:00", "2017-10-22 16:00:00", "pawan" ],
									[ 'Meeting',  		'Advanced JavaScript', "2017-10-22 16:30:00", "2017-10-22 19:45:50", "suri" ],
									[ 'Incoming Email', 'Intermediate Perl',   "2017-10-22 12:30:00", "2017-10-22 14:00:00", "dilip" ],
									[ 'Incoming Email', 'Email 2',   		   "2017-10-22 14:30:00", "2017-10-22 14:55:00", "prasanth" ],
									[ 'Outgoing Email', 'Advanced Perl',       "2017-10-22 14:30:00", "2017-10-22 16:00:00", "kumar" ],
									[ 'Outgoing Call', 	'Applied Perl',        "2017-10-22 16:30:00", "2017-10-22 18:00:00", "ravi" ],
									[ 'Incoming Call',  'Google Charts',   	   "2017-10-22 12:30:00", "2017-10-22 14:00:00", "harish K" ],
									[ 'Incoming SMS',   'Closure',             "2017-10-22 14:30:44", "2017-10-22 16:00:00", "harish G" ],
									[ 'Outgoing SMS',   'App Engine',          "2017-10-22 16:30:00", "2017-10-22 18:30:00", "amog" ]
								];
								
				var mainArray=[];
				for (i=0; i < rowdata.length; i++){	
					var start 	= moment(rowdata[i][2], 'YYYY-MM-DD HH:mm:ss');		
					var year	= start.format('YYYY');
					var month   = start.format('MM');
					var day   	= start.format('DD');
					var hour   	= start.format('HH');
					var min   	= start.format('mm');
					var sec   	= start.format('ss');
					
					var end 	= moment(rowdata[i][3], 'YYYY-MM-DD HH:mm:ss');		
					var end_year	= end.format('YYYY');
					var end_month   = end.format('MM');
					var end_day   	= end.format('DD');
					var end_hour   	= end.format('HH');
					var end_min   	= end.format('mm');
					var end_sec   	= end.format('ss');
					
					/*Details data -- click to display*/
					/* var detail = rowdata[i][0] +
					" "+rowdata[i][1]+
					" "+new Date(year,month,day,hour,min,sec)+
					" "+new Date(end_year,end_month,end_day,end_hour,end_min,end_sec)+
					" "+rowdata[i][4]; */
					
					
					var subArray = [rowdata[i][0], 
									null, 
									rowdata[i][1], 
									detail(	rowdata[i][0],
											rowdata[i][1],
											new Date(year,month,day,hour,min,sec),  
											new Date(end_year,end_month,end_day,end_hour,end_min,end_sec), 
											rowdata[i][4]
											), 
									createCustomHTMLContent(rowdata[i][0],
															new Date(year,month,day,hour,min,sec),  
															new Date(end_year,end_month,end_day,end_hour,end_min,end_sec), 
															rowdata[i][4]
															),
									new Date(year,month,day,hour,min,sec), 
									new Date(end_year,end_month,end_day,end_hour,end_min,end_sec)
								]
					
					mainArray.push(subArray)
				}

				dataTable.addRows(mainArray);
				var options = {
					/*backgroundColor: '#ffd',
					 focusTarget: 'category',
					tooltip: { isHtml: 'true' }, */
					timeline: 	{ 	colorByRowLabel: true,
									rowLabelStyle: {fontName: 'Helvetica', fontSize: 16, color: '#603913' },
									barLabelStyle: { fontName: 'Garamond', fontSize: 15 } 
								},
					
				};

				chart.draw(dataTable, options);

				function selectHandler(){
					var selections = chart.getSelection();
					if(selections.length == 0){
						alert('Nothing selected');
					}else{
						var selection = selections[0];
						$("#details").html(dataTable.getValue(selection.row, 3))
					}
				};

				google.visualization.events.addListener(chart, 'select', selectHandler); 	
				 
			}


			function createCustomHTMLContent(subject, start, end, comment) {
				start = moment(new Date(start));
				end = moment(new Date(end));
				var cal_duration = moment.duration(moment(end, 'lll').diff(moment(start, 'lll'))).asMilliseconds("");
				var duration = moment.utc(cal_duration).format("HH:mm:ss");
				return '<div class="infopopup" style="" >' +
				'<table class="medals_layout">' + '<tr>'  +
				'<td colspan="2"><b>' + subject + '</b><hr></td>' + '</tr>' + '<tr>' +
				'<td><b>Start Time: </b></td><td>' +  moment(start, 'lll').format('lll') + '</b></td>' + '</tr>' + '<tr>' +
				'<td><b>End Time: </b></td><td>' + moment(end, 'lll').format('YYYY-MM-DD HH:mm:ss') + '</b></td>' + '</tr>' + '<tr>' +
				'<td><b>Duration: </b></td><td>' + duration + '</b></td>' + '</tr>' + '<tr>' +
				'<td><b>Comment: </b></td><td>' + comment + '</b></td>' + '</tr>' + '</table>' + '</div>';
			 
			}
			function detail(subject, event, start, end, comment) {
				start = moment(new Date(start));
				end = moment(new Date(end));
				var cal_duration = moment.duration(moment(end, 'lll').diff(moment(start, 'lll'))).asMilliseconds("");
				var duration = moment.utc(cal_duration).format("HH:mm:ss");
				return '<div class="infopopup" style="" >' +
				'<table class="medals_layout">' + '<tr>'  +
				'<td colspan="2"><b>' + subject + '</b> '+event+'<hr></td>' + '</tr>' + '<tr>' +
				'<td><b>Start Time: </b></td><td>' +  moment(start, 'lll').format('lll') + '</b></td>' + '</tr>' + '<tr>' +
				'<td><b>End Time: </b></td><td>' + moment(end, 'lll').format('YYYY-MM-DD HH:mm:ss') + '</b></td>' + '</tr>' + '<tr>' +
				'<td><b>Duration: </b></td><td>' + duration + '</b></td>' + '</tr>' + '<tr>' +
				'<td><b>Comment: </b></td><td>' + comment + '</b></td>' + '</tr>' + '</table>' + '</div>';
			 
			}
			function report_setting(){
				$('#report_setting').modal('show');
			}
			function save_report_setting(){
				if($("#user_name").val() == ""){
					$("#user_name").next(".error-alert").text("Select a user.");
					return;
				}else{
					$("#user_name").next(".error-alert").text("");
				}
				
				if($("#selected_date input").val() == ""){
					$("#selected_date").next(".error-alert").text("Select a Date.");
					return;
				}else{
					$("#selected_date").next(".error-alert").text("");
				}
				if($("#selected_start_time input").val() == ""){
					$("#selected_start_time").next(".error-alert").text("Select start time.");
					return;
				}else{
					$("#selected_start_time").next(".error-alert").text("");
				}
				if($("#selected_end_time input").val() == ""){
					$("#selected_end_time").next(".error-alert").text("Select end time.");
					return;
				}else{
					$("#selected_end_time").next(".error-alert").text("");
				}
				
				var obj={};
				obj.user = $("#user_name").val();
				obj.selectedDate = $("#selected_date input").val();
				obj.starttime = $("#selected_start_time input").val();
				obj.endtime = $("#selected_end_time input").val();
				
				$("#user_name").val("");
				
				$('#selected_start_time').data().DateTimePicker.date(null);
				$('#selected_end_time').data().DateTimePicker.date(null);
			}
			
			</script>


    </head>
	<body class="hold-transition skin-blue sidebar-mini">   
		<!--<div class="loader">
			<center><h1 id="loader_txt"></h1></center>
	</div>-->
        <?php require 'demo.php' ?>
        <?php require 'manager_sidenav.php' ?>          
        <div class="content-wrapper body-content">
        	<div class="col-lg-12 column">
				
			
			<div class="row header1">				
				<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
					<span class="info-icon">
						<div>	
								<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-toggle="tooltip" data-placement="right" title="Work Pattern Analysis"/>

						</div>
					</span>
				</div>
				<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
					<h2>Work Pattern Analysis</h2>	
				</div>
				<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
					<div class="addBtns" onclick="add_lead()">
						<!--<a href="#leadinfoAdd" class="addPlus" data-toggle="modal" >
							<img src="<?php echo site_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
						</a>
						<a  class="addExcel" onclick="addExl()" >
							<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
						</a>-->
					</div>
					<div style="clear:both"></div>
				</div>
				<div style="clear:both"></div>
			</div>
			
			<ul class="nav nav-tabs nav-justified">
				<li class="active"><a data-toggle="tab" href="#standard">Standard Report</a></li>
				<li><a data-toggle="tab" href="#custom">Custom Report</a></li>
				<li><a data-toggle="tab" href="#scheduled">Scheduled Report</a></li>
			</ul>

			<div class="tab-content">
				<div id="standard" class="tab-pane fade in active">
					<center><h3>Standard Report</h3></center>
					
					<div class="setting">
						<a href="#" onclick="report_setting()"><i class="fa fa-cog fa-3" aria-hidden="true" ></i></a>
					</div>
					<div id="timeline-tooltip" style="height: 450px;"></div>
				</div>
				<div id="custom" class="tab-pane fade">
					<center><h3>Custom Report</h3></center>
					<div style="height: 450px;"></div>
				</div>
				<div id="scheduled" class="tab-pane fade">
					<center><h3>Scheduled Report</h3></center>
					<div style="height: 450px;"></div>
				</div>
			</div>
			<div id ="details"></div>
			<div id="report_setting" class="modal fade" data-backdrop='static'>
				<div class="modal-dialog" >
					<div class="modal-content">
						<div class="modal-header modal-title">
							<span class="close" onclick="cancel_report_setting('report_setting')">&times;</span>
							<h4 class="modal-title">Setting</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-3">
									<label for="user_name">User*</label>
								</div>
								<div class="col-md-9">
									<select class="form-control" id="user_name"></select>
									<span class="error-alert"></span>
								</div>
								<div class="col-md-3">
									<label for="selected_date">Date*</label>
								</div>
								<div class="col-md-9">
									<div class="form-group">
										<div class='input-group date' id='selected_date'>
											<input type='text' class="form-control" readonly />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="col-md-3">
									<label for="selected_start_time">Start Time*</label>
								</div>
								<div class="col-md-9">
									<div class="form-group">
										<div class='input-group date' id='selected_start_time'>
											<input type='text' class="form-control" readonly />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-time"></span>
											</span>
										</div>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="col-md-3">
									<label for="selected_end_time">End Time*</label>
								</div>
								<div class="col-md-9">
									<div class="form-group">
										<div class='input-group date' id='selected_end_time'>
											<input type='text' class="form-control" readonly />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-time"></span>
											</span>
										</div>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<input type="button" class="btn btn-default" onclick="save_report_setting()" value="Save">
							<input type="button" class="btn btn-default" onclick="cancel_report_setting('report_setting')" value="Cancel" >
						</div>
					</div>
				</div>
			</div>
			
			
			<center><h1>This page is under construction</h1></center>
        </div>
        <?php require 'footer.php' ?>

    </body>
</html>
