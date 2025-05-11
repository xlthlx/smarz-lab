<?php
/**
 * Settings configuration.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

$options = get_option( 'smarz_theme_options' );

return array(
	'production' => array(
		'credentials' => array(
			'seller' => $options['seller'],
			'appId'  => $options['appId'],
			'certId' => $options['certId'],
		),
		'categories'  => $options['categories'],
	),
);
