<?php

// Get regions
$region_taxonomy = 'workshops-regions';
$regions = get_terms([
    'taxonomy' => $region_taxonomy,
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
]);

?>

<?php if ($regions) : ?>

    <div class="workshops-map__regions accordion">
        <?php foreach ($regions as $region) : 
            $region_id = $region->term_id;
            $region_name = $region->name;
            $region_cities = get_field('city_pages', $region_taxonomy . '_' . $region_id);

            if(!$region_cities) {
                continue;
            }

            usort( $region_cities, Helper::sort_acf_by_name('city_name') );
        ?>
            <div class="workshop-map__regions--item accordion__item">
                <div class="accordion__title" name="<?php echo $region_name; ?>">
                    <h4 class="title"><?php echo $region_name; ?></h4>
                    <i class="fas fa-plus accordion__title--plus"></i>
                    <i class="fas fa-minus accordion__title--minus"></i>
                </div>
                <div class="accordion__description">
                    <div class="accordion__description--inner">
                        <?php foreach ($region_cities as $city) : 
                            $city_name = $city['city_name'];
                            $city_page_id = $city['city_page'];
                            $city_link = get_permalink($city_page_id);
                            if(!$city_link || !$city_page_id || get_post_status($city_page_id) !== 'publish') {
                                continue;
                            }
                        ?>
                            <a href="<?php echo $city_link; ?>" class="workshops-map__regions--city">
                                <i class="fas fa-location-dot"></i>
                                <?php echo $city_name; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>