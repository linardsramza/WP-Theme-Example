<?php

/**
 * Cities close by Block.
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
$id = 'cities-close-by-' . $block['id'];
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
$button = get_field('button');

// Link card settings
$link_card_settings = get_field('link_cards_settings') ?? [];
$card_title = $link_card_settings['title'];
$card_description = $link_card_settings['description'];
$selected_city = get_field('selected_city');
$service = get_field('service_name', $post_id) ?? null;
$available_shortcode_list = ['[city_name]', '[city_workshop_count]'];
$close_by_distance = get_field('close_by_distance_block');
if(!$close_by_distance || $close_by_distance < 1) {
    $close_by_distance = get_field('close_by_distance', 'options') ?? 150;
}

// Get closest cities
$closes_city = get_field('closest_city_option') ?? 'default';
$closes_cities = [];
if($closes_city === 'default' && wp_get_post_parent_id() == 0) {
	$closes_cities = Helper::get_closest_cities($post_id, $close_by_distance);
} elseif($closes_city === 'default' && !is_null($service)) {
	$closes_cities = Helper::get_closest_cities_by_service($post_id, $service, $close_by_distance);
} elseif($selected_city) {
	$selected_city = get_posts([
		'post_type'      => 'cities',
		'post_status'    => 'publish',
		'title'          => $selected_city->name,
		'posts_per_page' => 1,
	]);
	$city_id = $selected_city[0]->ID ?? false;
	if($city_id) {
		$closes_cities = Helper::get_closest_cities($city_id, $close_by_distance);
	}
}

// Get closest cities link list
$link_cards = [];
foreach($closes_cities as $city) {
	$city_term_ID = get_field('city', $city['ID']);
	$workshop_count = get_term($city_term_ID)->count ?? '';
	$shortcode_value_list = [$city['city'], $workshop_count];

	// Get title
	if(empty($card_title)) {
		$link_card_settings['title'] = $city['city'];
	} else {
		$link_card_settings['title'] = str_replace($available_shortcode_list, $shortcode_value_list, $card_title);
	}

	// Get description
	if(!empty($card_description)) {
		$link_card_settings['description'] = str_replace($available_shortcode_list, $shortcode_value_list, $card_description);
	}

	// Add values to link card settings
	$link_card_settings['link'] = [
		'title'  => $city['city'],
		'url'    => get_permalink($city['ID']),
		'target' => ''
	];
	$link_cards[] = $link_card_settings;
}

// If you want to reuse this template part, $blockData is not neccessary.
if($link_cards) {
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
}

