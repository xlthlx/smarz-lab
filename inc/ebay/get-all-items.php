<?php
/**
 * Ebay functions.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

/**
 *  Get all items.
 *
 * @return array $return Array of items.
 */
function sl_get_ebay_all_items() {
	global $config;
	$return = array();

	// Get the access token.
	$hash        = base64_encode( $config['production']['credentials']['appId'] . ':' . $config['production']['credentials']['certId'] );
	$oauth_url   = 'https://api.ebay.com/identity/v1/oauth2/token';
	$post_fields = 'grant_type=client_credentials&scope=https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope';

	$options = array(
		'body'        => $post_fields,
		'headers'     => array(
			'Authorization' => 'Basic ' . $hash,
		),
		'method'      => 'POST',
		'httpversion' => '1.0',
		'sslverify'   => false,
	);

	$response = wp_remote_post( $oauth_url, $options );

	if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		echo 'Something went wrong: ' . esc_attr( $error_message );
	} else {

		$response = wp_remote_retrieve_body( $response );
		$response = json_decode( $response, false );

		$access_token = $response->access_token;

		$prod_cats  = array( '58058' );
		$categories = $config['production']['categories'];

		if ( ! empty( $categories ) ) {
			$prod_cats = explode( ',', $categories );
		}

		$total = 0;

		foreach ( $prod_cats as $category ) {
			// Request to the Browse API.
			$seller   = $config['production']['credentials']['seller'];
			$url      = 'https://api.ebay.com/buy/browse/v1/item_summary/search?category_ids=' . $category . '&filter=sellers:{' . $seller . '}&fieldgroups=EXTENDED&limit=9&offset=0';
			$response = wp_remote_get(
				$url,
				array(
					'headers' => array(
						'Authorization'           => 'Bearer ' . $access_token,
						'X-EBAY-C-MARKETPLACE-ID' => 'EBAY_IT',
					),
				)
			);

			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$response = json_decode( $response['body'], false );
				$total    = $total + (int) $response->total;
				$pages    = round( (int) $total / 9 );

				$return['total'] = $total;
				$return['pages'] = $pages;

				if ( 0 !== $total ) {
					foreach ( $response->itemSummaries as $item ) {
						$object                           = sl_get_ebay_item( $item );
						$return['items'][ $item->itemId ] = $object;
					}

					for ( $page_number = 2; $page_number <= $pages; $page_number ++ ) {
						$offset = ( $page_number - 1 ) * 9;

						$url      = 'https://api.ebay.com/buy/browse/v1/item_summary/search?category_ids=' . $category . '&filter=sellers:{' . $seller . '}&fieldgroups=EXTENDED&limit=9&offset=' . $offset;
						$response = wp_remote_get(
							$url,
							array(
								'headers' => array(
									'Authorization' => 'Bearer ' . $access_token,
									'X-EBAY-C-MARKETPLACE-ID' => 'EBAY_IT',
								),
							)
						);

						$response = json_decode( $response['body'], false );

						foreach ( $response->itemSummaries as $item ) {
							$object                           = sl_get_ebay_item( $item );
							$return['items'][ $item->itemId ] = $object;
						}
					}
				}
			} else {
				$error_message = $response->get_error_message();
				echo 'Something went wrong: ' . esc_attr( $error_message );
			}
		}
	}

	return $return;
}

/**
 * Get single item.
 *
 * @param object $item Single item.
 *
 * @return array
 */
function sl_get_ebay_item( $item ) {

	$object           = array();
	$object['itemId'] = $item->itemId;
	$object['title']  = $item->title;

	if ( isset( $item->price ) ) {
		$price           = str_replace( array( '.', ',00' ), array( ',', '' ), $item->price->value );
		$object['price'] = '&euro; ' . $price;
	}

	if ( isset( $item->thumbnailImages ) ) {
		$object['image'] = $item->thumbnailImages[0]->imageUrl;
	}

	if ( isset( $item->itemWebUrl ) ) {
		$object['link'] = $item->itemWebUrl;
	}

	if ( isset( $item->condition ) ) {
		$object['condition'] = $item->condition;
	}

	if ( isset( $item->shortDescription ) ) {
		$object['description'] = $item->shortDescription;
	}

	return $object;
}
