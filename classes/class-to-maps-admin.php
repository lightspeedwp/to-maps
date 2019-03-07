<?php
/**
 * LSX_TO_Maps_Admin
 *
 * @package   LSX_TO_Maps_Admin
 * @author    {your-name}
 * @license   GPL-2.0+
 * @link
 * @copyright {year} LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_TO_Maps_Admin
 * @author  LightSpeed
 */

class LSX_TO_Maps_Admin extends LSX_TO_Maps{

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_vars();
		add_action('lsx_to_framework_api_tab_content',array($this,'api_key_settings'),5,1);

		add_action('lsx_to_framework_display_tab_content',array($this,'display_settings'),12,1);

		add_action( 'lsx_to_framework_destination_tab_general_settings_bottom', array( $this, 'general_settings' ), 10, 1 );


		if(!empty($post_types)){
			foreach($this->post_types as $post_type){

				if(isset($this->options[$post_type]['googlemaps_marker']) && '' !== $this->options[$post_type]['googlemaps_marker']){
					$this->markers->post_types[$post_type] = $this->options[$post_type]['googlemaps_marker'];
				}else{
					$this->markers->post_types[$post_type] = LSX_TO_MAPS_URL.'assets/img/'.$post_type.'-marker.png';
				}

				add_action('lsx_to_framework_'.$post_type.'_tab_content',array($this,'post_settings'),10,1);
			}
		}
	}
	/**
	 * outputs the general tabs settings
	 *
	 * @param $tab string
	 * @return null
	 */
	public function api_key_settings($tab='general') {
		?>
		<tr class="form-field-wrap">
			<th class="tour-operator_table_heading" style="padding-bottom:0px;" scope="row" colspan="2">
				<h4 style="margin-bottom:0px;"><span><?php _e( 'Google Maps API', 'to-maps' ); ?></span></h4>
			</th>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<i class="dashicons-before dashicons-admin-network"></i><label for="title"> <?php _e( 'Key', 'to-maps' ); ?></label>
			</th>
			<td>
				<input type="text" {{#if googlemaps_key}} value="{{googlemaps_key}}" {{/if}} name="googlemaps_key" />
			</td>
		</tr>
	<?php
	}

	/**
	 * outputs the display settings
	 *
	 * @param $tab string
	 * @return null
	 */
	public function display_settings($tab = 'general'){
		if('maps' === $tab) {
			$this->map_marker_field();
			$this->cluster_marker_field();
			$this->start_end_marker_fields();
			$this->fusion_tables_fields();
		}
	}

	/**
	 * outputs the post settings
	 *
	 * @param $tab string
	 * @return null
	 */
	public function post_settings($tab = 'general'){
		$this->map_marker_field();
	}

	/**
	 * outputs the map marker upload field
	 */
	public function map_marker_field() { ?>

		<tr class="form-field default-marker-wrap">
			<th scope="row">
				<label for="banner"> <?php esc_html_e( 'Choose a default marker', 'to-maps' ); ?></label>
			</th>
			<td>
				<input class="input_image_id" type="hidden" {{#if googlemaps_marker_id}} value="{{googlemaps_marker_id}}" {{/if}} name="googlemaps_marker_id" />
				<input class="input_image" type="hidden" {{#if googlemaps_marker}} value="{{googlemaps_marker}}" {{/if}} name="googlemaps_marker" />
				<div class="thumbnail-preview">
					{{#if googlemaps_marker}}<img src="{{googlemaps_marker}}" width="48" style="color:black;" />{{/if}}
				</div>
				<a {{#if googlemaps_marker}}style="display:none;"{{/if}} class="button-secondary lsx-thumbnail-image-add"><?php esc_html_e( 'Choose Image', 'to-maps' ); ?></a>
				<a {{#unless googlemaps_marker}}style="display:none;"{{/unless}} class="button-secondary lsx-thumbnail-image-delete"><?php esc_html_e( 'Delete', 'to-maps' ); ?></a>
			</td>
		</tr>
 		<?php
	}

	/**
	 * outputs the cluster marker upload field
	 */
	public function cluster_marker_field() { ?>
		<tr class="form-field default-cluster-small-wrap">
			<th scope="row">
				<label for="banner"> <?php esc_html_e( 'Choose a cluster marker', 'to-maps' ); ?></label>
			</th>
			<td>
				<input class="input_image_id" type="hidden" {{#if gmap_cluster_small_id}} value="{{gmap_cluster_small_id}}" {{/if}} name="gmap_cluster_small_id" />
				<input class="input_image" type="hidden" {{#if gmap_cluster_small}} value="{{gmap_cluster_small}}" {{/if}} name="gmap_cluster_small" />
				<div class="thumbnail-preview">
					{{#if gmap_cluster_small}}<img src="{{gmap_cluster_small}}" width="48" style="color:black;" />{{/if}}
				</div>
				<a {{#if gmap_cluster_small}}style="display:none;"{{/if}} class="button-secondary lsx-thumbnail-image-add"><?php esc_html_e( 'Choose Image', 'to-maps' ); ?></a>
				<a {{#unless gmap_cluster_small}}style="display:none;"{{/unless}} class="button-secondary lsx-thumbnail-image-delete"><?php esc_html_e( 'Delete', 'to-maps' ); ?></a>
			</td>
		</tr>
		<?php
		/*
		<tr class="form-field">
			<th scope="row">
				<label for="title"> Cluster Marker Medium</label>
			</th>
			<td>
				<input type="hidden" {{#if gmap_cluster_medium_id}} value="{{gmap_cluster_medium_id}}" {{/if}} name="gmap_cluster_medium_id" />
				<input type="hidden" {{#if gmap_cluster_medium}} value="{{gmap_cluster_medium}}" {{/if}} name="gmap_cluster_medium" />
				<div class="thumbnail-preview">
					{{#if gmap_cluster_medium}}<img src="{{gmap_cluster_medium}}" width="48" style="color:black;" />{{/if}}
				</div>

				<a {{#if gmap_cluster_medium}}style="display:none;"{{/if}} class="button-secondary lsx-thumbnail-image-add" data-slug="gmap_cluster_medium">Choose Image</a>

				<a {{#unless gmap_cluster_medium}}style="display:none;"{{/unless}} class="button-secondary lsx-thumbnail-image-delete" data-slug="gmap_cluster_medium">Delete</a>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="title"> Cluster Marker Large</label>
			</th>
			<td>
				<input type="hidden" {{#if gmap_cluster_large_id}} value="{{gmap_cluster_large_id}}" {{/if}} name="gmap_cluster_large_id" />
				<input type="hidden" {{#if gmap_cluster_large}} value="{{gmap_cluster_large}}" {{/if}} name="gmap_cluster_large" />
				<div class="thumbnail-preview">
					{{#if gmap_cluster_large}}<img src="{{gmap_cluster_large}}" width="48" style="color:black;" />{{/if}}
				</div>

				<a {{#if gmap_cluster_large}}style="display:none;"{{/if}} class="button-secondary lsx-thumbnail-image-add" data-slug="gmap_cluster_large">Choose Image</a>

				<a {{#unless gmap_cluster_large}}style="display:none;"{{/unless}} class="button-secondary lsx-thumbnail-image-delete" data-slug="gmap_cluster_large">Delete</a>
			</td>
		</tr>
		*/
	}

	/**
	 * outputs the start/end marker upload field
	 */
	public function start_end_marker_fields() { ?>
		<tr class="form-field default-cluster-small-wrap">
			<th scope="row">
				<label for="banner"> <?php esc_html_e( 'Choose a start marker', 'to-maps' ); ?></label>
			</th>
			<td>
				<input class="input_image_id" type="hidden" {{#if gmap_marker_start_id}} value="{{gmap_marker_start_id}}" {{/if}} name="gmap_marker_start_id" />
				<input class="input_image" type="hidden" {{#if gmap_marker_start}} value="{{gmap_marker_start}}" {{/if}} name="gmap_marker_start" />
				<div class="thumbnail-preview">
					{{#if gmap_marker_start}}<img src="{{gmap_marker_start}}" width="48" style="color:black;" />{{/if}}
				</div>
				<a {{#if gmap_marker_start}}style="display:none;"{{/if}} class="button-secondary lsx-thumbnail-image-add"><?php esc_html_e( 'Choose Image', 'to-maps' ); ?></a>
				<a {{#unless gmap_marker_start}}style="display:none;"{{/unless}} class="button-secondary lsx-thumbnail-image-delete"><?php esc_html_e( 'Delete', 'to-maps' ); ?></a>
			</td>
		</tr>
		<tr class="form-field default-cluster-small-wrap">
			<th scope="row">
				<label for="banner"> <?php esc_html_e( 'Choose a end marker', 'to-maps' ); ?></label>
			</th>
			<td>
				<input class="input_image_id" type="hidden" {{#if gmap_marker_end_id}} value="{{gmap_marker_end_id}}" {{/if}} name="gmap_marker_end_id" />
				<input class="input_image" type="hidden" {{#if gmap_marker_end}} value="{{gmap_marker_end}}" {{/if}} name="gmap_marker_end" />
				<div class="thumbnail-preview">
					{{#if gmap_marker_end}}<img src="{{gmap_marker_end}}" width="48" style="color:black;" />{{/if}}
				</div>
				<a {{#if gmap_marker_end}}style="display:none;"{{/if}} class="button-secondary lsx-thumbnail-image-add"><?php esc_html_e( 'Choose Image', 'to-maps' ); ?></a>
				<a {{#unless gmap_marker_end}}style="display:none;"{{/unless}} class="button-secondary lsx-thumbnail-image-delete"><?php esc_html_e( 'Delete', 'to-maps' ); ?></a>
			</td>
		</tr>
		<?php
	}

	/**
	 * outputs the map marker upload field
	 */
	public function fusion_tables_fields() { ?>
		<tr class="form-field">
			<th scope="row" colspan="2">
				<label>
					<h3><?php esc_html_e('Fusion Tables Settings','to-maps'); ?></h3>
				</label>
			</th>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="fusion_tables_enabled"><?php esc_html_e('Enable Fusion Tables','to-maps'); ?></label>
			</th>
			<td>
				<input type="checkbox" {{#if fusion_tables_enabled}} checked="checked" {{/if}} name="fusion_tables_enabled" />
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="title"><?php esc_html_e('Border Width','to-maps'); ?></label>
			</th>
			<td>
				<input type="text" maxlength="2" {{#if fusion_tables_width_border}} value="{{fusion_tables_width_border}}" {{/if}} name="fusion_tables_width_border" />
				<br>
				<small>Default value: 2</small>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="title"><?php esc_html_e('Border Colour','to-maps'); ?></label>
			</th>
			<td>
				<input type="text" maxlength="7" {{#if fusion_tables_colour_border}} value="{{fusion_tables_colour_border}}" {{/if}} name="fusion_tables_colour_border" />
				<br>
				<small>Default value: #000000</small>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="title"><?php esc_html_e('Background Colour','to-maps'); ?></label>
			</th>
			<td>
				<input type="text" maxlength="7" {{#if fusion_tables_colour_background}} value="{{fusion_tables_colour_background}}" {{/if}} name="fusion_tables_colour_background" />
				<br>
				<small>Default value: #000000</small>
			</td>
		</tr>
 		<?php
 	}

	/**
	 * Displays the destination specific settings
	 *
	 * @param $post_type string
	 * @param $tab       string
	 *
	 * @return null
	 */
	public function general_settings() {
		?>
		<tr class="form-field -wrap">
			<th scope="row">
				<label for="enable_banner_map"><?php esc_html_e( 'Display the map in the banner', 'tour-operator' ); ?></label>
			</th>
			<td>
				<input type="checkbox" {{#if enable_banner_map}} checked="checked" {{/if}} name="enable_banner_map" />
			</td>
		</tr>
		<tr class="form-field -wrap">
			<th scope="row">
				<label for="disable_banner_map_cluster"><?php esc_html_e( 'Disable Banner Map Cluster', 'tour-operator' ); ?></label>
			</th>
			<td>
				<input type="checkbox" {{#if disable_banner_map_cluster}} checked="checked" {{/if}} name="disable_banner_map_cluster" />
			</td>
		</tr>

		<?php
	}

}
new LSX_TO_Maps_Admin();
