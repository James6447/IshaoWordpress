<?php

function wc_category_slider_update_4_0_0() {
	$args = array(
		'post_type'   => 'wc_category_slider',
		'numberposts' => - 1,
	);

	$posts = get_posts( $args );

	foreach ( $posts as $post ) {

		$category_images = array_filter( (array) wc_category_slider_get_meta( $post->ID, 'wcs_category_images' ) );

		$conditional_metas = array(
			'include_child',
			'hide_empty',
			'hide_image',
			'hide_content',
			'hide_button',
			'hide_name',
			'hide_count',
			'hide_nav',
			'hide_border',
			'show_desc',
			'autoplay',
			'lazy_load',
			'loop',
		);

		//update category images
		if ( ! empty( $category_images ) ) {
			foreach ( $category_images as $cat_id => $img_url ) {
				$img_id = attachment_url_to_postid( $img_url );

				if ( ! empty( $img_id ) ) {

					$term_thumbnail = intval( get_term_meta( $cat_id, 'thumbnail_id', true ) );

					if ( $img_id == $term_thumbnail ) {
						continue;
					}

					//$cat_data = wc_category_slider_get_meta( $post->ID, 'categories' );

					$cat_data[ $cat_id ]['image_id'] = $img_id;

					delete_post_meta( $post->ID, 'categories' );

					update_post_meta( $post->ID, 'categories', $cat_data );

				}
			}
		}

		//update conditional meta
		foreach ( $conditional_metas as $meta_key ) {
			$old_value = wc_category_slider_get_meta( $post->ID, $meta_key );

			$new_value = ! empty( $old_value ) ? 'on' : 'off';

			delete_post_meta( $post->ID, $meta_key );
			update_post_meta( $post->ID, $meta_key, $new_value );

		}

	}

}

wc_category_slider_update_4_0_0();
