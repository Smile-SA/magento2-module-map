define(['jquery', 'leaflet'], function ($, L) {

    var geocoder = null;
    
    function Geocoder(options) {
        this.options = options;
    };

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
    }
    
    return Geocoder;
});