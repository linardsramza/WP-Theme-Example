<?php

/**
 * Text columns Block Template.
 */

$id = '';
$className = 'block text-columns';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$title = $args['title'] ?? null;
$title_args = '';
if(!is_null($title)) {
	$title_args = [
		'text'  => $title['header']['text'] ?? '',
		'tag'   => $title['header']['level'] ?? 'h2',
		'class' => $title['header']['style'] ?? 'h2' . ' text-columns__title heading'
	];
}
$background = $args['background'] ?? 'none';
$text_color = $args['text_color'] ?? 'dark';
$text_columns = $args['text_columns'];
$container_width = $args['container_width'] ?? 'regular';
$container = $container_width == 'regular' ? 'container--narrow' : '';
$column_spacing = $args['column_spacing'] ?? 'regular';
$column_alignment = $args['column_alignment'] ?? 'top';
$inverse_mobile = $args['inverse_columns_on_mobile'] ?? false;

if($text_columns) :
	switch (count($text_columns)) :
	    case 1:
	        $col_class = 'row__col--lg-12';
	        break;
	    default:
	        $col_class = 'row__col--lg-6';
	        break;
	endswitch;
endif;

if($column_spacing) :
	switch ($column_spacing) :
		case 'medium':
	        $gutter_class = 'row--medium-gutter';
	        break;
	    case 'wide':
	        $gutter_class = 'row--wide-gutter';
	        break;
	    default:
	        $gutter_class = '';
	        break;
	endswitch;
endif;
?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<?php if(!empty($title_args['text'])) : ?>
			<div class="text-columns__title-wrapper <?php echo $background == 'none' ? $container : ''; ?>">
				<?php echo Helper::generate_header($title_args); ?>
			</div>
		<?php endif; ?>
		<div class="text-columns__wrapper <?php echo $background; ?> <?php echo $text_color; ?>">
			<div class="container <?php echo $container; ?>">
				<div
					class="
						row
						<?php echo $gutter_class ?? ''; ?>
						<?php if($column_alignment == 'middle'){ echo 'row--center'; }; ?>
						<?php if($inverse_mobile){ echo 'row--inverse-mobile'; }; ?>
					"
				>
					<?php if($text_columns) : foreach($text_columns as $column) : ?>
						<div class="row__col <?php echo $col_class; ?>">
							<div class="content editor-content">
								<?php echo $column['text']; ?>
							</div>
							<?php if($column['buttons']) : ?>
								<div class="text-columns__buttons">
									<?php foreach($column['buttons'] as $btn) :
										$button = $btn['button']['button'];

										$link = $button['link'];
										$class = 'btn '. $button['btn_style']. ' '. $button['btn_size'];

										if($link && substr($link['url'], 0, 3) == 'tel') {
											$class .= ' phone-link';
										} elseif($link && substr($link['url'], 0, 6) == 'mailto') {
											$class .= ' email-link';
										}

										echo Helper::generate_acf_link(array(
											'link' 	=> $link,
											'class'	=> $class
										));
									endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>