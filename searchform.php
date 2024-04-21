<?php
/**
 * Template for displaying search forms
 * 
 * @package Free_Template
 */

declare(strict_types=1);

$unique_id = uniqid( 'search-form-' ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $unique_id ); ?>">
	</label>
	<input type="search" id="<?php echo esc_attr( $unique_id ); ?>" class="search-field" placeholder="<?php esc_attr_e( 'Search &hellip;', 'free-template' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" class="search-submit"><i class="fa fa-search" aria-hidden="true"></i></button>
</form>
