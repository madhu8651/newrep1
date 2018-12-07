<html lang="en">
	<head>
	<script>
		var baseurl="<?php echo base_url()?>";
	</script>
	<?php $this->load->view('scriptfiles'); ?>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<style>
	.modal-backdrop{
		z-index:-1;
	}
	.error-alert{
		color:red;
	}
	.info-icon div{
		width:50px;
		height:30px;
		margin-top: -36px;margin-left: 14px;
	}
	
.header2{
	background:rgb(30, 40, 44);
	padding:2px;
}
.pageHeader2{
	text-align:center;		
	color:white;
	height:41px;
	font-size:22px;
	margin-top: 0;
    margin-bottom: 14px;
}

	.pageHeader2 h2{
		margin-bottom:-20px;
	}
	.column{
		margin-top: -20px;padding:0;
	}
	.addExcel{
		bottom: 30px;
	}
	.addPlus{
		   bottom: 30px;
	}
	.table{
		margin-top:12px;
	}
	.table.table{
		    margin-top: 5px;
	}
	 .dashboardTable thead tr th{
		text-align:center;
		font-size: 18px;
		font-weight: 600;
	}
	.dashboardTable tbody tr td{
		border-bottom: 1px solid #e9e9e9;
		border-top: none;
	} 
	.dashboardRow{
		    margin-top: -20px;
	}
	.headerWelcome{
		text-align:center;
	}
	.headerWelcome h4{
		margin-top: 1px;
	}
	table.table1 tr th{
		background:none;
		color:black!important;
	}
	table.table1 tr td{
		border-bottom: 1px solid #e9e9e9;
		border-top: none;
	}
	.dashboardTable>tbody>tr>td, .dashboardTable>tbody>tr>th, .dashboardTable>tfoot>tr>td, .dashboardTable>tfoot>tr>th, .dashboardTable>thead>tr>td, .dashboardTable>thead>tr>th{
		padding:2px;
	}
	.table1>tbody>tr>td, .table1>tbody>tr>th, .table1>tfoot>tr>td, .table1>tfoot>tr>th, .table1>thead>tr>td, .table1>thead>tr>th{
		padding:2px;
	}
	.upbtn{
		margin-top: -4px;
		margin-bottom: 2px;
		padding:3px 6px;
	}
	.downbtn{
		 margin-top: 0px;
		 padding:3px 6px;
	}
	.rowgraph{
		margin-top: -20px;
		margin-bottom: -25px;
	}
	.rowstorage{
		margin-bottom: -5px;
	}
	.dash_sect1{
		margin-top:-8px;
	}
	#test{		
		height: 160px !important; 
		background-position: center;
		background-size: contain;
		background-repeat: no-repeat;
	}
	#jqcanvas_2{
		z-index: 11 !important;
		position: absolute !important;
		left: 0px !important;
		height: 142px;
		width: 210px;
	}
	.summerytable th,
	.summerytable td{
		text-align: left !important;
	}

	th.text-center,
	td.text-center{
		text-align: center !important;
	}
    .main-sidebar, .content-wrapper.body-content{
      display:none;
    }
	.style_video span:hover{
		padding: 7px;
	}
	.notification-mask {
		z-index: 222222;
		width: 100%;
		height: 100%;
		position: fixed;
		background-color: rgba(102, 103, 103, 0.8);
	}
	.notification-popup{
		height: 200px;
		max-width: 540px;
	    font-size: 16px;
		width: 100%;
		position:absolute;
		border: 1px solid #ccc;
		/* box-shadow: 5px 5px 5px #ccc; */
		box-shadow: 5px 6px 10px #000;
		right: 16px;
		z-index: 999;
		bottom: 10px;
		background: #ddd;
		padding: 5px;
	}
.notification-mask .style_video{
	margin-left: 0px !important;
}
.notification-mask .style_video span{
	background: #B5000A;
	padding: 4px;
	border-radius: 5px;
	font-size: 12px;
}
.notification-mask .style_video span:hover{
	padding: 3px;
}
	</style>
	<script>
	
	google.charts.load('current', {'packages':['gauge']});
	function call1(a){
		google.charts.setOnLoadCallback(function() {
			drawChart(a);
		});
	}
	function drawChart(a){
		var data = google.visualization.arrayToDataTable([
			['Label', 'Value'],
			['Storage', a],
		]);
        var options = {
			width: 400, height: 180,
			redFrom: 90, redTo: 100,
			yellowFrom:75, yellowTo: 90,
			minorTicks: 5
        };
        var chart = new google.visualization.Gauge(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
	$(window).load(function () {
		$('.notification-popup').css({ 'display':'block', 'right': '-500px', 'left': '', 'transition': '3s'}).animate({
			'right' : '30px'
		});
    });
	$(document).ready(function(){ 
		/* Upgrade to highrer version button  */
		if(versiontype == "standard"){
			$('#versionCtrlBtn').text('Upgrade to premium');
			$('#standard_version').closest('.col-md-4').hide();
			$('#premium_version').attr('checked', 'checked');
		}
		if(versiontype == "standard" || versiontype == "premium"){
			$('#versionCtrlBtn').hide();
		}
        if(versiontype == "lite"){
            $('#versionCtrlBtn').text('Upgrade to Professional');
        }
		/* Upgrade to highrer version button  */
		
		if(getCookie('notify') == 'yes'){
			$('.notification-mask').remove();
		}else{
			$('.notification-mask').removeClass('none');
		}
		var a = 10;call1(30);
		/* code for sandbox */
		var url1= window.location.href;
		var fileNameIndex1 = url1.lastIndexOf("/") + 1;
		var filename1 = url1.substr(fileNameIndex1);
        sandbox(filename1);

		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_dashboardController/get_userlicense'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				loaderHide();
				
				/* ----------------------------Product Information-------------------------------- */
				$('#tableTeam').empty();
                $('#clientName').html(data[0].client_name);
				var row1 = "",end_date_rw = '';
                var modusedcnt=0;
				if(data[0].end_date != '1970-01-01'){
					end_date_rw = '<tr> <td width="50%">End Date</td> <td class="text-center">' + moment(data[0].end_date).format('ll') + '</td> </tr>';
				}else{
					end_date_rw = ''
				}
				if(data[0].versiontype == 'standard'){
					data[0].versiontype = 'Professional'
				}
				row1 += '<tr> <td width="50%">Name</td> <td class="text-center">L Connectt</td> </tr>';
				row1 += '<tr> <td width="50%">Variant</td> <td class="text-center">'+data[0].versiontype+'</td> </tr>';
				/* row1 += '<tr> <td width="50%">Version</td> <td class="text-center">1.0.1</td> </tr>'; */
				row1 += '<tr> <td width="50%">Start Date</td> <td class="text-center">' + moment(data[0].start_date).format('ll') + '</td> </tr>';
				row1 += end_date_rw;
				$('#tableTeam').append(row1);
                modusedcnt=(parseInt(data[0].cxo_module_used)+parseInt(data[0].manager_module_used)+parseInt(data[0].sales_module_used));
				/* ------------------------------------------------------------------------------- */
				/* ----------------------------License Information ---License Purchase Information-------------------------- */
				$('#licensePurchase').empty();
					licensePur = 	'<tr>'+
										'<th class="table_header text-center" colspan="2">License Purchase Information</th>'+
									'</tr>'+
									'<tr>'+
										'<td>Purchased Licenses</td>'+
										'<td class="text-center">'+data[0].module_purchased+'</td>'+
									'</tr>'+
									'<tr>'+
										'<td>Used Licenses</td>'+
										'<td class="text-center">'+modusedcnt+'</td>'+
									'</tr>';
				$('#licensePurchase').append(licensePur);
				/* ------------------------------------------------------------------------------- */
				/* ----------------------------License Information ---License Usage Information-------------------------- */
				$('#tablebody').empty();
				var row = "";
				
				row += 	'<tr>'+
							'<th class="table_header text-center" colspan="2">License Usage Information</th>'+	
						'</tr>';
				row += 	'<tr>'+
							'<td>CXO Module</td>'+
							'<td class="text-center">' + data[0].cxo_module_used + '</td>'+
							/* '<td rowspan="3">' + data[0].cxo_module_purchased + '</td>'+ */
						'</tr>';
				row +=	'<tr>'+
							'<td>Manager Module</td>'+
							'<td class="text-center">' + data[0].manager_module_used + '</td>'+
							/* '<td>' + data[0].manager_module_purchased + '</td>'+ */
						'</tr>';
				row +=	'<tr>'+
							'<td>Executive Module</td>'+
							'<td class="text-center">' + data[0].sales_module_used + '</td>'+
							/* '<td>' + data[0].sales_module_purchased + '</td>'+ */
						'</tr>';

				
				
				var count = data.length;
				$('#tablebody').append(row);
				/* ------------------------------------------------------------------------------- */
				/* ----------------------------Data Transaction Information------------------------- */
				
				
				/* ------------------------------------------------------------------------------- */				
				/* ----------------------------Plugin Purchase Information------------------------- */
				$('#tablebody3').empty();
				var row2 = "";
				row2 += '<tr>'+												   
							'<th class="table_header text-center">Plugins</th>'+
							'<th class="table_header text-center">Purchased</th>'+									   
							'<th class="table_header text-center">Used</th>'+												
						'</tr>';
                row2 += "<tr> <td>Attendance</td> <td class='text-center'>" + data[0].attendance_purchased + "</td> <td class='text-center'>"+ data[0].attendance_used + "</td> </tr>";
				row2 += "<tr> <td>Communicator</td> <td class='text-center'>" + data[0].communicator_purchased + "</td> <td class='text-center'>" + data[0].communicator_used + "</td> </tr>";
				row2 += "<tr> <td>Expenses</td> <td class='text-center'>" + data[0].expenses_purchased + "</td> <td class='text-center'>" + data[0].expenses_used + "</td> </tr>";
				row2 += "<tr> <td>Inventory</td> <td class='text-center'>" + data[0].inventory_purchased + "</td> <td class='text-center'>" + data[0].inventory_used + "</td> </tr>";
				row2 += "<tr> <td>Library</td> <td class='text-center'>" + data[0].library_purchased + "</td> <td class='text-center'>" + data[0].library_used + "</td> </tr>";
				row2 += "<tr> <td>Navigator</td> <td class='text-center'>" + data[0].navigator_purchased + "</td> <td class='text-center'>" + data[0].navigator_used + "</td> </tr>";
				$('#tablebody3').append(row2);

			},
			error : function(data){
				network_err_alert(data)
			}		
		});
		
		window.startPos = window.endPos = {};
		makeDraggable();
		$('.droppable').droppable({
			hoverClass: 'hoverClass',
			drop: function(event, ui) {
				var $from = $(ui.draggable),
				$fromParent = $from.parent(),
				$to = $(this).children(),
				$toParent = $(this);
				window.endPos = $to.offset();
				swap($from, $from.offset(), window.endPos, 200);
				swap($to, window.endPos, window.startPos, 1000, function() {
					$toParent.html($from.css({position: 'relative', left: '', top: '', 'z-index': ''}));
					$fromParent.html($to.css({position: 'relative', left: '', top: '', 'z-index': ''}));
					makeDraggable();
				});
			}
		});

		function makeDraggable() {
			$('.draggable').draggable({
			  zIndex: 99999,
			  revert: 'invalid',
			  start: function(event, ui) {
				window.startPos = $(this).offset();
			  }
			});
		}

		function swap($el, fromPos, toPos, duration, callback) {
			$el.css('position', 'absolute')
			  .css(fromPos)
			  .animate(toPos, duration, function() {
				if (callback) callback();
			});
		}
	   });
       function versionCtrlfnc(){
            $("#versionCtrlVw").modal('show');
            $("#standard_version").prop('checked', true);
			if(versiontype == "standard"){
				$('#premium_version').prop('checked', true);
			}
            $('#versionCtrlVw .error-alert').text('');
            $("#server_msg").html("");
       }
  function submitVersion(){
            var versionType = $("#versionCtrlVw input[name='versionCtrl']:checked").val();
            var userCount = $.trim($("#numberofusers").val());
            var reg = /^((?!(0))[0-9]*)$/;
            if( userCount == ""){
                    $("#numberofusers").next('.error-alert').text('Please enter Number of Users');
                    return;
            }else if( reg.test(userCount) == false){
                    $("#numberofusers").next('.error-alert').text('Invalid input.');
                    return;
            }else{
                    $("#numberofusers").next('.error-alert').text('');
            }

            var addobj = {};
            addobj.versionType = versionType;
            addobj.userCount = userCount;
            addobj.clientname =$.trim($('#clientName').text());

            //console.log(reg.test(userCount));

            loaderShow();
           $.ajax({
                  type : "post",
                  url : "<?php echo site_url('admin_dashboardController/upgrade_mail'); ?>",
                  data : JSON.stringify(addobj),
                  dataType : "json",
                  success : function(data){
                        if(error_handler(data)){
                            return;
                        }
                        loaderHide();
                        if(data==1){
                            $("#server_msg").html('<div class="alert alert-success"><center><strong>Your request has been successfully submitted to the L-Connectt team. </strong></center> </div>');
                        }else{
                            $("#server_msg").html('<div class="alert alert-warning"><center><strong>Request sending failed. Please try again. </strong></center> </div>');
                        }
                        cancel();
                  },
                  error : function(data){
                        network_err_alert(data)
                  }
           });
  }
	function cancel(){
		$("#versionCtrlVw").modal('hide');
		$("#numberofusers").val("");
	}
    function hideNotification(){
		
		if($('#doNotDisturb').prop('checked') == true){
			setCookie('notify', 'yes', 999);
		}else{
			setCookie('notify', 'no', 999);
		}
		$('.notification-mask').remove();
	}    
	</script>

	</head>
	<body class="hold-transition skin-blue sidebar-mini"> 
	<div class="notification-mask none">
		<div class="wall notification-popup none">
			<div class="row">
				<p>					
					<center><b>Welcome to L Connectt!</b></br>
					
					Please fill out each page in a sequential manner to successfully set up the Admin Console. 
					<br>
					<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="20" height="20"  data-toggle="tooltip" data-placement="right" title="" style="margin-bottom: 2px;border-radius: 5px;"/>  and <span class="style_video"><span class="glyphicon glyphicon-facetime-video " data-toggle="tooltip" data-placement="right" title="" style="margin-top: 4px;"></span></span> on each page will guide you through the setup.
					</br>
					Click the link below to be redirected to the first page to setup.
					</br><b><a href="<?php echo site_url('admin_roleController'); ?>" >Department & Roles</a></b></center>
				</p>
			</div>
			<div class="row" style="position: absolute;bottom: 5px;width: 100%;">
				<div class="col-md-10 col-xs-10">
					<label>
						<input type="checkbox" id="doNotDisturb"/> Do not remind me again
					</label>
				</div>
				<div class="col-md-2 col-xs-2">
					<center><button type="button" class="btn btn-default no-margin" onclick="hideNotification()">Ok</button></center>
				</div>
			</div>
		</div>
	</div>
	<div class="loader">
		<center><h1 id="loader_txt"></h1></center>
	</div>
		<!---------------------------------------------------------
		Header bar
		------------------------------------------------------------>
		<?php $this->load->view('demo');  ?>
		<!---------------------------------------------------------
		side nav
		------------------------------------------------------------>		
		
		<?php $this->load->view('admin_sidenav'); ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
				<div class="row header2">					
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 " style="margin-top: 22px;">						
						<span class="style_video" style="margin-left: 16px!important;">
							<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Dashboard" style="margin-bottom: 5px;border-radius: 5px;"/>
						</span>
						<span class="style_video" style="margin-left: 16px!important;">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Admin_Dashboard', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video" style="margin-top: 4px;"></span>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader2 ">
							<h2>Dashboard</h2>		
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  ">						 
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="row">
					<span class="headerWelcome"><h4><b>Welcome <span id="clientName"></span></b></h4></span>
					<div class="col-md-6 dash_sect1" >
						<div class="row">
							<div class="row">
							<table class="table dashboardTable" >
								<thead>  
									<tr>			
										<th class="table_header" >Product Information</th>											   
									</tr>
								</thead>
							</table>
						</div>
						</div>
						<div class="row dashboardRow" >
							<div class="col-md-12 no-padding">
								<table class="table dashboardTable summerytable table-bordered" >							 
									<tbody id="tableTeam">
									</tbody>    
								</table>
							</div>
						</div>	
						<!--<div>
							<center>
								<button disabled type="button" class="btn btn-default upbtn">Check for Update</button>
							</center>
						</div>-->
					</div>
					<div class="col-md-6 dash_sect1">
						<div class="row">
							<table class="table dashboardTable" >
								<thead>  
									<tr>			
										<th class="table_header" >License Information</th>											   
									</tr>
								</thead>
							</table>
						</div>
						<div class="row dashboardRow">
							<div class="col-md-12 no-padding">
								<table style="margin:auto; width: 100%;">
									<tr>
										<td valign="top">
											<table class="table dashboardTable summerytable table-bordered" >									
												<tbody id="licensePurchase">
													
												</tbody>    
											</table>
										</td>
										<!--<td width="30px">
										
										</td>
										<td valign="top">
											<table class="table dashboardTable summerytable table-bordered" >
												<tbody id="tablebody">
												</tbody>    
											</table>
										</td>-->
									</tr>
								</table>
							</div>
						</div>						
					</div>
				</div>
				<div class="row">
						<div class="col-md-6">
							<table class="table dashboardTable">
								<thead>  
									<tr>			
										<th class="table_header">Data Transaction Information</th>			   
									</tr>
								</thead>
							</table>
							<div class="row rowgraph" align="center">
								<div class="col-md-6 no-padding">
									<table class="table dashboardTable summerytable table-bordered" >									
										<tbody id="tablebody1">
											<tr>
												<th class="table_header text-center" colspan="2">Storage Details</th>
											</tr>
											<tr>
												<td width="50%">Total Storage</td>
												<td class="text-center">200GB</td>
											</tr>
											<tr>
												<td width="50%">Used Storage</td>
												<td class="text-center">80GB</td>
											</tr>
										</tbody>    
									</table>
									<table class="table dashboardTable summerytable table-bordered" >									
										<tbody>
											<tr>
												<th class="table_header text-center" colspan="2">Monthly Data Transfer Details</th>
											</tr>
											<tr>
												<td width="50%">Incoming</td>
												<td class="text-center">1GB</td>
											</tr>
											<tr>
												<td width="50%">Outgoing</td>
												<td class="text-center">1GB</td>
											</tr>
											<!--<tr>
												<td class="table_header text-center" colspan="2" >
													<button disabled type="button" class="btn btn-default downbtn">View Details</button>
												</td>
											</tr>-->
										</tbody>    
									</table>								
								</div>
								<div class="col-md-6 no-padding">
									<div id="chart_div"  style="padding: 10px;"></div>
								</div>
								<!--<input type="hidden" id="tdt" class="speedometer" ></input>-->
								 
							</div>
							<div class="row rowstorage">
								<div class="col-md-12">
														
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<table class="table dashboardTable" >
									<thead>  
										<tr>			
											<th class="table_header" >Plugin Purchase Information</th>											   
										</tr>
									</thead>
								</table>
							</div>
							<div class="row dashboardRow">
								<div class="col-md-12 no-padding">
									<table class="table dashboardTable summerytable table-bordered">
										<tbody id="tablebody3">
										</tbody>    
									</table>
								</div>
							</div>						
						</div>
				</div>	
				<!--<div class="row" align="center" >
					<div class="col-md-4">
						<button disabled  type="button" class="btn btn-default downbtn">Manage Storage</button>
					</div>
					<div class="col-md-4">
						<button disabled type="button" class="btn btn-default downbtn">Purchase History</button>
					</div>
					<div class="col-md-4">
						<button disabled type="button" class="btn btn-default downbtn">Manage License</button>
						--<a class="disabled" href="<?php echo site_url('admin_mangelicenseController'); ?>">--
							
						--</a>
					</div>
				</div>-->
				<br/>
				<div class="row" align="center" >
					<div class="col-md-12">
						<button type="button" onclick="versionCtrlfnc()" id="versionCtrlBtn" class="btn btn-default">Upgrade to Paid Version</button>
					</div>
				</div>
                <p></p>
                <div id="server_msg" style="max-width:600px; margin:auto"></div>
         </div>
	</div>
	<div id="versionCtrlVw" class="modal fade" data-backdrop='static'>
		<div class="modal-dialog" >
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel()">x</span>
					<h4 class="modal-title"><b>Upgrade Setting</b></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4">
						</div>
						<div class="col-md-4">
							<label>
							<input type="radio" name="versionCtrl" id="standard_version" value="standard" />
							Pro version
							</label>
						</div>
						<div class="col-md-4">
							<label>
							<input type="radio" name="versionCtrl" id="premium_version" value="premium" />
							Premium version
							</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<label>Number of Users *</label>
						</div>
						<div class="col-md-8">
							<input type="number" class="form-control" name="versionCtrl" id="numberofusers" />
							<span class="error-alert"></span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn" value="Submit" onclick="submitVersion()"/>
					<input type="button" class="btn" value="Cancel" onclick="cancel()"/>
				</div>
			</div>
		</div>
	</div>
		<?php $this->load->view('footer'); ?>

	</body>
</html>
