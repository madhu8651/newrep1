<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'scriptfiles.php' ?>
<style>
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
     #myopp .table,
    #teamopp .table{
      width:100% !important;
    }

</style>
<script>
$(document).ready(function(){

        loadData('myopp_closed');
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

function  loadData(opt)
{

        if(opt=='myopp_closed')
        {
           var url_temp="<?php echo site_url('manager_opportunitiesController/get_closed_opportunities/myopp'); ?>";
           var url_temp1="/myopp";
        }else{
           var url_temp="<?php echo site_url('manager_opportunitiesController/get_closed_opportunities/teamopp'); ?>";
           var url_temp1="/teamopp";
        }


    	$.ajax({
		type : "POST",
		url : url_temp,
		dataType : 'json',
		cache : false,
		success : function(data){
			if (error_handler(data)) {
				return ;
			}
			loaderHide();
            if(opt=='myopp_closed')
            {
               $('#tablebody').parent("table").dataTable().fnDestroy();
            }else{
               $('#tablebody1').parent("table").dataTable().fnDestroy();
            }

			var row="";

        	for(i=0; i < data.length; i++ ){
			var link = "<?php echo site_url('manager_opportunitiesController/stage_view/'); ?>"+data[i].opportunity_id+url_temp1;
			var losstype ='';
				if(data[i].reason=="temporary_loss"){
				   losstype = "<span><font color='#ff9900'><b>Temp Loss</b></font></span>";
				} else if(data[i].reason=="permanent_loss"){
					losstype = "<span><font color='#cc0000'><b>Perm Loss</b></font></span>";
				} else if(data[i].reason=="closed_won"){
					losstype = "<span><font color='#3FB624'><b>Closed won</font></b></span>";
				}
				row += "<tr>\
					<td>" + (i+1)+ "</td>\
					<td>" + data[i].opportunity_name +"</td>\
					<td>" + data[i].lead_cust_name +"</td>\
					<td>" + data[i].product+ "</td>\
					<td>" + data[i].industry_name+ "</td>\
					<td>" + data[i].location_name+ "</td>\
					<td>" + data[i].expected_close_date+ "</td>\
					<td>" + data[i].stage_name +"</td>\
					<td>" + data[i].stage_owner +"</td>\
					<td>" +losstype + "</td>\
					<td><a href='"+link+"'><span class='glyphicon glyphicon-eye-open'></span></a></td>\
				</tr>";
		 	}
            //	<td>" + data[i].opportunity_value+ "</td>\
		   //			<td>" + data[i].opportunity_quantity+ "</td>\
            if(opt=='myopp_closed')
            {
               	$('#tablebody').html('').append(row);
                $('#myTableTeam').DataTable();
                if(data.length == 0){
                   $('#myTableTeam tbody tr td').attr('colspan' ,$('#myTableTeam thead tr th').length);
                }
            }else{
               	$('#tablebody1').html('').append(row);
			    $('#team_tableTeam').DataTable();
                if(data.length == 0){
                   $('#team_tableTeam tbody tr td').attr('colspan' ,$('#team_tableTeam thead tr th').length);
                }
            }

		}
	});
}

</script>
</head>
<body class="hold-transition skin-blue sidebar-mini"> 
<div class="loader">
		<center><h1 id="loader_txt"></h1></center>  
	</div>
<?php  require 'demo.php'  ?>
<?php require 'manager_sidenav.php' ?>
<?php require 'manager_opportunity_create_popup.php' ?>
<div class="content-wrapper body-content">
	<div class="col-lg-12 column">
		<div class="row header1">
			<div class="col-xs-2 col-sm-2 col-md-3 col-lg-3 aa">
				<span class="info-icon">
					<div >
						<img src="<?php echo site_url()?>/images/new/i_off.png" onmouseover="this.src='<?php echo site_url()?>/images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url()?>/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Closed Opportunities List"/>
					</div>
				</span>
			</div>
			<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6 pageHeader1 aa">
				<h2>Won Opportunities</h2>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-3 col-lg-3 aa">
				<div class="addBtns addPlus">
					<span class="info-icon">
						<!--<img src="<?php echo site_url()?>/images/new/Plus_Off.png" onmouseover="this.src='<?php echo site_url()?>/images/new/Plus_ON.png'" onmouseout="this.src='<?php echo site_url()?>/images/new/Plus_Off.png'" alt="info" width="30px" height="30px" class="dropdown-toggle" data-toggle="dropdown" />-->
						<ul class="dropdown-menu rightdropdown pull-right" id="create_oppo"></ul>
					</span>
				</div>
			</div>
		</div>
        <ul class="nav nav-tabs">
					<li class="active" onclick="loadData('myopp_closed')"><a data-toggle="tab" href="#myopp">My Opportunity</a></li>
					<li onclick="loadData('teamopp_closed')" id="state"><a data-toggle="tab" href="#teamopp">Team Opportunity</a></li>
		</ul>
        <div class="tab-content tab_countstat">
	   <!--	<div class="table-responsive">   -->
             <div id="myopp" class="tab-pane fade in active">
            			 <table class="table" id="myTableTeam">
            				<thead>
            					<tr>
            						<th class="table_header">Sl No</th>
            						<th class="table_header" style="text-align: left;">Name</th>
            						<th class="table_header" style="text-align: left;">Prospect</th>
            						<th class="table_header" style="text-align: left;">Product</th>
            						<th class="table_header" style="text-align: left;">Industry</th>
            						<th class="table_header" style="text-align: left;">Location</th>
            					   <!--	<th class="table_header" style="text-align: left;">Amount</th>
            						<th class="table_header" style="text-align: left;">Quantity</th>-->
            						<th class="table_header" style="text-align: left;">Close Date</th>
            						<th class="table_header" style="text-align: left;">Sales Stage</th>
            						<th class="table_header" style="text-align: left;">Closed by</th>
            						<th class="table_header" style="text-align: left;">Status</th>
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
            					   <!--	<th class="table_header" style="text-align: left;">Amount</th>
            						<th class="table_header" style="text-align: left;">Quantity</th>-->
            						<th class="table_header" style="text-align: left;">Close Date</th>
            						<th class="table_header" style="text-align: left;">Sales Stage</th>
            						<th class="table_header" style="text-align: left;">Closed by</th>
            						<th class="table_header" style="text-align: left;">Status</th>
            						<th class="table_header" data-orderable="false"></th>
                          </tr>
                    </thead>
                    <tbody id="tablebody1">

                    </tbody>
    			</table>
    		</div>
		</div>
	</div>
</div>
<?php require ('footer.php'); ?>
</body>
</html>