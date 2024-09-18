<?php

/**
 * Step by step Block Template.
 */

$id = '';
$className = 'block step-by-step';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$title_args = $args['title_args'];
$sub_title_args = $args['sub_title_args'];
$steps = $args['steps'];
$line_count = $args['line_count'];
$button = $args['button'];

$gap_class = 'steps--gap-large';
$step_width = 'step--wide';

if($steps) :
	if(count($steps) === 5 && $line_count == 1):
        $gap_class = 'steps--gap-small';
        $step_width = 'step--narrow';
    elseif(count($steps) === 4):
        $gap_class = 'steps--gap-medium';
        $step_width = 'step--medium';
    endif;
endif;

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<?php echo Helper::generate_header($title_args); ?>
		<?php echo Helper::generate_header($sub_title_args); ?>
		<?php if($steps) : ?>
			<div class="steps <?php echo $gap_class; ?>">
				<?php foreach($steps as $step) : ?>
					<?php
						$icon = $step['icon'];
						$title = $step['title'];
						$h_level = $step['title_h_level'];
						$text = $step['text'];
					?>
					<div class="step <?php echo $step_width; ?>">
						<?php echo Helper::generate_image(array(
							'image' 		  => $icon,
							'figure'          => true,
							'figure_class'    => 'step__image',
						)); ?>
						<?php echo Helper::generate_header(array(
							'text'        => $title,
							'tag'         => $h_level,
							'class'       => 'step__title'
						)); ?>
						<?php echo Helper::generate_paragraph(array(
							'text'        => $text,
							'class'       => 'step__text'
						)); ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php echo Helper::generate_acf_link(array(
			'link' 	=> $button['link'],
			'class'	=> 'btn '. $button['btn_style']. ' '. $button['btn_size']
		)); ?>
	</div>
</section>