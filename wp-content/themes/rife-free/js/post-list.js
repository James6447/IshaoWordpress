
jQuery(document).ready(function() {
    //--------------------//
    //***********translate google**********//

    setTimeout(function(){
        var g_frames_before = window.top.document.getElementById(":0.container");

        if( !g_frames_after ) {
            var active = g_frames_before.contentDocument.getElementById(':0.translate');
            active.click();
        }

        var g_frames_after = document.getElementById(":1.container");
        g_frames_after.style.visibility = 'hidden';
        g_frames_before.style.visibility = 'hidden';
    }, 5000);

    jQuery('#header-tools > a').on('click', function () {
        var g_frames_after = document.getElementById(":1.container");
        // var language = g_frames_after.contentDocument.getElementById(":1.finishSection").getElementsByClassName('goog-te-button')[0].getElementsByTagName("div")[0].getElementsByTagName("button")[0].getAttribute("id");
        var tmp = g_frames_after.contentDocument.getElementsByTagName("table")[1];
        var now_section = tmp.children[0];
        var language = "";
        var button;

        Array.prototype.forEach.call(now_section.children, child => {
            if(child.style.display !== 'none'){
                language = child.getElementsByClassName("goog-te-button")[0].getElementsByTagName("div")[0].getElementsByTagName("button")[0].getAttribute("id");
            }
        });

        switch (language) {
            case ':1.restore':
                this.innerHTML = 'ENGLISH →';
                button = g_frames_after.contentDocument.getElementById(':1.restore');
                break;
            case ':1.confirm':
                this.innerHTML = '中文 →';
                button = g_frames_after.contentDocument.getElementById(':1.confirm');
                break;
        }
        button.click();
        g_frames_after.style.visibility = 'hidden';
    });
    //--------END translate google------------//


    //--------------------//
    //***********for clicking logo to page as main page**********//
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
    //--------------------//


    //--------------------//
    //***********re-arrange for the work page list**********//
    var postList = jQuery('#special-post-list > div > div > div > div.archive-item');
    var length = postList.length;

    postList.each(function(index, element) {
        if(index === 0) {
            jQuery(this).addClass("first-special-post");
            jQuery(jQuery(element)[0].children[1].children[0].children[2].children[1].children[0]).addClass('first-post-link');
        }else if(index === (length - 1) ) {
            jQuery(this).addClass("last-special-post");
            jQuery(jQuery(element)[0].children[1].children[0].children[2].children[1].children[0]).addClass('last-post-link');
        }else{
            jQuery(jQuery(element)[0].children[1]).addClass('normal-formatter')
            jQuery(jQuery(element)[0].children[1].children[0].children[2].children[1].children[0]).addClass('normal-post-link');
        }

        jQuery('.posts-filter > li[data-filter="__all"] > a').text('所有簡文章')
        jQuery(jQuery(element)[0].children[1].children[0].children[2].children[1].children[0]).html('&rarr;');

        var special_post_time = jQuery(element)[0].children[1].children[0].children[0].children[0];
        var special_post_author = jQuery(element)[0].children[1].children[0].children[0].children[1];
        var new_special_post = '<div class="post-meta"> By ' +
            special_post_author.innerHTML + '<br>' +
            special_post_time.innerHTML +
            '</div>';

        //switch meta to lowest place
        jQuery(element)[0].children[1].children[0].children[0].remove();
        jQuery(element)[0].children[1].children[0].children[1].after(jQuery(new_special_post)[0]);

    });
    // jQuery('.last-special-post > div.formatter > div').append(post_meta_details[0].outerHTML);
    // post_meta_details.remove();

    //---------END re-arrange for the work page list-----------//


    var post_meta_time = jQuery('#mid > header > div > div > div.post-meta > time')[0];
    var post_meta_author = jQuery('#mid > header > div > div > div.post-meta > a')[0];
    var post_footer = jQuery('#text-bottom');

    post_footer.append('By');
    post_footer.append(post_meta_author);
    post_footer.append('<br>');
    post_footer.append(post_meta_time);
    jQuery('.single-post > div > #mid > header').remove();

    //--------------------//
    //***********Work single page tags**********//
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
    //---------END post single page header remove*-----------//


    //--------------------//
    //***********customize people filter list on page '團隊'**********//
    var list_item = jQuery('.people-filter > li');

    list_item.each(function (index, element) {
        var li_text = jQuery(this)[0].lastChild.innerHTML;

        var split_work = li_text.split('/');

        if(split_work[0] === 'works') {
            jQuery(this).remove();
        }
    })

    jQuery('.people-template-default > div > #mid > header').remove()
    //---------END customize people filter list on page '團隊'-----------//


    //--------------------//
    //***********customize people-single page button**********//
    var page_option = '<div class="custom-select">' +
        '<select>' +
        '<option value="1"> 1 </option>' +
        '</select>' +
        ' </div>';

    jQuery('#smooth_slider_3').append(page_option);

    setTimeout(function(){
        var nav_total = jQuery('#smooth_slider_3_nav > a').length;
        var option = '';

        for (let i=1; i<=nav_total; i++){
            if( i === 1 ){
                option = option + '<option value="' + i + '">' + i + ' of ' + nav_total + '</option>'
            }
            option = option + '<option value="' + i + '">' + i + ' of ' + nav_total + '</option>'
        }

        jQuery('.custom-select > select').html(option);
    }, 200);

    jQuery('#smooth_slider_3_next').html('下一頁   &rarr;');
    jQuery('#smooth_slider_3_prev').html('&larr;   上一頁');


    setTimeout(function(){
        var x, i, j, selElmnt, a, b, c;
        /*look for any elements with the class "custom-select":*/
        x = document.getElementsByClassName("custom-select");
        for (i = 0; i < x.length; i++) {
            selElmnt = x[i].getElementsByTagName("select")[0];
            /*for each element, create a new DIV that will act as the selected item:*/
            a = document.createElement("DIV");
            a.setAttribute("class", "select-selected");
            a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
            x[i].appendChild(a);
            /*for each element, create a new DIV that will contain the option list:*/
            b = document.createElement("DIV");
            b.setAttribute("class", "select-items select-hide");
            for (j = 1; j < selElmnt.length; j++) {
                /*for each option in the original select element,
                create a new DIV that will act as an option item:*/
                c = document.createElement("DIV");
                c.innerHTML = selElmnt.options[j].innerHTML;
                c.addEventListener("click", function(e) {
                    /*when an item is clicked, update the original select box,
                    and the selected item:*/
                    var y, i, k, s, h;
                    s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                    h = this.parentNode.previousSibling;
                    for (i = 0; i < s.length; i++) {
                        if (s.options[i].innerHTML == this.innerHTML) {
                            s.selectedIndex = i;
                            h.innerHTML = this.innerHTML;
                            y = this.parentNode.getElementsByClassName("same-as-selected");
                            for (k = 0; k < y.length; k++) {
                                y[k].removeAttribute("class");
                            }
                            this.setAttribute("class", "same-as-selected");
                            break;
                        }
                    }
                    h.click();
                });
                b.appendChild(c);
            }
            x[i].appendChild(b);
            a.addEventListener("click", function(e) {
                /*when the select box is clicked, close any other select boxes,
                and open/close the current select box:*/
                e.stopPropagation();
                closeAllSelect(this);
                this.nextSibling.classList.toggle("select-hide");
                this.classList.toggle("select-arrow-active");
            });
        }
        function closeAllSelect(elmnt) {
            /*a function that will close all select boxes in the document,
            except the current select box:*/
            var x, y, i, arrNo = [];
            x = document.getElementsByClassName("select-items");
            y = document.getElementsByClassName("select-selected");
            for (i = 0; i < y.length; i++) {
                if (elmnt == y[i]) {
                    arrNo.push(i)
                } else {
                    y[i].classList.remove("select-arrow-active");
                }
            }
            for (i = 0; i < x.length; i++) {
                if (arrNo.indexOf(i)) {
                    x[i].classList.add("select-hide");
                }
            }
        }
        /*if the user clicks anywhere outside the select box,
        then close all select boxes:*/
        document.addEventListener("click", closeAllSelect);

        jQuery('.select-items > div').click(function () {
            var selected_nav = this.innerHTML.split(' ');

            console.log(selected_nav[0]);
            jQuery('.smooth_nav > a')[parseInt(selected_nav[0]) - 1].click();
        });
    }, 500);
    //---------END customize people-single page button-----------//


    //--------------------//
    //***********people-single page slider**********//
    var slider = jQuery('.smooth_slideri');
    var tmp = '';
    var new_slider = '';

    slider.each(function (index,element){
        var content = '<div>' + jQuery(this)[0].innerHTML + '</div>';
        if( index%2 ==0 ){
            // 偶数
            tmp = tmp + content;
        }else{
            // 奇数
            tmp = tmp + content;
            new_slider = new_slider + '<div class="smooth_slideri" style="max-width: 382px; margin: 0px 24px; background-color: rgb(0, 0, 0); ' +
                'position: absolute; top: 0px; left: -402px; z-index: 5; opacity: 1; display: none; width: 402px; height: 189px;">\n' +
                '<div class="smooth_slider_content">' +
                tmp +
                '</div>' +
                '</div>';
            tmp = '';
        }
    });
    slider.remove();
    jQuery('.smooth_sliderb').append(new_slider);
    //--------END people-single page slider------------//

    //--------------------//
    //***********Team page people item list**********//
    var people_list = jQuery('.people-grid-container > .archive-item');

    people_list.each(function (index, element){
        var position_text = jQuery(jQuery(element)[0].children[3].children[0].children[0].children[1]).text().split('.');
        var new_position_text = position_text.join('.<br>');
        var new_position = '<div class="subtitle">' +
            new_position_text +
            '</div>';

        jQuery(jQuery(element)[0].children[3].children[0].children[0].children[1]).remove();
        jQuery(jQuery(element)[0].children[3].children[0].children[0]).append(new_position);
    })

    //--------END Team page people item list------------//
});

