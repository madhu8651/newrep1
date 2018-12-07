
<!DOCTYPE html>
<html lang="en">
	<head>
	<?php $this->load->view('scriptfiles'); ?>
            <script>
                var base_url = '<?php echo base_url(); ?>';
            </script>

	<style>
.lead_qualifier_nameE .error-alert{
	position: absolute;
    width: 310px;
    padding: 5px;
    left: -51px;
    font-size: 10px;
    background: #FFF;
    text-align: center;
	}
.lead_qualifier_nameE .glyphicon.glyphicon-saved{
	border-radius: 0px;
    border: 1px solid;
    margin-top: 0px;
    position: absolute;
    top: 10px;
    right: 10px;
    background: white;

}
.lead_qualifier_nameE input[type=text]{
	    height: 25px;
}
.item.ui-sortable{
	padding: 15px;
    height: 360px;
    overflow: auto;
    width: 100%;
}
.que-container {
   margin-top: -20px;
}
.delete-row{
	width: 20px;
    height: 20px;
    border: 1px solid;
    float: right;
    padding: 2px;
	margin-top: -23px;
    margin-right: -2px;
}

.li-shortable{
	min-height: 50px;
    border: 1px solid #ccc;
    box-shadow: 0px 3px 12px #ccc;
    padding: 5px;
}
.alert.alert-warning input{
	color:#000 !important;
}
.que-container{
	width:90%;
	float:left;
}
.clear-both{
    clear: both;
    right: 16px;
    height: 20px;
    margin-right: -20PX;
    margin-bottom: 3px;
}
#addAnswerTable td{
	padding:0px;
	border: 0px;
	line-height: 32px;
}
#addAnswerTable .delete-row.glyphicon {
	       margin-top: 6px;
			margin-right: 0px;
			border: 1px solid;
			line-height: 10px;
			padding: 4px 3px;
}
.save-cancel-edit{
	    text-align: right;
		height: 0px;
		line-height: 30px;
}
.save-cancel-edit .glyphicon{
	height: 20px;
    width: 20px;
    font-size: 10px;
    padding: 0px;
    line-height: 18px;
    text-align: center;
    margin-right: 18px;
    border:1px solid;
}
.qualifier_type li{
	list-style:none;
}
#questionlist li{
	margin-bottom: 5px;
}
.save-cancel-edit .fa-undo{
	margin-top: -6px;
}
.save-cancel-edit .glyphicon-saved{
	margin-right: 4px;
}

.que-container input[type=text] {
    width: 100%;
    height: 25px;
    outline: none;
    border: none;
    border-bottom: 1px solid #3c8dbc;
}
.ad_ans{
	border: 1px solid #ccc;
    padding: 5px;
    margin-top: 10px;
    min-width: 100px;
	cursor: pointer;
}
.que-container .ad_ans {
    margin-top: 0px;
    padding: 0px 5px;
	background: rgba(226, 219, 219, 0.3)
}
.que-container h5{
	margin-top: 0px;
    font-size: 16px;
}
#editmodal hr{
	margin:0px;
}

@-moz-document url-prefix() {
	.que-container {
		margin-top: -10px;
	}
	.delete-row{
		line-height: 14px;
		margin-top: -4px;
	}
	.que-container .ad_ans{
		margin-top: 7px;
	}
	
}
	</style>
	<script>
    var patt = new RegExp(/^[a-zA-Z0-9 &_]*$/);
	$(document).ready(function(){
        loadpage();
	});
	
	/* Validation : first character digit should not be any special cherecter*/
	function firstLetterChk1(name) {
		var nameReg = new RegExp(/^[a-zA-Z0-9 ]*$/);
		var valid = nameReg.test(name);
		if (!valid) {
			return false;
		} else {
			return true;
		}
	}
    /* --------------------- on load populate the table i.e qualifier data */
 function loadpage(){
	 $('#tablebody').parent("table").dataTable().fnDestroy();
    loaderShow();
    $.ajax({
    	type : "POST",
    	url : "<?php echo site_url('admin_qualifiersController/get_qualifier'); ?>",
    	dataType : 'json',
    	cache : false,
    	success : function(data){
    		if(error_handler(data)){
					return;
				}
    		$('.closeinput').val('');
    		$('#tablebody').empty();
    		var row = "";
    		for(i=0; i < data.length; i++ ){
    			var rowdata = JSON.stringify(data[i]);
                if(data[i].hasOwnProperty('stagedata')){

                  var str=cstr="";
                  var str1=cstr1="";
                  for(var a=0;a < data[i].stagedata.length; a++){
                      str+='<li style="text-align: left;">'+ data[i].stagedata[a].stage_name +'</li>';
                      str1+='<li style="text-align: left;">'+ data[i].stagedata[a].CYCLE_NAME +'</li>';
                  }

                }else{
                    var str="Unassigned";
                    var cstr="style='color: red'";
                    var str1="Unassigned";
                    var cstr1="style='color: red'";
                }

    			row += "<tr id='"+data[i].lead_qualifier_id+"'><td>" + (i+1) + "</td><td class='lead_qualifier_nameE'><span>" + data[i].lead_qualifier_name + "</span></td><td "+cstr+" ><ul>"+str+"</ul></td><td "+cstr1+"><ul>"+str1+"</ul></td><td><a data-toggle='modal' href='#editmodal'data-backdrop='static' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
    		}
    		$('#tablebody').append(row);
			$('#tablebody').parent("table").DataTable({
														"aoColumnDefs": [
															{ 	
																"bSortable": false, 
																"aTargets": [4] }
															]
													  });
            loaderHide();
			$(".lead_qualifier_nameE").each(function(){
				/* 
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
				*/
				var val = $(this).text();
				var IdVal = $(this).closest("tr").attr("id");
				var options = {
					content: '<div><input type="text" value="'+val+'"/><a class="glyphicon glyphicon-saved" title="Save" onclick="saveName(\''+val+'\' ,\''+IdVal+'\' )" ></a><br><i class="error-alert hidden"></i><div>',
					html: true,
					placement: 'bottom'
				};
				$(this).find('span').click(function(e){
					$('.popover').hide();
				})
				
				$(this).find('span').popover(options);
				
			})
			
    	}
	});

 }
 
	$('html').on('click', function(e) {
		if (typeof $(e.target).data('original-title') == 'undefined' && !$(e.target).parents().is('.popover.in')) {
			$('[data-original-title]').popover('hide');
		}
	});
 
	function saveName(name , rowId){
		if($.trim($('.lead_qualifier_nameE input').val()) == ""){
			$("#"+rowId).find(".lead_qualifier_nameE .error-alert").removeClass("hidden").text("Qualifier Name is required.");
			$(".lead_qualifier_nameE input").focus();
			$("#addsave1").prop('disabled', false);
			return;
		}else if(!patt.test($.trim($('.lead_qualifier_nameE input').val()))){
			$("#"+rowId).find(".lead_qualifier_nameE .error-alert").removeClass("hidden").text("Qualifier Name should be combination of Characters and Numbers.");
			 $(".lead_qualifier_nameE input").focus();
			$("#addsave1").prop('disabled', false);
			return;
		}else{
			$("#"+rowId).find(".lead_qualifier_nameE .error-alert").text("");
		}
		var addObj={};
		addObj.qualifierId = $.trim(rowId);
		addObj.qualifierName = $.trim($('.lead_qualifier_nameE input').val());

		$('[data-original-title]').popover('hide');
		 $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_qualifiersController/update_qualifiername'); ?>",
			dataType : 'json',
			data : JSON.stringify(addObj),
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
			  $("#addsave1").prop('disabled', false);
				if(data==0){
				    $.confirm({
                			title: 'Qualifier',
                			content: 'Qualifier Name Already Defined.',
                			animation: 'none',
                			closeAnimation: 'scale',
                			buttons: {
                				Ok: function () {
                					window.location.reload(true);
                				}
                			}
                	});

                    return;
				}else{
					 cancel();
					 loadpage();
				}
			}
		});
	}
	
   /* ------------------------------------------------------------------------------------------------------------------ */

   function loadques(qualid){
        addObj={};
        addObj.qualid=qualid;
        $.ajax({
                 type : "POST",
                 url : "<?php echo site_url('admin_qualifiersController/get_queansdata') ?>",
                 dataType : 'json',
                 data : JSON.stringify(addObj),
                 cache : false,
                 success : function(data){
					 if(error_handler(data)){
						return;
					}
					 $('#questionlist').empty();
                      var Qrow = "";
                      for(i=0; i < data.length; i++ ){
                                        if(data[i].mandatory_bit == 0){
                                           var str="";
                                        }
                                        else if(data[i].mandatory_bit == 1){
                                            var str="checked";
                                        }
                                          Qrow += "<li id='que"+ data[i].id+"' class='li-shortable row'><div class='que-container'><label class='ad_ans' for='aa"+data[i].id+"' ><input type='checkbox' "+str+" id='aa"+data[i].id+"' onchange='updatechk(\"aa"+data[i].id+"\",\""+data[i].question_id+"\")'/> Mandatory</label><h5>"+ data[i].question_text+"</h5><ol type='a'>";
                                          var rowdata1 = JSON.stringify(data[i]);
                                          if(data[i].hasOwnProperty('ansdata')) {

                                              for(j=0; j < data[i]['ansdata'].length; j++ ){
                                                  Qrow += "<li id="+data[i]['ansdata'][j].answer_id+">";
                                                  if(data[i].answer == data[i]['ansdata'][j].answer_text){
                                                  Qrow += "<b>" +data[i]['ansdata'][j].answer_text+"</b>";
                                                  }else{
                                                  Qrow += data[i]['ansdata'][j].answer_text;
                                                  }
                                                  Qrow += "</li>";
                                              }
                                          }

                                          
                                          /* 
										  Qrow += "</ol></div><div class='clear-both'><div class='save-cancel-edit' id='save-cancel"+i+"'><span title='Edit' onclick='edt(\""+data[i].id+"\","+i+")' class='glyphicon glyphicon-pencil'></span><span class='save-cancel'></span></div></div></li>"; 
										  ------------------------------------Changed on 10-08-2017 ---- Removed edit option  from question list view----------------------------------------------------
										  Qrow += "</ol></div><a href='#' onclick='delrow("+rowdata1+")' class='delete-row glyphicon glyphicon-remove-circle' title='Delete' ></a><div class='clear-both'><div class='save-cancel-edit' id='save-cancel"+i+"'><a title='Edit' onclick='edt(\""+data[i].id+"\","+i+")' class='glyphicon glyphicon-pencil'></a><a class='save-cancel'></a></div></div></li>";
										  */
										  Qrow += "</ol></div><a href='#' onclick='delrow("+rowdata1+")' class='delete-row glyphicon glyphicon-remove-circle' title='Delete' ></a><div class='clear-both'></div></li>";
										   

                                    }
                        $('#questionlist').append(Qrow);
                        $("#addQuestion").val("")
        			    $('#addAnswerTable .addAnsText').val("");
        			    $('#addAnswerTable tr').remove();
                        $('input[name=QualifierType1]').prop("checked", false);
						$("#addAnswer").removeAttr("onclick");
                        loaderHide();
                  }
            });


   }

   /* ------------------------------------------------------- on select of each row in a table, show the pop up and show the question and ans data */
   function selrow(obj){
        $('#addAnswer').hide();
           loaderShow();
           $(".error-alert").text("");
           $(".lead_qualifier_name").text(obj.lead_qualifier_name);
           $("#hiddenquaid").val("").val(obj.lead_qualifier_id);
           var quaid=obj.lead_qualifier_id
           var addObj={};
           addObj.quaid =quaid;

           $('#mandatorychk').prop("checked", true);
           $('#questionlist').empty();
           var Qrow = "";

           /* --------------------- populate the question and ans data on load of pop up window-------------------------------- */
            loadques(quaid);
            /* -------------------------------------------------------------------------------------------------------------------- */

           /* --------------removed------------------- on click of save question button , save the question and ans ---------------------------------- 
            $("#answerSave").click(function(){
					
            });
			*/
            loaderHide();
   }
/* --------------------------------- on click of save question button , save the question and ans ---------------------------------- */
function answerSaveFunc(){
	$(".error-alert").text("");
	var quaid;
	var question_answer_text ={};
	var answer_text =[];
	var success = 1;
	question_answer_text.quaid=$("#hiddenquaid").val();
	if($("#mandatorychk").prop('checked')==true){
		var manbit=1;
	}else{
		var manbit=0;
	}

	if(!$("#QualifierType1").is(':checked') &&!$("#QualifierType2").is(':checked') &&!$("#QualifierType3").is(':checked')){
		$("#QualifierType1").closest("div").find("span").text("Select type of Question.");
		return;
	}else{
		$("#QualifierType1").closest("div").find("span").text("");
		question_answer_text.questiontype = $("input[name='QualifierType1']:checked").val();

	}

	if($.trim($("#addQuestion").val()) == ""){
		$("#addQuestion").closest("div").find("span").text("Question is required.");
		return;
	}else if(!firstLetterChk1($.trim($("#addQuestion").val().substring(0, 2)))) {
		$("#addQuestion").closest("div").find("span").text("First two characters should not be Special character.");
		$("#addQuestion").focus();
		return;
	}else{
		$("#addQuestion").closest("div").find("span").text("");
		question_answer_text.question_text = $.trim($("#addQuestion").val());
	}
	
	var cnt=0;
	$('#addAnswerTable .addAnsText').each(function(){
		cnt=cnt+1;
		if($.trim($(this).val()) == ""){
			success = 0;
		}else if(!firstLetterChk1($.trim($(this).val().substring(0, 2)))) {
			success = 10;
		}

		answer_text.push({"answer_text":$.trim($(this).val())});
	});

	/* Check  duplicate answer  --starts*/
	var duplicate=false;
	$('#addAnswerTable .addAnsText').each(function(){
		var $this = $(this);
		if ($this.val()===''){ return;}
		$('#addAnswerTable .addAnsText').not($this).each(function(){
			if ( $(this).val().toLowerCase()==$this.val().toLowerCase()) {duplicate=true;}
		});
	});

	if(duplicate){
		$("#addAnswerError").text("Duplicate answers not allowed");
		return;
	}else{
		$("#addAnswerError").text("");
	}
	/* Check  duplicate answer  --ends*/

	if(success == 0){
			$("#addAnswerError").text("Add your answer.");
			answer_text=[];
			return;
	}else if (success == 10){
		$("#addAnswerError").text("First two characters should not be Special character.");
			answer_text=[];
			return;
	}else{
			$("#addAnswerError").text("");
			question_answer_text.answer_text = answer_text;
	}

	if(($("#addAnswerTable tr").length) == 0){
			if(question_answer_text.questiontype != 3){
				if(question_answer_text.questiontype == 1){
					$("#addAnswerError").text("Add your answer.");
					$('#addAnswerTable').append("<tr><td><input type='radio' name='addRightAns'/></td><td><div><input type='text' class='form-control addAnsText'/></div></td><td><a href='#' id='ans1' class='delete-row glyphicon glyphicon-remove-circle' onclick='del(this.id)'></a></td></tr>");
					return;
				}else if (question_answer_text.questiontype == 2){
						$("#addAnswerError").text("Add your answer.");
						$('#addAnswerTable').append("<tr><td><div><input type='text' class='form-control addAnsText'/></div></td><td><a href='#'  id='ans1' class='delete-row glyphicon glyphicon-remove-circle' onclick='del(this.id)'></a></td></tr>");
						return;
				}
			}

	}else{
			if(($("#addAnswerTable input[name='addRightAns']").length) > 0){
				if(!$("#addAnswerTable input[name='addRightAns']").is(':checked')){
						$("#addAnswerError").text("Please choose atleast one option.");
						
						return;
				}else{
						$("#addAnswerError").text("");
				}
			}
		  question_answer_text.answer =$.trim($("#addAnswerTable input[name='addRightAns']:checked").closest("tr").find(".addAnsText").val());
   }
	question_answer_text.manbit=manbit;
	$("#answerSave").prop('disable', true);
	loaderShow();
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('admin_qualifiersController/post_queans'); ?>",
		data : JSON.stringify(question_answer_text),
		cache : false,
		success : function(data1){
			if(error_handler(data1)){
				return;
			}
		  $("#answerSave").prop('disabled', false);
			if(data1==0){
				$("#addQuestion").closest("div").find("span").text("Question Already Present.");
				loaderHide();
				return;
			}else{
				var data = JSON.parse(data1);
				loadques($("#hiddenquaid").val());
				$("#addQuestion").closest("div").find("span").text("");
			}

		}
	});
}
			
var questionreset='';
function edt(id,index){
	questionreset = $("#que"+id).html();
	$("#que"+id+ " .glyphicon.glyphicon-pencil").hide();
	$("#que"+id+ " .save-cancel").show();
	$("#save-cancel"+index+ " .save-cancel").html("<span onclick='saveQ("+id+")' class='glyphicon glyphicon-saved' title='Save'></span><span onclick='cnclQ("+id+")' title='Undo' style='position: initial'class='glyphicon fa fa-undo'></span>");

	$("#que"+id).find("h5").html("<input type='text' value='"+$("#que"+id).find("h5").text()+"'/>");
    $("#que"+id).find("h5 input[type=text]").focus()
	var edtHtml = "";
	$("#que"+id+ " ol li").each(function(){

		edtHtml +="<li id="+$(this).attr('id')+"><input type='text' value='"+$(this).text()+"'/></li>"
	});

	$("#que"+id+ " ol").html(edtHtml);
}
function saveQ(id){
    loaderShow();
	var succ=1;
	$("#que"+id+" input[type=text]").each(function(){
		if($(this).val()== ""){
			$("#que"+id).css("background-color","rgb(255, 230, 230)");
			succ=0;
		}
	})
	if(succ==0){
		return;
	}else{

		/* Check  duplicate answer  --starts*/
		var duplicate=false;
		$("#que"+id+" ol li input[type=text]").each(function(){
			var $this = $(this);
			if ($this.val()===''){ return;}
			$("#que"+id+" ol li input[type=text]").not($this).each(function(){
				if ( $(this).val().toLowerCase()==$this.val().toLowerCase()) {duplicate=true;}
			});
		});

		if(duplicate){
			$("#que"+id).css("background-color","rgb(255, 230, 230)");
            loaderHide();
			return;
		}else{
			$("#que"+id).removeAttr("style");
		}
		/* Check  duplicate answer  --ends*/
	    var jObj={};
		$("#que"+id).removeAttr("style");

		var Qtxt = $.trim($("#que"+id).find("h5 input[type=text]").val());
		$("#que"+id+ " .save-cancel").hide();
		$("#que"+id+ " .glyphicon.glyphicon-pencil").show();
		$("#que"+id).find("h5").html(Qtxt);
		var edtHtml = "";
		var Atxt = [];
		$("#que"+id+ " ol li input[type=text]").each(function(){
			edtHtml +="<li>"+$(this).val()+"</li>";

			Atxt.push({"AId":$(this).closest("li").attr("id"),"txt":$.trim($(this).val())});
		});

		$("#que"+id+ " ol").html(edtHtml);
        jObj.qId=id;
        jObj.qualid=$("#hiddenquaid").val();
        jObj.qtxt=Qtxt;
        jObj.atxt=Atxt;
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_qualifiersController/update_queans'); ?>",
			dataType : 'json',
			data : JSON.stringify(jObj),
			cache : false,
			success : function(data){
				if(error_handler(data)){
								return;
							}
                if(data==0){
                    $.confirm({
                			title: 'Qualifier',
                			content: 'Question  Already Present.',
                			animation: 'none',
                			closeAnimation: 'scale',
                			buttons: {
                				Ok: function () {
                					window.location.reload(true);
                				}
                			}
                    });

                  loadques($("#hiddenquaid").val());
                }else{
                  loadques($("#hiddenquaid").val());
                }

			}
		});
	}
}
function cnclQ(id){
$("#que"+id).removeAttr("style");
$("#que"+id).html(questionreset);
}

   /* --------------------------------------------- end of select row function ---------------------------------------------------------- */
   $(function() {
			$( "#questionlist" ).sortable({
				placeholder: "ui-state-highlight"
			});
   });

   var counter=0;
   function QualifierType(type){
			$('#addAnswerTable tr').remove();
			if(type == 1 || type == 2){
				addAnswer(type);
				$('#addAnswer').show();
				$('#addAnswer').attr("onclick","addAnswer("+type+")");
			}
			if(type == 3){
				$('#addAnswer').removeAttr("onclick");
				$('#addAnswer').hide();
			}
   }

   function addAnswer(type) {
            $("#addAnswerError").text("");
			var html = "";
			counter += 1;
			html += "<tr>";
			if(type == 1){
				html += "<td>";
				html += "<input type='radio' name='addRightAns'/>";
				html += "</td>";
			}
			html += "<td>";
			html += "<div>";
			html += "<input type='text' class='form-control addAnsText'/>";

			html += "</div>";
			html += "</td>";

            html += "<td>";
        	html += "<a href='#' id='ans"+ counter +"' class='delete-row glyphicon glyphicon-remove-circle' onclick='del(this.id)'></a>";
        	html += "</td>";

			html += "</tr>";
			$('#addAnswerTable').append(html);
   }
   function del(counter){

			$("#"+counter).closest("tr").remove();
   }

   /* ------------------------------------------- delete each question function ------------------------------------------------- */

   function delrow(obj){

            var questionid=obj.question_id;

            var addObj={};
            addObj.questionid=questionid;
            loaderShow();
            $.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_qualifiersController/delete_question'); ?>",
				data : JSON.stringify(addObj),
				cache : false,
				success : function(data1){
					if(error_handler(data1)){
								return;
							}
                    loaderHide();
        			var data = JSON.parse(data1);
                    loadques($("#hiddenquaid").val());

        			$("#addQuestion").val("")
        			$('#addAnswerTable .addAnsText').val("");
        			$('#addAnswerTable tr').remove();
                    $('input[name=QualifierType1]').prop("checked", false);

				}
			});

        }

		/* -------------------------------------------------------------------------------------------------------------------------------------------- */


        /* --------------------------------- update mandatory check ---------------------------------------------- */
            function updatechk(id,question_id){
                var manbit=0;
                if($("#"+id).prop('checked')==true){
                    manbit=1;
                }else{
                    manbit=0;
                }

                var addObj={};
                addObj.questionid=question_id;
                addObj.manbit=manbit;

                loaderShow();
                $.ajax({
    				type : "POST",
    				url : "<?php echo site_url('admin_qualifiersController/update_mandatorychk'); ?>",
    				data : JSON.stringify(addObj),
    				cache : false,
    				success : function(data1){
						if(error_handler(data1)){
								return;
							}
                        loaderHide();
            			var data = JSON.parse(data1);
                        loadques($("#hiddenquaid").val());

            			$("#addQuestion").val("")
            			$('#addAnswerTable .addAnsText').val("");
            			$('#addAnswerTable tr').remove();
                        $('input[name=QualifierType1]').prop("checked", false);

    				}
    			});

            }

        /* ------------------------------------------------------------------------------------------------------------- */

        /* ---------------------- function to save the row order of questions and answer list ---------------------------------------- */
		function saveOrder() {
            loaderShow();
		    $("#save").prop('disabled', true);
			var selectedLanguage =[];
			$('#questionlist li.li-shortable').each(function() {
                selectedLanguage.push({"roworder":$.trim($(this).attr("id").replace("que",""))});
			});
			var addObj={};
            var qualid=$("#hiddenquaid").val();
            addObj.orderselected=selectedLanguage;
            addObj.qualid=qualid;

			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_qualifiersController/update_roworder'); ?>",
				data : JSON.stringify(addObj),
				cache : false,
				success : function(data1){
							if(error_handler(data1)){
								return;
							}
                             $("#save").prop('disabled', false);
                            var data = JSON.parse(data1);
                            loadques(qualid);
                         /*   reset add Question form form----      */
        			        $("#addQuestion").val("")
        			        $('#addAnswerTable .addAnsText').val("");
        			        $('#addAnswerTable tr').remove();
                            $('input[name=QualifierType1]').prop("checked", false);
                            $('.modal').modal('hide');
				}
			});
		}
       /* -------------------------------------------------------------------------------------------------------------------------------------- */
		function addpopup(){
			$.ajax({
				type : "POST",
				url : "",
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
								return;
							}
					var select = $("#addRegion"), options = '<option value="">Select</option>';
					select.empty();
					for(var i=0;i<data.length; i++){
						options += "<option value='"+data[i].regionid+"'>"+ data[i].regionname +"</option>";
					}
					select.append(options);
				}
			});
		}
		/* ----------------------------------------- save qualifier function ------------------------------------------------------------------ */
		function addsave(){
		   $("#addsave1").prop('disabled', true);
				if($.trim($("#QualifierName").val()) == ""){
					$("#QualifierName").closest("div").find("span").text("Qualifier Name is required.");
                    $("#QualifierName").focus();
                    $("#addsave1").prop('disabled', false);
					return;
				}else if(!patt.test($.trim($("#QualifierName").val()))){
					$("#QualifierName").closest("div").find("span").text("Qualifier Name should be combination of Characters and Numbers.");
                     $("#QualifierName").focus();
                    $("#addsave1").prop('disabled', false);
					return;
				}else{
					$("#QualifierName").closest("div").find("span").text("");
				}
				var addObj={};
				addObj.qualifiername = $.trim($("#QualifierName").val());

				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_qualifiersController/post_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						if(error_handler(data)){
								return;
							}
						$("#addsave1").prop('disabled', false);
                        if(data==0){
                        $("#QualifierName").closest("div").find("span").text("Qualifier Name Already Present.");
				        return;
                        }else{
                             $("#QualifierName").closest("div").find("span").text("");
    						 cancel();
    						 loadpage();
                        }
					}
				});
			};

		/* ----------------------------------------------------------------------------------------------------------------------------------------- */
		function openNav() {
			$("#mySidenav").css({"width": "50%", "overflow": "none","transition": "0.5s","display": "block"});
			$("#mainsec").css({"width": "50%"});

			$("#openNavelm").hide();
			$("#closeNavelm").show();
		}

		function closeNav() {
			$("#mySidenav").css({"display": "none","width": "0px", "overflow": "hidden","transition": "0.5s"});
			$("#mainsec").css({"width": "100%"});
			$("#openNavelm").show();
			$("#closeNavelm").hide();
		}
	function cancel(){
		$('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first'));
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
        $(".error-alert").text("");
        $("#mainsec").css("width", "50%");
        $("#mySidenav").css({"display": "block","width": "50%" });
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
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Qualifier list"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Qualifiers', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Qualifier list</h2>
					</div>
					<div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<a href="#addmodal" class="addPlus" data-toggle="modal" onclick="addpopup()" >
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" width="30px" height="30px"/>
							</a>
					</div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<table id="tableTeam" class="table">
					<thead>
						<tr>
							<th style="color: red">Sl No</th>
							<th>Qualifier Name</th>
                            <th>Sales Stage</th>
                            <th>Sales Cycle</th>
                            <th>Edit</th>
						</tr>
					</thead>
					<tbody id="tablebody">
					</tbody>
				</table>
			</div>
			<div id="editmodal" class="modal fade" data-backdrop="static" data-keyboard="false">
                <input type="hidden" id="hiddenquaid" />
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<span class="close" onclick="cancel()">x</span>
							<h4 class="modal-title">Edit</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div id="mainsec" class="col-md-6">
									<center>
										<h4 class="modal-title">Questions for <span class="lead_qualifier_name"></span></h4>
										<hr>
									</center>
									<div style="text-align:right">
										<span id="closeNavelm" onclick="closeNav()"><i class="glyphicon glyphicon-resize-full"></i></span>
										<span id="openNavelm" onclick="openNav()" style="display:none;"><i class="glyphicon glyphicon-resize-small"></i></span>
									</div>
									<ol type="1" class="item" id="questionlist">
									</ol>
								</div>
								<div id="mySidenav" class="col-md-6">
									<center>
									<h4 class="modal-title">Add Questions for <span class="lead_qualifier_name"></span></h4>
									<hr>
									</center>
									<div class="row que-ans-sec">
										<div class="col-md-3">
											<label for="addQuestion">Qualifier Type*</label>
											
											<label class="ad_ans" for="mandatorychk"><input type="checkbox" id="mandatorychk" checked="checked"  value="1" name="mandatorychk"/> Mandatory</label>
										</div>
										<div class="col-md-9">
											<ul class="qualifier_type">
												<li>
													<input type="radio" id="QualifierType1" onclick="QualifierType(1)" value="1" name="QualifierType1"/>
													<label for="QualifierType1"> Multiple Choice with validations</label>
												</li>
												<li>
													<input type="radio" id="QualifierType2" onclick="QualifierType(2)" value="2" name="QualifierType1"/>
													<label for="QualifierType2"> Multiple Choice without validations</label>
												</li>
												<li>
													<input type="radio" id="QualifierType3" onclick="QualifierType(3)" value="3" name="QualifierType1"/>
													<label for="QualifierType3"> Text Entry</label>
												</li>
											</ul>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row que-ans-sec">
										<div class="col-md-3">
											<label for="addQuestion">Question*</label>
										</div>
										<div class="col-md-9">
											<input type="text" id="addQuestion" class="form-control" name="addQuestion" autofocus/>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="row que-ans-sec">
										<div class="col-md-3" style="padding-top: 6px;">
											<a href="#" id="addAnswer" class="ad_ans" style="display:none;">
												<label ><span class="glyphicon glyphicon-plus">
												</span>Answer</label>
											</a>
										</div>
										<div class="col-md-9">
											<table id="addAnswerTable" class="table" style="margin-top: 0px !important;margin-bottom: 0px;">
											</table>
											<div id="addAnswerError" class='error-alert'></div>
											<center>
												<a href="#" type="button" onclick="answerSaveFunc()" id="answerSave" class="btn">Save Question</a>
											</center>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" onclick="saveOrder()" id="save" >Save Question Order</button>
							<button class="btn btn-default" onclick="cancel()">Cancel</button>
						</div>
					</div>
				</div>
			</div>
			<div id="addmodal" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
					<div class="modal-content">
						<div name="addTeamModal" class="form">
							<div class="modal-header">
								<span class="close" onclick="cancel()">x</span>
								<h4 class="modal-title">Add Qualifier</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-lg-4 col-md-6">
										<label for="QualifierName">Qualifier Name *</label>
									</div>
									<div class="col-lg-8 col-md-6">
										<input type="text" id="QualifierName" class="form-control" name="QualifierName" autofocus/>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" onclick="addsave()" class="btn btn-primary" id="addsave1" value="Save"/>
								<input type="button" class="btn btn-default" onclick="cancel()" value="Cancel"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php require 'footer.php' ?>
	</body>
</html>
