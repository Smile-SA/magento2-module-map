/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 *
 * @category  Smile
 * @package   Smile\Map
 * @author    Ihor KVASNYTSKYI <ihor.kvasnytskyi@smile-ukraine.com>
 * @copyright 2019 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
/*global define*/
define([
    'jquery',
    'smile-map'
], function ($) {
    'use strict';
    $.widget('smile.listItemEvent', {

        _create: function () {
            
            this.element.on('mouseover', function () {
                this.removeCurrentClass();
                this.showCurrentMarker();
            }.bind(this));
            this.element.on('mouseout', function () {
                this.removeCurrentClass();
            }.bind(this));

        },

        removeCurrentClass: function () {
            $('body').find('.custum-lf-popup').removeClass('current');
        },

        showCurrentMarker: function () {
            var name = this.element.attr('data-shop-name');
            $('.custum-lf-popup[data-n="' + name + '" ]').addClass('current');
        }
    });

    return $.smile.listItemEvent;
});