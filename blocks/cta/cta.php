<?php

/**
 * Text columns Block.
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
$id = 'text-columns-' . $block['id'];
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
$title = get_field('title');
$background = get_field('background');
$text_color = get_field('text_color');
$text_columns = get_field('text_columns');
$container_width = get_field('container_width');
$column_spacing = get_field('column_spacing');
$column_alignment = get_field('column_alignment');
$inverse_columns_on_mobile = get_field('inverse_columns_on_mobile');

// If you want to reuse this template part, $blockData is not neccessary.
get_template_part(
	'blocks/cta/cta',
	'template',
	array(
		'blockData' => $blockData,
		'title' => $title,
		'background' => $background,
		'text_color' => $text_color,
		'text_columns' => $text_columns,
		'container_width' => $container_width,
		'column_spacing' => $column_spacing,
		'column_alignment' => $column_alignment,
		'inverse_columns_on_mobile' => $inverse_columns_on_mobile
	)
);