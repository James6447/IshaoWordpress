<?php

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class Woolentor_Admin_Setting{

    public function __construct(){
        add_action('admin_enqueue_scripts', array( $this, 'woolentor_enqueue_admin_scripts' ) );
        $this->woolentor_admin_settings_page();
    }

    /*
    *  Setting Page
    */
    public function woolentor_admin_settings_page() {
        require_once('include/class.settings-api.php');
        require_once('include/admin-setting.php');
    }

    /*
    *  Enqueue admin scripts
    */
    public function woolentor_enqueue_admin_scripts(){
        wp_enqueue_style( 'woolentor-admin', WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/css/admin_optionspanel.css', FALSE, WOOLENTOR_VERSION );
        
        wp_enqueue_script( 'woolentor-admin-main', WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/js/woolentor-admin.js', array('jquery'), WOOLENTOR_VERSION, TRUE );
    }

}

new Woolentor_Admin_Setting();