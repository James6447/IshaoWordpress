<?php

/**
 * Class WC_Category_Slider_CPT
 */

class WC_Category_Slider_CPT {

	protected $slug;

	/**
	 * CPT constructor.
	 *
	 */
	public function __construct() {
		$this->slug = 'wc_category_slider';
		add_action( 'init', array( $this, 'register_shortcode_post' ), 0 );
		add_filter( 'manage_' . $this->slug . '_posts_columns', array( $this, 'set_shortocode_column' ) );
		add_filter( 'manage_' . $this->slug . '_posts_custom_column', array( $this, 'shortocode_column_data' ), 10, 2 );
	}

	/**
	 * Register post type
	 */
	public function register_shortcode_post() {
		$labels = array(
			'name'               => _x( 'WooCommerce Category Slider Shortcode', 'post type general name', 'woo-category-slider-by-pluginever' ),
			'singular_name'      => _x( 'WooCommerce Category Slider', 'post type singular name', 'woo-category-slider-by-pluginever' ),
			'menu_name'          => _x( 'WC Cat Slider', 'admin menu', 'woo-category-slider-by-pluginever' ),
			'name_admin_bar'     => _x( 'WooCommerce Category Slider', 'add new on admin bar', 'woo-category-slider-by-pluginever' ),
			'add_new'            => _x( 'Add New', 'book', 'woo-category-slider-by-pluginever' ),
			'add_new_item'       => __( 'Add New Slider', 'woo-category-slider-by-pluginever' ),
			'new_item'           => __( 'New Slider', 'woo-category-slider-by-pluginever' ),
			'edit_item'          => __( 'Edit Slider', 'woo-category-slider-by-pluginever' ),
			'view_item'          => __( 'View Slider', 'woo-category-slider-by-pluginever' ),
			'all_items'          => __( 'All Sliders', 'woo-category-slider-by-pluginever' ),
			'search_items'       => __( 'Search Slider', 'woo-category-slider-by-pluginever' ),
			'parent_item_colon'  => __( 'Parent Slider:', 'woo-category-slider-by-pluginever' ),
			'not_found'          => __( 'No Slider found.', 'woo-category-slider-by-pluginever' ),
			'not_found_in_trash' => __( 'No Slider found in Trash.', 'woo-category-slider-by-pluginever' ),
			'item_published'     => __( 'Slider published.', 'woo-category-slider-by-pluginever' ),
			'item_updated'       => __( 'Slider updated.', 'woo-category-slider-by-pluginever' ),
		);

		$args = array(
			'labels'                => $labels,
			'description'           => __( 'Description.', 'woo-category-slider-by-pluginever' ),
			'public'                => false,
			'publicly_queryable'    => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'query_var'             => true,
			'can_export'            => true,
			'capability_type'       => 'post',
			'has_archive'           => false,
			'hierarchical'          => false,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-images-alt',
			'supports'              => array( 'title' ),
			'show_in_rest'          => true,
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		);

		register_post_type( $this->slug, $args );
	}

	/**
	 * Register shortcode column
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function set_shortocode_column( $columns ) {
		unset( $columns['date'] );
		$columns['shortcode'] = __( 'Shortcode', 'woo-category-slider-by-pluginever' );
		$columns['date']      = __( 'Date', 'woo-category-slider-by-pluginever' );

		return $columns;
	}

	/**
	 * show shortcode column data
	 *
	 * @param $column
	 * @param $post_id
	 */
	public function shortocode_column_data( $column, $post_id ) {
		switch ( $column ) {

			case 'shortcode' :
				echo "<code>[woo_category_slider id='{$post_id}']</code>";
				break;

		}

	}
}

new WC_Category_Slider_CPT();
