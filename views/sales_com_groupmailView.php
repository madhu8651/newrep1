<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'scriptfiles.php' ?>
<style>
#cke_1_bottom{
	border-bottom: 1px solid gray;
}
.filter_label{
	margin-top: 25px;	
}
.lead_address{
	background-color:#c1c1c1;
	padding: 10px 12px;
	margin-bottom: 17px;
	margin-top: 6px;
}
.lead_opper{
	background-color:#c1c1c1;
	padding: 10px 12px;
	margin-bottom:0;
}
.lead_view{
	background-color:#c1c1c1;
	padding: 10px 12px;
}
#mapname,#edit_mapname{
	width: 100%;
	height: 150px;
	border: 1px;
	position: relative;
	overflow: hidden;
	margin-bottom: 12px;
}
.btn_log{
	margin-bottom: 5px;
}
.apport_label label{
	font-weight:bold!important;	
}
#files{  display:block;
                }

.ui-datepicker-month{
	margin-left: 19px!important;
	border: 1px solid lightgrey!important;
	border-radius: 5px!important;
	margin-right: 2px!important;
}
.ui-datepicker-year{
	border-radius: 5px;
	border-color: lightgrey;
}
.multiselect{
		height: 83px;
		overflow: auto;
		border: 1px solid #ccc;
		border-radius: 5px;
	}
	.multiselect ul{
			padding: 0px;
	}
	.multiselect ul li.sel{
			background: #ccc;
	}
	.multiselect ul li{
			padding: 0 10px;
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
.tree-view input{
    margin-top: 0px;
}

#tree_leadsource{
		position: absolute;
		background: white;
		z-index: 99;
		top: -50px;
		left: 100px;
		border: 1px solid #ccc;
		padding: 10px;
		border-radius: 5px;
	}
	#tree_leadsource1{
		position: absolute;
		background: white;
		z-index: 99;
		top: -50px;
		left: 100px;
		border: 1px solid #ccc;
		padding: 10px;
		border-radius: 5px;
	}
	.multiselect2{
	height: 200px;
	overflow: auto;
	border: 1px solid #ccc;
	border-radius: 5px;
	}
	.multiselect2 ul{
		padding: 0px;
	}
	.multiselect2 ul li.sel{
		background: #ccc;
	}
	.multiselect2 ul li{
		padding: 0 10px;
	}
	#leadview{
	overflow: auto;
	}
	.view_style{
		float: right;
		margin-right: 22px;
		border: solid 1px;
		padding: 5px;
	}
	#fetch_btn{
		margin-top: 4px;
    	margin-bottom: 4px;
	}
	.product_heading{
		background: #cecece;
		padding: 8px;
		color: black;
		font-weight: bold;
		margin-bottom: 5px;
	}
	.pro_Value,.e_pro_Value{
		margin-top: -39px;
		padding-left: 41px;
	}
	.provalue1{
		width: 50px;
		padding-top: 3px;
		margin-top: -1px;
	}
	.edit_purchase{
		float: right;
		margin-right: 14px;
	}
	#purchase_info{
		float: right;
		padding: 2px 9px!important;
	}
	#tablebody .tooltip.bottom .tooltip-arrow{
		color:black;
	}
	#tablebody .tooltip.bottom .tooltip-inner{
		background:black;
		color:white;
		text-align:left;
	}
	body{
		padding-right:0px!important;
	}
	.rejected_lead,
	.legend{
		background-color: rgba(180, 0, 10, 0.20) !important;
	}
	
	.legend{
		width: 30px;
		height: 30px;
		margin: -5px 10px 0px 0px;
	}
	.legend-wrapper{
		width: 200px;
		margin:auto;
		float: none; 
		margin-left:25px;
	}
	.no_opacity_tooltip .tooltip.in .tooltip-inner{
		text-align:left;
	}
	.no_opacity_tooltip .tooltip.in{
		opacity: 1;
		padding:5px;
		background:  #ccc;		
	}
	#fetch_style{
		margin-bottom:5px;
	}
    .searched_file{
      list-style-type: none;
    }

</style>
<script>
var doc_data = "";
$(document).ready(function(){	
	$("#number").prop("checked", true);
    $('input.my_btn').hide();
	$('[data-toggle="tooltip"').tooltip();
	 $('input[type="file"]').change(function(e){
		var fileName = e.target.files[0].name;
		$("#file_name").append(fileName);
	});
	groupMail('logdetails');
});
function data_load(){
        loaderShow();
      	 $.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_com_groupmailController/get_associatedata'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){

				if(error_handler(data)){
					return;
				}
                if(data.length == 0){

                }else{
                  loadpage(data);
                }


			}
		});
}

/*original text to encripted text: window.btoa(data[i].remarks);
encripted text to original text: window.atob(obj.remarks);*/


function groupMail(data){
    if(data == 'logdetails'){
            loaderShow();
          	$.ajax({
    			type : "POST",
    			url : "<?php echo site_url('sales_com_groupmailController/get_data/assoc'); ?>",
    			dataType : 'json',
    			cache : false,
    			success : function(data){
    				if(error_handler(data)){
    					return;
    				}

                      load_assoc_page(data);

    			}
            });
    }
    if(data == 'logdetails1'){
            loaderShow();
          	$.ajax({
    			type : "POST",
    			url : "<?php echo site_url('sales_com_groupmailController/get_data/unassoc'); ?>",
    			dataType : 'json',
    			cache : false,
    			success : function(data){
    				if(error_handler(data)){
    					return;
    				}

                      load_unassoc_page(data);

    			}
            });
    }
    if(data == 'logdetails2'){
            loaderShow();
          	$.ajax({
    			type : "POST",
    			url : "<?php echo site_url('sales_com_groupmailController/get_data/conflict'); ?>",
    			dataType : 'json',
    			cache : false,
    			success : function(data){
    				if(error_handler(data)){
    					return;
    				}

                      load_conflict_page(data);

    			}
            });

    }
    if(data == 'logdetails3'){
        loaderShow();
        $.ajax({
            type : "post",
            url : "<?php echo site_url('sales_com_groupmailController/get_data/allmails'); ?>",
            dataType : "json",
            cache : false,
            success : function(data){
                if(error_handler(data)){
                    return;
                }
                load_allmails_page(data);
            }
        });
    }
}
function load_assoc_page(data){
            console.log(data)
            loaderHide();
            /* ---------- associates ------------ */
			$('#tablebody1').empty();
			var row = "";
            var j=1;
                for(var i1=0; i1 < data.length; i1++){
                    var rowdata = window.btoa(JSON.stringify(data[i1]));
                    var strtype='assoc';
                    if(data[i1].hasOwnProperty('attachment')){
                        row += "<tr onclick='sub_details(\""+rowdata+"\",\""+strtype+"\")' ><td>"
                                + (j) + "</td><td>" + data[i1].mail_from + "</td><td>" 
                                + data[i1].mail_to + "</td><td>"
                                + data[i1].mail_subject + "</td><td>"
                                + data[i1].mail_date + "</td><td>"
                                + data[i1].associate + "</td><td><i class='fa fa-paperclip' aria-hidden='true'></i></td></tr>";
                    }else{
                        row += "<tr onclick='sub_details(\""+rowdata+"\",\""+strtype+"\")' ><td>"
                                + (j) + "</td><td>" + data[i1].mail_from + "</td><td>" 
                                + data[i1].mail_to + "</td><td>"
                                + data[i1].mail_subject + "</td><td>"
                                + data[i1].mail_date + "</td><td>"
                                + data[i1].associate + "</td><td></td></tr>";
                    }

                    j++;
                }
			$('#tablebody1').append(row);
			$('#tablebody1').parent("table").DataTable();
}
function load_unassoc_page(data){
        loaderHide();
       $('#tablebody2').empty();
        var strtype='unassoc';
        if(data.length == 0){
            $('#tablebody2').parent("table").DataTable();
             $('#tablebody2').parent("table").removeAttr('style');
             colspan = $('#tablebody2').parent("table").find('thead ').find('tr th').length;
             $('#tablebody2').parent("table").find('tbody').find('tr td').attr('colspan', colspan)

        }else{
        	var row = "";
            var j=1;
                for(var i1=0; i1 < data.length; i1++){
                    var rowdata = window.btoa(JSON.stringify(data[i1]));
                    var str="";
                    if(data[i1].associate==null){
                        str="";
                    }else{
                        str=data[i1].associate;
                    }

                row += "<tr>"+
                "<td>" + (j) + "</td>"+
                "<td>" + data[i1].mail_from + "</td>"+
                "<td>" + data[i1].mail_to + "</td>"+
                "<td>" + data[i1].mail_subject + "</td>"+
                "<td>" + data[i1].mail_date + "</td>"+
                "<td>" + str + "</td>"+
                "<td>"+
                    "<input type='button' style='margin-top: -3px' class='btn btn-sm' onclick='match_user(\""+data[i1].message_id+"\",\"match_"+data[i1].mail_from+"\")' value='Match' /> "+
                    " <a href='#' title='View Mail' onclick='sub_details(\""+rowdata+"\",\""+strtype+"\")'><span class='glyphicon glyphicon-eye-open'></span></a>"+

                "</td>"+
                "</tr>";
                    j++;
                }
        	$('#tablebody2').append(row);
            $('#tablebody2').parent("table").DataTable();
            $('#tablebody2').parent("table").removeAttr('style');
        }

}
function load_conflict_page(data){
        loaderHide();
       $('#tablebody3').empty();
       var strtype='conflict';
        if(data.length == 0){
            $('#tablebody3').parent("table").DataTable();
             $('#tablebody3').parent("table").removeAttr('style');
             colspan = $('#tablebody3').parent("table").find('thead ').find('tr th').length;
             $('#tablebody2').parent("table").find('tbody').find('tr td').attr('colspan', colspan)

        }else{
        	var row = "";
            var j=1;
                for(var i1=0; i1 < data.length; i1++){
                    var rowdata = window.btoa(JSON.stringify(data[i1]));
                    var str="";
                    var str1="";
                    if(data[i1].associate==null){
                        str="";
                    }else{
                        str=data[i1].associate;
                    }
                    if(data[i1].mail_associated_state==2){
                        str1='msg_'+data[i1].message_id;
                    }else{
                        str1='conf_'+data[i1].lead_cust_opp_id+"_"+data[i1].message_id;
                    }
                row += "<tr>"+
                "<td>" + (j) + "</td>"+
                "<td>" + data[i1].mail_from + "</td>"+
                "<td>" + data[i1].mail_to + "</td>"+
                "<td>" + data[i1].mail_subject + "</td>"+
                "<td>" + data[i1].mail_date + "</td>"+
                "<td>" + str + "</td>"+
                "<td>"+
                    "<input style='margin-top: -3px' type='button' class='btn btn-sm' onclick='match_user(\""+str1+"\",\"conflict_"+data[i1].mail_from+"\")' value='Match' /> "+
                    " <a href='#' title='View Mail' onclick='sub_details(\""+rowdata+"\",\""+strtype+"\")'><span class='glyphicon glyphicon-eye-open'></span></a>"+
                "</td>"+
                "</tr>";
                    j++;
                }
        	$('#tablebody3').append(row);
            $('#tablebody3').parent("table").DataTable();
            $('#tablebody3').parent("table").removeAttr('style');
        }

}

function load_allmails_page(data){
    console.log(data)
    loaderHide();
    /* ---------- All mails ------------ */
    $('#tablebody4').empty();
    var row = "";
    var j=1;
    for(var i1=0; i1 < data.length; i1++){
        var rowdata = window.btoa(JSON.stringify(data[i1]));
        var strtype='allmails';
        var style = '';
        if(data[i1].mail_read_state == '0'){
            style = "style=font-weight:bold;";
        }else{
            style = "style=font-weight:none;";
        }

        if(data[i1].hasOwnProperty('attachment')){
            row += "<tr onclick='sub_details(\""+rowdata+"\",\""+strtype+"\")' "+style+"><td>"
                    + (j) + "</td><td>" + data[i1].mail_from + "</td><td>" 
                    + data[i1].mail_to + "</td><td>"
                    + data[i1].mail_subject + "</td><td>"
                    + data[i1].mail_date + "</td><td>"
                    + data[i1].associate + "</td><td><i class='fa fa-paperclip' aria-hidden='true'></i></td></tr>";
        }else{
            row += "<tr onclick='sub_details(\""+rowdata+"\",\""+strtype+"\")' "+style+"><td>"
                    + (j) + "</td><td>" + data[i1].mail_from + "</td><td>" 
                    + data[i1].mail_to + "</td><td>"
                    + data[i1].mail_subject + "</td><td>"
                    + data[i1].mail_date + "</td><td>"
                    + data[i1].associate + "</td><td></td></tr>";
        }

        j++;
    }
    $('#tablebody4').append(row);
    $('#tablebody4').parent("table").DataTable();
}

function search_data(){
	var addObj = {};
	if($("#name").prop("checked") == true){
		addObj.nametype = "name";
		if(!($.trim($("#search_value").val().match(/^[a-zA-Z]+$/)))) {
			$("#search_value").closest("div").find("span").text("only alphabets are allowed");
			return;
		}else if($.trim($("#search_value").val())==""){
            $("#search_value").closest("div").find("span").text("Please Enter Value");
			return;
		}else{
			$("#search_value").closest("div").find("span").text("");
		}
	}
	if($("#number").prop("checked") == true){
		addObj.nametype = "number";
		if(!($.trim($("#search_value").val().match(/^[0-9]+$/)))) {
			$("#search_value").closest("div").find("span").text("only numericals are allowed");
			return;
		}else if($.trim($("#search_value").val())==""){
            $("#search_value").closest("div").find("span").text("Please Enter Value");
			return;
		}else{
			$("#search_value").closest("div").find("span").text("");
		}
	}
	addObj.search_value = $("#search_value").val();
	console.log(addObj);
    loaderShow();
    $.ajax({
		type : "POST",
		url : "<?php echo site_url('sales_com_groupmailController/get_matchdata/unassoc'); ?>",
		dataType : 'json',
		data : JSON.stringify(addObj),
		cache : false,
		success : function(data){
			console.log(data) ;
            loaderHide();
            var row = "";
            var str=[];
            if(data.length == 0){
                        row += '<ul class="searched_file">';
                        row += '<li>No search found.</li>';
            }else{
                    contact_email=JSON.parse(data[0].contact_email);
                    for(i=0;i<contact_email.email.length;i++){
                        if(contact_email.email[i]!=''){
                           str.push(contact_email.email[i]);
                        }
                    }
                    row += '<ul class="searched_file">';
                    if(data.length == 0){
                       row += '<li>No search found.</li>';
                    }else{
                      /* disable all */
                      for(i=0;i<data.length;i++){
                        row += '<li><input name="remove_unassoc" type="radio" id="'+data[i].lead_cust_id+'_'+data[i].contact_for+'" />  <label>'+data[i].contact_name+'</label>&nbsp; &nbsp;&nbsp;<label>( '+str+' )</label></li>';
                      }
                      row += "<li><button class='btn' onclick=remove_unassoc(\'unassoc\')>Match</button></li>";
                    }
            }

            row += '</ul>';
            $(".radio_select").empty();
            $(".radio_select").append(row);
		}
	});
}

function remove_unassoc(pagetype){
  var obj={};
  var flg=0;
  var value
     $('.searched_file li input[name=remove_unassoc]').each(function(){
      if($(this).prop('checked')==true){
         value = $(this).attr('id');
         flg=1;
      }
    }) ;
    if(flg==0){
        loaderHide();
        alert("please select atleast one option");
        return;
    }
    var value1=value.split('_');
    obj.lead_cust_id =value1[0]  ;
    obj.type =value1[1]  ;
    obj.pagetype =pagetype;
    obj.hidmsgid = $('#hidmsgid').val();
    obj.hidemail = $('#hidemail').val();

    loaderShow();
  	$.ajax({
      type : "POST",
      url : "<?php echo site_url('sales_com_groupmailController/remove_unassoc'); ?>",
      dataType : 'json',
      data : JSON.stringify(obj),
      cache : false,
      success : function(data){
          loaderHide();
          if(error_handler(data)){
          	return;
          }
          $("#match_type").modal("hide");
          if(pagetype=='unassoc'){
             groupMail('logdetails1');
          }else{
            groupMail('logdetails2');
          }

      }
    });
}
function addExl(){
	$("#mail_add").modal("show");
} 
function close_assign(){
	$('#mail_add').modal('hide');	
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
	$('#modal_upload #attachment').val("");
}
 function mail_match(){
	$('#match_type').modal('hide');	
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
}
function attachment_match(){
	$('#attachment_box').modal('hide');
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
}
function attach_match(){
	$('#attach_box').modal('hide');
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
}
 function match_user(hidmsgid,hidemail,type){
        $("#number").prop("checked", true);
        $("#search_value").val("").attr('placeholder', 'Search by Phone Number');


        $("#number").change(function(){
            $("#search_value").val("").attr('placeholder', 'Search by Phone Number');
            $("#search_value").closest("div").find("span").text("Please Enter Value");
        })

        $("#name").change(function(){
             $("#search_value").val("").attr('placeholder', 'Search by Name');
             $("#search_value").closest("div").find("span").text("Please Enter Value");
        })
        var value=hidemail;
        var value1=value.split('_');
        $("#attach_box").modal("hide");
        if(value1[0]=='match'){
            $("#match_type").modal("show");
            $(".radio_select").empty();
            $('#hidmsgid').val(hidmsgid);
            $('#hidemail').val(value1[1]);
            $('#matchdiv').show();
        }else{

            $("#match_type").modal("show");
            $(".radio_select").empty();
            $('#hidmsgid').val(hidmsgid);
            $('#hidemail').val(value1[1]);
            $('#matchdiv').hide();
            var addObj={};
            addObj.email=value1[1];
            addObj.hidmsgid=hidmsgid;
            var conftype=hidmsgid;
            var conftype1=conftype.split('_');    ;
            loaderShow();
            $.ajax({
        		type : "POST",
        		url : "<?php echo site_url('sales_com_groupmailController/get_matchdata/conflict'); ?>",
        		dataType : 'json',
        		data : JSON.stringify(addObj),
        		cache : false,
        		success : function(data){
        			console.log(data) ;
                    loaderHide();
                    var row = "";
                    var str=[];

                    if(conftype1[0]=='msg'){
                            if(data.length == 0){
                                row += '<ul class="searched_file">';
                                row += '<li>No search found.</li>';
                            }else{
                                /* disable all */
                                contact_email=JSON.parse(data[0].contact_email);
                                for(i=0;i<contact_email.email.length;i++){
                                    if(contact_email.email[i]!=''){
                                       str.push(contact_email.email[i]);
                                    }
                                }
                                row += '<ul class="searched_file">';
                                for(i=0;i<data.length;i++){
                                  row += '<li><input name="remove_unassoc" type="radio" id="'+data[i].lead_cust_id+'_'+data[i].contact_for+'" />  <label>'+data[i].contact_name+'</label>&nbsp; &nbsp;&nbsp;<label>( '+str+' )</label></li>';
                                }
                                row += "<li><button class='btn' onclick=remove_unassoc(\'conflict\')>Match</button></li>";
                            }
                            row += '</ul>';
                            $(".radio_select").empty();
                            $(".radio_select").append(row);
                    }else{

                            if(data.length == 0){
                                row += '<ul class="searched_file">';
                                row += '<li>No search found.</li>';
                            }else{
                                /* disable all */

                                row += '<ul class="searched_file">';
                                for(i=0;i<data.length;i++){
                                  row += '<li><input name="remove_unassoc" type="radio" id="'+data[i].opportunity_id+'_opportunity" />  <label>'+data[i].opportunity_name+'</label></li>';
                                }
                                row += "<li><button class='btn' onclick=remove_unassoc(\'conflict\')>Match</button></li>";
                            }
                            row += '</ul>';
                            $(".radio_select").empty();
                            $(".radio_select").append(row);


                    }

        		}
        	});
        }
 }
 function show_attachment(data){
	$("#attachment_box").modal("show");
	console.log(data)
	console.log(doc_data.attachment[1].mail_attachment_path)
	for(i=0;i<doc_data.attachment.length;i++){
		if(data == doc_data.attachment[i].mail_attachment_path){				
			$(".file_attach").css('height', '376px');
			$('.file_attach').css({'background-image':'url(<?php echo base_url(); ?>uploads/' + doc_data.attachment[i].mail_attachment_path + ')','background-repeat':' no-repeat','background-size': 'contain'});
		}
	}
 }
 function mail_send(){
	 var addObj = {};
	if($("#mail_to").val() == "" ){
		$("#mail_to").closest("div").find("span").text("Mail id is required.");
		$("#mail_to").focus();
		return;
	}else{
		$("#mail_to").closest("div").find("span").text("");
	}
	if($("#mail_sub").val() == ""){
		confirm("Are you sure you want to send without subject");
	}
	addObj.mail_to = $.trim($("#mail_to").val());
	addObj.mail_cc = $.trim($("#mail_cc").val());
	addObj.mail_bcc = $.trim($("#mail_bcc").val());
	addObj.mail_sub = $.trim($("#mail_sub").val());
	/* var data_text = CKEDITOR.instances.editor1.document.getBody().getHtml();
	
	var text_data = data_text.split("<p>");
	var text_data1 = text_data[1].split("</p>");
	addObj.msg_body = text_data1[0]; */
	addObj.msg_body = CKEDITOR.instances.editor1.document.getBody().getHtml();
	addObj.file = $("#contactImageUploadA").val();
	console.log(addObj)
 }
 function sub_details(data,strtype){

   data = window.atob(data);
     data=JSON.parse(data)   
	$("#attach_box").modal("show");
    $("#matchbtn").empty();
    $("#from_name").empty("").append(data.mail_from);
    $("#to_name").empty("").append(data.mail_to);

    $("#sub_name").empty("").append(data.mail_subject);
    $("#sub_date").empty("").append(data.mail_date);
    $("#mail_body").html("").append(data.mail_body);

    var row = "";
    var row1 = "";
    var str1 = "";
    if(strtype=='unassoc'){
        $('#raise_ticket').hide();
        $('#create_lead').show();
        $('#create_contact').show();
        row1 += "<ul class='searched_file'><li><button class='btn' onclick='match_user(\""+data.message_id+"\",\"match_"+data.mail_from+"\")'>Match</button></li></ul>";
        $("#matchbtn").empty();
        $("#matchbtn").append(row1);
    }else if(strtype=='conflict'){
        $('input.my_btn').hide();
        if(data.mail_associated_state==2){
                str1='msg_'+data.message_id;
        }else{
                str1='conf_'+data.lead_cust_opp_id+"_"+data.message_id;
        }

        row1 += "<ul class='searched_file'><li><button class='btn' onclick='match_user(\""+str1+"\",\"conflict_"+data.mail_from+"\")'>Match</button></li></ul>";
        $("#matchbtn").empty();
        $("#matchbtn").append(row1);

    }else if(strtype == 'allmails'){
        $('input.my_btn').hide();
    }else if(strtype == 'assoc'){
        $('#raise_ticket').show();
        $('#create_lead').hide();
        $('#create_contact').hide();
    }

    if(data.hasOwnProperty('attachment')){
        $('.showdiv').show();
     row += "<ul class='attach_class'>";
        for(i=0;i<data.attachment.length;i++){
          var path = data.attachment[i].mail_attachment_path;
         var file_name = path.split('/').pop();
         var format = file_name.split('.').pop();
         var file_name = file_name.split('.')[0];
		 doc_data = data;
         console.log(file_name)
            if(format == 'jpg' || format == 'jpeg' || format == 'gif' || format == 'bmp' || format == 'png'){
                row +='<li id="'+path+'" onclick="show_attachment(this.id)">'+data.attachment[i].mail_attachment_filename+'</li>';
            }
            if(format == 'doc' || format == 'docx' || format == 'pdf' || format == 'rtf' || format == 'txt' || format == 'xls' || format == 'csv'){
				if(format == 'pdf'){
					row +='<li><a href="https://lconnectt.in/pdfviewer/viewer.html?file=<?php echo base_url(); ?>uploads/' + data.attachment[i].mail_attachment_path +'" target="_blank">'+data.attachment[i].mail_attachment_filename+'</a></li>';
				}else{
					row +='<li>'+data.attachment[i].mail_attachment_filename+' (Cannot open this kind of files)</li>';
				}
            }
        }
        row +='</ul>';
    }else{
      $('.showdiv').hide();
    }
    $("#attach_file").empty();
    $("#attach_file").append(row);
 }

function raise_ticket(event){
    if(event.id == 'raise_ticket'){
        window.location.href="<?php echo site_url('sales_supportController'); ?>";
    }else if(event.id == 'create_lead'){
        window.location.href="<?php echo site_url('leadinfo_controller'); ?>";
    }else if(event.id == 'create_contact'){
        window.location.href="<?php echo site_url('sales_contactListController'); ?>";
    }
}

</script>
<script src="https://cdn.ckeditor.com/4.8.0/standard-all/ckeditor.js"></script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    	<div class="loader">
			<center><h1 id="loader_txt"></h1></center>
		</div>
        <?php require 'demo.php' ?>
        <?php require 'sales_sidenav.php' ?>
        <div class="content-wrapper body-content">
            <div class="col-lg-12 column">
				<div class="row header1">				
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div>
								<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-toggle="tooltip" data-placement="right" title="Communicator"/>
							</div>
						</span>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Group Mail</h2>
						<input type="hidden" id="e_currency1" />
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						<div class="addBtns">
							<a  class="addExcel" onclick="addExl()" >
							 <img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/></a>
						</div>
						<div style="clear:both"></div>
					</div>
				</div>
				<div class="row" id="activity_tab_view">
					<ul class="nav nav-tabs">
						<li class="active" id="logdetails_cust">
                            <a onclick="groupMail('logdetails')" data-toggle="tab" href="#logdetails">Associated</a>
                        </li>
						<li >
                            <a onclick="groupMail('logdetails1')" data-toggle="tab" href="#logdetails1">Un Associated</a>
                        </li>
						<li >
                            <a onclick="groupMail('logdetails2')" data-toggle="tab" href="#logdetails2">Conflict Mails</a>
                        </li>
                        <li >
                            <a onclick="groupMail('logdetails3')" data-toggle="tab" href="#logdetails3">All Mails</a>
                        </li>
					</ul>
					<div class="tab-content">
						<div id="logdetails" class="tab-pane fade in active">
							<table class="table">
								<thead>
									<tr>
                                        <th>SL No</th>
										<th>From</th>
                                        <th>To</th>
										<th>Subject</th>
										<th>Date</th>
										<th>Association</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="tablebody1">

								</tbody>
							</table>
						</div>
						<!------------------------------------------------------>
						<div id="logdetails1" class="tab-pane fade" >
							<table class="table">
								<thead>
									<tr>
										<th>SL No</th>
										<th>From</th>
                                        <th>To</th>
										<th>Subject</th>
										<th>Date</th>
										<th>Association</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="tablebody2">

								</tbody>
							</table>
						</div>		
						<div id="logdetails2" class="tab-pane fade">
							<table class="table">
								<thead>
									<tr>
                                        <th>SL No</th>
										<th>From</th>
                                        <th>To</th>
										<th>Subject</th>
										<th>Date</th>
										<th>Association</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="tablebody3">

								</tbody>
							</table>
						</div>
                        <div id="logdetails3" class="tab-pane fade">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>SL No</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Subject</th>
                                        <th>Date</th>
                                        <th>Association</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="tablebody4">

                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
            </div>
			<div id="mail_add" class="modal fade" data-backdrop="static">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<span class="close"  onclick="close_assign()">&times;</span>
							<h4 class="modal-title">New Message</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-2">
									<label for="mail_to">To</label>
								</div>
								<div class="col-md-10">
									<input type="text" class="form-control" id="mail_to" />
									<span class="error-alert"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<label for="mail_cc">Cc</label>
								</div>
								<div class="col-md-10">
									<input type="text" class="form-control" id="mail_cc" />
									<span class="error-alert"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<label for="mail_cc">Bcc</label>
								</div>
								<div class="col-md-10">
									<input type="text" class="form-control" id="mail_bcc" />
									<span class="error-alert"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<label for="mail_sub">Subject</label>
								</div>
								<div class="col-md-10">
									<input type="text" class="form-control" id="mail_sub" />
									<span class="error-alert"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<textarea cols="80" id="editor1" name="editor1" rows="10" >
									
									</textarea>									
								</div>								
							</div>
							<div id="text_area"></div>
							<div class="row showdiv" style="margin-top: 8px;">
								<div class="col-md-11" >
									<a href="#">
										<label style="width: 120px;background:#b5000a;color:white;"  for="contactImageUploadA" class="custom-file-upload"> 
											<i class="fa fa-cloud-upload"></i> Attachment</label>
									</a>
									<input style="width: 110px;" type="file" class="form-control" accept="image/*"  name = "userfile" id="contactImageUploadA" onchange="addimageloaded('change');" />  <p id="file_name"></p>
								</div>
								<div class="col-md-1">
									<input type="button" class="btn" onclick="mail_send()" value="Send">
								</div>
							</div>							
						</div>
						<div class="modal-footer">
													
							
						</div>
					</div>
				</div>
			</div>
			<div id="match_type" class="modal fade" data-backdrop="static" >
                <input type="hidden" id='hidmsgid' name='hidmsgid' />
                <input type="hidden" id='hidemail' name='hidemail' />
                <input type="hidden" id='hidcontact' name='hidcontact' />
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<span class="close"  onclick="mail_match()">&times;</span>
							<h4 class="modal-title">Match</h4>
						</div>
						<div class="modal-body" id="matchdiv">
							<div class="row">								
								<div class="col-md-3">
									<input type="radio" name="match_name" id="number" selected /><label for="number">Mobile</label>
								</div>
								<div class="col-md-3">
									<input type="radio" name="match_name" id="name" /><label for="name">Name</label>
								</div>
							</div>	
							<div class="row">
								<div class="col-md-6">
									<input class="form-control mr-sm-2" type="text" id="search_value" placeholder="Search">
									<span class="error-alert"></span>
								</div>
								<div class="col-md-4">
									<button class="btn btn-secondary my-2 my-sm-0" onclick="search_data()" type="submit">Search</button>
								</div>
							</div>
						</div>
                        <div class="row">
                                <div class="col-md-12 radio_select"></div>
						</div>
					</div>
				</div>
			</div>
			<div id="attach_box" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<span class="close"  onclick="attach_match()">&times;</span>
							<h4 class="modal-title">Mail Details</h4>
						</div>
						<div class="modal-body">
							<div class="row">
                                <div class="col-md-2">
									<label ><b>Date: </b></label>
								</div>
								<div class="col-md-10">
									<label id="sub_date"></label>
								</div>

                                <div class="col-md-2">
									<label><b>From: </b></label>
								</div>
								<div class="col-md-10">
									<p id="from_name"></p>
								</div>
                                <div class="col-md-2">
									<label><b>To: </b></label>
								</div>
								<div class="col-md-10">
									<p id="to_name"></p>
								</div>

								<div class="col-md-2">
									<label><b>Subject: </b></label>
								</div>
								<div class="col-md-10">
									<p id="sub_name"></p>
								</div>

							</div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label><b>Message</b></label>
                                </div>
                                <div class="col-md-9">
                                    <label id="mail_body"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label><b>Attachment</b></label>
                                </div>
                                <div class="col-md-9">
                                    <label id="attach_file"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label ></label>
                                </div>
                                <div class="col-md-9">
                                    <label id="matchbtn"></label>
                                </div>
                            </div>
						</div>
						<div class="modal-footer">
                            <input type="button" class="btn my_btn" id="raise_ticket" onclick="raise_ticket(this)" value="Raise Ticket" />
                            <input type="button" class="btn my_btn" id="create_lead" onclick="raise_ticket(this)" value="Create Lead" />
                            <input type="button" class="btn my_btn" id="create_contact" onclick="raise_ticket(this)" value="Create Contact" />
							<input type="button" class="btn" onclick="attach_match()" value="Cancel">
						</div>
					</div>
				</div>
			</div>

			<div id="attachment_box" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<span class="close"  onclick="attachment_match()">&times;</span>
							<h4 class="modal-title">Attached File</h4>
						</div>
						<div class="modal-body">
							<div class="row">
				                 <div class="col-md-3">

                                 </div>
                                 <div class="col-md-6 file_attach">

                                 </div>
                                 <div class="col-md-3">

                                 </div>
							</div>
						</div>
						<div class="modal-footer">
							<input type="button" class="btn" onclick="attachment_match()" value="Cancel">
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
		// We need to turn off the automatic editor creation first.
		CKEDITOR.disableAutoInline = true;

		CKEDITOR.replace( 'editor1' );
	</script>
	<?php require 'footer.php' ?>
    </body>
</html>
