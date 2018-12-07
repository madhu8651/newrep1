<!DOCTYPE html>
<html lang="en">
	<head>
	<?php $this->load->view('scriptfiles'); ?>
	<style>
		.addArrt{
			clear: both;
			background: #eee;
			padding: 3px 5px;
			top: 6px;
			position: relative;
		}
		.ui-datepicker-month{
			margin-left: 19px!important;
			border: 1px solid lightgrey!important;
			border-radius: 5px!important;
			margin-right: 2px!important;
		}
		.ui-datepicker-year{
			border-radius: 5px;
			border-color: lightgrey;
		}
		.modal h4{
			text-transform:capitalize;
		}
         /*-------------------*/
        .checkbox.custom {
            float: left;
            margin: 6px;
            padding: 0;
            display: block;
            width: 200px;
			z-index:99;
            margin-bottom: 20px;
            position: absolute;
          }

          input[type="checkbox"].custom {
            margin-left: 0;
            padding:0;
          }

          input[type=checkbox].css-checkbox {
            position:absolute;
            overflow:hidden;
            clip:rect(0 0 0 0);
            height:1px;
            width:1px;
            margin:-1px;
            padding:0px;
            border:0;
          }

          input[type=checkbox].css-checkbox+label.css-label,
          input[type=checkbox].css-checkbox+label.css-label-red,
          input[type=checkbox].css-checkbox+label.css-label-yellow,
          input[type=checkbox].css-checkbox+label.css-label-blue,
          input[type=checkbox].css-checkbox+label.css-label-purple{
            padding-left: 36px;
            height:30px;
            width:100%;
            display:inline-block;
            line-height:27px;
            background-repeat:no-repeat;
            background-position:0 0;
            font-size:13px;
            vertical-align:middle;
            cursor:pointer;
            opacity:1;
            -webkit-transition: all .2s ease-out;
            -moz-transition: all .2s ease-out;
            -ms-transition: all .2s ease-out;
            -o-transition: all .2s ease-out;
            transition: all .2s ease-out;
          }

          input[type=checkbox].css-checkbox+label.css-label:hover,
          input[type=checkbox].css-checkbox+label.css-label-red:hover,
          input[type=checkbox].css-checkbox+label.css-label-yellow:hover,
          input[type=checkbox].css-checkbox+label.css-label-blue:hover,
          input[type=checkbox].css-checkbox+label.css-label-purple:hover{
            opacity:0.5;
          }

          input[type=checkbox].css-checkbox:checked+label.css-label,
          input[type=checkbox].css-checkbox:checked+label.css-label-red,
          input[type=checkbox].css-checkbox:checked+label.css-label-yellow,
          input[type=checkbox].css-checkbox:checked+label.css-label-blue,
          input[type=checkbox].css-checkbox:checked+label.css-label-purple{
            background-position:0 -30px;
          }

          .css-label-blue {
            background-image:url(<?php echo base_url(); ?>css/check4.png);
                background-size: 30px 59px;
          }
	</style>
<script>

    var testData = [];
    $(document).ready(function(){
        loaderShow();
		$.ajax({
			type : "post",
			url : "<?php echo site_url('admin_leadsourceController/get_lead_source');?>",
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
	});
    function lead_source(){
		/* -------------------List view table render------------------ */
		$('#ListView table').dataTable().fnDestroy();
		var html="";
        var j=1;
		for (i=0; i<testData.length; i++){
		    if(testData[i].name!="Lead Source"){
               html +="<tr><td>"+(j)+"</td><td>"+testData[i].name+"</td><td>"+testData[i].id+"</td></tr>";
		    }else{
		        j--;
		    }
            j++;
		}
		$("#ListView tbody").html("").html(html);
		$('#ListView table').DataTable();
		$('#ListView table').removeAttr("style");
		
		/* -------------------Grid view table render------------------ */
		org_chart = $('#orgChart').orgChart({
			data: testData,
			showAddControl: true,
			showDeleteControl: false,
			allowEdit: false,
			showEditControl: true
		});
		$(".node").each(function(){
			if(!$(this).hasClass("child0")){
				$(this).find('.addArrt').hide();
			}
		});
		loaderHide();
		/* -------------------Hide Zoom button------------------ */
		$(".nav.nav-tabs li").each(function(){
			$(this).click(function(){
				if($(this).text() == "List View"){
					$(".pull-right.zoom").fadeOut();
				}else{
					$(".pull-right.zoom").fadeIn();
				}
			})
		})
	}
	var toDate1Hidden ="";
	function cancel(){
		if($('#addArrtbutePop').css("display") == "block"){
			if( toDate1Hidden == "" ){
				$('#fromDate1').data().DateTimePicker.date(null);
				$('#toDate1').data().DateTimePicker.date(null);
				$('#fromDate1').data().DateTimePicker.destroy();
				$('#toDate1').data().DateTimePicker.destroy();
			}
		}
        $('.modal').modal('hide');
		$('.modal input[type="text"], textarea').val('');
		$('.modal select').val($('.modal select option:first').val());
		$('.modal input[type="checkbox"]').prop('checked', false);
		$('.error-alert').text('');
        window.location.reload(true);

	}

	function editsource(source){
		$('#editmodal').modal('show');
		$('#edit_parent').html(source.parent_name);
        $('#parent_id').val(source.parent);
		$('.edit_lead_title').text(source.name);
		$('#edit_lead').val(source.name);
		$('#hierarchy_id').val(source.id);
		$('#hid').val(source.hid);
		setTimeout(function(){
			$("#edit_lead").focus();
		}, 500);
	}
	function edit_save(){
	    loaderShow();
		if($.trim($("#edit_lead").val())==""){
			$("#edit_lead").closest("div").find("span").text("Lead Source is required.");
			$("#edit_lead").focus();
            loaderHide();
			return;
		}else if(!validate_name($.trim($("#edit_lead").val()))) {
			$("#edit_lead").closest("div").find("span").text("No special characters allowed (except &).");
			$("#edit_lead").focus();
            loaderHide();
			return;
		}else if(!firstLetterChk($.trim($("#edit_lead").val()))) {
			$("#edit_lead").closest("div").find("span").text("First letter should not be Numeric or Special character.");
			$("#edit_lead").focus();
            loaderHide();
			return;
		}else{
			$("#edit_lead").closest("div").find("span").text("");
		}

		var editdata={};
		editdata.hierarchy_id = $.trim($('#hierarchy_id').val());
		editdata.hid = $.trim($('#hid').val());
		editdata.parent_id = $.trim($('#parent_id').val());
		editdata.node = $.trim($('#edit_lead').val());

        if($('#activeChk').prop('checked')==true){
				editdata.state =1;
    	}else{
    		editdata.state =0;
    	}
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_leadsourceController/update_source')?>",
			data : JSON.stringify(editdata),
			dataType : 'json',
			cache : false,
			success : function(data){
				loaderHide();
				//cancel();
				if(error_handler(data)){
					return;
				}
				if(data=='0'){
                    $("#alert").modal("show");
					$("#alert center span").text("Lead Source Already Defined");
					return;
                }else{
					cancel();
                    testData = [];
				    testData = JSON.parse(JSON.stringify(data));
				    lead_source();
                }
			}
		});
	}
	function addsource(source){
		$('#addsub').modal('show');
		$('.add_sub_text').html(source.name);
		$('#parent_id').val(source.id);
		setTimeout(function(){
			$("#add_lead_sub").focus();
		}, 500);
	}
	function add_source(){
	    loaderShow();
		if($.trim($("#add_lead_sub").val())==""){
			$("#add_lead_sub").closest("div").find("span").text("Lead Source is required.");
			$("#add_lead_sub").focus();
            loaderHide();
			return;
		}else if(!validate_name($.trim($("#add_lead_sub").val()))) {
			$("#add_lead_sub").closest("div").find("span").text("No special characters allowed (except &).");
			$("#add_lead_sub").focus();
            loaderHide();
			return;
		}else if(!firstLetterChk($.trim($("#add_lead_sub").val()))) {
			$("#add_lead_sub").closest("div").find("span").text("First letter should not be Numeric or Special character.");
			$("#add_lead_sub").focus();
            loaderHide();
			return;
		}else{
			$("#add_lead_sub").closest("div").find("span").text("");
		}

		var adddata={};
		adddata.parent_id = $.trim($('#parent_id').val());
		adddata.sourcename = $.trim($('#add_lead_sub').val());
		adddata.parent_name = $.trim($('.add_sub_text').html());
        if($('#activeChk').prop('checked')==true){
				adddata.state =1;
    	}else{
    		adddata.state =0;
    	}
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_leadsourceController/post_lead_data')?>",
			data : JSON.stringify(adddata),
			dataType : 'json',
			cache : false,
			success : function(data){
			    loaderHide();
				if(error_handler(data)){
					return;
				}
				if(data=='0'){
                    $("#alert").modal("show");
					$("#alert center span").text("Lead Source Already Defined");
					return;
                }else{
					cancel();
                    testData = [];
				    testData = JSON.parse(JSON.stringify(data));
				    lead_source();
                }
			}
		});
	}

	function load_currency(value){ /* // on load fill currency */
		$.ajax({
			type: "POST",
			url:"<?php echo site_url('admin_leadsourceController/get_currency')?>",
			dataType:'json',
			success: function(data){
				if(error_handler(data)){
					return;
				}
				var select = $("#add_cat"), options = "<option value=''>Select Currency</option>";
				select.empty();
				for(var i=0;i<data.length; i++){
					options += "<option value='"+data[i].currency_id+"'>"+ data[i].currency_name +"</option>";
				}
			   select.append(options);
               $('#add_cat').val(value);
               loaderHide();
			 }
		});
	}

    function actvChk(e){
        loaderShow();
		var addObj={};
		if(e=='load'){
			addObj.state =0;
		}
		else if(e == 'click'){
			if($('#activeChk').prop('checked')==true){
				addObj.state =1;
			}else{
				addObj.state =0;
			}
		}
		$.ajax({
			type : "post",
			data : JSON.stringify(addObj),
			url : "<?php echo site_url('admin_leadsourceController/get_inactive_src');?>",
			dataType : "json",
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
				testData =data;
				lead_source();
			}
		});
    }

	var regex  = /^\d+(?:\.\d{1,2})$/;
	var timeValChk  = /^([0-9]{2}):[0-5][0-9]$/;

	function addArrtbute(nodeid, name,parentid){
	    loaderShow();
		$("#addArrtbutePop").find("#title_name").text(name);
	    addObj={};
        addObj.nodeid=nodeid;
        addObj.parentid=parentid;
        var value="";
        $('#hid_nodeid').val(nodeid);
        $('#hid_parentid').val(parentid);
        load_currency(value);
        $.ajax({
			type: "POST",
			url:"<?php echo site_url('admin_leadsourceController/get_attr_data')?>",
			dataType:'json',
            data : JSON.stringify(addObj),
			success: function(data){
				if(error_handler(data)){
					return;
				}
				$("#budget").attr("disabled","disabled");
				$("#add_cat").change(function(){
					if($(this).val() != ""){
						$("#budget").removeAttr("disabled");
						$('#lead_lag_time').siblings(".error-alert").text("");
						$('#first_access_time').siblings(".error-alert").text("");
					}else{
						$("#budget").attr("disabled","disabled");
						$("#budget").val("");
						$('#lead_lag_time').siblings(".error-alert").text("");
						$('#first_access_time').siblings(".error-alert").text("");
					}
				})
				$('#lead_lag_time .glyphicon.glyphicon-remove').on('click', function(){
					$('#lead_lag_time input[type=text]').val("");
					$('#lead_lag_time').siblings(".error-alert").text("");
				});
                $('#first_access_time .glyphicon.glyphicon-remove').on('click', function(){
					$('#first_access_time input[type=text]').val("");
					$('#first_access_time').siblings(".error-alert").text("");
				});
				$(function() {
					var regExp = /[a-z]/i;
					$('#lead_lag_time input[type=text]').on('keydown keyup', function() {
						$(this).val($(this).val().replace(/(\d{2})\:?(\d{2})/,'$1:$2'));
					})
					$('#lead_lag_time input[type=text]').on('keydown keyup', function(e) {
						var value = String.fromCharCode(e.which) || e.key;
						if(value == "a" || value == "b"|| value == "c"|| value == "d" || value == "e" || value == "f"|| value == "g" || value == "h" || value == "i"|| value == "n"){

						}else{
							if (regExp.test(value)) {
								e.preventDefault();
								return false;
							}
						}
						if (!timeValChk.test($('#lead_lag_time input[type=text]').val())){
							$('#lead_lag_time').siblings(".error-alert").text("Invalid Time format.");
							return;
						}else{
							$('#lead_lag_time').siblings(".error-alert").text("");
						}
					});
				});
                $(function() {
					var regExp = /[a-z]/i;
					$('#first_access_time input[type=text]').on('keydown keyup', function() {
						$(this).val($(this).val().replace(/(\d{2})\:?(\d{2})/,'$1:$2'));
					})
					$('#first_access_time input[type=text]').on('keydown keyup', function(e) {
						var value = String.fromCharCode(e.which) || e.key;
						if(value == "a" || value == "b"|| value == "c"|| value == "d" || value == "e" || value == "f"|| value == "g" || value == "h" || value == "i"|| value == "n"){

						}else{
							if (regExp.test(value)) {
								e.preventDefault();
								return false;
							}
						}
						if (!timeValChk.test($('#first_access_time input[type=text]').val())){
							$('#first_access_time').siblings(".error-alert").text("Invalid Time format.");
							return;
						}else{
							$('#first_access_time').siblings(".error-alert").text("");
						}
					});
				});
                if(data=="yes"){ 
                        $("#alert").modal("show");
					    $("#alert center span").text("Please Active Parent Node First");
					    return;
                }else if(data==0){
				        $('#addArrtbutePop').modal('show');
					 $('#toDate1 input[type=text]').prop( "disabled", true );
                     $("#fromDate1 input[type=text]").val(moment(new Date()).format("DD-MM-YYYY HH:mm"));
					 $(function () {
						$('#fromDate1').datetimepicker({
							ignoreReadonly: true,
							allowInputToggle:true,
							format: 'DD-MM-YYYY HH:mm'
						});
						$('#toDate1').datetimepicker({
							useCurrent: false, /* //Important! See issue #1075 */
							ignoreReadonly: true,
							allowInputToggle:true,
							format: 'DD-MM-YYYY HH:mm'
						});

						$("#fromDate1").on("dp.change", function (e) {
							$('#toDate1').data("DateTimePicker").minDate(e.date);
							$('#toDate1 input[type=text]').prop( "disabled", false );
							$("#toDate1 input[type=text]").val("");
						});
						$("#toDate1").on("dp.change", function (e) {
							/* //$('#fromDate1').data("DateTimePicker").maxDate(e.date); */
						});
					});

					$(function() {
						var regExp = /[a-z]/i;
						$('#budget').on('keydown keyup', function(e) {
							var value = String.fromCharCode(e.which) || e.key;
							if(value == "a" || value == "b"|| value == "c"|| value == "d" || value == "e" || value == "f"|| value == "g" || value == "h" || value == "i"|| value == "n"){
							}else{
								if (regExp.test(value)) {
									e.preventDefault();
									return false;
								}
							}
							if (!regex.test($('#budget').val())){
								$("#budget").closest("div").find("span").text("Decimal value is required.");
								return;
							}else{
								$("#budget").closest("div").find("span").text("");
							}
						});
					});
                    loaderHide();

				}else{
					$("#budget").removeAttr("disabled");
					$("#add_cat").change(function(){
						if($(this).val() == data[0].currency_id){
							$("#budget").val(data[0].budget_value);
						}
					})

                    if(data[0].lead_cost_type=="campaign_budget"){
                         $('#chk1').prop('checked',true);
                         $('#add_cat').prop('disabled',false);
                    }else if(data[0].lead_cost_type=="cost_per_lead"){
                         $('#chk2').prop('checked',true);
                         $('#add_cat').prop('disabled',false);
                    }

					$('#addArrtbutePop').modal('show');
					$("#budget").val(data[0].budget_value);
					value=data[0].currency_id;
					load_currency(value);
					if(data[0].start_date == null){
						$("#fromDate1 input[type=text]").val("");
					}else{
						var start_date = data[0].start_date.split(" ");
						var sDate = start_date[0].split("-");
                        $("#fromDate1 input[type=text]").val(moment(data[0].start_date).format("DD-MM-YYYY HH:mm"));
					}

					if(data[0].end_date == null){
						$("#toDate1 input[type=text]").val("");
						toDate1Hidden = "";
					}else{
						var end_date = data[0].end_date.split(" ");
						var eDate = end_date[0].split("-");
                        $("#toDate1 input[type=text]").val(moment(data[0].end_date).format("DD-MM-YYYY HH:mm"));
						toDate1Hidden = moment(data[0].end_date).format("DD-MM-YYYY HH:mm");

					}
                    var a=data[0].lead_lag_time.split(":");
                    var llt=a[0]+":"+a[1];
					$('#lead_lag_time input[type=text]').val(llt);

                    var a1=data[0].first_access_time.split(":");
                    var llt1=a1[0]+":"+a1[1];
					$('#first_access_time input[type=text]').val(llt1);

					$(function () {
						$('#fromDate1').datetimepicker({
							ignoreReadonly: true,
							allowInputToggle:true,
							format: 'DD-MM-YYYY HH:mm'
						});

						$('#toDate1').datetimepicker({
							useCurrent: false, /* //Important! See issue #1075 */
							ignoreReadonly: true,
							allowInputToggle:true,
							format: 'DD-MM-YYYY HH:mm',
							minDate: data[0].start_date
						});


						$("#fromDate1").on("dp.change", function (e) {
							$('#toDate1').data("DateTimePicker").minDate(e.date);
							$("#toDate1 input[type=text]").val("");
							$('#toDate1').data().DateTimePicker.date(null);
						});

						$("#toDate1").on("dp.change", function (e) {
							/* $('#fromDate1').data("DateTimePicker").maxDate(e.date); */
						});
					});
                    //loaderHide();
				}
			}
		});
	}
    function chg(){

        if($('#chk1').prop('checked')==true){
              $('#chk2').prop('checked',false);
              $('#add_cat').prop('disabled',false);
        }else{
             $('#add_cat, #budget').prop('disabled',true);
             $('#add_cat, #budget').val("");
             $("#add_cat, #budget").closest("div").find("span").text("");
        }

     }
     function chg1(){
         if($('#chk2').prop('checked')==true){
                $('#chk1').prop('checked',false);
                $('#add_cat').prop('disabled',false);
         }else{
                $('#add_cat, #budget').prop('disabled',true);
                $('#add_cat, #budget').val("");
                $("#add_cat, #budget").closest("div").find("span").text("");
         }
     }

	function attr_save(){
	    loaderShow();

        var startdate=$.trim($('#fromDate1 input[type=text]').val());
        if(startdate ==""){
            $('#fromDate1').siblings(".error-alert").text("Start Date is required.");
            loaderHide();
			return;
        }else{
            $('#fromDate1').siblings(".error-alert").text("");
        }


        if($('#chk1').prop('checked')==true || $('#chk2').prop('checked')==true){

              if($("#add_cat").val()==""){
                $("#add_cat").closest("div").find("span").text("Please Select Currency");
      			$('#budget').val('0.00');
      			$("#budget").closest("div").find("span").text("");
                loaderHide();
          		return;
              }else{
      			$("#add_cat, #budget").closest("div").find("span").text("");
                  if (!regex.test($('#budget').val())){
          			$("#budget").closest("div").find("span").text("Decimal value is required.");
                      loaderHide();
          			return;
          		}else if($.trim($('#budget').val())== ''){
          		  $("#budget").closest("div").find("span").text("Budget is required.");
                    loaderHide();
          		  return;
          		}else if($.trim($('#budget').val())== '0.00'){
          		  $("#budget").closest("div").find("span").text("Budget is required.");
                    loaderHide();
          		  return;
          		}else{
          		    var budgetAmunt = $.trim($('#budget').val())
                        var budgetAmuntVal = $.trim(budgetAmunt).split(".");

                        if(budgetAmuntVal[1] == ""){
                          var bud= budgetAmuntVal[0]+'.00';
                          $.trim($('#budget').val(bud))
                        }else{
                          $("#budget").closest("div").find("span").text("");
                        }

          		}
              }
        }
        if($.trim($('#first_access_time input[type=text]').val()) == ""){
			$('#first_access_time').siblings(".error-alert").text("First access Time is required.");
            loaderHide();
			return;
		}else{
			var LLTime = $.trim($('#first_access_time input[type=text]').val()).split(":");

			if (!timeValChk.test($.trim($('#first_access_time input[type=text]').val()))){
				$('#first_access_time').siblings(".error-alert").text("Invalid Time format.");
                loaderHide();
				return;
			}else if((LLTime[1] > 60)){
				$('#first_access_time').siblings(".error-alert").text("Invalid Time format.");
                loaderHide();
				return;
			}else{
				$('#first_access_time').siblings(".error-alert").text("");
			}
		}
        if($.trim($('#lead_lag_time input[type=text]').val()) == ""){
			$('#lead_lag_time').siblings(".error-alert").text("Lead Lag Time is required.");
            loaderHide();
			return;
		}else{
			var LLTime = $.trim($('#lead_lag_time input[type=text]').val()).split(":");

			if (!timeValChk.test($.trim($('#lead_lag_time input[type=text]').val()))){
				$('#lead_lag_time').siblings(".error-alert").text("Invalid Time format.");
                loaderHide();
				return;
			}else if((LLTime[1] > 60)){
				$('#lead_lag_time').siblings(".error-alert").text("Invalid Time format.");
                loaderHide();
				return;
			}else{
				$('#lead_lag_time').siblings(".error-alert").text("");
			}

		}

        addObj={};
        if($('#activeChk').prop('checked')==true){
				addObj.state =1;
    	}else{
    		addObj.state =0;
    	}
        if($('#chk1').prop('checked')==true){
            addObj.costtype="campaign_budget";
        }else if($('#chk2').prop('checked')==true){
            addObj.costtype="cost_per_lead";
        }else{
            addObj.costtype="";
        }
        addObj.startdate=$.trim($('#fromDate1 input[type=text]').val());
        addObj.enddate=$.trim($('#toDate1 input[type=text]').val());
        addObj.budget=$.trim($('#budget').val());
        addObj.curid=$('#add_cat').val();
        addObj.leadlagtime=$.trim($('#lead_lag_time input[type=text]').val());
        addObj.firstaccesstime=$.trim($('#first_access_time input[type=text]').val());
        addObj.nodeid=$.trim($('#hid_nodeid').val());
        addObj.parentid=$.trim($('#hid_parentid').val());
		loaderShow();
        $.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_leadsourceController/post_attr_data')?>",
            dataType : 'json',
            data : JSON.stringify(addObj),
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
                cancel();
                testData = [];
				testData = JSON.parse(JSON.stringify(data));
                /* lead_source(); ----------- force refresh to avoid calender issue.. need to fix */
				location.reload();
			}
		});
	}



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
								<img src="<?php echo base_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Lead Source"/>
							</div>
						</span>
						<div class="style_video">
							<span class="glyphicon glyphicon-facetime-video " onclick="select_video('Lead_Source', 'video_body', 'Admin')" data-toggle="tooltip" data-placement="right" title="Click to play info video"></span>
						</div>
					</div>
					<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Lead Source</h2>
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
				
				
			   	<div class="container-fluid">
					<ul class="nav nav-tabs">
						<li class="active" ><a data-toggle="tab" href="#GridView">Grid View</a></li>
						<!--<li><a data-toggle="tab" href="#ListView">List View</a></li> -->
						<li>
							<div>
								<div class="checkbox custom pull-right">
								  <input id="activeChk" class="css-checkbox" type="checkbox" onchange="actvChk('click')"/>
								  <label for="activeChk" class="css-label-blue">Show Inactive Lead Source</label>
								</div>
							</div>
						</li>
					</ul>
				  <div id="orgChartContainer" class="tab-content tab_countstat">
						<div id="GridView" class="tab-pane fade in active" >
							<div id="orgChart"></div>
						</div>
						<div id="ListView" class="tab-pane fade">
							<table class="table" >
								<thead>
									<tr>
										<th class="table_header" >SL No</th>
										<th class="table_header" >Name</th>
										<th class="table_header" >ID</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
			</div>
			<div id="addArrtbutePop" class="modal fade" data-backdrop="static" >
				<div class="modal-dialog">
					<div class="modal-content">
						<form class="form">
							<div class="modal-header">
								<span class="close" onclick="cancel()">x</span>
								<h4 class="modal-title"><span id="title_name"></span> Attribute</h4>
							</div>
                            <input type="hidden" id="hid_nodeid"/>
                            <input type="hidden" id="hid_parentid"/>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-4">
										<label for="fromDate1">Date</label>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<div class='input-group date' id="fromDate1">
												<input type='text' class="form-control" placeholder="Start Date" readonly />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<span class="error-alert"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<div class='input-group date' id="toDate1">
												<input type='text' class="form-control" placeholder="End Date" readonly />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<span class="error-alert"></span>
										</div>

									</div>
								</div>
                                <div class="row">
									<div class="col-md-4">
									</div>
									<div class="col-md-4">

									   <input type="checkbox"  id="chk1" name="chk1" onclick="chg();" />
                                       <label for="chk1">Campaign Budget</label>
										<span class="error-alert"></span>
									</div>
									<div class="col-md-4">
										<input type="checkbox"  id="chk2" name="chk2" onclick="chg1();" />
                                        <label for="chk2">Cost Per Lead</label>
										<span class="error-alert"></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
									</div>
									<div class="col-md-4">
										<select name="adminContactDept" class="form-control" id="add_cat" disabled >

										</select>
										<span class="error-alert"></span>
									</div>
									<div class="col-md-4">
										<input type="text" class="form-control" id="budget"  placeholder="0.00" disabled/>
										<span class="error-alert"></span>
									</div>
								</div>
                                <div class="row">
									<div class="col-md-4">
										<label for="first_access_time_txt">Permissible FAT* <br>(First Action Time)</label>
									</div>
									<div class="col-md-4">
										<div class='input-group date' id="first_access_time">
											<input type='text' id="first_access_time_txt" class="form-control"  maxlength="5" placeholder="HH:MM"/>
											<span class="input-group-addon">
												<label class="glyphicon glyphicon-remove" for="first_access_time_txt" title="Clear"></label>
											</span>
										</div>
										<span class="error-alert"></span>
									</div>
									<div class="col-md-4">

									</div>
								</div>
								<div class="row">
                                    <div class="col-md-4">
										<label for="lead_lag_time_txt">Permissible LLT* <br>(Lead Lag Time)</label>
									</div>
									<div class="col-md-4">
										<div class='input-group date' id="lead_lag_time">
											<input type='text' id="lead_lag_time_txt" class="form-control"  maxlength="5" placeholder="HH:MM"/>
											<span class="input-group-addon">
												<label class="glyphicon glyphicon-remove" for="lead_lag_time_txt" title="Clear"></label>
											</span>
										</div>
										<span class="error-alert"></span>
									</div>
									<div class="col-md-4">

									</div>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" class="btn" onclick="attr_save()" value="Save" />
								<input type="button" class="btn" onclick="cancel()" value="Cancel" />
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="editmodal" class="modal fade" data-backdrop="static" >
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="editpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								<span class="close" onclick="cancel()">x</span>
								<h4 class="modal-title">Edit <span class="edit_lead_title"></span> Node</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="edit_parent">Parent Source</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<label id="edit_parent" style="font-weight:bold!important;"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="edit_lead">Lead Source</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<input type="text" class="form-control closeinput" name="edit_lead" id="edit_lead"/>
										<span class="error-alert"></span>
										<input type="hidden" class="form-control closeinput" name="hierarchy_id" id="hierarchy_id"/>
										<input type="hidden" class="form-control closeinput" name="hid" id="hid"/>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" class="btn" onclick="edit_save()" value="Save" />
								<input type="button" class="btn" onclick="cancel()" value="Cancel" />
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="addsub" class="modal fade" data-backdrop="static">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="addpopup" class="form" action="#" method="post" name="adminClient">
							<div class="modal-header">
								 <span class="close" onclick="cancel()">x</span>
								  <h4 class="modal-title">Add <span class="add_sub_text"></span> Node</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="add_sub">Parent Source</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<label class="add_sub_text" style="font-weight:bold!important;"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
										<label for="add_lead_sub">Lead Source</label>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-6 col-lg-8">
										<input type="text" class="form-control closeinput" name="add_lead_sub" id="add_lead_sub"/>
										<span class="error-alert"></span>
										<input type="hidden" id="parent_id" name="parent_id"/>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" class="btn" onclick="add_source()" value="Save">
								<input type="button" class="btn" onclick="cancel()" value="Cancel" >
							</div>
						</form>
					</div>
				</div>
			</div>
            <div id="alert" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
						<div class="modal-body">
							<div class="row">
								<center>
									<span></span>
									<br>
									<br>
									<input type="button" class="btn" data-dismiss="modal" value="Ok">
								</center>
							</div>
						</div>
                    </div>
                </div>
            </div>
		</div>
		<?php $this->load->view('footer'); ?>

	</body>
</html>
