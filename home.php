<?php
/**
 * Homepage template.
 *
 * @package  WordPress
 * @subpackage  Smarz Lab
 */

$context          = Timber::context();
$context['posts'] = new Timber\PostQuery();
$templates        = array( 'home.twig' );

Timber::render( $templates, $context );
