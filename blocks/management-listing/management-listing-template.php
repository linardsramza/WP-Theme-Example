<?php

/**
 * Management listing Block Template.
 */

$id = '';
$className = 'block management-listing';

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
	'class'       => $args['header']['header']['style'] . ' management-listing__title',
));

$description = Helper::generate_paragraph(array(
    'text'  => $description,
    'class' => 'management-listing__description'
));

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<?php echo $header; ?>
		<?php echo $description; ?>

		<?php if ($list) : ?>
			<div class="row management-listing__list">
				<?php foreach ($list as $item) : 
						$image = Helper::generate_image(array(
							'image'           => $item['image'],
							'image_size'      => 'full',
							'figure'          => true,
							'figure_class'    => 'management-listing__list-item--image',
						));
					?>
					<div class="row__col row__col--lg-6">
						<div class="management-listing__list-item">
							<?php echo $image; ?>
							<div class="management-listing__list-item--content">
								<span class="management-listing__list-item--name">
									<?php echo $item['name']; ?>
								</span>
								<span class="management-listing__list-item--position">
									<?php echo $item['position']; ?>
								</span>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>