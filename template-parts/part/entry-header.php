<?php
/**
 * Part for displaying Entry Header
 *
 * @package Free_Template
 */

declare(strict_types=1);

?>
<header class="entry-header">
	<div class="row">
		<?php
		if ( is_archive() || is_front_page() || is_home() || is_search() ) {
			?>
		<div class="col <?php echo is_category() ? 'pull-left' : 'col-xs-12'; ?>">
			<?php
			$icons = FREE_TEMPLATE()::get_post_icon();
			if ( is_single() || is_page() ) {
				the_title( '<h1 class="entry-title">' . $icons, '</h1>' );
			} else {
				the_title( '<h2 class="entry-title">' . $icons . '<a href="' . esc_url( get_permalink() ) . '" title="' . esc_attr( get_the_title() ) . '" rel="bookmark">', '</a></h2>' );
			}
			?>
		</div>
			<?php
		}
		?>
	</div>
</header>
