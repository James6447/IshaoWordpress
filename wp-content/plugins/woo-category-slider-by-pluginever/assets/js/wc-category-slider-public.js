/**
 * WooCommerce Category Slider
 * pluginever.com
 *
 * Copyright (c) 2018 pluginever
 * Licensed under the GPLv2+ license.
 */

/*jslint browser: true */
/*global jQuery:false */


jQuery(document).ready(function ($, window, document, undefined) {
	'use strict';
	$.wc_category_slider_public = {

		init: function () {

			$('.wc-category-slider').each(function (index, el) {
				var config = $(el).data('slider-config');
				$.wc_category_slider_public.initSlider(config, el);
			});

		},

		reInit: function () {
			$('.wc-category-slider').each(function (index, el) {
				var config = $(el).data('slider-config');
				$.wc_category_slider_public.initSlider(config, el, true);

				$(el).trigger('refresh.owl.carousel');
				setTimeout(function () {
					$(el).trigger('refresh.owl.carousel');
				}, 350);
			});
		},

		initSlider: function (config, el, reinit ) {
			reinit = reinit || false;
			config.onInitialized = function (event) {

				var slider = $(event.currentTarget);

				if (slider.hasClass('single-slide')) {
					return false;

				}

				var maxHeight = 0;
				slider.find('.owl-item.active').each(function () {

					slider.imagesLoaded(function () {

						var thisHeight = parseInt(slider.find('img').height(), 10);
						maxHeight = (maxHeight >= thisHeight ? maxHeight : thisHeight);

						if (maxHeight < 250) {
							maxHeight = 250;
						}
						console.log(maxHeight);
						slider.find('.wc-slide-image-wrapper').css('height', maxHeight);
					});
				});


				slider.find('.wc-slide').css('border-width', 0);
				slider.find('.wc-slide').css('border-width', '1px');

			};

			if (reinit) {
				$(el).owlCarousel('destroy');
				$(el).owlCarousel(config);
			} else {
				$(el).owlCarousel(config);
			}
		}
	};


	$.wc_category_slider_public.init();
});
