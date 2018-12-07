<!DOCTYPE html>
<html lang="en">
    <head>
<?php require 'scriptfiles.php' ?>
        <style>
             /*-----------Type suggestion-------------*/
.twitter-typeahead{
         width: 100%;
 }
 .md {
     height: 150px;
 }          
.tt-dropdown-menu {
	background: #fff;
	border: 1px solid #ccc;
	border-radius: 5px 0 5px 5px;
	text-align: left;
	width: 300px;
	left: auto !important;
	margin-top: -4px;
	z-index: 4;
	overflow: hidden;
}
.tt-suggestion p {
	cursor: pointer;
	padding: 10px 20px;
	margin: 0;
	display: block;
}
.tt-suggestion {
	padding: 0;
	margin: 0;
}
.tt-suggestion p:hover {
	background-color: #eee;
}
.tt-input {
	background-color: #fff !important;
}

.email_id li {
    display: inline-table;
    margin-right: 5px;
    background: #ccc;
    height: 25px;
    line-height: 25px;
    padding: 0px 5px;
    border-radius: 5px;
	margin-bottom: 5px;
}
        </style>
<script>
$(document).ready(function(){
    loaddata(); 
});

function loaddata(){
      $.ajax({
            type: "POST",
           //url: "<?php echo site_url('manager_supportrequestcontroller/get_inprogress_tickets'); ?>",
            url: "<?php echo site_url('manager_supportrequestcontroller/unassignedSupportRequest '); ?>",
            dataType:'json',
            success: function(data){
              if(error_handler(data)){
                  return;
                }
               
                loaderHide();
				/* data = [
				{
					"created_date":"12-31-1123",
					"ticket_no":"111",
					"product":"aaa",
					"tat":"123123",
					"stage":"1",
					"manager":true,
					"stageManager":true,
					"exec": false,
					"stageExec":false
				},{
					"created_date":"12-31-1123",
					"ticket_no":"222",
					"product":"bbb",
					"tat":"123123",
					"stage":"2",
					"manager":true,
					"stageManager":false,
					"exec": true,
					"stageExec":false
				},{
					"created_date":"12-31-1123",
					"ticket_no":"333",
					"product":"ccc",
					"tat":"123123",
					"stage":"3",
					"manager":false,
					"stageManager":true,
					"exec": false,
					"stageExec":true
				},{
					"created_date":"12-31-1123",
					"ticket_no":"444",
					"product":"ddd",
					"tat":"123123",
					"stage":"4",
					"manager":false,
					"stageManager":false,
					"exec": true,
					"stageExec":true
				}
			]; */
                $('#unassignedTable').dataTable().fnDestroy();
                var row="";
                for(i=0; i < data.length; i++ ){  
				  //For assignment : In assignUserList, Please send selected industry,location and product with key is same as name.//and also send newSupport
				  //var assignment = data[i].product +','+ data[i].opp_cust_id +','+'newSupport';
				  var assignment = 'pending';
					//--------------- manager
					
					
					var managerBtn = '';
					if(data[i].manager_owner_id != '') {
						managerBtn = data[i].support_manager;
					} else {
						assignment = 'pending';//not yet done.. once admin
						managerBtn ="<input type='button' class='btn' onclick='assign(\""+assignment+"\",\"manager\")' value='Assign'>";
					}
					//--------------- stage Manager
					var stageManagerBtn = '';
					if(data[i].stage_manager_owner_id != '') {
						stageManagerBtn = data[i].stage_man;
					} else {
						assignment = 'pending';//not yet done.. once admin
						stageManagerBtn = "<input type='button' class='btn' onclick='assign(\""+assignment+"\",\"stageManager\")' value='Assign'>";
					}
					//------------ Exec
					var execBtn = '';
					if(data[i].owner_id == null && data[i].owner_status == 0) {
						assignment = data[i].request_id +','+data[i].product +','+ data[i].opp_cust_id +','+'supportOwners'+','+data[i].process_type;
					   execBtn = "<input type='button' class='btn' onclick='assign(\""+assignment+"\",\"exec\")' value='Assign'>";
					} else if(data[i].owner_id != null) {
					   execBtn = data[i].suport_rep;
					} else {
					   execBtn = "Pending";
					}
					
					
					//------------stage Exec 
					var stageExecBtn = '';
					if(data[i].stage_owner_id==null && data[i].stage_owner_status==0) {
						assignment = data[i].request_id +','+data[i].product +','+ data[i].opp_cust_id +','+'stageOwners';
					   stageExecBtn = "<input type='button' class='btn' onclick='assign(\""+assignment+"\",\"stageExec\")' value='Assign'>";
					} else if(data[i].stage_owner_id != null) {
					    stageExecBtn = data[i].stage_rep;
					} else {
					   stageExecBtn = "Pending";
					}
					
                  row += 	"<tr>"+
								"<td>" + (i+1) +"</td>"+
								"<td>" + data[i].created_date +"</td>"+
								"<td>" + data[i].ticked_id +"</td>"+
								"<td>" + data[i].product_name +"</td>"+
								"<td>" + (data[i].TAT == null ? '--' : data[i].TAT) +"</td>"+
								"<td>" + (data[i].stage_name == '' ? '--' : data[i].stage_name) +"</td>"+
								"<td>" + managerBtn +"</td>"+
								"<td>" + stageManagerBtn +"</td>"+
								"<td>" + execBtn +"</td>"+
								"<td>" + stageExecBtn +"</td>"+
								"<td>"+
									"<a href='javascript:void(0)' onclick='viewrow(\""+data[i].request_id+"\")'>"+
										"<span class='glyphicon glyphicon-eye-open'></span>"+
									"</a>"+
								"</td>"+ 
							"</tr>";
                }
                $('#unassignedTable tbody').html("").append(row);
                $('#unassignedTable').DataTable({
					"aoColumnDefs": [{ "bSortable": false, "aTargets": [10] }]
				});
            }
        });
  }
  function request_assignment(id){
  $('#assign_request').modal('show');
  $('#request_ticket_id').val(id);
  var reuest={};
  reuest.id=id;
   $.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/assign_request'); ?>",
        dataType: "json",
        data    : JSON.stringify(reuest),
        cache : false,
        success : function(data){
            if(error_handler(data)){
                return;
            }
            if(data.length==0){
                alert("No user matches the parameters of the support ccyle");
            }else{
            var select = $("#usr_list"),options="";
                select.empty();   
                 options += "<div><label><input class='selectAll' type='checkbox' onchange='chkAll(\"usr_list\")' /> Auto Allocation </label></div>";  
                for(var i=0; i<data.length; i++){
                    if(data[i].manager_module != "0"){
                      options += "<div class='li'><label><input type='checkbox' value='"+data[i].user_id+"-mgr'/> "+ data[i].user_name+" - ( Manager ) </label></div>";  
                    }
                    if(data[i].sales_module != "0"){
                      options += "<div class='li'><label><input type='checkbox' value='"+data[i].user_id+"-sales'/> "+ data[i].user_name+" - ( Executive )</label></div>";  
                    }    
                }
                select.append(options);
                $("#usr_list div.li input[type=checkbox]").each(function(){
                    $(this).change(function(){
                         $(this).closest(".multiselect").find(".selectAll").prop("checked", false)
                    });
                });
        }
        }
    });
  
  }
 function viewrow(obj){
	$("#view_request").modal('show');
    $("#view_request .modal-title").text(obj.request_name);
    $("#req_ids").val(obj.request_id);
    var row = '';
    row = '<div class="row">'+
            '<div class="col-md-2 apport_label">'+
                '<label>Request Name</label>'+
            '</div>'+
            '<div class="col-md-4">'+
                '<label>'+obj.request_name+'</label>'+
            '</div>'+
            '<div class="col-md-2 apport_label">'+
                '<label>Request Id</label>'+
            '</div>'+
            '<div class="col-md-4">'+
                '<label>'+obj.request_user_id+'</label>'+
            '</div>'+
        '</div>'; 
    row += '<div class="row">'+
            '<div class="col-md-2 apport_label">'+
                '<label>Product</label>'+
            '</div>'+
            '<div class="col-md-4">'+
                '<label>'+obj.prod+'</label>'+
            '</div>'+
            '<div class="col-md-2 apport_label">'+
                '<label>Industry</label>'+
            '</div>'+
            '<div class="col-md-4">'+
                '<label>'+obj.ind+'</label>'+
            '</div>'+
        '</div>'; 
    row += '<div class="row">'+
            '<div class="col-md-2 apport_label">'+
                '<label>Criticality</label>'+
            '</div>'+
            '<div class="col-md-4">'+
                '<label>'+obj.cricticality+'</label>'+
            '</div>'+
            '<div class="col-md-2 apport_label">'+
                '<label>Committed TAT</label>'+
            '</div>'+
            '<div class="col-md-4">'+
                '<label>'+moment(obj.tat , "DD/MM/YYYY").format("lll")+'</label>'+
            '</div>'+
        '</div>';
        var name ="",number="";
    /*---------------------------------*/
    for(i=0; i<obj.contact_details.length; i++){
         name += '<li>'+ obj.contact_details[i].contact_name +'</li>';
         number += '<li>'+obj.contact_details[i].contact_number+'</li>';
    }
    row += '<div class="row">'+
            '<div class="col-md-2 apport_label">'+
                '<label>Contact Name</label>'+
            '</div>'+
            '<div class="col-md-4">'+
                '<ul style="padding: 0px 0px 0px 15px;">'+name+'</ul>'+
            '</div>'+
            '<div class="col-md-2 apport_label">'+
                '<label style="white-space: nowrap;">Contact Number</label>'+
            '</div>'+
            '<div class="col-md-4">'+
                '<ul style="padding: 0px 0px 0px 15px;">'+number+'</ul>'+
            '</div>'+
        '</div>'; 

    $("#view_request .modal-body").html("").html(row)
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
    $("#unassignedTable tbody tr input[type=checkbox]").each(function(){
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
function request_save(){
var activityowner=[];
    $("#usr_list div input[type=checkbox]").each(function(){
        if($(this).prop( "checked" ) == true){
            activityowner.push($(this).val());
        }
    });
    if(activityowner.length <= 0 ){
        $("#usr_list").next(".error-alert").text("Select atleast one owner.");
        return;
    }else{
        $("#usr_list").next(".error-alert").text("");
    }
    var addobj={};
    addobj.ownerlist=activityowner;
    addobj.req_id=$('#request_ticket_id').val();
    console.log(addobj);
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/save_support_request'); ?>",
        dataType: "json",
        data    : JSON.stringify(addobj),
        cache : false,
        success : function(data){
            if(error_handler(data)){
                return;
            }
            if(data==1){
                alert("request has been assigned succesfully");
                loaddata();
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
                                <img src="<?php echo site_url();?>/images/new/i_off.png" onmouseover="this.src='<?php echo site_url();?>/images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url();?>/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Unassigned Request"/>
                            </div>
                        </span>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
                        <h2>Unassigned Request</h2>	
                    </div>
                    <div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
                        <div class="addBtns" >
                            <a class="addPlus" id="addSupport" >
                                <img src="<?php echo site_url();?>/images/new/Plus_Off.png" onmouseover="this.src='<?php echo site_url();?>/images/new/Plus_ON.png'" onmouseout="this.src='<?php echo site_url();?>/images/new/Plus_Off.png'" width="30px" height="30px"/>
                            </a>
                       </div>
                       <div style="clear:both"></div>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <div class="table-responsive">
                        <table class="table" id="unassignedTable">
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
                                                    <label for="mgrlist1">User list*</label> 
                                                </div>
                                                <div class="col-md-10 multiselect md" id="mgrlist1">	
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
