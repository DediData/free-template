<?php

class Free_Template_BS_Walker_Comment extends Walker_Comment {
 
    /**
     * Outputs a comment in the HTML5 format.
     *
     * @since 3.6.0
     *
     * @see wp_list_comments()
     *
     * @param WP_Comment $comment Comment to display.
     * @param int        $depth   Depth of the current comment.
     * @param array      $args    An array of arguments.
     */
    protected function html5_comment( $comment, $depth, $args ) {
        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
		?>
        <<?php echo $tag; // xss ok ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
            <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">

                <div class="comment-author vcard panel box">
                    <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'], NULL, 'avatar' ); ?>
                </div><!-- .comment-author -->

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
										global $wpp_settings;
										if( in_array( 'wp-parsidate/wp-parsidate.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && isset($wpp_settings) && $wpp_settings['persian_date'] == 'enable' ) {
											$time_string = gregdate('c', eng_number(get_comment_time( 'c' )));
										}else{
											$time_string = get_comment_time( 'c' );
										}
									?>
									<time datetime="<?php echo $time_string; // xss ok ?>">
										<?php
											/* translators: 1: comment date, 2: comment time */
											printf( esc_html__( '%1$s at %2$s', 'free-template' ), get_comment_date( '', $comment ), get_comment_time() );
										?>
									</time>
								</a>
							</div>
						</div><!-- .comment-metadata -->
	 
						<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'free-template' ); ?></p>
						<?php endif; ?>
	                </footer><!-- .comment-meta -->
					<div class="panel-body">
						<?php comment_text(); ?>
					</div>
					<div class="panel-footer">
						<?php
						comment_reply_link( array_merge( $args, array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply btn btn-default btn-xs">',
							'after'     => '</div>'
						) ) );
						?>
						<div class="pull-right">
							<?php edit_comment_link( esc_html__( 'Edit', 'free-template' ), '<div class="btn btn-default comment-edit-btn btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;<span class="edit-link">', '</span></div>' ); ?>
						</div>
					</div>
                </div><!-- .comment-content -->
 
            </div><!-- .comment-body -->
		<?php
    }
}