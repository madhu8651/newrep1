<html lang="en">
	<head>
	<script>
		var baseurl="<?php echo base_url()?>";
	</script>
	<?php $this->load->view('scriptfiles'); ?>
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
		 margin-top: -10px;
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
	.table{
		box-shadow: 0px 2px 2px 0px #403a3a;
	}
	.table tbody tr td{
		background:white;
	}
	.title_num{
		float: left;
		border-radius: 30px;
		background: white;
		color: black;width: 23px;
	}
	</style>
	 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	 <script type="text/javascript">
    
    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart','table']});
      var data;
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);
    /* function clear_chart(){
		 data = new google.visualization.DataTable();
                    data.addColumn('string', '');
                    data.addColumn('number', '');
                    data.addColumn({type: 'string', role: ''});
	} */
    function drawChart () {

            $.ajax({
                url: "<?php echo site_url('manager_dashboardController/get_dashboard_reports'); ?>",
                dataType: "json",
                success: function (jsonData) {
					var color;
					console.log(jsonData);
						var row1="";
					$("#charts").empty();
					for(h=0;h<jsonData.length;h++){
						//if(h>0){
							var i= h+1;
							row1 +='<div class="col-md-6 " style="margin-top: 23px;"><table class="table"><tbody><tr><th><span class="title_num">'+i+'</span><span>'+ jsonData[h].title +'</span></th></tr><tr><td id="'+jsonData[h].id+'"></td></tr></tbody></table></div>';
							
						//}
						
					}
					$("#charts").append(row1);
                    for (var i = 0; i < jsonData.length; i++) {						
						data = new google.visualization.DataTable();
                   		data.addColumn('string', 'User');
                    	data.addColumn('number', '');
                    	data.addColumn({type: 'string', role: 'ID'});
							
						var type1 = jsonData[i].chart_type;
						for(j=0;j<jsonData[i].call.length;j++){
							 data.addRow([jsonData[i].call[j].Callers_Name, parseInt(jsonData[i].call[j].Total_Calls), jsonData[i].call[j].User_ID]);
						}
						var options = {
							title: jsonData[i].title,
							is3D: true,
							width: 500,
							height: 250,
							padding:0,
							colors: ['#b5000a','yellow', 'blue','#e0440e']
						};
						if(type1=='PieChart'){
							var data1 = data;
							var chart1 = new google.visualization.PieChart(document.getElementById(jsonData[i].id));
							chart1.draw(data, options);
							var selectHandler = function(e) {
								var selectedItem = chart1.getSelection()[0];
								if (selectedItem) {
								var topping = data1.getValue(selectedItem.row, 2);
								alert('The user selected ' + topping);
								}
							}
							google.visualization.events.addListener(chart1, 'select', selectHandler);
						}if(type1=='LineChart'){
							var data2 = data;
							var chart2 = new google.visualization.LineChart(document.getElementById(jsonData[i].id));
							chart2.draw(data, options);
							var selectHandler = function(e) {
								var selectedItem = chart2.getSelection()[0];
								if (selectedItem) {
								var topping = data2.getValue(selectedItem.row, 2);
								alert('The user selected ' + topping);
								}
							}
							google.visualization.events.addListener(chart2, 'select', selectHandler);
						}if(type1=='BarChart'){
							var data3 = data;
							var chart3 = new google.visualization.BarChart(document.getElementById(jsonData[i].id));
							chart3.draw(data, options);
							var selectHandler = function(e) {
								var selectedItem = chart3.getSelection()[0];
								if (selectedItem) {
								var topping = data3.getValue(selectedItem.row, 2);
								var top_id = data3.getValue(selectedItem.row, 1);
								alert('The user selected ' + topping);
									// var site_url = "<?php echo site_url('callsCompletedController/new_page');?>";
									// window.location.href = site_url+"/"+top_id;
								}
							}
							google.visualization.events.addListener(chart3, 'select', selectHandler);
						}if(type1=='ColumnChart'){
							var data4 = data;
							var chart4 = new google.visualization.ColumnChart(document.getElementById(jsonData[i].id));
							chart4.draw(data, options);
							var selectHandler = function(e) {
								var selectedItem = chart4.getSelection()[0];
								if (selectedItem) {
								var topping = data4.getValue(selectedItem.row, 2);
								alert('The user selected ' + topping);
								}
							}
							google.visualization.events.addListener(chart4, 'select', selectHandler);
						}
                    }
					
                }
            });
    }
    </script>

	</head>
	<body class="hold-transition skin-blue sidebar-mini"> 	
	
		<!---------------------------------------------------------
		Header bar
		------------------------------------------------------------>
		<?php $this->load->view('demo');  ?>
		<!---------------------------------------------------------
		side nav
		------------------------------------------------------------>		
		
		<?php $this->load->view('manager_sidenav'); ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
				<div class="row header2">					
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 ">
						
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader2 ">
							<h2>Dashboard</h2>		
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  ">						 
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<div id="charts">
					<div class="col-md-6 " style="margin-top: 23px;">
						<table class="table"><tbody><tr><th><span class="title_num">1</span><span>Choose Report</span></th></tr><tr><td id="1"><a href=""><img src="<?php echo base_url(); ?>images/Sample_Report.png" width="200px" height="200px" /></a></td></tr></tbody></table>
					</div>
					
									
				</div>
				<div id="chart1">
				
				</div>
         </div>	
	</div>
		<?php $this->load->view('footer'); ?>

	</body>
</html>
