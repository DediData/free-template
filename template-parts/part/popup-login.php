<?php
/**
 * Part for displaying Popup Login
 *
 * @package Free_Template
 */

declare(strict_types=1);

if ( get_theme_mod( 'display_login_link' ) ) {
	$login_link_texts = FREE_TEMPLATE()::login_link_texts();
	?>
		<div class="mainbox modal fade" id="myModal" role="dialog">
			<div class="modal-dialog" role="document" style="width: 400px;">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'free-template' ); ?>"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel"><?php echo esc_html( $login_link_texts[ get_theme_mod( 'login_link_text' ) ] ); ?></h4>
					</div>
					<div class="modal-body">
						<form id="loginform" data-toggle="validator" method="post" action="<?php echo esc_url( get_site_url() . '/wp-login.php' ); ?>">
							<div class="form-group has-feedback">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-at fa-lg"></i></span>
									<input id="login-username" style="direction: ltr;" type="email" class="form-control" name="log"
										placeholder="<?php esc_attr_e( 'Email Address', 'free-template' ); ?>" required="required" data-error="<?php esc_attr_e( 'Please enter your valid email address!', 'free-template' ); ?>" />
								</div>
								<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
								<div class="help-block with-errors"></div>
							</div>
							<div class="form-group has-feedback">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-key fa-lg"></i></span>
									<input id="login-password" style="direction: ltr;" type="password" class="form-control" name="pwd"
										placeholder="<?php esc_attr_e( 'Password', 'free-template' ); ?>"  required="required" data-error="<?php esc_attr_e( 'Please enter your password!', 'free-template' ); ?>" />
								</div>
								<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
								<div class="help-block with-errors"></div>
							</div>
							<div class="text-center form-group">
									<input type="submit" class="btn btn-primary" value="<?php esc_attr_e( 'Login', 'free-template' ); ?>" />&nbsp;
									<?php if ( get_option( 'users_can_register' ) ) { ?>
									<a class="btn btn-success" href="<?php echo esc_url( get_site_url() . '/wp-login.php?action=register' ); ?>" rel="nofollow"><?php esc_html_e( 'Register!', 'free-template' ); ?></a>&nbsp;
									<?php } ?>
									<a class="btn btn-warning"  rel="nofollow" href="<?php echo esc_url( get_site_url() . '/wp-login.php?action=lostpassword' ); ?>"><?php esc_html_e( 'Forgot Password?', 'free-template' ); ?></a>&nbsp;
							</div>
						</form>     	  
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e( 'Close', 'free-template' ); ?></button>
					</div>
				</div>
			</div>
		</div>
	<?php
}//end if
