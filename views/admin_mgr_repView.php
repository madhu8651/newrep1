<!DOCTYPE html>
<html lang="en">
	<head>	
	<?php $this->load->view('scriptfiles'); ?>
	<style>
		.user-hierarchy div.orgChart h2{
			margin-bottom:10px;
		}
		.user-hierarchy .modulename.cxo:hover{
			background-color: #2BBBAD!important;
		}
		.user-hierarchy .modulename.manager:hover{
			background-color: #e1bee7!important;
		}
		.user-hierarchy .modulename.sales:hover{
			background-color: #ffab00!important;
		}
		.user-hierarchy .modulename.cxo{
			background-color: #00695c!important;
		}
		.user-hierarchy .modulename.manager{
			background-color: #93C!important;
			padding: 6px 10px;
		}
		.user-hierarchy .modulename.sales{
			background-color: #ff6d00!important;
		}
		.user-hierarchy .modulename:hover{
			color:#FFF;	
		}
		.user-hierarchy .modulename{
			padding:6px 12px;
			color:#FFF;
			margin-right: 5px;
			border-radius: 50%;
		}
		.user-hierarchy .avt{
			width:60px;
			height:72px;
			float:left;
		}
		.user-hierarchy .content1{
			width: 125px;
			padding-left:10px;
			float: right;
		}
		
		.user-hierarchy div.orgChart div.node{
			max-width: 200px;
			min-width: 200px;
			min-height: 85px;
		}
		
		.moduleInfo {
			/* position: absolute; */
			margin-top: 10px;
		}
		.moduleInfo li {
			display: inline-block;
			height: 29px;
			line-height: 29px;
			list-style-type: none;
			width: 150px;
			margin-bottom: 5px;
			background: rgba(204, 204, 204, 0.19);
			border: 1px solid #ccc;
			border-radius: 5px;
		}
		.moduleInfo ul{
			padding-left: 15px;
		}
		.moduleInfo ul li span{
			padding: 3px 12px !important;
		}
	</style>
<script>
	/* Validation : first character digit */
	function firstLetter(name) {
		var nameReg = new RegExp(/^[a-zA-Z0-9]/);
		var valid = nameReg.test(name);
		if (!valid) {
			return false;
		} else {
			return true;
		}
	}
	function comment_validation(name) {
			var nameReg = new RegExp(/^[a-zA-Z0-9 $&:()#@\n_.,+%?-]*$/);
			var valid = nameReg.test(name);
			if (!valid) {
				return false;
			} else {
				return true;
			}
	}
	function compareContact(contact){
		if(contact[0] != ""){
			if(contact[0] == contact[1]){
				return "match"
			}else{
				return "diff"
			}
		}else{
			return "diff"
		}
	}
    var testData = [];
	function pageload(){
		$.ajax({
      		type : "post",
      		url : "<?php echo site_url('admin_mgr_repController/get_lead_source');?>",
      		dataType : "json",
      		cache : false,
      		success : function(data){
				if(error_handler(data)){
					return;
				}
                
				testData = JSON.parse(JSON.stringify(data));
      			lead_source();
      		}
      	});
	}
    $(document).ready(function(){
      	pageload();
    });



    function lead_source(data1){
        org_chart = $('#orgChart').orgChart({
			data: testData,
			showAddControl: true,
			showDeleteControl: false,
			allowEdit: false,
			showEditControl: true
		});

        $(".node").each(function(){
			if($(this).hasClass("child1")){
				$(this).find('div').hide();
				$(this).find('div.avt, div.content1').show();
				$(this).find('div.content1 h2').each(function(){
					if($(this).text() == 'Users'){
						$(this).parent(".content1").css({'margin':'auto',"float":"none"});
                        $(this).parent(".content1").append('<span class="fa fa-sitemap fa-3x"></span>');
					}
				})
			}
		});
        loaderHide();
	}
	function cancel(){
        $('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first').val());
		$('.modal input[type="radio"]').prop('checked', false);
		$('.modal input[type="checkbox"]').prop('checked', false);
		$('.error-alert').text('');
	}
	function editsource(source){
		$('#editmodal').modal('show');
		$('#edit_parent').html(source.parent_name);
		$('#edit_lead').val(source.name);
		$('#hierarchy_id').val(source.id);
		$('.edit_lead_title').text(source.name);
	}
	function edit_save(){
	    
	}
	function addsource(source){
		$('#addsub').modal('show');
		$('.add_sub_text').html(source.name);
		$('#parent_id').val(source.id);
	}
	function add_source(){
	    
	}
	function close_modal(){
		$('#modalstart1').modal('hide');	
		$('.modal .form-control').val("");
		$('.modal input[type="checkbox"], .modal input[type="radio"]').removeAttr('checked');
		$("#modal_upload").modal("hide");
		$('#modal_upload #files').val("");
		$('.leadsrcname').html("");
		$(".user_section").hide();
		$(".button_fetch").hide();
		$(".error-alert").text("");
	}
	function edit_tree(data, container){
		$("#"+container).siblings('.leadsrcname').text('');
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
		$("#"+container+"  input[type=radio]").each(function( index ){
			
			if(index == 0){
				$(this).hide();
			}
			$(this).click(function(){
				if($(this).prop('checked')==true){
					$("#"+container).siblings('.leadsrcname').text($(this).closest('label').text());
				}
			})
			if($(this).prop('checked')==true){
				$("#"+container).siblings('.leadsrcname').text($(this).closest('label').text());
			}
		})
		$("#"+container).append("<center><button id='clearSeclection'>clear</button></center>");
		$('#clearSeclection').click(function(){
			$("#"+container).find('input[type=radio]').prop('checked', false);
			$("#"+container).siblings('.leadsrcname').text('');
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
	</head>
	<body class="hold-transition skin-blue sidebar-mini user-hierarchy lcont-lead-page"> 
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
							<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="<?php echo base_url(); ?>images/new/i_ON.png" onmouseout="<?php echo base_url(); ?>images/new/i_off.png" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Reporting Tree"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Reporting_Tree', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Reporting Tree</h2>
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
					   <!--	<a  class="addExcel" onclick="addExl()" >
							<img src="<?php echo base_url(); ?>images/new/Xl_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Xl_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Xl_Off.png'" width="30px" height="30px"/>
						</a>-->
						<table class="pull-right zoom">
							<tr>
								<td>
									<span class="glyphicon glyphicon-zoom-in" data-toggle="tooltip" data-placement="bottom" title="Zoom in"></span>
								</td>
								<td>
								&nbsp;&nbsp;
								</td>
								<td>
									<span class="glyphicon glyphicon-zoom-out" data-toggle="tooltip" data-placement="bottom" title="Zoom out"></span>
								</td>
							</tr>						
						</table>
					</div>
					<div style="clear:both"></div>
				</div>
				<div id="orgChartContainer">
					<div class="moduleInfo">
						<ul>
							<!--
							****CXO module is not ready ****
							<li><span class="modulename cxo"></span> CXO Module</li>-->
							<li><span class="modulename manager"></span> Manager Module</li>
							<li><span class="modulename sales"></span> Executive Module</li>
						</ul>
					</div>
					<div id="orgChart"></div>
				</div>
			</div>
			
		</div>
		<?php /*$this->load->view('manager-exel-file-upload');*/ ?>
		<?php $this->load->view('footer'); ?>
	</body>
</html>
