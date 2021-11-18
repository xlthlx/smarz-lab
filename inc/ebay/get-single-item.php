<?php

use DTS\eBaySDK\Constants;
use DTS\eBaySDK\Shopping\Services;
use DTS\eBaySDK\Shopping\Types;

function get_single_item( $sku ) {

	global $config;
	$return = [];

	$service = new Services\ShoppingService( [
		'credentials' => $config['production']['credentials'],
		'authToken'   => get_oauth(),
		'globalId'    => Constants\GlobalIds::IT
	] );
	$request = new Types\GetSingleItemRequestType();

	$request->ItemID          = $sku;
	$request->IncludeSelector = 'ItemSpecifics,Variations,Details';

	$response = $service->getSingleItem( $request );

	if ( isset( $response->Errors ) ) {
		foreach ( $response->Errors as $error ) {
			$return['error'][ $error->ShortMessage ] = $error->LongMessage;
		}
	}

	if ( $response->Ack !== 'Failure' ) {

		$item             = $response->Item;
		$return['Titolo'] = $item->Title;

		if ( isset( $item->ListingStatus ) && ( $item->ListingStatus === 'Active' ) ) {

			if ( isset( $item->ItemSpecifics ) ) {

				foreach ( $item->ItemSpecifics->NameValueList as $nameValues ) {
					$return[ $nameValues->Name ] = implode( ', ', iterator_to_array( $nameValues->Value ) );

					if ( $return[ $nameValues->Name ] === 'ReturnsNotAccepted' ) {
						$return[ $nameValues->Name ] = 'Restituzioni non accettate';
					}
				}
			}

			if ( isset( $item->MinimumToBid ) ) {
				$return['Prezzo'] = '&euro; ' . $item->MinimumToBid->value;
			}

			if ( isset( $item->PictureURL ) ) {
				$return['Immagine'] = $item->PictureURL[0];
			}

			if ( isset( $item->ViewItemURLForNaturalSearch ) ) {
				$return['Link'] = $item->ViewItemURLForNaturalSearch;
			}

			if ( isset( $item->ConditionDisplayName ) ) {
				$return['Stato'] = $item->ConditionDisplayName;
			}

			if ( isset( $item->ConditionDescription ) ) {
				$return['Descrizione'] = $item->ConditionDescription;
			}

			if ( isset( $item->Location, $item->Site ) ) {
				$return['Luogo'] = $item->Location . ', ' . $item->Site;
			}

		}
	}

	return $return;

}
