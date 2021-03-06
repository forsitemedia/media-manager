<?php

/**
 * Media Manager admin page.
 */
class Media_Manager_Admin extends Media_Manager_Core {

	/**
	 * Fire the constructor up :)
	 */
	public function __construct() {

		// Add to hooks
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'create_admin_page' ) );

	}

	/**
	 * Init plugin options to white list our options.
	 */
	public function register_settings() {
		register_setting(
			self::GROUP,               // The settings group name
			self::OPTION,              // The option name
			array( $this, 'sanitize' ) // The sanitization callback
		);
	}

	/**
	 * Create the page and add it to the menu.
	 */
	public function create_admin_page() {
		add_media_page(
			__ ( 'Manager', 'media-manager' ), // Page title
			__ ( 'Manager', 'media-manager' ),       // Menu title
			'manage_options',                           // Capability required
			self::SLUG,                            // The URL slug
			array( $this, 'admin_page' )                // Displays the admin page
		);
	}

	/**
	 * Output the admin page.
	 */
	public function admin_page() {

		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Media Manager', 'media-manager' ); ?></h1>
			<p><?php esc_html_e( 'Place a description of what the admin page does here to help users make better use of the admin page.', 'media-manager' ); ?></p>

			<form method="post" action="options.php">

				<table class="form-table">

					<tr>
						<th>
							<?php esc_html_e( 'Select post-types', 'media-manager' ); ?>
						</th>
						<td><?php

						$post_types = get_post_types( array( 'public' => true ) );
						foreach ( $post_types as $key => $post_type ) {

							// Ignore attachments, since they're what we're trying to remove
							if ( 'attachment' == $post_type ) {
								continue;
							}

							// Get existing settings
							$post_types = $this->get_post_types();
							if ( isset( $post_types[$post_type] ) ) {
								$checked = 1;
							} else {
								$checked = 0;	
							}

							?>

							<p>
								<input 
									id="<?php echo esc_attr( self::OPTION . '[post_types][' . $post_type . ']' ); ?>" 
									name="<?php echo esc_attr( self::OPTION . '[post_types][' . $post_type . ']' ); ?>" 
									type="checkbox" 
									value="1"
									<?php checked( $checked, 1, true ); ?>
								 />
								<label for="<?php echo esc_attr( self::OPTION . '[post_types][' . $post_type . ']' ); ?>"><?php echo esc_html( $post_type ); ?></label>
							</p><?php
						}

						?>

						</td>
					</tr>

				</table>

				<?php settings_fields( self::GROUP ); ?>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'media-manager' ); ?>" />
				</p>
			</form>

		</div><?php
	}

	/**
	 * Sanitize the settings.
	 *
	 * @param   string   $input   The input array
	 * @return  array             The sanitized array
	 */
	public function sanitize( $input ) {

		foreach ( $input as $type => $selection ) {
			$new_type = esc_html( $type );
			foreach ( $selection as $name => $value ) {
				$new_name = esc_html( $name );
				$new_value = esc_html( $value );
				$new_selection[$new_name] = $new_value;
			}
			$output[$new_type] = $new_selection;
		}

		return $output;
	}

}
