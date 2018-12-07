<div id="leadview" class="modal fade" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="viewpopup" class="form" action="#" method="post" >
                            <div class="modal-header">
                                <span class="close" onclick="cancel1()">x</span>
								<!--<span style='float: right; margin-right: 54px;margin-top: 4px;'onclick="lead_history(
                                )" class="fa fa-history"></span> -->  
                                <h4 class="modal-title"><b>View <span id="view_customer"></span></b></h4>
                                <input type="hidden" id="customer_id">
                            </div>
                            <div class="modal-body">								
								<div id='custInfoView'></div>								
								
								<div class="row lead_address">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<center><b>Product Purchase Information</b><button type="button" class="btn" id="purchase_info" onclick="purchaseInfoFunction()">Add</button></center>
									</div>
                                </div>
                                <div class="prod_purchase_info">
                                </div>
                                <div class="row lead_address" >
									<div class="col-md-6 col-sm-6 col-xs-6">
										<center><b>Office Address</b></center>
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<center><b>Special Comments</b></center>
									</div>
                                </div>
                                <div class="row">
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<pre class="pre" id="view_ofcadd"></pre> 
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<pre class="pre" id="view_splcomments"></pre> 
									</div>
								</div>
								<div class="row lead_address map-marker">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<center><b>Google Map</b></center>
									</div>
								</div>
                                <input type="hidden" id="view_latt">
                                 <input type="hidden" id="view_long">
                                <div class="row map-marker" id="view_map2" >
									<div class="row" id="view_maploc" style="width:100% px;height:150px;border:1px;">
									 </div>
                                </div>
							   <div class="row" >
									<div class="col-md-12 lead_address">
										<center><b>Customer Contact Person Information</b></center>
									</div>
								</div>
								<div class="row" id="contact_prsn_list">
						
								</div>
                                <!--<div class="row">
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_firstcontact">Contact Person</label> 
											</div>
											<div class="col-md-4">
												<label id="view_firstcontact"></label> 
											</div>                                    
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_designation">Designation</label> 
											</div>
											<div class="col-md-4">
												<label id="view_disgnation"></label> 
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primmobile">Mobile Number 1</label> 
											</div>
											<div class="col-md-4">
												<label id="view_primmobile"></label> 
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primmobile2">Mobile Number 2</label> 
											</div>
											<div class="col-md-4">
												 <label id="view_primmobile2"></label> 
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primemail">Email 1</label> 
											</div>
											<div class="col-md-4">
												 <label id="view_primemail"></label> 
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_contacttype">Buyer Persona</label> 
											</div>
											<div class="col-md-4">
												<label id="view_contacttype"></label> 
												<input type="hidden" id="lead_id"/>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="row">
										<div class="col-md-2 apport_label">
											<label for="view_displaypic">Photo</label> 
										</div>
										<div class="col-md-4">
											 <img width="100" height="100" id="leadpic"/>
										</div>
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primemai2">Email 2</label> 
											</div>
											<div class="col-md-4">
												<label id="view_primemai2"></label> 
											</div>                                  
										</div>										
									</div>
									<div class="col-md-12">
										<div class="col-md-2 apport_label">
											<label>Address</label> 
										</div>
										<div class="col-md-4">
											 <pre class="pre" id="view_contactAdd" ></pre>
										</div>
									</div>
								</div>-->	
								<br/>
								<div class="row" id="activity_tab_view">
									<ul class="nav nav-tabs">
										<li class="active" onclick="leadlog();" id="logdetails_cust"><a data-toggle="tab" href="#logdetails">Customer History</a></li>
										<li onclick="schedule_fetch();"><a data-toggle="tab" href="#logdetails1">Scheduled Activities</a></li>
										<li onclick="opp_log();"><a data-toggle="tab" href="#opp_details1">Opportunities</a></li>
										<li onclick="hist_log();"><a data-toggle="tab" href="#history">History</a></li>
									</ul>
									<div class="tab-content">
										<div id="logdetails" class="tab-pane fade in active">
											
										</div>
										<!------------------------------------------------------>
										<div id="logdetails1" class="tab-pane fade">
										
										</div>
										<!------------------------------------------------------>
										<div id="opp_details1" class="tab-pane fade">
											 
										</div>
										<div id="history" class="tab-pane fade">
											
										</div>
									</div>
								</div>
								<div class="row none" id="custom_head_view">
                                    <div class="col-md-12 lead_address">
                                        <center><b>Custom Fields</b></center>
                                    </div>
                                </div>
								<div class="row" id="custom_fields_view">
                                    
                                </div>
							<div class="row" id="opportunity">
							</div>					
                          <div class="modal-footer">
							<span onclick="lead_history()" class="btn pull-left"  title="Click to view Audit Trail">Audit Trail</span>
							<input type="button" class="btn"  id="manager_lead_save" onclick="assign_btn2()" value="Assign"/>
							<input type="button" class="btn lead_recieved_btn none"  onclick="reject_btn('single')" value="Reject">
							<input type="button" class="btn btn-default" onclick="cancel1()" value="Cancel">
                          </div>
                        </div>
                      </form>
                    </div>
                </div>
            </div>  	
<div id="lead_hist" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<span class="close" onclick="hide_lead_histry()">&times;</span>
				<h4 class="modal-title">Audit Report</h4>
			</div>
			<div class="modal-body">
				
				<ul class="nav nav-tabs nav-justified">
					<li class="active" id="scheduled_activity_li"><a data-toggle="tab" href="#logLess"><h4>History Summary</h4></a></li>
					<li ><a data-toggle="tab" href="#logDetails"><h4>Details</h4></a></li>
				</ul>
				<div class="tab-content">
					<div id="logLess" class="tab-pane fade in active">
						<table class="table">
							<!--<thead>
								<tr>
									<th class="table_header">lead History</th>
								</tr>
							</thead>-->  
							<tbody id="history_min">				
							</tbody>    
						</table>
					</div>
					<!------------------------------------------------------>
					<div id="logDetails" class="tab-pane fade">
						<table id="history_detail" class="table">
							
						</table>
					</div>
					
				</div>
			</div>
		
			<div class="modal-footer">
			   <input type="button" class="btn" value="Cancel" onclick="hide_lead_histry()">                           
			</div>
		</div>
	</div>
</div>
<div id="modalpurchase" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
							<span class="close"  onclick="close_modal1()">&times;</span>
							<h4 class="modal-title">Add Purchase Information</h4>
					</div>
				   <div class="modal-body">
					<div class="row">
						<div class="col-md-2">
							<label for="">Opportunity Owner*</label>
						</div>
						<div class="col-md-4">
							<select  class="form-control" id="pro_owner" onchange="pro()" name="pro_owner" >
							</select>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="add_product"> Product*</label> 
						</div>
						<div class="col-md-4" id="add_product" name="edit_product1">                                       
						   
						</div>
						<div id="product_err" align="right">
							 <span class="error-alert" style="margin-right: 158px;"></span>
						</div>
					</div>
					 <div class="row fetch_btn none">
						<!--<div align="right"><input type="button" class="btn" value="Fetch Currency" id="fetch_btn"></div>-->
						<div align="right"><input type="button" class="btn" value="Fetch Currency" onclick="fetch_btn()" id="fetch_style"></div>
					</div>
					<div class="row">									
						<div class="col-md-2">
							<label for="">Reference Number</label>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" id="ref_number" style="display:block!important"/>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="">Currency*</label>
						</div>
						<div class="col-md-4">
							  <select  class="form-control" id="product_Currency" name="product_Currency" onchange="change_val()" >
							</select>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row">									
						<div class="col-md-2">
							<label for="">Document</label>
						</div>
						<div class="col-md-4">
							<input type="file" class="form-control" id="purchase_doc" style="display:block!important"/>
							<span class="error-alert"></span>
						</div>									
					</div>
					<div id="selcd_pro">
						
					</div>
				</div>
				<div class="modal-footer">
						<input type="button" class="btn" onclick="addProductPurchase()" value="Save">
						<!--<input type="button" class="btn" onclick="cancel()" value="Cancel" >-->
						<input type="button" class="btn" onclick="close_modal1()" value="Cancel">
				</div>
		</div>
	</div>
</div>
<div id="editmodalpurchase" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
				<div class="modal-header">
						<span class="close"  onclick="close_purchase()">&times;</span>
						<h4 class="modal-title">Add Purchase Information</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-2">
							<label for="">Opportunity Owner</label>
						</div>
						<div class="col-md-4">
							<label id="e_opp_owner"></label>
						</div>
						<div class="col-md-2">
							<label for="">Currency</label>
						</div>
						<div class="col-md-4">							  
							<select  class="form-control" id="e_currency" name="product_Currency" onchange="change_val()" >
							
							</select>
							<input type="hidden" id="e_currency1" />
							<input type="hidden" id="e_id"/>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<label for="">Reference Number</label>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" id="e_ref_number" style="display:block!important"/>
							<span class="error-alert"></span>
						</div>
						<div class="col-md-2">
							<label for="">Document</label>
						</div>
						<div class="col-md-4">
							<input type="file" class="form-control" id="e_purchase_doc" style="display:block!important"/>
							<span class="error-alert"></span>
						</div>																		
					</div>
				   <div id="selcd_pro1">
						
					</div>
				</div>	
				<div class="modal-footer">
						<input type="button" class="btn" onclick="save_purchaseEdit()" value="Save">
						<!--<input type="button" class="btn" onclick="cancel()" value="Cancel" >-->
						<input type="button" class="btn" onclick="close_purchase()" value="Cancel">
				</div>
		</div>
	</div>
</div>  
<div id="modalstart1" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
				<div class="modal-header">
					<input type="hidden" id="asssign_save_section"/>
						<span class="close"  onclick="close_assign1()">&times;</span>
						<h4 class="modal-title">Customer Assignment</h4>
				</div>
				<div class="modal-body">
					<div class="row targetrow ">
							<div class="col-md-2">
								<label id="mngrlist"></label> 
							</div>
							<div class="col-md-10">											
								<div id="mgrlist" class="multiselect2" >
									<ul>
									</ul>
								</div>
								<span class="error-alert"></span>
							</div>	
					</div>
					<!--<label><input type="checkbox" name="select_all_mgr" onclick="checkAllMgrs(this)"> Select All</label>-->                           	
				 
				</div>
				<div class="modal-footer">
						<input type="button" class="btn" id="assign_value" onclick="assign_save()" value="Assign">
						<!--<input type="button" class="btn" onclick="cancel()" value="Cancel" >-->
						<input type="button" class="btn" onclick="close_assign1()" value="Cancel">
				</div>
		</div>
	</div>
</div>
<div id="assignment_error1" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
				<div class="modal-header">
					<span class="close"  onclick="assignment_close1()">&times;</span>
					<h4 class="modal-title"><b>Lconnectt</b></h4>
				</div>
				<div class="modal-body">
					<div class="row assignment_body">
							
					</div>
				</div>
				<div class="modal-footer">
						<input type="button" class="btn" onclick="assignment_close1()" value="Cancel">
				</div>
		</div>
	</div>
</div>
<script>
var assignmentPro = '', assignProArray = [];
/*--------------------------------------------------------------Currency Change Starts-------------------------------------------------------*/
function change_val(){
	var cureency_type = $("#product_Currency option:selected").text();
	var last2 = cureency_type.slice(-3);
		$(".pro_Value1").val(last2);
}
/*--------------------------------------------------------------Currency Change Ends-------------------------------------------------------*/
function assignment_close1(){
	$("#assignment_error1").modal("hide");	
}
/*--------------------------------------------------------------Purchase Add Starts-------------------------------------------------------*/
function addProductPurchase(){
	var product_field_chk=0;
	var in_value=[], result = '';	
	var prod=[],pro_price=[],pro_quantity=[],pro_strDate=[], pro_endDate=[],pro_renewaldate=[],rate=[],score=[],customer_code=[],priority=[];
	var end_datetime1="",renewal_datetime='';
	var length_select = $('#product_Currency').children('option').length;
	if($.trim($("#pro_owner").val())==""){
		$("#pro_owner").closest("div").find("span").text("Owner is required.");
		$("#pro_owner").focus();				
		return;
	}else{
		$("#pro_owner").closest("div").find("span").text("");
	}
	$("#product_value1 ul li input[type=checkbox]").each(function(){
		if($(this).is(":checked")){
			in_value.push($(this).val());
		}
	});
	console.log(in_value)
	if(in_value.length == 0){
		$("#product_err").find("span").text("Product is required.");
		$("#product_err").focus();				
		return;
	}
	if(length_select>1){
		if($.trim($("#product_Currency").val())==""){
			$("#product_Currency").closest("div").find("span").text("Currency is required.");
			$("#product_Currency").focus();				
			return;
		}else{
			$("#product_Currency").closest("div").find("span").text("");
		}
	}
    $(".pro_Value").each(function(){
		if($(this).val()==""){
			$(this).closest("div").find("span").text("Cost is required.");
			product_field_chk =1;
		}else if(!validate_quantity($.trim($(this).val()))) {
			$(this).closest("div").find("span").text("Only numbers and . allowed");
			product_field_chk =1;
		}else{
			$(this).closest("div").find("span").text("");
			pro_price.push($(this).val());
		}		
    });
	$(".product_Number").each(function(){
		if($(this).val()==""){
			$(this).closest("div").find("span").text("Quantity is required.");
			product_field_chk =1;
		}else if(!validate_quantity($.trim($(this).val()))) {
			$(this).closest("div").find("span").text("Please enter only numbers");
			product_field_chk =1;
		}else{
			$(this).closest("div").find("span").text("");
			pro_quantity.push($(this).val());	
		}			
    });
	$(".product_start_date").each(function(){
		if($(this).val()==""){
			$(this).closest("div").find("span").text("Start date is required.");
			product_field_chk =1;
		}else{
			$(this).closest("div").find("span").text("");
			pro_strDate.push($(this).val());
		}			
    });console.log(pro_strDate)
	$(".product_end_date").each(function(){
		if($(this).val()==""){			
			pro_endDate.push("");
		}else{
			pro_endDate.push($(this).val());
		}				
    });
	console.log(pro_endDate)
	$(".renewal_end_date").each(function(){
		if($(this).val()==""){			
			pro_renewaldate.push("");
		}else{
			pro_renewaldate.push($(this).val());
		}				
    });
	$(".product_rate").each(function(){
		if($(this).val()==""){			
			rate.push("");
		}else{
			rate.push($(this).val());
		}				
    });
	$(".product_score").each(function(){
		if($(this).val()==""){			
			score.push("");
		}else{
			score.push($(this).val());
		}				
    });
	$(".product_cust_code").each(function(){
		if($(this).val()==""){			
			customer_code.push("");
		}else{
			customer_code.push($(this).val());
		}				
    });
	$(".product_priority").each(function(){
		if($(this).val()==""){			
			priority.push("");
		}else{
			priority.push($(this).val());
		}				
    });
	if(product_field_chk ==1){
			return;
		}
	$("#product_value1 li input[type=checkbox]").each(function(){
        if($(this).prop('checked')==true){
            prod.push($(this).val());
        }        
    });
	var sum_arry = [],purchase_arry=[];
	for(i=0;i<prod.length;i++){
		var obj = {};
		obj.product_id = prod[i];
		obj.pro_cost = pro_price[i];
		obj.pro_quantity = pro_quantity[i];
		var start_datetime = moment(pro_strDate[i], 'lll').format('YYYY-MM-DD HH:mm:ss');
		if(pro_endDate[i]==""){			
			end_datetime1 = "";
		}else{
			end_datetime1 = moment(pro_endDate[i], 'lll').format('YYYY-MM-DD HH:mm:ss');			
		}
		if(pro_renewaldate[i]==""){			
			renewal_datetime = "";
		}else{
			renewal_datetime = moment(pro_renewaldate[i], 'lll').format('YYYY-MM-DD HH:mm:ss');			
		}
		obj.pro_strDate = start_datetime;
		obj.pro_endDate = end_datetime1;
		obj.pro_renewaldate = renewal_datetime;
		obj.rate = rate[i];
		obj.score = score[i];
		obj.customer_code = customer_code[i];
		obj.priority = priority[i];
		sum_arry.push(obj);
	}
    var addObj={};
    addObj.product = sum_arry;
    addObj.pro_owner=$.trim($("#pro_owner").val());
    addObj.product_Currency=$.trim($("#product_Currency").val());
    addObj.purchase_doc=$.trim($("#purchase_doc").val());
    addObj.customer_id=$("#customer_id").val();
    addObj.ref_number=$("#ref_number").val();
	purchase_arry.push(addObj);
	console.log(addObj)
	loaderShow();
	$.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_customerController/addProductPurchase')?>",
        dataType : 'json',
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){ 
		loaderHide();
        $("#editmodalpurchase").modal("hide");
			loaderHide();
			if(error_handler(data)){
				return;
			}
			$(".prod_purchase_info").empty();
			var row = "";					
			for (i = data.length-1;i>=0; i--) {
				var rowdata1 = JSON.stringify(data[i]);
				row += "<div id='"+data[i].purchase_id+"' style='border: 1px solid #d6cece;padding: 3px;'><div class='row product_heading' style='text-align:center'>Purchase Order "+(i+1)+"<span class='edit_purchase'><a href='#' onclick='editselrow("+rowdata1+")'><span class='glyphicon glyphicon-pencil'></span></a></span></div><div class='row'><div class='col-md-2'><label>Product Owner</label></div><div class='col-md-4'>"+data[i].product_owner+"</div><div class='col-md-2'><label>Currency</label></div><div class='col-md-4'>"+data[i].currency_name+"</div></div><div class='row'><div class='col-md-2'><label>Reference Number</label></div><div class='col-md-4'>"+data[i].reference_number+"</div></div>";
				for(j=0;j<data[i].prod_data.length;j++){
					if(data.length-1==0){
						$(".prod_purchase_info").css("height", 184);
						$(".prod_purchase_info").css("margin-bottom", 8);
						$(".prod_purchase_info").css("overflow", "scroll");
					}
					if(data.length-1>0){
						$(".prod_purchase_info").css("height", 300);
						$(".prod_purchase_info").css("margin-bottom", 8);
						$(".prod_purchase_info").css("overflow", "scroll");
					}
					var rowdata = data[i].prod_data[j];
						var end_cmng_date,renewal_cmng_date;
						if(rowdata.purchase_end_date=="0000-00-00 00:00:00"){
							end_cmng_date='';
						}else{
							end_cmng_date=moment(rowdata.purchase_end_date).format('lll');
						}if(rowdata.renewal_date=="0000-00-00 00:00:00"){
							renewal_cmng_date='';
						}else{
							renewal_cmng_date=moment(rowdata.renewal_date).format('lll');
						}
						row +="<div class='row'><b>"+(j+1)+"."+rowdata.product_name+"</b></div><div class='row'><div class='col-md-2'><label>Quantity</label></div><div class='col-md-4'><label>"+rowdata.quantity+"</label></div><div class='col-md-2'><label>Cost</label></div><div class='col-md-4'><label>"+rowdata.amount+"</label></div></div><div class='row'><div class='col-md-2'><label>Start Date</label></div><div class='col-md-4'><label>"+moment(rowdata.purchase_start_date).format('lll')+"</label></div><div class='col-md-2'><label>End Date</label></div><div class='col-md-4'><label>"+end_cmng_date+"</label></div></div><div class='row'><div class='col-md-2'><label>Renewal Date</label></div><div class='col-md-4'><label>"+renewal_cmng_date+"</label></div>";
						if(rowdata.rate){
							row +="<div class='col-md-2'><label>Rate</label></div><div class='col-md-4'><label>"+rowdata.rate+"</label></div></div>";
						}else{
							row +="</div>";
						}if(rowdata.score!='' && rowdata.customer_code!=''){
							row +="<div class='row'><div class='col-md-2'><label>Score</label></div><div class='col-md-4'><label>"+rowdata.score+"</label></div><div class='col-md-2'><label>Customer Code</label></div><div class='col-md-4'><label>"+rowdata.customer_code+"</label></div></div>";
						}else if(rowdata.customer_code!=''){
							row +="<div class='row'><div class='col-md-2'><label>Customer Code</label></div><div class='col-md-4'><label>"+rowdata.customer_code+"</label></div></div>";
						}else if(rowdata.score!=''){
							row +="<div class='row'><div class='col-md-2'><label>Score</label></div><div class='col-md-4'><label>"+rowdata.score+"</label></div></div>";
						}if(rowdata.priority){
							row +="<div class='row'><div class='col-md-2'><label>Priority</label></div><div class='col-md-4'><label>"+rowdata.priority+"</label></div></div>";
						}	
				}								
				row +="</div>";
			}				
			$(".prod_purchase_info").append(row);
        
         $('#modalpurchase').modal('hide');  
		close_modal1();
	}
});	  
 
	}
/*--------------------------------------------------------------Purchase Add Ends-------------------------------------------------------*/
/*--------------------------------------------------------------Purchase Edit Starts-------------------------------------------------------*/
function editselrow(obj){	
	console.log(obj)
	var addObj={},inn_arr=[];
	for(i=0;i<obj.prod_data.length;i++){
		id_arr.push(obj.prod_data[i].id);
	}
	$("#e_currency1").val(obj.currency_id);
	$("#e_id").val(obj.prod_data.id);
	$("#editmodalpurchase").modal("show");
	$("#purchase").val(obj.purchase_id);
	$("#e_opp_owner").text(obj.product_owner);
	$("#e_ref_number").text(obj.reference_number);
	$("#e_currency").text(obj.currency_name);	
	$("#selcd_pro1").empty();
	var row = "",cureency_type,last2;
	for(i=0;i<obj.prod_data.length;i++){
		var rowdata = obj.prod_data[i];
		cureency_type = obj.currency_name;
		if(cureency_type!=null){
			last2 = cureency_type.slice(-3);
		}		
		row +='<div class="'+rowdata.product_id+' product_field"><div class="row product_heading"><span class="'+rowdata.product_id+'">'+rowdata.product_name+'</span></div><div class="row"><div class="col-md-2"><label for="">Quantity*</label></div><div class="col-md-4"><input class="form-control e_product_Number" name="e_product_Number" value="'+rowdata.quantity+'"/><span class="error-alert"></span></div><div class="col-md-2"><label for="">Cost*</label></div><div class="col-md-4"><input type="" class="form-control e_pro_Value1" value="" disabled ><input type="" class="form-control e_pro_Value" id="'+rowdata.product_id+'" value="'+rowdata.amount+'"><span class="error-alert"></span></div></div><div class="row"><div class="col-md-2"><label for="">Start Date*</label></div><div class="col-md-4"><input type="text" id="actve_duration1" class="form-control e_product_start_date" placeholder="DD-MM-YYYY"   value="'+moment(rowdata.purchase_start_date).format("lll")+'">	<span class="error-alert"></span></div><div class="col-md-2"><label for="">End Date</label></div><div class="col-md-4"><input class="form-control e_product_end_date" placeholder="DD-MM-YYYY" value="'+moment(rowdata.purchase_end_date).format("lll")+'"/><span class="error-alert"></span></div></div><div class="row"><div class="col-md-2"><label>Renewal Date</label></div><div class="col-md-4"><input type="text" class="form-control e_renewal_end_date" placeholder="DD-MM-YYYY" value="'+moment(rowdata.renewal_date).format("lll")+'">	<span class="error-alert"></span></div><div class="col-md-2"><label for="">Rate</label></div><div class="col-md-4"><input class="form-control e_product_rate" value="'+rowdata.rate+'"/><span class="error-alert"></span></div></div><div class="row"><div class="col-md-2"><label for="">Score</label></div><div class="col-md-4"><input class="form-control e_product_score" value="'+rowdata.score+'"/><span class="error-alert"></span></div><div class="col-md-2"><label for="">Customer Code</label></div><div class="col-md-4"><input class="form-control e_product_cust_code" value="'+rowdata.customer_code+'"/><span class="error-alert"></span></div></div><div class="row"><div class="col-md-2"><label for="">Priority</label></div><div class="col-md-4"><input class="form-control e_product_priority" value="'+rowdata.priority+'"/><span class="error-alert"></span></div></div></div>';
	}
	$("#selcd_pro1").append(row);
	$("#selcd_pro1 .product_heading span").each(function(){
		inn_arr.push($(this).attr("class"));
	});
	addObj.productArray=inn_arr; 
	addObj.ownerId=obj.product_owner_id;		
 		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_customerController/postProduct'); ?>",
			data : JSON.stringify(addObj),
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
                    return;
                }
			 var select = $("#e_currency"), options = "<option value=''>select</option>";
			   select.empty();      

			   for(var i=0;i<data.length; i++)
			   {
					options += "<option value='"+data[i].currency_id+"'>"+ data[i].currency_name +"</option>";              
			   }
			   select.append(options);
			   if(obj.currency_id!=''){
				$("#e_currency option[value="+obj.currency_id+"]").prop("selected",true);
			   }
			}
		});
	if(last2 == "USD"){
		$(".e_pro_Value1").val("USD");
	}
	if(last2 == "INR"){
		$(".e_pro_Value1").val("INR");
	}else{
		$(".e_pro_Value1").val("");
		$(".e_pro_Value1").val("");
	}
	$(".e_renewal_end_date").datetimepicker({
		ignoreReadonly:true,
		allowInputToggle:true,
		format:'lll',
		minDate:moment(rowdata.purchase_start_date),
	});
	$(".e_product_end_date").datetimepicker({
		ignoreReadonly:true,
		allowInputToggle:true,
		format:'lll',
		minDate: new Date()
	});
	$(".e_product_start_date").each(function(){
		$(this).datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'lll',
		});
		$(this).on("dp.change", function (selected) {
			$(this).closest(".row").find(".e_product_end_date").data("DateTimePicker").clear();
			$(this).closest(".row").siblings(".row").find(".e_renewal_end_date").data("DateTimePicker").clear();
			var startDateTime = moment($.trim($(this).val()), 'lll');
			$(this).closest(".row").find(".e_product_end_date").data("DateTimePicker").minDate(startDateTime);
			$(this).closest(".row").siblings(".row").find(".e_renewal_end_date").data("DateTimePicker").minDate(startDateTime);
		})
	})
}
/*-------------------------------------------------------------------------Purchase Edit Ends-------------------------------------------------------*/
/*-------------------------------------------------------------------------Purchase Edit Save Starts-------------------------------------------------------*/
function save_purchaseEdit(){
	/* ------------Product field set validation------------ */
		
		var product_field_chk=0;
		var e_prod=[],e_pro_price=[],e_pro_quantity=[],pro_strDate=[], pro_endDate=[],pro_renewaldate=[],rate=[],score=[],customer_code=[],priority=[];
		var end_datetime1="",renewal_datetime='';	
		$(".e_product_Number").each(function(){
		if($(this).val()==""){
				$(this).closest("div").find("span").text("Quantity is required.");
				product_field_chk =1;
			}else if(!validate_quantity($.trim($(this).val()))) {
				$(this).closest("div").find("span").text("Please enter only numbers");
				product_field_chk =1;
			}else{
				$(this).closest("div").find("span").text("");
				e_pro_quantity.push($(this).val());	
			}			
		});
	$(".e_pro_Value").each(function(){
		if($(this).val()==""){
			$(this).closest("div").find("span").text("Cost is required.");
			product_field_chk =1;
		}else if(!validate_quantity($.trim($(this).val()))) {
			$(this).closest("div").find("span").text("Only numbers and . allowed");
			product_field_chk =1;
		}else{
			$(this).closest("div").find("span").text("");
			e_pro_price.push($(this).val());
		}		
    });
	$(".e_product_start_date").each(function(){
		if($(this).val()==""){
			$(this).closest("div").find("span").text("Start date is required.");
			product_field_chk =1;
		}else{
			$(this).closest("div").find("span").text("");
			pro_strDate.push($(this).val());
		}			
    });
	$(".e_renewal_end_date").each(function(){
		if($(this).val()==""){			
			pro_renewaldate.push("");
		}else{
			pro_renewaldate.push($(this).val());
		}				
    });
	$(".e_product_end_date").each(function(){
		if($(this).val()==""){			
			pro_endDate.push("");
		}else{
			pro_endDate.push($(this).val());
		}				
    });
	$(".e_product_rate").each(function(){
		if($(this).val()==""){			
			rate.push("");
		}else{
			rate.push($(this).val());
		}				
    });
	$(".e_product_score").each(function(){
		if($(this).val()==""){			
			score.push("");
		}else{
			score.push($(this).val());
		}				
    });
	$(".e_product_cust_code").each(function(){
		if($(this).val()==""){			
			customer_code.push("");
		}else{
			customer_code.push($(this).val());
		}				
    });
	$(".e_product_priority").each(function(){
		if($(this).val()==""){			
			priority.push("");
		}else{
			priority.push($(this).val());
		}				
    });
	if(product_field_chk ==1){
			return;
		}
	$("#selcd_pro1 .product_heading span").each(function(){
            e_prod.push($(this).attr("class"));       
    });
	var sum_arry = [],purchase_arry=[];
	for(i=0;i<e_prod.length;i++){
		var obj = {};
		obj.product_id = e_prod[i];
		obj.pro_cost = e_pro_price[i];
		obj.pro_quantity = e_pro_quantity[i];
		var start_datetime = moment(pro_strDate[i], 'lll').format('YYYY-MM-DD HH:MM:SS');
		if(pro_endDate[i]==""){			
			end_datetime1 = "";
		}else{
			end_datetime1 = moment(pro_endDate[i], 'lll').format('YYYY-MM-DD HH:mm:ss');			
		}
		if(pro_renewaldate[i]==""){			
			renewal_datetime = "";
		}else{
			renewal_datetime = moment(pro_renewaldate[i], 'lll').format('YYYY-MM-DD HH:mm:ss');			
		}
		obj.pro_strDate = start_datetime;
		obj.pro_endDate = end_datetime1;
		obj.pro_renewaldate = renewal_datetime;
		obj.rate = rate[i];
		obj.score = score[i];
		obj.customer_code = customer_code[i];
		obj.priority = priority[i];
		obj.id = id_arr[i];
		sum_arry.push(obj);
	}
    var addObj={};
    addObj.product = sum_arry;
    addObj.purchase_id=$.trim($("#purchase").val());
	//addObj.id=$("#e_id").val();
    addObj.product_Currency=$.trim($("#e_currency option:selected").val());
    addObj.purchase_doc=$.trim($("#e_purchase_doc").val());
    addObj.customer_id=$("#customer_id").val();
    addObj.ref_number=$("#e_ref_number").val();
	console.log(addObj)
	purchase_arry.push(addObj);
	loaderShow();
	$.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_customerController/updateProductPurchase')?>",
        dataType : 'json',
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){ 
			$("#editmodalpurchase").modal("hide");
			loaderHide();
			if(error_handler(data)){
				return;
			}
			$(".prod_purchase_info").empty();
			var row = "";				
			for (i = data.length-1;i>=0; i--) {
				var rowdata1 = JSON.stringify(data[i]);
				row += "<div id='"+data[i].purchase_id+"' style='border: 1px solid #d6cece;padding: 3px;'><div class='row product_heading' style='text-align:center'>Purchase Order "+(i+1)+"<span class='edit_purchase'><a href='#' onclick='editselrow("+rowdata1+")'><span class='glyphicon glyphicon-pencil'></span></a></span></div><div class='row'><div class='col-md-2'><label>Product Owner</label></div><div class='col-md-4'>"+data[i].product_owner+"</div><div class='col-md-2'><label>Currency</label></div><div class='col-md-4'>"+data[i].currency_name+"</div></div><div class='row'><div class='col-md-2'><label>Reference Number</label></div><div class='col-md-4'>"+data[i].reference_number+"</div></div>";
				for(j=0;j<data[i].prod_data.length;j++){
					if(data.length-1==0){
						$(".prod_purchase_info").css("height", 184);
						$(".prod_purchase_info").css("margin-bottom", 8);
						$(".prod_purchase_info").css("overflow", "scroll");
					}
					if(data.length-1>0){
						$(".prod_purchase_info").css("height", 300);
						$(".prod_purchase_info").css("margin-bottom", 8);
						$(".prod_purchase_info").css("overflow", "scroll");
					}
					var rowdata = data[i].prod_data[j];
						var end_cmng_date,renewal_cmng_date;
						if(rowdata.purchase_end_date=="0000-00-00 00:00:00"){
							end_cmng_date='';
						}else{
							end_cmng_date=moment(rowdata.purchase_end_date).format('lll');
						}if(rowdata.renewal_date=="0000-00-00 00:00:00"){
							renewal_cmng_date='';
						}else{
							renewal_cmng_date=moment(rowdata.renewal_date).format('lll');
						}
						row +="<div class='row'><b>"+(j+1)+"."+rowdata.product_name+"</b></div><div class='row'><div class='col-md-2'><label>Quantity</label></div><div class='col-md-4'><label>"+rowdata.quantity+"</label></div><div class='col-md-2'><label>Cost</label></div><div class='col-md-4'><label>"+rowdata.amount+"</label></div></div><div class='row'><div class='col-md-2'><label>Start Date</label></div><div class='col-md-4'><label>"+moment(rowdata.purchase_start_date).format('lll')+"</label></div><div class='col-md-2'><label>End Date</label></div><div class='col-md-4'><label>"+end_cmng_date+"</label></div></div><div class='row'><div class='col-md-2'><label>Renewal Date</label></div><div class='col-md-4'><label>"+renewal_cmng_date+"</label></div>";
						if(rowdata.rate){
							row +="<div class='col-md-2'><label>Rate</label></div><div class='col-md-4'><label>"+rowdata.rate+"</label></div></div>";
						}else{
							row +="</div>";
						}if(rowdata.score!='' && rowdata.customer_code!=''){
							row +="<div class='row'><div class='col-md-2'><label>Score</label></div><div class='col-md-4'><label>"+rowdata.score+"</label></div><div class='col-md-2'><label>Customer Code</label></div><div class='col-md-4'><label>"+rowdata.customer_code+"</label></div></div>";
						}else if(rowdata.customer_code!=''){
							row +="<div class='row'><div class='col-md-2'><label>Customer Code</label></div><div class='col-md-4'><label>"+rowdata.customer_code+"</label></div></div>";
						}else if(rowdata.score!=''){
							row +="<div class='row'><div class='col-md-2'><label>Score</label></div><div class='col-md-4'><label>"+rowdata.score+"</label></div></div>";
						}if(rowdata.priority){
							row +="<div class='row'><div class='col-md-2'><label>Priority</label></div><div class='col-md-4'><label>"+rowdata.priority+"</label></div></div>";
						}
				}								
				row +="</div>";
			}				
			$(".prod_purchase_info").append(row); 
			close_modal1();
		}
	});
}
/*-------------------------------------------------------------------------Purchase Edit Save Ends-------------------------------------------------------*/
/*--------------------------------------------------------------Assign Starts-------------------------------------------------------*/
var finalArray = {};

/* select atleast one checkbox and click on the Assign-Button on the main page */
function assign_btn(btn_val){
	if(btn_val==1){
		$("#assign_value").val("ReAssign")
	}
	$("#mngrlist").empty().append("Executive list");
	var check = [];
	$("#tablebody tr input:checkbox").each(function () {       
		if($(this).prop("checked")==true){
			check.push($(this).attr('id'));
			flagchk=1;
		}		
	});
	if(check.length <= 0){
		alert("Please select the Customer");
		return;	
	}	
	assign_date(check.join(":"),"multiple"); 
}
/* click on the view button on the main page >> the click on the assign button on the view popup section  */
function assign_btn2() {
	if($("#manager_lead_save").val()=="Assign"){
		$("#assign_value").val("Assign");
	}else if($("#manager_lead_save").val()=="ReAssign"){
		$("#assign_value").val("ReAssign");
	}
	$("#mngrlist").empty().append("Manager list");
	var id=$("#customer_id").val();
	assign_date(id, "single");
}
 function assign_date(idval , status){
	 console.log(idval)
		if(status == "multiple"){
			$("#asssign_save_section").val('').val('multiple')
		}
		if(status == "single"){
			$("#asssign_save_section").val('').val('single')
		}

		if(idval!=''){
			$("#mgrlist").siblings(".error-alert").text("");
			$("#modalstart1").modal("show");
		}
	
		var finalArray = {};
		finalArray.leads= idval.split(":");	
		$(".multiselect2 ul").empty();
		$(".multiselect2").css({
					'background':'url(<?php echo base_url();?>images/hourglass.gif)',
					'background-position':'center',
					'background-size':'30px',
					'background-repeat':'no-repeat'
				});
	  	$.ajax({
				type: "POST",
				url: "<?php echo site_url('manager_customerController/get_managerlist_reassign');?>",
				dataType:'json',
				data:JSON.stringify(finalArray),
				success: function(data) {
					$(".multiselect2").removeAttr('style');
					if(error_handler(data)){
						return;
					}
					var multipl2="";
					for(var i=0;i<data.length; i++){
						if(status == "multiple"){
							if(data[i].sales_module!='0' && data[i].manager_module=='0'){
								multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'">'+data[i].user_name+' (Executive)</label></li>';
							}
							if(data[i].sales_module!='0' && data[i].manager_module!='0'){
								multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'">'+data[i].user_name+' (Executive)</label></li>';
							}
						}
						
						if(status == "single"){
							if(data[i].sales_module=='0' && data[i].manager_module!='0'){
								if(mod_val != data[i].user_id){
									multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'">'+data[i].user_name+' (Manager)</label></li>';
								}
							}
							if(data[i].sales_module!='0' && data[i].manager_module!='0'){
								/* if the logged in user and data[i].user_id is not same */
								if(mod_val != data[i].user_id){
										multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'">'+data[i].user_name+' (Manager)</label></li>';
								}								
							}
						}
					}
					
					$(".multiselect2 ul").append(multipl2);
				}
       	});		
	}

/* click on the save button in the lead assignment popup */
	function assign_save() {
		var finalArray = {};
		var leads = [];
		if($("#asssign_save_section").val() == "multiple"){
			$("#tablebody tr input:checkbox").each(function () {       
				if($(this).prop("checked")==true){
					leads.push($(this).attr('id'));				
				}		
			});
		}
		if($("#asssign_save_section").val() == "single"){
			leads.push($("#customer_id").val());	
		}
		
		
		
		var users = [];
		$("#mgrlist .mgrlist_sales, #mgrlist .mgrlist_manager").each(function(){
			if($(this).prop('checked')== true){
				users.push({'to_user_id': $(this).attr('id') , 'module': $(this).val()});
			}
		});
				
		if (users.length == 0) {
			$("#mgrlist").siblings(".error-alert").text("Select a user to assign");
			return;
		}else{
			$("#mgrlist").siblings(".error-alert").text("");
		}
		finalArray.customers = leads;
		finalArray.users = users;
		loaderShow();
		$.ajax({
				type: "POST",
				url: "<?php echo site_url('manager_customerController/postReassign'); ?>",
				data:JSON.stringify(finalArray),
				dataType:'json',
				success: function(data) {
					if(error_handler(data)){
						return;
					}			
						close_assign1();     
					assignedLoad();			
				}
		});
		/* --------------------------------------------------------------------------
		------------future  implementation-------- don't remove this code------------
		-----------------------------------------------------------------------------
		if ($('input.checkbox_check').is(':checked')) {
			$.ajax({
					type: "POST",
					url: "<?php echo site_url('manager_leadController/sendemails'); ?>",
					data:JSON.stringify(arr),
					dataType:'json',
					success: function(data) {

					}	

			});
		} 
		---------------------------------------------------------------------------*/
	}
/*--------------------------------------------------------------Assign Ends-------------------------------------------------------*/
/*--------------------------------------------------------------Add Purchase Starts-------------------------------------------------------*/
function pro(){
	var ownerId='';
	$("#selcd_pro").empty();
	$("#product_Currency").empty();
	$("#product_value1").empty();
	var addObj={};
	addObj.ownerId=$("#pro_owner").val();
	ownerId=$("#pro_owner").val();
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('manager_customerController/getProductData'); ?>",
		data : JSON.stringify(addObj),
		dataType:'json',
		success: function(data) {
			cust_data = data;
			var flag = 0, flag1 = 0;
				if(error_handler(data)){
				return;
			}
			if(data==""){
				$(".fetch_btn").hide();
				$("#add_product").html("");
			}else{
				$("#add_product").html("");
				$("#add_product").append(currencyhtml);
				var currencyhtml="";
				currencyhtml +='<div id="product_value1" class="multiselect">';
				currencyhtml +='<ul>';
				for(var j=0;j<data.length; j++){
						flag = flag + 1;
						currencyhtml +='<li><label><input type="checkbox" id="'+data[j].product_id+'" value="'+data[j].product_id+'" onchange="clear_field()" name="'+data[j].product_name+'" disabled><span id="name_val">  '+data[j].product_name+'</span><label></li>';
				}
				currencyhtml +='</ul>';
				currencyhtml +='</div><div class="alertProduct" style="color: #d22929;"><i class="fa fa-ban fa-rotate-90" aria-hidden="true" ></i>' +
								" Choosing products depends on executive's permissions. Add them in Admin Console or reassign the prospect.</div>";
				/*<div><i class="fa fa-ban fa-rotate-90" aria-hidden="true" style="color: #d22929;"></i></div>*/
				$("#add_product").append(currencyhtml);
				$("#add_product").show();
				$(".fetch_btn").show();
				for(var j=0;j<data.length; j++){
					for(i = 0; i < assignProArray.length; i++){
						if(data[j].product_id == assignProArray[i]){
							flag1 = flag1 + 1;
							$("#"+data[j].product_id).prop("disabled", false);
						}
					}
				}
				if(flag > flag1){
					$(".alertProduct").show();
				}else{
					$(".alertProduct").hide();
				}
			}
		}
	});
}
function purchaseInfoFunction(){
		$('#modalpurchase').modal('show');
 		$.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_customerController/getManagers'); ?>",
            dataType:'json',
            success: function(data){
            	if(error_handler(data)){
                    return;
                }
				var select = $("#pro_owner"), options = "<option value=''>select</option>";
				select.empty();      
				for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].user_id+"'>"+ data[i].user_name +"</option>";              
				}
				select.append(options);
                 
            }
        });

 		
		
 		/* $('#product_Currency').on('change',function(){
			var addObj={};
			addObj.user_id=$('#pro_owner').val();
			addObj.currency_id=this.value;
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('manager_customerController/getProductData'); ?>",
				data : JSON.stringify(addObj),
				dataType:'json',
				success: function(data) {
					if(error_handler(data)){
						return;
					}
					$("#add_product").html("");
					$("#add_product").append(currencyhtml);
					var currencyhtml="";
					currencyhtml +='<div id="product_value1" class="multiselect">';
					currencyhtml +='<ul>';
					for(var j=0;j<data.length; j++){								
							currencyhtml +='<li><label><input type="checkbox" value="'+data[j].product_id+'">  '+data[j].product_name+'<label></li>';
					}
					currencyhtml +='</ul>';
					currencyhtml +='</div>';
					$("#add_product").append(currencyhtml);
				}
			});
		}); */

	}
/*--------------------------------------------------------------Add Purchase Ends-------------------------------------------------------*/
/*--------------------------------------------------------------Fetch Purchase Currency Starts-------------------------------------------------------*/
function clear_field(){
	$("#selcd_pro").empty();
}
function fetch_btn() {
		var result = '';
		$(".error-alert").empty();
		$("#selcd_pro").empty();
 		var in_value=[], in_name=[], flag = 0, name=[];
 		var addObj={};
 		$("#product_value1 ul li input[type=checkbox]").each(function(){
 			if($(this).is(":checked")){
 				in_value.push($(this).val());
 				in_name.push($(this).attr("name"));
 			}
 		});
		if(in_value.length == 0){
			$("#product_err").find("span").text("Product is required.");
			$("#product_err").focus();				
			return;
		}
		var row='';
		for(i=0;i<cust_data.length;i++){
			for(j=0;j<in_value.length;j++){
				if(cust_data[i].product_id==in_value[j]){
					row +='<div class="'+cust_data[i].product_id+' product_field"><div class="row product_heading">'+cust_data[i].product_name+'</div><div class="row"><div class="col-md-2"><label for="">Quantity*</label></div><div class="col-md-4"><input class="form-control product_Number" name="product_Number"/><span class="error-alert"></span></div><div class="col-md-2"><label for="">Cost*</label></div><div class="col-md-4"><input type="" class="form-control pro_Value1" value="" disabled ><input type="" class="form-control pro_Value" ><span class="error-alert"></span></div></div><div class="row"><div class="col-md-2"><label for="">Start Date*</label></div><div class="col-md-4"><input type="text" id="actve_duration" class="form-control product_start_date" placeholder="DD-MM-YYYY"  maxlength="5" >	<span class="error-alert"></span></div><div class="col-md-2"><label for="">End Date</label></div><div class="col-md-4"><input class="form-control product_end_date" placeholder="DD-MM-YYYY" /><span class="error-alert"></span></div></div><div class="row"><div class="col-md-2"><label for="">Renewal Date</label></div><div class="col-md-4"><input class="form-control renewal_end_date" placeholder="DD-MM-YYYY" /><span class="error-alert"></span></div><div class="col-md-2"><label for="">Rate</label></div><div class="col-md-4"><input class="form-control product_rate" /><span class="error-alert"></span></div></div><div class="row"><div class="col-md-2"><label for="">Score</label></div><div class="col-md-4"><input class="form-control product_score" /><span class="error-alert"></span></div><div class="col-md-2"><label for="">Customer Code</label></div><div class="col-md-4"><input class="form-control product_cust_code" /><span class="error-alert"></span></div></div><div class="row"><div class="col-md-2"><label for="">Priority</label></div><div class="col-md-4"><input class="form-control product_priority" /><span class="error-alert"></span></div></div></div>';				
				}
			}
		}
		$("#selcd_pro").append(row);
		$(".renewal_end_date").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'lll',
		});
		$(".product_end_date").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'lll',
			minDate: new Date()
		});
		$(".product_start_date").each(function(){
			$(this).datetimepicker({
				ignoreReadonly:true,
				allowInputToggle:true,
				format:'lll',
			});
			$(this).on("dp.change", function (selected) {
				var startDateTime = moment($.trim($(this).val()), 'lll');
				$(this).closest(".row").find(".product_end_date").data("DateTimePicker").minDate(startDateTime);
				$(this).closest(".row").siblings(".row").find(".renewal_end_date").data("DateTimePicker").minDate(startDateTime);				
			})
		})
		
 		addObj.productArray=in_value; 
 		addObj.ownerId=$('#pro_owner').val();		
 		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_customerController/postProduct'); ?>",
			data : JSON.stringify(addObj),
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
                    return;
                }
			 var select = $("#product_Currency"), options = "<option value=''>select</option>";
			   select.empty();      

			   for(var i=0;i<data.length; i++)
			   {
					options += "<option value='"+data[i].currency_id+"'>"+ data[i].currency_name +"</option>";              
			   }
			   select.append(options);
			}
		});
 	
	
}
/*--------------------------------------------------------------Fetch Purchase Currency Ends-------------------------------------------------------*/
/*--------------------------------------------------------------Customer View Starts-------------------------------------------------------*/
function viewrow(obj,btn_val){ 
	console.log(obj)
	assignmentPro = obj;
	if(obj.rep_product != null){
		assignProArray = obj.rep_product.split(",");
	}
	var obj1 = {};
	obj1.customerid=obj.customer_id;
	if(btn_val=='myCustomer'){
		$("#manager_lead_save").show();
		$("#manager_lead_save").val("ReAssign");
	}else if(btn_val == 'teamCustomer'){
		$("#manager_lead_save").hide();
	}
	$("#customer_id").val(obj.customer_id);
	leadlog();
	$("#activity_tab_view .tab-pane.fade").each(function(){
		$(this).removeClass("active").removeClass("in");
	});
	$("#activity_tab_view .nav-tabs li").each(function(){
		$(this).removeClass("active");
	});
	
	
	$('.custom-file-upload').find('i').remove();
	
	
	var personal={};
	personal.name = obj.customer_name;
	personal.email = obj.customer_email;
	personal.phone = obj.customer_number;
	personal.website = obj.customer_website;
	personal.country = obj.country_name;
	personal.state = obj.state_name;
	personal.city = obj.city;
	personal.zip = obj.customer_zip;
	personal.industry = obj.industry_name;
	personal.Blocation = obj.customer_city;
	personal.logo = obj.customer_logo;
	
	leadinfoView(personal , 'Customer', 'custInfoView');
	
	contact_prsn_list(obj.customer_id , "contact_prsn_list", "customer", "manager");
	obj.customer_remarks = window.atob(obj.customer_remarks);
	obj.customer_address = window.atob(obj.customer_address);
	$("#view_leadname").val(obj.customer_name);
	$('#view_customer').text(obj.customer_name);
	$("#label_product").html(obj.product);
	$("#label_leadsource").html(obj.leadsource);
	$("#view_ofcadd").html(obj.customer_address);
	$("#view_splcomments").html(obj.customer_remarks);
	$("#view_long").val(obj.leadlng);
	$("#view_latt").val(obj.leadlat);  
	$.ajax({
	type: "POST",
	url: "<?php echo site_url('manager_customerController/customFieldCustomer');?>",
	data:JSON.stringify(obj1),
	dataType:'json',
	success: function(data) {
		console.log(data)
		$("#custom_fields_view").empty();
		var rowdata=data.customerCustom, row='';
		var rowdata1=data.leadCustom, row1='';
		console.log(data)
		if(error_handler(data)){
			return;
		}
		if(rowdata.length>0){
			var j = 0;
			$("#custom_head_view").show();
			for(i=0;i<rowdata.length;i++){
				if(i==j){
					row += '<div class="row">';
					j = j + 2;					
					if(rowdata[i].attribute_type=="Single_Line_Text"){
						row += "<div class='col-md-3'><label><b>"+rowdata[i].attribute_name+"</b></label></div><div class='col-md-3'><label id='customer_custom'>"+rowdata[i].attribute_value+"<label></div>";
					}
					
				}else{
					if(rowdata[i].attribute_type=="Single_Line_Text"){
						row += "<div class='col-md-3'><label><b>"+rowdata[i].attribute_name+"</b></label></div><div class='col-md-3'><label id='customer_custom'>"+rowdata[i].attribute_value+"<label></div>";
					}
				}
				if(i == (j - 1)){
					row += '</div>';
				}
				
			}
			$("#custom_fields_view").append(row);
		}
		if(rowdata1.length>0){
			var h = 0;
			$("#custom_head_view").show();
			for(i=0;i<rowdata1.length;i++){
				if(rowdata1[i].attribute_type=="Single_Line_Text"){
					if(i==h){
						row1 += '<div class="row">';
						h = h + 2;					
						if(rowdata1[i].attribute_type=="Single_Line_Text"){
							row1 += "<div class='col-md-3'><label><b>"+rowdata1[i].attribute_name+"</b></label></div><div class='col-md-3'><label id='customer_custom'>"+rowdata1[i].attribute_value+"<label></div>";
						}
						
					}else{
						if(rowdata1[i].attribute_type=="Single_Line_Text"){
							row1 += "<div class='col-md-3'><label><b>"+rowdata1[i].attribute_name+"</b></label></div><div class='col-md-3'><label id='customer_custom'>"+rowdata1[i].attribute_value+"<label></div>";
						}
					}
					if(i == (h - 1)){
						row1 += '</div>';
					}
				}
			}
			$("#custom_fields_view").append(row1);
		}
	}
});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('manager_leadController/get_plugin_data');?>",
		dataType:'json',
		success: function(data) {
			if(error_handler(data)){
				return;
			}
			
			if(obj.hasOwnProperty("coordinate")== false){
				associated_plugin_used(data[0].plugin_id , "view" , ",")
			}else(
				associated_plugin_used(data[0].plugin_id , "view", obj.coordinate)
			)
			
		}
	});
    var addObj={};
    addObj.customer_id=$("#customer_id").val();
       $.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_customerController/getProductPurchaseInfo'); ?>",
            dataType:'json',
            data:JSON.stringify(addObj),
            success: function(data){ 
					console.log(data)
					purchase_data = data;
					if(error_handler(data)){
						return;
					}
					$(".prod_purchase_info").empty();
					var row = "";					
					for (i = data.length-1;i>=0; i--) {
						var rowdata1 = JSON.stringify(data[i]);
						row += "<div id='"+data[i].purchase_id+"' style='border: 1px solid #d6cece;padding: 3px;'><div class='row product_heading' style='text-align:center'>Purchase Order "+(i+1)+"<span class='edit_purchase'><a href='#' onclick='editselrow("+rowdata1+")'><span class='glyphicon glyphicon-pencil'></span></a></span></div><div class='row'><div class='col-md-2'><label>Product Owner</label></div><div class='col-md-4'>"+data[i].product_owner+"</div><div class='col-md-2'><label>Currency</label></div><div class='col-md-4'>"+data[i].currency_name+"</div></div><div class='row'><div class='col-md-2'><label>Reference Number</label></div><div class='col-md-4'>"+data[i].reference_number+"</div></div>";
						for(j=0;j<data[i].prod_data.length;j++){
							if(data.length-1==0){
								$(".prod_purchase_info").css("height", 184);
								$(".prod_purchase_info").css("margin-bottom", 8);
								$(".prod_purchase_info").css("overflow", "scroll");
							}
							if(data.length-1>0){
								$(".prod_purchase_info").css("height", 300);
								$(".prod_purchase_info").css("margin-bottom", 8);
								$(".prod_purchase_info").css("overflow", "scroll");
							}
							var rowdata = data[i].prod_data[j];
							var end_cmng_date,renewal_cmng_date;
							
							rowdata.purchase_end_date = $.trim(rowdata.purchase_end_date);
							rowdata.renewal_date = $.trim(rowdata.renewal_date);
								
							if(	rowdata.purchase_end_date == "0000-00-00 00:00:00" ||
								rowdata.purchase_end_date == "-" ||
								rowdata.purchase_end_date == "" ){
								end_cmng_date = '';
							}else{
								end_cmng_date=moment(rowdata.purchase_end_date).format('lll');
							}
							
							if(	rowdata.renewal_date == "0000-00-00 00:00:00" ||
								rowdata.renewal_date == "-" ||
								rowdata.renewal_date == "" ){
								renewal_cmng_date = '';
							}else{
								renewal_cmng_date=moment(rowdata.renewal_date).format('lll');
							}
							
							row +="<div class='row'><b>"+(j+1)+"."+rowdata.product_name+"</b></div><div class='row'><div class='col-md-2'><label>Quantity</label></div><div class='col-md-4'><label>"+rowdata.quantity+"</label></div><div class='col-md-2'><label>Cost</label></div><div class='col-md-4'><label>"+rowdata.amount+"</label></div></div><div class='row'><div class='col-md-2'><label>Start Date</label></div><div class='col-md-4'><label>"+moment(rowdata.purchase_start_date).format('lll')+"</label></div><div class='col-md-2'><label>End Date</label></div><div class='col-md-4'><label>"+end_cmng_date+"</label></div></div><div class='row'><div class='col-md-2'><label>Renewal Date</label></div><div class='col-md-4'><label>"+renewal_cmng_date+"</label></div>";
							if(rowdata.rate){
								row +="<div class='col-md-2'><label>Rate</label></div><div class='col-md-4'><label>"+rowdata.rate+"</label></div></div>";
							}else{
								row +="</div>";
							}if(rowdata.score!='' && rowdata.customer_code!=''){
								row +="<div class='row'><div class='col-md-2'><label>Score</label></div><div class='col-md-4'><label>"+rowdata.score+"</label></div><div class='col-md-2'><label>Customer Code</label></div><div class='col-md-4'><label>"+rowdata.customer_code+"</label></div></div>";
							}else if(rowdata.customer_code!=''){
								row +="<div class='row'><div class='col-md-2'><label>Customer Code</label></div><div class='col-md-4'><label>"+rowdata.customer_code+"</label></div></div>";
							}else if(rowdata.score!=''){
								row +="<div class='row'><div class='col-md-2'><label>Score</label></div><div class='col-md-4'><label>"+rowdata.score+"</label></div></div>";
							}if(rowdata.priority){
								row +="<div class='row'><div class='col-md-2'><label>Priority</label></div><div class='col-md-4'><label>"+rowdata.priority+"</label></div></div>";
							}	
						}								
						row +="</div>";
					}				
					$(".prod_purchase_info").append(row); 
            }
        });
}
/*--------------------------------------------------------------Customer View Ends-------------------------------------------------------*/
/*-----------------customer log Starts------------------------*/
function leadlog(){
	  var customer={};
	  customer.customerId=$("#customer_id").val();
	 $.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_customerController/getCustomerLogDetails');?>",
			data : JSON.stringify(customer),
			dataType:'json',
			success: function(data) {
				console.log(data)
				$("#logdetails").addClass("active").addClass("in");
				$("#logdetails_cust").addClass("active");
				$('#logdetails').empty();
				if(error_handler(data)){
                    return;
                }
				if(data.length > 0){
				var tolltip = "";
					
					var row = "";
					row += '<div class="row">';
					row += '<table class="table"><thead><tr>';
					row += '<th>Sl No</th><th>Activity Owner</th><th>Date-time</th><th>Activity</th><th>Ratings</th><th>Duration</th><th>Remarks</th><th>Status</th><th>Call Recorded</th>';
					row += '</tr></thead><tbody>';
					for(i=0; i < data.length; i++ ){
						if (data[i].note.length > 20){
							tolltip = "rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='left'  data-title='"+data[i].note+"'";
							data[i].note = data[i].note.substring(0,20) + ' ...';
						}
						var start=data[i].start;
						var end=data[i].end;
						var cal_duration = moment.duration(moment(end, 'YYYY/MM/DD HH:mm:ss').
						diff(moment(start, 'YYYY/MM/DD HH:mm:ss'))).asMilliseconds("");
						var duration = moment.utc(cal_duration).format("HH:mm:ss");
						var rowdata = JSON.stringify(data[i]);	
						var url_path = "<?php echo base_url(); ?>uploads/";
						var rating = "";
						for(a=1; a< 5; a++){
							if(data[i].rating != ""){
								if(a <= parseInt(data[i].rating)){
									rating += "<i class='fa fa-star' aria-hidden='true'></i>";
								}else{
									rating += "<i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>";
								}
							}else{
								rating += "<i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>";
							}
						}
						row += "<tr><td>" + (i+1) + "</td>"+
							"<td>" + data[i].rep_name +"</td>"+
							"<td>" + data[i].starttime +"</td>"+
							"<td>" + data[i].activity + "</td>"+
							"<td id='ff'>" + rating + "</td>"+
							"<td>" + duration +"</td>"+
							"<td class='tt'><span "+ tolltip +">" + data[i].note +"</span></td>"+
							"<td>" + data[i].status +"</td>";
						if(data[i].conntype == 'CALL594ce66d07b45' || data[i].conntype == 'CALL594ce66d07b46' || data[i].conntype == 'ME594ce66d07b9fd4'){
							if(data[i].hasOwnProperty('path')){
								if(data[i].path == null){
									row +=	"<td>No audio and sms attached</td>";
								}else{
									row +=	"<td><audio controls controlsList='nodownload'><source src="+url_path+data[i].path+">"+
									"Your browser does not support the audio element."+
									"</audio></td>";
								}
								
							}else{
								row +=	"<td>No audio and sms attached</td>";
							}
						}else{
							if(data[i].hasOwnProperty('path')){
								if(data[i].path == null){
									row +=	"<td>No audio and sms attached</td>";
								}else{
									row +=	"<td>"+data[i].path+"</td>";
								}										
							}else{
								row +=	"<td>No audio and sms attached</td>";
							}
						}
						row += "</tr>";
					}     
					row +='</tbody>';   						
					row +='</table>';   
					row +='</div>';   
					$('#logdetails').append(row); 
				}else{
					$('#logdetails').empty().append('<center><h4>No Customer History Found</h4></center>');
				}			
			}
		});
	 
	}
/*----------------------------------------------------------Customer log Ends---------------------------------------------------------------*/
/*----------------------------------------------------------Customer History Starts---------------------------------------------------------------*/
function hist_log(){
	  var customer={};
	  customer.customer_id=$("#customer_id").val();
	 $.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_customerController/oppDetailsCustomer');?>",
			data : JSON.stringify(customer),
			dataType:'json',
			success: function(data) {
				console.log(data)
				$('#history').empty();
				if(error_handler(data)){
                    return;
                }
				if(data.length > 0){
					var tolltip = "";
					var row = "";
					var url_path = "<?php echo base_url(); ?>uploads/";
					row += '<div class="row">';
					row += '<table class="table"><thead><tr>';
					row += '<th>Sl No</th><th>Customer Name</th><th>Date-time</th><th>Activity</th><th>Ratings</th><th>Duration</th><th>Remarks</th><th>Call Recorded</th>';
					row += '</tr></thead><tbody>';
					for(i=0; i < data.length; i++ ){ 
						if (data[i].note.length > 20){
							tolltip = "rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='left'  data-title='"+data[i].note+"'";
							data[i].note = data[i].note.substring(0,20) + ' ...';
						}
						var start=data[i].start;
						var end=data[i].end;
						var cal_duration = moment.duration(moment(end, 'YYYY/MM/DD HH:mm:ss').
						diff(moment(start, 'YYYY/MM/DD HH:mm:ss'))).asMilliseconds("");
						var duration = moment.utc(cal_duration).format("HH:mm:ss");
						var rowdata = JSON.stringify(data[i]);	
						
						var rating = "";
						for(a=1; a< 5; a++){
							if(data[i].rating != ""){
								if(a <= parseInt(data[i].rating)){
									rating += "<i class='fa fa-star' aria-hidden='true'></i>";
								}else{
									rating += "<i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>";
								}
							}else{
								rating += "<i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>";
							}
						}
						row += "<tr><td>" + (i+1) + "</td>"+
							"<td>" + data[i].customer_name +"</td>"+
							"<td>" + data[i].starttime +"</td>"+
							"<td>" + data[i].activity + "</td>"+
							"<td id='ff'>" + rating + "</td>"+
							"<td>" + duration +"</td>"+
							"<td class='tt'><span "+ tolltip +">" + data[i].note +"</span></td>";
							if(data[i].conntype == 'CALL594ce66d07b45' || data[i].conntype == 'CALL594ce66d07b46' || data[i].conntype == 'ME594ce66d07b9fd4'){
								if(data[i].hasOwnProperty('path')){
									if(data[i].path == null){
										row +=	"<td>No audio and sms attached</td>";
									}else{
										row +=	"<td><audio controls controlsList='nodownload'><source src="+url_path+data[i].path+">"+
										"Your browser does not support the audio element."+
										"</audio></td>";
									}
									
								}else{
									row +=	"<td>No audio and sms attached</td>";
								}
							}else{
								if(data[i].hasOwnProperty('path')){
									if(data[i].path == null){
										row +=	"<td>No audio and sms attached</td>";
									}else{
										row +=	"<td>"+data[i].path+"</td>";
									}										
								}else{
									row +=	"<td>No audio and sms attached</td>";
								}
							}
							row += "</tr>";
					}     
					row +='</tbody>';   						
					row +='</table>';   
					row +='</div>';   
					$('#history').append(row); 
				}else{
					$('#history').empty().append('<center><h4>No Customer History Found</h4></center>');
				}			
			}
		});
	 
	}
/*----------------------------------------------------------Customer History Ends---------------------------------------------------------------*/
/*----------------------------------------------------------Scheduled log Starts------------------------------------------------------------*/
	function schedule_fetch(){
		var customer={};
		customer.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	  	customer.customerId=$("#customer_id").val();
	  	
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_customerController/getScheduleTask'); ?>",
			data : JSON.stringify(customer),
			dataType:'json',
			success: function(data) {
				console.log(data)
				$('#logdetails1').empty();
				if(error_handler(data)){
                    	return;
                }
				if(data.length > 0){
					var row = "";
					row += '<div class="row">';
					row += '<table class="table"><thead><tr>';
					row += '<th>#</th><th>Activity Owner</th><th>Start-Date</th><th>Duration</th><th>Activity</th><th>Asset</th><th>Remarks</th>';
					row += '</tr></thead><tbody>';
					for(i=0; i < data.length; i++ ){ 
					var tolltip = "";
					if (data[i].remarks.length > 50){
						data[i].remarks = data[i].remarks.substring(0,20) + ' ...';
						tolltip = "rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='left'  data-title='"+data[i].remarks+"'";
					} 
						var rowdata = JSON.stringify(data[i]);	
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].activity_owner +"</td><td>" + data[i].start_date +"</td><td>" + data[i].duration + "</td><td>" + data[i].activity +"</td><td>" + data[i].status +"</td><td style='width: 27%'><p "+tolltip+">"+ data[i].remarks +"</p></td></tr>";
					} 
					row +='</tbody>';   						
					row +='</table>';   
					row +='</div>';   
					$('#logdetails1').append(row); 
				}else{
					$('#logdetails1').empty().append('<center><h4>No Activities Scheduled</h4></center>');
				}
			}
		});	
	}
/*----------------------------------------------------------Scheduled log Ends---------------------------------------------------------------*/
/*----------------------------------------------------------Opportunity log Starts---------------------------------------------------------------*/
	function opp_log(){
		var customer={};
		customer.customerId=$("#customer_id").val();
			 $.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_customerController/getCustomerOppDetails');?>",
			data : JSON.stringify(customer),
			dataType:'json',
			success: function(data) {
				$('#opp_details1').empty();
				if(error_handler(data)){
                    return;
                }
				if(data.length > 0){
					var row = "";
					row += '<div class="row">';
					row += '<table class="table"><thead><tr>';
					row += '<th>Sl No</th><th>Name</th><th>Product</th><th>Sales Stage</th>	<th>Expected Close Date</th><th>Stage Owner</th>';
					row += '</tr></thead><tbody>';
					for(i=0; i < data.length; i++ ){						
						var rowdata = JSON.stringify(data[i]);
						var url = "<?php echo site_url("manager_opportunitiesController/stage_view/")?>"+data[i].opportunity_id;						 
						row += "<tr><td>" + (i+1) + "</td><td><a href='"+url+"'>" + data[i].opportunity_name +"</a></td><td>" + data[i].opportunity_product +"</td><td>" + data[i].stage_name+ "</td><td>"+data[i].opportunity_date +"</td><td>"+data[i].stage_owner +"</td></tr>";			
					}
					row +='</tbody>';   						
					row +='</table>';   
					row +='</div>';					
					$('#opp_details1').empty().append(row);
				}else{
					$('#opp_details1').append('<center><h4>No Opportunity Created</h4></center>');
				}
			}
		});
	}

/*----------------------------------------------------------Opportunity log Ends---------------------------------------------------------------*/
/*----------------------------------------------------------Customer History Starts------------------------------------------------------------*/
function hide_lead_histry(){
	$('#lead_hist').modal('hide');
	$('html').addClass('modal-open');
	
}
function lead_history(){
    $('#lead_hist').modal('show');
    var cust_histid={};
    cust_histid.id=$('#customer_id').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('manager_customerController/get_customerhistory'); ?>",
        data : JSON.stringify(cust_histid),
        dataType:'json',
        success: function(data) {
			console.log(data)
            if(error_handler(data)){
                    return;
            }
            var history=data.history;
            if(history){		
				$('#history_min').empty();
				var mapping_ids = [];
				for (var i = 0; i < history.length; i++){
					if (mapping_ids.indexOf(history[i].mapping_id) < 0){
						mapping_ids.push(history[i].mapping_id);		
						var action = history[i].action;
						var from_name = history[i].from_user_name;
						var to_name = history[i].to_user_name;
						var lead_cust_name = history[i].lead_cust_name;				
						var remarks = history[i].remarks;
						var timestamp = moment(history[i].timestamp).format('LL') +" at "+ moment(history[i].timestamp).format('LT'); 
						var rowhtml = '';
						if (action == 'created') {

								rowhtml += '<div class="created"><div><b><h3 style="display:inline;">'+capitalizeFirstLetter(action)+'</h3></b>by <u><b>' + from_name + '</b></u> (<i>' + capitalizeFirstLetter(data.history[i].module)+ '</i>)  for '+ lead_cust_name +'</div>' ;
								rowhtml += 'on <h5 style="display:inline;color:#777777">' + timestamp + '</h5></div>';
						} 
						else if (action == 'accepted'){
								rowhtml += '<div class="created"><div><b><h3 style="display:inline;">'+capitalizeFirstLetter(action)+'</h3></b>by <u><b>' + to_name + '</b></u> (<i>' + capitalizeFirstLetter(data.history[i].module) + '</i>)  for '+ lead_cust_name +'</div>';
								rowhtml += 'on <h5 style="display:inline;color:#777777">' + timestamp + '</h5></div>';
						} 
						else if ((action == 'assigned') || (action == 'reassigned')){
										/* get count of this mapping ID in array. */
										assigned_to = 0;
										assigned_to_names = [];
										for(var c = 0; c < history.length; c++)	{
												if (history[c].mapping_id == history[i].mapping_id) {
												assigned_to++;
												assigned_to_names.push(history[c].to_user_name);
												}
										}

								if(assigned_to > 1)	{
										to_name = assigned_to + " users";
								}
								rowhtml = '<div class="assigned"> <div><b><h3 style="display:inline;">'+capitalizeFirstLetter(action)+'</h3></b>to <u><b>'+ to_name + '</b></u> (<i>' + capitalizeFirstLetter(data.history[i].module) + '</i>) </div>';
								rowhtml += 'on <h5 style="display:inline;color:#777777">' + timestamp + '</h5></div>';

						}
						else if (action == 'added remarks')	{
								rowhtml = '<div class="remarks"><div><b><h3 style="display:inline;">'+capitalizeFirstLetter(action)+'</h3></b>by <u><b>' + from_name + '</b></u> (<i>' + capitalizeFirstLetter(data.history[i].module) + '</i>) </div>';
								rowhtml += 'on <h5 style="display:inline;color:#777777">' + timestamp + '</h5></div>';
						} 	
						console.log(rowhtml)
						row =   '<tr><td>'+ rowhtml + '</td></tr>';
						$('#history_min').append(row);	
					}								
				}
			}	
			/* Log Details................ */
			var historyDetail = data.history_detail;
			var detail="";
			$('#history_detail').empty();
			detail += '<thead><tr><th>Action</th><th>Performed By</th><th>In module</th><th>Assigned To</th><th>In module</th><th>Date-Time</th><th>Remarks</th></tr></thead>'
			if(historyDetail.length > 0){
				detail += '<tbody>'
				for(j =0; j< historyDetail.length; j++){
					
					var action = historyDetail[j].action;
					var from_name = historyDetail[j].from_user_name;
					var to_name = historyDetail[j].to_user_name;
					var remarks = historyDetail[j].remarks;
					var tolltip = "";
					if(remarks != null){
						if (remarks.length > 20){
							remarks = remarks.substring(0,20) + ' ...';
							tolltip = "rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='left'  data-title='"+historyDetail[j].remarks+"'";
						}
					}else{
						remarks = "";
					}
													
					var lead_cust_name = historyDetail[j].lead_cust_name;				
					var timestamp = moment(historyDetail[j].timestamp).format('LL') +" at "+ moment(historyDetail[j].timestamp).format('LT');
					
					
					if(historyDetail[j]. module == null){
						module = ""
					}else{
						module = historyDetail[j]. module ;
					}
					
					
					if (action == 'created') {
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td> - </td><td> - </td><td>'+timestamp+'</td><td  class="no_opacity_tooltip" ><p '+tolltip+'>'+remarks +'</p></td></tr>';
					}
					else if ((action == 'assigned') || (action == 'reassigned')){
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td> manager </td><td>'+to_name+'</td><td>'+module+'</td><td>'+timestamp+'</td><td  class="no_opacity_tooltip"><p '+tolltip+'>'+remarks +'</p></td></tr>';
					}
					else if (action == 'rejected'){
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td>'+to_name+'</td><td> manager </td><td>'+timestamp+'</td><td><p '+tolltip+'>'+remarks +'</p></td></tr>';
					} 
					else if (action == 'edited'){
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td> - </td><td> - </td><td>'+timestamp+'</td><td  class="no_opacity_tooltip"><p '+tolltip+'>'+remarks +'</p></td></tr>';
					} 
					else if (action == 'closed'){
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td> - </td><td> - </td><td>'+timestamp+'</td><td  class="no_opacity_tooltip"><p '+tolltip+'>'+remarks +'</p></td></tr>';
					} 
					else if (action == "in progress"){
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td> - </td><td> - </td><td>'+timestamp+'</td><td  class="no_opacity_tooltip"><p '+tolltip+'>'+remarks +'</p></td></tr>';
					} 
					else if (action == "rejected"){
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td> - </td><td> - </td><td>'+timestamp+'</td><td  class="no_opacity_tooltip"><p '+tolltip+'>'+remarks +'</p></td></tr>';
					} 
					else if (action == "reopened"){
						detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td> - </td><td> - </td><td>'+timestamp+'</td><td  class="no_opacity_tooltip"><p '+tolltip+'>'+remarks +'</p></td></tr>';
					} 
					
				}
				
				detail += '</tbody>';
				$('#history_detail').append(detail);
			}
					
		}		
	});	
}
/*----------------------------------------------------------Customer History Ends------------------------------------------------------------*/
</script>
<style>
	audio{
		width: 225px;
	}
	.no_opacity_tooltip .tooltip.in .tooltip-inner{
		text-align:left;
	}
	.no_opacity_tooltip .tooltip.in{
		opacity: 1;
		padding:5px;
		background:  #ccc;		
	}
	#fetch_style{
		margin-bottom:5px!important;
	}
</style>