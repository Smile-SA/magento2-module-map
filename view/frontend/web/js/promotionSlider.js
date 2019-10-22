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
    'slick'
], function ($, slick) {
    'use strict';
    $.widget('smile.promotionSlider', {

        _create: function () {
            if(window.innerWidth < 768) {
                this.sliderActive();
            }
        },

        sliderActive: function () {
            this.element.slick({
                dots: false,
                arrows: false,
                infinite: false,
                autoplay: false,
                speed: 300,
                slidesToShow: 1.2,
                slidesToScroll: 1
            });
        }
    });

    return $.smile.promotionSlider;
});