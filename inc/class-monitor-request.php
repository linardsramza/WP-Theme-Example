<?php

class Monitor_Request {
	function __construct()
	{
		add_action( 'admin_init', [$this, 'add_monitor_settings'] );
		add_action( 'admin_init', [$this, 'generate_monitor_key'] );
		add_action( 'admin_init', [$this, 'delete_monitor_key'] );
		add_action( 'rest_api_init', [$this, 'add_site_info_endpoint'] );
	}

	public function add_monitor_settings()
	{
		add_settings_section(  
			'monitor_settings',
			__( 'Monitor Settings', 'theme' ),
			[$this, 'monitor_settings_field'], 
			'general'
		);
	}

	public function monitor_settings_field()
	{
		$monitor_key      = get_option( 'monitor-key' );
		$generate_key_url = admin_url();
		$generate_key_url = add_query_arg( 'generate_monitor_key', 'true', $generate_key_url );
		$generate_key_url = wp_nonce_url( $generate_key_url, 'generate_monitor_key' );

		if ( !$monitor_key ) :
			
			?>
			<p>
				<a class="button button-secondary" href="<?php echo esc_url( $generate_key_url ); ?>">
					<?php _e( 'Generate a monitor key', 'theme' ); ?>
				</a>
			</p>
			<?php
		else:
			$remove_key_url = admin_url();
			$remove_key_url = add_query_arg( 'delete_monitor_key', 'true', $remove_key_url );
			$remove_key_url = wp_nonce_url( $remove_key_url, 'delete_monitor_key' );
			?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">
						<?php _e( 'Monitor key:', 'theme' ); ?>
					</th>
					<td>
						<pre><?php echo $monitor_key; ?></pre>
					</td>
				</tr>
			</table>
			<p>
				<a class="button button-secondary" href="<?php echo esc_url( $generate_key_url ); ?>">
					<?php _e( 'Generate a new monitor key', 'theme' ); ?>
				</a>
			</p>
			<p>
				<a style="color:#b32d2e;" href="<?php echo esc_url( $remove_key_url ); ?>" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete the monitor key?', 'theme'); ?>')">
					<?php _e( 'Delete monitor key', 'theme' ); ?>
				</a>
			</p>
			<?php
		endif;
	}

	public function generate_monitor_key()
	{
		if ( !isset( $_GET['generate_monitor_key'] ) ) return;
		check_admin_referer( 'generate_monitor_key' );
		$new_key = wp_generate_password( 20, false );
		update_option( 'monitor-key', $new_key );
		wp_safe_redirect( wp_get_referer() );
	}

	public function delete_monitor_key()
	{
		if ( !isset( $_GET['delete_monitor_key'] ) ) return;
		check_admin_referer( 'delete_monitor_key' );
		delete_option( 'monitor-key' );
		wp_safe_redirect( wp_get_referer() );
	}

	public function add_site_info_endpoint()
	{
		register_rest_route( 'theme/v1', '/site-info', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [$this, 'get_site_info'],
			'permission_callback' => "__return_true",
		) );
	}

	public function get_site_info( WP_REST_Request $request )
	{
		$monitor_key = get_option( 'monitor-key' );
		$request_monitor_key = $request->get_param( 'monitor-key' );
		if ( $monitor_key === '' || $request_monitor_key === '' || $monitor_key !== $request_monitor_key ) :
			return new WP_Error( 'rest_no_route', 'No route was found matching the URL and request method.', array('status' => 404) );
		endif;

		$return = array();

		$return            = $this->site_info();
		$return['plugins'] = $this->plugins_info();

		nocache_headers();

		$response = new WP_REST_Response($return);
		$response->set_status(200);

		return $response;
	}

	public function site_info()
	{
		$return = array();
		require ABSPATH . WPINC . '/version.php';
		global $wpdb, $wp_version;

		$wp_cron_disabled  = defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON;
		$alternate_wp_cron = defined( 'ALTERNATE_WP_CRON' ) && ALTERNATE_WP_CRON;

		$site = array(
			'site_name'          => get_bloginfo( 'name' ),
			'version'            => $wp_version,
			'multisite'          => is_multisite() ? '1' : '0',
			'language'           => get_bloginfo( 'language' ),
			'memory_limit'       => WP_MEMORY_LIMIT,
			'debug_mode'         => WP_DEBUG ? '1' : '0',
			'debug_log'          => WP_DEBUG_LOG ? '1' : '0',
			'debug_display'      => WP_DEBUG_DISPLAY ? '1' : '0',
			'script_debug'       => SCRIPT_DEBUG ? '1' : '0',
			'wp_cron'            => ! $wp_cron_disabled ? '1' : '0',
			'alternativ_wp_cron' => ! $alternate_wp_cron ? '1' : '0',
		);

		$server = array(
			'software'      => esc_html( $_SERVER['SERVER_SOFTWARE'] ),
			'port'          => esc_html( $_SERVER['SERVER_PORT'] ),
			'document_root' => esc_html( $_SERVER['DOCUMENT_ROOT'] ),
		);

		$php = array(
			'version'             => esc_html( phpversion() ),
			'memory_limit'        => esc_html( ini_get( 'memory_limit' ) ),
			'max_execution_time'  => esc_html( ini_get( 'max_execution_time' ) ),
			'upload_max_filesize' => esc_html( ini_get( 'upload_max_filesize' ) ),
			'max_file_uploads'    => esc_html( ini_get( 'max_file_uploads' ) ),
			'post_max_size'       => esc_html( ini_get( 'post_max_size' ) ),
			'max_input_vars'      => esc_html( ini_get( 'max_input_vars' ) ),
			'curl_enabled'        => function_exists( 'curl_init' ) ? 'Yes' : 'No',
			'extensions'          => get_loaded_extensions()
		);

		$database = array(
			'management_system' => $this->get_dbms_type(),
			'version'           => $this->get_db_version(),
			'character_set'     => $this->get_db_character_set(),
			'collation'         => $this->get_db_collation(),
		);

		return array(
			'site'     => $site,
			'server'   => $server,
			'php'      => $php,
			'database' => $database
		);
	}

	public function plugins_info()
	{
		$return = array();
		$all_plugins     = get_plugins();
		$active_plugins  = get_option('active_plugins');

		wp_clean_plugins_cache();
		wp_update_plugins();
		$current         = get_site_transient( 'update_plugins' );

		foreach ( $active_plugins as $plugin ) :
			if( isset( $all_plugins[$plugin] ) ) :
				$all_plugins[$plugin]['Activated'] = true;
			endif;
			if ( isset( $current->response[ $plugin ] ) ) :
				$all_plugins[$plugin]['Update'] = $current->response[ $plugin ];
			endif;
		endforeach;
		
		return $all_plugins;
	}

	public static function get_db_version()
	{
		global $wpdb;

		$ver = $wpdb->get_var( 'SELECT version();' );

		return preg_replace( '/[^0-9.].*/', '', $ver );
	}

	public static function get_dbms_type()
	{
		global $wpdb;

		$ver = $wpdb->get_var( 'SELECT version();' );

		return strpos( strtolower( $ver ), 'mariadb' ) ? 'MariaDB' : 'MySQL';
	}

	public static function get_db_character_set()
	{
		global $wpdb;

		$character_set = $wpdb->get_var( 'SELECT @@character_set_database' );

		return $character_set;
	}

	public static function get_db_collation()
	{
		global $wpdb;

		$collation = $wpdb->get_var( 'SELECT @@collation_database' );

		return $collation;
	}

}
new Monitor_Request();