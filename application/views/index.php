<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
    <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBn4uJFVXBLYeLqVWm7upq8LFM9FFgv-XE&sensor=true">
    </script>
    <script type="text/javascript">
      function initialize() {			
		var myLatLng = new google.maps.LatLng(22.7299811, 120.3297569);
		var mapOptions = {
		  zoom: 13,
		  center: myLatLng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
		var image = new google.maps.MarkerImage(
			'http://maps.google.com/mapfiles/ms/micons/blue-dot.png',
			new google.maps.Size(32, 32),	// size
			new google.maps.Point(0,0),	// origin
			new google.maps.Point(16, 32)	// anchor
		);
		var pinShadow = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
			new google.maps.Size(40, 37),
			new google.maps.Point(0, 0),
			new google.maps.Point(12, 35));
		<?php 
			if($wifis->num_rows() > 0) {
				foreach($wifis->result() as $row) {
		?>
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(<?php echo $row->gps_lat;?>, <?php echo $row->gps_lon;?>),
			icon: image,
			shadow: pinShadow,
			title:"<?php echo $row->SSID;?>"
		});
		marker.setMap(map);
		<?php
				}
			}
		?>	
		
		// var populationOptions = {
		  // strokeColor: "#00a0e0",
		  // strokeOpacity: 0.8,
		  // strokeWeight: 2,
		  // fillColor: "#00a0e0",
		  // fillOpacity: 0.2,
		  // map: map,
		  // center: myLatLng,
		  // radius: 5 * 1000
		// };
		// cityCircle = new google.maps.Circle(populationOptions);
      }
    </script>
  </head>
  <body onload="initialize()">
    <div id="map_canvas" style="width:100%; height:100%"></div>
  </body>
</html>