(function($){
"use strict";

   
    var WidgetProductSliderHandler = function ($scope, $) {

        var slider_elem = $scope.find('.product-slider').eq(0);

        if (slider_elem.length > 0) {

            var settings = slider_elem.data('settings');
            var arrows = settings['arrows'];
            var dots = settings['dots'];
            var autoplay = settings['autoplay'];
            var rtl = settings['rtl'];
            var autoplay_speed = parseInt(settings['autoplay_speed']) || 3000;
            var animation_speed = parseInt(settings['animation_speed']) || 300;
            var fade = settings['fade'];
            var pause_on_hover = settings['pause_on_hover'];
            var display_columns = parseInt(settings['product_items']) || 4;
            var scroll_columns = parseInt(settings['scroll_columns']) || 4;
            var tablet_width = parseInt(settings['tablet_width']) || 800;
            var tablet_display_columns = parseInt(settings['tablet_display_columns']) || 2;
            var tablet_scroll_columns = parseInt(settings['tablet_scroll_columns']) || 2;
            var mobile_width = parseInt(settings['mobile_width']) || 480;
            var mobile_display_columns = parseInt(settings['mobile_display_columns']) || 1;
            var mobile_scroll_columns = parseInt(settings['mobile_scroll_columns']) || 1;

            slider_elem.slick({
                arrows: arrows,
                prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
                dots: dots,
                infinite: true,
                autoplay: autoplay,
                autoplaySpeed: autoplay_speed,
                speed: animation_speed,
                fade: false,
                pauseOnHover: pause_on_hover,
                slidesToShow: display_columns,
                slidesToScroll: scroll_columns,
                rtl: rtl,
                responsive: [
                    {
                        breakpoint: tablet_width,
                        settings: {
                            slidesToShow: tablet_display_columns,
                            slidesToScroll: tablet_scroll_columns
                        }
                    },
                    {
                        breakpoint: mobile_width,
                        settings: {
                            slidesToShow: mobile_display_columns,
                            slidesToScroll: mobile_scroll_columns
                        }
                    }
                ]
            });
        };
    };

    // Custom Tab
    function woolentor_tabs( $tabmenus, $tabpane ){
        $tabmenus.on('click', 'a', function(e){
            e.preventDefault();
            var $this = $(this),
                $target = $this.attr('href');
            $this.addClass('htactive').parent().siblings().children('a').removeClass('htactive');
            $( $tabpane + $target ).addClass('htactive').siblings().removeClass('htactive');
        });
    }

    /* 
    * Universal product 
    */
    var WidgetThumbnaisImagesHandler = function thumbnailsimagescontroller(){
        $('.ht-product-image-slider').slick({
            dots: true,
            arrows: true,
            prevArrow: '<button class="slick-prev"><i class="sli sli-arrow-left"></i></button>',
            nextArrow: '<button class="slick-next"><i class="sli sli-arrow-right"></i></button>',
        });
        woolentor_tabs( $(".ht-product-cus-tab-links"), '.ht-product-cus-tab-pane' );
        woolentor_tabs( $(".ht-tab-menus"), '.ht-tab-pane' );

        // Countdown
        var finalTime, daysTime, hours, minutes, second;
        $('.ht-product-countdown').each(function() {
            var $this = $(this), finalDate = $(this).data('countdown');
            var customlavel = $(this).data('customlavel');
            $this.countdown(finalDate, function(event) {
                $this.html(event.strftime('<div class="cd-single"><div class="cd-single-inner"><h3>%D</h3><p>'+customlavel.daytxt+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%H</h3><p>'+customlavel.hourtxt+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%M</h3><p>'+customlavel.minutestxt+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%S</h3><p>'+customlavel.secondstxt+'</p></div></div>'));
            });
        });

        // Slick Slider Action Wit Bootstrap Tab
        $('[data-toggle="tab"]').on('shown.bs.tab', function(e) {
             var $this = $(this),
                 $id = $this.attr('href');
             $($id).find('.slick-slider').slick('refresh');
         });

    }

    // Quickview slider
    function quickViewMainImageSlider(){
        $('.ht-quick-view-learg-img').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.ht-quick-view-thumbnails'
        });
    }
    function quickViewThumb(){
        $('.ht-quick-view-thumbnails').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: '.ht-quick-view-learg-img',
            dots: false,
            arrows: true,
            focusOnSelect: true,
            prevArrow: '<button class="woolentor-slick-prev"><i class="sli sli-arrow-left"></i></button>',
            nextArrow: '<button class="woolentor-slick-next"><i class="sli sli-arrow-right"></i></button>',
        });
    }

    //Tool-tips
    function woolentor_tool_tips(element, content) {
        if (content == 'html') {
            var tipText = element.html();
        } else {
            var tipText = element.attr('title');
        }
        element.on('mouseover', function() {
            if ($('.woolentor-tip').length == 0) {
                element.before('<span class="woolentor-tip">' + tipText + '</span>');
                $('.woolentor-tip').css('transition', 'all 0.5s ease 0s');
                $('.woolentor-tip').css('margin-left', 0);
            }
        });
        element.on('mouseleave', function() {
            $('.woolentor-tip').remove();
        });
    }

    //Tooltip
    var WidgetWoolentorTooltipHandler = function woolentor_tool_tip(){
        $('a.woolentor-compare').each(function() {
            woolentor_tool_tips($(this), 'title');
        });
        $('.woolentor-cart a.add_to_cart_button,.woolentor-cart a.added_to_cart,.woolentor-cart a.button').each(function() {
            woolentor_tool_tips($(this), 'html');
        });
    }

    //Add quick view box
    $('body').append('<div class="woocommerce" id="ht-quick-viewmodal"><div class="ht-modal-dialog product"><div class="ht-modal-content"><button type="button" class="htcloseqv"><span class="sli sli-close"></span></button><div class="ht-modal-body"></div></div></div></div>');
    //show quick view
    $('.quickview').each(function(){
        var quickviewLink = $(this);
        var productID = quickviewLink.attr('data-quick-id');
        quickviewLink.on('click', function(event){
            event.preventDefault();
            $('.ht-modal-body').html(''); /*clear content*/
            $('body').addClass('quickview');
            $('#ht-quick-viewmodal').addClass('quickview-open');
            window.setTimeout(function(){
                $.post(
                    woolentor_addons.woolentorajaxurl, 
                    {
                      'action': 'woolentor_product_quickview',
                      'data':   productID
                    },
                    function(response){
                        $('.ht-modal-body').html(response);
                        quickViewMainImageSlider();
                        quickViewThumb();
                    }
                );
            }, 100);

        });
    });
    $('.htcloseqv').on('click', function(event){
        $('#ht-quick-viewmodal').removeClass('quickview-open');
        window.setTimeout(function(){
            $('body').removeClass('quickview');
        }, 500);
    });

    var  WidgetProducttabsHandler = woolentor_tabs( $(".ht-tab-menus"),'.ht-tab-pane' );
    // Run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-product-tab.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-product-tab.default', WidgetProducttabsHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-universal-product.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-universal-product.default', WidgetWoolentorTooltipHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-universal-product.default', WidgetThumbnaisImagesHandler);
    });


})(jQuery);