<style>
audio{
	width: 225px;
}
</style>
<div id="leadview" class="modal fade" data-backdrop="static"  data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<input type="hidden" id="lead_id"/>
			<form id="viewpopup" class="form" action="#" method="post" >
				<div class="modal-header">                                       
					<span class="close" onclick="cancel1()">x</span>
					<!--<span onclick="lead_history()" class="fa fa-history pull-right" style="font-size: 20px;margin-right: 20px;" title="Click to view Lead history"></span> --> 
					<h4 class="modal-title">View <span id="view_lead"></span>
					
					</h4>                                       
				</div>
				<div class="modal-body">
					<div id='leadInfoView'></div>
					
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
							<textarea class="pre form-control" id="view_ofcadd" disabled></textarea> 
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<textarea class="pre form-control" id="view_splcomments" disabled></textarea> 
						</div>
					</div>
					
					<div id="view_map_render">
						<div class="row" >
							<div class="col-md-12 lead_address">
								<center><b>Google Map</b></center>
							</div>
						</div>
						<input type="hidden" id="view_latt">
						<input type="hidden" id="view_long">
						<div class="row" id="view_map2" >
							<div class="row" id="view_maploc" style="width:100% px;height:150px;border:1px;"></div>
						</div>
					</div>
					
					<div class="row" >
						<div class="col-md-12 lead_address">
							<center><b>Lead Contact Person Information</b></center>
						</div>
					</div>
					<div class="row" id="contact_prsn_list">
						
					</div>
					<!--------------------------
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-4 apport_label">
									<label for="view_firstcontact">Contact Person</label> 
								</div>
								<div class="col-md-4">
									<label id="lead_firstcontact"></label> 
								</div>                                    
							</div>
							<div class="row">
								<div class="col-md-4 apport_label">
									<label for="view_designation">Designation</label> 
								</div>
								<div class="col-md-4">
									<label id="label_designation"></label> 
								</div>                                   
							</div>
							<div class="row">
								<div class="col-md-4 apport_label">
									<label for="view_primmobile">Mobile Number 1</label> 
								</div>
								<div class="col-md-4">
									<label id="label_primmobile"></label> 
								</div>                                   
							</div>
							<div class="row">
								<div class="col-md-4 apport_label">
									<label for="view_primmobile2">Mobile Number 2</label> 
								</div>
								<div class="col-md-4">
									 <label id="label_primmobile2"></label> 
								</div>                                   
							</div>
							<div class="row">
								<div class="col-md-4 apport_label">
									<label for="view_primemail">Email 1</label> 
								</div>
								<div class="col-md-4">
									 <label id="label_primemail"></label> 
								</div>                                   
							</div>
							<div class="row">
								<div class="col-md-4 apport_label">
									<label for="view_contacttype">Buyer Persona</label> 
								</div>
								<div class="col-md-4">
									<label id="label_contacttype"></label> 
									
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
							<div class="col-md-2 apport_label">
								<label for="view_displaypic">Photo</label> 
							</div>
							<div class="col-md-4">
								 
							</div>
							</div>
							<div class="row">
								<div class="col-md-4 apport_label">
									<label for="view_primemai2">Email 2</label> 
								</div>
								<div class="col-md-4">
									<label id="label_primemail2"></label> 
								</div>                                  
							</div>										
						</div>
						<div class="col-md-12">
							<div class="col-md-2 apport_label">
								<label>Address</label> 
							</div>
							<div class="col-md-10">
								 <textarea disabled class="pre form-control"  id="contact_person_address" ></textarea>
							</div>
						</div>
					</div>
					---------------------------->
					<br>
					<input type="hidden" id="logg">
					<div class="row" id="activity_tab_view">
						<ul class="nav nav-tabs">
							<li onclick="schedule_fetch();" id="scheduled_activity_li"><a data-toggle="tab" href="#logdetails1"><h4>Scheduled Activity</h4></a></li>
							<li onclick="oppo_fetch();"><a data-toggle="tab" href="#opp_details1"><h4>Opportunity Details</h4></a></li>
							<li class="active" onclick="leadfetch();"><a data-toggle="tab" href="#logdetails"><h4>Lead History</h4></a></li>
						</ul>
						<div class="tab-content">
							<div id="logdetails1" class="tab-pane fade">
							</div>
							<!------------------------------------------------------>
							<div id="opp_details1" class="tab-pane fade">
							</div>
							<!------------------------------------------------------>
							<div id="logdetails" class="tab-pane fade in active">
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
				</div>
				<div class="modal-footer">
					
					<span class="pull-left" >
						<span onclick="lead_history()" class="btn"  title="Click to view Audit Trail">Audit Trail</span>
						<span  id="CloseLeadBtn" onclick="check_opportunity()" class="btn hide"  title="Close Lead">Close Lead</span>
					</span>
					
					<input type="button" class="btn"  id="manager_lead_save" onclick="assign_btn2()" />
					<input type="button" class="btn lead_recieved_btn none"  onclick="reject_btn('single')" value="Reject">
					<input type="button" class="btn btn-default" onclick="cancel1()" value="Cancel">
				</div>
			</div>
		</form>
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


<script>
	
	function viewrow(obj,assing_team_lead){
		var obj1 = {};
		obj1.leadid=obj.leadid;
		
		var page = window.location.href.split('/'); //taking page from URL
		var allowCloseLead = ["assignedLeads_view"]; //Giving permission to only lead >> Assign page for close lead 
		allowCloseLead.forEach(function(elm){
			if(page[(page.length) - 1] == elm){
				$("#CloseLeadBtn").removeClass('hide');
				if($("#second_tab_li").hasClass('active')){
					//not showing for team lead tab
					$("#CloseLeadBtn").addClass('hide');
				}
			}
		})
		
		
		/* 
		obj.repremarks = window.atob(obj.repremarks);
		obj.leadtaddress = window.atob(obj.leadtaddress);
		obj.contPrsnAdd = window.atob(obj.contPrsnAdd);
		 */
		obj.repremarks = window.atob(unescape(decodeURIComponent(obj.repremarks)));
		obj.leadtaddress = window.atob(unescape(decodeURIComponent(obj.leadtaddress)));
		obj.contPrsnAdd = window.atob(unescape(decodeURIComponent(obj.contPrsnAdd)));
		
		$("#manager_lead_save").val($("#manager_lead").val());	
		
		if(typeof selectedSection != "undefined"){
			if(selectedSection == "teamLeads"){
				$("#manager_lead_save").hide();
			}else{
				$("#manager_lead_save").show();
			}
		}
		
		if($("#manager_lead").val()== "Reassign_not_required"){
			$("#manager_lead_save").hide();
		}
		if(assing_team_lead == "Reassign_not_required"){
			$("#manager_lead_save").hide();
		}
		
		
		
		$('.custom-file-upload').find('i').remove();
		
		var personal={};
		personal.name = obj.leadname;
		personal.email = obj.leademail;
		personal.phone = obj.leadphone;
		personal.website = obj.leadwebsite;
		personal.country = obj.countryname;
		personal.state = obj.statename;
		personal.city = obj.city;
		personal.zip = obj.zipcode;
		personal.industry = obj.industry_name;
		personal.Blocation = obj.business_location_name;
		personal.logo = obj.leadlogo;
		personal.logo = obj.leadlogo;
		personal.source = obj.leadsource;
		
		$("#view_ofcadd").val(obj.leadtaddress);
		$("#view_splcomments").val(obj.repremarks);
		$("#contact_person_address").val(obj.contPrsnAdd);
		$("#label_designation").html(obj.employeedesg);
		$("#label_primmobile").html(obj.employeephone1);
		$("#label_primmobile2").html(obj.employeephone2);
		$("#label_primemail").html(obj.employeeemail);
		$("#label_primemail2").html(obj.employeeemail2);
		$("#lead_firstcontact").html(obj.employeename);
		$("#label_contacttype").html(obj.contactype);/* obj.contacttypeid */
		if(obj.coordinate != ","){
			$("#view_map_render").show();
		}else{
			$("#view_map_render").hide();
		}
		
		
		contact_prsn_list(obj1.leadid, "contact_prsn_list" ,"lead","manager");
		
		$("#view_lead").html(obj.leadname);
		$("#logg").val(obj.leadid);
		/* viewloc(); */
		
		var id=obj.leadid;
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/customFieldLead');?>",
			data:JSON.stringify(obj1),
			dataType:'json',
			success: function(data) {
				$("#custom_fields_view").empty();
				var rowdata1=data.leadCustom, row1='';
				if(error_handler(data)){
					return;
				}				
				if(rowdata1.length>0){
					$("#custom_head_view").show();
					for(i=0;i<rowdata1.length;i++){
						if(rowdata1[i].attribute_type=="Single_Line_Text"){
							$("#custom_fields_view").append("<div class='col-md-2'><label><b>"+rowdata1[i].attribute_name+"</b></label></div><div class='col-md-4'><label id='customer_custom_lead'>"+rowdata1[i].attribute_value+"<label></div>");
						}
					}			
				}
			},
			error:function(data){
				network_err_alert();
			}
		});
		$.ajax({
				type: "POST",
				url: "<?php echo site_url('manager_leadController/product_views'); ?>",
				data : "id="+id,
				dataType:'json',
				success: function(data) {
					if(error_handler(data)){
						return;
					}
					personal.product = data;
					leadinfoView(personal , 'Lead', 'leadInfoView');
					
				},
				error:function(data){
					network_err_alert();
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
				
			},
			error:function(data){
				network_err_alert();
			}
		});	
		if($("#manager_lead").val() == "Accept"){
			$(".lead_recieved_btn").show();			
		}
		
		$("#activity_tab_view ul li").each(function(){
			$(this).removeClass("active")
		})
		
		$("#activity_tab_view .tab-content .tab-pane.fade").each(function(){
			$(this).removeClass("active").removeClass("in")
		})
		schedule_fetch();
		
	}
	
	
	
	function hide_lead_histry(){
		$('#lead_hist').modal('hide');
		$('html').addClass('modal-open');
		
	}
	function lead_history(){
		/*$("#leadview").modal("hide");*/
		$('#lead_hist').modal('show');
		var lead_histid={};
		lead_histid.id=$('#logg').val();

		$.ajax({
				type: "POST",
				url: "<?php echo site_url('manager_leadController/fetch_leadhistory'); ?>",
				data : JSON.stringify(lead_histid),
				dataType:'json',
				success: function(data) {
					if(error_handler(data)){
						return;
					}
										
					var history=data.history;
					/* Log summery................ */
					if(history){		
						$('#history_min').empty();
						var mapping_ids = [];
						for (var i = 0; i < history.length; i++) {
							if (mapping_ids.indexOf(history[i].mapping_id) < 0) {
								mapping_ids.push(history[i].mapping_id);		
								var action = history[i].action;
								var from_name = history[i].from_user_name;
								var to_name = history[i].to_user_name;
								var lead_cust_name = history[i].lead_cust_name;				
								var remarks = history[i].remarks;
								var timestamp = moment(history[i].timestamp).format('LL') +" at "+ moment(history[i].timestamp).format('LT'); 
								
								if(capitalizeFirstLetter(data.history[i].module) == 'Sales'){
									data.history[i].module = 'Executive'
								}
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
									tolltip = "rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='left'  data-title='"+historyDetail[j].remarks+"'";
									remarks = remarks.substring(0,20) + ' ...';
								}
							}else{
								remarks = "-";
							}
															
							var lead_cust_name = historyDetail[j].lead_cust_name;				
							var timestamp = moment(historyDetail[j].timestamp).format('LL') +" at "+ moment(historyDetail[j].timestamp).format('LT');
							
							
							if(historyDetail[j].module == null){
								module = ""
							}else if(capitalizeFirstLetter(historyDetail[j].module) == 'Sales'){
								module = 'Executive'
							}else{
								module = historyDetail[j].module ;
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
							/* else if (action == "rejected"){
								detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td> - </td><td> - </td><td>'+timestamp+'</td><td  class="no_opacity_tooltip"><p '+tolltip+'>'+remarks +'</p></td></tr>';
							} */ 
							else if (action == "reopened"){
								detail += '<tr><td>'+action+'</td><td>'+from_name+'</td><td>'+module+'</td><td> - </td><td> - </td><td>'+timestamp+'</td><td  class="no_opacity_tooltip"><p '+tolltip+'>'+remarks +'</p></td></tr>';
							} 
							
						}
						
						detail += '</tbody>';
						$('#history_detail').append(detail);
					}
					
				},
				error:function(data){
					network_err_alert();
				}		
		});	
	}

/* ------------------------ view lead log -------------------------------------------- */	
	function leadfetch(){
		var view_leadid={};
		view_leadid.id=$("#logg").val();
			$.ajax({
					type: "POST",
					url: "<?php echo site_url('manager_leadController/fetch_unAssignedLog'); ?>",
					data : JSON.stringify(view_leadid),
					dataType:'json',
					success: function(data) {
						if(error_handler(data)){
							return;
						}
						
						
						if(data.length > 0){
							var row = "";
							var url_path = "<?php echo base_url(); ?>uploads/";
							/* row += '<div class="col-md-12 lead_view">';
							row += '<div class="col-md-12 col-sm-12 col-xs-12">';
							row += '<center><b>Lead Log</b></center>';
							row += '</div>';
							row += '</div>'; */
							row += '<table class="table" id="tablelog" style="margin-top:5px">';
							row += '<thead>';
							row += '<tr>';	
							row += '<th>#</th><th>Activity Owner</th><th>Activity Start Date</th><th>Activity End Date</th><th>Ratings</th>';
							row += '<th>Duration</th><th>Activity</th><th>Status</th><th>Remarks</th><th>Content</th>';
							row += '</tr>';
							row += '</thead>';  
							row += '<tbody>';
							
							for(i=0; i < data.length; i++ ){ 
								
								var tolltip = "";
								if (data[i].note.length > 20){
									tolltip = "rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='left'  data-title='"+data[i].note+"'";
									data[i].note = data[i].note.substring(0,20) + ' ...';
								}
								
								var now  = data[i].start;
								var then = data[i].end;
								var cal_duration = moment.duration(moment(then, 'YYYY/MM/DD HH:mm:ss').
									diff(moment(now, 'YYYY/MM/DD HH:mm:ss'))).asMilliseconds("");
								var duration = moment.utc(cal_duration).format("HH:mm:ss");
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
								row += "<tr>"+
										"<td>" + (i+1) + "</td>"+
										"<td>" + data[i].rep_name +"</td>"+
										"<td>" + moment(data[i].start).format('lll') +"</td>"+
										"<td>" + moment(data[i].end).format('lll') + "</td>"+
										"<td class='star_rating'>"+ rating +"</td>"+
										"<td>" + duration +"</td>"+
										"<td>" + data[i].status +"</td>"+
										"<td>" + data[i].type +"</td>"+
										"<td  class='no_opacity_tooltip'><p "+tolltip+">"+ data[i].note +"</p></td>";
										if(data[i].conntype == 'CALL594ce66d07b45' || data[i].conntype == 'CALL594ce66d07b46' || data[i].conntype == 'ME594ce66d07b9fd4'){
											if(data[i].hasOwnProperty('path') || data[i].path != null){
												row +=	"<td><audio controls controlsList='nodownload'><source src="+url_path+data[i].path+">"+
													"Your browser does not support the audio element."+
													"</audio></td>";
											}else{
												row +=	"<td></td>";
											}
										}else{
											if(data[i].hasOwnProperty('path') || data[i].path != null){
												row +=	"<td>"+data[i].path+"</td>";
																					
											}else{
												row +=	"<td></td>";
											}
										}							
										row += "</tr>";
							}
							row += '</tbody>';    
							row += '</table>'; 
							$('#logdetails').empty().append(row);  
						}else{
							$('#logdetails').empty().append('<center><h4>No Activities Logged</h4></center>'); 
						}
					},
					error:function(data){
						network_err_alert();
					}
			});
 
	}
	
/* -----------------------View opportunity activity-------------------------------- */	
	function oppo_fetch(){
		var view_oppid={};
		view_oppid.id=$("#logg").val();
			$.ajax({
					type: "POST",
					url: "<?php echo site_url('manager_leadController/fetch_opportunity'); ?>",
					data : JSON.stringify(view_oppid),
					dataType:'json',
					success: function(data) {
						if(error_handler(data)){
							return;
						}
						
						if(data.length > 0){
							var row = "";
							/* row +='<div class="col-md-12 lead_view">';
							row +='<div class="col-md-12 col-sm-12 col-xs-12">';
							row +='<center><b>Opportunity Details</b></center>';
							row +='</div>';
							row +='</div>'; */
							row +='<table class="table" id="opp_log1" style="margin-top:5px">';
							row +='<thead>';
							row +='<tr>';	
							/* opp_name,lead_name,product,stage_name,value/amount,stage_owner */
							row +='<th>#</th><th>Opportunity Name</th><th>Product</th><th>Stage Name</th><th>Value/Amount</th><th>Stage Owner</th>';	
							row +='</tr>';
							row +='</thead>'; 
							row +='<tbody">';
							for(i=0; i < data.length; i++ ){ 
								var url = "<?php echo site_url("manager_opportunitiesController/stage_view/")?>"+data[i].opportunity_id+"/unassigned";
								
								row += "<tr>"+
											"<td>" + (i+1) + "</td>"+
											"<td><a href='"+url+"'>" + data[i].opportunity_name +"</a></td>"+
											"<td>" + data[i].opportunity_product +"</td>"+
											"<td>" + data[i].stage_name +"</td>"+
											"<td></td>"+
											"<td>"+data[i].stage_owner +"</td>"+
										"</tr>";
								
							}
							row +='</tbody>'    
							row +='</table>' 
							$('#opp_details1').empty().append(row);  
						}else{
							$('#opp_details1').empty().append('<center><h4>No Opportunity Created</h4></center>'); 
						}
					},
					error:function(data){
						network_err_alert();
					}
			});	
	}
/* -----------------------View schedule activity-------------------------------- */
	function schedule_fetch(){
		var view_leadid={};
		view_leadid.id=$("#logg").val();
			$.ajax({
					type: "POST",
					url: "<?php echo site_url('manager_leadController/logs_schedule'); ?>",
					data : JSON.stringify(view_leadid),
					dataType:'json',
					success: function(data) {
						if(error_handler(data)){
							return;
						}
						$("#logdetails1").addClass("active").addClass("in");						
						$("#scheduled_activity_li").addClass("active");
						if(data.length > 0){
							var row ="";
							/* row +='<div class="col-md-12 lead_view">';
							row +='<div class="col-md-12 col-sm-12 col-xs-12">';
							row +='<center><b>Scheduled Activities</b></center>';
							row +='</div>';
							row +='</div>'; */
							row +='<table class="table" id="tablelog1" style="margin-top:5px">';
							row +='<thead><tr>';
							row +='<th>#</th width="1%"><th width="15%">Activity Owner</th>';
							row +='<th width="17%">Start-Date</th><th width="17%">End-Date</th><th width="10%">Duration</th><th width="10%">Activity</th>';
							row +='<th width="20%">Remarks</th><th width="10%">Satus</th>';
							row +='</tr></thead>';
							row +='<tbody>';
							for(i=0; i < data.length; i++ ){ 
								var tolltip = "";
								if (data[i].remarks.length > 20){
									tolltip = "rel='tooltip' data-toggle='tooltip' data-trigger='hover' data-placement='left'  data-title='"+data[i].remarks+"'";
									data[i].remarks = data[i].remarks.substring(0,20) + ' ...';
								}
								
								var cal_duration = 	moment.duration(moment(data[i].meeting_end, 'YYYY/MM/DD HH:mm:ss').
																	diff(moment(data[i].meeting_start, 'YYYY/MM/DD HH:mm:ss'))).asMilliseconds("");
								var duration = moment.utc(cal_duration).format("HH:mm:ss");
								
								if(data[i].status == 'pending'){
									var color="style='color:red'";
								}
								var rowdata = JSON.stringify(data[i]);							
								row += "<tr>"+
											"<td>" + (i+1) + "</td>"+
											"<td>" + data[i].user_name +"</td>"+
											"<td>" + moment(data[i].meeting_start).format('lll') +"</td>"+
											"<td>" + moment(data[i].meeting_end).format('lll') +"</td>"+
											"<td>" + duration +"</td><td>" + data[i].activity +"</td>"+
											"<td class='no_opacity_tooltip'>"+
												"<p "+tolltip+">"+ data[i].remarks +"</p>"+
											"</td>"+
											"<td "+color+">"+ data[i].status +"</td>"+
										"</tr>";
								
							}
							row +='</tbody>';   						
							row +='</table>';   						
							$('#logdetails1').empty().append(row);  
						}else{
							$('#logdetails1').empty().append('<center><h4>No Activities Scheduled</h4></center>');
						}
					},
					error:function(data){
						network_err_alert();
					}
			});				
	}
</script>
<!---------------Requirement added 09/08/2018 for close lead ---------------->
		<div id="closed_opp" class="modal fade" data-backdrop="static" >
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<span class="close" onclick="cancel_opp()" >x</span>	
						<h4 class="modal-title">Choose Loss Type</h4>
					</div>				
					<div class="modal-body">						
						<div class="row">
							<div class="col-md-4">
								<input type="radio" name="radio1"  id="won" value="1"/> <label for="won">Permanent</label> 
							</div>
							<div class="col-md-4">
								<input type="radio" name="radio1" id="lost" value="0"/> <label for="lost">Temporary</label> 
							</div>
							<div class="col-md-4">
								<input type="checkbox" name="lead_activity" id="lead_activity"/> <label for="lead_activity">Close all future activities</label> 
							</div>
							<span class="error-alert"></span>
						</div><br/>
						<div class="row none" id="lost_id">		
							<div class="col-md-6">
								<input type="radio"  checked name="Temporary" value="1" id="Temporary"/> <label for="Temporary">Push to Myself</label>
							</div>
							<!--<div class="col-md-6">
								<input title="We will Update soon."type="radio" disabled="disabled" name="Permanent" value="0" id="Permanent"/> <label for="Permanent">Push to Sales Mil</label>
							</div>-->
							<span class="error-alert"></span>
						</div>
						<div class="row none" id="task_title">
							<div class="col-md-3">										
								<span>Title*</span>
							</div>
							<div class="col-md-9">
								<input type="text" id="activity_title" class="form-control" >
								<span class="error-alert"></span>							
							</div>
						</div>
						<div class="row none" id="future_activity">
							<div class="col-md-3">										
								<span>Future Activity*</span>
							</div>
							<div class="col-md-9">
								<select  class="form-control"></select>
								<span class="error-alert"></span>							
							</div>
						</div>
						<div class="row none" id="Temp_date">
							<div class="col-md-3">										
									<span>Remind Me On*</span>
							</div>
							<div class="col-md-9">
								<div class="form-group">
									<div class='input-group date'>
										<input type='text' class="form-control" placeholder="DD-MM-YYYY" id="tempdate" readonly />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<span class="error-alert"></span>
								</div>							
							</div>
						</div>
						<div class="row none" id="act_duration">
							<div class="col-md-3">										
									<span>Activity duration*</span>
							</div>
							<div class="col-md-9">
								<input type="text" id="activity_duration" class="form-control" placeholder="HH:MM"  maxlength="5">									
								<span class="error-alert"></span>							
							</div>
						</div>
						<div class="row none" id="reminder">
							<div class="col-md-3">										
								<span>Alert Before*</span>
							</div>
							<div class="col-md-9">
								<select class="form-control" id="reminder_time">
									<option value="">Select</option>
									<option value="5">5 mins</option>
									<option value="15">15 mins</option>
									<option value="30">30 mins</option>
								</select>
							<span class="error-alert"></span>						
							</div>
						</div>
						<div class="row none" id="contact_details">
							<div class="col-md-3">										
								<span>Contact Type*</span>
							</div>
							<div class="col-md-9">
								<select  class="form-control"></select>
								<span class="error-alert"></span>							
							</div>
						</div>
						<div class="row none" id="remarks">	
							<div class="col-md-3">
								<label>Remarks*</label>
							</div>
							<div class="col-md-9">
								<textarea name="Remarks" class="form-control" id="won_remarks"></textarea> 
								<span class="error-alert"></span>
							</div>
						</div>
						<div class="row none" id="remarks1">
							<div class="col-md-3">
								<label>Remarks*</label>
							</div>	
							<div class="col-md-9">
								<textarea name="Remarks" class="form-control" id="lost_remarks"></textarea> 
								<span class="error-alert"></span>
							</div>
						</div>
						<div class="row none" id="remarks2">	
							<div class="col-md-3">
								<label>Remarks*</label>
							</div>
							<div class="col-md-9">
								<textarea name="Remarks" class="form-control" id="temp_remarks"></textarea> 
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn" onclick="add1()" value="Close">
						<input type="button" class="btn" onclick="cancel_opp()" value="Cancel" >
					</div>
				</div>
			</div>
		</div>
<script>
	
	function add1(){
		$(".error-alert").text("")
		if($('#lead_activity').is(":checked")){
			var activity=1;
		}else{
		   var activity=0;
		}
		if($("#won").prop("checked")== false && $("#lost").prop("checked")== false ){
			$("#won").closest(".row").find(".error-alert").text("Select Temporary or Permanent.");
			return;
		}else{
			$("#won").closest(".row").find(".error-alert").text("");
		}
		if($("#lost").prop("checked")== true){                    
			/* if($("#Permanent").prop("checked")== false && $("#Temporary").prop("checked")== false ){ */
			if($("#Temporary").prop("checked")== false){                        
				$("#Temporary").closest(".row").find(".error-alert").text("Select loss type.");
				return;
			}else{
				$("#Temporary").closest(".row").find(".error-alert").text("");
			}
				   
			if($("#Permanent").prop("checked")== true){
				if($("#lost_remarks").val()==""){
					$("#lost_remarks").closest("div").find(".error-alert").text("Remarks is required.");
					$("#lost_remarks").focus();				
					return;
				}else if(!comment_validation($.trim($("#lost_remarks").val()))){
					$("#lost_remarks").closest("div").find(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
					$("#lost_remarks").focus();
					return;
				}else{
					$("#lost_remarks").closest("div").find(".error-alert").text("");
				}
			}
			if($("#Temporary").prop("checked")== true){
				if($.trim($("#activity_title").val())==""){
					$("#activity_title").next(".error-alert").text("Title is required.");
					$("#activity_title").focus();	
					return;
				}else if(!validate_name($.trim($("#activity_title").val()))){
					$("#activity_title").next(".error-alert").text("No special character.");
					$("#activity_title").focus();	
					return;
				}else{
					$("#activity_title").next(".error-alert").text("");
				}
				
				if($("#future_activity select").val() == ""){
					$("#future_activity select").next(".error-alert").text("Future activity is required.");
					$("#future_activity select").focus();	
					return;
				}else{
					$("#future_activity select").next(".error-alert").text("");
				}
				
				if($("#tempdate").val()==""){
					$("#tempdate").closest(".form-group").find(".error-alert").text("Date is required.");
					return;
				}else{
					$("#tempdate").closest(".form-group").find(".error-alert").text("");
				}
				
				if($.trim($("#activity_duration").val()) == ""){
					$("#activity_duration").next(".error-alert").text("Specify estimated activity duration.");
					return;	
				} else{
					var duration1 = $.trim($("#activity_duration").val()).split(":");
					if( parseInt(duration1[0]) == 0 && parseInt(duration1[1]) == 0){
						$("#activity_duration").next(".error-alert").text("Select valid duration time.");
						return;
					}else{
						$("#activity_duration").next(".error-alert").text("");
					}
				}
				
				if($("#reminder_time").val()==""){
					$("#reminder_time").closest("div").find("span").text("Select alert before time.");
					$("#reminder_time").focus();				
					return;
				}else{
					$("#reminder_time").closest("div").find("span").text("");
				}
				
				if($("#contact_details select").val()==""){
					$("#contact_details select").closest("div").find("span").text("Select contact type.");
					$("#contact_details select").focus();				
					return;
				}else{
					$("#contact_details select").closest("div").find("span").text("");
				}
				
				if($.trim($("#temp_remarks").val())==""){
					$("#temp_remarks").closest("div").find(".error-alert").text("Remarks is required.");
					$("#temp_remarks").focus();				
					return;
				}else if(!comment_validation($.trim($("#temp_remarks").val()))){
					$("#temp_remarks").closest("div").find(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
					$("#temp_remarks").focus();
					return;
				}else{
					$("#temp_remarks").closest("div").find(".error-alert").text("");
				}		
			}
		}
		var addObj={};
		if($("#won").prop("checked")== true){
			if($.trim($("#won_remarks").val()) == ""){
				$("#won_remarks").closest("div").find(".error-alert").text("Remarks is required.");
				return;
			}else if(!comment_validation($.trim($("#won_remarks").val()))){
					$("#won_remarks").closest("div").find(".error-alert").text("No special characters allowed (except $ & : () # @ _ . , + % ? -)");
					$("#won_remarks").focus();
					return;
			}else{
				$("#won_remarks").closest("div").find(".error-alert").text("");
			}
			addObj.remarks = $.trim($("#won_remarks").val());
			addObj.reason='permanent_loss';
			addObj.leadid = $.trim($("#logg").val());
			addObj.lead_name = $.trim($("#close_leadname").val());	
			addObj.lead_title ="";
			addObj.date ="";
			addObj.activity = activity;
			addObj.duration = "";
			addObj.activity = activity;
			addObj.future_activity = "";
			addObj.reminder = "";
			addObj.contact_id = "";
		}else{
			if($("#Temporary").prop("checked")== true){
				addObj.reason ='temporary_loss';
				addObj.remarks = $.trim($("#temp_remarks").val());
				addObj.leadid = $.trim($("#logg").val());
				addObj.lead_name = $.trim($("#close_leadname").val());
				addObj.lead_title = $.trim($("#activity_title").val());
				var startDateTime = $.trim($("#tempdate").val());
				addObj.date = startDateTime;
				var timeDuradion = moment($.trim($("#activity_duration").val()), 'HH:mm').format('HH:mm:ss');
				addObj.duration = timeDuradion;
				addObj.activity = activity;
				addObj.future_activity = $("#future_activity select").val();
				addObj.reminder = $("#reminder_time").val();
				addObj.contact_id = $("#contact_details select").val();
			}
		}
		console.log(addObj);
		loaderShow();
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('manager_leadController/close_lead'); ?>",
			dataType : 'json',
			data : JSON.stringify(addObj),
			cache : false,
			success : function(data){		
				if(error_handler(data)){
					return;
				}
				loaderHide();
				if(data==1){
					/* alert("Lead has been closed successfully!"); */
					$.alert({
						title: 'L Connectt',
						content: 'Lead has been closed successfully.',
						closeIcon: true,
						closeIconClass: 'fa fa-close',
					});
					cancel_opp();
					pageload(); /* complete page load */
					cancel1()/*Close view popup*/
				}
			},
			error:function(data){
				network_err_alert();
			}		
		});	
	}

	
	function cancel_opp(){
		$('#closed_opp').modal('hide');
		$('.form-control').val("");		
		$("#Temp_date,#future_activity,#contact_details,#task_title,#act_duration,#reminder,#remarks,#remarks1,#remarks2,#lost_id").hide();
		$('#closed_opp input[type="text"],textarea').val('');
		$('#closed_opp input[type="radio"]').prop('checked', false);
		$('#closed_opp input[type="checkbox"]').prop('checked', false);
		$(".error-alert").text("");
		/* $('.input-group.date').data().DateTimePicker.date(null);
		$(".input-group.date").data("DateTimePicker").destroy(); */
	}
	function futureActivity(){
		$.ajax({
			type: "POST",
			url:"<?php echo site_url('leadinfo_controller/get_activityList')?>",
			dataType:'json',
			success: function(data){
				if(error_handler(data)){
					return;
				}
				var select = $("#future_activity select"), options = "<option value=''>Select</option>";
				var select1 = $("#StateChangeFutureActivity select"), options1 = "<option value=''>Select</option>";
				select.empty();
				select1.empty();
				for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value+" </option>";
					options1 += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value+" </option>";
				}
				select.append(options);
				select1.append(options1);
			},
			error:function(data){
				network_err_alert();
			}
		});
	}

	function get_contacts(){
		var leadid = $.trim($("#logg").val());
		$.ajax({
			type: "POST",
			url:"<?php echo site_url('leadinfo_controller/get_contactList/')?>"+leadid,
			dataType:'json',
			success: function(data){
				if(error_handler(data)){
					return;
				}
				var select = $("#contact_details select"), options = "<option value=''>Select</option>";
				var select1 = $("#stateChangeContactType select"), options1 = "<option value=''>Select</option>";
				
				select.empty();
				select1.empty();
				for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].contact_id+"'>"+ data[i].contact_name+" </option>";
					options1 += "<option value='"+data[i].contact_id+"'>"+ data[i].contact_name+" </option>";
				}
				select.append(options);
				select1.append(options1);
			},
			error:function(data){
				network_err_alert();
			}
		});
	}
	
	function check_opportunity(){
		
		get_contacts();
		futureActivity();
		var id=$('#logg').val();
		loaderShow();
		$.ajax({
			type: "POST",
			url:  "<?php echo site_url('manager_leadController/check_opportunity'); ?>",
			data : "id="+id,
			dataType:'json',
			success: function(data){
				if(error_handler(data)){
					return;
				}
				/* -----Activity Date picker * */
				$("#tempdate").datetimepicker({
					ignoreReadonly:true,
					allowInputToggle:true,
					format:'DD-MM-YYYY',
					minDate:new Date()
				});
				/* ----Activity duration time picker *------------------ */
				$("#activity_duration").datetimepicker({
					ignoreReadonly:true,
					allowInputToggle:true,
					format:'HH:mm',
					defaultDate:'1970-01-01 00:00:00'
				});
				/* -------Premanent------ radio button */
				$("#won").click(function(){
					if($("#won").is(':checked')){
						$("#won").prop("value", 1);
						$("#lost").prop("value", 0);
						$("#Temporary").prop("value",0);
						$("#Permanent").prop("value",0);
						$("#remarks").show();
						$("#remarks1").hide();
						$("#remarks2").hide();
						$("#lost_id").hide();
						$("#Temp_date,#future_activity,#act_duration,#reminder,#contact_details,#task_title").hide();
					}else{
						$("#won").prop("value", 0);
						$("#remarks1").hide();
						$("#remarks2").hide();
						$("#remarks").hide();
						$("#lost_id").hide();
						$("#Temp_date,#future_activity,#act_duration,#reminder,#contact_details,#task_title").hide();
						$("#Permanent").hide();
					}
				});
				
				/* -------------------------  Temporary radio button */
				$("#lost").click(function(){
					$("#won").prop('value',0);
					$("#Temporary").prop('checked',false);
					$("#Permanent").prop('checked',false);
					if($("#lost").is(':checked')){
						$("#Temporary").prop('checked',true);
						$("#lost").prop("value", 1);
						$("#won").prop("value", 0);
						$("#lost_id").show();
						$("#remarks").hide();
						$("#remarks1").hide();
						$("#remarks2").hide();
						/* copy paste from below on 28-06-2018 --- E17 V1.1 */
						if($("#Temporary").is(':checked')){
							$("#Temporary").prop("value",1);
							$("#Permanent").prop("value",0);
							$("#Temp_date,#remarks2,#future_activity,#act_duration,#reminder,#contact_details,#task_title").show();
							$("#remarks,#remarks1").hide();
						}else{
							$("#Temporary").prop("value",0);
							$("#Temp_date,#future_activity,#act_duration,#reminder,#contact_details,#task_title").hide();
							$("#remarks").hide();
							$("#remarks1").hide();
							$("#remarks2").hide();
						}
							
					}else{
						$("#lost").prop("value", 0);
						$("#lost_id").hide();
						$("#remarks").hide();
						$("#remarks1").hide();
						$("#remarks2").hide();
					}
				});
				
				loaderHide();
				
				/*----------------------------------  Old implementation -------------------------------- 
				if(data==0){
					alert("You can not close the Lead, since there exist an active Opportunity for this.");					
					return false;
				}else if(data==1){
					$('#closed_opp').modal('show');
				} 
				----------------------------------------------------------------------------------------*/
				/* 	---------------------------------------------------------------------------------------
				New requirement (10-08-2018)
				If there is opportunity exists .. then also user can proceed with close lead by clicking 
				ok on the confirmbox  
				if data = 1 -- means threre is opportinity associated with this lead
				if data = 0 -- means there is no opportinity associated with this lead
				
				status 0 :  no need to show , directly proceed
				status 1 : user does not have the authority to close the lead (can not proceed )
				status 2 : One or more opportunity exists, do you still want to close the lead

				------------------------------------------------------------------------------------------*/
				if(data == 0){
					$('#closed_opp').modal('show');
				}else if(data == 1){
					/* alert("User does not have the authority to close the lead!") */
					$.alert({
						title: 'L Connectt',
						content: 'User does not have the authority to close the lead.',
						closeIcon: true,
						closeIconClass: 'fa fa-close',
					});
					return;
				}else if(data == 2){
					/* var alertMsg = confirm("One or more opportunity exists \nDo you still want to close the lead!");
					if (alertMsg == true) {
						$('#closed_opp').modal('show');
					} */
					$.confirm({
						title: 'L Connectt',
						content: 'One or more opportunity exists <br>Do you still want to close the lead',
						animation: 'none',
						closeAnimation: 'scale',
						buttons: {
							Ok: function () {
								$('#closed_opp').modal('show');
							},
							Cancel: function () {
								
							}
						}
					});
				}
			},
			error:function(data){
				network_err_alert();
			}
			
		});
		
		
	}
</script>