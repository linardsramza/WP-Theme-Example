<?php

/**
 * Removes all base Gutenberg blocks so we can start adding our own.
 * Blocks are created with a block.json file for each block and
 * managed using Advanced Custom Fields PRO.
 */
class Gutenberg
{
	
	public function __construct()
	{
		add_action( 'admin_enqueue_scripts', [$this, 'gutenberg_styles'] );
		add_filter( 'allowed_block_types_all', [$this, 'allowed_block_types'], 10, 2 );
		add_action( 'init', [$this, 'register_blocks'] );
		add_action( 'wp', [$this, 'register_block_scripts'] );
		add_filter( 'block_categories_all', [$this, 'theme_block_categories'], 10, 2 );
	}

	public function gutenberg_styles()
	{
		wp_enqueue_style( 
			'Gutenberg_styles',
			get_template_directory_uri() . '/inc/css/gutenberg-styles.css',
			'',
			false,
			'all'
		);
	}

	public function allowed_block_types( $block_editor_context, $editor_context )
	{
		$blocks = [];
		$blocks[] = 'core/block';
	
		foreach( glob( get_template_directory() . '/blocks/**/*.php' ) as $block ){
			$block = explode('/', $block);
			$block = end( $block );
			$block = str_replace('.php', '', $block);

			$is_block_restricted_to_cpt = $this->is_block_restricted_to_cpt($block, $editor_context->post->post_type);
			if($is_block_restricted_to_cpt) {
				continue;
			}

			$block = 'acf/' . $block;
			$blocks[] = $block;
		}
	
		return $blocks;
	}

	function theme_block_categories( $categories, $post ) {

		$categories[] = [
			'slug'  => 'template-blocks',
			'title' => __( 'Template Blocks', THEME_NAME ),
		];

		$categories[] = [
			'slug'  => 'workshop-blocks',
			'title' => __( 'Workshop Blocks', THEME_NAME ),
		];

		$categories[] = [
			'slug'  => 'warehouse-blocks',
			'title' => __( 'Warehouse Blocks', THEME_NAME ),
		];

		return $categories;
	}

	public function register_blocks()
	{
		if ( ! function_exists( 'register_block_type' ) ) return;
		foreach( glob( get_template_directory() . '/blocks/**/block.json' ) as $block ) :
			register_block_type( $block );
		endforeach;
	}

	public function register_block_scripts()
	{

		foreach( glob( get_template_directory() . '/blocks/**/block.json' ) as $block ) :
			$data = file_get_contents( $block );
			$data = json_decode( $data );

			$deps = array();
			$vendorScripts = array();
			$vendorStyles = array();

			if ( isset( $data->themeVendorScripts ) ) :
				if ( is_array( $data->themeVendorScripts ) ) :
					foreach ( $data->themeVendorScripts as $script ) :
						$vendorScripts[] = $script;
					endforeach;
				else:
					$vendorScripts[] = $data->themeVendorScripts;
				endif;
			endif;

			if ( isset( $data->themeVendorStyles ) ) :
				if ( is_array( $data->themeVendorStyles ) ) :
					foreach ( $data->themeVendorStyles as $style ) :
						$vendorStyles[] = $style;
					endforeach;
				else:
					$vendorStyles[] = $data->themeVendorStyles;
				endif;
			endif;

			$has_in_template = $this->has_block_in_template($data->name);

			if ( has_block( $data->name ) || $has_in_template ) :

				if ( !empty( $vendorScripts ) ) :
					foreach ( $vendorScripts as $script ) :
						if ( file_exists( get_template_directory() . '/dist/vendor/' . $script ) ) :
							$script_name = explode('/', $script);
							$script_name = end( $script_name );
							$script_name = str_replace( '.js', '', $script_name );
							$script_name = str_replace( '.min', '', $script_name );
							$deps[] = $script_name;
							$jstime = filemtime( get_template_directory() . '/dist/vendor/' . $script);
							wp_enqueue_script(
								$script_name,
								get_template_directory_uri() . '/dist/vendor/' . $script,
								array(),
								$jstime,
								true
							);
						endif;
					endforeach;
				endif;

				if ( isset( $data->themeScripts ) && $data->themeScripts === true ) :
					$script_name = str_replace( 'acf/', '', $data->name );
					if ( file_exists( get_template_directory() . '/dist/js/' . $script_name . '.min.js' ) ) :
						$jstime = filemtime( get_template_directory() . '/dist/js/' . $script_name . '.min.js' );
						wp_enqueue_script(
							$script_name,
							get_template_directory_uri() . '/dist/js/' . $script_name . '.min.js',
							$deps,
							$jstime,
							true
						);
					endif;
					if( $script_name == 'workshops-map' ) :
						wp_localize_script(
							'workshops-map',
							'restBase',
							['restbase' => home_url( '/' )]
						);
					endif;
					if( $script_name == 'eu-control-form' ) :
						wp_localize_script(
							'eu-control-form',
							'euControlForm',
							[
								'restbase' => home_url( '/' ),
								'control_deadline_str' => __('Control Deadline', THEME_NAME),
								'error_message' => __("We couldn't find a vehicle with this license plate number.", THEME_NAME),
								'loading_message' => __("LOADING..", THEME_NAME)
							]
						);
					endif;
				endif;

				if ( !empty( $vendorStyles ) ) :
					foreach ( $vendorStyles as $style ) :
						if ( file_exists( get_template_directory() . '/dist/vendor/' . $style ) ) :
							$csstime = filemtime( get_template_directory() . '/dist/vendor/' . $style );
							$style_name = explode('/', $style);
							$style_name = end( $style_name );
							$style_name = str_replace( '.css', '', $style_name );
							$style_name = str_replace( '.min', '', $style_name );

							wp_enqueue_style(
								$style_name,
								get_template_directory_uri() . '/dist/vendor/' . $style,
								array(),
								$csstime
							);
						endif;
					endforeach;
				endif;

			endif;

		endforeach;
	}

	public static function get_preview_image( $block_image, $block_name )
	{
		$output = '';
		if ( !$block_image ) return $output;
		$image = get_template_directory() . '/blocks/' . str_replace( 'acf/', '', $block_name ) . '/' . $block_image;
		if ( file_exists( $image ) ) :
			$imagetime = filemtime( $image );
			$image_src = get_template_directory_uri() . '/blocks/' . str_replace( 'acf/', '', $block_name ) . '/' . $block_image;
			$output = '<img src="' . $image_src .  '?v=' . $imagetime . '" />';
		else:
			$output = '<div class="block-editor-inserter__preview-content-missing">' . __( 'No preview available', 'theme' ) . '</div>';
		endif;
		return apply_filters('theme_block_preview_image', $output, $block_image, $block_name );
	}

	public function has_block_in_template($block_name)
	{
		if(is_404()) {
			$template_404 = get_field('page_template_404', 'options');
			return has_block( $block_name, $template_404);
		}

		if(is_archive()) {
			$news_template = get_field('page_template_news_archive', 'options');
			return has_block( $block_name, $news_template);
		}
	}

	public static function is_block_restricted_to_cpt($block_name, $post_type)
	{
		$workshop_blocks = ['workshop-hero', 'workshop-description', 'workshop-reviews', 'workshop-description-shortcode'];
		if($post_type !== 'workshops' && $post_type !== 'cpt-templates' && in_array($block_name, $workshop_blocks)) {
			return true;
		}

		$warehouse_blocks = ['warehouse-hero'];
		if($post_type !== 'warehouses' && $post_type !== 'cpt-templates' && in_array($block_name, $warehouse_blocks)) {
			return true;
		}

		$template_blocks = ['block-404', 'news-listing'];
		if($post_type !== 'cpt-templates' && in_array($block_name, $template_blocks)) {
			return true;
		}

		return false;
	}
}

new Gutenberg();