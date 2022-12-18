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
 * @param object $cmb  CMB2.
 * @param array  $args Arguments.
 *
 * @return void
 */
function sl_cache_options_page_message_callback( $cmb, $args ) {
	if ( ! empty( $args['should_notify'] ) ) {

		if ( $args['is_updated'] ) {
			$args['message'] = 'Tutti i prodotti aggiornati.';
		}

		if ( '' === $args['is_updated'] ) {
			$args['message'] = 'Nessun prodotto aggiornato.';
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
			'title'        => 'Opzioni Tema',
			'object_types' => array( 'options-page' ),
			'option_key'   => 'smarz_theme_options',
			'icon_url'     => 'dashicons-hammer',
			'menu_title'   => 'Opzioni',
			'message_cb'   => 'sl_cache_options_page_message_callback',
			'save_button'  => 'Aggiorna',
		)
	);

	$cmb_options->add_field(
		array(
			'name'              => 'Aggiornare prodotti da eBay?',
			'id'                => 'smarz_cache',
			'type'              => 'multicheck_inline',
			'options'           => array(
				'yes' => 'Si',
			),
			'select_all_button' => false,
		)
	);

}

add_action( 'cmb2_admin_init', 'sl_register_theme_options' );

/**
 * Update option for eBay items.
 *
 * @param string $option    Name of the updated option.
 * @param mixed  $old_value The old option value.
 * @param mixed  $value     The new option value.
 *
 * @return void
 */
function sl_updated_option_cache( $option, $old_value, $value ) {
	if ( ( 'smarz_theme_options' === $option ) && isset( $value['smarz_cache'][0] ) && ( 'yes' === $value['smarz_cache'][0] ) ) {

		update_option( 'smarz_theme_options', $old_value );

		delete_transient( 'ebay_items' );
		set_transient( 'ebay_items', sl_get_ebay_all_items(), 12 * HOUR_IN_SECONDS );
	}

}

add_filter( 'updated_option', 'sl_updated_option_cache', 10, 3 );
