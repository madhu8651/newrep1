<!DOCTYPE html>
<html lang="en">
	<head>
	
	<?php $this->load->view('scriptfiles'); ?>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">   
	<?php  require 'demo.php'  ?>
	<?php require 'manager_sidenav.php' ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">				
				<div class="row header1">				
                                    <div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
                                            <span class="info-icon">
                                                    <div >		
                                                            <img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Region List"/>
                                                    </div>
                                            </span>
                                    </div>
                                    <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
                                         <?php
                                            if($type=='lead'){
                                         ?>
                                         <h2>Rejected Leads List</h2>
                                           <?php 
                                            } else{?>
                                          <h2>Rejected Customers List</h2>
                                          <?php  
                                            }
                                        ?>
                                    </div>
				</div>
                                <div class="table-responsive">
					<table class="table" id="tableTeam">
						<thead>  
						<tr>	
							<th class="table_header">SL No</th>
							<th class="table_header">Lead Names</th>	
						</tr>
						</thead>  
      <tbody id="tablebody">
               <?php
        $i=1;
        if(isset($reject_list)&& is_array($reject_list) && count($reject_list) && (isset($totalleads))){
           if($totalleads==0) {
               ?>
         <td style="padding:10px;font-size:13px;font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;"><?php echo "No Leads Rejected "?></td>

        <?php
        }else{
         for($row=0;$row<count($reject_list);$row++){
         ?>
        <tr>
            <td style="padding:10px;font-size:13px;font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;"><?php echo $i; ?></td>
            <td style="padding:10px;font-size:13px;font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;"><?php echo $reject_list[$row]; ?></td>

        </tr>
        <?php
            $i++;
            }
        ?>
        <?php 
        }
        }
        ?>
	</tbody>    
					</table>
				</div>
                <div class="modal-footer" id="modal_footer">
                <center>
<a href="<?php echo site_url('manager_leadController/index'); ?>"><button type="button" style="padding:8px;">Back</button></a>  
                </center>
            </div>
			</div>

                </div>
		<?php require 'footer.php' ?>

	</body>
</html>
