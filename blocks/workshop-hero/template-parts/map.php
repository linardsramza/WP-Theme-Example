<?php
$lat = $args['latitude'];
$lang = $args['longitude'];

$link_args = [
    'text'   => __('Show directions', THEME_NAME) . ' <i class="fa-solid fa-arrow-right"></i>',
    'link'   => 'https://www.google.com/maps/search/?api=1&query='. $lat .'%2C'. $lang,
    'class'  => 'direction',
    'target' => '_blank'
];
?>
<?php if(!empty($lang) || !empty($lat)) { ?>
    <div class="wh__map">
        <div id="map" class="wh__map--wrapper"
            data-longitude="<?php echo $lang; ?>"
            data-latitude="<?php echo $lat; ?>" >
        </div>
        <?php echo Helper::generate_link($link_args); ?>
    </div>
<?php } ?>

