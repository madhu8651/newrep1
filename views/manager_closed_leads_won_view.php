<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require 'scriptfiles.php' ?>
<script>
$(document).ready(function(){	
	pageload();	
});

var finalArray = {};
function assign_btn(){
	var check = [];

	var flagchk=0;
	finalArray['leads'] = [];
    $("#tablebody tr input:checkbox").each(function () {
       
        if($(this).prop("checked")==true){
			$("#modalstart1").modal("show");
			check.push($(this).attr('id'));
			finalArray['leads'].push($(this).attr('id'));
			flagchk=1;
		}		
    });
    if(flagchk==0)	
    {
    	alert("Please select the lead");
		return;	
    }	
	assign_date(check.join(":"));       	
}



var arr={};
var managers = {};
$('#test').val('on');
function assign_date(idval){
	if(idval!=''){
		$("#modalstart1").modal("show");
	}
	arr.lid=idval;

		var multipl2="";
	  	$.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_leadController/get_managerlist_reassign1');?>",
            dataType:'json',
            data:JSON.stringify(finalArray),
            success: function(data) {
            	if(error_handler(data)){
                    	return;
               	}
            	managers = data;
	            for(var i=0;i<data.length; i++){
	            	if(data[i].sales_module=='0' && data[i].manager_module!='0'){
	            		multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'">'+data[i].user_name+' (Manager)<label></li>';
	            	}
	            	if(data[i].sales_module!='0' && data[i].manager_module=='0'){
	            		multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'">'+data[i].user_name+' (Sales)<label></li>';
	            	}
	            	if(data[i].sales_module!='0' && data[i].manager_module!='0'){
	            		multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'">'+data[i].user_name+' (Manager)<label></li>';
	            		multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'">'+data[i].user_name+' (Sales)<label></li>';
	               	}
	            }
	            $(".multiselect2 ul").empty();
	    		$(".multiselect2 ul").append(multipl2);
		    }
        });
	
		
}
	

function assign_save() {
	$('#modalstart1').modal('hide');

	var lead_ids = [];
	finalArray['users'] = [];

	$(".mgrlist_sales, .mgrlist_manager").each(function(){
		if($(this).prop('checked')== true){
			var localObj = {};
			localObj['to_user_id'] = $(this).attr('id');
			localObj['module'] = $(this).val();
			finalArray['users'].push(localObj);
		}
	});
    $("#tablebody tr input:checkbox").each(function () {   
	    if($(this).prop("checked")==true){
			$("#modalstart1").modal("show");
			lead_ids.push($(this).attr('id'));
		}
	});
	if (finalArray['users'].count == 0) {
		alert('Select a user to assign');
		return;
	} else if (finalArray['leads'].count == 0)	{
		alert('Select Lead(s) to assign');
	}

    $.ajax({
            type: "POST",
			url: "<?php echo site_url('manager_leadController/reassign_lost'); ?>",
            data:JSON.stringify(finalArray),
            dataType:'json',
            success: function(data) {
            	if(error_handler(data)){
                    	return;
                }
	          	if(data==1){
	          		$('#modalstart1').modal('hide');
					$('.form-control').val("");         		
	          	}
	          	pageload();               
            }
    });

}

function assign_btn2() {
	var id=$("#logg").val();	
	assign_date(id);
}


	var reporting=[];
	var myLeads =[];
	var teamLeads =[];
	var selectedSection = "myLeads";
	function myLeadsFunc() {
		rendertable(myLeads);
		selectedSection = "myLeads";
	}
	function teamLeadsFunc() {
		rendertable(teamLeads);
		selectedSection = "teamLeads";
	}
	function OtherWonLeads() {
		/* fetching team leads  */
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/OtherWonLeads'); ?>",
			dataType:'json',
			success: function(data) {
				rendertable(data);
				teamLeads = data;
			}
		})
	}
	function rendertable(leads){
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
			var lstate='';
			if(leads[i].reason=='closed_won'){
				lstate="<b style=color:green>Closed Won</b>";					
			}
			
			if(leads[i].user_state=='0'){
			 luser="<b style=color:red>"+leads[i].user_name+"</b>"
			}else{
				luser=leads[i].user_name;
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
			/* removed #M34	22/11/17		Leads	Close won leadslist-> Remove" Check Box" for close won leads.
			<input type='checkbox' name='"+leads[i].lead_rep_owner+"' val = '"+leads[i].lead_manager_owner+"'id='"+leads[i].leadid+"' class='assign_class'/> */
			
			row += "<tr><td>" + (i+1) + "</td><td> " + leads[i].leadname +"</td><td><ul style='padding-left: 5px;'>"+ pList +"</ul></td><td>"+leads[i].industry_name +"</td><td>"+ luser + "</td><td>" + leads[i].city +"</td><td>"+ leads[i].employeephone1 +"</td><td>" + leads[i].leadsource +"</td><td>"+ lstate +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";	
		}	             
		$('#tablebody').parent("table").removeClass('hidden');    
		$('#tablebody').append(row);
		$('#tablebody').parent("table").DataTable({
														"aoColumnDefs": [{ "bSortable": false, "aTargets":[9,10]}]
													});
		$("#tableTeam thead th").each(function(){
				$(this).removeAttr("style")
		});
		$('#tablebody [rel="tooltip"]').tooltip();
		var checkid=[];
	}
function pageload() {
	OtherWonLeads();
	/* fetching my leads  */
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/fetch_won_leads'); ?>",
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
					return;
				}
				leads=data['data'];	
				myLeads = leads;
				rendertable(leads);
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

/* $("#logdetails").hide();
	$("#logdetails1").hide(); 
	$("#oop_details").hide(); */
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



</script>
    </head>
       <body class="hold-transition skin-blue sidebar-mini lcont-lead-page">   
        <?php require 'demo.php' ?>
        <?php require 'manager_sidenav.php' ?>
        <div class="content-wrapper body-content">
            <div class="col-lg-12 column">
				
				<div class="row header1">				
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
	                        <div>	
								<img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="bottom" data-html="true" title="1.‘My Leads’ are all the leads that are owned by you that have successfully converted to customers.<br/>2.‘Team Leads’ are all the leads that managers and executives under you have closed that you do not own."/>
	                        </div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Won Leads</h2>	
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
					<li class="active">
						<a href="#" data-toggle="tab" onclick ="myLeadsFunc()"><h4>My Lead</h4></a>
					</li>
					<li >
						<a href="#" data-toggle="tab" onclick ="teamLeadsFunc()"><h4>Team Lead</h4></a>
					</li>
				</ul>
				<div class="table-responsive">
					<table class="table hidden" id="tableTeam">
						<thead>  
						<tr>	
							<!-- <th class="table_header"><input type="checkbox" name="select_all" id="select_all_leads" onclick="checkAllLeads(this)"></th> 
							<th class="table_header"></th>
							<th class="table_header">#</th>
							<th class="table_header">Name</th>
							<th class="table_header">Contact</th>
							<th class="table_header"> Designation</th>
							<th class="table_header"> Owned_By</th>
							<th class="table_header">Phone</th>		
							<th class="table_header">Email</th>
							<th class="table_header">Location</th>	
							<th class="table_header">Lead Source</th>	
							<th class="table_header">Status</th>
							<th class="table_header"></th>
							<th class="table_header"></th>
							-->
							<th class="table_header" width="5%">#</th>
							<th class="table_header" width="20%">Name</th>
							<th class="table_header" width="10%">Products</th>
							<th class="table_header" width="10%">Industry</th>
							<th class="table_header" width="10%">Owned By</th>
							<th class="table_header" width="10%">City</th>
							<th class="table_header" width="10%">Phone</th>		
							<th class="table_header" width="10%">Lead Source</th>	
							<th class="table_header" width="10%">Status</th>
							<th class="table_header" width="3%"></th>
							<th class="table_header" width="2%"></th>	

						</tr>
						</thead>  
						<tbody id="tablebody">
						</tbody>    
					</table>
				</div>
				<!--<div align="center">
					<input type="button" class="btn hidden" onclick="assign_btn()" id="assign1" value="ReAssign"/>
				</div>-->
            </div>
        </div>
		
			<?php require 'manager-leadinfo-add-modal.php' ?>  
			<?php require 'manager-leadinfo-edit-modal.php' ?>  
			<?php require 'manager-leadinfo-view-modal.php' ?>  		
			<?php require 'manager-exel-file-upload.php' ?> 
			<input type="hidden" id="manager_lead" value="Reassign_not_required"/>
			
			
              <div id="modalstart1" class="modal fade" data-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addpopup" class="form">
                                <div class="modal-header">
                                        <span class="close"  onclick="close_modal()">&times;</span>
                                        <h4 class="modal-title">Lead Assignment</h4>
                                </div>
                            <div class="modal-body">
                                <div class="row targetrow ">
									<div class="col-md-2">
										<label for="mgrlist">User List*</label> 
									</div>
									<div class="col-md-10">											
										<div id="mgrlist" class="multiselect2" >
											<ul>
											</ul>
										</div>
										<span class="error-alert"></span>
									</div>
								</div>
								<label><input type="checkbox" name="select_all_mgr" onclick="checkAllMgrs(this)"> Select All</label>  
							</div>
							<div class="modal-footer">
								<input type="button" class="btn" onclick="assign_save()" value="Assign">
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
