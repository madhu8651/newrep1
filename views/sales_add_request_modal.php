
<script>
    $(document).ready(function(){
        $("#committed_tat").datetimepicker({
                ignoreReadonly:true,
                allowInputToggle:true,
                format:'lll',
                minDate: moment(),
        });
    });
function add_request(){
     $.ajax({
        type : "POST",
        url : "<?php echo site_url('sales_supportController/get_processType'); ?>",
        dataType : 'json',
        cache : false,
        success : function(data){
                loaderHide();
                if(error_handler(data)){
                    return;
                }
                $('#leadinfoAdd').modal('show');
                var select = $("#process_type"), options = "<option value=''>Choose Process Type</option>";
                select.empty();      
                for(var i=0;i<data.length; i++)	{
                        options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value+"</option>";            
                }
                select.append(options);
        },
        error:function(data){
            network_err_alert();
        }
    });
}
function get_details(){
    cust_id="";
    $("#client_contact").html("");
    var select_val = $("#request_for").val();
    if(select_val == "customer"){
            $(".cust_row").show();
            $(".opp_row").hide();
            $('#opportunity_ids option').remove();
            $('#product_id option').remove();
            get_customers();
    }
    if(select_val == "opportunity"){
            $(".cust_row").hide();
            $(".opp_row").show();
            $('#product_id option').remove();
            get_opportunity();
            $("#customer").typeahead("destroy");
    }
}
var cust_id="";
function get_customers(){
    cust_id="";
    $.ajax({ 
        type : "POST",
        url : "<?php echo site_url('sales_supportController/get_customers'); ?>",
        dataType : 'json',		
        cache : false,
        success : function(data){
            if(error_handler(data)){
                return;
            }
            var customerData = [];
            for(i=0; i<data.length; i++){
                customerData.push(data[i]);
                var dataSource= new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('customer_id','customer_name'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    local: customerData									
                });
            }
            if(customerData.length>0){
                dataSource.initialize();
                $('#customer').typeahead({
                        minLength: 0,
                        highlight: true,
                        hint: false
                },{ 
                        name: 'email',
                        display: function(item) {
                                return item.customer_name
                        },
                        source: dataSource.ttAdapter(),
                        suggestion: function(data) {
                                return '<b>' + data.customer_name + '</b>' 
                        }
                });
                $('#customer').on('typeahead:selected', function (e, datum){					
                        var match=1;
                        if($.trim($(this).text())== datum.customer_id){
                            match=0;
                            return;
                        }
                        if(match==0){
                            $('#customer').val("");
                            return;
                        }
                        if ($("#customer").length <= 1) {
                            $('#customer').closest("div").find("span.error-alert").text("");
                            loaderShow();
                            cust_id = datum.customer_id;
                            
                            var leadObj = {};
                            leadObj.customerid = datum.customer_id;
                            loaderShow();
                            $.ajax({ 	
                                type : "POST",
                                url : "<?php echo site_url('sales_supportController/get_contactsforCustomer'); ?>",
                                data:JSON.stringify(leadObj),
                                dataType : 'json',
                                cache : false,
                                success : function(data){
                                    loaderHide();
                                    if(error_handler(data)){
                                        return;
                                     }
                                    var select = $("#client_contact"), options = "";
                                    select.empty();      
                                    for(var i=0;i<data.length; i++)	{
                                        options += "<div><label><input type='checkbox' value='"+data[i].contact_id+"'/> "+ data[i].contact_name +"</label></div>";                                                }
                                    select.append(options);
                                }
                            });
                            $.ajax({ 	
                                type : "POST",
                                url : "<?php echo site_url('sales_supportController/get_CustomerProduct'); ?>",
                                data: JSON.stringify(leadObj),
                                dataType : 'json',
                                cache : false,
                                success : function(data){
                                    loaderHide();
                                    if(error_handler(data)){
                                        return;
                                    }
                                    var select = $("#product_id"), options = "<option value=''>Choose Product</option>";
                                    select.empty();      
                                    for(var i=0;i<data.length; i++)	{
                                            options += "<option value='"+data[i].prod_id+"'>"+ data[i].prod_name+"</option>";            
                                    }
                                    select.append(options);
                                },
                                error:function(contact){
                                    network_err_alert();
                                }
                            });
                        }else{
                            $('#customer').closest("div").find("span.error-alert").text("Can add only one lead");
                            return;
                        }
                    });
		 }
		},
                error:function(data){
                    network_err_alert();
                }
            });
            
        }
function get_opportunity(){
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('sales_supportController/get_opportunitylist'); ?>",
        dataType : 'json',
        cache : false,
        success : function(data){
            loaderHide();
            if(error_handler(data)){
                            return;
                     }
            var select = $("#opportunity_ids"), options = "<option value=''>Choose Opportunities</option>";
            select.empty();      
            for(var i=0;i<data.length; i++)	{
                    options += "<option value='"+data[i].opportunity_id+"'>"+ data[i].opportunity_name+"( "+data[i].lead_cust_name+" )</option>";            
            }
            select.append(options);
        },
        error:function(data){
            network_err_alert();
        }
    });
}
function get_contacts(id){
   var oppobj={};
    oppobj.oppo_id=id;
    $.ajax({ 	
        type : "POST",
        url : "<?php echo site_url('sales_supportController/get_contactsforOpportunity'); ?>",
        data: JSON.stringify(oppobj),
        dataType : 'json',
        cache : false,
        success : function(contact){
            loaderHide();
            if(error_handler(contact)){
                return;
            }
            var select = $("#client_contact"),options="";
            select.empty();      
            for(var i=0; i<contact.length; i++){
                    options += "<div><label><input type='checkbox' value='"+contact[i].contact_id+"'/> "+ contact[i].contact_name +"</label></div>";
            }
            select.append(options);
        },
        error:function(contact){
            network_err_alert();
        }
    });
    var oppobj={};
    oppobj.oppo_id=id;
    $.ajax({ 	
        type : "POST",
        url : "<?php echo site_url('sales_supportController/get_OpportunityProducts'); ?>",
        data: JSON.stringify(oppobj),
        dataType : 'json',
        cache : false,
        success : function(data){
            loaderHide();
            if(error_handler(data)){
                return;
            }
            var select = $("#product_id"), options = "<option value=''>Choose Product</option>";
            select.empty();      
            for(var i=0;i<data.length; i++)	{
                    options += "<option value='"+data[i].prod_id+"'>"+ data[i].prod_name+"</option>";            
            }
            select.append(options);
        },
        error:function(data){
            network_err_alert();
        }
    });
}
function getuserlist(){
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('sales_supportController/get_emails'); ?>",
        dataType : 'json',					
        cache : false,
        success : function(data){
            if(error_handler(data)){
                return;
            }
            var jsonData = data;
            var dataSource = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('user_name', 'email','department_name','designation'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                local: jsonData
            });
            dataSource.initialize();
            $('#email_members').typeahead({
                minLength: 0,
                highlight: true,
                hint: false
            },{ 
                name: 'email',
                display: function(item) {
                    return item.user_name + ' ( ' + item.department_name+ ' ) ( ' + item.designation +' )'
                },
                source: dataSource.ttAdapter(),
                suggestion: function(data) {
                    return '<b>' + data.user_name + '–' + data.user_id + '</b>' 
                }
            });
            $('#email_members').on('typeahead:selected', function (e, datum) {
                var match=1;
                $("#email_list li").each(function(){
                    if($.trim($(this).attr('id'))== datum.user_id){
                            match=0;
                    }
                });
                if(match==0){
                    $('#email_members').val("");
                    return;
                }
                if ($("#email_list li").length <= 12) {
                    $("#email_list").append("<li id="+ datum.user_id+">"+ datum.user_name+" <a style='color:red;' class='glyphicon glyphicon-remove-sign' onclick='del(\""+datum.user_id+"\")'></a></li>");
                    $('#email_members').closest("div").find("span.error-alert").text("");
                    $('#email_members').val("");	
                }else{
                    alert("Can't add more than 12 Users");
                    $('#email_members').closest("div").find("span.error-alert").text("Can't add more than 12 Users");
                    return;
                }

            });
        }
    });
}
function del(id){	
    $("#"+id).remove();
}
function cancel1(){
   // $('#c_tat').data("DateTimePicker").clear();
    $('.modal').modal('hide');
    $('.form-control').val("");
    $("#first_section input , #first_section select ").removeAttr("disabled");
    $('#second_section').hide();
    $("#customer").typeahead("destroy");  
    $('#client_contact ,#activity_owner').html("");
    $('#client_contact').removeClass("disable")
} 
var raise_data={};
var details={};
function save_request(){
    $('#committed_tat').datetimepicker({
        ignoreReadonly:true,
        allowInputToggle:true,
        format:'lll',
        minDate: moment()
    });
    var request_name = $.trim($("#request_name").val());
    if(request_name == ""){
        $("#request_name").next(".error-alert").text("Request Name is required.");
        $("#request_name").focus();
        return;
    }else if(!validate_name(request_name)){
        $("#request_name").next(".error-alert").text("No special characters allowed (except &, _,-,.)");
        $("#request_name").focus();
        return;
    }else if(!firstLetterChk(request_name)){
        $("#request_name").next(".error-alert").text("First letter should not be Numeric or Special character.");
        $("#request_name").focus();
        return;		
    }else{
        $("#request_name").next(".error-alert").text("");
    }
    if($("#request_for").val() == ""){
        $("#request_for").next(".error-alert").text("Request For is required.");
        $("#request_for").focus();
        return;
    }else{
         $("#request_for").next(".error-alert").text("");
    }
    if($("#request_for").val() == "opportunity"){
        if($("#opportunity_ids").val() == ""){
            $("#opportunity_ids").next(".error-alert").text("Opportunity is required.");
            $("#opportunity_ids").focus();
            return;
        }else{
             $("#opportunity_ids").next(".error-alert").text("");
        }
    }
    if($("#request_for").val() == "customer"){
        if(cust_id == ""){
            $("#leadField").find(".error-alert").text("Customer is required.");
            $("#customer").focus();
            return;
        }else{
             $("#leadField").find(".error-alert").text("");
        }
    }
    if($("#product_id").val() == ""){
        $("#product_id").next(".error-alert").text("Product is required.");
        $("#product_id").focus();
        return;
    }else{
         $("#product_id").next(".error-alert").text("");
    }
    var clientContactArray=[];
    $("#client_contact div input[type=checkbox]").each(function(){
        if($(this).prop( "checked" ) == true){
            clientContactArray.push($(this).val());
        }
    });
    if(clientContactArray.length <= 0 ){
        $("#client_contact").next(".error-alert").text("Select atleast one Client Contact.");
        return;
    }else{
        $("#client_contact").next(".error-alert").text("");
    }
    /*---------------------process_type ----------------------------*/
    if($("#process_type").val() == ""){
        $("#process_type").next(".error-alert").text("Support Process is required.");
        $("#process_type").focus();
        return;
    }else{
         $("#process_type").next(".error-alert").text("");
    }
     if($("#support_criticality").val() == ""){
        $("#support_criticality").next(".error-alert").text("Support Criticality is required.");
        $("#support_criticality").focus();
        return;
    }else{
         $("#support_criticality").next(".error-alert").text("");
    }
    
    /*---------------------Request Remarks----------------------------*/
    if($.trim($("#remarks").val()) == ""){
        $("#remarks").next(".error-alert").text("Request Remarks is required.");
         $("#remarks").focus()
        return;
    }else{
         $("#remarks").next(".error-alert").text("");
    }
    var Obj={};
    Obj.request_name = request_name;
    Obj.request_for = $("#request_for").val();
     if($("#request_for").val() == "opportunity"){
        Obj.opportunity_id = $("#opportunity_ids").val();
    }else{
        Obj.opportunity_id="";
    }
    /*---------------------customer----------------------------*/
    if($("#request_for").val() == "customer"){
        Obj.customer_id = cust_id;
    }else{
        Obj.customer_id="";
    }
    Obj.client_contact = clientContactArray;    
    Obj.process_type = $("#process_type").val();
    Obj.product_id = $("#product_id").val();
    Obj.name=$("#request_name").val();
    Obj.for=$("#request_for").val()
    Obj.crictical=$("#support_criticality").val();
    var startDateTime = moment($.trim($("#committed_tat input[type=text]").val()), 'lll').format('YYYY-MM-DD HH:mm:ss');
    Obj.tat=startDateTime;
    Obj.remarks=$("#remarks").val();
    console.log(Obj);
    loaderShow();
    $.ajax({
        type : "POST",
        url : "<?php echo site_url('sales_supportController/get_process_cycle'); ?>",
        dataType : 'json',
        data:JSON.stringify(Obj),
        cache : false,
        success : function(data){
            if(error_handler(data)){
                return;
            }
            if(data==0){
                loaderHide();
                alert("Provided parameters does not match to any cycle, Please contact Admin.");
                return ;
            }else{      
                loaderHide();
                alert(data.message);
                if (data.qualifier == true) {
                    setup_questionnaire(data.qualifier_data);
                    raise_data=data.request_data;
                    details=data.details;
                }
            }
        },
        error:function(data){
            network_err_alert();
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
$("#Questionnaire").modal("hide")
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
        mainObj.stage_id=details.stage_id;
        mainObj.rep_id="<?php echo $this->session->userdata('uid');?>";
        mainObj.opp_cust_id= details.opportunity_id;
        mainObj.cycle=details.cycle_id;
        mainObj.industry=details.industry;
        mainObj.location=details.location;
        mainObj.request_id=raise_data.request_id;
        mainObj.request_name=raise_data.request_name;
        mainObj.request_for=raise_data.request_for;
        mainObj.contact_id=raise_data.contacts;
        mainObj.process=raise_data.process;
        mainObj.critical=raise_data.critical;
        mainObj.product=raise_data.product;
        mainObj.tat=raise_data.tat;
        mainObj.email=raise_data.email;
        mainObj.remarks=raise_data.remarks;
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
        console.log(mainObj);
        loaderShow();
        $.ajax({
            type:"post",
            cache:false,
            url:"<?php echo site_url('sales_supportController/verify_qualifier');?>",
            dataType : 'json',
            data:JSON.stringify(mainObj),
            success: function (data) {
                if(error_handler(data)) {
                  return;
                }
                if (data == 0){
                    alert("Successfully answering the qualifier is mandatory to create this opportunity.");
                    loaderHide();
                }else{
                    loaderHide();
                    $('#alert').modal('show');
                    $('#alert .row span').text("").text('Request has been raised with the Request Id -'+data);
                    $('#Questionnaire').modal('hide');
                    $('#leadinfoAdd').modal('hide');
                    loaddata();
                }
            }
        });
    }
}
</script>
            <div id="leadinfoAdd" class="modal fade" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <span class="close" onclick="cancel1()">x</span>
                            <h4 class="modal-title"><b>Raise Request</b>
                        </div>
                        <div class="modal-body">
                            <div id="first_section">
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="request_name">Request Name*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="request_name" name="request_name" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <input type="hidden" id="stage" name='stage'/>
                                 <input type="hidden" id="cycle" name='cycle'/>
                                  <input type="hidden" id="opp_cust_id" name='opp_cust_id'/>
                                 
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="request_for">Request For*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" id="request_for" onchange="get_details()">
                                            <option value="">Select</option>
                                            <option value="opportunity">Opportunity</option>
                                            <option value="customer">Customer</option>
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row cust_row none">
                                    <div class="col-md-4">
                                        <label for="customer"><b>Customer*</b></label>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="leadField">
                                            <input type="text" id="customer" class="form-control" placeholder="Enter Customer:">
                                            <span class="error-alert"></span>	
                                        </div>
                                    </div>
                                </div>
                                <div class="row opp_row none">
                                    <div id="oppGroup">
                                        <div class="col-md-4">
                                                <label for="opportunity_ids"><b>Opportunity*</b></label>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="oppField">
                                                <select class="form-control" id="opportunity_ids" onchange="get_contacts(this.value)">
                                                </select>
                                                <span class="error-alert"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="product_id">Product*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" id="product_id">                                        
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="client_contact"> Client Contact*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-control multiselect" id="client_contact">                                        
                                        </div>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="process_type"> Support Process*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" id="process_type" name="process_type">
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                            </div>
                            <div id="second_section">
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="support_criticality">Support Criticality*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" id="support_criticality" name="support_criticality" autofocus>
                                            <option value=''>Select</option>
                                            <option value='high'>High</option>
                                            <option value='medium'>Medium</option>
                                            <option value='low'>Low</option>
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="committed_tat">Committed TAT*</label> 
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class='input-group date' id="committed_tat">
                                                <input type='text' class="form-control" placeholder="DD-MM-YYYY" readonly />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                            <span class="error-alert"></span>
                                        </div>	
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label for="remarks" title="Suggested" >Request Remarks*</label>  
                                    </div>
                                    <div class="col-md-6">
                                        <textarea rows="4" cols="50" placeholder="Enter additional remarks for the Support Request" class="form-control" name="remarks" id="remarks"></textarea>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row" id="email_grant">
                                    <div class="col-md-2 pull-left">
                                            <input type="checkbox" name="check" id="email_check" />
                                            <label for="email_check"> Email Alert </label>
                                    </div>
                                    <div class="col-md-8 email_section" style="display: none;">
                                            <input id='email_members' class="form-control" placeholder="Send Emails to:" />
                                    </div>
                                </div>
                                <div class="row">
                                    <ul style="padding:0px"class="email_id" id="email_list"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                           <input type="button" class="btn"  value="Raise Request" id="save_request_btn"onclick="save_request();" >
                           <button  type="button" class="btn btn-default" id="btn1_cancel" onclick="cancel1()" >Cancel</button>
                        </div>
                    </div>                               
                </div>
            </div>
              
                <div id="Questionnaire" class="modal fade" data-backdrop="static" data-keyboard="false">
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
                                        <input type="hidden" id="user_id">
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
                                    <button type="button" class="btn btn-primary" id="submit_q_btn" onclick="SubmitQpaper()" >Submit</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="alert" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">                                               
                            <div class="modal-body">
                             <div class="row">
                               <span> </span>
                             </div>
                            </div>                            
                        </div>
                    </div>
                </div>
     