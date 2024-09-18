<?php

/**
 * Image text overlay Block Template.
 */

$id = '';
$className = 'block image-text-overlay';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$header = $args['header'];
$description = $args['description'];
$image = $args['image'];
$has_dotted_line = $args['has_dotted_line'];
$text_box_direction = $args['text_box_direction'];
$text_box_background = $args['text_box_background'];
$text_box_text_color = $args['text_box_text_color'];
$button = $args['button'];
$background_position = $args['background_position'];
$background_style = '';

if ($text_box_direction) :
	$text_box_style = ' image-text-overlay__background--swap';
endif;

if ($has_dotted_line) :
	$dotted_style = ' image-text-overlay__background--dotted-line';
endif;

$header = Helper::generate_header(array(
	'text'        => $args['header']['header']['text'],
	'tag'         => $args['header']['header']['level'],
	'class'       => $args['header']['header']['style'] . ' image-text-overlay__title',
));

$description = Helper::generate_wysiwyg(array(
	'content'  => $description,
	'classes' => array('editor-content', 'image-text-overlay__description')
));

$button = Helper::generate_acf_link(array(
	'link' 	=> $button['link'],
	'class'	=> 'btn '. $button['btn_style']. ' '. $button['btn_size']
));

if ($image) :
	$background_style = 'background-image: url('. $image .')';
endif;

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<div class="
			image-text-overlay__background 
			<?php echo $has_dotted_line ? $dotted_style : ''; ?> 
			<?php echo $text_box_direction ? $text_box_style : ''; ?>
			<?php echo $image ? 'background--' . $background_position : ''; ?>
			" 
			<?php echo $image ? 'style="'. $background_style .'"' : ''; ?>
		>
			<div class="image-text-overlay__content <?php echo $text_box_background; ?> <?php echo $text_box_text_color; ?>">
				<?php echo $header; ?>
				<?php echo $description; ?>
				<?php echo $button; ?>
			</div>
		</div>
	</div>
</section>