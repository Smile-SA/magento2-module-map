define([
    'jquery',
    'leaflet'
], function ($, L) {

    Provider = {
        init: function(map, config, cb) {
            L.tileLayer(config['tile_url']).addTo(map);
            if (cb !== undefined) {
                cb(map);
            }
        }
    }

    return Provider;
});