
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

    //post header remove
    var post_meta_time = jQuery('#mid > header > div > div > div.post-meta > time')[0];
    var post_meta_author = jQuery('#mid > header > div > div > div.post-meta > a')[0];
    var post_footer = jQuery('#text-bottom');

    // var new_meta = 'by'+ post_meta_author + post_meta_time;
    // console.log(post_meta_author)
    post_footer.append('By');
    post_footer.append(post_meta_author);
    post_footer.append('<br>');
    post_footer.append(post_meta_time);
    jQuery('.single-post > div > #mid > header').remove();

    //work single page tags
    var tag = jQuery('.cpt-categories.work-categories > a');
    var show_tag = jQuery('#tags');
    var div = '<div id="work-tag">';

    jQuery(tag).each(function (index, element) {
        div = div + '<span>' + jQuery(element).html() + '</span></br>';
    });
    div = div + '</div>';
    show_tag.append(div);
    //remove categories after replace with tag
    jQuery('#tags > .elementor-widget-container').remove();
    jQuery('.work-categories').remove();
    jQuery('.cpt-nav').remove();

    //customize people filter list on page '團隊'
    var list_item = jQuery('.people-filter > li');

    list_item.each(function (index, element) {
        var li_text = jQuery(this)[0].lastChild.innerHTML;

        var split_work = li_text.split('/');

        if(split_work[0] === 'works') {
            jQuery(this).remove();
        }
    })

    jQuery('.people-template-default > div > #mid > header').remove()
});

