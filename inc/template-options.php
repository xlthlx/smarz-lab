<?php
/**
 * Theme options page.
 *
 * @package  WordPress
 * @subpackage  Smarz Lab
 */

function smarz_cache_options_page_message_callback( $cmb, $args ) {

	if ( ! empty( $args['should_notify'] ) ) {

		if ( $args['is_updated'] ) {
			$args['message'] = 'Tutti i prodotti aggiornati.';
		}

		if ( $args['is_updated'] === '' ) {
			$args['message'] = 'Nessun prodotto aggiornato.';
		}

		add_settings_error( $args['setting'], $args['code'], $args['message'], $args['type'] );
	}
}

function smarz_register_theme_options() {
	$cmb_options = new_cmb2_box( array(
		'id'           => 'smarz_theme_options_page',
		'title'        => 'Opzioni Tema',
		'object_types' => array( 'options-page' ),
		'option_key'   => 'smarz_theme_options',
		'icon_url'     => 'dashicons-hammer',
		'menu_title'   => 'Opzioni',
		'message_cb'   => 'smarz_cache_options_page_message_callback',
		'save_button'  => 'Aggiorna',
	) );

	$cmb_options->add_field( array(
		'name'              => 'Aggiornare prodotti da eBay?',
		'id'                => 'smarz_cache',
		'type'              => 'multicheck_inline',
		'options'           => array(
			'yes' => 'Si',
		),
		'select_all_button' => false,
	) );

}

add_action( 'cmb2_admin_init', 'smarz_register_theme_options' );


function updated_option_smarz_cache( $option, $old_value, $value ) {

	if ( ( $option === 'smarz_theme_options' ) && isset($value['smarz_cache'][0]) && ($value['smarz_cache'][0]==='yes' ) ) {

		update_option( 'smarz_theme_options', $old_value );

		delete_transient( 'ebay_items' );
		set_transient( 'ebay_items', getAllItems(), 12 * HOUR_IN_SECONDS );
	}

}

add_filter( 'updated_option', 'updated_option_smarz_cache', 10, 3 );
