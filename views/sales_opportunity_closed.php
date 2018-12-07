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
        url : "<?php echo site_url('sales_opportunitiesController/get_closed_opportunities'); ?>",
        dataType : 'json',
        cache : false,
        success : function(data){
            if (error_handler(data)) {
                return ;
            }
            loaderHide();
            $('#tablebody').empty();
            if(data.length > 0){
                $('#opp_accept').removeClass('hidden');
            }
            var row="";
            for(i=0; i < data.length; i++ ){
                var rowdata = JSON.stringify(data[i]);
                var losstype ='';
                if(data[i].reason=="temporary_loss"){              
                   losstype = "<span><font color='#ff9900'><b>Temp Loss</b></font></span>";              
                }
                else if(data[i].reason=="permanent_loss"){
                    losstype = "<span><font color='#cc0000'><b>Perm Loss<b></font></span>";
                }
                else if(data[i].reason=="closed_won"){
                    losstype = "<span><font color='#03A228'><b>Closed Won<b></font></span>";
                }
                var link = "<?php echo site_url('sales_opportunitiesController/stage_view/'); ?>"+data[i].opportunity_id;
                row += "<tr>"+
                    "<td>" + (i+1)+"</td>"+
                    "<td>" + data[i].opportunity_name + "</td>"+
                    "<td>" + data[i].lead_cust_name +"</td>"+
                    "<td>" + data[i].product +"</td>"+
                    /* --- hiding (03-09-2018)-----<td>" + data[i].opportunity_value +"</td>"+ */
                    "<td>" + data[i].expected_close_date +"</td>"+
                    "<td>" + data[i].stage_name +"</td>"+
                    "<td>" + data[i].rep_owner +"</td>"+
                    "<td>" + data[i].stagerep_owner +"</td>"+
                    "<td>" + losstype +"</td>"+
                    "<td><a href='"+link+"'><span class='glyphicon glyphicon-eye-open'></span></a></td>"+
                "</tr>";            
        }
        $('#tablebody').append(row);
       $('#tableTeam').DataTable({
            "aoColumnDefs": [{ "bSortable": false, "aTargets": [9] }]
        });
        }
    });    
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
                        <img src="<?php echo site_url()?>/images/new/i_off.png" onmouseover="this.src='<?php echo site_url()?>/images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url()?>/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" data-html="true" title="View all the opportunities that got converted into customers successfully. Click on the <span class='glyphicon glyphicon-eye-open tooleye'></span> on each opportunity to view their details."/>
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
                <div class="table-responsive">
                     <table class="table" id="tableTeam">
                        <thead>  
                            <tr>
                            <th class="table_header">Sl No</th>
                            <th class="table_header" style="text-align: left;">Name</th>
                            <th class="table_header" style="text-align: left;">Prospect</th>
                            <th class="table_header" style="text-align: left;">Product</th>
                            <!--<th class="table_header" style="text-align: left;">Amount</th>-->
                            <th class="table_header" style="text-align: left;">Close Date</th>
                            <th class="table_header" style="text-align: left;">Stage</th>
                            <th class="table_header" style="text-align: left;">Executive </th>
                            <th class="table_header" style="text-align: left;">Stage Executive </th>
                            <th class="table_header" style="text-align: left;">Status </th>
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