<?php

/**
 * Warehouse hero Block Template.
 */

$id = '';
$className = 'block warehouse-hero wh';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<div class="row">
			<div class="row__col row__col--lg-9">
				<?php get_template_part(
					'blocks/warehouse-hero/template-parts/info-card',
					'',
					[
						'id' => $id,
						'bg_img' => $args['bg_img'],
						'bg_img_mob' => $args['bg_img_mob'],
						'bg_filter' => $args['bg_filter'],
						'title' => $args['title'],
						'title_lvl' => $args['title_lvl'],
						'address' => $args['address'],
						'phone' => $args['phone'],
						'email' => $args['email'],
					]
				); ?>
			</div>
			<div class="row__col row__col--lg-3">
				<?php get_template_part(
					'blocks/workshop-hero/template-parts/working-hours',
					'',
					[
						'working_hours' => $args['working_hours'],
					]
				); ?>
				<?php get_template_part(
					'blocks/workshop-hero/template-parts/map',
					'',
					[
						'longitude' => $args['longitude'],
						'latitude' => $args['latitude'],
					]
				); ?>
			</div>
		</row>
	</div>
</section>