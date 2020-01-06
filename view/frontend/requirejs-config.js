/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 *
 * @category  Smile
 * @package   Smile\Map
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @author    Guillaume Vrac <guillaume.vrac@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

var config = {
    map: {
        '*': {
            'smile-map'                      : 'Smile_Map/js/map',
            'smile-map-markers'              : 'Smile_Map/js/model/markers',
            'smile-map-provider-osm'         : 'Smile_Map/js/map-provider/osm',
            'smile-map-provider-google'      : 'Smile_Map/js/map-provider/google-maps',
            'smile-geocoder'                 : 'Smile_Map/js/geocoder',
            'smile-geocoder-provider-osm'    : 'Smile_Map/js/geocoder-provider/osm',
            'smile-geocoder-provider-google' : 'Smile_Map/js/geocoder-provider/google',
            'listItemEvent'                  : 'Smile_Map/js/listItemEvent',
            'mapMobile'                      : 'Smile_Map/js/mapMobile',
            'slick'                          : 'Smile_Map/js/lib/slick.min',
            'promotionSlider'                : 'Smile_Map/js/promotionSlider'
        }
    },
    config: {
        mixins: {
            'mage/menu': {
                'Smile_Map/js/lib/mage/menu-mixin': true
            }
        }
    },


    paths: {
        'leaflet'                   : 'Smile_Map/leaflet/leaflet',
        'leaflet-geosearch'         : 'Smile_Map/leaflet/plugins/geosearch/l.control.geosearch',
        'leaflet-geosearch-osm'     : 'Smile_Map/leaflet/plugins/geosearch/l.geosearch.provider.openstreetmap',
        'leaflet-geosearch-google'  : 'Smile_Map/leaflet/plugins/geosearch/l.geosearch.provider.google',
        'leaflet-markercluster'     : 'Smile_Map/leaflet/plugins/markercluster/leaflet.markercluster',
        'google-mutant'             : 'Smile_Map/leaflet/google-mutant'
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
        'leaflet-markercluster': {
            deps: ['leaflet']
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
