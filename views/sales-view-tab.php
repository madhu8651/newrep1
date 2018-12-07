<script>
function tab(id){
	
	
	
		
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('leadinfo_controller/schedule_logs'); ?>",
        data :"id="+id,
        dataType:'json',
        success: function(data){
			if(error_handler(data)){
				 return;
			}
			var row = "";
			if(data.length != 0){
			for(i=0; i < data.length; i++ ){  
				var rowdata = JSON.stringify(data[i]);	
				row += "<tr><td>" + (i+1) + "</td><td>" + data[i].user_name +"</td><td>" + data[i].leadname +"</td><td>" + data[i].activity + "</td></td><td>" + data[i].Start_time +"</td></td><td>" + data[i].End_time +"</td></td><td>" + data[i].remarks +"</td></tr>";
			}
			$('#activitylog').append(row);  
			}else{
				$('#activitylog').closest('.displayArea').hide(); 
				$('#activitylog').closest('.tab-pane').append("<h4 class='notFound'><center>No scheduled activity found</center></h4>");
			}
        },
		error:function(data){
			network_err_alert();
		}
    });
	
    $.ajax({
		type: "POST",
		url:  "<?php echo site_url('leadinfo_controller/dislpay_loglead'); ?>",
		data : "id="+id,
		dataType:'json',
		success: function(data){
			if(error_handler(data)){
				return;
			}
			var row = "";
			if(data.length != 0){
				var url_path = "<?php echo base_url(); ?>uploads/";
				for(i=0; i < data.length; i++ ){  
				   $('#logtable').empty();
				   var rowdata = JSON.stringify(data[i]);
				   if(data[i].rating == 0){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].lead_name +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].lookup_value +"</td><td>" +"-"+"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
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
					}else if(data[i].rating == 1){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].lead_name +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].lookup_value + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
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
					}else if(data[i].rating == 2){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].lead_name +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].lookup_value + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
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
					}else if(data[i].rating == 3){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].lead_name +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].lookup_value + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' ></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
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
					}else if(data[i].rating == 4){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].lead_name +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].lookup_value + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' ></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
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
					}else {
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].lead_name +"</td><td>" + data[i].Start_time +"</td><td>" + "-" + "</td><td>" + "<i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + "-" +"</td><td>" + "-" +"</td>";
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
				}
				$('#logtable').append(row); 
				
			}else{
				$('#logtable').closest('.displayArea').hide(); 
				$('#logtable').closest('.tab-pane').append("<h4 class='notFound'><center> No lead history found</center></h4>");
			}			
			
			
		},
		error:function(data){
			network_err_alert();
		} 
    });

	$.ajax({
		type: "POST",
		url:  "<?php echo site_url('leadinfo_controller/opportunity'); ?>",
		data : "id="+id,
		dataType:'json',
		success: function(data){
			if(error_handler(data)){
				return;
			}
			var row = "";
			if(data.length != 0){
				for(i=0; i < data.length; i++ ){  
					$('#opp_table').empty();
					row += "<tr><td>" + (i+1) + "</td><td>" + data[i].opportunity_name +"</td><td>" + data[i].stage_name + "</td><td>" + data[i].opportunity_date + "</td><td>" + data[i].user_name+ "</td>";
				}     
				$('#opp_table').append(row);
			}else{
				$('#opp_table').closest('.displayArea').hide(); 
				$('#opp_table').closest('.tab-pane').append("<h4 class='notFound'><center>No opportunity found</center></h4></div>");
			}
		},
		error:function(data){
			network_err_alert();
		}
	});
}
</script>
<div class="row">
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" id="leadlog" href="#logdetails">Lead History</a></li>
		<li><a data-toggle="tab" id="Scheduled_log" href="#scheduled_activity" >Scheduled Activity</a></li>
		<li><a data-toggle="tab" id="opp_log" href="#oop_details">Opportunities</a></li>
	</ul>
	<div class="tab-content">
		<div id="logdetails" class="tab-pane fade in active">
			<div class="displayArea">
				<div class="col-md-12 lead_view">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<center><b>Lead History</b></center>
					</div>
				</div>
				<table class="table" id="tablelog">
					<thead>  
					<tr>	
					   <th class="table_header">#</th>
						<th class="table_header">Lead Name</th>
						<th class="table_header">Date-time</th>
						<th class="table_header">Activity</th>
						<th class="table_header">Ratings</th>		
						<th class="table_header">Duration</th>
						<th class="table_header">Remarks</th>	
						<th class="table_header">Content</th>	
					</tr>
				</thead>  
				<tbody id="logtable">
				</tbody>    
				</table>
			</div>
		</div>
		<div id="scheduled_activity" class="tab-pane fade">
			<div class="displayArea">
				<div class="col-md-12 lead_view">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<center><b>Scheduled Activities</b></center>
					</div>
				</div>
				<table class="table" id="activity_log">
					<thead>  
						<tr>	
							<th class="table_header">#</th>
							<th class="table_header">Rep Name</th>
							<th class="table_header">Lead Name</th>
							 <th class="table_header">Activity</th>
							<th class="table_header">Start-Date</th>				
							<th class="table_header">End-Date</th>		
							<th class="table_header">Remarks</th>	
						</tr>
					</thead>  
					<tbody id="activitylog">
					</tbody>    
				</table> 
				 
			</div>
			<div class="row btn_log">
				<br>
				<center>
					<a href="<?php echo site_url('sales_mytaskController/index'); ?>"><button type="button" class="btn" id="new_opp1" >Add New Activity</button></a>
				</center>
			</div>
		</div>
		<div id="oop_details" class="tab-pane fade">
			<div class="displayArea">
				<div class="col-md-12" style="background-color:#c1c1c1;padding: 10px 12px;">
					<div class="col-md-12 col-sm-12 col-xs-12">
					<center><b>Opportunity List</b></center>
					</div>
				</div>
				<table class="table" id="tableopp">
					<thead>  
						<tr>	
							<th class="table_header">#</th>
							<th class="table_header">Opportunity Name</th>
							<th class="table_header"> Sales Stage</th>
							<th class="table_header">Expected Closed Date</th>		
							<th class="table_header">Stage Owner</th>
						</tr>
					</thead>  
						<tbody id="opp_table">
						</tbody>    
				</table>
			</div>
			<div class="row btn_log">
				<br>
				<center>
					<a href="<?php echo site_url('sales_opportunitiesController/inprogress_opportunities'); ?>"><button type="button" class="btn" id="new_opp" >Add New Opportunity</button></a>
				</center>
			</div>
		</div>
	</div>
</div>