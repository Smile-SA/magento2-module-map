define(['jquery', 'leaflet'], function ($, L) {

    function getServiceUrl(qry) {
        var parameters = L.Util.extend({
            q: qry,
            format: 'json'
        }, this.options);

        return 'https:'
            + '//nominatim.openstreetmap.org/search'
            + L.Util.getParamString(parameters);
    }

    function getGeolocalizeApi() {
        return '//freegeoip.net/json/?callback=?';
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
        var queryUrl = getServiceUrl(queryText);
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
        $.getJSON(getGeolocalizeApi(), function(success) {callback({coords: {latitude: success.latitude, longitude: success.longitude}})});
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
