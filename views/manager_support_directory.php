<!DOCTYPE html>
<html lang="en">
    <head>
<?php require 'scriptfiles.php' ?>
        <style>
            .border{
				    border: 1px solid #dee2e6!important;
					min-height:200px;
			}
        </style>
<script>
$(document).ready(function(){
    
});
function criteria(elem){
	$(elem).closest('div.row').find('.selection').remove();
	//customerName customerId phoneNo emailId contactName ticketNo
	if( $(elem).val() !="" ){
		
		html = '<div class="col-md-2 selection">'+
					$("#searchCriteria option:selected").text() +
				'</div>'+
				'<div class="col-md-2 selection">'+
					'<input type="text" class="form-control" id="searchInput"/>'+
				'</div>'+
				'<div class="col-md-2 selection">'+
					'<button class="btn" onclick="search()">Search</button>'+
				'</div>'
		$(elem).closest('div.row').append(html)
	}
		
}
  function search(){
	  var input = $("#searchInput").val().trim();
	  return;
	  $.ajax({
          type:'POST',
          url:'<?php echo site_url('manager_supportrequestcontroller/reopen_request'); ?>',
          datatype:'json',
          data : JSON.stringify(obj),
          cache : false,
          success:function(data){
              
          },
          error:function(response){
              
          }
      })
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
                                <img src="<?php echo site_url();?>/images/new/i_off.png" onmouseover="this.src='<?php echo site_url();?>/images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url();?>/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Customer Directory"/>
                            </div>
                        </span>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
                        <h2>Customer Directory</h2>	
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
				<div class="container">
					<div class="row">
					<br>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-2">
									Search criteria
								</div>
								<div class="col-md-4">
									<select class="form-control" id="searchCriteria" onchange="criteria(this)">
										<option value="">Select</option>
										<option value="customerName">Customer Name</option>
										<option value="customerId">Customer Id</option>
										<option value="phoneNo">Phone No</option>
										<option value="emailId" >Email Id</option>
										<option value="contactName">Contact Name</option>
										<option value="ticketNo">Ticket No.</option>
									</select>
								</div>
								
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 border border-primary" id="SupportTicket">
							
						</div>
						<div class="col-md-6 border border-primary" id="CustomerData">	
							
						</div>	
					</div>
				</div>
            </div>
				<?php require 'manager_add_request.php' ?>
                <?php require 'manager_edit_request.php' ?>

                
				
             </div>
        
        <?php require 'footer.php' ?>

    </body>
</html>
