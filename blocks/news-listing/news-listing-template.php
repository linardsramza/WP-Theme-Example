<?php

/**
 * News listing Block Template.
 */

$id = '';
$className = 'block news-listing';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$hide_cats = $args['hide_category_filtering'];

global $wp_query;

$current_page = get_query_var('paged');
$args = [
	'post_type'         => 'news',
	'post_status'       => 'publish',
	'posts_per_page'	=> 12,
	'paged'				=> $current_page
];

$cat_param = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : false;
$subcat_param = isset($_GET['subcat']) ? sanitize_text_field($_GET['subcat']) : false;

if($cat_param) :
	$tax_args = [
		'tax_query' => array(
	        array (
	            'taxonomy' => 'news-categories',
	            'field' => 'slug',
	            'terms' => $cat_param,
	        )
	    )
	];
	if($subcat_param) :
		$tax_args = [
			'tax_query' => array(
				'relation' => 'AND',
		        array (
		            'taxonomy' => 'news-categories',
		            'field' => 'slug',
		            'terms' => $cat_param,
		        ),
		        array (
		            'taxonomy' => 'news-categories',
		            'field' => 'slug',
		            'terms' => $subcat_param,
		        )
		    )
		];
	endif;
	$wp_query = new WP_Query(array_merge($args, $tax_args));
else :
	$wp_query = new WP_Query($args);
endif;

$big = 999999999; // need an unlikely integer

$pagination = paginate_links( array(
	'base' 		   => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' 	   => '?paged=%#%',
	'current'	   => max( 1, get_query_var('paged') ),
	'total' 	   => $wp_query->max_num_pages,
	'show_all'     => false,
    'type'         => 'plain',
    'end_size'     => 1,
    'mid_size'     => 2,
    'prev_next'    => true,
    'prev_text'    => '<i class="fa-solid fa-chevron-left"></i>',
    'next_text'    => '<i class="fa-solid fa-chevron-right"></i>'
) );

$categories = get_terms(array(
    'taxonomy'   => 'news-categories',
    'hide_empty' => true,
    'parent'   	 => 0
));

if($cat_param) :
	$term = get_term_by('slug', $cat_param, 'news-categories');
	$sub_categories = get_terms(array(
	    'taxonomy'   => 'news-categories',
	    'hide_empty' => true,
	    'child_of'   => $term->term_id
	));
else :
	$sub_categories = array();
	if($categories) :
		foreach($categories as $cat) :
			$terms = get_terms(array(
			    'taxonomy'   => 'news-categories',
			    'hide_empty' => true,
			    'child_of'   => $cat->term_id
			));
			if($terms) :
				foreach($terms as $term) :
					array_push($sub_categories, $term);
				endforeach;
			endif;
		endforeach;
	endif;
endif;

$news_archive_link = get_post_type_archive_link('news');

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

	<div id="news-anchor"></div>
	
	<?php if($hide_cats  == false || $hide_cats == null) : ?>
		<div class="container">
			<div class="news-listing__categories">
				<a href="<?php echo $news_archive_link. '#news-anchor'; ?>" class="news-listing__category <?php if(!$cat_param){ echo 'active'; }?>"><?php _e('All articles', THEME_NAME); ?></a>
				<?php if($categories) : foreach($categories as $cat) : ?>
					<a href="<?php echo $news_archive_link. '?cat='. $cat->slug. '#news-anchor'; ?>" class="news-listing__category <?php if($cat_param && $cat_param == $cat->slug){ echo 'active'; }?>"><?php echo $cat->name; ?></a>
				<?php endforeach; endif; ?>
			</div>
			<div class="news-listing__categories--mobile">
				<?php
					array_unshift($categories, (object)['slug' => false, 'name' => __('All articles', THEME_NAME)]);
					if($cat_param) : foreach($categories as $key => $cat) :
						if($cat_param == $cat->slug) {
							unset($categories[$key]);
							array_unshift($categories, $cat);
							array_values($categories);
						}
					endforeach; endif;
				?>
				<?php _e('Show', THEME_NAME); ?>
				<select class="news-listing__categories--dropdown" id="categories-select">
					<?php if($categories) : foreach($categories as $cat) : ?>
						<option
							<?php if($cat->slug) : ?>
								value="<?php echo $news_archive_link. '?cat='. $cat->slug. '#news-anchor'; ?>"
							<?php else : ?>
								value="<?php echo $news_archive_link. '#news-anchor'; ?>"
							<?php endif; ?>
						>
							<?php echo $cat->name; ?>
						</option>
					<?php endforeach; endif; ?>
				</select>
			</div>
		</div>	

		<?php if($sub_categories) : ?>
			<div class="news-listing__tags-scroll-container">
				<div class="container">
					<div class="news-listing__tags">
						<?php foreach($sub_categories as $tag) : ?>
							<?php
								$parent_term = get_term($tag->parent, 'news-categories');
								$tag_link = $news_archive_link. '?cat='. $parent_term->slug .'&subcat='. $tag->slug;
							?>
							<span class="news-listing__tag-wrapper">
								<a
									href="<?php echo $tag_link. '#news-anchor'; ?>"
									class="news-listing__tag <?php if($subcat_param && $subcat_param == $tag->slug){ echo 'active'; }?>"
									data-attr="<?php echo $tag->slug; ?>"
								>
									<?php echo $tag->name; ?>
								</a>
							</span>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>	

	<div class="container">
		<?php if( have_posts() ) : ?>
	        <div class="news-listing__list-wrapper">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php
						$id = get_the_ID();
						$title = get_the_title();
						$post_categories = get_the_terms($id, 'news-categories');
						$feat_img = get_the_post_thumbnail_url();
						if(empty($feat_img)) :
							$feat_img = get_field('placeholder_image', 'options');
						endif;
						$background_position = get_field('background_position', $id) ?? 'center';
					?>
					<a
						href="<?php echo get_permalink(); ?>"
						class="article"
						title="<?php echo $title; ?>"
					>
						<div
							class="article__image background--<?php echo $background_position; ?>"
							<?php if($feat_img) : ?>
								style="background-image: url('<?php echo $feat_img; ?>');"
							<?php endif; ?>
						></div>
						<div class="article__content">
							<div class="article__meta">
								<?php if($post_categories) : foreach($post_categories as $cat) : ?>
									<span><?php echo $cat->name; ?> • </span>
								<?php endforeach; endif; ?>
								<?php echo get_the_date(); ?>
							</div>
							<?php echo Helper::generate_header(array(
								'text'        => $title,
								'tag'         => 'h2',
								'class'       => 'article__title heading h5'
							)); ?>
							<div class="article__link-wrapper">
								<span class="article__link">
									<i class="fa-solid fa-long-arrow-right"></i>
									<?php _e('Read more', THEME_NAME); ?>
								</span>
							</div>
						</div>
					</a>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>

		<?php if ( $pagination ) : ?>
			<nav class="news-listing__pagination pagination">
				<?php echo $pagination; ?>
			</nav>
		<?php endif; ?>
	</div>
</section>