
<!DOCTYPE html>
<html lang="en">
	<head>
	<?php $this->load->view('scriptfiles'); ?>
	
	<style>
	.modal-backdrop{
		z-index:-1;
	}
	.error-alert{
		color:red;
	}
	.header1{
		background:rgb(30, 40, 44);
		padding:2px;
	}
	.pageHeader1{
		text-align:center;		
		color:white;
		height:41px;
		font-size:22px;
	}
	.pageHeader1 h2{
		margin-bottom: 0;
		margin-top: 0;
	}
	.column{
		padding:0;
	}
	.addExcel{
		bottom: 0;
	}
	.addPlus{
		   bottom: 0;
	}
	.table.table{
		margin-top:0;
	}
	.table tbody tr td{
		text-align:center;
		
	}
	.table thead tr th{
		text-align:center;
		
	}	
	.content-wrapper.body-content section.row{
		height:46px;
	}
	.aa{
		width: 33.33%;
		float: left;
		position: relative;
		height: 41px;
		line-height: 35px;
	}
	.info-icon div{
		margin-left: 14px;
	}
	.sidebar{
			margin-top: 0px;
	}
	
	@media only screen and (min-device-width: 320px) and (max-device-width: 480px){
		.aa h2{			
			font-size:16px;
			padding-left: -10px;
			margin-top: 9px;
		}
		.addPlus{
			margin-right: 18px;
		}
		.addExcel{
			margin-right: -14px;
		}
		.aa{
				margin-top: 62px;
				font-size:18px;
			}
		.addBtns{
			margin-right: -19px;
		}
		.aa{
			margin-left: 0px;
			font-size:16px;
			padding:0;
		}
		.sidebar{
				margin-top: 56px;
		}
    }
	@media only screen and (min-device-width: 768px) and (max-device-width: 1024px){
		  .aa h2{			
			font-size:22px;	
			margin-top: 6px;
		}		
    }
	@media only screen and (min-device-width: 340px) and (max-device-width: 632px){
		  .aa h2{			
			font-size:16px;
			padding-left: -10px;
			margin-top: 9px;
		}
		.addPlus{
			margin-right: 18px;
		}
		.addExcel{
			margin-right: -14px;
		}
		.aa{
				margin-top: 62px;
				font-size:18px;
			}
		.addBtns{
			margin-right: -19px;
		}
		.aa{
			margin-left: 0px;
			font-size:16px;
			padding:0;
		}
		.sidebar{
				margin-top: 56px;
		}	
    }
	</style>
	<script>	
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_userController/get_user'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				$('.modal').modal('hide');
				$('.closeinput').val('');
				$('#tablebody').empty();
				var row = "";
				for(i=0; i < data.length; i++ ){						
					var rowdata = JSON.stringify(data[i]);
					row += "<tr><td>" + (i+1) + "</td><td>" + data[i].user_name + "</td><td>" + data[i].Department_name + "</td><td>" + data[i].role_name + "</td><td>" + data[i].regionname + "</td><td>" + data[i].location_name +"</td><td>"+data[i].teamname+"</td></tr>";								
				}					
				$('#tablebody').append(row);					
			}
		});	
	</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">   
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
				
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div data-toggle="tooltip" title="Hooray!" >		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"/>
							</div>
						</span>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
							<h2 >User List</h2>	
					</div>
					<div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
						<div class="addBtns">
							<a href="<?php echo site_url('admin_userController/view_adduser') ?>" class="addPlus"><img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
							<div style="clear:both"></div>
						</div>
					</div>
					<div style="clear:both"></div>
				</div>
				<table class="table">					
					<thead>  
						<tr>	
                                                    <th class="table_header">Sl.No</th>
                                                    <th class="table_header" >Name</th>
                                                    <th class="table_header" >Department</th>
                                                    <th class="table_header" >Role</th>
                                                    <th class="table_header" >Region</th>
                                                    <th class="table_header" >Location</th>
                                                    <th class="table_header" >Team</th>
                                                    <th class="table_header" ></th>
						</tr>
					</thead> 
					<tbody id="tablebody">
					</tbody>
				</table>
			</div>			
		</div>
		<?php $this->load->view('footer'); ?>

	</body>
</html>
