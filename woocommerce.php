<?php
/**
 * The woocommerce template file
 *
 * @package Free_Template
 */

declare(strict_types=1);

get_header();
?>
<main id="main" class="site-main">
	<div class="container">
		<?php
		if ( is_active_sidebar( 'sidebar-1' ) ) {
			?>
		<div class="row">
			<?php
		}
		if ( is_active_sidebar( 'sidebar-1' ) ) {
			$sidebar_class = ' pull-right col-sm-9';
		}
		?>
			<div id="primary" class="site-content content-area col-xs-12<?php echo esc_attr( $sidebar_class ); ?>">
				<div class="wc-content panel box">
					<?php woocommerce_content(); ?>
				</div>
			</div>
			<?php

			if ( is_active_sidebar( 'sidebar-1' ) ) {
				get_sidebar();
			}

			if ( is_active_sidebar( 'sidebar-1' ) ) {
				?>
		</div>
				<?php
			}
			?>
	</div>
</main><!-- .site-main -->
<?php
get_footer();
