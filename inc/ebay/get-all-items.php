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
 * Get all items.
 *
 * @throws JsonException Exception.
 */
function sl_get_ebay_all_items(): array {
	global $config;
	$return  = array();

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
	$response = wp_remote_retrieve_body( $response );
	$response = json_decode( $response, false, 512, JSON_THROW_ON_ERROR );

	$access_token = $response->access_token;

	// Request to the Browse API.
	$url      = 'https://api.ebay.com/buy/browse/v1/item_summary/search?category_ids=58058&filter=sellers:{a.pigeons}&fieldgroups=EXTENDED&limit=9&offset=0';
	$response = wp_remote_get(
		$url,
		array(
			'headers' => array(
				'Authorization'           => 'Bearer ' . $access_token,
				'X-EBAY-C-MARKETPLACE-ID' => 'EBAY_IT',
			),
		)
	);

	$response = json_decode( $response['body'], false, 512, JSON_THROW_ON_ERROR );
	$total    = $response->total;
	$pages    = round( (int) $total / 9 );

	$return['total'] = $total;
	$return['pages'] = $pages;

	if ( $total !== 0 ) {
		foreach ( $response->itemSummaries as $item ) {
			$object                             = sl_get_ebay_item( $item );
			$return['page'][1][ $item->itemId ] = $object;
		}

		for ( $pageNum = 2; $pageNum <= $pages; $pageNum ++ ) {
			$offset = ( $pageNum - 1 ) * 9;

			$url      = 'https://api.ebay.com/buy/browse/v1/item_summary/search?category_ids=58058&filter=sellers:{a.pigeons}&fieldgroups=EXTENDED&limit=9&offset=' . $offset;
			$response = wp_remote_get(
				$url,
				array(
					'headers' => array(
						'Authorization'           => 'Bearer ' . $access_token,
						'X-EBAY-C-MARKETPLACE-ID' => 'EBAY_IT',
					),
				)
			);

			$response = json_decode( $response['body'], false, 512, JSON_THROW_ON_ERROR );

			foreach ( $response->itemSummaries as $item ) {
				$object                                      = sl_get_ebay_item( $item );
				$return['page'][ $pageNum ][ $item->itemId ] = $object;
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
function sl_get_ebay_item( $item ): array {

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
