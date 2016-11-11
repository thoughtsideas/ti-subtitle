<?php
/**
 * Plugin Name: TI Subtitle
 * Plugin URI: https://github.com/thoughtsideas/ti-subtitle/
 * Description: Adds a subtitle meta box to posts and pages.
 * Author: Michael Bragg
 * Author URI: http://www.thoughtsideas.uk
 * Version: 0.1.0
 *
 * @package ti-subtitle
 */

/**
 * Controls for Subtilte plugin.
 */
class TI_Subtitle {

	/**
	 * Prefix for metaboxes.
	 *
	 * @var string
	 *
	 * @since 0.1.0
	 */
	public $meta_prefix = '_ti_subtitle';

	/**
	 * Post Types to display subtitle in.
	 *
	 * @var array
	 *
	 * @since 0.1.0
	 */
	public $object_types = array( 'post', 'page' );

	/**
	 * Set defaults for plugin.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

	}

	/**
	 * Add metabox for subtitle content.
	 *
	 * @since 0.1.0
	 */
	public function add_metabox() {

		$ti_subtitle_metabox = new_cmb2_box( array(
			'id'            => $this->meta_prefix,
			'title'         => __( 'Subtitle', 'ti-subtitle' ),
			'object_types'  => $this->object_types,
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => false,
		) );

		$ti_subtitle_metabox->add_field( array(
			'id'						=> $this->meta_prefix . '_field',
			'name'					=> __( 'Subtitle', 'ti-subtitle' ),
			'description'		=> '',
			'type'					=> 'text',
			'options'				=> array(
			'placeholder' => __( 'Enter subtitle', 'ti-subtitle' ),
			),
		) );

	}

	/**
	 * Return subtitle.
	 *
	 * @since 0.1.0
	 *
	 * @param string $before	Apply before the content.
	 * @param string $after	Apply after the content.
	 */
	public function the_subtitle( $before = '', $after = '' ) {

		$title = get_post_meta( get_the_ID(), $this->meta_prefix . '_field', true );

		if ( strlen( $title ) === 0 ) {
			return;
		}

		$title = $before . esc_html( $title ) . $after;

		echo $title; // WPCS: XSS ok.

	}

}

/**
 * Return the one true TI Subtitle instance.
 *
 * @since 0.1.0
 */
function ti_subtitle_init() {

	global $ti_subtitle;

	if ( ! isset( $ti_subtitle ) ) {
		$ti_subtitle = new TI_Subtitle();
	}

	return $ti_subtitle;

}

add_action( 'plugins_loaded', 'ti_subtitle_init' );

/**
 * Setup the hooks and filters needed to run the plugin.
 *
 * Delay this until after theme is setup. Allows filter to be added inside
 * the `functions.php` file.
 */
function ti_subtitle_setup() {

	global $ti_subtitle;

	/** Check CMB2 dependancy is loaded. */
	if ( ! defined( 'CMB2_LOADED' ) ) {
		/** TODO include Admin warning message. */
		return;
	}

	/** Update object types supplied outside of plugin. */
	if ( has_filter( 'ti_subtitle_object_types' ) ) {

		$ti_subtitle->object_types = apply_filters(
			'ti_subtitle_object_types',
			$ti_subtitle->object_types
		);

	}

	add_action(
		'cmb2_admin_init',
		array( $ti_subtitle, 'add_metabox' )
	);

	add_action(
		'ti_the_subtitle',
		array( $ti_subtitle, 'the_subtitle' ),
		10,
		2
	);
}

add_action( 'after_setup_theme', 'ti_subtitle_setup' );
