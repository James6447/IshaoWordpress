<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

class class_post_grid_shortcodes{
	
	
    public function __construct(){
		
		add_shortcode( 'post_grid', array( $this, 'post_grid_new_display' ) );
	
	
	
		//include( 'shortcodes/shortcode-sticky-posts.php' );
		//include( 'shortcodes/shortcode-today-date.php' );
    }

	
	public function post_grid_new_display($atts, $content = null ){
		
		
			$atts = shortcode_atts(
				array(
					'id' => "",
					///'paging'=> 'pg'. $w4dev_custom_loop,
					), $atts);

				$grid_id = $atts['id'];
				
				wp_reset_postdata();
				//var_dump(get_the_ID());
				
				ob_start();
				
				include( post_grid_plugin_dir . 'templates/post-grid.php');
		
				return ob_get_clean();
				
		
		}	

	}

new class_post_grid_shortcodes();