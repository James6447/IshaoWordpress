/**
 * woocommerce-category-slider - v3.1.0 - 2019-01-01
 * https://pluginever.com/woo-category-slider
 *
 * Copyright (c) 2019;
 * Licensed GPLv2+
 */

window.WooCommerce_Category_Slider_Public=function(i,e,n,r){"use strict";var t={initialize:function(){n(".ever-category-slider").each(function(i,e){var r=n(e).data("slider-config");WooCommerce_Category_Slider_Public.initSlider(r,e)}),n(".ever-slider-image-wrapper").imgLiquid({fill:!0,horizontalAlign:"center",verticalAlign:"center"})},initSlider:function(i,e){i.onInitialized=function(i){var e=n(i.currentTarget);if(e.hasClass("single-slide"))return!1;var r=0;e.find(".owl-item.active").each(function(){var i=parseInt(e.find("img").height());r=i<=r?r:i}),r<300&&(r=300),e.find(".ever-slider-image-wrapper").css("height",r),e.find(".ever-slider-item").css("border-width",0),e.find(".ever-slider-item").css("border-width","1px")},i.onResized=function(i){var e=n(i.currentTarget);if(e.hasClass("single-slide"))return!1;var r=0;e.find(".owl-item.active").each(function(){var i=parseInt(e.find("img").height());r=i<=r?r:i}),r<300&&(r=300),e.find(".ever-slider-image-wrapper").css("height",r)},i.onTranslated=function(i){var e=n(i);if(e.hasClass("single-slide"))return!1;var r=0;e.find(".owl-item.active").each(function(){var i=parseInt(e.find("img").height());r=i<=r?r:i}),r<300&&(r=300),e.find(".ever-slider-image-wrapper").css("height",r)},n(e).owlCarousel(i)}};return n(e).ready(t.initialize),t}(window,document,jQuery);