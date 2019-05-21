<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Archive_Products_Render extends WC_Shortcode_Products {

    private $settings = [];
    private $product_filter_status = false;

    public function __construct( $settings = [] ) {
        $this->settings = $settings;
        $this->attributes   = $this->parse_attributes( [
            'columns'       => $settings['columns'],
            'rows'          => $settings['rows'],
            'paginate'      => $settings['paginate'],
            'cache'         => false,
        ] );
        $this->query_args = $this->parse_query_args();
    }

    protected function parse_query_args() {
        $settings = &$this->settings;

        $query_args = [
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => true,
            'no_found_rows'         => false === wc_string_to_bool( $this->attributes['paginate'] ),
            'orderby'               => $settings['orderby'],
            'order'                 => strtoupper( $settings['order'] ),
        ];

        $query_args['meta_query']   = WC()->query->get_meta_query();
        $query_args['tax_query']    = [];

        // Visibility.
        $this->set_visibility_query_args( $query_args );

        // ID.
        $this->set_ids_query_args( $query_args );

        // Categories.
        $this->set_categories_query_args( $query_args );

        // Tags.
        $this->set_tags_query_args( $query_args );

        $query_args = apply_filters( 'woocommerce_shortcode_products_query', $query_args, $this->attributes, $this->type );

        if ( 'yes' === $settings['paginate'] && 'yes' === $settings['allow_order'] ) {
            $ordering_args = WC()->query->get_catalog_ordering_args();
        } else {
            $ordering_args = WC()->query->get_catalog_ordering_args( $query_args['orderby'], $query_args['order'] );
        }

        $query_args['orderby'] = $ordering_args['orderby'];
        $query_args['order'] = $ordering_args['order'];
        if ( $ordering_args['meta_key'] ) {
            $query_args['meta_key'] = $ordering_args['meta_key'];
        }

        $query_args['posts_per_page'] = intval( $settings['columns'] * $settings['rows'] );

        if ( 'yes' === $settings['paginate'] ) {
            $page = absint( empty( $_GET['product-page'] ) ? 1 : $_GET['product-page'] );

            if ( 1 < $page ) {
                $query_args['paged'] = $page;
            }

            if ( 'yes' !== $settings['allow_order'] ) {
                remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
            }

            if ( 'yes' !== $settings['show_result_count'] ) {
                remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
            }
        }
        $query_args['posts_per_page'] = intval( $settings['columns'] * $settings['rows'] );
        $query_args['fields'] = 'ids';

        return $query_args;
    }


}
