<?php
    $hours = $args['working_hours'];
    $p_args = [
        'text'  => '<i class="fa-solid fa-clock"></i> '. __('Opening hours', THEME_NAME),
        'class' => 'h5'
    ];
    $hide_lunch_time = get_field('hide_lunch_time', 'options');
?>

<div class="wh__hours opened">

    <div class="wh__hours--header">
        <?php echo Helper::generate_paragraph($p_args); ?>
        <div class="icons">
            <i class="close fa-solid fa-minus"></i>
            <i class="open fa-solid fa-plus"></i>
        </div>
    </div>

    <?php if($hours) { ?>
        <div class="wh__hours--wrappers">
            <ul>
                <?php foreach($hours as $i => $h) {
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
                            echo '<li class="lunch-time"><b>'. __('Closed for lunch', THEME_NAME) .':</b> <span>'. $time_window .'</span></li>';
                            break;
                        case 'special_opening_hours':
                            if($h) {
                                $starting = strtotime($hours['special_opening_hours_start']);
                                $ending = strtotime($hours['special_opening_hours_end'] . ' +24 hours');
                                $current_date = time();
                                if(($starting === false || $ending === false) || ($current_date >= $starting && $current_date <= $ending)) {
                                    echo '<li class="special-opening-hours"><em>'. $h .'</em></li>';
                                }
                            }
                            break;
                        case 'special_opening_hours_start':
                            break;
                        case 'special_opening_hours_end':
                            break;
                        default:
                            $weekday = __($i, THEME_NAME);
                            echo '<li><b>'. ucfirst($weekday) .':</b> <span>'. $time_window .'</span></li>';
                    endswitch;
                }; ?>
            </ul>
        </div>
    <?php } ?>
</div>