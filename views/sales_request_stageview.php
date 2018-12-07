<!DOCTYPE html>
<html lang="en">
<head>	
<?php require 'scriptfiles.php' ?>
<style>
    .text-left{
        text-align:left !important;
    }
    .capitalize{
        text-transform: capitalize;
    }
    
    
    #opp_summary_panel{
		border: 0px; 
		-webkit-box-shadow: 0px rgb(0,0,0,0);
		box-shadow: 0px rgba(0,0,0,0);
	}
	.opp_summary_heading{
		border-radius: 10px;
		margin: auto;
		width: 30%;
		box-shadow: 0px 0px 4px 4px rgba(160, 133, 133, 0.78);
	}
	.opp_summary_row	{
		margin-top: 10px;
	}
	.static{
		position: fixed;
		bottom: 8px;
		left: 50%;
		width: 30%;
		background: rgba(198, 203, 203, 0.9);
		border-radius: 7px;
		padding: 5px 0px 5px;
		margin: 0 0 0 -15% !important;
   	}
	.static_elements	{
		text-align: center;
		font-weight: bold;
		padding: 1px 15px 1px;		
	}
	.oppo_details .row{
		border-bottom:1px solid black;
	}
	.oppo_details {
		margin-bottom: 4px;
	}
	.opp_details_btns	{
		margin: 10px 20px 10px !important;
	}
	.opportunity_history	{
		margin-top: 10px;
		margin-bottom: 10px;
		overflow-y: auto;
		height: 55vh;
	}
	.opportunity_actions	{
		overflow-y: auto;
	}
	.created, .assigned, .remarks, .stage_changed, .accepted, .rejected {
		text-align: left;
	}
	b{
		color: #b5000a;
	}
	.opp_stage{
		text-align: center;
		font-weight: bold;
		padding: 1px 10px 1px;
	}
	.stage_body{
		width: 99%;
		padding-left: 6px;
		padding-right: 6px;
		margin-bottom: 100px;
	}
	.arrow{
		display: inline;
		float: right;
	}
	.stage_body .panel-title{
		color: black;
	}
	#view_document{
		margin-right: 35px;
	}
	.opp_btn,.opp_btn1{
		float: right;
		margin-right: 10px !important;
	}
	.documents ul{
		list-style-type:none;
	}
	.go-top.visible{
		opacity: .75;
	}
	.go-top{
		-moz-border-radius: 7px 7px 0 0;
		-moz-transition: all .3s;
		-o-transition: all .3s;
		-webkit-border-radius: 7px 7px 0 0;
		-webkit-transition: all .3s;
		background: #434343;
		border-radius: 7px 7px 0 0;
		bottom: 4px;
		color: #fff;
		display: block;
		height: 9px;
		opacity: 0;
		padding: 13px 0 45px;
		position: fixed;
		right: 10px;
		text-align: center;
		text-decoration: none;
		transition: all .3s;
		width: 49px;
		z-index: 1040;
		right: 50px;
		border: 1px solid #434343;
	}
	.questions{
		min-height: 50px;
		border: 1px solid #ccc;
		box-shadow: 0px 3px 12px #ccc;
		padding: 15px 20px;
		transition: all 0.5s ease-in-out;
		margin-bottom: 20px;
	}
	.questions i.fa.fa-star-half-o {
		position: absolute;
		left: 2px;
		top: 1px;		
	}
	i.fa.fa-star-half-o {
		color:red;
	}
	.doc_list{
		list-style-type:none;
	}
	.table_hover tr:hover{
		background:white!important;
	}
	.color_rej{
		color:red!important;
	}
	.doc_remarks {
		color: darkslategray;
		font-style: oblique;
		display: block;
	}
            textarea {
                resize: none;
             }
</style>
<script>
   var request_id= window.location.href.split("/");
   request_id = request_id[request_id.length- 1];
   var loggedin_user_id="<?php echo $this->session->userdata('uid') ?>";
   var update_reqst=[];
   var request_details=[];
   var stage_details=[];
   var qualifier_id=0;
$(document).ready(function(){
    request_stage_view();
    $(".go-top").click(function(){
            $("#questionnaire").animate({ scrollTop: 0 }, "slow");
            return false;
    });
    $(".go-top").click(function() {
            $("#questionnaire").animate({
                    scrollTop: 0
            }, 500);
    });
    $("#questionnaire").scroll(function() {
            var aTop = 500;
            if($(this).scrollTop()>=aTop){
                    $(".go-top").addClass("visible");
            } else {
                    $(".go-top").removeClass("visible");
            }
    });
    $("#tempdate").datetimepicker({
            ignoreReadonly:true,
            allowInputToggle:true,
            format:'YYYY-MM-DD',
            minDate:moment()
    });	
    $("#stage_closed_date").datetimepicker({
            ignoreReadonly:true,
            allowInputToggle:true,
            format:'YYYY-MM-DD',
            minDate:moment()
    });	
    $('#support_progress_form').on('submit', function (e) {
            e.preventDefault();
    });
 });
function request_stage_view(){
    loaderShow();
    var oppoObj = {};
    oppoObj.request_id = request_id;
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('sales_supportController/request_details')?>",
        dataType : 'json',
        data : JSON.stringify(oppoObj),
        cache : false,
        success : function(data){
            if(error_handler(data)) {
                    return;
            }
            loaderHide();
            var contact1 = data.contact[0];
            var attribute_val=data.stage_value;
            console.log(request_details);
            request_details = data.request[0];
            stage_details = data.stage_attributes[0];
             var owner_id="<?php echo $this->session->userdata('uid') ?>";
             if(request_details.owner_id!=owner_id || request_details.closed_status==100){
                $('#opp_progress_btn').hide();
             }
            if(request_details.owner_status==1){
                $('#new_req_progress').hide();
            }else if(request_details.owner_status==2){
                $('#new_req_progress').show();
            }
            $("#oppo_cust_name").text(request_details.oppo_cust_name);
            $("#contact_name").text(contact1.contact_name);
            $("#product_name").text(request_details.prod);
            $("#indusrty_name").text(request_details.ind);
            $("#location_name").text(request_details.location);
            $("#stage_name").text(request_details.stage_name);
            $("#stage_owner").text(request_details.rep_name);
            $("#creator_name").text(request_details.rep_name);
            $('#owner_mgr_name').text(request_details.manager_name);
            $('#stage_mgr_name').text(request_details.stage_manager_owner_name);
            $("#req_critic").text(request_details.cricticality);
            $("#com_tat").text(request_details.tat);
            $(".cur_stage_name").text(request_details.stage_name);
            $("#process_type").text(request_details.processtype);
            $("#contact_num").text(request_details.contact_number);
            $("#contact_email").text(request_details.contact_email);
            $("#display_note").text(request_details.remarks);
            
            /* ------------------------------------------------ */
                update_reqst=[];
                for(o=0;o<data.stage_attributes.length; o++){
                    if((data.stage_attributes[o].stage_sequence == request_details.stage_sequence)
                        && data.stage_attributes[o].attribute_name !="allocation_matrix"
                        && data.stage_attributes[o].attribute_name !="qualifier"
                         && data.stage_attributes[o].attribute_name !="document_upload"){
                    }
                    if(data.stage_attributes[o].stage_sequence == request_details.stage_sequence){
                            update_reqst.push(data.stage_attributes[o]);
                    }
                    if(data.stage_attributes[o].stage_sequence != request_details.stage_sequence ){
                    var next=data.stage_attributes[o].stage_id+"-"+data.stage_attributes[o].stage_sequence;
                            $("#next_stage_id").val(next);
                    } 
                    
                }
                stage_history();
        }
    });
}
function stage_history(){
    var add={};
    add.request_id=request_id;
    $.ajax({
            type:"post",
            cache:false,
            url:"<?php echo site_url('sales_supportController/get_stage_history');?>",
            dataType : 'json',
            data:JSON.stringify(add),
            success: function (data) {
                if(error_handler(data)) {
                        return;
                }
                var groups = {};
                for (var i = 0; i < data.length; i++) {
                        var groupName = data[i].stage_sequence;
                        if(!groups[groupName]) {
                                groups[groupName] = [];
                        }
                        groups[groupName].push(data[i]);
                }
                var acc  = '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
                $.each(groups, function(i, item){
                    stage_name = item[0].stage_name.split(' ').join('');
                    acc += '<div class="panel panel-default">'+
                              '<div class="panel-heading" onclick="collapsefn(this.id)" role="tab" id="heading'+stage_name+'">'+
                                '<h4 class="panel-title collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'+stage_name+'" aria-expanded="true" aria-controls="collapse'+stage_name+'">'+
                                   '<a><b>'+
                                    item[0].stage_name.split(' ').join(' ')+
                                  '</b></a>'+
                                '<span class="arrow"><i class="fa fa-fw fa-chevron-down down_arrow" aria-hidden="true"></i></span>'+
                                '</h4>'+
                              '</div>'+
                              '<div id="collapse'+stage_name+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading'+stage_name+'">'+      
                                '<div class="panel-body">'+
                                    '<table class="table">';
                                        for(j=0; j<item.length; j++){
                                            acc +=   '<tr ><td class="text-left capitalize">'+item[j].support_attribute_name.split('_').join(' ')+'</td><td class="text-left">'+item[j].support_attribute_value+'</td></tr>' ;
                                        } 
                                        acc +='<tr ><td class="text-left capitalize">'+"Remarks"+'</td><td class="text-left">'+item[0].support_attribute_remarks+'</td></tr>' ;
                            acc += '</table>'+
                                '</div>'+
                    '</div>'+
                  '</div>';
                })
                acc += '</div>';
                $('#accordion_wrapper').html(acc);
            }
    });

}
function collapsefn(id){
    $("#".id +" .fa.fa-fw")
}
function update_req_popup(){
 var html ="";
  var addObj={};
  addObj. req_id=request_details.request_id;
  addObj.stage_id=request_details.request_stage;
    $("#req_id").val(request_details.request_id);
    $("#cyc_id").val(request_details.cycle_id);
    $("#opp_id").val(request_details.opp_cust_id);
     $("#oppor_id").val(request_details.opp_cust_id);
    $("#sta_id").val(request_details.request_stage);
    $("#process_id").val(request_details.process_type);
    loaderShow();
    $.ajax({
         type: 'POST',
         url: "<?php echo site_url('sales_supportController/get_attributue_val'); ?>",
         data: JSON.stringify(addObj),
         dataType : 'json',
         cache: false,
         contentType: false,
         processData: false,
         success : function(data){
             if(error_handler(data)) {
                     return;
             }
              loaderHide();
        html ="";
        var html1='';
        html += '<div class="row">'+
                                 '<div class="col-md-4"><label class="label1">Document upload</label></div>'+
                                 '<div class="col-md-8">'+
                                     '<input type="file" name="userfile[]" id="document_upload"  class="form-control closeinput" multiple style="display:block">'+
                                 '<span class="error-alert"></span>'+
                                 '</div>'+
                             '</div>';
        $("#update_req_popup .modal-body .form").html('');
        for(i=0,id=0; i<update_reqst.length; i++,id++){
            html1='';
            if(data.length>0){
            for(ii=0; ii<data.length; ii++){
                if(update_reqst[i].attribute_name == "action_button"){
                    $("#reject_owner").val(update_reqst[0].attribute_value);
                    $('#action_accept').show();
                    $('#action_progress').hide();
                }else if(update_reqst[i].attribute_name == "allocation_matrix"){
                    $('#allocation').val(update_reqst[i].attribute_value);
                }
                else if(update_reqst[i].attribute_name == "qualifier"){
                     qualifier_id=update_reqst[i].attribute_value;
                }else if(update_reqst[i].attribute_name == "max_permission"){
                     //=================================================
                        if(data[ii].support_attribute_name == update_reqst[i].attribute_name){
                            val= data[ii].support_attribute_value.split('-');
                            var days,Hours;
                            if(val[1] == "1"){
                                days ='<input id="days" checked name="timeframe" value="1" type="radio">';
                                Hours ='<input id="hours" name="timeframe" value="0" type="radio">';
                            }else if(val[1] == "0"){
                                days ='<input id="days" name="timeframe" value="1" type="radio">';
                                Hours ='<input id="hours" checked name="timeframe" value="0" type="radio">';
                            }
                            html1= '<div class="row">'+
                                        '<div class="col-md-4"><label class="label1"> Max Permissible Timeframe</label></div>'+
                                        '<div class="col-md-8">'+
                                                '<div class="row">'+
                                                    '<div class="col-md-3">'+
                                                        '<label for="days">Days '+ 
                                                        days+
                                                    '</label></div>'+
                                                    '<div class="col-md-3">'+
                                                        '<label for="hours"> Hours '+ 
                                                        Hours+
                                                    '</label></div>'+
                                                    '<div class="col-md-6">'+
                                                        '<input value="'+val[0]+'" name="timeframe_value" class="form-control" type="text">'+
                                                        '<span class="error-alert"></span>'+
                                                    '</div>'+
                                                '</div>'+
                                        '</div>'+
                                    '</div>';
                             break;
                        }else{
                            html1= '<div class="row">'+
                                        '<div class="col-md-4"><label class="label1"> Max Permissible Timeframe</label></div>'+
                                        '<div class="col-md-8">'+
                                                '<div class="row">'+
                                                    '<div class="col-md-3">'+
                                                        '<label for="days">Days '+ 
                                                        days+
                                                    '</label></div>'+
                                                    '<div class="col-md-3">'+
                                                        '<label for="hours"> Hours '+ 
                                                        Hours+
                                                    '</label></div>'+
                                                    '<div class="col-md-6">'+
                                                        '<input value="" name="timeframe_value" class="form-control" type="text">'+
                                                        '<span class="error-alert"></span>'+
                                                    '</div>'+
                                                '</div>'+
                                        '</div>'+
                                    '</div>';
                        }
                 }else if(update_reqst[i].attribute_name == "expected_close_date"){
                        if(data[ii].support_attribute_name == update_reqst[i].attribute_name){
                               html1= '<div class="row">'+
                                        '<div class="col-md-4"><label class="label1">Expected Close Date</label></div>'+
                                        '<div class="col-md-8">'+
                                            '<input value="'+data[ii].support_attribute_value+'"class="form-control" id="attr_expected_close_date" name="expected_close_date" placeholder="'+update_reqst[i].attribute_value+'"  type="text">'+
                                        '<span class="error-alert"></span>'+
                                        '</div>'+
                                    '</div>';
                           break;
                        }else{
                               html1= '<div class="row">'+
                                        '<div class="col-md-4"><label class="label1">Expected Close Date</label></div>'+
                                        '<div class="col-md-8">'+
                                            '<input class="form-control" id="attr_expected_close_date" name="expected_close_date" placeholder="'+update_reqst[i].attribute_value+'"  type="text">'+
                                        '<span class="error-alert"></span>'+
                                        '</div>'+
                                    '</div>';
                        }
                    
                 }else if(update_reqst[i].attribute_name == "document_upload"){
                     /*html1 = '<div class="row">'+
                                 '<div class="col-md-4"><label class="label1">Document upload</label></div>'+
                                 '<div class="col-md-8">'+
                                     '<input type="file" name="userfile[]" id="document_upload"  class="form-control closeinput" multiple style="display:block">'+
                                 '<span class="error-alert"></span>'+
                                 '</div>'+
                             '</div>';*/
                }else{
                    if(update_reqst[i].lookup_value!=null || update_reqst[i].lookup_value!=''){
                       
                        //=================================================
                        if(data[ii].support_attribute_name == update_reqst[i].lookup_value){
                            html1 = '<div class="row">'+
                                        '<div class="col-md-4"><label class="label1 '+update_reqst[i].attribute_name+'" for="cust_'+id+'">'+update_reqst[i].lookup_value+'</label></div>'+
                                            '<div class="col-md-8">'+
                                            '<input value="'+data[ii].support_attribute_value+'" class="form-control custom-field" id="cust_'+id+'" name="'+update_reqst[i].attribute_name+'" placeholder="'+update_reqst[i].attribute_value+'" type="text">'+
                                            '<span class="error-alert"></span>'+
                                        '</div>'+
                                    '</div>';
                            break;
                        }else{
                            html1 = '<div class="row">'+
                                        '<div class="col-md-4"><label class="label1 '+update_reqst[i].attribute_name+'" for="cust_'+id+'">'+update_reqst[i].lookup_value+'</label></div>'+
                                            '<div class="col-md-8">'+
                                            '<input class="form-control custom-field" id="cust_'+id+'" name="'+update_reqst[i].attribute_name+'" placeholder="'+update_reqst[i].attribute_value+'" type="text">'+
                                            '<span class="error-alert"></span>'+
                                        '</div>'+
                                    '</div>';
                        }

                    }
                }
                
            }
             html +=html1;
            }else{
               if(update_reqst[i].attribute_name == "action_button"){
                   $('#action_accept').show();
                   $('#action_progress').hide();
               }else if(update_reqst[i].attribute_name == "qualifier"){
                     qualifier_id=update_reqst[i].attribute_value;
                }else if(update_reqst[i].attribute_name == "allocation_matrix"){
                    $('#allocation').val(update_reqst[i].attribute_value);
                }else if(update_reqst[i].attribute_name == "max_permission"){
                    val= update_reqst[i].attribute_value.split('-');
                    var days,Hours;
                    if(val[1] == "1"){
                        days ='<input id="days" checked name="timeframe" value="1" type="radio">';
                        Hours ='<input id="hours" name="timeframe" value="0" type="radio">';
                    }else if(val[1] == "0"){
                        Days ='<input id="days" name="timeframe" value="1" type="radio">';
                        Hours ='<input id="hours" checked name="timeframe" value="0" type="radio">';
                    }
                    html += '<div class="row">'+
                                '<div class="col-md-4"><label> Max Permissible Timeframe</label></div>'+
                                '<div class="col-md-8">'+
                                        '<div class="row">'+
                                            '<div class="col-md-3">'+
                                                '<label for="days">Days '+ 
                                                days+
                                            '</label></div>'+
                                            '<div class="col-md-3">'+
                                                '<label for="hours"> Hours '+ 
                                                Hours+
                                            '</label></div>'+
                                            '<div class="col-md-6">'+
                                                '<input value="'+val[0]+'" name="timeframe_value" class="form-control" type="text">'+
                                                '<span class="error-alert"></span>'+
                                            '</div>'+
                                        '</div>'+
                                '</div>'+
                            '</div>';
                }else if(update_reqst[i].attribute_name == "expected_close_date"){
                    html += '<div class="row">'+
                                '<div class="col-md-4"><label>Expected Close Date<label></div>'+
                                '<div class="col-md-8">'+
                                    '<input class="form-control" id="attr_expected_close_date" name="expected_close_date" placeholder="'+update_reqst[i].attribute_value+'"  type="text">'+
                                '<span class="error-alert"></span>'+
                                '</div>'+
                            '</div>';
                }else if(update_reqst[i].attribute_name == "document_upload"){
                   /*html += '<div class="row">'+
                                 '<div class="col-md-4"><label class="label1">Document upload</label></div>'+
                                 '<div class="col-md-8">'+
                                     '<input type="file" name="userfile[]" id="document_upload"  class="form-control closeinput" multiple style="display:block">'+
                                 '<span class="error-alert"></span>'+
                                 '</div>'+
                             '</div>';*/
                }else{
                    if(update_reqst[i].lookup_value!=null || update_reqst[i].lookup_value!=''){
                        html += '<div class="row">'+
                                '<div class="col-md-4"><label class="label1 '+update_reqst[i].attribute_name+'" for="cust_'+id+'">'+update_reqst[i].lookup_value+'</label></div>'+
                                '<div class="col-md-8">'+
                                    '<input class="form-control custom-field" id="cust_'+id+'" name="'+update_reqst[i].attribute_name+'" placeholder="'+update_reqst[i].attribute_value+'" type="text">'+
                                    '<span class="error-alert"></span>'+
                                '</div>'+
                            '</div>';
                    }
                }
            }
        }
        html += '<div class="row">'+
                '<div class="col-md-4"><label class="label1" for="remarks" >Remarks*</label></div>'+
                '<div class="col-md-8">'+
                    '<textarea class="form-control" id="remarks"name="remarks"></textarea>'+
                     '<span class="error-alert"></span>'+
                '</div>'+
                '</div>';
                $("#update_req_popup .modal-body .form").html('').html(html);
                $("#update_req_popup").modal("show");
        }
    });
}
function cancel1(id){
$('#'+id).modal('hide');
}
function save_details(){
    var formData = new FormData($('#support_progress_form')[0]);
    if($("#update_req_popup #remarks").val()==""){
        $("#update_req_popup #remarks").closest(".row").find(".error-alert").text("Remarks is required");
        return false;
    } else {
        $("#update_req_popup #remarks").closest(".row").find(".error-alert").text("");
    }
    loaderShow();
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: "<?php echo site_url('sales_supportController/save_stage_attributes_alt'); ?>",
        data: formData,
        dataType : 'json',
        cache: false,
        contentType: false,
        processData: false,
        success : function(data){
            if(error_handler(data)) {
                    return;
            }
             loaderHide();
            if (data.status==false) {
                cancel1();
                $('#error_modal').modal('show');
                var str = '';
                $('#error_table #tablebody').empty();
                for (var i = 0; i < data.errors.length; i++) {
                    str += "<tr><td>"+(i+1)+"</td><td>"+ data.errors[i].error + "</td><td>" + data.errors[i].name + "</td></tr>";
                }
                $('#error_table #tablebody').append(str);
            }else{
                    alert('Data is saved successfully');
                    $('#update_req_popup').modal('hide');
                    stage_history();
            }
        }
    });
    }
function progress_check(){
    if($("#update_req_popup #remarks").val()==""){
        $("#update_req_popup #remarks").closest(".row").find(".error-alert").text("Remarks is required");
        return false;
    } else {
        $("#update_req_popup #remarks").closest(".row").find(".error-alert").text("");
    }
    var formData = new FormData($('#support_progress_form')[0]);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: "<?php echo site_url('sales_supportController/save_stage_attributes'); ?>",
        data: formData,
        dataType : 'json',
        cache: false,
        contentType: false,
        processData: false,
        success : function(data){
            if(error_handler(data)) {
                    return;
            }
            console.log(data);
            loaderHide();
            if(data==1){
                 alert('Data is saved successfully');
                 opp_progress_final();
                 $('#update_req_popup').modal('hide');
            }else{
                if(data.fileCheck==1){
                    if(data.qualifier!=0){
                        setup_questionnaire(data.qualifier);
                    }else{
                        $('#check_progression').hide();
                        $('#stage_progression').show();
                        $('#approve_progression').hide();
                        $('#accept_progression').show();
                        
                    }
                }else{
                    alert("Its mandatory to upload the file");
                }
            }
        }
    });
}
function opp_progress_final(){
    var formData = new FormData($('#support_progress_form')[0]);
     loaderShow();
    $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: "<?php echo site_url("sales_supportController/progress_nextstage"); ?>",
            data: formData,
            dataType : 'json',
            cache: false,
            contentType: false,
            processData: false,
            success : function(data){
                    if(error_handler(data)) {
                            return;
                    }	
                    if(data==5){
                        loaderHide();
                        alert("you have progressed stage successfully!");
                        $("#update_req_popup").modal("hide");
                        request_stage_view();
                    }
                }
                    
    });
}
var que_date;
function setup_questionnaire(data){
que_date = data;
        $("#Questionnaire").modal('show');
        $("#Questionnaire").css({
            "overflow-x": "hidden",
            "overflow-y": "auto"
        });
        $('#question-list').empty();
        var row = "";
        for(var i=0; i < data[0].question_data.length; i++){								
                if( data[0].question_data[i].mandatory_bit == "1" ){									
                        row +="<div class='questions star col-lg-12'><i class='fa fa-star-half-o' aria-hidden='true'></i><h4 id='"+data[0].question_data[i].question_id+"'>"+ (i+1)+" ) " + data[0].question_data[i].question_text +"</h4>";
                }else{
                        row +="<div class='questions col-lg-12'><h4 id='"+data[0].question_data[i].question_id+"'>"+ (i+1)+" ) " + data[0].question_data[i].question_text +"</h4>";
                }
                if(data[0].question_data[i].question_type == 1 || data[0].question_data[i].question_type == 2){
                        row +="<ol type='a'>";
                        if(data[0].question_data[i].answer_data != null){
                                for(var j=0; j < data[0].question_data[i].answer_data.length; j++){
                                        row +="<li id='"+data[0].question_data[i].answer_data[j].answer_id+"'><label>";
                                        row +="<input type='radio' name='"+data[0].question_data[i].question_id+"'>";
                                        row +=data[0].question_data[i].answer_data[j].answer_text;
                                        row +="</label></li>";
                                }
                                row+="<input type='hidden' value='"+data[0].question_data[i].question_type+"' id='questiontype'/>"
                         }
                }
                if(data[0].question_data[i].question_type == 3){
                        row +="<div class='row'><div class='col-lg-6 col-sm-12 col-xs-12'><textarea rows='3' class='form-control text-ans'/></div></div>";
                }
                row +="</ol>";
                row +="</div>";
        }
        $("#lead_qualifier_id").val(data[0].lead_qualifier_id)
        $("#lead_qualifier_name").text(data[0].lead_qualifier_name)
        $('#question-list').append(row);			
}
function cancel_quest(){
    $("#Questionnaire").modal("hide");
}
function SubmitQpaper(){
    var mainObj={};
    var someObj=[];
    var someObj1=[];
    var totalQuestions=0;
    var selectedQuestions=0;
    $(".questions").each(function(){
        if($(this).hasClass('star')){
            totalQuestions++;
            if($(this).find("textarea").length > 0){
                $(this).find("textarea").each(function(){
                    if($(this).val()==""){
                        return;
                        $("#mandatory").text("All Questions marked with an asterisk are manadatory");
                    }else{
                        selectedQuestions++;
                        someObj1.push({
                                "ans":$(this).val(), 
                                "quesid":$(this).closest(".col-lg-12").find("h4").attr("id")
                        });
                        $("#mandatory").text("");
                    }
                });
            }else{
                $(this).find("input:radio").each(function(){
                    if($(this).is(":checked")){
                        selectedQuestions++;	
                        someObj.push({
                                "ansid":$(this).closest("li").attr("id"),
                                "attempted_ans_txt":$(this).closest("li").text(),
                                "quesid":$(this).closest("ol").siblings("h4").attr("id"),
                                "questype":$(this).closest("ol").find("input[type=hidden]").attr("value")
                        });
                        return false;
                    }
                });
            }
            }else{
                if($(this).find("textarea").length > 0){
                    $(this).find("textarea").each(function(){							
                        someObj1.push({
                            "ans":$(this).val(), 
                            "quesid":$(this).closest(".col-lg-12").find("h4").attr("id")
                        });
                    });
                }else{
                    $(this).find("input:radio").each(function(){
                        if($(this).is(":checked")){	
                            someObj.push({
                                "ansid":$(this).closest("li").attr("id"),
                                "attempted_ans_txt":$(this).closest("li").text(),
                                "quesid":$(this).closest("ol").siblings("h4").attr("id"),
                                "questype":$(this).closest("ol").find("input[type=hidden]").attr("value"),
                                "ans_txt":$(this).closest("ol").find("input[type=hidden]").attr("value")
                            });
                        }
                    });
                }
            }
    });
    if(totalQuestions != selectedQuestions){
        $("#mandatory").text("All Questions marked with an asterisk are manadatory.");
        return;
    }else{
        $("#mandatory").text("");
        mainObj.lead_qualifier_id=qualifier_id;
        mainObj.stage_id=request_details.request_stage;
        mainObj.rep_id="<?php echo $this->session->userdata('uid');?>";
        mainObj.opp_cust_id=request_details.opp_cust_id;
        mainObj.request_id=request_details.request_id;
        mainObj.cycle_id=request_details.cycle_id;
        mainObj.process=request_details.process_type;
        mainObj.type1_2=someObj;
        mainObj.type3=someObj1;
        mainObj.que_date=que_date;
        
        for(q=0; q< que_date[0].question_data.length; q++){
            /*------------------------------------for type:1 question -----------------------------------------------*/
            if(que_date[0].question_data[q].question_type == "1" || que_date[0].question_data[q].question_type == "2"){
                for(Sq1=0; Sq1< mainObj.type1_2.length; Sq1++){
                    que_date[0].question_data[q]["attempted_ans"]="";
                    if(mainObj.type1_2[Sq1].quesid  == que_date[0].question_data[q].question_id){
                        que_date[0].question_data[q]["attempted_ans"] = mainObj.type1_2[Sq1].attempted_ans_txt;
                        break;
                    }
                }
            }
        
            /*------------------------------------for type:3 question -----------------------------------------------*/
        
            if(que_date[0].question_data[q].question_type == "3"){
                for(Sq3=0; Sq3< mainObj.type3.length; Sq3++){
                    if(mainObj.type3[Sq3].quesid  == que_date[0].question_data[q].question_id){
                        que_date[0].question_data[q].answer = mainObj.type3[Sq3].ans;
                    }
                }
            }
        }
        loaderShow();
        $.ajax({
            type:"post",
            cache:false,
            url:"<?php echo site_url('sales_supportController/check_qualifier');?>",
            dataType : 'json',
            data:JSON.stringify(mainObj),
            success: function (data) {
                if(error_handler(data)) {
                  return;
                }
                if (data == 0){
                    alert("Successfully answering the qualifier is mandatory to progress the stage.");
                    loaderHide();
                }else{
                    loaderHide();
                    opp_progress_final();
                    cancel_quest();
                    $('#check_progression').hide();
                    $('#stage_progression').show();
                }
            }
        });
    }
}
function reject_stage(){
loaderShow();
        var formData = new FormData($('#support_progress_form')[0]);
        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: "<?php echo site_url("sales_supportController/reject_stage"); ?>",
            data: formData,
            dataType : 'json',
            cache: false,
            contentType: false,
            processData: false,
            success : function(data){
                    if(error_handler(data)) {
                            return;
                    }
                     loaderHide();
            }
        });

}
function close_request(){
$('#close_req').modal('show');
}
function close_support(){
    
    var req_id=$('#req_id').val();
    if($.trim($("#close_remarks").val()) == ""){
        $("#close_remarks").next(".error-alert").text("Remarks is required.");
         $("#close_remarks").focus();
        return;
    }else{
         $("#close_remarks").next(".error-alert").text("");
    }
    var addObj={};
   addObj.remarks=$('#close_remarks').val();
   addObj.req_id=req_id;
   loaderShow();
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('sales_supportController/close_request'); ?>",
        dataType: "json",
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){
          if(error_handler(data)){
            return;
            }
            if(data==1){
                 loaderHide();
               $("#update_req_popup").modal("hide");
               $("#close_req").modal("hide");
               request_stage_view();
            }
        }
    });

}
function request_history(){
  var addObj={};
  addObj.req_id=request_details.request_id;
        $.ajax({
            type:"post",
            cache:false,
            url:"<?php echo site_url('sales_supportController/get_request_history');?>",
            dataType : 'json',
            data:JSON.stringify(addObj),
            success: function (data){
                if(error_handler(data)) {
                  return;
                }
                 var assign_actions1 =    []
                var history = data;
                if(history) {
                $('#viewHistory').modal('show')
                $("#opp_history_div").removeAttr('style');	
                $('#histroy_tablebody').empty();
                var mapping_ids = [];
                for (var i = 0; i < history.length; i++) {
                    var action = history[i].action.split('_').join(' ');
                    assign_actions1.push(action);
                    console.log(assign_actions1);
                    /*-----------------------------------------*/
                        if (mapping_ids.indexOf(history[i].mapping_id) < 0) {
                                mapping_ids.push(history[i].mapping_id);
                                                                    /*
                                                                    created 
                                                                    ownership accpted
                                                                    ownership accepted
                                                                    stage accpted
                                                                    updated
                                                                    passed qualifier
                                                                    failed qualifier
                                                                    stage progressed
                                                                    rejected
                                                                    */
                                var assign_actions =    [
                                                            'stage assigned',
                                                            'ownership assigned', 
                                                            'stage reassigned', 
                                                            'ownership reassigned',
                                                            'ownership accpted',
                                                            'created',
                                                            'passed qualifier'
                                                        ];
                                
                                var accept_actions = ['stage accepted', 'ownership accepted'];
                                var closed_actions = ['closed won', 'temporary loss', 'permanent loss'];
                                var action = history[i].action.split('_').join(' ');
                                var from_name = history[i].from_user_name;
                                var to_name = history[i].to_user_name;
                                var module = history[i].module;
                                if(module == 'sales'){
                                    module = 'Executive';
                                }else{
                                    module = history[i].module;
                                }
                                var stage_name = history[i].stage_name;
                                var remarks = history[i].remarks;
                                var timestamp = history[i].timestamp;
                                 
                                /*"<br /><b>Stage</b> - " + stage_name + */
                                /*on <h5 style='display:inline;'>" + timestamp + "</h5>*/
                                var rowhtml = '';
                                if (action == 'created') {
                                    rowhtml =   "<tr class='info'>"+
                                                    "<td>"+
                                                        "<div class='created'>"+
                                                            "<div>"+
                                                                "<h4 style='display:inline;'>"+
                                                                    "<i class='fa fa-money fa-fw'></i>"+
                                                                    capitalizeFirstLetter(action)+
                                                                "</h4>"+                                                        
                                                                " for "+ request_details.oppo_cust_name + 
                                                            "</div>"+                                                        
                                                            "<div><strong> By </strong>- " + from_name + " ("+capitalizeFirstLetter(module)+")</div>"+
                                                            "<strong>Stage </strong>- " + stage_name;
                                        if((remarks != null) && (remarks.length > 0)) {
                                            rowhtml +="<div>Remarks - " + remarks +"</div>";
                                        }
                                        rowhtml += "<br><small style='color:#777777'>" + timestamp + "</small>"+
                                                        "</div>"+
                                                    "</td>"+
                                                "</tr>";
                                } 
                                else if (action == 'ownership assigned'){
                                    assigned_to = 0;
                                    assigned_to_names='';
                                    for(var c = 0; c < history.length; c++){
                                        if (history[c].mapping_id == history[i].mapping_id) {
                                            if(history[c].module == 'sales'){
                                                history[c].module = 'Executive';
                                            }else{
                                                history[c].module = history[c].module;
                                            }
                                            assigned_to++;
                                            assigned_to_names += '<li class="text-left">'+history[c].to_user_name+' ('+capitalizeFirstLetter(history[c].module)+')</li>';
                                        }
                                    }
                                    assigned_to_names = "<ul >"+assigned_to_names+"</ul>";
                                    fa_user = 'fa-user';
                                    if(assigned_to > 1){
                                        fa_user = 'fa-users';
                                        to_name = assigned_to + " users <span class='glyphicon glyphicon-info-sign' data-placement='right' data-trigger='hover' data-html='true' data-title='"+assigned_to_names+"' data-toggle='tooltip'></span>";
                                    }
                                    rowhtml = "<tr><td><div class='assigned'>"+ 
                                              "<div>"+
                                              "<h4 style='display:inline;'>"+
                                              "<i class='fa "+fa_user+" fa-fw'></i>"+
                                              capitalizeFirstLetter(action)+
                                              "</h4> to "+ to_name + "</div>";
                                      
                                    if ((remarks != null) && (remarks.length > 0)){
                                        rowhtml += "<div>Remarks - " + remarks +"</div>";
                                    }
                                    rowhtml +=  '<small style="color:#777777">' + timestamp + '</small>'+
                                                '</div></td></tr>';
                                }
                                else if (action == 'ownership accepted' || 
                                         action == 'ownership accpted' ||
                                         action == 'stage accpted'){
                                    rowhtml =   '<tr> <td><div class="accepted">'+ 
                                                '<div><h4 style="display:inline;color:green;">'+ 
                                                '<i class="fa fa-user-plus fa-fw"></i>'+
                                                capitalizeFirstLetter(action)+' ('+module+')'+
                                                '</h4></div>'+
                                                '<div><strong>By </strong>- '+ to_name + '</div>';
                                    if ((remarks != null) && (remarks.length > 0)){
                                        rowhtml += "<div>Remarks - " + remarks +"</div>";
                                    }
                                    rowhtml +=  '<small style="color:#777777">' + timestamp + '</small>'+
                                                '</div></td></tr>';
                                }
                                
                                else if(action == 'updated'){
                                    rowhtml = `<tr> <td><div class="remarks"> 
                                                <div><h4 style='display:inline;'> <i class="fa fa-pencil fa-fw"></i>`+capitalizeFirstLetter(action)+`</h4> </div>
                                                <div><strong>By </strong>- ` + from_name + `</div>`;
                                    if ((remarks != null) && (remarks.length > 0)) {
                                            rowhtml +="<div>Remarks - " + remarks +"</div>";
                                    }
                                    rowhtml += "<small style='color:#777777'>" + timestamp + "</small></div></td></tr>";
                                }
                                else if (action == 'passed qualifier'){
                                    rowhtml = `<tr> <td><div class="passed_qualifier"> 
                                                <div><h4 style='display:inline;color:green;'><i class="fa fa-check-square fa-fw"></i>Qualifier Passed</h4> </div>
                                                <div><strong> By </strong>- ` + from_name + `</div>
                                                <strong>Stage </strong>-` + stage_name + `<div></div>`;
                                    rowhtml += "<small style='color:#777777'>" + timestamp + "</small></div></td></tr>";		
                                }
                                else if (action == 'failed qualifier'){
                                    rowhtml = `<tr> <td><div class="passed_qualifier"> 
                                                <div><h4 style='display:inline;color:red;'><i class="fa fa-minus-square fa-fw"></i> Qualifier Failed</h4></div>
                                                <div><strong> By </strong>- ` + from_name + `</div>
                                                <strong>Stage </strong>-` + stage_name + `<div></div>`;
                                    rowhtml += "<small style='color:#777777'>" + timestamp + "</small></div></td></tr>";
                                }							
                                else if (action == 'stage progressed'){
                                    rowhtml = `<tr> <td><div class="stage_changed"> 
                                                <div><h4 style='display:inline;'><i class="fa fa-step-forward fa-fw"></i>`+capitalizeFirstLetter(action)+`</h4>	</div>
                                                <div><strong>at Stage </strong>-` + stage_name + `<div></div>
                                                <div><strong>By </strong>- ` + from_name + `</b></div>`;
                                    if ((remarks != null) && (remarks.length > 0)) {
                                            rowhtml +="<div>Remarks - " + remarks +"</div>";
                                    }
                                    rowhtml += "<small style='color:#777777'>" + timestamp + "</small></div></td></tr>";
                                }
                                else if (action == 'rejected'){
                                    rowhtml = `<tr class='danger'> <td><div class="rejected"> 
                                                <div><h4 style='display:inline;color:red;'> <i class="fa fa-ban fa-fw"></i> Stage `+capitalizeFirstLetter(action)+`</h4>
                                                at ` + stage_name + ` </div>
                                                <div><strong> By </strong>- ` + from_name + `</div>`;
                                    if ((remarks != null) && (remarks.length > 0)) {
                                            rowhtml +="<div>Remarks - " + remarks +"</div>";
                                    }
                                    rowhtml += "<small style='color:#777777'>" + timestamp + "</small></div></td></tr>";
                                }
                                /*else if (closed_actions.indexOf(action) >= 0){
                                    var class_col='danger';
                                    var fa_icon = 'fa fa-times fa-fw';
                                    if (action == 'closed won') {
                                            class_col = 'success';
                                            fa_icon = 'fa fa-check fa-fw';
                                    } else if (action == 'temporary loss') {
                                            class_col='warning';
                                            fa_icon = 'fa fa-exclamation fa-fw';
                                    }
                                    rowhtml = `<tr class='`+class_col+`'> <td><div class="accepted"> 
                                                <div><h4 style='display:inline;'><i class="`+fa_icon+`"></i>`+capitalizeFirstLetter(action)+`</h4> </div>
                                                <div><strong> By </strong>- `+ to_name + `</div>
                                                <strong>Stage </strong>-` + stage_name;
                                    if ((remarks != null) && (remarks.length > 0)) {
                                            rowhtml +="<div>Remarks - " + remarks +"</div>";
                                    }
                                    rowhtml += `<h6 style='display:inline;color:#777777'>` + timestamp + `</h6></div></td></tr>`;
                                }
                                */   
                                else {
                                    rowhtml += "<tr> <td>"+history[i].action + " by " + from_name + "</td></tr>";
                                }
                                $('#histroy_tablebody').append(rowhtml);
                        }
                }
                var objDiv = document.getElementById("opp_history_div");
                objDiv.scrollTop = objDiv.scrollHeight;
                }
                
            }
        });
}
function view_req_documents() {
        var addObj = {};
        addObj.req_id=request_details.request_id;
        $.ajax({
            type : "POST",
            url:"<?php echo site_url('sales_supportController/get_documents');?>",
            dataType : 'json',
            data : JSON.stringify(addObj),
            cache : false,
            success : function(data){
                    if(error_handler(data)) {
                            return;
                    }
                    $('#viewDocument').modal('show');
                    frame_documents_uploaded(data);
            }
        });
}
function frame_documents_uploaded(data) {
    $("#viewDocumentsec").empty();
    var row1 = '';
    for(i=0;i<data.length;i++){
            row1 +='<tr><td style="text-align:left">'+(i+1)+'</td><td style="text-align:left">'+data[i].stage_name+'</td><td style="text-align:left">';
            var path = data[i].path;
            var file_name = path.split('/').pop();
            var format = file_name.split('.').pop();
            var file_name = file_name.split('.')[0];
            if(format == 'doc' || format == 'docx' || format == 'pdf' || format == 'rtf' || format == 'txt' || format == 'xls' || format == 'csv'){
                    row1 += '<ul class="doc_list"><li style="margin-left: -38px;"><a href="<?php echo site_url();?>'+data[i].path+'" target="_blank"><i class="fa fa-fw fa-file-text-o" aria-hidden="true"></i> '+file_name+' (by '+data[i].doc_user_id+')</a><i class="fa fa-fw fa-clock-o" data-toggle="tooltip" title="'+data[i].timestamp+'" aria-hidden="true"></i></li></ul>';
            }
            if(format == 'jpg' || format == 'jpeg' || format == 'gif' || format == 'bmp' || format == 'png'){
                    row1 += '<ul class="doc_list"><li style="margin-left: -38px;"><a href="<?php echo site_url();?>'+data[i].path+'" target="_blank"><i class="fa fa-fw fa-file-image-o" aria-hidden="true"></i>  '+file_name+' (by '+data[i].doc_user_id+')</a>  <i class="fa fa-fw fa-clock-o" data-toggle="tooltip" title="'+data[i].timestamp+'" aria-hidden="true"></i></li></ul>';
            }
            row1 +='</td></tr>';
    }
    $("#viewDocumentsec").append(row1);
    $('#viewDocument .table').DataTable();
    $('#viewDocument .table').width('100%');
}
function load_req_tasks(){
    $('#req_taskdetails').modal('show');
    $('#opp_task_div #tablebody').empty();
    $("#opp_task_div").css({
            'background':'url(<?php echo base_url();?>images/hourglass.gif)',
            'background-position':'center',
            'background-size':'30px',
            'background-repeat':'no-repeat'
    });
    var addObj = {};
    addObj.req_id=request_details.request_id;
    $.ajax({
            type : "POST",
             url:"<?php echo site_url('sales_supportController/scheduled_task');?>",
            dataType : 'json',
            data : JSON.stringify(addObj),
            cache : false,
            success : function(data){
                    if(error_handler(data)) {
                            return;
                    }
                    $("#opp_task_div").removeAttr('style');	
                    var rowhtml = '';
                    for (var i = 0; i < data.length; i++) {
                            rowhtml += "<tr>\
                                    <td>"+(i+1)+"</td>\
                                    <td>"+data[i].event_name+"</td>\
                                    <td>"+data[i].user+"</td>\
                                    <td>"+data[i].contact_name+"</td>\
                                    <td>"+data[i].activity+"</td>\
                                    <td>"+data[i].start_time+"</td>\
                                    <td>"+data[i].end_time+"</td>\
                                    <td>"+data[i].remarks+"</td>\
                            </tr>";
                    }
                    $('#opp_task_div #tablebody').append(rowhtml);
                    var objDiv = document.getElementById("opp_log_div");
                    objDiv.scrollTop = objDiv.scrollHeight;
            }		
    });	
}
function load_req_log(){
    $('#logdetails').modal('show');
    $('#opp_log_div #tablebody').empty();
        $("#opp_log_div").css({
                'background':'url(<?php echo base_url();?>images/hourglass.gif)',
                'background-position':'center',
                'background-size':'30px',
                'background-repeat':'no-repeat'
        });
        var addObj = {};
        addObj.req_id=request_details.request_id;
        $.ajax({
                type : "POST",
                url:"<?php echo site_url('sales_supportController/request_log');?>",
                dataType : 'json',
                data : JSON.stringify(addObj),
                cache : false,
                success : function(data){
                        if(error_handler(data)) {
                                return;
                        }
                        $("#opp_log_div").removeAttr('style');	
                        var rowhtml = '';
                        for (var i = 0; i < data.length; i++) {
                                rowhtml += "<tr>\
                                        <td>"+(i+1)+"</td>\
                                        <td>"+data[i].log_name+"</td>\
                                        <td>"+data[i].user+"</td>\
                                        <td>"+data[i].contact_name+"</td>\
                                        <td>"+data[i].activity+"</td>\
                                        <td>"+data[i].start_time+"</td>\
                                        <td>"+data[i].end_time+"</td>\
                                        <td>"+data[i].rating+"/4</td>\
                                        <td>"+data[i].remarks+"</td>\
                                        <td>"+data[i].path+"</td>\
                                </tr>";
                        }
                        $('#opp_log_div #tablebody').append(rowhtml);
                        var objDiv = document.getElementById("opp_log_div");
                        objDiv.scrollTop = objDiv.scrollHeight;
                }		
        });			
}
function close_popups(){
    $('#viewDocument').modal('hide');
    $('#logdetails').modal('hide');
    $('#req_taskdetails').modal('hide');
}

</script>
<?php require 'confetti.php' ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="loader">
  <center><h1 id="loader_txt"></h1></center>  
</div>
<?php require 'demo.php' ?>
<?php require 'sales_sidenav.php' ?>
<div class="content-wrapper body-content">
    <div class="col-lg-12 column" id="content">
        <div class="row header1">				
            <div class="col-xs-2 col-sm-2 col-md-1 col-lg-1 aa">
                <span class="info-icon">
                    <div>		
                        <img src="<?php echo site_url('/images/new/i_off.png'); ?>" 
                             onmouseover="this.src='<?php echo site_url('/images/new/i_ON.png'); ?>'" 
                             onmouseout="this.src='<?php echo site_url('/images/new/i_off.png'); ?>'" 
                             alt="info" width="30" height="30"  
                             data-toggle="tooltip" data-placement="right" title="Opportunity Details"/>
                    </div>
                </span>
            </div>
            <div class="col-xs-8 col-sm-8 col-md-10 col-lg-10 pageHeader1 aa">
                <h2><label id="opp_name1">Request Details</label><span class="error-alert"></span></h2>	
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
                <div class="text-right">
                    <a class="btn"  onclick="view_req_documents()">Documents</a>&nbsp;&nbsp;
                    <a class="btn" onclick="request_history()">History</a>
                </div>
            </div>
        </div>
            <div class="row opp_summary_row">
                <div class="panel-group">
                    <div class="panel panel-default" id="opp_summary_panel">
                        <a data-toggle="collapse" href="#opp_summary" onclick="toggle_opp_summary('#opp_summary')" class aria-expanded="true">
                            <div class="panel-heading opp_summary_heading">
                                <b><h4 class="panel-title opp_stage" id="opp_summary_title"> Hide Request Summary</h4></b>
                            </div>
                        </a>
                    <div id="opp_summary" class="panel-collapse collapse in ">
                <div class="panel-body">
                            <div class="row">
                                    <center><h4 class="opp_stage" id="opp_name"></h4></center>
                            </div>
                                    <div class="row">
                                        <div class="col-md-6 oppo_details">
                                            <div class="row">
                                                    <div class="col-md-3 apport_label">
                                                                    <label for="oppo_cust_name">Lead/Customer</label> 
                                                    </div>
                                                    <div class="col-md-9">
                                                                    <label id="oppo_cust_name"></label>
                                                    </div>
                                            </div>
                                            <div class="row">
                                                    <div class="col-md-3 apport_label">
                                                                    <label for="contact_name">Contact(s)</label> 
                                                    </div>
                                                    <div class="col-md-9">
                                                                    <label id="contact_name"></label>
                                                    </div>
                                            </div>
                                             <div class="row">
                                                    <div class="col-md-3 apport_label">
                                                                    <label for="contact_name">Contact Number</label> 
                                                    </div>
                                                    <div class="col-md-9">
                                                                    <label id="contact_num"></label>
                                                    </div>
                                            </div>
                                            <div class="row">
                                                    <div class="col-md-3 apport_label">
                                                                    <label for="product_name">Product</label> 
                                                    </div>
                                                    <div class="col-md-9">
                                                                    <label id="product_name"></label>
                                                    </div>
                                            </div>	
                                            <div class="row">
                                                    <div class="col-md-3 apport_label">
                                                            <label for="process_type">Process Type</label> 
                                                    </div>
                                                    <div class="col-md-9">
                                                            <label  id="process_type"></label>
                                                    </div>
                                            </div>	
                                            <div class="row">
                                                    <div class="col-md-3 apport_label">
                                                                    <label for="indusrty_name">Industry</label> 
                                                    </div>
                                                    <div class="col-md-9">
                                                                    <label  id="indusrty_name"></label>
                                                    </div>
                                            </div>	
                                            <div class="row">
                                                <div class="col-md-3 apport_label">
                                                                <label for="location_name">Location</label> 
                                                </div>
                                                <div class="col-md-9">
                                                                <label  id="location_name"></label>
                                                </div>
                                            </div>
                                            </div>						
                                <div class="col-md-6 oppo_details">
                                    <div class="row">
                                        <div class="col-md-3 apport_label">
                                            <label for="stage_name">Current Stage</label> 
                                        </div>
                                        <div class="col-md-9">
                                            <label  id="stage_name"></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 apport_label">
                                            <label for="stage_name">Contact email</label> 
                                        </div>
                                        <div class="col-md-9">
                                            <label  id="contact_email"></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 apport_label">
                                            <label for="owner_mgr_name">Manager</label> 
                                        </div>
                                        <div class="col-md-9">
                                            <label  id="owner_mgr_name"></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 apport_label">
                                            <label for="creator_name">Owner</label> 
                                        </div>
                                        <div class="col-md-9">
                                            <label id="creator_name"></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 apport_label">
                                            <label for="stage_owner">Stage Owner</label> 
                                        </div>
                                        <div class="col-md-9">
                                            <label  id="stage_owner"></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 apport_label">
                                            <label for="req_critic">Criticality</label> 
                                        </div>
                                        <div class="col-md-9">
                                            <label  id="req_critic"></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 apport_label">
                                            <label for="com_tat">Committed TAT</label> 
                                        </div>
                                        <div class="col-md-9">
                                            <label  id="com_tat"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <textarea class="form-control"  id="display_note" disabled rows="5">
                                    </textarea>
                                </div>        
                            </div>
                            
                    <div class="row">
                    <center>
                            <input type="button" class="btn opp_details_btns" value="Scheduled Activites" onclick="load_req_tasks()" data-toggle="modal">
                            <input type="button" class="btn opp_details_btns" value="Completed Activites" onclick="load_req_log()"  data-toggle="modal">
                    </center>
		</div>
                            <div class="row" > 
                                <div class="col-md-12" id="accordion_wrapper">

                                </div>
                            </div>
                    
                </div>
                </div>
                    </div>
                </div>
                
            </div>
        <div id="error_modal" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel1()">x</span>	
					<center><h4 class="modal-title">Errors</h4></center>
				</div>				
				<div class="modal-body">
					<div class="row">
						<label>  Something went wrong... Couldn't proceed further</label>
					</div>
					<div class="row">
						<table class="table" id="error_table">
							<thead>  
								<tr>
									<th class="table_header" style="text-align: left;">#</th>
									<th class="table_header" style="text-align: left;">Remarks</th>
									<th class="table_header" style="text-align: left;">Filename</th>
								</tr>
							</thead>  
							<tbody id="tablebody"></tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn" onclick="cancel1()" value="Close" >
				</div>
			</div>
		</div>
	</div>
        <div id="close_req" class="modal fade" data-backdrop="static" data-keyboard="false" style="z-index:1111">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                   <span class="close"  data-dismiss="modal" aria-hidden="true">&times;</span>
                    <h4 class="modal-title">Close Request</h4>
                </div>
                <div class="modal-body">
                    <div class="row" id="remarks">
                        <div class="col-md-12"><textarea class="form-control" id="close_remarks" placeholder="Enter your remarks ( Mandatory )."></textarea><span class="error-alert"></span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true" >Cancel</button>
                    <button class="btn btn-default" onclick="close_support()">Submit</button>
                </div>
            </div>
        </div>
    </div>
        <div id="Questionnaire" class="modal fade" data-backdrop="static" data-keyboard="false" style="z-index:1111">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="cancel_quest()">x</span>	
					<h4 class="modal-title">Qualifier</h4>
				</div>				
				<div class="modal-body">									
					<div class="row">
						<div class="col-lg-12">
							<center>
								<h2>Questions for <span id="lead_qualifier_name"></span></h2>
								<p>Mandatory fields are marked with an asterisk ( <i class='fa fa-star-half-o' aria-hidden='true'></i> ).</p>
							</center>
						</div>
					</div>
					<div class="row">
						<input type="hidden" id="lead_qualifier_id">
						<input type="hidden" id="stage_id">
						<input type="hidden" id="rep_id">
						<input type="hidden" id="lead_id">
						<input type="hidden" id="opp_id">
						 <form>
							<div class="col-lg-12" id="question-list">					
							</div>
						</form>
						<div class="go-top">
						<i class="fa fa-arrow-circle-o-up fa-3x" aria-hidden="true"></i>
						</div>
					</div>
					<br>
					<span id="mandatory" class="error-alert" style="color:red"></span>
				</div>
				<div class="modal-footer">
					<center>
						<button type="button" class="btn btn-primary" id="submit_qual_btn" onclick="SubmitQpaper()" >Submit</button>
					</center>
				</div>
			</div>
		</div>
	</div>
            <div class="row static " style="text-align:center">
                <label>Current Stage</label>
                <h4 class="opp_stage cur_stage_name"></h4>
                <div id="new_req_progress">
                <input type="button" class="btn" href="#updatePopup" onclick="update_req_popup();" value="Update" id="opp_progress_btn">
                </div>
            </div>
	</div>
    <div id="update_req_popup" class="modal fade" data-backdrop="static" data-keyboard="false" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="close" onclick="cancel1('update_req_popup')">x</span>	
                        <h4 class="modal-title">Update and Progress</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row info">
                           <center><label> <h2>Current Stage</h2> </label></center>
                            <h4 class="opp_stage cur_stage_name"></h4>
                            <hr>
                        </div>
                      <form name="support_progress_form" id="support_progress_form" method="POST" enctype="multipart/form-data">	
                        <div class="row form"></div>
                        <input type="hidden" name="req_id" id="req_id">
                         <input type="hidden" name="cyc_id" id="cyc_id">
                          <input type="hidden" name="sta_id" id="sta_id">
                           <input type="hidden" name="oppor_id" id="oppor_id">
                           <input type="hidden" name="allocation" id="allocation">
                           <input type="hidden" name="process_id" id="process_id">
                           <input type="hidden" name="next_stage_id" id="next_stage_id">
                           <input type="hidden" name="reject_owner" id="reject_owner">
                           
                      </form>
                    </div>
                    <div class="modal-footer">
                         <div id="action_accept" class="none">
                             <input type="button" class="btn " onclick="progress_check();" value="Approve" id="approve_progression">
                             <input type="button" class="btn none" onclick="opp_progress_final();" value="Approve" id="accept_progression">
                             <input type="button" class="btn " onclick="reject_stage();" value="Reject" id="opp_reject" >
                             <input type="button" class="btn" onclick="close_request()" value="Close Request" >
                         </div>
                         <div id="action_progress">
                          <input type="button" class="btn" onclick="save_details()" value="Save" >
                         <input type="button" class="btn" onclick="progress_check()" value="Progress" id="check_progression">
                          <input type="button" class="btn none" onclick="opp_progress_final()" value="Progress" id="stage_progression" >
                         <input type="button" class="btn" onclick="close_request()" value="Close Request" >
                         </div>
                    </div>
                </div>
            </div>
	</div>
        <div id="viewHistory" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <span class="close" onclick="cancel1('viewHistory')">x</span>	
                            <center><h4 class="modal-title">Opportunity History</h4></center>
                        </div>
                        <div class="modal-body">
                            <div class="row opportunity_history" id="opp_history_div">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="table_header" style="text-align: center;">
                                                <label id="history_table_opp_name"></label>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="histroy_tablebody"></tbody>    
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn" onclick="cancel1('viewHistory')" value="Close" >
                        </div>
                    </div>
		</div>
	</div>
    <div id="viewDocument" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="close_popups()">x</span>	
					<h4 class="modal-title">View Documents</h4>
				</div>
				<div class="modal-body">
					<div class="row documents">
						<table class="table">
							<thead>
								<th class="table_header">#</th>
								<th class="table_header">Stage Name</th>
								<th class="table_header">Document Name</th>
							</thead>
							<tbody id="viewDocumentsec">
							
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn" onclick="close_popups()" value="Close">
				</div>
			</div>
		</div>
	</div>
    <div id="req_taskdetails" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="close_popups()">x</span>	
					<center><h4 class="modal-title">Scheduled Activities</h4></center>
				</div>				
				<div class="modal-body">
					<div class="row">
						<center>
							<a href="<?php echo site_url('sales_calendarController')?>"> <label>Schedule a New Activity</label></a>
						</center>
					</div>
					<div class="row opportunity_history" id="opp_task_div">
						<table class="table">
							<thead>  
								<th class="table_header">#</th>
								<th class="table_header">Event Name</th>
								<th class="table_header">Scheduled by</th>
								<th class="table_header">Contact Person</th>
								<th class="table_header">Activity</th>
								<th class="table_header">Starts at</th>				
								<th class="table_header">Ends at</th>		
								<th class="table_header">Remarks</th>	
							</thead>  
							<tbody id="tablebody"></tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn" onclick="close_popups()" value="Close" >
				</div>
			</div>
		</div>
	</div>
    <div id="logdetails" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="close_popups()">x</span>	
					<center><h4 class="modal-title">Completed Activities</h4></center>
				</div>				
				<div class="modal-body">
					<div class="row">
						<center>
							<a href="<?php echo site_url('sales_mytaskController')?>"> <label>Log an Activity</label></a>
						</center>
					</div>					
					<div class="row opportunity_history" id="opp_log_div">
						<table class="table">
							<thead>  
									<th class="table_header">#</th>
									<th class="table_header">Activity Name</th>
									<th class="table_header">Logged by</th>
									<th class="table_header">Contact</th>
									<th class="table_header">Activity</th>
									<th class="table_header">Started on</th>
									<th class="table_header">Ended on</th>
									<th class="table_header">Rating</th>
									<th class="table_header">Remarks</th>
									<th class="table_header"></th>
							</thead>  
							<tbody id="tablebody"></tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn" onclick="close_popups()" value="Close" >
				</div>
			</div>
		</div>
	</div>
</div>
    		
<?php require ('footer.php'); ?>
</body>
</html>