<?php
    $title = [
        'text'   => __('No workshops found', THEME_NAME),
        'tag'    => 'h3',
        'class'  => 'h4 no-workshops-found',
    ];
    $map_center = get_field('map_center_coordinates', 'options');
    $country = get_field('map_country', 'options') ?? 'Sweden';
?>

<div class="row">
    <div class="row__col order-col">
        <div class="workshops-map__change-order">
            <p class="workshop-count-info"><?php echo __('Showing', THEME_NAME) . '<span class="visible-workshop-count"></span>' . __('of', THEME_NAME) . '<span class="workshop-count"></span>' . __('workshops', THEME_NAME); ?></p>
            <select name="order-form">
                <option value="1" selected><?php _e('Workshops A-Ö', THEME_NAME); ?></option>
                <option value="2"><?php _e('Workshops Ö-A', THEME_NAME); ?></option>
                <option value="3"><?php _e('Distance from you', THEME_NAME); ?></option>
            </select>
        </div>
    </div>
	<div class="row__col row__col--lg-5 map-col">
        <div id="map"
            data-country="<?php echo $country; ?>"
            data-latitude="<?php echo $map_center['latitude'] ?? '0'; ?>"
            data-longitude="<?php echo $map_center['longitude'] ?? '0'; ?>">
        </div>

        <button id="show-map" class="show-map">
            <i class="fa-solid fa-map"></i>
            <span class="open"><?php echo _e('Show map', THEME_NAME); ?><i class="fa-solid fa-chevron-down"></i></span>
            <span class="close"><?php echo _e('Hide map', THEME_NAME); ?><i class="fa-solid fa-chevron-up"></i></span>
        </button>
    </div>
    <div class="row__col row__col--lg-7 workshops-col">
        <div id="workshops">
            <?php echo Helper::generate_header($title); ?>
        </div>
        <button id="load-more-workshops" data-page="1" class="btn btn--primary load-more-workshops"><?php _e('Show more workshops', THEME_NAME); ?></button>
    </div>
</div>