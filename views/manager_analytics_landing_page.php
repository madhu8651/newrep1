<!DOCTYPE html>
<html lang="en">
    <head>
		<?php require 'scriptfiles.php' ?>
		<style>
		#savedRep .active_link{
			    border-bottom: 2px solid brown;
		}
			.page-container ol li a{
				margin-bottom: 4px;
				padding: 0;
				padding-bottom: 5px;
			}
			.page-container ol li h4{
				font-weight: 600;
				color:#565656;
			}
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
				margin:auto;
				clear:both;
			}
			.text_area{
				overflow: hidden;
			}
			.report_label_style{
				font-size: 14px;
				font-weight: bold!important;
				margin-top: 7px;
			}
			.schedule_box .row{
				margin-bottom: 5px;
			}
			#sched_margin{
				margin-top: 12px;
			}
			
			/* ------------------------------------- */
			/* Style the tab */
	div.tab {
		float: left;
		border: 1px solid #ccc;
		background-color: #f1f1f1;
		width: 30%;
		height: 300px;
	}

	/* Style the buttons inside the tab */
	div.tab button {
		display: block;
		background-color: inherit;
		color: black;
		padding: 22px 16px;
		width: 100%;
		border: none;
		outline: none;
		text-align: left;
		cursor: pointer;
		transition: 0.3s;
		font-size: 17px;
	}

	/* Change background color of buttons on hover */
	div.tab button:hover {
		background-color: #ddd;
	}

	/* Create an active/current "tab button" class */
	div.tab button.active {
		background-color: #ccc;
	}

	/* Style the tab content */
	.tabcontent {
		float: left;
		padding: 0px 12px;
		border: 1px solid #ccc;
		width: 70%;
		border-left: none;
		height: 300px;
	}
	.savedReportList #savedRep .col-md-3.col-lg-3{
		width:100%;
	}
		</style>
		<script>
			$(document).ready(function(){
				$("#accordion").accordion();
				$("#tabs").tabs();
				
				$(".start_date").datetimepicker({
					ignoreReadonly:true,
					allowInputToggle:true,
					format:'lll',
				});
				$(".start_time").datetimepicker({
					ignoreReadonly:true,
					allowInputToggle:true,
					format:'HH:mm:ss',
				});
				
				
				
				if(window.location.href.indexOf("#savedReport") >= 0){
					$("#typeList li").removeClass("active");
					$("#typeList li").each(function(){
						if($(this).text() == "Saved Report"){
							$(this).addClass("active");
						}
					})
					$("#standard, #custom, #scheduled").removeClass("in").removeClass("active")
					$("#standard, #custom, #scheduled").each(function(){
						if($(this).attr("id") == "custom"){
							$(this).addClass("in").addClass("active");
						}
					})

					list_saved_report('load')
				}
			});
				
			/* ----------------------list Saved reports--------------------------- */
			function list_saved_report(state){				
				if(versiontype=='lite'){
					alert('This feature is not available for this version.');
					return;
				}
				/* window.location = "<?php echo site_url('manager_standard_analytics/work_pattern_analysis'); ?>" */
				$.ajax({
					type: "POST",
					url:"<?php echo site_url('manager_standard_analytics/getsavedreport/list')?>",
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
						edit_tree(data, "savedRep", 'null')
						if(data.length > 0){
							var list= "";
							for(i=0; i<data.length; i++){
								var jsondata=JSON.stringify(data[i].details);
								jsondata = window.btoa(jsondata);
								if(state == "click"){
									/* if(i==0){
										list += "<li onclick='renderSingleTable1(\""+data[i].typeID+"\")' class='active'><a href='#"+data[i].typeID+"' data-toggle='tab'>" + data[i].typeName+"</li>";
										renderSingleTable1(data[i].typeID)
									}else{
										list += "<li><a onclick='renderSingleTable1(\""+data[i].typeID+"\")' id='id"+data[i].typeID+"'  href='#"+data[i].typeID+"' data-toggle='tab'>" + data[i].typeName+"</li>";
									} */
								}
								if(state == "load"){
									arr = window.location.href.split("=");
									report = arr[arr.length-1];
									reportArray = report.split("_");
									
									renderSingleTable1(reportArray[0])
									if(reportArray[0] == data[i].typeID){
										/*list += "<li onclick='renderSingleTable1(\""+data[i].typeID+"\")' class='active'><a href='#"+data[i].typeID+"' data-toggle='tab'>" + data[i].typeName+"</li>";
										*/
										
									}else{
										/* list += "<li><a onclick='renderSingleTable1(\""+data[i].typeID+"\")' id='id"+data[i].typeID+"'  href='#"+data[i].typeID+"' data-toggle='tab'>" + data[i].typeName+"</li>"; */
									} 
									
								}
							}
						}
						$("#savedReportList").html(list)
					},
					error:function(data){
						network_err_alert(data);
					}
				});
			}

			/* ---------------------------------------------------------------------------- */
			/*----------------------get Save List function ------------------------------*/
	
	function getSaveList(item, $list, parentID) {
			if($.isArray(item)){
				$.each(item, function (key, value) {
					getSaveList(value, $list, parentID);
				});
			}
			arr = window.location.href.split("=");
			report = arr[arr.length-1];
			reportArray = report.split("_");
			if (item) {
				if (item.name ) {
					if(reportArray[0] == item.id){
						var $li = $("<li class='active_link'></li>");
					}else{
						var $li = $("<li ></li>");
					}
					if(item.remarks !=""){
						$li.append($("<a href ='#"+item.id+"' onclick='renderSingleTable1(\""+item.id+"\")' >" + item.name + "</a>"));
					} else{
						$li.append($("<a href ='#' >" + item.name + "</a>"));
					} 
					
				}
				if (item.children && item.children.length) {
					var $sublist = $("<ol class= 'inner_li child-count-"+item.children.length+"'></ol>");
					getSaveList(item.children, $sublist, parentID)
					$li.append($sublist);
				}
				$list.append($li)
			}
		}
		/* ---------------------------------------------------------------------------- */
		/* ---------------------------------------------------------------------------- */
			/* function formatPath(array){
				var formatPath="";
				for(i=0; i<array.length; i++){
					
					if(i+1 != array.length ){
						formatPath +='<span> '+array[i]+' </span><i class="fa fa-hand-o-right" aria-hidden="true" style="margin: 0 10px;"></i>';
					}else{
						formatPath +='<span> '+array[i]+' </span>';
					}
				}
				return formatPath
			} */
			function renderSingleTable1(id){
				arr = window.location.href.split("=");
				report = arr[arr.length-1];
				reportArray = report.split("_");

				var obj={};
				obj.id = id;
                $.ajax({
					type: "POST",
					url:"<?php echo site_url('manager_standard_analytics/getsavedreport/detail')?>",
                    data : JSON.stringify(obj),
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
                        detail=data;
						/* ----------------------------------------- */
						var row1="";
						row1 += '<div class="tab-pane active" id="'+id+'">';
						row1 += '<table class="table"><thead>';
						row1 += "<tr><th width='5%'>SL No</th><th width='30%'>Report Name</th><th width='5%'> View</th></tr></thead><tbody class='ui-sortable'>";
						var duplicateBtn = 0;
						for(var k=0; k < detail.length; k++ ){
							cntrl_func = detail[k].hvalue2.replace(/[^A-Z0-9]/ig, "_") +"/"+session_userId.trim()+"/"+ detail[k].reportPageId+"_"+detail[k].id+"_"+detail[k].reportid;
							if(reportArray[2] == detail[k].reportid){
								row1 += "<tr id='" + detail[k].reportid + "'>"+
									"<td><i class='fa fa-hand-o-right' aria-hidden='true' style='margin: 0 10px;position: absolute;left: 0;font-size: 20px;'></i>" + (k+1) + "</td>"+
									"<td>" + detail[k].reportname + "</td>"+
									'<td>'+
										'<a href="<?php echo site_url('manager_standard_analytics/'); ?>'+cntrl_func+'">'+

											'<span class="glyphicon glyphicon-eye-open"></span>'+
										'</a>'+
									'</td>'+
									"</tr>";
							}else{
								row1 += "<tr id='" + detail[k].reportid + "'>"+
									"<td>" + (k+1) + "</td>"+
									"<td>" + detail[k].reportname + "</td>"+
									'<td>'+
										'<a href="<?php echo site_url('manager_standard_analytics/'); ?>'+cntrl_func+'">'+
											
											'<span class="glyphicon glyphicon-eye-open"></span>'+
										'</a>'+
									'</td>'+
									"</tr>";
							}
							
						}
						row1 += '</tbody></table></div>';
						$('#ReportListDetail').html("").append(row1);
						
						/* ----------------------------------------- */
					},
					error:function(data){
						network_err_alert(data);
					}
				});
				
				
			}
			
			function redirect(id){
                var obj={};
				obj.user = $("#user_name").val();
				obj.selectedDate = moment($("#selected_date input").val(),'ll').format('YYYY-MM-DD');
                	$.ajax({
					type: "POST",
					url:"<?php echo site_url('reports/standard_RepAnalysisController/get_time')?>",
                    data : JSON.stringify(obj),
					dataType:'json',
					success: function(data){
						if(error_handler(data)){
							return;
						}
                        for(var i=0;i<data.length; i++){
							$('#selected_start_time input').val(moment(data[i].start_time,'hh:mm:ss').format('LT'));
							$('#selected_end_time input').val(moment(data[i].end_time,'hh:mm:ss').format('LT'));
						}
                        console.log(data);
						loaderHide();
					},
					error:function(data){
						network_err_alert(data);
					}
				});
            }
			
			/* ----------------------list scheduled reports--------------------------- */
			function list_scheduled_report(state){
				if(versiontype=='lite'){
					alert('This feature is not available for this version.');
					return;
				}else{
					alert('Page under Construction');
				}
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
								<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-toggle="tooltip" data-placement="right" title="Standard Analytics"/>

						</div>
					</span>
				</div>
				<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
					<h2>Analytics</h2>
				</div>
				<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
					<div class="addBtns" >
						<!--<a href="#" class="addPlus"  onclick="reportListDisplay()">
							<i  style="font-size: 35px;" class="fa fa-list fa-2" aria-hidden="true"></i>
						</a>-->
						<!--<a  class="addExcel" onclick="addExl()" >
							<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
						</a>-->
					</div>
					<div style="clear:both"></div>
				</div>
				<div style="clear:both"></div>
			</div>
			<div class="report-list-container none">
			
			</div>
			
			<div class="page-container row">
				<ul class="nav nav-tabs nav-justified" id="typeList">
					<li class="active"><a data-toggle="tab" href="#standard"><h4>Standard</h4></a></li>
					<li><a onclick="list_saved_report('click')" data-toggle="tab" href="#custom"><h4>Saved</h4></a></li>
					<li><a onclick="list_scheduled_report('click')" data-toggle="tab" href="#scheduled"><h4>Scheduled</h4></a></li>
				</ul>
				<div class="tab-content">
					<div id="standard" class="tab-pane fade in active">

						<?php require 'manager_standard_report_list.php' ?>

					</div>
					<div id="custom" class="tab-pane fade">
						<div class="row">
							<div class="savedReportList">
								<div class="col-xs-3 verticle-tab">
									<!--<ul class="nav nav-tabs tabs-left" id="savedReportList">
									</ul>-->
									<div class="row report-list" id="savedRep"></div>
								</div>
								<div class="col-xs-9 tab-col" >
									<div class="tab-content" id="ReportListDetail">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="scheduled" class="tab-pane fade">
						
					</div>
				</div>
			</div>
			<div style="clear:both"></div>
			
			
        </div>
		</div>
        <?php require 'footer.php' ?>
    </body>
</html>
