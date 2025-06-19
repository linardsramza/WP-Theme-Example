<?php 
$social = get_field('social_networks', 'options');

if ($social) : 

?>

    <div class="social">
        <?php foreach( $social as $key => $item ) : 
            $social_network = $item['social_network'];
            $social_network_link = $item['social_network_link'];
            ?>

            <?php 
            if($social_network && $social_network_link) :
                echo Helper::generate_social_network_icon($social_network, $social_network_link);
            endif;
            ?>

        <?php endforeach; ?>
    </div>

<?php endif; ?>