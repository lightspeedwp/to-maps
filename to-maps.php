<?php
/*
 * Plugin Name:	Tour Operator Maps
 * Plugin URI:	https://www.lsdev.biz/product/tour-operator-maps/
 * Description:	The Maps extension gives you the ability to connect maps with location-marking pins to your Accommodation, Destination and Tour pages. You can also display maps with clusters of markers, to display the set of Accommodations in each Destination. Use the extension to show a mapped out trip itinerary that connects the dots of the accommodations you stay in on your tours. Maps will also integrate with the Activities post type, if you have that extension installed.
 * Version:     1.1.3
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
define('LSX_TO_MAPS_VER',  '1.1.3' );


/* ======================= Below is the Plugin Class init ========================= */

require_once( LSX_TO_MAPS_PATH . '/classes/class-to-maps.php' );
