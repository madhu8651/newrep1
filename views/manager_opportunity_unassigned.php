<!DOCTYPE html>
<html lang="en">
	<head>
	<?php require 'scriptfiles.php' ?>
	<style type="text/css">
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
		li_html += `<li><a onclick='show_opp_create("Lead");'><h4>Lead</h4></a></li>`;
    	}
    	if (customers == "1") {
    		li_html += `<li><a onclick='show_opp_create("Customer");'><h4>Customer</h4></a></li>`;
    	}
		$("#create_oppo").append(li_html);
	});

	function pageload() {
	    $.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_opportunitiesController/get_unassigned_opportunities'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				if (error_handler(data)) {
					return ;
				}
				loaderHide();
				console.log(data);
				$('.modal').modal('hide');
				$('.closeinput').val('');
				$('#tableBody').parent("table").dataTable().fnDestroy();
				$('#tablebody').empty();
				if(data.length > 0){
					$('#assign_btn').removeClass('hidden');
	   			}
	   			opportunities_data = data;
				for(i=0; i < data.length; i++ ){
					var rowdata = JSON.stringify(data[i]);
                    var localObj = {};
                    localObj.opp_id = data[i].opportunity_id;
                    localObj.lead_cust_id = data[i].lead_cust_id;
                    localObj.prod_id = data[i].opportunity_product;
                    localObj.currency = data[i].opportunity_currency;
                    localObj.ind_id = data[i].opportunity_industry;
                    localObj.loc_id = data[i].opportunity_location;
                    localObj.sell_type = data[i].sell_type;
                    localObj.cycle_id = data[i].cycle_id;
                    localObj.stage_id = data[i].stage_id;
                    localObj.opp_name = data[i].opportunity_name;

					link = "<?php echo site_url('manager_opportunitiesController/stage_view/'); ?>"+data[i].opportunity_id+"/uassigned";
					row = "<tr><td>"+(i+1)+"</td><td>"+data[i].opportunity_name+"</td>"+
                            "<td>"+data[i].lead_cust_name+"</td>"+
							"<td>"+data[i].product_name+"</td>";

                            if(data[i].manager_owner_id!='') {
                                row +="<td>"+data[i].opp_man+"</td>";
                            } else {
                                row +="<td><input type='button' class='btn' onclick='assign("+JSON.stringify(localObj)+",\"opportunity\")' value='Assign'></td>";
                            }

                            if(data[i].owner_id==null && data[i].owner_status==0) {
                               row +="<td><input type='button' class='btn' onclick='assign("+JSON.stringify(localObj)+",\"opportunity\")' value='Assign'></td>";
                            } else if(data[i].owner_id!=null) {
                               row +="<td>"+data[i].opp_rep+"</td>";
                            } else {
                               row +="<td>Pending</td>";
                            }

							row +="<td>"+data[i].stage_name+"</td>";

                            if(data[i].stage_manager_owner_id!='') {
                                row +="<td>"+data[i].stage_man+"</td>";
                            } else {
                                row +="<td><input type='button' class='btn' onclick='assign("+JSON.stringify(localObj)+",\"stage\")' value='Assign'></td>";
                            }

							if(data[i].stage_owner_id==null && data[i].stage_owner_status==0) {
                               row +="<td><input type='button' class='btn' onclick='assign("+JSON.stringify(localObj)+",\"stage\")' value='Assign'></td>";
                            } else if(data[i].stage_owner_id!=null) {
                               row +="<td>"+data[i].stage_rep+"</td>";
                            } else {
                               row +="<td>Pending</td>";
                            }

							row +="<td>"+data[i].opportunity_value+"</td>"+
						  	"<td><a href="+link+"><span class='glyphicon glyphicon-eye-open'></span></a></td>";
						"</tr>";
					$('#tablebody').append(row);
				}
				$('#tableTeam').DataTable();
			}
		});
	}

	var finalArray = {};
	function assign(obj,leadsflow) {

        $("#modalstart1").find('.error-alert').text('');
        $("#modalstart1 ul").empty();
	   	$("#modalstart1").modal("show");
		$("#mgrlist").css({
			'background':'url(<?php echo base_url();?>images/hourglass.gif)',
			'background-position':'center',
			'background-size':'30px',
			'background-repeat':'no-repeat'
		});
		if(leadsflow == 'opportunity'){
			obj.btn_status = 'oppOwner';
		}
		if(leadsflow == 'stage'){
			obj.btn_status = 'stageOwner'
		}

	  	$.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_opportunitiesController/get_assignees');?>",
            dataType:'json',
            data:JSON.stringify(obj),
            success: function(data) {
            	if (error_handler(data)) {
					return ;
				}
            	$("#mgrlist").removeAttr('style');
            	var multipl2 = '', flg = 0;
				if(data.length > 0){
					for(var i=0;i<data.length; i++){
						/*-----------------Changed requirement on 21-06-2018--------------------------------------
						if(data[i].sales_module=='0' && data[i].manager_module!='0'){
							multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'"> '+data[i].user_name+' (Manager)</label></li>';
						}
						if(data[i].sales_module!='0' && data[i].manager_module=='0'){
							multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'"> '+data[i].user_name+' (Executive)</label></li>';
						}
						if(data[i].sales_module!='0' && data[i].manager_module!='0'){
							multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'"> '+data[i].user_name+' (Manager)</label></li>';
							multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'"> '+data[i].user_name+' (Executive)</label></li>';
						}
						-----------------------------------------------------*/

						if(data[i].sales_module!='0'){
							flg = 1;
							multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'"> '+data[i].user_name+' (Executive)</label></li>';
						}
					}
				}
				if(flg == 1){
					$("#mgrlist ul").append('<li><label><input type="checkbox" name="select_all_mgr" onclick="checkAllMgrs(this)"> Select All</label></li>');
				}else{
					multipl2 = '<center>No Executive found</center>';
				}

	    		$("#mgrlist ul").append(multipl2);
                $("#assign_id").attr("onclick","assign_save("+JSON.stringify(obj)+",'"+leadsflow+"')");
		    }
        });
	}

  	function assign_save(obj,leadsflow)
    {
		finalArray['users'] = [];
		$(".mgrlist_sales, .mgrlist_manager").each(function(){
			if($(this).prop('checked')== true){
				var localObj = {};
				localObj['to_user_id'] = $(this).attr('id');
				localObj['module'] = $(this).val();
				finalArray['users'].push(localObj);
			}
		});

		if(finalArray['users'].length == 0){
			$("#mgr_div").find("span").text("Select at least one user to assign.");
			$("#mgr_div").focus();
			return;
		} else {
			$("#mgr_div").find("span").text("");
		}

	   	finalArray['leadsflow'] = leadsflow;
		finalArray['oppo']=obj;
        if(leadsflow == 'opportunity'){
         obj.btn_status = 'oppOwner';
        }
        if(leadsflow == 'stage'){
         obj.btn_status = 'stageOwner'
        }
		loaderShow();
	    $.ajax({
	            type: "POST",
	            url: "<?php echo site_url('manager_opportunitiesController/assign_opportunities'); ?>",
	            data:JSON.stringify(finalArray),
	            dataType:'json',
	            success: function(data) {
	            	if (error_handler(data)) {
						return ;
					}
	          		//console.log(data);
		          	if(data==1) {
						close_modal();
		          	}
		          	pageload();
	            }
	    });
	}

	function cancel(){
		$('.modal').modal('hide');
		$('.modal input, select, textarea').val('');
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
	 }

	function view(obj) {
		var url = "<?php echo site_url('manager_opportunitiesController/stage_view/unassigned'); ?>"+obj.opportunity_id;
		window.location.replace(url);
	}

	function close_modal(){
		$('#modalstart1').modal('hide');
		$('.modal input[type="text"],#completed select,#addmodal select, textarea').val('');
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
	}

	function checkAllMgrs(e)	{
		$('li input:checkbox',$("#mgrlist")).prop('checked',e.checked);
	}
	</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
	<div class="loader">
		<center><h1 id="loader_txt"></h1></center>
	</div>
		<?php require 'demo.php' ?>
		<?php require 'manager_sidenav.php' ?>
		<?php require 'manager_opportunity_create_popup.php' ?>
			<div class="content-wrapper body-content">
				<div class="col-lg-12 column">
					<div class="row header1">
						<div class="col-xs-2 col-sm-2 col-md-3 col-lg-3 aa">
							<span class="info-icon toolTipStyle">
								<div >
									<img src="<?php echo site_url()?>/images/new/i_off.png" onmouseover="this.src='<?php echo site_url()?>/images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url()?>/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" title="These are all the opportunities that you have accepted but not assigned to anyone to work on. Choose an opportunity to assign it to a manager or executive. When assigning, the people who show up on the list are ones who can handle the chosen opportunity’s parameters. In case of any discrepancy, check the settings in the Admin Console."/>
								</div>
							</span>
						</div>
						<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6 pageHeader1 aa">
							<h2>Unassigned Opportunities</h2>
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
        							<th class="table_header" style="text-align: left;">Manager</th>
        							<th class="table_header" style="text-align: left;">Rep</th>
        							<th class="table_header" style="text-align: left;">Stage</th>
        							<th class="table_header" style="text-align: left;">Stage Manager</th>
        							<th class="table_header" style="text-align: left;">Stage Rep</th>
        							<th class="table_header" style="text-align: left;">Amount</th>
        							<th class="table_header" data-orderable="false"></th>
								</tr>
							</thead>
							<tbody id="tablebody">
							</tbody>
						</table>
					</div>
				</div>
				<div id="modalstart1" class="modal fade" data-backdrop="static" data-keyboard="false">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<span class="close"  onclick="close_modal()">&times;</span>
								<h4 class="modal-title">Opportunity Assignment</h4>
							</div>
							<div class="modal-body">
								<div class="row targetrow">
									<div class="col-md-2">
										<label for="mgrlist">Assign To</label>
									</div>
									<div class="col-md-10" id="mgr_div">
										<div id="mgrlist" class="multiselect">
										<ul></ul>
										</div>
										<span class="error-alert"></span>
									</div>
								</div>
							   <!--	<div class="row targetrow">
									<div class="col-md-2">
										<label for="assign_remarks">Assign Remarks</label>
									</div>
									<div class="col-md-10">
										<textarea cols="80" rows="5" placeholder="Enter remarks for assigning Opportunity(s)" id="assign_remarks" class="form-control"></textarea>
									</div>
								</div>
								<div class="row targetrow targetrow1 none">
									<input type="checkbox" name="select_all_mgr" id="select_opp" onclick="checkAllMgrs(this)"> Select All
								</div>-->
							</div>
							<div class="modal-footer">
								<input type="button" id="assign_id" class="btn"  value="Assign">
								<input type="button" class="btn" onclick="close_modal()" value="Cancel">
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php require ('footer.php'); ?>
	</body>
</html>