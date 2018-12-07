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
		}
	</style>
<script>
$(document).ready(function(){
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
        loadData();
});

function loadData() {
        $.ajax({
        type : "POST",
        url : "<?php echo site_url('sales_opportunitiesController/get_inprogress_opportunities'); ?>",
        dataType : 'json',
        cache : false,
        success : function(data){
            if (error_handler(data)) {
                return ;
            }
            var userid="<?php echo $this->session->userdata('uid'); ?>";
            loaderHide();
            $('#tablebody').empty();
            var row="";
            for(i=0; i < data.length; i++ ){
                var ownership ='';
                if(data[i].owner_id==userid){
                       ownership = data[i].owner_name;
                }else{
                    if(data[i].owner_name=='pending'){
                        ownership="<span><font color='#FF8C00'><b>Pending<b></font></span>"
                    } else{
                        ownership = data[i].owner_name;
                    }
                }
                var stage_ownership='';
                if(data[i].stage_owner_id==userid){
                       stage_ownership = data[i].stage_owner_name;
                }else{
                    if(data[i].stage_owner_name=='pending'){

                        stage_ownership="<span><font color='#FF8C00'><b>Pending<b></font></span>"

                    } else{
                        stage_ownership = data[i].stage_owner_name;
                    }
                }

                row += "<tr>"+
                    "<td>" + (i+1)+"</td>"+
                    "<td>" + data[i].opportunity_name + "</td>"+
                    "<td>" + data[i].lead_cust_name +"</td>"+
                    "<td>" + data[i].product +"</td>"+
                    /* ---- hiding (03-09-2018)------------"<td>" + data[i].opportunity_value +"</td>"+ */
                    "<td>" + data[i].expected_close_date +"</td>"+
                    "<td>" + data[i].stage_name +"</td>"+
                    "<td>" + ownership +"</td>"+
                    "<td>" + stage_ownership +"</td>"+
                    "<td>"+
                        "<a onclick='redirect(\""+ data[i].opportunity_id + "\" , \""+ data[i].owner_name + "\")' >"+
                            "<span class='glyphicon glyphicon-eye-open'></span>"+
                        "</a>"+
                    "</td>"+
                "</tr>";
            }
            $('#tablebody').append(row);
            $('#tableTeam').DataTable({
                "aoColumnDefs": [{ "bSortable": false, "aTargets": [8] }]
            });
        }
    });
}

function redirect(id, state){
    if(state == 'pending'){
        alert("Opportunity Owner Pending");

    }else{
      window.location.href = "<?php echo site_url('sales_opportunitiesController/stage_view/'); ?>"+id;
    }
}
function cancel(){
    $('.modal').modal('hide');
    $('input, select, textarea').val('');
    $('input[type="radio"]').prop('checked', false);
    $('input[type="checkbox"]').prop('checked', false);
 }

	</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
        <div class="loader">
            <center><h1 id="loader_txt"></h1></center>
        </div>
		<?php require 'demo.php' ?>
		<?php require 'sales_sidenav.php' ?>
		<?php require 'sales_opportunity_create_popup.php' ?>
			<div class="content-wrapper body-content">
				<div class="col-lg-12 column">
				<div class="row header1">
					<div class="col-xs-2 col-sm-2 col-md-3 col-lg-3 aa">
						<span class="info-icon">
							<div >
                        <img src="<?php echo site_url()?>/images/new/i_off.png" onmouseover="this.src='<?php echo site_url()?>/images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url()?>/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" data-html="true" title="View all the opportunities in progress. Click on the <span class='glyphicon glyphicon-eye-open tooleye'></span> to view all the details about the opportunity and progress it."/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6 pageHeader1 aa">
							<h2>In Progress Opportunities</h2>
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
				<div class="table-responsive">
					 <table class="table" id="tableTeam">
						<thead>
                            <tr>
                            <th class="table_header">Sl No</th>
                            <th class="table_header" style="text-align: left;">Name</th>
                            <th class="table_header" style="text-align: left;">Prospect</th>
                            <th class="table_header" style="text-align: left;">Product</th>
                            <!----- hiding (03-09-2018)-----<th class="table_header" style="text-align: left;">Amount</th>-->
                            <th class="table_header" style="text-align: left;">Close Date</th>
                            <th class="table_header" style="text-align: left;">Stage</th>
                            <th class="table_header" style="text-align: left;">Executive </th>
                            <th class="table_header" style="text-align: left;">Stage Executive </th>
                            <th class="table_header" data-orderable="false"></th>
                            </tr>
						</thead>
						<tbody id="tablebody">
						</tbody>
					</table>
				</div>
			</div>

		</div>
		<?php require ('footer.php'); ?>
	</body>
</html>