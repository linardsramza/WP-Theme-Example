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
    $phone_args = [];
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
?>

<div class="wh__info-card <?php echo $bg_filter ? 'has-filer' : ''; ?> text-white">
    <div class="wh__info-card--title">
        <?php echo Helper::generate_header($title_args); ?>
        <?php echo Helper::generate_paragraph($paragraph_args); ?>
    </div>

    <div class="wh__info-card--contact-info">
        <?php echo Helper::generate_link($phone_args); ?>
        <?php echo Helper::generate_link($email_args); ?>
    </div>
</div>