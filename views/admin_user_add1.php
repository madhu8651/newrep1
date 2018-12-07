<!DOCTYPE html>
<html lang="en">
	<head>
    <?php $this->load->view('scriptfiles'); ?>
	<style>
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

.dataTable{
	width:100% !important
}
ol#ListDislayAdd li {
    list-style: decimal;
    border-bottom: 1px solid #ccc;
    margin-top: 7px;
}
.add_e_list{
  height: 32px;
    width: 30px;
    text-align: center;
    border: 1px solid;
    line-height: 32px;
    border-radius: 5px;
}

/* -----------------allignment fix for Add email and phn No .... on add and edit popup (14-09-2017)------------------------ */
.userpage .email_opt,
.userpage .mobile_opt{
	width: 12%;
}
.userpage .email_opt select,
.userpage .mobile_opt select{
	padding-left: 1px;
}
.userpage #mobile,
.userpage #mob_edit{
	width: 100% !important;
}
.userpage .email_type,
.userpage .mobile_type {
    width: 139px !important;
}
.userpage .col_fa,
.userpage .mob_add{
	width: 0;
    padding-left: 1px;
}
.userpage .modal button.btn{
	 margin-left: -8px;
}
.userpage .off_loc{
	    margin-left: 56px;
}
.userpage .sell_type_labl{
	    margin-left: 47px;
}
/* -------------------------------- */
	</style>
	<script>
	var team=[],ind=[],ind2=[],pro=[],pro1=[],cur=[],cur1=[],cur2=[],pro2=[];
	var loc1=[],eid=[],employeeid;
	var loc2=[],idarray=[],ofloc1=[],ofloc2=[],modularr=[],procur=[],sellarray=[],sellarray1=[];
	var team1=[],role_val=selrowflg=0,mgrflg=cxoflg=[];
	var p=1,d=1,s=1,w=1,r,h;
	var text_chk = new RegExp(/^[a-zA-Z &.]*$/);
	var emp_chk = new RegExp(/^[A-Za-z0-9#()_-]*$/);
	var num_chk = new RegExp(/^\+*[0-9]*$/);
	var cost_chk = new RegExp(/^[0-9 .]*$/);
	var email_chk = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    //new code added
    var pluarr=[];
    pluarr=['Attendance','Communicator','Expenses','Inventory','Library','Navigator'];

/*----------------------------------------------------------------------------other functions starts---------------------------------------------------*/
$(document).ready(function(){
            /* code for sandbox */
		    var url1= window.location.href;
		    var fileNameIndex1 = url1.lastIndexOf("/") + 1;
		    var filename1 = url1.substr(fileNameIndex1);
            sandbox(filename1);
            load();

			$('#time_zone, #time_zoneE').timezones();

            $('#add_user_dep').on('change', function() {
                    var depid= this.value;
					var obj="";
                    getroles(depid,obj);
            });
            $('#add_user_role').on('change', function() {
					var roleid= this.value;
					var obj="";
					get_designation(roleid,obj);
            });

			$('#add_user_desg').on('change', function() {
						var repto= this.value;
						var obj="";
						getrepto(repto,obj,"");
			});

			$('#add_desg_name').on('change', function(){
						var depid= $('#add_user_dep').val();
						var obj="";
						getteamdata(depid,obj,ofloc2,loc2,ind2,procur,sellarray);
			});
			$('#add_user_team').on('change', function() {
						var teamsid= this.value;
						$("#add_team").val(teamsid);
						getparentnode(teamsid,ofloc2,loc2,ind2,procur,sellarray);
			});
			$("#add_DOB1").datetimepicker({
					ignoreReadonly:true,
					allowInputToggle:true,
					format:'YYYY-MM-DD',
					useCurrent:false
			});
			$("#strt_time1,#strt_time2,#strt_time3,#strt_time4,#strt_time5,#strt_time6,#strt_time7,#end_time1, #end_time2,#end_time3,#end_time4,#end_time5,#end_time6,#end_time7").datetimepicker({
					ignoreReadonly:true,
					allowInputToggle:true,
					format:'HH:mm',
			});

            $("#targetStartDate").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "dd/mm/yy",
                yearRange: "-90:+00"
            });


            $("#Roaster").click(function(){
                var roast = $("#Roaster").val();
                if(roast=="custom"){
                    $(".work").show();
                }else{
                    $(".work").hide();
                }
            });
			 $("#add_user_target").on("change", function(){
                var target = $("#add_user_target").val();
                if(target == "numbers"){
                    $('.targetrow,.targetrow2').show();
                    $('.targetrow1').hide();
                }else if(target == "revenue"){
                    $('.targetrow,.targetrow1').show();
                    $('.targetrow2').hide();
                }else{
                    $('.targetrow,.targetrow1,.targetrow2').hide();
                }
            });
			$("#accounting").on("change", function(){
                if($("#accounting").is(':checked')){
                        $('.showaccounting').show();
                }else{
                        $('.showaccounting').hide();
               }
            });

			get_modules();

});
/*--------------------------------------------------------------onload functions starts---------------------------------------------------*/
var module_purchased=remcnt=0;
var modulecnt=usrcount=0;
function load(){
$.ajax({
		type : "POST",
		url : "<?php echo site_url('admin_userController1/get_users'); ?>",
		dataType : 'json',
		cache : false,
		success : function(data){
            loaderHide();
			main_data = data;
			if(error_handler(data)){
				return;
			}
			var activeRow = "";
			var inActiveRow = "";
			var str1 = "";
			var count = 1;
			for(i=0; i < data.udata.length; i++ ){

				$('#InactiveUsers table').dataTable().fnDestroy();
				var rowdata = JSON.stringify(data.udata[i]);
                eid.push(data.udata[i].employee_id);
				/* ------------------Inactive Users------------------- */
				if(data.udata[i].user_state == 0){
					inActiveRow += "<tr><td>" + (count) + "</td><td>" + data.udata[i].user_name + "</td><td>" + data.udata[i].Department_name + "</td><td>" + data.udata[i].role_name + "</td>";
					if(data.udata[i].Manager==null || data.udata[i].Manager=="Admin"){
						inActiveRow+="<td>" + "" + "</td>";
					}else{

                        inActiveRow+="<td>" + data.udata[i].Manager + "</td>";
					}

					inActiveRow += "<td>" + data.udata[i].teamname + "</td>";
					inActiveRow += "<td><label class='switch'><input  onchange='activeStatus(\""+data.udata[i].user_id+"\", \""+data.udata[i].reporting_to+"\" ,\""+data.udata[i].designation+"\",\""+data.udata[i].department+"\",\""+data.udata[i].manager_module+"\",\""+data.udata[i].sales_module+"\",\""+data.udata[i].cxo_module+"\",\""+data.udata[i].role_value+"\")' id='aa"+data.udata[i].user_id+"' type='checkbox'><div class='slider round'></div></label></td>";
					inActiveRow += "<td></td><td><a href='#' onclick='viewadminInfo(\""+data.udata[i].user_id+"\")'><span class='glyphicon glyphicon-eye-open'></span></a>" + "</td></tr>";
					count++;
				}

			}

			/* -------------------------- */
			var count = 1;
			var actcount = 0;
            module_purchased=data.cldata[0].module_purchased;
            modulecnt=data.cldata[0].modulecnt;
            usrcount=data.ucount[0].activeuser_cnt;
			for(j=0; j < data.udata.length; j++ ){

				$('#tablebody').parent("table").dataTable().fnDestroy();
				var rowdata = JSON.stringify(data.udata[j]);
                eid.push(data.udata[j].employee_id);

				/* -----------------Active Users-------------------- */
				if(data.udata[j].user_state == 1){
                    actcount++;
					activeRow += "<tr><td>" + (count) + "</td><td>" + data.udata[j].user_name + "</td><td>" + data.udata[j].Department_name + "</td><td>" + data.udata[j].role_name + "</td>";
					if(data.udata[j].Manager==null || data.udata[j].Manager=="Admin"){
						activeRow+="<td>" + "" + "</td>";
					}else{
						activeRow+="<td>" + data.udata[j].Manager + "</td>";
					}

					activeRow += "<td>" + data.udata[j].teamname + "</td>";
					activeRow += "<td><label class='switch'><input  onchange='activeStatus(\""+data.udata[j].user_id+"\", \""+data.udata[j].reporting_to+"\" ,\""+data.udata[j].designation+"\",\""+data.udata[j].department+"\",\""+data.udata[j].manager_module+"\",\""+data.udata[j].sales_module+"\",\""+data.udata[j].cxo_module+"\",\""+data.udata[j].role_value+"\")' id='aa"+data.udata[j].user_id+"' checked type='checkbox'><div class='slider round'></div></label></td>";
					activeRow += "<td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td><td><a href='#' onclick='viewadminInfo(\""+data.udata[j].user_id+"\")'><span class='glyphicon glyphicon-eye-open'></span></a>" + "</td></tr>";

					count++;
				}

			}
            /* code for sandbox */
           /* if(parseInt(modulecnt)== parseInt(module_purchased)){ this is required for module count maintaining code*/
            if(parseInt(usrcount)== parseInt(module_purchased)){
                    $('#useradbtn').hide();
            }else{
                    $('#useradbtn').show();
            }
			$('#tablebody').html("").append(activeRow);
            $('#tablebody').parent("table").DataTable({
														"aoColumnDefs": [{ "bSortable": false, "aTargets": [6,7,8] }]
													});
			$('#InactiveUsers table').DataTable({
														"aoColumnDefs": [{ "bSortable": false, "aTargets": [6,7,8] }]
													});
            /* ------------------------- */
				if(activeRow == ""){
						activeRow = "<tr><td></td><td></td><td>No data availavle</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
				}
				/* --------------   */
				if(inActiveRow == ""){
						/* inActiveRow = "<tr><td></td><td></td><td>No data availavle</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>"; */
                        $('#InactiveUsers tbody tr td').attr("colspan", "9")
				}else{
                    $('#InactiveUsers tbody').html("").append(inActiveRow);
			        $('#InactiveUsers table').css("width:100%");
				}
			/* -------------------------- */

		}
	});

}
/*-------------------------active /deactive functions ---------------------------------------------------*/
var rlvalflg=asgtflag=0;
function activeStatus(id,reportingTo,designation,department,mgrmod,salemod,cxomod,roleval){
    $('.activesubmit').removeClass('none');
    $('.activesubmit1').addClass('none');

    /*var u_modcount=0;
    if(mgrmod!='0'){
        u_modcount++;
    }else{$("#vmgrdiv").hide();}
    if(salemod !='0'){
        u_modcount++;
    }else{$("#vexediv").hide();}
    if(cxomod!='0'){
        u_modcount++;
    }else{$("#vcxodiv").hide();}
    remcnt= parseInt(module_purchased)-parseInt(modulecnt)-parseInt(u_modcount);
    if(parseInt(remcnt) == -1){
       get_modules();
    } this is required for module count maintaining code*/
    if($("#aa"+id).prop('checked')==true){
             //alert(module_purchased+"--------------"+modulecnt);

             /*if(parseInt(modulecnt)==parseInt(module_purchased)){ this is required for module count maintaining code */
             if(parseInt(usrcount)==parseInt(module_purchased)){

                                $("body").append('<div class="mask custom-alert"></div><div class="alert alert-danger row custom-alert"> <div class="col-md-9">You have exhausted the User License.</div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
            							$(".Ok").click(function(){
            								$(".custom-alert").remove();
                                            activecancel();
            							});
            							$(".notOk").click(function(){
            								$(".custom-alert").remove()
            								activecancel();
            							});

             }else{
                    $("body").append('<div class="mask custom-alert"></div><div class="alert row custom-alert" style="background:#3c8dbc"> <div class="col-md-9 style="color:#fff""> <b>Do you really want to activate the user?</b> </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
					$(".Ok").click(function(){
						$(".custom-alert").remove();

                        $('#add_user').val(id);
                        var addObj={};
                    	addObj.userID=id;
                    	addObj.department=department;
                    	addObj.roleid=designation;
                    	addObj.remcnt=remcnt;
                        $.ajax({
                    		type : "POST",
                    		url : "<?php echo site_url('admin_userController1/check_active'); ?>",
                    		dataType : 'json',
                    		data : JSON.stringify(addObj),
                    		cache : false,
                    		success : function(data){
                    				loaderHide();
									if(error_handler(data)){
										return;
									}
                                    if(data.length ==0){
                                            $("body").append('<div class="mask custom-alert"></div><div class="alert alert-danger row custom-alert"> <div class="col-md-9">Please Acitvate Level 1 Users/User First</div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
            							    $(".Ok").click(function(){
            								    $(".custom-alert").remove();
                                                activecancel();
            							    });
            							        $(".notOk").click(function(){
            								    $(".custom-alert").remove()
            								    activecancel();
            							    });
                                    }
                                    if(data!=0){

                                                    $("#active_status_confirm").modal('show');

                                                    var select = $("#assign_to"), options = "<option value=''>Select</option>";
                                                    select.empty();
                                                    for(var i=0;i<data.length; i++)
                                                    {
                              	                        options += "<option value='"+data[i].user_id+"<@>"+data[i].designation+"'>"+ data[i].user_name +"--"+data[i].role_name+"("+data[i].deptname+")"+"</option>";
                                                    }
                                                    select.append(options);
                                                    /*asgtflag=1; this is required for module count maintaining code*/


                                    }else{

                                        /*if(parseInt(remcnt)>=0){
                                            $("#active_status_confirm").modal('hide');
                                            activecancel();
                                        }else{
                                            $("#active_status_confirm").modal('show');
                                            var select = $("#assign_to"), options = "<option value=''>Select</option>";
                                            select.append(options);
                                            $("#replacediv").hide();
                                            if(roleval==1){ // if top level no executive comes
                                                $("#vexediv").hide();
                                                rlvalflg=1;
                                            }
                                        } this is required for module count maintaining code*/
                                        $("#active_status_confirm").modal('hide');
                                        activecancel();
                                    }
                    		}
                        });

					});
					$(".notOk").click(function(){
                            activecancel();
					});

             }
    }else{

                $("body").append('<div class="mask custom-alert"></div><div class="alert row custom-alert" style="background:#3c8dbc"> <div class="col-md-9 style="color:#fff""><b> Do you really want to deactivate the user?</b> </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
					$(".Ok").click(function(){
					        $(".custom-alert").remove();
                            $('#add_user').val(id);
                            var addObj={};
                        	addObj.userID=id;
                        	addObj.department=department;
                        	addObj.roleid=designation;
                            $.ajax({
                        		type : "POST",
                        		url : "<?php echo site_url('admin_userController1/check_deactive'); ?>",
                        		dataType : 'json',
                        		data : JSON.stringify(addObj),
                        		cache : false,
                        		success : function(data){
                        				loaderHide();
										if(error_handler(data)){
											return;
										}
                                        if(data!=0){
                                                if(data.reporting_users.length >0){
                                                                  if(data.reporting_users[0]=="nouser"){
                                                                      alert("No User Found For Reporting.. ");
                                                                      activecancel();
                                                                      /* return; */
                                                                  }else{
                                                                      display_reporting(data);
                                                                  }
                                                }else{
                                                    activecancel();
                                                }

                                        }else{
                                            activecancel();
                                        }
                        		}
                            });
					});
					$(".notOk").click(function(){
                          activecancel();
					});

    }

}

function activesubmit(){
 if($("#aa"+$('#add_user').val()).prop('checked')==true){

                    /*var addObj={};
                    if(parseInt(remcnt)>0){
                        if($("#assign_to").val()==""){
                                $(".error_mod3").show();
              			        $(".error_mod3").closest("div").find("span").text("Please select Reporting To User from the list.");
                                return;
                        }else{
                                $(".error_mod3").hide();
                                var val=$("#assign_to").val();
                                var val1=val.split("<@>");
                	            addObj.olduserID=$('#add_user').val();
                	            addObj.userID=val1[0];
                	            addObj.reportingdesg=val1[1];
                	            addObj.remcnt=remcnt;
                        }
                    }else{
                        if(rlvalflg==1){ // if top level no executive comes
                            if(($("#vManager").prop("checked")== true )||($("#vCXO").prop("checked")== true)){
                                $(".error_mod2").hide();
              		        }else{
              			        $(".error_mod2").show();
              			        $(".error_mod2").closest("div").find("span").text("Please select the designation.");
                                return;
              		        }
                        }else{
                            if(($("#vSales").prop("checked")== true )||($("#vManager").prop("checked")== true )||($("#vCXO").prop("checked")== true)){
                                $(".error_mod2").hide();
              		        }else{
              			        $(".error_mod2").show();
              			        $(".error_mod2").closest("div").find("span").text("Please select  the designation.");
                                return;
              		        }
                        }
              		    if($("#vSales").is(':checked')){
              			    addObj.add_sales = $("#vSales").prop('class');
                        }else{
              			    addObj.add_sales="0";
              		    }
              		    if($("#vManager").is(':checked')){
              			    addObj.add_manager = $("#vManager").prop('class');
              		    }else{
              			    addObj.add_manager="0";
              		    }
              		    if($("#vCXO").is(':checked')){
              			    addObj.add_CXO = $("#vCXO").prop('class');
              		    }else{
              			    addObj.add_CXO="0";
              		    }
                        if(asgtflag==1){
                            if($("#assign_to").val()==""){
                                $(".error_mod3").show();
              			        $(".error_mod3").closest("div").find("span").text("Please select Reporting To User from the list.");
                                return;
                            }else{
                                var val=$("#assign_to").val();
                                var val1=val.split("<@>");
                	            addObj.olduserID=$('#add_user').val();
                	            addObj.userID=val1[0];
                	            addObj.reportingdesg=val1[1];
                	            addObj.remcnt=remcnt;
                            }
                        }else{
                            addObj.olduserID=$('#add_user').val();
                	        addObj.userID="";
                	        addObj.reportingdesg="";
                	        addObj.remcnt=remcnt;
                        }

                    }*/
                    var addObj={};
                    var val=$("#assign_to").val();
                    var val1=val.split("<@>");
                	addObj.olduserID=$('#add_user').val();
                	addObj.userID=val1[0];
                	addObj.reportingdesg=val1[1];
                    addObj.remcnt=remcnt;
                	 $.ajax({
                		type : "POST",
                		url : "<?php echo site_url('admin_userController1/post_replacement_data/active'); ?>",
                		dataType : 'json',
                		data : JSON.stringify(addObj),
                		cache : false,
                		success : function(data){
            				if(error_handler(data)){
            					return;
            				}
                				loaderHide();
                				$("#active_status_confirm").modal('hide');
                                activecancel();
                		}
                	});

 }else{

                    var addObj ={};
                    var rep_arr =[];
                    var flgChk= 0;
                      $("#replacement_table table tbody tr").each(function(){
                          if($(this).find('select').val() == ""){
                              $(this).find('select').closest('td').find('.error-alert').remove();
                                $(this).find('select').closest('td').append('<span class="error-alert">Select Reporting User</span>')
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
                       return;
                    }
                    addObj.rep_arr=rep_arr;
                    addObj.olduserID=$('#add_user').val();
                    addObj.type="deactive";
                    console.log(addObj);
                    $.ajax({
                  				type : "POST",
                  				url : "<?php echo site_url('admin_userController1/update_reportingdata'); ?>",
                  				dataType : 'json',
                                data: JSON.stringify(addObj),
                  				cache : false,
                  				success : function(data){
      								/* loaderHide(); */
      								if(error_handler(data)){
      									return;
      								}
                                    loaderHide();
    				                $("#active_status_confirm1").modal('hide');
                                    activecancel();

                  				}
                    });

 }

}
function activecancel(){
	 window.location.reload(true);
}
var savedUserRecord;
var save_user_email=[];
function savedGrpMail_fun(){
       $("#ListDislayAdd").html('');
        $("#grp_emal_disp_edit").html('')

            for(ii=0; ii<save_user_email.length;ii++){
               $("#ListDislayAdd").append(
                          '<li>'+
                          '<span class="'+save_user_email[ii].user+'">'+save_user_email[ii].name +'</span> '+
                          '(<b>'+save_user_email[ii].permisi+'</b>)'+
                          '<a title="Delete" class="pull-right glyphicon glyphicon-remove-circle"></a>'+
                          '</li>'
                      );


            }
            $("#ListDislayAdd li a").each(function(){
				$(this).click(function(){
					$(this).closest('li').remove();
				})
			});

}
function selrow(obj){
		savedUserRecord=obj;
        $('.activesubmit1').removeClass('none');
        $('.activesubmit').addClass('none');
        $("#sec_from").val("edit");
        $("#rolval").val(obj.role_value);

		$('#edit_user_desg').on('change', function() {
					var repto= this.value;
                    var obj1="";
                    var uid=obj.user_id;
					getrepto(repto,obj1,uid);
		});
		$('#edit_user_dep').on('change', function() {
				$("#edit_rep_into,#edit_user_desg").val("");
				var depid= this.value;
                var obj1="";
				getroles(depid,obj1);
		});
		$('#edit_user_role').on('change', function() {
				var roleid= this.value;
                var obj1="a";
                selrowflg=1;
				get_designation(roleid,obj1);
		});
		$('#edit_desg_name').on('change', function() {
					var depid= $('#edit_user_dep').val();
                    var obj1="";
					getteamdata(depid,obj1,ofloc2,loc2,ind2,procur,sellarray);
		});
		/* ----------------------------- */
			$('#edit_user_team').on('change', function() {
					var teamsid= this.value;
					$("#edit_team").val(teamsid);
					getparentnode(teamsid,ofloc2,loc2,ind2,procur,sellarray);
			});
			$("#edit_DOB1").datetimepicker({
					ignoreReadonly:true,
					allowInputToggle:true,
					format:'YYYY-MM-DD',
					useCurrent:false
			});
			$("#e_strt_time1, #e_strt_time2,#e_strt_time3,#e_strt_time4,#e_strt_time5,#e_strt_time6,#e_strt_time7,#e_end_time1, #e_end_time2,#e_end_time3,#e_end_time4,#e_end_time5,#e_end_time6,#e_end_time7").datetimepicker({
					ignoreReadonly:true,
					allowInputToggle:true,
					format:'HH:mm',
			});

            $("#e_add_charge").on("change", function(){
                if($("#e_add_charge").is(':checked')){
                    $("#e_additional").show(500);
                }else{
                    $("#e_additional").hide(500);
                }
            });
			$("#targetStartDate1").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "dd/mm/yy",
                yearRange: "-90:+00"
            });
            $("#e_navigationmod").click(function(){
                if($("#e_navigationmod").is(":checked")){
                    $("#edit_user_nav").prop("disabled",false);
                }else{
                    $("#edit_user_nav").prop("disabled",true);
                }
            });
            $("#e_attendancemod").click(function(){
                if($("#e_attendancemod").is(":checked")){
                    $("#e_Roaster").prop("disabled",false);
                }else{
                    $("#e_Roaster").prop("disabled",true);
                }
            });
            $("#e_expensesmod1").click(function(){
                if($("#e_expensesmod1").is(":checked")){
                    $("#edit_user_exp").prop("disabled",false);
                }else{
                    $("#edit_user_exp").prop("disabled",true);
                }
            });

			$("#edit_user_target").on("change", function(){
				var target = $("#edit_user_target").val();
				if(target == "numbers"){
					$('.e_targetrow,.e_targetrow2').show();
					$('.e_targetrow1').hide();
				}else if(target == "revenue"){
					$('.e_targetrow,.e_targetrow1').show();
					$('.e_targetrow2').hide();
				}else{
					$('.e_targetrow,.e_targetrow1,.e_targetrow2').hide();
				}
			});

           $("#e_accounting").on("change", function(){
                if($("#e_accounting").is(':checked')){
                    $('.e_showaccounting').show();
                }else{
                    $('.e_showaccounting').hide();
                    $("#e_resourceCurrency,#e_resourceCost,#e_callCurrency,#e_callCost,#e_smsCurrency,#e_smsCost").val("");
                    $(".error-alert").text("");
                }
            });

		/* $(".e_section2").removeClass('none'); */

		$('.weeks').change(function(){
			if(this.checked){
				var cid = this.id;
				var id = cid.substr(6);
				$('#e_strt_time'+id).removeAttr("disabled");
				$('#e_end_time'+id).removeAttr("disabled");
			}
			if(!this.checked){
				var cid = this.id;
				var id = cid.substr(6);
				$('#e_strt_time'+id).attr("disabled","disabled");
				$('#e_end_time'+id).attr("disabled","disabled");
			}
		});
		var addObj={};
		$("#edit_name").val(obj.user_name);
		$("#edit_name1").val(obj.last_name);
		$("#edit_rep_into").val(obj.Manager);
		$("#e_res_address").val(obj.address1);

		$("#edit_gender option[value='"+obj.user_gender+"']").attr("selected",true);
		$("#e_res_address").val(obj.address1);
		if(obj.dob=='0000-00-00 00:00:00'){
			$("#edit_DOB").val('');
		}else{
			$("#edit_DOB").val(obj.dob);
		}
		$("#edit_user_eId").val(obj.employee_id);
		employeeid = obj.employee_id;
		$("#add_user").val(obj.user_id);
		addObj.loc_team=obj.location;
		addObj.user_id=obj.user_id;
		addObj.reporting_toid=obj.reporting_to;

		$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_userController1/get_remaininguser_data'); ?>",
				dataType : 'json',
				data:JSON.stringify(addObj),
				cache : false,
				success : function(data){
					if(error_handler(data)){
						return;
					}

                    var htm='';
                      save_user_email=[];
                    if(data.hasOwnProperty('groupmail')){
                        for(ii=0; ii<data.groupmail.length;ii++){
                            save_user_email.push({ 'user': data.groupmail[ii].map_id,
                                                'permisi': data.groupmail[ii].map_value,
                                                'name': data.groupmail[ii].email_id
                                              });

                            if(ii==0){
                           htm +='<span>'+data.groupmail[ii].email_id+'</span>'
                        }else{
                          htm +='<span>,'+data.groupmail[ii].email_id+'</span>'
                        }
                        }

                    }
                     //$("#grp_emal_disp_edit").append( htm ); //communicator plugin yet to develop

					var deptid=obj.department;
					if(data.prodetails.length>0){
						filldepartment(obj.department,
						data.prodetails[0].holiday_calender,
						obj.user_product,
						data.prodetails[0].resource_currency,
						data.prodetails[0].outgoingcall_currency,
						data.prodetails[0].outgoingsms_currency,"");
					}else{
					  filldepartment(obj.department,"",obj.user_product,"","","","");
					}

					var b=$('#edit_user_team').val();
					if($('#edit_user_team').val()==""){
						getteamdata(deptid,obj.team_id,data.officeloc,data.businessloc,data.industry, data.procur,data.selltype);
					}

					getroles(deptid,obj.designation);
					if($('#edit_user_role').val()!=""){
						/* if(obj.reporting_desg==""){
							get_designation(obj.designation,"a");
						}else{

						} */
						get_designation(obj.designation,obj.reporting_desg);
					}
					if($('#edit_user_desg').val()!=""){
						getrepto(obj.reporting_desg,obj.reporting_to,obj.user_id)

					}

						/* ---------------------call recording-------------------- */
					if(data.prodetails.length>0){
						if(data.prodetails[0].call_recording != 0){
							$("#edit_call_rec").prop("checked",true);
						}else{
							$("#edit_call_rec").prop("checked",false);
						}
					}else{
							$("#edit_call_rec").prop("checked",false);
						}
						/* ---------------------Spend calculation-------------------- */
					if(data.prodetails.length>0){
						if(data.prodetails[0].accounting != 0){
							$("#e_accounting").prop("checked",true);
							$(".e_showaccounting").show();

							$("#e_callCost").val(data.prodetails[0].outgoingcall_cost);
							$("#e_smsCost").val(data.prodetails[0].outgoingsms_cost);
							$("#e_resourceCost").val(data.prodetails[0].resource_cost);
						}else{
							$("#e_accounting").prop("checked",false);
							$(".e_showaccounting").hide();
						}
					}else{
						$("#e_accounting").prop("checked",false);
						$(".e_showaccounting").hide();
					}
                    //$('#time_zoneE').val(data.timezone[0].timezone);
					/* -------------------modules and plugins------------------------- */
					modules=JSON.parse(data.modules[0].module_id);
					rep_modules=JSON.parse(data.rep_modules[0].module_id);
                    var aflg=0;
					if(modules.Manager != 0){
						$("#eManager").prop('checked', true);
                        mgrflg.push("Mgr");
					}else{
						$("#eManager").prop('checked', false);

					}
					if(modules.cxo != 0){
						$("#eCXO").prop('checked', true);
                        mgrflg.push("cxo");
					}else{
						$("#eCXO").prop('checked', false);
					}
                    if(modules.sales != 0){
						$("#eSales").prop('checked', true);
					}else{
						$("#eSales").prop('checked', false);
					}
                    if(modules.custo_assign!=0){
						$("#e_custo_assign").prop('checked', true);
					}else{
						$("#e_custo_assign").prop('checked', false);
					}

                    if(obj.role_value!=1){
                        if(rep_modules.cxo == 0){
    		                $("#eCXO").closest(".col-md-4").hide();

    	                }else{
    		                $("#eCXO").closest(".col-md-4").show();

    	                }
    	                if(rep_modules.Manager == 0 && rep_modules.cxo == 0){
    		                $("#eManager").closest(".col-md-4").hide();
    	                }else{
    		                $("#eManager").closest(".col-md-4").show();
    	                }

                    }
			/* -------------------modules and plugins------------------------- */
					plugins=JSON.parse(data.plugin[0].plugin_id);
					if(plugins.Attendence== $("#eAttendance").val()){
						$("#eAttendance").prop('checked', true);
						$("#eRoaster").prop('disabled', false);
					}else{
						$("#eAttendance").prop('checked', false);
						$("#eRoaster").prop('disabled', true);
					}if(plugins.Communicator== $("#eCommunicator").val()){
                        $('#grp_emal_btn_e').hide();// show when communicator plugin is enable
						$("#eCommunicator").prop('checked', true);
						$("#eCommunicator").prop('disabled', false);
					}else{
                         $('#grp_emal_btn_e').hide();
						$("#eCommunicator").prop('checked', false);
					}if(plugins.Expense== $("#eExpenses").val()){

						$("#eExpenses").prop('checked', true);
						$("#edit_user_exp").prop('disabled', false);
					}else{

						$("#eExpenses").prop('checked', false);
						$("#edit_user_exp").prop('disabled', true);
					}if(plugins.Inventory== $("#eInventory").val()){
						$("#eInventory").prop('checked', true);
					}else{
						$("#eInventory").prop('checked', false);
					}if(plugins.Library== $("#eLibrary").val()){
						$("#eLibrary").prop('checked', true);
					}else{
						$("#eLibrary").prop('checked', false);
					}if(plugins.Navigator== $("#eNavigator").val()){
						$("#eNavigator").prop('checked', true);
						$("#edit_user_nav").prop('disabled', false);
					}else{
						$("#eNavigator").prop('checked', false);
						$("#edit_user_nav").prop('disabled', true);
					}
					/* --------------------------Working day details-------------------------- */


					for(var i=1;i<=7;i++){

						for(j= 0; j<data.workdetails.length; j++){
								var checkvl=$('#e_week'+i).val();
								var datavl=data.workdetails[j].day_of_week;
							if($('#e_week'+i).val() == data.workdetails[j].day_of_week){

								$('#e_week'+i).prop('checked', true);
								$('#e_strt_time'+i).prop('disabled', false).val(data.workdetails[j].start_time);
								$('#e_end_time'+i).prop('disabled', false).val(data.workdetails[j].end_time);
							}
						}
					}
				}
		});


		$("#mobile1_edit ul").empty();
		$("#home_edit ul").empty();
		$("#work_edit ul").empty();
		$("#main_edit ul").empty();
		$("#work_email_edit ul").empty();
		$("#email_personal_edit ul").empty();

		var phone = JSON.parse(obj.phone_num);
		var email = JSON.parse(obj.emailId);

		var mob = $("#mob_edit").val();
		if(email!=null){
			for(i=0;i<email.work.length;i++){
				$(".email_cat_edit").show();
					if(email.work!=""){
						p=p+1;
						if(obj.user_primary_email.trim() == email.work[i].trim()){
							$("#work_email_edit ul").append('<li title= "Primary Email ID" style="margin-left:40px"><i class="fa fa-key" aria-hidden="true"></i><i class="fa fa-envelope" aria-hidden="true"></i> : <span> '+email.work[i]+' </span>  <a href="#"><i class="fa fa-trash" ></i></a></li>');
						}else{
							$("#work_email_edit ul").append('<li style="margin-left:40px"><i class="fa fa-envelope" aria-hidden="true"></i> : <span> '+email.work[i]+' </span>  <a href="#"><i class="fa fa-trash" ></i></a></li>');
						}
					}
					$(".email_cat_edit li .fa.fa-trash").each(function(){
						$(this).click(function(){
							$(this).closest("li").remove();
						});
					});
			}
			for(i=0;i<email.personal.length;i++){
				$(".email_cat_edit").show();
					if(email.personal!=""){
						p=p+1;
						if(obj.user_primary_email.trim() == email.personal[i].trim()){
							$("#email_personal_edit ul").append('<li title= "Primary Email ID" style="margin-left:40px"><i class="fa fa-key" aria-hidden="true"></i><i class="fa fa-user" aria-hidden="true"></i> : <span> '+email.personal[i]+' </span>  <a href="#"><i class="fa fa-trash" ></i></a></li>');
						}else{
							$("#email_personal_edit ul").append('<li style="margin-left:40px"><i class="fa fa-user" aria-hidden="true"></i> : <span> '+email.personal[i]+' </span>  <a href="#"><i class="fa fa-trash" ></i></a></li>');
						}

					}
					$(".email_cat_edit li .fa.fa-trash").each(function(){
						$(this).click(function(){
							$(this).closest("li").remove();
						});
					});
			}
		}
		for(i=0;i<phone.mobile.length;i++){
			$(".contact_cat1").show();
				if(phone.mobile){
					if(obj.user_primary_mobile.trim() == phone.mobile[i].trim()){
						$("#mobile1_edit ul").append('<li title= "Primary contact number" style="margin-left:40px"><i class="fa fa-key" aria-hidden="true"></i><i class="fa fa-mobile" aria-hidden="true"></i> : <span> '+phone.mobile[i]+' </span>  <a href="#"><i class="fa fa-trash" ></i></a></li>');
					}else{
						$("#mobile1_edit ul").append('<li style="margin-left:40px"><i class="fa fa-mobile" aria-hidden="true"></i> : <span> '+phone.mobile[i]+' </span>  <a href="#"><i class="fa fa-trash" ></i></a></li>');
					}

				}
				$(".contact_cat1 li .fa.fa-trash").each(function(){
					$(this).click(function(){
						$(this).closest("li").remove();
					});
				});
		}
		for(i=0;i<phone.home.length;i++){
			$(".contact_cat1").show();
				if(phone.home){
					if(obj.user_primary_mobile.trim() == phone.home[i].trim()){
						$("#home_edit ul").append('<li title= "Primary contact number" style="margin-left:40px"><i class="fa fa-key" aria-hidden="true"></i><i class="fa fa-home" aria-hidden="true"></i> : <span> '+phone.home[i]+' </span>  <a href="#"><i class="fa fa-trash" ></i></a></li>');
					}else{
						$("#home_edit ul").append('<li style="margin-left:40px"><i class="fa fa-home" aria-hidden="true"></i> : <span> '+phone.home[i]+' </span>  <a href="#"><i class="fa fa-trash" ></i></a></li>');
					}

				}
				$(".contact_cat1 li .fa.fa-trash").each(function(){
					$(this).click(function(){
						$(this).closest("li").remove();
					});
				});
		}
		for(i=0;i<phone.work.length;i++){
				$(".contact_cat1").show();
				if(phone.work){
					if(obj.user_primary_mobile.trim() == phone.work[i].trim()){
						$("#work_edit ul").append('<li title= "Primary contact number" style="margin-left:40px"><i class="fa fa-key" aria-hidden="true"></i><i class="fa fa-building" aria-hidden="true"></i> : <span> '+phone.work[i]+' </span>  <a href="#"><i class="fa fa-trash" ></i></a></li>');
					}else{
						$("#work_edit ul").append('<li style="margin-left:40px"><i class="fa fa-building" aria-hidden="true"></i> : <span> '+phone.work[i]+' </span>  <a href="#"><i class="fa fa-trash" ></i></a></li>');
					}

				}
				$(".contact_cat1 li .fa.fa-trash").each(function(){
					$(this).click(function(){
						$(this).closest("li").remove();
					});
				});
		}
		for(i=0;i<phone.main.length;i++){
				$(".contact_cat1").show();
				if(phone.main){
					if(obj.user_primary_mobile.trim() == phone.main[i].trim()){
						$("#main_edit ul").append('<li title= "Primary contact number" style="margin-left:40px"><i class="fa fa-key" aria-hidden="true"></i><i class="fa fa-phone-square" aria-hidden="true"></i> : <span> '+phone.main[i]+' </span>  <a href="#"><i class="fa fa-trash" ></i></a></li>');
					}else{
						$("#main_edit ul").append('<li style="margin-left:40px"><i class="fa fa-phone-square" aria-hidden="true"></i> : <span> '+phone.main[i]+' </span>  <a href="#"><i class="fa fa-trash" ></i></a></li>');
					}

				}
				$(".contact_cat1 li .fa.fa-trash").each(function(){
					$(this).click(function(){
						$(this).closest("li").remove();
					});
				});
		}

}

/* -------------------------------------------------------------------------------------------- */

function compose(){
        $.ajax({
        		type : "POST",
        		url : "<?php echo site_url('admin_userController1/check_emailsetting')?>",
        		dataType : 'json',
                cache : false,
          		success : function(data){
          			if(error_handler(data)){
          				return;
          			}
                    if(data.length > 0){
                      $("#addmodal").modal('show');
                        $("#sec_from").val("add");
		                $('.weeks').change(function(){
				            if(this.checked){
					            var cid = this.id;
					            var id = cid.substr(4);
					            $('#strt_time'+id).removeAttr("disabled");
					            $('#end_time'+id).removeAttr("disabled");
				            }
				            if(!this.checked){
					            var cid = this.id;
					            var id = cid.substr(4);
					            $('#strt_time'+id).attr("disabled","disabled");
					            $('#end_time'+id).attr("disabled","disabled");
				            }
		                });
                        var deptid=calid=currid=salesperid=grpmail="";
                        filldepartment(deptid,calid,salesperid,resourceCurrency,callCurrency,smsCurrency,grpmail);
                    }else{
                       alert("Please Set up E-Mail setting before creating User. ");
                       return;
                    }
          		}
	    });


}

/* -------------------- get modules and plugin------------------------------------- */
var keepmodcnt=0;
function get_modules(){
		$.ajax({
		type : "POST",
		url : "<?php echo site_url('admin_userController1/get_modules'); ?>",
		dataType : 'json',
		cache : false,
		success : function(data){
			if(error_handler(data)){
					return;
				}
				 /* ---------------------------------------- modules ------------------------------------------------- */
                            keepmodcnt=0;
                            var row='';
				            var row1='';
				            var row2='';
                            var modstr="";
                            var divstr="";
				            for(var i=0;i<data.modules.length; i++){
								/* -----------Module append--------------- */
								if(data.modules[i].module_name == "CXO"){
									addMod.cxo = parseInt(data.modules[i].module_used);
                                    modstr="CXO";
                                    divstr="vcxodiv";
								}
								if(data.modules[i].module_name == "Manager"){
									addMod.manager = parseInt(data.modules[i].module_used);
                                    modstr="Manager";
                                    divstr="vmgrdiv";
								}
								if(data.modules[i].module_name == "Sales"){
									addMod.sales = parseInt(data.modules[i].module_used);
                                    modstr="Executive";
                                    divstr="vexediv";
								}
                                keepmodcnt=parseInt(keepmodcnt)+parseInt(data.modules[i].module_used);
                                var str="";
								/* -----------
									CXO module condition for lite was there before
									CXO module is not ready for standard & premium thats why condition added (25-06-2018)
								--------------- */
                                if((versiontype=='lite' || versiontype=='standard'|| versiontype=='premium') && data.modules[i].module_name=='CXO' ){
                                    str='style="display: none;"';
                                }else{
                                    str='style="display: block;"';
                                }

								/* -----------Module append--------------- */
								/* console.log(addMod) */
					            if(i==2){

						                    row += "<div "+str+" class='col-md-4'><input onchange='getcount(\""+data.modules[i].module_name+"\" , \""+data.modules[i].module_used+"\", \"add\", \""+data.modules[i].module_purchased+"\")' type='checkbox'  name='Module' value=\""+data.modules[i].module_used+"\" id=\""+data.modules[i].module_name+"\" class=\""+data.modules[i].module_id+"\"/> <label class=\""+data.modules[i].module_name+"\" for=\""+modstr+"\"><b>"+modstr+"</b></label></div>";

                                            row1 += "<div "+str+" class='col-md-4'><input onchange='getcount(\"e"+data.modules[i].module_name+"\" , \""+data.modules[i].module_used+"\", \"edit\", \""+data.modules[i].module_purchased+"\")' type='checkbox'  name='eModule' value=\""+data.modules[i].module_used+"\" id=\"e"+data.modules[i].module_name+"\" class=\""+data.modules[i].module_id+"\"/> <label class=\"e"+data.modules[i].module_name+"\" for=\"e"+modstr+"\"><b>"+modstr+"</b></label></div>";

                                            row2 += "<div id="+divstr+" "+str+" class='col-md-4'><input onchange='getcount(\"v"+data.modules[i].module_name+"\" , \""+data.modules[i].module_used+"\", \"v\", \""+data.modules[i].module_purchased+"\")' type='checkbox'  name='vModule' value=\""+data.modules[i].module_used+"\" id=\"v"+data.modules[i].module_name+"\" class=\""+data.modules[i].module_id+"\"/> <label class=\"v"+data.modules[i].module_name+"\" for=\"v"+modstr+"\"><b>"+modstr+"</b></label></div>";

					            }else{
                                            row += "<div "+str+" class='col-md-4'><input onchange='getcount(\""+data.modules[i].module_name+"\" , \""+data.modules[i].module_used+"\", \"add\", \""+data.modules[i].module_purchased+"\")' type='checkbox'  name='Module' value=\""+data.modules[i].module_used+"\" id=\""+data.modules[i].module_name+"\" class=\""+data.modules[i].module_id+"\"/> <label class=\""+data.modules[i].module_name+"\" for=\""+modstr+"\"><b>"+modstr+"</b></label></div>";

                                            row1 += "<div "+str+" class='col-md-4'><input onchange='getcount(\"e"+data.modules[i].module_name+"\" , \""+data.modules[i].module_used+"\", \"edit\", \""+data.modules[i].module_purchased+"\")' type='checkbox'  name='eModule' value=\""+data.modules[i].module_used+"\" id=\"e"+data.modules[i].module_name+"\" class=\""+data.modules[i].module_id+"\"/> <label class=\"e"+data.modules[i].module_name+"\" for=\"e"+modstr+"\"><b>"+modstr+"</b></label></div>";

                                            row2 += "<div id="+divstr+" "+str+" class='col-md-4'><input onchange='getcount(\"v"+data.modules[i].module_name+"\" , \""+data.modules[i].module_used+"\", \"v\", \""+data.modules[i].module_purchased+"\")' type='checkbox'  name='vModule' value=\""+data.modules[i].module_used+"\" id=\"v"+data.modules[i].module_name+"\" class=\""+data.modules[i].module_id+"\"/> <label class=\"v"+data.modules[i].module_name+"\" for=\"v"+modstr+"\"><b>"+modstr+"</b></label></div>";				            }
                                }

				            $("#mang").html("").append(row);
				            $("#rep_mang").html("").append(row2);
				            $("#mang1").html("").append(row1);
				            $("#view_mang1").html("").append(row2);

                            /* --------------------------------------------------------------------------------------------------------- */
							/* -------------------------------------- puglins --------------------------------------------------------------- */
                            var select=$("#plug"),row="";
                            var select1=$("#plug1"),row1="";
                            var select2=$("#view_plug1"),row2="";
                            var showplugins=0;
							$('.showpluginsHeader').hide();

            				for(var i=0;i<data.plugin.length; i++){
								/* -----------Plugin append starts--------------- */
								if(data.plugin[i].plugin_name == "Attendance"){
									addPlugin.attendance = parseInt(data.plugin[i].plugin_used);
                                    if(data.plugin[i].plugin_purchased > 0){
									showplugins=1;

                                    row += '<div class="row mod"><input onchange="getPluginCount(\''+data.plugin[i].plugin_name+'\' , \''+data.plugin[i].plugin_used+'\', \'add\', \''+data.plugin[i].plugin_purchased+'\')" type="checkbox" name="Modules"  value="'+data.plugin[i].plugin_id+'" id="'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" /> <label for="'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    row1 += '<div class="row mod"><input onchange="getPluginCount(\'e'+data.plugin[i].plugin_name+'\' , \''+data.plugin[i].plugin_used+'\', \'edit\', \''+data.plugin[i].plugin_purchased+'\')" type="checkbox" name="Modules"  value="'+data.plugin[i].plugin_id+'" id="e'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" /> <label for="e'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    row2 += '<div class="row mod"><input type="checkbox" name="vModules" disabled value="'+data.plugin[i].plugin_id+'" id="v'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" disabled/> <label for="v'+data.plugin[i].plugin_name+'" class="v'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                        $("#eRoaster").hide();
                                    }else{
                                        $("#eRoaster").show();
                                    }
                                }
								if(data.plugin[i].plugin_name == "Communicator"){
									addPlugin.communicator = parseInt(data.plugin[i].plugin_used);
                                    if(data.plugin[i].plugin_purchased > 0){
									showplugins=1;

                                    row += '<div class="row mod"><input onchange="getPluginCount(\''+data.plugin[i].plugin_name+'\' , \''+data.plugin[i].plugin_used+'\', \'add\', \''+data.plugin[i].plugin_purchased+'\')" type="checkbox" name="Modules"  value="'+data.plugin[i].plugin_id+'" id="'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" /> <label for="'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    row1 += '<div class="row mod"><input onchange="getPluginCount(\'e'+data.plugin[i].plugin_name+'\' , \''+data.plugin[i].plugin_used+'\', \'edit\', \''+data.plugin[i].plugin_purchased+'\')" type="checkbox" name="Modules"  value="'+data.plugin[i].plugin_id+'" id="e'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" /> <label for="e'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    row2 += '<div class="row mod"><input type="checkbox" name="vModules" disabled value="'+data.plugin[i].plugin_id+'" id="v'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" disabled/> <label for="v'+data.plugin[i].plugin_name+'" class="v'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    }
                                }
								if(data.plugin[i].plugin_name == "Expenses"){
									addPlugin.expenses = parseInt(data.plugin[i].plugin_used);
                                    if(data.plugin[i].plugin_purchased > 0){
									showplugins=1;

                                    row += '<div class="row mod"><input onchange="getPluginCount(\''+data.plugin[i].plugin_name+'\' , \''+data.plugin[i].plugin_used+'\', \'add\', \''+data.plugin[i].plugin_purchased+'\')" type="checkbox" name="Modules"  value="'+data.plugin[i].plugin_id+'" id="'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" /> <label for="'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    row1 += '<div class="row mod"><input onchange="getPluginCount(\'e'+data.plugin[i].plugin_name+'\' , \''+data.plugin[i].plugin_used+'\', \'edit\', \''+data.plugin[i].plugin_purchased+'\')" type="checkbox" name="Modules"  value="'+data.plugin[i].plugin_id+'" id="e'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" /> <label for="e'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    row2 += '<div class="row mod"><input type="checkbox" name="vModules" disabled value="'+data.plugin[i].plugin_id+'" id="v'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" disabled/> <label for="v'+data.plugin[i].plugin_name+'" class="v'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                        $("#edit_user_exp").hide();
                                    }else{
                                        $("#edit_user_exp").show();
                                    }
                                }
								if(data.plugin[i].plugin_name == "Inventory"){
									addPlugin.inventory = parseInt(data.plugin[i].plugin_used);
                                    if(data.plugin[i].plugin_purchased > 0){
									showplugins=1;

                                    row += '<div class="row mod"><input onchange="getPluginCount(\''+data.plugin[i].plugin_name+'\' , \''+data.plugin[i].plugin_used+'\', \'add\', \''+data.plugin[i].plugin_purchased+'\')" type="checkbox" name="Modules"  value="'+data.plugin[i].plugin_id+'" id="'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" /> <label for="'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    row1 += '<div class="row mod"><input onchange="getPluginCount(\'e'+data.plugin[i].plugin_name+'\' , \''+data.plugin[i].plugin_used+'\', \'edit\', \''+data.plugin[i].plugin_purchased+'\')" type="checkbox" name="Modules"  value="'+data.plugin[i].plugin_id+'" id="e'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" /> <label for="e'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    row2 += '<div class="row mod"><input type="checkbox" name="vModules" disabled value="'+data.plugin[i].plugin_id+'" id="v'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" disabled/> <label for="v'+data.plugin[i].plugin_name+'" class="v'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    }
                                }
								if(data.plugin[i].plugin_name == "Library"){
									addPlugin.library = parseInt(data.plugin[i].plugin_used);
                                    if(data.plugin[i].plugin_purchased > 0){
									showplugins=1;

                                    row += '<div class="row mod"><input onchange="getPluginCount(\''+data.plugin[i].plugin_name+'\' , \''+data.plugin[i].plugin_used+'\', \'add\', \''+data.plugin[i].plugin_purchased+'\')" type="checkbox" name="Modules"  value="'+data.plugin[i].plugin_id+'" id="'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" /> <label for="'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    row1 += '<div class="row mod"><input onchange="getPluginCount(\'e'+data.plugin[i].plugin_name+'\' , \''+data.plugin[i].plugin_used+'\', \'edit\', \''+data.plugin[i].plugin_purchased+'\')" type="checkbox" name="Modules"  value="'+data.plugin[i].plugin_id+'" id="e'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" /> <label for="e'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    row2 += '<div class="row mod"><input type="checkbox" name="vModules" disabled value="'+data.plugin[i].plugin_id+'" id="v'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" disabled/> <label for="v'+data.plugin[i].plugin_name+'" class="v'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    }
                                }
								if(data.plugin[i].plugin_name == "Navigator"){
									addPlugin.navigator = parseInt(data.plugin[i].plugin_used);
                                    if(data.plugin[i].plugin_purchased > 0){
									showplugins=1;

                                    row += '<div class="row mod"><input onchange="getPluginCount(\''+data.plugin[i].plugin_name+'\' , \''+data.plugin[i].plugin_used+'\', \'add\', \''+data.plugin[i].plugin_purchased+'\')" type="checkbox" name="Modules"  value="'+data.plugin[i].plugin_id+'" id="'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" /> <label for="'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    row1 += '<div class="row mod"><input onchange="getPluginCount(\'e'+data.plugin[i].plugin_name+'\' , \''+data.plugin[i].plugin_used+'\', \'edit\', \''+data.plugin[i].plugin_purchased+'\')" type="checkbox" name="Modules"  value="'+data.plugin[i].plugin_id+'" id="e'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" /> <label for="e'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                    row2 += '<div class="row mod"><input type="checkbox" name="vModules" disabled value="'+data.plugin[i].plugin_id+'" id="v'+data.plugin[i].plugin_name+'" class="'+data.plugin[i].plugin_used+'" disabled/> <label for="v'+data.plugin[i].plugin_name+'" class="v'+data.plugin[i].plugin_name+'"><b>'+data.plugin[i].plugin_name+'</b></label></div>';
                                        $("#edit_user_nav").hide()
                                    }else{
                                        $("#edit_user_nav").show();
                                    }
                                }
								if(showplugins == 1){
									$('.showpluginsHeader').show();
								}
            				}
            				console.log(addPlugin)
            				$("#plug").html("").append(row);
            				$("#plug1").html("").append(row1);
            				$("#view_plug1").html("").append(row2);
            				$("#Navigator").click(function(){
            						if($("#Navigator").is(":checked")){
            								$("#add_user_nav").prop("disabled",false);
            						}else{
            								$("#add_user_nav").prop("disabled",true);
            						}
            				});
            				$("#Attendance").click(function(){
            						if($("#Attendance").is(":checked")){
            								$("#Roaster").prop("disabled",false);
            						}else{
            								$("#Roaster").prop("disabled",true);
            								$("#Roaster").val("");
            						}
            				});
            				$("#Expenses").click(function(){
            						if($("#Expenses").is(":checked")){
            								$("#add_user_exp").prop("disabled",false);
            						}else{
            								$("#add_user_exp").prop("disabled",true);
            						}
            				});
							$("#eNavigator").click(function(){
								if($("#eNavigator").is(":checked")){
										$("#edit_user_nav").prop("disabled",false);
								}else{
										$("#edit_user_nav").prop("disabled",true);
								}
							});
							$("#eAttendance").click(function(){
								if($("#eAttendance").is(":checked")){
										$("#eRoaster").prop("disabled",false);
								}else{
										$("#eRoaster").prop("disabled",true);
										$("#eRoaster").val("");
								}
							});
							$("#eExpenses").click(function(){
								if($("#eExpenses").is(":checked")){
										$("#edit_user_exp").prop("disabled",false);
								}else{
										$("#edit_user_exp").prop("disabled",true);
								}
							});

                            $("#eCommunicator").click(function(){
								if($("#eCommunicator").is(":checked")){
										$("#edit_user_exp").prop("disabled",false);
								}else{
										$("#edit_user_exp").prop("disabled",true);
								}
							});
                            $("#eInventory").click(function(){
								if($("#eInventory").is(":checked")){
										$("#edit_user_exp").prop("disabled",false);
								}else{
										$("#edit_user_exp").prop("disabled",true);
								}
							});
                            $("#eLibrary").click(function(){
								if($("#eLibrary").is(":checked")){
										$("#edit_user_exp").prop("disabled",false);
								}else{
										$("#edit_user_exp").prop("disabled",true);
								}
							});
							$("#e_custo_assign").click(function(){
								if($("#e_custo_assign").is(":checked")){
										$("#eRoaster").prop("disabled",false);
								}else{
										$("#eRoaster").prop("disabled",true);
										$("#eRoaster").val("");
								}
							});
                            /*if(parseInt(data.modules[0].module_count)>= parseInt(data.modules[0].module_purchased)){
                                $(".error_mod").show();
            	                $(".error_mod").closest("div").find("span").text("You have exhausted the Module License.");
                                $("#CXO").prop("checked",false);
                                $("#Manager").prop("checked",false);
                                $("#Sales").prop("checked",false);
                                $('#useradbtn').hide();
            		        }else{
                                $(".error_mod").hide();
                                $('#useradbtn').show();
            		        }this is required for module count maintaining code */
                            /* -------------------------------------------------------------------------------------------------------- */
		}
	});


}
/* ----------------------------------------------------------------------- */
/* -----------------------------get MODULE Count---------------------- */
/* ----------------------------------------------------------------------- */
var addMod={};
function getcount(modulenm,modid,param,purcnt){

    /*$(".error_mod1").show();
	$(".error_mod1").closest("div").find("span").text("Please select any one of the designations.");*/

    var  modid1=0;
	if($("#"+modulenm).prop("checked") == true){
		/* -----------Module append--------------- */
		if(param == "add"){
		        modid1=0;
                $(".error_mod1").hide();
		        if(modulenm == "CXO"){
				    addMod.cxo =(parseInt(modid)+1);
                    modid1=addMod.cxo;
                    keepmodcnt =(parseInt(keepmodcnt)+1);
    			}
    			if(modulenm == "Manager"){
    				addMod.manager =(parseInt(modid)+1);
                    keepmodcnt =(parseInt(keepmodcnt)+1);
                    modid1 =addMod.manager;
    			}
    			if(modulenm == "Sales"){
    				addMod.sales =(parseInt(modid)+1);
                    keepmodcnt =(parseInt(keepmodcnt)+1);
                    modid1 =addMod.sales;
    			}
                /*if(parseInt(keepmodcnt)> parseInt(purcnt)){
                    $(".error_mod").show();
	                $(".error_mod").closest("div").find("span").text("You have exhausted the Selected Module License.");
                    $("#"+modulenm).prop("checked",false);
                    keepmodcnt =(parseInt(purcnt));
		        }else{
                    $(".error_mod").hide();
		        } this is required for module count maintaining code*/

		}
		if(param == "edit"){
                modid1=0;
                $(".error_mod1").hide();
		        if(modulenm == "eCXO"){
				    addMod.cxo =(addMod.cxo+1);
                    keepmodcnt =(parseInt(keepmodcnt)+1);
                    modid1=addMod.cxo;
			    }
			    if(modulenm == "eManager"){
				    addMod.manager =(addMod.manager+1);
                    keepmodcnt =(parseInt(keepmodcnt)+1);
				    modid1 =addMod.manager;
			    }
			    if(modulenm == "eSales"){
				    addMod.sales =(addMod.sales+1);
                    keepmodcnt =(parseInt(keepmodcnt)+1);
				    modid1 =addMod.sales;
			    }
                /*if(parseInt(keepmodcnt)> parseInt(purcnt)){
                    $(".error_mod1").show();
	                $(".error_mod1").closest("div").find("span").text("You have exhausted the Selected Module License.");
                    $("#"+modulenm).prop("checked",false);
                    keepmodcnt =(parseInt(purcnt));
		        }else{
                    $(".error_mod1").hide();
		        }this is required for module count maintaining code*/

		}
        if(param == "v"){
                modid1=0;
                $(".error_mod2").hide();
		        if(modulenm == "vCXO"){
				    addMod.cxo =(addMod.cxo+1);
                    keepmodcnt =(parseInt(keepmodcnt)+1);
                    modid1=addMod.cxo;
			    }
			    if(modulenm == "vManager"){
				    addMod.manager =(addMod.manager+1);
                    keepmodcnt =(parseInt(keepmodcnt)+1);
				    modid1 =addMod.manager;
			    }
			    if(modulenm == "vSales"){
				    addMod.sales =(addMod.sales+1);
                    keepmodcnt =(parseInt(keepmodcnt)+1);
				    modid1 =addMod.sales;
			    }
                /*if(parseInt(keepmodcnt)> parseInt(purcnt)){
                    $(".error_mod2").show();
	                $(".error_mod2").closest("div").find("span").text("You have exhausted the Selected Module License.");
                    $("#"+modulenm).prop("checked",false);
                    keepmodcnt =(parseInt(purcnt));
		        }else{
                    $(".error_mod2").hide();
		        } this is required for module count maintaining code*/

		}

	}else{
		/* -----------Module append--------------- */
		if(param == "add"){
		  $(".error_mod").hide();
			if(modulenm == "CXO"){
				addMod.cxo =(parseInt(modid));
                keepmodcnt =(parseInt(keepmodcnt)-1);
			}
			if(modulenm == "Manager"){
				addMod.manager =(parseInt(modid));
                keepmodcnt =(parseInt(keepmodcnt)-1);
			}
			if(modulenm == "Sales"){
				addMod.sales =(parseInt(modid));
                keepmodcnt =(parseInt(keepmodcnt)-1);
			}
		}
		if(param == "edit"){
		  $(".error_mod1").hide();
			if(modulenm == "eCXO"){
				addMod.cxo =(addMod.cxo -1);
                keepmodcnt =(parseInt(keepmodcnt)-1);
			}
			if(modulenm == "eManager"){
				addMod.manager =(addMod.manager-1);
                keepmodcnt =(parseInt(keepmodcnt)-1);
			}
			if(modulenm == "eSales"){
				addMod.sales =(addMod.sales-1);
                keepmodcnt =(parseInt(keepmodcnt)-1);
			}
		}
        if(param == "v"){
		  $(".error_mod2").hide();
			if(modulenm == "vCXO"){
				addMod.cxo =(addMod.cxo -1);
                keepmodcnt =(parseInt(keepmodcnt)-1);
			}
			if(modulenm == "vManager"){
				addMod.manager =(addMod.manager-1);
                keepmodcnt =(parseInt(keepmodcnt)-1);
			}
			if(modulenm == "vSales"){
				addMod.sales =(addMod.sales-1);
                keepmodcnt =(parseInt(keepmodcnt)-1);
			}
		}

	}
	/* console.log(addMod) */
}

/* ----------------------------------------------------------------------- */
/* -----------------------------get Plugin Count---------------------- */
/* ----------------------------------------------------------------------- */

var addPlugin={};
function getPluginCount(pluginName,pluginCount,section,purcnt){
    var modid1=0;
	/* -----------------------------ADD SECTION---------------------- */
	if(section == "add"){

		if($("#"+pluginName).prop("checked") == true){
		    modid1=0;
			if(pluginName == "Attendance"){
				addPlugin.attendance = (parseInt(pluginCount)+1);
                modid1=addPlugin.attendance;
			}
			if(pluginName == "Communicator"){
                $('#grp_emal_btn').hide(); // show when communicator plugin is enable

				addPlugin.communicator = (parseInt(pluginCount)+1);
                modid1=addPlugin.communicator;
			}
			if(pluginName == "Expenses"){
				addPlugin.expenses = (parseInt(pluginCount)+1);
                modid1=addPlugin.expenses;
			}
			if(pluginName == "Inventory"){
				addPlugin.inventory = (parseInt(pluginCount)+1);
                modid1=addPlugin.inventory;
			}
			if(pluginName == "Library"){
				addPlugin.library = (parseInt(pluginCount)+1);
                modid1=addPlugin.library;
			}
			if(pluginName == "Navigator"){
				addPlugin.navigator = (parseInt(pluginCount)+1);
                modid1=addPlugin.navigator;
			}
            if(parseInt(modid1)> parseInt(purcnt)){
                    $(".error_plug").show();
	                $(".error_plug").closest("div").find("span").text("You have exhausted the Selected Plugin License.");
                    $("#"+pluginName).prop("checked",false);
		    }else{
                    $(".error_plug").hide();
		    }

		}else{

			if(pluginName == "Attendance"){
				addPlugin.attendance = parseInt(pluginCount);
			}
			if(pluginName == "Communicator"){
			   $('#grp_emal_btn').hide();
				addPlugin.communicator = parseInt(pluginCount);
			}
			if(pluginName == "Expenses"){
				addPlugin.expenses = parseInt(pluginCount);
			}
			if(pluginName == "Inventory"){
				addPlugin.inventory = parseInt(pluginCount);
			}
			if(pluginName == "Library"){
				addPlugin.library = parseInt(pluginCount);
			}
			if(pluginName == "Navigator"){
				addPlugin.navigator = parseInt(pluginCount);
			}
		}
	}
	/* -----------------------------EDIT SECTION---------------------- */
	if(section == "edit"){
		if($("#"+pluginName).prop("checked") == true){
		    modid1=0;
			if(pluginName == "eAttendance"){
				addPlugin.attendance = (parseInt(addPlugin.attendance)+1);
                modid1=addPlugin.attendance;
			}
			if(pluginName == "eCommunicator"){
                 $('#grp_emal_btn_e').hide(); //show when communicator plugin is enable
				addPlugin.communicator = (parseInt(addPlugin.communicator)+1);
                modid1=addPlugin.communicator;
			}
			if(pluginName == "eExpenses"){

				addPlugin.expenses = (parseInt(addPlugin.expenses)+1);
                modid1=addPlugin.expenses;
			}
			if(pluginName == "eInventory"){
				addPlugin.inventory = (parseInt(addPlugin.inventory)+1);
                modid1=addPlugin.inventory;
			}
			if(pluginName == "eLibrary"){
				addPlugin.library = (parseInt(addPlugin.library)+1);
                modid1=addPlugin.library;
			}
			if(pluginName == "eNavigator"){
				addPlugin.navigator = (parseInt(addPlugin.navigator)+1);
                modid1=addPlugin.navigator;
			}
            if(parseInt(modid1)> parseInt(purcnt)){
                    $(".error_plug1").show();
	                $(".error_plug1").closest("div").find("span").text("You have exhausted the Selected Plugin License.");
                    $("#"+pluginName).prop("checked",false);
		    }else{
                    $(".error_plug1").hide();
		    }
		}else{
			if(pluginName == "eAttendance"){
				addPlugin.attendance = (parseInt(addPlugin.attendance)-1);
			}
			if(pluginName == "eCommunicator"){
			  $('#grp_emal_btn_e').hide();
				addPlugin.communicator = (parseInt(addPlugin.communicator)-1);
			}
			if(pluginName == "eExpenses"){

				addPlugin.expenses = (parseInt(addPlugin.expenses)-1);
			}
			if(pluginName == "eInventory"){
				addPlugin.inventory = (parseInt(addPlugin.inventory)-1);
			}
			if(pluginName == "eLibrary"){
				addPlugin.library = (parseInt(addPlugin.library)-1);
			}
			if(pluginName == "eNavigator"){
				addPlugin.navigator = (parseInt(addPlugin.navigator)-1);
			}
		}
	}
	/* console.log(addPlugin) */
}
function cancel2(){
  $('.modal').modal('hide');
  $('.modal input[type="text"], textarea').val('');
  $('.modal input[type="radio"]').prop('checked', false);
  $('.modal input[type="checkbox"]').prop('checked', false);
  $('.modal .contact_cat li').remove();
  $('.modal .email_cat li').remove();
  $('.modal select').val('');
  $(".error-alert").text("");
  $(".section2").hide();
  $(".section1").show();
  $("#add_user_exp").prop("disabled",true);
  $("#add_user_nav").prop("disabled",true);
  $("#Roaster").prop("disabled",true);
  $("#add_user_role option, #add_user_team option, .multiselect ul li, .multiselect1 ul li").remove();
  window.location.reload(true);
}

function edit_cancel2(){
  $('.modal').modal('hide');
  $('.error-alert').text('');
  $('.modal input[type="text"],.modal input[type="hidden"].modal textarea').val('');
  $('.modal input[type="radio"]').prop('checked', false);
  $('.modal input[type="checkbox"]').prop('checked', false);
  $(".e_section2").addClass('none');
  $(".e_section1").removeClass('none');
  $(".error-alert").text("");
  $("#currency_value_list").html("");
  $('.modal select').val('');
  window.location.reload(true);
 }
/* department-- */

function filldepartment(deptid,calid,salesperid,resourceCurrency,callCurrency,smsCurrency,grpmail){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('admin_userController1/get_department'); ?>",
						dataType:'json',
						cache : false,
						success: function(data) {
							if(error_handler(data)){
								return;
							}
                            /* ------------------------------------- department ------------------------------------------ */
							if(deptid==""){
								var select = $("#add_user_dep"), options = "<option value=''>Select Department</option>";
								select.empty();
								for(var i=0;i<data.dept.length; i++)
								{
									options += "<option value='"+data.dept[i].Department_id+"'>"+ data.dept[i].Department_name +"</option>";
								}
								select.append(options);

							}else{
							    var select = $("#edit_user_dep"), options = "<option value=''>Select Department</option>";
							    select.empty();

							    for(var i=0;i<data.dept.length; i++)
							    {
								    options += "<option value='"+data.dept[i].Department_id+"'>"+ data.dept[i].Department_name +"</option>";
							    }
								select.append(options);
                                $('#edit_user_dep').val(deptid);
                            }
                            /* ----------------------------------------------------------------------------------------------- */
                            /* ------------------------------------- sales persona ------------------------------------------ */

								var select = $("#add_sales_persona"), options = "<option value=''>Select Sales Persona</option>";
                                var select1 = $("#edit_sales_persona"), options = "<option value=''>Select Sales Persona</option>";
								select.empty();
								select1.empty();
								for(var i=0;i<data.salespersona.length; i++)
								{
									options += "<option value='"+data.salespersona[i].lookup_id+"'>"+ data.salespersona[i].lookup_value +"</option>";
								}
								select.append(options);
								select1.append(options);
                                $('#edit_sales_persona').val(salesperid);

                            /* ----------------------------------------------------------------------------------------------- */
                            /* ------------------------------------- groupmail ------------------------------------------ */
                                if(grpmail==""){
    								var select = $("#useridList"), options = "<option value=''>Select Email ID's</option>";
    								select.empty();
    								for(var i=0;i<data.groupmail.length; i++)
    								{
    									options += "<option value='"+data.groupmail[i].user_email_settings_id+"'>"+ data.groupmail[i].email_id +"</option>";
    								}
    								select.append(options);

    							}else{

                                }
                            /* ------------------------------------- ------------- ------------------------------------------ */


                            /* --------------------------------------- calender ----------------------------------------------------- */
                            var select = $("#add_user_Hcal"), options = "<option value=''>Select Calender</option>";
  			                var select1 = $("#edit_user_Hcal"), options = "<option value=''>Select Calender</option>";;
  			                select.empty();
  			                select1.empty();
  			                for(var i=0;i<data.calender.length; i++)
  			                {
  				                options += "<option value='"+data.calender[i].calenderid+"'>"+ data.calender[i].calendername +"</option>";
  			                }
  			                    select.append(options);
  			                    select1.append(options);
								if(calid!=""){
									$('#edit_user_Hcal').val(calid);
								}
                            /* ------------------------------------------------------------------------------------------------------ */

                            /* ----------------------------------------------- currency --------------------------------------------------- */
                            var select = $("#resourceCurrency"), options = '<option value="" selected>Select</option>';
                            var select1 = $("#e_resourceCurrency"), options = '<option value="" selected>Select</option>';
        		            select.empty();
        		            select1.empty();
        		            for(var i=0;i<data.currency.length; i++){
        			                options += "<option value='"+data.currency[i].currency_id+"'>"+ data.currency[i].currency_name +"</option>";
        		            }
        		            select.append(options);
        		            select1.append(options);
							if(resourceCurrency!=""){
								$('#e_resourceCurrency').val(resourceCurrency);
							}

        					var select = $("#callCurrency"), options = '<option value="" selected>Select</option>';
        					var select1 = $("#e_callCurrency"), options = '<option value="" selected>Select</option>';
        	                select.empty();
        	                select1.empty();
        		            for(var i=0;i<data.currency.length; i++){
        			                options += "<option value='"+data.currency[i].currency_id+"'>"+ data.currency[i].currency_name +"</option>";
        		            }
        		            select.append(options);
        		            select1.append(options);
							if(callCurrency!=""){
								$('#e_callCurrency').val(callCurrency);
							}

        					var select = $("#smsCurrency"), options = '<option value="" selected>Select</option>';
        					var select1 = $("#e_smsCurrency"), options = '<option value="" selected>Select</option>';
        		            select.empty();
        		            select1.empty();
        		            for(var i=0;i<data.currency.length; i++){
        			                options += "<option value='"+data.currency[i].currency_id+"'>"+ data.currency[i].currency_name +"</option>";
        		            }
        		            select.append(options);
        		            select1.append(options);
							if(smsCurrency!=""){
								$('#e_smsCurrency').val(smsCurrency);
							}


                            /* ------------------------------------------------------------------------------------------------------------- */
                    }
				});
}

function getroles(depid,obj){
				var addObj={};
				addObj.dept_id =depid;
				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_userController1/get_rolesdata'); ?>",
					data:JSON.stringify(addObj),
					dataType : 'json',
					cache : false,
					success : function(data){
						if(error_handler(data)){
							return;
						}
						var select = $("#add_user_role"), options = '<option value="">Select</option>';
						var select1 = $("#edit_user_role"), options = '<option value="">Select</option>';
						select.empty();
						select1.empty();
						for(var i=0;i<data.length; i++){
							options += "<option value='"+data[i].role_id+"'>"+ data[i].role_name +"</option>";
						}
						$('#add_user_role').empty();
						$('#edit_user_role').empty();
						select.append(options);
						select1.append(options);
						if(obj!=""){
							$("#edit_user_role option[value='"+obj+"']").attr("selected",true);
						}

					}
				});
}
function get_designation(roleid,obj1){

                var addObj = {};
                addObj.roleid = roleid;
				var aa=obj1;
				/* var bb=obj1; */
                $.ajax({
                   type : "POST",
                   url : "<?php echo site_url('admin_userController1/get_designation')?>",
                   data : JSON.stringify(addObj),
                   dataType : 'json',
                   cache : false,
                   success : function(data){
							if(error_handler(data)){
								return;
							}
						  var row='';
						  row+='<option value="">Select</option>';
						  for(var k=0;k<data.length;k++){
                                if(data.length==1 && data[k].role_value==0){
						            role_val=1;
                                    $("#rolval").val(1);
                                    $("#add_desg_name1").val(data[k].role_id);
                                    $("#add_rep_btn").hide();
                                    $("#edit_rep_btn").hide();
									if(obj1 === undefined || obj1=="" ){
										var depid= $('#add_user_dep').val();
										var obj2="";
										getteamdata(depid,obj2,ofloc2,loc2,ind2,procur,sellarray);
									}else if(obj1=="a"){
										var depid= $('#edit_user_dep').val();
                                        var obj2="";
										getteamdata(depid,obj2,ofloc2,loc2,ind2,procur,sellarray);
									}
                                    if(role_val==1 && selrowflg==1){
                                        $("#eCXO").closest(".col-md-4").show();
                                        $("#eManager").closest(".col-md-4").show();
                                        $("#eSales").closest(".col-md-4").show();
                                    }

                                }
                                else if(data[k].role_value>0){
									role_val=0;
                                    $("#rolval").val(0);
                                    $("#add_rep_btn").show();
									$("#edit_rep_btn").show();
						            if(data[k].hasOwnProperty('deptname')) {
                                       row+='<option value="'+data[k].role_id+'">'+data[k].role_name+" ("+data[k].deptname+")"+'</option>';
                                    }else{
                                        row+='<option value="'+data[k].role_id+'">'+data[k].role_name+'</option>';
                                    }
                                }
                          }
						  $('#add_user_desg').empty();
						  $('#add_user_desg').append(row);
						  $('#edit_user_desg').empty();
						  $('#edit_user_desg').append(row);
							if(aa!=""){
								$("#edit_user_desg option[value='"+aa+"']").attr("selected",true);
							}
                   }
                });
};

function getrepto(repid,obj,uid) {

			var addObj={};
			addObj.reporting_id =repid;
			addObj.uid =uid;
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_userController1/get_reportingname'); ?>",
				data:JSON.stringify(addObj),
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
						return;
					}
					var select = $("#add_desg_name"), options = '<option value="">Select</option>';
					var select1 = $("#edit_desg_name"), options = '<option value="">Select</option>';
					select.empty();
					select1.empty();
					$last_name="";
					for(var i=0;i<data.length; i++){

						if(data[i].last_name)
						{
						   $last_name= data[i].last_name;
						}else{$last_name="";}
							options += "<option value='"+data[i].user_id+"' >"+ data[i].user_name+" "+$last_name +"</option>";
							modularr[data[i].user_id]=data[i].cxo_module+"<@>"+data[i].manager_module;

					}
					$('#add_desg_name').empty();
					select.append(options);
					$('#edit_desg_name').empty();
					select1.append(options);
					if(obj!=""){
								$("#edit_desg_name option[value='"+obj+"']").attr("selected",true);
					}

				}
			});

};
function mod_plug1(valu){
  if(valu !=""){
        var valu1=modularr[valu]
	    var modVal = valu1.split("<@>");
	    var cxoVal=modVal[0];
	    var mangrVal=modVal[1];
	    if(cxoVal == 0){
		    $("#CXO").closest(".col-md-4").hide();
		    $("#eCXO").closest(".col-md-4").hide();
		    $("#eCXO").prop("checked",false);
		    $("#CXO").prop("checked",false);

	    }else{
		    $("#CXO").closest(".col-md-4").show();
		    $("#eCXO").closest(".col-md-4").show();

	    }
	    if(mangrVal == 0 && cxoVal == 0){
		    $("#Manager").closest(".col-md-4").hide();
		    $("#eManager").closest(".col-md-4").hide();
            $("#Manager").prop("checked",false);
		    $("#eManager").prop("checked",false);

	    }else{
		    $("#Manager").closest(".col-md-4").show();
		    $("#eManager").closest(".col-md-4").show();

	    }

  }

}
function add2(){
        if($("#add_user_desg").val()==""){
			$("#add_user_desg").closest("div").find("span").text("Please select designation.");
			$("#add_user_desg").focus();
			return;
		}else{
			$("#add_user_desg").closest("div").find("span").text("");

		}
        if($("#add_desg_name").val()==""){
				    $("#add_desg_name").closest("div").find("span").text("Reporting is required.");
				    $("#add_desg_name").focus();
                    return;
        }
        else{
			$("#add_desg_name").closest("div").find("span").text("");

		}
		var desid = $("#add_desg_name option:selected").text();
		$("#add_rep_into").val(desid);
		$("#addmodal1").modal("hide");
}

function getteamdata(depid,obj1,ofloc2,loc2,ind2,procur,sellarray){
				if(depid !=""){
					var addObj={};
					addObj.depid=depid;
					$.ajax({
						type : "POST",
						url : "<?php echo site_url('admin_userController1/get_teamdata'); ?>",
						data:JSON.stringify(addObj),
						dataType : 'json',
						cache : false,
						success : function(data){
								if(error_handler(data)){
									return;
								}
							var select = $("#add_user_team"), options = '<option value="">Select</option>';
							var select1 = $("#edit_user_team"), options = '<option value="">Select</option>';
							select.empty();
							select1.empty();
							for(var i=0;i<data.length; i++){
								options += "<option value='"+data[i].teamid+"'>"+ data[i].teamname +"</option>";
								idarray[data[i].teamid]=data[i].business_location_id+"<@>"+data[i].industry_id+"<@>"+data[i].customer_management;
							}
							$('#add_user_team').empty();
							select.append(options);
							$('#edit_user_team').empty();
							select1.append(options);
							if(obj1!=""){
									$("#edit_user_team option[value='"+obj1+"']").attr("selected",true);
                                    getparentnode(obj1,ofloc2,loc2,ind2,procur,sellarray);
							}

						}
					});
				}
}
function getparentnode(teamsid,ofloc2,loc2,ind2,procur,sellarray){

				var addObj={};
				addObj.teamid=teamsid;
				var val1=idarray[teamsid];
				var splitvalue=val1.split("<@>");
				addObj.busiid=splitvalue[0];
				addObj.indusid=splitvalue[1];
					$.ajax({
    					type : "POST",
    					url : "<?php echo site_url('admin_userController1/get_teams_dependdata'); ?>",
    					data:JSON.stringify(addObj),
    					dataType : 'json',
    					cache : false,
    					success : function(data){
                                /* --------------------- sell type -------------------- */
								if(error_handler(data)){
									return;
								}



                                var str="";
                                var multipl='';
                                //var regionid =  (data.selltype[0].regionid).split(",");
                                for(x=0; x<data.selltype.length;x++){
                                     multipl +='<li><label><input type="checkbox" value="'+data.selltype[x].lookup_id+'">  '+data.selltype[x].lookup_value+'<label></li>';
                                     sellarray1.push(data.selltype[x].lookup_id);
                                }
                                $('#add_sellType .sell_multiselect ul').empty();
        					    $("#add_sellType .sell_multiselect ul").append(multipl);
                                $('#edit_sellType .sell_multiselect ul').empty();
        					    $("#edit_sellType .sell_multiselect ul").append(multipl);
                                for(var k=0;k<sellarray1.length;k++){
						                for(var j=0;j<sellarray.length;j++){
							                if($.trim(sellarray[j].map_id) == $.trim(sellarray1[k])){
								                $("#edit_sellType .sell_multiselect ul li input[value='"+sellarray[j].map_id+"']").prop('checked', true);
							                }
						                }
					            }

                                /* --------------------- office location -------------------- */
                                var multipl='';
        					    for(var i=0;i<data.offloc.length; i++){
        						    multipl +='<li><label><input type="checkbox" value="'+data.offloc[i].locid+'">  '+data.offloc[i].ofcLoc+" ("+data.offloc[i].ofcLoc1+")"+'<label></li>';
									ofloc1.push(data.offloc[i].locid);
        					    }
        					    $('#add_user_loc .multiselect ul').empty();
        					    $("#add_user_loc .multiselect ul").append(multipl);
								$('#edit_user_loc .multiselect1 ul').empty();
        					    $("#edit_user_loc .multiselect1 ul").append(multipl);
								for(var k=0;k<ofloc1.length;k++){
						                for(var j=0;j<ofloc2.length;j++){
							                if($.trim(ofloc2[j].id) == $.trim(ofloc1[k])){
								                $("#edit_user_loc .multiselect1 ul li input[value='"+ofloc2[j].id+"']").prop('checked', true);
							                }
						                }
					            }
                                /* --------------------------------------------------------------- */

                                /* ------------------------------ bussiness location----------------------------- */

                                var multipl='';
				                for(var i=0;i<data.business.length; i++){
				                    multipl +='<li><label><input type="checkbox" value="'+data.business[i].nodeid+'">  '+data.business[i].nodename+" ("+data.business[i].nodename1+")"+'<label></li>';
					                loc1.push(data.business[i].nodeid);
				                }

				                $(".multiselect_loc ul").empty();
				                $(".multiselect_loc ul").append(multipl);
				                $(".multiselect_loc1 ul").empty();
				                $(".multiselect_loc1 ul").append(multipl);
                                for(var k=0;k<loc1.length;k++){
						                for(var j=0;j<loc2.length;j++){
							                if($.trim(loc2[j].map_id) == $.trim(loc1[k])){
								                $(".multiselect_loc1 ul li input[value='"+loc2[j].map_id+"']").prop('checked', true);
							                }
						                }
					            }

                                /* --------------------------------------------------------------------------------------------- */
                                /* ---------------------------------- industry -------------------------------------------------- */
                                var multipl='';
				                for(var i=0;i<data.industry.length; i++){
					                multipl +='<li><label><input type="checkbox" value="'+data.industry[i].nodeid+'">  '+data.industry[i].nodename+" ("+data.industry[i].nodename1+")"+'<label></li>';
					                ind.push(data.industry[i].nodeid);
				                }
				                $(".multiselect_indu ul").empty();
				                $(".multiselect_indu ul").append(multipl);
				                $(".multiselect_indu1  ul").empty();
				                $(".multiselect_indu1  ul").append(multipl);
                                for(var k=0;k<ind.length;k++){
						                for(var j=0;j<ind2.length;j++){
							                if($.trim(ind2[j].map_id) == $.trim(ind[k])){
								                $(".multiselect_indu1 ul li input[value='"+ind2[j].map_id+"']").prop('checked', true);
							                }
						                }
					            }
                                /* --------------------------------------------------------------------------------------------- */
                                /* ----------------------------- product currency -------------------------------------------------- */
                                var currencyhtml ="";
                				var currencyhtml1 ="";
								var container ="";
                                var sec_from=$("#sec_from").val();
								if(sec_from == "add"){
									container = $("#currency_value_list1");  //add
                                    $("#currency_value_list").html("");
								}
                                if(sec_from == "edit"){
									container = $("#currency_value_list");  //edit
                                    $("#currency_value_list1").html("");
								}
                				container.html("");

                					for(var i=0;i<data.procurdata.length; i++){

										container.append('<div class="col-md-5" id="currencyList'+i+'"><label class="prod_leaf_node"><input id="prod'+data.procurdata[i].product_id+'" onchange="checkUncheck1(this.id)" type="checkbox" value="'+data.procurdata[i].product_id+'">  '+data.procurdata[i].productname+'</label></div>');
										if(data.procurdata[i].curdata[0].currency_id !=null) {
											currencyhtml="";
											currencyhtml +='<div id="currency_value'+i+'" class="multiselect">';
											currencyhtml +='<ul>';
											for(var j=0;j<data.procurdata[i].curdata.length; j++){
												if(data.procurdata[i].curdata[j].currency_id !=null){
													currencyhtml +='<li><label><input type="checkbox" value="'+data.procurdata[i].curdata[j].currency_id+'" disabled>  '+data.procurdata[i].curdata[j].currencyname+'<label></li>';
												}

											}
											currencyhtml +='</ul>';
											currencyhtml +='</div>';
											$("#currencyList"+i).append(currencyhtml)
										}

									}
                					for(var i=0;i<data.procurdata.length; i++){
                						if(data.procurdata[i].hasOwnProperty('curdata')) {

                						}else{
                							currencyhtml1 += '<div class="col-md-12" id="currencyList'+i+'"><label class="prod_leaf_node"><input type="checkbox" value="'+data.procurdata[i].product_id+'">  '+data.procurdata[i].productname+'</label></div>';
                						}
                					}
                					if( currencyhtml1.length > 0){
                						container.append("<div class='without-curr col-md-5'>"+ currencyhtml1 +"</div>");
                					}
									if(procur.length != 0){
										$("#currency_value_list .prod_leaf_node input[type='checkbox']").each(function(){
											for(chk=0; chk<procur.length; chk++){
												if($(this).val() == procur[chk].product_id){
													if($(this).closest(".col-md-5").find(".multiselect").length > 0){
														$(this).closest("label").addClass("highlight");
													}
													$(this).prop("checked", true);
													$(this).closest(".col-md-5").find(".multiselect").find("input[type='checkbox']").prop("disabled", false);
													$(this).closest(".col-md-5").find(".multiselect").find("input[type='checkbox']").each(function(){
														for(chk1=0; chk1<procur[chk].curdata.length; chk1++){
															if($(this).val() == procur[chk].curdata[chk1].currency_id){
																$(this).prop("checked", true).prop("disabled", false);
															}
														}
													})
												}
											}
										})
									}

                                    /* ----------------------------------------------------------------------------------------*/
    					}
				});

}

 /* enable/disable currency checkbox  */
function checkUncheck1(id){
	var selected = $("#"+$.trim(id));
	if(selected.prop("checked") == true){
		$('#error').text('');
		selected.closest(".row").find(".col-md-5").removeAttr("style");
		selected.closest("label").removeClass("error").addClass("highlight");
		selected.closest(".col-md-5").find(".multiselect").find("input[type=checkbox]").removeAttr("disabled");
	}else{
		selected.closest("label").removeClass("error").removeClass("highlight");
		selected.closest(".col-md-5").removeAttr("style")
		selected.closest(".col-md-5").find(".multiselect").find("input[type=checkbox]").attr('disabled', 'disabled');
		selected.closest(".col-md-5").find(".multiselect").find("input[type=checkbox]").prop('checked', false);
	}
}
/*-----------------------------------------------------------------aad multiple fields ends---------------------------------------------------*/

function add(){
    add_contactlist('contact_cat','mobile_add','mobile','mobile1','home','work','main');
}

function add1_mobile(){

    add_contactlist('contact_cat1','mobile_edit','mob_edit','mobile1_edit','home_edit','work_edit','main_edit');
}

function add_contactlist(listshow,selectid,inputid,selectval1,selectval2,selectval3,selectval4){

		$("."+listshow+"").show();

		var myval = $("#"+inputid+"").val();
		if($.trim($("#"+selectid+"").val())==""){
			$("#"+inputid+"").closest("div").find("span").text("Please select one option.");
			$("#"+inputid+"").focus();
			return;
		}else{
			$("#"+inputid+"").closest("div").find("span").text("");
		}
		if($.trim($("#"+inputid+"").val())==""){
			$("#"+inputid+"").closest("div").find("span").text("Mobile is required.");
			$("#"+inputid+"").focus();
			return;
		}else if(!num_chk.test($.trim($("#"+inputid+"").val()))){
			$("#"+inputid+"").closest("div").find("span").text("Invalid number");
			$("#"+inputid+"").focus();
			return;
		}else if(myval.length < 10) {
			$("#"+inputid+"").closest("div").find("span").text("Value must contain 10 characters.");
			$(this).focus();
			return;
		}else {
			$("#"+inputid+"").closest("div").find("span").text("");
		}
        var numberChk = 0;
        $("."+listshow+" ul li span").each(function(){
            if($.trim($("#"+inputid+"").val()) == $.trim($(this).text())){
                numberChk =1;
            }
        })
        if(numberChk == 1){
            alert("Already Added");
            return;
        }else{

                addObj={};
                addObj.phno=$.trim($("#"+inputid+"").val());
                $("#"+inputid+"").val('');
                $.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_userController1/check_phone/phone'); ?>",
					data:JSON.stringify(addObj),
					dataType : 'json',
					cache : false,
					success : function(data){
						if(error_handler(data)){
							return;
						}
                           if(data==0){

                                    var mob = addObj.phno;
                            		var val = $("#"+selectid+"").val();
                            		if(val == "mobile"){

                            			$("#"+selectval1+" ul").append("<li style='margin-left:40px'><i class='fa fa-mobile' aria-hidden='true'></i> : <span> "+mob+" </span>  <a href='#' onfocus='delPhoneNumber(\""+selectval1+"\")'><i class='fa fa-trash'  aria-hidden='true'></i></a></li>");
                            		}
                            		if(val == "home"){
                            			$("#"+selectval2+" ul").append("<li style='margin-left:40px'><i class='fa fa-home' aria-hidden='true'></i> : <span> "+mob+" </span>  <a href='#' onfocus='delPhoneNumber(\""+selectval2+"\")'><i class='fa fa-trash'  aria-hidden='true'></i></a></li>");
                            		}
                            		if(val == "work"){
                            			$("#"+selectval3+" ul").append("<li style='margin-left:40px'><i class='fa fa-building' aria-hidden='true'></i> : <span> "+mob+" </span>  <a href='#' onfocus='delPhoneNumber(\""+selectval3+"\")'><i class='fa fa-trash'  aria-hidden='true'></i></a></li>");
                            		}
                            		if(val == "main"){
                            			$("#"+selectval4+" ul").append("<li style='margin-left:40px'><i class='fa fa-phone-square' aria-hidden='true'></i> : <span> "+mob+" </span>  <a href='#' onfocus='delPhoneNumber(\""+selectval4+"\")'><i class='fa fa-trash'  aria-hidden='true'></i></a></li>");
                            		}


                           }else{

                                alert("Already Added");
                                return;

                           }

					}
				});

        }

}
function delPhoneNumber(selectid){
    $("#"+selectid+" li .fa.fa-trash").each(function(){
		$(this).click(function(){
			$(this).closest("li").remove();
		});
	});
}
function email(){
        add_emaillist('email_cat','mobile_email','add_user_email','work_email','email_personal');
}
function email1(){
        add_emaillist('email_cat_edit','mobile_email_edit','edit_user_email','work_email_edit','email_personal_edit');
}

/* --- add email check ------------------------------------------------------------- */

function add_emaillist(listshow,selectid,inputid,selectval1,selectval2){
		$("."+listshow+"").show();
		if($.trim($("#"+selectid+"").val())==""){
			$("#"+inputid+"").closest("div").find("span").text("Please select any one email type.");
			$("#"+inputid+"").focus();
			return;
		}else{
			$("#"+inputid+"").closest("div").find("span").text("");
		}
		if($.trim($("#"+inputid+"").val())==""){
			$("#"+inputid+"").closest("div").find("span").text("Email is required.");
			$("#"+inputid+"").focus();
			return;
		}else if(!email_chk.test($.trim($("#"+inputid+"").val()))){
			$("#"+inputid+"").closest("div").find("span").text("Invalid email");
			$("#"+inputid+"").focus();
			return;
		}else{
			$("#"+inputid+"").closest("div").find("span").text("");
		}

        var numberChk = 0;
        $("."+listshow+" ul li span").each(function(){
            if($.trim($("#"+inputid+"").val()) == $.trim($(this).text())){
                numberChk =1;
            }
        })
        if(numberChk == 1){
            alert("Already Added");
            return;
        }else{

                addObj={};
                addObj.phno=$.trim($("#"+inputid+"").val());
                $("#"+inputid+"").val('');
                $.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_userController1/check_phone/email'); ?>",
					data:JSON.stringify(addObj),
					dataType : 'json',
					cache : false,
					success : function(data){
						if(error_handler(data)){
							return;
						}
					        if(data==0){
                                    var mob1 =addObj.phno;
                            		var val1 = $("#"+selectid+"").val();
                            		if(val1 == "work"){
                            			p=p+1;
                            			$("#"+selectval1+" ul").append('<li style="margin-left:40px"><i class="fa fa-envelope" aria-hidden="true"></i> : <span> '+mob1+' </span>  <a href="#"><i class="fa fa-trash" ></i></a></li>');

                            		}
                            		if(val1 == "personal"){
                            			w=w+1;
                            			$("#"+selectval2+" ul").append('<li style="margin-left:40px"><i class="fa fa-user" aria-hidden="true"></i>: <span> '+mob1+' </span>  <a href="#"><i class="fa fa-trash" ></i></a></li>');

                            		}

                            		$("."+listshow+" li .fa.fa-trash").each(function(){
                            			$(this).click(function(){
                            				$(this).closest("li").remove();
                            			});
                            		});
					        }else{

                                alert("Already Added");
                                return;
                           }

                    }

                });
        }

}
/* --------------------------------- first section save----------------------------------------------- */
 function conf_alert(data){
		$("#confirm_alert").modal('show');
}
	function alert_cancel(){
		$("#confirm_alert").modal('hide');
}


function next(){
	$("#confirm_alert").modal('hide');
	var addObj = p;
	var phn = "";
	var email = "";
	$(".phn_list ul li input[type=radio]").each(function(){
		if($(this).prop("checked") == true){
			phn = $(this).closest("li").text();
		}
	})
    $(".email_list ul li input[type=radio]").each(function(){
		if($(this).prop("checked") == true){
			email = $(this).closest("li").text();
		}
	})
	addObj.sendLoginDetails={"phn": phn,"email": email};
    console.log(addObj);
	loaderShow();
	$.ajax({
			 type : "POST",
			 url : "<?php echo site_url('admin_userController1/post_data'); ?>",
			 dataType : 'json',
				data : JSON.stringify(addObj),
				cache : false,
				success : function(data){
					 load();
					 loaderHide();
					 if(error_handler(data)){
						return;
					}
					 var userID = data;
					 $('#loadingmessage').hide();
					 $("#add_user").val(userID);
					 $("#sec_from").val("add");
					 $(".section1").hide();
					 $(".section2").show();
			 }
	});

}
/*-----------------------------------------------------------proceed functions starts---------------------------------------------------*/
function proceed(){
		$(".error-alert").text("");
		var saveflg=0;
		if($.trim($("#add_name").val())==""){
			$("#add_name").closest("div").find("span").text("Enter user name .");
			$("#add_name").focus();
			saveflg=1;
			return;
		}else if(!text_chk.test($.trim($("#add_name").val()))){
			$("#add_name").closest("div").find("span").text("Enter only characters");
			$("#add_name").focus();
			saveflg=1;
			return;
		}else{
			$("#add_name").closest("div").find("span").text("");
		}
		if(!text_chk.test($.trim($("#add_name1").val()))){
			$("#add_name1").closest("div").find("span").text("Enter only characters");
			$("#add_name1").focus();
			saveflg=1;
			return;
		}else{
			$("#add_name1").closest("div").find("span").text("");
		}
		if($.trim($("#add_user_eId").val())==""){
			$("#add_user_eId").closest("div").find("span").text("Enter valid Employee ID.");
			$("#add_user_eId").focus();
			saveflg=1;
			return;
		}else if(!emp_chk.test($.trim($("#add_user_eId").val()))){
			$("#add_user_eId").closest("div").find("span").text("Enter only characters");
			$("#add_user_eId").focus();
			saveflg=1;
			return;
		}else{
			$("#add_user_eId").closest("div").find("span").text("");
		}
		$(".error-alert").text("");
		var date_str=$("#add_DOB").val();
		var today_date = moment();
		var diff1 = today_date.diff(date_str, 'years');
		if(diff1 < 18){
			$(".sibling").closest("div").find(".error-alert").text("Should be atleast 18 years or above.");
			$(".sibling").focus();
			saveflg=1;
			return;
		}else{
			$(".sibling").closest("div").find(".error-alert").text("");
		}
		if($("#add_gender").val()==""){
			$("#add_gender").closest("div").find("span").text("Select gender.");
			$("#add_gender").focus();
			saveflg=1;
			return;
		}else{
			$("#add_gender").closest("div").find("span").text("");
		}
		if($.trim($("#add_user_dep").val())==""){
			$("#add_user_dep").closest("div").find("span").text("Please select department.");
			$("#add_user_dep").focus();
			saveflg=1;
			return;
		}else{
			$("#add_user_dep").closest("div").find("span").text("");
		}
		if($("#add_user_role").val()==""){
			$("#add_user_role").closest("div").find("span").text("Please select role.");
			$("#add_user_role").focus();
			saveflg=1;
			return;
		}else{
			$("#add_user_role").closest("div").find("span").text("");
		}
		if(role_val==0){
			if($("#add_rep_into").val()==""){
				$("#add_rep_into").closest("div").find("span").text("Reporting is required.");
				$("#add_rep_into").focus();
				saveflg=1;
				return;
			}else{
				$("#add_rep_into").closest("div").find("span").text("");
                if($("#add_desg_name").val()==""){
				    $("#add_desg_name").closest("div").find("span").text("Reporting is required.");
				    $("#add_desg_name").focus();
                    saveflg=1;
				    return;
                }

			}

			if($("#add_user_desg").val()=="" ){
				$("#add_user_desg").closest("div").find("span").text("Please select designation.");
				$("#add_user_desg").focus();
				saveflg=1;
				return;
			}else{
				$("#add_user_desg").closest("div").find("span").text("");
			}

		}else{
		    /* check for modules  */

            if($("#Sales").is(':checked') && ($("#Manager").prop('checked')==false) && $("#CXO").prop('checked')==false){
                $(".error_mod").show();
      			$(".error_mod").closest("div").find("span").text("Please select One more Designation Along with Executive Module .");
      			saveflg=1;
      			return;
            }else{
                $(".error_mod").hide();
            }

		}
		if($("#add_user_team").val()==""){
			$("#add_user_team").closest("div").find("span").text("Please select Team.");
			$("#add_user_team").focus();
			saveflg=1;
			return;
		}else{
			$("#add_user_team").closest("div").find("span").text("");
		}

		var addObj={};

		addObj.OfcLocTarget =[];
		addObj.selltype =[];
		addObj.work1=[];
		addObj.personal=[];
		addObj.mobile=[];
		addObj.home=[];
		addObj.work=[];
		addObj.main=[];
		addObj.add_user_dep = $.trim($("#add_user_dep").val());
		addObj.add_user_role  = $.trim($("#add_user_role").val());
		addObj.add_sales_persona  = $.trim($("#add_sales_persona").val());
		if($("#add_rep_into").val()==""){
			addObj.add_rep_into = $.trim($("#add_desg_name1").val());
		}else{
			var value=$.trim($("#add_desg_name").val());
			var add_rep_into=value.split("<@>");
			addObj.add_rep_into =add_rep_into[0];
		}

        if($("#add_user_desg").val()!== 'undefined'){
                addObj.add_rept_desg = $.trim($("#add_user_desg").val());

		}else{
                $("#add_user_desg").closest("div").find("span").text("Please select designation.");
				$("#add_user_desg").focus();
				saveflg=1;
				return;
		}
		var value=$.trim($("#add_user_team").val());
		addObj.add_user_team =value;
		addObj.add_user_region = $.trim($("#add_user_region").val());
		addObj.add_user = $.trim($("#add_user").val());
		addObj.ofc_address = $.trim($("#ofc_address").val());
		$("#add_user_loc input[type=checkbox]").each(function(){
		   if($(this).prop('checked')== true){
				addObj.OfcLocTarget.push($(this).val());

		   }
		});
		$("#add_sellType input[type=checkbox]").each(function(){
		   if($(this).prop('checked')== true){
				addObj.selltype.push($(this).val());

		   }
		});
		$("#work_email span").each(function(){
			addObj.work1.push($(this).text());
		});
		$("#email_personal span").each(function(){
			addObj.personal.push($(this).text());
		});
		if(addObj.work1.length==0 && addObj. personal.length==0){
			$("#add_user_email").closest("div").find("span").text("Enter valid Email ID.");
			saveflg=1;
			return;
		}
		if(addObj.OfcLocTarget.length==0){
			$("#add_user_loc").closest("div").find("span").text("Please select office location.");
			saveflg=1;
			return;
		}else{
			$("#add_user_loc").closest("div").find("span").text("");
		}

		if(addObj.selltype.length==0){
			$("#add_sellType").closest("div").find("span").text("Please select Type of Sell.");
			saveflg=1;
			return;
		}else{
			$("#add_sellType").closest("div").find("span").text("");
		}


		addObj.user_name = $.trim($("#add_name").val());
		addObj.last_name = $.trim($("#add_name1").val());
		addObj.user_dob  = $.trim($("#add_DOB").val());
		addObj.user_gender = $.trim($("#add_gender").val());
		addObj.user_resadd = $.trim($("#res_address").val());
		if(eid.length){
		for(var i=0;i<eid.length;i++){
			if($("#add_user_eId").val()!=eid[i]){
				addObj.add_user_eId = $.trim($("#add_user_eId").val());
			}else{
				$("#add_user_eId").closest("div").find("span").text("Already exists");
				saveflg=1;
				return;
			}
		}
		}else{
			   addObj.add_user_eId = $.trim($("#add_user_eId").val());
		}
		$("#home span").each(function(){
			addObj.home.push($(this).text());
		});
		$("#mobile1 span").each(function(){
			addObj.mobile.push($(this).text());
		});
		$("#work span").each(function(){
			addObj.work.push($(this).text());
		});
		$("#main span").each(function(){
			addObj.main.push($(this).text());
		});

		if(addObj.home.length==0 && addObj. mobile.length==0 && addObj.work.length==0 && addObj.main.length==0 ){
			$("#mobile").closest("div").find("span").text("Enter valid mobile number");
			saveflg=1;
			return;
		}

		var pluginArr=[];
		//$("#plug input[type=checkbox]").each(function(){
		for(i=0;i<=pluarr.length;i++){
            if($("#"+pluarr[i]).prop('checked')== true){
				pluginArr.push($("#e"+pluarr[i]).val());
			}else{
                pluginArr.push("");
			}
		}
		//});
        addObj.pluginArr=pluginArr.toString();
		if(($("#Sales").prop("checked")== true )||($("#Manager").prop("checked")== true )||($("#CXO").prop("checked")== true)){

			$(".error_mod").hide();
		}else{
			$(".error_mod").show();
			$(".error_mod").closest("div").find("span").text("Please select any one of the designations.");
			saveflg=1;
			return;
		}
		if($("#Sales").is(':checked')){
			addObj.add_sales = $("#Sales").prop('class');

		}else{
			addObj.add_sales="0";
		}
		if($("#Manager").is(':checked')){
			addObj.add_manager = $("#Manager").prop('class');
		}else{
			addObj.add_manager="0";
		}
		if($("#CXO").is(':checked')){
			addObj.add_CXO = $("#CXO").prop('class');
		}else{
			addObj.add_CXO="0";
		}
		if($("#custo_assign").is(':checked')){
			addObj.custo_assign = $("#custo_assign").prop('class');
		}else{

			addObj.custo_assign="0";
		}

        addObj.addMod=addMod;
        addObj.addPlugin =addPlugin;
		addObj.timeZone = $.trim($('#time_zone').val());

		addObj.add_user = $.trim($("#add_user").val());
		addObj.add_role = $.trim($("#add_role").val());

        if($("#Communicator").prop('checked') == true){
            addObj.save_user_email=save_user_email;
        }else{
           addObj.save_user_email=[];
        }

		if(saveflg==0){
			$("#confirm_alert").modal('show');
			p=addObj;
		}

		/* -------------------------------------- */
		var phnNoArray=[];
		var emailArray=[];
		$(".email_cat ul li span").each(function(){
			phnNoArray.push($(this).text().trim());
		})
		$(".contact_cat ul li span").each(function(){
			emailArray.push($(this).text().trim());
		})
		var phnNohtml="";
		var emailhtml="";
		phnNohtml +="<ul style='padding:0px'>"
		for (i=0; i<phnNoArray.length; i++){
			if(i == 0){
				phnNohtml +="<li><label><input type='radio' name='phnNoArray' checked/>"+phnNoArray[i]+"</label></li>";
			}else{
				phnNohtml +="<li><label><input type='radio' name='phnNoArray'/>"+phnNoArray[i]+"</label></li>";
			}

		}
		phnNohtml +="</ul>";

		emailhtml +="<ul style='padding:0px'>"
		for (i=0; i<emailArray.length; i++){
			if(i == 0){
				emailhtml +="<li><label><input type='radio' name='emailArray' checked/>"+emailArray[i]+"</label></li>";
			}else{
				emailhtml +="<li><label><input type='radio' name='emailArray'/>"+emailArray[i]+"</label></li>";
			}

		}
		emailhtml +="</ul>";
		$(".email_list").html('').html(phnNohtml);
		$(".phn_list").html('').html(emailhtml);

	}
/* ----------------------------------------------------- section 2 save button code --------------------------------------- */

function check_spendcalculation(rcur,rcost,ccur,ccost,scur,scost){
	var SpendCalculationChkFlg = true;

	if($("#"+rcur).val()==""){
		$("#"+rcur).closest("div").find("span").text("Please select Resource Currency.");
		SpendCalculationChkFlg = false;
	}else{
		$("#"+rcur).closest("div").find("span").text("");
	}

	if($("#"+rcost).val()==""){
		$("#"+rcost).closest("div").find("span").text("Please select Resource Cost.");
		SpendCalculationChkFlg = false;
	}else if(!cost_chk.test($.trim($("#"+rcost).val()))){
		$("#"+rcost).closest("div").find("span").text("Please enter only numbers.");
		SpendCalculationChkFlg = false;
	}else{
		$("#"+rcost).closest("div").find("span").text("");
	}

	if($("#"+ccur).val()==""){
		$("#"+ccur).closest("div").find("span").text("Please select Call Currency.");
		SpendCalculationChkFlg = false;
	}else{
		$("#"+ccur).closest("div").find("span").text("");
	}

	if($("#"+ccost).val()==""){
		$("#"+ccost).closest("div").find("span").text("Please select Call Cost.");
		SpendCalculationChkFlg = false;
	}else if(!cost_chk.test($.trim($("#"+ccost).val()))){
		$("#"+ccost).closest("div").find("span").text("Please enter only numbers.");
		SpendCalculationChkFlg = false;
	}else{
		$("#"+ccost).closest("div").find("span").text("");
	}

	if($("#"+scur).val()==""){
		$("#"+scur).closest("div").find("span").text("Please select  SMS Currency.");
		SpendCalculationChkFlg = false;
	}else{
		$("#"+scur).closest("div").find("span").text("");
	}

	if($("#"+scost).val()==""){
		$("#"+scost).closest("div").find("span").text("Please select SMS Cost.");
		SpendCalculationChkFlg = false;
	}else if(!cost_chk.test($.trim($("#"+scost).val()))){
		$("#"+scost).closest("div").find("span").text("Please enter only numbers.");
		SpendCalculationChkFlg = false;
	}else{
		$("#"+scost).closest("div").find("span").text("");
	}
	/* -------------this will send back true or false value----------------- */
	if(SpendCalculationChkFlg == false){
		return false;
	}else{
		return true;
	}

}



/* ---------------------------------------------------------------------------------------------------- */
var prodCurrencymain=[];

var flg1=0;
function create_procur_array(){
	var prodCurrencyObj={};
	prodCurrencymain=[];
	var aa1="";
	var aa=[];
	var successFlag =0;
	var flg=0;
	var length2 =0;
	$(".prod_leaf_node input[type=checkbox]").each(function(){
		if($(this).prop("checked") == true){
			var prod=$(this).val();
			var cur=[];
			var length = $(this).closest(".col-md-5").find(".multiselect input[type=checkbox]").length;
			length2 =0;
			if($(this).closest(".col-md-5").find(".multiselect").length > 0){
				$(this).closest(".col-md-5").find(".multiselect input[type=checkbox]").each(function(){

					if($(this).prop("checked")==true){
						cur.push($(this).val());
						if(parseInt(length) != parseInt(length2)){
							/* ----------removed to fixed the issue-----
							$(this).closest(".col-md-5").find(".prod_leaf_node").removeClass("error");
							$(this).closest(".col-md-5").removeAttr("style");
							*/
						}
					}else{
						length2 = length2+1;
						if(parseInt(length) == parseInt(length2)){
							$(this).closest(".col-md-5").find(".prod_leaf_node").addClass("error");
							$(this).closest(".col-md-5").css({"border": "1px solid red"});
						}/* removed to fixed the issue
						else{
							$('.prod_leaf_node').closest(".col-md-5").find(".prod_leaf_node").removeClass("error");
							$('.prod_leaf_node').closest(".col-md-5").removeAttr("style");
						}
						*/
					}
				})
			}else{
				length2 =-1
			}
			if(parseInt(length) == parseInt(length2)){
				successFlag =1;
			}else{
				$(this).closest(".prod_leaf_node").removeClass("error");
				$(this).closest(".col-md-5").removeAttr("style");
			}
			prodCurrencyObj={"prod" :prod,"currency":cur.toString()};
			prodCurrencymain.push(prodCurrencyObj);
		}
	})

	var flgunck =0;
	$(".prod_leaf_node input[type=checkbox]").each(function(){
		if($(this).prop("checked") == true){
			flgunck =1;
		}
	})

	if(flgunck ==0){
		return "selectnone"; /* "need to chk atleast one product" */
	}else if(successFlag ==1){
		return "chkatleastone"; /* "need to chk atleast one currency from selected product" */
	}else{
		return "success"; /* "success" */
	}
}
/* ---------------------------------------------------------------------------------------------------- */
$(document).ready(function(){

	$("#section2save").click(function(){

				$(".error-alert").text("");
				var addObj={};
				addObj.bussLocTarget =[];
				addObj.inductryTarget =[];
				addObj.currency =[];
				$("#add_user_loc_ofc input[type=checkbox]").each(function(){
					if($(this).prop('checked')== true){
						addObj.bussLocTarget.push($(this).val());
					}
				});
				if(addObj.bussLocTarget==0){
					$("#add_use").closest("div").find("span").text("Please select Business location");
					return;
				}else{
					$("#add_use").closest("div").find("span").text("");
				}
				$("#add_user_indu input[type=checkbox]").each(function(){
					if($(this).prop('checked')== true){
						addObj.inductryTarget.push($(this).val());
					}
				});
				if(addObj.inductryTarget==0){
					$("#add_use1").closest("div").find("span").text("Please select any one of Clientele Industries");
					return;
				}else{
					$("#add_use1").closest("div").find("span").text("");
				}
				addObj.add_user = $.trim($("#add_user").val());
				var value=$.trim($("#add_user_team").val());
				var teamid=value.split("<@>");
				addObj.add_team = teamid[0];
				flg =0;

				if(create_procur_array()  == "selectnone"){
						$('#error_edit').text("Please select atleast one product");
						$('.prod_leaf_node').closest(".col-md-5").find(".prod_leaf_node1").addClass("error");
						$('.prod_leaf_node').closest(".col-md-5").css({"border": "1px solid red"});
						return;
				}
				else if(create_procur_array()  == "chkatleastone"){
					$('#error_edit').text("Please select atleast one curreny from selected product(s)");
					return;
				}
				else if(create_procur_array()  == "success"){
					$('#error_edit').text("");
				}

				/* =============================================================== */

				/*if (role_val==0){ */



					addObj.prodCurrency = prodCurrencymain;
				/* =============================================================== */

				if($("#add_user_Hcal").val()==""){
					$("#add_user_Hcal").closest("div").find("span").text("Please select  Holiday calendar.");
					$("#add_user_Hcal").focus();
					return;
				}else{
					$("#add_user_Hcal").closest("div").find("span").text("");
				}
				if($("#call_rec").is(':checked')){
					$("#call_rec").prop('value',1);
				}else{
					$("#call_rec").prop('value',0);
				}
				if($("#accounting").is(':checked')){
					$("#accounting").prop('value',1);
					if(check_spendcalculation('resourceCurrency','resourceCost','callCurrency','callCost','smsCurrency','smsCost') == false){
						return;
					}
				}else{
					$("#accounting").prop('value',0);
				}

				addObj.add_user_Hcal = $.trim($("#add_user_Hcal").val());
				addObj.user_call_rec = $.trim($("#call_rec").val());
				addObj.user_accounting = $.trim($("#accounting").val());
				addObj.resourceCurrency = $.trim($("#resourceCurrency").val());
				addObj.callCurrency = $.trim($("#callCurrency").val());
				addObj.smsCurrency = $.trim($("#smsCurrency").val());
				addObj.resourceCost = $.trim($("#resourceCost").val());
				addObj.callCost = $.trim($("#callCost").val());
				addObj.smsCost = $.trim($("#smsCost").val());
				addObj.add_user = $.trim($("#add_user").val());
				var weekArray = [];
				var jsonArray = [];
				var jsonObj={};
				var checkflag = 0;
				var checkflag1 = 0;
				for(var i=1;i<=7;i++){
					if($('#week'+i).is(':checked')){

						var strtime=$('#strt_time'+i).val();
						var endtime=$('#end_time'+i).val();
						if(strtime.trim()=="" || endtime.trim()==""){
							checkflag1=1;
						}else{
							var show_msg =$('#end_time'+i);
							if(validate_time(strtime,endtime,show_msg) == true){
								return;
							}
							jsonObj=({'day_of_week':$('#week'+i).val(),'start_time':$.trim($('#strt_time'+i).val()),'end_time':$.trim($('#end_time'+i).val())})
							jsonArray.push(jsonObj);
						}
					   checkflag=1;
					}
				}

				if(checkflag==0){
					$("#workdays").closest("div").find("span").text("Please select working days and Working Hours");
					return false;
				}else{
					$("#workdays").closest("div").find("span").text("");
				}
				if(checkflag1==1){
				  $("#workdays").closest("div").find("span").text("Start Time and End Time Cannot be Blank");
				  return false;
				}else{

					$("#workdays").closest("div").find("span").text("");

				}
				addObj.workingdays=jsonArray;
				loaderShow();
                console.log(addObj);
				$.ajax({
						type : "POST",
						url : "<?php echo site_url('admin_userController1/post_data1'); ?>",
						dataType : 'json',
						data : JSON.stringify(addObj),
						cache : false,
						success : function(data){
								loaderHide();
								if(error_handler(data)){
									return;
								}
								$("#addmodal").modal("hide");
								cancel2();
						}
				});
	});

});
/*-----------------------------------------------------------------cancel fields starts---------------------------------------------------*/
	function cancel1(){
		$("#addmodal1").modal("hide");
	}

	function e_cancel1(){
		$("#addmodal2").modal("hide");

	}


/*-------------------------------------------------------------------edit proceed starts here--------------------------------------------------------*/
 function conf_alert(data){
		$("#confirm_alertE").modal('show');
}
	function alert_cancel(){
		$("#confirm_alert").modal('hide');
}

function display_reporting(data){
            $("#active_status_confirm1").modal('show');
            options ="";
            options += '<select class="form-control">';
            options += '<option value="">-Select-</option>';
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
                          if(data.users_rep[uid].length >0){
                               for(var i=0;i<data.users_rep[uid][0].length; i++)
                                {
    	                            options += "<option value='"+data.users_rep[uid][0][i].user_id+"<@>"+data.users_rep[uid][0][i].designation+"'>"+ data.users_rep[uid][0][i].user_name +"--"+data.users_rep[uid][0][i].role_name+"("+data.users_rep[uid][0][i].deptname+")"+"</option>";
                                }
                          }
                          options += '</select>';
                      }
                }
                replacement_table +='<tr class="'+data.users[a]+'"><td '+valign+'>'+(j)+'</td><td '+valign+'>'+data.users[b]+'</td><td '+valign+'>'+data.users[c]+'</td><td '+valign+'>'+data.users[d]+'</td><td '+valign+'>'+options+'</td>';
                u=d;
            }
            replacement_table +='</tbody></table>';
            $("#replacement_table").html("").html(replacement_table);

}

function nextE(){
	$("#confirm_alertE").modal('hide');
	var addObj = p;
    console.log(addObj);
    var len=mgrflg.length;
    var selvar="";
    var selvar1="";
    loaderShow();
    if(selrowflg==0){
          if(len >0){
              if(len==1){
                  addObj1={};
                  addObj1.userid1=addObj.add_user;
                  addObj1.add_role=addObj.add_role;
                  if($("#eManager").prop("checked") == true){
                         selvar="";
      		    }else if($("#eCXO").prop("checked") == true){
                         selvar="";
      		    }else{
      		        selvar="yes";
      		    }
                  if($("#eCXO").prop("checked") == false){
                              selvar1="a";
                            $.ajax({
                    			 type : "POST",
                    			 url : "<?php echo site_url('admin_userController1/check_forcxo'); ?>",
                    			 dataType : 'json',
                    				data : JSON.stringify(addObj1),
                    				cache : false,
                    				success : function(data){
                    				        loaderHide();
          								if(error_handler(data)){
          									return;
          								}
                                          if(data==1){
                                              alert("User with CXO Modules are Found underneath select user. Hence Cannot Modify CXO Module");
                                              $("#eCXO").prop("checked", true);
                                              selvar1="a";
                                              return false;
                                          }else{
                                                  if(selvar=="yes"){
                                                        $.ajax({
                                          			        type : "POST",
                                          			        url : "<?php echo site_url('admin_userController1/choose_replacement'); ?>",
                                          			        dataType : 'json',
                                          				    data : JSON.stringify(addObj1),
                                          				    cache : false,
                                          				    success : function(data){
                                          				        loaderHide();
                                								if(error_handler(data)){
                                									return;
                                								}
                                                                if(data.reporting_users.length >0){
                                                                        if(data.reporting_users[0]=="nouser"){
                                                                            alert("No Reporting User Found. Hence Cannot Edit Modules ");
                                                                            return;
                                                                        }else{
                                                                            display_reporting(data);
                                                                        }
                                                                }else if(data.users[0]=="nouser"){
                                                                        nextE_save();
                                                                }
                                          			        }
                                	                    });

                                                }else{
                                                        nextE_save();
                                                }
                                          }
                    			 }
          	            });
                  }
                  if(selvar1==""){
                          if(selvar=="yes"){
                                $.ajax({
                        			 type : "POST",
                        			 url : "<?php echo site_url('admin_userController1/choose_replacement'); ?>",
                        			 dataType : 'json',
                        			 data : JSON.stringify(addObj1),
                        			 cache : false,
                        			 success : function(data){
                        					 loaderHide();
              								if(error_handler(data)){
              									return;
              								}
                                              if(data.reporting_users.length >0){
                                                      if(data.reporting_users[0]=="nouser"){
                                                          alert("No Reporting User Found. Hence Cannot Edit Modules ");
                                                          return;
                                                      }else{
                                                          display_reporting(data);
                                                      }

                                              }else if(data.users[0]=="nouser"){
                                                      nextE_save();
                                              }
                        			 }
              	            });

                          }else{
                                  nextE_save();
                          }
                  }
              }else if(len==2){
                  addObj1={};
                  addObj1.userid1=addObj.add_user;
                  addObj1.add_role=addObj.add_role;
                  if($("#eCXO").prop("checked") == false){
                         selvar="yes";
      		    }
                  if($("#eManager").prop("checked") == true){
                         selvar="";
      		    }

                  if($("#eCXO").prop("checked") == false){
                              selvar1="a";
                            $.ajax({
                    			 type : "POST",
                    			 url : "<?php echo site_url('admin_userController1/check_forcxo'); ?>",
                    			 dataType : 'json',
                    				data : JSON.stringify(addObj1),
                    				cache : false,
                    				success : function(data){
                    				        loaderHide();
          								if(error_handler(data)){
          									return;
          								}
                                          if(data==1){
                                              alert("User with CXO Modules are Found underneath select user. Hence Cannot uncheck CXO Module");
                                              $("#eCXO").prop("checked", true);
                                              selvar1="a";
                                              return false;
                                          }else{
                                                  if(selvar=="yes"){
                                                        $.ajax({
                                          			        type : "POST",
                                          			        url : "<?php echo site_url('admin_userController1/choose_replacement'); ?>",
                                          			        dataType : 'json',
                                          				    data : JSON.stringify(addObj1),
                                          				    cache : false,
                                          				    success : function(data){
                                          				        loaderHide();
                                								if(error_handler(data)){
                                									return;
                                								}
                                                                if(data.reporting_users.length >0){
                                                                        if(data.reporting_users[0]=="nouser"){
                                                                            alert("No Reporting User Found. Hence Cannot Edit Modules ");
                                                                            return;
                                                                        }else{
                                                                            display_reporting(data);
                                                                        }
                                                                }else if(data.users[0]=="nouser"){
                                                                        nextE_save();
                                                                }
                                          			        }
                                	                    });

                                                }else{
                                                        nextE_save();
                                                }
                                          }
                    			 }
          	            });
                  }
              if(selvar1==""){
                      if(selvar=="yes"){
                              $.ajax({
                			        type : "POST",
                			        url : "<?php echo site_url('admin_userController1/choose_replacement'); ?>",
                			        dataType : 'json',
                				    data : JSON.stringify(addObj1),
                				    cache : false,
                				    success : function(data){
                				        loaderHide();
      								if(error_handler(data)){
      									return;
      								}
                                      if(data.reporting_users.length >0){
                                              if(data.reporting_users[0]=="nouser"){
                                                  alert("No Reporting User Found. Hence Cannot Edit Modules ");
                                                  return;
                                              }else{
                                                  display_reporting(data);
                                              }
                                      }else if(data.users[0]=="nouser"){
                                              nextE_save();
                                      }
                			        }
      	                    });

                      }else{
                              nextE_save();
                      }
                  }
              }
          }else{
                  nextE_save();
          }
    }else if(selrowflg==1){
          var selmod=0;
          if(addObj.add_CXO=="0" && addObj.add_manager=="0" && addObj.add_sales!="0"){
              selmod=1;
          }
          var jsonobj={};
          jsonobj.selmod=selmod;
          jsonobj.userid1=addObj.add_user;
          jsonobj.add_role=addObj.add_role;
          $.ajax({
    	        type : "POST",
    	        url : "<?php echo site_url('admin_userController1/replacement_rolechange'); ?>",
    	        dataType : 'json',
    		    data : JSON.stringify(jsonobj),
    		    cache : false,
    		    success : function(data){
    		        loaderHide();
                    if(error_handler(data)){
                        return;
                    }
                    if(data.reporting_users.length >0){
                            display_reporting(data);
                    }else{
                        nextE_save();
                    }

    	        }
         });


    }     /*alert(len);
    if(len==0){

    }*/

}
function nextE_save(){
     var addObj = p;
     addObj.rolval = role_val;
     console.log(addObj);
     $.ajax({
              			 type : "POST",
              			 url : "<?php echo site_url('admin_userController1/updata_section1data'); ?>",
              			 dataType : 'json',
              				data : JSON.stringify(addObj),
              				cache : false,
              				success : function(data){
              					 load();
              					 loaderHide();
              					 if(error_handler(data)){
              						return;
              					}
              					 var userID = data;
              					 $('#loadingmessage').hide();
              					 $("#add_user").val(userID);
              					 $("#sec_from").val("edit");
              					 $(".e_section1").hide();
              					 $(".e_section2").show();
              			 }
     });
}
function activesubmit1(){
                    var addObj ={};
                    var addObj1 ={};
                    addObj1 = p;
                    var rep_arr =[];
                    var flgChk= 0;
                      $("#replacement_table table tbody tr").each(function(){
                          if($(this).find('select').val() == ""){
                              $(this).find('select').closest('td').find('.error-alert').remove();
                                $(this).find('select').closest('td').append('<span class="error-alert">Select Reporting User</span>')
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

                       return;
                    }
                    addObj.rep_arr=rep_arr;
                    addObj.olduserID=$('#add_user').val();
                    addObj.type="";
                    console.log(addObj);
                    $.ajax({
                  				type : "POST",
                  				url : "<?php echo site_url('admin_userController1/update_reportingdata'); ?>",
                  				dataType : 'json',
                                data: JSON.stringify(addObj),
                  				cache : false,
                  				success : function(data){
      								//loaderHide();
      								if(error_handler(data)){
      									return;
      								}
                                    $("#active_status_confirm1").modal('hide');
                                      $.ajax({
                                			 type : "POST",
                                			 url : "<?php echo site_url('admin_userController1/updata_section1data'); ?>",
                                			 dataType : 'json',
                                				data : JSON.stringify(addObj1),
                                				cache : false,
                                				success : function(data){
                                					 load();
                                					 loaderHide();
                                					 if(error_handler(data)){
                                						return;
                                					 }
                                					 var userID = data;
                                					 $('#loadingmessage').hide();
                                					 $("#add_user").val(userID);
                                					 $("#sec_from").val("edit");
                                					 $(".e_section1").hide();
                                					 $(".e_section2").show();
                                			 }
                                	});
                  				}
                    });

}
function activecancel1(){
	            window.location.reload(true);
}
function add_ds(){
        var aa= $("#edit_user_desg").val();
		if($("#edit_user_desg").val()==""){
			$("#edit_user_desg").closest("div").find("span").text("Please select designation.");
			$("#edit_user_desg").focus();
			return;
		}else{
			$("#edit_user_desg").closest("div").find("span").text("");

		}
        if($("#edit_desg_name").val()==""){
				    $("#edit_desg_name").closest("div").find("span").text("Reporting is required.");
				    $("#edit_desg_name").focus();
                    return;
        }
        else{
			$("#edit_desg_name").closest("div").find("span").text("");

		}
		var des = $("#edit_desg_name option:selected").text();
		$("#addmodal2").modal("hide");
		$("#edit_rep_into").val(des);

}
function e_proceed(){
		$(".error-alert").text("");
		var saveflg=0;
		if($.trim($("#edit_name").val())==""){
			$("#edit_name").closest("div").find("span").text("Enter user name .");
			$("#edit_name").focus();
			saveflg=1;
			return;
		}else if(!text_chk.test($.trim($("#edit_name").val()))){
			$("#edit_name").closest("div").find("span").text("Enter only characters");
			$("#edit_name").focus();
			saveflg=1;
			return;
		}else{
			$("#edit_name").closest("div").find("span").text("");
		}
		if(!text_chk.test($.trim($("#edit_name1").val()))){
			$("#edit_name1").closest("div").find("span").text("Enter only characters");
			$("#edit_name1").focus();
			saveflg=1;
			return;
		}else{
			$("#edit_name1").closest("div").find("span").text("");
		}
		if($.trim($("#edit_user_eId").val())==""){
			$("#edit_user_eId").closest("div").find("span").text("Enter valid Employee ID.");
			$("#edit_user_eId").focus();
			saveflg=1;
			return;
		}else if(!emp_chk.test($.trim($("#edit_user_eId").val()))){
			$("#edit_user_eId").closest("div").find("span").text("Enter only characters");
			$("#edit_user_eId").focus();
			saveflg=1;
			return;
		}else{
			$("#edit_user_eId").closest("div").find("span").text("");
		}
		$(".error-alert").text("");
		var date_str=$("#edit_DOB").val();
		var today_date = moment();
		var diff = today_date.diff(date_str, 'years');

		if(diff < 18){
			$(".sibling1").closest("div").find(".error-alert").text("Should be atleast 18 years or above.");
			$(".sibling1").focus();
			saveflg=1;
			return;
		}else{
			$(".sibling1").closest("div").find(".error-alert").text("");
		}
		if($("#edit_gender").val()==""){
			$("#edit_gender").closest("div").find("span").text("Select gender.");
			$("#edit_gender").focus();
			saveflg=1;
			return;
		}else{
			$("#edit_gender").closest("div").find("span").text("");
		}
		if($.trim($("#edit_user_dep").val())==""){
			$("#edit_user_dep").closest("div").find("span").text("Please select department.");
			$("#edit_user_dep").focus();
			saveflg=1;
			return;
		}else{
			$("#edit_user_dep").closest("div").find("span").text("");
		}
		if($("#edit_user_role").val()==""){
			$("#edit_user_role").closest("div").find("span").text("Please select role.");
			$("#edit_user_role").focus();
			saveflg=1;
			return;
		}else{
			$("#edit_user_role").closest("div").find("span").text("");
		}
		if(role_val==0){
			if($("#edit_rep_into").val()==""){
				$("#edit_rep_into").closest("div").find("span").text("Reporting is required.");
				$("#edit_rep_into").focus();
				saveflg=1;
				return;
			}else{
				$("#edit_rep_into").closest("div").find("span").text("");
                if($("#edit_desg_name").val()==""){
				    $("#edit_rep_into").closest("div").find("span").text("Reporting is required.");
				    $("#edit_rep_into").focus();
                    saveflg=1;
                    return;
                }

			}
			if($("#edit_user_desg").val()==""){
				$("#edit_user_desg").closest("div").find("span").text("Please select designation.");
				$("#edit_user_desg").focus();
				saveflg=1;
				return;
			}else{
				$("#edit_user_desg").closest("div").find("span").text("");
			}

		}else{
            $("#edit_rep_into").val("");
		}
		if($("#edit_user_team").val()==""){
			$("#edit_user_team").closest("div").find("span").text("Please select Team.");
			$("#edit_user_team").focus();
			saveflg=1;
			return;
		}else{
			$("#edit_user_team").closest("div").find("span").text("");
		}

		var addObj={};

		addObj.OfcLocTarget =[];
		addObj.selltype =[];
		addObj.work1=[];
		addObj.personal=[];
		addObj.mobile=[];
		addObj.home=[];
		addObj.work=[];
		addObj.main=[];
		addObj.add_user_dep = $.trim($("#edit_user_dep").val());
		addObj.add_user_role  = $.trim($("#edit_user_role").val());
		addObj.add_sales_persona  = $.trim($("#edit_sales_persona").val());
		if($("#edit_rep_into").val()==""){
			addObj.add_rep_into = $.trim($("#reportingto_desg").val());
		}else{
			var value=$.trim($("#edit_desg_name").val());
			var add_rep_into=value.split("<@>");
			addObj.add_rep_into =add_rep_into[0];
		}
        if($("#edit_user_desg").val()!== 'undefined'){
                addObj.add_rept_desg = $.trim($("#edit_user_desg").val());
		}else{
                $("#edit_user_desg").closest("div").find("span").text("Please select designation.");
				$("#edit_user_desg").focus();
				saveflg=1;
				return;
		}

		var value=$.trim($("#edit_user_team").val());
		addObj.add_user_team =value;
		addObj.add_user_region = $.trim($("#add_user_region").val());
		addObj.add_user = $.trim($("#add_user").val());
		addObj.ofc_address = $.trim($("#e_res_address").val());
		$("#edit_user_loc input[type=checkbox]").each(function(){
		   if($(this).prop('checked')== true){
				addObj.OfcLocTarget.push($(this).val());

		   }
		});
		$("#edit_sellType input[type=checkbox]").each(function(){
		   if($(this).prop('checked')== true){
				addObj.selltype.push($(this).val());

		   }
		});
		$("#work_email_edit span").each(function(){
			addObj.work1.push($(this).text());
		});
		$("#email_personal_edit span").each(function(){
			addObj.personal.push($(this).text());
		});
		 if(addObj.work1.length==0 && addObj. personal.length==0){
			$("#edit_user_email").closest("div").find("span").text("Enter valid Email ID.");
			saveflg=1;
			return;
		}
		if(addObj.OfcLocTarget.length==0){
			$("#edit_user_loc").closest("div").find("span").text("Please select office location.");
			saveflg=1;
			return;
		}else{
			$("#edit_user_loc").closest("div").find("span").text("");
		}

		if(addObj.selltype.length==0){
			$("#edit_sellType").closest("div").find("span").text("Please select Type of Sell.");
			saveflg=1;
			return;
		}else{
			$("#edit_sellType").closest("div").find("span").text("");
		}


		addObj.user_name = $.trim($("#edit_name").val());
		addObj.last_name = $.trim($("#edit_name1").val());
		addObj.user_dob  = $.trim($("#edit_DOB").val());
		addObj.user_gender = $.trim($("#edit_gender").val());
		addObj.user_resadd = $.trim($("#e_res_address").val());
        flg=0;
        if($.trim($("#edit_user_eId").val())==employeeid){
      		        addObj.add_user_eId = $.trim($("#edit_user_eId").val());
                    flg=1;
        }
        if(flg==0){
            for(i=0;i<eid.length;i++){
                if($.trim($("#edit_user_eId").val())!=eid[i]){
                    addObj.add_user_eId = $.trim($("#edit_user_eId").val());
  				}else{
  						$("#edit_user_eId").closest("div").find("span").text("Already exists");
                        return;
  				}
            }
        }

        /*for(i=0;i<eid.length;i++){
				if(employeeid==eid[i]){
					addObj.add_user_eId = $.trim($("#edit_user_eId").val());
				}else if($("#edit_user_eId").val()!=eid[i]){
					addObj.add_user_eId = $.trim($("#edit_user_eId").val());
				}else{
					$("#edit_user_eId").closest("div").find("span").text("Already exists");
					return;
				}
		}*/

		$("#home_edit span").each(function(){
			addObj.home.push($(this).text());
		});
		$("#mobile1_edit span").each(function(){
			addObj.mobile.push($(this).text());
		});
		$("#work_edit span").each(function(){
			addObj.work.push($(this).text());
		});
		$("#main_edit span").each(function(){
			addObj.main.push($(this).text());
		});

		if(addObj.home.length==0 && addObj. mobile.length==0 && addObj.work.length==0 && addObj.main.length==0 ){
			$("#mob_edit").closest("div").find("span").text("Enter valid mobile number");
			saveflg=1;
			return;
		}

		var pluginArr=[];

		//$("#plug1 input[type=checkbox]").each(function(){
		for(i=0;i<=pluarr.length;i++){
            if($("#e"+pluarr[i]).prop('checked')== true){
				pluginArr.push($("#e"+pluarr[i]).val());
			}else{
                pluginArr.push("");
			}
		}
		//});
        addObj.pluginArr=pluginArr.toString();
		if(($("#eSales").prop("checked")== true )||($("#eManager").prop("checked")== true )||($("#eCXO").prop("checked")== true)){

			$(".error_mod1").hide();
		}else{
			$(".error_mod1").show();
			$(".error_mod1").closest("div").find("span").text("Please select any one of the designations.");
			saveflg=1;
			return;
		}
		if($("#eSales").is(':checked')){
			addObj.add_sales = $("#eSales").prop('class');

		}else{
			addObj.add_sales="0";
		}
		if($("#eManager").is(':checked')){
			addObj.add_manager = $("#eManager").prop('class');
		}else{
			addObj.add_manager="0";
		}
		if($("#eCXO").is(':checked')){
			addObj.add_CXO = $("#eCXO").prop('class');
		}else{
			addObj.add_CXO="0";
		}
		if($("#e_custo_assign").is(':checked')){
			addObj.custo_assign = $("#e_custo_assign").prop('class');
		}else{

			addObj.custo_assign="0";
		}
        var chkmodl=$("#rolval").val();
        if(parseInt(chkmodl)==1 && $("#eSales").is(':checked') && ($("#eManager").prop('checked')==false) && $("#eCXO").prop('checked')==false){
            $(".error_mod1").show();
  			$(".error_mod1").closest("div").find("span").text("Please select One more Designation Along with Executive Module.");
  			saveflg=1;
  			return;
        }else{
            $(".error_mod1").hide();
        }

        addObj.addMod=addMod;
        addObj.addPlugin =addPlugin;
		addObj.timeZone = $.trim($('#time_zoneE').val());
		addObj.add_role = $.trim($("#edit_user_role").val());

        if($("#eCommunicator").prop('checked') == true){
            addObj.save_user_email=save_user_email;
        }else{
           addObj.save_user_email=[];
        }
		if(saveflg==0){
			//$("#confirm_alertE").modal('show');
			p=addObj;
		}
        console.log(addObj);
		sendNotificationHtml(".email_cat_edit", ".contact_cat1")
}

function sendNotificationHtml(email_list, mobile_list){
		var phnNoArray=[];
		var emailArray=[];
		$( mobile_list+" ul li span").each(function(){
			phnNoArray.push($(this).text().trim());
		})
		$( email_list+" ul li span").each(function(){
			emailArray.push($(this).text().trim());
		})
		var phnNohtml="";
		var emailhtml="";
		phnNohtml +="<ul style='padding:0px'>"
		for (i=0; i<phnNoArray.length; i++){
			if(savedUserRecord.user_primary_mobile == phnNoArray[i] ){
				phnNohtml +="<li><label><input onchange='changePrimaryContact(\"phn\" ,\""+phnNoArray[i]+"\")' type='radio' name='phnNoArray' checked/>&nbsp;"+phnNoArray[i]+"</label></li>";
				p["user_primary_mobile"] = savedUserRecord.user_primary_mobile;
			}else if(i == 0){
				phnNohtml +="<li><label><input onchange='changePrimaryContact(\"phn\" ,\""+phnNoArray[i]+"\")' type='radio' name='phnNoArray' checked/>&nbsp;"+phnNoArray[i]+"</label></li>";
				p["user_primary_mobile"] = phnNoArray[i];
			}else{
				phnNohtml +="<li><label><input onchange='changePrimaryContact(\"phn\" ,\""+phnNoArray[i]+"\")' type='radio' name='phnNoArray'/>&nbsp;"+phnNoArray[i]+"</label></li>";
			}

		}
		phnNohtml +="</ul>";

		emailhtml +="<ul style='padding:0px'>"
		for (i=0; i<emailArray.length; i++){

			if(savedUserRecord.user_primary_email == emailArray[i]){
				emailhtml +="<li><label><input onchange='changePrimaryContact(\"email\" ,\""+emailArray[i]+"\")' type='radio' name='emailArray' checked/>&nbsp;"+emailArray[i]+"</label></li>";
				p["user_primary_email"] = savedUserRecord.user_primary_email;
			}else if(i == 0){
				emailhtml +="<li><label><input onchange='changePrimaryContact(\"email\" ,\""+emailArray[i]+"\")' type='radio' name='emailArray' checked/>&nbsp;"+emailArray[i]+"</label></li>";
				p["user_primary_email"] = emailArray[i];
			}else{
				emailhtml +="<li><label><input onchange='changePrimaryContact(\"email\" ,\""+emailArray[i]+"\")' type='radio' name='emailArray'/>&nbsp;"+emailArray[i]+"</label></li>";
			}

		}
		emailhtml +="</ul>";
		$(".email_list_E").html('').html(phnNohtml);
		$(".phn_list_E").html('').html(emailhtml);
        if(emailArray.length == 1 && phnNoArray.length == 1){
            nextE();
        }else{
            $("#confirm_alertE").modal('show');
        }
}
function changePrimaryContact(type, contact){
	if(type == "email"){
		p["user_primary_email"] = contact
	}
	if(type == "phn"){
		p["user_primary_mobile"] = contact
	}
}



function alert_cancelE(){
       $("#confirm_alertE").modal('hide');
}


function e_proceed2(){

				$(".error-alert").text("");
				var addObj={};
				addObj.bussLocTarget =[];
				addObj.inductryTarget =[];
				addObj.currency =[];
				$("#edit_user_floc input[type=checkbox]").each(function(){
					if($(this).prop('checked')== true){
						addObj.bussLocTarget.push($(this).val());
					}
				});
				if(addObj.bussLocTarget==0){
					$("#edit_user_bus").closest("div").find("span").text("please select Business location");
					return;
				}else{
					$("#edit_user_bus").closest("div").find("span").text("");
				}
				$("#edit_user_indu input[type=checkbox]").each(function(){
					if($(this).prop('checked')== true){
						addObj.inductryTarget.push($(this).val());
					}
				});
				if(addObj.inductryTarget==0){
					$("#add_use_ind").closest("div").find("span").text("Please select any one of Clientele Industries");
					return;
				}else{
					$("#add_use_ind").closest("div").find("span").text("");
				}
				addObj.add_user = $.trim($("#add_user").val());
				var value=$.trim($("#edit_user_team").val());
				var teamid=value.split("<@>");
				addObj.add_team = teamid[0];

				if(create_procur_array()  == "selectnone"){
						$('#error_add ,#error').text("Please select atleast one product");
						$('.prod_leaf_node').closest(".col-md-5").find(".prod_leaf_node1").addClass("error");
						$('.prod_leaf_node').closest(".col-md-5").css({"border": "1px solid red"});
						return;
				}
				else if(create_procur_array()  == "chkatleastone"){
					$('#error_add ,#error').text("Please select atleast one curreny from selected product(s)");
					return;
				}
				else if(create_procur_array()  == "success"){
					$('#error').text("");
				}

				/* =============================================================== */

					addObj.prodCurrency = prodCurrencymain;
				/* =============================================================== */

				if($("#edit_user_Hcal").val()==""){
					$("#edit_user_Hcal").closest("div").find("span").text("Please select  Holiday calendar.");
					$("#edit_user_Hcal").focus();
					return;
				}else{
					$("#edit_user_Hcal").closest("div").find("span").text("");
				}
				if($("#edit_call_rec").is(':checked')){
					$("#edit_call_rec").prop('value',1);
				}else{
					$("#edit_call_rec").prop('value',0);
				}
				if($("#e_accounting").is(':checked')){
					$("#e_accounting").prop('value',1);
					if(check_spendcalculation('e_resourceCurrency','e_resourceCost','e_callCurrency','e_callCost','e_smsCurrency','e_smsCost') == false){
						return;
					}
				}else{
					$("#e_accounting").prop('value',0);
				}

				addObj.add_user_Hcal = $.trim($("#edit_user_Hcal").val());
				addObj.user_call_rec = $.trim($("#edit_call_rec").val());
				addObj.user_accounting = $.trim($("#e_accounting").val());
				addObj.resourceCurrency = $.trim($("#e_resourceCurrency").val());
				addObj.callCurrency = $.trim($("#e_callCurrency").val());
				addObj.smsCurrency = $.trim($("#e_smsCurrency").val());
				addObj.resourceCost = $.trim($("#e_resourceCost").val());
				addObj.callCost = $.trim($("#e_callCost").val());
				addObj.smsCost = $.trim($("#e_smsCost").val());


				var weekArray = [];
				var jsonArray = [];
				var jsonObj={};
				var checkflag = 0;
				var checkflag1 = 0;
				for(var i=1;i<=7;i++){
					if($('#e_week'+i).is(':checked')){

						var strtime=$('#e_strt_time'+i).val();
						var endtime=$('#e_end_time'+i).val();
						if(strtime.trim()=="" || endtime.trim()==""){
							checkflag1=1;
						}else{
							var show_msg =$('#e_end_time'+i);
							if(validate_time(strtime,endtime,show_msg) == true){
								return;
							}
							jsonObj=({'day_of_week':$('#e_week'+i).val(),'start_time':$.trim($('#e_strt_time'+i).val()),'end_time':$.trim($('#e_end_time'+i).val())})
							jsonArray.push(jsonObj);
						}
					   checkflag=1;
					}
				}

				if(checkflag==0){
					$("#e_workdays span").text("Please select working  days and working  hours");
					return;
				}else{
					$("#e_workdays span").text("");
				}
				if(checkflag1==1){
				  $("#e_workdays span").text("Start Time and End Time Cannot be Blank");
				  return;
				}else{
					$("#e_workdays span").text("");

				}
				addObj.workingdays=jsonArray;
                loaderShow();
				$.ajax({
						type : "POST",
						url : "<?php echo site_url('admin_userController1/updata_section2data'); ?>",
						dataType : 'json',
						data : JSON.stringify(addObj),
						cache : false,
						success : function(data){
								loaderHide();
								if(error_handler(data)){
									return;
								}
								edit_cancel2();
						}
				});
	}


/*-------------------------start time should be less than end time Validation---------------------------------------------------*/

function validate_time(strtime,endtime,section){
	var strtime_split= 	strtime.split(":");
	var strtime_H =  strtime_split[0];
	var strtime_M =  strtime_split[1];
	/* -------------------- */
	var endtime_Split = endtime.split(":");
	var endtime_H = endtime_Split[0];
	var endtime_M = endtime_Split[1];

	if(strtime_H == endtime_H){
		if(strtime_M >= endtime_M){
			/* alert("start time should be less than end time Min"); */
			section.closest('div').addClass("has-error");
			section.focus();
			$("#e_workdays span").text("start time should be less than End time - Minutes");
			$("#workdays span").text("start time should be less than End time - Minutes");
			return true;
		}else{
			section.closest('div').removeClass("has-error");
			$("#e_workdays span").text("");
			$("#workdays span").text("");
			return false;
		}
	}else if(strtime_H > endtime_H){
		/* alert("start time should be less than end time Hour"); */
		section.closest('div').addClass("has-error");
		section.focus();
		$("#e_workdays span").text("Start time should be less than End time - Hour");
		$("#workdays span").text("Start time should be less than End time - Hour");
		return true;
	}else{
		section.closest('div').removeClass("has-error");
		$("#e_workdays span").text("");
		$("#workdays span").text("");
		return false;
	}
}
/*-------------------------edit proceed functions ends---------------------------------------------------*/
				function checkAllDays() {
					if($("#select_all_days").is(":checked")){
						$('#e_working_days input[type=checkbox]').prop('checked',true);
						$('#e_working_days input[type=text]').prop('disabled',false);
						$('#e_working_days .row .col-md-2 input[name=e_strt_time]').val($("#e_strt_time1").val());
						$('#e_working_days .row .col-md-2 input[name=e_end_time]').val($("#e_end_time1").val());
					}else{
						$('#e_working_days input[type=checkbox]').prop('checked',false);
                        $('#e_working_days input[type=text]').prop('disabled',true);
						remove2();
					}
				}
				function checkAllDays1() {
					if($("#select_all_days1").is(":checked")){
						$('#working_days input[type=checkbox]').prop('checked',true);
                        $('#working_days input[type=text]').prop('disabled',false);
						$('#working_days .row .col-md-2 input[name=strt_time]').val($("#strt_time1").val());
						$('#working_days .row .col-md-2 input[name=end_time]').val($("#end_time1").val());
					}else{
						$('#working_days input[type=checkbox]').prop('checked',false);
                        $('#working_days input[type=text]').prop('disabled',true);
						remove1();
					}
				}
				function e_check(){
					var weekArray = [];
					$("#e_working_days input[type=checkbox]").each(function(){
						   if($(this).prop('checked')== true){
								weekArray.push($(this).val());
						   }
						});
					if(weekArray.length==8){
						$('#e_working_days .row .col-md-2 input[name=e_strt_time]').val($("#e_strt_time1").val());
						$('#e_working_days .row .col-md-2 input[name=e_end_time]').val($("#e_end_time1").val());
					}
				}
				function e_check1(){
					var weekArray = [];
					$("#e_working_days input[type=checkbox]").each(function(){
						   if($(this).prop('checked')== true){
								weekArray.push($(this).val());
						   }
						});
					if(weekArray.length==8){
						$('#e_working_days .row .col-md-2 input[name=e_strt_time]').val($("#e_strt_time1").val());
						$('#e_working_days .row .col-md-2 input[name=e_end_time]').val($("#e_end_time1").val());
					}
				}
				function check(){
					var weekArray = [];
					$("#working_days input[type=checkbox]").each(function(){
						   if($(this).prop('checked')== true){
								weekArray.push($(this).val());
						   }
						});
					if(weekArray.length==8){
						$('#working_days .row .col-md-2 input[name=strt_time]').val($("#strt_time1").val());
						$('#working_days .row .col-md-2 input[name=end_time]').val($("#end_time1").val());
					}
				}
				function check1(){
					var weekArray = [];
					$("#working_days input[type=checkbox]").each(function(){
						   if($(this).prop('checked')== true){
								weekArray.push($(this).val());
						   }
						});
					if(weekArray.length==8){
						$('#working_days .row .col-md-2 input[name=e_strt_time]').val($("#strt_time1").val());
						$('#working_days .row .col-md-2 input[name=e_end_time]').val($("#end_time1").val());
					}
				}
		function remove2(){
		   if($("#e_week1").prop('checked')== false){
				$('#e_strt_time1').data("DateTimePicker").clear()
				$('#e_end_time1').data("DateTimePicker").clear()
		   }if($("#e_week2").prop('checked')== false){
				$('#e_strt_time2').data("DateTimePicker").clear()
				$('#e_end_time2').data("DateTimePicker").clear()
		   }if($("#e_week3").prop('checked')== false){
				$('#e_strt_time3').data("DateTimePicker").clear()
				$('#e_end_time3').data("DateTimePicker").clear()
		   }if($("#e_week4").prop('checked')== false){
				$('#e_strt_time4').data("DateTimePicker").clear()
				$('#e_end_time4').data("DateTimePicker").clear()
		   }if($("#e_week5").prop('checked')== false){
				$('#e_strt_time5').data("DateTimePicker").clear()
				$('#e_end_time5').data("DateTimePicker").clear()
		   }if($("#e_week6").prop('checked')== false){
				$('#e_strt_time6').data("DateTimePicker").clear()
				$('#e_end_time6').data("DateTimePicker").clear()
		   }if($("#e_week7").prop('checked')== false){
				$('#e_strt_time7').data("DateTimePicker").clear()
				$('#e_end_time7').data("DateTimePicker").clear()
		   }
		}
		function remove1(){
		   if($("#week1").prop('checked')== false){
				$('#strt_time1').data("DateTimePicker").clear()
				$('#end_time1').data("DateTimePicker").clear()
		   }if($("#week2").prop('checked')== false){
				$('#strt_time2').data("DateTimePicker").clear()
				$('#end_time2').data("DateTimePicker").clear()
		   }if($("#week3").prop('checked')== false){
				$('#strt_time3').data("DateTimePicker").clear()
				$('#end_time3').data("DateTimePicker").clear()
		   }if($("#week4").prop('checked')== false){
				$('#strt_time4').data("DateTimePicker").clear()
				$('#end_time4').data("DateTimePicker").clear()
		   }if($("#week5").prop('checked')== false){
				$('#strt_time5').data("DateTimePicker").clear()
				$('#end_time5').data("DateTimePicker").clear()
		   }if($("#week6").prop('checked')== false){
				$('#strt_time6').data("DateTimePicker").clear()
				$('#end_time6').data("DateTimePicker").clear()
		   }if($("#week7").prop('checked')== false){
				$('#strt_time7').data("DateTimePicker").clear()
				$('#end_time7').data("DateTimePicker").clear()
		   }
		}

        function grup_email(state){
            /*if(state == 'edit'){
                 savedGrpMail_fun();
            }

            $('#grp_mail_state').val(state);
            $('#grp_mail').modal('show'); show when communicator plugin is enable */

        }


        function save_user_email1(){
               save_user_email=[];
               $('#ListDislayAdd li').each(function(){
                  save_user_email.push({'user': $.trim($(this).find('span').attr('class')),
                                        'permisi': $.trim($(this).find('b').text()),
                                         'name': $.trim($(this).find('span').text())
                                        })
               })
               if(save_user_email.length <= 0){
                  $("#ListDislayAdd").siblings(".error-alert").text("Add group mails.");
                  return;
               }else{
                  $("#ListDislayAdd").siblings(".error-alert").text("");
               }
               grp_mailcancel();
        }

    	function grp_mailcancel(){
    	  var html='';
    	  for(i=0;i<save_user_email.length; i++){

            if(i==0){
               html +='<span>'+save_user_email[i].name+'</span>'
            }else{
              html +='<span>,'+save_user_email[i].name+'</span>'
            }
    	  }
          if( $.trim($('#grp_mail_state').val()) == 'add'){
              //$('#grp_emal_disp').html('').html(html); //show when communicator plugin is enable
          }
          if( $.trim($('#grp_mail_state').val()) == 'edit'){
              //$('#grp_emal_disp_edit').html('').html(html); //show when communicator plugin is enable
          }

           $('#grp_mail').modal('hide');
           $("#ListDislayAdd").html("");
           $("#useridList").val("");
    	}
    	function add_e_list(){
			var hVal = $.trim($("#useridList").val());
            var permission=""
			$(".error-alert").text("")
            var hName = $("#useridList option:selected").text();

			if(hName == ""){
				$("#useridList").closest("div").find("span").text("user id List is required");
				$("#useridList").focus();
				return;
			}else{
			    $("#useridList").closest("div").find("span").text("");
			}
            if(($('#read_write').prop('checked')== false) && ($('#readonly').prop('checked')== false)){
                 $("#read_write").closest(".row").find("span.error-alert").text("Please select any one option.");
                 return;
            }else{
                $("#read_write").closest(".row").find("span.error-alert").text(" ");
                if($('#read_write').prop('checked')== true){
                     permission ="read_write"
                }
                if($('#readonly').prop('checked')== true){
                     permission ="readonly"
                }
            }

			var html= 0;
			if($("#ListDislayAdd li").length <= 0){
				$("#ListDislayAdd").append('<li><span class="'+hVal+'">'+ hName +'</span> (<b>'+permission+'</b>)<a title="Delete" class="pull-right glyphicon glyphicon-remove-circle"></a></li>');
				$("#ListDislayAdd").siblings(".error-alert").text("");
			}else{
				$("#ListDislayAdd li").each(function(){
                    if($(this).find('span').text().trim() == hName.trim()){
                        html = 2;
                    }
				});

				if(html == 1){
					$("#ListDislayAdd").siblings(".error-alert").text("Duplicate Name.");
					$("#add_holiday").focus();
					return;
				}else if(html == 2){
					$("#ListDislayAdd").siblings(".error-alert").text("Duplicate Data.");
					return;
				}else{
					$("#ListDislayAdd").siblings(".error-alert").text("");
					$("#ListDislayAdd").append('<li><span class="'+hVal+'">'+ hName +'</span> (<b>'+permission+'</b>)<a title="Delete" class="pull-right glyphicon glyphicon-remove-circle"></a></li>');
				}
			}
			$("#ListDislayAdd li a").each(function(){
				$(this).click(function(){
					$(this).closest('li').remove();
				})
			});
		}


	</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini userpage" >
    <input type="hidden" id="sec_from" />
    <input type="hidden" id="rolval" />
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

					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div data-toggle="tooltip" title="Hooray!" >
								<img src="<?php echo base_url()?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url()?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url()?>images/new/i_off.png'" alt="info" width="30" height="30" data-toggle="tooltip" data-placement="right" title="User List"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Users', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
							<h2 >User List</h2>
					</div>
					<div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
						<div class="addBtns">
							<a  class="addPlus" id='useradbtn' onclick="compose();"><img src="<?php echo base_url()?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url()?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url()?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
							<div style="clear:both"></div>
						</div>
					</div>
					<div style="clear:both"></div>
				</div>


				<div class="container-fluid">

					<ul class="nav nav-tabs">
						<li class="active" ><a data-toggle="tab" href="#ActiveUsers">Active Users</a></li>
						<li><a data-toggle="tab" href="#InactiveUsers">Inactive Users</a></li>
					</ul>
					<div class="tab-content tab_countstat">
						<div id="ActiveUsers" class="tab-pane fade in active" >
							<table class="table" >
								<thead>
									<tr>
										<th class="table_header" >SL No</th>
										<th class="table_header" >Name</th>
										<th class="table_header" >Department</th>
										<th class="table_header" >Role</th>
										<th class="table_header" >Reporting Into</th>
										<th class="table_header" >Team</th>
										<th class="table_header" ></th>
										<th class="table_header" ></th>
										<th class="table_header" ></th>

									</tr>
								</thead>
								<tbody id="tablebody">
								</tbody>
							</table>
						</div>
						<div id="InactiveUsers" class="tab-pane fade">
							<table class="table" >
								<thead>
									<tr>
										<th class="table_header" >SL No</th>
										<th class="table_header" >Name</th>
										<th class="table_header" >Department</th>
										<th class="table_header" >Role</th>
										<th class="table_header" >Reporting Into</th>
										<th class="table_header" >Team</th>
										<th class="table_header" ></th>
										<th class="table_header" ></th>
										<th class="table_header" ></th>

									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
            <div id="grp_mail" class="modal fade"  data-backdrop="static" data-keyboard="false" style="z-index: 10000000;">
                <div class="modal-dialog">
					<div class="modal-content">
							<div class="modal-header">
								 <span class="close" onclick="grp_mailcancel()">x</span>
								 <h4 class="modal-title">Group email</h4>
							</div>
							<div class="modal-body">
                                <input type='hidden' id='grp_mail_state'/>
                               	<div class="row">
    								<div class="col-md-3">
    									<label for="add_name">Email ID*</label>
    								</div>
    								<div class="col-md-8">
    									<select class="form-control" id="useridList">
                                        </select>
    									<span class="error-alert"></span>
    								</div>
                                    <div class="col-md-1">
    									<a title="Add List" href="#" class="glyphicon glyphicon-plus-sign add_e_list" onclick="add_e_list()"></a>
    								</div>

							    </div>
                                <div class="row">
                                    <div class="col-md-3">
    									<label >Permission*</label>
    								</div>
                                    <div class="col-md-4">
    									<input type="radio" name="permissino" id="readonly" />
                                        <label for="readonly"> ReadOnly</label><br />
                                        <span class="error-alert"></span>
    								</div>
                                    <div class="col-md-4">
    									<input type="radio" name="permissino" id="read_write" />
                                        <label for="read_write"> Read/Write</label>
    								</div>

							    </div>
                                <ol id="ListDislayAdd"></ol>
								<span class="error-alert"></span>

                            </div>
                            <div class="modal-footer">
								<input type="button" class="btn" onclick="save_user_email1();" value="Save" />
							</div>
                    </div>
                </div>
            </div>
			<div id="addmodal" class="modal fade"  data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog  modal-lg">
					<div class="modal-content">
						<!--form id="addpopup" class="form" action="#" method="post" name="adminClient"-->
							<div class="modal-header section1">
								 <span class="close" onclick="cancel2()">x</span>
								 <h4 class="modal-title">Personal Information</h4>
							</div>
							<div class="modal-header section2 none">
								 <span class="close" onclick="cancel2()">x</span>
								 <h4 class="modal-title">Additional Details</h4>
							</div>
							<div class="modal-body section1">
									<div class="row">
										<div class="col-md-2">
											<label for="add_name">Name*</label>
										</div>
										<div class="col-md-2">
											<input type="text" id="add_name" class="form-control" placeholder="First name"/>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<input type="text" id="add_name1" class="form-control" placeholder="Last name"/>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="add_user_eId">Employee ID*</label>
										</div>
										<div class="col-md-4">
											<input type="text" class="form-control" id="add_user_eId"/>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label for="add_DOB">DOB</label>
										</div>
										<div class="col-md-4">
											<div class='input-group date' id='add_DOB1'>
												<input id="add_DOB" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
												<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="sibling">
												<span class="error-alert"></span>
											</div>
										</div>
										<div class="col-md-2">
											<label for="add_gender">Gender*</label>
										</div>
										<div class="col-md-4">
											<select name="adminContactDept" class="form-control" id="add_gender">
												<option value="">Choose</option>
												<option value="male">Male</option>
												<option value="female">Female</option>
												<option value="others">Others</option>
											</select>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label for="add_user_dep">Department*</label>
										</div>
										<div class="col-md-4">
											<select class="form-control" id="add_user_dep">
												<option value="">Choose</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="add_user_role">Role*</label>
										</div>
										<div class="col-md-4">
											<select class="form-control" id="add_user_role" >

											</select>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label for="add_rep_into">Reporting Into*</label>
										</div>
										<div class="col-md-3">
											<input type="text" class="form-control" id="add_rep_into" disabled />
											<span class="error-alert"></span>
										</div>
										<div class="col-md-1">
											<input type="button" id="add_rep_btn" class="btn" href="#addmodal1" data-toggle="modal" value="Add">
											<input type="hidden" class="btn" id="edit_user">
											<input type="hidden" class="btn" id="edit_role">
											<span class="error-alert"></span>
										</div>
                                        <div class="col-md-2">
											<label for="add_user_team">Team*</label>
										</div>
										<div class="col-md-4">
											<select class="form-control" id="add_user_team">
											</select>
											<span class="error-alert"></span>
										</div>

									</div>
									<div class="row">
										<div class="col-md-2">
											<label for="add_user_email">Email ID*</label>
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1 email_opt">
											<select class="form-control" id="mobile_email">
												<option value="">Email Type</option>
												<option value="work">Work</option>
												<option value="personal">Personal</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2 email_type">
											<input type="text" id="add_user_email" class="form-control" />
											<span class="error-alert mob_err"></span>
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1 col_fa">
											<span><input type="button" class="btn" onclick="email()" value="Add" /></span>
										</div>
										<div class="col-md-2 off_loc">
											<label for="add_user_loc">Office Location*</label>
										</div>
										<div class="col-md-4" id="add_user_loc">
											<div  class="multiselect ofc_loc">
												<ul>
												</ul>
											</div>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row email_cat none">
										<div class="col-md-3" id="work_email">
											<ul style="padding: 0px;">
											</ul>
										</div>
										<div class="col-md-3" id="email_personal">
											<ul style="padding: 0px;">
											</ul>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label for="add_mobile">Phone Number*</label>
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1 mobile_opt">
											<select class="form-control" id="mobile_add" style="padding-right: 2px;">
												<option value="">Choose</option>
												<option value="mobile">Mobile</option>
												<option value="home">Home</option>
												<option value="work">Work</option>
												<option value="main">Main</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2 mobile_type">
											<input type="text" id="mobile" name="my-mobile" class="form-control" placeholder="+91/0"/>
											<span class="error-alert mob_err"></span>
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1 mob_add">
											<span><button type="button" class="btn" onclick="add()">Add</button></span>
										</div>

										<div class="col-md-2 off_loc sell_type_labl" >
											<label>Sell/Process Type*</label>
										</div>
										<div class="col-md-4" id="add_sellType" style="margin-bottom: 5px;">
											<div  class="sell_multiselect">
												<ul>
												</ul>
											</div>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row contact_cat none">
										<div class="pull-left" id="mobile1">
											<ul style="padding: 0px;">
											</ul>
										</div>
										<div class="pull-left" id="home">
											<ul style="padding: 0px;">
											</ul>
										</div>
										<div class="pull-left" id="work">
											<ul style="padding: 0px;">
											</ul>
										</div>
										<div class="pull-left" id="main">
											<ul style="padding: 0px;">
											</ul>
										</div>
									</div>
                                    <div class="row">
										<div class="col-md-6" style="padding-left: 4px;">
											<div class="col-md-4">
												<label for="add_sales_persona">Executive Persona</label>
											</div>
											<div class="col-md-8">
												<select class="form-control" id="add_sales_persona">
													<option value="">Choose</option>
												</select>
												<span class="error-alert"></span>
											</div>

											<div class="col-md-4">
												<label for="time_zone">Time Zone*</label>
											</div>
											<div class="col-md-8">
												<select class="form-control" id="time_zone" disabled="disabled">
													<option>Choose</option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="col-md-4 mob_res">
												<label for="res_address">Residential Address</label>
											</div>
											<div class="col-md-8 mob_text" style="margin-left: 6px;padding-left: 4px;">
												<textarea class="form-control" id="res_address" style="min-height: 73px;"></textarea>
												<span class="error-alert"></span>
											</div>
										</div>
									</div>
                                    <div class="row">
                                        <div class="col-md-10" id="grp_emal_disp">

										</div>
										<div class="col-md-2">
											<input class="form-control btn" style="display: none" id="grp_emal_btn" type="button" onclick="grup_email('add')" value="Group Mail"/>
										</div>

									</div>
									<div class="row text_c">
										<div class="col-md-3">

										</div>
										<div class="col-md-6">
											<h4><b>Modules *</b></h4>
										</div>
										<div class="col-md-3">

										</div>
									</div>
									<div class="row" id="mang">

									</div>
									<div class="row error_mod none">
										<span class="error-alert"></span>
									</div>
									<div class="row tog_manager none">
										<div class="col-md-4">

										</div>
										<div class="col-md-3">
											<input type="checkbox" id="custo_assign" value="0" class="custo_assign"/> <label for="custo_assign">Customer Assignment</label>
										</div>
									</div>
									<div class="row text_c showpluginsHeader">
										<div class="col-md-3">

										</div>
										<div class="col-md-6">
											<h4><b>Plugins</b></h4>
										</div>
										<div class="col-md-3">

										</div>
									</div>
                                    <div class="row error_plug">
										<span class="error-alert"></span>
									</div>
									<!-- Logic need to change .. due to time limitation hiding entire..
									once start working on module need to work on it-->
									<div class="row">
										<div class="col-md-3" id="plug"></div>
										<div class="col-md-4 none">
											<div class="row">
												<select name="Roaster" id="Roaster" class="form-control" disabled>
													<option value="">Choose Roaster</option>
													<option value="custom">Custom</option>
													<option value="fixed">Fixed</option>
												</select>
											</div>
											<div class="row plugn"></div>
											<div class="row">
												<select class="form-control" id="add_user_exp" disabled>
													<option value="">Choose</option>
												</select>
											</div>
											<div class="row plugn"></div>
											<div class="row plugn"></div>
											<div class="row">
												<select class="form-control" id="add_user_nav" disabled>
													<option value="">Choose</option>
												</select>
											</div>
										</div>
									</div>
							</div>
								<div class="modal-body section2 none ">
									<div class="row">
										<div class="col-md-2">
											<label for="add_user_loc_ofc">Business Location*</label>
										</div>
										<div class="col-md-4" id="add_use">
											<div id="add_user_loc_ofc" class="multiselect_loc ofc_loc">
												<ul>
												</ul>
											</div>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="add_user_indu">Clientele Industries*</label>
										</div>
										<div class="col-md-4" id="add_use1">
											<div id="add_user_indu" class="multiselect_indu ofc_loc">
												<ul>
												</ul>
											</div>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row text_c">
										<div class="col-md-3">

										</div>
										<div class="col-md-6">
											<h4><b>Product and Currency*</b></h4>
										</div>
										<div class="col-md-3">

										</div>
									</div>
										<div class="row error_pro">
											<b><span class="error error-alert" id="error_edit"></span></b>
										</div>
									<div class="row pro_cur_user" id="currency_value_list1">

									</div>
									<div class="row">
										<div class="col-md-2">
											<label for="add_user_Hcal">Holiday Calendar*</label>
										</div>
										<div class="col-md-4">
											<select class="form-control" id="add_user_Hcal">
												<option value="">Choose</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-3">
											<!--  hide Call recording option becouse it is not ready from phone-------------(17-7-2018)  -->
											<label for="call_rec" class="none">
                                                <input type="checkbox" name="call_rec" id="call_rec" value="0">
                                                <b>Enable Call Recording</b>
                                            </label>
										</div>
										<div class="col-md-3">
											<input type="checkbox" name="accounting" id="accounting" value="0">
											<label for="accounting"><b>Enable Spend Calculation</b></label>
										</div>
									</div>
									<div class="row showaccounting none">
										<div class="col-md-2">
											<label for="resourceCurrency">Resource Cost/Hour*</label>
										</div>
										<div class="col-md-3">
											<select name="resourceCurrency" id="resourceCurrency" class="form-control ">
												<option value="">Choose Currency</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-1">
											<input type="text" name="resourceCost" class="form-control " id="resourceCost" placeholder="00.00" />
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="callCurrency">Cost/Outgoing Call/Min*</label>
										</div>
										<div class="col-md-3">
											<select name="callCurrency" id="callCurrency" class="form-control ">
												<option value="">Choose Currency</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-1">
											<input type="text" name="callCost" id="callCost" placeholder="00.00" class="form-control "/>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row showaccounting none">
										<div class="col-md-2">
											<label for="smsCurrency">Cost/Outgoing SMS*</label>
										</div>
										<div class="col-md-3">
											<select name="smsCurrency" id="smsCurrency" class="form-control ">
												<option value="">Choose Currency</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-1">
											<input type="text" name="smsCost" id="smsCost" placeholder="00.00" class="form-control "/>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="text_c">
										<div class="row work_h">
											<div class="col-md-3">
												<h4><b>Working Days</b></h4>
											</div>
											<div class="col-md-9">
												<h4><b>Working Hours</b></h4>
											</div>
										</div>

                                    <div id="working_days">
										<!--<br>
										<div class="row">
											<div class="col-md-2">
												<label for="time_zone"><b>Time Zone*</b></label>
											</div>
											<div class="col-md-5">
												<select class="form-control" id="time_zone">
													<option>Choose</option>
												</select>
											</div>
										</div>-->
										<br/>


										<div class="row" >
											<div class="col-md-2 pull-left">
												<input type="checkbox" name="select_all" id="select_all_days1" onclick="checkAllDays1()"><label for="select_all_days1">Select All</label>
											</div>
											<div id="workdays" class="col-md-10 pull-left">
												<center><span class="error-alert"></span></center>
											</div>
										</div>

                                        <div class="row ">
												<div class="col-md-3">
													<input type="checkbox" name="approve1" value="SUN" id="week1" onclick="remove1()" class="weeks"/> Sunday
												</div>
												<div class="col-md-2">
													<label for="Start Time">Start Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="strt_time" id="strt_time1" onmouseover="check()" onfocusout="check1()" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
												<div class="col-md-2">
													<label for="End Time">End Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="end_time" id="end_time1" onmouseover="check()" onfocusout="check1()" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
										</div>
										<div class="row ">
												<div class="col-md-3">
													<input type="checkbox" name="approve1" value="MON" id="week2" onclick="remove1()" class="weeks"/> Monday
												</div>
												<div class="col-md-2">
													<label for="Start Time">Start Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="strt_time" id="strt_time2" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
												<div class="col-md-2">
													<label for="End Time">End Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="end_time" id="end_time2" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
										</div>
										<div class="row ">
												<div class="col-md-3">
													<input type="checkbox" name="approve3" value="TUE" id="week3" onclick="remove1()" class="weeks"/> Tuesday
												</div>
												<div class="col-md-2">
													<label for="Start Time">Start Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="strt_time" id="strt_time3" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
												<div class="col-md-2">
													<label for="End Time">End Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="end_time" id="end_time3" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
										</div>
										<div class="row ">
												<div class="col-md-3 col_wed">
													<input type="checkbox" name="approve4" value="WED" id="week4" onclick="remove1()" class="weeks"/> Wednesday
												</div>
												<div class="col-md-2">
													<label for="Start Time">Start Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="strt_time" id="strt_time4"  disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
												<div class="col-md-2">
													<label for="End Time">End Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="end_time" id="end_time4" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
										</div>
										<div class="row ">
												<div class="col-md-3 col_thu">
													<input type="checkbox" name="approve5" value="THU" id="week5" onclick="remove1()" class="weeks"/> Thursday
												</div>
												<div class="col-md-2">
													<label for="Start Time">Start Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="strt_time" id="strt_time5" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
												<div class="col-md-2">
													<label for="End Time">End Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="end_time" id="end_time5" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
										</div>
										<div class="row ">
												<div class="col-md-3 col_fri">
													<input type="checkbox" name="approve6" value="FRI" id="week6" onclick="remove1()" class="weeks"/> Friday
												</div>
												<div class="col-md-2">
													<label for="Start Time">Start Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="strt_time" id="strt_time6" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
												<div class="col-md-2">
													<label for="End Time">End Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="end_time" id="end_time6" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
										</div>
										<div class="row ">
												<div class="col-md-3 col_sat">
													<input type="checkbox" name="approve7" value="SAT" id="week7" onclick="remove1()" class="weeks"/> Saturday
												</div>
												<div class="col-md-2">
													<label for="Start Time">Start Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="strt_time" id="strt_time7" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
												<div class="col-md-2">
													<label for="End Time">End Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="end_time" id="end_time7" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
										</div>

                                    </div>
									</div>
								</div>
							<div class="modal-footer section1">
								<span class="page_num">1 of 2</span>
								<input type="button" class="btn" onclick="proceed();" value="Save & continue" />
							</div>
							<div class="modal-footer section2 none">
								<span class="page_num">2 of 2</span>
								<input type="button" class="btn" id="back1" style="display: none" value="Back" >
								<input type="button" class="btn" name="btn2" id="section2save"  value="Save"/>
							</div>
						<!--/form-->
					</div>
				</div>
			</div>
			<div id="editmodal" class="modal fade"  data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog  modal-lg">
					<div class="modal-content">
						<!--form id="addpopup" class="form" action="#" method="post" name="adminClient"-->
							<div class="modal-header e_section1">
								 <span class="close" onclick="edit_cancel2()">x</span>
								 <h4 class="modal-title">Personal Information</h4>
							</div>
							<div class="modal-header e_section2 none">
								 <span class="close" onclick="edit_cancel2()">x</span>
								 <h4 class="modal-title">Additional Details</h4>
							</div>
							<div class="modal-body e_section1">
									<div class="row">
										<div class="col-md-2">
											<label for="edit_name">Name*</label>
										</div>
										<div class="col-md-4">
											<input type="hidden" id="add_user"/>

											<input type="text" id="edit_name" class="form-control" placeholder="Name"/>
											<span class="error-alert"></span>
										</div>
										<!--<div class="col-md-2">
											<input type="text" id="edit_name1" class="form-control" placeholder="Last name"/>
											<span class="error-alert"></span>
										</div>-->
										<div class="col-md-2">
											<label for="edit_user_eId">Employee ID*</label>
										</div>
										<div class="col-md-4">
											<input type="text" class="form-control" id="edit_user_eId"/>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label for="edit_DOB">DOB</label>
										</div>
										<div class="col-md-4">
											<div class='input-group date' id='edit_DOB1'>
												<input id="edit_DOB" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
												<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="sibling1">
												<span class="error-alert"></span>
											</div>
										</div>
										<div class="col-md-2">
											<label for="edit_gender">Gender*</label>
										</div>
										<div class="col-md-4">
											<select name="adminContactDept" class="form-control" id="edit_gender">
												<option value="">Choose</option>
												<option value="male">Male</option>
												<option value="female">Female</option>
												<option value="others">Others</option>
											</select>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label for="edit_user_dep">Department*</label>
										</div>
										<div class="col-md-4">
											<select class="form-control" id="edit_user_dep">
												<option value="">Choose</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="edit_user_role">Role*</label>
										</div>
										<div class="col-md-4">
											<select class="form-control" id="edit_user_role" >
											</select>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label for="edit_rep_into">Reporting Into*</label>
										</div>
										<div class="col-md-3">
											<input type="text" class="form-control" id="edit_rep_into" disabled />
											<span class="error-alert"></span>
										</div>
										<div class="col-md-1">
											<input type="button" class="btn" href="#addmodal2" data-toggle="modal" id="edit_rep_btn" value="Add">
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="edit_user_team">Team*</label>
										</div>
										<div class="col-md-4">
											<input type="hidden" id="add_team"/>
											<input type="hidden" id="edit_team"/>
											<select class="form-control" id="edit_user_team">
												<option value="">Choose</option>
											</select>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label for="edit_user_email">Email ID*</label>
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1 email_opt">
											<select class="form-control" id="mobile_email_edit">
												<option value="">Choose</option>
												<option value="work">Work</option>
												<option value="personal">Personal</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2 email_type">
											<input type="text" id="edit_user_email" class="form-control" />
											<span class="error-alert mob_err"></span>
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1 col_fa">
											<span><input type="button" class="btn" onclick="email1()" value="Add"></span>
										</div>
										<div class="col-md-2 off_loc">
											<label for="add_user_region">Office Location*</label>
										</div>
										<div class="col-md-4" id="edit_user_loc" style="margin-bottom:5px;">

											<div  class="multiselect1 ofc_loc">
												<ul>
												</ul>
											</div>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row email_cat_edit none">
											<div class="pull-left" id="work_email_edit">
												<ul style="padding: 0px;">
												</ul>
											</div>
											<div class="pull-left" id="email_personal_edit">
												<ul style="padding: 0px;">
												</ul>
											</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label for="edit_mobile">Phone Number*</label>
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1 mobile_opt">
											<select class="form-control" id="mobile_edit" style="padding-right: 2px;">
												<option value="">Choose</option>
												<option value="mobile">Mobile</option>
												<option value="home">Home</option>
												<option value="work">Work</option>
												<option value="main">Main</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2 mobile_type">
											<input type="text" id="mob_edit" class="form-control" placeholder="+91/0"/>
											<span class="error-alert mob_err"></span>
										</div>
										<div class="col-md-1 col-sm-1 col-xs-1 mob_add">
											<span><button type="button" class="btn" onclick="add1_mobile()">Add</button></span>
										</div>

										<div class="col-md-2 off_loc sell_type_labl">
											<label>Sell/Process Type*</label>
										</div>
										<div class="col-md-4" id="edit_sellType" style="margin-bottom: 5px;">
											<div  class="sell_multiselect">
												<ul>
												</ul>
											</div>
											<span class="error-alert"></span>
										</div>


									</div>

									<div class="row contact_cat1 none">
										<div class="pull-left" id="mobile1_edit">
											<ul style="padding: 0px;">
											</ul>
										</div>
										<div class="pull-left" id="home_edit">
											<ul style="padding: 0px;">
											</ul>
										</div>
										<div class="pull-left" id="work_edit">
											<ul style="padding: 0px;">
											</ul>
										</div>
										<div class="pull-left" id="main_edit">
											<ul style="padding: 0px;">
											</ul>
										</div>
									</div>
                                    <div class="row">
										<div class="col-md-6" style="padding-left: 4px;">
											<div class="col-md-4">
												<label for="edit_sales_persona">Executive Persona</label>
											</div>
											<div class="col-md-8">
												<select class="form-control" id="edit_sales_persona">
													<option value="">Choose</option>
												</select>
												<span class="error-alert"></span>
											</div>
												<div class="col-md-4" style="display: none">
													<label for="time_zoneE">Time Zone*</label>
												</div>
												<div class="col-md-8" style="display: none">
													<select class="form-control" id="time_zoneE"></select>
												</div>
										</div>

										<div class="col-md-6">
											<div class="col-md-4 mob_res">
												<label for="e_res_address">Residential Address</label>
											</div>
											<div class="col-md-8 mob_text" style="margin-left: 6px;padding-left: 4px;">
												<textarea class="form-control" id="e_res_address" style="min-height: 73px;"></textarea>
												<span class="error-alert"></span>
											</div>
										</div>
									</div>
                                    <div class="row">
                                        <div class="col-md-10" id="grp_emal_disp_edit">

										</div>
										<div class="col-md-2">
											<input class="form-control btn"style="display: none" id="grp_emal_btn_e" type="button" onclick="grup_email('edit')" value="Group Mail"/>
										</div>

									</div>
									<div class="row text_c">
										<div class="col-md-3">

										</div>
										<div class="col-md-6">
											<h4><b>Modules</b></h4>
										</div>
										<div class="col-md-3">

										</div>
									</div>
									<div class="row" id="mang1">

									</div>

									<div class="row error_mod1 none">
										<span class="error-alert"></span>
									</div>
									<div class="row e_tog_manager none">
                                        <div class="col-md-3">
											<input type="checkbox" id="e_custo_assign" /> <label for="e_custo_assign">Customer Assignment</label>
										</div>
										<!--<div class="col-md-3">
											<input type="checkbox" id="e_add_charge" /> <label for="e_add_charge">Additional Charges</label>
										</div>
										<div class="col-md-3">
											<input type="button" class="btn" id="e_dis_charge" value="Disable" />
										</div>
										<div class="col-md-3">
											<input type="button" class="btn" id="e_history_charge" value="Charge History" />
										</div>-->
									</div>
									<!--
									*********************8Additional Charges Removed***************

									<div class="row none" id="e_additional">
										<div class="col-md-2">
											<label for="">Start Time</label>
										</div>
										<div class="col-md-2">
											<input type="text" class="form-control" placeholder="hh:mm" id="charge_sdate" />
 										</div>
										<div class="col-md-2">
											<label for="">Start Time</label>
										</div>
										<div class="col-md-2">
											<input type="text" class="form-control" placeholder="hh:mm" id="charge_sdate" />
										</div>
										<div class="col-md-1">
											<label for="charge_team">Team</label>
										</div>
										<div class="col-md-3">
											<select class="form-control" id="charge_team">
												<option value="">Choose</option>
											</select>
										</div>
									</div>-->
									<div class="row text_c showpluginsHeader">
										<div class="col-md-3">

										</div>
										<div class="col-md-6">
											<h4><b>Plugins</b></h4>
										</div>
										<div class="col-md-3">

										</div>
									</div>
                                    <div class="row error_plug1 none">
										<span class="error-alert"></span>
									</div>
									<div class="row">
										<div class="col-md-3" id="plug1"></div>
										<div class="col-md-4 none">
											<div class="row">
												<select name="Roaster" id="eRoaster" class="form-control" disabled>
													<option value="">Choose Roaster</option>
													<option value="custom">Custom</option>
													<option value="fixed">Fixed</option>
												</select>
											</div>
											<div class="row plugn"></div>
											<div class="row">
												<select class="form-control" id="edit_user_exp" disabled>
													<option value="">Choose</option>
												</select>
											</div>
											<div class="row plugn"></div>
											<div class="row plugn"></div>
											<div class="row">
												<select class="form-control" id="edit_user_nav" disabled>
													<option value="">Choose</option>
												</select>
											</div>
										</div>
									</div>

							</div>
								<div class="modal-body e_section2 none">
									<div class="row">
										<div class="col-md-2">
											<label for="edit_user_floc">Business Location*</label>
										</div>
										<div class="col-md-4" id="edit_user_bus">
											<div id="edit_user_floc" class="multiselect_loc1 ofc_loc">
												<ul>
												</ul>
											</div>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="edit_user_indu">Clientele Industries*</label>
										</div>
										<div class="col-md-4" id="add_use_ind">
											<div id="edit_user_indu" class="multiselect_indu1 ofc_loc">
												<ul>
												</ul>
											</div>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row text_c">
										<div class="col-md-3">

										</div>
										<div class="col-md-6">
											<h4><b>Product and Currency*</b></h4>
										</div>
										<div class="col-md-3">

										</div>
									</div>
										<div class="row error_pro">
											<span class="error-alert" id="error_add"></span>

										</div>
									<div class="row pro_cur_user" id="currency_value_list">



									</div>
									<div class="row">
										<div class="col-md-2">
											<label for="edit_user_Hcal">Holiday Calendar*</label>
										</div>
										<div class="col-md-4">
											<select class="form-control" id="edit_user_Hcal">
												<option value="">Choose</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-3">
                                           <!--  hide Call recording option becouse it is not ready from phone-------------(17-7-2018)  -->
											<label for="edit_call_rec" class="none">
                                            <input type="checkbox" name="edit_call_rec" id="edit_call_rec" value="0">
                                            <b> Enable Call Recording</b>
                                            </label>
										</div>
										<div class="col-md-3">
											<input type="checkbox" name="e_accounting" id="e_accounting" value="0">
											<label for="e_accounting"><b>Enable Spend Calculation</b></label>
										</div>
									</div>
									<div class="row e_showaccounting none">
										<div class="col-md-2">
											<label for="e_resourceCurrency">Resource Cost/Hour*</label>
										</div>
										<div class="col-md-3">
											<select name="e_resourceCurrency" id="e_resourceCurrency" class="form-control ">
												<option value="">Choose Currency</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-1">
											<input type="text" name="e_resourceCost" class="form-control " id="e_resourceCost" placeholder="00.00" />
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="e_callCurrency">Cost/Outgoing Call/Min*</label>
										</div>
										<div class="col-md-3">
											<select name="e_callCurrency" id="e_callCurrency" class="form-control ">
												<option value="">Choose Currency</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-1">
											<input type="text" name="e_callCost" id="e_callCost" placeholder="00.00" class="form-control "/>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row e_showaccounting none">
										<div class="col-md-2">
											<label for="e_smsCurrency">Cost/Outgoing SMS*</label>
										</div>
										<div class="col-md-3">
											<select name="e_smsCurrency" id="e_smsCurrency" class="form-control ">
												<option value="">Choose Currency</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-1">
											<input type="text" name="e_smsCost" id="e_smsCost" placeholder="00.00" class="form-control "/>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="text_c work">
										<div class="row work_h">
										<div class="col-md-3">
											<h4><b>Working Days</b></h4>
										</div>
										<div class="col-md-9">
											<h4><b>Working Hours</b></h4>
										</div>
									</div>

									<div id="e_working_days">
										<br>
										<!--<div class="row">
											<div class="col-md-2">
												<label for="time_zoneE"><b>Time Zone*</b></label>
											</div>
											<div class="col-md-5">
												<select class="form-control" id="time_zoneE">
													<option>Choose</option>
												</select>
											</div>
										</div>-->


										<div class="row" >
											<div class="col-md-2 pull-left">
												<input type="checkbox" name="select_all" id="select_all_days" onclick="checkAllDays()"><label for="select_all_days">Select All</label>
											</div>
											<div id="e_workdays" class="col-md-10 pull-left">
												<center><span class="error-alert"></span></center>
											</div>
										</div>
                                        <div class="row ">
												<div class="col-md-3">
													<input type="checkbox" name="approve7" value="SUN"  id="e_week1" onclick="remove2()" class="weeks"/> Sunday
												</div>
												<div class="col-md-2">
													<label for="Start Time">Start Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="e_strt_time" id="e_strt_time1" onmouseover="e_check()" onfocusout="e_check1()" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
												<div class="col-md-2">
													<label for="End Time">End Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="e_end_time" id="e_end_time1" onmouseover="e_check()" onfocusout="e_check1()" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
										</div>
										<div class="row ">
												<div class="col-md-3">
													<input type="checkbox" name="approve1" value="MON" id="e_week2" onclick="remove2()" class="weeks"/> Monday
												</div>
												<div class="col-md-2">
													<label for="Start Time">Start Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="e_strt_time" id="e_strt_time2" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
												<div class="col-md-2">
													<label for="End Time">End Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="e_end_time" id="e_end_time2" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
										</div>
										<div class="row ">
												<div class="col-md-3">
													<input type="checkbox" name="approve2" value="TUE" id="e_week3" onclick="remove2()" class="weeks"/> Tuesday
												</div>
												<div class="col-md-2">
													<label for="Start Time">Start Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="e_strt_time" id="e_strt_time3" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
												<div class="col-md-2">
													<label for="End Time">End Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="e_end_time" id="e_end_time3" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
										</div>
										<div class="row ">
												<div class="col-md-3 col_wed">
													<input type="checkbox" name="approve3" value="WED" id="e_week4" onclick="remove2()" class="weeks"/> Wednesday
												</div>
												<div class="col-md-2">
													<label for="Start Time">Start Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="e_strt_time" id="e_strt_time4" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
												<div class="col-md-2">
													<label for="End Time">End Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="e_end_time" id="e_end_time4" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
										</div>
										<div class="row ">
												<div class="col-md-3 col_thu">
													<input type="checkbox" name="approve4" value="THU" id="e_week5" onclick="remove2()" class="weeks"/> Thursday
												</div>
												<div class="col-md-2">
													<label for="Start Time">Start Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="e_strt_time" id="e_strt_time5" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
												<div class="col-md-2">
													<label for="End Time">End Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="e_end_time" id="e_end_time5" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
										</div>
										<div class="row ">
												<div class="col-md-3 col_fri">
													<input type="checkbox" name="approve5" value="FRI" id="e_week6"  onclick="remove2()" class="weeks"/> Friday
												</div>
												<div class="col-md-2">
													<label for="Start Time">Start Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="e_strt_time" id="e_strt_time6" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
												<div class="col-md-2">
													<label for="End Time">End Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="e_end_time" id="e_end_time6" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
										</div>
										<div class="row ">
												<div class="col-md-3 col_sat">
													<input type="checkbox" name="approve6" value="SAT" id="e_week7" onclick="remove2()"  class="weeks"/> Saturday
												</div>
												<div class="col-md-2">
													<label for="Start Time">Start Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="e_strt_time" id="e_strt_time7"  disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
												<div class="col-md-2">
													<label for="End Time">End Time</label>
												</div>
												<div class="col-md-2">
													<input type="text" name="e_end_time" id="e_end_time7" disabled class="form-control"/>
													<span class="error-alert"></span>
												</div>
										</div>

									</div>
									</div>
								</div>
							<div class="modal-footer e_section1">
								<span class="page_num">1 of 2</span>
								<input type="button" class="btn" onclick="e_proceed()" value="Save & continue">
							</div>
							<div class="modal-footer e_section2 none">
								<span class="page_num">2 of 2</span>
								<input type="button" class="btn" id="e_back1" style="display: none"  value="Back" >
								<input type="button" class="btn" onclick="e_proceed2();" value="Save">
							</div>
						<!--/form-->
					</div>
				</div>
			</div>
			<div id="addmodal1" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
					<div class="modal-content">
						<!--form id="addpopup" class="form" action="#" method="post" name="adminClient"-->
							<div class="modal-header modal-title">
								 <span class="close" onclick="cancel1()">&times;</span>
								 <h4>Reporting Into</h4>
							</div>
							<div class="modal-body">
									<div class="row">
										<div class="col-md-2">
											<label for="add_user_desg">Designation*</label>
										</div>
										<div class="col-md-4">
											<select class="form-control" id="add_user_desg" >
												<option value="">Choose</option>
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="add_desg_name">Name</label>
										</div>
										<div class="col-md-4">
											<input type="hidden" id="add_desg_name1"/>
											<select class="form-control" id="add_desg_name" onchange="mod_plug1(this.value)">
											</select>
											<span class="error-alert"></span>
										</div>
									</div>
							</div>
							<div class="modal-footer" >
									<input type="button" class="btn" onclick="add2()" value="Save">
									<input type="button" class="btn" onclick="cancel1()" value="Cancel" >
							</div>
						<!--/form-->
					</div>
				</div>
			</div>
			<div id="addmodal2" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
					<div class="modal-content">
						<!--form id="addpopup" class="form" action="#" method="post" name="adminClient"-->
							<div class="modal-header modal-title">
								 <span class="close" onclick="e_cancel1()">&times;</span>
								 <h4>Reporting Into</h4>
							</div>
							<div class="modal-body">
									<div class="row">
										<div class="col-md-2">
											<label for="edit_user_desg">Designation *</label>
										</div>
										<div class="col-md-4">
                                            <input type="hidden" id="reportingto_desg"/>
											<select class="form-control" id="edit_user_desg">
											</select>
											<span class="error-alert"></span>
										</div>
										<div class="col-md-2">
											<label for="edit_desg_name">Name</label>
										</div>
										<div class="col-md-4">
											<select class="form-control" id="edit_desg_name" onchange="mod_plug1(this.value)" >
											</select>
											<span class="error-alert"></span>
										</div>
									</div>
							</div>
							<div class="modal-footer" >
									<input type="button" class="btn" onclick="add_ds()" value="Save">
									<input type="button" class="btn" onclick="e_cancel1()" value="Cancel" >
							</div>
						<!--/form-->
					</div>
				</div>
			</div>
			<div id="confirm_alert" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header modal-title">
							 <span class="close" onclick="alert_cancel()">&times;</span>
							 <h4 class="modal-title">Choose Contact details to send Login details</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-6 email_list"></div>
								<div class="col-md-6 phn_list"></div>
							</div>
						</div>
						<div class="modal-footer" >
							<input type="button" class="btn" onclick="next()" value="Proceed">
							<input type="button" class="btn" onclick="alert_cancel()" value="Cancel" >
						</div>
					</div>
				</div>
			</div>
            <div id="confirm_alertE" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header modal-title">
							 <span class="close" onclick="alert_cancelE()">&times;</span>
							 <h4 class="modal-title">Edit primary contact Info</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-6 email_list_E"></div>
								<div class="col-md-6 phn_list_E"></div>
							</div>
						</div>
						<div class="modal-footer alert_foot" >
							<input type="button" class="btn" onclick="nextE()" value="Proceed">
							<input type="button" class="btn" onclick="alert_cancelE()" value="Cancel" >
						</div>
					</div>
				</div>
			</div>
			<div id="active_status_confirm" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog alert_modal">
					<div class="modal-content">
						<div class="modal-header modal-title">
							<span class="close" onclick="activecancel()">x</span>
							<h4 class="modal-title">Choose Reporting Replacement</h4>
						</div>
                        <div class="row" id="rep_mang">
                        </div>
                        <div class="row error_mod2 none">
										<span class="error-alert"></span>
						</div>
						<div class="modal-body alert_body">
							<div class="row" id='replacediv'>
								<div class="col-md-12">
									<label for="assign_to">Choose Reporting Replacement*</label>
								</div>
								<div class="col-md-12">
									<select class="form-control" id="assign_to"></select>
								</div>
							</div>
						</div>
                        <div class="row error_mod3 none">
										<span class="error-alert"></span>
						</div>

						<div class="modal-footer alert_foot" >
							<input type="button" class="btn" onclick="activesubmit()" value="Save">
							<input type="button" class="btn" onclick="activecancel()" value="Cancel" >
						</div>
					</div>
				</div>
			</div>
            <!-- added by swati on 14-9-2017 -->
            <div id="active_status_confirm1" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header modal-title">
							<span class="close" onclick="activecancel1()">x</span>
							<h4 class="modal-title">Choose Reporting Replacement</h4>
						</div>
						<div class="modal-body" id="replacement_table">

						</div>
						<div class="modal-footer" >
							<input type="button" class="btn activesubmit1"  onclick="activesubmit1()" value="Save">
							<input type="button" class="btn activesubmit none" onclick="activesubmit()" value="Save">
							<input type="button" class="btn" onclick="activecancel1()" value="Cancel" >
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php $this->load->view('footer'); ?>

	</body>

</html>
