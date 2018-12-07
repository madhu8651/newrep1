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
			    margin-top: 2px;
			    right: 95px;
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
			display: inline-block;
		}
		.moduleInfo ul{
			padding-left: 15px;
		}
		.moduleInfo ul li span{
			padding: 3px 12px !important;
		}
		/*----------------------------------*/
		.list-view .user-hierarchy{
			text-align: justify !important;
		}
		
		.list-view .user-hierarchy .modulename.manager{
			
			padding: 3px 7px;
		}
		
		.list-view .user-hierarchy .modulename{
			padding:3px 8px;
			color:#FFF;
			margin-right: 5px;
			border-radius: 50%;
		}
		.full-width{
			width: 100%;
		}
		.table tbody tr:hover{
			cursor: pointer;
		}
	</style>
<script>
 	var testData = [];
    var testData1 = [];
    $(document).ready(function(){
      	grid_view();
      	$('#view_details').hide();

    });
    function grid_view(){
    	$(".zoom").fadeIn();
    	$.ajax({
      		type : "post",
      		url : "<?php echo site_url('manager_mgr_repController/get_lead_source');?>",
      		dataType : "json",
      		cache : false,
      		success : function(data){
      		    testData1=data;
      		    lead_source(data);
      		}
      	});
    }
    function list_view(){
    	$(".zoom").fadeOut();
    	$.ajax({
            type : "POST",
            url : "<?php echo site_url('manager_teamManagersController/get_manager_info'); ?>",
            dataType : 'json',
            cache : false,
            success : function(data){
	            $('.modal').modal('hide');
	            $('.closeinput').val('');
	            $('#tableBody').empty();
	            $('#view_details').show();
	            var row = "";
                for(i=0; i < data.length; i++ ){
	                var salesRepStatus="";
		                if(data[i].status == 0){
		                salesRepStatus = "<b style='color:red'>Inactive</b>"
	                }else{
	               		 salesRepStatus = "<b style='color:green'>Active</b>"
	                }
	                var modul = JSON.parse(data[i].modules);
	                var modul_name =""
	                if(modul.cxo != "0"){
						modul_name += '<span class="modulename cxo">C</span>';
	                }
	                if(modul.Manager != "0"){
	                	modul_name += '<span class="modulename manager">M</span>';
	                }
	                if(modul.sales != "0"){
	                	modul_name += '<span class="modulename sales">E</span>';
	                }
	                var rowdata = JSON.stringify(data[i]);
	                row += "<tr id='"+data[i].rep_id+"' onclick='view(this.id)'><td>" +"</td><td>"+ (i+1) +"</td><td>"+ data[i].repname+"</td><td>"+data[i].designation+"</td><td>"+data[i].teamname+"</td><td>"+
	                data[i].manager+"</td><td>"+salesRepStatus+"</td><td class='user-hierarchy'>"+modul_name+"</td><tr>";							
                }
                
	    	$('#tableBody').append(row);
	    						
	    }
	    });	
    }

    function view(val){
		viewadminInfo(val);
	}	
    /* function to find the root node from json data */
    function convert(data){
					var map = {};
					for(var i = 0,j=0; i < data.length; i++){
						 var obj = data[i];
                         if(obj.parent== "0"){
                              var j=1;
                              var aa =obj.id;
                              var results = [] ;
                              findAllChildren(aa, results,'0',aa);
                              testData=results;
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


    function lead_source(data1){

        var hierarchy=convert(data1);
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
                        $(this).parent(".content1").append('<a style="font-size:40px" class="fa fa-sitemap"></a>');
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

	</script>
	</head>
	<body class="hold-transition skin-blue sidebar-mini user-hierarchy">
	<div class="loader">
			<center><h1 id="loader_txt"></h1></center>
	</div>
		<?php $this->load->view('demo');  ?>
		<?php $this->load->view('manager_sidenav'); ?>
		<div class="content-wrapper body-content">
			<div class="col-lg-12 column">
				<div class="row header1">
					<div class="col-xs-2 col-sm-2 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >
								<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-toggle="tooltip" data-placement="bottom" title="View all the managers and executives under you and the reporting hierarchy."/>
							</div>
						</span>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Reporting Tree</h2>
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
				<div class="moduleInfo">
					<center>
						<ul>
							<li><span class="modulename cxo"></span> CXO Module</li>
							<li><span class="modulename manager"></span> Manager Module</li>
							<li><span class="modulename sales"></span> Executive Module</li>
						</ul>
					</center>					
				</div>
				<ul class="nav nav-tabs">					   
				    <li class="active"><a data-toggle="tab" onclick="grid_view()" href="#GridView">Grid View</a></li>
				    <li><a data-toggle="tab" onclick="list_view()" href="#ListView">List View</a></li>
				</ul>
				<div class="tab-content full-width">
					<div id="GridView" class="tab-pane fade in active">
				      	<div id="orgChartContainer">							
							<div id="orgChart"></div>
						</div>
				    </div>
				    <div id="ListView" class="tab-pane fade list-view">
				      	 <form class="form" action="#" method="post" name="adminClient">
							<div class="table-responsive">
								 <table class="table" id="tableTeam">
									<thead>  
										<tr>
											<th class="table_header"></th>
											<th class="table_header">SL No</th>
											<th class="table_header">Name</th>
											<th class="table_header">Designation</th>
											<th class="table_header">Team</th>
											<th class="table_header">Reporting Manager</th>
											<th class="table_header">User</th>
											<th class="table_header user-hierarchy">Module</th>
										</tr>
									</thead>  
									<tbody id="tableBody">
									
									</tbody>    
								</table>
							</div>
							<div>
								<center>
										<!--<input type="button" class="btn bt"  id="view_details" onclick="view()" value="View Details">-->
								</center>
							</div>    
		                </form>  
				    </div>					    
				</div>

				
			</div>

		</div>
		<?php $this->load->view('footer'); ?>

	</body>
</html>
