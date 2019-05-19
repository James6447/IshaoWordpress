<?php

/**
 * Fired during plugin activation
 *
 * @link       http://stehle-internet.de/
 * @since      1.0.0
 *
 * @package    Ultimate_Post_List
 * @subpackage Ultimate_Post_List/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ultimate_Post_List
 * @subpackage Ultimate_Post_List/includes
 * @author     Martin Stehle <shop@stehle-internet.de>
 */
class Ultimate_Post_List_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// store the flag into the db to trigger the display of a message after activation
		set_transient( UPL_TRANSIENT_PLUGIN_ACTIVATED, '1', 60 );

	}

}
