<?php

/**
 * Testimonials Block Template.
 */

$id = '';
$className = 'block testimonials';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$header = $args['header'];
$description = $args['description'];
$list = $args['list'];

$header = Helper::generate_header(array(
	'text'        => $args['header']['header']['text'],
	'tag'         => $args['header']['header']['level'],
	'class'       => $args['header']['header']['style'] . ' testimonials__title',
));

$description = Helper::generate_paragraph(array(
    'text'  => $description,
    'class' => 'testimonials__description'
));

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		
		<?php echo $header; ?>
		<?php echo $description; ?>

		<?php if ($list) : ?>
			<div class="testimonials__slider">
				<div class="swiper-wrapper">
					<?php foreach ($list as $item) : 
							$image = Helper::generate_image(array(
								'image'           => $item['image'],
								'image_size'      => 'full',
								'figure'          => true,
								'figure_class'    => 'testimonials__list-item--image',
							));
						?>
						<div class="swiper-slide">
							<div class="testimonials__list-item <?php echo $item['background_color']; ?>">
								<?php echo $image; ?>
								<div class="testimonials__list-item--content">
									<span class="testimonials__list-item--quote">
										<?php echo $item['quote']; ?>
									</span>
									<span class="testimonials__list-item--author">
										<?php echo $item['author']; ?>
									</span>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

	</div>
</section>