<?php

/**
 * Iframe Block Template.
 */

$id = '';
$className = 'block iframe';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

$iframe = $args['iframe'];

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<style>
		html {
			height: 100%;
		}
		html body {
			height: 100%;
		}
		html body main {
			height: 100%;
		}
		html body main .block.iframe {
			height: calc(100% + 104px);
		}
		html body main .block.iframe iframe {
			height: 100%;
			width: 100%;
		}
	</style>
	<?php if($iframe){ echo $iframe; } ?>
</section>