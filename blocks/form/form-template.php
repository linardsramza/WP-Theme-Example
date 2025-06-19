<?php

/**
 * Form Block Template.
 */

$id = '';
$className = 'block form';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$pretitle = $args['pretitle'];
$pretitle_args = array(
	'text'  => $pretitle['header']['text'],
	'tag'   => $pretitle['header']['level'] ?? 'h2',
	'class' => 'form__pretitle heading ' . $pretitle['header']['style'] ?? 'h5'
);
$title = $args['title'];
$title_args = array(
	'text'  => $title['header']['text'],
	'tag'   => $title['header']['level'] ?? 'h3',
	'class' => 'form__title heading ' . $title['header']['style'] ?? 'h2'
);
$description = $args['description'];
$link = $args['link'];
$form = $args['form'];

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<div class="row">
			<div class="row__col row__col--md-4">
				<div class="form__text-wrapper">
					<?php echo Helper::generate_header($pretitle_args); ?>
					<?php echo Helper::generate_header($title_args); ?>
					<?php echo Helper::generate_paragraph(array(
						'text'  => $description,
						'class' => 'form__description'
					)); ?>
					<?php if($link) : ?>
					<a
						href="<?php echo $link['url']; ?>"
		                class="form__link"
		                <?php if($link['target']) { echo 'target="_blank"'; } ?>
					>
						<?php echo $link['title']; ?><i class="fa-solid fa-arrow-right"></i>
					</a>
					<?php endif; ?>
				</div>
			</div>
			<div class="row__col row__col--md-8">
				<span class="form__search-field-placeholder"><?php _e('Search', THEME_NAME); ?></span>
				<?php if(function_exists('gravity_form')) {
					gravity_form($form, false, false, false, false, true, 0, true);
				} ?>
			</div>
		</div>
	</div>
</section>