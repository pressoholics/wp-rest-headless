<?php
/**
 * Plugin settings page template
 *
 * Place a copy of this in your theme under 'jam3-cookie-banner' subdirectory
 * to override this template
 */
?>
<p>Register post types with the GDPR process using the
	'wp_rest_headless_post_types' filter:</p>

<pre>
add_filter( 'wp_rest_headless_post_types', 'wp_rest_headless_register_post_types' );
function wp_rest_headless_register_post_types( $post_type_slugs ) {

	$post_type_slugs = array(
		'post',
		'page',
	);

	return $post_type_slugs;
}
</pre>
