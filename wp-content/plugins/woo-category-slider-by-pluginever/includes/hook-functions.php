<?php

function wc_slider_get_categories_ajax_callback() {

	$selection_type      = empty( $_REQUEST['selection_type'] ) ? 'all' : sanitize_key( $_REQUEST['selection_type'] );
	$selected_categories = empty( $_REQUEST['selected_categories'] ) ? [] : wp_parse_id_list( $_REQUEST['selected_categories'] );
	$include_child       = empty( $_REQUEST['include_child'] ) || 'on' !== $_REQUEST['include_child'] ? false : true;
	$number              = empty( $_REQUEST['number'] ) ? 10 : intval( $_REQUEST['number'] );
	$orderby             = empty( $_REQUEST['orderby'] ) ? 'name' : sanitize_key( $_REQUEST['orderby'] );
	$order               = empty( $_REQUEST['order'] ) ? 'ASC' : sanitize_key( $_REQUEST['order'] );
	$slider_id           = empty( $_REQUEST['slider_id'] ) ? null : sanitize_key( $_REQUEST['slider_id'] );
	$hide_empty          = ! empty( $_REQUEST['hide_empty'] ) && 'on' == $_REQUEST['hide_empty'] ? false : true;

	if ( $selection_type == 'all' ) {
		$selected_categories = [];
	}


	$categories = wc_category_slider_get_categories( array(
		'number'     => $number,
		'orderby'    => $orderby,
		'order'      => $order,
		'hide_empty' => $hide_empty,
		'include'    => $selected_categories,
		'exclude'    => array(),
		'child_of'   => 0,
		'post_id'    => $slider_id,
	) );

	$categories = apply_filters( 'wc_category_slider_categories', $categories, $slider_id );
	foreach ( $categories as $key => $category ) {
		$image        = WC_CAT_SLIDER_ASSETS_URL . '/images/no-image-placeholder.jpg';
		$thumbnail_id = $category['image_id'];
		if ( ! empty( $thumbnail_id ) ) {
			$attachment = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' );
			if ( is_array( $attachment ) && ! empty( $attachment[0] ) ) {
				$image = esc_url( $attachment[0] );
			}
		}

		$categories[ $key ]['image'] = $image;
	}
	wp_send_json_success( $categories );
}

add_action( 'wp_ajax_wc_slider_get_categories', 'wc_slider_get_categories_ajax_callback' );

function wc_category_slider_load_custom_category_attributes( $categories, $slider_id ) {
	if ( ! $slider_id ) {
		return $categories;
	}

	$custom_categories_props = wc_category_slider_get_meta( $slider_id, 'categories', [] );
	foreach ( $categories as $key => $category ) {
		$term_id = intval( $category['term_id'] );

		if ( empty( $term_id ) || ! isset( $custom_categories_props[ $term_id ] ) ) {
			continue;
		}
		$custom_category_props = $custom_categories_props[ $term_id ];
		foreach ( $category as $category_key => $value ) {
			if ( ! isset( $custom_category_props[ $category_key ] ) || empty( $custom_category_props[ $category_key ] ) ) {
				continue;
			}

			$categories[ $key ][ $category_key ] = $custom_category_props[ $category_key ];
		}

	}

	return $categories;
}

add_filter( 'wc_category_slider_categories', 'wc_category_slider_load_custom_category_attributes', 10, 2 );

function wc_category_slider_print_js_template() {

	global $current_screen;

	if ( empty( $current_screen->id ) || ( 'wc_category_slider' !== $current_screen->id ) ) {
		return;
	}

	$disabled = wc_category_slider()->is_pro_installed() ? '' : 'disabled';


	?>
	<script type="text/html" id="tmpl-wc-category-slide">

		<div class="ever-col-6 ever-slider-container">
			<div class="ever-slide">
				<div class="ever-slide-header">
					<div class="ever-slide-headerleft">{{data.name}}</div>
				</div>
				<div class="ever-slide-main">
					<div class="ever-slide-thumbnail">
						<img src="{{data.image}}" class="img-prev" alt="{{data.name}}">
						<input type="hidden" name="categories[{{data.term_id}}][image_id]" class="wccs-slider img-id" value="{{data.image_id}}">
						<div class="ever-slide-thumbnail-tools">
							<?php if ( ! wc_category_slider()->is_pro_installed() ) { ?>
								<div class="promotion-text">
									<span>Upgrade to <a href="https://www.pluginever.com/plugins/woocommerce-category-slider-pro/">PRO</a>, to change the Image</span>
								</div>
							<?php } ?>
							<div class="image-action">
								<a href="javascript:void(0)" class="edit-image"><span class="dashicons dashicons-edit" title="<?php _e('Change Image', 'woo-category-slider-by-pluginever') ?>"></span></a>
								<a href="javascript:void(0)" class="delete-image" title="<?php _e('Delete Image', 'woo-category-slider-by-pluginever') ?>"><span class="dashicons dashicons-trash"></span></a>
							</div>

						</div>
					</div>
					<div class="ever-slide-inner">

						<!--title-->
						<div class="ever-slide-title">
							<input class="ever-slide-url-inputbox regular-text" name="categories[{{data.term_id}}][name]" placeholder="{{data.name}}" type="text" value="{{data.name}}" <?php echo $disabled ?>>
						</div>

						<!--description-->
						<div class="ever-slide-captionarea">
							<textarea name="categories[{{data.term_id}}][description]" id="caption-{{data.term_id}}" class="ever-slide-captionarea-textfield" data-gramm_editor="false" placeholder="Description" <?php echo $disabled ?>>{{data.description}}</textarea>
						</div>

						<!--icon-->
						<div class="ever-slide-icon">
							<select name="categories[{{data.term_id}}][icon]" id="categories-{{data.term_id}}-icon" class="select-2">
								<option value=""><?php _e('No Icon', 'woo-category-slider-by-pluginever'); ?></option>
								<?php

								$icons = wc_slider_get_icon_list();

								ob_start();

								if ( ! wc_category_slider()->is_pro_installed() ) {

									for ( $a = 0; $a < 2; $a ++ ) {

										$offset       = $a == 0 ? 0 : 10;
										$length       = $a == 0 ? 10 : - 1;
										$sliced_icons = array_slice( $icons, $offset, $length );

										$label    = sprintf( __( '%s Icons', 'woo-category-slider-by-pluginever' ), $a == 0 ? 'Free' : 'Pro' );
										$disabled = $a == 0 ? '' : 'disabled';

										echo "<optgroup label='{$label}'>";

										foreach ( $sliced_icons as $key => $value ) {
											echo sprintf( '<option value="%s" %s  <# if( data.icon == "' . $key . '" ){ #> selected <# } #> >&#x%s; &nbsp; %1$s</option>', $key, $disabled, $value );
										}

										echo '</optgroup>';

									}
								} else {
									foreach ( $icons as $key => $value ) {
										echo sprintf( '<option value="%s"  <# if( data.icon == "' . $key . '" ){ #> selected <# } #> >&#x%s; &nbsp; %1$s</option>', $key, $value );
									}
								}

								$output = ob_get_clean();

								echo $output;

								?>

							</select>


						</div>

						<!--url-->
						<div class="ever-slide-url">
							<input name="categories[{{data.term_id}}][url]" class="ever-slide-url-inputbox regular-text" placeholder="{{data.url}}" value="{{data.url}}" type="url" <?php echo $disabled ?>>
						</div>

					</div>
				</div>
			</div>
		</div>

		<?php if ( wc_category_slider()->is_pro_installed() ) {
			//=== category image change js scripts ===
			?>
			<#

			$(document).on('click', '.edit-image', function (e) {			e.preventDefault();			e.stopPropagation();			e.stopImmediatePropagation();


			var $parent = jQuery(this).parentsUntil('.ever-slide-thumbnail');

			var $img_prev = $parent.siblings('.img-prev');			var $img_id = $parent.siblings('.img-id');

			var image = wp.media({			title: 'Upload Image'			})			.open().on('select', function () {			var uploaded_image = image.state().get('selection').first();			var image_url = uploaded_image.toJSON().url;			var image_id = uploaded_image.toJSON().id;			$img_prev.prop('src', image_url);			$img_id.val(image_id);			});

			});

			$(document).on('click', '.delete-image', function(e){			e.preventDefault();			e.stopPropagation();			e.stopImmediatePropagation();

			var $parent = jQuery(this).parentsUntil('.ever-slide-thumbnail');

			var $img_prev = $parent.siblings('.img-prev');			var $img_id = $parent.siblings('.img-id');			$img_prev.prop('src', '<?php echo WC_CAT_SLIDER_ASSETS_URL . '/images/no-image-placeholder.jpg'; ?>');			$img_id.val('');

			});

			#>

		<?php } ?>

	</script>
	<?php
}

add_action( 'admin_footer', 'wc_category_slider_print_js_template' );

function wc_category_slider_rest_api() {
	$namespace = 'wc-category-slider/v1';

	register_rest_route($namespace, '/slider/all', array(
		array(
			'methods' => 'GET',
			'callback' => 'wc_category_slider_rest_api_get_all_sliders'
		)
	));

	register_rest_route($namespace, '/slider/(?P<id>\d+)', array(
		array(
			'methods' => 'GET',
			'callback' => 'wc_category_slider_rest_api_get_slider_preview'
		)
	));
}
add_action( 'rest_api_init', 'wc_category_slider_rest_api' );

function wc_category_slider_rest_api_get_all_sliders() {
	$capability = 'edit_others_posts';
	if ( ! current_user_can( $capability ) ) {
		return wp_send_json_error(array(
			'message' => __('You do not have access to this resource.', 'woo-category-slider-by-pluginever')
		), 401);
	}

	$slider_posts = get_posts( array(
		'post_type' => 'wc_category_slider',
		'posts_per_page' => -1,
	) );

	$sliders = array();

	foreach ($slider_posts as $slider_post) {
		$sliders[ $slider_post->ID ] = $slider_post->post_title;
	}


	return wp_send_json_success( $sliders );
}

function wc_category_slider_rest_api_get_slider_preview( $data ) {
	$capability = 'edit_others_posts';
	if ( ! current_user_can( $capability ) ) {
		return wp_send_json_error(array(
			'message' => __('You do not have access to this resource.', 'woo-category-slider-by-pluginever')
		), 401);
	}

	$slider_id = isset( $data['id'] ) ? $data['id'] : false;

	if ( $slider_id === false ) {
		return wp_send_json_error(array(
			'message' => __('Given slider ID is not valid.', 'woo-category-slider-by-pluginever')
		), 404);
	}

	
	// $slide_view = wc_category_slider_get_slider_preview_html( $slider_id );
	

	return wp_send_json_success( do_shortcode( '[woo_category_slider id="' . $slider_id . '"]' ) );
}

function wc_category_slider_get_slider_preview_html( $id ) {
	ob_start();
	?>
<!DOCTYPE html>
<html>
	<head>
		<?php wp_head(); ?>
	</head>
	<body>
		<?php echo do_shortcode( '[woo_category_slider id="' . $id . '"]' ); ?>

		<?php wp_footer(); ?>
	</body>
</html>
	<?php

	return preg_replace('/\s+/S', " ", ob_get_clean());
}
