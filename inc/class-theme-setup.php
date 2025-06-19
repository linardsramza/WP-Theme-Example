<?php

/**
 * Sets up the base functionality for our base theme.
 * Also provides functions for adding your own basic functions.
 */
class Theme_Setup
{
	
	public function __construct()
	{
		// Theme support
		$this->add_theme_support( 'post-thumbnails' );
		$this->add_theme_support( 'title-tag' );
		$this->add_theme_support( 'automatic-feed-links' );
		$this->remove_theme_support( 'core-block-patterns' );

		// Remove
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
		add_action( 'wp_default_scripts', [$this, 'remove_jquery_migrate'] );
		add_action( 'init', [$this, 'check_can_update'] );

		// Embed container
		add_filter( 'embed_oembed_html', [$this, 'embed_container'], 10, 3 );
		add_filter( 'video_embed_html', [$this, 'embed_container'], 10, 3 );

		// Disable automatic updates
		add_filter( 'automatic_updater_disabled', '__return_true' );

		// Remove default image sizes
		add_filter( 'intermediate_image_sizes_advanced', [$this, 'remove_default_images'], 10, 3 );

		// Reusable blocks
		add_action( 'admin_menu', [$this, 'reusable_blocks_menu'] );

		// Theme CSS to Gutenberg
		add_action( 'after_setup_theme', [$this, 'add_editor_styles'] );

		// Block styles
		add_action( 'wp_enqueue_scripts', [$this, 'remove_block_styles'], 99999 );

		// jQuery footer
		add_action( 'wp_enqueue_scripts', [$this, 'move_jquery_footer'] );

		// Remove Posts from admin
		add_action('admin_menu', [$this, 'post_remove_admin'] );

		// Add image sizes
		add_action( 'after_setup_theme', [$this, 'add_image_sizes'] );

		// Theme Options
		add_action( 'acf/init', [$this, 'options_page'] );

		// Tracking codes
		$this->tracking_codes();

		// Tiny MCE
		add_filter( 'tiny_mce_before_init', [$this, 'styles_tinymce']);
		add_filter( 'mce_buttons_2', [$this, 'buttons_tinymce']);

		// Gravity Forms
		add_filter( 'gform_confirmation_anchor', '__return_false' );
		add_filter( 'gform_field_css_class', [$this, 'gform_custom_class'], 10, 3 );
		add_filter( 'gform_submit_button', [$this, 'form_submit_button'], 10, 2 );
		add_filter( 'gform_ip_address', '__return_empty_string' );

		// Disable ACF shortcodes
		add_action( 'acf/init', [$this, 'set_acf_settings'] );

		// Remove empty field group message for non admin
		add_filter( 'acf/blocks/no_fields_assigned_message', [$this, 'remove_empty_field_group_message'], 10, 2 );

		// Load Complianz translations
		add_action( 'init', [$this, 'complianz_load_textdomain'] );

		/**
		 * Security stuff
		 */

		 // Disable XML-RPC calls
		add_filter( 'xmlrpc_enabled', '__return_false' );

		// Remove users from REST API
		add_filter( 'rest_endpoints', [$this, 'remove_users_rest'] );

		// Remove author pages from front end
		add_action( 'template_redirect', [$this, 'remove_author_pages'], 1, 1 );

		// Non-specific login error messages
		add_filter( 'login_errors', [$this, 'login_error_message'] );

		// Security headers
		add_filter('wp_headers', [$this, 'security_headers'] );

		// WP Mail SMTP plugin - remove weekly email summaries emails
		add_filter( 'wp_mail_smtp_reports_emails_summary_is_disabled', '__return_true' );

		$this->plugin_licenses();

		// Disallow edit of files directly from admin
		define('DISALLOW_FILE_EDIT', true);

		// Add excerpts to pages
		add_post_type_support( 'page', 'excerpt' );

		add_action('acf/render_field_settings/type=text', [$this, 'add_readonly_to_fields']);
		add_action('acf/render_field_settings/type=textarea', [$this, 'add_readonly_to_fields']);

		// Add language support
		add_action('after_setup_theme', [$this, 'theme_load_textdomain']);
	}

	/**
	 * Registers theme support for a given feature.
	 * 
	 * @param string $feature The feature being added.
	 */
	public function add_theme_support( $feature )
	{
		add_action('after_setup_theme', function() use ( $feature ) {
			add_theme_support( $feature );
		});
	}

	/**
	 * Deregister theme support for a certain feature
	 * 
	 * @param string $feature The feature being removed
	 */
	public function remove_theme_support( $feature )
	{
		add_action('after_setup_theme', function() use ( $feature ) {
			remove_theme_support( $feature );
		});
	}

	/**
	 * Register a navigation menu location.
	 */
	public function addNavMenus( $args )
	{
		add_action('after_setup_theme', function() use ( $args ) {
			register_nav_menus($args);
		});
	}

	/**
	 * Adds reusable blocks to the admin menu for easier access.
	 */
	public function reusable_blocks_menu() 
	{
		add_menu_page( 
			'linked_url', 
			_x( 'Reusable blocks', 'post type general name' ), 
			'read', 
			'edit.php?post_type=wp_block', 
			'', 
			'dashicons-controls-repeat', 
			40 
		);
	}

	/**
	 * Removes jQuery Migrate
	 */
	public function remove_jquery_migrate( $scripts )
	{
		if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) :
			$script = $scripts->registered['jquery'];
			if ( $script->deps ) :
				$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
			endif;
		endif;
	}

	/**
	 * Disallow updates for all domains except theme.site (locally) and theme.host (monitor)
	 */
	public function check_can_update()
	{
		$allowed = false;

		$current_user = wp_get_current_user();
		$allowed_servers = array(
			'theme.site',
			'theme.host',
		);
		foreach ($allowed_servers as $url) :
			if ( strpos( $_SERVER['SERVER_NAME'], $url ) !== FALSE ) :
				$allowed = true;
			endif;
		endforeach;
		if ( $current_user->user_login === 'theme_admin' ) :
			$allowed = true;
		endif;
		
		if ( !$allowed && !strpos( $_SERVER['REQUEST_URI'], 'wp-json') ) :
			add_filter( 'pre_site_transient_update_core', [$this, 'disable_updates'] );     // Disable WordPress core updates
			add_filter( 'pre_site_transient_update_plugins', [$this, 'disable_updates'] );  // Disable WordPress plugin updates
			add_filter( 'pre_site_transient_update_themes', [$this, 'disable_updates'] );   // Disable WordPress theme updates
		endif;
	}

	public function disable_updates() {
		global $wp_version;
		return (object) array( 
			'last_checked'    => time(), 
			'version_checked' => $wp_version,
			'updates'         => array()
		);
	}

	/**
	 * Adds an embed container for all oembed objects.
	 */
	public function embed_container( $html )
	{
		return '<div class="embed-container">' . $html . '</div>';
	}

	/**
	 * Remove the deault image sizes small, medium, large
	 */
	public function remove_default_images( $sizes )
	{
		unset( $sizes['small'] );
		unset( $sizes['medium'] );
		unset( $sizes['large'] );
		unset( $sizes['medium_large'] );
		return $sizes;
	}

	/**
	 * Adds the theme CSS to the Gutenberg editor.
	 */
	public function add_editor_styles()
	{
		add_editor_style();
		add_theme_support( 'editor-styles' );
		add_theme_support( 'align-wide' );
		add_editor_style( 'dist/css/vendor.min.css' );
		add_editor_style( 'dist/css/app.min.css' );
	}

	/**
	 * Removes unneccessary block styles from the frontend.
	 */
	public function remove_block_styles()
	{
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-blocks-style' );
	}

	/**
	 * If jQuery is used, move it to the footer.
	 */
	public function move_jquery_footer()
	{
		wp_enqueue_script("jquery");
	}

	/**
	 * Remove regular posts from admin
	 */
	public function post_remove_admin()
	{
		remove_menu_page('edit.php');
	}

	/**
	 * Adds Theme Settings option page
	 */
	public function options_page()
	{
		if( function_exists('acf_add_options_page') ) {
			acf_add_options_page( array(
				'page_title' => __('Theme Settings', 'theme'),
				'menu_title' => __('Theme Settings', 'theme'),
				'menu_slug'	 => 'theme-settings',
				'capability' => 'edit_posts',
				'redirect'   => false
			));

			acf_add_options_page([
				'page_title'	=> __('Menu Settings', THEME_NAME),
				'menu_slug'		=> 'menu-settings',
				'parent_slug'   => 'theme-settings',
				'capability'    => 'manage_options'
			]);

			acf_add_options_page([
				'page_title'	=> __('CPTs Settings', THEME_NAME),
				'menu_slug'		=> 'cpt-url-structure-settings',
				'parent_slug'   => 'theme-settings',
				'capability'    => 'manage_options'
			]);

		}
	}

	/**
	 * Function for outputting tracking codes on the page.
	 */
	public function tracking_codes()
	{
		add_action( 'wp_body_open', function() {
			if( class_exists('ACF') ) :
				the_field( 'tracking_codes_body', 'option' );
			endif;
		});
		add_action( 'wp_body_open', function() {
			if( class_exists('ACF') ) :
				the_field( 'tracking_codes_header', 'option' );
			endif;
		});
		add_action( 'wp_body_open', function() {
			if( class_exists('ACF') ) :
				the_field( 'tracking_codes_footer', 'option' );
			endif;
		});
	}

	/**
	 * Tiny MCE styles
	 */
	public function styles_tinymce( $settings )
	{
		$style_formats = array(
			array(
				'title' => __( 'Title styles', 'theme'),
				'items' => array(
					array(
						'title' => __('Title 1', 'theme'),
						'selector' => 'h1,h2,h3,h4,h5,h6',
						'classes' => 'heading h1'
					),
					array(
						'title' => __('Title 2', 'theme'),
						'selector' => 'h2,h1,h3,h4,h5,h6',
						'classes' => 'heading h2'
					),
					array(
						'title' => __('Title 3', 'theme'),
						'selector' => 'h3,h2,h1,h4,h5,h6',
						'classes' => 'heading h3'
					),
					array(
						'title' => __('Title 4', 'theme'),
						'selector' => 'h4,h2,h3,h1,h5,h6',
						'classes' => 'heading h4'
					),
					array(
						'title' => __('Title 5', 'theme'),
						'selector' => 'h5,h4,h2,h3,h1,h6',
						'classes' => 'heading h5'
					),
					array(
						'title' => __('Title 6', 'theme'),
						'selector' => 'h6,h5,h4,h2,h3,h1',
						'classes' => 'heading h6'
					),
				)
			),
			array(
				'title' => __( 'Paragraph styles', 'theme'),
				'items' => array(
					array(
						'title' => __('Preamble', 'theme'),
						'selector' => 'p',
						'classes' => 'preamble'
					),
					array(
						'title' => __('Bottom spacing large', 'theme'),
						'selector' => 'p',
						'classes' => 'mb-large'
					)
				)
			),
			array(
				'title' => __( 'List styles', 'theme'),
				'items' => array(
					array(
						'title' => __('Bottom spacing large', 'theme'),
						'selector' => 'ul,ol',
						'classes' => 'mb-large'
					)
				)
			),
			array(
				'title' => __( 'Link styles', 'theme'),
				'items' => array(
					array(
						'title' => __('Button primary', 'theme'),
						'selector' => 'a',
						'classes' => 'btn btn--primary'
					),
					array(
						'title' => __('Button secondary', 'theme'),
						'selector' => 'a',
						'classes' => 'btn btn--secondary'
					),
					array(
						'title' => __('Button yellow', 'theme'),
						'selector' => 'a',
						'classes' => 'btn btn--yellow'
					),
					array(
						'title' => __('Button red', 'theme'),
						'selector' => 'a',
						'classes' => 'btn btn--red'
					),
					array(
						'title' => __('Button white', 'theme'),
						'selector' => 'a',
						'classes' => 'btn btn--white'
					),
					array(
						'title' => __('Button blue outline', 'theme'),
						'selector' => 'a',
						'classes' => 'btn btn--blue-outline'
					),
					array(
						'title' => __('Button white outline', 'theme'),
						'selector' => 'a',
						'classes' => 'btn btn--white-outline'
					),
					array(
						'title' => __('Button small', 'theme'),
						'selector' => 'a',
						'classes' => 'btn btn--small'
					),
					array(
						'title' => __('Button large', 'theme'),
						'selector' => 'a',
						'classes' => 'btn btn--large'
					),
					array(
						'title' => __('Phone link', 'theme'),
						'selector' => 'a',
						'classes' => 'phone-link'
					),
					array(
						'title' => __('Email link', 'theme'),
						'selector' => 'a',
						'classes' => 'email-link'
					)
				)
			),
			array(
				'title' => __( 'Image styles', 'theme'),
				'items' => array(
					array(
						'title' => __('Rounded corners', 'theme'),
						'selector' => 'img',
						'classes' => 'rounded-corners'
					),
					array(
						'title' => __('Full width', 'theme'),
						'selector' => 'img',
						'classes' => 'full-width'
					)
				)
			)
		);
		$settings['style_formats'] = json_encode( $style_formats );
		return $settings;
	}

	public function buttons_tinymce( $buttons )
	{
		array_unshift( $buttons, 'styleselect' );
		return $buttons;
	}

	public function gform_custom_class( $classes, $field, $form )
	{
		$classes .= ' gform_' . $field->type;
		return $classes;
	}

	public function form_submit_button( $button, $form )
	{
		return "<button class='btn btn--primary' id='gform_submit_button_{$form["id"]}'><span>{$form['button']['text']}</span></button>";
	}

	public function set_acf_settings()
	{
		acf_update_setting( 'enable_shortcode', false );
	}

	public function remove_empty_field_group_message( $message, $block_name )
	{
		if ( get_current_user_id() !== 1 ) :
			$message = '';
		endif;
		return $message;
	}

	public function complianz_load_textdomain()
	{
		load_plugin_textdomain( 'complianz-gdpr', false, '/../languages/theme/' );
	}

	public function remove_users_rest( $endpoints )
	{
		if ( isset( $endpoints['/wp/v2/users'] ) ) :
			unset( $endpoints['/wp/v2/users'] );
		endif;
		if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) :
			unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
		endif;
		return $endpoints;
	}

	public function remove_author_pages()
	{
		$author_query = get_query_var('author');
		if ( is_author() || $author_query ) :
			global $wp_query;
			$wp_query->set_404();
			status_header(404);
		endif;
	}

	public function login_error_message()
	{
		return __('Something is wrong!', 'theme');
	}

	public function security_headers( $headers ){

		if ( !is_admin() ) :
			$headers['X-Frame-Options']        = 'SAMEORIGIN';
			$headers['X-Content-Type-Options'] = 'nosniff';
			$headers['Referrer-Policy']        = 'no-referrer-when-downgrade';
			$headers['Feature-Policy']         = "microphone 'none'";
			$headers['Permissions-Policy']     = "microphone=()";

			if ( is_ssl() ) :
				$headers['Strict-Transport-Security'] = 'max-age=31536000;includeSubDomains';
			endif;
		endif;
		
		return $headers;
		
	}

	public function plugin_licenses()
	{
		define( 'ACF_PRO_LICENSE', '***************************************' );
		define( 'SEOPRESS_LICENSE_KEY', '***************************************' );
		define( 'WPMDB_LICENCE', '***************************************' );
		define( 'GF_LICENSE_KEY', '***************************************' );
	}
	
	/**
	 * Add image sizes
	 */
	public function add_image_sizes( $sizes )
	{
		add_image_size( 'link-card-certification-image', 248);
		add_image_size( 'eu-control-form-image', 684);
	}


	public function add_readonly_to_fields($field)
	{
		acf_render_field_setting( $field, array(
			'label' => __('Read Only?','acf'),
			'instructions'  => '',
			'type' => 'radio',
			'name' => 'disabled',
			'choices' => array(
				0 => __("No",'acf'),
				1 => __("Yes",'acf'),
			),
			'layout'  =>  'horizontal',
		));
	}


	public function theme_load_textdomain() {
		$path = get_template_directory() . '/languages';
		load_theme_textdomain(THEME_NAME, $path );
	}
}