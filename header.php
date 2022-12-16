<?php
/**
 * Header.
 *
 * @package    WordPress
 * @subpackage Smarz Lab
 */

global $charset;
?><!doctype html>
<html lang="it" id="top">
<head>
    <meta charset="<?php echo $charset; ?>">
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<header>
    <?php get_template_part('parts/navbar'); ?>
</header>
<main>
    <?php if (! ( is_front_page() ) ) { ?>
        <div class="container">
            <nav class="pt-4 pb-2" style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
        <?php sl_breadcrumbs(); ?>
            </nav>
        </div>
    <?php } else { ?>
        <div class="container pt-4 pb-2"></div>
    <?php } ?>

    <div class="container" id="content">
