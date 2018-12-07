<!DOCTYPE html>
<html lang="en">
	<head>	
	<?php $this->load->view('scriptfiles'); ?>
	<style>
	.tab-content {
		width: 100%;
		float: right;
	}
	.tabs-left, .tabs-right {
	  border-bottom: none;
	  padding-top: 2px;
	}
	.tabs-left {
	  border-right: 1px solid #ddd;
	}
	.tabs-right {
	  border-left: 1px solid #ddd;
	}
	.tabs-left li, .tabs-right li {
	  float: none;
	  margin-bottom: 2px;
	}
	.tabs-left li {
	  margin-right: -4px;
	}
	.tabs-right li {
	  margin-left: -1px;
	}
	.tabs-left li.active a,
	.tabs-left li.active a:hover,
	.tabs-left li.active a:focus {
	  border-bottom-color: #ddd;
	  border-right-color: transparent;
	}

	.nav.nav-tabs.tabs-left li{
		min-height:35px;
		line-height:48px;
	}
	#holidayListAdd li{
		    border-bottom: 1px solid #ccc;
			margin-bottom: 5px;
	}
	#holidayListAdd li a.glyphicon.glyphicon-remove-circle {
		float: right;
	}
	#holidayListAdd{
		margin-top:10px;
	}
	#addmodal .glyphicon-plus-sign {
		border: 1px solid;
		padding: 8px;
		border-radius: 0 5px 5px 0px;
		position: absolute;
		right: 0;
		background: #fff;
	}
	.body-content .nav.nav-tabs .active a{
		font-weight: 800 !important;
		color: #b5000a !important;
	}
	</style>
	<script>
		var maindata; 
		function cancel(){
			$('.modal').modal('hide');
			$('#holidayListAdd').empty();
			$('.modal input[type="text"], textarea').val('');
			$('.modal select').val($('.modal select option:first').val());
			$('.modal input[type="radio"]').prop('checked', false);
			$('.modal input[type="checkbox"]').prop('checked', false);
			$(".error-alert").text("");
			$("#addmodal .modal-header input[type=hidden]").remove();
		}
		
		function cancel_dept(){
		$('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first'));
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
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
            $('#rolebtn1').show();
			var activeCalendar = $.trim($("#calendarName li.active").text());
            var activeCalendarID = $("#calendarName li.active a").attr('id');
            $("#addmodal .modal-title").text("").text("Add Role For "+ activeCalendar);

			$("#addmodal .modal-header").append('<input type="hidden" value="'+activeCalendarID+'">');
		}

		function compose1(){
			$("#add_dept").focus();
		}

		function a(){
			$(".addBtns").show();
			$(".addBtns1").hide();
			$("#pghead").show();
			$("#pghead1").hide();
			$("#icon").show();
			$("#icon1").hide();
		}
		function b(){
			roleData();
			$(".addBtns1").show();
			$(".addBtns").hide();
			$("#pghead1").show();
			$("#pghead").hide();
			$("#icon1").show();
			$("#icon").hide();
            $('#rolebtn1').show();
		}

		function loadpage(){

			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_departmentController/get_department'); ?>",
				dataType : 'json',
				cache : false,
				success : function(data){
					loaderHide();
					$('#tablebody').empty();
					var row = "";
					for(var i=0, j=1; i < data.length; i++, j++){
						var rowdata = JSON.stringify(data[i]);
						row += "<tr><td>" + (j) + "</td><td>" + data[i].Department_name + "</td><td><a data-toggle='modal' href='#editmodalDept' onclick='selrow1("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
					}
					$('#tablebody').append(row);
					$('#tablebody').parent("table").DataTable({
																	"aoColumnDefs": [{ "bSortable": false, "aTargets": [2] }]
																});
                    if(data.length > 0){
							$('#holiDayList table').find("table").dataTable().fnDestroy();
        					$("#state").show();
              				}else{
              					$("#state").hide();
              		}
                    /* code for sandbox */
                    if(data.length >= parseInt(recordcount) && parseInt(recordcount)!= 0){
                        $('#deptbtn').hide();
                    }else{
                        $('#deptbtn').show();
                    }

				}
			});	
		}
		function roleData(){
			$('#holiDayList table').find("table").dataTable().fnDestroy();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_roleController/get_userrole/getdept'); ?>",
				dataType : 'json',
				cache : false,
				success : function(data){
					console.log(data)
				loaderHide();				
				$('#calendarName').empty();
				$('#holiDayList').empty();
				var row = "";
				var row1 = "";
				for(var i=0,j=0; i < data.length; i++,j++ ){
					var rowdata = data[i].Department_id;
                    /* ------------------------------------------------------------------------------- */
                    if(i==0){
						row += "<li onclick='loadtable(\""+data[i].Department_id+"\")' class='active' id='pos1'><a id='id"+data[i].Department_id+"' href='#"+data[i].Department_id+"' data-toggle='tab'>" + data[i].Department_name+"</li>";

					}else{
						row += "<li onclick='loadtable(\""+data[i].Department_id+"\")' id='pos1' ><a id='id"+data[i].Department_id+"' href='#"+data[i].Department_id+"' data-toggle='tab'>" + data[i].Department_name+"</li>";

					}
				}
				    $('#calendarName').append(row);
                    loadtable(data[0].Department_id);

				}
			});	
		}

        function loadtable(deptid){
                var addObj={};
                addObj.deptid=deptid;
                 loaderShow();
                 $.ajax({
        			type : "POST",
        			url : "<?php echo site_url('admin_roleController/get_userrole/getrole'); ?>",
        			dataType : 'json',
                    data: JSON.stringify(addObj),
        			cache : false,
        			success : function(data){
                            loaderHide();
        				    if(error_handler(data)){
        							return;
        				    }
                            $('#holiDayList').empty();
                            var row1 = "";
                            var role_data_table ="";
                            i1=0;
                            row1 += '<div class="tab-pane active" id="'+data[i1].Department_id+'">';

                            if(data[i1].hasOwnProperty('role_data')){
                                    row1 += '<table class="table"><thead>';
                            		row1 += "<tr><th width='10%'>Sl No</th><th width='80%'>Role Name</th><th width='10%'></th></tr></thead><tbody>";
                            		for(var i=0; i < data[0].role_data.length; i++ ){
                            			var jsondata=JSON.stringify(data[0].role_data[i]);
                            			row1 += "<tr><td>" + (i+1) + "</td><td>" + data[0].role_data[i].role_name + "</td><td><a data-toggle='modal' href='#editmodal_r' onclick='selrow("+jsondata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
                            		}
                            		row1 += '</tbody></table>';
                                    /* code for sandbox */
                                    if(data[0].role_data.length >= recordcount && parseInt(recordcount)!= 0){
                                        $('#rolebtn').hide();
                                    }else{
                                        $('#rolebtn').show();
                                    }
                            }else{

                                    row1 += "<center style='padding-top: 60px;'><h4>No data available.</h4></center>";
                                    $('#rolebtn').show();
                            }
                            row1 += '</div>';
                            $('#holiDayList').append(row1);

            				$('#holiDayList').find("table").each(function(){
            					$(this).DataTable({"aoColumnDefs": [{
            											"bSortable": false,
            											"aTargets": [2]
            											}
            										]});
            				});
            				$('#holiDayList table').removeAttr("style");

                    }
                 });

	    }


		function add(){
			$(".error-alert").text("");

			var addObj={};
			var  csObj=[];		
			$("#holidayListAdd li").each(function(){			
				csObj.push({
							 "rolename" : $(this).find('span').text()
							});
			})
			if(csObj.length <= 0){
				$("#holidayListAdd").siblings(".error-alert").text("Add Role first.");
				$("#add_role1").focus();
				return;
			}else{
				$("#holidayListAdd").siblings(".error-alert").text("");
			}
			addObj.roleobj = csObj;

            var calid=$.trim($("#holiDayList .tab-pane.active").attr('id'));
			addObj.deprt_id = calid;
	    	loaderShow();
		    //$("#"+calid).find("table").dataTable().fnDestroy();
            
			loaderShow();
			//$('#holiDayList table').find("table").dataTable().fnDestroy();
			$.ajax({
				type : "POST",
			   	url : "<?php echo site_url('admin_roleController/post_data'); ?>",
				dataType : 'json',
				data : JSON.stringify(addObj),
				cache : false,
				success : function(data){
                /* ----------------------------------------------------------------------------------- */
                    				loaderHide();
                    				if(error_handler(data)){
                    					return;
                    				}
                    				//$('#tablebody').empty();
                    				var row = "";
                    				row += loadtable1(data.getdata[0].role_data);
                                    /* code for sandbox */
                                    if(data.getdata[0].role_data.length >= recordcount && parseInt(recordcount)!= 0){
                                        $('#rolebtn').hide();
                                    }else{
                                        $('#rolebtn').show();
                                    }
                    				if(data.dup_roles.length>0)
                                    {
                                          $('#holidayListAdd').html("");
                                                for(var q=0;q<data.dup_roles.length;q++)
                                                {

                                                    $("#holidayListAdd").append('<li><span>'+ data.dup_roles[q].role_name +'</span><i class="error-alert">(Duplicate data found)</i><a title="Delete" class="pull-right glyphicon glyphicon-remove-circle"></a></li>');
                                                }
                                                                  	$("#holidayListAdd li a").each(function(){
                                                        				$(this).click(function(){
                                                        					$(this).closest('li').remove();
                                                        				})
                                                        			});
                                    }
                                    else
                                    {
                                      	cancel();
                                    }

                                    if( $("#"+calid +" table").length > 0){
                                         $("#"+calid).find("table").dataTable().fnDestroy();
                                    }

                    				$("#"+calid).html("").html(row);
                                    $("#"+calid).find("table").each(function(){
                    					$(this).DataTable({"aoColumnDefs": [{
                    											"bSortable": false,
                    											"aTargets": [2]
                    											}
                    										]});
                    				});

                /* ------------------------------------------------------------------------------------------- */

				}
			});
		}
		function selrow(obj){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin_roleController/get_department'); ?>",
				dataType:'json',
				success: function(data) {
				   var select = $("#edit_role"), options = "<option value=''>Select Department</option>";
				   select.empty();      

				   for(var i=0;i<data.length; i++)
				   {
					options += "<option value='"+data[i].Department_id+"'>"+ data[i].Department_name +"</option>";              
				   }
				   select.append(options);
                   $('#edit_role option[value="'+obj.Department_id+'"]').attr("selected",true);
				}
			});
			var activeCalendar = $.trim($("#calendarName li.active").text());
            var activeCalendarID = $("#calendarName li.active a").attr('id');            
            $("#editmodal_r .modal-title").text("").text("Edit role For "+ activeCalendar);
			$("#editmodal_r .modal-header").append('<input type="hidden" value="'+activeCalendarID+'">');
	
			$("#edit_role1").val(obj.role_name);
            $("#edit_role2").val(obj.role_id);
			$(".error-alert").text("");
			
		}
			function edit_save_dept(){

				if($.trim($("#edit_dept").val())==""){
					$("#edit_dept").closest("div").find("span").text("Department is required.");
					$("#edit_dept").focus();
					return;
				}else if(!validate_name($.trim($("#edit_dept").val()))) {
					$("#edit_dept").closest("div").find("span").text("No special characters allowed (except &, _,-,.).");
					$("#edit_dept").focus();
					return;
				}else if(!firstLetterChk($.trim($("#edit_dept").val()))) {
					$("#edit_dept").closest("div").find("span").text("First letter should not be Numeric or Special character.");
					$("#edit_dept").focus();	
					return;
				}else{
					$("#edit_dept").closest("div").find("span").text("");
				}
				var addObj={};
				addObj.deprt_id = $("#edit_dept1").val();

				addObj.deprtmt_name = $.trim($("#edit_dept").val());

				loaderShow();
				$('#tablebody').parent("table").dataTable().fnDestroy();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_departmentController/update_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						loaderHide();
						if(data=="false"){
							$("#alert").modal('show');
							$("#alert .modal-body center span").text("Department already exists.")
							$("#edit_dept").focus();
						}else{
							cancel();
							$('#tablebody').empty();
							var row = "";
							for(i=0; i < data.length; i++ ){						
								var rowdata = JSON.stringify(data[i]);
								row += "<tr><td>" + (i+1) + "</td><td>" + data[i].Department_name + "</td><td><a data-toggle='modal' href='#editmodalDept' onclick='selrow1("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";							
							}					
							$('#tablebody').append(row); 							
							$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [{ "bSortable": false, "aTargets": [2] }]
															});	
                        }	
					}
				});			
		}

		function edit_save(){

				if($.trim($("#edit_role1").val())==""){
					$("#edit_role1").closest("div").find("span").text("Role is required.");
					$("#edit_role1").focus();
					return;
				}else if(!validate_name($.trim($("#edit_role1").val()))) {
					$("#edit_role1").closest("div").find("span").text("No special characters allowed (except &, _,-,.).");
					$("#edit_role1").focus();
					return;
				}else if(!firstLetterChk($.trim($("#edit_role1").val()))) {
					$("#edit_role1").closest("div").find("span").text("First letter should not be Numeric or Special character.");
					$("#edit_role1").focus();
					return;
				}else{
					$("#edit_role1").closest("div").find("span").text("");
				}
				var addObj={};
				var calid=$.trim($("#holiDayList .tab-pane.active").attr('id'));
				addObj.deprt_id = calid;

				addObj.rolename = $.trim($("#edit_role1").val());
                addObj.roleid = $("#edit_role2").val();
			   	loaderShow();
				$("#"+calid).find("table").dataTable().fnDestroy();
				console.log(addObj)
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_roleController/update_data'); ?>",
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
								$("#alert .modal-body center span").text("Role name already exists.")
								$("#"+calid).find("table").DataTable({
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

                				row += loadtable1(data[0].role_data);

                				if( $("#"+calid +" table").length > 0){
                                     $("#"+calid).find("table").dataTable().fnDestroy();
                                }

                				$("#"+calid).html("").html(row);
                                $("#"+calid).find("table").each(function(){
                					$(this).DataTable({"aoColumnDefs": [{
                											"bSortable": false,
                											"aTargets": [2]
                											}
                										]});
                				});

    					}
					}
				});
			}

        function loadtable1(data){
          		var role_data_table ="";
          		role_data_table += '<table class="table"><thead>';
          		role_data_table += "<tr><th width='10%'>Sl No</th><th width='80%'>Role Name</th><th width='10%'></th></tr></thead><tbody>";
          		for(var i=0; i < data.length; i++ ){
          			var jsondata=JSON.stringify(data[i]);
          			role_data_table += "<tr><td>" + (i+1) + "</td><td>" + data[i].role_name + "</td><td><a data-toggle='modal' href='#editmodal_r' onclick='selrow("+jsondata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
          		}
          		role_data_table += '</tbody></table>';
          		return role_data_table;
	    }

		function addDept()
        {
			if($.trim($("#add_dept").val())==""){
				$("#add_dept").closest("div").find("span").text("Department is required.");
				$("#add_dept").focus();				
				return;
			}else if(!validate_name($.trim($("#add_dept").val()))) {
				$("#add_dept").closest("div").find("span").text("No special characters allowed (except &, _,-,.).");
				return;
			}else if(!firstLetterChk($.trim($("#add_dept").val()))) {
				$("#add_dept").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#add_dept").focus();	
				return;
			}
            else
            {
				$("#add_dept").closest("div").find("span").text("");
				var addObj={};
				addObj.deprtmt_name =$.trim($("#add_dept").val());
				loaderShow();
				$('#tablebody').parent("table").dataTable().fnDestroy();
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_departmentController/post_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						loaderHide();
						if(data=="false"){
							$("#alert").modal('show');
							$("#alert .modal-body center span").text("Department already exists.")
							$("#add_dept").focus();
							$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [{ "bSortable": false, "aTargets": [2] }]
															});	
						}else{
							cancel();
							$('#tablebody').empty();
							var row = "";
							for(i=0; i < data.length; i++ ){
								var rowdata = JSON.stringify(data[i]);
								row += "<tr><td>" + (i+1) + "</td><td>" + data[i].Department_name + "</td><td><a data-toggle='modal' href='#editmodalDept' onclick='selrow1("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";							
							}
							$('#tablebody').append(row);  
							
							$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [{ "bSortable": false, "aTargets": [2] }]
															});
                            if(data.length > 0){
							    $('#holiDayList table').find("table").dataTable().fnDestroy();
        					    $("#state").show();
                                roleData();
              				}else{
              					$("#state").hide();
              		        }
                            /* code for sandbox */
                            if(data.length >= parseInt(recordcount) && parseInt(recordcount)!= 0){
                                $('#deptbtn').hide();
                            }else{
                                $('#deptbtn').show();
                            }

                        }							
					}
				});	
			}
		}
		function selrow1(obj){
			$("#edit_dept1").val(obj.Department_id);
			$("#edit_dept").val(obj.Department_name);
			$(".error-alert").text("");
		}
		function add_holiday_list(){
			var rolename = $.trim($("#add_role1").val());

			$(".error-alert").text("")
			/* if(hDate == ""){
				$("#add_holDate").closest("div").siblings(".error-alert").text("Date is required");
				$("#add_holDate").focus();
				return;
			}else{
					$("#add_holDate").closest("div").siblings(".error-alert").text("");
			}
			 */
			if(rolename == ""){
				$("#add_role1").closest("div").find("span").text("Role Name is required");
				$("#add_role1").focus();
				return;
			}else if(!validate_location(rolename)) {
				$("#add_role1").closest("div").find("span").text("No special characters allowed (except &).");
				$("#add_role1").focus();
				return;
			}else if(!firstLetterChk(rolename)) {
				$("#add_role1").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#add_role1").focus();
				return;
			}else{
					$("#add_role1").closest("div").find("span").text("");
			}
			
			var html= 0;



			if($("#holidayListAdd li").length <= 0){
				$("#holidayListAdd").append('<li><span>'+ rolename +'</span><a title="Delete" class="glyphicon glyphicon-remove-circle"></a></li>');
				$("#holidayListAdd").siblings(".error-alert").text("");
                /* code for sandbox */
                if(parseInt(recordcount)!= 0){
                        $('#rolebtn1').hide();
                }else{
                        $('#rolebtn1').show();
                }
			}else{
				$("#holidayListAdd li").each(function(){
					if($(this).find('span').text().trim() == rolename){
						html = 1;
					}
				});
				
				if(html == 1){
					$("#holidayListAdd").siblings(".error-alert").text("Duplicate Role Name.");
					$("#add_role1").focus();
					return;
				}else{
					$("#holidayListAdd").siblings(".error-alert").text("");
					$("#holidayListAdd").append('<li><span>'+ rolename +'</span> <a title="Delete" class="glyphicon glyphicon-remove-circle"></a></li>');
				}
			}


			$("#add_role1").val("");
			$("#holidayListAdd li a").each(function(){
				$(this).click(function(){					
					$(this).closest('li').remove();
                    /* code for sandbox */
                    if($("#holidayListAdd li").length == 0){
                        $('#rolebtn1').show();
                    }
				});
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
		<?php  $this->load->view('demo');  ?>
		<!---------------------------------------------------------
		side nav
		------------------------------------------------------------>		
		
		<?php $this->load->view('admin_sidenav'); ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">				
			<div class="row header1">				
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div id="icon">		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Department List"/>
							</div>
							<div id="icon1" class="none">		
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30" data-toggle="tooltip" data-placement="right" title="Role List"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Departments_and_Roles', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2 id="pghead">Department List</h2>
							<h2 id="pghead1" class="none">Role List</h2>	
					</div>	
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<a href="#addmodaldept" class="addPlus" data-toggle="modal" id='deptbtn' onclick="compose1()">
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
                        </div>
						<div class="addBtns1 none">
							<a href="#addmodal" class="addPlus" data-toggle="modal" id='rolebtn' onclick="compose()">
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
                        </div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="container-fluid">
					
				  <ul class="nav nav-tabs">
					<li class="active" onclick="a()"><a data-toggle="tab" href="#country"><b>Department</b></a></li>
					<li onclick="b()" id="state"><a data-toggle="tab" href="#state1"><b>Role</b></a></li>    
				  </ul>
				  <div class="tab-content tab_countstat">
					<div id="country" class="tab-pane fade in active" >
					 <table class="table" >
						<thead>  
							<tr>			
								<th class="table_header">Sl No</th>
								<th class="table_header">Department</th>
								<th class="table_header"></th>
							</tr>
						</thead>
						<tbody id="tablebody">
						</tbody>    
					</table>
					</div>
					<div id="state1" class="tab-pane fade">
						<div class="col-xs-3 verticle-tab">
							<ul class="nav nav-tabs tabs-left" id="calendarName">
							</ul>
						</div>
						<div class="col-xs-9 tab-col" >
							<div class="tab-content" id="holiDayList">
							</div>
						</div>					
					</div>				
				  </div>
				</div>	
				</div>
			</div>
			<div id="editmodal_r" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="editpopup" class="form">
							<div class="modal-header">
								 <span class="close"  onclick="cancel()">&times;</span>
								 <h4 class="modal-title">Edit Role</h4>
							</div>
							<div class="modal-body">
									<!--<div class="row">
										<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
											<label for="edit_role">Department*</label> 
										</div>
										<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
											<select name="adminContactDept" class="form-control" id="edit_role">
												
											</select>
											<span class="error-alert"></span>
										</div>
									</div>-->
									<div class="row">
										<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
											<label for="edit_role1">Role Name*</label> 
										</div>
										<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
											<input type="hidden" class="form-control closeinput"  id="edit_role2"/>
											<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_role1"/>
											<span class="error-alert"></span>
										</div>
									</div>
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="edit_save()" value="Save">
									<input type="button" class="btn" id="cancle" onclick="cancel()" value="Cancel" >
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="addmodaldept" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form">
							<div class="modal-header">
								<span class="close"  onclick="cancel_dept()">&times;</span>
								<h4 class="modal-title">Add Department</h4>
							</div>
							<div class="modal-body">	
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="add_dept">Department*</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<input type="text" class="form-control closeinput" name="adminContactDept" id="add_dept"/>
										<span class="error-alert"></span>
										
									</div>
								</div>
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="addDept()" value="Save">
									<input type="button" class="btn" onclick="cancel_dept()" value="Cancel" >
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="addmodal" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form">
							<div class="modal-header">
								 <span class="close"  onclick="cancel()">&times;</span>
								 <h4 class="modal-title">Add Role</h4>

							</div>
							<div class="modal-body">
									<div class="row">
										<div class="col-md-3">
											<label for="add_role1">Role Name*</label> 
										</div>
										<div class="col-md-7">
											<input type="text" class="form-control closeinput" name="adminContactDept" id="add_role1" autofocus/>
											<span class="error-alert"></span>
											<input type="hidden" name="addroleCount" id="addroleCount"/>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2">
											<a title="Add Role List"  id='rolebtn1' href="#" class="glyphicon glyphicon-plus-sign" onclick="add_holiday_list()"></a>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<ol id="holidayListAdd">
											
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
			<div id="editmodalDept" class="modal fade" data-backdrop="static" >
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="editpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								<span class="close"  onclick="cancel_dept()">&times;</span>
								<h4 class="modal-title">Edit Department</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="edit_dept">Department*</label> 
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<input type="hidden" class="form-control closeinput"  id="edit_dept1"/>
										<input type="text" class="form-control closeinput" name="adminContactDept" id="edit_dept"/>
										<span class="error-alert"></span>
										
									</div>
								</div>
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" id="save" onclick="edit_save_dept()" value="Save">
									<input type="button" class="btn" id="cancle" onclick="cancel_dept()" value="Cancel" >
							</div>
						</form>
					</div>
				</div>
			</div>
			<!--<div id="modal_upload" class="modal fade" >
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" data-dismiss="modal">&times;</span>
								 <h3>User Role Details</h3>
							</div>
							<div class="modal-body">								
								<center>
									<div class="row">
										<div class="col-md-3">
											<label for="add_role">Add User Role Details*</label> 
										</div>
										<div class="col-md-5">
											<input name="fileUp" type='file' id="fileUp" class='form-control'  file-input="files"/>
											<span class="error-alert"></span>
										</div>										
									</div>									
								</center>
							</div>
							<div class="modal-footer" id="modal_footer">								
									<div class="row">
										<div class="col-md-6">
											<a class="btn btn-primary" href="Fnct_template/userrole.xls">
												Download Template
											</a>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-6">
											<input type="button" class="btn btn-primary" onclick="file()" value="Save">
											<input type="button" class="btn btn-primary" data-dismiss="modal" value="Cancel" >
										</div>
									</div>							
							</div>
						</form>
					</div>
				</div>
			</div>-->
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
		<?php $this->load->view('footer'); ?>

	</body>
</html>
