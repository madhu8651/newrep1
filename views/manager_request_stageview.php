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
            if(data==0){
                loaderHide();
                alert("You can not view this page");
                window.history.back();
            }else{
                loaderHide();
                var contact1 = data.contact[0];
                request_details = data.request[0];
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
                $("#com_tat").text(moment(request_details.tat).format('DD-MM-YYYY'));
                $(".cur_stage_name").text(request_details.stage_name);
                $("#process_type").text(request_details.processtype);
                $("#contact_num").text(request_details.contact_number);
                $("#contact_email").text(request_details.contact_email);
                $("#display_note").text(request_details.remarks);
               
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
        }
});
}
function reassign(data) {
        var flagchk=0;
        var localObj = {};
        localObj.id = request_details['request_id'];
        $("#reassign_opp").modal("show");
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_supportrequestcontroller/assign_request');?>",
            dataType:'json',
            data:JSON.stringify(localObj),
            success: function(data) {
                console.log(data)
                if (error_handler(data)) {
                    return ;
                }
                $("#mgrlist").removeAttr('style');
                $("#mgrlist ul").empty();
                var multipl2 = "";
                if(data.length>0){
                    multipl2 = '<li class="targetrow1 none"> <label> <input type="checkbox" name="select_all_mgr" onclick="checkAllMgrs(this)"> Select All </label> </li>';
                
                    for(var i=0;i<data.length; i++){
                        if(data[i].sales_module=='0' && data[i].manager_module!='0'){
                            multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'"> '+data[i].user_name+' (Manager)<label></li>';
                        }
                        if(data[i].sales_module!='0' && data[i].manager_module=='0'){
                            multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'"> '+data[i].user_name+' (Executive)<label></li>';
                        }
                        if(data[i].sales_module!='0' && data[i].manager_module!='0'){
                            multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'"> '+data[i].user_name+' (Manager)<label></li>';
                            multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'"> '+data[i].user_name+' (Executive)<label></li>';
                        }
                    }
                }
                $("#mgrlist ul").append(multipl2);
                var list_length = $("#mgrlist ul li").length;
                if(list_length>0){
                    $(".targetrow1").show();
                }else{
                    $(".targetrow1").hide();
                }
            }
        });        
    } 
    function checkAllMgrs(e)    {
        $('li input:checkbox',$("#mgrlist")).prop('checked',e.checked);
    } 
    var finalArray ={};      
    function assign_save()  {
        finalArray['users'] = [];
        $(".mgrlist_sales, .mgrlist_manager").each(function(){
            if($(this).prop('checked')== true){
                var localObj = {};
                localObj['to_user_id'] = $(this).attr('id');
                localObj['module'] = $(this).val();
                finalArray['users'].push(localObj);
            }
        });
        if (finalArray['users'].length == 0) {
            alert('Select a user to assign to ');
            return;
        }

        finalArray['req_id'] = request_details['request_id'];
        finalArray['remarks'] = $("#assign_remarks").val();
        console.log(finalArray);
        loaderShow();
         $.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_supportrequestcontroller/reassign_request');?>",
            data:JSON.stringify(finalArray),
            dataType:'json',
            success: function(data) {
                if (error_handler(data)) {
                    return ;
                }
                console.log(data);
                loaderHide();
                if(data>=1) {
                    close_modal();
                }
                init_stageview_page();
            }
        });
    }
    function close_modal(){
        $('#reassign_opp').modal('hide');
        $("#mgrlist ul").empty();   
        $('.modal input[type="text"],#completed select,#addmodal select, textarea').val('');
        $('.modal input[type="radio"]').prop('checked', false);
        $('.modal input[type="checkbox"]').prop('checked', false);
        $(".targetrow1").hide();
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
                console.log(groups)
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
function cancel1(id){
$('#'+id).modal('hide');
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
                                
                                else {
                                    rowhtml += "<tr> <td>"+history[i].action + " by " + from_name + "</td></tr>";
                                }
                                $('#histroy_tablebody').append(rowhtml);
                        }
                }
                var objDiv = document.getElementById("opp_history_div");
                objDiv.scrollTop = objDiv.scrollHeight;
                }
                logDetailsFunc();
                $("#viewHistory ul.nav.nav-tabs li").removeClass('active');
                $("#scheduled_activity_li").addClass('active');
                
                $("#viewHistory .tab-content .tab-pane").removeClass('active').removeClass('in');
                $('#opp_history_div').addClass('active').addClass('in');
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
function logDetailsFunc(){
    var rowhtml="";
    var addObj = {};
    addObj.req_id=request_details.request_id;
    $.ajax({
        type : "POST",
        url:"<?php echo site_url('manager_supportrequestcontroller/audit_details');?>",
        dataType : 'json',
        data : JSON.stringify(addObj),
        cache : false,
        success : function(data){
            if(error_handler(data)) {
                    return;
            }
            rowhtml +='<table class="table"><thead>';
            rowhtml += "<tr>\
                        <th>SL No</th>\
                        <th>Request Name</th>\
                        <th>Stage</th>\
                        <th>Attribute Name</th>\
                        <th>Attribute Value</th>\
                        <th>Logged On</th>\
                        <th>Remarks</th>\
                </tr>";
            rowhtml +='</thead><tbody>';
            for (var i = 0; i < data.length; i++) {
                rowhtml += "<tr>\
                        <td>"+(i+1)+"</td>\
                        <td>"+data[i].request_name+"</td>\
                        <td>"+data[i].stage_name+"</td>\
                        <td>"+data[i].attribute_name.split('_').join(' ')+"</td>\
                        <td>"+data[i].attribute_value+"</td>\
                        <td>"+moment(data[i].time_stamp).format('DD-MM-YYYY')+"</td>\
                        <td>"+data[i].remarks+"</td>\
                </tr>";
            }
            rowhtml +='</tbody><table>';
            $("#logDetails").html('').html(rowhtml);
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
                            <center>
                                <input type="button" value="Reassign" onclick="reassign('ownership')" class="btn" id="reassign_btn">
                            </center>
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
                <div class="row">
                    <center>
                            <input type="button" class="btn opp_details_btns" value="Scheduled Activites" onclick="load_req_tasks()" data-toggle="modal">
                            <input type="button" class="btn opp_details_btns" value="Completed Activites" onclick="load_req_log()"  data-toggle="modal">
                    </center>
		</div>
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
    <div id="reassign_opp" class="modal fade" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close"  onclick="close_modal()">&times;</span>
                    <h4 class="modal-title">Reassign Stage</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="reassign_type">
                    <center>
                        <span style="color: darkgrey"> Selecting a Executive replaces rep owner</span>
                        <br>
                        <span style="color: darkgrey"> Selecting a Manager user replaces manager owner</span>
                    </center>
                    <hr>
                    <div class="row targetrow">
                        <div class="col-md-2">
                            <label for="mgrlist">Users</label> 
                        </div>
                        <div class="col-md-10">                                         
                            <div id="mgrlist" class="multiselect">
                            <ul></ul>
                            </div>
                            <span class="error-alert"> </span>
                        </div>
                    </div>
                    <div class="row targetrow">
                        <div class="col-md-2">
                            <label for="assign_remarks">Reassign Remarks</label> 
                        </div>
                        <div class="col-md-10">
                            <textarea cols="80" rows="5" placeholder="Enter remarks for reassigning Opportunity(s)" id="assign_remarks" class="form-control" style="margin-top: 5px;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn" onclick="assign_save()" value="Reassign">
                    <input type="button" class="btn" onclick="close_modal()" value="Cancel">
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
                           <input type="hidden" name="opp_id" id="opp_id">
                           <input type="hidden" name="allocation" id="allocation">
                           <input type="hidden" name="process_id" id="process_id">
                           <input type="hidden" name="next_stage_id" id="next_stage_id">
                           <input type="hidden" name="reject_owner" id="reject_owner">
                           
                      </form>
                    </div>
                    <div class="modal-footer">
                         <div id="action_accept" class="none">
                             <input type="button" class="btn" onclick="save_details()" value="Save" >
                             <input type="button" class="btn opp_btn1" onclick="progress_check();" value="Approve" id="opp_approve">
                              <input type="button" class="btn none" onclick="opp_progress_final()" value="Progress" id="stage_progression1" >
			     <input type="button" class="btn opp_btn1" onclick="reject_stage();" value="Reject" id="opp_reject">
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
                                <ul class="nav nav-tabs nav-justified">
					<li class="active" id="scheduled_activity_li">
                                            <a data-toggle="tab" href="#opp_history_div">History</a>
                                        </li>
                                        <li ><a data-toggle="tab" href="#logDetails">Audit Trail</a></li>
				</ul>
				<div class="tab-content">
                                    <div class="row opportunity_history tab-pane fade in active" id="opp_history_div">
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
                                    <div id="logDetails" class="tab-pane fade">
                                        
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn" onclick="cancel1('viewHistory')" value="Close" >
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
      <div id="viewDocument" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" onclick="close_popups();">x</span>	
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
					<input type="button" class="btn" onclick="close_popups();" value="Close">
				</div>
			</div>
		</div>
	</div>
</div>
<?php require ('footer.php'); ?>
</body>
</html>