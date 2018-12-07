
<!DOCTYPE html>
<html lang="en">
	<head>

	<?php require 'scriptfiles.php' ?>
	<style>
	input[type="file"] {
  display: block!important;
 }
  .switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 20px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #B5000A;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 15px;
  width: 15px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: green;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(19px);
  -ms-transform: translateX(19px);
  transform: translateX(19px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.section-two,
.section-three,
.section-four{
	display:none;
}
ul {
        list-style-type: none;
    }
.tree-view ul{
	padding-left:20px;
	border-left: 1px dotted;
}
.tree-view ul.mytree{
	border-left: 0px;
}
.tree-view ul li label{
	margin-bottom: 0px;
}
.dash-left .glyphicon {
	position: absolute;
}
.dash-left{
	margin-left: -17px;
	float: left;
	position: absolute;
}

	</style>

	<script>


    $(document).ready(function(){
         $.ajax({
            type : "post",
            url : "<?php echo site_url('notificationController/displaynotifydata'); ?>",
            cache : false,
            dataType : "json",
            success : function(data)
            {
                    row='';
                	for(i=0; i < data.length; i++ )
                    {
                         // alert(data[i].notificationShortText);




                         // var datedata=JSON.stringify(data[i].notificationTimestamp);
                        //  var dt="<? echo new DateTime('2012-04-15 22:15:40')"
                          row += "<tr><td>" + data[i].notificationShortText + "</td>"+
                                 "<td>"+ data[i].notificationText +"</td>"+
                                 "<td>"+ data[i].username +"</td>"+
                                 "<td>"+ data[i].notifydate +"</td>"+
                                 "</td><td>"+data[i].action_url+"</td>";
                           /*	row += "<tr onclick='opennotifytab()'><td>" + (i+1) + "</td><td>"+ data[i].notificationShortText +
                               "</td>";*/
			        }
                  //$('.count').text(data.length);
                  $('#tablebody').html('').append(row);
            }


        });
    });








	</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">

		<!---------------------------------------------------------
		Header bar
		------------------------------------------------------------>
		<?php  require 'demo.php'  ?>
		<!---------------------------------------------------------
		side nav
		------------------------------------------------------------>
        <?php
           /* <?php echo "I am :".$_SESSION['active_module_name'] ?>*/
             if($_SESSION['active_module_name'] == "manager")
                {
                    require 'manager_sidenav.php';
                }
                else if($_SESSION['active_module_name'] == "sales")
                {
                    require 'sales_sidenav.php';
                }
                else
                {
                    require 'admin_sidenav.php';
                }

           // require 'admin_sidenav.php'


        ?>
		<?php  ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
			<div class="row header1">
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >

								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Sales Cycle"/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Notification</h2>
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<!--<a href="#modal_upload" data-toggle="modal" class="addExcel" >
								<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a>-->
							<a class="addPlus" onclick="compose()">
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<table class="table" id="tableTeam">
					<thead>
						<tr>
							<th class="table_header">Notification</th>
							<th class="table_header">Details</th>
							<th class="table_header">Created by</th>
							<th class="table_header">Date</th>
							<th class="table_header">Url</th>
						</tr>
					</thead>
					<tbody id="tablebody">
					</tbody>
				</table>
			</div>



		</div>
		<?php require 'footer.php' ?>

	</body>
</html>
