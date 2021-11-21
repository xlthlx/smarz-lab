<?php

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Finding\Services;
use DTS\eBaySDK\Finding\Types;

function get_all_items() {
	global $config;
	$return = [];

	$service = new Services\FindingService( [
		'credentials' => $config['production']['credentials'],
		'globalId'    => Constants\GlobalIds::IT
	] );

	$request               = new Types\FindItemsAdvancedRequest();
	$request->itemFilter[] = new Types\ItemFilter( [
		'name'  => 'Seller',
		'value' => [ 'a.pigeons' ]
	] );

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


	if ( $response->ack !== 'Failure' ) {

		$total            = $response->paginationOutput->totalEntries;
		$return['Totale'] = $total;
		$return['Pagine'] = $response->paginationOutput->totalPages;

		if ( $total !== 0 ) {
			foreach ( $response->searchResult->item as $item ) {

				$object['Titolo'] = $item->title;

				if ( isset( $item->sellingStatus->currentPrice ) ) {
					$object['Prezzo'] = '&euro; ' . $item->sellingStatus->currentPrice->value;
				}

				if ( isset( $item->galleryPlusPictureURL ) ) {
					$object['Immagine'] = $item->galleryPlusPictureURL[0];
				}

				if ( isset( $item->viewItemURL ) ) {
					$object['Link'] = $item->viewItemURL;
				}

				if ( isset( $item->condition ) ) {
					$object['Stato'] = $item->condition->conditionDisplayName;
				}

				if ( isset( $item->location ) ) {
					$object['Luogo'] = $item->location;
				}

				$return['Pagina'][1][ $item->itemId ] = $object;

			}
		}
	}


	$limit = $response->paginationOutput->totalPages;
	for ( $pageNum = 2; $pageNum <= $limit; $pageNum ++ ) {
		$request->paginationInput->pageNumber = $pageNum;

		$response = $service->findItemsAdvanced( $request );

		if ( $response->ack !== 'Failure' ) {

			foreach ( $response->searchResult->item as $item ) {

				$object['Titolo'] = $item->title;

				if ( isset( $item->sellingStatus->currentPrice ) ) {
					$object['Prezzo'] = '&euro; ' . $item->sellingStatus->currentPrice->value;
				}

				if ( isset( $item->galleryPlusPictureURL ) ) {
					$object['Immagine'] = $item->galleryPlusPictureURL[0];
				}

				if ( isset( $item->viewItemURL ) ) {
					$object['Link'] = $item->viewItemURL;
				}

				if ( isset( $item->condition ) ) {
					$object['Stato'] = $item->condition->conditionDisplayName;
				}

				if ( isset( $item->location ) ) {
					$object['Luogo'] = $item->location;
				}

				$return['Pagina'][ $pageNum ][ $item->itemId ] = $object;
			}
		}
	}

	return $return;
}
