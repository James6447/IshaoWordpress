<?php
namespace Pluginever\WoocommerceCategorySlider;

class Scripts{

	/**
	 * Constructor for the class
	 *
	 * Sets up all the appropriate hooks and actions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_public_assets') );
		//add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_assets') );
    }

   	/**
	 * Add all the assets of the public side
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function load_public_assets(){
		wp_register_style('wc-category-slider', WCS_ASSETS."/public/css/wc-category-slider-public.css", [], date('i'));
		wp_register_script('owl-carousel', WCS_ASSETS."/public/js/owl-carousel.js", ['jquery'], date('i'), true);
		wp_register_script('image-liquid', WCS_ASSETS."/public/js/image-liquid.js", ['jquery'], date('i'), true);
		wp_register_script('wc-category-slider', WCS_ASSETS."/public/js/wc-category-slider-public.js", ['jquery', 'owl-carousel', 'image-liquid'], date('i'), true);
		wp_localize_script('wc-category-slider', 'WCS', ['ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => 'wc-category-slider']);
		wp_enqueue_style('wc-category-slider');
		wp_enqueue_script('wc-category-slider');
	}

	 /**
	 * Add all the assets required by the plugin
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function load_admin_assets(){
		wp_register_style('wc-category-slider', WCS_ASSETS."/admin/css/wc-category-slider-admin.css", [], date('i'));
		wp_register_script('wc-category-slider', WCS_ASSETS."/admin/js/wc-category-slider-admin.js", ['jquery'], date('i'), true);
		wp_localize_script('wc-category-slider', 'WCS', ['ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => 'wc-category-slider']);
		wp_enqueue_style('wc-category-slider');
		wp_enqueue_script('wc-category-slider');
	}



}
