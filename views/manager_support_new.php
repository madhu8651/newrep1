<!DOCTYPE html>
<html lang="en">
    <head>
	<?php require 'scriptfiles.php' ?>
	  
<script>
$(document).ready(function(){
    loaddata();
});
function loaddata(){
	$("#decline_").hide();//Decline button hide
	$("#receivedTable").dataTable().fnDestroy();
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('manager_supportrequestcontroller/get_ticketDetails'); ?>",
		dataType:'json',
		success: function(data){
			if(error_handler(data)){
				return;
			}
			loaderHide();
			
			
			data = [
				{
					"created_date":"123123",
					"ticket_no":"111",
					"product":"aaa",
					"tat":"123123",
					"stage":"1",
					"manager":true,
					"stageManager":true
				},{
					"created_date":"123123",
					"ticket_no":"222",
					"product":"bbb",
					"tat":"123123",
					"stage":"2",
					"manager":true,
					"stageManager":false
				},{
					"created_date":"123123",
					"ticket_no":"333",
					"product":"ccc",
					"tat":"123123",
					"stage":"3",
					"manager":false,
					"stageManager":true
				},{
					"created_date":"123123",
					"ticket_no":"444",
					"product":"ddd",
					"tat":"123123",
					"stage":"4",
					"manager":false,
					"stageManager":false
				}
			];
			if(data.length > 0){
				$("#decline_").show();//Decline button show
			}
			$('#receivedTable').dataTable().fnDestroy();
			var row="";
			for(i=0; i < data.length; i++ ){ 
			  var rowdata = JSON.stringify(data[i]);
			  var link = "<?php echo site_url('manager_supportrequestcontroller/stage_view/'); ?>"+data[i].request_id;
			  
			  var managerBtn = '<input type="button" class="btn btn-default" onclick="managerAccept()" value="Accept">';
			  var stageManagerBtn = '<input type="button" class="btn btn-default" onclick="stageManagerAccept()" value="Accept">';
			  
			  row += "<tr>"+
						"<td><input type='radio' value="+data[i].ticket_no+"/></td>"+
						"<td>" + data[i].created_date +"</td>"+
						"<td>" + data[i].ticket_no +"</td>"+
						"<td>" + data[i].product +"</td>"+
						"<td>" + data[i].tat +"</td>"+
						"<td>" + data[i].stage +"</td>"+
						"<td>" + (data[i].manager == true ? managerBtn : '') +"</td>"+
						"<td>" + (data[i].stageManager == true ? stageManagerBtn : '') +"</td>"+
						"<td>"+
							"<a href='"+link+"'>"+
								"<span class='glyphicon glyphicon-eye-open'></span>"+
							"</a>"+
						"</td>"+
					"</tr>";
			}
			$('#receivedTable tbody').html("").append(row);
			$('#receivedTable').DataTable({
				"aoColumnDefs": [{ "bSortable": false, "aTargets": [6,7,8] }]
			});
			
			/* "<td>"+
				"<a  onclick='editRow("+rowdata+")' href='#'>"+
					"<span class='glyphicon glyphicon-pencil'></span>"+
				"</a>"+
			"</td>"+ */
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
                                <img src="<?php echo site_url();?>/images/new/i_off.png" onmouseover="this.src='<?php echo site_url();?>/images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url();?>/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="New Requests"/>
                            </div>
                        </span>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
                        <h2>New Requests</h2>	
                    </div>
                    <div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
                        <div class="addBtns" >
                            <a class="addPlus" id="addSupport">
                                <img src="<?php echo site_url();?>/images/new/Plus_Off.png" onmouseover="this.src='<?php echo site_url();?>/images/new/Plus_ON.png'" onmouseout="this.src='<?php echo site_url();?>/images/new/Plus_Off.png'" width="30px" height="30px"/>
                            </a>
                       </div>
                       <div style="clear:both"></div>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <div class="table-responsive">
					<table class="table" id="receivedTable">
						<thead>  
							<tr>	
								<th width="11%">Sl No</th>
								<th width="11%">Creation Date</th>
								<th width="11%">Support Ticket No</th>
								<th width="11%">Product</th>
								<th width="11%">TAT</th>
								<th width="11%">Stage</th>
								<th width="11%">Manager</th>		
								<th width="11%">Stage Manager</th>
								<th width="11%">View</th>
							</tr>
						</thead>  
						<tbody>
						</tbody>    
					</table>
                </div>
                <center>
                     <!--<button  type="button" class="btn btn-default" id="reassign" onclick="request_accept('multiple')" >Accept</button>-->
                     <button  type="button" class="btn btn-default none" id="decline_" onclick="request_reject('multiple')" >Decline</button>
                </center>
            </div>
               <?php require 'manager_add_request.php' ?>
			   <?php require 'manager_edit_request.php'?>
			   
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
