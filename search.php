<?php
/**
 * Search results page
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Smarz Lab
 */

$templates = array( 'search.twig', 'archive.twig', 'index.twig' );

$context          = Timber::context();
$context['title'] = sprintf( __( 'Risultati della ricerca per: %s' ), get_search_query() );
$context['posts'] = new Timber\PostQuery();

Timber::render( $templates, $context );
