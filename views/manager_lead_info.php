<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require 'scriptfiles.php' ?>
		<style>
			.style_video .popover-content hr{
				margin-top: 10px!important;
				margin-bottom: 10px!important;
			}
		</style>
<script>
	$(document).ready(function(){	
		pageload();	
		var user_module = {
			content: 	'<div>'+
							'<a href="#" onclick="select_video(\'Add_Leads\', \'video_body\', \'Manager\')">Add Leads</a>'+
						'</div><hr>' +
						'<div>'+
							'<a href="#" onclick="select_video(\'Assign_Leads\', \'video_body\', \'Manager\')">Assign Leads</a>'+
						'</div>',
			html: true,
			placement: 'bottom'
		};
		$("#leadVedios").popover(user_module);
	});
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
				url: "<?php echo site_url('manager_leadController/get_managerlist');?>",
				dataType:'json',
				data:JSON.stringify(finalArray),
				success: function(data) {
					$(".multiselect2").removeAttr('style');
					if(error_handler(data)){
						return;
					}
					var multipl2="";
					
					var chk=0;
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
									multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'">'+data[i].user_name+' (Executive)</label></li>';
									chk = 1;
								}
								if(data[i].sales_module!='0' && data[i].manager_module!='0'){
									multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'">'+data[i].user_name+' (Executive)</label></li>';
									chk = 1;
								}
							}
							
							if(status == "single"){
								if(data[i].sales_module=='0' && data[i].manager_module!='0'){
									if(user_chk != data[i].user_id){
										multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'">'+data[i].user_name+' (Manager)</label></li>';
										chk = 1;
									}
								}	
								if(data[i].sales_module!='0' && data[i].manager_module!='0'){
									/* if the logged in user and data[i].user_id is not same */
									if(user_chk != data[i].user_id){
											multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'">'+data[i].user_name+' (Manager)</label></li>';
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
				url: "<?php echo site_url('manager_leadController/post_id'); ?>",
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

	
	
	
	var user_chk='';
	
	function pageload() {
		$(".modal").each(function(){
			$(this).modal("hide");
		})
		$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('manager_leadController/fetch'); ?>",
	            dataType:'json',
	            success: function(data) {
	            	if(error_handler(data)){
                    	return;
                	}
					loaderHide();
					user_chk=data['user_check'];
					data=data['data'];
		            $('#tablebody').parent("table").dataTable().fnDestroy();
		            $('#tablebody').html("")
		            if(data.length == 0){	   					
						$('#assign1').addClass('hidden');
	   				}else{
						$('#assign1').removeClass('hidden');
					}				
		            var row = "";
		            for(i=0; i < data.length; i++ ){
						/* data[i].repremarks= window.btoa(data[i].repremarks);
						data[i].leadtaddress= window.btoa(data[i].leadtaddress);
						data[i].contPrsnAdd= window.btoa(data[i].contPrsnAdd);
						 */
						data[i].repremarks= window.btoa(unescape(encodeURIComponent(data[i].repremarks)));
						data[i].leadtaddress= window.btoa(unescape(encodeURIComponent(data[i].leadtaddress)));
						data[i].contPrsnAdd= window.btoa(unescape(encodeURIComponent(data[i].contPrsnAdd)));
						
			            var rowdata = JSON.stringify(data[i]);
						var lstate='';
						if(data[i].leadstate==1){
							lstate="<b style=color:blue>Pending</b>";					
						}
						else if(data[i].leadstate==3){
							lstate="<b style=color:green>Received</b>";		

						}
						else if(data[i].leadstate==4){
							lstate="<b style=color:red>Rejected</b>";	
						}
						else if(data[i].leadstate==2){
							lstate="<b style=color:red>Seen</b>";	
						}
						
						var pList = "";
						var pList1 = "";
						if(data[i].product_names != ""){
							var pArray = data[i].product_names.split(",")
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
    

		            	/* row += "<tr><td><input type='checkbox' name='foo[]' id='"+data[i].leadid+"' class='assign_class'/> " + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].employeename +"</td><td>" + data[i].employeedesg+ "</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>" + data[i].employeephone1 +"</td><td>" + data[i].leademail +"</td><td>" + data[i].city +"</td><td>" + data[i].leadsource +"</td><td>"+ data[i].lead_owner +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";	 */
						if(data[i].rejected_manager == '3'){
							row += "<tr class='rejected_lead'><td><input type='checkbox' name='foo[]' id='"+data[i].leadid+"' class='assign_class'/> " + (i+1) + "</td><td>" + data[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>"  + data[i].industry_name +"</td><td>"+ data[i].city +"</td><td>"+ data[i].employeename +"</td><td>"+ data[i].employeephone1 +"</td><td>" + data[i].leadsource +"</td><td><b>Rejected by managers</b></td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
						}else if(data[i].rejected_manager == '1'){
							row += "<tr><td><input type='checkbox' name='foo[]' id='"+data[i].leadid+"' class='assign_class'/> " + (i+1) + "</td><td>" + data[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>"+ data[i].industry_name +"</td><td>"  + data[i].city +"</td><td>"+ data[i].employeename +"</td><td>"+ data[i].employeephone1 +"</td><td>" + data[i].leadsource +"</td><td><b>Pending for acceptance</b></td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
						}else{
							row += "<tr><td><input type='checkbox' name='foo[]' id='"+data[i].leadid+"' class='assign_class'/> " + (i+1) + "</td><td>" + data[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>"+ data[i].industry_name +"</td><td>"  + data[i].city +"</td><td>"+ data[i].employeename +"</td><td>"+ data[i].employeephone1 +"</td><td>" + data[i].leadsource +"</td><td>"+ data[i].lead_owner +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
						}
		            			
	            	}	
					$('#tablebody').parent("table").removeClass('hidden');    
					$('#tablebody').append(row);
					$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [{ "bSortable": false, "aTargets": [9,10] }]
															});	
					$("#tableTeam thead th").each(function(){
						$(this).removeAttr("style")
					});
					$('#tablebody [rel="tooltip"]').tooltip();
					$('.legend-wrapper').remove();
					$('.dataTables_length').append('<label class="legend-wrapper" ><div class="legend pull-left" title="Rejected Lead"></div> <b>Rejected Lead</b></label>');
					
	            }
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
						for(var i=0;i<data.length; i++){
							options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
						}
						select.append(options);
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
		$('.form-control').val("");
	} 
	function cancel_opp(){
		$('.form-control').val("");		
		$("#Temp_date").hide();
		$("#remarks").hide();
		$("#remarks1").hide();
		$("#remarks2").hide();
		$('input[type="text"], select, textarea').val('');
		$('input[type="radio"]').prop('checked', false);
		$('input[type="checkbox"]').prop('checked', false);
	}

	function close_modal(){
		$('#modalstart1').modal('hide');	
		$('.form-control').val("");
		$("#modal_upload").modal("hide");
		$('#modal_upload #files').val("");
                $('.leadsrcname').html("");
	} 	
	




/* ------------------------------------------------------- */
/* -----------------------View lead ends-------------------------------- */
/* ------------------------------------------------------- */
	
	
	

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
								<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-toggle="tooltip" data-placement="bottom" data-html="true" title="These are leads that are assigned to that you have not assigned to another manager or executive yet. Select a lead to assign it to the right user. When assigning, the people who show up on the list are ones who can handle the chosen lead’s parameters. In case of any discrepancy, check the settings in the Admin Console."/>
	                        </div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " id="leadVedios"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Unassigned Leads</h2>	
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
				<div class="table-responsive">
					
					<table class="table hidden" id="tableTeam">
						<thead>  
							<tr>								
								<!--<th class="table_header">#</th>
								<th class="table_header">Name</th>
								<th class="table_header">Contact</th>
								<th class="table_header"> Designation</th>
								<th class="table_header">Products </th>
								<th class="table_header">Phone</th>		
								<th class="table_header">Email</th>
								<th class="table_header">Location</th>	
								<th class="table_header">Lead Source</th>	
								<th class="table_header">Lead Manager Owner</th>
								<th class="table_header"></th>
								<th class="table_header"></th>-->

								<th class="table_header" width="5%">#</th>
								<th class="table_header" width="20%">Name</th>
								<th class="table_header" width="10%">Products</th>
								<th class="table_header" width="10%">Industry</th>
								<th class="table_header" width="10%">City</th>	
								<th class="table_header" width="10%">Contact</th>
								<th class="table_header" width="10%">Phone</th>							
								<th class="table_header" width="10%">Lead Source</th>	
								<th class="table_header" width="10%">Lead Manager Owner</th>
								<th class="table_header" width="2%"></th>
								<th class="table_header" width="3%"></th>
							</tr>
						</thead>  
						<tbody id="tablebody">
						</tbody>    
					</table>
				</div>
				<div align="center">
					<input type="button" class="btn hidden" onclick="assign_btn()" id="assign1" value="Assign"/>
				</div>
            </div>
        </div>
                       
			<?php require 'manager-leadinfo-add-modal.php' ?>  
			<?php require 'manager-leadinfo-edit-modal.php' ?>  
			<?php require 'manager-leadinfo-view-modal.php' ?>  
			<?php require 'manager-exel-file-upload.php' ?>  
			<input type="hidden" id="manager_lead" value="Assign"/>
		
            <div id="modalstart1" class="modal fade" data-backdrop="static"  data-keyboard="false">
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
			
			<div id="counterList"  class="modal fade" data-keyboard="false"  data-keyboard="false">
				<div class="modal-dialog">
					<div class="modal-content">
						<h4 class="modal-body"> </h4>
					</div>
				</div>
			</div>
        <?php require 'footer.php' ?>

    </body>
</html>
