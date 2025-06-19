<?php  
$title = get_field('subscribe_title', 'options');
$description = get_field('subscribe_description', 'options');
$form = get_field('form', 'options');

$title = Helper::generate_header(array(
	'text'        => $title,
	'tag'         => 'h4',
	'class'       => 'h4 subscribe__title',
));

$description = Helper::generate_paragraph(array(
	'text'  => $description,
	'class' => 'subscribe__description'
));

?>

<div class="subscribe">
    <?php echo $title; ?>
    <?php echo $description; ?>
    <?php gravity_form( $form, false, false, false, '', false ); ?>
</div>
