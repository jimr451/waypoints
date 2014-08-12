/* Javascript file */
var current_lat = 0;
var current_lng = 0;
var last_lat = 0;
var last_lng = 0;
var bearing = 0;

function radians(n) {
  return n * (Math.PI / 180);
}
function degrees(n) {
  return n * (180 / Math.PI);
}

function getBearing(startLat,startLong,endLat,endLong){
  startLat = radians(startLat);
  startLong = radians(startLong);
  endLat = radians(endLat);
  endLong = radians(endLong);

  var dLong = endLong - startLong;

  var dPhi = Math.log(Math.tan(endLat/2.0+Math.PI/4.0)/Math.tan(startLat/2.0+Math.PI/4.0));
  if (Math.abs(dLong) > Math.PI){
    if (dLong > 0.0)
       dLong = -(2.0 * Math.PI - dLong);
    else
       dLong = (2.0 * Math.PI + dLong);
  }

  return (degrees(Math.atan2(dLong, dPhi)) + 360.0) % 360.0;
}

function updatelocation() {
   // One-shot position request.
     navigator.geolocation.getCurrentPosition(callback, eHandler,{maximumAge: Infinity,timeout:3000,enableHighAccuracy:true});
/*
	geoPosition.getCurrentPosition(callback,function(){document.getElementById('current').innerHTML="Couldn't get location"},{enableHighAccuracy:true});
*/

   }
function eHandler(error) { 
  switch(error.code) {
    case error.PERMISSION_DENIED:
      alert("User denied the request for Geolocation.");
      break;
    case error.POSITION_UNAVAILABLE:
      alert("Location information is unavailable.");
      break;
    case error.TIMEOUT:
      alert("The request to get user location timed out.");
      break;
    case error.UNKNOWN_ERROR:
      alert("An unknown error occurred.");
      break;
    }
} 
   function callback(position) {
	
	last_lat = current_lat;
	last_lng = current_lng;
	current_lat = parseFloat( position.coords.latitude);
	current_lng = parseFloat( position.coords.longitude);
	/* Randomize movements for testing */
	current_lat+=Math.random()/1000;
	current_lng+=Math.random()/1000;
   }

function getDistanceFromLatLon(lat1,lon1,lat2,lon2) {
  var R = 6371; // Radius of the earth in km
  var F = 3280.8399 // feet in a Km
  var dLat = deg2rad(lat2-lat1);  // deg2rad below
  var dLon = deg2rad(lon2-lon1); 
  var a = 
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
    Math.sin(dLon/2) * Math.sin(dLon/2)
    ; 
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
  var d = (R * c) * F; // Distance in feet
  
  return d;
}

function deg2rad(deg) {
  return deg * (Math.PI/180)
}
