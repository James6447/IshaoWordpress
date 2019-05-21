<?php

namespace Pluginever\WoocommerceCategorySlider;

class MetaBox {
    /**
     * Metabox constructor.
     */
    public function __construct() {
        add_action( 'admin_init', [ $this, 'init_category_settings_metabox' ] );
        add_action( 'admin_init', [ $this, 'init_custom_image_metabox' ] );
        add_action( 'admin_init', [ $this, 'init_display_settings_metabox' ] );
        add_action( 'admin_init', [ $this, 'init_slider_settings_metabox' ] );
        add_action( 'add_meta_boxes', [ $this, 'init_shortcode_metabox' ], 999 );

        if (! wc_category_slider_is_pro_active() ) {
            add_action( 'add_meta_boxes', [ $this, 'init_promotion_metabox' ] );
        }
    }

    /**
     * Register metabox for category selection
     */
    public function init_category_settings_metabox() {
        $metabox = new \Pluginever\Framework\Metabox( 'wc_category_slider_category_metabox' );
        $config  = array(
            'title'        => __( 'Category Settings', 'wc_category_slider' ),
            'screen'       => 'wc_category_slider',
            'context'      => 'normal',
            'priority'     => 'high',
            'lazy_loading' => 'true',
            'fields'       => array(
                array(
                    'type'     => 'select',
                    'name'     => 'selection_type',
                    'label'    => 'Selection Type',
                    'value'    => 'all',
                    'tooltip'  => __( 'Select all categories or any custom categories', 'wc_category_slider' ),
                    'sanitize' => 'sanitize_key',
                    'required' => 'true',
                    'options'  => array(
                        'all'    => 'All',
                        'custom' => 'Custom'
                    ),
                ),
                array(
                    'type'      => 'select',
                    'name'      => 'include',
                    'label'     => 'Select Categories',
                    'value'     => 'all',
                    'multiple'  => true,
                    'select2'   => 'true',
                    'sanitize'  => 'intval',
                    'condition' => array(
                        'depend_on'    => 'selection_type',
                        'depend_value' => 'custom',
                        'depend_cond'  => '==',
                    ),
                    'options'   => $this->get_wc_category_list(),
                ),
                array(
                    'type'    => 'checkbox',
                    'name'    => 'include_child',
                    'label'   => __( 'Include Children', 'wc_category_slider' ),
                    'tooltip' => __( 'Will include subcategories of the selected categories', 'wc_category_slider' ),
                    'title'   => __( 'Yes', 'wc_category_slider' ),
                ),
                array(
                    'type'    => 'checkbox',
                    'name'    => 'hide_empty',
                    'label'   => __( 'Hide Empty Categories', 'wc_category_slider' ),
                    'tooltip' => __( 'Automatically hides Category without products', 'wc_category_slider' ),
                    'title'   => __( 'Yes', 'wc_category_slider' ),
                ),
                array(
                    'type'     => 'number',
                    'name'     => 'number',
                    'tooltip'  => __( 'Limit the number of category appear on the slider', 'wc_category_slider' ),
                    'label'    => __( 'Limit Items', 'wc_category_slider' ),
                    'value'    => '20',
                    'sanitize' => 'intval',
                ),
            ),
        );
        $metabox->init( apply_filters( 'wc_category_slider_settings_config', $config ) );
    }

    /**
     * Register custom image metabox
     */
    public function init_custom_image_metabox() {
        $metabox = new \Pluginever\Framework\Metabox( 'wc_category_slider_custom_image_metabox' );
        $config  = array(
            'title'        => __( 'Custom Category Images', 'wc_category_slider' ),
            'screen'       => 'wc_category_slider',
            'context'      => 'normal',
            'priority'     => 'high',
            'lazy_loading' => 'true',
            'fields'       => array(
                array(
                    'type'          => 'html',
                    'name'          => 'html_title',
                    'wrapper_class' => 'row',
                    'help'          => __( 'Upgrade to PRO version to use custom category image', 'wc_category_slider' ),
                ),
            )
        );

        $metabox_config = apply_filters( 'wc_category_slider_custom_image_metabox_config', $config );
        $metabox->init( $metabox_config );
    }

    /**
     * Register slider display settings metabox
     */
    public function init_display_settings_metabox() {
        $metabox        = new \Pluginever\Framework\Metabox( 'wc_category_slider_display_metabox' );
        $config         = array(
            'title'        => __( 'Display Settings', 'wc_category_slider' ),
            'screen'       => 'wc_category_slider',
            'context'      => 'normal',
            'priority'     => 'high',
            'lazy_loading' => 'true',
            'fields'       => array(
                array(
                    'type'  => 'checkbox',
                    'name'  => 'hide_image',
                    'label' => __( 'Hide Image', 'wc_category_slider' ),
                    'value' => '0',
                    'title' => __( 'Yes', 'wc_category_slider' ),
                ),
                array(
                    'type'    => 'checkbox',
                    'name'    => 'hide_content',
                    'label'   => __( 'Hide Content', 'wc_category_slider' ),
                    'tooltip' => __( 'Hide category name, button, product count', 'wc_category_slider' ),
                    'value'   => '0',
                    'title'   => __( 'Yes', 'wc_category_slider' ),

                ),
                array(
                    'type'  => 'checkbox',
                    'name'  => 'hide_button',
                    'label' => __( 'Hide Button', 'wc_category_slider' ),
                    'value' => '0',
                    'title' => __( 'Yes', 'wc_category_slider' ),

                ),
                array(
                    'type'  => 'checkbox',
                    'name'  => 'hide_name',
                    'title' => __( 'Yes', 'wc_category_slider' ),
                    'label' => __( 'Hide Category Name', 'wc_category_slider' ),
                    'value' => '0',
                ),

                array(
                    'type'  => 'checkbox',
                    'name'  => 'hide_count',
                    'title' => __( 'Yes', 'wc_category_slider' ),
                    'label' => __( 'Hide Product Count', 'wc_category_slider' ),
                    'value' => '0',
                ),
                array(
                    'type'  => 'checkbox',
                    'name'  => 'hide_nav',
                    'title' => __( 'Yes', 'wc_category_slider' ),
                    'label' => __( 'Hide Navigation', 'wc_category_slider' ),
                    'value' => '0',

                ),
                array(
                    'type'    => 'checkbox',
                    'name'    => 'hide_border',
                    'title'   => __( 'Yes', 'wc_category_slider' ),
                    'label'   => __( 'Hide Border', 'wc_category_slider' ),
                    'tooltip' => __( 'Hide border around slider image?', 'wc_category_slider' ),
                    'value'   => '0',
                ),
                array(
                    'type'     => 'select',
                    'name'     => 'hover_style',
                    'label'    => __( 'Image Hover effect', 'wc_category_slider' ),
                    'value'    => 'hover-zoom-in',
                    'sanitize' => 'sanitize_key',
                    'options'  => apply_filters( 'wc_category_slider_hover_styles', array(
                        'no-hover'      => 'No Hover',
                        'hover-zoom-in' => 'Zoom In',
                    ) )
                ),
                array(
                    'type'    => 'select',
                    'name'    => 'theme',
                    'label'   => __( 'Theme', 'wc_category_slider' ),
                    'value'   => 'default',
                    'options' => apply_filters( 'wc_category_slider_themes', array(
                        'default'    => 'Default',
                        'theme-free' => 'Basic',
                    ) ),
                ),
            ),
        );
        $metabox_config = apply_filters( 'wc_category_slider_display_config', $config );
        $metabox->init( $metabox_config );
    }

    /**
     * Register Slider settings
     */
    public function init_slider_settings_metabox() {
        $metabox = new \Pluginever\Framework\Metabox( 'wc_category_slider_slider_metabox' );
        $config  = array(
            'title'        => __( 'Slider Settings', 'wc_category_slider' ),
            'screen'       => 'wc_category_slider',
            'context'      => 'normal',
            'priority'     => 'high',
            'lazy_loading' => 'true',
            'fields'       => array(
                array(
                    'type'     => 'select',
                    'name'     => 'autoplay',
                    'label'    => __( 'Slider Autoplay', 'wc_category_slider' ),
                    'tooltip'  => __( 'Slider will automatically start playing is set Yes.', 'wc_category_slider' ),
                    'value'    => '1',
                    'sanitize' => 'intval',
                    'options'  => array(
                        '1' => 'Yes',
                        '0' => 'No'
                    ),
                ),
            ),
        );
        $metabox->init( apply_filters( 'wc_category_slider_slider_config', $config ) );
    }

    /**
     * Register shortcode metabox
     */
    public function init_shortcode_metabox() {
        add_meta_box( 'woo-cat-slider-shotcode', __( 'Shotcode', 'wc_category_slider' ), [
            $this,
            'shortcode_metabox_callback'
        ], 'wc_category_slider', 'side' );
    }

    /**
     * Render shortcode metabox
     *
     * @param $post
     */
    public function shortcode_metabox_callback( $post ) {
        echo "<code>[woo_category_slider id='{$post->ID}']</code>";
        echo '<p>' . __( 'Use the shortocode to render the slider anywhere in page or post.', 'wc_category_slider' ) . '</p>';
    }


    public function init_promotion_metabox() {
        add_meta_box( 'woo-cat-slider-promotion', __( 'What More?', 'wc_category_slider' ),
            [
                $this,
                'promotion_metabox_callback'
            ],
            'wc_category_slider', 'side' );
    }

    /**
     *
     */
    public function promotion_metabox_callback() {
        $features = [
            '10+ Eye-Catching ready-made theme',
            'Custom Image',
            'Custom Category Order',
            'Custom Column Size',
            'Custom Content Color',
            'Custom Content Background Color',
            'Custom Button Color',
            'Custom Button Background Color',
            'Custom Button Text',
            'Custom CSS class',
            'Custom Autoplay Speed',
            'Excluding any categories',
            '9+ Hover effects',
            'Different button type',
            'Slider Loop Support',
            'Ability to Customize Almost Everything',
        ];

        ?>
        <img src="<?php echo WCS_ASSETS . '/admin/images/woo-category-slider-pro.png'; ?>" alt="WooCommerce Category Slider Pro" style="width: 100%;margin-bottom: 10px;">
        <h4 style="margin: 0;padding: 0;border-bottom: 1px solid #333;"><?php _e( 'Pro Features', 'wc_category_slider' ); ?></h4>
        <?php
        echo '<ul style="padding-left: 25px;list-style: disc;">';

        foreach ( $features as $feature){
            echo "<li>{$feature}</li>";
        }

        echo '</ul>';
        echo '<p>Use coupon code <strong>FREE2PRO</strong> to get 20% discount.</p>';
        echo '<a href="http://bit.ly/woo-category-slider-pro" target="_blank" style="text-align: center;font-weight: bold;">Upgrade To PRO Now</a>';
    }

    protected function get_wc_category_list() {

        $categories = wc_category_slider_get_categories( [ 'number' => 1000 ] );
        $list       = wp_list_pluck( $categories, 'name', 'term_id' );

        return $list;
    }


}
