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
define(['ko', 'uiClass'], function(ko, Class) {
    "use strict";

    return Class.extend({

        initialize: function () {
            this._super()
                .initObservable();

            return this;
        },

        initObservable: function () {
            this.items = ko.observableArray(this.items);
            return this;
        },

        getList: function() {
            return this.items();
        }
    });
});
