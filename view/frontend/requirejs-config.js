/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Smile Elastic Suite to newer
 * versions in the future.
 *
 *
 * @category  Smile
 * @package   Smile\StoreLocator
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @author    Guillaume Vrac <guillaume.vrac@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

var config = {
    map: {
        '*': {
            'smile-map'                 : 'Smile_Map/js/map',
            'smile-map-provider-osm'    : 'Smile_Map/js/map-provider/osm',
            'smile-map-provider-google' : 'Smile_Map/js/map-provider/google-maps'
        }
    },
    paths: {
        'leaflet'                   : 'Smile_Map/leaflet/leaflet',
        'leaflet-geosearch'         : 'Smile_Map/leaflet/plugins/geosearch/l.control.geosearch',
        'leaflet-geosearch-osm'     : 'Smile_Map/leaflet/plugins/geosearch/l.geosearch.provider.openstreetmap',
        'leaflet-geosearch-google'  : 'Smile_Map/leaflet/plugins/geosearch/l.geosearch.provider.google',
        'google-mutant'             : 'Smile_Map/leaflet/google-mutant',
    },
    shim: {
        'leaflet': {
            exports : 'L'
        },
        'leaflet-geosearch': {
            deps: ['leaflet']
        },
        'leaflet-geosearch-osm': {
            deps: ['leaflet', 'leaflet-geosearch']
        },
        'leaflet-geosearch-google': {
            deps: ['leaflet', 'leaflet-geosearch']
        },
        'google-mutant': {
            deps: ['leaflet']
        },
        'smile-map-provider-osm': {
            deps: ['leaflet-geosearch-osm']
        },
        'smile-map-provider-google': {
            deps: ['google-mutant', 'leaflet-geosearch-google']
        }
    }
};
