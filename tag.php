<?php
/**
 * The main template file
 */
?>
 
<?php get_header(); ?>
<main id="main" class="site-main">
	<div class="container"><?php
		if ( is_active_sidebar( 'sidebar-1' ) ) { ?>
		<div class="row"><?php
		} ?>
			<div id="primary" class="site-content content-area col-xs-12<?php if( is_active_sidebar( 'sidebar-1' ) ) { echo ' pull-right col-sm-9'; } ?>"><?php
			
			dynamic_sidebar( 'content-top' );
			
			if(is_tag() && tag_description()){ ?>
				<div class="box panel text-justify"><?php echo tag_description(); ?></div><?php
			}

			if ( have_posts() ) {

				/* Start the Loop */
				while ( have_posts() ) {
					the_post();
					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/post/content', get_post_format() );
				}

				Free_Template::posts_pagination( array(
					'prev_text' => '<span>' . esc_html__( 'Previous', 'free-template' ) . '</span>',
					'next_text' => '<span>' . esc_html__( 'Next', 'free-template' ) . '</span>',
					'type'						=> 'list',
					'end_size'					=> 3,
					'mid_size'					=> 3,
				) );

			}
			
			dynamic_sidebar( 'content-bottom' );
			
			?>
			</div><?php

			if ( is_active_sidebar( 'sidebar-1' ) ) {
				get_sidebar();
			}

		if ( is_active_sidebar( 'sidebar-1' ) ) { ?>
		</div>
		<?php } ?>
	</div>
</main><!-- .site-main -->
<?php
get_footer();
