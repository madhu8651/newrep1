<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'scriptfiles.php' ?>
<style>
	.tooleye{
		font-size: small;
		border: 2px solid;
		padding: 5px;
		border-radius: 4px;
		color: white;
		margin: 2px;
	}
	.rightdropdown	{
		right: 0;
		left: auto;
		text-align: right;
	}
	.multiselect{
		height: 150px;
		overflow: auto;
		border: 1px solid #ccc;
		border-radius: 5px;
	}
	.multiselect ul{
			padding: 0px;
	}
	.multiselect ul li.sel{
			background: #ccc;
	}
	.multiselect ul li{
			padding: 0 10px;
	}
	.rejected_lead,
	.legend{
		background-color: rgba(180, 0, 10, 0.20) !important;
	}

	.legend{
		width: 30px;
		height: 30px;
		margin: -5px 10px 0px 0px;
	}
	.legend-wrapper{
		width: 200px;
		margin:auto;
		float: none;
		margin-left:25px;
	}

</style>
<script>
	var opportunities_data = {};
	$(document).ready(function(){
		pageload();
		var leads = "<?php echo $leads;?>";
		var customers = "<?php echo $customers;?>";
		var sell_types = JSON.parse('<?php echo(json_encode($sell_types));?>');
		var li_html = "";
		$("#create_oppo").empty();
		if (leads == "1") {
			li_html += "<li><a onclick='show_opp_create(\"Lead\");'><h4>Lead</h4></a></li>";
		}
		if (customers == "1") {
			li_html += "<li><a onclick='show_opp_create(\"Customer\");'><h4>Customer</h4></a></li>";
		}
		$("#create_oppo").append(li_html);
	});
    function a(){
        pageload();
    }
    function b(){
      pageload1();
    }
	function pageload(){
		loaderShow();

		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_opportunitiesController/get_assigned_opportunities/myopp'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				if (error_handler(data)) {
					return ;
				}
				loaderHide();
				console.log(data);
				$('#tablebody').empty();
				opportunities_data = data;
				var row = '';
				for(i=0; i < data.length; i++ ){
					var rowdata = JSON.stringify(data[i]);
					red = ''
					if ((data[i].opp_repstatus==3 && data[i].stage_repstatus==3) || (data[i].opp_manstatus==3 && data[i].stage_manstatus==3))  {
						red += "class='danger'";
					}
				   	link = "<?php echo site_url('manager_opportunitiesController/stage_view/'); ?>"+data[i].opportunity_id+"/myopp";
					row +=   "<tr "+red+" >"+
						"<td>" + (i+1)+"</td>"+
						"<td>" + data[i].opportunity_name +"</td>"+
						"<td>" + data[i].lead_cust_name +"</td>"+
						"<td><a href="+link+">" + data[i].product_name+" Product(s)</a></td>"+
						"<td>" + data[i].industry_name + "</td>"+
						"<td>" + data[i].location_name + "</td>"+
						"<td>"+data[i].stage_name+"</td>";
						
						if(data[i].manager_owner_id == null || data[i].oppmanstatus=='1') {
							row +="<td><span><font color='#FF8C00'><b>Pending<b></font></span></td>";
						}else{
						   row +="<td>"+data[i].opp_man+"</td>";
						}
						if(data[i].owner_id == null || data[i].owner_status=='1') {
							row +="<td><span><font color='#FF8C00'><b>Pending<b></font></span></td>";
						}else{
						   row +="<td>"+data[i].opp_rep+"</td>";
						}
						if(data[i].stage_manager_owner_id == null || data[i].stage_manstatus=='1') {
							row +="<td><span><font color='#FF8C00'><b>Pending<b></font></span></td>";
						}else{
							row +="<td>"+data[i].stage_man+"</td>";
						}

						if(data[i].stage_owner_id == null || data[i].stage_owner_status=='1') {
							row +="<td><span><font color='#FF8C00'><b>Pending<b></font></span></td>";
						} else {
							row +="<td>"+data[i].stage_rep+"</td>";
						}

						row += "<td><a href="+link+"><span class='glyphicon glyphicon-eye-open'></span></a></td>";
					row += "</tr>";
				}
				$('#tablebody').append(row);
                $('#tableTeam').DataTable();
				$('.legend-wrapper').remove();
				$('.dataTables_length').append('<label class="legend-wrapper" ><div class="legend pull-left" title="Rejected Opportunities"></div> <b>Rejected Opportunities</b></label>');

			}
		});
	}

    function pageload1(){
		loaderShow();
        var str='teamopp';
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_opportunitiesController/get_assigned_opportunities/teamopp'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				if (error_handler(data)) {
					return ;
				}
				loaderHide();
				console.log(data);
				$('#team_tablebody').empty();
				opportunities_data = data;
				var row = ''
				for(i=0; i < data.length; i++ ){
					var rowdata = JSON.stringify(data[i]);
					red = ''
					if ((data[i].opp_repstatus==3 && data[i].stage_repstatus==3) || (data[i].opp_manstatus==3 && data[i].stage_manstatus==3))  {
						red += "class='danger'";
					}
				   	link = "<?php echo site_url('manager_opportunitiesController/stage_view/'); ?>"+data[i].opportunity_id+"/teamopp";
					row +=   "<tr "+red+" >"+
						"<td>" + (i+1)+"</td>"+
						"<td>" + data[i].opportunity_name +"</td>"+
						"<td>" + data[i].lead_cust_name +"</td>"+
						"<td><a href="+link+">" + data[i].product_name+" Product(s)</a></td>"+
						"<td>" + data[i].industry_name + "</td>"+
						"<td>" + data[i].location_name + "</td>"+
						"<td>"+data[i].stage_name+"</td>";

						if(data[i].manager_owner_id == null || data[i].oppmanstatus=='1') {
							row +="<td><span><font color='#FF8C00'><b>Pending<b></font></span></td>";
						}else{
						   row +="<td>"+data[i].opp_man+"</td>";
						}
						if(data[i].owner_id == null || data[i].owner_status=='1') {
							row +="<td><span><font color='#FF8C00'><b>Pending<b></font></span></td>";
						}else{
						   row +="<td>"+data[i].opp_rep+"</td>";
						}
						if(data[i].stage_manager_owner_id == null || data[i].stage_manstatus=='1') {
							row +="<td><span><font color='#FF8C00'><b>Pending<b></font></span></td>";
						}else{
							row +="<td>"+data[i].stage_man+"</td>";
						}

						if(data[i].stage_owner_id == null || data[i].stage_owner_status=='1') {
							row +="<td><span><font color='#FF8C00'><b>Pending<b></font></span></td>";
						} else {
							row +="<td>"+data[i].stage_rep+"</td>";
						}

						row += "<td><a href="+link+"><span class='glyphicon glyphicon-eye-open'></span></a></td>";
					row += "</tr>";
				}
				$('#team_tablebody').append(row);
                $('#team_tableTeam').DataTable();
				$('.legend-wrapper').remove();
				$('.dataTables_length').append('<label class="legend-wrapper" ><div class="legend pull-left" title="Rejected Opportunities"></div> <b>Rejected Opportunities</b></label>');

			}
		});
	}

	function close_modal(){
		$('.modal').modal('hide');
		$('.form-control').val("");
	}

</script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="loader">
		<center><h1 id="loader_txt"></h1></center>
	</div>
<?php require 'demo.php'  ?>
<?php require 'manager_sidenav.php' ?>
<?php require 'manager_opportunity_create_popup.php' ?>
<div class="content-wrapper body-content">
	<div class="col-lg-12 column">
		<div class="row header1">
			<div class="col-xs-2 col-sm-2 col-md-3 col-lg-3 aa">
				<span class="info-icon">
					<div >
						<img src="<?php echo site_url()?>/images/new/i_off.png" onmouseover="this.src='<?php echo site_url()?>/images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url()?>/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" data-html="true" title="These are all the opportunities that are assigned to a manager or executive. Click on the <span class='glyphicon glyphicon-eye-open tooleye'></span> to view details about each opportunity."/>
					</div>
				</span>
			</div>
			<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6 pageHeader1 aa">
				<h2>Assigned Opportunities</h2>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-3 col-lg-3 aa">
				<div class="addBtns addPlus">
					<span class="info-icon">
						<img src="<?php echo site_url()?>/images/new/Plus_Off.png" onmouseover="this.src='<?php echo site_url()?>/images/new/Plus_ON.png'" onmouseout="this.src='<?php echo site_url()?>/images/new/Plus_Off.png'" alt="info" width="30px" height="30px" class="dropdown-toggle" data-toggle="dropdown" />
						<ul class="dropdown-menu rightdropdown pull-right" id="create_oppo"></ul>
					</span>
				</div>
			</div>
		</div>
        <ul class="nav nav-tabs">
					<li class="active" onclick="a()"><a data-toggle="tab" href="#myopp">My Opportunity</a></li>
					<li onclick="b()" id="state"><a data-toggle="tab" href="#teamopp">Team Opportunity</a></li>
		</ul>
        <div class="tab-content tab_countstat">
    		<!--<div class="table-responsive"> -->
            <div id="myopp" class="tab-pane fade in active">
    			 <table class="table" id="tableTeam">
    				<thead>
    					<tr>
    						<th class="table_header">Sl No</th>
        					<th class="table_header" style="text-align: left;">Name</th>
        					<th class="table_header" style="text-align: left;">Prospect</th>
        					<th class="table_header" style="text-align: left;">Product</th>
    						<th class="table_header" style="text-align: left;">Industry</th>
    						<th class="table_header" style="text-align: left;">Location</th>
        					<th class="table_header" style="text-align: left;">Stage</th>
        					<th class="table_header" style="text-align: left;">Manager</th>
        					<th class="table_header" style="text-align: left;">Executive</th>
        					<th class="table_header" style="text-align: left;">Stage Manager</th>
        					<th class="table_header" style="text-align: left;">Stage Executive</th>
        					<th class="table_header" data-orderable="false"></th>
    					</tr>
    				</thead>
    				<tbody id="tablebody">
    				</tbody>
    			</table>
    		</div>
            <div id="teamopp" class="tab-pane fade">
    			 <table class="table" id="team_tableTeam">
    				<thead>
    					<tr>
    						<th class="table_header">Sl No</th>
        					<th class="table_header" style="text-align: left;">Name</th>
        					<th class="table_header" style="text-align: left;">Prospect</th>
        					<th class="table_header" style="text-align: left;">Product</th>
    						<th class="table_header" style="text-align: left;">Industry</th>
    						<th class="table_header" style="text-align: left;">Location</th>
        					<th class="table_header" style="text-align: left;">Stage</th>
        					<th class="table_header" style="text-align: left;">Manager</th>
        					<th class="table_header" style="text-align: left;">Executive</th>
        					<th class="table_header" style="text-align: left;">Stage Manager</th>
        					<th class="table_header" style="text-align: left;">Stage Executive</th>
        					<th class="table_header" data-orderable="false"></th>
    					</tr>
    				</thead>
    				<tbody id="team_tablebody">
    				</tbody>
    			</table>
    		</div>
        </div>
	</div>
</div>
<?php require ('footer.php'); ?>
</body>
</html>