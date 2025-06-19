<?php

/**
 * Services in city list.
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
$id = 'services-in-city-list-' . $block['id'];
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
$pre_title = get_field('pre_title');
$pre_title_args = array(
	'text'  => $pre_title['header']['text'],
	'tag'   => $pre_title['header']['level'] ?? 'h2',
	'class' => 'services-in-city-list__pre_title heading ' . $pre_title['header']['style'] ?? 'h5'
);
$title = get_field('title');
$h_tag = $title['header']['level'];
$title_args = array(
	'text'  => $title['header']['text'],
	'tag'   => $h_tag ?? 'h3',
	'class' => 'services-in-city-list__title heading ' . $title['header']['style'] ?? 'h2'
);
$description = get_field('description');

$cards_settings = [
	'h_level'    => (int) filter_var($h_tag, FILTER_SANITIZE_NUMBER_INT) + 1,
	'bg_color'   => get_field('card_bg_color'),
	'text_color' => get_field('card_text_color'),
	'title_template' => get_field('heading_title_template'),
];

$city_type = get_field('city_type');
$selected_city = get_field('city') ? get_field('city') : null;

// If you want to reuse this template part, $blockData is not neccessary.
get_template_part(
	'blocks/services-in-city-list/services-in-city-list',
	'template',
	array(
		'blockData' => $blockData,
		'pre_title_args' => $pre_title_args,
		'title_args' => $title_args,
		'title_h_level' => $h_tag ?? 'h3',
		'description' => $description,
		'city_type' => $city_type,
		'selected_city' => $selected_city,
		'cards_settings'=> $cards_settings
	)
);