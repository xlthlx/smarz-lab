<?php

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Finding\Services;
use DTS\eBaySDK\Finding\Types;

function get_all_items() {
	global $config;
	$return = [];

	$service = new Services\FindingService( [
		'credentials' => $config['production']['credentials'],
		'authToken'   => get_oauth(),
		'globalId'    => Constants\GlobalIds::IT
	] );

	$request               = new Types\FindItemsAdvancedRequest();
	$request->itemFilter[] = new Types\ItemFilter( [
		'name'  => 'Seller',
		'value' => [ 'a.pigeons' ]
	] );

	$request->sortOrder  = 'CurrentPriceHighest';
	$request->categoryId = [ '58058' ];

	$request->paginationInput                 = new Types\PaginationInput();
	$request->paginationInput->entriesPerPage = 9;
	$request->paginationInput->pageNumber     = 1;

	$response = $service->findItemsAdvanced( $request );

	if ( isset( $response->errorMessage ) ) {
		foreach ( $response->errorMessage->error as $error ) {
			$return['error'][ $error->message ] = $error->message;
		}
	}


	if ( $response->ack !== 'Failure' ) {

		$total            = $response->paginationOutput->totalEntries;
		$return['Totale'] = $total;
		$return['Pagine'] = $response->paginationOutput->totalPages;

		if ( $total !== 0 ) {
			foreach ( $response->searchResult->item as $item ) {

				$return['Pagina'][1][ $item->itemId ] = get_single_item( $item->itemId );

			}
		}
	}


	$limit = $response->paginationOutput->totalPages;
	for ( $pageNum = 2; $pageNum <= $limit; $pageNum ++ ) {
		$request->paginationInput->pageNumber = $pageNum;

		$response = $service->findItemsAdvanced( $request );

		if ( $response->ack !== 'Failure' ) {

			foreach ( $response->searchResult->item as $item ) {
				$return['Pagina'][ $pageNum ][ $item->itemId ] = get_single_item( $item->itemId );
			}
		}
	}

	return $return;
}
