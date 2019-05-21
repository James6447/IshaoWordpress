<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit();


if ( !class_exists( 'Woolentor_Elementor_Addons' ) ) {

    class Woolentor_Elementor_Addons_Init{

        public function __construct(){
            if ( class_exists( 'WooCommerce' ) ) {
                add_action( 'elementor/widgets/widgets_registered', array( $this, 'woolentor_includes_widgets' ) );
            }
            add_action( 'init', array( $this, 'woolentor_register_scripts' ) );
            add_action( 'wp_enqueue_scripts', array( $this,'woolentor_enqueue_frontend_scripts' ) );
            $this->woolentor_file_includes();
        }

        // Include Widgets File
        public function woolentor_includes_widgets(){
            if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/product_tabs.php' ) ) {
                require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/product_tabs.php';
            }
            if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/universal_product.php' ) ) {
                require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/universal_product.php';
            }
            if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/add_banner.php' ) ) {
                require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/add_banner.php';
            }

            // WooCommerce Builder
            if( woolentor_get_option( 'enablecustomlayout', 'woolentor_woo_template_tabs', '0' ) == 'on' ){
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/archive_product.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/archive_product.php';
                }
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_title.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_title.php';
                }
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_related.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_related.php';
                }
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_add_to_cart.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_add_to_cart.php';
                }
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_additional_information.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_additional_information.php';
                }
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_data_tab.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_data_tab.php';
                }
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_description.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_description.php';
                }
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_short_description.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_short_description.php';
                }
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_price.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_price.php';
                }
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_rating.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_rating.php';
                }
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_reviews.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_reviews.php';
                }
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_image.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_image.php';
                }
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_upsell.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_upsell.php';
                }
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_stock.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_stock.php';
                }
                if ( file_exists( WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_meta.php' ) ) {
                    require_once WOOLENTOR_ADDONS_PL_PATH.'includes/widgets/wb_product_meta.php';
                }
            }
        }

        // Include File
        Public function woolentor_file_includes(){
            if( woolentor_get_option( 'enablecustomlayout', 'woolentor_woo_template_tabs', '0' ) == 'on' ){
                include_once ( WOOLENTOR_ADDONS_PL_PATH.'includes/wl_woo_shop.php' );
                include_once ( WOOLENTOR_ADDONS_PL_PATH.'includes/archive_product_render.php' );
            }
        }

        // Register frontend scripts
        public function woolentor_register_scripts(){
            
            // Register Css file
            wp_register_style(
                'htflexboxgrid',
                WOOLENTOR_ADDONS_PL_URL . 'assets/css/htflexboxgrid.css',
                array(),
                WOOLENTOR_VERSION
            );
            
            wp_register_style(
                'simple-line-icons',
                WOOLENTOR_ADDONS_PL_URL . 'assets/css/simple-line-icons.css',
                array(),
                WOOLENTOR_VERSION
            );

            wp_register_style(
                'woolentor-widgets',
                WOOLENTOR_ADDONS_PL_URL . 'assets/css/woolentor-widgets.css',
                array(),
                WOOLENTOR_VERSION
            );

            wp_register_style(
                'slick',
                WOOLENTOR_ADDONS_PL_URL . 'assets/css/slick.css',
                array(),
                WOOLENTOR_VERSION
            );

            // Register JS file
            wp_register_script(
                'slick',
                WOOLENTOR_ADDONS_PL_URL . 'assets/js/slick.min.js',
                array('jquery'),
                WOOLENTOR_VERSION,
                TRUE
            );

            wp_register_script(
                'countdown-min',
                WOOLENTOR_ADDONS_PL_URL . 'assets/js/jquery.countdown.min.js',
                array('jquery'),
                WOOLENTOR_VERSION,
                TRUE
            );

            wp_register_script(
                'woolentor-widgets-scripts',
                WOOLENTOR_ADDONS_PL_URL . 'assets/js/woolentor-widgets-active.js',
                array('jquery'),
                WOOLENTOR_VERSION,
                TRUE
            );

            $localizeargs = array(
                'woolentorajaxurl' => admin_url( 'admin-ajax.php' ),
            );
            wp_localize_script( 'woolentor-widgets-scripts', 'woolentor_addons', $localizeargs );

        }

        // enqueue frontend scripts
        public function woolentor_enqueue_frontend_scripts(){
            // CSS File
            wp_enqueue_style( 'htflexboxgrid' );
            wp_enqueue_style( 'font-awesome' );
            wp_enqueue_style( 'simple-line-icons' );
            wp_enqueue_style( 'slick' );
            wp_enqueue_style( 'woolentor-widgets' );
            if ( is_rtl() ) {
              wp_enqueue_style(  'woolentor-widgets-rtl',  WOOLENTOR_ADDONS_PL_URL . 'assets/css/woolentor-widgets-rtl.css', array(), WOOLENTOR_VERSION );
            }
        }

    }
    new Woolentor_Elementor_Addons_Init();

}