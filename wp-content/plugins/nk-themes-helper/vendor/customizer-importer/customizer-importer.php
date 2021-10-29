<?php

/**
 * The main import class.
 *
 * @since 0.1
 */
class Customizer_Import {
    public $import_images = true;

	/**
	 * Imports uploaded mods and calls WordPress core customize_save actions so
	 * themes that hook into them can act before mods are saved to the database.
	 *
	 * @since 0.1
	 * @since 0.3 Added $wp_customize param and importing of options.
	 * @access private
	 * @param object $wp_customize An instance of WP_Customize_Manager.
	 * @return void
	 */
	public function _import( $import_file_path )
	{
		// Make sure WordPress upload support is loaded.
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		// Setup global vars.
		global $wp_customize;
		global $cei_error;

		// Setup internal vars.
		$cei_error	 = false;
		$template	 = get_template();

		if ( ! file_exists( $import_file_path ) ) {
			$cei_error = __( 'Error importing settings! Please try again.', 'nk-themes-helper' );
			return;
		}

		// Get the upload data.
		$raw  = file_get_contents( $import_file_path );
		$data = @unserialize( $raw );

		// Data checks.
		if ( 'array' != gettype( $data ) ) {
			$cei_error = __( 'Error importing settings! Please check that you uploaded a customizer export file.', 'nk-themes-helper' );
			return;
		}
		if ( ! isset( $data['template'] ) || ! isset( $data['mods'] ) ) {
			$cei_error = __( 'Error importing settings! Please check that you uploaded a customizer export file.', 'nk-themes-helper' );
			return;
		}
		if ( $data['template'] != $template ) {
			$cei_error = __( 'Error importing settings! The settings you uploaded are not for the current theme.', 'nk-themes-helper' );
			return;
		}

		// Import images.
		if ( $this->import_images ) {
			$data['mods'] = $this->_import_images( $data['mods'] );
		}

		// Call the customize_save action.
		//do_action( 'customize_save', $wp_customize );

		// Loop through the mods.
		foreach ( $data['mods'] as $key => $val ) {

			// Call the customize_save_ dynamic action.
			do_action( 'customize_save_' . $key, $wp_customize );

			// Save the mod.
			set_theme_mod( $key, $val );
		}

		// Call the customize_save_after action.
		//do_action( 'customize_save_after', $wp_customize );
	}

	/**
	 * Imports images for settings saved as mods.
	 *
	 * @since 0.1
	 * @access private
	 * @param array $mods An array of customizer mods.
	 * @return array The mods array with any new import data.
	 */
	public function _import_images( $mods )
	{
		foreach ( $mods as $key => $val ) {

			if ( $this->_is_image_url( $val ) ) {

				$data = $this->_sideload_image( $val );

				if ( ! is_wp_error( $data ) ) {

					$mods[ $key ] = $data->url;

					// Handle header image controls.
					if ( isset( $mods[ $key . '_data' ] ) ) {
						$mods[ $key . '_data' ] = $data;
						update_post_meta( $data->attachment_id, '_wp_attachment_is_custom_header', get_stylesheet() );
					}
				}
			}
		}

		return $mods;
	}

	/**
	 * Taken from the core media_sideload_image function and
	 * modified to return an array of data instead of html.
	 *
	 * @since 0.1
	 * @access private
	 * @param string $file The image file path.
	 * @return array An array of image data.
	 */
    public function _sideload_image( $file )
	{
		$data = new stdClass();

		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
		}
		if ( ! empty( $file ) ) {

			// Set variables for storage, fix file filename for query strings.
			preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
			$file_array = array();
			$file_array['name'] = basename( $matches[0] );

			// Download file to temp location.
			$file_array['tmp_name'] = download_url( $file );

			// If error storing temporarily, return the error.
			if ( is_wp_error( $file_array['tmp_name'] ) ) {
				return $file_array['tmp_name'];
			}

			// Do the validation and storage stuff.
			$id = media_handle_sideload( $file_array, 0 );

			// If error storing permanently, unlink.
			if ( is_wp_error( $id ) ) {
				@unlink( $file_array['tmp_name'] );
				return $id;
			}

			// Build the object to return.
			$meta					= wp_get_attachment_metadata( $id );
			$data->attachment_id	= $id;
			$data->url				= wp_get_attachment_url( $id );
			$data->thumbnail_url	= wp_get_attachment_thumb_url( $id );
			$data->height			= $meta['height'];
			$data->width			= $meta['width'];
		}

		return $data;
	}

	/**
	 * Checks to see whether a string is an image url or not.
	 *
	 * @since 0.1
	 * @access private
	 * @param string $string The string to check.
	 * @return bool Whether the string is an image url or not.
	 */
	public function _is_image_url( $string = '' )
	{
		if ( is_string( $string ) ) {

			if ( preg_match( '/\.(jpg|jpeg|png|gif)/i', $string ) ) {
				return true;
			}
		}

		return false;
	}
}
