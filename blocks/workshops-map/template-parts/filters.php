<?php
    $services = get_terms([
        'taxonomy' => 'services',
        'hide_empty' => true,
    ]);
    $cities = get_terms([
        'taxonomy' => 'workshops-cities',
        'hide_empty' => true,
    ]);
    $certificates = get_terms([
        'taxonomy' => 'workshops-certificates',
        'hide_empty' => true,
    ]);
    $zip_code = isset($_GET['zip-code']) ? sanitize_text_field($_GET['zip-code']) : '';
?>

<div class="workshops-map__filters">
    <form id="workshop_filters">

        <?php // Post code filter ?>
        <div class="workshops-map__filters--zip-code-search">
            <div class="input-wrapper">
                <input id="search-field" name="search" type="text" placeholder="<?php echo _e('Search by workshop or zip code', THEME_NAME); ?>" value="<?php echo $zip_code; ?>">
                <button id="zip-code-search-btn" class="btn btn--primary"><i class="fa-solid fa-magnifying-glass"></i><span class="text"><?php echo _e('Find your workshop', THEME_NAME); ?></span></button>
            </div>
            <div class="checkbox-wrapper search-by-location-wrapper">
                <input type="checkbox" id="search-by-location" name="search-by-location" value="search-by-location">
                <label for="search-by-location"><?php echo _e('Search by location', THEME_NAME); ?></label>
            </div>
            <button id="get-location" class="btn btn--blue-outline get-location"><i class="fa-solid fa-crosshairs"></i><?php echo _e('Close to you', THEME_NAME); ?></button>
            <button id="mobile-show-filters" class="btn btn--blue-outline show-filters"><i class="fa-regular fa-sliders"></i><?php echo _e('Filter', THEME_NAME); ?></button>
        </div>

        <div class="workshops-map__filters--wrapper">
            <p class="strong"><?php _e('Filter by', THEME_NAME); ?></p>

            <?php // Services filter ?>
            <?php if($services) { ?>
                <div class="filter-dropdown">
                    <div class="filter-btn btn btn--blue-outline btn--small">
                        <i class="fa-solid fa-wrench"></i>
                        <?php _e('Services', THEME_NAME); ?>
                        <span class="count"></span>
                        <i class="fa-solid fa-angle-down"></i>
                    </div>
                    <ul class="list-style-none workshops-map__filters--dropdown">
                        <li class="search-wrapper">
                            <input class="workshops-map__filters--search" type="text" placeholder="<?php _e('Search', THEME_NAME); ?>">
                            <i class="fa-regular fa-magnifying-glass"></i>
                        </li>
                        <?php foreach($services as $service) {
                            $service_id = 'service-' . $service->term_id; ?>
                            <li class="checkbox-wrapper">
                                <input type="checkbox" id="<?php echo $service_id; ?>" name="service" value="<?php echo $service->term_id; ?>">
                                <label for="<?php  echo $service_id; ?>"><?php echo $service->name; ?></label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <?php // City filter ?>
            <?php if($cities) { ?>
                <div class="filter-dropdown">
                    <div class="filter-btn btn btn--blue-outline btn--small">
                        <i class="fa-sharp fa-solid fa-location-dot"></i>
                        <?php _e('City', THEME_NAME); ?>
                        <span class="count"></span>
                        <i class="fa-solid fa-angle-down"></i>
                    </div>
                    <ul class="list-style-none workshops-map__filters--dropdown">
                        <li class="search-wrapper">
                            <input class="workshops-map__filters--search" type="text" placeholder="<?php _e('Search', THEME_NAME); ?>">
                            <i class="fa-regular fa-magnifying-glass"></i>
                        </li>
                        <?php foreach($cities as $city) {
                            $city_id = 'city-' . $city->term_id; ?>
                            <li class="checkbox-wrapper">
                                <input type="checkbox" id="<?php echo $city_id; ?>" name="city" value="<?php echo $city->term_id; ?>">
                                <label for="<?php  echo $city_id; ?>"><?php echo $city->name; ?></label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <?php // Opening hours filter  ?>
            <div class="filter-dropdown">
                <div class="filter-btn btn btn--blue-outline btn--small">
                    <i class="fa-solid fa-clock"></i>
                    <?php _e('Opening hours', THEME_NAME); ?>
                    <span class="count"></span>
                    <i class="fa-solid fa-angle-down"></i>
                </div>
                <ul class="list-style-none workshops-map__filters--dropdown">
                    <li class="checkbox-wrapper">
                        <input type="checkbox" id="opening-hours-1" name="opening-hours" value="open-now">
                        <label for="opening-hours-1"><?php echo _e('Open now', THEME_NAME) ?></label>
                    </li>
                    <li class="checkbox-wrapper">
                        <input type="checkbox" id="opening-hours-2" name="opening-hours" value="open-evenings">
                        <label for="opening-hours-2"><?php echo _e('Open evenings', THEME_NAME) ?></label>
                    </li>
                    <li class="checkbox-wrapper">
                        <input type="checkbox" id="opening-hours-3" name="opening-hours" value="open-weekends">
                        <label for="opening-hours-3"><?php echo _e('Open weekends', THEME_NAME) ?></label>
                    </li>
                </ul>
            </div>
            <?php // Certificates filter  ?>
            <?php if($certificates) { ?>
                <div class="filter-dropdown">
                    <div class="filter-btn btn btn--blue-outline btn--small">
                        <i class="fa-solid fa-circle-check"></i>
                        <?php _e('Certification', THEME_NAME); ?>
                        <span class="count"></span>
                        <i class="fa-solid fa-angle-down"></i>
                    </div>
                    <ul class="list-style-none workshops-map__filters--dropdown">
                        <?php foreach($certificates as $certificate) {
                            $certificate_id = 'certificate-' . $certificate->term_id; ?>
                            <li class="checkbox-wrapper">
                                <input type="checkbox" id="<?php echo $certificate_id; ?>" name="certificates" value="<?php echo $certificate->term_id; ?>">
                                <label for="<?php  echo $certificate_id; ?>"><?php echo $certificate->name; ?></label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>

            <?php // Mobile filter buttons ?>
            <div class="mobile-btn-wrapper">
                <button id="mobile-clear-filter" class="clear-filter"><?php _e('Clear all filters', THEME_NAME); ?></button>
                <button id="mobile-close-filter" class="btn btn--primary"><?php echo __('Show', THEME_NAME) . ' <span class="count"></span> ' . __('workshops', THEME_NAME)  ?></button>
            </div>
        </div>

    </form>
    <div class="workshops-map__filters--selected-items">
        <span id="clear-all-filters"><?php _e('Clear', THEME_NAME) ?></span>
    </div>
</div>
