
<html lang="en">
<head>
<?php require 'scriptfiles.php' ?>
	<style>
	.go-top{
		    /* position: static;
			margin-left: 100%;
			margin-bottom: 31px; */
	}
	.go-top.visible{
		opacity: .75;
	}
	.go-top{
		-moz-border-radius: 7px 7px 0 0;
		-moz-transition: all .3s;
		-o-transition: all .3s;
		-webkit-border-radius: 7px 7px 0 0;
		-webkit-transition: all .3s;
		background: #434343;
		border-radius: 7px 7px 0 0;
		bottom: 4px;
		color: #fff;
		display: block;
		height: 9px;
		opacity: 0;
		padding: 13px 0 45px;
		position: fixed;
		right: 10px;
		text-align: center;
		text-decoration: none;
		transition: all .3s;
		width: 49px;
		z-index: 1040;
		right: 50px;
		border: 1px solid #434343;
	}
	.questions{
		min-height: 50px;
		border: 1px solid #ccc;
		box-shadow: 0px 3px 12px #ccc;
		padding: 15px 20px;
		transition: all 0.5s ease-in-out;
		margin-bottom: 20px;
	}
	.questions i.fa.fa-star-half-o {
		position: absolute;
		left: 2px;
		top: 1px;		
	}
	i.fa.fa-star-half-o {
		color:red;
	}
	</style>

	<script>
		$(document).ready(function(){
			/* ----------------- */
			$(".go-top").click(function(){
				 $("html, body").animate({ scrollTop: 0 }, "slow");
				return false;
			})
			
			$(".go-top").click(function() {
				$("body, html").animate({
					scrollTop: 0
				}, 500);
			});
			$(window).scroll(function() {
				
				var aTop = 500;
				if($(this).scrollTop()>=aTop){
					$(".go-top").addClass("visible");
				}else{
					$(".go-top").removeClass("visible");
				}
			})
			/* ----------------- */
			var addObj={};
				addObj.stage_id = "<?php echo $stage_id; ?>";
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('common_opportunitiesController/check_qualifier'); ?>",
				dataType : 'json',
			    data: JSON.stringify(addObj),
				cache : false,
				success : function(data){
					console.log(data)
					$("#lead_qualifier_name").text(JSON.stringify(data))
					if (data == false) {
						return ;
					}
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
			});
		});

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
						})
					}else{
						$(this).find("input:radio").each(function(){
							if($(this).is(":checked")){
								selectedQuestions++;	
								someObj.push({
									"ansid":$(this).closest("li").attr("id"), 
									"quesid":$(this).closest("ol").siblings("h4").attr("id"),
									"questype":$(this).closest("ol").find("input[type=hidden]").attr("value")
								});
								return false;
							}
						})
					}
				}else{
					if($(this).find("textarea").length > 0){
						$(this).find("textarea").each(function(){							
							someObj1.push({
								"ans":$(this).val(), 
								"quesid":$(this).closest(".col-lg-12").find("h4").attr("id")
							});
						})
					}else{
						$(this).find("input:radio").each(function(){
							if($(this).is(":checked")){	
								someObj.push({
									"ansid":$(this).closest("li").attr("id"), 
									"quesid":$(this).closest("ol").siblings("h4").attr("id"),
									"questype":$(this).closest("ol").find("input[type=hidden]").attr("value")
								});
							}
						})
					}
				}
				
			});
			if(totalQuestions != selectedQuestions){
				$("#mandatory").text("All Questions marked with an asterisk are manadatory.");
				return;
			}else{
				$("#mandatory").text("");
                mainObj.lead_qualifier_id=$("#lead_qualifier_id").val();
                mainObj.stage_id = "<?php echo $stage_id; ?>";
                mainObj.lead_id = "<?php echo $lead_cust_id; ?>";
                mainObj.rep_id = "<?php echo $owner_id; ?>";
                mainObj.opp_id= "<?php echo $opportunity_id; ?>";

                mainObj.type1_2=someObj;
                mainObj.type3=someObj1;

				console.log(mainObj);
				$("#submit_q_btn").attr('disabled', true);
				$.ajax({
					type:"post",
					cache:false,
					url:"<?php echo site_url('common_opportunitiesController/post_data');?>",
					dataType : 'json',
					data:JSON.stringify(mainObj),
					success: function (data) {
						if(error_handler(data)) {
							return;
						}
						$("#submit_q_btn").attr('disabled', true);
						if (data == 0) {
							/* alert("Attempted Qualifier was unsuccessful. Opportunity cannot be created."); */
							$('#qualifier_success').val('0');
							$('#confirm_modal').modal('show');
							$('#confirm_modal .modal-body p').text('Attempted Qualifier was unsuccessful. Opportunity cannot be created.');
						} else if (data == 1) {
							submit_opp_final();
						}
					}
				});
			}
		}
		
		function submit_opp_final() {
			var addObj={};
			addObj.opportunity_id 	= "<?php echo $opportunity_id; ?>";
			addObj.opportunity_name = "<?php echo $opportunity_name; ?>";;		
			addObj.target 			= "<?php echo $target; ?>";;
			addObj.lead_cust_id 	= "<?php echo $lead_cust_id; ?>";

			addObj.opportunity_contact = '<?php echo $opportunity_contact; ?>';
			addObj.product_id 		= "<?php echo $product_id; ?>";;
			addObj.currency_id		= "<?php echo $currency_id; ?>";
			addObj.sell_type 		= "<?php echo $sell_type; ?>";
			
			addObj.industry_id 		= "<?php echo $industry_id; ?>";
			addObj.location_id 		= "<?php echo $location_id; ?>";
			addObj.opp_remarks 		= "<?php echo $opp_remarks; ?>";
			addObj.stage_id 		= "<?php echo $stage_id; ?>";
			addObj.cycle_id 		= "<?php echo $cycle_id; ?>";
			addObj.manager_id 		= "<?php echo $manager_id; ?>";
			addObj.owner_id 		= "<?php echo $owner_id; ?>";

			console.log(addObj);
			$.ajax({
				type : "POST",
				url : "<?php echo site_url('webservice_Controller/add_opp_final'); ?>",
				dataType : 'json',
				cache : false,
				data: JSON.stringify(addObj),
				success : function(data)	{
					if (error_handler(data)) {
						return ;
					}
					if (data.status == true) {
						$('#qualifier_success').val('1');
						$('#confirm_modal').modal('show');
						$('#confirm_modal .modal-body p').text('Qualifier submitted successfully.');
						
					} else {
						$('#qualifier_success').val('0');
						$('#confirm_modal').modal('show');
						$('#confirm_modal .modal-body p').text('Attempted Qualifier was unsuccessful. Opportunity cannot be created.');
					}
					/* console.log(data); */
				}
			});
		}
		function error_handler(data){
			if(data.hasOwnProperty("errorCode")){
				$('body').append('<div class="mask custom-alert" id="execption_custom_alert"><div style="background:url('+base_url+'images/alert.png);background-size: 60px;background-position: center left;background-repeat: no-repeat;" class="alert alert-danger row custom-alert"><div class="col-md-12"><b>Database Error Code : </b>'+data.errorCode+'</div><div class="col-md-12"><b>Database Error Message : </b>'+data.errorMsg+'</div></div></div>');
				var isInside = false;
				$('#execption_custom_alert .custom-alert').hover(function () {
					isInside = true;
				}, function () {
					isInside = false;
				})

				$(document).mouseup(function () {
					if (!isInside){
						/* $('#execption_custom_alert').remove(); */
					}

				});
				return true;
			}
			return false;
		}

	</script>
	</head>
	<body  class="container">	
		<div class="row">
			<div class="col-lg-12">
				<center>
					<h2>Questions for <span id="lead_qualifier_name"></span></h2>
					<p>Mandatory fields are marked with an asterisk ( <i class='fa fa-star-half-o' aria-hidden='true'></i> ).</p>
				</center>
			</div>
		</div>
		<div class="row">
			<input type="hidden" id="qualifier_success">
			<input type="hidden" id="lead_qualifier_id">
			<input type="hidden" id="stage_id">
			<input type="hidden" id="rep_id">
			<input type="hidden" id="lead_id">
			<input type="hidden" id="opp_id">
			 <form>
				<div class="col-lg-12" id="question-list">					
				</div>
			</form>
			<center>
				<span id="mandatory" class="error-alert" style="color:red"></span>
				<br>
				<br>
				<button type="button" class="btn btn-primary" id="submit_q_btn" onclick="SubmitQpaper()" >Submit</button>
				<br>
				<br>
			</center>
			<div class="go-top">
			<i class="fa fa-arrow-circle-o-up fa-3x" aria-hidden="true"></i>
			</div>
		</div>
		
		<div id="confirm_modal" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<center><h4 class="modal-title"></h4></center>
					</div>
					<div class="modal-body">
						<div class="row">
							<p>Qualifier submitted successfully.</p>
						</div>
					</div>
					<div class="modal-footer">
						
					</div>
				</div>
			</div>
		</div>
		
	</body>
</html>
