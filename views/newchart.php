<html>
  <head><script>
		var baseurl="<?php echo base_url()?>";
	</script>
	<?php $this->load->view('scriptfiles'); ?>
	
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="text/javascript" src="<?php echo base_url();?>js/loader1.js"></script>
    <!--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->
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
	.chart_style{
		//box-shadow: 0 0 4px rgba(101, 90, 90, 0.53);
		box-shadow: 4px 6px 8px rgba(195, 179, 179, 0.53);;
		margin-right: -5px!important;
		margin-left: -2px!important;
		border-radius:5px;
		border-top: 3px solid #B5000A;
		background:white;
	}
	#charts{
		padding-bottom:14px;
		background:rgb(241, 240, 240);
	}
	#num_tiles{
		height: 30px;
		margin-top: 24px;
		margin-bottom: -1px;
	}
	.tiles_style{
		color: white;
		margin-top: 28px;
		margin-left: 58px;
		font-weight: 700!important;
	}
	.target{
		text-align: center;
		font-size: 18px;
		font-weight: bold;
	}
	.hr_style{
		margin-top:5px!important;
	}
	.graph_space{
		    margin-top: -20px;
	}
	.graph_space svg{
		width:100%!important;
	}
	</style>
    <script type="text/javascript">
	
	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}
	/* Setting  tile index to cookie */
	function setTileIndex(index){		
		document.cookie = "setTileIndex =" + index + ";" + (1*24*60*60*1000) + ";path=/";
	}
	
	/* ----------------------- */
	function create_box(arr,arr1){
		console.log(arr)
		var num=0;
		var num_Arr=[],data_Arr=[],url = '';
		var rowdata="";
					$("#charts").empty();						
					var value=arr.length;
					for(h=0;h<arr1;h++){
						num= num+1;
						url = "<?php echo site_url('manager_dashboardsettingController/index1')?>";
						//url = "<?php echo site_url('manager_dashboardsettingController/index1')?>/"+num;
						if(h==0 || h==3 || h==6 || h==9 || h==12 || h==15){
							rowdata +='<div class="row">';
						}
						rowdata +='<div class="col-md-4" style="margin-top: 23px;text-align:center;"><div class="row chart_style"><div style="text-align: center;font-size: 14px;"><span class="chart_title"><b>Choose Chart</b></span><div class="chart_sub"><b>To Display report</b></div></div><div  class="row target"></div><hr class="hr_style"/><div class="graph_space" id="'+num+'"><a onclick="setTileIndex(\''+num+'\')" href="'+url+'"><img src="<?php echo site_url()?>/images/Sample_Report.png" height="200px" /></a></div></div></div>';
						if(h==2 || h==5 || h==8 || h==11 || h==14 || h==17){
							rowdata +='</div>';
						}
						num_Arr.push(num);
					}
					$("#charts").append(rowdata);
					for(h=0;h<arr.length;h++){
						data_Arr.push(arr[h].id);
					}
					for(g=0;g<data_Arr.length;g++){
					for(i=0;i<num_Arr.length;i++){
						if(num_Arr[i]==data_Arr[g]){
							if(arr[g].chart_type=="text"){
								$("#"+num_Arr[i]).empty();
								$("#"+num_Arr[i]).closest(".row").find(".target").empty();
								$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_sub").empty();
								//$("#"+num_Arr[i]).closest(".row").find(".target").append("<b>Target</b>: "+arr[g].target);		
							}
							for(j=0;j<arr[g].call.length;j++){
								$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_title").empty();
								if(arr[g].frequecy=="Custom"){
									var date = moment(arr[g].start_date).format('DD/MM/YYYY');
									var date1 = moment(arr[g].end_date).format('DD/MM/YYYY');
									$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_title").append("<b>"+ arr[g].call[j].callersname +": " + arr[g].title +" ("+ arr[g].frequecy +") from "+ date + " to " + date1 + "</b>");
									//$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_title").append("<b>"+ arr[g].call[j].callersname +": " + arr[g].title+" "+ arr[g].frequecy +"</b>");			
									$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_sub").empty();
									//$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_sub").append("<b>As on: "+moment().format('DD/MM/YYYY')+"</b>");
								}									
								else if(arr[g].frequecy == "Monthly" || arr[g].frequecy == "Quaterly"){
									$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_title").append("<b>"+ arr[g].call[j].callersname +": " + arr[g].title+" of "+ arr[g].displaydata +"</b>");		
									$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_sub").empty();
									$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_sub").append("<b>As on: "+moment().format('DD/MM/YYYY')+"</b>");
								}else if(arr[g].frequecy == "Weekly"){
									var date = moment().startOf('week').format('DD/MM/YYYY');
									var date1 = moment().endOf('week').format('DD/MM/YYYY');
									$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_title").append("<b>"+ arr[g].call[j].callersname +": " + arr[g].title +" from "+ date + " to " + date1 + "</b>");		
									$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_sub").empty();
									//$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_sub").append("<b>As on: "+moment().format('DD/MM/YYYY')+"</b>");
									//$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_sub").append("<b>from " + date + " to " + date1 +" As on: "+moment().format('DD/MM/YYYY')+"</b>");
								}else{
									$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_title").append("<b>"+ arr[g].call[j].callersname +": " + arr[g].title +"</b>");		
									$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_sub").empty();
									$("#"+num_Arr[i]).closest(".row").find("div").children(".chart_sub").append("<b> on: "+moment().format('DD/MM/YYYY')+"</b>");
								}
									
							}
						}
						
					}
	}
	}
      // Load Charts and the corechart package.
      google.charts.load('current', {'packages':['corechart']});
	  google.charts.load('current', {'packages':['table']});
		function myFunction(arr,arr1) {
			create_box(arr,arr1);
				var row,user_id,title,type,target,flag,row1,user_id1,title1,type1,target1,flag1,row2,user_id2,title2,type2,target2,flag2,row3,user_id3,title3,type3,target3,flag3,row4,user_id4,title4,type4,target4,flag4;
			for(i=0;i<arr.length;i++){
				if(arr[i].chart_type=="pie"){
					row=arr[i].call;
					user_id=arr[i].id;
					type=arr[i].chart_type;
					target=arr[i].target;
					flag=arr[i].flag_value;
					title=arr[i].title;
					call(row,user_id,type,target,flag,title);
				}if(arr[i].chart_type=="line"){
					row1=arr[i].call;
					user_id1=arr[i].id;
					type1=arr[i].chart_type;
					target1=arr[i].target;
					flag1=arr[i].flag_value;
					title1=arr[i].title;
					call(row1,user_id1,type1,target1,flag1,title1);
				}if(arr[i].chart_type=="column"){
					row2=arr[i].call;
					user_id2=arr[i].id;
					type2=arr[i].chart_type;
					target2=arr[i].target;
					flag2=arr[i].flag_value;
					title2=arr[i].title;
					call(row2,user_id2,type2,target2,flag2,title2);
				}if(arr[i].chart_type=="bar"){
					row3=arr[i].call;
					user_id3=arr[i].id;
					type3=arr[i].chart_type;
					target3=arr[i].target;
					flag3=arr[i].flag_value;
					title3=arr[i].title;
					call(row3,user_id3,type3,target3,flag3,title3);
				}if(arr[i].chart_type=="text"){
					row4=arr[i].call;
					user_id4=arr[i].id;
					type4=arr[i].chart_type;
					target4=arr[i].target;
					flag4=arr[i].flag_value;
					title4=arr[i].title;
					call1(row4,user_id4,type4,target4,flag4,title4);
				}
			}
		}
		function call(row1,title1,type1,target1,flag1,title){
			google.charts.setOnLoadCallback(function() {
						drawSarahChart(row1,title1,type1,target1,flag1,title);
					});
		}function call1(row1,title1,type1,target1,flag1,title){
			google.charts.setOnLoadCallback(function() {
						drawSarahChart1(row1,title1,type1,target1,flag1,title);
					});
		}
		$(document).ready(function(){
				var url1= window.location.href;
				var fileNameIndex1 = url1.lastIndexOf("/") + 1;
				var filename1 = url1.substr(fileNameIndex1);

				 $('.tooltip_style').tooltip({title: "Settings", placement: "bottom"}); 
             	$.ajax({
					type : "POST",
					url : "<?php echo site_url('manager_dashboardsettingController/loadgraphs'); ?>",
					dataType : 'json',
					cache : false,
					success : function(data)
                    {
						loaderHide();
                           //max_area
                            myFunction(data.data_graph,data.minarea);

					}
				});
		});
		function tiles_Count(){
			var val = $("#num_tiles").val();
		}

    </script><?php require 'function.php' ?>
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
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
		
		<?php $this->load->view('manager_sidenav'); ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
				<div class="row header2">					
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 ">
						<span>
	                        <div class="info_icon">	
								<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-toggle="tooltip" data-placement="bottom" title="View your dashboard reports on this page. Click on the settings toggle on top right to customize the reports on this page."/>

	                        </div>
						</span>
							<div class="style_video">
								<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Manager_Dashboard', 'video_body', 'Manager')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
							</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader2 ">
							<h2>Dashboard</h2>
					</div>
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 ">
						
					</div>
					<div  class="col-xs-1 col-sm-1 col-md-1 col-lg-1  ">
						<a href="<?php echo site_url('manager_dashboardsettingController/index1');  ?>"><span class="glyphicon glyphicon-cog tooltip_style" style="color:white!important;position:absolute;margin-top:31px;" ></span></a>
					</div>
					<div style="clear:both"></div>
				</div>
				<div  id="charts">
					
									
				</div>
				<div id="chart1">

				</div>
         </div>
	</div>
		<?php $this->load->view('footer'); ?>
  </body>
</html>