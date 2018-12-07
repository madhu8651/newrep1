<!DOCTYPE html>
<html lang="en">
	<head>
	<?php $this->load->view('scriptfiles'); ?>
	<script>
		var base_url = '<?php echo base_url(); ?>';
	</script>
	<style>
		#addmodal .alert.alert-info{
			color: #31708f;
			background-color: #23527c !important;
			border-color: #bce8f1;
			padding:1px 5px;
		}
        .multiselect_sell,
        .multiselect_sellE{
			height: 80px;
			overflow: auto;
			border: 1px solid #ccc;
			border-radius: 5px;
		}


	</style>
	<script>

	/*------------------------*/
	function cancel(){
		$('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first').val());
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
		$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
		$(".modal .section1").show();
		$('.displayChecked ol li').addClass('hide');
		$('.error-alert').text('');
        loadpage();
		$("#currency_value_listE").html("");
		 $('.chkAllLabel').remove();
	}

$(document).ready(function(){
     /* code for sandbox */
    var url1= window.location.href;
    var fileNameIndex1 = url1.lastIndexOf("/") + 1;
    var filename1 = url1.substr(fileNameIndex1);
    var fileA = filename1.split('#');
    sandbox(fileA[0]);
    loadpage();

});

function loadall(){
    /* code for sandbox */
    var url1= window.location.href;
    var fileNameIndex1 = url1.lastIndexOf("/") + 1;
    var filename1 = url1.substr(fileNameIndex1);
    sandbox(filename1);
    loadpage();

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
						$li.append($("<div class='PClass'><div class='dash-left'><label class='glyphicon' id='"+item.id+"'></label>---</div><label class='"+item.id+"'> <input name='"+item.nameAttr+"' type='radio' value='"+item.id+"' id='id"+item.id+"'  checked><b>  " + item.name + "</b></label></div>"));
					}else{
						$li.append($("<div class='PClass'><div class='dash-left'><label class='glyphicon' id='"+item.id+"'></label>---</div><label class='"+item.id+"'> <input  name='"+item.nameAttr+"' type='radio' value='"+item.id+"' id='id"+item.id+"'><b>  " + item.name + "</b></label></div>"));
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
    /* ---------------------------------------------------------------------------- */
    /*----------------------tree function ------------------------------*/

function edit_tree(data, container, saveLastNode){
    $("#"+container).html("");
	var oflocArray = convert(data);
	var $ul = $('<ul class="mytree"></ul>');
	getList(oflocArray, $ul);
	$ul.appendTo("#"+container);
	var display_list=[];

	$("#"+container+" input[type=radio]").each(function(){
		var rightChkList = $(this).closest(".row").find(".displayChecked").attr('id');
		if($(this).closest('li').children('ul').length > 0){

			$(this).closest('label').closest('div').find('.glyphicon').addClass('glyphicon-minus-sign');
		}else{
			$(this).closest("label").css({'text-decoration':'underline','font-style':'italic'}).addClass('lastnode');
			$(this).closest("label").find('input[type=radio]').remove();
		}

		$(this).change(function(){
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
			//if($(this).attr("name") == "Editprod" || $(this).attr("name") == "Editloc" || $(this).attr("name") == "Addloc" || $(this).attr("name") == "Addprod" || $(this).attr("name") == "Addbusiness" || $(this).attr("name") == "Editbuss"){
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
							if(saveLastNode[t].product_id == aa){
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
			/*}else{
				for(i=0; i< display_list.length; i++){
					html +="<li class='"+display_list[i].value+"'>"+display_list[i].name+"<b> ("+display_list[i].pname+")</b></li>";
				}
			}*/
			$(this).closest(".row").find('ol').html(html);
			var that = $(this).closest(".row").find("ol").find("li input[type=checkbox]");
			that.each(function(){
				that.change(function(){
					if(that.is(":checked")){
						that.closest(".displayChecked").find(".selectAll").prop("checked", false)
					}
				})
			});
		})
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
}

	 /* =-------------------------------------------------------------------------------------------------------------------------------------- */

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
		var html= '';
		if($.trim($("#id"+id).attr('name')) == "Editprod"){
			$("#id"+id).closest('li').children('ul').children('li').find("label.lastnode").each(function(){
				var pNode = $(this).closest('ul').siblings('.PClass').find('input[type=radio]').closest('label').text().trim();
				prod_list.push({"pname":pNode ,"name":$.trim($(this).text()) , "value":$.trim($(this).closest("label").attr('class'))});
				savedProdID = id;
			});
			globalArray = prod_list;
		}
		//if(nameAttr == "Editprod" || nameAttr == "Editloc" || nameAttr == "Addloc" || nameAttr == "Addprod" || $(this).attr("name") == "Addbusiness"|| $(this).attr("name") == "Editbuss"){
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
						if(saveLastNode[t].product_id == aa){
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
	   /*	}else{
			for(i=0; i< display_list.length; i++){
				if(display_list[i] != ""){
					html +="<li class='"+display_list[i].value+"'>"+display_list[i].name+"<b> ("+display_list[i].pname+")</b></li>";
				}
			}
		}*/

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

	function chkAll(wrapper){
		if($("#"+wrapper).find(".selectAll").is(":checked")){
			$("#"+wrapper).find("li input[type=checkbox]").prop("checked", true);
		}else{
			$("#"+wrapper).find("li input[type=checkbox]").prop("checked", false);
		}
	}
	 /* =-------------------------------------------------------------------------------------------------------------------------------------- */
/* =-------------------------------------------------------------------------------------------------------------------------------------- */
	/* ---------------------------- on load show the list of teams -------------------------------------------------------- */

    function loadpage(){
        loaderShow();

        $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_teamController/get_teamdata'); ?>",
			dataType : 'json',
            cache : false,
			success : function(data){
                 $('#tablebody').parent("table").dataTable().fnDestroy();
		            $('#tablebody').html("")
				var row = "";
				if(error_handler(data)){
					return;
				}
				for(i=0; i < data.length; i++ ){
				    var str = [];
					var rowdata = JSON.stringify(data[i]);
                    if(data[i].selltype !=null){
                        str.push(data[i].selltype);
                    }else{
                        str="";
                    }
					/* row += "<tr><td>" + (i+1) + "</td><td>"+ data[i].teamname +  "</td><td>"+ data[i].deptname +  "</td><td>"+ data[i].locationname +  "</td><td>"+ data[i].productname + "</td><td>"+ data[i].business + "</td><td>"+ data[i].industry + "</td><td>"+str+"</td><td><a href='#' onclick='display("+rowdata+")'><span class='glyphicon  glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>"; */
					row += "<tr><td>" + (i+1) + "</td><td>"+ data[i].teamname +  "</td><td>"+ data[i].deptname +  "</td><td>"+str+"</td><td><a href='#' onclick='display("+rowdata+")'><span class='glyphicon  glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#editmodal' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
				}
                /* code for sandbox */
                if(data.length >= parseInt(recordcount) && parseInt(recordcount)!= 0){
                    $('#teambtn').hide();
                }else{
                    $('#teambtn').show();
                }
                $('#tablebody').append(row);
                loaderHide();
				$('#tablebody').parent("table").DataTable({
																"aoColumnDefs": [
																	{
																		"bSortable": false,
																		"aTargets": [4,5] }
																	]
															  });
                fillproducts();
			}

		});

    }



    /* ---------------------------------------- fill products ---------------------------------------------------------------------------- */
        function fillproducts(){
                $.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_teamController/get_productdata'); ?>",
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
						return;
					}
                    edit_tree(data,"tree-container_products", "null");
                    business_location();
				}
			});
        }
        /* -------------------------------------------------------- get department --------------------------------------------------- */
        function business_location(){
                $.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_teamController/business_location'); ?>",
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
						return;
					}
                    edit_tree(data,"tree-container_bussloc" , "null");
                    get_industry();
				}
			});
        }
        function get_industry(){
                $.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_teamController/get_industry'); ?>",
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
						return;
					}
                    edit_tree(data,"tree-container_indus" , "null");
                    getlocation();
				}
			});
        }
        /* -------------------------------------------------------- get location by sending region id --------------------------------------------------- */

		function getlocation(){
            $.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_teamController/get_locationdata'); ?>",
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
						return;
					}
					edit_tree(data,"tree-container_ofloc", "null");
                    var deptid="";
	                getdeptdata(deptid);
				}
			});
        }
     /*   ---------------------------------------------------------------------------------------------------------*/
		function getdeptdata(deptid){
            $.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_teamController/department'); ?>",
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
						return;
					}
					if(deptid==""){
						var select = $("#dept"), options = "<option value=''>Select</option>";
						select.empty();
						for(var i=0;i<data.length; i++)
						{
							options += "<option value='"+data[i].Department_id+"'>"+ data[i].Department_name +"</option>";
						}
					   select.append(options);
					}else{
						var select = $("#deptE"), options = "<option value=''>Select</option>";
						select.empty();
						for(var i=0;i<data.length; i++)
						{
							options += "<option value='"+data[i].Department_id+"'>"+ data[i].Department_name +"</option>";
						}
						select.append(options);
						$('#deptE').val(deptid);
                        loaderHide();
					}

	                //get_selltype();
				}
			});
        }

        /*   ---------------------------------------------------------------------------------------------------------*/
        function addmodal(){
         $("#addmodal").modal("show");
         get_selltype("null", "#sellType")
       }
        var sellarr=[];
		function get_selltype(savedVlue, container){
            $.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_teamController/getselltype'); ?>",
				dataType : 'json',
				cache : false,
				success : function(data){
					if(error_handler(data)){
						return;
					}
                    sellarr=[];
    				var select = $(container),options="<ul style='list-style: none;'>";

    				select.empty();
    				for(var i=0;i<data.length; i++)
    				{
    				        chk=0;
                         if(savedVlue == "null"){
                           options += "<li><label><input type='checkbox' value='"+data[i].lookup_id+"' id='"+data[i].lookup_id+"' />"+ data[i].lookup_value +"</label></li>";
                         }else{
                            for(j=0; j<savedVlue.length; j++){
            					if(savedVlue[j] == data[i].lookup_id ){
            						options += "<li><label><input type='checkbox' value='"+data[i].lookup_id+"' id='"+data[i].lookup_id+"' checked/>"+ data[i].lookup_value +"</label></li>";
                                    chk=0;
                                    break;
                                }else{
                                    chk=1;
            					}
            				}
                            if(chk==1){
                                  options += "<li><label><input type='checkbox' value='"+data[i].lookup_id+"' id='"+data[i].lookup_id+"' />"+ data[i].lookup_value +"</label></li>";
                            }
                         }
                        sellarr.push(data[i].lookup_id);
    				}
                    options +="</ul>"
    				select.append(options);
				}
			});
        }



		/* -------------BACK button click----ADD Section 2-------------- */
		function add_section2B(){
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section1").show();
		}
		/* -------------BACK button click----ADD Section 3-------------- */
		function add_section3B(){
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section2").show();
		}
		/* -------------BACK button click----ADD Section 4-------------- */
		function add_section4B(){
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section3").show();
		}
		/* -------------BACK button click----ADD Section 5-------------- */
		function add_section5B(){
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section4").show();
		}
		/* -------------BACK button click----ADD Section 6-------------- */
		function add_section6B(){
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section5").show();
		}

		/* ------------------------------------------------------------------- */
		/* ------------------------------------------------------------------- */
		/* -------------Proceed button click--ADD Section 1---------------- */
		function add_section1P(){

			$('.error-alert').text('');
			if($("#addTeam").val()==""){
				$("#addTeam").closest("div").find("span").text("Team Name is required.");
				$("#addTeam").focus();
                loaderHide();
				return;
			}else if(!valid_name($.trim($("#addTeam").val()))) {
				$("#addTeam").closest("div").find("span").text("Single quote(') and Backslash(\\) are not acceptable.");
				$("#addTeam").focus();
                loaderHide();
				return;
			}else if(!firstLetterChk($.trim($("#addTeam").val()))) {
				$("#addTeam").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#addTeam").focus();
                loaderHide();
				return;
			}else{
				$("#addTeam").closest("div").find("span").text("");
			}

			if($("#dept").val()==""){
				$("#dept").closest("div").find("span").text("Department is required.");
				$("#dept").focus();
                loaderHide();
				return;
			}else{
				$("#dept").closest("div").find("span").text("");
			}

			var sellType =[];
			$("#sellType input[type=checkbox]").each(function(){
					if($(this).prop("checked")==true){
						sellType.push($(this).val());
					}
			})
			if(sellType.length == 0){
				$("#sellType").siblings(".error-alert").text("Sell type is required.");
				loaderHide();
				return;
			}
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section2").show();
		}

		/* -------------Proceed button click--ADD Section 2---------------- */
		function add_section2P(){

			$('.error-alert').text('');
			var myArrayID_loc =[];
			var lastnodeFlag=0
			$("#tree-container_ofloc input[type=radio]").each(function(){
				if($(this).prop('checked') == true){
					myArrayID_loc.push($.trim($(this).val()));
				}
			});
			if(myArrayID_loc.length  == 0){
				$(".error-alert.office-list").text("Please select Office Location.");
                loaderHide();
				return;
			}else{
				 $(".error-alert.office-list").text("");
			}
			/* --------------- */
			var lastnodeFlag=0
			$("#display_loc .lastnode").each(function(){
				if($(this).find("input[type=checkbox]").prop("checked")==true){
					lastnodeFlag = 1;
				}
			})
			if(lastnodeFlag == 0){
				$(".error-alert.office-list").text("You will need to select atleast one Office Location to proceed further.");
                loaderHide();
				return;
			}else{
				$(".error-alert.office-list").text("");
			}

			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section3").show();
		}
		/* -------------Proceed button click--ADD Section 3---------------- //  */
		function add_section3P(){

			$('.error-alert').text('');
			var busslocArray = [];
			$("#tree-container_bussloc input[type=radio]").each(function(){
				if($(this).prop('checked') == true){
					busslocArray.push($.trim($(this).val()));
				}
			});

			if(busslocArray.length  == 0){
				$(".error-alert.business-list").text("Please select Business Location.");
                loaderHide();
				return;
			}else{
				 $(".error-alert.business-list").text("");
			}
            /* --------------- */
			var lastnodeFlag=0
			$("#display_business .lastnode").each(function(){
				if($(this).find("input[type=checkbox]").prop("checked")==true){
					lastnodeFlag = 1;
				}
			})
			if(lastnodeFlag == 0){
				$(".error-alert.business-list").text("You will need to select atleast one Bussiness Location to proceed further.");
                loaderHide();
				return;
			}else{
				$(".error-alert.business-list").text("");
			}
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section4").show();
		}
		/* -------------Proceed button click--ADD Section 4---------------- */
		function add_section4P(){
			$('.error-alert').text('');
			var indusArray = [];
			$("#tree-container_indus input[type=radio]").each(function(){
				if($(this).prop('checked') == true){
					indusArray.push($.trim($(this).val()));
				}
			});
			if(indusArray.length  == 0){
				$(".error-alert.industry-list").text("Please select industry.");
                loaderHide();
				return;
			}else{
				 $(".error-alert.industry-list").text("");
			}
            /* --------------- */
			var lastnodeFlag=0
			$("#display_indus .lastnode").each(function(){
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

			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section5").show();
		}

		function add_section5P(){
			var myArrayID_pro =[]
			var prodOption=[];
			var prodOption1={};
			var lastnodeFlag=0;
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
			$("#tree-container_products input[type=radio]").each(function(){
                if($(this).prop('checked') == true){
                    myArrayID_pro.push($.trim($(this).val()));
                }
            });
            if(myArrayID_pro.length  == 0){
                $(".error-alert.product-list").text("Please select Product.");
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
			var prodhtml ="";
			var currencyhtml ="";
			var currencyhtml1 ="";
			$("#currency_value_list").html("");

			var addObj={};
			addObj.proarry=prodOption1;

			$.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_teamController/get_currency'); ?>",
				data : JSON.stringify(addObj),
				dataType : 'json',
				cache : false,
				success : function(data){
					loaderHide();
					if(error_handler(data)){
						return;
					}
					for(var i=0;i<data.length; i++){
						if(data[i].hasOwnProperty('curdata')) {
							$("#currency_value_list").append('<div class="col-md-5" id="currencyList'+i+'"><label class="prod_leaf_node highlight"><input checked disabled id="prod'+data[i].product_id+'" onchange="checkUncheck(this.id)" type="checkbox" value="'+data[i].product_id+'"> '+data[i].productname+'</label></div>');
							currencyhtml="";
							currencyhtml +='<div id="currency_value'+i+'" class="multiselect">';
							currencyhtml +='<ul>';

							for(var j=0;j<data[i].curdata.length; j++){
							    if(data[i].curdata.length ==1){
                                    currencyhtml +='<li><label><input type="checkbox" checked value="'+data[i].curdata[j].currency_id+'" >  '+data[i].curdata[j].currencyname+'<label></li>';
							    }else{
							        currencyhtml +='<li><label><input type="checkbox" value="'+data[i].curdata[j].currency_id+'" >  '+data[i].curdata[j].currencyname+'<label></li>';
							    }

							}
							currencyhtml +='</ul>';
							currencyhtml +='</div>';
							$("#currencyList"+i).append(currencyhtml);
						}
					}
					for(var i=0;i<data.length; i++){
						if(data[i].hasOwnProperty('curdata')) {

						}else{
							currencyhtml1 += '<div class="col-md-12" id="currencyList'+i+'"><label class="prod_leaf_node highlight"><input checked disabled type="checkbox" value="'+data[i].product_id+'">  '+data[i].productname+'</label></div>';
						}
					}
					if(currencyhtml1 != ""){
						$("#currency_value_list").append("<div class='without-curr col-md-5'>"+ currencyhtml1 +"</div>");
					}
				}
			});

			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section6").show();
		}
		/* enable/disable currency checkbox  */
		function checkUncheck(id){
			var selected = $("#"+$.trim(id));
			if(selected.prop("checked") == true){
				selected.closest("label").addClass("highlight");
				selected.closest(".col-md-5").find(".multiselect").find("input[type=checkbox]").removeAttr("disabled");
			}else{
				selected.closest("label").removeClass("highlight");
				selected.closest(".col-md-5").removeAttr("style")
				selected.closest(".col-md-5").find(".multiselect").find("input[type=checkbox]").attr('disabled', 'disabled');
				selected.closest(".col-md-5").find(".multiselect").find("input[type=checkbox]").prop('checked', false);
			}
		}
        /* -------------------------------Proceed button click--ADD Section 5--------------------
		save function ---------------------------------------------------------------- */
		function addsave(){
		    loaderShow();
			$('.error-alert').text('');
				var myArrayID_loc =[];
				var myArrayID_pro =[];
                var myArrayID_bus =[];
				var myArrayID_ind =[];
				var addObj={};
				/*--------------------------New implementation------------------------------*/
				if($("#addTeam").val()==""){
					$("#addTeam").closest("div").find("span").text("Team Name is required.");
					$("#addTeam").focus();
					return;
				}else if(!valid_name($.trim($("#addTeam").val()))) {
					$("#addTeam").closest("div").find("span").text("Single quote(') and Backslash(\\) are not acceptable.");
					$("#addTeam").focus();
                    loaderHide();
					return;
				}else if(!firstLetterChk($.trim($("#addTeam").val()))) {
					$("#addTeam").closest("div").find("span").text("First letter should not be Numeric or Special character.");
					$("#addTeam").focus();
                    loaderHide();
					return;
				}else{
					$("#addTeam").closest("div").find("span").text("");
				}

				if($("#dept").val()==""){
					$("#dept").closest("div").find("span").text("Department is required.");
                    loaderHide();
					return;
				}else{
					$("#dept").closest("div").find("span").text("");
				}
				var sellType =[];
				$("#sellType input[type=checkbox]").each(function(){
						if($(this).prop("checked")==true){
							sellType.push($(this).val());
						}
				})
				if(sellType.length == 0){
					$("#sellType").siblings(".error-alert").text("Sell type is required.");
					loaderHide();
					return;
				}

				/* -------------Location ------------------ */
				$("#tree-container_ofloc input[type=radio]").each(function(){
					if($(this).prop('checked') == true){
						myArrayID_loc.push($.trim($(this).val()));
					}
				});
				/* -------------Location last node------------------ */
				var OfficeLocation=[];
				var OfficeLocation1={};
				$("#display_loc .lastnode").each(function(){
					if($(this).find("input[type=checkbox]").prop("checked")==true){
						var optionVal = $.trim($(this).attr("class").replace(" lastnode", ""));
						var optionText = $.trim($(this).text());
						OfficeLocation.push({"val":optionVal , "txt": optionText})
						OfficeLocation1[optionVal]=optionText;
					}
				})
				/* ------------------Product------------- */
				$("#tree-container_products input[type=radio]").each(function(){
					if($(this).prop('checked') == true){
						myArrayID_pro.push($.trim($(this).val()));
					}
				});
                /*  -----------------business location---------------- */
                $("#tree-container_bussloc input[type=radio]").each(function(){
					if($(this).prop('checked') == true){
						myArrayID_bus.push($.trim($(this).val()));
					}
				});
                /*  -----------------business location last node---------------- */
                var bussiLocation=[];
				var bussiLocation1={};
				$("#display_business .lastnode").each(function(){
					if($(this).find("input[type=checkbox]").prop("checked")==true){
						var optionVal = $.trim($(this).attr("class").replace(" lastnode", ""));
						var optionText = $.trim($(this).text());
						bussiLocation.push({"val":optionVal , "txt": optionText})
						bussiLocation1[optionVal]=optionText;
					}
				})
				/*  ---------------industry---------------- */
				$("#tree-container_indus input[type=radio]").each(function(){
					if($(this).prop('checked') == true){
						myArrayID_ind.push($.trim($(this).val()));
					}
				});
                 /*  -----------------business location last node---------------- */
                var indusLocation=[];
				var indusLocation1={};
				$("#display_indus .lastnode").each(function(){
					if($(this).find("input[type=checkbox]").prop("checked")==true){
						var optionVal = $.trim($(this).attr("class").replace(" lastnode", ""));
						var optionText = $.trim($(this).text());
						indusLocation.push({"val":optionVal , "txt": optionText})
						indusLocation1[optionVal]=optionText;
					}
				})
				/*  ---------------Currency---------------- */
				flg =0;
				if(create_procur_array()  == "selectnone"){
						$('#error').text("Please select atleast one product");
						$('.prod_leaf_node').closest(".col-md-5").find(".prod_leaf_node1").addClass("error");
						$('.prod_leaf_node').closest(".col-md-5").css({"border": "1px solid red"});
						loaderHide();
						return;
				}
				else if(create_procur_array()  == "chkatleastone"){
					$('#error').text("Please select atleast one curreny from selected product(s)");
					 loaderHide();
					return;
				}
				else if(create_procur_array()  == "success"){
					$('#error').text("");
				}

				/*  ---------------Currency---------------- */

                if($('#cust_manage').prop('checked')== true){
                  var cust_management=1;
                }else{
                  var cust_management=0;
                }
				addObj.OfficeLocLastNode=OfficeLocation1;
				addObj.bussiLocLastNode=bussiLocation1;
				addObj.indusLocLastNode=indusLocation1;
				addObj.prodCurrency = prodCurrencymain;
				addObj.locNode = myArrayID_loc[0];
				addObj.proNode = myArrayID_pro[0];
                addObj.busNode = myArrayID_bus[0];
				addObj.indusNode = myArrayID_ind[0];
				addObj.cust_management = cust_management;
				addObj.sellType = sellType.toString();

                addObj.teamname = $("#addTeam").val().trim();
                addObj.deptname = $("#dept").val().trim();

				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_teamController/post_data'); ?>",
                    data : JSON.stringify(addObj),
					dataType : 'json',
					cache : false,
					success : function(data){
						if(error_handler(data)){
							return;
						}
					    cancel();
						$('#tablebody').empty();
                        if(data==1){
							loadall();
                        }else{
							/* alert("Team name already exists."); */
							loaderHide();
                            $('#alert').modal('show');
                        }
					}
				});
			}
        /* =-------------------------------------------------------------------------------------------------------------------------------------- */
      /* ------------------------------------- on select of row fill the required fields ----view_secton--------------------------------------------- */

	function display(obj){

        $("#view_modal").modal("show");
		$(".view_secton").html("");
		$(".view_secton_title").html("View "+obj.teamname+" Details");
		var viewHtml ="";
        var str = [];
        str=obj.selltype;
        addObj={};
        addObj.teamid = obj.teamid;
        addObj.business_location_id = obj.business_location_id;
        addObj.industry_id = obj.industry_id;
        $.ajax({
        	type : "POST",
        	url : "<?php echo site_url('admin_teamController/get_viewdata'); ?>",
            data : JSON.stringify(addObj),
        	dataType : 'json',
        	cache : false,
        	success : function(data){
						if(error_handler(data)){
							return;
						}
                            if(data.offdata[0].hasOwnProperty("locdata")){
                    			var offLoc ="";
                    			offLoc +="<div class='row'>";
                    			for(i = 0; i < data.offdata.length; i++){
                    				offLoc += "<div class='col-md-4'><i class='fa fa-map-marker' aria-hidden='true'></i> "+data.offdata[i].locdata[i].productname+"</div>";
                    			}
                    			offLoc +="</div>";
                    		}else{
                    			offLoc="";
                    		}
                            if(data.bldata.length >0){
                            if(data.bldata[0].hasOwnProperty("blocdata")){
                    			var blocLoc ="";
                    			blocLoc +="<div class='row'>";
                    			for(i = 0; i < data.bldata.length; i++){
                    				blocLoc += "<div class='col-md-4'><i class='fa fa-map-marker' aria-hidden='true'></i> "+data.bldata[i].blocdata[i].productname+"</div>";
                    			}
                    			blocLoc +="</div>";
                    		}

                            var indus ="";
                    		indus +="<div class='row'>";
                    		for(i = 0; i < data.indata.length; i++){
                    			indus += "<div class='col-md-4'><i class='fa fa-level-up' aria-hidden='true'></i> "+data.indata[i].indusdata[i].productname+"</div>";
                    		}
                    		indus +="</div>";
                            }
                    		var prodLi ="";
                    		prodLi +="<div class='row'>";
                            if(data.procur[0].hasOwnProperty("procurdata")){
                          		for(i = 0; i < data.procur.length; i++){
                          			if(data.procur[i].procurdata[i].hasOwnProperty("curdata")){
                          				var curLi ="";
                          				curLi +="<ul type='i'>";
                          				for(k = 0; k < data.procur[i].procurdata[i].curdata.length; k++){
                          					if(data.procur[i].procurdata[i].curdata[k].currencyname != null){
                          						curLi += "<li> <i class='fa fa-level-up' aria-hidden='true'></i> "+data.procur[i].procurdata[i].curdata[k].currencyname +"( <b>" +data.procur[i].procurdata[i].curdata[k].curvalue+" </b>)</li>";
                          					}
                          				}
                          				curLi +="</ul>";
                          			}else{
                          				curLi="";
                          			}
                          			prodLi += "<div class='col-md-4'><i class='fa fa-product-hunt' aria-hidden='true'></i> "+data.procur[i].procurdata[i].productname+ curLi+"</div>";
                          		}
                            }
                    		prodLi +="</div>";

                            viewHtml += "<table class='table'><tbody>";

                    		viewHtml += "<tr><td width='20%'><b>Team Name</b></td><td width='20%'>"+obj.teamname+" </td><td width='60%'></td></tr>";
                    		viewHtml += "<tr><td width='20%'><b>Sell Type</b></td><td width='20%'>"+str+" </td><td width='60%'><b>Department Name</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp"+obj.deptname+"</td></tr>";
                    		viewHtml += "<tr><td colspan='4'><h4><b>Office Location</b></h4>"+offLoc+"</td></tr>";
                    		viewHtml += "<tr><td colspan='4'><h4><b>Business Location</b></h4>"+blocLoc+"</td></tr>";
                    		viewHtml += "<tr><td colspan='4'><h4><b>Industry</b></h4>"+indus+"</td></tr>";
                    		viewHtml += "<tr><td colspan='4'><h4><b>Product</b></h4>"+prodLi+"</td></tr>";

                    		viewHtml += "</tbody></table>";
                    		$(".view_secton").append(viewHtml);
        	}
		});

	}



    /* ==================================================================================================================================== */
     /* -------------------------------------------------------- get location by sending region id --------------------------------------------------- */

		function getlocationE(teamid){
		    addObj={};
		    addObj.teamid=teamid;
            $.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_teamController/get_locationdata_edit'); ?>",
				dataType : 'json',
                data : JSON.stringify(addObj),
				cache : false,
				success : function(data){
					if(error_handler(data)){
						return;
					}
				    if(data.offloc1[0].hasOwnProperty("locdata")){
                        edit_tree(data.offhie,"tree-container_oflocE", data.offloc1[0].locdata);
                    }else{
                        edit_tree(data.offhie,"tree-container_oflocE", "null");
                    }

                    business_locationE(teamid);
				}
			});
        }
         /* -------------------------------------------------------- get department --------------------------------------------------- */
        function business_locationE(teamid){
                addObj={};
		        addObj.teamid=teamid;
                $.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_teamController/get_businessdata_edit'); ?>",
				dataType : 'json',
                data : JSON.stringify(addObj),
				cache : false,
				success : function(data){
					if(error_handler(data)){
						return;
					}
                    if(data.busloc1.length>0){
                        if(data.busloc1[0].hasOwnProperty("busdata")){
                            edit_tree(data.bushie,"tree-container_busE", data.busloc1[0].busdata);
                        }else{
                            edit_tree(data.bushie,"tree-container_busE", "null");
                        }
                    }else{
                        edit_tree(data.bushie,"tree-container_busE", "null");
                    }
                    //edit_tree(data,"tree-container_busE" , "null");
                    get_industryE(teamid);
				}
			});
        }
        function get_industryE(teamid){
                addObj={};
		        addObj.teamid=teamid;
                $.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_teamController/get_industry_edit'); ?>",
				dataType : 'json',
                data : JSON.stringify(addObj),
				cache : false,
				success : function(data){
					if(error_handler(data)){
						return;
					}
                    if(data.indusloc1.length>0){
                        if(data.indusloc1[0].hasOwnProperty("indusdata")){
                            edit_tree(data.indushie,"tree-container_indE", data.indusloc1[0].indusdata);
                        }else{
                            edit_tree(data.indushie,"tree-container_indE", "null");
                        }
                    }else{
                        edit_tree(data.indushie,"tree-container_indE", "null");
                    }
                    //edit_tree(data,"tree-container_indE" , "null");
                    fillproductsE(teamid);
				}
			});
        }
    /* ---------------------------------------- fill products ---------------------------------------------------------------------------- */
        function fillproductsE(teamid){
                addObj={};
		        addObj.teamid=teamid;
                $.ajax({
				type : "POST",
				url : "<?php echo site_url('admin_teamController/get_productdata_edit'); ?>",
				dataType : 'json',
                data : JSON.stringify(addObj),
				cache : false,
				success : function(data){
					if(error_handler(data)){
						return;
					}
				    if(data.procur[0].hasOwnProperty("procurdata")){
                        edit_tree(data.prohie,"tree-container_productsE", data.procur[0].procurdata);
                        $("#hiden_row_val").val(JSON.stringify(data.procur[0].procurdata));
                    }else{
                        edit_tree(data.prohie,"tree-container_productsE", "null");
                        $("#hiden_row_val").val("");
                    }

				}
			});
        }


     /*   ---------------------------------------------------------------------------------------------------------*/



    /* ==================================================================================================================================== */

	var retainOldChk;
	/* ----------------------------------- */
		function selrow(obj){
            loaderShow();

			$(".edit_secton_title").html("Edit "+obj.teamname+" Details");
			if(obj.hasOwnProperty("procurdata")){
					$("#hiden_row_val").val(JSON.stringify(obj.procurdata));
			}else{
				$("#hiden_row_val").val("");
			}
			$("#tree-container_oflocE").html("");
			$("#tree-container_productsE").html("");
            $("#tree-container_bussE").html("");
			$("#tree-container_indE").html("");
			$(".error-alert").text("");

            if(obj.customer_management == 1){
               $('#cust_manageE').prop('checked',true);
            }else{
               $('#cust_manageE').prop('checked',false);
            }

            var addObj={};
            var teamid=obj.teamid;
            var deptid=obj.department_id;
            getdeptdata(deptid);
            addObj.teamid=teamid;
            var sellType=obj.regionid;
            var sellType1=sellType.split(",");

             if(sellType1.length > 0){
                get_selltype(sellType1, "#sellTypeE");
             }else{
               get_selltype("null", "#sellTypeE");
             }

            getlocationE(teamid);

			$("#addTeamE").val(obj.teamname);
			$("#hid_teamid").val(obj.teamid);

		}
		/* ------------------------------------------------------------------- */
		/* ------------------------------------------------------------------- */
		/* -------------BACK button click----Edit Section 2-------------- */
		function edit_section2B(){
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section1").show();
		}
		function edit_section3B(){
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section2").show();
		}
		function edit_section4B(){
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section3").show();
		}
		function edit_section5B(){
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section4").show();
		}
		function edit_section6B(){
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section5").show();
		}
        /* --------------------------------------------------------------------------------------------------------------------------------- */
        /* -----------------------------------------  Edit section  --------------------------------------------------------------------- */

		function edit_section1P(){
			$('.error-alert').text('');
			if($("#addTeamE").val()==""){
				$("#addTeamE").closest("div").find("span").text("Team Name is required.");
				$("#addTeamE").focus();
                loaderHide();
				return;
			}else if(!valid_name($.trim($("#addTeamE").val()))) {
				$("#addTeamE").closest("div").find("span").text("Single quote(') and Backslash(\\) are not acceptable.");
				$("#addTeamE").focus();
                loaderHide();
				return;
			}else if(!firstLetterChk($.trim($("#addTeamE").val()))) {
				$("#addTeamE").closest("div").find("span").text("First letter should not be Numeric or Special character.");
				$("#addTeamE").focus();
                loaderHide();
				return;
			}else{
				$("#addTeamE").closest("div").find("span").text("");
			}

			if($("#deptE").val()==""){
				$("#deptE").closest("div").find("span").text("Department is required.");
				$("#deptE").focus();
                loaderHide();
				return;
			}else{
				$("#deptE").closest("div").find("span").text("");
			}
			var sellTypeE =[];
			$("#sellTypeE input[type=checkbox]").each(function(){
					if($(this).prop("checked")==true){
						sellTypeE.push($(this).val());
					}
			})
			if(sellTypeE.length == 0){
				$("#sellTypeE").siblings(".error-alert").text("Sell type is required.");
				loaderHide();
				return;
			}
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section2").show();
		}
		function edit_section2P(){

			$('.error-alert').text('');
			var myArrayID_loc =[];
			/* -------------Location ------------------ */
			$("#tree-container_oflocE input[type=radio]").each(function(){
				if($(this).prop('checked') == true){
					myArrayID_loc.push($.trim($(this).val()));
				}
			});

			if(myArrayID_loc.length  == 0){
				$(".error-alert.loc-list").text("Please select Office Location.");
                loaderHide();
				return;
			}else{
				 $(".error-alert.loc-list").text("");
			}
			/* --------------- */
			var lastnodeFlag=0
			$("#display_locE .lastnode").each(function(){
				if($(this).find("input[type=checkbox]").prop("checked")==true){
					lastnodeFlag = 1;
				}
			})
			if(lastnodeFlag == 0){
				$(".error-alert.loc-list").text("You will need to select atleast one Office Location to proceed further.");
                loaderHide();
				return;
			}else{
				$(".error-alert.loc-list").text("");
			}

			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section3").show();
		}
		function edit_section3P(){

			$('.error-alert').text('');
			var myArrayID_busi =[];
			/* ------------------------- business Location*/
			$("#tree-container_busE input[type=radio]").each(function(){
				if($(this).prop('checked') == true){
					myArrayID_busi.push($.trim($(this).val()));
				}
			});
			if(myArrayID_busi.length  == 0){
				$(".error-alert.business-list").text("Please select Business Location.");
                loaderHide();
				return;
			}else{
				 $(".error-alert.business-list").text("");
			}
            /* --------------- */
			var lastnodeFlag=0
			$("#display_bussE .lastnode").each(function(){
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
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section4").show();
		}
		function edit_section4P(){


			$('.error-alert').text('');
			var myArrayID_indus =[];
			/* -------------------- industry */
			$("#tree-container_indE input[type=radio]").each(function(){
				if($(this).prop('checked') == true){
					myArrayID_indus.push($.trim($(this).val()));
				}
			});
			if(myArrayID_indus.length  == 0){
				$(".error-alert.industry-list").text("Please select Industry.");
                loaderHide();
				return;
			}else{
				 $(".error-alert.industry-list").text("");
			}
            /* --------------- */
			var lastnodeFlag=0
			$("#display_indE .lastnode").each(function(){
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
			$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
			$(".section5").show();
		}
		/* function edit_section5P(obj){ */
		function proceedProdList(prodOption1){
		        loaderShow();
				var prodhtml ="";
				var currencyhtml ="";
				var currencyhtml1 ="";
				$("#currency_value_listE").html("");

				var addObj={};
				addObj.proarry=prodOption1;
				addObj.inActiveSet="outOfScope";

				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_teamController/get_currency'); ?>",
					data : JSON.stringify(addObj),
					dataType : 'json',
					cache : false,
					success : function(data){
						if(error_handler(data)){
							return;
						}

						for(var i=0;i<data.length; i++){
							if(data[i].hasOwnProperty('curdata')) {
								$("#currency_value_listE").append('<div class="col-md-5" id="currencyList'+i+'"><label class="prod_leaf_node highlight"><input disabled checked id="prod'+data[i].product_id+'"onchange="checkUncheck(this.id)" type="checkbox" value="'+data[i].product_id+'">  '+data[i].productname+'</label></div>');
								currencyhtml="";
								currencyhtml +='<div id="currency_value'+i+'" class="multiselect">';
								currencyhtml +='<ul>';

								for(var j=0;j<data[i].curdata.length; j++){
									currencyhtml +='<li><label><input type="checkbox" value="'+data[i].curdata[j].currency_id+'">  '+data[i].curdata[j].currencyname+'<label></li>';
								}
								currencyhtml +='</ul>';
								currencyhtml +='</div>';
								$("#currencyList"+i).append(currencyhtml)
							}
						}
						for(var i=0;i<data.length; i++){
							if(data[i].hasOwnProperty('curdata')) {

							}else{
								currencyhtml1 += '<div class="col-md-12" id="currencyList'+i+'"><label class="prod_leaf_node highlight" ><input disabled checked type="checkbox" value="'+data[i].product_id+'">  '+data[i].productname+'</label></div>';
							}
						}

						if(currencyhtml1 != ""){
							$("#currency_value_listE").append("<div class='without-curr col-md-5'>"+ currencyhtml1 +"</div>");
						}
						if($("#hiden_row_val").val() != ""){
							var savedCurrency = JSON.parse($("#hiden_row_val").val());
								$("#currency_value_listE .prod_leaf_node input[type='checkbox']").each(function(){
								for(chk=0; chk<savedCurrency.length; chk++){
									if($(this).val() == savedCurrency[chk].product_id){
										if($(this).closest(".col-md-5").find(".multiselect").length > 0){
											$(this).closest("label").addClass("highlight");
										}
										$(this).prop("checked", true);
										$(this).closest(".col-md-5").find(".multiselect").find("input[type='checkbox']").prop("disabled", false);
										$(this).closest(".col-md-5").find(".multiselect").find("input[type='checkbox']").each(function(){
											for(chk1=0; chk1<savedCurrency[chk].curdata.length; chk1++){
												if($(this).val() == savedCurrency[chk].curdata[chk1].currency_id){
													$(this).prop("checked", true).prop("disabled", false);
												}
											}
										})
									}
								}
							})
							loaderHide();
						}else{
                            loaderHide();
						}
					}

				});
				$(".section1,.section2,.section3,.section4,.section5,.section6").hide();
				$(".section6").show();
		}
		function edit_section5P(){
			var myArrayID_pro =[];
			/* ------------------Product------------- *///
				$("#tree-container_productsE input[type=radio]").each(function(){
					if($(this).prop('checked') == true){
						myArrayID_pro.push($.trim($(this).val()));
					}
				});
				if(myArrayID_pro.length  == 0){
					$(".error-alert.prod-curncy").text("Please select Product.");
                    loaderHide();
					return;
				}else{
					 $(".error-alert.prod-curncy").text("");
				}
				var lastnodeFlag=0
				$("#display_prodE .lastnode").each(function(){
					if($(this).find("input[type=checkbox]").prop("checked")==true){
						lastnodeFlag = 1;
					}
				})
				if(lastnodeFlag == 0){
					$(".error-alert.prod-curncy").text("You will need to select atleast one Product to proceed further.");
                    loaderHide();
					return;
				}else{
					$(".error-alert.prod-curncy").text("");
				}
			var prodOption=[];
			var prodOption1={};
			$("#selectProduct").html("");
			var prodLoop =0
			var proceedFlag =0
			$("#display_prodE .lastnode").each(function(){
				if($(this).find("input[type=checkbox]").prop('checked') == true){
					var optionVal = $.trim($(this).attr("class").replace(" lastnode", ""));
					var optionText = $.trim($(this).text());
					prodOption.push({"val":optionVal , "txt": optionText})
					prodOption1[optionVal]=optionText;
				}
			})
			for(ex=0; ex< globalArray.length; ex++){
				for(ex1=0; ex1< prodOption.length; ex1++){
					if($.trim(globalArray[ex].value.replace(" lastnode", "")) == $.trim(prodOption[ex1].val)){
						prodLoop = 1;
						break;
					}
				}
			}
			if(prodLoop == 1){
				proceedFlag = 1;
			}else{

				$("body").append('<div class="mask custom-alert"></div><div class="alert alert-danger row custom-alert"> <div class="col-md-9">Do you want to change the list of products..? </div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');

				$(".notOk").click(function(){
					$("#id"+savedProdID).prop("checked", true);
					displayList(savedProdID,"Editprod",retainOldChk);
					$(".custom-alert").remove();
				});
				$(".Ok").click(function(){
						proceedProdList(prodOption1);
						$(".custom-alert").remove();
				});
			}

			if(proceedFlag == 1){
				proceedProdList(prodOption1);
			}

		}
        /* --------------------------------------------------------------------------------------------------------------------------------- */
        /* -----------------------------------------  update function --------------------------------------------------------------------- */
        function update(){
                loaderShow();
				$('.error-alert').text('');
				var myArrayID_loc =[];
                var myArrayID_busi =[];
				var myArrayID_indus =[];
				var myArrayID_pro =[];
                var addObj={};
				/*--------------------------New implementation------------------------------*/
				if($("#addTeamE").val()==""){
					$("#addTeamE").closest("div").find("span").text("Team Name is required.");
					$("#addTeamE").focus();
                    loaderHide();
					return;
				}else if(!valid_name($.trim($("#addTeamE").val()))) {
					$("#addTeamE").closest("div").find("span").text("Single quote(') and Backslash(\\) are not acceptable.");
					$("#addTeamE").focus();
                    loaderHide();
					return;
				}else if(!firstLetterChk($.trim($("#addTeamE").val()))) {
					$("#addTeamE").closest("div").find("span").text("First letter should not be Numeric or Special character.");
					$("#addTeamE").focus();
                    loaderHide();
					return;
				}else{
					$("#addTeamE").closest("div").find("span").text("");
				}
				if($("#deptE").val()==""){
					$("#deptE").closest("div").find("span").text("Department is required.");
                    loaderHide();
					return;
				}else{
					$("#deptE").closest("div").find("span").text("");
				}
				var sellTypeE =[];
				$("#sellTypeE input[type=checkbox]").each(function(){
						if($(this).prop("checked")==true){
							sellTypeE.push($(this).val());
						}
				})
				if(sellTypeE.length == 0){
					$("#sellTypeE").siblings(".error-alert").text("Sell type is required.");
					loaderHide();
					return;
				}
				/* -------------Office Location ------------------ */
				$("#tree-container_oflocE input[type=radio]").each(function(){
					if($(this).prop('checked') == true){
						myArrayID_loc.push($.trim($(this).val()));
					}
				});
                /* -------------Location last node------------------ */
				var OfficeLocation=[];
				var OfficeLocation1={};
				$("#display_locE .lastnode").each(function(){
					if($(this).find("input[type=checkbox]").prop("checked")==true){
						var optionVal = $.trim($(this).attr("class").replace(" lastnode", ""));
						var optionText = $.trim($(this).text());
						OfficeLocation.push({"val":optionVal , "txt": optionText})
						OfficeLocation1[optionVal]=optionText;
					}
				});
				/* ------------------------- business Location*/
				$("#tree-container_busE input[type=radio]").each(function(){
					if($(this).prop('checked') == true){
						myArrayID_busi.push($.trim($(this).val()));
					}
				});
                /* -------------Location last node------------------ */
				var bussiLocation=[];
				var bussiLocation1={};
				$("#display_bussE .lastnode").each(function(){
					if($(this).find("input[type=checkbox]").prop("checked")==true){
						var optionVal = $.trim($(this).attr("class").replace(" lastnode", ""));
						var optionText = $.trim($(this).text());
						bussiLocation.push({"val":optionVal , "txt": optionText})
						bussiLocation1[optionVal]=optionText;
					}
				});
				/* -------------------- industry */
				$("#tree-container_indE input[type=radio]").each(function(){
					if($(this).prop('checked') == true){
						myArrayID_indus.push($.trim($(this).val()));
					}
				});
                /* -------------Location last node------------------ */
				var indusLocation=[];
				var indusLocation1={};
				$("#display_indE .lastnode").each(function(){
					if($(this).find("input[type=checkbox]").prop("checked")==true){
						var optionVal = $.trim($(this).attr("class").replace(" lastnode", ""));
						var optionText = $.trim($(this).text());
						indusLocation.push({"val":optionVal , "txt": optionText})
						indusLocation1[optionVal]=optionText;
					}
				});
				/*  ---------------Currency---------------- */
				$("#tree-container_productsE input[type=radio]").each(function(){
					if($(this).prop('checked') == true){
						myArrayID_pro.push($.trim($(this).val()));
					}
				});
				/*  ---------------Currency---------------- */
				flg =0;
				if(create_procur_array()  == "selectnone"){
						$('#error').text("Please select atleast one product");
						$('.prod_leaf_node').closest(".col-md-5").find(".prod_leaf_node1").addClass("error");
						$('.prod_leaf_node').closest(".col-md-5").css({"border": "1px solid red"});
						loaderHide();
						return;
				}
				else if(create_procur_array()  == "chkatleastone"){
					$('#error').text("Please select atleast one curreny from selected product(s)");
					 loaderHide();
					return;
				}
				else if(create_procur_array()  == "success"){
					$('#error').text("");
				}


				/* ------------------- */
				var unselectedProd=[];
				$(".prod_leaf_node input[type=checkbox]").each(function(){
					if($(this).prop("checked") == false){
						unselectedProd.push($(this).val());
					}
				});

                if($('#cust_manageE').prop('checked')== true){
                  var cust_management=1;
                }else{
                  var cust_management=0;
                }
				addObj.unselectedProd = unselectedProd.toString();
				addObj.prodCurrency = prodCurrencymain;
				addObj.OfficeLocLastNode = OfficeLocation1;
				addObj.bussiLocLastNode = bussiLocation1;
				addObj.indusLocLastNode = indusLocation1;
				addObj.locNode = myArrayID_loc[0];
				addObj.proNode = myArrayID_pro[0];
                addObj.busNode=  myArrayID_busi[0];
				addObj.indusNode= myArrayID_indus [0];
				addObj.cust_management= cust_management;
				addObj.sellTypeE= sellTypeE.toString();

                addObj.teamname = $("#addTeamE").val().trim();
                addObj.teamid = $("#hid_teamid").val().trim();
                addObj.deptid = $("#deptE").val().trim();

				$.ajax({
					type : "POST",
					url : "<?php echo site_url('admin_teamController/update_data'); ?>",
					dataType : 'json',
					data : JSON.stringify(addObj),
					cache : false,
					success : function(data){
						if(error_handler(data)){
							return;
						}

					    cancel();
                        if(data==1){
							loadall();
                        }else{
							/* alert("Team name already exists."); */
                            loaderHide();
                            $('#alert').modal('show');
                        }
					}
				});

			}
            function closemodel(){
                    loadall();
            }
        /* ------------------------------------------------------------------------------------------------------------------------- */



/* ---------------------------------------------------------------------------------------------------- */
var prodCurrencymain=[];

var flg1=0;
function create_procur_array(){
	var unselectedProd=[]
	var prodCurrencyObj={};
	prodCurrencymain=[];
	var aa1="";
	var aa=[];
	var successFlag =0;
	var flg=0;
	$(".prod_leaf_node input[type=checkbox]").each(function(){
		if($(this).prop("checked") == true){
			var prod=$(this).val();
			var cur=[];
			var length = $(this).closest(".col-md-5").find(".multiselect input[type=checkbox]").length;
			var length2 =0;
			if($(this).closest(".col-md-5").find(".multiselect").length > 0){
				$(this).closest(".col-md-5").find(".multiselect input[type=checkbox]").each(function(){
					if($(this).prop("checked")==true){
						cur.push($(this).val());
						if(parseInt(length) != parseInt(length2)){
							$(this).closest(".col-md-5").find(".prod_leaf_node").removeClass("error");
							$(this).closest(".col-md-5").removeAttr("style");
						}
					}else{
						length2 = length2+1;
						if(parseInt(length) == parseInt(length2)){
							$(this).closest(".col-md-5").find(".prod_leaf_node").addClass("error");
							$(this).closest(".col-md-5").css({"border": "1px solid red"});
						}else{
							$(this).closest(".col-md-5").find(".prod_leaf_node").removeClass("error");
							$(this).closest(".col-md-5").removeAttr("style");
						}
					}
				})
			}else{
				length2 =-1
			}
			if(parseInt(length) == parseInt(length2)){
				successFlag =1;
			}
			prodCurrencyObj={"prod" :prod,"currency":cur.toString()};
			prodCurrencymain.push(prodCurrencyObj);
		}else{
			unselectedProd.push($(this).val());
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
	</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini admin-team-page">
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
            <input type="hidden" id="hid_teamid" name="hid_teamid"/>
			<div class="col-lg-12 column">
				<div class="row header1">
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Team List"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Teams', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
							<h2>Team List</h2>
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						 <div class="addBtns">
							<!--<a href="#modal_upload" data-toggle="modal" class="addExcel" >
								<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
							</a> -->
							<a href="#addmodal" onclick="addmodal()"  id='teambtn' class="addPlus" >
								<img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>


				<table class="table">
					<thead>
						<tr>
							<th width="5%" >Sl No</th>
                            <th width="25%">Team</th>
                            <th width="25%">Department</th>
                            <!--<th width="10%">Office Location</th>
                            <th width="15%">Products</th>
                            <th width="10%">Business Location</th>
                            <th width="10%">Industry</th>-->
                            <th width="25%">Sell Type</th>
							<th width="5%"></th>
							<th width="5%"></th>
						</tr>
					</thead>
					<tbody id="tablebody">
					</tbody>
				</table>
			</div>
			<div id="view_modal" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<span class="close"  onclick="cancel()">x</span>
							<h4 class="modal-title view_secton_title"></h4>
						</div>
						<div class="modal-body view_secton">

						</div>
					</div>
				</div>
			</div>
			<div id="editmodal" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<form name="editTeamModal" class="form">
							<div class="modal-header">
								<span class="close"  onclick="cancel()">x</span>
								<h4 class="modal-title edit_secton_title"></h4>
							</div>
							<div class="modal-body section1">
                                <div class="row">
									<div class="col-md-2">
										<label for="addTeamE">Team*</label>
									</div>
									<div class="col-md-10">
										<input type="text" name="addTeamE" id="addTeamE" class="form-control"/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<label for="deptE">Department*</label>
									</div>
									<div class="col-md-10">
										<select type="text" id="deptE" class="form-control"></select>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<label>Sell Type/Support Process*</label>
									</div>
									<div class="col-md-10">
										<div id="sellTypeE" class="multiselect_sellE">

										</div>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="modal-body section2">
								<div class="row">
									<div class="pull-left">
										<div id="tree-container_oflocE" class="tree-view"></div>
									</div>
									<div class="pull-right">
										<div class="displayChecked" id="display_locE">
											<ol type="1"></ol>
										</div>
									</div>
								</div>
								<div class="row">
									<center><span class="error-alert loc-list"></span></center>
								</div>
							</div>
							<div class="modal-body section3">
                                <div class="row">
									<div class="pull-left">
										<div id="tree-container_busE" class="tree-view"></div>
									</div>
									<div class="pull-right">
										<div class="displayChecked" id="display_bussE">
											<ol type="1"></ol>
										</div>
									</div>
								</div>
								<div class="row">
									<center><span class="error-alert business-list"></span></center>
								</div>
							</div>
							<div class="modal-body section4">
                                <div class="row">
									<div class="pull-left">

										<div id="tree-container_indE" class="tree-view"></div>
									</div>
									<div class="pull-right">
										<div class="displayChecked" id="display_indE">
											<ol type="1"></ol>
										</div>
									</div>
								</div>
								<div class="row">
									<center><span class="error-alert industry-list"></span></center>
								</div>
							</div>
							<div class="modal-body section5">
								<div class="row">
									<div class="pull-left" >
										<div id="tree-container_productsE" class="tree-view"></div>
									</div>
									<div class="pull-right">
										<div class="displayChecked" id="display_prodE">
											<ol type="1"></ol>
										</div>
									</div>
								</div>
								<div class="row">
									<center><span class="error-alert prod-curncy"></span></center>
								</div>
                                <!-- chnages made -->
								<div class="row" style="display: none">
									<div class="col-md-12">
										<input type="checkbox" id="cust_manageE" name="cust_manageE">
										<label for="cust_manageE">Customer Managment</label>
									</div>
								</div>
							</div>
							<div class="modal-body section6">
								<div class="row" id="currency_value_listE">

								</div>
							</div>
                            <div class="modal-footer section1" >
								<button type="button" onclick="edit_section1P()" class="btn btn-default">Proceed</button>
								<button type="button" onclick="cancel()" class="btn btn-default" >Cancel</button>
							</div>
							<div class="modal-footer section2" >
								<button type="button" onclick="edit_section2B()" class="btn btn-default">Back</button>
								<button type="button" onclick="edit_section2P()" class="btn btn-default">Proceed</button>
								<button type="button" onclick="cancel()" class="btn btn-default" >Cancel</button>
							</div>
							<div class="modal-footer section3" >
								<button type="button" onclick="edit_section3B()" class="btn btn-default">Back</button>
								<button type="button" onclick="edit_section3P()" class="btn btn-default">Proceed</button>
								<button type="button" onclick="cancel()" class="btn btn-default" >Cancel</button>
							</div>
							<div class="modal-footer section4" >
								<button type="button" onclick="edit_section4B()" class="btn btn-default">Back</button>
								<button type="button" onclick="edit_section4P()" class="btn btn-default">Proceed</button>
								<button type="button" onclick="cancel()" class="btn btn-default" >Cancel</button>
							</div>
							<div class="modal-footer section5" >
								<button type="button" onclick="edit_section5B()" class="btn btn-default">Back</button>
								<!--<button type="button" id="getProdData" class="btn btn-default">Proceed</button>-->
								<button type="button" onclick="edit_section5P()" class="btn btn-default">Proceed</button>
								<button type="button" onclick="cancel()" class="btn btn-default" >Cancel</button>
							</div>
							<input type="hidden" id="hiden_row_val"/>
							<div class="modal-footer section6" >
								<button type="button" onclick="edit_section6B()" class="btn btn-default">Back</button>
								<button type="button" onclick="update()" class="btn btn-default">Save</button>
								<button type="button" onclick="cancel()" class="btn btn-default" >Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="addmodal" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<form name="addTeamModal" class="form">
							<div class="modal-header">
								<span class="close" onclick="cancel()">x</span>
								<h4 class="modal-title">Add Team</h4>
							</div>
							<div class="modal-body section1">
                                <div class="row">
									<div class="col-md-2">
										<label for="addTeam">Team*</label>
									</div>
									<div class="col-md-10">
										<input type="text" name="addTeam" id="addTeam" class="form-control"/>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<label for="dept">Department*</label>
									</div>
									<div class="col-md-10">
										<select type="text" id="dept" class="form-control"></select>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<label>Sell Type/Support Process*</label>
									</div>
									<div class="col-md-10">
										<div id="sellType" class="multiselect_sell">
											<ul style="list-style: none;">

											</ul>
										</div>
										<span class="error-alert"></span>
									</div>
								</div>
							</div>
							<div class="modal-body section2">
								<div class="row">
									<div class="col-md-12 alert alert-info">
										<i><i class="fa fa-info-circle" aria-hidden="true"></i><label for="addLocation">Please choose an Office Location for the Team *</label> </i>
									</div>
									<div class="pull-left">

										<div id="tree-container_ofloc" class="tree-view" ></div>
									</div>
									<div class="pull-right">
										<div class="displayChecked" id="display_loc">
											<ol type="1"></ol>
										</div>
									</div>
								</div>
								<div class="row">
									<center><span class="error-alert office-list"></span></center>
								</div>
							</div>
							<div class="modal-body section3">
                                <div class="row">
									<div class="col-md-12 alert alert-info">
										<i><i class="fa fa-info-circle" aria-hidden="true"></i><label for="display_business">Please choose a Business Location for the Team *</label> </i>
									</div>
									<div class="pull-left">

										<div id="tree-container_bussloc" class="tree-view" ></div>
									</div>
									<div class="pull-right">
										<div class="displayChecked" id="display_business">
											<ol type="1"></ol>
										</div>
									</div>
								</div>
								<div class="row">
									<center><span class="error-alert business-list"></span></center>
								</div>
							</div>
							<div class="modal-body section4">
                                <div class="row">
									<div class="col-md-12 alert alert-info">
										<i><i class="fa fa-info-circle" aria-hidden="true"></i><label for="display_indus">Please choose an Industry for the Team *</label> </i>
									</div>
									<div class="pull-left">

										<div id="tree-container_indus" class="tree-view" ></div>
									</div>
									<div class="pull-right">
										<div class="displayChecked" id="display_indus">
											<ol type="1"></ol>
										</div>
									</div>
								</div>
								<div class="row">
									<center><span class="error-alert industry-list"></span></center>
								</div>
							</div>
							<div class="modal-body section5">
                                <div class="row">
									<div class="col-md-12 alert alert-info">
										<i><i class="fa fa-info-circle" aria-hidden="true"></i><label for="addProduct">Please choose a Product for the Team *</label></i>
									</div>
									<div class="pull-left" >

										<div id="tree-container_products" class="tree-view"></div>
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
								<div class="row" style="display: none">
									<div class="col-md-12">
										<input type="checkbox" id="cust_manage" name="cust_manage">
										<label for="cust_manage">Customer Managment</label>
									</div>

								</div>
							</div>
							<div class="modal-body section6">
								<div class="row" id="currency_value_list">

								</div>
							</div>
							<div class="modal-footer section1" >
								<button type="button" onclick="add_section1P()" class="btn btn-default">Proceed</button>
								<button type="button" onclick="cancel()" class="btn btn-default" >Cancel</button>
							</div>
							<div class="modal-footer section2" >
								<button type="button" onclick="add_section2B()" class="btn btn-default">Back</button>
								<button type="button" onclick="add_section2P()" class="btn btn-default">Proceed</button>
								<button type="button" onclick="cancel()" class="btn btn-default" >Cancel</button>
							</div>
							<div class="modal-footer section3" >
								<button type="button" onclick="add_section3B()" class="btn btn-default">Back</button>
								<button type="button" onclick="add_section3P()" class="btn btn-default">Proceed</button>
								<button type="button" onclick="cancel()" class="btn btn-default" >Cancel</button>
							</div>
							<div class="modal-footer section4" >
								<button type="button" onclick="add_section4B()" class="btn btn-default">Back</button>
								<button type="button" onclick="add_section4P()" class="btn btn-default">Proceed</button>
								<button type="button" onclick="cancel()" class="btn btn-default" >Cancel</button>
							</div>
							<div class="modal-footer section5" >
								<button type="button" onclick="add_section5B()" class="btn btn-default">Back</button>
								<button type="button" onclick="add_section5P()" class="btn btn-default">Proceed</button>
								<button type="button" onclick="cancel()" class="btn btn-default" >Cancel</button>
							</div>
							<div class="modal-footer section6" >
								<button type="button" onclick="add_section6B()" class="btn btn-default">Back</button>
								<button type="button" onclick="addsave()" class="btn btn-default">Save</button>
								<button type="button" onclick="cancel()" class="btn btn-default" >Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
            <div id="alert" class="modal fade" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
						<div class="modal-body">
							<div class="row">
								<center>
									<span>Team Name Already Exists.</span>
									<br>
									<br>
									<input type="button" class="btn" data-dismiss="modal" onclick="closemodel()"  value="Ok">
								</center>
							</div>
						</div>
                    </div>
                </div>
            </div>
		</div>
		<?php require 'footer.php' ?>

	</body>
</html>
