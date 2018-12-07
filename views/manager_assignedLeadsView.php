<!DOCTYPE html>
<html lang="en">
    <head>
		<?php require 'scriptfiles.php' ?>
		<script>

		$(document).ready(function(){	
			pageload();	
		});

		function capitalizeFirstLetter(string) {
				return string.charAt(0).toUpperCase() + string.slice(1);
		}

		/* select atleast one checkbox and click on the Assign-Button on the main page */
		function assign_btn(){
			var check = [];
			$("#tablebody tr input:checkbox").each(function () {       
				if($(this).prop("checked")==true){
					check.push($(this).attr('id'));
					flagchk=1;
				}		
			});
			if(check.length <= 0){
				alert("Please select the lead");
				return;	
			}	
			assign_date(check.join(":"),"multiple");       	
		}

		/* click on the view button on the main page >> the click on the assign button on the view popup section  */
		function assign_btn2() {
			var id=$("#logg").val();	
			assign_date(id, "single");
		}
		 function assign_date(idval , status){
				if(idval!=''){
					$("#mgrlist").siblings(".error-alert").text("");
					$("#modalstart1").modal("show");
				}
				if(status == "multiple"){
					$("#asssign_save_section").val('').val('multiple')
				}
				if(status == "single"){
					$("#asssign_save_section").val('').val('single')
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
						url: "<?php echo site_url('manager_leadController/get_managerlist_reassign');?>",
						dataType:'json',
						data:JSON.stringify(finalArray),
						success: function(data) {
							$(".multiselect2").removeAttr('style');
							if(error_handler(data)){
								return;
							}
							var multipl2="";
							var chk = 0;
							if(data.length <= 0){
								if(status == "multiple"){
									multipl2 ='<li>No Executive(s) available for the selected lead(s).</li>';
								}
								if(status == "single"){
									multipl2 ='<li>No Manager(s) available for the selected lead.</li>';
								}
							}else{
								for(var i=0;i<data.length; i++){
									if(status == "multiple"){
										if(data[i].sales_module!='0' && data[i].manager_module=='0'){
											multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'"> '+data[i].user_name+' (Executive)</label></li>';
											chk = 1;
										}
										if(data[i].sales_module!='0' && data[i].manager_module!='0'){
											multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'"> '+data[i].user_name+' (Executive)</label></li>';
											chk = 1;
										}
									}
									
									if(status == "single"){
										if(data[i].sales_module=='0' && data[i].manager_module!='0'){
											if(user_chk != data[i].user_id){
												multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'"> '+data[i].user_name+' (Manager)</label></li>';
												chk = 1;
											}
										}	
										if(data[i].sales_module!='0' && data[i].manager_module!='0'){
											/* if the logged in user and data[i].user_id is not same */
											if(user_chk != data[i].user_id){
													multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'"> '+data[i].user_name+' (Manager)</label></li>';
													chk = 1;
											}								
										}
									}
								}
								if(status == "multiple" && chk == 0){
									multipl2 ='<li>No Executive(s) available for the selected lead(s).</li>';
								}
								if(status == "single" && chk == 0){
									multipl2 ='<li>No Manager(s) available for the selected lead.</li>';
								}
							}
								
							$(".multiselect2 ul").append(multipl2);
						},
						error:function(data){
							network_err_alert();
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
					leads.push($("#logg").val());	
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
				finalArray.leads = leads;
				finalArray.users = users;
				loaderShow();
				$.ajax({
						type: "POST",
						url: "<?php echo site_url('manager_leadController/reassign'); ?>",
						data:JSON.stringify(finalArray),
						dataType:'json',
						success: function(data) {
							if(error_handler(data)){
								return;
							}
							loaderHide();			
							if(data==1){
								$('#modalstart1').modal('hide');
								$('.form-control').val("");         		
							}
							pageload();			
						},
						error:function(data){
							network_err_alert();
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

		function fetch_assigned_others(){
			
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('manager_leadController/fetch_assigned_others'); ?>",
				dataType:'json',
				success: function(data){
						if(error_handler(data)){
							return;
						}
						
						leads=data['data'];	
						user_chk=data['user_check'];	            
						$('#second_tab_table').dataTable().fnDestroy();
						$('#second_tab_table tbody').html("")
						
						var row = "";
						var luser=''; 
						for(i=0; i < leads.length; i++ ){	
							/* leads[i].repremarks= window.btoa(leads[i].repremarks);
							leads[i].leadtaddress= window.btoa(leads[i].leadtaddress);
							leads[i].contPrsnAdd= window.btoa(leads[i].contPrsnAdd); */
							
							leads[i].repremarks= window.btoa(unescape(encodeURIComponent(leads[i].repremarks)));
							leads[i].leadtaddress= window.btoa(unescape(encodeURIComponent(leads[i].leadtaddress)));
							leads[i].contPrsnAdd= window.btoa(unescape(encodeURIComponent(leads[i].contPrsnAdd)));
							
							var rowdata = JSON.stringify(leads[i]);
							
							
							if(leads[i].leadstate=='pending'){
								luser="<b>Pending for acceptance</b>"
										
							}else if(leads[i].leadstate=='accepted'){
								if(leads[i].user_state=='0'){
									luser="<b style=color:red>"+leads[i].user_name+"</b>"
								}else{
									luser=leads[i].user_name;
								}
							}
							
							if(leads[i].mgr_owner==''){
								leads[i].mgr_owner="<b>Pending for acceptance</b>";
										
							}else{
								leads[i].mgr_owner = leads[i].mgr_owner;
							}			
							
							var pList = "";
							var pList1 = "";
							if(leads[i].product_names != "-"){
								var pArray = leads[i].product_names.split(",")
								for(p=0; p< pArray.length; p++){
									if(p<=1){								
										pList += "<li>"+pArray[p]+"</li>";
									}
									if(p > 1){
										pList1 += '<li>'+pArray[p]+'</li>';
									}
								}
								if(pArray.length > 2){
									pList += '<span rel="tooltip" data-toggle="tooltip" data-trigger="hover" data-placement="bottom" data-html="true" data-title="'+pList1+'"><u> '+(pArray.length - 2)+' more</u></span>';
								}
							}
							
							if(leads[i].rejected_manager == '3' && leads[i].rejected_sales == '3'){
								row += "<tr class='rejected_lead'><td>" + (i+1) + "</td><td>" + leads[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</td><td>"+ luser + "</ul></td><td>"+ leads[i].industry_name + "</td><td>" + leads[i].city +"</td><td>"+ leads[i].employeename +"</td><td>"+ leads[i].employeephone1 +"</td><td>" + leads[i].leadsource +"</td><td>"+ leads[i].mgr_owner +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+",\"Reassign_not_required\")'><span class='glyphicon glyphicon-eye-open'></span></a></td></tr>";		

							}else if(leads[i].rejected_manager == '3' && leads[i].rejected_sales <= '2'){
								row += "<tr class='rejected_lead'><td>" + (i+1) + "</td><td>" + leads[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</td><td>"+ luser + "</ul></td><td>"+ leads[i].industry_name + "</td><td>" + leads[i].city +"</td><td>"+ leads[i].employeename +"</td><td>"+ leads[i].employeephone1 +"</td><td>" + leads[i].leadsource +"</td><td><b>Rejected by managers</b></td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+",\"Reassign_not_required\")'><span class='glyphicon glyphicon-eye-open'></span></a></td></tr>";

							}else if(leads[i].rejected_manager <='2' && leads[i].rejected_sales == '3'){
								row += "<tr class='rejected_lead'><td>" + (i+1) + "</td><td>" + leads[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</td><td><b>Rejected by executives</b></ul></td><td>"+ leads[i].industry_name + "</td><td>" + leads[i].city +"</td><td>"+ leads[i].employeename +"</td><td>"+ leads[i].employeephone1 +"</td><td>" + leads[i].leadsource +"</td><td>"+ leads[i].mgr_owner +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+",\"Reassign_not_required\")'><span class='glyphicon glyphicon-eye-open'></span></a></td></tr>";

							}else if(leads[i].rejected_manager == '1'){
								row += "<tr><td>" + (i+1) + "</td><td>" + leads[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>"+ luser + "</td><td>"+ leads[i].industry_name + "</td><td>" + leads[i].city +"</td><td>"+ leads[i].employeename +"</td><td>"+ leads[i].employeephone1 +"</td><td>" + leads[i].leadsource +"</td><td><b>Pending for acceptance</b></td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+",\"Reassign_not_required\")'><span class='glyphicon glyphicon-eye-open'></span></a></td></tr>";
							}else{
								row += "<tr><td>" + (i+1) + "</td><td>" + leads[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>"+ luser + "</td><td>"+ leads[i].industry_name + "</td><td>" + leads[i].city +"</td><td>"+ leads[i].employeename +"</td><td>"+ leads[i].employeephone1 +"</td><td>" + leads[i].leadsource +"</td><td>"+ leads[i].mgr_owner +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+",\"Reassign_not_required\")'><span class='glyphicon glyphicon-eye-open'></span></a></td></tr>";						
							}
						}	             
						$('#second_tab_table tbody').append(row);
						$('#second_tab_table').DataTable({
															"aoColumnDefs": [{ "bSortable": false, "aTargets":[10]}]
														});
						
						$('#second_tab_table').removeAttr("style")
						$('.legend-wrapper').remove();
						$('.dataTables_length').append('<label class="legend-wrapper" ><div class="legend pull-left" title="Rejected Lead"></div> <b>Rejected Lead</b></label>');
						
						if(leads.length <= 0){
							col = $('#second_tab_table thead tr th').length;
							$('#second_tab_table tbody tr td').attr('colspan', col);
						}
						
					
				},
				error:function(data){
					/* network_err_alert(); */
					console.log(data)
				}
			})
		}



		var user_chk='';  
		function pageload(){
			$(".modal").each(function(){
				$(this).modal("hide");
			})
			$.ajax({
					type: "POST",
					url: "<?php echo site_url('manager_leadController/fetch_assigned'); ?>",
					dataType:'json',
					success: function(data) {
						fetch_assigned_others();				
						loaderHide();
						if(error_handler(data)){
							return;
						}
						
						leads=data['data'];	
						user_chk=data['user_check'];	            
						$('#tablebody').parent("table").dataTable().fnDestroy();
						$('#tablebody').html("")
						if(leads.length > 0){
							 $('#assign1').removeClass('hidden');
						}

						var row = "";
						var luser=''; 
						for(i=0; i < leads.length; i++ ){	
							/* 
							leads[i].repremarks= window.btoa(leads[i].repremarks);
							leads[i].leadtaddress= window.btoa(leads[i].leadtaddress);
							leads[i].contPrsnAdd= window.btoa(leads[i].contPrsnAdd);
							 */
							leads[i].repremarks= window.btoa(unescape(encodeURIComponent(leads[i].repremarks)));
							leads[i].leadtaddress= window.btoa(unescape(encodeURIComponent(leads[i].leadtaddress)));
							leads[i].contPrsnAdd= window.btoa(unescape(encodeURIComponent(leads[i].contPrsnAdd)));
							
							var rowdata = JSON.stringify(leads[i]);

							if(leads[i].leadstate=='pending'){
								luser="<b>Pending for acceptance</b>";
							}else if(leads[i].leadstate=='accepted'){
								if(leads[i].user_state=='0'){
									luser="<b style=color:red>"+leads[i].user_name+"</b>";
								}else{
									luser=leads[i].user_name;
								}
							}
							
							if(leads[i].mgr_owner==''){
								leads[i].mgr_owner="<b>Pending for acceptance</b>";
										
							}else{
								leads[i].mgr_owner = leads[i].mgr_owner;
							}
							
							var pList = "";
							var pList1 = "";
							if(leads[i].product_names != "-"){
								var pArray = leads[i].product_names.split(",")
								for(p=0; p< pArray.length; p++){
									if(p<=1){								
										pList += "<li>"+pArray[p]+"</li>";
									}
									if(p > 1){
										pList1 += '<li>'+pArray[p]+'</li>';
									}
								}
								if(pArray.length > 2){
									pList += '<span rel="tooltip" data-toggle="tooltip" data-trigger="hover" data-placement="bottom" data-html="true" data-title="'+pList1+'"><u> '+(pArray.length - 2)+' more</u></span>';
								}
							}
							
							/* row += "<tr><td><input type='checkbox' name='"+leads[i].lead_rep_owner+"' val = '"+leads[i].lead_manager_owner+"'id='"+leads[i].leadid+"' class='assign_class'/> " + (i+1) + "</td><td>" + leads[i].leadname +"</td><td>" + leads[i].employeename +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>"+ leads[i].employeedesg+ "</td><td>"+ luser + "</td><td>" + leads[i].employeephone1 +"</td><td>" + leads[i].leademail +"</td><td>" + leads[i].city +"</td><td>" + leads[i].leadsource +"</td><td>"+ leads[i].mgr_owner +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>"; */
							if(leads[i].rejected_manager == '3' && leads[i].rejected_sales == '3'){
								row += "<tr class='rejected_lead'><td><input type='checkbox' name='"+leads[i].lead_rep_owner+"' val = '"+leads[i].lead_manager_owner+"'id='"+leads[i].leadid+"' class='assign_class'/> " + (i+1) + "</td><td>" + leads[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td><b>Rejected by all</b></td><td>"+ leads[i].industry_name + "</td><td>" + leads[i].city +"</td><td>"+ leads[i].employeename +"</td><td>"+ leads[i].employeephone1 +"</td><td>" + leads[i].leadsource +"</td><td>"+ leads[i].mgr_owner +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";	

							}else if(leads[i].rejected_manager == '3' && leads[i].rejected_sales <= '2'){
								row += "<tr class='rejected_lead'><td><input type='checkbox' name='"+leads[i].lead_rep_owner+"' val = '"+leads[i].lead_manager_owner+"'id='"+leads[i].leadid+"' class='assign_class'/> " + (i+1) + "</td><td>" + leads[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>"+luser+"</td><td>"+ leads[i].industry_name + "</td><td>" + leads[i].city +"</td><td>"+ leads[i].employeename +"</td><td>"+ leads[i].employeephone1 +"</td><td>" + leads[i].leadsource +"</td><td><b>Rejected by managers</b></td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";	

							}else if(leads[i].rejected_manager <= '2' && leads[i].rejected_sales == '3'){
								row += "<tr class='rejected_lead'><td><input type='checkbox' name='"+leads[i].lead_rep_owner+"' val = '"+leads[i].lead_manager_owner+"'id='"+leads[i].leadid+"' class='assign_class'/> " + (i+1) + "</td><td>" + leads[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td><b>Rejected by executives</b></td><td>"+ leads[i].industry_name + "</td><td>" + leads[i].city +"</td><td>"+ leads[i].employeename +"</td><td>"+ leads[i].employeephone1 +"</td><td>" + leads[i].leadsource +"</td><td>"+ leads[i].mgr_owner +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";	

							}else if(leads[i].rejected_manager == '1'){
								row += "<tr><td><input type='checkbox' name='"+leads[i].lead_rep_owner+"' val = '"+leads[i].lead_manager_owner+"'id='"+leads[i].leadid+"' class='assign_class'/> " + (i+1) + "</td><td>" + leads[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>"+ luser + "</td><td>"+ leads[i].industry_name + "</td><td>" + leads[i].city +"</td><td>"+ leads[i].employeename +"</td><td>"+ leads[i].employeephone1 +"</td><td>" + leads[i].leadsource +"</td><td><b>Pending for acceptance</b></td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";

							}else{
								row += "<tr><td><input type='checkbox' name='"+leads[i].lead_rep_owner+"' val = '"+leads[i].lead_manager_owner+"'id='"+leads[i].leadid+"' class='assign_class'/> " + (i+1) + "</td><td>" + leads[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>"+ luser + "</td><td>"+ leads[i].industry_name + "</td><td>" + leads[i].city +"</td><td>"+ leads[i].employeename +"</td><td>"+ leads[i].employeephone1 +"</td><td>" + leads[i].leadsource +"</td><td>"+ leads[i].mgr_owner +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";						
							}
						}	             
						$('#tablebody').parent("table").removeClass('hidden');    
						$('#tablebody').append(row);
						$('#tablebody').parent("table").DataTable({
																		"aoColumnDefs": [{ "bSortable": false, "aTargets":[10,11]}]
																	});
						$("#tableTeam thead th").each(function(){
								$(this).removeAttr("style")
						});
						var checkid=[];
						$('.legend-wrapper').remove();
						$('.dataTables_length').append('<label class="legend-wrapper" ><div class="legend pull-left" title="Rejected Lead"></div> <b>Rejected Lead</b></label>');
			
					},
					error:function(data){
						network_err_alert();
					}

			});
			$('#select_map').hide();
			
			$("#map2").hide();
			$("#okmap").click(function(){
				$("#select_map").show();
				$("#map1").hide();
				$("#map2").hide();
				rendergmap();
			});
			$("#edit_okmap").click(function(){
				$("#edit_selectmap").show();
				$("#edit_map2").hide();
				$("#edit_map1").hide();
				edit_rendergmap();
			});
			
		}



		function change1(){
			var id= $('#country option:selected').val(); 
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('manager_leadController/get_state'); ?>",
				data : "id="+id,
				dataType:'json',
				success: function(data) {
					if(error_handler(data)){
						return;
					}
					var select = $("#state"), options = "<option value=''>select</option>";
				   select.empty();
				   for(var i=0;i<data.length; i++)
				   {
						options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
				   }
				   select.append(options);
				},
				error:function(data){
					network_err_alert();
				}
			});
		}


		function cancelCust(){
			$('.modal').modal('hide');
			$('.modal .form-control[type=text],.modal textarea').val("");
			$('.modal select.form-control').val($('.modal select.form-control option:first').val());
			$(".contact_type1").remove(); 
		}
		function add_cancel(){
			$('.modal').modal('hide');
			$('.modal .form-control[type=text],.modal textarea').val("");
			$('.modal select.form-control').val($('.modal select.form-control option:first').val());
			$(".contact_type02").remove();
			$('#select_map').hide();
			$('#map1').show();
		}
		function cancel1(){
			$('.modal').modal('hide');
			$('.modal .form-control').val("");
		} 
		function cancel_opp(){
			$('.form-control').val("");		
			$("#Temp_date").hide();
			$("#remarks").hide();
			$("#remarks1").hide();
			$("#remarks2").hide();
			$('.modal input[type="text"], select, textarea').val('');
			$('.modal input[type="radio"]').prop('checked', false);
			$('.modal input[type="checkbox"]').prop('checked', false);
		}
		function close_modal(){
			$('#modalstart1').modal('hide');	
			$('.modal .form-control').val("");
			$("#modal_upload").modal("hide");
			$('#modal_upload #files').val("");
			$('.leadsrcname').html("");
		 } 




		</script>
    </head>
       <body class="hold-transition skin-blue sidebar-mini lcont-lead-page">  
		<div class="loader">
			<center><h1 id="loader_txt"></h1></center>
	</div>
        <?php require 'demo.php' ?>
        <?php require 'manager_sidenav.php' ?>
        <div class="content-wrapper body-content">
            <div class="col-lg-12 column">
				<div class="row header1">				
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon toolTipStyle">
	                        <div>	
								<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-toggle="tooltip" data-placement="bottom" data-html="true" title="1.‘My Leads’ are all the leads that you own that are assigned to a manager or executive under you.<br/><br/>2.‘Team Leads’ are the leads that managers and executives under you are working on where you are not the manager owner."/>

	                        </div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Assigned Leads</h2>	
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						<div class="addBtns" onclick="add_lead()">
							<a href="#leadinfoAdd" class="addPlus" data-toggle="modal" >
								<img src="<?php echo site_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
							<a  class="addExcel" onclick="addExl()" >
								<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a>
						</div>
                        <div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				
				
				<ul class="nav nav-tabs">
					<li class="active" id="first_tab_li"><a data-toggle="tab" href="#first_tab_div"><h4>My Leads</h4></a></li>
					<li id="second_tab_li"><a data-toggle="tab" href="#second_tab_div"><h4>Team Leads</h4></a></li>
				</ul>
				<div class="tab-content">
					<div id="first_tab_div" class="tab-pane fade in active">
						<div class="table-responsive">
							<table class="table hidden" id="tableTeam">
								<thead>  
								<tr>
									<th class="table_header" width="3%">#</th>
									<th class="table_header" width="10%">Name</th>		
									<th class="table_header" width="10%">Products</th>
									<th class="table_header" width="10%">Owned By</th>
									<th class="table_header" width="10%">Industry</th>
									<th class="table_header" width="10%">City</th>	
									<th class="table_header" width="10">Contact</th>
									<th class="table_header" width="10%">Phone</th>							
									<th class="table_header" width="10%">Lead Source</th>	
									<th class="table_header" width="10%">Lead Manager Owner</th>
									<th class="table_header" width="3%"></th>
									<th class="table_header" width="4%"></th>
								</tr>
								</thead>  
								<tbody id="tablebody">
								</tbody>    
							</table>
						</div>
						<div align="center">
							<input type="button" class="btn hidden" onclick="assign_btn()" id="assign1" value="Reassign"/>
						</div>
					</div>
					<!------------------------------------------------------>
					<div id="second_tab_div" class="tab-pane fade">
						<div class="table-responsive">
							<table class="table" id="second_tab_table">
								<thead>  
								<tr>
									<th class="table_header" width="5%">#</th>
									<th class="table_header" width="10%">Name</th>		
									<th class="table_header" width="10%">Products</th>
									<th class="table_header" width="10%">Owned By</th>
									<th class="table_header" width="10%">Industry</th>
									<th class="table_header" width="10%">City</th>	
									<th class="table_header" width="10">Contact</th>
									<th class="table_header" width="10%">Phone</th>							
									<th class="table_header" width="10%">Lead Source</th>	
									<th class="table_header" width="10%">Lead Manager Owner</th>
									<th class="table_header" width="5%"></th>
								</tr>
								</thead>  
								<tbody>
								</tbody>    
							</table>
						</div>
					</div>
				</div>
            </div>
        </div>
            <?php require 'manager-leadinfo-add-modal.php' ?>  
			<?php require 'manager-leadinfo-edit-modal.php' ?>  
			<?php require 'manager-leadinfo-view-modal.php' ?>  		
			<?php require 'manager-exel-file-upload.php' ?>         
            <input type="hidden" id="manager_lead" value="Reassign"/>
              
           
			<div id="modalstart1" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form">
						<input type="hidden" id="asssign_save_section"/>
							<div class="modal-header">
									<span class="close"  onclick="close_modal()">&times;</span>
									<h4 class="modal-title">Lead Assignment</h4>
							</div>
							<div class="modal-body">
								<div class="row targetrow ">
										<div class="col-md-2">
											<label for="mgrlist">User list*</label> 
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
									<input type="button" class="btn" onclick="assign_save()" value="Assign">
									<!--<input type="button" class="btn" onclick="cancel()" value="Cancel" >-->
									<input type="button" class="btn" onclick="close_modal()" value="Cancel">
							</div>
						</form>
					</div>
				</div>
			</div>
			
		

        <div id="counterList"  class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                        <h4 class="modal-body"> </h4>
                </div>
            </div>
        </div>
        <?php require 'footer.php' ?>

    </body>
</html>
