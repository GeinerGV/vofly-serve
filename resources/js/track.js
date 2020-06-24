const customMapStyle = [
	{
	  "elementType": "geometry",
	  "stylers": [
		{
		  "color": "#e8e1d7"
		}
	  ]
	},
	{
	  "elementType": "labels.text.fill",
	  "stylers": [
		{
		  "color": "#777777"
		}
	  ]
	},
	{
	  "elementType": "labels.text.stroke",
	  "stylers": [
		{
		  "color": "#f5f1e6"
		}
	  ]
	},
	{
	  "featureType": "administrative",
	  "elementType": "geometry.stroke",
	  "stylers": [
		{
		  "color": "#c9b2a6"
		}
	  ]
	},
	{
	  "featureType": "administrative.land_parcel",
	  "elementType": "geometry.stroke",
	  "stylers": [
		{
		  "color": "#e8dfd8"
		}
	  ]
	},
	{
	  "featureType": "administrative.land_parcel",
	  "elementType": "labels",
	  "stylers": [
		{
		  "visibility": "off"
		}
	  ]
	},
	{
	  "featureType": "administrative.land_parcel",
	  "elementType": "labels.text.fill",
	  "stylers": [
		{
		  "color": "#777777"
		}
	  ]
	},
	{
	  "featureType": "administrative.locality",
	  "stylers": [
		{
		  "visibility": "off"
		}
	  ]
	},
	{
	  "featureType": "administrative.locality",
	  "elementType": "labels.text.fill",
	  "stylers": [
		{
		  "color": "#b0b0b0"
		},
		{
		  "weight": 0.5
		}
	  ]
	},
	{
	  "featureType": "landscape.natural",
	  "elementType": "geometry",
	  "stylers": [
		{
		  "color": "#e8e1d7"
		}
	  ]
	},
	{
	  "featureType": "poi",
	  "elementType": "geometry",
	  "stylers": [
		{
		  "color": "#d9cbbe"
		}
	  ]
	},
	{
	  "featureType": "poi",
	  "elementType": "labels.text.fill",
	  "stylers": [
		{
		  "color": "#93817c"
		}
	  ]
	},
	{
	  "featureType": "poi.business",
	  "stylers": [
		{
		  "visibility": "simplified"
		}
	  ]
	},
	{
	  "featureType": "poi.park",
	  "elementType": "geometry.fill",
	  "stylers": [
		{
		  "color": "#cad89d"
		}
	  ]
	},
	{
	  "featureType": "poi.park",
	  "elementType": "labels.text",
	  "stylers": [
		{
		  "visibility": "off"
		}
	  ]
	},
	{
	  "featureType": "poi.park",
	  "elementType": "labels.text.fill",
	  "stylers": [
		{
		  "color": "#447530"
		}
	  ]
	},
	{
	  "featureType": "poi.place_of_worship",
	  "stylers": [
		{
		  "visibility": "off"
		}
	  ]
	},
	{
	  "featureType": "road",
	  "elementType": "geometry",
	  "stylers": [
		{
		  "color": "#f5f1e6"
		}
	  ]
	},
	{
	  "featureType": "road.arterial",
	  "elementType": "geometry",
	  "stylers": [
		{
		  "color": "#fdfcf8"
		}
	  ]
	},
	{
	  "featureType": "road.arterial",
	  "elementType": "geometry.stroke",
	  "stylers": [
		{
		  "color": "#fdfcf8"
		}
	  ]
	},
	{
	  "featureType": "road.highway",
	  "elementType": "geometry.stroke",
	  "stylers": [
		{
		  "color": "#ffffff"
		}
	  ]
	},
	{
	  "featureType": "road.highway",
	  "elementType": "labels",
	  "stylers": [
		{
		  "visibility": "off"
		}
	  ]
	},
	{
	  "featureType": "road.local",
	  "stylers": [
		{
		  "color": "#fdfcf8"
		}
	  ]
	},
	{
	  "featureType": "road.local",
	  "elementType": "labels",
	  "stylers": [
		{
		  "visibility": "off"
		}
	  ]
	},
	{
	  "featureType": "road.local",
	  "elementType": "labels.text.fill",
	  "stylers": [
		{
		  "color": "#777777"
		}
	  ]
	},
	{
	  "featureType": "transit.line",
	  "elementType": "labels.text.fill",
	  "stylers": [
		{
		  "color": "#8f7d77"
		}
	  ]
	},
	{
	  "featureType": "transit.line",
	  "elementType": "labels.text.stroke",
	  "stylers": [
		{
		  "color": "#ebe3cd"
		}
	  ]
	},
	{
	  "featureType": "transit.station",
	  "stylers": [
		{
		  "visibility": "off"
		}
	  ]
	},
	{
	  "featureType": "transit.station",
	  "elementType": "geometry",
	  "stylers": [
		{
		  "color": "#d8cec4"
		}
	  ]
	},
	{
	  "featureType": "water",
	  "elementType": "geometry.fill",
	  "stylers": [
		{
		  "color": "#73b5e5"
		}
	  ]
	},
	{
	  "featureType": "water",
	  "elementType": "labels.text.fill",
	  "stylers": [
		{
		  "color": "#92998d"
		}
	  ]
	}
  ];

var calcZoomFromLongitudeDelta = (delta) => {
	return Math.round(Math.log(360 / delta) / Math.LN2);
}

var parseCoordToLatLng = (coord) => {
	return {lat: coord.latitude, lng: coord.longitude}
}

var getRadioFromZoom = (zoom) => {
	return 44795 * Math.pow(Math.E, -0.498*zoom);
}

const defaultCircleOptions = {
	strokeColor: '#0071e4',
	strokeOpacity: 0.8,
	strokeWeight: 1,
	fillColor: '#0071e4',
	fillOpacity: 0.75,
}

const defaultExtraCircleOptions = {
	...defaultCircleOptions,
	strokeColor: 'red',
	fillColor: 'red',
}

window.initMap = function () {
	var myLatLng = {lat: -12.047188130329875, lng: -77.02250618487597}
	var zoom = calcZoomFromLongitudeDelta(0.35100638);
	var map = new google.maps.Map(document.getElementById("map"), {
		zoom: zoom,
		center: myLatLng,
		mapTypeControlOptions: {
			mapTypeIds: ['roadmap', 'satellite', 'hybrid', 'terrain',
					'styled_map']
		  }
	})
	var styledMapType = new google.maps.StyledMapType(customMapStyle, {name: 'Personalizado'});
	map.mapTypes.set('styled_map', styledMapType);
	map.setMapTypeId('styled_map');
	console.log(DELIVERY_DATA);

	var puntos = [];

	var puntosExtras = [];

	function AddPuntos(location) {
		var punto = new google.maps.Circle({
			...defaultCircleOptions,
			map: map,
			center: location,
			radius: getRadioFromZoom(zoom),
			...(puntos.length>0 ? {} : {
				strokeColor: '#000',
				fillColor: '#000',
				zIndex: 100
			})
		});
		puntos.push(punto);
	}

	function AddExtra(location) {
		var extra = new google.maps.Circle({
			...defaultExtraCircleOptions,
			map: map,
			center: location,
			radius: getRadioFromZoom(zoom),
			...(puntos.length>0 ? {} : {
				strokeColor: '#000',
				fillColor: '#000',
				zIndex: 100
			})
		});
		puntosExtras.push(extra);
	}

	(DELIVERY_DATA.location||[]).forEach((coord, idx) => {
		AddPuntos(parseCoordToLatLng(coord));
	});

	map.addListener('zoom_changed', function() {
		zoom = map.zoom;
		puntos.forEach(punto=>{
			punto.setOptions({
				radius: getRadioFromZoom(map.zoom),
			})
		})
		puntosExtras.forEach(extra=>{
			extra.setOptions({
				radius: getRadioFromZoom(map.zoom),
			});
		});
	});

	var recojo = new google.maps.Marker({
		position: {lat: DELIVERY_DATA.recogible.place.latitud, 
			lng: DELIVERY_DATA.recogible.place.longitud},
		map: map,
		title: "Recojo",
		label: "R"
	})
	var entrega = new google.maps.Marker({
		position: {lat: DELIVERY_DATA.entregable.place.latitud, 
			lng: DELIVERY_DATA.entregable.place.longitud},
		map: map,
		title: "Entrega",
		label: "E"
	})

	var driver = !DELIVERY_DATA.driver?.location ? null : new google.maps.Marker({
		position: parseCoordToLatLng(DELIVERY_DATA.driver.location),
		map: map,
		title: "Driver",
		label: "D",
		icon: '/images/red-driver-marker-clipart.png',
	})

	var interval = setInterval(()=>{
		if (DELIVERY_DATA.estado!=="Enviado") {
			axios.post("/track", {
				id: DELIVERY_DATA.trackid
			}).then(res=>{
				if (res.data?.delivery) {
					DELIVERY_DATA = {...DELIVERY_DATA, ...res.data.delivery}
					if (DELIVERY_DATA.driver) {
						if(DELIVERY_DATA.driver.location) AddExtra(parseCoordToLatLng(DELIVERY_DATA.driver.location))
					}
				}
			})
		} else {
			if (puntos.length) puntos[puntos.length-1].setOptions({
				strokeColor: 'red',
				fillColor: 'red',
				zIndex: 100
			});
			if (driver) {
				driver.setOptions({
					map: null
				})
				driver = null;
			}
			clearInterval(interval);
		}
	}, 3000)
}
