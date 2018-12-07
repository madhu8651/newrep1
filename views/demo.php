<style>
textarea{
	resize: vertical;
}
.notification{
    position: absolute;
    width: 300px;
    background: #FFF;
    right: 0px;
    top: 34px;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-shadow: 4px 4px 8px #999;
}
.notification .content{
    border-bottom:1px solid #ccc;
    margin:5px 0px;
	min-height:10px;
	padding: 5px;
}
.count{    width: 17px;
    height: 12px;
    text-align: center;
    font-size: 12px;
}
.notificationCount{
	background: #000;
    color: #fff;
    border-radius: 10px;
    position: absolute;
    top: 0px;
    right: 8px;
}

.personalInfo ul,
.businessInfo ul,
.officInfo ul,
.accessControls ul{
	padding:0px;
}
.personalInfo img{
	width: 100px;
    height: 100px;
}
.personalInfo ul li,
.businessInfo ul li,
.accessControls ul li,
.officInfo ul li{
	list-style-type: none;
	display: inline-block;
	margin-right: 10px;
	min-width: 120px;
}
.personalInfo ul li i
.businessInfo ul li i
.officInfo ul li i{
	width:20px;
	text-align: center;
}
.productLi li{
	display: inline-block;
	margin-right: 10px;
    margin-bottom: 10px;
    border-bottom: 10px solid #ddd;
    border-left: 2px solid #ddd;
}
.productLi .fa{
	margin-right: 8px;
}
li .crr{
	margin-left:10px;
}
li .crr hr{
	margin:0px;
}
#pviewmodal .panel-default>.panel-heading {
    color: #611010;
    background-color: #e0e0e0;
    border-color: #ddd;
    box-shadow: 0px 2px 5px;
	position: relative;
}
#pviewmodal .panel-default .add{
    top: -6px;
    right: 15px;
    border: 2px solid;
    padding: 5px 8px;
}
#pviewmodal .targetCurrencyHide{
	display:none;
}
#pviewmodal .collapse {
    padding-top: 15px;
}
#notifytablebody tr td{
    text-align:left;

}
.mask {
	width: 100%;
	margin: auto;
	height: 100%;
	position: fixed;
	top: 0;
	background: transparent;
	z-index: 999999;
}
.alert.row {
	position: fixed;
	z-index: 99999999;
	top: 0;
	width: 44%;
	margin: 20% 28% !important;
}
#execption_custom_alert .alert.row{
	position: relative;
	margin: 20% auto !important;
}
#execption_custom_alert .col-md-12{
	padding-left:60px;
}
.table-bordered>thead>tr>th,
.table-bordered>tbody>tr>th,
.table-bordered>tfoot>tr>th,
.table-bordered>thead>tr>td,
.table-bordered>tbody>tr>td,
.table-bordered>tfoot>tr>td {
    border: 1px solid #151414;
}
.table-bordered,
.table-bordered > tbody > tr > th,
.table-bordered > tbody > tr > td {
    border: 1px solid #ccc!important;
}
.btn-default.disabled, .btn-default.disabled.active, .btn-default.disabled.focus, .btn-default.disabled:active, .btn-default.disabled:focus, .btn-default.disabled:hover, .btn-default[disabled], .btn-default[disabled].active, .btn-default[disabled].focus, .btn-default[disabled]:active, .btn-default[disabled]:focus, .btn-default[disabled]:hover, fieldset[disabled] .btn-default, fieldset[disabled] .btn-default.active, fieldset[disabled] .btn-default.focus, fieldset[disabled] .btn-default:active, fieldset[disabled] .btn-default:focus, fieldset[disabled] .btn-default:hover {
    background-color: #fff;
    border-color: #ccc;
    cursor: not-allowed;
    background: #b5000a8a !important;
}
span.error {
	color: #ff0000;
}
.validated{
	background:green !important;
}
.bold{
      font-weight: bold;
}
.version_type{
	font-size: 16px;
    margin-right: 15px;
    color: #fff;
    font-weight: 700;
    border-bottom: 1px solid;
    padding: 5px;
    width: 100%;
    text-align: center;
    border-radius: 5px;
}
#productivityDetailsEditCommon .cal-name,
#productivityDetailsEditCommon .spend-calculation{
	max-width: 586px;
	margin: auto; 
}
#mail_to_commonUl li, 
#mail_cc_commonUl li,
#mail_bcc_commonUl li{
	display:inline-block;
	position: relative;	
}
#mail_to_commonUl li .glyphicon.glyphicon-remove,
#mail_cc_commonUl li .glyphicon.glyphicon-remove,
#mail_bcc_commonUl li .glyphicon.glyphicon-remove{
	position: absolute;
    right: 0px;
    top: 0px;
    background: #fff;
    border: 1px solid;
    padding: 9px;
}
#mail_to_commonUl li .display,
#mail_cc_commonUl li .display,
#mail_bcc_commonUl li .display{
	padding-right: 40px;
}
/* fixed for 0000105: Expanded header should be highlighted. */
.body-content .nav.nav-tabs .active a{
	font-weight: 800 !important;
	color: #b5000a !important;
}
</style>

<script>

	var mang_id;
	var moduleName = "<?php echo ucfirst($_SESSION['active_module_name']) ?>";
	var cxo = "<?php echo $_SESSION['cxo']; ?>";
	var manager = "<?php echo $_SESSION['manager']; ?>";
	var sales = "<?php echo $_SESSION['sales']; ?>";
	var versiontype = "<?php echo $_SESSION['versiontype']; ?>";

	var dateFormat = "<?php echo $_SESSION['date_format']; ?>";
	var dateTimeFormat = "<?php echo $_SESSION['date_time_format']; ?>";
	
    versiontype=versiontype.toLowerCase();
	function switchModule(){
		window.location.href="<?php echo site_url('indexController/multiple_login'); ?>";
	}
	function userSignOut(){
		$.confirm({
			title: 'L Connectt',
			content: 'Are you sure you want to Sign Out',
			animation: 'none',
			closeAnimation: 'scale',
			buttons: {
				Ok: function () {
					window.location.href="<?php echo site_url('loginController/logout');?>";
				},
				Cancel: function () {
					
				}
			}
		});
	};
	/*--------analytics details of page---------*/
	/* new chages by tapash*/
	function analytics(){
		/*----code that gets page name------*/
		var path = window.location.pathname, result = {};
		var page = path.split("/").pop();
		result.page_name = page;
		result.page_start_time = moment().format("YYYY-MM-DD HH:mm:ss");
		$.ajax({
                type : "post",
                url : "<?php echo site_url('loginController/userBehaviour'); ?>",
                cache : false,
				data : JSON.stringify(result),
                dataType : "json",
                success : function(data){
				}
		});
	}
    $(document).ready(function(){	
		analytics();
        /* code to fetch the notification for the logged-in user */
		checkUserState();
		checkSessionID();
		$('input.hideBtn').hide();
		if(moduleName == 'Manager' || moduleName == 'Executive' || moduleName == 'Sales'){
			getUserEmailSettings();
			/*  syncEmails(); */
			/* syncEmails_personal(); */
		}if(moduleName == 'Admin'){
			UserEmailSettings = 1;
		}
         var myArray = [];
		$('#bellClick').click(function(){
            $.ajax({
                type : "post",
                url : "<?php echo site_url('notificationController/getnotifydata'); ?>",
                cache : false,
                dataType : "json",
                success : function(data){

					if(error_handler(data)){
							return;
					}
					if(data.length > 0 ){
						var row='';
                        for(i=0; i < data.length; i++ ){
                            myArray.push(data[i].notify_id);
						}

					}else{
					  myArray = [];
					}
                    displaynotifyview('unread',myArray);
					var isInside = false;

					$(".notification").hover(function () {
						isInside = true;
					}, function () {
						isInside = false;
					});

					$(document).mouseup(function () {
						if (!isInside)
							$(".notification").hide();
					});
                },
				error : function(data){
					network_err_alert(data)
				}

            });
		});
		/*  end of code to fetch the notification */



         if(typeof(EventSource) != "undefined")
        {

            var notifydata='';
            var source = new EventSource("<?php echo site_url('notificationController/getcount'); ?>");
            source.onmessage = function(event)
            {
                if($('.count').text()<event.data && $('.count').text()!='')
                {
                    var audio = new Audio('<?php echo base_url(); ?>/uploads/bell-sound.mp3');
		            audio.play();
                }
                if(event.data>0)
                {
                	$('.notificationCount').css("background","#000");
                    $('.count').text(event.data);
                }
                else
                {
                   $('.count').text('');
                   $('.notificationCount').css("background","none");
                }
            };
        } 
    });

   /* code for sandbox */
 var recordcount=0;
 /* var versiontype='sandbox'; */
 function sandbox(pagename) {
           var fileA = pagename.split('#');

           //alert(fileA[0]);
           if(versiontype=='lite'){
               $('.main-sidebar, .content-wrapper.body-content').show();
               switch(fileA[0]) {
                        case 'admin_roleController':
                        recordcount=0;

                        break;
                        case 'admin_currencyController':
                        recordcount=1;
                        break;
                        case 'admin_support_processController':
                        recordcount=5; /*  four are pre defined, so restricted after count 5 */
                        break;
                        case 'admin_support_customController':
                        recordcount=3;
                        break;
                        case 'admin_holidaysController':
                        recordcount=1;
                        break;
                        case 'admin_teamController':
                        recordcount=0;
                        break;
                        case 'admin_buyerpersonaController':
                        recordcount=0;
                        break;
                        case 'admin_salespersonaController':
                        recordcount=0;
                        break;
                        case 'admin_activityController':
                        recordcount=0; /*  seven are pre defined, so restricted after count 10 */
                        break;
                        case 'admin_mastersales_cycleController':
                        recordcount=1;
                        break;
                        case 'admin_sales_cycleController':
                        recordcount=2;
                        break;
                        case 'admin_sup_mastersales_cycleController':
                        recordcount=1;
                        break;
                        case 'admin_sup_sales_cycleController':
                        recordcount=2;
                        break;
                        case 'admin_userController1':
                        recordcount=5;
                        break;
                        case 'admin_customFieldController':
                        recordcount=0;
                        break;
                        case 'index1':
                        recordcount=6;
                        break;
               }
           }else if(versiontype=='standard'){
                $('.main-sidebar, .content-wrapper.body-content').show();
                switch(fileA[0]) {
                     /* alert(fileA); */
                     case 'index1':
                        recordcount=12;
                     break;
                     default:
                     recordcount=0;
                }

           }else if(versiontype=='premium'){
                $('.main-sidebar, .content-wrapper.body-content').show();
                switch(fileA[0]) {
                     /* alert(fileA); */
                     case 'index1':
                        recordcount=18;
                     break;
                     default:
                     recordcount=0;
                }
           }else{
                   $('.main-sidebar, .content-wrapper.body-content').remove();
                   htm = '<div class="mask custom-alert" id="execption_custom_alert">'+
                   '<div style="background:url('+base_url+'images/alert.png);background-size: 60px;background-position: center left;background-repeat: no-repeat;" class="alert alert-danger row custom-alert">'+
                   '<div class="col-md-12">'+
                   '<center><b>Invalid Version Type!</b></center></br>'+
                   '</div>'+
                   '<div class="col-md-12">'+
                   '<center><input type="button" class="btn" onclick="userSignOut()" value="Log Out"/></center>'+
                   '</div>'+
                   '</div>'+
                   '</div>';

                   $('body').append(htm);
                   var isInside = false;
                   $('#execption_custom_alert .custom-alert').hover(function () {
                   isInside = true;
                   }, function () {
                   isInside = false;
                   })

                   $(document).mouseup(function () {
                   if (!isInside){
                   / $('#execption_custom_alert').remove(); /
                   }

                   });

           }
 }


	/* Synchronization of Emails */
	function syncEmails(){
		$.ajax({
			type : "post",
			url : "<?php echo site_url('emailExtractController/get_emails'); ?>",
			success : function(data){

			},
			error : function(data){
				network_err_alert(data)
			}
		});
	}

	/* function syncEmails_personal(){
		$.ajax({
			type : "post",
			url : "<?php echo site_url('emailExtractController/get_personal_emails'); ?>",
			success : function(data){

			},
			error : function(data){
				network_err_alert(data)
			}
		});
	} */
	function checkUserState(){
		var obj = {};
		obj.user_id = "<?php echo $this->session->userdata('uid'); ?>";

		$.ajax({
			type : "post",
			url : "<?php echo site_url('loginController/checkUserState'); ?>",
			dataType : "json",
			data : JSON.stringify(obj),
			success : function(data){
				if(data>0){
					window.location.href="<?php echo site_url('loginController/logout');?>";
				}
			},
			error : function(data){
				network_err_alert(data)
			}
		});
	}

    function checkSessionID(){
		var user_id = "<?php echo $this->session->userdata('uid'); ?>";

		$.ajax({
			type : "post",
			url : "<?php echo site_url('loginController/checkSessionID'); ?>",
			data : "user_id="+user_id,
			success : function(data){
				if(data==0){
					$.confirm({
						title: 'L Connectt',
						content: 'Your account was logged into from an unrecognized system. We are logging you out from the system.',
						animation: 'none',
						closeAnimation: 'scale',
						buttons: {
							Ok: function () {
								window.location.href="<?php echo site_url('loginController/logout');?>";
							}
						}
					});
				}
			},
			error : function(data){
				network_err_alert(data)
			}
		});
	}

    function displaynotifyview(state,myArray){

		$.ajax({
			type : "post",
			url : "<?php echo site_url('notificationController/displaynotifydata/'); ?>"+state,
			cache : false,
			data: {ids:myArray},
			dataType : "json",
			success : function(data){

				if(error_handler(data)){
					return;
				}
				row='';
              	for(i=0; i < data.length; i++ ){
					var notificationTimestamp =  moment(data[i].notifydate , 'DD-MM-YYYY HH:mm:ss').format('ll')+'<br><i class="fa fa-clock-o" aria-hidden="true"></i> '+
					moment(data[i].notifydate , 'DD-MM-YYYY HH:mm:ss').format('LT');
                    var match ="";

                    if($.inArray(data[i].id,myArray) !== -1)
                    {
                         match = "bold";

                    } else{
                          match ="";
                    }
                     row += "<tr class ="+match+"><td>" + data[i].notificationShortText + "</td>"+
						   "<td>"+ data[i].notificationText +"</td>"+
						   "<td>"+ data[i].username +"</td>"+
						   "<td>"+ notificationTimestamp +"</td>"
						   /*"<td>"+data[i].action_url+"</td></tr>"*/;
				}
				$('#notifytablebody').html('').append(row);

			},
			error : function(data){
				network_err_alert(data)
			}
		});
        $('#addmodal_notification').modal('show');
    }
</script>
<script>
/*--------------Menu Highlight on page load------------*/
var LogggedInUserRole =0;
$( window ).load(function() {
    $.ajax({
		type : "POST",
		url : "<?php echo site_url('admin_sidenavController/get_photo')?>",
		dataType : 'json',
        cache : false,
		success : function(data){
			mang_id=data[0].user_id;

			if(error_handler(data)){
				return;
			}
			/* ---------------------------------------------- */
			if(moduleName == "Manager"){
				$("#mdlName").css("color", "rgb(215, 135, 255)");
				LogggedInUserRole =2;
			}if(moduleName == "Executive" ||  $.trim(moduleName) == "sales"){
				$("#mdlName").css("color", "#f4f4f4");
			}if(moduleName == "Admin" ){
				$("#mdlName").css("color", "rgb(8, 232, 72)");
				LogggedInUserRole =1;
			}

			var switchModule="";
			var profileviewLink="";
			if($.trim(moduleName) == "Executive" || $.trim(moduleName) == "Manager" ||  $.trim(moduleName) == "sales" ){
				if(($.trim(manager) != '-') && ($.trim(sales) != '-')){
					switchModule = '<hr><div><a href"#" onclick="switchModule()"><i class="fa fa-retweet fa-lg"></i> Switch Module</a></div>';
				}
				var settingsHtml = ""
				if(UserEmailSettings == 0){					
					settingsHtml = '<hr><div><a href="#" style="color:red;" onclick="emailSettings(\''+data[0].user_id+'\')" ><i class="fa fa-cogs fa-lg"></i> Email Settings</a></div>';					
				}else{
					settingsHtml = '<hr><div><a href="#" style="color:#3c8dbc;" onclick="emailSettings(\''+data[0].user_id+'\')" ><i class="fa fa-cogs fa-lg"></i> Email Settings</a></div>';
				}
				profileviewLink = '<div><a href"#" onclick="viewadminInfo(\''+data[0].user_id+'\')"><i class="fa fa-eye fa-lg" aria-hidden="true"></i> View Profile</a></div>'+settingsHtml+'<hr>';
			}
			var user_module = {
				content: '<div class="module-info">'+profileviewLink+
							'<div>'+
								'<a href"#" onclick="editadminInfo()">' +
									'<i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i> Edit Profile'+
								'</a>'+
							'</div>'+
							/* '<hr>'+
							'<div>'+
								'<a href"#" id="support_mail" onclick="supportMail(\'supportmail\')" >'+
									'<i class="fa fa-at fa-lg"></i> Support Mail'+
								'</a>'+
							'</div>'+ */
							switchModule+'<hr><div><a href"#" id="signOut" onclick="userSignOut()" ><i class="fa fa-sign-out fa-lg"></i> Log Out</a></div></div>',
				html: true,
				placement: 'bottom'
			};
		    $("#adminAvt").popover(user_module);

		    $('html').on('click', function(e) {
				if (typeof $(e.target).data('original-title') == 'undefined' && !$(e.target).parents().is('.popover.in')) {
					$('[data-original-title]').popover('hide');
				}
			});
			/* ---------------------------------------------- */
			$('#getuser_name').text(data[0].user_name);
			if(data[0].photo){
				$("#contactAddAvatar").attr("src","<?php echo base_url(); ?>uploads/"+data[0].photo);
				$('#adminAvt').attr('src', "<?php echo base_url(); ?>uploads/"+data[0].photo);
			}else{
				$("#contactAddAvatar").attr("src","<?php echo base_url(); ?>images/default-pic.jpg");
				$('#adminAvt').attr('src', "<?php echo base_url(); ?>images/default-pic.jpg");
			}
		},
		error : function(data){
			network_err_alert(data)
		}
	});
});
/* ----------------------------Setting function starts-------------------------------------------- */
function cancel_setting(){
	$('#user_setting_modal_view').modal('hide');
}
function save_setting(){
	var addObj={};
	addObj.timeZone = $.trim($('#time_zone_setting').val());
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('admin_sidenavController/save_timezones')?>",
		dataType : 'json',
		data : JSON.stringify(addObj),
        cache : false,
		success : function(data){
			if(error_handler(data)){
				return;
			}
            $('#user_setting_modal_view').modal('hide');
		},
		error : function(data){
			network_err_alert(data)
		}
	});
}
function userSetting(){
	$('#user_setting_modal_view').modal('show');
    $.ajax({
		type : "POST",
		url : "<?php echo site_url('admin_sidenavController/get_timezones')?>",
		dataType : 'json',
        cache : false,
		success : function(data){
			if(error_handler(data)){
				return;
			}
            if(data.length>0){
               $('#time_zone_setting').timezones();
               $('#time_zone_setting').val(data[0].timezone);
            }else{
               $('#time_zone_setting').timezones();
            }
		},
		error : function(data){
			network_err_alert(data)
		}
	});


}

/* --------------------------Setting function end------------------------------------------ */
function e_cancel2(){
	$('.modal').modal('hide');
}
function editadminInfo(){
	$("#imageUploadA").trigger('reset');
    $.ajax({
		type : "POST",
		url : "<?php echo site_url('admin_sidenavController/get_data')?>",
		dataType : 'json',
        cache : false,
		success : function(data){
			if(error_handler(data)){
							return;
						}
            $('#adminProfileE').modal('show');
            /* var phoneNumber= JSON.parse(data[0].phone_num); */
        	$("#adminEditid").val(data[0].user_id);
        	$("#adminNameE").val(data[0].user_name);
        	/* $("#adminModE").val(phoneNumber.mobile[0]); */
        	$("#adminUsernameE").val(data[0].login_id);
        	$("#hid_oldpassword").val(data[0].login_pwd);
			if(data[0].photo){
				$("#contactAddAvatar").attr("src","<?php echo base_url(); ?>uploads/"+data[0].photo);
				$('#adminAvt').attr('src', "<?php echo base_url(); ?>uploads/"+data[0].photo);
			}else{
				$("#contactAddAvatar").attr("src","<?php echo base_url(); ?>images/default-pic.jpg");
				$('#adminAvt').attr('src', "<?php echo base_url(); ?>images/default-pic.jpg");
			}

		},
		error : function(data){
			network_err_alert(data)
		}
	});
}

/* -----------------------------resend mail for admin----------------------------------------- */
function emailResend(user,loginid){
	var r1 = confirm("Are you sure you want to send mail..!");
	if(r1==true){
        var obj = {};
		obj.user_id =user;
		obj.loginid =loginid;

		$.ajax({
			type : "post",
			url : "<?php echo site_url('lconnectt_commonController/resend_mail'); ?>",
			dataType : "json",
			data : JSON.stringify(obj),
			success : function(data){
                 if(error_handler(data)){
					return;
				}
                if(data==1){
                   $("#pviewmodal .e_section5").append('<center><p style="background-color:#9edf93">Mail has been sent successfully.</p></center>');
                }else{
                    $("#pviewmodal .e_section5").append('<center><p style="background-color:#9edf93">Mail Sending Failed.</p></center>');
                }

			},
			error : function(data){
				network_err_alert(data)
			}
		});

	}

}
/* for currency validation */
function currencyValidation(input){
	var withDecPoint = new RegExp(/^\d+\.\d\d$/);
	var withOutDecPoint = new RegExp(/^\d+$/);
	if (!withDecPoint.test($.trim(input)) && !withOutDecPoint.test($.trim(input))) {
		return false;
	} else {
		return true;
	}
}
/* -----------------------------Profile View----------------------------------------- */
var seleUserData;
function viewadminInfo(userid){
	$("#officInfo, #accessControls, #businessInfo, #prodCurrInfo, #targetInfo, #deviceInfo").collapse('hide');
	$('#personalInfo').collapse('show')
	var obj={};
	obj.manager_id=userid;
	var addObj={};
	addObj.userid=userid;
	$.ajax({
			type : "POST",
			url : "<?php echo site_url('lconnectt_commonController/get_fulluser_data'); ?>",
			dataType : 'json',
			data : JSON.stringify(addObj),
			cache : false,
			success : function(data){
			
			loaderHide();
			if(error_handler(data)){
						return;
					}
			$('#pviewmodal .modal-header .modal-title').text(data.user[0].user_name)
			var photoUrl= base_url+"uploads/default-pic.jpg";
			if(data.user[0].photo=="" || data.user[0].photo == null ){
				photoUrl= base_url+"uploads/default-pic.jpg";
			}else{
				photoUrl= base_url+'uploads/'+data.user[0].photo;
			}
			
			
			/* ----------------------------------------------------------------------------------------*/
			/* ------------------------------Edit in view Start ---------------------------------------------*/
			seleUserData = data;
			editDetails();
			
			if(data.user[0].dob == null){
				data.user[0].dob = "&nbsp;&nbsp;&nbsp;";
			}

			/* -------------------------------------------------------- */
			if(LogggedInUserRole ==1){
				$("#pviewmodal .e_section5").html('<center><input type="button" class="btn" value="Resend Mail" onclick="emailResend(\''+userid+'\',\''+data.user[0].login_id+'\')"/> </center>');
			}

			/* ---------------- Personal Information ------------------- */
			var PersonalInformation = '<div class="row"><div class="col-md-4"><img class="img-responsive img-circle" src="'+photoUrl+'" alt="'+photoUrl+'"/></div><div class="col-md-2"></div><div class="col-md-2"><label><b>Employee Name  </b></label><br><label><b>Employee ID  </b></label></div><div class="col-md-4"><label>'+data.user[0].user_name+'</label><br><label>'+data.user[0].employee_id+'</label></div><div class="col-md-2"></div><div class="col-md-2"><label><b>DOB </b></label></div><div class="col-md-4"><label>'+data.user[0].dob+'</label></div><div class="col-md-2"></div><div class="col-md-2"><label><b>Gender  </b></label></div><div class="col-md-4"><label>'+capitalizeFirstLetter(data.user[0].user_gender)+'</label></div></div>';

			PersonalInformation += '<div class="row"></div>';

			var phoneNum = JSON.parse(data.user[0].phone_num);
			var phoneNumArray =[];

			if(phoneNum.mobile.length > 0){
				for(m=0; m<phoneNum.mobile.length; m++){
					phoneNumArray.push('<i class="fa fa-mobile" aria-hidden="true"></i>'+phoneNum.mobile[m]);
				}
			}

			if(phoneNum.home.length > 0){
				for(h=0; h<phoneNum.home.length; h++){
					phoneNumArray.push('<i class="fa fa-home" aria-hidden="true"></i>'+phoneNum.home[h]);
				}
			}
			if(phoneNum.main.length > 0){
				for(ma=0; ma<phoneNum.main.length; ma++){
					phoneNumArray.push('<i class="fa fa-home" aria-hidden="true"></i>'+phoneNum.main[ma]);
				}
			}
			if(phoneNum.work.length > 0){
				for(w=0; w<phoneNum.work.length; w++){
					phoneNumArray.push('<i class="fa fa-phone-square" aria-hidden="true"></i>'+phoneNum.work[w]);
				}
			}
			var phNolist = [];
			var phNolistV = '';
			for(slNo=0; slNo<phoneNumArray.length; slNo++){
				phNolistV += '<li>'+phoneNumArray[slNo]+'</li>';
			}
			PersonalInformation += '<div class="row"><div class="col-md-2"><label><b></b></label></div><div class="col-md-4"><label></label></div><div class="col-md-2"><label><b>Residential Address </b></label></div><div class="col-md-4"><label>'+data.user[0].address1+'</label></div></div>';
			
			PersonalInformation += '<div class="row"><div class="col-md-2"><label><b></b></label></div><div class="col-md-4"><label></label></div><div class="col-md-2"><label><b>Phone Number </b></label></div><div class="col-md-4"><label><ul>'+phNolistV+'</ul></label></div></div>';

			$(".personalInfo").html("").append(PersonalInformation);
			/* ---------------- Office Information ------------------- */
			var OfficeInformation = '<div class="row"><div class="col-md-2"><label><b>Department</b></label></div><div class="col-md-4"><label>'+data.user[0].Department_name+'</label></div><div class="col-md-2"><label><b>Role </b></label></div><div class="col-md-4"><label>'+data.user[0].role_name+'</label></div></div>';

			OfficeInformation += '<div class="row"><div class="col-md-2"><label><b>Reporting Into</b></label></div><div class="col-md-4"><label>'+data.user[0].Manager+'</label></div><div class="col-md-2"><label><b>Team </b></label></div><div class="col-md-4"><label>'+data.user[0].teamname+'</label></div></div>';
			/* ---------------- ------------------- */

			var mailId = JSON.parse(data.user[0].emailId);
			var emailArray =[];

			if(mailId.work.length > 0){
				for(em=0; em<mailId.work.length; em++){
					emailArray.push('<i class="fa fa-envelope" aria-hidden="true"></i>'+mailId.work[em]);
				}
			}

			if(mailId.personal.length > 0){
				for(eh=0; eh<mailId.personal.length; eh++){
					emailArray.push('<i class="fa fa-user" aria-hidden="true"></i>'+mailId.personal[eh]);
				}
			}
			var emailListV = '';
			for(slNo=0; slNo<emailArray.length; slNo++){
				emailListV += '<li>'+emailArray[slNo]+'</li>';
			}


			var officelocV = '';
			for(slNo=0; slNo<data.officeloc.length; slNo++){
				officelocV += '<li><i class="fa fa-map-marker" aria-hidden="true"></i> '+data.officeloc[slNo].bussinessLoc+"<b> ("+data.officeloc[slNo].bussinessLoc1+")</b>"+'</li>';
			}
			OfficeInformation += '<div class="row"><div class="col-md-2"><label><b>Email ID</b></label></div><div class="col-md-4"><ul>'+emailListV+'</ul></div><div class="col-md-2"><label><b>Office Location</b></label></div><div class="col-md-4"><ul>'+officelocV+'</ul></div></div>';
			$(".officInfo").html("").append(OfficeInformation);
			/* ---------------- Access Controls ------------------- */
			var custAssing = JSON.parse(data.modules[0].module_id);
			if(custAssing.custo_assign != 0){
				var lbl = "Yes"
			}else{
				var lbl = "No"
			}
			var mod =[];
			if(custAssing.cxo != 0){
				mod.push(" CXO ");
			}if(custAssing.sales != 0){
				mod.push(" Executive ");
			}if(custAssing.Manager != 0){
				mod.push(" Manager ");
			}


            var plugAssing = JSON.parse(data.plugin[0].plugin_id);
            var plug =[];
            if(plugAssing.Attendence != ""){
				plug.push(" Attendence ");
			}
            if(plugAssing.Communicator != ""){
				plug.push(" Communicator ");
			}
			if(plugAssing.Expense != ""){
				plug.push(" Expense ");
			}
            if(plugAssing.Inventory != ""){
				plug.push(" Inventory ");
			}
            if(plugAssing.Library != ""){
				plug.push(" Library ");
			}
            if(plugAssing.Navigator != ""){
				plug.push(" Navigator ");
			}



            var str = [];

            for(x=0; x<data.selltype.length;x++){
                str.push(data.selltype[x].map_id);
            }
			var AccessControls = '<div class="row"><div class="col-md-6"><label><b>Sell Type</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+str.join(", ")+'</div><div class="col-md-2"><label><b>Associated Modules</b></label></div><div class="col-md-4"><ul>'+mod+'</ul></div></div>';
			if(plug.length != 0 ){
				AccessControls += '<div class="row"><div class="col-md-2"><label><b>Associated Plugin</b></label></div><div class="col-md-4"><ul>'+plug+'</ul></div></div>';
			}

			$(".accessControls").html("").append(AccessControls);
			
			var Businessformation = '<div class="row edit-btn"><span title="Edit business clientele list" onclick="editBusinessClient()" class="pull-right add glyphicon glyphicon-pencil"></div>'+
					'<div class="row edit none">'+
						'<div class="col-md-2">'+
							'<label><b>Business Location</b></label>'+
						'</div>'+
						'<div class="col-md-4 business multiselect">'+
							
						'</div>'+
						'<div class="col-md-2">'+
							'<label><b>Clientele Industries</b></label>'+
						'</div>'+
						'<div class="col-md-4 clientele multiselect">'+
							
						'</div>'+
					'</div>'+
					'<div class="row edit none">'+
						'<hr>'+
						'<center><p>'+
							'<input type="button" class="btn btn-default" onclick="saveBusinessClientele()" value="Save">&nbsp&nbsp'+
							'<input type="button" class="btn btn-default" onclick="closeBusinessClientele()" value="Cancel">'+
						'</p></center>'+
					'</div>';
			var productivityDetails =	'<div class="row edit-btn"><span title="Edit productivity details " onclick="editProductivityDetails()" class="pull-right add glyphicon glyphicon-pencil"></div>'+
					'<div class="row none" id="productivityDetailsEditCommon">'+
						'<div class="row">'+
						'<div class="col-md-12 pro no-padding"></div>'+
						'</div>'+
						'<hr>'+
						'<div class="cal-name">'+
						'<div class="row">'+
						'<div class="col-md-4"><label><b>Calendar Name</b></label></div>'+
						'<div class="col-md-8"></div>'+
						'</div>'+
						'</div>'+
						'<hr>'+
						'<div class="spend-calculation">'+
						
						'</div>'+
						'<hr>'+
						'<div class="row">'+
						'<div class="col-md-12 timing-details"></div>'+
						'</div>'+
						'<div class="row">'+
							'<center><p>'+
								'<input type="button" class="btn btn-default" onclick="saveProductivityDetails()" value="Save">&nbsp&nbsp'+
								'<input type="button" class="btn btn-default" onclick="closeProductivityDetails()" value="Cancel">'+
							'</p></center>'+
						'</div>'+
					'</div>';
            if(data.procur.length >0){
				/* ---------------- BUSINESS DETAILS ------------------- */						
				
				
				Businessformation += '<div class="row view"></div>';

				$(".businessInfo").html("").append(Businessformation);
				businessClienteleView(data.industry,data.businessloc);
				var elm = $(".businessInfo .row.edit-btn");
				checkPermission(elm);
				/* ---------------- PRODUCTIVITY DETAILS ------------------- */
				var prodCurr = data.procur;
		
				var RecordingStatus = (data.prodetails[0].call_recording == '0' ? "Disabled" : "Enabled" );

				
					productivityDetails +=	'<div class="row view">'+
												'<div class="col-md-2"><label><b>Product</b></label></div>'+
												'<div class="col-md-10">'+
													'<ul style="padding: 0px;" class="productLi"></ul>'+
												'</div>'+
											'</div>';

				productivityDetails += '<div class="row view text-center">'+
				'<div class="col-md-12 ">'+
				'<label><b>Call Recording </b></label>&nbsp;&nbsp;&nbsp;&nbsp;<label>'+RecordingStatus+'</label>'+
				'</div>'+
				'</div>'+
				'<div class="row spend-cal view text-center"></div>';
				
				
				/*-----------work timing details view-----------*/
				var workdetails = data.workdetails;
				
				
				
											
				productivityDetails += '<div class="row view"><div class="col-md-2 view"><label><b>Calendar Name</b></label></div>'+
				'<div class="col-md-10 view"><label>'+data.prodetails[0].calendername+'</label></div>'+
				'<div class="col-md-12 view weekDays"></div>'+
				'</div>';
				
				
				$(".prodCurrInfo").html("").append(productivityDetails);
				renderprodCrrView(prodCurr);
				renderWeekDaysView(workdetails);
				spendCalView(data.prodetails[0]);
				
				var elm = $("#prodCurrInfo .row.edit-btn");
				checkPermission(elm);
				
			}else{
				$(".businessInfo").html(Businessformation+"<center><b>No data available</b></center>");
				$(".prodCurrInfo").html(productivityDetails+"<center><b>No data available</b></center>");
			}
	/* ---------------- TARGET INFORMATIONNFO  ------------------- */

			$.ajax({
				type : "POST",
				data : JSON.stringify(obj),
				url : "<?php echo site_url('lconnectt_commonController/get_target')?>",
				dataType : 'json',
				cache : false,
				success : function(targetdata){
					if(error_handler(targetdata)){
							return;
					}
					$(".targetInfo").html("");	
					if(LogggedInUserRole == 2 || LogggedInUserRole == 1){
						add_target_view(data);
					}
					
					target_tbl(targetdata);

				},
				error : function(data){
					network_err_alert(data)
				}

			})

	/* ---------------- -----------------------Device Details  -------------------------- */
			var btntext="";
			var btncolor="";
			if(parseInt(data.user[0].app_login_state)== 0 || parseInt(data.user[0].app_login_state)==1)
			{
				btntext='Lock Phone';
				btncolor='';
			}else{
				btntext='UnLock Phone';
				btncolor='style="background: green !important;"';

			}

			if(data.appdetails.length>0){
				devcInfo ='<div class="row">'+
					'<div class="col-md-6">'+
						'<label><b>Device Model : </b>'+data.appdetails[0].device_model+'</label>'+
					'</div>'+
					'<div class="col-md-6">'+
						'<label><b>Device OS Version : </b>'+data.appdetails[0].device_os_version+'</label>'+
					'</div>'+
				'</div>'+
				'<div class="row">'+
					'<div class="col-md-6">'+
						'<label><b>UUID / IMEI Number : </b>'+data.appdetails[0].IMEI+'</label>'+
					'</div>'+
					'<div class="col-md-6">'+
						'<label><b>Device Type : </b>'+data.appdetails[0].device_type+'</label>'+
					'</div>'+
				'</div>';
				if(LogggedInUserRole == 1 && parseInt(data.user[0].user_state)==1){
					devcInfo += '<div class="row">'+
					   '<div class="col-md-12">'+
						'<p><center>'+
						 '<button id="blckid" class="btn" '+btncolor+' onclick="blckUser(\''+btntext+'\',\''+data.user[0].user_id+'\')">'+btntext+'</button>&nbsp;&nbsp;'+
						 '<button class="btn"  onclick="resetUser(\''+data.user[0].user_id+'\')">Reset</button>'+
						'</center></p>'+
					   '</div>'+
					  '</div>';
				}
            }else{
                devcInfo="<p><center>Mobile Application is not yet installed!</center></p> ";
            }
			$(".deviceInfo").html("").append(devcInfo);
			/**************************************************** */
			/**************************************************** */
			$('#pviewmodal').modal('show');
        },
		error : function(data){
			network_err_alert(data)
		}

	});
}
/* ----------------------------------------------------------------------------------------*/

function editDetails(){			
	var ObjforEdit = {};
	ObjforEdit.teamid =seleUserData.user[0].team_id;
	ObjforEdit.userid =seleUserData.user[0].user_id;
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('manager_teamManagersController/getTeamRelatedData'); ?>",
		dataType : 'json',
		data : JSON.stringify(ObjforEdit),
		cache : false,
		success : function(editdata){
			if(error_handler(editdata)){
				return;
			}
			/*-------------------------------*/
			
			var business = "",clientele = "";
			$.each( editdata.bussinessLocation, function( i, val ){
				var islocSave = 0;
				$.each( seleUserData.businessloc, function( j, savedval ){
					if( savedval.map_id == val.nodeid){
						islocSave = 1;
					}
				})
				if( islocSave == 1){
					business += "<div><label><input type='checkbox' checked value='"+val.nodeid+"'> <span>"+ val.nodename +"</span> <b>("+val.bussinessLoc1+")</b></label></div>";
				}else{
					business += "<div><label><input type='checkbox' value='"+val.nodeid+"'> <span>"+ val.nodename +"</span> <b>("+val.bussinessLoc1+")</b></label></div>";
				}
				
			})
			$.each( editdata.industry, function( i, val ){
				var islocSave = 0;
				$.each( seleUserData.industry, function( j, savedval ){
					if( savedval.map_id == val.nodeid){
						islocSave = 1;
					}
				})
				if( islocSave == 1){
					clientele += "<div><label><input type='checkbox' checked value='"+val.nodeid+"'> <span>"+ val.nodename +"</span> <b>("+val.clientInds1+")</b></label></div>";
				}else{
					clientele += "<div><label><input type='checkbox' value='"+val.nodeid+"'> <span>"+ val.nodename +"</span> <b>("+val.clientInds1+")</b></label></div>";
				}
			})
			
			$('#businessInfo .business.multiselect').html("").html(business);
			$('#businessInfo .clientele.multiselect').html("").html(clientele);
			
			
			/*-------------------------------*/
			var workTimingDetailsEdit = "";
			procurdata = editdata.product
			var calNameForEdit = '<select class="form-control"><option value="0">Select</option>';
								
			$.each( editdata.calendar, function( i, val ) {
				calNameForEdit += '<option value="'+val.calenderid+'">'+val.calendername+'</option>';						
			});
			
			calNameForEdit +='</select>';
			
		
			var currencyOptions = "";
			$.each( editdata.currency, function( i, val ) {
					currencyOptions += "<option value='"+val.currency_id+"'>"+ val.currency_name +"</option>";
			}) 
			$('#productivityDetailsEditCommon .cal-name .col-md-8').html('').html(calNameForEdit);
			$('#productivityDetailsEditCommon .cal-name select').val(seleUserData.prodetails[0].holiday_calender);
			/*---------spend calculation edit-------------*/
			var enableSpendCalculation ="";
			enableSpendCalculation += '<div class="row">'+
				'<div class="col-md-12"><label><input type="checkbox" '+ (seleUserData.prodetails[0].accounting != "0" ? "checked" : "")+'> Enable Spend Calculation</label></div>'+
				'</div>'+
				'<div class="curr row '+ (seleUserData.prodetails[0].accounting != "0" ? "" : "none") +'">'+
				'<div class="col-md-4"><label>Resource Cost/Hour*</label></div>'+
				'<div class="col-md-4">'+
				'<select class="form-control">'+
				'<option value="">Select</option>'+currencyOptions+
				'</select>'+
				'</div>'+
				'<div class="col-md-4"><input class="form-control" type="text"/></div>'+
				'</div>'+
				'<div class="curr row '+ (seleUserData.prodetails[0].accounting != "0" ? "" : "none") +'">'+
				'<div class="col-md-4"><label>Cost/Outgoing Call/Min*</label></div>'+
				'<div class="col-md-4">'+
				'<select class="form-control">'+
				'<option value="">Select</option>'+currencyOptions+
				'</select>'+
				'</div>'+
				'<div class="col-md-4"><input class="form-control" type="text"/></div>'+
				'</div>'+
				'<div class="curr row '+ (seleUserData.prodetails[0].accounting != "0" ? "" : "none") +'">'+
				'<div class="col-md-4"><label>Cost/Outgoing SMS*</label></div>'+
				'<div class="col-md-4">'+
				'<select class="form-control">'+
				'<option value="">Select</option>'+currencyOptions+
				'</select>'+
				'</div>'+
				'<div class="col-md-4"><input class="form-control" type="text"/></div>'+
				'</div>';
			$('#productivityDetailsEditCommon .spend-calculation').html('').html(enableSpendCalculation);
			$('#productivityDetailsEditCommon .spend-calculation .row').each(function(i){
				$(this).find('input[type="text"]').keyup(function(){
					if(!currencyValidation($(this).val())){ 
						$(this).closest('div').find('.error-alert').remove();
						$(this).closest('div').append('<span class="error-alert">Invalid currency format.</span>') 	
					}else{
						$(this).closest('div').find('.error-alert').remove();
					}										
				})
				switch(i) {
					case 0:
						$(this).find('input[type="checkbox"]').click(function(){
							if($(this).prop('checked') == true){
								$('#productivityDetailsEditCommon .spend-calculation .curr.row').removeClass('none');
							}else{
								$('#productivityDetailsEditCommon .spend-calculation .curr.row').addClass('none');
							}
						})
						break;
					case 1:
						$(this).find('select').val(seleUserData.prodetails[0].resource_currency);
						$(this).find('input[type="text"]').val(seleUserData.prodetails[0].resource_cost);
						break;
					case 2:
						$(this).find('select').val(seleUserData.prodetails[0].outgoingcall_currency);
						$(this).find('input[type="text"]').val(seleUserData.prodetails[0].outgoingcall_cost);
						break;
					case 3:
						$(this).find('select').val(seleUserData.prodetails[0].outgoingsms_currency);
						$(this).find('input[type="text"]').val(seleUserData.prodetails[0].outgoingsms_cost);
				}
			})
			/*---------work timing details edit-------------*/
				
			var weekDays =['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
			workTimingDetailsEdit = '<div id="workTimingDetailsEdit"><table class="table table-striped">';
			workTimingDetailsEdit += '<thead><tr><th><label><input type="checkbox" class="selectAll"/> Select All</label></th><th>Day</th><th>Start Time</th><th>End Time</th></tr></thead><tbody>';
			$.each( weekDays, function( i, day ){
				var start = "";
				var end = "";
				var isSave = 0;
				$.each( seleUserData.workdetails, function( j, savedval ) {
					if(day == savedval.day_of_week){
						start = savedval.start_time;
						end = savedval.end_time;
						isSave = 1;
					}
				})
				workTimingDetailsEdit += '<tr>'+
					'<td><input type="checkbox" '+ (isSave == 1 ? "checked":"")+' value="'+day+'"/></td>'+
					'<td>'+day+'</td>'+
					'<td><input '+ (isSave == 1 ? "":"disabled")+' class="form-control start" type="text" value="'+start+'"/></td>'+
					'<td><input '+ (isSave == 1 ? "":"disabled")+' class="form-control end" type="text" value="'+end+'"/></td>'+
				'</tr>';
			
			});
			workTimingDetailsEdit += "</tbody></table></div>";
			$('#productivityDetailsEditCommon .timing-details').html('').html(workTimingDetailsEdit);
			
			$("#workTimingDetailsEdit table tbody tr").each(function(i){
				var theRow = $(this);
				/*Configuring time plugin*/
				$(this).find('.start').datetimepicker({
					ignoreReadonly:true,
					allowInputToggle:true,
					format:'HH:mm',
				});
				$(this).find('.end').datetimepicker({
					ignoreReadonly:true,
					allowInputToggle:true,
					format:'HH:mm',
				});

				/*onchange of start time reseting end time to avoid start time less than end time condition*/
				$(this).find('.start').on("dp.change", function (selected) {
										
					startTime= $.trim($(this).val()) == "" ? "00:00" : $.trim($(this).val());
					if( startTime == "00:00"){
						$(this).closest("tr").find(".end").data("DateTimePicker").clear();
					}else{
						startTime = $.trim($(this).val());
						minDate = moment(startTime,'HH:mm');
						$(this).closest("tr").find(".end").data("DateTimePicker").minDate(minDate);
						$(this).closest("tr").find(".end").data("DateTimePicker").date(startTime);
					}
					
					if( i == 0 && $('#workTimingDetailsEdit .selectAll').prop('checked') == true){
						$('#workTimingDetailsEdit .start').val($(this).val())
					}
					
				});
				/*onchange of end time seting end time for all when select all check box is on*/
				 $(this).find('.end').on("dp.change", function (selected) {
					if( i == 0 && $('#workTimingDetailsEdit .selectAll').prop('checked') == true){
						$('#workTimingDetailsEdit .end').val($(this).val())
					}
				}); 
				/*onchange of checkbox state disabled/enabled text box*/
				$(this).find("input[type='checkbox']").change(function(){
					if($(this).prop('checked') == false){
						theRow.find("input[type='text']").attr('disabled','disabled');
						theRow.find("input[type='text']").data("DateTimePicker").clear();
					}else{
						theRow.find("input[type='text']").removeAttr('disabled');
						theRow.find(".start").data("DateTimePicker").date('00:00');
					}
				})
			});
				
			/* onchange of select All-checkbox state disabled/enabled textbox and checked/unchecked checkbox*/
			$('#workTimingDetailsEdit .selectAll').click(function(){
				if($(this).prop('checked') == true){
					$("#workTimingDetailsEdit table tbody tr input[type='checkbox']").prop('checked', true);
					$('#workTimingDetailsEdit input[type="text"]').removeAttr('disabled');
					//$('#workTimingDetailsEdit input[type="text"]').val();
					$("#workTimingDetailsEdit table tbody tr .start").each(function(i){
						$(this).data("DateTimePicker").date('00:00');
					})
					$("#workTimingDetailsEdit table tbody tr .end").each(function(i){
						$(this).data("DateTimePicker").date('00:00');
					})
					
				}else{
					$("#workTimingDetailsEdit table tbody tr input[type='checkbox']").prop('checked', false);
					$('#workTimingDetailsEdit input[type="text"]').attr('disabled','disabled');
					//$('#workTimingDetailsEdit input[type="text"]').data("DateTimePicker").clear();
					$("#workTimingDetailsEdit table tbody tr .start").each(function(i){
						$(this).data("DateTimePicker").clear();
					})
					$("#workTimingDetailsEdit table tbody tr .end").each(function(i){
						$(this).data("DateTimePicker").clear();
					})
				}
			});
			
			/* ----------------------------------------------------------------------------------------*/
			var currencyhtml ="";
			var currencyhtml1 ="";
			var container = $('#productivityDetailsEditCommon .pro');
			container.html("");

			for(var i=0;i<procurdata.length; i++){
				container.append('<div class="col-md-4" id="proCurrList'+i+'"><label class="prod_leaf_node"><input type="checkbox" value="'+procurdata[i].product_id+'">  '+procurdata[i].productname+'</label></div>');
				if(procurdata[i].curdata[0].currency_id !=null) {
					currencyhtml="";
					currencyhtml +='<div class="multiselect">';
					currencyhtml +='<ul>';
					for(var j=0;j<procurdata[i].curdata.length; j++){
						if(procurdata[i].curdata[j].currency_id !=null){
							currencyhtml +='<li><label><input type="checkbox" value="'+procurdata[i].curdata[j].currency_id+'" disabled>  '+procurdata[i].curdata[j].currencyname+'<label></li>';
						}
					}
					currencyhtml +='</ul>';
					currencyhtml +='</div>';
					$("#proCurrList"+i).append(currencyhtml)
				}

			}
			
			for(var i=0;i<procurdata.length; i++){
				if(procurdata[i].hasOwnProperty('curdata')) {

				}else{
					currencyhtml1 += '<div class="col-md-12"><label class="prod_leaf_node"><input type="checkbox" value="'+procurdata[i].product_id+'">  '+procurdata[i].productname+'</label></div>';
				}
			}
			if( currencyhtml1.length > 0){
				container.append("<div class='without-curr col-md-6'>"+ currencyhtml1 +"</div>");
			}
			/*-------Saved product checkbox highlight---*/
			procur = seleUserData.procur
			if(procur.length != 0){
				$("#productivityDetailsEditCommon .pro .prod_leaf_node input[type='checkbox']").each(function(){
					for(chk=0; chk<procur.length; chk++){
						if($(this).val() == procur[chk].product_id){
							if($(this).closest(".col-md-4").find(".multiselect").length > 0){
								$(this).closest("label").addClass("highlight");
							}
							$(this).prop("checked", true);
							$(this).closest(".col-md-4").find(".multiselect").find("input[type='checkbox']").prop("disabled", false);
							$(this).closest(".col-md-4").find(".multiselect").find("input[type='checkbox']").each(function(){
								for(chk1=0; chk1<procur[chk].curdata.length; chk1++){
									if($(this).val() == procur[chk].curdata[chk1].currency_id){
										$(this).prop("checked", true).prop("disabled", false);
									}
								}
							})
						}
					}
				})
			}
			/*-------Change product checkbox---*/
			$("#productivityDetailsEditCommon .pro .prod_leaf_node input[type='checkbox").each(function(i){
				var selected = $(this);
				selected.change(function(){
					if(selected.prop("checked") == true){
						$('#error').text('');
						selected.closest(".row").find(".col-md-4").removeAttr("style");
						selected.closest("label").removeClass("error").addClass("highlight");
						selected.closest(".col-md-4").find(".multiselect").find("input[type=checkbox]").removeAttr("disabled");
					}else{
						selected.closest("label").removeClass("error").removeClass("highlight");
						selected.closest(".col-md-4").removeAttr("style")
						selected.closest(".col-md-4").find(".multiselect").find("input[type=checkbox]").attr('disabled', 'disabled');
						selected.closest(".col-md-4").find(".multiselect").find("input[type=checkbox]").prop('checked', false);
					}
				})
				
			})
			/* ----------------------------------------------------------------------------------------*/
			
		},
		error : function(data){
			network_err_alert(data)
		}
	})
}
/* ----------------------------------------------------------------------------------------*/
/* ------------------------------Edit in view end ---------------------------------------------*/
/* ----------------------------------------------------------------------------------------*/
/* ------------------------- */
function businessClienteleView(industry, businessloc){
	var clientLlistV="";
	for(slNo=0; slNo < industry.length; slNo++){
		clientLlistV += '<li><i class="fa fa-caret-right" aria-hidden="true"></i> '+industry[slNo].clientInds+"<b> ("+industry[slNo].clientInds1+")</b>"+'</li>';
	}

	var businessLocV = '';
	for(slNo=0; slNo < businessloc.length; slNo++){
		businessLocV += '<li><i class="fa fa-map-marker" aria-hidden="true"></i> '+businessloc[slNo].bussinessLoc+"<b> ("+businessloc[slNo].bussinessLoc1+")</b>"+'</li>';
	}
	
	var businessClientele = '<div class="col-md-2"><label><b>Business Location</b></label></div><div class="col-md-4"><ul>'+businessLocV+'</ul></div><div class="col-md-2"><label><b>Clientele Industries</b></label></div><div class="col-md-4"><ul>'+clientLlistV+'</ul></div>'
	$("#businessInfo .row.view").html("").append(businessClientele);
	
}

function editBusinessClient(){
	$('#businessInfo .edit').show();
	$('#businessInfo .view').hide();
	editDetails();
	$('#businessInfo .edit-btn').hide();
}
function saveBusinessClientele(){
	$('#businessInfo').find('.error-alert').remove();
	var business = $('#businessInfo .business.multiselect input[type="checkbox"]');
	var clientele = $('#businessInfo .clientele.multiselect input[type="checkbox"]');
	var busCli = {};
	busCli.business =[];
	busCli.clientele =[];
	busCli.user_id =seleUserData.user[0].user_id;
	
	business.each(function(i){
		if($(this).prop('checked') == true){
			nodeid = $(this).val();
			bussinessLoc = $.trim($(this).closest('label').find('span').html());
			bussinessLoc1 = $.trim($(this).closest('label').find('b').html()).replace("(","").replace(")","");
			busCli.business.push({'bussinessLoc':bussinessLoc,'bussinessLoc1':bussinessLoc1,'nodeid':nodeid})
		}
	})
	clientele.each(function(i){
		if($(this).prop('checked') == true){
			nodeid = $(this).val();
			clientInds = $.trim($(this).closest('label').find('span').html());
			clientInds1 = $.trim($(this).closest('label').find('b').html()).replace("(","").replace(")","");
			busCli.clientele.push({'clientInds':clientInds,'clientInds1':clientInds1,'nodeid':nodeid})
		}
	})
	if(busCli.business.length == 0){
		$('#businessInfo .business.multiselect').closest('.row.edit').append('<div class="col-md-6 error-alert text-center">Please select Business location.</div><div class="col-md-6 error-alert"></div>');
		return;
	}else if(busCli.clientele.length == 0){
		$('#businessInfo .clientele.multiselect').closest('.row.edit').append('<div class="col-md-6 error-alert"></div><div class="col-md-6 error-alert text-center">Please select any one of Clientele Industries.</div>');
		return;
	}else{
		$('#businessInfo').find('.error-alert').remove();
	}
	businessClienteleView(busCli.clientele, busCli.business);
	closeBusinessClientele();
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('manager_teamManagersController/updateUserData'); ?>",
		dataType : 'json',
		data : JSON.stringify(busCli),
		cache : false,
		success : function(data){
			if(error_handler(data)){
				return;
			}
			$.alert({
				title: 'L Connectt',
				content: 'Data has been saved successfully.',
				closeIcon: true,
				closeIconClass: 'fa fa-close',
			});
		},
		error : function(data){
			network_err_alert(data)
		}
	})
}
function closeBusinessClientele(){
	$('#businessInfo .edit').hide();
	$('#businessInfo .view').show();
	$('#businessInfo .edit-btn').show();
	$('#businessInfo').find('.error-alert').remove();
}
/* ------------------------- */
function renderprodCrrView(prodCurr){
	
	var productLi ="";
	for(slNo=0;slNo<prodCurr.length; slNo++){
		var currLi = "";
		for(slNo1=0;slNo1<prodCurr[slNo].curdata.length; slNo1++){
			if(prodCurr[slNo].curdata[slNo1].currencyname != null){
				currLi +='<div><i><u>'+prodCurr[slNo].curdata[slNo1].currencyname+'</u></i></div>';
			}else{
				currLi +='<div><i><u>No Currency Defined</u></i></div>';
			}
		}

		productLi +='<li><div class="prd"><i class="fa fa-product-hunt fa-2" aria-hidden="true"></i>'+prodCurr[slNo].productname+'</div><div class="crr">'+currLi+'</div></li>';
		
	}
	$("#prodCurrInfo .productLi").html("").append(productLi);
}
function spendCalView(prodetails){
	var SpendCalculationVal = '';
	if(prodetails.accounting == '0'){
		SpendCalculationVal = '<div class="col-md-12">'+
		'<label><b>Spend Calculation</b></label>&nbsp;&nbsp;&nbsp;&nbsp;<label>Disabled</label>'+
		'</div>';
	}else{
		SpendCalculationVal = '<div class="col-md-12">'+
		'<label><b>Spend Calculation</b></label>&nbsp;&nbsp;&nbsp;&nbsp;<label>Enabled</label>'+
		'</div>'+
		'<div class="col-md-4">'+
		'<label><b>Resource Cost/Hour</b> </label></br>'+
		'<label>'+prodetails.resource_curcyName+'( '+prodetails.resource_cost+' )</label>'+
		'</div>'+
		'<div class="col-md-4">'+
		'<label><b>Cost/Outgoing Call/Min</b> </label></br>'+
		'<label>'+prodetails.outgoingcall_curcyName+'( '+prodetails.outgoingcall_cost+' )</label>'+
		'</div>'+
		'<div class="col-md-4">'+
		'<label><b> Cost/Outgoing SMS</b> </label></br>'+
		'<label>'+prodetails.outgoingsms_curcyName+'( '+prodetails.outgoingsms_cost+' )</label>'+
		'</div>';
	}
	$('#prodCurrInfo .view.spend-cal').html('').append(SpendCalculationVal)
}
function renderWeekDaysView(workdetails){
	var daylist = "<table class='table table-striped'>";
		daylist += '<thead><tr><th>Day</th><th>Start Time</th><th>End Time</th></tr></thead><tbody>';
		for(slNo=0; slNo<workdetails.length; slNo++){
			daylist += '<tr><td>'+workdetails[slNo].day_of_week+'</td><td>'+workdetails[slNo].start_time+'</td><td>'+workdetails[slNo].end_time+'</td></tr>';
		}
		daylist += "</tbody></table>";
		
	$("#prodCurrInfo .view.weekDays").html("").append(daylist);
}
function editProductivityDetails(){
	editDetails();
	$('#productivityDetailsEditCommon').show();
	$('#prodCurrInfo .view').hide();
	$('#prodCurrInfo .edit-btn').hide();
} 
function saveProductivityDetails(){
	var prod = $("#productivityDetailsEditCommon .pro .prod_leaf_node input[type='checkbox']");
	var spendCal = $("#productivityDetailsEditCommon .spend-calculation input[type='checkbox']");
	var cal = $('#productivityDetailsEditCommon .cal-name');
	var week = $('#productivityDetailsEditCommon .timing-details tbody tr input[type="checkbox"]');
	
	var isProdChked = 0, isCurChked = 0, curErr =0, isDayChecked = 0,isTimeSet = 0;
	
	var prodData = [],weekData = [],spedCalData = {};
	prod.each(function(){
		if($(this).prop('checked') == true){
			isProdChked = 1;
			isCurChked = 0;
			var curdata = [];
			if($(this).closest('.col-md-4').find('.multiselect').length > 0){
				$(this).closest('.col-md-4').find('.multiselect input[type="checkbox"]').each(function(){
					if($(this).prop('checked') == true){
						isCurChked = 1;
						curdata.push({"currency_id" : $(this).val(),"currencyname" : $.trim($(this).closest('label').text())});
					}
				})
				if(isCurChked == 0){
					curErr =1
					$(this).closest('.prod_leaf_node').addClass('error');
				}
			}else{
				curdata.push({"currency_id" : null ,"currencyname" : null });
			}
			prodData.push({"curdata" : curdata ,"product_id" : $(this).val(),"productname" : $.trim($(this).closest('.prod_leaf_node').text())});
			
		}
	})
	
	if(isProdChked == 0){
		$('#productivityDetailsEditCommon .prod_leaf_node').closest('.col-md-4').attr("style","border: 1px solid red");
		$("#productivityDetailsEditCommon").find('.errorText.error-alert').remove();
		$("#productivityDetailsEditCommon .pro").prepend('<center class="errorText error-alert"><b>Please select atleast one product.</b></center>');
		return;
	}else if(curErr == 1){
		$("#productivityDetailsEditCommon").find('.errorText.error-alert').remove();
		$("#productivityDetailsEditCommon .pro").prepend('<center class="errorText error-alert"><b>Please select atleast one curreny from selected product(s).</b></center>');
		return;
	}else{
		$("#productivityDetailsEditCommon").find('.errorText.error-alert').remove();
		$('#productivityDetailsEditCommon .prod_leaf_node').removeClass('error');
	}
	/* ----------------- */
	$("#productivityDetailsEditCommon .spend-calculation .error-alert").remove();
	
	if(spendCal.prop('checked') == true){
		var crncyChk = 0,crncyValChk = 0;
		spedCalData.accounting = '1';
		$("#productivityDetailsEditCommon .spend-calculation .curr.row").each(function(i){
			var crr = $(this).find('select');
			var amount = $(this).find('input[type="text"]');
			if(crr.val() == ""){
				switch(i) {
					case 0:
						crr.closest('div').append('<span class="error-alert">Select Resource Cost/Hour.</span>');
						crncyChk = 1;
						break;
					case 1:
						crr.closest('div').append('<span class="error-alert">Select Cost/Outgoing Call/Min.</span>');
						crncyChk = 1;
						break;
					case 2:
						crr.closest('div').append('<span class="error-alert">Select Cost/Outgoing SMS.</span>');
						crncyChk = 1;
				}
			}else{
				switch(i) {
					case 0:
						spedCalData.resource_currency = crr.val();
						spedCalData.resource_curcyName = $.trim($(this).find('select option:selected').text());
						break;
					case 1:
						spedCalData.outgoingcall_currency = crr.val();
						spedCalData.outgoingcall_curcyName = $.trim($(this).find('select option:selected').text());
						break;
					case 2:
						spedCalData.outgoingsms_currency = crr.val();
						spedCalData.outgoingsms_curcyName = $.trim($(this).find('select option:selected').text());
				}
			}
			if($.trim(amount.val()) == ""){
				switch(i) {
					case 0:
						amount.closest('div').append('<span class="error-alert">Resource Cost/Hour value is required.</span>');
						crncyValChk = 1;
						break;
					case 1:
						amount.closest('div').append('<span class="error-alert">Cost/Outgoing Call/Min  value is required.</span>');
						crncyValChk = 1;
						break;
					case 2:
						amount.closest('div').append('<span class="error-alert">Cost/Outgoing SMS  value is required.</span>');
						crncyValChk = 1;
				}
			}else{
				switch(i) {
					case 0:
						spedCalData.resource_cost = $.trim(amount.val());
						break;
					case 1:
						spedCalData.outgoingcall_cost = $.trim(amount.val());
						break;
					case 2:
						spedCalData.outgoingsms_cost = $.trim(amount.val());	
				}
			}
			if(!currencyValidation(amount.val())){
				amount.closest('div').append('<span class="error-alert">Invalid currency format.</span>');
				crncyValChk = 1;
			}
		});
		if(crncyChk == 1 || crncyValChk == 1){
			return;
		}
		
	}else{
		spedCalData.accounting = '0';
		spedCalData.outgoingsms_cost = "";
		spedCalData.outgoingcall_cost = "";
		spedCalData.resource_cost = "";
		spedCalData.resource_currency = "";
		spedCalData.resource_curcyName = "";
		spedCalData.outgoingcall_currency = "";
		spedCalData.outgoingcall_curcyName = "";
		spedCalData.outgoingsms_currency = "";
		spedCalData.outgoingsms_curcyName = "";
	}
	/* ----------------- */
	if(cal.find('select').val() == '0'){
		cal.append('<center class="errorText error-alert">Please select Holiday calendar.</center>');
		return;
	}else{
		$("#productivityDetailsEditCommon").find('.errorText.error-alert').remove();
	}
	/* ----------------- */
	week.each(function(){
		if($(this).prop('checked') == true){
			isDayChecked = 1;
			start_time = $.trim($(this).closest('tr').find('.start').val());
			end_time = $.trim($(this).closest('tr').find('.end').val());
			if(start_time == ""){
				isTimeSet = 1;
			}else if(end_time == ""){
				isTimeSet = 1;
			}else{
				weekData.push({"day_of_week":$(this).val(),"start_time":start_time,"end_time":end_time,});
			}
		}
	})
	if(isDayChecked == 0){
		$("#productivityDetailsEditCommon .timing-details").prepend('<center class="errorText error-alert"><b>Please select working days and working hours.</b></center>');
		return;
	}else if(isTimeSet == 1){
		$("#productivityDetailsEditCommon .timing-details").prepend('<center class="errorText error-alert"><b>Start Time and End Time Cannot be Blank.</b></center>');
		return;
	}else{
		$("#productivityDetailsEditCommon").find('.errorText.error-alert').remove();
	}
	/* --------reseting temp data (seleUserData)--------- */
	seleUserData.procur = prodData;
	seleUserData.prodetails[0].holiday_calender = cal.find('select').val();
	
	seleUserData.prodetails[0].accounting = spedCalData.accounting;
	seleUserData.prodetails[0].outgoingsms_cost = spedCalData.outgoingsms_cost;
	seleUserData.prodetails[0].outgoingcall_cost = spedCalData.outgoingcall_cost;
	seleUserData.prodetails[0].resource_cost = spedCalData.resource_cost;
	seleUserData.prodetails[0].resource_currency = spedCalData.resource_currency;
	seleUserData.prodetails[0].resource_curcyName = spedCalData.resource_curcyName;
	seleUserData.prodetails[0].outgoingcall_currency = spedCalData.outgoingcall_currency;
	seleUserData.prodetails[0].outgoingcall_curcyName = spedCalData.outgoingcall_curcyName;
	seleUserData.prodetails[0].outgoingsms_currency = spedCalData.outgoingsms_currency;
	seleUserData.prodetails[0].outgoingsms_curcyName = spedCalData.outgoingsms_curcyName;
	
	seleUserData.workdetails = weekData;
	/* ----------------- */
	
	spendCalView(spedCalData);
	renderprodCrrView(prodData);
	renderWeekDaysView(weekData);
	closeProductivityDetails();
	
	var productivityDetails = {};
	productivityDetails.holiday_calender = cal.find('select').val();
	productivityDetails.spedCalData = spedCalData;
	productivityDetails.prodData = prodData;
	productivityDetails.weekData = weekData;
	productivityDetails.user_id =seleUserData.user[0].user_id;
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('manager_teamManagersController/updateProductivity'); ?>",
		dataType : 'json',
		data : JSON.stringify(productivityDetails),
		cache : false,
		success : function(data){
			if(error_handler(data)){
				return;
			}
			$.alert({
				title: 'L Connectt',
				content: 'Data has been saved successfully.',
				closeIcon: true,
				closeIconClass: 'fa fa-close',
			});
		},
		error : function(data){
			network_err_alert(data)
		}
	})
} 
function closeProductivityDetails(){
	$('#productivityDetailsEditCommon').hide();
	$('#prodCurrInfo .view').show();
	$('#prodCurrInfo .edit-btn').show();
}
/* ------------------------- */
function blckUser(btntext,user_id){
       var obj={};
       obj.btntext=btntext;
       obj.userid=user_id;
       if(btntext == 'Lock Phone'){
            $("body").append('<div class="mask custom-alert"></div><div class="alert alert-danger row custom-alert"> <div class="col-md-9 style="color:#fff"">This will restrict this users access to L Connectt on their mobile device. Do you wish to continue</div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
       }else{
            $("body").append('<div class="mask custom-alert"></div><div class="alert alert-danger row custom-alert"> <div class="col-md-9 style="color:#fff"">This will open this users access to L Connectt on their mobile device. Do you wish to continue</div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');
       }

       $(".Ok").click(function(){
            $(".custom-alert").remove();
            $.ajax({
				type : "POST",
				data : JSON.stringify(obj),
				url : "<?php echo site_url('lconnectt_commonController/lock_unlock_ph')?>",
				dataType : 'json',
				cache : false,
				success : function(targetdata){
					if(error_handler(targetdata)){
							return;
					}
                    var btntext="";
					var btncolor="";
					if(parseInt(targetdata)== 0 || parseInt(targetdata)==1){
						$('#deviceInfo #blckid').attr(
													{'style': 'background: #B5000A !important',
													'onclick': 'blckUser("Lock Phone", "'+user_id+'")'}
													);
						$('#deviceInfo #blckid').text('Lock Phone');
					}else{
					$('#deviceInfo #blckid').attr({'style': 'background: green !important',
												'onclick': 'blckUser("UnLock Phone", "'+user_id+'")'});
                        $('#deviceInfo #blckid').text('UnLock Phone');

                    }
				},
				error : function(data){
					network_err_alert(data)
				}
			});
       });
	   $(".notOk").click(function(){
	        $(".custom-alert").remove();
	   });


}

/* ------------------------- */
function resetUser(user_id){
       var obj={};

       obj.userid=user_id;
       $("body").append('<div class="mask custom-alert"></div><div class="alert alert-danger row custom-alert"> <div class="col-md-9 style="color:#fff"">Are you sure you want to Reset Device Details?</div><div class="col-md-3"><span class="btn Ok">Ok</span>&nbsp;&nbsp;<span class="btn notOk">Cancel</span></div></div>');


       $(".Ok").click(function(){
            $(".custom-alert").remove();
            $.ajax({
				type : "POST",
				data : JSON.stringify(obj),
				url : "<?php echo site_url('lconnectt_commonController/reset_ph')?>",
				dataType : 'json',
				cache : false,
				success : function(targetdata){
					if(error_handler(targetdata)){
							return;
					}

                    devcInfo="<p><center>Device Details got Reset Sucessfully. Please Reinstall the Mobile Application.</center></p> ";
                    $(".deviceInfo").html("").append(devcInfo);
				},
				error : function(data){
					network_err_alert(data)
				}
			});
       });
	   $(".notOk").click(function(){
	        $(".custom-alert").remove();
	   });


}


function close_target(){
	$('#targetaddbtnID').show();
	$("#target_area_com").hide();
	$("#targetInfo .add.glyphicon.glyphicon-plus").show();
	
	$("#target_area_com .targetCurrencyHide ,#target_area_com .targetEndDate").hide();
	
	$("#target_area_com").hide();
	$("#target_area_com").find('.error-alert').text("");
	date = '#target_area_com .row.input_field .date input[type = "text"]';
	txt = "#target_area_com #change_sales_type input[type='text']";
	select = "#target_area_com .row.input_field select";
	
	$(select + ',' + txt +','+ date).each(function(i){
		$(this).val("")
	});
	
	$("#target_area_com .row.input_field input[name = 'slTyp']").each(function(i){
		if(i == 0){
			$(this).prop('checked', true);
		}
	});
	
	$("#target_area_com .row.input_field input[name = 'checkedTarget']").each(function(i){
		if(i == 0){
			$(this).prop('checked', true);
		}
	});
	change_sales_type('Target');
	$("#prodCurr").html("");
	
	
}
/*target Add rendering ------------*/
var targetCurrencyList = "";
function add_target_view(data){
	
	targetCurrencyList = "";
	$('#target_area_com .targetCurrencyHide').hide();
/* 	$('#target_area_com .startDate').hide();
	$('#target_area_com .endDate').hide(); */
	
	pdOpt = [];
	for(pd=0; pd< data.procur.length; pd++){
		if(data.procur[pd].product_id != ""){
			pdOpt.push('<option value="'+data.procur[pd].product_id+'">'+ data.procur[pd].productname + '</option>');
		}
	}

	var Split = [];
	for(st=0; st< data.selltype.length; st++){
		if( data.selltype[st].map_id != "Renewal"){
			Split.push(data.selltype[st].map_id);
		}
	}
	for(st=0; st< data.selltype.length; st++){
		if( data.selltype[st].map_id == "Renewal"){
			Split.push(data.selltype[st].map_id);
		}
	}
	var Consolidated = "Target";
	var Split_s = Split.join(',');
	var custAssing = JSON.parse(data.modules[0].module_id);
	var mod =[], checkedTarget = "";
	if(custAssing.cxo != 0){
		mod.push(" CXO ");
	}if(custAssing.sales != 0){
		mod.push("individual Target");
	}if(custAssing.Manager != 0){
		mod.push("team Target");
	}
	$.each(mod, function( index, value ){
		if(index == 0){
			checkedTarget += '<div class="col-md-3"></div><div class="col-md-3"><label><input checked type="radio" value="'+value.split(" ").join("")+'" name="checkedTarget"/> ' + value +'</label></div>';
		}else if(index == 1){
			checkedTarget += '<div class="col-md-3"><label><input type="radio" value="'+value.split(" ").join("")+'" name="checkedTarget"/> ' + value +'</label></div><div class="col-md-3"></div>';
		}
		
	}); 
	targetCurrencyList = window.btoa(JSON.stringify(data.procur));
	
	var add_target = '<div id="targetaddbtnID" class="row"><span title="Add Target" onclick="target_add_btn(\'add\')" class="pull-right add glyphicon glyphicon-plus"></span></div>'+
			'<div id="target_area_com" class="none">'+
			'<div class="row input_field text-center" style="text-transform: capitalize;">'+
				checkedTarget +
			'<br><br></div>'+
			'<div class="row input_field">'+
				'<div class="col-md-2">'+
					'<label>Product * </label>'+
				'</div>'+
				'<div class="col-md-4">'+
					'<select name="targetProduct" class="form-control" onchange="get_pdCur(this.value, \''+window.btoa(JSON.stringify(data.procur))+'\')">'+
						'<option value="">Choose Product</option>'+
						pdOpt.join('')+
					'</select>'+
					'<span class="error-alert"></span>'+
				'</div>'+
				'<div class="col-md-2">'+
					'<label>Target type *</label>'+
				'</div>'+
				'<div class="col-md-4">'+
					'<select name="ContactLocation" class="form-control" onchange="targetType(this.value)">'+
						'<option value="">Choose</option>'+
						'<option value="numbers">Numbers</option>'+
						'<option value="revenue">Revenue</option>'+
					'</select>'+
					'<span class="error-alert"></span>'+
				'</div>'+
			'</div>'+
			'<div class="row input_field">'+
				'<div class="col-md-2">'+
					'<label>Period *</label>'+
				'</div>'+
				'<div class="col-md-4">'+
					'<select name="targetPeriod" class="form-control" onchange="targetPeriod(this.value)">'+
						'<option value="">Choose</option>'+
						'<option value="Monthly">Monthly</option>'+
						'<option value="Quarterly">Quarterly</option>'+
						'<option value="Half Yearly">Half Yearly</option>'+
						'<option value="Yearly">Yearly</option>'+
						'<option value="Custom">Custom</option>'+
					'</select>'+
					'<span class="error-alert"></span>'+
				'</div>'+
				'<div class="col-md-2 targetCurrencyHide">'+
					'<label>Currency type * </label>'+
				'</div>'+
				'<div class="col-md-4 targetCurrencyHide">'+
					'<select name="targetCurrency" class="form-control" id="prodCurr">'+
					'</select>'+
					'<span class="error-alert"></span>'+
				'</div>'+
			'</div>'+
			'<div class="row input_field ">'+
				'<div class="col-md-2 targetStartDate">'+
					'<label>Start Date *</label>'+ 
				'</div>'+
				'<div class="col-md-4 targetStartDate">'+	
					'<div class="input-group date">'+
					   '<input placeholder="Select Date" type="text" class="form-control" readonly="readonly">'+
					   '<span class="input-group-addon">'+
					   '<span class="glyphicon glyphicon-calendar"></span>'+
					   '</span>'+
					'</div>'+
					'<div>'+
						'<span class="error-alert"></span>'+
					'</div>'+											
				'</div>'+
				'<div class="col-md-2 targetEndDate">'+
					'<label>End Date *</label>'+ 
				'</div>'+
				'<div class="col-md-4 targetEndDate">'+	
					'<div class="input-group date">'+
					   '<input placeholder="Select Date" type="text" class="form-control" readonly="readonly">'+
					   '<span class="input-group-addon">'+
					   '<span class="glyphicon glyphicon-calendar"></span>'+
					   '</span>'+
					'</div>'+
					'<div>'+
						'<span class="error-alert"></span>'+
					'</div>'+
				'</div>'+									
			'</div>'+
			'<center>'+
				'<h4><b>Sales Type </b></h4>'+
			'</center>'+
			'<div class="row input_field">'+
				'<div class="col-md-3">'+
					'<label>'+
					'<input type="radio" value="Consolidated" name="slTyp" onchange="change_sales_type(\''+Consolidated+'\')" checked />'+
					' Consolidated</label>'+
				'</div>'+
				'<div class="col-md-3">'+
					'<label>'+
					'<input type="radio" value="Split" name="slTyp" onchange="change_sales_type(\''+Split_s+'\')"/>'+
					' Split</label>'+
				'</div>'+
			'</div>'+
			'<div class="row input_field" id ="change_sales_type">'+
			'</div>'+
			'<div class="row" style="margin:10px 0px">'+
				'<div class="col-md-6 text-right">'+
					'<input type="button" class="btn btn-default" onclick="save_target1(\''+data.user[0].user_id+'\')" value="Save">'+
				'</div>'+
				'<div class="col-md-6 text-left">'+
					'<input type="button" class="btn btn-default" onclick="close_target()" value="Cancel">'+
				'</div>'+
			'</div>'+
		'</div>';

	$(".targetInfo").append(add_target);
	change_sales_type(Consolidated);
	
	$("#target_area_com .targetEndDate").hide();
	$('#target_area_com .targetStartDate .date').datetimepicker({
		ignoreReadonly:true,
		allowInputToggle:true,
		format:'DD-MM-YYYY'
	});
	$('#target_area_com .targetEndDate .date').datetimepicker({
		ignoreReadonly:true,
		allowInputToggle:true,
		format:'DD-MM-YYYY'
	});
	$('#target_area_com .targetEndDate .date').on("dp.change", function (selected) {
		var startDateTime = moment($.trim($('#target_area_com .targetStartDate input').val()), 'DD-MM-YYYY');
		$(this).data("DateTimePicker").minDate(startDateTime.add(1, 'days'));			
	})
	
	
	var elm = $("#targetaddbtnID");
	checkPermission(elm);
	
}
/*target Tab rendering ------------*/
function target_tbl(targetdata , targetTypeSection){
	$("#targetDisplaySection").html('');
	var target_row = '';
	var add ='';
	var individualTarget = [],teamTarget = [];
	if(targetdata.length > 0){ 
		
		for(var i=0;i<targetdata.length; i++){
			var rowdata= window.btoa(JSON.stringify(targetdata[i]));
			var target1 = JSON.parse(targetdata[i].target_data);
			if(target1.checked == "individualTarget" ){
				individualTarget.push(targetdata[i])
			}else if(target1.checked == "teamTarget"){
				teamTarget.push(targetdata[i])
			}
		}
	}
	
	/* -- for tab view--
	target_row += add+'<ul class="nav nav-tabs">'+
					'<li class="'+targetTypeSection+ '+(teamTarget.length == 0 ? "hide" : "" )+'"><a data-toggle="tab" href="#teamTargetTable">Team Target</a></li>'+
					'<li  class="'+(teamTarget.length == 0 ? "active" : "" )+'"><a data-toggle="tab" href="#individualTargetTable">Individual Target</a></li>'+
				'</ul>';
	 */
				
	if(targetdata.length > 0){ 
		/* -- for tab view--
		target_row += '<div class="tab-content">'+
					'<div id="teamTargetTable" class="tab-pane fade '+(teamTarget.length == 0 ? "hide" : "active in" )+'">'+ targetTable( window.btoa(JSON.stringify(teamTarget)) ) + '</div>'+
					'<div id="individualTargetTable" class="tab-pane fade '+(teamTarget.length == 0 ? "active in" : "" )+'">'+ targetTable(window.btoa(JSON.stringify(individualTarget))) + '</div>'+
				'</div>';
		*/
		target_row += '<div class="row">'+
					'<div class="'+(teamTarget.length == 0 ? "hide" : "" )+'">'+ 
						targetTable( window.btoa(JSON.stringify(teamTarget)), 'Team Target') + 
					'</div>'+
					'<div class="'+(individualTarget.length == 0 ? "hide" : "" )+'">'+ 
						targetTable(window.btoa(JSON.stringify(individualTarget)),'Individual Target') + 
					'</div>'+
				'</div>';
	}else{
		target_row = "<center><b>No target added</b></center>"
	}
	$("#targetInfo").append('<div class="row" id="targetDisplaySection"></div>');
	$("#targetDisplaySection").append(target_row);
	
	
	var elm = $("#targetDisplaySection .table.table-bordered .edit");
	checkPermission(elm);
}
/*target Table rendering ------------*/
function checkPermission(elm){
	checkSuperior = seleUserData.permission;
	if(	checkSuperior.canAddHimself == "Yes" || checkSuperior.canEditHimself == "Yes" && checkSuperior.loginUser == checkSuperior.user_id ){
		elm.show();
	}else if((checkSuperior.canAdd == "Yes" || checkSuperior.canEdit == "Yes") && checkSuperior.loginUser == checkSuperior.user_id ){
		elm.hide();
	}else if(LogggedInUserRole == 1){
		//elm.hide();
	}else{
		elm.show();
	}
	if(LogggedInUserRole == 1){
		if(elm.attr('id') == 'targetaddbtnID'){
			elm.hide();
		}
	}
}
function targetTable(targetdata , title){
	targetdata = JSON.parse(window.atob(targetdata));
	var add ='';
	var target_row = '';
	if(targetdata.length > 0){
		target_row += '<table class="table table-bordered">'+
							'<thead>'+
								'<tr><th colspan="8" class="text-center">'+ title +'</th></tr>'+
								'<tr>'+
									'<th>#</th>'+
									'<th>Product</th>'+
									'<th>Period</th>'+
									'<th>Date Range</th>'+
									'<th>Target Model</th>'+
									/* '<th>Date</th>'+ */
									'<th>Target Type</th>'+ 
									'<th>Target</th>'+
									'<th class="edit">Edit</th>'+
									/* '<th>Currency Name</th>'+add+ */
								'</tr>'+
							'</thead><tbody>';


		for(var i=0;i<targetdata.length; i++){
			var target1 = JSON.parse(targetdata[i].target_data) ;
			
			 
			/*edit ='<td></td>';*/
			/*------------------ Month calculation ----------------------*/
			moment.addRealMonth = function addRealMonth(d , monthCount) {
			  var fm = moment(d).add(monthCount, 'M');
			  var fmEnd = moment(fm).endOf('month');
			  return d.date() != fm.date() && fm.isSame(fmEnd.format('DD-MM-YYYY')) ? fm.add(1, 'd') : fm;  
			}
			var nextMonth = ""
			
			var period = {"Monthly":1, "Quarterly":3, "HalfYearly":6, "Yearly":12};
			
			$.each( period, function(key, val){
				if(target1.period.split(' ').join('') == key){
					nextMonth = moment.addRealMonth(moment(target1.startDate,'DD-MM-YYYY') , val);
					nextMonth =  moment(nextMonth).subtract(1, "days").format("DD-MM-YYYY");
				}	
			})
			/*----------------------------------------*/
			var date = target1.endDate == "" ? target1.startDate +" to "+ nextMonth : target1.startDate +" to "+ target1.endDate;
			var currency = targetdata[i].currency_name == null ? ["",""] : targetdata[i].currency_name.split('-');
			currency = currency[1].trim() == "" ? "" : ' ('+currency[1].trim()+')';
			
			if(target1.category == "Split"){
				count = 0, j=0;
				$.each( target1.category_details, function(key, val){
					if(val.value != ""){
						count++;
					}
				})

				$.each( target1.category_details, function(key, val){
					if(val.value != ""){
						j++;
						TargetModel =	'<td>' + capitalizeFirstLetter(key).split('_').join(' ') +'</td>';
										/* '<td>' + val.start_date + '</td>' */
						TargetValue = 	'<td>' + (Math.floor(parseInt(val.value) / 1)).toLocaleString() + '</td>'; 
						edit = '<td class="edit"><a data-toggle="modal" href="#edit_target" onclick="editTarget(\''+window.btoa(targetdata[i].target_data)+'\', \''+capitalizeFirstLetter(key)+'\', \''+targetdata[i].target_id+'\')"><span class="glyphicon glyphicon-pencil"></span></a></td>';
						if(j == 1){
							target_row +='<tr style="cursor:auto">'+
									'<td rowspan="'+count+'">'+(i+1)+'</td>'+
									'<td rowspan="'+count+'">' + targetdata[i].product_name + '</td>'+
									'<td rowspan="'+count+'">' + target1.period + '</td>'+
									'<td rowspan="'+count+'">' + date + '</td>'+ TargetModel +
									'<td rowspan="'+count+'">' + capitalizeFirstLetter(target1.target_type)+ currency +'</td>'+ TargetValue + edit
									/* '<td colspan="2" rowspan="'+count+'">' + targetdata[i].currency_name + '</td>'+ */
								'</tr>';
						}else{
							target_row +='<tr style="cursor:auto">'+ TargetModel + TargetValue + edit +'</tr>';
						}
					}
				})
			}
			if(target1.category == "Consolidated"){
				target_row +='<tr style="cursor:auto">'+
								'<td>'+(i+1)+'</td>'+
								'<td>' + targetdata[i].product_name + '</td>'+
								'<td>' + target1.period + '</td>'+
								'<td>' + date + '</td>'+
								'<td>' + capitalizeFirstLetter(target1.category) + '</td>'+								
								'<td>' + capitalizeFirstLetter(target1.target_type)+ currency +'</td>'+
								'<td>' + (Math.floor(parseInt(target1.category_details.Target.value) / 1)).toLocaleString()  + '</td>'+ 
								'<td class="edit"><a data-toggle="modal" href="#edit_target" onclick="editTarget(\''+window.btoa(targetdata[i].target_data)+'\', \''+target1.category+'\', \''+targetdata[i].target_id+'\')"><span class="glyphicon glyphicon-pencil"></span></a></td>';								
								/* '<td>' + target1.category_details.common.start_date + '</td>'+ */
								/* '<td colspan="2" >' + targetdata[i].currency_name + '</td>'+ */
								'</tr>';
			}
		}
		target_row +='</tbody></table>';
	}else{
		target_row += "<center><b>No target added</b></center>";
	}
	return target_row;
}

var targetClickedFor = "";
var targetIdForEdit = "";
/*target add/edit section show ------------*/
function target_add_btn(state, data, rowType, target_id){
	$('#targetaddbtnID').hide();
	targetIdForEdit = target_id;
	if( state == 'edit' ){
		targetClickedFor = state;
		targetIdForEdit = target_id;
	}else{
		targetClickedFor = state;
		targetIdForEdit = "";
	}
	$("#target_area_com").show();
	if(state == 'edit'){
		console.log(data);
		$("#targetInfo .add.glyphicon.glyphicon-plus").hide();
		
		select = "#target_area_com .row.input_field select";
		date = '#target_area_com .row.input_field .date input[type = "text"]';
		txt = "#target_area_com #change_sales_type .row .col-md-4";
		$("#target_area_com").find('.error-alert').text("");
		
		$("#target_area_com .row.input_field input[name = 'slTyp']").removeAttr('checked');
		$("#target_area_com .row.input_field input[name = 'checkedTarget']").removeAttr('checked');
		
		$("#target_area_com .row.input_field input[name = 'slTyp']").each(function(){
			if($(this).val() == data.category){
				$(this).prop('checked', true);
			}
		});
		
		$("#target_area_com .row.input_field input[name = 'checkedTarget']").each(function(){
			if($(this).val() == data.checked){
				$(this).prop('checked', true);
			}
		});
		
		$(date).each(function(i){
			if(i == 0){
				$(this).val(data.startDate);
			}
			if(i == 1){
				if(data.endDate != ""){
					$(this).val(data.endDate);
					$("#target_area_com .targetEndDate").show();
				}else{
					$(this).val('');
					$("#target_area_com .targetEndDate").hide();
				}
			}
		})
		var val ="";
		selectedseltype = rowType == 'Consolidated' ? 'Target' : rowType.split("_").join(" ");
		
		$.each(data.category_details, function(i, item) {
			if(i.indexOf($.trim(selectedseltype).split(' ').join('_')) >= 0){
				val = item.value;
			}
		})
		
		change_sales_type( data.category_details, $.trim(selectedseltype) , val);
		
		$(select).each(function(i){
			if(i == 0){
				$(this).val(data.product_ids[0]);
				get_pdCur(data.product_ids[0], targetCurrencyList)
			}
			if(i == 1){
				$(this).val(data.target_type);
				if(data.target_type == "revenue"){
					$("#target_area_com .targetCurrencyHide").show();
				}else{
					$("#target_area_com .targetCurrencyHide").hide();
				}
			}
			if(i == 2){
				$(this).val(data.period);
			}
			if(i == 3){
				$(this).val(data.target_currency);
			}}
		)
		
		
	}
}
function editTarget(data , type,  target_id){
	data = JSON.parse(window.atob(data))
	
	target_add_btn('edit', data , type, target_id)
}
/* ------------------------------------------------ */
/*Validation : currency format */
function curr_format(value){
	value = value.trim();
	var nameReg = new RegExp(/^\d+$/);
	var nameReg1 = new RegExp(/^\d+\.\d$/);
	var nameReg2 = new RegExp(/^\d+\.\d\d$/);
	var valid = nameReg.test(value);
	var valid1 = nameReg1.test(value);
	var valid2 = nameReg2.test(value);
	if (!valid && !valid1 && !valid2) {
		return false;
	}else{
		return true;
	}
}/*Validation : end*/
/* ------------------------------------------------ */
/*target save ------------*/
function save_target1(userId){
	$("#target_area_com").find('.error-alert').text("");
	txt = "#target_area_com #change_sales_type .row .col-md-4";
	category_details = "#target_area_com #change_sales_type .row .col-md-2";
	radio = "#target_area_com .row.input_field input[name = 'slTyp']:checked";
	select = "#target_area_com .row.input_field select";
	date = '#target_area_com .row.input_field .date input[type = "text"]';
	
	var Obj={}, type=0, chk = 1;
	Obj.checked = $("#target_area_com .row.input_field input[name = 'checkedTarget']:checked").val();
	Obj.category = $(radio+":checked").val();
	
	$(date).each(function(i){
		if(i == 0){
			Obj.startDate = this.value;
		}
		if(i == 1){
			Obj.endDate = this.value;
		}
	})
	$(select).each(function(i){
		value = $.trim($(this).val());
		if(i == 0){
			Obj.manager_id = userId;
			Obj.product_ids = [value];
		}
		if(i == 1){
			Obj.target_type = value;
		}
		if(i == 2){
			Obj.period = value;
		}
		if(i == 3){
			Obj.target_currency = value;
		}
		if(i == 3){
			if( Obj.target_type == "revenue" && (value == "" || value == null)){
				$(this).next('.error-alert').text("Choose an option.");
				chk = 0;
			}else{
				$(this).next('.error-alert').text("");
			}
		}else{
			if( value == "" || value == null){
				$(this).next('.error-alert').text("Choose an option.");
				chk = 0;
			}else{
				$(this).next('.error-alert').text("");
			}
		}
	})
	
	/* $(radio).each(function(i){
		value = $.trim($(this).val());
		if(i == 0){
			Obj.category = value;
			if(value == 'consolidated'){
				type = 1
			}else{
				type = 0
			}
		}
	}) */
	Obj.category_details={};

	$(txt).each(function(i){
		tlbl = $.trim($(this).prev('.col-md-2').find('.tlbl').text()).split(' ');
		value = $.trim($(this).find('.type').val());
		/* -------------- validation ------------------- */
		if( value == "" || value == null){
			$(this).find('.type').next('.error-alert').text(tlbl[0]+" "+ ($.trim(tlbl[1]) == "*" ? " " : $.trim(tlbl[1]))+" is required");
			chk = 0;
		}else if(!curr_format(value)){
			$(this).find('.type').next('.error-alert').text(tlbl[0]+" "+ ($.trim(tlbl[1]) == "*" ? " " : $.trim(tlbl[1]))+" value format is wrong.");
			chk = 0;
		}else{
			$(this).find('.type').next('.error-alert').text("");
		}
		
		/*
		if(type == 1){
			 Obj["category_details"]["common"] = {"value":value}; 
		}else{
			Obj["category_details"][ $.trim(tlbl[0]) + ($.trim(tlbl[1]) == "*" ? "" : "_"+$.trim(tlbl[1]))] = {"value":value};
		}
		*/
		Obj["category_details"][ $.trim(tlbl[0]) + ($.trim(tlbl[1]) == "*" ? "" : "_"+$.trim(tlbl[1]))] = {"value":value};
	})
	if(Obj.startDate == ""){
		$('#target_area_com .targetStartDate').find('.error-alert').text("Select a start date.");
		chk = 0;
	}
	if(Obj.period == "Custom" && Obj.endDate == ""){
		$('#target_area_com .targetEndDate').find('.error-alert').text("Select a end date.");
		chk = 0;
	}
	
	if(chk == 0){
		return;
	}else{
		loaderShow();
		
		if(targetClickedFor == 'add'){
			api = "<?php echo site_url('manager_teamManagersController/saveTarget');?>";
		}else{
			api = "<?php echo site_url('manager_teamManagersController/update_target');?>";
			Obj.target_id = targetIdForEdit;
		}
		
		$.ajax({
			type : "POST",
			url : api,
			data:JSON.stringify(Obj),
			dataType : 'json',
			cache : false,
			success : function(data){
				loaderHide();

				if(error_handler(data)){
					return;
				}else if(data == false ){
					$.alert({
						title: 'L Connectt',
						content: 'Target is already defined.',
						Ok: function () {
						}
					});	
				}else{
					$(".targetInfo").find('table').remove();
					target_tbl(data ,Obj.checked);
					close_target();
				}
			},
			error : function(data){
				network_err_alert(data)
			}
		 })
	}
}

function targetType(targetTypeVal){
	if(targetTypeVal == "" || targetTypeVal == "numbers"){
		$(".targetCurrencyHide").hide();
	}else{
		$(".targetCurrencyHide").show();
	}
}
function targetPeriod(targetPeriod){
	if(targetPeriod == "" || targetPeriod == "Custom"){
		$("#target_area_com .targetEndDate").show();
	}else{
		$("#target_area_com .targetEndDate").hide();
	}
}
function get_pdCur(pd, data){
	if(pd == ""){
		$("#prodCurr").html("");
	}else{
		data = JSON.parse(window.atob(data));
		pdOpt = [];
		
		for (var i in data) {
			if(data[i].product_id != "" && data[i].product_id != null){
				if(pd == data[i].product_id){
					pdOpt = ['<option value="">Choose Currency</option>'];
					for (var j in data[i].curdata){
						if(data[i].curdata[j].currencyname != "" && data[i].curdata[j].currencyname != null){
							pdOpt.push('<option value="'+data[i].curdata[j].currency_id+'">'+ data[i].curdata[j].currencyname + '</option>');
						}
					}
					$("#prodCurr").html("").append(pdOpt.join(''))
				}
			}
		}
    }
}
var globalSalesType = {};
function change_sales_type(type ,selection, val){
	var type_html="";
	
	if(typeof val != "undefined"){
		globalSalesType = type;
		
		$.each(type, function(typeS, value) {
			type_html += 	'<div class="col-md-2">'+
							'<label class="tlbl">'+typeS.split('_').join(' ')+' *</label>'+
						'</div>'+
						'<div class="col-md-4">';
							if(selection.split(' ').join('_') == typeS){
								
								type_html +='<input type="text" class="form-control type" value="'+val+'"/>';
							}else{
								type_html +='<input type="text" class="form-control type" value="'+value.value+'" disabled/>';
							}
							
							type_html +='<span class="error-alert"></span>'+
						'</div>';
		})
		
		
	}else{
		globalSalesType = {};
		type = type.split(',');
		
		for(s=0; s < type.length; s++){
		type_html += 	'<div class="col-md-2">'+
							'<label class="tlbl">'+type[s]+' *</label>'+
						'</div>'+
						'<div class="col-md-4">'+
							'<input type="text" class="form-control type" value=""/>'+
							'<span class="error-alert"></span>'+
						'</div>';
		}
	}
	
	$("#change_sales_type").html("").append('<div class="row">'+type_html+'</div>');

}
/* ---------------------------------------------------------------------- */

var mobileNumber = new RegExp(/^\+*[0-9]*$/);

var adminName_chk = new RegExp(/^[a-zA-Z ]*$/);
var adminUsername_chk = new RegExp(/^[a-zA-Z &_]*$/);

function adminProfileEdit(){
	if($.trim($("#adminNameE").val())==""){
		$("#adminNameE").closest("div").find("span").text("Name is required.");
        $("#adminNameE").focus();
		return;
	}else if(!adminName_chk.test($.trim($("#adminNameE").val()))) {
        $("#adminNameE").closest("div").find("span").text("Enter Only Chracters");
        $("#adminNameE").focus();
       return;
    }else{
		$("#adminNameE").closest("div").find("span").text("");
	}
	if($.trim($("#adminModE").val())==""){
		$("#adminModE").closest("div").find("span").text("Mobile number is required.");
        $("#adminModE").focus();
		return;
	}else if(!validate_PhNo($.trim($("#adminModE").val()))) {
        $("#adminModE").closest("div").find("span").text("Mobile number should be of 10 digits.");
        $("#adminModE").focus();
        return;
    }else{
		$("#adminModE").closest("div").find("span").text("");
	}
	if($.trim($("#adminUsernameE").val())==""){
		$("#adminUsernameE").closest("div").find("span").text("Username is required.");
        $("#adminUsernameE").focus();
		return;
	}else if(!adminName_chk.test($.trim($("#adminUsernameE").val()))) {
       $("#adminUsernameE").closest("div").find("span").text("Enter Only Chracters");
       $("#adminUsernameE").focus();
       return;
    }else{
		$("#adminUsernameE").closest("div").find("span").text("");
	}
	console.log($("contactImageUploadA").val(""));
	var adminProfileObj={};
	adminProfileObj.managerid = $("#adminEditid").val();
	adminProfileObj.managername = $.trim($("#adminNameE").val());
	adminProfileObj.managerphone = $.trim($("#adminModE").val());
	adminProfileObj.managerloginid = $.trim($("#adminUsernameE").val());
	$.ajax({
		type : "POST",
		url : "<?php echo site_url('admin_sidenavController/update_managerinfo')?>",
		dataType : 'json',
		data : JSON.stringify(adminProfileObj),
		cache : false,
		success : function(data){
            addimageloaded();
			if(error_handler(data)){
				return;
			}
			/* document.getElementById("imageUploadA").submit(); */

		},
		error : function(data){
			network_err_alert(data)
		}
	});

}
/* -------------------------------------------- */

    function readURL(input, imgid, action) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(imgid).attr('src', e.target.result);
            }
			var valid_extensions = /(\.jpg|\.jpeg|\.gif|\.bmp|\.png|\.JPG|\.JPEG|\.GIF|\.BMP|\.PNG)$/i;
			if(!valid_extensions.test(input.files[0].name)){
				$("contactImageUploadA").val("");
				$("#dp_error").text("Invalid File type.");
				return;
			}else if(input.files[0].size >= 1000000){
				$("contactImageUploadA").val("");
				$("#dp_error").text("File size is too long.");
				return;
			}else{
				$("#dp_error").text("");
				reader.readAsDataURL(input.files[0]);
				var url="<?php echo site_url('admin_sidenavController/do_upload'); ?>";
				if(action == "click"){
					document.getElementById('imageUploadA').action = url;
					document.getElementById("imageUploadA").submit();
				}
			}
        }else{
			$("#dp_error").text("Change photo.");
            return;
        }
    }

	function addimageloaded(action) {
		var x = document.getElementById("contactImageUploadA");
		var imgid = '#contactAddAvatar';
		readURL(x, imgid, action);
	}

/* -------------------------------------------- */
function PWResetFormShow(){
	$("#adminEditForm").hide();
	$("#adminPasswordResetForm").show();
}

function adminPasswordReset(){
    var oldpwd=$("#hid_oldpassword").val();
    var newpwd=$("#adminOldPw").val();
	if( $.trim($("#adminOldPw").val()) ==""){
		$("#adminOldPw").closest("div").find("span").text("Password is required.");
        $("#adminOldPw").focus();
		return;
	}else{
	        if($.trim($("#adminOldPw").val())!=''){
        		if(oldpwd!=newpwd){
                    $("#adminOldPw").closest("div").find("span").text("Old Password not matching");
                      $("#adminOldPw").focus();
                    return;
        		}else{
                    $("#adminOldPw").closest("div").find("span").text("");
        		}
	        }

	}
	if( $.trim($("#adminNewPw").val()) ==""){
		$("#adminNewPw").closest("div").find("span").text("New Password is required.");
        $("#adminNewPw").focus();
		return;
	}else{
	    var newpwd1=$.trim($("#adminNewPw").val());
        if(oldpwd==newpwd1){
             $("#adminNewPw").closest("div").find("span").text("New Password and Old Password Cannot be Same.");
             $("#adminNewPw").focus();
        	 return;
        }else{
             $("#adminNewPw").closest("div").find("span").text("");
        }

	}
	if( $.trim($("#adminConfirmPw").val()) ==""){
		$("#adminConfirmPw").closest("div").find("span").text("Confirm Password is required.");
          $("#adminConfirmPw").focus();
		return;
	}else if( $.trim($("#adminConfirmPw").val()) != $.trim($("#adminNewPw").val())){
		$("#adminConfirmPw").closest("div").find("span").text("New Password and Confirm Password is not same.");
         $("#adminConfirmPw").focus();
		return;
	}else{
		$("#adminConfirmPw").closest("div").find("span").text("");
	}

	var PasswordReset={};
	PasswordReset.managerid = $("#adminEditid").val();
	PasswordReset.managername = $.trim($("#adminOldPw").val());
	PasswordReset.managerphone = $.trim($("#adminNewPw").val());
	PasswordReset.managerloginid = $.trim($("#adminConfirmPw").val());

	$.ajax({
		type : "POST",
		url : "<?php echo site_url('admin_sidenavController/update_password')?>",
		dataType : 'json',
		data : JSON.stringify(PasswordReset),
		cache : false,
		success : function(data){
			if(error_handler(data)){
				return;
			}
			if(data==1){
				$.confirm({
					title: 'L Connectt',
					content: 'Password has been changed successfully.',
					animation: 'none',
					closeAnimation: 'scale',
					buttons: {
						Ok: function () {
							cancel_edit();
						}
					}
				});
            }else{
				$.confirm({
					title: 'L Connectt',
					content: 'Could Not Update the New Password.',
					animation: 'none',
					closeAnimation: 'scale',
					buttons: {
						Ok: function () {
							return false;
						}
					}
				});
            }
		},
		error : function(data){
			network_err_alert(data)
		}
	});

}

function cancel_edit(){
	$('#adminProfileE').modal('hide');
	$('#adminProfileE .error-alert').text("");
	$('#adminProfileE input[type="text"],input[type="password"], textarea').val('');
    $("#adminEditForm").show();
	$("#adminPasswordResetForm").hide();
}


/* -----------------Network error alert display function----------------------------- */

function network_err_alert(){
	$.alert({
		title: 'L Connectt',
		content: 'Something went wrong please Check again.',
		closeIcon: true,
		closeIconClass: 'fa fa-close',
	});
}
/* -----------------Data base error alert display function----------------------------- */
function  error_handler(data){
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

/* ---------------------- User Email Settings functions UserEmailSettingsValue------------------------*/
function emailSettings(){
	$('#common_server_msg').html('');
	if(UserEmailSettingsValue.emailset == '1'){
		$('#user_email_setting_modal').modal('show');
	}else{
		$.alert({
			title: 'L Connectt',
			content: 'The Email settings in the Admin is not defined.',
			Ok: function () {
			
			}
		});	
	}
	
	
	if(UserEmailSettingsValue.hasOwnProperty('password') == false){
		$('#common_login_name').attr('disabled', 'disabled').val(UserEmailSettingsValue.user_name);
		$('#common_login_email').val(UserEmailSettingsValue.email);
		$('#hostName').val(UserEmailSettingsValue.incoming_host);
		$('#signature').val(UserEmailSettingsValue.signature);
		$('#ChangePassword').hide();
	}else if( UserEmailSettingsValue.hasOwnProperty('user_email_settings_id') && $.trim(UserEmailSettingsValue.password) == ""){
		/* setting values after editing user primary mail id */
		$('#common_login_name').attr('disabled', 'disabled').val(UserEmailSettingsValue.name);
		$('#common_login_email').val(UserEmailSettingsValue.email_id);
		$('#user_email_settings_id').val(UserEmailSettingsValue.user_email_settings_id);
		$('#hostName').val(UserEmailSettingsValue.incoming_host);
		$('#signature').val(UserEmailSettingsValue.signature);
		$('#ChangePassword').hide();
	}else{
		/* setting value after mail settings done */
		$('#common_login_name').attr('disabled', 'disabled').val(UserEmailSettingsValue.name);
		$('#common_login_email').val(UserEmailSettingsValue.email_id);
		$('#common_login_password').attr('disabled', 'disabled').val(UserEmailSettingsValue.password);
		$('#user_email_settings_id').val(UserEmailSettingsValue.user_email_settings_id);
		$('#hostName').val(UserEmailSettingsValue.incoming_host);
		$('#signature').val(UserEmailSettingsValue.signature);
		$('#editvalidatebtn').hide();
		$('#common_email_update').removeAttr('disabled');
		$('#ChangePassword').show();
	}
}
function ChangePasswordFunction(){
	$('#common_login_password').removeAttr('disabled').val('');
	$('#common_email_update').attr('disabled', 'disabled');
	$('#editvalidatebtn').show();
	$('#ChangePassword').hide();
}
function cancel_email_setting(input){
	$('#user_email_setting_modal').modal('hide');
	$('.error').text("");
	if(input == 'add'){
		$('#common_login_name').val("");
		$('#common_login_email').val("");
		$('#common_login_password').val("");
	}
}

function save_email_setting(input){
	if(input == 'add'){
		var addObj={};
		addObj.login_name = $.trim($('#common_login_name').val());
		addObj.login_email = $.trim($('#common_login_email').val());
		addObj.login_password = $.trim($('#common_login_password').val());
		
		addObj.incoming_host = $.trim($("#hostName").val());
		addObj.signature = $.trim($("#signature").val());
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sidenavController/save_user_email_settings')?>",
			dataType : 'json',
			data : JSON.stringify(addObj),
	        cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
	            $('#user_email_setting_modal').modal('hide');
	            $('#server_msg').text("");
	            $("#email_save").attr("disabled","disabled");
	            $("#addvalidatebtn").removeAttr("disabled");
	            $("#addvalidatebtn").removeClass("validated");
				
				$.confirm({
					title: 'L Connectt',
					content: 'Email Settings added successfully.',
					animation: 'none',
					closeAnimation: 'scale',
					buttons: {
						Ok: function () {
							location.reload();
						}
					}
				});
			},
			error : function(data){
				network_err_alert(data)
			}
		});
	}else if(input == 'edit'){
		var addObj={};
		addObj.login_name = $.trim($('#common_login_name').val());
		addObj.login_email = $.trim($('#common_login_email').val());
		addObj.login_password = $.trim($('#common_login_password').val());
		addObj.settings_id = $.trim($('#user_email_settings_id').val());
		addObj.incoming_host = $.trim($("#hostName").val());
		addObj.signature = $.trim($("#signature").val());
		if($('#common_login_password').attr('disabled') == 'disabled'){
			addObj.isSignature = 1;
		}else{
			addObj.isSignature = 0;
		}
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('admin_sidenavController/update_user_email_settings')?>",
			dataType : 'json',
			data : JSON.stringify(addObj),
	        cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
	            $('#user_email_setting_modal').modal('hide');
	            $('#server_msg').text("");
	            $("#email_update").attr("disabled","disabled");
	            $("#editvalidatebtn").removeAttr("disabled");
	            $("#editvalidatebtn").removeClass("validated");
				
				$.confirm({
					title: 'L Connectt',
					content: 'Email Settings updated successfully.',
					animation: 'none',
					closeAnimation: 'scale',
					buttons: {
						Ok: function () {
							location.reload();
						}
					}
				});
			},
			error : function(data){
				network_err_alert(data)
			}
		});
	}
}

function namefirstLtrChk(name) {
	var nameReg = new RegExp(/^[a-zA-Z0-9 -]*$/);
	var valid = nameReg.test(name);
	if (!valid) {
		return false;
	} else {
		return true;
	}
}

function validateSpclChr(name) {
	var nameReg = new RegExp(/^[\\)(}{/;'"$]*$/);
	var inputArray = name.split("")
	for(i=0;i<inputArray.length; i++){
		var valid = nameReg.test(inputArray[i]);
		if (valid == true) {
			return "invalid";
		}
	}
}
function domain_name1(name){
	if(name.indexOf('.') !== -1){
		var valueArray = name.split(".");
		if(valueArray.length < 3 || valueArray.length > 4){
			return "invalid array length ";
		}else{
			for(i=0;i<valueArray.length; i++){
				if(valueArray[i].length == 0){
					return "empty array";
				}
				if(!domainfirstLtrChk(valueArray[i])){
					return "NO special character";
				}
				if(i == 0){
					if(valueArray[i].length < 3 || valueArray[i].length > 15){
						return "invalid array1 value length ";
					}
				}
				if(i == 1){
					if(valueArray[i].length < 1 ){
						return "invalid array2 value length ";
					}
				}
				if(i == 2){
					if(valueArray[i].length < 2 || valueArray[i].length > 10){
						return "invalid array3 value length ";
					}
				}
				if(i == 3){
					if(valueArray[i].length < 2 || valueArray[i].length > 6){
						return "invalid array4 value length ";
					}
				}
			}
			return "valid";
		}
	}else{
		return "invalid no dot";
	}
}
function domainfirstLtrChk(name) {
	var nameReg = new RegExp(/^[a-zA-Z0-9]*$/);
	var valid = nameReg.test(name);
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function characterLimit(name , limit){
	$("#"+name).next(".error").text((limit - $("#"+name).val().length) + " characters left");
}
function commonvalidatefield(input){
	$(".error").text("");

	if($.trim($("#common_login_name").val())==""){
		$("#common_login_name").next(".error").text("Name is required.");
		$("#common_login_name").focus();
		return;
	}else if(!namefirstLtrChk($.trim($("#common_login_name").val()))){
		$("#common_login_name").next(".error").text("First letter should not be any Special character.");
		$("#common_login_name").focus();
		return;
	}else if(validateSpclChr($.trim($("#common_login_name").val())) == "invalid"){
		$("#common_login_name").next(".error").text("No special characters allowed  ( \ ) ( } { / ; ' '' $ . )" );
		$("#common_login_name").focus();
		return;
	}else{
		$("#common_login_name").next(".error").text("");
	}
	
	if($.trim($("#common_login_email").val())==""){
		$("#common_login_email").next(".error").text("Email ID is required.");
		$("#common_login_email").focus();
		return;
	} else if(!validate_email($.trim($("#common_login_email").val()))){
		$("#common_login_email").next(".error").text("Invalid Email ID");
		$("#common_login_email").focus();
		return;
	}else{
		$("#common_login_email").next(".error").text("");
	}
	if($.trim($("#common_login_password").val())==""){
		$("#common_login_password").next(".error").text("Password is required.");
		$("#common_login_password").focus();
		return;
	}else{
		$("#common_login_password").next(".error").text("");
	}/* 
	if($.trim($("#hostName").val())==""){
		$("#hostName").next(".error").text("Host Name is required.");
		$("#hostName").focus();
		return;
	}else if(domain_name1($.trim($("#hostName").val())) != "valid"){
		$("#hostName").next(".error").text("Invalid Host Name");
		$("#hostName").focus();
		return;
	}else{
		$("#hostName").next(".error").text("");
	} */
	if($.trim($("#signature").val())!=""){
		if(validateSpclChr($.trim($("#signature").val())) == "invalid"){
			$("#signature").next(".error").text("No special characters allowed  ( \ ) ( } { / ; ' '' $ . )" );
			$("#signature").focus();
			return;
		}else if($.trim($("#signature").val()).length > 255 ){
			$("#signature").next(".error").text("Maximum character limit is 255");
		}else{
			$("#signature").next(".error").text("");
		}
	}
	var obj = {};
	obj.login_name = $.trim($("#common_login_name").val());
	obj.login_email = $.trim($("#common_login_email").val());
	obj.login_password = $.trim($("#common_login_password").val());
	obj.incoming_host = $.trim($("#hostName").val());
	obj.signature = $.trim($("#signature").val());
	loaderShow();
	 $.ajax({
		type : "post",
		url : "<?php echo site_url('admin_sidenavController/validate_email'); ?>",
		data : JSON.stringify(obj),
		dataType : "json",
        cache : false,
		success : function(data){
	        loaderHide();
	        if(error_handler(data)){
	                return;
	        }
	         if(data=="Sent"){
	         	if(input == 'add'){
		            $("#common_email_save").removeAttr("disabled");
		            $("#addvalidatebtn").attr("disabled","disabled");
		            $("#addvalidatebtn").addClass("validated");
				}else{
					$("#common_email_update").removeAttr("disabled");
		            $("#editvalidatebtn").attr("disabled","disabled");
		            $("#editvalidatebtn").addClass("validated");
				}
	            
	             $("#common_server_msg").html('<div class="alert alert-success"><strong>Authenticated Successfully. </strong> </div>');
				 
	         }else if(data=='Not sent'){
	         	if(input == 'add'){
				 	$("#addvalidatebtn").removeClass("validated");
	             	$("#common_email_save").attr("disabled","disabled");
	             	$("#addvalidatebtn").removeAttr("disabled");
	         	}else{
	         		$("#editvalidatebtn").removeClass("validated");
	             	$("#common_email_update").attr("disabled","disabled");
	             	$("#editvalidatebtn").removeAttr("disabled");
	         	}
				 $("#common_server_msg").html('<div class="alert alert-warning"><strong>Authentication Revoke.</strong></div>');
	         	
	         }else{
	         	if(input == 'add'){
		         	$("#addvalidatebtn").removeClass("validated");
		            $("#common_email_save").attr("disabled","disabled");
		            $("#addvalidatebtn").removeAttr("disabled");
		        }else{
		        	$("#editvalidatebtn").removeClass("validated");
		            $("#common_email_update").attr("disabled","disabled");
		            $("#editvalidatebtn").removeAttr("disabled");
		        }
				$("#common_server_msg").html('<div class="alert alert-warning"><strong>Email Settings not accurate. </strong></div>');
	         }

		},
		error: function(xhr){
		}
	});
}

var UserEmailSettings = 0, ud, UserEmailSettingsValue;

function getUserEmailSettings(){
	 loaderShow();
	$.ajax({
		type : "post",
		url : "<?php echo site_url('admin_sidenavController/get_user_email_settings'); ?>",
		dataType : "json",
		cache : false,
		success : function(data){
			if(error_handler(data)){
	            return;
	        }
			loaderHide();
	        if(data[0].hasOwnProperty('password') == false){
				UserEmailSettingsValue = data[0];
	        	$('input.hideBtn').hide();
	        	$('#addvalidatebtn').show();
	        	$('#common_email_save').show();
	        	$('#email_add_cancel').show();
				/* setting initial value */
				$('#common_login_name').val(data[0].user_name);
				$('#common_login_email').val(data[0].email);
				$('#hostName').val(data[0].incoming_host);
				$('#signature').val(data[0].signature);
				UserEmailSettings = 0;
	        }else if( data[0].hasOwnProperty('user_email_settings_id') && $.trim(data[0].password) == ""){
				UserEmailSettingsValue = data[0];
				UserEmailSettings = 0;
				$('input.hideBtn').hide();
	        	$('#editvalidatebtn').show();
	        	$('#common_email_update').show();
	        	$('#email_edit_cancel').show();
				/* setting values after editing user primary mail id */
	        	$('#user_email_settings_id').val(data[0].user_email_settings_id);
	        	$('#common_login_name').val(data[0].name);
	        	$('#common_login_email').val(data[0].email_id);
	        	$('#user_email_settings_id').val(data[0].user_email_settings_id);
	        	$('#hostName').val(data[0].incoming_host);
				$('#signature').val(data[0].signature);
			}else{
	        	$('input.hideBtn').hide();
	        	$('#editvalidatebtn').show();
	        	$('#common_email_update').show();
	        	$('#email_edit_cancel').show();
				/* setting value after mail settings done */
				UserEmailSettingsValue = data[0];
	        	$('#common_login_name').val(data[0].name);
	        	$('#common_login_email').val(data[0].email_id);
	        	$('#common_login_password').val(data[0].password);
	        	$('#user_email_settings_id').val(data[0].user_email_settings_id);
	        	$('#hostName').val(data[0].incoming_host);
				$('#signature').val(data[0].signature);
				CKEDITOR.instances['editor1_common'].setData("<br/><br/><br/>"+ data[0].signature.replace(/(\r\n|\n)/g, "<br/>"));
				UserEmailSettings = 1;
				ud = data[0].user_id;
	        }
			/* user mail page > view mail section > reply, forward button show */
			if(typeof(UserEmailSettingsValue) != "undefined"){
				$('#attach_box .reply-forward').show();
			}
		},
		error : function(xhr){
				loaderHide();
		}
	});
}

/* ---------------------- User Email Settings functions End ------------------------*/


/* ---------------------------------------------------------------------------- */
function mail_attach_validation(input) {
	$("#file_name_common").html('');
	var input = document.getElementById(input);
	var size = 0
	var name = ""
	$.each(input.files, function(i, item) {
		if (item) {
			size = size + item.size;
			name = name + ' <pre class="attach_file_list"> ' + item.name + ' </pre> ' ; 
		}
	})
	/* 20000000 byte == 20 mb */
	if(size > 10000000){
		$("#file_name_common").html('<span class="error-alert"> Attachment file(s) size limit should not exceed 10 MB.</span>');
		input.value = "";
		return;
	}else{
		$("#file_name_common").html(name);
	}
}


/* ---------------------------------------------------------------------------- */
/* -------------------Open/Close mailer Function--- Starts---------------- */
var startComposeTime = "";
function supportMail(section, type){
	CommonratingVal = 0;
	$("#mail_add_common .rating_activity_add .glyphicon.glyphicon-star").css("color",'#d2cfcf');
	$("#mail_add_common .ratingCommentSection .rating_msg").text("");
	$('#mail_to_commonUl,#mail_cc_commonUl,#mail_bcc_commonUl').html('');
	if(UserEmailSettingsValue.hasOwnProperty('signature')){
		CKEDITOR.instances['editor1_common'].setData("<br/><br/><br/>"+ UserEmailSettingsValue.signature.replace(/(\r\n|\n)/g, "<br/>"));
	}
	typeaheadSetup(type)
	startComposeTime = moment().format('DD-MM-YYYY HH:mm:ss');
	if(UserEmailSettingsValue.emailset == "0"){
		$.alert({
			title: 'L Connectt',
			content: 'The Email settings in the Admin is not defined.',
			closeIcon: true,
			closeIconClass: 'fa fa-close',
		});
		return;
	}else if(UserEmailSettingsValue.emailset == "1"){
		if(UserEmailSettings == 0){
			$.confirm({
				title: 'L Connectt',
				content: 'Please configure your Email Settings. <br> Click Ok to setup your Email Settings.',
				animation: 'none',
				closeAnimation: 'scale',
				closeIcon: true,
				closeIconClass: 'fa fa-close',
				buttons: {
					Ok: function () {
						emailSettings(ud);
					},
					Cancel: function () {
						
					}
				}
			});
			return;
		}
	}
	
	if(section == "supportmail"){
		$('#mail_add_common .row.eId').addClass('none');
		$('#mail_to_common').val('support@digiconnectt.com');
		$('#mail_send_common').val('Raise a ticket');
	}else{
		$('#mail_add_common .modal-header .modal-title').text(section);
		$('#mail_to_common').val('');
		$('#mail_send_common').val('Send');
		$('#mail_add_common .row.eId').removeClass('none');
		doTimer(); /* ----Call start time count function------ */
	}
	$(".error-alert").text("");
	$("#file_name_common").html("");
	$("#mail_add_common").modal("show");
	$('#mail_send_common').removeAttr('disabled');
}

function close_assign(section){
	$('#mail_add_common').modal('hide');	
	$('#mail_add_common.modal input[type=text],#mail_add_common.modal input[type=file], #mail_add_common.modal textarea').val("");
	CKEDITOR.instances['editor1_common'].setData('');
	$("#file_name_common").html("");	
	/*counterClose(section);  ----Call end time count function compose mail section------ */
	location.reload();
}
/* -------------------Open/Close mailer Function--- Ends---------------- */
/* -------------------Send email Function--- Starts---------------- */
function mail_send(){
	var endComposeTime = moment().format('DD-MM-YYYY HH:mm:ss');
	$("#startComposeTime").val(startComposeTime);
	$("#endComposeTime").val(endComposeTime);
	$(".error-alert").text("");
	var addObj = {};
	var mail_to = $("#mail_to_common").val().trim();
	var mail_cc = $("#mail_cc_common").val().trim();
	var mail_bcc = $("#mail_bcc_common").val().trim();
	var mail_sub = $("#mail_sub_common").val().trim();
	 
	/*if( mail_to == "" ){
		$("#mail_to_common").closest('div').find(".error-alert").text("Mail id is required.");
		$("#mail_to_common").focus();
		return;
	}else if(!validate_email(mail_to)){
		$("#mail_to_common").closest('div').find(".error-alert").text("Invalid Mail id.");
		$("#mail_to_common").focus();
		return;
	}else{
		if($("#mail_to_common").closest('span').siblings('ul').length > 0){
			 if($("#mail_to_common").closest('span').siblings('ul').find("input[name='matchUserMail']").is(':checked')){
				$("#mail_to_common").closest('div').find(".error-alert").text("");
			}else{
				$("#mail_to_common").closest('div').find(".error-alert").text("Select an option.");
				return;
			} 
		}else{
			$("#mail_to").closest('div').find(".error-alert").text("");
		}
	}
	if(mail_cc != ""){
		if(!validate_email(mail_cc)){
			$("#mail_cc_common").closest('div').find(".error-alert").text("Invalid Mail id.");
			$("#mail_cc_common").focus();
			return;
		}else{
			$("#mail_cc_common").closest('div').find(".error-alert").text("");
		}
	} 
	if(mail_bcc != ""){
		if(!validate_email(mail_bcc)){
			$("#mail_bcc_common").closest('div').find(".error-alert").text("Invalid Mail id.");
			$("#mail_bcc_common").focus();
			return;
		}else{
			$("#mail_bcc_common").closest('div').find(".error-alert").text("");
		}
	}
	*/
	
	var toObj =[],ccObj =[],bccObj =[];
	$("#mail_to_common").closest('.twitter-typeahead').siblings('ul').find('li').each(function(){
		toObj.push({
			"display": $(this).find('.display label').text().trim(),
			"actualVal": $(this).find('.actualVal').val(),
		})
	});
	
	if(toObj.length == 0){
		$("#mail_to_common").closest('div').find(".error-alert").text("Mail id is required.");
		$("#mail_to_common").focus();
		return;
	}else{
		$("#mail_to").closest('div').find(".error-alert").text("");
	}
	
	
	
	$("#mail_cc_common").closest('.twitter-typeahead').siblings('ul').find('li').each(function(){
		ccObj.push({
			"display": $(this).find('.display label').text().trim(),
			"actualVal": $(this).find('.actualVal').val(),
		})
	});
	
	$("#mail_bcc_common").closest('.twitter-typeahead').siblings('ul').find('li').each(function(){
		bccObj.push({
			"display": $(this).find('.display label').text().trim(),
			"actualVal": $(this).find('.actualVal').val(),
		})
	});
	var rating = CommonratingVal;
	if(rating <= 0){
		$("#mail_add_common .ratingCommentSection .rating_error").text("Rating is required.");
		return;
	}else{
		$("#mail_add_common .ratingCommentSection .rating_error").text("");
	}
	
	var remarks = $("#mail_remark_common");
	if(!comment_validation($.trim(remarks.val()))){
		remarks.closest("div").find(".error-alert").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
		remarks.focus();
		return;
	}else{
		remarks.closest("div").find(".error-alert").text("");
		timer_detail.remarks = $.trim(remarks.val());
	}
	
	$("#mail_raiting_common").val(rating);
	$("#mail_to_common1").val(JSON.stringify(toObj));
	$("#mail_cc_common1").val(JSON.stringify(ccObj));
	$("#mail_bcc_common1").val(JSON.stringify(bccObj));
	
	if(UserEmailSettings == 1){
		if(mail_sub == ""){
			$.confirm({
				title: 'L Connectt',
				content: 'Are you sure you want to send without subject.',
				animation: 'none',
				closeAnimation: 'scale',
				closeIcon: true,
				closeIconClass: 'fa fa-close',
				buttons: {
					Ok: function () {
						mailSendRqst();
					},
					Cancel: function () {
						
					}
				}
			});
		}else{
			mailSendRqst();
		}
		
		//------------------------old
		/* if(mail_sub == ""){
			var r = confirm("Are you sure you want to send without subject");
		}else{
			r = true;
		}
		if (r == true) {
			$('#custom_mail').on('submit', function (e){
				e.preventDefault();
			});
			$('#editor12_common').val(CKEDITOR.instances.editor1_common.document.getBody().getHtml())
			var form = $('#custom_mail')[0];
			var fd = new FormData(form);
			$('#mail_send_common').attr('disabled', 'disabled');
			$.ajax({
				url: '<?php echo site_url('sales_com_personalmailController/submitFormdata'); ?>',
				enctype: 'multipart/form-data',
				data: fd,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function(data){
					if(error_handler(data)){
						return;
					}
					$.alert({
						title: 'L Connectt',
						content: data.split('"').join('').trim(),
						Ok: function () {
							$('#mail_send_common').removeAttr('disabled');
							close_assign('compose'); 
						}
					});	
					
				},
				error: function(data){
					network_err_alert(data);
				}
			});
		}else{
			
		} */
		return;
	}
}
var CommonratingVal = 0;
function Commonrating(rating){
	$(".rating_error").text("");
	$(".rating1,.rating2,.rating3,.rating4").css("color",'#d2cfcf');
	if(rating==4){
		$(".rating1,.rating2,.rating3,.rating4").css("color",'#B5000A');
		$(".rating_msg").text("Completely achieve");
	}
	if(rating==3){
		$(".rating1,.rating2,.rating3").css("color",'#B5000A');
		$(".rating_msg").text("Achieved but not completely");
	}
	if(rating==2){
		$(".rating1,.rating2").css("color",'#B5000A');
		$(".rating_msg").text("Partially achieved");
	}
	if(rating==1){
		$(".rating1").css("color",'#B5000A');
		$(".rating_msg").text("Did not achieved");
	}
	CommonratingVal = rating;
}
function mailSendRqst(){
	$('#custom_mail').on('submit', function (e){
		e.preventDefault();
	});
	$('#editor12_common').val(CKEDITOR.instances.editor1_common.document.getBody().getHtml())
	var form = $('#custom_mail')[0];
	var fd = new FormData(form);
	$('#mail_send_common').attr('disabled', 'disabled');
	$.ajax({
		url: '<?php echo site_url('sales_com_personalmailController/submitFormdata'); ?>',
		enctype: 'multipart/form-data',
		data: fd,
		processData: false,
		contentType: false,
		type: 'POST',
		success: function(data){
			if(error_handler(data)){
				return;
			}
			//data == '0' Mail has been not sent successfully
			//data == '1' Mail has been sent successfully
			
			var msg = ($.trim(data) == '0' ? "Mail has not been sent." : "Mail has been sent successfully.")
			$.confirm({
				title: 'L Connectt',
				content: msg,
				buttons: {
					Ok: function () {
						$('#mail_send_common').removeAttr('disabled');
						if($.trim(data) == '1'){
							close_assign('compose'); /* ----Call end time count function------ */
						}
					}
				}
			});	
			
		},
		error: function(data){
			network_err_alert(data);
		}
	});
}
 
function setCookie(cname, cvalue, exdays) {
	document.cookie = cname + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT";path='+window.location.href;
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires="+ d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path="+window.location.href;
}

function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}
function getval(){	
	var toggleVal = $("body").attr("class").split(" ");
	var row1 = '', width = '', versionName = '';
	width = $(".main-sidebar").css("width");
	versionName = $(".version_name").text();
	if(width == "230px"){
		if(versionName == "Professional"){
			row1 += "<div class='version_type'>PRO</div>";
		}else if(versionName == "Premium"){
			row1 += "<div class='version_type'>PRM</div>";
		}else{
			row1 += "<div class='version_type'>Lite</div>";
		}
	}else{
		if(versionName == "PRO"){
			row1 += "<div class='version_type'>Professional</div>";
		}else if(versionName == "PRM"){
			row1 += "<div class='version_type'>Premium</div>";
		}else{
			row1 += "<div class='version_type'>Lite</div>";
		}
	}
	$(".version_name").empty().append(row1);
	if(toggleVal.length == 3 && toggleVal[2] == 'my-task-page'){
		$(".sidebar-toggle").attr("title","Maximize");
	}else if(toggleVal.length == 3){
		$(".sidebar-toggle").attr("title","Minimize");
	}else if(toggleVal.length == 4){		
		$(".sidebar-toggle").attr("title","Minimize");
	}else{		
		$(".sidebar-toggle").attr("title","Maximize");
	}
}
/* -------------------Send email Function--- Ends---------------- */
</script>

<header class="main-header">
	<a class="logo" >
		<span class="logo-mini" >
			<img src="<?php echo base_url(); ?>images/new/White L.png" alt="Lconnect Logo" class="lc-logo-mini">
		</span>
		<span class="logo-lg">
			<img src="<?php echo base_url(); ?>images/new/White Logo.png" alt="Lconnect Logo" class="lc-logo-lg">
		</span>
	</a>
	<nav class="navbar navbar-static-top" role="navigation">
		<a class="sidebar-toggle" data-toggle="offcanvas" role="button" title="Minimize" onclick="getval()"></a>
		<div class="navbar-custom-menu">
			<div class="user-panel" >
				<ul class="nav navbar-nav">
					<li>
						<label class='accessLabel'>
							<a id="reloadClick" style="color: #fff;font-size:20px;" onclick="location.reload();" data-toggle="tooltip" data-placement="bottom" title="Refresh"><span class="glyphicon glyphicon-refresh"></span></a>
						</label>
					</li>
					<?php if($_SESSION['active_module_name'] == 'manager' || $_SESSION['active_module_name'] == 'executive'){ ?>
					<li>
						<label class='accessLabel'>
							<a id="bellClick" style="color: #fff;" data-toggle="tooltip" data-placement="bottom" title="Notification"><i class="fa fa-bell fa-lg" aria-hidden="true"></i></a>
						</label>
						<span class="notificationCount">
							<label class="count"></label>
						</span>
					</li>
					<?php } ?>
					<li >
						<label class='accessLabel'>
							<span id="getuser_name"></span><br>
							<span id="mdlName" style="font-size:11px;float: right;"><?php echo ucfirst($_SESSION['active_module_name']) ?></span>
						</label>
					</li>
					<li>
						<div class="pull-left image">
							<a href="#">
								<img src='<?php echo base_url(); ?>images/default-pic.jpg' title="<?php echo ucfirst($_SESSION['active_module_name']) ?> Module" id="adminAvt" >
							</a>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</header>

	<!----- manger module user profile view----- -->
<div id="pviewmodal" class="modal fade"  data-backdrop="static">
	<div class="modal-dialog  modal-lg">
		<div class="modal-content">
		<input type="hidden" id="submit_user_id"/>
				<div class="modal-header ">
					 <span class="close" onclick="e_cancel2()">x</span>
					 <h4 class="modal-title">View</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="panel-group" id="accordion">
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<a data-toggle="collapse" data-parent="#accordion" href="#personalInfo">
										<h4 class="panel-title">Personal Information</h4>
									</a>
								</div>
								<div id="personalInfo" class="panel-collapse collapse in personalInfo">

								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<a data-toggle="collapse" data-parent="#accordion" href="#officInfo">
										<h4 class="panel-title">Office Information</h4>
									</a>
								</div>
								<div id="officInfo" class="panel-collapse collapse officInfo">
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<a data-toggle="collapse" data-parent="#accordion" href="#accessControls">
										<h4 class="panel-title">Access Controls</h4>
									</a>
								</div>
								<div id="accessControls" class="panel-collapse collapse accessControls">

								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<a data-toggle="collapse" data-parent="#accordion" href="#businessInfo">
										<h4 class="panel-title">Business Details</h4>
									</a>
								</div>
								<div id="businessInfo" class="panel-collapse collapse businessInfo">

								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<a data-toggle="collapse" data-parent="#accordion" href="#prodCurrInfo">
										<h4 class="panel-title">Productivity Details</h4>
									</a>
								</div>
								<div id="prodCurrInfo" class="panel-collapse collapse ">
									<div class="row prodCurrInfo">
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<a data-toggle="collapse" data-parent="#accordion" href="#targetInfo">
										<h4 class="panel-title">Target Details</h4>
									</a>
								</div>
								<div id="targetInfo" class="panel-collapse collapse targetInfo">

								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<a data-toggle="collapse" data-parent="#accordion" href="#deviceInfo">
										<h4 class="panel-title">Device Details</h4>
									</a>
								</div>
								<div id="deviceInfo" class="panel-collapse collapse deviceInfo">

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer e_section5 ">
				</div>
		</div>
	</div>
</div>
<!---------------------User Edit------------------------>
<div id="adminProfileE" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div id="adminEditForm" class="form">
				<input type="hidden" id="adminEditid"/>
				<div class="modal-header">
					 <span class="close" onclick="cancel_edit()">x</span>
					 <h4 class="modal-title">Edit Profile</h4>
				</div>
				<div class="modal-body">
					<div class="row hidden">
						<div class="col-md-3">
							<label for="adminNameE">Name*</label>
						</div>
						<div class="col-md-9">
							<input type="text" class="form-control" id="adminNameE" readonly/>
							<input type="hidden" class="form-control" id="hid_oldpassword"/>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row hidden">
						<div class="col-md-3">
							<label for="adminModE">Mobile*</label>
						</div>
						<div class="col-md-9">
							<input type="text" class="form-control" id="adminModE" readonly/>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row hidden">
						<div class="col-md-3">
							<label for="adminUsernameE">Login ID*</label>
						</div>
						<div class="col-md-9">
							<input type="text" class="form-control" id="adminUsernameE" readonly/>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<center>
							<!-- ---------------------------------- -->
								<form method="POST" enctype="multipart/form-data" action="" id="imageUploadA">
									<div style="width: 100%; max-width: 110px;height: 110px; background: #ccc; border-radius: 5px;">
										<img  style="width: 100px;height: 100px; margin:5px" id="contactAddAvatar" alt="Avatar" src="<?php echo base_url(); ?>images/default-pic.jpg" class="round" />
									</div>
									<a href="#"><label style="width: 110px;"  for="contactImageUploadA" class="custom-file-upload"> <i class="fa fa-cloud-upload"></i>Upload</label></a>
									<input style="width: 110px;" type="file" class="form-control" accept="image/*"  name = "userfile" id="contactImageUploadA" onchange="addimageloaded('change');" />
									<input style="width: 110px;" type="hidden"   name = "pre_url" id="pre_url" />

								</form>
							<!-- ---------------------------------- -->
								<span style="color:#ff0000;font-size:11px;">
									<?php
									if($this->session->flashdata('error')){echo $this->session->flashdata('error');}
									?>
								</span>
								<span id="dp_error" class="error-alert"></span>
							</center>
						</div>
                        <!--<div class="col-md-9">
							<center>
							   	<label for="adminImageUploadE" class="custom-file-upload">
									<img width="100" height="100" id="adminAvatar" alt="Avatar">
							  </label>
							</center>
						</div>-->

					</div>
				</div>
				<div class="modal-footer">
				<center style="position: absolute;">
					<a href="#"><label onclick="PWResetFormShow()">Click here to change the password</label></a>
				</center>
					<input type="button" class="btn btn-default" onclick="addimageloaded('click')" value="Save">
					<input type="button" class="btn btn-default" onclick="cancel_edit()" value="Cancel" >
					<!--<input type="button" class="btn btn-default" onclick="adminProfileEdit()" value="Save">-->
				</div>
			</div>
			<form id="adminPasswordResetForm" class="form" style="display:none">
				<div class="modal-header">
					 <span class="close" onclick="cancel_edit()">x</span>
					 <h4 class="modal-title">Change Password</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-3">
							<label for="adminOldPw">Old Password*</label>
						</div>
						<div class="col-md-9">
							<input type="password" class="form-control" id="adminOldPw"/>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label for="adminNewPw">New Password*</label>
						</div>
						<div class="col-md-9">
							<input type="password" class="form-control" id="adminNewPw"/>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label for="adminConfirmPw">Confirm New Password*</label>
						</div>
						<div class="col-md-9">
							<input type="password" class="form-control" id="adminConfirmPw"/>
							<span class="error-alert"></span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" onclick="adminPasswordReset()" value="Save">
					<input type="button" class="btn btn-default" onclick="cancel_edit()" value="Cancel" >
				</div>
			</form>
		</div>
	</div>
</div>
<div id="addmodal_notification" class="modal fade" data-backdrop='static'>
	<div class="modal-dialog modal-lg" >
		<div class="modal-content">
			<div class="modal-header modal-title">
				 <span class="close" data-dismiss="modal">&times;</span>
				<center><h4 class="modal-title">Notification Details</h4></center>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 table-responsive">
						<table class="table table-bordered" id="notifytable">
							<thead>
								<tr>
									<th class="table_header" width="33%">Notification</th>
									<th class="table_header" width="32%">Details</th>
									<th class="table_header" width="20%">Created by</th>
									<th class="table_header" width="15%">Date</th>
									<!--<th class="table_header">Url</th>-->
								</tr>
							</thead>
							<tbody id="notifytablebody">
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="user_setting_modal_view" class="modal fade" data-backdrop='static'>
	<div class="modal-dialog" >
		<div class="modal-content">
			<div class="modal-header modal-title">
				<span class="close" onclick="cancel_setting()">&times;</span>
				<h4 class="modal-title">Setting</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-3">
						<label for="time_zone_setting"><b>Time Zone*</b></label>
					</div>
					<div class="col-md-9">
						<select class="form-control" id="time_zone_setting">
							<option>Choose</option>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="button" class="btn btn-default" onclick="save_setting()" value="Save">
				<input type="button" class="btn btn-default" onclick="cancel_setting()" value="Cancel" >
			</div>
		</div>
	</div>
</div>

<!--  User Email Settings Modal  -->
<div id="user_email_setting_modal" class="modal fade" data-backdrop='static'>
	<div class="modal-dialog" >
		<div class="modal-content">
			<div class="modal-header modal-title">
				<span class="close" onclick="cancel_email_setting()">&times;</span>
				<h4 class="modal-title">Email Setting</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-4">
						<label for="common_login_name">Name*</label>
					</div>
                    <div class="col-md-8">
                        <input class="form-control" id="common_login_name" type="text" placeholder="Send messages using this name" />
						<span class="error"></span>
					</div>
				</div>
                <div class="row">
					<div class="col-md-4">
					   <label for="common_login_email">Email ID*</label>
					</div>
                    <div class="col-md-8">
                        <input class="form-control" id="common_login_email" type="email" disabled />
						<span class="error"></span>
					</div>
				</div>
                <div class="row">
					<div class="col-md-4">
						<label for="common_login_password">Password*</label>
					</div>
                    <div class="col-md-8">
                        <input class="form-control" id="common_login_password" type="password"/>
						<span class="error"></span>
						<input type="hidden" id="user_email_settings_id" />
					</div>
				</div>
				<input class="form-control" id="hostName" type="hidden"/>
				<!--<div class="row">
					<div class="col-md-4">
						<label for="hostName">Incoming Host*</label>
					</div>
                    <div class="col-md-8">
                        <input class="form-control" id="hostName" type="text" placeholder="Example: xxx.xxx.xxx"/>
						<span class="error"></span>
					</div>
				</div>-->
				<div class="row">
					<div class="col-md-4">
						<label for="signature">Signature</label>
					</div>
                    <div class="col-md-8">
						<textarea class="form-control" id="signature"  onkeyup="characterLimit('signature', 255)" maxlength="255"></textarea>
						<span class="error"></span>
					</div>
				</div>
                <div id="common_server_msg"></div>
			</div>
			<div class="modal-footer">
				<input class="btn btn-default" type="button" id="ChangePassword" value="Change Password" onclick="ChangePasswordFunction()">
				<input class="btn btn-default hideBtn" type="button" id="addvalidatebtn" value="Validate" onclick="commonvalidatefield('add')">
				<input class="btn btn-default hideBtn" type="button" id="editvalidatebtn" value="Validate" onclick="commonvalidatefield('edit')">
				<input type="button" class="btn btn-default hideBtn" onclick="save_email_setting('add')" value="Save" disabled="disabled" id="common_email_save">
				<input type="button" class="btn btn-default hideBtn" onclick="cancel_email_setting('add')" value="Cancel" id="email_add_cancel">
				<input type="button" class="btn btn-default hideBtn" onclick="save_email_setting('edit')" value="Save" disabled="disabled" id="common_email_update">
				<input type="button" class="btn btn-default hideBtn" onclick="cancel_email_setting('edit')" value="Cancel" id="email_edit_cancel">
			</div>
		</div>
	</div>
</div>

<!--  Support mail/ compose mail Settings Modal  -->
<div id="mail_add_common" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<form enctype="multipart/form-data" method="post" id="custom_mail" name="custom_mail">
			<div class="modal-content">
				<div class="modal-header">
					<span class="close"  onclick="close_assign('compose')">&times;</span>
					<h4 class="modal-title">Compose</h4>
					<input type="hidden" class="form-control"  id="startComposeTime" name="startComposeTime" />
					<input type="hidden" class="form-control"  id="endComposeTime" name="endComposeTime" />
				</div>
				<div class="modal-body">
					<div class="row eId">
						<div class="col-md-2">
							<label for="mail_to_common">To</label>
						</div>
						<div class="col-md-10">
							<input type="text" class="form-control" id="mail_to_common" name="mail_to_common" />
							<input type="hidden" class="form-control" id="mail_to_common1" name="mail_to_common1" />
							<ul id="mail_to_commonUl"></ul>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row eId">
						<div class="col-md-2">
							<label for="mail_cc_common">Cc</label>
						</div>
						<div class="col-md-10">
							<input type="text" class="form-control" id="mail_cc_common" />
							<input type="hidden" class="form-control" id="mail_cc_common1" name="mail_cc_common" />
							<ul id="mail_cc_commonUl"></ul>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row eId">
						<div class="col-md-2">
							<label for="mail_bcc_common">Bcc</label>
						</div>
						<div class="col-md-10">
							<input type="text" class="form-control" id="mail_bcc_common"/>
							<input type="hidden" class="form-control" id="mail_bcc_common1" name="mail_bcc_common" />
							<ul id="mail_bcc_commonUl"></ul>
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<label for="mail_sub_common">Subject</label>
						</div>
						<div class="col-md-10">
							<input type="text" class="form-control" id="mail_sub_common" name="mail_sub_common" />
							<span class="error-alert"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2" >
							<a href="#">
								<label for="mail_attach_common" class="custom-file-upload btn"> 
									<i class="fa fa-cloud-upload"></i> Attachment
								</label>
							</a>
							<input type="file" class="form-control" name="mail_attach_common[]" multiple id="mail_attach_common" onchange="mail_attach_validation('mail_attach_common');"/>  
							<!-- accept="image/*"-->
							
						</div>
						<div class="col-md-10" >
							<p id="file_name_common"></p>
						</div>
					</div>
					</br>
					<div class="row">
						<div class="col-md-12">
							<textarea cols="80" id="editor1_common" name="editor1_common" rows="10" ></textarea>
							<textarea class="none" cols="80" id="editor12_common" name="editor12_common" rows="10" ></textarea>	
						</div>								
					</div>
					<div id="text_area"></div>
					<div class="row ratingCommentSection">
						<hr>
						<div class="col-md-2">
							<label><b>Rating activity*</b></label>
						</div>
						<div class="col-md-6 text-center">
							<label class="rating_activity_add">
								<i class="glyphicon glyphicon-star rating1" onclick="Commonrating(1)" style="color: rgb(181, 0, 10);"></i>
								<i class="glyphicon glyphicon-star rating2" onclick="Commonrating(2)" style="color: rgb(181, 0, 10);"></i>
								<i class="glyphicon glyphicon-star rating3" onclick="Commonrating(3)" style="color: rgb(181, 0, 10);"></i>
								<i class="glyphicon glyphicon-star rating4" onclick="Commonrating(4)" style="color: rgb(210, 207, 207);"></i>
							</label>
							<input type="hidden" class="form-control" id="mail_raiting_common" name="rating_common" />
							<br>
							<span class="error-alert rating_error"></span>
						</div> 
						<div class="col-md-4">
							<span class="rating_msg"></span>
						</div>
						<div class="col-md-12">
							<textarea class="form-control" placeholder="Enter Remarks" id="mail_remark_common" name="remark_common" ></textarea>
							<span class="error-alert"></span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn" id="mail_send_common" onclick="mail_send()" value="Send">
				</div>
			</div>
		</form>
	</div>
</div>
