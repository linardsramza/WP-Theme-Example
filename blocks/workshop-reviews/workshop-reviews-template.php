<?php

/**
 * Workshop reviews Block Template.
 */

$id = '';
$className = 'block workshop-reviews';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$title = Helper::generate_header($args['title_args']);
$reviews_count = count($args['reviews']);
$load_more_classes = '';
if($reviews_count > 2) {
	$load_more_classes .= ' show-sm';
}

if($reviews_count > 3) {
	$load_more_classes .= ' show-md';
}

if($reviews_count > 5) {
	$load_more_classes .= ' show-xl';
}

if($reviews_count > 0) { ?>

	<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<div class="container">
			<?php echo $title; ?>
		</div>
		<?php if(wp_is_mobile()) { ?>
			<div class="swiper review-slider">
				<div class="swiper-wrapper">
					<?php foreach($args['reviews'] as $review) { ?>
						<div class="swiper-slide">
							<?php get_template_part('blocks/workshop-reviews/template-parts/review','card', ['review' => $review]); ?>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php } else { ?>
			<div class="container">
				<div class="workshop-reviews__cards-wrapper">
					<?php foreach($args['reviews'] as $review) {
						get_template_part('blocks/workshop-reviews/template-parts/review','card', ['review' => $review]);
					} ?>
				</div>
			</div>
		<?php } ?>
		<div class="workshop-reviews__load-more">
			<p><?php _e('Reviews are taken from Google', THEME_NAME); ?></p>
			<?php if(!wp_is_mobile()) { ?>
				<button class="btn btn--primary load-more-btn <?php echo $load_more_classes; ?>"><?php _e('More reviews', THEME_NAME); ?></button>
			<?php } ?>
		</div>
	</section>

<?php }