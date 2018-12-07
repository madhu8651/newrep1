
<?php 
if( $_SESSION['Navigator']==1){
?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPquJYJq7KSiQPchdgioEVs-xOY4ERUdE&libraries=places"async defer></script>
<script>
  function render_map(long,latt,mapid,search_id){
        var mapOptions = {
        center: new google.maps.LatLng(12.93325692, 77.57465679),
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var infoWindow = new google.maps.InfoWindow();
    var latlngbounds = new google.maps.LatLngBounds();
    var map = new google.maps.Map(document.getElementById(mapid), mapOptions);

    var input= document.getElementById(search_id);
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
            document.getElementById(long).value = e.latLng.lat();
            document.getElementById(latt).value = e.latLng.lng();
    });
}
function search_location(long,latt,search_id,mapid){
    //alert(long+"--"+long)
    geocoder = new google.maps.Geocoder();
    var address = document.getElementById(search_id).value;
    geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                    document.getElementById(long).value = results[0].geometry.location.lat();
                    document.getElementById(latt).value = results[0].geometry.location.lng();
                    map_marker(long,latt,mapid);
            }else {
                    alert("Geocode was not successful for the following reason: " + status);
            }
    });
}
function map_marker(long,latt,mapid){
	var lat=document.getElementById(long).value;
	var log=document.getElementById(latt).value;

	var myCenter=new google.maps.LatLng(lat,log);
	var mapProp = {
	  center:myCenter,
	  zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	  };

	var map=new google.maps.Map(document.getElementById(mapid),mapProp);

	var marker=new google.maps.Marker({
	  position:myCenter,
	  });

	marker.setMap(map);
}
function get_coordinate(long,latt,mapid){
     if(long=="long"){
        $("#map2").show();
	$("#map1").show();
	$("#select_map").hide();  
     }
     if(long=="edit_long"){
        $("#edit_map2").show();
	$("#edit_map1").show();
	$("#edit_selectmap").hide();  
     }
	var lat=document.getElementById(long).value;
	var log=document.getElementById(latt).value;
	var myCenter=new google.maps.LatLng(lat,log);
	var mapProp = {
	  center:myCenter,
	  zoom:14,
	  mapTypeId:google.maps.MapTypeId.ROADMAP
	  };
	var map=new google.maps.Map(document.getElementById(mapid),mapProp);

	var marker=new google.maps.Marker({
	  position:myCenter,
	  });

	marker.setMap(map);
    }
</script>
 <?php } ?>
