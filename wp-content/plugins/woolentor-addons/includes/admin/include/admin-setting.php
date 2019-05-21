<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

if ( ! function_exists('is_plugin_active')){ include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }

class Woolentor_Admin_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new Woolentor_Settings_API();

        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 220 );
        add_action( 'wsa_form_bottom_woolentor_themes_library_tabs', array( $this, 'woolentor_html_themes_library_tabs' ) );
        add_action( 'wsa_form_bottom_woolentor_template_library_tabs', array( $this, 'woolentor_html_template_library_tabs' ) );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->woolentor_admin_get_settings_sections() );
        $this->settings_api->set_fields( $this->woolentor_admin_fields_settings() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    // Plugins menu Register
    function admin_menu() {
        add_menu_page( 
            __( 'WooLentor', 'woolentor' ),
            __( 'WooLentor', 'woolentor' ),
            'manage_options',
            'woolentor',
            array ( $this, 'plugin_page' ),
            'dashicons-admin-generic',
            100
        );
    }

    // Options page Section register
    function woolentor_admin_get_settings_sections() {
        $sections = array(
            
            array(
                'id'    => 'woolentor_woo_template_tabs',
                'title' => esc_html__( 'WooCommerce Template', 'woolentor' )
            ),

            array(
                'id'    => 'woolentor_themes_library_tabs',
                'title' => esc_html__( 'Theme Library', 'woolentor' )
            ),

            array(
                'id'    => 'woolentor_template_library_tabs',
                'title' => esc_html__( 'Template Library', 'woolentor' )
            ),

        );
        return $sections;
    }

    // Options page field register
    protected function woolentor_admin_fields_settings() {

        $settings_fields = array(

            'woolentor_woo_template_tabs'=>array(

                array(
                    'name'  => 'enablecustomlayout',
                    'label'  => __( 'Enable Custom Product and Shop Layout', 'woolentor' ),
                    'desc'  => __( 'Enable', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'    => 'singleproductpage',
                    'label'   => __( 'Single Product Template', 'woolentor' ),
                    'desc'    => __( 'You can select Custom Product details layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => woolentor_elementor_template()
                ),

                array(
                    'name'    => 'productarchivepage',
                    'label'   => __( 'Product Archive Page Template', 'woolentor' ),
                    'desc'    => __( 'You can select Custom Product Shop page layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => woolentor_elementor_template()
                ),

            ),

            'woolentor_themes_library_tabs'=>array(),
            'woolentor_template_library_tabs'=>array(),

        );
        
        return array_merge( $settings_fields );
    }


    function plugin_page() {

        echo '<div class="wrap">';
            echo '<h2>'.esc_html__( 'Woolentor Settings','woolentor' ).'</h2>';
            $this->save_message();
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
        echo '</div>';

    }

    function save_message() {
        if( isset($_GET['settings-updated']) ) { ?>
            <div class="updated notice is-dismissible"> 
                <p><strong><?php esc_html_e('Successfully Settings Saved.', 'woolentor') ?></strong></p>
            </div>
            <?php
        }
    }

    // Custom Markup
    function woolentor_html_themes_library_tabs() {
        $output = '<div class="woolentor-themes-laibrary">';
            $output .='<p>Use Our WooCommerce Theme for your online Store.</p>';
            $output .='<div class="woolentor-themes-area"><div class="woolentor-themes-row">';
            $output .='<div class="woolentor-single-theme"><img src="'.WOOLENTOR_ADDONS_PL_URL.'/includes/admin/assets/images/parlo.png" alt="">
                    <div class="woolentor-theme-content">
                        <h3>Parlo - WooCommerce Theme</h3>
                        <a href="http://demo.shrimpthemes.com/1/parlo/" class="woolentor-button" target="_blank">Preview</a>
                        <a href="https://freethemescloud.com/item/parlo-free-woocommerce-theme/" class="woolentor-button">Download</a>
                    </div></div>';
            $output .='</div></div>';
        $output .= '</div>';
        echo $output;
    }

    function woolentor_html_template_library_tabs(){
        $wltemplatelist = '<div class="woolentor-template-laibrary">';
        $wltemplatelist .='<h3>Elementor Template Library</h3>';
        $wltemplatelist .='<p>Use Our Readymade Elementor templates and build your pages easily.</p>';

        $wltemplatelist .='<div class="woolentor-admin-tab-area">';
            $wltemplatelist .='<ul class="woolentor-admin-tabs">';
                $wltemplatelist .='<li><a class="wlactive" href="#homepage">Home Page</a></li>';
                $wltemplatelist .='<li><a href="#shoppage">Shop Page</a></li>';
                $wltemplatelist .='<li><a href="#productdetailspage">Product Details Page</a></li>';
                $wltemplatelist .='<li><a href="#otherspage">Others Page</a></li>';
            $wltemplatelist .='</ul>';
        $wltemplatelist .='</div>';

        $wltemplatelist .='<div class="woolentor-admin-tab-pane wlactive" id="homepage"><div class="woolentor-template-area"><div class="woolentor-themes-row">';
        $wltemplatelist .='<div class="woolentor-single-theme">
                <img src="'.WOOLENTOR_ADDONS_PL_URL.'/includes/admin/assets/images/parlo-home-1.png" alt="">
                <div class="woolentor-theme-content">
                    <h3>Home Page One</h3>
                    <a href="http://demo.shrimpthemes.com/1/parlo/" class="woolentor-button" target="_blank">Preview</a>
                    <a href="https://freethemescloud.com/download/parlo-home-page-one/" class="woolentor-button">Download</a>
                </div></div>';
        $wltemplatelist .='<div class="woolentor-single-theme">
                <img src="'.WOOLENTOR_ADDONS_PL_URL.'/includes/admin/assets/images/parlo-home-2.png" alt="">
                <div class="woolentor-theme-content">
                    <h3>Home Page Two <span>( Pro )</span></h3>
                    <a href="http://demo.shrimpthemes.com/1/parlo/home-two/?footerlayout=1" class="woolentor-button" target="_blank">Preview</a>
                    <a href="https://freethemescloud.com/item/parlo-free-woocommerce-theme/" class="woolentor-button">Download</a>
                </div></div>';
        $wltemplatelist .='<div class="woolentor-single-theme">
                <img src="'.WOOLENTOR_ADDONS_PL_URL.'/includes/admin/assets/images/parlo-home-3.png" alt="">
                <div class="woolentor-theme-content">
                    <h3>Home Page Three <span>( Pro )</span></h3>
                    <a href="http://demo.shrimpthemes.com/1/parlo/home-three/?footerlayout=1" class="woolentor-button" target="_blank">Preview</a>
                    <a href="https://freethemescloud.com/item/parlo-free-woocommerce-theme/" class="woolentor-button">Download</a>
                </div></div>';         
         $wltemplatelist .= '</div></div></div>';

        $wltemplatelist .='<div class="woolentor-admin-tab-pane" id="shoppage"><div class="woolentor-template-area"><div class="woolentor-themes-row">';

        $wltemplatelist .='<div class="woolentor-single-theme">
                <img src="'.WOOLENTOR_ADDONS_PL_URL.'/includes/admin/assets/images/archive-layout-3.png" alt="">
                <div class="woolentor-theme-content">
                    <h3>Shop Page Style One</h3>
                    <a href="http://demo.wphash.com/woolentor/shop-page-one/" class="woolentor-button" target="_blank">Preview</a>
                    <a href="https://freethemescloud.com/download/woolentor-shop-page-three/" class="woolentor-button">Download</a>
                </div></div>';
        $wltemplatelist .='<div class="woolentor-single-theme">
                <img src="'.WOOLENTOR_ADDONS_PL_URL.'/includes/admin/assets/images/archive-layout-1.png" alt="">
                <div class="woolentor-theme-content">
                    <h3>Shop Page Style Two<span>( Pro )</span></h3>
                    <a href="http://demo.wphash.com/woolentor/shop-page-two/" class="woolentor-button" target="_blank">Preview</a>
                </div></div>';
        $wltemplatelist .='<div class="woolentor-single-theme">
                <img src="'.WOOLENTOR_ADDONS_PL_URL.'/includes/admin/assets/images/archive-layout-2.png" alt="">
                <div class="woolentor-theme-content">
                    <h3>Shop Page Style Three<span>( Pro )</span></h3>
                    <a href="http://demo.wphash.com/woolentor/shop-page-three/" class="woolentor-button" target="_blank">Preview</a>
                </div></div>';       
         $wltemplatelist .= '</div></div></div>';

        $wltemplatelist .='<div class="woolentor-admin-tab-pane" id="productdetailspage"><div class="woolentor-template-area"><div class="woolentor-themes-row">';
        $wltemplatelist .='<div class="woolentor-single-theme">
                <img src="'.WOOLENTOR_ADDONS_PL_URL.'/includes/admin/assets/images/product-details-1.png" alt="">
                <div class="woolentor-theme-content">
                    <h3>Product Details Style One</h3>
                    <a href="http://demo.wphash.com/woolentor/product-details-one/" class="woolentor-button" target="_blank">Preview</a>
                    <a href="https://freethemescloud.com/download/woolentor-product-details/" class="woolentor-button">Download</a>
                </div></div>';
        $wltemplatelist .='<div class="woolentor-single-theme">
                <img src="'.WOOLENTOR_ADDONS_PL_URL.'/includes/admin/assets/images/product-details-2.png" alt="">
                <div class="woolentor-theme-content">
                    <h3>Product Details Style Two</h3>
                    <a href="http://demo.wphash.com/woolentor/product-details-two/" class="woolentor-button" target="_blank">Preview</a>
                    <a href="https://freethemescloud.com/download/woolentor-product-details-two/" class="woolentor-button">Download</a>
                </div></div>';
        $wltemplatelist .='<div class="woolentor-single-theme">
                <img src="'.WOOLENTOR_ADDONS_PL_URL.'/includes/admin/assets/images/product-details-3.png" alt="">
                <div class="woolentor-theme-content">
                    <h3>Product Details Style Three</h3>
                    <a href="http://demo.wphash.com/woolentor/product-details-three/" class="woolentor-button" target="_blank">Preview</a>
                    <a href="https://freethemescloud.com/download/woolentor-product-details-three/" class="woolentor-button">Download</a>
                </div></div>';         
         $wltemplatelist .= '</div></div></div>';

        $wltemplatelist .='<div class="woolentor-admin-tab-pane" id="otherspage"><div class="woolentor-template-area"><div class="woolentor-themes-row">';
        $wltemplatelist .='<div class="woolentor-single-theme">
                <img src="'.WOOLENTOR_ADDONS_PL_URL.'/includes/admin/assets/images/about-us.png" alt="">
                <div class="woolentor-theme-content">
                    <h3>About Us Page</h3>
                    <a href="http://demo.shrimpthemes.com/1/parlo/about-us/" class="woolentor-button" target="_blank">Preview</a>
                    <a href="https://freethemescloud.com/download/parlo-about-us-page/" class="woolentor-button">Download</a>
                </div></div>';
        $wltemplatelist .='<div class="woolentor-single-theme">
                <img src="'.WOOLENTOR_ADDONS_PL_URL.'/includes/admin/assets/images/contact-us.png" alt="">
                <div class="woolentor-theme-content">
                    <h3>Contact Us Page</h3>
                    <a href="http://demo.shrimpthemes.com/1/parlo/contact-us/" class="woolentor-button" target="_blank">Preview</a>
                    <a href="https://freethemescloud.com/download/parlo-contact-us-page/" class="woolentor-button">Download</a>
                </div></div>';

         $wltemplatelist .= '</div></div></div>';

         $wltemplatelist .= '</div>';
        echo $wltemplatelist;
    }
    

}

new Woolentor_Admin_Settings();