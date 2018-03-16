<?php
/**
 * LSX_TO_Maps
 *
 * @package   LSX_TO_Maps
 * @author    {your-name}
 * @license   GPL-2.0+
 * @link
 * @copyright {year} LightSpeedDevelopment
 */
if (!class_exists( 'LSX_TO_Maps' ) ) {

	/**
	 * Main plugin class.
	 *
	 * @package LSX_TO_Maps
	 * @author  {your-name}
	 */
	class LSX_TO_Maps {

		/** @var string */
		public $plugin_slug = 'to-maps';

		/**
		 * Holds an array of the post types you can assign Map Markers to.
		 *
		 * @since 0.0.1
		 *
		 * @var      object|Lsx
		 */
		public $markers = false;

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->set_vars();

			// Make TO last plugin to load
			add_action( 'activated_plugin', array( $this, 'activated_plugin' ) );

			add_action('init',array($this,'load_plugin_textdomain'));

			require_once(LSX_TO_MAPS_PATH . '/classes/class-to-maps-admin.php');
			require_once(LSX_TO_MAPS_PATH . '/classes/class-to-maps-frontend.php');
			require_once(LSX_TO_MAPS_PATH . '/includes/template-tags.php');

			// flush_rewrite_rules()
			register_activation_hook( LSX_TO_MAPS_CORE, array( $this, 'register_activation_hook' ) );
			add_action( 'admin_init', array( $this, 'register_activation_hook_check' ) );
		}
		/**
		 * sets the variables
		 */
		public function set_vars() {
			$this->options = get_option('_lsx-to_settings',false);
			$this->post_types = array('accommodation','activity','destination');

			$this->markers = new stdClass();

			if((false !== $this->options && isset($this->options['api']['googlemaps_key'])) || defined('GOOGLEMAPS_API_KEY')){

				if(!defined('GOOGLEMAPS_API_KEY')) {
					$this->api_key = $this->options['api']['googlemaps_key'];
				}else{
					$this->api_key = GOOGLEMAPS_API_KEY;
				}

				if(isset($this->options['display']['googlemaps_marker']) && '' !== $this->options['display']['googlemaps_marker']){
					$this->markers->default_marker = $this->options['display']['googlemaps_marker'];
				}else{
					$this->markers->default_marker = LSX_TO_MAPS_URL.'assets/svg/gmaps-mark.svg';
				}

				if(isset($this->options['display']['gmap_cluster_small']) && '' !== $this->options['display']['gmap_cluster_small']){
					$this->markers->cluster_small = $this->options['display']['gmap_cluster_small'];
				}else{
					$this->markers->cluster_small = LSX_TO_MAPS_URL.'assets/img/m1.png';
				}

				if(isset($this->options['display']['gmap_cluster_medium']) && '' !== $this->options['display']['gmap_cluster_medium']){
					$this->markers->cluster_medium = $this->options['display']['gmap_cluster_medium'];
				}else{
					$this->markers->cluster_medium = LSX_TO_MAPS_URL.'assets/img/m2.png';
				}

				if(isset($this->options['display']['gmap_cluster_large']) && '' !== $this->options['display']['gmap_cluster_large']){
					$this->markers->cluster_large = $this->options['display']['gmap_cluster_large'];
				}else{
					$this->markers->cluster_large = LSX_TO_MAPS_URL.'assets/img/m3.png';
				}

				if(isset($this->options['display']['gmap_marker_start']) && '' !== $this->options['display']['gmap_marker_start']){
					$this->markers->start = $this->options['display']['gmap_marker_start'];
				}else{
					$this->markers->start = LSX_TO_MAPS_URL.'assets/img/start-marker.png';
				}

				if(isset($this->options['display']['gmap_marker_end']) && '' !== $this->options['display']['gmap_marker_end']){
					$this->markers->end = $this->options['display']['gmap_marker_end'];
				}else{
					$this->markers->end = LSX_TO_MAPS_URL.'assets/img/end-marker.png';
				}

			}else{
				$this->api_key = false;
			}
		}

		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'to-maps', FALSE, basename( LSX_TO_MAPS_PATH ) . '/languages');
		}

		/**
		 * Make TO last plugin to load.
		 */
		public function activated_plugin() {
			if ( $plugins = get_option( 'active_plugins' ) ) {
				$search = preg_grep( '/.*\/tour-operator\.php/', $plugins );
				$key = array_search( $search, $plugins );

				if ( is_array( $search ) && count( $search ) ) {
					foreach ( $search as $key => $path ) {
						array_splice( $plugins, $key, 1 );
						array_push( $plugins, $path );
						update_option( 'active_plugins', $plugins );
					}
				}
			}
		}

		/**
		 * On plugin activation
		 */
		public function register_activation_hook() {
			if ( ! is_network_admin() && ! isset( $_GET['activate-multi'] ) ) {
				set_transient( '_tour_operators_maps_flush_rewrite_rules', 1, 30 );
			}
		}

		/**
		 * On plugin activation (check)
		 */
		public function register_activation_hook_check() {
			if ( ! get_transient( '_tour_operators_maps_flush_rewrite_rules' ) ) {
				return;
			}

			delete_transient( '_tour_operators_maps_flush_rewrite_rules' );
			flush_rewrite_rules();
		}

	}
	$lsx_to_maps = new LSX_TO_Maps();
}
