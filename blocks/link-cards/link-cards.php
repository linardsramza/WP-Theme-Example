<?php

/**
 * Link cards Block.
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
$id = 'link-cards-' . $block['id'];
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
$pretitle = get_field('pretitle');
$pretitle_args = array(
	'text'  => $pretitle['header']['text'],
	'tag'   => $pretitle['header']['level'] ?? 'h2',
	'class' => 'link-cards__pretitle heading ' . $pretitle['header']['style'] ?? 'h5'
);
$title = get_field('title');
$title_args = array(
	'text'  => $title['header']['text'],
	'tag'   => $title['header']['level'] ?? 'h3',
	'class' => 'link-cards__title heading ' . $title['header']['style'] ?? 'h2'
);
$description = get_field('description');
$link_cards = get_field('link_cards');
$button = get_field('button');

// If you want to reuse this template part, $blockData is not neccessary.
get_template_part(
	'blocks/link-cards/link-cards',
	'template',
	array(
		'blockData' => $blockData,
		'pretitle_args' => $pretitle_args,
		'title_args' => $title_args,
		'title_h_level' => $title['header']['level'] ?? 'h3',
		'description' => $description,
		'link_cards' => $link_cards,
		'button' => $button
	)
);