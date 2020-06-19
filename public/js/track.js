/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/track.js":
/*!*******************************!*\
  !*** ./resources/js/track.js ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports) {

function ownKeys(object, enumerableOnly) {
  var keys = Object.keys(object);

  if (Object.getOwnPropertySymbols) {
    var symbols = Object.getOwnPropertySymbols(object);
    if (enumerableOnly) symbols = symbols.filter(function (sym) {
      return Object.getOwnPropertyDescriptor(object, sym).enumerable;
    });
    keys.push.apply(keys, symbols);
  }

  return keys;
}

function _objectSpread(target) {
  for (var i = 1; i < arguments.length; i++) {
    var source = arguments[i] != null ? arguments[i] : {};

    if (i % 2) {
      ownKeys(Object(source), true).forEach(function (key) {
        _defineProperty(target, key, source[key]);
      });
    } else if (Object.getOwnPropertyDescriptors) {
      Object.defineProperties(target, Object.getOwnPropertyDescriptors(source));
    } else {
      ownKeys(Object(source)).forEach(function (key) {
        Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));
      });
    }
  }

  return target;
}

function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

var customMapStyle = [{
  "elementType": "geometry",
  "stylers": [{
    "color": "#e8e1d7"
  }]
}, {
  "elementType": "labels.text.fill",
  "stylers": [{
    "color": "#777777"
  }]
}, {
  "elementType": "labels.text.stroke",
  "stylers": [{
    "color": "#f5f1e6"
  }]
}, {
  "featureType": "administrative",
  "elementType": "geometry.stroke",
  "stylers": [{
    "color": "#c9b2a6"
  }]
}, {
  "featureType": "administrative.land_parcel",
  "elementType": "geometry.stroke",
  "stylers": [{
    "color": "#e8dfd8"
  }]
}, {
  "featureType": "administrative.land_parcel",
  "elementType": "labels",
  "stylers": [{
    "visibility": "off"
  }]
}, {
  "featureType": "administrative.land_parcel",
  "elementType": "labels.text.fill",
  "stylers": [{
    "color": "#777777"
  }]
}, {
  "featureType": "administrative.locality",
  "stylers": [{
    "visibility": "off"
  }]
}, {
  "featureType": "administrative.locality",
  "elementType": "labels.text.fill",
  "stylers": [{
    "color": "#b0b0b0"
  }, {
    "weight": 0.5
  }]
}, {
  "featureType": "landscape.natural",
  "elementType": "geometry",
  "stylers": [{
    "color": "#e8e1d7"
  }]
}, {
  "featureType": "poi",
  "elementType": "geometry",
  "stylers": [{
    "color": "#d9cbbe"
  }]
}, {
  "featureType": "poi",
  "elementType": "labels.text.fill",
  "stylers": [{
    "color": "#93817c"
  }]
}, {
  "featureType": "poi.business",
  "stylers": [{
    "visibility": "simplified"
  }]
}, {
  "featureType": "poi.park",
  "elementType": "geometry.fill",
  "stylers": [{
    "color": "#cad89d"
  }]
}, {
  "featureType": "poi.park",
  "elementType": "labels.text",
  "stylers": [{
    "visibility": "off"
  }]
}, {
  "featureType": "poi.park",
  "elementType": "labels.text.fill",
  "stylers": [{
    "color": "#447530"
  }]
}, {
  "featureType": "poi.place_of_worship",
  "stylers": [{
    "visibility": "off"
  }]
}, {
  "featureType": "road",
  "elementType": "geometry",
  "stylers": [{
    "color": "#f5f1e6"
  }]
}, {
  "featureType": "road.arterial",
  "elementType": "geometry",
  "stylers": [{
    "color": "#fdfcf8"
  }]
}, {
  "featureType": "road.arterial",
  "elementType": "geometry.stroke",
  "stylers": [{
    "color": "#fdfcf8"
  }]
}, {
  "featureType": "road.highway",
  "elementType": "geometry.stroke",
  "stylers": [{
    "color": "#ffffff"
  }]
}, {
  "featureType": "road.highway",
  "elementType": "labels",
  "stylers": [{
    "visibility": "off"
  }]
}, {
  "featureType": "road.local",
  "stylers": [{
    "color": "#fdfcf8"
  }]
}, {
  "featureType": "road.local",
  "elementType": "labels",
  "stylers": [{
    "visibility": "off"
  }]
}, {
  "featureType": "road.local",
  "elementType": "labels.text.fill",
  "stylers": [{
    "color": "#777777"
  }]
}, {
  "featureType": "transit.line",
  "elementType": "labels.text.fill",
  "stylers": [{
    "color": "#8f7d77"
  }]
}, {
  "featureType": "transit.line",
  "elementType": "labels.text.stroke",
  "stylers": [{
    "color": "#ebe3cd"
  }]
}, {
  "featureType": "transit.station",
  "stylers": [{
    "visibility": "off"
  }]
}, {
  "featureType": "transit.station",
  "elementType": "geometry",
  "stylers": [{
    "color": "#d8cec4"
  }]
}, {
  "featureType": "water",
  "elementType": "geometry.fill",
  "stylers": [{
    "color": "#73b5e5"
  }]
}, {
  "featureType": "water",
  "elementType": "labels.text.fill",
  "stylers": [{
    "color": "#92998d"
  }]
}];

var calcZoomFromLongitudeDelta = function calcZoomFromLongitudeDelta(delta) {
  return Math.round(Math.log(360 / delta) / Math.LN2);
};

var parseCoordToLatLng = function parseCoordToLatLng(coord) {
  return {
    lat: coord.latitude,
    lng: coord.longitude
  };
};

var getRadioFromZoom = function getRadioFromZoom(zoom) {
  return 44795 * Math.pow(Math.E, -0.498 * zoom);
};

var defaultCircleOptions = {
  strokeColor: '#0071e4',
  strokeOpacity: 0.8,
  strokeWeight: 1,
  fillColor: '#0071e4',
  fillOpacity: 0.75
};

var defaultExtraCircleOptions = _objectSpread({}, defaultCircleOptions, {
  strokeColor: 'red',
  fillColor: 'red'
});

window.initMap = function () {
  var myLatLng = {
    lat: -12.047188130329875,
    lng: -77.02250618487597
  };
  var zoom = calcZoomFromLongitudeDelta(0.35100638);
  var map = new google.maps.Map(document.getElementById("map"), {
    zoom: zoom,
    center: myLatLng,
    mapTypeControlOptions: {
      mapTypeIds: ['roadmap', 'satellite', 'hybrid', 'terrain', 'styled_map']
    }
  });
  var styledMapType = new google.maps.StyledMapType(customMapStyle, {
    name: 'Personalizado'
  });
  map.mapTypes.set('styled_map', styledMapType);
  map.setMapTypeId('styled_map');
  console.log(DELIVERY_DATA);
  var puntos = [];
  var puntosExtras = [];

  function AddPuntos(location) {
    var punto = new google.maps.Circle(_objectSpread({}, defaultCircleOptions, {
      map: map,
      center: location,
      radius: getRadioFromZoom(zoom)
    }, puntos.length > 0 ? {} : {
      strokeColor: '#000',
      fillColor: '#000',
      zIndex: 100
    }));
    puntos.push(punto);
  }

  function AddExtra(location) {
    var extra = new google.maps.Circle(_objectSpread({}, defaultExtraCircleOptions, {
      map: map,
      center: location,
      radius: getRadioFromZoom(zoom)
    }, puntos.length > 0 ? {} : {
      strokeColor: '#000',
      fillColor: '#000',
      zIndex: 100
    }));
    puntosExtras.push(extra);
  }

  DELIVERY_DATA.location.forEach(function (coord, idx) {
    AddPuntos(parseCoordToLatLng(coord));
  });
  map.addListener('zoom_changed', function () {
    zoom = map.zoom;
    puntos.forEach(function (punto) {
      punto.setOptions({
        radius: getRadioFromZoom(map.zoom)
      });
    });
    puntosExtras.forEach(function (extra) {
      extra.setOptions({
        radius: getRadioFromZoom(map.zoom)
      });
    });
  });
  var recojo = new google.maps.Marker({
    position: {
      lat: DELIVERY_DATA.recogible.place.latitud,
      lng: DELIVERY_DATA.recogible.place.longitud
    },
    map: map,
    title: "Recojo",
    label: "R"
  });
  var entrega = new google.maps.Marker({
    position: {
      lat: DELIVERY_DATA.entregable.place.latitud,
      lng: DELIVERY_DATA.entregable.place.longitud
    },
    map: map,
    title: "Entrega",
    label: "E"
  });
  var driver = !DELIVERY_DATA.driver ? null : new google.maps.Marker({
    position: parseCoordToLatLng(DELIVERY_DATA.driver.location),
    map: map,
    title: "Driver",
    label: "D",
    icon: '/images/red-driver-marker-clipart.png'
  });
  var interval = setInterval(function () {
    if (DELIVERY_DATA.estado !== "Enviado") {
      axios.post("/tack", {
        id: DELIVERY_DATA.trackid
      }).then(function (res) {
        var _res$data;

        if ((_res$data = res.data) === null || _res$data === void 0 ? void 0 : _res$data.delivery) {
          DELIVERY_DATA = _objectSpread({}, DELIVERY_DATA, {}, res.data.delivery);

          if (DELIVERY_DATA.driver) {
            AddExtra(parseCoordToLatLng(DELIVERY_DATA.driver.location));
          }
        }
      });
    } else {
      if (puntos.length) puntos[puntos.length - 1].setOptions({
        strokeColor: 'red',
        fillColor: 'red',
        zIndex: 100
      });

      if (driver) {
        driver.setOptions({
          map: null
        });
        driver = null;
      }

      clearInterval(interval);
    }
  }, 3000);
};

/***/ }),

/***/ 2:
/*!*************************************!*\
  !*** multi ./resources/js/track.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! E:\Proyectos\Trabajo\vofly-serve\resources\js\track.js */"./resources/js/track.js");


/***/ })

/******/ });