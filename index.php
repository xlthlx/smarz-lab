<?php
/**
 * The main template file.
 *
 * @package  WordPress
 * @subpackage  Smarz Lab
 */

$context          = Timber::context();
$context['posts'] = new Timber\PostQuery();
$templates        = array( 'index.twig' );

Timber::render( $templates, $context );
