<?php
/**
 * Plugin settings page template
 *
 * Place a copy of this in your theme under 'jam3-cookie-banner' subdirectory
 * to override this template
 */

$status = '<strong style="color:red;">Disabled</strong>';
if( true === WP_Headless_Jwt_Rest_Api::$enable_jwt ) {
	$status = '<strong style="color:green;">Enabled</strong>';
}
?>

<div style="background:#0073aa; padding:20px;" >
	<a href="https://documenter.getpostman.com/view/1175138/RztkPpV4" target="_blank" style="color: #ffffff;">
		See example requests in plugin docs!!
	</a>
</div>

<h2>Status: <?php echo wp_kses_post( $status ); ?></h2>

<h4>Enable JSON Web Token Authentication with</h4>
<pre>
add_filter( 'wp_headless_rest__enable_jwt', '__return_true' );
</pre>

<p>Ensure your web server supports the HTTP Authorization Header. If you are using a shared host, this is often disabled by default. To enable it, add the following to your WordPress’ .htaccess file:</p>
<pre>
	RewriteEngine on
	RewriteCond %{HTTP:Authorization} ^(.*)
	RewriteRule ^(.*) - [E=HTTP_AUTHORIZATION:%1]
</pre>

<hr/>

<h4>Filter Validate JWT Response:</h4>

<p>You can filter the REST response object returned when you validate a token if you like:</p>

<pre>
add_filter( 'wp_headless_rest__generate_jwt_response', 'wp_rest_headless_filter_jwt_response' );
function wp_rest_headless_filter_jwt_response( $response, $token, $user ) {

	$response = array(
		'token' => $token,
		'user'  => array(
			'user_email'    => $user->data->user_email,
			'user_nicename' => $user->data->user_nicename,
			'display_name'  => $user->data->display_name,
		),
	);

	return $response;
}
</pre>