<?php
/**
 * FreeTemplate Main Class
 * 
 * @package FreeTemplate
 */

declare(strict_types=1);

namespace FreeTemplate;

/**
 * Class Walker_Bootstrap_Comment
 */
final class Walker_Bootstrap_Comment extends \Walker_Comment {

	/**
	 * Outputs a comment in the HTML5 format.
	 *
	 * @since 3.6.0
	 * @see wp_list_comments()
	 * @param \WP_Comment  $comment Comment to display.
	 * @param integer      $depth   Depth of the current comment.
	 * @param array<mixed> $args    An array of arguments.
	 * @return void
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	protected function html5_comment( $comment, int $depth, $args ) {
		$tag = 'div' === $args['style'] ? 'div' : 'li';
		?>
		<<?php echo esc_html( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
			<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">

				<div class="comment-author vcard panel box">
					<?php
					if ( 0 !== $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'], null, 'avatar' );
					}
					?>
				</div>

				<div class="comment-content panel box">
					<footer class="comment-meta panel-heading has-arrow-right">
						<div class="comment-metadata">
							<?php
								printf( '<i class="fa fa-user" aria-hidden="true"></i> <b class="fn">%s</b>', get_comment_author_link( $comment ) );
							?>
							<div class="pull-right">
								<i class="fa fa-clock-o" aria-hidden="true"></i>
								<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
									<?php
									// phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
									$wpp_settings   = $GLOBALS['wpp_settings'];
									$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
									$time_string    = get_comment_time( 'c' );
									if (
										in_array( 'wp-parsidate/wp-parsidate.php', $active_plugins, true ) &&
										isset( $wpp_settings ) &&
										'enable' === $wpp_settings['persian_date']
									) {
										$time_string = gregdate( 'c', eng_number( get_comment_time( 'c' ) ) );
									}
									?>
									<time datetime="<?php echo esc_attr( $time_string ); ?>">
										<?php
											/* translators: 1: comment date, 2: comment time */
											echo esc_html( sprintf( __( '%1$s at %2$s', 'free-template' ), get_comment_date( '', $comment ), get_comment_time() ) );
										?>
									</time>
								</a>
							</div>
						</div>
	 
						<?php if ( '0' === $comment->comment_approved ) { ?>
						<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'free-template' ); ?></p>
						<?php } ?>
					</footer>
					<div class="panel-body">
						<?php comment_text(); ?>
					</div>
					<div class="panel-footer">
						<?php
						comment_reply_link(
							array_merge(
								$args,
								array(
									'add_below' => 'div-comment',
									'depth'     => $depth,
									'max_depth' => $args['max_depth'],
									'before'    => '<div class="reply btn btn-default btn-xs">',
									'after'     => '</div>',
								)
							)
						);
						?>
						<div class="pull-right">
							<?php edit_comment_link( esc_html__( 'Edit', 'free-template' ), '<div class="btn btn-default comment-edit-btn btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;<span class="edit-link">', '</span></div>' ); ?>
						</div>
					</div>
				</div>
 
			</div>
		<?php
	}
}
