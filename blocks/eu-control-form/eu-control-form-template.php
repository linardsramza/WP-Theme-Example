<?php

/**
 * EU control form Block Template.
 */

$id = '';
$className = 'block eu-control-form';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$header = $args['header'];
$title_args = array(
	'text'  => $header['text'],
	'tag'   => $header['level'] ?? 'h2',
	'class' => 'eu-control-form__title heading ' . $header['style'] ?? 'h2'
);
$description = $args['description'];
$image = $args['image'];

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<div class="eu-control-form__wrapper">
			<div class="eu-control-form__content">
				<?php echo Helper::generate_header($title_args); ?>
				<?php echo Helper::generate_paragraph(array(
					'text'  => $description,
					'class' => 'eu-control-form__description'
				)); ?>
				<div class="eu-control-form__registration-info">
					<div class="eu-control-form__message">
						<i class="icon fa fa-info-circle"></i>
						<div class="response"></div>
					</div>
				</div>
				<form method="POST" id="registration-number-form">
					<label for="registration-number"><?php _e('Registration number', THEME_NAME); ?></label>
					<input type="text" id="registration-number" placeholder="AB 12345">
					<input type="submit" class="btn btn--primary" value="<?php _e('Check', THEME_NAME); ?>">
				</form>
			</div>
			<?php if($image) : ?>
				<div class="eu-control-form__image" style="background-image: url(<?php echo $image['url']; ?>); "></div>
			<?php endif; ?>
		</div>
	</div>
</section>