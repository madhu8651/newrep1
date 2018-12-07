
<!DOCTYPE html>
<html lang="en">
	<head>
	
	<?php $this->load->view('scriptfiles'); ?>
	<script>
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_mangelicenseController/get_licenseinfo'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){				
				$('#tablebody').empty();
				var row = "";
				for(i=0; i < data.length; i++ ){						
					var rowdata = JSON.stringify(data[i]);
					row += "<tr><td>" +(i+1) + "</td><td>" +data[i].user_name+"</td><td>" +data[i].role_name+"</td><td>0</td><td>"+data[i].manager_module+"</td><td>" +data[i].sales_module +"</td> <td>0</td> <td>0</td> <td>0</td> <td>0</td> <td>0</td> <td>0</td> <td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";							
				}					
				$('#tablebody').append(row);					
			}
		});			
	</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">	
	<div class="loader"></div>   
		<!---------------------------------------------------------
		Header bar
		------------------------------------------------------------>
		<?php $this->load->view('demo');  ?>
		<!---------------------------------------------------------
		side nav
		------------------------------------------------------------>		
		
		<?php $this->load->view('admin_sidenav'); ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
				<div class="row header1">				
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Manage License"/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Manage License</h2>	
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="table-responsive">
					<table class="table table_license">					
						<thead>  
							<tr>			
								<th class="table_header" colspan="3">User</th>
								<th class="table_header" colspan="3">Modules</th>
								<th class="table_header" colspan="7">Plugins</th>
							</tr>
							<tr>			
								<th class="table_header">SL No</th>
								<th class="table_header">Name</th>
								<th class="table_header">Designation</th>
								<th class="table_header">CXO</th>
								<th class="table_header">Manager</th>
								<th class="table_header">Sales</th>
								<th class="table_header">Library</th>
								<th class="table_header">Attandance</th>
								<th class="table_header">Expenses</th>
								<th class="table_header">Navigation</th>
								<th class="table_header">Communicator</th>
								<th class="table_header">Inventory</th>		   
								<th class="table_header"></th>		   
							</tr>
						</thead>  
						<tbody id="tablebody">
						</tbody>    
					</table>
				</div>
			</div>						
		</div>
		<?php $this->load->view('footer'); ?>
	</body>
</html>
