
<!DOCTYPE html>
<html lang="en">
	<head>

	<?php require 'scriptfiles.php' ?>
<script>
var mainData;
function cancel(){
	$('.modal').modal('hide');
	$('.modal input[type="text"], textarea').val('');
	$('.modal select').val($('.modal select option:first').val());
	$('.modal input[type="radio"]').prop('checked', false);
	$('.modal input[type="checkbox"]').prop('checked', false);
	$(".error-alert").text("");
	$("#currencylistView").html("");
}

$(document).ready(function(){
    /* code for sandbox */
	var url1= window.location.href;
	var fileNameIndex1 = url1.lastIndexOf("/") + 1;
	var filename1 = url1.substr(fileNameIndex1);
    sandbox(filename1);
	loadpage();
});

function loadpage(){
	$('#currency_data').find("table").dataTable().fnDestroy();
	/* currency_catt  currency_data*/
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_currencyController/get_data/getcatg'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				loaderHide();
				if(error_handler(data)){
							return;
						}
				$('#currency_catt').empty();
				$('#currency_data').empty();
				var row = "";
				var row1 = "";
				for(var i=0,j=0; i < data.length; i++,j++ ){
					if(i==0){
						row += "<li onclick='loadtable1(\""+data[i].currency_category_id+"\")' class='active'><a  href='#"+data[i].currency_category_id+"' data-toggle='tab'>" + data[i].currency_category_name+"</li>";

					}else{
						row += "<li onclick='loadtable1(\""+data[i].currency_category_id+"\")' ><a  href='#"+data[i].currency_category_id+"' data-toggle='tab'>" + data[i].currency_category_name+"</li>";
                    }

				}
				$('#currency_catt').append(row);
                loadtable1(data[0].currency_category_id);

			}
		});
	}	


	function loadtable(data){
		var currency_data_table ="";
		currency_data_table += '<table class="table"><thead>';
		currency_data_table += "<tr><th width='10%'>Sl No</th><th width='80%'>Currency Name</th><th width='10%'></th></tr></thead><tbody>";
		for(var i=0; i < data.length; i++ ){
			var jsondata=JSON.stringify(data[i]);
			currency_data_table += "<tr><td>" + (i+1) + "</td><td>" + data[i].currency_name + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+jsondata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
		}
		currency_data_table += '</tbody></table>';
		return currency_data_table;
	}

    function loadtable1(catgid){
                var addObj={};
                addObj.catgid=catgid;
                 loaderShow();
                 $.ajax({
        			type : "POST",
        			url : "<?php echo site_url('admin_currencyController/get_data/getcur'); ?>",
        			dataType : 'json',
                    data: JSON.stringify(addObj),
        			cache : false,
        			success : function(data){
                            loaderHide();
        				    if(error_handler(data)){
        							return;
        				    }
                            $('#currency_data').empty();
                            $('#currency_data').find("table").dataTable().fnDestroy();
                            var row1 = "";
                            var role_data_table ="";
                            i1=0;
                            row1 += '<div class="tab-pane active" id="'+data[i1].currency_category_id+'">';

                            if(data[i1].hasOwnProperty('currency_data')){
                                    row1 += '<table class="table"><thead>';
                            		row1 += "<tr><th width='10%'>Sl No</th><th width='80%'>Currency Name</th><th width='10%'></th></tr></thead><tbody>";
                            		for(var i=0; i < data[0].currency_data.length; i++ ){
                            			var jsondata=JSON.stringify(data[0].currency_data[i]);
                            			row1 += "<tr><td>" + (i+1) + "</td><td>" + data[0].currency_data[i].currency_name + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+jsondata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
                            		}
                            		row1 += '</tbody></table>';
                                    /* code for sandbox */
                                    if(data[0].currency_data.length >= 1 && parseInt(recordcount)!= 0){
                                        $('#curbtn').hide();
                                    }else{
                                        $('#curbtn').show();
                                    }
                            }else{

                                    row1 += "<center style='padding-top: 60px;'><h4>No data available.</h4></center>";
                                    $('#curbtn').show();
                            }
                            row1 += '</div>';
                            $('#currency_data').append(row1);

            				$('#currency_data table').each(function(){
            					$(this).DataTable({
            						"aoColumnDefs": [
            							{
            								"bSortable": false,
            								"aTargets": [2] }
            							]
            						});
            					$(this).removeAttr('style');
            					$(this).find("th").removeAttr('style');

            				});

                    }
                 });

	    }




function addCurrency(){
	$("#addmodal .modal-header h4.modal-title").text("Add Currency for "+$("#currency_catt li.active").text());
    $('#curbtn1').show();
	$.ajax({
        type: "POST",
        url:"<?php echo base_url('js/currencylist.json'); ?>",
        dataType:'json',
        success: function(data){
			if(error_handler(data)){
				return;
			}
			var select = $("#add_cur"), options = "<option value=''>select</option>";
			select.empty();
			for(var i=0;i<data.length; i++)
			{
				options += "<option value='"+data[i].value+"'>"+ data[i].innertext +"</option>";
			}
			select.append(options);
			
			setTimeout(function(){
					$("#add_cur").focus();	
			},300);
        }
	});
}

function add_currency_list(){
	var currName = $.trim($("#add_cur").val());
	$(".error-alert").text("")
	
	if(currName ==""){
        $("#add_cur").closest("div").find("span").text("Currency is required.");
		$("#add_cur").focus();
        return;
    }else{
         $("#add_cur").closest("div").find("span").text("");
    }

	var html= 0;
	if($("#currencylistView li").length <= 0){
		$("#currencylistView").append('<li><span>'+ currName +'</span><a title="Delete" class="pull-right glyphicon glyphicon-remove-circle"></a></li>');
		$("#currencylistView").siblings(".error-alert").text("");
        /* code for sandbox */
        if(parseInt(recordcount)!= 0){
                $('#curbtn1').hide();
        }else{
                $('#curbtn1').show();
        }
	}else{
		$("#currencylistView li").each(function(){
			if($(this).find('span').text().trim() == currName){
				html = 1;
			}
		});

		if(html == 1){
			$("#currencylistView").siblings(".error-alert").text("Duplicate Currency Name.");
			$("#add_cur").focus();
			return;
		}else{
			$("#currencylistView").siblings(".error-alert").text("");
			$("#currencylistView").append('<li><span>'+ currName +'</span> <a title="Delete" class="pull-right glyphicon glyphicon-remove-circle"></a></li>');
		}
	}
	$("#add_cur").val("");
	$("#currencylistView li a").each(function(){
		$(this).click(function(){					
			$(this).closest('li').remove();
            /* code for sandbox */
            if($("#currencylistView li").length == 0){
                $('#curbtn1').show();
            }
		})
	});
}

/* -----------------------------------------Add Currency -------------------------------------------------------------- */
function add(){
	var addObj={};
	var  csObj=[];		
	$("#currencylistView li").each(function(){			
		csObj.push({ "currency_name" : $(this).find('span').text()});
	})
	if(csObj.length <= 0){
		$("#currencylistView").siblings(".error-alert").text("Please add Currency.");
		$("#add_cur").focus();
		return;
	}else{
		$("#currencylistView").siblings(".error-alert").text("");
	}
	
	var currency_cat_id=$.trim($("#currency_data .tab-pane.active").attr('id'));
	addObj.currencyObj = csObj;
	addObj.currency_cat_id = currency_cat_id;
	loaderShow();

	$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_currencyController/add_currency'); ?>",
			dataType : 'json',
			data : JSON.stringify(addObj),
			cache : false,
			success : function(data){
				loaderHide();
				if(error_handler(data)){
					return;
				}
				$('#tablebody').empty();
				var row = "";
				row += loadtable(data.currency[0].currency_data);
                /* code for sandbox */
                if(data.currency[0].currency_data.length >= 1 && parseInt(recordcount)!= 0){
                    $('#curbtn').hide();
                }else{
                    $('#curbtn').show();
                }
				if(data.dup_currency.length>0)
                {
                      $('#currencylistView').html("");
                            for(var q=0;q<data.dup_currency.length;q++)
                            {

                                $("#currencylistView").append('<li><span>'+ data.dup_currency[q].currency_name +'</span><i class="error-alert">(Duplicate data found)</i><a title="Delete" class="pull-right glyphicon glyphicon-remove-circle"></a></li>');
                            }
                                              	$("#currencylistView li a").each(function(){
                                    				$(this).click(function(){
                                    					$(this).closest('li').remove();
                                    				})
                                    			});
                }
                else
                {
                  	cancel();
                }

                if( $("#"+currency_cat_id +" table").length > 0){
                     $("#"+currency_cat_id).find("table").dataTable().fnDestroy();
                }

				$("#"+currency_cat_id).html("").html(row);
                $("#"+currency_cat_id).find("table").each(function(){
					$(this).DataTable({"aoColumnDefs": [{
											"bSortable": false,
											"aTargets": [2]
											}
										]});
				});

			}
	});
}
/* -------------------------------------------------------------------------------------------------------------- */
function selrow(obj){
	$("#edit_cur_hidden").val(obj.currency_id);
	$("#editmodal .modal-header h4.modal-title").text("Edit Currency for "+$("#currency_catt li.active").text());
	

   $.ajax({
        type: "POST",
        url:"<?php echo base_url('js/currencylist.json'); ?>",
        dataType:'json',
        success: function(data){
			if(error_handler(data)){
				return;
			}
			var select = $("#edit_cur"), options = "<option value=''>select</option>";
			select.empty();
			for(var i=0;i<data.length; i++){
                options += "<option value='"+data[i].value+"'>"+ data[i].innertext +"</option>";
			}
			select.append(options);
			$("#edit_cur option[value='"+obj.currency_name+"']").attr("selected",true);
			setTimeout(function(){
					$("#edit_cur").focus();	
			},300);
        }
	});
}
function update(){
		$(".error-alert").text("");
		
		if($("#edit_cur").val()==""){
				$("#edit_cur").closest("div").find("span").text("Currency is required.");
				$("#edit_cat").focus();
				return;
		}else{
				$("#edit_cur").closest("div").find("span").text("");
		}
		
		var addObj={};
		var currency_cat_id =$.trim($("#currency_data .tab-pane.active").attr('id'));
		addObj.CURRENCY_CATEGORY_ID = currency_cat_id;
		addObj.CURRENCY_ID = $("#edit_cur").val();
		addObj.CURRENCYID = $("#edit_cur_hidden").val();

		console.log(addObj)
		loaderShow();
		$("#"+currency_cat_id).find("table").dataTable().fnDestroy();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin_currencyController/update_data'); ?>",
			data : JSON.stringify(addObj),
			dataType:'json',
			success: function(data) {
				loaderHide();
				if(error_handler(data)){
					return;
				}
                str= data;
                if(str=="0"){

                                $('#alert').modal('show');
								$("#alert .modal-body center span").text("Currency Name already exists.");
								$("#"+currency_cat_id).find("table").DataTable({
																		"aoColumnDefs": [
																			{
																				"bSortable": false,
																				"aTargets": [2] }
																			]
																		  });
                                return;
                }
				else{
        				cancel();
        				var row = "";

        				row += loadtable(data[0].currency_data);

        				if( $("#"+currency_cat_id +" table").length > 0){
                             $("#"+currency_cat_id).find("table").dataTable().fnDestroy();
                        }

        				$("#"+currency_cat_id).html("").html(row);
                        $("#"+currency_cat_id).find("table").each(function(){
        					$(this).DataTable({"aoColumnDefs": [{
        											"bSortable": false,
        											"aTargets": [2]
        											}
        										]});
        				});
                }

			}
		});
   };



</script>

	</head>
	<body class="hold-transition skin-blue sidebar-mini">
		<div class="loader">
			<center><h1 id="loader_txt"></h1></center>
		</div>
		<!---------------------------------------------------------
		Header bar
		------------------------------------------------------------>
		<?php  require 'demo.php'  ?>
		<!---------------------------------------------------------
		side nav
		------------------------------------------------------------>

		<?php require 'admin_sidenav.php' ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
				<div class="row header1">
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Currency List"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Currency', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Currency List</h2>
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<a onclick="addCurrency()" href="#addmodal" id='curbtn' class="addPlus" data-toggle="modal">
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="col-xs-3 verticle-tab">
					<ul class="nav nav-tabs tabs-left" id="currency_catt">
					</ul>
				</div>
				<div class="col-xs-9 tab-col" >
					<div class="tab-content" id="currency_data">
					</div>
				</div>
				
			</div>
			<div id="editmodal" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="editpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Edit Currency</h4>
							</div>
							<div class="modal-body">
									<!--<div class="row">
										<div class="col-md-3">
											<label for="edit_cat">Currency Category*</label>
										</div>
										<div class="col-md-9">
											<select name="adminContactDept" class="form-control" id="edit_cat">

											</select>
											<span class="error-alert"></span>
										</div>
									</div>-->
									<div class="row">
										<div class="col-md-3">
											<label for="edit_cur">Currency*</label>
										</div>
										<div class="col-md-9">
											<select name="adminContactDept" class="form-control" id="edit_cur">
											</select>
											<span class="error-alert"></span>
											<input type="hidden" id="edit_cur_hidden"/>
										</div>
									</div>
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="update()" value="Save">
									<input type="button" class="btn" id="cancle" onclick="cancel()" value="Cancel" >
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="addmodal" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form" action="#" method="post">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								 <h4 class="modal-title">Add Currency</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-3">
										<label for="add_cur">Currency*</label>
									</div>
									<div class="col-md-7">
										<select name="adminContactDept" class="form-control" id="add_cur">

										</select>
										<span class="error-alert"></span>
									</div>
									<div class="col-md-2 col-sm-2 col-xs-2">
										<a title="Add currency" href="#"  id='curbtn1' class="glyphicon glyphicon-plus-sign" onclick="add_currency_list()"></a>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<ol id="currencylistView">
										
										</ol>
										<span class="error-alert"></span>											
									</div>	
								</div>
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="add()" value="Save">
									<input type="button" class="btn" onclick="cancel()" value="Cancel">
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="alert" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
						<div class="modal-body">
							<div class="row">
								<center>
									<span></span>
									<br>
									<br>
									<input type="button" class="btn" data-dismiss="modal" value="Ok">
								</center>
							</div>
						</div>
                    </div>
                </div>
            </div>
		</div>
		<?php require 'footer.php' ?>

	</body>
</html>
