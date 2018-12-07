
<!DOCTYPE html>
<html lang="en">
	<head>

	<?php require 'scriptfiles.php' ?>
<script>

var pagedata=[
   {
      "id":"1",
      "module":"User",
      "modulename":"User"
   },
   {
      "id":"2",
      "module":"Lead",
      "modulename":"Lead"
   },
   {
      "id":"3",
      "module":"Opportunity",
      "modulename":"Opportunity"
   },
   {
      "id":"4",
      "module":"Customer",
      "modulename":"Customer"
   }
];

var mainData;
function cancel(){
	$('.modal').modal('hide');
	$('.modal input[type="text"], textarea').val('');
	$('.modal select').val($('.modal select option:first').val());
	$('.modal input[type="radio"]').prop('checked', false);
	$('.modal input[type="checkbox"]').prop('checked', false);
	$(".error-alert").text("");
    $('#curbtn1').show();
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

function compose(){
     $("#add_custom_field_name").val("");
     $("#add_custom_field_type").val("");
     $("#edit_custom_field_name").val("");
     $("#edit_custom_field_type").val("");

}

function loadpage(){
	$('#currency_data').find("table").dataTable().fnDestroy();
	/* currency_catt  currency_data*/
		loaderHide();
		if(error_handler(pagedata)){
					return;
				}
		$('#currency_catt').empty();
		$('#currency_data').empty();
		var row = "";
		var row1 = "";
		for(var i=0,j=0; i < pagedata.length; i++,j++ ){
			if(i==0){
				row += "<li onclick='loadtable1(\""+pagedata[i].module+"\")' id='li-"+pagedata[i].module+"' class='active'><a  href='#"+pagedata[i].module+"' data-toggle='tab'>" + pagedata[i].modulename+"</li>";

			}else{
				row += "<li onclick='loadtable1(\""+pagedata[i].module+"\")' id='li-"+pagedata[i].module+"'><a  href='#"+pagedata[i].module+"' data-toggle='tab'>" + pagedata[i].modulename+"</li>";
                  }

		}
		$('#currency_catt').append(row);
              loadtable1(pagedata[0].module);

	}


	function loadtable(data){
		                    $('#currency_data').empty();
                            $('#currency_data').find("table").dataTable().fnDestroy();
                            var row1 = "";
                            var role_data_table ="";
                            i1=0;

                            if(data.length >0){
                                    row1 += '<div class="tab-pane active" id="'+data[i1].module+'">';

                                    if(data[i1].hasOwnProperty('attribute')){
                                            row1 += '<table class="table"><thead>';
                                    		row1 += "<tr><th width='10%'>SL No</th><th width='40%'>Custome Field Name</th><th width='40%'>Custome Field Type</th><th width='10%'></th></tr></thead><tbody>";
                                    		for(var i=0; i < data[0].attribute.length; i++ ){
                                    			var jsondata=JSON.stringify(data[0].attribute[i]);
                                                var attribute_type = data[0].attribute[i].attribute_type;
                                                attribute_type = attribute_type.replace(/_/g, ' ');
                                                if(attribute_type == 'Single_Line_Text'){attribute_type='Single Line Text'}
                                    			row1 += "<tr><td>" + (i+1) + "</td><td>" + data[0].attribute[i].attribute_name + "</td><td>" + attribute_type + "</td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+jsondata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
                                    		}
                                    		row1 += '</tbody></table>';
                                            /* code for sandbox */
                                            if(data[0].attribute.length >= recordcount && parseInt(recordcount)!= 0){
                                                $('#curbtn').hide();
                                            }else{
                                                $('#curbtn').show();
                                            }
                                    }else{

                                            row1 += "<center style='padding-top: 60px;'><h4>No data available.</h4></center>";
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

    function loadtable1(pageid){
                var addObj={};
                addObj.pageid=pageid;
                 loaderShow();
                 $.ajax({
        			type : "POST",
        			url : "<?php echo site_url('admin_customFieldController/get_data'); ?>",
        			dataType : 'json',
                    data: JSON.stringify(addObj),
        			cache : false,
        			success : function(data){
                            loaderHide();
        				    if(error_handler(data)){
        							return;
        				    }
                            loadtable(data);
                    }
                 });
	    }



function add_currency_list(){
	$(".error-alert").text("");
	var custName = $.trim($("#add_custom_field_name").val());
    if(custName == ""){
		$("#add_custom_field_name").closest("div").find("span").text("Custom Field Name is required");
		$("#add_custom_field_name").focus();
		return;
	}else if(!validate_location(custName)) {
		$("#add_custom_field_name").closest("div").find("span").text("No special characters allowed (except &).");
		$("#add_custom_field_name").focus();
		return;
	}else if(!firstLetterChk(custName)) {
		$("#add_custom_field_name").closest("div").find("span").text("First letter should not be Numeric or Special character.");
		$("#add_custom_field_name").focus();
		return;
	}else{
		$("#add_custom_field_name").closest("div").find("span").text("");
	}
    if($.trim($("#add_custom_field_type").val())==""){
		$("#add_custom_field_type").closest("div").find("span").text("Custom Field Type is required.");
		$("#add_custom_field_type").focus();
		return;
    }else{
		var cust_tyName = $.trim($("#add_custom_field_type").val());
		$("#add_custom_field_type").closest("div").find("span").text("");
    }

	var html= 0;
	if($("#currencylistView li").length <= 0){
		$("#currencylistView").append('<li><span class="custName">'+ custName +'</span> ( <span class="cust_tyName">'+ cust_tyName +'</span> )<a title="Delete" class="pull-right glyphicon glyphicon-remove-circle"></a></li>');
		$("#currencylistView").siblings(".error-alert").text("");
        $("#add_custom_field_type").val("");
        /* code for sandbox */
        if(parseInt(recordcount)!= 0){
                $('#curbtn1').hide();
        }else{
                $('#curbtn1').show();
        }
	}else{
	    $("#add_custom_field_type").val("");
		$("#currencylistView li").each(function(){
			if($(this).find('span.custName').text().trim() == custName && $(this).find('span.cust_tyName').text().trim() == cust_tyName){
				html = 1;
			}
		});

		if(html == 1){
			$("#currencylistView").siblings(".error-alert").text("Duplicate Entry.");
			$("#add_custom_field_name").focus();
			return;
		}else{
			$("#currencylistView").siblings(".error-alert").text("");
			$("#currencylistView").append('<li><span class="custName">'+ custName +'</span>( <span class="cust_tyName">'+ cust_tyName +'</span> ) <a title="Delete" class="pull-right glyphicon glyphicon-remove-circle"></a></li>');
		}
	}
	$("#add_custom_field_name").val("");
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
	$("#addmodal .error-alert").text("");
	$("#currencylistView li").each(function(){
		csObj.push({
		  "field_name" : $(this).find('span.custName').text(),
		  "field_type" : $(this).find('span.cust_tyName').text()
        });
	})
	if(csObj.length <= 0){
		var custName = $.trim($("#add_custom_field_name").val());
		if(custName == ""){
			$("#add_custom_field_name").closest("div").find("span").text("Custom Field Name is required");
			$("#add_custom_field_name").focus();
			return;
		}else if(!validate_location(custName)) {
			$("#add_custom_field_name").closest("div").find("span").text("No special characters allowed (except &).");
			$("#add_custom_field_name").focus();
			return;
		}else if(!firstLetterChk(custName)) {
			$("#add_custom_field_name").closest("div").find("span").text("First letter should not be Numeric or Special character.");
			$("#add_custom_field_name").focus();
			return;
		}else{
			$("#add_custom_field_name").closest("div").find("span").text("");
		}
		if($.trim($("#add_custom_field_type").val())==""){
			$("#add_custom_field_type").closest("div").find("span").text("Custom Field Type is required.");
			$("#add_custom_field_type").focus();
			return;
		}else{
			var cust_tyName = $.trim($("#add_custom_field_type").val());
			$("#add_custom_field_type").closest("div").find("span").text("");
		}
		
		$("#currencylistView").siblings(".error-alert").text("Please click on Plus Button to create custom field list.");
		$("#add_cur").focus();
		return;
	}else{
		$("#currencylistView").siblings(".error-alert").text("");
	}
    var liId = $.trim($("#currency_catt li.active").attr('id'));
	var currency_cat_id = liId.replace('li-','');
	addObj.currencyObj = csObj;
	addObj.module = currency_cat_id;
	loaderShow();
	$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_customFieldController/post_data'); ?>",
			dataType : 'json',
			data : JSON.stringify(addObj),
			cache : false,
			success : function(data){
                            loaderHide();
        				    if(error_handler(data)){
        							return;
        				    }
                            //cancel();
                            loadtable(data.getdata);
                            /* code for sandbox */
                            /*if(data.getdata.length >= recordcount && parseInt(recordcount)!= 0){
                                $('#curbtn').hide();
                            }else{
                                $('#curbtn').show();
                            }*/
            				if(data.dup_roles.length>0)
                            {
                                  $('#currencylistView').html("");
                                        for(var q=0;q<data.dup_roles.length;q++)
                                        {

                                            $("#currencylistView").append('<li><span class="custName">'+ data.dup_roles[q].attribute_name +'</span>( <span class="cust_tyName">'+ data.dup_roles[q].attribute_type +'</span> )<i class="error-alert">(Duplicate data found)</i><a title="Delete" class="pull-right glyphicon glyphicon-remove-circle"></a></li>');
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

			}
	});
}
/* -------------------------------------------------------------------------------------------------------------- */
function selrow(obj){
    $('#attribute_id').val(obj.attribute_key);
	$("#editmodal .modal-header h4.modal-title").text("Edit Custom Field for "+$("#currency_catt li.active").text());
    $('#edit_custom_field_name').val(obj.attribute_name);
    $('#edit_custom_field_type option[value="'+obj.attribute_type+'"]').prop("selected",true);


}
function update(){
		$(".error-alert").text("");

		if($("#edit_custom_field_name").val()==""){
				$("#edit_custom_field_name").closest("div").find("span").text("Custom Field name is required.");
				$("#edit_custom_field_name").focus();
				return;
		}else{
				$("#edit_custom_field_name").closest("div").find("span").text("");
		}

        if($.trim($("#edit_custom_field_type").val())==""){
                $("#edit_custom_field_type").closest("div").find("span").text("Custom Field Type is required.");
                $("#edit_custom_field_type").focus();
                return;
        }else{

                    $("#edit_custom_field_type").closest("div").find("span").text("");
        }

		var addObj={};
		var liId = $.trim($("#currency_catt li.active").attr('id'));
	    var currency_cat_id = liId.replace('li-','');
		addObj.module = currency_cat_id;
		addObj.field_name = $("#edit_custom_field_name").val();
		addObj.field_type = $("#edit_custom_field_type").val();
        addObj.id = $('#attribute_id').val();

		loaderShow();
		$("#"+currency_cat_id).find("table").dataTable().fnDestroy();
        $.ajax({
                type : "POST",
                url : "<?php echo site_url('admin_customFieldController/update_data'); ?>",
                dataType : 'json',
                data : JSON.stringify(addObj),
                cache : false,
                success : function(data){
                    loaderHide();
                    if(error_handler(data)){
                        return;
                    }
                    str= data;
                        if(str=="0"){

                                $('#alert').modal('show');
								$("#alert .modal-body center span").text("Duplicate Entry");
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
                                loadtable(data);
    					}
                }
            });

}



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
            <input type="hidden" id='attribute_id' name='attribute_id' />
			<div class="col-lg-12 column">
				<div class="row header1">
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Custom Field List"/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Custom Field List</h2>
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<a  href="#addmodal" onclick="compose()" id='curbtn' class="addPlus" data-toggle="modal">
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
								 <h4 class="modal-title">Edit Custom Field</h4>
							</div>
							<div class="modal-body">
							    <div class="row">
									<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <label for="edit_custom_field_name">Custom Field Name*</label>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <input type="text" class="form-control closeinput" name="edit_custom_field_name" id="edit_custom_field_name" autofocus/>
                                        <span class="error-alert"></span>
                                    </div>
								</div>
                                <div class="row">
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <label for="edit_custom_field_type">Custom Field Type*</label>
                                    </div>
                                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                        <select name="edit_custom_field_type" id="edit_custom_field_type" class="form-control closeinput">
                                            <option value="">Choose</option>
                                            <option value="Single_Line_Text">Single Line Text</option>
                                        </select>
                                        <span class="error-alert"></span>
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
								 <h4 class="modal-title">Add Custom Field</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <label for="add_custom_field_name">Custom Field Name*</label>
                                    </div>
                                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                        <input type="text" class="form-control closeinput" name="add_custom_field_name" id="add_custom_field_name" autofocus/>
                                        <span class="error-alert"></span>
                                    </div>
								</div>
                                <div class="row">
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                        <label for="add_custom_field_type">Custom Field Type*</label>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <select name="add_custom_field_type" id="add_custom_field_type" class="form-control closeinput">
                                            <option value="">Choose</option>
                                            <option value="Single_Line_Text">Single Line Text</option>
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
									<div class="col-md-2 col-sm-2 col-xs-2">
										<a title="Add Custom Field" href="#"  id='curbtn1' class="glyphicon glyphicon-plus-sign" onclick="add_currency_list()"></a>
									</div>
                                </div>
								<div class="row">
									<div class="col-md-12">
										<ol id="currencylistView">

										</ol>
										<center class="error-alert"></center>
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
