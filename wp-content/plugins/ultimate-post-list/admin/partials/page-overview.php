<?php

/**
 * Provide a page to add a new post list
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://stehle-internet.de/
 * @since      1.0.0
 *
 * @package    Ultimate_Post_List
 * @subpackage Ultimate_Post_List/admin/partials
 */

// get URL of image folder once
$image_root_url = dirname( plugin_dir_url( __FILE__ ) ) . '/images/';

// translate strings of WP core
$text = 'Widgets';
$widgets_link = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'widgets.php^' ) ), esc_html__( $text ) ); 
?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<div class="upl_wrapper">
		<div id="upl_main">
			<div class="upl_content">
				<h2><?php esc_html_e( 'How to use the generator', 'ultimate-post-list' ); ?></h2>
				<ol>
					<li><?php
					printf(
						'<a href="%s">%s</a>', 
						esc_url( admin_url( $this->post_type_new ) ),
						esc_html__( 'Create a new list.', 'ultimate-post-list' )
					); ?></li>
					<li><?php esc_html_e( 'After you published the new list you can use it both as a widget and as content in a post via the shortcode.', 'ultimate-post-list' ); ?></li>
				</ol>
				<h2><?php esc_html_e( 'How to use the list as a widget', 'ultimate-post-list' ); ?></h2>
				<p><?php printf( esc_html__( 'Go to the page %s, move the widget "%s" into the desired area and select the list you want to see in the website.', 'ultimate-post-list' ), $widgets_link, esc_html__( 'Ultimate Post List', 'ultimate-post-list' ) ); ?></p>
				<p><?php esc_html_e( 'You can add a title for the widget to show it on the website. Save the widget. The selected list is displayed on the website immediately.', 'ultimate-post-list' ); ?></p>
				<h2><?php esc_html_e( 'How to use the shortcode', 'ultimate-post-list' ); ?></h2>
				<div id="screenshot-shortcode-box"><img src="<?php echo $image_root_url; ?>screenshot-shortcode-box.gif" alt="<?php esc_attr_e( 'Screenshot of the Shortcode box', 'ultimate-post-list' ); ?>" width="283" height="118"><br><em><?php esc_html_e( 'Screenshot of the Shortcode box', 'ultimate-post-list'); ?></em></div>
				<p><?php esc_html_e( 'Copy the shortcode in the Shortcode box and insert it at the desired place in the content.', 'ultimate-post-list' ); ?></p>
				<p><?php esc_html_e( 'You can find the shortcode both in the table list of all post lists and in the Shortcode column and in the Shortcode box on the edit page of each list in the Shortcode box.', 'ultimate-post-list' ); ?></p>
				<h2><?php esc_html_e( 'Why do I not see the list?', 'ultimate-post-list' ); ?></h2>
				<p><?php esc_html_e( 'You can use only published post lists as a widget or via the shortcode. If a formerly published list would be set to draft or deleted there would be no output. Not even an error message, fortunately. The widget or the shortcode remains in the widget area or in the text editor until you remove it.', 'ultimate-post-list' ); ?></p>

<?php include_once( 'section-footer.php' ); ?>