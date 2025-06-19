<?php
// Language support
load_theme_textdomain( 'theme', get_template_directory() . '/languages' );

// All of the base theme functions
require_once(get_template_directory() . '/inc/debug-functions.php');
require_once(get_template_directory() . '/inc/class-theme-setup.php');
require_once(get_template_directory() . '/inc/class-helper.php');
require_once(get_template_directory() . '/inc/class-admin.php');
require_once(get_template_directory() . '/inc/class-gutenberg.php');
if ( !class_exists( 'Monitor_Request' ) ) :
	require_once(get_template_directory() . '/inc/class-monitor-request.php');
endif;

// Theme specific functions
require_once(get_template_directory() . '/inc/class-custom-functions.php');
require_once(get_template_directory() . '/inc/class-custom-hooks.php');
require_once(get_template_directory() . '/inc/class-register-cpt.php');
require_once(get_template_directory() . '/inc/class-register-taxonomies.php');
require_once(get_template_directory() . '/inc/class-custom-rest-routes.php');
require_once(get_template_directory() . '/inc/class-shortcodes.php');

$theme_setup = new Theme_Setup;

$theme_setup->addNavMenus( array( 
	'primary' => __( 'Primary Menu', 'theme' )
) );

new Register_CPTs();
new Register_Taxonomies();
new Register_Shortcodes();

// Styles and scripts
add_action( 'wp_enqueue_scripts', function() {

	// App style
	if ( file_exists( get_template_directory() . '/dist/css/app.min.css' ) ) :
		$csstime = filemtime( get_template_directory() . '/dist/css/app.min.css' );
		wp_enqueue_style( 'app', get_template_directory_uri() . '/dist/css/app.min.css', array(), $csstime );
	endif;

	// App script
	if ( file_exists( get_template_directory() . '/dist/js/app.min.js' ) ) :
		$jstime = filemtime( get_template_directory() . '/dist/js/app.min.js' );
		wp_enqueue_script( 'app', get_template_directory_uri() . '/dist/js/app.min.js', array(), $jstime, true );
	endif;

	// Menu script
	if ( file_exists( get_template_directory() . '/dist/js/menu.min.js' ) ) :
		$jstime = filemtime( get_template_directory() . '/dist/js/menu.min.js' );
		wp_enqueue_script( 'menu', get_template_directory_uri() . '/dist/js/menu.min.js', array(), $jstime, true );
	endif;

});

// Allow SVG's
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
  $filetype = wp_check_filetype( $filename, $mimes );
  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );

define('THEME_NAME', 'theme' );

function head_scripts() {
	$head_scripts = get_field('head_scripts', 'options');

	echo $head_scripts;
}

add_action('wp_head', 'head_scripts');

function body_scripts() {
	$body_scripts = get_field('body_scripts', 'options');

	echo $body_scripts;
}

add_action('wp_body_open', 'body_scripts');