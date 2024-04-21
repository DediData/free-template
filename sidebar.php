<?php
/**
 * The sidebar for our theme
 *
 * This is the template that displays sidebar section
 * 
 * @package Free_Template
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

declare(strict_types=1);

?>
<aside id="secondary">
	<div class="col-sm-3 col-xs-12 pull-left side-column">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div>
</aside>
