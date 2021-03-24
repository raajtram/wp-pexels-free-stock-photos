<?php

/*
	Pexels: Free Stock Photos
	https://raajtram.com/plugins/pexels/
	Author: Raaj Trambadia (https://raajtram.com)
*/


/**
 * Register and enqueue a custom scripts in the WordPress admin.
 */
function pexels_fsp_images_enqueue_custom_admin_style() {
	wp_enqueue_script( 'pexels_fsp_images_script', plugin_dir_url( __FILE__ ) . 'pexels_fsp_images.js' );
	$options = get_option( 'pexels_fsp_images_options' );
	wp_add_inline_script( 'pexels_fsp_images_script', 'const OPTIONS = ' . json_encode( array(
			'searchLocale' => $options['search_locale'],
			'apiKey'       => $options['api_key'],
		) ), 'before' );
}

add_action( 'admin_enqueue_scripts', 'pexels_fsp_images_enqueue_custom_admin_style' );

/* Add the menu */
function add_admin_menu() {
	add_submenu_page(
		'upload.php',
		__( 'Pexels: Free Stock Photos', 'pexels_fsp_images' ),
		__( 'Pexels Photos', 'pexels_fsp_images' ),
		'edit_posts',
		'pexels_fsp_images_settings',
		'pexels_fsp_images_settings_page'
	);
	add_action( 'admin_init', 'register_pexels_fsp_images_options' );
}

add_action( 'admin_menu', 'add_admin_menu', 99 );

/* Register the options */
function register_pexels_fsp_images_options() {
	register_setting( 'pexels_fsp_images_options', 'pexels_fsp_images_options', 'pexels_fsp_images_options_validate' );
	add_settings_section( 'pexels_fsp_images_options_section', '', '', 'pexels_fsp_images_settings' );
	add_settings_field( 'attribution-id', __( 'Attribution', 'pexels_fsp_images' ), 'pexels_fsp_images_render_attribution', 'pexels_fsp_images_settings', 'pexels_fsp_images_options_section' );
	add_settings_field(
		'search-locale-id', // ID
		__( 'Search locale', 'pexels_fsp_images' ), // Title
		'pexels_fsp_images_render_search_locale', // Callback
		'pexels_fsp_images_settings', // Page
		'pexels_fsp_images_options_section' // Section
	);
	add_settings_field(
		'api-key-id',
		__( 'Pexels API Key', 'pexels_fsp_images' ), // Title
		'pexels_fsp_images_render_pexels_api_key', // Callback
		'pexels_fsp_images_settings', // Page
		'pexels_fsp_images_options_section' // Section
	);
}

/* Attribution field */
function pexels_fsp_images_render_attribution() {
	$options = get_option( 'pexels_fsp_images_options' );
	echo '<label><input name="pexels_fsp_images_options[attribution]" value="true" type="checkbox" ' . ( ! $options['attribution'] | $options['attribution'] == 'true' ? ' checked="checked"' : '' ) . ' > ' . __( 'Automatically insert image captions with attribution.', 'pexels_fsp_images' ) . '</label>';
}

/* Get the settings option array and print one of its values */
function pexels_fsp_images_render_search_locale() {
	$options = get_option( 'pexels_fsp_images_options' );
	printf(
		'<input type="text" id="search_locale" name="pexels_fsp_images_options[search_locale]" value="%s" />',
		isset( $options['search_locale'] ) ? esc_attr( $options['search_locale'] ) : ''
	);
}

function pexels_fsp_images_render_pexels_api_key() {
	$options = get_option( 'pexels_fsp_images_options' );
	printf(
		'<input type="text" id="api_key" name="pexels_fsp_images_options[api_key]" value="%s" />',
		isset( $options['api_key'] ) ? esc_attr( $options['api_key'] ) : ''
	);
}


/* HTML for the settings page */
function pexels_fsp_images_settings_page() { ?>
	<div class="wrap">
		<h2><?= _e( 'Pexels: Free Stock Photos Images', 'pexels_fsp_images' ); ?></h2>
		<?php media_pexels_fsp_images_tab(); ?>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'pexels_fsp_images_options' );
			do_settings_sections( 'pexels_fsp_images_settings' );
			submit_button();
			?>
		</form>
		<hr style="margin-bottom:20px">
		<p>
			Photos provided by <a href="https://pexels.com/?utm_source=wordpress-plugin&utm_medium=settings-page"
			                      target="_blank"><img src="<?= plugin_dir_url( __FILE__ ) . 'img/pexels-logo.png' ?>"
			                                           style="margin:0 3px;position:relative;top:4px" width="80"></a>.
			Plugin developed and maintained by <a
				href="https://raajtram.com/?utm_source=pexels-wp-plugin&utm_medium=settings-page">@raajtram</a>.
		</p>
		<p>
			If this plugin helped you, you can show your appreciation by <a
				href="https://wordpress.org/support/plugin/wp-pexels-free-stock-photos/reviews/#new-post"
				target="_blank" rel="noopener nofollow">leaving a review</a>.

		</p>
		View the <a href="https://raajtram.com/plugins/pexels/?utm_source=pexels-wp-plugin&utm_medium=settings-page"
		            target="_blank">plugin documentation</a> for help.
		<p>
		</p>
	</div>
<?php }


/* validate settings */
function pexels_fsp_images_options_validate( $input ) {
	global $pexels_fsp_images_gallery_languages;
	$options = get_option( 'pexels_fsp_images_options' );
	if ( $input['attribution'] ) {
		$options['attribution'] = 'true';
	} else {
		$options['attribution'] = 'false';
	}

	if ( isset( $input['search_locale'] ) ) {
		$options['search_locale'] = sanitize_text_field( $input['search_locale'] );
	}

	if ( isset( $input['api_key'] ) ) {
		$options['api_key'] = sanitize_text_field( $input['api_key'] );
	}

	return $options;
}

?>
