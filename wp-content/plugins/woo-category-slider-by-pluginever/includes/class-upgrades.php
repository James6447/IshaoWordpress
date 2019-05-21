<?php

/**
 * Plugin Upgrade Routine
 *
 * @since 1.0.0
 */
class WC_Category_Slider_Updates {

	/**
	 * The upgrades
	 *
	 * @var array
	 */
	private static $upgrades = array( '4.0.0' => 'updates/update-4.0.0.php' );

	/**
	 * Check if the plugin needs any update
	 *
	 * @return boolean
	 */
	public function needs_update() {

		// may be it's the first install
		if ( ! $this->get_version() && ! $this->get_installation_date() ) {
			return false;
		}


		if ( version_compare( $this->get_version(), WC_CAT_SLIDER_VERSION, '>=' ) ) {
			return false;
		}

		if ( ! $this->get_version() && $this->get_installation_date() ) {
			return true;
		}

		if ( version_compare( $this->get_version(), WC_CAT_SLIDER_VERSION, '<' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the plugin version
	 *
	 * @return string
	 */
	public function get_version() {
		return get_option( 'wc_category_slider_version' );
	}

	public function get_installation_date() {
		return get_option( 'woocommerce_category_slider_install_date' );
	}

	/**
	 * Perform all the necessary upgrade routines
	 *
	 * @return void
	 */
	function perform_updates() {
		$installed_version = $this->get_version();
		$path              = trailingslashit( dirname( __FILE__ ) );

		foreach ( self::$upgrades as $version => $file ) {
			if ( version_compare( $installed_version, $version, '<' ) ) {
				include $path . $file;
			}
		}

		update_option( 'wc_category_slider_version', WC_CAT_SLIDER_VERSION );
	}

}
