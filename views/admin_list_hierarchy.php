<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('scriptfiles'); ?>
        <style>
            .subscript{font-size:xx-small; vertical-align:bottom;}
            #display11 tr td,
            #displayEdit tr td{
                text-align: left;
            }
            .static{
				position: fixed;
				bottom: 8px;
				right: 37%;
            }
            .alert.alert-info{
                background-color: #00c0ef !important;
                border-color: #bce8f1;
                padding:4px;
            }
            #editmodal .alert.alert-info{
			color: #31708f;
			/*background-color: #23527c !important;*/
			background-color: rgba(102, 102, 102, 0.66) !important;
			border-color: #bce8f1;
			padding:1px 5px;
		}

        </style>
        <script>

            $(document).ready(function(){
                loadpage();
            });

/* ------------------------------------------ on load populate list --------------------------------------------------------------------------------- */

            function loadpage(){
              $.ajax({
                   type : "post",
                   url : "<?php echo site_url('admin_rolesHierarchyController/get_levelcount'); ?>",
                   dataType : "json",
                   cache : false,
                   success : function(data){

						$('#tablebody').parent("table").dataTable().fnDestroy();
						loaderHide();
						if(error_handler(data)){
							return;
						}
                            $('.hidlevel_cnt').text(data.str);
                            $('.closeinput').val('');
            				$('#tablebody').empty();
            				var row = "";
                            var mainOrder=[];
            				for(i=0; i < data.records.length; i++ ){
            					var rowdata = JSON.stringify(data.records[i]);

                                var str="";
                                for(var a=0;a < data.records[i]['levelname'].length; a++){
                                    str+='<li>'+ data.records[i].levelname[a].attribute_name +'</li>';
                                }
            					row += "<tr  id='"+rowdata+"'><td> Level " + data.records[i].role_value + "</td><td style='text-align: left;'><ul>"+ str +  "</ul></td><td><a onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
                                 mainOrder.push(data.records[i].role_value);
                            }

            				$('#tablebody').append(row);
							$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [
																	{
																		"bSortable": false,
																		"aTargets": [2] }
																	]
															  });

                           /*  //dragable row--------------- */
            				/*$(function() {
            					$( ".ui-sortable" ).sortable({
            						placeholder: "ui-state-highlight"
            					});

            				});*/
                    }
                });
            }

/* ---------------------------------------------------------------------------------------------------------------------------------------------------- */
/* --------------------------------------------- populate the select box on click of plus sign   ------------------------------------------------------- */

            function compose(){
                $.ajax({
                   type : "post",
                   url : "<?php echo site_url('admin_rolesHierarchyController/get_user_roles'); ?>",
                   dataType : "json",
                   cache : false,
                   success : function(data){
						if(error_handler(data)){
							return;
						}
                        var row="";
                        $('#rolelist').empty();
                        row+="<option value=''>--Choose Roles--</option>";
                        for(var i=0;i<data.length;i++){
                            row+="<option value='"+data[i].roleid+"'>"+data[i].rolename+" ("+data[i].deptname+") </option>";
                        }
                        $('#rolelist').append(row);
                        getLevels();
                    }
                });
            }
            var lastLevel=0;
           function getLevels(){
                $.ajax({
                   type : "post",
                   url : "<?php echo site_url('admin_rolesHierarchyController/get_levels'); ?>",
                   dataType : "json",
                   cache : false,
                   success : function(data){
						if(error_handler(data)){
							return;
						}
                        var row="";
                        $('#levelSeq').empty();
                        $('#levelSeqE').empty();
                        row+="<option value=''>--Select Levels--</option>";
                        for(var i=0;i<data.length;i++){
                            row+="<option value='"+data[i].role_value+"' selected>Level "+data[i].role_value+"</option>";
                        }
                        $('#levelSeq').append(row);
                        $('#levelSeqE').append(row);
                        lastLevel = data.length;
                        if(data.length !=0){
                            $('.level_div').removeClass('none');
                        }

                    }
                });
            }

/* ------------------------------------------------------------------------------------------------------------------------------------------------------ */
           function level_onchngfn(){
                     if($('#before').prop('checked') == true){
                              if($('#levelSeq').val() == ""){
                                        $("#levelSeq").closest("div").find("span").text("Please Select Level");
                                        $(".hidlevel_cnt").text("");
                              }else{
                                  $("#levelSeq").closest("div").find("span").text("");
                                  if(parseInt($('#levelSeq').val())==1){
                                      $(".hidlevel_cnt").text(parseInt($('#levelSeq').val()));
                                  }else{
                                      $(".hidlevel_cnt").text(parseInt($('#levelSeq').val()) - 1);
                                  }
                              }
          		   }
                   if($('#after').prop('checked') == true){
                              if($('#levelSeq').val() == ""){
                                        $("#levelSeq").closest("div").find("span").text("Please Select Level");
                                        $(".hidlevel_cnt").text("");
                              }else{
                                  $("#levelSeq").closest("div").find("span").text("");
                                  $(".hidlevel_cnt").text(parseInt($('#levelSeq').val()) + 1);
                              }
          		   }
        }
/* ---------------------------------------------- on click of pencil incon fill list in pop up -------------------------------------------------------------- */

            function selrow(obj){
                //compose();
                var addObj = {};
				addObj.role_value =obj.role_value;
                $.ajax({
                   type : "post",
                   url : "<?php echo site_url('admin_rolesHierarchyController/get_user_rolesE'); ?>",
                   dataType : "json",
                   data: JSON.stringify(addObj),
                   cache : false,
                   success : function(data){

						if(error_handler(data)){
							return;
						}
                        var row="";
                        $('#rolelistEdit').empty();

                        row+="<option value=''>--Choose Roles--</option>";
                        for(var i=0;i<data.selrow_role.length;i++){
                            row+="<option value='"+data.selrow_role[i].roleid+"_"+data.selrow_role[i].role_value+"'>"+data.selrow_role[i].rolename+" ("+data.selrow_role[i].deptname+") </option>";
                        }
                        $('#rolelistEdit').append(row);

                        row="";
                        $('#rolelistEdit1').empty();

                        row+="<option value=''>--Choose Roles--</option>";
                        for(var i=0;i<data.chk_role.length;i++){
                            row+="<option value='"+data.chk_role[i].roleid+"_"+data.chk_role[i].role_value+"'>"+data.chk_role[i].rolename+" ("+data.chk_role[i].deptname+") </option>";
                        }
                        $('#rolelistEdit1').append(row);

                    }
                });
                $('#editmodal').modal('show');
                /*var html=""
                for(i=0; i< obj.levelname.length; i++ ){
                   html+=  "<tr id="+ obj.levelname[i].role_id +"><td>"+ obj.levelname[i].attribute_name +" </td><td><a class='glyphicon glyphicon-remove-sign' onclick='del(\""+obj.levelname[i].role_id+"\",\"edit\",\"editC\")'></a></td></tr>"
                }
                $("#displayEdit").html(html);*/
                $('.edit_hidlevel_cnt').text(obj.role_value);
                $('.edit_hidlevel_cnt1').text(obj.role_value);
            }

/* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */
/* -------------------------------------------- on change of select option hide the select value(on add function) ------------------------------------------------ */

            function selectedOpt(){
          		var match=1;
                var id = $("#rolelist").val();
                var name = $("#rolelist option:selected").text();
          		$("#display11 tr").each(function(){
          			if($(this).attr("id")== $("#rolelist").val()){
          				match=0;
          			}
          		})
          		if(match==0){
          			return;
          		}
          		if($("#rolelist").val() != ""){
          			$("#display11").append("<tr id="+ id +"><td>"+ name +" </td><td><a class='glyphicon glyphicon-remove-sign' onclick='del(\""+id+"\")'></a></td></tr>");
          		}

                $("#rolelist option:selected").css('display','none');
          		$('#rolelist').val($('#rolelist option:first').val());
                $("#rolelist").closest("div").find("span").text("");
	        }

/* -------------------------------------------------------------------------------------------------------------------------------------------------- */

/* -------------------------------------------- on change of select option hide the select value(on edit function) ------------------------------------------------ */

            function editSelectedOpt(){
                if($('#chk_level').prop('checked') == false){
                  		var match=1;
                        var id=$("#rolelistEdit").val();
                        var name = $("#rolelistEdit option:selected").text();
                  		$("#displayEdit tr").each(function(){
                  			if($(this).attr("id")== $("#rolelistEdit").val()){
                  				match=0;
                  			}
                  		})
                  		if(match==0){
                  			return;
                  		}
                  		if($("#rolelistEdit").val() != ""){
                  			$("#displayEdit").append("<tr id="+ id +"><td>"+ name +" </td><td><a class='glyphicon glyphicon-remove-sign' onclick='del(\""+id+"\",\"edit\")'></a></td></tr>");
                  		}

                        $("#rolelistEdit option:selected").css('display','none');
                  		$('#rolelistEdit').val($('#rolelistEdit option:first').val());
                }else{
                        var match=1;
                        var id=$("#rolelistEdit1").val();
                        var name = $("#rolelistEdit1 option:selected").text();
                  		$("#displayEdit1 tr").each(function(){
                  			if($(this).attr("id")== $("#rolelistEdit1").val()){
                  				match=0;
                  			}
                  		})
                  		if(match==0){
                  			return;
                  		}
                  		if($("#rolelistEdit1").val() != ""){
                  			$("#displayEdit1").append("<tr id="+ id +"><td>"+ name +" </td><td><a class='glyphicon glyphicon-remove-sign' onclick='del(\""+id+"\",\"edit\")'></a></td></tr>");
                  		}

                        $("#rolelistEdit1 option:selected").css('display','none');
                  		$('#rolelistEdit1').val($('#rolelistEdit1 option:first').val());
                }
				editSave()
	        }

/* -------------------------------------------------------------------------------------------------------------------------------------------------- */

/* ------------------------------------------------ common remove function for both edit and add of select option -------------------------------------------- */

            function del(id,str){
                if(str=="edit"){
					if($("#displayEdit tr").length != 0){
						$("body").append('<div class="mask custom-alert"></div><div class="alert alert-danger row custom-alert"> <div class="col-md-9 style="color:#fff""> Do you wish to remove! </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
						$(".Ok").click(function(){
							$("#"+id).remove();
							$('#rolelist option[value='+id+']').css('display','block');
							$('#rolelistEdit option[value='+id+']').css('display','block');

							$(".custom-alert").remove()
							var addObj = {};
							addObj.role_id =id;
							loaderShow();
							$.ajax({
								type : "POST",
								url : "<?php echo site_url('admin_rolesHierarchyController/remove_role'); ?>",
								dataType : 'json',
								data: JSON.stringify(addObj),
								cache : false,
								success : function(data){
									loaderHide();
									if(error_handler(data)){
										return;
									}
									compose();
								}
							});
						});
						$(".notOk").click(function(){
							$(".custom-alert").remove();
						});
					}
                }else{
					$("#"+id).remove();
					$('#rolelist option[value='+id+']').css('display','block');
					$('#rolelistEdit option[value='+id+']').css('display','block');
					$('#rolelistEdit1 option[value='+id+']').css('display','block');

				}
        	}
/* --------------------------------------------------------- save function -------------------------------------------------------------------------------- */

            function add(){
            		var addObj = {};
            		var selectedOptArray1 = [];
            		$("#display11 tr").each(function(){
            			selectedOptArray1.push($(this).attr("id"));
            		})
            		if(selectedOptArray1.length > 0){
                        $("#rolelist").closest("div").find("span").text("");
            		}else{
            			$("#rolelist").closest("div").find("span").text("Please Select Roles");
            			return;
            		}
                    if(parseInt(lastLevel)>0){
                          if($('#levelSeq').val() == ""){
                                $("#levelSeq").closest("div").find("span").text("Please Select Level");
                          }else{
                                $("#levelSeq").closest("div").find("span").text("");
                          }
                          addObj.levelCount = $('#levelSeq').val();
                    }else{
                         addObj.levelCount = 0;
                    }
            		addObj.rolesid = selectedOptArray1;

                    if($('#before').prop('checked') == true){
                         var status="before";
                         addObj.status=status;
                    }else if($('#after').prop('checked') == true){
                         var status="after";
                         addObj.status=status;
                    }
					loaderShow();

                    $.ajax({
            				type : "POST",
            				url : "<?php echo site_url('admin_rolesHierarchyController/post_data'); ?>",
            				dataType : 'json',
                            data: JSON.stringify(addObj),
            				cache : false,
            				success : function(data){
								loaderHide();
								if(error_handler(data)){
									return;
								}
                                cancel();
                                loadpage();
            				}
                    });
        	}
/* ------------------------------------------------------------------------------------------------------------------------------------------------------------ */

/* -------------------------------------------------- save function on edit  ------------------------------------------------------------------------------ */
        var arr_userid=[]; // global array
        function editSave(){
                    var addObj = {};
            		var selectedOptArray1 = [];
					var lastrow = "";
                    var new_levelcnt11="";
                    if($('#chk_level').prop('checked') == false){
                		$("#displayEdit tr").each(function(){
                            var value=$(this).attr("id");
                            var val1=value.split("_");
                            var id1 = val1[0];
                            new_levelcnt11 = val1[1];
                            addObj.new_levelcnt=new_levelcnt11;
                			selectedOptArray1.push(id1);
    						lastrow = $(this).attr("id");
                		});
                    }else{
                        $("#displayEdit1 tr").each(function(){
                            var value=$(this).attr("id");
                            var val1=value.split("_");
                            var id1 = val1[0];
                            new_levelcnt11 = val1[1];
                            addObj.new_levelcnt=new_levelcnt11;
                			selectedOptArray1.push(id1);
    						lastrow = $(this).attr("id");
                		});
                    }
            		if(selectedOptArray1.length > 0){

            		}else{
            			alert("select atleast 1");
            			return;
            		}
            		addObj.rolesid = selectedOptArray1.toString();
                    level_onchngfnE();
                    if($('#chk_level').prop('checked') == false){
                        addObj.levelCount = $('.edit_hidlevel_cnt').text();
                        addObj.chk="false";
                    }else{
                        addObj.levelCount = $("#hidden_cnt").val();
                        addObj.chk="true";
                    }

                    console.log(addObj);
                    if($('#chk_level').prop('checked') == false){
                          if(new_levelcnt11 == "0" || new_levelcnt11==0){
                            $("body").append('<div class="mask custom-alert"></div><div class="alert row custom-alert" style="background:#3c8dbc"> <div class="col-md-9" style="color:#fff"> Do You Really Wish to add Role to selected Level? </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
                          }else{
                            $("body").append('<div class="mask custom-alert"></div><div class="alert row custom-alert" style="background:#3c8dbc"> <div class="col-md-9" style="color:#fff"> The Role Selected is of Level'+new_levelcnt11+'. Do You Really Wish to Move? </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
                          }
                    }else{
                          $("body").append('<div class="mask custom-alert"></div><div class="alert row custom-alert" style="background:#3c8dbc"> <div class="col-md-9" style="color:#fff"> Do You Really Wish to add Role to selected Level? </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
                    }
					$(".Ok").click(function(){
    					  //if($('#chk_level').prop('checked') == false){
                              first_editselect(addObj);
                          //}
					});
					$(".notOk").click(function(){
						$(".custom-alert").remove();
                        if($('#chk_level').prop('checked') == false){
      						$("#displayEdit tr").each(function(){
      							if($(this).attr("id")== lastrow){
      								del(lastrow,"")
      							}
      						});
                        }else{
                            $("#displayEdit1 tr").each(function(){
      							if($(this).attr("id")== lastrow){
      								del(lastrow,"")
      							}
      						});

                        }
					});

        	}

            /* ------------------------------------------------------------------------------------------------------------------------------------------------------ */
           function level_onchngfnE(){
                     if($('#beforeE').prop('checked') == true){
                                $('#levelSeqE option[value=1]').css('display','none');
                              if($('#levelSeqE').val() == ""){
                                        $("#levelSeqE").closest("div").find("span").text("Please Select Level");
                                        $("#hidden_cnt").val("");
                              }else{
                                  $("#levelSeqE").closest("div").find("span").text("");
                                  if(parseInt($('#levelSeqE').val())==1){
                                      $("#hidden_cnt").val(parseInt($('#levelSeqE').val()));
                                  }else{
                                      $("#hidden_cnt").val(parseInt($('#levelSeqE').val()) - 1);
                                  }
                              }
          		   }
                   if($('#afterE').prop('checked') == true){
                                $('#levelSeqE option[value=1]').css('display','block');
                              if($('#levelSeqE').val() == ""){
                                        $("#levelSeqE").closest("div").find("span").text("Please Select Level");
                                        $("#hidden_cnt").val("");
                              }else{
                                  $("#levelSeqE").closest("div").find("span").text("");
                                  $("#hidden_cnt").val(parseInt($('#levelSeqE').val()) + 1);
                              }
          		   }
                   //alert($("#hidden_cnt").val());
            }


            function first_editselect(addObj){
              $(".custom-alert").remove();
						loaderShow();

						$.ajax({
            				type : "POST",
            				url : "<?php echo site_url('admin_rolesHierarchyController/post_data1'); ?>",
            				dataType : 'json',
                            data: JSON.stringify(addObj),
            				cache : false,
            				success : function(data){
            				    loaderHide();
								if(error_handler(data)){
									return;
								}
                                if(data=="cannot_move"){
                                    $('#alert1').modal('show');
								    $("#alert1 .modal-body center span").text("Users Having Executive Module Found in Level Selected. Hence Cannot Move the Role.");

                                }else{
                                        if(data.reporting_users.length >0){
                                                $("#active_status_confirm").modal('show');

                                                        options ="";
                                                        options += '<select class="form-control">';
                                                        options += '<option value="">-Select Reporting Users-</option>';
                                                        for(var i=0;i<data.reporting_users.length; i++)
                                                        {
                                  	                        options += "<option value='"+data.reporting_users[i].user_id+"<@>"+data.reporting_users[i].designation+"'>"+ data.reporting_users[i].user_name +"--"+data.reporting_users[i].role_name+"("+data.reporting_users[i].deptname+")"+"</option>";
                                                        }
                                                        options += '</select>';
                                                        var valign="style= 'vertical-align: middle'" ;
                                                        var replacement_table ="";
                                                        replacement_table +='<table class="table"><thead>';
                                                        replacement_table +='<tr><th width="10%">SL No.</th><th width="30%">User Name</th><th width="20%">Department</th><th width="20%">Designation</th><th width="20%">Reporting To</th>';
                                                        replacement_table +='</thead><tbody>';
                                                        for(var u=0,j=1; u<data.users.length; u++,j++){
                                                            var a=u; /*userid*/
                                                            var uid=data.users[a];
                                                            var b=u+1; /*name*/
                                                            var c=u+2;/*dep*/
                                                            var d=u+3;/*desg*/
                                                            if(data.hasOwnProperty('users_rep')){
                                                                  if(data.users_rep.hasOwnProperty(uid)){
                                                                      options ="";
                                                                      options += '<select class="form-control">';
                                                                      options += '<option value="">-Select Reporting Users-</option>';
                                                                      for(var i=0;i<data.users_rep[uid][0].length; i++)
                                                                      {
                                                	                        options += "<option value='"+data.users_rep[uid][0][i].user_id+"<@>"+data.users_rep[uid][0][i].designation+"'>"+ data.users_rep[uid][0][i].user_name +"--"+data.users_rep[uid][0][i].role_name+"("+data.users_rep[uid][0][i].deptname+")"+"</option>";
                                                                      }
                                                                      options += '</select>';
                                                                  }
                                                            }
                                                            replacement_table +='<tr class="'+data.users[a]+'"><td '+valign+'>'+(j)+'</td><td '+valign+'>'+data.users[b]+'</td><td '+valign+'>'+data.users[c]+'</td><td '+valign+'>'+data.users[d]+'</td><td '+valign+'>'+options+'</td>';
                                                            u=d;
                                                        }
                                                        replacement_table +='</tbody></table>';
                                                        $("#replacement_table").html("").html(replacement_table);


                                        }else{
                                            if($('#chk_level').prop('checked') == false){
                                                 move_role();
                                            }else{
                                              activesubmit();
                                            }

                                        }
                                }
								//loaderHide();
                                //loadpage();
            				}
						});
            }

            function move_role(){
                    var addObj={};
                    var selectedOptArray1 = [];

            		$("#displayEdit tr").each(function(){
                        var value=$(this).attr("id");
                        var val1=value.split("_");
                        var id1 = val1[0];
                        var new_levelcnt = val1[1];
                        addObj.new_levelcnt = new_levelcnt;
            			selectedOptArray1.push(id1);
            		});

            		addObj.rolesid = selectedOptArray1.toString();
                    addObj.levelCount = $('.edit_hidlevel_cnt').text();

                    //console.log(addObj);
                    loaderShow();
                    $.ajax({
                  				type : "POST",
                  				url : "<?php echo site_url('admin_rolesHierarchyController/move_roledata'); ?>",
                  				dataType : 'json',
                                data: JSON.stringify(addObj),
                  				cache : false,
                  				success : function(data){
      								loaderHide();
      								if(error_handler(data)){
      									return;
      								}
                                    if(data=="cannot_move"){
                                        $('#alert1').modal('show');
								        $("#alert1 .modal-body center span").text("Users Having Executive Module Found in Level Selected. Hence Cannot Move the Role.");

                                    }else{
                                        cancel();
                                        loadpage();
                                    }

                  				}
                    });
            }

            function activesubmit(){
                    var addObj ={};
                    var rep_arr =[];
                    var flgChk= 0;
                      $("#replacement_table table tbody tr").each(function(){
                          if($(this).find('select').val() == ""){
                              $(this).find('select').closest('td').find('.error-alert').remove();
                                $(this).find('select').closest('td').append('<span class="error-alert">Select Reporting Replacement</span>')
                                flgChk= 1;
                          }else{
                                $(this).find('select').closest('td').find('.error-alert').remove();
                          }
                          var to_repor= $(this).find('select').val().split("<@>");
                          rep_arr.push(
                                    {
                                      "user_id":$(this).attr('class'),
                                      "reporting_to_id":to_repor[0],
                                      "reporting_to_desg":to_repor[1],
                                    }
                                );
                    });
                    if(flgChk== 1){
                      //alert("U missed some thing")
                       return;
                    }
                    var selectedOptArray1 = [];

                    if($('#chk_level').prop('checked') == false){
                		$("#displayEdit tr").each(function(){
                            var value=$(this).attr("id");
                            var val1=value.split("_");
                            var id1 = val1[0];
                            new_levelcnt11 = val1[1];
                            addObj.new_levelcnt=new_levelcnt11;
                			selectedOptArray1.push(id1);
    						lastrow = $(this).attr("id");
                		});
                        if(selectedOptArray1.length > 0){

                		}else{
                			alert("select atleast 1");
                			return;
                		}
                        addObj.rolesid = selectedOptArray1.toString();
                        addObj.levelCount = $('.edit_hidlevel_cnt').text();
                        addObj.rep_arr=rep_arr;
                        console.log(addObj);
                        loaderShow();
                        $.ajax({
                      				type : "POST",
                      				url : "<?php echo site_url('admin_rolesHierarchyController/update_reportingdata'); ?>",
                      				dataType : 'json',
                                    data: JSON.stringify(addObj),
                      				cache : false,
                      				success : function(data){
          								loaderHide();
          								if(error_handler(data)){
          									return;
          								}
                                          cancel();
                                          loadpage();
                      				}
                        });
                    }else{
                            $("#displayEdit1 tr").each(function(){
                                var value=$(this).attr("id");
                                var val1=value.split("_");
                                var id1 = val1[0];
                                new_levelcnt11 = val1[1];
                                addObj.new_levelcnt=new_levelcnt11;
                    			selectedOptArray1.push(id1);
        						lastrow = $(this).attr("id");
                    		});
                            if(selectedOptArray1.length > 0){

                    		}else{
                    			alert("select atleast 1");
                    			return;
                    		}
                            if(parseInt(lastLevel)>0){
                              if($('#levelSeqE').val() == ""){
                                    $("#levelSeqE").closest("div").find("span").text("Please Select Level");
                              }else{
                                    $("#levelSeqE").closest("div").find("span").text("");
                              }
                                    addObj.levelCount = $('#levelSeqE').val();
                              }else{
                                    addObj.levelCount = 0;
                              }
                            if($('#beforeE').prop('checked') == true){
                                    var status="before";
                                    addObj.status=status;
                            }else if($('#afterE').prop('checked') == true){
                                    var status="after";
                                    addObj.status=status;
                            }
                            addObj.rolesid = selectedOptArray1.toString();
                            addObj.rep_arr=rep_arr;
                            console.log(addObj);
                            $.ajax({
                      				type : "POST",
                      				url : "<?php echo site_url('admin_rolesHierarchyController/update_reportingdata1'); ?>",
                      				dataType : 'json',
                                    data: JSON.stringify(addObj),
                      				cache : false,
                      				success : function(data){
          								loaderHide();
          								if(error_handler(data)){
          									return;
          								}
                                          cancel();
                                          loadpage();
                      				}
                            });

                    }
            }

            function activecancel(){
	            window.location.reload(true);
            }
            function close_win(){
                 cancel();
                 loadpage();
            }

/* --------------------------------------------------------------------------------------------------------------------------------------------------------- */

/* ----------------------------------------------------- save level order function -------------------------------------------------------------------------- */

         function saveOrder() {
                 var rowOrderA=[];
                 var rowOrderObj={};
                 var testarr={};
                 $("#tablebody tr").each(function(){
                      var obj=$.trim($(this).attr("id"));
                      var obj1=JSON.parse(obj);
                      var roleval=obj1.role_value;
                      for(var i=0;i< obj1.levelname.length;i++){
                          testarr[obj1.levelname[i].role_id]=roleval;
                      }
                 });
                 rowOrderObj.orderselected= testarr;
				 loaderShow();
                 $.ajax({
          				type : "POST",
          				url : "<?php echo site_url('admin_rolesHierarchyController/save_order'); ?>",
          				dataType : 'json',
                        data: JSON.stringify(rowOrderObj),
          				cache : false,
          				success : function(data){
							if(error_handler(data)){
								return;
							}
							loaderHide();
							cancel();
							loadpage();
							$("#alert").modal('show');
          				}
                  });
          }

/* ------------------------------------------------------------------------------------------------------------------------------------------------------------ */
/* ------------------------------------------------------------------------------------------------------------------------------------------------------------ */
function showdiv(){
    if($('#chk_level').prop('checked') == true){
         $('.level_div1').removeClass('none');
         $('#rolelistEdit').prop('disabled', true);
         getLevels();

    }else{
        $('.level_div1').addClass('none');
        $('#rolelistEdit').prop('disabled', false);
    }


}


/* ------------------------------------------------------------------------------------------------------------------------------------------------------------ */

          function cancel(){

      	        $('#display11 tr').remove();
      	        $('#displayEdit tr').remove();
      	        $('#displayEdit1 tr').remove();
                $('.modal').modal('hide');
                $('.level_div1').addClass('none');
                $('.modal select').val($('.modal select option:first').val());
                loadpage();
				$(".error-alert").text("");
				$("#after").prop("checked",true);
				$("#afterE").prop("checked",true);
				$("#chk_level").prop("checked",false);
				$("#rolelistEdit").prop("disabled",false);
  		  }

</script>
</head>
    <body  class="hold-transition skin-blue sidebar-mini">
    <input type="hidden" id="hidden_cnt" name="hidden_cnt" />
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
                            <div><img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30" data-toggle="tooltip" data-placement="right" title="Roles Hierarchy List"/>
                            </div>
                        </span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Roles_Hierarchy', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
                    </div>
                    <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
                        <h2>Roles Hierarchy List</h2>
                    </div>
                    <div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
                        <div class="addBtns">
                            <a href="#addmodal" class="addPlus" data-toggle="modal" onclick="compose()">
                                <img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
                            </a>
                       </div>
                       <div style="clear:both"></div>
                    </div>
                    <div style="clear:both"></div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th width="20%" class="table_header">Levels</th>
                            <th width="60%" class="table_header">Roles</th>
                            <th width="20%" class="table_header"></th>
                        </tr>
                    </thead>
                    <tbody id="tablebody" class='ui-sortable'>
                    </tbody>
                </table>
                <input type="button" class="btn static" style="display: none" onclick="saveOrder()" value="Save Order">

            </div>
            <div id="addmodal" class="modal fade" data-backdrop="static">
		        <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="addpopup" class="form">
                            <div class="modal-header">
                                <span class="close"  onclick="cancel()">&times;</span>
                                <h4 class="modal-title">Add Roles For New Level <span class="hidlevel_cnt" style="display: none"></span></h4>
                            </div>
                            <div class="modal-body level_div none" >
                                 <div class="row">
                                    <div class="col-md-6">
                                        <label><b>Before&nbsp;</b> <input type="radio" id="before" value="bfr" name="position" onclick="level_onchngfn()" /></label>
                                    </div>
                                    <div  class="col-md-6">
                                        <label><b>After&nbsp;</b> <input type="radio" id="after" value="aftr" name="position" onclick="level_onchngfn()" checked/></label>
                                    </div>
                                </div>
                                <div class="row">
                                        <select  name="levelSeq" class="form-control" id="levelSeq" onchange="level_onchngfn();" >
                                        </select>
                                        <span class="error-alert"></span>
                                </div>
                            </div>
                            <div class="modal-body" >
                                <div class="row">
                                        <select onchange="selectedOpt()"name="rolelist" class="form-control" id="rolelist" >
                                        </select>
                                        <span class="error-alert"></span>
                                </div>
                                <div class="row">
                                    <table class="table" id="display11"></table>
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

            <div id="editmodal" class="modal fade" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form">
                            <div class="modal-header">
                                <span class="close"  onclick="cancel()">&times;</span>
                                <h4 class="modal-title">Edit Roles For - Level <span class="edit_hidlevel_cnt"></span></h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 alert alert-info">
										<i><i class="fa fa-info-circle" aria-hidden="true"></i><label for="addLocation">Please choose a Role to Move to Level <span class="edit_hidlevel_cnt1"></span> </label> </i>
									</div>
                                    <select onchange="editSelectedOpt()"name="rolelist" class="form-control" id="rolelistEdit" >
                                    </select>
                                    <span class="error-alert"></span>
                                </div>
                                <div class="row">
                                    <table class="table" id="displayEdit"></table>
                                </div>
                            </div>

                            <!-- ----------------------------------------------------------------------------------------------- -->
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 alert alert-info">
										<i><i class="fa fa-info-circle" aria-hidden="true"></i><label for="addLocation">Please choose a Role to Move to New Level Selected </label> </i>
									</div>
                                    <label><b>Create New Level&nbsp;</b> <input type="checkbox" id="chk_level"   onclick="showdiv()" /></label>
                                    <span class="error-alert"></span>
                                </div>
                            </div>

                            <div class="modal-body level_div1 none" >
                                   <div class="modal-body " >
                                         <div class="row">
                                            <div class="col-md-6">
                                                <label><b>Before&nbsp;</b> <input type="radio" id="beforeE" value="bfrE" name="position" onclick="level_onchngfnE()" /></label>
                                            </div>
                                            <div  class="col-md-6">
                                                <label><b>After&nbsp;</b> <input type="radio" id="afterE" value="aftrE" name="position" onclick="level_onchngfnE()" checked/></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                                <select  name="levelSeq" class="form-control" id="levelSeqE" onchange="level_onchngfnE();" >
                                                </select>
                                                <span class="error-alert"></span>
                                        </div>
                                   </div>
                                    <div class="modal-body" >
                                        <div class="row">
                                                <select onchange="editSelectedOpt()"name="rolelistEdit1" class="form-control" id="rolelistEdit1" >
                                                </select>
                                                <span class="error-alert"></span>
                                        </div>
                                        <div class="row">
                                            <table class="table" id="displayEdit1"></table>
                                        </div>
                                    </div>
                            </div>

                            <!-- ----------------------------------------------------------------------------------------------- -->

                            <div class="modal-footer">
                                <!--<input type="button" class="btn" onclick="editSave()" value="Save">-->
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
									<span>Levels changed successfully.</span>
									<br>
									<br>
									<input type="button" class="btn" data-dismiss="modal" value="Ok">
								</center>
							</div>
						</div>
                    </div>
                </div>
            </div>
            <!-- added by swati on 14-9-2017 -->
            <div id="active_status_confirm" class="modal fade" data-backdrop="static">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header modal-title">
							<span class="close" onclick="activecancel()">x</span>
							<h4 class="modal-title">Choose Reporting Replacement</h4>
						</div>
						<div class="modal-body" id="replacement_table">

						</div>
						<div class="modal-footer" >
							<input type="button" class="btn" onclick="activesubmit()" value="Save">
							<input type="button" class="btn" onclick="activecancel()" value="Cancel" >
						</div>
					</div>
				</div>
			</div>
            <div id="alert1" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
						<div class="modal-body">
							<div class="row">
								<center>
									<span></span>
									<br>
									<br>
									<input type="button" class="btn"  onclick="close_win()" value="Ok">
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