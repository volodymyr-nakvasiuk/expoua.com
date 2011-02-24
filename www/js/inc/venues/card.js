js.module("inc.venues.card");
js.include("jquery.ui.tabs");
js.include("jquery.photoslider");
js.include("jquery.form");

$(function () {
	var mapDiv = document.getElementById("venue_map");
	if (mapDiv){
		var map = 0;
		$( "#tabs" ).bind( "tabsshow", function(event, ui) {
			if (ui.panel.id == "map" && map==0) {
				latlng = new google.maps.LatLng(latlng.lat, latlng.lng);
				var options = {
					center: latlng,
					zoom: 11,
					draggable: true,
					scrollwheel: true,
					mapTypeControl: true,
					navigationControl: true,
					scaleControl: true,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				map = new google.maps.Map(mapDiv, options);

				var exImage = new google.maps.MarkerImage('/img/venues/map_marker_venue.png', new google.maps.Size(36, 50), new google.maps.Point(0,0), new google.maps.Point(18, 50));
				var exShadow = new google.maps.MarkerImage('/img/venues/map_shadow_venue.png', new google.maps.Size(59, 50), new google.maps.Point(0,0), new google.maps.Point(18, 50));
				var marker = new google.maps.Marker({ title:venue_map_info.title, position: latlng, icon: exImage, shadow: exShadow});
				marker.setMap(map);

				var infowindow = new google.maps.InfoWindow({content: '<h3>'+venue_map_info.title+'</h3>&nbsp;'+venue_map_info.address});
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.open(map, marker);
				});
			}
		});
	}
});