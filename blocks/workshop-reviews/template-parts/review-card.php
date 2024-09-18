<?php
$starts = $args['review']['rating'] ?? 0;
$text = $args['review']['review_text'] ?? '';
if($text !== '') { ?>
    <div class="workshop-reviews__card">
        <div class="workshop-reviews__card--stars">
            <?php if($starts > 0 ) {
                echo Helper::show_workshop_rating_stars($starts);
            } ?>
        </div>
        <div class="workshop-reviews__card--text">
            <p><?php echo $text; ?></p>
        </div>
    </div>
<?php }
