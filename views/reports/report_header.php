<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/reports_style.css ">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>	

<!--<script src="<?php echo base_url()?>js/html2canvas.js"></script>
<script src="<?php echo base_url()?>js/jspdf.min.js"></script>-->
<style>
#chart_type h4{
	display: none;
}
#chart_type hr{
	display: none;
}
#chart_type:first-child{
	margin-top: 25px;
}
</style>
<script>
 function gimg(){
	 html2canvas($("#canvas"), {
		onrendered: function(canvas) {
		   var imgsrc = canvas.toDataURL('image/png',1.0);
			var pdf = new jsPDF('landscape');

		  pdf.addImage(imgsrc, 'JPEG', 10, 50, 250, 100); 
		  pdf.save("download.pdf");
		}
	 });
 }
function sort_list(data, type, key){
/* DATA is the object and TYPE is alphabatic/number sort */
	data.sort(function(a, b){
		if(type == "string"){
			var x = a[key].toLowerCase();
			var y = b[key].toLowerCase();
			if (x < y) {return -1;}
			if (x > y) {return 1;}
			return 0;
		}else if(type == "number"){
			return a[key] - b[key];
		}
	});
}  
</script>

<script type="text/javascript">
var reportData = {};
var arr = window.location.href.split("/");
var report = arr[arr.length-1];
var reportArray = report.split("_");
/* var chain = [],main_chain = [];	 */
function get_heirarchy(report){
	$("#report_name").closest(".row").css("margin-top","15px");
	var id = report;
	$.ajax({
		type: "POST",
		url:"<?php echo site_url('manager_standard_analytics/get_heirarchy/')?>"+id,
		dataType:'json',
		success: function(data){
			if(error_handler(data)){
				return;
			}
		   data=data.reverse()
		   breadcrumb(data)
		   /* main_chain = data;
		   chain=data; */
		},
		error:function(data){
			network_err_alert(data);
		}
	});
}

function breadcrumb(data){
	var breadcrumb = "";
	for(i=0; i<data.length; i++){
	   if(i == 0){
		   breadcrumb += '<li><a href="<?php echo site_url('manager_standard_analytics'); ?>">Standard report</a></li>';
	   }else if(i == (data.length-1)){
		   breadcrumb += '<li class="active last"><a href="#">'+data[i]+'</a></li>';
	   }else{
			breadcrumb += '<li ><a href="<?php echo site_url('manager_standard_analytics'); ?>">'+data[i]+'</a></li>';
	   }
	   if(i == data.length-1){
		   $(".pageHeader1 h2").text(data[i]);
		   $(".info-icon img").attr("title", data[i]);
	   }
	}
	$("#direction").html("").append(' <ol class="breadcrumb breadcrumb-arrow">'+breadcrumb+'</ol>')
}



/* ================================ */
function addRow(myList, selector , removeClo){
	var datacell="";
	if(removeClo.length > 0){
		removeClo.forEach(function(element) {
				delete myList[element]
		})
		$.each(myList, function(key, value){
			datacell += '<td style="text-align: left;">'+ value +'</td>';
		});
		/* datacell = printrow(myList) */
	}else{
		$.each(myList, function(key, value){
			datacell += '<td style="text-align: left;">'+ value +'</td>';
		});
	}
	$(selector +" tbody").append("<tr class='total-show'>"+datacell+"</tr>");
}

/* ---not using--

function printrow(myList, element){
	var datacell="";
	$.each(myList, function(key, value){
			datacell += '<td style="text-align: left;">'+ value +'</td>';
	});
	return datacell;
} */
/* ================================ */
function addArray(select, data, label){
	var sumval={};
	$.each(select, function(key, setected){
		setected['value'] = indivisual(key , setected['type'], data );
		sumval[key] = setected["value"];
	})
	
	var additional ={};
	$.each(data[0], function(key1, row){
		$.each(sumval, function(key, val){
			if(key == key1){
				additional[key1] = "<b>"+val+"</b>";
				return false; 
			}else if (key1 == label){
				additional[key1] = "<b>Total</b>";
				return false;
			}else{
				additional[key1] = "";
			}				
		})
	})
	console.log(additional)
	return additional;
}
/* =============================== */
function indivisual(key, type, data){
	var sum = 0; 
	$.each(data, function(key1, row){
		$.each(row, function(key2, column){
			if(key2 == key){
				if(type == 'float'){
					sum += parseFloat(column);
				}else{
					sum += parseInt(column);
				}
			}
		})
	})
	
	if(type == 'float'){
		return sum.toFixed(2);
	}else{
		return sum.toFixed(0);
	}
}
function export_report(report_name){
	$('#export_to').html("");	
	
	var btn_grp = 	'<button  class="btn btn-default pdf" title="PDF" onclick="gimg()">'+
						'<span class="fa fa-file-pdf-o"></span> PDF '+
					'</button>'+
					'<button  class="btn btn-default excel" title="CSV">'+
						'<span class="fa fa-file-excel-o"></span> CSV  '+
					'</button>'+
					'<button class="btn btn-default print" title="Print Report" onclick="window.print();">'+
						'<span class="fa fa-print"></span> Print Report '+
					'</button>'+
					'<button class="btn btn-default clock" onclick="scheduled_report_settings();" title="Schedule Report">'+
						'<span class="fa fa-clock-o"></span> Schedule '+
					'</button>';		
	$('#export_to').append(btn_grp);
	
	if(versiontype=='lite'){
		$('#report_name, #update_report_btn, #saveAs_report_btn, #save_report_btn').attr('disabled', 'disabled');
		$('#export_to .pdf, #export_to .excel, #export_to .print, #export_to .clock').attr('disabled', 'disabled');
		$('#export_to .pdf, #export_to .excel, #export_to .clock').addClass('none');
	}else if(versiontype=='standard'){
		$('#export_to .pdf, #export_to .excel, #export_to .clock').attr('disabled', 'disabled').addClass('none');
	}else if(versiontype=='premium'){
		$('#export_to .pdf, #export_to .excel, #export_to .clock').attr('disabled', 'disabled').addClass('none');
	}
	
};

function scheduled_report_settings(){
	$("#scheduled_report_settings").modal('show');
}

/* -------------------------------------------------------------------------- */
/* -----------------------------table to excel--------------------------------------------- */
function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("table tr");
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], 
		cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++){
			row.push(cols[j].innerText);
			
		} 
		
		
		if(row.length > 0){
			csv.push(row.join(","));  
		}
              
    }
    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
}
function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}	
</script>

<div class="row header1" style="height: 50px;padding-top: 10px;">				
	<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
		<span class="info-icon">
			<div>	
					<img src="<?php echo site_url(); ?>images/new/i_off.png" onmouseover="this.src='<?php echo site_url(); ?>images/new/i_ON.png'" onmouseout="this.src='<?php echo site_url(); ?>images/new/i_off.png'" alt="info" width="30" height="30"   data-toggle="tooltip" data-placement="right"/>

			</div>
		</span>
	</div>
	<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 pageHeader1">
		<h2></h2>	
	</div>
	<div  class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
		<div class="addBtns" >
			<a href="#" class="addPlus"  onclick="reportListDisplay()">
				<i  style="font-size: 35px;" class="fa fa-list fa-2" aria-hidden="true"></i>
			</a>
		</div>
		<div style="clear:both"></div>
	</div>
	<div style="clear:both"></div>
</div>
<div class="report-list-container none">
	<?php require __DIR__.'/../manager_standard_report_list.php' ?>
</div>
<div class="page-container">
	<div class="row" id="previousPage"></div>
	<div class="row breadcrumb-section">
		<center id="direction"></center>
		<center id="remarks_descriction"></center>
		<hr>
	</div>
</div>


<div id="scheduled_report_settings" class="modal fade" data-backdrop='static' data-keyboard="false">
	<div class="modal-dialog" >
		<div class="modal-content">
			<div class="modal-header modal-title">
				<span class="close" onclick="cancel_setting()">&times;</span>
				<h4 class="modal-title">Scheduled Report</h4>
			</div>
			<div class="modal-body">
				<div class="row" style="height: 450px;">
					<form>
						<div class="row">
							<div class="col-md-1">
							
							</div>
							<div class="col-md-2">
								<label class="report_label_style">Name: </label>
							</div>
							<div class="col-md-8">
								<input type="text" class="form-control" placeholder="Report Name" />
							</div>
							<div class="col-md-1">
							
							</div>
						</div>
						<div class="row">
							<div class="col-md-1">
							
							</div>
							<div class="col-md-10">
								<div class="row">
									<div class="col-md-1">									
									</div>
									<div class="col-md-4">
										<input type="checkbox" id="send_now"/> <label class="report_label_style" for="send_now">Send Now</label>									
									</div>
								</div>
							</div>
							<div class="col-md-1">
							
							</div>
						</div>
						<div class="row">
							<div class="col-md-1">
							
							</div>
							<div class="col-md-2">
								<label class="report_label_style">StartDate: </label>
							</div>
							<div class="col-md-8">
								<input class="form-control start_date" placeholder="DD-MM-YYYY" /><span class="error-alert"></span>
							</div>
							<div class="col-md-1">
							
							</div>
						</div>
						<div class="row">
							<div class="col-md-1">
							
							</div>
							<div class="col-md-2">
								<label class="report_label_style">Frequency: </label>
							</div>
							<div class="col-md-8">
								<select class="form-control" id="frequency_val" onclick="frequency_call()">
									<option value="">Choose</option>
									<option value="daily">Daily</option>
									<option value="weekly">Weekly</option>
									<option value="bi_weekly">Bi-weekly</option>
									<option value="monthly">Monthly</option>
									<option value="quarterly">Quarterly</option>
								</select>
							</div>
							<div class="col-md-1">
							
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
							
							</div>
							<div class="col-md-2 none" id="radio_day">
								<input type="radio" id="day_view" name="radio" /> <label for="day_view">Day</label>
							</div>
							<div class="col-md-2 none" id="radio_date">
								<input type="radio" id="date_view" name="radio" /> <label for="date_view">Date</label>
							</div>
							<div class="col-md-2 none" id="radio_event">
								<input type="radio" id="event_view" name="radio" /> <label for="event_view">Event</label>
							</div>
							<div class="col-md-1">
							
							</div>
						</div>
						<div class="row">
							<div class="col-md-1">
							
							</div>
							<div class="col-md-2">
								<label class="report_label_style">Time: </label>
							</div>
							<div class="col-md-8">
								<input class="form-control start_time" placeholder="HH:mm:ss" /><span class="error-alert"></span>
							</div>
							<div class="col-md-1">
							
							</div>
						</div>
						<div class="row">
							<div class="col-md-1">
							
							</div>
							<div class="col-md-2">
								<label class="report_label_style">Send To: </label>
							</div>
							<div class="col-md-8">
								<input class="form-control" placeholder="Email Address" /><span class="error-alert"></span>
							</div>
							<div class="col-md-1">
							
							</div>
						</div>
						<div class="row">
							<div class="col-md-1">
							
							</div>
							<div class="col-md-10">
								<div class="row">
									<div class="col-md-1">									
									</div>
									<div class="col-md-4">
										<input type="checkbox" id="mark_now"/> <label class="report_label_style" for="mark_now">Mark me on the cc</label>									
									</div>
								</div>
							</div>
							<div class="col-md-1">
							
							</div>
						</div>
						<div class="row">
							<div class="col-md-1">
							
							</div>
							<div class="col-md-2">
								<label class="report_label_style">Subject: </label>
							</div>
							<div class="col-md-8">
								<input class="form-control" placeholder="Enter subject here" /><span class="error-alert"></span>
							</div>
							<div class="col-md-1">
							
							</div>
						</div>
						<div class="row">
							<div class="col-md-1">
							
							</div>
							<div class="col-md-2">
								<label class="report_label_style">Message: </label>
							</div>
							<div class="col-md-8">
								<textarea class="form-control text_area" placeholder="Enter message here">
								
								</textarea>
							</div>
							<div class="col-md-1">
							
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<input type="button" class="btn btn-default" onclick="save_setting()" value="Save">
				<input type="button" class="btn btn-default" onclick="cancel_setting()" value="Cancel" >
			</div>
		</div>
	</div>
</div>