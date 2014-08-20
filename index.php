<?
  include "waypoints.php";
  include "config.php";
  // Get a list of stored waypoints for this user
  $userid = 1;
  // These are all settings from config.php
  // Blah
  $database = mysql_connect($DBhost, $DBuser, $DBpasswd);
  mysql_select_db($DBname,$database);

  $waypoints = WayPoint::listAll($userid);
?>
<html>
<head>
	<title>javascript-mobile-desktop-geolocation With No Simulation with Google Maps</title>
	<meta name = "viewport" content = "width = device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">		
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<link rel="stylesheet" href="css/mystyles.css" />
		<script src="js/geoPosition.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDWoI9L4EqJ5jijssRX6ZkLI7Fgpo-zTzo"></script>

		<script>
var waypoints = [];
var infos = [];
var marker;
var infowindow;
var latitude;
var longitude;


		function initializeMap()
		{
		    var myOptions = {
			      zoom: 4,
			      mapTypeControl: true,
			      mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
			      navigationControl: true,
			      navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
			      mapTypeId: google.maps.MapTypeId.ROADMAP      
			    }	
			map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
			map.setZoom(18);
		}
		function initialize()
		{
			if(geoPosition.init())
			{
				document.getElementById('current').innerHTML="Receiving...";
				geoPosition.getCurrentPosition(showPosition,function(){document.getElementById('current').innerHTML="Couldn't get location"},{enableHighAccuracy:true});
			}
			else
			{
				document.getElementById('current').innerHTML="Functionality not available";
			}
		}

		function showPosition(p)
		{
			 latitude = parseFloat( p.coords.latitude);
			 longitude = parseFloat( p.coords.longitude );

			document.getElementById('current').innerHTML="latitude=" + latitude + " longitude=" + longitude;
			var pos=new google.maps.LatLng( latitude , longitude);
			map.setCenter(pos);

			if(marker){ 
				marker.setPositon(pos);
			} else{  
			  infowindow = new google.maps.InfoWindow({
			    content: "You are Here!"
			  });

			  marker = new google.maps.Marker({
			    position: pos,
			    map: map,
			    icon: {
      				path: google.maps.SymbolPath.CIRCLE,
      				scale: 4,
				strokeColor:'blue'	
    			    },
			    title:"You are here"
			  });

			  google.maps.event.addListener(marker, 'click', function() {
			  infowindow.open(map,marker);
			  });
		 	}	
		}
function addMarker(index,name,lat,lng) {
 myLatlng = new google.maps.LatLng(lat,lng);
 infos[index] = new google.maps.InfoWindow({ content: name });
 waypoints[index] = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: 'Waypoint: '+name
 });
google.maps.event.addListener(waypoints[index], 'click', function() {
  infos[index].open(map,waypoints[index]);
});
}
function removeMarker(index) {
 waypoints[index].setMap(null);
}
function update_position() {
  geoPosition.getCurrentPosition(showPosition,function(){document.getElementById('current').innerHTML="Couldn't get location"},{enableHighAccuracy:true});
}


		</script>
	</head>
	<body onload="initializeMap();initialize()">
<div id=home data-theme="b" data-role="page" data-title="Jim's GPS">
<? show_header("GPS");?>
        <div data-role="content">
		<div id="map_canvas" style="width:100%; height:250px"></div>
</div>
<input type="button" value=" Update Location " onclick="javascript:update_position()">
        <div data-role="collapsible"><h2>Waypoint List</h2>
                <div id="wayPointList" data-role="collapsible-set">
<? foreach($waypoints as $w) { ?>
<label><input type=checkbox class=coordinate value="<?=$w['id'];?>,<?=str_replace(array("'",","),"",$w['name']);?>,<?=$w['latitude'];?>,<?=$w['longitude'];?>"> <?=$w['name'];?></label>
<? } ?>
</div>
</div>
<div id="current">Initializing...</div>
<script>
$('.coordinate').click(function() {
        var args = $(this).val().split(",");
        if($(this).is(':checked')) {
          addMarker(args[0],args[1],args[2],args[3]);
        } else {
                removeMarker(args[0]);
        }
});
$('#newSave').click(function() { 
  $('#wayPointList').append( "<div class=\"ui-checkbox\"><label><input type=checkbox class=coordinate value=\"2,"+$('#newName').val()+","+latitude+","+longitude+"\"> "+$('#newName').val()+"</label></div>");
   $('#newName').html("");
   $('#newForm').hide();	
});
</script>
	</body>
</html>
<?
function show_header($title) { ?>
                        <div data-id="main_header" data-role="header">
                        <h1><?=$title;?></h1>
                        <a href="#home" data-icon=home>Home</a>
                                                <a href="#info" data-icon=info data-rel="dialog">Info</a>

                </div>

<?
}
?>

