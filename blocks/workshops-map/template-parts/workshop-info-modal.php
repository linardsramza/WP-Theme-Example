<?php 
    $id = $args['workshop_id'];
    $title = [
        'text'   => get_the_title($id),
        'tag'    => 'p',
        'class'  => 'h6',
        'link'   => get_permalink($id),
    ];
    $address_field = get_field('workshop_address', $id);
    $address = [
        'text'  => $address_field['address'] . ',<br>' . $address_field['zip_code'] . ' ' . $address_field['city']
    ];
    $link = [
        'text'   => __('More about the workshop' ,THEME_NAME) . ' <i class="fa-solid fa-arrow-right"></i>',
        'link'   => get_permalink($id),
    ];
?>

<div class="workshops-map__modal">
    <?php
        echo Helper::generate_header($title);
        echo Helper::generate_paragraph($address);
        echo Helper::generate_link($link);
    ?>
</div>