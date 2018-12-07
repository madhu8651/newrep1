<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'scriptfiles.php' ?>
<script src="/js/prefixfree.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPquJYJq7KSiQPchdgioEVs-xOY4ERUdE&libraries=places" async defer></script>
<style>
.filter_select{
margin-top: 16px;	
}
.filter_label{	
	margin-top: 25px;	
}
.lead_address{
	background-color:#c1c1c1;
	padding: 10px 12px;
	margin-bottom: 17px;
	margin-top: 6px;
}
.lead_opper{
	background-color:#c1c1c1;
	padding: 10px 12px;
	margin-bottom:0;
}
.lead_view{
	background-color:#c1c1c1;
	padding: 10px 12px;
}
#mapname,#edit_mapname{
	width: 100%;
	height: 150px;
	border: 1px;
	position: relative;
	overflow: hidden;
	margin-bottom: 12px;
}
.btn_log{
	margin-bottom: 5px;
}
.apport_label label{
	font-weight:bold!important;	
}

.loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('images/LConnectt Fevicon.png') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
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
</style>
<script type="text/javascript">
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
			var nameReg = new RegExp(/^[a-zA-Z0-9 $&:()#@_.,+%?-]*$/);
			var valid = nameReg.test(name);
			if (!valid) {
				return false;
			} else {
				return true;
			}
	}
function capitalizeFirstLetter(string) {
	    return string.charAt(0).toUpperCase() + string.slice(1);
}

$(window).load(function() {
    $(".loader").fadeOut("slow");
});
</script>
<script>
function validate_name(name) {
	var nameReg = new RegExp(/^[a-zA-Z]+$/);
	var valid = nameReg.test(name);	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function validate_website(website) {
	var nameReg = new RegExp( /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/);
	var valid = nameReg.test(website);	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}function validate_email(email) {
	var nameReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	var valid = nameReg.test(email);	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function validate_phone(phone) {
	var nameReg = new RegExp(/^(\+91-|\+91|0)?\d{10}$/);
	var valid = nameReg.test(phone);	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function validate_city(city) {
	var nameReg = new RegExp(/^[a-zA-Z]+$/);
	var valid = nameReg.test(city);	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function validate_zipcode(zipcode) {
	var nameReg = new RegExp(/^[0-9]{6}$/);
	var valid = nameReg.test(zipcode);	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function validate_contact(contact) {
	var nameReg = new RegExp(/^[a-zA-Z]+$/);
	var valid = nameReg.test(contact);	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function validate_designation(designation) {
	var nameReg = new RegExp(/^[a-zA-Z]+$/);
	var valid = nameReg.test(designation);	
	if (!valid) {
		return false;
	} else {
		return true;
	}
}
function error_handler(data){
        if(data.hasOwnProperty("errorCode")){
                    alert(data.errorCode+"  "+data.errorMsg);
                    return true;
        }
        return false;
}
/* function add_contact(){
	$('#leadinfoedit .modal-body .edit_CustContact').append("<div class='contact_type1'><div class='row' ><div class='col-md-12 lead_address'><center><b>Customer Contact Person Information</b></center></div></div><div class='row'><div class='col-md-2'><label>Contact Person*</label></div><div class='col-md-4'><input type='text' class='form-control edit_firstcontact' name='edit_firstcontact' ><span class='error-alert'></span></div><div class='col-md-2'><label>Designation</label></div><div class='col-md-4'><input type='text' class='form-control edit_disgnation' name='edit_disgnation' ><span class='error-alert'></span></div></div><div class='row'><div class='col-md-2'> <label>Mobile Number 1</label></div><div class='col-md-4'><input type='text' class='form-control edit_primmobile' ><span class='error-alert'></span></div><div class='col-md-2'><label>Mobile Number 2*</label></div><div class='col-md-4'><input type='text' class='form-control edit_primmobile2' name='edit_primmobile2' ><span class='error-alert'></span></div></div><div class='row'><div class='col-md-2'><label>Email 1</label></div><div class='col-md-4'><input type='text' class='form-control edit_primemail' ><span class='error-alert'></span></div><div class='col-md-2'><label>Email 2</label></div><div class='col-md-4'><input type='text' class='form-control edit_primemai2'><span class='error-alert'></span></div></div><div class='row'><div class='col-md-2'><label for='edit_displaypic'>Photo</label></div><div class='col-md-4'><label for='adminImageUploadE' class='custom-file-upload'><i class='fa fa-cloud-upload'></i> Image Upload</label><input type='file' class='form-controz' id='displaypic1' ><span class='error-alert'></span></div><div class='col-md-2'><label for='edit_contacttype'>Contact Type</label></div><div class='col-md-4'><select class='form-control' id='edit_contacttype'></select><span class='error-alert'></span></div></div></div>");
} */
function add_proDetails(){		
	$('#leadinfoedit .modal-body .edit_custPro').append('<div class="proDetail"><div class="row lead_address"><div class="col-md-12 col-sm-12 col-xs-12"><center><b>Product Purchase Information</b></center></div></div><div class="row"><div class="col-xs-2"><label for="edit_product1">Product</label></div><div class="col-xs-4"><select class="form-control edit_product1" name="edit_product" ></select><span class="error-alert"></span></div><div class="col-xs-2"><label for="pro_Value">Value</label></div><div class="col-xs-4"><input class="form-control pro_Value" /><span class="error-alert"></span></div></div><div class="row"><div class="col-xs-2"><label for="edit_Number">Number</label></div><div class="col-xs-4"><select class="form-control edit_Number" name="edit_Number" ></select><span class="error-alert"></span></div><div class="col-xs-2"><label for="pro_owner">Account Owner</label></div><div class="col-xs-4"><input class="form-control pro_owner" /><span class="error-alert"></span></div></div><div class="row"><div class="col-xs-2"><label for="start_date">Start Date</label></div><div class="col-xs-4"><input class="form-control start_date" placeholder="DD-MM-YYYY" disabled /><span class="error-alert"></span></div><div class="col-xs-2"><label for="end_date">End Date</label></div><div class="col-xs-4"><input class="form-control end_date" placeholder="DD-MM-YYYY" readonly /></div></div></div>');
}
$(document).on('focus',".end_date", function(){
    $(this).datepicker({
		changeMonth: true, 
		changeYear: true, 
		dateFormat: "dd/mm/yy",
		yearRange: "-90:+00"
	});
	
});
$(document).ready(function(){
	$("#end_date").datepicker({
		changeMonth: true, 
		changeYear: true, 
		dateFormat: "dd/mm/yy",
		yearRange: "-90:+00"
	});	
	$("#leadlog").click(function(){
	 $('#logdetails').show();
	 var view_leadid=$('#lead_id').val();
	 $.ajax({
			type: "POST",
			url: "lead_source.json",
			data : "id="+view_leadid,
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
                    return;
                }
			var row = "";
				for(i=0; i < data.length; i++ ){  
					$('#logtable').empty();
					var rowdata = JSON.stringify(data[i]);	
					if(data[i].rating == 1){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
					}else if(data[i].rating == 2){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
					}else if(data[i].rating == 3){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' ></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
					}else if(data[i].rating == 4){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].call_type + "</td><td id='ff'>" + "<i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true'></i><i class='fa fa-star' aria-hidden='true' ></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
					}else if(data[i].rating == ""){
						row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].Start_time +"</td><td>" + data[i].call_type + "</td><td>" + "<i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i><i class='fa fa-star' aria-hidden='true' style='color:lightgray'></i>" +"</td><td>" + data[i].duration +"</td><td>" + data[i].note +"</td>";
					}
				}     
			$('#logtable').append(row);  
			}
		});
	 
	});		
	$.ajax({
		type: "POST",
		url: "lead_source.json",
		dataType:'json',
		success: function(data) {
				if(error_handler(data)){
                    return;
                }
				var row = "";
				for(i=0; i < data.length; i++ ){						
				var rowdata = JSON.stringify(data[i]);						 
				row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].employeename +"</td><td>" + data[i].employeedesg+ "</td><td>" + data[i].leadphone +"</td><td>" + data[i].leademail +"</td><td>" + data[i].city +"</td><td>" + data[i].leadsource +"</td><td><a data-toggle='modal' href='#leadview' onclick='viewrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";			
			}					
			$('#tablebody').append(row);
		}
	});
	$('#select_map').hide();
	$('#logdetails').hide();
	$('#oop_details').hide();
	$("#map2").hide();
	$("#okmap").click(function(){
		$("#select_map").show();
		$("#map1").hide();
		$("#map2").hide();
		rendergmap();
	});
	$("#edit_okmap").click(function(){
		$("#edit_selectmap").show();
		$("#edit_map2").hide();
		$("#edit_map1").hide();
		edit_rendergmap();
	});
	$("#leadlog").click(function(){
		$('#logdetails').show();
	});
	$("#opp_log").click(function(){
		$('#oop_details').show();
	});
});
function edit_rendergmap() {
	var mapOptions = {
		center: new google.maps.LatLng(12.93325692, 77.57465679),
		zoom: 12,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var infoWindow = new google.maps.InfoWindow();
	var latlngbounds = new google.maps.LatLngBounds();
	var map = new google.maps.Map(document.getElementById("edit_mapname"), mapOptions);
	
	var input = document.getElementById('edit_search');
	var searchBox = new google.maps.places.SearchBox(input);
	var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.setTypes(['geocode']);
		
	map.addListener('bounds_changed', function() {
	  searchBox.setBounds(map.getBounds());
	});

	var markers = [];
	
	searchBox.addListener('places_changed', function() {
	  var places = searchBox.getPlaces();

	  if (places.length == 0) {
		return;
	  }

	  markers.forEach(function(marker) {
		marker.setMap(null);
	  });
	  markers = [];

	  var bounds = new google.maps.LatLngBounds();
	  places.forEach(function(place) {
		if (!place.geometry) {
		  alert("Returned place contains no geometry");
		  return;
		}
		var icon = {
		  url: place.icon,
		  size: new google.maps.Size(71, 71),
		  origin: new google.maps.Point(0, 0),
		  anchor: new google.maps.Point(17, 34),
		  scaledSize: new google.maps.Size(25, 25)
		};

		markers.push(new google.maps.Marker({
		  map: map,
		  icon: icon,
		  title: place.name,
		  position: place.geometry.location
		}));

		if (place.geometry.viewport) {
		  bounds.union(place.geometry.viewport);
		} else {
		  bounds.extend(place.geometry.location);
		}
	  });
	  map.fitBounds(bounds);
	   var place = autocomplete.getPlace();
		if (!place.geometry) {
			return;
		}

		var address = '';
		if (place.address_components) {
			address = [
				(place.address_components[0] && place.address_components[0].short_name || ''),
				(place.address_components[1] && place.address_components[1].short_name || ''),
				(place.address_components[2] && place.address_components[2].short_name || '')
				].join(' ');
		}
	});
	
	google.maps.event.addListener(map, 'click', function(e){
		
		var latlngstr = "lat=" + e.latLng.lat() + "&lng=" + e.latLng.lng();
		document.getElementById("edit_long").value = e.latLng.lat();
		document.getElementById("edit_latt").value = e.latLng.lng();
	});
}
function add_lead(){
	$.ajax({
		type: "POST",
		url: "",
		dataType:'json',
		success: function(data) {
			if(error_handler(data)){
                    return;
                }
		 var select = $("#product"), options = "<option value=''>select</option>";
		   select.empty();      
			for(var i=0;i<data.length; i++)
		   {
				options += "<option value='"+data[i].product_id+"'>"+ data[i].product_name +"</option>";              
		   }
		   select.append(options);
		}
	});
	$.ajax({
		type: "POST",
		url: "",
		dataType:'json',
		success: function(data) {
			if(error_handler(data)){
                    return;
                }
		 var select = $("#product"), options = "<option value=''>select</option>";
		   select.empty();      
			for(var i=0;i<data.length; i++)
		   {
				options += "<option value='"+data[i].product_id+"'>"+ data[i].product_name +"</option>";              
		   }
		   select.append(options);
		}
	});
	$.ajax({
		type: "POST",
		url: "",
		dataType:'json',
		success: function(data) {
			if(error_handler(data)){
                    return;
                }
		 var select = $("#contacttype"), options = "<option value=''>select</option>";
		   select.empty();      
			for(var i=0;i<data.length; i++)
		   {
				options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
		   }
		   select.append(options);
		}
	});
	$.ajax({
		type: "POST",
		url: "",
		dataType:'json',
		success: function(data) {
			if(error_handler(data)){
                    return;
                }
		 var select = $("#country"), options = "<option value=''>select</option>";
		   select.empty();      
			for(var i=0;i<data.length; i++)
		   {
				options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
		   }
		   select.append(options);
		}
	});
	$('#country').on('change',function(){
	   var id= this.value; 
		$.ajax({
			type: "POST",
			url: "",
			data : "id="+id,
			dataType:'json',
			success: function(data) {
				if(error_handler(data)){
                    return;
                }
			 var select = $("#state"), options = "<option value=''>select</option>";
			   select.empty();      

			   for(var i=0;i<data.length; i++)
			   {
					options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
			   }
			   select.append(options);
			}
		});
	});
 }
function rendergmap() {
	var mapOptions = {
		center: new google.maps.LatLng(12.93325692, 77.57465679),
		zoom: 12,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var infoWindow = new google.maps.InfoWindow();
	var latlngbounds = new google.maps.LatLngBounds();
	var map = new google.maps.Map(document.getElementById("mapname"), mapOptions);
	
	var input = document.getElementById('search');
	var searchBox = new google.maps.places.SearchBox(input);
	var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.setTypes(['geocode']);
		
	map.addListener('bounds_changed', function() {
	  searchBox.setBounds(map.getBounds());
	});

	var markers = [];
	
	searchBox.addListener('places_changed', function() {
	  var places = searchBox.getPlaces();

	  if (places.length == 0) {
		return;
	  }

	  markers.forEach(function(marker) {
		marker.setMap(null);
	  });
	  markers = [];

	  var bounds = new google.maps.LatLngBounds();
	  places.forEach(function(place) {
		if (!place.geometry) {
		  alert("Returned place contains no geometry");
		  return;
		}
		var icon = {
		  url: place.icon,
		  size: new google.maps.Size(71, 71),
		  origin: new google.maps.Point(0, 0),
		  anchor: new google.maps.Point(17, 34),
		  scaledSize: new google.maps.Size(25, 25)
		};

		markers.push(new google.maps.Marker({
		  map: map,
		  icon: icon,
		  title: place.name,
		  position: place.geometry.location
		}));

		if (place.geometry.viewport) {
		  bounds.union(place.geometry.viewport);
		}else {
		  bounds.extend(place.geometry.location);
		}
	  });
	  map.fitBounds(bounds);
	   var place = autocomplete.getPlace();
		if (!place.geometry) {
			return;
		}

		var address = '';
		if (place.address_components) {
			address = [
			(place.address_components[0] && place.address_components[0].short_name || ''),
			(place.address_components[1] && place.address_components[1].short_name || ''),
			(place.address_components[2] && place.address_components[2].short_name || '')
			].join(' ');
		}
	});	
	google.maps.event.addListener(map, 'click', function(e){		
		var latlngstr = "lat=" + e.latLng.lat() + "&lng=" + e.latLng.lng();
		document.getElementById("long").value = e.latLng.lat();
		document.getElementById("latt").value = e.latLng.lng();
    });
}
function cancelCust(){
	$('.modal').modal('hide');
	$('.modal .form-control[type=text],.modal textarea').val("");
	$('.modal select.form-control').val($('.modal select.form-control option:first').val());
	$(".contact_type1").remove(); 
	$(".proDetail").remove(); 
}
function add_cancel(){
	$('.modal').modal('hide');
	$('.form-control').val("");
	$('#select_map').hide();
	$('#map1').show();
}
function cancel1(){
	$('.modal').modal('hide');
	$('.form-control').val("");
	$("#logdetails").hide();
	$("#oop_details").hide();
}
function codeAddress() {
    geocoder = new google.maps.Geocoder();
    var address = document.getElementById("search").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			document.getElementById("long").value = results[0].geometry.location.lat();
			document.getElementById("latt").value = results[0].geometry.location.lng();
			map_marker();
		}else{
			alert("Geocode was not successful for the following reason: " + status);
		}
    });
}
function map_marker(){
	var lat=document.getElementById("long").value;
	var log=document.getElementById("latt").value;
	var myCenter=new google.maps.LatLng(lat,log);
	var mapProp = {
	  center:myCenter,
	  zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	};
	var map=new google.maps.Map(document.getElementById("mapname"),mapProp);
	var marker=new google.maps.Marker({
	  position:myCenter,
	});
	marker.setMap(map);
}
function map_marker1(){
	var lat=document.getElementById("edit_long").value;
	var log=document.getElementById("edit_latt").value;
	var myCenter=new google.maps.LatLng(lat,log);
	var mapProp = {
	  center:myCenter,
	  zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	};
	var map=new google.maps.Map(document.getElementById("edit_mapname"),mapProp);
	var marker=new google.maps.Marker({
	  position:myCenter,
	});
	marker.setMap(map);
}
function show_map(){
	$("#map2").show();
	$("#map1").show();
	$("#select_map").hide();

	var lat=document.getElementById("long").value;
	var log=document.getElementById("latt").value;

	var myCenter=new google.maps.LatLng(lat,log);
	var mapProp = {
	  center:myCenter,
	  zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	  };

	var map=new google.maps.Map(document.getElementById("maploc"),mapProp);

	var marker=new google.maps.Marker({
	  position:myCenter,
	  });
	marker.setMap(map);
}
function edit_showmap(){
$("#edit_map2").show();
$("#edit_map1").show();
$("#edit_selectmap").hide();

var lat=document.getElementById("edit_long").value;
var log=document.getElementById("edit_latt").value;

var myCenter=new google.maps.LatLng(lat,log);
var mapProp = {
  center:myCenter,
  zoom:14,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("edit_maploc"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,
  });

marker.setMap(map);
}
function editadd(){
var lat=document.getElementById("edit_long").value;
var log=document.getElementById("edit_latt").value;

var myCenter=new google.maps.LatLng(lat,log);
var mapProp = {
  center:myCenter,
  zoom:14,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("edit_maploc"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,
  });

marker.setMap(map);
}
/* function save_leadinfo(){
	if($.trim($("#leadname").val())==""){
		$("#leadname").closest("div").find("span").text("Lead name is required.");
		$("#leadname").focus();
		return;
    }else if(!validate_name($.trim($("#leadname").val()))){
		$("#leadname").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#leadname").closest("div").find("span").text("");
    }  
	if($.trim($("#leadweb").val())==""){
		$("#leadweb").closest("div").find("span").text("Lead name is required.");
		$("#leadweb").focus();
		return;
    }else if(!validate_website($.trim($("#leadweb").val()))){
		$("#leadweb").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#leadweb").closest("div").find("span").text("");
    }   
	if($.trim($("#leadmail").val())==""){
		$("#leadmail").closest("div").find("span").text("Email is required.");
		$("#leadmail").focus();
		return;
    }else if(!validate_email($.trim($("#leadmail").val()))){
		$("#leadmail").closest("div").find("span").text("Enter Only Chracters");
    } else{
		$("#leadmail").closest("div").find("span").text("");
    } 
	if($.trim($("#leadphone").val())==""){
		$("#leadphone").closest("div").find("span").text("Phone is required.");
		$("#leadphone").focus();
		return;
    }else if(!validate_phone($.trim($("#leadphone").val()))){
		$("#leadphone").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#leadphone").closest("div").find("span").text("");
    } 
	if($.trim($("#city").val())==""){
		$("#city").closest("div").find("span").text("City is required.");
		$("#city").focus();
		return;
    }else if(!validate_city($.trim($("#city").val()))){
		$("#city").closest("div").find("span").text("Enter Only Chracters");
    } else{
		$("#city").closest("div").find("span").text("");
    } 
    if($.trim($("#zipcode").val())==""){
		$("#zipcode").closest("div").find("span").text("Zipcode is required.");
		$("#zipcode").focus();
		return;
    }else if(!validate_zipcode($.trim($("#zipcode").val()))){
		$("#zipcode").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#zipcode").closest("div").find("span").text("");
    }           
    if($.trim($("#firstcontact").val())==""){
		$("#firstcontact").closest("div").find("span").text("Contact Name is required.");
		$("#firstcontact").focus();
		return;
    }else if(!validate_contact($.trim($("#firstcontact").val()))){
		$("#firstcontact").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#firstcontact").closest("div").find("span").text("");
    }             
    if($.trim($("#disgnation").val())==""){
		$("#disgnation").closest("div").find("span").text("Designation is required.");
		$("#disgnation").focus();
		return;
    }else if(!validate_designation($.trim($("#disgnation").val()))){
		$("#disgnation").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#disgnation").closest("div").find("span").text("");
    } 
	if($.trim($("#primmobile").val())==""){
		$("#primmobile").closest("div").find("span").text("Mobile Nummber is required.");
		$("#primmobile").focus();
		return;
    }else if(!validate_phone($("#primmobile").val())){
		$("#primmobile").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#primmobile").closest("div").find("span").text("");
    }  
    var addObj={};
    addObj.leadname = $.trim($("#leadname").val());
    addObj.leadwebsite = $.trim($("#leadweb").val());
    addObj.leademail = $.trim($("#leadmail").val());
    addObj.phone = $.trim($("#leadphone").val());
    addObj.product = $.trim($("#product").val());
    addObj.source = $.trim($("#leadsource").val());
    addObj.country = $.trim($("#country").val());
    addObj.state = $.trim($("#state").val());
    addObj.city = $.trim($("#city").val());
    addObj.zipcode = $.trim($("#zipcode").val());
    addObj.ofcaddress = $.trim($("#ofcadd").val());
    addObj.splcomments = $.trim($("#splcomments").val());
    addObj.contactname = $.trim($("#firstcontact").val());
    addObj.designation = $.trim($("#disgnation").val());
    addObj.mobile1 = $.trim($("#primmobile").val());
    addObj.mobile2 = $.trim($("#primmobile2").val());
    addObj.email1 = $.trim($("#primemail").val());
    addObj.email2 = $.trim($("#primemail2").val());
    addObj.contacttype = $.trim($("#contacttype").val());
    addObj.longitude = $.trim($("#long").val());
    addObj.lattitude = $.trim($("#latt").val());
    
    $.ajax({
        type : "POST",
        url : "lead_source.json",
        dataType : 'json',
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){
            $('.modal').modal('hide');
            $('.form-control').val("");
            $('#tablebody').empty();
            var row = "";
            for(i=0; i < data.length; i++ ){						
            var rowdata = JSON.stringify(data[i]);
           
             row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].employeename +"</td><td>" + data[i].employeedesg+ "</td><td>" + data[i].leadphone +"</td><td>" + data[i].leademail +"</td><td>" + data[i].city +"</td><td>" + data[i].leadsource +"</td><td><a data-toggle='modal' href='#leadview' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
            }					
              $('#tablebody').append(row);
        
}
});	


} */
function editAddress() {
    geocoder = new google.maps.Geocoder();
    var address = document.getElementById("edit_search").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                    document.getElementById("edit_long").value = results[0].geometry.location.lat();
                    document.getElementById("edit_latt").value = results[0].geometry.location.lng();
                    map_marker1();
            }else {
                    alert("Geocode was not successful for the following reason: " + status);
            }
    });
   }
function selrow(obj){
$("#leadinfoedit").modal('show');
$("#edit_customer").html(obj.leadname);
$("#edit_leadname").val(obj.leadname);
$("#edit_leadweb").val(obj.leadwebsite);
$("#edit_leadmail").val(obj.leademail);
$("#edit_leadphone").val(obj.leadphone);
$("#edit_city").val(obj.city);
$("#edit_zipcode").val(obj.zipcode);
$("#edit_ofcadd").val(obj.leadtaddress);
$("#edit_splcomments").val(obj.repremarks);
$("#edit_disgnation").val(obj.employeedesg);
$("#edit_primmobile").val(obj.employeephone1);
$("#edit_primmobile2").val(obj.employeephone2);
$("#edit_primemail").val(obj.employeeemail);
$("#edit_primemai2").val(obj.employeeemail2);
$("#edit_firstcontact").val(obj.employeename);
$("#edit_contacttype").val(obj.leadname);
$("#leadid").val(obj.leadid);
$("#employeeid").val(obj.employeeid);
$("#edit_long").val(obj.leadlng);
$("#edit_latt").val(obj.leadlat);
$("#pro_Value").val(obj.pro_Value);
$("#edit_Number").val(obj.edit_Number);
$('#edit_selectmap').hide();
$('#edit_map1').show();
 $.ajax({
        type: "POST",
        url: "lead_source.json",
        dataType:'json',
        success: function(data) {
        	if(error_handler(data)){
                    return;
                }
        var select = $("#edit_country"), options = "<option value=''>select</option>";
           select.empty();      
           for(var i=0;i<data.length; i++)
           {
                options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>"; 

           }
           select.append(options);
           $("#edit_country option[value='"+obj.leadcountry+"']").attr("selected",true);

        }
        });
        var id= obj.leadcountry;
                $.ajax({ 
                type : "POST",
                url : "",
                data : "id="+id,
                dataType : 'json',
                cache : false,
                success : function(data){
                	if(error_handler(data)){
                    return;
                }
                    var select = $("#edit_state"), options = "<option value=''>Select</option>";
                        select.empty();      
                        for(var i=0;i<data.length; i++)
                        {
                             options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
                        }
                        select.append(options);
                        $("#edit_state option[value='"+obj.state+"']").attr("selected",true);

                 }
            });
            $('#edit_country').on('change',function(){
               var id= this.value; 
                $.ajax({
                    type: "POST",
                    url: "",
                    data : "id="+id,
                    dataType:'json',
                    success: function(data) {
						if(error_handler(data)){
						return;
						}
                     var select = $("#edit_state"), options = "<option value=''>select</option>";
                       select.empty();      

                       for(var i=0;i<data.length; i++)
                       {
                            options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
                       }
                       select.append(options);

                    }
	        });
            });
            $.ajax({
            type: "POST",
            url: "",
            dataType:'json',
            success: function(data) {
            	if(error_handler(data)){
                    return;
                }
             var select = $("#edit_product"), options = "<option value=''>select</option>";
               select.empty();      
                for(var i=0;i<data.length; i++)
               {
                    options += "<option value='"+data[i].product_id+"'>"+ data[i].product_name +"</option>";              
               }
               select.append(options);
                $("#edit_product option[value='"+obj.productid+"']").attr("selected",true);


            }
        });
         $.ajax({
            type: "POST",
            url: "",
            dataType:'json',
            success: function(data) {
            	if(error_handler(data)){
                    return;
                }
             var select = $("#edit_contacttype"), options = "<option value=''>select</option>";
               select.empty();      
                for(var i=0;i<data.length; i++)
               {
                    options += "<option value='"+data[i].lookup_id+"'>"+ data[i].lookup_value +"</option>";              
               }
               select.append(options);
               $("#edit_contacttype option[value='"+obj.contact_type+"']").attr("selected",true);
            }
        });
 $("#edit_info").click(function(){
    if($.trim($("#edit_leadname").val())==""){
		$("#edit_leadname").closest("div").find("span").text("Lead name is required.");
		$("#edit_leadname").focus();
		return;
    }else if(!validate_name($.trim($("#edit_leadname").val()))){
		$("#edit_leadname").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#edit_leadname").closest("div").find("span").text("");
    }  
	if($.trim($("#edit_leadweb").val())==""){
		$("#edit_leadweb").closest("div").find("span").text("Lead name is required.");
		$("#edit_leadweb").focus();
		return;
    }else if(!validate_website($.trim($("#edit_leadweb").val()))){
		$("#edit_leadweb").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#edit_leadweb").closest("div").find("span").text("");
    }   
	if($.trim($("#edit_leadmail").val())==""){
		$("#edit_leadmail").closest("div").find("span").text("Email is required.");
		$("#edit_leadmail").focus();
		return;
    }else if(!validate_email($.trim($("#edit_leadmail").val()))){
		$("#edit_leadmail").closest("div").find("span").text("Enter Only Chracters");
    } else{
		$("#edit_leadmail").closest("div").find("span").text("");
    } 
	if($.trim($("#edit_leadphone").val())==""){
		$("#edit_leadphone").closest("div").find("span").text("Phone is required.");
		$("#edit_leadphone").focus();
		return;
    }else if(!validate_phone($.trim($("#edit_leadphone").val()))){
		$("#edit_leadphone").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#edit_leadphone").closest("div").find("span").text("");
    } 
	if($.trim($("#edit_city").val())==""){
		$("#edit_city").closest("div").find("span").text("City is required.");
		$("#edit_city").focus();
		return;
    }else if(!validate_city($.trim($("#edit_city").val()))){
		$("#edit_city").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#edit_city").closest("div").find("span").text("");
    } 
    if($.trim($("#edit_zipcode").val())==""){
		$("#edit_zipcode").closest("div").find("span").text("Zipcode is required.");
		$("#edit_zipcode").focus();
		return;
    }else if(!validate_zipcode($.trim($("#edit_zipcode").val()))){
		$("#edit_zipcode").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#edit_zipcode").closest("div").find("span").text("");
    }           
    if($.trim($("#edit_firstcontact").val())==""){
		$("#edit_firstcontact").closest("div").find("span").text("Contact Name is required.");
		$("#edit_firstcontact").focus();
		return;
    }else if(!validate_contact($.trim($("#edit_firstcontact").val()))){
		$("#edit_firstcontact").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#edit_firstcontact").closest("div").find("span").text("");
    }             
    if($.trim($("#edit_disgnation").val())==""){
		$("#edit_disgnation").closest("div").find("span").text("Designation is required.");
		$("#edit_disgnation").focus();
		return;
    }else if(!validate_designation($.trim($("#edit_disgnation").val()))){
		$("#edit_disgnation").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#edit_disgnation").closest("div").find("span").text("");
    } 
	if($.trim($("#edit_primmobile").val())==""){
		$("#edit_primmobile").closest("div").find("span").text("Mobile Nummber is required.");
		$("#edit_primmobile").focus();
		return;
    }else if(!validate_phone($("#edit_primmobile").val())){
		$("#edit_primmobile").closest("div").find("span").text("Enter Only Chracters");
    }else{
		$("#edit_primmobile").closest("div").find("span").text("");
    }
    var addObj={};
    var lead={};
    var leadContactPrimary={};
    var productPurchasePrimary={};
	var set1={};
	var setArray1=[];
	var set={};
	var setArray=[];
	//----------------Lead Info
    lead.leadname = $.trim($("#edit_leadname").val());
    lead.leadwebsite = $.trim($("#edit_leadweb").val());
    lead.leademail = $.trim($("#edit_leadmail").val());
    lead.phone = $.trim($("#edit_leadphone").val());
    lead.product = $.trim($("#edit_product").val());
    lead.source = $.trim($("#edikt_leadsource").val());
    lead.country = $.trim($("#edit_country").val());
    lead.state = $.trim($("#edit_state").val());
    lead.city = $.trim($("#edit_city").val());
    lead.zipcode = $.trim($("#edit_zipcode").val());
	//----------------Product purchase Info
	productPurchasePrimary.edit_product1 = $.trim($("#edit_product1").val());
    productPurchasePrimary.pro_owner = $.trim($("#pro_owner").val());
    productPurchasePrimary.start_date = $.trim($("#start_date").val());
    productPurchasePrimary.end_date = $.trim($("#end_date").val());
    productPurchasePrimary.pro_Value = $.trim($("#pro_Value").val());
    productPurchasePrimary.edit_Number = $.trim($("#edit_Number").val());
	//----------------Product purchase Secondary  Info
	$(".proDetail").each(function(){
		set1=({
			"edit_product1":$.trim($(this).find(".edit_product1").val()),
			"pro_owner":$.trim($(this).find(".pro_owner").val()),
			"start_date":$.trim($(this).find(".start_date").val()),
			"end_date":$.trim($(this).find(".end_date").val()),
			"pro_Value":$.trim($(this).find(".pro_Value").val()),
			"edit_Number":$.trim($(this).find(".edit_Number").val())
		});
		setArray1.push(set1);
		set1={};
	})
	//----------------Contact person Info
	leadContactPrimary.contactname = $.trim($("#edit_firstcontact").val());
    leadContactPrimary.designation = $.trim($("#edit_disgnation").val());
    leadContactPrimary.mobile1 = $.trim($("#edit_primmobile").val());
    leadContactPrimary.mobile2 = $.trim($("#edit_primmobile2").val());
    leadContactPrimary.email1 = $.trim($("#edit_primemail").val());
    leadContactPrimary.email2 = $.trim($("#edit_primemai2").val());
    leadContactPrimary.contacttype = $.trim($("#edit_contacttype").val());
	//----------------lead Contact person Secondary  Info
	/* $(".contact_type1").each(function(){
		set=({
			"contactname":$.trim($(this).find(".edit_firstcontact").val()),
			"designation":$.trim($(this).find(".edit_disgnation").val()),
			"mobile1":$.trim($(this).find(".edit_primmobile").val()),
			"mobile2":$.trim($(this).find(".edit_primmobile2").val()),
			"email1":$.trim($(this).find(".edit_primemail").val()),
			"email2":$.trim($(this).find(".edit_primemai2").val()),
			"contacttype":$.trim($(this).find(".edit_contacttype").val())
		});
		setArray.push(set);
		set={};
	}) */
	addObj=({
			"lead":lead, 
			"ofcaddress":$.trim($("#edit_ofcadd").val()),
			"splcomments":$.trim($("#edit_splcomments").val()), 
			"leadContactPrimary":leadContactPrimary, 
			"productPurchasePrimary":productPurchasePrimary, 
			//"leadContactSecondary":setArray,
			"productPurchaseSecondary":setArray1,
			"longitude":$.trim($("#edit_long").val()),
			"lattitude":$.trim($("#edit_latt").val()),
			"leadid":$.trim($("#leadid").val()),
			"employeeid":$.trim($("#employeeid").val())
		});
	
	console.log(addObj);

     /*$.ajax({
        type : "POST",
        url : "lead_source.json",
        dataType : 'json',
        data    : JSON.stringify(addObj),
        cache : false,
        success : function(data){
            $('.modal').modal('hide');
            $('.form-control').val("");
            $('#tablebody').empty();
           var row = "";
            for(i=0; i < data.length; i++ ){						
            var rowdata = JSON.stringify(data[i]);
           
             row += "<tr><td>" + (i+1) + "</td><td>" + data[i].leadname +"</td><td>" + data[i].employeename +"</td><td>" + data[i].employeedesg+ "</td><td>" + data[i].leadphone +"</td><td>" + data[i].leademail +"</td><td>" + data[i].city +"</td><td>" + data[i].leadsource +"</td><td><a data-toggle='modal' href='#leadview' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-eye-open'></span></a></td><td><a data-toggle='modal' href='#leadinfoedit' onclick='selrow("+rowdata+")'><span class='glyphicon glyphicon-pencil'></span></a></td></tr>";
            }					
              $('#tablebody').append(row);
        
}
});	 */
    
 });
}
function viewloc(){
var lat=document.getElementById("view_long").value;
var log=document.getElementById("view_latt").value;

var myCenter=new google.maps.LatLng(lat,log);
var mapProp = {
  center:myCenter,
  zoom:14,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("view_maploc"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,
  });

marker.setMap(map);
}
function viewrow(obj){
//$("#view_leadname").val(obj.leadname);
$('#leadname_label').text(obj.leadname);
$("#label_leadweb").html(obj.leadwebsite);
$("#label_leadmail").html(obj.leademail);
$("#label_leadphone").html(obj.leadphone);
$("#label_product").html(obj.product);
$("#label_leadsource").html(obj.leadsource);
$("#label_country").html(obj.country);
$("#label_state").html(obj.state);
$("#label_city").html(obj.city);
$("#label_zipcode").html(obj.zipcode);
$("#view_ofcadd").html(obj.leadtaddress);
$("#view_splcomments").html(obj.repremarks);
$("#view_disgnation").html(obj.employeedesg);
$("#view_primmobile").html(obj.employeephone1);
$("#view_primmobile2").html(obj.employeephone2);
$("#view_primemail").html(obj.employeeemail);
$("#view_primemai2").html(obj.employeeemail2);
$("#view_firstcontact").html(obj.employeename);
$("#view_contacttype").html(obj.leadname);
$("#view_long").html(obj.leadlng);
$("#view_latt").html(obj.leadlat);
$("#view_customer").html(obj.leadname);
viewloc();
}

function lead_history(){
	$("#leadview").modal("hide");
		$('#lead_hist').modal('show');
		var addobj={};
		addobj.customerid=$("#lead_id").val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('manager_customerController/getCustomerHistory'); ?>",
			data : JSON.stringify(addobj),
			dataType:'json',
			success: function(data) {
					if(error_handler(data)){
                    return;
                	}
					console.log(data);	
					var history=data;
					if(history) {		
					$('#tablebody1').empty();
					var mapping_ids = [];
					for (var i = 0; i < history.length; i++) {
						if (mapping_ids.indexOf(history[i].mapping_id) < 0) {
							mapping_ids.push(history[i].mapping_id);		
							var action = history[i].action;
							var from_name = history[i].from_user_name;
							var to_name = history[i].to_user_name;
							var lead_cust_name = history[i].lead_cust_name;				
							var remarks = history[i].remarks;
							var timestamp = history[i].timestamp;
							var rowhtml = '';
							if (action == 'created') {
								rowhtml += `<div class="created"> 
											<div><b><h3 style='display:inline;'>`+CapitalizeFirstLetter(action)+`</h3></b>
											by <u><b>` + from_name + `</u></b> for `+ lead_cust_name +`</div>
											<b>` ;
								
								rowhtml += `at <h5 style='display:inline;color:#777777'>` + timestamp + `</h5></div>`;
								alert(rowhtml)
							} 
							else if (action == 'accepted') {
								rowhtml += `<div class="created"> 
											<div><b><h3 style='display:inline;'>`+capitalizeFirstLetter(action)+`</h3></b>
											by <u><b>` + to_name + `</u></b> for `+ lead_cust_name +`</div>
											<b>` ;
								
								rowhtml += `at <h5 style='display:inline;color:#777777'>` + timestamp + `</h5></div>`;
								//alert(rowhtml)
							} 
							else if ((action == 'assigned') || (action == 'reassigned')){
								//get count of this mapping ID in array.
								assigned_to = 0;
								assigned_to_names = [];
								for(var c = 0; c < history.length; c++)	{
									if (history[c].mapping_id == history[i].mapping_id) {
										assigned_to++;
										assigned_to_names.push(history[c].to_user_name);
									}
								}
								
									if(assigned_to > 1)	{
										to_name = assigned_to + " users";
									}
									rowhtml = `<div class="assigned"> 
											<div><b><h3 style='display:inline;'>`+capitalizeFirstLetter(action)+`</h3></b>
											to <u><b>`+ to_name + `</u></b></div>`;
									/*if (remarks.length > 0) {
									rowhtml +="<div>Remarks - " + remarks +"</div>";
									}*/
									rowhtml += `at <h5 style='display:inline;color:#777777'>` + timestamp + `</h5></div>`;
									
							}
							else if (action == 'added remarks')	{
								rowhtml = `<div class="remarks"> 
											<div><b><h3 style='display:inline;'>`+capitalizeFirstLetter(action)+`</h3></b>
											by <u><b>` + from_name + `</u></b></div>`;
								/*if (remarks.length > 0) {
									rowhtml +="<div>Remarks - " + remarks +"</div>";
								}*/
								rowhtml += `at <h5 style='display:inline;color:#777777'>` + timestamp + `</h5></div>`;
							} 							
							row =   `<tr>
										<td>`+ rowhtml + `</td>
									</tr>`;

							$('#tablebody1').append(row);	
					}								
				}
			}		

		}		
	});	
}

</script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini"> 
	<div class="loader"></div>	
        <?php require 'demo.php' ?>
        <?php require 'sales_sidenav.php' ?>
        <div class="content-wrapper body-content">
            <div class="col-lg-12 column">
				<div class="row header1">				
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 aa">
						<span class="info-icon">
							<div >		
								<img src="/images/new/i_off.png" onmouseover="this.src='/images/new/i_ON.png'" onmouseout="this.src='/images/new/i_off.png'" alt="info" width="30" height="30"  data-toggle="tooltip" data-placement="right" title="Active Customer List"/>
							</div>
						</span>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 pageHeader1 aa">
						<h2>Active Customer</h2>	
					</div>
					<div  class="col-xs-4 col-sm-4 col-md-4 col-lg-4  aa">
						 <!--<div class="addBtns" onclick="add_lead();">
							<a href="#leadinfoAdd" class="addPlus" data-toggle="modal" onclick="compose()">
								<img src="/images/new/Plus_Off.png" onmouseover="this.src='/images/new/Plus_ON.png'" onmouseout="this.src='/images/new/Plus_Off.png'" width="30px" height="30px"/>
							</a>
						</div>-->
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="table-responsive">
					<table class="table" id="tableTeam">
						<thead>  
						<tr>	
							<th class="table_header">SL No</th>
							<th class="table_header">Name</th>
							<th class="table_header">Contact Person</th>
							<th class="table_header"> Designation</th>
							<th class="table_header">Phone</th>		
							<th class="table_header">Email</th>
							<th class="table_header">Location</th>	
							<th class="table_header">Lead Source</th>
							<th class="table_header"></th>
							<th class="table_header"></th>		
						</tr>
						</thead>  
						<tbody id="tablebody">
						</tbody>    
					</table>
				</div>
            </div>
            <!--<div id="leadinfoAdd" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="addpopup" class="form" action="#" method="post" >
                            <div class="modal-header">
                                <span class="close" onclick="add_cancel()">x</span>
                                <h4 class="modal-title"><b>Add Customer</b></h4>
                            </div>
                            <div class="modal-body">								
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="leadname">Customer Name*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="leadname" name="leadname" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="leadweb">Lead Website*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="leadweb" name="leadweb" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="leadmail">Customer Email*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"name="adminContactDept" class="form-control" id="leadmail" name="leadmail" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="leadphone">Customer Phone*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"class="form-control" id="leadphone" name="leadphone" >											
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="product">Product*</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <select class="form-control" id="product" name="product" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="leadsource">Lead Source</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="leadsource" name="leadsource" >
                                        </select>				
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2" >
                                        <label for="country">Country*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select  class="form-control" id="country" name="country" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="state">State*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select type="text" class="form-control" id="state" name="state" >
                                        </select>				
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="city">City*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="city" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 ">
                                        <label for="zipcode">Zipcode*</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <input type="text"  class="form-control" id="zipcode" name="zipcode" >

                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row lead_address" >
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
										<center><b>Office Address</b></center>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6  ">
										<center><b>Special Comments</b></center>
									</div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
                                        <textarea class="form-control" id="ofcadd"></textarea>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
                                        <textarea class="form-control" id="splcomments"></textarea>
                                    </div>
                                </div>
                                 <div class="row" id="map1">
                                <div class="row">
									<center>
										<button type="button" class="btn" id="okmap" >Google Map</button>
									</center>
                                 </div>
                                 </div>
                                <div class="row" id="map2" >
									<div class="row" id="maploc" >
									 </div>
                                </div>
                                <div class="row" id="select_map" >
									<div class="row" id="mapname" >
									 </div>
                                    <div class="row">
                                        <div class="col-md-1 ">
                                        <label for="search">Search</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <input type="text" class="form-control" onfocusout="codeAddress();" id="search" name="search" />
                                    </div>
									<div class="col-md-1">
										<label for="long">Longitude</label> 
                                    </div>
                                    <div class="col-md-2 ">
                                        <input type="text" class="form-control" id="long" name="long"/>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="latt">Latitude</label> 
                                    </div>
                                    <div class="col-md-2 ">
                                        <input type="text" class="form-control" id="latt" name="latt" />
                                    </div>
                                    
                                    <div class="col-md-1 ">
                                        <button type="button"  class="btn btn-default" id="proceed" style="margin-top:0px" onclick="show_map();">OK</button>
                                    </div>
                                    </div>
                                </div>
                              
                                <div class="row" >
                                    <div class="col-md-12 lead_address">
                                        <center><b>Customer Contact Information</b></center>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="firstcontact">Contact Person*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="firstcontact" name="firstcontact" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 ">
                                        <label for="disgnation">Designation</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="disgnation" name="disgnation" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="primmobile">Mobile Number 1</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="primmobile" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="primmobile2">Mobile Number 2*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="primmobile2" name="primmobile" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="primemail">Email 1</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="primemail" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="primemail2">Email 2</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="primemail2" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="displaypic">Photo</label> 
                                    </div>
                                    <div class="col-md-4">									
										<label for="adminImageUploadE" class="custom-file-upload">
											<i class="fa fa-cloud-upload"></i> Image Upload
										</label>
                                        <input type="file" class="form-control" id="displaypic" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="contacttype">contact Type</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="contacttype">
                                            
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                              </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" onclick="save_leadinfo()">Save</button>
                                <button  type="button" class="btn btn-default" onclick="add_cancel()" >Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>-->
              <div id="leadinfoedit" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="editpopup" class="form" action="#" method="post" >
                            <div class="modal-header">
                                <span class="close" onclick="cancelCust()">x</span>
                                <h4 class="modal-title"><b>Edit <span id="edit_customer"></span></b></h4>
                            </div>
                            <div class="modal-body">								
                                <div class="row">
                                    <div class="col-md-2 ">
                                        <label for="edit_leadname">Customer Name*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_leadname" name="edit_leadname" >
                                        <input type="hidden" class="form-control" id="leadid" name="leadid" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_leadweb">Lead Website</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="edit_leadweb" name="edit_leadweb" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_leadmail">Customer Email*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"name="adminContactDept" class="form-control" id="edit_leadmail" name="edit_leadmail" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_leadphone">Customer Phone*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"class="form-control" id="edit_leadphone" name="edit_leadphone" >											
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_product">Product*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="edit_product" name="edit_product" >
											
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_leadsource">Lead Source</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="edit_leadsource" name="edit_leadsource" >
                                        </select>				
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2" >
                                        <label for="edit_country">Country*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select  class="form-control" id="edit_country" name="edit_country" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_state">State*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select type="text" class="form-control" id="edit_state" name="edit_state" >
                                        </select>				
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_city">City*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_city" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_zipcode">Zipcode*</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="edit_zipcode" name="edit_zipcode" >

                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row lead_address">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<center><b>Product Purchase Information</b></center>
									</div>
                                </div>
								<div class="row">
                                    <div class="col-xs-2">
                                        <label for="">Product</label>
                                    </div>
                                    <div class="col-xs-4">
                                        <select class="form-control" id="edit_product1" name="edit_product" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
									<div class="col-xs-2">
                                        <label for="">Value</label>
                                    </div>
                                    <div class="col-xs-4">
                                        <input class="form-control" id="pro_Value"/>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
								<div class="row">
                                    <div class="col-xs-2">
                                        <label for="">Number</label>
                                    </div>
                                    <div class="col-xs-4">
                                        <select class="form-control" id="edit_Number" name="edit_product" >
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
									<div class="col-xs-2">
                                        <label for="">Opportunity Owner</label>
                                    </div>
                                    <div class="col-xs-4">
                                        <input class="form-control" id="pro_owner"/>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
								<div class="row">
                                    <div class="col-xs-2">
                                        <label for="">Start Date</label>
                                    </div>
                                    <div class="col-xs-4">
                                        <input class="form-control" id="start_date" placeholder="DD-MM-YYYY" disabled />
                                        <span class="error-alert"></span>
                                    </div>
									<div class="col-xs-2">
                                        <label for="">End Date</label>
                                    </div>
                                    <div class="col-xs-4">
                                        <input class="form-control" id="end_date" placeholder="DD-MM-YYYY" readonly />
                                    </div>
                                </div>									
								<div class="edit_custPro">
								
								</div>
								<div class="row">
									<div class="col-md-10">
										
									</div>
									<div class="col-md-2">
										<input type="button" class="btn" onclick="add_proDetails()" value="Add More Purchase"/>
									</div>
								</div>
								<div class="row lead_address">
									<div class="col-md-6 col-sm-6 col-xs-6">
										<center><b>Office Address</b></center>
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<center><b>Special Comments</b></center>
									</div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <textarea class="form-control" id="edit_ofcadd"></textarea>
										<span class="error-alert"></span>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <textarea class="form-control" id="edit_splcomments"></textarea>
										<span class="error-alert"></span>
                                    </div>
                                </div>
								 <div class="row" id="edit_map2" >
									<div class="row" id="edit_maploc" style="width:100% px;height:150px;border:1px;">
									 </div>
                                </div>
                                 <div class="row" id="edit_map1">
									<div class="row">
										<center>
											<button type="button" class="btn" id="edit_okmap" >Google Map</button>
										</center>
									 </div>
                                 </div>                               
                                <div class="row" id="edit_selectmap" >
                                <div class="row" id="edit_mapname" style="width:100% px;height:150px;border:1px;">
                                 </div>
                                    <div class="row">
                                        <div class="col-md-1">
                                        <label for="search">Search</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" onfocusout="editAddress();" id="edit_search" name="edit_search" />
                                    </div>
                                  <div class="col-md-1">
                                   <label for="edit_long">Longitude</label> 
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" id="edit_long" name="edit_long"/>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="edit_latt">Latitude</label> 
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" id="edit_latt" name="edit_latt" />
                                    </div>
                                    
                                    <div class="col-md-1">
                                        <button type="button"  class="btn btn-default" id="proceed" style="margin-top:0px" onclick="edit_showmap();">OK</button>
                                    </div>
                                    </div>
                                </div>
                              
                                <div class="row" >
                                    <div class="col-md-12 lead_address">
                                        <center><b>Customer Contact Person Information</b></center>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_firstcontact">Contact Person*</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <input type="text" class="form-control" id="edit_firstcontact" name="edit_firstcontact" >
                                        <input type="hidden"  id="employeeid" name="employeeid">
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_disgnation">Designation</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="edit_disgnation" name="edit_disgnation" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_primmobile">Mobile Number 1</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_primmobile" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_primmobile2">Mobile Number 2*</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <input type="text"  class="form-control" id="edit_primmobile2" name="edit_primmobile2" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="edit_primemail">Email 1</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="edit_primemail" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="edit_primemai2">Email 2</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  class="form-control" id="edit_primemai2" >
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 ">
                                        <label for="edit_displaypic">Photo</label> 
                                    </div>
                                    <div class="col-md-2">									
										<label for="adminImageUploadE" class="custom-file-upload">
											<i class="fa fa-cloud-upload"></i> Image Upload
										</label>
                                        <input type="file" class="form-control" id="displaypic" >
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 ">
                                        <div for="edit_contacttype"></div> 
                                    </div>
									<div class="col-md-2 ">
                                        <label for="edit_contacttype">Buyer Persona</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" id="edit_contacttype">
                                            
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
								<div class="row">
                                    <div class="col-md-2 ">
                                        <label for="edit_address">Address</label> 
                                    </div>
                                    <div class="col-md-4">									
										<textarea id="edit_address" class="form-control"></textarea>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 ">
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
								<!--<div class="edit_CustContact">
								
								</div>
								<div class="row">
									<div class="col-md-10">
										
									</div>
									<div class="col-md-2">
										<input type="button" class="btn" onclick="add_contact()" value="Add Contact"/>
									</div>
								</div>	-->							
                              </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" id="edit_info">Save</button>
                                <button  type="button" class="btn btn-default" onclick="cancelCust()" >Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="lead_hist" class="modal fade" data-backdrop="static" >
				<div class="modal-dialog modal-lg">
        				<div class="modal-content">
                        		<div class="modal-header">
                                		<span class="close" data-dismiss="modal">&times;</span>
                                 			<h3>Customer History</h3>
                        		</div>
                        		<div class="modal-body">
									<table class="table">
										<thead>
											<tr>
												<th class="table_header">Customer History</th>
											</tr>
										</thead>  
										<tbody id="tablebody1">	

										</tbody>    
									</table>
								</div>
                        <div class="modal-footer" id="modal_footer">
                                      
                           <input type="button" class="btn" data-dismiss="modal" value="Cancel" >                           
                        </div>
    		    </div>
		</div>
 </div>
            <div id="leadview" class="modal fade" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="viewpopup" class="form" action="#" method="post" >
                            <div class="modal-header">
                                <span class="close" onclick="cancel1()">x</span>
                                <h4 class="modal-title"><b>View <span id="view_customer"></span></b></h4>
                            </div>
                            <div class="modal-body">								
                                <div class="row">
                                    <div class="col-md-2 apport_label">
                                        <label for="view_leadname">Customer Name</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <label id="leadname_label"></label>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 apport_label">
                                        <label for="view_leadweb">Lead Website</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <label id="label_leadweb"></label> 
                                       <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2  apport_label">
                                        <label for="view_leadmail">Customer Email</label> 
                                    </div>
                                    <div class="col-md-4 ">
                                        <label id="label_leadmail"></label> 
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 apport_label">
                                        <label for="view_leadphone">Customer Phone</label> 
                                    </div>
                                    <div class="col-md-4">
                                      <label id="label_leadphone"> </label> 

                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 apport_label">
                                        <label for="view_product">Product</label> 
                                    </div>
                                    <div class="col-md-4">
                                    <label id="label_product"></label>                                       
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 apport_label">
                                        <label for="view_leadsource">Lead Source</label> 
                                    </div>
                                    <div class="col-md-4">
                               <label id="label_leadsource"></label>                                         				
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 apport_label" >
                                        <label for="view_country">Country</label> 
                                    </div>
                                    <div class="col-md-4">
                                       <label for="label_country"></label> 
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 apport_label">
                                        <label for="view_state">State</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <label for="label_state"></label> 			
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 apport_label">
                                        <label for="view_city">City</label> 
                                    </div>
                                    <div class="col-md-4">
                                        <label id="label_city"></label> 
                                    </div>
                                    <div class="col-md-2 apport_label">
                                        <label for="view_zipcode">Zipcode</label> 
                                    </div>
                                    <div class="col-md-4">
                                         <label id="label_zipcode"></label> 
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
								<div class="row lead_address">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<center><b>Product Purchase Information</b></center>
									</div>
                                </div>
								<div class="row">
                                    <div class="col-xs-2 apport_label">
                                        <label for="">Product</label>
                                    </div>
                                    <div class="col-xs-4">
										 <label id="edit_product"></label>
                                        </select>
                                        <span class="error-alert"></span>
                                    </div>
									<div class="col-xs-2 apport_label">
                                        <label for="">Account Owner</label>
                                    </div>
                                    <div class="col-xs-4">
										 <label id="edit_product_Own"></label>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
								<div class="row">
                                    <div class="col-xs-2 apport_label">
                                        <label for="">Start Date</label>
                                    </div>
                                    <div class="col-xs-4">
										 <label id="start_date1"></label>
                                        <span class="error-alert"></span>
                                    </div>
									<div class="col-xs-2 apport_label">
                                        <label for="">End Date</label>
                                    </div>
                                    <div class="col-xs-4">
										 <label id="end_date1"></label>
                                    </div>
                                </div>
                                <div class="row lead_address" >
									<div class="col-md-6 col-sm-6 col-xs-6">
										<center><b>Office Address</b></center>
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<center><b>Special Comments</b></center>
									</div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">										
										 <label id="view_ofcadd"></label>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">									
										 <label id="view_splcomments"></label>
                                    </div>
                                </div>
                                <input type="hidden" id="view_latt">
                                 <input type="hidden" id="view_long">
                                <div class="row" id="view_map2" >
									<div class="row" id="view_maploc" style="width:100% px;height:150px;border:1px;">
									 </div>
                                </div>
							   <div class="row" >
									<div class="col-md-12 lead_address">
										<center><b>Customer Contact Person Information</b></center>
									</div>
								</div>
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_firstcontact">Contact Person</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
										<label id="view_firstcontact"></label> 
                                        <input type="hidden"  id="employeeid" name="employeeid">
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_disgnation">Designation</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
										<label id="view_disgnation"></label> 
                                        <span class="error-alert"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_primmobile">Mobile Number 1</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
										<label id="view_primmobile"></label>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_primmobile2">Mobile Number 2</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
										<label id="view_primmobile2"></label>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_primemail">Email 1</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
										<label id="view_primemail"></label>
                                        <span class="error-alert"></span>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_primemai2">Email 2</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
										<label id="view_primemai2"></label>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_displaypic">Photo</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 apport_label">
                                        <label for="view_contacttype">Buyier Persona</label> 
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
										<label id="view_contacttype"></label>
                                        <span class="error-alert"></span>
                                    </div>
                                </div>
                                <div class="row" id="view_log">
                                <div class="row btn_log">
									<center>
										<button type="button" class="btn" id="leadlog" >View Customer Log</button>
									</center>
                                </div>
                                </div>
								<div class="row" id="logdetails">
								<div class="col-md-12 lead_view">
								<div class="col-md-12 col-sm-12 col-xs-12">
								<center><b>Customer Log</b></center>
								</div>
								</div>
								<table class="table" id="tablelog">
									<thead>  
									<tr>	
									   <th class="table_header">SL No</th>
										<th class="table_header">Customer Name</th>
										<th class="table_header">Date-time</th>
										<th class="table_header">Activity</th>
										<th class="table_header">Ratings</th>		
										<th class="table_header">Duration</th>
										<th class="table_header">Remarks</th>	
									</tr>
									</thead>  
									<tbody id="logtable">
									</tbody>    
								</table>
								
                          </div>
							<div class="row" id="opportunity">
								<div class="row btn_log">
									<center>
									<button type="button" class="btn" id="opp_log" >View Opportunities</button>
									</center>
								</div>
							</div> 
							<div class="row" id="oop_details">
									<div class="col-md-12" style="background-color:#c1c1c1;padding: 10px 12px;">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<center><b>Opportunity List</b></center>
										</div>
									</div>
								<table class="table" id="tableopp">
										<thead>  
											<tr>
												<th class="table_header"></th>
												<th class="table_header">SL No</th>
												<th class="table_header">Name</th>
												<th class="table_header">Product</th>	
												<th class="table_header">Sales Stage</th>	
												<th class="table_header">Expected Close Date</th>
												<th class="table_header">Stage Owner</th>
											</tr>
										</thead>  
									<tbody id="opp_table">
									
									</tbody>    
								</table>
								<div class="row btn_log">
									<center>
										<button type="button" class="btn" id="new_opp" >Add New Opportunity</button>
									</center>
                                </div>
								</div>
                          <div class="modal-footer">
							<button  type="button" class="btn btn-default" onclick="cancel1()" >Cancel</button>
                          </div>
                        </div>
                      </form>
                    </div>
                </div>
            </div>             
        </div>
        <?php require 'footer.php' ?>

    </body>
</html>
