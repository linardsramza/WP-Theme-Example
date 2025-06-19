<?php

/**
 * Block 404 Block Template.
 */

$id = '';
$className = 'block block-404';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$pretitle_args = $args['pretitle_args'];
$title_args = $args['title_args'];
$text = $args['text'];
$icon = $args['icon'];

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<div class="block-404__wrapper">
			<?php echo Helper::generate_image(array(
				'image' 		  => $icon,
				'figure'          => true,
				'figure_class'    => 'block-404__image',
			)); ?>
			<?php echo Helper::generate_header($pretitle_args); ?>
			<?php echo Helper::generate_header($title_args); ?>
			<?php echo Helper::generate_paragraph(array(
				'text'  => $text,
				'class' => 'block-404__text'
			)); ?>
		</div>
	</div>
</section>