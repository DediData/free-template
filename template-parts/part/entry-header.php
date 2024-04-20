<header class="entry-header">
	<div class="row"><?php
		if(is_archive() or is_front_page() or is_home() or is_search()){ ?>
		<div class="col <?php echo (is_category() ? 'pull-left' : 'col-xs-12'); ?>">
		<?php
		$icons = Free_Template::get_post_icon();
		if( is_single() or is_page() ){
			the_title( '<h1 class="entry-title">' . $icons, '</h1>' );
		}else{
			the_title( '<h2 class="entry-title">' . $icons . '<a href="' . esc_url( get_permalink() ) . '" title="' . esc_attr(get_the_title()) . '" rel="bookmark">', '</a></h2>' );
		} ?>
		</div><?php
		}
		?>
	</div>
</header><!-- .entry-header -->
