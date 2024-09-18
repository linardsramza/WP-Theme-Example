<?php

/**
 * Latest news Block Template.
 */

$id = '';
$className = 'block latest-news';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$title_args = $args['title_args'];
$title_h_level = (int) filter_var($args['title_h_level'], FILTER_SANITIZE_NUMBER_INT);
$posts = $args['posts'];
$number_of_posts = $args['number_of_posts'];
$link = $args['link'];

if(!$posts) :
	$posts = get_posts(array(
		'post_type'      => 'news',
		'post_status'    => 'publish',
		'posts_per_page' => $number_of_posts
	));
endif;

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<?php if($title_args || $link) : ?>
			<div class="latest-news__title-wrapper">
				<?php echo Helper::generate_header($title_args); ?>
				<?php if($link) : ?>
					<div class="latest-news__link-wrapper desktop">
						<a
							href="<?php echo $link['url']; ?>"
			                class="latest-news__link"
			                <?php if($link['target']) { echo 'target="_blank"'; } ?>
						>
							<?php echo $link['title']; ?><i class="fa-solid fa-arrow-right"></i>
						</a>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if($posts) : ?>
			<?php
				$latest_post = array_slice($posts, 0, 1)[0];
				$other_posts = array_slice($posts, 1, 3);
			?>
			<div class="row row--stretch">
				<div class="row__col row__col--md-7">
					<?php
						$categories = get_the_terms($latest_post->ID, 'news-categories');
						$feat_img = get_the_post_thumbnail_url($latest_post->ID);
						if(empty($feat_img)) :
							$feat_img = get_field('placeholder_image', 'options');
						endif;
					?>
					<a
						href="<?php echo get_permalink($latest_post->ID); ?>"
						class="latest-post"
						title="<?php echo get_the_title($latest_post->ID); ?>"
						<?php if($feat_img) : ?>
							style="background-image: url('<?php echo $feat_img; ?>');"
						<?php endif; ?>
					>
						<div class="latest-post__meta">
							<?php if($categories) : foreach($categories as $cat) : ?>
								<span><?php echo $cat->name; ?> • </span>
							<?php endforeach; endif; ?>
							<?php echo get_the_date('', $latest_post->ID); ?>
						</div>
						<?php echo Helper::generate_header(array(
							'text'        => get_the_title($latest_post->ID),
							'tag'         => 'h'.strval($title_h_level+1),
							'class'       => 'latest-post__title heading h3'
						)); ?>
						<div class="latest-post__link-wrapper">
							<span class="latest-post__link">
								<i class="fa-solid fa-long-arrow-right"></i>
								<?php _e('Read more', THEME_NAME); ?>
							</span>
						</div>
					</a>
				</div>
				<div class="row__col row__col--md-5 latest-news__posts-column">
					<?php foreach($other_posts as $post) : ?>
						<?php
							$categories = get_the_terms($post->ID, 'news-categories');
						?>
						<a href="<?php echo get_permalink($post->ID); ?>" class="post" title="<?php echo get_the_title($post->ID); ?>">
							<div class="post__meta">
								<?php if($categories) : foreach($categories as $cat) : ?>
									<span><?php echo $cat->name; ?> • </span>
								<?php endforeach; endif; ?>
								<?php echo get_the_date('', $post->ID); ?>
							</div>
							<?php echo Helper::generate_header(array(
								'text'        => get_the_title($post->ID),
								'tag'         => 'h'.strval($title_h_level+1),
								'class'       => 'post__title heading h4'
							)); ?>
							<div class="post__link-wrapper">
								<span class="post__link">
									<i class="fa-solid fa-long-arrow-right"></i>
									<?php _e('Read more', THEME_NAME); ?>
								</span>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php if($link) : ?>
			<div class="latest-news__link-wrapper mobile">
				<a
					href="<?php echo $link['url']; ?>"
	                class="latest-news__link"
	                <?php if($link['target']) { echo 'target="_blank"'; } ?>
				>
					<?php echo $link['title']; ?><i class="fa-solid fa-arrow-right"></i>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>