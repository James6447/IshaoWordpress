<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

class class_post_grid_support{
	
	public function __construct(){

	}

    public function our_plugins(){

        $our_plugins = array(
            array(
                'title'=>'Post Grid',
                'link'=>'http://www.pickplugins.com/item/post-grid-create-awesome-grid-from-any-post-type-for-wordpress/',
                'thumb'=>'https://www.pickplugins.com/wp-content/uploads/2015/12/3814-post-grid-thumb-500x262.jpg',
            ),

            array(
                'title'=>'Woocommerce Products Slider',
                'link'=>'http://www.pickplugins.com/item/woocommerce-products-slider-for-wordpress/',
                'thumb'=>'https://www.pickplugins.com/wp-content/uploads/2016/03/4357-woocommerce-products-slider-thumb-500x250.jpg',
            ),

            array(
                'title'=>'Team Showcase',
                'link'=>'http://www.pickplugins.com/item/team-responsive-meet-the-team-grid-for-wordpress/',
                'thumb'=>'https://www.pickplugins.com/wp-content/uploads/2016/06/5145-team-thumb-500x250.jpg',
            ),

            array(
                'title'=>'Job Board Manager',
                'link'=>'https://wordpress.org/plugins/job-board-manager/',
                'thumb'=>'https://www.pickplugins.com/wp-content/uploads/2015/08/3466-job-board-manager-thumb-500x250.png',
            ),

            array(
                'title'=>'Wishlist for WooCommerce',
                'link'=>'https://www.pickplugins.com/item/woocommerce-wishlist/',
                'thumb'=>'https://www.pickplugins.com/wp-content/uploads/2017/10/12047-woocommerce-wishlist.png',
            ),

            array(
                'title'=>'Breadcrumb',
                'link'=>'https://www.pickplugins.com/item/breadcrumb-awesome-breadcrumbs-style-navigation-for-wordpress/',
                'thumb'=>'https://www.pickplugins.com/wp-content/uploads/2016/03/4242-breadcrumb-500x252.png',
            ),

            array(
                'title'=>'Pricing Table',
                'link'=>'https://www.pickplugins.com/item/pricing-table/',
                'thumb'=>'https://www.pickplugins.com/wp-content/uploads/2016/10/7042-pricing-table-thumbnail-500x250.png',
            ),

        );

        return apply_filters('post_grid_our_plugins', $our_plugins);


    }


    public function video_tutorials(){

        $tutorials = array(
            array(
                'question'=>__('How to install activate & license', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=gzH0uO6IReE',
            ),
            array(
                'question'=>__('How to create Post Grid', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=6HwLUBqT7i4',
            ),
            array(
                'question'=>__('How to activate license?', 'post-grid'),
                'answer_url'=>'https://www.pickplugins.com/documentation/woocommerce-products-slider/faq/activate-license/',
            ),
        );

        return apply_filters('post_grid_video_tutorials', $tutorials);


    }



    public function faq(){
        $faq = array(
            array(
                'question'=>__('How to Create a Post Grid?', 'post-grid'),
                'answer_url'=>'https://www.pickplugins.com/documentation/post-grid/faq/how-to-create-a-post-grid/',
            ),
            array(
                'question'=>__('How to upgrade to premium?', 'post-grid'),
                'answer_url'=>'https://www.pickplugins.com/documentation/post-grid/upgrade-to-premium/',
            ),

//            array(
//                'question'=>__('How to activate license?', 'post-grid'),
//                'answer_url'=>'https://www.pickplugins.com/documentation/woocommerce-products-slider/faq/activate-license/',
//            ),

            array(
                'question'=>__('Post grid on archive page?', 'post-grid'),
                'answer_url'=>'https://www.pickplugins.com/documentation/post-grid/faq/post-grid-for-archive-page/',
            ),


            array(
                'question'=>__('How to display HTML/Shortcode via layout editor ?', 'post-grid'),
                'answer_url'=>'https://www.pickplugins.com/documentation/post-grid/faq/layout-editor-how-at-add-htmlshortcode/',
            ),
        );


        return apply_filters('post_grid_faq', $faq);



    }





	
	
}

