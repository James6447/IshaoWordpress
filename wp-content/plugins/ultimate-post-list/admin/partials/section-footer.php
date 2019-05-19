<?php

/**
 * Provide the footer of an admin page
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://stehle-internet.de/
 * @since      1.0.0
 *
 * @package    Ultimate_Post_List
 * @subpackage Ultimate_Post_List/admin/partials
 */
?>
			</div><!-- .upl_content -->
		</div><!-- #upl_main -->
		<div id="upl_footer">
			<div class="upl_content">
				<h2><?php esc_html_e( 'Helpful Links', 'ultimate-post-list' ); ?></h2>
				<p><?php
				$link = sprintf( '<a href="https://wordpress.org/support/view/plugin-reviews/ultimate-post-list">%s</a>', esc_html__( 'Reviews at wordpress.org', 'ultimate-post-list' ) );
				esc_html_e( 'Do you like the plugin?', 'ultimate-post-list' ); ?>
				<?php printf( esc_html__( 'Rate it on %s.', 'ultimate-post-list' ), $link );
				?></p>
				<p>&copy; <?php echo date( 'Y' );?> <a href="http://stehle-internet.de/">Martin Stehle</a>, Berlin, Germany</p>
			</div><!-- .upl_content -->
		</div><!-- #upl_footer -->
	</div><!-- .upl_wrapper -->
</div><!-- .wrap -->
