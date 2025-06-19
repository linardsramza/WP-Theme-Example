<?php

/**
 * Werehouses region list Block.
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
$id = 'werehouses-region-list-' . $block['id'];
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

$region_taxonomy = 'warehouses-cities';
$regions = get_terms([
    'taxonomy' => $region_taxonomy,
    'hide_empty' => false,
	'orderby' => 'name',
    'order' => 'ASC'
]);

// If you want to reuse this template part, $blockData is not neccessary.
get_template_part(
	'blocks/werehouses-region-list/werehouses-region-list',
	'template',
	array(
		'blockData' => $blockData,
		'header' => $header,
		'description' => $description,
		'region_taxonomy' => $region_taxonomy,
		'regions' => $regions
	)
);