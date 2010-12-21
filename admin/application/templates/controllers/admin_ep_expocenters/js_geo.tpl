google.load("maps", "2.x", {ldelim}"language" : "{$selected_language}"{rdelim});
var map = null;
var geocoder = null;
{literal}function mapInitialize() {
	map = new google.maps.Map2(document.getElementById("map"));
	var lat = parseFloat($("#latitude").val());
	var lng = parseFloat($("#longitude").val());
	if (lat != 0) {
		var point = new google.maps.LatLng(lat, lng);
		map.setCenter(point, 15);
		map.addOverlay(new google.maps.Marker(point));
	} else {
		map.setCenter(new google.maps.LatLng(0, 0), 2);
	}
	map.setMapType(G_HYBRID_MAP);
	map.setUIToDefault();

	geocoder = new google.maps.ClientGeocoder();

	{/literal}{if $entry.latitude == 0 && !empty($entry.address)}
	showAddress($("#address").val() + ", " + cityName + ", " + countryName);
	{/if}{literal}
	$("#address").blur(function() {
		showAddress($(this).val() + ", " + cityName + ", " + countryName);
	});
}
google.setOnLoadCallback(mapInitialize);

function showAddress(address) {
	geocoder.getLocations(address, function(response) {
		map.clearOverlays();
		if (!response || response.Status.code != 200) {
			alert("Не удалось определить координаты для адреса\n" + address);
		} else {
			var place = response.Placemark[0];
			var point = new google.maps.LatLng(place.Point.coordinates[1], place.Point.coordinates[0]);
			map.setCenter(point, 15);

			var marker = new google.maps.Marker(point, {draggable: true});
			markerAddListeners(marker);

			map.addOverlay(marker);

			var message = address + '<br/>' + place.address + '<br/>Точность: ' + place.AddressDetails.Accuracy + '<br/>';
			if (place.AddressDetails.Accuracy > 6) {
				applyCoordinats(point.lat(), point.lng());
				message += 'Координаты приняты';
				$("#address").css("background-color", "green");
			} else {
				message += '<input type="button" value="Принять координаты" onclick="applyCoordinats(' + point.lat() + ',' + point.lng() + ');"/>';
				$("#address").css("background-color", "red");
			}
			
			marker.openInfoWindowHtml(message);
		}
	});
}

function markerAddListeners(marker) {
	google.maps.Event.addListener(marker, "dragstart", function() {
		map.closeInfoWindow();
	});
	google.maps.Event.addListener(marker, "dragend", function() {
		var point = marker.getLatLng();
		geocoder.getLocations(point, function(response) {
			map.clearOverlays();
			var marker = new google.maps.Marker(point, {draggable: true});
			
			if (!response || response.Status.code != 200) {
				marker.openInfoWindowHtml('Не удалось определить адрес.<br/>Принять новые координаты?<br/><input type="button" value="Да" onclick="applyCoordinats(' + point.lat() + ',' + point.lng() + ');"/>');
			} else {
				var place = response.Placemark[0];
				markerAddListeners(marker);

				map.addOverlay(marker);
				
				var message = 'Адрес в точке определен как:<br/>' + place.address + '<br/>Точность: ' + place.AddressDetails.Accuracy + '<br/>';
				message += '<input type="button" value="Принять координаты" onclick="applyCoordinats(' + point.lat() + ',' + point.lng() + ');"/>';
				
				marker.openInfoWindowHtml(message);
			}
		});
	});
}

function applyCoordinats(lat, lng) {
	$("#latitude").val(lat);
	$("#longitude").val(lng);
}
{/literal}
