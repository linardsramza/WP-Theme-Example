<?php

/**
 * Workshop list Block.
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
$id = 'workshop-list-' . $block['id'];
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
	'block'           => $block,
	'content'         => $content,
	'is_preview'      => $is_preview,
	'post_id'         => $post_id,
	'id'              => $id,
	'className'       => $className,
);

$title = get_field('title');
$title_args = array(
	'text'  => $title['header']['text'],
	'tag'   => $title['header']['level'] ?? 'h3',
	'class' => 'heading ' . $title['header']['style'] ?? 'h2'
);
$intro = get_field('intro_text');
$workshops_to_display = get_field('workshops_to_display');
$workshop_list = [];

if($workshops_to_display === 'selected-workshop') {
	$workshop_list = get_field('select_workshops');
}

$page_id = get_the_ID();
$current_city = get_field('city', $page_id);
$have_city = !is_null($current_city) && !empty($current_city);
$service_name = get_field('service_name', $page_id) ?? '';

if($workshops_to_display === 'current-city' && $have_city) {
	$args = [
		'post_type' => 'workshops',
		'posts_per_page' => -1,
		'tax_query' => [
			[
				'taxonomy' => 'workshops-cities',
				'field' => 'term_id',
				'terms' => $current_city
			]
		]
	];
	$workshops = get_posts($args);
	foreach($workshops as $workshop) {
		$workshop_list[] = $workshop->ID;
	}
}

if($workshops_to_display === 'current-city-services') {

	$args = [
		'post_type' => 'workshops',
		'posts_per_page' => -1,
		'tax_query' => [
			'relation' => 'AND',
			[
				'taxonomy' => 'services',
				'field' => 'name',
				'terms' => $service_name,
			],
			[
				'taxonomy' => 'workshops-cities',
				'field' => 'term_id',
				'terms' => $current_city,
			]
		]
	];
	// Get the posts
	$workshops = get_posts( $args );
	foreach($workshops as $workshop) {
		$workshop_list[] = $workshop->ID;
	}
}

$workshops_title_settings = get_field('workshop_card_title_settings');
$service_code = get_field('booking_link_service_code') ?? false;

// If you want to reuse this template part, $blockData is not neccessary.
get_template_part(
	'blocks/workshop-list/workshop-list',
	'template',
	array(
		'blockData'       => $blockData,
		'title_args'      => $title_args,
		'intro'           => $intro,
		'workshops'       => $workshop_list,
		'workshops_title' => $workshops_title_settings,
        'service_code'    => $service_code,
	)
);