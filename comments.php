<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @package Free_Template
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

declare(strict_types=1);

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area panel box">

	<?php
	
	// You can start editing here -- including this comment!
	if ( have_comments() ) {
		?>
		<h2 class="comments-title panel-heading">
			<?php
			$comments_number = get_comments_number();
			if ( '1' === $comments_number ) {
				/* translators: %s: post title */
				printf( esc_html__( 'One Reply to &ldquo;%s&rdquo;', 'free-template' ), esc_html( get_the_title() ) );
			} else {
				printf(
					esc_html(
						/* translators: 1: number of comments, 2: post title */
						_n(
							'%1$s Reply to &ldquo;%2$s&rdquo;',
							'%1$s Replies to &ldquo;%2$s&rdquo;',
							$comments_number,
							'free-template'
						)
					),
					esc_html( number_format_i18n( $comments_number ) ),
					esc_html( get_the_title() )
				);
			}
			?>
		</h2>

		<div class="panel-body">
		<?php
		echo wp_kses(
			FREE_TEMPLATE()::comments_pagination(
				array(
					'prev_text' => '<span>' . esc_html__( 'Previous', 'free-template' ) . '</span>',
					'next_text' => '<span>' . esc_html__( 'Next', 'free-template' ) . '</span>',
					'type'      => 'list',
				)
			),
			'post'
		);
		?>

		<ol class="comment-list">
			<?php
				wp_list_comments(
					array(
						'walker'      => new \FreeTemplate\Walker_Bootstrap_Comment(),
						'avatar_size' => 100,
						'style'       => 'ol',
						'short_ping'  => true,
						'reply_text'  => '<i class="fa fa-reply" aria-hidden="true"></i>&nbsp;' . esc_html__( 'Reply', 'free-template' ),
					)
				);
			?>
		</ol>
		
		<?php
		echo wp_kses(
			FREE_TEMPLATE()::comments_pagination(
				array(
					'prev_text' => '<span>' . esc_html__( 'Previous', 'free-template' ) . '</span>',
					'next_text' => '<span>' . esc_html__( 'Next', 'free-template' ) . '</span>',
					'type'      => 'list',
				)
			),
			'post'
		);
		?>
		</div>
		<?php
	}//end if

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'free-template' ); ?></p>
		<?php
	}
	?>
	
	<div class="panel-footer">
		<?php FREE_TEMPLATE()::validate_comment_form(); ?>
	</div>

</div><!-- #comments -->
