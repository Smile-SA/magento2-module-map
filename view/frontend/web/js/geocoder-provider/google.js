define(['jquery', 'leaflet'], function ($, L) {

    /**
     * Retrieve Google Maps API
     *
     * @param config the config (contains API Key, country, etc...).
     *
     * @returns {string}
     */
    function getApiUrl(config) {
        var apiKey = config['api_key'];
        var country = config['country'] || 'FR';
        var locale = config['locale'] || 'fr_FR';

        return '//maps.google.com/maps/api/js?key=' + apiKey + '&libraries=geometry&language=' + locale + '&country=' + country;
    }

    /**
     * Retrieve Google Geolocalization API URL
     *
     * @param config the config (contains API Key, country, etc...).
     *
     * @returns {string}
     */
    function getGeolocalizeApi(config) {
        var apiKey = config['api_key'];
        return 'https://www.googleapis.com/geolocation/v1/geolocate?key=' + apiKey;
    }

    var geocoder = null;

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
        if (geocoder === null && google && google.maps) {
            geocoder = new google.maps.Geocoder();
        } else if (geocoder === null) {
            throw __('Google Maps API is not ready yet.')
        }

        var request = {address: queryText, region: 'FR'};

        geocoder.geocode(request, function(results) {
            results = results.map(this.prepareResult);

            if (options['bounds']) {
                results = results.filter(function(result) { return options['bounds'].contains(result.location); });
            }

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
        var processedResult = {
            name   : result['address_components'][0]['short_name'],
            bounds : new L.LatLngBounds(
                {lat: result['geometry']['bounds'].getNorthEast().lat(), lng: result['geometry']['bounds'].getNorthEast().lng()},
                {lat: result['geometry']['bounds'].getSouthWest().lat(), lng: result['geometry']['bounds'].getSouthWest().lng()}
            ),
            location: new L.LatLng(result['geometry']['location'].lat(), result['geometry']['location'].lng())
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

        var center = new google.maps.LatLng(centerPosition.lat, centerPosition.lng);
        var list = [];

        markersList.forEach(function(marker) {
            var itemPosition = new google.maps.LatLng(marker.latitude, marker.longitude);
            var distance = google.maps.geometry.spherical.computeDistanceBetween(itemPosition, center);
            if (distance <= radius) {
                marker.distance = distance;
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
        $.post(getGeolocalizeApi(this.options), function(success) {callback({coords: {latitude: success.location.lat, longitude: success.location.lng}})});
    };

    /**
     * Immutable provider for Geocoder retrieval.
     *
     * @type {{init: Provider.init, getGeocoder: Provider.getGeocoder}}
     */
    Provider = {
        init: function(config, callback) {
            require([getApiUrl(config)], function() {
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
