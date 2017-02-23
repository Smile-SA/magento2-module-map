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
    'mage/translate'
], function ($, Component) {
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
            require(['smile-geocoder-provider-' + component.provider], function(provider) {
                provider.init(component, component.onGeocoderReady.bind(component));
                component.provider = provider;
            }).bind(this);
        },

        /**
         * Assign geocoder component after init. Used as a callback.
         */
        onGeocoderReady: function() {
            this.geocoder = this.provider.getGeocoder();
            this.onSearch();
        },

        /**
         * Trigger the geocoding on search. Exposes current result then.
         */
        onSearch: function() {
            if (!this.fulltextSearch() || this.fulltextSearch().trim().length === 0) {
                this.currentResult(null);
            } else {
                var geocodingOptions = {};
                this.geocoder.geocode(this.fulltextSearch(), geocodingOptions, function (results) {
                    if (results.length > 0) {
                        this.currentResult(results[0]);
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
            return this.geocoder.filterMarkersListByPositionRadius(markersList, centerPosition, radius)
        }
    })
});
