<!DOCTYPE html>
<html lang="en">
	<head>
	
	<?php require 'scriptfiles.php' ?>
	
	
	<script>
			loaderShow();
            $.ajax({
                    type : "POST",
                    url : "<?php echo site_url('manager_salesRepresentativeController/get_rep_info'); ?>",
                    dataType : 'json',
                    cache : false,
                    success : function(data){
                    	loaderHide();
                    $('.modal').modal('hide');
                    $('.closeinput').val('');
                    $('#tablebody').empty();
                    var row = "";
                        for(i=0; i < data.length; i++ ){
                        var salesRepStatus="";
                        if(data[i].rep_actvstate == 0){
                        salesRepStatus = "<b style='color:red'>Inactive</b>"
                        }else{
                        salesRepStatus = "<b style='color:green'>Active</b>"
                        }
                        var rowdata = JSON.stringify(data[i]);
                        row += "<tr><td><input type='radio' name='check_list' id='check_list'  value='"+data[i].rep_id+"'>" +"</td><td>" + (i+1) + "</td><td>" + data[i].repname + "</td><td>"+data[i].designation+"</td><td>"+data[i].teamname+"</td><td>"+
                        data[i].manager+"</td><td>"+salesRepStatus+"</td><tr>";							
                        }			
            $('#tablebody').append(row);					
            }
            });	

            function cancel(){
              $('.modal').modal('hide');
              $('input, select, textarea').val('');
              $('input[type="radio"]').prop('checked', false);
              $('input[type="checkbox"]').prop('checked', false);
             }
			 
	function view(){
	if ($("input[name='check_list']:checked").length > 0){
	var rep_id = $('input:radio[name=check_list]:checked').val();
        console.log(rep_id);
	}
	else{
	alert("Please select the Sales Representative, try again!");
	return false;
	}
        var site_url = "<?php echo site_url('manager_salesRepresentativeController/post_id');?>";
        window.location.href = site_url+"/"+rep_id;
	}	 	 
	</script>
	
	</head>
	<body class="hold-transition skin-blue sidebar-mini">

		<?php require 'demo.php' ?>		
		<?php require 'manager_sidenav.php' ?>
		<div class="loader">
		<center><h1 id="loader_txt"></h1></center>		
		</div>
			<div class="content-wrapper body-content">			
				<div class="col-lg-12 column">
				<div class="row header1">				
					<div class="col-xs-2 col-sm-2 col-md-3 col-lg-3 aa">
						<span class="info-icon">
							<div >		
								<img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Sales Representative Information"/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6 pageHeader1 aa">
							<h2>Representative Information</h2>	
					</div>
					<div style="clear:both"></div>
				</div>
                <form class="form" action="#" method="post" name="adminClient">
				<div class="table-responsive">
					 <table class="table" id="tableTeam">
						<thead>  
							<tr>
								<th class="table_header"></th>
								<th class="table_header">SL No</th>
								<th class="table_header">Name</th>
								<th class="table_header">Designation</th>		
								<th class="table_header">Team</th>
								<th class="table_header">Reporting Manager</th>
								<th class="table_header">User</th>
								<th class="table_header"></th>
							</tr>
						</thead>  
						<tbody id="tablebody">
						
						</tbody>    
					</table>
				</div>
				<div>
				<center>
						<input type="button" class="btn bt"  onclick="view()" value="View Details">

				</center>
				</div>
                </form>          
            </div>	
		</div>
			
    <?php require ('footer.php'); ?>	
   </body>
</html>