define([
    'jquery',
    'leaflet',
    'geoAddressModel'
], function ($, L, GeoAddressModel) {

    const BASE_API_URL = '//nominatim.openstreetmap.org';

    function getServiceUrl(qry, options) {
        options['countrycodes'] = options['countrycodes'] || 'FR';
        var parameters = L.Util.extend({
            q: qry,
            format: 'json',
        }, options);

        this.options = options;

        return 'https:'
            + '//nominatim.openstreetmap.org/search'
            + L.Util.getParamString(parameters);
    }

    function getGeolocalizeApi(config) {
        return '//api.ipstack.com/check?output=json&fields=latitude,longitude&access_key='+ config['api_key'];
    }

    /**
     * Geocoder constructor
     *
     * @param options Options configuration
     * @constructor
     */
    function Geocoder(options) {
        this.options = options;
    }

    /**
     * Geocode a text query (address, postcode, etc...)
     *
     * @param queryText The query text
     * @param options   The geocoder options
     * @param callback  potential callback to call on results
     */
    Geocoder.prototype.geocode = function (queryText, options, callback) {
        var queryUrl = getServiceUrl(queryText, options);
        $.getJSON(queryUrl, function(results) {
            results = results.map(this.prepareResult);
            callback(results);
        }.bind(this));
    };

    /**
     * Parse results before returning them
     *
     * @param result
     * @returns {{name: *, bounds: o.LatLngBounds, location: o.LatLng}}
     */
    Geocoder.prototype.prepareResult = function (result) {

        var boundingBox = result.boundingbox,
            northEastLatLng = new L.LatLng( boundingBox[1], boundingBox[3] ),
            southWestLatLng = new L.LatLng( boundingBox[0], boundingBox[2] );

        var processedResult = {
            name   : result.display_name,
            bounds : new L.LatLngBounds([
                northEastLatLng,
                southWestLatLng
            ]),
            location: new L.LatLng(result.lat,result.lon)
        };

        return processedResult;
    };

    /**
     * Filter a markerlist to return only those being on a radius around a given position.
     *
     * @param markersList    The marker lists
     * @param centerPosition The center position
     * @param radius         The radius to check, in meters
     *
     * @returns {Array}
     */
    Geocoder.prototype.filterMarkersListByPositionRadius = function (markersList, centerPosition, radius) {

        var center = new L.LatLng(centerPosition.lat, centerPosition.lng);
        var list = [];

        markersList.forEach(function(marker) {
            var itemPosition = new L.LatLng(marker.latitude, marker.longitude);
            var distance = itemPosition.distanceTo(center);
            if (distance <= radius) {
                marker.distance(distance);
                list.push(marker);
            }
        }, this);

        return list;
    };

    /**
     * Retrieve User Geolocalization. Used as a fallback when navigator.geolocation.getCurrentPosition fails
     *
     * @param callback
     */
    Geocoder.prototype.geoLocalizeViaApi = function (callback) {
        $.getJSON(getGeolocalizeApi(this.options), function(success) {callback({coords: {latitude: success.latitude, longitude: success.longitude}})});
    };

    /**
     * Retrieve address data by latitude / longitude
     *
     * @param {Number} latitude
     * @param {Number} longitude
     * @param {Function} callback
     *
     * @return {jqXHR}
     */
    Geocoder.prototype.getAddressByLatLng = function (latitude, longitude, callback) {
        return $.getJSON(BASE_API_URL + '/reverse?format=json&lat=' + latitude + '&lon=' + longitude, function (resp) {
            if (resp.hasOwnProperty('error') || !resp.hasOwnProperty('address') || !resp.hasOwnProperty('lat') ||
                !resp.hasOwnProperty('lon')
            ) {
                callback({successResponse: false});

                return;
            }

            let address = resp.address;
            let city = address.hasOwnProperty('city') ?
                address.city : (address.hasOwnProperty('village') ? address.village : '');

            callback({
                successResponse: true,
                address: new GeoAddressModel({
                    countryCode: address.hasOwnProperty('country_code') ? address.country_code : '',
                    country: address.hasOwnProperty('country') ? address.country : '',
                    city: city,
                    postCode: address.hasOwnProperty('postcode') ? address.postcode : '',
                    street: address.hasOwnProperty('road') ? address.road : '',
                    streetNumber: address.hasOwnProperty('house_number') ? address.house_number : '',
                    position: {
                        latitude: resp.hasOwnProperty('lat') ? resp.lat : '',
                        longitude: resp.hasOwnProperty('lon') ? resp.lon : ''
                    }
                })
            });
        }).fail(function () {
            callback({successResponse: false});
        });
    };

    /**
     * Immutable provider for Geocoder retrieval.
     *
     * @type {{init: Provider.init, getGeocoder: Provider.getGeocoder}}
     */
    Provider = {
        init: function(config, callback) {
            require(['leaflet-geosearch-osm'], function() {
                Provider.geocoder = new Geocoder(config);
                if (callback !== undefined) {
                    return callback();
                }
            });
        },

        getGeocoder: function() {
            return Provider.geocoder;
        }
    };

    return Provider;
});
