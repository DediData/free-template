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
	require __DIR__ . '/includes/DediData/class-theme-autoloader.php';
}
// Set name spaces we use in this plugin
// @phan-suppress-next-line PhanNoopNew
new \DediData\Theme_Autoloader( array( 'DediData', 'FreeTemplate' ) );
/**
 * The function FREE_TEMPLATE returns an instance of the Free_Template class.
 * 
 * @return \FreeTemplate\Free_Template as an instance of return
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
function FREE_TEMPLATE() { // phpcs:ignore Squiz.Functions.GlobalFunction.Found, WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return \FreeTemplate\Free_Template::get_instance( __FILE__ );
}
FREE_TEMPLATE();

