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
    $('#tablebody').parent("table").dataTable().fnDestroy();
      $.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_supportrequestcontroller/get_assigned_tickets'); ?>",
            dataType:'json',
            success: function(data){
              if(error_handler(data)){
                  return;
              }
                loaderHide();
                $('#tablebody').parent("table").dataTable().fnDestroy();
                var row="";
                for(i=0; i < data.length; i++ ){ 
                    var ticketowner ='';
                    if(data[i].rep_owner=="-"){              
                       ticketowner = "<span><font color='#cc0000'><b>Pending<b></font></span>";              
                    }
                    else{
                        ticketowner = data[i].rep_owner;
                    }
                  var rowdata = JSON.stringify(data[i]);
                  var link = "<?php echo site_url('manager_supportrequestcontroller/stage_view/'); ?>"+data[i].request_id;
                  row += "<tr><td><input type='checkbox' id='"+data[i].request_id+"'/><td>" + data[i].request_name +"</td><td>" + data[i].request_user_id +"</td><td>" + data[i].oppo_cust_name +"</td><td>" + data[i].lookup_value +"</td><td>" + data[i].prod +"</td></td><td>" + data[i].ind +"</td><td>" + data[i].tat+"</td><td>" + data[i].user_name+"</td><td>" + ticketowner+"</td><td><a  onclick='editRow("+rowdata+")' href='#'><span class='glyphicon glyphicon-pencil'></span></a></td><td><a href='"+link+"'><span class='glyphicon glyphicon-eye-open'></span></a></td></tr>";
                }
                $('#tablebody').html("").append(row);
                $('#tablebody').parent("table").DataTable({
                "aoColumnDefs": [{ "bSortable": false, "aTargets": [7] }]
               });
            }
        });
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
                                <img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="New Leads List"/>
                            </div>
                        </span>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
                        <h2>Assigned Request</h2>	
                    </div>
                    <div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
                        <div class="addBtns" >
                            <a class="addPlus" onclick="add_request()" >
                                <img src="/images/new/Plus_Off.png" onmouseover="this.src='/images/new/Plus_ON.png'" onmouseout="this.src='/images/new/Plus_Off.png'" width="30px" height="30px"/>
                            </a>
                       </div>
                       <div style="clear:both"></div>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <div class="table-responsive">
                        <table class="table" id="tableTeam">
                                <thead>  
                                <tr>	
                                    <th class="table_header">#</th>
                                    <th class="table_header">Request Name</th>
                                    <th class="table_header">Ticket Number</th>
                                    <th class="table_header">Opportunity/Customer</th>
                                    <th class="table_header">Process Type</th>
                                    <th class="table_header">Product</th>
                                    <th class="table_header">Industry</th>
                                    <th class="table_header">Committed TAT</th>	
                                    <th class="table_header">Manager Owner</th>
                                    <th class="table_header">Ticket Owner</th>
                                    <th class="table_header"></th>
                                    <th class="table_header"></th>
                                </tr>
                                </thead>  
                                <tbody id="tablebody">
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
