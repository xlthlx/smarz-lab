<?php
use DTS\eBaySDK\OAuth\Services;

function get_oauth() {
	global $config;

	$service = new Services\OAuthService( [
		'credentials' => $config['production']['credentials'],
		'ruName'      => $config['production']['ruName'],
		'scope' => 'scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly https://api.ebay.com/oauth/api_scope/sell.finances https://api.ebay.com/oauth/api_scope/sell.payment.dispute https://api.ebay.com/oauth/api_scope/commerce.identity.readonly https://api.ebay.com/oauth/api_scope/commerce.notification.subscription https://api.ebay.com/oauth/api_scope/commerce.notification.subscription.readonly'
	] );

	$response = $service->getAppToken();


	printf( "\nStatus Code: %s\n\n", $response->getStatusCode() );
	if ( $response->getStatusCode() !== 200 ) {
		$return = printf(
			"%s: %s\n\n",
			$response->error,
			$response->error_description
		);
	} else {
		$return = $response->access_token;
	}

	return $return;
}
