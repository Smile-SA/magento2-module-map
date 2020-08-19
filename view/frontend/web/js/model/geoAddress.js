/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 *
 * @category  Smile
 * @package   Smile\Map
 * @author    Remy LESCALLIER <remy.lescallier@smile.fr>
 * @copyright 2020 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

define(function () {
    'use strict';

    /**
     * Constructor
     *
     * @param {Object} data
     * @constructor
     */
    function GeoAddress(data) {
        if (!(this instanceof GeoAddress)) {
            throw new TypeError("GeoAddress constructor cannot be called as a function.");
        }

        this.city = data.city;
        this.country = data.country;
        this.countryCode = data.countryCode;
        this.street = data.street;
        this.streetNumber = data.streetNumber;
        this.postCode = data.postCode;

        this.position = {};
        this.position.latitude = data.position.latitude;
        this.position.longitude = data.position.longitude;
    }

    GeoAddress.prototype = {

        /**
         * Constructor
         */
        constructor: GeoAddress,

        /**
         * Get city
         *
         * @return {String}
         */
        getCity: function () {
            return this.city;
        },

        /**
         * Get country name
         *
         * @return {String}
         */
        getCountry: function () {
            return this.country;
        },

        /**
         * Get country code
         *
         * @return {String}
         */
        getCountryCode: function () {
            return this.countryCode;
        },

        /**
         * Get street name
         *
         * @return {String}
         */
        getStreet: function () {
            return this.street;
        },

        /**
         * Get street number
         *
         * @return {String}
         */
        getStreetNumber: function () {
            return this.streetNumber;
        },

        /**
         * Get post code
         *
         * @return {String}
         */
        getPostCode: function () {
            return this.postCode;
        },

        /**
         * Get position latitude
         *
         * @return {Number}
         */
        getPositionLatitude: function () {
            return this.position.latitude;
        },

        /**
         * Get position longitude
         *
         * @return {Number}
         */
        getPositionLongitude: function () {
            return this.position.longitude;
        },
    };

    return GeoAddress;
});
