<html lang="en">
	<head>
	<?php require 'scriptfiles.php' ?>
	<style>
	.modal-backdrop{
		z-index:-1;
	}
	.modal_margin{
		margin-left: 251px;
		margin-top: 105px;
	}
	.error-alert{
		color:red;
	}
	.info-icon div{
		width:50px;
		height:30px;
		margin-top: -36px;margin-left: 14px;
	}

	.header2{
		background:rgb(30, 40, 44);
		padding:2px;
	}
	.pageHeader2{
		text-align:center;		
		color:white;
		height:41px;
		font-size:22px;
		margin-top: 0;
		margin-bottom: 14px;
	}

	.pageHeader2 h2{
		margin-bottom:-20px;
	}
	.column{
		margin-top: -20px;padding:0;
	}
	.addExcel{
		bottom: 30px;
	}
	.addPlus{
		   bottom: 30px;
	}
	.table{
		margin-top:12px;
	}
	.table tbody tr:hover{
		background:white!important;
	}
	.table tbody tr{
		background:white!important;
		border:none!important;
	}
	#num_tiles{
		height: 30px;
		margin-top: 24px;
		margin-bottom: -1px;
	}
	.tiles_style{
		color: white;
		margin-top: 28px;
		margin-left: 58px;
		font-weight: 700!important;
	}
	.table_Drow{
		padding-right: 5px;
		padding-left: 5px;
	}
	.row_margin{
		margin-top: 8px;
	}
	.sub_param{
		line-height:12px;
	}
	.err_position{
		text-align: center;
		margin-left: 38px;
	}
	.modal_custom{
		width:400px;
	}
	.font_bold{
		font-weight:bold!important;
	}
	</style>
	<script>
	function validate_number(name) {
		var nameReg = new RegExp(/^[0-9%]*$/);
		var valid = nameReg.test(name);
		if (!valid) {
			return false;
		} else {
			return true;
		}
}
	var object1,tiles_val;
	function network_err_alert(){
		alert('Something went wrong please Check again');
	}
	function close_modal(){
		$('.modal').modal('hide');
		$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");		
	}
	function select_cust(object){
		if(object=='edit'){
			var option_val = $("#period_edit").val();
			$(".product_start_date_edit").val("");
			$(".product_end_date_edit").val("");
			if(option_val == "Custom"){
				$("#dash_strt_date_e").show();
				$("#dash_end_date_e").show();
			}else{
				$("#dash_strt_date_e").hide();
				$("#dash_end_date_e").hide();
			}
		}else{
			var option_val = $("#period").val();
			$(".product_start_date").val("");
			$(".product_end_date").val("");
			if(option_val == "Custom"){
				$("#dash_strt_date").show();
				$("#dash_end_date").show();
			}else{
				$("#dash_strt_date").hide();
				$("#dash_end_date").hide();
			}
		}
	}
	function reload_tiles(){
		
	}
	 function tiles_Count(){
		 //console.log(object1)
			var val = $("#num_tiles").val();
            if(val>=1)
            {
                	 loaderShow();
                     $.ajax({
        					type : "POST",
        					url : "<?php echo site_url('manager_dashboardsettingController/get_tilecount/'); ?>"+val,
        					dataType : 'json',
        					cache : false,
        					success : function(data){
        						if(error_handler(data)){
        							return;
        						}
        						tiles_val = data;
        						console.log(data)
        						loaderHide();
        						var val_dis = [];
        						$("#dis_area option").each(function(){
        							val_dis.push($(this).val());
        						});
        						console.log(val_dis)
        						$("#dis_area").children('option').hide();
        						$("#dis_area_edit").children('option').hide();
        						$("#dis_area_swap").children('option').hide();
        						for(i=0;i<data;i++)
        						{
        						console.log(val_dis[i+1])
        							if(val_dis[i+1] ==i+1){
        								$("#dis_area option[value=" + val_dis[i+1] + "]").show();
        								$("#dis_area option[value='']").show();
        								$("#dis_area_edit option[value=" + val_dis[i+1] + "]").show();
        								$("#dis_area_edit option[value='']").show();
        								//$("#dis_area_swap option[value=" + val_dis[i+1] + "]").show();
        							}
        							for(h=0;h<object1.length;h++){
        								if(object1[h].display_area==val_dis[i+1]){
        									$("#dis_area option[value=" + val_dis[i+1] + "]").hide();
        									$("#dis_area_edit option[value=" + val_dis[i+1] + "]").hide();
        									//$("#dis_area_swap option[value=" + val_dis[i+1] + "]").hide();
        								}
        							}
        						}
								/* fetching tile index from cookie */
								//var tile = decodeURIComponent(document.cookie).split('=')
								var tile = getCookie('setTileIndex');
								$("#dis_area").val(tile)
        					},
        					error:function(error){
        						network_err_alert();
        					}
        				});
            }




		}
		function getCookie(cname) {
			var name = cname + "=";
			var ca = document.cookie.split(';');
			for(var i = 0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) == ' ') {
					c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
					return c.substring(name.length, c.length);
				}
			}
			return "";
		}
	 $(document).ready(function(){
		 
        /* code for sandbox -- code to set the number of tiles depending on the version  */
		$("#target_call_edit").hide();
        var url1= window.location.href;
        var fileNameIndex1 = url1.lastIndexOf("/") + 1;
        var filename1 = url1.substr(fileNameIndex1);
        sandbox(filename1);

        var rdcount=0;
        rdcount=recordcount;
        var row = "";
        var row_cnt=0;
        while(rdcount)
        {
            row_cnt=row_cnt+3;
            row = "<option value='"+row_cnt+"'>"+row_cnt+"</option>";
            $("#num_tiles").append(row);
            rdcount=rdcount-3;
        }
        loadtable(); // function to set the tile count from usermappingtable and display the dashboard reports already saved in atable
		$(".product_end_date").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:false,
			format:'YYYY-MM-DD',
		});
		$(".product_start_date").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'YYYY-MM-DD',
		});
		$(".product_start_date").on("dp.change", function (selected) {
			var startDateTime = moment($.trim($(this).val()), 'YYYY-MM-DD');
			$(".product_end_date").data("DateTimePicker").minDate(startDateTime);
			
		})
		$(".product_end_date_edit").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:false,
			format:'YYYY-MM-DD',
		});
		$(".product_start_date_edit").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'YYYY-MM-DD',
		});
		$("#target_call, #target_call_edit").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'HH:mm:ss',
		});
		$("#flag_call, #flag_edit_call").datetimepicker({
			ignoreReadonly:true,
			allowInputToggle:true,
			format:'HH:mm:ss',
		});
		$(".product_start_date_edit").on("dp.change", function (selected) {
			var startDateTime = moment($.trim($(this).val()), 'YYYY-MM-DD');
			$(".product_end_date_edit").data("DateTimePicker").minDate(startDateTime);
			
		})
		$(".product_start_date").click(function(){
			$(".bootstrap-datetimepicker-widget").removeClass("top");
			$(".bootstrap-datetimepicker-widget").addClass("bottom");
			$(".bootstrap-datetimepicker-widget").css("bottom","auto");
		});
		
		$("#criteria").on('change', function(){
			var sel = $("#criteria").val();
			if(sel == 'actuals' || sel == ""){
				$("#flag, #flag_call").prop("disabled",true);
				$("#flag, #flag_call").val("");
				$(".error-alert").text("");
			}else{
				$("#flag, #flag_call").prop("disabled",false);
				$("#flag, #flag_call").val("");
				$(".error-alert").text("");
			}
			if($.trim($("#report_name option:selected").text()) == "Call Time"){
				$("#flag").hide();
				$("#flag_call").show();
			}else{
				$("#flag").show();
				$("#flag_call").hide();
			}
		});
		$("#criteria_edit").on('change', function(){
			var sel = $("#criteria_edit").val();
			if(sel == 'actuals' || sel == ""){
				$("#flag_edit, #flag_edit_call").prop("disabled",true);
				$("#flag_edit, #flag_edit_call").val("");
				$(".error-alert").text("");
			}else{
				$("#flag_edit, #flag_edit_call").prop("disabled",false);
				$("#flag_edit, #flag_edit_call").val("");
				$(".error-alert").text("");
			}
			if($.trim($("#report_name_edit option:selected").text()) == "Call Time"){
				$("#flag_edit").hide();
				$("#flag_edit_call").show();
			}else{
				$("#flag_edit").show();
				$("#flag_edit_call").hide();
			}
		});

        $.ajax({
					type : "POST",
					url : "<?php echo site_url('manager_dashboardsettingController/get_reportname_tabledata'); ?>",
					dataType : 'json',
					cache : false,
					success : function(data){
								if(error_handler(data)){
									return;
								}
					            var options='';
							    var select=$("#report_name");
							    var select1=$("#report_name_edit");
                                for(var i=0;i<data.length; i++)
                                {
                            	        options += "<option value='"+data[i].dash_repo_id+"'>"+ data[i].dash_repo_name +"</option>";
                                }
                                select.append(options);
                                select1.append(options);
                               //loadtable();
					},
					error:function(error){
						network_err_alert();
					}
				});
	 });
	 function cancel(){
		 $("input[type='text'],select,textarea").val('');
	 }

        function loadtable(){
			$(".error-alert").empty();
           	$.ajax({
					type : "POST",
					url : "<?php echo site_url('manager_dashboardsettingController/loadtable'); ?>",
					dataType : 'json',
					cache : false,
					success : function(data){
						//loaderHide();
						console.log(data)
								if(error_handler(data)){
									return;
								}
								console.log(data)

								$("#num_tiles").val(data.titlecount);
								var data = data.tabledata;
								/* var area=[];
								$("#dis_area option").each(function(){
									area.push($(this).val());
								}); */
								$('#tablebody').parent("table").dataTable().fnDestroy();
								$("#tablebody").empty();
								var row='';
								object1=data;
                                for(var i=0;i<data.length;i++)
                                {
									/* for(j=0;j<area.length;j++){
										if(data[i].display_area==area[j]){
											$("#dis_area option[value=" + data[i].display_area + "]").hide();
											$("#dis_area_edit option[value=" + data[i].display_area + "]").hide();
										}
									} */
									var name = "";
									var rowdata = JSON.stringify(data[i]);
									$(".table_Drow").removeClass('none');
									name = data[i].chart_type.charAt(0).toUpperCase() + data[i].chart_type.slice(1) + " Chart"  ;
                            	    row +="<tr><td>"+(i+1)+"</td><td>"+data[i].dash_repo_name+"</td><td>"+name+"</td><td>"+data[i].target+"</td><td>"+data[i].frequecy+"</td><td>"+data[i].display_area+"</td><td><a href='#'  onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td><td><a href='#'  onclick='swap("+rowdata+")'><span class='glyphicon glyphicon-sort'></span></a></td><td><a href='#' onclick='del("+data[i].id+")'><span class='glyphicon glyphicon glyphicon-trash'></span></a></td><td><a href='#' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td></tr>";
                                }
								$("#tablebody").append(row);
								$('#tablebody').parent("table").DataTable();
								tiles_Count();						
					},
					error:function(error){
						network_err_alert();
					}
				});
        }
        function getsub_param(){
           /* if($('#parameter').val()=='Individual' || $('#parameter').val()=='Team'){   */
                     	$.ajax({
    					type : "POST",
    					url : "<?php echo site_url('manager_dashboardsettingController/get_subparam/'); ?>"+$('#parameter').val(),
    					dataType : 'json',
    					cache : false,
    					success : function(data){
									if(error_handler(data)){
										return;
									}
    					            var options='';
                                    var opval1='';
    							    var select=$("#sub_parameter");
                                    select.empty();
                                    select, options = "<option value=''>Choose</option>";
                                    select.val('');
                                    if($('#parameter').val()=='Individual')
                                    {
                                       for(var i=0;i<data.length; i++)
                                        {
                                            options += "<option value='"+data[i].user_id+"'>"+ data[i].user_name +"</option>";
                                        }
                                    }
                                    else if($('#parameter').val()=='Team')
                                    {
                                        for(var i=0;i<data.length; i++)
                                        {
                                            options += "<option value='"+data[i].team_id+"'>"+ data[i].teamname +"</option>";
                                        }
                                    }
                                    else
                                    {
                                        for(var i=0;i<data.length; i++)
                                        {
                                            options += "<option value='"+data[i].business_location_id+"'>"+ data[i].bus_locname +"</option>";
                                        }
                                    }

                                    select.append(options);
                             },
							 error:function(error){
								network_err_alert();
							 }
							 
    				});

        }
		function getsub_param1(obj,obj1){
           /* if($('#parameter').val()=='Individual' || $('#parameter').val()=='Team'){   */
		   var obj_val;
		   if(obj==1){
			   obj_val=$('#parameter_edit').val();
		   }else{
			   obj_val=obj;
		   }
                     	$.ajax({
    					type : "POST",
    					url : "<?php echo site_url('manager_dashboardsettingController/get_subparam/'); ?>"+obj_val,
    					dataType : 'json',
    					cache : false,
    					success : function(data){
									if(error_handler(data)){
										return;
									}
    					            var options='';
                                    var opval1='';
    							    var select1=$("#sub_parameter_edit");
                                    select1.empty();
                                    select1, options = "<option value=''>Choose</option>";
                                    select1.val('');
                                    if($('#parameter_edit').val()=='Individual')
                                    {
                                       for(var i=0;i<data.length; i++)
                                        {
                                            options += "<option value='"+data[i].user_id+"'>"+ data[i].user_name +"</option>";
                                        }
                                    }
                                    else if($('#parameter_edit').val()=='Team')
                                    {
                                        for(var i=0;i<data.length; i++)
                                        {
                                            options += "<option value='"+data[i].team_id+"'>"+ data[i].teamname +"</option>";
                                        }
                                    }
                                    else
                                    {
                                        for(var i=0;i<data.length; i++)
                                        {
                                            options += "<option value='"+data[i].business_location_id+"'>"+ data[i].bus_locname +"</option>";
                                        }
                                    }

                                    select1.append(options);
									$('#sub_parameter_edit').val(obj1).prop("selected",true);
                             },
							error:function(error){
								network_err_alert();
							}
							 
    				});
        }


		function genrate_val(){
			var addObj = {};
			var name = $("#target").val();
			var last = name.substr(name.length - 1)
			var name1 = $("#report_name option:selected").text();
			if($("#report_name").val()==""){
				$("#report_name").focus();
				$("#report_name").closest("div").find("span").text("Report Name is required.");
				return;	
			}else{
				$("#report_name").closest("div").find("span").text("");
			}			
			
			if($("#datatype").val()==""){
				$("#datatype").focus();
				$("#datatype").closest("div").find("span").text("Data is required.");
				return;
			}else{
				$("#datatype").closest("div").find("span").text("");
			}if($("#chart_type").val()==""){
				$("#chart_type").focus();
				$("#chart_type").closest("div").find("span").text("Report Type is required.");
				return;	
			}else{
				$("#chart_type").closest("div").find("span").text("");
			}if($("#parameter").val()==""){
				$("#parameter").focus();
				$("#parameter").closest("div").find("span").text("Parameter is required.");
				return;	
			}else{
				$("#parameter").closest("div").find("span").text("");
			}if($("#sub_parameter").val()==""){
				$("#sub_parameter").focus();
				$("#sub_parameter").closest("div").find("span").text("Sub Parameter is required.");
				return;
			}else{
				$("#sub_parameter").closest("div").find("span").text("");
			}
			if($("#datatype").val()!='AsIs'){
				if(name1 == "Productivity Time"){
					if(last == "%"){
						$("#target").closest("div").find("span").text("");
					}else{					
						$("#target").focus();
						$("#target").closest("div").find("span").text("Target should be in %.");
						return;
					}
				}
				if(name1 != "Call Time"){
					if($("#target").val()==""){
						$("#target").focus();
						$("#target").closest("div").find("span").text("Target is required.");
						return;	
					}else if(!validate_number($("#target").val())){					
						$("#target").focus();
						$("#target").closest("div").find("span").text("Only numbers allowed.");
						return;
					}else{
						$("#target").closest("div").find("span").text("");
					}
				}else{
					if($("#target_call").val()==""){
						$("#target_call").closest("div").find("span").text("Target is required.");
						return;	
					}else{
						$("#target_call").closest("div").find("span").text("");
					}
				}
				
				if($("#criteria").val()==""){
					$("#criteria").focus();
					$("#criteria").closest("div").find("span").text("Criteria is required.");
					return;	
				}else{
					$("#criteria").closest("div").find("span").text("");
				}
				if($("#criteria").val()!=='actuals'){	
					if(name1 != "Call Time"){
						if($("#flag").val()==""){
							$("#flag").focus();
							$("#flag").closest("div").find("span").text("Flag is required.");
							return;	
						}else if(!validate_number($("#flag").val())){					
							$("#flag").focus();
							$("#flag").closest("div").find("span").text("Only numbers allowed.");
							return;
						}else{
							$("#flag").closest("div").find("span").text("");
						}	
					}else{
						if($("#flag_call").val()==""){
							$("#flag_call").closest("div").find("span").text("Flag is required.");
							return;	
						}else{
							$("#flag_call").closest("div").find("span").text("");
						}
					}
					
				}
			}
			if($("#period").val()==""){
				$("#period").focus();
				$("#period").closest("div").find("span").text("Period is required.");
				return;	
			}else{
				$("#period").closest("div").find("span").text("");
			}
			if($("#period").val()=='Custom'){
				if($(".product_start_date").val()==""){
					$("#strt_date_err").closest("div").find("span").text("Start date is required.");
					return;	
				}else{
					$("#strt_date_err").closest("div").find("span").text("");
				}if($(".product_end_date").val()==""){
					$("#end_date_err").closest("div").find("span").text("End date is required.");
					return;	
				}else{
					$("#end_date_err").closest("div").find("span").text("");
				}
			}
			addObj.report_id=$("#report_name").val();
			addObj.report_name=$("#report_name option:selected").text();
			addObj.chart_type=$("#chart_type").val();
			addObj.parameter=$("#parameter").val();
			addObj.sub_parameter=$("#sub_parameter").val();
			addObj.period=$("#period").val();
			addObj.datatype=$("#datatype").val();
			if(name1 == "Call Time"){
				addObj.target=$("#target_call").val();
			}else{
				addObj.target=$("#target").val();
			}			
			addObj.criteria=$("#criteria").val();
			if(name1 == "Call Time"){
				addObj.flag=$("#flag_call").val();
			}else{
				addObj.flag=$("#flag").val();
			}			
			addObj.dis_area=$("#dis_area").val();
			addObj.num_tales=$("#num_tiles option:selected").val();
			if($("#period").val()=="Custom"){
				var start_datetime = $(".product_start_date").val();
				var end_datetime1 = $(".product_end_date").val();
			}else{
				var start_datetime ="";
				var end_datetime1 ="";
			}
			addObj.start_date=start_datetime;
			addObj.end_date=end_datetime1;
			loaderShow();
		  	$.ajax({
					type : "POST",
					url : "<?php echo site_url('manager_dashboardsettingController/post_data'); ?>",
                    data : JSON.stringify(addObj),
					dataType : 'json',
					cache : false,
					success : function(data){
						if(error_handler(data)){
							return;
						}
						cancel();
                        if(data)
                        {
							close_modal();
                           loadtable();
                        }
                        else
                        {
                           alert("Duplicate Entry");
							loaderHide();
                        }

					},
					error:function(error){
						network_err_alert();
					}
				});
		}
		function selrow(obj){
			console.log(obj)
			$("#settings_edit").modal('show');
			$("#label_title").text(obj.dash_repo_name);
			getsub_param1(obj.select_param,obj.select_sub_param);
			$("#report_name_edit").val(obj.dash_repo_id);								
			if(obj.dash_repo_name == "Call Time"){
				$("#target_edit, #flag_edit").val('').hide();
				$("#target_call_edit, #flag_edit_call").val('').show();
			}else{
				$("#target_edit, #flag_edit").val('').show();
				$("#target_call_edit, #flag_edit_call").val('').hide();
			}
			if(obj.DATA!='AsIs'){			
				$("#chart_type_edit").val(obj.chart_type);
				$("#parameter_edit").val(obj.select_param);
				$("#sub_parameter_edit").val(obj.select_sub_param);
				$("#period_edit").val(obj.frequecy);
				$("#datatype_edit").val(obj.DATA);
				$("#dis_area_edit").val(obj.display_area);
				$(".product_end_date_edit").val(obj.end_date);
				$(".product_start_date_edit").val(obj.start_date);
				$("#target_edit").val(obj.target);
				$("#target_call_edit").val(obj.target);
				$("#criteria_edit").val(obj.criteria);
				$("#flag_edit").val(obj.flag_value);
				$("#flag_edit_call").val(obj.flag_value);
				$("#target_edit").prop('disabled',false);
				$("#target_call_edit").prop('disabled',false);
				$("#criteria_edit").prop('disabled',false);
				$("#flag_edit").prop('disabled',false);
				if(obj.criteria!="actuals"){				
					$("#flag_edit").prop("disabled",false);
					$("#flag_edit_call").prop("disabled",false);
				}else{				
					$("#flag_edit").prop("disabled",true);
					$("#flag_edit_call").prop("disabled",true);
				}
				$("#chart_type_edit option[value='pie']" ).show();
			}else{
				$("#chart_type_edit").val(obj.chart_type);
				$("#parameter_edit").val(obj.select_param);
				$("#sub_parameter_edit").val(obj.select_sub_param);
				$("#period_edit").val(obj.frequecy);
				$("#datatype_edit").val(obj.DATA);
				$("#dis_area_edit").val(obj.display_area);
				$(".product_end_date_edit").val(obj.end_date);
				$(".product_start_date_edit").val(obj.start_date);
				$("#target_edit").prop('disabled',true);
				$("#target_call_edit").prop('disabled',true);
				$("#criteria_edit").prop('disabled',true);
				$("#flag_edit").prop('disabled',true);
				$("#flag_edit_call").prop('disabled',true);
				$("#chart_type_edit option[value='pie']" ).hide();
			}						
			if(obj.frequecy=='Custom'){
				$("#dash_strt_date_e").show();
				$("#dash_end_date_e").show();
			}else{
				$("#dash_strt_date_e").hide();
				$("#dash_end_date_e").hide();
			}
            $("#edit_id").val(obj.id);

		}
		function viewrow(obj){
			console.log(obj)
			obj.criteria = obj.criteria.split("_").join(" ");
			obj.criteria = obj.criteria.substring(0, 1).toUpperCase() + obj.criteria.substring(1);
			obj.DATA = obj.DATA.split("_").join(" ");
			obj.DATA = obj.DATA.substring(0, 1).toUpperCase() + obj.DATA.substring(1);
			obj.chart_type = obj.chart_type.substring(0, 1).toUpperCase() + obj.chart_type.substring(1);
			$("#settings_v").modal('show');
			$("#label_title_v").text(obj.dash_repo_name);
			$("#report_name_v").text(obj.dash_repo_name);
			$("#chart_type_v").text(obj.chart_type);

            (obj.select_param!='') ? $("#parameter_v").text(obj.select_param) : $("#target_v").text("-");
            (obj.sub_param_name!='') ? $("#sub_parameter_v").text(obj.sub_param_name) : $("#target_v").text("-");
            (obj.frequecy!='') ? $("#period_v").text(obj.frequecy) : $("#target_v").text("-");
            (obj.DATA!='') ? $("#datatype_v").text(obj.DATA) : $("#target_v").text("-");


            (obj.target!='') ? $("#target_v").text(obj.target) : $("#target_v").text("-");
            (obj.criteria!='') ? $("#criteria_v").text(obj.criteria) : $("#criteria_v").text("-");
            (obj.flag_value!='') ? $("#flag_v").text(obj.flag_value) : $("#flag_v").text("-");
            (obj.display_area!='') ? $("#dis_area_v").text(obj.display_area) : $("#dis_area_v").text("-");
            (obj.start_date!='') ? $("#start_date_v").text(obj.start_date) : $("#start_date_v").text("-");
            (obj.end_date!='') ? $("#end_date_v").text(obj.end_date) : $("#end_date_v").text("-");

			if(obj.frequecy=='Custom'){
				$("#dash_strt_date_v").show();
				$("#dash_end_date_v").show();
			}else{
				$("#dash_strt_date_v").hide();
				$("#dash_end_date_v").hide();
			}
		}
		function edit_save()
        {
			var name = $("#target_edit").val();
			var last = name.substr(name.length - 1)
			var name1 = $("#report_name_edit option:selected").text();
			if($("#report_name_edit").val()==""){
				$("#report_name_edit").focus();
				$("#report_name_edit").closest("div").find("span").text("Report Name is required.");
				return;	
			}else{
				$("#report_name_edit").closest("div").find("span").text("");
			}
			if($("#datatype_edit").val()==""){
				$("#datatype_edit").focus();
				$("#datatype_edit").closest("div").find("span").text("Data is required.");
				return;	
			}else{
				$("#datatype_edit").closest("div").find("span").text("");
			}if($("#chart_type_edit").val()==""){
				$("#chart_type_edit").focus();
				$("#chart_type_edit").closest("div").find("span").text("Chart Type is required.");
				return;	
			}else{
				$("#chart_type_edit").closest("div").find("span").text("");
			}if($("#parameter_edit").val()==""){
				$("#parameter_edit").focus();
				$("#parameter_edit").closest("div").find("span").text("Parameter is required.");
				return;	
			}else{
				$("#parameter_edit").closest("div").find("span").text("");
			}if($("#sub_parameter_edit").val()==""){
				$("#sub_parameter_edit").focus();
				$("#sub_parameter_edit").closest("div").find("span").text("Sub Parameter is required.");
				return;
			}else{
				$("#sub_parameter_edit").closest("div").find("span").text("");
			}
			if($("#datatype_edit").val()!='AsIs'){
				if(name1 == "Productivity Time"){
					if(last == "%"){
						$("#target_edit").closest("div").find("span").text("");
					}else{					
						$("#target_edit").focus();
						$("#target_edit").closest("div").find("span").text("Target should be in %.");
						return;
					}
				}
				if(name1 != "Call Time"){
					if($("#target_edit").val()==""){
						$("#target_edit").focus();
						$("#target_edit").closest("div").find("span").text("Target is required.");
						return;	
					}else{
						$("#target_edit").closest("div").find("span").text("");
					}
				}else{
					if($("#target_call_edit").val()==""){
						$("#target_call_edit").closest("div").find("span").text("Target is required.");
						return;	
					}else{
						$("#target_call_edit").closest("div").find("span").text("");
					}
				}				
				if($("#criteria_edit").val()==""){
					$("#criteria_edit").focus();
					$("#criteria_edit").closest("div").find("span").text("Criteria is required.");
					return;	
				}else{
					$("#criteria_edit").closest("div").find("span").text("");
				}
				if($("#criteria_edit").val()!=='actuals'){
					if(name1 != "Call Time"){
						if($("#flag_edit").val()==""){
							$("#flag_edit").focus();
							$("#flag_edit").closest("div").find("span").text("Flag is required.");
							return;	
						}else if(!validate_number($("#flag_edit").val())){					
							$("#flag_edit").focus();
							$("#flag_edit").closest("div").find("span").text("Only numbers allowed.");
							return;
						}else{
							$("#flag_edit").closest("div").find("span").text("");
						}	
					}else{
						if($("#flag_edit_call").val()==""){
							$("#flag_edit_call").closest("div").find("span").text("Flag is required.");
							return;	
						}else{
							$("#flag_edit_call").closest("div").find("span").text("");
						}
					}
					
				}
			}
			if($("#period_edit").val()==""){
				$("#period_edit").focus();
				$("#period_edit").closest("div").find("span").text("Period is required.");
				return;	
			}else{
				$("#period_edit").closest("div").find("span").text("");
			}
			if($("#period_edit").val()=='Custom'){
				if($(".product_start_date_edit").val()==""){
					$("#strt_edit_err").closest("div").find("span").text("Start date is required.");
					return;	
				}else{
					$("#strt_edit_err").closest("div").find("span").text("");
				}if($(".product_end_date_edit").val()==""){
					$("#end_edit_err").closest("div").find("span").text("End date is required.");
					return;	
				}else{
					$("#end_edit_err").closest("div").find("span").text("");
				}
			}

            var addObj = {};
            addObj.id=$("#edit_id").val();
            addObj.report_id=$("#report_name_edit").val();
			addObj.report_name=$("#report_name_edit option:selected").text();
			addObj.chart_type=$("#chart_type_edit").val();
			addObj.parameter=$("#parameter_edit").val();
			addObj.sub_parameter=$("#sub_parameter_edit").val();
			addObj.period=$("#period_edit").val();
			addObj.datatype=$("#datatype_edit").val();
			if(name1 != "Call Time"){
				addObj.target=$("#target_edit").val();
			}else{
				addObj.target=$("#target_call_edit").val();
			}
			
			addObj.criteria=$("#criteria_edit").val();
			if(name1 == "Call Time"){
				addObj.flag=$("#flag_edit_call").val();
			}else{
				addObj.flag=$("#flag_edit").val();
			}			
			addObj.dis_area=$("#dis_area_edit").val();
            if($("#period_edit").val()=="Custom"){
				var start_datetime = $(".product_start_date_edit").val();
				var end_datetime1 = $(".product_end_date_edit").val();
			}else{
				var start_datetime ="";
				var end_datetime1 ="";
			}
			addObj.start_date=start_datetime;
			addObj.end_date=end_datetime1;
			loaderShow();
            	$.ajax({
					type : "POST",
					url : "<?php echo site_url('manager_dashboardsettingController/edit_data'); ?>",
                    data : JSON.stringify(addObj),
					dataType : 'json',
					cache : false,
					success : function(data){
						if(error_handler(data)){
							return;
						}
                        if(data)
                        {
							close_modal();
                           loadtable();
                        }
                        else
                        {
							loaderHide();
                           alert("no changes have been made");
							close_modal();
                        }

					},
					error:function(error){
						network_err_alert();
					}
				});



		}
		function swap(obj){
			$("#swap_display").modal("show");
			var area=[],area1=[];
			$("#dis_area_swap option").each(function(){
				area.push($(this).val());
			});
			console.log(area1)
			for(i=0;i<tiles_val;i++){
				area1.push(i+1);
			}
			console.log(area1)
			for(j=0;j<area.length;j++){
				$("#dis_area_swap option[value=" + area1[j] + "]").show();
				$("#dis_area_swap option[value='']").show();
				if(obj.display_area!=''){
					if(obj.display_area==area[j]){ 
						$("#dis_area_swap option[value=" + obj.display_area + "]").hide();
					}
				}
			}  
			$("#swap_hidden").val(obj.id);
			$("#swap_hidden1").val(obj.display_area);
		}
		function swap_save()
        {
			var addObj={},new_id1='';
			addObj.current_id = $("#swap_hidden").val();
			addObj.current_area = $("#swap_hidden1").val();
			addObj.new_area = $("#dis_area_swap option:selected").val();
			var dis = $("#dis_area_swap option:selected").val();
			for(i=0;i<object1.length;i++){
				if(dis==object1[i].display_area){
					new_id1 = object1[i].id;
				}
			}
			if(new_id1!=''){
				addObj.new_id = new_id1;
			}else{
				addObj.new_id = '';
			}
			loaderShow();
            console.log(addObj);
          	$.ajax({
			type : "POST",
		    url : "<?php echo site_url('manager_dashboardsettingController/swap_data'); ?>",
            data : JSON.stringify(addObj),
			dataType : 'json',
			cache : false,
			success : function(data){
					if(error_handler(data)){
						return;
					}
                      if(data)
                      {
                         alert("Display Area swapped successfully");
						 close_modal();
                         loadtable();
                      }
                      else
                      {
						close_modal();
						loaderHide();
                        alert("Swap could not be done,Please try again");
                      }

			},
			error:function(error){
				network_err_alert();
			}
		});
	  }
		function del(obj){
			$("#alert_display").modal("show");
			$("#alert_hidden").val(obj);

		}
		function del_ok(){
			var obj = $("#alert_hidden").val();
			loaderShow();
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('manager_dashboardsettingController/delete_data/'); ?>"+obj,
				dataType : 'json',
				cache : false,
				success : function(data){
							if(error_handler(data)){
								return;
							}
						  if(data)
						  {
							 alert("Record successfully deleted");
							 close_modal();
							 loadtable();
						  }
						  else
						  {
							close_modal();
							loaderHide();
							alert("Record couldnot be deleted please try again");
						  }

				},
				error:function(error){
					network_err_alert();
				}
			});
		}
		function data_change(data){
			$(".error-alert").empty();
			console.log(data)
			if(data=="add"){
				var selected_val = $("#datatype").val();
				if(selected_val=="AsIs"){
					$("#chart_type" ).val("");
					$("#chart_type option[value='pie']" ).hide();
					$("#target").prop("disabled",true);
					$("#target_call").prop("disabled",true);
					$("#criteria").prop("disabled",true);
					$("#flag").prop("disabled",true);
					$("#target").val("");
					$("#criteria").val("");
					$("#flag").val("");
					/* $("#chart_type").val("");
					$("#parameter").val("");
					$("#sub_parameter").val("");
					$("#dis_area").val("");
					$("#period").val(""); */
				}else{
					$("#chart_type" ).val("");
					$("#chart_type").children('option').show();
					$("#target").prop("disabled",false);
					$("#target_call").prop("disabled",false);
					$("#criteria").prop("disabled",false);
					$("#flag").prop("disabled",false);
					$("#flag").prop("disabled",true);
					$("#target").val("");
					$("#criteria").val("");
					$("#flag").val("");
					/* $("#chart_type").val("");
					$("#parameter").val("");
					$("#sub_parameter").val("");
					$("#dis_area").val("");
					$("#period").val(""); */
				}
			}
			if(data=="edit"){
				var selected_val = $("#datatype_edit").val();
				if(selected_val=="AsIs"){
					$("#chart_type_edit" ).val("");
					//$("#chart_type_edit").children('option').hide();
					$("#chart_type_edit option[value='pie']" ).hide();
					$("#target_edit").prop("disabled",true);
					$("#criteria_edit").prop("disabled",true);
					$("#flag_edit").prop("disabled",true);
					$("#flag_edit_call").prop("disabled",true);
					$("#target_edit").val("");
					$("#target_call_edit").prop("disabled",true);
					$("#criteria_edit").val("");
					$("#flag_edit").val("");
					$("#flag_edit_call").val("");
					/* $("#chart_type_edit").val("");
					$("#parameter_edit").val("");
					$("#sub_parameter_edit").val("");
					$("#dis_area_edit").val("");
					$("#period_edit").val(""); */
				}else{
					$("#chart_type_edit" ).val("");
					$("#chart_type_edit").children('option').show();
					$("#target_edit").prop("disabled",false);
					$("#criteria_edit").prop("disabled",false);
					$("#flag_edit").prop("disabled",true);
					$("#flag_edit_call").prop("disabled",true);
					$("#target_edit").val("");
					$("#target_call_edit").prop("disabled",false);
					$("#criteria_edit").val("");
					$("#flag_edit").val("");
					$("#flag_edit_call").val("");
					/* $("#chart_type_edit").val("");
					$("#parameter_edit").val("");
					$("#sub_parameter_edit").val("");
					$("#dis_area_edit").val("");
					$("#period_edit").val(""); */
				}
			}
		}
		function set_target(value){ 
			if(value == "add"){				
				var name = $("#report_name option:selected").text();
				if(name == "Productivity Time"){
					$("#target").attr("placeholder", "Enter % with target (ex: 2%)");
				}else{
					$("#target").attr("placeholder", "");
				}
				if(name == "Call Time"){
					$("#target, #flag").val('').hide();
					$("#target_call, #flag_call").val('').show();
				}else{
					$("#target, #flag").val('').show();
					$("#target_call, #flag_call").val('').hide();
				}
			}
			if(value == "edit"){
				var name = $("#report_name_edit option:selected").text();
				if(name == "Productivity Time"){
					$("#target_edit").attr("placeholder", "Enter % with target (ex: 2%)");
				}else{
					$("#target_edit").attr("placeholder", "");
				}
				if(name == "Call Time"){
					$("#target_edit, #flag_edit").val('').hide();
					$("#target_call_edit, #flag_edit_call").val('').show();
				}else{
					$("#target_edit, #flag_edit").val('').show();
					$("#target_call_edit, #flag_edit_call").val('').hide();
				}
			}
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
		<?php $this->load->view('manager_sidenav'); ?>

		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
				<div class="row header2">
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 ">
						
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader2 ">
							<h2>Dashboard Settings</h2>		
					</div>
					<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 ">
						<label class="tiles_style">Number of Tiles*</label>
						<input type="hidden" id="alert_hidden" />
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-2 col-lg-2  ">	
						<select class="form-control" id="num_tiles" onchange="tiles_Count()">
							
						</select>
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="row row_margin">
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-4">
								Report Name*
							</div>
							<div class="col-md-8">
								<select class="form-control" id="report_name" onchange="set_target('add')">
									<option value="">Choose</option>
								</select>
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-4">
								Data*
							</div>
							<div class="col-md-8">
								<select class="form-control" id="datatype" onchange="data_change('add')">
									<option value="">Choose</option>
									<option value="AsIs">As Is</option>
									<option value="with_target">With Target</option>
								</select>
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-4">
								Report type*
							</div>
							<div class="col-md-8">
								<select class="form-control" id="chart_type">
									<option value="">Choose</option>
									<option value='line'>Line Chart</option>
									<option value='bar'>Bar Chart</option>
									<option value='column'>Column Chart</option>
									<option value='pie'>Pie Chart</option>
									<option value='text'>Text Chart</option>
								</select>
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="row row_margin">
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-4">
								Parameter*
							</div>
							<div class="col-md-8">
								<select class="form-control" id="parameter" onchange="getsub_param();">
									<option value="">Choose</option>
									<option value='Team'>Team</option>
									<option value='Individual'>Individual</option>
									<option value='Business'>Business Location</option>
								</select>
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-4 sub_param">
								Sub Parameter*
							</div>
							<div class="col-md-8">
								<select class="form-control" id="sub_parameter">
									<option value="">Choose</option>
								</select>
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-4">
								Target*
							</div>
							<div class="col-md-8">
								<input type="text" class="form-control" placeholder="" id="target"/>
								<input type="text" class="form-control none" placeholder="hh:mm:ss" id="target_call"/>

								<span class="error-alert"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="row row_margin">
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-4">
								Criteria*
							</div>
							<div class="col-md-8">
								<select class="form-control" id="criteria">
									<option value="">Choose</option>
									<option value="flag_above">Flag - Above</option>
									<option value="flag_below">Flag - Below</option>
									<option value="actuals">Actuals</option>
								</select>
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-4">
								Flag Value*
							</div>
							<div class="col-md-8">
								<input type="text" class="form-control" id="flag" disabled />
								<input type="text" class="form-control none" placeholder="hh:mm:ss" id="flag_call" disabled />
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-4">
								Display Area
							</div>
							<div class="col-md-8">
								<select class="form-control" id="dis_area">
									<option value="">Choose</option>
									<option value="1" class="none">1</option>
									<option value="2" class="none">2</option>
									<option value="3" class="none">3</option>
									<option value="4" class="none">4</option>
									<option value="5" class="none">5</option>
									<option value="6" class="none">6</option>
									<option value="7" class="none">7</option>
									<option value="8" class="none">8</option>
									<option value="9" class="none">9</option>
									<option value="10" class="none">10</option>
									<option value="11" class="none">11</option>
									<option value="12" class="none">12</option>
									<option value="13" class="none">13</option>
									<option value="14" class="none">14</option>
									<option value="15" class="none">15</option>
									<option value="16" class="none">16</option>
									<option value="17" class="none">17</option>
									<option value="18" class="none">18</option>
								</select>
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="row row_margin">
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-4">
								Period*
							</div>
							<div class="col-md-8">
								<select class="form-control" id="period" onchange="select_cust('add')">
									<option value="">Choose</option>
									<option value='Daily'>Daily</option>
									<option value='Weekly'>Weekly</option>
									<option value='Monthly'>Monthly</option>
									<option value='Quaterly'>Quarterly</option>
									<option value='Custom'>Custom</option>
								</select>
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="row none" id="dash_strt_date">
							<div class="col-md-4">
								Start Date*
							</div>
							<div class="col-md-8">
								<input class="form-control product_start_date" placeholder="DD-MM-YYYY" />
							</div>
							<div class="err_position" id="strt_date_err">
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="row none" id="dash_end_date">
							<div class="col-md-4">
								End Date*
							</div>
							<div class="col-md-8">
								<input class="form-control product_end_date" placeholder="DD-MM-YYYY" />
							</div>
							<div class="err_position" id="end_date_err">
								<span class="error-alert"></span>
							</div>
						</div>
					</div>
				</div>
				<center style="margin:10px 0 10px 0">
					<input type="button" class="btn" onclick="genrate_val()" value="Generate" />
				</center>
				<div class="row table_Drow none">
					<table class="table">
						<thead>
							<th>Sl No</th>
							<th>Report Name</th>
							<th>Report Type</th>
							<th>Target</th>
							<th>period</th>
							<th>Display Area</th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</thead>
						<tbody id="tablebody">
						
						</tbody>
					</table>
				</div>
         </div>	
		<div id="settings_edit" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dailog modal-lg modal_margin">
				<div class="modal-content">
					<div class="modal-header">
						<span class="close"  onclick="close_modal()">&times;</span>
                        <h4 class="modal-title">Edit&nbsp;<label id="label_title"></label></h4>
					</div>
					<div class="modal-body">
						<div class="row row_margin">
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-4">
										Report Name*
									</div>
									<div class="col-md-8">
										<select class="form-control" id="report_name_edit" onchange="set_target('edit')">
											<option value="">Choose</option>
										</select>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-4">
										Data*
									</div>
									<div class="col-md-8">
										<select class="form-control" id="datatype_edit" onchange="data_change('edit')">
											<option value="">Choose</option>
											<option value="AsIs">As Is</option>
											<option value="with_target">With Target</option>
										</select>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-4">
										Report type*
									</div>
									<div class="col-md-8">
										<select class="form-control" id="chart_type_edit">
											<option value="">Choose</option>
											<option value='line'>Line Chart</option>
											<option value='bar'>Bar Chart</option>
											<option value='column'>Column Chart</option>
											<option value='pie'>Pie Chart</option>
											<option value='text'>Text Chart</option>
										</select>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
						</div>
						<div class="row row_margin">
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-4">
										Parameter*
									</div>
									<div class="col-md-8">
										<select class="form-control" id="parameter_edit" onchange="getsub_param1(1);">
											<option value="">Choose</option>
											<option value='Team'>Team</option>
											<option value='Individual'>Individual</option>
											<option value='Business'>Business Location</option>
										</select>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-4 sub_param">
										Sub Parameter*
									</div>
									<div class="col-md-8">
										<select class="form-control" id="sub_parameter_edit">
											<option value="">Choose</option>
										</select>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-4">
										Target*
									</div>
									<div class="col-md-8">
										<input type="text" class="form-control" id="target_edit"/>
										<input type="text" class="form-control" placeholder="hh:mm:ss" id="target_call_edit"/>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
						</div>
						<div class="row row_margin">
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-4">
										Criteria*
									</div>
									<div class="col-md-8">
										<select class="form-control" id="criteria_edit">
											<option value="">Choose</option>
											<option value="flag_above">Flag - Above</option>
											<option value="flag_below">Flag - Below</option>
											<option value="actuals">Actuals</option>
										</select>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-4">
										Flag Value*
									</div>
									<div class="col-md-8">
										<input type="text" class="form-control" id="flag_edit" disabled />
										<input type="text" class="form-control none" placeholder="hh:mm:ss" id="flag_edit_call" disabled />
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-4">
										Display Area
									</div>
									<div class="col-md-8">
										<select class="form-control" id="dis_area_edit">
											<option value="">Choose</option>
											<option value="1" class="none">1</option>
											<option value="2" class="none">2</option>
											<option value="3" class="none">3</option>
											<option value="4" class="none">4</option>
											<option value="5" class="none">5</option>
											<option value="6" class="none">6</option>
											<option value="7" class="none">7</option>
											<option value="8" class="none">8</option>
											<option value="9" class="none">9</option>
											<option value="10" class="none">10</option>
											<option value="11" class="none">11</option>
											<option value="12" class="none">12</option>
											<option value="13" class="none">13</option>
											<option value="14" class="none">14</option>
											<option value="15" class="none">15</option>
											<option value="16" class="none">16</option>
											<option value="17" class="none">17</option>
											<option value="18" class="none">18</option>
										</select>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
						</div>
						<div class="row row_margin">
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-4">
										Period*
									</div>
									<div class="col-md-8">
										<select class="form-control" id="period_edit" onchange="select_cust('edit')">
											<option value="">Choose</option>
											<option value='Daily'>Daily</option>
											<option value='Weekly'>Weekly</option>
											<option value='Monthly'>Monthly</option>
											<option value='Quaterly'>Quaterly</option>
											<option value='Custom'>Custom</option>
										</select>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row" id="dash_strt_date_e">
									<div class="col-md-4">
										Start Date*
									</div>
									<div class="col-md-8">
										<input class="form-control product_start_date_edit" placeholder="DD-MM-YYYY" id='start_date_edit' />
									</div>
									<div class="err_position" id="strt_edit_err">
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row " id="dash_end_date_e">
									<div class="col-md-4">
										End Date*
									</div>
									<div class="col-md-8">
										<input class="form-control product_end_date_edit" placeholder="DD-MM-YYYY"   />
									</div>
									<div class="err_position" id="end_edit_err">
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
                    	<input type="hidden" class="form-control" id="edit_id"/>
						<input type="button" class="btn" onclick="edit_save()" value="Save">
						<input type="button" class="btn" onclick="close_modal()" value="Cancel">
					</div>
				</div>
			</div>
		</div>
		<div id="settings_v" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dailog modal-lg modal_margin">
				<div class="modal-content">
					<div class="modal-header">
						<span class="close"  onclick="close_modal()">&times;</span>
                        <h4 class="modal-title">View&nbsp;<label id="label_title_v"></label></h4>
					</div>
					<div class="modal-body">
						<div class="row row_margin">
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-6">
										<label class="font_bold">Report Name</label>
									</div>
									<div class="col-md-6">
										<label id="report_name_v"></label>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-6">
										<label class="font_bold">Data</label>
									</div>
									<div class="col-md-6">
										<label id="datatype_v"></label>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-6">
										<label class="font_bold">Report type</label>
									</div>
									<div class="col-md-6">
										<label id="chart_type_v"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="row row_margin">
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-6">
										<label class="font_bold">Parameter</label>
									</div>
									<div class="col-md-6">
										<label id="parameter_v"></label>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-6 sub_param">
										<label class="font_bold">Sub Parameter</label>
									</div>
									<div class="col-md-6">
										<label id="sub_parameter_v"></label>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-6">
										<label class="font_bold">Target</label>
									</div>
									<div class="col-md-6">
										<label id="target_v"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="row row_margin">
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-6">
										<label class="font_bold">Criteria</label>
									</div>
									<div class="col-md-6">
										<label id="criteria_v"></label>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-6">
										<label class="font_bold">Flag Value</label>
									</div>
									<div class="col-md-6">
										<label id="flag_v"></label>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-6">
										<label class="font_bold">Display Area</label>
									</div>
									<div class="col-md-6">
										<label id="dis_area_v"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="row row_margin">
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-6">
										<label class="font_bold">Period</label>
									</div>
									<div class="col-md-6">
										<label id="period_v"></label>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row" id="dash_strt_date_v">
									<div class="col-md-6">
										<label class="font_bold">Start Date</label>
									</div>
									<div class="col-md-6">
										<label id="start_date_v"></label>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row " id="dash_end_date_v">
									<div class="col-md-6">
										<label class="font_bold">End Date</label>
									</div>
									<div class="col-md-6">
										<label id="end_date_v"></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">

					   <!--	<input type="button" class="btn" onclick="edit_save()" value="Save">  -->
						<input type="button" class="btn" onclick="close_modal()" value="Cancel">
					</div>
				</div>
			</div>
		</div>
		<div id="swap_display" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal_custom">
				<div class="modal-content">
					<div class="modal-header">
						<span class="close" onclick="close_modal()">&times;</span>
						<h4 class="modal-title">Swap <label id="swap_name"></label></h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-3">
								Display Area
							</div>
							<div class="col-md-8">
								<select class="form-control" id="dis_area_swap">
									<option value="">Choose</option>
									<option value="1" class="none">1</option>
									<option value="2" class="none">2</option>
									<option value="3" class="none">3</option>
									<option value="4" class="none">4</option>
									<option value="5" class="none">5</option>
									<option value="6" class="none">6</option>
									<option value="7" class="none">7</option>
									<option value="8" class="none">8</option>
									<option value="9" class="none">9</option>
									<option value="10" class="none">10</option>
									<option value="11" class="none">11</option>
									<option value="12" class="none">12</option>
									<option value="13" class="none">13</option>
									<option value="14" class="none">14</option>
									<option value="15" class="none">15</option>
									<option value="16" class="none">16</option>
									<option value="17" class="none">17</option>
									<option value="18" class="none">18</option>
								</select>
								<span class="error-alert"></span>	
								<input type="hidden" id="swap_hidden" />
								<input type="hidden" id="swap_hidden1" />
							</div>
						</div>
					</div>

					<div class="modal-footer">
					   <input type="button" class="btn" value="Save" onclick="swap_save()">                           
					   <input type="button" class="btn" value="Cancel" onclick="close_modal()">                           
					</div>
				</div>
			</div>
		</div>
		<div id="alert_display" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal_custom">
				<div class="modal-content">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<span>Do you really want to delete this?</span> 
							</div>
						</div>
					</div>

					<div class="modal-footer">
					   <input type="button" class="btn" value="Yes" onclick="del_ok()">                           
					   <input type="button" class="btn" value="No" onclick="close_modal()">                           
					</div>
				</div>
			</div>
		</div>
	</div>
		        <?php require 'footer.php' ?>

	</body>
</html>
