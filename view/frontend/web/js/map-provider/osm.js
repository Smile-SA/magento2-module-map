define([
    'jquery',
    'leaflet'
], function ($, L) {

    Provider = {
        init: function(map, config, cb) {
            L.tileLayer(config['tile_url']).addTo(map);
            if (cb !== undefined) {
                cb(map);
            }
        },

        /**
         * Add distance from center of map to a given list of markers
         *
         * @param markersList
         * @param centerPosition
         * @returns {*}
         */
        addDistanceToMarkers: function (markersList, centerPosition) {
            var center = new L.LatLng(centerPosition.lat, centerPosition.lng);
            markersList.forEach(function(marker) {
                var itemPosition = new L.LatLng(marker.latitude, marker.longitude);
                marker.distance(itemPosition.distanceTo(center));
            }, this);

            return markersList;
        }
    };

    return Provider;
});
