<?php

/**
 * Content Block Template.
 */

$id = '';
$className = 'block content';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$content = $args['content'];
$content_width = $args['content_width'];

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

<?php if(!empty($content)) : ?>
	<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<div class="container <?php echo $container_class; ?> editor-content">
			<?php echo $content; ?>
		</div>
	</section>
<?php endif; ?>