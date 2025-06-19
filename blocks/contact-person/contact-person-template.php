<?php

/**
 * Contact person Block Template.
 */

$id = '';
$className = 'block contact-person';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$header = $args['header'];
$pretitle = $args['pretitle'];
$description = $args['description'];
$text_box_background = $args['text_box_background'];
$text_box_text_color = $args['text_box_text_color'];

// Contact person
$name = $args['name'];
$position = $args['position'];
$phone = $args['phone'];
$email = $args['email'];
$image = $args['image'];
$background_style = '';

$header = Helper::generate_header(array(
	'text'        => $args['header']['header']['text'],
	'tag'         => $args['header']['header']['level'],
	'class'       => $args['header']['header']['style'] . ' contact-person__title',
));
$pretitle = Helper::generate_header(array(
	'text'        => $pretitle,
	'tag'         => 'h5',
	'class'       => 'h5' . ' contact-person__pretitle',
));

$description = Helper::generate_paragraph(array(
    'text'  => $description,
    'class' => 'contact-person__description'
));

$image = Helper::generate_image(array(
	'image'           => $image,
	'image_size'      => 'full',
	'figure'          => true,
	'figure_class'    => 'contact-person__image',
));

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<div class="contact-person__background <?php echo $text_box_background; ?> <?php echo $text_box_text_color; ?>">
			<div class="container container--narrow">
				
				<div class="contact-person__content">
					<?php echo $pretitle; ?>
					<?php echo $header; ?>
					<?php echo $description; ?>
				</div>

				<div class="contact-person__info">
					<?php 
					if (!empty($image)) : 
						echo $image;
					else : ?>
						<figure class="contact-person__image contact-person__image--empty"></figure>
					<?php endif; ?>
					<div class="contact-person__info--content">
						<span class="contact-person__name">
							<?php echo $name; ?>
						</span>
						<span class="contact-person__position">
							<?php echo $position; ?>
						</span>
						<?php if (!empty($phone)) : ?>
							<a href="tel:<?php echo $phone; ?>" class="contact-person__phone">
								<i class="fas fa-phone"></i>
								<?php echo $phone; ?>
							</a>
						<?php endif; ?>
						<?php if (!empty($email)) : ?>
							<a href="mailto:<?php echo $email; ?>" class="contact-person__email">
							<i class="fas fa-envelope"></i>
								<?php echo $email; ?>
							</a>
						<?php endif; ?>
					</div>
				</div>

			</div>
		</div>
	</div>
</section>