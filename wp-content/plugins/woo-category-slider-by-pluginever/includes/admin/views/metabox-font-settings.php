<?php

global $post;

echo wc_get_metabox_promo_text();

echo wc_category_slider()->elements->select( apply_filters( 'wc_category_slider_title_font_args', array(
	'label'            => __( 'Title Font Family', 'woo-category-slider-by-pluginever' ),
	'name'             => 'title_font',
	'class'            => 'select-2 title-font',
	'show_option_all'  => '',
	'show_option_none' => '',
	'double_columns'   => false,
	'options'          => wc_slider_get_font_list(),
	'required'         => false,
	'disabled'         => false,
	'option_disabled'  => true,
	'desc'             => __( 'Select the font family for title', 'woo-category-slider-by-pluginever' ),
), $post->ID ) );

echo wc_category_slider()->elements->select( apply_filters( 'wc_category_slider_description_font_args', array(
	'label'            => __( 'Description Font Family', 'woo-category-slider-by-pluginever' ),
	'name'             => 'description_font',
	'class'            => 'select-2 description-font',
	'show_option_all'  => '',
	'show_option_none' => '',
	'double_columns'   => false,
	'options'          => wc_slider_get_font_list(),
	'required'         => false,
	'disabled'         => false,
	'option_disabled'  => true,
	'desc'             => __( 'Select the font family for details', 'woo-category-slider-by-pluginever' ),
), $post->ID ) );

echo wc_category_slider()->elements->select( apply_filters( 'wc_category_slider_button_font_args', array(
	'label'            => __( 'Button Font Family', 'woo-category-slider-by-pluginever' ),
	'name'             => 'button_font',
	'class'            => 'select-2 description-font',
	'show_option_all'  => '',
	'show_option_none' => '',
	'double_columns'   => false,
	'options'          => wc_slider_get_font_list(),
	'required'         => false,
	'disabled'         => false,
	'option_disabled'  => true,
	'desc'             => __( 'Select the font family for buttons', 'woo-category-slider-by-pluginever' ),
), $post->ID ) );


