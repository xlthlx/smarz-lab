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

// @codingStandardsIgnoreStart
use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Finding\Services;
use DTS\eBaySDK\Finding\Types;
// @codingStandardsIgnoreEnd

/**
 * Get all items.
 *
 * @return array
 */
function sl_get_ebay_all_items() {
	global $config;
	$return = array();
	$options = get_option( 'smarz_theme_options' );

	// @codingStandardsIgnoreStart
	$service = new Services\FindingService(
		array(
			'credentials' => $config['production']['credentials'],
			'globalId'    => Constants\GlobalIds::IT,
		)
	);

	$request               = new Types\FindItemsAdvancedRequest();
	$request->itemFilter[] = new Types\ItemFilter(
		array(
			'name'  => 'Seller',
			'value' => array( $options['seller'] ),
		)
	);

	$request->sortOrder = 'CurrentPriceHighest';

	$request->paginationInput                 = new Types\PaginationInput();
	$request->paginationInput->entriesPerPage = 9;
	$request->paginationInput->pageNumber     = 1;

	$response = $service->findItemsAdvanced( $request );

	if ( isset( $response->errorMessage ) ) {
		foreach ( $response->errorMessage->error as $error ) {
			$return['error'][ $error->message ] = $error->message;
		}
	}

	if ( $response->ack != 'Failure' ) {

		$total            = $response->paginationOutput->totalEntries;
		$return['total'] = $total;
		$return['pages'] = $response->paginationOutput->totalPages;

		if ( $total !== 0 ) {
			foreach ( $response->searchResult->item as $item ) {

				$object                               = sl_get_ebay_item( $item );
				$return['page'][1][ $item->itemId ] = $object;

			}
		}
	}

	$limit = $response->paginationOutput->totalPages;
	for ( $pageNum = 2; $pageNum <= $limit; $pageNum ++ ) {
		$request->paginationInput->pageNumber = $pageNum;

		$response = $service->findItemsAdvanced( $request );

		if ( $response->ack != 'Failure' ) {

			foreach ( $response->searchResult->item as $item ) {

				$object                                        = sl_get_ebay_item( $item );
				$return['page'][ $pageNum ][ $item->itemId ] = $object;
			}
		}
	}

	// @codingStandardsIgnoreEnd

	return $return;
}

/**
 * Get single item.
 *
 * @param Types\SearchItem $item Single item.
 *
 * @return array
 */
function sl_get_ebay_item( $item ) {

	// @codingStandardsIgnoreStart

	$object['title'] = $item->title;

	if ( isset( $item->sellingStatus->currentPrice ) ) {
		$object['price'] = '&euro; ' . $item->sellingStatus->currentPrice->value;
	}

	if ( isset( $item->galleryURL ) ) {
		$object['image'] = str_replace( 's-l140', 's-l500', $item->galleryURL );
	}

	if ( isset( $item->viewItemURL ) ) {
		$object['link'] = $item->viewItemURL;
	}

	if ( isset( $item->condition ) ) {
		$object['condition'] = $item->condition->conditionDisplayName;
	}

	if ( isset( $item->location ) ) {
		$object['location'] = $item->location;
	}

	$object['debug'] = '<pre>' . print_r( $item, true ) . '</pre><br/><br/>';

	// @codingStandardsIgnoreEnd

	return $object;
}
