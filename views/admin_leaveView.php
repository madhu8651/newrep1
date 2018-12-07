<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>L-Connectt</title>
<link href="<?php echo base_url(); ?>css/tablecss.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.selectBox.css" />
<link href="<?php echo base_url(); ?>css/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>css/rep-style.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<style>
body{ margin:auto;}
#table1 tr{ width:100%; text-align:center; }
#table1 tr td{ color:#000; }
.errMessage{ color:red !important; }
#table1 > thead > tr > th{padding:14px;font-size:14px;font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;text-align:center;}
#table1 > tbody > tr > td{padding:10px;font-size:13px;font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;}
#table2 > tr > td{padding:3px;}
ul {
    list-style-type: none;
	background: #b5000a;
	padding: 15px 6px;
	color:#ffffff;
	text-align:center;
	border: 0;
	background-color:#357ca5;
	box-shadow: 0px 0px 8px #000;
	width:100%;
	height:52px;
	cursor:pointer;
	margin-bottom:-5px;
	margin-top:0px;

}

li {
   	float: left;
	height:12px;
	width:90px;
	color:#ffffff;
	font-size:15px;
	font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
	margin-top:2px;
}
a.tab{text-decoration:none;color:#fff;}
a.tab1{background-color:#1e282c;padding:11px 5px;}
.addPlus{position:relative;bottom:28PX;right:45px;float:right;text-decoration:none;}
#new_conv{
	width:500px;
	height:auto;
	border-radius: 2px;
    padding: 1px 13px;
    color: #82899E;
    border: 0;
	background-color:#FFFFFF;
    box-shadow: 0px 0px 8px #000;
	position:absolute;
	z-index:500;
	top:150px;
	left:310px;
}
#edit_conv{
  width:500px;
	height:auto;
	border-radius: 2px;
    padding: 1px 13px;
    color: #82899E;
    border: 0;
	background-color:#FFFFFF;
    box-shadow: 0px 0px 8px #000;
	position:absolute;
	z-index:500;
	top:150px;
	left:310px;


}

button{background: #b5000a;color:#fff!important;cursor:pointer;border:none;}
button:hover{background: #b5000a;color:#fff!important;cursor:pointer;}

</style>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.12.3.min.js"></script>
<script src="<?php echo base_url(); ?>js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
    $('#edit_conv').hide();
	$('.errMessage').hide();
	$('#saveCategory').click(function(){
		var flag = 1;
		if ($.trim($("#categoryname").val()) == ""){
            $("#categoryNameMsg").show();
			flag = 0;
        }

		if(!validatecategory($('#categoryname').val())){
			flag=0;
			$('#categoryNameMsg1').show();
		}
		if (($.trim($("#categoryname").val()) != "") && (validatecategory($('#categoryname').val()))){
			flag=1;
		}
		if(flag==1){

			var category = $('#categoryname').val();
			$.ajax({
				type:"post",
				cache:false,
				url:"<?php echo site_url('admin_leaveController/check_category')?>",
				data:"category="+category,
				success: function (data) {
                    if(data==0)
                    {
                        $('#admin_client').submit();  // to insert the new record
                    }
                    else
                    {
                        $("#categoryNameMsg2").show();
						$('#saveCategory').removeAttr("disabled"); // if record exists
                    }
				}
			});
		}
	});
	$('#new_conv').hide();
	$("#compose").click(function(){

		$("#new_conv").toggle();
	});
	$(".close1").click(function(){
		 $("#new_conv").hide();
         $("#categoryNameMsg").hide();
         $("#categoryNameMsg1").hide();
         $("#categoryNameMsg2").hide();
         $("#categoryname").val("");
	});

    $(".close2").click(function(){
		 $("#edit_conv").hide();
         $("#editcategoryNameMsg").hide();
         $("#editcategoryNameMsg1").hide();
         $("#editcategoryNameMsg2").hide();
         $("#edit_categoryname").val("");
	});


     // to open the pop up on click of edit and fill it with values
    $('.editbtn').click(function(){
        var categoryid = this.id;
        $.ajax({
            type : "post",
            url : "<?php echo site_url('admin_leaveController/edit_data') ?>",
            data : "categoryid="+categoryid,
            cache : false,
            dataType : "json",
            success : function(data){
                $('#edit_conv').show();
                $('#edit_categoryname').val(data[0]['leave_category_name']);
                $('#categoryID').val(data[0]['leave_category_id']);

            }
        });
    });

    $('#updatebtn').click(function(){
		var flag = 1;
		if ($.trim($("#edit_categoryname").val()) == ""){
            $("#editcategoryNameMsg").show();
			flag = 0;
        }

		if(!validatecategory($('#edit_categoryname').val())){
			flag=0;
			$('#editcategoryNameMsg1').show();
		}
		if (($.trim($("#edit_categoryname").val()) != "") && (validatecategory($('#edit_categoryname').val()))){
			flag=1;
		}
		if(flag==1){

			var category = $('#edit_categoryname').val();
			var categoryID = $('#categoryID').val();
			$.ajax({
				type:"post",
				cache:false,
				url:"<?php echo site_url('admin_leaveController/check_category')?>",
				data:"category="+category+"&categoryID="+categoryID,
				success: function (data) {
                    if(data==0)
                    {
                        $('#edit_client').submit();  // to insert the new record
                    }
                    else
                    {
                        $("#editcategoryNameMsg2").show();
						$('#updatebtn').removeAttr("disabled"); // if record exists
                    }
				}
			});
		}
	});



});
// function to validate only characters
    function validatecategory(name){
		var r = new RegExp(/^[a-zA-Z_]*$/);
		var valid = r.test(name);
		if(!valid){
			return false;
		}else{
			return true;
		}
	}
</script>
</head>
<body>	
<div class="loader"></div>
<section class="row" style="height:42px;margin-top:65px;">
	<div class="pageHeader1">
		<h2>Leave Category List</h2>
		<span class="info-icon"><a data-toggle="tooltip" title="Leave Category List"><img src="<?php echo base_url(); ?>/images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>/images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>/images/new/i_off.png'" alt="info" width="30" height="30"/></a></span>
	</div>
	<div class="addBtns">
		<a href="#" class="addPlus" id="compose"><img src="<?php echo base_url(); ?>/images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>/images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>/images/new/Plus_Off.png'" width="30px" height="30px"/>
		</a>
	</div>
</section>
<div id="new_conv">
	<p><input type="button" value="X" style="float:right" class="close1"></p><br>
	<form id="admin_client" class="Admin_Client" action="<?php echo site_url('admin_leaveController/post_data'); ?>" method="post" name="admin_client" >
		<table id="table1" class="table_admin" style="margin:20px auto;width:95%;">
			<tr>
				<td class="td" style="font-family: Times New Roman, Times, serif;text-align:center;font-size:14px;"><label>Leave Category Name*</label></td>
				<td class="td">
					<input type="text" name="categoryname" id="categoryname" autocomplete="off" required placeholder="Leave Category Name"/>
					<div>
						<span id="categoryNameMsg" class="errMessage"> Leave Category Name is required</span>
						<span id="categoryNameMsg1" class="errMessage"> Leave Category Name should be of Characters</span>
						<span id="categoryNameMsg2" class="errMessage"> Leave Category Name already exists</span>
					</div>
				</td>
			</tr>

			<tr>
				<td class="td"><button type="button" id="saveCategory" name="saveCategory">Save</button></td>
				<td class="td"><button type="reset">Clear</button></td>
			</tr>
		</table>
	</form>
</div>

<div id="edit_conv">
	<p><input type="button" value="X" style="float:right" class="close2"></p><br>
	<form id="edit_client" class="Admin_Client" action="<?php echo site_url('admin_leaveController/update_data'); ?>" method="post" name="admin_client" >
		<table id="table1" class="table_admin" style="margin:20px auto;width:95%;">
			<tr>
				<td class="td" style="font-family: Times New Roman, Times, serif;text-align:center;font-size:14px;"><label>Leave Category Name*</label></td>
				<td class="td">
					<input type="text" name="edit_categoryname" id="edit_categoryname" autocomplete="off" />
                    <input type="hidden" name="categoryID" id="categoryID"/>
					<div>
						<span id="editcategoryNameMsg" class="errMessage"> Leave Category Name is required</span>
						<span id="editcategoryNameMsg1" class="errMessage"> Leave Category Name should be of Characters</span>
						<span id="editcategoryNameMsg2" class="errMessage"> Leave Category Name already exists</span>
					</div>
				</td>
			</tr>

			<tr>
				<td class="td"><button type="button" id="updatebtn" name="updatebtn">Save</button></td>
				<td class="td"><button type="reset">Clear</button></td>
			</tr>
		</table>
	</form>
</div>

<table id="table1" style="margin-top:0px;margin-bottom:20px;">
	<thead >
		<tr>
			<th>SL No</th>
			<th>Leave Category Name</th>
            <th></th>
		</tr>
	</thead>
	<tbody>
		<?php
	    $i=1;
        if(isset($leave_category) && is_array($leave_category) && count($leave_category)){
            foreach ($leave_category as $key => $arr){
		?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $arr['leave_category_name']; ?></td>
            <td><a id="<?php echo $arr['leave_category_id']; ?>" class="editbtn" style="text-decoration:none;padding:7px 13px;cursor:pointer;">Edit</a></td>
		</tr>
		<?php
	   	    $i++;
		    }
        }
		?>
	</tbody>
</table>
</body>
</html>
