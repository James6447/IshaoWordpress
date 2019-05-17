
jQuery(document).ready(function() {
    //for clicking logo to page as main page
    var is_main = window.location.search;
    var logo_a = jQuery('#header > div > div.logo-container > a');

    if(is_main === ''){
        logo_a.addClass('logo-title-a');
    }else {
        logo_a.removeClass('logo-title-a');
    }

    //for the title beside of the logo to change color when scrolling down the page
    jQuery( window ).scroll(function() {
        var window_height = window.pageYOffset;
        var logo_title = jQuery('#header > div > div.logo-container > p');

        if(window_height >= 200){
            logo_title.addClass('logo-title');
        }else {
            logo_title.removeClass('logo-title');
        }
    });

    //re-arrange for the work page list
    var postList = jQuery('.special-post-list div div .pt-cv-view div .pt-cv-content-item');
    var lenght = postList.length;

    postList.each(function(index, element) {
       jQuery(this).removeClass();

       if(index === 0) {
           jQuery(this).addClass("col-md-12 col-sm-2 col-xs-12 pt-cv-content-item pt-cv-1-col special-post float-left");
       }else if(index === (lenght - 1) ) {
           jQuery(this).addClass("col-md-12 col-sm-2 col-xs-12 pt-cv-content-item pt-cv-1-col special-post float-right");
       }else {
               jQuery(this).addClass("col-md-3 col-sm-2 col-xs-12 pt-cv-content-item pt-cv-1-col");
       }

    });
});

