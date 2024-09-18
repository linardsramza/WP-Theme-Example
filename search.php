<?php 
get_header();

global $wp_query;

$current_page = get_query_var('paged');
$args = [
	's'                 => get_search_query(),
	'post_status'       => 'publish',
	'paged'				=> $current_page
];
$post_type = isset($_GET['pt']) ? sanitize_key($_GET['pt']) : 'workshops';

$workshops_query = new WP_Query(array_merge($args, ['post_type' => 'workshops']));
$others_query = new WP_Query(array_merge($args, ['post_type' => ['page', 'news']]));

switch ($post_type) :
    case 'others':
        $wp_query = $others_query;
        break;
    default:
        $wp_query = $workshops_query;
        break;
endswitch;

$big = 999999999; // need an unlikely integer

$pagination = paginate_links( array(
	'base' 		   => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' 	   => '?paged=%#%',
	'current' 	   => max( 1, $current_page ),
	'total' 	   => $wp_query->max_num_pages,
    'show_all'     => false,
    'type'         => 'plain',
    'end_size'     => 1,
    'mid_size'     => 2,
    'prev_next'    => true,
    'prev_text'    => '<i class="fa-solid fa-chevron-left"></i>',
    'next_text'    => '<i class="fa-solid fa-chevron-right"></i>',
) );

$text_columns_block = get_field('text_columns_block', 'options');

?>
<main id="main" role="main">
	<?php get_template_part('templates/breadcrumbs'); ?>
	<section class="block search-results">
		<div class="container container--narrow">
			<?php echo Helper::generate_header( array(
			    'text'  => __('Search results for', 'theme'). ' "'. get_search_query(). '"',
			    'tag'   => 'h1',
			    'class' => 'search-results__title heading h1'
			) ); ?>
			<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-results__search-field">
		        <input type="text" class="field" name="s" placeholder="<?php _e( 'Search', 'theme' ); ?>" value="<?php echo get_search_query(); ?>" />
		        <button type="submit" name="search-submit" class="btn btn--primary btn--search">
		        	<span class="search-text"><?php _e( 'Search', 'theme' ); ?></span>
		            <i class="icon fa-solid fa-magnifying-glass"></i>
		        </button>
		    </form>
			<?php
				$workshops_count = count(get_posts([
					'post_type' => 'workshops',
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'fields' => 'ids',
					's' => get_search_query()
				]));
				$others_count = count(get_posts([
					'post_type' => ['page', 'news'],
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'fields' => 'ids',
					's' => get_search_query()
				]));
			?>

			<div class="search-results__filters">
				<a
					href="<?php echo get_site_url(). '/?s='. get_search_query(); ?>"
					class="search-results__filter <?php if($post_type == 'workshops'){ echo ' active'; } ?>"
				>
					<?php _e('Workshops', 'theme'); ?> (<?php echo $workshops_count; ?>)
				</a>
	            <a
	            	href="<?php echo get_site_url(). '/?s='. get_search_query(). '&pt=others'; ?>"
	            	class="search-results__filter <?php if($post_type == 'others'){ echo ' active'; } ?>"
	            >
	            	<?php _e('Articles/Tips & Advice', 'theme'); ?> (<?php echo $others_count; ?>)
	            </a>
	        </div>

			<?php if( have_posts() ) : ?>

		        <div class="search-results__list">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php
		        			$post_title = Helper::generate_header(array(
				                'text'  => get_the_title(),
				                'tag'   => 'h2',
				                'class' => 'post__title heading h4'
				            ));
							$post_text = '';
				            if($post_type == 'workshops') :
				            	$address_data = get_field('workshop_address');
				            	$address = $address_data['address'] ?? '';
				            	$zip_code = $address_data['zip_code'] ?? '';
				            	$city = $address_data['city'] ?? '';

					            $post_text = Helper::generate_paragraph(array(
					                'text'  => $address. ', '. $zip_code. ' '. $city,
					                'class' => 'post__excerpt'
					            ));
					        elseif($post_type == 'others') :
					        	$post_text = Helper::generate_paragraph(array(
					                'text'  => wp_trim_words(get_the_excerpt(), 12) ?? '',
					                'class' => 'post__excerpt'
					            ));
					        endif;
		        		?>
						<a href="<?php echo get_permalink(); ?>" class="post">
							<div class="post__content">
				                <?php echo $post_title; ?>
				                <?php echo $post_text; ?>
				            </div>
			                <span class="post__link">
								<i class="fa-solid fa-long-arrow-right"></i><?php _e('Read more', 'theme'); ?>
							</span>
						</a>
					<?php endwhile; ?>
				</div>

			<?php else : ?>

				<div class="search-results__no-posts-found">
					<i class="icon fas fa-exclamation-circle"></i>
					<h2 class="heading h5"><?php _e('No search results, please try another search phrase.', 'theme'); ?></h2>
				</div>

			<?php endif; ?>

			<?php if ( $pagination ) : ?>
				<nav class="search-results__pagination pagination">
					<?php echo $pagination; ?>
				</nav>
			<?php endif; ?>
			
		</div>
	</section>
	<?php if ( $text_columns_block ) :
		$title = $text_columns_block['header'];
		$title_args = array(
			'text'  => $title['text'],
			'tag'   => $title['level'] ?? 'h2',
			'class' => 'text-columns__title heading ' . $title['style'] ?? 'h2'
		);

		$background = $text_columns_block['background'];
		$text_color = $text_columns_block['text_color'];
		$text_columns = $text_columns_block['text_columns'];
		$column_spacing = $text_columns_block['column_spacing'];

		get_template_part(
			'blocks/cta/cta',
			'template',
			array(
				'title_args' => $title_args,
				'background' => $background,
				'text_color' => $text_color,
				'text_columns' => $text_columns,
				'column_spacing' => $column_spacing
			)
		);
	endif; ?>
</main><!-- /#main -->
<?php get_footer(); ?>

