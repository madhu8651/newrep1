<!DOCTYPE html>
<html lang="en">
	<head>
	<?php $this->load->view('scriptfiles'); ?>
            <script>
                var base_url = '<?php echo base_url(); ?>';
            </script>
	<style>

#workingTable tr td{
  text-align: inherit;
}

.text-center{font-weight:bold;font-size:16px;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;}
#stage_entry_point{font-weight:bold;font-size:16px;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;}
#stage_exit_point{font-weight:bold;font-size:16px;font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;}
.stage{font-weight:bold;font-size:16px;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;}
#custom_fields{
  border-top: none!important;
}
    .multiselect ul li:hover{
       background: #e4e3d8;
       margin-bottom: 10px;
     }

     .multiselect ul:hover{
            height: 230px;
          overflow: auto;
          background: #fff;
          position:absolute;
     }

     .multiselect{
       overflow: hidden;
     }

     .multiselect.disable ul li{
       background: #e4e3d8;
       margin-bottom: 10px;
     }
     .multiselect ul li.sel {
        background: #ccc!important;
     }
     .multiselect ul li label{
        width: 100%;
     }
     .multiselect.disable li,
     .multiselect.disable li *{
           cursor: not-allowed;
     }
    .modulename{
      color: #FFF;
      font-size: 10px;
      border-radius: 50%;
      padding: 4px 7px;

        position: relative;
    }
    .modulename.sales {
    background-color: #ff6d00!important;
    }
    .modulename.manager {
      background-color: #93C!important;

	}
	#stageAttribute .popover.fade.bottom.in{
		    width: 100%;
    max-width: 400px;
	}
	.glyphicon.glyphicon-saved{
		font-size: 30px;
		border: 1px solid;
	}
	#workingTable.table .glyphicon{
		margin-top: 4px;
		padding: 2px;
	}
	.body-content .nav.nav-tabs .active a{
		font-weight: 800 !important;
		color: #b5000a !important;
	}
	</style>
	<script>
$(window).load(function() {
		loadpage();
});

	/* //--------------------------------------Load Table fuction */
	var number_chk =  new RegExp(/^\d+$/);

/* ------------------------------------------------------------ on load fill the table -------------------------------------------------------------------- */
	function loadpage(){
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sales_stage_flowchartController/get_data'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
							return;
						}
				$('#CYCLE_NAME').empty();
				$('#stagedata').empty();
				var row = "";
				var row1 = "";

				$("#main_data_content").show();
                $("#no_data_section").addClass('none').html("");
                if(data.length==0){
					loaderHide();
					$("#main_data_content").hide();
					$("#no_data_section").removeClass('none').append("<center style='padding-top: 60px;'><h4>No data available. Visit <b>Sales Cycle</b> page to add data<br> <a href='<?php echo site_url('admin_sales_cycleController'); ?>'><u>Sales Cycle </u></a> and Visit <b>Sales Stage Flowchart</b> page to add data<br> <a href='<?php echo site_url('admin_sales_stageController'); ?>'><u>Sales Cycle Stages </u></a></h4></center>");
                }else{
					for(i=0;i < data.length; i++){
						if(i==0){
							row += "<li class='active'><a href='#"+data[i].CYCLE_ID+"' onclick='renderSingleTable(\""+data[i].CYCLE_ID+"\")'  data-toggle='tab'>" + data[i].CYCLE_NAME+"</li>";
						}else{
							row += "<li><a href='#"+data[i].CYCLE_ID+"' onclick='renderSingleTable(\""+data[i].CYCLE_ID+"\")'  data-toggle='tab'>" + data[i].CYCLE_NAME+"</li>";
						}
					}
					$('#CYCLE_NAME').append(row);
					renderSingleTable(data[0].CYCLE_ID)
				}
			}
		});
	}
/* -------------------------------------------------------------------------------------------------------------------------------------------------------- */

function renderSingleTable(cycleid){
        var addObj={};
        addObj.cycleid=cycleid;
         loaderShow();
         $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sales_stage_flowchartController/get_data1'); ?>",
			dataType : 'json',
            data: JSON.stringify(addObj),
			cache : false,
			success : function(data){

				if(error_handler(data)){
							return;
				}
				$('#stagedata').empty();
				
				var row1 = "";
				row1 += '<div class="tab-pane active" id="'+cycleid+'">';
				
				if(data.length <= 0){
					row1 += "<center style='padding-top: 60px;'><h4>No data available.</h4></center>";
				}else{
					row1 += '<table class="table"><thead>';
					row1 += "<tr><th width='10%'>Sl No</th><th width='40%'>Stage Name</th><th width='40%'>Description</th><th width='10%'></th></tr></thead><tbody>";
					for(i=0;i < data.length; i++){
							for(var k=0; k < data[i].stagedata.length; k++ ){
								var rowdata = JSON.stringify(data[i].stagedata[k]);
								var remarks = "";
								var jsondata=JSON.stringify([{"CYCLE_ID":cycleid,"stage_name":data[i].stagedata[k].stage_name,"stage_id":data[i].stagedata[k].stage_id,"remarks":data[i].stagedata[k].remarks,"stg_seq":data[i].stagedata[k].stage_sequence}]);
								if(data[i].stagedata[k].remarks == null){
									remarks = "";
								}else{
									remarks = data[i].stagedata[k].remarks;
								}
								var next="";
								var prev="";
								if(0<k && k<(data[i].stagedata.length-1)){
									prev = data[i].stagedata[(k-1)].stage_name;
									next = data[i].stagedata[(k+1)].stage_name;
								}
								if(k==0){
									if(data[i].stagedata.length==1){
										next="";
									}else{
										next = data[i].stagedata[(k+1)].stage_name;
									}
								}
								if(k== data[i].stagedata.length-1){
									if(data[i].stagedata.length==1){
										prev = "";
									}else{
										prev = data[i].stagedata[(k-1)].stage_name;
									}

								}

								row1 += "<tr id='"+data[i].stagedata[k].stage_id+"'><td>" + (k+1) + "</td><td class='stage-name'>" + data[i].stagedata[k].stage_name + "</td><td >" + remarks + "</td><td><a data-toggle='modal' href='#stageAttribute'  onclick='selrow("+jsondata+","+rowdata+",\""+prev+"\",\""+next+"\")'><span class='glyphicon glyphicon-pencil'></span></td></tr>";
							}
					}
					row1 += '</tbody></table>';
				}
				row1 += '</div>';
				$('#stagedata').append(row1);
				$('#stagedata table').each(function(){
					$(this).removeAttr("style");

				});
				$('#stagedata table tr th').each(function(){
						$(this).removeAttr("style");
				});
			}
         });
         get_allocation(cycleid);
}
var testData = [];
function get_allocation(cycleid){
     var addObj={};
     addObj.cycleid=cycleid;
     testData = [];
     $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sales_stage_flowchartController/get_owner'); ?>",
			dataType : 'json',
            data: JSON.stringify(addObj),
			cache : false,
			success : function(data){
				if(error_handler(data)){
							return;
				}
                loaderHide();
                testData = JSON.parse(JSON.stringify(data));
			}
         });
}
/* ------------------------------------------------------- function to fill user name ----------------------------------------------------------------------- */
   function loadowner(cycleid,dataval){
        var addObj={};
        addObj.cycleid=cycleid;
        if(dataval === undefined ){
                dataval="";
        }
        var multipl=""
		for(var i=0,j=1;i<testData.length; i++){
		  var Persona="";
		  if( !testData[i].user_product){
		    Persona = "";
		  }else{
            Persona = '<span><b>Sales Persona : </b>'+testData[i].user_product +'</span> <br>';
		  }
          var module="";
          if(testData[i].manager_module !="0"){
           module += '<span  class="modulename manager">M</span>';
          }else{
             module += "";
          }
          if(testData[i].sales_module != "0"){
            module += '<span class="modulename sales">E</span>';
          }else{
             module += "";
          }
			//multipl +='<li><label><input type="checkbox"  value="'+testData[i].user_id+'" disabled />  '+testData[i].user_name+' ('+testData[i].user_product+','+testData[i].deptnm+','+testData[i].tmnm+')'+'<label></li>';
			multipl +='<li><label><input type="checkbox"  value="'+testData[i].user_id+'" disabled />'+
                        '<span>'+testData[i].user_name+'</span> &nbsp;&nbsp;&nbsp;<span>'+module+'</span><br>'+
                        '<div style="margin-left:30px;">'+Persona+
                        '<span><b>Department : </b>'+testData[i].deptnm+'</span><br>'+
                        '<span><b>Team : </b>'+testData[i].tmnm+'</span></div>'+
                        '</label> </li>';

		}

		$(".multiselect ul").html(multipl);

        if(dataval!=""){
                console.log(dataval)
                var ownerval1 = dataval.split(':');
                $(".multiselect ul li input[type=checkbox]").each(function(){
        $(this).prop("disabled", false);
                    for(var j=0;j<ownerval1.length;j++){
                        if(ownerval1[j] == $(this).val()){
                            loaderHide();
                            $(this).prop("checked", true);
                            $(this).closest('li').addClass('sel');
                        }
                    }
                });
        }else{
             loaderHide();
        }
        loadqual1(cycleid);

    }
/* ------------------------------------------------------------------------------------------------------------------------------------------------------- */
/* ------------------------------------------------------- function to fill qualifier saved and not saved one ----------------------------------------------------------------------- */
    function loadqual(qualval,cycleid){
         addObj={};
         addObj.qualid=qualval;
         addObj.cycleid=cycleid;
         $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sales_stage_flowchartController/get_qualifier'); ?>",
			dataType : 'json',
            data: JSON.stringify(addObj),
            cache : false,
            success : function(data){
				if(error_handler(data)){
							return;
						}
				var select = $("#attr_value2"), options = '<option value="0">Select</option>';
				select.empty();
				for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].lead_qualifier_id+"'>"+ data[i].lead_qualifier_name +"</option>";
				}
				select.append(options);
                if(qualval!=""){
				    $('#attr_value2 option[value="'+qualval+'"]').attr("selected",true);
				}

			}
         });
    }
/* ------------------------------------------------------------------------------------------------------------------------------------------------------- */

/* ------------------------------------------------------- function to fill qualifier which are not saved  ----------------------------------------------------------------------- */

    function loadqual1(cycleid){
         addObj={};
         addObj.cycleid=cycleid;
         $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sales_stage_flowchartController/get_qualifier1'); ?>",
			dataType : 'json',
            data: JSON.stringify(addObj),
            cache : false,
            success : function(data){
				if(error_handler(data)){
							return;
						}
				var select = $("#attr_value2"), options = '<option value="0">Select</option>';
                
                var select = $("#hid_attr_value5"), options = '<option value="0">Select</option>';
				select.empty();
				for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].lead_qualifier_id+"'>"+ data[i].lead_qualifier_name +"</option>";
				}
				select.append(options);

                
				setTimeout(
				function(){ 
					/* alert($("#stageAttribute .multiselect").closest('td').innerWidth()) */
					$(".multiselect,.multiselect ul").css("width", $("#stageAttribute .multiselect").closest('td').innerWidth()-5);
					}, 
					1000
				);
			}
         });
    }
/* ----------------------------------------------------------------------------------------------------------------------------------------------------------------- */
/* ------------------------------------------------------- function to fill action stages above the selected stage ----------------------------------------------------------------------- */
  function load_actnstage(stage_seq,cycleid,stageval){
		var addObj={};
		addObj.stage_seq=stage_seq;
		addObj.cycleid=cycleid;
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sales_stage_flowchartController/get_actnstage'); ?>",
			dataType : 'json',
			data: JSON.stringify(addObj),
			cache : false,
			success : function(data){
				if(error_handler(data)){
							return;
						}
                /* loaderHide(); */
				var select = $("#attr_value4"), options = '<option value="0">Select Reject Redirect Stage</option>';
				select.empty();
				for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].stage_id+"'>"+ data[i].stage_name +"</option>";
				}
				select.append(options);
				if(stageval!=""){
				  $('#attr_value4 option[value="'+stageval+'"]').attr("selected",true);
				}else{
				  loadowner(cycleid,"");
				}
			}
		});
    }

/* ----------------------------------------------------------------------------------------------------------------------------------------------------------- */

/* ----------------------------------------- on click of pencil icon fill the pop up ---------------------------------------------------------------------- */

 var savedvalArr={};
 function renderSavedValues(id){

        if($("#"+id).prop('checked')==true){
          $("#attr_"+id).prop('disabled',false);
          $("#attr_"+id).closest('tr').find('input[type=text]').prop('disabled', false).val(savedvalArr[id]);
        }else{
            $("#attr_"+id).prop('disabled',true);
            $("#attr_"+id).closest('tr').find('input[type=text]').prop('disabled', true).val('');
        }


 }
 function saveName(stage_id, CYCLE_ID){
	 
		var obj={};
		obj.description = $.trim($("#descriptionChng").val());
		obj.stage_id = stage_id;
		obj.CYCLE_ID = CYCLE_ID;
		if(obj.description == ""){
			$("#descriptionChng").next('.error-alert').text('Description is required.')
			$("#descriptionChng").focus();
			return;
		}else if(!comment_validation(obj.description)){
			$("#descriptionChng").next('.error-alert').text("No special characters allowed (except $ & : ) ( # @ _ . , + % ? -)");
			$("#descriptionChng").focus();
			return;
		}else{
			$("#descriptionChng").next('.error-alert').text("")
		}


	 $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sales_stage_flowchartController/post_desc'); ?>",
			dataType : 'json',
			data: JSON.stringify(obj),
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
                $("#stage_remarks").text(obj.description);
                $('.popover').hide();
                renderSingleTable(CYCLE_ID);
				
			},
			error :function(data){
				network_err_alert(data);
			}
		});
 }
 function selrow(obj,mainobj, entryPoint, exitPoint){
        savedvalArr={};
        var flg1=0;
        var flg2=0;
        var flg3=0;
        $("#hid_stageid").val(obj[0].stage_id);
        $("#hid_cycleid").val(obj[0].CYCLE_ID);
        $("#hid_stage_seq").val(obj[0].stg_seq);
        var row = $("#"+obj[0].stage_id).closest('tr');

        var next = row.next('tr').attr('id');
        var prev = row.prev('tr').attr('id');

        document.getElementById("attr_value2").style.display='none';

        loadowner(obj[0].CYCLE_ID,"");
        document.getElementById("hid_attr_value5").style.display='block';

        $("#stage_name_t").text(obj[0].stage_name);
        $("#stage_exit_point").text(exitPoint);
        if(entryPoint == ""){
            $(".text-center").hide();
        }else{
            $(".text-center").show();
        }
        if(exitPoint == ""){
            $(".stage").hide();
        }else{
            $(".stage").show();
        }
        $("#stage_entry_point").text(entryPoint);
        $("#stage_remarks").text(obj[0].remarks);
		/*$(".lead_qualifier_nameE").each(function(){
			---------Double click----------
			var val = $(this).text();
			var IdVal = $(this).closest("tr").attr("id");
			$(this).dblclick(function(){
				$(".lead_qualifier_nameE").find('span').show();
				$(".lead_qualifier_nameE").find('div').remove();
				$(this).append('<div><input type="text" value="'+val+'"/><a class="glyphicon glyphicon-saved" title="Save" onclick="saveName(\''+val+'\' ,\''+IdVal+'\' )" ></a><a title="Undo" class="glyphicon fa fa-undo" onclick="cnclName(\''+IdVal+'\')"></a><div>');
				$(this).find('span').hide();
			})
			---------Double click----------
		})*/
		
		/* var val = obj[0].remarks; */
		var stage_id = obj[0].stage_id;
		var CYCLE_ID = obj[0].CYCLE_ID;
		var options = {
			content: '<div class="row">'+
						'<div class="col-md-10">'+
							'<input id="descriptionChng" class="form-control" type="text" value="'+obj[0].remarks+'"/>'+
							'<span class="error-alert"></span>'+
						'</div>'+
						'<div class="col-md-2 text-center">'+
						'<a class="glyphicon glyphicon-saved" title="Save" onclick="saveName(\''+stage_id+'\',\''+CYCLE_ID+'\' )" ></a>'+
						'</div>'+
					'<div>',
			html: true,
			placement: 'bottom'
		};
		/*$("#stage_remarks").click(function(e){
			 $('.popover').hide();
		})
		*/
		$("#stage_remarks").popover(options);


		
        $("#allocationMatrix,#qualifier,#documentUpload,#actionButton,#max_permission").each(function(){
            var checked_checkbox = $(this);
            checked_checkbox.closest('tr').find('input[type=text],input[type=radio],select').prop('disabled', true);
            checked_checkbox.closest('tr').find('.multiselect').addClass('disable');
            checked_checkbox.closest('tr').find('.multiselect input[type=checkbox]').prop('disabled', true);

            checked_checkbox.change(function(){

                if(checked_checkbox.prop('checked')==true){

                    if(checked_checkbox.val()== "max_permission"){
                        $("#days").prop('checked', true);
                    }
                    checked_checkbox.closest('tr').find('input[type=text],input[type=radio],select').removeAttr('disabled');
                    checked_checkbox.closest('tr').find('.multiselect').removeClass('disable');
                    checked_checkbox.closest('tr').find('.multiselect input[type=checkbox]').prop('disabled', false);


                }else if(checked_checkbox.prop('checked')==false){
                    checked_checkbox.closest('tr').find('input[type=text],input[type=radio],select').prop('disabled', true);
                    /* making reset right side corrocponding input element once user unchecked the left side checkbox--------- */
                    if(checked_checkbox.val()== "max_permission"){
                        checked_checkbox.closest('tr').find('input[type=radio]').prop('checked', false);
                    }
                    if(checked_checkbox.val()== "allocation_matrix"){
                        checked_checkbox.closest('tr').find('.multiselect input[type=checkbox]').val("");
                        checked_checkbox.closest('tr').find('.multiselect').addClass("disable");
                        checked_checkbox.closest('tr').find('.multiselect input[type=checkbox]').prop('disabled',true );
                    }
                    checked_checkbox.closest('tr').find('input[type=text],input[type=radio],.multiselect input[type=checkbox]').val("");
                    checked_checkbox.closest('tr').find('select').val(checked_checkbox.closest('tr').find('select option:first').val());
                }
            });
        });

        var selectstage_seq=obj[0].stg_seq
        if(selectstage_seq==6){
                  /* $("#allocationMatrix").prop('disabled', true);
                  $("#qualifier").prop('disabled', true); */
        }
        if(mainobj.hasOwnProperty('attributedata')) {
            savedvalArr={};
            load_actnstage(obj[0].stg_seq, obj[0].CYCLE_ID, stageval);
            for(var i=0; i < mainobj.attributedata.length;i++){

                var dataname = mainobj.attributedata[i].attribute_name;
                var dataval = mainobj.attributedata[i].attribute_value;
                var saved_seq = mainobj.attributedata[i].seqno;

                if(dataname == "allocation_matrix"){
                    i;
                    loadowner(obj[0].CYCLE_ID,dataval);
                    var ownerval=dataval;
                    $("#allocationMatrix").prop('checked', true);
                    $("#allocationMatrix").closest('tr').find('select').removeAttr('disabled');
                    $("#allocationMatrix").closest('tr').find('.multiselect').removeClass('disable');
                    $("#allocationMatrix").closest('tr').find('.multiselect input[type=checkbox]').prop('disabled', false);
                    if(ownerval === undefined ){
                        ownerval="";
                    }

                    if(ownerval!=""){

                        var ownerval1 = ownerval.split(':');
                        $(".multiselect ul li input[type=checkbox]").each(function(){
                            for(var j=0;j<ownerval.length;j++){
                                if(ownerval1[j] == $(this).val()){
                                    $(this).prop("checked", true);
                                    $(this).closest('li').addClass('sel');
                                }
                            }
                        });
                    }

                }else if(dataname=="qualifier"){
                    var qualval=dataval;
                    loadqual(dataval,$("#hid_cycleid").val());
                    document.getElementById("attr_value2").style.display='block';
                    document.getElementById("hid_attr_value5").style.display='none';
                    $("#qualifier").prop('checked', true);
                    $("#attr_value2").closest('tr').find('select').removeAttr('disabled');
                    if(qualval!=""){
                            $('#attr_value2 option[value="'+qualval+'"]').attr("selected",true);
                    }
                }else if(dataname=="document_upload"){
                    $("#documentUpload").prop('checked', true);
                    $("#attr_value3").closest('tr').find('input[type=text]').prop('disabled', false).val(dataval);
                }else if(dataname=="action_button"){
                    var stageval=dataval;
                    load_actnstage(obj[0].stg_seq, obj[0].CYCLE_ID, dataval);
                    if(dataval!=""){
                        $('#attr_value4 option[value="'+qualval+'"]').attr("selected",true);
                    }
                    $("#actionButton").prop('checked', true);
                    $("#attr_value4").closest('tr').find('select').removeAttr('disabled');
                }else if(dataname=="max_permission"){
                    var data=dataval.split("-");
                    if(data[1]==1){
                        $("#days").prop('checked', true);
                    }else{
                        $("#hours").prop('checked', true);
                    }
                    $("#max_permission").prop('disabled', false).prop('checked', true);
                    $("#attr_value5").closest('tr').find('input[type=text]').prop('disabled', false).val(data[0]);
                }else if(dataname=="closedwon"){
                    $("#allowclosedwon").prop('disabled', false).prop('checked', true);
                }

                if(parseInt(selectstage_seq) > parseInt(saved_seq)){
                    $("#"+dataname).prop('disabled', true).prop('checked', true);
                    $("#attr_"+dataname).closest('tr').find('input[type=text]').val(dataval);

                }

                if(parseInt(selectstage_seq) == parseInt(saved_seq)){
                    $("#"+dataname).prop('disabled', false).prop('checked', true);
                    $("#attr_"+dataname).closest('tr').find('input[type=text]').prop('disabled', false).val(dataval);

                }

                if(parseInt(selectstage_seq) < parseInt(saved_seq)){
                    $("#hid_attr_"+dataname).closest('tr').find('input[type=hidden]').prop('disabled', false).val(dataval);
                    console.log($("#hid_attr_"+dataname).val());
                    savedvalArr[dataname]=dataval;
                }

            } /* // end of  mainobj for loop */

        }else{
                load_actnstage(obj[0].stg_seq, obj[0].CYCLE_ID, stageval);

                $("#stageAttributeFrm input[type=text],#stageAttributeFrm input[type=radio],#stageAttributeFrm select").prop('disabled', true);
                $("#stageAttributeFrm .multiselect").addClass('disable')
                $("#stageAttributeFrm .multiselect input[type=checkbox]").prop('disabled', true);
                /* $("#stageAttributeFrm input[type=checkbox]").each(function(){ */
                $("#allocationMatrix,#qualifier,#documentUpload,#actionButton,#max_permission,#allowclosedwon").each(function(){
                    var checked_checkbox = $(this);
                    checked_checkbox.change(function(){
                    if(checked_checkbox.prop('checked')==true){
                        checked_checkbox.closest('tr').find('input[type=text],input[type=radio],select').removeAttr('disabled');
                        checked_checkbox.closest('tr').find('.multiselect').removeClass('disable');
                        $("#stageAttributeFrm .multiselect input[type=checkbox]").prop('disabled', false);
                    }else if(checked_checkbox.prop('checked')==false){
                        checked_checkbox.closest('tr').find('input[type=text],input[type=radio],select').prop('disabled', true);
                        /*making reset right side corrocponding input element once user unchecked the left side checkbox---------*/
                        if(checked_checkbox.val()== "max_permission"){
                            checked_checkbox.closest('tr').find('input[type=radio]').prop('checked', false);
                        }
                        checked_checkbox.closest('tr').find('input[type=text],input[type=radio],.multiselect input[type=checkbox]').val("");
                        checked_checkbox.closest('tr').find('select').val(checked_checkbox.closest('tr').find('select option:first').val());
                    }
                    })
                });

                $('input.default-disabled').prop('disabled',true);
        }
    }
/* ---------------------------------------------------------------------------------------------------------------------------------------------------------- */
/* //----------------------------------------------Edit Sales stage----------------- -----------------------------------------------------------------------*/
		function editsave(){
			$("#editsave_btn").prop('disabled', true);
			var stageArrtibute1={};
			var stageArrtibute2={};
			var usernames='';
            var testarr={};
            var addObj={};
			var succ=1;
            var usrflg=0;
            var hid_stageid=$("#hid_stageid").val();
            var hid_cycleid=$("#hid_cycleid").val();
            var hid_stage_seq=$("#hid_stage_seq").val();
            var i=1;
			$("#allocationMatrix,#qualifier,#documentUpload,#actionButton,#max_permission,#allowclosedwon").each(function(){
				var checked_checkbox = $(this);
				var error_msg = $.trim(checked_checkbox.closest('label').text());
				var attrvalue2=[];
				if(checked_checkbox.prop('checked')==true){
                    var attrvalue=$('#attr_value'+i).val();
                    var attrvalue11=$('#hid_attr_value5').val();
                    var attrname=checked_checkbox.val();

                    if(attrname=='qualifier' && attrvalue11 !="0"){
                         attrvalue=attrvalue11;
                    }
                    if(attrname == 'closedwon'){
                         testarr[attrname]="closedwon";
                    }
                    if(attrname=='allocation_matrix'){
						$(".multiselect ul li input[type=checkbox]").each(function(){
							if($(this).prop("checked")==true){
								attrvalue2.push($(this).val());
							}
						});
                        attrvalue=attrvalue2.toString();
						usernames=attrvalue.replace(/,/g, ":");
                        if(usernames.length ==0){
                            checked_checkbox.closest('tr').find('.error-alert').text(error_msg+" is required.");
						    succ=0;
                        }

                    }else if(attrname=='max_permission'){
						var attrvalue1=attrvalue+"-"+$("input[name=timeframe]:checked").val();
						testarr[attrname]=attrvalue1;
                    }else{
                        testarr[attrname]=attrvalue;
                    }

					if(( attrvalue == ""  || attrvalue =="0")){
						if(error_msg == "Document Upload"){
							error_msg ="Document name";
						}
						checked_checkbox.closest('tr').find('.error-alert').text(error_msg+" is required.");
						succ=0;
					}else{
						if(error_msg == "Max Permissible Timeframe"){
							if(number_chk.test(attrvalue) == false){
								checked_checkbox.closest('tr').find('.error-alert').text("Input should be Numbers only.");
								succ=0;
							}else{
								checked_checkbox.closest('tr').find('.error-alert').text("");
							}
						}else{
							checked_checkbox.closest('tr').find('.error-alert').text("");
						}
					}
				}else{
                   var attrvalue=$('#attr_value'+i).val();
                   var attrname=checked_checkbox.val();
                   /*if(attrname=="value" || attrname=="numbers" || attrname=="expected_close_date"){
					        stageArrtibute2[attrname]=attrvalue;
                    }*/
				}
                i++;
			});
            /* ------------------------------ */
            var check_count = document.getElementsByClassName("custom_checkbox_class");

            for(var j=0;j<check_count.length;j++){
                var check_val = check_count[j].value;
                attrvalue=$('#attr_'+check_val).val();
                attrname=check_val;
              if(check_count[j].checked){
                if(attrvalue == ""){
                    $('#'+check_val).closest('tr').find('.error-alert').text(attrname+" is required.");
				    succ=0;
                }else{
                    $('#'+check_val).closest('tr').find('.error-alert').text("");
                    testarr[attrname]=attrvalue;
                    stageArrtibute1[attrname]=attrvalue;
                }
              }
              stageArrtibute2[attrname]="";
            }
            /* ------------------------------ */
            addObj.hid_stageid=hid_stageid;
            addObj.hid_cycleid=hid_cycleid;
            addObj.hid_stage_seq=hid_stage_seq;
            addObj.stradd_attr_value=testarr;
            addObj.ownervalue=usernames;
            addObj.val_namearr=stageArrtibute1;
            addObj.val_namearr1=stageArrtibute2;
            //addObj.allowclosedwon=$("#allowclosedwon");
            console.log(addObj);
			if(succ==0){
				$("#editsave_btn").prop('disabled', false);
				return;
			}else{
			    loaderShow();
			    $.ajax({
        				type : "POST",
        				url : "<?php echo site_url('admin_sales_stage_flowchartController/post_data'); ?>",
        				dataType : 'json',
                        data: JSON.stringify(addObj),
        				cache : false,
        				success : function(data){
							if(error_handler(data)){
							return;
						}
							$("#editsave_btn").prop('disabled', false);
						        var row1="";

								for(var k=0; k < data[0].stagedata.length; k++ ){
									var rowdata = JSON.stringify(data[0].stagedata[k]);
									
									var remarks = "";
									var jsondata=JSON.stringify([{"CYCLE_ID":data[0].CYCLE_ID,"stage_name":data[0].stagedata[k].stage_name,"stage_id":data[0].stagedata[k].stage_id,"remarks":data[0].stagedata[k].remarks,"stg_seq":data[0].stagedata[k].stage_sequence}]);
									if(data[0].stagedata[k].remarks == null){
										remarks = "";
									}else{
										remarks = data[0].stagedata[k].remarks;
									}
									/* ------------next previous stage ------------------- */
									var next="";
									var prev="";
									if(0<k && k<(data[0].stagedata.length-1)){
										prev = data[0].stagedata[(k-1)].stage_name;
										next = data[0].stagedata[(k+1)].stage_name;
									}
									if(k==0){
    									if(data[0].stagedata.length==1){
    										next="";
    									}else{
    										next = data[0].stagedata[(k+1)].stage_name;
    									}
    								}
    								if(k== data[0].stagedata.length-1){
    									if(data[0].stagedata.length==1){
    										prev = "";
    									}else{
    										prev = data[0].stagedata[(k-1)].stage_name;
    									}

    								}
									/* ---------------------------------------------------- */
									
									row1 += "<tr id='"+data[0].stagedata[k].stage_id+"'><td>" + (k+1) + "</td><td class='stage-name'>" + data[0].stagedata[k].stage_name + "</td><td >" + remarks + "</td><td><a data-toggle='modal' href='#stageAttribute'  onclick='selrow("+jsondata+","+rowdata+",\""+prev+"\",\""+next+"\")'><span class='glyphicon glyphicon-pencil'></span></td></tr>";
								}

								$("#"+data[0].CYCLE_ID+" table tbody").html(row1);
                          cancel1();
                          loaderHide();
        				}
                });
			}

		}

/* -------------------------------------------------------------------------------------------------------------------------------------------------------------- */

		function cancel1(){
			$('.modal').modal('hide');
			$('.modal .error-alert').text("");
			$('.modal input[type="text"],input[type="password"], textarea').val('');
            $('.modal select').val($('.modal select option:first'));
			$('.modal input[type="radio"]').prop('checked', false);
			$('.modal input[type="checkbox"]').prop('checked', false).prop('disabled', false);
            $("#hid_attr_value2").val("");
            $("#hid_attr_value3").val("");
            $("#hid_attr_value4").val("");
            savedvalArr={};
            $("#stageAttributeFrm input[type=text],#stageAttributeFrm input[type=radio],#stageAttributeFrm select").prop('disabled', true);

			    $("#allocationMatrix,#qualifier,#documentUpload,#actionButton,#max_permission").each(function(){
					var checked_checkbox = $(this);
					checked_checkbox.change(function(){
						if(checked_checkbox.prop('checked')==true){
							checked_checkbox.closest('tr').find('input[type=text],input[type=radio],select').removeAttr('disabled');
						}else if(checked_checkbox.prop('checked')==false){
							checked_checkbox.closest('tr').find('input[type=text],input[type=radio],select').prop('disabled', true);
							/* making reset right side corrocponding input element once user unchecked the left side checkbox--------- */
							if(checked_checkbox.val()== "max_permission"){
								checked_checkbox.closest('tr').find('input[type=radio]').prop('checked', false);
							}
							checked_checkbox.closest('tr').find('input[type=text],input[type=radio]').val("");

						}
					})
			    });
                $('input.custom_checkbox_class').click(function(){
                  var checked_cb = this.id;
                        $("#attr_"+checked_cb).closest('tr').find('input[type=text]').prop('disabled', true).val('');
                        $("#hid_attr_"+checked_cb).closest('tr').find('input[type=hidden]').prop('disabled', true).val('');
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
							<div>
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Stage Attributes"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Stage_Attributes', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Stage Attributes</h2>
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
					</div>
					<div style="clear:both"></div>
				</div>
			</div>
			<div id="main_data_content">
				<div class="row">
					<div class="col-xs-3 verticle-tab">
						<ul class="nav nav-tabs tabs-left" id="CYCLE_NAME">
						</ul>
					</div>
					<div class="col-xs-9 tab-col" >
						<br>
						<div class="tab-content" id="stagedata">
						</div>
					</div>
				</div>
			</div>
			<div class="none" id="no_data_section"></div>
            <input type="hidden" id="hid_stageid" name="hid_stageid" />
            <input type="hidden" id="hid_cycleid" name="hid_cycleid" />
            <input type="hidden" id="hid_stage_seq" name="hid_stage_seq" />
			<div id="stageAttribute" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="stageAttributeFrm" class="form" name="adminClient">
							<div class="modal-header">
								<span class="close" onclick="cancel1()">x</span>
								<h4 class="modal-title">Stage Attributes For <span id="stage_name_t"></span></h4>
							</div>
							<div class="modal-body">
								<table width="100%">
									<tr>
										<td colspan="2">
											<center><h4><b>Description</b></h4></center>
											<center><p id="stage_remarks"></p></center>
										</td>
									</tr>
									<tr>
										<td>
											<center>
											<span class="text-center">Entry Stage :</span>
											<span id="stage_entry_point"></span>
											</center>
										</td>
									</tr>
								</table></br>
								<table class="table" id="workingTable">
									<thead>
										<tr>
											<th>Attribute</th>
											<th>Attribute Values</th>
                                            <th></th>
										</tr>
									</thead>
									<tbody>

										<tr>
											<td>
												<label for="allocationMatrix">
													<input type="checkbox" value="allocation_matrix" id="allocationMatrix" />
													Allocation Matrix
												</label>
											</td>
											<td style="text-align:left">
												<div id="attr_value1" class="multiselect" >
													<ul>
													</ul>													
												</div>
												<span class="error-alert"></span>
											</td>
                                            <td>
                                                <span class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" id="glyph_doc_remarks" data-original-title="Choose the user(s) that can work on this stage of the sales cycle." ></span>
                                            </td>
										</tr>
                                        <tr>
											<td>
												<label for="qualifier">
													<input type="checkbox" name="approve7" value="qualifier" id="qualifier" /> Qualifier
												</label>
											</td>
											<td>
												<select class="form-control" type="text" style="display: none"  id="attr_value2" >

												</select>
                                                <select class="form-control" type="text"   id="hid_attr_value5" >
												</select>

												<span class="error-alert"></span>
											</td>
                                            <td>
                                                <span class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" id="glyph_doc_remarks" data-original-title="The selected qualifier will appear before entering this sales stage" ></span>
                                            </td>

										</tr>

										<tr>
											<td>
												<label for="documentUpload">
													<input type="checkbox" name="approve7" value="document_upload" id="documentUpload" /> Document Upload
												</label>
											</td>
											<td>
												<input class="form-control" type="text"  id="attr_value3" placeholder="Remarks" size="45">
												<span class="error-alert"></span>
											</td>
											<td>
											</td>

										</tr>
										<tr>
											<td>
												<label for="actionButton">
													<input type="checkbox" name="approve7" value="action_button" id="actionButton"/> Action Button
												</label>
											</td>
											<td>
												<select class="form-control" type="text" id="attr_value4" >
												</select>
												<span class="error-alert"></span>
											</td>
                                            <td>
                                                <span class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" id="glyph_doc_remarks" data-original-title="Upon rejection of this stage, it will automatically go back to the stage selected here" ></span>
                                            </td>
										</tr>
                                        <!-- --------- 18.08.2017 for version 0.1 -->
                                        <tr>
                                            <td>
                                               	<label for="max_permission">
											    <input type="checkbox" name="approve7" value="max_permission" id="max_permission"/> Max Permissible Timeframe
												</label>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-3"><label for="days">Days</label> <input type="radio" id="days" name="timeframe" value="1"></div>
                                                    <div class="col-md-3"><label for="hours"> Hours</label> <input type="radio" id="hours" name="timeframe" value="0"></div>
                                                    <div class="col-md-6">
													<input type="text" name="dys_hrs" id="attr_value5" class="form-control">

													<span class="error-alert"></span>
													</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" id="glyph_doc_remarks" data-original-title="This is the maximum time a user can spend on this sales stage from the time they enter it." ></span>
                                            </td>
                                        </tr>

									</tbody>

                                    <tbody id="custom_fields">
                                        <tr>
											<td>
												<label for="amount">
													<input onchange="renderSavedValues('amount')" type="checkbox" name="approve4" value="amount" id="amount" class="custom_checkbox_class" /> Amount
												</label>
											</td>
											<td>
												<input class="form-control default-disabled" type="text"  id="attr_amount" placeholder="Reference Text" size="45">
												<input class="form-control" type="hidden"  id="hid_attr_amount" placeholder="Reference Text" size="45">
												<span class="error-alert"></span>
											</td>

										</tr>
										<tr>
											<td>
												<label for="quantity">
													<input onchange="renderSavedValues('quantity')" type="checkbox" name="approve5" value="quantity" id="quantity" class="custom_checkbox_class" /> Quantity
												</label>
											</td>
											<td>
												<input class="form-control default-disabled" type="text"  id="attr_quantity" placeholder="Reference Text" size="45">
												<input class="form-control" type="hidden"  id="hid_attr_quantity" placeholder="Reference Text" size="45">
												<span class="error-alert"></span>
											</td>
										</tr>
										<tr>
											<td>
												<label for="expected_close_date">
													<input onchange="renderSavedValues('expected_close_date')" type="checkbox" name="approve6" value="expected_close_date" id="expected_close_date" class="custom_checkbox_class" /> Expected Close Date
												</label>
											</td>
											<td>
												<input class="form-control default-disabled" type="text"  id="attr_expected_close_date" placeholder="Reference Text" size="45">
												<input class="form-control" type="hidden"  id="hid_attr_expected_close_date" placeholder="Reference Text" size="45">
												<span class="error-alert"></span>
											</td>

										</tr>
                                        <!-- ----------------------------- 15/11/2017 -------------------- -->
                                        <tr>
											<td>
												<label for="rate">
													<input onchange="renderSavedValues('rate')" type="checkbox" name="approve8" value="rate" id="rate" class="custom_checkbox_class" /> Rate
												</label>
											</td>
											<td>
												<input class="form-control default-disabled" type="text"  id="attr_rate" placeholder="Reference Text" size="45">
												<input class="form-control" type="hidden"  id="hid_attr_rate" placeholder="Reference Text" size="45">
												<span class="error-alert"></span>
											</td>

										</tr>

                                        <tr>
											<td>
												<label for="score">
													<input onchange="renderSavedValues('score')" type="checkbox" name="approve9" value="score" id="score" class="custom_checkbox_class" /> Score
												</label>
											</td>
											<td>
												<input class="form-control default-disabled" type="text"  id="attr_score" placeholder="Reference Text" size="45">
												<input class="form-control" type="hidden"  id="hid_attr_score" placeholder="Reference Text" size="45">
												<span class="error-alert"></span>
											</td>

										</tr>

                                        <tr>
											<td>
												<label for="cust_code">
													<input onchange="renderSavedValues('cust_code')" type="checkbox" name="approve10" value="cust_code" id="cust_code" class="custom_checkbox_class" /> Customer Code
												</label>
											</td>
											<td>
												<input class="form-control default-disabled" type="text"  id="attr_cust_code" placeholder="Reference Text" size="45">
												<input class="form-control" type="hidden"  id="hid_attr_cust_code" placeholder="Reference Text" size="45">
												<span class="error-alert"></span>
											</td>

										</tr>
                                        <tr>
											<td>
												<label for="priority">
													<input onchange="renderSavedValues('priority')" type="checkbox" name="approve10" value="priority" id="priority" class="custom_checkbox_class" /> Priority
												</label>
											</td>
											<td>
												<input class="form-control default-disabled" type="text"  id="attr_priority" placeholder="Reference Text" size="45">
												<input class="form-control" type="hidden"  id="hid_attr_priority" placeholder="Reference Text" size="45">
												<span class="error-alert"></span>
											</td>

										</tr>
                                        <tr>
											<td>
												<label for="allowclosedwon">
													<input type="checkbox" name="allowclosedwon" value="closedwon" id="allowclosedwon"/> Allow Closed Won
												</label>
											</td>
											<td>
                                                <input class="form-control" type="hidden" id="attr_value6" value="closedwon" />
												<!--<select class="form-control" type="text" id="attr_value6" >
												</select>
												<span class="error-alert"></span>-->
											</td>
										</tr>
                                    </tbody>

								</table>
								<table width="100%">
									<tr>
										<td>
											<center>
											<span class="stage">Exit Stage :</span>
											<span id="stage_exit_point"></span>
											</center>
										</td>
									</tr>
								</table>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" onclick="cancel1()">Cancel</button>
								<button type="button" class="btn btn-primary" id="editsave_btn" onclick="editsave()" >Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php require 'footer.php' ?>
	</body>
</html>
