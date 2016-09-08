@extends('layouts.app')

  <script src="http://52.66.141.118:8090/socket.io/socket.io.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyD8x7f9Yxj3ydTDR7XSeEHhaH4t-4P2a68&sensor=false">
  </script>
  <script type="text/javascript">
    function initialize() {
      initMap();
    }
  </script>
  <script>
    var oldCircle = null;
    var oldVehTime = null;

    var map = null;
    var vehicleColors = ["#ff0000", "#333300", "#000099", "#999999"]
    var vehicleSpecs = {};
    var vehicleRoutes = {};

    var route = [];

    function MapAnnotation(map, latLng, text) {
      this.map = map;
      this.latLng = latLng;
      this.text = text;

      this.setMap(map);
    }

    MapAnnotation.prototype = new google.maps.OverlayView();

    MapAnnotation.prototype.onAdd = function() {
      var div = document.createElement('div');
      div.style.border = "1px solid #303030";
      div.style.background = "#303030";
      div.style.opacity = 0.8;
      div.style.color = "white";
      div.style.fontFamily = "arial,helvetica,sans";
      div.style.fontSize = "10px";
      div.style.fontWeight = 700;
      div.style.zIndex = 999;
      this.div_ = div;

      var panes = this.getPanes();
      panes.mapPane.appendChild(div);
    }

    MapAnnotation.prototype.draw = function() {
      var overlayProjection = this.getProjection();
      var pxVals = overlayProjection.fromLatLngToDivPixel(this.latLng);
      console.log(pxVals);
      var div = this.div_;
      div.id = "map-annotation";
      div.style.position = "absolute";
      div.style.borderRadius = "2px";
      div.style.left = pxVals.x;
      div.style.top = pxVals.y;
      div.style.padding = '2px';
      div.style.width = '110px';
      div.style.height = 'auto';

      div.innerHTML = this.text;
   }

    MapAnnotation.prototype.onRemove = function() {
      this.div_.parentNode.removeChild(this.div_);
      this.div_ = null;
    }

    MapAnnotation.prototype.show = function() {
      if (this.div_) {
        //this.div_.style.visibility = "visible";
        this.setMap(this.map);
      }
    }

    MapAnnotation.prototype.hide = function() {
      if (this.div_) {
        //this.div_.style.visibility = "hidden";
        this.setMap(null);
      }
    }

    $(document).ready(function() {
      var socket = io.connect('http://52.66.141.118:8090/');
      socket.on('connect', function(){  });
      socket.on('message', function(message) {
          console.log(message);
        vehicle = $.parseJSON(message);
        updateMap(vehicle);
      });
      socket.on('disconnect', function(){ });
    });

    function initMap() {
      geocoder = new google.maps.Geocoder();
      var lat = 12.829677;
      var lng = 80.223400;
      var myLatLng = new google.maps.LatLng(lat, lng);
      var mapOptions = {
        center: myLatLng,
        zoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
    }

    function codeAddress() {
      var address = document.getElementById("address").value;
      geocoder.geocode( { 'address': address }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          map.setCenter(results[0].geometry.location);
	      map.setZoom(10);
          var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
          });
        } else {
        alert("Geocode was not successful for the following reason: " + status);
        }
      });
    }

    function hideOld(vehicleSpec) {
      oldCircle = vehicleSpec.oldCircle;
      if (oldCircle) { oldCircle.setMap(null); }

      oldVehTime = vehicleSpec.oldVehicleTime;
      if (oldVehTime) { oldVehTime.hide(); }
    }

    function updateMap(vehicle) {
      var vehicleId = vehicle.id;
      var lat = vehicle.lat;
      var lng = vehicle.lng;
      var vehicleTime = vehicle.time;

      var vehicleSpec = vehicleSpecs[vehicle.id];

      if (!vehicleSpec || vehicleSpec == 'undefined') {
        vehicleSpecs[vehicle.id] = {};
        vehicleSpec = vehicleSpecs[vehicle.id];
        vehicleSpec.color = vehicleColors[Math.floor(Math.random() * 3)];
      }

      hideOld(vehicleSpec);

      var myLatLng = new google.maps.LatLng(lat, lng);
      var markerOptions = {
      position: myLatLng,
      icon: {
	    path: google.maps.SymbolPath.CIRCLE,
	    fillOpacity: 0.5,
	    fillColor: vehicleSpec.color,
	    strokeOpacity: 1.0,
	    strokeColor: vehicleSpec.color,
	    strokeWeight: 1.0,
	    scale: 7 //pixels
	  }
      };

      var marker = new google.maps.Marker(markerOptions);
      marker.setMap(map);

      route = vehicleSpec.route;
      if (!route || route == 'undefined') {
        route = [];
      }
      route.push(myLatLng);
      vehicleSpec.route = route;

      // Show the time
      var vehTime = new MapAnnotation(map, myLatLng, "<span style='color:yellow'>" + vehicle.id + "</span><br/>" + vehicleTime);
      vehTime.show();
      /*
      oldCircle = marker;
      oldVehTime = vehTime;
      */

      vehicleSpec.oldCircle = marker;
      vehicleSpec.oldVehicleTime = vehTime;
      vehicleSpecs[vehicle.id] = vehicleSpec;
    }
  </script>
    <script type="text/javascript">
  	$(document).ready(function() {
      initMap();
    });
  </script>
@section('content')
<div style="margin:auto;width:80%;height:100%;">
  <div class="title-bar">
    <div class="title"><strong>Track Users</strong></div>
    <div class="reset-btn"><input type="button" name="reset-map" id="reset-map" value="Reset Map" onclick="javascript:location.reload();"/></div>
  </div>
  <div id="map-canvas" class="map-canvas"></div>
</div>
    @endsection
