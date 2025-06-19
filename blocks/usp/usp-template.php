<?php

/**
 * USP Block Template.
 */

$id = '';
$className = 'block usp';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$header = $args['header'];
$pretitle = $args['pretitle'];
$list = $args['list'];
$button = $args['button'];

$header = Helper::generate_header(array(
	'text'        => $args['header']['header']['text'],
	'tag'         => $args['header']['header']['level'],
	'class'       => $args['header']['header']['style'] . ' usp__title',
));

$pretitle = Helper::generate_header(array(
	'text'        => $pretitle,
	'tag'         => 'h5',
	'class'       => 'h5 usp__pretitle',
));

$button = Helper::generate_acf_link(array(
	'link' 	=> $button['link'],
	'class'	=> 'btn '. $button['btn_style']. ' '. $button['btn_size']
));

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<div class="usp__inner">
			<?php echo $pretitle; ?>
			<?php echo $header; ?>

			<?php if ($list) : ?>
				<div class="usp__list row">
					<?php foreach($list as $item) : 
						$icon = Helper::generate_image(array(
							'image' 		  => $item['icon'],
							'figure'          => true,
							'figure_class'    => 'usp__list-item--image',
						));
						$item_title = Helper::generate_header(array(
							'text'        => $item['title'],
							'tag'         => 'h5',
							'class'       => 'usp__list-item--title'
						));
						$item_content = Helper::generate_paragraph(array(
							'text'        => $item['content'],
							'class'       => 'usp__list-item--text'
						)); ?>
						<div class="usp__list-item row__col row__col--lg-3 row__col--md-6">
							<?php echo $icon; ?>
							<div class="usp__list-item--content">
								<?php echo $item_title; ?>
								<?php echo $item_content; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php echo $button; ?>
		</div>
	</div>
</section>