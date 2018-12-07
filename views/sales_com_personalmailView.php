<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'scriptfiles.php' ?>
<style>
	body{
		padding-right:0px!important;
	}
	.searched_file{
		list-style-type: none;
		overflow-x: auto;
	}
	pre{
		border: none;
		white-space: pre-line;
		background: no-repeat;
	}
	.twitter-typeahead{
		width: 100%;
	}
	#file_name_common .attach_file_list{
		border: 1px solid #ccc;
		background: #f8f8f8;
		min-width: 225px;
		display: inline-flex;
	}
	/*-----------Type suggestion-------------*/
span.twitter-typeahead .tt-dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 250px;
    padding: 5px;
    margin: 2px 0 0;
    list-style: none;
    font-size: 14px;
    text-align: left;
    background-color: #ffffff;
    border: 1px solid #cccccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
}
span.twitter-typeahead .tt-suggestion > p {
    display: block;
    padding: 5px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.42857143;
    color: #333333;
    white-space: nowrap;
}
span.twitter-typeahead .tt-suggestion > p:hover,
span.twitter-typeahead .tt-suggestion > p:focus {
    color: #ffffff;
    text-decoration: none;
    outline: 0;
    background-color: #428bca;
}
span.twitter-typeahead .tt-suggestion.tt-cursor {
    color: #ffffff;
    background-color: #428bca;
}
span.twitter-typeahead {
    width: 100%;
}
.input-group span.twitter-typeahead {
    display: block !important;
}
.input-group span.twitter-typeahead .tt-dropdown-menu {
    top: 32px !important;
}
.input-group.input-group-lg span.twitter-typeahead .tt-dropdown-menu {
    top: 44px !important;
}
.input-group.input-group-sm span.twitter-typeahead .tt-dropdown-menu {
    top: 28px !important;
}
#mail_body{
	-webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
    font-weight: 400;
    font-size: 14px;
}
.table.dataTable{
	width: 100% !important;
}
</style>
<script>
var matchBtnSection ='';
var doc_data = "";
var domainExt = [];
var uid = "<?php echo $_SESSION['uid']; ?>";
$(document).ready(function(){	
	$("#number").prop("checked", true);
    $('input.my_btn').hide();
	$('[data-toggle="tooltip"').tooltip();
	
	groupMail('logdetails');
	keyfunction();
	
	var li_html = "<li><a onclick='supportMail(\"Compose\",\"Internal\")'><h4>Internal</h4></a></li>"+
					"<li><a onclick='supportMail(\"Compose\",\"Regular\")'><h4>Regular</h4></a></li>";
	$("#create_oppo").html('').append(li_html);
	$.ajax({
		type : "post",
		url : "<?php echo site_url('userEmailController/get_personal_emails/'); ?>"+uid,
		success : function(data){
			if(error_handler(data)){
				return;
			}
		},
		error : function(data){
			network_err_alert(data)
		}
	});
	
});


function data_load(){
        loaderShow();
      	 $.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_com_personalmailController/get_associatedata'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
                if(data.length == 0){

                }else{
                  loadpage(data);
                }
			},
			error : function(data){
				network_err_alert(data)
			}
		});
}

/*original text to encripted text: window.btoa(data[i].remarks);
encripted text to original text: window.atob(obj.remarks);*/
/* function syncEmails_personal(){ */
function groupMail(section){
	groupMail1(section);
	/* loaderShow();
	$.ajax({
		type : "post",
		url : "<?php echo site_url('emailExtractController/get_personal_emails'); ?>",
		success : function(data){
			if(error_handler(data)){
				return;
			}
			groupMail1(section);
		},
		error : function(data){
			network_err_alert(data)
		}
	}); */
}
var data_assoc, data_unassoc, data_conflict, data_all, internal, sent_item;
function groupMail1(data){
	loaderShow();
	/* Associated ajax call */
    if(data == 'logdetails'){
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_com_personalmailController/get_data/assoc'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
				data_assoc = data.data;
				load_assoc_page(data.data);
			},
			error : function(data){
				network_err_alert(data)
			}
		});
    }
	/* Un associated ajax call */
    if(data == 'logdetails1'){
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_com_personalmailController/get_data/unassoc'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
				data_unassoc = data.data;
				/* seperating internal and unassociated data */
				if(data.domain.length <= 0){
					loaderHide();
					return;
				}else{
					domainExt = data.domain;
					internal =[];
					var unassoc = [];
					for(i=0; i< data_unassoc.length; i++){
						if(data.domain.indexOf(data_unassoc[i].mail_from) >= 0){
							internal.push(data_unassoc[i]);
						}else{
							unassoc.push(data_unassoc[i]);
						}
						/*if(  domainExt[1] == mail_from[1]){
							internal.push(data_unassoc[i]);
						}else{
							unassoc.push(data_unassoc[i]);
						}*/
					}
					load_unassoc_page(unassoc ,'tablebody2', 'unassoc');
					load_unassoc_page(internal ,'tablebody5', 'internal');
				}
				
			},
			error : function(data){
				network_err_alert(data)
			}
		});
    }
	/* Conflict mails ajax call */
    if(data == 'logdetails2'){
		$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_com_personalmailController/get_data/conflict'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				if(error_handler(data)){
					return;
				}
				data_conflict = data.data;
				load_conflict_page(data.data);
			},
			error : function(data){
				network_err_alert(data)
			}
		});
    }
	/* All Mails ajax call */
    if(data == 'logdetails3'){
        $.ajax({
            type : "post",
            url : "<?php echo site_url('sales_com_personalmailController/get_data/allmails'); ?>",
            dataType : "json",
            cache : false,
            success : function(data){
                if(error_handler(data)){
                    return;
                }
				data_all = data.data;
                load_allmails_page(data.data);
            },
			error : function(data){
				network_err_alert(data)
			}
        });
    }
	/* Sent items mail  ajax call */
	if(data == 'logdetails5'){
        $.ajax({
            type : "post",
            url : "<?php echo site_url('sales_com_personalmailController/get_sentmail'); ?>",
            dataType : "json",
            cache : false,
            success : function(data){
                if(error_handler(data)){
                    return;
                }
				sent_item = data;
                load_sentmail_page(data);
            },
			error : function(data){
				network_err_alert(data)
			}
        });
    }
}
function load_assoc_page(data){
	loaderHide();
	/* ---------- associates ------------ */
	
	var row = "";
	var j=1;
	$('#tablebody1').parent("table").dataTable().fnDestroy(); 
	$('#tablebody1').empty();
	if(data.length == 0){
		$('#tablebody1').parent("table").DataTable();
		$('#tablebody1').parent("table").removeAttr('style');
		colspan = $('#tablebody1').parent("table").find('thead ').find('tr th').length;
		$('#tablebody1').parent("table").find('tbody').find('tr td').attr('colspan', colspan);
	}else{
		for(var i1=0; i1 < data.length; i1++){
			/* var rowdata = window.btoa(JSON.stringify(data[i1])); */
			var strtype='assoc';
			message_id = data[i1].message_id +'-'+data[i1].contact_id+'-'+data[i1].lead_cust_opp_id;
			var style = data[i1].mail_read_state == '0' ? "style=font-weight:bold;" : "style=font-weight:none;";
			var chkSentMail = parseInt(data[i1].mail_associated_state) == 10? "(Sent Item)" : "";
			
			data[i1].mail_date = moment(data[i1].mail_date , 'DD-MM-YYYY HH:mm:ss').format('lll');
			var isAttachment = data[i1].hasOwnProperty('attachment') ? "<i class='fa fa-paperclip' aria-hidden='true'></i>" : "";
			
			row += "<tr "+style+"><td>"
					+ (j) + "</td><td>" + data[i1].mail_from +" "+chkSentMail+ "</td><td>" 
				   /* + data[i1].mail_to + "</td><td>"*/
					+ (data[i1].mail_subject.length > 100 ? data[i1].mail_subject.substring(0, 100)+'...' : data[i1].mail_subject) + "</td><td>"
					+ data[i1].mail_date + "</td><td>"
					+capitalizeFirstLetter(data[i1].associate) + "</td><td>"+isAttachment+"</td>"+
					"<td><a href='#' title='View Mail' onclick='sub_details(\""+message_id+"\",\""+strtype+"\", this)'><span class='glyphicon glyphicon-eye-open'></span></a></td>"+
					"</tr>";

			j++;
		}
		$('#tablebody1').html('').html(row);
		$('#tablebody1').parent("table").DataTable();
	}
}
function load_unassoc_page(data ,tablebody ,strtype){
        loaderHide();
        /* var strtype='unassoc';
        var strtype='internal'; */
		$('#'+tablebody).parent("table").dataTable().fnDestroy();
		$('#'+tablebody).empty();
        if(data.length == 0){
			$('#'+tablebody).parent("table").DataTable();
			$('#'+tablebody).parent("table").removeAttr('style');
			colspan = $('#'+tablebody).parent("table").find('thead ').find('tr th').length;
			$('#'+tablebody).parent("table").find('tbody').find('tr td').attr('colspan', colspan);
        }else{
        	var row = "";
            var j=1;
                for(var i1=0; i1 < data.length; i1++){
                    var str = data[i1].associate==null ? "" : data[i1].associate;
                    data[i1].mail_date = moment(data[i1].mail_date , 'DD-MM-YYYY HH:mm:ss').format('lll');		
					var style = data[i1].mail_read_state == '0' ? "style=font-weight:bold;" : "style=font-weight:none;";
					var matchBnt = '';
					if( tablebody == 'tablebody2'){
						matchBnt = "<td><input type='button' style='margin-top: -3px' class='btn btn-sm' onclick='match_user(\""+data[i1].message_id+"\",\"match_"+data[i1].mail_from+"\",\"unassoc\")' value='Match' /></td>";
					}
					row += "<tr "+style+">"+
					"<td>" + (j) + "</td>"+
					"<td>" + data[i1].mail_from + "</td>"+
					/*"<td>" + data[i1].mail_to + "</td>"+*/
					"<td>" + (data[i1].mail_subject.length > 100 ? data[i1].mail_subject.substring(0, 100)+'...' : data[i1].mail_subject) + "</td>"+
					"<td>" + data[i1].mail_date + "</td>";
					/* not required to display association column 14-11-2018---
					row += (strtype == 'unassoc' ? "" : "<td>" + str + "</td>");
					*/
					
					row += matchBnt+"<td><a href='#' title='View Mail' onclick='sub_details(\""+data[i1].message_id+"\",\""+strtype+"\", this)'><span class='glyphicon glyphicon-eye-open'></span></a>"+

					"</td>"+
					"</tr>";
					j++;
                }
        	$('#'+tablebody).html("").html(row);
            $('#'+tablebody).parent("table").DataTable();
            $('#'+tablebody).parent("table").removeAttr('style');
            $('#'+tablebody).parent("table").find('thead').find('th').removeAttr('style');
        }

}

function load_conflict_page(data){
        loaderHide();
		var strtype='conflict';
		$('#tablebody3').parent("table").dataTable().fnDestroy(); 
		$('#tablebody3').empty();
        if(data.length == 0){
			$('#tablebody3').parent("table").DataTable();
			$('#tablebody3').parent("table").removeAttr('style');
			colspan = $('#tablebody3').parent("table").find('thead ').find('tr th').length;
			$('#tablebody3').parent("table").find('tbody').find('tr td').attr('colspan', colspan);
        }else{
        	var row = "";
            var j=1;
                for(var i1=0; i1 < data.length; i1++){
					var style = data[i1].mail_read_state == '0' ? "style=font-weight:bold;" : "style=font-weight:none;";
					data[i1].mail_date = moment(data[i1].mail_date , 'DD-MM-YYYY HH:mm:ss').format('lll');
                    var str = data[i1].associate == null ? "" : data[i1].associate ;
                    var str1= data[i1].mail_associated_state == 2 ? 'msg_'+data[i1].message_id : 'conf_'+data[i1].lead_cust_opp_id+"_"+data[i1].message_id ;
                    /* if(data[i1].associate==null){
                        str="";
                    }else{
                        str=data[i1].associate;
                    } */
                    /* if(data[i1].mail_associated_state==2){
                        str1='msg_'+data[i1].message_id;
                    }else{
                        str1='conf_'+data[i1].lead_cust_opp_id+"_"+data[i1].message_id;
                    } */
                row += "<tr>"+
                "<td>" + (j) + "</td>"+
                "<td>" + data[i1].mail_from + "</td>"+
                /*"<td>" + data[i1].mail_to + "</td>"+*/
                "<td>" + (data[i1].mail_subject.length > 100 ? data[i1].mail_subject.substring(0, 100)+'...' : data[i1].mail_subject) + "</td>"+
                "<td>" + data[i1].mail_date + "</td>"+
                /* not required to display association column 14-11-2018---
				"<td>" + str + "</td>"+ */
				"<td><input style='margin-top: -3px' type='button' class='btn btn-sm' onclick='match_user(\""+str1+"\",\"conflict_"+data[i1].mail_from+"\",\"conflict\")' value='Match' /></td>"+ 
                "<td><a href='#' title='View Mail' onclick='sub_details(\""+data[i1].message_id+"\",\""+strtype+"\", this)'><span class='glyphicon glyphicon-eye-open'></span></a>"+
                "</td>"+
                "</tr>";
                    j++;
                }
        	$('#tablebody3').html('').html(row);
            $('#tablebody3').parent("table").DataTable();
            $('#tablebody3').parent("table").removeAttr('style');
        }

}

function load_allmails_page(data){
    loaderHide();
    /* ---------- All mails ------------ */
	$('#tablebody4').parent("table").dataTable().fnDestroy(); 
	$('#tablebody4').empty();
	if(data.length == 0){
		$('#tablebody4').parent("table").DataTable();
		$('#tablebody4').parent("table").removeAttr('style');
		colspan = $('#tablebody4').parent("table").find('thead ').find('tr th').length;
		$('#tablebody4').parent("table").find('tbody').find('tr td').attr('colspan', colspan);
	}else{
		var row = "";
		var j=1;
		for(var i1=0; i1 < data.length; i1++){
			var strtype='allmails';
			var isAttachment = data[i1].hasOwnProperty('attachment') ? '<i class="fa fa-paperclip" aria-hidden="true"></i>' : '';
			var style = data[i1].mail_read_state == '0' ? "style=font-weight:bold;" : "style=font-weight:none;";
			/* if(data[i1].mail_read_state == '0'){
				style = "style=font-weight:bold;";
			}else{
				style = "style=font-weight:none;";
			} 
			
			if(data[i1].hasOwnProperty('attachment')){
				isAttachment = '<i class="fa fa-paperclip" aria-hidden="true"></i>';
			}else{
				isAttachment = '';
			}*/
			data[i1].mail_date = moment(data[i1].mail_date , 'DD-MM-YYYY HH:mm:ss').format('lll');
			if(data[i1].associate == null){
				data[i1].associate = '';
			}
			//------------15-11-2018 code match mail--
			var str1= data[i1].mail_associated_state == 2 ? 'msg_'+data[i1].message_id : 'conf_'+data[i1].lead_cust_opp_id+"_"+data[i1].message_id ;
			var matchbutton = ""
			if(data[i1].mail_associated_state == 0){
				//like unassociated match
				matchbutton = "<input type='button' style='margin-top: -3px' class='btn btn-sm' onclick='match_user(\""+data[i1].message_id+"\",\"match_"+data[i1].mail_from+"\",\"all_unassoc\")' value='Match' /> ";
			}else if(data[i1].mail_associated_state == 2){
				//like Conflict match
				matchbutton = "<input style='margin-top: -3px' type='button' class='btn btn-sm' onclick='match_user(\""+str1+"\",\"conflict_"+data[i1].mail_from+"\",\"all_conflict\")' value='Match' />"
			}
			//---------
			row += "<tr "+style+">"+
					"<td>"+ (j) + "</td>"+
					"<td>" + data[i1].mail_from + "</td>"+
					/*"<td>" + data[i1].mail_to + "</td>*/
					"<td>"+ (data[i1].mail_subject.length > 100 ? data[i1].mail_subject.substring(0, 100)+'...' : data[i1].mail_subject) + "</td>"+
					"<td>"+ data[i1].mail_date + "</td>"+
					"<td class='text-capitalize'>"+ data[i1].associate.split('_').join(' ') + "</td>"+
					"<td>"+isAttachment+"</td>"+
					"<td>"+matchbutton+"</td>"+
					"<td><a href='#' title='View Mail' onclick='sub_details(\""+data[i1].message_id+"\",\""+strtype+"\", this)'><span class='glyphicon glyphicon-eye-open'></span></a></td>"+
					"</tr>";
			j++;
		}
		$('#tablebody4').html('').html(row);
		$('#tablebody4').parent("table").DataTable();
		
		/* $('#tablebody4 tr a').each(function(){
			$(this).click(function(){
				$(this).closest('tr').removeAttr('style');
			})
		}) */
	}
}

function load_sentmail_page(data){
    loaderHide();
    /* ---------- sent  mails ------------ */
	$('#tablebody6').parent("table").dataTable().fnDestroy(); 
	$('#tablebody6').empty();
	if(data.length == 0){
		$('#tablebody6').parent("table").DataTable();
		$('#tablebody6').parent("table").removeAttr('style');
		colspan = $('#tablebody6').parent("table").find('thead ').find('tr th').length;
		$('#tablebody6').parent("table").find('tbody').find('tr td').attr('colspan', colspan);
	}else{
		var row = "";
		var j=1;
		for(var i1=0; i1 < data.length; i1++){
			var strtype='sent_item';
			var isAttachment='';
			var isAttachment = data[i1].hasOwnProperty('attachment') ? '<i class="fa fa-paperclip" aria-hidden="true"></i>' : '';
			/* if(data[i1].hasOwnProperty('attachment')){
				isAttachment = '<i class="fa fa-paperclip" aria-hidden="true"></i>';
			}else{
				isAttachment = '';
			} =---associate */
			if(data[i1].associate == null){
				data[i1].associate ='';
			}
			var matchbutton = '';
			if(capitalizeFirstLetter(data[i1].associate) == 'Unassociated'){
				//like unassociated match
				matchbutton = "<input type='button' style='margin-top: -3px' class='btn btn-sm' onclick='match_user(\""+data[i1].message_id+"\",\"match_"+data[i1].mail_to+"\",\"sentItem\")' value='Match' /> ";
			}
			data[i1].mail_date = moment(data[i1].mail_date , 'DD-MM-YYYY HH:mm:ss').format('lll');
			row += "<tr><td>"
					+ (j) + "</td><td>" + data[i1].mail_to.split(',').join(', ') + "</td><td>" 
				   /* + data[i1].mail_to + "</td><td>"*/
					+ (data[i1].mail_subject.length > 100 ? data[i1].mail_subject.substring(0, 100)+'...' : data[i1].mail_subject) + "</td><td>"
					+ data[i1].mail_date + "</td><td class='text-capitalize'>"
					+ data[i1].associate + "</td><td>"+isAttachment+"</td>"+
					"<td>"+matchbutton+"</td>"+
					"<td><a href='#' title='View Mail' onclick='sub_details(\""+data[i1].message_id+"\",\""+strtype+"\", this)'><span class='glyphicon glyphicon-eye-open'></span></a></td>"+
					"</tr>";
			j++;
		}
		$('#tablebody6').html('').html(row);
		$('#tablebody6').parent("table").DataTable();
	}
}
function search_data(){
	var addObj = {};
	if($("#name").prop("checked") == true){
		addObj.nametype = "name";
		//if(!($.trim($("#search_value").val().match(/^[a-zA-Z]+$/)))) { -- not taking space
		if(!adminName_chk.test($.trim($("#search_value").val()))){
			$("#search_value").closest("div").find("span").text("only alphabets are allowed");
			return;
		}else if($.trim($("#search_value").val())==""){
            $("#search_value").closest("div").find("span").text("Please Enter Value");
			return;
		}else{
			$("#search_value").closest("div").find("span").text("");
		}
	}
	if($("#number").prop("checked") == true){
		addObj.nametype = "number";
		if(!($.trim($("#search_value").val().match(/^[0-9]+$/)))) {
			$("#search_value").closest("div").find("span").text("only numericals are allowed");
			return;
		}else if($.trim($("#search_value").val())==""){
            $("#search_value").closest("div").find("span").text("Please Enter Value");
			return;
		}else{
			$("#search_value").closest("div").find("span").text("");
		}
	}
	addObj.search_value = $("#search_value").val();
    loaderShow();
    $.ajax({
		type : "POST",
		url : "<?php echo site_url('sales_com_personalmailController/get_matchdata/unassoc'); ?>",
		dataType : 'json',
		data : JSON.stringify(addObj),
		cache : false,
		success : function(data){
            loaderHide();
            var row = "";
            
			
            if(data.length == 0){
				row += '<p class="error-alert text-center">No search found.</p>';
            }else{
				row += '<ul class="searched_file no-padding">';
				
				/* disable all */
				
				for(i=0;i<data.length;i++){
					/* ----------Change email rendering process-------- */
					var str=[];
					contact_email=JSON.parse(data[i].contact_email);
					for(j=0;j<contact_email.email.length;j++){
						if(contact_email.email[j]!=''){
						   str.push(contact_email.email[j].split(',').join(', '));
						}
					}
					var emailList = str.length > 0? '('+str.join(', ')+')':'';
					/* ------------------------------------------------ */
					
					row += '<li id="'+data[i].associated_id+'_'+data[i].contact_id+'_'+data[i].contact_name+'"><label>'+
						'<input name="remove_unassoc" type="radio" id="'+data[i].lead_cust_id+'_'+data[i].type+'" /> '+
						'<span>'+data[i].contact_name+'</span>&nbsp;'+
						'<span>'+emailList+'</span>&nbsp; &nbsp;&nbsp;'+
						'<span>'+data[i].name+'</span>&nbsp;'+
						'<span>('+capitalizeFirstLetter(data[i].type)+')</span>'+
					'</label></li>';
				}
				row += '</ul>';
				/* rating section
				row += '<div class="col-md-12"><hr></div>';
				row += '<div class="col-md-2">Rating*</div>'+
					'<div class="col-md-4">'+
					'<p class="text-center">'+
					'<label class="rating_activity_add">'+
					'<i class="glyphicon glyphicon-star rating1" onclick="rating(1)" style="color: rgb(210, 210, 210);"></i>'+
					'<i class="glyphicon glyphicon-star rating2" onclick="rating(2)" style="color: rgb(210, 210, 210);"></i>'+
					'<i class="glyphicon glyphicon-star rating3" onclick="rating(3)" style="color: rgb(210, 210, 210);"></i>'+
					'<i class="glyphicon glyphicon-star rating4" onclick="rating(4)" style="color: rgb(210, 207, 207);"></i>'+
					'</label>'+
					'<br>'+
					'<span class="error-alert rating_error"></span>'+
					'</p>'+
					'</div>'+
					'<div class="col-md-6">'+
					'<span class="rating_msg"></span>'+
					'</div>'; */
				row += '<div class="col-md-12"><hr></div>';
				row += "<div class='col-md-12'><p class='text-center'><button class='btn' onclick=remove_unassoc(\'unassoc\')>Match</button></p></div>";
            }

            
            $(".radio_select").empty();
            $(".radio_select").append(row);
		},
		error: function(data){
			network_err_alert(data);
		}
	});
}

function remove_unassoc(pagetype){
  var obj={};
  var flg=0;
  var value, associated_id;
     $('.searched_file li input[name=remove_unassoc]').each(function(){
      if($(this).prop('checked')==true){
         value = $(this).attr('id');
         associated_id = $(this).closest('li').attr('id');
         flg=1;
      }
    }) ;
    if(flg==0){
        loaderHide();
        alert("please select atleast one option");
        return;
    }
    var value1=value.split('_');
    obj.lead_cust_id =value1[0];
    obj.type =value1[1]  ;
    //obj.pagetype =pagetype; --- fetching value from global variable...
	if(matchBtnSection.indexOf('all_') >= 0 ){
		obj.pagetype = matchBtnSection.replace('all_', '');
	}else{
		obj.pagetype = matchBtnSection;
	}
    
    obj.hidmsgid = $('#hidmsgid').val();
    obj.hidemail = $('#hidemail').val();
    obj.associated_id = associated_id;
    loaderShow();
  	$.ajax({
		type : "POST",
		url : "<?php echo site_url('sales_com_personalmailController/remove_unassoc'); ?>",
		dataType : 'json',
		data : JSON.stringify(obj),
		cache : false,
		success : function(data){
			loaderHide();
			if(error_handler(data)){
				return;
			}
			$("#match_type").modal("hide");
			if(matchBtnSection == 'unassoc'){
				groupMail('logdetails1');// unasso
			}else if(matchBtnSection == 'conflict'){
				groupMail('logdetails2');// conflict
			}else if(matchBtnSection == 'sentItem'){
				groupMail('logdetails5')
			}else if(matchBtnSection.indexOf('all_') >= 0 ){
				groupMail('logdetails3');//all mail
			}

		},
		error: function(data){
			network_err_alert(data);
		}
    });
}

function mail_match(){
	$('#match_type').modal('hide');	
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
}


 function match_user(hidmsgid,hidemail,type){
		matchBtnSection = type;
        $("#number").prop("checked", true);
        $("#search_value").val("").attr('placeholder', 'Search by Phone Number');


        $("#number").change(function(){
            $("#search_value").val("").attr('placeholder', 'Search by Phone Number');
            $("#search_value").closest("div").find("span").text("Please Enter Value");
        })

        $("#name").change(function(){
             $("#search_value").val("").attr('placeholder', 'Search by Name');
             $("#search_value").closest("div").find("span").text("Please Enter Value");
        })
        var value=hidemail;
        var value1=value.split('_');
        //$("#attach_box").modal("hide");
		if(typeof(selectedMailData) != 'undefined'){
			if(selectedMailData.message_id != ""){ 
			// 22-11-2018 additional checking for second time match click from outside view pop-up
				if(selectedMailData.mail_read_state == '0'){
					if(!ratingChk()){
						return;
					};
					attach_match('view');
				}else{
					$("#attach_box").modal("hide");
					attach_match('view');
				}
			}
		}
		
        if(value1[0]=='match'){
            $("#match_type").modal("show");
            $(".radio_select").empty();
            $('#hidmsgid').val(hidmsgid);
            $('#hidemail').val(value1[1]);
            $('#matchdiv').show();
        }else{

            $("#match_type").modal("show");
            $(".radio_select").empty();
            $('#hidmsgid').val(hidmsgid);
            $('#hidemail').val(value1[1]);
            $('#matchdiv').hide();
            var addObj={};
            addObj.email=value1[1];
            addObj.hidmsgid=hidmsgid;
            var conftype=hidmsgid;
            var conftype1=conftype.split('_');    ;
            loaderShow();
            $.ajax({
        		type : "POST",
        		url : "<?php echo site_url('sales_com_personalmailController/get_matchdata/conflict'); ?>",
        		dataType : 'json',
        		data : JSON.stringify(addObj),
        		cache : false,
        		success : function(data){
                    loaderHide();
                    var row = "";
                    
                    if(conftype1[0]=='msg'){
						if(data.length == 0){
							row += '<ul class="searched_file no-padding">';
							row += '<li>No search found.</li>';
						}else{
							/* disable all */
							
							row += '<ul class="searched_file no-padding">';
							
							for(i=0;i<data.length;i++){
								/* ----------Change email rendering process-------- */
								var str=[];
								contact_email=JSON.parse(data[i].contact_email);
								for(j=0;j<contact_email.email.length;j++){
									if(contact_email.email[j]!=''){
									   str.push(contact_email.email[j].split(',').join(', '));
									}
								}
								var emailList = str.length > 0? '('+str.join(', ')+')':'';
								/* ------------------------------------------------ */
								
								row += '<li id="'+data[i].associated_id+'_'+data[i].contact_id+'_'+data[i].contact_name+'"><label>'+
								'<input name="remove_unassoc" type="radio" id="'+data[i].lead_cust_id+'_'+data[i].type+'" />  '+
								'<span>'+data[i].contact_name+'</span>&nbsp;'+
								'<span>'+emailList+'</span>&nbsp;&nbsp;&nbsp;'+
								'<span>'+data[i].name+'</span>&nbsp;'+
								'<span>('+capitalizeFirstLetter(data[i].type)+')</span>'+
								'</label></li>';
							}
							row += '<li><hr></li>';
							row += "<li class='pull-right'><button class='btn' onclick=remove_unassoc(\'conflict\')>Match</button></li>";
						}
						row += '</ul>';
						$(".radio_select").empty();
						$(".radio_select").append(row);
                    }else{
						if(data.length == 0){
							row += '<ul class="searched_file no-padding">';
							row += '<li>No search found.</li>';
						}else{
							/* disable all */

							row += '<ul class="searched_file no-padding">';
							for(i=0;i<data.length;i++){
							  row += '<li><input name="remove_unassoc" type="radio" id="'+data[i].opportunity_id+'_opportunity" />  <label>'+data[i].opportunity_name+'</label></li>';
							}
							row += "<li class='pull-right'><button class='btn' onclick=remove_unassoc(\'conflict\')>Match</button></li>";
						}
						row += '</ul>';
						$(".radio_select").empty();
						$(".radio_select").append(row);
                    }

        		},
				error: function(data){
					network_err_alert(data);
				}
        	});
        }
 }

 
/* -------------------get email id with mane--- Starts---------------- */
var getAllMailGlobal;
var getAllMailGlobaldomain;
function getAllMail(){
	$.ajax({
			type : "POST",
			url : "<?php echo site_url('sales_com_personalmailController/getAllMail'); ?>",
			dataType : 'json',
			cache : false,
			success : function(data){
				//typeaheadSetup();	
				getAllMailGlobal = data.allmail;	
				getAllMailGlobaldomain = data.domain;	
			},
			error: function(data){
				network_err_alert(data);
			}
	})
} 

function typeaheadSetup(type){
	var data = [];
	var flag = 0;	
	if(type == "Internal"){
		data = getAllMailGlobaldomain;
	}else{
		var data1 = [];
		for(i = 0; i< data1.length; i++){
			if(data1[i].matchdata.length == 0){
				data.push(data1[i])
			}
		}
		for(i = 0; i< getAllMailGlobal.length; i++){
			!getAllMailGlobal[i].hasOwnProperty("matchdata") ? getAllMailGlobal[i]["matchdata"] =[] : "";
			data1.push(getAllMailGlobal[i]);
			if(getAllMailGlobal[i].matchdata.length > 0){
				for(j = 0; j< getAllMailGlobal[i].matchdata.length; j++){
					data1.push({	
							/* "Mail_ID": JSON.parse(getAllMailGlobal[i].matchdata[j].contact_email).email[0],  */
							"Mail_ID": getAllMailGlobal[i].Mail_ID, 
							"Name": getAllMailGlobal[i].Name+"-"+getAllMailGlobal[i].matchdata[j].name+"-"+getAllMailGlobal[i].matchdata[j].type,
							"matchdata": [],
							"leadName": getAllMailGlobal[i].matchdata[j].name,
							"leadType": getAllMailGlobal[i].matchdata[j].type,
							"associated_id": getAllMailGlobal[i].matchdata[j].associated_id,
							"contact_id": getAllMailGlobal[i].matchdata[j].contact_id,
						}
					)
				}
				
			}
		}
		
		for(i = 0; i< data1.length; i++){
			if(data1[i].matchdata.length == 0){
				data.push(data1[i])
			}
		}
	}
	for(i = 0; i< data.length; i++){
		data[i].Name = $.trim(data[i].Name);
		data[i].Mail_ID = $.trim(data[i].Mail_ID);
	}
	var dataSource2 = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('Mail_ID', 'Name'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		local: data									
	});
	if(data.length>0){
		dataSource2.initialize();
		$('#mail_to_common, #mail_cc_common, #mail_bcc_common').typeahead('destroy');	
		$('#mail_to_common, #mail_cc_common, #mail_bcc_common').typeahead(
			{
				minLength: 0,
				highlight: true,
				hint: false
			},
			{ 
				name: 'User_Name',
				display: function(data) {
					return data.Mail_ID 
				},
				source: dataSource2.ttAdapter(),
				templates: {
					/* empty:[
							'<div class="empty-message">',
								'No Match',
							'</div>'
						].join('\n'), */
					suggestion: function(data){
						return '<div><strong>'+data.Mail_ID+'</strong> – '+data.Name+'</div>';
					}
				}
			}
		//).bind('typeahead:closed', function (){
		).bind('typeahead:opened', function (){
			$('#mail_to_common,#mail_cc_common, #mail_bcc_common').each(function(){
				$(this).bind("keydown blur", function(e) {
					var keyCode = e.keyCode || e.which; 
					if (e.type == 'blur' || keyCode == 13 || keyCode == 9 ){
						$(this).closest('div').find(".error-alert").text("");
						if ($(this).val().trim() != ""){
							if(!validate_email($(this).val().trim())){
								$(this).focus();
								$(this).closest('div').find(".error-alert").text("Invalid Mail id.");
								return;
							}
							if(type == "Internal"){
								alert('It seems that you have typed an external email. Please send this message to an internal L Connectt user or compose a new email for someone external.')
							}else{
								var row ='<li>';
								row += '<span class="form-control display"><label>'+$(this).val().trim()+'</label></span>';
								row += '<span class="glyphicon glyphicon-remove" onclick="removeMail(this)"></span>';
								row +='</li>';
								$(this).closest('span').siblings('ul').append(row);
							}
							$(this).typeahead('val','');
						}
					}
				});
			 })
			 //$('#mail_to_common,#mail_cc_common, #mail_bcc_common').typeahead('val','');
			 
		});
		 $('#mail_to_common,#mail_cc_common, #mail_bcc_common').each(function(){ 
		/*$('#mail_to_common').each(function(){*/
				/* $(this).bind("keydown blur", function(e) {
					var keyCode = e.keyCode || e.which; 
					if (keyCode == 13 || keyCode == 9 ){
						$(this).closest('div').find(".error-alert").text("");
							
						if ($(this).val().trim() != ""){
							if(!validate_email($(this).val().trim())){
								$(this).focus();
								$(this).closest('div').find(".error-alert").text("Invalid Mail id.");
								return;
							}
							
							var row ='<li>';
							row += '<span class="form-control display"><label>'+$(this).val().trim()+'</label></span>';
							row += '<span class="glyphicon glyphicon-remove" onclick="removeMail(this)"></span>';
							row +='</li>';
							$(this).closest('span').siblings('ul').append(row);
							$(this).val('');
						}
					}
				}); */
			
			
			$(this).on('typeahead:selected', function (e, datum){
				var TempData={}, row = "";
				//------------------
				// if(datum.matchdata.length > 0){
					// row +='<ul>';
					// for(i = 0; i< datum.matchdata.length; i++){
						// TempData = {
							// associated_id : datum.matchdata[i].associated_id,/*for send*/
							// contact_name : datum.matchdata[i].contact_name,/*For display*/
							// name : datum.matchdata[i].name,/*For display*/
							// contact_id : datum.matchdata[i].contact_id,/*For send*/
							// type : datum.matchdata[i].type	/*for send and display*/
						// }
						// row += 	'<li>'+
									// '<label>'+
										// '<input value="'+TempData.associated_id +'-'+ TempData.contact_id +'-'+ TempData.type+'" name="matchUserMail" type="radio" />'+
										// '<span> ' + TempData.contact_name + ' / &nbsp; </span>'+
										// '<span> ' + TempData.name + ' / &nbsp; </span>'+
										// '<span  class="text-capitalize"> ( ' + TempData.type + ' )</span>'+
									// '</label>'+
								// '</li>';
			  
					// }
					// row +='</ul>';
					// $(this).closest('span').after(row);
				// }
				//-------------
				//$(this).val('')
				row +='<li>';
				row += '<span class="form-control display"><label>'+datum.Mail_ID +'</label> ('+ datum.Name+')</span>';
				if(datum.hasOwnProperty('associated_id')){
					row += '<input class="form-control actualVal" type="hidden" value="'+datum.associated_id +'-'+ datum.contact_id +'-'+ datum.leadType+'" >';
				}
				row += '<span class="glyphicon glyphicon-remove" onclick="removeMail(this)"></span>';
				row +='</li>';
				$(this).closest('span').siblings('ul').append(row);
				
				$(this).val('');
				$(this).typeahead('val','');
			});
			
		})
		
	}
}

/* -------------------get email id with mane--- Ends---------------- */
/* -------------------send  email validation--- Starts---------------- */

function removeMail(e){
	$(e).closest('li').remove();
}
function keyfunction(){
	$("#mail_to_common, #mail_cc_common, #mail_bcc_common").each(function(i){
		$(this).on("keydown", function(e) {
			var keyCode = e.keyCode || e.which; 
			if (keyCode == 13 || keyCode == 9 ) { 
				//e.preventDefault(); 
				//myfunction($(this));
			} 
		});
	})
	getAllMail();
}
function myfunction(e){
	var id = e.attr('id');
	if(id === 'mail_to_common'){
		if( $("#" + id).val() == "" ){
			$("#" + id).closest('div').find(".error-alert").text("Mail id is required.");
			$("#" + id).focus();
			//$("#" + id).closest('span').siblings('ul').remove();
			return;
		}else if(!validate_email($("#" + id).val())){
			$("#" + id).closest('div').find(".error-alert").text("Invalid Mail id.");
			$("#" + id).focus();
			//$("#" + id).closest('span').siblings('ul').remove();
			return;
		}else{
			$("#" + id).closest('div').find(".error-alert").text("");
			
			var row ='<li>';
			row += '<span class="form-control display"><label>'+$("#" + id).val().trim()+'</label></span>';
			row += '<span class="glyphicon glyphicon-remove" onclick="removeMail(this)"></span>';
			row +='</li>';
			$("#" + id).closest('span').siblings('ul').append(row);
			//$('#mail_to_common').val('');
		}
	}else{
		if($("#" + id).val() != ""){
			if(!validate_email($("#" + id).val())){
				$("#" + id).closest('div').find(".error-alert").text("Invalid Mail id.");
				$("#" + id).focus();
				return;
			}else{
				$("#" + id).closest('div').find(".error-alert").text("");
			}
		}
	}
}
/* -------------------send email validation--- Ends---------------- */
 /* ------------------Read / write time count---Starts--------------------- */
	var c = 0,timer_is_on = 0, t;
	var timer_detail={
		"reading_start_at" : '',
		"reading_end_at" : '',
		"msg_id" : '',
		"counter_section" : ''
	}
	/* window.addEventListener('focus', doTimer);
	window.addEventListener('blur', stopCount); */
	
	function timedCount(){
		/* console.log('Time : ' + moment().format('DD-MM-YYYY HH:mm:ss')+'\ncount : ' + c); */
		c++;
		t = setTimeout("timedCount()",1000);
	}

	function doTimer(){
		timer_detail.reading_start_at = moment().format('DD-MM-YYYY HH:mm:ss');
		c = 0;
		if (!timer_is_on){
			timer_is_on = 1;
			timedCount();
		}
	}

	function stopCount(){		
		timer_detail.reading_end_at = moment().format('DD-MM-YYYY HH:mm:ss');
		clearTimeout(t);
		timer_is_on = 0;
	}
	function counterClose(section){
		stopCount();
		count = 0;
		timer_detail.counter_section = section;
		/* var cal_duration = 	moment.duration(moment(timer_detail.reading_end_at, 'DD-MM-YYYY HH:mm:ss').
							diff(moment(timer_detail.reading_start_at, 'DD-MM-YYYY HH:mm:ss'))).asMilliseconds("");
		timer_detail.duration = moment.utc(cal_duration).format("HH:mm:ss");
		console.log(timer_detail); */
		var addObj=timer_detail;
		$.ajax({
        		type : "POST",
        		url : "<?php echo site_url('sales_com_personalmailController/insert_reading_time'); ?>", 
        		dataType : 'json',
        		data : JSON.stringify(addObj),
        		cache : false,
        		success : function(data){
					if(error_handler(data)){
						return;
					}
					selectedMailData.message_id = "";// 22-11-2018 making empty for second time match click from outside view pop-up
					/*------------------------------------------------*/
					console.log(timer_detail.mail_type);
					switch(timer_detail.mail_type) {
						case 'assoc':
							$.each(data_assoc,function(key, val){
								if( val['message_id'] === timer_detail.msg_id){
									if(val.hasOwnProperty('mail_read_state')){
										val.mail_read_state = '1';
									}
								}
							})
							break;
						case 'unassoc':
							$.each(data_unassoc,function(key, val){
								if( val['message_id'] === timer_detail.msg_id){
									if(val.hasOwnProperty('mail_read_state')){
										val.mail_read_state = '1';
									}
								}
							})
							break;
						case 'conflict':
							$.each(data_conflict,function(key, val){
								if( val['message_id'] === timer_detail.msg_id){
									if(val.hasOwnProperty('mail_read_state')){
										val.mail_read_state = '1';
									}
								}
							})
							break;
						case 'allmails':
							$.each(data_all,function(key, val){
								if( val['message_id'] === timer_detail.msg_id){
									if(val.hasOwnProperty('mail_read_state')){
										val.mail_read_state = '1';
									}
								}
							})
							break;
						case 'internal':
							$.each(data_all,function(key, val){
								if( val['message_id'] === timer_detail.msg_id){
									if(val.hasOwnProperty('mail_read_state')){
										val.mail_read_state = '1';
									}
								}
							})
							break;
						case 'sent_item':
					}
					/*------------------------------------------------*/
					timer_detail={}
				},
				error: function(data){
					network_err_alert(data);
				}
		})
	}
/* ------------------Read / write time count---Ends--------------------- */
/* ------------------close add Lead function extend--------------------- */


function ratingChk(){
	var remarks = $('#attach_box .ratingCommentSection textarea');
	timer_detail.remarks = "";
	var check = false;
	//if(selectedMailData.mail_type != "unassoc"){
		if(selectedMailData.mail_read_state == '0'){
			if(timer_detail.raring == '0'){
				$(".rating_error").text("Rating is required.");
				check = false;
				var objDiv = document.getElementById("attach_box");
				objDiv.scrollTop = objDiv.scrollHeight;
				return;	
			}
			if(!comment_validation($.trim(remarks.val()))){
				remarks.closest("div").find(".error-alert").text("No special characters allowed (except & : () # @ _ . , + % ? -)");
				remarks.focus();
				check = false;
				return;
			}else{
				remarks.closest("div").find(".error-alert").text("");
				timer_detail.remarks = $.trim(remarks.val());
				check = true;
			}
		}else{
			check = true;
		}
	/* }else{
		check = true;
	} */
	return check;
}
function attach_match(section){
	if(!ratingChk()){
		return;
	}
	if(selectedMailData.mail_read_state == '0' || selectedMailData.mail_type == "assoc" || selectedMailData.mail_type == "unassoc"){
		counterClose(section); /* ----Call end time count function view mail------ */
	}
	
	$('#attach_box').modal('hide');
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
}
function rating(rating){
	$(".rating_error").text("");
	$(".rating1,.rating2,.rating3,.rating4").css("color",'#d2cfcf');
	if(rating==4){
		$(".rating1,.rating2,.rating3,.rating4").css("color",'#B5000A');
		$(".rating_msg").text("Completely achieved");
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
		$(".rating_msg").text("Did not achieve");
	}
	timer_detail.raring = rating;
}
var selectedMailData;
function sub_details(msg_id,strtype, e){
	
 	$("#cc_name").closest('div.row').hide();
 	$("#bcc_name").closest('div.row').hide();
 	$("#attach_file").closest('div.row').hide();
	$(".rating_msg").text("");
	$(".rating_activity_add .glyphicon.glyphicon-star").css("color",'#d2cfcf');
	$(e).closest('tr').removeAttr('style');
	timer_detail.msg_id = msg_id;
	if(strtype == 'assoc'){
		var ids = msg_id.split('-');
		//data[i1].message_id +'-'+data[i1].contact_id+'-'+data[i1].lead_cust_opp_id;
		timer_detail.msg_id = ids[0];
		timer_detail.contact_id = ids[1];
		timer_detail.lead_cust_opp_id = ids[2];
	}
	timer_detail.mail_type = strtype;
	timer_detail.raring = 0;
	doTimer(); /* ----Call start time count function------ */
	var data = {};
	
	if(strtype=='assoc'){ 
		data = data_assoc;
	};
	if(strtype=='unassoc'){
		data = data_unassoc;
	};
	if(strtype=='conflict'){
		data = data_conflict;
	};
	if(strtype=='allmails'){
		data = data_all;
	};
	if(strtype=='internal'){
		data = data_unassoc;
	}; 
	if(strtype=='sent_item'){
		data = sent_item;
	};
	
	
	$.each(data,function(key, val){
		if( val['message_id'] === timer_detail.msg_id){
			data = val;
		}
	})
	/* global data storing for forward, sent mail */
	selectedMailData = data;
	selectedMailData.mail_type = strtype;
	/* global data storing for forward, sent mail */
	timer_detail.mail_from = data.mail_from;
	if(strtype=='assoc'){ 
		timer_detail.type = data.associate;
	};
	if(strtype=='unassoc'){
		timer_detail.type = 'unassociated';
	};
	if(strtype=='conflict'){};
	if(strtype=='allmails'){};
	if(strtype=='internal'){}; 
	if(strtype=='sent_item'){};
	
	$("#attach_box").modal("show");
    $("#matchbtn").empty();
    $("#from_name").empty("").append(data.mail_from);
    $("#to_name").empty("").append(data.mail_to.split(',').join(', '));
    $("#from_name_hidden").val(data.mail_from);
    if(data.hasOwnProperty('mail_cc') && $.trim(data.mail_cc) != ""){
    	 $("#cc_name").empty("").append(data.mail_cc);
    	 $("#cc_name").closest('div.row').show();
    }
    if(data.hasOwnProperty('mail_bcc') && $.trim(data.mail_bcc) != ""){
    	$("#bcc_name").empty("").append(data.mail_bcc); 
    	$("#bcc_name").closest('div.row').show();	
    }
   

    $("#sub_name").empty("").append(data.mail_subject);
    $("#sub_date").empty("").append(data.mail_date);
    $("#mail_body").html("").append(data.mail_body);

    var row = "";
    var row1 = "";
    var str1 = "";
    if(strtype == 'unassoc'){
        $('#raise_ticket').hide();
        $('#create_lead').show();
        $('#create_contact').show();
        row1 += "<ul class='searched_file'><li><button class='btn' onclick='match_user(\""+data.message_id+"\",\"match_"+data.mail_from+"\",\"unassoc\")'>Match</button></li></ul>";
        $("#matchbtn").append(row1);
    }else if(strtype == 'internal'){
        $('#raise_ticket').hide();
        $('#create_lead').hide();
        $('#create_contact').show();
        /* row1 += "<ul class='searched_file'><li><button class='btn' onclick='match_user(\""+data.message_id+"\",\"match_"+data.mail_from+"\")'>Match</button></li></ul>";
        $("#matchbtn").append(row1); */
    }else if(strtype == 'conflict'){
        $('input.my_btn').hide();
        /* if(data.mail_associated_state==2){
                str1= 'msg_'+data.message_id;
        }else{
                str1= 'conf_'+data.lead_cust_opp_id+"_"+data.message_id;
        } */
		str1 = data.mail_associated_state == 2 ? 'msg_'+data.message_id : 'conf_'+data.lead_cust_opp_id+"_"+data.message_id ;
		
		row1 += "<ul class='searched_file'><li><button class='btn' onclick='match_user(\""+str1+"\",\"conflict_"+data.mail_from+"\",\"conflict\")'>Match</button></li></ul>";
        $("#matchbtn").append(row1);

    }else if(strtype == 'allmails'){
        $('input.my_btn').hide();
    }else if(strtype == 'assoc'){
        $('#raise_ticket').show();
        $('#create_lead').hide();
        $('#create_contact').hide();
    }

    if(data.hasOwnProperty('attachment')){
    	$("#attach_file").closest('div.row').show();
        $('.showdiv').show();
		row += "<ul class='attach_class'>";
        for(i=0;i<data.attachment.length;i++){
			var path = data.attachment[i].mail_attachment_path;
			var file_name = path.split('/').pop();
			var format = file_name.split('.').pop();
			var file_name = file_name.split('.')[0];
			doc_data = data;
            if(format == 'jpg' || format == 'jpeg' || format == 'gif' || format == 'bmp' || format == 'png'){
                row +='<li id="'+path+'" onclick="show_attachment(this.id,\'img\')">'+data.attachment[i].mail_attachment_filename+'</li>';
            }else if(format == 'mp3'){
				row +='<li id="'+path+'" onclick="show_attachment(this.id,\'audio\')">'+data.attachment[i].mail_attachment_filename+'</li>';
			}else if(format == 'mp4'){
				row +='<li id="'+path+'" onclick="show_attachment(this.id,\'video\')">'+ data.attachment[i].mail_attachment_filename +'</li>';
			}else if(format == 'pdf'){
				row +='<li><a href="https://lconnectt.in/pdfviewer/viewer.html?file=<?php echo base_url(); ?>uploads/' + data.attachment[i].mail_attachment_path +'" target="_blank">'+data.attachment[i].mail_attachment_filename+'</a></li>';
			}else{
				row +='<li>'+data.attachment[i].mail_attachment_filename+' (Cannot open this kind of files)</li>';
			}
			
            /* if(format == 'doc' || format == 'docx' || format == 'pdf' || format == 'rtf' || format == 'txt' || format == 'xls' || format == 'csv'|| format == 'php'){
				
            } */
        }
        row +='</ul>';
    }else{
      $('.showdiv').hide();
    }
    $("#attach_file").empty();
    $("#attach_file").append(row);
    $("#mail_body").find('style').remove();
    $("#mail_body").find('a').attr('href', 'javascript:void(0)').css("color", "black");
	$("#attach_box, .ratingCommentSection").show();
	/* if(selectedMailData.mail_read_state == '1' || !selectedMailData.hasOwnProperty('mail_read_state')|| selectedMailData.mail_type == "unassoc"){ */
	if(selectedMailData.mail_read_state != 0 || !selectedMailData.hasOwnProperty('mail_read_state')){
		$("#attach_box .ratingCommentSection").hide();
	}
	
 }
 function show_attachment(data, elm){
	$("#attachment_box").modal("show");
	for(i=0;i<doc_data.attachment.length;i++){
		if(data == doc_data.attachment[i].mail_attachment_path){				
			var file = '<?php echo base_url(); ?>uploads/' + doc_data.attachment[i].mail_attachment_path;
			if(elm == 'img'){
				$("#attachment_box .file_attach").css('height', '376px');
				$('#attachment_box .file_attach').css({
					'background-image':'url('+file+')',
					'background-repeat':' no-repeat',
					'background-size': 'contain',
					'background-position': 'center'});
			}
			if(elm == 'audio'){
				innerhtml = '<center><audio controls width="100%" controlsList="nodownload">'+
				'<source src="'+file+'" type="audio/mp3">'+
				'Your browser does not support the audio tag.'+
				'</audio><center>';
				$('#attachment_box .file_attach').html(innerhtml);
			}
			if(elm == 'video'){
				innerhtml = '<video width="100%" controls controlsList="nodownload">'+
				'<source src="'+file+'" type="video/mp4">'+
				'Your browser does not support the video tag.'+
				'</video>';
				$('#attachment_box .file_attach').html(innerhtml);
			}
		}
	}
 }
function add_cancel(){
	$('#leadinfoAdd.modal').modal('hide');
	$('#leadinfoAdd.modal .form-control[type=text],#leadinfoAdd.modal textarea').val("");
	$('#leadinfoAdd.modal select.form-control').val($('#leadinfoAdd.modal select.form-control option:first').val());
	$(".contact_type02").remove();
	$('#select_map').hide();
	$('#map1').show();
}
function attachment_match(){
	$('#attachment_box').modal('hide');
	$('#attachment_box .file_attach').removeAttr('style').html('');
	$('.modal input[type=text],.modal textarea, .modal input[type=checkbox], .modal select').val("");
}
function raise_ticket(event){
	var module_ = $('#mdlName').text().trim();
	if(module_ == "Manager"){
		if(event.id == 'raise_ticket'){
			window.location.href="<?php echo site_url('manager_supportrequestcontroller'); ?>";
		}else if(event.id == 'create_lead'){
			//window.location.href="<?php echo site_url('manager_leadController'); ?>";
			var email_id = $("#from_name_hidden").val();
			add_lead(email_id);
		}else if(event.id == 'create_contact'){
			window.location.href="<?php echo site_url('manager_contacts'); ?>";
		}
	}
	if(module_ == "Executive"){
		if(event.id == 'raise_ticket'){
			window.location.href="<?php echo site_url('sales_supportController'); ?>";
		}else if(event.id == 'create_lead'){
			//window.location.href="<?php echo site_url('leadinfo_controller'); ?>";
			var email_id = $("#from_name_hidden").val();
			add_lead(email_id);
		}else if(event.id == 'create_contact'){
			window.location.href="<?php echo site_url('sales_contactListController'); ?>";
		}
	}
}

function forwardSelectedMail(){
	if(selectedMailData.mail_read_state == '0'){
		if(!ratingChk()){
			return;
		};
		attach_match('view');
	}
	$('#mail_sub_common').val(selectedMailData.mail_subject);
	var forwardmail = '<p><label><b>---------- Forwarded message ---------</b></label><br/>'+
					'<label><b>Date: </b></label>'+selectedMailData.mail_date +'<br/>'+
					'<label><b>From: </b></label>'+selectedMailData.mail_from +'<br/>'+
					'<label><b>To: </b></label>'+selectedMailData.mail_to +'<br/>';
					
	forwardmail += 	selectedMailData.hasOwnProperty('mail_cc') ? '<label><b>CC: </b></label>'+selectedMailData.mail_cc +'<br/>' : "";
	
	forwardmail +=	'<label><b>Subject: </b></label>'+selectedMailData.mail_subject +'<br/><br/>'+
					selectedMailData.mail_body.replace(/(\r\n|\n)/g, "<br/>")+'<br/><br/></p><hr>';
	
	if(typeof(UserEmailSettingsValue) != "undefined" && UserEmailSettingsValue.hasOwnProperty('signature')){
	CKEDITOR.instances['editor1_common'].setData("<br/><br/><br/><p>"+ UserEmailSettingsValue.signature.replace(/(\r\n|\n)/g, "<br/>")+'</p>'+ forwardmail);
	}
	
	supportMail("Forward Mail","Regular");
	/* mail_to = selectedMailData.mail_to.split(',');
	$.each(mail_to, function(key, val){
		$('#mail_to_commonUl').append('<li><span class="form-control display"><label>'+val+'</label></span><span class="glyphicon glyphicon-remove" onclick="removeMail(this)"></span></li>');
	}) */
}
function replyAllSelectedMail(){
	if(selectedMailData.mail_read_state == '0'){
		if(!ratingChk()){
			return;
		};
		attach_match('view');
	}
	$('#mail_sub_common').val(selectedMailData.mail_subject);
	
	var forwardmail = '<p><label><b>-----------------------------------</b></label><br/>'+
					'<label><b>Date: </b></label>'+selectedMailData.mail_date +'<br/>'+
					'<label><b>From: </b></label>'+selectedMailData.mail_from +'<br/>'+
					'<label><b>To: </b></label>'+selectedMailData.mail_to +'<br/>';
					
	forwardmail += 	selectedMailData.hasOwnProperty('mail_cc') ? '<label><b>CC: </b></label>'+selectedMailData.mail_cc +'<br/>' : "";
	
	forwardmail +=	'<label><b>Subject: </b></label>'+selectedMailData.mail_subject +'<br/><br/>'+
					selectedMailData.mail_body.replace(/(\r\n|\n)/g, "<br/>")+'<br/><br/></p><hr>';
	
	if(typeof(UserEmailSettingsValue) != "undefined" && UserEmailSettingsValue.hasOwnProperty('signature')){
	CKEDITOR.instances['editor1_common'].setData("<br/><br/><br/><p>"+ UserEmailSettingsValue.signature.replace(/(\r\n|\n)/g, "<br/>")+'</p>'+ forwardmail);
	}
	
	supportMail("Reply All","Regular");
	mail_to = selectedMailData.mail_to.split(',');
	mail_from = selectedMailData.mail_from.split(',');
	maiTo = mail_to.concat(mail_from);
	
	$.each(maiTo, function(key, val){
		if($.trim(UserEmailSettingsValue.email_id) != $.trim(val)){
			if($.trim(val) != ""){
				$('#mail_to_commonUl').append('<li><span class="form-control display"><label>'+$.trim(val)+'</label></span><span class="glyphicon glyphicon-remove" onclick="removeMail(this)"></span></li>');
			}
		}
		
	})
	if(selectedMailData.hasOwnProperty('mail_cc')){
		ccMail = selectedMailData.mail_cc.split(',');
		$.each(ccMail, function(key, val){
			if($.trim(UserEmailSettingsValue.email_id) != $.trim(val)){
				if($.trim(val) != ""){
					$('#mail_cc_commonUl').append('<li><span class="form-control display"><label>'+$.trim(val)+'</label></span><span class="glyphicon glyphicon-remove" onclick="removeMail(this)"></span></li>');
				}
			}
			
		})
	}
	/* if(selectedMailData.hasOwnProperty('mail_bcc')){
		bccMail = selectedMailData.mail_bcc.split(',');
		$.each(bccMail, function(key, val){
			if($.trim(UserEmailSettingsValue.email_id) != $.trim(val)){
				$('#mail_bcc_commonUl').append('<li><span class="form-control display"><label>'+val+'</label></span><span class="glyphicon glyphicon-remove" onclick="removeMail(this)"></span></li>');
			}
			
		})
	} */
	
}


function replySelectedMail(){
	if(selectedMailData.mail_read_state == '0'){
		if(!ratingChk()){
			return;
		};
		attach_match('view');
	}
	$('#mail_sub_common').val(selectedMailData.mail_subject);
	
	var forwardmail = '<p><label><b>-----------------------------------</b></label><br/>'+
					'<label><b>Date: </b></label>'+selectedMailData.mail_date +'<br/>'+
					'<label><b>From: </b></label>'+selectedMailData.mail_from +'<br/>'+
					'<label><b>To: </b></label>'+selectedMailData.mail_to +'<br/>';
					
	forwardmail += 	selectedMailData.hasOwnProperty('mail_cc') ? '<label><b>CC: </b></label>'+selectedMailData.mail_cc +'<br/>' : "";
	
	forwardmail +=	'<label><b>Subject: </b></label>'+selectedMailData.mail_subject +'<br/><br/>'+
					selectedMailData.mail_body.replace(/(\r\n|\n)/g, "<br/>")+'<br/><br/></p><hr>';
	
	
	if(typeof(UserEmailSettingsValue) != "undefined" && UserEmailSettingsValue.hasOwnProperty('signature')){
	CKEDITOR.instances['editor1_common'].setData("<br/><br/><br/><p>"+ UserEmailSettingsValue.signature.replace(/(\r\n|\n)/g, "<br/>")+'</p>'+ forwardmail);
	}
	
	supportMail("Reply Mail","Regular");
	if(selectedMailData.mail_type == "sent_item"){
		mail_to = selectedMailData.mail_to.split(',');
		$.each(mail_to, function(key, val){
			if($.trim(val) != ""){
				$('#mail_to_commonUl').append('<li><span class="form-control display"><label>'+$.trim(val)+'</label></span><span class="glyphicon glyphicon-remove" onclick="removeMail(this)"></span></li>');
			}
		}) 
	}else{
		mail_to = selectedMailData.mail_from.split(',');
		$.each(mail_to, function(key, val){
			if($.trim(val) != ""){
				$('#mail_to_commonUl').append('<li><span class="form-control display"><label>'+$.trim(val)+'</label></span><span class="glyphicon glyphicon-remove" onclick="removeMail(this)"></span></li>');
			}
		}) 
	}
	
	
}
</script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    	<div class="loader">
			<center><h1 id="loader_txt"></h1></center>
		</div>
        <?php require 'demo.php' ?>
        <?php if($_SESSION['active_module_name']=='sales' || $_SESSION['active_module_name']=='executive'){
        		require 'sales_sidenav.php'; 
        }else{
        		 require 'manager_sidenav.php'; 
        }?>
      
        <div class="content-wrapper body-content">
            <div class="col-lg-12 column">
				<div class="row header1">				
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
						<span class="info-icon toolTipStyle">
							<div>
								<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-html="true" data-toggle="tooltip" data-placement="bottom" title="1.Once you have set up your Email Settings in your profile, you can view all your emails here.<br/><br/> 
								2.Associated emails are ones that are associated with any lead, customer or opportunity that you have a stake in.<br/><br/>
								3.Unassociated emails are emails that are not associated with any lead, customer or opportunity. You can match these to any lead, opportunity or customer to put them in the Associated tab.<br/><br/>
								4.Conflict emails are those that have more than one lead, customer or opportunity associated with it. These can be selected and associated with any specific lead, customer or opportunity.<br/><br/> 
								5.Internal emails are from other users within your organization using L Connectt."/>
							</div>
						</span>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>User Mail</h2>
						<input type="hidden" id="e_currency1" />
					</div>
					<div  class="col-xs-2 col-sm-2 col-md-4 col-lg-4  aa">
						<div class="addBtns">
							<a  class="addExcel">
							 <img src="<?php echo base_url(); ?>images/new/Plus_Off.png" onmouseover="this.src='<?php echo base_url(); ?>images/new/Plus_ON.png'" onmouseout="this.src='<?php echo base_url(); ?>images/new/Plus_Off.png'" width="30px" height="30px" class="dropdown-toggle" data-toggle="dropdown"/>
							 <ul class="dropdown-menu rightdropdown pull-right" id="create_oppo"></ul>
							 </a>
							 
						</div>
						<div style="clear:both"></div>
					</div>
				</div>
				<div class="row" id="activity_tab_view">
					<ul class="nav nav-tabs">
						<li class="active" id="logdetails_cust">
                            <a onclick="groupMail('logdetails')" data-toggle="tab" href="#logdetails">Associated</a>
                        </li>
						<li >
                            <a onclick="groupMail('logdetails1')" data-toggle="tab" href="#logdetails1">Unassociated</a>
                        </li>
						<li >
                            <a onclick="groupMail('logdetails2')" data-toggle="tab" href="#logdetails2">Conflict Mails</a>
                        </li>
                        <li >
                            <a onclick="groupMail('logdetails3')" data-toggle="tab" href="#logdetails3">All Mails</a>
                        </li>
						<li >
                            <a onclick="groupMail('logdetails1')" data-toggle="tab" href="#logdetails4">Internal Mails</a>
                        </li>
						<li >
                            <a onclick="groupMail('logdetails5')" data-toggle="tab" href="#logdetails5">Sent Items</a>
                        </li>
					</ul>
					<div class="tab-content">
						<!--  Associated -->
						<div id="logdetails" class="tab-pane fade in active">
							<table class="table">
								<thead>
									<tr>
                                        <th width="5%">SL No</th>
										<th width="25%">From</th>
                                       <!--  <th>To</th> -->
										<th width="30%">Subject</th>
										<th width="15%">Date</th>
										<th width="15%">Association</th>
										<th width="5%" data-orderable="false"></th>
										<th width="5%" data-orderable="false"></th>
									</tr>
								</thead>
								<tbody id="tablebody1">

								</tbody>
							</table>
						</div>
						<!------------------------------------------------------>
						<!--  UNAssociated -->
						<div id="logdetails1" class="tab-pane fade" >
							<table class="table">
								<thead>
									<tr>
										<th width="5%">SL No</th>
										<th width="25%">From</th>
										<!--  <th>To</th> -->
										<th width="35%">Subject</th>
										<th width="25%">Date</th>
										<th width="5%" data-orderable="false"></th>
										<th width="5%" data-orderable="false"></th>
									</tr>
								</thead>
								<tbody id="tablebody2">

								</tbody>
							</table>
						</div>	
						<!--  Conflict -->
						<div id="logdetails2" class="tab-pane fade">
							<table class="table">
								<thead>
									<tr>
                                        <th width="5%">SL No</th>
										<th width="25%">From</th>
										<!--  <th>To</th> -->
										<th width="35%">Subject</th>
										<th width="25%">Date</th>
										<!--<th width="15%">Association</th>-->
										<th width="5%" data-orderable="false"></th>
										<th width="5%" data-orderable="false"></th>
									</tr>
								</thead>
								<tbody id="tablebody3">

								</tbody>
							</table>
						</div>
						<!--  All Mail -->
                        <div id="logdetails3" class="tab-pane fade">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="5%">SL No</th>
										<th width="25%">From</th>
										<!--  <th>To</th> -->
										<th width="25%">Subject</th>
										<th width="15%">Date</th>
										<th width="15%">Association</th>
										<th width="5%" data-orderable="false"></th>
										<th width="5%" data-orderable="false"></th>
										<th width="5%" data-orderable="false"></th>
                                    </tr>
                                </thead>
                                <tbody id="tablebody4">

                                </tbody>
                            </table>
                        </div>
						<!--  internal mail -->
						<div id="logdetails4" class="tab-pane fade">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="5%">SL No</th>
										<th width="30%">From</th>
										<!--  <th>To</th> -->
										<th width="35%">Subject</th>
										<th width="15%">Date</th>
										<!--<th width="15%">Association</th>-->
										<th width="15%" data-orderable="false"></th>
                                    </tr>
                                </thead>
                                <tbody id="tablebody5">

                                </tbody>
                            </table>
                        </div>
						<!--  Sent items -->
						<div id="logdetails5" class="tab-pane fade">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="5%">SL No</th>
										<th width="25%">To</th>
										<!--  <th>To</th> -->
										<th width="25%">Subject</th>
										<th width="15%">Date</th>
										<th width="15%">Association</th>
										<th width="5%" data-orderable="false"></th>
										<th width="5%" data-orderable="false"></th>
										<th width="5%" data-orderable="false"></th>
                                    </tr>
                                </thead>
                                <tbody id="tablebody6">

                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
            </div>
			
			<div id="match_type" class="modal fade" data-backdrop="static" data-keyboard="false">
                <input type="hidden" id='hidmsgid' name='hidmsgid' />
                <input type="hidden" id='hidemail' name='hidemail' />
                <input type="hidden" id='hidcontact' name='hidcontact' />
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<span class="close"  onclick="mail_match()">&times;</span>
							<h4 class="modal-title">Match</h4>
						</div>
						<div class="modal-body" id="matchdiv">
							<div class="row">								
								<div class="col-md-6">
									<input type="radio" name="match_name" id="number" selected /> <label for="number">Mobile</label>
								</div>
								<div class="col-md-6">
									<input type="radio" name="match_name" id="name" /> <label for="name">Name</label>
								</div>
							</div>	
							<div class="row">
								<div class="col-md-10">
									<input class="form-control mr-sm-2" type="text" id="search_value" placeholder="Search">
									<span class="error-alert"></span>
								</div>
								<div class="col-md-2">
									<button class="btn btn-secondary my-2 my-sm-0" onclick="search_data()" type="submit">Search</button>
								</div>
							</div>
						</div>
                        <div class="row">
                                <div class="col-md-12 radio_select"></div>
						</div>
					</div>
				</div>
			</div>
			<div id="attach_box" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
					<div class="modal-content modal-lg">
						<div class="modal-header">
							<span class="close"  onclick="attach_match('view')">&times;</span>
							<h4 class="modal-title">Mail Details</h4>
						</div>
						<div class="modal-body">
							<div class="row reply-forward none" style="position: absolute;right: 10px;z-index: 999;">
                                <div class="col-md-12 text-right">
									<a onclick="replySelectedMail()" href="javascript:void(0)" title="Reply Mail">
										<i class="fa fa-reply fa-2x btn"></i>
									</a>
									<a onclick="replyAllSelectedMail()" href="javascript:void(0)" title="Reply All">
										<i class="fa fa-reply-all fa-2x btn"></i>
									</a>
									<a onclick="forwardSelectedMail()" href="javascript:void(0)" title="Forward Mail">
										<i class="fa fa-arrow-circle-right fa-2x btn"></i>
									</a>
								</div>
							</div>
							<div class="row">
                                <div class="col-md-2">
									<label ><b>Date: </b></label>
								</div>
								<div class="col-md-10">
									<label id="sub_date"></label>
								</div>
							</div>
                            <div class="row">
                                <div class="col-md-2">
									<label><b>From: </b></label>
								</div>
								<div class="col-md-10">
									<p id="from_name"></p>
									<input type="hidden" id="from_name_hidden" />
								</div>
							</div>
                            <div class="row">
                                <div class="col-md-2">
									<label><b>To: </b></label>
								</div>
								<div class="col-md-10">
									<p id="to_name"></p>
								</div>
							</div>
							<div class="row">
                                <div class="col-md-2">
									<label><b>CC: </b></label>
								</div>
								<div class="col-md-10">
									<p id="cc_name"></p>
								</div>
							</div>
							<div class="row">
                                <div class="col-md-2">
									<label><b>BCC: </b></label>
								</div>
								<div class="col-md-10">
									<p id="bcc_name"></p>
								</div>
							</div>
                            <div class="row">
								<div class="col-md-2">
									<label><b>Subject: </b></label>
								</div>
								<div class="col-md-10">
									<p id="sub_name"></p>
								</div>

							</div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label><b>Message: </b></label>
                                </div>
                                <div class="col-md-10">
                                    <pre id="mail_body"></pre>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label><b>Attachment: </b></label>
                                </div>
                                <div class="col-md-10">
                                    <label id="attach_file"></label>
                                </div>
                            </div>
                            <div class="row ratingCommentSection">
								<hr>
								<div class="col-md-2">
                                    <label><b>Rating activity*</b></label>
                                </div>
                                <div class="col-md-6 text-center">
									<label class="rating_activity_add">
										<i class="glyphicon glyphicon-star rating1" onclick="rating(1)" style="color: rgb(181, 0, 10);"></i>
										<i class="glyphicon glyphicon-star rating2" onclick="rating(2)" style="color: rgb(181, 0, 10);"></i>
										<i class="glyphicon glyphicon-star rating3" onclick="rating(3)" style="color: rgb(181, 0, 10);"></i>
										<i class="glyphicon glyphicon-star rating4" onclick="rating(4)" style="color: rgb(210, 207, 207);"></i>
									</label>
									<br>
									<span class="error-alert rating_error"></span>
                                </div>
								<div class="col-md-4">
                                    <span class="rating_msg"></span>
                                </div>
								<div class="col-md-12">
                                    <textarea class="form-control" placeholder="Enter Remarks"></textarea>
									<span class="error-alert"></span>
                                </div>
							</div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label ></label>
                                </div>
                                <div class="col-md-10">
                                    <label id="matchbtn"></label>
                                </div>
                            </div>
						</div>
						<div class="modal-footer">
                            <!--<input type="button" class="btn my_btn" id="raise_ticket" onclick="raise_ticket(this)" value="Raise Ticket" /> Requirement Doc 10th Oct 11(Priority 8)-->
							<!-- removed on 15-11-2018-->
                            <input type="button" class="btn my_btn" id="create_lead" onclick="raise_ticket(this)" href="#leadinfoAdd" class="addPlus" data-toggle="modal"value="Create Lead" />
							
                            <!--<input type="button" class="btn my_btn" id="create_contact" onclick="raise_ticket(this)" value="Create Contact" />-->
							<input type="button" class="btn" onclick="attach_match('view')" value="Close">
						</div>
					</div>
				</div>
			</div>

			<div id="attachment_box" class="modal fade" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<span class="close"  onclick="attachment_match()">&times;</span>
							<h4 class="modal-title">Attached File</h4>
						</div>
						<div class="modal-body">
							<div class="row">
                                 <div class="col-md-12 file_attach no-padding"></div>
							</div>
						</div>
						<div class="modal-footer">
							<input type="button" class="btn" onclick="attachment_match()" value="Cancel">
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if($_SESSION['active_module_name'] == "manager"){?>
			<?php require 'manager-leadinfo-add-modal.php' ?> 
		<?php } else {?>
			<?php require 'lead_add_view.php' ?>
		<?php } ?>
        
	<?php require 'footer.php' ?>
    </body>
</html>
