<?php

/**
 * Hero Block Template.
 */

$id = '';
$className = 'block hero';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$header = $args['header'];
$description = $args['description'];
$label = $args['label'];
$image = $args['image'];
$mobile_image = $args['mobile_image'];
$video = $args['video'];
$list = $args['list'];
$button = $args['button'];
$has_dotted_line = $args['has_dotted_line'];
$has_search_field = $args['has_search_field'];
$workshops_search_page = $args['workshops_search_page'];
$has_hexagon = $args['has_hexagon'];
$hexagon_placement = $args['hexagon_placement'];
$has_image_field = $args['has_image_field'];
$image_position = $args['image_position'];
$image_position_mob = $args['image_position_mob'];
$text_box_background = $args['text_box_background'];
$text_box_text_color = $args['text_box_text_color'];
$hexagon_background = $args['hexagon_background'];
$hexagon_text_color = $args['hexagon_text_color'];
$enable_animations = $args['enable_animations'];
$crop_video = $args['crop_video'];
$background_position = $args['background_position'];

$className .= !$list && !$has_search_field && !$has_image_field ? ' hero--text-long' : '';
$className .= !$list && !$has_search_field && $has_image_field ? ' hero--description-full' : '';
$className .= ' ' . $text_box_background . ' ' . $text_box_text_color;
$className .= $enable_animations ? ' hero--animations' : '';
if($video || $has_image_field) {
    $className .= ' hero--image-' . $image_position;
    $className .= ' hero--image-mob-' . $image_position_mob;
}

if ($has_dotted_line && !$has_image_field) :
	$className .= ' hero--dotted-line';
endif;

if ($video && $has_image_field && $crop_video) :
	$className .= ' hero--video-full';
endif;

$header = Helper::generate_header(array(
	'text'        => $args['header']['header']['text'],
	'tag'         => $args['header']['header']['level'],
	'class'       => $args['header']['header']['style'] . ' hero__title',
));

$description = Helper::generate_wysiwyg(array(
	'content'  => $description,
	'classes' => array('editor-content', 'hero__description')
));

if ($button) :
	$button = Helper::generate_acf_link(array(
		'link' 	=> $button['button']['link'],
		'class'	=> 'btn '. $button['button']['btn_style']. ' btn--hero '. $button['button']['btn_size']
	));
endif;

?>

<?php if ($image) : ?>
	<style>
		<?php echo '#' . $id . ' .hero__image'; ?> {
			background-image: url(<?php echo $image['url']; ?>);
		}
		<?php if ($mobile_image) : ?>
			@media screen and (max-width: 768px) {
				<?php echo '#' . $id . ' .hero__image'; ?> {
					background-image: url(<?php echo $mobile_image['url']; ?>);
				}
			}
		<?php endif; ?>
	</style>
<?php endif; ?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

	<div class="hero__content <?php echo $text_box_background; ?> <?php echo $text_box_text_color; ?>">
		<div class="container <?php echo $has_image_field ? 'container--shortened' : ''; ?>">
			<div class="hero__content--header <?php echo $button ? 'hero__content--mb' : ''; ?>">
				<?php echo $header; ?>
				<?php echo $description; ?>
				<?php if ($button) :
					echo $button;
				endif; ?>
			</div>

			<?php if ($list && $has_image_field) : ?>
				<ul class="check-list">
					<?php foreach($list as $item) : ?>
						<li><?php echo $item['title']; ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			
			<?php if ($has_search_field && $has_image_field && $workshops_search_page) : ?>
				<div class="hero__search">
					<form method="get" action="<?php echo get_the_permalink($workshops_search_page); ?>">
						<input type="text" name="zip-code" placeholder="<?php _e('Search by zip code', 'theme'); ?>" class="hero__search-input">
						<input type="submit" value="<?php _e('Find workshop', 'theme'); ?>" class="btn btn--primary">
					</form>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ($video && $has_image_field) : ?>
	<div class="hero__video-wrapper <?php echo $has_dotted_line ? 'hero__video-wrapper--dotted-line' : ''; ?>">
		<video autoplay loop muted playsinline id="hero-video">
			<source src="<?php echo $video['url']; ?>#t=0.001" type='video/mp4'>
		</video>
		<?php if ($label && $has_hexagon) : ?>
			<div class="hero__label hero__label--<?php echo $hexagon_placement; ?> <?php echo $hexagon_background; ?> <?php echo $hexagon_text_color; ?>">
				<span><?php echo $label; ?></span>
			</div>
			<svg style="visibility: hidden; position: absolute;" width="0" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1">
			<defs>
					<filter id="round">
						<feGaussianBlur in="SourceGraphic" stdDeviation="5" result="blur" />    
						<feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
						<feComposite in="SourceGraphic" in2="goo" operator="atop"/>
					</filter>
				</defs>
			</svg>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	
	<?php if ($has_image_field && !$video) : ?>
		<div class="hero__image background--<?php echo $background_position; ?> <?php echo $video ? 'hero__image--video' : ''; ?> <?php echo $has_dotted_line ? 'hero__image--dotted-line' : ''; ?>">
			<?php if ($label && $has_hexagon) : ?>
				<div class="hero__label hero__label--<?php echo $hexagon_placement; ?> <?php echo $hexagon_background; ?> <?php echo $hexagon_text_color; ?>">
					<span><?php echo $label; ?></span>
				</div>
				<svg style="visibility: hidden; position: absolute;" width="0" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1">
				<defs>
						<filter id="round">
							<feGaussianBlur in="SourceGraphic" stdDeviation="5" result="blur" />    
							<feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
							<feComposite in="SourceGraphic" in2="goo" operator="atop"/>
						</filter>
					</defs>
				</svg>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	
</section>