<?php

/**
 * Warehouse hero Block.
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
$id = 'warehouse-hero-' . $block['id'];
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

// Block fields
$bg_img = get_field('info_card_bg_img');
$bg_img_mob = get_field('info_card_bg_img_mob');
$bg_filter = get_field('add_background_filter');

// Warehouse fields
$w_id = get_the_ID();
$title = get_the_title();
$title_lvl = isset(get_field('h_level')['level']) ? get_field('h_level')['level'] : 'h1';

$address = '';
$address_info = get_field('workshop_address', $w_id);
if($address_info) {
	$address .= isset($address_info['address']) ? $address_info['address'] . ', ' : '';
	$address .= isset($address_info['zip_code']) ? $address_info['zip_code'] . ' ' : '';
	$address .= isset($address_info['city']) ? $address_info['city'] : '';
}
$longitude = isset($address_info['longitude']) ? $address_info['longitude'] : '';
$latitude = isset($address_info['latitude']) ? $address_info['latitude'] : '';

$contact_info = get_field('contact_info', $w_id);
$phone = isset($contact_info['phone_number']) ? $contact_info['phone_number'] : '';
$email = isset($contact_info['email']) ? $contact_info['email'] : '';

$working_hours = get_field('working_hours', $w_id);

// If you want to reuse this template part, $blockData is not neccessary.
get_template_part(
	'blocks/warehouse-hero/warehouse-hero',
	'template',
	array(
		'blockData' => $blockData,
		'bg_img' => $bg_img,
		'bg_img_mob' => $bg_img_mob,
		'bg_filter' => $bg_filter,
		'title' => $title,
		'title_lvl' => $title_lvl,
		'address' => $address,
		'longitude' => $longitude,
		'latitude' => $latitude,
		'phone' => $phone,
		'email' => $email,
		'working_hours' => $working_hours,
	)
);