<?php
    $id = $args['workshop_id'];
    $info = get_post_meta($id, 'theme_workshop_details')[0];
    $workshop_permalink = get_permalink($id);

    // Title settings
    $title = [
        'text'     => $args['workshop_title'],
        'tag'      => 'h3',
        'class'    => 'h4',
		'link_url' => $workshop_permalink,
    ];

    // City paragraph
    $city = [];
    if(!empty($info['city'])) {
        $city = [
            'text'   => '<i class="fa-solid fa-location-dot"></i>' . $info['city'],
            'class'  => 'city',
        ];
    }

    // Google link args
    $google_link = [];
    if(!empty($info['longitude']) && !empty($info['latitude'])) {
        $google_link = [
            'text'   => __('Find here', THEME_NAME),
            'link'   => 'https://www.google.com/maps/search/?api=1&query='. $info['latitude'] .'%2C'. $info['longitude'],
            'class'  => 'direction',
            'target' => '_blank'
        ];
    }

    // Booking link
    $booking_link = [];
    if($info['booking_link']) {
        $booking_link = [
            'text'   => __('Book your appointment', THEME_NAME),
            'link'   => $info['booking_link'],
            'class'  => 'booking-link btn btn--yellow btn--small',
        ];
        $book_time_page_type = get_field('book_time_page_type', 'options');
        if($book_time_page_type === 'external-site') {
            $booking_link['target'] = "_blank";
        }
    }

    // Address settings
    $address = [];
    if($info['address_line_1'] && $info['address_line_2']) {
        $address = [
            'text'   => '<i class="fa-solid fa-location-dot"></i> ' . $info['address_line_1'] . '<br>' . $info['address_line_2'],
            'class'  => 'address',
        ];
    }

    // Phone Settings
    $phone_link = [];
    if($info['phone']) {
        $phone_link = [
            'text'   => '<i class="fa-solid fa-phone"></i> ' . $info['phone'],
            'link'   => 'tel:' . $info['phone'],
            'class'  => 'phone',
        ];
    }

    // Email Settings
    $email_link = [];
    if($info['email']) {
        $email_link = [
            'text'   => '<i class="fa-solid fa-envelope"></i> ' . $info['email'],
            'link'   => 'mailto:' . $info['email'],
            'class'  => 'phone',
        ];
    }

    // Workshop link settings
    $workshop_link = [
        'text'   => __('More about the workshop', THEME_NAME),
        'link'   => $workshop_permalink,
        'class'  => 'workshop-link btn btn--blue-outline btn--small',
    ];

    // Working hours
    $working_hours = $info['working_hours'][0];
    $special_opening_hours = $info['special_opening_hours'] ?? [];
    $special_opening_hours_text = $special_opening_hours['text'] ?? '';
    $todays_index = $args['today'][0] - 1;
    $todays_working_hours = $working_hours[$todays_index];
    $opens = $todays_working_hours['opens'] ?? '-';
    $closes = $todays_working_hours['closes'] ?? '-';
    $todays_working_hours = $opens . ' - ' . $closes;
    if($opens == '-' && $closes == '-') {
        $todays_working_hours = __('Closed', THEME_NAME);
    }
    $working_hours_args = [
        'text'  => __('Open today:', THEME_NAME) . ' ' . $todays_working_hours,
        'class' => 'opening-hours'
    ];
?>

<div class="workshops-map__info-card visible"
    data-latitude="<?php echo $info['latitude'] ?? ''; ?>"
    data-longitude="<?php echo $info['longitude'] ?? ''; ?>"
    data-close-to-location
    data-services="<?php echo implode(',', $info['services_ids']); ?>"
    data-city="<?php echo $info['city_id']; ?>"
    data-open-today="<?php echo $opens; ?>"
    data-closes-today="<?php echo $closes; ?>"
    data-open-evening="<?php echo $info['open_evening'] ? 1 : 0; ?>"
    data-open-weekends="<?php echo $info['open_weekends'] ? 1 : 0; ?>"
    data-certificates="<?php echo implode(',', $info['certifications']); ?>">

    <div class="workshops-map__info-card--header">
        <?php
        // if(!$info['hide_rating'] && $info['rating'] > 0) {
        //     echo Helper::show_workshop_rating_stars($info['rating']);
        // }
        ?>
        <div class="first-wrapper">
            <?php echo Helper::generate_header($title); ?>
            <?php echo Helper::generate_paragraph($working_hours_args); ?>
        </div>

        <div class="second-wrapper">
            <div class="location-wrapper">
                <?php echo Helper::generate_paragraph($city); ?>
                <span> | </span>
                <?php echo Helper::generate_link($google_link); ?>
            </div>
            <div class="open-close-wrapper">
                <button class="open-btn"><?php _e('Show info', THEME_NAME) ?> <i class="fa-solid fa-chevron-down"></i></button>
                <button class="close-btn"><?php _e('Close info', THEME_NAME) ?> <i class="fa-solid fa-chevron-up"></i></button>
            </div>
        </div>
    </div>

    <div class="workshops-map__info-card--body">
        <div class="workshops-map__info-card--body--wrapper">
            <div class="row">
                <div class="row__col row__col--md-7">
                    <?php echo Helper::generate_link($booking_link); ?>
                    <?php if($working_hours) {
                        $w_title = [
                            'text'   => __('Opening hours', THEME_NAME),
                            'tag'    => 'h4',
                            'class'  => 'h6',
                        ]; ?>
                        <div class="working-hours">
                            <?php echo Helper::generate_header($w_title); ?>
                            <ul>
                                <?php foreach($working_hours as $i => $h) {
                                    $opens = $h['opens'] ?? '-';
                                    $closes = $h['closes'] ?? '-';
                                    $time_window = __('Closed', THEME_NAME);
                                    $have_time_window = ($opens !== '' && $opens !== '-') && ($closes !== '' && $closes !== '-');
                                    if($have_time_window) {
                                        $time_window = $opens .'-'. $closes;
                                    }

                                    $weekday = __(strtolower(jddayofweek($i, 1)), THEME_NAME);
                                    echo '<li>'. $weekday .' <span>'. $time_window .'</span></li>';
                                } ?>
                                <?php if(!empty($special_opening_hours_text)) {
                                    $starting = strtotime($special_opening_hours['starts']);
                                    $ending = strtotime($special_opening_hours['ends'] . ' +24 hours');
                                    $current_date = time();
                                    if(($starting === false || $ending === false) || ($current_date >= $starting && $current_date <= $ending)) {
                                        echo '<li class="special-opening-hours">'. $special_opening_hours_text .'</li>';
                                    }
                                } ?>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
                <div class="row__col row__col--md-5">
                    <div class="link-wrapper">
                        <?php echo Helper::generate_paragraph($address); ?>
                        <?php echo Helper::generate_link($phone_link); ?>
                        <?php echo Helper::generate_link($email_link); ?>
                    </div>
                    <?php if($info['certifications']) { ?>
                        <div class="certifications">
                            <?php foreach($info['certifications'] as $certificate) {
                                $certificate_image = get_field('logo', 'workshops-certificates_' . $certificate);
                                if($certificate_image) {
                                    echo wp_get_attachment_image( 
                                        $certificate_image['id'], 'medium', false, ['alt' => $certificate_image['alt'], 'title' => $certificate_image['title']]
                                    );
                                }
                            } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="row services-wrap">
                <div class="row__col row__col--lg-8">
                    <?php if($info['services']) {
                        $s_title = [
                            'text'   => __('We specialize in e.g.', THEME_NAME),
                            'tag'    => 'h4',
                            'class'  => 'h6 services-title',
                        ];
                        $services_count = count($info['services']); ?>
                        <?php echo Helper::generate_header($s_title); ?>
                        <ul class="services">
                            <?php foreach($info['services'] as $service) {
                                echo '<li>'. $service .'</li>';
                            } ?>
                        </ul>
                        <?php if($services_count > 5) {
                            echo '<span class="services-wrap__show-all">' . __('Show all services', THEME_NAME) . '<i class="fas fa-angle-down"></i></span>';
                        } ?>
                    <?php } ?>
                </div>
                <div class="row__col row__col--lg-4">
                    <?php echo Helper::generate_link($workshop_link); ?>
                </div>
            </div>
            <div class="open-close-wrapper">
                <button class="close-btn close-btn--mobile"><?php _e('Close info', THEME_NAME) ?> <i class="fa-solid fa-chevron-up"></i></button>
            </div>
        </div>
        </div>
    </div>
</div>