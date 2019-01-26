<?php
/**
 * WP_Headless_Settings_Main_Options
 *
 * Handles all logic for generating the settings Main Option section
 *
 * @author Ben Moody
 */

class WP_Headless_Settings_Main_Options extends WP_Headless_Settings {

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

		//Register Time to trash field
		$this->register_field__time_to_trash();

	}

	/**
	 * register_field__time_to_trash
	 *
	 * @CALLED BY $this->create_fields()
	 *
	 * Add field and register setting with WP settings API
	 *
	 * @access private
	 * @author Ben Moody
	 */
	private function register_field__time_to_trash() {

		//vars
		$field_slug = self::$plugin_prefix . 'time-to-trash';

		//Register field
		add_settings_field(
			$field_slug,
			esc_html_x( 'Post GDPR Trash Age (in days)', 'settings field title', WP_REST_HEADLESS_TEXT_DOMAIN ),
			array( $this, 'render_field__time_to_trash' ),
			$this->menu_slug,
			$this->settings_section_id,
			array(
				'id' => $field_slug,
			)
		);

		register_setting(
			$this->option_group,
			$field_slug,
			array(
				'type'              => 'integer',
				'description'       => esc_html_x( 'Age of GDPR post in days before it is moved to GDPR trash', 'register setting description', WP_REST_HEADLESS_TEXT_DOMAIN ),
				'sanitize_callback' => array(
					$this,
					'sanitize__time_to_trash',
				),
				'show_in_rest'      => false,
				'default'           => '0',
			)
		);

	}

	/**
	 * render_field__time_to_trash
	 *
	 * @CALLED BY register_field__time_to_trash() :: add_settings_field callback
	 *
	 * Render field HTML
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function render_field__time_to_trash( $args ) {

		if ( ! isset( $args['id'] ) ) {
			return;
		}

		?>
		<input
				type="text"
				name="<?php echo esc_attr( $args['id'] ); ?>"
				id="<?php echo esc_attr( $args['id'] ); ?>"
				value="<?php echo esc_html( WP_Headless_Settings::get_option( $args['id'] ) ); ?>"/>
		<span class="description">
			<?php echo esc_html_x( 'Time before posts are moved to GDPR trash. Must be less than Post Delete Age value', 'settings field title', WP_REST_HEADLESS_TEXT_DOMAIN ); ?>
		</span>
		<?php

	}

	/**
	 * sanitize__time_to_trash
	 *
	 * @CALLED BY register_field__time_to_trash() :: add_settings_field
	 *     sanitize callback
	 *
	 * Sanitize int value for time to delete. Also perform validation on
	 *     provided value to ensure it's LOWER than the Post Delete Age value.
	 *     Reset it to the default value if validation fails
	 *
	 * @param int $value
	 *
	 * @return int $value
	 * @access public
	 * @author Ben Moody
	 */
	public function sanitize__time_to_trash( $value ) {

		//vars
		$field_slug          = self::$plugin_prefix . 'time-to-trash';
		$days_to_gdpr_delete = WP_Headless_Core::$days_to_delete;
		$admin_notice        = null;
		$admin_notice_type   = null;

		//First make sure value is int
		$value = intval( $value );

		//If days to delete are not set yet try $_POST
		if ( empty( $days_to_gdpr_delete ) && isset( $_POST['wp_rest_headless_time-to-delete'] ) ) {
			$days_to_gdpr_delete = intval( $_POST['wp_rest_headless_time-to-delete'] );
		}

		//Validate that requested trash timeframe is greater than time to put post in GDPR trash
		if ( ( false === $days_to_gdpr_delete ) || ( $value >= intval( $days_to_gdpr_delete ) ) ) {

			$value = 150;

			//Add admin notice making user aware of invalid value
			$admin_notice      = esc_html_x( 'Time before posts are placed in GDPR trash must be LESS than Post Delete Age. Value reset to default 150 days', 'admin notice', WP_REST_HEADLESS_TEXT_DOMAIN );
			$admin_notice_type = 'error';

			//Create admin notice for user
			add_settings_error(
				$field_slug,
				$field_slug,
				$admin_notice,
				$admin_notice_type
			);

		}

		return intval( $value );
	}

}