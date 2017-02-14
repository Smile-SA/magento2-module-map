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
 * @copyright 2017 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
/*global define*/
define([], function() {
        "use strict";
        var markersList = [];

        return {
            markersList: markersList,

            setList: function (list) {
                this.markersList = list;
            },

            getList: function () {
                return this.markersList;
            },

            filter: function (callback) {
                return callback(this.markersList);
            }
        };
    }
);
