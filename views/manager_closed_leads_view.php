<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require 'scriptfiles.php' ?>
        <script src="/js/prefixfree.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPquJYJq7KSiQPchdgioEVs-xOY4ERUdE&libraries=places"
    async defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
        <style>
			.modal-backdrop{
	z-index:-1;
}
.error-alert{
	color:red;
}
.header1{
	background:rgb(30, 40, 44);
	padding:2px;
}
#files{  display:block;
                }
.pageHeader1{
	text-align:center;		
	color:white;
	height:41px;
	font-size:22px;
}
.pageHeader1 h2{
	margin-bottom: 0;
	margin-top: 0;
}

	.created, .assigned, .remarks	{
		text-align: left;
	}

.column{
	padding:0;
}
.addExcel{
	bottom: 0;
}
.addPlus{
   bottom: 0;
}
.table.table{
	margin-top:0;
}
.table tbody tr td{
	text-align:center;
}
.table thead tr th{
	text-align:center;
}	
.content-wrapper.body-content section.row{
	height:46px;
}
.aa{
	float: left;
	position: relative;
	height: 41px;
	line-height: 35px;
}
.info-icon div{
	margin-left: 14px;
}
.sidebar{
	margin-top: 0px;
}
.main-sidebar{
	z-index:0;
}
.modal-dialog{
	margin-top: 110px;
}
.modal-header{
	background: #B5000A;
	color: white;
}
.close{
	color: white;
	opacity: 1;
}
@media only screen and (min-device-width: 320px) and (max-device-width: 480px){
	.aa h2{			
		font-size:16px;
		padding-left: -10px;
		margin-top: 9px;
	}	
	.addExcel{
		margin-right: -14px;
	}
	.aa{
		margin-top: 62px;
		font-size:18px;
	}
	.addBtns{
		margin-right: -19px;
	}
	.addPlus{
		margin-right: 30px;
	}
	.aa{
		margin-left: 0px;
		font-size:16px;
		padding:0;
	}
	.sidebar{
			margin-top: 56px;
	}
	.main-sidebar{
		z-index:0;
	}
	.modal-dialog{
		margin-top: 175px;
	}
	.main-header .sidebar-toggle{
		padding: 13px 15px;
	}
	.accessLabel{
		margin: 5px;
	}
	.navbar-custom-menu .navbar-nav>li>a{
		padding-top: 5px;
	}
}
@media only screen and (min-device-width: 768px) and (max-device-width: 1024px){
	  .aa h2{			
		font-size:22px;	
		margin-top: 6px;
	}	
	.modal-dialog{
		margin-top: 175px;
	}
	.main-sidebar{
		z-index:0;
	}
}
@media only screen and (min-device-width: 340px) and (max-device-width: 632px){
  .aa h2{			
	font-size:16px;
	padding-left: -10px;
	margin-top: 9px;
	}
	.addPlus{
		margin-right: 30px;
	}
	.addExcel{
		margin-right: -14px;
	}
	.aa{
			margin-top: 62px;
			font-size:18px;
		}
	.addBtns{
		margin-right: -19px;
	}
	.aa{
		margin-left: 0px;
		font-size:16px;
		padding:0;
	}
	.sidebar{
			margin-top: 56px;
	}	
	.main-sidebar{
		z-index:0;
	}
	.modal-dialog{
		margin-top: 175px;
	}
}
.filter_select{
margin-top: 16px;	
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
.none{
	display: none;
}
.modal {
  overflow-y:auto;
}
.apport_label label{
	font-weight:bold!important;	
}
/* tree-veiw css*/
ul {
        list-style-type: none;
    }
ul li{
    text-align: left;
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

#tree_leadsource,#tree_lead_source{
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
	.multiselect1{
		height: 60px;
		overflow: auto;
		border: 1px solid #ccc;
		border-radius: 5px;
	}
	.multiselect1 ul{
			padding: 0px;
	}
	.multiselect1 ul li.sel{
			background: #ccc;
	}
	.multiselect1 ul li{
			padding: 0 10px;
	}

	.multiselect2{
	height: 250px;
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
	.leadsrcname {
			margin-left: 72px;
	}
</style>
<script>
	function error_handler(data){
			if(data.hasOwnProperty("errorCode")){
				alert(data.errorCode+"  "+data.errorMsg);
				return true;
			}
			return false;
	}
  $(document).ready(function(){
     $('#files').change(handleFile);
     $("#save_file").click(function(){
        if($('#files').val()!=''){
//            var file = $('#files').val();
//            var file = file.split(".");
//            var arr = [ "XLS", "xls","xlsx","XLSX","csv"];
        }else{
            $("#files").closest("div").find("span").text("Attachment file is required");
            return;
        }
        var exce=remove_duplicates(result, 'Lead Name*^');
        for(i=0;i<(exce.length);i++){
             exce[i].Lead_id="";
             exce[i].Contact_id="";
        }
       var addobj={};
       addobj.lead=exce;
       console.log(addobj);
        $.ajax({
                type : "POST",
                url : "<?php echo site_url('manager_leadController/get_data'); ?>",
                dataType : 'json',
                data :JSON.stringify(addobj),
                cache : false,
                success : function(data){
                	if(error_handler(data)){
              			return;
       				}
                     $("#modal_upload").modal("hide");
                    var rejected =total-data;
                    $("#counterList").modal("show");
                    $("#counterList .modal-body").text("Out of "+total+" Customers, "+rejected+ " are rejected");
                }
            });
        
        });
    });
    var result=[];
    var total;
    function handleFile(e) {
        var files = e.target.files;
        var excel =files[0];
        var reader = new FileReader();
        reader.readAsBinaryString(excel);
        reader.onload = function(e) {
        var data = e.target.result;
        var workbook = XLSX.read(data, {type: 'binary'});
        workbook.SheetNames.forEach(function(sheetName) {
        var roa = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName]);
            if(roa.length > 0 ){
                    result = roa;
            }
        });
        total=result.length;
};
}
 function remove_duplicates(original_array, objKey) {
  var final_array = [];
  var json_data= [];
  var value;

  for(var i = 0; i < original_array.length; i++) {
    value = original_array[i][objKey];

    if(json_data.indexOf(value) === -1) {
      final_array.push(original_array[i]);
      json_data.push(value);
    }
  }

  return final_array;

}
</script>
<script>
	function edit_tree(data, container){
    $("#"+container).html("");
	var oflocArray = convert(data);
	var $ul = $('<ul class="mytree"></ul>');
	getList(oflocArray, $ul);
	$ul.appendTo("#"+container);
	var display_list=[];

	$("#"+container+" input[type=radio]").each(function(){

		if($(this).closest('li').children('ul').length > 0){

			$(this).closest('label').closest('div').find('.glyphicon').addClass('glyphicon-minus-sign');
		}else{
			$(this).closest("label").css({'text-decoration':'underline','font-style':'italic'}).addClass('lastnode');
			/*$(this).closest("label").find('input[type=radio]').remove();*/
		}

		$(this).change(function(){
			display_list=[];
			if($(this).prop('checked')==true){
				if($(this).closest('li').children('ul').length > 0){
					$(this).closest('li').children('ul').find(".lastnode").each(function(){
						display_list.push($.trim($(this).text()));
					});
				}
			}
			var html= '';
			for(i=0; i< display_list.length; i++){
				html +="<li>"+display_list[i]+"</li>"
			}

			$(this).closest(".row").find('ol').html(html);
		})
	});

	$("#"+container+" input[type=radio]").each(function(){
		if($(this).prop('checked')==true){
			//	displayList($(this).val());
			}
	})
	/* hide/show child node on click of plus/minus */
	$("#"+container+"  label.glyphicon").each(function(){
		$(this).click(function(){
			if($(this).closest('li').children('ul').length > 0 || $(this).closest('li').children('ul').css('display')=="block" ){

				$(this).closest('li').children('ul').hide(1000);
				$(this).closest('div').find('.glyphicon-minus-sign').removeClass('glyphicon-minus-sign').addClass('glyphicon-plus-sign');
			}
			if($(this).closest('li').children('ul').css('display')=="none" ){

				$(this).closest('li').children('ul').show(1000);
				$(this).closest('div').find('.glyphicon-plus-sign').removeClass('glyphicon-plus-sign').addClass('glyphicon-minus-sign');
			}
		})
	})
	$("#"+container+"  input[type=radio]").each(function(){
		$(this).click(function(){
			if($(this).prop('checked')==true){
				$("#"+container).siblings('.leadsrcname').text($(this).closest('label').text());
			}
		})
		if($(this).prop('checked')==true){
			$("#"+container).siblings('.leadsrcname').text($(this).closest('label').text());
		}

	})
}	


 function convert(data){
			var map = {};
			for(var i = 0; i < data.length; i++){
				 var obj = data[i];
				obj.children= [];
				map[obj.id] = obj;
				var parent = obj.parent || '-';
				if(!map[parent]){
					map[parent] = {
						children: []
					};
				}
				map[parent].children.push(obj);
			}
			return map['-'].children;
}
		 /* ------------------------ constructing tree structure ---------------- */
function getList(item, $list) {
	if($.isArray(item)){
		$.each(item, function (key, value) {
			getList(value, $list);
		});

	}

	if (item) {
		var $li = $('<li />');
		if (item.name) {

			if(item.checked == true){
				$li.append($("<div><div class='dash-left'><label class='glyphicon' id='"+item.id+"'></label>---</div><label> <input name='"+item.nameAttr+"' type='radio' value='"+item.id+"' id='id"+item.id+"'  checked>  " + item.name + "</label></div>"));
			}else{
				$li.append($("<div><div class='dash-left'><label class='glyphicon' id='"+item.id+"'></label>---</div><label> <input  name='"+item.nameAttr+"' type='radio' value='"+item.id+"' id='id"+item.id+"'>  " + item.name + "</label></div>"));
			}
		}
		if (item.children && item.children.length) {
			var $sublist = $("<ul class=child-count-"+item.children.length+"></ul>");
			getList(item.children, $sublist)
			$li.append($sublist);
		}
		$list.append($li)
	}
}
</script>

<script>
function validate_name(name) {
	var nameReg = new RegExp(/^[a-zA-Z]+$/);
	var valid = nameReg.test(name);
	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function validate_website(website) {
	var nameReg = new RegExp( /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/);
	var valid = nameReg.test(website);
	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}function validate_email(email) {
	var nameReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	var valid = nameReg.test(email);
	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function validate_phone(phone) {
	var nameReg = new RegExp(/^(\+91-|\+91|0)?\d{10}$/);
	var valid = nameReg.test(phone);
	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function validate_city(city) {
	var nameReg = new RegExp(/^[a-zA-Z]+$/);
	var valid = nameReg.test(city);
	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function validate_zipcode(zipcode) {
	var nameReg = new RegExp(/^[0-9]{6}$/);
	var valid = nameReg.test(zipcode);
	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function validate_contact(contact) {
	var nameReg = new RegExp(/^[a-zA-Z]+$/);
	var valid = nameReg.test(contact);
	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function validate_designation(designation) {
	var nameReg = new RegExp(/^[a-zA-Z]+$/);
	var valid = nameReg.test(designation);
	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function add_contact(){

	$('#leadinfoedit .modal-body .edit_leadContact').append("<div class='contact_type1'><div class='row' ><div class='col-md-12 lead_address'><center><b>Lead Contact Person Information</b></center></div></div><div class='row'><div class='col-md-2'><label>Contact Person*</label></div><div class='col-md-4'><input type='text' class='form-control edit_firstcontact' name='edit_firstcontact' ><span class='error-alert'></span></div><div class='col-md-2'><label>Designation</label></div><div class='col-md-4'><input type='text' class='form-control edit_disgnation' name='edit_disgnation' ><span class='error-alert'></span></div></div><div class='row'><div class='col-md-2'> <label>Mobile Number 1</label></div><div class='col-md-4'><input type='text' class='form-control edit_primmobile' ><span class='error-alert'></span></div><div class='col-md-2'><label>Mobile Number 2*</label></div><div class='col-md-4'><input type='text' class='form-control edit_primmobile2' name='edit_primmobile2' ><span class='error-alert'></span></div></div><div class='row'><div class='col-md-2'><label>Email 1</label></div><div class='col-md-4'><input type='text' class='form-control edit_primemail' ><span class='error-alert'></span></div><div class='col-md-2'><label>Email 2</label></div><div class='col-md-4'><input type='text' class='form-control edit_primemai2'><span class='error-alert'></span></div></div><div class='row'><div class='col-md-2'><label for='edit_displaypic'>Photo</label></div><div class='col-md-4'><label for='adminImageUploadE' class='custom-file-upload'><i class='fa fa-cloud-upload'></i> Image Upload</label><input type='file' class='form-controz' id='displaypic1' ><span class='error-alert'></span></div><div class='col-md-2'><label for='edit_contacttype'>Contact Type</label></div><div class='col-md-4'><select class='form-control' id='edit_contacttype'></select><span class='error-alert'></span></div></div></div>");
}
function add_contact1(){

	$('#leadinfoAdd .modal-body .add_leadContact').append("<div class='contact_type02'><div class='row' ><div class='col-md-12 lead_address'><center><b>Lead Contact Person Information</b></center></div></div><div class='row'><div class='col-md-2'><label>Contact Person*</label></div><div class='col-md-4'><input type='text' class='form-control edit_firstcontact' name='edit_firstcontact' ><span class='error-alert'></span></div><div class='col-md-2'><label>Designation</label></div><div class='col-md-4'><input type='text' class='form-control edit_disgnation' name='edit_disgnation' ><span class='error-alert'></span></div></div><div class='row'><div class='col-md-2'> <label>Mobile Number 1</label></div><div class='col-md-4'><input type='text' class='form-control edit_primmobile' ><span class='error-alert'></span></div><div class='col-md-2'><label>Mobile Number 2*</label></div><div class='col-md-4'><input type='text' class='form-control edit_primmobile2' name='edit_primmobile2' ><span class='error-alert'></span></div></div><div class='row'><div class='col-md-2'><label>Email 1</label></div><div class='col-md-4'><input type='text' class='form-control edit_primemail' ><span class='error-alert'></span></div><div class='col-md-2'><label>Email 2</label></div><div class='col-md-4'><input type='text' class='form-control edit_primemai2'><span class='error-alert'></span></div></div><div class='row'><div class='col-md-2'><label for='edit_displaypic'>Photo</label></div><div class='col-md-4'><label for='adminImageUploadE' class='custom-file-upload'><i class='fa fa-cloud-upload'></i> Image Upload</label><input type='file' class='form-controz' id='displaypic1' ><span class='error-alert'></span></div><div class='col-md-2'><label for='edit_contacttype'>Contact Type</label></div><div class='col-md-4'><select class='form-control' id='edit_contacttype'></select><span class='error-alert'></span></div></div></div>");
};

$(document).ready(function(){	
	pageload();	
	$("#startDateTimePicker").datetimepicker({
		ignoreReadonly:true,
		allowInputToggle:true,
		format:'DD-MM-YYYY',
		minDate: moment(),
	});

	$("#tempdate").datepicker({dateFormat: 'dd-mm-yy'});

	
	
	$("#Temporary").click(function(){
		if($("#Temporary").is(':checked')){
			$("#Temporary").prop("value",1);
			$("#Permanent").prop("value",0);
			$("#Temp_date,#remarks2").show();
			$("#remarks,#remarks1").hide();
		}else{
			$("#Temporary").prop("value",0);
			$("#Temp_date").hide();
			$("#remarks").hide();
			$("#remarks1").hide();
			$("#remarks2").hide();
		}
	});
	$("#Permanent").click(function(){
		if($("#Permanent").is(':checked')){
			$("#Permanent").prop("value",1);
			$("#Temporary").prop("value",0);			
			$("#remarks1").show();
			$("#remarks2").hide();
			$("#remarks").hide();
			$("#Temp_date").hide();
		}else{
			$("#Permanent").prop("value",0);
			$("#remarks").hide();
			$("#remarks1").hide();
			$("#remarks2").hide();
			$("#Temp_date").hide();
		}
	});
});

function capitalizeFirstLetter(string) {
	    return string.charAt(0).toUpperCase() + string.slice(1);
	}

var finalArray = {};
function assign_btn(){
	var check = [];

	var flagchk=0;

	/*if($("#tablebody tr input[type=checkbox]").prop("checked")==false){
		
	}*/
	finalArray['leads'] = [];
    $("#tablebody tr input:checkbox").each(function () {
       
        if($(this).prop("checked")==true){
			$("#modalstart1").modal("show");
			check.push($(this).attr('id'));
			finalArray['leads'].push($(this).attr('id'));
			flagchk=1;
		}		
    });
    if(flagchk==0)	
    {
    	alert("Please select the lead");
		return;	
    }	
	assign_date(check.join(":"));       	
}



var arr={};
var managers = {};
$('#test').val('on');
function assign_date(idval){
	if(idval!=''){
		$("#modalstart1").modal("show");
	}
	arr.lid=idval;

		var multipl2="";
	  	$.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_leadController/get_managerlist_reassign');?>",
            dataType:'json',
            data:JSON.stringify(finalArray),
            success: function(data) {
            	if(error_handler(data)){
                	return;
               	}
            	managers = data;
            	/* var select = $("#replist"), options = "<option value=''>select</option>";
               select.empty();    */  
	            for(var i=0;i<data.length; i++){
	            	if(data[i].sales_module=='0' && data[i].manager_module!='0'){
	            		multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'">'+data[i].user_name+' (Manager)<label></li>';
	            	}
	            	if(data[i].sales_module!='0' && data[i].manager_module=='0'){
	            		multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'">'+data[i].user_name+' (Sales)<label></li>';
	            	}
	            	if(data[i].sales_module!='0' && data[i].manager_module!='0'){
	            		multipl2 +='<li><label><input type="checkbox" class="mgrlist_manager" value="manager" id="'+data[i].user_id+'">'+data[i].user_name+' (Manager)<label></li>';
	            		multipl2 +='<li><label><input type="checkbox" class="mgrlist_sales" value="sales" id="'+data[i].user_id+'">'+data[i].user_name+' (Sales)<label></li>';
	               	}

	              //   if(data[i].sales_module!='0' && data[i].manager_module!='0'){   
	              // /* 	count=2;            	
	              //  		while(count!=0){*/
	              //  		multipl2 +='<li><label><input type="checkbox" onchange="magrlist();" value="'+data[i].user_id+'">  sales<label></li>';
	              //  		 		multipl2 +='<li><label><input type="checkbox" onchange="magrlist();" value="'+data[i].user_id+'">  manager<label></li>';
	              //  		/*}*/

	              //  	}	
	               /*     options += "<option value='"+data[i].rep_id+"'>"+ data[i].repname +"</option>";      */        
	                   /* arr.memail=data[i].memail;          
	                    arr.email=data[i].email;
	                    arr.leadname=data[i].leadname;
	                    arr.manager=data[i].manager;*/
	            }
	            $(".multiselect2 ul").empty();
	    		$(".multiselect2 ul").append(multipl2);
		               //select.append(options);
		    }
        });
	
/* 	REPLIST DROPDOWN
	var multipl1=""	;
  	$.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_leadController/get_replist1');?>",
            dataType:'json',
            data:JSON.stringify(arr),
            success: function(data) {            	
                for(var i=0;i<data.length; i++)
               {
               	multipl1 +='<li id="'+ data[i].rep_id+'"><label><input type="checkbox"  value="'+data[i].rep_id+'">  '+data[i].repname+'<label></li>';	
              
               }
               $(".multiselect1 ul").append(multipl1);
               
            }
    });*/
		
}
	

function assign_save() {
	$('#modalstart1').modal('hide');
	//$('.form-control').val("");	
	
	/*  REPLIST SELECTION
	$("#replist input[type=checkbox]").each(function(){
			if($(this).prop('checked')== true){
				rep_id.push($(this).val());				   
			}
	});*/
		
	//arr.rep_id=rep_id.join(":");
	var lead_ids = [];
	finalArray['users'] = [];

	$(".mgrlist_sales, .mgrlist_manager").each(function(){
		if($(this).prop('checked')== true){
			var localObj = {};
			localObj['to_user_id'] = $(this).attr('id');
			localObj['module'] = $(this).val();
			finalArray['users'].push(localObj);
		}
	});
    $("#tablebody tr input:checkbox").each(function () {   
	    if($(this).prop("checked")==true){
			$("#modalstart1").modal("show");
			lead_ids.push($(this).attr('id'));
		}
	});
	if (finalArray['users'].count == 0) {
		alert('Select a user to assign');
		return;
	} else if (finalArray['leads'].count == 0)	{
		alert('Select Lead(s) to assign');
	}
	console.log(finalArray);

    $.ajax({
            type: "POST",
        	url: "<?php echo site_url('manager_leadController/reassign'); ?>",
            data:JSON.stringify(finalArray),
            dataType:'json',
            success: function(data) {
          	console.log(data);
	          	if(data==1)
	          	{
	          		$('#modalstart1').modal('hide');
					$('.form-control').val("");         		
	          	}
	          	pageload();               
            }
    });

    if ($('input.checkbox_check').is(':checked')) {
				 $.ajax({
			            type: "POST",
			            url: "<?php echo site_url('manager_leadController/sendemails'); ?>",
			            data:JSON.stringify(arr),
			            dataType:'json',
			            success: function(data) {

			          	}	

			    });
	}
}

function assign_btn2() {
	var id=$("#logg").val();	
	assign_date(id);
}


/*$(document).ready(function(){
		$("#leadlog").click(function(){
		 $('#logdetails').show();
		 var view_leadid={};
		 view_leadid.id=$("#logg").val();
		 $.ajax({
				type: "POST",
				url: "<?php echo site_url('manager_leadController11/fetch_leadlog'); ?>",
				data : JSON.stringify(view_leadid),
				dataType:'json',
				success: function(data) {
					console.log(data);
				var row = "";
				for(i=0; i < data.length; i++ ){  
					$('#logtable').empty();
					var rowdata = JSON.stringify(data[i]);	
					if(data[i].rating == 1){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].meeting_start +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
					}else if(data[i].rating == 2){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].meeting_start +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
					}else if(data[i].rating == 3){
					row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' ></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
					}else if(data[i].rating == 4){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' ></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
					}else {
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].time_stamp +"</td><td>" + "-" + "</td><td>" + "<i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + "-" +"</td><td>" + "-" +"</td><td>" + "-" +"</td>";
					}
				}     
				
				$('#logtable').append(row);  
				}
			});
		 
		});	

*/
var reporting=[];   
function pageload() {
		$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('manager_leadController/fetch_assigned'); ?>",
	            dataType:'json',
	            success: function(data) {
	            	if(error_handler(data)){
                    	return;
                	}
	           // reporting=data['reportingPersons'];
	            console.log(reporting)
	            leads=data['data'];	
	            $('#tablebody').parent("table").dataTable().fnDestroy();
	            $('#tablebody').html("")
	            if(leads.length > 0){
   					 $('#assign1').removeClass('hidden');
   				}

				console.log(data);
	            var row = "";

	           /* for(i=0;i<reporting.length;i++){
	          		
	            }*/
	            var luser=''; 
	            for(i=0; i < leads.length; i++ ){						
		            var rowdata = JSON.stringify(leads[i]);
					var lstate='';
					if(leads[i].leadstate=='pending'){
						lstate="<b style=color:blue>Pending</b>";					
					}				
					else if(leads[i].leadstate=='accepted'){
						lstate="<b style=color:green>Accepted</b>";		

					}
					if(leads[i].user_state=='0'){
	            	 luser="<b style=color:red>"+leads[i].user_name+"</b>"
	            	}
	            	else{
	            		luser=leads[i].user_name;
	            	}			

	            row += "<tr><td>" + "<input type='checkbox' name='"+leads[i].lead_rep_owner+"' val = '"+leads[i].lead_manager_owner+"'id='"+leads[i].leadid+"' class='assign_class'/>" + "</td><td>" + (i+1) + "</td><td>" + leads[i].leadname +"</td><td>" + leads[i].employeename +"</td><td>" + leads[i].employeedesg+ "</td><td>"+ luser + "</td><td>" + leads[i].leadphone +"</td><td>" + leads[i].leademail +"</td><td>" + leads[i].city +"</td><td>" + leads[i].leadsource +"</td><td>"+ lstate +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";					
	            }	             
					$('#tablebody').parent("table").removeClass('hidden');    
					$('#tablebody').append(row);
					$('#tablebody').parent("table").DataTable();
					var checkid=[];
					/*$('#tablebody tr input[type=checkbox]').each(function(){
						checkid.push($(this).attr("val"));
						for (var i = 0; i<reporting.length; i++) {
						for(j=0;j<checkid.length;j++){
							if (reporting[i].user_id==checkid[j] ) {
								$(this).removeAttr("disabled");
							}
						}
					}
					});		*/			

	            }

    });
				$('#select_map').hide();
				$('#logdetails').hide();
				$('#oop_details').hide();
				$("#map2").hide();
				$("#okmap").click(function(){
				$("#select_map").show();
				$("#map1").hide();
				$("#map2").hide();
				rendergmap();
				});
				$("#edit_okmap").click(function(){
				$("#edit_selectmap").show();
				$("#edit_map2").hide();
				$("#edit_map1").hide();
				edit_rendergmap();
				});
				$("#leadlog").click(function(){
				$('#logdetails').show();
				});
				$("#opp_log").click(function(){
				$('#oop_details').show();
				});
}


function edit_rendergmap() {
	var mapOptions = {
		center: new google.maps.LatLng(12.93325692, 77.57465679),
		zoom: 12,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var infoWindow = new google.maps.InfoWindow();
	var latlngbounds = new google.maps.LatLngBounds();
	var map = new google.maps.Map(document.getElementById("edit_mapname"), mapOptions);
	
	var input = document.getElementById('edit_search');
	var searchBox = new google.maps.places.SearchBox(input);
	var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.setTypes(['geocode']);
		
	map.addListener('bounds_changed', function() {
	  searchBox.setBounds(map.getBounds());
	});

	var markers = [];
	
	searchBox.addListener('places_changed', function() {
	  var places = searchBox.getPlaces();

	  if (places.length == 0) {
		return;
	  }

	  markers.forEach(function(marker) {
		marker.setMap(null);
	  });
	  markers = [];

	  var bounds = new google.maps.LatLngBounds();
	  places.forEach(function(place) {
		if (!place.geometry) {
		  alert("Returned place contains no geometry");
		  return;
		}
		var icon = {
		  url: place.icon,
		  size: new google.maps.Size(71, 71),
		  origin: new google.maps.Point(0, 0),
		  anchor: new google.maps.Point(17, 34),
		  scaledSize: new google.maps.Size(25, 25)
		};

		markers.push(new google.maps.Marker({
		  map: map,
		  icon: icon,
		  title: place.name,
		  position: place.geometry.location
		}));

		if (place.geometry.viewport) {
		  bounds.union(place.geometry.viewport);
		} else {
		  bounds.extend(place.geometry.location);
		}
	  });
	  map.fitBounds(bounds);
	   var place = autocomplete.getPlace();
		if (!place.geometry) {
			return;
		}

		var address = '';
		if (place.address_components) {
			address = [
				(place.address_components[0] && place.address_components[0].short_name || ''),
				(place.address_components[1] && place.address_components[1].short_name || ''),
				(place.address_components[2] && place.address_components[2].short_name || '')
				].join(' ');
		}
	});
	
	google.maps.event.addListener(map, 'click', function(e){
		
		var latlngstr = "lat=" + e.latLng.lat() + "&lng=" + e.latLng.lng();
		document.getElementById("edit_long").value = e.latLng.lat();
		document.getElementById("edit_latt").value = e.latLng.lng();
	});
}
function add_lead(){

	$.ajax({
        type: "POST",
        url: "<?php echo site_url('manager_leadController/getIndustry')?>",
        dataType:'json',
        success: function(data) {
        	if(error_handler(data)){
                    	return;
            }
        var select = $("#add_industry"), options = "<option value=''>select</option>";
           select.empty();      
           for(var i=0;i<data.length; i++)
           {
                options += "<option value='"+data[i].industry_id+"'>"+ data[i].industry_name +"</option>"; 

           }
           select.append(options);

        }
    });

	$.ajax({
        type: "POST",
        url: "<?php echo site_url('manager_leadController/getLocation')?>",
        dataType:'json',
        success: function(data) {
        	if(error_handler(data)){
                    	return;
            }
        var select = $("#add_business_location"), options = "<option value=''>select</option>";
           select.empty();      
           for(var i=0;i<data.length; i++)
           {
                options += "<option value='"+data[i].business_location_id+"'>"+ data[i].business_location_name +"</option>"; 

           }
           select.append(options);

        }
    });

	 $.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_leadController/get_product'); ?>",
            dataType:'json',
            success: function(data){
            	if(error_handler(data)){
                    	return;
                }
                 $("#product").html("");
            var currencyhtml="";
            currencyhtml +='<div id="product_value" class="multiselect">';
            currencyhtml +='<ul>';

                    for(var j=0;j<data.length; j++){								
                            currencyhtml +='<li><label><input type="checkbox" value="'+data[j].product_id+'">  '+data[j].product_name+'<label></li>';
                    }
                    currencyhtml +='</ul>';
                    currencyhtml +='</div>';
                    $("#product").append(currencyhtml)
            }
        });

	 $.ajax({
	            type : "POST",
	            url : "<?php echo site_url('manager_leadController/lead_source'); ?>",
	            dataType : 'json',
	            cache : false,
	            success : function(data){
	            	if(error_handler(data)){
                    	return;
                	}
			          	edit_tree(data,"tree_leadsource" );
					
						var isInside = false;
							
						$("#tree_lead").click(function () {
							$("#tree_leadsource").show();
						});
						
						$("#tree_leadsource").hover(function () {
							isInside = true;
						}, function () {
							isInside = false;
						})

						$(document).mouseup(function () {
							if (!isInside)
				            $("#tree_leadsource").hide();
						});
		        }
        });

     $.ajax({
	            type: "POST",
	            url: "<?php echo site_url('manager_leadController/contacttype'); ?>",
	            dataType:'json',
	            success: function(data) {
	            	if(error_handler(data)){
                    	return;
                	}
		             var select = $("#contacttypes"), options = "<option value=''>select</option>";
		               select.empty();      
		                for(var i=0;i<data.length; i++)
		               {
		                    options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
		               }
		               select.append(options);

	            }
        });

       $.ajax({
	            type: "POST",
	            url: "<?php echo site_url('manager_leadController/get_country');?>",
	            dataType:'json',
	            success: function(data) {
	            	if(error_handler(data)){
                    	return;
                	}
	             var select = $("#country"), options = "<option value=''>select</option>";
	               select.empty();      
	                for(var i=0;i<data.length; i++)
	               {
	                    options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
	               }
	               select.append(options);

	            }
        });
        
         
  }

  function change1(){
	// alert("hi");
               var id= $('#country option:selected').val(); 
               console.log(id);
				$.ajax({
					    type: "POST",
					    url: "<?php echo site_url('manager_leadController/get_state'); ?>",
					    data : "id="+id,
					    dataType:'json',
					    success: function(data) {
					    	if(error_handler(data)){
                    			return;
                			}
					     var select = $("#state"), options = "<option value=''>select</option>";
					       select.empty();      

					       for(var i=0;i<data.length; i++)
					       {
					            options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
					       }
					       select.append(options);

					    }
				});
}




function rendergmap() {
	var mapOptions = {
		center: new google.maps.LatLng(12.93325692, 77.57465679),
		zoom: 12,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var infoWindow = new google.maps.InfoWindow();
	var latlngbounds = new google.maps.LatLngBounds();
	var map = new google.maps.Map(document.getElementById("mapname"), mapOptions);
	
	var input = document.getElementById('search');
	var searchBox = new google.maps.places.SearchBox(input);
	var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.setTypes(['geocode']);
		
	map.addListener('bounds_changed', function() {
	  searchBox.setBounds(map.getBounds());
	});

	var markers = [];
	
	searchBox.addListener('places_changed', function() {
	  var places = searchBox.getPlaces();

	  if (places.length == 0) {
		return;
	  }

	  markers.forEach(function(marker) {
		marker.setMap(null);
	  });
	  markers = [];

	  var bounds = new google.maps.LatLngBounds();
	  places.forEach(function(place) {
		if (!place.geometry) {
		  alert("Returned place contains no geometry");
		  return;
		}
		var icon = {
		  url: place.icon,
		  size: new google.maps.Size(71, 71),
		  origin: new google.maps.Point(0, 0),
		  anchor: new google.maps.Point(17, 34),
		  scaledSize: new google.maps.Size(25, 25)
		};

		markers.push(new google.maps.Marker({
		  map: map,
		  icon: icon,
		  title: place.name,
		  position: place.geometry.location
		}));

		if (place.geometry.viewport) {
		  bounds.union(place.geometry.viewport);
		} else {
		  bounds.extend(place.geometry.location);
		}
	  });
	  map.fitBounds(bounds);
	   var place = autocomplete.getPlace();
		if (!place.geometry) {
			return;
		}

		var address = '';
		if (place.address_components) {
			address = [
				(place.address_components[0] && place.address_components[0].short_name || ''),
				(place.address_components[1] && place.address_components[1].short_name || ''),
				(place.address_components[2] && place.address_components[2].short_name || '')
				].join(' ');
		}
	});
	
	google.maps.event.addListener(map, 'click', function(e){
		
		var latlngstr = "lat=" + e.latLng.lat() + "&lng=" + e.latLng.lng();
		document.getElementById("long").value = e.latLng.lat();
		document.getElementById("latt").value = e.latLng.lng();
        });
}
function cancelCust(){
	$('.modal').modal('hide');
	$('.modal .form-control[type=text],.modal textarea').val("");
	$('.modal select.form-control').val($('.modal select.form-control option:first').val());
	$(".contact_type1").remove(); 
}
 function add_cancel(){
	$('.modal').modal('hide');
	$('.modal .form-control[type=text],.modal textarea').val("");
	$('.modal select.form-control').val($('.modal select.form-control option:first').val());
	$(".contact_type02").remove();
	$('#select_map').hide();
	$('#map1').show();

 }
 function cancel1(){
	$('.modal').modal('hide');
	$('.form-control').val("");
	$("#logdetails").hide();
	$("#logdetails1").hide(); //CHANGE
	$("#oop_details").hide();
 } 
 function cancel_opp(){
	$('.form-control').val("");		
	$("#Temp_date").hide();
	$("#remarks").hide();
	$("#remarks1").hide();
	$("#remarks2").hide();
	//$("#lost_id").hide();		
	$('input[type="text"], select, textarea').val('');
	$('input[type="radio"]').prop('checked', false);
	$('input[type="checkbox"]').prop('checked', false);
 }

 function close_modal(){
	$('#modalstart1').modal('hide');	
	$('.form-control').val("");
 } 
function codeAddress() {
    geocoder = new google.maps.Geocoder();
    var address = document.getElementById("search").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                    document.getElementById("long").value = results[0].geometry.location.lat();
                    document.getElementById("latt").value = results[0].geometry.location.lng();
                    map_marker();
            }else {
                    alert("Geocode was not successful for the following reason: " + status);
            }
    });
}
function map_marker(){
	var lat=document.getElementById("long").value;
	var log=document.getElementById("latt").value;

	var myCenter=new google.maps.LatLng(lat,log);
	var mapProp = {
	  center:myCenter,
	  zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	  };

	var map=new google.maps.Map(document.getElementById("mapname"),mapProp);

	var marker=new google.maps.Marker({
	  position:myCenter,
	  });

	marker.setMap(map);
}
function map_marker1(){
	var lat=document.getElementById("edit_long").value;
	var log=document.getElementById("edit_latt").value;

	var myCenter=new google.maps.LatLng(lat,log);
	var mapProp = {
	  center:myCenter,
	  zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	  };

	var map=new google.maps.Map(document.getElementById("edit_mapname"),mapProp);

	var marker=new google.maps.Marker({
	  position:myCenter,
	  });

	marker.setMap(map);
}
function show_map(){
	$("#map2").show();
	$("#map1").show();
	$("#select_map").hide();

	var lat=document.getElementById("long").value;
	var log=document.getElementById("latt").value;

	var myCenter=new google.maps.LatLng(lat,log);
	var mapProp = {
	  center:myCenter,
	  zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	  };

	var map=new google.maps.Map(document.getElementById("maploc"),mapProp);

	var marker=new google.maps.Marker({
	  position:myCenter,
	  });

	marker.setMap(map);
}
function edit_showmap(){
	$("#edit_map2").show();
	$("#edit_map1").show();
	$("#edit_selectmap").hide();

	var lat=document.getElementById("edit_long").value;
	var log=document.getElementById("edit_latt").value;

	var myCenter=new google.maps.LatLng(lat,log);
	var mapProp = {
	  center:myCenter,
	  zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	  };

	var map=new google.maps.Map(document.getElementById("edit_maploc"),mapProp);

	var marker=new google.maps.Marker({
	  position:myCenter,
	  });

	marker.setMap(map);
}
function editadd(){
	var lat=document.getElementById("edit_long").value;
	var log=document.getElementById("edit_latt").value;

	var myCenter=new google.maps.LatLng(lat,log);
	var mapProp = {
	  center:myCenter,
	  zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	 };

	var map=new google.maps.Map(document.getElementById("edit_maploc"),mapProp);

	var marker=new google.maps.Marker({
		position:myCenter,
	 });
	marker.setMap(map);
}
function save_leadinfo(){	
	var leadsource =[];
    if($.trim($("#leadname").val())==""){
		$("#leadname").closest("div").find("span").text("Lead name is required.");
		$("#leadname").focus();
		return;
    }else if(!validate_name($.trim($("#leadname").val()))){
		$("#leadname").closest("div").find("span").text("Enter Only Chracters");
		$("#leadname").focus();
		return;
    }else{
		$("#leadname").closest("div").find("span").text("");
    }  
	
	/*if($.trim($("#leadmail").val())==""){
		$("#leadmail").closest("div").find("span").text("Email is required.");
		$("#leadmail").focus();
		return;
    }else if(!validate_email($.trim($("#leadmail").val()))){
		$("#leadmail").closest("div").find("span").text("Enter valid email address");
		$("#leadmail").focus();
		return;
    } else{
		$("#leadmail").closest("div").find("span").text("");
    } */
	if($.trim($("#leadphone").val())==""){
		$("#leadphone").closest("div").find("span").text("Phone is required.");
		$("#leadphone").focus();
		return;
    }else if(!validate_phone($.trim($("#leadphone").val()))){
		$("#leadphone").closest("div").find("span").text("Please Enter 10 digit mobile number");
		return;
    }else{
		$("#leadphone").closest("div").find("span").text("");
    } 
    

   /*if($("#product_value li input[type=checkbox]").prop("checked")==false){   	
		$("#product").siblings(".error-alert").text("Product is required");
		return;
	    
   }*/
   /* if($.trim($("#leadsource").val())==""){
		$("#leadsource").closest("div").find("span").text("Leadsource is required.");
		$("#leadsource").focus();
		return;
    }
    else{
		$("#leadsource").closest("div").find("span").text("");
    } */
    
    /*if($.trim($("#country").val())==""){
		$("#country").closest("div").find("span").text("Country is required.");
		$("#country").focus();
		return;
    }
    else{
		$("#country").closest("div").find("span").text("");
    } 
    if($.trim($("#state").val())==""){
		$("#state").closest("div").find("span").text("state is required.");
		$("#state").focus();
		return;
    }
    else{
		$("#state").closest("div").find("span").text("");
    }
    
	if($.trim($("#city").val())==""){
		$("#city").closest("div").find("span").text("City is required.");
		$("#city").focus();
		return;
    }else if(!validate_city($.trim($("#city").val()))){
		$("#city").closest("div").find("span").text("Enter Only Chracters");
    } else{
		$("#city").closest("div").find("span").text("");
    } 
    if($.trim($("#zipcode").val())==""){
		$("#zipcode").closest("div").find("span").text("Zipcode is required.");
		$("#zipcode").focus();
		return;
    }else if(!validate_zipcode($.trim($("#zipcode").val()))){
		$("#zipcode").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#zipcode").closest("div").find("span").text("");
    }*/           
    if($.trim($("#firstcontact").val())==""){
		$("#firstcontact").closest("div").find("span").text("Contact Name is required.");
		$("#firstcontact").focus();
		return;
    }else if(!validate_contact($.trim($("#firstcontact").val()))){
		$("#firstcontact").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#firstcontact").closest("div").find("span").text("");
    }             
    /*if($.trim($("#disgnation").val())==""){
		$("#disgnation").closest("div").find("span").text("Designation is required.");
		$("#disgnation").focus();
		return;
    }else if(!validate_designation($.trim($("#disgnation").val()))){
		$("#disgnation").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#disgnation").closest("div").find("span").text("");
    } */
	/*if($.trim($("#primmobile").val())==""){
		$("#primmobile").closest("div").find("span").text("Mobile Nummber is required.");
		$("#primmobile").focus();
		return;
    }else if(!validate_phone($("#primmobile").val())){
		$("#primmobile").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#primmobile").closest("div").find("span").text("");
    } */

      $("#tree_leadsource input[type=radio]").each(function(){
        if($(this).prop('checked') == true){
                leadsource.push($.trim($(this).val()));
        }
    });

    var prod=[];
    $("#product_value li input[type=checkbox]").each(function(){
        if($(this).prop('checked')==true){
            prod.push($(this).val());
        }        
    });

    
   
    var addObj={};
    var mobiles=[];
    var emails=[];
    var leademail=[];
    addObj.product = prod;
    mobiles.push($.trim($("#primmobile").val()));
    mobiles.push($.trim($("#primmobile2").val()));
    emails.push($.trim($("#primemail").val()));
    emails.push($.trim($("#primemail2").val()));
    addObj.emails=emails;
    addObj.mobiles=mobiles;
    addObj.leadname = $.trim($("#leadname").val());
    addObj.leadwebsite = $.trim($("#leadweb").val());
    leademail.push($.trim($("#leadmail").val()));
    addObj.leademail =leademail;
    addObj.phone = $.trim($("#leadphone").val());
    //addObj.product = $.trim($("#product").val());
    //addObj.source = $.trim($("#leadsource").val());
    addObj.source = leadsource[0];
    addObj.country = $.trim($("#country").val());
    addObj.state = $.trim($("#state").val());
    addObj.city = $.trim($("#city").val());
    addObj.zipcode = $.trim($("#zipcode").val());
    addObj.ofcaddress = $.trim($("#ofcadd").val());
    addObj.splcomments = $.trim($("#splcomments").val());
    addObj.contactname = $.trim($("#firstcontact").val());
    addObj.designation = $.trim($("#disgnation").val());
    /*addObj.mobile1 = $.trim($("#primmobile").val());
    addObj.mobile2 = $.trim($("#primmobile2").val());
    addObj.email1 = $.trim($("#primemail").val());
    addObj.email2 = $.trim($("#primemail2").val());*/
    addObj.contacttype = $.trim($("#contacttypes").val());
  /* addObj.leadphoto = $.trim($("#contacttype").val());*/
   var longitude = $.trim($("#long").val());
   var lattitude = $.trim($("#latt").val());
    addObj.add_industry= $("#add_industry").val();
    addObj.add_business_location=$("#add_business_location").val();
    addObj.coordinate=longitude+","+lattitude;
    
    console.log(addObj);
     $.ajax({
        type : "POST",
        url : "<?php echo site_url('manager_leadController/post_leadinfo');?>",
        dataType : 'json',
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){
        	if(error_handler(data)){
                    	return;
           	}
        	if(data==true)
        	{
        		$('.modal').modal('hide');
        	}

           /* var leadid = data['leadid'];
                var fileurl = "<?php echo site_url("manager_leadController/file_upload/"); ?>"+leadid;
                uploadImage(fileurl, 'add');*/
		}
	});	 


}
function editAddress() {
    geocoder = new google.maps.Geocoder();
    var address = document.getElementById("edit_search").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                    document.getElementById("edit_long").value = results[0].geometry.location.lat();
                    document.getElementById("edit_latt").value = results[0].geometry.location.lng();
                    map_marker1();
            }else {
                    alert("Geocode was not successful for the following reason: " + status);
            }
    });
   }
function selrow(obj){
console.log(obj);    
$("#edit_product").html("");
$("#edit_leadname").val(obj.leadname);
$("#edit_leadweb").val(obj.leadwebsite);
$("#edit_leadmail").val(obj.leademail);
$("#edit_leadphone").val(obj.leadphone);
$("#edit_city").val(obj.city);
$("#edit_product").val(obj.product);
$("#edit_leadsource").val(obj.leadsource);
$("#edit_country").val(obj.leadcountry);
$("#edit_state").val(obj.state);
$("#edit_zipcode").val(obj.zipcode);
$("#edit_ofcadd").val(obj.leadtaddress);
$("#edit_splcomments").val(obj.repremarks);
$("#edit_disgnation").val(obj.employeedesg);
$("#edit_primmobile").val(obj.employeephone1);
$("#edit_primmobile2").val(obj.employeephone2);
$("#edit_primemail").val(obj.employeeemail);
$("#edit_primemai2").val(obj.employeeemail2);
$("#edit_firstcontact").val(obj.employeename);
$("#edit_contacttype").val(obj.contacttypeid);
$("#leadid").val(obj.leadid);
$("#employeeid").val(obj.employeeid);
$("#edit_long").val(obj.leadlng);
$("#edit_latt").val(obj.leadlat);
$("#edit_lead").text(obj.leadname);
$('#edit_selectmap').hide();
$('#edit_map1').show();
$("#logg").val(obj.leadid);
$("#edit_contadd").val(obj.leadtaddress);
$("#edit_industry").val(obj.lead_industry);
$("#edit_business_location").val(obj.lead_business_loc)
/*edit_showmap();*/
var img_path=(obj.leadphoto);

	
	$.ajax({
        type: "POST",
        url: "<?php echo site_url('manager_leadController/getIndustry')?>",
        dataType:'json',
        success: function(data) {
        	if(error_handler(data)){
            	return;
            }
        var select = $("#edit_industry"), options = "<option value=''>select</option>";
           select.empty();      
           for(var i=0;i<data.length; i++)
           {
                options += "<option value='"+data[i].industry_id+"'>"+ data[i].industry_name +"</option>"; 

           }
           select.append(options);
           $("#edit_industry option[value='"+obj.lead_industry+"']").attr("selected",true);

        }
        });

	$.ajax({
        type: "POST",
        url: "<?php echo site_url('manager_leadController/getLocation')?>",
        dataType:'json',
        success: function(data) {
        	if(error_handler(data)){
                    	return;
            }
        var select = $("#edit_business_location"), options = "<option value=''>select</option>";
           select.empty();      
           for(var i=0;i<data.length; i++)
           {
                options += "<option value='"+data[i].business_location_id+"'>"+ data[i].business_location_name +"</option>"; 

           }
           select.append(options);
           $("#edit_business_location option[value='"+obj.lead_business_loc+"']").attr("selected",true);

        }
        });

	 $.ajax({
	        type: "POST",
	        url: "<?php echo site_url('manager_leadController/get_country');?>",
	        dataType:'json',
	        success: function(data) {
	        	if(error_handler(data)){
                    	return;
                }
	        var select = $("#edit_country"), options = "<option value=''>select</option>";
	           select.empty();      
	           for(var i=0;i<data.length; i++)
	           {
	                options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>"; 

	           }
	           select.append(options);
	           $("#edit_country option[value='"+obj.leadcountry+"']").attr("selected",true);

	        }
	});
        
    var id= obj.leadcountry;
	$.ajax({ 
			type : "POST",
			url : "<?php echo site_url('manager_leadController/get_state'); ?>",
			data : "id="+id,
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
                    	return;
                }
			    var select = $("#edit_state"), options = "<option value=''>Select</option>";
			        select.empty();      
			        for(var i=0;i<data.length; i++)
			        {
			             options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
			        }
			        select.append(options);
			        $("#edit_state option[value='"+obj.state+"']").attr("selected",true);

			 }
	});
$('#edit_country').on('change',function(){
		    var id= this.value; 

	        $.ajax({
		            type: "POST",
		            url: "<?php echo site_url('manager_leadController/get_state'); ?>",
		            data : "id="+id,
		            dataType:'json',
		            success: function(data) {
		            	if(error_handler(data)){
                    		return;
                		}
		             var select = $("#edit_state"), options = "<option value=''>select</option>";
		               select.empty();      

		               for(var i=0;i<data.length; i++)
		               {
		                    options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
		               }
		               select.append(options);

		            }
	   		 });
 });
          /*  $.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_leadController/get_product');?>",
            dataType:'json',
            success: function(data) {
             var select = $("#edit_product"), options = "<option value=''>select</option>";
               select.empty();      
                for(var i=0;i<data.length; i++)
               {
                    options += "<option value='"+data[i].product_id+"'>"+ data[i].product_name +"</option>";              
               }
               select.append(options);
                $("#edit_product option[value='"+obj.productid+"']").attr("selected",true);


            }
        });*/

        /*$.ajax({
	            type: "POST",
	            url: "<?php echo site_url('manager_leadcontroller/get_productselected'); ?>",
	            dataType:'json',
	            success: function(data){
	                 $("#edit_product").html("");
	            var currencyhtml="";
	            currencyhtml +='<div id="product_value1" class="multiselect">';
	            currencyhtml +='<ul>';

	                    for(var j=0;j<data.length; j++){								
	                            currencyhtml +='<li><label><input type="checkbox" value="'+data[j].product_id+'">  '+data[j].product_name+'<label></li>';
	                    }
	                    currencyhtml +='</ul>';
	                    currencyhtml +='</div>';
	                    $("#edit_product").append(currencyhtml)
	            }
        });
*/
var id= obj.leadid;
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_leadcontroller/get_productselected'); ?>",
            dataType:'json',
            success: function(data){
            	if(error_handler(data)){
                    	return;
                }
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('manager_leadcontroller/product_array'); ?>",
                    data : "id="+id,
                    dataType:'json',
                    success: function(saved_data) {
                    	if(error_handler(data)){
                    		return;
                		}
                    console.log(data);
                    $("#edit_product").html("");
                    var currencyhtml="";
                    currencyhtml +='<div id="product_value1" class="multiselect">';
                    currencyhtml +='<ul>';

                    for(var j=0;j<data.length; j++){
                        var bmatch = 0;
                        for(var p=0;p<saved_data.length; p++){
                            if(saved_data[p].product_id==data[j].product_id){
                                bmatch = 1;
                                break;
                            }                        
                        }
                        if(bmatch == 1){
                            currencyhtml +='<li><label><input type="checkbox" checked value="'+data[j].product_id+'">  '+data[j].product_name+'<label></li>';
                        }else{
                            currencyhtml +='<li><label><input type="checkbox" value="'+data[j].product_id+'">  '+data[j].product_name+'<label></li>';
                        }
                    }
                    currencyhtml +='</ul>';
                    currencyhtml +='</div>';
                    $("#edit_product").append(currencyhtml)
                   }
                });
                 
            }
        });

         $.ajax({
	            type: "POST",
	            url: "<?php echo site_url('manager_leadController/contacttype'); ?>",
	            dataType:'json',
	            success: function(data) {
	            	if(error_handler(data)){
                    	return;
                	}
	             var select = $("#edit_contacttype"), options = "<option value=''>select</option>";
	               select.empty();      
	                for(var i=0;i<data.length; i++)
	               {
	                    options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
	               }
	               select.append(options);
	               $("#edit_contacttype option[value='"+obj.contacttypeid+"']").attr("selected",true);
	            }
        });

        $.ajax({
	            type : "POST",
	            url : "<?php echo site_url('manager_leadController/leadsource_edit'); ?>",
	            dataType : 'json',
	            data :  "id="+id,
	            cache : false,
	            success : function(data){
	            	if(error_handler(data)){
                    	return;
                	}
	         	edit_tree(data,"tree_leadsource1" );			
					var isInside = false;
						
					$("#tree_lead1").click(function () {
						$("#tree_leadsource1").show();
					});
					
					$("#tree_leadsource1").hover(function () {
						isInside = true;
					}, function () {
						isInside = false;
					})

					$(document).mouseup(function () {
						if (!isInside)
			            $("#tree_leadsource1").hide();
					});
	            }
        });
          /*$.ajax({
            type: "POST",
            url: "<?php echo site_url('manager_leadController/get_leadsource'); ?>",
            dataType:'json',
            success: function(data) {
             var select = $("#edit_leadsource"), options = "<option value=''>select</option>";
               select.empty();      
                for(var i=0;i<data.length; i++)
               {
                      options += "<option value='"+data[i].hvalue2+"'>"+ data[i].hvalue2 +"</option>";              
               }
               select.append(options);
               $("#edit_leadsource option[value='"+obj.leadsource+"']").attr("selected",true);
            }
        });*/

}

function edit_info(){
	var leadsource1=[];
    if($.trim($("#edit_leadname").val())==""){
		$("#edit_leadname").closest("div").find("span").text("Lead name is required.");
		$("#edit_leadname").focus();
		return;
    }else if(!validate_name($.trim($("#edit_leadname").val()))){
		$("#edit_leadname").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#edit_leadname").closest("div").find("span").text("");
    }  
	
	/*if($.trim($("#edit_leadmail").val())==""){
		$("#edit_leadmail").closest("div").find("span").text("Email is required.");
		$("#edit_leadmail").focus();
		return;
    }else if(!validate_email($.trim($("#edit_leadmail").val()))){
		$("#edit_leadmail").closest("div").find("span").text("Enter Only Chracters");
    } else{
		$("#edit_leadmail").closest("div").find("span").text("");
    } */
	if($.trim($("#edit_leadphone").val())==""){
		$("#edit_leadphone").closest("div").find("span").text("Phone is required.");
		$("#edit_leadphone").focus();
		return;
    }else if(!validate_phone($.trim($("#edit_leadphone").val()))){
		$("#edit_leadphone").closest("div").find("span").text("please Enter 10 digit mobile number");
    }else{
		$("#edit_leadphone").closest("div").find("span").text("");
    } 
	/*if($.trim($("#edit_city").val())==""){
		$("#edit_city").closest("div").find("span").text("City is required.");
		$("#edit_city").focus();
		return;
    }else if(!validate_city($.trim($("#edit_city").val()))){
		$("#edit_city").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#edit_city").closest("div").find("span").text("");
    } 
    if($.trim($("#edit_zipcode").val())==""){
		$("#edit_zipcode").closest("div").find("span").text("Zipcode is required.");
		$("#edit_zipcode").focus();
		return;
    }else if(!validate_zipcode($.trim($("#edit_zipcode").val()))){
		$("#edit_zipcode").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#edit_zipcode").closest("div").find("span").text("");
    }          */ 
    if($.trim($("#edit_firstcontact").val())==""){
		$("#edit_firstcontact").closest("div").find("span").text("Contact Name is required.");
		$("#edit_firstcontact").focus();
		return;
    }else if(!validate_contact($.trim($("#edit_firstcontact").val()))){
		$("#edit_firstcontact").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#edit_firstcontact").closest("div").find("span").text("");
    }             
    /*if($.trim($("#edit_disgnation").val())==""){
		$("#edit_disgnation").closest("div").find("span").text("Designation is required.");
		$("#edit_disgnation").focus();
		return;
    }else if(!validate_designation($.trim($("#edit_disgnation").val()))){
		$("#edit_disgnation").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#edit_disgnation").closest("div").find("span").text("");
    }*/ 
	/*if($.trim($("#edit_primmobile").val())==""){
		$("#edit_primmobile").closest("div").find("span").text("Mobile Nummber is required.");
		$("#edit_primmobile").focus();
		return;
    }else if(!validate_phone($("#edit_primmobile").val())){
		$("#edit_primmobile").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#edit_primmobile").closest("div").find("span").text("");
    }*/

    $("#tree_leadsource1 input[type=radio]").each(function(){
        if($(this).prop('checked') == true){
                leadsource1.push($.trim($(this).val()));
        }
    });


    var prod=[];    
    $("#product_value1 li input[type=checkbox]").each(function(){
        if($(this).prop('checked')==true){
            prod.push($(this).val());
        }        
    });  

    var addObj={};
    var mobiles=[];
    var emails=[];
    addObj.product = prod;
    mobiles.push($.trim($("#edit_primmobile").val()));
    mobiles.push($.trim($("#edit_primmobile2").val()));
    emails.push($.trim($("#edit_primemail").val()));
    emails.push($.trim($("#edit_primemai2").val()));  
    addObj.emails=emails;
    addObj.mobiles=mobiles;
    addObj.leadname = $.trim($("#edit_leadname").val());
    addObj.leadwebsite = $.trim($("#edit_leadweb").val());
    addObj.leademail = $.trim($("#edit_leadmail").val());
    addObj.phone = $.trim($("#edit_leadphone").val());
	// addObj.product = $.trim($("#edit_product").val());
	// addObj.source = $.trim($("#edit_leadsource").val());
   	addObj.source = leadsource1[0];
    addObj.country = $.trim($("#edit_country").val());
    addObj.state = $.trim($("#edit_state").val());
    addObj.city = $.trim($("#edit_city").val());
    addObj.zipcode = $.trim($("#edit_zipcode").val());
    addObj.ofcaddress = $.trim($("#edit_ofcadd").val());
    addObj.splcomments = $.trim($("#edit_splcomments").val());
    addObj.contactname = $.trim($("#edit_firstcontact").val());
    addObj.designation = $.trim($("#edit_disgnation").val());
    // addObj.mobile1 = $.trim($("#edit_primmobile").val());
    // addObj.mobile2 = $.trim($("#edit_primmobile2").val());
    // addObj.email1 = $.trim($("#edit_primemail").val());
    // addObj.email2 = $.trim($("#edit_primemai2").val());
    addObj.contacttype = $.trim($("#edit_contacttype").val());
    var longitude = $.trim($("#edit_long").val());
    var lattitude = $.trim($("#edit_latt").val());
    addObj.leadid = $.trim($("#leadid").val());
    addObj.employeeid = $.trim($("#employeeid").val());
    addObj.business_location=$("#edit_business_location").val();
    addObj.industry_name=$("#edit_industry").val();
    addObj.coordinate=longitude+","+lattitude;

	
	console.log(addObj);
	$.ajax({
	        type : "POST",
	        url : "<?php echo site_url('manager_leadController/update_lead'); ?>",
	        dataType : 'json',
	        data    : JSON.stringify(addObj),
	        cache : false,
	        success : function(data){
	        	if(error_handler(data)){
                    	return;
                }
	        	if(data==true){
        				$('.modal').modal('hide');        				
        		}
        		pageload();
	            var leadid = $.trim($("#leadid").val());
	            var fileurl = "<?php echo site_url("manager_leadController/file_upload/"); ?>"+leadid;
	            uploadImage(fileurl, 'edit');
			}
	});
}
function viewloc(){
var lat=document.getElementById("view_long").value;
var log=document.getElementById("view_latt").value;

var myCenter=new google.maps.LatLng(lat,log);
var mapProp = {
  center:myCenter,
  zoom:14,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("view_maploc"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,
  });

marker.setMap(map);
}
function viewrow(obj){
	
	$('#logdetails').hide();
	$('#logdetails1').hide();
	console.log(obj);
	$("#view_leadname").val(obj.leadname);
	$('#leadname_label').text(obj.leadname);
	$("#label_leadweb").html(obj.leadwebsite);
	$("#label_leadmail").html(obj.leademail);
	$("#label_leadphone").html(obj.leadphone);
	//$("#label_product").html(obj.product_name);
	$("#label_leadsource").html(obj.leadsource);
	$("#label_country").html(obj.countryname);
	$("#label_state").html(obj.statename);
	$("#label_city").html(obj.city);
	$("#label_zipcode").html(obj.zipcode);
	$("#view_ofcadd").html(obj.leadtaddress);
	$("#view_splcomments").html(obj.repremarks);
	$("#label_designation").html(obj.employeedesg);
	$("#label_primmobile").html(obj.employeephone1);
	$("#label_primmobile2").html(obj.employeephone2);
	$("#label_primemail").html(obj.employeeemail);
	$("#label_primemail2").html(obj.employeeemail2);
	$("#lead_firstcontact").html(obj.employeename);
	$("#label_contacttype").html(obj.contactype);
	$("#logg").val(obj.leadid);
	var latlng= obj.coordinate;
	/*alert(latlng);*/	
	var arr = latlng.split(',');	
	$("#view_long").val(arr[0]);
	$("#view_latt").val(arr[1]);
	/*alert($("#view_latt").val());*/
	$("#view_lead").html(obj.leadname);
	$("#logg").val(obj.leadid);
	$("#label_industry").html(obj.industry_name);
	$("#label_location").html(obj.business_location_name);
	viewloc();
	var id=obj.leadid;
  $.ajax({
    type: "POST",
    url: "<?php echo site_url('manager_leadController/product_views'); ?>",
    data : "id="+id,
    dataType:'json',
    success: function(data) {
    	if(error_handler(data)){
              	return;
        }
        var html="";
        html +='<div class="multiselect">';
        html +='<ul>';
        for(var j=0;j<data.length; j++){								
            html+="<li>"+data[j].hvalue2+"</li>";
        }
        html +='</ul>';
        html +='</div>';
        $("#label_product").html(html);
      }
  });

}


/*function add1(){
	//if($("#lost").is(":checked")){
		if($("#Permanent").is(':checked')){
			if($("#lost_remarks").val()==""){
				$("#lost_remarks").closest("div").find("span").text("Remarks is required.");
				$("#lost_remarks").focus();				
				return;
			}else{
				$("#lost_remarks").closest("div").find("span").text("");
			}
		}else{
			$("#Permanent").prop("checked", false);
		}
		if($("#Temporary").is(':checked')){
			if($("#tempdate").val()==""){
				$("#tempdate").closest("div").find("span").text("Date is required.");
				$("#tempdate").focus();				
				return;
			}else{
				$("#tempdate").closest("div").find("span").text("");
			}
			if($("#temp_remarks").val()==""){
				$("#temp_remarks").closest("div").find("span").text("Remarks is required.");
				$("#temp_remarks").focus();				
				return;
			}else{
				$("#temp_remarks").closest("div").find("span").text("");
			}		
		}
	//}
	var addObj={};			
	addObj.leadname = $.trim($("#edit_leadname").val());
    addObj.leadwebsite = $.trim($("#edit_leadweb").val());
    addObj.leademail = $.trim($("#edit_leadmail").val());
    addObj.phone = $.trim($("#edit_leadphone").val());
    addObj.product = $.trim($("#edit_product").val());
    addObj.source = $.trim($("#edikt_leadsource").val());
    addObj.country = $.trim($("#edit_country").val());
    addObj.state = $.trim($("#edit_state").val());
    addObj.city = $.trim($("#edit_city").val());
    addObj.zipcode = $.trim($("#edit_zipcode").val());
    addObj.ofcaddress = $.trim($("#edit_ofcadd").val());
    addObj.splcomments = $.trim($("#edit_splcomments").val());
    addObj.contactname = $.trim($("#edit_firstcontact").val());
    addObj.designation = $.trim($("#edit_disgnation").val());
    addObj.mobile1 = $.trim($("#edit_primmobile").val());
    addObj.mobile2 = $.trim($("#edit_primmobile2").val());
    addObj.email1 = $.trim($("#edit_primemail").val());
    addObj.email2 = $.trim($("#edit_primemail2").val());
    addObj.contacttype = $.trim($("#edit_contacttype").val());
    addObj.longitude = $.trim($("#edit_long").val());
    addObj.lattitude = $.trim($("#edit_latt").val());
    addObj.leadid = $.trim($("#leadid").val());
    addObj.employeeid = $.trim($("#employeeid").val());
	addObj.permanent = $("#Permanent").val();
	addObj.Temporary = $("#Temporary").val();
	addObj.tempdate = $("#tempdate").val();
	addObj.won_remarks = $("#won_remarks").val();
	addObj.lost_remarks = $("#lost_remarks").val();
	addObj.temp_remarks = $("#temp_remarks").val();
	addObj.won = $("#won").val();
	addObj.lost = $("#lost").val();
	console.log(addObj);	
	$.ajax({
		type : "POST",
		url : "lead_source.json",
		dataType : 'json',
		data : JSON.stringify(addObj),
		cache : false,
		success : function(data){		
		$('input[type="text"], select, textarea').val('');
		$('input[type="radio"]').prop('checked', false);
		$('input[type="checkbox"]').prop('checked', false);
		$('.closeinput').val('');
		$("#Temp_date").hide();
		$("#remarks").hide();
		$("#remarks1").hide();
		$("#remarks2").hide();
		//$("#lost_id").hide();						
		}		
	});	
}*/
function leadfetch()
{
					 $('#logdetails').toggle();
					 var view_leadid={};
				 view_leadid.id=$("#logg").val();
	$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/fetch_unAssignedLog'); ?>",
			data : JSON.stringify(view_leadid),
			dataType:'json',
			success: function(data) {
					if(error_handler(data)){
              			return;
       				 }
					console.log(data);
					var row = "";
					for(i=0; i < data.length; i++ ){  
						$('#logtable').empty();
						var rowdata = JSON.stringify(data[i]);	
						if(data[i].rating == 1){
							row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].meeting_start +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
						}else if(data[i].rating == 2){
							row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].meeting_start +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
						}else if(data[i].rating == 3){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' ></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
						}else if(data[i].rating == 4){
							row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' ></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
						}else {
							row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].time_stamp +"</td><td>" + "-" + "</td><td>" + "<i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + "-" +"</td><td>" + "-" +"</td><td>" + "-" +"</td>";
						}
				}     
				
				$('#logtable').append(row);  
			}
	});
	
		
     
    $('#select_map').hide();
  // $('#logdetails').hide();
    $('#oop_details').hide();
    $("#map2").hide();
    $("#okmap").click(function(){
		$("#select_map").show();
		$("#map1").hide();
		$("#map2").hide();
		rendergmap();
    });
    $("#edit_okmap").click(function(){
    $("#edit_selectmap").show();
    $("#edit_map2").hide();
    $("#edit_map1").hide();
     edit_rendergmap();
    });

	 $("#opp_log").click(function(){
	     $('#oop_details').show();
	 });

}

	function schedule_fetch(){
		$('#logdetails1').toggle();
		var view_leadid={};
		view_leadid.id=$("#logg").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/logs_schedule'); ?>",
			data : JSON.stringify(view_leadid),
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
            	  	return;
        		}
					console.log(data);
					var row = "";
					for(i=0; i < data.length; i++ ){  
						$('#logtable1').empty();
						var rowdata = JSON.stringify(data[i]);	
						if(data[i].rating == 1){
							row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].meeting_start +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
						}else if(data[i].rating == 2){
							row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].meeting_start +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
						}else if(data[i].rating == 3){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' ></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
						}else if(data[i].rating == 4){
							row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' ></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
						}else {
							row += "<tr><td>" + (i+1) + "</td><td>" + data[i].user_name +"</td><td>" + data[i].meeting_start +"</td><td>" + data[i].meeting_end +"</td><td>" + data[i].activity +"</td><td>" + data[i].activity +"</td><td>"+data[i].remarks +"</td>";
						}
				}     
				$('#logtable1').append(row);  
			}
		});
		// $('#select_map').hide();
		// $('#logdetails').hide();
		// $('#logdetails1').hide();
		$('#oop_details').hide();
		// $("#map2").hide();
		//  $("#okmap").click(function(){
		//	$("#select_map").show();
		//	$("#map1").hide();
		//	$("#map2").hide();
		//		rendergmap();
		//  });
		// $("#edit_okmap").click(function(){
		// $("#edit_selectmap").show();
		//  $("#edit_map2").hide();
		//  $("#edit_map1").hide();
		//   edit_rendergmap();
		// });

		$("#opp_log").click(function(){
			$('#oop_details').show();
		});
	}


function addExl(){
    $("#modal_upload").modal("show");
}
	
	function checkAllLeads(e) {
		$('tr input:checkbox',$("#tableTeam")).prop('checked',e.checked);
	}
	function checkAllMgrs(e)	{
		$('li input:checkbox',$("#mgrlist")).prop('checked',e.checked);
	}

function lead_history(){
			/*$("#leadview").modal("hide");*/
			$('#lead_hist').modal('show');
			var lead_histid={};
			alert($('#logg').val());
			lead_histid.id=$('#logg').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_leadController/fetch_leadhistory'); ?>",
			data : JSON.stringify(lead_histid),
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
              		return;
        		}
				console.log(data);		
				var history = data;
				if(history) {		
					$('#tablebody1').empty();
					var mapping_ids = [];
					for (var i = 0; i < history.length; i++) {
						if (mapping_ids.indexOf(history[i].mapping_id) < 0) {
							mapping_ids.push(history[i].mapping_id);		
							var action = history[i].action;
							var from_name = history[i].from_user_name;
							var to_name = history[i].to_user_name;
							var lead_cust_name = history[i].lead_cust_name;				
							var remarks = history[i].remarks;
							var timestamp = history[i].timestamp;
							var rowhtml = '';
							if (action == 'created') {
								rowhtml += `<div class="created"> 
											<div><b><h3 style='display:inline;'>`+capitalizeFirstLetter(action)+`</h3></b>
											by <u><b>` + from_name + `</u></b> for `+ lead_cust_name +`</div>
											<b>` ;
								
								rowhtml += `at <h5 style='display:inline;color:#777777'>` + timestamp + `</h5></div>`;
								/*alert(rowhtml)*/
							} 
							else if (action == 'accepted') {
								rowhtml += `<div class="created"> 
											<div><b><h3 style='display:inline;'>`+capitalizeFirstLetter(action)+`</h3></b>
											by <u><b>` + to_name + `</u></b> for `+ lead_cust_name +`</div>
											<b>` ;
								
								rowhtml += `at <h5 style='display:inline;color:#777777'>` + timestamp + `</h5></div>`;
								//alert(rowhtml)
							} 
							else if ((action == 'assigned') || (action == 'reassigned')){
								//get count of this mapping ID in array.
								assigned_to = 0;
								assigned_to_names = [];
								for(var c = 0; c < history.length; c++)	{
									if (history[c].mapping_id == history[i].mapping_id) {
										assigned_to++;
										assigned_to_names.push(history[c].to_user_name);
									}
								}
								
									if(assigned_to > 1)	{
										to_name = assigned_to + " users";
									}
									rowhtml = `<div class="assigned"> 
											<div><b><h3 style='display:inline;'>`+capitalizeFirstLetter(action)+`</h3></b>
											to <u><b>`+ to_name + `</u></b></div>`;
									/*if (remarks.length > 0) {
									rowhtml +="<div>Remarks - " + remarks +"</div>";
									}*/
									rowhtml += `at <h5 style='display:inline;color:#777777'>` + timestamp + `</h5></div>`;
									
							}
							else if (action == 'added remarks')	{
								rowhtml = `<div class="remarks"> 
											<div><b><h3 style='display:inline;'>`+capitalizeFirstLetter(action)+`</h3></b>
											by <u><b>` + from_name + `</u></b></div>`;
								/*if (remarks.length > 0) {
									rowhtml +="<div>Remarks - " + remarks +"</div>";
								}*/
								rowhtml += `at <h5 style='display:inline;color:#777777'>` + timestamp + `</h5></div>`;
							} 							
							row =   `<tr>
										<td>`+ rowhtml + `</td>
									</tr>`;

							$('#tablebody1').append(row);	
					}								
				}
			}
		}	
	});	
}


</script>
    </head>
       <body class="hold-transition skin-blue sidebar-mini">   
        <?php require 'demo.php' ?>
        <?php require 'manager_sidenav.php' ?>
        <div class="content-wrapper body-content">
            <div class="col-lg-12 column">
				<div class="row header1">				
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >		
								<img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="New Leads List"/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Closed Lost Leads</h2>	
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
                                            <div class="addBtns" onclick="add_lead()">
                                             <a href="#leadinfoAdd" class="addPlus" data-toggle="modal" >
                                                <img src="/images/new/Plus_Off.png" onmouseover="this.src='/images/new/Plus_ON.png'" onmouseout="this.src='/images/new/Plus_Off.png'" width="30px" height="30px"/>
                                             </a>
                                      <a  class="addExcel" onclick="addExl()" >
                                         <img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/></a>
                                            </div>
                                           
					</div>
				</div>
				<div class="table-responsive">
					<table class="table hidden" id="tableTeam">
						<thead>  
						<tr>	
							<!-- <th class="table_header"><input type="checkbox" name="select_all" id="select_all_leads" onclick="checkAllLeads(this)"></th> -->
							<th class="table_header"></th>
							<th class="table_header">#</th>
							<th class="table_header">Name</th>
							<th class="table_header">Contact</th>
							<th class="table_header"> Designation</th>
							<th class="table_header"> Owned_By</th>
							<th class="table_header">Phone</th>		
							<th class="table_header">Email</th>
							<th class="table_header">Location</th>	
							<th class="table_header">Lead Source</th>	
							<th class="table_header">Status</th>
							<th class="table_header"></th>
							<th class="table_header"></th>		
						</tr>
						</thead>  
						<tbody id="tablebody">
						</tbody>    
					</table>
				</div>
				<div align="center">
					<input type="button" class="btn hidden" onclick="assign_btn()" id="assign1" value="ReAssign"/>
				</div>
            </div>
                       
            <div id="leadinfoAdd" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="addpopup" class="form" action="#" method="post" >
                            <div class="modal-header">
                                <span class="close" onclick="add_cancel()">x</span>
                                <h4 class="modal-title"><b>Add Lead</b></h4>
                            </div>
                            <div class="modal-body">								
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="leadname">Lead Name*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="leadname" name="leadname" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="leadweb">Lead Website</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="leadweb" name="leadweb" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="leadmail">Lead Email</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="adminContactDept" class="form-control" id="leadmail" name="leadmail" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="leadphone">Lead Phone*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="leadphone" name="leadphone" autofocus>											
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="product">Product</label> 
                                    </div>
                                    <div class="col-md-4">
                                       <div id="product">
                                       </div>
                                        <span class="error-alert"></span>
                                    </div>
                                     <div class="col-md-6">
                               <label for="tree_leadsource"  id="tree_lead" ><a href="#" >Lead Source<b class="glyphicon glyphicon-menu-right" style="position: absolute;top: 4px;"></b></a></label>
				<div id="tree_leadsource" class="tree-view" style="display:none"></div>
                               <span class="error-alert"></span>
                               <label class="leadsrcname"></label>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2" >
                                        <label for="country">Country</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select  class="form-control" id="country" name="country" onchange="change1()" autofocus>
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="state">State</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select type="text" class="form-control" id="state" name="state" autofocus>
                                        </select>				
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="city">City</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="city" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 ">
                                        <label for="zipcode">Zipcode</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <input type="text"  class="form-control" id="zipcode" name="zipcode" autofocus>

                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                  <div class="row">
                                    <div class="col-md-2">
                                        <label for="add_industry">Industry</label> 
                                    </div>
                                    <div class="col-md-4">
                                       <select  class="form-control" id="add_industry" name="add_industry" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="add_business_location">Location</label> 
                                    </div>
                                    <div class="col-md-4">
                                       <select  class="form-control" id="add_business_location" name="add_business_location" >
                                        </select>

                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row lead_address" >
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
										<center><b>Office Address</b></center>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6  ">
										<center><b>Special Comments</b></center>
									</div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
                                        <textarea class="form-control" id="ofcadd"></textarea>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
                                        <textarea class="form-control" id="splcomments"></textarea>
                                    </div>
                                </div>
                                 <div class="row" id="map1">
                                <div class="row">
									<center>
										<button type="button" class="btn" id="okmap" >Google Map</button>
									</center>
                                 </div>
                                 </div>
                                <div class="row" id="map2" >
									<div class="row" id="maploc" style="width:100% px;height:150px;border:1px;">
									 </div>
                                </div>
                                <div class="row" id="select_map" >
									<div class="row" id="mapname" >
									 </div>
                                    <div class="row">
                                        <div class="col-md-1 ">
                                        <label for="search">Search</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <input type="text" class="form-control" onfocusout="codeAddress();" id="search" name="search" />
                                    </div>
									<div class="col-md-1">
										<label for="long">Longitude</label> 
                                    </div>
                                    <div class="col-md-2 ">
                                        <input type="text" class="form-control" id="long" name="long"/>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="latt">Latitude</label> 
                                    </div>
                                    <div class="col-md-2 ">
                                        <input type="text" class="form-control" id="latt" name="latt" />
                                    </div>
                                    
                                    <div class="col-md-1 ">
                                        <button type="button"  class="btn btn-default" id="proceed" style="margin-top:0px" onclick="show_map();">OK</button>
                                    </div>
                                    </div>
                                </div>
                              
                                <div class="row" >
                                    <div class="col-md-12 lead_address">
                                        <center><b>Lead Contact Person Information</b></center>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="firstcontact">Contact Person*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="firstcontact" name="firstcontact" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 ">
                                        <label for="disgnation">Designation</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="disgnation" name="disgnation" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="primmobile">Mobile Number 1</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="primmobile" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="primmobile2">Mobile Number 2</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="primmobile2" name="primmobile" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="primemail">Email 1</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="primemail" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="primemail2">Email 2</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="primemail2" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="displaypic">Photo</label> 
                                    </div>
                                    <div class="col-md-4">									
										<label for="adminImageUploadE" class="custom-file-upload">
											<i class="fa fa-cloud-upload"></i> Image Upload
										</label>
                                        <input type="file" class="form-control" id="displaypic" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="contacttypes">Contact Type</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="contacttypes">
                                            
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row lead_contact_address" >
                                    <div class="col-md-2">
                                        <label for="Address">Address</label>  
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
                                        <textarea class="form-control" id="contadd"></textarea>
                                    </div>
		
                                </div>
								<div class="add_leadContact">
								
								</div>
								<!--<div class="row">
									<div class="col-md-10">
										
									</div>
									<div class="col-md-2">
										<input type="button" class="btn" onclick="add_contact1()" value="Add Contact"/>
									</div>
								</div>-->
                              </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" onclick="save_leadinfo()">Save</button>
                                <button  type="button" class="btn btn-default" onclick="add_cancel()" >Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
              <div id="leadinfoedit" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="editpopup" class="form" action="#" method="post" >
                            <div class="modal-header">
                                <span class="close" onclick="cancelCust()">x</span>
                                <h4 class="modal-title"><b>Edit <span id="edit_lead"></span></b></h4>
                            </div>
                            <div class="modal-body">								
                                <div class="row">
                                    <div class="col-md-2 ">
                                        <label for="edit_leadname">Lead Name*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_leadname" name="edit_leadname" autofocus>
                                        <input type="hidden" class="form-control" id="leadid" name="leadid" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_leadweb">Lead Website</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="edit_leadweb" name="edit_leadweb" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_leadmail">Lead Email</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"name="adminContactDept" class="form-control" id="edit_leadmail" name="edit_leadmail" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_leadphone">Lead Phone*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"class="form-control" id="edit_leadphone" name="edit_leadphone" autofocus>											
                                        <span class="error-alert"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_product">Product</label> 
                                    </div>
                                    <div class="col-md-4" id="edit_product" name="edit_product">
                                       
                                        <span class="error-alert"></span>
                                    </div>

                                    <!--  <div class="col-md-6">
                               <label for="tree_leadsource1"  id="tree_lead1" ><a href="#" >Lead Source<b class="glyphicon glyphicon-menu-right" style="position: absolute;top: 4px;"></b></a></label>
				<div id="tree_leadsource1" class="tree-view" style="display:none"></div>
                                 <span class="error-alert"></span>
                                    </div> -->
                                    <div class="col-md-6">
                               			<label id="tree_lead1" >
                                   			<a href="#" >Lead Source<b class="glyphicon glyphicon-menu-right" style="position: absolute;top: 4px;"></b>
                                   			</a>
                              			</label>
									<div id="tree_leadsource1" class="tree-view" style="display:none"></div>      			
										<span class="error-alert"></span>
										<label class="leadsrcname"></label>
                                    </div>
                                </div>

                              <!--   <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_product">Product*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="edit_product" name="edit_product" autofocus>
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_leadsource">Lead Source</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="edit_leadsource" name="edit_leadsource" autofocus>
                                        </select>				
                                        <span class="error-alert"></span>
                                    </div>
                                </div> -->
                                <div class="row">
                                    <div class="col-md-2" >
                                        <label for="edit_country">Country</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select  class="form-control" id="edit_country" name="edit_country"  autofocus>
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_state">State</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select type="text" class="form-control" id="edit_state" name="edit_state" autofocus>
                                        </select>				
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_city">City</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_city" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_zipcode">Zipcode</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="edit_zipcode" name="edit_zipcode" autofocus>

                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_industry">Industry</label> 
                                    </div>
                                    <div class="col-md-4">
                                       <select  class="form-control" id="edit_industry" name="edit_industry" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_business_location">Location</label> 
                                    </div>
                                    <div class="col-md-4">
                                       <select  class="form-control" id="edit_business_location" name="edit_business_location" >
                                        </select>

                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row lead_address">
									<div class="col-md-6 col-sm-6 col-xs-6">
										<center><b>Office Address</b></center>
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<center><b>Special Comments</b></center>
									</div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <textarea class="form-control" id="edit_ofcadd"></textarea>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <textarea class="form-control" id="edit_splcomments"></textarea>
                                    </div>
                                </div>
								 <div class="row" id="edit_map2" >
									<div class="row" id="edit_maploc" style="width:100% px;height:150px;border:1px;">
									 </div>
                                </div>
                                 <div class="row" id="edit_map1">
									<div class="row">
										<center>
											<button type="button" class="btn" id="edit_okmap" >Google Map</button>
										</center>
									 </div>
                                 </div>                               
                                <div class="row" id="edit_selectmap" >
                                <div class="row" id="edit_mapname" style="width:100% px;height:150px;border:1px;">
                                 </div>
                                    <div class="row">
                                        <div class="col-md-1">
                                        <label for="search">Search</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" onfocusout="editAddress();" id="edit_search" name="edit_search" />
                                    </div>
                                  <div class="col-md-1">
                                   <label for="edit_long">Longitude</label> 
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" id="edit_long" name="edit_long"/>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="edit_latt">Latitude</label> 
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" id="edit_latt" name="edit_latt" />
                                    </div>
                                    
                                    <div class="col-md-1">
                                        <button type="button"  class="btn btn-default" id="proceed" style="margin-top:0px" onclick="edit_showmap();">OK</button>
                                    </div>
                                    </div>
                                </div>
                              
                                <div class="row" >
                                    <div class="col-md-12 lead_address">
                                        <center><b>Lead Contact Person Information</b></center>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_firstcontact">Contact Person*</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <input type="text" class="form-control" id="edit_firstcontact" name="edit_firstcontact" autofocus>
                                        <input type="hidden"  id="employeeid" name="employeeid">
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_disgnation">Designation</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="edit_disgnation" name="edit_disgnation" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_primmobile">Mobile Number 1</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_primmobile" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_primmobile2">Mobile Number 2</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <input type="text"  class="form-control" id="edit_primmobile2" name="edit_primmobile2" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_primemail">Email 1</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_primemail" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_primemai2">Email 2</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="edit_primemai2" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 ">
                                        <label for="edit_displaypic">Photo</label> 
                                    </div>
                                    <div class="col-md-4">									
										<label for="adminImageUploadE" class="custom-file-upload">
											<i class="fa fa-cloud-upload"></i> Image Upload
										</label>
                                        <input type="file" class="form-control" id="displaypic" autofocus>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 ">
                                        <label for="edit_contacttype">Contact Type</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="edit_contacttype">
                                            
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
							<!--	<div class="edit_leadContact">
								
								</div>
								<div class="row">
									<div class="col-md-10">
										
									</div>
									<div class="col-md-2">
										<input type="button" class="btn" onclick="add_contact()" value="Add Contact"/>
									</div>
								</div>-->
                                <div class="row lead_contact_address" >
                                    <div class="col-md-2">
                                        <label for="Address">Address</label>  
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
                                        <textarea class="form-control" id="edit_contadd"></textarea>
                                    </div>
		
                                </div>                        
                              </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" onclick="edit_info()">Save</button>
                                <button  type="button" class="btn btn-default" onclick="cancelCust()" >Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="leadview" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="viewpopup" class="form" action="#" method="post" >
                            <div class="modal-header">
                                <span class="close" onclick="cancel1()">x</span>
                                <h4 class="modal-title"><b>View <span id="view_lead"></span></b><span style='margin-left: 725px;'onclick="lead_history(
                                )" class="fa fa-history"></span></h4>
                            </div>
                            <div class="modal-body">								
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_leadname">Lead Name</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <label id="leadname_label"></label>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_leadweb">Lead Website</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <label id="label_leadweb"></label> 
                                       <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_leadmail">Lead Email</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <label id="label_leadmail"></label> 
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_leadphone">Lead Phone</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                      <label id="label_leadphone"> </label> 

                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_product">Product</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                    <label id="label_product"></label>                                       
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_leadsource">Lead Source</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                               <label id="label_leadsource"></label>                                         				
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label" >
                                        <label for="view_country">Country</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                       <label id="label_country"></label> 
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_state">State</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <label id="label_state"></label> 			
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_city">City</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <label id="label_city"></label> 
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_zipcode">Zipcode</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                         <label id="label_zipcode"></label> 
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div calss="row">
                                 <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_industry">Industry</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                         <label id="label_industry"></label> 
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                 <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_location">Location</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                         <label id="label_location"></label> 
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row lead_address" >
									<div class="col-md-6 col-sm-6 col-xs-6">
										<center><b>Office Address</b></center>
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<center><b>Special Comments</b></center>
									</div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<label id="view_ofcadd">Contact Person</label> 
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<label id="view_splcomments">Contact Person</label> 
                                    </div>
                                </div>
                                 <div class="row" >
                                    <div class="col-md-12 lead_address">
                                        <center><b>Google Map</b></center>
                                    </div>
                                </div>
                                <input type="hidden" id="view_latt">
                                 <input type="hidden" id="view_long">
                                <div class="row" id="view_map2" >
                                <div class="row" id="view_maploc" style="width:100% px;height:150px;border:1px;">
                                 </div>
                                </div>
                               <div class="row" >
                                    <div class="col-md-12 lead_address">
                                        <center><b>Lead Contact Person Information</b></center>
                                    </div>
                                </div>
								<div class="row">
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_firstcontact">Contact Person</label> 
											</div>
											<div class="col-md-4">
												<label id="lead_firstcontact"></label> 
												<input type="hidden"  id="employeeid" name="employeeid">
												<span class="error-alert"></span>
											</div>                                    
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_designation">Designation</label> 
											</div>
											<div class="col-md-4">
												<label id="label_designation"></label> 
												<span class="error-alert"></span>
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primmobile">Mobile Number 1</label> 
											</div>
											<div class="col-md-4">
												<label id="label_primmobile"></label> 
												<span class="error-alert"></span>
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primmobile2">Mobile Number 2</label> 
											</div>
											<div class="col-md-4">
												 <label id="label_primmobile2"></label> 
												<span class="error-alert"></span>
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primemail">Email 1</label> 
											</div>
											<div class="col-md-4">
												 <label id="label_primemail"></label> 
												  <span class="error-alert"></span>
											</div>                                   
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_contacttype">contact Type</label> 
											</div>
											<div class="col-md-4">
												<label id="label_contacttype"></label> 
												<input type="hidden" id="lead_id"/>
												<span class="error-alert"></span>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="row">
										<div class="col-md-2 apport_label">
											<label for="view_displaypic">Photo</label> 
										</div>
										<div class="col-md-4">
											 <img width="100" height="100" id="leadpic"/>
										</div>
										</div>
										<div class="row">
											<div class="col-md-4 apport_label">
												<label for="view_primemai2">Email 2</label> 
											</div>
											<div class="col-md-4">
												<label id="label_primemail2"></label> 
												<span class="error-alert"></span>
											</div>                                  
										</div>										
									</div>
								</div>
                                <div class="row" id="view_log">
                                <div class="row btn_log">
									<center>
										<button type="button" class="btn" id="leadlog" onclick="leadfetch();">View Lead Log</button>		
									</center>
                                </div>
                                </div>
                                <div class="row" id="logdetails">
                                    <div class="col-md-12 lead_view">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <center><b>Lead Log</b></center>
                                        </div>
                                    </div>
                                        <table class="table" id="tablelog">
                                            <thead>  
                                            <tr>	
                                               <th class="table_header">SL No</th>
                                                <th class="table_header">leadname</th>
                                                <th class="table_header">Date-Time</th>
                                                <th class="table_header">Activity</th>
                                                <th class="table_header">Ratings</th>		
                                                <th class="table_header">Duration</th>
                                                <th class="table_header">Asset</th>
                                                <th class="table_header">Remarks</th>	
                                            </tr>
                                        </thead>  
                                            <tbody id="logtable">
                                            </tbody>    
                                       </table>   
				                                        
				                <input type="hidden" id="logg">
								</div>

								<div class="row btn_log">
									<center>
										<button type="button" class="btn" id="leadlog1" onclick="schedule_fetch();" >View Scheduled Activity</button>		
									</center>
                                </div>

                                <div class="row" id="logdetails1">
                                    <div class="col-md-12 lead_view">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <center><b>Scheduled Activities</b></center>
                                        </div>
                                    </div>
                                        <table class="table" id="tablelog1">
                                        	<thead>  
                                            	<tr>	
													<th class="table_header">#</th>
													<th class="table_header">rep_name</th>
													<th class="table_header">Start-Date</th>				
													<th class="table_header">End-Date</th>		
													<th class="table_header">Activity</th>											
													<th class="table_header">Asset</th>
													<th class="table_header">Remarks</th>	
                                            	</tr>
                                        	</thead>  
                                            <tbody id="logtable1">
                                            </tbody>    
                                       </table>   	                                        

								</div>


							<!-- 	  <div class="row" id="opportunity">
										<div class="row btn_log">
											<center>
												<button type="button" class="btn" id="opp_log" >View Opportunities</button>
											</center>
										</div>
								</div>
								 <div class="row" id="oop_details">
                                   <div class="col-md-12" style="background-color:#c1c1c1;padding: 10px 12px;">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                        <center><b>Opportunity List</b></center>
                                        </div>
                                    </div>
                                     <table class="table" id="tableopp">
                                        <thead>  
											<tr>	
												<th class="table_header"></th>
												<th class="table_header">SL No</th>
												<th class="table_header">Name</th>
												<th class="table_header">Product</th>	
												<th class="table_header">Sales Stage</th>	
												<th class="table_header">Expected Close Date</th>
												<th class="table_header">Stage Owner</th>
											</tr>
										</thead>  
                                    <tbody id="opp_table">
                                   </tbody>    
                                   </table>
                               </div>
                               -->   <div class="modal-footer">
                               <p align="center">
									<input type="button" class="btn" onclick="assign_btn2()" value="Assign"/></p>
								<input type="hidden" id="assign2">
                                <input type="button" class="btn btn-default" onclick="cancel1()" value="Cancel">
                            </div>
                          </div>
                      </form>
                    </div>
                </div>
            </div>
              <div id="modalstart1" class="modal fade" data-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addpopup" class="form">
                                <div class="modal-header">
                                        <span class="close"  onclick="close_modal()">&times;</span>
                                        <h4 class="modal-title">Lead Assignment</h4>
                                </div>
                            <div class="modal-body">

                                <div class="row targetrow ">
										<div class="col-md-2">
											<label for="mgrlist">Manager list</label> 
										</div>
										<div class="col-md-10">											
											<div id="mgrlist" class="multiselect2" >
												<ul>
												</ul>
											</div>
											<span class="error-alert"></span>
										</div>
										<!-- <div class="col-md-2">
											<label for="replist">Replist</label> 
										</div>
										<div class="col-md-4">			
											<div id="replist" class="multiselect1">
												<ul>
												</ul>													
											</div>
											<span class="error-alert"></span>
										</div>	 -->					
								</div>
								<input type="checkbox" name="select_all_mgr" onclick="checkAllMgrs(this)"> Select All                                  	
                               <!--  <div class="row targetrow ">
															                                      
	                           	</div> -->
	                           		
                                    <!-- <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <label for="product">End Date</label> 
                                            </div>
                                            <div class="col-md-4">
                                                <div class='input-group date' id='startDateTimePicker'>
                                                    <input id="start_date" placeholder="Select Date" type='text' class="form-control" readonly="readonly" />
                                                    <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            //   <input type="checkbox" id="test" name="test" class="checkbox_check" checked/><p>Send E-mail notification</p> 
                                                <span class="error-alert"></span>
                                            </div>
                                        </div>
                                    </div> -->
							</div>
							<div class="modal-footer">
									<input type="button" class="btn" onclick="assign_save()" value="Assign">
									<!--<input type="button" class="btn" onclick="cancel()" value="Cancel" >-->
									<input type="button" class="btn" onclick="close_modal()" value="Cancel">
							</div>
						</form>
					</div>
				</div>
			</div>
		<div id="lead_hist" class="modal fade" data-backdrop="static" >
				<div class="modal-dialog modal-lg">
        				<div class="modal-content">
                        		<div class="modal-header">
                                		<span class="close" data-dismiss="modal">&times;</span>
                                 			<h3>lead_history</h3>
                        		</div>
                        		<div class="modal-body">
						<table class="table">
							<thead>
								<tr>
									<th class="table_header">lead History</th>
								</tr>
							</thead>  
									<tbody id="tablebody1">				
									</tbody>    
						</table>
						</div>
        				<!-- <div class="modal-body">							
                        	<div class="row">                                    
                            <label id="lhist"></label>    
                        </div>	 -->
                      
                        <div class="modal-footer" id="modal_footer">
                                      
                           <input type="button" class="btn" data-dismiss="modal" value="Cancel" >                           
                        </div>
    		    </div>
		</div>
 </div>
		

       	<div id="modal_upload" class="modal fade" data-backdrop="static" >
<div class="modal-dialog">
        <div class="modal-content">
                        <div class="modal-header">
                                 <span class="close" data-dismiss="modal">&times;</span>
                                 <h3>Upload File</h3>
                        </div>
                        <div class="modal-body">								
                                        <div class="row">
                                                <div class="col-md-3">
                                                        <label for="add_role">Select File*</label> 
                                                </div>
                                                <div class="col-md-5">
                                                        <input name="files" type='file' id="files" file-input="files"/>
                                                        <span class="error-alert"></span>
                                                </div>
                                                <div class="col-md-4">
                                                    <a class="btn btn-primary" href="<?php echo base_url(); ?>/uploads/lead_template.xlsx">
                                                                Download Template
                                                        </a>
                                                        <span class="error-alert"></span>
                                                </div>
                                        </div>	
                        </div>
                        <div class="modal-footer" id="modal_footer">

                                        <input type="button" class="btn" id="save_file" value="Save">
                                        <input type="button" class="btn" data-dismiss="modal" value="Cancel" >

                        </div>
        </div>
</div>
			</div>
           <div id="counterList"  class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                        <h4 class="modal-body"> </h4>
                </div>
            </div>
        </div>
        <?php require 'footer.php' ?>

    </body>
</html>
