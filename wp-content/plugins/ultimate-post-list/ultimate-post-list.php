<?php

/**
 * @link              http://stehle-internet.de/
 * @since             1.0.0
 * @package           Ultimate_Post_List
 *
 * @wordpress-plugin
 * Plugin Name:       Ultimate Post List
 * Plugin URI:        http://stehle-internet.de/upl
 * Description:       Make up custom-tailored preview lists of the contents easily and place them in widget areas and post contents.
 * Version:           4.4.0
 * Author:            Martin Stehle
 * Author URI:        http://stehle-internet.de/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ultimate-post-list
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The constants for this plugin
 */
define( 'UPL_NAME', 'Ultimate Post List' );
define( 'UPL_VERSION', '4.4.0' );
define( 'UPL_ROOT', plugin_dir_path( __FILE__ ) );
define( 'UPL_URL', plugin_dir_url( __FILE__ ) );
define( 'UPL_ROOT_FILE', plugin_basename( __FILE__ ) );
define( 'UPL_POST_TYPE', 'upl_post_list' );
define( 'UPL_SHORTCODE_NAME', 'ultimate_post_list' );
define( 'UPL_OPTION_NAME', 'ultimate_post_list_settings' );
define( 'UPL_CLONE_ACTION_NAME', 'clone_upl' );
define( 'UPL_TRANSIENT_PLUGIN_ACTIVATED', 'ultimate-post-list-plugin-activated' );
define( 'UPL_TRANSIENT_LIST_CLONED', 'ultimate-post-list-list-cloned' );
define( 'UPL_CSS_FILE_PATH', dirname( __FILE__ ) . '/public/css/ultimate-post-list-public.css' );
define( 'UPL_CSS_FILE_URL', plugin_dir_url( __FILE__ ) . 'public/css/ultimate-post-list-public.css' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ultimate-post-list-activator.php
 */
function activate_ultimate_post_list() {
	require_once UPL_ROOT . 'includes/class-ultimate-post-list-activator.php';
	Ultimate_Post_List_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ultimate-post-list-deactivator.php
 */
function deactivate_ultimate_post_list() {
	require_once UPL_ROOT . 'includes/class-ultimate-post-list-deactivator.php';
	Ultimate_Post_List_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ultimate_post_list' );
register_deactivation_hook( __FILE__, 'deactivate_ultimate_post_list' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require UPL_ROOT . 'includes/class-ultimate-post-list.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ultimate_post_list() {

	$plugin = new Ultimate_Post_List();
	$plugin->run();

}
run_ultimate_post_list();
