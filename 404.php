<?php
/**
 * The 404 template file
 * 
 * @package Free_Template
 */

declare(strict_types=1);

get_header();
$sidebar_condition = is_active_sidebar( 'sidebar-1' ); ?>
<main id="main" class="site-main">
	<div class="container">
		<?php
		if ( $sidebar_condition ) {
			$sidebar_class = ' pull-right col-sm-9';
			?>
		<div class="row">
			<?php
		}
		?>
			<div id="primary" class="site-content content-area col-xs-12<?php echo esc_attr( $sidebar_class ); ?>">
			
				<section class="error-404 not-found panel box" style="padding: 10px;">
					<div class="page-content">
						<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'free-template' ); ?></p>

						<?php get_search_form(); ?>

					</div><!-- .page-content -->
				</section><!-- .error-404 -->
				<?php
					dynamic_sidebar( 'frontend-content-top' );
					dynamic_sidebar( 'content-top' );
					dynamic_sidebar( 'frontend-content-bottom' );
					dynamic_sidebar( 'content-bottom' );
				?>
			</div>
			<?php
			if ( $sidebar_condition ) {
				get_sidebar();
			}
			if ( $sidebar_condition ) {
				?>
		</div>
				<?php
			}
			?>
	</div>
</main><!-- .site-main -->
<?php
get_footer();
