<?php

/**
 * Hero Block.
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
$id = 'hero-' . $block['id'];
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
$container_spacing = get_field('container_spacing') ?? 'wide';
$className .= ' hero--' . $container_spacing . ' ';
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
$label = get_field('label_text');
$image = get_field('image');
$mobile_image = get_field('mobile_image');
$video = get_field('video');
$list = get_field('list');

$has_dotted_line = get_field('has_dotted_line');
$has_search_field = get_field('has_search_field');
$workshops_search_page = get_field('workshops_map_page', 'options');
$has_hexagon = get_field('has_hexagon');
$hexagon_placement = get_field('hexagon_placement') ?? 'left';
$has_image_field = get_field('has_image_field');
$enable_animations = get_field('enable_animations');
$crop_video = get_field('crop_video');

$image_position = get_field('block_layout') ?? 'right';
$image_position_mob = get_field('block_layout_mobile') ?? 'top';
$text_box_background = isset(get_field('background_color_clone')['background_color']) ? get_field('background_color_clone')['background_color'] : 'bg-primary';
$text_box_text_color = isset(get_field('text_color_clone')['text_color']) ? get_field('text_color_clone')['text_color'] : 'text-white';
$hexagon_background = !empty(get_field('hexagon_background')) ? get_field('hexagon_background') : 'bg-primary';
$hexagon_text_color = !empty(get_field('hexagon_text_color')) ? get_field('hexagon_text_color') : 'text-white';
$button = get_field('button');
$background_position = get_field('background_position') ?? 'center';

// If you want to reuse this template part, $blockData is not neccessary.
get_template_part(
	'blocks/hero/hero',
	'template',
	array(
		'blockData' => $blockData,
		'header' => $header,
		'description' => $description,
		'label' => $label,
		'image' => $image,
		'mobile_image' => $mobile_image,
		'video' => $video,
		'list' => $list,
		'button' => $button,
		'has_dotted_line' => $has_dotted_line,
		'has_search_field' => $has_search_field,
		'workshops_search_page' => $workshops_search_page,
		'has_hexagon' => $has_hexagon,
		'hexagon_placement' => $hexagon_placement,
		'has_image_field' => $has_image_field,
        'image_position' => $image_position,
        'image_position_mob' => $image_position_mob,
		'text_box_background' => $text_box_background,
		'text_box_text_color' => $text_box_text_color,
		'hexagon_background' => $hexagon_background,
		'hexagon_text_color' => $hexagon_text_color,
		'container_spacing' => $container_spacing,
		'button' => $button,
		'enable_animations' => $enable_animations,
		'crop_video' => $crop_video,
		'background_position' => $background_position,
	)
);

// Add breadcrumbs
$display_breadcrumbs = get_field('display_breadcrumbs', get_the_ID()) ?? true;
if($display_breadcrumbs){
	get_template_part('templates/breadcrumbs');
}