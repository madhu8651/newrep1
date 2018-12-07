<style type="text/css">
.report-list-container OL,
.report-list OL { counter-reset: item }
.report-list{ margin-top: 12px; }

.report-list-container LI,
.report-list LI { display: block }

.report-list-container LI:before,
.report-list LI:before { content: counters(item, ".") " "; counter-increment: item }

.report-list-container li .tooltip,
.report-list-container li .tooltip-inner,
.report-list li .tooltip-inner,
.report-list li .tooltip-inner
{
	width:300px !important;
	max-width:300px !important;
}
.report-list-container{ 
	position: absolute;
	z-index: 50000;
	background: #ccc;
	width: 100%;
	height: 400px;
	overflow: auto;
	margin-top: 12px;
	padding: 20px;
}
.report-list-container .bs-example.scroll_bar {
	background: #fff;
}
.col-md-3 ol li{
	font-size: 14px;
    font-weight: 600;
    color: #6B7B89;
	margin-top: 6px;
}
.col-md-3 ol li a{
	color: #1F584E;
}
.outer_ul{
	margin-left: -35px;
}
.outer_ul1{
	margin-left: -35px;
}
.header_row{
	background: #DCDCDC;
    border-radius: 5px 5px 0 0;
	box-shadow: 1px 1px 5px #888282;
    margin-bottom: 2px;
}
.header_row h5{
    margin-top: 0;
    margin-left: -3px;
    padding-top: 6px;
    margin-bottom: 1px;
    color: #565656;
    font-weight: bold;
    text-align: center;
    padding-bottom: 5px;
    font-size: 19px;
}
.bs-example{
	border: 1px solid lightgrey;
    box-shadow: 0 0 3px #cfcaca;
	border-radius: 5px;
    padding-left: 2px;
    height: 300px;
    overflow: auto;
	margin-bottom: 18px;
}
#savedRep .bs-example{
	display:none;
}
.report-list li::before {content: counters(item, ".") " "; counter-increment: item; color: red;
  display: inline-block; 
  border-radius:5px;
  padding:1px 7px 1px 7px; 
  background:#DCDCDC;
  color:#565656;
  margin-right:4px;
  width: 28px;
  }
  .inner_li li::before {content: counters(item, ".") " "; counter-increment: item; color: red;
  
  padding:5px 5px 4px 3px; 
  font-size:12px;
  color:#565656;
  }


.scroll_bar::-webkit-scrollbar
{
	width: 8px;
	background-color:#A4A7A6;
}

.scroll_bar::-webkit-scrollbar-thumb
{
	border-radius: 10px;
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
	background-color: #F5F5F5;
}
</style>
<script>
var session_userId="<?php echo $_SESSION['uid']; ?> ";

  var display_outter=0;
$(document).ready(function(){

/*********************************** Code for version control**********************/
       /* function used to display reports depending on the version */
       if(versiontype=='premium'){
           display_outter=3;
        }else if(versiontype=='standard'){
           display_outter=2;
        }else{
           display_outter=1;
        }
/*********************************** Code forversion control**********************/


		$(function(){
		$('[rel="popover"]').popover({
			container: 'body',
			html: true,
			content: function () {
				var clone = $($(this).data('popover-content')).clone(true).removeClass('hide');
				return clone;
			}
		}).click(function(e) {
			e.preventDefault();
		});
	});
});

/* --------------------------------- */
function reportListDisplay(){
	$(".report-list-container").show();
	var isInside = false;
	
	$(".report-list-container").hover(function () {
		isInside = true;
	}, function () {
		isInside = false;
	});

	$(document).mouseup(function () {
		if (!isInside)
			$(".report-list-container").hide();
	});
}


$(document).ready(function(){
	/* loaderShow(); */

	$.ajax({
		type : "post",
		url : "<?php echo site_url('manager_standard_analytics/getreportlist')?>",
		dataType : "json",
		cache : false,
		success : function(data){

			if(error_handler(data)){
				return;
			}
		   edit_tree(data, "tree", 'null')
		},
		error:function(data){
			network_err_alert(data);
		}
	});
});

/* ------------------------ function to convert the json data in tree structure ---------------- */
function convert(data){
	var map = {};
	for(var i = 0; i < data.length; i++)
	{
				var obj = data[i];
				obj.children= [];
				map[obj.id] = obj;
				if(obj.parent==0)
				{
				  obj.parent="";
				}
				var parent = obj.parent || '-';
				if(!map[parent])
				{
					map[parent] = {
						children: []
					};
				}
				map[parent].children.push(obj);
	}
	return map['-'].children;
}

/* ------------------------ constructing tree structure ---------------- */
		function getList(item, $list, parentID) {
			if($.isArray(item)){
				$.each(item, function (key, value) {
					getList(value, $list, parentID);
				});
			}

			if(item){
/*********************************** Code for version control**********************/
                    if(item.versiontype == 'lite'){
                        var display_inner = 1;
                    }else if(item.versiontype == 'standard'){
                        var display_inner = 2;
                    }else if(item.versiontype == 'premium'){
                        var display_inner = 3;
                    }

/*********************************** Code for version control**********************/

        				if(item.name){
        				    if(display_outter >= display_inner){
            					/* -------------------------- */
            					arr = window.location.href.split("/");
            					report = arr[arr.length-3];
            					if(item.name.replace(/[^A-Z0-9]/ig, "_") == report ){
            						$("#remarks_descriction").html("<p><b>Note: </b>"+item.remarks+"</p>")
            					}
            					/* ------------------------------------ */
              					var $li = $('<li></li>');
              					if(item.remarks =="" ){
              						$li.append($("<a href ='#'>" + item.name + "</a>"));
              					}else{
              						$li.append($("<a data-toggle='tooltip' title='"+item.remarks+"' href ='<?php echo site_url('manager_standard_analytics/');?>"+item.name.replace(/[^A-Z0-9]/ig, "_")+"/"+session_userId.trim()+"/"+item.id+"_"+parentID+"' >" + item.name + "</a>"));
              					}

            			   }
                        }
                        if(display_outter >= display_inner){
                          /* console.log(display_outter +' -- '+item.name+' -- '+display_inner+' -- '+item.versiontype); */
              				if (item.children && item.children.length) {
              					var $sublist = $("<ol class= 'inner_li child-count-"+item.children.length+"'></ol>");
              					getList(item.children, $sublist, parentID)
              					$li.append($sublist);
              				}
              				$list.append($li)
                       }
			}
		}
    
    /* ---------------------------------------------------------------------------- */
    /*----------------------tree function ------------------------------*/
function toggleSection(id){
	$("#savedRep #"+id +" .scroll_bar").slideToggle();
	$("#"+id +" .row.header_row").find('i.fa').toggleClass('fa-chevron-down');
}

function edit_tree(data, container, saveLastNode){
    $("#"+container).html("");
	var oflocArray = convert(data);
	var counter =0;
	var main ="";
	var wrapper = "";
    console.log(oflocArray);
	for(i=0; i<oflocArray[0].children.length; i++){
		inner_container = container+i;
		wrapper = "";
		/* if(counter % 4 == 0 ){
			wrapper += 	'<div class="row report-list class'+inner_container+'">';
		} */
		if(container == "savedRep"){
			var display ="";
			if(i == 0){
				display ="style='display:block' ";
			}
		wrapper += 	'<div class="col-md-3 col-lg-3" id="'+container+oflocArray[0].children[i].id+'">'+
							'<div class="row header_row" onclick="toggleSection(\''+container+oflocArray[0].children[i].id+'\')">'+
								'<h5 data-toggle="tooltip" title="">'+oflocArray[0].children[i].name+'<i class="fa fa-chevron-right" aria-hidden="true" style="float: right;"></i></h5> '+
							'</div>'+
							'<div class="row bs-example scroll_bar" '+ display+' id="'+ inner_container +'">'+

							'</div>'+
						'</div>';
		
		}else{
               if(oflocArray[0].children[i].versiontype=='lite'){
                var display_inner=1;
              }else if(oflocArray[0].children[i].versiontype=='standard'){
                var display_inner=2;
              }else if(oflocArray[0].children[i].versiontype=='premium'){
                var display_inner=3;
              }

            if(display_outter>=display_inner)
            {
                wrapper += 	'<div class="col-md-3 col-lg-3" id="'+container+oflocArray[0].children[i].id+'">'+
							'<div class="row header_row">'+
								'<h5 data-toggle="tooltip" title="">'+oflocArray[0].children[i].name+'</h5> '+
							'</div>'+
							'<div class="row bs-example scroll_bar" id="'+ inner_container +'">'+

							'</div>'+
						'</div>';
           }

		}
		/* if(counter % 4 == 3 || counter+1 == oflocArray[0].children.length){
			wrapper += '</div>';

		} */

		$("#"+container).append(wrapper);
		var $ul = $('<ol class="outer_ul"></ol>');
		if(container == "savedRep"){
			getSaveList(oflocArray[0].children[i].children, $ul, oflocArray[0].children[i].id);
		}else{
			getList(oflocArray[0].children[i].children, $ul, oflocArray[0].children[i].id, "0");
		}
		$ul.appendTo("#"+inner_container);
		counter++;
		/* ----------------------------------------------------------------------------------------------------------- */
		$("#savedRep #"+inner_container+ " li").each(function(){
			if($(this).hasClass("active_link")){
				$(this).closest(".bs-example.scroll_bar").show();
				$(this).closest(".bs-example.scroll_bar").siblings(".row.header_row").find(".fa.fa-chevron-right").addClass('fa-chevron-down');
			}
		})
	}
}
</script>
<div>
	<div class="row report-list" id="tree"></div>
</div>