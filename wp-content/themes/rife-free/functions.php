<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/* Here you can insert your functions, filters and actions. */

@ini_set( 'upload_max_filesize', '16M' ); // 單一檔案大小上限
@ini_set( 'post_max_size', '32M');        // POST 資料大小上限
@ini_set( 'memory_limit', '64M' );        // 記憶體上限
@ini_set( 'max_execution_time', '300' );  // 執行時間上限，單位為秒





/* That's all, stop editing! Make a great website!. */

/* Init of the framework */

/* This function exist in WP 4.7 and above.
 * Theme has protection from runing it on version below 4.7
 * However, it has to at least run, to give user info about having not compatible WP version :-)
 */
if( function_exists('get_theme_file_path') ){
	/** @noinspection PhpIncludeInspection */
	require_once( get_theme_file_path( '/advance/class-apollo13-framework.php' ) );
}
else{
	/** @noinspection PhpIncludeInspection */
	require_once( get_template_directory() . '/advance/class-apollo13-framework.php' );
}

$apollo13framework_a13 = new Apollo13Framework();
$apollo13framework_a13->start();