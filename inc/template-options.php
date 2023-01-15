<?php
/**
 * Theme options page.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

/**
 * Modify message after post.
 *
 * @param object $cmb CMB2.
 * @param array  $args Arguments.
 *
 * @return void
 */
function sl_cache_options_page_message_callback( $cmb, $args ) {
	if ( ! empty( $args['should_notify'] ) ) {

		if ( $args['is_updated'] ) {
			$args['message'] = esc_html__( 'All products updated.', 'smarz-lab' );
		}

		if ( '' === $args['is_updated'] ) {
			$args['message'] = esc_html__( 'No product updated.', 'smarz-lab' );
		}

		add_settings_error( $args['setting'], $args['code'], $args['message'], $args['type'] );
	}
}

/**
 * Register theme options.
 *
 * @return void
 */
function sl_register_theme_options() {
	$cmb_options = new_cmb2_box(
		array(
			'id'           => 'smarz_theme_options_page',
			'title'        => esc_html__( 'Theme Options', 'smarz-lab' ),
			'object_types' => array( 'options-page' ),
			'option_key'   => 'smarz_theme_options',
			'icon_url'     => 'dashicons-hammer',
			'menu_title'   => esc_html__( 'Theme Options', 'smarz-lab' ),
			'save_button'  => esc_html__( 'Save', 'smarz-lab' ),
			'tab_group'    => 'ebay-options',
			'tab_title'    => esc_html__( 'API Options', 'smarz-lab' ),
		)
	);

	$cmb_options->add_field(
		array(
			'name'       => esc_html__( 'Seller', 'smarz-lab' ),
			'id'         => 'seller',
			'type'       => 'text',
		)
	);

	$cmb_options->add_field(
		array(
			'name'       => esc_html__( 'DevId', 'smarz-lab' ),
			'id'         => 'devId',
			'type'       => 'text',
			'attributes' => array(
				'type' => 'password',
			),
		)
	);

	$cmb_options->add_field(
		array(
			'name'       => esc_html__( 'AppId', 'smarz-lab' ),
			'id'         => 'appId',
			'type'       => 'text',
			'attributes' => array(
				'type' => 'password',
			),
		)
	);

	$cmb_options->add_field(
		array(
			'name'       => esc_html__( 'CertId', 'smarz-lab' ),
			'id'         => 'certId',
			'type'       => 'text',
			'attributes' => array(
				'type' => 'password',
			),
		)
	);

	$cmb_options->add_field(
		array(
			'name'       => esc_html__( 'RuName', 'smarz-lab' ),
			'id'         => 'ruName',
			'type'       => 'text',
			'attributes' => array(
				'type' => 'password',
			),
		)
	);

	$cmb_prod_options = new_cmb2_box(
		array(
			'id'           => 'smarz_theme_products_options_page',
			'title'        => esc_html__( 'Products Options', 'smarz-lab' ),
			'object_types' => array( 'options-page' ),
			'option_key'   => 'smarz_theme_product_options',
			'message_cb'   => 'sl_cache_options_page_message_callback',
			'save_button'  => esc_html__( 'Update', 'smarz-lab' ),
			'parent_slug'  => 'smarz_theme_options',
			'tab_group'    => 'ebay-options',
			'tab_title'    => esc_html__( 'Products Options', 'smarz-lab' ),
		)
	);

	$cmb_prod_options->add_field(
		array(
			'name'              => esc_html__( 'Update products from eBay?', 'smarz-lab' ),
			'id'                => 'smarz_cache',
			'type'              => 'multicheck_inline',
			'options'           => array(
				'yes' => esc_html__( 'Yes', 'smarz-lab' ),
			),
			'select_all_button' => false,
		)
	);

}

add_action( 'cmb2_admin_init', 'sl_register_theme_options' );

/**
 * Update option for eBay items.
 *
 * @param string $option Name of the updated option.
 * @param mixed  $old_value The old option value.
 * @param mixed  $value The new option value.
 *
 * @return void
 */
function sl_updated_option_cache( $option, $old_value, $value ) {
	if ( ( 'smarz_theme_product_options' === $option ) && isset( $value['smarz_cache'][0] ) && ( 'yes' === $value['smarz_cache'][0] ) ) {

		update_option( 'smarz_theme_product_options', $old_value );

		delete_transient( 'ebay_items' );
		set_transient( 'ebay_items', sl_get_ebay_all_items(), 12 * HOUR_IN_SECONDS );
	}

}

add_filter( 'updated_option', 'sl_updated_option_cache', 10, 3 );
