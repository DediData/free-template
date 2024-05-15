<?php
/**
 * Part for displaying Entry Footer
 *
 * @package Free_Template
 */

declare(strict_types=1);

?>
<footer class="entry-footer">
<?php
// Get Tags for posts.
$tags_list = get_the_tag_list( '', ' ' );
if ( $tags_list && ! is_archive() && ! is_front_page() ) {
	?>
	<div class="tags-links">
		<span class="fa fa-tags fa-lg" title="<?php esc_attr_e( 'Tags', 'free-template' ); ?>"></span>&nbsp;
		<?php echo wp_kses_post( $tags_list ); ?>
	</div>
	<?php
}
if ( is_single() || is_archive() ) {
	// Get Categories for posts.
	$categories_list = get_the_category_list( esc_html__( ', ', 'free-template' ) );
	if ( $categories_list ) {
		?>
		<div class="footer-item cat-links">
			<i class="fa fa-list-ul fa-lg" title="<?php esc_attr_e( 'Categories', 'free-template' ); ?>" aria-hidden="true"></i>
			<?php echo wp_kses_post( $categories_list ); ?>
		</div>
		<?php
	}
	?>
	<div class="footer-item author-name">
		<i class="fa fa-user fa-lg" title="<?php esc_attr_e( 'Author', 'free-template' ); ?>" aria-hidden="true"></i>
		<span class="author vcard" title="<?php esc_attr_e( 'Author', 'free-template' ); ?>">
			<a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author(); ?></a>
		</span>
	</div>
	<div class="footer-item">
			<?php FREE_TEMPLATE()::posted_on(); ?>
			<?php FREE_TEMPLATE()::modified_on(); ?>
	</div>
	<?php
}//end if
if ( function_exists( 'wp_statistics_pages' ) && get_theme_mod( 'display_visits', true ) && ! is_front_page() ) {
	?>
	<div class="footer-item total-hits">
		<i class="fa fa-bar-chart fa-lg" title="<?php esc_attr_e( 'Total Hits', 'free-template' ); ?>" aria-hidden="true"></i>
		<span class="stat-hits" title="<?php esc_attr_e( 'Total Hits', 'free-template' ); ?>">
		<?php echo esc_html( wp_statistics_pages( 'total', '', get_the_ID() ) ); ?>
		</span>
	</div>
	<?php
}
if ( comments_open( get_the_ID() ) ) {
	?>
<div class="footer-item comments-number">
	<i class="fa fa-comments fa-lg" title="<?php esc_attr_e( 'Comments Number', 'free-template' ); ?>" aria-hidden="true"></i> 
	<span class="comments-count" title="<?php esc_attr_e( 'Comments Number', 'free-template' ); ?>">
	<?php echo esc_html( get_comments_number() ); ?>
	</span>
	</div>
	<?php
}
?>
</footer>
