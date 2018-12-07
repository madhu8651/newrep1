<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require 'scriptfiles.php' ?>
        <script src="<?php echo site_url(); ?>js/prefixfree.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPquJYJq7KSiQPchdgioEVs-xOY4ERUdE&libraries=places" async defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>




 <script>
$(document).ready(function(){
	pageload();	
});

function reject_btn(section){
	/* from the lending page */
	if( section== "multiple"){
		if(!($("#tablebody tr input[type=checkbox]").is(':checked'))){
			alert("Please select atleast one lead for reject.");
			return;		
		}else{
			$("#reject_save_section").val("").val("multiple");
			$("#modalstart1").modal("show");
		}
	}
	/* from the view info popup section */
	if( section== "single"){
		$("#reject_save_section").val("").val("single");
		$("#modalstart1").modal("show");			
	}
	
}
/* from the lending page */
function assign_btn(){
	var check = [];
	if(!($("#tablebody tr input[type=checkbox]").is(':checked'))){
		alert("Please select atleast one lead for accept.");
		return;
	}
	
    $("#tablebody tr input:checkbox:checked").each(function () {
        check.push($(this).attr('id'));
    });	

    if(check.length>0){    	
    	assign_date(check.join(":"), "");	
    } 	
}

/* from the view info popup section */
function assign_btn2() {
	var id=$("#logg").val();	
	assign_date(id,"");
}

function assign_save(){
	var check = [];
	var notes = $.trim($("#notes").val());
	
	/* from the lending page */
	if( $("#reject_save_section").val() == "multiple"){
		$("#tablebody tr input:checkbox:checked").each(function () {
			check.push($(this).attr('id'));
		});
		if($.trim($("#notes").val()) == ""){
			$("#notes").closest("div").find(".error-alert").text("Remarks is required.");
			return;
		}else{
			$("#notes").closest("div").find(".error-alert").text("");
		}
		if(check.length>0){   	
			assign_date(check.join(":"), notes);	
		}
	}

/* from the view info popup section */
	if( $("#reject_save_section").val() == "single"){
		if($.trim($("#notes").val()) == ""){
			$("#notes").closest("div").find(".error-alert").text("Remarks is required.");
			return;
		}else{
			$("#notes").closest("div").find(".error-alert").text("");
		}
		var id=$("#logg").val(); 	
		assign_date(id, notes);	
		
	}
		
    
}


function assign_date(idval,note){
	var arr = {};
	arr.lid = idval;
	loaderShow();
	if(note == ""){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/lead_accept'); ?>",
			data:JSON.stringify(arr),
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
					return;
				}
				$('.form-control').val("");
				var totalLeadCount = idval.split(':').length;
				// var failureLeadCount = data.length;
				var successLeadCount = data.length;					
				var finalStr = successLeadCount + " of " + totalLeadCount + " Lead(s) accepted";
				alert(finalStr);
				pageload();
			}
		});
	}else{
		arr.note = note;
		$.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_leadController/lead_reject'); ?>",
            data:JSON.stringify(arr),
            dataType:'json',
            success: function(data) {
				if(error_handler(data)){
					return;
				}
          		if(data == true){         		
					$('.modal .form-control').val("");
					pageload();          		
          		}           
            }
    	});
	}
    
}


 function pageload() {
			$(".modal").each(function(){
				$(this).modal("hide");
			})
			$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('manager_leadController/fetch_received_lead'); ?>",
	            dataType:'json',
	            success: function(data) {
					if(error_handler(data)){
						return;
					}
					loaderHide();
					 $('#tablebody').parent("table").dataTable().fnDestroy();
					$('#tablebody').html("");
					
					if(data.length != 0){
						$('#assign1').removeClass('hidden');
					}else{
						$('#assign1').addClass('hidden');
					}
					mainData = data;
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('manager_leadController/checkManagerAcceptReject'); ?>",
						dataType:'json',
						success: function(data) {
							if(error_handler(data)){
								return;
							}
							/*---------------accept and reject btn show hide*/
							if(mainData.length != 0){
								$('#assign2').removeClass('hidden');
								if(data == 1){
									$('#assign2').addClass('hidden');
									$("#leadview .lead_recieved_btn").addClass('hidden');
								}else{
									$('#assign2').removeClass('hidden');
									$("#leadview .lead_recieved_btn").removeClass('hidden');
								}
							}else{
								$('#assign2').addClass('hidden');/* hide reject button by defalt */
							}
							
							/* if(data == 1){
								$('#assign2').addClass('hidden');
								$("#leadview .lead_recieved_btn").addClass('hidden');
							}else{
								$('#assign2').removeClass('hidden');
								$("#leadview .lead_recieved_btn").removeClass('hidden');
							} */
						}
					})
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
						if(data[i].leadstate==0){
							lstate="<b style=color:blue>Created</b>";					
						}
						else if(data[i].leadstate==3){
							lstate="<b style=color:green>Received</b>";
						}
						else if(data[i].leadstate==4){
							lstate="<b style=color:red>Rejected</b>";
						}
						
						var pList = "";
						var pList1 = "";
						if(data[i].product_names != "-"){
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
							
						row += "<tr><td> <input type='checkbox' name='foo[]' id='"+data[i].leadid+"' class='assign_class'/> " + (i+1) + "</td><td>" + data[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>"+ data[i].industry_name +"</td><td>"+ data[i].city +"</td><td>"+ data[i].employeename +"</td><td>" + data[i].employeephone1 +"</td><td>"+ data[i].leadsource +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td></tr>";	
						/*<td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td>		*/
					}					
	              	$('#tablebody').parent("table").removeClass('hidden');    
					$('#tablebody').append(row);
					$('#tablebody').parent("table").DataTable({
						"aoColumnDefs": [{ "bSortable": false, "aTargets": [8] }]
					});
					
					
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

				for(var i=0;i<data.length; i++)
				{
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
  function close_modal(){
		$('#modalstart1').modal('hide');	
		$('.modal .form-control').val("");
		$("#modal_upload").modal("hide");
		$('#modal_upload #files').val("");
                $('.leadsrcname').html("");
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

function accept_response_modal(){
$('#accept_response_modal').modal('hide');
$('html').addClass('modal-open');

}
/*  */


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
								<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-toggle="tooltip" data-placement="bottom" data-html="true" title="View the leads assigned to you by a manager. Accept a lead to assign it to another manager or executive that you handle, or you can decline it with a reason. Click on the <img src='<?php echo site_url(); ?>images/new/Plus_Off.png' width='20px' height='20px' /> button on the top right to add a new lead. Click the <img src='<?php echo site_url(); ?>images/new/Xl_Off.png' width='20px' height='20px' /> to upload the leads in bulk. The new leads will automatically go to your ‘Unassigned’ leads."/>

	                        </div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Leads Received</h2>	
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
					<table class="table hidden" id="tableTeam" >
						<thead>  
						<tr>	
								<th class="table_header" width="5%">#</th>
								<th class="table_header" width="20%">Name</th>
								<th class="table_header" width="12%">Products</th>
								<th class="table_header" width="12%">Industry</th>
								<th class="table_header" width="12%">City</th>	
								<th class="table_header" width="12%">Contact</th>
								<th class="table_header" width="12%">Phone</th>							
								<th class="table_header" width="10%">Lead Source</th>					
								<th class="table_header" width="5%"></th>
						</tr>
						</thead>  
						<tbody id="tablebody">
						</tbody>    
					</table>
				</div>
				<div align="center">
					<input type="button" class="btn hidden" id="assign1" onclick="assign_btn()" value="Accept"/>
					<input type="button" class="btn hidden" id="assign2" onclick="reject_btn('multiple')" value="Reject"/>
				</div>
            </div>
                       
				<?php require 'manager-leadinfo-add-modal.php' ?> 
				
				<?php require 'manager-leadinfo-view-modal.php' ?>  
				<?php require 'manager-exel-file-upload.php' ?>  
				<input type="hidden" id="manager_lead" value="Accept"/>
				
           
				<div id="modalstart1" class="modal fade" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addpopup" class="form">
								<input type="hidden" id="reject_save_section"/>
                                <div class="modal-header">
                                        <span class="close"  onclick="close_modal()">&times;</span>
                                        <h4 class="modal-title">Please enter the remarks</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
										<div class="col-md-2">
											<label for="notes">Remark*</label>
										</div>
										<div class="col-md-10">
											<textarea id="notes" class="form-control"></textarea>											
											<span class="error-alert"></span>
										</div>
									</div>                                    
								</div>
								<div class="modal-footer">
									 <input type="button" class="btn" onclick="assign_save()" value="Save">
									<input type="button" class="btn" onclick="close_modal()" value="Cancel">
								</div>
							</form>
						</div>
					</div>
				</div>
				
              <div id="accept_response_modal" class="modal fade" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                            	<span class="close" onclick="accept_response_modal()">&times;</span>
                            </div>
                            <div class="modal-body">
                            	<label id="accept_response"></label>
                            </div>
						</div>
							<div class="modal-footer">
								<input type="button" class="btn" onclick="accept_response_modal()" value="Cancel">
							</div>
						</form>
					</div>
				</div>
			</div>

      
        <?php require 'footer.php' ?>

    </body>
</html>
