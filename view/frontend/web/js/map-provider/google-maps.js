define([
    'jquery',
    'leaflet',
    'google-mutant',
    'leaflet-geosearch',
    'leaflet-geosearch-google'
], function ($, L) {

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
        var libraries = config['libraries'] || 'geometry';

        return '//maps.google.com/maps/api/js?key=' + apiKey + '&libraries=' + libraries + '&language=' + locale + '&country=' + country;
    }

    function addGoogleMapsLayer(map, config) {
        var mutantConfig = {type: config['type'] || 'roadmap'};
        if (config['map_styles'] && (config['map_styles'] !== '')) {
            mutantConfig.styles = JSON.parse(config['map_styles'].replace(/(\r\n|\n|\r)/gm,""));
        }
        L.gridLayer.googleMutant(mutantConfig).addTo(map);
    }

    Provider = {
        init: function(map, config, callback) {
            require([getApiUrl(config)], function() {
                if (map !== null) {
                    addGoogleMapsLayer(map, config);
                }
                if (callback !== undefined) {
                    callback(map);
                }
            });
        },

        /**
         * Add distance from center of map to a given list of markers
         *
         * @param markersList
         * @param centerPosition
         * @returns {*}
         */
        addDistanceToMarkers: function (markersList, centerPosition) {
            var center = new google.maps.LatLng(centerPosition.lat, centerPosition.lng);
            markersList.forEach(function(marker) {
                var itemPosition = new google.maps.LatLng(marker.latitude, marker.longitude);
                marker.distance(google.maps.geometry.spherical.computeDistanceBetween(itemPosition, center));
            }, this);

            return markersList;
        }
    };

    return Provider;
});
