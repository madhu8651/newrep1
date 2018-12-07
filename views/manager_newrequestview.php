<!DOCTYPE html>
<html lang="en">
    <head>
<?php require 'scriptfiles.php' ?>
        <style>
             /*-----------Type suggestion-------------*/
.twitter-typeahead{
         width: 100%;
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
            url: "<?php echo site_url('manager_supportrequestcontroller/receviedSupportForUser'); ?>",
            dataType:'json',
            success: function(data){
              if(error_handler(data)){
                  return;
              }
                loaderHide();
                $('#tablebody').parent("table").dataTable().fnDestroy();
                var row="";
                for(i=0; i < data.length; i++ ){ 
                  var rowdata = JSON.stringify(data[i]);
                  var link = "<?php echo site_url('manager_supportrequestcontroller/stage_view/'); ?>"+data[i].request_id;
                  row += "<tr><td><input type='checkbox' id='"+data[i].request_id+"'/><td>" + data[i].SupportRequestName +"</td><td>" + data[i].ticketId +"</td><td>" + data[i].supportAssociatedName +"</td><td>" + data[i].processTypeName +"</td><td>" + data[i].productName +"</td></td><td>" + data[i].industryName +"</td><td>" + data[i].TAT+"</td><td><a  onclick='editRow("+rowdata+")' href='#'><span class='glyphicon glyphicon-pencil'></span></a></td><td><a href='"+link+"'><span class='glyphicon glyphicon-eye-open'></span></a></td></tr>";
                }
                $('#tablebody').html("").append(row);
                $('#tablebody').parent("table").DataTable({
                "aoColumnDefs": [{ "bSortable": false, "aTargets": [7] }]
               });
            }
        });
  }

function request_accept(status){
     var acceptNewLd =[];
      var addObj={};
      if(status=="single" ){
        var accept_id=($('#lead_id').val());
        acceptNewLd.push(accept_id);
        addObj.reject_data=acceptNewLd;
    }
    if(status=="multiple" ){
        $("#tablebody tr input[type=checkbox]").each(function(){
            if($(this).prop("checked") ==true){
                acceptNewLd.push($(this).attr("id"));
            }
        });
        if(acceptNewLd.length  == 0){
            $("#alert_select").modal('show');
            $("#alert_select .modal-body span").text("Please select atleast one Request");
            return false;
        }
        addObj.reject_data=acceptNewLd;
    }
   var total=acceptNewLd.length;
   loaderShow();
     $.ajax({
    type : "POST",
    url : "<?php echo site_url('manager_supportrequestcontroller/accept_multiple'); ?>",
    dataType: "json",
    data    : JSON.stringify(addObj),
    cache : false,
    success : function(data){
      if(error_handler(data)){
        return;
      }
     $('#leadview').hide();
     var accept=data.length;
     if(accept==0){
         loaddata();
     }else{
         alert("out of "+total+" leads "+(total-accept)+" are accepted");
         cancel1();
         loaddata();
    }
    }
});
    
}

function request_reject(status){
    if(status=="single" ){
        $("#reject_lid").modal("show");
        var selected_lead = $('#lead_id').val();
        $('#reject_value').val(selected_lead);
    }
    
   if(status=="multiple" ){
       var rejectNewLd =[];
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
        $("#reject_lid").modal("show");
        var selected_leadid = rejectNewLd.toString();
        $('#reject_value').val(selected_leadid);
    }
}
function final_rejection(){
       $("#reject_lid").modal("show");
        if($("#rej_remarks").val()==""){
            $("#rej_remarks").closest("div").find("span").text("Reject Remarks is required.");
            $("#rej_remarks").focus();
            return;
        }else{
            $("#rej_remarks").closest("div").find("span").text("");
        }
        var addObj={};
        addObj.rej_remarks = $('#rej_remarks').val();
        addObj.reject_data=$('#reject_value').val();
        loaderShow();
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_supportrequestcontroller/reject_multiple'); ?>",
        dataType: "json",
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){
          if(error_handler(data)){
            return;
            }
            if(data==1){
               $("#reject_lid").modal("hide");
               cancel1();
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
                                <img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="New Leads List"/>
                            </div>
                        </span>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
                        <h2>New Requests</h2>	
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
                                    <th class="table_header"></th>
                                    <th class="table_header"></th>
                                </tr>
                                </thead>  
                                <tbody id="tablebody">
                                </tbody>    
                        </table>
                </div>
                <center>
                     <button  type="button" class="btn btn-default" id="reassign" onclick="request_accept('multiple')" >Accept</button>
                     <button  type="button" class="btn btn-default" id="reassign" onclick="request_reject('multiple')" >Reject</button>
                </center>
            </div>
               <?php require 'manager_add_request.php' ?>
                <div id="view_request" class="modal fade" data-backdrop="static">
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <div class="modal-header">
                                <span class="close" onclick="cancel1()">x</span>
                                <h4 class="modal-title"></h4>
                            </div>
                            <div class="modal-body">								

                            </div>                  
                            <div class="modal-footer">
                                 <button  type="button" class="btn btn-default" id="btn1_cancel" onclick="cancel1()" >Cancel</button>
                                 <button  type="button" class="btn btn-default" id="reassign" onclick="cancel1()" >Reassign</button>

                            </div>
                        </div>
                    </div>
                </div> 
                         <?php require 'manager_edit_request.php' ?>

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
            <div id="reject_lid" class="modal fade" data-backdrop="static" >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form  class="form" action="#" method="post" name="adminClient">
                            <div class="modal-header">
                                    <span class="close" onclick="cancel1()">x</span>	
                                    <h4 class="modal-title">Choose</h4>
                            </div>	
                            <input type="hidden" id="reject_value" />
                            <div class="modal-body">							
                                    <div class="row" id="reject_remarks">	
                                        <div class="col-md-12">
                                            <label>Remarks*</label>
                                            <textarea name="Remarks" class="form-control" id="rej_remarks"></textarea> 
                                            <span class="error-alert"></span>
                                        </div>
                                    </div>							
                            </div>
                            <div class="modal-footer">
                                    <input type="button" class="btn" onclick="final_rejection()" value="Reject">
                                    <input type="button" class="btn" onclick="cancel_rej()" value="Cancel" >
                            </div>
                        </form>
                    </div>
                </div>
		</div>
           
             </div>        
        <?php require 'footer.php' ?>

    </body>
</html>
