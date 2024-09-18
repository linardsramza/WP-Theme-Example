<?php 
$id = get_the_ID();
$address_info = get_field('workshop_address', $id);
$contact_info = get_field('contact_info', $id);
$working_hours = get_field('working_hours', $id);
$hide_lunch_time = get_field('hide_lunch_time', 'options');

$address = '';

$title = Helper::generate_header(array(
	'text'        => get_the_title(),
	'tag'         => 'h3',
	'class'       => 'h3 werehouse__title',
));

if($address_info) :
    $address .= isset($address_info['address']) ? $address_info['address'] . ', ' : '';
    $address .= isset($address_info['zip_code']) ? $address_info['zip_code'] . ' ' : '';
    $address .= isset($address_info['city']) ? $address_info['city'] : '';
endif;

$longitude = isset($address_info['longitude']) ? $address_info['longitude'] : '';
$latitude = isset($address_info['latitude']) ? $address_info['latitude'] : '';

$phone = isset($contact_info['phone_number']) ? $contact_info['phone_number'] : '';
$email = isset($contact_info['email']) ? $contact_info['email'] : '';

$phone_link = Helper::get_phone_link($phone);

$email = Helper::generate_link(array(
    'text'   => '<i class="fa-solid fa-envelope"></i> ' .  $email,
    'link'   => 'mailto:' . $email,
    'title'  => $email,
));

$phone = Helper::generate_link(array(
    'text'   => '<i class="fa-solid fa-phone"></i> ' . $phone,
    'link'   => 'tel:' . $phone_link,
    'title'  => $phone,
));

$address = Helper::generate_paragraph(array(
    'text'  => $address,
    'class' => 'address'
));

$werehouse_link = Helper::generate_link(array(
    'text'   => __('More info about the express warehouse', THEME_NAME),
    'link'   => get_permalink(),
    'class'  => 'btn btn--primary',
));
$directions_link = Helper::generate_link(array(
    'text'   => __('Find here', THEME_NAME) . ' <i class="fa-solid fa-arrow-right"></i>',
    'link'   => 'https://www.google.com/maps/search/?api=1&query='. $latitude .'%2C'. $longitude,
    'class'  => 'direction',
    'target' => '_blank'
));

$hours_header = Helper::generate_paragraph(array(
    'text'  => __('Opening hours', THEME_NAME),
    'class' => 'h6'
));
// name of warehouse, address, link to map, phone, email, all opening hours. Remove the link to the single warehouse page.
?>
<div class="werehouses-region-list__regions--werehouse">
    <div class="werehouses-region-list__regions--content">
        <?php echo $title; ?>
        <?php echo $address; ?>
        <div class="werehouses-region-list__regions--links">
            <?php echo $phone; ?>
            <?php echo $email; ?>
            <?php echo $directions_link; ?>
        </div>

        <?php if($working_hours) { 
            ?>

        <div class="wh__hours--header">
            <?php echo $hours_header; ?>
        </div>
        <div class="wh__hours--wrappers">
            <ul>
                <?php foreach($working_hours as $i => $h) {
                    $opens = $h['opens'] ?? '-';
                    $closes = $h['closes'] ?? '-';
                    $time_window = __('Closed', THEME_NAME);
                    $have_time_window = ($opens !== '' && $opens !== '-') && ($closes !== '' && $closes !== '-');
                    if($have_time_window) {
                        $time_window = $opens .' - '. $closes;
                    }
                    switch ($i) :
                        case 'launch_time':
                            if($hide_lunch_time === true) {
                                break;
                            }
                            $starts = $h['starts'] ?? '-';
                            $ends = $h['ends'] ?? '-';
                            $time_window = $starts .' - '. $ends;
                            echo '<li class="lunch-time"><span>'. __('Closed for lunch', THEME_NAME) .':</span> <span>'. $time_window .'</span></li>';
                            break;
                        case 'special_opening_hours':
                            if($h) {
                                $starting = strtotime($working_hours['special_opening_hours_start']);
                                $ending = strtotime($working_hours['special_opening_hours_end']);
                                $current_date = time();
                                if(($starting === false || $ending === false) || ($current_date >= $starting && $current_date <= $ending)) {
                                    echo '<li><em>'. $h .'</em></li>';
                                }
                            }
                            break;
                        case 'special_opening_hours_start':
                            break;
                        case 'special_opening_hours_end':
                            break;
                        default:
                            $weekday = __($i, THEME_NAME);
                            echo '<li><span>'. ucfirst($weekday) .':</span> <span>'. $time_window .'</span></li>';
                    endswitch;
                }; ?>
            </ul>
        </div>
    <?php } ?>
    </div>
</div>