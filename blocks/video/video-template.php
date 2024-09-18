<?php

/**
 * Video Block Template.
 */

$id = '';
$className = 'block video';

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
	'class' => 'video__pretitle heading h5'
);
$title = $args['title']['header'];
$title_args = array(
	'text'  => $title['text'],
	'tag'   => $title['level'] ?? 'h3',
	'class' => 'video__title heading ' . $title['style'] ?? 'h2'
);
$description = $args['description'];
$video = $args['video'];
$video_thumbnail = $args['video_thumbnail'];
$button = $args['button']['button'];

preg_match('/src="([^"]+)"/', $video, $src_matches);
preg_match('/data-src-defer="([^"]+)"/', $video, $data_src_matches);
$broken_video = empty($src_matches) && empty($data_src_matches);

if( $video && !$broken_video ) :
	preg_match('/src="([^"]+)"/', $video, $matches);
	$src = $matches[1] ?? null;

	if(empty($src)){
		preg_match('/data-src-defer="([^"]+)"/', $video, $matches);
		$src = $matches[1];
	}

	$params = array(
		'autoplay' => 0,
		'mute' => 0,
		'enablejsapi' => 1,
	);

	$new_src = add_query_arg($params, $src);
	$video = str_replace($src, $new_src, $video);
	$attributes = 'frameborder="0"';
	$video = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $video);
endif;

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<div class="row row--center <?php echo 'row--'. $layout; ?>">
			<div class="video__text-column row__col row__col--md-6 row__col--lg-5">
				<div class="video__text">
					<?php echo Helper::generate_header($pretitle_args); ?>
					<?php echo Helper::generate_header($title_args); ?>
					<?php echo Helper::generate_paragraph(array(
						'text'  => $description,
						'class' => 'video__description'
					)); ?>
					<?php echo Helper::generate_acf_link(array(
						'link' 	=> $button['link'],
						'class'	=> 'btn '. $button['btn_style']. ' '. $button['btn_size']
					)); ?>
				</div>
			</div>
			<div class="video__video-column row__col row__col--md-6 row__col--lg-7">
				<?php if(!empty($video)) : ?>
					<div class="video-wrapper">
						<?php echo $video; ?>
						<?php if($video_thumbnail) : ?>
							<?php echo Helper::generate_image(array(
								'image' 		  => $video_thumbnail,
								'figure'          => true,
								'figure_class'    => 'video__thumbnail',
							)); ?>
							<span class="video__play-icon">
								<?php echo Helper::load_svg(get_template_directory_uri() . '/dist/img/play-icon.svg'); ?>
							</span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>