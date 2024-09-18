<?php

/**
 * Services Block Template.
 */

$id = '';
$className = 'block services';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$layout = $args['layout'];
$pretitle = $args['pretitle']['header'];
$pretitle_args = array(
	'text'  => $pretitle['text'],
	'tag'   => $pretitle['level'] ?? 'h2',
	'class' => 'services__pretitle heading ' . $pretitle['style'] ?? 'h5'
);
$title = $args['title']['header'];
$title_args = array(
	'text'  => $title['text'],
	'tag'   => $title['level'] ?? 'h3',
	'class' => 'services__title heading ' . $title['style'] ?? 'h2'
);
$description = $args['description'];
$services = $args['services'];
$link = $args['link'];

if($layout == 'side-by-side') :
	$col_class = 'row__col--lg-6 row__col--xl-4';
else :
	$col_class = 'row__col--lg-6 row__col--xl-3';
endif;

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<div class="services__inner <?php echo 'services__inner--'. $layout; ?>">
			<div class="services__text">
				<div class="services__text-content">
					<?php echo Helper::generate_header($pretitle_args); ?>
					<?php echo Helper::generate_header($title_args); ?>
					<?php echo Helper::generate_paragraph(array(
						'text'  => $description,
						'class' => 'services__description'
					)); ?>
					<?php if($link) : ?>
						<div class="services__link-wrapper">
							<a
								href="<?php echo $link['url']; ?>"
								class="services__link services__link--desktop"
								<?php if($link['target']) { echo 'target="_blank"'; } ?>
							>
								<?php echo $link['title']; ?>
								<i class="fa-solid fa-long-arrow-right"></i>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if($services) : ?>
				<div class="services__list row row--stretch">
					<?php foreach($services as $service) : ?>
						<div class="service__column row__col <?php echo $col_class; ?>">
							<?php
								$icon = '';
								$title = $service['title'];
								$title_h_level = (int) filter_var($title['level'] ?? 'h3', FILTER_SANITIZE_NUMBER_INT);
								$content = $service['content'];
								$button = $service['button'];

                                $icon = Helper::generate_image(array(
									'image' 		  => $service['icon'],
									'figure'          => true,
									'figure_class'    => 'service__image',
								));
							?>
							<div class="service">
								<div class="service__content">
									<div class="service__header">
										<?php echo $icon; ?>
										<?php echo Helper::generate_header(array(
											'text'        => $title,
											'tag'         => 'h'.strval($title_h_level+1),
											'class'       => 'service__title heading h4'
										)); ?>
									</div>
									<?php if(!empty($content)) : ?>
										<div class="service__text">
											<?php echo $content; ?>
										</div>
									<?php endif; ?>
								</div>
								<?php if($button) : ?>
									<div class="service__button">
										<?php echo Helper::generate_acf_link(array(
											'link' 	=> $button['link'],
											'class'	=> 'btn '. $button['btn_style']. ' '. $button['btn_size']
										)); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if($layout == 'stacked' && count($services) > 6) : ?>
					<div class="services__show-more-btn">
						<a href="#" class="btn btn--primary btn--small"><?php _e('Show all services', THEME_NAME); ?></a>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php if($link) : ?>
			<div class="services__link-wrapper">
				<a
					href="<?php echo $link['url']; ?>"
					class="services__link services__link--mobile"
					<?php if($link['target']) { echo 'target="_blank"'; } ?>
				>
					<?php echo $link['title']; ?>
					<i class="fa-solid fa-long-arrow-right"></i>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>