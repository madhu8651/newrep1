<script>
    function editRow(obj){
   var contact=obj.request_contact.split(":");
    $("#edit_modal").modal('show');
    $("#edit_modal .modal-title").text("Edit "+obj.request_name);
    $("#req_name").val(obj.request_name);
    $("#critc").val(obj.cricticality);
    $("#req_id").val(obj.request_id);
    $("#edit_cycle").val(obj.cycle_id);
    $("#edit_stage").val(obj.request_stage);
    $("#edit_process").val(obj.process_type);
    $("#edit_oppo").val(obj.opp_cust_id);
    var req_for=obj.request_for;        
    $('#comm_tat').datetimepicker({
        ignoreReadonly:true,
        allowInputToggle:true,
        format:'ll',
        minDate: moment(obj.tat , "DD/MM/YYYY")
    });
    $('#comm_tat input').val(moment(obj.tat , "DD/MM/YYYY").format("ll"));
    var addobj={};
    addobj.opp_id=obj.opp_cust_id;
    addobj.req_for=req_for;
                $.ajax({ 
                type : "POST",
                url : "<?php echo site_url('sales_supportController/get_contacts'); ?>",
                data : JSON.stringify(addobj),
                dataType : 'json',
                cache : false,
                success : function(data){
                  if(error_handler(data)){
                    return;
                    }
                    var select = $("#con_name"), options = "";
                    select.empty();      
                    for(var i=0;i<data.length; i++){
                        for(var j=0;j<contact.length; j++){
                            if(data[i].contact_id== contact[j]){
                                options += "<div><label><input type='checkbox' checked value='"+data[i].contact_id+"'/> "+ data[i].contact_name +"</label></div>"; 
                            }else{
                                options += "<div><label><input type='checkbox'  value='"+data[i].contact_id+"'/> "+ data[i].contact_name +"</label></div>"; 

                            }
                        }
                    }
                    select.append(options);

                 }
            });
}
function edit_save(){
    if($("#req_name").val() == ""){
        $("#req_name").next(".error-alert").text("Request Name is required.");
        $("#req_name").focus();
        return;
    }else{
         $("#req_name").next(".error-alert").text("");
    }
    var con_num_array=[];
    $("#con_name div input[type=checkbox]").each(function(){
        if($(this).prop( "checked" ) == true){
            con_num_array.push($(this).val());
        }
    });
     if(con_num_array.length <= 0 ){
        $("#con_name").next(".error-alert").text("Select atleast one contact.");
        return;
    }else{
        $("#con_name").next(".error-alert").text("");
    }
    if($("#critc").val() == ""){
        $("#critc").next(".error-alert").text("Request Criticality is required.");
        $("#critc").focus();
        return;
    }else{
         $("#critc").next(".error-alert").text("");
    }
     if($.trim($("#comm_tat input[type=text]").val()) == ""){
        $("#comm_tat").next(".error-alert").text("Committed TAT is required.");
        $("#comm_tat").focus();
        return;
    }else{
         $("#comm_tat").next(".error-alert").text("");
    }
    if($("#remarks_id").val() == ""){
        $("#remarks_id").next(".error-alert").text("Remarks is required.");
        $("#remarks_id").focus();
        return;
    }else{
         $("#remarks_id").next(".error-alert").text("");
    }
    var editobj={};
    editobj.req_name=$("#req_name").val();
    editobj.con_name=con_num_array;
    editobj.critc=$("#critc").val();
    editobj.remarks=$("#remarks_id").val();
    editobj.req_id=$("#req_id").val();
    editobj.edit_cycle=$("#edit_cycle").val();
    editobj.edit_oppo=$("#edit_oppo").val();
    editobj.edit_stage=$("#edit_stage").val();
    editobj.edit_process=$("#edit_process").val();
    var startDateTime = moment($.trim($("#comm_tat input[type=text]").val()), 'lll').format('YYYY-MM-DD HH:mm:ss');
    editobj.comm_tat=startDateTime;
    loaderShow();
    $.ajax({ 
        type : "POST",
        url : "<?php echo site_url('sales_supportController/update_request'); ?>",
        data: JSON.stringify(editobj),
        dataType : 'json',
        cache : false,
        success : function(data){
            if(error_handler(data)){
                return;
            }
            loaderHide();
            if(data==1){
                alert("Data has been updated successfuly");
                 $("#edit_modal").modal('hide');
                 loaddata();
             }
          }
    });
    
}
   </script>
   <div id="edit_modal" class="modal fade" data-backdrop="static">
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <div class="modal-header">
                                <span class="close" onclick="cancel1()">x</span>
                                <h4 class="modal-title"></h4>
                            </div>
                            <div class="modal-body">								
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="req_name">Request Name*</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="req_name"/>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="con_name">Contact Name*</label>
                                    </div>
                                     <div class="col-md-8">
                                        <div class="form-control multiselect" id="con_name">                                        
                                        </div>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <input type="hidden" id="req_id">
                                 <input type="hidden" id="edit_cycle">
                                  <input type="hidden" id="edit_stage">
                                   <input type="hidden" id="edit_oppo">
                                    <input type="hidden" id="edit_process">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="critc">Criticality*</label>
                                    </div>
                                    <div class="col-md-8">
                                       <select class="form-control" id="critc" name="critc" autofocus>
                                            <option value=''>Select</option>
                                            <option value='high'>High</option>
                                            <option value='medium'>Medium</option>
                                            <option value='low'>Low</option>
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="comm_tat">Committed TAT*</label>
                                    </div>
                                    <div class="col-md-8">
                                        
                                        <div class='input-group date' id="comm_tat">
                                            <input type='text' class="form-control" placeholder="DD-MM-YYYY" readonly />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <textarea type="text" class="form-control" placeholder="Enter remarks ( Mandatory )" id="remarks_id"></textarea>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                            </div>                  
                            <div class="modal-footer">
                                <input  type="button" class="btn btn-default" value="Save" onclick="edit_save()"/>
                                 <input  type="button" class="btn btn-default" value="Cancel"  onclick="cancel1()"/>
                            </div>
                        </div>
                    </div>
                </div>
    
