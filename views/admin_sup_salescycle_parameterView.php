<!DOCTYPE html>
<html lang="en">
    <head>

    <?php $this->load->view('scriptfiles'); ?>
<style>

 /*-------------Check box style------------*/
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
.section-two,
.section-three,
.section-four,
.section-five{
	display:none;
}
ul {
        list-style-type: none;
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

/* ------------------ Custom alert------------------------ */
.mask{
	width: 100%;
    margin: auto;
    height: 100%;
    position: absolute;
    top: 0;
    background: transparent;
    z-index: 999999;
}
.alert.alert-danger.row{
	position: absolute;
    z-index: 99999999;
    top: 0;
    width: 60%;
    margin: 20% 20% !important;
}
.multiselect.disable{
	background-color: #eee;
    opacity: 1;
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

#addmodal .alert.alert-info,
#editmodal .alert.alert-info{
    background-color: #00c0ef !important;
    border-color: #bce8f1;
    padding:4px;
}
#addmodal .alert-info.alert-warning,
#editmodal .alert-info.alert-warning{
    background-color: #f39c12 !important;
}
#prv{
	margin-right: 5px;
}
#combination .table tbody tr,#prv{
	display:none;
}
#combination .table tbody tr.active01{
	display:table-row;
	border-bottom:1px solid #ccc;
}
#combination .table thead tr th{
	background: #808080;
    color: #fff!important;
}
.pagingBar .paginationBtn{
	background: #999 !important;
    border-radius: 50%;
}
.pagingBar .paginationBtn.active{
	background: #b5000a !important;
}
.next-prev a{
        font-size: 32px;
        height: 28px;
        line-height: 32px;
}
#tablebody ul{
 padding: 0 20px;
 list-style-type: circle;
}

</style>
<script>
$(document).ready(function(){

    loadpage();
    /* loadpage1(); */

});

function validate_name(name) {
  var nameReg = new RegExp(/^[a-zA-Z0-9 &_]*$/);
  var valid = nameReg.test(name);

  if (!valid) {
  	return false;
  } else {
  	return true;
  }
}
/* ------------------------ function to convert the json data in tree structure ---------------- */
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
/* ------------------------------------------------------------------------------ */


var prodOption=[];
var prodOption1={};
var lastnodeFlag=0;

/*----------------------tree function ------------------------------*/
function edit_tree(data, container, select, saveLastNode){
    $("#"+container).html("");
	var oflocArray = convert(data);
	var $ul = $('<ul class="mytree"></ul>');
	getList(oflocArray, $ul,select);
	$ul.appendTo("#"+container);
	var display_list=[];

	$("#"+container+" input[type=radio]").each(function(){

		if($(this).closest('li').children('ul').length > 0){

			$(this).closest('label').closest('div').find('.glyphicon').addClass('glyphicon-minus-sign');
		}else{
			$(this).closest("label").css({'text-decoration':'underline','font-style':'italic'}).addClass('lastnode');
			$(this).closest("label").find('input[type=radio]').remove();
		}

		if($(this).attr('disabled', false)){
			display_list=[];
			if($(this).prop('checked')==true){
				if($(this).closest('li').children('ul').length > 0){
					$(this).closest('li').children('ul').find(".lastnode").each(function(){
					    var pNode = $(this).closest('ul').siblings('.PClass').find('input[type=radio]').closest('label').text().trim();
						display_list.push({"pname":pNode , "name":$.trim($(this).text()) , "value":$.trim($(this).closest("label").attr('class'))});
					});
				}
			}
			var html= '';

		/* --------------------------------------- */
        $(this).change(function(){
			display_list=[];
			if($(this).prop('checked')==true){
				if($(this).closest('li').children('ul').length > 0){
					$(this).closest('li').children('ul').find(".lastnode").each(function(){
					  var pNode = $(this).closest('ul').siblings('.PClass').find('input[type=radio]').closest('label').text().trim();
						display_list.push({"pname":pNode ,"name":$.trim($(this).text()) , "value":$.trim($(this).closest("label").attr('class'))});
					});
				}
			}
			var html= '';
			if($(this).attr("name") == "Editprod" || $(this).attr("name") == "Addprod" || $(this).attr("name") == "Editindus"|| $(this).attr("name") == "Editloc" || $(this).attr("name") == "Addindus" || $(this).attr("name") == "Addbloc"){
				var wrapper = $(this).closest(".row").find('.displayChecked').attr('id');
				$(this).closest(".row").find(".displayChecked").find(".selectAll").closest("label").remove();
				$(this).closest(".row").find(".displayChecked").prepend("<label class='chkAllLabel'><input class='selectAll' type='checkbox' onchange='chkAll(\""+wrapper+"\")'>Select all</label>");
				if(saveLastNode == "null"){
					for(i=0; i< display_list.length; i++){
						var aa = $.trim(display_list[i].value.replace(" lastnode", ""));
						if(display_list[i] != ""){
							html +="<li class='"+display_list[i].value +"'><label><input type='checkbox'> "+display_list[i].name+"<b> ("+display_list[i].pname+")</b></label></li>";
						}
					}
				}else{
					for(i=0; i< display_list.length; i++){
						var aa = $.trim(display_list[i].value.replace(" lastnode", ""));
						var bFound = false;

						for(t=0; t< saveLastNode.length; t++){
							if(saveLastNode[t].parameter_product_node == aa || saveLastNode[t].parameter_industry_node == aa || saveLastNode[t].parameter_location_node == aa){
								bFound = true
								break;
							}
						}

						if(bFound){
							html +="<li class='"+display_list[i].value +"'><label><input type='checkbox' checked> "+display_list[i].name+"<b> ("+display_list[i].pname+")</b></label></li>";
						}else{
							html +="<li class='"+display_list[i].value +"'><label><input type='checkbox'> "+display_list[i].name+"<b> ("+display_list[i].pname+")</b></label></li>";
						}
					}
				}
			}
			$(this).closest(".row").find('ol').html(html);
			var that = $(this).closest(".row").find("ol").find("li input[type=checkbox]");
			that.each(function(){
				that.change(function(){
					if(that.is(":checked")){
						that.closest(".displayChecked").find(".selectAll").prop("checked", false)
					}
				})
			});
		});

		/* --------------------------------------- */
			$(this).closest(".row").find('ol').html(html);
		}
	});

	$("#"+container+" input[type=radio]").each(function(){
		if($(this).prop('checked')==true){
				displayList($(this).val(), $(this).attr("name"), saveLastNode);
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
	});
    loaderHide();
}

/* ------------------------------------------------------------------------------------------ */

var globalArray =[];
var globarr=[];
var savedProdID = "";

function displayList(id, nameAttr, saveLastNode){

		var rightChkList = $("#id"+id).closest(".row").find(".displayChecked").attr('id');
		var id = $.trim(id);
		var display_list=[];
		var prod_list=[];
		$("#id"+id).closest('li').children('ul').children('li').find("label.lastnode").each(function(){
		    var pNode = $(this).closest('ul').siblings('.PClass').find('input[type=radio]').closest('label').text().trim();
			display_list.push({"pname":pNode ,"name":$.trim($(this).text()) , "value":$.trim($(this).closest("label").attr('class'))});
		});

        /* ###################################### */
        var html= '';
		if($.trim($("#id"+id).attr('name')) == "Editprod"){
			$("#id"+id).closest('li').children('ul').children('li').find("label.lastnode").each(function(){
			    var pNode = $(this).closest('ul').siblings('.PClass').find('input[type=radio]').closest('label').text().trim();
				prod_list.push({"pname":pNode ,"name":$.trim($(this).text()) , "value":$.trim($(this).closest("label").attr('class'))});
				savedProdID = id;
			});
			globalArray = prod_list;
		}
		if(nameAttr == "Editprod" || nameAttr == "Addprod" || nameAttr == "Editindus" || nameAttr == "Editloc"  || nameAttr == "Addindus" || nameAttr == "Addbloc"){
			/* ----slelct All------------- */
			var wrapper = $("#id"+id).closest(".row").find('.displayChecked').attr('id');
			$("#id"+id).closest(".row").find(".displayChecked").find(".selectAll").closest("label").remove();
			$("#id"+id).closest(".row").find(".displayChecked").prepend("<label class='chkAllLabel'><input class='selectAll' type='checkbox' onchange='chkAll(\""+wrapper+"\")'> Select all</label>");
			/* ----slelct All------------- */
			if(saveLastNode == "null"){
				for(i=0; i< display_list.length; i++){
					var aa = $.trim(display_list[i].value.replace(" lastnode", ""));
					if(display_list[i] != ""){
						html +="<li class='"+display_list[i].value +"'><label><input type='checkbox'> "+display_list[i].name+"<b> ("+display_list[i].pname+")</b></label></li>";
					}
				}
			}else{
				for(i=0; i< display_list.length; i++){
					var aa = $.trim(display_list[i].value.replace(" lastnode", ""));
					var bFound = false;

					for(t=0; t< saveLastNode.length; t++){
					   if(saveLastNode[t].parameter_product_node == aa || saveLastNode[t].parameter_industry_node == aa || saveLastNode[t].parameter_location_node == aa){
							bFound = true
							break;
						}
					}
					if(bFound){
						html +="<li class='"+display_list[i].value +"'><label><input type='checkbox' checked> "+display_list[i].name+"<b> ("+display_list[i].pname+")</b></label></li>";
					}else{
						html +="<li class='"+display_list[i].value +"'><label><input type='checkbox'> "+display_list[i].name+"<b> ("+display_list[i].pname+")</b></label></li>";
					}
				}
			}
		}
        /* ###################################### */
		$("#id"+id).closest(".row").find("ol").html(html);

		var that = $("#id"+id).closest(".row").find("ol").find("li input[type=checkbox]");
		that.each(function(){
			that.change(function(){
				if(that.is(":checked")){
					that.closest(".displayChecked").find(".selectAll").prop("checked", false)
				}
			})
		});
        loaderHide();
}

/* ------------------------ constructing tree structure ---------------- */
function getList(item, $list,select) {
			if($.isArray(item)){
				$.each(item, function (key, value) {
					getList(value, $list,select);
				});
			}

			if (item) {
				var $li = $('<li />');
				if (item.name) {

					if(item.checked == true){
						$li.append($("<div class='PClass'><div class='dash-left'><label class='glyphicon' id='"+item.id+"'></label>---</div><label class='"+item.id+"'> <input name='"+item.nameAttr+"' type='radio' value='"+item.id+"' id='id"+item.id+"'  checked ><b>  " + item.name + "</b></label></div>"));
					}else{
						$li.append($("<div class='PClass'><div class='dash-left'><label class='glyphicon' id='"+item.id+"'></label>---</div><label class='"+item.id+"'> <input  name='"+item.nameAttr+"' type='radio' value='"+item.id+"' id='id"+item.id+"' ><b>  " + item.name + "</b></label></div>"));
					}
				}
				if (item.children && item.children.length) {
					var $sublist = $("<ul class=child-count-"+item.children.length+"></ul>");
					getList(item.children, $sublist,select)
					$li.append($sublist);
				}
				$list.append($li)
			}
}

function chkAll(wrapper){
		if($("#"+wrapper).find(".selectAll").is(":checked")){
			$("#"+wrapper).find("li input[type=checkbox]").prop("checked", true);
		}else{
			$("#"+wrapper).find("li input[type=checkbox]").prop("checked", false);
		}
	}


/* --------------------------------------------------------------- on load fill the table list ------------------------------------------------- */
var idarray=[]; /*  globle array */
function loadpage(){
        loaderShow();
        $.ajax({
            type : "POST",
            url : "<?php echo site_url('admin_sup_salescycle_parameterController/display_data'); ?>",
            dataType : 'json',
            cache : false,
            success : function(data){
				if(error_handler(data)){
								return;
							}
				$('#tablebody').parent("table").dataTable().fnDestroy();
                $('#tablebody').empty();
                var row="";
                for(i=0; i < data.length; i++ ){
                  var rowdata = JSON.stringify(data[i]);

                  if(data[i].cycle_togglebit == 0){
                       var str="";
                        /* product */
                        var str1="";
                        for(var a=0;a < data[i]['pro_data'].length; a++){
                          str1+='<li>'+ data[i].pro_data[a].product +'</li>';
                        }
                        /* industry */
                        var str2="";
                        for(var a=0;a < data[i]['ind_data'].length; a++){
                          str2+='<li>'+ data[i].ind_data[a].industry +'</li>';
                        }
                        /* location */
                        var str3="";
                        for(var a=0;a < data[i]['loc_data'].length; a++){
                          str3+='<li>'+ data[i].loc_data[a].location +'</li>';
                        }
                        /* sell type */
                        var str4="";
                        var selltype="";
                        for(var a=0;a < data[i]['param_data'].length; a++){
                          str4+='<li>'+ data[i].param_data[a].paramname +'</li>';

                        }

                       row += "<tr style='background:#ff9f80'><td >" +(i+1)+ "</td><td><ul>"+str1+"</ul></td><td><ul>"+str2+"</ul></td><td><ul>"+str3+"</ul></td><td><ul>"+str4+"</ul></td><td>" + data[i].cyclename + "</td><td valign='middle'><a data-toggle='modal' href='#editmodal' id='editcompose' onclick='selrow("+rowdata+",\"editrow\")'  style='display: block' ><span class='glyphicon glyphicon-pencil'></span></a></td><td valign='middle'><a data-toggle='modal' href='#editmodal' id='editcompose1' onclick='selrow("+rowdata+",\"addrow\")'  style='display: block'><span class='glyphicon glyphicon-plus-sign'></span></a></td><td><label class='switch'><input  onchange='toggle(\"aa"+data[i].id+"\",\""+data[i].parameter_id+"\","+rowdata+")' id='aa"+data[i].id+"'  type='checkbox'  ><div class='slider round' style='display: none'></div></label> </td></tr>";
                    }
                    else if(data[i].cycle_togglebit == 1){
                        idarray.push(data[i].cycle_id);
                        var str="checked";

                        var str1="";
                        for(var a=0;a < data[i]['pro_data'].length; a++){
                          str1+='<li>'+ data[i].pro_data[a].product +'</li>';
                        }

                        var str2="";
                        for(var a=0;a < data[i]['ind_data'].length; a++){
                          str2+='<li>'+ data[i].ind_data[a].industry +'</li>';
                        }

                        var str3="";
                        for(var a=0;a < data[i]['loc_data'].length; a++){
                          str3+='<li>'+ data[i].loc_data[a].location +'</li>';
                        }

                        var str4="";
                        var selltype="";
                        for(var a=0;a < data[i]['param_data'].length; a++){
                            str4+='<li>'+ data[i].param_data[a].paramname +'</li>';
                        }

                        row += "<tr><td >" +(i+1)+ "</td><td><ul>"+str1+"</ul></td><td><ul>"+str2+"</ul></td><td><ul>"+str3+"</ul></td><td><ul>"+str4+"</ul></td><td>" + data[i].cyclename + "</td><td valign='middle'><a data-toggle='modal' href='#editmodal' id='editcompose' onclick='selrow("+rowdata+",\"editrow\")' ><span class='glyphicon glyphicon-pencil'></span></a></td><td valign='middle'><a data-toggle='modal' href='#editmodal' id='editcompose1' onclick='selrow("+rowdata+",\"addrow\")'><span class='glyphicon glyphicon-plus-sign'></span></a></td><td><label class='switch'><input  onchange='toggle(\"aa"+data[i].id+"\",\""+data[i].parameter_id+"\","+rowdata+")' id='aa"+data[i].id+"' "+str+" type='checkbox'  ><div class='slider round' style='display: none'></div></label> </td></tr>";
                    }

                 }
                 $('#tablebody').append(row);
                 loaderHide();
                 $('#tablebody').parent("table").DataTable({
                        "aoColumnDefs": [
                        {
                            "bSortable": false,
                            "aTargets": [6,7,8] }
                        ]
                 });

            }
    });

}
/* ----------------------------------------- common function to fill all select box before adding new cycle ---------------------------------------------------- */
function spin(elm){
	$("#"+elm).css({
		  'background':'url(<?php echo base_url();?>images/hourglass.gif)',
		  'background-position':'center',
		  'background-size':'30px',
		  'background-repeat':'no-repeat'
	})
}
function compose(){
        $("#hid_type").val("");
        $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sup_salescycle_parameterController/product'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
								return;
							}
				edit_tree(data, 'products', "", 'null');
			}
	    });

}

/* ----------------------------------------------------------- on click function of edit icon ----------------------------------------------------- */
var parameter_industry ="", parameter_location ="",parameter_protype="";
var retainOldChk,retainOldChk1,retainOldChk2;
function selrow(obj,type){
	parameter_industry =obj.ind_data;
	parameter_location =obj.loc_data;
	parameter_protype =obj.param_data;
    loaderShow();

        /*for(var i=0;i<obj.param_data.length;i++){
            if(obj.param_data[i].parameter_for == 'new_sell'){
                $("#sales_newsellE").prop('checked',true);
            }else if(obj.param_data[i].parameter_for == 'up_sell'){
               $("#sales_upsellE").prop('checked',true);
            }else if(obj.param_data[i].parameter_for == 'cross_sell'){
               $("#sales_crosssellE").prop('checked',true);
            }
        }*/
    $("#hid_type").val(type);
    $("#hid_parameterid").val(obj.parameter_id);
    $("#hid_cycleid").val(obj.cycle_id);
    $("#edit_cycle").val(obj.cycle_id);
    $("#hid_tgbit").val(obj.cycle_togglebit);
    addObj={};
    addObj.cycle_id=obj.cycle_id;

     /* ----------------------  product tree*/
      $.ajax({
          type : "POST",
          url : "<?php echo site_url('admin_sup_salescycle_parameterController/get_productdata_edit'); ?>",
          dataType : 'json',
          data : JSON.stringify(addObj),
          cache : false,
          success : function(data){
			  if(error_handler(data)){
								return;
							}
              if(obj.hasOwnProperty("pro_data")){
                retainOldChk = obj.pro_data;
          	    edit_tree(data,"productsE", "",obj.pro_data);
              }else{
                  edit_tree(data,"productsE","", "null");
              }
          }
      });
      loaderHide();
 }
/* ---------------------------------------------------------------------------------------------------------------------------------------------------- */

/* ------------------ proceed one button --------------------------------------------------- */
var myArrayID_pro =[]; // globle array
function sectionOneP(status){

	/* ----------For Add module----------- */
	if(status == 'add'){
            loaderShow();
            myArrayID_pro =[];
            lastnodeFlag=0;
			$("#selectProduct").html("");
			$("#display_prod .lastnode").each(function(){
				if($(this).find("input[type=checkbox]").prop("checked")==true){
					var optionVal = $.trim($(this).attr("class").replace(" lastnode", ""));
					var optionText = $.trim($(this).text());
					prodOption.push({"val":optionVal , "txt": optionText})
					prodOption1[optionVal]=optionText;
					lastnodeFlag = 1;
				}
			})
			$("#products input[type=radio]").each(function(){
                if($(this).prop('checked') == true){
                    myArrayID_pro.push($.trim($(this).val()));
                }
            });
            if(myArrayID_pro.length  == 0){
                $(".error-alert.product-list").text("Please Choose the Products for the Support Cycle");
                loaderHide();
                return;
            }else{
                 $(".error-alert.product-list").text("");
            }

			if(lastnodeFlag == 0){
				$(".error-alert.product-list").text("You will need to select atleast one Product to proceed further.");
                loaderHide();
				return;
			}else{
				$(".error-alert.product-list").text("");
			}

    		 $.ajax({
    			type : "POST",
    			url : "<?php echo site_url('admin_sup_salescycle_parameterController/industry'); ?>",
    			dataType : 'json',
    			cache : false,
    			success : function(data){
					if(error_handler(data)){
								return;
							}
    				edit_tree(data, 'industry' , "", 'null');
    			}
    		});
    		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
    		$(".section-two").show();
	}
    	/* ----------For edit module----------- */
    if(status == 'edit'){
	    loaderShow();
        lastnodeFlag=0;
	        $("#selectProduct").html("");

			$("#display_prodE .lastnode").each(function(){
				if($(this).find("input[type=checkbox]").prop("checked")==true){
					lastnodeFlag = 1;
				}
			})
			$("#productsE input[type=radio]").each(function(){
                if($(this).prop('checked') == true){
                    myArrayID_pro.push($.trim($(this).val()));
                }
            });
            if(myArrayID_pro.length  == 0){
                $(".error-alert.product-list").text("Please Choose the Products for the Support Cycle.");
                loaderHide();
                return;
            }else{
                 $(".error-alert.product-list").text("");
            }

			if(lastnodeFlag == 0){
				$(".error-alert.product-list").text("You will need to select atleast one product to proceed further");
                loaderHide();
				return;
			}else{
				$(".error-alert.product-list").text("");
			}

            var addObj={};
            addObj.paramid=$("#hid_cycleid").val();
    		 $.ajax({
    			type : "POST",
    			url : "<?php echo site_url('admin_sup_salescycle_parameterController/get_industry_edit'); ?>",
    			dataType : 'json',
    			data : JSON.stringify(addObj),
    			cache : false,
    			success : function(data){
					if(error_handler(data)){
								return;
							}
    			    retainOldChk = parameter_industry;
    				edit_tree(data, 'industryE', "", parameter_industry);
    			}
    		});

    		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
    		$(".section-two").show();
    }
}

/* --------------------- proceed two button ---------------------------------------------- */
function sectionTwoP(status){
    /* add module code-- */
	if(status == 'add'){
        loaderShow();
		var industryArray =[];
		$("#industry input[type=radio]").each(function(){
			if($(this).prop('checked') == true){
				industryArray.push($.trim($(this).val()));
			}
		});
		if(industryArray.length  == 0){
			$(".error-alert.industry-list").text("Please Choose an Industry for Support Cycle.");
            loaderHide();
			return;
		}else{
			 $(".error-alert.industry-list").text("");
		}
		var lastnodeFlag=0
		$("#display_loc .lastnode").each(function(){
			if($(this).find("input[type=checkbox]").prop("checked")==true){
				lastnodeFlag = 1;
			}
		})
		if(lastnodeFlag == 0){
			$(".error-alert.industry-list").text("You will need to select atleast one Industry to proceed further.");
			loaderHide();
			return;
		}else{
			$(".error-alert.industry-list").text("");
		}
		 $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sup_salescycle_parameterController/locations'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
								return;
							}
				edit_tree(data, 'business_loc', "", 'null');
			}
		});

		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
		$(".section-three").show();
	}

    /* ---------- edit module code */
    if(status == 'edit'){
	    loaderShow();
        var param=$('#hid_parameterid').val();
		var industryArray =[];
		$("#industryE input[type=radio]").each(function(){
			if($(this).prop('checked') == true){
				industryArray.push($.trim($(this).val()));
			}
		});
		if(industryArray.length  == 0){
			$(".error-alert.industry-list").text("Please Choose an Industry for Support Cycle.");
            loaderHide();
			return;
		}else{
			 $(".error-alert.industry-list").text("");
		}

		var lastnodeFlag=0
		$("#display_locE .lastnode").each(function(){
			if($(this).find("input[type=checkbox]").prop("checked")==true){
				lastnodeFlag = 1;
			}
		})
		if(lastnodeFlag == 0){
			$(".error-alert.industry-list").text("You will need to select atleast one Industry to proceed further.");
			loaderHide();
			return;
		}else{
			$(".error-alert.industry-list").text("");
		}
        var addObj={};
        addObj.paramid=$("#hid_cycleid").val();
		 $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sup_salescycle_parameterController/get_business_edit'); ?>",
			dataType : 'json',
			data : JSON.stringify(addObj),
			cache : false,
			success : function(data){
				if(error_handler(data)){
								return;
							}
			    retainOldChk = parameter_location;
				edit_tree(data, 'business_locE', "", parameter_location);
			}
		});

		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
		$(".section-three").show();
	}
}

/* ---------------- proceed three button ------------------------------------------------ */
function support_process(savedData, container, maindata){
  var html='';
  html += '<label><input class="selectAll" type="checkbox" onchange="chkAll(\''+container+'\')"/> Select All</label>';
  html += "<ul class='multiselect'>";
  for(i=0; i<maindata.length; i++){
        var chk=0;
      if(savedData == "null"){
          html += '<li><label><input type="checkbox" value="'+maindata[i].lookup_id+'"/> '+ maindata[i].lookup_value +'<label></li>';
      }else{
          for(j=0; j<savedData.length; j++){
            if(maindata[i].lookup_id == savedData[j].parameter_for){
                html += '<li><label><input type="checkbox" value="'+maindata[i].lookup_id+'" checked/> '+ maindata[i].lookup_value +'<label></li>';
                chk=0;
                break;
            }else{
               chk=1;
            }
          }
          if( chk == 1 ){
               html += '<li><label><input type="checkbox" value="'+maindata[i].lookup_id+'"/> '+ maindata[i].lookup_value +'<label></li>';
            }
      }

  }
  html += "</ul>";
  $("#"+container).html("").html(html);
        var that = $("#"+container+" ul li input[type=checkbox]")
        that.each(function(){
			that.change(function(){
				if(that.is(":checked")){
					$("#"+container).find(".selectAll").prop("checked", false)
				}
			})
		});
}
function sectionThreeP(status){
    /* add module code ------------------ */
	if(status == 'add'){
	    loaderShow();
		var BusinessLocationArray =[];
		$("#business_loc input[type=radio]").each(function(){
			if($(this).prop('checked') == true){
				BusinessLocationArray.push($.trim($(this).val()));
			}
		});
		if(BusinessLocationArray.length  == 0){
			$(".error-alert.business-list").text("Please Choose an Business Location for Support Cycle.");
            loaderHide();
			return;
		}else{
			 $(".error-alert.business-list").text("");
		}
		var lastnodeFlag=0
		$("#display_Category .lastnode").each(function(){
			if($(this).find("input[type=checkbox]").prop("checked")==true){
				lastnodeFlag = 1;
			}
		})
		if(lastnodeFlag == 0){
			$(".error-alert.business-list").text("You will need to select atleast one Business Location to proceed further.");
			loaderHide();
			return;
		}else{
			$(".error-alert.business-list").text("");
		}
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sup_salescycle_parameterController/get_cycle'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
								return;
							}
				var select = $("#salescyle"), options = "<option value=''>Choose Cycle</option>";
				select.empty();
				for(var i=0;i<data.cycle.length; i++)
				{
					 options += "<option value='"+data.cycle[i].CYCLE_ID+"_"+data.cycle[i].togglebit+"'>"+ data.cycle[i].CYCLE_NAME +"</option>";
				}
				select.append(options);
                loaderHide();
                support_process("null", "support_process", data.process);
			}
		});


		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
		$(".section-four").show();
	}

    /*  edit module code --------------- */
    if(status == 'edit'){
	    loaderShow();
		var BusinessLocationArray =[];
		$("#business_locE input[type=radio]").each(function(){
			if($(this).prop('checked') == true){
				BusinessLocationArray.push($.trim($(this).val()));
			}
		});
		if(BusinessLocationArray.length  == 0){
			$(".error-alert.business-list").text("Please Choose an Business Location for Support Cycle.");
            loaderHide();
			return;
		}else{
			 $(".error-alert.business-list").text("");
		}
		var lastnodeFlag=0
		$("#display_CategoryE .lastnode").each(function(){
			if($(this).find("input[type=checkbox]").prop("checked")==true){
				lastnodeFlag = 1;
			}
		});
		if(lastnodeFlag == 0){
			$(".error-alert.business-list").text("You will need to select one Business Location to proceed further.");
			loaderHide();
			return;
		}else{
			$(".error-alert.business-list").text("");
		}
        var cycleid=$('#hid_cycleid').val();
        var cycleid=cycleid+"_"+$("#hid_tgbit").val();
        $.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_sup_salescycle_parameterController/get_cycle'); ?>",
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
								return;
							}
					var select = $("#edit_cycle"), options = "<option value=''>Choose Cycle</option>";
					select.empty();
					for(var i=0;i<data.cycle.length; i++)
					{
						 options += "<option value='"+data.cycle[i].CYCLE_ID+"_"+data.cycle[i].togglebit+"'>"+ data.cycle[i].CYCLE_NAME +"</option>";
					}
					select.append(options);
				   $("#edit_cycle option[value='"+cycleid+"']").attr("selected",true);

                   loaderHide();
                   retainOldChk = parameter_protype;
                   support_process(parameter_protype, "support_processE", data.process);
				}/*,
                error: function(data){

                }*/
		});
		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
		$(".section-four").show();
	}
}

/* ---------------- proceed four button ------------------------------------------------ */
function sectionFourP(status){
  /* ------------------add module-------------------------- */
  if(status == 'add'){

        if($("#salescyle").val()==""){
      	    $("#salescyle").closest("div").find("span").text("Support Cycle is required.");
              loaderHide()
      	    return;
        }else{
        	    $("#salescyle").closest("div").find("span").text("");
                $("#cycleNmeV").text( $("#salescyle option:selected").text());
        }
		var seltypeChk=0;
		$("#support_process ul li input[type=checkbox]").each(function(){
			if($(this).prop('checked') == true){
				seltypeChk=1;
			}
		});
		if(seltypeChk==0){
			$('#seltypeErchk span').text("Please choose atleast one Sell Type");
			loaderHide()
			return;
		}else{
			$('#seltypeErchk span').text("");
		}
         var cycleid=$('#salescyle').val();
         var val=cycleid.split('_');

         if(idarray.length){
         for(var i=0;i<idarray.length;i++){
        	if(val[0]!=idarray[i]){

            	}else{
            		$("#salescyle").closest("div").find("span").text("Cycle Already exists");
                    loaderHide()
                    return;
            	}
            }
         }
         common_fn_togetCombination("display_prod","display_loc","display_Category","support_process","addmodal");
  }
  /* ------------------edit module-------------------------- */
  if(status == 'edit'){

            if($("#edit_cycle").val()==""){
          	    $("#edit_cycle").closest("div").find("span").text("Sales Cycle is required.");
                loaderHide();
          	    return;
            }else{
          	    $("#edit_cycle").closest("div").find("span").text("");
                $("#cycleNmeVE").text( $("#edit_cycle option:selected").text());
            }
			var seltypeChk=0;
			$("#support_processE ul li input[type=checkbox]").each(function(){
				if($(this).prop('checked') == true){
					seltypeChk=1;
				}
			});
			if(seltypeChk==0){
				$('#seltypeErchkE span').text("Please choose atleast one Process Type");
				return;
			}else{
				$('#seltypeErchkE span').text("");
			}
            flg=0;
            var cycleid=$('#edit_cycle').val();
            var val=cycleid.split('_');
            var typeofsave=$("#hid_type").val();
            if(typeofsave =='editrow'){
                if(val[0]==$("#hid_cycleid").val()){
      		        addObj.salescyle = $.trim($("#edit_cycle").val());
                    flg=1;
        		}
                if(flg==0){
                    for(i=0;i<idarray.length;i++){
                        if(val[0]!=idarray[i]){

          				}else{
          					$("#edit_cycle").closest("div").find("span").text("Already exists");
                              loaderHide();
          					  return;
          				}
                    }
                }

            }else if(typeofsave =='addrow'){
                 for(i=0;i<idarray.length;i++){
                    if(val[0]!=idarray[i]){

      				}else{
      					$("#edit_cycle").closest("div").find("span").text("Already exists");
                          loaderHide();
      					return;
      				}
                }
            }
       common_fn_togetCombination("display_prodE","display_locE","display_CategoryE","support_processE","editmodal");
  }

}

/* ---------------- common function to getcombinations ------------------------------------------------ */

function getPermutation(array, prefix) {
        prefix = prefix || '';
        if (!array.length) {
    	    return prefix;
        }
        var result = array[0].reduce(function (result, value) {
    	    return result.concat(getPermutation(array.slice(1), prefix + value+","));
        }, []);
        return result;
}


var selectedsallType=[];
function common_fn_togetCombination(pro_listid,industry_listid,busi_listid,supportpro_id,modal_type){
    var selectedProd=[];
    var selectedIndustry=[];
    var selectedBusinessLoc=[];
    selectedsallType=[];
    var listProdId=[];
    var listProdName=[];
    var listIndsId=[];
    var listIndsName=[];
    var listBusiId=[];
    var listBusiName=[];
    var supProId=[];
    var supProName=[];
    $("#"+pro_listid+" .lastnode").each(function(){
    	if($(this).find("input[type=checkbox]").prop("checked")==true){
    		var optionVal = $.trim($(this).attr("class").replace(" lastnode", ""));
    		var optionText = $.trim($(this).text());
    		selectedProd.push({"val":optionVal , "txt": optionText});
    		//lastnodeFlag = 1;
    	}
    });
    $("#"+industry_listid+" .lastnode").each(function(){
    	if($(this).find("input[type=checkbox]").prop("checked")==true){
    		var optionVal = $.trim($(this).attr("class").replace(" lastnode", ""));
    		var optionText = $.trim($(this).text());
    		selectedIndustry.push({"val":optionVal , "txt": optionText});
    		//lastnodeFlag = 1;
    	}
    });
    $("#"+busi_listid+" .lastnode").each(function(){
    	if($(this).find("input[type=checkbox]").prop("checked")==true){
    		var optionVal = $.trim($(this).attr("class").replace(" lastnode", ""));
    		var optionText = $.trim($(this).text());
    		selectedBusinessLoc.push({"val":optionVal , "txt": optionText});
    		//lastnodeFlag = 1;
    	}
    });
    $("#"+supportpro_id+" ul li ").each(function(){
    	if($(this).find("input[type=checkbox]").prop('checked')== true){
    	    var optionVal = $.trim($(this).find("input[type=checkbox]").val());
    		var optionText = $.trim($(this).text());
    		selectedsallType.push({"val":optionVal , "txt": optionText});
    	}
    });
    for(a=0; a<selectedProd.length;a++){
        listProdId.push(selectedProd[a].val);
        listProdName.push(selectedProd[a].txt);
    }
    for(b=0; b<selectedIndustry.length;b++){
        listIndsId.push(selectedIndustry[b].val);
        listIndsName.push(selectedIndustry[b].txt);
    }
    for(c=0; c<selectedBusinessLoc.length;c++){
        listBusiId.push(selectedBusinessLoc[c].val);
        listBusiName.push(selectedBusinessLoc[c].txt);
    }
    for(d=0; d<selectedsallType.length;d++){
        supProId.push(selectedsallType[d].val);
        supProName.push(selectedsallType[d].txt);
    }
    /* -------------------------------------------------- */
    var allArrays = new Array(listProdName,listIndsName,listBusiName,supProName);
    var allIdArrays = new Array(listProdId,listIndsId,listBusiId,supProId);

    result1=getPermutation(allArrays);
    /* NmArray1=result1; */
    result2=getPermutation(allIdArrays);
    /* Array1=result2; */


    var str = "";
    var str21 = "";
    str += "<div id='combination'><table class='table'><thead><tr><th>SL No</th><th>Products</th><th>Industry</th><th>Business</th><th>Process Type</th></tr></thead><tbody>";
    for(i=0;i<result1.length;i++){
        var a=result1[i];
        var val=a.split(',');
        /* -------------- */
        var B=result2[i];
        var Idval=B.split(',');
        /* -------------- */
		if(i<10){
			str += "<tr class='active01'><td><input type='checkbox' checked/> "+(i+1)+"</td><td><span class='txt01'>"+val[0]+"</span><i class='vall01 none'>"+Idval[0]+"</i></td><td><span class='txt02'>"+val[1]+"</span><i class='vall02 none'>"+Idval[1]+"</i></td><td ><span class='txt03'>"+val[2]+"</span><i class='vall03 none'>"+Idval[2]+"</i></td><td><span class='txt04'>"+val[3]+"</span><i class='vall04 none'>"+Idval[3]+"</i></td></tr>";
		}else{
			str += "<tr><td><input type='checkbox' checked/> "+(i+1)+"</td><td><span class='txt01'>"+val[0]+"</span><i class='vall01 none'>"+Idval[0]+"</i></td><td><span class='txt02'>"+val[1]+"</span><i class='vall02 none'>"+Idval[1]+"</i></td><td ><span class='txt03'>"+val[2]+"</span><i class='vall03 none'>"+Idval[2]+"</i></td><td><span class='txt04'>"+val[3]+"</span><i class='vall04 none'>"+Idval[3]+"</i></td></tr>";
		}

    }
    str += "</tbody></table>";
	if(result1.length >10){
		str += "<div class='next-prev'><a href='#' id='prv' class='pull-left' onclick='prev(\"#combination .table\",\"10\")' title='Previous'><i class='fa fa-chevron-circle-left fa-2'' aria-hidden='true''></i></a><a href='#' id='nxt' class='pull-right' onclick='next(\"#combination .table\",\"10\")' title='Next'><i class='fa fa-chevron-circle-right fa-2' aria-hidden='true'></i></a></div>";
	}
    str += "</div>";

    if (str.substr(0,1) == "||") {
        str = str.substring(1);
    }
    var len = str.length;
    if (str.substr(len-1,1) == "||") {
        str = str.substring(0,len-1);
    }
    $("#combination").remove()
    $("#"+modal_type+" .modal-body.section-five").append(str);

	var totalRow =(($('#combination .table tr').length)/10);
	var paginationBtn ="";
	if(result1.length >10){
		if(totalRow<10){
			for(i=0; i<totalRow; i++){
				if(i == 0){
					paginationBtn += "<input type='button' class='paginationBtn btn active' id='pagi"+(i*10)+"' onclick='page("+(i*10)+",\"#combination .table\",\"10\")' value='"+(i+1)+"'/>&nbsp;&nbsp;&nbsp;";
				}else{
					paginationBtn += "<input type='button' class='paginationBtn btn' id='pagi"+(i*10)+"' onclick='page("+(i*10)+",\"#combination .table\",\"10\")' value='"+(i+1)+"'/>&nbsp;&nbsp;&nbsp;";
				}

			}
		}
	}
	$("#"+modal_type+" .modal-body.section-five").find(".pagingBar").remove();
	$("#"+modal_type+" .modal-body.section-five").append("<center class='pagingBar'>"+paginationBtn.trim()+"<div style='clear:both'></div></center>");
    $(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
    $(".section-five").show();
	index=10;
	lastindex=0;

}
/* ---------------------------------------------------------------------------------------------------------------------------------------------- */
/*---------------pagination functions ---------*/
function page(pageNo, table, number){

	lastindex = (pageNo + parseInt(number));
	$(table+" tbody tr").removeClass('active01');
	for(i=pageNo+1; i<lastindex+1; i++){
		$(table+" tr").eq(i).addClass('active01');
	}
	index=lastindex;
	if(lastindex >= $(table+" tbody tr").length){
		$("#nxt").hide();
		$("#prv").show();
	}else if(lastindex <= parseInt(number)){
		$("#nxt").show();
		$("#prv").hide();
	}else{
		$("#prv,#nxt").show();
	}
	$(".paginationBtn").removeClass('active');
	$("#pagi"+pageNo).addClass('active').focus();

}

/*------------------------*/
var index=10;
var lastindex=0;
function prev(table, number){
	lastindex = (index - parseInt(number));

	$(table+" tbody tr").removeClass('active01');
	for(i=(lastindex - parseInt(number)); i<(index - parseInt(number)); i++){
		$(table+" tbody tr").eq(i).addClass('active01');
	}
	index=lastindex;
	if(lastindex <= parseInt(number)){
		$("#prv").hide();
		$("#nxt").show();
	}else{
		$("#prv,#nxt").show();
	}
	/*------------active Page Number sylye---------------------*/
	var activePageNumber = lastindex - parseInt(number);
	$(".paginationBtn").removeClass('active');
	$("#pagi"+activePageNumber).addClass('active').focus();
}

function next(table, number){
	lastindex = (index + parseInt(number));

	$(table+" tbody tr").removeClass('active01');
	for(i=index; i<lastindex; i++){
		$(table+" tbody tr").eq(i).addClass('active01');
	}
	index=lastindex;
	if(lastindex >= $(table+" tbody tr").length){
		$("#nxt").hide();
		$("#prv").show();
	}else{
		$("#prv,#nxt").show();
	}
	/*------------active Page Number sylye---------------------*/
	var activePageNumber = lastindex - parseInt(number);
	$(".paginationBtn").removeClass('active');
	$("#pagi"+activePageNumber).addClass('active').focus();
}

function arr(arr1){
  var m = {}, newarr = []
  for (var i=0; i<arr1.length; i++) {
    var v = arr1[i];
    if (!m[v]) {
      newarr.push(v);
      m[v]=true;
    }
  }
  return (newarr);
}


function get_nonduplicate(Array1,Array2){
    var Arr1=[],Arr2=[];
    var len=Array1.length;
    var len1=Array2.length;
    if(len>=len1){
        Arr1=Array1;
        Arr2=Array2;
    }else if(len1>=len){
        Arr1=Array2;
        Arr2=Array1;
    }

    for(var i = 0; i<Arr2.length; i++){
	var arrlen = Arr1.length;
    	for (var j = 0; j<arrlen; j++){
    		if (Arr2[i] == Arr1[j]){
    			Arr1 = Arr1.slice(0, j).concat(Arr1.slice(j+1, arrlen));
    		}/* if close */
    	}/* for close */
    }
    return Arr1;
}

function form_CombinationTable(fn_type,modal_type,result1,result2,Array1,NmArray1){

    var str = "";
    var str21 = "";
    if(fn_type=="else_part"){

          str += "<div id='combination'><table class='table'><thead><tr><th>SL No</th><th>Products</th><th>Industry</th><th>Business</th><th>Process Type</th></tr></thead><tbody>";
          for(i=0;i<Array1.length;i++){
              var a=NmArray1[i]; /* name array */
              if(a != undefined){
                     var val=a.split(',');
                     var B=Array1[i]; /*  id array */
                     var Idval=B.split(',');
                     for(var x=0; x<selectedsallType.length;x++){
                        if(selectedsallType[x].val == Idval[3]){
                          var proname= selectedsallType[x].txt;
                        }
                     }
                     if(i<10){
                    	str += "<tr class='active01 alert alert-danger'><td><input type='checkbox' checked/> "+(i+1)+"</td><td><span class='txt01'>"+val[0]+"</span><i class='vall01 none'>"+Idval[0]+"</i></td><td><span class='txt02'>"+val[1]+"</span><i class='vall02 none'>"+Idval[1]+"</i></td><td ><span class='txt03'>"+val[2]+"</span><i class='vall03 none'>"+Idval[2]+"</i></td><td><span class='txt04'>"+proname+"</span><i class='vall04 none'>"+Idval[3]+"</i></td></tr>";
                     }else{
                              str += "<tr class='alert alert-danger'><td><input type='checkbox' checked/> "+(i+1)+"</td><td><span class='txt01'>"+val[0]+"</span><i class='vall01 none'>"+Idval[0]+"</i></td><td><span class='txt02'>"+val[1]+"</span><i class='vall02 none'>"+Idval[1]+"</i></td><td ><span class='txt03'>"+val[2]+"</span><i class='vall03 none'>"+Idval[2]+"</i></td><td><span class='txt04'>"+proname+"</span><i class='vall04 none'>"+Idval[3]+"</i></td></tr>";
                     }
              }
              /* -------------- */


              /* -------------- */

          }
          for(i1=0;i1<result2.length;i1++){
                console.log(selectedsallType);

              var a=result1[i1]; /* name array */
              if(a != undefined){
                  var val=a.split(',');
                  /* -------------- */
                  var B=result2[i1];  /* id array */
                  var Idval=B.split(',');
                  for(var x=0; x<selectedsallType.length;x++){
                        if(selectedsallType[x].val == Idval[3]){
                          var proname= selectedsallType[x].txt;
                        }
                  }
                  /* -------------- */
                  if(i<10){
                  	str += "<tr class='active01'><td><input type='checkbox' checked/> "+(i+1)+"</td><td><span class='txt01'>"+val[0]+"</span><i class='vall01 none'>"+Idval[0]+"</i></td><td><span class='txt02'>"+val[1]+"</span><i class='vall02 none'>"+Idval[1]+"</i></td><td ><span class='txt03'>"+val[2]+"</span><i class='vall03 none'>"+Idval[2]+"</i></td><td><span class='txt04'>"+proname+"</span><i class='vall04 none'>"+Idval[3]+"</i></td></tr>";
                  }else{
                  	str += "<tr><td><input type='checkbox' checked/> "+(i+1)+"</td><td><span class='txt01'>"+val[0]+"</span><i class='vall01 none'>"+Idval[0]+"</i></td><td><span class='txt02'>"+val[1]+"</span><i class='vall02 none'>"+Idval[1]+"</i></td><td ><span class='txt03'>"+val[2]+"</span><i class='vall03 none'>"+Idval[2]+"</i></td><td><span class='txt04'>"+proname+"</span><i class='vall04 none'>"+Idval[3]+"</i></td></tr>";
                  }
                  i++;
              }

          }
          str += "</tbody></table>";
          if(result2.length > 10){
            str += "<div class='next-prev'><a href='#' id='prv' class='pull-left' onclick='prev(\"#combination .table\",\"10\")' title='Previous'><i class='fa fa-chevron-circle-left fa-2'' aria-hidden='true''></i></a><a href='#' id='nxt' class='pull-right' onclick='next(\"#combination .table\",\"10\")' title='Next'><i class='fa fa-chevron-circle-right fa-2' aria-hidden='true'></i></a></div>";
          }
          str += "</div>";

          if (str.substr(0,1) == "||") {
              str = str.substring(1);
          }
          var len = str.length;
          if (str.substr(len-1,1) == "||") {
              str = str.substring(0,len-1);
          }
          $("#rmdup").show();
          $("#rmdupE").show();
          loaderHide();
    }else if(fn_type=="if_part"){

          str += "<div id='combination'><table class='table'><thead><tr><th>SL No</th><th>Products</th><th>Industry</th><th>Business</th><th>Process Type</th></tr></thead><tbody>";
          for(i=0;i<Array1.length;i++){

                      var a=NmArray1[i]; /* name array */
            if(a != undefined){
                      var val=a.split(',');
                      /* -------------- */
                      var B=Array1[i];  /* id array */
                      var Idval=B.split(',');
                      for(var x=0; x<selectedsallType.length;x++){
                        if(selectedsallType[x].val == Idval[3]){
                          var proname= selectedsallType[x].txt;
                        }
                      }
                      /* -------------- */
              		if(i<10){
              			str += "<tr class='active01'><td><input type='checkbox' checked/> "+(i+1)+"</td><td><span class='txt01'>"+val[0]+"</span><i class='vall01 none'>"+Idval[0]+"</i></td><td><span class='txt02'>"+val[1]+"</span><i class='vall02 none'>"+Idval[1]+"</i></td><td ><span class='txt03'>"+val[2]+"</span><i class='vall03 none'>"+Idval[2]+"</i></td><td><span class='txt04'>"+proname+"</span><i class='vall04 none'>"+Idval[3]+"</i></td></tr>";
              		}else{
              			str += "<tr><td><input type='checkbox' checked/> "+(i+1)+"</td><td><span class='txt01'>"+val[0]+"</span><i class='vall01 none'>"+Idval[0]+"</i></td><td><span class='txt02'>"+val[1]+"</span><i class='vall02 none'>"+Idval[1]+"</i></td><td ><span class='txt03'>"+val[2]+"</span><i class='vall03 none'>"+Idval[2]+"</i></td><td><span class='txt04'>"+proname+"</span><i class='vall04 none'>"+Idval[3]+"</i></td></tr>";
              		}
              }
          }
          str += "</tbody></table>";
          if(Array1.length > 10){
                		str += "<div class='next-prev'><a href='#' id='prv' class='pull-left' onclick='prev(\"#combination .table\",\"10\")' title='Previous'><i class='fa fa-chevron-circle-left fa-2'' aria-hidden='true''></i></a><a href='#' id='nxt' class='pull-right' onclick='next(\"#combination .table\",\"10\")' title='Next'><i class='fa fa-chevron-circle-right fa-2' aria-hidden='true'></i></a></div>";
          }
          str += "</div>";

          if (str.substr(0,1) == "||") {
              str = str.substring(1);
          }
          var len = str.length;
          if (str.substr(len-1,1) == "||") {
              str = str.substring(0,len-1);
          }
          result2=Array1;
          $("#rmdup").hide();
          $("#rmdupE").hide();
          loaderHide();
    }
    $("#combination").remove();
    $("#"+modal_type+" .modal-body.section-five").append(str);

    var totalRow =(($('#combination .table tr').length)/10);
    var paginationBtn ="";
    if(result2.length > 10){
      if(totalRow<10){
        for(i=0; i<=totalRow; i++){
          if(i == 0){
                paginationBtn += "<input type='button' class='paginationBtn btn active' id='pagi"+(i*10)+"' onclick='page("+(i*10)+",\"#combination .table\",\"10\")' value='"+(i+1)+"'/>&nbsp;&nbsp;&nbsp;";
          }else{
                paginationBtn += "<input type='button' class='paginationBtn btn' id='pagi"+(i*10)+"' onclick='page("+(i*10)+",\"#combination .table\",\"10\")' value='"+(i+1)+"'/>&nbsp;&nbsp;&nbsp;";
          }
        }
      }
    }
    $("#"+modal_type+" .modal-body.section-five").find(".pagingBar").remove();
    $("#"+modal_type+" .modal-body.section-five").append("<center class='pagingBar'>"+paginationBtn.trim()+"<div style='clear:both'></div></center>");

    index=10;
    lastindex=0;

}

function removeDuplicate(status){
         $("#combination table tr").each(function(){
                                    if($(this).hasClass('alert-danger') && $(this).find("input[type=checkbox]").prop('checked')==true){
                                        $(this).remove();
                                    }
         });
         checkDuplicate(status);
}

function checkDuplicate(status){

    $("#rmdup").hide();
    $("#rmdupE").hide();
    var typeofsave=$("#hid_type").val();
    if(typeofsave == 'addrow'){ status=""; }
    /* add duplicate button function ------------ */
	if(status == 'add' || typeofsave == 'addrow'){
	    loaderShow();
	    addObj={};
		var listCombId="";
        var Array1=[],NmArray1=[],Array2=[],NmArray2=[]; // global array
		$("#combination table tr input[type=checkbox]").each(function(){
			if($(this).prop('checked')== true){
				listCombId += $(this).closest('tr').find('.vall01').text()+','+$(this).closest('tr').find('.vall02').text()+','+$(this).closest('tr').find('.vall03').text()+','+$(this).closest('tr').find('.vall04').text()+':';
                Array1.push($(this).closest('tr').find('.vall01').text()+','+$(this).closest('tr').find('.vall02').text()+','+$(this).closest('tr').find('.vall03').text()+','+$(this).closest('tr').find('.vall04').text());
                NmArray1.push($(this).closest('tr').find('.txt01').text()+','+$(this).closest('tr').find('.txt02').text()+','+$(this).closest('tr').find('.txt03').text()+','+$(this).closest('tr').find('.vall04').text());
			}
		});
        addObj.listCombId=listCombId;
        addObj.hid_parameterid="";

        $.ajax({
                    type : "POST",
                    url : "<?php echo site_url('admin_sup_salescycle_parameterController/checkDuplicate/add'); ?>",
                    dataType : 'json',
                    data : JSON.stringify(addObj),
                    cache : false,
                    success : function(data){
							if(error_handler(data)){
								return;
							}
                            $("#combination table tr").each(function(){
                                    if($(this).hasClass('alert-danger') && $(this).find("input[type=checkbox]").prop('checked')==false){
                                        $(this).remove();
                                    }
                            });
                            if(data==0){
                                    if(Array1.length==0){
                                        loaderHide();
                                        if(status==""){
                                        $("#savebtnE").attr("disabled" ,"disabled");
                                            modal_type="editmodal";
                                        }else{
                                            $("#savebtn").attr("disabled" ,"disabled");
                                            modal_type="addmodal";
                                        }
                                        $(".section-five .alert.alert-info").removeClass("none").addClass("alert-warning");
                                        $(".section-five .alert.alert-warning").find("span").html('<b> No Combinations Found To Proceed </b> ');
                                        return;
                                    }else{
                                        if(status==""){
                                            $("#savebtnE").removeAttr("disabled");
                                            modal_type="editmodal";
                                        }else{
                                            $("#savebtn").removeAttr("disabled");
                                            modal_type="addmodal";
                                        }
                                        $(".section-five .alert.alert-info").removeClass("none").removeClass("alert-warning");
                                        $(".section-five .alert.alert-info").find("span").html('<b> No duplicate combination found. You can proceed to Save </b>');
                                        var result1=result2=[];
                                        form_CombinationTable("if_part",modal_type,result1,result2,Array1,NmArray1);

                                    }

                            }else{
                                $(".section-five .alert.alert-info").removeClass("none").addClass("alert-warning");
                                $(".section-five .alert.alert-warning").find("span").html('<b> Remove/Edit Duplicate combination before Saving </b> ');


                                for(i=0; i<data.length; i++){
                                    /* -- duplicate found array */
                                    Array2.push(data[i].param_product_node+','+data[i].param_industry_node+','+data[i].param_location_node+','+data[i].parameter_for);
                                    NmArray2.push(data[i].productn+' ('+data[i].productp+')'+','+data[i].industryn+' ('+data[i].industryp+')'+','+data[i].locationn+' ('+data[i].locationp+')'+','+data[i].parameter_for);
                                }

                                /* --- remove the duplicate found array from main combination array */
                                result1=get_nonduplicate(NmArray1,NmArray2); //param1=main array, param2= duplicate array
                                result2=get_nonduplicate(Array1,Array2);

                                console.log(result1);
                                if(status==""){
                                    modal_type="editmodal";
                                }else{
                                    modal_type="addmodal";
                                }
                                form_CombinationTable("else_part",modal_type,result1,result2,Array2,NmArray2);

                            }
                    }
        });
	}
    /* edit duplicate button function ------------ */
    if(status == 'edit' ){
        loaderShow();        
	    addObj={};
		var listCombId="";
        var Array1=[],NmArray1=[],Array2=[],NmArray2=[]; // global array
		$("#combination table tr input[type=checkbox]").each(function(){
			if($(this).prop('checked')== true){
				listCombId += $(this).closest('tr').find('.vall01').text()+','+$(this).closest('tr').find('.vall02').text()+','+$(this).closest('tr').find('.vall03').text()+','+$(this).closest('tr').find('.vall04').text()+':';
                Array1.push($(this).closest('tr').find('.vall01').text()+','+$(this).closest('tr').find('.vall02').text()+','+$(this).closest('tr').find('.vall03').text()+','+$(this).closest('tr').find('.vall04').text());
                NmArray1.push($(this).closest('tr').find('.txt01').text()+','+$(this).closest('tr').find('.txt02').text()+','+$(this).closest('tr').find('.txt03').text()+','+$(this).closest('tr').find('.vall04').text());
			}
		});
        addObj.listCombId=listCombId;
        addObj.hid_parameterid=$("#hid_parameterid").val();
        $.ajax({
                    type : "POST",
                    url : "<?php echo site_url('admin_sup_salescycle_parameterController/checkDuplicate/update'); ?>",
                    dataType : 'json',
                    data : JSON.stringify(addObj),
                    cache : false,
                    success : function(data){
							if(error_handler(data)){
								return;
							}
                            $("#combination table tr").each(function(){
                                    if($(this).hasClass('alert-danger') && $(this).find("input[type=checkbox]").prop('checked')==false){
                                        $(this).remove();
                                    }
                            	});
                            if(data==0){
                                 if(Array1.length==0){
                                        loaderHide();
                                        $("#savebtnE").attr("disabled" ,"disabled");
                                        $(".section-five .alert.alert-info").removeClass("none").addClass("alert-warning");
                                        $(".section-five .alert.alert-warning").find("span").html('<b> No Combinations Found To Proceed </b>');
                                        return;
                                 }else{
                                        $("#savebtnE").removeAttr("disabled");
                                        $(".section-five .alert.alert-info").removeClass("none").removeClass("alert-warning");
                                        $(".section-five .alert.alert-info").find("span").html('<b> No duplicate combination found. You can proceed to Save </b>');

                                        modal_type="editmodal";
                                        var result1=result2=[];
                                        form_CombinationTable("if_part",modal_type,result1,result2,Array1,NmArray1);
                                 }

                            }else{
                                $(".section-five .alert.alert-info").removeClass("none").addClass("alert-warning");
                                $(".section-five .alert.alert-warning").find("span").html('<b> Remove/Edit Duplicate combination before Saving </b> ');

                                for(i=0; i<data.length; i++){
                                    /* -- duplicate found array */
                                    Array2.push(data[i].param_product_node+','+data[i].param_industry_node+','+data[i].param_location_node+','+data[i].parameter_for);
                                    NmArray2.push(data[i].productn+' ('+data[i].productp+')'+','+data[i].industryn+' ('+data[i].industryp+')'+','+data[i].locationn+' ('+data[i].locationp+')'+','+data[i].parameter_for);
                                }

                                /* --- remove the duplicate found array from main combination array */
                                result1=get_nonduplicate(NmArray1,NmArray2); //param1=main array, param2= duplicate array
                                result2=get_nonduplicate(Array1,Array2);

                                console.log(result1);
                                modal_type="editmodal";
                                form_CombinationTable("else_part",modal_type,result1,result2,Array2,NmArray2);


                            }
                    }
        });
	}
}
/* --------------------------------------------------- save function ------------------------------------------------------------------------------ */
function add_cycle(){
    loaderShow();
    /* ----------- products --------------- */
    var myArrayID_pro =[];
    $("#products input[type=radio]").each(function(){
                if($(this).prop('checked') == true){
                    myArrayID_pro.push($.trim($(this).val()));
                }
    });
    if(myArrayID_pro.length  == 0){
        $("#products").siblings(".error-alert").text("Please Choose the Products for the Support Cycle");
        loaderHide();
        return;
    }else{
         $("#products").siblings(".error-alert").text("");
    }
    /* ---------------Industry validation ---------------- */
	var industryArray =[];
	$("#industry input[type=radio]").each(function(){
		if($(this).prop('checked') == true){
			industryArray.push($.trim($(this).val()));
		}
	});
	if(industryArray.length  == 0){
		$("#industry").siblings(".error-alert").text("Please select Location.");
        loaderHide()
		return;
	}else{
		 $("#industry").siblings(".error-alert").text("");
	}
    /* ----------------Business location validation --------------- */
	var BusinessLocationArray =[];
	$("#business_loc input[type=radio]").each(function(){
		if($(this).prop('checked') == true){
			BusinessLocationArray.push($.trim($(this).val()));
		}
	});
	if(BusinessLocationArray.length  == 0){
		$("#business_loc").siblings(".error-alert").text("Please select Location.");
        loaderHide()
		return;
	}else{
		 $("#business_loc").siblings(".error-alert").text("");
	}

    var addObj={};
    addObj.salescyle = $.trim($("#salescyle").val());
    addObj.ind_parent = industryArray[0];
    addObj.bus_parent = BusinessLocationArray[0];
    addObj.pro_parent = myArrayID_pro[0];
    var listCombId="";
      $("#combination table tr input[type=checkbox]").each(function(){
      	if($(this).prop('checked')== true){
      		listCombId += $(this).closest('tr').find('.vall01').text()+','+$(this).closest('tr').find('.vall02').text()+','+$(this).closest('tr').find('.vall03').text()+','+$(this).closest('tr').find('.vall04').text()+':';

      	}
      });
      addObj.listCombId=listCombId;
      console.log(addObj)
      $.ajax({
                type : "POST",
                url : "<?php echo site_url('admin_sup_salescycle_parameterController/post_data'); ?>",
                dataType : 'json',
                data : JSON.stringify(addObj),
                cache : false,
                success : function(data){
					cancel();
					loadpage();
                    $("#rmdup").hide();
                    $("#rmdupE").hide();
					if(error_handler(data)){
						return;
					}
                }
      });
}

/* -----------------------------------------------------  edit function  ------------------------------------------------------------------------------ */

function saveedit(){
    //loaderShow();
    /* ----------- products --------------- */
    var myArrayID_pro =[];
    $("#productsE input[type=radio]").each(function(){
                if($(this).prop('checked') == true){
                    myArrayID_pro.push($.trim($(this).val()));
                }
    });
    if(myArrayID_pro.length  == 0){
        $("#productsE").siblings(".error-alert").text("Please Choose the Products for the Support Cycle");
        loaderHide();
        return;
    }else{
         $("#productsE").siblings(".error-alert").text("");
    }
    /* ---------------Industry validation ---------------- */
	var industryArray =[];
	$("#industryE input[type=radio]").each(function(){
		if($(this).prop('checked') == true){
			industryArray.push($.trim($(this).val()));
		}
	});
	if(industryArray.length  == 0){
		$("#industryE").siblings(".error-alert").text("Please select Location.");
        loaderHide();
		return;
	}else{
		 $("#industryE").siblings(".error-alert").text("");
	}
    /* ----------------Business location validation --------------- */
	var BusinessLocationArray =[];
	$("#business_locE input[type=radio]").each(function(){
		if($(this).prop('checked') == true){
			BusinessLocationArray.push($.trim($(this).val()));
		}
	});
    /* ----------------Business location validation --------------- */
	var myArrayID_pro =[];
	$("#productsE input[type=radio]").each(function(){
		if($(this).prop('checked') == true){
			myArrayID_pro.push($.trim($(this).val()));
		}
	});
	if(BusinessLocationArray.length  == 0){
		$("#business_locE").siblings(".error-alert").text("Please select Location.");
        loaderHide();
		return;
	}else{
		 $("#business_locE").siblings(".error-alert").text("");
	}

    addObj.salescyle = $.trim($("#edit_cycle").val());
    addObj.ind_parent = industryArray[0];
    addObj.bus_parent = BusinessLocationArray[0];
    addObj.pro_parent = myArrayID_pro[0];
    addObj.toggleid = 1;
    addObj.hid_parameterid=$('#hid_parameterid').val();
    var listCombId="";
      $("#combination table tr input[type=checkbox]").each(function(){
      	if($(this).prop('checked')== true){
      		listCombId += $(this).closest('tr').find('.vall01').text()+','+$(this).closest('tr').find('.vall02').text()+','+$(this).closest('tr').find('.vall03').text()+','+$(this).closest('tr').find('.vall04').text()+':';

      	}
      });
    addObj.listCombId=listCombId;
    var typeofsave=$("#hid_type").val();

    if(typeofsave =='editrow'){
                 $.ajax({
                   type : "POST",
                   url : "<?php echo site_url('admin_sup_salescycle_parameterController/update_data'); ?>",
                   dataType : 'json',
                   data : JSON.stringify(addObj),
                   cache : false,
                   success : function(data){
                        cancel();
			            loadpage();
                        $("#rmdup").hide();
                        $("#rmdupE").hide();
						if(error_handler(data)){
								return;
							}
                   }
                });
    }else if(typeofsave == 'addrow'){
                $.ajax({
                        type : "POST",
                        url : "<?php echo site_url('admin_sup_salescycle_parameterController/post_data'); ?>",
                        dataType : 'json',
                        data : JSON.stringify(addObj),
                        cache : false,
                        success : function(data){
                                cancel();
			                    loadpage();
                                $("#rmdup").hide();
                                $("#rmdupE").hide();
								if(error_handler(data)){
									return;
								}
                        }
                });
    }

}
/*  --------------- back button one --------------------- */
function sectionTwoB(status){
	if(status == 'edit'){
		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
		$(".section-one").show();
	}
	if(status == 'add'){
		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
		$(".section-one").show();
	}
}

/* ------------------ back button two --------------------------- */
function sectionThreeB(status){
	if(status == 'edit'){
		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
		$(".section-two").show();
	}
	if(status == 'add'){
		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
		$(".section-two").show();
	}
}

/* --------------- back button three ------------ */
function sectionFourB(status){
	if(status == 'edit'){
		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
		$(".section-three").show();
	}
	if(status == 'add'){
		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
		$(".section-three").show();
	}
}
/* --------------- back button four ------------ */
function sectionFiveB(status){
	if(status == 'edit'){
		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
		$(".section-four").show();
        $("#savebtnE").attr("disabled" ,"disabled");
        $(".section-five .alert.alert-info").addClass("none");
        $(".section-five .alert.alert-info").find("span").html('');
	}
	if(status == 'add'){
		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
		$(".section-four").show();
        $("#savebtn").attr("disabled" ,"disabled");
         $(".section-five .alert.alert-info").addClass("none");
         $(".section-five .alert.alert-info").find("span").html('');
	}
    $("#rmdup").hide();
    $("#rmdupE").hide();
}


function cancel(){
		$('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first').val());
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
		$(".section-one, .section-two, .section-three, .section-four, .section-five").hide();
		$(".modal .section-one").show();
        $('.displayChecked ol li').remove();
        $("#product ul").html("");
        $(".error-alert").text("");
        $("#savebtn").attr("disabled" ,"disabled");
        $("#savebtnE").attr("disabled" ,"disabled");
        $(".section-five .alert.alert-info").addClass("none");
        $(".section-five .alert.alert-info").find("span").html('');
        $("#rmdup").hide();
        $("#rmdupE").hide();
}
/* ---------------------------------------------------------------------- */

</script>
</head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="loader">
			<center><h1 id="loader_txt"></h1></center>
		</div>
        <!---------------------------------------------------------
        Header bar
        ------------------------------------------------------------>
        <?php $this->load->view('demo');  ?>
        <!---------------------------------------------------------
        side nav
        ------------------------------------------------------------>

        <?php $this->load->view('admin_sidenav'); ?>
        <div class="content-wrapper body-content">
            <div class="col-lg-12 column">
			<div class="row header1">
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Support Cycle Parameters"/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Support Cycle Parameters</h2>
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<!--<a href="#modal_upload" data-toggle="modal" class="addExcel" >
								<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a>-->
							<a href="#addmodal" class="addPlus" data-toggle="modal" onclick="compose()" id="compose">
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
                 <input type="hidden" id="hid_tgbit" name="hid_tgbit"  />
                 <input type="hidden" id="hid_parameterid" name="hid_parameterid"  />
                 <input type="hidden" id="hid_cycleid" name="hid_cycleid"  />
                 <input type="hidden" id="hid_param" name="hid_param"  />
                 <input type="hidden" id="hid_type" name="hid_type"  />
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th class="table_header" width="5%">SL No</th>
                                <th class="table_header" width="25%">Products</th>
                                <th class="table_header" width="20%">Industry</th>
                                <th class="table_header" width="20%" >Business Location</th>
                                <th class="table_header" width="10%">Type</th>
                                <th class="table_header" width="20%">Support Cycle</th>
                                <th class="table_header" width="1%"></th>
                                <th class="table_header" width="1%"></th>
                                <th class="table_header" width="1%"></th>
							</tr>
						</thead>
						<tbody id="tablebody">
						</tbody>
					</table>
				</div>
            </div>
            <div id="editmodal" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="editpopup" class="form" action="#" method="post" name="adminClient">
                            <div class="modal-header">
                                 <span class="close" onclick="cancel()">x</span>
                                 <h4 class="modal-title">Edit Support Cycle Parameters</h4>
                            </div>
							<div class="modal-body section-one">
                                 <div class="row">
                                    <div class="pull-left" >

										<div id="productsE" class="tree-view"></div>
									</div>
									<div class="pull-right">
										<div class="displayChecked" id="display_prodE">
											<ol type="1"></ol>
										</div>
									</div>
								</div>
                                <div class="row">
									<center><span class="error-alert product-list"></span></center>
								</div>
							</div>
							<div class="modal-body section-two">
								<div class="row">
                                    <div class="pull-left">

										<div id="industryE" class="tree-view" ></div>
									</div>
									<div class="pull-right">
										<div class="displayChecked" id="display_locE">
											<ol type="1"></ol>
										</div>
									</div>
								</div>
                                <div class="row">
									<center><span class="error-alert industry-list"></span></center>
								</div>
							</div>
							<div class="modal-body section-three">
								<div class="row">
                                    <div class="pull-left">

										<div id="business_locE" class="tree-view" ></div>
									</div>
									<div class="pull-right">
										<div class="displayChecked" id="display_CategoryE">
											<ol type="1"></ol>
										</div>
									</div>
								</div>
                                <div class="row">
									<center><span class="error-alert business-list"></span></center>
								</div>
							</div>
							<div class="modal-body section-four">
								<div class="row">
								  <div class="col-md-3">
									  <label for="edit_cycle">Support Cycle*</label>
								  </div>
								  <div class="col-md-9">
										<select class="form-control" id="edit_cycle">
										</select>
										<span class="error-alert"></span>
								  </div>
								</div>
									<div class="row ">
      								  <div class="col-md-3">
									   Support Process*
      								  </div>
      								  <div class="col-md-6 " id="support_processE">

      								  </div>

									  <center id="seltypeErchkE"><span class="error-alert"></span></center>
      								</div>
							</div>
							<div class="modal-body section-five">
                            	<div class="row ">

                                  <p><b>Cycle Name: </b><span id='cycleNmeVE'></span> </p>

                                </div>
							</div>
							<div class="modal-footer section-one">
                                <input type="button" class="btn" onclick="sectionOneP('edit')" value="Proceed">
                                <input type="button" class="btn" onclick="cancel()" value="Cancel" >
                            </div>
							<div class="modal-footer section-two">
                                <input type="button" class="btn" onclick="sectionTwoB('edit')" value="Back">
                                <input type="button" class="btn" onclick="sectionTwoP('edit')" value="Proceed">
                                <input type="button" class="btn" onclick="cancel()" value="Cancel" >
                            </div>
							<div class="modal-footer section-three">
                                <input type="button" class="btn" onclick="sectionThreeB('edit')" value="Back">
                                <input type="button" class="btn" onclick="sectionThreeP('edit')" value="Proceed">
                                <input type="button" class="btn" onclick="cancel()" value="Cancel" >
                            </div>
                            <div class="modal-footer section-four">
                                <input type="button" class="btn" onclick="sectionFourB('edit')" value="Back">
                                <input type="button" class="btn" onclick="sectionFourP('edit')"  value="Proceed"/>
								<input type="button" class="btn" onclick="cancel()" value="Cancel"/>
							</div>
							<div class="modal-footer section-five">
                                <div class="col-md-12 alert alert-info text-center none">
                                      <i><i class="fa fa-info-circle" aria-hidden="true"></i> <span></span></i>
                                </div>
                                <input type="button" class="btn pull-left" onclick="checkDuplicate('edit')" value="Check Duplicate">
                                <input type="button" class="btn pull-left" id="rmdupE" style="display: none" onclick="removeDuplicate('edit')" value="Remove Duplicate">
                                <input type="button" class="btn" onclick="sectionFiveB('edit')" value="Back">
                                <input type="button" class="btn" id="savebtnE" onclick="saveedit()"  value="Save" disabled/>
								<input type="button" class="btn" onclick="cancel()" value="Cancel"/>
							</div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="addmodal" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="addpopup" class="form" action="#" method="post" name="adminClient">
                            <div class="modal-header">
                                 <span class="close" onclick="cancel()">x</span>
                                 <h4 class="modal-title">Add Support Cycle Parameters</h4>
                            </div>
                            <div class="modal-body section-one">
                                <div class="row">
                                    <div class="col-md-12 alert alert-info">
										<i><i class="fa fa-info-circle" aria-hidden="true"></i> <label for="products">Please choose a Product for the Support Cycle *</label></i>
									</div>
									<div class="pull-left" >
										<div id="products" class="tree-view"></div>
									</div>
									<div class="pull-right">
										<div class="displayChecked" id="display_prod">
											<ol type="1"></ol>
										</div>
									</div>
								</div>
                                <div class="row">
									<center><span class="error-alert product-list"></span></center>
								</div>
							</div>
							<div class="modal-body section-two">
								<div class="row">
                                    <div class="col-md-12 alert alert-info">
										<i><i class="fa fa-info-circle" aria-hidden="true"></i> <label for="industry">Please choose an Industry for the Support Cycle *</label></i>
									</div>
									<div class="pull-left">
										<div id="industry" class="tree-view" ></div>
									</div>
									<div class="pull-right">
										<div class="displayChecked" id="display_loc">
											<ol type="1"></ol>
										</div>
									</div>
								</div>
                                <div class="row">
									<center><span class="error-alert industry-list"></span></center>
								</div>
							</div>
                            <div class="modal-body section-three">
								<div class="row">
                                    <div class="col-md-12 alert alert-info">
										<i><i class="fa fa-info-circle" aria-hidden="true"></i><label for="business_loc">Please choose a Business Location for the Support Cycle *</label></i>
									</div>
									<div class="pull-left">

										<div id="business_loc" class="tree-view" ></div>
									</div>
									<div class="pull-right">
										<div class="displayChecked" id="display_Category">
											<ol type="1"></ol>
										</div>
									</div>
								</div>
                                <div class="row">
									<center><span class="error-alert business-list"></span></center>
								</div>
							</div>
							<div class="modal-body section-four">
								<div class="row">
                                    <div class="col-md-12 alert alert-info">
										<i><i class="fa fa-info-circle" aria-hidden="true"></i> Please choose Cycle and Process Type for the Support Cycle</i>
									</div>
    								<div class="col-md-3">
    									  <label for="salescyle">Support Cycle*</label>
    								</div>
								    <div class="col-md-9">
										<select class="form-control" id="salescyle">
										</select>
										<span class="error-alert"></span>
								    </div>
								</div>
								<div class="row ">
								    <div class="col-md-3">
								    Support Process*
								    </div>
                                    <div class="col-md-6 " id="support_process">

      								</div>
								    <!--<div class="col-md-3 ">
										<input  type="checkbox" id="sales_newsell" value="new_sell"/>
										<label for="sales_newsell"> New Sell</label>
								    </div>
								    <div class="col-md-3 ">
										<input type="checkbox" id="sales_upsell" value="up_sell"/>
										<label for="sales_upsell"> Up Sell</label>
								    </div>
                                    <div class="col-md-3 ">
										<input type="checkbox" id="sales_crosssell" value="cross_sell"/>
										<label for="sales_crosssell"> Cross Sell</label>
								    </div>-->
								    <center id="seltypeErchk"><span class="error-alert"></span></center>
								</div>
							</div>
							<div class="modal-body section-five">
                            	<div class="row ">

                                  <p><b>Cycle Name: </b><span id='cycleNmeV'></span> </p>

                                </div>
							</div>
							<div class="modal-footer section-one">
                                <input type="button" class="btn" onclick="sectionOneP('add')" value="Proceed">
                                <input type="button" class="btn" onclick="cancel()" value="Cancel" >
                            </div>
							<div class="modal-footer section-two">
                                <input type="button" class="btn" onclick="sectionTwoB('add')" value="Back">
                                <input type="button" class="btn" onclick="sectionTwoP('add')" value="Proceed">
                                <input type="button" class="btn" onclick="cancel()" value="Cancel" >
                            </div>
							<div class="modal-footer section-three">
                                <input type="button" class="btn" onclick="sectionThreeB('add')" value="Back">
                                <input type="button" class="btn" onclick="sectionThreeP('add')" value="Proceed">
                                <input type="button" class="btn" onclick="cancel()" value="Cancel" >
                            </div>
                            <div class="modal-footer section-four">
                                <input type="button" class="btn" onclick="sectionFourB('add')" value="Back">
                                <input type="button" class="btn" onclick="sectionFourP('add')"  value="Proceed"/>
								<input type="button" class="btn" onclick="cancel()" value="Cancel"/>
							</div>
							<div class="modal-footer section-five">
                                <div class="col-md-12 alert alert-info none">
                                      <i><i class="fa fa-info-circle" aria-hidden="true"></i> <span></span></i>
                                </div>
                                <input type="button" class="btn pull-left" onclick="checkDuplicate('add')" value="Check Duplicate">
                                <input type="button" class="btn pull-left" id="rmdup" style="display: none" onclick="removeDuplicate('add')" value="Remove Duplicate">
                                <input type="button" class="btn" onclick="sectionFiveB('add')" value="Back">
                                <input type="button" class="btn" id="savebtn" onclick="add_cycle()"  value="Save" disabled/>
								<input type="button" class="btn" onclick="cancel()" value="Cancel"/>
							</div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <?php $this->load->view('footer'); ?>

    </body>
</html>