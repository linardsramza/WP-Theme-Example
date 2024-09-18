<?php
/*
	WP Admin Custom Function
*/

class Admin {
	public function __construct()
	{
		add_filter( 'admin_footer_text', [$this, 'custom_footer_admin'] );
		add_action( 'wp_before_admin_bar_render', [$this, 'admin_bar'] );
		add_action( 'admin_menu', [$this, 'admin_menu'] );
		add_action( 'wp_dashboard_setup', [$this, 'remove_dashboard_widgets'] );
		add_action( 'load-index.php', function() {
			remove_action('welcome_panel', 'wp_welcome_panel');
		} );
		add_action( 'welcome_panel', [$this, 'welcome_panel'] );
		add_action( 'admin_enqueue_scripts', [$this, 'admin_style_and_scripts'] );
		add_action( 'login_enqueue_scripts', [$this, 'login_style'] );
		add_filter( 'login_headerurl', [$this, 'login_headerurl'] );
		add_filter( 'login_headertext', [$this, 'login_headertext'] );
		add_filter( 'site_status_tests', [$this, 'remove_unneccessary_tests'] );
		add_filter( 'sanitize_file_name', [$this, 'sanitize_file_names'], 10 );
	}

	public function custom_footer_admin() 
	{
		echo '<span id="footer-thankyou">' . __('Built with love by', 'theme') . '<a href="https://www.theme.se/" target="_blank"> theme.se</a></span>';
	}

	public function admin_bar()
	{
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}

	public function admin_menu()
	{
		remove_menu_page( 'edit-comments.php' );
		if ( ! current_user_can( 'manage_options' ) ) {
			remove_menu_page( 'tools.php' );
			remove_submenu_page( 'themes.php', 'customize.php' );
		}
	}

	public function remove_dashboard_widgets()
	{
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); //Quick Press widget
		remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' ); //Recent Drafts
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); //WordPress.com Blog
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' ); //Other WordPress News
		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' ); //Incoming Links
		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' ); //Plugins
		remove_meta_box( 'dashboard_recent_comments',	 'dashboard', 'normal'); //Comments
	}

	public function welcome_panel()
	{
		list( $display_version ) = explode( '-', get_bloginfo( 'version' ) );
		$edit_posts              = current_user_can( 'edit_posts' );
		$edit_posts              = current_user_can( 'edit_posts' );
		$edit_theme_options      = current_user_can( 'edit_theme_options' );
		?>
		<div class="welcome-panel-content">
			<div class="welcome-panel-header">
				<h2>
					<?php
						printf(
							__( 'Welcome to WordPress %s!', 'theme' ),
							$display_version
						);
					?>
				</h2>
				<p class="welcome-panel-text">
					<?php 
						printf( 
							__( 'This is a site created by theme. If you have any question you could always contact us or send us an email by visiting %sour website%s.', 'theme' ),
							' <a href="http://www.theme.com" target="_blank">',
							'</a>'
						);
					?>
					<br />
					<?php
						printf( 
							__( 'We also have a %ssupport system%s where you can put all your questions or issues that you want fixed.', 'theme' ),
							' <a href="http://clients.theme.com" target="_blank">',
							'</a>'
						);
					?>
				</p>
			</div>
			<div class="welcome-panel-column-container">
				<div class="welcome-panel-column">
					<div class="welcome-panel-icon-pages"></div>
					<div class="welcome-panel-column-content">
						<h3><?php _e( 'Create rich pages with blocks' ); ?></h3>
						<p><?php _e( 'Blocks are little pieces of content which you can add to any page you create.' ); ?></p>
						<?php if ( $edit_posts ) : ?>
							<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=page' ) ); ?>"><?php _e( 'Add a new page' ); ?></a>
						<?php endif; ?>
					</div>
				</div>
				<div class="welcome-panel-column">
					<div class="welcome-panel-icon-layout"></div>
					<div class="welcome-panel-column-content">
						<h3><?php _e( 'Customize your menu' ); ?></h3>
						<p><?php _e( 'Configure your site&#8217;s menus to contain anything you want - both internal and external.' ); ?></p>
						<?php if ( $edit_theme_options ) : ?>
							<a href="<?php echo esc_url( admin_url('nav-menus.php') ); ?>"><?php _e( 'Edit your menus' ); ?></a>
						<?php endif; ?>
					</div>
				</div>
				<div class="welcome-panel-column">
					<div class="welcome-panel-icon-styles"></div>
					<div class="welcome-panel-column-content">
						<h3><?php _e( 'Edit your site settings' ); ?></h3>
						<p><?php _e( 'Tweak your site settings in the Theme Settings menu.' ); ?></p>
						<?php if ( $edit_theme_options ) : ?>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=theme-settings' ) ); ?>"><?php _e( 'Edit Theme Settings' ); ?></a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function admin_style_and_scripts() {
		$csstime = filemtime( get_template_directory() . '/inc/css/admin-styles.css' );
		wp_enqueue_style('theme_admin_styles', get_template_directory_uri() . '/inc/css/admin-styles.css', '', $csstime, 'all');

        $jstime = filemtime( get_template_directory() . '/inc/js/acf-disable-field.js' );
        wp_enqueue_script( 'acf_disable', get_template_directory_uri() . '/inc/js/acf-disable-field.js', array('wp-blocks', 'wp-dom-ready', 'acf'), $jstime );
	}

	public function login_style()
	{
		$csstime = filemtime( get_template_directory() . '/inc/css/login-styles.css' );
		wp_enqueue_style( 'theme_login_styles', get_template_directory_uri() . '/inc/css/login-styles.css', '', $csstime, 'all' ); 
	}

	public function login_headerurl()
	{
		return "https://www.theme.com/";
	}

	public function login_headertext()
	{
		return "theme";
	}

	public function remove_unneccessary_tests( $tests )
	{
		$page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$domain = parse_url($page_url);
		if ( strpos( $domain['host'], 'theme.site' ) !== false ) :
			unset( $tests['direct']['debug_enabled'] );
		endif;

		unset( $tests['direct']['plugin_theme_auto_updates'] );
		unset( $tests['direct']['theme_version'] );
		unset( $tests['async']['background_updates'] );

		return $tests;
	}

	public function sanitize_file_names( $filename )
	{
		$filename = str_replace( 'å', 'a', $filename );
		$filename = str_replace( 'ä', 'a', $filename );
		$filename = str_replace( 'ö', 'o', $filename );
		$filename = str_replace( 'Å', 'A', $filename );
		$filename = str_replace( 'Ä', 'A', $filename );
		$filename = str_replace( 'Ö', 'O', $filename );
		return $filename;
	}
}

new Admin();