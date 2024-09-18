<?php

/**
 * Booking iframe Block Template.
 */

$id = '';
$className = 'block booking-iframe';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$iframe_source_link = $args['iframe_source_link'];
$iframe_source_link = rtrim($iframe_source_link, '/') . '/';

$workshop_id = sanitize_text_field($_GET['workshop'] ?? '');
$services = sanitize_text_field($_GET['services'] ?? '');
$platenumber = sanitize_text_field($_GET['platenumber'] ?? '');
$country = sanitize_text_field($_GET['country'] ?? '');
$km = sanitize_text_field($_GET['km'] ?? '');
$workshops = sanitize_text_field($_GET['workshops'] ?? '');

$params_arr = array();

if(!empty($workshop_id)) :
	array_push($params_arr, 'workshop='. $workshop_id);
endif;
if(!empty($services)) :
	array_push($params_arr, 'services='. $services);
endif;
if(!empty($platenumber)) :
	array_push($params_arr, 'platenumber='. $platenumber);
endif;
if(!empty($country)) :
	array_push($params_arr, 'country='. $country);
endif;
if(!empty($km)) :
	array_push($params_arr, 'km='. $km);
endif;
if(!empty($workshops)) :
	array_push($params_arr, 'workshops='. $workshops);
endif;

if($params_arr) :
	$iframe_source_link .= '?'. implode('&', $params_arr);
endif;

?>
<?php if(!empty($iframe_source_link)) : ?>
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
			html body main .block.booking-iframe {
				height: 100%;
			}
			html body main .block.booking-iframe iframe {
				height: 100%;
				width: 100%;
			}
		</style>
		<iframe src="<?php echo $iframe_source_link; ?>" frameborder="0"></iframe>
	</section>
<?php endif; ?>