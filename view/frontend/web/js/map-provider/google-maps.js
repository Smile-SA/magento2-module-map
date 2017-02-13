define([
    'jquery',
    'leaflet',
    'google-mutant',
    'leaflet-geosearch',
    'leaflet-geosearch-google',
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

        return '//maps.google.com/maps/api/js?key=' + apiKey + '&libraries=geometry&language=' + locale + '&country=' + country;
    }

    function addGoogleMapsLayer(map, type) {
        L.gridLayer.googleMutant({type: type}).addTo(map);
    }

    Provider = {
        init: function(map, config, callback) {
            require([getApiUrl(config)], function() {
                if (map !== null) {
                    addGoogleMapsLayer(map, config['type']);
                }
                if (callback !== undefined) {
                    callback(map);
                }
            });
        }
    };

    return Provider;
});
