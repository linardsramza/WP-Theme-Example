<?php

/**
 * Werehouses region list Block Template.
 */

$id = '';
$className = 'block werehouses-region-list';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$header = $args['header'];
$description = $args['description'];
$regions = $args['regions'];
$region_taxonomy = $args['region_taxonomy'];

$title = Helper::generate_header(array(
	'text'        => $header['header']['text'],
	'tag'         => $header['header']['level'],
	'class'       => $header['header']['style'] . ' werehouses-region-list__title',
));
$description = Helper::generate_wysiwyg(array(
	'content'  => $description,
	'classes' => array('editor-content', 'werehouses-region-list__description')
));

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<?php if (!empty($title)) : ?>
		<div class="container">
			<div class="werehouses-region-list__headline">
				<div class="row">
					<div class="row__col row__col--lg-8 row__col--md-10">
						<?php echo $title; ?>
						<?php echo $description; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ($regions) : ?>

		<div class="werehouses-region-list__regions accordion">
			<?php foreach ($regions as $region) : 
				$region_id = $region->term_id;
				$region_name = $region->name;
				// $region_cities = get_field('city_pages', $region_taxonomy . '_' . $region_id);
				$werehouses_args = array(
					'post_type' => 'warehouses',
					'tax_query' => array(
						array(
						'taxonomy' => $region_taxonomy,
						'field' => 'term_id',
						'terms' => $region_id,
						'orderby' => 'title',
						'order' => 'ASC' 
						)
					),
					'orderby' => 'title',
					'order' => 'ASC' 
				);
				$werehouses_query = new WP_Query( $werehouses_args );
				
			?>
				<?php if ($werehouses_query->have_posts()) : ?>
					<div class="accordion__item">
						<div class="accordion__title" name="<?php echo $region_name; ?>">
							<h4 class="title"><?php echo $region_name; ?></h4>
							<i class="fas fa-plus accordion__title--plus"></i>
							<i class="fas fa-minus accordion__title--minus"></i>
						</div>
						<div class="accordion__description">
							<div class="accordion__description--inner">
								<?php while ($werehouses_query->have_posts()) : $werehouses_query->the_post();

										get_template_part( 'blocks/werehouses-region-list/template-parts/loop', 'werehouse' );


									endwhile; 
									wp_reset_postdata();
								?>
							</div>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

	<?php endif; ?>

</section>