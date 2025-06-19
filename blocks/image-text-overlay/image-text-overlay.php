<?php

/**
 * Image text overlay Block.
 *
 * @param	 array $block The block settings and attributes.
 * @param	 string $content The block inner HTML (empty).
 * @param	 bool $is_preview True during AJAX preview.
 * @param	 (int|string) $post_id The post ID this block is saved to.
 */

if( isset( $block['data']['preview_image_help'] )  ) :
	echo Gutenberg::get_preview_image( $block['data']['preview_image_help'], $block['name'] );
	return;
endif;

// Create id attribute allowing for custom 'anchor' value.
$id = 'image-text-overlay-' . $block['id'];
if (!empty($block['anchor'])) :
	$id = $block['anchor'];
endif;

// Create class attribute allowing for custom 'className' and 'align' values.
$className = '';
if (!empty($block['className'])) :
	$className .= ' ' . $block['className'];
endif;
if (!empty($block['align'])) :
	$className .= ' align' . $block['align'];
endif;

// Add spacing classes
$margin_top = get_field('margin_top');
$className .= ' ' . !empty($margin_top) ? 'mt-' . $margin_top : 'mt-md';
$margin_bottom = get_field('margin_bottom');
$className .= !empty($margin_bottom) ? ' mb-' . $margin_bottom : 'mb-md';

// Collect all block data
$blockData = array(
	'block'      => $block,
	'content'    => $content,
	'is_preview' => $is_preview,
	'post_id'    => $post_id,
	'id'         => $id,
	'className'  => $className
);

// Load values and assing defaults.
$header = get_field('header');
$description = get_field('description');
$image = get_field('image');
$has_dotted_line = get_field('has_dotted_line');
$text_box_direction = get_field('text_box_direction');
$text_box_background = get_field('text_box_background') ? get_field('text_box_background') : 'bg-primary';
$text_box_text_color = isset(get_field('text_box_text_color')['text_color']) ? get_field('text_box_text_color')['text_color'] : 'text-white';
$button = get_field('button');
$background_position = get_field('background_position') ?? 'center';

// If you want to reuse this template part, $blockData is not neccessary.
get_template_part(
	'blocks/image-text-overlay/image-text-overlay',
	'template',
	array(
		'blockData' => $blockData,
		'header' => $header,
		'description' => $description,
		'image' => $image,
		'has_dotted_line' => $has_dotted_line,
		'text_box_direction' => $text_box_direction,
		'text_box_background' => $text_box_background,
		'text_box_text_color' => $text_box_text_color,
		'button' => $button['button'],
		'background_position' => $background_position,
	)
);