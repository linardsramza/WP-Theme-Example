<?php
    // Styling
    $bg_img = $args['bg_img'];
    $bg_img_mob = $args['bg_img_mob'];
    $bg_filter = $args['bg_filter'];
    if ($bg_img) : ?>
        <style>
            <?php echo '#' . $args['id'] . ' .wh__info-card'; ?> {
                background-image: url(<?php echo $bg_img; ?>);
            }
            <?php if ($bg_img_mob) : ?>
                @media screen and (max-width: 768px) {
                    <?php echo '#' . $args['id'] . ' .wh__info-card'; ?> {
                        background-image: url(<?php echo $bg_img_mob; ?>);
                    }
                }
            <?php endif; ?>
        </style>
    <?php endif;

    // Content
    $certificates = $args['certificates'];
    $title_args = [
        'text' => $args['title'],
        'tag' => $args['title_lvl'],
        'class' => 'title h1',
    ];

    $paragraph_args = array(
        'text'  => $args['address'],
        'class' => 'address'
    );

    $phone = $args['phone'];
    $phone_args =[];
    if($phone) {
        $phone = explode(',', $phone)[0];
        $phone_link = Helper::get_phone_link($phone);
        $phone_args = [
            'text'   => '<i class="fa-solid fa-phone"></i> ' . $phone,
            'link'   => 'tel:' . $phone_link,
            'title'  => $phone,
        ];
    }

    $email_args = [
        'text'   => '<i class="fa-solid fa-envelope"></i> ' .  $args['email'],
        'link'   => 'mailto:' . $args['email'],
        'title'  => $args['email'],
    ];

    $booking_args = [
        'text'   => __('Book now', THEME_NAME),
        'link'   => $args['link_to_booking'],
        'class'  => 'btn btn--yellow',
        'title'  => __('Book now', THEME_NAME),
    ];
    $book_time_page_type = get_field('book_time_page_type', 'options');
    if($book_time_page_type === 'external-site') {
        $booking_args['target'] = "_blank";
    }

?>

<div class="wh__info-card <?php echo $bg_filter ? 'has-filer' : ''; ?> text-white">

    <?php if($certificates) { ?>
        <div class="wh__info-card--certificates">
            <?php foreach($certificates as $certificate) {
                $certificate_img = get_field('logo', 'workshops-certificates_' . $certificate->term_id) ?? false;
                if($certificate_img) {
                    $img_args = [];
                    $img_args['image'] = $certificate_img;
                    $img_args['attributes'] = [
                        'title' => $certificate_img['title'],
                        'alt' => $certificate_img['alt'],
                    ];
                    echo Helper::generate_image($img_args);
                }
            } ?>
        </div>
    <?php } ?>

    <div class="row">
		<div class="row__col row__col--lg-7">
            <?php
                // if(!$args['hide_rating'] && $args['rating'] > 0 ) {
                //     echo Helper::show_workshop_rating_stars($args['rating']);
                // }
            ?>
            <?php echo Helper::generate_header($title_args); ?>
            <?php echo Helper::generate_paragraph($paragraph_args); ?>

            <div class="wh__info-card--contact-info">
                <?php echo Helper::generate_link($phone_args); ?>
                <?php echo Helper::generate_link($email_args); ?>
            </div>
        </div>
        <div class="row__col row__col--lg-5 wh__info-card--btn-wrapper">
            <?php echo Helper::generate_link($booking_args); ?>
        </div>
    </div>

</div>