<?php

/**
 * Workshop services Block.
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
$id = 'workshop-description-' . $block['id'];
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
$workshop_id = get_the_ID();
$pre_title = get_field('pre_title') ?? '';
$pre_title_args = [
	'text' => isset($pre_title['text']) ? $pre_title['text'] : '',
	'tag' => isset($pre_title['level']) ? $pre_title['level'] : '',
	'class' => 'pre-title ' . isset($pre_title['style']) ? $pre_title['style'] : '',
];

$title = get_field('title') ?? '';
$title_args = [
	'text' => isset($title['text']) ? $title['text'] : '',
	'tag' => isset($title['level']) ? $title['level'] : '',
	'class' => 'title ' . isset($title['style']) ? $title['style'] : '',
];

$services_terms = wp_get_post_terms($workshop_id, 'services');
$services = [];
foreach($services_terms as $service) {
	$services[] = $service->name;
}


// If you want to reuse this template part, $blockData is not neccessary.
get_template_part(
	'blocks/workshop-description/workshop-description',
	'template',
	array(
		'blockData' => $blockData,
		'pre_title_args' => $pre_title_args,
		'title_args' => $title_args,
		'services' => $services
	)
);