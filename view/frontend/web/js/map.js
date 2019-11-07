define([
    'jquery',
    'uiComponent',
    'leaflet',
    'ko',
    'uiRegistry',
    'smile-map-markers',
    'mage/translate',
    'leaflet-markercluster'
], function ($, Component, L, ko, registry, MarkersList) {
    return Component.extend({
        defaults: {
           provider : "osm",
           tile_url: "https://{s}.tile.osm.org/{z}/{x}/{y}.png",
           controls_position: 'topright',
           markers : [],
           markerIconSize: [18,23],
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
            this.markers.forEach(function(marker) {
                marker.distance = ko.observable(0);
                marker.distanceBetween = ko.observable(0);
                marker.shopStatus = ko.observable(0);
            });
            this.displayedMarkers = ko.observable(this.markers);
        },

        /**
         * Observe events on elements
         */
        observeElements: function() {
            this.observe([
                'markers',
                'displayedMarkers',
                'nearbyMarkers',
                'selectedMarker',
                'fulltextSearch',
                'distanceBetween',
                'shopStatus'
            ]);
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

            if (component.provider !== null && (typeof component.provider === 'function' || typeof component.provider === 'object')) {
                component.provider.init(component.map, component, component.onMapReady.bind(component));
            } else {
                require(['smile-map-provider-' + component.provider], function (provider) {
                    provider.init(component.map, component, component.onMapReady.bind(component));
                    component.provider = provider;
                });
            }
        },

        /**
         * Reset the map
         */
        resetMap: function() {
            this.selectedMarker(null);
            this.currentBounds = this.initialBounds;
            this.map.fitBounds(this.initialBounds);
            this.resetHash();
        },

        /**
         * Reset the bounds : usefull to zoom out the map after having zoomed on a dedicated marker
         */
        resetBounds: function() {
            this.selectedMarker(null);
            this.map.fitBounds(this.currentBounds);
            var center = this.currentBounds.getCenter();
            this.setHashFromLocation({coords : {latitude : center.lat, longitude : center.lng}});
        },

        /**
         * Callback after map provider is ready and has been initialized
         */
        onMapReady: function() {
            this.initGeocoderBinding();
            this.loadMarkers();
            this.initPosition();
        },

        /**
         * Init current position from URL query params, if any.
         */
        initPosition: function () {
            var position = this.getLocationFromHash();
            if (position !== null) {
                this.currentBounds = this.initialBounds;
                this.applyPosition(position);
            } else {
                this.map.fitBounds(this.initialBounds);
            }
        },

        /**
         * Center the map on a given position
         *
         * @param position
         */
        applyPosition: function(position) {
            if( position === undefined || position.coords.longitude === undefined ) {
                this.displayedMarkers(this.markers());
            } else {
                var coords = new L.latLng(position.coords.latitude, position.coords.longitude);

                var isMarker = false;
                this.markers().forEach(function(marker) {
                    if (marker.latitude === position.coords.latitude && marker.longitude === position.coords.longitude) {
                        isMarker = marker;
                    }
                }, this);

                if (isMarker) {
                    this.currentBounds = this.initialBounds;
                    this.selectedMarker(isMarker);
                    this.refreshNearByMarkers(new L.latLng(isMarker.latitude, isMarker.longitude), true);
                    this.map.setView(coords, 14);
                } else {
                    this.map.setView(coords, 14);
                    this.currentBounds = this.map.getBounds();
                }

                this.setHashFromLocation(position);
            }
        },
        /**
         * Show user position and distance to each displayed markers,
         * if geolocation is 'true'
         *
         * @param position
         */
        displayPositionAndDistance: function(position) {
            if(position.coords.longitude != undefined) {
                this.addMarkerWithMyPosition(position);
                this.applyDistanceBetween(position);
            }
        },
        /**
         * Add distance from position to marker
         *
         * @param position
         */
        applyDistanceBetween: function (position) {
            var newLat = position.coords.latitude;
            var newLon = position.coords.longitude;
            var coords = new L.latLng(newLat, newLon);
            this.changeDisplayList(this.markers(), coords);
            this.markers().forEach(function (marker) {
                var itemPosition = new L.LatLng(marker.latitude, marker.longitude);
                var distanceFromCoords = itemPosition.distanceTo(coords);
                var result = (distanceFromCoords / 1000).toFixed(1);
                if(result === '0.0') {
                    result = (distanceFromCoords / 1000).toFixed(3) + ' m';
                } else {
                    result = (distanceFromCoords / 1000).toFixed(1) + ' km';
                }
                marker.distanceBetween(result);
            });
        },
        /**
         * Change list markers relatively to position of user
         *
         * @param markers
         * @param bounds
         */
        changeDisplayList: function (markers, bounds) {
            if (this.geocoder) {
                var nearbyMarkers = this.geocoder.filterMarkersListByPositionRadius(this.markers(), bounds);
                nearbyMarkers = nearbyMarkers.sort(function(a, b) {
                    var distanceA = ko.isObservable(a['distance']) ? a['distance']() : a['distance'],
                        distanceB = ko.isObservable(b['distance']) ? b['distance']() : b['distance'];
                    return ((distanceA < distanceB) ? - 1 : ((distanceA > distanceB) ? 1 : 0));
                });
            }
            this.displayedMarkers(nearbyMarkers);
        },

        /**
         * Geolocalize the user with geocoder and apply position to map.
         */
        geolocalize: function() {
            if (this.geocoder) {
                this.geocoder.geolocalize(this.applyPosition.bind(this));
                this.geocoder.geolocalize(this.displayPositionAndDistance.bind(this));
            }
        },

        /**
         * Init the geocoding component binding
         */
        initGeocoderBinding: function() {
            registry.get(this.name + '.geocoder', function (geocoder) {
                this.geocoder = geocoder;
                geocoder.currentResult.subscribe(function (result) {
                    if (result && result.bounds) {
                        this.map.setView(result.location, 11);
                        this.setHashFromLocation({coords : {latitude : result.location.lat, longitude : result.location.lng}});
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
            var markers = [],
                isMarkerCluster = this.marker_cluster === '1';
            var icon = L.icon({iconUrl: this.markerIcon, iconSize: this.markerIconSize});
            this.markers().forEach(function(markerData) {
                var currentMarker = [markerData.latitude, markerData.longitude];
                var markerOptionLocator = L.divIcon({
                    iconSize: null,
                    html: '<div class="custum-lf-popup" data-lat="'+ markerData.latitude +'" data-lon="'+ markerData.longitude +'" data-n="'+ markerData.name +'"><span>'+ markerData.id +'</span><div class="button-decor"></div></div>'
                });
                var marker = L.marker(currentMarker, {icon: markerOptionLocator});
                if (!isMarkerCluster) {
                    marker.addTo(this.map);
                }
                marker.on('click', function() {
                    this.selectMarker(markerData);
                }.bind(this));

                markers.push(marker);
                markerData.shopStatus(this.prepareShopStatus(markerData));
            }.bind(this));
            var group = new L.featureGroup(markers);
            if (isMarkerCluster) {
                group = new L.markerClusterGroup();
                group.addLayers(markers);
                this.map.addLayer(group);
            }
            this.initialBounds = group.getBounds();
        },


        /**
         * Add status of store to the list
         * @param markerData
         */
        prepareShopStatus: function (markerData) {
            var schedule = markerData.getSchedule();
            var isOpen = schedule.isOpenToday();
            var statusClass;
            if(!isOpen) {
                isOpen = 'Closed';
                statusClass = 'close-shop';
            } else {
                isOpen = 'Open';
                statusClass = 'open-shop';
            }
            var time = schedule.getTodayCloseTime(isOpen);
            if(time === 'closeNow') {
                isOpen = 'closeNow';
                statusClass = 'close-shop';
                time = schedule.getTodayCloseTime(isOpen);
                isOpen = 'Closed';
            }
            var openDay = schedule.getDayWhenStoreOpen();
            if (!openDay) {
                openDay = '';
            }
            var html = '<span class="'+ statusClass +'">'+ isOpen +'</span> - today until <span>'+ time +'</span><span>'+ openDay +'</span>';
            return html;
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
            this.setHashFromLocation({coords : marker});
            this.map.setView(coords, 18);
            
        },

        /**
         * Retrieve a list of markers nearby given coordinates
         *
         * @param coords
         * @param removeFirstMarker
         */
        refreshNearByMarkers: function(coords, removeFirstMarker) {
            removeFirstMarker = typeof removeFirstMarker === 'undefined' ? false : removeFirstMarker;
            if (this.geocoder) {
                var nearbyMarkers = this.geocoder.filterMarkersListByPositionRadius(this.markers(), coords);
                nearbyMarkers = nearbyMarkers.sort(function(a, b) {
                    var distanceA = ko.isObservable(a['distance']) ? a['distance']() : a['distance'],
                        distanceB = ko.isObservable(b['distance']) ? b['distance']() : b['distance'];
                    return ((distanceA < distanceB) ? - 1 : ((distanceA > distanceB) ? 1 : 0));
                });

                if (removeFirstMarker) {
                    nearbyMarkers.shift();
                }
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

            if (displayedMarkers.length === 0 && this.disabled_zoom_out !== '1') {
                zoom = zoom - 1;
                this.map.setZoom(zoom);
            }

            // displayedMarkers = this.addDistanceToMarkers(displayedMarkers, this.map.getCenter());

            displayedMarkers = displayedMarkers.sort(function(a, b) {
                var distanceA = ko.isObservable(a['distance']) ? a['distance']() : a['distance'],
                    distanceB = ko.isObservable(b['distance']) ? b['distance']() : b['distance'];
                return ((distanceA < distanceB) ? - 1 : ((distanceA > distanceB) ? 1 : 0));
            });


            var position = this.getLocationFromHash();
            if( position === undefined || position.coords.longitude === undefined ) {
                this.displayedMarkers(this.markers());
            } else {
                this.displayedMarkers(displayedMarkers);
            }

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
        },

        /**
         * Set current window location from Hash
         *
         * @param location
         */
        setHashFromLocation : function (location) {
            if (location.coords && location.coords.latitude && location.coords.longitude) {
                window.location.hash = [location.coords.latitude, location.coords.longitude].join(",");
            }
        },

        /**
         * Reset current window location hash
         */
        resetHash : function() {
            window.location.hash = "_";
            return false;
        },

        /**
         * Add distance from center of map to a given list of markers
         *
         * @param markersList
         * @param centerPosition
         * @returns {*}
         */
        addDistanceToMarkers: function (markersList, centerPosition) {
            if (this.provider !== null && (typeof this.provider === 'function' || typeof this.provider === 'object')) {
                return this.provider.addDistanceToMarkers(markersList, centerPosition);
            } else {
                return markersList;
            }
        },
        /**
         * Add marker with user position to the map
         *
         * @param position
         */
        addMarkerWithMyPosition: function (position) {
            if (position && position.coords) {
                var positionMe = position;
                var markerd;
                var newLat = position.coords.latitude;
                var newLon = position.coords.longitude;
                var coords = new L.latLng(newLat, newLon);
                var markerOpt = L.divIcon({
                    iconSize: null,
                    html: '<div class="custum-lf-popup position my-position" data-lat="'+ newLat +'" data-lon="'+ newLon +'"><div class="button-decor"></div></div>'
                });

                if($('.position').length > 0) {
                    $('.position').parent().remove();
                }
                markerd = L.marker(coords, {icon: markerOpt}).addTo(this.map);
            }
        },
        /**
         * Close view store details
         */
        closeDetails: function () {
            this.resetSelectedMarker();
            this.resetBounds();
            this.geolocalize();
        }
    });
});
