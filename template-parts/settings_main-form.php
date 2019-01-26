<?php
/**
 * Plugin settings page template
 *
 * Place a copy of this in your theme under 'jam3-cookie-banner' subdirectory
 * to override this template
 */
?>
<?php if ( defined( 'WP_REST_HEADLESS_PLUGIN_LOADED' ) ): ?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h1><?php echo esc_html( $page_title ); ?></h1>

		<?php
		//we check if the page is visited by click on the tabs or on the menu button.
		$active_tab = WP_Headless_Settings::get_active_settings_page_tab();
		?>

		<!-- wordpress provides the styling for tabs. -->
		<h2 class="nav-tab-wrapper">
			<!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
			<a href="?page=<?php echo rawurlencode( $menu_slug ); ?>&tab=main-options" class="nav-tab <?php if('main-options' === $active_tab){echo 'nav-tab-active';} ?> ">
				<?php echo esc_html_x( 'Main Menu', 'tab nav title', WP_REST_HEADLESS_TEXT_DOMAIN ); ?>
			</a>

			<a href="?page=<?php echo rawurlencode( $menu_slug ); ?>&tab=post-types" class="nav-tab <?php if('post-types' === $active_tab){echo 'nav-tab-active';} ?> ">
				<?php echo esc_html_x( 'Post Types', 'tab nav title', WP_REST_HEADLESS_TEXT_DOMAIN ); ?>
			</a>
		</h2>

		<form method="post" action="options.php">
			<?php

			settings_fields( $option_group );

			do_settings_sections( $menu_slug );

			submit_button();

			?>
		</form>
	</div>
<?php endif; ?>
