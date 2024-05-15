<?php
/**
 * Function file of theme
 * 
 * @package Free_Template
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( '\DediData\Theme_Autoloader' ) ) {
	require 'includes/DediData/class-theme-autoloader.php';
}
// Set name spaces we use in this plugin
new \DediData\Theme_Autoloader( array( 'DediData', 'FreeTemplate' ) );
/**
 * The function FREE_TEMPLATE returns an instance of the Free_Template class.
 *
 * @return object an instance of the \FreeTemplate\Free_Template class.
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function FREE_TEMPLATE() { // phpcs:ignore Squiz.Functions.GlobalFunction.Found, WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return \FreeTemplate\Free_Template::get_instance( __FILE__ );
}
FREE_TEMPLATE();