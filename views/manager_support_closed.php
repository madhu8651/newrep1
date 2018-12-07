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
textarea {
   resize: none;
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
            url: "<?php echo site_url('manager_supportrequestcontroller/closed_tickets'); ?>",
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
                  row += "<tr><td><input class='request_id' type='radio' value='"+data[i].request_id+" 'name='"+data[i].request_id+"'/><td>" + data[i].request_name +"</td><td>" + data[i].request_user_id +"</td><td>" + data[i].oppo_cust_name +"</td><td>" + data[i].lookup_value +"</td><td>" + data[i].prod +"</td></td><td>" + data[i].ind +"</td><td>" + data[i].tat+"</td><td><a href='"+link+"'><span class='glyphicon glyphicon-eye-open'></span></a></td></tr>";
                }
                $('#tablebody').html("").append(row);
                $('#tablebody').parent("table").DataTable({
                "aoColumnDefs": [{ "bSortable": false, "aTargets": [7] }]
               });
            }
        });
  }
  function reopen_request(){
        
        $('#reopen_req').find('textarea').val('');
        $('#reopen_req').find('.error-alert').text('');
        var flg = false;
        $('#tablebody tr input.request_id').each(function(){
          if($(this).prop('checked') == true){
              flg = true;
          }
        })
        if(flg == true){
            $('#reopen_req').modal('show'); 
        }else{
            $('#reopen_req').modal('hide'); 
            alert('Need to select atleast one request.')
        }
  }
  function cancel(modalDiv){
     $("#"+modalDiv).modal('hide');
  }
  function submit_note(){
      var request_id;
      var note = $("#request_note").val().trim();
      if(note == ""){
          $("#request_note").next('.error-alert').text('Remarks is required.');
          return;
      }else if(!comment_validation(note)){
          $("#request_note").next('.error-alert').text('No special cherecter excepct ( $ & : ) ( # @ _ . , + % ? - )');
          return;
      }else{
          $("#request_note").next('.error-alert').text('');
      }
     
      $('#tablebody tr input.request_id').each(function(){
          if($(this).prop('checked') == true){
             request_id=$(this).val();
          }
      })
      var obj ={};
      obj.note = note;
      obj.reqst_id = request_id;
      $.ajax({
          type:'POST',
          url:'<?php echo site_url('manager_supportrequestcontroller/reopen_request'); ?>',
          datatype:'json',
          data : JSON.stringify(obj),
          cache : false,
          success:function(data){
              if(data== 1){
                  alert("Request has been reopened successfully!!");
                  $('#reopen_req').modal('hide');
                  loaderHide();
                  loaddata();
              }
          },
          error:function(response){
              
          }
      })
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
                        <h2>Closed Request</h2>	
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
                                    <th class="table_header"></th>
                                    <th class="table_header">Request Name</th>
                                    <th class="table_header">Ticket Number</th>
                                    <th class="table_header">Opportunity/Customer</th>
                                    <th class="table_header">Process Type</th>
                                    <th class="table_header">Product</th>
                                    <th class="table_header">Industry</th>
                                    <th class="table_header">Committed TAT</th>		
                                    <th class="table_header"></th>
                                </tr>
                                </thead>  
                                <tbody id="tablebody">
                                </tbody>    
                        </table>
                </div>
                <div align="center">
                        <input type="button" class="btn" onclick="reopen_request()" id="assign1" value="Reopen"/>
                </div>
            </div>
               <?php require 'manager_add_request.php' ?>
                <?php require 'manager_edit_request.php' ?>

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
             </div>
        <div id="reopen_req" class="modal fade" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form">
                        <div class="modal-header">
                            <span class="close"  onclick="cancel('reopen_req')">&times;</span>
                            <h4 class="modal-title">Reopen Request</h4>
                        </div>
                        <div class="modal-body">	
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea class="form-control" id="request_note" placeholder="Remarks (Mandatory" rows="5">
                                        
                                    </textarea>
                                    <span class="error-alert"></span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn" onclick="submit_note()" value="Save">
                            <input type="button" class="btn" onclick="cancel('reopen_req')" value="Cancel" >
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php require 'footer.php' ?>

    </body>
</html>
