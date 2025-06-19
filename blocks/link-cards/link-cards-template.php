<?php

/**
 * Link cards Block Template.
 */

$id = '';
$className = 'block link-cards';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$pretitle_args = $args['pretitle_args'];
$title_args = $args['title_args'];
$description = $args['description'];
$link_cards = $args['link_cards'];
$button = $args['button']['button'];

if($link_cards) :
	switch (count($link_cards)) :
	    case 2:
	        $col_class = 'row__col--md-6';
	        $title_class = 'h3';
	        $card_size_class = 'card--large';
	        break;
	    case 3:
	        $col_class = 'row__col--md-6 row__col--xl-4';
	        $title_class = 'h3';
	        $card_size_class = 'card--large';
	        break;
	    case 4:
	        $col_class = 'row__col--md-6 row__col--xl-3';
	        $title_class = 'h4';
	        $card_size_class = 'card--small';
	        break;
	    default:
	        $col_class = 'row__col--md-6';
	        $title_class = 'h3';
	        $card_size_class = 'card--large';
	        break;
	endswitch;
endif;

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<?php echo Helper::generate_header($pretitle_args); ?>
		<?php echo Helper::generate_header($title_args); ?>
		<?php echo Helper::generate_paragraph(array(
			'text'  => $description,
			'class' => 'link-cards__description'
		)); ?>
		<?php if($link_cards) : ?>
			<div class="row row--stretch">
				<?php foreach($link_cards as $card) : ?>
					<?php
						$link = $card['link'];
						$background_image = $card['background_image'];
						$background_filter = $card['background_filter'];
						$background_position = $card['background_position'] ?? 'center';
						$background_color = $card['background_color'];
						$title = $card['title'];
						$title_h_level = (int) filter_var($args['title_h_level'], FILTER_SANITIZE_NUMBER_INT);
						$description = $card['description'] ?? '';
						$text_color = $card['text_color'];
						$certification_image = $card['certification_image'] ?? [];
					?>
					<div class="row__col <?php echo $col_class; ?>">
						<a
							href="<?php echo $link['url'] ?? '#'; ?>"
                            class="card <?php echo $background_color. ' '.  $text_color. ' '.  $card_size_class; if($background_image){ echo ' card--has-image background--' . $background_position; } if($background_filter){ echo ' card--has-filter'; } ?>"
                            <?php if($link && $link['target']) { echo 'target="_blank"'; } ?>
                            <?php if($background_image) : ?>
                            	style="background-image: url(<?php echo $background_image['url']; ?>);"
                            <?php endif; ?>
						>
							<div class="card__content">
								<?php echo Helper::generate_header(array(
									'text'        => $title,
									'tag'         => 'h'.strval($title_h_level+1),
									'class'       => 'card__title heading '. $title_class
								)); ?>
								<span class="card__arrow">
									<i class="fa-solid fa-arrow-right"></i>
								</span>
							</div>
							<?php echo Helper::generate_image(array(
								'image'           => $certification_image,
								'image_size'      => 'link-card-certification-image',
								'class'           => '',
								'figure'          => true,
								'figure_class'    => 'card__certification-image'
							)); ?>
							<?php if($description) {
								echo "<p class='card__description'>{$description}</p>";
							} ?>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if($button) : ?>
			<div class="link-cards__button">
				<?php echo Helper::generate_acf_link(array(
					'link' 	=> $button['link'],
					'class'	=> 'btn '. $button['btn_style']. ' '. $button['btn_size']
				)); ?>
			</div>
		<?php endif; ?>
	</div>
</section>