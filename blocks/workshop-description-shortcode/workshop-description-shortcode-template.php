<?php

/**
 * Workshop description Block Template.
 */

$id = '';
$className = 'block workshop-description-shortcode';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$description = $args['description'];
$content_width = $args['content_width'];
$container_class = '';

if($content_width) :
	switch ($content_width) :
		case 'wide':
	        $container_class = 'container--wide';
	        break;
	    case 'narrow':
	        $container_class = 'container--narrow';
	        break;
	    case 'very-narrow':
	        $container_class = 'container--very-narrow';
	        break;
	    default:
	        $container_class = '';
	        break;
	endswitch;
endif;
?>

<?php if(!empty($description)) : ?>
	<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<div class="container <?php echo $container_class; ?> editor-content">
			<?php echo $description; ?>
		</div>
	</section>
<?php endif; ?>