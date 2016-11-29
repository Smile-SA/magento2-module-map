define([
    'jquery',
    'leaflet',
    'google-mutant',
    'leaflet-geosearch',
    'leaflet-geosearch-google',
], function ($, L) {

    
    function getApiUrl(apiKey) {
        return 'http://maps.google.com/maps/api/js?key=' + apiKey + '&language=fr_FR&country=FR';
    }

    function addGoogleMapsLayer(map, type) {
        L.gridLayer.googleMutant({type: type}).addTo(map);
    }
    
    function initGeocoder(map) {
        return new L.GeoSearch.Provider.Google();
    }
    
    Provider = {
        init: function(map, config, cb) {
            require([getApiUrl(config['api_key'])], function() {
                addGoogleMapsLayer(map, config['type']);
                Provider.geocoder = initGeocoder();
                if (cb !== undefined) {
                    cb(map);
                }
            });
        },

        getGeocoder: function(map, config) {
            return Provider.geocoder;
        }
    }

    return Provider;
});