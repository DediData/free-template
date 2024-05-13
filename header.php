<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package Free_Template
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

declare(strict_types=1);

?><!DOCTYPE html>
<html <?php language_attributes(); ?> itemscope itemtype="http://schema.org/WebPage">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to main content', 'free-template' ); ?></a>
<a id="back-to-top" href="#" class="btn btn-default back-to-top" role="button" title="<?php esc_attr_e( 'Go to top', 'free-template' ); ?>">
	<span class="glyphicon glyphicon-chevron-up"></span>
</a>
<?php get_template_part( 'template-parts/part/popup-login' ); ?>
<?php get_template_part( 'template-parts/part/nav-header-top' ); ?>
<header id="masthead" class="site-header">
	<?php get_template_part( 'template-parts/part/header-carousel' ); ?>
	<?php get_template_part( 'template-parts/part/nav-header' ); ?>
</header><!-- #masthead -->
