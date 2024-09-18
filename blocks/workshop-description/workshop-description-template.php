<?php

/**
 * Workshop services Block Template.
 */

$id = '';
$className = 'block workshop-description';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$services = $args['services'];
$services_count = count($services);
?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<div class="row">
			<div class="row__col row__col--lg-6 workshop-description__services">
				<?php echo Helper::generate_header($args['pre_title_args']); ?>
				<?php echo Helper::generate_header($args['title_args']); ?>
			</div>
			<div class="row__col row__col--lg-6">
				<?php if($services) { ?>
					<ul class="block workshop-description__list">
						<?php foreach($services as $service) { ?>
							<li><?php echo $service; ?></li>
						<?php } ?>
					</ul>
				<?php } ?>
				<?php if($services_count > 4) {
                    $count_class = $services_count > 10 ? 'more-then-10' : 'more-then-4';
					echo '<span class="workshop-description__show-all '. $count_class .'">' . __('Show all services', THEME_NAME) . '<i class="fas fa-angle-down"></i></span>';
				} ?>
			</div>
		</div>
	</div>
</section>