<?php
/*
Plugin Name: Post Grid by PickPlugins
Plugin URI: https://www.pickplugins.com/item/post-grid-create-awesome-grid-from-any-post-type-for-wordpress/
Description: Awesome post grid for query post from any post type and display on grid.
Version: 2.0.34
Author: PickPlugins
Author URI: https://www.pickplugins.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class PostGrid{
	
	
	public function __construct(){
		
		define('post_grid_plugin_url', plugins_url('/', __FILE__) );
		define('post_grid_plugin_dir', plugin_dir_path(__FILE__) );
        define('post_grid_plugin_basename', plugin_basename( __FILE__ ) );
		define('post_grid_wp_url', 'https://wordpress.org/plugins/post-grid/' );
		define('post_grid_pro_url','https://www.pickplugins.com/item/post-grid-create-awesome-grid-from-any-post-type-for-wordpress/' );
		define('post_grid_demo_url', 'https://www.pickplugins.com/demo/post-grid/' );
		define('post_grid_conatct_url', 'https://pickplugins.com/contact/' );
		define('post_grid_qa_url', 'https://www.pickplugins.com/questions/' );
		define('post_grid_plugin_name', 'Post Grid' );
		define('post_grid_version', '2.0.34' );
		define('post_grid_customer_type', 'free' );



        include( 'includes/classes/class-post-grid-support.php' );



        include( 'includes/data-update/class-post-grid-data-update.php' );
        include( 'includes/functions/functions-post-grid-settings.php' );
		//include( ABSPATH.'wp-admin/includes/template.php' );
		include( 'includes/class-functions.php' );
		include( 'includes/class-shortcodes.php' );
		include( 'includes/class-settings.php' );	

        //include( 'includes/post-grid-skin-meta-box.php' );


        include( 'includes/class-post-grid-meta-box.php' );
        include( 'includes/class-settings-tabs.php' );
        include( 'includes/functions/functions-post-grid-meta-box.php' );
        include( 'includes/functions/functions-post-meta-box.php' );

        include( 'includes/post-grid-meta-box.php' );

        include( 'includes/functions/functions-post-grid.php' );


		//include( 'includes/meta.php' );
		include( 'includes/post-meta-settings.php' );		
		include( 'includes/functions.php' );
		include( 'includes/shortcodes/shortcode-current_user_id.php' );





		add_action( 'wp_enqueue_scripts', array( $this, 'post_grid_scripts_front' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'post_grid_scripts_admin' ) );
		add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' ); 
		
		add_action( 'plugins_loaded', array( $this, 'textdomain' ));
		
		register_activation_hook( __FILE__, array( $this, 'post_grid_install' ) );
		register_deactivation_hook( __FILE__, array( $this, 'post_grid_deactivation' ) );
		//register_uninstall_hook( __FILE__, array( $this, 'post_grid_uninstall' ) );


    }




	public function textdomain() {

        $locale = apply_filters( 'plugin_locale', get_locale(), 'post-grid' );
        load_textdomain('post-grid', WP_LANG_DIR .'/post-grid/post-grid-'. $locale .'.mo' );

        load_plugin_textdomain( 'post-grid', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );

	}
		
	public function post_grid_install(){
		
		
		$class_post_grid_functions = new class_post_grid_functions();

		$post_grid_layout_content = get_option('post_grid_layout_content');
		if(empty($post_grid_layout_content)){
			$layout_content_list = $class_post_grid_functions->layout_content_list();
			update_option('post_grid_layout_content', $layout_content_list);
			}
		
		
		//$layout_hover_list = $class_post_grid_functions->layout_hover_list();		
		//update_option('post_grid_layout_hover', $layout_hover_list);
		
/*

		if(!file_exists(ABSPATH."wp-content/uploads/post-grid/")){
			mkdir(ABSPATH."wp-content/uploads/post-grid/");
			}
			
*/
		
		



		/*
		 * Save plugin version information on database
		 * option id: post_grid_info
		 *
		 * */

        $post_grid_info = get_option('post_grid_info');
        $post_grid_info['current_version'] = '2.0.34';
        $post_grid_info['last_version'] = isset($post_grid_info['last_version']) ? $post_grid_info['last_version'] : '2.0.30';
        $post_grid_info['data_update_status'] = isset($post_grid_info['data_update_status']) ? $post_grid_info['data_update_status'] : 'pending';
        update_option('post_grid_info',$post_grid_info);
		

		/*
		 * Custom action hook for plugin activation.
		 * Action hook: post_grid_activation
		 * */
		do_action( 'post_grid_activation' );
		
		}		
		
	public function post_grid_uninstall(){

        /*
         * Custom action hook for plugin uninstall/delete.
         * Action hook: post_grid_uninstall
         * */
		do_action( 'post_grid_uninstall' );
		}		
		
	public function post_grid_deactivation(){

        /*
         * Custom action hook for plugin deactivation.
         * Action hook: post_grid_deactivation
         * */
		do_action( 'post_grid_deactivation' );
		}
		
	
		
	public function post_grid_scripts_front(){
		wp_enqueue_script('jquery');

		wp_enqueue_style('post_grid_style', post_grid_plugin_url.'assets/frontend/css/style-new.css');
		wp_enqueue_script('post_grid_scripts', plugins_url( '/assets/frontend/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
		wp_localize_script('post_grid_scripts', 'post_grid_ajax', array( 'post_grid_ajaxurl' => admin_url( 'admin-ajax.php')));


		//wp_enqueue_script('jquery.mixitup.min', plugins_url( '/assets/frontend/js/jquery.mixitup.min.js' , __FILE__ ) , array( 'jquery' ));
		//wp_enqueue_script('jquery.mixitup-pagination', plugins_url( '/assets/frontend/js/jquery.mixitup-pagination.js' , __FILE__ ) , array( 'jquery' ));

		wp_enqueue_script('masonry.pkgd.min', plugins_url( '/assets/frontend/js/masonry.pkgd.min.js' , __FILE__ ) , array( 'jquery' ));
        wp_enqueue_script('imagesloaded.pkgd.js', plugins_url( '/assets/frontend/js/imagesloaded.pkgd.js' , __FILE__ ) , array( 'jquery' ));

		//wp_enqueue_script('jquery-scrollto', plugins_url( '/assets/frontend/js/jquery-scrollto.js' , __FILE__ ) , array( 'jquery' ));

		//wp_enqueue_script('owl.carousel.min', plugins_url( '/assets/frontend/js/owl.carousel.min.js' , __FILE__ ) , array( 'jquery' ));
		//wp_enqueue_style('owl.carousel.min', post_grid_plugin_url.'assets/frontend/css/owl.carousel.min.css');

		wp_enqueue_style('font-awesome', post_grid_plugin_url.'assets/global/css/font-awesome.min.css');
        wp_enqueue_style('font-awesome5', post_grid_plugin_url.'assets/global/css/fontawesome-5.min.css');

		//wp_enqueue_style('style-woocommerce', post_grid_plugin_url.'assets/frontend/css/style-woocommerce.css');



		wp_enqueue_style('style.skins', post_grid_plugin_url.'assets/global/css/style.skins.css');
		wp_enqueue_style('style.layout', post_grid_plugin_url.'assets/global/css/style.layout.css');
		wp_enqueue_style('style.animate', post_grid_plugin_url.'assets/global/css/animate.css');

		
		}
		
		
	public function post_grid_scripts_admin(){

        $screen = get_current_screen();


		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		//wp_enqueue_script('jquery-ui-droppable');
		
		wp_enqueue_script('post_grid_admin_js', plugins_url( 'assets/admin/js/scripts-new.js' , __FILE__ ) , array( 'jquery' ));
		wp_localize_script( 'post_grid_admin_js', 'post_grid_ajax', array( 'post_grid_ajaxurl' => admin_url( 'admin-ajax.php')));


        wp_enqueue_style('post-grid-meta-box', post_grid_plugin_url.'assets/admin/css/post-grid-meta-box.css');
        wp_enqueue_script('post-grid-meta-box', plugins_url( 'assets/admin/js/post-grid-meta-box.js' , __FILE__ ) , array( 'jquery' ));

		wp_enqueue_style('post_grid_admin_style', post_grid_plugin_url.'assets/admin/css/style-new.css');

		wp_enqueue_style('font-awesome', post_grid_plugin_url.'assets/global/css/font-awesome.min.css');
        wp_enqueue_style('font-awesome5', post_grid_plugin_url.'assets/global/css/fontawesome-5.min.css');

		wp_enqueue_style('post-grid-expandable', post_grid_plugin_url.'assets/admin/css/expandable.css');

		wp_enqueue_style('tooltipster.bundle.min', post_grid_plugin_url.'assets/global/css/tooltipster.bundle.min.css');		
		wp_enqueue_script('tooltipster.bundle.min', plugins_url( 'assets/global/js/tooltipster.bundle.min.js' , __FILE__ ) , array( 'jquery' ));

        wp_enqueue_style('select2.min', post_grid_plugin_url.'assets/admin/css/select2.min.css');
        wp_enqueue_script('select2.min', plugins_url( 'assets/admin/js/select2.full.js' , __FILE__ ) , array( 'jquery' ));

        wp_enqueue_style('post-skin', post_grid_plugin_url.'assets/admin/css/post-skin.css');

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'post_grid_color_picker', plugins_url('/assets/admin/js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );

        if (  $screen->id == 'post_grid' ){

            wp_enqueue_script('codemirror', plugins_url( 'assets/admin/js/codemirror.js' , __FILE__ ) , array( 'jquery' ));
            wp_enqueue_script('simplescrollbars', plugins_url( 'assets/admin/js/simplescrollbars.js' , __FILE__ ) , array( 'jquery' ));
            wp_enqueue_script('css', plugins_url( 'assets/admin/js/css.js' , __FILE__ ) , array( 'jquery' ));
            wp_enqueue_script('javascript', plugins_url( 'assets/admin/js/javascript.js' , __FILE__ ) , array( 'jquery' ));
            wp_enqueue_style('codemirror', post_grid_plugin_url.'assets/admin/css/codemirror.css');
            wp_enqueue_style('simplescrollbars', post_grid_plugin_url.'assets/admin/css/simplescrollbars.css');

            wp_enqueue_style('style.skins', post_grid_plugin_url.'assets/global/css/style.skins.css');
            wp_enqueue_style('style.layout', post_grid_plugin_url.'assets/global/css/style.layout.css');
            wp_enqueue_style('style.animate', post_grid_plugin_url.'assets/global/css/animate.css');

        }



        wp_enqueue_style('settings-tabs', post_grid_plugin_url.'assets/admin/css/settings-tabs.css');
        wp_enqueue_script('settings-tabs', plugins_url( 'assets/admin/js/settings-tabs.js' , __FILE__ ) , array( 'jquery' ));

		}
		
		
	
	}

new PostGrid();