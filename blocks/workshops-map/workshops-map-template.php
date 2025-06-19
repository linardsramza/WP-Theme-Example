<?php

/**
 * Workshops map Block Template.
 */

$id = '';
$className = 'block workshops-map';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$intro = $args['intro'];
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">

		<?php if($intro) { ?>
			<div class="workshops-map__headline">
				<div class="row">
					<div class="row__col row__col--lg-8 row__col--md-10">
						<?php echo $intro;?>
					</div>
				</div>
			</div>
		<?php } ?>

		<div class="tabs">
			<div class="tabs__btn active" data-id="maps"><?php _e('Find via search', THEME_NAME); ?></div>
			<div class="tabs__btn" data-id="regions"><?php _e('Find by county', THEME_NAME); ?></div>
		</div>

		<div class="tabs__content">
			<div class="tabs__content--item active" id="maps">
				<?php get_template_part('blocks/workshops-map/template-parts/filters',''); ?>
				<?php get_template_part('blocks/workshops-map/template-parts/map','', ); ?>
			</div>
			<div class="tabs__content--item" id="regions">
				<?php get_template_part('blocks/workshops-map/template-parts/regions',''); ?>
			</div>
		</div>

	</div>
</section>