<?php
/*
 * Plugin Name:	Tour Operator Maps
 * Plugin URI:	https://www.lsdev.biz/product/tour-operator-maps/
 * Description:	The Maps extension gives you the ability to connect maps with location-marking pins to your Accommodation, Destination and Tour pages. You can also display maps with clusters of markers, to display the set of Accommodations in each Destination. Use the extension to show a mapped out trip itinerary that connects the dots of the accommodations you stay in on your tours. Maps will also integrate with the Activities post type, if you have that extension installed.
 * Version:     1.1.2
 * Author:      LightSpeed
 * Author URI: 	https://www.lsdev.biz/
 * License: 	GPL3+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: to-maps
 * Domain Path: /languages/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('LSX_TO_MAPS_PATH',  plugin_dir_path( __FILE__ ) );
define('LSX_TO_MAPS_CORE',  __FILE__ );
define('LSX_TO_MAPS_URL',  plugin_dir_url( __FILE__ ) );
define('LSX_TO_MAPS_VER',  '1.1.2' );


/**
 * Runs once when the plugin is activated.
 */
function lsx_to_maps_activate_plugin() {
    $lsx_to_password = get_option('lsx_api_instance',false);
    if(false === $lsx_to_password){
    	update_option('lsx_api_instance',LSX_API_Manager::generatePassword());
    }
}
register_activation_hook( __FILE__, 'lsx_to_maps_activate_plugin' );


/* ======================= The API Classes ========================= */
if(!class_exists('LSX_API_Manager')){
	require_once('classes/class-lsx-api-manager.php');
}

/**
 *	Grabs the email and api key from the LSX Search Settings.
 */
function lsx_to_maps_options_pages_filter($pages){
	$pages[] = 'lsx-to-settings';
	return $pages;
}
add_filter('lsx_api_manager_options_pages','lsx_to_maps_options_pages_filter',10,1);


function lsx_to_maps_api_admin_init(){
	$options = get_option('_lsx-to_settings',false);
	$data = array('api_key'=>'','email'=>'');

	if(false !== $options && isset($options['api'])){
		if(isset($options['api']['to-maps_api_key']) && '' !== $options['api']['to-maps_api_key']){
			$data['api_key'] = $options['api']['to-maps_api_key'];
		}
		if(isset($options['api']['to-maps_email']) && '' !== $options['api']['to-maps_email']){
			$data['email'] = $options['api']['to-maps_email'];
		}
	}

	$instance = get_option( 'lsx_api_instance', false );
	if(false === $instance){
		$instance = LSX_API_Manager::generatePassword();
	}

	$api_array = array(
		'product_id'	=>		'TO Maps',
		'version'		=>		'1.1.1',
		'instance'		=>		$instance,
		'email'			=>		$data['email'],
		'api_key'		=>		$data['api_key'],
		'file'			=>		'to-maps.php',
		'documentation' =>		'tour-operator-maps'
	);

	$lsx_to_api_manager = new LSX_API_Manager($api_array);
}
add_action('admin_init','lsx_to_maps_api_admin_init');



/* ======================= Below is the Plugin Class init ========================= */

require_once( LSX_TO_MAPS_PATH . '/classes/class-to-maps.php' );
