<?php

class WC_Category_Slider_Shortcode {
	/**
	 * Shortcode constructor.
	 */
	public function __construct() {
		add_shortcode( 'woo_category_slider', array( $this, 'render' ) );
		add_shortcode( 'wc_category_slider', array( $this, 'render_shortcode_demo' ) );
	}


	public function render_shortcode_demo( $attr ) {
		ob_start();
		$attr = wp_parse_args( $attr, array(
			'template' => 'default',
		) );
		?>
		<style>
			.wc-slider {
				width: 300px !important;
				overflow: hidden;
				float: left;
				margin: 0 10px 10px 0;
			}

			.wrap {
				width: 1200px !important;
			}
		</style>
		<?php

		$files = glob( WC_CAT_SLIDER_TEMPLATES . '/*.php' );
		foreach ( $files as $file ) {
			include $file;
		}

		$file = WC_CAT_SLIDER_TEMPLATES . '/' . $attr['template'] . '.php';
		if ( file_exists( $file ) ) {
			include $file;
		}

		$html = ob_get_contents();
		ob_get_clean();

		return $html;
	}

	public function render( $attr ) {

		$params = shortcode_atts( [ 'id' => null ], $attr );
		if ( empty( $params['id'] ) ) {
			return false;
		}

		$post_id = $params['id'];

		$selected_categories = 'all';

		$theme          = wc_category_slider_get_meta( $post_id, 'theme', 'default' );
		$selection_type = wc_category_slider_get_meta( $post_id, 'selection_type', 'all' );
		$limit_number   = wc_category_slider_get_meta( $post_id, 'limit_number', '10' );
		$orderby        = wc_category_slider_get_meta( $post_id, 'orderby', 'name' );
		$order          = wc_category_slider_get_meta( $post_id, 'order', 'asc' );
		$include_child  = wc_category_slider_get_meta( $post_id, 'include_child', 'on' );
		$hide_empty     = wc_category_slider_get_meta( $post_id, 'hide_empty', 'on' );
		$hide_name      = wc_category_slider_get_meta( $post_id, 'hide_name', 'off' );
		$hide_image     = wc_category_slider_get_meta( $post_id, 'hide_image', 'off' );
		$hide_content   = wc_category_slider_get_meta( $post_id, 'hide_content', 'off' );
		$show_desc      = wc_category_slider_get_meta( $post_id, 'show_desc', 'off' );
		$hide_count     = wc_category_slider_get_meta( $post_id, 'hide_count', 'off' );
		$hide_border    = wc_category_slider_get_meta( $post_id, 'hide_border', 'off' );
		$hide_button    = wc_category_slider_get_meta( $post_id, 'hide_button', 'off' );
		$hide_icon      = wc_category_slider_get_meta( $post_id, 'hide_icon', 'off' );
		$button_text    = wc_category_slider_get_meta( $post_id, 'button_text', __( 'Shop Now', 'woo-category-slider-by-pluginever' ) );
		$hover_style    = wc_category_slider_get_meta( $post_id, 'hover_style', 'hover-zoom-in' );
		$icon_size      = wc_category_slider_get_meta( $post_id, 'icon_size', '2x' );
		$image_size     = wc_category_slider_get_meta( $post_id, 'image_size', 'default' );

		if ( 'all' != $selection_type ) {
			$selected_category_ids = wc_category_slider_get_meta( $post_id, 'selected_categories', [] );

			if ( is_array( $selected_category_ids ) && ! empty( $selected_category_ids ) ) {
				$selected_categories = wp_parse_id_list( $selected_category_ids );
			}
		}

		$terms = wc_category_slider_get_categories( apply_filters( 'wc_category_slider_term_list_args', array(
			'taxonomy'   => 'product_cat',
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $hide_empty == 'on' ? false : true,
			'include'    => $selected_categories,
			'number'     => $limit_number,
			//'child_of'   => $include_child == 'on' ? $selected_categories : 0,
			'childless'  => false,
		), $post_id ) );

		$terms = apply_filters( 'wc_category_slider_categories', $terms, $post_id );

		$theme_class   = 'wc-category-' . $theme;
		$slider_class  = 'wc-category-slider-' . $post_id;
		$wrapper_class = "$theme_class $slider_class $hover_style";

		if ( 'on' == $hide_image ) {
			$wrapper_class .= ' hide-image ';
		}

		if ( 'on' == $hide_content ) {
			$wrapper_class .= ' hide-content ';
		}

		if ( 'on' == $hide_border ) {
			$wrapper_class .= ' hide-border ';
		}

		ob_start();

		?>

	<div class="wc-category-slider <?php echo $wrapper_class; ?>" id="<?php echo 'wc-category-slider-' . $post_id ?>" data-slider-config='<?php echo $this->get_slider_config( $post_id ); ?>'>

		<?php

	foreach ( $terms

		as $term ) {


		//=== Slider Components ===
		if ( 'on' != $hide_image ) {
			$image = sprintf( '<div class="wc-slide-image-wrapper"><a class="wc-slide-link" href="%s">%s</a></div>', $term['url'], ! empty( $term['image_id'] ) ? wp_get_attachment_image( $term['image_id'], $image_size, '', array( 'class' => $image_size ) ) : '<img class="default" src="'.WC_CAT_SLIDER_ASSETS_URL.'/images/placeholder.png">'  );
		} else {
			$image = '';
		}

		if ( 'off' == $hide_icon && ! empty( $term['icon'] ) ) {
			$icon = sprintf( '<i class="fa %s wc-slide-icon fa-%s" aria-hidden="true"></i>', esc_attr( $term['icon'] ), $icon_size );
		} else {
			$icon = '';
		}

		if ( 'on' != $hide_name ) {
			$title = sprintf( '<a href="%s" class="wc-slide-link"><h3 class="wc-slide-title">%s</h3></a>', $term['url'], $term['name'] );
		} else {
			$title = '';
		}

		if ( 'on' != $hide_count ) {
			$count = sprintf( '<span class="wc-slide-product-count">%s</span>', __( sprintf( '<span>%s</span> Products', $term['count'] ), 'woo-category-slider-by-pluginever' ) );
		} else {
			$count = '';
		}

		//==== Child Term Items ===
		$child_terms = '';

		if ( 'on' == $include_child ) {

			$taxonomy = 'product_cat';
			$children = array_filter( get_term_children( $term['term_id'], $taxonomy ) );

			$child_terms .= '<ul class="wc-slide-child-items">';

			foreach ( $children as $child ) {
				$child_term  = get_term_by( 'id', $child, $taxonomy );
				$child_terms .= sprintf( ' <li class="wc-slide-child-item"><a href="%s" class="wc-slide-link">%s (%s)</a></li> ', get_term_link( $child, $taxonomy ), $child_term->name, $child_term->count );
			}

			$child_terms .= '</ul>';
		}

		$description = $show_desc == 'on' && ! empty( $term['description'] ) ? sprintf( '<p class="wc-slide-description">%s</p>', $term['description'] ) : '';
		$button      = $hide_button != 'on' ? sprintf( '<a href="%s" class="wc-slide-button">%s</a>', esc_url( $term['url'] ), $button_text ) : '';

		?>

		<div class="wc-slide">

		<!--Image-->                <?php echo $image; ?>

		<?php if ( $theme == 'pro-18' ) {
			echo $count;
		} ?>

		<?php

		if ( 'off' == $hide_content ) { ?>
			<div class="wc-slide-content-wrapper">

				<?php

				//=== Generate html markup based on theme ===
				if ( in_array( $theme, array(
					'pro-6',
					'pro-7',
					'pro-8',
					'pro-9',
					'pro-10',
					'pro-21',
					'pro-22',
					'pro-24'
				) ) ) {

					echo '<div class="wc-slide-before-hover">';
					echo $icon;
					echo $title;
					echo $count;
					echo $child_terms;
					echo $description;
					echo '</div>';

					echo '<div class="wc-slide-after-hover">';
					echo $title;
					echo $count;
					echo $button;
					echo '</div>';

				} elseif ( in_array( $theme, array( 'pro-14', 'pro-15' ) ) ) {
					echo '<div class="wc-slide-header">';
					echo $title;
					echo $icon;
					echo '</div>';

					echo '<div class="wc-slide-footer">';
					echo $count;
					echo $child_terms;
					echo $description;
					echo $button;
					echo '</div>';

				} elseif ( in_array( $theme, array( 'pro-16' ) ) ) {
					echo '<div class="wc-slide-content-bottom">';
					echo $title;
					echo $icon;
					echo $count;
					echo '<div class="content-footer">';
					echo $child_terms;
					echo $description;
					echo $button;
					echo '</div>';
					echo '</div>';
				} elseif ( in_array( $theme, array( 'pro-17' ) ) ) {
					echo '<div class="wc-slide-content-top">';
					echo $icon;
					echo $count;
					echo '</div>';
					echo "<div class='wc-slide-heading'>$title</div>";
					echo '<div class="wc-slide-content-bottom">';
					echo $title;
					echo $child_terms;
					echo $description;
					echo $button;
					echo '</div>';

				} elseif ( in_array( $theme, array( 'pro-19', 'pro-20' ) ) ) {
					echo '<div class="wc-slide-header">';
					echo $icon;
					echo $title;
					echo $child_terms;
					echo 'pro-20' == $theme ? $description : '';
					echo '</div>';

					echo '<div class="wc-slide-footer">';
					echo 'pro-19' == $theme ? $description : '';
					echo $count;
					echo $button;
					echo '</div>';

				} else {
					echo $icon;
					echo $title;
					echo $count;
					echo $child_terms;
					echo $description;
					echo $button;
				}

				?>
			</div><!--end content-wrapper-->
		<?php }
		echo '</div><!--end wc-slide-->';
	}

		?>
		</div>

		<?php

		$this->get_slider_styles( $post_id );

		$html = ob_get_clean();

		return $html;
	}


	/**
	 * Get slider settings
	 *
	 * @param $settings
	 *
	 * @return object
	 */
	protected
	function get_slider_config(
		$post_id
	) {

		$config = array(
			'dots'               => 'off' == wc_category_slider_get_meta( $post_id, 'hide_paginate', 'off' ) ? true : false,
			'autoHeight'         => true,
			'singleItem'         => true,
			'autoplay'           => 'on' == wc_category_slider_get_meta( $post_id, 'autoplay' ) ? true : false,
			'loop'               => 'on' == wc_category_slider_get_meta( $post_id, 'loop' ) ? true : false,
			'lazyLoad'           => 'on' == wc_category_slider_get_meta( $post_id, 'lazy_load' ) ? true : false,
			'margin'             => intval( wc_category_slider_get_meta( $post_id, 'column_gap', 10 ) ),
			'autoplayTimeout'    => intval( wc_category_slider_get_meta( $post_id, 'slider_speed', 3000 ) ),
			'autoplaySpeed'      => intval( wc_category_slider_get_meta( $post_id, 'autoplay_speed', 600 ) ),
			'autoplayHoverPause' => true,
			'nav'                => 'off' == wc_category_slider_get_meta( $post_id, 'hide_nav', 'off' ) ? true : false,
			'navText'            => [ '<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>' ],
			'stagePadding'       => 4,
			'items'              => intval( wc_category_slider_get_meta( $post_id, 'cols', 3 ) ),
			'responsive'         => [
				'0'    => [
					'items' => intval( wc_category_slider_get_meta( $post_id, 'phone_cols', 1 ) ),
				],
				'600'  => [
					'items' => intval( wc_category_slider_get_meta( $post_id, 'tab_cols', 2 ) ),
				],
				'1000' => [
					'items' => intval( wc_category_slider_get_meta( $post_id, 'cols', 4 ) ),
				],
			],
		);

		//		if ( ! empty( $settings['fluid_speed'] ) ) {
		//			$config['fluidSpeed'] = intval( wc_category_slider_get_meta( $post_id, 'slider_speed' ) );
		//			$config['smartSpeed'] = intval( wc_category_slider_get_meta( $post_id, 'slider_speed' ) );
		//		}

		$config = apply_filters( 'wc_slider_config', $config );

		return json_encode( $config );
	}

	/**
	 * Get Slider Styles
	 *
	 * @since 3.13
	 *
	 */

	public
	function get_slider_styles(
		$post_id
	) {
		$theme       = wc_category_slider_get_meta( $post_id, 'theme' );
		$hide_border = wc_category_slider_get_meta( $post_id, 'hide_border', 'off' );

		//wrapper classes
		$prefix          = "#wc-category-slider-{$post_id} .wc-slide";
		$image_wrapper   = "$prefix .wc-slide-image-wrapper";
		$content_wrapper = "$prefix .wc-slide-content-wrapper";

		ob_start();

		if ( $hide_border == 'on' ) {
			echo "$prefix{border: 1px solid transparent}";
		}

		$styles = ob_get_clean();

		echo sprintf( '<style>%s</style>', apply_filters( 'wc_category_slider_styles', $styles, $post_id ) );
	}

}

new WC_Category_Slider_Shortcode();
