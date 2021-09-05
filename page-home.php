<?php
/**
 * Template Name: Homepage
 *
 * @package  WordPress
 * @subpackage  Smarz Lab
 */

$context = Timber::context();

$timber_post     = new Timber\Post();
$context['post'] = $timber_post;

Timber::render( array( 'page-home.twig' ), $context );
