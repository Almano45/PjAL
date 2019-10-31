<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<style type="text/css">
	  html { height: 100% }
	  body { height: 100%; margin: 0px; padding: 0px }
	  #map_canvas { height: 100% ; width:100%;}
	</style>
	
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
		
 	<!-- essai avec la cle google maps mais marche po
	  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-TsKOQIlzj6cdwMB8RfLsRdR51ojeFKM&callback=initMap"
  		type="text/javascript"></script>
	-->
	<script type="text/javascript">
		var previousPosition = null;
	
		function initialize() {
			map = new google.maps.Map(document.getElementById("map_canvas"), {
			      zoom: 19,
			      center: new google.maps.LatLng(48.858565, 2.347198),
			      mapTypeId: google.maps.MapTypeId.ROADMAP
			    });		
		}
		  
		if (navigator.geolocation)
			var watchId = navigator.geolocation.watchPosition(successCallback, null, {enableHighAccuracy:true});
		else
			alert("Votre navigateur ne prend pas en compte la géolocalisation HTML5");
			
		function successCallback(position){
			map.panTo(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(position.coords.latitude, position.coords.longitude), 
				map: map
			});  
			if (previousPosition){
				var newLineCoordinates = [
					 new google.maps.LatLng(previousPosition.coords.latitude, previousPosition.coords.longitude),
					 new google.maps.LatLng(position.coords.latitude, position.coords.longitude)];
				
				var newLine = new google.maps.Polyline({
					path: newLineCoordinates,	       
					strokeColor: "#FF0000",
					strokeOpacity: 1.0,
					strokeWeight: 2
				});
				newLine.setMap(map);
			}
			previousPosition = position;
		};		
	</script>
</head>
<body onload="initialize()">
	<div id="map_canvas"></div>

<?php 

$ip = '62.39.220.112'; // Recuperation de l'IP du visiteur
$apiKey = 'eef921436d8a69';

$apiQuery = 'http://ipinfo.io/%s/json?token=%s';

$apiResult = file_get_contents(sprintf($apiQuery, $ip, $apiKey));
$jsonResult = json_decode($apiResult);

// Affichage
echo $ip;
?>

<h1>Détails Adresse IP <?= $ip ?></h1>
<ul>
    <li><strong>Nom d'hôte : </strong> <?= $jsonResult->hostname ?></li>
    <li><strong>Ville : </strong> <?= $jsonResult->city ?></li>
    <li><strong>Région : </strong> <?= $jsonResult->region ?></li>
    <li><strong>Pays : </strong> <?= $jsonResult->country ?></li>
    <li>
        <strong>Coordonnées GPS : </strong> 
        <ul>
            <li><strong>Latitude : </strong> <?= explode(',', $jsonResult->loc)[0] ?></li>
            <li><strong>Longitude : </strong> <?= explode(',', $jsonResult->loc)[1] ?></li>
        </ul>
    </li>
</ul>
<?php
$ip = '62.39.220.112'; // Recuperation de l'IP du visiteur
// normalement $ip = $_SERVER['REMOTE_ADDR'];
/*
ou mieux 
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
*/
$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip)); //connection au serveur de ip-api.com et recuperation des données
if($query && $query['status'] == 'success') 
{
	//code avec les variables
	echo "Bonjour visiteur de " . $query['country'] . ", " . $query['city']. ", " . $query['lon']. ", " . $query['lat'];
}
?>

</body>
</html>
