<?php

/**
 * Services in city list Block Template.
 */

$id = '';
$className = 'block services-in-city-list link-cards';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$pre_title_args = $args['pre_title_args'];
$title_args = $args['title_args'];
$description = $args['description'];
$card_title_class = 'h4';
$card_settings = $args['cards_settings'];

$city_type = $args['city_type'];
if($city_type == 'current' && get_post_type() === 'cities') {
	$city_id = get_the_ID();
} else {
	$city_id = $args['selected_city'];
}

if(is_null($city_id)) {
	$services_list = [];
} else {
	$services_args = array(
		'post_type'      => 'cities',
		'post_parent'    => $city_id,
		'orderby'        => 'menu_order',
		'posts_per_page' => -1,
	);
	$services_list = get_posts($services_args);
}
if($services_list) { ?>
	<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<div class="container">
			<?php echo Helper::generate_header($pre_title_args); ?>
			<?php echo Helper::generate_header($title_args); ?>
			<?php echo Helper::generate_paragraph(array(
				'text'  => $description,
				'class' => 'services-in-city-list__description'
			)); ?>

			<?php if($services_list) : ?>
				<div class="row row--stretch">
					<?php foreach($services_list as $service) : 
						$service_id = $service->ID;
						$link = get_permalink($service_id);
						$service_name = get_field('service_name', $service_id);
						$title = $card_settings['title_template'];
						$title = str_replace("[service_name]", $service_name, $title);
						$title = str_replace("/", '/<wbr>', $title);
						if(empty($title)) {
							$title = $service->post_title;
						}
					?>
						<div class="row__col row__col--md-6 row__col--xl-3">
							<a
								href="<?php echo $link; ?>"
								class="card card--large <?php echo $card_settings['bg_color'] . ' '.  $card_settings['text_color']; ?>"
							>
								<div class="card__content">
									<?php echo Helper::generate_header(array(
										'text'        => $title,
										'tag'         => 'h'. $card_settings['h_level'],
										'class'       => 'card__title heading '. $card_title_class
									)); ?>
									<span class="card__arrow">
										<i class="fa-solid fa-arrow-right"></i>
									</span>
								</div>
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

		</div>
	</section>
<?php } elseif(is_admin()) { ?>
	<div><?php echo __("Selected city doesn't have any services", THEME_NAME); ?></div> 
<?php } ?>