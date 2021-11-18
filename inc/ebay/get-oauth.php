<?php

use DTS\eBaySDK\OAuth\Services;

function get_oauth() {
	global $config;
	$return = [];

	if ( false === ( get_transient( 'ebay_token' ) ) ) {

		$service = new Services\OAuthService( [
			'credentials' => $config['production']['credentials'],
			'ruName'      => $config['production']['ruName'],
			'scope'       => 'scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly https://api.ebay.com/oauth/api_scope/sell.finances https://api.ebay.com/oauth/api_scope/sell.payment.dispute https://api.ebay.com/oauth/api_scope/commerce.identity.readonly https://api.ebay.com/oauth/api_scope/commerce.notification.subscription https://api.ebay.com/oauth/api_scope/commerce.notification.subscription.readonly'
		] );

		$response = $service->getAppToken();

		if ( $response->getStatusCode() !== 200 ) {
			$return['error'][ $response->error ] = $response->error_description;
		} else {
			$ebay_token = $response->access_token;
			set_transient( 'ebay_token', $ebay_token, 12 * HOUR_IN_SECONDS );
			$return = $ebay_token;
		}
	}

	return $return;
}
