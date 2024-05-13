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

?>
	<footer class="page-footer navbar-inverse text-warning">
		<div class="container">
			<div class="row">            
				<div class="footer-widget col-xs-12 col-sm-3"><?php dynamic_sidebar( 'footer-column-1' ); ?></div>
				<div class="footer-widget col-xs-12 col-sm-3"><?php dynamic_sidebar( 'footer-column-2' ); ?></div>
				<div class="footer-widget col-xs-12 col-sm-3"><?php dynamic_sidebar( 'footer-column-3' ); ?></div>
				<div class="footer-widget col-xs-12 col-sm-3"><?php dynamic_sidebar( 'footer-column-4' ); ?></div>
			</div>
		</div>
		<?php get_template_part( 'template-parts/part/nav-bottom' ); ?>
		<div class="footer-bottom">
			<div class="container">
				<?php if ( is_front_page() ) { ?>
				<div class="pull-left col-xs-12 col-sm-6">
					<a href="<?php echo esc_url( esc_attr__( 'https://dedidata.com', 'free-template' ) ); ?>" title="<?php esc_attr_e( 'Free Theme by DediData', 'free-template' ); ?>" target="_blank">
						<?php esc_attr_e( 'Theme Design by DediData', 'free-template' ); ?>
					</a>
				</div>
				<?php } ?>
				<div class="pull-right col-xs-12 col-sm-6">
					<p>
						<?php
						// Translators: %1$s is current year and %2$s is site name
						printf( esc_html__( 'Copyright &copy; %1$s %2$s. All right reserved.', 'free-template' ), esc_html( gmdate( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) );
						?>
					</p>
				</div>
			</div>
		</div>
	</footer>
	<?php wp_footer(); ?>
</body>
</html>
