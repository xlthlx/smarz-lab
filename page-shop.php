<?php
/**
 * Template Name: Shop
 *
 * @package  WordPress
 * @subpackage  Smarz Lab
 */

$context = Timber::context();

$timber_post     = new Timber\Post();
$context['post'] = $timber_post;

if ( false === ( $ebay_items = get_transient( 'ebay_items' ) ) ) {
	$ebay_items = get_all_items();
	set_transient( 'ebay_items', $ebay_items, 12 * HOUR_IN_SECONDS );
}
$context['items'] = $ebay_items;

Timber::render( array( 'page-shop.twig' ), $context, 0 );
