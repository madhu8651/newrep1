<!DOCTYPE html>
<html lang="en">
    <head>
<?php require 'scriptfiles.php' ?>
<style>
.legend {
    width: 30px;
    height: 30px;
    margin: -5px 10px 0px 0px;
}
.rejected_lead, .legend {
    background-color: rgba(180, 0, 10, 0.20) !important;
}
</style>
<script>
$(document).ready(function(){
    loaddata(); 
});
function loaddata(){
//----------------------------------------------
//--------------my Assigned Table---------------
//----------------------------------------------

  myAssignedTable('myAssignedTable')
}

function myAssignedTable(section){
	$.ajax({
		type: "POST",
		//url: "<?php echo site_url('manager_supportrequestcontroller/get_assigned_tickets'); ?>",
		url: "<?php echo site_url('manager_supportrequestcontroller/unassignedSupportRequest'); ?>",
		dataType:'json',
		success: function(data){
			if(error_handler(data)){
				return;
			}
			loaderHide();
			table(data, section);
		}
	});
}
function teamAssignedTable(section){
	$.ajax({
		type: "POST",
		//url: "<?php echo site_url('manager_supportrequestcontroller/get_assigned_tickets'); ?>",
		url: "<?php echo site_url('manager_supportrequestcontroller/unassignedSupportRequest'); ?>",
		dataType:'json',
		success: function(data){
			if(error_handler(data)){
				return;
			}
			loaderHide();
			table(data, section);
		}
	});
}
function table(data , section){
	$('#'+section+' table').dataTable().fnDestroy();
	
	var row = ""
	for(i=0; i < data.length; i++ ){  
		var rowdata = JSON.stringify(data[i]);
		
		var manager = "",stageManager = "", exec = "", stageExec = ""; rejectedTicket = ''
		//rejected Ticket ------
		if ((data[i].support_repstatus == 3 && data[i].stage_repstatus == 3) || (data[i].support_manager_status == 3 && data[i].stage_manstatus == 3))  {
			rejectedTicket = "class='danger'";
		}
		//manager------
		if(data[i].manager_owner_id == null || data[i].support_manager_status == '1') {
			manager ="<span><font color='#FF8C00'><b>Pending<b></font></span>";
		}else{
			manager = data[i].support_manager;
		}
		//-------- stage manager
		if(data[i].stage_manager_owner_id == null || data[i].stage_manstatus == '1') {
			stageManager ="<span><font color='#FF8C00'><b>Pending<b></font></span>";
		}else{
			stageManager = data[i].stage_man == null ? '--': data[i].stage_man;
		}
		//exec----------
		if(data[i].owner_id == null || data[i].owner_status == '1') {
			exec ="<span><font color='#FF8C00'><b>Pending<b></font></span>";
		}else{
		   exec = data[i].suport_rep;
		}
		
		//------------stage exec
		if(data[i].stage_owner_id == null || data[i].stage_owner_status=='1') {
			stageExec ="<span><font color='#FF8C00'><b>Pending<b></font></span>";
		} else {
			stageExec = data[i].stage_rep ;
		}
		
		//--------------------------------
		//-------------------------------- same condition for team and my support ref. opp assign page
			
			/* if(data[i].manager_owner_id == null || data[i].support_manager_status == '1') {
				row +="<td><span><font color='#FF8C00'><b>Pending<b></font></span></td>";
			}else{
			   row +="<td>"+data[i].support_manager+"</td>";
			}
			if(data[i].stage_manager_owner_id == null || data[i].stage_manstatus =='1') {
				row +="<td><span><font color='#FF8C00'><b>Pending<b></font></span></td>";
			}else{
				row +="<td>"+data[i].stage_man+"</td>";
			}
			//-----------
			if(data[i].owner_id == null || data[i].owner_status =='1') {
				row +="<td><span><font color='#FF8C00'><b>Pending<b></font></span></td>";
			}else{
			   row +="<td>"+data[i].opp_rep+"</td>";
			}
			
			if(data[i].stage_owner_id == null || data[i].stage_owner_status=='1') {
				row +="<td><span><font color='#FF8C00'><b>Pending<b></font></span></td>";
			} else {
				row +="<td>"+data[i].stage_rep+"</td>"; 
			}*/
		//--------------------------------
		//--------------------------------
		row += 	"<tr "+rejectedTicket+">"+
				"<td>" + (i+1) +"</td>"+
				"<td>" + data[i].created_date +"</td>"+
				"<td>" + data[i].ticked_id +"</td>"+
				"<td>" + data[i].product_name +"</td>"+
				"<td>" + (data[i].TAT == null ? '--' : data[i].TAT) +"</td>"+
				"<td>" + (data[i].stage_name == '' ? '--' : data[i].stage_name) +"</td>"+
				"<td>" + manager  +"</td>"+
				"<td>" + stageManager  +"</td>"+
				"<td>" + exec  +"</td>"+
				"<td>" + stageExec  +"</td>"+
				"<td>"+
					"<a href='javascript:void(0)' onclick='viewrow(\""+data[i].request_id+"\","+rowdata+")'>"+
						"<span class='glyphicon glyphicon-eye-open'></span>"+
					"</a>"+
				"</td>"+ 
			"</tr>";
	}
	$('#'+section+' table tbody').html(row);
	$('#'+section+' table').DataTable({
			"aoColumnDefs": [{ "bSortable": false, "aTargets": [10] }]
		});
	$('#'+section+' .legend-wrapper').remove();
	$('#'+section+' .dataTables_length').append('<label class="legend-wrapper" ><div class="legend pull-left" title="Rejected Support Ticket"></div> <b>Rejected Support Ticket</b></label>');

}
function close_fnc(){
    $("#close_req").modal('show');
    var id= $('#req_ids').val();
 var close_sec= '<div class="modal-header">'+
                    '<span class="close"  data-dismiss="modal" aria-hidden="true">&times;</span>'+
                    '<h4 class="modal-title">Close Request</h4>'+
                '</div>'+
                '<div class="modal-body">'+
                    '<div class="row" id="remarks">'+
                        '<div class="col-md-12"><textarea class="form-control" id="close_remarks" placeholder="Enter your remarks ( Mandatory )."></textarea><span class="error-alert"></span></div>'+
                    '</div>'+
                '</div>'+
                '<div class="modal-footer">'+
                    '<button class="btn btn-default" data-dismiss="modal" aria-hidden="true" >Cancel</button>'+
                    '<button class="btn btn-default" onclick="close_save(\''+id+'\')">Submit</button>'+
                '</div>';
    $("#close_req .modal-content").html("").append(close_sec);
}
function close_save(id){
    var req_id=id;
    if($.trim($("#close_remarks").val()) == ""){
        $("#close_remarks").next(".error-alert").text("Remarks is required.");
         $("#close_remarks").focus();
        return;
    }else{
         $("#close_remarks").next(".error-alert").text("");
    }
    var addObj={};
   addObj.remarks=$('#close_remarks').val();
   addObj.req_id=req_id;
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/close_request'); ?>",
        dataType: "json",
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){
          if(error_handler(data)){
            return;
            }
            if(data==1){
               $("#reassign_modal").modal("hide");
               $("#close_req").modal("hide");
                   cancel1();
                   loaddata();        
               }
           }
    });
}
function close_section(){
    $("#remarks").remove();
}
function manager_reassign(){
    var rejectNewLd =[];
    var addObj={};
    $("#tablebody tr input[type=checkbox]").each(function(){
        if($(this).prop("checked") ==true){
            rejectNewLd.push($(this).attr("id"));
       }
    });
    if(rejectNewLd.length  == 0){
        $("#alert_select").modal('show');
        $("#alert_select .modal-body span").text("Please select atleast one Request");
        return false;
    }
    $('#reassign_modal').modal('show');
    var req_id=rejectNewLd.join(",");
    addObj.req_id=req_id;
    $('#requestids').val(req_id);
    console.log(addObj);
    $("#reassign_modal .multiselect").css({
        'background':'url(http://localhost/LconnecttWeb-Integrated/images/hourglass.gif)',
        'background-position':'center',
        'background-size':'30px',
        'background-repeat':'no-repeat'
    });
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/reassign_contacts'); ?>",
        dataType: "json",
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){
            if(error_handler(data)){
                return;
            }
           $("#reassign_modal .multiselect").removeAttr("style");
           var options ="";
           if(data.length > 0){
               
               for(var j=0;j<data.length; j++){
                    if(data[j].sales_module != "0"){
                        options += "<div><label><input type='checkbox' checked value='"+data[j].user_id+"'/> "+ data[j].user_name +"</label></div>"; 
                    }
                    if(data[j].manager_module !="0" ){
                        options += "<div><label><input type='checkbox'  value='"+data[j].user_id+"'/> "+ data[j].user_name +"</label></div>"; 

                    }
                }
                $("#reassign_modal .multiselect").html(options)
           }else{
               $("#reassign_modal .multiselect").html("<center>No User found</center>") 
           }
           
        }
    });

}
function reassign_save(){
var request=$('#requestids').val();
var user_list=[];
    $("#mgrlist div input[type=checkbox]").each(function(){
        if($(this).prop( "checked" ) == true){
            user_list.push($(this).val());
        }
    });
    var addObj={};
    addObj.user_list=user_list;
    addObj.request=request;
     $.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/save_reassign'); ?>",
        dataType: "json",
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){
            if(error_handler(data)){
                return;
            }
        }
    });
}

</script>
</head>
<body class="hold-transition skin-blue sidebar-mini"> 
    <div class="loader">
    <center><h1 id="loader_txt"></h1></center>  
</div>
        <?php require 'demo.php' ?>
        <?php require 'manager_sidenav.php' ?>
        <div class="content-wrapper body-content">
            <div class="col-lg-12 column">
                <div class="row header1">				
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
                        <span class="info-icon">
                            <div >		
                                <img src="<?php echo site_url();?>/images/new/i_off.png" onmouseover="this.src='<?php echo site_url();?>/images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url();?>/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Assigned Request"/>
                            </div>
                        </span>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
                        <h2>Assigned Request</h2>	
                    </div>
                    <div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
                        <div class="addBtns" >
                            <a class="addPlus" onclick="add_request()" >
                                <img src="<?php echo site_url();?>/images/new/Plus_Off.png" onmouseover="this.src='<?php echo site_url();?>/images/new/Plus_ON.png'" onmouseout="this.src='<?php echo site_url();?>/images/new/Plus_Off.png'" width="30px" height="30px"/>
                            </a>
                       </div>
                       <div style="clear:both"></div>
                    </div>
                    <div style="clear:both"></div>
                </div>
				<ul class="nav nav-tabs"> 
					<li class="active" onclick="myAssignedTable('myAssignedTable')">
						<a data-toggle="tab" href="#myAssignedTable" aria-expanded="true">My Support Ticket</a>
					</li>
					<li onclick="teamAssignedTable('teamAssignedTable')">
						<a data-toggle="tab" href="#teamAssignedTable" aria-expanded="false">Team Support Ticket</a>
					</li>
				</ul>
				<div class="tab-content tab_countstat">
					<div id="myAssignedTable" class="tab-pane fade active in">
						<div class="table-responsive">
							<table class="table">
								<thead>  
									<tr>
										<th width="9%">Sl No</th>
										<th width="9%">Creation Date</th>
										<th width="9%">Support Ticket No</th>
										<th width="9%">Product</th>
										<th width="9%">TAT</th>
										<th width="9%">Stage</th>
										<th width="9%">Manager</th>	
										<th width="9%">Stage Manager</th>
										<th width="9%">Exec</th>
										<th width="9%">Stage Exec</th>
										<th width="9%">View</th>
									</tr>
								</thead>  
								<tbody>
								</tbody>    
							</table>
						</div>
					</div>
					<div id="teamAssignedTable" class="tab-pane fade">
						<div class="table-responsive">
							<table class="table">
								<thead>  
									<tr>
										<th width="9%">Sl No</th>
										<th width="9%">Creation Date</th>
										<th width="9%">Support Ticket No</th>
										<th width="9%">Product</th>
										<th width="9%">TAT</th>
										<th width="9%">Stage</th>
										<th width="9%">Manager</th>	
										<th width="9%">Stage Manager</th>
										<th width="9%">Exec</th>
										<th width="9%">Stage Exec</th>
										<th width="9%">View</th>
									</tr>
								</thead>  
								<tbody>
								</tbody>    
							</table>
						</div>
					</div>
				</div>
                
				
                <center>
<!--                     <button  type="button" class="btn btn-default" id="reassign" onclick="manager_reassign()" >Reassign</button>-->
                </center>
            </div>
               <?php require 'manager_add_request.php' ?>
                <div id="view_request" class="modal fade" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <div class="modal-header">
                                <span class="close" onclick="cancel1()">x</span>
                                <h4 class="modal-title"></h4>
                            </div>
                            <input type='hidden' id='req_ids'>
                            <div class="modal-body">								

                            </div>  
                            <input
                            <div class="modal-footer">
                                 <input  type="button" class="btn btn-default" value="Cancel" id="btn1_cancel" onclick="cancel1()">
                                 <input  type="button" class="btn btn-default" value="Close Request" id="close_btn" onclick="close_fnc()"/>;
                            </div>
                        </div>
                    </div>
                </div>
             <?php require 'manager_edit_request.php' ?>
             </div>  
                        <div id="reassign_modal" class="modal fade" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form id="addpopup" class="form">
                                    <input type="hidden" id="asssign_save_section"/>
                                        <div class="modal-header">
                                            <span class="close"  onclick="cancel1()">&times;</span>
                                            <h4 class="modal-title">Request Reassignment</h4>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" id="requestids">
                                            <div class="row targetrow ">
                                                <div class="col-md-2">
                                                    <label for="mgrlist">User list*</label> 
                                                </div>
                                                <div class="col-md-10 multiselect md" id="mgrlist">	
                                                    <ul>
                                                    </ul>
                                                    <span class="error-alert"></span>
                                                </div>	
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="button" class="btn" onclick="reassign_save()" value="Assign">
                                            <input type="button" class="btn" onclick="cancel1()" value="Cancel">
                                        </div>
                                    </form>
                                </div>
                            </div>
			</div>
    <div id="close_req" class="modal fade" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                    
                    
            </div>
        </div>
    </div>
    <div id="alert_select" class="modal fade" data-backdrop="static">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                                               
                    <div class="modal-body">
                     <div class="row">
                       <span></span>
                       <br>
                       <br>
                       <center><input type="button" class="btn" data-dismiss="modal" value="Ok"></center>
                     </div>
                    </div>                            
                </div>
            </div>
        </div>
        <?php require 'footer.php' ?>

    </body>
</html>
