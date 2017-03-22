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
            this.observe(['markers', 'displayedMarkers', 'nearbyMarkers', 'selectedMarker', 'fulltextSearch']);
            this.markers.subscribe(this.loadMarkers.bind(this));
        },

        /**
         * Init the Map. Called as callback after component initialization
         *
         * @param element   Map element
         * @param component Component
         */
        initMap: function (element, component) {
            component.map = L.map(element, {zoomControl: false, attributionControl: false, scrollWheelZoom: $(document).width() > 480});
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
            this.currentBounds = this.initialBounds;
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
            this.initGeocoderBinding(this.initPosition.bind(this));
            this.loadMarkers();
        },

        /**
         * Init current position from URL query params, if any.
         */
        initPosition: function () {
            var position = this.getLocationFromHash();
            if (position !== null) {
                this.currentBounds = this.initialBounds;
                this.applyPosition(position);
            } else if (!this.geocoder || !this.geocoder.fulltextSearch()) {
                this.map.fitBounds(this.initialBounds);
            }
        },

        /**
         * Center the map on a given position
         *
         * @param position
         */
        applyPosition: function(position) {
            if (position && position.coords) {
                var coords = new L.latLng(position.coords.latitude, position.coords.longitude);
                this.map.setView(coords, 11);
                this.currentBounds = this.map.getBounds();
            }
        },

        /**
         * Init the geocoding component binding
         */
        initGeocoderBinding: function(callback) {
            registry.get(this.name + '.geocoder', function (geocoder) {
                this.geocoder = geocoder;
                geocoder.currentResult.subscribe(function (result) {
                    if (result && result.bounds) {
                        this.map.setView(result.location, 11);
                        this.currentBounds = result.bounds;
                    } else {
                        this.resetMap();
                        return callback();
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
            this.initialBounds = group.getBounds();
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
            // Set current bounds before zooming in : to allow returning to these bounds after.
            if (!this.selectedMarker()) {
                this.currentBounds = this.map.getBounds();
            }

            this.selectedMarker(marker);
            var coords = new L.latLng(marker.latitude, marker.longitude);
            this.refreshNearByMarkers(coords);

            // Remove current markers from nearby markers
            var nearbyMarkers = this.nearbyMarkers();
            nearbyMarkers.shift();
            this.nearbyMarkers(nearbyMarkers);

            this.map.setView(coords, 15);
        },

        /**
         * Retrieve a list of markers nearby given coordinates
         *
         * @param coords
         */
        refreshNearByMarkers: function(coords) {
            if (this.geocoder) {
                var nearbyMarkers = this.geocoder.filterMarkersListByPositionRadius(this.markers(), coords);
                nearbyMarkers = nearbyMarkers.sort(function(a, b) {
                    var distanceA = a['distance']; var distanceB = b['distance'];
                    return ((distanceA < distanceB) ? - 1 : ((distanceA > distanceB) ? 1 : 0));
                });

                this.nearbyMarkers(nearbyMarkers);
            }
        },

        /**
         * Refresh markers according to current bounds.
         */
        refreshDisplayedMarkers: function () {
            var bounds = this.map.getBounds();
            var displayedMarkers = this.filterMarkersByBounds(this.markers(), bounds);
            var zoom = this.map.getZoom();

            if (displayedMarkers.length === 0) {
                zoom = zoom - 1;
                this.map.setZoom(zoom);
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
         * Count the number of nearby markers.
         *
         * @returns int
         */
        countNearbyMarkers : function() {
            return this.nearbyMarkers().length;
        },

        /**
         * If a Reset link can be displayed
         *
         * @returns {boolean}
         */
        displayReset : function() {
            return this.displayedMarkers().length !== this.markers().length
        },

        /**
         * Parse query params from query String
         *
         * @param qs
         * @returns {{}}
         */
        getQueryParams : function (qs) {
            qs = qs.split('+').join(' ');

            var params = {},
                tokens,
                re = /[?&]?([^=]+)=([^&]*)/g;

            while (tokens = re.exec(qs)) {
                params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
            }

            return params;
        },

        /**
         * Return current location from URL anchor if any.
         *
         * @returns {*}
         */
        getLocationFromHash : function() {
            var location = null;

            var hash = window.location.hash.substr(1);
            if (hash) {
                hash = hash.split(",");
                if (hash.length === 2) {
                    location = {coords: {latitude: hash[0], longitude: hash[1]}};
                }
            }

            return location;
        }
    });
});
