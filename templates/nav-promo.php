<?php
// Variables
$is_mobile  = $args['is_mobile'];
$promo_count = $args['promo_count'];
$promo_loop = $args['promo_loop'];
$has_promo_color_filter = get_field('has_promo_color_filter', 'options');

$promo_class = '';
if ($promo_count == 2) :
    $promo_class = 'promo--two';
elseif ($promo_count == 3) :
    $promo_class = 'promo--three';
endif;
?>

<div class="promo <?php echo $is_mobile ? 'promo__mobile' : ''; ?> <?php echo !empty($promo_class) ? $promo_class : ''; ?> <?php echo $has_promo_color_filter ? 'promo--has-filter' : ''; ?>">
    <?php foreach($promo_loop as $promo_item) : 

        $promo_title = Helper::generate_header(array(
            'text'        => $promo_item['title'],
            'tag'         => 'h3',
            'class'       => 'h3 promo__title',
        ));
        
        $promo_button = Helper::generate_acf_link(array(
            'link'            => $promo_item['page'],
            'class'           => 'promo__button',
        ));

        $promo_background = '';
        if ($promo_item['image']) :
            $promo_background = 'background-image: url('. $promo_item['image'] .')';
        endif;
        ?>
        
        <a href="<?php echo $promo_item['page']['url']; ?>" class="promo__item" <?php echo !empty($promo_background) ? 'style="'. $promo_background .'"' : ''; ?>>
            <?php echo $promo_title; ?>
            <div class="promo__button"><?php echo $promo_item['page']['title']; ?></div>
        </a>
    <?php endforeach; ?>
</div>