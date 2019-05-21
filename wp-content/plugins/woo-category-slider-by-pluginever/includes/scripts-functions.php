<?php

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add all the assets of the public side
 *
 * @since 1.0.0
 *
 * @return void
 */
function wc_slider_load_public_assets( $hook ) {

	wp_register_script( 'owl-carousel', WC_CAT_SLIDER_ASSETS_URL . "/vendor/owlcarousel/owl.carousel.js", [ 'jquery' ], WC_CAT_SLIDER_VERSION, true );
	wp_register_script( 'imagesLoaded', WC_CAT_SLIDER_ASSETS_URL . "/vendor/imagesLoaded.min.js", [
		'jquery',
		'owl-carousel'
	], date( 'i' ), true );
	wp_register_script( 'wc-category-slider', WC_CAT_SLIDER_ASSETS_URL . "/js/wc-category-slider-public.min.js", [
		'jquery',
		'owl-carousel',
		'imagesLoaded',
	], date( 'i' ), true );
	wp_register_style( 'wccs-owlcarousel', WC_CAT_SLIDER_ASSETS_URL . "/vendor/owlcarousel/assets/owl.carousel.css", [], WC_CAT_SLIDER_VERSION );
	wp_register_style( 'wccs-owltheme-default', WC_CAT_SLIDER_ASSETS_URL . "/vendor/owlcarousel/assets/owl.theme.default.css", [], WC_CAT_SLIDER_VERSION );
	wp_register_style( 'wccs-fontawesome', WC_CAT_SLIDER_ASSETS_URL . "/vendor/font-awesome/css/font-awesome.css", [], WC_CAT_SLIDER_VERSION );
	wp_register_style( 'wc-category-slider', WC_CAT_SLIDER_ASSETS_URL . "/css/wc-category-slider-public.css", [
		'wccs-fontawesome',
		'wccs-owlcarousel',
		'wccs-owltheme-default'
	], date( 'i' ) );
	wp_enqueue_style( 'wc-category-slider' );
	wp_enqueue_script( 'imagesLoaded' );
	wp_enqueue_script( 'wc-category-slider' );

}

add_action( 'wp_enqueue_scripts', 'wc_slider_load_public_assets' );

/**
 * Add all the assets required by the plugin
 *
 * @since 1.0.0
 *
 * @return void
 */
function wc_slider_load_admin_assets( $hook ) {

	if ( ! in_array( $hook, array( 'post-new.php', 'post.php' ) ) ) {
		return;
	}

	global $post;

	if ( 'wc_category_slider' != $post->post_type ) {
		return;
	}

	wp_register_style( 'wccs-fontawesome', WC_CAT_SLIDER_ASSETS_URL . "/vendor/font-awesome/css/font-awesome.css", [], date( 'i' ) );

	wp_register_style( 'wc-category-slider', WC_CAT_SLIDER_ASSETS_URL . "/css/admin.css", [
		'woocommerce_admin_styles',
		'wccs-fontawesome'
	], date( 'i' ) );
	wp_register_script( 'wc-category-slider', WC_CAT_SLIDER_ASSETS_URL . "/js/wc-category-slider-admin.js", [
		'jquery',
		'wp-util',
		'select2',
		'wp-color-picker',
	], date( 'i' ), true );
	wp_localize_script( 'wc-category-slider', 'WCS',
		[
			'ajaxurl'    => admin_url( 'admin-ajax.php' ),
			'nonce'      => 'wc-category-slider',
			'codeEditor' => wp_enqueue_code_editor( array( 'type' => 'text/css' ) )
		] );
	wp_enqueue_style( 'wc-category-slider' );
	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker-alpha', WC_CAT_SLIDER_ASSETS_URL . '/js/wp-color-picker-alpha.min.js', [
		'jquery',
		'wp-color-picker'
	], date( 'i' ), true );
	wp_enqueue_script( 'wc-category-slider' );
}

add_action( 'admin_enqueue_scripts', 'wc_slider_load_admin_assets' );


function wc_slider_register_block() {
	if ( ! function_exists( 'register_block_type' ) ) {
		// Gutenberg is not active.
		return;
	}

	// Plugin Assets
	wp_register_script( 'owl-carousel-editor', WC_CAT_SLIDER_ASSETS_URL . "/vendor/owlcarousel/owl.carousel.js", [ 'jquery' ], date( 'i' ), true );
	wp_register_script( 'imagesLoaded', WC_CAT_SLIDER_ASSETS_URL . "/vendor/imagesLoaded.min.js", [ 'jquery' ], date( 'i' ), true );
	wp_register_script( 'wc-category-slider-editor', WC_CAT_SLIDER_ASSETS_URL . "/js/wc-category-slider-public.js", [
		'jquery',
		'imagesLoaded',
		'owl-carousel-editor'
	], date( 'i' ), true );

	wp_register_style( 'wccs-owlcarousel-editor', WC_CAT_SLIDER_ASSETS_URL . "/vendor/owlcarousel/assets/owl.carousel.css", [], date( 'i' ) );
	wp_register_style( 'wccs-owltheme-default-editor', WC_CAT_SLIDER_ASSETS_URL . "/vendor/owlcarousel/assets/owl.theme.default.css", [], date( 'i' ) );
	wp_register_style( 'wccs-fontawesome-editor', WC_CAT_SLIDER_ASSETS_URL . "/vendor/font-awesome/css/font-awesome.css", [], date( 'i' ) );
	wp_register_style( 'wc-category-slider-editor', WC_CAT_SLIDER_ASSETS_URL . "/css/wc-category-slider-public.css", [
		'wccs-fontawesome-editor',
		'wccs-owlcarousel-editor',
		'wccs-owltheme-default-editor'
	], date( 'i' ) );
	// Plugin Assets End

	wp_register_script(
		'wc-category-slider-block',
		WC_CAT_SLIDER_ASSETS_URL . '/js/wc-category-slider-block.js',
		array(
			'jquery',
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-editor',
			'wp-api-fetch',
			'wc-category-slider-editor'
		),
		filemtime( WC_CAT_SLIDER_PATH . '/assets/js/wc-category-slider-block.js' )
	);

	$inline_scripts = 'var isWCCategorySliderPro=' . ( wc_category_slider()->is_pro_installed() ? 'true' : 'false' ) . ';';

	wp_add_inline_script( 'wc-category-slider-block', $inline_scripts, 'before' );

	register_block_type( 'pluginever/wc-category-slider', array(
		'editor_script' => 'wc-category-slider-block',
		'editor_style'  => 'wc-category-slider-editor'
	) );


	if ( function_exists( 'wp_set_script_translations' ) ) {
		/**
		 * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
		 * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
		 * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
		 */
		wp_set_script_translations( 'wc-category-slider-block', 'woo-category-slider-by-pluginever' );
	}
}

add_action( 'init', 'wc_slider_register_block' );
