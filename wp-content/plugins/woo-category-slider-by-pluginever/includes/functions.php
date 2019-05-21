<?php
// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
//function prefix is wc_category_slider please use that for all functions here

/**
 * Checks if pro version is active or not
 *
 * @since 3.0.0
 * @return bool
 */
function wc_category_slider_is_pro_active() {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if ( in_array( 'woocommerce-category-slider-pro/wc-category-slider-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        return true;
    }

    return false;

}


/**
 * Return all WC categories
 *
 * @since 3.0.0
 *
 * @param array $args
 * @param int   $post_id
 *
 * @return array|int|\WP_Error
 */
function wc_category_slider_get_categories( $args = array(), $post_id = null ) {
    global $wp_version;
    $categories = array();
    $default    = array(
        'number'     => '20',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
        'include'    => array(),
        'exclude'    => array(),
        'child_of'   => 0,
    );
    if ( version_compare( $wp_version, '4.5.0', '<' ) ) {
        $args       = wp_parse_args( $args, $default );
        $categories = (array) get_terms( 'product_cat', $args );
    } else {
        $args             = wp_parse_args( $args, $default );
        $args['taxonomy'] = 'product_cat';
        $categories       = (array) get_terms( $args );

    }

    $results = [];
    foreach ( $categories as $category ) {
        $results[] = [
            'term_id'     => $category->term_id,
            'name'        => $category->name,
            'slug'        => $category->slug,
            'url'         => get_term_link( $category->term_id ),
            'description' => $category->description,
            'count'       => $category->count,
            'image'       => wc_category_get_category_image( $category->term_id, $post_id ),
        ];
    }

    return apply_filters( 'wc_category_slider_get_categories', $results, $post_id );
}

/**
 * Get default shortcode settings
 *
 * @param $post_id
 *
 * @return array
 */
function wc_category_slider_get_settings( $post_id ) {
    $default = array(
        'post_id'        => $post_id,
        'selection_type' => 'all',
        'include'        => [],
        'hide_empty'     => '0',
        'include_child'  => '0',
        'number'         => '20',

        //design
        'hide_image'     => '0',
        'hide_content'   => '0',
        'hide_button'    => '0',
        'hide_name'      => '0',
        'hide_count'     => '0',
        'hide_nav'       => '0',
        'hover_style'    => 'hover-zoom-in',
        'hide_border'    => '0',
        'show_desc'      => '0',
        'theme'          => 'default',
        'button_text'    => 'Shop Now',

        //slider
        'autoplay'       => '1',
        'cols'           => '4',
        'tab_cols'       => '2',
        'phone_cols'     => '1',
        'slider_speed'   => '3000',
        'fluid_speed'    => '0',
        'loop'           => '1',
        'column_gap'     => '10',
        'lazy_load'      => '1',
    );
    // those are fields which will be merged with post meta
    $default_fields = apply_filters( 'wc_category_slider_default_settings', $default );
    $settings       = array();

    if ( $post_id !== null && get_post_status( $post_id ) ) {
        foreach ( $default_fields as $key => $value ) {
            $saved = get_post_meta( $post_id, $key, true );
            if ( $saved == '0' || ! empty( $saved ) ) {
                $settings[ $key ] = $saved;
            } else {
                $settings[ $key ] = $value;
            }
        }

    }

    return $settings;
}

/**
 * Get selected categories from the post id
 *
 * @param $args
 * @param $post_id
 *
 * @return array $categories
 */
function wc_category_slider_get_selected_categories( $args, $post_id = null ) {
    $default = array(
        'selection_type' => 'all',
        'include'        => [],
        'exclude'        => [],
        'number'         => 20,
        'hide_empty'     => 0,
        'order'          => 'name',
        'order_by'       => 'ASC',
        'hierarchical'   => true,
        'include_child'  => '1',
    );

    $settings = wp_parse_args( $args, $default );
    if ( $settings['selection_type'] == 'all' ) {
        $settings['include'] = array();
    }

    //get categories
    $categories = wc_category_slider_get_categories( $settings, $post_id );

    return $categories;
}

/**
 * Get category Image
 *
 * @param      $term_id
 * @param null $post_id
 *
 * @return string
 */
function wc_category_get_category_image( $term_id, $post_id = null ) {
    $thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );
    $image_url    = WCS_ASSETS . '/public/images/placeholder.png';

    if ( ! empty( $thumbnail_id ) ) {
        $image_url = wp_get_attachment_image_url( $thumbnail_id, 'large' );
    }

    return $image_url;
}
