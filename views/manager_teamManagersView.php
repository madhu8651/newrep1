<!DOCTYPE html>
<html lang="en">
	<head>
	<?php require 'scriptfiles.php' ?>
	
	<style>
		.user-hierarchy{
			text-align: justify !important;
		}
		.user-hierarchy .modulename.cxo:hover{
			background-color: #2BBBAD!important;
		}
		.user-hierarchy .modulename.manager:hover{
			background-color: #e1bee7!important;
		}
		.user-hierarchy .modulename.sales:hover{
			background-color: #ffab00!important;
		}
		.user-hierarchy .modulename.cxo{
			background-color: #00695c!important;
		}
		.user-hierarchy .modulename.manager{
			background-color: #93C!important;
			padding: 3px 7px;
		}
		.user-hierarchy .modulename.sales{
			background-color: #ff6d00!important;
		}
		.user-hierarchy .modulename{
			padding:3px 8px;
			color:#FFF;
			margin-right: 5px;
			border-radius: 50%;
		}
	</style>
	<script>
		$(document).ready(function(){
			pageLoad()
		})
		function pageLoad(){
			$('#tablebody').parent("table").dataTable().fnDestroy();
            $.ajax({
				type : "POST",
				url : "<?php echo site_url('manager_teamManagersController/get_manager_info'); ?>",
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
                    return;
                }
					console.log(data);
					$('.modal').modal('hide');
					$('.closeinput').val('');
					var row = "";
					for(i=0; i < data.length; i++ ){
						var salesRepStatus="";
						if(data[i].status == 0){
							salesRepStatus = "<b style='color:red'>Inactive</b>"
						}else{
							salesRepStatus = "<b style='color:green'>Active</b>"
						}
						var modul = JSON.parse(data[i].modules);
						var modul_name ="";
						if(modul.cxo != "0"){
							modul_name += '<span class="modulename cxo">C</span>';
						}
						if(modul.Manager != "0"){
							modul_name += '<span class="modulename manager">M</span>';
						}
						if(modul.sales != "0"){
							modul_name += '<span class="modulename sales">S</span>';
						}
						//var rowdata = JSON.stringify(data[i]);
						row += "<tr><td><input type='radio' name='check_list' id='check_list'  value='"+data[i].rep_id+"'>" +"</td><td>"+ (i+1) +"</td><td>"+ data[i].repname+"</td><td>"+data[i].designation+"</td><td>"+data[i].teamname+"</td><td>"+
						data[i].manager+"</td><td>"+salesRepStatus+"</td><td class='user-hierarchy'>"+modul_name+"</td><tr>";
					}			
					$('#tablebody').append(row);
					$('#tablebody').parent("table").DataTable();				
				}
            });	
		}
            function cancel(){
              $('.modal').modal('hide');
              $('input, select, textarea').val('');
              $('input[type="radio"]').prop('checked', false);
              $('input[type="checkbox"]').prop('checked', false);
             }
			 
	function view(){
		if ($("input[name='check_list']:checked").length > 0){
			var manager_id = $('input:radio[name=check_list]:checked').val();
	        console.log(manager_id);
		}
		else{
			alert("Please select the Sales Representative, try again!");
			return false;
		}
        var site_url = "<?php echo site_url('manager_teamManagersController/post_id');?>";
        window.location.href = site_url+"/"+manager_id;
	}	
	function error_handler(data){
        if(data.hasOwnProperty("errorCode")){
                    alert(data.errorCode+"  "+data.errorMsg);
                    return true;
        }
        return false;
    } 	 
	</script>
	
	</head>
	<body class="hold-transition skin-blue sidebar-mini">   
		<?php require 'demo.php' ?>		
		<?php require 'manager_sidenav.php' ?>
			<div class="content-wrapper body-content">			
				<div class="col-lg-12 column">
				<div class="row header1">				
					<div class="col-xs-2 col-sm-2 col-md-3 col-lg-3 aa">
						<span class="info-icon">
							<div >	
								<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-toggle="tooltip" data-placement="right" title="Sales Representative Information"/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6 pageHeader1 aa">
							<h2>Managers Information</h2>	
					</div>
					<div style="clear:both"></div>
				</div>
               
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
									<th class="table_header user-hierarchy">Module</th>
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
               
            </div>	
		</div>
			
    <?php require ('footer.php'); ?>	
   </body>
</html>