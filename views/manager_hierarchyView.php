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
			position: absolute;
			margin-top: 10px;
		}
		.moduleInfo li {
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
.section-four{
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
	</style>
<script>
 var testData = {};
 var testData1 = [];
    $(document).ready(function(){
        //loaderShow();
      	$.ajax({
      		type : "post",
      		url : "<?php echo site_url('manager_hierarchyController/get_lead_source');?>",
      		dataType : "json",
      		cache : false,
      		success : function(data){
      		    testData1=data;
                convert(data);
      		}
      	});
    });

    /* function to find the root node from json data */
    function convert(data){
					var map = {};
					for(var i = 0,j=0; i < data.length; i++){
						 var obj = data[i];
                         if(obj.parent== ""){
                              var j=1;
                              var aa =obj.id;
                              var results = [] ;
                              findAllChildren(aa, results,'0',aa);
                              testData=results;
                              edit_tree(testData, 'industryE');
                         }
                    }
    }
    /* function to get all parent child and grand child of root node */
    function findAllChildren (id, results, depth,mainid) {
                          for (d in testData1) {
                              if(testData1[d].id==mainid){
                                results.push(testData1[d]);
                              }
                              if (testData1[d].parent == id) {
                                  testData1[d].depth = depth;
                                  results.push(testData1[d]);
                                  findAllChildren(testData1[d].id, results, depth + 1);
                              }
                          }
    }

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
    /* function to get parent child grandchild nested array to form tree view */
    function convert1(data){
					var map = {};
					for(var i = 0,j=0; i < data.length; i++){
						 var obj = data[i];
                         if(obj.parent== ""){
                              var j=1;
                              var aa =obj.id;
                         }
                        if(j==1){
                                obj.children= [];
                                obj.item = ({"id": data[i].id,"label": data[i].name,"checked": data[i].checked});
        						map[obj.id] = obj;
                                var parent = obj.parent || '-';
                                if(!map[parent]){
                                   map[parent] = {
        								children: []

        							};
        						}
                                map[parent].children.push(obj);
                        }

					}

					return map['-'].children;
	}


    function edit_tree(data, container){
                $("#"+container).html("");
            	var oflocArray = convert1(data);
            	var $ul = $('<ul class="mytree"></ul>');
            	getList(oflocArray, $ul);
            	$ul.appendTo("#"+container);
            	var display_list=[];

            	$("#"+container+" input[type=radio]").each(function(){

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
            				displayList($(this).val());
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

	</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini user-hierarchy">
	<!--<div class="loader">
			<center><h1 id="loader_txt"></h1></center>
	</div>-->
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
							<img src="/images/new/i_off.png" onmouseover="/images/new/i_ON.png'" onmouseout="/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Office Location"/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Office Location</h2>
					</div>

					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
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
				<!--<div id="orgChartContainer">
					<div class="moduleInfo">
						<ul>
							<li><span class="modulename cxo"></span> CXO Module</li>
							<li><span class="modulename manager"></span> Manager Module</li>
							<li><span class="modulename sales"></span> Sales Module</li>
						</ul>
					</div>
					<div id="orgChart"></div>
				</div>-->
                <div class="col-md-9">
										<div id="industryE" class="tree-view"></div>
										 <span class="error-alert"></span>
				</div>
			</div>

		</div>
		<?php $this->load->view('footer'); ?>

	</body>
</html>
