<?php

/**
 * FAQ Block Template.
 */

$id = '';
$className = 'block faq';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.

$title = Helper::generate_header(array(
	'text'        => $args['header']['header']['text'],
	'tag'         => $args['header']['header']['level'],
	'class'       => $args['header']['header']['style'] . ' section__title',
));
$questions = $args['questions'];
$load_more_button = $args['load_more_button'];

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">
		<?php echo $title; ?>

		<?php if ($questions) : 
			$questions_per_view = $args['questions_per_view'];
			?>
			<div class="accordion" data-accordion>

				<?php foreach($questions as $key => $item) : 
					$answer = Helper::generate_wysiwyg(array(
						'content'  => $item['answer'],
						'classes' => array('editor-content', 'accordion__description')
					));
					?>
					<div class="accordion__item <?php echo $questions_per_view <= $key &&  $questions_per_view >= 3  ? 'accordion__item--hidden' : ''; ?>" data-accordion-item>
						<div class="accordion__title" name="<?= $item['question']; ?>">
							<h4 class="title"><?= $item['question']; ?></h4>
							<i class="fas fa-plus accordion__title--plus"></i>
							<i class="fas fa-minus accordion__title--minus"></i>
						</div>
						<?php echo $answer; ?>				
					</div>
				<?php endforeach; ?>

			</div>

			<?php if ($load_more_button) : ?>
				<div class="accordion__more">
					<?php _e('Show more questions', 'theme'); ?>
				</div>
			<?php endif; ?>
			
		<?php endif; ?>

	</div>
</section>