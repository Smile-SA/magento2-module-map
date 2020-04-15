/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 *
 * @category  Smile
 * @package   Smile\Map
 * @author    Remy LESCALLIER <remy.lescallier@smile.fr>
 * @copyright 2020 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

define(['ko', 'uiClass'], function (ko, Class) {
    'use strict';

    return Class.extend({

        /**
         * {@inheritDoc}
         */
        initialize: function () {
            this._super().initObservable();

            return this;
        },

        /**
         * {@inheritDoc}
         */
        initObservable: function () {
            this.city = ko.observable(this.city);
            this.country = ko.observable(this.country);
            this.countryCode = ko.observable(this.countryCode);
            this.street = ko.observable(this.street);
            this.streetNumber = ko.observable(this.streetNumber);
            this.postCode = ko.observable(this.postCode);
            this.position.latitude = ko.observable(this.position.latitude);
            this.position.longitude = ko.observable(this.position.longitude);

            return this;
        },
    });
});
