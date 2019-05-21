<?php
namespace Pluginever\WoocommerceCategorySlider;

class Install{

	/**
	 * Constructor for the class
	 *
	 * Sets up all the appropriate hooks and actions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		register_activation_hook( WCS_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( WCS_FILE, array( $this, 'deactivate' ) );

    }

    /**
	 * Executes during plugin activation
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function activate() {
        if ( false == get_option( 'woocatslider_install_date' ) ) {
            update_option( 'woocommerce_category_slider_install_date', current_time( 'timestamp' ) );
        }else{
            update_option( 'woocommerce_category_slider_install_date', get_option( 'woocatslider_install_date' ) );
            delete_option('woocatslider_install_date');
        }
	}

	/**
	 * Executes during plugin deactivation
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function deactivate() {

	}



}
