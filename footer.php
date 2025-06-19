<?php 

$footer_top = get_field('footer_top', 'options'); 
$footer_menu = get_field('footer_menu', 'options');
$documents_menu = get_field('documents_menu', 'options');
$copyright = get_field('copyright_text', 'options') ?? '';
$social_title = get_field('social_header', 'options');
$social_description = get_field('social_description', 'options');
$has_dashed_line = get_field('has_dashed_line', 'options');
$chatbox_code = get_field('chatbox_code', 'options', false);
$has_subscribe_form = get_field('has_subscribe_form', 'options');

$social_title = Helper::generate_header(array(
	'text'        => $social_title,
	'tag'         => 'h4',
	'class'       => 'h4 social__title',
));

$social_description = Helper::generate_paragraph(array(
    'text'  => $social_description,
    'class' => 'social__description'
));

if(is_page()) :
    $hide_footer = get_field('hide_footer') ?? false;
else:
    $hide_footer = false;
endif;

?>

<?php if(!$hide_footer) : ?>
    <footer class="site-footer <?php echo $has_dashed_line ? 'site-footer--dashed-line' : ''; ?>">
        <div class="container">

            <?php if ($footer_top) : ?>
                    <div class="site-footer__top">

                    <?php foreach($footer_top as $item) : 
                        $link = Helper::generate_acf_link(array(
                            'link'            => $item['link'],
                            'class'           => 'site-footer__top--title',
                        )); ?>
                        <div class="site-footer__top--item">
                            <?php echo $link; ?>
                            <div class="site-footer__top--description">
                                <?php echo $item['description']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    </div>
            <?php endif; ?>

            <?php if ($footer_menu) : ?>
                <div class="menu-list menu-list__accordion">

                    <?php foreach($footer_menu as $item) : 
                        $link_title = Helper::generate_acf_link(array(
                            'link'            => $item['title'],
                            'class'           => 'menu-list__item--link',
                        ));
                        ?>

                        <div class="menu-list__item accordion__item">
                            <div class="menu-list__item--title accordion__title">
                                <?php echo $link_title; ?>
                                <div class="accordion__title--toggle">
                                    <i class="fas fa-plus accordion__title--plus"></i>
                                    <i class="fas fa-minus accordion__title--minus"></i>
                                </div>
                            </div>

                            <?php if ($item['menu']) : ?>
                                <div class="menu-list__menu accordion__description">
                                    <?php foreach($item['menu'] as $menu_item) : 
                                        $link = Helper::generate_acf_link(array(
                                            'link'            => $menu_item['menu_item'],
                                            'class'           => 'menu-list__menu--item',
                                        ));
                                        echo $link;
                                    endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="row site-footer__bottom">

                <?php if ($has_subscribe_form) : ?>
                    <div class="row__col row__col--lg-5">
                        <?php get_template_part('templates/subscribe'); ?>
                    </div>
                <?php endif; ?>

                <div class="row__col row__col--lg-5 <?php echo $has_subscribe_form ? 'row__col--push-2 row__col--push' : ''; ?>">
                    <?php echo $social_title; ?>
                    <?php echo $social_description; ?>
                    <?php get_template_part('templates/social'); ?>
                </div>

            </div>

            <?php if ($documents_menu || $copyright !== '') : ?>
                <div class="row">
                    <div class="row__col">
                        <div class="documents-list">
                            <?php echo $copyright !== '' ? '<p class="documents-list--item">' . $copyright . '</p>' : ''; ?>
                            <?php foreach($documents_menu as $link) : 
                                $link = Helper::generate_acf_link(array(
                                    'link' => $link['link'],
                                    'class' => 'documents-list--item',
                                ));

                                echo $link;
                            endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </footer>
<?php endif; ?>
<?php wp_footer(); ?>
<?php if (!empty($chatbox_code)) : ?>
    <div id="puzzle_chat_placeholder"></div>
    <script src="https://chat.puzzel.com/Content/Client/js/jquery-intelecomchat.libs.latest.min.js"></script>
    <script src="https://chat.puzzel.com/Content/Client/js/jquery-intelecomchat.latest.min.js"></script>
    <link rel="stylesheet" href="https://chat.puzzel.com/Content/Client/css/intelecom-light.css" />

    <script type="text/javascript">
        <?php echo $chatbox_code; ?>
    </script>

    <style type="text/css">
            [data-puzzel-chat] {
                font-family: 'Open Sans', sans-serif;
                z-index: 9999;
            }
        </style>
<?php endif; ?>
</body>
</html>