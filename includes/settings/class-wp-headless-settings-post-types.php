<?php
/**
 * WP_Headless_Settings_Endpoints
 *
 * Handles all logic for generating the settings Main Option section
 *
 * @author Ben Moody
 */

class WP_Headless_Settings_Endpoints extends WP_Headless_Settings {

	/**
	 * create_fields
	 *
	 * @CALLED BY WP_Headless_Settings::create_tab__main_options()
	 *
	 * Init the creation of fields for this page
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function create_fields() {

		//Register post types list
		$this->register_field__post_types();

	}

	/**
	 * register_field__post_types
	 *
	 * @CALLED BY $this->create_fields()
	 *
	 * Add field to WP settings API, this is a html list not a field so we are
	 *     not registering the settings as there is nothing to save in options
	 *     table
	 *
	 * @access private
	 * @author Ben Moody
	 */
	private function register_field__post_types() {

		//vars
		$field_slug = self::$plugin_prefix . 'post-types-list';

		//Register field
		add_settings_field(
			$field_slug,
			esc_html_x( 'Registered Post Types', 'settings field title', WP_REST_HEADLESS_TEXT_DOMAIN ),
			array( $this, 'render_post_types_list' ),
			$this->menu_slug,
			$this->settings_section_id
		);

	}

	/**
	 * render_post_types_list
	 *
	 * @CALLED BY register_field__manual_trigger() :: add_settings_field
	 *     callback
	 *
	 * Renders the list of registered post types
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function render_post_types_list() {

		//vars
		$post_types = WP_Headless_Core::$registered_post_types;

		if ( is_array( $post_types ) && ! empty( $post_types ) ) {

			?>
			<ul>
				<?php foreach ( $post_types as $post_type ) : ?>
					<li>
						<strong style="color: green;">
							<?php echo esc_html( $post_type ); ?>
						</strong>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php

		} else {

			?>
			<p style="color: red;">Please register some post types using the
				filter above.</p>
			<?php

		}

	}

}