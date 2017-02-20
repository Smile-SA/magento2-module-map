define([
    'jquery',
    'uiComponent',
    'leaflet',
    'ko',
    'uiRegistry',
    'smile-map-markers',
    'mage/translate'
], function ($, Component, L, ko, registry, MarkersList) {
    return Component.extend({
        defaults: {
           provider : "osm",
           tile_url: "http://{s}.tile.osm.org/{z}/{x}/{y}.png",
           controls_position: 'topright',
           markers : [],
           selectedMarker : null
        },

        /**
         * Map constructor
         */
        initialize: function () {
            this._super();
            this.initMarkers();
            this.observeElements();
        },

        /**
         * Init markers on the map
         */
        initMarkers: function() {
            var markersList = new MarkersList({items : this.markers});
            this.markers = markersList.getList();
            this.displayedMarkers = ko.observable(markersList.getList());
        },

        /**
         * Observe events on elements
         */
        observeElements: function() {
            this.observe(['markers', 'displayedMarkers', 'selectedMarker', 'fulltextSearch']);
            this.markers.subscribe(this.loadMarkers.bind(this));
        },

        /**
         * Init the Map. Called as callback after component initialization
         *
         * @param element   Map element
         * @param component Component
         */
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

        /**
         * Reset the map
         */
        resetMap: function() {
            this.selectedMarker(null);
            this.map.fitBounds(this.initialBounds);
        },

        /**
         * Reset the bounds : usefull to zoom out the map after having zoomed on a dedicated marker
         */
        resetBounds: function() {
            this.selectedMarker(null);
            this.map.fitBounds(this.currentBounds);
        },

        /**
         * Callback after map provider is ready and has been initialized
         */
        onMapReady: function() {
            this.loadMarkers();
            this.initGeocoderBinding();
        },

        /**
         * Init the geocoding component binding
         */
        initGeocoderBinding: function() {
            registry.get(this.name + '.geocoder', function (geocoder) {
                this.geocoder = geocoder;
                geocoder.currentResult.subscribe(function (result) {
                    if (result && result.bounds) {
                        this.map.fitBounds(result.bounds);
                        this.currentBounds = result.bounds;
                    } else {
                        this.resetMap();
                    }
                }.bind(this));
            }.bind(this));
        },

        /**
         * Load the markers and centers the map on them.
         */
        loadMarkers: function() {
            var markers = [];
            this.markers().forEach(function(markerData) {
                var currentMarker = [markerData.latitude, markerData.longitude];
                var marker = L.marker(currentMarker).addTo(this.map);
                marker.on('click', function() {
                    this.selectMarker(markerData);
                }.bind(this));
                markers.push(marker);
            }.bind(this));
            
            var group = new L.featureGroup(markers);
            this.map.fitBounds(group.getBounds());
            this.initialBounds = this.map.getBounds();
            this.currentBounds = this.initialBounds;
        },

        /**
         * Reset the currently selected marker
         */
        resetSelectedMarker: function () {
            this.selectedMarker(null);
        },

        /**
         * Select a given marker
         *
         * @param marker
         */
        selectMarker: function(marker) {
            this.selectedMarker(marker);
            var coords = new L.latLng(marker.latitude, marker.longitude);
            this.map.setView(coords, 15);
        },

        /**
         * Refresh markers according to current bounds.
         */
        refreshDisplayedMarkers: function () {
            var bounds = this.map.getBounds();

            var displayedMarkers = this.filterMarkersByBounds(this.markers(), bounds);

            if (displayedMarkers.length === 0) {
                displayedMarkers = this.markers();
            }
            
            this.displayedMarkers(displayedMarkers);
        },

        /**
         * Filters a given list of marker by bounds. Only keep the markers being contained by bounds.
         *
         * @param markers list of markers
         * @param bounds  bounds to apply
         *
         * @returns {Array}
         */
        filterMarkersByBounds: function (markers, bounds) {

            var list = [];

            markers.forEach(function(marker) {
                var coords = new L.latLng(marker.latitude, marker.longitude);
                if (bounds.contains(coords)) {
                    list.push(marker);
                }
            }, this);

            return list;
        },

        /**
         * Count the number of currently displayed markers.
         *
         * @returns int
         */
        countDisplayedMarkers : function() {
            return this.displayedMarkers().length;
        },

        /**
         * If a Reset link can be displayed
         *
         * @returns {boolean}
         */
        displayReset : function() {
            return this.displayedMarkers().length !== this.markers().length
        }
    });
});
