<?php
// Variables
$is_mobile  = $args['is_mobile'];
?>


<div class="site-header__search-bar <?php echo $is_mobile ? 'site-header__search-bar--mobile' : 'site-header__search-bar--desktop'; ?>">
    <form method="get" class="site-header__searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <input type="text" class="field" name="s" value="<?php echo get_search_query(); ?>" placeholder="<?php _e('Search at theme.se', THEME_NAME); ?>" />
        <button type="submit" name="search-submit" class="btn btn--primary">
            <?php _e( 'Search', THEME_NAME ); ?>
        </button>
    </form>
</div>