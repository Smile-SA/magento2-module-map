define([
    'jquery',
    'uiComponent',
    'leaflet',
    'Smile_Map/js/geocoder/abstract',
    'mage/translate'
], function ($, Component, L, Geocoder) {
    return Component.extend({
        defaults: {
           provider : "osm",
           tile_url: "http://{s}.tile.osm.org/{z}/{x}/{y}.png",
           controls_position: 'topright',
           markers : [],
           selectedMarker : null
        },

        initialize: function () {
            this._super();
            this.displayedMarkers = this.markers;
            this.observe(['markers', 'displayedMarkers', 'selectedMarker', 'fulltextSearch']);
            this.markers.subscribe(this.loadMarkers.bind(this));
        },
        
        initMap: function (element, component) {
            component.map = L.map(element, {zoomControl: false, attributionControl: false});
            component.map.on('moveend', component.refreshDisplayedMarkers.bind(component));
            layerControl = L.control.zoom({position: component['controls_position']});
            layerControl.addTo(component.map);
            
            require(['smile-map-provider-' + component.provider], function(provider) {
                provider.init(component.map, component, component.onMapReady.bind(component));
                component.provider = provider;
            });
        },

        onMapReady: function() {
            this.loadMarkers();
            this.geocoder = new Geocoder();
        },
        
        onSearch: function() {
            if (this.fulltextSearch().trim().length == 0) {
                this.onSearchReset();
            } else {
                var geocodingOptions = {'bounds' : this.initialBounds};
                this.geocoder.geocode(this.fulltextSearch(), geocodingOptions, function(results) {
                    this.map.fitBounds(results[0].bounds);
                }.bind(this));
            }

            return false;
        },
        
        onSearchReset: function() {
            this.fulltextSearch(null);
            this.map.fitBounds(this.initialBounds);
        },

        loadMarkers: function() {
            var markers = [];
            this.markers().forEach(function(markerData) {
                var currentMarker = [markerData.latitude, markerData.longitude];
                var marker = L.marker(currentMarker).addTo(this.map);
                marker.on('click', function() {
                    this.selectedMarker(markerData);
                }.bind(this));
                markers.push(marker);
            }.bind(this));
            
            var group = new L.featureGroup(markers);
            this.map.fitBounds(group.getBounds());
            this.initialBounds = this.map.getBounds();
        },

        resetSelectedMarker: function () {
            this.selectedMarker(null);
        },

        selectMarker: function(marker) {
            this.selectedMarker(marker)
        },
        
        refreshDisplayedMarkers: function () {
            var bounds = this.map.getBounds()
            var displayedMarkers = this.markers().filter(function(marker) {
                var coords = new L.latLng(marker.latitude, marker.longitude);
                return bounds.contains(coords);
            })
            
            if (displayedMarkers.length == 0) {
                displayedMarkers = this.markers();
            }
            
            this.displayedMarkers(displayedMarkers);
        },
        
        countDisplayedMarkers : function() {
            return this.displayedMarkers().length;
        },
        
        getSearchResultLabel : function() {
            return $.mage.__('%s result(s)').replace('%s', this.countDisplayedMarkers());
        },
        
        displayReset : function() {
            return this.displayedMarkers().length != this.markers().length
        }
    });
});