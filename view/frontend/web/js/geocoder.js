/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 *
 * @category  Smile
 * @package   Smile\Map
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2017 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
/*global define*/
define([
    'jquery',
    'uiComponent',
    'uiRegistry',
    'mage/translate'
], function ($, Component, registry) {
    return Component.extend({
        defaults: {
            provider: "osm",
            radius: 25000
        },

        /**
         * Component Constructor
         */
        initialize: function () {
            this._super();
            this.observe(['fulltextSearch', 'currentResult']);
        },

        /**
         * Init the geocoder component
         *
         * @param element   Element triggering the init
         * @param component The JS Component
         */
        initGeocoder: function (element, component) {
            if (component.provider !== null && (typeof component.provider === 'function' || typeof component.provider === 'object')) {
                component.provider.init(component, component.onGeocoderReady.bind(component));
            } else {
                require(['smile-geocoder-provider-' + component.provider], function(provider) {
                    provider.init(component, component.onGeocoderReady.bind(component));
                    component.provider = provider;
                }).bind(this);
            }
        },

        /**
         * Assign geocoder component after init. Used as a callback.
         */
        onGeocoderReady: function() {
            this.geocoder = this.provider.getGeocoder();
        },

        /**
         * Trigger the geocoding on search. Exposes current result then.
         *
         * @param {function} noResultCallback Callback executed if no result was found
         */
        onSearch: function(noResultCallback = null) {
            if (!this.fulltextSearch() || this.fulltextSearch().trim().length === 0) {
                this.currentResult(null);
            } else {
                var geocodingOptions = {};
                this.geocoder.geocode(this.fulltextSearch(), geocodingOptions, function (results) {
                    if (results.length > 0) {
                        this.currentResult(results[0]);

                        return;
                    }

                    if (typeof noResultCallback === 'function') {
                        noResultCallback();
                    }
                }.bind(this));
            }
        },

        /**
         * Filters a given list of markers being around a position, for the current radius
         *
         * @param markersList    An array containing the markers
         * @param centerPosition The center position
         * @param radius         The radius to check
         *
         * @returns {*|Array}
         */
        filterMarkersListByPositionRadius: function(markersList, centerPosition, radius) {
            if (!radius) {
                radius = parseInt(this.radius, 10);
            }
            if (this.geocoder == undefined && typeof this.provider === 'object') {
                this.geocoder = this.provider.getGeocoder();
            }
            return this.geocoder.filterMarkersListByPositionRadius(markersList, centerPosition, radius)
        },

        /**
         * Geolocalize current user
         * Uses navigator.geolocation object to retrieve position.
         *
         * Fallbacks to a localization by API if
         * @param callback
         */
        geolocalize: function(callback) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    callback,
                    function(error) {
                        if (error.message.indexOf("Only secure origins are allowed") === 0
                            || error.code === error.POSITION_UNAVAILABLE
                            || error.code === error.TIMEOUT) {
                            this.geoLocalizeViaApi(callback);
                        }
                    }.bind(this),
                    {maximumAge: 50000, timeout: 20000, enableHighAccuracy: true}
                );
            }
        },

        /**
         * Geolocalizes the user via the geocoder provider.
         * Called as a fallback when geolocation through user browser fails.
         *
         * @param callback
         * @returns {*}
         */
        geoLocalizeViaApi: function(callback) {
            return this.geocoder.geoLocalizeViaApi(callback);
        }
    })
});
