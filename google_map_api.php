<!DOCTYPE html>
<html>
  <head>
    <title>Asynchronous Loading</title>
    <meta name="Google map" content="initial-scale=1.0, user-scalable=no">

    <script type=text/javascript src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>
    <meta charset="utf-8">
    
    <?php
       
        ?>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
    <script>
function initialize() {
     var lat = '';
            var lng = '';
            var zip = document.getElementById('zipcode').value;
             var geocoder = new google.maps.Geocoder();
               geocoder.geocode( { 'address': "52402"}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                       
                       lat = results[0].geometry.location.lat();
                        alert (lat);
                       lng = results[0].geometry.location.lng();
                       alert(lng);
                       var mapOptions = {
                                    zoom: 9,
                                    center: new google.maps.LatLng(lat,lng)
                      };
                      
                     var map = new google.maps.Map(document.getElementById('map-canvas'),
                     mapOptions);
                      
                       
                     map.setCenter(results[0].geometry.location);
                     var center = map.getCenter();
                     google.maps.event.trigger(map, 'resize');
                     map.setCenter(center);
                     var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location
                     });
                    
                    } else {
                      alert("Geocode was not successful for the following reason: " + status);
                    }
                });
                
}

function loadScript() {
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' +
      'callback=initialize';
  document.body.appendChild(script);
}

window.onload = loadScript;

    </script>
  </head>
  <body style="width:100%;">
      
   
      <div id="map-canvas" style="width:100%; margin-left:0px;" >
          google map
          
      </div>
  </body>
</html>